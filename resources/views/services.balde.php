@extends('layouts.app')

@section('title','Services')

@section('content')
<div class="py-4">
  <div class="container">
    <h2>Services</h2>
    <div class="row">
      <div class="col-md-3"><div class="feature"><img src="{{ asset('vendor/furni/images/truck.svg') }}" alt=""><h5>Fast Shipping</h5></div></div>
      <div class="col-md-3"><div class="feature"><img src="{{ asset('vendor/furni/images/bag.svg') }}" alt=""><h5>Easy to Shop</h5></div></div>
      <div class="col-md-3"><div class="feature"><img src="{{ asset('vendor/furni/images/support.svg') }}" alt=""><h5>Support</h5></div></div>
      <div class="col-md-3"><div class="feature"><img src="{{ asset('vendor/furni/images/return.svg') }}" alt=""><h5>Returns</h5></div></div>
    </div>
  </div>
</div>
@endsection