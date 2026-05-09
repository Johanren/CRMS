<?php ob_start(); ?>

<?php
foreach (glob("../controllers/*.php") as $filename) {
    require_once $filename;
}
foreach (glob("../models/*.php") as $filename) {
    require_once $filename;
}

/* ==============================
   CRUD AJAX
============================== */
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    $conn = new Conexion();
    $conectar = $conn->conectar();

    switch ($_POST['action']) {
        case 'listar':

            try {

                // =====================================================
                // ACTIVAR ERRORES PDO
                // =====================================================
                $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $conectar->beginTransaction();
                session_start();
                $foco = $_SESSION['foco'];
                $empresa = $_SESSION['cod_emp'];

                // =====================================================
                // VALIDAR QUE EXISTA FOCO EN SESSION
                // =====================================================
                if (empty($foco)) {

                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No existe foco en sesión'
                    ]);

                    exit;
                }

                // =====================================================
                // OBTENER DETALLES DEL FOCO
                // =====================================================
                $sqlFoco = "SELECT 
                        fd.foc_fde,
                        fd.prog_fde,
                        fd.jorn_fde,
                        fd.ven_fde,
                        fd.emp_fde,
                        p.desc_pro,
                        h.descripcion
                    FROM foco_detalle fd
                    INNER JOIN programa p 
                        ON p.cod_pro = fd.prog_fde
                    INNER JOIN horario h 
                        ON h.id_horario = fd.jorn_fde
                    WHERE fd.foc_fde = ?
                    AND fd.emp_fde = ?";

                $stmtFoco = $conectar->prepare($sqlFoco);

                $stmtFoco->execute([
                    $foco,
                    $empresa
                ]);

                $detallesFoco = $stmtFoco->fetchAll(PDO::FETCH_ASSOC);

                // =====================================================
                // SI NO HAY DETALLES
                // =====================================================
                if (empty($detallesFoco)) {

                    echo json_encode([
                        'status' => 'error',
                        'message' => 'El foco no tiene detalles registrados',
                        'foco' => $foco
                    ]);

                    exit;
                }

                // =====================================================
                // RECORRER DETALLES DEL FOCO
                // =====================================================
                foreach ($detallesFoco as $detalle) {

                    // =====================================================
                    // VALIDAR SI YA EXISTE EN PRIORIDAD
                    // =====================================================
                    $sqlExiste = "SELECT cpr_pri
                          FROM prioridad
                          WHERE cpr_pri = ?
                          AND cho_pri = ?
                          AND foc_pri = ?
                          AND emp_pri = ?";

                    $stmtExiste = $conectar->prepare($sqlExiste);

                    $stmtExiste->execute([
                        $detalle['prog_fde'],
                        $detalle['jorn_fde'],
                        $detalle['foc_fde'],
                        $detalle['emp_fde']
                    ]);

                    $existe = $stmtExiste->fetch(PDO::FETCH_ASSOC);

                    // =====================================================
                    // INSERTAR SI NO EXISTE
                    // =====================================================
                    if (!$existe) {

                        $sqlInsert = "INSERT INTO prioridad
                (
                    cpr_pri,
                    dpr_pri,
                    cho_pri,
                    dho_pri,
                    cup_pri,
                    pri_pri,
                    emp_pri,
                    foc_pri
                )
                VALUES (?,?,?,?,?,?,?,?)";

                        $stmtInsert = $conectar->prepare($sqlInsert);

                        $stmtInsert->execute([
                            $detalle['prog_fde'],
                            $detalle['desc_pro'],
                            $detalle['jorn_fde'],
                            $detalle['descripcion'],
                            $detalle['ven_fde'],
                            1,
                            $detalle['emp_fde'],
                            $detalle['foc_fde']
                        ]);
                    } else {

                        // =====================================================
                        // ACTUALIZAR SI EXISTE
                        // =====================================================
                        $sqlUpdate = "UPDATE prioridad
                SET
                    dpr_pri = ?,
                    dho_pri = ?,
                    cup_pri = ?
                WHERE cpr_pri = ?
                AND cho_pri = ?
                AND foc_pri = ?
                AND emp_pri = ?";

                        $stmtUpdate = $conectar->prepare($sqlUpdate);

                        $stmtUpdate->execute([
                            $detalle['desc_pro'],
                            $detalle['descripcion'],
                            $detalle['ven_fde'],
                            $detalle['prog_fde'],
                            $detalle['jorn_fde'],
                            $detalle['foc_fde'],
                            $detalle['emp_fde']
                        ]);
                    }
                }

                // =====================================================
                // ELIMINAR PRIORIDADES QUE YA NO EXISTAN EN FOCO
                // =====================================================
                $sqlDelete = "DELETE pri
        FROM prioridad pri
        LEFT JOIN foco_detalle fd
            ON fd.prog_fde = pri.cpr_pri
            AND fd.jorn_fde = pri.cho_pri
            AND fd.foc_fde = pri.foc_pri
            AND fd.emp_fde = pri.emp_pri
        WHERE fd.prog_fde IS NULL
        AND pri.foc_pri = ?
        AND pri.emp_pri = ?";

                $stmtDelete = $conectar->prepare($sqlDelete);

                $stmtDelete->execute([
                    $foco,
                    $empresa
                ]);

                // =====================================================
                // LISTAR PRIORIDADES
                // =====================================================
                $sql = "SELECT *
                FROM prioridad
                WHERE foc_pri = :foc_pri
                AND emp_pri = :emp_pri
                ORDER BY CAST(pri_pri AS UNSIGNED) DESC";

                $stmt = $conectar->prepare($sql);

                $stmt->execute([
                    ':foc_pri' => $foco,
                    ':emp_pri' => $empresa
                ]);

                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $conectar->commit();

                echo json_encode($resultado);
            } catch (Exception $e) {

                if ($conectar->inTransaction()) {
                    $conectar->rollBack();
                }

                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }

            break;

        case 'insertar':
            $sql = "INSERT INTO prioridad 
                    (cpr_pri, dpr_pri, cho_pri, dho_pri, cup_pri, pri_pri, emp_pri, foc_pri)
                    VALUES (:cpr_pri, :dpr_pri, :cho_pri, :dho_pri, :cup_pri, :pri_pri, :emp_pri, :foc_pri)";
            $stmt = $conectar->prepare($sql);
            // Pasamos los parámetros limpiarmente
            $stmt->execute([
                ':cpr_pri' => $_POST['cpr_pri'],
                ':dpr_pri' => $_POST['dpr_pri'],
                ':cho_pri' => $_POST['cho_pri'],
                ':dho_pri' => $_POST['dho_pri'],
                ':cup_pri' => $_POST['cup_pri'],
                ':pri_pri' => $_POST['pri_pri'],
                ':emp_pri' => $_POST['emp_pri'],
                ':foc_pri' => $_POST['foc_pri']
            ]);
            echo json_encode(["success" => true]);
            break;

        case 'actualizar_prioridad':
            $sql = "UPDATE prioridad SET pri_pri = :valor WHERE cpr_pri = :id AND cho_pri = :cho";
            $stmt = $conectar->prepare($sql);
            $stmt->bindParam(':valor', $_POST['valor']);
            $stmt->bindParam(':id', $_POST['id']);
            $stmt->bindParam(':cho', $_POST['cho']);
            $stmt->execute();
            echo json_encode(["success" => true]);
            break;
    }
    exit;
}
?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-0">Panel de ofertas</h4>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="javascript:void(0);" onclick="cargarTabla()" class="btn btn-icon btn-outline-light shadow">
                    <i class="ti ti-refresh"></i>
                </a>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12 d-flex">
                <div class="card flex-fill">

                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                            <h6 class="mb-0">Programa</h6>
                            <button class="btn btn-primary" onclick="abrirModal()">
                                <i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar carrera
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive custom-table">
                            <table class="table table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Programa</th>
                                        <th>Jornada</th>
                                        <th>Cupo</th>
                                        <th>Prioridad</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProgramas"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <?php require_once '../partials/footer.php'; ?>

</div>

<!-- ========================
        End Page Content
    ========================= -->

<!-- ==============================
        MODAL
================================= -->

<div class="modal fade" id="modalPrograma">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nuevo Programa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formPrograma">

                    <input class="form-control mb-2" name="cpr_pri" placeholder="Código programa" required>
                    <input class="form-control mb-2" name="dpr_pri" placeholder="Nombre programa" required>

                    <input class="form-control mb-2" name="cho_pri" placeholder="Código jornada" required>
                    <input class="form-control mb-2" name="dho_pri" placeholder="Nombre jornada" required>

                    <input type="number" class="form-control mb-2" name="cup_pri" placeholder="Cupo">

                    <!-- PRIORIDAD COMO NUMBER -->
                    <input type="number" class="form-control mb-2" name="pri_pri" placeholder="Prioridad">

                    <!-- EMPRESA Y FOCO SIGUEN EXISTIENDO -->
                    <input class="form-control mb-2" name="emp_pri" placeholder="Empresa">
                    <input class="form-control mb-2" name="foc_pri" placeholder="Foco">

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>

        </div>
    </div>
</div>
<script>
    // Definimos el modal en el ámbito global para que todas las funciones lo vean
    let modalPrograma;

    document.addEventListener("DOMContentLoaded", function() {
        modalPrograma = new bootstrap.Modal(document.getElementById('modalPrograma'));
        cargarTabla();
    });

    function cargarTabla() {
        fetch("prioridad.php", { // Asegúrate de que el archivo sea el mismo
                method: "POST",
                body: new URLSearchParams({
                    action: "listar"
                })
            })
            .then(res => res.json())
            .then(data => {
                // Ordenamos en el cliente por si acaso la DB no lo hizo
                data.sort((a, b) => Number(b.pri_pri) - Number(a.pri_pri));

                renderizarTabla(data);
            })
            .catch(err => console.error("Error al cargar:", err));
    }

    function renderizarTabla(data) {
        let html = "";
        data.forEach(row => {
            html += `
            <tr>
                <td>${row.cpr_pri}</td>
                <td>${row.dpr_pri}</td>
                <td>${row.dho_pri}</td>
                <td>${row.cup_pri}</td>
                <td>
                    <input type="number"
                           class="form-control form-control-sm shadow-sm"
                           style="width: 80px"
                           value="${row.pri_pri}"
                           onchange="actualizarPrioridad('${row.cpr_pri}', '${row.cho_pri}', this.value)">
                </td>
            </tr>`;
        });
        document.getElementById("tablaProgramas").innerHTML = html;
    }

    function actualizarPrioridad(id, cho, valor) {
        fetch("prioridad.php", {
                method: "POST",
                body: new URLSearchParams({
                    action: "actualizar_prioridad",
                    id: id,
                    cho: cho,
                    valor: valor
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    // Al actualizar, volvemos a cargar para que la fila se "mueva" sola
                    cargarTabla();
                    console.log("Orden actualizado");
                }
            });
    }

    function abrirModal() {
        document.getElementById("formPrograma").reset();
        modalPrograma.show();
    }

    function guardar() {
        const form = document.getElementById("formPrograma");
        const data = new FormData(form);
        data.append("action", "insertar");

        fetch("prioridad.php", {
                method: "POST",
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    modalPrograma.hide();
                    cargarTabla();
                }
            });
    }
</script>

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>