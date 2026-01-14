function listarUserOption() {
    fetch("ajax.php?accion=listar_user_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("user")) {
                document.getElementById("user").innerHTML = data.option;
            }
        });
}

listarUserOption();