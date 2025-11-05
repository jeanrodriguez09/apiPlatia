<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resumen de Cuenta</title>
        <style>
            body {
                font-family: "Courier New", Courier, monospace;
                padding: 5px !important;
                box-sizing: border-box !important;
            }

            @page {
                size: legal portrait !important; /* Tamaño de papel Oficio en horizontal */
            }

            .container {
                width: 100%;
                max-width: 1100px;
                margin: auto;
                border: 1px solid black;
            }

            .header, .footer {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid black;
                padding: 10px;
            }

            .header .logo {
                width: 40%;
            }
            
            .header .logo img {
                width: 65%;
            }

            .header .company-info, .header .account-summary {
                width: 30%;
                text-align: center;
            }

            .company-info p {
                font-size: 14px;
            }

            .account-summary {
                font-size: 12px;
            }

            .content {
                padding: 10px;
            }

            .content table {
                width: 100%;
                border-collapse: collapse;
            }

            .content table, .content th, .content td {
                border: 1px solid black;
            }

            .content th, .content td {
                padding: 5px;
                text-align: left;
            }

            .content th {
                background-color: #f0f0f0;
            }

            .right-align {
                text-align: right;
            }

            .bold {
                font-weight: bold;
            }

            .customer-info {
                margin-bottom: 20px;
            }
            
            .row {
                display: flex;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
            }

            .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto, .col-xs, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-auto, .col-xxl, .col-xxl-1, .col-xxl-10, .col-xxl-11, .col-xxl-12, .col-xxl-2, .col-xxl-3, .col-xxl-4, .col-xxl-5, .col-xxl-6, .col-xxl-7, .col-xxl-8, .col-xxl-9, .col-xxl-auto, .col-xxxl, .col-xxxl-1, .col-xxxl-10, .col-xxxl-11, .col-xxxl-12, .col-xxxl-2, .col-xxxl-3, .col-xxxl-4, .col-xxxl-5, .col-xxxl-6, .col-xxxl-7, .col-xxxl-8, .col-xxxl-9, .col-xxxl-auto {
                position: relative;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
            }

            @media (min-width: 768px) {
                .col-md-2 {
                    flex: 0 0 16.66667%;
                    max-width: 16.66667%;
                }

                .col-md-3 {
                    flex: 0 0 25%;
                    max-width: 25%
                }

                .col-md-4 {
                    flex: 0 0 33.33333%;
                    max-width: 33.33333%
                }

                .col-md-5 {
                    flex: 0 0 41.66667%;
                    max-width: 41.66667%;
                }

                .col-md-7 {
                    flex: 0 0 58.33333%;
                    max-width: 58.33333%;
                }

                .col-md-8 {
                    flex: 0 0 66.66667%;
                    max-width: 66.66667%
                }

                .col-md-9 {
                    flex: 0 0 75%;
                    max-width: 75%
                }

                .col-md-10 {
                    flex: 0 0 83.33333%;
                    max-width: 83.33333%;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header Section -->
            <div class="header">
                <div class="logo">
                    <img src="<?= base_url('/public/img/logo/logo-indufar.png') ?>" alt="Logo Indufar">
                </div>
                <div class="company-info">
                    <h1>INDUFAR C.I.S.A.</h1>
                    <h2>ADMINISTRACIÓN Y PLANTA INDUSTRIAL</h2>
                    <p>
                        Arazá e/ Zavvedro y Bella Vista - Zona Norte<br>
                        Teléfonos: (021) 682 510/13<br>
                        Email: indufar@indufar.com.py
                    </p>
                </div>
                <div class="account-summary">
                    <h2>
                        RESUMEN DE CUENTA
                        <br>
                        AL 09.09.2024
                    </h2>
                </div>
            </div>

            <!-- Content Section -->
            <div class="content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="customer-info">
                            <table>
                                <tr>
                                    <td><span class="bold">SEÑOR(ES):</span> FARMA S.A.</td>
                                    <td><span class="bold">RUC:</span> 80022877-4</td>
                                </tr>
                                <tr>
                                    <td><span class="bold">DOMICILIO:</span> ACCESO SUR C/JUAN P. CARRILLO - ÑEMBY</td>
                                    <td><span class="bold">TELÉFONO:</span> 590722</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <table>
                            <tr>
                                <th>Cta. Cte. N.</th>
                                <th>Hoja</th>
                                <th>Vend</th>
                                <th>Cobr</th>
                                <th>Zona</th>
                            </tr>
                            <tr>
                                <td>6176</td>
                                <td>1</td>
                                <td>250</td>
                                <td>250</td>
                                <td>12</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <td>CPTO</td>
                            <td>CONDC</td>
                            <td>DOC. LEGAL</td>
                            <td>EMISION</td>
                            <td>VENCE</td>
                            <td>DEBITO</td>
                            <td>CREDITO</td>
                            <td>SALDO</td>
                            <td>SALDO ACU.</td>
                            <td>MORA</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>NC</td>
                            <td>C090</td>
                            <td>000100405456445646</td>
                            <td>02.01.2024</td>
                            <td>02.01.2024</td>
                            <td>0</td>
                            <td>12.600</td>
                            <td>12.600-</td>
                            <td>12.600-</td>
                            <td>251</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
