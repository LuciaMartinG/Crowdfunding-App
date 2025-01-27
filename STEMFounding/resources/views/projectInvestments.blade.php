@extends('layout')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($investments->isEmpty())
        <p class="text-muted">You haven't invested in this project yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Investment Date</th>
                    <th>Amount Invested</th>
                    <th>Actions</th> <!-- Columna para las acciones -->
                </tr>
            </thead>
            <tbody>
                @foreach($investments as $investment)
                    <tr>
                        <td>{{ $investment->created_at->format('d-m-Y H:i') }}</td>
                        <td>${{ number_format($investment->investment_amount, 2) }}</td>
                        <td>
                            <!-- Verificar si el tiempo transcurrido desde la inversión es menor a 24 horas -->
                            @php
                                $timeElapsed = now()->diffInHours($investment->created_at);
                            @endphp
                            @if ($timeElapsed <= 24)
                                <form action="{{ route('investments.withdraw', $investment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to withdraw your investment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Withdraw Investment</button>
                                </form>
                            @else
                                <span class="text-muted">Cannot withdraw after 24 hours</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
