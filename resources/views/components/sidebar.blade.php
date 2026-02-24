@props([
    'allCategories' => collect()
])

<!-- Sidebar -->
<div id="sidebar" class="sidebar">

    <!-- Header -->
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Menu</h6>

        <!-- Close Button -->
        <button id="sidebarClose" class="sidebar-close">
            ✕
        </button>
    </div>

    <!-- Home -->
    <a href="/" class="sidebar-link">
        Home
    </a>

    <!-- Dynamic Categories -->
    @foreach($allCategories as $cat)

        <div class="sidebar-group">
            @php
            $catName = Str::lower($cat->name); 
            @endphp
            <!-- Category Toggle -->
           {{-- Category --}}
<div class="sidebar-category">
    <a href="@if($catName === 'markets')
                            {{url('/')}} 
                        @else
                            {{ route('category.show', ['name' => $cat->name]) }}
                        @endif" 
       class="sidebar-link fw-semibold">
        {{ $cat->name }}
    </a>

    <span class="arrow" onclick="toggleCategory({{ $cat->id }})"
          id="arrow-{{ $cat->id }}" 
          style="cursor:pointer;">
        ▾
    </span>
</div>

{{-- Subcategories --}}
<div class="sidebar-sub-wrapper" id="sub-{{ $cat->id }}">
    @foreach($cat->subcategories as $sub)
     @php 
                $subName = Str::lower($sub->name);
            @endphp
        <a href="@if($subName === 'sensex/nifty')
                            {{ route('sensex.index') }} 
                        @elseif($subName === 'stock')
                            {{ url('/stock-news') }} 
                             @else
                            {{ route('category.show', ['name' => $sub->name]) }}
                        @endif" 
           class="sidebar-sub">
            {{ $sub->name }}
        </a>
    @endforeach
</div>
        </div>

    @endforeach

</div>

<!-- Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('sidebarToggle');
const closeBtn = document.getElementById('sidebarClose');

/* Open Sidebar */
toggleBtn.addEventListener('click', () => {
    sidebar.classList.add('active');
    overlay.classList.add('active');
});

/* Close Sidebar */
closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});

/* Close when clicking overlay */
overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});

/* Accordion */
function toggleCategory(id) {
    const sub = document.getElementById('sub-' + id);
    const arrow = document.getElementById('arrow-' + id);

    if (sub.style.maxHeight) {
        sub.style.maxHeight = null;
        arrow.classList.remove('rotate');
    } else {
        sub.style.maxHeight = sub.scrollHeight + "px";
        arrow.classList.add('rotate');
    }
}
</script>

