 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NiftyNews</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @include('components.styles')
</head>
<body>

<x-navbar />

<div class="d-flex">
   @if(session()->has('user_id'))
    @php
        $user = \App\Models\Users::find(session('user_id'));
    @endphp

    @if($user && $user->role === 'editor')
        <x-editorsidebar />
    @else
        <x-sidebar />
    @endif
@else
    <x-sidebar />
@endif

    <main class="main-content flex-fill p-4">
        @yield('content')
    </main>
</div>


<script>
/* Sidebar Toggle */
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});
</script>
@include('components.footer')
</body>
</html>
