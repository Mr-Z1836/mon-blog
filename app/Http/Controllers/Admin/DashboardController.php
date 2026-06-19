<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $topPosts = Post::query()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->withCount('views')
            ->orderByDesc('views_count')
            ->limit(5)
            ->get(['id', 'titre', 'slug']);

        $totalViews = PostView::count();
        $avgDuration = (int) round((float) PostView::avg('duration_seconds'));
        $countries = PostView::query()
            ->whereNotNull('country_code')
            ->select('country_code', DB::raw('count(*) as total'))
            ->groupBy('country_code')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $singleViewSessions = PostView::query()
            ->select('ip_address')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) = 1')
            ->get()
            ->count();
        $bounceRate = $totalViews > 0
            ? round(($singleViewSessions / max($totalViews, 1)) * 100, 1)
            : 0;

        $categories = Category::query()
            ->withCount('posts')
            ->orderBy('nom')
            ->get(['id', 'nom', 'slug']);

        return view('admin.dashboard', [
            'categories' => $categories,
            'postsCount' => Post::count(),
            'commentsCount' => Comment::count(),
            'pendingReportsCount' => CommentReport::where('statut', 'en_attente')->count(),
            'unreadContactCount' => ContactMessage::whereNull('lu_le')->count(),
            'newsletterSubscribersCount' => NewsletterSubscriber::query()->active()->count(),
            'totalViews' => $totalViews,
            'avgDuration' => $avgDuration,
            'countries' => $countries,
            'bounceRate' => $bounceRate,
            'topPosts' => $topPosts,
        ]);
    }
}
