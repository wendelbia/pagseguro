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
        
        <!--URL PagSeguro Transparent-->
        <script src="{{config('pagseguro.url_transparente_js_sandbox')}}"></script>
        
        <script>
    $(function(){
        $('.btn-finished').click(function(){
            setSessionId();
            
            $(".preloader").show();
            
            return false;
        });
    });
    
    function setSessionId()
    {
        var data = $('#form').serialize();
        $.ajax({
            url: "{{route('pagseguro.code.transparent')}}",
            method: "POST",
            data: data
        }).done(function(data){
            PagSeguroDirectPayment.setSessionId(data);
            paymentBillet();
        }).fail(function(){
            alert("Fail request... :-(");
        });
    }

    function getPaymentMethods()
    {
        PagSeguroDirectPayment.getPaymentMethods({
            success: function(response){
                console.log(response);
                if (response.error == false) {
                    $each(response.paymentMethods, function(key, value){
                        $('.payments-methods').append(key+"<br>");
                    });
                }
            },
            error: function(response){
                console.log(response);
            },
            complete: function(response){

            }
        });
    }
    
    function paymentBillet()
    {
        var sendHash = PagSeguroDirectPayment.getSenderHash();

        var data = $('#form').serialize()+"&sendHash="+sendHash;


        $.ajax({
            url: "{{route('pagseguro.billet')}}",
            method: "POST",
            data: data
        }).done(function(url){
            console.log(url);
            
            location.href=url;
        }).fail(function(){
            alert("Fail request... :-(");
        });
    }        
</script>
    </body>
</html>
