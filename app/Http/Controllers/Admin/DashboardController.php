<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $topPosts = Post::query()
            ->withCount('views')
            ->orderByDesc('views_count')
            ->limit(5)
            ->get(['id', 'title', 'slug']);

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
            ->select('ip_address', DB::raw('count(*) as views'))
            ->groupBy('ip_address')
            ->having('views', '=', 1)
            ->count();
        $bounceRate = $totalViews > 0
            ? round(($singleViewSessions / max($totalViews, 1)) * 100, 1)
            : 0;

        return view('admin.dashboard', [
            'postsCount' => Post::count(),
            'pendingCommentsCount' => Comment::where('is_approved', false)->count(),
            'pendingReviewsCount' => Review::where('is_approved', false)->count(),
            'pendingReportsCount' => CommentReport::where('status', 'pending')->count(),
            'totalViews' => $totalViews,
            'avgDuration' => $avgDuration,
            'countries' => $countries,
            'bounceRate' => $bounceRate,
            'topPosts' => $topPosts,
        ]);
    }
}
