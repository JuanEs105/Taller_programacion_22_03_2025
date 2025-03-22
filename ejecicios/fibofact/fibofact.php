<?php
session_start();

function calcularFibonacci($n) {
    $fib = [0, 1];
    for ($i = 2; $i < $n; $i++) {
        $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
    }
    return array_slice($fib, 0, $n);
}

function calcularFactorial($n) {
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

$resultado = '';
$error = '';
$numero = '';
$operacion = 'fibonacci';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'] ?? '';
    $operacion = $_POST['operacion'] ?? 'fibonacci';
    
    if (!is_numeric($numero) || $numero < 0 || $numero != floor($numero)) {
        $error = "Por favor ingrese un número entero positivo válido";
    } else {
        $numero = (int)$numero;
        if ($operacion === 'fibonacci') {
            $serie = calcularFibonacci($numero);
            $resultado = "Serie Fibonacci: " . implode(', ', $serie);
        } else {
            $factData = calcularFactorial($numero);
            $resultado = "Factorial de $numero:" . $factData['pasos'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fibonacci y Factorial</title>
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
        <h1>Calculadora de Fibonacci y Factorial</h1>
        
        <form method="POST">
            <input type="number" name="numero" placeholder="Ingrese un número" 
                   value="<?= htmlspecialchars($numero) ?>" min="0" required>
            
            <select name="operacion">
                <option value="fibonacci" <?= $operacion === 'fibonacci' ? 'selected' : '' ?>>Fibonacci</option>
                <option value="factorial" <?= $operacion === 'factorial' ? 'selected' : '' ?>>Factorial</option>
            </select>
            
            <button type="submit">Calcular</button>
        </form>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($resultado && !$error): ?>
            <div class="resultado">
                <h3>Resultado:</h3>
                <p><?= str_replace('×', '×', htmlspecialchars($resultado)) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>