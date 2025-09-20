@extends('layouts.admin')

@section('title', 'Cấu hình Website')

@section('content')
<div class="container mt-4">
    <h2>Cấu hình Website</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('website_custom.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $config->address ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Hotline</label>
            <input type="text" name="hotline" class="form-control" value="{{ old('hotline', $config->hotline ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $config->email ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Màu nền</label>
            <input type="color" name="background_color" class="form-control form-control-color" value="{{ old('background_color', $config->background_color ?? '#ffffff') }}">
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

        <!-- Upload logo -->
        <div class="mb-3">
            <label>Logo website</label>
            @if(!empty($config->logo))
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $config->logo) }}" alt="Logo" style="max-height: 80px;">
                </div>
            @endif
            <input type="file" name="logo" class="form-control">
        </div>

        <button class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>
@endsection
