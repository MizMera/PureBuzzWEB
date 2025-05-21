<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../../../controllers/EventController.php';

$eventController = new EventController($access);

$message = $messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Add Event
        if (isset($_POST['add'])) {
            $name = $_POST['name'];
            $location = $_POST['location'];
            $description = $_POST['description'];
            $numTickets = $_POST['num_tickets'];

            $uploadDir = 'C:\xampp\htdocs\dashboard\karamvc\public';
            $imageFile = basename($_FILES['image']['name']);
            $imagePath = $uploadDir . $imageFile;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                if ($eventController->addEvent($name, $location, $description, $numTickets, $imagePath)) {
                    $message = "Event added successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error adding event.";
                    $messageType = "error";
                }
            } else {
                $message = "Error uploading image.";
                $messageType = "error";
            }
        }

        // Update Event
        elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $location = $_POST['location'];
            $description = $_POST['description'];
            $numTickets = $_POST['num_tickets'];

            if ($eventController->updateEvent($id, $name, $location, $description, $numTickets)) {
                $message = "Event updated successfully.";
                $messageType = "success";
            } else {
                $message = "Error updating event.";
                $messageType = "error";
            }
        }

        // Delete Event
        elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            if ($eventController->deleteEvent($id)) {
                $message = "Event deleted successfully.";
                $messageType = "success";
            } else {
                $message = "Error deleting event.";
                $messageType = "error";
            }
        }
    } catch (PDOException $e) {
        $message = "Database Error: " . htmlspecialchars($e->getMessage());
        $messageType = "error";
    }

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
}

// Fetch all events
$events = $eventController->getAllEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beez & Honey Event Management</title>
    
    <link rel="stylesheet" href="../../../assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../../../assets/js/select.dataTables.min.css">
    <link rel="stylesheet" href="../../../assets/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="../../../assets/css/Back_office/AllUsers.css">
    <link rel="shortcut icon" href="../../../assets/PureBuzzLogo.png" />
        <link rel="stylesheet" href="../../../assets/css/sidebar.css">

    <style>
        .main-panel {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: 0.3s;
            padding: 20px;
            padding-top: 80px; /* Changed from margin-top */
            background: #f5f5f5;
        }

        .content-wrapper {
            padding: 20px;
            width: 100%;
            margin-top: 0;
            background: #f5f5f5;
        }

        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }

        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            height: calc(100vh - 80px);
            width: 260px;
            z-index: 1020;
        }

        .container-scroller {
            position: relative;
            overflow: hidden;
        }

        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                        <span class="icxon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="index.html">
                        <img src="../../../assets/PureBuzzLogo.png" style="height: 80px;" alt="logo" />
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top"> 
                <ul class="navbar-nav">
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Mangment, <span class="text-black fw-bold">Events</span></h1>
                        <h3 class="welcome-sub-text">Your performance summary this week </h3>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
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

          <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">                 
                    <li class="nav-item nav-category">Products Management</li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../categories/index.php">
                            <i class="mdi mdi-grid-large menu-icon"></i>
                            <span class="menu-title">Categories</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="../../Products/index.php">
                            <i class="menu-icon mdi mdi-cart"></i>
                            <span class=" menu-icon menu-title">Products</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">User Management</li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../user/Back_Office/stat.html">
                            <i class="menu-icon mdi mdi-account-group"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../user/Front_office/UserProfile.html">
                            <i class="menu-icon mdi mdi-account-circle"></i>
                            <span class="menu-title">My Profile</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Apiaries</li>
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
                            <i class="menu-icon mdi mdi-cart-outline"></i>
                            <span class="menu-title">Cart Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../Cart/back/promo.php">
                            <i class="menu-icon mdi mdi-tag-multiple"></i>
                            <span class="menu-title">Promos</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Events</li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_events.php">
                            <i class="menu-icon mdi mdi-calendar"></i>
                            <span class="menu-title">Manage Events</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_tickets.php">
                            <i class="menu-icon mdi mdi-ticket"></i>
                            <span class="menu-title">Manage Tickets</span>
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

            <!-- Main Panel -->
        

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12">
                        <h2 class="page-title">Event Management</h2>
                        <p class="welcome-text">Manage your events and tickets here</p>
                    </div>
                    
                    <!-- Add Event Card -->
                    <div class="col-12 col-xl-5 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add New Event</h4>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="name">Event Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="num_tickets">Number of Tickets</label>
                                        <input type="number" class="form-control" id="num_tickets" name="num_tickets" required min="1">
                                    </div>
                                    <div class="form-group">
                                        <label for="file-upload" class="custom-file-upload">
                                            <i class="mdi mdi-upload"></i> Choose Event Image
                                        </label>
                                        <input id="file-upload" type="file" name="image" accept="image/*" required style="display: none;">
                                    </div>
                                    <button type="submit" name="add" class="btn btn-primary">Add Event</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Events List Card -->
                    <div class="col-12 col-xl-7 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Events List</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Description</th>
                                                <th>Tickets</th>
                                                <th>Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($events)): ?>
                                                <?php foreach ($events as $event): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($event->getId()); ?></td>
                                                        <td><?= htmlspecialchars($event->getName()); ?></td>
                                                        <td><?= htmlspecialchars($event->getLocation()); ?></td>
                                                        <td><?= htmlspecialchars($event->getDescription()); ?></td>
                                                        <td><?= htmlspecialchars($event->getNumTickets()); ?></td>
                                                        <td>
                                                            <img src="<?= htmlspecialchars($event->getImage()); ?>" 
                                                                 alt="Event Image" 
                                                                 style="width:50px; height:50px; object-fit:cover; border-radius:5px;">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" onclick="editEvent(<?= $event->getId() ?>)">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteEvent(<?= $event->getId() ?>)">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No events found.</td>
                                                </tr>
                                            <?php endif; ?>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix sidebar height
            const sidebar = document.querySelector('.sidebar');
            const mainPanel = document.querySelector('.main-panel');
            
            function adjustLayout() {
                const windowHeight = window.innerHeight;
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                sidebar.style.height = `${windowHeight - navbarHeight}px`;
                sidebar.style.top = `${navbarHeight}px`;
                mainPanel.style.marginTop = `${navbarHeight}px`;
            }

            adjustLayout();
            window.addEventListener('resize', adjustLayout);
        });
    </script>
</body>
</html>

<script src="../../../assets/js/sidebar.js"></script>

