<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stock Adjustment</title>
</head>
<body>
    <h1>Edit Stock Adjustment</h1>
    <form method="POST" action="index.php?controller=Stock&action=update&id=<?= $stock['id'] ?>">
        <label>Product ID:</label>
        <input type="number" name="product_id" value="<?= $stock['product_id'] ?>" required>
        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= $stock['quantity'] ?>" required>
        <label>Date:</label>
        <input type="date" name="date" value="<?= $stock['date'] ?>" required>
        <label>Remarks:</label>
        <textarea name="remarks"><?= $stock['remarks'] ?></textarea>
        <button type="submit">Update</button>
    </form>
</body>
</html>
