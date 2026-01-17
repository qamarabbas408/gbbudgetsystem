<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GB Planning & Development Budget System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <style>
        /* Remove default DataTables border-bottom */
        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        /* Clean up the sorting icons */
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background-image: none !important;
        }

        /* Fix table alignment */
        .dataTables_wrapper {
            padding: 1.5rem;
            /* Give the controls some room from the edges */
        }
      
        input:focus {
            outline: none;
            /* removes default browser outline */
            border: 0.5px solid #4f46e5;
            /* adds a custom border color (indigo) */
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.5);
            /* subtle glow effect */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header Component -->
            <x-app-header />

            <!-- Dynamic Content -->
            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>


    <!-- Floating Action Button Component -->
    {{-- <x-floating-action-btn /> --}}
    {{-- @if (!request()->routeIs('reports.adpSummary'))
        <x-floating-action-btn />
    @endif --}}
    @hasSection('hide_floating_btn')
        {{-- Do nothing if this section exists --}}
    @else
        <x-floating-action-btn />
    @endif
    <!-- Toast Notifications -->
    <x-toast />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>s
    @stack('scripts')
    <!-- Add this in the <head> of layouts/app.blade.php -->



</body>

</html>
