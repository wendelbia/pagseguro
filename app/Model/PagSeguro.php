<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as Guzzle;

/* Exceptions
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Throwable;
*/
class PagSeguro extends Model
{
    use PagSeguroTrait;
    //a var $cart tem todos os items e métodos da Model cart
    private $cart, $reference, $user;
    private $currency = 'BRL';

//construtor q vai receber os métodos da model de Cart
//o laravel injecta automaticamente um objeto da Model Cart na variável $cart
    public function __construct(Cart $cart)
    {
        //recebe o objeto
        $this->cart = $cart;
        //identificador para transação tanto no sistema quanto no pgseguro é um nível de identificação a mais aqui gera um vl unico 
        $this->reference = uniqid(date('YmdHis'));
        
        $this->user = auth()->user();
    }

    public function getSessionId()
    { 
        $params = $this->getConfigs();
        $params = http_build_query($params);
       /*poderia usar assim também
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
        ];*/
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_transparent_session'), [
            'query' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        
        $xml = simplexml_load_string($contents);
        
        return $xml->id;
    }
    //function q realmente vai gerar o pedido
    public function paymentBillet($sendHash)
    {
        $params = [
            'senderHash' => $sendHash,
            'paymentMode' => 'default',
            'paymentMethod' => 'boleto',
            'currency' => $this->currency,
            'reference' => $this->reference,
        ];
        //faço a junção dos array aqui com as funções da Trait
        //$params = http_build_query($params);
        $params = array_merge($params, $this->getConfigs());
        $params = array_merge($params, $this->getItems());
        $params = array_merge($params, $this->getSender());
        $params = array_merge($params, $this->getShipping());
        
        $guzzle = new Guzzle;
            //como modificado no aquivo pagseguro.php fica url_payment_transparent
            $response = $guzzle->request('POST', config('pagseguro.url_payment_transparent'), [
            'form_params' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        
        $xml = simplexml_load_string($contents);
        
        /*return $xml->paymentLink;*/
        return [
            'success'       => true,
            'payment_link'  => (string)$xml->paymentLink,
            'reference'     => $this->reference,
            'code'          => (string)$xml->code,
        ];
    }
    public function paymentCredCard($request)
    {
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
            'senderHash' => $request->senderHash,
            'paymentMode' => 'default',
            'paymentMethod' => 'boleto',
            'currency' => 'BRL',
            'itemId1' => '0001',
            'itemDescription1' => 'Produto PagSeguroI',
            'itemAmount1' => '99999.99',
            'itemQuantity1' => '1',
            'itemWeight1' => '1000',
            'itemId2' => '0002',
            'itemDescription2' => 'Produto PagSeguroII',
            'itemAmount2' => '99999.98',
            'itemQuantity2' => '2',
            'itemWeight2' => '750',
            'reference' => 'REF1234',
            'senderName' => 'Jose Comprador',
            'senderAreaCode' => '99',
            'senderPhone' => '99999999',
            'senderEmail' => 'c45179611178859634334@sandbox.pagseguro.com.br',
            'senderCPF' => '82908788187',
            'shippingType' => '1',
            'shippingAddressStreet' => 'Av. PagSeguro',
            'shippingAddressNumber' => '9999',
            'shippingAddressComplement' => '99o andar',
            'shippingAddressDistrict' => 'Jardim Internet',
            'shippingAddressPostalCode' => '99999999',
            'shippingAddressCity' => 'Cidade Exemplo',
            'shippingAddressState' => 'SP',
            'shippingAddressCountry' => 'ATA',
            'creditCardToken'=>$request->cardToken,
            'installmentQuantity'=>1,
            'installmentValue'=>300021.45,
            'noInterestInstallmentQuantity'=>2,
            'creditCardHolderName'=>'Jose Comprador',
            'creditCardHolderCPF'=>'82908788187',
            'creditCardHolderBirthDate'=>'01/01/1900',
            'creditCardHolderAreaCode'=>99,
            'creditCardHolderPhone'=>99999999,
            'billingAddressStreet'=>'Av. PagSeguro',
            'billingAddressNumber'=>9999,
            'billingAddressComplement'=>'99o andar',
            'billingAddressDistrict'=>'Jardim Internet',
            'billingAddressPostalCode'=>99999999,
            'billingAddressCity'=>'Cidade Exemplo',
            'billingAddressState'=>'SP',
            'billingAddressCountry'=>'ATA',
        ];
        //$params = http_build_query($params);
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_payment_transparent_sandbox'), [
            'form_params' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        
        $xml = simplexml_load_string($contents);
        
        return $xml->code;
    }
}


