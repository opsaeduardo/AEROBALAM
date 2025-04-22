<?php
    use App\Controllers\Home;
    namespace App\Controllers;
    use App\Models\participantes;
    require 'vendor/autoload.php';
    
    class Webhook extends BaseController
    {
        //Regresa la vista
        public function index()
        {
            $stripe = new \Stripe\StripeClient(env('SECRET_KEY'));

            // This is your Stripe CLI webhook secret for testing your endpoint locally.
            // Amifit.  -- whsec_IbEyGsLnbHU4plN4kGlpEHzyJ4MTSj89
            //$endpoint_secret = 'whsec_ug7HndQNEubUs3jwDemPUGNxcgAPFVvk';
            $endpoint_secret = 'whsec_IbEyGsLnbHU4plN4kGlpEHzyJ4MTSj89';
            // local
            // $endpoint_secret = 'whsec_8cd3548b6334138c4ca3856c2c38a891f6e4ccd5b32fcf67ec248a5dce511ab3';
            
            $payload = @file_get_contents('php://input');
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            $event = null;
            
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpoint_secret
                );
            } catch (\UnexpectedValueException $e) {
                // Invalid payload
                http_response_code(400);
                exit();
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                http_response_code(400);
                exit();
            }
            
            // segunda version
            switch ($event->type) {
                case 'checkout.session.async_payment_failed':
                    $session = $event->data->object;
                    break;
                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;
                    break;
                case 'checkout.session.expired':
                    $session = $event->data->object;
                    break;
                case 'payout.canceled':
                    $payout = $event->data->object;
                    break;
                case 'payout.created':
                    $payout = $event->data->object;
                    break;
                case 'payout.failed':
                    $payout = $event->data->object;
                    break;
                case 'payout.paid':
                    $payout = $event->data->object;
                    break;
                case 'payout.reconciliation_completed':
                    $payout = $event->data->object;
                    break;
                case 'payout.updated':
                    $payout = $event->data->object;
                    break;
                case 'payment_intent.created':
                    $payment_intent = $event->data->object;
                    $paymentMethodTypes = $payment_intent->payment_method_types;
                    if($paymentMethodTypes[0] === "oxxo") {

                        $modelo = new participantes();
                        $home = new Home();

                        $id = $payment_intent->metadata->id;

                        $infoRegistro = $modelo->informacionRegistro($id);
                        $correo = $infoRegistro["Correo"];
                        
                        $data = [
                            'StatusPago' => 'Pendiente',
                            'TipoPago' => 'OXXO',
                        ];
                        // SE ACTUALIZA EL REGISTRO 
                        $actualizarRegistro = $modelo->actualizarRegistro($id, $data);
                        $mandarCorreo = $home->CorreoId($id, $correo);

                        $respuesta = [
                            'ActualizacionRegistro' => $actualizarRegistro,
                            'MandarCorreo' => $mandarCorreo
                        ];

                        return json_encode($respuesta);
                    }
                break;
                case 'payment_intent.succeeded':
                    $home = new Home();
                    $payment_intent = $event->data->object;
                    $paymentMethodTypes = $payment_intent->payment_method_types;
                    if($paymentMethodTypes[0] === "oxxo") {

                        $modelo = new participantes();

                        $idPago = $payment_intent->id;
                        // ID DEL CLIENTE
                        $id = $payment_intent->metadata->id;

                        $data = [
                            'StatusPago' => 'Pagado'
                        ];

                        $actualizarRegistro = $modelo->actualizarRegistro($id, $data);

                        return json_encode($actualizarRegistro);
                    }
                        
                break;
                case 'payment_intent.payment_failed':
                    $home = new Home();
                    $payment_intent = $event->data->object;
                    $paymentMethodTypes = $payment_intent->payment_method_types;
                    if($paymentMethodTypes[0] === "oxxo") {
                        $idCliente = $payment_intent->metadata->id;
                        $modelo = new participantes();
                        $data = [
                            'StatusPago' => 'Cancelado',
                            'TipoPlayera' => '',
                            'Categoria' => 'Sin Categoria',
                            'Cupon' => '',
                            'NumeroParticipante' => ''
                        ];

                        $modelo->cancelarPaseDoble($idCliente);

                        $consulta = $modelo->cancelarRegistro($idCliente, $data);
                
                        return json_encode($consulta);
                    }
                    
                break;
                default:
                    echo 'Received unknown event type ' . $event->type;
            }
            
            /*
            //primera version
            switch ($event->type) {
                case 'checkout.session.async_payment_failed':
                    $session = $event->data->object;
                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;
                    // echo "Pago exitoso, ID de pago checkout.session.async_payment_failed: " . $session->id;
                case 'checkout.session.completed':
                    $session = $event->data->object;
                case 'checkout.session.expired':
                    $session = $event->data->object;
                case 'payout.canceled':
                    $payout = $event->data->object;
                    // echo "Pago cancelado, ID de pago payout.canceled: " . $payout->id;
                case 'payout.created':
                    $payout = $event->data->object;
                case 'payout.failed':
                    $payout = $event->data->object;
                case 'payout.paid':
                    // echo "payout.paid : " . $session->id;
                    $payout = $event->data->object;
                case 'payout.reconciliation_completed':
                    $payout = $event->data->object;
                case 'payout.updated':
                    $payout = $event->data->object;
                    // ... handle other event types
                default:
                    echo 'Received unknown event type ' . $event->type;
            }*/
            
            http_response_code(200);
        }
    }
?>