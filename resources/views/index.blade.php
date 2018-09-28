@extends('lyra::master')

@section('content')
Welcome back {{ Auth::user()->name }}

{{--@php dd(auth()->user()->role->permissions) @endphp--}}
@endsection