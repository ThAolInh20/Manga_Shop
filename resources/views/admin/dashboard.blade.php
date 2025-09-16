@extends('layouts.admin')

@section('title', 'Thống kê đơn hàng')

@section('content')
<div class="row">
    <div class="col-md-6">
        @include('admin.chart.chartorder')
    </div>
    <div class="col-md-6">
        @include('admin.chart.chartorder')
    </div>

</div>

@endsection
