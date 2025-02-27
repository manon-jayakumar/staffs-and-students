@extends('layouts.app')
@section('title', 'All Leave Requests')
@section('content')
    <div class="container">
        <h2 class="text-center">All Leave Requests</h2>
        <br>
        <br>

        <table id="leaveRequestsTable" class="display">
            <thead>
            <tr>
                <th>Student Name</th>
                <th>Roll Number</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leaveRequests as $leaveRequest)
                <tr>
                    <td>{{ $leaveRequest->student_name }}</td>
                    <td>{{ $leaveRequest->roll_number }}</td>
                    <td>{{ $leaveRequest->reason }}</td>
                    <td>
                        <a href="{{ route('view.leave.request', $leaveRequest->id) }}" class="btn bg_color text-white btn-sm">Show</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

    <script>
        $(document).ready(function() {
            $('#leaveRequestsTable').DataTable();
        });
    </script>

@endsection

