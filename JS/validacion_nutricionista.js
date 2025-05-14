/******************VALIDACION DE NUTRICIONISTA:PHP*************************************************/
// Función para validar nombres o apellidos
function validar_Nombre(nombre) {
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    return regex.test(nombre);
}

//Función para validar sólo el contenido con números
function validar_Numeros(numero) {
    const regex = /^[0-9]+$/;
    return regex.test(numero);
}

// Función para validar correos electrónicos
function validar_Correo(correo) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(correo);
}

function validar_Entrada(texto) {
    const textRegex = /^[a-zA-Z\sñÑ]{3,}$/; // Permite letras y espacios, mínimo 3 caracteres
    return textRegex.test(texto);
}

  // Función para validar textos con menos de 10 caracteres
function validar_Entrada_Ingredientes(texto) {
    const textRegex = /^.{10,}$/;
    return textRegex.test(texto);
}

//función para validar formulario de crear paciente  
function validar_crear_paciente(){
        
let usuario = document.getElementById("usuario_paciente").value.trim();
let pass = document.getElementById("pass_paciente").value.trim();
let nombre = document.getElementById("nombre_paciente").value.trim();
let apellido = document.getElementById("apellido_paciente").value.trim();
let email = document.getElementById("email_paciente").value.trim();

    console.log(document.getElementById("usuario_paciente").value);
    console.log(document.getElementById("pass_paciente").value);
    console.log(document.getElementById("nombre_paciente").value);
    console.log(document.getElementById("apellido_paciente").value);
    console.log(document.getElementById("email_paciente").value);


if (usuario === "" || pass === "" || nombre === "" || apellido === "" || email === "") {
    alert("Todos los campos son obligatorios");
    return false;
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
    return false;
}

// Mostrar error si el usuario , nombre o apellido tienene una longitud inferior a 3 caracteres
if (!validarLongitudUsuario || !validarLongitudNombre || !validarLongitudApellidos) {
    mensajeError.textContent = "El usuario , nombre o apellido debe de tener 3 o más caracteres";
    mensajeError.style.display = "block";
    return false;
}   

// Mostrar error si el correo no es válido
if (!validarCorreo) {
    mensajeError.textContent = "Por favor ingresa un correo válido";
    mensajeError.style.display = "block";
    return false;
}
return true;
};


//función para validar formulario de mod paciente
function validar_mod_paciente(){
    
    let usuario = document.getElementById("usuario_paciente_mod").value.trim();
    let pass = document.getElementById("pass_paciente_mod").value.trim();
    let nombre = document.getElementById("nombre_paciente_mod").value.trim();
    let apellido = document.getElementById("apellido_paciente_mod").value.trim();
    let email = document.getElementById("email_paciente_mod").value.trim();
    
    if (usuario === "" || pass === "" || nombre === "" || apellido === "" || email === "") {
        alert("Todos los campos son obligatorios");
        return false;
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
        return false;
    }
    
    // Mostrar error si el usuario , nombre o apellido tienene una longitud inferior a 3 caracteres
    if (!validarLongitudUsuario || !validarLongitudNombre || !validarLongitudApellidos) {
        mensajeError.textContent = "El usuario , nombre o apellido debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return false;
    }   
    
    // Mostrar error si el correo no es válido
    if (!validarCorreo) {
        mensajeError.textContent = "Por favor ingresa un correo válido";
        mensajeError.style.display = "block";
        return false;
    }
    return true;
};

//función para validar formulario de crear receta
function validar_crear_receta(){
    
    let nombre = document.getElementById("nombre_receta").value.trim();
    let calorias = document.getElementById("calorias_receta").value.trim();
    let ingredientes = document.getElementById("ingredientes_receta").value.trim();

    
    if (nombre === "" || calorias === "" || ingredientes === "" ) {
        alert("Todos los campos son obligatorios");
        return false;
    }
    
    var validarNombre = validar_Nombre(nombre);
    var validarLongitudNombre = validar_Entrada(nombre);
    var validarNumeros = validar_Numeros(calorias);
    var validarIngredientes = validar_Entrada_Ingredientes(ingredientes);
    
    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error_crear_receta");
    
    // ocultar mensaje
    mensajeError.style.display = "none";
    
    // Mostrar error si el nombre no es válido
    if (!validarNombre ) {
        mensajeError.textContent = "El nombre no puede contener números";
        mensajeError.style.display = "block";
        return false;
    }
    
    // Mostrar error si los ingredientes contienen menos de 10 caracteres
    if (!validarIngredientes) {
        mensajeError.textContent = "Los ingredientes deben de contener más de 10 caracteres";
        mensajeError.style.display = "block";
        return false;
    }

    // Mostrar error si las calorías no están formadas por números
    if (!validarNumeros ) {
        mensajeError.textContent = "Indique las calorías por ración con números";
        mensajeError.style.display = "block";
        return false;
    }

    // Mostrar error si el nombre tiene una longitud inferior a 3 caracteres
    if (!validarLongitudNombre ) {
        mensajeError.textContent = "El nombre debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return false;
    }   
    
    
    return true;
};

//función para validar formulario de modificar receta
function validar_mod_receta(){
    
    let nombre = document.getElementById("nombre_receta_mod").value.trim();
    let calorias = document.getElementById("calorias_receta_mod").value.trim();
    let ingredientes = document.getElementById("ingredientes_receta_mod").value.trim();

    
    if (nombre === "" || calorias === "" || ingredientes === "" ) {
        alert("Todos los campos son obligatorios");
        return false;
    }
    
    var validarNombre = validar_Nombre(nombre);
    var validarLongitudNombre = validar_Entrada(nombre);
    var validarNumeros = validar_Numeros(calorias);
    var validarIngredientes = validar_Entrada_Ingredientes(ingredientes);
    
    // Mensaje error validacion
    let mensajeError = document.getElementById("mensaje_error_mod_receta");
    
    // ocultar mensaje
    mensajeError.style.display = "none";
    
    // Mostrar error si el nombre no es válido
    if (!validarNombre ) {
        mensajeError.textContent = "El nombre no puede contener números";
        mensajeError.style.display = "block";
        return false;
    }
    
    // Mostrar error si los ingredientes contienen menos de 10 caracteres
    if (!validarIngredientes) {
        mensajeError.textContent = "Los ingredientes deben de contener más de 10 caracteres";
        mensajeError.style.display = "block";
        return false;
    }

    // Mostrar error si las calorías no están formadas por números
    if (!validarNumeros ) {
        mensajeError.textContent = "Indique las calorías por ración con números";
        mensajeError.style.display = "block";
        return false;
    }

    // Mostrar error si el nombre tiene una longitud inferior a 3 caracteres
    if (!validarLongitudNombre ) {
        mensajeError.textContent = "El nombre debe de tener 3 o más caracteres";
        mensajeError.style.display = "block";
        return false;
    }   
    
    
    return true;
};

//Validación de crear paciente
document.getElementById("formulario_crear_paciente").addEventListener("submit", function(event) {
    if (!validar_crear_paciente()) {
        event.preventDefault(); // Detiene el envío del formulario si la validación falla
    }
});

//Validación de mod paciente 
document.getElementById("formulario_mod_paciente").addEventListener("submit", function(event) {
    if (!validar_mod_paciente()) {
        event.preventDefault(); // Detiene el envío del formulario si la validación falla
    }
});

//Validación de crear receta
document.getElementById("formulario_crear_receta").addEventListener("submit", function(event) {
    if (!validar_crear_receta()) {
        event.preventDefault(); // Detiene el envío del formulario si la validación falla
    }
});

//Validación de modificar receta
document.getElementById("formulario_mod_receta").addEventListener("submit", function(event) {
    if (!validar_mod_receta()) {
        event.preventDefault(); // Detiene el envío del formulario si la validación falla
    }
});
/*************************************************************************************************/

function mostrarSeccion(id, tipo) {
    let secciones = document.getElementsByClassName("seccion");

    // Ocultar todas las secciones
    for (let i = 0; i < secciones.length; i++) {
        secciones[i].style.display = "none";
    }
    // Mostrar la sección especificada
    document.getElementById(id).style.display = "block";

    // Mostrar la tabla correcta según el tipo
    let tabla = document.getElementById(tipo);
    if (tabla) {
        tabla.style.display = "block";
    }

    if (tipo === "contenedor_tabla_paciente") {
        document.getElementById("ficha_nutricionista").style.display = "none";
        document.getElementById("div_pacientes").style.display = "block";
        document.getElementById("div_recetas").style.display = "none";
        document.getElementById("div_calendario").style.display = "none";
        document.getElementById("div_citas").style.display = "none";
    } else if (tipo === "tabla_contenedor_recetas") {
        document.getElementById("ficha_nutricionista").style.display = "none";
        document.getElementById("div_pacientes").style.display = "none";
        document.getElementById("div_recetas").style.display = "block";
        document.getElementById("div_calendario").style.display = "none";
        document.getElementById("div_citas").style.display = "none";
    } else if (tipo === "tabla_calendario") {
        document.getElementById("ficha_nutricionista").style.display = "none";
        document.getElementById("div_pacientes").style.display = "none";
        document.getElementById("div_recetas").style.display = "none";
        document.getElementById("div_calendario").style.display = "block";
        document.getElementById("div_citas").style.display = "none";
    } else if (tipo === "tabla_citas") {
        document.getElementById("ficha_nutricionista").style.display = "none";
        document.getElementById("div_pacientes").style.display = "none";
        document.getElementById("div_recetas").style.display = "none";
        document.getElementById("div_calendario").style.display = "none";
        document.getElementById("div_citas").style.display = "block";
    } else if (tipo === "ficha_nutricionista") {
        document.getElementById("ficha_nutricionista").style.display = "block";
        document.getElementById("div_pacientes").style.display = "none";
        document.getElementById("div_recetas").style.display = "none";
        document.getElementById("div_calendario").style.display = "none";
        document.getElementById("div_citas").style.display = "none";
    }

    // Guardar la sección activa en localStorage
    localStorage.setItem("seccion_activa", id);
    localStorage.setItem("tabla_activa", tipo);
}

document.addEventListener("DOMContentLoaded", function () {
    let seccionGuardada = localStorage.getItem('seccion_activa');
    let tablaGuardada = localStorage.getItem('tabla_activa');

    if (seccionGuardada && tablaGuardada) {
        mostrarSeccion(seccionGuardada,tablaGuardada); // Mostrar la sección guardada
    } else {
        mostrarSeccion("crear_paciente","contenedor_tabla_paciente");
    }
});

function mostrarFormulario() {
    document.getElementById('formulario_modificar_ficha').style.display = 'block';
}

//función para abrir menú
$(document).ready(function(){
    $(".menu-btn").click(function(){
        var target = $(this).data("target");
        $(".submenu").not(target).slideUp(); // Cierra otros submenús
        $(target).slideToggle();
    });
});
    // Cierra el submenú cuando se hace clic en una opción
    $(".submenu li").click(function(){
        $(this).parent().slideUp();
    });

//función para borrar el localStorage    
document.getElementById("cerrarSesion").addEventListener("click", function() {
       localStorage.removeItem("seccion_activa");
       localStorage.removeItem("tabla_activa");
});

$(document).ready(function () {
    // Mostrar el panel al hacer clic en el botón de abrir
    $('#toggle-panel').on('click', function () {
      $('#side-panel').addClass('open');
    });

    // Ocultar el panel al hacer clic en el botón de cerrar
    $('#close-panel').on('click', function () {
      $('#side-panel').removeClass('open');
    });
  });