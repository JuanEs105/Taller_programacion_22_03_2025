<?php
session_start();

$acronimo = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frase = $_POST['frase'] ?? '';
    $fraseLimpia = preg_replace('/[^\w\s-]/', '', $frase);
    $palabras = preg_split('/[\s-]+/', $fraseLimpia);
    
    foreach ($palabras as $palabra) {
        if (!empty($palabra)) {
            $acronimo .= strtoupper($palabra[0]);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generador de Acrónimos</title>
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
        <h1>Generador de Acrónimos</h1>
        <form method="POST">
            <input type="text" name="frase" placeholder="Ej: Portable Network Graphics" required>
            <button type="submit">Generar</button>
        </form>
        
        <?php if (!empty($acronimo)): ?>
            <div class="resultado">
                <h3>Resultado:</h3>
                <p><?= htmlspecialchars($acronimo) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>