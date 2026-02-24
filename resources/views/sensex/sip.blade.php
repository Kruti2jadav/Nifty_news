@extends('components.app')

@section('content')
<div class="container my-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Available SIPs / Mutual Funds</h5>

            @if(count($sips))
                <ul class="list-group list-group-flush">
                    @foreach($sips as $sip)
                        <li class="list-group-item">
                            <strong>{{ $sip['name'] }}</strong> ({{ $sip['amc'] }})<br>
                            NAV: {{ $sip['nav'] }} | Min SIP: ₹{{ $sip['minSIPAmount'] }}<br>
                            Frequency: {{ $sip['frequency'] }}<br>
                            @if(!empty($sip['url']))
                                <a href="{{ $sip['url'] }}" target="_blank">More Info</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No SIPs available from RapidAPI</p>
            @endif

        </div>
    </div>

</div>
@endsection