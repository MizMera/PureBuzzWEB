document.getElementById('productForm').addEventListener('submit', function(e) {
    let isValid = true;  // Flag to check form validity

    // Validation for product name
    const name = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    if (name.value.trim() === '') {
        nameError.textContent = 'Product name is required.';
        isValid = false;
    } else {
        nameError.textContent = '';
    }

    // Validation for price
    const price = document.getElementById('price');
    const priceError = document.getElementById('priceError');
    if (price.value.trim() === '' || parseFloat(price.value) <= 0) {
        priceError.textContent = 'Valid price is required.';
        isValid = false;
    } else {
        priceError.textContent = '';
    }

    // Validation for stock
    const stock = document.getElementById('stock');
    const stockError = document.getElementById('stockError');
    if (stock.value.trim() === '' || parseInt(stock.value) <= 0) {
        stockError.textContent = 'Stock must be a positive number.';
        isValid = false;
    } else {
        stockError.textContent = '';
    }

    // Validation for description
    const description = document.getElementById('description');
    const descriptionError = document.getElementById('descriptionError');
    if (description.value.trim() === '') {
        descriptionError.textContent = 'Description is required.';
        isValid = false;
    } else {
        descriptionError.textContent = '';
    }

    // Validation for image
    const image = document.getElementById('image');
    const imageError = document.getElementById('imageError');
    if (!image.value) {
        imageError.textContent = 'Image is required.';
        isValid = false;
    } else {
        imageError.textContent = '';
    }

    // If the form is not valid, prevent submission
    if (!isValid) {
        e.preventDefault();
    }
});
