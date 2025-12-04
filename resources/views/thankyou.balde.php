@extends('layouts.app')

@section('title','Merci')

@section('content')
<div class="untree_co-section py-5">
  <div class="container text-center">
    <span class="display-3 text-primary"><i class="fa fa-check-circle fa-4x"></i></span>
    <h2>Merci !</h2>
    <p>Votre commande a été effectuée avec succès.</p>
    <p><a href="{{ route('articles.index') }}" class="btn btn-outline-primary">Retour shopping</a></p>
  </div>
</div>
@endsection