<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answers' => json_decode($this->answers, true) ?? [],
            'correct_answer' => $this->correct_answer,
            'explanation' => $this->explanation,
        ];
    }
}
