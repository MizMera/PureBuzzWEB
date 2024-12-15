// Initialisation du panier
let cart = JSON.parse(localStorage.getItem('cart')) || []; // Récupérer le panier ou initialiser un tableau vide

// Fonction pour afficher les produits du panier
function displayCart() {
    const cartTableBody = document.querySelector("#cart-table tbody");
    cartTableBody.innerHTML = ""; // Vider le contenu avant de recharger

    if (cart.length === 0) {
        document.getElementById("empty-cart-message").style.display = "block";
    } else {
        document.getElementById("empty-cart-message").style.display = "none";
        cart.forEach((product, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <br>${product.name}
                </td>
                <td class="product-price">${product.price} TND</td>
                <td>
                    <button onclick="changeQuantity(${index}, -1)">-</button>
                    <input type="number" value="${product.quantity}" min="1" class="quantity-input" onchange="updateQuantity(${index}, this.value)">
                    <button onclick="changeQuantity(${index}, 1)">+</button>
                </td>
                <td class="product-subtotal">${(product.price * product.quantity).toFixed(3)} TND</td>
                <td>
                    <button onclick="removeProduct(${index})">✕</button>
                </td>
            `;
            cartTableBody.appendChild(row);
        });
    }
    updateSummary();
}

// Fonction pour ajouter un produit au panier
function addProduct(product) {
    const existingProduct = cart.find(item => item.name === product.name);
    if (existingProduct) {
        existingProduct.quantity += product.quantity;
    } else {
        cart.push(product);
    }
    saveCart();
    displayCart();
}

// Fonction pour supprimer un produit
function removeProduct(index) {
    cart.splice(index, 1);
    saveCart();
    displayCart();
}

// Fonction pour changer la quantité
function changeQuantity(index, change) {
    cart[index].quantity = Math.max(1, cart[index].quantity + change); // Empêcher la quantité de devenir < 1
    saveCart();
    displayCart();
}

// Fonction pour mettre à jour la quantité à partir du champ d'entrée
function updateQuantity(index, value) {
    cart[index].quantity = Math.max(1, parseInt(value, 10)); // Empêcher les quantités invalides
    saveCart();
    displayCart();
}

// Fonction pour mettre à jour le résumé du panier
function updateSummary() {
    const subtotal = cart.reduce((sum, product) => sum + product.price * product.quantity, 0);
    const shipping = subtotal > 0 ? 4.500 : 0; // Exemple : frais de port fixe si le panier n'est pas vide
    const total = subtotal + shipping;

    document.getElementById("subtotal").textContent = `${subtotal.toFixed(3)} TND`;
    document.getElementById("shipping-cost").textContent = `${shipping.toFixed(3)} TND`;
    document.getElementById("grand-total").textContent = `${total.toFixed(3)} TND`;
}

// Fonction pour sauvegarder le panier dans Local Storage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Fonction pour vider le panier
function clearCart() {
    cart = [];
    saveCart();
    displayCart();
}

// Exemple : Ajouter un produit (Appelée depuis une autre page ou un bouton)
function addToCartFromProductPage() {
    const product = {
        name: "Lip Balm",
        price: 50.000,
        quantity: 1,
        image: "baume.png"
    };
    addProduct(product);
}

// Fonction pour rediriger et sauvegarder le panier
function redirectToShopping() {
    saveCart(); // Sauvegarde du panier
    window.location.href = "index.html"; // Redirection vers la page principale ou la boutique
}

// Initialisation : afficher le panier au chargement de la page
document.addEventListener("DOMContentLoaded", () => {
    displayCart(); // Afficher le panier

    // Gestionnaire d'événement pour "Continuer vos achats"
    const continueShoppingButton = document.getElementById("continue-shopping");
    if (continueShoppingButton) {
        continueShoppingButton.addEventListener("click", redirectToShopping);
    }

    // Gestionnaire d'événement pour le logo de la navbar
    const navbarBrand = document.querySelector(".navbar-brand");
    if (navbarBrand) {
        navbarBrand.addEventListener("click", redirectToShopping);
    }
});

// Initialisation du panier de démonstration
localStorage.setItem("cart", JSON.stringify([
    { id: "1", name: "Lip Balm", price: 50, quantity: 1, image: "images/baume.png" },
    { id: "2", name: "Honey Jar", price: 20, quantity: 2, image: "images/honey.png" }
]));
