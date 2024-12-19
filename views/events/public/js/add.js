function editEvent(eventId) {
    // Fetch event details
    fetch(`index.php?action=getEventDetails&id=${eventId}`)
        .then(response => response.json())
        .then(event => {
            // Populate form with event details
            document.getElementById('name').value = event.name;
            document.getElementById('location').value = event.location;
            document.getElementById('description').value = event.description;
            document.getElementById('num_tickets').value = event.num_tickets;

            // Change form action to update instead of add
            const form = document.querySelector('form');
            form.action = `index.php?action=updateEvent&id=${eventId}`;
            form.querySelector('button[type="submit"]').textContent = 'Update Event';

            // Scroll to the form
            form.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => console.error('Error:', error));
}

// Reset form when adding a new event
document.addEventListener('DOMContentLoaded', function() {
    const addNewEventButton = document.createElement('button');
    addNewEventButton.textContent = 'Add New Event';
    addNewEventButton.addEventListener('click', function() {
        const form = document.querySelector('form');
        form.reset();
        form.action = 'index.php?action=addEvent';
        form.querySelector('button[type="submit"]').textContent = 'Add Event';
    });

    document.querySelector('.container').insertBefore(addNewEventButton, document.querySelector('form'));
});

