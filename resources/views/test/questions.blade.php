@extends('layouts.app')
@section('title', $test->title)

@section('content')

    <style>
        .bg-light-purple {
            background-color: #EAF7E1 !important;
            border: 2px solid #28A745 !important;
        }

        .bg-light-gray {
            background-color: #F0F0F0 !important;
        }

        .bg-danger {
            background-color: #F8D7DA !important;
            border: 2px solid #DC3545 !important;
        }

        .border-success {
            border: 2px solid #28A745 !important;
        }

        .border-danger {
            border: 2px solid #DC3545 !important;
        }
    </style>

    <div id="questionsDiv" class="container my-4">


        <h4 class="fw-bold text-center">{{ $test->title }}</h4>

        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fas fa-clock"></i> <span id="timer">{{ $test->timer }}:00 min</span></span>
        </div>

        <hr>

        <p id="error-message" class="text-danger text-center" style="display: none">Please Answer to all the
            Questions</p>

        <div class="mt-4" id="question-container">
            @foreach ($questions as $index => $question)
                <div class="question" id="question-{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                    <h5 class="fw-bold"> {{ $index + 1 }}. {{ $question->question_text }}</h5>
                    <form id="question-form-{{ $index }}">

                        <input type="hidden" name="question_id" value="{{ $question->id }}">
                        <p>{{ $question->question }}</p>

                        @foreach(json_decode($question->answers) as $optionIndex => $option)
                            <div
                                class="form-check p-3 rounded option-field @if(isset($selectedOption) && $selectedOption == $optionIndex) bg-light-purple @else bg-light-gray @endif"
                                id="option-{{ $index }}-{{ $optionIndex }}"
                                data-question-id="{{ $question->id }}"
                                data-correct-answer="{{ $question->correct_answer }}"
                                data-explanation="{{ $question->explanation }}">
                                <input class="form-check-input" type="radio" name="selected_option"
                                       value="{{ $optionIndex }}" id="option{{ $optionIndex }}"
                                       @if(isset($selectedOption) && $selectedOption == $optionIndex) checked @endif>
                                <label class="form-check-label w-100" for="option{{ $optionIndex }}">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach

                        <div class="explanation mt-3 text-purple fw-bold" id="explanation-{{ $index }}"
                             style="display: none;">
                            <strong>Explanation:</strong> <span id="explanation-text-{{ $index }}"></span>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn bg_color text-white fw-bold submit-btn"
                                    id="submit-btn-{{ $index }}">Submit this Question
                            </button>
                        </div>

                    </form>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <span class="fw-bold" id="question-count">QUESTION 1 OF {{ count($questions) }}</span>
            <div class="d-flex justify-content-center mt-2">
                @foreach($questions as $index => $q)
                    <button
                        class="btn @if($index == $currentQuestionIndex) bg_color text-white @else border-purple text-purple @endif mx-1"
                        id="question-btn-{{ $index }}"
                        onclick="showQuestion({{ $index }})">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div id="success-message" class="text-center mt-4" style="display: none;">
        <h4 class="text-success">Answers submitted successfully!</h4>
        <p>Redirecting to home page...</p>
    </div>

    <script>
        let time = {{ $test->timer }} * 60;
        let currentQuestionIndex = {{ $currentQuestionIndex }};
        let answers = [];

        function updateTimer() {
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            document.getElementById("timer").textContent = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
            if (time > 0) time--;
            else alert("Time is up!");
        }

        setInterval(updateTimer, 1000);

        function showQuestion(questionIndex) {
            const questions = document.querySelectorAll('.question');
            questions.forEach((question, index) => {
                question.style.display = (index === questionIndex) ? 'block' : 'none';
            });

            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.classList.remove('bg_color', 'text-white');
                button.classList.add('border-purple', 'text-purple');
            });

            const activeButton = document.getElementById(`question-btn-${questionIndex}`);
            activeButton.classList.add('bg_color', 'text-white');

            const submitButton = questions[questionIndex].querySelector('.submit-btn');
            submitButton.classList.add('bg_color', 'text-white');

            currentQuestionIndex = questionIndex;
            document.getElementById('question-count').textContent = `QUESTION ${currentQuestionIndex + 1} OF ${questions.length}`;
        }

        document.querySelectorAll('.form-check-input').forEach(input => {
            input.addEventListener('change', function () {
                const optionField = this.closest('.option-field');
                const questionId = optionField.getAttribute('data-question-id');
                const selectedOption = this.value;
                const correctAnswer = optionField.getAttribute('data-correct-answer');
                const explanationText = optionField.getAttribute('data-explanation');
                const explanationDiv = document.getElementById(`explanation-${currentQuestionIndex}`);
                const explanationTextSpan = document.getElementById(`explanation-text-${currentQuestionIndex}`);

                const optionFields = document.querySelectorAll(`#question-form-${currentQuestionIndex} .option-field`);
                optionFields.forEach(field => {
                    field.classList.remove('bg-light-purple', 'bg-success', 'bg-danger');
                    field.classList.add('bg-light-gray');
                });

                answers[currentQuestionIndex] = selectedOption;

                fetch('/check-answer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({question_id: questionId, selected_option: selectedOption})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.correct) {
                            optionField.classList.add('bg-light-purple', 'border-success');
                            optionField.classList.remove('bg-light-gray');
                            explanationDiv.style.display = 'none';
                        } else {
                            optionField.classList.add('bg-danger', 'border-danger');
                            optionField.classList.remove('bg-light-gray');

                            const correctOption = document.querySelector(`#option-${currentQuestionIndex}-${correctAnswer}`);
                            if (correctOption) {
                                correctOption.classList.add('bg-light-purple', 'border-success');
                                correctOption.classList.remove('bg-light-gray');
                            }

                            explanationTextSpan.textContent = explanationText;
                            explanationDiv.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

        document.querySelectorAll('.submit-btn').forEach((btn, index) => {
            btn.addEventListener('click', function (event) {
                event.preventDefault();

                if (!confirm("Are you sure you want to submit all questions?")) return;

                let allAnswered = true;
                const questionForms = document.querySelectorAll('.question');
                questionForms.forEach((form, index) => {
                    const selectedOption = form.querySelector('input[name="selected_option"]:checked');
                    if (!selectedOption) {
                        allAnswered = false;
                    }
                });

                if (!allAnswered) {
                    document.getElementById('error-message').style.display = 'block';
                } else {
                    document.getElementById('questionsDiv').style.display = 'none';
                    document.getElementById('success-message').style.display = 'block';

                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                }
            });
        });
    </script>

@endsection
