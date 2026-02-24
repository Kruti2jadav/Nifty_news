@extends('components.app')

@section('content')
<div class="container my-4">
    <div class="row">

        {{-- Main News Section --}}
        <div class="col-lg-8">

            {{-- Indian News --}}
            <h3 class="mb-3">India News</h3>
            @forelse($indiaNews as $news)
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0">
                       <div class="col-md-4 news-img-box">
    <img 
        src="{{ $news['urlToImage'] ?? asset('no-image.png') }}"
        class="news-img"
        alt="News Image"
        loading="lazy"
        onload="if(this.naturalWidth < 200) this.src='{{ asset('no-image.png') }}';"
        onerror="this.onerror=null;this.src='{{ asset('no-image.png') }}';"
    >
</div>


                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $news['title'] }}</h5>
                                <p class="card-text">{{ $news['description'] }}</p>
                                <a href="{{ route('news.show', urlencode($news['title'])) }}" target="_blank" class="btn btn-sm btn-purple">Read More</a>
                                <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($news['publishedAt'])->diffForHumans() }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No Indian news found.</p>
            @endforelse

            {{-- Foreign/World News --}}
            <h3 class="mb-3 mt-4">World News</h3>
            @forelse($worldNews as $news)
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0">
                    <div class="col-md-4 news-img-box">
    <img 
        src="{{ $news['urlToImage'] ?? asset('no-image.png') }}"
        class="news-img"
        alt="News Image"
        loading="lazy"
        onload="if(this.naturalWidth < 200) this.src='{{ asset('no-image.png') }}';"
        onerror="this.onerror=null;this.src='{{ asset('no-image.png') }}';"
    >
</div>


                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $news['title'] }}</h5>
                                <p class="card-text">{{ $news['description'] }}</p>
                                <a href="{{ route('news.show', urlencode($news['title'])) }}" 
   class="btn btn-sm btn-purple">
   Read More
</a>
                                <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($news['publishedAt'])->diffForHumans() }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No World news found.</p>
            @endforelse

            {{-- Pagination --}}
            <nav>
                <ul class="pagination">
                    @if($page > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $page - 1 }}">Previous</a>
                        </li>
                    @endif
                    <li class="page-item">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ url()->current() }}?page={{ $page + 1 }}">Next</a>
                    </li>
                </ul>
            </nav>

        </div>

        {{-- Sidebar: Breaking News --}}
      <div class="col-lg-4">
    <div class="card breaking-card shadow-sm border-0">

        <div class="card-header fw-bold">
            🔥 Breaking News
        </div>

        <ul class="list-group list-group-flush">
            @forelse($breakingNews as $news)

                <li class="list-group-item breaking-item">
                    <a href="{{ route('news.show', urlencode($news['title'])) }}"
                       class="text-decoration-none fw-semibold">
                        {{ $news['title'] }}
                    </a>

                    <br>

                    <small class="breaking-time">
                        {{ \Carbon\Carbon::parse($news['publishedAt'])->diffForHumans() }}
                    </small>
                </li>

            @empty

                <li class="list-group-item breaking-item">
                    No breaking news.
                </li>

            @endforelse
        </ul>
    </div>
</div>

    </div>
</div>

{{-- Custom CSS --}}
<style>
.btn-purple {
    background-color: #6f42c1;
    color: #fff;
}

.btn-purple:hover {
    background-color: #5936a2;
    color: #fff;
}
/* ===== NEWS CARD IMAGE FIX ===== */

.news-img-box {
    height: 160px;
    overflow: hidden;
}
a{
    color:black;
}
.news-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center; /* Focus center */
    border-radius: 6px 0 0 6px;
    background: #f3f4f6; /* Prevent blank flash */
}
a:hover {
    color: #6f42c1;
    text-decoration: underline;
}

/* Make cards same height */
.card {
    min-height: 180px;
}
/* =====================
   FORCE DARK BACKGROUND
===================== */
body.dark a{
    color:white;
}
body.dark .breaking-card {
    background: #020617 !important;   /* Dark solid */
}

body.dark .breaking-card .list-group {
    background: #020617 !important;   /* UL background */
}

body.dark .breaking-item {
    background: #020617 !important;   /* LI background */
    border-color: #1e293b !important;
}

body.dark .breaking-item:hover {
    background: #020617 !important;
}

/* Remove Bootstrap default */
body.dark .list-group-item {
    background-color: #020617 !important;
}

</style>
@endsection
