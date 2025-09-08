@extends('user.layouts.app')
@section('title', 'Danh sách sản phẩm')

@section('content')

@if($search)
        <all-products-search :search="'{{ $search }}'"></all-products-search>
    @else
        <all-products></all-products>
    @endif


@endsection
