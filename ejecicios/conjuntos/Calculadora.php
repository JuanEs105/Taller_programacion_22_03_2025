<?php

require_once 'Conjunto.php';

class Calculadora {
    public $A;
    public $B;

    public function __construct(Conjunto $A, Conjunto $B) {
        $this->A = $A->elementos;
        $this->B = $B->elementos;
    }

    public function union() {
        return array_values(array_unique(array_merge($this->A, $this->B)));
    }

    public function interseccion() {
        return array_values(array_intersect($this->A, $this->B));
    }

    public function diferenciaAB() {
        return array_values(array_diff($this->A, $this->B));
    }

    public function diferenciaBA() {
        return array_values(array_diff($this->B, $this->A));
    }
}

?>
