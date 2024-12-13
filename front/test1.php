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

        // Ajouter le logo avec une taille réduite (modifiez la taille selon vos besoins)
        $logo_path = 'PureBuzzLogo.png'; // Remplacez par le chemin de votre logo
        $pdf->Image($logo_path, 4, 4, 10); // Position x, y et taille (largeur)

        // Ajouter du texte sous le logo
        $pdf->SetXY(15, 20); // Définir la position du texte sous le logo
        $pdf->SetFont('helvetica', 'B', 16); // Police et taille du texte
        $pdf->Cell(0, 10, 'PureBuzz - Order Invoice', 0, 1, 'C'); // Afficher le texte centré sous le logo
       
        ///////////////////////
        // Ajouter le logo avec une taille réduite (modifiez la taille selon vos besoins)
$logo_path = 'PureBuzzLogo.png'; // Remplacez par le chemin de votre logo

// Obtenir la largeur de la page
$pageWidth = $pdf->GetPageWidth();

// Calculer la position x pour le logo (en le positionnant à l'extrémité droite)
$xPosition = $pageWidth - 14; // 15 est une marge à droite (ajustez si nécessaire)

// Ajouter le logo à l'extrémité droite de la page
$pdf->Image($logo_path, $xPosition, 4, 10); // Position x calculée, position y à 4, taille (largeur) du logo = 10
        // Définir la police pour le reste du contenu
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
