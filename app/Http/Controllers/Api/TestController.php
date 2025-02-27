<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Test\StoreTestRequest;
use App\Http\Requests\Api\Test\UpdateTestRequest;
use App\Http\Resources\TestResource;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        try {
            $tests = Test::with('questions')
                ->select('id', 'title', 'description', 'start_date', 'end_date', 'timer', 'batch')
                ->latest()
                ->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Tests listed successfully',
                'data' => TestResource::collection($tests),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tests are not listed!',
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function store(StoreTestRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $test = Test::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'timer' => $validated['timer'],
                'batch' => $validated['batch'],
            ]);

            foreach ($validated['questions'] as $question) {
                Question::create([
                    'test_id' => $test->id,
                    'question' => $question['question'],
                    'answers' => json_encode($question['answers']),
                    'correct_answer' => $question['correct_answer'],
                    'explanation' => $question['explanation'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Test and questions created successfully.',
                'data' => new TestResource($test->load('questions')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error while creating test and questions!',
                'data' => $e->getMessage(),
            ]);
        }
    }

    public function update(UpdateTestRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $test = Test::findOrFail($validated['id']);

            $test->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'timer' => $validated['timer'],
                'batch' => $validated['batch'],
            ]);

            $existingQuestionIds = $test->questions->pluck('id')->toArray();
            $updatedQuestionIds = [];

            foreach ($validated['questions'] as $question) {
                if (!empty($question['id'])) {
                    $questionData = Question::findOrFail($question['id']);

                    $questionData->update([
                        'question' => $question['question'],
                        'answers' => json_encode($question['answers']),
                        'correct_answer' => $question['correct_answer'],
                        'explanation' => $question['explanation'] ?? null,
                    ]);

                    $updatedQuestionIds[] = $question['id'];
                } else {
                    $newQuestion = Question::create([
                        'question' => $question['question'],
                        'answers' => json_encode($question['answers']),
                        'correct_answer' => $question['correct_answer'],
                        'explanation' => $question['explanation'] ?? null,
                    ]);

                    $updatedQuestionIds[] = $newQuestion->id;
                }
            }

            $questionsToDelete = array_diff($existingQuestionIds, $updatedQuestionIds);

            Question::whereIn('id', $questionsToDelete)->delete();

            $updatedTest = Test::findOrFail($test->id)->load('questions');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Test and questions updated successfully.',
                'data' => new TestResource($updatedTest),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error while updating test and questions!',
                'data' => $e->getMessage(),
            ]);
        }
    }
}
