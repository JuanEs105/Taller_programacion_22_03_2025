<?php


class Nodo {
    private $valor;
    private $izquierda;
    private $derecha;
    
    public function __construct($valor) {
        $this->valor = $valor;
        $this->izquierda = null;
        $this->derecha = null;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getIzquierda() {
        return $this->izquierda;
    }

    public function getDerecha() {
        return $this->derecha;
    }

    public function setIzquierda($nodo) {
        $this->izquierda = $nodo;
    }

    public function setDerecha($nodo) {
        $this->derecha = $nodo;
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

        $raiz->setIzquierda($this->construir($preordenIzq, $inordenIzq));
        $raiz->setDerecha($this->construir($preordenDer, $inordenDer));

        return $raiz;
    }

    public function getRaiz() {
        return $this->raiz;
    }

    public function imprimirArbolHTML($nodo, $tipoNodo = "raiz") {
        if (!$nodo) {
            return "";
        }

        $clase = $tipoNodo === "raiz" ? "raiz" : ($nodo->getIzquierda() || $nodo->getDerecha() ? "rama" : "hoja");

        $html = "<li><div class='$clase'>{$nodo->getValor()}</div>";
        if ($nodo->getIzquierda() || $nodo->getDerecha()) {
            $html .= "<ul>";
            if ($nodo->getIzquierda()) {
                $html .= $this->imprimirArbolHTML($nodo->getIzquierda(), "rama");
            }
            if ($nodo->getDerecha()) {
                $html .= $this->imprimirArbolHTML($nodo->getDerecha(), "rama");
            }
            $html .= "</ul>";
        }
        $html .= "</li>";

        return $html;
    }
}

class GestorArbol {
    private $inputPreorden;
    private $inputInorden;
    private $resultadoHTML;
    private $error;

    public function __construct() {
        $this->inputPreorden = "";
        $this->inputInorden = "";
        $this->resultadoHTML = "";
        $this->error = "";
    }

    public function procesarFormulario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->inputPreorden = trim($_POST['preorden']);
            $this->inputInorden = trim($_POST['inorden']);

            if (!empty($this->inputPreorden) && !empty($this->inputInorden)) {
                $preorden = explode(",", str_replace(" ", "", $this->inputPreorden));
                $inorden = explode(",", str_replace(" ", "", $this->inputInorden));

                $arbol = new ArbolBinario();
                $arbol->construirDesdePreInorden($preorden, $inorden);
                $this->resultadoHTML = $arbol->imprimirArbolHTML($arbol->getRaiz());
            } else {
                $this->error = "Ambos recorridos son requeridos.";
            }
        }
    }

    public function getPreorden() {
        return $this->inputPreorden;
    }

    public function getInorden() {
        return $this->inputInorden;
    }

    public function getResultadoHTML() {
        return $this->resultadoHTML;
    }

    public function getError() {
        return $this->error;
    }
}


$gestor = new GestorArbol();
$gestor->procesarFormulario();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árbol Binario</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <nav class="menu">
        <a href="../../index.php">Inicio</a>
        <a href="../../ejecicios/acronimo/acronimo.php">Acrónimos</a>
        <a href="../../ejecicios/fibofact/fibofact.php">Fibonacci/Factorial</a>
        <a href="../../ejecicios/estadisticas/estadisticas.php">Estadísticas</a>
        <a href="../../ejecicios/binario/binario.php">Binario</a>
        <a href="../../ejecicios/conjuntos/index.php">Conjuntos</a>
        <a href="../../ejecicios/Arbol Binario/Arbol.php">Árbol</a>
    </nav>

    <div class="container">
        <h1>Construcción de Árbol Binario</h1>

        <form method="POST">
            <div class="form-group">
                <label>Recorrido Preorden (separado por comas):</label>
                <input type="text" name="preorden" 
                       value="<?= htmlspecialchars($gestor->getPreorden()) ?>"
                       placeholder="Ej: A,B,C,D,E,F,G" required>
            </div>
            <div class="form-group">
                <label>Recorrido Inorden (separado por comas):</label>
                <input type="text" name="inorden" 
                       value="<?= htmlspecialchars($gestor->getInorden()) ?>"
                       placeholder="Ej: D,B,E,A,F,C,G" required>
            </div>
            <button type="submit">Construir Árbol</button>
        </form>

        <?php if ($gestor->getError()): ?>
            <div class="error">
                <?= htmlspecialchars($gestor->getError()) ?>
            </div>
        <?php endif; ?>

        <?php if ($gestor->getResultadoHTML()): ?>
            <div class="tree-container">
                <h3>Árbol generado:</h3>
                <div class="tree">
                    <ul>
                        <?= $gestor->getResultadoHTML() ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
