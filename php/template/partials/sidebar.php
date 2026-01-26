    <!-- Search Modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-transparent">
                <div class="card shadow-none mb-0">
                    <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                        <i class="ti ti-search fs-22"></i>
                        <input type="search" class="form-control border-0" placeholder="Search">
                        <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x fs-22"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidenav Menu Start -->
    <div class="sidebar" id="sidebar">

        <!-- Start Logo -->
        <div class="sidebar-logo">
            <div>
                <!-- Logo Normal -->
                <a href="index.php" class="logo logo-normal">
                    <img src="https://multitech.envision.com.co/img/logo.png" width="120px" alt="Logo">
                </a>

                <!-- Logo Small -->
                <a href="index.php" class="logo-small">
                    <img src="assets/img/logo-small.svg" alt="Logo">
                </a>

                <!-- Logo Dark -->
                <a href="index.php" class="dark-logo">
                    <img src="assets/img/logo-white.svg" alt="Logo">
                </a>
            </div>
            <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
                <i class="ti ti-arrow-bar-to-left"></i>
            </button>

            <!-- Sidebar Menu Close -->
            <button class="sidebar-close">
                <i class="ti ti-x align-middle"></i>
            </button>
        </div>
        <!-- End Logo -->

        <!-- Sidenav Menu -->
        <div class="sidebar-inner" data-simplebar>
            <div id="sidebar-menu" class="sidebar-menu">
                <ul><?php if ($_SESSION['rol'] == "Admin") { ?>
                        <li class="menu-title"><span>Menú Principal</span></li>
                        <li>
                            <ul>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="<?php echo ($page == 'index.php' || $page == '/' || $page == 'leads-dashboard.php' || $page == 'project-dashboard.php') ? 'active subdrop' : ''; ?>">
                                        <i class="ti ti-dashboard"></i><span>Foco</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li class="<?php echo ($page == 'venta.php') ? 'active' : ''; ?>"><a href="venta.php"><span>Crear Foco</span></a></li>
                                        <li><a href="index.php" class="<?php echo ($page == 'index.php' || $page == '/') ? 'active' : ''; ?>">Visualizar Foco</a></li>
                                        <li><a href="resultado_foco.php" class="<?php echo ($page == 'resultado_foco.php' || $page == '/') ? 'active' : ''; ?>">Resultado Foco</a></li>
                                        <li><a href="leads-dashboard.php" class="<?php echo ($page == 'leads-dashboard.php') ? 'active' : ''; ?>">Panel de control de clientes potenciales</a></li>
                                        <li><a href="project-dashboard.php" class="<?php echo ($page == 'project-dashboard.php') ? 'active' : ''; ?>">Panel de control del proyecto</a></li>
                                    </ul>
                                </li>
                                <!--<li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'chat.php' || $page == 'video-call.php' || $page == 'audio-call.php' || $page == 'call-history.php' || $page == 'calendar.php' || $page == 'email.php' || $page == 'email-reply.php'
                                                                            || $page == 'todo.php' || $page == 'todo-list.php' || $page == 'notes.php' || $page == 'file-manager.php' || $page == 'social-feed.php' || $page == 'kanban-view.php' || $page == 'invoice.php' || $page == 'add-invoices.php' || $page == 'edit-invoices.php' || $page == 'invoice-details.php') ? 'active subdrop' : ''; ?>"><i class="ti ti-brand-airtable"></i><span>Applications</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="<?php echo ($page == 'chat.php') ? 'active' : ''; ?>"><a href="chat.php">Chat</a></li>
                                    <li class="submenu submenu-two">
                                        <a href="javascript:void(0);" class="<?php echo ($page == 'video-call.php' || $page == 'audio-call.php' || $page == 'call-history.php') ? 'active subdrop' : ''; ?>">Call<span class="menu-arrow inside-submenu"></span></a>
                                        <ul>
                                            <li><a href="video-call.php" class="<?php echo ($page == 'video-call.php') ? 'active' : ''; ?>">Video Call</a></li>
                                            <li><a href="audio-call.php" class="<?php echo ($page == 'audio-call.php') ? 'active' : ''; ?>">Audio Call</a></li>
                                            <li><a href="call-history.php" class="<?php echo ($page == 'call-history.php') ? 'active' : ''; ?>">Call History</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="calendar.php" class="<?php echo ($page == 'calendar.php') ? 'active' : ''; ?>">Calendar</a></li>
                                    <li><a href="email.php" class="<?php echo ($page == 'email.php' || $page == 'email-reply.php') ? 'active' : ''; ?>">Email</a></li>
                                    <li><a href="todo.php" class="<?php echo ($page == 'todo.php' || $page == 'todo-list.php') ? 'active' : ''; ?>">To Do</a></li>
                                    <li><a href="notes.php" class="<?php echo ($page == 'notes.php') ? 'active' : ''; ?>">Notes</a></li>
                                    <li><a href="file-manager.php" class="<?php echo ($page == 'file-manager.php') ? 'active' : ''; ?>">File Manager</a></li>
                                    <li><a href="social-feed.php" class="<?php echo ($page == 'social-feed.php') ? 'active' : ''; ?>">Social Feed</a></li>
                                    <li><a href="kanban-view.php" class="<?php echo ($page == 'kanban-view.php') ? 'active' : ''; ?>">Kanban</a></li>
                                    <li><a href="invoice.php" class="<?php echo ($page == 'invoice.php' || $page == 'add-invoices.php' || $page == 'edit-invoices.php' || $page == 'invoice-details.php') ? 'active' : ''; ?>">Invoices</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#" class="<?php echo ($page == 'dashboard.php' || $page == 'company.php' || $page == 'subscription.php' || $page == 'packages.php' || $page == 'domain.php' || $page == 'purchase-transaction.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-user-star"></i><span>Super Admin</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="dashboard.php" class="<?php echo ($page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a></li>
                                    <li><a href="company.php" class="<?php echo ($page == 'company.php') ? 'active' : ''; ?>">Companies</a></li>
                                    <li><a href="subscription.php" class="<?php echo ($page == 'subscription.php') ? 'active' : ''; ?>">Subscriptions</a></li>
                                    <li><a href="packages.php" class="<?php echo ($page == 'packages.php') ? 'active' : ''; ?>">Packages</a></li>
                                    <li><a href="domain.php" class="<?php echo ($page == 'domain.php') ? 'active' : ''; ?>">Domain</a></li>
                                    <li><a href="purchase-transaction.php" class="<?php echo ($page == 'purchase-transaction.php') ? 'active' : ''; ?>">Purchase Transaction</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#" class="<?php echo ($page == 'layout-mini.php' || $page == 'layout-hoverview.php' || $page == 'layout-hidden.php' || $page == 'layout-fullwidth.php' || $page == 'layout-rtl.php' || $page == 'layout-dark.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-layout-grid"></i><span>Layouts</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="layout-mini.php" class="<?php echo ($page == 'layout-mini.php') ? 'active' : ''; ?>">Mini</a></li>
                                    <li><a href="layout-hoverview.php" class="<?php echo ($page == 'layout-hoverview.php') ? 'active' : ''; ?>">Hover View</a></li>
                                    <li><a href="layout-hidden.php" class="<?php echo ($page == 'layout-hidden.php') ? 'active' : ''; ?>">Hidden</a></li>
                                    <li><a href="layout-fullwidth.php" class="<?php echo ($page == 'layout-fullwidth.php') ? 'active' : ''; ?>">Full Width</a></li>
                                    <li><a href="layout-rtl.php" class="<?php echo ($page == 'layout-rtl.php') ? 'active' : ''; ?>">RTL</a></li>
                                    <li><a href="layout-dark.php" class="<?php echo ($page == 'layout-dark.php') ? 'active' : ''; ?>">Dark</a></li>
                                </ul>
                            </li>-->
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="menu-title"><span>CRM</span></li>
                    <li>
                        <ul>
                            <?php if ($_SESSION['rol'] == "Admin") { ?>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'info_origen.php' || $page == '/' || $page == 'ciudad.php' || $page == 'barrio.php' || $page == 'programa.php' || $page == 'jornada.php' || $page == 'interes.php' || $page == 'medio.php' || $page == 'fuente.php' || $page == 'accion.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-dashboard"></i><span>Información Origen</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li class="<?php echo ($page == 'info_origen.php') ? 'active' : ''; ?>"><a href="info_origen.php"><span>Departamento</span></a></li>
                                    <li><a href="ciudad.php" class="<?php echo ($page == 'ciudad.php' || $page == '/') ? 'active' : ''; ?>">Ciudad</a></li>
                                    <li><a href="barrio.php" class="<?php echo ($page == 'barrio.php') ? 'active' : ''; ?>">Barrio</a></li>
                                    <li><a href="programa.php" class="<?php echo ($page == 'programa.php') ? 'active' : ''; ?>">Programa</a></li>
                                    <li><a href="jornada.php" class="<?php echo ($page == 'jornada.php') ? 'active' : ''; ?>">Jornada</a></li>
                                    <li><a href="interes.php" class="<?php echo ($page == 'interes.php') ? 'active' : ''; ?>">Nivel de interes</a></li>
                                    <li><a href="medio.php" class="<?php echo ($page == 'medio.php') ? 'active' : ''; ?>">Medio</a></li>
                                    <li><a href="fuente.php" class="<?php echo ($page == 'fuente.php') ? 'active' : ''; ?>">Fuente</a></li>
                                    <li><a href="accion.php" class="<?php echo ($page == 'accion.php') ? 'active' : ''; ?>">Acción</a></li>
                                </ul>
                            </li>
                            <?php } ?>
                            <li class="<?php echo ($page == 'contacts.php' || $page == 'contacts-list.php' || $page == 'contact-details.php') ? 'active' : ''; ?>">
                                <a href="contacts.php"><i class="ti ti-user-up"></i><span>Contactos</span></a>
                            </li>
                            <!--<li class="<?php echo ($page == 'companies.php' || $page == 'companies-list.php' || $page == 'company-details.php') ? 'active' : ''; ?>">
                                <a href="companies.php"><i class="ti ti-building-community"></i><span>Companies</span></a>-->
                    </li>
                    <li class="<?php echo ($page == 'deals.php' || $page == 'deals-list.php' || $page == 'deals-details.php') ? 'active' : ''; ?>">
                        <a href="deals.php"><i class="ti ti-medal"></i><span>Referidos</span></a>
                    </li>
                    <li class="<?php echo ($page == 'leads.php' || $page == 'leads-list.php' || $page == 'leads-details.php') ? 'active' : ''; ?>">
                        <a href="leads.php"><i class="ti ti-chart-arcs"></i><span>Leads</span></a>
                    </li>
                    <li class="<?php echo ($page == 'resultado_foco.php') ? 'active' : ''; ?>">
                        <a href="resultado_foco.php"><i class="ti ti-dashboard"></i><span>Resultado Foco</span></a>
                    </li>
                    <!--<li class="<?php echo ($page == 'pipeline.php') ? 'active' : ''; ?>">
                        <a href="pipeline.php"><i class="ti ti-timeline-event-exclamation"></i><span>PipeLine</span></a>
                    </li>
                    <li class="<?php echo ($page == 'campaign.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php') ? 'active' : ''; ?>">
                        <a href="campaign.php"><i class="ti ti-brand-campaignmonitor"></i><span>Campaña</span></a>
                    </li>
                    <li class="<?php echo ($page == 'projects.php' || $page == 'projects-list.php' || $page == 'project-details.php') ? 'active' : ''; ?>">
                                <a href="projects.php"><i class="ti ti-atom-2"></i><span>Projects</span></a>
                            </li>
                            <li class="<?php echo ($page == 'tasks.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php') ? 'active' : ''; ?>">
                                <a href="tasks.php"><i class="ti ti-list-check"></i><span>Tasks</span></a>
                            </li>
                            <li class="<?php echo ($page == 'proposals.php' || $page == 'proposals-list.php') ? 'active' : ''; ?>">
                                <a href="proposals.php"><i class="ti ti-file-star"></i><span>Proposals</span></a>
                            </li>
                            <li class="<?php echo ($page == 'contracts.php' || $page == 'contracts-list.php' || $page == 'contracts-details.php') ? 'active' : ''; ?>">
                                <a href="contracts.php"><i class="ti ti-file-check"></i><span>Contracts</span></a>
                            </li>
                            <li class="<?php echo ($page == 'estimations.php' || $page == 'estimations-list.php') ? 'active' : ''; ?>">
                                <a href="estimations.php"><i class="ti ti-file-report"></i><span>Estimations</span></a>
                            </li>
                            <li class="<?php echo ($page == 'invoices.php' || $page == 'invoices-details.php' || $page == 'invoice-list.php') ? 'active' : ''; ?>">
                                <a href="invoices.php"><i class="ti ti-file-invoice"></i><span>Invoices</span></a>
                            </li>
                            <li class="<?php echo ($page == 'payments.php') ? 'active' : ''; ?>">
                                <a href="payments.php"><i class="ti ti-report-money"></i><span>Payments</span></a>
                            </li>
                            <li class="<?php echo ($page == 'analytics.php') ? 'active' : ''; ?>">
                                <a href="analytics.php"><i class="ti ti-chart-bar"></i><span>Analytics</span></a>
                            </li>
                            <li class="<?php echo ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-meeting.php' || $page == 'activity-mail.php' || $page == 'activity-task.php') ? 'active' : ''; ?>">
                                <a href="activities.php"><i class="ti ti-bounce-right"></i><span>Activities</span></a>-->
                    </li>
                </ul>
                </li>
                <li class="menu-title"><span>Herramientas</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="<?php echo ($page == 'rst_frm_dia.php') ? 'active subdrop' : ''; ?>">
                                <i class="ti ti-report-analytics"></i><span>Herramientas</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="mensajes.php" class="<?php echo ($page == 'mensajes.php' || $page == '/') ? 'active' : ''; ?>">Mensaje</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="menu-title"><span>Informes</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="<?php echo ($page == 'rst_frm_dia.php' || $page == 'lead-reports.php' || $page == 'deal-reports.php' || $page == 'dashboard.php' || $page == 'contact-reports.php' || $page == 'company-reports.php' || $page == 'project-reports.php' || $page == 'task-reports.php') ? 'active subdrop' : ''; ?>">
                                <i class="ti ti-report-analytics"></i><span>Informes</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="index.php" class="<?php echo ($page == 'index.php' || $page == '/') ? 'active' : ''; ?>">Visualizar Foco</a></li>
                                <li><a href="lead-reports.php" class="<?php echo ($page == 'lead-reports.php') ? 'active' : ''; ?>">Informes de clientes potenciales</a></li>
                                <li><a href="leads-reports.php" class="<?php echo ($page == 'leads-reports.php') ? 'active' : ''; ?>">Informes de leads</a></li>
                                <li><a href="contact-reports.php" class="<?php echo ($page == 'contact-reports.php') ? 'active' : ''; ?>">Informes de matriculados</a></li>
                                <li><a href="dashboard.php" class="<?php echo ($page == 'dashboard.php') ? 'active' : ''; ?>">Informes por asesor</a></li>
                                <li><a href="rst_frm.php" class="<?php echo ($page == 'rst_frm.php') ? 'active' : ''; ?>">Informes rst_frm</a></li>
                                <li><a href="rst_frm_dia.php" class="<?php echo ($page == 'rst_frm_dia.php') ? 'active' : ''; ?>">Informes RST dia</a></li>
                                <!--<li><a href="company-reports.php" class="<?php echo ($page == 'company-reports.php') ? 'active' : ''; ?>">Company Reports</a></li>
                                    <li><a href="project-reports.php" class="<?php echo ($page == 'project-reports.php') ? 'active' : ''; ?>">Project Reports</a></li>
                                    <li><a href="task-reports.php" class="<?php echo ($page == 'task-reports.php') ? 'active' : ''; ?>">Task Reports</a></li>-->
                            </ul>
                        </li>
                    </ul>
                </li>
                <!--<li class="menu-title"><span>CRM Settings</span></li>
                    <li>
                        <ul>
                            <li class="<?php echo ($page == 'sources.php') ? 'active' : ''; ?>"><a href="sources.php"><i class="ti ti-artboard"></i><span>Sources</span></a></li>
                            <li class="<?php echo ($page == 'lost-reason.php') ? 'active' : ''; ?>"><a href="lost-reason.php"><i class="ti ti-message-exclamation"></i><span>Lost Reason</span></a></li>
                            <li class="<?php echo ($page == 'contact-stage.php') ? 'active' : ''; ?>"><a href="contact-stage.php"><i class="ti ti-steam"></i><span>Contact Stage</span></a></li>
                            <li class="<?php echo ($page == 'industry.php') ? 'active' : ''; ?>"><a href="industry.php"><i class="ti ti-building-factory"></i><span>Industry</span></a></li>
                            <li class="<?php echo ($page == 'calls.php') ? 'active' : ''; ?>"><a href="calls.php"><i class="ti ti-phone-check"></i><span>Calls</span></a></li>
                        </ul>
                    </li>-->
                <?php if ($_SESSION['rol'] == "Admin") { ?>
                    <li class="menu-title"><span>Gestion de usuarios</span></li>
                    <li>
                        <ul>
                            <li class="<?php echo ($page == 'manage-users.php') ? 'active' : ''; ?>"><a href="manage-users.php"><i class="ti ti-users"></i><span>Administrar usuarios</span></a></li>
                            <li class="<?php echo ($page == 'roles-permissions.php' || $page == 'permission.php') ? 'active' : ''; ?>"><a href="roles-permissions.php"><i class="ti ti-user-shield"></i><span>Roles y permisos</span></a></li>
                            <li class="<?php echo ($page == 'delete-request.php') ? 'active' : ''; ?>"><a href="delete-request.php"><i class="ti ti-flag-question"></i><span>Solicitud de eliminación</span></a></li>
                        </ul>
                    </li>
                <?php } ?>
                <!--<li class="menu-title"><span>Membership</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'membership-plans.php' || $page == 'membership-addons.php' || $page == 'membership-transactions.php') ? 'active subrop' : ''; ?>">
                                    <i class="ti ti-brand-apple-podcast"></i><span>Membership</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="membership-plans.php" class="<?php echo ($page == 'membership-plans.php') ? 'active' : ''; ?>">Membership Plans</a></li>
                                    <li><a href="membership-addons.php" class="<?php echo ($page == 'membership-addons.php') ? 'active' : ''; ?>">Membership Addons</a></li>
                                    <li><a href="membership-transactions.php" class="<?php echo ($page == 'membership-transactions.php') ? 'active' : ''; ?>">Transactions</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Content</span></li>
                    <li>
                        <ul>
                            <li class="<?php echo ($page == 'pages.php' || $page == 'add-page.php' || $page == 'edit-page.php') ? 'active' : ''; ?>"><a href="pages.php"><i class="ti ti-page-break"></i><span>Pages</span></a></li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'blogs.php' || $page == 'add-blog.php' || $page == 'edit-blog.php' || $page == 'blog-details.php' || $page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-brand-blogger"></i><span>Blog</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="blogs.php" class="<?php echo ($page == 'blogs.php' || $page == 'add-blog.php' || $page == 'edit-blog.php' || $page == 'blog-details.php') ? 'active' : ''; ?>">All Blogs</a></li>
                                    <li><a href="blog-categories.php" class="<?php echo ($page == 'blog-categories.php') ? 'active' : ''; ?>">Blog Categories</a></li>
                                    <li><a href="blog-comments.php" class="<?php echo ($page == 'blog-comments.php') ? 'active' : ''; ?>">Blog Comments</a></li>
                                    <li><a href="blog-tags.php" class="<?php echo ($page == 'blog-tags.php') ? 'active' : ''; ?>">Blog Tags</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'countries.php' || $page == 'states.php' || $page == 'cities.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-map-pin-pin"></i><span>Location</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="countries.php" class="<?php echo ($page == 'countries.php') ? 'active' : ''; ?>">Countries</a></li>
                                    <li><a href="states.php" class="<?php echo ($page == 'states.php') ? 'active' : ''; ?>">States</a></li>
                                    <li><a href="cities.php" class="<?php echo ($page == 'cities.php') ? 'active' : ''; ?>">Cities</a></li>
                                </ul>
                            </li>                                
                            <li class="<?php echo ($page == 'testimonials.php') ? 'active' : ''; ?>"><a href="testimonials.php"><i class="ti ti-quote"></i><span>Testimonials</span></a></li>
                            <li class="<?php echo ($page == 'faq.php') ? 'active' : ''; ?>"><a href="faq.php"><i class="ti ti-question-mark"></i><span>FAQ</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Support</span></li>
                    <li>
                        <ul>
                            <li class="<?php echo ($page == 'contact-messages.php') ? 'active' : ''; ?>"><a href="contact-messages.php"><i class="ti ti-message-check"></i><span>Contact Messages</span></a></li>
                            <li class="<?php echo ($page == 'tickets.php' || $page == 'ticket-details.php') ? 'active' : ''; ?>"><a href="tickets.php"><i class="ti ti-ticket"></i><span>Tickets</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Settings</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'profile-settings.php' || $page == 'security-settings.php' || $page == 'notifications-settings.php' || $page == 'connected-apps.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-settings-cog"></i><span>General Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="profile-settings.php" class="<?php echo ($page == 'profile-settings.php') ? 'active' : ''; ?>">Profile</a></li>
                                    <li><a href="security-settings.php" class="<?php echo ($page == 'security-settings.php') ? 'active' : ''; ?>">Security</a></li>
                                    <li><a href="notifications-settings.php" class="<?php echo ($page == 'notifications-settings.php') ? 'active' : ''; ?>">Notifications</a></li>
                                    <li><a href="connected-apps.php" class="<?php echo ($page == 'connected-apps.php') ? 'active' : ''; ?>">Connected Apps</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'company-settings.php' || $page == 'localization-settings.php' || $page == 'prefixes-settings.php' || $page == 'preference-settings.php' || $page == 'appearance-settings.php' || $page == 'language-settings.php' || $page == 'language-web.php' || $page == 'language-web-edit.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-world-cog"></i><span>Website Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="company-settings.php" class="<?php echo ($page == 'company-settings.php') ? 'active' : ''; ?>">Company Settings</a></li>
                                    <li><a href="localization-settings.php" class="<?php echo ($page == 'localization-settings.php') ? 'active' : ''; ?>">Localization</a></li>
                                    <li><a href="prefixes-settings.php" class="<?php echo ($page == 'prefixes-settings.php') ? 'active' : ''; ?>">Prefixes</a></li>
                                    <li><a href="preference-settings.php" class="<?php echo ($page == 'preference-settings.php') ? 'active' : ''; ?>">Preference</a></li>
                                    <li><a href="appearance-settings.php" class="<?php echo ($page == 'appearance-settings.php') ? 'active' : ''; ?>">Appearance</a></li>
                                    <li><a href="language-settings.php" class="<?php echo ($page == 'language-settings.php' || $page == 'language-web.php' || $page == 'language-web-edit.php') ? 'active' : ''; ?>">Language</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'invoice-settings.php' || $page == 'printers-settings.php' || $page == 'custom-fields-setting.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-apps"></i><span>App Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="invoice-settings.php" class="<?php echo ($page == 'invoice-settings.php') ? 'active' : ''; ?>">Invoice Settings</a></li>
                                    <li><a href="printers-settings.php" class="<?php echo ($page == 'printers-settings.php') ? 'active' : ''; ?>">Printers</a></li>
                                    <li><a href="custom-fields-setting.php" class="<?php echo ($page == 'custom-fields-setting.php') ? 'active' : ''; ?>">Custom Fields</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'email-settings.php' || $page == 'sms-gateways.php' || $page == 'gdpr-cookies.php') ? 'active subrop' : ''; ?>">
                                    <i class="ti ti-device-laptop"></i><span>System Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="email-settings.php" class="<?php echo ($page == 'email-settings.php') ? 'active' : ''; ?>">Email Settings</a></li>
                                    <li><a href="sms-gateways.php" class="<?php echo ($page == 'sms-gateways.php') ? 'active' : ''; ?>">SMS Gateways</a></li>
                                    <li><a href="gdpr-cookies.php" class="<?php echo ($page == 'gdpr-cookies.php') ? 'active' : ''; ?>">GDPR Cookies</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'payment-gateways.php' || $page == 'bank-accounts.php' || $page == 'tax-rates.php' || $page == 'currencies.php') ? 'active subrop' : ''; ?>">
                                    <i class="ti ti-moneybag"></i><span>Financial Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="payment-gateways.php" class="<?php echo ($page == 'payment-gateways.php') ? 'active' : ''; ?>">Payment Gateways</a></li>
                                    <li><a href="bank-accounts.php" class="<?php echo ($page == 'bank-accounts.php') ? 'active' : ''; ?>">Bank Accounts</a></li>
                                    <li><a href="tax-rates.php" class="<?php echo ($page == 'tax-rates.php') ? 'active' : ''; ?>">Tax Rates</a></li>
                                    <li><a href="currencies.php" class="<?php echo ($page == 'currencies.php') ? 'active' : ''; ?>">Currencies</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'sitemap.php' || $page == 'clear-cache.php' || $page == 'storage.php' || $page == 'cronjob.php' || $page == 'ban-ip-address.php' || $page == 'system-backup.php' || $page == 'database-backup.php' || $page == 'system-update.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-settings-2"></i><span>Other Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="sitemap.php" class="<?php echo ($page == 'sitemap.php') ? 'active' : ''; ?>">Sitemap</a></li>
                                    <li><a href="clear-cache.php" class="<?php echo ($page == 'clear-cache.php') ? 'active' : ''; ?>">Clear Cache</a></li>
                                    <li><a href="storage.php" class="<?php echo ($page == 'storage.php') ? 'active' : ''; ?>">Storage</a></li>
                                    <li><a href="cronjob.php" class="<?php echo ($page == 'cronjob.php') ? 'active' : ''; ?>">Cronjob</a></li>
                                    <li><a href="ban-ip-address.php" class="<?php echo ($page == 'ban-ip-address.php') ? 'active' : ''; ?>">Ban IP Address</a></li>
                                    <li><a href="system-backup.php" class="<?php echo ($page == 'system-backup.php') ? 'active' : ''; ?>">System Backup</a></li>
                                    <li><a href="database-backup.php" class="<?php echo ($page == 'database-backup.php') ? 'active' : ''; ?>">Database Backup</a></li>
                                    <li><a href="system-update.php" class="<?php echo ($page == 'system-update.php') ? 'active' : ''; ?>">System Update</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Pages</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-lock-square-rounded"></i><span>Authentication</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="login.php">Login</a></li>
                                    <li><a href="register.php">Register</a></li>
                                    <li><a href="forgot-password.php">Forgot Password</a></li>
                                    <li><a href="reset-password.php">Reset Password</a></li>
                                    <li><a href="email-verification.php">Email Verification</a></li>
                                    <li><a href="two-step-verification.php">2 Step Verification</a></li>
                                    <li><a href="lock-screen.php">Lock Screen</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-error-404"></i><span>Error Pages</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="error-404.php">404 Error</a></li>
                                    <li><a href="error-500.php">500 Error</a></li>
                                </ul>
                            </li>
                            <li class="<?php echo ($page == 'blank-page.php') ? 'active' : ''; ?>"><a href="blank-page.php"><i class="ti ti-file"></i><span>Blank Page</span></a></li>
                            <li><a href="coming-soon.php"><i class="ti ti-inner-shadow-top-right"></i><span>Coming Soon</span></a></li>
                            <li><a href="under-maintenance.php"><i class="ti ti-info-triangle"></i><span>Under Maintenance</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>UI Interface</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'ui-accordion.php' || $page == 'ui-alerts.php' || $page == 'ui-avatar.php' || $page == 'ui-badges.php' || $page == 'ui-breadcrumb.php' || $page == 'ui-buttons.php'
                                                                            || $page == 'ui-buttons-group.php' || $page == 'ui-cards.php' || $page == 'ui-carousel.php' || $page == 'ui-collapse.php' || $page == 'ui-dropdowns.php' || $page == 'ui-ratio.php' || $page == 'ui-grid.php'
                                                                            || $page == 'ui-images.php' || $page == 'ui-links.php' || $page == 'ui-list-group.php' || $page == 'ui-modals.php' || $page == 'ui-offcanvas.php' || $page == 'ui-pagination.php' || $page == 'ui-placeholders.php' || $page == 'ui-popovers.php'
                                                                            || $page == 'ui-progress.php' || $page == 'ui-scrollspy.php' || $page == 'ui-spinner.php' || $page == 'ui-nav-tabs.php' || $page == 'ui-toasts.php' || $page == 'ui-tooltips.php' || $page == 'ui-typography.php' || $page == 'ui-utilities.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-hierarchy"></i><span>Base UI</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="ui-accordion.php" class="<?php echo ($page == 'ui-accordion.php') ? 'active' : ''; ?>">Accordion</a></li>
                                    <li><a href="ui-alerts.php" class="<?php echo ($page == 'ui-alerts.php') ? 'active' : ''; ?>">Alerts</a></li>
                                    <li><a href="ui-avatar.php" class="<?php echo ($page == 'ui-avatar.php') ? 'active' : ''; ?>">Avatar</a></li>
                                    <li><a href="ui-badges.php" class="<?php echo ($page == 'ui-badges.php') ? 'active' : ''; ?>">Badges</a></li>
                                    <li><a href="ui-breadcrumb.php" class="<?php echo ($page == 'ui-breadcrumb.php') ? 'active' : ''; ?>">Breadcrumb</a></li>
                                    <li><a href="ui-buttons.php" class="<?php echo ($page == 'ui-buttons.php') ? 'active' : ''; ?>">Buttons</a></li>
                                    <li><a href="ui-buttons-group.php" class="<?php echo ($page == 'ui-buttons-group.php') ? 'active' : ''; ?>">Button Group</a></li>
                                    <li><a href="ui-cards.php" class="<?php echo ($page == 'ui-cards.php') ? 'active' : ''; ?>">Card</a></li>
                                    <li><a href="ui-carousel.php" class="<?php echo ($page == 'ui-carousel.php') ? 'active' : ''; ?>">Carousel</a></li>
                                    <li><a href="ui-collapse.php" class="<?php echo ($page == 'ui-collapse.php') ? 'active' : ''; ?>">Collapse</a></li>
                                    <li><a href="ui-dropdowns.php" class="<?php echo ($page == 'ui-dropdowns.php') ? 'active' : ''; ?>">Dropdowns</a></li>
                                    <li><a href="ui-ratio.php" class="<?php echo ($page == 'ui-ratio.php') ? 'active' : ''; ?>">Ratio</a></li>
                                    <li><a href="ui-grid.php" class="<?php echo ($page == 'ui-grid.php') ? 'active' : ''; ?>">Grid</a></li>
                                    <li><a href="ui-images.php" class="<?php echo ($page == 'ui-images.php') ? 'active' : ''; ?>">Images</a></li>
                                    <li><a href="ui-links.php" class="<?php echo ($page == 'ui-links.php') ? 'active' : ''; ?>">Links</a></li>
                                    <li><a href="ui-list-group.php" class="<?php echo ($page == 'ui-list-group.php') ? 'active' : ''; ?>">List Group</a></li>
                                    <li><a href="ui-modals.php" class="<?php echo ($page == 'ui-modals.php') ? 'active' : ''; ?>">Modals</a></li>
                                    <li><a href="ui-offcanvas.php" class="<?php echo ($page == 'ui-offcanvas.php') ? 'active' : ''; ?>">Offcanvas</a></li>
                                    <li><a href="ui-pagination.php" class="<?php echo ($page == 'ui-pagination.php') ? 'active' : ''; ?>">Pagination</a></li>
                                    <li><a href="ui-placeholders.php" class="<?php echo ($page == 'ui-placeholders.php') ? 'active' : ''; ?>">Placeholders</a></li>
                                    <li><a href="ui-popovers.php" class="<?php echo ($page == 'ui-popovers.php') ? 'active' : ''; ?>">Popovers</a></li>
                                    <li><a href="ui-progress.php" class="<?php echo ($page == 'ui-progress.php') ? 'active' : ''; ?>">Progress</a></li>
                                    <li><a href="ui-scrollspy.php" class="<?php echo ($page == 'ui-scrollspy.php') ? 'active' : ''; ?>">Scrollspy</a></li>
                                    <li><a href="ui-spinner.php" class="<?php echo ($page == 'ui-spinner.php') ? 'active' : ''; ?>">Spinner</a></li>
                                    <li><a href="ui-nav-tabs.php" class="<?php echo ($page == 'ui-nav-tabs.php') ? 'active' : ''; ?>">Tabs</a></li>
                                    <li><a href="ui-toasts.php" class="<?php echo ($page == 'ui-toasts.php') ? 'active' : ''; ?>">Toasts</a></li>
                                    <li><a href="ui-tooltips.php" class="<?php echo ($page == 'ui-tooltips.php') ? 'active' : ''; ?>">Tooltips</a></li>
                                    <li><a href="ui-typography.php" class="<?php echo ($page == 'ui-typography.php') ? 'active' : ''; ?>">Typography</a></li>
                                    <li><a href="ui-utilities.php" class="<?php echo ($page == 'ui-utilities.php') ? 'active' : ''; ?>">Utilities</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'ui-dragula.php' || $page == 'ui-clipboard.php' || $page == 'ui-rangeslider.php' || $page == 'ui-sweetalerts.php' || $page == 'ui-lightbox.php' || $page == 'ui-rating.php' || $page == 'ui-scrollbar.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-whirl"></i><span>Advanced UI</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="ui-dragula.php" class="<?php echo ($page == 'ui-dragula.php') ? 'active' : ''; ?>">Dragula</a></li>
                                    <li><a href="ui-clipboard.php" class="<?php echo ($page == 'ui-clipboard.php') ? 'active' : ''; ?>">Clipboard</a></li>
                                    <li><a href="ui-rangeslider.php" class="<?php echo ($page == 'ui-rangeslider.php') ? 'active' : ''; ?>">Range Slider</a></li>
                                    <li><a href="ui-sweetalerts.php" class="<?php echo ($page == 'ui-sweetalerts.php') ? 'active' : ''; ?>">Sweet Alerts</a></li>
                                    <li><a href="ui-lightbox.php" class="<?php echo ($page == 'ui-lightbox.php') ? 'active' : ''; ?>">Lightbox</a></li>
                                    <li><a href="ui-rating.php" class="<?php echo ($page == 'ui-rating.php') ? 'active' : ''; ?>">Rating</a></li>
                                    <li><a href="ui-scrollbar.php" class="<?php echo ($page == 'ui-scrollbar.php') ? 'active' : ''; ?>">Scrollbar</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'form-basic-inputs.php' || $page == 'form-checkbox-radios.php' || $page == 'form-input-groups.php' || $page == 'form-grid-gutters.php' || $page == 'form-mask.php' || $page == 'form-fileupload.php' || $page == 'form-horizontal.php' || $page == 'form-vertical.php' || $page == 'form-floating-labels.php'
                                                                            || $page == 'form-validation.php' || $page == 'form-select.php' || $page == 'form-wizard.php' || $page == 'form-pickers.php' || $page == 'form-editors.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-forms"></i><span>Forms</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li class="submenu submenu-two">
                                        <a href="javascript:void(0);" class="<?php echo ($page == 'form-basic-inputs.php' || $page == 'form-checkbox-radios.php' || $page == 'form-input-groups.php' || $page == 'form-grid-gutters.php' || $page == 'form-mask.php' || $page == 'form-fileupload.php') ? 'active subdrop' : ''; ?>">Form Elements<span class="menu-arrow inside-submenu"></span></a>
                                        <ul>
                                            <li><a href="form-basic-inputs.php" class="<?php echo ($page == 'form-basic-inputs.php') ? 'active' : ''; ?>">Basic Inputs</a></li>
                                            <li><a href="form-checkbox-radios.php" class="<?php echo ($page == 'form-checkbox-radios.php') ? 'active' : ''; ?>">Checkbox & Radios</a></li>
                                            <li><a href="form-input-groups.php" class="<?php echo ($page == 'form-input-groups.php') ? 'active' : ''; ?>">Input Groups</a></li>
                                            <li><a href="form-grid-gutters.php" class="<?php echo ($page == 'form-grid-gutters.php') ? 'active' : ''; ?>">Grid & Gutters</a></li>
                                            <li><a href="form-mask.php" class="<?php echo ($page == 'form-mask.php') ? 'active' : ''; ?>">Input Masks</a></li>
                                            <li><a href="form-fileupload.php" class="<?php echo ($page == 'form-fileupload.php') ? 'active' : ''; ?>">File Uploads</a></li>
                                        </ul>
                                    </li>
                                    <li class="submenu submenu-two">
                                        <a href="javascript:void(0);" class="<?php echo ($page == 'form-horizontal.php' || $page == 'form-vertical.php' || $page == 'form-floating-labels.php') ? 'active subdrop' : ''; ?>">Layouts<span class="menu-arrow inside-submenu"></span></a>
                                        <ul>
                                            <li><a href="form-horizontal.php" class="<?php echo ($page == 'form-horizontal.php') ? 'active' : ''; ?>">Horizontal Form</a></li>
                                            <li><a href="form-vertical.php" class="<?php echo ($page == 'form-vertical.php') ? 'active' : ''; ?>">Vertical Form</a></li>
                                            <li><a href="form-floating-labels.php" class="<?php echo ($page == 'form-floating-labels.php') ? 'active' : ''; ?>">Floating Labels</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="form-validation.php" class="<?php echo ($page == 'form-validation.php') ? 'active' : ''; ?>">Form Validation</a></li>
                                    <li><a href="form-select.php" class="<?php echo ($page == 'form-select.php') ? 'active' : ''; ?>">Form Select</a></li>
                                    <li><a href="form-wizard.php" class="<?php echo ($page == 'form-wizard.php') ? 'active' : ''; ?>">Form Wizard</a></li>
                                    <li><a href="form-pickers.php" class="<?php echo ($page == 'form-pickers.php') ? 'active' : ''; ?>">Form Picker</a></li>
                                    <li><a href="form-editors.php" class="<?php echo ($page == 'form-editors.php') ? 'active' : ''; ?>">Form Editors</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'tables-basic.php' || $page == 'data-tables.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-table"></i><span>Tables</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="tables-basic.php" class="<?php echo ($page == 'tables-basic.php') ? 'active' : ''; ?>">Basic Tables </a></li>
                                    <li><a href="data-tables.php" class="<?php echo ($page == 'data-tables.php') ? 'active' : ''; ?>">Data Table </a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'chart-apex.php' || $page == 'chart-c3.php' || $page == 'chart-js.php' || $page == 'chart-morris.php' || $page == 'chart-flot.php' || $page == 'chart-peity.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-chart-pie-3"></i><span>Charts</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="chart-apex.php" class="<?php echo ($page == 'chart-apex.php') ? 'active' : ''; ?>">Apex Charts</a></li>
                                    <li><a href="chart-c3.php" class="<?php echo ($page == 'chart-c3.php') ? 'active' : ''; ?>">Chart C3</a></li>
                                    <li><a href="chart-js.php" class="<?php echo ($page == 'chart-js.php') ? 'active' : ''; ?>">Chart Js</a></li>
                                    <li><a href="chart-morris.php" class="<?php echo ($page == 'chart-morris.php') ? 'active' : ''; ?>">Morris Charts</a></li>
                                    <li><a href="chart-flot.php" class="<?php echo ($page == 'chart-flot.php') ? 'active' : ''; ?>">Flot Charts</a></li>
                                    <li><a href="chart-peity.php" class="<?php echo ($page == 'chart-peity.php') ? 'active' : ''; ?>">Peity Charts</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'icon-fontawesome.php' || $page == 'icon-tabler.php' || $page == 'icon-bootstrap.php' || $page == 'icon-remix.php' || $page == 'icon-feather.php' || $page == 'icon-ionic.php'
                                                                            || $page == 'icon-material.php' || $page == 'icon-pe7.php' || $page == 'icon-simpleline.php' || $page == 'icon-themify.php' || $page == 'icon-weather.php' || $page == 'icon-typicon.php' || $page == 'icon-flag.php') ? 'active subdrop' : ''; ?>">
                                    <i class="ti ti-icons"></i><span>Icons</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="icon-fontawesome.php" class="<?php echo ($page == 'icon-fontawesome.php') ? 'active' : ''; ?>">Fontawesome Icons</a></li>
                                    <li><a href="icon-tabler.php" class="<?php echo ($page == 'icon-tabler.php') ? 'active' : ''; ?>">Tabler Icons</a></li>
                                    <li><a href="icon-bootstrap.php" class="<?php echo ($page == 'icon-bootstrap.php') ? 'active' : ''; ?>">Bootstrap Icons</a></li>
                                    <li><a href="icon-remix.php" class="<?php echo ($page == 'icon-remix.php') ? 'active' : ''; ?>">Remix Icons</a></li>
                                    <li><a href="icon-feather.php" class="<?php echo ($page == 'icon-feather.php') ? 'active' : ''; ?>">Feather Icons</a></li>
                                    <li><a href="icon-ionic.php" class="<?php echo ($page == 'icon-ionic.php') ? 'active' : ''; ?>">Ionic Icons</a></li>
                                    <li><a href="icon-material.php" class="<?php echo ($page == 'icon-material.php') ? 'active' : ''; ?>">Material Icons</a></li>
                                    <li><a href="icon-pe7.php" class="<?php echo ($page == 'icon-pe7.php') ? 'active' : ''; ?>">Pe7 Icons</a></li>
                                    <li><a href="icon-simpleline.php" class="<?php echo ($page == 'icon-simpleline.php') ? 'active' : ''; ?>">Simpleline Icons</a></li>
                                    <li><a href="icon-themify.php" class="<?php echo ($page == 'icon-themify.php') ? 'active' : ''; ?>">Themify Icons</a></li>
                                    <li><a href="icon-weather.php" class="<?php echo ($page == 'icon-weather.php') ? 'active' : ''; ?>">Weather Icons</a></li>
                                    <li><a href="icon-typicon.php" class="<?php echo ($page == 'icon-typicon.php') ? 'active' : ''; ?>">Typicon Icons</a></li>
                                    <li><a href="icon-flag.php" class="<?php echo ($page == 'icon-flag.php') ? 'active' : ''; ?>">Flag Icons</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?php echo ($page == 'maps-vector.php' || $page == 'maps-leaflet.php') ? 'active subrop' : ''; ?>"><i class="ti ti-map-star"></i><span>Maps</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="maps-vector.php" class="<?php echo ($page == 'maps-vector.php') ? 'active' : ''; ?>">Vector</a>
                                    </li>
                                    <li>
                                        <a href="maps-leaflet.php" class="<?php echo ($page == 'maps-leaflet.php') ? 'active' : ''; ?>">Leaflet</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Help</span></li>
                    <li>
                        <ul>
                            <li><a href="javascript:void(0);"><i class="ti ti-file-stack"></i><span>Documentation</span></a></li>
                            <li><a href="javascript:void(0);"><i class="ti ti-arrow-capsule"></i><span>Changelog v2.3.0</span></a></li>
                            <li class="submenu">
                                <a href="javascript:void(0);"><i class="ti ti-menu-deep"></i><span>Multi Level</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="javascript:void(0);">Level 1.1</a></li>
                                    <li class="submenu submenu-two"><a href="javascript:void(0);">Level 1.2<span class="menu-arrow inside-submenu"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 2.1</a></li>
                                            <li class="submenu submenu-two submenu-three"><a href="javascript:void(0);">Level 2.2<span class="menu-arrow inside-submenu inside-submenu-two"></span></a>
                                                <ul>
                                                    <li><a href="javascript:void(0);">Level 3.1</a></li>
                                                    <li><a href="javascript:void(0);">Level 3.2</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>-->
                </ul>
            </div>
        </div>

    </div>
    <!-- Sidenav Menu End -->