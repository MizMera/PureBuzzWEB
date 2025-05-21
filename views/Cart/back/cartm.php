<?php
session_start();
include_once __DIR__ . '/../../../config/database.php';

// Connexion à la base de données via PDO
$conn  = Database::getConnexion();


$filterStatus = $_GET['status'] ?? '';
$sqlCarts = "SELECT id, total, status FROM cart";
if ($filterStatus) {
    $sqlCarts .= " WHERE status = :status";
    $stmtCarts = $conn->prepare($sqlCarts);
    $stmtCarts->execute([':status' => $filterStatus]);
} else {
    $stmtCarts = $conn->prepare($sqlCarts);
    $stmtCarts->execute();
}
$carts = $stmtCarts->fetchAll(PDO::FETCH_ASSOC);

// Updating the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['new_status'])) {
    $cartId = $_POST['cart_id'];
    $newStatus = $_POST['new_status'];

    $updateSql = "UPDATE cart SET status = :new_status WHERE id = :cart_id";
    $updateStmt = $conn->prepare($updateSql);

    try {
        $updateStmt->execute([':new_status' => $newStatus, ':cart_id' => $cartId]);
        header("Location: cartm.php?status=" . $filterStatus);
        exit;
    } catch (PDOException $e) {
        $error = "Error updating status: " . $e->getMessage();
    }
}

function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Pending':
            return 'warning';
        case 'In Progress':
            return 'primary';
        case 'Ready for Delivery':
            return 'info';
        case 'Delivered':
            return 'success';
        default:
            return 'secondary';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Management</title>
    <link rel="stylesheet" href="catm.css?v=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/typicons/typicons.css">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    <link rel="stylesheet" href="../Products/style.css">
    <link rel="stylesheet" href="../Products/style2.css">
    <link rel="shortcut icon" href="../../../assets/PureBuzzLogo.png" />
    <link rel="stylesheet" href="../../../assets/css/sidebar.css">

</head>
<style>
    .card {
        margin-bottom: 30px;
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .card-title {
        color: #ff5722; /* Orange color matching theme */
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .card-description {
        color:rgb(0, 0, 0);
        margin-bottom: 1.5rem;
    }

    .table {
        margin-top: 1rem;
    }

    .table thead th {
        background-color: #fff5f2; /* Light orange background */
        border-bottom: 2px solid #ff5722;
        color: #ff5722;
        font-weight: 600;
        padding: 12px;
    }

    .table tbody tr:hover {
        background-color: #fff5f2;
        cursor: pointer;
    }

    .badge {
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-success {
        background-color: #ff5722;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }

    .btn-primary {
        background-color: #ff5722;
        border: none;
        margin-right: 5px;
    }

    .btn-primary:hover {
        background-color: #f4511e;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .table td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .card {
            margin: 10px;
        }
        
        .table td, .table th {
            padding: 8px;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
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
                        <h1 class="welcome-text">Cart Management </h1>
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
                                    <h4 class="card-title">Cart Management</h4>
                                    <p class="card-description">Manage and track all customer carts</p>

                                    <form method="GET" action="" class="form-filter mb-4">
                                        <label for="filter">Filter by Status:</label>
                                        <select name="status" id="filter" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                                            <option value="">All</option>
                                            <option value="Pending" <?= $filterStatus == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="In Progress" <?= $filterStatus == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option value="Ready for Delivery" <?= $filterStatus == 'Ready for Delivery' ? 'selected' : '' ?>>Ready for Delivery</option>
                                            <option value="Delivered" <?= $filterStatus == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>
                                    </form>

                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cart ID</th>
                                                    <th>Total (TND)</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($carts)): ?>
                                                    <?php foreach ($carts as $cart): ?>
                                                        <tr>
                                                            <td><?= $cart['id']; ?></td>
                                                            <td><?= number_format($cart['total'], 2); ?> TND</td>
                                                            <td>
                                                                <span class="badge badge-<?= getStatusBadgeClass($cart['status']) ?>">
                                                                    <?= htmlspecialchars($cart['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <form method="POST" action="" class="form-change-status d-inline">
                                                                    <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
                                                                    <select name="new_status" class="form-control form-control-sm d-inline-block" style="width: auto;" onchange="this.form.submit()">
                                                                        <option value="Pending" <?= $cart['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                                        <option value="In Progress" <?= $cart['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                                                        <option value="Ready for Delivery" <?= $cart['status'] == 'Ready for Delivery' ? 'selected' : '' ?>>Ready for Delivery</option>
                                                                        <option value="Delivered" <?= $cart['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                                                    </select>
                                                                </form>

                                                                <a href="details.php?id=<?= $cart['id']; ?>" class="btn btn-info btn-sm ml-2">
                                                                    <i class="mdi mdi-eye"></i> View
                                                                </a>
                                                                <a href="delete_cart.php?id=<?= $cart['id']; ?>" 
                                                                   onclick="return confirm('Are you sure you want to delete this cart?');" 
                                                                   class="btn btn-danger btn-sm ml-2">
                                                                    <i class="mdi mdi-delete"></i> Delete
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No carts found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger mt-3">
                                            <?= $error; ?>
                                        </div>
                                    <?php endif; ?>
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
