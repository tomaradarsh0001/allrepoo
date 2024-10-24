const appointmentDateElement = document.getElementById('app_appointment_date');
const timeSlotDiv = document.getElementById('app_timeSlotDiv');
const meetingTimeElement = document.getElementById('app_meeting_time');

if (appointmentDateElement) {
    let fullyBookedDates = [];
    let fullyBookedWeeks = [];
    let availableWeeks = [];
    const maxWeeksAvailable = 4;

    // Fetch fully booked dates and weeks
    fetch('/appointments/get-fully-booked-dates')
        .then(response => response.json())
        .then(data => {
            const today = new Date(); // Define today's date
            const todayDay = today.getDay(); // Get current day of the week (0 = Sunday, 6 = Saturday)

            // Filter out past fully booked dates
            fullyBookedDates = data.fullyBookedDates.filter(dateString => {
                const date = new Date(dateString);
                return date >= today; // Only keep future fully booked dates
            }).map(dateString => {
                const date = new Date(dateString);
                const options = { timeZone: 'Asia/Kolkata', year: 'numeric', month: '2-digit', day: '2-digit' };
                return new Intl.DateTimeFormat('en-CA', options).format(date);
            });

            const weekBookings = {};

            // Loop over each fully booked date
            fullyBookedDates.forEach(dateString => {
                const date = new Date(dateString);
                const weekNumber = getStandardWeekNumber(date); // Calculate the week number
                const dayOfWeek = date.getDay(); // Get the day of the week (0 = Sunday, 6 = Saturday)

                // Only consider Wednesday (3), Thursday (4), and Friday (5)
                if (dayOfWeek >= 3 && dayOfWeek <= 5) {
                    if (!weekBookings[weekNumber]) {
                        weekBookings[weekNumber] = new Set();
                    }
                    weekBookings[weekNumber].add(dayOfWeek); // Add the day to the week
                }
            });

            console.log("Fully booked dates:", fullyBookedDates);

            // Now, mark a week as fully booked if it contains all three days (Wednesday, Thursday, Friday)
            const currentWeekNumber = getStandardWeekNumber(today); // Calculate current week number

            fullyBookedWeeks = Object.keys(weekBookings).filter(weekNumber => {
                const daysBooked = weekBookings[weekNumber];

                if (weekNumber == currentWeekNumber) {
                    // Special logic for the current week:
                    // Check if 3, 4, and 5 are either fully booked or are past eligible days
                    const wednesday = new Date(today);
                    const thursday = new Date(today);
                    const friday = new Date(today);

                    wednesday.setDate(today.getDate() - today.getDay() + 3); // Get Wednesday's date
                    thursday.setDate(today.getDate() - today.getDay() + 4); // Get Thursday's date
                    friday.setDate(today.getDate() - today.getDay() + 5); // Get Friday's date

                    // Print the calculated dates for Wednesday, Thursday, and Friday
                    // console.log("Wednesday's date:", wednesday.toDateString());
                    // console.log("Thursday's date:", thursday.toDateString());
                    // console.log("Friday's date:", friday.toDateString());

                    const isCurrentWeekFullyBooked = (
                        (daysBooked.has(3) || isPastEligibleDay(wednesday, today)) &&
                        (daysBooked.has(4) || isPastEligibleDay(thursday, today)) &&
                        (daysBooked.has(5) || isPastEligibleDay(friday, today))
                    );

                    return isCurrentWeekFullyBooked;
                }

                // For future weeks, the original logic remains:
                return daysBooked.has(3) && daysBooked.has(4) && daysBooked.has(5); // Fully booked future week
            });

            console.log("Fully booked weeks:", fullyBookedWeeks);


            // Filter out past weeks and fully booked weeks, and ensure at least 4 available weeks
            availableWeeks = calculateAvailableWeeks(today, todayDay, fullyBookedWeeks, maxWeeksAvailable);
            console.log("Available weeks:", availableWeeks);

            // Initialize Flatpickr with updated logic
            flatpickr(appointmentDateElement, {
                dateFormat: "Y-m-d",
                minDate: "today",  // Prevents booking for past dates including today
                enable: [
                    function (date) {
                        const weekNumber = getStandardWeekNumber(date);
                        const year = date.getFullYear();
                        const month = ("0" + (date.getMonth() + 1)).slice(-2);
                        const day = ("0" + date.getDate()).slice(-2);
                        const dateString = `${year}-${month}-${day}`;

                        // Disable current and past dates
                        if (date < today) {
                            return false;
                        }

                        // Only enable valid days (Wednesday, Thursday, Friday)
                        const isValidDay = date.getDay() === 3 || date.getDay() === 4 || date.getDay() === 5;
                        const isFullyBooked = fullyBookedDates.includes(dateString);
                        const isWeekEnabled = availableWeeks.includes(weekNumber);

                        // Ensure the date is in the future and matches available weeks/days
                        return isValidDay && !isFullyBooked && isWeekEnabled && date >= today;
                    }
                ],
                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    const date = new Date(dayElem.dateObj);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Normalize today's date

                    const year = date.getFullYear();
                    const month = ("0" + (date.getMonth() + 1)).slice(-2);
                    const day = ("0" + date.getDate()).slice(-2);
                    const dateString = `${year}-${month}-${day}`;

                    // Remove past-eligible-day class if it's a past week
                    // if (getStandardWeekNumber(date) < getStandardWeekNumber(today)) {
                    //     dayElem.classList.remove('past-eligible-day');
                    // }

                    // If the date is in the past or today and not bookable anymore
                    if (date <= today && isPastEligibleDay(date, today)) {
                        dayElem.classList.add('past-eligible-day');
                    }

                    // Remove 'fully-booked-date' class and add 'flatpickr-disabled' if the date is in the past
                    if (date < today) {
                        dayElem.classList.remove('fully-booked-date');
                        dayElem.classList.add('flatpickr-disabled');
                        dayElem.disabled = true;
                    }

                    // Add 'fully-booked-date' class for fully booked future dates
                    if (fullyBookedDates.includes(dateString)) {
                        dayElem.classList.add('fully-booked-date');
                    }

                    // Add 'available-date' class for available dates
                    const weekNumber = getStandardWeekNumber(date);
                    const isValidDay = date.getDay() === 3 || date.getDay() === 4 || date.getDay() === 5;
                    const isFullyBooked = fullyBookedDates.includes(dateString);
                    const isWeekEnabled = availableWeeks.includes(weekNumber);

                    if (isValidDay && !isFullyBooked && isWeekEnabled && date >= today) {
                        dayElem.classList.add('available-date');
                    }
                },
                onChange: function (selectedDates, dateStr) {
                    if (dateStr) {
                        fetchAvailableTimeSlots(dateStr);
                    }
                }
            });

        })
        .catch(error => {
            console.error('Error fetching fully booked dates:', error);
        });
}

// Check if the day is a past eligible day
function isPastEligibleDay(date, today) {
    const currentWeekDay = today.getDay();
    const dateWeekDay = date.getDay();
    const isEligibleDay = [3, 4, 5].includes(dateWeekDay);
    const isTodayOrPast = date <= today;

    // Check if the date is in the same week as today
    let startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - currentWeekDay + (currentWeekDay === 0 ? -6 : 1));
    let endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    return isEligibleDay && isTodayOrPast && date >= startOfWeek && date <= endOfWeek;
}


function fetchAvailableTimeSlots(date) {
    fetch(`/appointments/get-available-time-slots?date=${date}`)
        .then(response => response.json())
        .then(data => {
            meetingTimeElement.innerHTML = '<option value="">Select a time slot</option>';

            if (data.length > 0) {
                data.forEach(slot => {
                    let option = document.createElement('option');
                    option.value = slot;
                    option.textContent = slot;
                    meetingTimeElement.appendChild(option);
                });
                timeSlotDiv.style.display = 'block';
            } else {
                timeSlotDiv.style.display = 'none';
            }
        })
        .catch(error => console.error('Error fetching time slots:', error));
}

// Function to calculate the standard week number (Monday-Sunday)
function getStandardWeekNumber(date) {
    const tempDate = new Date(date);
    const startOfYear = new Date(tempDate.getFullYear(), 0, 1); // January 1st
    const diffInTime = tempDate - startOfYear;
    const diffInDays = Math.floor(diffInTime / (1000 * 60 * 60 * 24)) + 1;
    const dayOfWeek = startOfYear.getDay();
    const startOffset = (dayOfWeek === 0) ? 1 : 0; // If it's Sunday, adjust to Monday
    const weekNumber = Math.ceil((diffInDays + dayOfWeek - startOffset) / 7);
    return weekNumber;
}

// Function to calculate available weeks (excluding past and fully booked weeks)
function calculateAvailableWeeks(today, todayDay, fullyBookedWeeks, maxWeeksAvailable) {
    let enabledWeeks = [];
    let weekIndex = 0;

    // If today is Friday or later, skip the current week (week of todayDay > 4)
    if (todayDay >= 5) {
        weekIndex = 1; // Start from next week
    }

    const todayWeekNumber = getStandardWeekNumber(today);

    // Continue adding weeks until we have maxWeeksAvailable
    while (enabledWeeks.length < maxWeeksAvailable) {
        const weekNumber = todayWeekNumber + weekIndex;

        // Calculate the start and end of the week (Monday to Sunday)
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() + (weekIndex * 7) - today.getDay()); // Get the start date of the week (Monday)

        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6); // End of week (Sunday)

        // Exclude fully booked weeks and past weeks
        if (!fullyBookedWeeks.includes(String(weekNumber)) && endOfWeek >= today) {
            enabledWeeks.push(weekNumber);
        }

        weekIndex++;
    }
    return enabledWeeks;
}



// OTP Input Management Logic
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

// Initialize setupForm for OTP forms
setupForm('app-otp-form', '#appVerifyMobileOtpBtn');
setupForm('app-otp-form-email', '#appVerifyEmailOtpBtn');
var app_meetingPurpose = document.getElementById("app_meetingPurpose");
app_meetingPurpose.addEventListener('change', function () {
    var meetingDescriptionDiv = document.getElementById('app_meetingDescriptionDiv');
    meetingDescriptionDiv.style.display = this.value !== "" ? 'block' : 'none';
});
// Appointment Form Validation

$(document).ready(function () {

    $('#app_AppointmentSubmitButton').click(function (event) {
        event.preventDefault();
        var form = document.getElementById('appointment_form')
        if (validateForm()) {
            form.submit();
        }
    });

    function toggleAddressFields() {
        if ($('#app_Yes').is(':checked')) {
            $('#app_ifyes').show();
            $('#app_ifYesNotChecked').hide();
        } else {
            $('#app_ifyes').hide();
            $('#app_ifYesNotChecked').show();
        }
    }

    toggleAddressFields();
    $('#app_Yes').change(function () {
        toggleAddressFields();
    });

    // Stack Holder
    function toggleStackHolderFields() {
        if ($('#app_isStakeholder').is(':checked')) {
            $('#app_ifStakeholder').show();
        } else {
            $('#app_ifStakeholder').hide();
        }
    }
    toggleStackHolderFields();
    $('#app_isStakeholder').change(function () {
        toggleStackHolderFields();
    });

    function validateForm() {
        let firstInvalidField = null;
        let isValid = true;

        const basicFields = [
            { id: '#app_fullname', errorId: '#app_fullnameError' },
            { id: '#app_mobile', errorId: '#app_mobileError' },
            { id: '#app_email', errorId: '#app_emailError' },
            { id: '#app_pan_number', errorId: '#app_panNumberError' },
        ];

        basicFields.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);

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
        const $AppMobile = $('#app_mobile');
        const $AppMobileError = $('#app_mobileError');
        const dataIdValue = $AppMobile.attr('data-id');

        // Function to validate the mobile number
        function validateMobileNumber() {
            const AppMobileValue = $AppMobile.val().trim();

            // Validate mobile number input
            if (AppMobileValue === '') {
                $AppMobileError.text('Mobile Number is required');
                $AppMobileError.show();
                isValid = false;
                if (firstInvalidField === null) {
                    firstInvalidField = $AppMobile;
                }
            } else if (AppMobileValue.length !== 10) {
                $AppMobileError.text('Mobile Number must be exactly 10 digits');
                $AppMobileError.show();
                isValid = false;
                if (firstInvalidField === null) {
                    firstInvalidField = $AppMobile;
                }
            } else if (dataIdValue === "0") {
                $AppMobileError.text('Please verify your mobile number');
                $AppMobileError.show();
                isValid = false;
                if (firstInvalidField === null) {
                    firstInvalidField = $AppMobile;
                }
            } else if (dataIdValue === "1") {
                $AppMobileError.hide();
            } else {
                $AppMobileError.hide();
            }
        }

        // Initial validation
        validateMobileNumber();

        // Add input event listener
        $AppMobile.on('input', function () {
            // On input change, validate again and remove error messages if valid
            if ($AppMobile.val().trim() !== '') {
                // Reset the firstInvalidField when the input is valid
                firstInvalidField = null;
            }
            validateMobileNumber();
        });


        // Email validation
        const $AppEmail = $('#app_email');
        const $AppEmailError = $('#app_emailError');
        const AppEmailValue = $AppEmail.val().trim();
        const dataIdEmailValue = $AppEmail.attr('data-id');

        // Validate mobile number input
        if (AppEmailValue === '') {
            $AppEmailError.text('Email is required');
            $AppEmailError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $AppEmail;
            }
        } else if (dataIdEmailValue === "0") {
            $AppEmailError.text('Please verify your email');
            $AppEmailError.show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $AppEmail;
            }
        } else if (dataIdEmailValue === "1") {
            $AppEmailError.hide();
        } else {
            $AppEmailError.hide();
        }

        // PAN Number Validation
        const $AppPanNumber = $('#app_pan_number');
        const $AppPanNumberError = $('#app_panNumberError');
        const AppPanNumberValue = $AppPanNumber.val().trim();

        // Validate PAN number input length
        if (AppPanNumberValue === '') {
            $AppPanNumber.addClass('required');
            $AppPanNumberError.text('This field is required').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $AppPanNumber;
            }
        } else if (AppPanNumberValue.length !== 10) {
            $AppPanNumber.addClass('required');
            $AppPanNumberError.text('PAN Number must be exactly 10 characters').show();
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $AppPanNumber;
            }
        } else {
            $AppPanNumber.removeClass('required');
            $AppPanNumberError.hide();
        }

        // Address details validation based on checkbox
        if ($('#app_Yes').is(':checked')) {
            const addressFieldsYes = [
                { id: '#app_localityFill', errorId: '#app_localityFillError' },
                { id: '#app_blocknoFill', errorId: '#app_blocknoFillError' },
                { id: '#app_plotnoFill', errorId: '#app_plotnoFillError' },
            ];

            addressFieldsYes.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);

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
                { id: '#app_locality', errorId: '#app_localityError' },
                { id: '#app_block', errorId: '#app_blockError' },
                { id: '#app_plot', errorId: '#app_plotError' }
            ];

            addressFieldsNo.forEach(function (field) {
                const inputField = $(field.id);
                const errorField = $(field.errorId);

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

        if ($('#app_isStakeholder').is(':checked')) {
            const $AppStakeholderProof = $('#app_stakeholderProof');
            const $AppStakeholderProofError = $('#app_stakeholderProofError');

            // Function to validate file input
            function validateStakeholderProof() {
                const files = $AppStakeholderProof[0].files;
                let atLeastOneFile = false;
                let allFilesArePDF = true;

                // Check if at least one file is selected
                if (files.length > 0) {
                    atLeastOneFile = true;
                    // Validate the file extension
                    for (let i = 0; i < files.length; i++) {
                        const fileName = files[i].name;
                        if (!fileName.endsWith('.pdf')) {
                            allFilesArePDF = false;
                            break;
                        }
                    }
                }

                // Validate the file input
                if (!atLeastOneFile) {
                    $AppStakeholderProof.addClass('required');
                    $AppStakeholderProofError.text("File is required").show();
                    isValid = false;
                    if (firstInvalidField === null) {
                        firstInvalidField = $AppStakeholderProof;
                    }
                } else if (!allFilesArePDF) {
                    $AppStakeholderProof.addClass('required');
                    $AppStakeholderProofError.text("Only PDF file is allowed").show();
                    isValid = false;
                    if (firstInvalidField === null) {
                        firstInvalidField = $AppStakeholderProof;
                    }
                } else {
                    $AppStakeholderProof.removeClass('required');
                    $AppStakeholderProofError.hide();
                }
            }

            // Initial validation
            validateStakeholderProof();

            // Add change event listener to validate when files are selected
            $AppStakeholderProof.on('change', function () {
                // Reset the firstInvalidField when the input changes
                firstInvalidField = null;
                validateStakeholderProof();
            });
        }


        const natureVisits = [
            { id: '#app_natureOfVisit', errorId: '#app_natureOfVisitError' },
            { id: '#app_meetingPurpose', errorId: '#app_meetingPurposeError' },
            { id: '#app_appointment_date', errorId: '#app_appointmentDateError' }
        ];

        natureVisits.forEach(function (field) {
            const inputField = $(field.id);
            const errorField = $(field.errorId);

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

        // Meeting Description Required
        const $meetingPurpose = $('#app_meetingPurpose');
        const $meetingDescription = $('#app_meetingDescription');
        const $meetingDescriptionError = $('#app_meetingDescriptionError');

        // Function to validate meeting description based on meeting purpose
        function validateMeetingDescription() {
            const meetingPurposeValue = $meetingPurpose.val().trim();
            const meetingDescriptionValue = $meetingDescription.val().trim();

            // Validate Description on Behalf of Purpose
            if (meetingPurposeValue !== '' && meetingDescriptionValue === '') {
                $meetingDescriptionError.text('Meeting Description is required');
                $meetingDescriptionError.show();
                $meetingDescription.addClass('required');
                isValid = false;
                if (firstInvalidField === null) {
                    firstInvalidField = $meetingDescription;
                }
            } else {
                $meetingDescriptionError.hide();
                $meetingDescription.removeClass('required');
            }
        }

        // Initial validation
        validateMeetingDescription();

        // Add event listeners for input changes
        $meetingPurpose.on('input', validateMeetingDescription);
        $meetingDescription.on('input', function () {
            // When the user types in the meeting description, check if the error can be removed
            if ($meetingDescription.val().trim() !== '') {
                firstInvalidField = null; // Reset if this field becomes valid
            }
            validateMeetingDescription();
        });


        // Meeting Description Required
        const $appointmentDate = $('#app_appointment_date');
        const $appointmentTime = $('#app_meeting_time');
        const appointmentDateValue = $appointmentDate.val().trim();
        const appointmentTimeValue = $appointmentTime.val().trim();
        const $appointmentTimeError = $('#app_meetingTimeError');
        // Validate Description on Behalf of Purpose
        if (appointmentDateValue !== '' && appointmentTimeValue === '') {
            $appointmentTimeError.text('Time Slot is required');
            $appointmentTimeError.show();
            $appointmentTime.addClass('required');
            isValid = false;
            if (firstInvalidField === null) {
                firstInvalidField = $appointmentTime;
            }
        } else {
            $appointmentTimeError.hide();
            $appointmentTime.removeClass('required');
        }

        // This is focus on invalid fields
        if (firstInvalidField !== null) {
            firstInvalidField.focus();
        }

        return isValid;
    }

});