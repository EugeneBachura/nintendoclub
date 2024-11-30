<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\PostCategory;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Handles the home and dashboard views.
 */
class HomeController extends Controller
{
    /**
     * Displays the home page with latest news, online users, and categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $latestNews = News::latest()->where('status', 'active')->take(8)->get();
        $usersOnlineCount = Profile::where('last_active_at', '>', Carbon::now()->subMinutes(10))->count();
        $usersOnline = Profile::where('last_active_at', '>', Carbon::now()->subMinutes(10))->get();
        $usersOnlineTodayCount = Profile::where('last_active_at', '>=', Carbon::today())->count();
        $topNews = News::orderBy('popularity', 'desc')->orderBy('created_at', 'desc')->where('status', 'active')->take(5)->get();

        $categories = PostCategory::with(['posts' => function ($query) {
            $query->where('status', 'active')
                ->where('language', app()->getLocale())
                ->latest();
        }])->get()->filter(function ($category) {
            return $category->posts->isNotEmpty();
        })->map(function ($category) {
            $category->setRelation('posts', $category->posts->take(2));
            return $category;
        });

        return view('home', [
            'latestNews' => $latestNews,
            'usersOnlineCount' => $usersOnlineCount,
            'usersOnline' => $usersOnline,
            'usersOnlineTodayCount' => $usersOnlineTodayCount,
            'topNews' => $topNews,
            'categories' => $categories,
        ]);
    }

    /**
     * Displays the dashboard with user's reward and profile details.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = auth()->user();
        $profile = $user->profile;

        $last_reward_collected_at = $profile->last_reward_collected_at
            ? Carbon::parse($profile->last_reward_collected_at)->isToday()
            : false;

        return view('dashboard', [
            'collectedDays' => $profile->consecutive_days,
            'collectedToday' => $last_reward_collected_at,
            'name' => $profile->nickname(),
        ]);
    }
}
