function cargarNotificaciones() {
    const datos = new FormData();
    datos.append("accion", "listar");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(data => {
            const contenedor = document.getElementById("listaNotificaciones");
            contenedor.innerHTML = "";

            if (data.length === 0) {
                contenedor.innerHTML = "<p class='text-center'>No hay notificaciones</p>";
                return;
            }

            data.forEach(n => {
                const ref = JSON.parse(n.referencia);
                const url = `${n.modulo}?id=${ref.id}&id_cliente=${ref.id_cliente}`;

                contenedor.innerHTML += `
            <div class="card notication-card mb-2 ${n.leida == 0 ? 'border-danger' : ''}">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <p class="fw-medium mb-1">${n.titulo}</p>
                        <p class="mb-0">${n.mensaje}</p>
                        <small><i class="ti ti-clock"></i> ${n.fecha}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary"
                            onclick="verNotificacion(${n.id_notificacion}, '${url}')">
                            Ver
                        </button>
                    </div>
                </div>
            </div>`;
            });
        });
}

function cargarTopbarNotificaciones() {
    const datos = new FormData();
    datos.append("accion", "listar_limit");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(data => {
            const body = document.querySelector(".notification-body");
            body.innerHTML = "";

            data.forEach(n => {
                const ref = JSON.parse(n.referencia);
                const url = `${n.modulo}?id=${ref.id}&id_cliente=${ref.id_cliente}`;

                body.innerHTML += `
            <div class="dropdown-item notification-item py-3 border-bottom">
                <p class="fw-medium mb-0">${n.titulo}</p>
                <p class="mb-1">${n.mensaje}</p>
                <a href="javascript:void(0);" onclick="verNotificacion(${n.id_notificacion}, '${url}')">
                    Ver
                </a>
            </div>`;
            });
        });
}

function actualizarBadge() {
    const datos = new FormData();
    datos.append("accion", "contador");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(total => {
            document.querySelector(".badge").innerText = total;
        });
}

function verNotificacion(id, url) {

    const datos = new FormData();
    datos.append("accion", "marcar_leida");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(resp => {

            if (resp.ok) {
                // Redirige al módulo correspondiente
                window.location.href = url;
            } else {
                console.error("No se pudo marcar la notificación como leída");
                // Igual redirigimos para no bloquear UX
                window.location.href = url;
            }

        })
        .catch(error => {
            console.error("Error:", error);
            // Fallback de seguridad
            window.location.href = url;
        });
}

function cargarNuevosLeads() {
    const datos = new FormData();
    datos.append("accion", "nuevos_leads");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(data => {
            const body = document.querySelector(".nuevosLeads");
            if (body) {
                body.textContent = data.total || 0;
            } else {
                console.warn("Elemento .nuevosLeads no encontrado");
            }
        })
        .catch(error => console.error("Error en fetch:", error));
}

cargarTopbarNotificaciones();
actualizarBadge();
cargarNotificaciones();
cargarNuevosLeads();