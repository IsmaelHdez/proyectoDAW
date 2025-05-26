function mostrarSeccion(id) {
    let secciones = document.getElementsByClassName("seccion");

    for (let i = 0; i < secciones.length; i++) {
        secciones[i].style.display = "none";
        secciones[i].classList.remove("active");
    }

    const activa = document.getElementById(id);
    if (activa) {
        activa.style.display = "block";
        activa.classList.add("active");
    } else {
        console.warn("No se encontró la sección:", id);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    let seccionGuardada = localStorage.getItem('seccion_activa');

    if (seccionGuardada) {
        mostrarSeccion(seccionGuardada);
        localStorage.removeItem('seccion_activa');
    } else {
        mostrarSeccion("ficha_paciente");
    }

    const guardarMedicionBtn = document.getElementById('guardar_medicion');
    if (guardarMedicionBtn) {
        guardarMedicionBtn.addEventListener('click', function () {
            localStorage.setItem('seccion_activa', 'mediciones_paciente');
        });
    }
});

function mostrarFormulario() {
    const form = document.getElementById('formulario_modificar_ficha');
    if (form) form.style.display = 'block';
}

function añadirMedicion() {
    const form = document.getElementById('formulario_añadir_medicion');
    if (form) form.style.display = 'block';
}

function mostrarFormularioModificar(id, fecha, hora) {
    const idInput = document.getElementById("editar_id_cita");
    const fechaInput = document.getElementById("editar_fecha");
    const horaInput = document.getElementById("editar_hora");
    const form = document.getElementById("formulario_editar_cita");

    if (idInput && fechaInput && horaInput && form) {
        idInput.value = id;
        fechaInput.value = fecha;
        horaInput.value = hora;
        form.style.display = "block";
        window.scrollTo({
            top: form.offsetTop - 50,
            behavior: 'smooth'
        });
    }
}

function mostrarFormularioNuevaCita() {
    const form = document.getElementById("formulario_nueva_cita");
    if (form) {
        form.style.display = "block";
        window.scrollTo({
            top: form.offsetTop - 50,
            behavior: 'smooth'
        });
    }
}
