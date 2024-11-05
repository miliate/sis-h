<?php
	include_once("header.php");	///loads the html HEAD section (JS,CSS)
	echo '<table border=0 width=100% cellspacing=0 cellpading=0>';
		echo '<tr>';
			echo '<td  colspan=2 id="top_menu">';
					echo '<div id="header">';
						echo Modules::run('menu'); //runs the available menu option to that usergroup
					echo '</div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan=2	>';
				echo '<div class="mdshead">';
				echo 'Patient registration / New';
				echo Modules::run('hhims/get_user_info'); //gets the user information from SESSION
				echo '</div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td  id="left_menu" width=200px valign="top">';
			echo '</td>';
			echo '<td  id="content"  valign="top">';
				echo Modules::run('form/create','patient');
			echo '</td>';
		echo '</tr>';
		
	echo '<table>';
?>
