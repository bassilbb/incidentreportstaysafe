<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}
if ((CurrentUserLevel() == 1)) {
		 $_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyNewCount']      = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyAssignCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4)AND `staff_id` = '".$_SESSION['Staff_ID']."' ");
	     $_SESSION['MyIssueresolvedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
	     $_SESSION['MyMaintenancetickketCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
	     $_SESSION['MyTicketreviewedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (3) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
	     $_SESSION['MyDispenserCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `dispenser` WHERE `status` in (1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
}
/*if ((CurrentUserLevel() == 1) || (CurrentUserLevel() == 2)|| (CurrentUserLevel() == 3)) {
		 $_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyNewCount']      = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
}*/
if (CurrentUserLevel() == -1) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5)");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0)");
	$_SESSION['MyAssignCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4) ");
	$_SESSION['MyIssueresolvedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6)");
	$_SESSION['MyMaintenancetickketCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (2)");
	$_SESSION['MyTicketreviewedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (3)");
	$_SESSION['MyDispenserCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `dispenser` WHERE `status` in (1)");
}
/*if (CurrentUserLevel() == 1) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
}*/
if (CurrentUserLevel() == 2) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyAssignCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4) AND `assign` = '".$_SESSION['Staff_ID']."' ");
	$_SESSION['MyIssueresolvedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6)AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyMaintenancetickketCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
	$_SESSION['MyTicketreviewedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (3) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
	$_SESSION['MyDispenserCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `dispenser` WHERE `status` in (1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
}
if (CurrentUserLevel() == 3) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyAssignCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4) ");
	$_SESSION['MyIssueresolvedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6) ");
	$_SESSION['MyMaintenancetickketCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (2) ");
	$_SESSION['MyTicketreviewedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (3) ");
	$_SESSION['MyDispenserCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `dispenser` WHERE `status` in (1) ");
}
if (CurrentUserLevel() == 4) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyAssignCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyIssueresolvedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyMaintenancetickketCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (2)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyTicketreviewedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (3)AND `branch` = '".$_SESSION['Branch']."' ");
	$_SESSION['MyDispenserCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `dispenser` WHERE `status` in (1)AND `branch` = '".$_SESSION['Branch']."' ");
}
if ((CurrentUserLevel() == 5 || CurrentUserLevel() == 6)) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyAssignCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyIssueresolvedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (12) ");
	$_SESSION['MyMaintenancetickketCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (12) ");
	$_SESSION['MyTicketreviewedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `maintenance` WHERE `status` in (12) ");
	$_SESSION['MyDispenserCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `dispenser` WHERE `status` in (10) ");
}

function generateSIDKey(){
$randStrs =	mt_rand(001,999);
return "SID".$randStrs;
}

function generateINCKey(){
$randStrs =	mt_rand(10000000,99999999);
return "INC".$randStrs;
}

function generateREFKey(){
$randStrs =	mt_rand(0000001,9999999);
return "REF".$randStrs;
}

function generateREFNKey(){
$randStrs =	mt_rand(0000001,9999999);
return "REFN".$randStrs;
}

function generateINVKey(){
$randStrs =	mt_rand(0000001,9999999);
return "INV".$randStrs;
}

function generateINSKey(){
$randStrs =	mt_rand(0000001,9999999);
return "INS".$randStrs;
}

function generateSYSKey(){
$randStrs =	mt_rand(0000001,9999999);
return "SYS".$randStrs;
}

function generateLAPKey(){
$randStrs =	mt_rand(000000011,999999999);
return "LAP".$randStrs;
}

//
//$conn = mysqli_connect("localhost", "root","","incident_report");

?>
