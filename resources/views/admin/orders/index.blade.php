@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')


<div class="container">
    <h2>Danh sách đơn hàng</h2>
     @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
   @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
<div class="dropdown d-inline">
        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Xuất báo cáo
        </button>
        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'all') }}">Tất cả</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'week') }}">Tuần này</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'lastWeek') }}">Tuần trước</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'month') }}">Tháng này</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'lastMonth') }}">Tháng trước</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'year') }}">Năm nay</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.orders.export', 'lastYear') }}">Năm trước</a></li>
        </ul>
    </div>
    {{-- Bộ lọc --}}
    <form id="filter-form" class="row g-2 mb-3">
        <div class="col-md-2">
            <input type="text" name="customer_name" class="form-control" placeholder="Tên khách hàng">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="0">Chờ thanh toán</option>
                <option value="1">Đang xử lý</option>
                <option value="2">Đang giao</option>
                <option value="3">Hoàn tất</option>
                <option value="4">Đổi trả</option>
                <option value="5">Đã hủy</option>
            </select>
        </div>
         <div class="col-md-2">
            <select name="select_fill" class="form-select">
                <option value="">-- Tất cả --</option>
                <option value="week">Tuần này</option>
                <option value="lastWeek">Tuần trước</option>
                <option value="month">Tháng này</option>
                <option value="lastMonth">Tháng trước</option>
                <option value="year">Năm nay</option>
                <option value="lastYear">Năm trước</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="order_date" class="form-control">
        </div>
        <div class="col-md-2">
            <select id="per-page" class="form-select">
                <option value="10" {{ request()->get('per_page') == 10 ? 'selected' : '' }}>10/trang</option>
                <option value="20" {{ request()->get('per_page') == 20 ? 'selected' : '' }}>20/trang</option>
                <option value="30" {{ request()->get('per_page') == 30 ? 'selected' : '' }}>30/trangư</option>
            </select>
        </div>
        <div class="col-md-2">
            <!-- <button type="submit" class="btn btn-primary">Lọc</button> -->
            <button type="reset" class="btn btn-secondary" id="reset-filter">Reset</button>
            
        </div>
    </form>

    <div id="orders-wrapper">
        <div class="mb-3">
    
</div>
        @include('admin.orders.table', ['orders' => $orders])
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.getElementById("orders-wrapper");
    const form = document.getElementById("filter-form");

    // Fetch dữ liệu
    function fetchOrders(query = "") {
        let url = `/admin/orders?ajax=1`;
        if (query) url += "&" + query;

        fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(res => res.text())
            .then(html => { wrapper.innerHTML = html; initActions(); })
            .catch(err => console.error(err));
    }
    document.getElementById('per-page').addEventListener('change', function() {
        let params = new URLSearchParams(new FormData(form));
        params.set('per_page', this.value);
        fetchOrders(params.toString());
    });

    form.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('change', function() {
            let params = new URLSearchParams(new FormData(form));
            fetchOrders(params.toString());
        });
    });
    // Lọc
    // form.addEventListener("submit", function (e) {
    //     e.preventDefault();
    //     fetchOrders(new URLSearchParams(new FormData(form)).toString());
    // });
    

    // Reset filter
    document.getElementById("reset-filter").addEventListener("click", function () {
        form.reset();
        fetchOrders();
    });

    // Phân trang
    document.addEventListener("click", function (e) {
        if (e.target.closest(".pagination a")) {
            e.preventDefault();
            fetchOrders(new URL(e.target.closest("a").href).searchParams.toString());
        }
    });
    // Sắp xếp
document.addEventListener("click", function (e) {
    const link = e.target.closest(".sort-link");
    if (link) {
        e.preventDefault();
        const field = link.dataset.field;
        const order = link.dataset.order;

        // Giữ filter hiện tại
        let params = new URLSearchParams(new FormData(form));
        params.set("sort_field", field);
        params.set("sort_order", order);

        fetchOrders(params.toString());
    }
});

    // Khởi tạo hành động
    function initActions() {
        document.querySelectorAll("#orders-table tbody tr").forEach(row => {
            const statusCode = parseInt(row.dataset.status);
            const orderStatuses = {
                0: { text: "Chờ khách thanh toán", class: "secondary" },
                1: { text: "Đang xử lý", class: "info" },
                2: { text: "Đang giao", class: "primary" },
                3: { text: "Hoàn tất", class: "success" },
                4: { text: "Đổi trả", class: "warning" },
                5: { text: "Đã hủy", class: "danger" }
            };

            row.querySelector(".status-cell").innerHTML =
                `<span class="badge bg-${orderStatuses[statusCode].class}">
                    ${orderStatuses[statusCode].text}
                </span>`;

            let btnHtml = `<a href="/admin/orders/${row.dataset.id}" class="btn btn-info btn-sm me-1">Xem</a>`;
            if (statusCode === 1) {
                btnHtml += `<button class="btn btn-danger btn-sm cancel_order" data-id="${row.dataset.id}">Hủy</button>`;
            }
            if (statusCode === 1) {
                btnHtml += `<button class="btn btn-primary btn-sm update-status" data-id="${row.dataset.id}" data-next="2">Xác nhận giao</button>`;
            }
            if (statusCode === 2) {
                btnHtml += `<button class="btn btn-success btn-sm update-status" data-id="${row.dataset.id}" data-next="3">Hoàn tất</button>`;
            }
            if (statusCode === 3) {
                btnHtml += `<button class="btn btn-warning btn-sm update-status" data-id="${row.dataset.id}" data-next="4">Đổi trả</button>`;
            }
            row.querySelector(".action-cell").innerHTML = btnHtml;
        });

        // Hủy đơn
        document.querySelectorAll(".cancel_order").forEach(btn => {
            btn.addEventListener("click", function () {
                const id = this.dataset.id;
                if (confirm("Bạn có chắc muốn hủy đơn này không?")) {
                    fetch(`/admin/orders/${id}/cancel`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => { alert(data.message); fetchOrders(); });
                }
            });
        });

        // Cập nhật trạng thái
        document.querySelectorAll(".update-status").forEach(btn => {
            btn.addEventListener("click", function () {
                const id = this.dataset.id;
                const next = this.dataset.next;
                if (confirm("Xác nhận cập nhật trạng thái?")) {
                    fetch(`/admin/orders/${id}/status`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ status_want: parseInt(next) })
                    })
                    .then(res => res.json())
                    .then(data => { alert(data.message); fetchOrders(); });
                }
            });
        });
    }

    initActions();
});
</script>
@endsection
