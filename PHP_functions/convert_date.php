<?php
// Convert date to a more readable string
function convertDate($convertDate, bool $timePeriod = true) {
	$DT1 = new DateTime($convertDate);
	$DT2 = new DateTime(date('Y-m-d H:i:s'));
	$diff = $DT1->diff($DT2);
	if($diff->y == 0) {
		if($diff->m == 0) {
			if($diff->d == 0) {
				if($diff->h == 0) {
					if($diff->i == 0) {
						echo ($timePeriod
						? 'just now'
						: 'less then one minute');
					} elseif($diff->i == 1) {
						// if($timePeriod) {
						// 	echo 'a minute ago';
						// } else {
						// 	echo '1 minute';
						// }
						echo ($timePeriod
						? 'a minute ago'
						: '1 minute');
					} else {
						// if($timePeriod) {
						// 	echo $diff->i.' minutes ago';
						// } else {
						// 	echo $diff->i.' minutes';
						// }
						echo $diff->i.($timePeriod
						? ' minutes ago'
						: ' minutes');
					}
				} else {
					if($diff->h == 1) {
						// if($timePeriod) {
						// 	echo 'an hour ago';
						// } else {
						// 	echo 'one hour';
						// }
						echo ($timePeriod
						? 'an hour ago'
						: 'one hour');
					} else {
						// if($timePeriod) {
						// 	echo $diff->h.' hours ago';
						// } else {
						// 	echo $diff->h.' hours';
						// }
						echo $diff->h.($timePeriod
						? ' hours ago'
						: ' hours');
					}
				}
			} else {
				if($diff->d == 1) {
					if($timePeriod) {
						echo 'a day ago at '.substr($convertDate, -8, 5);
					} else {
						echo 'one day and '.$diff->h.' hour';
						if($diff->h != 1) {
							echo 's';
						}
					}
				} else {
					if($timePeriod) {
						echo $diff->d.' days ago at '.substr($convertDate, -8, 5);
					} else {
						echo $diff->d.' days and '.$diff->h.' hour';
						if($diff->h != 1) {
							echo 's';
						}
					}
				}
			}
		} else {
			if($diff->m == 1) {
				if($timePeriod) {
					echo 'a month ago';
				} else {
					echo 'one month and '.$diff->d.' day';
					if($diff->d != 1) {
						echo 's';
					}
				}
			} else {
				if($timePeriod) {
					echo $diff->m.' months ago';
				} else {
					echo $diff->m.' months and '.$diff->d.' day';
					if($diff->d != 1) {
						echo 's';
					}
				}
			}
		}
	} else {
		if($diff->y == 1) {
			if($timePeriod) {
				echo 'a year ago';
			} else {
				echo 'one year and '.$diff->m.' month';
				if($diff->m != 1) {
					echo 's';
				}
			}
		} else {
			if($timePeriod) {
				echo $diff->y.' years ago';
			} else {
				echo $diff->y.' years and '.$diff->m.' month';
				if($diff->m != 1) {
					echo 's';
				}
			}
		}
	}
}
?>
