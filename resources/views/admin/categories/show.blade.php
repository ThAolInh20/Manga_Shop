@extends('layouts.admin')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="container mt-4">
    <h2>Danh mục: {{ $category->name }}</h2>
    <p><strong>Mô tả:</strong> {{ $category->detail }}</p>
    <p><strong>Tổng sản phẩm:</strong> {{ $category->products->count() }}</p>

    <h4 class="mt-4">Danh sách sản phẩm</h4>
    @if($category->products->isEmpty())
        <p>Danh mục này chưa có sản phẩm nào.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Ảnh</th>
                    <th>Ngày tạo</th>
                    <th width="150">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($category->products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            @if($product->images)
                                <img src="{{ asset('storage/'.$product->images) }}" width="50">
                            @else
                                —
                            @endif
                        <td>{{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : '—' }}</td>
                        <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    
                </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection
