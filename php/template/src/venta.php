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
                <h4 class="mb-0">Foco de ventas</h4>
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
                <div class="card flex-fill">
                    <div class="card-body">
                        <form id="formFoco">
                            <div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Codigo <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="codigoFoco" name="codigoFoco">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nombreFoco" name="nombreFoco">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Fecha inicial <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="fechaInicioFoco" name="fechaInicioFoco">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Fecha fin <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="fechaFinFoco" name="fechaFinFoco">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Programa <span class="text-danger">*</span></label>
                                            <select class="form-control" id="carrera" name="carrera"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Jornada <span class="text-danger">*</span></label>
                                            <select class="form-control" id="horario" name="horario"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Cupo ventas <span class="text-danger">*</span></label>
                                            <input class="form-control" id="cupoVentaFoco" name="cupoVentaFoco" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Cupo Reintegros <span class="text-danger">*</span></label>
                                            <input class="form-control" id="cupoReintegroFoco" name="cupoReintegroFoco" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Total Cupos <span class="text-danger">*</span></label>
                                            <input class="form-control" disabled id="totalCupoFoco" name="totalCupoFoco" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-primary me-2" id="btnCrearFoco">Crear</button>
                                <button type="button" class="btn btn-success" id="btnGuardarDetalle">Guardar Detalle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h6 class="mb-0">Foco <span id="nombreFocoActivo" class="text-primary fw-bold"></span></h6>
                        <!--<div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offfoco_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar foco</a>
                        </div>-->
                        <div class="dropdown">
                            <a onclick="exportarExcel('foco')" class="btn btn-primary"><i class="ti ti-square-rounded-plus-filled me-1"></i>Exportar excel</a>
                        </div>
                    </div>
                    <style>
                        #tablaFoco {
                            border-collapse: collapse;
                            width: 100%;
                            text-align: center;
                        }

                        #tablaFoco th,
                        #tablaFoco td {
                            border: 1px solid black;
                            padding: 8px;
                        }

                        #tablaFoco th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                        }

                        #tablaFoco td {
                            background-color: #ffffff;
                        }
                    </style>
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table id="tablaFoco">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

        </div>

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