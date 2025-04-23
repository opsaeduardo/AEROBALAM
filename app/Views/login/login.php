<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Aerobalam</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="<?php echo base_url(); ?>/public/js/sweetalert/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- toastr -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/css/toastr/toastr.min.css" />

    <style>
        :root {
            --colorVerde: #1F4F23;
            --colorDorado: #AA7D2C;
        }

        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 600px;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
            background: white;
        }

        .nav-custom {
            height: 90px;
            background-color: var(--colorVerde);
        }

        .div-img {
            padding: 50px 0;
            border-bottom-right-radius: 150px;
            margin-bottom: 5px;
        }

        .login-card label {
            font-size: 1.1rem;
            /* Tamaño más grande para el label */
        }

        .login-card .input-group-text i {
            font-size: 1.2rem;
            /* Tamaño más grande del ícono */
        }

        .login-card .form-control {
            font-size: 1.1rem;
            /* Texto del input más grande */
            padding: 0.75rem 1rem;
            /* Más espacio interior */
        }
    </style>
</head>

<body>

    <input type="text" id="ruta" value="<?php echo base_url() ?>" hidden>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm nav-custom mb-1">
        <div class="bg-light div-img">
            <img src="<?php echo base_url() ?>public/img/logoAEROBALAM.png" alt="Logo" height="80" class="me-2">
        </div>
    </nav>

    <!-- LOGIN FORM -->
    <div class="login-container">
        <div class="login-card">
            <div>
                <h2 class="text-center text-white px-0 py-4 rounded-top" style="background-color: var(--colorDorado); border-bottom-right-radius: 20px;">Iniciar Sesión</h2>
            </div>
            <div class="mb-3 p-3">
                <label for="usuario" class="form-label">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" id="usuario" placeholder="Ingresa tu usuario" autocomplete="off">
                </div>
            </div>
            <div class="mb-3 p-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" placeholder="Ingresa tu contraseña" autocomplete="off">
                </div>
            </div>
            <div class="mb-3 p-3 text-center">
                <button id="btnLogin" type="submit" class="btn btn-primary text-center btn-lg">Ingresar <i class="fa-solid fa-arrow-right ms-1"></i></button>
            </div>
            <!-- <button id="btnLogin">Iniciar Sesión</button> -->
            <button id="btnVerificar">Verificar Sesión</button>
            <button id="btnLogout">Cerrar Sesión</button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery 3.6.0 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- toastr JS -->
    <script src="<?php echo base_url(); ?>/public/js/toastr/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            var ruta = $('#ruta').val();
            // Botón iniciar sesión
            $('#btnLogin').click(function() {

                $.ajax({
                    url: ruta + 'LoginController/login',
                    type: 'POST',
                    data: {
                        'usuario': $('#usuario').val(),
                        'password': $('#password').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            // Redirige al dashboard
                            window.location.href = response.redirect;
                        } else if (response.status === 'error') {
                            toastr.error(response.message, 'Error');
                        }
                    },
                    error: function(error) {
                        console.log(error.responseText);
                    }
                });
            });

            // Botón verificar sesión
            $('#btnVerificar').click(function() {
                $.ajax({
                    url: ruta + 'LoginController/verificarSesion',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'ok') {
                            alert('Sesión activa: ' + response.usuario.Nombre);
                        } else {
                            alert(response);
                        }
                    },
                    error: function(error) {
                        console.log(error.responseText);
                    }
                });
            });

            // Botón cerrar sesión
            $('#btnLogout').click(function() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: '¿Cerrar sesión?',
                    text: "Esta a punto de cerrar sesión del sistema ¿continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Cerrar Sesión',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?php echo base_url('/LoginController/logout'); ?>';
                    }
                })
                // $.ajax({
                //     url: ruta + 'LoginController/logout',
                //     type: 'GET',
                //     dataType: 'text',
                //     success: function(response) {
                //         alert(response.mensaje);
                //     },
                //     error: function(error) {
                //         console.log(error.responseText);
                //     }
                // });
            });
        });
    </script>
</body>

</html>