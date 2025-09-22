@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="container mt-4">
    <h2>Sửa sản phẩm</h2>
     @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
   @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
<a href="{{ route('products.import', $product->id) }}" class="btn btn-sm btn-warning">
    Nhập hàng
</a>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <!-- Danh mục -->
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tên -->
                <div class="mb-3">
                    <label>Tên sản phẩm <span class="text-error">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tuổi -->
                <div class="mb-3">
                    <label>Độ tuổi</label>
                    <input type="number" name="age" class="form-control @error('age') is-invalid @enderror"
                           value="{{ old('age', $product->age) }}">
                    @error('age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tác giả -->
                <div class="mb-3">
                    <label>Tác giả</label>
                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                           value="{{ old('author', $product->author) }}">
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nhà xuất bản -->
                <div class="mb-3">
                    <label>Nhà xuất bản</label>
                    <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror"
                           value="{{ old('publisher', $product->publisher) }}">
                    @error('publisher')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ngôn ngữ -->
                <div class="mb-3">
                    <label>Ngôn ngữ</label>
                    <input type="text" name="language" class="form-control @error('language') is-invalid @enderror"
                           value="{{ old('language', $product->language) }}">
                    @error('language')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <!-- Giá -->
                <div class="mb-3">
                    <label>Giá <span class="text-error">*</span></label>
                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $product->price) }}">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Giảm giá -->
                <div class="mb-3">
                    <label>Sale</label>
                    <input type="number" name="sale" class="form-control @error('sale') is-invalid @enderror"
                           value="{{ old('sale', $product->sale) }}">
                    @error('sale')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Số lượng -->
                <div class="mb-3">
                    <label>Số lượng</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', $product->quantity) }}" disabled>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Số lượng đã mua -->
                <div class="mb-3">
                    <label>Số lượng đã mua</label>
                    <input type="number" name="quantity_buy" class="form-control @error('quantity_buy') is-invalid @enderror"
                           value="{{ old('quantity_buy', $product->quantity_buy) }}" disabled>
                    @error('quantity_buy')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Trọng lượng -->
                <div class="mb-3">
                    <label>Trọng lượng</label>
                    <input type="text" name="weight" class="form-control @error('weight') is-invalid @enderror"
                           value="{{ old('weight', $product->weight) }}">
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kích thước -->
                <div class="mb-3">
                    <label>Kích thước</label>
                    <input type="text" name="size" class="form-control @error('size') is-invalid @enderror"
                           value="{{ old('size', $product->size) }}">
                    @error('size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Ảnh -->
        <div class="mb-3">
            <label>Ảnh chính</label><br>
            @if($product->images)
                <img src="{{ asset('storage/'.$product->images) }}" width="100" class="mb-2"><br>
            @endif
            <input type="file" name="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" onchange="previewMain(this)">
            @error('images')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <img id="preview-main" class="mt-2" width="100">
        </div>

        <div class="mb-3">
            <label>Ảnh phụ</label><br>
            @if($product->images_sup)
                <div class="d-flex flex-wrap mb-2">
                    @foreach(json_decode($product->images_sup, true) as $sup)
                        <div class="position-relative me-2 mb-2" style="width: 80px; height: 80px;">
                            <img src="{{ asset('storage/'.$sup) }}" 
                                 class="rounded border" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <!-- nút xoá -->
                            <button type="button" 
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 px-1"
                                    onclick="removeSupImage(this, '{{ $sup }}')">×</button>
                        </div>
                    @endforeach
                </div>
            @endif

            <input type="file" name="images_sup[]" class="form-control @error('images_sup') is-invalid @enderror" multiple accept="image/*" onchange="previewSup(this)">
            @error('images_sup')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div id="preview-sup" class="d-flex flex-wrap mt-2"></div>
        </div>

        <!-- Trạng thái -->
        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror">
                <option value="new" {{ $product->status == 'new' ? 'selected' : '' }}>Mới</option>
                <option value="hot" {{ $product->status == 'hot' ? 'selected' : '' }}>Hot</option>
                <option value="sale" {{ $product->status == 'sale' ? 'selected' : '' }}>Sale</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Kích hoạt -->
        <div class="mb-3">
            <label>Kích hoạt</label>
            <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                <option value="1" {{ $product->is_active ? 'selected' : '' }}>Có</option>
                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Không</option>
            </select>
            @error('is_active')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Loại -->
        <div class="mb-3">
            <label>Loại (categ)</label>
            <input type="text" name="categ" class="form-control @error('categ') is-invalid @enderror"
                   value="{{ old('categ', $product->categ) }}">
            @error('categ')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Mô tả -->
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="detail" rows="4" class="form-control @error('detail') is-invalid @enderror">{{ old('detail', $product->detail) }}</textarea>
            @error('detail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
    </form>
</div>

<script>
function previewMain(input) {
    const img = document.getElementById('preview-main');
    img.src = URL.createObjectURL(input.files[0]);
}
function previewSup(input) {
    const preview = document.getElementById('preview-sup');
    preview.innerHTML = "";
    Array.from(input.files).forEach(file => {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.width = 80;
        img.classList.add("me-2", "mb-2");
        preview.appendChild(img);
    });
}
function removeSupImage(button, filename) {
    // Xoá khỏi giao diện
    button.parentElement.remove();

    // Nếu cần xoá luôn trong DB thì thêm 1 input hidden
    let container = document.getElementById('preview-sup');
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remove_images_sup[]';
    input.value = filename;
    container.appendChild(input);
}
</script>
<style>
    .text-error {
    color: red;
    font-weight: bold;
}
</style>
@endsection
