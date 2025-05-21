<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../../../controllers/TicketController.php';

$ticketController = new TicketController($access);

$message = $messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Add Ticket
        if (isset($_POST['add'])) {
            $eventId = $_POST['event_id'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $numTickets = $_POST['num_tickets'];

            if ($ticketController->addTicket($eventId, $username, $phone, $email, $numTickets)) {
                $message = "Ticket added successfully.";
                $messageType = "success";
            } else {
                $message = "Error adding ticket.";
                $messageType = "error";
            }
        }
        // Update Ticket
        elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $numTickets = $_POST['num_tickets'];

            if ($ticketController->updateTicket($id, $username, $phone, $email, $numTickets)) {
                $message = "Ticket updated successfully.";
                $messageType = "success";
            } else {
                $message = "Error updating ticket.";
                $messageType = "error";
            }
        }
        // Delete Ticket
        elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];

            if ($ticketController->deleteTicket($id)) {
                $message = "Ticket deleted successfully.";
                $messageType = "success";
            } else {
                $message = "Error deleting ticket.";
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

// Fetch all tickets
$tickets = $ticketController->getAllTickets();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tickets</title>

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
</head>
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
<body>
    <div class="container-scroller">
        <!-- Navbar -->
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
                        <h1 class="welcome-text">Ticket <span class="text-black fw-bold">Mangment</span></h1>
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

        <!-- Main Container -->
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
                    <li class="nav-item">
                        <a class="nav-link" href="../../Products/index.php">
                            <i class="menu-icon mdi mdi-cart"></i>
                            <span class="menu-title">Products</span>
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
                            <h2 class="page-title">Ticket Management</h2>
                            <p class="welcome-text">Manage your event tickets here</p>
                        </div>
                        
                        <!-- Add Ticket Card -->
                        <div class="col-12 col-xl-5 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add New Ticket</h4>
                                    <form method="POST">
                                        <div class="form-group">
                                            <label for="event_id">Event ID</label>
                                            <input type="number" class="form-control" name="event_id" id="event_id" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Name</label>
                                            <input type="text" class="form-control" name="username" id="username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="tel" class="form-control" name="phone" id="phone" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="num_tickets">Number of Tickets</label>
                                            <input type="number" class="form-control" name="num_tickets" id="num_tickets" required min="1">
                                        </div>
                                        <button type="submit" name="add" class="btn btn-primary">Add Ticket</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Tickets List Card -->
                        <div class="col-12 col-xl-7 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tickets List</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Event ID</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Tickets</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($tickets)): ?>
                                                    <?php foreach ($tickets as $ticket): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($ticket->getId()); ?></td>
                                                            <td><?= htmlspecialchars($ticket->getEventId()); ?></td>
                                                            <td><?= htmlspecialchars($ticket->getUsername()); ?></td>
                                                            <td><?= htmlspecialchars($ticket->getPhone()); ?></td>
                                                            <td><?= htmlspecialchars($ticket->getEmail()); ?></td>
                                                            <td><?= htmlspecialchars($ticket->getNumTickets()); ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm" onclick="editTicket(<?= $ticket->getId() ?>)">
                                                                    <i class="mdi mdi-pencil"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm" onclick="deleteTicket(<?= $ticket->getId() ?>)">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">No tickets found.</td>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix sidebar height
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

        function editTicket(id) {
            // Implement edit functionality
            console.log('Edit ticket:', id);
        }

        function deleteTicket(id) {
            if (confirm('Are you sure you want to delete this ticket?')) {
                // Submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="id" value="${id}">
                    <input type="hidden" name="delete" value="1">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
<script src="../../../assets/js/sidebar.js"></script>
