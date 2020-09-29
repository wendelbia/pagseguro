<?php  

namespace App\Model;
use GuzzleHttp\Client as Guzzle;

trait PagSeguroTrait
{

    public function getConfigs()
    {
        return [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
        ];
    }
    
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    //pego os dados do carrinho e crio um array dinamicamente
    public function getItems()
    {
        //var pra ter todos os itens
        $items = [];
        //retorna os items do carrinho
        $itemsCart = $this->cart->getItems();
        //preciso usar o essa var para que faço o loop e não traga apenas a primeira infomaçção, seguindo assim os padrões do pagseguro
        $posistion = 1;
        
        foreach ($itemsCart as $item) {
            //para recuperar o id do item, faço $items na posição recebe o item id
            $items["itemId{$posistion}"]            = $item['item']->id;
            $items["itemDescription{$posistion}"]   = $item['item']->description;
            //pra ñ gerar erro de vírgula coloco entro aspas
            $items["itemAmount{$posistion}"]        = "{$item['item']->price}0";
            $items["itemQuantity{$posistion}"]      = $item['qtd'];
            
            $posistion++;
        }

        return $items;
        /*
        
        return [
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
        ];
         */
    }
    
    
    public function getSender()
    {
        return [
            'senderName'        => $this->user->name,
            'senderAreaCode'    => $this->user->area_code,
            'senderPhone'       => $this->user->phone,
            'senderEmail'       => $this->user->email,
            'senderCPF'         => $this->user->cpf,
        ];
    }
    
    public function getShipping()
    {
        return [
            'shippingType'                  => '1',
            'shippingAddressStreet'         => $this->user->street,
            'shippingAddressNumber'         => $this->user->number,
            'shippingAddressComplement'     => $this->user->complement,
            'shippingAddressDistrict'       => $this->user->district,
            'shippingAddressPostalCode'     => $this->user->postal_code,
            'shippingAddressCity'           => $this->user->city,
            'shippingAddressState'          => $this->user->state,
            'shippingAddressCountry'        => $this->user->country,
        ];
    }

/*
    public function getCreditCard($holderName)
    {
        return [
            'creditCardHolderName'      => $holderName,
            'creditCardHolderCPF'       => $this->user->cpf,
            'creditCardHolderBirthDate' => '01/01/1900',
            'creditCardHolderAreaCode'  => '99',
            'creditCardHolderPhone'     => '99999999',
        ];
    }


    public function billingAddress()
    {
        return [
            'billingAddressStreet'      => $this->user->street,
            'billingAddressNumber'      => $this->user->number,
            'billingAddressComplement'  => $this->user->complement,
            'billingAddressDistrict'    => $this->user->district,
            'billingAddressPostalCode'  => $this->user->postal_code,
            'billingAddressCity'        => $this->user->city,
            'billingAddressState'       => $this->user->state,
            'billingAddressCountry'     => 'BRL',
        ];
    }
*/
}