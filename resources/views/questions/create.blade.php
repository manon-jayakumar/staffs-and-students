@extends('layouts.app')
@section('title', 'Create Questions')

@section('content')
    <div class="container">
        <h2 class="text-center">Create Questions</h2>

        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
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
                            <button type="button" class="btn btn-danger ms-2 remove-answer">X</button>
                        </div>
                        <div class="answer mb-2 d-flex align-items-center">
                            <input type="radio" name="questions[1][correct_answer]" value="1">
                            <input type="text" name="questions[1][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                            <button type="button" class="btn btn-danger ms-2 remove-answer">X</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary mt-2 add-answer" data-question-index="1">+ Add Answer</button>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Explanation (Optional)</label>
                        <textarea name="questions[1][explanation]" class="form-control" placeholder="Enter explanation"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3" id="pagination">
                <button class="btn btn-primary pagination-btn" data-question-index="1">1</button>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-outline-primary" id="add-question">+ Add Question</button>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Submit All Questions</button>
        </form>
    </div>

    <script>
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
                <button type="button" class="btn btn-danger ms-2 remove-answer">X</button>
            </div>
            <div class="answer mb-2 d-flex align-items-center">
                <input type="radio" name="questions[${questionIndex}][correct_answer]" value="1">
                <input type="text" name="questions[${questionIndex}][answers][]" class="form-control ms-2" placeholder="Type answer here" required>
                <button type="button" class="btn btn-danger ms-2 remove-answer">X</button>
            </div>
        </div>

        <button type="button" class="btn btn-outline-primary mt-2 add-answer" data-question-index="${questionIndex}">+ Add Answer</button>

        <div class="mb-3 mt-3">
            <label class="form-label">Explanation (Optional)</label>
            <textarea name="questions[${questionIndex}][explanation]" class="form-control" placeholder="Enter explanation"></textarea>
        </div>
    `;

            container.appendChild(div);

            let pagination = document.getElementById('pagination');
            pagination.innerHTML = "";

            if (totalQuestions > 1) {
                for (let i = 1; i <= totalQuestions; i++) {
                    let pageButton = document.createElement('button');
                    pageButton.classList.add('btn', 'btn-outline-primary', 'ms-2', 'pagination-btn');
                    pageButton.setAttribute('data-question-index', i);
                    pageButton.innerText = i;
                    pagination.appendChild(pageButton);
                }
            }

        });

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
            <button type="button" class="btn btn-danger ms-2 remove-answer">X</button>
        `;
                container.appendChild(div);
            }

            if (e.target.classList.contains('remove-answer')) {
                e.target.parentElement.remove();
            }
        });

    </script>
@endsection
