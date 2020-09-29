@extends('store.layouts.main')

@section('content')
<section class="products">
	<h1 class="title">Lançamentos</h1>
	@forelse($products as $product)
	<article class="product col-md-3 col-sm-6 col-xs-12">
		<img src="{{url("assets/imgs/temp/{$product->image}")}}" alt="">
		<h2>{{$product->name}}</h2>
		<a href="{{ route('add.cart', $product->id)}}">Adicionar no Carrinho  <i class="fa fa-cart-plus" aria-hidden="true"></i></a>
	</article>
<!--caso ñ encotre nenhum valor-->
	@empty
	<p>Não tem produto cadastrado</p>
	@endforelse
</section>

@endsection