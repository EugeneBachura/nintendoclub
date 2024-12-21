<?php

use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DailyRewardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OldUserController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostCommentLikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Models\Game;
use App\Models\News;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Shop;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'setlocale'
], function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Discord login  
    Route::get('auth/discord', [DiscordController::class, 'redirectToProvider'])->name('auth.discord');
    Route::get('auth/discord/callback', [DiscordController::class, 'handleProviderCallback']);
    Route::post('/logout', [DiscordController::class, 'logout'])->name('logout');

    // User routes
    Route::get('/user/{id}', [ProfileController::class, 'show'])->name('profile.show');

    // Profile search
    Route::get('/users/search', [ProfileController::class, 'search'])->name('profile.search');

    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{notification}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Only users (not banned users)
    Route::group(['role_or_permission:user'], function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        // Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
        // Route::post('/shop/buy/{item}', [ShopController::class, 'buy'])->name('shop.buy');
        Route::get('/shop', Shop::class)->name('shop.index');
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
        Route::get('/transactions', [ShopController::class, 'history'])->name('transactions.history');
        Route::post('/games/{gameId}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/games/{gameId}/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
        Route::post('/posts/store', [PostController::class, 'store'])->name('post.store');
        Route::post('/collect-daily-reward', [DailyRewardController::class, 'collectDailyReward'])->name('collect.daily.reward');
        Route::post('/update-activity', [ActivityController::class, 'update']);
        Route::post('/image-upload', [ImageUploadController::class, 'store'])->name('image.upload');
        Route::post('/image-delete', [ImageUploadController::class, 'delete'])->name('image.delete');
        Route::post('/posts/{postId}/toggle-like', [PostLikeController::class, 'toggleLike'])->name('posts.toggle-like');
        Route::post('/comments/{comment}/like', [PostCommentLikeController::class, 'like'])->name('comments.like');
        Route::post('/comments/add', [PostCommentController::class, 'store'])->name('comments.store');
    });

    // Only admins
    Route::group(['role_or_permission:administrator'], function () {
        Route::resource('news', NewsController::class)->only(['destroy']);
        Route::get('/games/all', [GameController::class, 'index'])->name('game.index');
        Route::get('/games/create', [GameController::class, 'create'])->name('game.create');
        Route::post('/games/store', [GameController::class, 'store'])->name('game.store');
        Route::get('/review/all', [ReviewController::class, 'index'])->name('review.index');
        Route::get('/review/edit/{id}', [ReviewController::class, 'edit'])->name('review.edit');
        Route::put('/review/update/{gameId}/{id}', [ReviewController::class, 'updateForAdmin'])->name('review.updateForAdmin');
        Route::get('/posts/all', [PostController::class, 'index'])->name('post.index');
        Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/posts/{id}/update', [PostController::class, 'update'])->name('post.update');
        Route::delete('/posts/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');
        Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
        Route::get('/old_user/refresh', [OldUserController::class, 'refresh'])->name('old_user.refresh');
    });

    // Only admins, editors, and review editors
    Route::group(['role_or_permission:administrator|editor|review_editor'], function () {
        Route::resource('news', NewsController::class)->only(['create', 'store', 'edit', 'update']);
        Route::get('news/index', [NewsController::class, 'index'])->name('news.index');
    });

    // Other pages
    Route::get('/pages/terms', function () {
        return view('pages.terms');
    })->name('terms');
    Route::get('/pages/contacts', function () {
        return view('pages.contacts');
    })->name('contacts');
    Route::get('/pages/updates', function () {
        return view('pages.updates');
    })->name('updates');

    // Level revards
    Route::get('/awards', [LevelController::class, 'rewards'])->name('levels.rewards');

    // Post routes
    Route::get('/posts', [PostController::class, 'showAll'])->name('post.showAll');
    Route::get('/posts/{alias}', [PostController::class, 'show'])->name('post.show');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // News routes
    Route::get('/news', [NewsController::class, 'showAll'])->name('news.showAll');
    Route::get('/news/{alias}', [NewsController::class, 'show'])->name('news.show');

    // Game routes
    Route::get('/games', [GameController::class, 'showAll'])->name('game.showAll');
    Route::get('/game/{alias}', [GameController::class, 'show'])->name('game.show');
});

Route::get('/admin/add-exp', function () {
    $user = auth()->user();

    // Only allow the admin with id = 1 to access this route
    if ($user && $user->id === 1) {
        $user->profile->addExp(1000); // Add 100 experience points for testing
        return back()->with('success', 'Experience added!');
    }

    return abort(403); // Forbidden for non-admins
})->middleware('auth');

Route::get('robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
    ];

    // Возвращаем ответ с соответствующим содержимым и типом MIME
    return response(implode(PHP_EOL, $lines), 200)->header('Content-Type', 'text/plain');
});

Route::get('/set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ru', 'pl'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);

        // Сохраняем выбор пользователя в базе, если он авторизован
        if (auth()->check()) {
            $user = auth()->user();
            $user->update(['locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('locale.set');
