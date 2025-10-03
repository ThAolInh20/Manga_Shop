@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')


<div class="container">
    <h2>Danh sách đơn hàng</h2>
  <div class="card-group mb-3">
    @foreach ($statuses as $code => $info)
        <div class="card border-{{ $info['class'] }}">
            <div class="card-body text-center">
                <h6 class="card-title mb-1 text-{{ $info['class'] }}">
                    {{ $info['label'] }}
                </h6>
                <div class="fs-4 fw-bold">
                    {{ $stats[$code] ?? 0 }}
                </div>
            </div>
        </div>
    @endforeach
</div>
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
        <div class="col-md-3">
            <input type="text" name="customer_name" class="form-control" placeholder="Mã đơn hoặc tên khách hàng">
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
                <option value="6">Hoàn tiền</option>
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
        <div class="col-md-1 d-none" >
            <select id="per-page" class="form-select">
                <option value="10" {{ request()->get('per_page') == 10 ? 'selected' : '' }}>10/trang</option>
                <option value="20" {{ request()->get('per_page') == 20 ? 'selected' : '' }}>20/trang</option>
                <option value="30" {{ request()->get('per_page') == 30 ? 'selected' : '' }}>30/trang</option>
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
<!-- Modal Hủy Đơn -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Xác nhận hủy đơn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Yêu cầu gọi khách hoặc người nhận trước khi hủy đơn!!!</strong></p>
        <p><strong>Tên khách hàng:</strong> <span id="modal-customer-name"></span></p>
        <p><strong>SĐT khách:</strong> <span id="modal-customer-phone"></span></p>
        <p><strong>Tên người nhận:</strong> <span id="modal-receiver-name"></span></p>
        <p><strong>SĐT người nhận:</strong> <span id="modal-receiver-phone"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-danger" id="confirmCancel">Xác nhận hủy</button>
      </div>
    </div>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    let currentPage = 1;
document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.getElementById("orders-wrapper");
    const form = document.getElementById("filter-form");

    // Fetch dữ liệu
 function fetchOrders(query = "") {
    let url = `/admin/orders?ajax=1`;
    if (query) url += "&" + query;

    // Cập nhật biến currentPage mỗi lần fetch
    const urlObj = new URL(window.location.origin + url);
    currentPage = urlObj.searchParams.get("page") || 1;

    // Update URL trên trình duyệt
    const cleanUrl = url.replace("&ajax=1", "").replace("?ajax=1", "");
    window.history.pushState({}, "", cleanUrl);

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
   function reloadOrders(extra = {}) {
    let params = new URLSearchParams(new FormData(form));

    // Luôn giữ lại page đã lưu
    if (currentPage) {
        params.set("page", currentPage);
    }

    for (const [k, v] of Object.entries(extra)) {
        params.set(k, v);
    }

    fetchOrders(params.toString());
}

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
                5: { text: "Đã hủy", class: "danger" },
                6: {text:"Hoàn tiền",class:"warning"}
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
            if (statusCode === 6) {
                btnHtml += `<button class="btn btn-success btn-sm update-status" data-id="${row.dataset.id}" data-next="5">Hoàn tiền khách</button>`;
            }
           
            row.querySelector(".action-cell").innerHTML = btnHtml;
        });

        // Hủy đơn
        // Hủy đơn
document.querySelectorAll(".cancel_order").forEach(btn => {
    btn.addEventListener("click", function () {
        const row = this.closest("tr");
        const id = row.dataset.id;

        // Điền dữ liệu vào modal
        document.getElementById("modal-customer-name").textContent = row.dataset.customerName || "-";
        document.getElementById("modal-customer-phone").textContent = row.dataset.customerPhone || "-";
        document.getElementById("modal-receiver-name").textContent = row.dataset.receiverName || "-";
        document.getElementById("modal-receiver-phone").textContent = row.dataset.receiverPhone || "-";

        // Lưu id đơn hàng vào button confirm
        document.getElementById("confirmCancel").setAttribute("data-id", id);

        // Hiển thị modal
        new bootstrap.Modal(document.getElementById("cancelModal")).show();
    });
});

// Khi nhấn Xác nhận hủy trong modal
document.getElementById("confirmCancel").addEventListener("click", function () {
    const id = this.dataset.id;
    fetch(`/admin/orders/${id}/cancel`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => { 
        alert(data.message); 
        reloadOrders(); 
        bootstrap.Modal.getInstance(document.getElementById("cancelModal")).hide();
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
                    .then(data => { alert(data.message); reloadOrders(); });
                }
            });
        });
    }

    initActions();
});
</script>
@endsection
