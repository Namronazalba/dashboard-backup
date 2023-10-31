<h3>Beakdown</h3>
<table class="custom-table">
    <thead>
        <tr>
            <th>PLATE NUMBER</th>
            <th>LOCATION</th>
            <th>LAST CHECKED</th>
            <th>ACTION TAKEN</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($breakdownReports as $report)
            <tr>
                <td>{{ $report->vplatenum }}</td>
                <td>{{ $report->complete_address }}</td>
                <td>{{ $report->date_work_end }}</td>
                <td>{{ $report->action_taken }}</td>
                <td><button>Details</button></td>
            </tr>
        @endforeach
    </tbody>
</table>

