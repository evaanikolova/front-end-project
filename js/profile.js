'use strict';

const insertAfter = (referenceNode, newNode) => {
	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

window.onload = (event) => {
	event.preventDefault();

	fetch('../endpoints/users/getProfile.php', { method: 'GET' })
		.then(response => response.json())
		.then(response => {
			if (!response.status) {
				window.location.replace('../views/login.html');
			} else {
				document.getElementById('welcome-prompt').innerText += (' ' + response.name);
				document.getElementById('username').innerText += (' ' + response.username);
				document.getElementById('email').innerText += (' ' + response.email);
				document.getElementById('role').innerText += (' ' + response.role);
				if (response.role === 'student') {
					const profile = document.getElementById('profile');
					const facultyNumberNode = document.createElement('p');
					facultyNumberNode.setAttribute('id', 'facultyNumber');
					const facultyNumberText = document.createTextNode('Faculty number: ' + response.facultyNumber);
					facultyNumberNode.appendChild(facultyNumberText);
					insertAfter(document.getElementById('role'), facultyNumberNode);
				}
				else if (response.role === 'teacher') {

					const codeNode = document.createElement('p');
					codeNode.setAttribute('id', 'verification-code');
					let codeText = '';

					if (response.verificationCode !== null) {
						codeText = document.createTextNode('Verification code: ' + response.verificationCode);
						
					} else {
						codeText = document.createTextNode('');
					}
					codeNode.appendChild(codeText);
					insertAfter(document.getElementById('role'), codeNode);

					const generateCodeButton = document.getElementById("generate-verification-code-button");
					generateCodeButton.classList.remove("hidden");

					generateCodeButton.addEventListener('click', (event) => {
						event.preventDefault();

						const generatedCode = generateCode(15);
						codeNode.innerText = 'Verification code: ' + generatedCode;
						const verification = {
							code: generatedCode
						}
						// put request to server with code;
						fetch('../endpoints/updateVerificationCode.php', {
							method: 'PUT',
							body: JSON.stringify(verification)
						})
						.then(response => response.json())
						.then(response => {
							if (response.status) {
								showSnackbar('Successfully updated verification code!');
							} else {
								showSnackbar('There was an error updating your verification code. Please try again!');
							}
						});
					});
				}
			}
		});
};

const generateCode = (length) => {
	const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	const charactersLength = chars.length;
	let resultCode = '';

	for (let i = 0; i < length; i++) {
		resultCode += chars.charAt(Math.floor(Math.random() * charactersLength));
	}

	return resultCode;
}

const logoutButton = document.getElementById('logout-button');
logoutButton.addEventListener('click', (event) => {
	event.preventDefault();

	fetch('../endpoints/users/logout.php', { method: 'GET' })
		.then(response => response.json())
		.then(response => {
			if (response.status) {
				window.location.replace('../views/login.html');
			} else {
				console.log('Logout failed!');
			}
		});
});

const showErrorMessage = (message) => {
	document.getElementById('change-failure').classList.remove('hidden');
	document.getElementById('failure-message').innerText = message;
	// shake animation here again
	const panel = document.getElementById('profile');
	panel.style.animation = 'shake 0.3s';
	panel.style.animationIterationCount = '1s';
}

const showSnackbar = (message) => {
	const snackbar = document.getElementById('snackbar');
	snackbar.innerText = message;
	snackbar.className = 'show';
	setTimeout(function () {
		snackbar.className = snackbar.className.replace('show', '');
		// location.reload();
	}, 2000);
}

const changePasswordRequest = (userPasswords) => {
	fetch('../endpoints/users/changePassword.php', {
		method: 'POST',
		body: JSON.stringify(userPasswords)
	})
		.then(response => response.json())
		.then(response => {
			if (response.status) {
				showSnackbar('You have successfully changed your password');
			} else {
				showErrorMessage('Wrong password');
			}
		});
}

const changePasswordListen = () => {
	const changePasswordButton = document.getElementById('change-password-button');
	changePasswordButton.addEventListener('click', (event) => {
		event.preventDefault();

		changePasswordButton.innerText = 'Save';
		const passwordsForm = document.getElementById('passwords');
		passwordsForm.classList.remove('hidden');
		changePasswordButton.addEventListener('click', (event) => {
			event.preventDefault();
			const userPasswords = {
				current: document.getElementById('current-password').value,
				new: document.getElementById('new-password').value
			}

			const confirmationPassword = document.getElementById('confirm-new-password').value;

			if (userPasswords.new != confirmationPassword) {
				showErrorMessage('Passwords don\'t match');
			} else {
				changePasswordRequest(userPasswords);
			}
		});
	});
}

changePasswordListen();
