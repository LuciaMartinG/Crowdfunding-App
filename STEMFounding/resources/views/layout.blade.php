<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <nav class="container d-flex justify-content-between align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <a href="/" class="nav-link text-white">Projects</a>
                </li>
                @if(Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a href="/project/create" class="btn btn-primary ms-3">Create Project</a>
                    </li>
                @endif
            </ul>
            <ul class="nav">
                <li class="nav-item">
                    <a href="/users/{{ Auth::user()->id }}" class="nav-link text-white">{{ Auth::user()->name }}</a>
                </li>
                <li class="nav-item">
                    <form action="/logout" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0">© 2024 STEMFounding | All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
