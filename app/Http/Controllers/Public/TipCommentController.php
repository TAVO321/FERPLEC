<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use App\Models\TipComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TipCommentController extends Controller
{
    public function store(Request $request, Tip $tip): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'content' => ['required', 'string', 'max:1000'],
            'honeypot' => ['nullable', 'max:0'],
        ]);

        if (! empty($data['honeypot'] ?? null)) {
            return back();
        }

        TipComment::create([
            'tip_id' => $tip->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'content' => $data['content'],
            'ip' => $request->ip(),
            'is_approved' => false,
        ]);

        return back()->with('comment_status', 'Gracias. Tu comentario será revisado antes de publicarse.');
    }
}
