<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Cart;
use Session;

class StoreController extends Controller
{
    //para ñ fazer mais $product = new Product;
    //posso chamar como parmas a model Porduct e uma var do tipo product -> $product
    public function index(Product $product)
    {
    	//chamo todos os products
    	$products = $product->all();
    	return view('store.home.index', compact('products'));
    }

    public function cart()
    {
    	$title = 'Meu Carrinho de Compras!';
        //uso o get para recuperar e também poderia usar o has para ver se existe, com isso recupera mas com o getItems() ainda não , para isso preciso atualizar no Cart.php
        //dd(Session::get('cart'));

        $cart = new Cart;
        //aparecerá vazio o array, para isso precisamos ir no CartController e adicionar uma sessão para q salva essa informação
        //dd($cart->getItems());
        //dd($cart->total());
        //dd($cart->totalItems());
        //para exibir os produtos dinamicamente
        $products = $cart->getItems();
        //dd($products);

    	return view('store.cart.cart', compact('title', 'cart', 'products'));
    }

    public function methodPayment()
    {
        $title = 'Escolha o metodo de pagamento';
        
        return view('store.cart.method-payment', compact('title'));
    }
}
