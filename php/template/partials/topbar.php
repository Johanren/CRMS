    <!-- Topbar Start -->
    <header class="navbar-header">
        <div class="page-container topbar-menu">
            <div class="d-flex align-items-center gap-2">

                <!-- Logo -->
                <a href="index.php" class="logo">

                    <!-- Logo Normal -->
                    <span class="logo-light">
                        <span class="logo-lg"><img src="https://multitech.envision.com.co/img/logo.png" alt="logo"></span>
                        <span class="logo-sm"><img src="https://multitech.envision.com.co/img/logo.png" alt="small logo"></span>
                    </span>

                    <!-- Logo Dark -->
                    <span class="logo-dark">
                        <span class="logo-lg"><img src="https://multitech.envision.com.co/img/logo.png" alt="dark logo"></span>
                    </span>
                </a>

                <!-- Sidebar Mobile Button -->
                <a id="mobile_btn" class="mobile-btn" href="#sidebar">
                    <i class="ti ti-menu-deep fs-24"></i>
                </a>

                <button class="sidenav-toggle-btn btn border-0 p-0" id="toggle_btn2">
                    <i class="ti ti-arrow-bar-to-right"></i>
                </button>

                <!-- Search -->
                <div class="me-auto d-flex align-items-center header-search d-lg-flex d-none">
                    <!-- Search -->
                    <div class="input-icon position-relative me-2">
                        <input type="text" class="form-control" placeholder="Search Keyword">
                        <span class="input-icon-addon d-inline-flex p-0 header-search-icon"><i class="ti ti-command"></i></span>
                    </div>
                    <!-- /Search -->
                </div>

            </div>

            <div class="d-flex align-items-center">

                <!-- Search for Mobile -->
                <div class="header-item d-flex d-lg-none me-2">
                    <button class="topbar-link btn" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                        <i class="ti ti-search fs-16"></i>
                    </button>
                </div>


                <!-- Minimize -->
                <div class="header-item">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="btn topbar-link btnFullscreen"><i class="ti ti-maximize"></i></a>
                    </div>
                </div>
                <!-- Minimize -->

                <?php if ($page !== 'layout-mini.php' && $page !== 'layout-hoverview.php' && $page !== 'layout-hidden.php' && $page !== 'layout-fullwidth.php' && $page !== 'layout-rtl.php' && $page !== 'layout-dark.php') {   ?>
                    <!-- Light/Dark Mode Button -->
                    <div class="header-item d-none d-sm-flex me-2">
                        <button class="topbar-link btn topbar-link" id="light-dark-mode" type="button">
                            <i class="ti ti-moon fs-16"></i>
                        </button>
                    </div>
                <?php } ?>

                <!-- pages -->
                <!--<div class="header-item d-none d-sm-flex">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="btn topbar-link topbar-teal-link" data-bs-toggle="dropdown">
                            <i class="ti ti-layout-grid-add"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                            <a href="contacts.php" class="dropdown-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="d-flex mb-1 fw-semibold text-dark">Contacts</span>
                                        <span class="fs-13">View All the Contacts</span>
                                    </div>
                                    <i class="ti ti-chevron-right-pipe text-dark"></i>
                                </div>
                            </a>

                            <a href="pipeline.php" class="dropdown-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="d-flex mb-1 fw-semibold text-dark">Pipeline</span>
                                        <span class="fs-13">View All the Pipeline</span>
                                    </div>
                                    <i class="ti ti-chevron-right-pipe text-dark"></i>
                                </div>
                            </a>
                            <a href="activities.php" class="dropdown-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="d-flex mb-1 fw-semibold text-dark">Activities</span>
                                        <span class="fs-13">Activities</span>
                                    </div>
                                    <i class="ti ti-chevron-right-pipe text-dark"></i>
                                </div>
                            </a>

                            <a href="analytics.php" class="dropdown-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="d-flex mb-1 fw-semibold text-dark">Analytics</span>
                                        <span class="fs-13">Analytics</span>
                                    </div>
                                    <i class="ti ti-chevron-right-pipe text-dark"></i>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>-->

                <!-- faq -->
                <!--<div class="header-item d-none d-sm-flex">
                    <div class="dropdown me-2">
                        <a href="faq.php" class="btn topbar-link topbar-indigo-link"><i class="ti ti-help-hexagon"></i></a>
                    </div> 
                </div>-->

                <!-- report -->
                <!--<div class="header-item d-none d-sm-flex">
                    <div class="dropdown me-2">
                        <a href="lead-reports.php" class="btn topbar-link topbar-warning-link"><i class="ti ti-chart-pie"></i></a>
                    </div> 
                </div>-->

                <div class="header-line"></div>

                <!-- message -->
                <!--<div class="header-item">
                    <div class="dropdown me-2">
                        <a href="chat.php" class="btn topbar-link">
                            <i class="ti ti-message-circle-exclamation"></i>
                            <span class="badge rounded-pill">14</span>
                        </a>
                    </div> 
                </div>-->

                <!-- Notification Dropdown -->
                <div class="header-item">
                    <div class="dropdown me-2">

                        <button class="topbar-link btn topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-bell-check fs-16 animate-ring"></i>
                            <span class="badge rounded-pill"></span>
                        </button>

                        <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">

                            <div class="p-2 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold"> Notificaciones</h6>
                                    </div>
                                </div>
                            </div>
                            <style>
                                /* Ajuste para que el cuerpo sea desplazable y no tape el pie */
                                .notification-body {
                                    max-height: 350px;
                                    /* Ajusta esta altura según tu preferencia */
                                    overflow-y: auto;
                                    /* Habilita el scroll vertical */
                                    overflow-x: hidden;
                                    /* Evita scroll horizontal innecesario */
                                }

                                /* Si usas simplebar, asegúrate de que el contenedor padre tenga posición relativa */
                                .notification-list-container {
                                    display: flex;
                                    flex-direction: column;
                                    max-width: 320px;
                                    /* O el ancho que prefieras para tu dropdown */
                                }
                            </style>
                            <!-- Notification Body -->
                            <div class="notification-list-container">
                                <div class="notification-body position-relative z-2 rounded-0 w-100" data-simplebar id="listaNotificaciones">
                                </div>

                                <div class="p-2 rounded-bottom border-top text-center bg-light">
                                    <a href="notifications.php" class="text-center text-decoration-underline fs-14 mb-0 text-primary">
                                        Ver todas las notificaciones
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown profile-dropdown d-flex align-items-center justify-content-center">
                    <a href="javascript:void(0);" class="topbar-link dropdown-toggle drop-arrow-none position-relative" data-bs-toggle="dropdown" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false">
                        <img src="<?php echo $foto = !empty($_SESSION['foto']) ? 'ajax/' . $_SESSION['foto'] : 'assets/img/users/user-40.jpg'; ?>" width="38" class="rounded-1 d-flex" alt="user-image">
                        <span class="online text-success"><i class="ti ti-circle-filled d-flex bg-white rounded-circle border border-1 border-white"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">

                        <div class="d-flex align-items-center bg-light rounded-3 p-2 mb-2">
                            <img src="<?php echo $foto = !empty($_SESSION['foto']) ? 'ajax/' . $_SESSION['foto'] : 'assets/img/users/user-40.jpg'; ?>" class="rounded-circle" width="42" height="42" alt="Img">
                            <div class="ms-2">
                                <p class="fw-medium text-dark mb-0"><?php echo $_SESSION['user'] ?></p>
                                <span class="d-block fs-13" id="nombreRolUsuario"><?php echo $_SESSION['rol'] ?></span>
                            </div>
                        </div>

                        <!-- Item-->
                        <!--<a href="profile-settings.php" class="dropdown-item">
                            <i class="ti ti-user-circle me-1 align-middle"></i>
                            <span class="align-middle">Profile Settings</span>
                        </a>-->

                        <!-- item -->
                        <!--<div class="form-check form-switch form-check-reverse d-flex align-items-center justify-content-between dropdown-item mb-0">
                            <label class="form-check-label" for="notify"><i class="ti ti-bell"></i>Notifications</label>
                            <input class="form-check-input me-0" type="checkbox" role="switch" id="notify">
                        </div>-->

                        <!-- Item-->
                        <!--<a href="javascript:void(0);" class="dropdown-item">
                            <i class="ti ti-help-circle me-1 align-middle"></i>
                            <span class="align-middle">Help & Support</span>
                        </a>-->

                        <!-- Item-->
                        <!--<a href="profile-settings.php" class="dropdown-item">
                            <i class="ti ti-settings me-1 align-middle"></i>
                            <span class="align-middle">Settings</span>
                        </a>-->

                        <!-- Item-->
                        <div class="pt-2 mt-2 border-top">
                            <a href="salir.php" class="dropdown-item text-danger">
                                <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                                <span class="align-middle">Cerrar Sesion</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>
    <!-- Topbar End -->

    <!-- =========================================
MODAL ALERTA NUEVA NOTIFICACIÓN
(Cambia el id del primer contenedor)
========================================= -->

    <div class="modal fade" id="modalNuevaNotificacion" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xxl-custom">
            <div class="modal-content">

                <div class="modal-header text-white">
                    <h5 class="modal-title fw-bold">
                        🔔 Tienes una nueva notificación
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info mb-3">
                        Se ha generado una nueva operación pendiente.
                    </div>
                    <span class="badge bg-dark fs-12 d-none"><span id="contadorTotalLeads2">0</span> Leads encontrados</span>
                    <div class="table-responsive">
                        <table id="leads_list2" class="table table-bordered table-hover align-middle mb-0">
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

                <div class="modal-footer">
                    <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGestionLead" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xxl-custom">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Gestión de Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">
                    <iframe id="frameGestion"
                        src=""
                        style="width:100%; height:80vh; border:none;">
                    </iframe>
                </div>

            </div>
        </div>
    </div>

    <style>
        .modal-xxl-custom {
            max-width: 95%;
            width: 95%;
        }
    </style>