<?php

/**
 * @author: Bradley Page
 */
class Cart implements Iterator, Countable
{
    protected $position = 0;
    protected $items = array();
    protected $ids = array();
    protected $v_ids = array();
    protected $total_price = 0;

    public function isEmpty()
    {
        return (empty($this->items));
    }

    /**
     * @param Item $item Adds the specified item to the cart - with seperate values for different variations
     */
    public function addItem(Item $item)
    {
        $id = $item->getPId();
        $v_id = $item->getVID();

        if (isset($this->items[$id][$v_id])) {
            // If there's already an item with $item's ID --
            $this->updateItem($item, $this->items[$id][$v_id]['qty'] + 1);
        } else {
            $this->items[$id][$v_id] = array('item' => $item, 'qty' => 1);
            $this->ids[] = $id;
            $this->v_ids[] = $v_id;
        }
    }

    /**
     * @param Item $item Updates this item that is in cart.
     * @param $qty Quantity to set item to
     */
    public function updateItem(Item $item, $qty)
    {
        $id = $item->getPId();
        $v_id = $item->getVID();

        if ($qty === 0) {
            $this->deleteItem($item);
        } else if (($qty > 0) && ($qty != $this->items[$id][$v_id]['qty'])) {
            $this->items[$id][$v_id]['qty'] = $qty;
        }
    }

    /**
     * @param $id Id of item to update
     * @param $v_id Variant ID of item to update
     * @param $qty Quantity to update to
     */
    public function updateItemById($id, $v_id, $qty){

        if ($qty === 0) {
            $this->deleteItemById($id, $v_id);
        } else if (($qty > 0) && ($qty != $this->items[$id][$v_id]['qty'])) {
            $this->items[$id][$v_id]['qty'] = $qty;
        }
    }

    /**
     * @param Item $item Item to be deleted form cart
     */
    public function deleteItem(Item $item)
    {
        $id = $item->getPId();
        $v_id = $item->getVID();

        if (isset($this->items[$id][$v_id])) {
            unset($this->items[$id][$v_id]);

            $index = array_search($id, $this->ids);
            unset($this->ids[$index]);

            $this->ids = array_values($this->ids);
        }
    }

    /**
     * @param $id ID of item to be deleted form cart
     * @param $v_id Variation ID of the item to be deleted from cart
     */
    public function deleteItemById($id, $v_id){

        if (isset($this->items[$id][$v_id])) {
            unset($this->items[$id][$v_id]);

            $index = array_search($id, $this->ids);
            unset($this->ids[$index]);

            $this->ids = array_values($this->ids);
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * Adds the prices using the quantity of each item
     * @return int The total price of cart
     */
    public function calcTotalPrice()
    {
        $total_price = 0;

        foreach ($this->getItems() as $items) {
            foreach ($items as $vrnt) {
                $item = $vrnt['item'];
                $price = $item->getPrice();
                $qty = $vrnt['qty'];

                $total_price += $price * $qty;
            }
        }

        return $total_price;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return (isset($this->ids[$this->position]));
    }

    public function current()
    {
        $index = $this->ids[$this->position];
        return $this->items[$index];
    } // End of current() method.
}

?>