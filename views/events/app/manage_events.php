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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff8e1;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #6d4c41;
            margin-bottom: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #ffeb3b;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #8d6e63;
            border-radius: 5px;
            background-color: #fff;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #6d4c41;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            display: none;
        }
        .file-upload {
            display: inline-block;
            background-color: #fbc02d;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 5px;
        }
        .file-upload:hover {
            background-color: #f9a825;
        }
        button {
            background-color: #fbc02d;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        button:hover {
            background-color: #f9a825;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ffe082;
            color: #6d4c41;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container-fluid">
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icxon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="index.html">
              <img src="../../Public/Product-pages/Images/PureBuzzLogo.png"style="height: 80px;" alt="logo" />
            </a>
           
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top"> 
          <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
              <h1 class="welcome-text">Good Morning, <span class="text-black fw-bold">Admin</span></h1>
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

<nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
            <li class="nav-item nav-category">Products and Management</li>
                        <li class="nav-item">
                        <a class="nav-link" href="../../categories/index.php" aria-expanded="false" aria-controls="charts">
                                <i class="mdi mdi-grid-large menu-icon"></i>
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
                        <li class="nav-item nav-category">EVENTS</li>
                    <li class="nav-item">
                            <a class="nav-link" href="../../events/app/manage_events.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Mangae Event</span>
                            </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="../../events/app/manage_tickets.php">
                                <i class="mdi mdi-grid-large menu-icon"></i>
                                <span class="menu-title">Manage Ticket</span>
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
         <div class="container-fluid">
    <div class="container">
        <h1>Beez & Honey Event Management</h1>

        <?php if ($message): ?>
            <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="container-scroller">

        <!-- Form to Add Event -->
        <form method="POST" enctype="multipart/form-data">
            <h2>Add Event</h2>
            <label for="name">Name:</label>
            <input id="name" type="text" name="name" required>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="num_tickets">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets" required min="1">
            <label for="file-upload" class="file-upload">Choose File</label>
            <input id="file-upload" type="file" name="image" accept="image/*" required>
            <button type="submit" name="add">Add Event</button>
        </form>

        <!-- Form to Update Event -->
        <form method="POST">
            <h2>Update Event</h2>
            <label for="id">Event ID:</label>
            <input type="number" name="id" id="id" required>
            <label for="name2">Name:</label>
            <input type="text" name="name" id="name2" required>
            <label for="location2">Location:</label>
            <input type="text" id="location2" name="location" required>
            <label for="description2">Description:</label>
            <textarea name="description" id="description2" required></textarea>
            <label for="num_tickets2">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets2" required min="1">
            <button type="submit" name="update">Update Event</button>
        </form>

        <!-- Form to Delete Event -->
        <form method="POST">
            <h2>Delete Event</h2>
            <label for="delete_id">Event ID:</label>
            <input type="number" name="id" id="delete_id" required>
            <button type="submit" name="delete">Delete Event</button>
        </form>

        <!-- Display All Events -->
        <h2>All Events</h2>
        <?php if (!empty($events)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Number of Tickets</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event->getId()); ?></td>
                            <td><?php echo htmlspecialchars($event->getName()); ?></td>
                            <td><?php echo htmlspecialchars($event->getLocation()); ?></td>
                            <td><?php echo htmlspecialchars($event->getDescription()); ?></td>
                            <td><?php echo htmlspecialchars($event->getNumTickets()); ?></td>
                            <td><img src="<?php echo htmlspecialchars($event->getImage()); ?>" alt="Event Image" style="width:100px;"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

