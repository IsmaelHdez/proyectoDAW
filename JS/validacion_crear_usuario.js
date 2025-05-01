document.getElementById("formulario").addEventListener("submit", async function(event) {
    event.preventDefault();

    let nombre_crear = document.getElementById("nombre").value.trim();
    let apellido_crear = document.getElementById("apellido").value.trim();
    let usuario_crear = document.getElementById("usuario").value.trim();
    let pass_crear = document.getElementById("pass").value.trim();
    let pass2_crear = document.getElementById("pass2").value.trim();
    let email_crear = document.getElementById("email").value.trim();
    let especialidad = document.getElementById("opciones").value.trim();

    let token = generarToken();
    setCookie("token", token, 1);

    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error");

    // ocultar mensaje
    mensajeError.style.display = "none";

    // Validación de campos vacíos
    if (usuario_crear === "" || pass_crear === "" || pass2_crear === "" || nombre_crear === "" || apellido_crear === "" || email_crear === "" || especialidad === "") {
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

    // Mostrar error si el nombre o apellido no son válidos
    if (especialidad == "Seleccione una opción") {
        mensajeError.textContent = "Selecciona una especialidad";
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

    // Obtener el archivo de imagen (si existe)
    let imageFile = document.getElementById("subir_foto").files[0];
    let base64Image = "";
    
    // Si se seleccionó un archivo de imagen, convertirlo a base64
    if (imageFile) {
        base64Image = await convertToBase64(imageFile);
    }

    // Si todas las validaciones pasaron
    if (validarNombre && validarApellido && validarCorreo && validarPass) {
        // Si todo es válido, enviamos la solicitud al servidor
        let respuesta = await fetch("../php/conexion.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                nombre_crear,
                apellido_crear,
                usuario_crear,
                nombre_crear,
                pass_crear,
                email_crear,
                especialidad,
                image: base64Image                
            })
        });

        let resultado = await respuesta.json();

        // Redirige según el tipo de usuario
        if (resultado.success) {
            window.location.href = resultado.redirect;
        } else {
            // Mostrar el mensaje de error del servidor
            mensajeError.textContent = resultado.message;
            mensajeError.style.display = "block";
        }
    }
});

// Función para convertir la imagen a base64
function convertToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result); // El resultado es la imagen en base64
        reader.onerror = reject;
        reader.readAsDataURL(file); // Lee el archivo y lo convierte a base64
    });
}

function generarToken() {
    return crypto.randomUUID(); // Genera un UUID único
}

function setCookie(nombre, valor, horas) {
    let fecha = new Date();
    fecha.setTime(fecha.getTime() + (horas * 60 * 60 * 1000)); // Convierte horas a milisegundos
    let expira = "expires=" + fecha.toUTCString();
    document.cookie = `${nombre}=${valor}; ${expira}; path=/`;
}
