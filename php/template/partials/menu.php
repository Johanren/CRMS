<?php if ($page !== 'login.php' && $page !== 'register.php' && $page !== 'forgot-password.php' && $page !== 'reset-password.php' && $page !== 'email-verification.php' && $page !== 'two-step-verification.php' && $page !== 'success.php' && $page !== 'error-404.php' && $page !== 'error-500.php' && $page !== 'coming-soon.php' && $page !== 'under-maintenance.php' && $page !== 'lock-screen.php') {   ?>
    <?php include_once __DIR__ . '/topbar.php';?>

    <?php include_once __DIR__ . '/sidebar.php';?>
<?php }?> 
