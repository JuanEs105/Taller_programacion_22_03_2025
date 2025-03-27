<?php
session_start();

class ConversorBinario {
    public function convertir($input) {
        $numero = $this->validarYConvertir($input);
        if (!is_numeric($numero)) {
            throw new InvalidArgumentException($numero); // $numero contiene el mensaje de error
        }
        return [
            'decimal' => $numero,
            'binario' => decbin($numero)
        ];
    }

    private function validarYConvertir($input) {
        $inputLimpio = str_replace(',', '.', trim($input));
        
        if (!is_numeric($inputLimpio)) {
            return "Ingrese un número válido";
        }
        
        $numeroFloat = (float)$inputLimpio;
        if ($numeroFloat != (int)$numeroFloat) {
            return "El número debe ser entero";
        }
        
        return (int)$numeroFloat;
    }
}

class FormularioBinario {
    private $input;
    private $resultado;
    private $error;
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->input = $_POST['numero'] ?? '';
            $conversor = new ConversorBinario();
            
            try {
                $this->resultado = $conversor->convertir($this->input);
            } catch (InvalidArgumentException $e) {
                $this->error = $e->getMessage();
            }
        }
    }
    
    public function getInput() {
        return $this->input;
    }
    
    public function getResultado() {
        return $this->resultado;
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function tieneResultado() {
        return !empty($this->resultado);
    }
}

class VistaBinario {
    public static function render(FormularioBinario $formulario) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Conversor a Binario</title>
            <link rel="stylesheet" href="../../styles.css">
        </head>
        <body>
            <nav class="menu">
                <a href="../../index.php">Inicio</a>
                <a href="../../ejercicios/acronimo">Acrónimos</a>
                <a href="../../ejercicios/fibofact">Fibonacci/Factorial</a>
                <a href="../../ejercicios/estadisticas">Estadísticas</a>
                <a href="../../ejercicios/binario">Binario</a>
            </nav>
            
            <div class="container">
                <h1>Conversor de Decimal a Binario</h1>
                
                <form method="POST">
                    <input type="text" name="numero" 
                           placeholder="Ej: -255, 1024" 
                           value="<?= htmlspecialchars($formulario->getInput()) ?>"
                           required>
                    <button type="submit">Convertir</button>
                </form>

                <?php if ($formulario->getError()): ?>
                    <div class="error">
                        <?= htmlspecialchars($formulario->getError()) ?>
                    </div>
                <?php endif; ?>

                <?php if ($formulario->tieneResultado()): ?>
                    <div class="resultado">
                        <h3>Resultado:</h3>
                        <p>
                            <?= htmlspecialchars($formulario->getResultado()['decimal']) ?><sub>10</sub> = 
                            <?= htmlspecialchars($formulario->getResultado()['binario']) ?><sub>2</sub>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}


$formulario = new FormularioBinario();
$formulario->procesar();
VistaBinario::render($formulario);
?>