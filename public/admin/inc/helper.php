<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/admin/config.php';

    

    function comprobarSincronizacionSAP($link) {
        //Fecha y hora de la última sincronización.
        $sUltSincronizacion = "SELECT fechaSincronizacion FROM sincronizacionessap WHERE estado=1 ORDER BY fechaSincronizacion DESC LIMIT 1";
        $qUltSincronizacion = $link->query($sUltSincronizacion);
        $rowSincronizacion = $qUltSincronizacion->fetch_assoc();
        $ultimaSincronizacion = $rowSincronizacion['fechaSincronizacion'] ?? null;
        return $ultimaSincronizacion;
    }

    function isUsernameExists($link, $username) {
        $sql = "SELECT COUNT(*) AS count FROM usuarios WHERE usuario = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
    
    function get_numeroCliente($link, $idb) {
        //Fecha y hora de la última sincronización.
        $sCliente = "SELECT numero FROM clientes WHERE id = $idb";
        $qCliente = $link->query($sCliente);
        $rowCliente = $qCliente->fetch_assoc();
        $numeroCliente = $rowCliente['numero'] ?? 0;
        return $numeroCliente;
    }

    function estadoExpediente($link, $idb) {
        //Fecha y hora de la última sincronización.
        $sEstadoExpediente = "SELECT estado FROM expedientes WHERE id = $idb";
        $qEstadoExpediente = $link->query($sEstadoExpediente);
        $rowEstadoExpediente = $qEstadoExpediente->fetch_assoc();
        $estadoExpediente = $rowEstadoExpediente['estado'] ?? 0;
        return $estadoExpediente;
    }

    function generarPlantilla($newPassword, $tipo){
        $motivo="";
        if($tipo==1){
            $motivo="la Creaci&oacute;n de usuario";
        }elseif($tipo==2){
            $motivo="el Restablecimiento de contrase&ntilde;a";
        }else{
            exit;
            return false;
        }

        return '<!DOCTYPE html>
                <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width,initial-scale=1">
                    <meta name="x-apple-disable-message-reformatting">
                    <title></title>
                    <style>
                        table, td, div, h1, p {font-family: Arial, sans-serif;}
                        .btnConfirmacion {
                            margin: 0.25rem 0.125rem;
                            color: #fff !important;
                            background-color: #198754;
                            border-color: #198754;
                            display: inline-block;
                            font-weight: 400;
                            line-height: 1.5;
                            text-align: center;
                            text-decoration: none;
                            vertical-align: middle;
                            cursor: pointer;
                            user-select: none;
                            border: 1px solid transparent;
                            padding: 0.375rem 0.75rem;
                            font-size: 1rem;
                            border-radius: 0.25rem;
                            transition: color .15s ease-in-out,
                                        background-color .15s ease-in-out,
                                        border-color .15s ease-in-out,
                                        box-shadow .15s ease-in-out;
                            text-transform: none;
                            -webkit-appearance: button;
                            box-sizing: border-box;
                            font-family: inherit;
                        }
                    </style>
                </head>
                <body style="margin:0;padding:0;">
                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                        <tr>
                            <td align="center" style="padding:0;">
                                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                    <tr>
                                        <td align="center" style="padding:30px 0 30px 0;background:#ffff;">
                                            <img src="http://www.indufar.com.py/photos/shares/Sistema/Empresa/logo3.png" alt="" width="300" style="height:auto;display:block;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:30px 30px 30px 30px;">
                                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                <tr>
                                                    <td style="padding:0 0 30px 0;color:#153643;">
                                                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;text-align:center;">Ajustes de sistema.</h1>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="padding:0;">
                                                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Se ha realizado <b>'.$motivo.'</b>. Contrase&ntilde;a temporal: <strong>'.$newPassword.'</strong>. <i>El sistema solicitar&aacute; el cambio en el primer acceso.</i></p>
                                                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:center;">
                                                            <a href="http://satisfaccion.indufar.com.py/sysimm" class="btnConfirmacion">Ingresar al sistema</a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="padding:30px;background:#76391a;">
                                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                                <tr>
                                                    <td style="padding:0;width:50%;" align="left">
                                                        <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                                            &reg; '.date('Y').' INDUFAR C.I.S.A. Todos los derechos reservados.
                                                        </p>
                                                    </td>
                                                    <td style="padding:0;width:50%;" align="right">
                                                        <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                                                            <tr>
                                                                <td style="padding:0 0 0 10px;width:38px;">
                                                                    <a href="https://www.facebook.com/LaboratoriosIndufar" style="color:#ffffff;"><img src="http://www.indufar.com.py/photos/shares/facebook.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
                                                                </td>
                                                                <td style="padding:0 0 0 10px;width:38px;">
                                                                    <a href="https://www.instagram.com/laboratoriosindufar_py/" style="color:#ffffff;"><img src="http://www.indufar.com.py/photos/shares/instagram.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';
    }

?>