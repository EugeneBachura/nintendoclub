<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\PostCategory;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем 8 последних новостей
        $latestNews = News::latest()->where('status', 'active')->take(8)->get();

        // Получаем количество пользователей онлайн
        $usersOnlineCount = Profile::where('last_active_at', '>', Carbon::now()->subMinutes(10))->count();

        // Получаем пользователей онлайн
        $usersOnline = Profile::where('last_active_at', '>', Carbon::now()->subMinutes(10))->get();

        // Получаем количество пользователей, которые были онлайн сегодня
        $usersOnlineTodayCount = Profile::where('last_active_at', '>=', Carbon::today())->count();

        // Получаем топ 5 популярных новостей (предполагается наличие поля 'popularity')
        $topNews = News::orderBy('popularity', 'desc')->orderBy('created_at', 'desc')->where('status', 'active')->take(5)->get();

        // Получаем категории с постами, отфильтрованными по языку и статусу
        $categories = PostCategory::with(['posts' => function ($query) {
            $query->where('status', 'active')
                ->where('language', app()->getLocale()) // Фильтруем по текущей локали
                ->latest();
        }])->get()->filter(function ($category) {
            return $category->posts->isNotEmpty(); // Оставляем только категории с постами
        })->map(function ($category) {
            // Ограничиваем количество постов до 2 после загрузки данных
            $category->setRelation('posts', $category->posts->take(2));
            return $category;
        });;

        return view('home', [
            'latestNews' => $latestNews,
            'usersOnlineCount' => $usersOnlineCount,
            'usersOnline' => $usersOnline,
            'usersOnlineTodayCount' => $usersOnlineTodayCount,
            'topNews' => $topNews,
            'categories' => $categories
        ]);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $profile = $user->profile;

        // Проверяем, была ли награда собрана сегодня
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
