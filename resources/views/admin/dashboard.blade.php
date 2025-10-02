@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div id="print-area">
<div class="row g-3"> <!-- g-3 = khoảng cách giữa các col -->
    <!-- Bên trái -->
    <div class="col-md-6">
        <div class="card h-100">
            <h4 class="mb-3 text-primary p-3">📊 Thống kê nhanh</h4>
            <table class="table table-bordered table-striped mb-0">
                <tbody>
                    <tr>
                        <th class="w-50"><a href="{{ route('orders.index') }}">Tổng số đơn cần xử lý</a></th>
                        <td>
                            <span  class="text-danger fw-bold">
                                {{ $pendingOrders ?? 0 }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><a href="{{ route('accounts.index') }}">Tài khoản yêu cầu xóa</a></th>
                        <td>
                            <span  class="text-warning fw-bold">
                                {{ $inactiveAccounts ?? 0 }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tài khoản mới</th>
                        <td class="text-success fw-bold">{{ $newAccounts ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th><a href="{{ route('products.index') }}">Sản phẩm sắp hết hàng</a></th>
                        <td class="text-danger fw-bold">{{ $lowStockProducts ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bên phải -->
    <div class="col-md-6">
        <div class="card h-100">
            <h4 class="mb-3 text-success p-3">🏆 Top hoạt động</h4>
            <table class="table table-bordered table-striped mb-0">
                <tbody>
                    <tr>
                        <th class="w-50">Danh mục có sản phẩm mua nhiều nhất</th>
                        <td>
                            @if($topCategory?->category)
                                <a href="{{ route('categories.show', $topCategory->category_id) }}" class="fw-bold text-primary">
                                    {{ $topCategory->category->name }}
                                </a>
                                (ID: {{ $topCategory->category_id }})
                            @else
                                Chưa có
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Có đơn hoàn thành nhiều nhất</th>
                        <td>
                            @if($topAccountByOrders)
                                <a href="{{ route('accounts.show', $topAccountByOrders->id) }}" class="fw-bold text-primary">
                                    {{ $topAccountByOrders->name }}
                                </a>
                                (ID: {{ $topAccountByOrders->id }})
                            @else
                                Chưa có
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Có tổng tiền mua hàng cao nhất</th>
                        <td>
                            @if($topAccountByRevenue)
                                <a href="{{ route('accounts.show', $topAccountByRevenue->id) }}" class="fw-bold text-primary">
                                    {{ $topAccountByRevenue->name }}
                                </a>
                                (ID: {{ $topAccountByRevenue->id }})
                            @else
                                Chưa có
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Chart -->
<div class="row mt-3">
    <div class="col-12">
        @include('admin.orders.chart')
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        @include('admin.suppliers.chart')
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        @include('admin.accounts.chart')
    </div>
</div>
</div>
</div>
<button onclick="window.print()">🖨 In / Lưu PDF</button>
 <!-- <button onclick="downloadPDF()">📥 Xuất PDF</button> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



<style>
@media print {
  body {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    font-family: DejaVu Sans, sans-serif;
    font-size: 12pt;
    margin: 0;
    padding: 0;
  }

  /* Ẩn tất cả ngoài vùng in */
  body * {
    visibility: hidden;
  }
  #print-area, #print-area * {
    visibility: visible;
  }
  #print-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    margin: 0;
    padding: 0;
  }

  /* Ẩn riêng những thứ không cần */
  .navbar,
  .no-print,
  .print-hide {
    display: none !important;
  }

  /* Giữ lại button lọc */
  .filter-btn {
    display: inline-block !important;
    visibility: visible !important;
    border: 1px solid #333 !important;
    padding: 4px 8px !important;
    font-size: 11pt !important;
  }

  /* Card in gọn */
  .card {
    box-shadow: none !important;
    border: 1px solid #ccc !important;
    margin-bottom: 12px;
    page-break-inside: avoid;
  }

  /* Bảng rõ ràng */
  table {
    border-collapse: collapse;
    width: 100% !important;
  }
  th, td {
    border: 1px solid #000 !important;
    padding: 6px;
  }
  th {
    background: #f3f4f6 !important;
  }

  /* Chart fit gọn */
  canvas {
    max-width: 100% !important;
    height: auto !important;
  }

  /* Nếu muốn chart mỗi trang riêng */
  .chart {
    page-break-before: always;
  }
}

</style>

@endsection
