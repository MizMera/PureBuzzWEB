<?php 
require_once __DIR__ . '/../../controllers/categories_controller.php';

$categorie_Controller = new categorie_Controller();
$categories = $categorie_Controller->getCategories();

if (isset($_GET['controller']) && $_GET['controller'] === 'categorie' && isset($_GET['action'])) {
    $categorie_Controller = new categorie_Controller();

    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $categorie_Controller->delete((int)$_GET['id']);
    }
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categorie_Controller = new categorie_Controller();
    $categorie_Controller->store(); // Call the controller's store method to handle the submission
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories List</title>
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
            background: #f5f5f5;
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
            background: #f5f5f5;
        }

        .sidebar .nav::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        .sidebar .nav::-webkit-scrollbar-thumb:hover {
           
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
                    <h3 class="welcome-sub-text">Categories Management</h3>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
    <?php include_once '../../Public/sidebar.php'; ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <!-- Form Card -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add New Category</h4>
                                <form id="categoryForm" method="POST" action="" class="forms-sample">
                                    <div class="form-group">
                                        <label for="name">Category Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Enter category name" required>
                                        <small class="text-danger" id="nameError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" 
                                                  rows="4" placeholder="Enter description"></textarea>
                                        <small class="text-danger" id="descriptionError"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-2">Save Category</button>
                                    <button type="reset" class="btn btn-light">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Categories List Card -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Categories List</h4>
                                <form method="GET" action="index.php" class="search-form mb-3">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Search categories..." 
                                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                                
                                <?php if ($categories): ?>
                                <div class="table-responsive">
                                    <table class="table" style="width: 100%; table-layout: fixed;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">ID</th>
                                                <th style="width: 30%">Name</th>
                                                <th style="width: 40%">Description</th>
                                                <th style="width: 20%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $category): ?>
                                                <tr>
                                                    <td class="text-truncate"><?= $category['id'] ?></td>
                                                    <td class="text-truncate"><?= htmlspecialchars($category['name']) ?></td>
                                                    <td class="text-truncate"><?= htmlspecialchars($category['description']) ?></td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a class="btn btn-primary btn-sm" 
                                                               href="edit.php?id=<?= $category['id'] ?>">Edit</a>
                                                            <a class="btn btn-danger btn-sm" 
                                                               href="index.php?controller=categorie&action=delete&id=<?= $category['id'] ?>" 
                                                               onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <p>No categories available.</p>
                                <?php endif; ?>
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
