<?php
require_once __DIR__ . 'C:\xampp\htdocs\project1\model/cart.php';

class CartController
{
    // Add a product to the cart
    public function addToCart($userId, $produitId, $quantite, $prixUnitaire)
    {
        $cart = new Cart($userId);
        $cart->ajouterProduit($produitId, $quantite, $prixUnitaire);
        return ['status' => 'success', 'message' => 'Produit ajouté au panier avec succès.'];
    }

    // Update product quantity in the cart
    public function updateProductQuantity($userId, $articleId, $quantite)
    {
        $cart = new Cart($userId);
        $cart->mettreAJourProduit($articleId, $quantite);
        return ['status' => 'success', 'message' => 'Quantité mise à jour avec succès.'];
    }

    // Remove a product from the cart
    public function removeProduct($userId, $articleId)
    {
        $cart = new Cart($userId);
        $cart->supprimerProduit($articleId);
        return ['status' => 'success', 'message' => 'Produit supprimé du panier avec succès.'];
    }

    // Get the total cost of the cart
    public function getCartTotal($userId)
    {
        $cart = new Cart($userId);
        $total = $cart->calculerTotal();
        return ['status' => 'success', 'total' => $total];
    }

    // Get all products in the cart
    public function getCartProducts($userId)
    {
        $cart = new Cart($userId);
        $products = $cart->getProduits();
        return ['status' => 'success', 'products' => $products];
    }

    // Handle AJAX requests (optional helper method)
    public function handleRequest($request)
    {
        $action = $request['action'];
        $userId = $request['userId'];

        switch ($action) {
            case 'add':
                $produitId = $request['produitId'];
                $quantite = $request['quantite'];
                $prixUnitaire = $request['prixUnitaire'];
                return $this->addToCart($userId, $produitId, $quantite, $prixUnitaire);

            case 'update':
                $articleId = $request['articleId'];
                $quantite = $request['quantite'];
                return $this->updateProductQuantity($userId, $articleId, $quantite);

            case 'remove':
                $articleId = $request['articleId'];
                return $this->removeProduct($userId, $articleId);

            case 'getTotal':
                return $this->getCartTotal($userId);

            case 'getProducts':
                return $this->getCartProducts($userId);

            default:
                return ['status' => 'error', 'message' => 'Action invalide.'];
        }
    }
}


