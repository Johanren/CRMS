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
                <h4 class="mb-1">Leads<span class="badge badge-soft-primary ms-2" id="id_leads"></span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leads</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                    <div class="dropdown-menu  dropdown-menu-end">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Exportar como
                                    PDF</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Exportar como
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

        <div class="row">
            <div class="col-md-12">

                <div class="mb-3">
                    <a href="leads.php"><i class="ti ti-arrow-narrow-left me-1"></i>Volver a Leads</a>
                </div>

                <div class="card">
                    <div class="card-body pb-2">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar avatar-xxl avatar-rounded border border-warning bg-soft-warning me-3 flex-shrink-0">
                                    <h6 class="mb-0 text-warning">HT</h6>
                                </div>
                                <div>
                                    <h5 class="mb-1">
                                        <span class="editable" id="nombreClienteLeads"></span> <span class="editable" id="apellidoClienteLeads"></span>
                                    </h5>
                                    <input type="text" class="form-control d-none" id="input_nombreClienteLeads">
                                    <input type="text" class="form-control d-none" id="input_apellidoClienteLeads">
                                    <p class="mb-1">
                                        <i class="ti ti-building-skyscraper me-1" id="empresaCarrera"></i>
                                    <div class="col-sm-12 d-none" id="select_empresaCarrera">
                                        <select class="form-control">
                                            <option value="1">MULTITECH</option>
                                            <option value="2">MULTICOMPUTO</option>
                                        </select>
                                    </div>
                                    </p>
                                    <p class="mb-0 editable" id="direccionClienteLeads">
                                        <i class="ti ti-map-pin-pin me-1"></i>
                                    </p>
                                    <input type="text" class="form-control d-none" id="input_direccionClienteLeads">
                                </div>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="dropdown">
                                    <a href="#" class="btn-info fs-12 py-1 px-2 fw-medium d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ti ti-thumb-up me-1"></i><span id="asesorLeads"></span><i class="ti ti-chevron-down ms-1"></i> </a>
                                    <div class="dropdown-menu dropdown-menu-right" id="userAsesor"></div>
                                </div>
                                <span class="py-1 px-2 fs-12 bg-soft-danger rounded text-danger fw-medium"><i class="ti ti-lock me-1"></i>Private</span>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-xs btn-success fs-12 py-1 px-2 fw-medium d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ti ti-thumb-up me-1"></i>Closed<i class="ti ti-chevron-down ms-1"></i> </a>
                                    <div class="dropdown-menu dropdown-menu-right" id="estadoLeadsSecundario">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Contact User -->

            </div>

            <!-- Contact Sidebar -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <h6 class="mb-3 fw-semibold">Información Cliente</h6>
                        </div>
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Identificación </p>
                                <p class="mb-0 text-dark editable" id="identificacionLeads"> </p>
                                <input type="text" class="form-control d-none" id="input_identificacionLeads">
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Correo </p>
                                <p class="mb-0 text-dark editable" id="correoLeads"> </p>
                                <input type="text" class="form-control d-none" id="input_correoLeads">
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Telefono </p>
                                <p class="mb-0 text-dark editable" id="telefonoLeads"> </p>
                                <input type="text" class="form-control d-none" id="input_telefonoLeads">
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <h6 class="mb-3 fw-semibold">Números Adicionales</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="mt-2 ps-3">
                                    <button id="btnAgregarNumero"
                                        class="btn btn-outline-primary btn-sm mb-2 none" style="display:none;">
                                        + Agregar número adicional
                                    </button>
                                    <div id="contenedorNumeros" style="display:none;">
                                        <div id="listaNumeros">
                                            <template id="template-numero">
                                                <div class="row g-3 mb-2 numeroItem">

                                                    <!-- TEXTO -->
                                                    <div class="col-md-12 telefonoTexto d-none">
                                                        <span class="fw-semibold indicativoTxt"></span>
                                                        <span class="numeroTxt"></span>
                                                        <span class="text-muted ms-2 descTxt"></span>

                                                        <button class="btn btn-sm btn-link editarNumero">
                                                            <i class="ti ti-pencil"></i>
                                                        </button>
                                                    </div>

                                                    <!-- INPUTS -->
                                                    <div class="col-md-4 telefonoInput">
                                                        <label class="form-label">Indicativo</label>
                                                        <input type="text" class="form-control indicativo">
                                                    </div>

                                                    <div class="col-md-4 telefonoInput">
                                                        <label class="form-label">Número</label>
                                                        <input type="text" class="form-control numeroTel">
                                                    </div>

                                                    <div class="col-md-3 telefonoInput">
                                                        <label class="form-label">Descripción</label>
                                                        <input type="text" class="form-control descNumero">
                                                    </div>

                                                    <div class="col-md-1 d-flex align-items-end telefonoInput">
                                                        <a class="btn btn-danger btnEliminarNumero">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <button id="btnNuevoNumero"
                                            class="btn btn-success btn-sm mt-2">
                                            + Nuevo número
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="contenedor_matricula" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <h6 class="mb-3 fw-semibold">Información Matricula</h6>
                            </div>
                            <div class="border-bottom mb-3 pb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0">N° Factura</p>
                                    <p class="mb-0 text-dark" id="Nfactura"> </p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0">Valor</p>
                                    <p class="mb-0 text-dark" id="valorF"> </p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0">Metodo pago</p>
                                    <p class="mb-0 text-dark" id="metodoF"> </p>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-3 fw-semibold">Información Lead</h6>
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Fecha de creación</p>
                                <p class="mb-0 text-dark fechaLeads"> </p>
                            </div>
                            <!--<div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Valor</p>
                                <p class="mb-0 text-dark" id="valorCarrera">$25,11,145</p>
                            </div>-->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Fecha de vencimiento </p>
                                <p class="mb-0 text-dark fechaLeads"> </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Seguimiento</p>
                                <p class="mb-0 text-dark fechaLeads"></p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Programa</p>
                                <p class="mb-0 text-dark editable" id="carreraLead"></p>
                                <div class="col-sm-6 d-none" id="select_carreraLead">
                                    <select class="form-control" id="carrera"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Horario</p>
                                <p class="mb-0 text-dark editable" id="horarioLead"></p>
                                <div class="col-sm-6 d-none" id="select_horarioLead">
                                    <select class="form-control" id="horario"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Interés</p>
                                <p class="mb-0 text-dark editable" id="interesLead"></p>
                                <div class="col-sm-6 d-none" id="select_interesLead">
                                    <select class="form-control" id="interes"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Medio</p>
                                <p class="mb-0 text-dark editable" id="medioLead"></p>
                                <div class="col-sm-6 d-none" id="select_medioLead">
                                    <select class="form-control" id="medio"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Fuente</p>
                                <p class="mb-0 text-dark editable" id="fuenteLead"></p>
                                <div class="col-sm-6 d-none" id="select_fuenteLead">
                                    <select class="form-control" id="fuente"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Campaña</p>
                                <p class="mb-0 text-dark editable" id="campanaLead"></p>
                                <div class="col-sm-6 d-none" id="select_campanaLead">
                                    <select class="form-control" id="campana"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Acción</p>
                                <p class="mb-0 text-dark editable" id="accionLead"></p>
                                <div class="col-sm-6 d-none" id="select_accionLead">
                                    <select class="form-control" id="accion"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Departamento</p>
                                <p class="mb-0 text-dark editable" id="departamentoLead"></p>
                                <div class="col-sm-6 d-none" id="select_departamentoLead">
                                    <select class="form-control" id="departamento"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Ciudad</p>
                                <p class="mb-0 text-dark editable" id="ciudadLead"></p>
                                <div class="col-sm-6 d-none" id="select_ciudadLead">
                                    <select id="ciudad" class="form-control"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Barrio</p>
                                <p class="mb-0 text-dark editable" id="barrioLead"></p>
                                <div class="col-sm-6 d-none" id="select_barrioLead">
                                    <select id="barrio" class="form-control"></select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Observaciones</p>
                                <p class="mb-0 text-dark editable" id="observacionesLead"></p>
                                <textarea id="textarea_observacionesLead" cols="2" rows="2" class="form-control d-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Contact Sidebar -->

            <!-- Contact Details -->
            <div class="col-xl-8">
                <div class="mb-3 pb-3 border-bottom">
                    <h5 class="mb-3">Estado del leads principal</h5>
                    <div id="estadoLeadsSecundarioSeguimiento"></div>
                    <!--<div class="step bg-indigo">Not Contacted</div>
                            <div class="step bg-cyan">Contacted</div>
                            <div class="step bg-success">Closed</div>
                            <div class="step bg-orange">Lost</div>
                            <div class="step bg-transparent"></div>-->

                </div>
                <div class="card mb-3">
                    <div class="card-body pb-0 pt-2 px-2">
                        <ul class="nav nav-tabs nav-bordered border-0 mb-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab_1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active border-3" aria-selected="true" role="tab">
                                    <span class="d-md-inline-block"><i class="ti ti-alarm-minus me-1"></i>Actividad</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab_2" data-bs-toggle="tab" aria-expanded="true" class="nav-link border-3" aria-selected="false" role="tab" tabindex="-1">
                                    <span class="d-md-inline-block"><i class="ti ti-notes me-1"></i>Notas</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab_3" data-bs-toggle="tab" aria-expanded="false" class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                    <span class="d-md-inline-block"><i class="ti ti-phone me-1"></i>Llamadas</span>
                                </a>
                            </li>
                            <!--<li class="nav-item" role="presentation">
                                <a href="#tab_4" data-bs-toggle="tab" aria-expanded="false" class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                    <span class="d-md-inline-block"><i class="ti ti-file me-1"></i>Archivos</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab_5" data-bs-toggle="tab" aria-expanded="false" class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                    <span class="d-md-inline-block"><i class="ti ti-mail-check me-1"></i>Correos</span>
                                </a>
                            </li>-->
                            <li class="nav-item" role="presentation">
                                <a href="#tab_6" data-bs-toggle="tab" aria-expanded="false" class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                    <span class="d-md-inline-block"><i class="ti ti-mail-check me-1"></i>Proxima Actividad</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content pt-0">

                    <!-- Activities -->
                    <div class="tab-pane active show" id="tab_1">
                        <div class="card">
                            <div
                                class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h5 class="fw-semibold mb-0">Activities</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-sort-ascending-2 me-2"></i>Sort By</a>
                                    <div class="dropdown-menu">
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Newest</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Oldest</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="timeline-container" class="card-body"></div>
                        </div>
                    </div>
                    <!-- /Activities -->

                    <!-- Notes -->
                    <div class="tab-pane fade" id="tab_2">
                        <div class="card">
                            <div
                                class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h5 class="fw-semibold mb-0">Notas</h5>
                                <div class="d-inline-flex align-items-center">
                                    <div class="dropdown me-2">
                                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-sort-ascending-2 me-2"></i>Ordenar Por</a>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item">Más reciente</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item">Más antiguo</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_notes" class="link-primary fw-medium"><i class="ti ti-circle-plus me-1"></i>Agregar Nota</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="notes-activity">
                                    <div id="contenedorNotas"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Notes -->

                    <!-- Calls -->
                    <div class="tab-pane fade" id="tab_3">
                        <div class="card">
                            <div
                                class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h5 class="fw-semibold mb-0">Llamadas</h5>
                                <div class="d-inline-flex align-items-center">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_call" class="link-primary fw-medium"><i class="ti ti-circle-plus me-1"></i>Agregar llamada</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="listaLlamadas"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /Calls -->

                    <!-- Files -->
                    <div class="tab-pane fade" id="tab_4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="fw-semibold mb-0">Files</h5>
                            </div>
                            <div class="card-body">
                                <div class="card border mb-3">
                                    <div class="card-body pb-0">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <h6 class="mb-1">Manage Documents</h6>
                                                    <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <div class="mb-3">
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_file">Create Document</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border shadow-none mb-3">
                                    <div class="card-body pb-0">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <h6 class="fw-semibold fs-14 mb-1">Collier-Turner Proposal</h6>
                                                    <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                                                    <div class="d-flex align-items-center flex-wrap row-gap-2">
                                                        <span class="avatar avatar-md me-2 flex-shrink-0">
                                                            <img src="assets/img/profiles/avatar-21.jpg" alt="img" class="rounded-circle">
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <p class="mb-0 me-2">Vaughan Lewis</p>
                                                            <span class="badge bg-light text-body">Owner</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <div class="mb-3 d-inline-flex align-items-center">
                                                    <span class="badge badge-soft-danger me-1">Proposal</span>
                                                    <span class="badge bg-info me-1">Draft</span>
                                                    <div class="dropdown">
                                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_file">
                                                                <i class="ti ti-trash me-1"></i>Delete
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">
                                                                <i class="ti ti-download me-1"></i>Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border shadow-none mb-3">
                                    <div class="card-body pb-0">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <h6 class="fw-semibold fs-14 mb-1">Collier-Turner Proposal</h6>
                                                    <p>Send customizable quotes, proposals and contracts to
                                                        close deals faster.</p>
                                                    <div class="d-flex align-items-center flex-wrap row-gap-2">
                                                        <span class="avatar avatar-md me-2 flex-shrink-0">
                                                            <img src="assets/img/profiles/avatar-01.jpg" alt="img" class="rounded-circle">
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <p class="mb-0 me-2">Jessica Louise</p>
                                                            <span class="badge bg-light text-body">Owner</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <div class="mb-3 d-inline-flex align-items-center">
                                                    <span class="badge badge-purple-light me-1">Quote</span>
                                                    <span class="badge bg-success me-1">Sent</span>
                                                    <div class="dropdown">
                                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_file">
                                                                <i class="ti ti-trash me-1"></i>Delete
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">
                                                                <i class="ti ti-download me-1"></i>Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body pb-0">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <h6 class="fw-semibold fs-14 mb-1">Collier-Turner Proposal</h6>
                                                    <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                                                    <div class="d-flex align-items-center flex-wrap row-gap-2">
                                                        <span class="avatar avatar-md me-2 flex-shrink-0">
                                                            <img src="assets/img/profiles/avatar-22.jpg" alt="img" class="rounded-circle">
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <p class="mb-0 me-2">Dawn Merhca</p>
                                                            <span class="badge bg-light text-body">Owner</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <div class="mb-3 d-inline-flex align-items-center">
                                                    <span class="badge badge-danger-light me-1">Proposal</span>
                                                    <span class="badge bg-pending priority-badge me-1">Draft</span>
                                                    <div class="dropdown">
                                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_file">
                                                                <i class="ti ti-trash me-1"></i>Delete
                                                            </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">
                                                                <i class="ti ti-download me-1"></i>Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Files -->

                    <!-- Email -->
                    <div class="tab-pane fade" id="tab_5">
                        <div class="card">
                            <div
                                class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h5 class="mb-1">Email</h5>
                                <div class="d-inline-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-primary fw-medium" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="tooltip-dark" data-bs-original-title="There are no email accounts configured, Please configured your email account in order to Send/ Create EMails"><i class="ti ti-circle-plus me-1"></i>Create Email</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card border mb-0">
                                    <div class="card-body pb-0">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <h6 class="mb-1">Manage Emails</h6>
                                                    <p>You can send and reply to emails directly via this section.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <div class="mb-3">
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#create_email">Connect Account</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Email -->

                    <div class="tab-pane fade" id="tab_6">
                        <div class="card">
                            <div
                                class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h5 class="fw-semibold mb-0">Proxima Actividad</h5>
                                <div class="d-inline-flex align-items-center">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_actividad" class="link-primary fw-medium"><i class="ti ti-circle-plus me-1"></i>Agregar Actividad</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="contenedorActividades"></div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /Tab Content -->

            </div>
            <!-- /Contact Details -->

        </div>
        <!-- Start Footer -->

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