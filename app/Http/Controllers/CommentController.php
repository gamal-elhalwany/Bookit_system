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
    public function index()
    {
        $comments = Comment::all();
        return response()->json(['comments' => $comments]);
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     * Show a single comment.
     */
    public function show(Comment $comment)
    {
        return response()->json(['comment' => $comment]);
    }

    /**
     * Add a new Comment.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment' => 'required|array',
            'comment.ar' => 'string|max:255',
            'comment.en' => 'string|max:255',
            'job_title' => 'required|array',
            'job_title.ar' => 'string|max:255',
            'job_title.en' => 'string|max:255',
            'rate' => 'required|numeric|min:0|max:5',
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
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'comment' => 'required|array',
            'comment.ar' => 'string|max:255',
            'comment.en' => 'string|max:255',
            'job_title' => 'required|array',
            'job_title.ar' => 'string|max:255',
            'job_title.en' => 'string|max:255',
            'rate' => 'required|numeric|min:0|max:5',
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
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
