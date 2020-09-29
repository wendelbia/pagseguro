<?php

Auth::routes();

$this->group(['middleware' => 'auth'], function(){
	//cart
	$this->get('meio-pagamento', 'StoreController@methodPayment')
            ->middleware('check.qty.cart')
            ->name('method.payment');
	//daqui vamos no .env e modificar as vars do pagseguro para aplicar a lógica e depois vamos no pagseguro.php e modificamo
	$this->post('pagseguro-getcode', 'PagSeguroController@getCode')->name('pagseguro.code.transparent');
    $this->post('pagseguro-payment-billet', 'PagSeguroController@billet')->name('pagseguro.billet');





	//perfil
	$this->get('meu-perfil', 'UserController@profile')->name('profile');
	$this->get('logout', 'UserController@logout')->name('logout');
	$this->post('atualizar-perfil', 'UserController@profileUpdate')->name('profile.update');
	 $this->post('atualizar-senha', 'UserController@passwordUpdate')->name('password.update');
	 $this->get('minha-senha', 'UserController@password')->name('password');
});



$this->get('remove-cart/{id}', 'CartController@remove')->name('remove.cart');
$this->get('add-cart/{id}', 'CartController@add')->name('add.cart');

//rota de carrinho 
$this->get('carrinho', 'StoreController@cart')->name('cart');

$this->get('/', 'StoreController@index')->name('home');


/*
$this->post('pagseguro-transparent-card', 'PagSeguroController@cardTransaction')->name('pagseguro.card.transaction');
//pag com cartão
$this->get('pagseguro-transparent-card', 'PagSeguroController@card')->name('pagseguro.transparent.card');

$this->post('pagseguro-billet', 'PagSeguroController@billet')->name('pagseguro.billet');

$this->post('pagseguro-transparente', 'PagSeguroController@getCode')->name('pagseguro.code.transparente');
$this->get('pagseguro-transparente', 'PagSeguroController@transparente')->name('pagseguro.transparente');


$this->get('pagseguro-btn', function() {
	return view('pagseguro-btn');
});
//faço uma blade pagseguro-btn
//rota para teste, a url é pagseguro, chamada do método, e o nome da rota é pagseruro
$this->get('pagseguro', 'PagSeguroController@pagseguro')->name('pagseguro');

$this->get('pagseguro-lightbox', 'PagSeguroController@lightbox')->name('pagseguro.lightbox');

$this->post('pagseguro-lightbox', 'PagSeguroController@lightboxCode')->name('pagseguro.lightbox.code');

Route::get('/', function () {
    return view('welcome');
});
*/
?> 

