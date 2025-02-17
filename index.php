<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Page object name
	var $PageObjName = 'default';

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
			define("EW_PAGE_ID", 'default', TRUE);

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

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Global Page Loading event (in userfn*.php)

		Page_Loading();

		// Page Load event
		$this->Page_Load();

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

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		$this->Page_Redirecting($url);

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
		global $Security, $Language, $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($Language->Phrase("SessionExpired"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'home.php'))
		$this->Page_Terminate("home.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'branch'))
			$this->Page_Terminate("branchlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'category'))
			$this->Page_Terminate("categorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'client'))
			$this->Page_Terminate("clientlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'depertment'))
			$this->Page_Terminate("depertmentlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'gender'))
			$this->Page_Terminate("genderlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'incident-category'))
			$this->Page_Terminate("incident_categorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'report_form'))
			$this->Page_Terminate("report_formlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'sub-category'))
			$this->Page_Terminate("sub_categorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'users'))
			$this->Page_Terminate("userslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'status'))
			$this->Page_Terminate("statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'incident_location'))
			$this->Page_Terminate("incident_locationlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'no_of_people'))
			$this->Page_Terminate("no_of_peoplelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'type_of_incident'))
			$this->Page_Terminate("type_of_incidentlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'news'))
			$this->Page_Terminate("newslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'userlevelpermissions'))
			$this->Page_Terminate("userlevelpermissionslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'userlevels'))
			$this->Page_Terminate("userlevelslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'testing.php'))
			$this->Page_Terminate("testing.php");
		if ($Security->AllowList(CurrentProjectID() . 'audittrail'))
			$this->Page_Terminate("audittraillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'departments'))
			$this->Page_Terminate("departmentslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'chart_report.php'))
			$this->Page_Terminate("chart_report.php");
		if ($Security->AllowList(CurrentProjectID() . 'incident_sub_location'))
			$this->Page_Terminate("incident_sub_locationlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'incident_venue'))
			$this->Page_Terminate("incident_venuelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'sub_sub_category'))
			$this->Page_Terminate("sub_sub_categorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'report'))
			$this->Page_Terminate("reportlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'designation'))
			$this->Page_Terminate("designationlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'selection_sub_category'))
			$this->Page_Terminate("selection_sub_categorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'reason'))
			$this->Page_Terminate("reasonlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'buildings'))
			$this->Page_Terminate("buildingslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'floors'))
			$this->Page_Terminate("floorslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'items'))
			$this->Page_Terminate("itemslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'maintenance'))
			$this->Page_Terminate("maintenancelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'maintained_status'))
			$this->Page_Terminate("maintained_statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'maintenance_report'))
			$this->Page_Terminate("maintenance_reportlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'assign_task'))
			$this->Page_Terminate("assign_tasklist.php");
		if ($Security->AllowList(CurrentProjectID() . 'inventory'))
			$this->Page_Terminate("inventorylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'issuance_store'))
			$this->Page_Terminate("issuance_storelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dispenser'))
			$this->Page_Terminate("dispenserlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dispenser_status'))
			$this->Page_Terminate("dispenser_statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dispenser_type'))
			$this->Page_Terminate("dispenser_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'dispenser_report'))
			$this->Page_Terminate("dispenser_reportlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'action_taken'))
			$this->Page_Terminate("action_takenlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'statuss'))
			$this->Page_Terminate("statusslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'inventory_report'))
			$this->Page_Terminate("inventory_reportlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'inventory_record'))
			$this->Page_Terminate("inventory_recordlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'systems'))
			$this->Page_Terminate("systemslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'system_issues'))
			$this->Page_Terminate("system_issueslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pc_issuance'))
			$this->Page_Terminate("pc_issuancelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'issuance_history'))
			$this->Page_Terminate("issuance_historylist.php");
		if ($Security->AllowList(CurrentProjectID() . 'issuance_tracking'))
			$this->Page_Terminate("issuance_trackinglist.php");
		if ($Security->AllowList(CurrentProjectID() . 'system_status'))
			$this->Page_Terminate("system_statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pc_issuance_report'))
			$this->Page_Terminate("pc_issuance_reportlist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage(ew_DeniedMsg() . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
