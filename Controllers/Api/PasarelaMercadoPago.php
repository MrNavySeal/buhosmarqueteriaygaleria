<?php
    use MercadoPago\Client\PaymentMethod\PaymentMethodClient;
    use MercadoPago\MercadoPagoConfig;
    class PasarelaMercadoPago extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
        }

        public function getPaymentMethods(){
            MercadoPagoConfig::setAccessToken(getCredentials()['secret']);
            $client = new PaymentMethodClient();
            $payment_methods = $client->list();
            echo json_encode($payment_methods,JSON_UNESCAPED_UNICODE);
        }
    }
?>