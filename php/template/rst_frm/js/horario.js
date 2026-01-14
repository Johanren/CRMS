function listarHrsOption() {
    fetch("ajax.php?accion=listar_hrs_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("horario")) {
                document.getElementById("horario").innerHTML = data.option;
            }
        });
}
listarHrsOption();
