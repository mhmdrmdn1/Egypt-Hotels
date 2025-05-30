document.addEventListener('DOMContentLoaded', function() {
	const signUpButton = document.getElementById('signUp');
	const signInButton = document.getElementById('signIn');
	const container = document.getElementById('container');
	const signUpForm = document.getElementById('signUpForm');
	const signInForm = document.getElementById('signInForm');

	signUpButton.addEventListener('click', () => {
		container.classList.add('right-panel-active');
	});

	signInButton.addEventListener('click', () => {
		container.classList.remove('right-panel-active');
	});

	signUpForm.addEventListener('submit', async function(e) {
		e.preventDefault();
		
		const name = document.getElementById('signup-name').value.trim();
		const username = document.getElementById('signup-username').value.trim();
		const email = document.getElementById('signup-email').value.trim();
		const password = document.getElementById('signup-password').value;
		const messageDiv = document.getElementById('signup-message');

		try {
			const response = await fetch('login.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: `action=signup&name=${encodeURIComponent(name)}&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
			});

			const data = await response.json();
			
			if (data.status === 'success') {
				messageDiv.style.color = 'green';
				messageDiv.textContent = data.message;
				setTimeout(() => {
					signUpForm.reset();
					container.classList.remove('right-panel-active');
					document.getElementById('signin-email').value = email;
					document.getElementById('signin-password').focus();
				}, 1500);
			} else {
				messageDiv.style.color = 'red';
				messageDiv.textContent = data.message;
			}
		} catch (error) {
			messageDiv.style.color = 'red';
			messageDiv.textContent = 'An error occurred. Please try again.';
		}
	});

	signInForm.addEventListener('submit', async function(e) {
		e.preventDefault();
		
		const email = document.getElementById('signin-email').value.trim();
		const password = document.getElementById('signin-password').value;
		const messageDiv = document.getElementById('signin-message');
		const rememberMe = document.getElementById('rememberMe').checked;

		try {
			const response = await fetch('login.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: `action=signin&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&remember_me=${rememberMe}`
			});

			const data = await response.json();
			
			if (data.status === 'success') {
				messageDiv.style.color = 'green';
				messageDiv.textContent = data.message;
				
				setTimeout(() => {
					window.location.href = '../index.php';
				}, 1500);
			} else {
				messageDiv.style.color = 'red';
				messageDiv.textContent = data.message;
			}
		} catch (error) {
			messageDiv.style.color = 'red';
			messageDiv.textContent = 'An error occurred. Please try again.';
		}
	});

	const forgotPasswordForm = document.getElementById('forgotPasswordForm');
	const forgotPasswordMessageDiv = document.getElementById('forgotPasswordMessage');

	if (forgotPasswordForm) {
		forgotPasswordForm.addEventListener('submit', async function(e) {
			e.preventDefault();

			const email = document.getElementById('forgotPasswordEmail').value.trim();

			if (email === '') {
				forgotPasswordMessageDiv.style.color = 'red';
				forgotPasswordMessageDiv.textContent = 'Please enter your email address.';
				return;
			}

			try {
				console.log('Sending POST to forgot_password.php with email:', email);
				const response = await fetch('forgot_password.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: `email=${encodeURIComponent(email)}`
				});
				console.log('Response status:', response.status);
				const data = await response.json();
				console.log('Response data:', data);

				if (data.status === 'success') {
					forgotPasswordMessageDiv.style.color = 'green';
					forgotPasswordMessageDiv.textContent = data.message;
					setTimeout(() => { 
						const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
						if (modal) modal.hide();
					}, 5000);
				} else {
					forgotPasswordMessageDiv.style.color = 'red';
					forgotPasswordMessageDiv.textContent = data.message;
				}
			} catch (error) {
				forgotPasswordMessageDiv.style.color = 'red';
				forgotPasswordMessageDiv.textContent = 'An error occurred. Please try again.';
				console.error('Forgot password error:', error);
			}
		});
	}

	const inputs = document.querySelectorAll('input');
	inputs.forEach(input => {
		input.addEventListener('input', function() {
			const messageDiv = this.closest('form').querySelector('.message');
			if (messageDiv) {
				messageDiv.textContent = '';
			}
		});
	});
});