<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answers' => 'required|array|min:2',
            'correct_answer' => 'required|integer',
            'explanation' => 'nullable|string',
        ]);

        Question::create([
            'question' => $request->question,
            'answers' => json_encode($request->answers),
            'correct_answer' => $request->correct_answer,
            'explanation' => $request->explanation,
        ]);

        return redirect()->back()->with('success', 'Question added successfully!');
    }
}
