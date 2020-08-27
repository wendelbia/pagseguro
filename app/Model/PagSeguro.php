<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as Guzzle;

class PagSeguro extends Model
{
    //
    public function generate ()
    {
    	$params = [

		    'email' => config('pagseguro.email'),
		    'token' => config('pagseguro.token'),
			'currency' => 'BRL',
			'itemId1' => '0001',
			'itemDescription1' => 'Notebook Prata',
			'itemAmount1' => '24300.00',
			'itemQuantity1' => '1',
			'itemWeight1' => '1000',
			'itemId2' => '0002',
			'itemDescription2' => 'Notebook Rosa',
			'itemAmount2' => '25600.00',
			'itemQuantity2' => '2',
			'itemWeight2' => '750',
			'reference' => 'REF1234',
			'senderName' => 'Jose Comprador',
			'senderAreaCode' => '11',
			'senderPhone' => '56273440',
			'senderEmail' => 'comprador@uol.com.br',
			'shippingType' => '1',
			'shippingAddressStreet' => 'Av. Brig. Faria Lima',
			'shippingAddressNumber' => '1384',
			'shippingAddressComplement' => '5o andar',
			'shippingAddressDistrict' => 'Jardim Paulistano',
			'shippingAddressPostalCode' => '01452002',
			'shippingAddressCity' => 'Sao Paulo',
			'shippingAddressState' => 'SP',
			'shippingAddressCountry' => 'BRA'

    	];
    	$params = http_build_query($params);
    	//dd($params);
    	//para confirmar link
    	//dd(config('pagseguro.url_checkout_sandbox'));
    	$guzzle = new Guzzle;
    	$response = $guzzle->request('POST', config('pagseguro.url_checkout_sandbox'), [
    		'query' => $params,
    		//'http_errors'
    	]);
    	//se quisesse pegar o status da requisição
    	//$response->getStatusCode();
    	//se quisesse pegar o header
    	$body = $response->getBody();
    	$contents = $body->getContents();
    	//dd($response);
    	//preciso converter para json
    	//dd($contents);
    	//para isso
    	$xml = simplexml_load_string($contents);
    	//dd($xml);
    	//e para recuperar para código, redireciona para a compra que é a url que está em config/pagseguro.php :
    	//dd($xml->code);
    	$code = $xml->code;
    	return $code; //isso retorna o código p o controller

    }

    public function getSessionId()
    {
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
        ];
        $params = http_build_query($params);
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_transparente_session_sandbox'), [
            'query' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        
        $xml = simplexml_load_string($contents);
        
        return $xml->id;
    }

   public function paymentBillet($sendHash)
    {
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
            //mudo para senderHash
            'senderHash' => $sendHash,
            'paymentMode' => 'default',
            'paymentMethod' => 'boleto',
            'currency' => 'BRL',
            'itemId1' => '0001',
            'itemDescription1' => 'Notebook Prata',
            'itemAmount1' => '24300.00',
            'itemQuantity1' => '1',
            'itemWeight1' => '1000',
            'itemId2' => '0002',
            'itemDescription2' => 'Notebook Rosa',
            'itemAmount2' => '25600.00',
            'itemQuantity2' => '2',
            'itemWeight2' => '750',
            'reference' => 'REF1234',
            'senderName' => 'Jose Comprador',
            'senderAreaCode' => '11',
            'senderPhone' => '56273440',
            //vou no comprador teste na pág do pagseguro:https://sandbox.pagseguro.uol.com.br/comprador-de-testes.html e copio esse email:
            //'senderEmail' => 'c45179611178859634334@sandbox.pagseguro.com.br',
            //'senderEmail' => 'xxxxxxxxxxx@sandbox.pagseguro.com.br',
            'senderEmail' => 'v93786787625683890498@sandbox.pagseguro.com.br',
            //acrescento o cpf
            'senderCPF'   => '82908788187',
            'shippingType' => '1',
            'shippingAddressStreet' => 'Av. Brig. Faria Lima',
            'shippingAddressNumber' => '1384',
            'shippingAddressComplement' => '5o andar',
            'shippingAddressDistrict' => 'Jardim Paulistano',
            'shippingAddressPostalCode' => '01452002',
            'shippingAddressCity' => 'Sao Paulo',
            'shippingAddressState' => 'SP',
            'shippingAddressCountry' => 'BRA'

        ];
        //$params = http_build_query($params);
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_payment_transparent_sandbox'), [
            'form_params' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        
        $xml = simplexml_load_string($contents);
        //$code = $xml->code;
        //return $code;
        //return $contents;
        //dd($xml);
        //é o link para onde o usu deve ser redirecionado
        return $xml->paymentLink;
    }

}

