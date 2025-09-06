@extends('user.layouts.app')
@section('title', 'Danh sách yêu thích')

@section('content')

<div class="container mt-4">
    <h2>Danh sách yêu thích</h2>
    @if($wishlistItems->isEmpty())
        <p>Danh sách yêu thích của bạn đang trống.</p>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($wishlistItems as $item)
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $item->product->images) }}" class="card-img-top" alt="{{ $item->product->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title
@endsection
