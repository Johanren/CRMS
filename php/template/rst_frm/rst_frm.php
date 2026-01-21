<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MULTITECH - Reporte RST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables (si lo usas) -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="js/rst_frm.js"></script>

    <style>
        body {
            background: #f2f6fc;
        }

        .page-wrapper {
            padding: 40px 20px;
        }

        .card {
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            border: none;
        }

        .card-header {
            background: linear-gradient(90deg, #0062E6, #33AEFF);
            color: #fff;
            border-radius: 18px 18px 0 0;
        }

        .card-header h4 {
            font-weight: 700;
            margin: 0;
        }

        .btn-filter {
            border-radius: 50px;
        }

        .input-icon {
            max-width: 260px;
        }

        .table thead {
            background: #f0f4ff;
        }

        .table th {
            font-weight: 600;
            color: #004085;
            white-space: nowrap;
        }

        /* Loader */
        .loader-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 6px solid #ddd;
            border-top: 6px solid #0062E6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .d-none {
            display: none;
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <div class="container-fluid">

        <div class="card">

            <!-- HEADER -->
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4>📊 Reporte RST</h4>
            </div>

            <!-- BODY -->
            <div class="card-body position-relative">
                <!-- TABLA -->
                <div class="table-responsive custom-table position-relative">
                    <table class="table table-hover table-bordered align-middle" id="rst_reports">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Asesor RST</th>
                                <th>Observación</th>
                                <th>Asesor</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Loader -->
                    <div id="loaderFoco" class="loader-overlay d-none">
                        <div class="spinner"></div>
                        <p class="mt-2 fw-semibold">Cargando reporte...</p>
                    </div>
                </div>

                <!-- PAGINACIÓN -->
                <div class="row align-items-center mt-3">
                    <div class="col-md-6">
                        <div class="datatable-length"></div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="datatable-paginate"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>
