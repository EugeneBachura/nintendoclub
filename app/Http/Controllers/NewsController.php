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
use Illuminate\Support\Facades\App;

/**
 * Handles CRUD operations and display logic for news.
 */
class NewsController extends Controller
{
    protected $keywordGenerator;

    /**
     * Initialize the controller with a keyword generator service.
     *
     * @param KeywordGenerator $keywordGenerator
     */
    public function __construct(KeywordGenerator $keywordGenerator)
    {
        $this->keywordGenerator = $keywordGenerator;
    }

    /**
     * Display the list of news articles.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Display all active news articles.
     *
     * @return \Illuminate\View\View
     */
    public function showAll()
    {
        $newsList = News::where('status', 'active')->orderBy('created_at', 'desc')->paginate(18);

        foreach ($newsList as $news) {
            $news->popularityColor = $news->getPopularityColor();
            $news->trimmedContent = $this->getTrimmedContent($news->getTranslation('content', app()->getLocale()), app()->getLocale());
        }

        $breadcrumbs = [
            ['title' => __('titles.all_news'), 'url' => '']
        ];

        return view('news.showAll', compact('newsList', 'breadcrumbs'));
    }

    /**
     * Shortens content based on the selected language and character limit.
     *
     * @param string $content
     * @param string $locale
     * @return string
     */
    private function getTrimmedContent($content, $locale)
    {
        $limit = match ($locale) {
            'en' => 100,
            'ru' => 300,
            'pl' => 110,
            default => 100,
        };

        $ending = '...';
        return mb_strlen($content) > $limit
            ? mb_substr($content, 0, mb_strripos(mb_substr($content, 0, $limit), ' ')) . $ending
            : $content;
    }

    /**
     * Show the form for creating a new news article.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created news article.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $statusRules = 'in:hidden,under_review,deleted,active';
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

        $languages = config('localization.supported_locales');
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
        $news->author_id = auth()->id();
        $news->video = $request->video;

        if ($request->alias) {
            $news->alias = $request->alias;
        } else {
            $aliasSource = $request->input('en_title') ?? $request->input('ru_title') ?? $request->input('pl_title');
            $news->alias = Str::slug($aliasSource);
        }

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

        foreach ($languages as $lang) {
            if ($request->input("{$lang}_title") && $request->input("{$lang}_content")) {
                $dirtyHtml = trim($request->input("{$lang}_content"));
                $allowedTags = '<b><i><img><br><p><h1><h2><h3><h4><h5><h6><ul><ol><li><a><strong><em><u><s><sub><sup><blockquote>';
                $cleanHtml = strip_tags($dirtyHtml, $allowedTags);
                $cleanHtml = $this->sanitizeImageTags($cleanHtml);
                $cleanHtml = str_replace("'", "&apos;", $cleanHtml);

                $keywords = $request->input("{$lang}_keywords") ?? $this->keywordGenerator->generate($cleanHtml, $lang);

                $decodedContent = html_entity_decode($cleanHtml, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $seoDescription = $request->input("{$lang}_seo_description") ?? Str::limit(strip_tags($decodedContent), 160, '...');

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

    /**
     * Show the form for editing the specified news article.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);

        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified news article.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        if ($news->author_id !== auth()->id() && !auth()->user()->hasAnyRole(['review_editor', 'administrator'])) {
            return redirect()->back()->with('error', 'You do not have permissions to edit this news.');
        }

        $statusRules = 'in:hidden,under_review,deleted,active';
        if (Auth::user()->hasRole('editor')) {
            $statusRules = 'in:under_review';
        } elseif (Auth::user()->hasRole('review_editor') || Auth::user()->hasRole('administrator')) {
            $statusRules = 'in:hidden,under_review,deleted,active';
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|' . $statusRules,
            'alias' => 'nullable|string|unique:news,alias,' . $news->id,
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

        $languages = config('localization.supported_locales');
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
        $news->reviewer_id = auth()->id();
        $news->video = $request->video;

        if ($request->alias) {
            $news->alias = $request->alias;
        } else {
            $aliasSource = $request->input('en_title') ?? $request->input('ru_title') ?? $request->input('pl_title');
            $news->alias = Str::slug($aliasSource);
        }

        $existingAlias = News::where('alias', $news->alias)->where('id', '!=', $news->id)->exists();
        if ($existingAlias) {
            $news->alias .= '-' . time();
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::delete('public/news_images/' . $news->image);
            }

            $imageExtension = $request->image->extension();
            $imageName = time() . '_' . $news->id . '_' . auth()->id() . '_' . $news->alias . '.' . $imageExtension;
            $imagePath = $request->image->storeAs('news_images', $imageName, 'public');
            $news->image = $imagePath;
        }

        $news->save();

        foreach ($languages as $lang) {
            if ($request->input("{$lang}_title") && $request->input("{$lang}_content")) {
                $dirtyHtml = trim($request->input("{$lang}_content"));
                $allowedTags = '<b><i><img><br><p><h1><h2><h3><h4><h5><h6><ul><ol><li><a><strong><em><u><s><sub><sup><blockquote>';
                $cleanHtml = strip_tags($dirtyHtml, $allowedTags);
                $cleanHtml = $this->sanitizeImageTags($cleanHtml);
                $cleanHtml = str_replace("'", "&apos;", $cleanHtml);

                $keywords = $request->input("{$lang}_keywords") ?? $this->keywordGenerator->generate($cleanHtml, $lang);

                $decodedContent = html_entity_decode($cleanHtml, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $seoDescription = $request->input("{$lang}_seo_description") ?? Str::limit(strip_tags($decodedContent), 160, '...');

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

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    /**
     * Display the specified news article.
     *
     * @param Request $request
     * @param string $alias
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $alias)
    {
        $locale = App::getLocale();

        $news = News::where('alias', $alias)->where('status', 'active')->firstOrFail();

        $translation = $news->translations()->where('locale', $locale)->first();

        if (!$translation || !$translation->title || !$translation->content) {
            abort(404);
        }

        $user = auth()->user();
        $rewards = [];

        if ($user) {
            $view = $news->views()->firstOrCreate([
                'user_id' => $user->id
            ]);

            if ($view->wasRecentlyCreated) {
                $news->increment('popularity', 10);
                $user->profile()->increment('coins');
                $rewards[] = (object) ['icon' => 'coins', 'quantity' => 1, 'item' => 'coins'];
            }
            $news->increment('popularity', 1);
        } else {
            $news->increment('popularity', 1);
        }

        $news->increment('views_count');

        $seo_description = $translation->seo_description;
        $seo_keywords = $translation->keywords;

        session()->flash('rewards', $rewards);

        $popularityColor = $news->getPopularityColor();

        $breadcrumb_title = $translation->title;
        $breadcrumbs = [
            ['title' => __('titles.all_news'), 'url' => localized_url('news.showAll')],
            ['title' => $this->trimTitle($breadcrumb_title), 'url' => ''],
        ];

        return view('news.show', compact('news', 'seo_description', 'seo_keywords', 'popularityColor', 'breadcrumbs'));
    }

    /**
     * Trim the title for breadcrumbs.
     *
     * @param string $title
     * @return string
     */
    private function trimTitle($title)
    {
        $limit = 50;
        $ending = '...';

        if (mb_strlen($title) > $limit) {
            $cutOff = mb_strripos(mb_substr($title, 0, $limit), ' ');
            $trimmed = mb_substr($title, 0, $cutOff) . $ending;
        } else {
            $trimmed = $title;
        }

        return $trimmed;
    }

    /**
     * Remove the specified news article.
     *
     * @param News $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::delete('public/news_images/' . $news->image);
        }
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News has been deleted successfully.');
    }

    /**
     * Sanitize image tags in the HTML content.
     *
     * @param string $html
     * @return string|null
     */
    private function sanitizeImageTags($html)
    {
        if (trim($html) === '') {
            return null;
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            foreach (iterator_to_array($img->attributes) as $attribute) {
                if (!in_array($attribute->name, ['src', 'alt'])) {
                    $img->removeAttribute($attribute->name);
                }
            }
        }

        return $dom->saveHTML();
    }
}
