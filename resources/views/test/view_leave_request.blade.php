@extends('layouts.app')
@section('title', 'View Leave Request')
@section('content')
    <div class="container">
        <h2 class="text-center">{{ $leaveRequest->student_name }}'s Leave Request</h2>
        <br>
        <div class="card mb-3">
            <div class="card-body">

                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Name</label>
                    <input type="text" class="form-control"
                           id="student_name" name="student_name" value="{{ $leaveRequest->student_name }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="roll_number" class="form-label">Roll Number</label>
                    <input type="text" class="form-control"
                           id="roll_number" name="roll_number" value="{{ $leaveRequest->roll_number }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Applying</label>
                    <textarea class="form-control" id="reason" name="reason"
                              rows="3" disabled>{{ $leaveRequest->reason }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="leave_type" class="form-label">Leave Type</label>
                    <input class="form-control" id="leave_type" name="leave_type"
                              rows="3" disabled value="{{ $leaveRequest->leave_type }}">
                </div>

                <div class="mb-3">
                    <label for="from_date" class="form-label">From</label>
                    <input type="date" class="form-control" id="from_date"
                           name="from_date" value="{{ $leaveRequest->from_date }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="to_date" class="form-label">To</label>
                    <input type="date" class="form-control" id="to_date"
                           name="to_date" value="{{ $leaveRequest->to_date }}" disabled>
                </div>

                <a href="{{ route('view.all.leave.requests') }}" class="btn bg_color text-white w-100">Back</a>

            </div>
        </div>

    </div>

@endsection

