<?php
require_once 'Conjunto.php';
require_once 'Calculadora.php';

$resultados = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $A = $_POST['conjuntoA'];
    $B = $_POST['conjuntoB'];

    $conjuntoA = new Conjunto($A);
    $conjuntoB = new Conjunto($B);
    $calculadora = new Calculadora($conjuntoA, $conjuntoB);

    $resultados['Unión'] = implode(', ', $calculadora->union());
    $resultados['Intersección'] = implode(', ', $calculadora->interseccion());
    $resultados['Diferencia A - B'] = implode(', ', $calculadora->diferenciaAB());
    $resultados['Diferencia B - A'] = implode(', ', $calculadora->diferenciaBA());
}
?>
