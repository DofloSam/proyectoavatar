$(document).ready(function() {
    // Configuración
    const config = {
        maxSize: 5 * 1024 * 1024, // 5MB
        validTypes: ['image/jpeg', 'image/png', 'image/gif'],
        previewElement: '#avatarPreview',
        fileInput: '#avatar',
        errorMessages: {
            size: 'El archivo excede el límite de 5MB',
            type: 'Solo se permiten imágenes JPG, PNG o GIF'
        }
    };

    // Elementos del DOM
    const $avatarInput = $(config.fileInput);
    const $avatarPreview = $(config.previewElement);
    const $mensajeError = $('#mensaje-error-avatar');

    // Función para mostrar errores
    function mostrarError(mensaje) {
        if ($mensajeError.length) {
            $mensajeError.text(mensaje).show();
        } else {
            // Crear elemento de mensaje si no existe
            $('<div id="mensaje-error-avatar" class="error-message"></div>')
                .text(mensaje)
                .insertAfter($avatarInput)
                .delay(3000)
                .fadeOut();
        }
        
        $avatarInput.val('');
        $avatarPreview.hide();
    }

    // Evento change para el input de archivo
    $avatarInput.on('change', function(e) {
        const file = this.files[0];
        
        if (!file) {
            $avatarPreview.hide();
            return;
        }

        // Validar tamaño del archivo
        if (file.size > config.maxSize) {
            mostrarError(config.errorMessages.size);
            return;
        }

        // Validar tipo de archivo
        if (!config.validTypes.includes(file.type)) {
            mostrarError(config.errorMessages.type);
            return;
        }

        // Ocultar mensaje de error si existe
        if ($mensajeError.length) $mensajeError.hide();

        // Mostrar vista previa
        const reader = new FileReader();
        
        reader.onloadstart = function() {
            // Mostrar loader si es necesario
            $avatarPreview.hide();
        };
        
        reader.onload = function(e) {
            $avatarPreview
                .attr('src', e.target.result)
                .css({
                    'display': 'block',
                    'max-width': '100%',
                    'height': 'auto'
                });
        };
        
        reader.onerror = function() {
            mostrarError('Error al leer el archivo');
        };
        
        reader.readAsDataURL(file);
    });

    // Resetear al hacer clic en el avatar
    $avatarPreview.on('click', function() {
        $avatarInput.val('');
        $(this).hide();
    });
});