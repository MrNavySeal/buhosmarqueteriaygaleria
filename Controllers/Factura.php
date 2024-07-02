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
                    $data['orderdetail'] = $this->getDetailHtml($idOrder);
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
        public function getDetailHtml($id){
            $request = $this->model->selectOrderDetail($id);
            $html="";
            $subtotal = 0;
            $arrRows = [];
            foreach ($request as $data) {
                $subtotalProduct =$data['quantity']*$data['price'];
                $subtotal+= $data['quantity']*$data['price'];
                $description="";
                if($data['topic'] == 1){
                    $detail = json_decode($data['description']);
                    $img ="";
                    if(isset($detail->type)){
                        $intWidth = floatval($detail->width);
                        $intHeight = floatval($detail->height);
                        $intMargin = floatval($detail->margin);
                        $colorFrame =  $detail->colorframe ? $detail->colorframe : "";
                        $material = $detail->material ? $detail->material : "";
                        $marginStyle = $detail->style == "Flotante" || $detail->style == "Flotante sin marco interno" ? "Fondo" : "Paspartú";
                        $borderStyle = $detail->style == "Flotante" ? "marco interno" : "bocel";
                        $glassStyle = $detail->idType == 4 ? "Bastidor" : "Tipo de vidrio";
                        $measureFrame = ($intWidth+($intMargin*2))."cm X ".($intHeight+($intMargin*2))."cm";
                        if($detail->photo !=""){
                            $img = '<a href="'.media().'/images/uploads/'.$detail->photo.'" target="_blank">Ver imagen</a><br>';
                        }
                        $description.='
                                '.$img.'
                                '.$detail->name.'
                                <ul>
                                    <li><span class="fw-bold t-color-3">Referencia: </span>'.$detail->reference.'</li>
                                    <li><span class="fw-bold t-color-3">Color del marco: </span>'.$colorFrame.'}</li>
                                    <li><span class="fw-bold t-color-3">Material: </span>'.$material.'</li>
                                    <li><span class="fw-bold t-color-3">Orientación: </span>'.$marginStyle.'</li>
                                    <li><span class="fw-bold t-color-3">Estilo de enmarcación: </span>'.$detail->style.'</li>
                                    <li><span class="fw-bold t-color-3">'.$marginStyle.': </span>'.$detail->margin.'cm</li>
                                    <li><span class="fw-bold t-color-3">Medida imagen: </span>'.$detail->width.'cm X '.$detail->height.'cm</li>
                                    <li><span class="fw-bold t-color-3">Medida marco: </span>'.$measureFrame.'</li>
                                    <li><span class="fw-bold t-color-3">Color del '.$marginStyle.': </span>'.$detail->colormargin.'</li>
                                    <li><span class="fw-bold t-color-3">Color del '.$borderStyle.': </span>'.$detail->colorborder.'</li>
                                    <li><span class="fw-bold t-color-3">'.$glassStyle.': </span>'.$detail->glass.'</li>
                                </ul>
                        ';
                    }else{
                        if($detail->img !="" && $detail->img !=null){
                            $img = '<a href="'.media().'/images/uploads/'.$detail->img.'" target="_blank">Ver imagen</a><br>';
                        }
                        $htmlDetail ="";
                        $arrDet = $detail->detail;
                        foreach ($arrDet as $d) {
                            $htmlDetail.='<li><span class="fw-bold t-color-3">'.$d->name.': </span>'.$d->value.'</li>';
                        }
                        $description = $img.$detail->name.'<ul>'.$htmlDetail.'</ul>';
                    }
                }else{
                    $description=$data['description'];
                    $flag = substr($data['description'], 0,1) == "{" ? true : false;
                    if($flag){
                        $arrData = json_decode($data['description'],true);
                        $name = $arrData['name'];
                        $varDetail = $arrData['detail'];
                        $textDetail ="";
                        foreach ($varDetail as $d) {
                            $textDetail .= '<li><span class="fw-bold t-color-3">'.$d['name'].':</span> '.$d['option'].'</li>';
                        }
                        $description = $name.'<ul>'.$textDetail.'</ul>';
                    }
                }
                $row = array(
                    "reference"=>$data['reference'],
                    "description"=>$description,
                    "price"=>formatNum($data['price'],false),
                    "qty"=>$data['quantity'],
                    "subtotal"=>formatNum($subtotalProduct,false)
                );
                $html.='<td class="text-start w10">'.$data['reference'].'</td>';
                $html.='<td class="text-start w55">'.$description.'</td>';
                $html.='
                    <td class="text-right w10">'.formatNum($data['price'],false).'</td>
                    <td class="text-right w10">'.$data['quantity'].'</td>
                    <td class="text-right w15">'.formatNum($subtotalProduct,false).'</td>
                ';
                array_push($arrRows,$row);
            }
            return array("rows"=>$arrRows,"subtotal"=>$subtotal);
        }
    }
?>