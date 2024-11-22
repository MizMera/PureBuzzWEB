<?php 
// Use require_once to avoid multiple inclusions of the controller
require_once __DIR__ . '/../../controllers/stockController.php';
$StockController = new StockController();
$stocks = $StockController->index(); // Call index() method to get all stocks
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Adjustments</title>
    <link rel="stylesheet" href="/path/to/your/styles.css"> <!-- Update path to your CSS file -->
    
    <style>
        /* Reset some default styles */
        body, h1, table {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Button styling */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* Table styling */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        td {
            color: #34495e;
        }

        tr:hover {
            background-color: #ecf0f1;
        }

        /* Action Links */
        a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }

        a:hover {
            color: #2980b9;
        }

        /* Center the table if the content is too narrow */
        .table-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* No data row */
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <h1>Stock Adjustments</h1>
    <a href="index.php?controller=Stock&action=create" class="btn">Add New Adjustment</a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stocks) && is_array($stocks)): ?>
                    <?php foreach ($stocks as $stock): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stock['id']); ?></td>
                            <td><?php echo htmlspecialchars($stock['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($stock['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($stock['date']); ?></td>
                            <td><?php echo htmlspecialchars($stock['remarks']); ?></td>
                            <td>
                                <a href="index.php?controller=Stock&action=edit&id=<?php echo htmlspecialchars($stock['id']); ?>">Edit</a> | 
                                <a href="index.php?controller=Stock&action=delete&id=<?php echo htmlspecialchars($stock['id']); ?>" 
                                   onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">No stock adjustments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
