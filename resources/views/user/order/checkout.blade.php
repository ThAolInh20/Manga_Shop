@extends('user.layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')

<order-checkout order-id="{{ $id }}" account_id="{{ $account_id }}"></order-checkout>

@endsection