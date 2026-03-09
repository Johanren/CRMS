<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Reporte fuente y origen<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte fuente y origen</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                        class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                    id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- card start -->
        <div class="card border-0 rounded-0">
            <div class="card-body">
                <div class="row mb-4">

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><strong>Leads por Fuente</strong></div>
                            <div class="card-body">
                                <div class="grafico-scroll">
                                    <div class="grafico-container">
                                        <canvas id="chartFuente"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><strong>Leads por Origen</strong></div>
                            <div class="card-body">
                                <div class="grafico-scroll">
                                    <div class="grafico-container">
                                        <canvas id="chartOrigen"></canvas>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row mb-4">

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><strong>Distribución por Fuente</strong></div>
                            <div class="card-body">
                                <div class="grafico-scroll">
                                    <div class="grafico-container">
                                        <canvas id="chartPieFuente"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><strong>Fuente vs Origen</strong></div>
                            <div class="card-body">
                                <div class="grafico-scroll">
                                    <div class="grafico-container">
                                        <canvas id="chartStackFuenteOrigen"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <style>
            .grafico-container {
                position: relative;
                height: 380px;
                width: 100%;
            }

            .grafico-scroll {
                overflow-x: auto;
            }

            canvas {
                min-width: 600px;
            }
        </style>

    </div>
    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>

<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>
<script>
    function cargarReporteFuenteOrigen() {

        fetch("ajax/ajax.php?accion=reporte_fuente_origen")
            .then(res => res.json())
            .then(data => {
                generarGraficosFuenteOrigen(data);
            });

    }

    document.addEventListener("DOMContentLoaded", cargarReporteFuenteOrigen);


    let chartFuente, chartOrigen, chartPie, chartStack;

    /* PALETA DE COLORES PROFESIONAL */

    const colores = [
        '#2563eb',
        '#16a34a',
        '#ea580c',
        '#9333ea',
        '#dc2626',
        '#0891b2',
        '#ca8a04',
        '#4f46e5',
        '#be185d',
        '#059669',
        '#0284c7',
        '#7c3aed'
    ];


    function generarGraficosFuenteOrigen(data) {

        const fuentes = {};
        const origenes = {};

        data.forEach(row => {

            let fuente = row.fuente || "SIN FUENTE";
            let origen = row.origen || "SIN ORIGEN";
            let total = parseInt(row.total_leads);

            if (!fuentes[fuente]) fuentes[fuente] = 0;
            if (!origenes[origen]) origenes[origen] = 0;

            fuentes[fuente] += total;
            origenes[origen] += total;

        });

        const fuentesLabels = Object.keys(fuentes);
        const fuentesData = Object.values(fuentes);

        const origenLabels = Object.keys(origenes);
        const origenData = Object.values(origenes);


        /* ======================
           GRAFICO FUENTE
        ====================== */

        if (chartFuente) chartFuente.destroy();

        chartFuente = new Chart(document.getElementById("chartFuente"), {

            type: 'bar',

            data: {
                labels: fuentesLabels,
                datasets: [{
                    label: 'Leads',
                    data: fuentesData,
                    backgroundColor: colores.slice(0, fuentesLabels.length),
                    borderRadius: 6
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },

                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }

        });


        /* ======================
           GRAFICO ORIGEN
        ====================== */

        if (chartOrigen) chartOrigen.destroy();

        chartOrigen = new Chart(document.getElementById("chartOrigen"), {

            type: 'bar',

            data: {
                labels: origenLabels,
                datasets: [{
                    label: 'Leads',
                    data: origenData,
                    backgroundColor: colores.slice(0, origenLabels.length),
                    borderRadius: 6
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }

        });



        /* ======================
           PIE FUENTES
        ====================== */

        if (chartPie) chartPie.destroy();

        chartPie = new Chart(document.getElementById("chartPieFuente"), {

            type: 'pie',

            data: {
                labels: fuentesLabels,
                datasets: [{
                    data: fuentesData,
                    backgroundColor: colores.slice(0, fuentesLabels.length)
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }

            }

        });



        /* ======================
           STACK FUENTE VS ORIGEN
        ====================== */

        const datasets = origenLabels.map((origen, index) => {

            return {

                label: origen,

                data: fuentesLabels.map(f => {

                    const row = data.find(d => d.fuente == f && d.origen == origen);
                    return row ? parseInt(row.total_leads) : 0;

                }),

                backgroundColor: colores[index % colores.length]

            }

        });

        if (chartStack) chartStack.destroy();

        chartStack = new Chart(document.getElementById("chartStackFuenteOrigen"), {

            type: 'bar',

            data: {
                labels: fuentesLabels,
                datasets: datasets
            },

            options: {

                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },

                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }

            }

        });

    }
</script>