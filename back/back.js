// Function to show the active section
function showSection(sectionId) {
    // Hide all sections
    var sections = document.querySelectorAll('.section');
    sections.forEach(function (section) {
        section.classList.remove('active');
    });

    // Show the selected section
    var activeSection = document.getElementById(sectionId);
    activeSection.classList.add('active');
}

// Show the dashboard by default
showSection('dashboard');

// Simulate viewing cart details with product list
function viewCartDetails(cartId) {
    const modal = document.getElementById('cartDetailsModal');
    const cartDetails = document.getElementById('cartDetails');

    // Example data for products in the cart (can be dynamic based on cartId)
    const products = [
        { name: "Product 1", price: 30, quantity: 2 },
        { name: "Product 2", price: 50, quantity: 1 },
        { name: "Product 3", price: 70, quantity: 2 }
    ];

    // Build product details HTML
    let productDetailsHTML = `
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
    `;

    // Loop through the products and create a table row for each
    let totalCartPrice = 0;
    products.forEach(product => {
        let totalProductPrice = product.price * product.quantity;
        totalCartPrice += totalProductPrice;
        productDetailsHTML += `
            <tr>
                <td>${product.name}</td>
                <td>€${product.price}</td>
                <td>${product.quantity}</td>
                <td>€${totalProductPrice}</td>
            </tr>
        `;
    });

    productDetailsHTML += `
            </tbody>
        </table>
        <hr>
        <h3>Total Cart Price: €${totalCartPrice}</h3>
    `;

    // Update the modal content with the product details
    cartDetails.innerHTML = productDetailsHTML;

    // Show the modal
    modal.style.display = 'block';
}

// Close the modal
function closeModal() {
    const modal = document.getElementById('cartDetailsModal');
    modal.style.display = 'none';
}

// Simulate deleting a cart
function deleteCart(cartId) {
    if (confirm("Are you sure you want to delete this cart?")) {
        alert(`Cart ${cartId} deleted.`);
        // Add logic to delete the cart from the database
    }
}
function deleteCart(cartId) {
    if (confirm("Are you sure you want to delete this cart?")) {
        window.location.href = "delete_cart.php?id=" + cartId;
    }
}
document.querySelectorAll('.stat-box').forEach(function(box) {
    box.style.backgroundColor = '#f6b92b';  // Appliquer la couleur de fond via JavaScript
});

