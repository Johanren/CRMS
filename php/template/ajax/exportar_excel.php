<?php
ob_clean();
ob_start();
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
$carreras     = json_decode($_GET["carreras"] ?? "[]");
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
        $fila = 3;

        foreach ($jornadas as $jornada) {

            $sheet->setCellValue("A{$fila}", $jornada);

            $col = 2;
            $tC = $tV = $tR = 0;

            foreach ($programas as $programa) {

                $c = $data[$jornada][$programa]["cupos"] ?? 0;
                $v = $data[$jornada][$programa]["ventas"] ?? 0;
                $r = $data[$jornada][$programa]["reintegros"] ?? 0;

                $sheet->setCellValueByColumnAndRow($col, $fila, $c);
                $sheet->setCellValueByColumnAndRow($col + 1, $fila, $v);
                $sheet->setCellValueByColumnAndRow($col + 2, $fila, $r);

                $tC += $c;
                $tV += $v;
                $tR += $r;

                $col += 3;
            }

            // Totales por jornada
            $sheet->setCellValueByColumnAndRow($col, $fila, $tC);
            $sheet->setCellValueByColumnAndRow($col + 1, $fila, $tV);
            $sheet->setCellValueByColumnAndRow($col + 2, $fila, $tR);

            $fila++;
        }

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

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Reporte_Foco_Matriz.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
        break;


    default:
        die("Tipo de reporte no válido");
}
