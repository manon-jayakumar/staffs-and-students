<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function create()
    {
        return view('test.create');
    }

    public function leaveRequestPage()
    {
        return view('leave_request_form');
    }

    public function viewAllLeaveRequests()
    {
        $leaveRequests = Leave::all();
        return view('test.all_leave_requests', compact('leaveRequests'));
    }

    public function viewLeaveRequest($id)
    {
        $leaveRequest = Leave::findOrFail($id);
        return view('test.view_leave_request', compact('leaveRequest'));
    }

    public function leaveRequestStore(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'roll_number' => 'required|string|max:20',
            'reason' => 'required|string|max:255',
            'leave_type' => 'required|in:sick,casual,vacation',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

         Leave::create($request->all());

        return back()->with('success', 'Leave request submitted successfully!');
    }

    public function store(Request $request)
    {
        $test = Test::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'timer' => $request->timer,
            'batch' => $request->batch
        ]);

        foreach ($request->questions as $questionData) {
            if (isset($questionData['question'], $questionData['answers'], $questionData['correct_answer'])) {
                Question::create([
                    'question' => $questionData['question'],
                    'answers' => json_encode($questionData['answers']),
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'test_id' => $test->id,
                ]);
            } else {
                \Log::error('Missing required fields in question data: ' . json_encode($questionData));
            }
        }

        return response()->json([
            'message' => 'Test and questions created successfully.',
            'test' => $test
        ]);

    }

    public function showTestDescription($testId)
    {
        $test = Test::findOrFail($testId);
        return view('test.test_description', compact('test'));
    }

    public function showQuestionsPage(Request $request, $id)
    {
        $test = Test::findOrFail($id);
        $questions = $test->questions;
        $currentQuestionIndex = $request->query('q', 0);

        return view('test.questions', compact('test', 'questions', 'currentQuestionIndex'));
    }

    public function checkAnswer(Request $request)
    {
        $question = Question::find($request->question_id);
        $correctAnswer = $question->correct_answer;

        if ($request->selected_option == $correctAnswer) {
            return response()->json(['correct' => true]);
        } else {
            return response()->json(['correct' => false]);
        }
    }


}
