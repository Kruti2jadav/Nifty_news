<div class="gainers-losers mb-6">
    <h2 class="text-xl font-bold mb-2">Top Gainers & Losers</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Top Gainers --}}
        <div class="p-4 border rounded">
            <h3 class="font-semibold mb-2">Top Gainers</h3>
            <ul>
                @foreach($topGainers as $stock)
                    <li class="mb-1">
                        <strong>{{ $stock['symbol'] ?? '' }}</strong> - 
                        {{ $stock['regularMarketPrice'] ?? 0 }}
                        <span class="{{ $stock['regularMarketChange'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ({{ $stock['regularMarketChange'] ?? 0 }})
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Top Losers --}}
        <div class="p-4 border rounded">
            <h3 class="font-semibold mb-2">Top Losers</h3>
            <ul>
                @foreach($topLosers as $stock)
                    <li class="mb-1">
                        <strong>{{ $stock['symbol'] ?? '' }}</strong> - 
                        {{ $stock['regularMarketPrice'] ?? 0 }}
                        <span class="{{ $stock['regularMarketChange'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ({{ $stock['regularMarketChange'] ?? 0 }})
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
