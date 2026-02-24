<div class="indices mb-6">
    <h2 class="text-xl font-bold mb-2">Market Indices</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($marketData as $name => $data)
            <div class="p-4 border rounded shadow-sm">
                <h3 class="font-semibold">{{ $name }}</h3>
                <p>Price: <span class="font-bold">{{ $data['price'] }}</span></p>
                <p>Change: 
                    <span class="{{ $data['changePts'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $data['changePts'] }} ({{ $data['changePercent'] }}%)
                    </span>
                </p>
            </div>
        @endforeach
    </div>
</div>
