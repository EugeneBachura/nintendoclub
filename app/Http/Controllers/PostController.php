<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $postsList = Post::paginate(18);
        return view('posts.index', compact('postsList'));
    }

    public function showAll()
    {
        $posts = Post::where('language', app()->getLocale())
            ->where('status', 'active')
            ->with(['category'])
            ->paginate(18);
        return view('posts.showAll', compact('posts'));
    }

    public function showUserPosts()
    {
        $postsList = Post::where('autor_id', auth()->user()->id)->paginate(18);
        return view('posts.index', compact('postsList'));
    }

    public function show($alias)
    {
        $post = Post::with(['comments' => function ($query) {
            $query->where('status', 'approved')->whereNull('parent_id') // Загружаем только комментарии верхнего уровня
                ->with('user') // Загружаем данные пользователя, который оставил комментарий
                ->with(['replies' => function ($query) {
                    $query->with('user'); // Загружаем данные пользователя для ответов на комментарии
                }])
                ->orderBy('created_at', 'desc'); // Сортировка комментариев по дате создания
        }])->where('alias', $alias)->where('language', 'en')->firstOrFail();
        $post->increment('views_count');

        $user = auth()->user();
        if ($user) {
            $user_level = $user->profile->level;
        } else {
            $user_level = 0;
        }

        return view('posts.show', compact('post', 'user_level'));
    }
    public function showWithLocale($locale, $alias)
    {
        $locale = app()->getLocale();
        $post = Post::with(['comments' => function ($query) {
            $query->where('status', 'approved')->whereNull('parent_id') // Загружаем только комментарии верхнего уровня
                ->with('user') // Загружаем данные пользователя, который оставил комментарий
                ->with(['replies' => function ($query) {
                    $query->with('user'); // Загружаем данные пользователя для ответов на комментарии
                }])
                ->orderBy('created_at', 'desc'); // Сортировка комментариев по дате создания
        }])->where('alias', $alias)->where('language', $locale)->firstOrFail();
        $post->increment('views_count');

        $user = auth()->user();
        if ($user) {
            $user_level = $user->profile->level;
        } else {
            $user_level = 0;
        }

        return view('posts.show', compact('post', 'user_level'));
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
            $post->keywords = $request->keywords;
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
            $post->keywords = $request->keywords;
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
