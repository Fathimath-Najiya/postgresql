// script.js

document.addEventListener('DOMContentLoaded', function() {
    fetchBuildingList();
});

function fetchBuildingList() {
    fetch('fetch_buildings.php')
        .then(response => response.json())
        .then(data => {
            const buildingList = document.getElementById('buildingList');
            buildingList.innerHTML = '';

            data.forEach(building => {
                const li = document.createElement('li');
                li.textContent = building.name;

                const updateButton = document.createElement('button');
                updateButton.textContent = 'Update';
                updateButton.addEventListener('click', function() {
                    // Redirect to update page
                    window.location.href = 'update_building.php?id=' + building.id;
                });

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.addEventListener('click', function() {
                    deleteBuilding(building.id);
                });

                li.appendChild(updateButton);
                li.appendChild(deleteButton);
                buildingList.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching building list:', error));
}

function deleteBuilding(buildingId) {
    fetch('delete_building.php?id=' + buildingId, { method: 'DELETE' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the building from the UI
                const buildingItem = document.getElementById('buildingItem-' + buildingId);
                if (buildingItem) {
                    buildingItem.remove();
                }
            } else {
                console.error('Failed to delete building:', data.message);
            }
        })
        .catch(error => console.error('Error deleting building:', error));
}
