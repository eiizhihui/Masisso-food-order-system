<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Masisso</title>
    <!-- Standard fonts matching login page -->
    <!-- FontAwesome for symbols/icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap grid and alerts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --masisso-orange: #FF5500;
            --masisso-dark: #192231;
            --masisso-grey: #8E8E93;
            --masisso-bg: #FAF9F5;
            --border-color: #E2E2D9;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: var(--masisso-orange);
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .split-container {
            display: flex;
            width: 1000px;
            height: 680px;
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        /* LEFT PANEL - Brand and visual placeholder */
        .left-panel {
            flex: 1.1;
            background: linear-gradient(rgba(230, 81, 0, 0.45), rgba(25, 34, 49, 0.55)), url('images/laksaLogin.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
            position: relative;
            text-align: center;
        }

        .left-panel-brand {
            font-size: 38px;
            font-weight: 800;
            font-style: italic;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .left-panel-sub {
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 40px;
            letter-spacing: 2px;
        }

        .brand-logo-card {
            width: 290px;
            height: 290px;
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
            transition: transform 0.3s;
            cursor: pointer;
            box-sizing: border-box;
        }

        .brand-logo-card:hover {
            transform: scale(1.03);
            background: rgba(255, 255, 255, 0.18);
        }

        .brand-logo-card i {
            font-size: 90px;
            color: #ffffff;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.15));
        }

        .brand-logo-title {
            font-size: 20px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #ffffff;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .brand-logo-subtitle {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 5px;
        }

        /* RIGHT PANEL - Scrollable Form */
        .right-panel {
            flex: 1;
            padding: 35px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: var(--masisso-bg);
            overflow-y: auto;
            max-height: 100%;
        }

        .right-panel::-webkit-scrollbar {
            width: 6px;
        }

        .right-panel::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .form-header-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--masisso-dark);
            margin-bottom: 15px;
        }

        .custom-form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .custom-form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--masisso-dark);
            margin-bottom: 6px;
            display: block;
        }

        .custom-input {
            width: 100%;
            padding: 11px 16px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .custom-input:focus {
            border-color: var(--masisso-orange);
            box-shadow: 0 0 0 3px rgba(255, 85, 0, 0.08);
        }

        .custom-input:disabled {
            background: #EFEFEF;
            color: #777;
            border-color: #DDD;
        }

        /* Email verification wrapper */
        .email-field-container {
            position: relative;
            display: flex;
        }

        .email-field-container .custom-input {
            padding-right: 85px;
        }

        .btn-verify {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--masisso-orange);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-verify:hover {
            opacity: 0.9;
        }

        .btn-verify.verified {
            background: #2ECC71;
            cursor: default;
        }

        .verification-success-badge {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2ECC71;
            font-size: 16px;
            display: none;
        }

        /* Country Code and Phone input container */
        .phone-input-container {
            display: flex;
            gap: 8px;
        }

        .country-code-select {
            position: relative;
            user-select: none;
        }

        .selected-code {
            border: 1px solid var(--border-color);
            background: white;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            height: 44px;
            box-sizing: border-box;
        }

        .country-dropdown-list {
            position: absolute;
            top: 48px;
            left: 0;
            width: 200px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            display: none;
            z-index: 100;
            max-height: 200px;
            overflow-y: auto;
        }

        .dropdown-item {
            padding: 10px 14px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-item:hover {
            background: var(--masisso-bg);
        }

        /* Password and dynamic validation checks */
        .password-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        .password-recommend-btn {
            font-size: 11px;
            color: var(--masisso-orange);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            font-weight: 700;
        }

        .password-recommend-btn:hover {
            text-decoration: underline;
        }

        .password-example {
            font-size: 11px;
            color: var(--masisso-grey);
        }

        .password-requirements {
            font-size: 11px;
            color: var(--masisso-grey);
            margin-top: 8px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            background: white;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .req-item {
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .req-item i {
            font-size: 10px;
        }

        .req-item.valid {
            color: #2ECC71;
        }

        .req-item.invalid {
            color: #E74C3C;
        }

        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 15px;
            font-size: 13px;
            color: var(--masisso-dark);
        }

        .terms-row input {
            margin-top: 3px;
            cursor: pointer;
        }

        .submit-btn {
            width: 100%;
            background-color: var(--masisso-orange);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(255,85,0,0.2);
            transition: transform 0.2s, opacity 0.2s;
        }

        .submit-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            font-size: 13px;
            color: var(--masisso-grey);
            margin-top: 15px;
        }

        .form-footer a {
            color: var(--masisso-orange);
            font-weight: 700;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Verification overlay popup model */
        .verify-modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .verify-modal {
            background: white;
            padding: 28px;
            border-radius: 20px;
            width: 340px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            animation: modalPop 0.3s ease-out;
        }

        @keyframes modalPop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .verify-modal h4 {
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--masisso-dark);
        }

        .verify-modal p {
            font-size: 13px;
            color: var(--masisso-grey);
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .code-inputs-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .code-input-single {
            width: 44px;
            height: 48px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            outline: none;
            background: var(--masisso-bg);
            transition: border-color 0.2s, background 0.2s;
        }

        .code-input-single:focus {
            border-color: var(--masisso-orange);
            background: white;
        }

        .verify-submit-btn {
            background: var(--masisso-dark);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }

        .verify-submit-btn:hover {
            background: #25334a;
        }

        .modal-close-link {
            display: inline-block;
            margin-top: 15px;
            font-size: 12px;
            color: var(--masisso-grey);
            text-decoration: none;
        }

        .modal-close-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .split-container {
                width: 90%;
                height: auto;
                flex-direction: column;
                margin: 20px 0;
            }
            .left-panel {
                padding: 30px;
            }
            .placeholder-image-card {
                width: 220px;
                height: 220px;
            }
            .placeholder-image-card i {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>

    <div class="split-container">
        <!-- LEFT PANEL: Visual Placeholder -->
        <div class="left-panel">
            <div class="left-panel-brand">Masisso</div>
            <div class="left-panel-sub">Food Order System</div>
            
            <div class="brand-logo-card">
                <i class="fa-solid fa-bowl-food"></i>
                <h4 class="brand-logo-title">Masisso Laksa</h4>
                <span class="brand-logo-subtitle">Authentic Taste</span>
            </div>
        </div>

        <!-- RIGHT PANEL: Registration Form -->
        <div class="right-panel">
            <div>
                <h2 class="form-header-title">Create account</h2>

                <!-- Alerts -->
                <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger py-2 px-3 mb-3 small" role="alert" style="border-radius: 10px;">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success py-2 px-3 mb-3 small" role="alert" style="border-radius: 10px;">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
                <?php } ?>

                <div class="alert alert-danger py-2 px-3 mb-3 small" id="js-error-alert" role="alert" style="border-radius: 10px; display: none;"></div>

                <form action="php/check-register.php" method="post" id="registration-form" onsubmit="return validateFormBeforeSubmit()">
                    
                    <!-- Hidden fields required by backend -->
                    <input type="hidden" name="role" value="customer">
                    <input type="hidden" name="phone" id="phone-hidden">

                    <!-- Name Field -->
                    <div class="custom-form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="custom-input" name="name" id="name" placeholder="Enter your full name" required>
                    </div>

                    <!-- Username Field -->
                    <div class="custom-form-group">
                        <label for="username">Username</label>
                        <input type="text" class="custom-input" name="username" id="username" placeholder="Choose a unique username" required>
                    </div>

                    <!-- Email Field with Verification Button -->
                    <div class="custom-form-group">
                        <label for="email">E-mail</label>
                        <div class="email-field-container">
                            <input type="email" class="custom-input" name="email" id="email" placeholder="Enter email address" required>
                            <button type="button" class="btn-verify" id="btn-verify-email" onclick="startEmailVerification()">Verify</button>
                            <span class="verification-success-badge" id="email-ok-badge"><i class="fa-solid fa-circle-check"></i></span>
                        </div>
                    </div>

                    <!-- Phone Field with Dropdown Country Selector -->
                    <div class="custom-form-group">
                        <label for="phone-number-field">Phone Number</label>
                        <div class="phone-input-container">
                            <div class="country-code-select">
                                <div class="selected-code" onclick="toggleCountryDropdown()">
                                    <span id="flag-display">🇲🇾</span> 
                                    <span id="code-display">+60</span> 
                                    <span style="font-size: 10px; margin-left: 2px;">▼</span>
                                </div>
                                <div class="country-dropdown-list" id="country-dropdown-list">
                                    <div class="dropdown-item" onclick="selectCountry('🇲🇾', '+60', 'Malaysia')">🇲🇾 Malaysia (+60)</div>
                                    <div class="dropdown-item" onclick="selectCountry('🇸🇬', '+65', 'Singapore')">🇸🇬 Singapore (+65)</div>
                                    <div class="dropdown-item" onclick="selectCountry('🇰🇭', '+855', 'Cambodia')">🇰🇭 Cambodia (+855)</div>
                                    <div class="dropdown-item" onclick="selectCountry('🇹🇭', '+66', 'Thailand')">🇹🇭 Thailand (+66)</div>
                                    <div class="dropdown-item" onclick="selectCountry('🇮🇩', '+62', 'Indonesia')">🇮🇩 Indonesia (+62)</div>
                                </div>
                            </div>
                            <input type="text" class="custom-input" id="phone-number-field" placeholder="12-345 6789" required oninput="this.value = this.value.replace(/[^0-9\- ]/g, '')">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="custom-form-group">
                        <div style="display: flex; justify-content: space-between;">
                            <label for="password">Password</label>
                        </div>
                        <input type="password" class="custom-input" name="password" id="password" placeholder="Password" required oninput="checkPasswordStrength(this.value)">
                        <div class="password-meta-row">
                            <span class="password-example">Example: M@sisso2026!</span>
                            <button type="button" class="password-recommend-btn" onclick="recommendStrongPassword()">✨ Suggest Password</button>
                        </div>
                        
                        <!-- Password criteria validation visual -->
                        <div class="password-requirements">
                            <div class="req-item invalid" id="req-length">
                                <i class="fa-solid fa-circle-xmark"></i> 8+ characters
                            </div>
                            <div class="req-item invalid" id="req-number">
                                <i class="fa-solid fa-circle-xmark"></i> 1+ number
                            </div>
                            <div class="req-item invalid" id="req-special">
                                <i class="fa-solid fa-circle-xmark"></i> 1+ special symbol
                            </div>
                            <div class="req-item invalid" id="req-match">
                                <i class="fa-solid fa-circle-xmark"></i> Passwords match
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="custom-form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="custom-input" id="confirm_password" placeholder="Confirm password" required oninput="validatePasswordMatch()">
                    </div>

                    <!-- Address Field -->
                    <div class="custom-form-group">
                        <label for="address">Default Delivery Address</label>
                        <textarea class="custom-input" name="address" id="address" placeholder="Enter your default home address" style="height: 55px; resize: none; padding-top: 8px;" required></textarea>
                    </div>

                    <!-- Terms checkbox -->
                    <div class="terms-row">
                        <input type="checkbox" id="agree-terms" required>
                        <label for="agree-terms">I agree to terms & conditions and consent to verification.</label>
                    </div>

                    <button type="submit" class="submit-btn">Sign up</button>
                </form>
            </div>

            <div class="form-footer">
                Already have an account? <a href="login.php">Log in here</a>
            </div>
        </div>
    </div>

    <!-- CODE VERIFICATION POPUP MODAL -->
    <div class="verify-modal-overlay" id="verify-overlay">
        <div class="verify-modal">
            <h4>Verify your Email</h4>
            <p>We've simulated sending a 4-digit code to <br><strong id="verifying-email-label">email@example.com</strong>.<br><br><span style="background: #FFF3E0; color: #E65100; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 11px;">Demo Code: 1234</span></p>
            
            <div class="code-inputs-container">
                <input type="text" class="code-input-single" maxlength="1" id="c1" onkeyup="focusNextInput(this, 'c2')">
                <input type="text" class="code-input-single" maxlength="1" id="c2" onkeyup="focusNextInput(this, 'c3')">
                <input type="text" class="code-input-single" maxlength="1" id="c3" onkeyup="focusNextInput(this, 'c4')">
                <input type="text" class="code-input-single" maxlength="1" id="c4" onkeyup="focusNextInput(this, '')">
            </div>

            <button type="button" class="verify-submit-btn" onclick="submitVerificationCode()">Verify Code</button>
            <div>
                <a href="#" class="modal-close-link" onclick="closeVerificationModal(event)">Cancel</a>
            </div>
        </div>
    </div>

    <!-- Script triggers and controllers -->
    <script>
        // State variables
        let isEmailVerified = false;
        let selectedCountryFlag = '🇲🇾';
        let selectedCountryCode = '+60';

        // 1. Dropdown handler for flag selection
        function toggleCountryDropdown() {
            const list = document.getElementById('country-dropdown-list');
            list.style.display = (list.style.display === 'block') ? 'none' : 'block';
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const selectEl = document.querySelector('.country-code-select');
            if (selectEl && !selectEl.contains(e.target)) {
                document.getElementById('country-dropdown-list').style.display = 'none';
            }
        });

        function selectCountry(flag, code, name) {
            selectedCountryFlag = flag;
            selectedCountryCode = code;
            
            document.getElementById('flag-display').innerText = flag;
            document.getElementById('code-display').innerText = code;
            document.getElementById('country-dropdown-list').style.display = 'none';
            
            // Focus phone field
            document.getElementById('phone-number-field').focus();
        }

        // 2. Email verification
        async function startEmailVerification() {
            const emailInput = document.getElementById('email');
            const emailVal = emailInput.value.trim();
            const errAlert = document.getElementById('js-error-alert');

            errAlert.style.display = 'none';

            if (!emailVal) {
                showJsError('Please enter an email address first.');
                emailInput.focus();
                return;
            }

            // Simple format check
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(emailVal)) {
                showJsError('Please enter a valid email address.');
                emailInput.focus();
                return;
            }

            // AJAX Check to verify-email.php
            try {
                const formData = new URLSearchParams();
                formData.append('email', emailVal);

                const response = await fetch('php/verify-email.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    if (data.exists) {
                        showJsError('Email is already registered. Please use another.');
                    } else {
                        // Open code modal
                        document.getElementById('verifying-email-label').innerText = emailVal;
                        document.getElementById('verify-overlay').style.display = 'flex';
                        clearCodeInputs();
                        document.getElementById('c1').focus();
                    }
                } else {
                    showJsError(data.message || 'Error checking email.');
                }
            } catch (e) {
                console.error(e);
                showJsError('Could not contact database server. Try again.');
            }
        }

        function clearCodeInputs() {
            document.getElementById('c1').value = '';
            document.getElementById('c2').value = '';
            document.getElementById('c3').value = '';
            document.getElementById('c4').value = '';
        }

        function focusNextInput(current, nextId) {
            if (current.value.length === 1 && nextId !== '') {
                document.getElementById(nextId).focus();
            }
        }

        function closeVerificationModal(e) {
            if (e) e.preventDefault();
            document.getElementById('verify-overlay').style.display = 'none';
        }

        function submitVerificationCode() {
            const c1 = document.getElementById('c1').value;
            const c2 = document.getElementById('c2').value;
            const c3 = document.getElementById('c3').value;
            const c4 = document.getElementById('c4').value;
            const code = c1 + c2 + c3 + c4;

            if (code === '1234') {
                isEmailVerified = true;
                
                // Hide button & show success badge
                document.getElementById('btn-verify-email').style.display = 'none';
                document.getElementById('email-ok-badge').style.display = 'block';
                
                // Lock email field
                document.getElementById('email').readOnly = true;
                
                closeVerificationModal();
                alert('Email address verified successfully!');
            } else {
                alert('Invalid verification code. Please enter 1234.');
                clearCodeInputs();
                document.getElementById('c1').focus();
            }
        }

        function showJsError(msg) {
            const errAlert = document.getElementById('js-error-alert');
            errAlert.innerText = msg;
            errAlert.style.display = 'block';
            errAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // 3. Password Strength Checker
        function checkPasswordStrength(password) {
            // Check length (8+ chars)
            const lenOk = password.length >= 8;
            updateCriteria('req-length', lenOk);

            // Check number (1+ digit)
            const numOk = /[0-9]/.test(password);
            updateCriteria('req-number', numOk);

            // Check special symbol (1+ special char)
            const specialOk = /[^A-Za-z0-9]/.test(password);
            updateCriteria('req-special', specialOk);

            // Trigger match check
            validatePasswordMatch();
        }

        function updateCriteria(elementId, isValid) {
            const item = document.getElementById(elementId);
            const icon = item.querySelector('i');
            
            if (isValid) {
                item.className = 'req-item valid';
                icon.className = 'fa-solid fa-circle-check';
            } else {
                item.className = 'req-item invalid';
                icon.className = 'fa-solid fa-circle-xmark';
            }
        }

        function validatePasswordMatch() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const isMatch = pass && confirm && (pass === confirm);
            updateCriteria('req-match', isMatch);
        }

        // 4. Suggest/Recommend Strong Password
        function recommendStrongPassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const numbers = "0123456789";
            const special = "!@#$%^&*()_+~|}{[]:;?><,./-=";
            
            // Force requirements
            let pass = "";
            pass += "M@s_"; // Brand prefix
            for (let i = 0; i < 3; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            for (let i = 0; i < 3; i++) {
                pass += numbers.charAt(Math.floor(Math.random() * numbers.length));
            }
            pass += special.charAt(Math.floor(Math.random() * special.length));

            // Fill values
            document.getElementById('password').value = pass;
            document.getElementById('confirm_password').value = pass;

            // Trigger visual checks
            checkPasswordStrength(pass);
            
            // Inform user
            alert(`Suggested Secure Password auto-filled:\n\n${pass}\n\nMake sure to write it down!`);
        }

        // 5. Final Form Submission verification
        function validateFormBeforeSubmit() {
            const errAlert = document.getElementById('js-error-alert');
            errAlert.style.display = 'none';

            // Check email verification
            if (!isEmailVerified) {
                showJsError('Please verify your email address by clicking the "Verify" button.');
                return false;
            }

            // Check password strength criteria
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            const lenOk = pass.length >= 8;
            const numOk = /[0-9]/.test(pass);
            const specialOk = /[^A-Za-z0-9]/.test(pass);
            const isMatch = pass === confirm;

            if (!lenOk || !numOk || !specialOk) {
                showJsError('Password does not meet the complexity requirements.');
                return false;
            }

            if (!isMatch) {
                showJsError('Passwords do not match.');
                return false;
            }

            // Concatenate Phone Country Code and Number
            const phoneFieldVal = document.getElementById('phone-number-field').value.trim();
            if (!phoneFieldVal) {
                showJsError('Phone number is required.');
                return false;
            }

            const cleanPhone = phoneFieldVal.replace(/[^0-9]/g, '');
            document.getElementById('phone-hidden').value = selectedCountryCode + ' ' + cleanPhone;

            return true;
        }
    </script>
</body>
</html>