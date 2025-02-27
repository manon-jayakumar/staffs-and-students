@extends('layouts.app')
@section('title', 'Test Description')
@section('content')
    <div class="container my-5">

        <a href="{{ route('home') }}" class="btn bg_color text-white mb-3">
            Back
        </a>

        <h2 class="text-center fw-bold">Test Description</h2>

        <div class="card mt-4">
            <div class="card-header bg_color text-white">
                <h5 class="fw-bold text-center">Welcome to the Introduction of {{ $test->title }}</h5>
            </div>
            <div class="card-body">
                <p>{{ $test->description }}</p>
            </div>
        </div>

        <div class="card mt-4 bg-light">
            <div class="card-header bg_color text-white">
                <h6 class="fw-bold">Here’s what you need to know before starting:</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-clock text-purple"></i> <b>Time limit:</b> {{ $test->timer }} mins</li>
                    <li><i class="fas fa-list text-purple"></i> <b>Questions:</b> {{ count($test->questions) }} questions, including multiple-choice and short-answer.</li>
                    <li><i class="fas fa-arrow-right text-purple"></i> <b>Navigation:</b> Use the “Submit All Questions” button to proceed; you can revisit previous questions.</li>
                    <li><i class="fas fa-upload text-purple"></i> <b>Submission:</b> Please submit before the time runs out.</li>
                </ul>
            </div>
        </div>

        <h6 class="text-center mt-4 text-purple fw-bold">A L L   T H E   B E S T !</h6>

        <div class="text-center mt-3">
            <a href="{{ route('test.questions', $test->id) }}" class="btn bg_color text-white w-50 fw-bold">Start</a>
        </div>
    </div>
@endsection
