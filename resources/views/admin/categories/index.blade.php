@extends('admin.layout')

@section('title', 'Danh sách danh mục')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách danh mục</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Thêm danh mục</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Số lượng sản phẩm</th>
                <th>Ngày tạo</th>
                <th>Người sửa</th>
                <th width="150">Hành động</th>
            </tr>
        </thead>
        <tbody>
    @forelse($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->detail ?? '—' }}</td>
            <td>{{ $category->products_count }}</td>
            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $category->updatedBy->name ?? '—' }}</td>
            <td>
                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-info">Xem</a>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xoá?')">Xoá</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">Không có danh mục nào.</td>
        </tr>
    @endforelse
</tbody>
    </table>
</div>
@endsection
