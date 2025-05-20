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
    <link rel="stylesheet" href="../../Public/css/common.css">
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/typicons/typicons.css">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="js/select.dataTables.min.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="../../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="shortcut icon" href="../../../assets/PureBuzzLogo.png" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
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
            background: #f5f1f5;
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
            background: #f5f1f5;

            
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
              <img src="../../Public/Product-pages/Images/PureBuzzLogo.png"style="height: 80px;" alt="logo" />
            </a>
           
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top "> 
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
       <!-- --------------------------------------nav-bar-set ne touche pas  -------------------------------------------------------------- -->
    <div class="container-fluid"> 
        <?php include_once '../../Public/sidebar.php'; ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <!-- Form Card -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Product</h4>
                                <form id="productForm" method="POST" action="" enctype="multipart/form-data" class="forms-sample">
                                    <div class="form-group">
                                        <label for="name">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                                        <small class="text-danger" id="nameError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="float" class="form-control" id="price" name="price" step="0.01" required>
                                        <small class="text-danger" id="priceError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" class="form-control" id="stock" name="stock" required>
                                        <small class="text-danger" id="stockError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                        <small class="text-danger" id="descriptionError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Upload Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                        <small class="text-danger" id="imageError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select class="form-control" id="category" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-danger" id="categoryError"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-2">Save Product</button>
                                    <button type="reset" class="btn btn-light">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table Card -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Product List</h4>
                                <form method="GET" action="index.php" class="search-form mb-3">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search products name.." 
                                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                                        <button type="submit" class="btn btn-primary" style="padding: 0.375rem 1rem;">Search</button>
                                    </div>
                                </form>
                                        
                                <?php if ($products): ?>
                                <div class="table-responsive">
                                    <table class="table" style="width: 100%; table-layout: fixed;">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%"><a href="?controller=Product&action=index&sort=id&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">ID</a></th>
                                                <th style="width: 10%"><a href="?controller=Product&action=index&sort=name&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">Name</a></th>
                                                <th style="width: 8%"><a href="?controller=Product&action=index&sort=price&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">Price</a></th>
                                                <th style="width: 8%"><a href="?controller=Product&action=index&sort=stock&direction=<?= $sortDirection === 'ASC' ? 'DESC' : 'ASC' ?>">Stock</a></th>
                                                <th style="width: 15%">Description</th>
                                                <th style="width: 12%">Image</th>
                                                <th style="width: 10%">Created At</th>
                                                <th style="width: 10%">Updated At</th>
                                                <th style="width: 10%">Category</th>
                                                <th style="width: 12%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td class="text-truncate"><?= $product['id'] ?></td>
                                                    <td class="text-truncate"><?= htmlspecialchars($product['name']) ?></td>
                                                    <td class="text-truncate"><?= $product['price'] ?></td>
                                                    <td class="text-truncate"><?= $product['stock'] ?></td>
                                                    <td class="text-truncate"><?= $product['description'] ?></td>
                                                    <td>
                                                    <?php 
                                                        $image_path = $product['image_url'];
                                                        if (!empty($image_path)) {
                                                            $image_url = "/Project_web/PureBuzzWEB/uploads/" . basename($image_path);
                                                            echo "<img src='$image_url' alt='Product Image' style='width: 80px; height: 80px; object-fit: cover; border-radius: 5px;'>";
                                                        } else {
                                                            echo "<img src='/Project_web/PureBuzzWEB/Public/Product-pages/Images/no-image.png' alt='No Image Available' style='width: 80px; height: 80px; object-fit: cover; border-radius: 5px;'>";
                                                        }
                                                    ?>
                                                    </td>
                                                    <td class="text-truncate"><?= $product['created_at'] ?></td>
                                                    <td class="text-truncate"><?= $product['updated_at'] ?></td>
                                                    <td class="text-truncate"><?= htmlspecialchars($product['category_id']) ?></td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a class="btn btn-primary btn-sm" href="edit.php?id=<?= $product['id'] ?>">Edit</a>
                                                            <a class="btn btn-danger btn-sm" href="index.php?controller=Product&action=delete&id=<?= $product['id'] ?>" 
                                                               onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <p>No products available. <a href="create.php">Add one now</a>.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Chart Card -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Product Stock Chart</h4>
                                <div class="chart-container" style="position: relative; height:400px;">
                                    <canvas id="stockChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Product Stock Levels'
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

        // Add scroll handling for sidebar
        let lastScrollTop = 0;
        const sidebar = document.querySelector('.sidebar');
        const mainPanel = document.querySelector('.main-panel');
        const scrollThreshold = 100; // Minimum scroll before sidebar starts hiding

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            if (currentScroll > scrollThreshold) {
                if (currentScroll > lastScrollTop) {
                    // Scrolling down
                    sidebar.classList.add('hidden');
                    mainPanel.classList.add('expanded');
                } else {
                    // Scrolling up
                    sidebar.classList.remove('hidden');
                    mainPanel.classList.remove('expanded');
                }
            } else {
                // At the top of the page
                sidebar.classList.remove('hidden');
                mainPanel.classList.remove('expanded');
            }
            
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });

        // Add hover behavior
        sidebar.addEventListener('mouseenter', () => {
            sidebar.classList.remove('hidden');
            mainPanel.classList.remove('expanded');
        });

        // Optional: Hide sidebar when mouse leaves
        sidebar.addEventListener('mouseleave', () => {
            if (window.pageYOffset > scrollThreshold) {
                sidebar.classList.add('hidden');
                mainPanel.classList.add('expanded');
            }
        });
    </script>
</body>
</html>