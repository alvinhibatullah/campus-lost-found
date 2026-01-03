<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Lost & Found')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Default Background (bisa ditimpa oleh page lain) */
        body { 
            background-color: #f8f9fa; 
        }
        
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        
        /* Navbar Custom Style */
        .navbar-custom {
            background: linear-gradient(90deg, #00d2ff, #3a7bd5) !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 shadow-sm">
        <div class="container-fluid px-4"> 
            <a class="navbar-brand fw-bold" href="{{ url('/menu') }}">
                <i class="fas fa-search-location me-2"></i>Campus Lost & Found
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="ms-auto d-flex align-items-center">
                    
                    @auth
                        <a href="{{ route('home') }}" class="d-block me-3" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                                alt="Profile"
                                class="rounded-circle border border-2 border-white"
                                style="width: 35px; height: 35px; object-fit: cover;">
                        </a>

                        <span class="text-white fw-bold me-3">
                            Halo, {{ Auth::user()->name }}
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3" type="submit">
                                Logout
                            </button>
                        </form>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4"> 
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    @stack('scripts')
</body>
</html>