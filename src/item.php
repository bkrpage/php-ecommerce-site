<?php
/**
 * This class is used to create item objects to be used in the listings, basket and checkout pages.
 *
 * @author Bradley Page
 * @version indev0.1
 */
class Item {
    protected $product_id;
    protected $product_name;
    protected $product_desc;
    protected $variant_id;
    protected $variant_desc;
    protected $img_path;
    protected $price;
    protected $stock;

    /**
     *
     */
    public function __construct($product_id, $product_name, $product_desc, $variant_id,
                                $variant_desc, $img_path, $price, $stock){
        $this -> product_id = $product_id;
        $this -> product_name = $product_name;
        $this -> product_desc = $product_desc;
        $this -> variant_id = $variant_id;
        $this -> variant_desc = $variant_desc;
        $this -> img_path = $img_path;
        $this -> price = $price;
        $this -> stock = $stock;
    }

    public function getPID(){
        return $this -> product_id;
    }

    public function getPName(){
        return $this -> product_name;
    }

    public function getPDesc(){
        return $this -> product_desc;
    }

    public function getVID(){
        return $this -> variant_id;
    }

    public function getVDesc(){
        return $this -> variant_desc;
    }

    public function getImgPath(){
        return $this -> img_path;
    }

    public function getPrice(){
        return $this -> price;
    }
}
?>