<?php
$link = $_SERVER['PHP_SELF'];
$link_array = explode('/', $link);
$page = end($link_array);
?>
<?php
if($page != 'login.php'){
?>
<script src="assets/json/filtro.js"></script>
<script>
    fetch('ajax/ajax.php?accion=redireccionamiento')
        .then(res => res.json())
        .then(data => {
            if (data.redirect && data.redirect !== false) {
                window.location.href = data.redirect;
            }
        })
        .catch(err => console.log("Error:", err));
</script>
<?php
}
?>
<!-- jQuery -->
<script src="assets/js/jquery-3.7.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Simplebar JS -->
<script src="assets/plugins/simplebar/simplebar.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($page == 'login.php') {   ?>
    <!-- Datatable JS -->
    <script src="assets/json/login.js"></script>
<?php } ?>

<?php if ($page == 'activities.php' || $page == 'venta.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'analytics.php' || $page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php' || $page == 'calls.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'cities.php' || $page == 'companies-list.php' || $page == 'company-reports.php' || $page == 'company.php' || $page == 'contact-messages.php' || $page == 'contact-reports.php' || $page == 'contact-stage.php' || $page == 'contacts-list.php' || $page == 'contracts-list.php' || $page == 'countries.php' || $page == 'data-tables.php' || $page == 'deal-reports.php' || $page == 'deals-list.php' || $page == 'delete-request.php' || $page == 'domain.php' || $page == 'estimations-list.php' || $page == 'faq.php' || $page == 'index.php' || $page == 'industry.php' || $page == 'language-settings.php' || $page == 'langugae-web-edit.php' || $page == 'language-web.php' || $page == 'layout-dark.php' || $page == 'layout-fullwidth.php' || $page == 'layout-hidden.php' || $page == 'layout-hoverview.php' || $page == 'layout-mini.php' || $page == 'layout-rtl.php' || $page == 'lead-reports.php' || $page == 'leads-dashboard.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'lost-reason.php' || $page == 'manage-users.php' || $page == 'membership-transactions.php' || $page == 'packages.php' || $page == 'pages.php' || $page == 'payments.php' || $page == 'permission.php' || $page == 'pipeline.php' || $page == 'printers-settings.php' || $page == 'project-dashboard.php' || $page == 'project-reports.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'purchase-transaction.php' || $page == 'roles-permissions.php' || $page == 'sources.php' || $page == 'states.php' || $page == 'subscription.php' || $page == 'task-reports.php' || $page == 'testimonials.php' || $page == 'tickets.php' || $page == 'info_origen.php') {   ?>
    <!-- Datatable JS -->
    <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/js/dataTables.bootstrap5.min.js"></script>
<?php } ?>

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'analytics.php' || $page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php' || $page == 'calendar.php' || $page == 'call-history.php' || $page == 'calls.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company-reports.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contact-messages.php' || $page == 'contact-reports.php' || $page == 'contact-stage.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'dashboard.php' || $page == 'deal-reports.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'delete-request.php' || $page == 'domain.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'index.php' || $page == 'industry.php' || $page == 'invoice-list.php' || $page == 'invoices.php' || $page == 'layout-dark.php' || $page == 'layout-fullwidth.php' || $page == 'layout-hidden.php' || $page == 'layout-hoverview.php' || $page == 'layout-mini.php' || $page == 'layout-rtl.php' || $page == 'lead-reports.php' || $page == 'leads-dashboard.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'lost-reason.php' || $page == 'manage-users.php' || $page == 'membership-transactions.php' || $page == 'packages.php' || $page == 'pages.php' || $page == 'payments.php' || $page == 'permission.php' || $page == 'pipeline.php' || $page == 'project-dashboard.php' || $page == 'project-details.php' || $page == 'project-reports.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'purchase-transaction.php' || $page == 'sources.php' || $page == 'subscription.php' || $page == 'task-reports.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'tickets.php') {   ?>
    <!-- Daterangepicker JS -->
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<?php } ?>

<?php if ($page == 'activities.php' || $page == 'add-blog.php' || $page == 'blog-details.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'company-details.php' || $page == 'companies.php' || $page == 'contact-details.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'cronjob.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'edit-blog.php' || $page == 'email-reply.php' || $page == 'email.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'form-select.php' || $page == 'form-wizard.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'localization-settings.php' || $page == 'notes.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'tax-rates.php') {   ?>
    <!-- Choices Js -->
    <script src="assets/plugins/choices.js/public/assets/scripts/choices.min.js"></script>
<?php } ?>

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'add-invoices.php' || $page == 'calendar.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'dashboard.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'domain.php' || $page == 'edit-invoices.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'form-pickers.php' || $page == 'invoice-list.php' || $page == 'invoices.php' || $page == 'kanban-view.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'notes.php' || $page == 'packages.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'project-dashboard.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'purchase-transaction.php' || $page == 'subscription.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'todo.php') {   ?>
    <script src="assets/plugins/flatpickr/flatpickr.min.js"></script>
<?php } ?>

<?php if ($page == 'email-reply.php' || $page == 'social-feed.php') {   ?>
    <!-- Fancybox JS -->
    <script src="assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<?php } ?>

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'add-blog.php' || $page == 'blog-categories.php' || $page == 'blog-details.php' || $page == 'blogs.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'edit-blog.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'file-manager.php' || $page == 'form-editors.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-list.php' || $page == 'invoice-settings.php' || $page == 'invoices.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'notes.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'ticket-details.php' || $page == 'todo-list.php' || $page == 'todo.php') {   ?>
    <!-- Quill JS -->
    <script src="assets/plugins/quill/quill.min.js"></script>
<?php } ?>

<?php if ($page == 'form-editors.php') {   ?>
    <!-- Quill JS -->
    <script src="assets/js/form-quill.js"></script>
<?php } ?>

<?php if ($page == 'file-manager.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-settings.php' || $page == 'notes.php' || $page == 'todo-list.php' || $page == 'todo.php') {   ?>
    <script src="assets/js/form-quilljs.js"></script>
<?php } ?>

<?php if ($page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'contact-details.php' || $page == 'contact-messages.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'delete-request.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'manage-users.php' || $page == 'membership-transactions.php' || $page == 'pages.php' || $page == 'permission.php' || $page == 'pipeline.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'security-settings.php') {   ?>
    <!-- Mobile Input -->
    <script src="assets/plugins/intltelinput/js/intlTelInput.js"></script>
<?php } ?>

<?php if ($page == 'appearance-settings.php' || $page == 'ban-ip-address.php' || $page == 'bank-accounts.php' || $page == 'clear-cache.php' || $page == 'company-details.php' || $page == 'company-settings.php' || $page == 'connected-apps.php' || $page == 'contact-details.php' || $page == 'cronjob.php' || $page == 'currencies.php' || $page == 'custom-fields-setting.php' || $page == 'database-backup.php' || $page == 'deals-details.php' || $page == 'email-settings.php' || $page == 'file-manager.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-settings.php' || $page == 'language-settings.php' || $page == 'language-web-edit.php' || $page == 'language-web.php' || $page == 'leads-details.php' || $page == 'localization-settings.php' || $page == 'notes.php' || $page == 'notifications-settings.php' || $page == 'payment-gateways.php' || $page == 'preference-settings.php' || $page == 'prefixes-settings.php' || $page == 'printers-settings.php' || $page == 'profile-settings.php' || $page == 'project-details.php' || $page == 'security-settings.php' || $page == 'sitemap.php' || $page == 'sms-gateways.php' || $page == 'social-feed.php' || $page == 'storage.php' || $page == 'system-backup.php' || $page == 'system-update.php' || $page == 'tax-rates.php') {   ?>
    <!-- Sticky Sidebar JS -->
    <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
    <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
<?php } ?>

<?php if ($page == 'calendar.php') {   ?>
    <!-- Fullcalendar JS -->
    <script src="assets/plugins/fullcalendar/index.global.min.js"></script>
    <script src="assets/plugins/fullcalendar/calendar-data.js"></script>
<?php } ?>

<?php if ($page == 'analytics.php' || $page == 'calls.php' || $page == 'chart-apex.php' || $page == 'company-reports.php' || $page == 'contact-reports.php' || $page == 'contact-stage.php' || $page == 'dashboard.php' || $page == 'deal-reports.php' || $page == 'index.php' || $page == 'layout-dark.php' || $page == 'layout-fullwidth.php' || $page == 'layout-hidden.php' || $page == 'layout-hoverview.php' || $page == 'layout-mini.php' || $page == 'layout-rtl.php' || $page == 'lead-reports.php' || $page == 'leads-dashboard.php' || $page == 'lost-reason.php' || $page == 'project-dashboard.php' || $page == 'project-reports.php' || $page == 'sources.php' || $page == 'task-reports.php') {   ?>
    <!-- Apexchart JS -->
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
<?php } ?>

<?php if ($page == 'chart-c3.php') {   ?>
    <!-- Chart JS -->
    <script src="assets/plugins/c3-chart/d3.v5.min.js"></script>
    <script src="assets/plugins/c3-chart/c3.min.js"></script>
    <script src="assets/plugins/c3-chart/chart-data.js"></script>
<?php } ?>

<?php if ($page == 'chart-flot.php') {   ?>
    <!-- Chart JS -->
    <script src="assets/plugins/flot/jquery.flot.js"></script>
    <script src="assets/plugins/flot/jquery.flot.fillbetween.js"></script>
    <script src="assets/plugins/flot/jquery.flot.pie.js"></script>
    <script src="assets/plugins/flot/chart-data.js"></script>
<?php } ?>

<?php if ($page == 'chart-js.php') {   ?>
    <!-- Chart JS -->
    <script src="assets/plugins/chartjs/chart.min.js"></script>
    <script src="assets/plugins/chartjs/chart-data.js"></script>
<?php } ?>

<?php if ($page == 'chart-morris.php') {   ?>
    <!-- Chart JS -->
    <script src="assets/plugins/morris/raphael-min.js"></script>
    <script src="assets/plugins/morris/morris.min.js"></script>
    <script src="assets/plugins/morris/chart-data.js"></script>
<?php } ?>

<?php if ($page == 'chart-peity.php' || $page == 'dashboard.php') {   ?>
    <!-- Chart JS -->
    <script src="assets/plugins/peity/jquery.peity.min.js"></script>
    <script src="assets/plugins/peity/chart-data.js"></script>
<?php } ?>

<?php if ($page == 'deals.php' || $page == 'estimations.php' || $page == 'leads.php') {   ?>
    <!-- Drag Card -->
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<?php } ?>

<?php if ($page == 'form-upload.php') {   ?>
    <!-- Dropzone File Js -->
    <script src="assets/plugins/dropzone/dropzone-min.js"></script>
<?php } ?>

<?php if ($page == 'form-mask.php') {   ?>
    <!-- Inputmask JS -->
    <script src="assets/plugins/inputmask/inputmask.min.js"></script>
<?php } ?>

<?php if ($page == 'form-wizard.php') {   ?>
    <!-- Wizrd JS -->
    <script src="assets/plugins/vanilla-wizard/js/wizard.min.js"></script>

    <!-- Wizard JS -->
    <script src="assets/js/form-wizard.js"></script>
<?php } ?>


<?php if ($page == 'maps-leaflet.php') {   ?>
    <!-- Leaflet Maps JS -->
    <script src="assets/plugins/leaflet/leaflet.js"></script>
    <script src="assets/js/leaflet.js"></script>
<?php } ?>

<?php if ($page == 'maps-vector.php') {   ?>
    <!-- JSVector Maps JS -->
    <script src="assets/plugins/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="assets/plugins/jsvectormap/maps/world-merc.js"></script>
    <script src="assets/js/us-merc-en.js"></script>
    <script src="assets/js/russia.js"></script>
    <script src="assets/js/spain.js"></script>
    <script src="assets/js/canada.js"></script>
    <script src="assets/js/jsvectormap.js"></script>
<?php } ?>

<?php if ($page == 'kanban-view.php' || $page == 'ui-dragula.php') {   ?>
    <!-- Dragula Js-->
    <script src="assets/plugins/dragula/dragula.min.js"></script>
    <script src="assets/js/dragula.js"></script>
<?php } ?>

<?php if ($page == 'ui-clipboard.php') {   ?>
    <!-- Clipboard JS -->
    <script src="assets/plugins/clipboard/clipboard.min.js"></script>
    <script src="assets/js/clipboard.js"></script>
<?php } ?>

<?php if ($page == 'ui-lightbox.php') {   ?>
    <!-- Glightbox JS -->
    <script src="assets/plugins/lightbox/glightbox.min.js"></script>
    <script src="assets/plugins/lightbox/lightbox.js"></script>
<?php } ?>

<?php if ($page == 'ui-rangeslider.php') {   ?>
    <!-- noUiSlider js -->
    <script src="assets/plugins/nouislider/nouislider.min.js"></script>
    <script src="assets/plugins/wnumb/wNumb.min.js"></script>

    <!-- Rangeslider JS -->
    <script src="assets/js/range-slider.js"></script>
<?php } ?>

<?php if ($page == 'ui-rating.php') {   ?>
    <!-- Rater JS -->
    <script src="assets/plugins/rater-js/index.js"></script>
    <script src="assets/js/ratings.js"></script>
<?php } ?>

<?php if ($page == 'ui-sweetalerts.php') {   ?>
    <!-- Sweet Alerts js -->
    <script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="assets/js/sweetalerts.js"></script>
<?php } ?>

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'add-blog.php' || $page == 'add-invoices.php' || $page == 'add-page.php' || $page == 'appearance-settings.php' || $page == 'ban-ip-address.php' || $page == 'bank-accounts.php' || $page == 'blog-details.php' || $page == 'calendar.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'clear-cache.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company-reports.php' || $page == 'company-settings.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contact-reports.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'cronjob.php' || $page == 'currencies.php' || $page == 'custom-fields-setting.php' || $page == 'dashboard.php' || $page == 'database-backup.php' || $page == 'deal-reports.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'delete-request.php' || $page == 'domain.php' || $page == 'edit-blog.php' || $page == 'edit-invoices.php' || $page == 'edit-page.php' || $page == 'email-settings.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'faq.php' || $page == 'form-select.php' || $page == 'form-wizard.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-list.php' || $page == 'invoice-settings.php' || $page == 'invoices.php' || $page == 'kanban-view.php' || $page == 'language-settings.php' || $page == 'language-web-edit.php' || $page == 'language-web.php' || $page == 'lead-reports.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'localization-settings.php' || $page == 'manage-users.php' || $page == 'membership-addons.php' || $page == 'membership-plans.php' || $page == 'membership-transactions.php' || $page == 'notes.php' || $page == 'packages.php' || $page == 'pages.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'preference-settings.php' || $page == 'printers-settings.php' || $page == 'profile-settings.php' || $page == 'project-dashboard.php' || $page == 'project-details.php' || $page == 'project-reports.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'purchase-transaction.php' || $page == 'security-settings.php' || $page == 'sitemap.php' || $page == 'sms-gateways.php' || $page == 'storage.php' || $page == 'subscription.php' || $page == 'system-backup.php' || $page == 'system-update.php' || $page == 'task-reports.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'tax-rates.php' || $page == 'testimonials.php' || $page == 'tickets.php' || $page == 'todo-list.php' || $page == 'todo.php') {   ?>
    <!-- Select2 JS -->
    <script src="assets/plugins/select2/js/select2.min.js"></script>
<?php } ?>

<?php if ($page == 'activities.php') {   ?>
    <script src="assets/json/activity-list.js"></script>
<?php } ?>

<?php if ($page == 'leads-dashboard.php') {   ?>
    <script src="assets/json/lead-project.js"></script>
<?php } ?>

<?php if ($page == 'activity-calls.php') {   ?>
    <script src="assets/json/activity-calls.js"></script>
<?php } ?>

<?php if ($page == 'activity-mail.php') {   ?>
    <script src="assets/json/activity-mail.js"></script>
<?php } ?>

<?php if ($page == 'activity-meeting.php') {   ?>
    <script src="assets/json/activity-meeting.js"></script>
<?php } ?>

<?php if ($page == 'analytics.php') {   ?>
    <script src="assets/json/analytic-contact.js"></script>
    <script src="assets/json/analytic-deal.js"></script>
    <script src="assets/json/analytic-company.js"></script>
<?php } ?>

<?php if ($page == 'activity-task.php') {   ?>
    <script src="assets/json/activity-task.js"></script>
<?php } ?>

<?php if ($page == 'blog-categories.php') {   ?>
    <script src="assets/json/categories-list.js"></script>
<?php } ?>

<?php if ($page == 'blog-comments.php') {   ?>
    <script src="assets/json/blog-comment-list.js"></script>
<?php } ?>

<?php if ($page == 'blog-tags.php') {   ?>
    <script src="assets/json/tags-list.js"></script>
<?php } ?>

<?php if ($page == 'calls.php') {   ?>
    <script src="assets/json/calls-list.js"></script>
<?php } ?>

<?php if ($page == 'campaign-archieve.php') {   ?>
    <script src="assets/json/campaign-archieve.js"></script>
<?php } ?>

<?php if ($page == 'campaign-complete.php') {   ?>
    <script src="assets/json/campaign-complete.js"></script>
<?php } ?>

<?php if ($page == 'campaign.php') {   ?>
    <script src="assets/json/campaign-list.js"></script>
    <script src="assets/json/auditoria.js"></script>
<?php } ?>

<?php if ($page == 'cities.php') {   ?>
    <script src="assets/json/cities-list.js"></script>
<?php } ?>

<?php if ($page == 'companies-list.php') {   ?>
    <script src="assets/json/companies-list.js"></script>
<?php } ?>

<?php if ($page == 'contact-messages.php') {   ?>
    <script src="assets/json/contact-messages-list.js"></script>
<?php } ?>

<?php if ($page == 'contact-reports.php') {   ?>
    <script src="assets/json/contact-reports.js"></script>
<?php } ?>

<?php if ($page == 'contacts-list.php') {   ?>
    <script src="assets/json/contacts-list.js"></script>
<?php } ?>

<?php if ($page == 'company-reports.php') {   ?>
    <script src="assets/json/company-reports.js"></script>
<?php } ?>

<?php if ($page == 'contact-stage.php') {   ?>
    <script src="assets/json/contact-stage.js"></script>
<?php } ?>

<?php if ($page == 'contracts-list.php') {   ?>
    <script src="assets/json/contracts-list.js"></script>
<?php } ?>

<?php if ($page == 'countries.php') {   ?>
    <script src="assets/json/countries-list.js"></script>
<?php } ?>

<?php if ($page == 'deal-reports.php') {   ?>
    <script src="assets/json/deal-reports.js"></script>
<?php } ?>

<?php if ($page == 'deals-list.php') {   ?>
    <script src="assets/json/deal-list.js"></script>
<?php } ?>

<?php if ($page == 'delete-request.php') {   ?>
    <script src="assets/json/delete-request.js"></script>
<?php } ?>

<?php if ($page == 'faq.php') {   ?>
    <script src="assets/json/faq-list.js"></script>
<?php } ?>

<?php if ($page == 'industry.php') {   ?>
    <script src="assets/json/industry-list.js"></script>
<?php } ?>

<?php if ($page == 'language-web.php') {   ?>
    <script src="assets/json/language-web.js"></script>
<?php } ?>

<?php if ($page == 'leads-list.php' || $page == 'leads.php' || $page == 'leads-details.php' || $page == 'contacts.php') {   ?>
    <script src="assets/json/leads-list.js"></script>
<?php } ?>

<?php if ($page == 'lead-reports.php') {   ?>
    <script src="assets/json/leads-reports.js"></script>
<?php } ?>

<?php if ($page == 'lost-reason.php') {   ?>
    <script src="assets/json/reason-list.js"></script>
<?php } ?>

<?php if ($page == 'manage-users.php') {   ?>
    <script src="assets/json/manage-users-list.js"></script>
    <script src="assets/json/roles-list.js"></script>
<?php } ?>

<?php if ($page == 'membership-transactions.php') {   ?>
    <script src="assets/json/transactions-list.js"></script>
<?php } ?>

<?php if ($page == 'pages.php') {   ?>
    <script src="assets/json/pages-list.js"></script>
<?php } ?>

<?php if ($page == 'payments.php') {   ?>
    <script src="assets/json/payments-list.js"></script>
<?php } ?>

<?php if ($page == 'permission.php') {   ?>
    <script src="assets/json/permission-list.js"></script>
<?php } ?>

<?php if ($page == 'pipeline.php') {   ?>
    <script src="assets/json/pipeline-list.js"></script>
<?php } ?>

<?php if ($page == 'project-dashboard.php') {   ?>
    <script src="assets/json/recent-project.js"></script>
<?php } ?>

<?php if ($page == 'project-reports.php') {   ?>
    <script src="assets/json/project-reports.js"></script>
<?php } ?>

<?php if ($page == 'projects-list.php') {   ?>
    <script src="assets/json/project-list.js"></script>
<?php } ?>

<?php if ($page == 'proposals-list.php') {   ?>
    <script src="assets/json/proposals-list.js"></script>
<?php } ?>

<?php if ($page == 'roles-permissions.php') {   ?>
    <script src="assets/json/roles-list.js"></script>
<?php } ?>

<?php if ($page == 'sources.php') {   ?>
    <script src="assets/json/source-list.js"></script>
<?php } ?>

<?php if ($page == 'states.php') {   ?>
    <script src="assets/json/states-list.js"></script>
<?php } ?>

<?php if ($page == 'tickets.php') {   ?>
    <script src="assets/json/tickets-list.js"></script>
<?php } ?>

<?php if ($page == 'testimonials.php') {   ?>
    <script src="assets/json/testimonials-list.js"></script>
<?php } ?>

<?php if ($page == 'index.php' || $page == 'layout-rtl.php' || $page == 'layout-mini.php' || $page == 'layout-hoverview.php' || $page == 'layout-fullwidth.php' || $page == 'layout-hidden.php' || $page == 'layout-dark.php') {   ?>
    <script src="assets/json/deals-project.js"></script>
<?php } ?>

<?php if ($page == 'info_origen.php' || $page == 'index.php' || $page == 'leads.php' || $page == 'venta.php' || $page == 'leads-list.php' || $page == 'leads-details.php' || $page == 'contacts.php' || $page == 'lead-reports.php'){   ?>
    <script src="assets/json/venta.js"></script>
    <script src="assets/json/info_origen.js"></script>
    <script src="assets/json/manage-users-list.js"></script>
    <script src="assets/json/roles-list.js"></script>
<?php } ?>

<?php if ($page == 'chat.php' || $page == 'video-call.php') {   ?>
    <script src="assets/js/chat.js"></script>
<?php } ?>

<?php if ($page == 'custom-fields-setting.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-settings.php' || $page == 'profile-settings.php' || $page == 'sms-gateways.php') {   ?>
    <!-- Profile Upload JS -->
    <script src="assets/js/profile-upload.js"></script>
<?php } ?>

<?php if ($page == 'email-reply.php' || $page == 'email.php' || $page == 'social-feed.php') {   ?>
    <script src="assets/js/email.js"></script>
<?php } ?>

<?php if ($page == 'todo-list.php' || $page == 'todo.php') {   ?>
    <script src="assets/js/todo.js"></script>
<?php } ?>

<!-- Main JS -->
<script src="assets/js/script.js"></script>
<!--SCRIPRS GENERALES-->