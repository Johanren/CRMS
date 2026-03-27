<?php
session_start();
foreach (glob("../controllers/*.php") as $filename) {
    require_once $filename;
}
foreach (glob("../models/*.php") as $filename) {
    require_once $filename;
}

/* ==============================
   CONFIGURACIÓN
============================== */
define("CLAVE_ACCESO", "RST2025MULTI");

/* ==============================
   CERRAR SESIÓN
============================== */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

/* ==============================
   VALIDAR CLAVE DE ACCESO
============================== */
if (isset($_POST['clave_acceso'])) {

    if ($_POST['clave_acceso'] === CLAVE_ACCESO) {
        $_SESSION['acceso_programas'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Clave incorrecta";
    }
}

/* ==============================
   CRUD AJAX
============================== */
if (isset($_SESSION['acceso_programas']) && isset($_POST['action'])) {

    header('Content-Type: application/json');

    $conn = new Conexion();
    $conectar = $conn->conectar();

    switch ($_POST['action']) {

        /* =========================
           LISTAR
        ==========================*/
        case 'listar':

            $sql = "SELECT * FROM prioridad WHERE foc_pri = :foc_pri ORDER BY CAST(pri_pri AS UNSIGNED) DESC";
            $stmt = $conectar->prepare($sql);
            $stmt->execute([':foc_pri' => 56]);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);

            break;


        /* =========================
           INSERTAR
        ==========================*/
        case 'insertar':

            $sql = "INSERT INTO prioridad 
                    (cpr_pri, dpr_pri, cho_pri, dho_pri, cup_pri, pri_pri, emp_pri, foc_pri)
                    VALUES (:cpr_pri, :dpr_pri, :cho_pri, :dho_pri, :cup_pri, :pri_pri, :emp_pri, :foc_pri)";

            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(':cpr_pri', $_POST['cpr_pri']);
            $stmt->bindParam(':dpr_pri', $_POST['dpr_pri']);
            $stmt->bindParam(':cho_pri', $_POST['cho_pri']);
            $stmt->bindParam(':dho_pri', $_POST['dho_pri']);
            $stmt->bindParam(':cup_pri', $_POST['cup_pri']);
            $stmt->bindParam(':pri_pri', $_POST['pri_pri']);
            $stmt->bindParam(':emp_pri', $_POST['emp_pri']);
            $stmt->bindParam(':foc_pri', $_POST['foc_pri']);

            $stmt->execute();

            echo json_encode(["success" => true]);

            break;


        /* =========================
           OBTENER
        ==========================*/
        case 'obtener':

            $sql = "SELECT * FROM prioridad WHERE cpr_pri = :id";
            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($result);

            break;


        /* =========================
           ACTUALIZAR
        ==========================*/
        case 'actualizar':

            $sql = "UPDATE prioridad SET
                    cpr_pri = :cpr_pri,
                    dpr_pri = :dpr_pri,
                    cho_pri = :cho_pri,
                    dho_pri = :dho_pri,
                    cup_pri = :cup_pri,
                    pri_pri = :pri_pri,
                    emp_pri = :emp_pri,
                    foc_pri = :foc_pri
                    WHERE cpr_pri = :id";

            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(':cpr_pri', $_POST['cpr_pri']);
            $stmt->bindParam(':dpr_pri', $_POST['dpr_pri']);
            $stmt->bindParam(':cho_pri', $_POST['cho_pri']);
            $stmt->bindParam(':dho_pri', $_POST['dho_pri']);
            $stmt->bindParam(':cup_pri', $_POST['cup_pri']);
            $stmt->bindParam(':pri_pri', $_POST['pri_pri']);
            $stmt->bindParam(':emp_pri', $_POST['emp_pri']);
            $stmt->bindParam(':foc_pri', $_POST['foc_pri']);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);

            $stmt->execute();

            echo json_encode(["success" => true]);

            break;


        /* =========================
           ELIMINAR
        ==========================*/
        case 'eliminar':

            $sql = "DELETE FROM prioridad WHERE cpr_pri = :id";
            $stmt = $conectar->prepare($sql);

            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(["success" => true]);

            break;

        /* =========================
   AUTOCOMPLETE PROGRAMA
=========================*/
        case 'buscar_programa':

            $sql = "SELECT cod_pro, desc_pro 
            FROM programa 
            WHERE cod_pro LIKE :busqueda 
            OR desc_pro LIKE :busqueda 
            LIMIT 10";

            $stmt = $conectar->prepare($sql);
            $busqueda = "%" . $_POST['q'] . "%";
            $stmt->bindParam(':busqueda', $busqueda);
            $stmt->execute();

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

            break;


        /* =========================
   AUTOCOMPLETE JORNADA
=========================*/
        case 'buscar_jornada':

            $sql = "SELECT cod_jor, desc_jor 
            FROM jornada 
            WHERE cod_jor LIKE :busqueda 
            OR desc_jor LIKE :busqueda 
            LIMIT 10";

            $stmt = $conectar->prepare($sql);
            $busqueda = "%" . $_POST['q'] . "%";
            $stmt->bindParam(':busqueda', $busqueda);
            $stmt->execute();

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

            break;
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión Programas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f2f6fc;
        }

        .card-custom {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
            max-width: 750px;
            margin: 40px auto;
        }

        .btn-primary {
            background: linear-gradient(90deg, #0062E6, #33AEFF);
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-weight: 600;
        }

        .required::after {
            content: " *";
            color: red;
        }
    </style>
    <style>
        .autocomplete-list {
            position: absolute;
            width: 100%;
            background: #fff;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 9999;
        }

        .autocomplete-item {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-item:hover {
            background: #f1f1f1;
        }
    </style>

</head>

<body>

    <div class="card-custom">

        <?php if (!isset($_SESSION['acceso_programas'])): ?>

            <!-- 🔐 PANTALLA DE CLAVE -->

            <h4 class="text-center mb-4">Acceso Seguro</h4>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Ingrese la clave de acceso</label>
                <input type="password" name="clave_acceso" class="form-control mb-3" required>
                <button class="btn btn-primary w-100">Ingresar</button>
            </form>

        <?php else: ?>

            <div class="d-flex justify-content-between mb-3">
                <h4>Gestión de Programas</h4>
                <div>
                    <button class="btn btn-success" onclick="abrirModal()">Nuevo Registro</button>
                    <a href="?logout=true" class="btn btn-danger">Cerrar sesión</a>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Programa</th>
                        <th>Jornada</th>
                        <th>Cupo</th>
                        <th>Disponibilidad</th>
                        <th>Empresa</th>
                        <th>Foco</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaProgramas"></tbody>
            </table>
            <div class="modal fade" id="modalPrograma" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Programa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <form id="formPrograma">
                                <input type="hidden" name="id" id="id">

                                <div class="position-relative">
                                    <input class="form-control mb-2" name="cpr_pri" id="cpr_pri" placeholder="Código programa" autocomplete="off" required>
                                    <div class="autocomplete-list" id="list_programa"></div>
                                </div>

                                <input class="form-control mb-2" name="dpr_pri" id="dpr_pri" placeholder="Nombre programa" readonly>


                                <div class="position-relative">
                                    <input class="form-control mb-2" name="cho_pri" id="cho_pri" placeholder="Código jornada" autocomplete="off" required>
                                    <div class="autocomplete-list" id="list_jornada"></div>
                                </div>

                                <input class="form-control mb-2" name="dho_pri" id="dho_pri" placeholder="Nombre jornada" readonly>
                                <input class="form-control mb-2" type="number" name="cup_pri" placeholder="Cupo">
                                <select class="form-select mb-2" name="pri_pri">
                                    <option value="1">Disponible</option>
                                    <option value="0">No disponible</option>
                                </select>
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
        <?php endif; ?>

    </div>

    <?php if (isset($_SESSION['acceso_programas'])): ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            const modal = new bootstrap.Modal(document.getElementById('modalPrograma'));

            function cargarTabla() {
                fetch("index.php", {
                        method: "POST",
                        body: new URLSearchParams({
                            action: "listar"
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        let html = "";
                        data.forEach(row => {
                            html += `
            <tr>
                <td>${row.cpr_pri}</td>
                <td>${row.dpr_pri}</td>
                <td>${row.dho_pri}</td>
                <td>${row.cup_pri}</td>
                <td>${row.pri_pri}</td>
                <td>${row.emp_pri}</td>
                <td>${row.foc_pri}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editar(${row.cpr_pri})">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="eliminar(${row.cpr_pri})">Eliminar</button>
                </td>
            </tr>`;
                        });
                        document.getElementById("tablaProgramas").innerHTML = html;
                    });
            }

            function abrirModal() {
                document.getElementById("formPrograma").reset();
                document.getElementById("id").value = "";
                modal.show();
                setTimeout(() => {
                    document.getElementById("cpr_pri").focus();
                }, 300);
            }

            function guardar() {
                const form = document.getElementById("formPrograma");
                const data = new FormData(form);

                if (data.get("id")) {
                    data.append("action", "actualizar");
                } else {
                    data.append("action", "insertar");
                }

                fetch("index.php", {
                        method: "POST",
                        body: data
                    })
                    .then(res => res.json())
                    .then(() => {
                        modal.hide();
                        cargarTabla();
                    });
            }

            function editar(id) {

                fetch("index.php", {
                        method: "POST",
                        body: new URLSearchParams({
                            action: "obtener",
                            id: id
                        })
                    })
                    .then(res => res.json())
                    .then(data => {

                        // ⚠️ FORZAMOS EL ID
                        document.getElementById("id").value = data.cpr_pri;

                        // Llenamos los demás campos
                        for (let key in data) {
                            const input = document.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.value = data[key];
                            }
                        }

                        modal.show();
                    });
            }

            function eliminar(id) {
                Swal.fire({
                    title: "¿Eliminar?",
                    icon: "warning",
                    showCancelButton: true
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch("index.php", {
                                method: "POST",
                                body: new URLSearchParams({
                                    action: "eliminar",
                                    id: id
                                })
                            })
                            .then(res => res.json())
                            .then(() => cargarTabla());
                    }
                });
            }

            function activarAutocomplete(inputCodigo, inputDescripcion, listId, action, keyCode, keyDesc) {

                const input = document.getElementById(inputCodigo);
                const list = document.getElementById(listId);

                input.addEventListener("keyup", function() {

                    const query = this.value;

                    if (query.length < 2) {
                        list.innerHTML = "";
                        return;
                    }

                    fetch("index.php", {
                            method: "POST",
                            body: new URLSearchParams({
                                action: action,
                                q: query
                            })
                        })
                        .then(res => res.json())
                        .then(data => {

                            let html = "";

                            data.forEach(item => {
                                html += `
                    <div class="autocomplete-item"
                        onclick="seleccionar(
                            '${inputCodigo}',
                            '${inputDescripcion}',
                            '${listId}',
                            '${item[keyCode]}',
                            '${item[keyDesc]}'
                        )">
                        <strong>${item[keyCode]}</strong> - ${item[keyDesc]}
                    </div>
                `;
                            });

                            list.innerHTML = html;
                        });

                });
            }

            function seleccionar(inputCodigo, inputDescripcion, listId, codigo, descripcion) {

                // llenar código
                document.getElementById(inputCodigo).value = codigo;

                // llenar descripción automáticamente
                document.getElementById(inputDescripcion).value = descripcion;

                // limpiar lista
                document.getElementById(listId).innerHTML = "";

                if (inputCodigo === "cpr_pri") {
                    setTimeout(() => {
                        document.getElementById("cho_pri").focus();
                    }, 100);
                }
            }

            cargarTabla();

            // Programa
            activarAutocomplete(
                "cpr_pri", // código
                "dpr_pri", // descripción
                "list_programa",
                "buscar_programa",
                "cod_pro",
                "desc_pro"
            );

            // Jornada
            activarAutocomplete(
                "cho_pri",
                "dho_pri",
                "list_jornada",
                "buscar_jornada",
                "cod_jor",
                "desc_jor"
            );
        </script>

    <?php endif; ?>

</body>

</html>