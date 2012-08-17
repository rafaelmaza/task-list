<?php
$this->pageTitle=Yii::app()->name . ' - REST API Reference';
$this->breadcrumbs=array(
	'REST API Reference',
);
?>
<h1>REST API Quick Reference</h1>

<p>This sample application provides a REST API for manipulating its data. It can be accessed in <strong><?php echo $this->createAbsoluteUrl('/api'); ?>/</strong>.</p>

<h2>Authentication</h2>

<table>
	<caption><strong>POST auth</strong> - Authenticate a user. <strong>Returns a token that must be provided in every request, as "?token=[token]".</strong></caption>
	<thead>
		<tr>
			<th>Parameter</th>
			<th>Type</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>username</td>
			<td>String</td>
			<td>User name. Maximum of 14 alphanumeric characters.</td>
		</tr>
		<tr>
			<td>password</td>
			<td>String</td>
			<td>User password. Minimum of 6 characters.</td>
		</tr>
	</tbody>
</table>
<h2>User management</h2>

<table>
	<caption><strong>POST user</strong> - Create a new user.</caption>
	<thead>
		<tr>
			<th>Parameter</th>
			<th>Type</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>username</td>
			<td>String</td>
			<td>User name. Maximum of 14 alphanumeric characters.</td>
		</tr>
		<tr>
			<td>password</td>
			<td>String</td>
			<td>User password. Minimum of 6 characters.</td>
		</tr>
	</tbody>
</table>

<table>
	<caption><strong>PUT user/:id</strong> - Update user information (just for example purpose).</caption>
	<thead>
		<tr>
			<th>Parameter</th>
			<th>Type</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>username</td>
			<td>String</td>
			<td>User name. Maximum of 14 alphanumeric characters.</td>
		</tr>
		<tr>
			<td>password</td>
			<td>String</td>
			<td>User password. Minimum of 6 characters.</td>
		</tr>
	</tbody>
</table>

<h2>Task management</h2>

<table>
	<caption><strong>GET task</strong> - Get all tasks information.</caption>
</table>

<table>
	<caption><strong>GET task/:id</strong> - Get specific task information.</caption>
</table>

<table>
	<caption><strong>POST task</strong> - Create a new task.</caption>
	<thead>
		<tr>
			<th>Parameter</th>
			<th>Type</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>description</td>
			<td>String</td>
			<td>Task description.</td>
		</tr>
		<tr>
			<td>priority</td>
			<td>Integer</td>
			<td>Task priority, optional. If not provided, will default to Low. Allowed values: 1-Low, 2-Medium, 3-High</td>
		</tr>
		<tr>
			<td>due_date</td>
			<td>String</td>
			<td>Task due date, optional. If not provided, will default to D+1. Format: YYYY-MM-DD</td>
		</tr>
		<tr>
			<td>completed</td>
			<td>Integer</td>
			<td>Task completed flag, optional. If not provided, will default to Open. Allowed values: 0-Open, 1-Completed</td>
		</tr>
	</tbody>
</table>

<table>
	<caption><strong>PUT task/:id</strong> - Update an existing task.</caption>
	<thead>
		<tr>
			<th>Parameter</th>
			<th>Type</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>description</td>
			<td>String</td>
			<td>Task description.</td>
		</tr>
		<tr>
			<td>priority</td>
			<td>Integer</td>
			<td>Task priority, optional. If not provided, will default to Low. Allowed values: 1-Low, 2-Medium, 3-High</td>
		</tr>
		<tr>
			<td>due_date</td>
			<td>String</td>
			<td>Task due date, optional. If not provided, will default to D+1. Format: YYYY-MM-DD</td>
		</tr>
		<tr>
			<td>completed</td>
			<td>Integer</td>
			<td>Task completed flag, optional. If not provided, will default to Open. Allowed values: 0-Open, 1-Completed</td>
		</tr>
	</tbody>
</table>

<table>
	<caption><strong>DELETE task/:id</strong> - Remove task.</caption>
</table>