@extends('components.app')

@section('content')

<h3 class="mb-4">Editor Dashboard</h3>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6>My Articles</h6>
            <h3>{{ $totalArticles }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6>Published</h6>
            <h3>{{ $published }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6>Drafts</h6>
            <h3>{{ $drafts }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6>Views</h6>
            <h3>{{ number_format($totalViews) }}</h3>
        </div>
    </div>

</div>

<hr class="my-5">

<h5>Recent Activity</h5>

<ul class="list-group shadow-sm">
    <li class="list-group-item">
        🕒 {{ $pendingReview }} Articles Awaiting Review
    </li>
</ul>

@endsection