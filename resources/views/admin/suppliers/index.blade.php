@extends('layouts.admin')

@section('title', 'Danh sách nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Danh sách nhà cung cấp</h2>
     @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
   @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <a href="{{ route('suppliers.create') }}" class="btn btn-success mb-3">Thêm nhà cung cấp</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Mã số thuế</th>
                <th>Hợp đồng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $s)
            <tr>
                <td>{{ $s->name }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->phone }}</td>
                <td>{{ $s->tax_code }}</td>
                <td>
                    @if($s->link_contract)
                        <a href="{{ $s->link_contract }}" target="_blank">Xem</a>
                    @else
                        Không có
                    @endif
                </td>
                <td>
                    <a href="{{ route('suppliers.show', $s->id) }}" class="btn btn-info btn-sm">Xem</a>
                    <a href="{{ route('suppliers.edit', $s->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('suppliers.active', $s->id) }}" method="POST" class="d-inline">
                        @csrf @method('POST')
                            @if($s->is_active)
                         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tạm khóa nhà cung cấp?')">Khóa</button>
                            @else
                         <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Mở lại nhà cung cấp?')">Mở lại</button>
                            @endif
                        
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
