@extends('admin.layouts')
@section('title', 'Orders List')
@section('content')
<div class="container">
    <h4>Orders List</h4>
    @foreach($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->customer->name }}</td>
        <td>{{ $order->total_amount }}</td>
        <td>{{ $order->status }}</td>
        <td>
            <a href="">View</a>
            <form action="{{ route('orders.updateStatus', [$order, 'shipped']) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit">Mark Shipped</button>
            </form>
        </td>
    </tr>
    @endforeach
</div>
@endsection

