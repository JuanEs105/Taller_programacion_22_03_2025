<?php

class Conjunto {
    public $elementos;

    public function __construct($entrada) {
        $this->elementos = array_unique(array_map('intval', explode(',', $entrada)));
    }
}

?>
