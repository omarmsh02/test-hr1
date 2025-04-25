<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR Management System') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')

    <style>
        /* Sidebar Styles */
        .sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 250px;
            background-color: #2c3e50;
            position: fixed;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .sidebar-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .logout-btn {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1.5rem;
            display: block;
        }

        .logout-btn:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Navbar Styles */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            height: 60px; /* Reduced height */
            margin-left: 250px; /* Offset for the sidebar */
        }

        .avatar-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .topbar-btn {
            font-size: 0.875rem; /* Smaller font size */
            padding: 0.375rem 0.75rem; /* Reduced padding */
            margin-right: 0.5rem;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px; /* Offset for the sidebar */
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @include('components.__sidebar')

    <!-- Topbar -->
    @include('components.__topbar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Heading -->
        @if(isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>