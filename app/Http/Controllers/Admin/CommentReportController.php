<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommentReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index', [
            'reports' => CommentReport::with(['comment.post:id,title,slug', 'reporter:id,name'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function update(Request $request, CommentReport $commentReport): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,reviewed,dismissed'],
        ]);

        $commentReport->update(['status' => $data['status']]);

        return back()->with('status', 'Signalement mis à jour.');
    }
}
