<?php
$link = $_SERVER['PHP_SELF'];
$link_array = explode('/', $link);
$page = end($link_array);
?>

<?php
if (in_array($page, ['layout-mini.php'])) {
    echo '<body class="mini-sidebar">';
} elseif ($page === 'login.php') {
    echo '<body class="account-page bg-white">';
} elseif ($page === 'coming-soon.php') {
    echo '<body class="comming-soon">';
} elseif (in_array($page, ['email-verification.php', 'forgot-password.php', 'register.php', 'reset-password.php', 'success.php', 'two-step-verification.php'])) {
    echo '<body class="account-page">';
} elseif (in_array($page, ['error-404.php', 'error-500.php', 'under-maintenance.php'])) {
    echo '<body class="error-page">';
} else {
    echo '<body>';
}
?>
