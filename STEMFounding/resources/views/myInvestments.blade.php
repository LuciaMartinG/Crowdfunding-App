@extends('layout')

@section('title', 'My Investments')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">My investmens</h1>

    @if($investments->isEmpty())
        <p class="text-muted">You haven't invested in any projects yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Amount Invested</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $investment)
                    <tr>
                        <td>{{ $investment->project->title }}</td>
                        <td>${{ number_format($investment->investment_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
