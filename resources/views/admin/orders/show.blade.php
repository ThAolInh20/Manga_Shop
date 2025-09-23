@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container">
    <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Thông tin đơn hàng</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Mã đơn</th>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Người đặt</th>
                    <td>{{ $order->account->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Mã giảm giá</th>
                    @if($order->voucher)
                    <td>{{ $order->voucher->code ?? 'Không có' }} (Giảm {{ $order->voucher->sale}}% tối đa {{number_format($order->voucher->max_discount, 0, ',', '.')  }}) </td>
                    @endif
                </tr>
                <tr>
                    <th>Phí ship</th>
                    <td>{{ number_format($order->shipping?$order->shipping->shipping_fee:0, 0, ',', '.') }} đ</td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        @php
                            $statuses = [
                                0 => ['Chờ khách xác nhận', 'secondary'],
                                1 => ['Đang xử lý', 'info'],
                                2 => ['Đang giao', 'primary'],
                                3 => ['Hoàn tất', 'success'],
                                4 => ['Đổi trả', 'warning'],
                                5 => ['Đã hủy', 'danger'],
                                6 => ['Hoàn tiền','warning']
                            ];
                            $status = $statuses[$order->order_status] ?? ['Không xác định', 'dark'];
                        @endphp
                        <span class="badge bg-{{ $status[1] }}">{{ $status[0] }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Thanh toán</th>
                    <td>{{ $order->payment_status == 1 ? 'Đã thanh toán online' : 'Thanh toán khi nhận hàng' }}</td>
                </tr>
                <tr>
                    <th>Ngày đặt</th>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Ngày cập nhật</th>
                    <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ giao</th>
                    <td>{{ $order->shipping->shipping_address ?? 'Chưa có' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <h5>Danh sách sản phẩm</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>id</th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng mua</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->productOrders as $index => $productOrder)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $productOrder->product_id }}</td>
                <td><a href="{{ route('products.edit',$productOrder->product_id) }}">{{ $productOrder->product->name ?? 'Sản phẩm đã xoá' }}</a></td>
                <td>{{ number_format($productOrder->price, 0, ',', '.') }} đ</td>
                <td>{{ $productOrder->quantity }}</td>
                <td>{{ number_format($productOrder->price * $productOrder->quantity, 0, ',', '.') }} đ</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Tổng cộng</th>
                <th>{{ number_format($order->subtotal_price, 0, ',', '.') }} đ</th>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">⬅ Quay lại danh sách</a>
</div>
@endsection
