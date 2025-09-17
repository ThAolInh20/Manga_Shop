@extends('layouts.admin')

@section('title', 'Danh sách danh mục')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách danh mục</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <!-- Nút mở modal thêm -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Thêm danh mục</button>
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
                <th width="200">Hành động</th>
            </tr>
        </thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->detail ?? '—' }}</td>
                <td>{{ $category->products_count }}</td>
                <td>{{ optional($category->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ optional($category->updatedBy)->name ?? '—' }}</td>
                <td>
                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-info">Xem</a>

                    <!-- Nút sửa mở modal -->
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

<!-- Modal Thêm -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Thêm danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Tên danh mục</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="detail" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Các modal sửa (render ngoài vòng lặp) -->
@foreach($categories as $category)
<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Tên danh mục</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                </div>
                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="detail" class="form-control">{{ $category->detail }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
