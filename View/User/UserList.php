<div class="user-list">
<h3> UserList </h3>

<table border=1>

<tr>
	<th> user id 	</th>
	<th> user name </th>
	<th> mail adress </th>
	<th> authority </th>
</tr>

<?php foreach ($v->user_list as $key => $user_data): ?>
 <tr>
 	<td> <?= $user_data['user_id'] ?> </td>
 	<td> <?= $user_data['user_name'] ?> </td>
 	<td> <?= $user_data['mail_address'] ?> </td>
 	<td> <?= $user_data['authority'] ?> </td>
 </tr>
<?php endforeach ?> 
</table>
</div>