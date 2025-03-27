<?php

class Nodo {
    public $valor;
    public $izquierda;
    public $derecha;
    
    public function __construct($valor) {
        $this->valor = $valor;
        $this->izquierda = null;
        $this->derecha = null;
    }
}

class ArbolBinario {
    private $raiz;

    public function __construct() {
        $this->raiz = null;
    }

    public function construirDesdePreInorden($preorden, $inorden) {
        $this->raiz = $this->construir($preorden, $inorden);
    }

    private function construir($preorden, $inorden) {
        if (empty($preorden) || empty($inorden)) {
            return null;
        }

        $raizValor = array_shift($preorden);
        $raiz = new Nodo($raizValor);

        $indiceRaiz = array_search($raizValor, $inorden);

        $inordenIzq = array_slice($inorden, 0, $indiceRaiz);
        $inordenDer = array_slice($inorden, $indiceRaiz + 1);

        $preordenIzq = array_slice($preorden, 0, count($inordenIzq));
        $preordenDer = array_slice($preorden, count($inordenIzq));

        $raiz->izquierda = $this->construir($preordenIzq, $inordenIzq);
        $raiz->derecha = $this->construir($preordenDer, $inordenDer);

        return $raiz;
    }

    public function imprimirArbolHTML($nodo, $tipoNodo = "raiz") {
        if (!$nodo) {
            return "";
        }
        
        $clase = $tipoNodo === "raiz" ? "raiz" : ($nodo->izquierda || $nodo->derecha ? "rama" : "hoja");
        
        $html = "<li><div class='$clase'>{$nodo->valor}</div>";
        if ($nodo->izquierda || $nodo->derecha) {
            $html .= "<ul>";
            if ($nodo->izquierda) {
                $html .= $this->imprimirArbolHTML($nodo->izquierda, "rama");
            }
            if ($nodo->derecha) {
                $html .= $this->imprimirArbolHTML($nodo->derecha, "rama");
            }
            $html .= "</ul>";
        }
        $html .= "</li>";

        return $html;
    }

    public function getRaiz() {
        return $this->raiz;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $preorden = explode(",", str_replace(" ", "", $_POST['preorden']));
    $inorden = explode(",", str_replace(" ", "", $_POST['inorden']));
    
    $arbol = new ArbolBinario();
    $arbol->construirDesdePreInorden($preorden, $inorden);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árbol Binario</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .tree-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .tree {
            text-align: center;
            position: relative;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;
            display: flex;
            justify-content: center;
        }

        .tree li {
            list-style-type: none;
            text-align: center;
            position: relative;
            padding: 20px 5px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .tree li::before, .tree li::after {
            content: "";
            position: absolute;
            top: 0;
            width: 50%;
            height: 20px;
            border-top: 2px solid black;
        }

        .tree li::before {
            right: 50%;
            border-right: 2px solid black;
            transform: rotate(-45deg);
            transform-origin: right;
        }

        .tree li::after {
            left: 50%;
            border-left: 2px solid black;
            transform: rotate(45deg);
            transform-origin: left;
        }

        .tree li:only-child::before, .tree li:only-child::after {
            display: none;
        }

        .tree li:first-child::before, .tree li:last-child::after {
            border: none;
        }

        .tree li div {
            padding: 10px 15px;
            border-radius: 50%;
            font-weight: bold;
            position: relative;
            border: 2px solid black;
        }

        .raiz { background: gold; color: black; }
        .rama { background: lightblue; color: black; }
        .hoja { background: lightgreen; color: black; }

    </style>
</head>
<body>
<nav class="menu">
                <a href="../../index.php">Inicio</a>
                <a href="../../ejecicios\acronimo\acronimo.php">Acrónimos</a>
                <a href="../../ejecicios\fibofact\fibofact.php">Fibonacci/Factorial</a>
                <a href="../../ejecicios\estadisticas\estadisticas.php">Estadísticas</a>
                <a href="../../ejecicios\binario\binario.php">Binario</a>
                <a href="../../ejecicios\conjuntos\index.php">Conjutos</a>
                <a href="../../ejecicios\Arbol Binario\Arbol.php">Arbol</a>
            </nav>
    <div class="container">
        <h2>Construcción de Árbol Binario</h2>
        <form method="post">
            <label>Recorrido Preorden (separado por comas):</label>
            <input type="text" name="preorden" required><br>
            <label>Recorrido Inorden (separado por comas):</label>
            <input type="text" name="inorden" required><br>
            <button type="submit">Construir Árbol</button>
        </form>

        <?php if (isset($arbol)) { ?>
            <h3>Árbol generado:</h3>
            <div class="tree-container">
                <div class="tree">
                    <ul>
                        <?php echo $arbol->imprimirArbolHTML($arbol->getRaiz()); ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>