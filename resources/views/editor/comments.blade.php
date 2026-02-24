@extends('components.app')

@section('content')
<div class="card shadow-sm p-4">
    <h4 class="fw-bold mb-4">Article Comments</h4>

    <!-- Article selection -->
    <form method="GET" action="{{ route('comments.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <select name="article_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">-- Select Article --</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}" {{ ($selectedArticleId == $article->id) ? 'selected' : '' }}>
                            {{ $article->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <!-- Comments table -->
    @if($selectedArticleId)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Posted At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                    <tr>
                        <td>{{ $comment->user->name ?? 'Unknown' }}</td>
                        <td>{{ $comment->content }}</td>
                        <td>{{ $comment->status ? 'Visible' : 'Hidden' }}</td>
                        <td>{{ $comment->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No comments for this article.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection