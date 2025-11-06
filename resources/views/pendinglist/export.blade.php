<table border="1">
    <thead>
        <tr>
            <th>JO NO.</th>
            <th>JO DATE</th>
            <th>ITEM CODE</th>
            <th>SIZE</th>
            <th>STD. WT.</th>
            <th>BAL. QTY.</th>
            <th>KID</th>
            <th>REQ DATE K</th>
            <th>REQ DATE C</th>

        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
            <tr>
                <td>{{ $row->job_no }}</td>
                <td>{{ $row->jo_date }}</td>
                <td>{{ $row->item_code }}</td>
                <td>{{ $row->size }}</td>
                <td>{{ $row->st_weight }}</td>
                <td>{{ $row->bal_qty }}</td>
                <td>{{ $row->kid }}</td>
                <td>{{ $row->REQ_DATE_K }}</td>
                <td>{{ $row->REQ_DATE_C }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
