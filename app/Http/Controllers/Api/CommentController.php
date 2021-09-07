<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReplay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment($id)
    {
        $comments = Comment::where('blog_id', $id)->with('user', 'replays.user')->latest()->paginate(15);
        return response()->json(compact('comments'));
    }

    public function createComment(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'message' => 'required|max:500'
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->blog_id = $request->blog_id;
        $comment->message = $request->message;
        $comment->save();
    }

    public function createCommentReplay(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'message' => 'required|max:500'
        ]);
        
        $replay = new CommentReplay();
        $replay->user_id = Auth::id();
        $replay->blog_id = $request->blog_id;
        $replay->comment_id = $request->comment_id;
        $replay->message = $request->message;
        $replay->save();
    }
    
    public function deleteComment(Comment $comment)
    {
        $this->authorize('authCheck');
        $comment->delete();
    }
    
    public function deleteCommentReplay(CommentReplay $commentReplay)
    {
        $this->authorize('authCheck');
        $commentReplay->delete();
    }
}
