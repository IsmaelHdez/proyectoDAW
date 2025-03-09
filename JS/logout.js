document.getElementById("cerrarSesion").addEventListener("click", async function() {
    // Crear la solicitud AJAX para cerrar sesión
    let respuesta = await fetch("../php/conexion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "cerrar_sesion" })
    });

    let resultado = await respuesta.json();

    if (resultado.success) {
        // Redirige a la página de inicio
        window.location.href = "index.php";
    } else {
        alert("Hubo un error al cerrar la sesión.");
    }
});