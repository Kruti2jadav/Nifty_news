@extends('components.app')

@section('content')

<div class="container mt-5">
    <div class="row">

        {{-- LEFT CONTENT --}}
      {{-- LEFT CONTENT --}}
<div class="col-lg-8">

    <div class="card shadow-sm p-4">

        {{-- Category --}}
        <div class="mb-2">
            <span class="badge bg-secondary">
                {{ $article->category->name ?? 'General' }}
            </span>

            @if($article->subcategory)
                <span class="badge bg-light text-dark border">
                    {{ $article->subcategory->name }}
                </span>
            @endif
        </div>

        {{-- Title --}}
        <h2 class="fw-bold mb-3">
            {{ $article->title }}
        </h2>

        {{-- Meta Info --}}
        <p class="text-muted small mb-4">
            By <strong>{{ $article->author->name ?? 'Editorial Team' }}</strong>
            • {{ optional($article->published_at)->format('F d, Y') }}
            • {{ $article->views }} Views
        </p>

        {{-- Media Container --}}
        @php
            $media = $article->media->first();
        @endphp

        @if($media)
            <div class="mb-4 text-center">

                @if($media->type === 'image')
                    <img src="{{ asset($media->file_url) }}"
                         class="img-fluid rounded"
                         style="max-height:450px; object-fit:cover;"
                         alt="{{ $article->title }}">

                @elseif($media->type === 'video')
                    <video controls class="w-100 rounded">
                        <source src="{{ asset($media->file_url) }}" type="video/mp4">
                    </video>

                @elseif($media->type === 'audio')
                    <audio controls style="width:350px;">
                        <source src="{{ asset($media->file_url) }}" type="audio/mpeg">
                    </audio>

                @elseif($media->type === 'document')
                    <a href="{{ asset($media->file_url) }}"
                       target="_blank"
                       class="btn btn-outline-secondary">
                        View Document
                    </a>
                @endif

            </div>
        @endif

        {{-- Short Description --}}
        @if($article->short_description)
           <div class="short-desc-box p-3 mb-4 rounded border-start border-4">
                <p class="mb-0 fw-semibold">
                    {{ $article->short_description }}
                </p>
            </div>
        @endif

        {{-- Full Content --}}
        <div class="article-content" style="line-height:1.9; font-size:17px;">
            {!! $article->full_content !!}
        </div>

    </div>

</div>

        {{-- RIGHT SIDEBAR --}}
       <div class="col-lg-4">
    <div class="card shadow-sm p-3">

        <h5 class="mb-3" style="color:#6f42c1;">
            More From This Category
        </h5>

        @foreach($relatedArticles as $rel)

            <div class="mb-3 pb-3 border-bottom">

                <a href="{{ route('articles.show', $rel->slug) }}"
                   class="fw-bold text-decoration-none" style="color:#6f42c1;">
                    {{ $rel->title }}
                </a>

                <p class="small text-muted mb-1">
                    {{ Str::limit($rel->short_description, 80) }}
                </p>

                <small class="text-muted">
                    {{ $rel->author->name ?? 'Editorial Team' }}
                    • 
                    {{ optional($rel->published_at)->format('M d, Y') }}
                </small>

            </div>

        @endforeach

    </div>
</div>


    </div>
</div>

@endsection
