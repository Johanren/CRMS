<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cargue Masivo Excel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: 12px;
        }

        .form-label {
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">📊 Cargue Masivo desde Excel</h5>
                    </div>

                    <div class="card-body">
                        <form action="carge_excel.php" method="POST" enctype="multipart/form-data">

                            <!-- Tipo de cargue -->
                            <div class="mb-3">
                                <label class="form-label">Tipo de cargue</label>
                                <select name="tipo_cargue" class="form-select" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="leads">Leads</option>
                                    <option value="telemercadeo">Telemercadeo</option>
                                    <option value="deleas">Deleas</option>
                                </select>
                            </div>

                            <!-- Archivo Excel -->
                            <div class="mb-3">
                                <label class="form-label">Archivo Excel</label>
                                <input
                                    type="file"
                                    name="archivo_excel"
                                    class="form-control"
                                    accept=".xlsx,.xls"
                                    required>
                                <div class="form-text">
                                    Formatos permitidos: .xls, .xlsx
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Empresa</label>
                                <select name="cod_emp" class="form-select" required>
                                    <option value="">Seleccione empresa</option>
                                    <option value="1">MULTITECH</option>
                                    <option value="2">MULTICOMPUTO</option>
                                </select>
                            </div>

                            <!-- Botón -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    🚀 Cargar Información
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    💡 El sistema validará registros duplicados automáticamente.
                </div>

            </div>
        </div>
    </div>

</body>

</html>


<?php
foreach (glob("../models/*.php") as $filename) {
    require_once $filename;
}

ini_set('memory_limit', '1024M');
set_time_limit(0);

require '../vendor/autoload.php';
require 'ChunkReadFilter.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$conectar = (new Conexion())->conectar();

if (!isset($_FILES['archivo_excel'])) {
    die("No se recibió archivo");
}

$archivo = $_FILES['archivo_excel']['tmp_name'];
$tipo = $_POST['tipo_cargue'] ?? '';

/* ==========================
   CONFIG POR TIPO
========================== */
switch ($tipo) {
    case 'telemercadeo':
        /* ==========================
        LECTOR OPTIMIZADO
        ========================== */
        $reader = IOFactory::createReaderForFile($archivo);
        $reader->setReadDataOnly(true);

        $chunkSize = 1000;
        $filter = new ChunkReadFilter();
        $reader->setReadFilter($filter);

        /* ==========================
        INSERT IGNORE (CLAVE)
        ========================== */
        $sqlInsert = "
            INSERT IGNORE INTO telemercadeo (
                cod_con, id_con, nom_con, ape_con, telefono,
                cod_car_con, cod_hor_con, cod_int_con,
                cod_fue_con, email, dir_con,
                user_id, obs_con, estado_lead_id, campana_id
            ) VALUES (
                :cod_con, :id_con, :nom_con, :ape_con, :telefono,
                :cod_car_con, :cod_hor_con, :cod_int_con,
                :cod_fue_con, :email, :dir_con,
                :user_id, :obs_con, :estado_lead_id, :campana_id
            )";

        $stmtInsert = $conectar->prepare($sqlInsert);

        $insertados = 0;
        $duplicados = 0;

        $conectar->beginTransaction();

        $startRow = 4;

        while (true) {

            $filter->setRows($startRow, $chunkSize);
            $spreadsheet = $reader->load($archivo);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            if (count($rows) <= 1) {
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
                break;
            }

            foreach ($rows as $row) {

                $telefono = trim($row[6] ?? '');
                if ($telefono === '') continue;

                $stmtInsert->execute([
                    ':cod_con'         => trim($row[1] ?? ''),
                    ':id_con'          => trim($row[2] ?? ''),
                    ':nom_con'         => trim($row[4] ?? ''),
                    ':ape_con'         => trim($row[5] ?? ''),
                    ':telefono'        => $telefono,
                    ':cod_car_con'     => trim($row[8] ?? ''),
                    ':cod_hor_con'     => trim($row[10] ?? ''),
                    ':cod_int_con'     => trim($row[12] ?? ''),
                    ':cod_fue_con'     => trim($row[19] ?? ''),
                    ':email'           => trim($row[14] ?? ''),
                    ':dir_con'         => trim($row[15] ?? ''),
                    ':user_id'         => trim($row[21] ?? ''),
                    ':obs_con'         => trim($row[23] ?? ''),
                    ':estado_lead_id'  => trim($row[27] ?? ''),
                    ':campana_id'      => trim($row[29] ?? ''),
                ]);

                if ($stmtInsert->rowCount() > 0) {
                    $insertados++;
                } else {
                    $duplicados++;
                }
            }

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            $startRow += $chunkSize;
        }

        $conectar->commit();

        echo "✅ Proceso finalizado<br>";
        echo "📥 Insertados: $insertados<br>";
        echo "⚠️ Duplicados: $duplicados";
    case 'deleas':

        $cod_emp = $_POST['cod_emp'];

        try {

            set_time_limit(0);
            ini_set('memory_limit', '1024M');

            /* ==========================
       LECTOR OPTIMIZADO (CHUNKS)
    ========================== */
            $reader = IOFactory::createReaderForFile($archivo);
            $reader->setReadDataOnly(true);

            $chunkSize = 500;
            $filter = new ChunkReadFilter();
            $reader->setReadFilter($filter);

            $startRow = 5;

            $conectar->beginTransaction();

            /* ===============================
       CONTADORES
    ================================ */
            $totalDeals = 0;
            $cedulasInsertadas = [];

            /* ===============================
       1️⃣ OBTENER ASESORES
    ================================ */
            $sql = "
        SELECT l.user_id
        FROM deals l
        INNER JOIN user u ON u.id_user = l.user_id
        INNER JOIN user_role ur ON ur.id_rol = u.rol_id
        WHERE l.cod_emp = ?
        AND ur.activo = 1
        GROUP BY l.user_id
        ORDER BY COUNT(*) ASC
    ";
            $stmt = $conectar->prepare($sql);
            $stmt->execute([$cod_emp]);
            $asesores = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($asesores)) {
                $sql2 = "
            SELECT u.id_user
            FROM user u
            INNER JOIN user_role r ON u.rol_id = r.id_rol
            WHERE r.nombre_rol LIKE '%asesor%'
            AND u.cod_emp = ?
            AND r.activo = 1
        ";
                $stmt2 = $conectar->prepare($sql2);
                $stmt2->execute([$cod_emp]);
                $asesores = $stmt2->fetchAll(PDO::FETCH_COLUMN);
            }

            if (empty($asesores)) {
                throw new Exception("No hay asesores disponibles");
            }

            $indexAsesor   = 0;
            $totalAsesores = count($asesores);

            /* ===============================
       2️⃣ PREPARED STATEMENTS
    ================================ */
            $stmtBuscarCliente = $conectar->prepare("
        SELECT id_cliente
        FROM cliente
        WHERE identificacion = ?
        OR telefono_principal = ?
        LIMIT 1
    ");

            $stmtInsertCliente = $conectar->prepare("
        INSERT INTO cliente
        (identificacion, nombres, telefono_principal, email)
        VALUES (?,?,?,?)
    ");

            $stmtInsertLead = $conectar->prepare("
        INSERT INTO deals
        (user_id, cliente_id, carrera_id, horario_id, estado_leads_id, cod_emp)
        VALUES (?,?,?,?,?,?)
    ");

            $stmtInsertNota = $conectar->prepare("
        INSERT INTO nota
        (tit_nota, desc_not, id_lead, user_id, cod_emp)
        VALUES (?,?,?,?,?)
    ");

            /* ===============================
       3️⃣ PROCESO POR CHUNKS
    ================================ */
            while (true) {

                $filter->setRows($startRow, $chunkSize);
                $spreadsheet = $reader->load($archivo);
                $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                if (count($rows) <= 1) {
                    $spreadsheet->disconnectWorksheets();
                    unset($spreadsheet);
                    break;
                }

                foreach ($rows as $row) {

                    if (empty($row['A']) && empty($row['G'])) {
                        continue;
                    }

                    $cedula   = trim($row['A']);
                    $nombre   = trim($row['B']);
                    $programa = trim($row['C']);
                    $jornada  = trim($row['D']);
                    $correo   = trim($row['F']);
                    $telefono = trim($row['G']);
                    $estado   = trim($row['H']);
                    $obs      = trim($row['M']);

                    /* CLIENTE */
                    $stmtBuscarCliente->execute([$cedula, $telefono]);
                    $cliente = $stmtBuscarCliente->fetch(PDO::FETCH_ASSOC);

                    if ($cliente) {
                        $id_cliente = $cliente['id_cliente'];
                    } else {
                        $stmtInsertCliente->execute([
                            $cedula,
                            $nombre,
                            $telefono,
                            $correo
                        ]);
                        $id_cliente = $conectar->lastInsertId();
                    }

                    /* ASESOR (ROUND ROBIN) */
                    $user_id = $asesores[$indexAsesor];
                    $indexAsesor = ($indexAsesor + 1) % $totalAsesores;

                    /* INSERT DEAL */
                    $stmtInsertLead->execute([
                        $user_id,
                        $id_cliente,
                        $programa,
                        $jornada,
                        $estado,
                        $cod_emp
                    ]);

                    $id_lead = $conectar->lastInsertId();

                    if ($id_lead) {
                        $totalDeals++;
                        $cedulasInsertadas[] = $cedula;
                    }

                    /* NOTA */
                    if (!empty($obs)) {
                        $stmtInsertNota->execute([
                            'Cargue masivo Excel',
                            $obs,
                            $id_lead,
                            $user_id,
                            $cod_emp
                        ]);
                    }
                }

                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                $startRow += $chunkSize;
            }

            $conectar->commit();

            /* ===============================
       RESULTADO FINAL
    ================================ */
            echo "✅ Cargue DEALS finalizado correctamente<br>";
            echo "📥 Total deals insertados: <b>$totalDeals</b><br>";

            if (!empty($cedulasInsertadas)) {
                echo "📄 Cédulas insertadas:<br>";
                echo implode(' - ', $cedulasInsertadas);
            }
        } catch (Exception $e) {
            $conectar->rollBack();
            echo "❌ Error cargue DEALS: " . $e->getMessage();
        }

        break;

    default:
        die("Tipo inválido");
}
