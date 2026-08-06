<table id="datatable-buttons" class="table table-bordered table-responsive">
	<thead>
		<tr>
		    <th>Name</th>
		    <th>Email</th>
		    <th>IP Address</th>
		    <th>User Agent</th>
		    <th>Last Activity</th>
		</tr>
	</thead>
	<tbody>
	    @foreach($activeUsers as $user)
	    <tr>
	        <td>{{ $user->name }}</td>
	        <td>{{ $user->email }}</td>
	        <td>{{ $user->ip_address }}</td>
	        <td>{{ $user->user_agent }}</td>
	        <td>{{ $user->last_activity }}</td>
	    </tr>
	    @endforeach
	</tbody>
</table>

<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});
</script>