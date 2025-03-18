function mostrarSeccion(id) {
    let secciones = document.getElementsByClassName("seccion");
    
    for (let i = 0; i < secciones.length; i++) {
        secciones[i].style.display = "none";
    }

    document.getElementById(id).style.display = "block";
}

document.addEventListener("DOMContentLoaded", function () {
    let seccionGuardada = localStorage.getItem('seccion_activa');

    if (seccionGuardada) {
        mostrarSeccion(seccionGuardada); // Mostrar la sección guardada
        localStorage.removeItem('seccion_activa'); // Eliminar el registro para futuras cargas
    } else {
        mostrarSeccion("ficha_paciente"); // Si no hay sección guardada, mostrar la predeterminada
    }
});

function mostrarFormulario() {
    document.getElementById('formulario_modificar_ficha').style.display = 'block';
}

function añadirMedicion() {
    document.getElementById('formulario_añadir_medicion').style.display = 'block';
}

document.getElementById('guardar_medicion').addEventListener('click', function() {
    localStorage.setItem('seccion_activa', 'mediciones_paciente'); // Guardar la sección activa antes de enviar el formulario
});
