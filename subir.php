<?php
// Se obtiene el nombre de la carpeta desde el formulario
$carpetaNombre = isset($_POST['nombreCod']) ? $_POST['nombreCod'] : '';
$carpetaRuta = "./descarga/" . $carpetaNombre;

try {
    // Verifica si la carpeta no existe y la crea si es necesario
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
    }

    // Procesa los archivos subidos
    if (isset($_FILES['archivos'])) {
        $archivos = $_FILES['archivos'];

        // Itera sobre los archivos subidos
        foreach ($archivos['tmp_name'] as $key => $tmp_name) {
            $nombreArchivo = $archivos['name'][$key];
            $rutaDestino = $carpetaRuta . '/' . $nombreArchivo;

            // Mueve el archivo desde su ubicación temporal a la carpeta de destino
            if (move_uploaded_file($tmp_name, $rutaDestino)) {
                echo "Archivo $nombreArchivo subido con éxito.<br>";
            } else {
                throw new Exception("Error al subir el archivo $nombreArchivo.");
            }
        }
    } else {
        throw new Exception("No se han recibido archivos.");
    }
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>
