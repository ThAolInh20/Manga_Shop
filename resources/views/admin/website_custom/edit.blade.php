@extends('layouts.admin')

@section('title', 'Cấu hình Website Custom')

@section('content')
<div class="container mt-4">
    <h2>Cấu hình Website Custom</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('website_custom.update') }}" method="POST">
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

        <!-- <div class="mb-3">
            <label>Màu chính</label>
            <input type="color" name="primary_color" class="form-control form-control-color" value="{{ old('primary_color', $config->primary_color ?? '#ff6600') }}">
        </div> -->

        <div class="mb-3">
            <label>Màu nền</label>
            <input type="color" name="background_color" class="form-control form-control-color" value="{{ old('background_color', $config->background_color ?? '#ffffff') }}">
        </div>
<!-- 
        <div class="mb-3">
            <label>Background (url)</label>
            <input type="text" name="background" class="form-control" value="{{ old('background', $config->background ?? '') }}">
        </div> -->
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
        

        <button class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>
@endsection
