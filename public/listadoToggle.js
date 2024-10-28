// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
    // Selecciona todos los elementos con la clase 'botonToggle'
    const toggleButtons = document.querySelectorAll('.botonToggle');

    // Itera sobre cada botón
    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Obtiene el id del contenedor correspondiente
            const containerId = this.getAttribute('data-container-id');
            const container = document.getElementById(containerId);

            // Verifica si el contenedor existe
            if (container) {
                // Si el contenedor está visible, lo oculta
                if (container.style.display === 'flex') {
                    container.style.display = 'none';
                } else {
                    // Si está oculto, oculta todos los demás contenedores
                    const allContainers = document.querySelectorAll('.contenedorToggle');
                    allContainers.forEach(c => {
                        c.style.display = 'none'; // Oculta todos los contenedores
                    });
                    // Muestra el contenedor correspondiente
                    container.style.display = 'flex'; // Muestra el contenedor correspondiente
                }
            }
        });
    });
});