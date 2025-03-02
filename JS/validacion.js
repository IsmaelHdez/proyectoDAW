document.getElementById("formulario").addEventListener("submit", async function(event) {
    event.preventDefault();

    let usuario = document.getElementById("text_usuario").value.trim();
    let pass = document.getElementById("text_pass").value.trim();

    if (usuario === "" || pass === "") {
        alert("Todos los campos son obligatorios");
        return;
    }

    let respuesta = await fetch("../php/conexion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario, pass })
    });

    let resultado = await respuesta.json();

    // Referencia al mensaje de error
    let mensajeError = document.getElementById("mensaje_error");

     // Redirige según el tipo de usuario
    if (resultado.success) {
        window.location.href = resultado.redirect;
    } else {
        // Mostrar el mensaje de error
        mensajeError.textContent = resultado.message;
        mensajeError.style.display = "block"; 
    }
});
