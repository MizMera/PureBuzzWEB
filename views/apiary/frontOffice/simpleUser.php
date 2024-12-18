<?php

include_once "../../../config/database.php";
include_once "../../../Controllers/apiariesC.php";
include_once "../../../Controllers/harvestsC.php";


$harvestC = new HarvestC();
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


$search1 = isset($_GET['search1']) ? $_GET['search1'] : '';
$sort1 = isset($_GET['sort1']) ? $_GET['sort1'] : 'ASC'; // Default sorting order

// Pagination settings
$limit1 = 4; // Items per page
$page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1; // Current page
$offset1 = ($page1 - 1) * $limit1;
// Fetch the total count of harvests for search
$totalHarvests = $harvestC->countHarvestsWithSearch($search1);
$totalPages = ceil($totalHarvests / $limit1);

// Fetch the filtered and sorted list of harvests
$listHarvests = $harvestC->fetchFilteredSortedHarvests($search1, $sort1, $limit1, $offset1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product-style.css">
    <style>
   /* General Reset */
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

/* Card */
.card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Table */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #fff; /* White background for the table */
}

.custom-table th, .custom-table td {
    text-align: left;
    padding: 12px 15px;
    border: 1px solid #ddd;
}

.custom-table th {
    background-color: #ffc107; /* Yellow background for the table header */
    color: white;
    text-transform: uppercase;
    font-size: 14px;
}

.custom-table tbody tr:nth-child(even) {
    background-color: #f9f9f9; /* Light gray background for even rows */
}

.custom-table tbody tr:nth-child(odd) {
    background-color: #fff; /* White background for odd rows */
}

.custom-table tbody tr:hover {
    background-color: #ffe082; /* Lighter yellow on hover */
    transition: background-color 0.3s;
}

/* Search and Sort Bar */
.search-sort-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-input, .sort-select, .btn-submit {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    font-size: 14px;
}

.search-input {
    flex: 1;
    margin-right: 10px;
}

.sort-select {
    width: 200px;
    margin-right: 10px;
}

.btn-submit {
    background-color: #ffc107; /* Yellow */
    color: black;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #e0a800; /* Darker yellow on hover */
}

/* Actions Buttons */
a.signin {
    text-decoration: none;
    padding: 5px 10px;
    margin-right: 5px;
    border-radius: 5px;
    background-color: #ffc107; /* Yellow */
    color: black;
    font-weight: bold;
    font-size: 12px;
    text-align: center;
    transition: background-color 0.3s ease;
}

a.signin:hover {
    background-color: #e0a800; /* Darker yellow on hover */
}

/* Pagination */
.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination-link {
    text-decoration: none;
    padding: 5px 10px;
    margin: 0 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    color: black;
}

.pagination-link.active {
    background-color: #ffc107;
    color: white;
}

.pagination-link:hover {
    background-color: #e0a800;
}


    </style>
</head>
<body>

   


   
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List of Apiaries</h4>
                <!-- Search and Sort -->
                <form method="GET" class="search-sort-bar">
                    <input type="text" name="search" class="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                    <select name="sort" class="sort-select">
                        <option value="ASC" <?php echo $sort == 'ASC' ? 'selected' : ''; ?>>Date Ascending</option>
                        <option value="DESC" <?php echo $sort == 'DESC' ? 'selected' : ''; ?>>Date Descending</option>
                    </select>
                    <button type="submit" class="btn-submit">Search</button>
                </form>
                <div class="table-wrapper">
                    <table class="custom-table">
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
                                            <a href='map.php?cor=" . $apiary['coordinates'] . "' class='signin'>Show in map</a>
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
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="pagination-link">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="pagination-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="pagination-link">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    </section>
        <!-- About Us Section -->
        <section id="about" class="info-section">
        <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List of Harvests</h4>
                <!-- Search and Sort -->
                <form method="GET" class="search-sort-bar">
                    <input type="text" name="search1" class="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search1); ?>">
                    <select name="sort1" class="sort-select">
                        <option value="ASC" <?php echo $sort1 == 'ASC' ? 'selected' : ''; ?>>Date Ascending</option>
                        <option value="DESC" <?php echo $sort1 == 'DESC' ? 'selected' : ''; ?>>Date Descending</option>
                    </select>
                    <button type="submit" class="btn-submit">Search</button>
                </form>
                <div class="table-wrapper">
                <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Quantity</th>
                                <th>Quality</th>
                                <th>Apiary Name</th>
       
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listHarvests)) {
                                foreach ($listHarvests as $index => $harvest) {
                                    echo "<tr>
                                        <td>" . ($offset + $index + 1) . "</td>
                                        <td>" . htmlspecialchars($harvest['date']) . "</td>
                                        <td>" . htmlspecialchars($harvest['location']) . "</td>
                                        <td>" . htmlspecialchars($harvest['quantity']) . "</td>
                                        <td>" . htmlspecialchars($harvest['quality']) . "</td>
                                        <td>" . htmlspecialchars($harvest['apiaryName']) . "</td>
                                      
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>No harvests found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
               <!-- Pagination -->
               <div class="pagination">
                    <?php if ($page1 > 1): ?>
                        <a href="?page1=<?php echo $page1 - 1; ?>&search1=<?php echo urlencode($search1); ?>&sort1=<?php echo $sort1; ?>" class="pagination-link">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page1=<?php echo $i; ?>&search1=<?php echo urlencode($search1); ?>&sort1=<?php echo $sort1; ?>" class="pagination-link <?php echo $i == $page1 ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page1 < $totalPages): ?>
                        <a href="?page1=<?php echo $page1 + 1; ?>&search1=<?php echo urlencode($search1); ?>&sort1=<?php echo $sort1; ?>" class="pagination-link">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    </section>

    
</body>
</html>
