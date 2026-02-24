@props([
    'fixedCategories' => [],
    'allCategories' => collect()
])

<nav class="navbar navbar-expand-lg px-4 py-2">
    <div class="d-flex w-100 align-items-center position-relative">

        {{-- Left: Sidebar & Brand --}}
        <div class="d-flex align-items-center">
            <button id="sidebarToggle" class="btn btn-light me-3">☰</button>
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">NiftyNews</a>
        </div>
 

    {{-- Mobile Toggle --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">

        {{-- Center Categories --}}
        @php
            $userRole = session('user_role') ?? null;
        @endphp

         <ul class="navbar-nav position-absolute start-50 translate-middle-x mb-0">
            @if($userRole === 'user'||$userRole === null)
                @foreach($fixedCategories as $cat)
                    <li class="nav-item dropdown d-flex align-items-center">
                        {{-- Main Link --}}
                        <a class="nav-link {{ request()->is(strtolower($cat['name'])) ? 'active-nav' : '' }}"
                           href="{{ $cat['url'] }}">
                            {{ $cat['name'] }}
                        </a>

                        {{-- Dropdown --}}
                        @if(!empty($cat['subcategories']) && $cat['subcategories']->count())
                            <a class="nav-link dropdown-toggle dropdown-toggle-split"
                               href="#"
                               role="button"
                               data-bs-toggle="dropdown">
                            </a>

                            <ul class="dropdown-menu shadow-sm">
                                @foreach($cat['subcategories'] as $sub)
                                    @php $subName = Str::lower($sub->name); @endphp
                                    <li>
                                        <a class="dropdown-item"
                                           href="
                                            @if($subName === 'sensex/nifty')
                                                {{ route('sensex.index') }} 
                                            @elseif($subName === 'stock')
                                                {{ url('/stock-news') }}  
                                            @else
                                                {{ route('category.show', ['name' => $sub->name]) }}
                                            @endif
                                           ">
                                            {{ $sub->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif
        </ul>

        {{-- Right Section --}}
        <div class="d-flex align-items-center gap-3 ms-auto">
            <button class="theme-btn" id="themeBtn">
                <i class="bi bi-sun" id="themeIcon"></i>
            </button>

            {{-- Language Dropdown --}}
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-globe"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm lang-dropdown">
                    <li><a class="dropdown-item" href="#">English</a></li>
                    <li><a class="dropdown-item" href="#">हिन्दी</a></li>
                    <li><a class="dropdown-item" href="#">ગુજરાતી</a></li>
                    <li><a class="dropdown-item" href="#">मराठी</a></li>
                    <li><a class="dropdown-item" href="#">বাংলা</a></li>
                    <li><a class="dropdown-item" href="#">தமிழ்</a></li>
                    <li><a class="dropdown-item" href="#">తెలుగు</a></li>
                    <li><a class="dropdown-item" href="#">ಕನ್ನಡ</a></li>
                    <li><a class="dropdown-item" href="#">മലയാളം</a></li>
                    <li><a class="dropdown-item" href="#">ਪੰਜਾਬੀ</a></li>
                    <li><a class="dropdown-item" href="#">اردو</a></li>
                </ul>
            </div>

            {{-- Login / Logout --}}
            @if(session()->has('user_id'))
                <a href="{{ url('/logout') }}" class="btn btn-danger btn-sm ms-2">
                    <i class="bi bi-box-arrow-right"></i> 
                </a>
            @else
                <a href="{{ url('/signin') }}" class="btn login-btn btn-sm ms-2">
                    <i class="bi bi-person"></i> 
                </a>
            @endif
        </div>
    </div>
</nav>
<script>
/* Dark Mode */
const btn = document.getElementById('themeBtn');
const icon = document.getElementById('themeIcon');

if(localStorage.theme === 'dark'){
    document.body.classList.add('dark');
    icon.className = 'bi bi-moon';
}

btn.onclick = () => {
    document.body.classList.toggle('dark');
    const dark = document.body.classList.contains('dark');
    icon.className = dark ? 'bi bi-moon' : 'bi bi-sun';
    localStorage.theme = dark ? 'dark' : 'light';
};
</script>
