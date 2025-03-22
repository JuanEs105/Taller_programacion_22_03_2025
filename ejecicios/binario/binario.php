<?php
session_start();

$numero = '';
$binario = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = str_replace(',', '.', trim($_POST['numero'] ?? ''));
    
    if (!is_numeric($input)) {
        $error = "Ingrese un número válido";
    } else {
        $numero_float = (float)$input;
        if ($numero_float != (int)$numero_float) {
            $error = "El número debe ser entero";
        } else {
            $numero = (int)$numero_float;
            $binario = decbin($numero);
        }
    }
}
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
                   value="<?= htmlspecialchars($numero) ?>"
                   required>
            <button type="submit">Convertir</button>
        </form>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($binario !== '' && !$error): ?>
            <div class="resultado">
                <h3>Resultado:</h3>
                <p>
                    <?= htmlspecialchars($numero) ?><sub>10</sub> = 
                    <?= htmlspecialchars($binario) ?><sub>2</sub>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>