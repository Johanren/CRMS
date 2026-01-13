document.getElementById("btnLogin").addEventListener("click", async(e) => {
    e.preventDefault();

    const email = document.getElementById("emailUser").value;
    const password = document.getElementById("passwordUser").value;

    const data = new FormData();
    data.append("accion", "login");
    data.append("email", email);
    data.append("password", password);

    const response = await fetch("ajax/ajax.php", {
        method: "POST",
        body: data
    });

    const result = await response.json();

    if (result.status === "ok") {

        // Redirección por rol
        /*if (result.rol === "Asesor") {
            window.location.href = "leads.php";
        } else if (result.rol === "Admin") {
            window.location.href = "dashboard.php";
        } else {
            window.location.href = "index.php";
        }*/
       window.location.href = "resultado_foco.php";

    } else {
        alert("Credenciales incorrectas");
    }
});