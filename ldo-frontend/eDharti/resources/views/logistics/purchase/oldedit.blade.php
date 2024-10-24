@extends('layouts.app')

@section('title', 'Edit Purchase')

@section('content')

<style>
    .alert-danger {
        display: none !important;
    }

    .repeater-item-template {
        display: none;
    }

    .warning_icon {
        width: 80px;
        margin-bottom: 10px;
    }

    .btn-width {
        width: 40%;
    }

    .remove-btn {
        margin-top: 28px;
    }

    .error-message {
        color: red;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }

    .loading-icon {
        display: none;
        margin-left: 10px;
    }
    .btn-loading {
        pointer-events: none;
        opacity: 0.6;
    }

    input.error, select.error {
        border-color: red;
    }

</style>

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Logistic</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Purchase</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end py-3">
            <a href="{{ url('logistic/purchase') }}">
                <button type="button" class="btn btn-danger px-2 mx-2">← Back</button>
            </a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="purchaseForm" method="POST" action="{{ route('purchase.update', $purchaseId) }}">
            @csrf
            @method('PUT')

            <div class="common-form m-3" style="border: 1px solid #ddd; padding: 5px; margin-bottom: 10px; border-radius: 5px;">
                <div class="form-row m-3">
                    <div class="form-group col-md-6">
                        <label class="form-label" for="vendor_supplier_name">Vendor/Supplier Name:</label>
                        <input type="text" id="vendor_supplier_name" class="form-control"
                            value="{{ $purchaseItems->first()->SupplierVendorDetails->name }}" disabled>
                        <input type="hidden" name="vendor_supplier_id" value="{{ $purchaseItems->first()->vendor_supplier_id }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="form-label" for="purchased_date">Purchased Date:</label>
                        <input type="date" id="purchased_date" class="form-control"
                            value="{{ $purchaseItems->first()->purchased_date }}" disabled>
                        <input type="hidden" name="purchased_date" value="{{ $purchaseItems->first()->purchased_date }}">
                    </div>
                </div>

               <!-- Existing Purchase Items (Read-Only) -->
                        @foreach($purchaseItems as $item)
                            <div class="form-row m-3">
                                @include('logistics.purchase.repeater-edit', [
                                    'prefix' => '', 
                                    'purchaseCategory' => $purchaseCategory, 
                                    'purchaseItem' => $purchaseItem, 
                                    'item' => $item,
                                    'readOnly' => true  
                                ])
                            </div>
                            <hr>
                        @endforeach


                <!-- New Repeater Items (Editable) -->
                <div id="repeater-container">
                    <div class="repeater-box repeater-item-template">
                        <div class="row m-1 mb-3 align-items-center">
                            @include('logistics.purchase.repeater-edit', [
                                'prefix' => 'repeater[0][', 
                                'purchaseCategory' => $purchaseCategory, 
                                'purchaseItem' => $purchaseItem
                            ])
                            <div class="col-md-1">
                                <div class="text-end">
                                    <button type="button" class="btn btn-danger remove-btn px-4" data-toggle="tooltip"
                                        title="Click to delete this form" data-bs-target="#ModalDelete">
                                        <i class="fadeIn animated bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end p-3">
                    <button type="button" id="duplicate-form" class="btn btn-outline-primary repeater-add-btn"
                        class="btn btn-outline-primary repeater-add-btn" data-toggle="tooltip" title="Add more items"
                        data-placement="bottom">
                        <i class="bx bx-plus me-0"></i>
                    </button>
                </div>
                <button type="button" id="openSubmitModal" class="btn btn-primary mx-3 mb-4">
                    Update
                    <span class="spinner-border spinner-border-sm loading-icon" role="status" aria-hidden="true"></span>
                </button>

        </form>
    </div>
</div>

@include('include.alerts.delete-confirmation')
@include('include.alerts.update-confirmation')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        var repeaterIndex = {{ count($purchaseItems) }};
        var repeaterToDelete;
        var availableUnitsCache = {};

        function fetchAvailableUnits(logisticItemId, callback) {
            if (availableUnitsCache[logisticItemId] !== undefined) {
                callback(availableUnitsCache[logisticItemId]);
            } else {
                $.ajax({
                    url: `/logistic/available-units/${logisticItemId}`,
                    type: 'GET',
                    success: function (response) {
                        availableUnitsCache[logisticItemId] = response.available_units;
                        callback(response.available_units);
                    },
                    error: function () {
                        callback(0); 
                    }
                });
            }
        }

        $('#duplicate-form').click(function () {
            var newRepeaterItem = $('.repeater-item-template:first').clone();

            newRepeaterItem.removeClass('repeater-item-template').removeAttr('style');
            newRepeaterItem.find('select, input').each(function () {
                var name = $(this).data('name');
                $(this).attr('name', 'repeater[' + repeaterIndex + '][' + name + ']').val('');
                $(this).next('.error-message').remove(); 
            });
            $('#repeater-container').append(newRepeaterItem);
            $('#repeater-container').append('<hr>');  // Append an <hr> after the new repeater item
            repeaterIndex++;

            newRepeaterItem.find('.remove-btn').click(function () {
                repeaterToDelete = $(this).closest('.repeater-box');
                $('#ModalDelete').modal('show');
            });
        });

        $(document).on('click', '.remove-btn', function () {
            repeaterToDelete = $(this).closest('.repeater-box');
            $('#ModalDelete').modal('show');
        });

        $('#confirmDelete').click(function () {
            repeaterToDelete.remove();
            repeaterIndex--;
            $('#ModalDelete').modal('hide');
        });

        $('form').on('submit', function () {
            $('.repeater-item-template').remove();
        });

        function validateFields(callback) {
            var isValid = true;
            var errorMessages = [];
            var pendingValidations = 0;

            $('input[name^="repeater"], select[name^="repeater"]').each(function () {
                var $row = $(this).closest('.row');
                var purchasedUnit = $row.find('input[name$="[purchased_unit]"]').val();
                var reducedUnit = $row.find('input[name$="[reduced_unit]"]').val();
                var perUnitCost = $row.find('input[name$="[per_unit_cost]"]').val();
                var logisticItemId = $row.find('select[name$="[logistic_items_id]"]').val();
                var categoryId = $row.find('select[name$="[category_id]"]').val();

                // Remove any existing error messages
                $row.find('.error-message').remove(); 
                // Remove error class from all inputs
                $row.find('input, select').removeClass('error'); 

                if (!logisticItemId) {
                    isValid = false;
                    errorMessages.push('Item name is required.');
                    $row.find('select[name$="[logistic_items_id]"]').addClass('error').after('<div class="error-message">Item name is required.</div>');
                }

                // if (!categoryId) {
                //     isValid = false;
                //     errorMessages.push('Category name is required.');
                //     $row.find('select[name$="[category_id]"]').addClass('error').after('<div class="error-message">Category name is required.</div>');
                // }

                if (!purchasedUnit && !reducedUnit) {
                    isValid = false;
                    errorMessages.push('Either purchased unit or reduced unit must be entered.');
                    $row.find('input[name$="[purchased_unit]"]').addClass('error').after('<div class="error-message">Either purchased unit or reduced unit must be entered.</div>');
                    $row.find('input[name$="[reduced_unit]"]').addClass('error').after('<div class="error-message">Either purchased unit or reduced unit must be entered.</div>');
                }

                if (purchasedUnit && reducedUnit) {
                    isValid = false;
                    errorMessages.push('Both purchased unit and reduced unit cannot be entered at the same time.');
                    $row.find('input[name$="[purchased_unit]"]').addClass('error').after('<div class="error-message">Purchased unit and Reduced unit cannot be entered at the same time.</div>');
                    $row.find('input[name$="[reduced_unit]"]').addClass('error').after('<div class="error-message">Purchased unit and Reduced unit cannot be entered at the same time.</div>');
                }

                if (purchasedUnit != null && purchasedUnit < 0) {
                    isValid = false;
                    errorMessages.push('Purchased unit must be greater than 0.');
                    $row.find('input[name$="[purchased_unit]"]').addClass('error').after('<div class="error-message">Purchased unit must be greater than 0.</div>');
                }

                if (reducedUnit != null && reducedUnit < 0) {
                    isValid = false;
                    errorMessages.push('Reduced unit must be greater than 0.');
                    $row.find('input[name$="[reduced_unit]"]').addClass('error').after('<div class="error-message">Reduced unit must be greater than 0.</div>');
                }

                if (perUnitCost && perUnitCost <= 0) {
                    isValid = false;
                    errorMessages.push('Per unit cost cannot be negative.');
                    $row.find('input[name$="[per_unit_cost]"]').addClass('error').after('<div class="error-message">Per unit cost cannot be negative.</div>');
                }

                // Validate reduced unit against available units
                if (reducedUnit) {
                    pendingValidations++;
                    fetchAvailableUnits(logisticItemId, function (availableUnits) {
                        if (reducedUnit > availableUnits) {
                            isValid = false;
                            $row.find('.error-message').remove();  
                            errorMessages.push('Reduced unit cannot be greater than available stock.');
                            $row.find('input[name$="[reduced_unit]"]').addClass('error').after('<div class="error-message">Reduced unit cannot be greater than available stock.</div>');
                        }
                        pendingValidations--;
                        if (pendingValidations === 0) {
                            callback(isValid, errorMessages);
                        }
                    });
                }
            });

            if (pendingValidations === 0) {
                callback(isValid, errorMessages);
            }
        }

        // Real-time validation
        $(document).on('input', 'input[name^="repeater"][name$="[purchased_unit]"], input[name^="repeater"][name$="[reduced_unit]"], input[name^="repeater"][name$="[per_unit_cost]"], select[name^="repeater"][name$="[logistic_items_id]"], select[name^="repeater"][name$="[category_id]"]', function () {
            validateFields(function (isValid, errorMessages) {
                // Error messages are handled inside validateFields
            });
        });

        // Automatically select category based on logistic item by Swati Mishra 25-07-2024
        function updateCategory(selectElement) {
            var logisticItems = @json($purchaseItem->keyBy('id'));
            var selectedItemId = selectElement.val();
            var categorySelect = selectElement.closest('.row').find('select[name$="[category_id]"]');

            if (selectedItemId && logisticItems[selectedItemId]) {
                categorySelect.val(logisticItems[selectedItemId].category_id).change();
            } else {
                categorySelect.val('').change();
            }
        }

        $(document).on('change', 'select[name="logistic_items_id"], select[name^="repeater"][name$="[logistic_items_id]"]', function () {
            updateCategory($(this));
        });

        // Open submit confirmation modal
        $('#openSubmitModal').click(function () {
            validateFields(function (isValid, errorMessages) {
                if (isValid) {
                    $('#confirmUpdateModal').modal('show');
                }
            });
        });

        // Confirm submit
        $('#confirmSubmit').click(function () {
            $('#purchaseForm').submit();
        });
    });
</script>






@endsection
