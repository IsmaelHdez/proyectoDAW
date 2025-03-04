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
    
    // Función para validar textos con menos de 3 caracteres
    function validar_Entrada(correo) {
            const emailRegex = /^[^\s@]{3,}$/;
            return emailRegex.test(correo);
        }

//Validación de crear nutricionista 
document.getElementById("crear_nutricionista").addEventListener("submit", async function(event) {
    event.preventDefault();

    let usuario = document.getElementById("usuario_nutricionista").value.trim();
    let pass = document.getElementById("pass_nutricionista").value.trim();
    let nombre = document.getElementById("nombre_nutricionista").value.trim();
    let apellido = document.getElementById("apellido_nutricionista").value.trim();
    let email = document.getElementById("email_nutricionista").value.trim();

    if (usuario === "" || pass === "" || nombre === "" || apellido === "" || email === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    var validarNombre = validar_Nombre(nombre);
    var validarApellido = validar_Nombre(apellido);
    var validarCorreo = validar_Correo(email);
    var validarLongitudUsuario = validar_Entrada(usuario);
    var validarLongitudNombre = validar_Entrada(nombre);
    var validarLongitudApellidos = validar_Entrada(apellido);
    
    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error_crear_nutricionista");

    // ocultar mensaje
    mensajeError.style.display = "none";

    // Mostrar error si el nombre o apellido no son válidos
    if (!validarNombre || !validarApellido) {
        mensajeError.textContent = "El nombre y el apellido no pueden contener números";
        mensajeError.style.display = "block";
        return;
    }

    // Mostrar error si el usuario , nombre o apellido tienene una longitud inferior a 3 caracteres
    if (!validarLongitudUsuario || !validarLongitudNombre || !validarLongitudApellidos) {
        mensajeError.textContent = "El usuario , nombre o apellido debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return;
    }   

    // Mostrar error si el correo no es válido
    if (!validarCorreo) {
        mensajeError.textContent = "Por favor ingresa un correo válido";
        mensajeError.style.display = "block";
        return;
    }
});

//Validación de crear nutricionista 
document.getElementById("modificar_nutricionista").addEventListener("submit", async function(event) {
    event.preventDefault();

    let usuario = document.getElementById("usuario_nutricionista_mod").value.trim();
    let pass = document.getElementById("pass_nutricionista_mod").value.trim();
    let nombre = document.getElementById("nombre_nutricionista_mod").value.trim();
    let apellido = document.getElementById("apellido_nutricionista_mod").value.trim();
    let email = document.getElementById("email_nutricionista_mod").value.trim();

    if (usuario === "" || pass === "" || nombre === "" || apellido === "" || email === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    var validarNombre = validar_Nombre(nombre);
    var validarApellido = validar_Nombre(apellido);
    var validarCorreo = validar_Correo(email);
    var validarLongitudUsuario = validar_Entrada(usuario);
    var validarLongitudNombre = validar_Entrada(nombre);
    var validarLongitudApellidos = validar_Entrada(apellido);
    
    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error_mod_nutricionista");

    // ocultar mensaje
    mensajeError.style.display = "none";

    // Mostrar error si el nombre o apellido no son válidos
    if (!validarNombre || !validarApellido) {
        mensajeError.textContent = "El nombre y el apellido no pueden contener números";
        mensajeError.style.display = "block";
        return;
    }

    // Mostrar error si el usuario , nombre o apellido tienene una longitud inferior a 3 caracteres
    if (!validarLongitudUsuario || !validarLongitudNombre || !validarLongitudApellidos) {
        mensajeError.textContent = "El usuario , nombre o apellido debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return;
    }   

    // Mostrar error si el correo no es válido
    if (!validarCorreo) {
        mensajeError.textContent = "Por favor ingresa un correo válido";
        mensajeError.style.display = "block";
        return;
    }
});

//Validación de crear paciente 
document.getElementById("crear_paciente").addEventListener("submit", async function(event) {
    event.preventDefault();

    let usuario = document.getElementById("usuario_paciente").value.trim();
    let pass = document.getElementById("pass_paciente").value.trim();
    let nombre = document.getElementById("nombre_paciente").value.trim();
    let apellido = document.getElementById("apellido_paciente").value.trim();
    let email = document.getElementById("email_paciente").value.trim();

    if (usuario === "" || pass === "" || nombre === "" || apellido === "" || email === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    var validarNombre = validar_Nombre(nombre);
    var validarApellido = validar_Nombre(apellido);
    var validarCorreo = validar_Correo(email);
    var validarLongitudUsuario = validar_Entrada(usuario);
    var validarLongitudNombre = validar_Entrada(nombre);
    var validarLongitudApellidos = validar_Entrada(apellido);
    
    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error_crear_paciente");

    // ocultar mensaje
    mensajeError.style.display = "none";

    // Mostrar error si el nombre o apellido no son válidos
    if (!validarNombre || !validarApellido) {
        mensajeError.textContent = "El nombre y el apellido no pueden contener números";
        mensajeError.style.display = "block";
        return;
    }

    // Mostrar error si el usuario , nombre o apellido tienene una longitud inferior a 3 caracteres
    if (!validarLongitudUsuario || !validarLongitudNombre || !validarLongitudApellidos) {
        mensajeError.textContent = "El usuario , nombre o apellido debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return;
    }   

    // Mostrar error si el correo no es válido
    if (!validarCorreo) {
        mensajeError.textContent = "Por favor ingresa un correo válido";
        mensajeError.style.display = "block";
        return;
    }
});

//Validación de modificar paciente 
document.getElementById("modificar_paciente").addEventListener("submit", async function(event) {
    event.preventDefault();

    let usuario = document.getElementById("usuario_paciente_mod").value.trim();
    let pass = document.getElementById("pass_paciente_mod").value.trim();
    let nombre = document.getElementById("nombre_paciente_mod").value.trim();
    let apellido = document.getElementById("apellido_paciente_mod").value.trim();
    let email = document.getElementById("email_paciente_mod").value.trim();

    if (usuario === "" || pass === "" || nombre === "" || apellido === "" || email === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    var validarNombre = validar_Nombre(nombre);
    var validarApellido = validar_Nombre(apellido);
    var validarCorreo = validar_Correo(email);
    var validarLongitudUsuario = validar_Entrada(usuario);
    var validarLongitudNombre = validar_Entrada(nombre);
    var validarLongitudApellidos = validar_Entrada(apellido);
    
    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error_mod_paciente");

    // ocultar mensaje
    mensajeError.style.display = "none";

    // Mostrar error si el nombre o apellido no son válidos
    if (!validarNombre || !validarApellido) {
        mensajeError.textContent = "El nombre y el apellido no pueden contener números";
        mensajeError.style.display = "block";
        return;
    }

    // Mostrar error si el usuario , nombre o apellido tienene una longitud inferior a 3 caracteres
    if (!validarLongitudUsuario || !validarLongitudNombre || !validarLongitudApellidos) {
        mensajeError.textContent = "El usuario , nombre o apellido debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return;
    }   

    // Mostrar error si el correo no es válido
    if (!validarCorreo) {
        mensajeError.textContent = "Por favor ingresa un correo válido";
        mensajeError.style.display = "block";
        return;
    }
});