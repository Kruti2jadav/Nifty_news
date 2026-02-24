@extends('components.app')

@section('content')
<div class="container my-5">
    <div class="row">

        {{-- ================= MAIN ARTICLE ================= --}}
        <div class="col-lg-8">

            {{-- Title --}}
            <h2 class="mb-3 fw-bold" style="color:#6f42c1;">
                {{ $article['title'] ?? 'Untitled Article' }}
            </h2>

            {{-- Published Time --}}
            @if(!empty($article['publishedAt']))
                <p class="text-muted mb-3">
                    {{ \Carbon\Carbon::parse($article['publishedAt'])->diffForHumans() }}
                </p>
            @endif

            {{-- Image --}}
           @if(!empty($article['urlToImage']))
    <div class="article-image-wrapper mb-4">
        <img src="{{ $article['urlToImage'] }}"
             alt="Article Image"
             onerror="this.src='{{ asset('no-image.png') }}'">
    </div>
@endif

            {{-- Description --}}
            @if(!empty($article['description']))
                <p class="lead fw-semibold">
                    {{ $article['description'] }}
                </p>
            @endif

            {{-- Clean Content --}}
            @php
                $cleanContent = $article['content'] ?? '';
                $cleanContent = preg_replace('/\[\+\d+\schars\]/', '', $cleanContent);
                $cleanContent = strip_tags($cleanContent);
                $cleanContent = trim($cleanContent);
            @endphp

            <div class="article-content">
                {!! nl2br(e($cleanContent ?: 'Full content not available from API.')) !!}
            </div>

        </div>

        {{-- ================= SIMILAR NEWS ================= --}}
       <div class="col-lg-4">
    <div class="card similar-news-card">
        <!-- Header -->
        <div class="card-header similar-news-header">
            Similar News
        </div>
        <!-- Body -->
        <div class="card-body p-0">
            @forelse($similarNews as $news)
                @php
                    $similarTitle = $news['title'] ?? '';
                @endphp
                <div class="similar-news-item">
                    <a href="{{ route('news.show', urlencode($similarTitle)) }}"
                       class="similar-news-link">
                        {{ $similarTitle }}
                    </a>
                    @if(!empty($news['publishedAt']))
                        <div class="similar-news-time">
                            {{ \Carbon\Carbon::parse($news['publishedAt'])->diffForHumans() }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted p-3">No similar news found.</p>
            @endforelse
        </div>
    </div>
</div>


    </div>
</div>


{{-- ================= CUSTOM STYLES ================= --}}
<style>
/* Bootstrap Purple */
.bg-purple {
    background-color: #6f42c1;
}

.text-purple {
    color: #f1eff5;
}

/* Hover effect */
.hover-purple:hover {
    color: #6f42c1 !important;
}

/* Article content styling */
.article-content {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
}

.article-content p {
    margin-bottom: 15px;
}
/* ===== ARTICLE IMAGE FIXED SIZE ===== */

.article-image-wrapper {
    width: 100%;
    height: 420px;        /* Fixed height like news portals */
    overflow: hidden;
    border-radius: 12px;
}

.article-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;    /* Crop nicely */
    display: block;
}

/* ===== SIMILAR NEWS DARK CARD ===== */

/* ===== SIMILAR NEWS - LIGHT MODE ===== */

.similar-news-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    color: #111827;
}

/* Header */
.similar-news-header {
    background: #f9fafb;
    color: #111827;
    font-weight: 600;
    padding: 14px 18px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 16px;
}

/* Each News Item */
.similar-news-item {
    padding: 15px 18px;
    border-bottom: 1px solid #e5e7eb;
    transition: background 0.3s ease;
}

/* Remove last border */
.similar-news-item:last-child {
    border-bottom: none;
}

/* Hover Effect */
.similar-news-item:hover {
    background: #f3f4f6;
}

/* News Link */
.similar-news-link {
    color: #111827;
    font-weight: 600;
    text-decoration: none;
    display: block;
    line-height: 1.4;
}

/* Hover → Bootstrap Purple */
.similar-news-link:hover {
    color: #6f42c1;
    text-decoration: underline;
}

/* Time */
.similar-news-time {
    margin-top: 6px;
    font-size: 13px;
    color: #6b7280;
}
/* ===== SIMILAR NEWS - DARK MODE ===== */

body.dark .similar-news-card {
    background: linear-gradient(135deg, #020617, #020617);
    border: 1px solid #1e293b;
    color: #ffffff;
}

body.dark .similar-news-header {
    background: transparent;
    color: #ffffff;
    border-bottom: 1px solid #1e293b;
}

body.dark .similar-news-item {
    border-bottom: 1px solid #1e293b;
}

body.dark .similar-news-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

body.dark .similar-news-link {
    color: #ffffff;
}

body.dark .similar-news-time {
    color: #94a3b8;
}

</style>

@endsection
