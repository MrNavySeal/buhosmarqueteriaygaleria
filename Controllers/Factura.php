<?php
    require 'Libraries/html2pdf/vendor/autoload.php';
    use Spipu\Html2Pdf\Html2Pdf;
    class Factura extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            getPermits(6);
        }
        
        public function generarFactura($idOrder){
            if($_SESSION['permitsModule']['r']){
                if(is_numeric($idOrder)){
                    $idPerson ="";
                    if($_SESSION['userData']['roleid'] == 2 ){
                        $idPerson= $_SESSION['idUser'];
                    }
                    $data['orderdata'] = $this->model->selectOrder($idOrder,$idPerson);
                    $data['orderdetail'] = $this->model->selectOrderDetail($idOrder);
                    if($data['orderdata']['coupon']!=""){
                        $data['cupon'] = $this->model->selectCouponCode($data['orderdata']['coupon']);
                    }
                    $data['company'] = getCompanyInfo();
                    $title = $data['orderdata']['idorder'].$data['orderdata']['idtransaction'];
                    ob_end_clean();
                    $html = getFile("Template/Modal/comprobantePdf",$data);
                    $pdf = new Html2Pdf();
                    $pdf->writeHTML($html);
                    $pdf->output("{$title}.pdf");
                }else{
                    header("location: ".base_url()."/pedidos");
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
    }
?>