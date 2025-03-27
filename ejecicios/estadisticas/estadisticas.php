<?php
session_start();

class CalculadoraEstadistica {
    public function calcularMedia(array $numeros): float {
        return array_sum($numeros) / count($numeros);
    }

    public function calcularMediana(array $numeros): float {
        sort($numeros);
        $count = count($numeros);
        $mid = floor(($count - 1) / 2);
        
        if ($count % 2 == 0) {
            return ($numeros[$mid] + $numeros[$mid + 1]) / 2;
        }
        return $numeros[$mid];
    }

    public function calcularModas(array $numeros): array {
        $frecuencias = array_count_values(array_map('strval', $numeros));
        arsort($frecuencias);
        
        $max_frecuencia = reset($frecuencias);
        if ($max_frecuencia <= 1) {
            return [];
        }
        
        $modas = array_keys(array_filter(
            $frecuencias, 
            fn($v) => $v == $max_frecuencia
        ));
        
        return array_map('floatval', $modas);
    }

    public function calcularTodo(array $numeros): array {
        return [
            'media' => $this->calcularMedia($numeros),
            'mediana' => $this->calcularMediana($numeros),
            'modas' => $this->calcularModas($numeros)
        ];
    }
}

class ProcesadorNumeros {
    public function validarYProcesar(string $input): array {
        if (empty($input)) {
            throw new InvalidArgumentException("Por favor ingrese números válidos");
        }
        
        $numeros = [];
        $valores = preg_split('/[\s,;]+/', $input);
        
        foreach ($valores as $valor) {
            $valor_limpio = str_replace(',', '.', $valor);
            if (!is_numeric($valor_limpio)) {
                throw new InvalidArgumentException("Valor no válido: $valor");
            }
            $numeros[] = (float)$valor_limpio;
        }
        
        if (count($numeros) < 1) {
            throw new InvalidArgumentException("Debe ingresar al menos un número");
        }
        
        return $numeros;
    }
}

class FormularioEstadisticas {
    private $input;
    private $resultados;
    private $error;
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->input = trim($_POST['numeros'] ?? '');
            
            try {
                $procesador = new ProcesadorNumeros();
                $numeros = $procesador->validarYProcesar($this->input);
                
                $calculadora = new CalculadoraEstadistica();
                $this->resultados = $calculadora->calcularTodo($numeros);
            } catch (InvalidArgumentException $e) {
                $this->error = $e->getMessage();
            }
        }
    }
    
    public function getInput(): string {
        return $this->input ?? '';
    }
    
    public function getResultados(): ?array {
        return $this->resultados ?? null;
    }
    
    public function getError(): ?string {
        return $this->error ?? null;
    }
}

class VistaEstadisticas {
    public static function render(FormularioEstadisticas $formulario) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Estadísticas</title>
            <link rel="stylesheet" href="../../styles.css">
        </head>
        <body>
            <nav class="menu">
                <a href="../../index.php">Inicio</a>
                <a href="../../ejercicios/acronimo">Acrónimos</a>
                <a href="../../ejercicios/fibofact">Fibonacci/Factorial</a>
                <a href="../../ejercicios/estadisticas">Estadísticas</a>
            </nav>
            
            <div class="container">
                <h1>Calculadora Estadística</h1>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Ingrese números separados por comas, espacios o comas:</label>
                        <input type="text" name="numeros" 
                               value="<?= htmlspecialchars($formulario->getInput()) ?>"
                               placeholder="Ej: 5, 3.2 4; 6 7.8" required>
                    </div>
                    <button type="submit">Calcular</button>
                </form>

                <?php if ($formulario->getError()): ?>
                    <div class="error">
                        <?= htmlspecialchars($formulario->getError()) ?>
                    </div>
                <?php endif; ?>

                <?php if ($formulario->getResultados()): ?>
                    <div class="resultado">
                        <h3>Resultados:</h3>
                        <p><strong>Media:</strong> <?= number_format($formulario->getResultados()['media'], 2) ?></p>
                        <p><strong>Mediana:</strong> <?= number_format($formulario->getResultados()['mediana'], 2) ?></p>
                        <p><strong>Moda:</strong> 
                            <?php if (empty($formulario->getResultados()['modas'])): ?>
                                No existe moda
                            <?php else: ?>
                                <?= implode(', ', array_map(
                                    fn($m) => number_format($m, 2), 
                                    $formulario->getResultados()['modas']
                                )) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}


$formulario = new FormularioEstadisticas();
$formulario->procesar();
VistaEstadisticas::render($formulario);
?>