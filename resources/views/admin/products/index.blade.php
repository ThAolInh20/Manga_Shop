@extends('admin.layout')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách sản phẩm</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Thêm sản phẩm</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th width="150">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category->name ?? '—' }}</td>
                <td>
                    @if($p->images)
                        <img src="{{ asset('storage/'.$p->images) }}" width="50">
                    @endif
                </td>
                <td>{{ number_format($p->price) }} đ</td>
                <td>
                    <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Xoá sản phẩm?')" class="btn btn-sm btn-danger">Xoá</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
