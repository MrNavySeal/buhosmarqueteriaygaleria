<?php
    class HelperGeneral{
        const RELACION_PAGO = [
            "1"=>["id"=>1,"nombre"=>"Cartera"],
            "2"=>["id"=>2,"nombre"=>"Proveedores"],
            "3"=>["id"=>3,"nombre"=>"Cartera/Proveedores"],
        ];

        const TIPO_PAGO = [
            "1"=>["id"=>1,"nombre"=>"Efectivo"],
            "2"=>["id"=>2,"nombre"=>"Transferencia bancaria"],
            "3"=>["id"=>3,"nombre"=>"Tarjetas"],
            "4"=>["id"=>4,"nombre"=>"Crédito"],
            "5"=>["id"=>5,"nombre"=>"Pagos en línea"],
        ];
    }
?>