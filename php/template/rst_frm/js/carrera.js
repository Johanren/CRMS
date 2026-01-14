function listarCarreraOption() {
    fetch("ajax.php?accion=listar_carrera_option&cod_emp=1")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("carrera")) {
                document.getElementById("carrera").innerHTML = data.option;
            }
        });
}
listarCarreraOption();
