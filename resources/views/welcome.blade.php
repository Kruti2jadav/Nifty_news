@extends('components.app')

@section('content')

 
<div class="main-content">
<!-- ================= MAIN CONTAINER ================= -->
<div class="container my-4">

    <div class="row g-4">

        <!-- ================= LEFT COLUMN ================= -->
        <div class="col-lg-6">

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <h4 class="fw-bold mb-3 border-bottom pb-2">
                        NIFTY 50
                    </h4>

                    <h2 class="mb-3">
                        ₹{{ number_format($niftyPrice,2) }}
                        <span class="fs-5 {{ $niftyChange >= 0 ? 'text-success' : 'text-danger' }}">
                            ({{ number_format($niftyChange,2) }}%)
                        </span>
                    </h2>

                    <div style="height:400px;">
                        <canvas id="chart"></canvas>
                    </div>

                </div>
            </div>

        </div>


        <!-- ================= MIDDLE COLUMN ================= --> 
<div class="col-lg-3">

    <!-- Gainers -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h6 class="fw-bold text-success border-bottom pb-2 mb-3">
                Top Gainers
            </h6>

            @forelse($gainers as $stock)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="small">
                        {{ $stock['shortName'] ?? $stock['symbol'] }}
                    </span>
                    <span class="small text-success">
                        {{ number_format($stock['regularMarketChangePercent'] ?? 0,2) }}%
                    </span>
                </div>
            @empty
                <p class="text-muted small mb-0">No data available</p>
            @endforelse

        </div>
    </div>

    <!-- Losers -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">
                Top Losers
            </h6>

            @forelse($losers as $stock)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="small">
                        {{ $stock['shortName'] ?? $stock['symbol'] }}
                    </span>
                    <span class="small text-danger">
                        {{ number_format($stock['regularMarketChangePercent'] ?? 0,2) }}%
                    </span>
                </div>
            @empty
                <p class="text-muted small mb-0">No data available</p>
            @endforelse

        </div>
    </div>

</div>

        <!-- ================= RIGHT COLUMN ================= -->
       <!-- ================= RIGHT COLUMN ================= -->
<div class="col-lg-3">

    <!-- Market Snapshot -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h6 class="fw-bold border-bottom pb-2 mb-3">
                Market Snapshot
            </h6>

            <table class="table table-sm mb-0 dark-table">
                <tr>
                    <td>Sensex</td>
                    <td>{{ number_format($sensexPrice,2) }}</td>
                    <td class="{{ $sensexChange >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($sensexChange,2) }}%
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <!-- Most Active -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h6 class="fw-bold border-bottom pb-2 mb-3">
                Most Active
            </h6>

            @forelse($mostActive as $stock)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <span class="small fw-medium">
                        {{ $stock['shortName'] ?? $stock['symbol'] }}
                    </span>
                    <span class="small">
                        ₹{{ number_format($stock['regularMarketPrice'] ?? 0,2) }}
                    </span>
                </div>
            @empty
                <p class="text-muted small mb-0">No data available</p>
            @endforelse

        </div>

    </div>

</div>


            <!-- ================= INDICES ROW ================= -->


        </div>

    </div>
<div class="container my-4">

    <div class="row g-4 text-center">

        @foreach($marketData as $name => $data)

            <div class="col-lg-3 col-md-6">

                <div class="card shadow-sm h-100">
                    <div class="card-body">

                        <h6 class="fw-bold mb-2">
                            {{ $name }}
                        </h6>

                        <h5 class="mb-1">
                            {{ number_format($data['price'],2) }}
                        </h5>

                        <span class="small {{ $data['changePercent'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($data['changePercent'],2) }}%
                        </span>

                    </div>
                </div>

            </div>

        @endforeach

    </div>

</div>
<div class="container my-5">

    <div class="card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Stocks Dashboard</h5>
            <small class="text-muted">
                As on {{ now()->format('M d, Y h:i A') }}
            </small>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills mb-4" id="stockTabs">
            <li class="nav-item">
                <button class="nav-link active" data-tab="active">Most Active - Value</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-tab="gainers">Top Gainers</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-tab="losers">Top Losers</button>
            </li> 
        </ul>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Chg</th>
                        <th>%Chg</th>
                        <th>Value (Cr)</th>
                    </tr>
                </thead>

                <tbody id="stockTableBody">
                </tbody>

            </table>

        </div>

        <small class="text-muted">
            * Investments in securities are subject to market risks.
        </small>

    </div>
</div>
</div>

<!-- ================= CHART ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            data: @json($prices),
            borderColor: '#198754',
            backgroundColor: 'rgba(25,135,84,0.1)',
            fill: true,
            tension: 0.3,
            pointRadius: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { display: false } }
    }
});
</script>
<script>
const stockData = {
    active: @json($mostActiveValue),
    gainers: @json($topGainers),
    losers: @json($topLosers)
};

function formatCrores(value) {
    return (value / 10000000).toFixed(2);
}

function renderTable(type) {

    const table = document.getElementById('stockTableBody');
    table.innerHTML = '';

    stockData[type].forEach(stock => {

        const change = parseFloat(stock.regularMarketChange ?? 0);
        const percent = parseFloat(stock.regularMarketChangePercent ?? 0);
        const tradedValue = parseFloat(stock.tradedValue ?? 0);

        const changeClass = percent >= 0 ? 'text-success' : 'text-danger';

        table.innerHTML += `
            <tr>
                <td>${stock.shortName ?? stock.symbol}</td>
                <td>₹${(stock.regularMarketPrice ?? 0).toFixed(2)}</td>
                <td class="${changeClass}">
                    ${change.toFixed(2)}
                </td>
                <td class="${changeClass}">
                    ${percent.toFixed(2)}%
                </td>
                <td>
                    ${formatCrores(tradedValue)}
                </td>
            </tr>
        `;
    });
}

renderTable('active');

document.querySelectorAll('#stockTabs .nav-link').forEach(btn => {
    btn.addEventListener('click', function () {

        document.querySelectorAll('#stockTabs .nav-link')
            .forEach(el => el.classList.remove('active'));

        this.classList.add('active');

        renderTable(this.dataset.tab);
    });
});
</script>

</div>
@endsection
