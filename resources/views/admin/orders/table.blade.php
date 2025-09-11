<table class="table table-bordered" id="orders-table">
    <thead>
        <tr>
            <th><a href="?sort_field=id&sort_order=asc">ID</a></th>
            <th><a href="?sort_field=customer_name&sort_order=asc">Tài khoản</a></th>
            <th><a href="?sort_field=total_price&sort_order=asc">Tổng tiền</a></th>
            <th><a href="?sort_field=order_status&sort_order=asc">Trạng thái</a></th>
            <th><a href="?sort_field=created_at&sort_order=asc">Ngày đặt</a></th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr data-id="{{ $order->id }}" data-status="{{ $order->order_status }}">
            <td>{{ $order->id }}</td>
            <td>{{ $order->account->name ?? '' }}</td>
            <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
            <td class="status-cell"></td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td class="action-cell"></td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $orders->links() }}
