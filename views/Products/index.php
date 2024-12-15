<?php

include __DIR__ . '/../../controllers/ProductController.php';
include __DIR__ . '/../../controllers/categories_controller.php';

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
$categorie_Controller = new categorie_Controller();
$categories = $categorie_Controller->getCategories(); 



$products = [];

if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $products = $productController->search($searchTerm);
} else {
    $products = $productController->getAllProducts(); // Default list
}

// Example route in index.php
if (isset($_GET['action']) && $_GET['action'] === 'sortByNumber') {
    $sortColumn = $_GET['column'] ?? 'price';
    $sortDirection = $_GET['direction'] ?? 'ASC';
    $productController->sortByNumber($sortColumn, $sortDirection);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../Products/style.css">
    <link rel="stylesheet" href="../Products/style2.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

         <!-- --------------------------------------nav-bar-set ne touche pas  -------------------------------------------------------------- -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icxon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="index.html">
              <img src="/Project_web/Public/Product-pages/Images/PureBuzzLogo.png"style="height: 80px;" alt="logo" />
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

   
        <!-- ...----------------------------- ----------------------- (Sidebar code)---------------------- ... -->
        <?php include '../../Public/sidebar.php'; ?>
             
        <div class="container-fluid">
      

            <!-- Form Section -->
            <div class="form-card" >
                <h2>Add Product</h2>
            <form id="productForm" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter product name" required>
                    <span id="nameError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="float" id="price" name="price" step="0.01" required>
                    <span id="priceError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" required>
                    <span id="stockError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                    <span id="descriptionError" class="error"></span>
                </div>
                <div class="form-group">
                  <label for="image">Upload Image</label>
                  <input type="file" id="image" name="image" accept="image/*" required>
                  <span id="imageError" class="error"></span>
              </div>
              <div class="form-group">
                  <label for="category">Category:</label>
                  <select id="category" name="category_id" required>
                      <option value="">Select Category</option>
                      <?php foreach ($categories as $category): ?>
                          <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
                      <button type="submit" class="btn-primary">Save Product</button>
                
            </form>
               <script src="formValidationproduct.js"></script>
            </div>
    
            <!-- Table Section -->
            <div class="table-card">
              
                <h2>Product Table</h2>
             
            <form method="GET" action="index.php" class="Search-form">
              <lable for="search"> Search </lable>
            <input type="text" name="search" placeholder="Search products name.." 
                  value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
            <button type="submit">Search</button>
            </form>
                        
            <?php if ($products): ?>
      <table>
          <thead>
              <tr>
              <th><a href="?controller=Product&action=index&sort=id&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">ID</a></th>
              <th><a href="?controller=Product&action=index&sort=name&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">Name</a></th>
              <th><a href="?controller=Product&action=index&sort=price&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">Price</a></th>
              <th><a href="?controller=Product&action=index&sort=stock&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">Stock</a></th>
              <th>Description</th>
              <th>Image URL</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th>Categroy</th>
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
                     <td>
                     <?php 
                        $image_path = $product['image_url']; // Path stored in the database, e.g., "/uploads/product_67479637987a65.90291195.png"
                        $base_url = "/Project_web"; // Base URL of your project
                        
                        // Check if the image path is not empty
                        if (!empty($image_path)) {
                            // Combine base URL with the image path
                            echo "<img src='$base_url$image_path' alt='Product Image' style='width: 100px; height: auto;'>";
                        } else {
                            echo "No Image Available";
                        }
                    ?>
                </td>
                      <td><?= $product['created_at'] ?></td>
                      <td><?= $product['updated_at'] ?></td>
                      <td><?= htmlspecialchars($product['category_id']) ?></td>
          <td>
          <div class="action-buttons">
              <a class="btn-edit" href="edit.php?id=<?= $product['id'] ?>">Edit</a>  
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
        <?php else: ?>
            <p>No products available. <a href="create.php">Add one now</a>.</p>
        <?php endif; ?>
       
    <div class="chart-card ">
    <h2>Product Stock Chart</h2>
    <canvas id="stockChart" class="form-card" ></canvas>
    
</div>
            </div>
   
        </div>
                    <!-- Chart Section -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Extract data from PHP
    const productNames = <?php echo json_encode(array_column($products, 'name')); ?>;
    const stockQuantities = <?php echo json_encode(array_column($products, 'stock')); ?>;

    // Chart.js setup
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: productNames, 
            datasets: [{
                label: 'Stock Quantity',
                data: stockQuantities, 
                backgroundColor: 'rgba(226, 109, 30, 0.5)',
                borderColor: 'rgba(251, 170, 47, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Products'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Stock Quantity'
                    }
                }
            }
        }
    });
</script>
                </div>
                
            </div>
            
        </div>
            
    </body>
</html>