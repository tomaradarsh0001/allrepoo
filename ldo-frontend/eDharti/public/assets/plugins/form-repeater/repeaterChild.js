class MultipleDivRepeater {
    static count = 1;
    constructor(containerId) {
        this.container = document.getElementById(containerId);

        // Add event listener for adding parent
        this.container.addEventListener('click', this.addParent.bind(this));

        // Add event listener for adding child
        this.container.addEventListener('click', this.addChild.bind(this));
        this.count = 1;
    }
    // Function to validate input fields
    validateInputFields(parentContainer) {
        let isValid = true;
        const inputs = parentContainer.querySelectorAll('.form-required');
        inputs.forEach(input => {
            // Example validation: Check if the input is not empty
            if (input.value.trim() === '') {
                isValid = false;
                const errorDiv = input.parentElement.querySelector('.text-danger');
                errorDiv.textContent = 'This field is required';
            } else {
                const errorDiv = input.parentElement.querySelector('.text-danger');
                errorDiv.textContent = ''; // Clear previous error message if any
            }
        });
        return isValid;
    }

    addParent(event) {
        if (event.target.classList.contains('add-parent-button')) {
            const parentContainer = document.createElement('div');
            parentContainer.id = this.count;
            parentContainer.classList.add('parent-container');
            parentContainer.innerHTML = `
            <div class="text-align-right">
            <button type="button" class="delete-parent-button btn btn-outline-danger"><i class="fadeIn animated bx bx-trash"></i> Delete</button>
            </div>
        <div class="row mb-3">
        <div class="col-12 col-lg-4 my-4">
            <label for="ProcessTransfer"
                class="form-label">Process of transfer</label>
            <select name="land_transfer_type[]" class="form-select processtransfer form-required" data-name="processtransfer"
                id="ProcessTransfer"
                aria-label="Type of Lease">
                <option value="" selected>Select</option>
                <option value="Substitution">Substitution</option>
                <option value="Mutation">Mutation</option>
                <option value="Substitution cum Mutation">Substitution cum Mutation</option>
                <option value="Mutation cum Substitution">Mutation cum Substitution</option>
                <option value="Successor in interest">Successor in interest</option>
                <option value="Others">Others</option>
            </select>
            <div id="ProcessTransferError" class="text-danger"></div>
        </div>
        <div class="col-12 col-lg-4 my-4">
            <label for="transferredDate"
                class="form-label">Date</label>
            <input type="date"
            name="transferDate[]"
                class="form-control form-required"
                id="transferredDate">
                <div id="transferredDateError" class="text-danger"></div>
        </div>
    </div>
          <button type="button" class="add-button btn btn-outline-secondary" id="addLesseeBtn"><i class="fadeIn animated bx bx-plus"></i> Add Lessee Details</button>
          <div id="addLesseeBtnError" class="text-danger" style="display: block;">Please click on Add Lessee Button</div>
        `;
            this.count = this.count + 1;
            this.container.insertBefore(parentContainer, event.target);
        }
        else if (event.target.classList.contains('delete-parent-button')) {
            const parentContainer = event.target.closest('.parent-container');
            parentContainer.remove();
        }
    }

    addChild(event) {
        if (event.target.classList.contains('add-button')) {
            $(function () {
                $(".alpha-only").keypress(function (event) {
                    var charCode = event.which;
                    if (
                        (charCode < 65 || (charCode > 90 && charCode < 97) || charCode > 122) &&
                        charCode !== 32 &&
                        charCode !== 46 &&
                        charCode !== 47
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

                $('.pan_number_format').on('keypress', function (event) {
                    var charCode = event.which;

                    if (charCode === 0 || charCode === 8 || charCode === 9 || charCode === 13) {
                        return;
                    }

                    var charStr = String.fromCharCode(charCode).toUpperCase();

                    var currentLength = $(this).val().length;

                    if (currentLength < 5 && !/[A-Z]/.test(charStr)) {
                        event.preventDefault();
                    }

                    else if (currentLength >= 5 && currentLength < 9 && !/[0-9]/.test(charStr)) {
                        event.preventDefault();
                    }

                    else if (currentLength === 9 && !/[A-Z]/.test(charStr)) {
                        event.preventDefault();
                    }
                });

            });

            var nearestDivId = $(event.target).closest('div').attr('id');
            if (nearestDivId == undefined) {
                nearestDivId = 0;
            }
            console.log("Nearrest Div Id: " + nearestDivId);
            const parentContainer = event.target.closest('.parent-container');
            const childItem = document.createElement('div');
            childItem.classList.add('child-item');
            childItem.innerHTML = `
        <div class="duplicate-field-tab">
        <div class="items1"
            data-group="test">
            <div
                class="item-content row">
                <div
                    class="col-lg-4 mb-3">
                    <label
                        for="name"
                        class="form-label">Name</label>
                    <input
                        type="text"
                        name="name${nearestDivId}[]"
                        class="form-control form-required alpha-only"
                        id="name"
                        placeholder="Name"
                        data-name="name">
                        <div id="nameError" class="text-danger"></div>
                </div>
                <div
                    class="col-lg-4 mb-3">
                    <label for="age"
                        class="form-label">Age</label>
                    <input
                        type="text"
                        name="age${nearestDivId}[]"
                        class="form-control numericOnly"
                        id="age"
                        placeholder="Age"
                        maxlength="3"
                        data-name="age">
                        <div id="ageError" class="text-danger"></div>
                </div>
                <div
                    class="col-lg-4 mb-3">
                    <label
                        for="share"
                        class="form-label">Share</label>
                    <input
                        type="text"
                        class="form-control form-required"
                        id="share"
                        name="share${nearestDivId}[]"
                        placeholder="Share"
                        data-name="share">
                        <div id="shareError" class="text-danger"></div>
                </div>
                <div
                    class="col-lg-4 mb-3">
                    <label
                        for="pannumber"
                        class="form-label">PAN
                        Number</label>
                    <input
                        type="text"
                        class="form-control text-uppercase pan_number_format"
                        id="pannumber"
                        name="panNumber${nearestDivId}[]"
                        placeholder="PAN Number"
                        maxlength="10"
                        data-name="pannumber">
                        <div id="pannumberError" class="text-danger"></div>
                </div>
                <div
                    class="col-lg-4 mb-3">
                    <label
                        for="aadharnumber"
                        class="form-label">Aadhar
                        Number</label>
                    <input
                        type="text"
                        class="form-control numericOnly"
                        id="aadharnumber"
                        name="aadharNumber${nearestDivId}[]"
                        placeholder="Aadhar Number"
                        data-name="aadharnumber"
                        maxlength="12">
                        <div id="aadharnumberError" class="text-danger"></div>
                </div>
            </div>

        </div>
    </div>
          <button type="button" class="delete-button btn btn-outline-danger"><i class="fadeIn animated bx bx-trash"></i> Delete Lessee Details</button>
        `;
            parentContainer.appendChild(childItem);
        } else if (event.target.classList.contains('delete-button')) {
            const childItem = event.target.closest('.child-item');
            childItem.remove();
        }
    }
}

const multipleRepeater = new MultipleDivRepeater('container');

const validateButton = document.getElementById('submitButton3');
validateButton.addEventListener('click', function () {
    // Get all parent containers
    const parentContainers = document.querySelectorAll('.parent-container');
    let allValid = true;

    // Loop through each parent container and validate its input fields
    parentContainers.forEach(parentContainer => {
        const isValid = multipleRepeater.validateInputFields(parentContainer);
        if (!isValid) {
            allValid = false;
        }

        if (!transferredCheckboxYes.checked) {
            allValid = true;
        }
    });


    if (allValid) {
        // alert('All fields are valid!');

        stepper3.next()
        const transferredCheckboxYes = document.getElementById('transferredFormYes');
        transferredCheckboxYes.addEventListener('change', function () {
            if (this.checked) {


                var addTransferBtnError = document.getElementById('addTransferBtnError')
                addTransferBtnError.textContent = 'Please click on Add Transfer Button';
                addTransferBtnError.style.display = 'block';



                var addTransferBtn = document.getElementById('addTransferBtn')

                addTransferBtn.addEventListener('click', function () {
                    addTransferBtnError.style.display = 'none';

                    var addLesseeBtn = document.getElementById('addLesseeBtn')

                    addLesseeBtn.addEventListener('click', function () {
                        var addLesseeBtnError = document.getElementById('addLesseeBtnError')
                        addLesseeBtnError.style.display = 'none';
                    });

                });


            }
        });
        stepper3.next()
        // Proceed with form submission or other actions
    } else {
        // alert('Please fill out all required fields.');

    }



});




