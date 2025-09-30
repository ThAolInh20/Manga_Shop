@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
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



@endsection
