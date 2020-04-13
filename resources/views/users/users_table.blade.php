<table id="listtable" class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Active</th>
			<th>Roles</th>
            <th class="no-sort" width="10%">
                @if (Gate::allows('us_cr'))
                <a href="{{ url("/users/create") }}" role="button" class="add-icon" title="Add"></a>	
                @endif
            </th>
        </tr>		
    </thead>
    <tbody>			
        @foreach ($users as $user)
        <tr>
            <td> {{ $user->name }} </td>
            <td> {{ $user->email }} </td>
            <td> @if ($user->active == 1) Yes @else No @endif </td>
			<td>
				@foreach ($user->roles as $role)
				{{ $role->rolename}}<br>
				@endforeach
			</td>
            <td>
                @if (Gate::allows('us_vw', $user->id))
                <a href="{{ url("/users/view/" . $user->id) }}" class="view-icon" role="button" title="View"></a>							
                @endif
            </td>			
        </tr>	
        @endforeach			
    </tbody>
</table>