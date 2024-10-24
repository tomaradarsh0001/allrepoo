// Field Validation
$(document).ready(function () {
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

  $(".numericDecimal").on("input", function () {
    var value = $(this).val();
    if (!/^\d*\.?\d*$/.test(value)) {
      $(this).val(value.slice(0, -1));
    }
  });

  $(".numericOnly").on("input", function (e) {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9]/g, "")
    );
  });
  $(".numericDecimalHyphen").on("input", function () {
    var value = $(this).val();
    if (!/^[\d-]*\.?\d*$/.test(value)) {
      $(this).val(value.slice(0, -1));
    }
  });
  $(".alphaNum-hiphenForwardSlash").on("input", function () {
    var value = $(this).val();
    // Allow only alphanumeric, hyphen, and forward slash
    var filteredValue = value.replace(/[^a-zA-Z0-9\-\/]/g, "");
    $(this).val(filteredValue);
  });

  //   Date Format
  $(".date_format").on("input", function (e) {
    var input = $(this).val().replace(/\D/g, "");
    if (input.length > 8) {
      input = input.substring(0, 8);
    }

    var formattedDate = "";
    if (input.length > 0) {
      formattedDate = input.substring(0, 2);
    }
    if (input.length >= 3) {
      formattedDate += "-" + input.substring(2, 4);
    }
    if (input.length >= 5) {
      formattedDate += "-" + input.substring(4, 8);
    }

    $(this).val(formattedDate);
  });

  // Plot No.
  $(".plotNoAlpaMix").on("input", function () {
    var pattern = /[^a-zA-Z0-9+\-/]/g;
    var sanitizedValue = $(this).val().replace(pattern, "");
    $(this).val(sanitizedValue);
  });
  // PAN Number
  // PAN
  $(".pan_number_format").on("input", function (event) {
    var value = $(this).val().toUpperCase();
    var newValue = "";
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
      } else if (i >= 5 && i < 9) {
        if (/[0-9]/.test(char)) {
          newValue += char;
        } else {
          valid = false;
          break;
        }
      } else if (i === 9) {
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
  // Share
  $(".alphaNum_slash_modulus").on("input", function () {
    var value = $(this).val();
    var sanitizedValue = value.replace(/[^a-zA-Z0-9\/%]/g, "");
    if (value !== sanitizedValue) {
      $(this).val(sanitizedValue);
    }
  });
});
// Repeater for Add Co-Applicant Lessee Details
// Repeater for Add Co-Applicant Lessee Details
$("#MUTrepeater").createRepeater({
  showFirstItemToDefault: true,
});
// End
// Repeater for Add Co-Applicant Lessee Details
$("#CONrepeater").createRepeater({
  showFirstItemToDefault: true,
});

$("#repeater").createRepeater({
  showFirstItemToDefault: true,
});
// End
// Repeater for Add Applicant Lessee Details
// End
// Repeater for Add Applicant Lessee Details
$("#repeaterLessee").createRepeater({
  showFirstItemToDefault: true,
});
// End

function getBaseURL() {
  const { protocol, hostname, port } = window.location;
  return `${protocol}//${hostname}${port ? ":" + port : ""}`;
}

$(document).ready(function () {
  // Self Attested Doc for Other
  // Self Attested Doc for Other
  $("#selectdocselfattesteddocname").change(function () {
    if ($(this).val() === "Other") {
      $("#docName").show();
      $("#docName").show();
    } else {
      $("#docName").hide();
      $("#docName").hide();
    }
  });

  // Application Form Show/Hide Based on Selection
  $("#applicationType").change(function () {
    const selectedValue = $(this).val();

    if (selectedValue === "NOC") {
      $(".nocDiv").show();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();
    } else if (selectedValue === "") {
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();
    } else if (selectedValue === "SUB_MUT") {
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").show();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();

      getUserDetails()
        .then(function (response) {
          if (response.status) {
            $("#mutNameApp").val(response.data.user.name);
            $("#mutGenderApp").val(response.data.details.gender);
            $("#mutAgeApp").val(response.data.user.name);
            $("#mutprefixApp").html(response.data.details.so_do_spouse);
            $("#mutFathernameApp").val(response.data.details.second_name);
            $("#mutAadharApp").val(response.data.details.aadhar_card);
            $("#mutPanApp").val(response.data.details.pan_card);
            $("#mutMobilenumberApp").val(response.data.user.mobile_no);
          }
        })
        .catch(function (error) {
          console.error("Error during AJAX call:", error);
        }); //code changed by Nitin to reuse fetch-user details function
    } else if (selectedValue === "PRP_CERT") {
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").show();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();
    } else if (selectedValue === "SEL_PERM") {
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").show();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();
    } else if (selectedValue === "CONVERSION") {
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").show();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();

      getUserDetails()
        .then(function (response) {
          if (response.status) {
            $("#convname").val(response.data.user.name);
            $("#convgender").val(response.data.details.gender);
            $("#convage").val(response.data.user.age);
            $("#convprefixApp").html(response.data.details.so_do_spouse);
            $("#convfathername").val(response.data.details.second_name);
            $("#convaadhar").val(response.data.details.aadhar_card);
            $("#convpan").val(response.data.details.pan_card);
            $("#convmobilenumber").val(response.data.user.mobile_no);
          }
        })
        .catch(function (error) {
          console.error("Error during AJAX call:", error);
        }); //code changed by Nitin to reuse fetch-user details function
    } else if (selectedValue === "DOA") {
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").show();
      $(".landusechangeDiv").hide();
    } else if (selectedValue === "LUC") {
      var propertyId = $("#propertyid").val();
      var updateId = $('input[name="updateId"]').val();
      if (propertyid) {
        getLandUseChangeData(propertyId, updateId, function (success, message) {
          if (success) {
            $(".nocDiv").hide();
            $(".FHSubstitutionMutationdiv").hide();
            $(".propertycertificateDiv").hide();
            $(".salepermissionDiv").hide();
            $(".LHConversiondiv").hide();
            $(".deedofappartmentDiv").hide();
            $(".landusechangeDiv").show();
          } else {
            showError(message);
          }
        });
      }
    } else {
      $("#freeleasetitle").text("");
      $(".nocDiv").hide();
      $(".FHSubstitutionMutationdiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".propertycertificateDiv").hide();
      $(".salepermissionDiv").hide();
      $(".LHConversiondiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".deedofappartmentDiv").hide();
      $(".landusechangeDiv").hide();
    }
  });

  // -------------------- if Yes Mortgaged --------------------
  $("#YesMortgaged").change(function () {
    $("#yesRemarksDiv").show();
  });
  $("#NoMortgaged").change(function () {
    $("#yesRemarksDiv").hide();
  });

  // -------------------- if Yes Court Order --------------------
  $("#YesCourtOrder").change(function () {
    $("#yescourtorderDiv").show();
  });
  $("#NoCourtOrder").change(function () {
    $("#yescourtorderDiv").hide();
  });

  // -------------------- if Yes Court Order in Conversion --------------------
  $("#YesCourtOrderConversion").change(function () {
    $("#yescourtorderConversionDiv").show();
  });
  $("#NoCourtOrderConversion").change(function () {
    $("#yescourtorderConversionDiv").hide();
  });

  // -------------------- if Yes Mortgaged in Conversion --------------------
  $("#YesMortgagedConversion").change(function () {
    $("#yesRemarksDivConversion").show();
  });
  $("#NoMortgagedConversion").change(function () {
    $("#yesRemarksDivConversion").hide();
  });

  // -------------------- if Yes Deed Lost in Conversion --------------------
  $("#YesDeedLostConversion").change(function () {
    $("#yesDeedLostDivConversion").show();
  });
  $("#NoMortgagedConversion").change(function () {
    $("#NoDeedLostConversion").hide();
  });
});

// Checkbox Group Only One Selection
$("input:checkbox").on("click", function () {
  var $box = $(this);
  if ($box.is(":checked")) {
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  var form1 = document.getElementById("newstep-vl-1");
  var form2 = document.getElementById("newstep-vl-2");
  var form3 = document.getElementById("newstep-vl-3");

  // Form 1 Fields
  var propertyid = document.getElementById("propertyid");
  var propertyStatus = document.getElementById("propertyStatus");
  var applicationType = document.getElementById("applicationType");
  var statusofapplicant = document.getElementById("statusofapplicant");

  var lucpropertytypeto = document.getElementById("lucpropertytypeto");
  var lucpropertysubtypeto = document.getElementById("lucpropertysubtypeto");

  // Form 1 Errors
  var propertyIdError = document.getElementById("propertyIdError");
  var propertyStatusError = document.getElementById("propertyStatusError");
  var applicationTypeError = document.getElementById("applicationTypeError");
  var statusofapplicantError = document.getElementById(
    "statusofapplicantError"
  );

  var lucpropertytypetoError = document.getElementById(
    "lucpropertytypetoError"
  );
  var lucpropertysubtypetoError = document.getElementById(
    "lucpropertysubtypetoError"
  );

  function validatePropertyId() {
    var propertyidValue = propertyid.value.trim();
    if (propertyidValue === "") {
      propertyIdError.textContent = "Property ID is required";
      propertyIdError.style.display = "block";
      return false;
    } else {
      propertyIdError.style.display = "none";
      return true;
    }
  }

  function validatePropertyStatus() {
    var propertyStatusValue = propertyStatus.value.trim();
    if (propertyStatusValue === "") {
      propertyStatusError.textContent = "Property Status is required";
      propertyStatusError.style.display = "block";
      return false;
    } else {
      propertyStatusError.style.display = "none";
      propertyStatusError.style.display = "none";
      return true;
    }
  }

  function validateApplicationType() {
    var applicationTypeValue = applicationType.value.trim();
    if (applicationTypeValue === "") {
      applicationTypeError.textContent = "Application Type is required";
      applicationTypeError.style.display = "block";
      return false;
    } else {
      applicationTypeError.style.display = "none";
      applicationTypeError.style.display = "none";
      return true;
    }
  }

  function validateStatusOfApplicant() {
    var statusofapplicantValue = statusofapplicant.value.trim();
    if (statusofapplicantValue === "") {
      statusofapplicantError.textContent = "Status of Applicant is required";
      statusofapplicantError.style.display = "block";
      return false;
    } else {
      statusofapplicantError.style.display = "none";
      statusofapplicantError.style.display = "none";
      return true;
    }
  }

  function validateStatusOfChangeProperty() {
    var lucpropertytypetoValue = lucpropertytypeto.value.trim();
    if (lucpropertytypetoValue === "") {
      lucpropertytypetoError.textContent =
        "Change to Property Type is required";
      lucpropertytypetoError.style.display = "block";
      return false;
    } else {
      lucpropertytypetoError.style.display = "none";
      lucpropertytypetoError.style.display = "none";
      return true;
    }
  }

  function validateStatusOfChangeSubProperty() {
    var lucpropertysubtypetoValue = lucpropertysubtypeto.value.trim();
    if (lucpropertysubtypetoValue === "") {
      lucpropertysubtypetoError.textContent =
        "Change to Property Sub Type is required";
      lucpropertysubtypetoError.style.display = "block";
      return false;
    } else {
      lucpropertysubtypetoError.style.display = "none";
      lucpropertysubtypetoError.style.display = "none";
      return true;
    }
  }

  // Validate Form 1
  function validateForm1LUC() {
    var isPropertyIdValid = validatePropertyId();
    var isPropertyStatusValid = validatePropertyStatus();
    var isApplicationTypeValid = validateApplicationType();
    var isStatusOfApplicantValid = validateStatusOfApplicant();
    // LUC
    var isStatusOfChangePropertyValid = validateStatusOfChangeProperty();
    var isStatusOfChangeSubPropertyValid = validateStatusOfChangeSubProperty();

    return (
      isPropertyIdValid &&
      isPropertyStatusValid &&
      isApplicationTypeValid &&
      isStatusOfApplicantValid &&
      isStatusOfChangePropertyValid &&
      isStatusOfChangeSubPropertyValid
    );
  }

  // Form 2 Fields
  var lucpropertyTaxpayreceipt = document.getElementById(
    "lucpropertyTaxpayreceipt"
  );
  var PropertyTaxAssessmentReceipt = document.getElementById(
    "PropertyTaxAssessmentReceipt"
  );
  var lucphoto1 = document.getElementById("lucphoto1");
  var lucmpdzonalpermitting = document.getElementById("lucmpdzonalpermitting");
  var lucagreeconsent = document.getElementById("lucagreeconsent");

  // Form 2 Errors
  var lucpropertyTaxpayreceiptError = document.getElementById(
    "lucpropertyTaxpayreceiptError"
  );
  var PropertyTaxAssessmentReceiptError = document.getElementById(
    "PropertyTaxAssessmentReceiptError"
  );
  var lucphoto1Error = document.getElementById("lucphoto1Error");
  var lucmpdzonalpermittingError = document.getElementById(
    "lucmpdzonalpermittingError"
  );
  var lucagreeconsentError = document.getElementById("lucagreeconsentError");

  function validatelucpropertyTaxpayreceipt() {
    if (lucpropertyTaxpayreceipt.files.length > 0) {
      var file = lucpropertyTaxpayreceipt.files[0];
      if (file.size > 5 * 1024 * 1024) {
        lucpropertyTaxpayreceiptError.textContent =
          "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        lucpropertyTaxpayreceiptError.textContent =
          "Only PDF files are allowed";
        return false;
      } else {
        lucpropertyTaxpayreceiptError.textContent = "";
        return true;
      }
    } else {
      lucpropertyTaxpayreceiptError.textContent =
        "Property Tax Payment Receipt is required";
      return false;
    }
  }

  function validatePropertyTaxAssessmentReceipt() {
    if (PropertyTaxAssessmentReceipt.files.length > 0) {
      var file = PropertyTaxAssessmentReceipt.files[0];
      if (file.size > 5 * 1024 * 1024) {
        PropertyTaxAssessmentReceiptError.textContent =
          "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        PropertyTaxAssessmentReceiptError.textContent =
          "Only PDF files are allowed";
        return false;
      } else {
        PropertyTaxAssessmentReceiptError.textContent = "";
        return true;
      }
    } else {
      PropertyTaxAssessmentReceiptError.textContent =
        "Property Tax Assessment is required";
      return false;
    }
  }

  function validateLUCPhoto1() {
    if (lucphoto1.files.length > 0) {
      var file = lucphoto1.files[0];
      if (file.size > 5 * 1024 * 1024) {
        lucphoto1Error.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        lucphoto1Error.textContent = "Only PDF file is allowed";
        return false;
      } else {
        lucphoto1Error.textContent = "";
        return true;
      }
    } else {
      lucphoto1Error.textContent = "Property Photo is required";
      return false;
    }
  }

  function validateLUCMPDPermit() {
    if (lucmpdzonalpermitting.files.length > 0) {
      var file = lucmpdzonalpermitting.files[0];
      if (file.size > 5 * 1024 * 1024) {
        lucmpdzonalpermittingError.textContent =
          "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        lucmpdzonalpermittingError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        lucmpdzonalpermittingError.textContent = "";
        return true;
      }
    } else {
      lucmpdzonalpermittingError.textContent =
        "MPD/Zonal Plan Permitting LUC is required";
      return false;
    }
  }

  function validateStatusOfChangeProperty() {
    var lucpropertytypetoValue = lucpropertytypeto.value.trim();
    if (lucpropertytypetoValue === "") {
      lucpropertytypetoError.textContent =
        "Change to Property Type is required";
      lucpropertytypetoError.style.display = "block";
      return false;
    } else {
      lucpropertytypetoError.style.display = "none";
      lucpropertytypetoError.style.display = "none";
      return true;
    }
  }

  function validateAgreeConsentLUC() {
    if (!lucagreeconsent.checked) {
      lucagreeconsentError.textContent = "Please accept Terms & Conditions";
      lucagreeconsentError.style.display = "block";
      return false;
    } else {
      lucagreeconsentError.style.display = "none";
      return true;
    }
  }

  // Validate Form 2
  function validateForm2LUC() {
    var islucpropertyTaxpayreceiptValid = validatelucpropertyTaxpayreceipt();
    var isPropertyTaxAssessmentReceiptValid =
      validatePropertyTaxAssessmentReceipt();
    var isLUCPhoto1Valid = validateLUCPhoto1();
    var isLUCMPDPermitValid = validateLUCMPDPermit();
    var isAgreeConsentLUCValid = validateAgreeConsentLUC();

    return (
      islucpropertyTaxpayreceiptValid &&
      isPropertyTaxAssessmentReceiptValid &&
      isLUCPhoto1Valid &&
      isLUCMPDPermitValid &&
      isAgreeConsentLUCValid
    );
  }

  // Form 1 Fields
  var namergapp = document.getElementById("namergapp");
  var mutExecutedOnAsConLease = document.getElementById(
    "mutExecutedOnAsConLease"
  );
  var regno = document.getElementById("regno");
  var bookno = document.getElementById("bookno");
  var volumeno = document.getElementById("volumeno");
  var pageno = document.getElementById("pageno");
  var regdate = document.getElementById("regdate");
  var soughtByApplicant = document.getElementById("soughtByApplicant");
  var YesMortgaged = document.getElementById("YesMortgaged");
  var remarks = document.getElementById("remarks");

  // Form 1 Errors
  var namergappError = document.getElementById("namergappError");
  var mutExecutedOnAsConLeaseError = document.getElementById(
    "mutExecutedOnAsConLeaseError"
  );
  var regnoError = document.getElementById("regnoError");
  var booknoError = document.getElementById("booknoError");
  var volumenoError = document.getElementById("volumenoError");
  var pagenoError = document.getElementById("pagenoError");
  var regdateError = document.getElementById("regdateError");
  var soughtByApplicantError = document.getElementById(
    "soughtByApplicantError"
  );
  var YesMortgagedError = document.getElementById("YesMortgagedError");

  function validateNamerGapp() {
    var namergappValue = namergapp.value.trim();
    if (namergappValue === "") {
      namergappError.textContent = "Executed in favour of is required";
      namergappError.style.display = "block";
      return false;
    } else {
      namergappError.style.display = "none";
      return true;
    }
  }

  function validateExecutedOn() {
    var mutExecutedOnAsConLeaseValue = mutExecutedOnAsConLease.value.trim();
    if (mutExecutedOnAsConLeaseValue === "") {
      mutExecutedOnAsConLeaseError.textContent =
        "Executed On field is required";
      mutExecutedOnAsConLeaseError.style.display = "block";
      return false;
    } else {
      mutExecutedOnAsConLeaseError.style.display = "none";
      return true;
    }
  }

  function validateRegOn() {
    var regnoValue = regno.value.trim();
    if (regnoValue === "") {
      regnoError.textContent = "Executed On field is required";
      regnoError.style.display = "block";
      return false;
    } else {
      regnoError.style.display = "none";
      return true;
    }
  }

  function validateBookNo() {
    var booknoValue = bookno.value.trim();
    if (booknoValue === "") {
      booknoError.textContent = "Book No. is required";
      booknoError.style.display = "block";
      return false;
    } else {
      booknoError.style.display = "none";
      return true;
    }
  }

  function validateVolumeNo() {
    var volumenoValue = volumeno.value.trim();
    if (volumenoValue === "") {
      volumenoError.textContent = "Volume No. is required";
      volumenoError.style.display = "block";
      return false;
    } else {
      volumenoError.style.display = "none";
      return true;
    }
  }

  function validatePageNo() {
    var pagenoValue = pageno.value.trim();
    if (pagenoValue === "") {
      pagenoError.textContent = "Page No. is required";
      pagenoError.style.display = "block";
      return false;
    } else {
      pagenoError.style.display = "none";
      return true;
    }
  }

  function validateRegDate() {
    var regdateValue = regdate.value.trim();
    if (regdateValue === "") {
      regdateError.textContent = "Reg. Date is required";
      regdateError.style.display = "block";
      return false;
    } else {
      regdateError.style.display = "none";
      return true;
    }
  }

  function validateSoughtApplicant() {
    var soughtByApplicantValue = document.querySelectorAll(
      ".documentType:checked"
    );
    console.log(soughtByApplicantValue);
    if (soughtByApplicantValue.length === 0) {
      soughtByApplicantError.style.display = "block";
      soughtByApplicantError.textContent =
        "Please select at least one document";
      return false;
    } else {
      soughtByApplicantError.style.display = "none";
      return true;
    }

    // if (soughtByApplicantValue === "") {
    //   soughtByApplicantError.textContent = "Mutation/Substitution sought by applicant is required";
    //   soughtByApplicantError.style.display = "block";
    //   return false;
    // } else {
    //   soughtByApplicantError.style.display = "none";
    //   return true;
    // }
  }

  function validateYesMortgages() {
    if (YesMortgaged.checked) {
      var remarksValue = remarks.value.trim();
      if (remarksValue === "") {
        YesMortgagedError.textContent = "Remarks is required.";
        YesMortgagedError.style.display = "block";
        return false;
      } else {
        YesMortgagedError.style.display = "none";
      }
    }
    return true;
  }

  // Validate Form 1 MUT
  function validateForm1MUT() {
    var isFarm1MUTValid = validateNamerGapp();
    var isExecutedOnValid = validateExecutedOn();
    var isRegOnValid = validateRegOn();
    var isBookNoValid = validateBookNo();
    var isVolumeNoValid = validateVolumeNo();
    var isPageNoValid = validatePageNo();
    var isRegDateValid = validateRegDate();
    var isSoughtApplicantValid = validateSoughtApplicant();
    var isYesMortgagesValid = validateYesMortgages();

    return (
      isFarm1MUTValid &&
      isExecutedOnValid &&
      isRegOnValid &&
      isBookNoValid &&
      isVolumeNoValid &&
      isPageNoValid &&
      isRegDateValid &&
      isSoughtApplicantValid &&
      isYesMortgagesValid
    );
  }

  // Form 2 Fields
  var affidavits = document.getElementById("affidavits");
  var affidavitsDateAttestation = document.getElementById("dateattestation");
  var affidavitsAttestedby = document.getElementById("attestedby");

  var indemnityBond = document.getElementById("indemnityBond");
  var indemnityBonddateattestation = document.getElementById(
    "indemnityBonddateattestation"
  );
  var indemnityBondattestedby = document.getElementById(
    "indemnityBondattestedby"
  );

  var leaseconyedeed = document.getElementById("leaseconyedeed");
  var dateofexecution = document.getElementById("dateofexecution");
  var lesseename = document.getElementById("lesseename");

  var pannumber = document.getElementById("pannumber");
  var pancertificateno = document.getElementById("pancertificateno");
  var pandateissue = document.getElementById("pandateissue");

  var aadharnumber = document.getElementById("aadharnumber");
  var aadharcertificateno = document.getElementById("aadharcertificateno");
  var aadhardateissue = document.getElementById("aadhardateissue");

  var publicnoticeenhin = document.getElementById("publicnoticeenhin");
  var propertyPhoto = document.getElementById("property_photo");
  var newspapernameengligh = document.getElementById("newspapernameengligh");
  var publicnoticedate = document.getElementById("publicnoticedate");

  // Form 2 Errors
  var affidavitsError = document.getElementById("affidavitsError");
  var dateattestationError = document.getElementById("dateattestationError");
  var attestedbyError = document.getElementById("attestedbyError");

  var indemnityBondError = document.getElementById("indemnityBondError");
  var indemnityBonddateattestationError = document.getElementById(
    "indemnityBonddateattestationError"
  );
  var indemnityBondattestedbyError = document.getElementById(
    "indemnityBondattestedbyError"
  );

  var leaseconyedeedError = document.getElementById("leaseconyedeedError");
  var dateofexecutionError = document.getElementById("dateofexecutionError");
  var lesseenameError = document.getElementById("lesseenameError");

  var pannumberError = document.getElementById("pannumberError");
  var pancertificatenoError = document.getElementById("pancertificatenoError");
  var pandateissueError = document.getElementById("pandateissueError");

  var aadharnumberError = document.getElementById("aadharnumberError");
  var aadharcertificatenoError = document.getElementById(
    "aadharcertificatenoError"
  );
  var aadhardateissueError = document.getElementById("aadhardateissueError");

  var publicnoticeenhinError = document.getElementById(
    "publicnoticeenhinError"
  );
  var propertyPhotoError = document.getElementById("property_photoError");
  var newspapernameenglighError = document.getElementById(
    "newspapernameenglighError"
  );
  var publicnoticedateError = document.getElementById("publicnoticedateError");

  function validateAffidavits() {
    if (affidavits.files.length > 0) {
      var file = affidavits.files[0];
      if (file.size > 5 * 1024 * 1024) {
        affidavitsError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        affidavitsError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        affidavitsError.textContent = "";
        return true;
      }
    } else {
      affidavitsError.textContent = "Affidavits is required";
      return false;
    }
  }
  function validateDateofAffidavits() {
    var affidavitsDateAttestationValue = affidavitsDateAttestation.value.trim();
    if (affidavitsDateAttestationValue === "") {
      dateattestationError.textContent = "Date of attestation is required";
      dateattestationError.style.display = "block";
      return false;
    } else {
      dateattestationError.style.display = "none";
      return true;
    }
  }
  function validateAttestedByAffidavits() {
    var affidavitsAttestedbyValue = affidavitsAttestedby.value.trim();
    if (affidavitsAttestedbyValue === "") {
      attestedbyError.textContent = "Attested by is required";
      attestedbyError.style.display = "block";
      return false;
    } else {
      attestedbyError.style.display = "none";
      return true;
    }
  }

  function validateIndemnityBond() {
    if (indemnityBond.files.length > 0) {
      var file = indemnityBond.files[0];
      if (file.size > 5 * 1024 * 1024) {
        indemnityBondError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        indemnityBondError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        indemnityBondError.textContent = "";
        return true;
      }
    } else {
      indemnityBondError.textContent = "Indemnity Bond is required";
      return false;
    }
  }
  function validateIndemnityDateofAttestation() {
    var indemnityBonddateattestationValue =
      indemnityBonddateattestation.value.trim();
    if (indemnityBonddateattestationValue === "") {
      indemnityBonddateattestationError.textContent =
        "Date of attestation is required";
      indemnityBonddateattestationError.style.display = "block";
      return false;
    } else {
      indemnityBonddateattestationError.style.display = "none";
      return true;
    }
  }
  function validateIndemnityAttestedBy() {
    var indemnityBondattestedbyValue = indemnityBondattestedby.value.trim();
    if (indemnityBondattestedbyValue === "") {
      indemnityBondattestedbyError.textContent = "Attested by is required";
      indemnityBondattestedbyError.style.display = "block";
      return false;
    } else {
      indemnityBondattestedbyError.style.display = "none";
      return true;
    }
  }

  function validateLeaseConyence() {
    if (leaseconyedeed.files.length > 0) {
      var file = leaseconyedeed.files[0];
      if (file.size > 5 * 1024 * 1024) {
        leaseconyedeedError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        leaseconyedeedError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        leaseconyedeedError.textContent = "";
        return true;
      }
    } else {
      leaseconyedeedError.textContent =
        "Lease Deed/Conveyance Deed is required";
      return false;
    }
  }
  function validateDateofExecution() {
    var dateofexecutionValue = dateofexecution.value.trim();
    if (dateofexecutionValue === "") {
      dateofexecutionError.textContent = "Date of execution is required";
      dateofexecutionError.style.display = "block";
      return false;
    } else {
      dateofexecutionError.style.display = "none";
      return true;
    }
  }
  function validateLesseeName() {
    var lesseenameValue = lesseename.value.trim();
    if (lesseenameValue === "") {
      lesseenameError.textContent = "Lessee Name is required";
      lesseenameError.style.display = "block";
      return false;
    } else {
      lesseenameError.style.display = "none";
      return true;
    }
  }

  function validatePAN() {
    if (pannumber.files.length > 0) {
      var file = pannumber.files[0];
      if (file.size > 5 * 1024 * 1024) {
        pannumberError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        pannumberError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        pannumberError.textContent = "";
        return true;
      }
    } else {
      pannumberError.textContent = "PAN is required";
      return false;
    }
  }
  function validatePANCertification() {
    var pancertificatenoValue = pancertificateno.value.trim();
    if (pancertificatenoValue === "") {
      pancertificatenoError.textContent = "Certificate No. is required";
      pancertificatenoError.style.display = "block";
      return false;
    } else {
      pancertificatenoError.style.display = "none";
      return true;
    }
  }
  function validatePANDate() {
    var pandateissueValue = pandateissue.value.trim();
    if (pandateissueValue === "") {
      pandateissueError.textContent = "Date of Issue is required";
      pandateissueError.style.display = "block";
      return false;
    } else {
      pandateissueError.style.display = "none";
      return true;
    }
  }

  function validateAadhar() {
    if (aadharnumber.files.length > 0) {
      var file = aadharnumber.files[0];
      if (file.size > 5 * 1024 * 1024) {
        aadharnumberError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        aadharnumberError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        aadharnumberError.textContent = "";
        return true;
      }
    } else {
      aadharnumberError.textContent = "Aadhar is required";
      return false;
    }
  }
  function validateAadharCertification() {
    var aadharcertificatenoValue = aadharcertificateno.value.trim();
    if (aadharcertificatenoValue === "") {
      aadharcertificatenoError.textContent = "Certificate No. is required";
      aadharcertificatenoError.style.display = "block";
      return false;
    } else {
      aadharcertificatenoError.style.display = "none";
      return true;
    }
  }
  function validateAadharDate() {
    var aadhardateissueValue = aadhardateissue.value.trim();
    if (aadhardateissueValue === "") {
      aadhardateissueError.textContent = "Date of Issue is required";
      aadhardateissueError.style.display = "block";
      return false;
    } else {
      aadhardateissueError.style.display = "none";
      return true;
    }
  }

  function validatePublicNoticeND() {
    if (publicnoticeenhin.files.length > 0) {
      var file = publicnoticeenhin.files[0];
      if (file.size > 5 * 1024 * 1024) {
        publicnoticeenhinError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        publicnoticeenhinError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        publicnoticeenhinError.textContent = "";
        return true;
      }
    } else {
      publicnoticeenhinError.textContent =
        "Public Notice in National Daily (English & Hindi) is required";
      return false;
    }
  }

  function validatePropertyPhoto() {
    if (propertyPhoto.files.length > 0) {
      var file = propertyPhoto.files[0];
      if (file.size > 5 * 1024 * 1024) {
        propertyPhotoError.textContent = "File size must be less than 5 MB";
        return false;
      } else if (!file.name.endsWith(".pdf")) {
        propertyPhotoError.textContent = "Only PDF files are allowed";
        return false;
      } else {
        propertyPhotoError.textContent = "";
        return true;
      }
    } else {
      propertyPhotoError.textContent = "Property Photo is required";
      return false;
    }
  }
  function validateNewsPaperNameengligh() {
    var newspapernameenglighValue = newspapernameengligh.value.trim();
    if (newspapernameenglighValue === "") {
      newspapernameenglighError.textContent =
        "Name of Newspaper(English & Hindi) is required";
      newspapernameenglighError.style.display = "block";
      return false;
    } else {
      newspapernameenglighError.style.display = "none";
      return true;
    }
  }
  function validatePublicNoteDate() {
    var publicnoticedateValue = publicnoticedate.value.trim();
    if (publicnoticedateValue === "") {
      publicnoticedateError.textContent = "Date of Public Notice is required";
      publicnoticedateError.style.display = "block";
      return false;
    } else {
      publicnoticedateError.style.display = "none";
      return true;
    }
  }

  // Validate Form 2 MUT
  function validateForm2MUT() {
    var isAffidavitsValid = validateAffidavits();
    // var isDateofAffidavitsValid = validateDateofAffidavits();
    // var isAttestedByAffidavitsValid = validateAttestedByAffidavits();

    var isIndemnityBondValid = validateIndemnityBond();
    // var isIndemnityDateofAttestationValid = validateIndemnityDateofAttestation();
    // var isIndemnityAttestedByValid = validateIndemnityAttestedBy();

    var isLeaseConyenceValid = validateLeaseConyence();
    // var isDateofExecutionValid = validateDateofExecution();
    // var isLesseeNameValid = validateLesseeName();

    var isPANValid = validatePAN();
    // var isPANCertificationValid = validatePANCertification();
    // var isPANDateValid = validatePANDate();

    var isAadharValid = validateAadhar();
    // var isAadharCertificationValid = validateAadharCertification();
    // var isAadharDateValid = validateAadharDate();

    var isPublicNoticeNDValid = validatePublicNoticeND();
    // var isNewsPaperNameenglighValid = validateNewsPaperNameengligh();
    // var isPublicNoteDateValid = validatePublicNoteDate();
    var isPropertyPhotoValid = validatePropertyPhoto();

    return (
      isAffidavitsValid &&
      // isDateofAffidavitsValid &&
      // isAttestedByAffidavitsValid &&

      isIndemnityBondValid &&
      // isIndemnityDateofAttestationValid &&
      // isIndemnityAttestedByValid &&

      isLeaseConyenceValid &&
      // isDateofExecutionValid &&
      // isLesseeNameValid &&

      isPANValid &&
      // isPANCertificationValid &&
      // isPANDateValid &&

      isAadharValid &&
      // isAadharCertificationValid &&
      // isAadharDateValid &&

      isPublicNoticeNDValid &&
      // isNewsPaperNameenglighValid &&
      // isPublicNoteDateValid

      isPropertyPhotoValid
    );
  }

  // Form 3 Fields
  var MutAgreeConsent = document.getElementById("agreeconsent");

  // Form 3 Errors
  var MutAgreeconsentError = document.getElementById("MutAgreeconsentError");

  function validateAgreeConsentMut() {
    if (!MutAgreeConsent.checked) {
      MutAgreeconsentError.textContent = "Please accept Terms & Conditions";
      MutAgreeconsentError.style.display = "block";
      return false;
    } else {
      MutAgreeconsentError.style.display = "none";
      return true;
    }
  }

  function validateForm3MUT() {
    var isAgreeConsentMutValid = validateAgreeConsentMut();

    return isAgreeConsentMutValid;
  }

  form1.addEventListener("button", function (event) {
    event.preventDefault();
    if (validateForm1LUC() || validateForm1MUT()) {
      alert("Form submitted successfully");
    }
  });

  form2.addEventListener("button", function (event) {
    event.preventDefault();
    if (validateForm2LUC()) {
      alert("Form submitted successfully");
    }
  });

  form3.addEventListener("button", function (event) {
    event.preventDefault();
    if (validateForm3()) {
      alert("Form submitted successfully");
    }
  });

  var submitButton1 = document.getElementsByClassName("submitbtn1");
  // var $submitButtonOne = $(submitButton1);
  submitButton1.forEach((btn) => {
    btn.addEventListener("click", function () {
      // btn.textContent = "Submitting...";
      // btn.disabled = true;
      var propertyid = $("#propertyid").val();
      var propertyStatus = $("input[name='applicationStatus']").val();
      var applicationType = $("select[name='applicationType']").val();

      if (validateForm1MUT()) {
        // for submitting the first step of application  - Sourav Chauhan (17/sep/2024)
        // btn.textContent = "Submitting...";
        // btn.disabled = true;
        var propertyid = $("#propertyid").val();
        var propertyStatus = $("input[name='applicationStatus']").val();
        var applicationType = $("select[name='applicationType']").val();
        //for mutation - Sourav Chauhan (17/sep/2024)
        if (applicationType == "SUB_MUT") {
          mutation(propertyid, propertyStatus, function (success, result) {
            if (result.status) {
              btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showSuccess(result.message);
              steppers["stepper3"].next();
            } else {
              btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showError(result.message);
            }
          });
        }
      } else if (validateForm1LUC() && applicationType === "LUC") {
        if (propertyStatus == "Lease Hold" && applicationType == "LUC") {
          landUseChange(function (success, message) {
            if (success) {
              btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              steppers["stepper5"].next();
              showSuccess(message);
            } else {
              btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showError(message);
            }
          });
        }
      }

      if (propertyStatus == "Lease Hold" && applicationType == "DOA") {
        deedOfApartment(propertyid, propertyStatus, function (success, result) {
          if (result.status) {
            btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
            btn.disabled = false;
            showSuccess(result.message);
            steppers["stepper6"].next();
          } else {
            btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
            ('Next <i class="bx bx-right-arrow-alt ms-2"></i>');
            btn.disabled = false;
            showError(result.message);
          }
        });
      }

      if (propertyStatus == "Lease Hold" && applicationType == "CONVERSION") {
        conversionStep1(propertyid, propertyStatus, function (success, result) {
          if (success) {
            btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
            btn.disabled = false;
            showSuccess(result.message);
            steppers["stepper4"].next();
          } else {
            btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
            btn.disabled = false;
            showError(result.message);
          }
        });
      }
    });
  });

  function deedOfApartment(propertyid, propertyStatus, callback) {
    var localityDropDown = document.getElementsByName("locality")[0];
    var blockDropDown = document.getElementsByName("block")[0];
    var plotDropDown = document.getElementsByName("plot")[0];
    var knownAsDropDown = document.getElementsByName("knownas")[0];
    var flatDropDown = document.getElementsByName("flatId")[0];

    var updateId = $("input[name='updateId']").val();
    var statusofapplicant = $("#statusofapplicant").val();
    var applicantName = $("input[name='applicantName']").val();
    var applicantAddress = $("input[name='applicantAddress']").val();
    var buildingName = $("input[name='buildingName']").val();
    var locality = localityDropDown.value;
    var block = blockDropDown.value;
    var plot = plotDropDown.value;
    var knownas = knownAsDropDown.value;
    var flatId = flatDropDown.value;
    var isFlatNotListed = $("input[name='isFlatNotInList']:checked").val();
    var flatNumber = $("input[name='flatNumber']").val();
    var builderName = $("input[name='builderName']").val();
    var originalBuyerName = $("input[name='originalBuyerName']").val();
    var presentOccupantName = $("input[name='presentOccupantName']").val();
    var purchasedFrom = $("input[name='purchasedFrom']").val();
    var purchaseDate = $("input[name='purchaseDate']").val();
    var apartmentArea = $("input[name='apartmentArea']").val();
    var plotArea = $("input[name='plotArea']").val();
    var oldPropertyId = $("input[name='old_property_id']").val();
    var propertyMasterId = $("input[name='property_master_id']").val();
    var newPropertyId = $("input[name='new_property_id']").val();
    var splittedPropertyId = $(
      "input[name='splited_property_detail_id']"
    ).val();

    var baseUrl = getBaseURL();
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: baseUrl + "/doa-step-first",
      type: "POST",
      dataType: "JSON",
      data: {
        _token: csrfToken,
        updateId: updateId,
        propertyid: propertyid,
        oldPropertyId: oldPropertyId,
        propertyMasterId: propertyMasterId,
        newPropertyId: newPropertyId,
        splittedPropertyId: splittedPropertyId,
        propertyStatus: propertyStatus,
        statusofapplicant: statusofapplicant,
        applicantName: applicantName,
        applicantAddress: applicantAddress,
        buildingName: buildingName,
        locality: locality,
        block: block,
        plot: plot,
        knownas: knownas,
        flatId: flatId,
        isFlatNotListed: isFlatNotListed,
        flatNumber: flatNumber,
        builderName: builderName,
        originalBuyerName: originalBuyerName,
        presentOccupantName: presentOccupantName,
        purchasedFrom: purchasedFrom,
        purchaseDate: purchaseDate,
        apartmentArea: apartmentArea,
        plotArea: plotArea,
      },
      success: function (result) {
        if (result.status) {
          $("#submitbtn1").html(
            'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
          );
          $("#submitbtn1").prop("disabled", false);
          $("input[name='updateId']").val(result.data.id);
          $("input[name='lastPropertyId']").val(result.data.old_property_id);
          if (callback) callback(true, result); // Call the callback with success
        } else {
          // Handle failure scenario
          $("#submitbtn1").html(
            'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
          );
          $("#submitbtn1").prop("disabled", false);
          if (callback) callback(false, result); // Call the callback with failure
        }
      },
      error: function (xhr, status, error) {
        // Handle error scenario
        $("#submitbtn1").html(
          'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#submitbtn1").prop("disabled", false);
        // if (callback) callback(false, { xhr, status, error }); // Call the callback with error
        if (err.responseJSON && err.responseJSON.message) {
          if (callback) callback(false, err.responseJSON.message);
        } else {
          if (callback) callback(false, "Unknown error!!");
        }
      },
    });
  }

  // for storing first step of mutation- Sourav Chauhan (17/sep/2024)
  function mutation(propertyid, propertyStatus, callback) {
    var updateId = $("input[name='updateId']").val();
    var statusofapplicant = $("#statusofapplicant").val();
    var mutNameApp = $("input[name='mutNameApp']").val();
    var mutGenderApp = $("input[name='mutGenderApp']").val();
    var mutAgeApp = $("input[name='mutAgeApp']").val();
    // var mutFathernameApp = $("input[name='mutFathernameApp']").val();
    var mutExecutedOnAsConLease = $(
      "input[name='mutExecutedOnAsConLease']"
    ).val();
    var mutAadharApp = $("input[name='mutAadharApp']").val();
    var mutPanApp = $("input[name='mutPanApp']").val();
    var mutMobilenumberApp = $("input[name='mutMobilenumberApp']").val();

    var mutNameAsConLease = $("input[name='mutNameAsConLease']").val();
    var mutFathernameAsConLease = $(
      "input[name='mutFathernameAsConLease']"
    ).val();
    var mutRegnoAsConLease = $("input[name='mutRegnoAsConLease']").val();
    var mutBooknoAsConLease = $("input[name='mutBooknoAsConLease']").val();
    var mutVolumenoAsConLease = $("input[name='mutVolumenoAsConLease']").val();
    var mutPagenoAsConLease = $("input[name='mutPagenoAsConLease']").val();
    var mutRegdateAsConLease = $("input[name='mutRegdateAsConLease']").val();
    var soughtByApplicant = $("#soughtByApplicant").val();
    const soughtByApplicantDocuments = [];
    const checkboxes = document.querySelectorAll(".documentType:checked");
    checkboxes.forEach(function (checkbox) {
      soughtByApplicantDocuments.push(checkbox.value);
    });
    var mutPropertyMortgaged = $(
      "input[name='mutPropertyMortgaged']:checked"
    ).val();
    var mutMortgagedRemarks = $("textarea[name='mutMortgagedRemarks']").val();
    var mutCourtorder = $("input[name='mutCourtorder']:checked").val();
    var coapplicants = {};

    // Iterate over all input elements with names starting with 'coapplicant'
    $("input[name^='coapplicant'], select[name^='coapplicant']").each(
      function () {
        var nameAttr = $(this).attr("name");
        var value = $(this).val();
        var matches = nameAttr.match(/coapplicant\[(\d+)]\[(\w+)\]/);
        if (matches) {
          var index = matches[1];
          var field = matches[2];
          if (!coapplicants[index]) {
            coapplicants[index] = {};
          }
          coapplicants[index][field] = value;
        }
      }
    );

    var baseUrl = getBaseURL();
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: baseUrl + "/mutation-step-first",
      type: "POST",
      dataType: "JSON",
      data: {
        _token: csrfToken,
        updateId: updateId,
        propertyid: propertyid,
        propertyStatus: propertyStatus,
        statusofapplicant: statusofapplicant,
        mutNameApp: mutNameApp,
        mutGenderApp: mutGenderApp,
        mutAgeApp: mutAgeApp,
        mutExecutedOnAsConLease: mutExecutedOnAsConLease,
        // mutFathernameApp: mutFathernameApp,
        mutAadharApp: mutAadharApp,
        mutPanApp: mutPanApp,
        mutMobilenumberApp: mutMobilenumberApp,
        coapplicants: coapplicants,
        mutNameAsConLease: mutNameAsConLease,
        // mutFathernameAsConLease: mutFathernameAsConLease,
        mutRegnoAsConLease: mutRegnoAsConLease,
        mutBooknoAsConLease: mutBooknoAsConLease,
        mutVolumenoAsConLease: mutVolumenoAsConLease,
        mutPagenoAsConLease: mutPagenoAsConLease,
        mutRegdateAsConLease: mutRegdateAsConLease,
        soughtByApplicantDocuments: soughtByApplicantDocuments,
        mutPropertyMortgaged: mutPropertyMortgaged,
        mutMortgagedRemarks: mutMortgagedRemarks,
        mutCourtorder: mutCourtorder,
      },
      success: function (result) {
        if (result.status) {
          $("#submitbtn1").html(
            'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
          );
          $("#submitbtn1").prop("disabled", false);
          $("input[name='updateId']").val(result.data.id);
          $("input[name='lastPropertyId']").val(result.data.old_property_id);
          if (callback) callback(true, result); // Call the callback with success
        } else {
          // Handle failure scenario
          $("#submitbtn1").html(
            'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
          );
          $("#submitbtn1").prop("disabled", false);
          // $("input[name='updateId']").val(result.data.id);
          // $("input[name='lastPropertyId']").val(result.data.old_property_id);
          if (callback) callback(false, result); // Call the callback with failure
        }
      },
      error: function (err) {
        $("#submitbtn1").html(
          'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#submitbtn1").prop("disabled", false);
        if (err.responseJSON && err.responseJSON.message) {
          if (callback) callback(false, err.responseJSON.message);
        } else {
          if (callback) callback(false, "Unknown error!!");
        }
      },
    });
  }

  //for step second***********************************
  var submitButton2 = document.getElementsByClassName("submitbtn2");
  submitButton2.forEach((btn) => {
    btn.addEventListener("click", function () {
      if (validateForm2MUT()) {
        btn.textContent = "Submitting...";
        btn.disabled = true;
        var propertyStatus = $("input[name='applicationStatus']").val();
        var applicationType = $("select[name='applicationType']").val();

        if (applicationType == "SUB_MUT") {
          mutationStepSecond(function (success, result) {
            if (result.status) {
              btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showSuccess(result.message);
              steppers["stepper3"].next();
            } else {
              btn.innerHTML = 'Next <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showError(result.message);
            }
          });
        }
      } else if (propertyStatus == "Lease Hold" && applicationType == "LUC") {
        //not used after lastest design update
        /* landUseChangeStep2(function (success, message) {
          if (success) {
            $("#submitbtn2").html(
              'Submitted <i class="bx bx-right-arrow-alt ms-2"></i>'
            );
            $("#submitbtn2").prop("disabled", false);
            stepper3.next();
          } else {
            $("#submitbtn2").html(
              'Failed <i class="bx bx-right-arrow-alt ms-2"></i>'
            );
            $("#submitbtn2").prop("disabled", false);
            showError(message); 
          }
        });*/
      }
    });
  });

  // for storing second step of mutation- Sourav Chauhan (19/sep/2024)
  function mutationStepSecond(callback) {
    var updateId = $("input[name='updateId']").val();
    // var affidavitsDateAttestation = $(
    //   "input[name='affidavitsDateAttestation']"
    // ).val();
    // var affidavitsAttestedby = $("input[name='affidavitsAttestedby']").val();
    // var indemnityBondDateAttestation = $(
    //   "input[name='indemnityBondDateAttestation']"
    // ).val();
    // var indemnityBondattestedby = $(
    //   "input[name='indemnityBondattestedby']"
    // ).val();
    // var leaseConvDeedDateOfExecution = $(
    //   "input[name='leaseConvDeedDateOfExecution']"
    // ).val();
    // var leaseConvDeedLesseename = $(
    //   "input[name='leaseConvDeedLesseename']"
    // ).val();
    // var panCertificateNo = $("input[name='panCertificateNo']").val();
    // var panDateIssue = $("input[name='panDateIssue']").val();
    // var aadharCertificateNo = $("input[name='aadharCertificateNo']").val();
    // var aadharDateIssue = $("input[name='aadharDateIssue']").val();
    // var newspaperName = $("input[name='newspaperName']").val();
    // var publicNoticeDate = $("input[name='publicNoticeDate']").val();

    var baseUrl = getBaseURL();
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      url: baseUrl + "/mutation-step-second",
      type: "POST",
      dataType: "JSON",
      data: {
        _token: csrfToken,
        updateId: updateId,
        // affidavitsDateAttestation: affidavitsDateAttestation,
        // affidavitsAttestedby: affidavitsAttestedby,
        // indemnityBondDateAttestation: indemnityBondDateAttestation,
        // indemnityBondattestedby: indemnityBondattestedby,
        // leaseConvDeedDateOfExecution: leaseConvDeedDateOfExecution,
        // leaseConvDeedLesseename: leaseConvDeedLesseename,
        // panCertificateNo: panCertificateNo,
        // panDateIssue: panDateIssue,
        // aadharCertificateNo: aadharCertificateNo,
        // aadharDateIssue: aadharDateIssue,
        // newspaperName: newspaperName,
        // publicNoticeDate: publicNoticeDate,
      },
      success: function (result) {
        if (result.status) {
          $("#submitbtn2").html(
            'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
          );
          $("#submitbtn2").prop("disabled", false);
          if (callback) callback(true, result); // Call the callback with success
        } else {
          // Handle failure scenario
          $("#submitbtn2").html(
            'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
          );
          $("#submitbtn2").prop("disabled", false);
          if (callback) callback(false, result); // Call the callback with failure
        }
      },
      error: function (err) {
        $("#submitbtn1").html(
          'Next <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#submitbtn1").prop("disabled", false);
        if (err.responseJSON && err.responseJSON.message) {
          if (callback) callback(false, err.responseJSON.message);
        } else {
          if (callback) callback(false, "Unknown error!!");
        }
      },
    });
  }

  var btnfinalsubmit = document.getElementsByClassName("btnfinalsubmit");
  btnfinalsubmit.forEach((btn) => {
    btn.addEventListener("click", function () {
      var propertyStatus = $("input[name='applicationStatus']").val();
      var applicationType = $("select[name='applicationType']").val();
      if (validateForm3MUT()) {
        btn.textContent = "Submitting...";
        btn.disabled = true;

        console.log(applicationType);

        if (applicationType == "SUB_MUT") {
          mutationStepThird(function (success, result) {
            if (result.status) {
              btn.innerHTML =
                'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showSuccess(
                result.message,
                getBaseURL() + "/applications/history/details"
              );
            } else {
              btn.innerHTML =
                'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showError(result.message);
            }
          });
        }
      }

      if (validateForm2LUC() && applicationType === "LUC") {
        //   btn.textContent = "Submitting...";
        // btn.disabled = true;

        // var propertyStatus = $("input[name='applicationStatus']").val();
        // var applicationType = $("select[name='applicationType']").val();

        if (propertyStatus == "Lease Hold" && applicationType == "LUC") {
          console.log("validation completed");

          landUseChangeStep2(function (success, result) {
            if (success) {
              btn.innerHTML =
                'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showSuccess(
                result,
                getBaseURL() + "/applications/history/details"
              );
            } else {
              btn.innerHTML =
                'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>';
              btn.disabled = false;
              showError(result);
            }
          });
        }
      }

      if (propertyStatus == "Lease Hold" && applicationType == "DOA") {
        deedOfApartmentStepFinal(function (success, result) {
          if (result.status) {
            btn.innerHTML =
              'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>';
            btn.disabled = false;
            showSuccess(
              result.message,
              getBaseURL() + "/applications/history/details"
            );
          } else {
            btn.innerHTML =
              'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>';
            btn.disabled = false;
            showError(result.message);
          }
        });
      }
    });
  });
});

// for storing second step of mutation- Sourav Chauhan (19/sep/2024)
function mutationStepThird(callback) {
  var updateId = $("input[name='updateId']").val();
  var deathCertificateDeceasedName = $(
    "input[name='deathCertificateDeceasedName']"
  ).val();
  var deathCertificateDeathdate = $(
    "input[name='deathCertificateDeathdate']"
  ).val();
  var deathCertificateIssuedate = $(
    "input[name='deathCertificateIssuedate']"
  ).val();
  var deathCertificateDocumentCertificate = $(
    "input[name='deathCertificateDocumentCertificate']"
  ).val();
  var SaleDeedRegno = $("input[name='SaleDeedRegno']").val();
  var SaleDeedVolume = $("input[name='SaleDeedVolume']").val();
  var saleDeedBookNo = $("input[name='saleDeedBookNo']").val();
  var saleDeedPageNo = $("input[name='saleDeedPageNo']").val();
  var saleDeedFrom = $("input[name='saleDeedFrom']").val();
  var saleDeedTo = $("input[name='saleDeedTo']").val();
  var saleDeedRegDate = $("input[name='saleDeedRegDate']").val();
  var saleDeedRegOfficeName = $("input[name='saleDeedRegOfficeName']").val();
  var regWillDeedTestatorName = $(
    "input[name='regWillDeedTestatorName']"
  ).val();
  var regWillDeedRegNo = $("input[name='regWillDeedRegNo']").val();
  var regWillDeedVolume = $("input[name='regWillDeedVolume']").val();
  var regWillDeedBookNo = $("input[name='regWillDeedBookNo']").val();
  var regWillDeedPageNo = $("input[name='regWillDeedPageNo']").val();
  var regWillDeedFrom = $("input[name='regWillDeedFrom']").val();
  var regWillDeedTo = $("input[name='regWillDeedTo']").val();
  var regWillDeedRegDate = $("input[name='regWillDeedRegDate']").val();
  var regWillDeedRegOfficeName = $(
    "input[name='regWillDeedRegOfficeName']"
  ).val();
  var unregWillCodicilTestatorName = $(
    "input[name='unregWillCodicilTestatorName']"
  ).val();
  var unregWillCodicilDateOfWillCodicil = $(
    "input[name='unregWillCodicilDateOfWillCodicil']"
  ).val();
  var relinquishDeedRegNo = $("input[name='relinquishDeedRegNo']").val();
  var relinquishDeedVolume = $("input[name='relinquishDeedVolume']").val();
  var relinquishDeedBookno = $("input[name='relinquishDeedBookno']").val();
  var relinquishDeedPageno = $("input[name='relinquishDeedPageno']").val();
  var relinquishDeedFrom = $("input[name='relinquishDeedFrom']").val();
  var relinquishDeedTo = $("input[name='relinquishDeedTo']").val();
  var relinquishDeedRegdate = $("input[name='relinquishDeedRegdate']").val();
  var relinquishDeedRegname = $("input[name='relinquishDeedRegname']").val();
  var giftdeedRegno = $("input[name='giftdeedRegno']").val();
  var giftdeedVolume = $("input[name='giftdeedVolume']").val();
  var giftdeedBookno = $("input[name='giftdeedBookno']").val();
  var giftdeedPageno = $("input[name='giftdeedPageno']").val();
  var giftdeedFrom = $("input[name='giftdeedFrom']").val();
  var giftdeedTo = $("input[name='giftdeedTo']").val();
  var giftdeedRegdate = $("input[name='giftdeedRegdate']").val();
  var giftdeedRegOfficeName = $("input[name='giftdeedRegOfficeName']").val();
  var smcCertificateNo = $("input[name='smcCertificateNo']").val();
  var smcDateOfIssue = $("input[name='smcDateOfIssue']").val();
  var sbpDateOfIssue = $("input[name='sbpDateOfIssue']").val();
  var otherDocumentRemark = $("textarea[name='otherDocumentRemark']").val();
  var agreeConsent = $("#agreeconsent").is(":checked") ? 1 : 0;
  console.log(agreeConsent);

  var baseUrl = getBaseURL();
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    url: baseUrl + "/mutation-step-third",
    type: "POST",
    dataType: "JSON",
    data: {
      _token: csrfToken,
      updateId: updateId,
      deathCertificateDeceasedName: deathCertificateDeceasedName,
      deathCertificateDeathdate: deathCertificateDeathdate,
      deathCertificateIssuedate: deathCertificateIssuedate,
      deathCertificateDocumentCertificate: deathCertificateDocumentCertificate,
      SaleDeedRegno: SaleDeedRegno,
      SaleDeedVolume: SaleDeedVolume,
      saleDeedBookNo: saleDeedBookNo,
      saleDeedPageNo: saleDeedPageNo,
      saleDeedFrom: saleDeedFrom,
      saleDeedTo: saleDeedTo,
      saleDeedRegDate: saleDeedRegDate,
      saleDeedRegOfficeName: saleDeedRegOfficeName,
      regWillDeedTestatorName: regWillDeedTestatorName,
      regWillDeedRegNo: regWillDeedRegNo,
      regWillDeedVolume: regWillDeedVolume,
      regWillDeedBookNo: regWillDeedBookNo,
      regWillDeedPageNo: regWillDeedPageNo,
      regWillDeedFrom: regWillDeedFrom,
      regWillDeedTo: regWillDeedTo,
      regWillDeedRegDate: regWillDeedRegDate,
      regWillDeedRegOfficeName: regWillDeedRegOfficeName,
      unregWillCodicilTestatorName: unregWillCodicilTestatorName,
      unregWillCodicilDateOfWillCodicil: unregWillCodicilDateOfWillCodicil,
      relinquishDeedRegNo: relinquishDeedRegNo,
      relinquishDeedVolume: relinquishDeedVolume,
      relinquishDeedBookno: relinquishDeedBookno,
      relinquishDeedPageno: relinquishDeedPageno,
      relinquishDeedFrom: relinquishDeedFrom,
      relinquishDeedTo: relinquishDeedTo,
      relinquishDeedRegdate: relinquishDeedRegdate,
      relinquishDeedRegname: relinquishDeedRegname,
      giftdeedRegno: giftdeedRegno,
      giftdeedVolume: giftdeedVolume,
      giftdeedBookno: giftdeedBookno,
      giftdeedPageno: giftdeedPageno,
      giftdeedFrom: giftdeedFrom,
      giftdeedTo: giftdeedTo,
      giftdeedRegdate: giftdeedRegdate,
      giftdeedRegOfficeName: giftdeedRegOfficeName,
      smcCertificateNo: smcCertificateNo,
      smcDateOfIssue: smcDateOfIssue,
      sbpDateOfIssue: sbpDateOfIssue,
      otherDocumentRemark: otherDocumentRemark,
      agreeConsent: agreeConsent,
    },
    success: function (result) {
      if (result.status) {
        $("#btnfinalsubmit").html(
          'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#btnfinalsubmit").prop("disabled", false);
        if (callback) callback(true, result); // Call the callback with success
      } else {
        // Handle failure scenario
        $("#btnfinalsubmit").html(
          'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#btnfinalsubmit").prop("disabled", false);
        if (callback) callback(false, result); // Call the callback with failure
      }
    },
    error: function (err) {
      $("#submitbtn1").html(
        'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>'
      );
      $("#submitbtn1").prop("disabled", false);
      if (err.responseJSON && err.responseJSON.message) {
        if (callback) callback(false, err.responseJSON.message);
      } else {
        if (callback) callback(false, "Unknown error!!");
      }
    },
  });
}

/**Land use change Added by Nitin */
let propertyTypes; // to capture allowed property types for land use
function getLandUseChangeData(propertyId, updateId, callback) {
  var baseUrl = getBaseURL();

  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  $.ajax({
    url: baseUrl + "/application/fetch-luc-details",
    type: "POST",
    dataType: "JSON",
    data: {
      _token: csrfToken,
      propertyId: propertyId,
      updateId: updateId,
    },
    success: function (response) {
      if (response.status && response.status == "error") {
        if (callback) callback(false, response.details);
      } else {
        if (response.colony_id) {
          $("#luclocality").append(
            `<option value=${response.colony_id}>${response.colony_name}</option>`
          );
        }
        $("#lucblockno").val(response.block_no ?? "");
        $("#lucplotno").val(response.plot_no ?? "");
        $("#lucknownas").val(response.known_as ?? "");
        $("#lucarea").val(response.area ?? "");
        $("#leasetype").val(response.lease_type ?? "");
        let allowdChnage = response.allowdChnage;
        let firstRow = allowdChnage[0];
        $("#lucpropertytype").append(
          `<option value="${firstRow.property_type_from}"> ${firstRow.fromTypeName}</option>`
        );
        $("#lucpropertysubtype").append(
          `<option value="${firstRow.property_sub_type_from}"> ${firstRow.fromSubtypeName}</option>`
        );
        let keys = Object.keys(allowdChnage);
        propertyTypes = [];
        $("#lucpropertytypeto").html('<option value="">Select</option>');
        let propertyTypeMap = new Map();

        $.each(allowdChnage, function (index, row) {
          if (!propertyTypeMap.has(row.property_type_to)) {
            propertyTypeMap.set(row.property_type_to, {
              id: row.property_type_to,
              name: row.toTypeName,
              subtypes: [
                {
                  id: row.property_sub_type_to,
                  name: row.toSubtypeName,
                  rate: row.rate,
                },
              ],
            });
            var appendOption = `<option value="${row.property_type_to}" ${
              isEditing == 1 &&
              application.property_type_change_to == row.property_type_to
                ? "selected"
                : ""
            }>${row.toTypeName}</option>`;
            $("#lucpropertytypeto").append(appendOption);
          } else {
            let propertyType = propertyTypeMap.get(row.property_type_to);
            propertyType.subtypes.push({
              id: row.property_sub_type_to,
              name: row.toSubtypeName,
              rate: row.rate,
            });
          }
        });
        propertyTypes = Array.from(propertyTypeMap.values());

        if (isEditing) {
          $("#lucpropertytypeto").change();
        }
        if (callback) {
          callback(true, "");
        }
      }
    },
    error: (err) => {
      if (err.responseJSON && err.responseJSON.message) {
        if (callback) callback(false, err.responseJSON.message);
      } else {
        if (callback) callback(false, "Unknown error!!");
      }
    },
  });
}

$("#lucpropertytypeto").change(function () {
  let propertyTypeTo = $(this).val();
  if (propertyTypeTo != "") {
    $("#lucpropertysubtypeto").html('<option value="">Select</option>');
    let selectedPropertyType = propertyTypes.find(
      (type) => type.id == propertyTypeTo
    );
    if (selectedPropertyType) {
      let subtypes = selectedPropertyType.subtypes;
      $.each(subtypes, (i, val) => {
        $("#lucpropertysubtypeto").append(
          `<option value="${val.id}" data-rate="${val.rate}" ${
            isEditing == true &&
            application.property_subtype_change_to == val.id
              ? "selected"
              : ""
          }>${val.name}</option>`
        );
      });
    }
  }
});

function landUseChange(callback) {
  var baseUrl = getBaseURL();
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  var id = $("input[name='updateId']").val();
  var oldPropertyId = $("#propertyid").val();
  var propertyTypeFrom = $("#lucpropertytype").val();
  var propertySubtypeFrom = $("#lucpropertysubtype").val();
  var propertyTypeTo = $("#lucpropertytypeto").val();
  var propertySubtypeTo = $("#lucpropertysubtypeto").val();
  var statusofapplicant = $("#statusofapplicant").val();
  $.ajax({
    type: "POST",
    url: `${baseUrl}/application/luc-step-1`,
    data: {
      _token: csrfToken,
      id: id,
      oldPropertyId: oldPropertyId,
      propertyTypeFrom: propertyTypeFrom,
      propertySubtypeFrom: propertySubtypeFrom,
      propertyTypeTo: propertyTypeTo,
      propertySubtypeTo: propertySubtypeTo,
      applicantStatus: statusofapplicant,
    },
    success: function (response) {
      if (response.status == "success") {
        $("input[name='updateId']").val(response.id);
        if (callback) callback(true, response.message); // Call the callback with success
      } else {
        if (callback) callback(false, response.details);
      }
    },
    error: function (response) {
      if (callback) callback(false, response.responseJSON.error);
    },
  });
}

function landUseChangeStep2(callback) {
  var id = $("input[name='updateId']").val();
  var baseUrl = getBaseURL();
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  var consent = $("#lucagreeconsent").is(":checked");
  $.ajax({
    type: "POST",
    url: baseUrl + "/application/luc-step-2",
    data: { id: id, _token: csrfToken, consent: consent ? 1 : 0 },
    success: function (response) {
      if (response.status == "success") {
        if (callback) callback(true, response.message);
      } else {
        if (response.missing) {
          let errorArr = [];
          response.missing.forEach((element, index) => {
            $("#" + element.id + "Error").html("This document is required");
            errorArr.push(`${element.label} is required`);
          });
          if (callback) callback(false, errorArr);
        }
        if (response.message) {
          if (callback) callback(false, response.message);
        }
      }
    },
    error: function (err) {
      if (err.responseJSON && err.responseJSON.message) {
        if (callback) callback(false, err.responseJSON.message);
      } else {
      }
    },
  });
}

function deedOfApartmentStepFinal(callback) {
  var updateId = $("input[name='updateId']").val();
  var baseUrl = getBaseURL();
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  var agreeConsent = $("#agreeDOAConsent").is(":checked") ? 1 : 0;
  $.ajax({
    type: "POST",
    url: baseUrl + "/doa-step-final",
    dataType: "JSON",
    data: { updateId: updateId, _token: csrfToken, agreeConsent: agreeConsent },
    success: function (result) {
      if (result.status) {
        $("#btnfinalsubmit").html(
          'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#btnfinalsubmit").prop("disabled", false);
        if (callback) callback(true, result); // Call the callback with success
      } else {
        // Handle failure scenario
        $("#btnfinalsubmit").html(
          'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>'
        );
        $("#btnfinalsubmit").prop("disabled", false);
        if (callback) callback(false, result); // Call the callback with failure
      }
    },
    error: function (err) {
      $("#submitbtn1").html(
        'Proceed to Pay <i class="bx bx-right-arrow-alt ms-2"></i>'
      );
      $("#submitbtn1").prop("disabled", false);
      if (err.responseJSON && err.responseJSON.message) {
        if (callback) callback(false, err.responseJSON.message);
      } else {
        if (callback) callback(false, "Unknown error!!");
      }
    },
  });
}

//not required after design update - 04-10-2024
/* function landUseChangeStep3(callback) {
  var id = $("input[name='updateId']").val();
  var baseUrl = getBaseURL();
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  var consent = $("#lucagreeconsent").is(":checked");
  $.ajax({
    type: "POST",
    url: baseUrl + "/application/luc-step-3",
    data: { id: id, _token: csrfToken, consent: consent ? 1 : 0 },
    success: function (response) {
      if (response.status == "success") {
        if (callback) callback(true);
      } else {
        if (callback) callback(false, response.message);
      }
    },
    error: function (err) {
      console.log(err);
      if (err.responseJSON && err.responseJSON.message) {
        if (callback) callback(false, err.responseJSON.message);
      } else {
      }
    },
  });
} */

$("#lucpropertysubtypeto").change(function () {
  displayEstimatedCharges();
});
function displayEstimatedCharges() {
  var baseUrl = getBaseURL();
  var propertyId = $("#propertyid").val();
  var applicantType = $("#applicantType").val();
  $.ajax({
    type: "GET",
    url: baseUrl + "/land-use-change/property-type-options/" + propertyId,
    success: function (response) {
      if (response.status == "success" && response.propertyDetails) {
        var propertyDetails = response.propertyDetails;
        var calculationRate = $("#lucpropertysubtypeto option:selected").data(
          "rate"
        );

        var landValue = propertyDetails.land_value;
        var basicEstimate = "0.00";
        if (calculationRate > 0) {
          basicEstimate = (
            (((calculationRate * landValue) / 100) * 100) /
            100
          ).toFixed(2); //round to 2 decimal places
        }
        $("#estimatedCharges").html("₹ " + basicEstimate);
        $("#checkDetailsMessage").html(
          "You can check the detailed calculation in Know Your Charges &gt; Land Use Change"
        );
        if ($(".estimate").hasClass("d-none")) {
          $(".estimate").removeClass("d-none");
        }
      }
    },
    error: function (response) {
      console.log(response);
    },
  });
}

function getUserDetails() {
  var baseUrl = getBaseURL();
  var csrfToken = $('meta[name="csrf-token"]').attr("content");
  return $.ajax({
    url: baseUrl + "/fetch-user-details",
    type: "GET",
    dataType: "JSON",
    data: {
      _token: csrfToken,
    },
  });
}

function conversionStep1(propertyid, propertyStatus, callback) {
  var updateId = $("input[name='updateId']").val();
  var statusofapplicant = $("#statusofapplicant").val();
  var convname = $("input[name='convname']").val();
  var convgender = $("input[name='convgender']").val();
  var convage = $("input[name='convage']").val();
  var convRelationPrefix = $("#convprefixApp").html();
  var convRelationName = $("#convfathername").html();
  var convExecutedOnAsOnLease = $(
    "input[name='convExecutedOnAsOnLease']"
  ).val();
  var convaadhar = $("input[name='convaadhar']").val();
  var convpan = $("input[name='convpan']").val();
  var convmobilenumber = $("input[name='convmobilenumber']").val();
  var convNameAsOnLease = $("input[name='convNameAsOnLease']").val();
  var convRegnoAsOnLease = $("input[name='convRegnoAsOnLease']").val();
  var convBooknoAsOnLease = $("input[name='convBooknoAsOnLease']").val();
  var convVolumenoAsOnLease = $("input[name='convVolumenoAsOnLease']").val();
  var convPagenoFrom = $("input[name='convPagenoFrom']").val();
  var convPagenoTo = $("input[name='convPagenoTo']").val();
  var convRegdateAsOnLease = $("input[name='convRegdateAsOnLease']").val();
  var convCaseNo = $("input[name='convCaseNo']").val();
  var convCaseDetail = $("textarea[name='convCaseDetail']").val();
  var courtorderConversion = $(
    "input[name='courtorderConversion']:checked"
  ).val();

  var coapplicants = {};
  var formData = new FormData(); // Initialize FormData to handle both text and file data

  // Collect other fields (property details)
  formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
  formData.append("updateId", updateId);
  formData.append("propertyid", propertyid);
  formData.append("propertyStatus", propertyStatus);
  formData.append("statusofapplicant", statusofapplicant);
  formData.append("convname", convname);
  formData.append("convgender", convgender);
  formData.append("convage", convage);
  formData.append("convRelationPrefix", convRelationPrefix);
  formData.append("convRelationName", convRelationName);
  formData.append("convExecutedOnAsOnLease", convExecutedOnAsOnLease);
  formData.append("convaadhar", convaadhar);
  formData.append("convpan", convpan);
  formData.append("convmobilenumber", convmobilenumber);
  formData.append("convNameAsOnLease", convNameAsOnLease);
  formData.append("convRegnoAsOnLease", convRegnoAsOnLease);
  formData.append("convBooknoAsOnLease", convBooknoAsOnLease);
  formData.append("convVolumenoAsOnLease", convVolumenoAsOnLease);
  formData.append("convPagenoFrom", convPagenoFrom);
  formData.append("convPagenoTo", convPagenoTo);
  formData.append("convRegdateAsOnLease", convRegdateAsOnLease);
  formData.append("courtorderConversion", courtorderConversion);
  formData.append("convCaseNo", convCaseNo);
  formData.append("convCaseDetail", convCaseDetail);

  // Iterate over co-applicant inputs and add text fields to formData
  $("input[name^='convcoapplicant'], select[name^='convcoapplicant']").each(
    function () {
      var nameAttr = $(this).attr("name");
      var value = $(this).val();
      var matches = nameAttr.match(/coapplicant\[(\d+)]\[(\w+)\]/);
      console.log(matches);
      if (matches) {
        var index = matches[1];
        var field = matches[2];
        if (!coapplicants[index]) coapplicants[index] = {};
        coapplicants[index][field] = value;

        // Append the text field to FormData
        formData.append(`coapplicants[${index}][${field}]`, value);
        if ($(this).attr("type") == "file") {
          var fileInput = $(this)[0].files[0]; // Get the first file (since each input has one file)
          if (fileInput) {
            formData.append(`coapplicants[${index}][${field}]`, fileInput); // Append the file to FormData
          }
        }
      }
    }
  );

  // Iterate over co-applicant image inputs and add file fields to formData
  /*  $("input[type='file'][name^='convcoapplicantphoto']").each(function () {
    var nameAttr = $(this).attr("name");

    var fileInput = $(this)[0].files[0]; // Get the first file (since each input has one file)
    if (fileInput) {
      formData.append(nameAttr, fileInput); // Append the file to FormData
    }
  }); */

  var baseUrl = getBaseURL();

  $.ajax({
    url: baseUrl + "/conversion/step-1",
    type: "POST",
    data: formData, // Use FormData for sending files
    contentType: false, // Prevent jQuery from overriding content type
    processData: false, // Tell jQuery not to process data
    success: function (result) {
      $("#submitbtn1").html('Next <i class="bx bx-right-arrow-alt ms-2"></i>');
      $("#submitbtn1").prop("disabled", false);

      if (result.status == "success") {
        $("input[name='updateId']").val(result.data.id);
        $("input[name='lastPropertyId']").val(result.data.old_property_id);
        if (callback) callback(true, result); // Success
      } else {
        if (callback) callback(false, result); // Failure
      }
    },
    error: function (err) {
      $("#submitbtn1").html('Next <i class="bx bx-right-arrow-alt ms-2"></i>');
      $("#submitbtn1").prop("disabled", false);
      if (err.responseJSON && err.responseJSON.message) {
        if (callback) callback(false, err.responseJSON.message);
      } else {
        if (callback) callback(false, "Unknown error!!");
      }
    },
  });
}

//for show and hide the mutation third step documents according to th check and uncheck of documents at step first
//SOURAV CHAUHAN - 18/Oct/2024
document.querySelectorAll(".documentType").forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    const value = this.value;
    const checked = this.checked;
    handleCheckboxChange(value, checked);
  });
});

function handleCheckboxChange(value, checked) {
  if (checked) {
    $(`#${value}`).show();
    // Add your logic for checked state here
  } else {
    $(`#${value}`).hide();
    // Add your logic for unchecked state here
  }
}
