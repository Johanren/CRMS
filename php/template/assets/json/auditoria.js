function listarAuditoria() {
    fetch("ajax/ajax.php?accion=listar_auditoria")
        .then(res => res.json())
        .then(data => {
            document.getElementById("audiencia").innerHTML = data.option;
        });
}

listarAuditoria();