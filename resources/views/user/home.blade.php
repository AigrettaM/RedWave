@extends('dashboardlayout.app')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@section('title', 'Dashboard - Your App')
@section('page-title', 'Dashboard')

@section('content')

@endsection
