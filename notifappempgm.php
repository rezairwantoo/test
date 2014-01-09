<?php
	mysql_connect("localhost", "root", "") or die(mysql_error()); 
	mysql_select_db("sisindo2") or die(mysql_error()); 
	$datas = mysql_query("SELECT * FROM period where period_id >= 60")
	or die(mysql_error()); 
	$arremployee = array();
	$arrperiod = array();
	$message = "";
	//$rowperiod = mysql_fetch_array($data, MYSQL_ASSOC);
	while($rowperiod = mysql_fetch_array($datas, MYSQL_ASSOC))
	{
		array_push($arrperiod, $rowperiod);
	}
	//print_r($arrperiod[0]['to_date']);
	foreach($arrperiod as $a=>$v){
		//echo $a;
		$todays_date = date("d-m-Y"); 
		$tod = date ("Y-m-d");
		$today = strtotime($todays_date);
		$divdate = $today - $arrperiod[$a]['to_date'];
		$perioddate = date("d-m-Y", strtotime($arrperiod[$a]['to_date']));
		$periodid = $arrperiod[$a]['period_id'];
		$periodname = $arrperiod[$a]['period_name'];
		$selisih=(strtotime($tod)-strtotime($arrperiod[$a]['to_date']))/(60*60*24);
		if($today > $perioddate && $selisih >= 3){
			$data = mysql_query("SELECT * FROM position where position_id = '13' or position_id = '14' or position_id = '15' or position_id = '16'") or die(mysql_error()); 
			$arrspv = array();
			$messages = "";
			$arremployee = array();
			while($row = mysql_fetch_array($data, MYSQL_ASSOC))
			{
				array_push($arrspv, $row);
			}
			foreach($arrspv as $b=>$v){
				$empid = $arrspv[$b]['position_id'];
				//echo $periodid;
				//echo $empid;
				//echo "select t.*, e.employee_name, p.period_name from employee e, timesheet t, period p where t.employee_id in (SELECT employee_id FROM employee where supervisor = '$empid') and t.employee_id = e.employee_id and t.period_id = $periodid and p.period_id = $periodid and t.status_timesheet = 'Active'";
				$dataemployee = mysql_query("select t.*, e.employee_name, p.period_name from employee e, timesheet t, period p where t.employee_id in (SELECT employee_id FROM employee where supervisor = '$empid') and t.employee_id = e.employee_id and t.period_id = $periodid and p.period_id = $periodid and t.status_timesheet = 'Waiting'"); 
				$num_results = mysql_num_rows($dataemployee);
				if ($num_results > 0){
					while($rowdataemployee = mysql_fetch_array($dataemployee, MYSQL_ASSOC))
					{
						//echo $rowdataemployee;
						array_push($arremployee, $rowdataemployee);
					}
					$message .="Employee pada divisi anda ada yang belum dilakukan pengecekan/persetujuan timesheet pada period ".$periodname." adalah sebagai berikut: <br />";			
					foreach($arremployee as $k=>$v){
						$message .= $arremployee[$k]['employee_name']." <br />";
						
					}
					$arremployee = array();
					$spvid = $arrspv[$b]['position_head'];
					$dataspv = mysql_query("SELECT * FROM employee where employee_id = '$spvid'")
					or die(mysql_error()); 
					while($rowdataspv = mysql_fetch_array($dataspv, MYSQL_ASSOC))
					{
						$gmspv = $rowdataspv['supervisor'];
						$gm = mysql_query("SELECT * FROM position where position_id = '$gmspv'") or die(mysql_error()); 
						while($rowgm = mysql_fetch_array($gm, MYSQL_ASSOC))
						{
							$gmid = $rowgm['position_head'];
							$datagm = mysql_query("SELECT * FROM employee where employee_id = '$gmid'") or die(mysql_error()); 
							while($rowdatagm = mysql_fetch_array($datagm, MYSQL_ASSOC)){
								$message .= "supervisor dari employee tersebut adalah ".$rowdataspv['employee_name']."<br />";
								$message .= "Mohon untuk menindak lanjuti terhadap supervisor yang terkait dengan employee untuk segera melakukan pengecekan/persetujuan timesheet pada periode tersebut diatas <br />";
								echo $message;
								$message = "";
								/*$siteOwnersEmail = 'reza.irwantoo@gmail.com, reza.kaseptea@gmail.com, andi.ridwan@gmail.com' ;

								$name = "pmo@sisindokom.com";
								$contact_message = "pmo@sisindokom.com";
								$email = "pmo@sisindokom.com";
								$subject = "Pengecekan & persetujuan timesheet report";

							   // Check Name
								if (strlen($name) < 2) {
									$error['name'] = "Please enter your name.";
								}
								// Check Email
								if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
									$error['email'] = "Please enter a valid email address.";
								}
								// Check Message
								if (strlen($contact_message) < 15) {
									$error['message'] = "Please enter your message. It should have at least 15 characters.";
								}
							   // Subject
								if ($subject == '') { $subject = "Contact Form Submission"; }

							   // Set From: header
								$from =  $name . " <pmo@sisindokom.com>";

							   // Email Headers
								$headers = "From: " . $from . "\r\n";
								$headers .= "Reply-To: ". $email . "\r\n";
								$headers .= "MIME-Version: 1.0\r\n";
								$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

								ini_set("sendmail_from", "pmo@sisindokom.com"); // for windows server
								$mail = mail($siteOwnersEmail, $subject, $message, $headers);
								  //echo $mail;
								if (!$error) {

								ini_set("sendmail_from", "pmo@sisindokom.com"); // for windows server
								$mail = mail($siteOwnersEmail, $subject, $message, $headers);
								
									if ($mail) { echo "OK"; }
								  else { echo "Something went wrong. Please try again."; }
									
								} # end if - no validation error

								else {

									$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
									$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
									$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
									
									echo $response;

								}
								*/
							}
						}
						
					}
				}
			}
		}
		
		
		
	}
	
?>