<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentReportRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentReportController extends Controller
{
    public function store(StoreCommentReportRequest $request, Comment $comment): RedirectResponse
    {
        $motif = $request->validated('reason');
        $user = $request->user();

        if ($user !== null) {
            $report = $comment->reports()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'motif' => $motif,
                    'statut' => 'en_attente',
                    'reporter_ip' => $request->ip(),
                ],
            );

            if (! $report->wasRecentlyCreated) {
                return back()->with('status', 'Tu as déjà signalé ce commentaire.');
            }
        } else {
            $alreadyReported = $comment->reports()
                ->whereNull('user_id')
                ->where('reporter_ip', $request->ip())
                ->exists();

            if ($alreadyReported) {
                return back()->with('status', 'Tu as déjà signalé ce commentaire.');
            }

            $comment->reports()->create([
                'user_id' => null,
                'reporter_ip' => $request->ip(),
                'motif' => $motif,
                'statut' => 'en_attente',
            ]);
        }

        return back()->with('status', 'Signalement envoyé. Merci pour ta vigilance.');
    }
}
