<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock Adjustment</title>
</head>
<body>
    <h1>Add New Stock Adjustment</h1>
    <form method="POST" action="index.php?controller=Stock&action=store">
        <label>Product ID:</label>
        <input type="number" name="product_id" required>
        <label>Quantity:</label>
        <input type="number" name="quantity" required>
        <label>Date:</label>
        <input type="date" name="date" required>
        <label>Remarks:</label>
        <textarea name="remarks"></textarea>
        <button type="submit">Save</button>
    </form>
</body>
</html>
