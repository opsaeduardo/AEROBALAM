<?php

namespace App\Controllers;

use App\Models\participantes;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'public/PHPMailer/Exception.php';
require 'public/PHPMailer/PHPMailer.php';
require 'public/PHPMailer/SMTP.php';

define('METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'op5813Sa2135So56N');
define('SECRET_IV', '7884588');

class Home extends BaseController
{

    // DECLARACION DE MODULOS
    protected $moduloCupon = 'Cupones';
    protected $moduloPaseDoble = 'PaseDoble';
    protected $moduloRegistro = 'Registro';

    // PRECIO DE INSCRIPCION
    protected $precio = 100;

    // COMSION FIJA DE STRIPE DE 3 PESOS
    protected $comisionFija = 3;

    // VISTA
    public function index(): string
    {
        $modelo = new participantes();
        $consulta = $modelo->consultarDestinos();
        return view('index', ['destinos' => $consulta]);
        // return view('index');
    }

    /* METODO QUE TRAE LAS FECHAS */
    public function consultarFecha()
    {
        $Origen_Id = $this->request->getVar('Origen_Id');
        $modelo = new participantes();
        $consulta = $modelo->consultarFecha($Origen_Id);
        return json_encode($consulta);
    }

    /* METODO QUE REISTRA EN RESERVAS */
    public function registrarReserva()
    {
        $reservas = $this->request->getPost('pasajeros');
        $total = $this->request->getPost('Total');

        $modelo = new Participantes();
        $respuesta = [];
        $idReservas = [];

        if (!empty($reservas)) {
            foreach ($reservas as $p) {
                // 1. Registrar pasajero
                $idPasajero = $modelo->registrarPasajeros(
                    $p['Nombre'],
                    $p['Correo'],
                    $p['Telefono'],
                    $p['Edad'],
                    $p['Genero']
                );

                // 2. Registrar reserva
                $reservaData = [
                    'Vuelo_Id'      => $p['Vuelo_Id'],
                    'Pasajero_Id'   => $idPasajero,
                    'Asiento'       => 0,
                    'Estado'        => 'Reservado',
                    'Fecha_Reserva' => date('Y-m-d H:i:s'),
                ];

                // 3. Registrar la reserva y obtener el ID de la nueva reserva
                $idReserva = $modelo->registrarReserva($reservaData);
                $idReservas[] = $idReserva;  // Guardar el ID de la reserva.

                // Para respuesta AJAX si deseas usarlo.
                $respuesta[] = [
                    'idPasajero' => $idPasajero,
                    'vueloId'    => $p['Vuelo_Id'],
                    'reservaId'  => $idReserva
                ];
            }

            if (!empty($idReservas)) {
                // COMISIÓN POR PORCENTAJE
                $comisionPorcentaje = round($total * 0.036, 2);

                // IVA SOBRE COMISIONES
                $IVA = round(($this->comisionFija + $comisionPorcentaje) * 0.16, 2);

                // MONTO NETO
                $montoNeto = round($total - $this->comisionFija - $comisionPorcentaje - $IVA, 2);

                // 4. Registrar pago una sola vez.
                $pagoData = [
                    'Total' => $total,
                    'ComisionPorcentaje' => $comisionPorcentaje,
                    'ComisionFija' => $this->comisionFija,
                    'IVA' => $IVA,
                    'MontoTotal' => $montoNeto,
                    'StatusPago' => 'Pendiente',
                    'TipoPago' => 'Tarjeta',
                    'Fecha' => date('Y-m-d H:i:s'),
                ];

                // 5. Vincular reservas al pago
                $idPago = $modelo->agregarReservaPago($idReservas, $pagoData);

                $respuesta['idPago'] = $idPago;
            }
        }

        return $this->response->setJSON($respuesta);
    }

    /* METODO QUE REVISA LA INFOMRACION DE PAGO */
    public function revisarPago()
    {
        $modelo = new participantes();

        $idPago = $this->request->getVar('idPago');

        $consulta = $modelo->revisarPago($idPago);

        return json_encode($consulta);
    }

    /* METODO QUE CANCELA LA RESERVACION DE VUELOS Y EL ID PAGO */
    public function cancelarRegistro()
    {
        $postData = json_decode($this->request->getPost('datos'), true);
        $modelo = new participantes();

        if (!empty($postData)) {
            // 1. Cancelar las reservas
            foreach ($postData as $key => $item) {
                
                if (is_numeric($key) && is_array($item) && isset($item['reservaId'])) {
                    $dataUpdate = ['Estado' => 'Cancelado'];
                    $modelo->cancelarReserva($item['reservaId'], $dataUpdate);
                }
            }

            // 2. Cancelar el pago
            if (isset($postData['idPago'])) {
                $dataUpdate = ['StatusPago' => 'Cancelado'];
                $modelo->cancelarPago($postData['idPago'], $dataUpdate);
            }
        }

        return $this->response->setJSON([
            'status' => 'OK',
            'mensaje' => 'Registro cancelado correctamente.'
        ]);
    }

    /* METODO QUE ACTUALIZA Y TRAE LA INFO DE LOS PASAJEROS */
    public function actualizarURL()
    {
        $idPago = $this->request->getVar('id');

        if (!$idPago) {
            return $this->response->setJSON(['error' => 'ID de pago no recibido']);
        }

        $modelo = new participantes();
        $resultado = $modelo->actualizarURL($idPago);

        return $this->response->setJSON($resultado);
    }

    /* METODO QUE GUARDA EL QR EN IMAGEN */
    public function QR()
    {
             $img = $_POST['imgBase64'];
        $idSocio = $_POST['id'];

        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $directory = './public/uploads/QRAEROBALAM/' . $idSocio;
        if (is_dir($directory)) {
            return json_encode('ExisteImagen');
        } else {
            mkdir($directory, 0777, true);
            $file = $directory . '/' . $idSocio . '.png';
            $success = file_put_contents($file, $data);
            return $success ? '1' : '0';
        }
    }

    /* METODO QUE OBTINE LA INFORMACION DEL REGISTRO POR RESERVA */
    public function informacionRegistro()
    {
        $id = $this->request->getVar('id');

        $modelo = new participantes();

        $consulta = $modelo->informacionRegistro($id);

        return json_encode($consulta);
    }

    /* METODO QUE OBTINE LA INFORMACION DEL REGISTRO POR RESERVA PAGO ID  */
    public function informacionPago()
    {
        $id = $this->request->getVar('idPago');

        $modelo = new participantes();

        $consulta = $modelo->informacionPago($id);

        return json_encode($consulta);
    }

    /* METODO QUE ENVIA EL CORREO */
    public function Correo()
    {
        $correo = $this->request->getVar('correo');
        $id = $this->request->getVar('id');

        $modelo  = new participantes();

        $registro = $modelo->informacionRegistro($id);

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no_responder@aerobalam.info';
            $mail->Password   = 'N0_ResP0nd3R!$';
            $mail->SMTPSecure = "tls";
            $mail->SMTPAutoTLS = false;
            $mail->Port = 587;
            $mail->setFrom('no_responder@aerobalam.info', '=?UTF-8?B?' . base64_encode('Boleto') . '?=');
            $mail->AddAddress($correo);
            $mail->isHTML(true);
            // **************************************************************LOCALHOST**************************************************************
            // IMAGEN DE CARRERA HORTENSIAS
            $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . '/AEROBALAM//public/img/logoAEROBALAM.png', 'aero', 'aero.png');
            // IMAGEN DEL QR
            $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . '/AEROBALAM/public/uploads/QRAEROBALAM/' . $id . '/' . $id . '.png', $id, $id . '.png');

            // **************************************************************PRODUCCION**************************************************************
            // IMAGEN DE CARRERA HORTENSIAS
            // $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . '/public/img/purple_minimum.png', 'purple_minimum', 'purple_minimum.png');
            // IMAGEN DEL QR
            // $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . '/public/uploads/VamosAncianos/' . $id . '/' . $id . '.png', $id, $id . '.png');
            $mail->Subject =  mb_convert_encoding('AEROBALAM', 'ISO-8859-1', 'UTF-8');
            $mail->Body =
            '
           <div align="center" style="font-family: Roboto-regular, Helvetica; color:#A3B4BB">
                <div>
                    <img src="cid:aero" width="300px">
                </div>
                <div style="font-family: Arial, sans-serif; border:1px solid #e1e1e1; border-radius:8px; padding:16px; margin-bottom:20px;">
                    <h2 style="color:#1F4F23; margin-top:0; font-size:22px;">🎟️ Confirmación de Vuelo</h2>
                    <!-- <p style="font-size:18px;"><strong>Reserva:</strong> ' . $registro['ReservaPagoId'] . '</p> -->
                    <hr>
                    <ul style="list-style:none; padding:0; font-size:20px;">
                        <li><strong>Nombre:</strong> ' . $registro['Nombre'] . '</li>
                        <li><strong>Correo:</strong> ' . $registro['Correo'] . '</li>
                        <li><strong>Teléfono:</strong> ' . $registro['Telefono'] . '</li>
                        <li><strong>Edad:</strong> ' . $registro['Edad'] . '</li>
                        <li><strong>Género:</strong> ' . $registro['Genero'] . '</li>
                    </ul>
                    <h3 style="color:#333; font-size:20px;">✈️ Detalles del Vuelo</h3>
                    <ul style="list-style:none; padding:0; font-size:18px;">
                        <li><strong>Origen:</strong> ' . $registro['Origen_Codigo'] . ' - ' . $registro['Origen_Nombre'] . '</li>
                        <li><strong>Destino:</strong> ' . $registro['Destino_Codigo'] . ' - ' . $registro['Destino_Nombre'] . '</li>
                        <li><strong>Fecha:</strong> ' . $registro['Vuelo_Fecha'] . '</li>
                        <li><strong>Hora:</strong> ' . $registro['Vuelo_Hora'] . '</li>
                    </ul>
                    <div style="background:#f0f0f0; border-radius:8px; padding:10px; margin-top:10px; text-align:center;">
                        <img src="cid:' . $id . '" width="300px">
                    </div>
                </div>
            </div>

            ';
            $mail->CharSet = 'UTF-8';
            $mail->send();
            echo 'Correo exitoso: ' .  $correo . '<br>';
        } catch (Exception $e) {
            echo 'Correo fallido: ' .  $correo . '. Error: ' . $mail->ErrorInfo . '<br>';
        }
    }


    // public function registrarReserva()
    // {
    //     $reservas = $this->request->getPost('pasajeros');

    //     $modelo = new participantes();
    //     $respuesta = [];

    //     if (!empty($reservas)) {
    //         foreach ($reservas as $p) {
    //             // Primero registrar al pasajero
    //             $idPasajero = $modelo->registrarPasajeros(
    //                 $p['Nombre'],
    //                 $p['Correo'],
    //                 $p['Telefono'],
    //                 $p['Edad'],
    //                 $p['Genero'],
    //             );

    //             // Luego registrar la reserva
    //             $reservaData = [
    //                 'Vuelo_Id'       => $p['Vuelo_Id'],
    //                 'Pasajero_Id'    => $idPasajero,
    //                 'Asiento'        => 0, // Como comentaste, de momento es 0
    //                 'Estado'         => 'Reservado', // o el valor por defecto
    //                 'Fecha_Reserva'  => date('Y-m-d H:i:s'),
    //             ];

    //             $modelo->registrarReserva($reservaData);

    //             // Guardar para devolver al AJAX
    //             $respuesta[] = [
    //                 'idPasajero' => $idPasajero,
    //                 'vueloId'    => $p['Vuelo_Id']
    //             ];
    //         }
    //     }

    //     return $this->response->setJSON($respuesta);
    // }

    // METODO QUE PONE EL PAGO COMO CANCELADO EL REGISTRO
    // public function cancelarRegistro()
    // {
    //     $modelo = new participantes();

    //     $idParticipante = $this->request->getVar('id');

    //     $data = [
    //         'StatusPago' => 'Cancelado',
    //         'TipoPlayera' => '',
    //         'Categoria' => 'Sin Categoria',
    //         'Cupon' => '',
    //         'NumeroParticipante' => ''
    //     ];

    //     $modelo->cancelarPaseDoble($idParticipante);

    //     $consulta = $modelo->cancelarRegistro($idParticipante, $data);

    //     return json_encode($consulta);
    // }
}
