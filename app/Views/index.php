<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url() ?>public/css/style.css?=1.5">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/qrious@4.0.2/dist/qrious.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-parallax-js@5.5.1/dist/simpleParallax.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="<?php echo base_url() ?>public/img/ico.png">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>



    <title>AEROBALAM</title>

</head>

<body>

    <input type="text" id="ruta" value="<?php echo base_url() ?>" hidden>
    <div style="display: none;" class="contenido-principal">
        <!-- MODAL DE PAGO -->
        <div class="modal fade" tabindex="1" role="dialog" id="selectorPago">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header modal-header-color">
                        <h3 class="modal-title text-light">Escanea el código QR para continuar con el pago</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="border-end col-12 col-md-8 d-flex flex-column align-items-center justify-content-center">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h6 class="text-center"><i id="ticket-nombre"></i></h6>
                                </div>
                                <h5><i>PAGO DE BOLETO(S)</i></h5>
                                <hr class="w-100">
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-1 w-100 fw-bold" id="llenado-boletos">

                                    <!-- <div class="col-md-4 d-flex flex-column border-dark rounded-3 p-1 bg-light shadow-lg position-relative">
                                        <div class="border-top border-dark border-2 dotted"></div>
                                        <div class="border-bottom border-dark border-2 dotted mt-auto"></div>
    
                                        <small class="fw-semibold text-secondary"><i class="bi bi-person-fill me-1"></i><span id="ticket-nombre" class="text-dark">Nombre Completo del usuario</span></small>
                                        <small class="text-muted"><i class="bi bi-calendar-fill me-1"></i><span id="ticket-edad">22 años</span></small>
                                        <small class="text-truncate text-muted"><i class="bi bi-envelope-fill me-1"></i><span id="ticket-correo">correo@correo.com</span></small>
                                        <div class="mt-3">
                                            <small class="fw-bold fs-5 text-success"><i class="bi bi-tag-fill me-1"></i><span id="ticket-costoReal">$ 850.00</span></small>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-4 d-flex flex-column">
                                        <small class=""><i id="">Jose Eduardo Contreras Romero</i></small>
                                        <small class="">27 años</small>
                                        <small class="">chivaa.97@gmail.com</small>
                                        <small class=""><i id="ticket-costoReal">$ 850.00</i></small>
                                    </div>
                                    <div class="col-4 d-flex flex-column">
                                        <small class=""><i id="">Jose Eduardo Contreras Romero</i></small>
                                        <small class="">27 años</small>
                                        <small class="">chivaa.97@gmail.com</small> -->
                                    <!-- <small class=""><i id="ticket-costoReal">$ 850.00</i></small> -->
                                    <!-- </div> -->
                                </div>
                                <div class="align-items-center w-100 mt-4">
                                    <h3 class="text-end text-success">Monto Total:</h3>
                                    <h4 class="text-end text-success"><i id="pago-total"></i></h4>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center">
                                <div id="pagoTarjeta" class="col-12 text-center img-modal">
                                    <img alt="Código QR" id="codigo" src="">
                                </div>
                                <div class="col-12 text-center img-modal">
                                    <h3 id="textoCaducidad" class="text-center text-success">Este enlace expira en 5 minutos.</h3>
                                    <h2 id="cronometro" class="text-center text-success">5:00</h2>
                                </div>
                                <button hidden id="reiniciarPagina" class="btn btn-success btn-lg">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg">
                    <div class="modal-header modal-header-color">
                        <h5 class="modal-title text-light" id="registroModalLabel">Compra de boletos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row seleccion-destino">
                            <h2 class="text-center mb-2">SELECCIONA TU PUNTO DE PARTIDA</h2>
                            <?php foreach ($destinos as $destino): ?>
                                <div class="col-6 mb-4 tarjeta-destino" data-id="<?= $destino['Id'] ?>">
                                    <div class="card shadow-sm text-center h-90 boton-dorado">
                                        <div class="card-body d-flex flex-column justify-content-center boton-dorado">
                                            <h2 class="card-title fs-1 text-white mb-2"><?= htmlspecialchars($destino['Codigo']) ?></h2>
                                            <p class="card-text text-white mb-0">(<?= htmlspecialchars($destino['Nombre']) ?>)</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="mb-3 selector-fechas" style="display: none;">
                                <label for="fechaSelect" class="form-label">Selecciona tu fecha de vuelo:</label>
                                <input type="text" class="form-control" id="fechaSelect" placeholder="Elige una fecha disponible" readonly>
                                <small id="numeroPasajeros" class="form-text text-muted"></small> <!-- Aquí se mostrará el número de pasajeros -->
                            </div>

                            <!-- <select class="form-select" id="fechaSelect" size="3">

                            </select> -->

                        </div>

                        <div style="display: none" class="row formulario">
                            <hr>
                            <div class="col-md-8">
                                <h3>Registrar Pasajero</h3>
                                <div class="row">

                                    <div class="mb-3 col-6">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" autocomplete="off">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="edad" class="form-label">Edad:</label>
                                        <input type="number" class="form-control" id="edad" autocomplete="off">
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="tel" class="form-control" id="telefono" autocomplete="off">
                                    </div>
                                    <!-- select genero -->
                                    <div class="mb-3 col-4">
                                        <label for="genero" class="form-label">Género:</label>
                                        <select class="form-select" id="genero">
                                            <option value="" disabled selected>Selecciona un género</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="correo" class="form-label">Correo:</label>
                                        <input type="email" class="form-control" id="correo" autocomplete="off">
                                    </div>
                                    <button id="agregar-registro" class="btn btn-primary">Agregar</button>
                                    <button style="display: none;" id="actualizar-registro" class="btn btn-warning botones-edicion">Actualizar</button>
                                    <button style="display: none;" id="cancelar-edicion" class="btn btn-secondary botones-edicion">Cancelar</button>
                                </div>
                            </div>
                            <div class="col-md-4 bg-light p-1">
                                <h3 class="mb-3">Pasajeros Registrados</h3>
                                <div id="registros-lista"></div>
                                <small id="precio-total"></small>

                                <div class="bg-white mt-2 p-2 rounded">
                                    <h6 class="form-label">Si tienes un cupón, ingrésalo aquí</h6>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cupon" autocomplete="off" placeholder="Código de cupón">
                                        <button class="btn btn-primary" type="button" id="aplicarCupon">Aplicar</button>
                                    </div>
                                </div>

                                <button id="pagar" class="btn btn-success w-100 mt-3" disabled>Comprar Boleto(s)</button>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div hidden class="boton-arriba">
            <i class="fas fa-chevron-circle-up"></i>
        </div>

        <nav class="navbar navbar-expand-lg shadow-sm navbar-custom">
            <div class="container-fluid">
                <div class="logo-container">
                    <a class="navbar-brand d-flex align-items-center" href="javascript:void(0)">
                        <img src="<?php echo base_url() ?>public/img/logoAEROBALAM.png" alt="Logo" width="320" height="100" class="me-2">
                    </a>
                </div>
                <div class="d-flex ms-auto align-items-center">
                    <!--<a href="https://facebook.com" target="_blank" class="btn btn-link text-light fs-5 me-2">-->
                    <!--    <i class="fab fa-facebook"></i>-->
                    <!--</a>-->
                    <!--<a href="https://instagram.com" target="_blank" class="btn btn-link text-light fs-5">-->
                    <!--    <i class="fab fa-instagram"></i>-->
                    <!--</a>-->
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row" style="height: 80vh;">
                <div class="col-6 d-flex flex-column">
                    <div class="flex-grow-1 mb-2">
                        <img src="<?php echo base_url() ?>public/img/avion3.jpeg" class="w-100 h-100 object-fit-cover top-radius" alt="Imagen 1">
                    </div>
                    <div class="d-flex flex-grow-1 gap-2">
                        <div class="w-50">
                            <img src="<?php echo base_url() ?>public/img/avion2.jpeg" class="w-100 h-100 object-fit-cover rounded" alt="Imagen 2">
                        </div>
                        <div class="w-50">
                            <img src="<?php echo base_url() ?>public/img/avion.jpeg" class="w-100 h-100 object-fit-cover rounded" alt="Imagen 3">
                        </div>
                    </div>
                </div>
                <div class="col-6 bg-light d-flex align-items-center justify-content-center flex-column text-center">
                    <h2>VIAJA Y</h2>
                    <p style="color: #1f4f23; font-size: 60px;">EXPLORA</p>
                    <img src="<?php echo base_url() ?>public/img/logoAEROBALAM.png" alt="LOGO AEROBALAM">
                    <p>¡Viaja rápido y cómodo con AEROBALAM! Conecta Tuxtla y Tapachula en menos tiempo, con el mejor servicio y tarifas accesibles. ¡Compra hoy tu vuelo y vive la experiencia de volar con nosotros!</p>
                    <div class="container pb-4">
                        <h2 class="boton-dorado text-white p-2">COMPRA TUS BOLETOS &nbsp;<i class="fas fa-hand-point-left"></i></h2>
                    </div>
                    <img width="250" src="<?php echo base_url() ?>public/img/logoExtraordinario.jpg" alt="LOGO AEROBALAM">
                </div>
            </div>
        </div>
    </div>

    <div style="display: none;" class="contenido-ticket container">

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/8c0737a2ba.js" crossorigin="anonymous"></script>
    <!-- <script src="<?php echo base_url() ?>public/js/main.js?v=<?php echo time(); ?>"></script> -->

    <script src="<?php echo base_url(); ?>/public/js/sweetalert/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        var ruta = "<?php echo base_url(); ?>";
        var stripePublicId = "<?php echo env('PUBLIC_ID') ?>";
        var datos = <?php echo json_encode($_GET); ?>;
    </script>

    <script>
        $(document).ready(function() {
            /* VARIABLES GENERALES */
            var idOrigen = 0; /* variable que contiene el id del destino seleccionado */
            var idFecha = 0; /* variable que contiene el id de la fecha seleccionada */
            var asientosDisponibles = 0 /* variable que contiene la cantidad de asientos disponibles */
            var precioBoleto = 0; /* variable que contiene el precio del boleto */
            var total = 0; /* variable que contiene el total a pagar */

            let tiempo = 300;
            const cronometro = $("#cronometro");

            const actualizarCronometro = () => {
                const minutos = Math.floor(tiempo / 60);
                const segundos = tiempo % 60;
                cronometro.text(`${minutos}:${segundos.toString().padStart(2, '0')}`);
            };

            var ruta = $("#ruta").val();

            var url = window.location.href;

            // CUANDO EL PAGO ES CON LINK
            if (url.includes('Link')) {
                console.log(datos["idSocioLink"]);
                // return;
                $.ajax({
                    type: "POST",
                    url: ruta + "Home/actualizarURL",
                    data: {
                        'id': datos["idSocioLink"],
                        // 'url': url
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);


                        $(".contenido-ticket").show();

                        let html = '';

                        // Recorremos los datos para crear el HTML y los QR
                        response.forEach(function(item, index) {
                            html += `
                            <div class="card mb-3 shadow-lg rounded-3">
                                <div class="card-header text-white py-2 rounded-top" style="background-color: white;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <img src="<?php echo base_url() ?>public/img/logoAEROBALAM.png" alt="Logo" width="320" height="100" class="me-2">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6 col-12">
                                            <h5 class="card-title text-primary fw-semibold">${item.Nombre}</h5>
                                            <p class="card-text mb-1"><i class="fas fa-envelope me-2 text-muted"></i> ${item.Correo}</p>
                                            <p class="card-text mb-1"><i class="fas fa-phone me-2 text-muted"></i> ${item.Telefono}</p>
                                            <p class="card-text mb-1"><i class="fas fa-user-alt me-2 text-muted"></i> Edad: ${item.Edad}</p>
                                        </div>
                                        <div class="col-md-6 col-12 text-end">
                                            <p class="card-text mb-1"><strong class="text-secondary">Origen:</strong> <span class="text-muted">${item.Origen_Codigo} - ${item.Origen_Nombre}</span></p>
                                            <p class="card-text mb-1"><strong class="text-secondary">Destino:</strong> <span class="text-muted">${item.Destino_Codigo} - ${item.Destino_Nombre}</span></p>
                                            <p class="card-text mb-1"><strong class="text-secondary">Fecha (AAAA-MM-DD):</strong> <span class="text-muted">${item.Vuelo_Fecha}</span></p>
                                            <p class="card-text mb-0"><strong class="text-secondary">Estado de Pago:</strong> <span class="text-muted">${item.StatusPago}</span></p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <img id="codigo-${index}" alt="Código QR" />
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });

                        $(".contenido-ticket").html(html);

                        // Ahora generamos los códigos QR e inmediatamente los convertimos a base64
                        response.forEach(function(item, index) {
                            var qr = new QRious({
                                element: document.getElementById("codigo-" + index),
                                value: ruta + '?idQR=' + item.ReservaPagoId,
                                size: 200,
                                backgroundAlpha: 1,
                                foreground: "#3c3c3c",
                                background: "white",
                                foregroundAlpha: 1,
                                level: "H",
                            });

                            // Obtener el código QR generado en base64
                            var valorUrl = document.getElementById("codigo-" + index).src;

                            // Guardamos la imagen base64 en el objeto 'item'
                            item.imgBase64 = valorUrl;

                            // Enviamos la imagen base64 al servidor para guardarla
                            $.ajax({
                                url: ruta + 'Home/QR',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    imgBase64: item.imgBase64,
                                    id: item.ReservaPagoId
                                },
                                success: function(respuesta) {
                                    console.log(respuesta);
                                    if (respuesta === 'ExisteImagen') {
                                        return;
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: ruta + "Home/Correo",
                                        data: {
                                            correo: item.Correo,
                                            id: item.ReservaPagoId
                                        },
                                        dataType: "text",
                                        success: function(response) {
                                            console.log(response);
                                        },
                                        error: function(e) {
                                            console.log(e);
                                        }
                                    });
                                    console.log("aqui sigo")
                                },
                                error: function(e) {
                                    console.error(`Error al guardar la imagen de ${item.ReservaPagoId}:`, e);
                                }
                            });
                        });
                    },

                    error: function(e) {
                        console.log(e);
                    }
                });

                /* CUANDO SE ESCANEA EL QR */
            } else if (url.includes('idQR')) {
                $(".contenido-ticket").show();
                console.log(datos["idQR"]);
                $.ajax({
                    type: "GET",
                    url: "Home/informacionRegistro",
                    data: {
                        'id': datos["idQR"]
                    },
                    dataType: "json",
                    success: function(response) {
                        let html = `
                        <div class="d-flex justify-content-center align-items-center flex-column" style="font-family: Roboto, Helvetica, Arial, sans-serif; color:#343a40;">
                            <div class="mb-4">
                                
                            </div>

                            <div class="shadow-lg mb-5 bg-white rounded-4 border border-light-subtle" style="min-width:300px; max-width:1200px; width:100%;">
                                <div class="text-success text-center mb-3 fw-bold" style="font-size:24px;"><img src="<?php echo base_url() ?>public/img/logoAEROBALAM.png" class="img-fluid" style="max-width:300px;"></div>
                                <div class="row p-3">
                                    <hr class="border-dark opacity-50">
                                    <div class="col-md-6 col-12 mb-3">
                                        <h3 class="text-primary mt-3 mb-3 fw-semibold" style="font-size:20px;">👤 Detalles del usuario</h3>
                                        <!-- <p class="fs-5"><strong>Reserva:</strong> ${response.ReservaPagoId}</p> -->
                                        <ul class="list-unstyled fs-5 mb-4">
                                            <li><strong class="text-dark">Nombre:</strong> ${response.Nombre}</li>
                                            <li><strong class="text-dark">Correo:</strong> ${response.Correo}</li>
                                            <li><strong class="text-dark">Teléfono:</strong> ${response.Telefono}</li>
                                            <li><strong class="text-dark">Edad:</strong> ${response.Edad}</li>
                                            <li><strong class="text-dark">Género:</strong> ${response.Genero}</li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <div class="col-md-6 col-12 mb-3">
                                        <h3 class="text-primary mt-3 mb-3 fw-semibold" style="font-size:20px;">✈️ Detalles del Vuelo</h3>

                                        <ul class="list-unstyled fs-5">
                                            <li><strong class="text-dark">Origen:</strong> ${response.Origen_Codigo} - ${response.Origen_Nombre}</li>
                                            <li><strong class="text-dark">Destino:</strong> ${response.Destino_Codigo} - ${response.Destino_Nombre}</li>
                                            <li><strong class="text-dark">Fecha:</strong> ${response.Vuelo_Fecha}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                        $(".contenido-ticket").html(html);
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });

            } else {

                $(".contenido-principal").show();

                $(".boton-dorado").click(function() {
                    $("#registroModal").modal("show");
                });

                /* METODO QUE SELECCIONA EL DESTINO */
                $(document).on("click", ".tarjeta-destino", function() {
                    idOrigen = $(this).data("id");
                    // console.log("ID del destino seleccionado: " + idOrigen);
                    // $(".seleccion-destino").hide(500);
                    $(".selector-fechas").show(600);
                    // $(".formulario").show(600);


                    $.ajax({
                        url: ruta + 'Home/consultarFecha/',
                        type: 'GET',
                        data: {
                            'Origen_Id': idOrigen
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Cargando...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(data) {
                            Swal.close();
                            console.log(data);
                            if (data === '0') {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se encontraron vuelos disponibles para el destino seleccionado.',
                                    confirmButtonText: 'Aceptar',
                                })
                            } else {
                                let fechasDisponibles = [];

                                $.each(data, function(index, vuelo) {
                                    if (parseInt(vuelo.Asientos_Libres) > 0) {
                                        fechasDisponibles.push(vuelo.FechaISO);
                                    }
                                });

                                if (fechasDisponibles.length > 0) {
                                    $(".selector-fechas").show();


                                    // OBTIENE LA ULTIMA FECHA PARA EL MAX DATE
                                    let ultimaFecha = fechasDisponibles[fechasDisponibles.length - 1];
                                    // OBTIENE LA PRIMERA FECHA PARA EL MIN DATE
                                    let primeraFecha = fechasDisponibles[0];

                                    flatpickr("#fechaSelect", {
                                        dateFormat: "Y-m-d", // Formato que devuelve FechaISO, ejemplo: 2024-04-20
                                        enable: fechasDisponibles, // Solo fechas válidas
                                        locale: "es",
                                        minDate: primeraFecha,
                                        maxDate: ultimaFecha,
                                        onChange: function(selectedDates, dateStr, instance) {
                                            console.log("Fecha seleccionada:", dateStr);

                                            // Busca el vuelo correspondiente a la fecha seleccionada
                                            let vueloSeleccionado = data.find(vuelo => vuelo.FechaISO === dateStr);

                                            if (vueloSeleccionado) {
                                                registros = [];
                                                actualizarListaRegistros();
                                                console.log("Vuelo seleccionado:", vueloSeleccionado);
                                                console.log("ID:", vueloSeleccionado.Id);
                                                console.log("Precio:", vueloSeleccionado.Precio);
                                                console.log("Asientos libres:", vueloSeleccionado.Asientos_Libres);

                                                idFecha = vueloSeleccionado.Id;
                                                precioBoleto = vueloSeleccionado.Precio;
                                                asientosDisponibles = vueloSeleccionado.Asientos_Libres;

                                                // Actualiza el número de pasajeros al lado del calendario o debajo del input
                                                let numeroPasajeros = vueloSeleccionado.Asientos_Libres;
                                                $("#numeroPasajeros").text(`${numeroPasajeros} lugares disponibles`);

                                                // Mostrar en el input el valor de la fecha seleccionada
                                                $("#fechaSelect").val(dateStr);

                                                // Si necesitas mostrar el formulario después de seleccionar la fecha
                                                $(".formulario").show(600);
                                            }
                                        }
                                    });


                                } else {
                                    $(".selector-fechas").hide();
                                    $(".formulario").hide();
                                    registros = [];
                                    actualizarListaRegistros();
                                    if ($("#fechaSelect")[0] && $("#fechaSelect")[0]._flatpickr) {
                                        $("#fechaSelect")[0]._flatpickr.destroy();
                                    }

                                    swal.fire({
                                        icon: 'warning',
                                        title: 'Sin vuelos disponibles',
                                        text: 'No se encontraron vuelos disponibles para el destino seleccionado.',
                                        confirmButtonText: 'Aceptar',
                                    })
                                }


                                // $.each(data, function(index, vuelo) {
                                //     let disabled = vuelo.Asientos_Libres == 0 ? 'disabled' : '';
                                //     select.append(
                                //         `<option class="m-2" value="${vuelo.Id}" data-precio="${vuelo.Precio}" data-asientos="${vuelo.Asientos_Libres}" ${disabled}>
                                //     ${vuelo.Fecha} - ${vuelo.Hora} (${vuelo.Asientos_Libres} asientos disponibles)
                                //  </option>`
                                //     );
                                // });

                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud:", error);
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo cargar la información de los vuelos.',
                            });
                        }
                    });

                });
                /* METODO QUE SELECCIONA LA FECHA */
                // $(document).on("change", "#fechaSelect", function() {
                //     idFecha = $(this).val();
                //     precioBoleto = $(this).find(':selected').data('precio');
                //     asientosDisponibles = $(this).find(':selected').data('asientos');
                //     console.log(precioBoleto)
                //     console.log("ID de la fecha seleccionada: " + idFecha);
                //     console.log("Asientos disponibles: " + asientosDisponibles);
                //     // $(".selector-fechas").hide(500);
                //     $(".formulario").show(600);
                // });


                /* METODO QUE REINICIA LA MODAL */
                $('#registroModal').on('hidden.bs.modal', function(e) {

                    //LIMPIA CAMPOS DEL FORMULARIO
                    $('input, select, textarea', this).val('');

                    // OCULTA EL FORMULARIO
                    $(".formulario").hide();

                    // OCULTA EL SELECT DE FECHAS
                    $(".selector-fechas").hide();

                    // RESTAURA LOS BOTONES
                    agregarButton.show();
                    actualizarButton.hide();
                    cancelarEdicionButton.hide();

                    // SE RESETEAN LAS VARIABLES
                    idOrigen = 0;
                    idFecha = 0;
                    precioBoleto = 0;
                    total = 0;

                    // SE COLOCA EL TEXTO DE TOTAL EN 0
                    $('#precio-total').text('Precio total: $0.00');
                    $("#numeroPasajeros").text('');

                    // LIMPIA LOS REGISTROS
                    registros = [];
                    actualizarListaRegistros();

                    // Puedes también limpiar mensajes o estados visuales:
                    $(this).find('.is-invalid').removeClass('is-invalid');
                });

                let registros = [];
                let registroActual = {};
                let indiceEdicion = -1; // -1 indica que no se está editando ningún registro

                const nombreInput = $('#nombre');
                const edadInput = $('#edad');
                const telefonoInput = $('#telefono');
                const correoInput = $('#correo');
                const genereroInput = $('#genero');
                const agregarButton = $('#agregar-registro');
                const actualizarButton = $('#actualizar-registro');
                const cancelarEdicionButton = $('#cancelar-edicion');
                const pagarButton = $('#pagar');
                const registrosLista = $('#registros-lista');

                function actualizarListaRegistros() {
                    registrosLista.empty();
                    registros.forEach((registro, index) => {
                        const card = $(`
                        <div class="card registro-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <h6>${registro.Nombre}</h6>
                                    <small>${registro.Correo}</small>
                                    <small>${registro.Edad}</small>
                                    <small>${registro.Telefono}</small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary editar-registro" data-index="${index}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger eliminar-registro" data-index="${index}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                        registrosLista.append(card);


                    });

                    console.log(precioBoleto);
                    console.log(registros.length);
                    total = precioBoleto * registros.length;
                    var totalFormateado = total.toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    });

                    $('#precio-total').text(`Precio total: ${totalFormateado}`);

                    pagarButton.prop('disabled', registros.length === 0);
                }

                function limpiarFormulario() {
                    nombreInput.val('');
                    edadInput.val('');
                    telefonoInput.val('');
                    correoInput.val('');
                    genereroInput.val('');
                    registroActual = {};
                    indiceEdicion = -1;
                    agregarButton.show();
                    actualizarButton.hide();
                    cancelarEdicionButton.hide();
                }

                function cargarRegistroEnFormulario(registro) {
                    nombreInput.val(registro.Nombre || '');
                    edadInput.val(registro.Edad || '');
                    telefonoInput.val(registro.Telefono || '');
                    correoInput.val(registro.Correo || '');
                    genereroInput.val(registro.Genero || '');
                    registroActual = {
                        ...registro
                    };
                    agregarButton.hide();
                    actualizarButton.show();
                    cancelarEdicionButton.show();
                }

                agregarButton.click(function() {
                    if (registros.length >= asientosDisponibles || registros.length >= 3) {
                        let mensaje = registros.length >= 3 ?
                            'Solo puedes agregar hasta 3 registros.' :
                            `No hay asientos disponibles para la fecha seleccionada.`;

                        Swal.fire({
                            icon: 'warning',
                            title: 'Límite alcanzado',
                            text: mensaje,
                        });
                        return;
                    }

                    const nuevoRegistro = {
                        Nombre: nombreInput.val(),
                        Edad: edadInput.val(),
                        Telefono: telefonoInput.val(),
                        Correo: correoInput.val(),
                        Genero: genereroInput.val(),
                        Vuelo_Id: idFecha,
                    };
                    console.log(nuevoRegistro);
                    if (nuevoRegistro.Nombre && nuevoRegistro.Correo && nuevoRegistro.Telefono && nuevoRegistro.Edad && nuevoRegistro.Genero) {
                        registros.push(nuevoRegistro);
                        actualizarListaRegistros();
                        limpiarFormulario();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos requeridos',
                            text: 'Todos los campos son obligatorios.',
                        });
                    }
                });

                registrosLista.on('click', '.editar-registro', function() {
                    const index = parseInt($(this).data('index'));
                    if (index >= 0 && index < registros.length) {
                        indiceEdicion = index;
                        cargarRegistroEnFormulario(registros[index]);
                    }
                });

                actualizarButton.click(function() {
                    if (indiceEdicion !== -1) {
                        const registroEditado = {
                            Nombre: nombreInput.val(),
                            Edad: edadInput.val(),
                            Telefono: telefonoInput.val(),
                            Correo: correoInput.val(),
                            Genero: genereroInput.val(),
                            Vuelo_Id: idFecha,
                        };
                        registros[indiceEdicion] = registroEditado;
                        actualizarListaRegistros();
                        limpiarFormulario();
                    }
                });

                cancelarEdicionButton.click(function() {
                    limpiarFormulario();
                });

                registrosLista.on('click', '.eliminar-registro', function() {
                    const indexEliminar = parseInt($(this).data('index'));
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción eliminará el registro.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            registros.splice(indexEliminar, 1);
                            actualizarListaRegistros();
                            limpiarFormulario(); // Limpiar formulario después de eliminar
                            Swal.fire(
                                '¡Eliminado!',
                                'El registro ha sido eliminado.',
                                'success'
                            )
                        }
                    });
                });

                $('#registroModal').on('hidden.bs.modal', function() {
                    limpiarFormulario();
                    registros = [];
                    actualizarListaRegistros();
                });

                pagarButton.click(function() {

                    /* VALIDACION DE QUE UNA FECHA HAYA SIDO SELECCIONADA */
                    if (idFecha === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha no seleccionada',
                            text: 'Por favor, selecciona una fecha antes de pagar.',
                        });
                        return;
                    }
                    if (registros.length > 0) {
                        console.log(registros);
                        // Aquí envías por AJAX
                        $.ajax({
                            url: ruta + 'Home/registrarReserva',
                            type: 'POST',
                            data: {
                                pasajeros: registros,
                                'Total': total,
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Cargando...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                Swal.close();
                                console.log(response);
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Completado!',
                                    text: 'A continuación procederás a realizar el pago.'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        let contenedor = $('#llenado-boletos');
                                        contenedor.empty();
                                        console.log(registros);
                                        registros.forEach(function(registro) {

                                            /* 
                                         <div class="col-md-4 d-flex flex-column border-dark rounded-3 p-1 bg-light shadow-lg position-relative">
                                    <div class="border-top border-dark border-2 dotted"></div>
                                    <div class="border-bottom border-dark border-2 dotted mt-auto"></div>

                                    <small class="fw-semibold text-secondary"><i class="bi bi-person-fill me-1"></i><span id="ticket-nombre" class="text-dark">Nombre Completo del usuario</span></small>
                                    <small class="text-muted"><i class="bi bi-calendar-fill me-1"></i><span id="ticket-edad">22 años</span></small>
                                    <small class="text-truncate text-muted"><i class="bi bi-envelope-fill me-1"></i><span id="ticket-correo">correo@correo.com</span></small>
                                    <div class="mt-3">
                                        <small class="fw-bold fs-5 text-success"><i class="bi bi-tag-fill me-1"></i><span id="ticket-costoReal">$ 850.00</span></small>
                                    </div>
                                </div>
                                        */
                                            contenedor.append(`
                                            <div class="col-md-4 d-flex flex-column mb-3 border border-1 rounded-2 p-3 bg-white shadow-sm">
                                                <small class="fw-semibold text-secondary"><i class="fas fa-user me-2"></i> <span class="text-dark">${registro.Nombre}</span></small>
                                                <small class="text-muted"><i class="fas fa-calendar-alt me-2"></i> ${registro.Edad} años</small>
                                                <small class="text-truncate text-muted"><i class="fas fa-envelope me-2"></i> ${registro.Correo}</small>
                                            </div>
                                        `);
                                        });

                                        // Mostrar total formateado en el input o div con clase .pago-total
                                        $('#pago-total').text(`${total.toLocaleString('es-MX', {style: 'currency', currency: 'MXN'})}`);

                                        // $("#registroModal").modal("hide");
                                        // $("#selectorPago").modal("show");
                                        // retun;
                                        console.log(response["idPago"]);
                                        console.log(total);
                                        // return;
                                        $.ajax({
                                            url: ruta + "Stripe/createPaymentLink",
                                            type: "POST",
                                            data: {
                                                'id': response["idPago"],
                                                'monto': total
                                            },
                                            dataType: "JSON",
                                            beforeSend: function() {
                                                Swal.fire({
                                                    didOpen: function() {
                                                        Swal.showLoading()
                                                    },
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false,
                                                    showConfirmButton: false
                                                });
                                            },
                                            success: function(datos) {
                                                swal.close();
                                                console.log(datos);
                                                linkId = datos.paymentLinkId;

                                                const codigoElement = document.querySelector("#codigo");
                                                var qr = new QRious({
                                                    element: codigoElement,
                                                    value: datos.paymentLink,
                                                    size: 200,
                                                    backgroundAlpha: 1,
                                                    foreground: "#3c3c3c",
                                                    background: "white",
                                                    foregroundAlpha: 1,
                                                    level: "H",
                                                });

                                                $("#registroModal").modal("hide");
                                                $("#selectorPago").modal("show");

                                                // EVITAR CERRAR MODALES
                                                $('#selectorPago').modal({
                                                    backdrop: 'static',
                                                    keyboard: false
                                                });

                                                // MANDA ALERTA DE PERDIDA DE PROCESO EN CASO DE CERRAR MODAL
                                                $('#selectorPago').on('hide.bs.modal', function(e) {
                                                    e.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Estás seguro de cancelar el pago?',
                                                        text: "Perderás todo tu proceso",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Sí, cerrar',
                                                        cancelButtonText: 'No, cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {

                                                            // CANCELA EL REGISTRO
                                                            $.ajax({
                                                                url: ruta + "Stripe/deactivatePaymentLink", // Ruta al método del controlador
                                                                type: "POST",
                                                                data: {
                                                                    paymentLinkId: linkId // ID del enlace de pago
                                                                },
                                                                dataType: "JSON",
                                                                beforeSend: function() {
                                                                    Swal.fire({
                                                                        didOpen: function() {
                                                                            Swal.showLoading()
                                                                        },
                                                                        allowOutsideClick: false,
                                                                        allowEscapeKey: false,
                                                                        showConfirmButton: false
                                                                    });
                                                                },
                                                                success: function(dataDescPayment) {
                                                                    swal.close();
                                                                    console.log(dataDescPayment);
                                                                    // ajax para cancelar el registro
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: ruta + "/Home/CancelarRegistro",
                                                                        data: {
                                                                            datos: JSON.stringify(response),
                                                                        },
                                                                        dataType: "json",
                                                                        success: function(dataCancelRegistro) {

                                                                            window.location.reload();
                                                                        },

                                                                        // ERROR AL CANCELAR REGISTRO
                                                                        error: function(e) {
                                                                            console.log(e);
                                                                            Swal.fire({
                                                                                title: "Error",
                                                                                text: "Hubo un error al eliminar registro, por favor, comunicate con nosotros",
                                                                                icon: "error"
                                                                            });
                                                                        }
                                                                    });
                                                                },
                                                                error: function(error) {
                                                                    console.log(error);
                                                                    swal.close();
                                                                    Swal.fire({
                                                                        title: "Error",
                                                                        text: "No se pudo procesar la solicitud.",
                                                                        icon: "error"
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                                });

                                                const intervalo = setInterval(() => {
                                                    if (tiempo > 0) {
                                                        tiempo--;
                                                        actualizarCronometro();
                                                        if (tiempo % 10 === 0) {
                                                            $.ajax({
                                                                url: ruta + "Home/informacionPago",
                                                                type: "POST",
                                                                data: {
                                                                    'idPago': response["idPago"]
                                                                },
                                                                dataType: "JSON",
                                                                success: function(informacionRegistro) {
                                                                    console.log(informacionRegistro);
                                                                    if (informacionRegistro["StatusPago"] == 'Pagado') {
                                                                        $("#reiniciarPagina").removeAttr("hidden");

                                                                        clearInterval(intervalo);

                                                                        $("#textoCaducidad").html('');

                                                                        cronometro.text('¡Felicidades! tu pago fue exitoso, has sido redirigido a la página principal, ya puedes cerrar esta ventana, tu inscripción se ha completado');

                                                                        $('#selectorPago').off('hide.bs.modal');

                                                                        $('#selectorPago').on('hide.bs.modal', function(e) {
                                                                            window.location.reload();
                                                                        });

                                                                        $("#codigo").hide(500);

                                                                        setTimeout(() => {
                                                                            window.location.reload();
                                                                        }, 10000);
                                                                    }

                                                                },
                                                                error: function(error) {
                                                                    console.error("Error al verificar el estado del pago:", error);
                                                                }
                                                            });
                                                        }
                                                    } else {
                                                        clearInterval(intervalo);
                                                        $("#textoCaducidad").html('');
                                                        cronometro.text("Enlace expirado, por favor, inténtalo de nuevo").removeClass("text-success").addClass("text-danger");

                                                        $("#codigo").hide(500);
                                                        $.ajax({
                                                            url: ruta + "Stripe/deactivatePaymentLink", // Ruta al método del controlador
                                                            type: "POST",
                                                            data: {
                                                                paymentLinkId: linkId // ID del enlace de pago
                                                            },
                                                            dataType: "JSON",
                                                            beforeSend: function() {
                                                                Swal.fire({
                                                                    didOpen: function() {
                                                                        Swal.showLoading()
                                                                    },
                                                                    allowOutsideClick: false,
                                                                    allowEscapeKey: false,
                                                                    showConfirmButton: false
                                                                });
                                                            },
                                                            success: function(dataDesactivarPaymentLink) {
                                                                swal.close();
                                                                console.log(dataDesactivarPaymentLink);
                                                                // ajax para cancelar el registro
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: ruta + "/Home/CancelarRegistro",
                                                                    data: {
                                                                        datos: JSON.stringify(response),
                                                                    },
                                                                    dataType: "json",
                                                                    success: function(dataCancelarElRegistro) {
                                                                        console.log(dataCancelarElRegistro);
                                                                        $("#reiniciarPagina").removeAttr("hidden");

                                                                        $('#selectorPago').off('hide.bs.modal');

                                                                        $('#selectorPago').modal({
                                                                            backdrop: true,
                                                                            keyboard: true
                                                                        });

                                                                        $('#selectorPago').on('hide.bs.modal', function(e) {
                                                                            window.location.reload();
                                                                        });
                                                                        setTimeout(() => {
                                                                            window.location.reload();
                                                                        }, 40000);

                                                                        $("#reiniciarPagina").click(function(e) {
                                                                            window.location.reload();
                                                                        });
                                                                    },

                                                                    // ERROR AL CANCELAR REGISTRO
                                                                    error: function(e) {
                                                                        console.log(e);
                                                                        Swal.fire({
                                                                            title: "Error",
                                                                            text: "Hubo un error al eliminar registro, por favor, comunicate con nosotros",
                                                                            icon: "error"
                                                                        });
                                                                    }
                                                                });
                                                            },
                                                            error: function(error) {
                                                                console.log(error);
                                                                swal.close();
                                                                Swal.fire({
                                                                    title: "Error",
                                                                    text: "No se pudo procesar la solicitud.",
                                                                    icon: "error"
                                                                });
                                                            }
                                                        });
                                                    }
                                                }, 1000);

                                                actualizarCronometro();
                                            },
                                            error: function(e) {
                                                swal.close();
                                                console.log(e);
                                                Swal.fire({
                                                    title: "Error!",
                                                    text: "Error al generar el enlace de pago, consulta al Administrador!",
                                                    icon: "error"
                                                });
                                            }
                                        });

                                        registros = [];
                                        actualizarListaRegistros();
                                    } else {
                                        console.log('El usuario cerró el mensaje o canceló.');
                                    }
                                });


                            },
                            error: function(e) {
                                Swal.close();
                                console.error(e);
                                Swal.fire('Error', 'No se pudo conectar al servidor.', 'error');
                            }
                        });

                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin registros',
                            text: 'No hay registros para pagar.',
                        });
                    }
                });

                actualizarListaRegistros();
            }

        });
    </script>
</body>

</html>