@extends('admin.layout')
@section('title', 'Danh sách tài khoản')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách tài khoản</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">+ Thêm tài khoản</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Role</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th width="170">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->id }}</td>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->email }}</td>
                    <td>{{ $account->phone }}</td>
                    
                    <td>
                        @if($account->role == 0)
                            <span class="badge bg-primary">Admin</span>
                        @elseif($account->role == 1)
                            <span class="badge bg-secondary">Cộng tác viên</span>
                        @elseif($account->role == 2)
                            <span class="badge bg-success">Khách hàng</span>
                        @endif
                        
                    </td>
                    <td>
                        @if($account->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Khoá</span>
                        @endif
                    </td>
                    <td>{{ $account->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('accounts.show', $account->id) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xoá?')">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Không có tài khoản nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection