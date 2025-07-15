<script>
document.querySelector("form").addEventListener("submit", function(event) {
    let nickname = document.querySelector("[name='nickname']").value;
    let email = document.querySelector("[name='email']").value;
    let password = document.querySelector("[name='contraseña']").value;
    
    if (nickname.length < 3) {
        alert("El nickname debe tener al menos 3 caracteres.");
        event.preventDefault(); // Detener el envío si hay error
    }

    if (password.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        event.preventDefault();
    }

    if (!email.includes("@")) {
        alert("Introduce un correo válido.");
        event.preventDefault();
    }

    // Si todo es correcto, limpiar el formulario tras el registro
    setTimeout(() => {
        document.querySelector("form").reset();
    }, 3000);
});
</script>
