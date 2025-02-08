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
        <ul>
            <input type='hidden' name='personID' value=''>
            <li>First Name: <input type='text' name='firstName' value=''></li>
            <li>Last Name: <input type='text' name='lastName' value=''></li>
            <li>OIB: <input type='text' name='OIB' value=''></li>
            <li>Year of Birth: <input type='text' name='yearOfBirth' value=''></li>
            <li>Education Level: <input type='text' name='educationLevel' value=''></li>
            <li>Years of Experience: <input type='text' name='yearsOfExperience' value=''></li>
            <li>Job Categories: <input type='text' name='jobCategories' value=''></li>
            <li>Resume: <input type='text' name='resume' value=''></li>
        </ul>
        <button type='submit' name='add'>Save Changes</button>
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
        <ul>
            <input type='hidden' name='personID' value='${personData.personID || ""}'>
            <li>First Name: <input type='text' name='firstName' value='${personData.firstName || ""}'></li>
            <li>Last Name: <input type='text' name='lastName' value='${personData.lastName || ""}'></li>
            <li>OIB: <input type='text' name='OIB' value='${personData.OIB || ""}'></li>
            <li>Year of Birth: <input type='text' name='yearOfBirth' value='${personData.yearOfBirth || ""}'></li>
            <li>Education Level: <input type='text' name='educationLevel' value='${personData.educationLevel || ""}'></li>
            <li>Years of Experience: <input type='text' name='yearsOfExperience' value='${personData.yearsOfExperience || ""}'></li>
            <li>Job Categories: <input type='text' name='jobCategories' value='${personData.jobCategories || ""}'></li>
            <li>Resume: <input type='text' name='resume' value='${personData.resume || ""}'></li>
        </ul>
        <button type='submit' name='edit'>Save Changes</button>
    `;
}

function handleLogin(form) {
    console.log("login triggered");

    form.innerHTML = `
        <div id='login-signup'>
            <div><a onclick="openModal('login')">Log In</a></div>
            <div><a onclick="openModal('signup')">Sign Up</a></div>
        </div>
        <ul>
            <li>Email Address</li>
            <li><input type='email' name='email' id='email'></input></li>
            <li>Password</li>
            <li><input type='password' name='password' id='password'></input></li>
        </ul>
        <div id="recaptcha-container"></div>
        <br>
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
        let formData = new FormData(this);

        $.ajax({
            url: '/HRAdmin/lib/login.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Response from server:", response);
                if (response.includes("Login successful")) {
                    modal.style.display = 'none';
                    location.reload();
                }
            }
        });
    });
}

function handleSignup(form) {
    console.log("signup triggered");
            
    form.innerHTML = `
        <div id='login-signup'>
            <div><a onclick="openModal('login')">Log In</a></div>
            <div><a onclick="openModal('signup')">Sign Up</a></div>
        </div>
        <ul>
            <li>Email Address</li>
            <li><input type='email' name='email' id='email'></input></li>
            <li id='email-message'></li>
            <li>Password</li>
            <li><input type='password' name='password' id='password'></input></li>
            <li id='invalid-password-message'></li>
            <li>Repeat Password</li>
            <li><input type='password' name='repeat-password' id='repeat-password'></input></li>
            <li id='incorrect-password-message'></li>
        </ul>
        <div id="recaptcha-container"></div>
        <br>
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

                if (response.includes("Email sent successfully")) {
                    $(form).html(`
                        <ul>
                            <li><input type='hidden' name='email' id='email'></input></li>
                            <li><input type='text' name='code' id='code'></input></li>
                        </ul>
                        <input type='hidden' name='email-confirmation'>
                        <button type='submit' name='email-confirmation' id='confirm'>Confirm</button>
                    `);

                    $('#email').val(formData.get('email'));

                    $(form).off('submit');
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
    });
};