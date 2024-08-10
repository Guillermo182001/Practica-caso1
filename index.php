<?php
// Se obtiene el nombre de la carpeta desde los parámetros de la URL
$carpetaNombre = isset($_GET['cod']) ? $_GET['cod'] : '';

// Se define la ruta a la carpeta donde se almacenarán los archivos subidos
$carpetaRuta = "./descarga/" . $carpetaNombre;

try {
    // Verifica si la carpeta no existe y la crea si es necesario
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    // Procesa el formulario de subida de archivos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['archivos'])) {
            $archivos = $_FILES['archivos'];

            // Si se han subido archivos se itera cada uno
            foreach ($archivos['tmp_name'] as $key => $tmp_name) {
                $nombreArchivo = $archivos['name'][$key];
                // Aqui se reemplaza los espacios en blanco por guiones bajos en el nombre del archivo utilizando str_replace
                $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
                $rutaDestino = $carpetaRuta . '/' . $nombreArchivo;

                // Mueve el archivo desde su ubicación temporal a la carpeta destino
                if (move_uploaded_file($tmp_name, $rutaDestino)) {
                    $subido = true;
                    $mensaje = "Archivo $nombreArchivo subido con éxito.";
                } else {
                    throw new Exception("Error al subir el archivo $nombreArchivo.");
                }
            }
        }

        // Si se ha enviado una solicitud para eliminar un archivo
        if (isset($_POST['eliminarArchivo'])) {
            $archivoAEliminar = $_POST['eliminarArchivo'];
            $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

            // Se verifica si el archivo existe y lo elimina
            if (file_exists($archivoRutaAEliminar)) {
                if (unlink($archivoRutaAEliminar)) {
                    $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
                } else {
                    throw new Exception("Error al eliminar el archivo.");
                }
            } else {
                throw new Exception("El archivo '$archivoAEliminar' no existe.");
            }
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}

// Genera tres caracteres aleatorios si no se proporciona un nombre en la URL
if (empty($_GET['cod'])) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $carpetaNombre = substr(str_shuffle($caracteres), 0, 3);
    header("Location: ?cod=$carpetaNombre"); // Redirige a la URL con los tres caracteres generados
    exit;
} else {
    $carpetaNombre = $_GET['cod'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <script src="parametro.js"></script>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- Contenedor principal -->
    <div class="content">
    <h4>Sube tus archivos y comparte este enlace temporal: <span>g-dom.net.pe/?cod=<?php echo htmlspecialchars($carpetaNombre); ?></span></h4>
        <div class="container">
            <!-- Área de arrastre para subir archivos -->
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                        <path d="M13 19v-4h3l-4-5-4 5h3v4z"></path>
                        <path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path>
                    </svg> 
                    <input type="file" class="file-input" name="archivos[]" id="archivo" multiple>
                    <button type="button" class="browse-btn" onclick="document.getElementById('archivo').click();">Subir</button>
                    <br><label> Arrastra tus archivos aquí o</label>
                    <p><b>Abre el explorador</b></p></br>
                </form>
            </div>

            <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
            
            <div class="container2">
                <div id="file-list" class="pila">
                    <?php
                    // Lee los archivos en la carpeta especificada
                    $targetDir = $carpetaRuta;
                    $files = scandir($targetDir);
                    $files = array_diff($files, array('.', '..')); // Elimina las entradas '.' y '..'

                    if (count($files) > 0) {
                        echo "<h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                        // Muestra cada archivo en una lista
                        foreach ($files as $file) {
                            echo "<div class='archivos_subidos'>
                                <div><a href='$carpetaRuta/$file' download class='boton-descargar'>$file</a></div>
                                <div>
                                    <form action='' method='POST' style='display:inline;'>
                                        <input type='hidden' name='eliminarArchivo' value='$file'>
                                        <button type='submit' class='btn_delete'>
                                            <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='1.5' stroke='#ff2825' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                                <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                                <line x1='4' y1='7' x2='20' y2='7' />
                                                <line x1='10' y1='11' x2='10' y2='17' />
                                                <line x1='14' y1='11' x2='14' y2='17' />
                                                <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                                <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<p>No hay archivos subidos aún.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
