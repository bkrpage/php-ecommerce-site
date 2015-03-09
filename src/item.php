<?php
class Item {
    protected $id;
    protected $name;
    protected $desc;
    protected $img_path;
    protected $price;

    public function __construct($id, $name, $desc, $img_path, $price){
        $this -> id = $id;
        $this -> name = $name;
        $this -> desc = $desc;
        $this -> img_path = $img_path;
        $this -> price = $price;
    }

    public function getId(){
        return $this -> id;
    }

    public function getName(){
        return $this -> name;
    }

    public function getDesc(){
        return $this -> desc;
    }

    public function getImgPath(){
        return $this -> img_path;
    }

    public function getPrice(){
        return $this -> price;
    }
}
?>