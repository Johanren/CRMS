<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-0">Foco</h4>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div class="daterangepick form-control w-auto d-flex align-items-center">
                    <i class="ti ti-calendar text-dark me-2"></i>
                    <span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
                </div>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- start row -->
        <div class="row">

            <div class="col-md-12 d-flex">
                <div class="card w-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h6 class="mb-0">Foco Detalle</h6>
                        <div class="d-flex align-items-center flex-wrap row-gap-3">
                            <div class="dropdown">
                                <a onclick="exportarExcel('foco_leads')" class="btn btn-primary"><i class="ti ti-square-rounded-plus-filled me-1"></i>Exportar excel</a>
                            </div>
                        </div>
                    </div>
                    <style>
                        #tablaFocoReporte {
                            border-collapse: collapse;
                            width: 100%;
                            text-align: center;
                        }

                        #tablaFocoReporte th,
                        #tablaFocoReporte td {
                            border: 1px solid black;
                            padding: 8px;
                        }

                        #tablaFocoReporte th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                        }

                        #tablaFocoReporte td {
                            background-color: #ffffff;
                        }
                    </style>
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table id="tablaFocoReporte">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div>
    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>

<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>