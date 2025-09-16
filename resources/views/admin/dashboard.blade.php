@extends('layouts.admin')

@section('title', 'Thống kê đơn hàng')

@section('content')
<div class="row">
  
        @include('admin.chart.chartorder')
    

</div>

@endsection
