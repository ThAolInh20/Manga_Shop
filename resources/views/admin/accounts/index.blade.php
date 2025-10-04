@extends('layouts.admin')
@section('title', 'Danh sách tài khoản')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách tài khoản</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
   @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">+ Thêm tài khoản</a>
        <button id="reset-filter" class="btn btn-secondary">Reset bộ lọc</button>
    </div>

    {{-- Filters --}}
    <form id="filter-form" class="row g-3 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm...">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">-- Lọc theo role --</option>
                <option value="0">Admin</option>
                <option value="1">Cộng tác viên</option>
                <option value="2">Khách hàng</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="is_active" class="form-select">
                <option value="">-- Lọc theo trạng thái --</option>
                <option value="1">Hoạt động</option>
                <option value="0">Yêu cầu xóa</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="per_page" class="form-select" id="per-page">
                <option value="10">10 / trang</option>
                <option value="20">20 / trang</option>
                <option value="30">30 / trang</option>
            </select>
        </div>
    </form>

    {{-- Table --}}
    <div id="account-container" class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
          
            <thead class="table-dark">
                <tr>
                    <th><a href="#" class="sort text-white" data-sort="id">ID</a></th>
                    <th><a href="#" class="sort text-white" data-sort="name">Tên</a></th>
                    <th><a href="#" class="sort text-white" data-sort="email">Email</a></th>
                    <th><a href="#" class="sort text-white" data-sort="phone">SĐT</a></th>
                    <th><a href="#" class="sort text-white" data-sort="role">Role</a></th>
                    

                    <th><a href="#" class="sort text-white" data-sort="is_active">Trạng thái</a></th>
                    <th>Người cập nhật</th>
                    <th width="180">Hành động</th>
                </tr>
            </thead>
            <tbody id="account-table">
                @forelse($accounts as $account)
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->email }}</td>
                        <td>{{ $account->phone }}</td>
                        <td>
                            @switch($account->role)
                                @case(0)<span class="badge bg-primary">Admin</span>@break
                                @case(1)<span class="badge bg-secondary">Cộng tác viên</span>@break
                                @default<span class="badge bg-success">Khách hàng</span>
                            @endswitch
                        </td>
                        <td>
                            @if($account->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Yêu cầu xóa</span>
                            @endif
                        </td>
                        <td>{{ $account->updatedBy? $account->updatedBy->name : '' }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('accounts.show', $account->id) }}" class="btn btn-sm btn-info">Xem</a>
                            @if($account->role!=2)
                                <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            @endif
                            @if(!$account->is_active || $account->role==1)
                                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xoá?')">Xoá</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có tài khoản phù hợp</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
  {{-- Pagination --}}
        <div id="pagination-links" class="">
            {{ $accounts->links('pagination::bootstrap-5') }}
        </div>
        
    </div>
</div>

{{-- JS AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let sort = 'id';
let order = 'asc';

function loadData(page = 1) {
    let formData = $('#filter-form').serialize();
    $.ajax({
        url: "{{ route('accounts.index') }}?page=" + page + "&sort=" + sort + "&order=" + order,
        data: formData,
        success: function (data) {
            let html = $(data);
            $('#account-table').html(html.find('#account-table').html());
            $('#pagination-links').html(html.find('#pagination-links').html());
        }
    });
}

// Reset
$('#reset-filter').click(function(){
    $('#filter-form')[0].reset();
    loadData(1);
});

// Filter
$('#filter-form select, #filter-form input').on('change input', function(){
    loadData(1);
});

// Sort
$(document).on('click', '.sort', function(e){
    e.preventDefault();
    let newSort = $(this).data('sort');
    if(sort === newSort) order = (order==='asc')?'desc':'asc';
    else { sort = newSort; order = 'asc'; }
    loadData(1);
});
</script>

<style>
.pagination a { text-decoration: none; }
.pagination li a::after { content: none; }
.table th, .table td { vertical-align: middle; }
.table td .btn { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
</style>
@endsection
