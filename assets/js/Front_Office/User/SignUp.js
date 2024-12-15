function showStep(step) {
    const steps = document.querySelectorAll('.form-step');
    steps.forEach((el, index) => {
        if (index === step - 1) {
            el.classList.add('form-step-active');
        } else {
            el.classList.remove('form-step-active');
        }
    });
}

function selectGender(gender) {
    // Get both labels
    const maleLabel = document.getElementById('male-label');
    const femaleLabel = document.getElementById('female-label');

    // Reset classes
    maleLabel.classList.remove('active');
    femaleLabel.classList.remove('active');

    // Add 'active' class to the selected gender
    if (gender === 'male') {
        maleLabel.classList.add('active');
    } else if (gender === 'female') {
        femaleLabel.classList.add('active');
    }
}


    const errorMessages = {
    first_name: "First Name must only contain letters.",
    last_name: "Last Name must only contain letters.",
    date_of_birth: "You must be at least 18 years old.",
    email: "Please enter a valid email address.",
    password: "Password must be at least 8 characters, contain a number, and uppercase and lowercase letters.",
    location: "Please enter a valid location.",
    mobile: "Mobile number must be at least 8 digits.",
    role: "Please select a role.",
    gender: "Please select a gender.",
};

    const validators = {
    first_name: (value) => /^[A-Za-z]+$/.test(value),
    last_name: (value) => /^[A-Za-z]+$/.test(value),
    date_of_birth: (value) => {
    if (!value) return false;
    const today = new Date();
    const birthDate = new Date(value);
    const age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();
    return age > 18 || (age === 18 && monthDifference >= 0);
},
    email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
    password: (value) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/.test(value),
    location: (value) => value.trim() !== "",
    mobile: (value) => /^\d{8,}$/.test(value),
    role: (value) => value !== "",
    gender: (value) => value !== "",
};

    function validateField(input) {
    const errorDiv = input.nextElementSibling;
    const name = input.name;

    if (validators[name] && !validators[name](input.value)) {
    errorDiv.textContent = errorMessages[name];
    errorDiv.style.color = "red";
    return false;
} else {
    errorDiv.textContent = "";
    return true;
}
}

    function validateStep(step) {
    const currentStep = document.getElementById(`step${step}`);
    const inputs = currentStep.querySelectorAll("input, select");
    let isValid = true;

    inputs.forEach((input) => {
    if (!validateField(input)) {
    isValid = false;
}
});

    return isValid;
}

    function showStep(step) {
    const steps = document.querySelectorAll(".form-step");
    if (step > 1 && !validateStep(step - 1)) {
    alert("Please fix the errors before proceeding.");
    return;
}

    steps.forEach((el, index) => {
    el.classList.toggle("form-step-active", index === step - 1);
});
}

    document.getElementById("multiStepForm").addEventListener("submit", function (e) {
    e.preventDefault();

    if (!validateStep(3)) {
    alert("Please fix the errors in Step 3 before submitting.");
    return;
}

    const formData = new FormData(this);

    fetch("../../../controller/user/addUser.php", {
    method: "POST",
    body: formData,
})
    .then((response) => response.json())
    .then((data) => {
    if (data.success) {
    showStep(4);
} else {
    alert("Error: " + (data.message || "An unexpected error occurred."));
}
})
    .catch((error) => {
    console.error("Error:", error);
    alert("Error: Unable to add user. Please try again.");
});
});

    document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".form-control, .form-control-select").forEach((input) => {
        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        input.after(errorDiv);

        input.addEventListener("input", () => validateField(input));
    });

    toggleGenderSelection();
});

    function toggleGenderSelection() {
    const maleLabel = document.getElementById("male-label");
    const femaleLabel = document.getElementById("female-label");
    const maleRadio = document.getElementById("male");
    const femaleRadio = document.getElementById("female");

    maleLabel.addEventListener("click", () => {
    maleLabel.classList.add("active");
    femaleLabel.classList.remove("active");
    maleRadio.checked = true;
    validateField(maleRadio);
});

    femaleLabel.addEventListener("click", () => {
    femaleLabel.classList.add("active");
    maleLabel.classList.remove("active");
    femaleRadio.checked = true;
    validateField(femaleRadio);
});
}


     function redirectTolog() {
    // Redirige vers la page d'édition avec l'ID de l'utilisateur comme paramètre
    window.location.href = `log_in.html`;
}
