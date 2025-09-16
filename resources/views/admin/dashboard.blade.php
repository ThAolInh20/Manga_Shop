@extends('layouts.admin')

@section('title', 'Trang chủ admin')

@section('content')
<div class="container mt-4">
    Xin chào {{ Auth::user()->name }}
</div>
@endsection
