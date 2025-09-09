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
            $arrData = curlConnectionGet("https://api.mercadopago.com/v1/payment_methods","application/json");
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        }
    }
?>