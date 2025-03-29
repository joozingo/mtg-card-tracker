<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MTG Card Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'figtree', sans-serif;
            background-color: #f7fafc;
            color: #1a202c;
        }

        .navbar {
            background-color: #2d3748;
            color: white;
            padding: 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .card-item {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
            transition: transform 0.2s;
        }

        .card-item:hover {
            transform: translateY(-5px);
        }

        .card-image {
            width: 100%;
            height: auto;
        }

        .card-content {
            padding: 1rem;
        }

        .search-form {
            background-color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            background-color: #4a5568;
            color: white;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2d3748;
        }

        .pagination {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }

        /* Mobile menu styles */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            width: 100%;
            margin-top: 1rem;
        }

        .mobile-menu a {
            display: block;
            padding: 0.75rem 0;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-menu a:last-child {
            border-bottom: none;
        }

        .desktop-menu {
            display: flex;
            align-items: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .desktop-menu {
                display: none;
            }

            .mobile-menu.active {
                display: flex;
            }

            /* Make tables responsive */
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>
</body>

</html>