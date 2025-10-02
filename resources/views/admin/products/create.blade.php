@extends('layouts.admin')

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

        <div class="row">
            <div class="col-md-6">
                <!-- Danh mục -->
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
    <label>Tên sản phẩm <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>



<!-- Giá -->
<div class="mb-3">
    <label>Giá<span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}">
    @error('price')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
<!-- Tác giả -->
<div class="mb-3">
    <label>Tác giả </label>
    <input type="text" name="author" class="form-control" value="{{ old('author') }}">
    @error('author')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

                <!-- Tuổi -->
                <div class="mb-3">
                    <label>Độ tuổi</label>
                    <input type="number" name="age" class="form-control" value="">
                </div>

               

             
                
            </div>

            <div class="col-md-6">
               
           
                <!-- Giảm giá -->
                <div class="mb-3">
                    <label>Sale</label>
                    <input type="number" step="0.01" name="sale" class="form-control" value="0.00">
                </div>
                 <div class="mb-3">
                    <label>Thể loại</label>
                    <input type="text" name="categ" class="form-control" value="">
                </div>
<div class="mb-3">
                    <label>Nhà xuất bản</label>
                    <input type="text" name="publisher" class="form-control" value="">
                </div>

                <!-- Ngôn ngữ -->
                <div class="mb-3">
                    <label>Ngôn ngữ</label>
                    <input type="text" name="language" class="form-control" value="">
                </div>

               



                <!-- Trọng lượng -->
                <div class="mb-3">
                    <label>Trọng lượng</label>
                    <input type="text" name="weight" class="form-control" value="">
                </div>

                <!-- Kích thước -->
                <div class="mb-3">
                    <label>Kích thước</label>
                    <input type="text" name="size" class="form-control" value="">
                </div>
            </div>
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
       <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="detail" rows="4" class="form-control"></textarea>
           
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
