<?php
ob_clean();
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

foreach (glob("../controllers/*.php") as $filename) {
    require_once $filename;
}
foreach (glob("../models/*.php") as $filename) {
    require_once $filename;
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require "../vendor/autoload.php";

function exportarExcel($nombreArchivo, $data, $columnas = [])
{
    if (empty($data)) {
        die("No hay datos para exportar.");
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados: si no se envían columnas, usar keys del resultado
    if (empty($columnas)) {
        $headers = [];
        foreach ($data[0] as $key => $v) {
            $headers[$key] = ucfirst(str_replace("_", " ", $key));
        }
    } else {
        $headers = $columnas;
    }

    // === ENCABEZADOS ===
    $col = 1;
    foreach ($headers as $campo => $titulo) {
        $sheet->setCellValueByColumnAndRow($col, 1, $titulo);
        $col++;
    }

    // === CONTENIDO ===
    $fila = 2;
    foreach ($data as $row) {
        $col = 1;
        foreach ($headers as $campo => $titulo) {
            $sheet->setCellValueByColumnAndRow($col, $fila, $row[$campo] ?? "");
            $col++;
        }
        $fila++;
    }

    // === DESCARGA ===
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");

    exit;
}

$tipo = $_GET["tipo"] ?? "leads";

$texto        = $_GET["texto"] ?? "";
$asesor       = json_decode($_GET["asesor"] ?? "[]");
$carreras     = json_decode($_GET["carreras"] ?? $_GET["carrera"] ?? "[]");
$horario      = json_decode($_GET["horario"] ?? "[]");
$interes      = json_decode($_GET["interes"] ?? "[]");
$medio        = json_decode($_GET["medio"] ?? "[]");
$fuente       = json_decode($_GET["fuente"] ?? "[]");
$campana      = json_decode($_GET["campana"] ?? "[]");
$accion       = json_decode($_GET["accion"] ?? "[]");
$departamento = json_decode($_GET["departamento"] ?? "[]");
$ciudad       = json_decode($_GET["ciudad"] ?? "[]");
$barrio       = json_decode($_GET["barrio"] ?? "[]");
$estados      = json_decode($_GET["estados"] ?? "[]");
$fecha_inicio = !empty($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin    = !empty($_GET['fecha_fin'])    ? $_GET['fecha_fin']    : null;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$mes = date('m');
$anio = date('Y');

switch ($tipo) {
    case "leads":

        $data = LeadsModels::listarLeads(
            $texto,
            $asesor,
            $carreras,
            $horario,
            $interes,
            $medio,
            $fuente,
            $campana,
            $accion,
            $departamento,
            $ciudad,
            $barrio,
            $estados
        );

        $columnas = [
            "nombres" => "Nombres",
            "apellidos" => "Apellidos",
            "email" => "Email",
            "telefono_principal" => "Teléfono",
            "desc_pro" => "Programa",
            "ciudad" => "Ciudad",
            "horario" => "Horario",
            "fecha_creacion" => "Fecha de creación",
            "nombreAsesor" => "Asesor",
            "estado" => "Estado",
        ];

        exportarExcel("Leads_Filtrados", $data, $columnas);
        break;

    case "leads_reporte":

        $data = LeadsModels::obtenerResumenHorarios(
            $_SESSION["cod_emp"],
            json_decode($_GET["asesor"] ?? "[]"),
            json_decode($_GET["carreras"] ?? "[]"),
            json_decode($_GET["horario"] ?? "[]"),
            json_decode($_GET["estados"] ?? "[]"),
            $_GET["fecha_inicio"] ?? null,
            $_GET["fecha_fin"] ?? null
        );

        // Columnas dinámicas → usar keys del JSON
        $columnas = [];
        if (!empty($data)) {
            foreach (array_keys($data[0]) as $campo) {
                $columnas[$campo] = ucfirst(str_replace("_", " ", $campo));
            }
        }

        exportarExcel("Reporte_Leads", $data, $columnas);
        break;

    case "foco":

        $resultado = focoControllers::reporteFocoActivoMatriz();

        $data      = $resultado["matriz"];
        $jornadas  = $resultado["jornadas"];
        $programas = $resultado["programas"];

        if (empty($jornadas) || empty($programas)) {
            die("No hay datos para exportar");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ================= HEADER =================
        $sheet->setCellValue("A1", "Jornada");
        $sheet->mergeCells("A1:A2");

        $col = 2;
        foreach ($programas as $programa) {

            $inicio = $col;
            $sheet->setCellValueByColumnAndRow($col, 1, $programa);
            $sheet->mergeCellsByColumnAndRow($inicio, 1, $inicio + 2, 1);

            $sheet->setCellValueByColumnAndRow($inicio, 2, "Cupos");
            $sheet->setCellValueByColumnAndRow($inicio + 1, 2, "Ventas");
            $sheet->setCellValueByColumnAndRow($inicio + 2, 2, "Reintegros");

            $col += 3;
        }

        // Totales
        $sheet->setCellValueByColumnAndRow($col, 1, "Total");
        $sheet->mergeCellsByColumnAndRow($col, 1, $col + 2, 1);
        $sheet->setCellValueByColumnAndRow($col, 2, "Cupos");
        $sheet->setCellValueByColumnAndRow($col + 1, 2, "Ventas");
        $sheet->setCellValueByColumnAndRow($col + 2, 2, "Reintegros");

        // ================= BODY =================

        // 🔹 acumuladores por programa
        $totalesPrograma = [];
        foreach ($programas as $programa) {
            $totalesPrograma[$programa] = [
                "c" => 0,
                "v" => 0,
                "r" => 0
            ];
        }

        $totalGeneralC = 0;
        $totalGeneralV = 0;
        $totalGeneralR = 0;

        $fila = 3;

        foreach ($jornadas as $jornada) {

            $sheet->setCellValue("A{$fila}", $jornada);

            $col = 2;
            $tC = $tV = $tR = 0;

            foreach ($programas as $programa) {

                // 🔁 swap cupos / ventas
                $v = $data[$jornada][$programa]["cupos"] ?? 0;
                $c = $data[$jornada][$programa]["ventas"] ?? 0;
                $r = $data[$jornada][$programa]["reintegros"] ?? 0;

                $sheet->setCellValueByColumnAndRow($col, $fila, $c);
                $sheet->setCellValueByColumnAndRow($col + 1, $fila, $v);
                $sheet->setCellValueByColumnAndRow($col + 2, $fila, $r);

                // 🔹 totales por jornada
                $tC += $c;
                $tV += $v;
                $tR += $r;

                // 🔹 totales por programa (vertical)
                $totalesPrograma[$programa]["c"] += $c;
                $totalesPrograma[$programa]["v"] += $v;
                $totalesPrograma[$programa]["r"] += $r;

                $col += 3;
            }

            // Totales por jornada
            $sheet->setCellValueByColumnAndRow($col, $fila, $tC);
            $sheet->setCellValueByColumnAndRow($col + 1, $fila, $tV);
            $sheet->setCellValueByColumnAndRow($col + 2, $fila, $tR);

            // 🔹 totales generales
            $totalGeneralC += $tC;
            $totalGeneralV += $tV;
            $totalGeneralR += $tR;

            $fila++;
        }

        // ================= FILA TOTALES =================
        $sheet->setCellValue("A{$fila}", "Totales");

        $col = 2;

        foreach ($programas as $programa) {

            $sheet->setCellValueByColumnAndRow($col, $fila, $totalesPrograma[$programa]["c"]);
            $sheet->setCellValueByColumnAndRow($col + 1, $fila, $totalesPrograma[$programa]["v"]);
            $sheet->setCellValueByColumnAndRow($col + 2, $fila, $totalesPrograma[$programa]["r"]);

            $col += 3;
        }

        // Totales generales
        $sheet->setCellValueByColumnAndRow($col, $fila, $totalGeneralC);
        $sheet->setCellValueByColumnAndRow($col + 1, $fila, $totalGeneralV);
        $sheet->setCellValueByColumnAndRow($col + 2, $fila, $totalGeneralR);

        // ================= ESTILOS =================
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getStyle("A1:{$lastCol}2")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A{$fila}:{$lastCol}{$fila}")
            ->getFont()
            ->setBold(true);

        $sheet->getStyle("A{$fila}:{$lastCol}{$fila}")
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFEFEFEF');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Foco_Matriz.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;
    case "foco_leads":

        $resultado = focoControllers::reporteFocoLeadsMatriz();

        $data      = $resultado["matriz"];
        $jornadas  = $resultado["jornadas"];
        $programas = $resultado["programas"];

        if (empty($jornadas) || empty($programas)) {
            die("No hay datos para exportar");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* ================= HEADER ================= */

        $sheet->setCellValue("A1", "Jornada");
        $sheet->mergeCells("A1:A2");

        $col = 2;
        foreach ($programas as $programa) {

            $inicio = $col;

            $sheet->setCellValueByColumnAndRow($col, 1, $programa);
            $sheet->mergeCellsByColumnAndRow($inicio, 1, $inicio + 2, 1);

            $sheet->setCellValueByColumnAndRow($inicio,     2, "Cupos");
            $sheet->setCellValueByColumnAndRow($inicio + 1, 2, "Ventas");
            $sheet->setCellValueByColumnAndRow($inicio + 2, 2, "Reintegros");

            $col += 3;
        }

        /* ===== TOTAL ===== */
        $sheet->setCellValueByColumnAndRow($col, 1, "Total");
        $sheet->mergeCellsByColumnAndRow($col, 1, $col + 2, 1);

        $sheet->setCellValueByColumnAndRow($col,     2, "Con horario");
        $sheet->setCellValueByColumnAndRow($col + 1, 2, "Ventas");
        $sheet->setCellValueByColumnAndRow($col + 2, 2, "Solo carrera");

        /* ================= BODY ================= */

        $fila = 3;

        $totalConHorario = 0;
        $totalSoloCarrera = 0;

        foreach ($jornadas as $jornada) {

            /* ================= FILA 1 → CUPOS ================= */
            $sheet->setCellValue("A{$fila}", $jornada);
            $sheet->mergeCells("A{$fila}:A" . ($fila + 2));

            $col = 2;
            $totalFilaCupos = 0;

            foreach ($programas as $programa) {

                $cupos = $data[$jornada][$programa]["ventas"] ?? 0;

                $sheet->setCellValueByColumnAndRow($col, $fila, $cupos);
                $sheet->mergeCellsByColumnAndRow($col, $fila, $col + 2, $fila);

                $totalFilaCupos += $cupos;
                $col += 3;
            }

            $sheet->setCellValueByColumnAndRow($col, $fila, $totalFilaCupos);
            $sheet->mergeCellsByColumnAndRow($col, $fila, $col + 2, $fila);

            $fila++;

            /* ================= FILA 2 → VENTAS / REINTEGROS ================= */
            $col = 2;
            foreach ($programas as $programa) {

                $sheet->setCellValueByColumnAndRow($col,     $fila, 0);
                $sheet->setCellValueByColumnAndRow($col + 1, $fila, 0);
                $sheet->setCellValueByColumnAndRow($col + 2, $fila, 0);

                $col += 3;
            }

            $sheet->setCellValueByColumnAndRow($col,     $fila, 0);
            $sheet->setCellValueByColumnAndRow($col + 1, $fila, 0);
            $sheet->setCellValueByColumnAndRow($col + 2, $fila, 0);

            $fila++;

            /* ================= FILA 3 → LEADS ================= */
            $col = 2;
            $tCon = 0;
            $tSolo = 0;

            foreach ($programas as $programa) {

                $con  = $data[$jornada][$programa]["con"] ?? 0;
                $solo = $data[$jornada][$programa]["solo"] ?? 0;

                $sheet->setCellValueByColumnAndRow($col,     $fila, $con);
                $sheet->mergeCellsByColumnAndRow($col, $fila, $col + 1, $fila);

                $sheet->setCellValueByColumnAndRow($col + 2, $fila, $solo);

                $tCon  += $con;
                $tSolo += $solo;

                $totalConHorario += $con;
                $totalSoloCarrera += $solo;

                $col += 3;
            }

            $sheet->setCellValueByColumnAndRow($col,     $fila, $tCon);
            $sheet->mergeCellsByColumnAndRow($col, $fila, $col + 1, $fila);
            $sheet->setCellValueByColumnAndRow($col + 2, $fila, $tSolo);

            $fila++;
        }

        /* ================= FILA FINAL ================= */

        $sheet->setCellValue("A{$fila}", "Totales");

        $col = 2;
        foreach ($programas as $programa) {

            $sheet->setCellValueByColumnAndRow($col,     $fila, "");
            $sheet->mergeCellsByColumnAndRow($col, $fila, $col + 1, $fila);
            $sheet->setCellValueByColumnAndRow($col + 2, $fila, "");

            $col += 3;
        }

        $sheet->setCellValueByColumnAndRow($col,     $fila, $totalConHorario);
        $sheet->mergeCellsByColumnAndRow($col, $fila, $col + 1, $fila);
        $sheet->setCellValueByColumnAndRow($col + 2, $fila, $totalSoloCarrera);

        /* ================= ESTILOS ================= */

        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Foco_Leads.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;
    case "leads_campaign":

        $data = LeadsControllers::utm_campaign();

        $columnas = [
            "campaign" => "UTM Campaign",
            "total"        => "Total Leads"
        ];

        exportarExcel("Leads_por_Campaign", $data, $columnas);
        break;

    case "leads_campaign_click":

        $data = Marketing_trackingControllers::utm_campaignClic();

        $columnas = [
            "utm_campaign" => "UTM Campaign",
            "clicks" => "Click",
            "convertidos"  => "Convertido"
        ];

        exportarExcel("Leads_por_Campaign_clic", $data, $columnas);
        break;

    case "CRMS_lead":
        // 1. Obtener la data del modelo
        $data = LeadsControllers::listarReporteCRMLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);

        if (empty($data)) {
            die("No hay datos para exportar.");
        }

        // 2. Procesar la data para crear la matriz (Pivot)
        $programas = [];
        $horarios = [];
        $matriz = [];

        foreach ($data as $row) {
            $p = $row['programa'] ?? '(En blanco)';
            $h = $row['horario'] ?? '(Sin horario)';
            $cant = (int)$row['total_leads'];

            $programas[$p] = $p;
            $horarios[$h] = $h;
            $matriz[$p][$h] = $cant;
        }

        ksort($programas);
        ksort($horarios);

        // 3. Crear el Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- ENCABEZADOS ---
        $sheet->setCellValue("A1", "Programa / Horario");
        $col = 2;
        foreach ($horarios as $h) {
            $sheet->setCellValueByColumnAndRow($col, 1, $h);
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, 1, "Total General");
        $colFinal = $col;

        // --- CUERPO ---
        $fila = 2;
        $totalesColumnas = array_fill(2, count($horarios), 0);
        $granTotal = 0;

        foreach ($programas as $p) {
            $sheet->setCellValue("A{$fila}", $p);
            $col = 2;
            $totalFila = 0;

            foreach ($horarios as $h) {
                $valor = $matriz[$p][$h] ?? 0;
                if ($valor > 0) {
                    $sheet->setCellValueByColumnAndRow($col, $fila, $valor);
                }
                $totalFila += $valor;
                $totalesColumnas[$col] += $valor;
                $col++;
            }

            // Total al final de la fila
            $sheet->setCellValueByColumnAndRow($col, $fila, $totalFila);
            $granTotal += $totalFila;
            $fila++;
        }

        // --- FILA DE TOTALES (PIE) ---
        $sheet->setCellValue("A{$fila}", "Total general");
        $col = 2;
        foreach ($totalesColumnas as $totalCol) {
            $sheet->setCellValueByColumnAndRow($col, $fila, $totalCol);
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, $fila, $granTotal);

        // --- ESTILOS ---
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colFinal);

        // Bordes y alineación
        $sheet->getStyle("A1:{$lastColLetter}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A1:{$lastColLetter}1")->getFont()->setBold(true);
        $sheet->getStyle("A{$fila}:{$lastColLetter}{$fila}")->getFont()->setBold(true);

        // Auto-ajustar columnas
        foreach (range('A', $lastColLetter) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // --- DESCARGA ---
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_CRMS_Leads.xlsx"');
        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;

    case "estado_lead":

        $respuesta = LeadsControllers::ctrReporteEstadoLeads($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin, 1, 10000);
        $data = $respuesta['data'] ?? [];

        if (empty($data)) {
            die("No hay datos para exportar.");
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Reporte Estado Leads");

        $encabezados = ["ID Lead", "Cliente", "Asesor", "Estado Actual", "N° de Cambios"];
        $columnas = ['A', 'B', 'C', 'D', 'E'];

        foreach ($columnas as $index => $col) {
            $sheet->setCellValue($col . "1", $encabezados[$index]);
        }

        $fila = 2;
        foreach ($data as $row) {
            $sheet->setCellValue("A{$fila}", $row['id_lead']);
            $sheet->setCellValue("B{$fila}", $row['cliente']);
            $sheet->setCellValue("C{$fila}", $row['asesor'] ?? "-");
            $sheet->setCellValue("D{$fila}", $row['estado_actual']);
            $sheet->setCellValue("E{$fila}", $row['cambios']);
            $fila++;
        }

        $ultimaFila = $fila - 1;
        $sheet->getStyle("A1:E1")->getFont()->setBold(true);
        $sheet->getStyle("A1:E{$ultimaFila}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        foreach ($columnas as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Estado_Leads_' . date('His') . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("php://output");

        exit;
        break;

    case "lead_dia":

        $respuesta = LeadsControllers::listarReporteLeadDia($mes, $anio, $texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);
        $data = $respuesta['porEstado'] ?? [];

        if (empty($data)) {
            die("No hay datos para exportar.");
        }

        $estados_header = array_values(array_unique(array_column($data, 'estado')));
        $asesores_rows = array_values(array_unique(array_column($data, 'asesor')));

        $matriz = [];
        foreach ($data as $row) {
            $matriz[$row['asesor']][$row['estado']] = (int)$row['total'];
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Resumen Leads por Estado");

        $sheet->setCellValue("A1", "ASESOR");
        $col = 2;
        foreach ($estados_header as $est) {
            $sheet->setCellValueByColumnAndRow($col, 1, $est);
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, 1, "TOTAL"); // Columna final
        $colFinalNum = $col;

        $fila = 2;
        $totalesColumnas = array_fill(2, count($estados_header), 0);
        $granTotalAbsoluto = 0;

        foreach ($asesores_rows as $nomAsesor) {
            $sheet->setCellValue("A{$fila}", $nomAsesor);

            $col = 2;
            $totalFilaAsesor = 0;

            foreach ($estados_header as $index => $est) {
                $valor = $matriz[$nomAsesor][$est] ?? 0;
                if ($valor > 0) {
                    $sheet->setCellValueByColumnAndRow($col, $fila, $valor);
                }
                $totalFilaAsesor += $valor;
                $totalesColumnas[$col] += $valor;
                $col++;
            }

            $sheet->setCellValueByColumnAndRow($col, $fila, $totalFilaAsesor);
            $granTotalAbsoluto += $totalFilaAsesor;
            $fila++;
        }

        $sheet->setCellValue("A{$fila}", "TOTAL GENERAL");
        $col = 2;
        foreach ($totalesColumnas as $tCol) {
            $sheet->setCellValueByColumnAndRow($col, $fila, $tCol);
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, $fila, $granTotalAbsoluto);

        $colFinalLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colFinalNum);

        $sheet->getStyle("A1:{$colFinalLetra}1")->getFont()->setBold(true); // Cabecera
        $sheet->getStyle("A{$fila}:{$colFinalLetra}{$fila}")->getFont()->setBold(true); // Pie
        $sheet->getStyle("A1:{$colFinalLetra}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getStyle("B1:{$colFinalLetra}{$fila}")->getAlignment()->setHorizontal('center');

        foreach (range('A', $colFinalLetra) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Leads_Por_Estado_' . date('dmY') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;

    case "reporte_fuente":

        $data = LeadsControllers::reporteLeadsFuente($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);

        if (empty($data)) {
            die("No hay datos para exportar.");
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Reporte Fuente de Origen");

        $encabezados = [
            "MEDIO",
            "FUENTE",
            "CAMPAÑA",
            "NUEVO LEADS",
            "PROSPECTO",
            "LEADS ACTIVO",
            "INTERESADO",
            "EN DECISIÓN",
            "MATRICULA EN PROCESO",
            "MATRICULADO",
            "APLAZADO",
            "PERDIDO",
            "TOTAL"
        ];

        $columnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

        foreach ($columnas as $index => $col) {
            $sheet->setCellValue($col . "1", $encabezados[$index]);
        }

        $fila = 2;
        $t = [
            'nuevo' => 0,
            'prospecto' => 0,
            'activo' => 0,
            'interesado' => 0,
            'decision' => 0,
            'proceso' => 0,
            'matriculado' => 0,
            'aplazado' => 0,
            'perdido' => 0,
            'general' => 0
        ];

        foreach ($data as $row) {
            $sheet->setCellValue("A{$fila}", $row['medio']);
            $sheet->setCellValue("B{$fila}", $row['fuente']);
            $sheet->setCellValue("C{$fila}", $row['campana']);
            $sheet->setCellValue("D{$fila}", $row['nuevo_leads']);
            $sheet->setCellValue("E{$fila}", $row['prospecto']);
            $sheet->setCellValue("F{$fila}", $row['leads_activo']);
            $sheet->setCellValue("G{$fila}", $row['interesado']);
            $sheet->setCellValue("H{$fila}", $row['en_decision']);
            $sheet->setCellValue("I{$fila}", $row['matricula_proceso']);
            $sheet->setCellValue("J{$fila}", $row['matriculado']);
            $sheet->setCellValue("K{$fila}", $row['aplazado']);
            $sheet->setCellValue("L{$fila}", $row['perdido']);
            $sheet->setCellValue("M{$fila}", $row['total']);

            foreach (range('D', 'M') as $colRed) {
                $valCell = $sheet->getCell($colRed . $fila)->getValue();
                if ($valCell > 0) {
                    $sheet->getStyle($colRed . $fila)->getFont()->getColor()->setARGB('FFFF0000'); // Rojo
                    $sheet->getStyle($colRed . $fila)->getFont()->setBold(true);
                }
            }

            $t['nuevo'] += $row['nuevo_leads'];
            $t['prospecto'] += $row['prospecto'];
            $t['activo'] += $row['leads_activo'];
            $t['interesado'] += $row['interesado'];
            $t['decision'] += $row['en_decision'];
            $t['proceso'] += $row['matricula_proceso'];
            $t['matriculado'] += $row['matriculado'];
            $t['aplazado'] += $row['aplazado'];
            $t['perdido'] += $row['perdido'];
            $t['general'] += $row['total'];

            $fila++;
        }

        $sheet->setCellValue("A{$fila}", "TOTAL GENERAL");
        $sheet->mergeCells("A{$fila}:C{$fila}");

        $sheet->setCellValue("D{$fila}", $t['nuevo']);
        $sheet->setCellValue("E{$fila}", $t['prospecto']);
        $sheet->setCellValue("F{$fila}", $t['activo']);
        $sheet->setCellValue("G{$fila}", $t['interesado']);
        $sheet->setCellValue("H{$fila}", $t['decision']);
        $sheet->setCellValue("I{$fila}", $t['proceso']);
        $sheet->setCellValue("J{$fila}", $t['matriculado']);
        $sheet->setCellValue("K{$fila}", $t['aplazado']);
        $sheet->setCellValue("L{$fila}", $t['perdido']);
        $sheet->setCellValue("M{$fila}", $t['general']);

        $ultimaCol = "M";
        $rangoTabla = "A1:{$ultimaCol}{$fila}";

        $sheet->getStyle("A1:{$ultimaCol}1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF212529');
        $sheet->getStyle("A1:{$ultimaCol}1")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $sheet->getStyle("A{$fila}:L{$fila}")->getFont()->getColor()->setARGB('FFFF0000');
        $sheet->getStyle("A{$fila}:M{$fila}")->getFont()->setBold(true);
        $sheet->getStyle("A{$fila}:M{$fila}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF5F5F5');

        $sheet->getStyle("M{$fila}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E66DC');
        $sheet->getStyle("M{$fila}")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $sheet->getStyle($rangoTabla)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle("D1:M{$fila}")->getAlignment()->setHorizontal('center');

        foreach (range('A', $ultimaCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Fuente_Origen_' . date('Ymd_His') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;

    case "rst_teo":

        // 2. OBTENCIÓN DE DATOS (Agrupados y Detallados)
        $dataRespuesta = LeadsControllers::listarReporteRstDiaTEO($mes, $anio);
        $dataDias = $dataRespuesta['porDia'] ?? [];
        $dataEstados = $dataRespuesta['porEstado'] ?? [];

        // Obtenemos el detalle que usa el DataTable
        $dataDetalle = LeadsControllers::listarReporteRstTEO($texto, $asesor);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        /* =========================================================
       HOJA 1: RESUMEN DIARIO
       ========================================================= */
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle("Resumen Diario TEO");

        if (!empty($dataDias)) {
            $asesoresUnicos = array_values(array_unique(array_column($dataDias, 'asesor')));
            $teoNombre = $dataDias[0]['asesorRTS'] ?? 'TEO';

            // Encabezados
            $sheet1->setCellValue("A1", "DÍA");
            $sheet1->setCellValue("B1", "TEO");
            $sheet1->mergeCells("A1:A2");
            $sheet1->mergeCells("B1:B2");

            $col = 3;
            foreach ($asesoresUnicos as $nomAsesor) {
                $letraIni = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $letraFin = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $sheet1->setCellValueByColumnAndRow($col, 1, $nomAsesor);
                $sheet1->mergeCells("{$letraIni}1:{$letraFin}1");
                $sheet1->setCellValueByColumnAndRow($col, 2, "Llamada");
                $sheet1->setCellValueByColumnAndRow($col + 1, 2, "WhatsApp");
                $col += 2;
            }
            $letraTotalCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet1->setCellValueByColumnAndRow($col, 1, "TOTAL");
            $sheet1->mergeCells("{$letraTotalCol}1:{$letraTotalCol}2");

            // Datos
            $fila = 3;
            $diasUnicos = array_unique(array_column($dataDias, 'dia'));
            sort($diasUnicos);
            $mesesNombres = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];

            foreach ($diasUnicos as $dia) {
                $sheet1->setCellValue("A{$fila}", $dia . " - " . $mesesNombres[(int)$mes]);
                $sheet1->setCellValue("B{$fila}", $teoNombre);
                $colData = 3;
                $totalFila = 0;
                foreach ($asesoresUnicos as $asesor) {
                    $llamada = 0;
                    $ws = 0;
                    foreach ($dataDias as $r) {
                        if ($r['dia'] == $dia && $r['asesor'] == $asesor) {
                            if (($r['tipo_nom'] ?? '') === 'WhatsApp') $ws += (int)($r['tipo'] ?? 0);
                            else $llamada += (int)($r['tipo'] ?? $r['total'] ?? 0);
                        }
                    }
                    $sheet1->setCellValueByColumnAndRow($colData, $fila, $llamada);
                    $sheet1->setCellValueByColumnAndRow($colData + 1, $fila, $ws);
                    $totalFila += ($llamada + $ws);
                    $colData += 2;
                }
                $sheet1->setCellValueByColumnAndRow($colData, $fila, $totalFila);
                $fila++;
            }

            // Fila Totales
            $sheet1->setCellValue("A{$fila}", "TOTAL GENERAL");
            $sheet1->mergeCells("A{$fila}:B{$fila}");
            for ($c = 3; $c <= $col; $c++) {
                $letraC = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $sheet1->setCellValueByColumnAndRow($c, $fila, "=SUM({$letraC}3:{$letraC}" . ($fila - 1) . ")");
            }
            $sheet1->getStyle("A1:{$letraTotalCol}2")->getAlignment()->setHorizontal('center');
            $sheet1->getStyle("A{$fila}:{$letraTotalCol}{$fila}")->getFont()->setBold(true);
            $sheet1->getStyle("A1:{$letraTotalCol}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        /* =========================================================
       HOJA 2: RESUMEN POR ESTADOS
       ========================================================= */
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle("Resumen por Estados");

        if (!empty($dataEstados)) {
            $estadosUnicos = array_values(array_unique(array_column($dataEstados, 'estado')));
            $asesoresRows = array_values(array_unique(array_column($dataEstados, 'asesor')));

            $sheet2->setCellValue("A1", "ASESOR");
            $col = 2;
            foreach ($estadosUnicos as $est) {
                $sheet2->setCellValueByColumnAndRow($col, 1, $est);
                $col++;
            }
            $sheet2->setCellValueByColumnAndRow($col, 1, "TOTAL");
            $colMaxNum = $col;
            $letraMax = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colMaxNum);

            $fila = 2;
            foreach ($asesoresRows as $asesor) {
                $sheet2->setCellValue("A{$fila}", $asesor);
                $colData = 2;
                $totalAsesor = 0;
                foreach ($estadosUnicos as $estado) {
                    $val = 0;
                    foreach ($dataEstados as $r) {
                        if ($r['asesor'] === $asesor && $r['estado'] === $estado) $val = (int)$r['total'];
                    }
                    $sheet2->setCellValueByColumnAndRow($colData, $fila, $val);
                    $totalAsesor += $val;
                    $colData++;
                }
                $sheet2->setCellValueByColumnAndRow($colData, $fila, $totalAsesor);
                $fila++;
            }
            // Fila Totales Hoja 2
            $sheet2->setCellValue("A{$fila}", "TOTAL GENERAL");
            for ($c = 2; $c <= $colMaxNum; $c++) {
                $letraC = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $sheet2->setCellValueByColumnAndRow($c, $fila, "=SUM({$letraC}2:{$letraC}" . ($fila - 1) . ")");
            }
            $sheet2->getStyle("A1:{$letraMax}1")->getFont()->setBold(true);
            $sheet2->getStyle("A{$fila}:{$letraMax}{$fila}")->getFont()->setBold(true);
            $sheet2->getStyle("A1:{$letraMax}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        /* =========================================================
       HOJA 3: DETALLE DE REGISTROS (DataTable)
       ========================================================= */
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle("Detalle de Registros");

        $headers = ["Fecha", "Cliente", "Teléfono", "TEO", "Tipo Transferencia", "Observación", "Asesor", "Estado", "Notas"];
        $sheet3->fromArray($headers, NULL, 'A1');

        if (!empty($dataDetalle)) {
            $filaDet = 2;
            foreach ($dataDetalle as $reg) {
                $sheet3->setCellValue("A{$filaDet}", $reg['fecha'] ?? '');
                $sheet3->setCellValue("B{$filaDet}", $reg['cliente_nombre'] ?? '');
                $sheet3->setCellValue("C{$filaDet}", $reg['cliente_telefono'] ?? '');
                $sheet3->setCellValue("D{$filaDet}", $reg['asesor_nombre'] ?? '');
                $sheet3->setCellValue("E{$filaDet}", $reg['tipo_nom'] ?? '');
                $sheet3->setCellValue("F{$filaDet}", $reg['obs_rst'] ?? '');
                $sheet3->setCellValue("G{$filaDet}", $reg['asesor_nombre_lead'] ?? '');
                $sheet3->setCellValue("H{$filaDet}", $reg['estado_leads'] ?? '');
                $sheet3->setCellValue("I{$filaDet}", $reg['nota'] ?? '');
                $filaDet++;
            }
            // Auto-ajustar ancho de columnas
            foreach (range('A', 'I') as $columnID) {
                $sheet3->getColumnDimension($columnID)->setAutoSize(true);
            }
            $sheet3->getStyle("A1:I1")->getFont()->setBold(true);
            $sheet3->getStyle("A1:I" . ($filaDet - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // 3. DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_RST_TEO_' . date('dmY') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;

    case "rst_frm":

        // 2. OBTENCIÓN DE DATOS (Agrupados y Detallados)
        $dataRespuesta = LeadsControllers::listarReporteRstDia($mes, $anio);
        $dataDias = $dataRespuesta['porDia'] ?? [];
        $dataEstados = $dataRespuesta['porEstado'] ?? [];

        // Obtenemos el detalle que usa el DataTable
        $dataDetalle = LeadsControllers::listarReporteRst($texto, $asesor, $carreras, $horario, $interes, $medio, $fuente, $campana, $accion, $departamento, $ciudad, $barrio, $estados, $fecha_inicio, $fecha_fin);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        /* =========================================================
       HOJA 1: RESUMEN DIARIO
       ========================================================= */
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle("Resumen Diario");

        if (!empty($dataDias)) {
            $asesoresUnicos = array_values(array_unique(array_column($dataDias, 'asesor')));
            $teoNombre = $dataDias[0]['asesorRTS'] ?? 'TEO';

            // Encabezados
            $sheet1->setCellValue("A1", "DÍA");
            $sheet1->setCellValue("B1", "TEO");
            $sheet1->mergeCells("A1:A2");
            $sheet1->mergeCells("B1:B2");

            $col = 3;
            foreach ($asesoresUnicos as $nomAsesor) {
                $letraIni = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $letraFin = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $sheet1->setCellValueByColumnAndRow($col, 1, $nomAsesor);
                $sheet1->mergeCells("{$letraIni}1:{$letraFin}1");
                $sheet1->setCellValueByColumnAndRow($col, 2, "Llamada");
                $sheet1->setCellValueByColumnAndRow($col + 1, 2, "WhatsApp");
                $col += 2;
            }
            $letraTotalCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet1->setCellValueByColumnAndRow($col, 1, "TOTAL");
            $sheet1->mergeCells("{$letraTotalCol}1:{$letraTotalCol}2");

            // Datos
            $fila = 3;
            $diasUnicos = array_unique(array_column($dataDias, 'dia'));
            sort($diasUnicos);
            $mesesNombres = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];

            foreach ($diasUnicos as $dia) {
                $sheet1->setCellValue("A{$fila}", $dia . " - " . $mesesNombres[(int)$mes]);
                $sheet1->setCellValue("B{$fila}", $teoNombre);
                $colData = 3;
                $totalFila = 0;
                foreach ($asesoresUnicos as $asesor) {
                    $llamada = 0;
                    $ws = 0;
                    foreach ($dataDias as $r) {
                        if ($r['dia'] == $dia && $r['asesor'] == $asesor) {
                            if (($r['tipo_nom'] ?? '') === 'WhatsApp') $ws += (int)($r['tipo'] ?? 0);
                            else $llamada += (int)($r['tipo'] ?? $r['total'] ?? 0);
                        }
                    }
                    $sheet1->setCellValueByColumnAndRow($colData, $fila, $llamada);
                    $sheet1->setCellValueByColumnAndRow($colData + 1, $fila, $ws);
                    $totalFila += ($llamada + $ws);
                    $colData += 2;
                }
                $sheet1->setCellValueByColumnAndRow($colData, $fila, $totalFila);
                $fila++;
            }

            // Fila Totales
            $sheet1->setCellValue("A{$fila}", "TOTAL GENERAL");
            $sheet1->mergeCells("A{$fila}:B{$fila}");
            for ($c = 3; $c <= $col; $c++) {
                $letraC = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $sheet1->setCellValueByColumnAndRow($c, $fila, "=SUM({$letraC}3:{$letraC}" . ($fila - 1) . ")");
            }
            $sheet1->getStyle("A1:{$letraTotalCol}2")->getAlignment()->setHorizontal('center');
            $sheet1->getStyle("A{$fila}:{$letraTotalCol}{$fila}")->getFont()->setBold(true);
            $sheet1->getStyle("A1:{$letraTotalCol}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        /* =========================================================
       HOJA 2: RESUMEN POR ESTADOS
       ========================================================= */
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle("Resumen por Estados");

        if (!empty($dataEstados)) {
            $estadosUnicos = array_values(array_unique(array_column($dataEstados, 'estado')));
            $asesoresRows = array_values(array_unique(array_column($dataEstados, 'asesor')));

            $sheet2->setCellValue("A1", "ASESOR");
            $col = 2;
            foreach ($estadosUnicos as $est) {
                $sheet2->setCellValueByColumnAndRow($col, 1, $est);
                $col++;
            }
            $sheet2->setCellValueByColumnAndRow($col, 1, "TOTAL");
            $colMaxNum = $col;
            $letraMax = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colMaxNum);

            $fila = 2;
            foreach ($asesoresRows as $asesor) {
                $sheet2->setCellValue("A{$fila}", $asesor);
                $colData = 2;
                $totalAsesor = 0;
                foreach ($estadosUnicos as $estado) {
                    $val = 0;
                    foreach ($dataEstados as $r) {
                        if ($r['asesor'] === $asesor && $r['estado'] === $estado) $val = (int)$r['total'];
                    }
                    $sheet2->setCellValueByColumnAndRow($colData, $fila, $val);
                    $totalAsesor += $val;
                    $colData++;
                }
                $sheet2->setCellValueByColumnAndRow($colData, $fila, $totalAsesor);
                $fila++;
            }
            // Fila Totales Hoja 2
            $sheet2->setCellValue("A{$fila}", "TOTAL GENERAL");
            for ($c = 2; $c <= $colMaxNum; $c++) {
                $letraC = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                $sheet2->setCellValueByColumnAndRow($c, $fila, "=SUM({$letraC}2:{$letraC}" . ($fila - 1) . ")");
            }
            $sheet2->getStyle("A1:{$letraMax}1")->getFont()->setBold(true);
            $sheet2->getStyle("A{$fila}:{$letraMax}{$fila}")->getFont()->setBold(true);
            $sheet2->getStyle("A1:{$letraMax}{$fila}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        /* =========================================================
       HOJA 3: DETALLE DE REGISTROS (DataTable)
       ========================================================= */
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle("Detalle de Registros");

        $headers = ["Fecha", "Cliente", "Teléfono", "TEO", "Tipo Transferencia", "Observación", "Asesor", "Estado", "Notas"];
        $sheet3->fromArray($headers, NULL, 'A1');

        if (!empty($dataDetalle)) {
            $filaDet = 2;
            foreach ($dataDetalle as $reg) {
                $sheet3->setCellValue("A{$filaDet}", $reg['fecha'] ?? '');
                $sheet3->setCellValue("B{$filaDet}", $reg['cliente_nombre'] ?? '');
                $sheet3->setCellValue("C{$filaDet}", $reg['cliente_telefono'] ?? '');
                $sheet3->setCellValue("D{$filaDet}", $reg['asesor_nombre'] ?? '');
                $sheet3->setCellValue("E{$filaDet}", $reg['tipo_nom'] ?? '');
                $sheet3->setCellValue("F{$filaDet}", $reg['obs_rst'] ?? '');
                $sheet3->setCellValue("G{$filaDet}", $reg['asesor_nombre_lead'] ?? '');
                $sheet3->setCellValue("H{$filaDet}", $reg['estado_leads'] ?? '');
                $sheet3->setCellValue("I{$filaDet}", $reg['nota'] ?? '');
                $filaDet++;
            }
            // Auto-ajustar ancho de columnas
            foreach (range('A', 'I') as $columnID) {
                $sheet3->getColumnDimension($columnID)->setAutoSize(true);
            }
            $sheet3->getStyle("A1:I1")->getFont()->setBold(true);
            $sheet3->getStyle("A1:I" . ($filaDet - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // 3. DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_RST_' . date('dmY') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;
    case "perdido":

        $data = LeadsControllers::reporteLeadsPastelMotivo();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Reporte Pivot Motivos");

        if (empty($data)) {
            die("No hay datos");
        }

        // 🔹 1. Obtener asesores y estados únicos
        $asesores = [];
        $estados = [];

        foreach ($data as $row) {
            $asesores[$row['asesor']] = true;
            $estados[$row['estado']] = true;
        }

        $asesores = array_keys($asesores);
        $estados = array_keys($estados);

        // 🔹 2. Crear matriz pivot
        $tabla = [];

        foreach ($estados as $estado) {
            foreach ($asesores as $asesor) {
                $tabla[$estado][$asesor] = 0;
            }
            $tabla[$estado]['total'] = 0;
        }

        // 🔹 3. Llenar datos
        foreach ($data as $row) {
            $asesor = $row['asesor'];
            $estado = $row['estado'];
            $cantidad = (int)$row['cantidad'];

            $tabla[$estado][$asesor] += $cantidad;
            $tabla[$estado]['total'] += $cantidad;
        }

        // 🔹 4. Totales por asesor
        $totalesAsesor = array_fill_keys($asesores, 0);
        $totalGeneral = 0;

        foreach ($estados as $estado) {
            foreach ($asesores as $asesor) {
                $totalesAsesor[$asesor] += $tabla[$estado][$asesor];
            }
            $totalGeneral += $tabla[$estado]['total'];
        }

        // 🔹 5. ENCABEZADOS
        $sheet->setCellValue("A1", "ESTADO");

        $col = 2; // B
        foreach ($asesores as $asesor) {
            $sheet->setCellValueByColumnAndRow($col, 1, $asesor);
            $col++;
        }

        $sheet->setCellValueByColumnAndRow($col, 1, "TOTAL");

        // 🎨 Estilo encabezado
        $sheet->getStyle("A1:" . $sheet->getHighestColumn() . "1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4472C4']
            ]
        ]);

        // 🔹 6. LLENAR FILAS
        $fila = 2;

        foreach ($estados as $estado) {

            $sheet->setCellValue("A{$fila}", $estado);

            $col = 2;
            foreach ($asesores as $asesor) {
                $sheet->setCellValueByColumnAndRow($col, $fila, $tabla[$estado][$asesor]);
                $col++;
            }

            $sheet->setCellValueByColumnAndRow($col, $fila, $tabla[$estado]['total']);

            $fila++;
        }

        // 🔹 7. FILA TOTAL FINAL
        $sheet->setCellValue("A{$fila}", "TOTAL");

        $col = 2;
        foreach ($asesores as $asesor) {
            $sheet->setCellValueByColumnAndRow($col, $fila, $totalesAsesor[$asesor]);
            $col++;
        }

        $sheet->setCellValueByColumnAndRow($col, $fila, $totalGeneral);

        // 🔹 8. ESTILOS FINALES
        $sheet->getStyle("A{$fila}:" . $sheet->getHighestColumn() . "{$fila}")
            ->getFont()->setBold(true);

        $sheet->getStyle("A1:" . $sheet->getHighestColumn() . "{$fila}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // 🔹 9. AUTO SIZE
        foreach (range('A', $sheet->getHighestColumn()) as $colLetra) {
            $sheet->getColumnDimension($colLetra)->setAutoSize(true);
        }

        // 🔹 10. DESCARGA
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Pivot_Motivos_' . date('dmY') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;

        break;
    default:
        die("Tipo de reporte no válido");
}
