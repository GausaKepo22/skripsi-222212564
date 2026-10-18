{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard Potensi Pariwisata Klaten')</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    /* Body & Layout */
    body { 
        margin: 0; 
        min-height: 100vh; 
        display: flex; 
        flex-direction: column; 
        background-color: white; /* penting: putih full page */
        color: #1F2937; 
    }
    main { flex: 1; }
    footer { flex-shrink: 0; }

    /* Card hover efek */
    .card-hover:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* Navbar hover + active */
    nav a { transition: color 0.3s, font-weight 0.3s; }
    nav a:hover { color: #60A5FA; }
    nav a.active { color: #3B82F6; font-weight: 700; }
    nav a.active:hover { color: #2563EB; }

    /* Map z-index */
    .leaflet-container { z-index:0; }
</style>
</head>

<body>

    <!-- Header/Navbar -->
    @include('partials.header')

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')


<div class="fixed bottom-6 right-6 z-[9999] group">

    <div class="absolute bottom-20 left-1/2 -translate-x-[90%]
                bg-slate-900 text-white text-sm
                px-3 py-2 rounded-lg shadow-lg
                opacity-0 group-hover:opacity-100
                transition duration-300 whitespace-nowrap
                pointer-events-none">
        Informasi
    </div>

    <a href="{{ url('/information') }}"
    class="fixed bottom-6 right-6 z-[9999]
            w-16 h-16
            rounded-full
            bg-gradient-to-r from-yellow-300 to-orange-500
            text-blue-600
            shadow-xl
            hover:shadow-2xl
            hover:scale-110
            transition-all duration-300
            flex flex-col items-center justify-center">

        <svg xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 3a6 6 0 00-3.6 10.8c.7.5 1.1 1.3 1.1 2.2V17h5v-.98c0-.9.4-1.7 1.1-2.2A6 6 0 0012 3z" />
            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M9.75 17h4.5M10.5 20h3" />
        </svg>

        <span class="text-[11px] font-bold text-blue-600 leading-none mt-1">
            Info
        </span>

    </a>

</div>


<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@stack('scripts')

</body>
</html>