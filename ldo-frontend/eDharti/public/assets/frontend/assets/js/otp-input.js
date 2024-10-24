document.addEventListener('DOMContentLoaded', () => {
    const setupForm = (formId, submitId) => {
        const form = document.getElementById(formId);
        const inputs = [...form.querySelectorAll('input[type=text]')];
        const submit = form.querySelector(submitId);

        const handleKeyDown = (e) => {
            const index = inputs.indexOf(e.target);

            if (!/^[0-9]{1}$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && !e.metaKey) {
                e.preventDefault();
            }

            if ((e.key === 'Delete' || e.key === 'Backspace') && index >= 0) {
                if (inputs[index].value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                    }
                } else {
                    inputs[index].value = '';  // Clear current input if not empty
                }
                e.preventDefault();  // Prevent default behavior
            }
        };

        const handleInput = (e) => {
            const { target } = e;
            const index = inputs.indexOf(target);
            if (target.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            } else if (index === inputs.length - 1) {
                submit.focus();
            }
        };

        const handleFocus = (e) => {
            e.target.select();
        };

        const handlePaste = (e) => {
            e.preventDefault();
            const text = e.clipboardData.getData('text');
            if (!/^[0-9]{1,}$/.test(text)) return;
            const digits = text.split('').slice(0, inputs.length);
            inputs.forEach((input, i) => input.value = digits[i] || '');
            if (digits.length === inputs.length) {
                submit.focus();
            }
        };

        inputs.forEach((input) => {
            input.addEventListener('input', handleInput);
            input.addEventListener('keydown', handleKeyDown);
            input.addEventListener('focus', handleFocus);
            input.addEventListener('paste', handlePaste);
        });
    };

    setupForm('otp-form', '#verifyMobileOtpBtn');
    setupForm('otp-form-email', '#verifyEmailOtpBtn');
    setupForm('org-otp-form', '#orgVerifyMobileOtpBtn');
    setupForm('org-otp-form-email', '#orgVerifyEmailOtpBtn');
});

// Field Data Validation in All Inputs - 31-07-2024 by Diwakar Sinha

$(document).ready(function () {
    $('.alpha-only').keypress(function (event) {
        var charCode = event.which;
        // Allow only alphabetic characters (a-z, A-Z), space (32), and dot (46)
        if (
            (charCode < 65 || (charCode > 90 && charCode < 97) || charCode > 122) &&
            charCode !== 32 && charCode !== 46
        ) {
            event.preventDefault();
        }
    });
    $('.numericDecimal').on('input', function () {
        var value = $(this).val();
        if (!/^\d*\.?\d*$/.test(value)) {
            $(this).val(value.slice(0, -1));
        }
    });

    $(".numericOnly").on('input', function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $('.alphaNum-hiphenForwardSlash').on('input', function () {
        var value = $(this).val();
        // Allow only alphanumeric, hyphen, and forward slash
        var filteredValue = value.replace(/[^a-zA-Z0-9\-\/]/g, '');
        $(this).val(filteredValue);
    });

    //   Date Format
    $('.date_format').on('input', function (e) {
        var input = $(this).val().replace(/\D/g, '');
        if (input.length > 8) {
            input = input.substring(0, 8);
        }

        var formattedDate = '';
        if (input.length > 0) {
            formattedDate = input.substring(0, 2);
        }
        if (input.length >= 3) {
            formattedDate += '-' + input.substring(2, 4);
        }
        if (input.length >= 5) {
            formattedDate += '-' + input.substring(4, 8);
        }

        $(this).val(formattedDate);
    });

    // Plot No.
    $('.plotNoAlpaMix').on('input', function () {
        var pattern = /[^a-zA-Z0-9+\-/]/g;
        var sanitizedValue = $(this).val().replace(pattern, '');
        $(this).val(sanitizedValue);
    });

    // PAN
    $('.pan_number_format').on('input', function (event) {
        var value = $(this).val().toUpperCase();
        var newValue = '';
        var valid = true;

        // PAN format: AAAAA9999A

        for (var i = 0; i < value.length; i++) {
            var char = value[i];

            if (i < 5) {
                if (/[A-Z]/.test(char)) {
                    newValue += char;
                } else {
                    valid = false;
                    break;
                }
            }

            else if (i >= 5 && i < 9) {
                if (/[0-9]/.test(char)) {
                    newValue += char;
                } else {
                    valid = false;
                    break;
                }
            }

            else if (i === 9) {
                if (/[A-Z]/.test(char)) {
                    newValue += char;
                } else {
                    valid = false;
                    break;
                }
            }
        }

        if (value.length > 10) {
            valid = false;
        }

        if (valid) {
            $(this).val(newValue);
        } else {
            $(this).val(newValue);
        }
    });
    // End PAN


});

// Required Validation - 27-09-2024 by Diwakar Sinha
// This is working final for Individual Owner
$(document).ready(function () {
    var form = $('.dynamicForm');

    function updateFormId() {
        if ($('#propertyowner').is(':checked')) {
            form.attr('id', 'propertyOwnerForm');
        } else if ($('#organization').is(':checked')) {
            form.attr('id', 'organizationForm');
        }
    }

    $('#propertyowner, #organization').change(function () {
        updateFormId();
    });

    $('#IndsubmitButton').click(function (event) {
        event.preventDefault();

        updateFormId();

        var updatedFormId = form.attr('id');
        var updatedForm = $('#' + updatedFormId);

        if (validateForm()) {
            $('#IndsubmitButton').attr('disabled', true).text('Submitting...');
            updatedForm[0].submit();
        }
    });

    function toggleAddressFields() {
        if ($('#Yes').is(':checked')) {
            $('#ifyes').show();
            $('#ifYesNotChecked').hide();
        } else {
            $('#ifyes').hide();
            $('#ifYesNotChecked').show();
        }
    }

    toggleAddressFields();
    $('#Yes').change(function () {
        toggleAddressFields();
    });

    function validateForm() {
        let firstInvalidField = null;
        let isValid = true;

        const basicFields = [
            { id: '#indfullname', errorId: '#IndFullNameError' },
            { id: '#Indgender', errorId: '#IndGenderError' },
            { id: '#dateOfBirth', errorId: '#dateOfBirthError' },
            { id: '#IndSecondName', errorId: '#IndSecondNameError' },
            { id: '#mobileInv', errorId: '#IndMobileError' },
            { id: '#emailInv', errorId: '#IndEmailError' },
            { id: '#IndPanNumber', errorId: '#IndPanNumberError' },
            { id: '#IndAadhar', errorId: '#IndAadharError' },
            { id: '#commAddress', errorId: '#IndCommAddressError' },
        ];

        basicFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);

            // On change or input, hide the error message
            inputField.on('input', function () {
                if (inputField.val().trim() !== "") {
                    inputField.removeClass('required');
                    errorField.hide();
                }
            });

            if (inputField.val().trim() === "") {
                inputField.addClass('required');
                errorField.text("This field is required").show();
                isValid = false;
                if (firstInvalidField === null) {
                    firstInvalidField = inputField;
                }
            } else {
                inputField.removeClass('required');
                errorField.hide();
            }
        });


        // Mobile number validation
        const $Indmobile = $('#mobileInv');
        const $IndMobileError = $('#IndMobileError');
        const IndMobileValue = $Indmobile.val().trim();
        const dataIdValue = $Indmobile.attr('data-id');

        // Validate mobile number input
        if (IndMobileValue === '') {
            $IndMobileError.text('Mobile Number is required');
            $IndMobileError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $Indmobile;
            }
        } else if (IndMobileValue.length !== 10) {
            $IndMobileError.text('Mobile Number must be exactly 10 digit');
            $IndMobileError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $Indmobile;
            }
        } else if (dataIdValue === "0") {
            $IndMobileError.text('Please verify your mobile number');
            $IndMobileError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $Indmobile;
            }
        } else if (dataIdValue === "1") {
            $IndMobileError.hide();
        } else {
            $IndMobileError.hide();
        }

        // Email validation
        const $IndEmail = $('#emailInv');
        const $IndEmailError = $('#IndEmailError');
        const IndEmailValue = $IndEmail.val().trim();
        const dataIdEmailValue = $IndEmail.attr('data-id');

        // Validate mobile number input
        if (IndEmailValue === '') {
            $IndEmailError.text('Email is required');
            $IndEmailError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $IndEmail;
            }
        } else if (dataIdEmailValue === "0") {
            $IndEmailError.text('Please verify your email');
            $IndEmailError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $IndEmail;
            }
        } else if (dataIdEmailValue === "1") {
            $IndEmailError.hide();
        } else {
            $IndEmailError.hide();
        }

        // PAN Number Validation
        const $IndPanNumber = $('#IndPanNumber');
        const $IndPanNumberError = $('#IndPanNumberError');
        const IndPanNumberValue = $IndPanNumber.val().trim();

        // Validate PAN number input length
        if (IndPanNumberValue === '') {
            $IndPanNumber.addClass('required');
            $IndPanNumberError.text('This field is required').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $IndPanNumber;
            }
        } else if (IndPanNumberValue.length !== 10) {
            $IndPanNumber.addClass('required');
            $IndPanNumberError.text('PAN Number must be exactly 10 characters').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $IndPanNumber;
            }
        } else {
            $IndPanNumber.removeClass('required');
            $IndPanNumberError.hide();
        }

        // Aadhaar Number Validation
        const $IndAadhar = $('#IndAadhar');
        const $IndAadharError = $('#IndAadharError');
        const IndAadharValue = $IndAadhar.val().trim();

        // Validate PAN number input length
        if (IndAadharValue === '') {
            $IndAadhar.addClass('required');
            $IndAadharError.text('This field is required').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $IndAadhar;
            }
        } else if (IndAadharValue.length !== 12) {
            $IndAadhar.addClass('required');
            $IndAadharError.text('Aadhar Number must be exactly 12 digit').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $IndAadhar;
            }
        } else {
            $IndAadhar.removeClass('required');
            $IndAadharError.hide();
        }
        // Address details validation based on checkbox
        if ($('#Yes').is(':checked')) {
            const addressFieldsYes = [
                { id: '#localityFill', errorId: '#localityFillError' },
                { id: '#blocknoInvFill', errorId: '#blocknoInvFillError' },
                { id: '#plotnoInvFill', errorId: '#plotnoInvFillError' },
                { id: '#landUseInvFill', errorId: '#landUseInvFillError' },
                { id: '#landUseSubtypeInvFill', errorId: '#landUseSubtypeInvFillError' }
            ];

            addressFieldsYes.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);

                // On change or input, hide the error message
                inputField.on('input', function () {
                    if (inputField.val().trim() !== "") {
                        inputField.removeClass('required');
                        errorField.hide();
                    }
                });

                if (inputField.val().trim() === "") {
                    inputField.addClass('required');
                    errorField.text("This field is required").show();
                    isValid = false;
                    if (firstInvalidField === null) {
                        firstInvalidField = inputField;
                    }
                } else {
                    inputField.removeClass('required');
                    errorField.hide();
                }
            });
        } else {
            const addressFieldsNo = [
                { id: '#locality', errorId: '#localityError' },
                { id: '#block', errorId: '#blockError' },
                { id: '#plot', errorId: '#plotError' },
                // {id: '#knownas', errorId: '#knownasError'},
                { id: '#landUse', errorId: '#landUseError' },
                { id: '#landUseSubtype', errorId: '#landUseSubtypeError' }
            ];

            addressFieldsNo.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);

                // On change or input, hide the error message
                inputField.on('input', function () {
                    if (inputField.val().trim() !== "") {
                        inputField.removeClass('required');
                        errorField.hide();
                    }
                });


                if (inputField.val().trim() === "") {
                    inputField.addClass('required');
                    errorField.text("This field is required").show();
                    isValid = false;
                    if (firstInvalidField === null) {
                        firstInvalidField = inputField;
                    }
                } else {
                    inputField.removeClass('required');
                    errorField.hide();
                }
            });
        }

        const fileFields = [
            { id: '#IndSaleDeed', errorId: '#IndSaleDeedError' },
            { id: '#IndBuildAgree', errorId: '#IndBuildAgreeError' },
            { id: '#IndLeaseDeed', errorId: '#IndLeaseDeedError' },
            { id: '#IndSubMut', errorId: '#IndSubMutError' },
            { id: '#IndOther', errorId: '#IndOtherError' }
        ];

        let isValidFiles = true;
        let firstInvalidFieldFiles = null;
        let atLeastOneFile = false;

        // Resetting error states
        fileFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);

            // On change (when files are selected), validate the files
            inputField.on('change', function () {
                const files = inputField[0].files;
                if (files.length > 0) {
                    let allFilesArePDF = true;

                    for (let i = 0; i < files.length; i++) {
                        const fileName = files[i].name;
                        // Check if the file is a PDF
                        if (!fileName.endsWith('.pdf')) {
                            allFilesArePDF = false;
                            break;
                        }
                    }

                    if (allFilesArePDF) {
                        inputField.removeClass('required');
                        errorField.hide();
                    } else {
                        inputField.addClass('required');
                        errorField.text("Only PDF files are allowed").show();
                    }
                }
            });

            inputField.removeClass('required');
            errorField.hide();
        });

        fileFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);
            const files = inputField[0].files;

            if (files.length > 0) {
                atLeastOneFile = true;

                let allFilesArePDF = true;
                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;
                    // Validate the file extension
                    if (!fileName.endsWith('.pdf')) {
                        allFilesArePDF = false;
                        break;
                    }
                }
                // If any non-PDF file is found
                if (!allFilesArePDF) {
                    inputField.addClass('required');
                    errorField.text("Only PDF files are allowed").show();
                    isValidFiles = false;
                    isValid = false;
                    if (firstInvalidFieldFiles === null) {
                        firstInvalidFieldFiles = inputField;
                    }
                }
            }
        });


        const ownerLessField = $('#IndOwnerLess');
        const ownerLessError = $('#IndOwnerLessError');
        ownerLessField.removeClass('required');
        ownerLessError.hide();

        // On change (when files are selected), validate the files
        ownerLessField.on('change', function () {
            if (ownerLessField[0].files.length === 0) {
                ownerLessField.addClass('required');
                ownerLessError.text("This field is mandatory. Upload a PDF file").show();
            } else {
                const files = ownerLessField[0].files;
                let allFilesArePDF = true;

                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;
                    // Check if the file is a PDF
                    if (!fileName.endsWith('.pdf')) {
                        allFilesArePDF = false;
                        break;
                    }
                }

                if (allFilesArePDF) {
                    ownerLessField.removeClass('required');
                    ownerLessError.hide();
                } else {
                    ownerLessField.addClass('required');
                    ownerLessError.text("Only PDF files are allowed").show();
                }
            }
        });

        if (ownerLessField[0].files.length === 0) {
            ownerLessField.addClass('required');
            ownerLessError.text("This field is mandatory. Upload a PDF file").show();
            isValidFiles = false;
            if (firstInvalidFieldFiles === null) {
                firstInvalidFieldFiles = ownerLessField;
            }
        } else {
            const files = ownerLessField[0].files;
            let allFilesArePDF = true;
            for (let i = 0; i < files.length; i++) {
                const fileName = files[i].name;
                if (!fileName.endsWith('.pdf')) {
                    allFilesArePDF = false;
                    break;
                }
            }

            if (!allFilesArePDF) {
                ownerLessField.addClass('required');
                ownerLessError.text("Only PDF files are allowed").show();
                isValidFiles = false;
                if (firstInvalidFieldFiles === null) {
                    firstInvalidFieldFiles = ownerLessField;
                }
            }
        }

        if (!atLeastOneFile) {
            fileFields.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);
                inputField.addClass('required');
                errorField.text("At least one file is required").show();
                isValidFiles = false;
                if (firstInvalidFieldFiles === null) {
                    firstInvalidFieldFiles = inputField;
                }
            });
        }

        // if (firstInvalidFieldFiles !== null) {
        //     firstInvalidFieldFiles.focus();
        // }


        const consentField = $('#IndConsent');
        const consentErrorField = $('#IndConsentError');
        if (!consentField.is(':checked')) {
            consentField.addClass('required');
            consentErrorField.text("You must agree to the terms").show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = consentField;
            }
        } else {
            consentField.removeClass('required');
            consentErrorField.hide();
        }

        if (firstInvalidField !== null) {
            firstInvalidField.focus();
        }

        return isValid;
    }

});


$(document).ready(function () {
    var form = $('.dynamicForm');

    function updateFormId() {
        if ($('#propertyowner').is(':checked')) {
            form.attr('id', 'propertyOwnerForm');
        } else if ($('#organization').is(':checked')) {
            form.attr('id', 'organizationForm');
        }
    }


    $('#propertyowner, #organization').change(function () {
        updateFormId();
    });

    $('#OrgsubmitButton').click(function (event) {
        event.preventDefault();

        updateFormId();

        var updatedFormId = form.attr('id');
        var updatedForm = $('#' + updatedFormId);

        if (validateForm()) {
            $('#OrgsubmitButton').attr('disabled', true).text('Submitting...');
            updatedForm[0].submit();
        }
    });

    function toggleAddressFields() {
        if ($('#YesOrg').is(':checked')) {
            $('#ifyesOrg').show();
            $('#ifYesNotCheckedOrg').hide();
        } else {
            $('#ifyesOrg').hide();
            $('#ifYesNotCheckedOrg').show();
        }
    }

    toggleAddressFields();
    $('#YesOrg').change(function () {
        toggleAddressFields();
    });

    function validateForm() {
        let firstInvalidField = null;
        let isValid = true;

        // Basic details validation
        const basicFields = [
            { id: '#OrgName', errorId: '#OrgNameError' },
            { id: '#OrgPAN', errorId: '#OrgPANError' },
            { id: '#orgAddressOrg', errorId: '#orgAddressOrgError' },
            { id: '#OrgNameAuthSign', errorId: '#OrgNameAuthSignError' },
            { id: '#authsignatory_mobile', errorId: '#authsignatory_mobileError' },
            { id: '#emailauthsignatory', errorId: '#emailauthsignatoryError' },
            { id: '#orgAadharAuth', errorId: '#orgAadharAuthError' },
        ];

        basicFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);

            // On change or input, hide the error message
            inputField.on('input', function () {
                if (inputField.val().trim() !== "") {
                    inputField.removeClass('required');
                    errorField.hide();
                }
            });

            if (inputField.val().trim() === "") {
                inputField.addClass('required');
                errorField.text("This field is required").show();
                isValid = false;
                if (firstInvalidField === null) {
                    firstInvalidField = inputField;
                }
            } else {
                inputField.removeClass('required');
                errorField.hide();
            }
        });

        // Special validation for #orgAddressOrg (address field)
        // Special validation for #orgAddressOrg (address field)
        const orgAddressOrg = $('#orgAddressOrg');
        const orgAddressOrgError = $('#orgAddressOrgError');
        const orgAddressValue = orgAddressOrg.val().trim();
        const addressRegex = /^[a-zA-Z0-9\s,#\-()/]*$/;  // Regex allowing specific characters

        // Reset error state
        orgAddressOrg.removeClass('required');
        orgAddressOrgError.hide();

        // Required field validation
        if (orgAddressValue.length === 0) {
            orgAddressOrg.addClass('required');
            orgAddressOrgError.text("This field is required").show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = orgAddressOrg;
            }
        }
        // Maxlength validation (200 characters) and regex validation
        else if (orgAddressValue.length > 200) {
            orgAddressOrg.addClass('required');
            orgAddressOrgError.text("Address cannot exceed 200 characters").show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = orgAddressOrg;
            }
        }
        // Regex validation for allowed characters
        else if (!addressRegex.test(orgAddressValue)) {
            orgAddressOrg.addClass('required');
            orgAddressOrgError.text("Only letters, digits, hyphen (-), comma (,), hash (#), parenthesis ( ), forward slash (/), and spaces are allowed").show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = orgAddressOrg;
            }
        }



        // Mobile number validation
        const $Orgmobile = $('#authsignatory_mobile');
        const $OrgMobileError = $('#OrgMobileAuthError');
        const OrgMobileValue = $Orgmobile.val().trim();
        const dataIdValue = $Orgmobile.attr('data-id');

        // Validate mobile number input
        if (OrgMobileValue === '') {
            $OrgMobileError.text('Mobile Number is required');
            $OrgMobileError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $Orgmobile;
            }
        } else if (OrgMobileValue.length !== 10) {
            $OrgMobileError.text('Mobile Number must be exactly 10 digit');
            $OrgMobileError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $Orgmobile;
            }
        } else if (dataIdValue === "0") {
            $OrgMobileError.text('Please verify your mobile number');
            $OrgMobileError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $Orgmobile;
            }
        } else if (dataIdValue === "1") {
            $OrgMobileError.hide();
        } else {
            $OrgMobileError.hide();
        }

        // Email validation
        const $OrgEmail = $('#emailauthsignatory');
        const $OrgEmailError = $('#OrgEmailAuthSignError');
        const OrgEmailValue = $OrgEmail.val().trim();
        const dataIdEmailValue = $OrgEmail.attr('data-id');

        // Validate mobile number input
        if (OrgEmailValue === '') {
            $OrgEmailError.text('Email is required');
            $OrgEmailError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $OrgEmail;
            }
        } else if (dataIdEmailValue === "0") {
            $OrgEmailError.text('Please verify your email');
            $OrgEmailError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $OrgEmail;
            }
        } else if (dataIdEmailValue === "1") {
            $OrgEmailError.hide();
        } else {
            $OrgEmailError.hide();
        }

        // PAN Number Validation
        const $OrgPAN = $('#OrgPAN');
        const $OrgPANError = $('#OrgPANError');
        const OrgPANValue = $OrgPAN.val().trim();

        // Validate PAN number input length
        if (OrgPANValue === '') {
            $OrgPAN.addClass('required');
            $OrgPANError.text('This field is required').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $OrgPAN;
            }
        } else if (OrgPANValue.length !== 10) {
            $OrgPAN.addClass('required');
            $OrgPANError.text('PAN Number must be exactly 10 characters').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $OrgPAN;
            }
        } else {
            $OrgPAN.removeClass('required');
            $OrgPANError.hide();
        }

        // Aadhaar Number Validation
        const $orgAadharAuth = $('#orgAadharAuth');
        const $orgAadharAuthError = $('#orgAadharAuthError');
        const orgAadharAuthValue = $orgAadharAuth.val().trim();

        // Validate PAN number input length
        if (orgAadharAuthValue === '') {
            $orgAadharAuth.addClass('required');
            $orgAadharAuthError.text('This field is required').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $orgAadharAuth;
            }
        } else if (orgAadharAuthValue.length !== 12) {
            $orgAadharAuth.addClass('required');
            $orgAadharAuthError.text('Aadhar Number must be exactly 12 digit').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $orgAadharAuth;
            }
        } else {
            $orgAadharAuth.removeClass('required');
            $orgAadharAuthError.hide();
        }
        // Address details validation based on checkbox
        if ($('#YesOrg').is(':checked')) {
            const addressFieldsYes = [
                { id: '#localityOrgFill', errorId: '#localityOrgFillError' },
                { id: '#blocknoOrgFill', errorId: '#blocknoOrgFillError' },
                { id: '#plotnoOrgFill', errorId: '#plotnoOrgFillError' },
                { id: '#landUseOrgFill', errorId: '#landUseOrgFillError' },
                { id: '#landUseSubtypeOrgFill', errorId: '#landUseSubtypeOrgFillError' }
            ];

            addressFieldsYes.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);

                // On change or input, hide the error message
                inputField.on('input', function () {
                    if (inputField.val().trim() !== "") {
                        inputField.removeClass('required');
                        errorField.hide();
                    }
                });


                if (inputField.val().trim() === "") {
                    inputField.addClass('required');
                    errorField.text("This field is required").show();
                    isValid = false;
                    if (firstInvalidField === null) {
                        firstInvalidField = inputField;
                    }
                } else {
                    inputField.removeClass('required');
                    errorField.hide();
                }
            });
        } else {

            const addressFieldsNo = [
                { id: '#locality_org', errorId: '#locality_orgError' },
                { id: '#block_org', errorId: '#block_orgError' },
                { id: '#plot_org', errorId: '#plot_orgError' },
                // {id: '#knownas_org', errorId: '#knownas_orgError'},
                { id: '#landUse_org', errorId: '#landUse_orgError' },
                { id: '#landUseSubtype_org', errorId: '#landUseSubtype_orgError' }
            ];

            addressFieldsNo.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);

                // On change or input, hide the error message
                inputField.on('input', function () {
                    if (inputField.val().trim() !== "") {
                        inputField.removeClass('required');
                        errorField.hide();
                    }
                });


                if (inputField.val().trim() === "") {
                    inputField.addClass('required');
                    errorField.text("This field is required").show();
                    isValid = false;
                    if (firstInvalidField === null) {
                        firstInvalidField = inputField;
                    }
                } else {
                    inputField.removeClass('required');
                    errorField.hide();
                }
            });
        }

        const fileFields = [
            { id: '#OrgSaleDeedDoc', errorId: '#OrgSaleDeedDocError' },
            { id: '#OrgBuildAgreeDoc', errorId: '#OrgBuildAgreeDocError' },
            { id: '#OrgLeaseDeedDoc', errorId: '#OrgLeaseDeedDocError' },
            { id: '#OrgSubMutDoc', errorId: '#OrgSubMutDocError' },
            { id: '#OrgOther', errorId: '#OrgOtherError' }
        ];

        let isValidFiles = true;
        let firstInvalidFieldFiles = null;
        let atLeastOneFile = false;

        // Resetting error states
        fileFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);
            inputField.removeClass('required');
            errorField.hide();

            // On change (when files are selected), validate the files
            inputField.on('change', function () {
                const files = inputField[0].files;
                if (files.length > 0) {
                    let allFilesArePDF = true;

                    for (let i = 0; i < files.length; i++) {
                        const fileName = files[i].name;
                        // Check if the file is a PDF
                        if (!fileName.endsWith('.pdf')) {
                            allFilesArePDF = false;
                            break;
                        }
                    }

                    if (allFilesArePDF) {
                        inputField.removeClass('required');
                        errorField.hide();
                    } else {
                        inputField.addClass('required');
                        errorField.text("Only PDF files are allowed").show();
                    }
                }
            });
        });

        fileFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);
            const files = inputField[0].files;

            // Check if there are files in this input
            if (files.length > 0) {
                atLeastOneFile = true;

                let allFilesArePDF = true;
                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;
                    if (!fileName.endsWith('.pdf')) {
                        allFilesArePDF = false;
                        break;
                    }
                }

                // Show error message if not all files are PDF
                if (!allFilesArePDF) {
                    inputField.addClass('required');
                    errorField.text("Only PDF files are allowed").show();
                    isValidFiles = false;
                    isValid = false;
                    if (firstInvalidFieldFiles === null) {
                        firstInvalidFieldFiles = inputField;
                    }
                }
            }
        });


        // Special validation for mandatory field #OrgSignAuthDoc (must always be filled)
        const OrgSignAuthField = $('#OrgSignAuthDoc');
        const OrgSignAuthDocError = $('#OrgSignAuthDocError');
        OrgSignAuthField.removeClass('required');  // Reset error state
        OrgSignAuthDocError.hide();


        // On change (when files are selected), validate the files
        OrgSignAuthField.on('change', function () {
            if (OrgSignAuthField[0].files.length === 0) {
                OrgSignAuthField.addClass('required');
                OrgSignAuthDocError.text("This field is mandatory. Upload a PDF file").show();
            } else {
                const files = OrgSignAuthField[0].files;
                let allFilesArePDF = true;

                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;
                    if (!fileName.endsWith('.pdf')) {
                        allFilesArePDF = false;
                        break;
                    }
                }

                if (allFilesArePDF) {
                    OrgSignAuthField.removeClass('required');
                    OrgSignAuthDocError.hide();
                } else {
                    OrgSignAuthField.addClass('required');
                    OrgSignAuthDocError.text("Only PDF files are allowed").show();
                }
            }
        });

        if (OrgSignAuthField[0].files.length === 0) {
            OrgSignAuthField.addClass('required');
            OrgSignAuthDocError.text("This field is mandatory. Upload a PDF file").show();
            isValidFiles = false;
            if (firstInvalidFieldFiles === null) {
                firstInvalidFieldFiles = OrgSignAuthField;
            }
        } else {
            const files = OrgSignAuthField[0].files;
            let allFilesArePDF = true;
            for (let i = 0; i < files.length; i++) {
                const fileName = files[i].name;
                if (!fileName.endsWith('.pdf')) {
                    allFilesArePDF = false;
                    break;
                }
            }

            if (!allFilesArePDF) {
                OrgSignAuthField.addClass('required');
                OrgSignAuthDocError.text("Only PDF files are allowed").show();
                isValidFiles = false;
                if (firstInvalidFieldFiles === null) {
                    firstInvalidFieldFiles = OrgSignAuthField;
                }
            }
        }

        // Special validation for mandatory field #scannedIDOrg (must always be filled)
        const scannedIDOrg = $('#scannedIDOrg');
        const scannedIDOrgError = $('#scannedIDOrgError');
        scannedIDOrg.removeClass('required');  // Reset error state
        scannedIDOrgError.hide();

        // On change (when files are selected), validate the files
        scannedIDOrg.on('change', function () {
            if (scannedIDOrg[0].files.length === 0) {
                scannedIDOrg.addClass('required');
                scannedIDOrgError.text("This field is mandatory. Upload a PDF file").show();
            } else {
                const files = scannedIDOrg[0].files;
                let allFilesArePDF = true;

                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name;
                    // Check if the file is a PDF
                    if (!fileName.endsWith('.pdf')) {
                        allFilesArePDF = false;
                        break;
                    }
                }

                if (allFilesArePDF) {
                    scannedIDOrg.removeClass('required');
                    scannedIDOrgError.hide();
                } else {
                    scannedIDOrg.addClass('required');
                    scannedIDOrgError.text("Only PDF files are allowed").show();
                }
            }
        });

        if (scannedIDOrg[0].files.length === 0) {
            scannedIDOrg.addClass('required');
            scannedIDOrgError.text("This field is mandatory. Upload a PDF file").show();
            isValidFiles = false;
            if (firstInvalidFieldFiles === null) {
                firstInvalidFieldFiles = scannedIDOrg;
            }
        } else {
            const files = scannedIDOrg[0].files;
            let allFilesArePDF = true;
            for (let i = 0; i < files.length; i++) {
                const fileName = files[i].name;
                if (!fileName.endsWith('.pdf')) {
                    allFilesArePDF = false;
                    break;
                }
            }

            if (!allFilesArePDF) {
                scannedIDOrg.addClass('required');
                scannedIDOrgError.text("Only PDF files are allowed").show();
                isValidFiles = false;
                if (firstInvalidFieldFiles === null) {
                    firstInvalidFieldFiles = scannedIDOrg;
                }
            }
        }

        // Check if at least one file is selected
        if (!atLeastOneFile) {
            fileFields.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);
                inputField.addClass('required');
                errorField.text("At least one file is required").show();
                isValidFiles = false;
                isValid = false;
                if (firstInvalidFieldFiles === null) {
                    firstInvalidFieldFiles = inputField;
                }
            });
        }

        // if (firstInvalidFieldFiles !== null) {
        //     firstInvalidFieldFiles.focus();
        // }


        // Agreement checkbox validation
        const consentField = $('#OrgConsent');
        const consentErrorField = $('#OrgConsentError');
        if (!consentField.is(':checked')) {
            consentField.addClass('required');
            consentErrorField.text("You must agree to the terms").show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = consentField;
            }
        } else {
            consentField.removeClass('required');
            consentErrorField.hide();
        }

        if (firstInvalidField !== null) {
            firstInvalidField.focus();
        }

        return isValid;
    }

});
