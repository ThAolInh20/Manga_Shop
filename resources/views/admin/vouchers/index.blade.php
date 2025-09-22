@extends('layouts.admin')

@section('title', 'Danh sách Voucher')

@section('content')
<div class="container mt-4">
    <h2>Danh sách Voucher</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Nút mở modal thêm -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Thêm Voucher
    </button>

   <table class="table table-bordered">
    <thead>
        <tr>
            <th>Mã</th>
            <th>Sale (%)</th>
            <th>Giảm tối đa</th>
            <th>Ngày hết hạn</th>
            <th>Kích hoạt</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vouchers as $v)
            <tr>
                <td>{{ $v->code }}</td>
                <td>{{ $v->sale }}%</td>
                <td>{{ $v->max_discount }}</td>
                <td>{{ $v->date_end }}</td>
                <td>
                    @if($v->is_active)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Khoá</span>
                    @endif
                </td>
                <td>
                    <!-- Nút mở modal sửa -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $v->id }}">
                        Sửa
                    </button>

                    <!-- Nút xóa -->
                    <form action="{{ route('vouchers.destroy', $v->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa voucher này?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $vouchers->links() }}

<!-- Modal Edit đặt ngoài bảng -->
@foreach($vouchers as $v)
<div class="modal fade" id="editModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('vouchers.update', $v->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Sửa Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Mã voucher</label>
                    <input type="text" name="code" class="form-control" value="{{ $v->code }}" required>
                </div>
                <div class="mb-3">
                    <label>Sale (%)</label>
                    <input type="number" name="sale" class="form-control" step="0.01" value="{{ $v->sale }}" required>
                </div>
                <div class="mb-3">
                    <label>Giảm tối đa</label>
                    <input type="number" name="max_discount" class="form-control" step="0.01" value="{{ $v->max_discount }}">
                </div>
                <div class="mb-3">
                    <label>Ngày hết hạn</label>
                    <input type="date" name="date_end" class="form-control" value="{{ $v->date_end }}">
                </div>
                <div class="mb-3">
                    <label>Kích hoạt</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $v->is_active ? 'selected' : '' }}>Có</option>
                        <option value="0" {{ !$v->is_active ? 'selected' : '' }}>Không</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
@endforeach
        </tbody>
    </table>

    {{ $vouchers->links() }}
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('vouchers.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Thêm Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Mã voucher</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                    @error('code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Sale (%)</label>
                    <input type="number" name="sale" class="form-control" step="0.01" value="{{ old('sale') }}" required>
                    @error('sale')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Giảm tối đa</label>
                    <input type="number" name="max_discount" class="form-control" step="0.01" value="{{ old('max_discount') }}">
                    @error('max_discount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Ngày hết hạn</label>
                    <input type="date" name="date_end" class="form-control" value="{{ old('date_end') }}">
                    @error('date_end')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Kích hoạt</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Có</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Không</option>
                    </select>
                    @error('is_active')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection
