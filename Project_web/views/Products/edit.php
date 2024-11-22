<?php
// Include necessary files
include_once __DIR__ . '/../../controllers/ProductController.php';

// Check if product ID is provided via the URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product data from the database
    $productController = new ProductController();
    $product = $productController->getProductById($productId);

    if ($product === null) {
        echo "Product not found.";
        exit;
    }

    // Handle form submission to update product
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input data
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $image_url = $_POST['image_url'];
        $category = $_POST['category'];

        // Call method to update product
        $productController->updateProduct($productId, $name, $price, $stock, $description, $image_url, $category);
        header("Location: index.php"); // Redirect to product list after update
        exit;
    }
} else {
    echo "Product ID is missing.";
    exit;
}


//for delete   
if (isset($_GET['controller']) && $_GET['controller'] === 'Product' && isset($_GET['action'])) {
    $productController = new ProductController();

    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $productController->delete((int)$_GET['id']);
    }
}
// Handle form submission // for creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductController();
    $controller->store(); // Call the controller's store method to handle the submission
}

// Instantiate the controller and fetch products // for index
$productController = new ProductController();
$products = $productController->getAllProducts();


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
    <style>
       body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="index.html">
              <img src="PureBuzzLogo.png"style="height: 80px;" alt="logo" />
            </a>
           
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top"> 
          <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
              <h1 class="welcome-text">Good Morning, <span class="text-black fw-bold">Si khalil</span></h1>
              <h3 class="welcome-sub-text">Your performance summary this week </h3>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-none d-lg-block">
              <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Select Category </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
                <a class="dropdown-item py-3" >
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
       <!-- --------------------------------------nav-bar-set ne touche pas  -------------------------------------------------------------- -->
    <div class="container-fluid page-body-wrapper"> 

   
        <!-- ...---------------------------------------------------- (Sidebar code)---------------------- ... -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" href="main-menu.html">
                  <i class="mdi mdi-grid-large menu-icon"></i>
                  <span class="menu-title">Main Menu</span>
                </a>
              </li>

             <li class="nav-item nav-category">Products and Stocks</li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                  <i class="menu-icon mdi mdi-card-text-outline"></i>
                  <span class="menu-title">Form elements</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="form-elements">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                  <i class="menu-icon mdi mdi-chart-line"></i>
                  <span class="menu-title">Charts</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="charts">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                  <i class="menu-icon mdi mdi-table"></i>
                  <span class="menu-title">Tables</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="tables">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                  <i class="menu-icon mdi mdi-layers-outline"></i>
                  <span class="menu-title">Icons</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="icons">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
                  </ul>
                </div>
              </li>
              <li class="nav-item nav-category">pages</li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                  <i class="menu-icon mdi mdi-account-circle-outline"></i>
                  <span class="menu-title">User Pages</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="auth">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                  </ul>
                </div>
              </li>
              <li class="nav-item nav-category">help</li>
              <li class="nav-item">
                <a class="nav-link" href="http://bootstrapdash.com/demo/star-admin2-free/docs/documentation.html">
                  <i class="menu-icon mdi mdi-file-document"></i>
                  <span class="menu-title">Documentation</span>
                </a>
              </li>
            </ul>
        </nav>
        

    <div class="form-card" >
    <h2>Edit Product</h2>
        <form action="" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            <span id="nameError" class="error"></span>
         </div>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            <span id="priceError" class="error"></span>        
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>            
            <span id="stockError" class="error"></span>
        </div>
         <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            <span id="descriptionError" class="error"></span>
 
        </div>
         <div class="form-group">
            <label for="image_url">Image URL:</label>
            <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
            <span id="imageError" class="error"></span>

        </div>
        <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option>Honey</option>
                        <option>Medicines</option>
                        <option>Cosmetics</option>
                        <option>Beehives</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Save edited Product</button>
        </form>
        <script src="formValidation.js"></script>
    </div>


    <div class="table-card">
              
              <h2>Product Table</h2>
              <!--<a href="create.php" style="background: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Add New Product</a>-->

<?php if ($products): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Description</th>
                <th>Image URL</th>
                <th>Categroy</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>

        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= $product['price'] ?></td>
                    <td><?= $product['stock'] ?></td>
                    <td><?= $product['description'] ?></td>
                    <td><?= htmlspecialchars($product['image_url']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td><?= $product['created_at'] ?></td>
                    <td><?= $product['updated_at'] ?></td>
        <td>
          <div class="action-buttons">
              <a class="btn-edit" href="index.php?id=<?= $product['id'] ?>">Add</a>  
          </div>
          </td>
          <td>
          <div class="action-buttons">
              <a class="btn-delete" href="index.php?controller=Product&action=delete&id=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
          </div>
          </td>
        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            </div>
        </div>
        
      <?php else: ?>
          <p>No products available. <a href="create.php">Add one now</a>.</p>
      <?php endif; ?>
                  </div>
              </div>
          </div>
      </div>
          
</body>
</html>
