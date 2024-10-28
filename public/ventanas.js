// index.js
class ToggleVisibility {
    constructor() {
        this.init();
    }

    aparecer(contenedor) {
        contenedor.classList.add('activado');
    }

    desaparecer(contenedor) {
        contenedor.classList.remove('activado');
    }

    init() {
        const activadores = document.querySelectorAll('.activador');

        activadores.forEach(activador => {
            const accion = activador.getAttribute('data-accion'); 
            const contenedorID = activador.getAttribute('data-contenedor'); 
            const contenedor = document.getElementById(contenedorID);

            activador.addEventListener('click', () => {
                if (accion === 'aparecer') {
                    this.aparecer(contenedor);
                } else if (accion === 'desaparecer') {
                    this.desaparecer(contenedor);
                }
            });
        });
    }
}

// Instancia de la clase para que funcione
const toggleVisibility = new ToggleVisibility();