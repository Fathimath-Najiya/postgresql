// Counter to keep track of added floors
let floorCount = 1;

// Function to add a new floor option
function addFloor() {
    floorCount++;
    const floorOptions = document.getElementById('floorOptions');
    const newFloor = document.createElement('div');
    newFloor.className = 'floor';
    newFloor.innerHTML = `
        <label for="floorPlan${floorCount}">Floor Plan Image ${floorCount}:</label>
        <input type="file" id="floorPlan${floorCount}" accept=".pdf,.jpg,.png" required>
        <label for="floorGraph${floorCount}">Graph for Floor ${floorCount}:</label>
        <input type="file" id="floorGraph${floorCount}" accept=".pdf,.jpg,.png,.json" required>
    `;
    floorOptions.appendChild(newFloor);
}
 // Fetch owner_id of logged-in owner from session or wherever it's stored


 // Set the owner_id value in the hidden input field
 document.getElementById('owner_id').value = owner_id;
// Event listener to add a new floor option when the button is clicked
document.getElementById('addFloorButton').addEventListener('click', addFloor);

// Function to handle form submission
document.getElementById('buildingRegistrationForm').addEventListener('submit', function(event) {
    event.preventDefault();

    
        // Prepare form data
        const formData = new FormData(this);
    
        // Send AJAX request
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Building registered successfully');
                // Add any additional logic here, such as clearing form fields or updating UI
            } else {
                alert('Error registering building');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error registering building');
        });
    });
    
