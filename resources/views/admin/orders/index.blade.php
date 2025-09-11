@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container">
    <h2>Danh sách đơn hàng</h2>

    {{-- Bộ lọc --}}
    <form id="filter-form" class="row g-2 mb-3">
        <div class="col-md-3">
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
        <div class="col-md-3">
        <input type="date" name="order_date" class="form-control">
    </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Lọc</button>
            <button type="reset" class="btn btn-secondary" id="reset-filter">Reset</button>
        </div>
    </form>

    <div id="orders-wrapper">
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

    // Lọc
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        fetchOrders(new URLSearchParams(new FormData(form)).toString());
    });

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

    // Khởi tạo hành động
    function initActions() {
        document.querySelectorAll("#orders-table tbody tr").forEach(row => {
            const statusCode = parseInt(row.dataset.status);
            const orderStatuses = {
                0: { text: "Chờ khách xác nhận đơn", class: "secondary" },
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

            let btnHtml = `<a href="/admin/orders/${row.dataset.id}" class="btn btn-warning btn-sm me-1">Xem</a>`;
            if (statusCode === 0) {
                btnHtml += `<button class="btn btn-danger btn-sm cancel_order" data-id="${row.dataset.id}">Hủy</button>`;
            }
            if (statusCode === 1) {
                btnHtml += `<button class="btn btn-primary btn-sm update-status" data-id="${row.dataset.id}" data-next="2">Xác nhận giao</button>`;
            }
            if (statusCode === 2) {
                btnHtml += `<button class="btn btn-success btn-sm update-status" data-id="${row.dataset.id}" data-next="3">Hoàn tất</button>`;
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
