@extends('components.app')

@section('content')

<div class="card shadow-sm p-4">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">My Articles</h4>

        <button class="btn btn-primary px-4"
                data-bs-toggle="modal"
                data-bs-target="#articleModal"
                onclick="openCreateModal()">
            + New Article
        </button>
    </div>

    <table class="table align-middle">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th width="180">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($articles as $article)
            <tr>
                <td>{{ $article->title }}</td>
                <td>
                    <span class="badge
                        {{ $article->status == 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst($article->status) }}
                    </span>
                </td>
                <td>
                    <button 
    class="btn btn-sm btn-primary editBtn"
    data-id="{{ $article->id }}"
    data-title="{{ $article->title }}"
    data-category="{{ $article->category_id }}"
    data-subcategory="{{ $article->subcategory_id }}"
    data-short="{{ $article->short_description }}"
    data-content="{{ $article->full_content }}"
    data-status="{{ $article->status }}"
    data-meta_title="{{ $article->meta_title }}"
    data-meta_description="{{ $article->meta_description }}"
   data-media="{{ json_encode($article->media->map(function($m){
        return [
            'type' => $m->type,
            'file_url' => asset($m->file_url),
            'name' => $m->file_name ?? null,
            'size' => $m->size
        ];
    })) }}"
>
Edit
</button>

                    <form action="{{ route('articles.delete', $article->id) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this article?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">
                    No Articles Found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>


<!-- Modal -->
<div class="modal fade" id="articleModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="articleForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Category</label>
                            <select name="category_id" id="categorySelect" class="form-control" required>
                                <option value="">Select</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Subcategory</label>
                            <select name="subcategory_id" id="subcategorySelect" class="form-control">
                                <option value="">Select</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Short Description</label>
                            <textarea name="short_description" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Full Content</label>
                            <textarea name="full_content" id="fullContent" class="form-control"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="draft">Draft</option> 
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Upload Media</label>
                            <input type="file" name="media[]" multiple class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Existing Media</label> 
                              <div id="mediaContainer" class="d-flex flex-wrap gap-2"></div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save Article</button>
                </div>
            </form>

        </div>
    </div>
</div>
</div>

@endsection


<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = "New Article";
      document.getElementById('articleForm').action = 
        "{{ route('articles.store') }}";
    document.getElementById('articleTitle').value = '';
    document.getElementById('articleStatus').value = 'draft';
}

function openEditModal(id, title, status) {
    document.getElementById('modalTitle').innerText = "Update Article";
    document.getElementById('articleForm').action = "editor/articles/update/" + id;
    document.getElementById('articleTitle').value = title;
    document.getElementById('articleStatus').value = status;

    new bootstrap.Modal(document.getElementById('articleModal')).show();
}
</script> 
<script>
document.addEventListener('DOMContentLoaded', function () {

    let category = document.getElementById('categorySelect');

    category.addEventListener('change', function () {

        fetch(`/editor/subcategories/${this.value}`)
            .then(response => response.json())
            .then(data => {

                let sub = document.getElementById('subcategorySelect');
                sub.innerHTML = '<option value="">Select Subcategory</option>';

                data.forEach(item => {
                    sub.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });

                // If editing, select correct subcategory
                if (category.dataset.selectedSubcategory) {
                    sub.value = category.dataset.selectedSubcategory;
                    category.dataset.selectedSubcategory = '';
                }

            })
            .catch(error => console.error(error));
    });

});
</script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea[name=full_content]',
  height: 400,
  plugins: 'lists link image table code',
  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
});
</script> <script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('articleModal');
    const modal = new bootstrap.Modal(modalElement);

    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {
            let articleId = this.dataset.id;
            document.getElementById('articleForm').action = "/editor/articles/update/" + articleId;

            document.querySelector('.modal-title').innerText = "Update Article";
            document.querySelector('input[name=title]').value = this.dataset.title;
            document.querySelector('textarea[name=short_description]').value = this.dataset.short;
            document.querySelector('select[name=status]').value = this.dataset.status;

            // Category
            const categorySelect = document.querySelector('select[name=category_id]');
            categorySelect.value = this.dataset.category;
            categorySelect.dataset.selectedSubcategory = this.dataset.subcategory;
            categorySelect.dispatchEvent(new Event('change'));

            // Full content
            document.querySelector('textarea[name=full_content]').value = this.dataset.content; 

            // Existing media
           const mediaContainer = document.getElementById('mediaContainer');
const mediaFiles = JSON.parse(this.dataset.media || '[]');
mediaContainer.innerHTML = '';

if (mediaFiles.length === 0) {
    mediaContainer.innerHTML = 'No existing media';
} else {
    mediaFiles.forEach(media => {
        let html = '';
        let fileUrl = media.file_url;

        switch(media.type) {
            case 'image':
                html = `<img src="${fileUrl}" width="100" alt="Image">`;
                break;
            case 'video':
                html = `<video width="150" controls>
                            <source src="${fileUrl}" type="video/${fileUrl.split('.').pop()}">
                        </video>`;
                break;
            case 'audio':
                html = `<audio controls>
                            <source src="${fileUrl}" type="audio/${fileUrl.split('.').pop()}">
                        </audio>`;
                break;
            case 'document':
                html = `<a href="${fileUrl}" target="_blank">${media.name || fileUrl.split('/').pop()}</a>`;
                break;
        }

        if(media.size) {
            let sizeKB = (media.size / 1024).toFixed(2);
            html += ` <small>(${sizeKB} KB)</small>`;
        }

        mediaContainer.innerHTML += html + ' ';
    });
}

            modal.show();
        });
    });
});
</script>
<script>
function deleteMedia(id) {

    if (!confirm('Delete this media?')) return;

    fetch('/media/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => location.reload());
}
</script>