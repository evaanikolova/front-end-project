'use strict';

const isUsernameCorrect = (username) => {
    if (username.length < 3 || username.length > 10) {
        return false;
    }
    return true;
}

const isEmailCorrect = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (re.test(String(email).toLowerCase())) {
        return true;
    }
    return false;
}

const doPasswordsMatch = (password, passwordConfirm) => {
    return password === passwordConfirm;
}

const showFailureMessage = (message) => {
    document.getElementById('register-failure').classList.remove('hidden');
    document.getElementById('register-failure-text').innerText = message;
    // shake animation bellow
    const panel = document.getElementById('register-form');
    panel.style.animation = 'shake 0.3s';
    panel.style.animationIterationCount = '1s';
}

let isUserTeacher = true;

const studentRadioButton = document.getElementById('student');
studentRadioButton.addEventListener('change', (event) => {
    event.preventDefault();
    isUserTeacher = false;
    document.getElementById('register-faculty-number').style.display = 'block';
});

const teacherRadioButton = document.getElementById('teacher');
teacherRadioButton.addEventListener('change', (event) => {
    event.preventDefault();
    isUserTeacher = true;
    document.getElementById('register-faculty-number').style.display = 'none';
});

const registerButton = document.getElementById('register-button');
registerButton.addEventListener('click', (event) => {
    event.preventDefault();

    const userData = {
        username: document.getElementById('register-username').value,
        name: document.getElementById('register-name').value,
        email: document.getElementById('register-email').value,
        role: isUserTeacher === true ? "teacher" : "student",
        facultyNumber: isUserTeacher === true ? 1000 : document.getElementById('register-faculty-number').value,
        password: document.getElementById('register-password').value,
        confirmPassword: document.getElementById('register-password-confirm').value
    }

    if (!doPasswordsMatch(userData.password, userData.confirmPassword)) {
        showFailureMessage('Passwords don\'t match!');
    } else if (!isUsernameCorrect(userData.username)) {
        showFailureMessage('Username should be between\n 3 and 10 symbols long!');
    } else if (!isEmailCorrect(userData.email)) {
        showFailureMessage('Email is not valid!');
    } else {
        fetch('../endpoints/users/register.php', {
        method: 'POST',
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(response => {
        if (response.status) {
            console.log("Successful register!");
            const loginUser = {
                username: userData.username,
                password: userData.password
            }

            // The code bellow is repeatable - need to be fixed.
            
            fetch('../endpoints/users/login.php', {
                method: 'POST',
                body: JSON.stringify(loginUser)
            })
            .then(response => response.json())
            .then(response => {
                if (response.status) {
                    console.log("Successful login!");
                    console.log(response.username);
                    window.location.replace('../views/profile.html');
                } else {
                    console.log("Error login");
                }
            });
        } else {
            console.log("Error registration");
            console.log(response.message);
            console.log(response.errorCode);
            switch(response.errorCode) {
                case '23000': 
                    if (response.message.search('username') !== -1) {
                        showFailureMessage('This username is already taken');
                    } else if (response.message.search('facultyNumber') !== -1) {
                        showFailureMessage('This faculty number already exists');
                    }
                    break;
                default:
                    break;
            }
        }
    });
    }
});
