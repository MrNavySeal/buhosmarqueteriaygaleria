<?php
    use MercadoPago\Client\Payment\PaymentClient;
    use MercadoPago\Client\Common\RequestOptions;
    use MercadoPago\MercadoPagoConfig;
    class PasarelaMercadoPago extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
        }

        public function getPaymentMethods(){
            $arrPayments = curlConnectionGet("https://api.mercadopago.com/v1/payment_methods","application/json");
            $arrData = array(
                "pse" => array_values(array_filter($arrPayments,function($e){return $e->id =="pse";}))[0]
            );
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        }
    }
?>