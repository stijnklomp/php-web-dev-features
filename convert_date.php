<?php
function convertDate($convertDate)
{
	$DT1 = new DateTime($convertDate);
	$DT2 = new DateTime(date('Y-m-d H:i:s'));
	$diff = $DT1->diff($DT2);
	if($diff->y == 0)
	{
		if($diff->m == 0)
		{
			if($diff->d == 0)
			{
				if($diff->h == 0)
				{
					if($diff->i == 0)
					{
						echo 'just now';
					}
					elseif($diff->i == 1)
					{
						echo 'a minute ago';
					}
					else
					{
						echo $diff->i.' minutes ago';
					}
				}
				else
				{
					if($diff->h == 1)
					{
						echo 'an hour ago';
					}
					else
					{
						echo $diff->h.' hours ago';
					}
				}
			}
			else
			{
				if($diff->d == 1)
				{
					echo 'a day ago at '.substr($convertDate, -8, 5);
				}
				else
				{
					echo $diff->d.' days ago at '.substr($convertDate, -8, 5);
				}
			}
		}
		else
		{
			if($diff->m == 1)
			{
				echo 'a month ago';
			}
			else
			{
				echo $diff->m.' months ago';
			}
		}
	}
	else
	{
		if($diff->y == 1)
		{
			echo 'a year ago';
		}
		else
		{
			echo $diff->y.' years ago';
		}
	}
}
?>