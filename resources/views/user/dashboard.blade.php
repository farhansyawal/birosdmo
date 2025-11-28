@extends('user.layouts.app')
@section('title', 'Halaman Dashboard')
@section('content')

<h6>Selamat datang {{ Auth::user()->unit_kerja }}</h6>

@endsection