<div class="commodities mb-6">
    <h2 class="text-xl font-bold mb-2">Commodities</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($commodityData as $symbol => $data)
            <div class="p-4 border rounded shadow-sm">
                <h3 class="font-semibold">{{ $symbol }}</h3>
                <p>Price: <span class="font-bold">{{ $data['price'] ?? 'N/A' }}</span></p>
                <p>Change: 
                    <span class="{{ $data['changePts'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $data['changePts'] ?? 0 }} ({{ $data['changePercent'] ?? 0 }}%)
                    </span>
                </p>
            </div>
        @endforeach
    </div>
</div>
