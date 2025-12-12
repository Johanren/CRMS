<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Contacts<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                    <div class="dropdown-menu  dropdown-menu-end">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Export as
                                    PDF</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as
                                    Excel </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- table header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filtro<i class="ti ti-chevron-down ms-2"></i></a>
                    <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                        <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                            <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filtro</h6>
                            <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                        </div>
                        <div class="filter-set-view p-3">
                            <div class="accordion" id="accordionExample">
                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCarrera" aria-expanded="false" aria-controls="collapseThree">Carrera</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseCarrera" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div id="listar_filtro_carrera" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#status" aria-expanded="false" aria-controls="status">Estado</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="status" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div id="listar_filtro_estado"></div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <a href="#" id="btnGuardarFiltros" class="btn btn-outline-primary w-100">Guardar filtros</a>
                                <a href="#" id="btnCargarFiltros" class="btn btn-primary w-100">Aplicar filtros guardados</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-icon input-icon-start position-relative">
                    <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                    <input type="text" class="form-control" id="buscador" placeholder="Buscar">
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!--<div class="d-flex align-items-center shadow p-1 rounded border bg-white view-icons">
                    <a href="contacts-list.php" class="btn btn-sm p-1 border-0 fs-14"><i class="ti ti-list-tree"></i></a>
                    <a href="contacts.php" class="flex-shrink-0 btn btn-sm active p-1 border-0 ms-1 fs-14"><i class="ti ti-grid-dots"></i></a>
                </div>-->
                <!--<a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add Contacts</a>-->
            </div>
        </div>
        <!-- table header -->

        <div class="row" id="contact-grid"></div>

        <!--<div class="load-btn text-center">
            <a href="javascript:void(0);" class="btn btn-primary"><i class="ti ti-loader me-1"></i> Load More</a>
        </div>-->


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