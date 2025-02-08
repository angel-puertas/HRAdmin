function openModal(mode, personID) {
    const modal = document.getElementById("modal");
    const modalContent = document.getElementById("modal-content");
    modalContent.innerHTML = "";

    let form = document.createElement("form");
    form.method = "post";
    modalContent.appendChild(form);

    switch (mode) {
        case "add":
            console.log('add triggered');
            
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
            break;
        case "edit":
            console.log("edit triggered");

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
            break;
        case "login":
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
            
            form.addEventListener("submit", function(event) {
                event.preventDefault();
        
                const formData = new FormData(form);
        
                fetch("/HRAdmin/lib/login.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Response from server:", data);
                    // alert(data);
                    if (data.includes("Login successful")) {
                        modal.style.display = 'none';
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });

            break;
            case "signup":
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

            const emailAddress = document.getElementById("email").value;

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

            form.addEventListener("submit", function(event) {
                event.preventDefault();
        
                const formData = new FormData(form);
        
                fetch("/HRAdmin/lib/signup.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Response from server:", data);
                    alert(data);

                    if (data.includes("Email sent successfully")) {
                        form.innerHTML = `
                            <ul>
                                <li><input type='hidden' name='email' id='email'></input></li>
                                <li><input type='text' name='code' id='code'></input></li>
                            </ul>
                            <input type='hidden' name='email-confirmation'>
                            <button type='submit' name='email-confirmation' id='confirm'>Confirm</button>
                        `;
                        
                        document.getElementById("email").value = formData.get('email');
                        
                        form.removeEventListener("submit", arguments.callee);
                        form.addEventListener("submit", function(event) {
                            event.preventDefault();
                    
                            const confirmFormData = new FormData(form);
                    
                            fetch("/HRAdmin/lib/confirm_email.php", {
                                method: "POST",
                                body: confirmFormData
                            })
                            .then(response => response.text())
                            .then(data => {
                                console.log("Response from server:", data);
                                alert(data);
                                if (data.includes("Email confirmation successful")) {
                                    modal.style.display = "none";
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });

            break;
    }

    modal.style.display = 'flex';

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}