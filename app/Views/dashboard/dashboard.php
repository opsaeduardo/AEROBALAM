<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="<?php echo base_url(); ?>/public/js/sweetalert/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- toastr -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/css/toastr/toastr.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/css/dashboard/dashboard.css" />

    <style>

    </style>
</head>

<body>
    <input type="text" id="ruta" value="<?php echo base_url() ?>" hidden>

    <div class="wrapper">
        <!-- SIDEBAR -->
        <nav id="sidebarResponsive" class="sidebar col-12 col-md-3 col-lg-2 collapse d-md-block">
            <div class="d-flex flex-column h-100 p-3">
                <!-- Botón de cerrar sidebar (solo en pantallas pequeñas) -->
                <div class="d-md-none d-flex justify-content-end">
                    <button type="button" class="btn text-white" data-bs-toggle="collapse" data-bs-target="#sidebarResponsive" aria-label="Cerrar menú">
                        <i class="fa-solid fa-xmark fa-lg"></i>
                    </button>
                </div>
                <hr>
                <a href="/" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                    <i class="fa-solid fa-gauge-high me-2"></i>
                    <span class="fs-5">Dashboard</span>
                </a>
                <ul class="nav nav-pills flex-column mb-auto">
                    
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard'); ?>" class="nav-link text-white"><i class="fa-solid fa-house me-2"></i>Inicio</a>
                    </li>
                    <li>
                        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#usuariosMenu" role="button" aria-expanded="false">
                            <span><i class="fa-solid fa-users me-2"></i>Usuarios</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </a>
                        <ul class="collapse ps-3 list-unstyled" id="usuariosMenu">
                            <li><a href="#" class="nav-link text-white ps-4">Lista de usuarios</a></li>
                            <li><a href="#" class="nav-link text-white ps-4">Agregar nuevo</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('cupones'); ?>" class="nav-link text-white">
                            <i class="fa-solid fa-ticket me-2"></i>Cupones
                        </a>
                    </li>

                </ul>
                <hr>
                <div class="dropdown mt-auto">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="">
                        <strong>Admin</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="content-area">
            <nav class="navbar navbar-expand-lg shadow-sm">
                <div class="container-fluid">
                    <!-- BOTÓN DE HAMBURGUESA -->
                    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarResponsive" aria-controls="sidebarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars text-white"></i>
                    </button>
                    <div class="container-logo">
                        <img src="<?php echo base_url() ?>public/img/logoAEROBALAM.png" alt="Logo" class="logo-img">
                    </div>
                </div>
            </nav>

            <div class="content">
                <?= $this->renderSection('content') ?>
            </div>
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

            if (window.innerWidth < 768) {
                $('#sidebarResponsive a.nav-link').on('click', function() {
                    $('#sidebarResponsive').collapse('hide');
                });
            }

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
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>