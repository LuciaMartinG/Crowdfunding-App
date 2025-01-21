<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Header -->
    <header class="bg-secondary text-white py-3">
        <nav class="container d-flex justify-content-between align-items-center">
            <ul class="nav">
               <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle text-white" id="projectsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Projects
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="projectsDropdown">
                        <li><a class="dropdown-item" href="/projects?state=active">Active</a></li>
                        <li><a class="dropdown-item" href="/projects?state=inactive">Inactive</a></li>
                        <li><a class="dropdown-item" href="/projects">All Projects</a></li>
                    </ul>
                </li>
                @if(Auth::check() && Auth::user()->role == 'entrepreneur')
                    <li class="nav-item">
                        <a href="/project/create" class="btn btn-secondary text-white ms-3">Create Project</a>
                    </li>
                    <li class="nav-item">
                        <a href="/user/projects" class="btn btn-secondary text-white ms-3">My Projects</a>
                    </li>
                @endif
                
                @if(Auth::check() && Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a href="/user" class="btn btn-secondary text-white ms-3">Users</a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->role == 'investor')
                    <li class="nav-item">
                        <a href="/investments/my-projects" class="btn btn-secondary text-white ms-3">My investments</a>
                    </li>
                @endif
            </ul>
            <ul class="nav">
                @if(Auth::check()) <!-- Verifica si el usuario está autenticado -->
                    <li class="nav-item">
                        <a href="/user/detail/{{ Auth::user()->id }}" class="nav-link text-white">{{ Auth::user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    </li>
                @else
                    <!-- Mostrar opciones para usuarios no autenticados -->
                    <li class="nav-item">
                        <a href="/login" class="btn btn-outline-light me-2">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-light">Register</a>
                    </li>
                @endif
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <br>
    <footer class="bg-secondary text-white text-center py-3 mt-auto">
        <p class="mb-0">© 2024 STEMFounding | All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
