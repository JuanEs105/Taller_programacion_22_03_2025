<?php
require_once 'procesar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Operaciones con Conjuntos</title>
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
        <h1>Operaciones con Conjuntos</h1>

        <form method="post">
            <div class="form-group">
                <label>Conjunto A (separado por comas):</label>
                <input type="text" name="conjuntoA" required>
            </div>
            <div class="form-group">
                <label>Conjunto B (separado por comas):</label>
                <input type="text" name="conjuntoB" required>
            </div>
            <button type="submit">Calcular</button>
        </form>

        <?php if (!empty($resultados)): ?>
            <div class="resultados">
                <h3>Resultados:</h3>
                <ul>
                    <?php foreach ($resultados as $operacion => $resultado): ?>
                        <li><strong><?= $operacion ?>:</strong> <?= $resultado ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
