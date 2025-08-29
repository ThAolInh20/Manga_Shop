@extends('admin.layout')

@section('title', 'Thêm nhập hàng')

@section('content')
<div class="container mt-4">
    <h2>Thêm nhập hàng</h2>
    <form action="{{ route('product_suppliers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Sản phẩm</label>
            <select name="product_id" class="form-control">
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nhà cung cấp</label>
            <select name="supplier_id" class="form-control">
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

     

        <div class="mb-3">
            <label>Giá nhập</label>
            <input type="number" step="0.01" name="import_price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Số lượng</label>
            <input type="number" name="quantity" class="form-control">
        </div>

        <div class="mb-3">
            <label>Chi tiết</label>
            <textarea name="detail" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
