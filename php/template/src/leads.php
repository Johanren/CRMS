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
                <h4 class="mb-1">Leads<span class="badge badge-soft-primary ms-2">123</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leads</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Exportar</a>
                    <div class="dropdown-menu  dropdown-menu-end">
                        <ul>
                            <!--<li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Exportar como
                                        PDF</a>
                                </li>-->
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item" onclick="exportarExcel('leads')">
                                    <i class="ti ti-file-type-xls me-1"></i> Exportar como Excel
                                </a>
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
        <div class="input-icon input-icon-start position-relative">
            <span id="resumen-filtros" class="text-muted small"></span>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filtrar<i class="ti ti-chevron-down ms-2"></i></a>
                    <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                        <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                            <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filtrar</h6>
                            <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                        </div>
                        <div class="filter-set-view p-3">
                            <div class="accordion" id="accordionExample">

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAsesor">Asesor</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseAsesor" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-asesor" id="all_asesor">
                                                <label class="form-check-label fw-bold" for="all_asesor">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_user" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCarrera">Carrera</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseCarrera" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-carrera" id="all_carrera">
                                                <label class="form-check-label fw-bold" for="all_carrera">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_carrera" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#horarioCollapse">Jornada</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="horarioCollapse" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-horario" id="all_horario">
                                                <label class="form-check-label fw-bold" for="all_horario">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_horario" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseInteres">Interes</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseInteres" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-interes" id="all_interes">
                                                <label class="form-check-label fw-bold" for="all_interes">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_interes" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseMedio">Medio</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseMedio" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-medio" id="all_medio">
                                                <label class="form-check-label fw-bold" for="all_medio">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_medio" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFuente">Fuente</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseFuente" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-fuente" id="all_fuente">
                                                <label class="form-check-label fw-bold" for="all_fuente">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_fuente" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCamp">Campaña</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseCamp" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-campana" id="all_campana">
                                                <label class="form-check-label fw-bold" for="all_campana">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_campana" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAcc">Acción</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseAcc" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-accion" id="all_accion">
                                                <label class="form-check-label fw-bold" for="all_accion">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_accion" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseDep">Departamento</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseDep" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-dep" id="all_dep">
                                                <label class="form-check-label fw-bold" for="all_dep">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_dep" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCiduad">Ciudad</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseCiduad" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-ciu" id="all_ciu">
                                                <label class="form-check-label fw-bold" for="all_ciu">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_ciudad" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseBrr">Barrio</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseBrr" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-brr" id="all_brr">
                                                <label class="form-check-label fw-bold" for="all_brr">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_brr" class="overflow-x-auto"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter-set-content">
                                    <div class="filter-set-content-head">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#status">Estado</a>
                                    </div>
                                    <div class="filter-set-contents accordion-collapse collapse" id="status" data-bs-parent="#accordionExample">
                                        <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                            <div class="form-check mb-2 border-bottom pb-1">
                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-estado" id="all_estado">
                                                <label class="form-check-label fw-bold" for="all_estado">Seleccionar todos</label>
                                            </div>
                                            <div id="listar_filtro_estado"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="contenedor-botones"></div>
                        </div>
                    </div>
                </div>
                <div id="reportrange" class="reportrange-picker d-flex align-items-center shadow">
                    <i class="ti ti-calendar-due text-dark fs-14 me-1"></i>
                    <span class="reportrange-picker-field">Seleccione fechas</span>
                </div>
                <div class="input-icon input-icon-start position-relative">
                    <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                    <input type="text" class="form-control" id="buscador" placeholder="Buscar">
                </div>
                <div class="input-icon input-icon-start position-relative">
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offproximaActividad">Ver proxima actividad</button>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="d-flex align-items-center shadow p-1 rounded border view-icons bg-white">
                    <a href="leads-list.php" class="btn btn-sm p-1 border-0 fs-14"><i class="ti ti-list-tree"></i></a>
                    <a href="leads.php" class="flex-shrink-0 btn btn-sm p-1 border-0 ms-1 fs-14 active"><i class="ti ti-grid-dots"></i></a>
                </div>
                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar Cliente</a>
            </div>
        </div>
        <!-- table header -->

        <!-- Leads Kanban -->
        <style>
            /* ===== LOADER ===== */
            .loader-overlay {
                position: fixed;
                inset: 0;
                background: rgba(255, 255, 255, 0.85);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            .loader-overlay p {
                margin-top: 10px;
                font-weight: bold;
            }

            .spinner {
                width: 50px;
                height: 50px;
                border: 6px solid #ddd;
                border-top: 6px solid #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* ===== UTIL ===== */
            .d-none {
                display: none;
            }
        </style>
        <div id="kanban-container" class="d-flex overflow-x-auto align-items-start gap-3"></div>
        <div id="loaderFoco" class="loader-overlay d-none">
            <div class="spinner"></div>
            <p>Cargando leads...</p>
        </div>
        <!-- /Leads Kanban -->
        <!-- End Content -->

        <?php require_once '../partials/footer.php'; ?>

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

    <?php
    $content = ob_get_clean();

    require_once '../partials/main.php'; ?>