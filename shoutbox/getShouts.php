<?php
if(empty($checkIncludedFile))
{
	session_start();
	include_once 'autoloader.php';
	$db = new Database();
	$user = new User();
	if($user->loginCheck())
	{
		$user->getUserByID($_SESSION['user_ID']);
	}
}
$sth = $db->selectDatabase('shoutbox', '', '', ' ORDER BY createdDate DESC LIMIT 0, 50');
$count = 0;
while($row = $sth->fetch())
{
	?>
	<div class="shoutRow">
		<div class="shoutRowBackground"></div>
		<?php
		$sth2 = $db->selectDatabase('users', 'user_ID', $row['user_ID'], '');
		$row2 = $sth2->fetch();
		echo '<div class="shoutboxRowDate">['.$row['createdDate'].']</div>&nbsp;';
		echo '<a href="profile?userID='.$row['user_ID'].'" class="shoutboxRowName">'.$row2['Username'].'</a>:&nbsp;';
		if($row['Status'] == 2)
		{
			echo '<div class="shoutboxRowDeleted">This message has been deleted on ['.$row['deletedDate'].']</div>';
		}
		else
		{
			// Delete button
			if($user->loginCheck() && ($user->id == $row['user_ID'] || $user->permission > 7))
			{
				?>
				<div class="deleteShoutRow" onclick="deleteShout('<?= $row['post_ID']; ?>');" title="Delete message"><b>&minus;</b></div>
				<?php
			}
			// Edit button
			if($user->loginCheck() && ($user->id == $row['user_ID'] || $user->permission > 8))
			{
				?>
				<div class="editShoutRow" onclick="editShout('<?= $row['post_ID']; ?>');" title="Edit message">&andd;</div>
				<?php
			}
			// Edited icon
			if($row['Edited'] == 2)
			{
				?>
				<div class="editedIcon" title="This message has been edited">&oslash;</div>
				<?php
			}
			echo '<div class="shoutboxRowText">'.$row['Text'].'</div>';
		}
		?>
	</div>
	<?php
	$count++;
}
if($count == 0)
{
	echo '<div id="shoutboxRowNone">Be the first to say something.';
	if(!$user->loginCheck())
	{
		echo '<br/><a href="login" style="font: 15px/15px Orbitron;text-decoration: none;">Log in to say something</a>';
	}
	echo '</div>';
}
?>