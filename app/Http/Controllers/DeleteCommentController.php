<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class DeleteCommentController extends Controller
{
     /**
     * Handle the incoming request to delete a comment.
     *
     * @param String $id
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(String $id, Comment $comment)
    {
        try {
            // Check if the user is authorized to delete the comment
            $this->authorize('delete', $comment);

            // Delete the comment
            $comment->delete();

            // Redirect back with a success message
            return back()->with('success', 'Comment deleted successfully.');

        } catch (AuthorizationException $e) {
            // Log unauthorized action attempt
            \Log::warning('Unauthorized attempt to delete comment', [
                'comment_id' => $comment->id,
                'user_id' => auth()->id(),
                'exception' => $e->getMessage(),
            ]);

            // Return back with a forbidden error message
            return back()->with('error', 'You are not authorized to delete this comment.');

        } catch (\Exception $e) {
            // Log general errors
            \Log::error('Error deleting comment', [
                'comment_id' => $comment->id,
                'user_id' => auth()->id(),
                'exception' => $e->getMessage(),
            ]);

            // Return back with a generic error message
            return back()->with('error', 'An error occurred while deleting the comment. Please try again later.');
        }
    }
}
