@extends('lyra::master')

@section('content')
  <lyra-loader v-if="loader"></lyra-loader>
  <router-view :key="$route.path"></router-view>
@endsection