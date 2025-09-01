@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container">
    <h2>Danh sách đơn hàng</h2>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tài khoản</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Ngày đặt</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->account->name ?? '' }}</td>
                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                <td>{{ $order->order_status }}</td>
                <td>{{ $order->payment_status }}</td>
                <td>{{ $order->order_date }}</td>
                <td>
        
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">Sửa</a>
                 
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
@endsection
