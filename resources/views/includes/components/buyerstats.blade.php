<table class="zebra table-space">
	<thead>
		<th></th>
		<th>1 mounth</th>
		<th>3 mounth</th>
		<th>1 year</th>
		<th>all time</th>
	</thead>
	<tbody>
		<tr>
			<td style="text-align: left">total transactions</td>
			<td>{{ $user->totalOrdersCompleted(1) }}</td>
			<td>{{ $user->totalOrdersCompleted(3) }}</td>
			<td>{{ $user->totalOrdersCompleted(12) }}</td>
			<td>{{ $user->totalOrdersCompleted() }}</td>
		</tr>
		<tr>
			<td style="text-align: left">total spent(USD)</td>
			<td>&#8499;{{ $user->totalSpent($user, 1) }}</td>
			<td>&#8499;{{ $user->totalSpent($user, 3) }}</td>
			<td>&#8499;{{ $user->totalSpent($user, 12) }}</td>
			<td>&#8499;{{ $user->totalSpent($user) }}</td>
		</tr>
		<tr>
			<td style="text-align: left">dispute rate</td>
			<td>{{ $user->rateDispute(1) }}</td>
			<td>{{ $user->rateDispute(3) }}</td>
			<td>{{ $user->rateDispute(12) }}</td>
			<td>{{ $user->rateDispute() }}</td>
		</tr>
	</tbody>
</table>
