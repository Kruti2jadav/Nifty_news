@extends('components.app')

@section('content')
<div class="container my-4">

    <!-- NIFTY & SENSEX SIDE BY SIDE -->
    <div class="row g-4 mb-4">

        <!-- NIFTY -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">NIFTY 50</h5>
                    @if($nifty)
                        <h3>
                            ₹{{ number_format($nifty['price'],2) }}
                            <span class="{{ $nifty['percent'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ({{ $nifty['percent'] }}%)
                            </span>
                        </h3>
                        <div style="height:250px;">
                            <canvas id="niftyChart"></canvas>
                        </div>
                    @else
                        <p>No Data</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- SENSEX -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">SENSEX</h5>
                    @if($sensex)
                        <h3>
                            ₹{{ number_format($sensex['price'],2) }}
                            <span class="{{ $sensex['percent'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ({{ $sensex['percent'] }}%)
                            </span>
                        </h3>
                        <div style="height:250px;">
                            <canvas id="sensexChart"></canvas>
                        </div>
                    @else
                        <p>No Data</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- TOP SCREENER -->
    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold text-success">Top Gainers</h6>
                    @foreach($gainers as $s)
                        <div class="d-flex justify-content-between py-1">
                            <span>{{ $s['symbol'] }}</span>
                            <span class="text-success">
                                {{ number_format($s['regularMarketChangePercent'] ?? 0,2) }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold text-danger">Top Losers</h6>
                    @foreach($losers as $s)
                        <div class="d-flex justify-content-between py-1">
                            <span>{{ $s['symbol'] }}</span>
                            <span class="text-danger">
                                {{ number_format($s['regularMarketChangePercent'] ?? 0,2) }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Most Active</h6>
                    @foreach($mostActive as $s)
                        <div class="d-flex justify-content-between py-1">
                            <span>{{ $s['symbol'] }}</span>
                            <span>₹{{ number_format($s['regularMarketPrice'] ?? 0,2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

  <div class="container my-4">

    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="fw-bold mb-4">Latest Market News</h5>

                    @forelse($marketNews as $news)
                        <div class="mb-3 border-bottom pb-3">
                            <a href="{{ route('news.show', urlencode($news['title'])) }}"  
                               class="fw-semibold text-decoration-none text-dark d-block">
                                {{ $news['title'] }}
                            </a>

                            <div class="small text-muted mt-1">
                                {{ \Carbon\Carbon::parse($news['publishedAt'])->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No Market News Available</p>
                    @endforelse

                </div>
            </div>

        </div>
    </div>

</div>



</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@if($nifty)
new Chart(document.getElementById('niftyChart'), {
    type: 'line',
    data: {
        labels: @json(array_map(fn($ts)=>date('H:i',$ts), $nifty['timestamps'])),
        datasets: [{
            data: @json($nifty['closes']),
            borderColor: '#22c55e',
            fill: false,
            tension: 0.3,
            pointRadius: 0
        }]
    },
    options: { plugins:{legend:{display:false}}, scales:{x:{display:false}} }
});
@endif

@if($sensex)
new Chart(document.getElementById('sensexChart'), {
    type: 'line',
    data: {
        labels: @json(array_map(fn($ts)=>date('H:i',$ts), $sensex['timestamps'])),
        datasets: [{
            data: @json($sensex['closes']),
            borderColor: '#ef4444',
            fill: false,
            tension: 0.3,
            pointRadius: 0
        }]
    },
    options: { plugins:{legend:{display:false}}, scales:{x:{display:false}} }
});
@endif
</script>

@endsection
