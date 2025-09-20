@extends('layouts.admin')
@section('title', 'Danh sách sản phẩm')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Danh sách sản phẩm</h2>
     @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
   @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
<div class="mb-3 d-flex gap-2">
    <div class="mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Thêm sản phẩm</a>
    </div>
    <div class="dropdown d-inline">
        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Xuất danh sách
        </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <li><a class="dropdown-item" href="{{ route('products.export') }}">Tất cả</a></li>
                @foreach($categories as $c)
                    <li>
                        <a class="dropdown-item" href="{{ route('products.export', $c->id) }}">
                            {{ $c->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
    </div>
</div>

    {{-- Filters --}}
    <form id="filter-form" class="row g-3 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Tên hoặc tác giả">
        </div>
        {{-- lọc theo category --}}
        <div class="col-md-2">
            <select name="category_id" class="form-select">
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id')==$c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="price_range" class="form-select">
                <option value="">-- Khoảng giá --</option>
                <option value="0-100000" {{ request('price_range')=='0-100000' ? 'selected' : '' }}>0 - 100k</option>
                <option value="100001-500000" {{ request('price_range')=='100001-500000' ? 'selected' : '' }}>100k - 500k</option>
                <option value="500001-1000000" {{ request('price_range')=='500001-1000000' ? 'selected' : '' }}>500k - 1tr</option>
                <option value="1000001-99999999" {{ request('price_range')=='1000001-99999999' ? 'selected' : '' }}>> 1tr</option>
            </select>
        </div>

        {{-- thay vì chọn khoảng số lượng -> nhập số lượng max --}}
        <div class="col-md-2">
            <input type="number" name="quantity_max" class="form-control" 
                   placeholder="Số lượng nhỏ hơn hoặc bằng..."
                   value="{{ request('quantity_max') }}">
        </div>

        <div class="col-md-2">
            <select name="per_page" class="form-select" id="per-page">
                <option value="10" {{ request('per_page')=='10' ? 'selected' : '' }}>10 / trang</option>
                <option value="20" {{ request('per_page')=='20' ? 'selected' : '' }}>20 / trang</option>
                <option value="30" {{ request('per_page')=='30' ? 'selected' : '' }}>30 / trang</option>
            </select>
        </div>

        <div class="col-md-1 d-flex align-items-center">
            <button type="button" id="reset-filter" class="btn btn-secondary w-100">Reset</button>
        </div>
    </form>

    {{-- Table --}}
    <div id="product-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th><a href="#" class="sort" data-sort="id">ID</a></th>
                    <th><a href="#" class="sort" data-sort="name">Tên</a></th>
                    <th><a href="#" class="sort" data-sort="category_id">Danh mục</a></th>
                    <th>Ảnh</th>
                    <th><a href="#" class="sort" data-sort="author">Tác giả</a></th>
                    <th><a href="#" class="sort" data-sort="price">Giá gốc</a></th>
                    <th><a href="#" class="sort" data-sort="quantity">Số lượng</a></th>
                    <th width="170">Hành động</th>
                </tr>
            </thead>
            <tbody id="product-table">
    @forelse($products as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name ?? '—' }}</td>
            <td>@if($p->images)<img src="{{ asset('storage/'.$p->images) }}" width="50">@endif</td>
            <td>{{ $p->author ?? '—' }}</td>
            <td>{{ number_format($p->price) }} đ</td>
            <td>{{ $p->quantity }}</td>
            <td>
                <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xoá?')">Xoá</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center text-muted">Không tìm thấy sản phẩm nào</td>
        </tr>
    @endforelse
</tbody>
        </table>

        {{-- Pagination --}}
        <div id="pagination-links">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- JS AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let sort = 'id';
let order = 'asc';

function loadData(page = 1) {
    let formData = $('#filter-form').serializeArray();
    formData.push({name:'sort', value:sort});
    formData.push({name:'order', value:order});
    formData.push({name:'page', value:page});

    $.get("{{ route('products.index') }}", $.param(formData), function(data){
        let html = $(data);
        $('#product-table').html(html.find('#product-table').html());
        $('#pagination-links').html(html.find('#pagination-links').html());
    });
}

$('#filter-form select, #filter-form input').on('change', function() {
    loadData(1);
});

// Reset
$('#reset-filter').click(()=>{
    $('#filter-form')[0].reset();
    loadData(1);
});

// Sort
$(document).on('click', '.sort', function(e){
    e.preventDefault();
    let newSort = $(this).data('sort');
    if(sort===newSort) order = (order==='asc')?'desc':'asc';
    else { sort = newSort; order = 'asc'; }
    loadData(1);
});
</script>
@endsection
