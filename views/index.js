function validation() {
    const name = document.getElementById("user-name").value;
    if (name == null || name == "") {
        alert("name can't be blank");
    }
}
function validation_date() {
    const inputDate = document.getElementById("claim-date").value;
    const now = new Date();

    const selectedDate = new Date(inputDate);
    if (isNaN(selectedDate.getTime())) {
        alert("Invalid date format");
        return;
    }

    if (selectedDate > now) {
        alert("Invalid date. The date cannot be in the future.");
    }
}

function fassa5() {
    location.reload();
}
function claim_detail() {
    const detail = document.getElementById("claim-details").value;
    if (detail == "") {
        alert("please describe the probleme");
    }
}
function editRecord(button) {
    // Find the row of the button
    var row = button.closest("tr");

    // Iterate through each cell except the "Actions" cell
    Array.from(row.cells).forEach((cell, index) => {
        if (index === row.cells.length - 1) return; // Skip "Actions" column

        // Replace cell content with an input field if not already editable
        if (!cell.querySelector("input")) {
            let currentValue = cell.textContent.trim();
            cell.innerHTML = `<input type="text" value="${currentValue}" class="form-control form-control-sm" />`;
        }
    });

    // Change the button to a "Save" button
    button.textContent = "Save";
    button.onclick = function () {
        saveRecord(button);
    };
}

function saveRecord(button) {
    // Find the row of the button
    var row = button.closest("tr");

    // Save input values back to cells
    Array.from(row.cells).forEach((cell, index) => {
        if (index === row.cells.length - 1) return; // Skip "Actions" column

        let input = cell.querySelector("input");
        if (input) {
            cell.textContent = input.value.trim(); // Replace input with its value
        }
    });

    // Change the button back to "Edit"
    button.textContent = "Edit";
    button.onclick = function () {
        editRecord(button);
    };
}

function deleteRecord(button) {
    // Find the row of the button
    var row = button.closest("tr");

    // Remove the row from the table
    row.remove();
}
function applyFilters() {
    // Apply both filters
    filterByStatus();
    searchById();
}

function filterByStatus() {
    var selectedStatus = document.getElementsByName("filter-status")[0].value;

    var table = document.getElementById("tbl");
    var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    // Iterate through the rows and check the status column
    Array.from(rows).forEach(row => {
        var statusCell = row.cells[3]; // Assuming the "Status" column is the 4th column (index 3)
        var rowIdCell = row.cells[0];  // Get the first column (ID) to keep track for search filtering
        if (statusCell.textContent.trim() === selectedStatus || selectedStatus === "all") {
            row.style.display = rowIdCell.style.display === "" ? "" : "none"; // Maintain visibility based on search
        } else {
            row.style.display = "none"; // Hide row
        }
    });
}

function searchById() {
    const id = document.getElementById("search-bar").value.trim(); // Get the value of the search bar and trim spaces
    var table = document.getElementById("tbl");
    var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    // If the search input is empty, show all rows, otherwise apply search filter
    Array.from(rows).forEach(row => {
        var IdCell = row.cells[0]; // Assuming the ID is in the first cell
        if (id === "" || IdCell.textContent.trim() === id) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}


document.getElementById("claimForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = {
        user_name: document.getElementById("user-name").value,
        product: document.getElementById("product").value,
        claim_details: document.getElementById("claim-details").value,
    };

    try {
        const response = await fetch("http://localhost/omar/public/index.php?controller=claims&action=store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
        });

        const result = await response.json();
        if (response.ok) {
            alert(result.message);
            document.getElementById("claimForm").reset();
        } else {
            alert("Error: " + result.error);
        }
    } catch (error) {
        console.error("Error submitting the form:", error);
        alert("Failed to submit claim. Please try again later.");
    }
});
