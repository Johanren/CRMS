<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MULTITECH - Técnicos Laborales RST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/carrera.js"></script>
    <script src="js/horario.js"></script>
    <script src="js/manage-users-list.js"></script>
    <script src="js/leads-list.js"></script>

    <style>
        body {
            background: #f2f6fc;
        }

        .form-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
            max-width: 750px;
            margin: 40px auto;
        }

        .logo {
            max-width: 140px;
            margin-bottom: 15px;
        }

        h1 {
            font-weight: 800;
            color: #004085;
        }

        .subtitle {
            color: #555;
            font-size: 0.95rem;
        }

        .form-title {
            color: #0062E6;
            font-weight: 600;
            margin-top: 25px;
        }

        .form-control,
        .form-select,
        textarea {
            border-radius: 12px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #0062E6, #33AEFF);
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 50px;
        }

        .footer {
            text-align: center;
            font-size: 0.85rem;
            color: #024BA5;
            margin-top: 25px;
        }

        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body>

    <div class="form-container text-center">

        <img src="img/logo.png" class="logo" alt="Multitech">

        <h1>MULTITECH – TÉCNICOS LABORALES – RST</h1>

        <p class="subtitle mt-2">
            Este formulario se utiliza para registrar los datos de personas interesadas en los
            <strong>Técnicos Laborales</strong> después de la gestión del <strong>Contact Center</strong>.
            <br>
            El registro permite que el equipo de <strong>Ventas Multitech</strong> atienda al lead de forma inmediata.
        </p>
        <form id="mainForm" class="text-start mt-4">
            <div class="mb-4 text-start">
                <label class="required">Usuario / Closer asignado</label>
                <select name="user" id="user" class="form-select" required>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="required">Celular del Estudiante</label>
                <input type="text" name="celular_estudiante" id="celular_estudiante" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="required">Nombre del Estudiante</label>
                <input type="text" name="nombre_estudiante" id="nombre_estudiante" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Nombre del Acudiente (Opcional)</label>
                <input type="text" name="nombre_acudiente" id="nombre_acudiente" class="form-control">
            </div>

            <div class="mb-3">
                <label>Teléfono del Acudiente (Opcional)</label>
                <input type="tel" name="telefono_acudiente" id="telefono_acudiente" class="form-control">
            </div>

            <div class="mb-3">
                <label class="required">Programa Técnico Laboral de Interés</label>
                <select name="carrera" id="carrera" class="form-select" required>
                </select>
            </div>

            <div class="mb-3">
                <label class="required">Horario – Días</label>
                <select name="horario" id="horario" class="form-select" required>
                </select>
            </div>

            <div class="mb-4">
                <label>Observaciones / Datos Importantes</label>
                <textarea name="observaciones" class="form-control" rows="4"></textarea>
            </div>
            <input type="hidden" id="id_lead" name="id_lead">
            <input type="hidden" id="cod_emp" name="cod_emp" value="1">
            <input type="hidden" id="cliente_id" name="cliente_id">
            <button type="submit" class="btn btn-primary w-100">
                Registrar Lead
            </button>

        </form>

        <div class="footer">
            © 2025 Multitech | Atención inmediata Closer Ventas
        </div>

    </div>

</body>

</html>