@extends('admin.layout')

@section('title', 'Thêm sản phẩm')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container mt-4">
    <h2>Thêm sản phẩm</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}">
        </div>

        {{-- Ảnh chính --}}
        <div class="mb-3">
            <label>Ảnh chính</label>
            <input type="file" name="images" class="form-control" accept="image/*" onchange="previewMain(this)">
            <img id="preview-main" class="mt-2 border rounded" width="100" style="display:none;">
        </div>

        {{-- Ảnh phụ (chọn nhiều) --}}
        <div class="mb-3">
            <label>Ảnh phụ</label>
            <input type="file" name="images_sup[]" class="form-control" multiple accept="image/*" onchange="previewSup(this)">
            <div id="preview-sup" class="d-flex flex-wrap mt-2"></div>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>

<script>
function previewMain(input) {
    const img = document.getElementById('preview-main');
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        img.style.display = "block";
    }
}

function previewSup(input) {
    const preview = document.getElementById('preview-sup');
    preview.innerHTML = "";
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            img.width = 80;
            img.classList.add("me-2", "mb-2", "border", "rounded");
            preview.appendChild(img);
        });
    }
}
</script>
@endsection
