@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
<div class="container mt-4">
    <h2>Thêm danh mục mới</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="detail" class="form-label">Mô tả</label>
            <textarea name="detail" class="form-control">{{ old('detail') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
