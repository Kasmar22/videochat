<?php

	require 'config.php';
	
	header('Content-type: text/plain');
	echo('<?xml version="1.0" encoding="utf-8"?><result>');
	$identity  = $_GET['identity'];
	$sex = $_GET['sex'];
	$newSex = $_GET['newSex'];
	$filter = $_GET['filter'];
	$count = $_GET['count'];
	$deleteId = $_GET['deleteId'];
	$sId = $_GET['sId'];
	$dId = $_GET['dId'];
	$msg = $_GET['msg'];
	
	if (($identity != "") && ($sex != "")) {
		if (strlen($identity) != 64) {
			echo('<update>false</update>');
		}
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
		mysql_select_db($dbname);
		$sex = mysql_real_escape_string($sex);
		$identity = mysql_real_escape_string($identity);
		$result = mysql_query("REPLACE INTO sessions SET id = '".$identity."', sex = '".$sex."';");
		if ($result) {
			echo('<update>true</update>');
		} else
		{
			echo('<update>false</update>');
		}
		mysql_close($conn);

	} else {
		if (($identity != "") && ($newSex != "")) {
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
			mysql_select_db($dbname);
			$identity = mysql_real_escape_string($identity);
			$newSex = mysql_real_escape_string($newSex);
			$result = mysql_query("REPLACE INTO sessions SET id = '".$identity."', sex = '".$newSex."';");
			if ($result) {
				echo('<updateSex>true</updateSex>');
			} else
			{
				echo('<updateSex>false</updateSex>');
			}
			mysql_close($conn);
		} else {
			if ($filter != "") {
				if ($filter == "b") {
					$q = "SELECT * FROM sessions WHERE created_at > DATE_SUB(NOW(),INTERVAL 30 SECOND) ORDER BY RAND() LIMIT 1";
				} elseif ($filter == "m") {
					$q = "SELECT * FROM sessions WHERE sex='m' AND created_at > DATE_SUB(NOW(),INTERVAL 30 SECOND) ORDER BY RAND() LIMIT 1";
				} elseif ($filter == "f") {
					$q = "SELECT * FROM sessions WHERE sex='f' AND created_at > DATE_SUB(NOW(),INTERVAL 30 SECOND) ORDER BY RAND() LIMIT 1";
				} else {
					$q = "SELECT * FROM sessions WHERE created_at > DATE_SUB(NOW(),INTERVAL 30 SECOND) ORDER BY RAND() LIMIT 1";
				}
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
				mysql_select_db($dbname);
				$filter = mysql_real_escape_string($filter);
				$result = mysql_query($q); 
				if ($result) {
					while ($row = mysql_fetch_array($result)) { 
						echo('<identity>'.trim($row['id']).'</identity>');
					}
				} else
				{
					echo('<identity>false</identity>');
				}
			mysql_close($conn);
			} else {
				if ($count != "") {
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
					mysql_select_db($dbname);
					$result = mysql_query("select count(*) as num FROM sessions WHERE created_at > DATE_SUB(NOW(),INTERVAL 30 SECOND)");
					$result = mysql_fetch_assoc( $result );
					$total = $result['num'];
					echo('<online>'.$total.'</online>');
					mysql_close($conn);
				} else {
					if ($deleteId != "") {
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
						mysql_select_db($dbname);
						$deletedId = mysql_real_escape_string($deletedId);
						$result = mysql_query("DELETE FROM sessions WHERE id='".$deleteId."'");
						echo('<deleted>'.mysql_affected_rows().'</deleted>');
						mysql_close($conn);	
					} else {
						if ($sId != "") {
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
							mysql_select_db($dbname);
							$sId = mysql_real_escape_string($sId);
							$result = mysql_query("INSERT INTO talks (sId, dId, msg) VALUES ('".$sId."','".$dId."','".urldecode($msg)."')");
							echo('<talks>true</talks>');
							mysql_close($conn);
						}
					}
				}
 			}
		}
	}
	echo('</result>');
?>