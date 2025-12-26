<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->
<style>
    .video-bg {
        overflow: hidden;
    }

    .video-cover {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        transform: translate(-50%, -50%);
        object-fit: cover;
        z-index: 1;
    }

    .video-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 2;
    }
</style>
<div class="container-fluid vh-100 p-0">
    <div class="row vh-100 g-0">

        <!-- COLUMNA VIDEO -->
        <div class="col-lg-8 d-none d-lg-block position-relative video-bg">
            <video autoplay muted loop playsinline class="video-cover">
                <source src="https://v.ftcdn.net/16/82/99/61/700_F_1682996118_oYHuzPQLf6TIV2sjuhf3UZ1r00p9tfgZ_ST.mp4" type="video/mp4">
            </video>

            <!-- Overlay opcional oscuro -->
            <div class="video-overlay"></div>
        </div>

        <!-- COLUMNA LOGIN -->
        <div class="col-lg-4 col-md-12 d-flex align-items-center justify-content-center bg-white">
            <form action="index.php" class="w-100 p-4" style="max-width:420px">

                <div class="text-center mb-4">
                    <img src="https://multitech.envision.com.co/img/logo.png" class="img-fluid" style="max-height:80px">
                </div>

                <div class="mb-3">
                    <h3 class="mb-2">Bienvenidos</h3>
                    <p class="mb-0 text-muted">Favor ingrese el usuario y contraseña</p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección de correo electrónico</label>
                    <div class="input-group input-group-flat">
                        <input type="email" class="form-control" id="emailUser">
                        <span class="input-group-text">
                            <i class="ti ti-mail"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group input-group-flat pass-group">
                        <input type="password" class="form-control pass-input" id="passwordUser">
                        <span class="input-group-text toggle-password">
                            <i class="ti ti-eye-off"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Recuérdame</label>
                    </div>
                    <a href="forgot-password.php" class="link-danger">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" id="btnLogin" class="btn btn-primary w-100">
                    Iniciar sesión
                </button>

            </form>
        </div>

    </div>
</div>


<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>