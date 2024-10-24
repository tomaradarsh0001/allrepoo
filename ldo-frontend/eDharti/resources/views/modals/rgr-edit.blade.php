<div class="modal fade" id="editRGRModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
                @if(!is_null($shouldEdit))
                <div class="row">
                    <div class="col-lg-12 mb-2 heading-container">

                        <button type="button" class="btn btn-primary d-none" id="btnPostAllSelected">Update RGR for selcted properties</button>
                    </div>
                    @if(!empty($shouldEdit['land_status_change']))
                    <div class="col d-none" id="detail-prop-status-changed">

                        <h6 class="text-center mb-2">Land staus changed</h6>


                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class=" check-header">
                                    </th>
                                    <th>Address</th>
                                    <th>From Date</th>
                                    <th>Till Date</th>
                                    <th>Free Hold Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shouldEdit['land_status_change'] as $prop)
                                <tr>
                                    <th>
                                        <input type="checkbox" class="check-body" data-old-id="{{$prop->rgr_id}}" data-from_date="{{$prop->from_date}}" data-till_date="{{$prop->transferDate}}" data-reason_for_change="2">
                                    </th>
                                    <td>{{$prop->address}}</td>
                                    <td>{{date('d-m-Y', strtotime($prop->from_date))}}</td>
                                    <td>{{date('d-m-Y', strtotime($prop->till_date))}}</td>
                                    <td>{{date('d-m-Y', strtotime($prop->transferDate))}}</td>
                                    <td>
                                        <button class="btn btn-warning btn-update-rgr-row">Update</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @if(!empty($shouldEdit['re_entered']))
                    <div class="col d-none" id="detail-prop-reentered">
                        <h6 class="text-center mb-2 float-left">Property Reentered</h6>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class=" check-header">
                                    </th>
                                    <th>Address</th>
                                    <th>From Date</th>
                                    <th>Till Date</th>
                                    <th>Re-entry Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shouldEdit['re_entered'] as $prop)
                                <tr>
                                    <th>
                                        <input type="checkbox" class="check-body" data-old-id="{{$prop->rgr_id}}" data-from_date="{{$prop->from_date}}" data-till_date="{{$prop->reentry_date}}" data-reason_for_change="3">
                                    </th>
                                    <td>{{$prop->address}}</td>
                                    <td>{{date('d-m-Y', strtotime($prop->from_date))}}</td>
                                    <td>{{date('d-m-Y', strtotime($prop->till_date))}}</td>
                                    <td>{{date('d-m-Y', strtotime($prop->reentry_date))}}</td>
                                    <td>
                                        <button class="btn btn-warning btn-update-rgr-row">Update</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let postData = [];

    $('#viewLandStatusUpdate').click(function() {

        if ($('#detail-prop-reentered').length > 0 && !$('#detail-prop-reentered').hasClass('d-none')) {
            $('#detail-prop-reentered').addClass('d-none');

        }
        if ($('#detail-prop-status-changed').hasClass('d-none')) {
            $('#detail-prop-status-changed').removeClass('d-none')
        }
        $('#editRGRModal').modal('show');
    });
    $('#viewReEteredUpdate').click(function() {
        if ($('#detail-prop-status-changed').length > 0 && !$('#detail-prop-status-changed').hasClass('d-none')) {
            $('##detail-prop-status-changed').addClass('d-none');

        }
        if ($('#detail-prop-reentered').hasClass('d-none')) {
            $('#detail-prop-reentered').removeClass('d-none')
        }
        $('#editRGRModal').modal('show');
    });
    $('.check-header').change(function() { //when check box in header is clicked then add/ remove all the rows in the table
        var table = $(this).closest('table');
        var isChecked = $(this).is(':checked');
        if (table.length > 0) {
            // table.find('.check-body').prop('checked', $(this).is(':checked')).trigger('change');
            table.find('.check-body').each(function(i, elm) {
                $(elm).prop('checked', isChecked).trigger('change');
            })
        }
    });

    $('.check-body').change(function() {
        var oldId = $(this).data('oldId');
        if ($(this).is(':checked')) {
            //add data for selected row in postData;
            postData.push({
                oldId: oldId,
                from_date: $(this).data('from_date'),
                till_date: $(this).data('till_date'),
                reason_for_change: $(this).data('reason_for_change')
            });
        } else {
            postData.splice(postData.findIndex(item => item.oldId == oldId), 1);
        }
        if (postData.length > 0) {
            if ($('#btnPostAllSelected').hasClass('d-none')) {
                $('#btnPostAllSelected').removeClass('d-none')
            }
        } else {
            if (!$('#btnPostAllSelected').hasClass('d-none')) {
                $('#btnPostAllSelected').addClass('d-none')
            }
        }
    })
    $('#btnPostAllSelected').click(function() {
        submiData(postData);
    });
    $('.btn-update-rgr-row').click(function() {
        var checkbox = $(this).closest('tr').find('.check-body');
        if (checkbox) {
            var target = $(checkbox[0]);
            submiData([{
                oldId: target.data('oldId'),
                from_date: target.data('from_date'),
                till_date: target.data('till_date'),
                reason_for_change: target.data('reason_for_change')
            }])
        }
    });

    function submiData(data) {
        if (data.length > 0 && confirm("This action will withdraw " + data.length + " seleted entrie(s) and create new entries with corresponding end date. Continue?")) {

            $.ajax({
                type: "post",
                url: "{{route('editMultipleRGR')}}",
                data: {
                    data: data,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    //window.location.reload()
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    }
</script>