@extends('components.app')

@section('content')

<style>
    .news-title {
        color: #6f42c1;
        font-weight: 600;
    }

    .analyse-btn {
        background: #6f42c1;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        transition: 0.3s;
    }

    .analyse-btn:hover {
        background: #59359c;
    }

    .result-box {
        display: none;
        background: #f8f5ff;
        border-left: 4px solid #6f42c1;
        padding: 12px;
        border-radius: 6px;
        margin-top: 12px;
    }
    body.dark .result-box {
     background-color: #0f172a;
    color: #e0e0e0;
    border-color: #8a63d2 !important;
}
.news-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
    transition: 0.3s ease;
}

.news-card:hover .news-image {
    transform: scale(1.05);
} 
/* Pagination */
.pagination{
    justify-content:center;
    margin-top:25px;
}
.pagination .page-link {
    color:#6f42c1 !important;
    border-color:#e5e7eb;
}

.pagination .active .page-link {
    background:#6f42c1 !important;
    border-color:#6f42c1 !important;
    color:#fff !important;
}

.pagination .page-link:hover {
    background:#f3f0ff;
    color:#6f42c1 !important;
}
</style>

<div class="container mt-4">
    <h2 class="mb-4 news-title">Stock News</h2>

    @foreach($news as $item)
    <div class="card mb-3 shadow-sm p-3 news-card">
        <div class="row">
           <div class="col-md-3">
    @if($item['urlToImage'])
        <img src="{{ $item['urlToImage'] }}" class="news-image rounded">
    @else
        <img src="https://via.placeholder.com/300x200?text=No+Image" class="news-image rounded">
    @endif
</div>

            <div class="col-md-9">
                <h5 class="news-title">{{ $item['title'] }}</h5>
                <small class="text-muted">
                    {{ $item['source']['name'] }} • 
                    {{ \Carbon\Carbon::parse($item['publishedAt'])->format('d M Y H:i') }}
                </small>

                <br><br>

                <button 
                    class="analyse-btn"
                    data-symbol="{{ $item['instrument_key'] ?? '' }}"
                    data-title="{{ $item['title'] }}"
                    data-date="{{ $item['publishedAt'] }}">
                    Analyse Impact
                </button>

                <div class="result-box"></div>

                <canvas class="mini-chart mt-3" height="80" style="display:none;"></canvas>
            </div>
        </div>
    </div>
    
    @endforeach
  <div class="mt-4 text-center">
                {{ $news->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
</div>

{{-- ================= SCRIPTS ================= --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function(){

    // Toggle + API call
    $('.analyse-btn').click(function(e){

        e.stopPropagation();

        let btn = $(this);
        let card = btn.closest('.news-card');
        let resultBox = card.find('.result-box');
        let chartCanvas = card.find('.mini-chart');

        // If already open → close it
        if(resultBox.is(':visible')){
            resultBox.slideUp();
            chartCanvas.hide();
            return;
        }

        // Close all others
        $('.result-box').slideUp();
        $('.mini-chart').hide();

        resultBox.html("Analysing... ⏳").slideDown();

        $.post('/analyse-news', {
            _token: '{{ csrf_token() }}',
            symbol: btn.data('symbol'),
            title: btn.data('title'),
            publishedAt: btn.data('date')
        }, function(data){

            resultBox.html(`
                <p><strong>Volume Spike:</strong> ${data.volume_spike ? 'YES 🚀' : 'NO'}</p>
                <p><strong>Price Change:</strong> ${data.price_change_percent}%</p>
                <p><strong>Future Outlook:</strong> ${data.future_outlook}</p>
            `);

            chartCanvas.show();

            let ctx = chartCanvas[0].getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Before News','After News'],
                    datasets: [{
                        data: [0, data.price_change_percent],
                        borderColor: '#6f42c1',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

        }).fail(function(){
            resultBox.html("Error analysing news ❌");
        });

    });

    // Click outside → close result
    $(document).click(function(){
        $('.result-box').slideUp();
        $('.mini-chart').hide();
    });

});
</script>

@endsection