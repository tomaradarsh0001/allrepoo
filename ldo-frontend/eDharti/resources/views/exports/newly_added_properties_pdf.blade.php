<table>
    <thead>
        <tr>
            <th>Serial No.</th>
            <th>Application No.</th>
            <th>Name</th>
            <th>Property Details</th>
            <th>Status</th>
            <th>Documents</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($properties as $property)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $property->applicant_number }}</td>
                <td>{{ $property->name }}</td>
                <td>{{ ucfirst($property->block) . '/' . ucfirst($property->plot) . '/' . ucfirst($property->old_colony_name) }}</td>
                <td>{{ $property->item_name }}</td>
                <td><!-- Documents logic here --></td>
                <td>{{ $property->remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
