function openModal(mode, personID) {
    const modal = document.getElementById("modal");
    const modalContent = document.getElementById("modal-content");
    modalContent.innerHTML = "";

    let form = document.createElement("form");
    form.method = "post";
    modalContent.appendChild(form);

    switch (mode) {
        case "login":
            handleLogin(form);
            break;
        case "signup":
            handleSignup(form);
            break;
        case "add":
            handleAdd(form);
            break;
        case "edit":
            handleEdit(form, personID);
            break;
        default:
            console.log("Invalid mode");
            break;
    }

    modal.style.display = 'flex';

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

function handleAdd(form) {
    form.innerHTML = `
        <h2>Add Person</h2>
        <div class='form-group form-group-people'>
            <label for='firstName'>First Name</label>
            <input type='text' name='firstName' value=''>

            <label for='lastName'>Last Name</label>
            <input type='text' name='lastName' value=''>

            <label for='OIB'>OIB</label>
            <input type='text' name='OIB' value=''>
        
            <label for='yearOfBirth'>Year of Birth</label>
            <input type='text' name='yearOfBirth' value=''>

            <label for='educationLevel'>Education Level</label>
            <input type='text' name='educationLevel' value=''>

            <label for='yearsOfExperience'>Years of Experience</label>
            <input type='text' name='yearsOfExperience' value=''>

            <label for='jobCategories'>Job Categories</label>
            <input type='text' name='jobCategories' value=''>

            <label for='resume'>Resume</label>
            <input type='text' name='resume' value=''>
        </div>
        <button type='submit' name='add'>Add Person</button>
    `;
}

function handleEdit(form, personID) {
    let personData = {
        personID: personID,
        firstName: document.getElementById(`firstName${personID}`).textContent,
        lastName: document.getElementById(`lastName${personID}`).textContent,
        OIB: document.getElementById(`OIB${personID}`).textContent,
        yearOfBirth: document.getElementById(`yearOfBirth${personID}`).textContent,
        educationLevel: document.getElementById(`educationLevel${personID}`).textContent,
        yearsOfExperience: document.getElementById(`yearsOfExperience${personID}`).textContent,
        jobCategories: document.getElementById(`jobCategories${personID}`).textContent,
        resume: document.getElementById(`resume${personID}`).textContent
    };

    form.innerHTML = `
        <h2>Edit Person</h2>
        <div class='form-group form-group-people'>
            <input type='hidden' name='personID' value='${personData.personID || ""}'>

            <label for='firstName'>First Name</label>
            <input type='text' name='firstName' value='${personData.firstName || ""}'>

            <label for='lastName'>Last Name</label>
            <input type='text' name='lastName' value='${personData.lastName || ""}'>

            <label for='OIB'>OIB</label>
            <input type='text' name='OIB' value='${personData.OIB || ""}'>
        
            <label for='yearOfBirth'>Year of Birth</label>
            <input type='text' name='yearOfBirth' value='${personData.yearOfBirth || ""}'>

            <label for='educationLevel'>Education Level</label>
            <input type='text' name='educationLevel' value='${personData.educationLevel || ""}'>

            <label for='yearsOfExperience'>Years of Experience</label>
            <input type='text' name='yearsOfExperience' value='${personData.yearsOfExperience || ""}'>

            <label for='jobCategories'>Job Categories</label>
            <input type='text' name='jobCategories' value='${personData.jobCategories || ""}'>

            <label for='resume'>Resume</label>
            <input type='text' name='resume' value='${personData.resume || ""}'>
        </div>
        <button type='submit' name='edit'>Save Changes</button>
    `;
}

function handleLogin(form) {
    form.innerHTML = `
        <div id='login-signup'>
            <div><a onclick="openModal('login')">Log In</a></div>
            <div><a onclick="openModal('signup')">Sign Up</a></div>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type='email' name='email' id='email'>
            <div id='email-message'></div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type='password' name='password' id='password'>
            <div id='invalid-password-message'></div>
        </div>
        <div id="recaptcha-container"></div>
        <input type='hidden' name='recaptcha-token' id='recaptcha-token'>
        <input type='hidden' name='login'>
        <button type='submit' name='login'>Log In</button>
    `;
    
    setTimeout(() => {
        grecaptcha.render("recaptcha-container", {
            "sitekey": "6Ld7GdEqAAAAADxNT_aoapkP1kf_4hOpxmnE7fpP"
        });
    }, 500);
    
    
    $(form).on('submit', function(event) {
        event.preventDefault();

        // Client side reCAPTCHA validation
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            alert('Please complete the reCAPTCHA');
            return;
        }

        let formData = new FormData(this);

        $.ajax({
            url: '/HRAdmin/lib/login.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Response from server:", response);
                if (response.includes("Email address unconfirmed.")) {
                    emailVerification(form, formData.get('email'));
                } else if (response.includes("Login successful")) {
                    modal.style.display = 'none';
                    location.reload();
                }
            }
        });
    });
}

function handleSignup(form) {            
    form.innerHTML = `
        <div id='login-signup'>
            <div><a onclick="openModal('login')">Log In</a></div>
            <div><a onclick="openModal('signup')">Sign Up</a></div>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type='email' name='email' id='email'>
            <div id='email-message'></div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type='password' name='password' id='password'>
            <div id='invalid-password-message'></div>
        </div>
        <div class="form-group">
            <label for="repeat-password">Repeat Password</label>
            <input type='password' name='repeat-password' id='repeat-password'>
            <div id='incorrect-password-message'></div>
        </div>
        <div id="recaptcha-container"></div>
        <input type='hidden' name='signup'>
        <button type='submit' name='signup'>Sign Up</button>
    `;

    // Delay execution to ensure the form is rendered before reCAPTCHA loads
    setTimeout(() => {
        grecaptcha.render("recaptcha-container", {
            "sitekey": "6Ld7GdEqAAAAADxNT_aoapkP1kf_4hOpxmnE7fpP"
        });
    }, 500);
    
    // CLIENT SIDE FORM VALIDATION
    const password = document.getElementById("password");
    const repeatPassword = document.getElementById("repeat-password");
    const invalidPasswordMessage = document.getElementById("invalid-password-message");
    const incorrectPasswordMessage = document.getElementById("incorrect-password-message");

    password.addEventListener("blur", function () {
        if (password.value.length > 0 && password.value.length < 8) {
            invalidPasswordMessage.style.color = "Red";
            invalidPasswordMessage.innerHTML = "Password needs more than 8 characters!";
        }
    });

    password.addEventListener("input", function () {
        if (password.value.length == 0 || password.value.length >= 8) {
            invalidPasswordMessage.innerHTML = "";
        }
    });

    repeatPassword.addEventListener("blur", function() {
        if (password.value !== repeatPassword.value) {
            incorrectPasswordMessage.style.color = "Red";
            incorrectPasswordMessage.innerHTML = "Passwords don't match!";
        }
    });

    repeatPassword.addEventListener("input", function() {
        if (password.value === repeatPassword.value) {
            incorrectPasswordMessage.innerHTML = "";
        }
    });

    // LIVE FORM VALIDATION (jQuery + AJAX)
    $(document).ready(function(){
        $('#email').on('blur', function(){
            let email = $(this).val();
            $.ajax({
                url: '/HRAdmin/lib/check_email.php',
                type: 'POST',
                data: { email: email },
                success: function(response){
                    if(response == 'taken'){
                        $('#email-message').html('<span style="color: red;">Email already in use</span>');
                    } else {
                        $('#email-message').html('<span style="color: green;"></span>');
                    }
                }
            });
        });
    });

    $(form).on('submit', function(event) {
        event.preventDefault();

        // Client side reCAPTCHA validation
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            alert('Please complete the reCAPTCHA');
            return;
        }

        let formData = new FormData(this);

        $.ajax({
            url: '/HRAdmin/lib/signup.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log("Response from server:", response);
                alert(response);

                if (response.includes("Signup successful")) {
                    emailVerification(form, formData.get('email'));
                }
            }
        });
    });
};


function emailVerification(form, email) {
    let formData = new FormData();
    formData.append('email', email);

    $.ajax({
        url: '/HRAdmin/lib/send_confirmation_email.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            console.log("Response from server:", response);
            alert(response);

            if (response.includes("Email sent successfully")) {
                $(form).off('submit');

                $(form).html(`
                    <div class="form-group">
                        <input type='hidden' name='email' id='email'>
                        <label for="confirmation-code">Confirmation Code</label>
                        <input type='text' name='confirmation-code' id='confirmation-code'>
                    </div>
                    <input type='hidden' name='email-confirmation'>
                    <button type='submit' name='email-confirmation' id='confirm'>Confirm</button>
                `);

                $('#email').val(formData.get('email'));

                $(form).on('submit', function(event) {
                    event.preventDefault();
            
                    $.ajax({
                        url: '/HRAdmin/lib/confirm_email.php',
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response){
                            console.log("Response from server:", response);
                            alert(response);
                            if (response.includes("Email confirmation successful")) {
                                modal.style.display = "none";
                            }
                        }
                    });
                });
            }
        }
    });
}