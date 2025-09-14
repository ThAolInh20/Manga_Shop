@extends('user.layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')

<order-detail order-id="{{ $id }}"></order-detail>

@endsection