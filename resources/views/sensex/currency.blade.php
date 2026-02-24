@extends('components.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Live Currency / Forex Quotes</h5>

            @if(count($currencies))
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Bid</th>
                            <th>Ask</th>
                            <th>Last Price</th>
                            <th>Change</th>
                            <th>% Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $c)
                            <tr>
                                <td>{{ $c['symbol'] }}</td>
                                <td>{{ $c['bid'] }}</td>
                                <td>{{ $c['ask'] }}</td>
                                <td>{{ $c['lastPrice'] }}</td>
                                <td>{{ $c['change'] }}</td>
                                <td>{{ $c['percentChange'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No currency data available from Upstox</p>
            @endif

        </div>
    </div>
</div>
@endsection