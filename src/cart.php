<?php

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

    public function getItems()
    {
        return $this->items;
    }

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