<?php

namespace App\Http\Requests\Api\Test;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'timer' => ['required', 'integer'],
            'batch' => ['required', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string', 'max:255'],
            'questions.*.answers' => ['required', 'array', 'min:2'],
            'questions.*.answers.*' => ['required', 'string'],
            'questions.*.correct_answer' => ['required', 'string'],
            'questions.*.explanation' => ['nullable', 'string'],
        ];
    }
}
