<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Cart;

class CheckQtyItemsCart
{
    
    public function handle($request, Closure $next)
    {

        $cart = new Cart;
        //temos esse me´todo implementado lá no Cart q retorna a quantidade de carrinho
        $cart->totalItems();
        if($cart->totalItems() < 1)
            return redirect()->back()->with('message', 'Não existe itens no carrinho!');
        //preciso registrar o middleware então vou em Kernel protected $routeMiddleware= [...]
        return $next($request);
    }
}
