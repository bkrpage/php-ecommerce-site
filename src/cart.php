<?php
class Cart implements Iterator, Countable {
    protected $items = array();
    protected $position = 0;
    protected $ids = array();
    protected $total_price = 0;
    public function isEmpty() {
        return (empty($this->items));
    }

    public function addItem(Item $item){
        $id = $item -> getPId();

        if (isset($this -> items[$id])) {
            // If there's already an item with $item's ID --
            $this -> updateItem($item, $this -> items[$id]['qty'] + 1);
        } else {
            $this -> items[$id] = array('item' => $item, 'qty' => 1);
            $this -> ids[] = $id;
		}
    }

    public function updateItem(Item $item, $qty){
        $id = $item->getPId();

        if ($qty === 0){
            $this -> deleteItem($item);
        } else if (($qty > 0) && ($qty != $this -> items[$id]['qty'])) {
            $this->items[$id]['qty'] = $qty;
        }
    }


    public function deleteItem(Item $item){
        $id = $item -> getPId();

        if (isset($this -> items[$id])) {
            unset($this->items[$id]);

            $index = array_search($id, $this->ids);
            unset($this->ids[$index]);

            $this->ids = array_values($this->ids);
        }
    }

    public function getItems(){
        return $this -> items;
    }

    public function calcTotalPrice(){
        $total_price = 0;

        foreach ($this-> items as $arr){
            $item = $arr['item'];
            $price = $item -> getPrice();
            $qty = $arr['qty'];

            $total_price += $price * $qty;
        }

        return $total_price;
    }

    public function setItems($items){
        $this -> items = $items;
    }

    public function count(){
        return count($this ->items);
    }
    public function key() {
        return $this->position;
    }
    public function next() {
        $this->position++;
    }
    public function rewind() {
        $this->position = 0;
    }
    public function valid() {
        return (isset($this->ids[$this->position]));
    }

    public function current() {
        $index = $this->ids[$this->position];
        return $this->items[$index];
    } // End of current() method.
}
?>