@extends('layouts.admin')
@section('title', 'Danh sách tài khoản')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách tài khoản</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">+ Thêm tài khoản</a>
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
                <option value="0">Khoá</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="per_page" class="form-select" id="per-page">
                <option value="10">10 / trang</option>
                <option value="20">20 / trang</option>
                <option value="30">30 / trang</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button type="button" id="reset-filter" class="btn btn-secondary w-100">Reset</button>
        </div>
    </form>

    {{-- Table --}}
    <div id="account-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th><a href="#" class="sort" data-sort="id">ID</a></th>
                    <th><a href="#" class="sort" data-sort="name">Tên</a></th>
                    <th><a href="#" class="sort" data-sort="email">Email</a></th>
                    <th><a href="#" class="sort" data-sort="phone">SĐT</a></th>
                    <th><a href="#" class="sort" data-sort="role">Role</a></th>
                    <th><a href="#" class="sort" data-sort="is_active">Trạng thái</a></th>
                    <th width="170">Hành động</th>
                </tr>
            </thead>
            <tbody id="account-table">
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->email }}</td>
                        <td>{{ $account->phone }}</td>
                        <td>
                            @if($account->role == 0)<span class="badge bg-primary">Admin</span>
                            @elseif($account->role == 1)<span class="badge bg-secondary">Cộng tác viên</span>
                            @else<span class="badge bg-success">Khách hàng</span>@endif
                        </td>
                        <td>
                            @if($account->is_active)<span class="badge bg-success">Hoạt động</span>
                            @else<span class="badge bg-danger">Khoá</span>@endif
                        </td>
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
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div id="pagination-links">
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

{{-- CSS hide big arrows in pagination --}}
<style>
.pagination a { text-decoration: none; }
.pagination li a::after { content: none; }
</style>
@endsection
