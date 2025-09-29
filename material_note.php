<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$material_note_php = NULL; // Initialize page object first

class cmaterial_note_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'material_note.php';

	// Page object name
	var $PageObjName = 'material_note_php';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'material_note.php');

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect();

		// User table object (users)
		if (!isset($UserTable)) {
			$UserTable = new cusers();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		if (@$_GET["export"] <> "")
			$gsExport = $_GET["export"]; // Get export parameter, used in header

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		// Close connection

		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "material_note_php", $url, "", "material_note_php", TRUE);
		$this->Heading = $Language->TablePhrase("material_note_php", "TblCaption"); 
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($material_note_php)) $material_note_php = new cmaterial_note_php();

// Page init
$material_note_php->Page_Init();

// Page main
$material_note_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php //include_once "header.php" ?>
<!-- %%Custom page content begin%% -->
 
<?php 

	//echo CurrentUserName()  . "</br>";
	$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
	//echo $url . "</br>";
	//exit;
		$parts = parse_url($url);
		parse_str($parts['query'], $query);		
		//echo $query['name'];
	//exit;
	$code = $query['code'];

	$date = "";
	$name = "";
	$outward_location = "";
	$delivery_point = "";
	$department = "";
	$designation = "";
	$driver_name = "";
	$vehicle_no = "";
	$authorizer_name = "";
	$date_authorized = "";
	$rep_name = "";
	$rep_date = "";








	// $sSqlWrk = "SELECT * FROM `requisition_module` WHERE `code` = '". $code ."'";
	//$sSqlWrk .= " WHERE `code` = '". $code ."'";



	// $sSqlWrk = "SELECT 
	// 		r.outward_location as outward_location,
	// 		r.delivery_point as delivery_point,
	// 		r.date as date,
	// 		u.firstname as name,
	// 		b.branch_name as organization,
	// 		d.department_name as depertment_name,
	// 		g.description as designation_name,	
	// 		r.item_description as item_description,
	// 		r.driver_name as driver_name,
	// 		r.vehicle_no as vehicle_no,
	// 		r.date_authorized as date_authorized,
	// 		r.rep_name as rep_name,
	// 		r.rep_date as rep_date,
	// 		r.outward_datetime as outward_datetime
			
	// 		FROM requisition_module r
	// 		LEFT JOIN users u 
	// 			ON r.code = u.id  -- or whatever the actual foreign key is
	// 		LEFT JOIN branch b 
	// 			ON r.organization = b.branch_id
	// 		LEFT JOIN depertment d 
	// 			ON r.department = d.department_id
	// 		LEFT JOIN designation g 
	// 			ON r.designation = g.code
	// 	    WHERE r.code = '" . $code . "'";


	// //$sSqlWrk .= " WHERE `code` = '3'";
	// $rswrk = Conn()->Execute($sSqlWrk);
	// if ($rswrk && !$rswrk->EOF) {
	// 	$date = $rswrk->fields('date');
	// 	$name = $rswrk->fields('name');
	// 	$outward_location = $rswrk->fields('outward_location');
	// 	$delivery_point = $rswrk->fields('delivery_point');
	// 	$department = $rswrk->fields('department');
	// 	$designation = $rswrk->fields('designation');
	// 	$driver_name = $rswrk->fields('driver_name');
	// 	$vehicle_no = $rswrk->fields('vehicle_no');

	// 	$rswrk->Close();
	// }			
	


	$sSqlWrk = "
	SELECT 
		r.outward_location AS outward_location,
		r.delivery_point AS delivery_point,
		r.date AS date,

		CONCAT(u.firstname, ' ', u.lastname) AS name,
		CONCAT(a.firstname, ' ', a.lastname) AS authorizer_name,
		CONCAT(rep.firstname, ' ', rep.lastname) AS rep_name,

		b.branch_name AS organization,
		d.department_name AS department_name,
		g.description AS designation_name,    

		r.item_description AS item_description,
		r.driver_name AS driver_name,
		r.vehicle_no AS vehicle_no,
		r.date_authorized AS date_authorized,
		r.rep_date AS rep_date,
		r.outward_datetime AS outward_datetime,
		DATE(r.outward_datetime) AS outward_date,
		DATE_FORMAT(r.outward_datetime, '%r') AS outward_time   -- 12-hour format with AM/PM


		FROM requisition_module r
		LEFT JOIN users u 
			ON r.name = u.id                -- requester
		LEFT JOIN users a 
			ON r.authorizer_name = a.id     -- authorizer
		LEFT JOIN users rep 
			ON r.rep_name = rep.id          -- rep
		LEFT JOIN branch b 
			ON r.organization = b.branch_id
		LEFT JOIN depertment d 
			ON r.department = d.department_id
		LEFT JOIN designation g 
			ON r.designation = g.code
		WHERE r.code = '" . $code . "'";
		

	$rswrk = Conn()->Execute($sSqlWrk);

	if ($rswrk && !$rswrk->EOF) {
		$date              = $rswrk->fields('date');
		$name              = $rswrk->fields('name');
		$outward_location  = $rswrk->fields('outward_location');
		$delivery_point    = $rswrk->fields('delivery_point');
		$organization      = $rswrk->fields('organization');
		$department        = $rswrk->fields('department_name');   // ✅ match alias
		$designation       = $rswrk->fields('designation_name');  // ✅ match alias
		$item_description  = $rswrk->fields('item_description');
		$driver_name       = $rswrk->fields('driver_name');
		$vehicle_no        = $rswrk->fields('vehicle_no');
		$date_authorized   = $rswrk->fields('date_authorized');
		$authorizer_name   = $rswrk->fields('authorizer_name');
		$rep_name          = $rswrk->fields('rep_name');
		$rep_date          = $rswrk->fields('rep_date');
		$outward_date  = $rswrk->fields('outward_date');
		$outward_time  = $rswrk->fields('outward_time');

		$rswrk->Close();
	}

?>



<!----------------------------------------new note-----------------------------/>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <!- 引入样式 -->
    <link rel="stylesheet" href="stylenote/style.css">

    <style type="text/css" media="print">
        .noprint {
            display: none
        }
        
        .print {
            display: block !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <header class="el-header noprint">
            <div class="icon-btns">
                <i class="icon-list" @click="changeLeftMenu"></i>
                <i class="icon-skip_previous" v-bind:class="{'disabled': currentPage == 1}" @click="changeCurrentPage('first')"></i>
                <i class="icon-play_arrow prev-icon" v-bind:class="{'disabled': currentPage == 1}" @click="changeCurrentPage('prev')"></i>
                <i class="icon-play_arrow" v-bind:class="{'disabled': currentPage == pageNum}" @click="changeCurrentPage('next')"></i>
                <i class="icon-skip_next" v-bind:class="{'disabled': currentPage == pageNum}" @click="changeCurrentPage('last')"></i>
                <select v-model="currentPage">
                    <option v-for="page in pageNum" v-bind:value="page">page {{ page }}</option>
                </select>
                <i class="icon-zoom_in" v-bind:class="{'disabled': zoomNum == 2}" @click="modifyZoom('in')"></i>
                <select v-model="zoomNum">
                    <option value="0.5">50%</option>
                    <option value="0.6">60%</option>
                    <option value="0.7">70%</option>
                    <option value="0.8">80%</option>
                    <option value="0.9">90%</option>
                    <option value="1.0" selected>100%</option>
                    <option value="1.1">110%</option>
                    <option value="1.2">120%</option>
                    <option value="1.3">130%</option>
                    <option value="1.4">140%</option>
                    <option value="1.5">150%</option>
                    <option value="1.6">160%</option>
                    <option value="1.7">170%</option>
                    <option value="1.8">180%</option>
                    <option value="1.9">190%</option>
                    <option value="2.0">200%</option>
                </select>
                <i class="icon-zoom_out" v-bind:class="{'disabled': zoomNum == 0.5}" @click="modifyZoom('out')"></i>
                <i class="icon-format_align_left" @click="textAlign = 'left'"></i>
                <i class="icon-format_align_center" @click="textAlign = 'center'"></i>
                <i class="icon-format_align_right" @click="textAlign = 'right'"></i>
                <i class="icon-print" @click="window.print()"></i>
            </div>
        </header>

        <aside class="noprint" width="240px" v-show="ifMenuShow">
            <nav class="tabNav">
                <ul>
                    <li v-bind:class="{ 'curr': currentNav == 0 }" @click="currentNav = 0">Page</li>
                    <li v-bind:class="{ 'curr': currentNav == 1 }" @click="currentNav = 1">Bookmark</li>
                </ul>

                <div class="clear"></div>
            </nav>

            <div class="tab-conent scrollbar" v-bind:style="{ height: asideHeight + 'px' }">

            <section v-show="currentNav == 0">
                <ul class="page-menu">
                    <li v-for="page in pageNum" v-bind:class="{ 'curr': currentPage == page }" @click="changePage(page)"><i class="icon-file-text2"></i> page {{ page }}</li>
                </ul>
            </section>

            <section v-show="currentNav == 1">
                <ul class="page-menu">
                    <li v-for="page in pageNum" v-bind:class="{ 'curr': currentPage == page }" @click="changePage(page)"><i class="icon-turned_in_not"></i> Bookmark {{ page }}</li>
                </ul>
            </section>
        </div>

        </aside>
        <div class="main scrollbar noprint"  v-bind:style="{ height: mainHeight + 'px' }" v-bind:class="{ 'mainLeftM': ifMenuShow, 'aleft': textAlign === 'left','acenter': textAlign === 'center','aright': textAlign === 'right'}">
            <div class="conent" v-html="pageContent" v-bind:style="zoomStyle"></div>

            <div class="clear"></div>
        </div>

        <!--专门只为打印的内容-->
        <div class="conent print" style="display:none" v-html="pageContent"></div>
    </div>
</body>
<!-- 先引入 Vue -->
<script src="jsnote/vue.min.js"></script>
<script>

var app = new Vue({
        el: '#app',
        data: function() {
            return {
                // visible: false,
                isCollapse: false,
                currentNav: 0,
                activeName2: 'first',
                pageNum: 1, 
                currentPage: 1,
                pageContent: '',
                asideHeight: 300,
                mainHeight: 300,
                ifMenuShow: true,
                zoomNum: '1.0',
                textAlign: 'left',
                zoomStyle: {},
                pageDatas: ['<div><div style="position:absolute;top:0.000000px;left:0.000000px"><nobr><img height="1056.000000" width="816.000000" src ="bgimgnote/bg00001.jpg"/><br/></nobr></div><p><span style="position:absolute;top:96.629883px;left:547.226013px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">120, Ikotun Egbe Road </span></nobr></span></p><p><span style="position:absolute;top:112.676758px;left:547.226013px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">Ejigbo, Lagos, Nigeria.</span></nobr></span></p><p><span style="position:absolute;top:128.696945px;left:547.226013px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">+2348150989340</span></nobr></span></p><p><span style="position:absolute;top:144.717117px;left:550.110779px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#0563C1;writing-mode: horizontal-tb;"><a href="mailto:info@groomingcentre.org">info@groomingcentre.org</a></span></nobr></span></p><p><span style="position:absolute;top:160.737305px;left:550.110779px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">ww.gromingcentre.org</span></nobr></span></p><p><span style="position:absolute;top:176.850098px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-weight:bold;color:#000000;writing-mode: horizontal-tb;">MATERIAL OUTWARD NOTE </span></nobr></span></p><p><span style="position:absolute;top:239.052567px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">OUTWARD LOACTION: ………<?php echo $outward_location ?>…………</span></nobr></span></p><p><span style="position:absolute;top:269.330811px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">DELIVERY POINT: …………<?php echo $delivery_point ?>…………….</span></nobr></span></p><p><span style="position:absolute;top:329.766937px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-weight:bold;color:#000000;writing-mode: horizontal-tb;">REQUEST PERSONEL INFORMATION: </span></nobr></span></p><p><span style="position:absolute;top:360.045166px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">NAME: …………<?php echo $name ?>……………… DESIGNATION ……...……<?php echo $designation ?>………………</span></nobr></span><span style="position:absolute;top:390.323334px;left:96.188927px"><nobr>SIGNATURE……………………… DEPT……<?php echo $department ?>……. DATE/TIME…<?php echo $date ?>……</span></nobr></span><span style="position:absolute;top:420.601501px;left:96.188927px"><nobr>ISSUER’S INFORMATION: </span></nobr></span></p><p><span style="position:absolute;top:450.719513px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">NAME: ……<?php echo $authorizer_name ?>…….. SIGNATURE….....…..... DATE/TIME: …...<?php echo $date_authorized ?>.......</span></nobr></span><span style="position:absolute;top:480.997681px;left:96.188927px"><nobr>WAREHOUSE REP. INFORMATION:</span></nobr></span></p><p><span style="position:absolute;top:511.275879px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">NAME…………<?php echo $rep_name ?>…………. SIGNATURE………………… DATE:……<?php echo $rep_date ?>……….</span></nobr></span><span style="position:absolute;top:541.554016px;left:96.188927px"><nobr>MOTOR/VEHICLE INFORMATION: </span></nobr></span></p><p><span style="position:absolute;top:571.858948px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">DRIVER’S NAME…………<?php echo $driver_name ?>…………………. VEHICLE NO……...…<?php echo $vehicle_no ?>……..</span></nobr></span><span style="position:absolute;top:601.976929px;left:96.188927px"><nobr>SIGNATURE…………………. OUTWARD TIME ……<?php echo $outward_time ?>………. DATE……<?php echo $outward_date ?>……</span></nobr></span><span style="position:absolute;top:632.255127px;left:96.188927px"><nobr>EXIT POINT INFORMATION: (FOR SECURITY USE ONLY) </span></nobr></span></p><p><span style="position:absolute;top:662.533264px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">NAME………………………………………….. SIGNATURE…………………… DATE……………….</span></nobr></span></p><div style="position:absolute;top:692.486572px;left:96.509453px"><nobr><table height="193.083496px" width="624.480042px" border="0"><tr><td height = "13.096497" width="53.843834" rowspan="1" colspan="1"><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;"><p><span style="position:absolute;top:0.805501px;left:7.211751px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">S/NO</span></nobr></span></p></span></td><td height = "13.096497" width="180.324158" rowspan="1" colspan="1"><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;"><p><span style="position:absolute;top:0.805501px;left:79.003525px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">DESCRIPTION OF ITEMS </span></nobr></span></p></span></td><td height = "13.096497" width="117.091034" rowspan="1" colspan="1"><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;"><p><span style="position:absolute;top:0.805501px;left:319.435791px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">UOM</span></nobr></span></p></span></td><td height = "13.096497" width="117.101013" rowspan="1" colspan="1"><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;"><p><span style="position:absolute;top:0.805501px;left:475.557129px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">QTY</span></nobr></span></p></span></td></tr><tr><td height = "13.216675" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.216675" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.216675" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.216675" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.096558" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.096558" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.096558" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.096558" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.216614" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.216614" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.216614" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.216614" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.096558" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.096558" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.096558" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.096558" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.216614" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.216614" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.216614" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.216614" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.246765" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.246765" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.246765" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.246765" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.096497" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.096497" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.096497" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.096497" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.216675" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.216675" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.216675" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.216675" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.096497" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.096497" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.096497" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.096497" width="117.101013" rowspan="1" colspan="1"></td></tr><tr><td height = "13.216675" width="53.843834" rowspan="1" colspan="1"></td><td height = "13.216675" width="180.324158" rowspan="1" colspan="1"></td><td height = "13.216675" width="117.091034" rowspan="1" colspan="1"></td><td height = "13.216675" width="117.101013" rowspan="1" colspan="1"></td></tr></table></nobr></div><p><span style="position:absolute;top:916.808594px;left:96.188927px"><nobr><span class="" style="font-family:Arial;font-style:normal;font-weight:normal;color:#000000;writing-mode: horizontal-tb;">HOD APPROVAL……………………………………… SIGNATURE/DATE ……………………………</span></nobr></span></p></div>']
            }
        },
        mounted: function() {
            this.$nextTick(function() {
                this.pageNum = this.pageDatas.length;
                this.pageContent = this.pageDatas[0];

                this.setLeftMenuHeight();
            })
        },
        watch: {
            'currentPage': function(newVal, oldValue) {
                // console.log('newVal ' + newVal, 'oldValue ' + oldValue);
                if (newVal) {
                    this.pageContent = this.pageDatas[this.currentPage - 1];
                }
            },
            'zoomNum': function(newVal, oldValue) {
                if (newVal) {
                    this.zoomStyle = {
                        'transform': 'scale(' + newVal + ')',
                        '-webkit-transform': 'scale(' + newVal + ')',
                        '-ms-transform': 'scale(' + newVal + ')',
                        '-moz-transform': 'scale(' + newVal + ')',
                        '-o-transform': 'scale(' + newVal + ')'
                    }
                }
            }
        },
        methods: {
            
            changeCurrentPage: function(methods) {
                switch (methods) {
                    case 'first':
                        this.currentPage = 1;
                        break;
                    case 'prev':
                        if (this.currentPage > 1) {
                            this.currentPage -= 1;
                        }
                        break;
                    case 'next':
                        if (this.currentPage < this.pageNum) {
                            this.currentPage += 1;
                        }
                        break;
                    case 'last':
                        this.currentPage = this.pageNum;
                        break;
                }
            },

            gotoPage: function(page) {
                console.log(page);
                this.currentPage = page;
            },
            modifyZoom: function(type) {
                switch (type) {
                    case 'in':
                        if (this.zoomNum < 2) {
                            // this.zoomNum = (this.zoomNum + 0.1).toFixed(1);
                            this.zoomNum = (parseFloat(this.zoomNum) + 0.1).toFixed(1);
                        }
                        break;
                    case 'out':
                        if (this.zoomNum > 0.5) {
                            this.zoomNum = (parseFloat(this.zoomNum) - 0.1).toFixed(1);
                        }
                        break;
                    default:
                        break;
                }
                console.log(this.zoomNum);
            },
            setLeftMenuHeight: function() {
                // this.asideHeight = document.body.scrollHeight - 60;
                this.mainHeight = document.documentElement.clientHeight - 60 - 20;
                // 60为头部导航高度， 46为menu高度， 40为上下padding
                this.asideHeight = this.mainHeight - 20 - 46;
            },
            changePage: function(page) {
                this.currentPage = page;
                // this.pageContent = this.pageDatas[page - 1];
            },
            changeLeftMenu: function() {
                this.ifMenuShow = !this.ifMenuShow;
            }
        }
    });

function gotoPage(page) {
    console.log(page);
    app.gotoPage(page);
}

</script>

</html>
















<!-- %%Custom page content end%% --><?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php //include_once "footer.php" ?>
<?php
$material_note_php->Page_Terminate();
?>
