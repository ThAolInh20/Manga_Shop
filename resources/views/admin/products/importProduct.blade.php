@extends('layouts.admin')

@section('title', 'Nhập hàng')

@section('content')
<div class="container mt-4">
    <h3>Nhập kho sản phẩm</h3>

    <form action="{{ route('products.import.store', $product->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">ID sản phẩm</label>
            <input type="text" class="form-control" value="{{ $product->id }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" value="{{ $product->name }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Số lượng hiện tại</label>
            <input type="text" class="form-control" value="{{ $product->quantity }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Số lượng nhập thêm</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá nhập</label>
            <input type="text" name="import_price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nhà cung cấp</label>
            <select name="supplier_id" class="form-select" required>
                <option value="">-- Chọn nhà cung cấp --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả / Ghi chú</label>
            <textarea name="detail" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Nhập kho</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
