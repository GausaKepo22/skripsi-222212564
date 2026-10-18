<nav class="bg-[#1A237E] fixed top-0 left-0 w-full z-20 shadow-md">
  <div class="flex items-center justify-between px-6 py-4 w-full">
    
    <!-- LOGO + TITLE -->
    <div class="flex items-center gap-3">
      <!-- Globe Icon -->
      <div class="bg-white/20 w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/30 transition duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="w-5 h-5 text-white" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <circle cx="12" cy="12" r="10" stroke-width="2"/>
            <path stroke-width="2" d="M2 12h20M12 2c3 4 3 16 0 20M12 2c-3 4-3 16 0 20"/>
        </svg>
      </div>
      <!-- Title -->
      <h1 class="text-xl font-bold text-white">
        TOURISM DEVELOPMENT POTENTIAL
      </h1>
    </div>
    
    <!-- Hamburger Button Mobile -->
    <button id="menu-btn" class="md:hidden text-white focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

    <!-- Menu Desktop -->
    <div class="hidden md:flex ml-auto space-x-6 font-semibold">
      <a href="{{ route('home') }}" 
         class="text-white hover:text-blue-300 transition {{ request()->routeIs('home') ? 'border-b-2 border-white pb-1' : '' }}">
         Beranda
      </a>

      <a href="{{ route('variabel') }}" 
         class="text-white hover:text-blue-300 transition {{ request()->routeIs('variabel') ? 'border-b-2 border-white pb-1' : '' }}">
         Indikator
      </a>

      <a href="{{ route('eksploratif') }}" 
         class="text-white hover:text-blue-300 transition {{ request()->routeIs('eksploratif') ? 'border-b-2 border-white pb-1' : '' }}">
         ODTW
      </a>

      <a href="{{ route('spasial') }}" 
         class="text-white hover:text-blue-300 transition {{ request()->routeIs('spasial') ? 'border-b-2 border-white pb-1' : '' }}">
         Spasial
      </a>
    </div>
  </div>

  <!-- Sidebar Mobile -->
  <div id="mobile-menu" 
       class="fixed top-0 left-0 h-full w-64 bg-[#1A237E] transform -translate-x-full transition-transform duration-300 ease-in-out z-30 md:hidden">
    
    <div class="px-6 py-10 flex flex-col space-y-6">
      <a href="{{ route('home') }}" class="text-white text-lg font-semibold hover:text-blue-300 transition" onclick="closeMenu()">Beranda</a>
      <a href="{{ route('variabel') }}" class="text-white text-lg font-semibold hover:text-blue-300 transition" onclick="closeMenu()">Indikator</a>
      <a href="{{ route('eksploratif') }}" class="text-white text-lg font-semibold hover:text-blue-300 transition" onclick="closeMenu()">ODTW</a>
      <a href="{{ route('spasial') }}" class="text-white text-lg font-semibold hover:text-blue-300 transition" onclick="closeMenu()">Spasial</a>
    </div>
  </div>

  <!-- Overlay -->
  <div id="overlay" 
       class="fixed inset-0 bg-black/50 opacity-0 invisible transition-opacity duration-300 z-20 md:hidden" 
       onclick="closeMenu()"></div>
</nav>

<script>
const menuBtn = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const overlay = document.getElementById('overlay');

menuBtn.addEventListener('click', () => {
  mobileMenu.classList.toggle('-translate-x-full');
  overlay.classList.toggle('opacity-0');
  overlay.classList.toggle('invisible');
});

function closeMenu() {
  mobileMenu.classList.add('-translate-x-full');
  overlay.classList.add('opacity-0');
  overlay.classList.add('invisible');
}
</script>