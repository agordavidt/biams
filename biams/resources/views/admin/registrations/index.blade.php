<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Practice</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registrations as $registration)
            <tr>
                <td>{{ $registration->user->name }}</td>
                <td>{{ $registration->practice->practice_name }}</td>
                <td>{{ $registration->submission_date }}</td>
                <td>{{ $registration->status }}</td>
                <td>
                    <form action="{{ route('admin.registrations.update', $registration->registration_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status">
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>