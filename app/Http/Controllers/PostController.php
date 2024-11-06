<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\KeywordGenerator;
use Illuminate\Support\Facades\App;

class PostController extends Controller
{

    protected $keywordGenerator;

    public function __construct(KeywordGenerator $keywordGenerator)
    {
        $this->keywordGenerator = $keywordGenerator;
    }

    public function index()
    {
        $postsList = Post::paginate(18);
        return view('posts.index', compact('postsList'));
    }

    public function showAll()
    {
        // Получаем список постов, отфильтрованных по языку, статусу и загружаем связанные категории
        $posts = Post::where('language', app()->getLocale())
            ->where('status', 'active')
            ->with('category')
            ->paginate(18);

        // Добавляем сокращенный контент для постов
        foreach ($posts as $post) {
            $post->trimmedContent = $this->getTrimmedContent($post->content, app()->getLocale());
        }

        // Формируем хлебные крошки
        $breadcrumbs = [
            ['title' => __('titles.all_posts'), 'url' => '']
        ];

        return view('posts.showAll', compact('posts', 'breadcrumbs'));
    }

    private function getTrimmedContent($content, $locale)
    {
        $limit = match ($locale) {
            'en' => 100,
            'ru' => 120,
            'pl' => 110,
            default => 100,
        };

        // Убираем HTML-теги из текста
        $plainContent = strip_tags($content);

        // Убираем специальные символы, такие как &nbsp; и другие
        $plainContent = preg_replace('/&nbsp;|•|[^\p{L}\p{N}\s,.!?:;"\'-]+/u', ' ', $plainContent);

        // Сокращаем текст с добавлением "..."
        $ending = '...';
        $trimmedContent = mb_strlen($plainContent) > $limit
            ? mb_substr($plainContent, 0, mb_strripos(mb_substr($plainContent, 0, $limit), ' ')) . $ending
            : $plainContent;

        // Убираем лишние пробелы
        return trim(preg_replace('/\s+/', ' ', $trimmedContent));
    }

    public function showUserPosts()
    {
        $postsList = Post::where('autor_id', auth()->user()->id)->paginate(18);
        return view('posts.index', compact('postsList'));
    }

    public function show(Request $request, $alias)
    {
        // Получаем текущую локаль приложения
        $locale = App::getLocale();

        // Загружаем пост с комментариями и связями
        $post = Post::with([
            'comments' => function ($query) {
                $query->where('status', 'approved')
                    ->whereNull('parent_id') // Только комментарии верхнего уровня
                    ->with('user') // Пользователь, оставивший комментарий
                    ->with(['replies' => function ($query) {
                        $query->with('user'); // Пользователь, оставивший ответ
                    }])
                    ->orderBy('created_at', 'desc'); // Сортировка по дате создания
            },
            'likes', // Загружаем лайки для поста
        ])
            ->where('alias', $alias)
            ->where('language', $locale)
            ->firstOrFail();

        // Увеличиваем количество просмотров
        $post->increment('views_count');

        // Получаем уровень пользователя
        $user = auth()->user();
        $user_level = $user ? $user->profile->level : 0;

        // Обрезаем заголовок для хлебных крошек
        $trimmedTitle = $this->trimTitle($post->title, $locale);

        // Формируем хлебные крошки
        $breadcrumbs = [
            ['title' => __('titles.all_posts'), 'url' => localized_url('post.showAll')],
            ['title' => $trimmedTitle, 'url' => ''],
        ];

        // SEO данные
        $seo_description = $post->seo_description ?? '';
        $seo_keywords = $post->keywords ?? '';

        // Передаём необходимые данные в представление
        return view('posts.show', compact('post', 'user_level', 'breadcrumbs', 'seo_description', 'seo_keywords'));
    }

    /**
     * Обрезает заголовок для хлебных крошек.
     *
     * @param string $title
     * @param string $locale
     * @return string
     */
    private function trimTitle($title, $locale)
    {
        $limit = match ($locale) {
            'en' => 70,
            'ru' => 60,
            'pl' => 60,
            default => 70,
        };

        $ending = '...';

        if (mb_strlen($title) > $limit) {
            $cutOff = mb_strripos(mb_substr($title, 0, $limit), ' ');
            $trimmed = mb_substr($title, 0, $cutOff) . $ending;
        } else {
            $trimmed = $title;
        }

        return $trimmed;
    }

    public function create()
    {
        $categories = PostCategory::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $userLevel = $user->profile->level ?? 0;

        if ($userLevel < 3) {
            return redirect()->back()->with('error', 'Your level is too low to create a post. You need a level 3.');
        }

        // Определение правил на основе роли пользователя
        $statusRules = 'in:under_review'; // По умолчанию, если нет специальных ролей
        if (auth()->user()->hasAnyRole(['review_editor', 'administrator'])) {
            $statusRules = 'in:hidden,under_review,deleted,active';
            $request->validate([
                'status' => 'required|' . $statusRules,
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:5000',
                'keywords' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:1000',
                'alias' => 'nullable|string|unique:posts',
                'language' => 'required|string|max:2',
                'category_id' => 'required|integer|exists:post_categories,id',
            ]);

            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->status = $request->status;
            $post->alias = $request->alias ?? Str::slug($request->title);
            $post->keywords = $request->keywords ?? $this->keywordGenerator->generate($request->content, $request->language);
            $post->seo_description = $request->seo_description ?? Str::limit(strip_tags($request->content), 160, '...');
            $post->author_id = $user->id;
            $post->language = $request->language;
            $post->category_id = $request->category_id;
            $post->save();
        } else {
            $request->validate([
                'status' => 'required|' . $statusRules,
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:5000',
                'category_id' => 'required|integer|exists:post_categories,id',
            ]);
            $post = new Post();
            $post->author_id = $user->id;
            $post->status = 'under_review';
            $post->language = app()->getLocale();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->alias = Str::slug($request->title);
            $post->keywords = $this->keywordGenerator->generate($request->content, $request->language);
            $post->seo_description = Str::limit(strip_tags($request->content), 160, '...');
            $post->category_id = $request->category_id;
            $post->save();
        }

        if ($request->hasFile('image')) {
            $imageName = $post->id . '_' . time() . '.' . $request->image->extension();
            $request->image->storeAs('posts_images', $imageName, 'public');
            $post->image = $imageName;
            $post->save();
        }

        return redirect()->route('post.index')->with('success', 'Post created successfully and submitted for review.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        // Проверка прав на редактирование
        if ($post->author_id !== auth()->id() && !auth()->user()->hasAnyRole(['review_editor', 'administrator'])) {
            return redirect()->back()->with('error', 'You do not have permission to edit this post.');
        }

        // Определение правил на основе роли пользователя
        $statusRules = 'in:under_review'; // По умолчанию, если нет специальных ролей
        if (auth()->user()->hasAnyRole(['review_editor', 'administrator'])) {
            $statusRules = 'in:hidden,under_review,deleted,active';
            $request->validate([
                'status' => 'required|' . $statusRules,
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:5000',
                'keywords' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:1000',
                'alias' => 'nullable|string|unique:posts,alias,' . $post->id, // Исключаем текущий пост из проверки',
                'language' => 'required|string|max:2',
                'category_id' => 'required|integer|exists:post_categories,id',
            ]);

            $post->title = $request->title;
            $post->content = $request->content;
            $post->status = $request->status;
            $post->alias = $request->alias ?? Str::slug($request->title);
            $post->keywords = $request->keywords ?? $this->keywordGenerator->generate($request->content, $request->language);
            $post->seo_description = $request->seo_description ?? Str::limit(strip_tags($request->content), 160, '...');
            $post->reviewer_id = $user->id;
            $post->language = $request->language;
            $post->category_id = $request->category_id;
        } else {
            if ($post->author_id != $user->id) {
                return redirect()->route('post.index')->with('error', 'You can\'t edit someone else\'s post.');
            }
            $request->validate([
                'status' => 'required|' . $statusRules,
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:5000',
                'category_id' => 'required|integer|exists:post_categories,id',
            ]);
            $post->status = 'under_review';
            $post->language = app()->getLocale();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->alias = Str::slug($request->title);
            $post->keywords = $this->keywordGenerator->generate($request->content, $request->language);
            $post->seo_description = Str::limit(strip_tags($request->content), 160, '...');
            $post->category_id = $request->category_id;
        }


        if ($request->hasFile('image')) {
            // Удаление старой картинки, если она есть
            if ($post->image) {
                Storage::delete('public/posts_images/' . $post->image);
            }
            $imageName = $post->id . '_' . time() . '.' . $request->image->extension();
            $request->image->storeAs('posts_images', $imageName, 'public');
            $post->image = $imageName;
        }

        $post->save();

        return redirect()->route('post.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->image) {
            Storage::delete('public/posts_images/' . $post->image);
        }
        $post->delete();
        return redirect()->route('post.index')->with('success', 'Post has been deleted successfully.');
    }
}