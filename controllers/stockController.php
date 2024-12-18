<?php



include __DIR__ . '/../Models/Stock.php';
include __DIR__ . '/../config/database.php';

class StockController
{
    private $stockModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnexion();
        $this->stockModel = new Stock($db);
    }

    public function index()
    {
        $stocks = $this->stockModel->getAll();
    
    // Debugging line to see if data is being fetched correctly
   
    return $stocks; // Make sure you're returning the data, not just rendering the view
    }

    // Display all stock adjustments
    /*public function index()
    {
        $stocks = $this->stockModel->getAll();
        include __DIR__ . '/../views/stocks/index.php';
    }*/

    // Show form to create a stock adjustment
    public function create()
    {
        include __DIR__ . '/../views/stocks/create.php';
    }

    public function store()
{
    $stock = new Stock($this->db);
    $stock->setProductId($_POST['product_id'])
          ->setQuantity($_POST['quantity'])
          ->setDate(date('Y-m-d H:i:s'))
          ->setRemarks($_POST['remarks']);
    $stock->create();
    header('Location: index.php?controller=Stock&action=index');
}

    // Show form to edit an existing stock adjustment
    public function edit($id)
    {
        $stock = $this->stockModel->getById($id);
        include __DIR__ . '/../views/stocks/edit.php';
    }

    // Handle updating an existing stock adjustment
    public function update($id)
{
    $stock = new Stock($this->db);
    $stock->setProductId($_POST['product_id'])
          ->setQuantity($_POST['quantity'])
          ->setDate(date('Y-m-d H:i:s'))
          ->setRemarks($_POST['remarks']);
    $stock->update($id);
    header('Location: index.php?controller=Stock&action=index');
}


    // Handle deleting a stock adjustment
    public function delete($id)
    {
        $this->stockModel->delete($id);
        header('Location: index.php?controller=Stock&action=index');
        exit;
    }

    // Validate form data
    private function validateData($data)
    {
        return isset($data['product_id'], $data['quantity']) && is_numeric($data['quantity']) && $data['quantity'] > 0;
    }
}


?>
