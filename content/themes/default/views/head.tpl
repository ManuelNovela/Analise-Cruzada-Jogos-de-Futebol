<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{$system['titulo']}</title>
    
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="{$system['system_assets']}/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="{$system['system_assets']}/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="{$system['system_assets']}/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{$system['system_assets']}/css/style.css"/> <!-- End layout styles -->
    <link rel="shortcut icon" href="{$system['system_assets']}/images/favicon.ico" />
    
    <link rel="icon" type="image/x-icon" href="{$system['system_assets']}/images/favicon.ico">


    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        @media (min-width: 768px) {
            .no-meio {
                margin-left: 25%;
            }
            .no-meio-qr {
                margin-left: 35%;
            }
        }

        @keyframes spinner {
            0% {
                transform: translate3d(-50%, -50%, 0) rotate(0deg);
            }
            100% {
                transform: translate3d(-50%, -50%, 0) rotate(360deg);
            }
        }
        .spin::before {
            animation: 1.5s linear infinite spinner;
            animation-play-state: inherit;
            border: solid 5px #cfd0d1;
            border-bottom-color: #1c87c9;
            border-radius: 50%;
            content: "";
            height: 200px;
            width: 200px;
            position: absolute;
            /* top: 10%;
             left: 10%;*/
            transform: translate3d(-50%, -50%, 0);
            will-change: transform;
        }
    </style>

</head>
<body>





    <div class="container-fluid page-body-wrapper">
        <!-- partial:/partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-profile">
                    <a href="#" class="nav-link">
                        <div class="profile-image">
                            {if $usuario['foto'] == '' }
                                <img class="img-xs rounded-circle" src="{$system['system_assets']}/images/faces/face1.jpg" alt="profile image">
                            {else}
                                <img class="img-xs rounded-circle" src="{$usuario['foto']}" alt="profile image">
                            {/if}
                            <div class="dot-indicator bg-success"></div>
                        </div>
                        <div class="text-wrapper">
                            <p class="profile-name">{$usuario['nome']}</p>
                            <p class="designation">
                                {if $usuario['user_group'] == 1 }Administrator{/if}
                                {if $usuario['user_group'] == 2 }Cliente{/if}
                            </p>
                        </div>
                       <!-- <div class="icon-container">
                            <i class="icon-bubbles"></i>
                            <div class="dot-indicator bg-danger"></div>
                        </div> -->
                    </a>
                </li>
                <li class="nav-item nav-category">
                    <span class="nav-link">Dashboard</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/inicio">
                        <span class="menu-title">Inicio</span>
                        <i class="icon-screen-desktop menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/text">
                        <span class="menu-title">Chat</span>
                        <i class="icon-camera menu-icon"></i>
                    </a>
                </li>
                




                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/auto">
                        <span class="menu-title">Respostas Automática</span>
                        <i class="icon-rocket menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/suporte">
                        <span class="menu-title">Suporte</span>
                        <i class="icon-call-in menu-icon"></i>
                    </a>
                </li>






                <li class="nav-item nav-category">
                    <span class="nav-link">Grupos</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/trans">
                        <span class="menu-title">Envio em Grupos</span>
                        <i class="icon-magnet menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/lista">
                        <span class="menu-title">Envios Agendados</span>
                        <i class="icon-clock menu-icon"></i>
                    </a>
                </li>

                <!--
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                        <span class="menu-title">Grupos</span>
                        <i class="icon-people menu-icon"></i>
                    </a>
                    <div class="collapse" id="auth">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_url']}/grupos"> Grupos</a></li>
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_url']}/gruposmembros"> Membros dos Grupos </a></li>
                        </ul>
                    </div>
                </li>

                -->


                <li class="nav-item nav-category">
                    <span class="nav-link">Sistema</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/setup_suporte">
                        <span class="menu-title">Config. Suporte</span>
                        <i class="icon-call-out menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_url']}/setup">
                        <span class="menu-title">Definicoes</span>
                        <i class="icon-settings menu-icon"></i>
                    </a>
                </li>

                <!--
                <li class="nav-item nav-category"><span class="nav-link">UI Elements</span></li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                        <span class="menu-title">Basic UI Elements</span>
                        <i class="icon-layers menu-icon"></i>
                    </a>
                    <div class="collapse" id="ui-basic">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/ui-features/buttons.html">Buttons</a></li>
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/ui-features/typography.html">Typography</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_assets']}/pages/icons/simple-line-icons.html">
                        <span class="menu-title">Icons</span>
                        <i class="icon-globe menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_assets']}/pages/forms/basic_elements.html">
                        <span class="menu-title">Form Elements</span>
                        <i class="icon-book-open menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_assets']}/pages/charts/chartist.html">
                        <span class="menu-title">Charts</span>
                        <i class="icon-chart menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{$system['system_assets']}/pages/tables/basic-table.html">
                        <span class="menu-title">Tables</span>
                        <i class="icon-grid menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item nav-category"><span class="nav-link">Sample Pages</span></li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                        <span class="menu-title">General Pages</span>
                        <i class="icon-doc menu-icon"></i>
                    </a>
                    <div class="collapse" id="auth">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/samples/login.html"> Login </a></li>
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/samples/register.html"> Register </a></li>
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/samples/error-404.html"> 404 </a></li>
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/samples/error-500.html"> 500 </a></li>
                            <li class="nav-item"> <a class="nav-link" href="{$system['system_assets']}/pages/samples/blank-page.html"> Blank Page </a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item pro-upgrade">

              <span class="nav-link">
                <a class="btn btn-block px-0 btn-rounded btn-upgrade" href="https://www.bootstrapdash.com/product/stellar-admin-template/" target="_blank"> <i class="icon-badge mx-2"></i> Upgrade to Pro</a>
              </span>
                </li>-->






            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">