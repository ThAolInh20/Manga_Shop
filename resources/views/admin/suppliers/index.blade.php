@extends('admin.layout')

@section('title', 'Danh sách nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Danh sách nhà cung cấp</h2>
    
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
                    @if($s->contract)
                        <a href="{{ asset('storage/' . $s->contract) }}" target="_blank">Xem</a>
                    @else
                        Không có
                    @endif
                </td>
                <td>
                    <a href="{{ route('suppliers.show', $s->id) }}" class="btn btn-info btn-sm">Xem</a>
                    <a href="{{ route('suppliers.edit', $s->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('suppliers.destroy', $s->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
