<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $questionId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'question_id' => $questionId,
            'content' => $request->content,
        ]);

        // Загружаем связанные данные для отображения
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Комментарий успешно добавлен!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->format('d.m.Y H:i')
            ]
        ]);
    }
}