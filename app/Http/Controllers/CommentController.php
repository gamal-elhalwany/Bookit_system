<?php

namespace App\Http\Controllers;
use App\Models\Comment;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * Get all comments.
     */
    public function allcomments()
    {
        return response()->json(Comment::all());
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     * Show a single comment.
     */
    public function showcomment(Comment $comment)
    {
        return response()->json($comment);
    }

    /**
     * Add a new Comment.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storecomment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:5',
            'comment' => 'required|string',
        ]);

        $comment = Comment::create($validated);

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     * Update a single comment.
     */
    public function updatecomment(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'job_title' => 'sometimes|required|string|max:255',
            'rate' => 'sometimes|required|numeric|min:0|max:5',
            'comment' => 'sometimes|required|string',
        ]);

        $comment->update($validated);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     * delete a single comment.
     */
    public function deletecomment(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
