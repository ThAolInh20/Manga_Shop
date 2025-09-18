@extends('layouts.admin')

@section('title', 'Danh sách nhập hàng')

@section('content')
<div class="container mt-4">
    <h2>Danh sách nhập hàng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('product_suppliers.create') }}" class="btn btn-primary">➕ Thêm nhập hàng</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Sản phẩm</th>
                <th>Nhà cung cấp</th>
                <th>Ngày nhập</th>
                <th>Giá nhập</th>
                <th>Số lượng</th>
                <th>Chi tiết</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($imports as $i)
                <tr>
                    <td>{{ $i->id }}</td>
                    <td>{{ $i->product->name ?? 'N/A' }}</td>
                    <td>{{ $i->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $i->created_at }}</td>
                    <td>{{ number_format($i->import_price, 0, ',', '.') }} đ</td>
                    <td>{{ $i->quantity }}</td>
                    <td>{{ $i->detail }}</td>
                    <td>
                        <a href="{{ route('product_suppliers.edit', $i->id) }}" class="btn btn-sm btn-warning">✏️ Sửa</a>
                        <form action="{{ route('product_suppliers.destroy', $i->id) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa nhập hàng này?')">🗑️ Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Chưa có dữ liệu nhập hàng</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
