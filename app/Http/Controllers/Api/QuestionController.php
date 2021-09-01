<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index($id)
    {
        $questions = Question::where('package_id', $id)->with('user')->latest()->paginate(15);
        return response()->json(compact('questions'));
    }

    public function createQuestion(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'question' => 'required|max:1000',
        ]);
        $question = new Question();
        $question->user_id = Auth::id();
        $question->package_id = $request->id;
        $question->message = $request->question;
        $question->replay = '[]';
        $question->save();
    }

    public function deleteQuestion(Question $question)
    {
        $question->delete();
    }

    public function createReplay(Question $question, Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'message' => 'required|max:1000',
        ]);
        $replay = json_decode($question->replay, true);

        array_push($replay, ['message' => $request->message, 'user_id'=>Auth::id(), 'user_name'=>Auth::user()->name, 'created_at'=>Carbon::now()]);
        
        $question->replay = json_encode($replay);
        $question->save();
    }
    
    public function deleteReplay(Question $question, Request $request)
    {
        $replay = json_decode($question->replay, true);
        array_splice($replay, $request->key, 1);
        
        $question->replay = json_encode($replay);
        $question->save();
    }
}
