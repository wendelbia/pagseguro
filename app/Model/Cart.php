<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Product;
use Session;


class Cart extends Model
{
    //
    private $items = [];

    //para que seja atualizado no carrinho quando adicionardo um produto eu uso aqui o método construtor
	public function __construct()
	{
		//se existe produto no carrinho eu armazeno lá em cima no array $items
		if (Session::has('cart')) {
			//dd(Session::get('cart'));
			$cart = Session::get('cart');

			$this->items = $cart->items;
		}

	}

    public function add(Product $product)
    {
    	
    	/*
    	dd($product);
    	[
    		2 => ['item' => $product, 'qtd' => 2],
    	]
    	*/

    	/* aqui vamos debugar para ver se existe o produto no carrinho, se existe incrementarei mais um*/
    	if (isset($this->items[$product->id])) 
    			//dd('existe');
    			$this->items[$product->id] = [
		    		'item' => $product,
		    		//se existir o produto no carrinho então adiciono mais 1
		    		'qtd' => $this->items[$product->id]['qtd'] +1,
		    	];
    		else
    			//dd('n existe');
		    	//var recebe o array de product->id, com item q é o product e a quantidade q é 1
		    	$this->items[$product->id] = [
		    		'item' => $product,
		    		'qtd' => 1,
		    	];
    }

    public function remove(Product $product)
    {
    	if (isset($this->items[$product->id]) && $this->items[$product->id]['qtd'] > 1)
    			$this->items[$product->id] = [
		    		'item' => $product,
		    		//se existir o produto no carrinho então diminue mais 1
		    		'qtd' => $this->items[$product->id]['qtd'] -1,
		    	];
		elseif ( isset($this->items[$product->id]))
			unset($this->items[$product->id]);
    }

    public function getItems()
    {
    	return $this->items;
    }
    //pega todos os item e soma total e subtotal
    public function total()
    {
    	$total = 0;
    	foreach ($this->items as $item) {
    		//vai pegar o valor o preço
    		//dd($item['item']->price);
    		//vai pegar a quantidade
    		//dd($item['qtd']);
    		//pegamos o subtotal
    		$subTotal = $item['item']->price * $item['qtd'];
    		$total += $subTotal;
    	}
    	return number_format($total,2,'.','');
    }

    public function totalItems()
    {
    	return count($this->items);
    }

    //limpar a sessão do carrinho
    public function emptyCart()
    {
    	//se a sessão existir
    	if (Session::has('cart')) {
    		//então apague
    		Session::forget('cart');
    	}
    }
}
