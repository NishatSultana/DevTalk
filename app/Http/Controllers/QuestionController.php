<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Str;
use App\Http\Requests\AskQuestionRequest;


class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        // Start Query Log
        // \DB::enableQueryLog();

        // Lazy Load
        // $questions = Question::latest()->paginate(5);

        // Eager Load
        $questions = Question::with('user')->latest()->paginate(5);

        return view('questions.index', compact('questions'));

        // Check Query Log
        // view('questions.index', compact('questions'))->render();

        // End Query Log
        // dd(\DB::getQueryLog());
    }

    public function create()
    {
        $question = new Question();

        return view('questions.create', compact('question'));
    }

    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title', 'body'));

        return redirect()->route('questions.index')->with('success', "Your question has been submitted");
    }

    public function edit(Question $question)
    {
        /*
        // \Gate::allow
        if (\Gate::denies('update-question', $question)) {
            abort(403, "Access Denied");
        }
        */
        $this->authorize("update", $question);
        return view("questions.edit", compact('question'));
    }

    public function update(AskQuestionRequest $request, Question $question)
    {
        if (\Gate::denies('update-question', $question)) {
            abort(403, "Access Denied");
        }

        $question->update($request->only('title', 'body'));

        return redirect('/questions')->with('success', "Your question has been updated.");
    }

    public function show(Question $question)
    {
        $question->increment('views');
        return view('questions.show', compact('question'));
    }

    public function destroy(Question $question)
    {
        /*
        if (\Gate::denies('delete-question', $question)) {
            abort(403, "Access Denied");
        }
        */

        $this->authorize("delete", $question);
        $question->delete();
        return redirect('/questions')->with("success", "Your Question has beed deleted.");
    }
}
