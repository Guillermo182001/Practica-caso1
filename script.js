// Espera a que el contenido del DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form');
    const fileInput = document.getElementById('archivo');
    const dropArea = document.getElementById('drop-area');

    // Maneja los archivos seleccionados o arrastrados
    function handleFiles(files) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('archivos[]', files[i]);
        }

        if (files.length > 0) {
            // Envía los archivos al servidor usando AJAX
            fetch('', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(response => {
                console.log('Archivos subidos con éxito:', response);
                // Recarga la página después de la carga exitosa
                location.reload();
            })
            .catch(error => {
                console.error('Error al subir los archivos:', error);
            });
        } else {
            alert('Por favor, seleccione archivos primero.');
        }
    }

    // Maneja el evento de cambio en el input de archivos
    fileInput.addEventListener('change', () => {
        handleFiles(fileInput.files);
    });

    // Maneja el envío del formulario
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        handleFiles(fileInput.files);
    });

    // Maneja los eventos de arrastre en el área de arrastre
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('drag-over');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('drag-over');
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('drag-over');
        handleFiles(e.dataTransfer.files);
    });
});
