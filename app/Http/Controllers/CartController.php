<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Cart;
use Session;

class CartController extends Controller
{
    //
    public function add($id)
    {
    	$product = Product::find($id);
    	if(!$product)
    		return redirect()->back();
    	//dd($product);
    	$cart = new Cart;
    	$cart->add($product);

    	//depois de adicionar os items no carrinho eu vejo quais são eles através do método getItems
    	//dd($cart->getItems());

    	//para criar uma sessão chamao a classe Session e uso o método put e passo dois parâmetros um é o nome da sessão e o outro o objeto da model 
    	Session::put('cart', $cart);
    	return redirect()->route('cart');
    }

    public function remove($id)
    {
    	$product = Product::find($id);
    	if(!$product)
    		return redirect()->back();
    	//dd($product);
    	$cart = new Cart;
    	$cart->remove($product);

    	Session::put('cart', $cart);
    	return redirect()->route('cart');
    }
}
