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
    $(this).val($(this).val().replace(/[^0-9]/g, ""));
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
  $(".pan_number_format").on("keypress", function (event) {
    var charCode = event.which;

    if (charCode === 0 || charCode === 8 || charCode === 9 || charCode === 13) {
      return;
    }

    var charStr = String.fromCharCode(charCode).toUpperCase();

    var currentLength = $(this).val().length;

    if (currentLength < 5 && !/[A-Z]/.test(charStr)) {
      event.preventDefault();
    } else if (
      currentLength >= 5 &&
      currentLength < 9 &&
      !/[0-9]/.test(charStr)
    ) {
      event.preventDefault();
    } else if (currentLength === 9 && !/[A-Z]/.test(charStr)) {
      event.preventDefault();
    }
  });
  // Share
  $(".alphaNum_slash_modulus").on("input", function () {
    var value = $(this).val();
    var sanitizedValue = value.replace(/[^a-zA-Z0-9\/%]/g, "");
    if (value !== sanitizedValue) {
      $(this).val(sanitizedValue);
    }
  });
});

$("#repeater").createRepeater({
  showFirstItemToDefault: true,
});
$("#repeaterLessee").createRepeater({
  showFirstItemToDefault: true,
});
$(document).ready(function() {
  // Function to toggle docName field visibility
  $('#selectdocselfattesteddocname').change(function() {
    if ($(this).val() === "Other") {
      $('#docName').show();  // Show the docName field
    } else {
      $('#docName').hide();  // Hide the docName field
    }
  });
});
// End Field Validation
$(document).ready(function () {

  $('#freehold').change(function () {
    if ($(this).prop('checked')) {
        $('#freeHoldDiv').show();
        $('#leaseHoldDiv').hide();
        $('.landusechangeDiv').hide();
        $('.FHSubstitutionMutationdiv').hide();
        $('#freeleasetitle').text('Details of Conveyance Deed');
        $('.LHConversiondiv').hide();
    } else {
        $('#freeleasetitle').text('');
        $('#freeHoldDiv').hide();
        $('.LHConversiondiv').hide();
    }
});

$('#leasehold').change(function () {
  if ($(this).prop('checked')) {
      $('#freeHoldDiv').hide();
      $('#leaseHoldDiv').show();
      $('.FHSubstitutionMutationdiv').hide();
      $('.landusechangeDiv').hide();
      $('#freeleasetitle').text('Details of Lease Deed');
  } else {
      $('#freeleasetitle').text('');
      $('#leaseHoldDiv').hide();
      $('.LHConversiondiv').hide();
  }
});

$('#Salepermission').change(function () {
  if ($(this).prop('checked')) {
      $('.FHSubstitutionMutationdiv').hide();
      $('.LHConversiondiv').hide();
      $('.landusechangeDiv').hide();
  } else {
    $('.FHSubstitutionMutationdiv').hide();
      $('.LHConversiondiv').hide();
      $('.landusechangeDiv').hide();
    // Sale Permission Hide
  }
});

$('#Conversion').change(function () {
  if ($(this).prop('checked')) {
      $('.FHSubstitutionMutationdiv').hide();
      $('.landusechangeDiv').hide();
      $('.LHConversiondiv').show();
  } else {
    // Sale Permission Hide
    $('.LHConversiondiv').hide();
  }
});

  $('#FHSubstitutionMutation').on('change', function () {
    if ($(this).is(':checked')) {
      $('.FHSubstitutionMutationdiv').show();
      $('.landusechangeDiv').hide();
      $('.LHConversiondiv').hide();
    } else {
      $('.FHSubstitutionMutationdiv').hide();
      $('.landusechangeDiv').hide();
      $('.LHConversiondiv').hide();
    }
  });


  $('#landusechange').on('change', function () {
    if ($(this).is(':checked')) {
      $('.landusechangeDiv').show();
      $('.FHSubstitutionMutationdiv').hide();
      $('#finalStateTitle').text("Terms & Conditions");
      $('#LUCHideTitle').hide();
      $('#finalStateSubtitle').hide()
      $('.LHConversiondiv').hide()
    } else {
      $('.landusechangeDiv').hide();
    }
  });

  $('#LHSubstitutionMutation').change(function () {
    if ($(this).prop('checked')) {
        $('.FHSubstitutionMutationdiv').show();
        $('.landusechangeDiv').hide();
        $('#freeleasetitle').text('Details of Lease Deed');
        $('.LHConversiondiv').hide();
    } else {
        $('#freeleasetitle').text('');
        $('.FHSubstitutionMutationdiv').hide();
        $('.LHConversiondiv').hide();
    }
  });

  // input#LHPropertyCertificate
  $('#LHPropertyCertificate').change(function () {
    if ($(this).prop('checked')) {
        $('.FHSubstitutionMutationdiv').hide();
        $('.landusechangeDiv').hide();
        $('.LHConversiondiv').hide();
    } else {
        $('#freeleasetitle').text('');
    }
  });  
  // $('#LHSubstitutionMutation').change(function () {
  //   $('.FHSubstitutionMutationdiv').show();
  // });

  // Optionally, handle the initial state when the page loads
  if ($('#FHSubstitutionMutation').is(':checked')) {
    $('.FHSubstitutionMutationdiv').show();
  } else {
    $('.FHSubstitutionMutationdiv').hide();
  }

  // $('#LHSubstitutionMutation').change(function () {
  //   $('.FHSubstitutionMutationdiv').show();
  // });
  $('#NOC').change(function () {
    $('.FHSubstitutionMutationdiv').hide();
    $('#LHSubstitutionMutationdiv').hide();
  });
  $('#PropertyCertificate').change(function () {
    $('.FHSubstitutionMutationdiv').hide();
    $('#LHSubstitutionMutationdiv').hide();
  });
  $('#YesMortgaged').change(function () {
    $('#yesRemarksDiv').show();
  });
  $('#NoMortgaged').change(function () {
    $('#yesRemarksDiv').hide();
  });

  $('#YesCourtOrder').change(function () {
    $('#yescourtorderDiv').show();
  });
  $('#NoCourtOrder').change(function () {
    $('#yescourtorderDiv').hide();
  });

  $('#YesCourtOrderConversion').change(function () {
    $('#yescourtorderConversionDiv').show();
  });
  $('#NoCourtOrderConversion').change(function () {
    $('#yescourtorderConversionDiv').hide();
  });

  // $('#YesMortgaged').change(function () {
  //   if ($(this).prop('checked')) {
  //     $('#yesRemarksDiv').show();
  //   } else {
  //     $('#yesRemarksDiv').hide();
  //   }
  // });

  // $('#YesMortgagedConversion').change(function () {
  //   if ($(this).prop('checked')) {
  //     $('#yesRemarksDivConversion').show();
  //   } else {
  //     $('#yesRemarksDivConversion').hide();
  //   }
  // });

  $('#YesMortgagedConversion').change(function () {
    $('#yesRemarksDivConversion').show();
  });
  $('#NoMortgagedConversion').change(function () {
    $('#yesRemarksDivConversion').hide();
  });

  $('#YesDeedLostConversion').change(function () {
    $('#yesDeedLostDivConversion').show();
  });
  $('#NoMortgagedConversion').change(function () {
    $('#NoDeedLostConversion').hide();
  });

});

// Checkbox Group Only One Selection
$("input:checkbox").on('click', function () {
  var $box = $(this);
  if ($box.is(":checked")) {
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }
});



document.addEventListener('DOMContentLoaded', function () {
  var form1 = document.getElementById('newstep-vl-1');
  var form2 = document.getElementById('newstep-vl-2');
  var form3 = document.getElementById('newstep-vl-3');

  // Form 1 Fields
  var propertyid = document.getElementById('propertyid');

  // Form 1 Errors
  var propertyIdError = document.getElementById('propertyIdError');
  var applicationStatusError = document.getElementById('applicationStatusError');
  var freeHoldOptionsStatusError = document.getElementById('freeHoldOptionsStatusError');
  var leaseHoldOptionsStatusError = document.getElementById('leaseHoldOptionsStatusError');


  function validatePropertyId() {
    var propertyidValue = propertyid.value.trim();
    if (propertyidValue === '') {
      propertyIdError.textContent = 'Property ID is required';
      propertyIdError.style.display = 'block';
      return false;
    } else {
      propertyIdError.style.display = 'none';
      return true;
    }
  }

  function validateApplicationStatus() {
    var radioOptions = document.getElementsByName('applicationStatus');
    var isRadioSelected = false;
    for (var i = 0; i < radioOptions.length; i++) {
      if (radioOptions[i].checked) {
        isRadioSelected = true;
        break;
      }
    }

    if (!isRadioSelected) {
      applicationStatusError.textContent = 'Please select an option';
      applicationStatusError.style.display = 'block';
      return false;
    } else {
      applicationStatusError.style.display = 'none';
      return true;
    }
  }

  function validateFreeHoldOptions() {
    var checkboxOptions = document.getElementsByName('freehold_options');
    var isCheckboxSelected = false;
    for (var i = 0; i < checkboxOptions.length; i++) {
      if (checkboxOptions[i].checked) {
        isCheckboxSelected = true;
        break;
      }
    }

    if (!isCheckboxSelected) {
      freeHoldOptionsStatusError.textContent = 'Please select an option';
      freeHoldOptionsStatusError.style.display = 'block';
      return false;
    } else {
      freeHoldOptionsStatusError.style.display = 'none';
      return true;
    }
  }

  function validateLeaseHoldOptions() {
    var checkboxOptions = document.getElementsByName('leasehold_options');
    var isCheckboxSelected = false;
    for (var i = 0; i < checkboxOptions.length; i++) {
      if (checkboxOptions[i].checked) {
        isCheckboxSelected = true;
        break;
      }
    }

    if (!isCheckboxSelected) {
      leaseHoldOptionsStatusError.textContent = 'Please select an option';
      leaseHoldOptionsStatusError.style.display = 'block';
      return false;
    } else {
      leaseHoldOptionsStatusError.style.display = 'none';
      return true;
    }
  }

  // Validate Form 1
  function validateForm1() {
    var isPropertyIdValid = validatePropertyId();
    var isApplicationStatusValid = validateApplicationStatus();
    var isFreeHoldOptionsValid = validateFreeHoldOptions();
    var isLeaseHoldOptionsValid = validateLeaseHoldOptions();

    return isPropertyIdValid && isApplicationStatusValid && (isFreeHoldOptionsValid || isLeaseHoldOptionsValid);
  }

  form1.addEventListener('button', function (event) {
    event.preventDefault();
    if (validateForm1()) {
      alert('Form submitted successfully');
    }
  });

  var submitButton1 = document.getElementById('submitbtn1');
  // var $submitButtonOne = $(submitButton1);
  submitButton1.addEventListener('click', function () {
    if (validateForm1()) {
      // for submitting the first step of application  - Sourav Chauhan (17/sep/2024)
      submitButton1.textContent = 'Submitting...';
      submitButton1.disabled = true;
      var propertyid = $('#propertyid').val()
      var propertyStatus = $("input[name='applicationStatus']").val();
      var checkedValues = $("input[name='freehold_options']:checked").map(function() {
        return $(this).val();
    }).get();
    //for mutation - Sourav Chauhan (17/sep/2024)
      if(propertyStatus == '952' && checkedValues[0] == 'Substitution/Mutation'){
        mutation(propertyid,propertyStatus,function(success, result){
          if (success) {
            stepper3.next()
        } else {
        }
        })
      }
    }
  });


  function getBaseURL() {
    const { protocol, hostname, port } = window.location;
    return `${protocol}//${hostname}${port ? ':' + port : ''}`;
}

// for storing first step of mutation- Sourav Chauhan (17/sep/2024)
  function mutation(propertyid,propertyStatus,callback){
    var updateId = $("input[name='updateId']").val();
    var statusofapplicant = $('#statusofapplicant').val()
    var mutNameApp = $("input[name='mutNameApp']").val();
    var mutGenderApp = $("input[name='mutGenderApp']").val();
    var mutAgeApp = $("input[name='mutAgeApp']").val();
    var mutFathernameApp = $("input[name='mutFathernameApp']").val();
    var mutAadharApp = $("input[name='mutAadharApp']").val();
    var mutPanApp = $("input[name='mutPanApp']").val();
    var mutMobilenumberApp = $("input[name='mutMobilenumberApp']").val();

    var mutNameAsConLease = $("input[name='mutNameAsConLease']").val();
    var mutFathernameAsConLease = $("input[name='mutFathernameAsConLease']").val();
    var mutRegnoAsConLease = $("input[name='mutRegnoAsConLease']").val();
    var mutBooknoAsConLease = $("input[name='mutBooknoAsConLease']").val();
    var mutVolumenoAsConLease = $("input[name='mutVolumenoAsConLease']").val();
    var mutPagenoAsConLease = $("input[name='mutPagenoAsConLease']").val();
    var mutRegdateAsConLease = $("input[name='mutRegdateAsConLease']").val();
    var soughtByApplicant = $("#soughtByApplicant").val();
    var mutPropertyMortgaged = $("input[name='mutPropertyMortgaged']").val();
    var mutMortgagedRemarks = $("input[name='mutMortgagedRemarks']").val();
    var mutCourtorder = $("input[name='mutCourtorder']").val();
    var coapplicants = {};

    // Iterate over all input elements with names starting with 'coapplicant'
    $("input[name^='coapplicant'], select[name^='coapplicant']").each(function() {
      var nameAttr = $(this).attr('name');
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
    });

    var baseUrl = getBaseURL();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url: baseUrl+"/mutation-step-first",
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
        mutFathernameApp: mutFathernameApp,
        mutAadharApp: mutAadharApp,
        mutPanApp: mutPanApp,
        mutMobilenumberApp: mutMobilenumberApp,
        coapplicants: coapplicants,
        mutNameAsConLease : mutNameAsConLease,
        mutFathernameAsConLease : mutFathernameAsConLease,
        mutRegnoAsConLease : mutRegnoAsConLease,
        mutBooknoAsConLease : mutBooknoAsConLease,
        mutVolumenoAsConLease : mutVolumenoAsConLease,
        mutPagenoAsConLease : mutPagenoAsConLease,
        mutRegdateAsConLease : mutRegdateAsConLease,
        soughtByApplicant : soughtByApplicant,
        mutPropertyMortgaged : mutPropertyMortgaged,
        mutMortgagedRemarks : mutMortgagedRemarks,
        mutCourtorder : mutCourtorder
      },
      success: function (result) {
        if (result.status) {
          $('#submitbtn1').html('Submitted <i class="bx bx-right-arrow-alt ms-2"></i>');
          $('#submitbtn1').prop('disabled', false);
          $("input[name='updateId']").val(result.data);
          if (callback) callback(true, result); // Call the callback with success
        } else {
          // Handle failure scenario
          $('#submitbtn1').html('Failed <i class="bx bx-right-arrow-alt ms-2"></i>');
          $('#submitbtn1').prop('disabled', false);
          $("input[name='updateId']").val(result.data);
          if (callback) callback(false, result); // Call the callback with failure
        }
      },
      error: function(xhr, status, error) {
        // Handle error scenario
        $('#submitbtn1').html('Error <i class="bx bx-right-arrow-alt ms-2"></i>');
        $('#submitbtn1').prop('disabled', false);
        if (callback) callback(false, { xhr, status, error }); // Call the callback with error
    }
    })
  }
  

  //for step second***********************************
  var submitButton2 = document.getElementById('submitbtn2');
  submitButton2.addEventListener('click', function () {
    // if (validateForm2()) {
      mutationStepSecond(function(success, result){
        if (success) {
          stepper3.next()
        } else {
        }
      });
    // }
  });

  // for storing second step of mutation- Sourav Chauhan (19/sep/2024)
  function mutationStepSecond(callback){
    var updateId = $("input[name='updateId']").val();
    var affidavitsDateAttestation = $("input[name='affidavitsDateAttestation']").val()
    var affidavitsAttestedby = $("input[name='affidavitsAttestedby']").val()
    var indemnityBondDateAttestation = $("input[name='indemnityBondDateAttestation']").val()
    var indemnityBondattestedby = $("input[name='indemnityBondattestedby']").val()
    var leaseConvDeedDateOfExecution = $("input[name='leaseConvDeedDateOfExecution']").val()
    var leaseConvDeedLesseename = $("input[name='leaseConvDeedLesseename']").val()
    var panCertificateNo = $("input[name='panCertificateNo']").val()
    var panDateIssue = $("input[name='panDateIssue']").val()
    var aadharCertificateNo = $("input[name='aadharCertificateNo']").val()
    var aadharDateIssue = $("input[name='aadharDateIssue']").val()
    var newspaperName = $("input[name='newspaperName']").val()
    var publicNoticeDate = $("input[name='publicNoticeDate']").val()

    var baseUrl = getBaseURL();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url: baseUrl+"/mutation-step-second",
      type: "POST",
      dataType: "JSON",
      data: {
        _token: csrfToken,
        updateId: updateId,
        affidavitsDateAttestation: affidavitsDateAttestation,
        affidavitsAttestedby: affidavitsAttestedby,
        indemnityBondDateAttestation: indemnityBondDateAttestation,
        indemnityBondattestedby: indemnityBondattestedby,
        leaseConvDeedDateOfExecution: leaseConvDeedDateOfExecution,
        leaseConvDeedLesseename: leaseConvDeedLesseename,
        panCertificateNo: panCertificateNo,
        panDateIssue: panDateIssue,
        aadharCertificateNo: aadharCertificateNo,
        aadharDateIssue: aadharDateIssue,
        newspaperName: newspaperName,
        publicNoticeDate: publicNoticeDate
      },
      success: function (result) {
        if (result.status) {
          $('#submitbtn2').html('Submitted <i class="bx bx-right-arrow-alt ms-2"></i>');
          $('#submitbtn2').prop('disabled', false);
          if (callback) callback(true, result); // Call the callback with success
        } else {
          // Handle failure scenario
          $('#submitbtn2').html('Failed <i class="bx bx-right-arrow-alt ms-2"></i>');
          $('#submitbtn2').prop('disabled', false);
          if (callback) callback(false, result); // Call the callback with failure
        }
      },
      error: function(xhr, status, error) {
        // Handle error scenario
        $('#submitbtn2').html('Error <i class="bx bx-right-arrow-alt ms-2"></i>');
        $('#submitbtn2').prop('disabled', false);
        if (callback) callback(false, { xhr, status, error }); // Call the callback with error
      }
    })
  }


  // var btnfinalsubmit = document.getElementById('btnfinalsubmit');
  // btnfinalsubmit.addEventListener('click', function () {
  //   if (validateForm3()) {
  //     this.removeAttribute('type', 'submit');
  //   } else {
  //     this.setAttribute('type', 'button');
  //   }
  // });
});
