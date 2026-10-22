<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TipCommentController extends Controller
{
    public function index(): View
    {
        $comments = TipComment::with('tip')->orderByDesc('created_at')->paginate(20);

        return view('admin.tip-comments.index', compact('comments'));
    }

    public function approve(TipComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);

        return back()->with('status', 'Comentario aprobado.');
    }

    public function reject(TipComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => false]);

        return back()->with('status', 'Comentario ocultado.');
    }

    public function destroy(TipComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Comentario eliminado.');
    }
}
