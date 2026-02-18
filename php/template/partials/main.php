<?php require_once __DIR__ . '/session.php'; ?>

<?php
$esModal = isset($_GET['modal']) && $_GET['modal'] == 1;
?>

<!DOCTYPE html>
<?php require_once __DIR__ . '/theme-settings.php'; ?>

<head>

    <?php require_once __DIR__ . '/title-meta.php'; ?>
    <?php require_once __DIR__ . '/head-css.php'; ?>

</head>

<body>

<?php if (!$esModal): ?>
    <?php require_once __DIR__ . '/body.php'; ?>
<?php endif; ?>

<?php if (!$esModal): ?>
    <div class="main-wrapper">
        <?php require_once __DIR__ . '/menu.php'; ?>
<?php endif; ?>

        <!-- CONTENIDO PRINCIPAL -->
        <?= $content ?>

<?php if (!$esModal): ?>
        <?php include_once __DIR__ . '/modal-popup.php'; ?>
    </div>
<?php else: ?>
    <?php include_once __DIR__ . '/modal-popup.php'; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/vendor-scripts.php'; ?>

</body>
</html>
