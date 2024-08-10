// Espera a que el contenido del DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Se obtiene los parámetros de la URL
    const params = new URLSearchParams(window.location.search);
    const nombre = params.get('cod');

    if (nombre) {
        // Crea un input oculto con el nombre de la carpeta
        const carpetaInput = document.createElement('input');
        carpetaInput.type = 'hidden';
        carpetaInput.name = 'nombreCod';
        carpetaInput.value = nombre;

        // Añade el input oculto al formulario
        const form = document.getElementById('form');
        form.appendChild(carpetaInput);
    }
});
