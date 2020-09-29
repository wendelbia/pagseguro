<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PagSeguro;

class PagSeguroController extends Controller
{
    //
    public function pagseguro (PagSeguro $pagseguro) 
    {
    	//para confirmar link
    	//dd($pagseguro->generate());
    	$code = $pagseguro->generate();
    	$urlRedirect = config('pagseguro.url_redirect_after_request').$code;
    	return redirect()->away($urlRedirect);
    }

    public function lightbox()
    {
    	//ctrl+u
    	return view('pagseguro-lightbox');
    }

    public function lightboxCode(PagSeguro $pagseguro)
    {
    	return $pagseguro->generate();
    }

    public function transparente()
    {
        return view('pagseguro-transparente');
    }
    //requisição para passa o email e token para receber um código, crio um método na model
    public function getCode(PagSeguro $pagseguro)
    {
        return $pagseguro->getSessionId();
    }

    public function billet(Request $request, PagSeguro $pagseguro)
    {
    	//dd($request->get('sendHash'));
    	//vou na model recupero a hash
    	return $pagseguro->paymentBillet($request->sendHash);
    }

    public function card()
    {
    	return view('pagseguro-transparent-card');
    }

//
    public function cardTransaction(Request $request, PagSeguro $pagseguro)
    {
    	return $pagseguro->paymentCredCard($request);
    	//return $request->all();
    	//return $pagseguro->paymentCredCard($request);
    }
}
