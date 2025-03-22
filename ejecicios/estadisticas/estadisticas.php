<?php
session_start();

function calcularEstadisticas($numeros) {
    $resultados = [];
    
    // Calcular Media
    $resultados['media'] = array_sum($numeros) / count($numeros);
    
    // Calcular Mediana
    sort($numeros);
    $count = count($numeros);
    $mid = floor(($count - 1) / 2);
    
    if ($count % 2 == 0) {
        $resultados['mediana'] = ($numeros[$mid] + $numeros[$mid + 1]) / 2;
    } else {
        $resultados['mediana'] = $numeros[$mid];
    }
    
    // Calcular Moda
    $frecuencias = array_count_values(
        array_map('strval', $numeros)
    );
    arsort($frecuencias);
    
    $max_frecuencia = reset($frecuencias);
    $modas = array_keys(array_filter(
        $frecuencias, 
        function($v) use ($max_frecuencia) { 
            return $v == $max_frecuencia; 
        }
    ));
    
    $resultados['modas'] = ($max_frecuencia > 1) 
        ? array_map('floatval', $modas)
        : [];
    
    return $resultados;
}

$input = '';
$error = '';
$resultados = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['numeros'] ?? '');
    
    if (!empty($input)) {
        // Limpiar y validar números
        $numeros = [];
        $valores = preg_split('/[\s,;]+/', $input);
        
        foreach ($valores as $valor) {
            $valor_limpio = str_replace(',', '.', $valor);
            if (is_numeric($valor_limpio)) {
                $numeros[] = (float)$valor_limpio;
            } else {
                $error = "Valor no válido: $valor";
                break;
            }
        }
        
        if (count($numeros) < 1) {
            $error = "Debe ingresar al menos un número";
        }
        
        if (!$error) {
            $resultados = calcularEstadisticas($numeros);
        }
    } else {
        $error = "Por favor ingrese números válidos";
    }
}
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
                <label>Ingrese números separados por comas, espacios y comas:</label>
                <input type="text" name="numeros" 
                       value="<?= htmlspecialchars($input) ?>"
                       placeholder="Ej: 5, 3.2 4; 6 7.8" required>
            </div>
            <button type="submit">Calcular</button>
        </form>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($resultados): ?>
            <div class="resultado">
                <h3>Resultados:</h3>
                <p><strong>Media:</strong> <?= number_format($resultados['media'], 2) ?></p>
                <p><strong>Mediana:</strong> <?= number_format($resultados['mediana'], 2) ?></p>
                <p><strong>Moda:</strong> 
                    <?php if (empty($resultados['modas'])): ?>
                        No existe moda
                    <?php else: ?>
                        <?= implode(', ', array_map(
                            fn($m) => number_format($m, 2), 
                            $resultados['modas']
                        )) ?>
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>