@extends('components.app')

@section('content')

<style>

/* ================= MONEYCONTROL STYLE ================= */

.news-wrapper{
    max-width:1150px;
    margin:auto;
}

/* Layout */
.news-layout{
    display:flex;
    gap:30px;
}

.left-news{
    flex:0 0 70%;
}

.right-news{
    flex:0 0 30%;
}

/* News Item (Horizontal Layout) */
.news-item{
    display:flex;
    gap:20px;
    padding:22px 0;
    border-bottom:1px solid #e5e7eb;
}

.news-item:last-child{
    border-bottom:none;
}

.news-thumb{
    width:220px;
    height:140px;
    object-fit:cover;
    border-radius:6px;
}

.news-content{
    flex:1;
}

/* Title */
.news-title{
    font-size:20px;
    font-weight:700;
    line-height:1.4;
    margin-bottom:8px;
}

.news-title a{
    color:#111;
    text-decoration:none;
}

.news-title a:hover{
    color:#6f42c1;
}

/* Description */
.news-desc{
    font-size:14px;
    color:#555;
    margin-bottom:8px;
}

/* Meta */
.news-meta{
    font-size:13px;
    color:#6b7280;
}
.news-box{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:10px;
    padding:0 20px;
}

/* Sidebar */
.sidebar-box{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:18px;
}

.sidebar-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:15px;
     color:#6f42c1;
}

.related-list{
    list-style:none;
    padding:0;
    margin:0;
}

.related-list li{
    padding:12px 0;
    border-bottom:1px solid #eee;
}

.related-list li:last-child{
    border-bottom:none;
}

.related-list a{
    font-size:14px;
    font-weight:600;
    color:black;
    text-decoration:none;
}

.related-list a:hover{
    text-decoration:underline;
}

/* Pagination */
.pagination{
    justify-content:center;
    margin-top:25px;
}
.pagination .page-link {
    color:#6f42c1 !important;
    border-color:#e5e7eb;
}

.pagination .active .page-link {
    background:#6f42c1 !important;
    border-color:#6f42c1 !important;
    color:#fff !important;
}

.pagination .page-link:hover {
    background:#f3f0ff;
    color:#6f42c1 !important;
}
/* Dark mode pagination */
body.dark{
--bg:#05070c;
--card:#0b0f19;
--text:#f8fafc;
--muted:#9ca3af;
--border:#1f2933;
--chart-fill:linear-gradient(180deg,rgba(34,197,94,.35),rgba(0,0,0,.05));
}
body.dark .page-link {
    background-color: #0f172a !important;
    color: #ffffff !important;
    border-color: #1e293b !important;
}

body.dark .page-item.active .page-link {
    background-color: #6f42c1 !important;
    border-color: #6f42c1 !important;
}
/* ================= DARK THEME ================= */

/* Page Heading */
body.dark h3 {
    color: #f1f5f9;
}

/* Main News Box */
body.dark .news-box {
    background-color: #0b0f19;
    border-color: #334155;
}

/* Sidebar Box */
body.dark .sidebar-box {
    background-color: #0b0f19;
    border-color: #334155;
}

/* News Item Divider */
body.dark .news-item {
    border-bottom: 1px solid #334155;
}

/* Title */
body.dark .news-title a {
    color: #f8fafc;
}

body.dark .news-title a:hover {
    color: #8b5cf6;
}

/* Description */
body.dark .news-desc {
    color: #cbd5e1;
}

/* Meta Text */
body.dark .news-meta {
    color: #94a3b8;
}

/* Sidebar List */
body.dark .related-list li {
    border-bottom: 1px solid #334155;
}

body.dark .related-list a {
    color: #e2e8f0;
}

body.dark .related-list a:hover {
    color: #8b5cf6;
}

/* Editor Articles small text */
body.dark .text-muted {
    color: #94a3b8 !important;
}

/* Pagination */
body.dark .pagination .page-link {
    background-color: #1e293b !important;
    color: #e2e8f0 !important;
    border-color: #334155 !important;
}

body.dark .pagination .page-link:hover {
    background-color: #334155 !important;
}

body.dark .pagination .page-item.active .page-link {
    background-color: #6f42c1 !important;
    border-color: #6f42c1 !important;
    color: #ffffff !important;
}

/* Responsive */
@media(max-width:992px){
    .news-layout{
        flex-direction:column;
    }
    .left-news,.right-news{
        width:100%;
    }
    .news-item{
        flex-direction:column;
    }
    .news-thumb{
        width:100%;
        height:200px;
    }
}

</style>


<div class="container news-wrapper py-4">

    <h3 class="mb-4 fw-bold">
        {{ ucfirst($category) }}
    </h3>

    <div class="news-layout">

        {{-- LEFT SECTION --}}
       <div class="left-news">

    <div class="news-box">

        @if(isset($data['news']) && $data['news']->count())

            @foreach($data['news'] as $item)
            <div class="news-item">

                  <img 
        src="{{ $item['urlToImage'] ?? asset('no-image.png') }}"
        class="news-thumb"
        alt="News Image"
        loading="lazy"
        onload="if(this.naturalWidth < 200) this.src='{{ asset('no-image.png') }}';"
        onerror="this.onerror=null;this.src='{{ asset('no-image.png') }}';"
    >

                <div class="news-content">

                    <div class="news-title">
                        <a href="{{ route('news.show', urlencode($item['title'])) }}">
                            {{ is_array($item['title']) ? '' : $item['title'] }}
                        </a>
                    </div>

                    @if(!empty($item['description']))
                    <div class="news-desc">
                        {{ is_array($item['description']) ? '' : Str::limit($item['description'], 180) }}
                    </div>
                    @endif

                    <div class="news-meta">
                        {{ is_array($item['source']) ? '' : ($item['source'] ?? 'News') }}
                        •
                        @if(!empty($item['published_at']))
                            {{ \Carbon\Carbon::parse($item['published_at'])->diffForHumans() }}
                        @endif
                    </div>

                </div>
            </div>
            @endforeach

            <div class="mt-4 text-center">
                {{ $data['news']->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        @endif

    </div>

</div>
        {{-- RIGHT SIDEBAR --}}
        <div class="right-news">

            @if(isset($data['related']) && count($data['related']))
            <div class="sidebar-box">

                <div class="sidebar-title">
                    Related News
                </div>

                <ul class="related-list">
                    @foreach($data['related'] as $rel)
                    <li>
                        <a href="{{ route('news.show', urlencode($item['title'])) }}">
                            {{ is_array($rel['title']) ? '' : $rel['title'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>

            </div>
            @endif
{{-- ============================= --}}
{{-- Database Articles Section --}}
{{-- ============================= --}}

@if(isset($articles) && $articles->count())
<br/>

<div class="right-news">
    <div class="sidebar-box">

        <div class="sidebar-title">
            Editors Articles
        </div>

        <ul class="related-list">

          @foreach($articles->take(5) as $article)
    <li class="mb-3">

        <a href="{{ route('articles.show', $article->slug) }}" class="d-block fw-semibold">
            {{ $article->title }}
        </a>

        <small class="text-muted d-block mt-1">
            {{ $article->author->name ?? 'Editorial Team' }}
            •
            {{ optional($article->published_at)->diffForHumans() }}
        </small>

        <p class="mt-1 mb-0 text-muted" style="font-size: 13px;">
            {{ \Illuminate\Support\Str::limit($article->short_description, 120) }}
        </p>

    </li>
@endforeach


        </ul>

        {{-- View All Button --}}
        <div class="text-center mt-3">
            <a href="{{ route('articles.index') }}"
               class="btn"
               style="background-color:#6f42c1; color:#fff; border-color:#6f42c1;">
                View All Articles
            </a>
        </div>

    </div>
</div>
@endif


        </div>

    </div>

</div>

@endsection
