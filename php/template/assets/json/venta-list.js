function inicializarDataTableFoco(foco) {

    if ($.fn.dataTable.isDataTable('#info_foco')) {
        $('#info_foco').DataTable().clear().destroy();
    }

    const table = $('#info_foco').DataTable({
        searching: true, // 🔍 buscador activo
        bInfo: false,
        ordering: true,
        autoWidth: false,

        language: {
            search: "",
            searchPlaceholder: "Buscar",
            paginate: {
                next: '<i class="ti ti-chevron-right"></i>',
                previous: '<i class="ti ti-chevron-left"></i>'
            }
        },

        data: foco,

        columns: [
            { title: "Foco", data: "Foco" },
            { title: "Jornada", data: "Jornada" },
            { title: "Programa", data: "Programa" },
            { title: "Cupos", data: "Cupos" },
            { title: "Reintegros", data: "Reintegros" },
            { title: "Total Cupos", data: "Total_Cupos" }
        ]
    });
}

function listarFoco() {
    fetch("ajax/ajax.php?accion=listar_foco")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableFoco(data);
        })
        .catch(err => console.error("Error al listar foco:", err));
}

listarFoco();

function calcularTotalCupos() {
    const ventas = parseInt(document.getElementById('cupoVentaFoco').value) || 0;
    const reintegros = parseInt(document.getElementById('cupoReintegroFoco').value) || 0;

    document.getElementById('totalCupoFoco').value = ventas + reintegros;
}

document.getElementById('cupoVentaFoco').addEventListener('input', calcularTotalCupos);
document.getElementById('cupoReintegroFoco').addEventListener('input', calcularTotalCupos);

if (document.getElementById("formFoco")) {
    document.getElementById("formFoco").addEventListener("submit", function (e) {
        e.preventDefault();

        let carreraSelect = document.getElementById('carrera');
        let carreraNombre = carreraSelect.options[carreraSelect.selectedIndex].text;

        let horarioSelect = document.getElementById('horario');
        let horarioNombre = horarioSelect.options[horarioSelect.selectedIndex].text;

        let totalCupoFoco = document.getElementById('totalCupoFoco').value;

        let datos = new FormData(this);
        datos.append("accion", "registrar_foco");
        datos.append("carreraNombre", carreraNombre);
        datos.append("horarioNombre", horarioNombre);
        datos.append("totalCupoFoco", totalCupoFoco);

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarFoco();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-foco").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}