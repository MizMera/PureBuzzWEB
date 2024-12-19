<?php
// Inclure la bibliothèque TCPDF
require_once 'C:\xampp\htdocs\PureBuzzWEB\views\Cart\front\TCPDF-main\tcpdf.php';

// Connexion à la base de données (assurez-vous que la connexion PDO est correcte)
include_once __DIR__ . '/../../../config/database.php';
// Connexion à la base de données via PDO
$pdo = Database::getConnexion();

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
        $stmt_items = $pdo->prepare("SELECT p.name, ci.quantity, p.price AS unitprice 
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
        $logo_path = '../PureBuzzLogo.png'; // Remplacez par le chemin de votre logo
        $pdf->Image($logo_path, 4, 4, 10); // Position x, y et taille (largeur)
        $pdf->Image($logo_path, 197, 4, 10); // Position x, y et taille (largeur)

        // Ajouter du texte sous le logo
        $pdf->SetXY(15, 20); // Définir la position du texte sous le logo
        $pdf->SetFont('helvetica', 'B', 16); // Police et taille du texte
        $pdf->Cell(0, 10, 'PureBuzz - Order Invoice', 0, 1, 'C'); // Afficher le texte centré sous le logo

        // Définir la police pour le reste du contenu
        $pdf->SetFont('helvetica', '', 12);

        // Contenu HTML à insérer dans le PDF
        $html = '<h1 style="text-align: center;">Your Order Details</h1>';

        // Informations générales de la commande
        $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
        $html .= '<tr><td><strong>Order Number:</strong></td><td>' . htmlspecialchars($order['cart_id']) . '</td></tr>';
        $html .= '<tr><td><strong>Name:</strong></td><td>' . htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) . '</td></tr>';
        $html .= '<tr><td><strong>Address:</strong></td><td>' . htmlspecialchars($order['address'] . ', ' . $order['city'] . ', ' . $order['country']) . '</td></tr>';
        $html .= '<tr><td><strong>Phone Number:</strong></td><td>' . htmlspecialchars($order['phone']) . '</td></tr>';
        $html .= '</table>';
        
        // Liste des produits
        $html .= '<h2>Products:</h2>';
        $html .= '<table style="width: 100%; border-collapse: collapse; border: 1px solid black;">';
        $html .= '<tr style="background-color: #f6b92b; text-align: left;">
                    <th style="padding: 8px; border: 1px solid black;">Product Name</th>
                    <th style="padding: 8px; border: 1px solid black;">Quantity</th>
                    <th style="padding: 8px; border: 1px solid black;">Unit Price (TND)</th>
                    <th style="padding: 8px; border: 1px solid black;">Total (TND)</th>
                  </tr>';
        
        foreach ($cartItems as $item) {
            $html .= '<tr>
                        <td style="padding: 8px; border: 1px solid black;">' . htmlspecialchars($item['name']) . '</td>
                        <td style="padding: 8px; border: 1px solid black; text-align: center;">' . intval($item['quantity']) . '</td>
                        <td style="padding: 8px; border: 1px solid black; text-align: right;">' . number_format($item['unitprice'], 2) . '</td>
                        <td style="padding: 8px; border: 1px solid black; text-align: right;">' . number_format($item['unitprice'] * $item['quantity'], 2) . '</td>
                      </tr>';
        }
        
        $html .= '</table>';
        
        // Total général et frais de livraison
        $html .= '<h2 style="text-align: right; margin-top: 20px;">Total (+4.500): <strong>' . number_format($total + 4.500, 3) . ' TND</strong></h2>';
        
        // Estimation de livraison
        $html .= '<h2>Delivery Estimate:</h2>';
        $html .= '<p>Your order will be delivered between <strong>' . date('d/m/Y', strtotime('+1 day')) . '</strong> and <strong>' 
                 . date('d/m/Y', strtotime('+3 days')) . '</strong>.</p>';
        
        // Ajouter le contenu HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Récupérer le PDF sous forme de chaîne pour l'envoyer par e-mail
        $pdfContent = $pdf->Output('Order_Invoice_' . $order_id . '.pdf', 'S'); // 'S' pour obtenir le PDF sous forme de chaîne

        // Afficher le PDF dans le navigateur
        $pdf->Output('Order_Invoice_' . $order_id . '.pdf', 'I'); // 'I' pour afficher dans le navigateur
    }
}
?>
