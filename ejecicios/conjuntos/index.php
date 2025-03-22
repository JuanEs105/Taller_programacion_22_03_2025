<?php
require_once 'procesar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Operaciones con Conjuntos</title>
</head>
<body>
    <h2>Operaciones con Conjuntos</h2>
    <form method="post">
        <label>Conjunto A (separado por comas):</label>
        <input type="text" name="conjuntoA" required>

        <label>Conjunto B (separado por comas):</label>
        <input type="text" name="conjuntoB" required>

        <input type="submit" value="Calcular">
    </form>

    <?php if (!empty($resultados)): ?>
        <h3>Resultados:</h3>
        <ul>
            <?php foreach ($resultados as $operacion => $resultado): ?>
                <li><strong><?= $operacion ?>:</strong> <?= $resultado ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
