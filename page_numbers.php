<?php
// Page numbers
function pageNmbCheck($totalPages, $redirectDest, $sessionName) {
	if(isset($_POST['navBtn1'])) {
		$_SESSION['pageNmb'.$sessionName] = $_POST['navBtn1'];
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['navBtn2'])) {
		$_SESSION['pageNmb'.$sessionName] = $_POST['navBtn2'];
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['navBtn3'])) {
		$_SESSION['pageNmb'.$sessionName] = $_POST['navBtn3'];
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['next'])) {
		$_SESSION['pageNmb'.$sessionName] = $_SESSION['pageNmb'.$sessionName] + 1;
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['prev'])) {
		$_SESSION['pageNmb'.$sessionName] = $_SESSION['pageNmb'.$sessionName] - 1;
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['end'])) {
		$_SESSION['pageNmb'.$sessionName] = $totalPages;
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['beg'])) {
		$_SESSION['pageNmb'.$sessionName] = 1;
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
	elseif(isset($_POST['changePageNmb'])) {
		$_SESSION['pageNmb'.$sessionName] = $_POST['changePageNmb'];
		echo '<script>window.location.href = "'.$redirectDest.'";</script>';
	}
}

function pageNmbBtn($totalPages, $redirectDest, $sessionName) {
	if($totalPages > 1) {
		?>
		<style>
		#userPageNav input{
			outline: none;
		}

		.pageNavBtn{
			color: #DEDEDE;
			background-color: #032848;
			width: 50px;
			height: 30px;
			font-size: 20px;
			border: 2px solid #172c3e;
			transition: all 0.3s;
		}

		.pageNavBtnNmb:hover, .pageNavBtn:hover{
			color: #A9D1D9;
			background-color: #5b583d;
			cursor: pointer;
			box-shadow: 0px 5px 20px #4D460F inset;
		}

		.pageNavBtnNmb{
			color: #DEDEDE;
			background-color: #032848;
			width: 30px;
			height: 30px;
			font-size: 20px;
			border: 2px solid #172c3e;
			transition: all 0.3s;
		}
		</style>
		<?php
		echo '<form action="'.$redirectDest.'" method="post" autocomplete="off" id="userPageNav">';
		if($_SESSION['pageNmb'.$sessionName] > 1) {
			?>
			<input type="submit" name="prev" value="&lt;" class="pageNavBtn">
			<?php
		}
		if($_SESSION['pageNmb'.$sessionName] < $totalPages) {
			?>
			<input type="submit" name="next" value="&gt;" class="pageNavBtn">
			<?php
		}
		if($totalPages != 1) {
			?>
			<input type="submit" name="navBtn1" value="<?php if($_SESSION['pageNmb'.$sessionName] > 1 AND $_SESSION['pageNmb'.$sessionName] < $totalPages){echo $_SESSION['pageNmb'.$sessionName] - 1;}elseif($_SESSION['pageNmb'.$sessionName] == $totalPages AND $totalPages != 2){echo $_SESSION['pageNmb'.$sessionName] - 2;}else{echo 1;} ?>" id="navBtn1" class="pageNavBtnNmb" <?php if($_SESSION['pageNmb'.$sessionName] == 1){echo 'style="color: #032848;"';} ?>>
			<?php
		}
		if($totalPages > 1) {
			?>
			<input type="submit" name="navBtn2" value="<?php if($_SESSION['pageNmb'.$sessionName] > 1 AND $_SESSION['pageNmb'.$sessionName] < $totalPages){echo $_SESSION['pageNmb'.$sessionName];}elseif($_SESSION['pageNmb'.$sessionName] == $totalPages AND $totalPages != 2){echo $_SESSION['pageNmb'.$sessionName] - 1;}else{echo 2;} ?>" id="navBtn2" class="pageNavBtnNmb" <?php if(($_SESSION['pageNmb'.$sessionName] != 1 && $_SESSION['pageNmb'.$sessionName] != $totalPages) || ($_SESSION['pageNmb'.$sessionName] == 2 && $totalPages == 2)){echo 'style="color: #032848;"';} ?>>
			<?php
			if($totalPages > 2) {
			?>
				<input type="submit" name="navBtn3" value="<?php if($_SESSION['pageNmb'.$sessionName] == 1){echo 3;}elseif($_SESSION['pageNmb'.$sessionName] == $totalPages){echo $totalPages;}else{echo $_SESSION['pageNmb'.$sessionName] + 1;} ?>" id="navBtn3" class="pageNavBtnNmb" <?php if($_SESSION['pageNmb'.$sessionName] == $totalPages && $totalPages != 2){echo 'style="color: #032848;"';} ?>>
				<?php
			}
			if($_SESSION['pageNmb'.$sessionName] > 1) {
				?>
				<input type="submit" name="beg" value="&Lt;" class="pageNavBtn">
				<?php
			}
			if($_SESSION['pageNmb'.$sessionName] < $totalPages) {
				?>
				<input type="submit" name="end" value="&Gt;" class="pageNavBtn">
				<?php
			}
		}
		if($_SESSION['pageNmb'.$sessionName] == 1) {
			if($totalPages != 1) {
				echo '<style>#navBtn1{background-color: #A7A8B0;}</style>';
			}
		}
		else {
			if($_SESSION['pageNmb'.$sessionName] == $totalPages) {
				if($totalPages > 2) {
					echo '<style>#navBtn3{background-color: #A7A8B0;}</style>';
				}
				else {
					echo '<style>#navBtn2{background-color: #A7A8B0;}</style>';
				}
			}
			else {
				echo '<style>#navBtn2{background-color: #A7A8B0;}</style>';
			}
		}
		echo '</form>';
	}
}
?>