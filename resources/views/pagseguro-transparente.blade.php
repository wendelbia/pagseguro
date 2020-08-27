<html>
    <head>
        <title>Checkout Transparente PagSeguro</title>
    </head>

    <body>
        {!! Form::open(['id' => 'form']) !!}
        
        {!! Form::close() !!}
        <a href="" class="btn-finished">Pagar com Boleto Bancário!</a>
        
        <div class="payments-methods"></div>
        

        <!--jQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        
        <!--URL PagSeguro Transparent
        <script src="{{config('pagseguro.url_payment_transparent_production')}}"></script>-->
        <script src="{{config('pagseguro.url_transparente_js_sandbox')}}"></script>

        
        <script>
            //inicio o jquery capturo o evento de click q entra no callback
            $(function(){
                $('.btn-finished').click(function(){
                    setSessionId();
                    
                    return false;
                });
            });
            
            function setSessionId()
            {
                //var recebe o id do form, o serialize pega os campos, por exemplo o token
                var data = $('#form').serialize();
                //faz a requisição ajax
                $.ajax({
                    url: "{{route('pagseguro.code.transparente')}}",
                    method: "POST",
                    data: data
                }).done(function(data){
                    //alert(data);
                //esse é o um método do pagseguro com o id da sessão
                    PagSeguroDirectPayment.setSessionId(data);
                    //o getPaymentMethods(); tem um array de informações, uso o looping do jquery para extrair, por enquanto não exibo os meios de pag, é opcional
                    //getPaymentMethods();
                    //chamo o pag em boleto
                    //getPaymentMethods();
                    
                    paymentBillet();
                }).fail(function(){
                   
                    alert("Fail request... :-(");
                });
            }
            //imprime os meios de pagamento
            function getPaymentMethods()
            {
                PagSeguroDirectPayment.getPaymentMethods({
                    //poderia passar um amount com o vl da comprar isso seria opcional
                    success: function(response){
                        console.log(response);
                        if( response.error == false ) {
                            $.each(response.paymentMethods, function(key, value){
                                $('.payments-methods').append(key+"<br>");
                        //response.paymentMethods
                        //o getPaymentMethods(); tem um array de informações, uso o looping do jquery para extrair
                        //com esse looping recuperamos a forma de pagamento
                            });
                        }
                    },
                    error: function(response){
                        console.log(response);
                    },
                    //qunado chega no complete finalizao o preload
                    complete: function(response){
                        //console.log(response);
                    }
                });
            }
            
            function paymentBillet()
            {
                //recupero a var sendHash, preciso chamar o metdo do pagseguro PagSeguro... e o getSender... q retorna o token da has
                var sendHash = PagSeguroDirectPayment.getSenderHash();
                //concateno a var sendHash q envio para lá para nosso controller através do request
                var data = $('#form').serialize()+"&sendHash="+sendHash;
                
                $.ajax({
                    url: "{{route('pagseguro.billet')}}",
                    method: "POST",
                    data: data
                    //url está retornando um link
                }).done(function(url){
                    //console.log(data);
                    //alert(data);
                    //esse é o um método do pagseguro com o id da sessão
                    //PagSeguroDirectPayment.setSessionId(data);
                    //o getPaymentMethods(); tem um array de informações, uso o looping do jquery para extrair
                    //getPaymentMethods();
                    //console.log(data);
                    //alert(data);
                    //vai para url para pagar o boleto
                    location.href=url;
                    //alert(data.paymentLink);
                }).fail(function(){
                    alert("Fail request... :-(");
                });
            }
        </script>

    </body>
</html>