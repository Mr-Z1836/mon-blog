<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\CommentReportController as AdminCommentReportController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\PostSeriesController as AdminPostSeriesController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostBookmarkController;
use App\Http\Controllers\PostReactionController;
use App\Http\Controllers\PostReadHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PostController;
use App\Models\Post as BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/articles', [HomeController::class, 'index'])->name('posts.index');
Route::get('/articles/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/sitemap.xml', function (): Response {
    $posts = BlogPost::query()
        ->published()
        ->orderByDesc('updated_at')
        ->get(['slug', 'updated_at']);

    return response()
        ->view('seo.sitemap', ['posts' => $posts])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function (): Response {
    $content = implode(PHP_EOL, [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        '',
        'Sitemap: '.route('sitemap'),
    ]);

    return response($content, 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('robots');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', function (): RedirectResponse {
        return auth()->user()->is_admin
            ? to_route('admin.dashboard')
            : to_route('posts.index');
    })->name('dashboard');

    Route::middleware('throttle:20,1')->group(function (): void {
        Route::post('/articles/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
        Route::post('/articles/{post}/comments/{comment}/report', [CommentReportController::class, 'store'])->name('posts.comments.report');
        Route::post('/articles/{post}/ratings', [RatingController::class, 'store'])->name('posts.ratings.store');
        Route::post('/articles/{post}/reviews', [ReviewController::class, 'store'])->name('posts.reviews.store');
        Route::post('/articles/{post}/reactions', [PostReactionController::class, 'store'])->name('posts.reactions.store');
        Route::post('/articles/{post}/bookmark', [PostBookmarkController::class, 'store'])->name('posts.bookmark.store');
        Route::delete('/articles/{post}/bookmark', [PostBookmarkController::class, 'destroy'])->name('posts.bookmark.destroy');
        Route::patch('/articles/{post}/read-progress', [PostReadHistoryController::class, 'update'])->name('posts.read-progress.update');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->as('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('posts', AdminPostController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('tags', AdminTagController::class)->except('show');
    Route::resource('comments', AdminCommentController::class)->only(['index', 'update', 'destroy']);
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'update', 'destroy']);
    Route::resource('series', AdminPostSeriesController::class)->except('show');
    Route::resource('users', AdminUserController::class)->only(['index', 'update', 'destroy']);
    Route::get('media', [AdminMediaController::class, 'index'])->name('media.index');
    Route::post('media', [AdminMediaController::class, 'store'])->name('media.store');
    Route::delete('media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    Route::get('reports', [AdminCommentReportController::class, 'index'])->name('reports.index');
    Route::patch('reports/{commentReport}', [AdminCommentReportController::class, 'update'])->name('reports.update');
});

require __DIR__.'/auth.php';
