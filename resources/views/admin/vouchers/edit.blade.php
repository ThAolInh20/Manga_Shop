@extends('layouts.admin')

@section('title', 'Sửa Voucher')

@section('content')
<div class="container mt-4">
    <h2>Sửa Voucher</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Mã voucher</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Sale (%)</label>
            <input type="number" name="sale" class="form-control" value="{{ old('sale', $voucher->sale) }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giảm tối đa</label>
            <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $voucher->max_discount) }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày hết hạn</label>
            <input type="date" name="date_end" class="form-control" value="{{ old('date_end', $voucher->date_end) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kích hoạt</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ old('is_active', $voucher->is_active) ? 'selected' : '' }}>Có</option>
                <option value="0" {{ old('is_active', $voucher->is_active) ? '' : 'selected' }}>Không</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-success">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
