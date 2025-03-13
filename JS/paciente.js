function mostrarSeccion(id) {
    let secciones = document.getElementsByClassName("seccion");
    
    for (let i = 0; i < secciones.length; i++) {
        secciones[i].style.display = "none";
    }

    document.getElementById(id).style.display = "block";
}

document.addEventListener("DOMContentLoaded", function () {
    mostrarSeccion("ficha_paciente");
});

function mostrarFormulario() {
    document.getElementById('formulario_modificar_ficha').style.display = 'block';
}

function añadirMedicion() {

}

function eliminarMedicion() {

}

function modificarMedicion() {
    
}