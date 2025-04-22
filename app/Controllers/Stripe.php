<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Participantes;
use CodeIgniter\BaseModel;
use Stripe\StripeClient;

require 'vendor/autoload.php';

class Stripe extends BaseController
{
    public function createSession()
    {

        $monto = $this->request->getVar('monto');

        $id = $this->request->getVar('id');
        $tipoPago = $this->request->getVar('tipoPago');

        $stripe = new StripeClient([
            // PRODUCCION
            "api_key" => env('SECRET_KEY'),
        ]);

        $checkout_session = $stripe->checkout->sessions->create([
            //'payment_method_types' => ['card', 'oxxo'],
            'payment_method_types' => [$tipoPago],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'MXN',
                    'product_data' => [
                        'name' => 'VAMOS ANCIANOS',
                    ],
                    'unit_amount' => $monto,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'ui_mode' => 'embedded',
            'return_url' => base_url() . "?StripePasarela_ID={CHECKOUT_SESSION_ID}&idSocio=$id",
            // 'return_url' => base_url() . "?StripePasarela_ID={CHECKOUT_SESSION_ID}&idSocio=$id&descripcion=$descripcion&correo=$correo&talla=$talla&edad=$edad",
            'metadata' => [
                'id' => $id,
                // 'nombre' => $nombre,
                // 'apellido' => $apellido,
                // 'correo' => $correo,
                // 'sexo' => $sexo,
                // 'monto' => $monto,
                // 'descripcion' => $descripcion,
                // 'gymId' => $gymId,
                // 'edad' => $edad,
                // 'talla' => $talla
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'id' => $id,
                    // 'nombre' => $nombre,
                    // 'apellido' => $apellido,
                    // 'correo' => $correo,
                    // 'edad' => $edad,
                    // 'sexo' => $sexo,
                    // 'monto' => $monto,
                    // 'descripcion' => $descripcion,
                    // 'gymId' => $gymId,
                    // 'talla' => $talla
                ]
            ]
        ]);
        echo json_encode(array('clientSecret' => $checkout_session->client_secret));
    }

    public function estadoStripe()
    {
        try {
            $stripe = new StripeClient([
                "api_key" => env('SECRET_KEY'),
            ]);
            $id = $this->request->getVar('id');
            $session = $stripe->checkout->sessions->retrieve($id);
            echo json_encode([
                $session

            ]);
            http_response_code(200);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function createPaymentLink()
    {
        $modeloParticipantes = new Participantes();

        $id = $this->request->getVar('id');
        $monto = $this->request->getVar('monto');

        $monto = round($monto * 100);
        
        // Crear una instancia del cliente de Stripe
        $stripe = new StripeClient([
            "api_key" => env('SECRET_KEY'),
        ]);

        $price = $stripe->prices->create([
            'unit_amount' => $monto, 
            'currency' => 'mxn',
            'product_data' => [
                'name' => 'Pago de Boleto(s)',
            ],
        ]);

        // Crear un enlace de pago en Stripe (con metadata para el id del participante)
        $paymentLinkStripe = $stripe->paymentLinks->create([
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'metadata' => [
                'id' => $id, 
                'puntoventa' => 'true', // Identificar que este enlace es del punto de venta
            ],
            'restrictions' => ['completed_sessions' => ['limit' => 1]],
            'inactive_message' => 'Lo sentimos, el enlace de pago ya no es válido.',
            'after_completion' => [
                'type' => 'redirect',
                'redirect' => [
                    'url' => base_url("?idSocioLink=" . $id),
                ],
            ],
        ]);
        /* QUEDA PENDIENTE EL ATRAPAR IDSOCIOLINK PARA PODRR ACTUALIZAR URL DE PAGO */

        // Devolver el nuevo enlace de pago
        echo json_encode([
            'paymentLink' => $paymentLinkStripe->url,
            'paymentLinkId' => $paymentLinkStripe->id,
        ]);
    }

    public function deactivatePaymentLink()
    {
        $paymentLinkId = $this->request->getVar('paymentLinkId');

        try {
            $stripe = new \Stripe\StripeClient(env('SECRET_KEY'));

            //DESACTIVA ENLACE DE PAGO
            $updatedLink = $stripe->paymentLinks->update(
                $paymentLinkId,
                ['active' => false]
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'El enlace de pago se desactivó correctamente.',
                'link' => $updatedLink
            ]);
        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al desactivar el enlace de pago: ' . $e->getMessage()
            ]);
        }
    }
    //     public function estadoStripe()
    //     {
    //         try {
    //             $stripe = new StripeClient([
    //                 "api_key" => env('SECRET_KEY'),
    //             ]);
    //             $id = $this->request->getVar('id');
    //             $session = $stripe->checkout->sessions->retrieve($id);
    //             echo json_encode([
    //                 'status' => $session->status,
    //                 // 'session' => $session,
    //                 'customer_email' => $session->customer_details->email,
    //                 'session' => $session,
    //                 'tipoPago' => $session->payment_method_types[0],
    //                 'montoTotal' => $session->amount_total,
    //             'IdOrden' => $session->id,
    //             'IdPago' => $session->payment_intent,
    //             'FechaPago' => date("Y-m-d H:i:s", $session->created),
    //             'Status' => $session->status

    //         ]);
    //         http_response_code(200);
    //     } catch (\Exception $e) {
    //         http_response_code(500);
    //         echo json_encode(['error' => $e->getMessage()]);
    //     }
    // }
}
