<?php
$user = json_decode(file_get_contents("php://input"));
?>
<form action="" method="POST">
	<input type="hidden" name="username" value="<?= $user->username; ?>">
	<input type="hidden" name="password" value="<?= $user->password; ?>">
	<input type="submit" name="logout" value="Logout">
</form>
<?php
echo '<table>';
foreach($user as $value => $child) {
	echo '<tr><td>'.$value.'</td><td>| '.$child.'</td></tr>';
}
echo '</table>';
?>