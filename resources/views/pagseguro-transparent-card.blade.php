<!DOCTYPE html>
<html>
<head>
	<title></title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">	
<!-- Latest compiled and minified JavaScript -->

</head>
<body>
	<div class="container">
		<h1>Pagar com Cartão</h1>
		<!--chamo a getBrand quando o form for submetido-->
		{!! Form::open(['id' => 'form']) !!}

		<div class="form-group">
			<label>Número do cartão</label>
			{!! Form::text('cardNumber', null, ['class' => 'form-control', 'Placeholder' => 'Número do cartão', 'required']) !!}
		</div>
		<div class="form-group">
			<label>Mês de expiração</label>
			{!! Form::text('cardExpiryMonth', null, ['class' => 'form-control', 'Placeholder' => 'Mês de expiração', 'required']) !!}
		</div>
		<div class="form-group">
			<label>Ano de expiração</label>
			{!! Form::text('cardExpiryYear', null, ['class' => 'form-control', 'Placeholder' => 'Ano de expiração', 'required']) !!}
		</div>
		<div class="form-group">
			<label>Código de Segurança (3 números atraz do cartão)</label>
			{!! Form::text('cardCVV', null, ['class' => 'form-control', 'Placeholder' => 'Código de Segurança', 'required']) !!}
		</div>
		<div class="form-group">
			{!! Form::hidden('cardName', null) !!}
			<!--podemos ter opcional esse campo parmazenar o token-->
			{!! Form::hidden('cardToken', null) !!}
			<button type="submit" class="btn btn-default btn-buy">Enviar Agora</button>
			
		</div>


		{!! Form::close() !!}
		<!--primeiro a ser carregado é a setSessionId e a getBrand demora um pouco então vamos fazer um preload-->
		<div class="preloader" style="display: none;">Preloader...</div>
		<div class="message" style="display: none;"></div>
	</div>

	<!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{config('pagseguro.url_transparente_js_sandbox')}}"></script>
        <script>
        	$(function () {
        		//uma vez q já setou o setSessionId, posso recuperar a marca do cartão, crio uma função para isso a getBrand, toda vez q faço uma requisição na pág ele faz uma chamda desse método para obter a sessão
        		setSessionId();
        		$('#form').submit(function () {
        			getBrand();
        			//quando submeter o cliente lá eu chamo o statPreloader
        			startPreloader("Enviando dados...");

        			return false;
        		});
        	});
        	//function recupera a sessão
        	function setSessionId()
            {
                var data = $('#form').serialize();
                $.ajax({
                    url: "{{route('pagseguro.code.transparente')}}",
                    method: "POST",
                    data: data,
                    //enquanto a requisição está sendo feita é chamdo o startPreloader, se deixo vásio o params aqui então vai mostar o valor do divi bnt-buy mas se quiser colocar uma mensagem usar o params
                    beforeSend: startPreloader("Carregando a página...Aguarde!")
                }).done(function(data){
                    PagSeguroDirectPayment.setSessionId(data);
                }).fail(function(){
                    alert("Fail request... :-(");
                    //depois de acabada a requisição é chamdo o endPreloader q também desabilita o botão
                }).always(function () {
                	endPreloader();
                });
            }
            //function q recupera a bandeira do cartão q é a marca do cartão
            function getBrand()
            {
            	//alert($('input[name=cardNumber').val().replace(/ /g, ''));
            	PagSeguroDirectPayment.getBrand({

            		//num do cartão eu o recupero pelo input do fomr e o val() é o valor dele, para evitar erro de espaço usu o replace()
            		cardBin: $('input[name=cardNumber').val().replace(/ /g, ''),
            		//passo as config aqui
            		success: function(response) {

            				console.log("Success getBrand");
            				console.log(response);
            				//para recuperar a logo do cartão e vou no form e crio um campo oculto para o nome do catão acima do button, pego esse valor (response.brand.name) e coloco naquele campo assim:
            				$("input[name=cardName]").val(response.brand.name);
            				createCredCardToken(response.brand.name);
            				
            		},
            		error: function(response) {

            				console.log("Error getBrand");
            				console.log(response);
            		},
            		//cai aqui tendo ou não erro
            		complete: function(response){

            				console.log("Success getBrand");
            				//console.log(response);
            		}
            	});
            }
            //function q gera o token do cartão
            //criando o token do cartão
            function createCredCardToken()
            {
            	//cham classe pagseguro
            	PagSeguroDirectPayment.createCardToken({
            		//envio o numero do cartão
            		cardNumber: $('input[name=cardNumber]').val().replace(/ /g, ''),
            		//bandeira do cart q eu pego lá em cima no form
            		brand: $('input[name=cardName]').val(),
            		cvv: $('input[name=cardCVV]').val(),
            		expirationMonth: $('input[name=cardExpiryMonth]').val(),
            		expirationYear: $('input[name=cardExpiryYear]').val(),
            		//aqui eu trato o retorno
            		success: function(response) {

            				console.log("Success createCardToken");
            				console.log(response);
            				//agora vamos recuperar o token
            				$("input[name=cardToken]").val(response.card.token);
            				//depois de realizado essa busca pelo token chamo o método para executar a transação em si, q inicia o precesso de pagamento
            				createTransactionCard();
            		},
            		error: function(response) {
            				console.log("Error createCardToken");
            				console.log(response);
            		},
            		complete: function(response){
            				console.log("Success createCardToken");
            				endPreloader();
            		}

            	});
            }

            function createTransactionCard()
            {	
            	var senderHash = PagSeguroDirectPayment.getSenderHash();
            	var data = $('#form').serialize()+"&senderHash="+senderHash;
                $.ajax({
                    url: "{{route('pagseguro.card.transaction')}}",
                    method: "POST",
                    data: data,
                    beforeSend: startPreloader("Realizando o pagamento com catão.")
                }).done(function(code){
                	//console.log(data);
                    //alert(data);
                    $(".message").html("Código da transação: "+code);
                    $(".message").show();
                }).fail(function(){
                    alert("Fail request... :-(");
                }).always(function () {
                	endPreloader();
                });
            }
            //se quise pode mandar uma mensagem usando um parâmetro
            function startPreloader(msgPreloader)
            {
            	if(msgPreloader != "")
            		$('.preloader').html(msgPreloader);

            	$('.preloader').show();
            	//quando disparar o preloader então vai esconder o botão desabilitando-o
            	$('.btn-buy').addClass('disabled');
            }

            function endPreloader()
            {
            	$('.preloader').hide();
            	//aqui eu removo a desabilitação
            	$('.btn-buy').removeClass('disabled');
            }
            /*
            	var senderHash = PagSeguroDirectPayment.getSenderHash();
            	//aqui pego todos os dados q vem do formulário
            	var data = $('#form').serialize()+"&senderHash="+senderHash;
                $.ajax({
                	//passando o nome da rota ou url é a mesma coisa
                    url: "{{route('pagseguro.card.transaction')}}",
                    method: "POST",
                    data: data,
                    beforeSend: startPreloader("Realizando o pagamento com o cartão.")
                }).done(function(data){
                	$(".message").html("Código da transação: "+code);
                	$(".message").show();
                    //PagSeguroDirectPayment.setSessionId(data);
                    //dou um alert na var data ou um console
                    console.log(data);
                    alert(data);
                }).fail(function(){
                    alert("Fail request... :-(");
                }).always(function () {
                	endPreloader();
                });*/
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>