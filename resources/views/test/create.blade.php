@extends('layouts.app')
@section('title', 'Create Test')
@section('content')
    <div class="container">
        <div id="testFormDiv">
            <h2 class="text-center">Create Test</h2>

            <div class="mb-3">
                <label class="form-label">Test Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter test title" value="{{ old('title') }}">
                <span class="text-danger" id="title_error"></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Test Description</label>
                <textarea name="description" class="form-control" placeholder="Enter test description">{{ old('description') }}</textarea>
                <span class="text-danger" id="description_error"></span>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    <span class="text-danger" id="start_date_error"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    <span class="text-danger" id="end_date_error"></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Timer (Minutes)</label>
                <input type="number" name="timer" class="form-control" placeholder="Enter time in minutes" value="{{ old('timer') }}">
                <span class="text-danger" id="timer_error"></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Assign to Batch</label>
                <select name="batch" class="form-control">
                    <option value="">Select batch</option>
                    <option value="Batch 1" {{ old('batch') == 'Batch 1' ? 'selected' : '' }}>Batch 1</option>
                    <option value="Batch 2" {{ old('batch') == 'Batch 2' ? 'selected' : '' }}>Batch 2</option>
                </select>
                <span class="text-danger" id="batch_error"></span>
            </div>

            <div class="mb-3">
                <label class="form-label">Created Questions</label>
                <ul id="created-questions-list" class="list-group">
                    <li class="list-group-item text-muted">No questions created yet.</li>
                </ul>
                <span class="text-danger" id="questions_error"></span>
            </div>

            <div class="text-center">
                <button type="button" class="btn bg_color text-white" data-bs-toggle="modal" data-bs-target="#createQuestionModal">
                    Create Questions
                </button>
            </div>

            <a onclick="validation()" class="btn bg_color text-white w-100 mt-3">Save</a>

        </div>

        <div class="text-center" id="testFormSuccessDiv" style="display: none">
            <h1>Test Created Successfully</h1>
            <a href="{{ route('test.create') }}" class="btn bg_color text-white mt-3">Create Another Test</a>
        </div>
    </div>


    <div class="modal fade" id="createQuestionModal" tabindex="-1" aria-labelledby="createQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createQuestionModalLabel">Create Questions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="question-form-container">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg_color text-white" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg_color text-white" id="save-question-btn">Save Questions</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script>

        function validation() {
            document.querySelectorAll('.text-danger').forEach(span => span.textContent = '');

            let isValid = true;

            const title = document.querySelector('input[name="title"]').value;
            if (!title.trim()) {
                document.getElementById('title_error').textContent = 'Test title is required.';
                isValid = false;
            }

            const description = document.querySelector('textarea[name="description"]').value;
            if (!description.trim()) {
                document.getElementById('description_error').textContent = 'Test description is required.';
                isValid = false;
            }

            const startDate = document.querySelector('input[name="start_date"]').value;
            if (!startDate.trim()) {
                document.getElementById('start_date_error').textContent = 'Start date is required.';
                isValid = false;
            }

            const endDate = document.querySelector('input[name="end_date"]').value;
            if (!endDate.trim()) {
                document.getElementById('end_date_error').textContent = 'End date is required.';
                isValid = false;
            }

            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                document.getElementById('start_date_error').textContent = 'Start date should be before the end date.';
                isValid = false;
            }

            if (endDate && startDate && new Date(endDate) < new Date(startDate)) {
                document.getElementById('end_date_error').textContent = 'End date should be after the start date.';
                isValid = false;
            }

            const timer = document.querySelector('input[name="timer"]').value;
            if (!timer || timer <= 0) {
                document.getElementById('timer_error').textContent = 'Timer must be a positive number.';
                isValid = false;
            }

            const batch = document.querySelector('select[name="batch"]').value;
            if (!batch) {
                document.getElementById('batch_error').textContent = 'Please select a batch.';
                isValid = false;
            }

            const questions = [];
            document.querySelectorAll('.question-section').forEach((section, index) => {
                const question = {};

                question.question = section.querySelector(`input[name="questions[${index + 1}][question]"]`).value;

                question.answers = [];
                section.querySelectorAll(`input[name="questions[${index + 1}][answers][]"]`).forEach((input) => {
                    question.answers.push(input.value);
                });

                const correctAnswer = section.querySelector(`input[name="questions[${index + 1}][correct_answer]"]:checked`);
                question.correct_answer = correctAnswer ? correctAnswer.value : null;  // Set the correct answer if selected

                question.explanation = section.querySelector(`textarea[name="questions[${index + 1}][explanation]"]`).value;

                questions.push(question);
            });

            if (questions.length === 0) {
                document.getElementById('questions_error').textContent = 'At least one question must be created.';
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: "{{ route('test.store') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        title: title,
                        description: description,
                        start_date: startDate,
                        end_date: endDate,
                        timer: timer,
                        batch: batch,
                        questions: questions
                    },
                    success: function(data) {
                        console.log(data.message);
                        document.getElementById('testFormDiv').style.display = "none";
                        document.getElementById('testFormSuccessDiv').style.display = "block";
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            } else {
                console.log("Form validation failed.");
            }
        }


        document.addEventListener("DOMContentLoaded", function () {
            let questionsArray = [];

            document.getElementById("save-question-btn").addEventListener("click", function () {
                const questionSections = document.querySelectorAll(".question-section");
                let hasError = false;
                questionsArray = [];

                questionSections.forEach((section, index) => {
                    const questionText = section.querySelector(`input[name="questions[${index + 1}][question]"]`).value;
                    const answers = [...section.querySelectorAll(`input[name="questions[${index + 1}][answers][]"]`)].map(input => input.value);
                    const correctAnswer = section.querySelector(`input[name="questions[${index + 1}][correct_answer]"]:checked`);
                    const explanation = section.querySelector(`textarea[name="questions[${index + 1}][explanation]"]`).value;

                    if (answers.length < 1 || answers.some(answer => !answer.trim())) {
                        section.querySelector('.error-message').textContent = 'At least one answer is required.';
                        hasError = true;
                    } else {
                        section.querySelector('.error-message').textContent = '';
                    }

                    if (!correctAnswer && answers.length > 0) {
                        section.querySelector('.error-message').textContent = 'Please select a correct answer.';
                        hasError = true;
                    }

                    if (questionText.trim() !== "" && !hasError) {
                        questionsArray.push({
                            question: questionText,
                            answers: answers,
                            correctAnswer: correctAnswer ? answers[correctAnswer.value] : "No correct answer selected",
                            explanation: explanation
                        });
                    }
                });

                if (!hasError) {
                    updateQuestionList();
                    bootstrap.Modal.getInstance(document.getElementById("createQuestionModal")).hide();
                }
            });

            function updateQuestionList() {
                const questionList = document.getElementById("created-questions-list");
                questionList.innerHTML = "";

                if (questionsArray.length === 0) {
                    questionList.innerHTML = `<li class="list-group-item text-muted">No questions created yet.</li>`;
                } else {
                    questionsArray.forEach((q, index) => {
                        const listItem = document.createElement("li");
                        listItem.classList.add("list-group-item");
                        listItem.innerHTML = `
                    <strong>Q${index + 1}: ${q.question}</strong>
                `;
                        questionList.appendChild(listItem);
                    });
                }
            }

            document.getElementById('createQuestionModal').addEventListener('show.bs.modal', function () {
                const formContainer = document.getElementById('question-form-container');
                const formHTML = `
            <div id="questions-container">
                <div class="question-section" data-question-index="1">
                    <div class="mb-3">
                        <label class="form-label">Question 1</label>
                        <input type="text" name="questions[1][question]" class="form-control" placeholder="Enter question" required>
                    </div>

                    <div id="answers-container-1">
                        <div class="answer mb-2 d-flex align-items-center">
                            <input type="radio" name="questions[1][correct_answer]" value="0">
                            <input type="text" name="questions[1][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                            <button type="button" class="btn bg_color text-white ms-2 remove-answer">X</button>
                        </div>
                        <div class="answer mb-2 d-flex align-items-center">
                            <input type="radio" name="questions[1][correct_answer]" value="1">
                            <input type="text" name="questions[1][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                            <button type="button" class="btn bg_color text-white ms-2 remove-answer">X</button>
                        </div>
                    </div>

                    <button type="button" class="btn bg_color text-white mt-2 add-answer" data-question-index="1">+ Add Answer</button>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Explanation (Optional)</label>
                        <textarea name="questions[1][explanation]" class="form-control" placeholder="Enter explanation"></textarea>
                    </div>

                    <div class="error-message text-danger"></div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3" id="pagination">
                <button class="btn bg_color text-white pagination-btn" data-question-index="1">1</button>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn bg_color text-white" id="add-question">+ Add Question</button>
            </div>
        `;

                formContainer.innerHTML = formHTML;

                initializeQuestionForm();
            });

            function initializeQuestionForm() {
                let questionIndex = 1;
                let totalQuestions = 1;

                document.getElementById('add-question').addEventListener('click', function() {
                    questionIndex++;
                    totalQuestions++;

                    let container = document.getElementById('questions-container');

                    document.querySelectorAll('.question-section').forEach(q => q.style.display = 'none');

                    let div = document.createElement('div');
                    div.classList.add('question-section');
                    div.setAttribute('data-question-index', questionIndex);
                    div.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Question ${questionIndex}</label>
                    <input type="text" name="questions[${questionIndex}][question]" class="form-control" placeholder="Enter question" required>
                </div>

                <div id="answers-container-${questionIndex}">
                    <div class="answer mb-2 d-flex align-items-center">
                        <input type="radio" name="questions[${questionIndex}][correct_answer]" value="0">
                        <input type="text" name="questions[${questionIndex}][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                        <button type="button" class="btn bg_color text-white ms-2 remove-answer">X</button>
                    </div>
                    <div class="answer mb-2 d-flex align-items-center">
                        <input type="radio" name="questions[${questionIndex}][correct_answer]" value="1">
                        <input type="text" name="questions[${questionIndex}][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                        <button type="button" class="btn bg_color text-white ms-2 remove-answer">X</button>
                    </div>
                </div>

                <button type="button" class="btn bg_color text-white mt-2 add-answer" data-question-index="${questionIndex}">+ Add Answer</button>

                <div class="mb-3 mt-3">
                    <label class="form-label">Explanation (Optional)</label>
                    <textarea name="questions[${questionIndex}][explanation]" class="form-control" placeholder="Enter explanation"></textarea>
                </div>

                <div class="error-message text-danger"></div>
            `;
                    container.appendChild(div);

                    updatePagination(totalQuestions);
                });

                function updatePagination(totalQuestions) {
                    let pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';

                    for (let i = 1; i <= totalQuestions; i++) {
                        let pageButton = document.createElement('button');
                        pageButton.classList.add('btn', 'bg_color', 'text-white', 'ms-2', 'pagination-btn');
                        pageButton.setAttribute('data-question-index', i);
                        pageButton.innerText = i;
                        pagination.appendChild(pageButton);
                    }
                }

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('pagination-btn')) {
                        let index = e.target.getAttribute('data-question-index');

                        document.querySelectorAll('.question-section').forEach(q => q.style.display = 'none');
                        document.querySelector(`.question-section[data-question-index="${index}"]`).style.display = 'block';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('add-answer')) {
                        let qIndex = e.target.getAttribute('data-question-index');
                        let container = document.getElementById(`answers-container-${qIndex}`);
                        let index = container.children.length;

                        let div = document.createElement('div');
                        div.classList.add('answer', 'mb-2', 'd-flex', 'align-items-center');
                        div.innerHTML = `
                    <input type="radio" name="questions[${qIndex}][correct_answer]" value="${index}">
                    <input type="text" name="questions[${qIndex}][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                    <button type="button" class="btn bg_color text-white ms-2 remove-answer">X</button>
                `;
                        container.appendChild(div);
                    }

                    if (e.target.classList.contains('remove-answer')) {
                        e.target.parentElement.remove();
                    }
                });
            }
        });


    </script>

@endsection
