<?php

include_once "../../../config/database.php";
include_once "../../../Controllers/apiariesC.php";


$apiaryC = new ApiaryC();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC'; // Default sorting order

// Pagination settings
$limit = 4; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit;

// Fetch the total count of apiaries for search
$totalApiaries = $apiaryC->countApiariesWithSearch($search);
$totalPages = ceil($totalApiaries / $limit);

// Fetch the filtered and sorted list of apiaries
$listApiaries = $apiaryC->fetchFilteredSortedApiaries($search, $sort, $limit, $offset);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_apiary'])) {
    $apiaryName = $_POST['apiaryName'];
    $beekeeper = $_POST['beekeeper'];
    $location = $_POST['location'];
    $coordinates = $_POST['coordinates'];
    $date = $_POST['date'];
    $weather = $_POST['weather'];
    $hiveCount = $_POST['hiveCount'];
    $observation = $_POST['observation'];

    // Create the Apiary object
    $apiary = new Apiary(null, $apiaryName, $beekeeper, $location, $coordinates, $date, $weather, $hiveCount, $observation);

    // Add the Apiary to the database
    $apiaryC->ajouterApiary($apiary);

    // Redirect to the apiaries list page or show a success message
    header("Location: apiaries.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Star Admin2 </title>
    <!-- plugins:css -->
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
                        <h1 class="welcome-text">welcome </h1>
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
                            <a class="nav-link" data-bs-toggle="collapse" href="../../categories/index.php"
                                aria-expanded="false" aria-controls="tables">
                                    <i class="menu-icon mdi mdi-table"></i>
                                  <span class="menu-title">Categories</span>
                            </a>
                            <div class="collapse" id="tables">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Products/index.php" aria-expanded="false" aria-controls="charts">
                                <i class="menu-icon mdi mdi-chart-line"></i>
                                <span class="menu-title">Product</span>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
            <li class="nav-item nav-category">Support</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../support/Reclamation.html" aria-expanded="false"
                                aria-controls="charts">
                                <i class="menu-icon mdi mdi-chart-line"></i>
                                <span class="menu-title">Claims views</span>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                                    </li>
                                </ul>
                            </div>
                        </li>


             <li class="nav-item nav-category">User Managment</li>
                        <li class="nav-item"> 
                             <a class="nav-link" href="../../user/Back_Office/stat.html">
                                <i class="menu-icon mdi mdi-table"></i>
                                <span class="menu-title">User Mangamnets</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <li class="nav-item">
                            <a class="nav-link" href="../../user/Front_office/UserProfile.html">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">My profile</span>
                            </a>
                        </li>
                            <div class="collapse" id="basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="addUser.html">Add User</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="AllUsers.html">Get All Users</a>
                                    </li>
                                    <li class="nav-item"> <a class="nav-link" href="stat.html">Dashboard</a></li>

                                </ul>
                            </div>
                        </li>
             <li class="nav-item nav-category"> apiaries</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../apiary/backOffice/apiaries.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Apiaries</span>
                            </a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../apiary/backOffice/harvests.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Harvests</span>
                            </a>
                        </li>
          <li class="nav-item nav-category">Cart</li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Cart/back/cartm.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Cart Management</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../Cart/back/promo.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Promos</span>
                            </a>
                        </li>

                        <li class="nav-item nav-category">Settings</li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="logoutLink">
                                <i class="menu-icon mdi mdi-file-document"></i>
                                <span class="menu-title">Log Out</span>
                            </a>
                        </li>


                    </ul>
                </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="home-tab">
                                
                                <div class="tab-content tab-content-basic">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                        <div class="row">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Add Apiary</h4>
                                                        <form action="apiaries.php" method="POST" onsubmit="return validateForm()">
                                                            <div class="form-group">
                                                                <label for="apiaryName">Apiary Name</label>
                                                                <input type="text" class="form-control" id="apiaryName" name="apiaryName" placeholder="Enter Apiary Name">
                                                                <small class="text-danger" id="apiaryNameError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="beekeeper">Beekeeper Email</label>
                                                                <input type="email" class="form-control" id="beekeeper" name="beekeeper" placeholder="Enter Beekeeper Email">
                                                                <small class="text-danger" id="beekeeperError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="location">Location</label>
                                                                <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location">
                                                                <small class="text-danger" id="locationError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="coordinates">Coordinates</label>
                                                                <input type="text" class="form-control" id="coordinates" name="coordinates" placeholder="Enter Coordinates">
                                                                <small class="text-danger" id="coordinatesError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="date">Establishment Date</label>
                                                                <input type="date" class="form-control" id="date" name="date">
                                                                <small class="text-danger" id="dateError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="weather">Weather Condition</label>
                                                                <input type="text" class="form-control" id="weather" name="weather" placeholder="Enter Weather Condition">
                                                                <small class="text-danger" id="weatherError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="hiveCount">Hive Count</label>
                                                                <input type="number" class="form-control" id="hiveCount" name="hiveCount" placeholder="Enter Number of Hives">
                                                                <small class="text-danger" id="hiveCountError"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="observation">Observation</label>
                                                                <textarea class="form-control" id="observation" name="observation" placeholder="Enter Observations" rows="4"></textarea>
                                                                <small class="text-danger" id="observationError"></small>
                                                            </div>
                                                            <button type="submit" name="add_apiary" class="btn btn-primary">Add Apiary</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List of Apiaries</h4>
                <!-- Search and Sort -->
                <form method="GET" class="d-flex mb-3">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                    <select name="sort" class="form-control me-2">
                        <option value="ASC" <?php echo $sort == 'ASC' ? 'selected' : ''; ?>>Date Ascending</option>
                        <option value="DESC" <?php echo $sort == 'DESC' ? 'selected' : ''; ?>>Date Descending</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <div class="table-responsive pt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Apiary Name</th>
                                <th>Beekeeper</th>
                                <th>Location</th>
                                <th>Coordinates</th>
                                <th>Date</th>
                                <th>Weather</th>
                                <th>Hive Count</th>
                                <th>Observation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listApiaries)) {
                                foreach ($listApiaries as $index => $apiary) {
                                    echo "<tr>
                                        <td>" . ($offset + $index + 1) . "</td>
                                        <td>" . htmlspecialchars($apiary['apiaryName']) . "</td>
                                        <td>" . htmlspecialchars($apiary['beekeeper']) . "</td>
                                        <td>" . htmlspecialchars($apiary['location']) . "</td>
                                        <td>" . htmlspecialchars($apiary['coordinates']) . "</td>
                                        <td>" . htmlspecialchars($apiary['date']) . "</td>
                                        <td>" . htmlspecialchars($apiary['weather']) . "</td>
                                        <td>" . htmlspecialchars($apiary['hiveCount']) . "</td>
                                        <td>" . htmlspecialchars($apiary['observation']) . "</td>
                                        <td>
                                            <a href='modify_apiary.php?id=" . $apiary['idApiary'] . "' class='btn btn-warning btn-sm'>Modify</a>
                                            <a href='delete_apiary.php?id=" . $apiary['idApiary'] . "' onclick='return confirm(\"Are you sure you want to delete this apiary?\")' class='btn btn-danger btn-sm'>Delete</a>
                                            <a href='add_harvest.php?id=" . $apiary['idApiary'] . "' class='btn btn-success btn-sm'>Add Harvest</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center'>No apiaries found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>">Previous</a></li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2021. All rights reserved.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendors/progressbar.js/progressbar.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->
    <script>
    function validateForm() {
        let isValid = true;

        document.querySelectorAll('small.text-danger').forEach(error => error.textContent = '');

        const apiaryName = document.getElementById('apiaryName').value.trim();
        const beekeeper = document.getElementById('beekeeper').value.trim();
        const location = document.getElementById('location').value.trim();
        const coordinates = document.getElementById('coordinates').value.trim();
        const date = document.getElementById('date').value.trim();
        const weather = document.getElementById('weather').value.trim();
        const hiveCount = document.getElementById('hiveCount').value.trim();
        const observation = document.getElementById('observation').value.trim();

        // Validation rules
        if (apiaryName === '') {
            document.getElementById('apiaryNameError').textContent = "Apiary Name is required.";
            isValid = false;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
        if (beekeeper === '') {
            document.getElementById('beekeeperError').textContent = 'Beekeeper email is required.';
            isValid = false;
        } 
        if (!emailRegex.test(beekeeper)) {
            document.getElementById('beekeeperError').textContent = 'Invalid email format.';
            isValid = false;
        }

        if (location === '') {
            document.getElementById('locationError').textContent = "Location is required.";
            isValid = false;
        }

        if (coordinates === '') {
            document.getElementById('coordinatesError').textContent = "Coordinates are required.";
            isValid = false;
        } else if (!/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/.test(coordinates)) {
            document.getElementById('coordinatesError').textContent = "Coordinates must be in the format: latitude, longitude (e.g., 36.7783, -119.4179).";
            isValid = false;
        }

        if (date === '') {
            document.getElementById('dateError').textContent = "Establishment Date is required.";
            isValid = false;
        }

        if (weather === '') {
            document.getElementById('weatherError').textContent = "Weather condition is required.";
            isValid = false;
        }

        if (hiveCount === '' || isNaN(hiveCount) || hiveCount <= 0) {
            document.getElementById('hiveCountError').textContent = "Hive Count must be a positive number.";
            isValid = false;
        }

        if (observation.length > 255) {
            document.getElementById('observationError').textContent = "Observation cannot exceed 255 characters.";
            isValid = false;
        }

        return isValid; 
    }
</script>
</body>

</html>
