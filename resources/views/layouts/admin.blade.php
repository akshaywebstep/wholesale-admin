<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            <!-- Header -->
            @include('admin.partials.header')

            <!-- Main Content Area -->
            <main class="flex-1 p-6 md:p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('admin.partials.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>