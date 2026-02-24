@extends('components.app')

@section('content')

<style>
.company-header {
    background: #0f172a;
    padding: 25px;
    border-radius: 14px;
    color: #fff;
    margin-bottom: 25px;
}

.search-bar {
    max-width: 400px;
}

.price {
    font-size: 28px;
    font-weight: 600;
}

.negative { color: #dc3545; }
.positive { color: #16a34a; }

.info-box {
    background: #111827;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    color: #cbd5e1;
}

.tab-btn {
    margin-right: 10px;
    padding: 6px 14px;
    border-radius: 6px;
    background: #1e293b;
    color: #fff;
    border: none;
}

.tab-btn.active {
    background: #6f42c1;
}
</style>

<div class="container mt-4">

    {{-- 🔍 SEARCH BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Company Dashboard</h3>

        <div class="search-bar">
            <input type="text" id="searchSymbol" class="form-control" placeholder="Search Company (e.g. INFY)">
        </div>
    </div>

    {{-- 📊 COMPANY HEADER --}}
    <div class="company-header">
        <h4 id="companyName">Search a company</h4>
        <div class="price" id="companyPrice"></div>
        <div id="companyChange"></div>
    </div>

    {{-- 📑 TABS --}}
    <div class="mb-3">
        <button class="tab-btn active">Overview</button>
        <button class="tab-btn">Financials</button>
        <button class="tab-btn">News</button>
        <button class="tab-btn">Technical</button>
    </div>

    {{-- 📈 CHART --}}
    <div class="info-box">
        <canvas id="stockChart" height="100"></canvas>
    </div>

    {{-- 📊 FUNDAMENTALS --}}
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <strong>Market Cap</strong>
                <div id="marketCap">-</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>P/E Ratio</strong>
                <div id="peRatio">-</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>EPS</strong>
                <div id="eps">-</div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let chart;

$('#searchSymbol').on('keypress', function(e) {

    if(e.which == 13){

        let symbol = $(this).val();

        $.get('/company/search', { symbol: symbol }, function(data){

            $('#companyName').text(data.name);
            $('#companyPrice').text("₹ " + data.price);

            let changeClass = data.percent < 0 ? 'negative' : 'positive';

            $('#companyChange')
                .removeClass()
                .addClass(changeClass)
                .text(data.change + " (" + data.percent + "%)");

            $('#marketCap').text(data.market_cap);
            $('#peRatio').text(data.pe_ratio);
            $('#eps').text(data.eps);

            renderChart(data.percent);
        });

    }
});

function renderChart(change){

    let ctx = document.getElementById('stockChart').getContext('2d');

    if(chart) chart.destroy();

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                data: [100,110,105,120,115, 115 + change],
                borderColor: '#6f42c1',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            plugins: { legend: { display:false } }
        }
    });
}
</script>

@endsection