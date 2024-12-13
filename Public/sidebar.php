<!-- sidebar.php -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Product and categories management -->
        <li class="nav-item nav-category">Products and Management</li>
        <li class="nav-item">
            <a class="nav-link" href="index.php" aria-expanded="false" aria-controls="form-elements">
                <span class="menu-title">PRODUCT AND CATEGORY</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../index.php? " aria-expanded="false" aria-controls="charts">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">PRODUCT</span>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="menu-icon mdi mdi-table"></i>
                <span class="menu-title">CATEGORIES</span>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
