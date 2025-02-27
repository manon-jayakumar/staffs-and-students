@extends('layouts.app')
@section('title', 'Homepage')
@section('content')
    <div class="container">
        <h2>Course Tests</h2>

        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search for a test..." onkeyup="filterTests()">

        @foreach ($tests as $test)
            <a href="{{ route('test.test_description', ['id' => $test->id]) }}" class="text-decoration-none test-card">
                <div class="card mb-3">
                    <div class="card-body"
                         style="background-color: {{ getStatusColor($test->start_date, $test->end_date) }}">

                        <h3 class="font-bold text-lg test-title">{{ $test->title }}</h3>
                        <p class="text-gray-600">{{ $test->description }}</p>

                        <div class="flex items-center space-x-2 text-gray-600 mt-2">
                            <span>📅 {{ \Carbon\Carbon::parse($test->start_date)->format('d M, Y') }}</span>
                            <span>⏳ {{ $test->timer }} mins</span>
                        </div>

                        <div class="flex items-center space-x-2 text-gray-600 mt-2">
                            <span>📋 {{ count($test->questions) }} Questions</span>
                        </div>

                        <div class="flex items-center space-x-2 text-gray-600 mt-2">
                            <span>🏷 Batch: {{ $test->batch }}</span>
                        </div>

                        <span class="absolute right-4 px-2 rounded-full text-xs"
                              style="background-color: {{ getBadgeColor($test->start_date, $test->end_date) }};">
                           {{ getStatusLabel($test->start_date, $test->end_date) }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach

        <p id="noResults" class="text-center text-gray-600 mt-3" style="display: none;">--------- No Tests Found ---------</p>

    </div>

    <script>
        function filterTests() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let testCards = document.querySelectorAll('.test-card');
            let noResultMessage = document.getElementById('noResults');

            let hasResults = false;

            testCards.forEach(card => {
                let title = card.querySelector('.test-title').innerText.toLowerCase();
                if (title.includes(input)) {
                    card.style.display = 'block';
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                }
            });

            noResultMessage.style.display = hasResults ? 'none' : 'block';
        }

    </script>

    <script>
        <?php
        function getStatusLabel($start, $end)
        {
            $now = \Carbon\Carbon::now();
            if ($now->lt($start)) return "Yet to start";
            if ($now->between($start, $end)) return "Ongoing";
            return "Completed";
        }

        function getStatusColor($start, $end)
        {
            $now = \Carbon\Carbon::now();
            if ($now->lt($start)) return "#BEE3F8";
            if ($now->between($start, $end)) return "#D6BCFA";
            return "#E2E8F0";
        }

        function getBadgeColor($start, $end)
        {
            $now = \Carbon\Carbon::now();
            if ($now->lt($start)) return "#FACC15";
            if ($now->between($start, $end)) return "#34D399";
            return "#CBD5E0";
        }
        ?>

    </script>

@endsection

