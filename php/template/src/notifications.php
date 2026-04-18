<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap">
            <div>
                <h4 class="mb-1">Notifications</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- card start -->
        <div class="card mb-0">

            <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
                <h6 class="d-inline-flex align-items-center mb-0">Notificaciones <span class="badge bg-danger ms-2"></span></h6>
                <!--<div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-light"><i class="ti ti-checks me-1"></i>Mark all as read</a>
                    <a href="javascript:void(0);" class="btn btn-danger"><i class="ti ti-trash me-1"></i>Delete All</a>
                </div>-->
            </div>

            <div class="card-body">
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <strong>Resultados del filtro:</strong>
                    <span class="badge bg-dark fs-12"><span id="contadorTotalLeads1">0</span> Leads encontrados</span>
                </div>
                <div class="table-responsive">
                    <table id="leads_list_1" class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Carrera</th>
                                <th>Telefono</th>
                                <th>Estado</th>
                                <th>Asesor</th>
                                <th>Fecha creación</th>
                                <th>Fecha ultima gestion</th>
                                <th>Fecha ultima asignacion</th>
                                <th>Gestion</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- dinámico -->
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <!-- card start -->

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