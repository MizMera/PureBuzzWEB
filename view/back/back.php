<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Administration</title>
    <link rel="stylesheet" href="bck.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
</head>
<body>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="#dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="#carts" onclick="showSection('carts')">Cart Management</a></li>
        </ul>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Dashboard Section -->
        <div id="dashboard" class="section">
            <h2>Dashboard</h2>
            <div class="statistics">
                <div class="stat-box">
                    <h3>Total Carts</h3>
                    <p>120</p>
                </div>
                <div class="stat-box">
                    <h3>Total Users</h3>
                    <p>85</p>
                </div>
                <div class="stat-box">
                    <h3>Total Sales</h3>
                    <p>€3,500</p>
                </div>
            </div>
        </div>

        <!-- Cart Management Section -->
        <div id="carts" class="section">
            <h2>Cart Management</h2>
            <p>List of user carts</p>

            <table>
                <thead>
                    <tr>
                        <th>Cart ID</th>
                        <th>User ID</th>
                        <th>Creation Date</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example of a cart -->
                    <tr>
                        <td>1</td>
                        <td>1001</td>
                        <td>2024-11-19</td>
                        <td>€150.00</td>
                        <td class="actions">
                            <button onclick="viewCartDetails(1)">View Details</button>
                            <button onclick="deleteCart(1)">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>1002</td>
                        <td>2024-11-18</td>
                        <td>€200.00</td>
                        <td class="actions">
                            <button onclick="viewCartDetails(2)">View Details</button>
                            <button onclick="deleteCart(2)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Cart Details -->
    <div id="cartDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Cart Details</h2>
            <div id="cartDetails">
                <!-- Cart details will be dynamically added here -->
            </div>
        </div>
    </div>

    <script src="back.js"></script>

</body>
</html>
