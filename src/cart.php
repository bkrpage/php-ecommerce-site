class Cart implements Iterator, Countable {
    protected $items = array();
    protected $position = 0;
    protected $ids = array();

    public function isEmpty() {
        return (empty($this->items));
    }

    public function addItem(Item $item){
        $id = $item -> getId();

        if (!$id) throw new Exception(
            'The cart requires items with unique ID values');

        if (isset($this -> items[$id])) {
            //uses classes own function, if the item is already there adds onto the qty
            $this -> updateItem($item, $this -> items[$item]['qty'] + 1);
        } else {
            $this -> items[$id] = array('item' => $item, 'qty' => 1);
            $this -> ids[] = $id;
		}
    }

    public function updateItem(Item $item, $qty){
        $id = $item->getId();

        if ($qty === 0){
            $this -> deleteItem($item);
        } else if (($qty > 0) && ($qty != $this -> items[$id]['qty'])) {
            $this->items[$id]['qty'] = $qty;
        }
    }


    public function deleteItem(Item $item){
        $id = $item -> getId();

        if (isset($this -> items[$id])) {
            unset($this->items[$id]);

            $index = array_search($id, $this->ids);
            unset($this->ids[$index]);

            $this->ids = array_values($this->ids);
        }
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