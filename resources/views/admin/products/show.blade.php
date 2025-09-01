@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết sản phẩm</h2>

    <div class="card">
        <div class="card-body row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $product->id }}</p>
                <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Không có' }}</p>
                <p><strong>Tên:</strong> {{ $product->name }}</p>
                <p><strong>Độ tuổi:</strong> {{ $product->age }}</p>
                <p><strong>Tác giả:</strong> {{ $product->author }}</p>
                <p><strong>Nhà xuất bản:</strong> {{ $product->publisher }}</p>
                <p><strong>Ngôn ngữ:</strong> {{ $product->language }}</p>
                <p><strong>Loại (categ):</strong> {{ $product->categ }}</p>
                <p><strong>Mô tả:</strong><br>{{ $product->detail }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
                <p><strong>Sale:</strong> {{ number_format($product->sale, 0, ',', '.') }} đ</p>
                <p><strong>Số lượng tồn:</strong> {{ $product->quantity }}</p>
                <p><strong>Số lượng đã mua:</strong> {{ $product->quantity_buy }}</p>
                <p><strong>Trọng lượng:</strong> {{ $product->weight }}</p>
                <p><strong>Kích thước:</strong> {{ $product->size }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="badge bg-info">{{ $product->status }}</span>
                </p>
                <p><strong>Kích hoạt:</strong> 
                    {!! $product->is_active ? '<span class="badge bg-success">Có</span>' : '<span class="badge bg-danger">Không</span>' !!}
                </p>
            </div>

            <div class="col-12 mt-3">
                <p><strong>Ảnh chính:</strong></p>
                @if($product->images)
                    <img src="{{ asset('storage/'.$product->images) }}" width="200" class="mb-3">
                @else
                    <p>Không có ảnh chính</p>
                @endif

                <p><strong>Ảnh phụ:</strong></p>
                @if($product->images_sup)
                    <div class="d-flex flex-wrap">
                        @foreach(json_decode($product->images_sup, true) as $sup)
                            <img src="{{ asset('storage/'.$sup) }}" width="120" class="me-2 mb-2">
                        @endforeach
                    </div>
                @else
                    <p>Không có ảnh phụ</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Sửa</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection
