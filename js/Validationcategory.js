document.getElementById('categoryForm').addEventListener('submit', function(e) {
    let isValid = true;

    // Get form fields and error elements
    const name = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    const description = document.getElementById('description');
    const descriptionError = document.getElementById('descriptionError');

    // Validate category name
    if (name.value.trim() === '') {
        nameError.textContent = 'Category name is required.';
        isValid = false;
    } else {
        nameError.textContent = '';
    }

    // Validate description
    if (description.value.trim() === '') {
        descriptionError.textContent = 'Description is required.';
        isValid = false;
    } else {
        descriptionError.textContent = '';
    }

    // Prevent form submission if invalid
    if (!isValid) {
        e.preventDefault();
    }
});
