<?php
$link = $_SERVER[ 'PHP_SELF' ];
$link_array = explode( '/', $link );
$page = end( $link_array );
?>  

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.png">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">

<?php if ($page !== 'layout-mini.php' && $page !== 'layout-hoverview.php' && $page !== 'layout-hidden.php' && $page !== 'layout-fullwidth.php' && $page !== 'layout-rtl.php' && $page !== 'layout-dark.php' && $page !== 'login.php' && $page !== 'register.php' && $page !== 'forgot-password.php' && $page !== 'reset-password.php' && $page !== 'success.php' && $page !== 'email-verification.php' && $page !== 'two-step-verification.php' && $page !== 'lock-screen.php' && $page !== 'error-404.php' && $page !== 'error-500.php' && $page !== 'coming-soon.php' && $page !== 'under-maintenance.php') {   ?>      
    <!-- Theme Config Js -->
    <script src="assets/js/theme-script.js"></script>
<?php }?>     

<?php if ($page !== 'layout-rtl.php') {   ?>   
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
<?php }?> 

<?php if ($page == 'layout-rtl.php') {   ?>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">  
<?php }?> 

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.min.css">

<?php if ($page == 'icon-bootstrap.php') {   ?>
    <!-- Bootstrap Icon CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/bootstrap/bootstrap-icons.min.css">
<?php }?> 

<?php if ($page == 'icon-feather.php') {   ?>
    <!-- Feather CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/feather/feather.css">
<?php }?> 

<?php if ($page == 'icon-flag.php') {   ?>
    <!-- Flag CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css">
<?php }?>     

<?php if ($page == 'add-invoices.php' || $page == 'calendar.php' || $page == 'edit-invoices.php' || $page == 'file-manager.php' || $page == 'icon-fontawesome.php' || $page == 'invoice.php' || $page == 'kanban-view.php' || $page == 'notes.php' || $page == 'todo.php') {   ?> 
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
<?php }?>  

<?php if ($page == 'icon-ionic.php') {   ?>
    <!-- Ionic CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/ionic/ionicons.css">
<?php }?> 

<?php if ($page == 'icon-material.php') {   ?>
    <!-- Material CSS -->
    <link rel="stylesheet" href="assets/plugins/material/materialdesignicons.css">
<?php }?> 

<?php if ($page == 'icon-pe7.php') {   ?>
    <!-- Pe7 CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/pe7/pe-icon-7.css">
<?php }?> 

<?php if ($page == 'icon-remix.php') {   ?>
    <!-- Remix Icon CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/remix/remixicon.css">
<?php }?> 

<?php if ($page == 'icon-simpleline.php') {   ?>
    <!-- Simpleline CSS -->
    <link rel="stylesheet" href="assets/plugins/simpleline/simple-line-icons.css">
<?php }?> 

<?php if ($page == 'icon-themify.php') {   ?>
    <!-- Themify CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/themify/themify.css">
<?php }?> 

<?php if ($page == 'icon-typicon.php') {   ?>
    <!-- Typicon CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/typicons/typicons.css">
<?php }?> 

<?php if ($page == 'icon-weather.php') {   ?>
    <!-- Weather CSS -->
    <link rel="stylesheet" href="assets/plugins/icons/weather/weathericons.css">
<?php }?> 

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="assets/plugins/simplebar/simplebar.min.css">

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'analytics.php' || $page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php' || $page == 'calls.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'cities.php' || $page == 'companies-list.php' || $page == 'company-reports.php' || $page == 'company.php' || $page == 'contact-messages.php' || $page == 'contact-reports.php' || $page == 'contact-stage.php' || $page == 'contacts-list.php' || $page == 'contracts-list.php' || $page == 'countries.php' || $page == 'data-tables.php' || $page == 'deal-reports.php' || $page == 'deals-list.php' || $page == 'delete-request.php' || $page == 'domain.php' || $page == 'estimations-list.php' || $page == 'faq.php' || $page == 'index.php' || $page == 'industry.php' || $page == 'language-settings.php' || $page == 'langugae-web-edit.php' || $page == 'language-web.php' || $page == 'layout-dark.php' || $page == 'layout-fullwidth.php' || $page == 'layout-hidden.php' || $page == 'layout-hoverview.php' || $page == 'layout-mini.php' || $page == 'layout-rtl.php' || $page == 'lead-reports.php' || $page == 'leads-dashboard.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'lost-reason.php' || $page == 'manage-users.php' || $page == 'membership-transactions.php' || $page == 'packages.php' || $page == 'pages.php' || $page == 'payments.php' || $page == 'permission.php' || $page == 'pipeline.php' || $page == 'printers-settings.php' || $page == 'project-dashboard.php' || $page == 'project-reports.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'purchase-transaction.php' || $page == 'roles-permissions.php' || $page == 'sources.php' || $page == 'states.php' || $page == 'subscription.php' || $page == 'task-reports.php' || $page == 'testimonials.php' || $page == 'tickets.php') {   ?> 
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="assets/plugins/datatables/css/dataTables.bootstrap5.min.css">
<?php }?> 

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'analytics.php' || $page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php' || $page == 'calendar.php' || $page == 'call-history.php' || $page == 'calls.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company-reports.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contact-messages.php' || $page == 'contact-reports.php' || $page == 'contact-stage.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'dashboard.php' || $page == 'deal-reports.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'delete-request.php' || $page == 'domain.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'index.php' || $page == 'industry.php' || $page == 'invoice-list.php' || $page == 'invoices.php' || $page == 'layout-dark.php' || $page == 'layout-fullwidth.php' || $page == 'layout-hidden.php' || $page == 'layout-hoverview.php' || $page == 'layout-mini.php' || $page == 'layout-rtl.php' || $page == 'lead-reports.php' || $page == 'leads-dashboard.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'lost-reason.php' || $page == 'manage-users.php' || $page == 'membership-transactions.php' || $page == 'packages.php' || $page == 'pages.php' || $page == 'payments.php' || $page == 'permission.php' || $page == 'pipeline.php' || $page == 'project-dashboard.php' || $page == 'project-details.php' || $page == 'project-reports.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'purchase-transaction.php' || $page == 'sources.php' || $page == 'subscription.php' || $page == 'task-reports.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'tickets.php') {   ?> 
	<!-- Daterangepicker CSS -->
	<link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
<?php }?>  

<?php if ($page == 'activities.php' || $page == 'add-blog.php' || $page == 'blog-details.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'company-details.php' || $page == 'companies.php' || $page == 'contact-details.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'cronjob.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'edit-blog.php' || $page == 'email-reply.php' || $page == 'email.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'form-select.php' || $page == 'form-wizard.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'localization-settings.php' || $page == 'notes.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'tax-rates.php') {   ?> 
    <!-- Choices CSS -->
    <link rel="stylesheet" href="assets/plugins/choices.js/public/assets/styles/choices.min.css"> 
<?php }?>   
    
<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'add-invoices.php' || $page == 'calendar.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'dashboard.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'domain.php' || $page == 'edit-invoices.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'form-pickers.php' || $page == 'invoice-list.php' || $page == 'invoices.php' || $page == 'kanban-view.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'notes.php' || $page == 'packages.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'project-dashboard.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'purchase-transaction.php' || $page == 'subscription.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'todo.php') {   ?> 
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="assets/plugins/flatpickr/flatpickr.min.css">   
<?php }?>    

<?php if ($page == 'email-reply.php' || $page == 'social-feed.php') {   ?> 
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">
<?php }?> 

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'add-blog.php' || $page == 'blog-categories.php' || $page == 'blog-details.php' || $page == 'blogs.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'edit-blog.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'file-manager.php' || $page == 'form-editors.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-list.php' || $page == 'invoice-settings.php' || $page == 'invoices.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'notes.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'ticket-details.php' || $page == 'todo-list.php' || $page == 'todo.php') {   ?> 
    <!-- Quill CSS -->
    <link rel="stylesheet" href="assets/plugins/quill/quill.snow.css">    
<?php }?>   

<?php if ($page == 'file-manager.php' || $page == 'form-editors.php') {   ?> 
    <!-- Quill css -->
    <link rel="stylesheet" href="assets/plugins/quill/quill.core.css">
    <link rel="stylesheet" href="assets/plugins/quill/quill.bubble.css">
<?php }?>   

<?php if ($page == 'blog-categories.php' || $page == 'blog-comments.php' || $page == 'blog-tags.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'contact-details.php' || $page == 'contact-messages.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'delete-request.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'manage-users.php' || $page == 'membership-transactions.php' || $page == 'pages.php' || $page == 'permission.php' || $page == 'pipeline.php' || $page == 'project-details.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'security-settings.php') {   ?> 
    <!-- Mobile CSS-->
    <link rel="stylesheet" href="assets/plugins/intltelinput/css/intlTelInput.css">
    <link rel="stylesheet" href="assets/plugins/intltelinput/css/demo.css">
<?php }?>      

<?php if ($page == 'chart-c3.php') {   ?>
    <!-- ChartC3 CSS -->
    <link rel="stylesheet" href="assets/plugins/c3-chart/c3.min.css">
<?php }?>     

<?php if ($page == 'chart-morris.php') {   ?>
    <!-- Morris CSS -->
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
<?php }?>     

<?php if ($page == 'maps-leaflet.php') {   ?>
    <!-- Leaflet Maps CSS -->
    <link rel="stylesheet" href="assets/plugins/leaflet/leaflet.css">
<?php }?>     

<?php if ($page == 'maps-leaflet.php') {   ?>
    <!-- Jsvector Maps -->
    <link rel="stylesheet" href="assets/plugins/jsvectormap/css/jsvectormap.min.css">
<?php }?>     

<?php if ($page == 'ui-lightbox.php') {   ?>
    <!-- Glightbox CSS -->
    <link rel="stylesheet" href="assets/plugins/lightbox/glightbox.min.css">
<?php }?>     

<?php if ($page == 'ui-rangeslider.php') {   ?>
    <!-- Rangeslider CSS -->
    <link rel="stylesheet" href="assets/plugins/nouislider/nouislider.min.css">
<?php }?>     

<?php if ($page == 'ui-sweetalerts.php') {   ?>
    <!-- Sweetalert2 CSS -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2/sweetalert2.min.css">
<?php }?>     

<?php if ($page == 'activities.php' || $page == 'activity-calls.php' || $page == 'activity-mail.php' || $page == 'activity-meeting.php' || $page == 'activity-task.php' || $page == 'add-blog.php' || $page == 'add-invoices.php' || $page == 'add-page.php' || $page == 'appearance-settings.php' || $page == 'ban-ip-address.php' || $page == 'bank-accounts.php' || $page == 'blog-details.php' || $page == 'calendar.php' || $page == 'campaign-archieve.php' || $page == 'campaign-complete.php' || $page == 'campaign.php' || $page == 'clear-cache.php' || $page == 'companies-list.php' || $page == 'companies.php' || $page == 'company-details.php' || $page == 'company-reports.php' || $page == 'company-settings.php' || $page == 'company.php' || $page == 'contact-details.php' || $page == 'contact-reports.php' || $page == 'contacts-list.php' || $page == 'contacts.php' || $page == 'contracts-list.php' || $page == 'contracts.php' || $page == 'cronjob.php' || $page == 'currencies.php' || $page == 'custom-fields-setting.php' || $page == 'dashboard.php' || $page == 'database-backup.php' || $page == 'deal-reports.php' || $page == 'deals-details.php' || $page == 'deals-list.php' || $page == 'deals.php' || $page == 'delete-request.php' || $page == 'domain.php' || $page == 'edit-blog.php' || $page == 'edit-invoices.php' || $page == 'edit-page.php' || $page == 'email-settings.php' || $page == 'estimations-list.php' || $page == 'estimations.php' || $page == 'faq.php' || $page == 'form-select.php' || $page == 'form-wizard.php' || $page == 'gdpr-cookies.php' || $page == 'invoice-list.php' || $page == 'invoice-settings.php' || $page == 'invoices.php' || $page == 'kanban-view.php' || $page == 'language-settings.php' || $page == 'language-web-edit.php' || $page == 'language-web.php' || $page == 'lead-reports.php' || $page == 'leads-details.php' || $page == 'leads-list.php' || $page == 'leads.php' || $page == 'localization-settings.php' || $page == 'manage-users.php' || $page == 'membership-addons.php' || $page == 'membership-plans.php' || $page == 'membership-transactions.php' || $page == 'notes.php' || $page == 'packages.php' || $page == 'pages.php' || $page == 'payments.php' || $page == 'pipeline.php' || $page == 'preference-settings.php' || $page == 'printers-settings.php' || $page == 'profile-settings.php' || $page == 'project-dashboard.php' || $page == 'project-details.php' || $page == 'project-reports.php' || $page == 'projects-list.php' || $page == 'projects.php' || $page == 'proposals-list.php' || $page == 'proposals.php' || $page == 'purchase-transaction.php' || $page == 'security-settings.php' || $page == 'sitemap.php' || $page == 'sms-gateways.php' || $page == 'storage.php' || $page == 'subscription.php' || $page == 'system-backup.php' || $page == 'system-update.php' || $page == 'task-reports.php' || $page == 'tasks-completed.php' || $page == 'tasks-important.php' || $page == 'tasks.php' || $page == 'tax-rates.php' || $page == 'testimonials.php' || $page == 'tickets.php' || $page == 'todo-list.php' || $page == 'todo.php') {   ?> 
    <!-- Select2 CSS -->
	<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<?php }?>  

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css" id="app-style">