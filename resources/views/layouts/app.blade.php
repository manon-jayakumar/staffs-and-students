<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        .bg_color {
            background-color: purple;
        }

        .bg_color_header {
            background-color: purple;
        }

        .bg_color:hover{
            background-color: #9c579c;
        }
    </style>
</head>
<body>
<header class="text-white p-3 bg_color_header">
    <nav class="container d-flex justify-content-between">
        <a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a>
        <div>
            <a href="{{ route('leave.request') }}" class="text-white text-decoration-none me-3">Apply Leave</a>
            @guest
                <a href="{{ route('login') }}" class="text-white text-decoration-none me-3">Login</a>
                <a href="{{ route('register') }}" class="text-white text-decoration-none">Register</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none me-3">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @endguest
        </div>
    </nav>
</header>
<main class="container mt-4">
    @yield('content')
</main>
</body>
</html>
