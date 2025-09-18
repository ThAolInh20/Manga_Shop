<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên sản phẩm</th>
            <th>Người nhập</th>
            <th>Giá nhập</th>
            <th>Số lượng</th>
            <th>Ngày nhập</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $index => $ps)
            <tr>
                <td>{{ $products->firstItem() + $index }}</td>
                <td><a href="{{ route('products.edit',$ps->product->id) }}">{{ $ps->product->name ?? 'Sản phẩm đã xóa' }}</a></td>
                <td>{{ $ps->importBy->name ?? 'N/A' }}</td>
                <td>{{ number_format($ps->import_price) }}</td>
                <td>{{ $ps->quantity }}</td>
                <td>{{ optional($ps->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Không có sản phẩm nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-2">
    {{ $products->links() }}
</div>
