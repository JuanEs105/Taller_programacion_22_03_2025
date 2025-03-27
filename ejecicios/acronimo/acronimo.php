<?php
session_start();

class GeneradorAcronimo {
    public function generar($frase) {
        $fraseLimpia = $this->limpiarFrase($frase);
        $palabras = $this->dividirPalabras($fraseLimpia);
        return $this->construirAcronimo($palabras);
    }
    
    private function limpiarFrase($frase) {
        return preg_replace('/[^\w\s-]/', '', $frase);
    }
    
    private function dividirPalabras($frase) {
        return preg_split('/[\s-]+/', $frase);
    }
    
    private function construirAcronimo($palabras) {
        $acronimo = '';
        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $acronimo .= strtoupper($palabra[0]);
            }
        }
        return $acronimo;
    }
}

class FormularioAcronimo {
    private $frase;
    private $acronimo;
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->frase = $_POST['frase'] ?? '';
            $generador = new GeneradorAcronimo();
            $this->acronimo = $generador->generar($this->frase);
        }
    }
    
    public function getFrase() {
        return $this->frase;
    }
    
    public function getAcronimo() {
        return $this->acronimo;
    }
    
    public function tieneAcronimo() {
        return !empty($this->acronimo);
    }
}

class VistaAcronimo {
    public static function render(FormularioAcronimo $formulario) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Generador de Acrónimos</title>
            <link rel="stylesheet" href="../../styles.css">
        </head>
        <body>
            <nav class="menu">
                <a href="../index.php">Inicio</a>
                <a href="../ejercicios/acronimo">Acrónimos</a>
                <a href="../ejercicios/fibofact">Fibonacci/Factorial</a>
                <a href="../ejercicios/estadisticas">Estadísticas</a>
            </nav>
            
            <div class="container">
                <h1>Generador de Acrónimos</h1>
                <form method="POST">
                    <input type="text" name="frase" 
                           placeholder="Ej: Portable Network Graphics" 
                           value="<?= htmlspecialchars($formulario->getFrase()) ?>" 
                           required>
                    <button type="submit">Generar</button>
                </form>
                
                <?php if ($formulario->tieneAcronimo()): ?>
                    <div class="resultado">
                        <h3>Resultado:</h3>
                        <p><?= htmlspecialchars($formulario->getAcronimo()) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}


$formulario = new FormularioAcronimo();
$formulario->procesar();
VistaAcronimo::render($formulario);
?>