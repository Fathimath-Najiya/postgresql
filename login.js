document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    
    fetch('connect.php', {
        method: 'POST',
        body: JSON.stringify({ username: username, password: password }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Signup successful!');
            // Redirect to login.html
            window.location.href = 'home.html'; // Assuming login.html is in the same directory
        } else {
            console.error('Signup failed:', data.message); // Log detailed error message
            errorMessage.textContent = data.message; // Display error message to the user
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = 'Error occurred. Please try again.';
    });
});

