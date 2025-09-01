@extends('admin.layout')

@section('title', 'Cập nhật đơn hàng')

@section('content')
<div class="container mt-4">
    <h2>Cập nhật đơn hàng #{{ $order->id }}</h2>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Thông tin khách hàng -->
        <div class="mb-3">
            <label class="form-label">Khách hàng</label>
            <input type="text" class="form-control" value="{{ $order->account->name }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ giao hàng</label>
            <textarea class="form-control" rows="2" readonly>{{ $order->shipping_address }}</textarea>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Ngày đặt</label>
                <input type="text" class="form-control" value="{{ $order->order_date }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Ngày giao</label>
                <input type="text" class="form-control" value="{{ $order->deliver_date }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Phí ship</label>
                <input type="text" class="form-control" value="{{ number_format($order->shipping_fee, 0, ',', '.') }} đ" readonly>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <h5 class="mt-4">Sản phẩm trong đơn</h5>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ number_format($product->pivot->price, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tổng tiền -->
        <div class="mb-3">
            <label class="form-label">Tổng tiền</label>
            <input type="text" class="form-control" value="{{ number_format($order->total_price, 0, ',', '.') }} đ" readonly>
        </div>

        <!-- Trạng thái (chỉ cái này sửa được) -->
        <div class="mb-3">
            <label class="form-label">Trạng thái đơn hàng</label>
            <select name="order_status" class="form-select" required>
                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <!-- Nút -->
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
