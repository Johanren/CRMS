<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Multitech - Inscripción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }

        h1 {
            font-weight: 700;
            color: #004085;
        }

        .form-title {
            color: #0062E6;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .form-floating label {
            color: #6c757d;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #0062E6, #33AEFF);
            border: none;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0046a1, #1f89da);
            transform: scale(1.03);
        }

        .footer {
            text-align: center;
            font-size: 0.9rem;
            color: #024BA5;
            margin-top: 20px;
        }
    </style>


</head>

<body>


    <div class="form-container text-center">
        <!-- Logo -->
        <img src="img/logo.png" alt="Logo" class="logo">

        <!-- Título -->
        <h1>Escríbenos para más información</h1>
        <p class="text-muted mb-4">¿Interesado en estudiar en Multitech? ¡Inscríbete!</p>

        <!-- Formulario -->
        <form id="mainForm">

            <h3 class="h5 form-title">Complete sus datos:</h3>

            <div class="mb-3 form-floating">
                <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres" required>
                <label for="nombres">Nombres</label>
            </div>
            <div class="mb-3 form-floating">
                <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos" required>
                <label for="apellidos">Apellidos</label>
            </div>

            <div class="mb-3 form-floating">
                <input type="number" name="cedula" id="cedula" class="form-control" placeholder="Número de Cédula">
                <label for="cedula">Número de Cédula</label>
            </div>

            <div class="mb-3 form-floating">
                <input type="email" name="email" id="email" class="form-control" placeholder="Correo Electrónico" required>
                <label for="email">Correo Electrónico</label>
            </div>

            <div class="form-floating mb-4">
                <input type="number" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required>
                <label for="telefono">Teléfono</label>
            </div>
            <!-- anti spam-->
            <div style="display:none;">
                <label>Dejar este campo vacío</label>
                <input type="text" name="website" autocomplete="off" tabindex="-1">
            </div>

            <!-- Url de Origen-->
            <input type="hidden" name="origen_url" id="origen_url">
            <!-- Codigo Empresa-->
            <input type="hidden" name="cod_emp" id="cod_emp" value="1">


            <!-- Campo select -->
            <?php
            require_once "config/conexion.php";


            // Consulta de programas
            $query = "SELECT cod_pro, desc_pro FROM programa WHERE emp_pro = 1 ORDER BY desc_pro ASC";
            $result = $conexion->query($query);

            ?>

            <!-- Campo select dinámico -->
            <div class="form-floating mb-4">
                <select class="form-select" id="curso" name="carrera" required>
                    <option value="" selected disabled>Seleccione una opción</option>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <option value="<?php echo $row['cod_pro']; ?>">
                            <?php echo htmlspecialchars($row['desc_pro']); ?>
                        </option>
                    <?php endwhile; ?>

                </select>
                <label for="curso">Programa</label>
            </div>

            <!-- Botón -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">🚀 Enviar </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="footer mt-4">
            <p>© 2025 Programa Multitech | Todos los derechos reservados</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Recibir la URL real desde el padre
        window.addEventListener("message", function(event) {
            // Validar el mensaje recibido
            if (event.data && event.data.tipo === "url_padre") {
                const campo = document.getElementById("origen_url");
                if (campo) {
                    campo.value = event.data.url; // Asignar la URL recibida
                    //console.log("URL recibida del padre:", event.data.url);
                }
            }
        });
    </script>

    <script>
        function enviarWhatsApp() {

            let nombres = document.getElementById('nombres').value.trim();
            let apellidos = document.getElementById('apellidos').value.trim();
            let cedula = document.getElementById('cedula').value.trim();
            let email = document.getElementById('email').value.trim();
            let telefono = document.getElementById('telefono').value.trim();
            let cursoSelect = document.getElementById('curso');
            let cursoId = cursoSelect.value;
            let cursoNombre = cursoSelect.options[cursoSelect.selectedIndex].text;

            // Validación rápida
            if (!nombres || !apellidos || !email || !telefono || !cursoId) {
                Swal.fire("Atención", "Por favor completa todos los campos antes de enviar.", "warning");
                return;
            }

            let mensaje =
                `¡Hola! Estoy interesado en el programa Multitech.
            
            🧑 Nombre: ${nombres} ${apellidos}
            🪪 Cédula: ${cedula}
            📧 Correo: ${email}
            📱 Teléfono: ${telefono}
            🎓 Curso: ${cursoNombre}
            
            Gracias por su atención.`;

            let numero = "573178939531"; // CAMBIA ESTE NÚMERO

            let url = "https://wa.me/" + numero + "?text=" + encodeURIComponent(mensaje);

            window.open(url, "_blank");
        }
    </script>


    <script>
        document.getElementById('mainForm').addEventListener('submit', function(event) {
            if (!event.target.checkValidity()) {
                event.preventDefault();
                alert('Por favor complete todos los campos antes de enviar.');
            }
        });

        let sourceField = '';
        let mediumField = '';
        let campaignField = '';
        let ip_usuario = '';

        window.addEventListener("message", function(event) {
            // Validar el mensaje recibido
            if (event.data && event.data.tipo === "url_padre") {
                const queryString = event.data.url.split("?")[1] || "";

                const params = new URLSearchParams(queryString);

                sourceField = params.get('utm_source') || 'directo';
                mediumField = params.get('utm_medium') || 'ninguno';
                campaignField = params.get('utm_campaign') || 'general';
            }
        });

        async function obtenerIP() {
            try {
                let res = await fetch("https://api.ipify.org?format=json");
                let data = await res.json();
                return data.ip;
            } catch (e) {
                console.log(e);
                return null;
            }
        }

        $(document).ready(async function() {

            ip_usuario = await obtenerIP();

            // 1️⃣ Primero consultar si la IP existe en la base de datos
            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: {
                    accion: "buscar_ip_tracking",
                    ip_usuario: ip_usuario
                },
                dataType: "json",
                success: function(data) {

                    // 2️⃣ Si la IP existe, NO hacer nada
                    if (data.status === "existe") {
                        console.log("La IP ya existe, no se registra:", ip_usuario);
                        return;
                    }

                    // 3️⃣ Si no existe → registrar
                    if (data.status === "no_existe") {

                        $.ajax({
                            url: "ajax.php",
                            type: "POST",
                            data: {
                                accion: "marketing_tracking",
                                ip_usuario: ip_usuario,
                                sourceField: sourceField || 'directo',
                                mediumField: mediumField || 'ninguno',
                                campaignField: campaignField || 'general'
                            },
                            success: function(r) {
                                console.log("Nueva IP registrada:", ip_usuario);
                            }
                        });

                    }
                }
            });

        });

        if (document.getElementById("mainForm")) {

            document.getElementById("mainForm").addEventListener("submit", function(e) {
                e.preventDefault();
                let cursoSelect = document.getElementById('curso');
                let cursoId = cursoSelect.value;
                let cursoNombre = cursoSelect.options[cursoSelect.selectedIndex].text;
                let datos = new FormData(this);
                datos.append("accion", "registrar_leads");
                datos.append("cursoNombre", cursoNombre);
                datos.append("sourceField", sourceField);
                datos.append("mediumField", mediumField);
                datos.append("campaignField", campaignField);
                datos.append("ip_usuario", ip_usuario);

                fetch("ajax.php", {
                        method: "POST",
                        body: datos
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (data.status === "success") {

                            Swal.fire("Éxito", data.message, "success");
                            // Enviar a WhatsApp
                            enviarWhatsApp();
                            this.reset();

                            // --- SEGUNDO FETCH (PHPMailer) ---
                            fetch("phpmailer/wedding_phpmailer_template.php", {
                                    method: "POST",
                                    body: datos
                                })
                                .then(res => res.json())
                                .then(resp => {
                                    console.log("Correo:", resp);
                                    // 3. Redirigir a la página de agradecimiento
                                     window.top.location.href = 'https://multitech.edu.co/gracias.php';
                                })
                                .catch(err => {
                                    console.error("Error en correo", err);
                                    // Si el correo falla, redirigimos de todas formas
                                    window.top.location.href = 'https://multitech.edu.co/gracias.php';
                                });
                            // ----------------------------------

                        } else {
                            Swal.fire("Error", data.message, "error");
                        }
                    });

            });

        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>