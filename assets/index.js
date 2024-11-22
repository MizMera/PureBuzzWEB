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

function validation() {
    // Your validation logic
    return true; // or false if validation fails
}

document.querySelector('.claim-form').addEventListener('submit', function (event) {
    if (!validation()) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
});


