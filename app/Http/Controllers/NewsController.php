<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\KeywordGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use HTMLPurifier;
use HTMLPurifier_Config;

class NewsController extends Controller
{
    protected $keywordGenerator;

    public function __construct(KeywordGenerator $keywordGenerator)
    {
        $this->keywordGenerator = $keywordGenerator;
    }

    public function index()
    {
        if (auth()->user()->hasRole(['editor'])) {
            $newsList = News::where('author_id', auth()->user()->id)->paginate(20);
        };
        if (auth()->user()->hasAnyRole(['review_editor', 'administrator'])) {
            $newsList = News::paginate(20);
        }
        return view('news.index', compact('newsList'));
    }

    public function showAll()
    {
        $newsList = News::orderBy('created_at', 'desc')->where('status', 'active')->paginate(18);
        return view('news.showAll', compact('newsList'));
    }

    public function create()
    {
        return view('news.create');
    }

    // Метод для сохранения новости в базу данных
    public function store(Request $request)
    {
        // Определение доступных статусов на основе роли пользователя
        $statusRules = 'in:hidden,under_review,deleted,active'; // По умолчанию для администратора
        if (Auth::user()->hasRole('editor')) {
            $statusRules = 'in:under_review';
        } elseif (Auth::user()->hasRole('review_editor') || Auth::user()->hasRole('administrator')) {
            $statusRules = 'in:hidden,under_review,deleted,active';
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|' . $statusRules,
            'alias' => 'nullable|string|unique:news',
            'image' => 'nullable|image|mimes:jpg,jpeg|dimensions:min_width=800,min_height=400,max_width=800,max_height=400|max:500',
            'video' => 'nullable|string|max:255',
            'en_title' => 'nullable|string|max:255',
            'en_content' => 'nullable|string',
            'en_keywords' => 'nullable|string',
            'ru_title' => 'nullable|string|max:255',
            'ru_content' => 'nullable|string',
            'ru_keywords' => 'nullable|string',
            'pl_title' => 'nullable|string|max:255',
            'pl_content' => 'nullable|string',
            'pl_keywords' => 'nullable|string',
            'en_seo_description' => 'nullable|string',
            'ru_seo_description' => 'nullable|string',
            'pl_seo_description' => 'nullable|string',
        ]);

        // Валидация зависимых полей
        $languages = ['en', 'ru', 'pl']; // Список поддерживаемых языков
        foreach ($languages as $lang) {
            $validator->sometimes("{$lang}_title", 'required_with:' . "{$lang}_content", function ($input) use ($lang) {
                return $input["{$lang}_content"] || $input["{$lang}_title"];
            });

            $validator->sometimes("{$lang}_content", 'required_with:' . "{$lang}_title", function ($input) use ($lang) {
                return $input["{$lang}_title"] || $input["{$lang}_content"];
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $news = new News();
        $news->status = $request->status;
        $news->author_id = auth()->id(); // Пример использования ID авторизованного пользователя
        $news->video = $request->video;

        // Генерация алиаса, если не указан
        if ($request->alias) {
            $news->alias = $request->alias;
        } else {
            // Используем английский заголовок или любой доступный
            $aliasSource = $request->input('en_title') ?? $request->input('ru_title') ?? $request->input('pl_title');
            $news->alias = Str::slug($aliasSource);
        }

        // Проверяем уникальность алиаса
        $existingAlias = News::where('alias', $news->alias)->exists();
        if ($existingAlias) {
            $news->alias .= '-' . time();
        }

        if ($request->hasFile('image')) {
            $imageExtension = $request->image->extension();
            $imageName = time() . '_' . $news->id . '_' . auth()->id() . '_' . $news->alias . '.' . $imageExtension;
            $imagePath = $request->image->storeAs('news_images', $imageName, 'public');
            $news->image = $imagePath;
        }

        $news->save();

        // Обновление или создание переводов
        $languages = ['en', 'ru', 'pl'];
        foreach ($languages as $lang) {
            if ($request->input("{$lang}_title") && $request->input("{$lang}_content")) {
                $dirtyHtml = trim($request->input("{$lang}_content"));
                $allowedTags = '<b><i><img><br><p>';
                $cleanHtml = strip_tags($dirtyHtml, $allowedTags);
                $cleanHtml = $this->sanitizeImageTags($cleanHtml);
                $cleanHtml = str_replace("'", "&apos;", $cleanHtml);

                // Генерация ключевых слов и SEO-описания, если не указаны
                $keywords = $request->input("{$lang}_keywords") ?? $this->keywordGenerator->generate($cleanHtml, $lang);
                $seoDescription = $request->input("{$lang}_seo_description") ?? Str::limit(strip_tags($cleanHtml), 160, '...');

                $translation = $news->translations()->updateOrCreate(
                    ['locale' => $lang],
                    [
                        'title' => $request->input("{$lang}_title"),
                        'content' => $cleanHtml,
                        'keywords' => $keywords,
                        'seo_description' => $seoDescription
                    ]
                );
            }
        }

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);

        return view('news.edit', compact('news'));
    }

    // Метод для редактирования новости в базу данных
    public function update(Request $request, $id)
    {
        // Найти существующую новость
        $news = News::findOrFail($id);

        // Проверка прав на редактирование
        if ($news->author_id !== auth()->id() && !auth()->user()->hasAnyRole(['review_editor', 'administrator'])) {
            return redirect()->back()->with('error', 'У вас нет прав на редактирование этогой новости.');
        }

        // Определение доступных статусов на основе роли пользователя
        $statusRules = 'in:hidden,under_review,deleted,active'; // По умолчанию для администратора
        if (Auth::user()->hasRole('editor')) {
            $statusRules = 'in:under_review';
        } elseif (Auth::user()->hasRole('review_editor') || Auth::user()->hasRole('administrator')) {
            $statusRules = 'in:hidden,under_review,deleted,active';
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|' . $statusRules,
            'alias' => 'required|string|unique:news,alias,' . $news->id, // Исключаем текущую новость из проверки
            'image' => 'nullable|image|mimes:jpg,jpeg|dimensions:min_width=800,min_height=400,max_width=800,max_height=400|max:500',
            'video' => 'nullable|string|max:255',
            'en_title' => 'nullable|string|max:255',
            'en_content' => 'nullable|string',
            'en_keywords' => 'nullable|string',
            'ru_title' => 'nullable|string|max:255',
            'ru_content' => 'nullable|string',
            'ru_keywords' => 'nullable|string',
            'pl_title' => 'nullable|string|max:255',
            'pl_content' => 'nullable|string',
            'pl_keywords' => 'nullable|string',
            'en_seo_description' => 'nullable|string',
            'ru_seo_description' => 'nullable|string',
            'pl_seo_description' => 'nullable|string',
        ]);

        // Получите неочищенный HTML-контент
        $dirtyHtmlEn = $request->input('en_content');
        $dirtyHtmlRu = $request->input('ru_content');
        $dirtyHtmlPl = $request->input('pl_content');

        // Очистите HTML-контент, разрешив только теги b, i и img
        $allowedTags = '<b><i><img><br><p>';
        $cleanHtmlEn = strip_tags($dirtyHtmlEn, $allowedTags);
        $cleanHtmlRu = strip_tags($dirtyHtmlRu, $allowedTags);
        $cleanHtmlPl = strip_tags($dirtyHtmlPl, $allowedTags);

        // Далее убедитесь, что все теги img имеют безопасные атрибуты src, alt и title
        $cleanHtmlEn = $this->sanitizeImageTags($cleanHtmlEn);
        $cleanHtmlRu = $this->sanitizeImageTags($cleanHtmlRu);
        $cleanHtmlPl = $this->sanitizeImageTags($cleanHtmlPl);

        // Валидация зависимых полей
        $languages = ['en', 'ru', 'pl']; // Список поддерживаемых языков
        foreach ($languages as $lang) {
            $validator->sometimes("{$lang}_title", 'required_with:' . "{$lang}_content", function ($input) use ($lang) {
                return $input["{$lang}_content"] || $input["{$lang}_title"];
            });

            $validator->sometimes("{$lang}_content", 'required_with:' . "{$lang}_title", function ($input) use ($lang) {
                return $input["{$lang}_title"] || $input["{$lang}_content"];
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $news->status = $request->status;
        $news->alias = $request->alias;
        $news->reviewer_id = auth()->id(); // Пример использования ID авторизованного пользователя
        $news->alias = $request->alias;
        $news->video = $request->video;

        // Обработка изображения, если оно было загружено
        if ($request->hasFile('image')) {
            // Удаление старого изображения
            Storage::delete('public/news_images/' . $news->image);
            $imageExtension = $request->image->extension();

            $imageName = time() . '_' . $news->id . '_' . auth()->id() . '_' . $news->alias . '.' . $imageExtension;
            $imagePath = $request->image->storeAs('news_images', $imageName, 'public');
            $news->image = $imagePath;
        }

        $news->save();

        // Обновление или создание переводов
        $languages = ['en', 'ru', 'pl'];
        foreach ($languages as $lang) {
            if ($request->input("{$lang}_title") && $request->input("{$lang}_content")) {
                $dirtyHtml = trim($request->input("{$lang}_content"));
                $allowedTags = '<b><i><img><br><p>';
                $cleanHtml = strip_tags($dirtyHtml, $allowedTags);
                $cleanHtml = $this->sanitizeImageTags($cleanHtml);
                $cleanHtml = str_replace("'", "&apos;", $cleanHtml);
                $translation = $news->translations()->updateOrCreate(
                    ['locale' => $lang],
                    [
                        'title' => $request->input("{$lang}_title"),
                        'content' => $cleanHtml,
                        'keywords' => $request->input("{$lang}_keywords") ?? null,
                        'seo_description' => $request->input("{$lang}_seo_description") ?? null
                    ]
                );
            }
        }

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    public function show(Request $request, $locale = null, $alias)
    {
        // Установка локализации приложения
        $locale = $locale ?? 'en';
        app()->setLocale($locale);

        // Получение новости по алиасу
        $news = News::where('alias', $alias)->where('status', 'active')->firstOrFail();

        // Загрузка перевода для заданной локализации
        $translation = $news->translations()->where('locale', $locale)->first();

        // Проверка наличия заголовка и контента для данной локализации
        if (!$translation || !$translation->title || !$translation->content) {
            // Если перевода нет, или нет заголовка/контента, вы можете перенаправить пользователя
            // на английскую версию новости или показать 404 страницу
            // if ($locale !== 'en') {
            //     return redirect()->route('news.show', ['locale' => 'en', 'news' => $newsId]);
            // }
            abort(404);
        }

        $user = auth()->user();
        $rewards = [];
        if ($user) {
            $view = $news->views()->firstOrCreate([
                'user_id' => $user->id
            ]);

            // Если это первый просмотр, увеличиваем популярность и начисляем монету
            if ($view->wasRecentlyCreated) {
                $news->popularity += 10;
                $news->update();
                $user->profile()->increment('coins');
                $rewards[] = (object) array('icon' => 'coins', 'quantity' => 1, 'item' => 'coins');
            }
        }

        $news->increment('views_count');
        if ($news->views_count % 3 == 0) {
            $news->increment('popularity');
        }

        $seo_description = $translation->seo_description;
        $seo_keywords = $translation->keywords;

        session()->flash('rewards', $rewards);

        // Если все проверки пройдены, показываем новость
        return view('news.show', compact('news', 'seo_description', 'seo_keywords'));
    }

    public function showWithoutLocale(Request $request, $alias)
    {
        // Установка локализации приложения
        $locale = 'en';
        app()->setLocale($locale);

        // Получение новости по алиасу
        $news = News::where('alias', $alias)->where('status', 'active')->firstOrFail();

        // Загрузка перевода для заданной локализации
        $translation = $news->translations()->where('locale', $locale)->first();

        // Проверка наличия заголовка и контента для данной локализации
        if (!$translation || !$translation->title || !$translation->content) {
            abort(404);
        }

        $user = auth()->user();
        $rewards = [];
        if ($user) {
            $view = $news->views()->firstOrCreate([
                'user_id' => $user->id
            ]);

            // Если это первый просмотр, увеличиваем популярность и начисляем монету
            if ($view->wasRecentlyCreated) {
                $news->popularity += 10;
                $news->update();
                $user->profile()->increment('coins');
                $rewards[] = (object) array('icon' => 'coins', 'quantity' => 1, 'item' => 'coins');
            }
        }

        $news->increment('views_count');

        $seo_description = $translation->seo_description;
        $seo_keywords = $translation->keywords;

        session()->flash('rewards', $rewards);

        // Если все проверки пройдены, показываем новость
        return view('news.show', compact('news', 'seo_description', 'seo_keywords'));
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::delete('public/news_images/' . $news->image);
        }
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News has been deleted successfully.');
    }

    private function sanitizeImageTags($html)
    {
        if (trim($html) === '') {
            return null;
        }
        // Загрузите HTML в DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // Найдите все теги изображений
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            // Удалить все атрибуты, кроме src, alt и title
            foreach (iterator_to_array($img->attributes) as $attribute) {
                if (!in_array($attribute->name, ['src', 'alt'])) {
                    $img->removeAttribute($attribute->name);
                }
            }
        }
        return $dom->saveHTML();
    }
}
