@extends('layouts.admin')

@section('title', 'Chi tiết nhà cung cấp')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết nhà cung cấp</h2>

    <p><strong>Tên:</strong> {{ $supplier->name }}</p>
    <p><strong>Địa chỉ:</strong> {{ $supplier->address }}</p>
    <p><strong>SĐT:</strong> {{ $supplier->phone }}</p>
    <p><strong>Email:</strong> {{ $supplier->email }}</p>
    <p><strong>Mã số thuế:</strong> {{ $supplier->tax_code }}</p>
    <p><strong>Hợp đồng:</strong> 
        @if($supplier->link_contract)
                        <a href="{{ $supplier->link_contract }}" target="_blank">Xem</a>
                    @else
                        Không có
                    @endif
    </p>

    <h3 class="mt-4">Danh sách sản phẩm nhập</h3>

    <div class="mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" id="search_name" class="form-control" placeholder="Tìm theo tên sản phẩm">
            </div>
            <div class="col-md-4">
                <input type="date" id="date" class="form-control">
            </div>
            <div class="col-md-3">
                <button id="filterBtn" class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </div>

    <div id="productsTable">
        @include('admin.suppliers.products_table', ['products' => $products,'supplier'=>$supplier])
    </div>

    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // AJAX filter
    $('#filterBtn').click(function() {
        let name = $('#search_name').val();
        let date = $('#date').val();

        $.ajax({
            url: "{{ route('suppliers.filterProducts', $supplier->id) }}",
            method: 'GET',
            data: { name: name, date: date },
            success: function(res) {
                $('#productsTable').html(res);
            },
            error: function(err) {
                alert('Có lỗi xảy ra!');
            }
        });
    });

    // AJAX pagination
    $(document).on('click', '#productsTable .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let name = $('#search_name').val();
        let date = $('#date').val();

        $.ajax({
            url: url,
            method: 'GET',
            data: { name: name, date: date },
            success: function(res) {
                $('#productsTable').html(res);
            }
        });
    });
});
</script>
@endpush
