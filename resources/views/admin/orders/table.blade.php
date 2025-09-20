<table class="table table-bordered" id="orders-table">
    <div class="mb-2">
        Tổng số: {{ $orders->total() }} đơn
    </div>
    <thead>
        <tr>
            <th>
                <a href="#" class="sort-link" data-field="id" data-order="{{ request('sort_field') === 'id' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}">
                    Mã đơn
                </a>
            </th>
            <th>
                <a href="#" class="sort-link" data-field="customer_name" data-order="{{ request('sort_field') === 'customer_name' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}">
                    Tài khoản
                </a>
            </th>
            <th>
                <a href="#" class="sort-link" data-field="total_price" data-order="{{ request('sort_field') === 'total_price' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}">
                    Tổng tiền
                </a>
            </th>
            <th>
                <a href="#" class="sort-link" data-field="order_status" data-order="{{ request('sort_field') === 'order_status' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}">
                    Trạng thái
                </a>
            </th>
            <th>
                <a href="#" class="sort-link" data-field="created_at" data-order="{{ request('sort_field') === 'created_at' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}">
                    Ngày đặt
                </a>
            </th>
            <th>
                Người cập nhật
            </th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
         @forelse($orders as $order)
        <tr data-id="{{ $order->id }}" data-status="{{ $order->order_status }}">
            <td>{{ $order->id }}</td>
            <td><a href="{{ route('accounts.show', $order->account ??0 ) }}">{{  $order->account->name ?? '' }}</a></td>
            <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
            <td class="status-cell"></td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('accounts.show',$order->updatedBy??0) }}">{{ $order->updatedBy->name??'' }}</a>
            </td>
            <td class="action-cell"></td>
        </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Không có đơn hàng phù hợp</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div id="pagination-links">
    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
