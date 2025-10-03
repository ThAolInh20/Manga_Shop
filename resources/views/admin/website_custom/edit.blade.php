@extends('layouts.admin')

@section('title', 'Cấu hình Website')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">⚙️ Cấu hình Website</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('website_custom.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Cột trái: thông tin -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Thông tin cơ bản</h5>

                        <div class="mb-3">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" class="form-control" 
                                   value="{{ old('address', $config->address ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label>Hotline</label>
                            <input type="text" name="hotline" class="form-control" 
                                   value="{{ old('hotline', $config->hotline ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $config->email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label>Màu nền</label>
                            <input type="color" name="background_color" 
                                   class="form-control form-control-color" 
                                   value="{{ old('background_color', $config->background_color ?? '#ffffff') }}">
                        </div>

                        <div class="mb-3">
                            <label>Chọn font chữ</label>
                            <select id="fontSelector" name="font_family" class="form-select">
                                <option value="Arial, sans-serif" {{ old('font_family', $config->font_family) == 'Arial, sans-serif' ? 'selected' : '' }}>Arial</option>
                                <option value="'Roboto', sans-serif" {{ old('font_family', $config->font_family) == "'Roboto', sans-serif" ? 'selected' : '' }}>Roboto</option>
                                <option value="'Open Sans', sans-serif" {{ old('font_family', $config->font_family) == "'Open Sans', sans-serif" ? 'selected' : '' }}>Open Sans</option>
                                <option value="'Poppins', sans-serif" {{ old('font_family', $config->font_family) == "'Poppins', sans-serif" ? 'selected' : '' }}>Poppins</option>
                                <option value="'Lato', sans-serif" {{ old('font_family', $config->font_family) == "'Lato', sans-serif" ? 'selected' : '' }}>Lato</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary">💾 Lưu thay đổi</button>
            </div>

            <!-- Cột phải: upload ảnh -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Ảnh & Banner</h5>

                        <!-- Logo -->
                        <div class="mb-3">
                            <label>Logo website</label>
                            @if(!empty($config->logo))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $config->logo) }}" 
                                         alt="Logo" style="max-height: 80px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control preview-input" data-preview="logoPreview">
                            <div id="logoPreview" class="mt-2"></div>
                        </div>

                        <!-- Banner chính -->
                        <div class="mb-3">
                            <label>Banner chính</label>
                            @if(!empty($config->banner_main))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $config->banner_main) }}" 
                                         alt="Banner" style="max-height: 180px; width:100%; object-fit:cover;">
                                </div>
                            @endif
                            <input type="file" name="banner_main" class="form-control preview-input" data-preview="mainBannerPreview">
                            <div id="mainBannerPreview" class="mt-2"></div>
                        </div>

                        <!-- Banner phụ -->
                        <div class="mb-3">
                            <label>Banner phụ</label>
                            @php
                                $subBanners = json_decode($config->sub_banners ?? '[]', true);
                            @endphp

                            <!-- Ảnh cũ -->
                            @if(!empty($subBanners))
                                <div class="d-flex flex-wrap mb-2" id="subBannersPreview">
                                    @foreach($subBanners as $i => $sb)
                                        <div class="position-relative me-2 mb-2">
                                            <img src="{{ asset('storage/'.$sb) }}" 
                                                 class="rounded shadow-sm" 
                                                 style="max-height: 100px;">

                                            <!-- Nút X -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-sub-banner" 
                                                    data-index="{{ $i }}">
                                                &times;
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Input ẩn giữ danh sách cũ -->
                            <input type="hidden" name="sub_banners_old" id="subBannersInput" value='@json($subBanners)'>

                            <!-- Chọn ảnh mới -->
                            <input type="file" name="sub_banners[]" class="form-control preview-input" multiple data-preview="newSubBannersPreview">

                            <!-- Preview ảnh mới -->
                            <div id="newSubBannersPreview" class="d-flex flex-wrap mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </form>
</div>

<script>
// Xoá banner phụ cũ
document.querySelectorAll('.remove-sub-banner').forEach(btn => {
    btn.addEventListener('click', function() {
        let index = this.dataset.index;
        let input = document.getElementById('subBannersInput');
        let subBanners = JSON.parse(input.value);

        subBanners.splice(index, 1); // xoá phần tử
        input.value = JSON.stringify(subBanners);

        this.closest('.position-relative').remove(); // xoá UI
    });
});

// Preview cho tất cả input có class .preview-input
document.querySelectorAll('.preview-input').forEach(input => {
    input.addEventListener('change', function() {
        let previewId = this.dataset.preview;
        let preview = document.getElementById(previewId);
        preview.innerHTML = ''; // clear cũ

        [...this.files].forEach(file => {
            let reader = new FileReader();
            reader.onload = e => {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('rounded', 'shadow-sm', 'me-2', 'mb-2');
                img.style.maxHeight = '120px';
                img.style.maxWidth = '100%';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endsection
