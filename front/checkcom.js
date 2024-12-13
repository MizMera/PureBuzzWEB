document.getElementById('place-order').addEventListener('click', function(event) {
  // Empêcher le comportement par défaut du bouton si la validation échoue
  event.preventDefault();

  // Initialisation des variables de validation
  let isValid = true;
  let errorMessage = '';

  // Vérification des champs du formulaire de facturation
  const firstName = document.getElementById('first-name');
  const lastName = document.getElementById('last-name');
  const country = document.getElementById('country');
  const address = document.getElementById('address');
  const city = document.getElementById('city');
  const governorate = document.getElementById('governorate');
  const phone = document.getElementById('phone');
  const email = document.getElementById('email');

  // Liste des erreurs pour la facturation
  const errors = [
    { field: firstName, errorSpan: 'firstNameError', label: 'First Name' },
    { field: lastName, errorSpan: 'lastNameError', label: 'Last Name' },
    { field: country, errorSpan: 'countryError', label: 'Country/Region' },
    { field: address, errorSpan: 'addressError', label: 'Address' },
    { field: city, errorSpan: 'cityError', label: 'City' },
    { field: governorate, errorSpan: 'governorateError', label: 'Governorate' },
    { field: phone, errorSpan: 'phoneError', label: 'Phone' },
    { field: email, errorSpan: 'emailError', label: 'Email' }
  ];

  // Réinitialisation des messages d'erreur et des couleurs de bordure
  errors.forEach(function(item) {
    document.getElementById(item.errorSpan).innerText = '';
    item.field.style.borderColor = '';  // Réinitialiser la couleur de bordure
    document.getElementById(item.errorSpan).style.color = ''; // Réinitialiser la couleur du texte
  });

  // Validation des champs de facturation
  errors.forEach(function(item) {
    // Validation en temps réel pendant la saisie
    item.field.addEventListener('input', function() {
      if (item.field.value.trim() !== '') {
        // Si le champ est valide, afficher "Valid" en vert et la bordure verte
        document.getElementById(item.errorSpan).innerText = 'Valid';
        document.getElementById(item.errorSpan).style.color = 'green';
        item.field.style.borderColor = 'green';
      } else {
        // Si le champ est vide, afficher un message d'erreur rouge et mettre la bordure rouge
        document.getElementById(item.errorSpan).innerText = 'fill this field please';
        document.getElementById(item.errorSpan).style.color = 'red';
        item.field.style.borderColor = 'red';
      }
    });

    if (item.field.value.trim() === '') {
      // Si le champ est vide au chargement, afficher une erreur rouge
      document.getElementById(item.errorSpan).innerText = 'fill this field please';
      document.getElementById(item.errorSpan).style.color = 'red';
      item.field.style.borderColor = 'red';
      isValid = false;
      errorMessage += `Please fill in the ${item.label} field.\n`;
    }
  });

  // Validation des champs de paiement
  const cardNumber = document.querySelector('#payment-info input[placeholder="Credit Card Number"]');
  const expirationDate = document.querySelector('#payment-info input[placeholder="Expiration Date (MM/YY)"]');
  const cvv = document.querySelector('#payment-info input[placeholder="CVV"]');

  // Liste des erreurs pour les champs de paiement
  const paymentErrors = [
    { field: cardNumber, errorSpan: 'cardNumberError', label: 'Credit Card Number' },
    { field: expirationDate, errorSpan: 'expirationDateError', label: 'Expiration Date' },
    { field: cvv, errorSpan: 'cvvError', label: 'CVV' }
  ];

  // Réinitialisation des messages d'erreur et des couleurs de bordure pour les champs de paiement
  paymentErrors.forEach(function(item) {
    document.getElementById(item.errorSpan).innerText = '';
    item.field.style.borderColor = '';  // Réinitialiser la couleur de bordure
    document.getElementById(item.errorSpan).style.color = ''; // Réinitialiser la couleur du texte
  });

  // Fonction de validation pour les champs de paiement en temps réel
  function validatePaymentField(field, errorSpan) {
    field.addEventListener('input', function() {
      if (field.value.trim() !== '') {
        // Si le champ est valide, afficher "Valid" en vert et la bordure verte
        document.getElementById(errorSpan).innerText = 'Valid';
        document.getElementById(errorSpan).style.color = 'green';
        field.style.borderColor = 'green';
      } else {
        // Si le champ est vide, afficher un message d'erreur rouge et mettre la bordure rouge
        document.getElementById(errorSpan).innerText = 'fill this field please.';
        document.getElementById(errorSpan).style.color = 'red';
        field.style.borderColor = 'red';
      }
    });

    // Vérification de l'état initial des champs de paiement
    if (field.value.trim() === '') {
      document.getElementById(errorSpan).innerText = 'fill this field please';
      document.getElementById(errorSpan).style.color = 'red';
      field.style.borderColor = 'red';
      isValid = false;
      errorMessage += `Please fill in the ${field.placeholder} field.\n`;
    }
  }

  // Validation des champs de paiement en temps réel
  paymentErrors.forEach(function(item) {
    validatePaymentField(item.field, item.errorSpan);
  });

  // Si le formulaire est valide, rediriger vers la page de confirmation
  if (isValid) {
    alert('Order placed successfully!');
    window.location.href = "file:///C:/Users/merya/OneDrive/Desktop/projet%20web/new/try.html";  // Remplacez par l'URL de votre page de confirmation
  } else {
    // Si le formulaire est invalide, afficher tous les messages d'erreur
    alert(errorMessage || 'Please fill in all required fields.');
  }
});
