    function validateForm(event) {
        const name = document.getElementById('name')?.value.trim() || "";
        const location = document.getElementById('location')?.value.trim() || "";
        const description = document.getElementById('description')?.value.trim() || "";
        const iddd = document.getElementById('iddd')?.value.trim() || "";

        const maxLength = 500;

        // Validate Add Event fields
        if (!name) {
            alert("Name is required.");
            event.preventDefault();
            return false;
        }
        if (name.length >= maxLength) {
            alert("Name must be less than 500 characters.");
            event.preventDefault();
            return false;
        }
        if (!location) {
            alert("Location is required.");
            event.preventDefault();
            return false;
        }
        if (location.length >= maxLength) {
            alert("Location must be less than 500 characters.");
            event.preventDefault();
            return false;
        }
        if (!description) {
            alert("Description is required.");
            event.preventDefault();
            return false;
        }
        if (description.length >= maxLength) {
            alert("Description must be less than 500 characters.");
            event.preventDefault();
            return false;
        }

        // Validate Update Event fields
        if (iddd && !name2) {
            alert("Name2 is required when ID is provided.");
            event.preventDefault();
            return false;
        }
  
        if (iddd && !location2) {
            alert("Location2 is required when ID is provided.");
            event.preventDefault();
            return false;
        }

        if (iddd && !description2) {
            alert("Description2 is required when ID is provided.");
            event.preventDefault();
            return false;
        }
        if (!iddd && event.submitter?.name === "update") {
            alert("ID is required for updating an event.");
            event.preventDefault();
            return false;
        }

        return true;
    }
