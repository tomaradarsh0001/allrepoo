$(document).ready(function () {
    // Restrict input fields
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
  
    $(".numericOnly").on("input", function (e) {
      $(this).val($(this).val().replace(/[^0-9]/g, ""));
    });
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    var form1 = document.getElementById("grievanceForm");
  
    // Form fields
    var fullname = document.getElementById("fullname");
    var mobile = document.getElementById("mobile");
    var email = document.getElementById("email");
  
    var commAddress = document.getElementById("comm_address");
    var localityFill = document.getElementById("localityFill");
    var description = document.getElementById("description");
  
    // Error fields
    var fullnameError = document.getElementById("fullnameError");
    var mobileError = document.getElementById("mobileError");
    var emailError = document.getElementById("emailError");
  
    var addressError = document.getElementById("addressError");
    var localityFillError = document.getElementById("localityFillError");
    var descriptionError = document.getElementById("descriptionError");
  
    // Validation functions
    function validateFullName() {
      var fullnameValue = fullname.value.trim();
      if (fullnameValue === "") {
        fullnameError.textContent = "Full Name is required";
        return false;
      } else if (!/^[a-zA-Z\s]+$/.test(fullnameValue)) {
        fullnameError.textContent = "Only alphabets are allowed";
        return false;
      } else {
        fullnameError.textContent = "";
        return true;
      }
    }
  
    function validateMobile() {
      var mobileValue = mobile.value.trim();
      if (mobileValue === "") {
        mobileError.textContent = "Mobile Number is required";
        return false;
      } else if (!/^\d{10}$/.test(mobileValue)) {
        mobileError.textContent = "Mobile Number must be exactly 10 digits";
        return false;
      } else {
        mobileError.textContent = "";
        return true;
      }
    }
  
    function validateEmail() {
      var emailValue = email.value.trim();
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (emailValue === "") {
        emailError.textContent = "Email is required";
        return false;
      } else if (!emailPattern.test(emailValue)) {
        emailError.textContent = "Invalid email format";
        return false;
      } else {
        emailError.textContent = "";
        return true;
      }
    }
  
    function validateCommAddress() {
      var commAddressValue = commAddress.value.trim();
      if (commAddressValue === "") {
        addressError.textContent = "Communication Address is required";
        return false;
      } else {
        addressError.textContent = "";
        return true;
      }
    }
  
    function validateLocalityFill() {
      var localityFillValue = localityFill.value.trim();
      if (localityFillValue === "") {
        localityFillError.textContent = "Locality is required";
        return false;
      } else {
        localityFillError.textContent = "";
        return true;
      }
    }
  
    function validateDescription() {
      var descriptionValue = description.value.trim();
      if (descriptionValue === "") {
        descriptionError.textContent = "Description is required";
        return false;
      } else if (descriptionValue.length > 255) {
        descriptionError.textContent = "Description cannot exceed 255 characters";
        return false;
      } else {
        descriptionError.textContent = "";
        return true;
      }
    }
  
    function validateForm1() {
      var isFullNameValid = validateFullName();
      var isMobileValid = validateMobile();
      var isEmailValid = validateEmail();
      var isCommAddressValid = validateCommAddress();
      var isLocalityFillValid = validateLocalityFill();
      var isDescriptionValid = validateDescription();
  
      return (
        isFullNameValid &&
        isMobileValid &&
        isEmailValid &&
        isCommAddressValid &&
        isLocalityFillValid &&
        isDescriptionValid
      );
    }
  
    // Attach validation to form submission
    var submitButton = document.getElementById("ProceedButton");
    submitButton.addEventListener("click", function (event) {
      event.preventDefault();
      if (validateForm1()) {
        form1.submit(); // Submit the form after validation
      }
    });
  });
  