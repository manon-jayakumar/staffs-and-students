@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="text-center">
        <h1>Welcome to Dashboard, {{ auth()->user()->name }}</h1>
        <a href="{{ route('test.create') }}" class="btn bg_color text-white mt-3">Create Test</a>
        <a href="{{ route('view.all.leave.requests') }}" class="btn bg_color text-white mt-3">Leave Requests</a>
    </div>
@endsection
