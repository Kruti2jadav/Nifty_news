@extends('components.app')

@section('content')

<div class="container mt-5">
    <h2 class="mb-4">Editors Articles</h2>

    <div class="row">
        @foreach($articles as $article)

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                   @php
    $media = $article->media->first();
@endphp

@if($media)

    @if($media->type === 'image')

        <img 
            src="{{ asset($media->file_url) }}"
            class="card-img-top"
            style="height:200px; object-fit:cover;"
            alt="{{ $article->title }}"
        >

    @elseif($media->type === 'video')

        <video controls class="card-img-top" style="height:200px; object-fit:cover;">
            <source src="{{ asset($media->file_url) }}" type="video/mp4">
        </video>

    @elseif($media->type === 'audio')

       <div class="d-flex justify-content-center align-items-center mt-3" style="height:200px;">
    <audio controls style="width:300px;">
        <source src="{{ asset($media->file_url) }}" type="audio/mpeg">
    </audio>
</div>


    @elseif($media->type === 'document')

        <a href="{{ asset($media->file_url) }}"
           target="_blank"
           class="document-card d-flex align-items-center justify-content-center"
           style="height:200px; border:1px solid #e5e7eb;">

            <div class="text-center">
                <i class="bi bi-file-earmark-text" style="font-size:40px;"></i>
                <div>View Document</div>
            </div>

        </a>

    @endif

@else

    <img 
        src="{{ asset('no-image.png') }}"
        class="card-img-top"
        style="height:200px; object-fit:cover;"
        alt="No Image"
    >

@endif

                    <div class="card-body">

                        <h5 class="card-title">
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none" style="color:#6f42c1;">
                                {{ $article->title }}
                            </a>
                        </h5>

                        <small class="text-muted">
                            {{ $article->author->name ?? 'Editorial Team' }}
                            • 
                            {{ optional($article->published_at)->diffForHumans() }}
                        </small>

                        <p class="mt-2">
                            {{ \Illuminate\Support\Str::limit($article->short_description, 120) }}
                        </p>

                    </div>

                </div>
            </div>

        @endforeach
    </div>

    <div class="mt-4">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection
