@extends('components.app')

@section('content')
<div class="card shadow-sm p-4">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Media Library</h4>
    </div>

   <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Article</th>
            <th>Type</th>
            <th>File</th>
            <th>Size</th>
            <th>Preview</th>
            <th>Actions</th> <!-- New column -->
        </tr>
    </thead>
    <tbody>
        @foreach($mediaItems as $media)
            <tr>
                <td>{{ $media->article->title ?? 'Deleted Article' }}</td>
                <td class="text-capitalize">{{ $media->type }}</td>
                <td>{{ $media->file_url }}</td>
                <td>{{ number_format($media->size / 1024, 2) }} KB</td>
                <td>
                    @if($media->type == 'image')
                        <img src="{{ asset($media->file_url) }}" alt="media" style="height:50px;">
                    @elseif($media->type == 'video')
                        <video width="100" height="50" controls>
                            <source src="{{ asset($media->file_url) }}" type="video/mp4">
                        </video>
                    @elseif($media->type == 'audio')
                        <audio controls>
                            <source src="{{ asset($media->file_url) }}" type="audio/mpeg">
                        </audio>
                    @else
                        <a href="{{ asset($media->file_url) }}" target="_blank">Download</a>
                    @endif
                </td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editMediaModal{{ $media->id }}">
                        Edit
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('media.destroy', $media->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this media?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editMediaModal{{ $media->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('media.update', $media->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Media</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Replace File</label>
                                    <input type="file" name="media_file" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </tbody>
</table>
</div>
@endsection