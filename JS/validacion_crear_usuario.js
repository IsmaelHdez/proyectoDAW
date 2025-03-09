document.getElementById("formulario").addEventListener("submit", async function(event) {
    event.preventDefault();

    let nombre_crear = document.getElementById("nombre").value.trim();
    let apellido_crear = document.getElementById("apellido").value.trim();
    let usuario_crear = document.getElementById("usuario").value.trim();
    let pass_crear = document.getElementById("pass").value.trim();
    let pass2_crear = document.getElementById("pass2").value.trim();
    let email_crear = document.getElementById("email").value.trim();

    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error");

    // ocultar mensaje
    mensajeError.style.display = "none";

    // Validación de campos vacíos
    if (usuario_crear === "" || pass_crear === "" || pass2_crear === "" || nombre_crear === "" || apellido_crear === "" || email_crear === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    // Función para validar nombres o apellidos
    function validar_Nombre(nombre) {
        const regex = /^[a-zA-Z\s]+$/;
        return regex.test(nombre);
    }

    // Función para validar correos electrónicos
    function validar_Correo(correo) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(correo);
    }

    // Función para validar contraseñas
    function validar_pass(pass, pass2) {
        return pass === pass2;
    }

    // Valida el nombre, apellido, correo y contraseñas
    var validarNombre = validar_Nombre(nombre_crear);
    var validarApellido = validar_Nombre(apellido_crear);
    var validarCorreo = validar_Correo(email_crear);
    var validarPass = validar_pass(pass_crear, pass2_crear);

    // Mostrar error si el nombre o apellido no son válidos
    if (!validarNombre || !validarApellido) {
        mensajeError.textContent = "El nombre y el apellido no pueden contener números";
        mensajeError.style.display = "block";
        return;
    }

    // Mostrar error si el correo no es válido
    if (!validarCorreo) {
        mensajeError.textContent = "Por favor ingresa un correo válido";
        mensajeError.style.display = "block";
        return;
    }

    // Mostrar error si las contraseñas no coinciden
    if (!validarPass) {
        mensajeError.textContent = "Las contraseñas no coinciden";
        mensajeError.style.display = "block";
        return;
    }

    // Si todas las validaciones pasaron
    if (validarNombre && validarApellido && validarCorreo && validarPass) {
        // Si todo es válido, enviamos la solicitud al servidor
        let respuesta = await fetch("../php/conexion.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ usuario_crear, pass_crear, pass2_crear, nombre_crear, apellido_crear, email_crear })
        });

        let resultado = await respuesta.json();

        // Redirige según el tipo de usuario
        if (resultado.success) {
            let token = generarToken();
            setCookie("token", token, 1);
            window.location.href = resultado.redirect;
        } else {
            // Mostrar el mensaje de error del servidor
            mensajeError.textContent = resultado.message;
            mensajeError.style.display = "block";
        }
    }
});

function generarToken() {
    return crypto.randomUUID(); // Genera un UUID único
}

function setCookie(nombre, valor, horas) {
    let fecha = new Date();
    fecha.setTime(fecha.getTime() + (horas * 60 * 60 * 1000)); // Convierte horas a milisegundos
    let expira = "expires=" + fecha.toUTCString();
    document.cookie = `${nombre}=${valor}; ${expira}; path=/`;
}