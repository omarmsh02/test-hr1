<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WorkWise - HR Management System</title>

    <!-- Favicon (Font Awesome clipboard-list icon as SVG) -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path fill='%232c3e50' d='M336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM96 424c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm96-192c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24zm128 368c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-96c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-96c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16z'/></svg>" type="image/svg+xml">

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
            left: 0;
            top: 0;
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

        /* Navbar Styles - ONLY CHANGE MADE: position changed to sticky */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            height: 60px;
            margin-left: 250px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .avatar-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .topbar-btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            margin-right: 0.5rem;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @include('components.__sidebar')

    <div class="content-wrapper">
        <!-- Topbar - Now sticky at the top -->
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
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>