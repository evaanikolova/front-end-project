'use strict';

fetch('../endpoints/users/getProfile.php', { method: 'GET' })
.then(response => response.json())
.then(response => {
    if (!response.status) {
        // User not logged in;
    } else {
        if (response.role === 'teacher') {
            console.log('It\'s a teacher!');
            document.getElementById('add-slots-menu-item').style.display = 'inline-block';
        }
    }
});