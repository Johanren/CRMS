document.addEventListener("DOMContentLoaded", () => {
    ["celular_estudiante"].forEach(id => {
        const campo = document.getElementById(id);

        if (!campo) {
            console.warn(`Elemento no encontrado: ${id}`);
            return;
        }

        campo.addEventListener("blur", async function() {
            let valor = this.value.trim();
            if (valor === "") return;

            const datos = new FormData();
            datos.append("accion", "buscar_cliente_leads");
            datos.append("valor", valor);

            try {
                let response = await fetch("ajax.php", {
                    method: "POST",
                    body: datos
                });

                let data = await response.json();

                if (data.status === "existe") {

                    Swal.fire("Usuario Encontrado", data.message, "success");

                    if (data.cliente && data.cliente.length > 0) {
                        let c = data.cliente[0]; // 👈 CLAVE

                        if (document.getElementById("id_lead"))
                            document.getElementById("id_lead").value = c.id_lead || "";

                        if (document.getElementById("cliente_id"))
                            document.getElementById("cliente_id").value = c.cliente_id || "";

                        if (document.getElementById("nombre_estudiante"))
                            document.getElementById("nombre_estudiante").value = c.nombre || "";

                        if (document.getElementById("celular_estudiante"))
                            document.getElementById("celular_estudiante").value = c.telefono_principal || "";

                        if (document.getElementById("nombre_acudiente"))
                            document.getElementById("nombre_acudiente").value = c.acudiente || "";

                        if (document.getElementById("telefono_acudiente"))
                            document.getElementById("telefono_acudiente").value = c.tel_acudiente || "";

                        if (document.getElementById("carrera"))
                            document.getElementById("carrera").value = c.carrera_id || "";

                        if (document.getElementById("horario"))
                            document.getElementById("horario").value = c.horario_id || "";

                        //if (document.getElementById("cod_emp"))
                            //document.getElementById("cod_emp").value = c.cod_emp || "";
                    }
                }

            } catch (error) {
                console.error("Error en la validación:", error);
            }
        });
    });


    document.getElementById("mainForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        const datos = new FormData(this);
        datos.append("accion", "actualizar_lead");

        const usuario = document.getElementById("user").value;

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
});