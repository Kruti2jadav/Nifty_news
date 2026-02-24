<div id="sidebar" class="editor-sidebar">

    <div class="sidebar-title">
        Editor Panel
    </div>

    <a href="{{ route('editor.dashboard') }}">
        <i class="bi bi-house"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{route('articles')}}">
        <i class="bi bi-journal-text"></i>
        <span>My Articles</span>
    </a>

    <a href="{{url('/editor/media')}}">
        <i class="bi bi-images"></i>
        <span>Media Library</span>
    </a>

    <a href="#">
        <i class="bi bi-robot"></i>
        <span>AI Writing Tools</span>
    </a>

    <a href="{{route('comments.index')}}">
        <i class="bi bi-chat-dots"></i>
        <span>Comments</span>
    </a>

    <a href="{{ route('profile.show') }}">
        <i class="bi bi-person"></i>
        <span>Profile</span>
    </a>

    <a href="{{ route('logout') }}" class="text-danger">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
    </a>

</div>