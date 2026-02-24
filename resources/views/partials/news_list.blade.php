<div class="news-list mb-6">
    <h2 class="text-xl font-bold mb-2">Latest News</h2>
    @foreach($news as $item)
        <div class="p-4 border-b last:border-b-0">
            <a href="{{ $item['url'] }}" target="_blank" class="font-semibold text-blue-600 hover:underline">
                {{ $item['title'] }}
            </a>
            <p class="text-gray-700 mt-1">{{ $item['subtitle'] }}</p>
            <small class="text-gray-500">
                {{ $item['source'] }} | {{ \Carbon\Carbon::parse($item['published_at'])->diffForHumans() }}
            </small>
        </div>
    @endforeach
</div>
