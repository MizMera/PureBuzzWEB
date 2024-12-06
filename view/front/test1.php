<?php
// Inclure la bibliothèque TCPDF
require_once 'C:/xampp/htdocs/project1modif/view/front/TCPDF-main/tcpdf.php';

// Connexion à la base de données (assurez-vous que la connexion PDO est correcte)
include_once 'config.php';
$pdo = Config::getConnexion();

// Vérifier si l'ID de la commande est présent dans l'URL
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']); // Sécuriser l'entrée

    // Récupérer les détails de la commande
    $stmt_order = $pdo->prepare("SELECT o.*, c.total 
                                 FROM orders o 
                                 JOIN cart c ON o.cart_id = c.id 
                                 WHERE o.id = ?");
    $stmt_order->execute([$order_id]);
    $order = $stmt_order->fetch();

    if ($order) {
        // Récupérer les articles du panier
        $stmt_items = $pdo->prepare("SELECT p.name, ci.quantity, p.price 
                                     FROM cartitem ci 
                                     JOIN products p ON ci.productid = p.id 
                                     WHERE ci.cartid = ?");
        $stmt_items->execute([$order['cart_id']]);
        $cartItems = $stmt_items->fetchAll();

        // Calculer le total (au cas où)
        $total = $order['total'];
        
        // Initialiser TCPDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('PureBuzz');
        $pdf->SetTitle('Order Invoice');
        $pdf->SetSubject('Order Details');
        $pdf->SetKeywords('TCPDF, order, PDF, details');

        // Définir les marges
        $pdf->SetMargins(15, 27, 15); // Marge gauche, marge haute, marge droite
        $pdf->SetAutoPageBreak(TRUE, 25); // Activer le saut de page automatique

        // Ajouter une page
        $pdf->AddPage();

        // Définir la police
        $pdf->SetFont('helvetica', '', 12);

        // Contenu HTML à insérer dans le PDF
        $html = '<h1>Your Order Details</h1>';
        $html .= '<p><strong>Order Number:</strong> ' . htmlspecialchars($order['cart_id']) . '</p>';
        $html .= '<p><strong>Name:</strong> ' . htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) . '</p>';
        $html .= '<p><strong>Address:</strong> ' . htmlspecialchars($order['address'] . ', ' . $order['city'] . ', ' . $order['country']) . '</p>';
        $html .= '<p><strong>Phone Number:</strong> ' . htmlspecialchars($order['phone']) . '</p>';
        
        $html .= '<h2>Products:</h2><ul>';
        foreach ($cartItems as $item) {
            $html .= '<li>' . htmlspecialchars($item['name']) . ' × ' . intval($item['quantity']) . ' - ' 
                     . number_format($item['price'] * $item['quantity'], 2) . ' TND</li>';
        }
        $html .= '</ul>';

        // Ajouter le total et la livraison
        $html .= '<h2>Total:(+4.500)</h2>';
        $html .= '<p><strong>' . number_format($total + 4.500, 3) . ' TND</strong></p>';

        // Estimation de la livraison
        $html .= '<h2>Delivery Estimate:</h2>';
        $html .= '<p>Your order will be delivered between ' . date('d/m/Y', strtotime('+1 day')) . ' and ' 
                 . date('d/m/Y', strtotime('+3 days')) . '.</p>';

        // Ajouter le contenu HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Désactiver toute sortie avant d'envoyer le PDF
        ob_end_clean(); // Nettoyer le tampon de sortie

        // Générer le PDF et l'envoyer au navigateur
        $pdf->Output('Order_Invoice_' . $order_id . '.pdf', 'I'); // 'I' pour afficher dans le navigateur
        exit; // Terminer le script après l'envoi du PDF
    } else {
        // Si la commande n'est pas trouvée
        header("HTTP/1.1 404 Not Found");
        echo "Order not found.";
        exit;
    }
} else {
    // Si l'ID de la commande est manquant
    header("HTTP/1.1 400 Bad Request");
    echo "Error: Missing order ID.";
    exit;
}
?>
