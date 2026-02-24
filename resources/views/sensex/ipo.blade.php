@extends('components.app')

@section('content')
<div class="container my-4">

    {{-- Upcoming IPOs --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Upcoming IPOs</h5>
            @if(count($ipos['upcoming']))
                <ul class="list-group list-group-flush">
                    @foreach($ipos['upcoming'] as $ipo)
                        <li class="list-group-item">
                            <strong>{{ $ipo['company'] ?? 'N/A' }}</strong> ({{ $ipo['exchange'] ?? '' }})<br>
                            Open: {{ $ipo['openDate'] ?? 'N/A' }} | Close: {{ $ipo['closeDate'] ?? 'N/A' }}<br>
                            Price: {{ $ipo['priceBand'] ?? 'N/A' }} | Lot Size: {{ $ipo['lotSize'] ?? 'N/A' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No upcoming IPOs</p>
            @endif
        </div>
    </div>

    {{-- Live / Open IPOs --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Live / Open IPOs</h5>
            @if(count($ipos['live']))
                <ul class="list-group list-group-flush">
                    @foreach($ipos['live'] as $ipo)
                        <li class="list-group-item">
                            <strong>{{ $ipo['company'] ?? 'N/A' }}</strong> ({{ $ipo['exchange'] ?? '' }})<br>
                            Open: {{ $ipo['openDate'] ?? 'N/A' }} | Close: {{ $ipo['closeDate'] ?? 'N/A' }}<br>
                            Price: {{ $ipo['priceBand'] ?? 'N/A' }} | Lot Size: {{ $ipo['lotSize'] ?? 'N/A' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No live IPOs</p>
            @endif
        </div>
    </div>

    {{-- Recent IPOs --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Recent IPOs</h5>
            @if(count($ipos['recent']))
                <ul class="list-group list-group-flush">
                    @foreach($ipos['recent'] as $ipo)
                        <li class="list-group-item">
                            <strong>{{ $ipo['company'] ?? 'N/A' }}</strong> ({{ $ipo['exchange'] ?? '' }})<br>
                            Open: {{ $ipo['openDate'] ?? 'N/A' }} | Close: {{ $ipo['closeDate'] ?? 'N/A' }}<br>
                            Price: {{ $ipo['priceBand'] ?? 'N/A' }} | Lot Size: {{ $ipo['lotSize'] ?? 'N/A' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No recent IPOs</p>
            @endif
        </div>
    </div>

    {{-- IPO News --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">IPO News</h5>
            @if(count($news))
                <ul class="list-group list-group-flush">
                    @foreach($news as $article)
                        <li class="list-group-item">
                            <a href="{{ $article['url'] }}" target="_blank" class="text-dark text-decoration-none">{{ $article['title'] }}</a>
                            <br>
                            <small>{{ $article['source'] }} | {{ date('d M Y', strtotime($article['publishedAt'])) }}</small>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No IPO news available</p>
            @endif
        </div>
    </div>

</div>
@endsection
