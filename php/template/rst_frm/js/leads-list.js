document.addEventListener("DOMContentLoaded", () => {

    const campos = [
        {
            id: "celular_estudiante",
            accion: "buscar_cliente_leads",
            campoTelefono: "celular_estudiante"
        },
        {
            id: "celular_estudiante_tele",
            accion: "buscar_cliente_tele",
            campoTelefono: "celular_estudiante_tele"
        }
    ];

    campos.forEach(cfg => {
        const campo = document.getElementById(cfg.id);

        if (!campo) {
            console.warn(`Elemento no encontrado: ${cfg.id}`);
            return;
        }

        campo.addEventListener("blur", async function () {
            let valor = this.value.trim();
            if (!valor) return;

            const datos = new FormData();
            datos.append("accion", cfg.accion);
            datos.append("valor", valor);

            try {
                const response = await fetch("ajax.php", {
                    method: "POST",
                    body: datos
                });

                const data = await response.json();

                if (data.status === "existe") {
                    Swal.fire("Usuario Encontrado", data.message, "success");

                    // 🔥 Soporta array o objeto
                    const c = Array.isArray(data.cliente)
                        ? data.cliente[0]
                        : data.cliente?.data || data.cliente;

                    if (!c) return;

                    setValue("id_lead", c.id_lead);
                    setValue("cliente_id", c.cliente_id);
                    setValue("estado_lead_id", c.estado_lead);
                    setValue("user_id", c.user_id);
                    setValue("nombre_estudiante", c.nombre);
                    setValue(cfg.campoTelefono, c.telefono_principal);
                    setValue("nombre_acudiente", c.acudiente);
                    setValue("telefono_acudiente", c.tel_acudiente);
                    setValue("email", c.email);
                    setValue("dire", c.dire);
                    setValue("carrera", c.carrera_id);
                    setValue("horario", c.horario_id);
                }

            } catch (error) {
                console.error("Error en la validación:", error);
            }
        });
    });

    // 🔹 Submit único
    const form = document.getElementById("mainForm");
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const datos = new FormData(this);
            datos.append("accion", "actualizar_lead");

            const usuario = document.getElementById("user")?.value;

            if (!usuario) {
                Swal.fire("Asignación obligatoria", "Debe seleccionar un asesor", "warning");
                return;
            }

            try {
                const resp = await fetch("ajax.php", {
                    method: "POST",
                    body: datos
                });

                const json = await resp.json();

                if (json.status === "ok") {
                    Swal.fire("Correcto", json.message, "success");
                    this.reset();
                } else {
                    Swal.fire("Error", json.message, "error");
                }

            } catch (error) {
                console.error(error);
            }
        });
    }

});

/* ===========================
   Helpers
=========================== */
function setValue(id, value) {
    const el = document.getElementById(id);
    if (el && value !== undefined && value !== null) {
        el.value = value;
    }
}
