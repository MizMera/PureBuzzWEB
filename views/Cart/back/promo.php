<?php

include_once __DIR__ . '/../../../config/database.php';

// Connexion à la base de données via PDO
$conn = Database::getConnexion();
// Récupérer les codes promo de la base de données
$sql = "SELECT * FROM promos";
$stmt = $conn->prepare($sql);
$stmt->execute();
$promos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Stocker les résultats dans $promos

if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $code = $_POST['code'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $valid_from = $_POST['valid_from'];
    $valid_until = $_POST['valid_until'];

    try {
        // Préparer la requête d'insertion avec PDO
        $sql = "INSERT INTO promos (code, discount_type, discount_value, valid_from, valid_until)
                VALUES (:code, :discount_type, :discount_value, :valid_from, :valid_until)";

        // Préparer la requête avec la connexion récupérée
        $stmt = $conn->prepare($sql);

        // Lier les paramètres à la requête préparée
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':discount_type', $discount_type, PDO::PARAM_STR);
        $stmt->bindParam(':discount_value', $discount_value, PDO::PARAM_STR);
        $stmt->bindParam(':valid_from', $valid_from, PDO::PARAM_STR);
        $stmt->bindParam(':valid_until', $valid_until, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Rediriger après l'insertion pour éviter la soumission automatique à chaque rafraîchissement
        header("Location: promo.php");
        exit; // Important d'ajouter un exit après la redirection pour empêcher toute exécution de code supplémentaire
    } catch (PDOException $e) {
        // Afficher l'erreur en cas d'échec
        echo "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Code Promo</title>
    <link rel="stylesheet" href="bck.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
            <!-- plugins:css -->
            <link rel="stylesheet" href="vendors/feather/feather.css">
   <link rel="stylesheet" href="../../../assets/vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../../../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../../../assets/vendors/typicons/typicons.css">
<link rel="stylesheet" href="../../../assets/vendors/simple-line-icons/css/simple-line-icons.css">
<link rel="stylesheet" href="../../../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../../assets/PureBuzzLogo.png" />

    <link rel="stylesheet" href="../../../assets/css/sidebar.css">

    <style>
        .card {
            margin-bottom: 30px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .card-description {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #dde1e3;
            padding: 10px 15px;
            margin-bottom: 1rem;
        }

        .sidebar {
            position: fixed;
            top: 80px; /* Match the navbar height */
            left: 0;
            height: calc(100% - 80px); /* Subtract navbar height */
            z-index: 999; /* Just below navbar */
            background: #f5f5f5;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 260px;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-260px);
        }

        .sidebar .nav {
            height: 100%;
            margin-top: 0; /* Remove margin since we're already positioned below navbar */
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar .nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar .nav::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar .nav::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        .sidebar .nav::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .main-panel {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: 0.3s;
            padding: 20px;
            margin-top: 80px; /* Add margin to account for fixed navbar */
        }

        .main-panel.expanded {
            margin-left: 0;
            width: 100%;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: static;
                height: auto;
                width: 100%;
                transform: none !important;
                top: 0;
            }
            .sidebar .nav {
                height: auto;
                margin-top: 0;
                overflow: visible;
            }
            .main-panel {
                margin-left: 0;
                width: 100%;
                margin-top: 80px;
            }
        }
    </style>
</head>
<body>
        <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="index.html">
                        <img src="PureBuzzLogo.png" style="height: 80px;" alt="logo" />
                    </a>

                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Promo Codes </h1>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown d-none d-lg-block">
                        <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Select Category </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
                            <a class="dropdown-item py-3">
                                <p class="mb-0 font-weight-medium float-left">Select category</p>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">Bootstrap Bundle </p>
                                    <p class="fw-light small-text mb-0">This is a Bundle featuring 16 unique dashboards</p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">Angular Bundle</p>
                                    <p class="fw-light small-text mb-0">Everything you’ll ever need for your Angular projects</p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">VUE Bundle</p>
                                    <p class="fw-light small-text mb-0">Bundle of 6 Premium Vue Admin Dashboard</p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">React Bundle</p>
                                    <p class="fw-light small-text mb-0">Bundle of 8 Premium React Admin Dashboard</p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                            <span class="input-group-addon input-group-prepend border-right">
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>
                            <input type="text" class="form-control">
                        </div>
                    </li>
                    <li class="nav-item">
                        <form class="search-form" action="#">
                            <i class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                        </form>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                            <i class="icon-mail icon-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                            <a class="dropdown-item py-3 border-bottom">
                                <p class="mb-0 font-weight-medium float-left">You have 4 new notifications </p>
                                <span class="badge badge-pill badge-primary float-right">View all</span>
                            </a>
                            <a class="dropdown-item preview-item py-3">
                                <div class="preview-thumbnail">
                                    <i class="mdi mdi-alert m-auto text-primary"></i>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject fw-normal text-dark mb-1">Application Error</h6>
                                    <p class="fw-light small-text mb-0"> Just now </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item py-3">
                                <div class="preview-thumbnail">
                                    <i class="mdi mdi-settings m-auto text-primary"></i>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6>
                                    <p class="fw-light small-text mb-0"> Private message </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item py-3">
                                <div class="preview-thumbnail">
                                    <i class="mdi mdi-airballoon m-auto text-primary"></i>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject fw-normal text-dark mb-1">New user registration</h6>
                                    <p class="fw-light small-text mb-0"> 2 days ago </p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="icon-bell"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown">
                            <a class="dropdown-item py-3">
                                <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
                                <span class="badge badge-pill badge-primary float-right">View all</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face10.jpg" alt="image" class="img-sm profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                                    <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face12.jpg" alt="image" class="img-sm profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                                    <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face1.jpg" alt="image" class="img-sm profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow py-2">
                                    <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
                                    <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-xs rounded-circle" src="images/faces/face8.jpg" alt="Profile image"> </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <img class="img-md rounded-circle" src="images/faces/face8.jpg" alt="Profile image">
                                <p class="mb-1 mt-3 font-weight-semibold">Allen Moreno</p>
                                <p class="fw-light text-muted mb-0">allenmoreno@gmail.com</p>
                            </div>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile <span class="badge badge-pill badge-danger">1</span></a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages</a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->
            <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="ti-settings"></i></div>
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close ti-close"></i>
                    <p class="settings-heading">SIDEBAR SKINS</p>
                    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                        <div class="img-ss rounded-circle bg-light border me-3"></div>Light
                    </div>
                    <div class="sidebar-bg-options" id="sidebar-dark-theme">
                        <div class="img-ss rounded-circle bg-dark border me-3"></div>Dark
                    </div>
                    <p class="settings-heading mt-2">HEADER SKINS</p>
                    <div class="color-tiles mx-0 px-4">
                        <div class="tiles success"></div>
                        <div class="tiles warning"></div>
                        <div class="tiles danger"></div>
                        <div class="tiles info"></div>
                        <div class="tiles dark"></div>
                        <div class="tiles default"></div>
                    </div>
                </div>
            </div>
            <div id="right-sidebar" class="settings-panel">
                <i class="settings-close ti-close"></i>
                <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
                    </li>
                </ul>
                <div class="tab-content" id="setting-content">
                    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
                        <div class="add-items d-flex px-3 mb-0">
                            <form class="form w-100">
                                <div class="form-group d-flex">
                                    <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                                    <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                                </div>
                            </form>
                        </div>
                        <div class="list-wrapper px-3">
                            <ul class="d-flex flex-column-reverse todo-list">
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Team review meeting at 3.00 PM
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Prepare for presentation
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Resolve all the low priority tickets due today
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked>
                                            Schedule meeting for next week
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked>
                                            Project review
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                            </ul>
                        </div>
                        <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary me-2"></i>
                                <span>Feb 11 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
                            <p class="text-gray mb-0">The total number of sessions</p>
                        </div>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary me-2"></i>
                                <span>Feb 7 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                            <p class="text-gray mb-0 ">Call Sarah Graves</p>
                        </div>
                    </div>
                    <!-- To do section tab ends -->
                    <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
                        <div class="d-flex align-items-center justify-content-between border-bottom">
                            <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
                            <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
                        </div>
                        <ul class="chat-list">
                            <li class="list active">
                                <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
                                <div class="info">
                                    <p>Thomas Douglas</p>
                                    <p>Available</p>
                                </div>
                                <small class="text-muted my-auto">19 min</small>
                            </li>
                            <li class="list">
                                <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
                                <div class="info">
                                    <div class="wrapper d-flex">
                                        <p>Catherine</p>
                                    </div>
                                    <p>Away</p>
                                </div>
                                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                                <small class="text-muted my-auto">23 min</small>
                            </li>
                            <li class="list">
                                <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
                                <div class="info">
                                    <p>Daniel Russell</p>
                                    <p>Available</p>
                                </div>
                                <small class="text-muted my-auto">14 min</small>
                            </li>
                            <li class="list">
                                <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
                                <div class="info">
                                    <p>James Richardson</p>
                                    <p>Away</p>
                                </div>
                                <small class="text-muted my-auto">2 min</small>
                            </li>
                            <li class="list">
                                <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
                                <div class="info">
                                    <p>Madeline Kennedy</p>
                                    <p>Available</p>
                                </div>
                                <small class="text-muted my-auto">5 min</small>
                            </li>
                            <li class="list">
                                <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
                                <div class="info">
                                    <p>Sarah Graves</p>
                                    <p>Available</p>
                                </div>
                                <small class="text-muted my-auto">47 min</small>
                            </li>
                        </ul>
                    </div>
                    <!-- chat tab ends -->
                </div>
            </div>
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
          <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item nav-category">Products and Management</li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="../../categories/index.php" aria-expanded="false" aria-controls="tables">
                                <i class="menu-icon mdi mdi-format-list-bulleted"></i>
                                <span class="menu-title">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Products/index.php" aria-expanded="false" aria-controls="charts">
                                <i class="menu-icon mdi mdi-package-variant"></i>
                                <span class="menu-title">Product</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">Support</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../support/Reclamation.html" aria-expanded="false" aria-controls="charts">
                                <i class="menu-icon mdi mdi-message-alert"></i>
                                <span class="menu-title">Claims views</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">User Management</li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="../../user/Back_Office/stat.html" aria-expanded="false" aria-controls="basic">
                                <i class="menu-icon mdi mdi-account-group"></i>
                                <span class="menu-title">User Mangamnets</span>
                            </a>
                            <div class="collapse" id="basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="addUser.html">Add User</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="AllUsers.html">Get All Users</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="stat.html">Dashboard</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../user/Front_office/UserProfile.html">
                                <i class="menu-icon mdi mdi-account-circle"></i>
                                <span class="menu-title">My profile</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">apiaries</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../apiary/backOffice/apiaries.php">
                                <i class="menu-icon mdi mdi-hexagon-multiple"></i>
                                <span class="menu-title">Apiaries</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../apiary/backOffice/harvests.php">
                                <i class="menu-icon mdi mdi-honey-pot"></i>
                                <span class="menu-title">Harvests</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">Cart</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Cart/back/cartm.php">
                                <i class="menu-icon mdi mdi-cart"></i>
                                <span class="menu-title">Cart Management</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Cart/back/promo.php">
                                <i class="menu-icon mdi mdi-tag-multiple"></i>
                                <span class="menu-title">Promos</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">EVENTS</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../events/app/manage_events.php">
                                <i class="menu-icon mdi mdi-calendar"></i>
                                <span class="menu-title">Manage Event</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../events/app/manage_tickets.php">
                                <i class="menu-icon mdi mdi-ticket"></i>
                                <span class="menu-title">Manage Ticket</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">Settings</li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="logoutLink">
                                <i class="menu-icon mdi mdi-logout"></i>
                                <span class="menu-title">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add a Promo Code</h4>
                        <p class="card-description">Enter promo code details below</p>
                        
                        <form class="forms-sample" action="promo.php" method="POST">
                            <div class="form-group">
                                <label for="code">Promo Code</label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="Enter promo code" required>
                            </div>

                            <div class="form-group">
                                <label for="discount_type">Discount Type</label>
                                <select class="form-control" id="discount_type" name="discount_type">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="discount_value">Discount Value</label>
                                <input type="number" class="form-control" id="discount_value" name="discount_value" placeholder="Enter discount value" required>
                            </div>

                            <div class="form-group">
                                <label for="valid_from">Valid From</label>
                                <input type="datetime-local" class="form-control" id="valid_from" name="valid_from" required>
                            </div>

                            <div class="form-group">
                                <label for="valid_until">Valid Until</label>
                                <input type="datetime-local" class="form-control" id="valid_until" name="valid_until" required>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="reset" class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Promo Code List</h4>
                        <p class="card-description">Current active and expired promo codes</p>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Valid From</th>
                                        <th>Valid Until</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($promos)) {
                                        foreach ($promos as $promo) {
                                            $isValid = strtotime($promo['valid_until']) > time();
                                            $statusClass = $isValid ? 'badge badge-success' : 'badge badge-danger';
                                            $statusText = $isValid ? 'Active' : 'Expired';
                                            
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($promo['id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($promo['code']) . "</td>";
                                            echo "<td>" . htmlspecialchars($promo['discount_type']) . "</td>";
                                            echo "<td>" . htmlspecialchars($promo['discount_value']) . 
                                                 ($promo['discount_type'] == 'percentage' ? '%' : '$') . "</td>";
                                            echo "<td>" . htmlspecialchars($promo['valid_from']) . "</td>";
                                            echo "<td>" . htmlspecialchars($promo['valid_until']) . "</td>";
                                            echo "<td><span class='{$statusClass}'>{$statusText}</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No promo codes found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
<script src="../../../assets/js/sidebar.js"></script>
