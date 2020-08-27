<!DOCTYPE html>
<html>
<head>
	<title>PagSeguro LightBox</title>
</head>
<body>
	<!--vou no site do jquery para baixar-->
	

	<a href="#" class="btn-buy">Finalizar Compra</a>
 <!--executa o html que gera um campo com nome de token-->
 	{!! csrf_field() !!}	

 	<div class="msg-return"></div>

 	<div class="preloader" style="display: none;">Carregando...</div>

	<!--jQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<script>
		$(function(){
			$('.btn-buy').click(function(){

				//para capturar o token ele tem q capturar um campo (input) cujo o nome seja token
				var token = $('input[name=_token]').val();
				//alert(token);

				$.ajax({
					url: "{{route('pagseguro.lightbox.code')}}",
					method: "POST",
					//passa uma propriedade com o campo chamado token
					data: {_token: $('input[name=_token]').val()},
					//enquanto estiver requisitando a url: "..." vai ficar rodando o preloader através do beforeSend
					beforeSend: startPreloader()
					//se der certo então chamo o done q passa o code
				}).done(function(code) {
					//método do pagseguro q vai receber o token, mas ñ vou usar, vou criar uma funçõa
					//PagSeguroLightbox(code);
					lightbox(code);
				//caso algo dê errado ou a internet falhar	
				}).fail(function() {
					alert("Erro inesperado, tente novamente!");
				//sempe q a requisição acabar dando falha ou não cairá nesse método, não uso complete pq is deprecated então uso always
				}).always(function() {
					stopPreloader();
				});
				return false;
			});
		});
		function lightbox(code)
		//alguns navegadores não dam suporte para o lightbox então para isso declaro uma var que receberá esse método
		{
			var isOpenLightbox = PagSeguroLightbox({
				code: code
			}, {
				success: function(transactionCode){
					$('.msg-return').html ("Pedido realizadco com sucesso: "+transactionCode);
				},
				abort: function(){
					alert("Compra cancelada");
				}
			});
			//faço o if q nos redirecionamos
			if( !isOpenLightbox ) {
                location.href="{{config('pagseguro.url_redirect_after_request')}}"+code;
			}
		}
		//mostra a div de carregamento
		function startPreloader()
        {
            $('.preloader').show();
        }
		//para a div de carregamento
		function stopPreloader()
        {
            $('.preloader').hide();
        }
	</script>
<!---->
	<script src="{{config('pagseguro.url_lightbox_sandbox')}}"></script>
</body>
</html>