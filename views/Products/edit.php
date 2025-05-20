<?php
// Include necessary files
include_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/categories_controller.php';

// Initialize the product controller
$productController = new ProductController();

// Handle the form submission and other actions
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product data from the database
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
        $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : '';
        $category_id = $_POST['category_id'];
    
        // Call method to update product
        $productController->updateProduct($productId, $name, $price, $stock, $description, $image_url, $category_id);
        header("Location: index.php"); // Redirect to product list after update
        exit;
    }
} else {
    echo "Product ID is missing.";
    exit;
}

// Instantiate category controller and fetch categories
$categorie_Controller = new categorie_Controller();
$categories = $categorie_Controller->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/typicons/typicons.css">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="js/select.dataTables.min.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../../../assets/PureBuzzLogo.png" />

    <style>
        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            height: calc(100% - 80px);
            z-index: 999;
            background: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 260px;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-260px);
        }

        .sidebar .nav {
            height: 100%;
            margin-top: 0;
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
            margin-top: 80px;
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
    <!-- Navbar -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <div class="me-3">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo" href="index.html">
                    <img src="../../Public/Product-pages/Images/PureBuzzLogo.png" style="height: 80px;" alt="logo" />
                </a>
            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
            <ul class="navbar-nav">
                <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                    <h1 class="welcome-text">Good Morning, <span class="text-black fw-bold">Admin</span></h1>
                    <h3 class="welcome-sub-text">Edit Product</h3>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
        <?php include_once '../../Public/sidebar.php'; ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Product</h4>
                                <form method="POST" action="" enctype="multipart/form-data" class="forms-sample">
                                    <div class="form-group">
                                        <label for="name">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" class="form-control" id="stock" name="stock" 
                                               value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" 
                                                  rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Current Image</label>
                                        <?php if (!empty($product['image_url'])): ?>
                                            <img src="/Project_web/PureBuzzWEB/uploads/<?php echo basename($product['image_url']); ?>" 
                                                 alt="Current Product Image" style="width: 100px; height: 100px; object-fit: cover; margin: 10px 0;">
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <small class="text-muted">Leave empty to keep current image</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select class="form-control" id="category" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" 
                                                    <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-2">Update Product</button>
                                    <a href="index.php" class="btn btn-light">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add scroll handling for sidebar
        let lastScrollTop = 0;
        const sidebar = document.querySelector('.sidebar');
        const mainPanel = document.querySelector('.main-panel');
        const scrollThreshold = 100;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            if (currentScroll > scrollThreshold) {
                if (currentScroll > lastScrollTop) {
                    sidebar.classList.add('hidden');
                    mainPanel.classList.add('expanded');
                } else {
                    sidebar.classList.remove('hidden');
                    mainPanel.classList.remove('expanded');
                }
            } else {
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

        sidebar.addEventListener('mouseleave', () => {
            if (window.pageYOffset > scrollThreshold) {
                sidebar.classList.add('hidden');
                mainPanel.classList.add('expanded');
            }
        });
    </script>
</body>
</html>
