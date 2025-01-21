@extends('layout')

@section('title', 'Investors for Project')

@section('content')

<div class="container mt-4">
    <h3>Investors for Project: {{ $project->title }}</h3>
    
    @if($investorsWithAmount->isEmpty())
        <p class="text-muted">No investors have invested in this project yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th>Amount Invested</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investorsWithAmount as $investment)
                    <tr>
                        <td>{{ $investment['user'] }}</td>
                        <td>${{ number_format($investment['investment_amount'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
