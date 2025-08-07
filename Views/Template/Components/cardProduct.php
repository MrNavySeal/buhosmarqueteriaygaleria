<?php
    $producto = $data;
    $id = openssl_encrypt($producto['idproduct'],METHOD,KEY);
    $discount = "";
    $resultDiscount = "";
    if($producto['discount'] > 0){
        $resultDiscount = floor((1-($producto['discount']/$producto['price']))*100);
    }
    $reference = $producto['reference']!="" ? "REF: ".$producto['reference'] : "";
    $variant = $producto['product_type'] ? "Desde " : "";
    $price ='</span><span class="current">'.$variant.formatNum($producto['price']).'</span>';
    $favorite="";
    if($producto['favorite']== 0){
        $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav "><i class="far fa-heart "></i></button>';
    }else{
        $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav active"><i class="fas fa-heart text-danger "></i></button>';
    }
    if($producto['is_stock']){
        if($producto['discount'] > 0 && $producto['stock'] > 0){
            $discount = '<span class="discount">-'.$resultDiscount.'%</span>';
            $price ='<span class="current sale me-2">'.$variant.formatNum($producto['discount'],false).'</span><span class="compare">'.formatNum($producto['price']).'</span>';
        }else if($producto['stock'] <= 0){
            $price = '<span class="current sale me-2">Agotado</span>';
            $discount="";
        }
    }else{
        if($producto['discount'] > 0){
            $discount = '<span class="discount">-'.$resultDiscount.'%</span>';
            $price ='<span class="current sale me-2">'.$variant.formatNum($producto['discount'],false).'</span><span class="compare">'.formatNum($producto['price']).'</span>';
        }
    }
?>
<div class="card--product">
    <div class="card--product-img">
        <a href="<?=base_url()."/tienda/producto/".$producto['route']?>">
            <?=$discount?>
            <img src="<?=$producto['url']?>" alt="<?=$producto['category']." ".$producto['subcategory']?>">
        </a>
    </div>
    <div class="card--product-info">
        <h4><a href="<?=base_url()."/tienda/producto/".$producto['route']?>"><?=$producto['name']?></a></h4>
        <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
        <div class="card--price">
            <?=$price?>
        </div>
        
    </div>
    <div class="card--product-btns">
        <div class="d-flex">
            <?=$favorite?>
            <?php if(!$producto['product_type'] && $producto['is_stock'] && $producto['stock'] > 0){?>
            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
            <?php }else if(!$producto['product_type'] && !$producto['is_stock']){?>
            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
            <?php }else if($producto['product_type']){?>
            <a href="<?=base_url()."/tienda/producto/".$producto['route']?>" class="btn btn-bg-1 w-100"><i class="fas fa-exchange-alt"></i></a>
            <?php }?>
        </div>
    </div>
</div>
<?php ?>