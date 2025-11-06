<table border="1">
    <thead>
        <tr>
            <th>Vchno</th>
            <th>Date</th>
            <th>Location</th>
            <th>Ledger Name</th>
            <th>LedgerCode</th>
            <th>Ledger Type</th>
            <th>Item</th>
            <th>Itemtype</th>
            <th>Nettwt</th>
            <th>Purity</th>
            <th>Purewt</th>

        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
            <tr>
                <td>{{ $row->vou_no }}</td>
                <td>{{ $row->metal_receive_entries_date }}</td>
                <td>{{ $row->location_name }}</td>
                <td>{{ $row->ledger_name }}</td>
                <td>{{ $row->ledger_code }}</td>
                <td>{{ $row->ledger_type }}</td>
                <td>{{ $row->metal_category }}</td>
                <td>{{ $row->metal_name }}</td>
                <td>{{ $row->net_wt }}</td>
                <td>{{ $row->purity }}</td>
                <td>{{ $row->pure_wt }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
