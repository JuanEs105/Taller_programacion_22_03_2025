<?php
session_start();

class CalculadoraFibonacci {
    public function calcular($n) {
        $fib = [0, 1];
        for ($i = 2; $i < $n; $i++) {
            $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
        }
        return array_slice($fib, 0, $n);
    }
}

class CalculadoraFactorial {
    public function calcular($n) {
        if ($n == 0) {
            return ['valor' => 1, 'pasos' => '0! = 1'];
        }
        
        $fact = 1;
        $pasos = [];
        for ($i = 1; $i <= $n; $i++) {
            $fact *= $i;
            $pasos[] = $i;
        }
        
        $expresion = implode('×', $pasos);
        return [
            'valor' => $fact,
            'pasos' => "$expresion = $fact"
        ];
    }
}

class CalculadoraMatematica {
    private $fibonacci;
    private $factorial;
    
    public function __construct() {
        $this->fibonacci = new CalculadoraFibonacci();
        $this->factorial = new CalculadoraFactorial();
    }
    
    public function ejecutarOperacion($operacion, $numero) {
        switch ($operacion) {
            case 'fibonacci':
                $serie = $this->fibonacci->calcular($numero);
                return "Serie Fibonacci: " . implode(', ', $serie);
            case 'factorial':
                $factData = $this->factorial->calcular($numero);
                return "Factorial de $numero: " . $factData['pasos'];
            default:
                throw new Exception("Operación no válida");
        }
    }
}

class FormularioCalculadora {
    private $numero;
    private $operacion;
    private $error;
    private $resultado;
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->numero = $_POST['numero'] ?? '';
            $this->operacion = $_POST['operacion'] ?? 'fibonacci';
            
            if (!$this->validarNumero()) {
                $this->error = "Por favor ingrese un número entero positivo válido";
            } else {
                $this->numero = (int)$this->numero;
                $calculadora = new CalculadoraMatematica();
                try {
                    $this->resultado = $calculadora->ejecutarOperacion(
                        $this->operacion, 
                        $this->numero
                    );
                } catch (Exception $e) {
                    $this->error = $e->getMessage();
                }
            }
        }
    }
    
    private function validarNumero() {
        return is_numeric($this->numero) && 
               $this->numero >= 0 && 
               $this->numero == floor($this->numero);
    }
    
    public function getNumero() {
        return $this->numero;
    }
    
    public function getOperacion() {
        return $this->operacion;
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function getResultado() {
        return $this->resultado;
    }
}

class VistaCalculadora {
    public static function render(FormularioCalculadora $formulario) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Fibonacci y Factorial</title>
            <link rel="stylesheet" href="../styles.css">
        </head>
        <body>
            <nav class="menu">
                <a href="index.php">Inicio</a>
                <a href="../Taller_programacion_22_03_2025/ejecicios/acronimo/acronimo.php">Acrónimos</a>
                <a href="../Taller_programacion_22_03_2025/ejecicios/fibofact/fibofact.php">Fibonacci/Factorial</a>
                <a href="../Taller_programacion_22_03_2025/ejecicios/estadisticas/estadisticas.php">Estadísticas</a>
                <a href="../Taller_programacion_22_03_2025/ejecicios/binario/binario.php">Binario</a>
                <a href="../Taller_programacion_22_03_2025/ejecicios/conjuntos/index.php">Conjuntos</a>
                <a href="../Taller_programacion_22_03_2025/ejecicios/Arbol Binario/Arbol.php">Arbol</a>
            </nav>
            
            <div class="container">
                <h1>Calculadora de Fibonacci y Factorial</h1>
                
                <form method="POST">
                    <input type="number" name="numero" placeholder="Ingrese un número" 
                           value="<?= htmlspecialchars($formulario->getNumero()) ?>" min="0" required>
                    
                    <select name="operacion">
                        <option value="fibonacci" <?= $formulario->getOperacion() === 'fibonacci' ? 'selected' : '' ?>>Fibonacci</option>
                        <option value="factorial" <?= $formulario->getOperacion() === 'factorial' ? 'selected' : '' ?>>Factorial</option>
                    </select>
                    
                    <button type="submit">Calcular</button>
                </form>

                <?php if ($formulario->getError()): ?>
                    <div class="error">
                        <?= htmlspecialchars($formulario->getError()) ?>
                    </div>
                <?php endif; ?>

                <?php if ($formulario->getResultado() && !$formulario->getError()): ?>
                    <div class="resultado">
                        <h3>Resultado:</h3>
                        <p><?= str_replace('×', '×', htmlspecialchars($formulario->getResultado())) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}


$formulario = new FormularioCalculadora();
$formulario->procesar();
VistaCalculadora::render($formulario);
?>