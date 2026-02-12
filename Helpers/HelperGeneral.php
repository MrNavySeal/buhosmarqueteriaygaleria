<?php
    class HelperGeneral{
        const RELACION_PAGO = [
            "1"=>["id"=>1,"nombre"=>"Cartera"],
            "2"=>["id"=>2,"nombre"=>"Proveedores"],
            "3"=>["id"=>3,"nombre"=>"Cartera/Proveedores"],
        ];

        const TIPO_PAGO = [
            "1"=>["id"=>1,"nombre"=>"Efectivo","checked"=>false],
            "2"=>["id"=>2,"nombre"=>"Transferencia bancaria","checked"=>false],
            "3"=>["id"=>3,"nombre"=>"Tarjetas","checked"=>false],
            "4"=>["id"=>4,"nombre"=>"Crédito","checked"=>false],
            "5"=>["id"=>5,"nombre"=>"Pagos en línea","checked"=>false],
            "6"=>["id"=>6,"nombre"=>"Otros","checked"=>false],
        ];

        public static function getMetodosPago(){
            $con = new Mysql();
            $arrTipos = [];
            foreach (HelperGeneral::TIPO_PAGO as $tipo) {
                $sql = "SELECT * FROM payment_type WHERE status = 1 AND type = $tipo[id]";
                $tipo['formas'] = $con->select_all($sql);
                $arrTipos[] = $tipo;
            }
            return $arrTipos;
        }
    }
?>