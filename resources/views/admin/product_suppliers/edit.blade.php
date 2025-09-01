@extends('layouts.admin')

@section('title', 'Sửa nhập hàng')

@section('content')
<div class="container mt-4">
    <h2>Sửa nhập hàng</h2>

    <form action="{{ route('product_suppliers.update', $import->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Sản phẩm -->
        <div class="mb-3">
            <label>Sản phẩm</label>
            <select name="product_id" class="form-control">
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $import->product_id == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Nhà cung cấp -->
        <div class="mb-3">
            <label>Nhà cung cấp</label>
            <select name="supplier_id" class="form-control">
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ $import->supplier_id == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Ngày nhập -->
        <div class="mb-3">
            <label>Ngày nhập</label>
            <input type="date" name="date_import" class="form-control" value="{{ old('date_import', $import->date_import) }}">
        </div>

        <!-- Giá nhập -->
        <div class="mb-3">
            <label>Giá nhập</label>
            <input type="number" step="0.01" name="import_price" class="form-control" value="{{ old('import_price', $import->import_price) }}">
        </div>

        <!-- Số lượng -->
        <div class="mb-3">
            <label>Số lượng</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $import->quantity) }}">
        </div>

        <!-- Ghi chú -->
        <div class="mb-3">
            <label>Chi tiết</label>
            <textarea name="detail" rows="3" class="form-control">{{ old('detail', $import->detail) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('product_suppliers.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
