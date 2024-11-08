<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "dispenser_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$dispenser_report_delete = NULL; // Initialize page object first

class cdispenser_report_delete extends cdispenser_report {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'dispenser_report';

	// Page object name
	var $PageObjName = 'dispenser_report_delete';

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
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Parent constuctor
		parent::__construct();

		// Table object (dispenser_report)
		if (!isset($GLOBALS["dispenser_report"]) || get_class($GLOBALS["dispenser_report"]) == "cdispenser_report") {
			$GLOBALS["dispenser_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dispenser_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dispenser_report');

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("dispenser_reportlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->date_initiated->SetVisibility();
		$this->referrence_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->fullname->SetVisibility();
		$this->department->SetVisibility();
		$this->location->SetVisibility();
		$this->sub_location->SetVisibility();
		$this->venue->SetVisibility();
		$this->type->SetVisibility();
		$this->action_taken->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->status->SetVisibility();
		$this->initiated_by->SetVisibility();

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
		global $EW_EXPORT, $dispenser_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dispenser_report);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("dispenser_reportlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in dispenser_report class, dispenser_reportinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("dispenser_reportlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id->setDbValue($row['id']);
		$this->date_initiated->setDbValue($row['date_initiated']);
		$this->referrence_id->setDbValue($row['referrence_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->fullname->setDbValue($row['fullname']);
		$this->department->setDbValue($row['department']);
		$this->location->setDbValue($row['location']);
		$this->sub_location->setDbValue($row['sub_location']);
		$this->venue->setDbValue($row['venue']);
		$this->type->setDbValue($row['type']);
		$this->action_taken->setDbValue($row['action_taken']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->status->setDbValue($row['status']);
		$this->initiated_by->setDbValue($row['initiated_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['referrence_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['fullname'] = NULL;
		$row['department'] = NULL;
		$row['location'] = NULL;
		$row['sub_location'] = NULL;
		$row['venue'] = NULL;
		$row['type'] = NULL;
		$row['action_taken'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['status'] = NULL;
		$row['initiated_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->referrence_id->DbValue = $row['referrence_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->fullname->DbValue = $row['fullname'];
		$this->department->DbValue = $row['department'];
		$this->location->DbValue = $row['location'];
		$this->sub_location->DbValue = $row['sub_location'];
		$this->venue->DbValue = $row['venue'];
		$this->type->DbValue = $row['type'];
		$this->action_taken->DbValue = $row['action_taken'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->status->DbValue = $row['status'];
		$this->initiated_by->DbValue = $row['initiated_by'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date_initiated
		// referrence_id
		// staff_id
		// fullname
		// department
		// location
		// sub_location
		// venue
		// type
		// action_taken
		// initiator_action
		// initiator_comment
		// status
		// initiated_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 7);
		$this->date_initiated->ViewCustomAttributes = "";

		// referrence_id
		$this->referrence_id->ViewValue = $this->referrence_id->CurrentValue;
		$this->referrence_id->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staff_id->ViewValue = $this->staff_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
			}
		} else {
			$this->staff_id->ViewValue = NULL;
		}
		$this->staff_id->ViewCustomAttributes = "";

		// fullname
		if (strval($this->fullname->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->fullname->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->fullname->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->fullname->ViewValue = $this->fullname->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->fullname->ViewValue = $this->fullname->CurrentValue;
			}
		} else {
			$this->fullname->ViewValue = NULL;
		}
		$this->fullname->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->department->ViewValue = $this->department->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->department->ViewValue = $this->department->CurrentValue;
			}
		} else {
			$this->department->ViewValue = NULL;
		}
		$this->department->ViewCustomAttributes = "";

		// location
		if (strval($this->location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->location->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->location->ViewValue = $this->location->CurrentValue;
			}
		} else {
			$this->location->ViewValue = NULL;
		}
		$this->location->ViewCustomAttributes = "";

		// sub_location
		if (strval($this->sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_sub` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_location->ViewValue = $this->sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_location->ViewValue = $this->sub_location->CurrentValue;
			}
		} else {
			$this->sub_location->ViewValue = NULL;
		}
		$this->sub_location->ViewCustomAttributes = "";

		// venue
		if (strval($this->venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->venue->ViewValue = $this->venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->venue->ViewValue = $this->venue->CurrentValue;
			}
		} else {
			$this->venue->ViewValue = NULL;
		}
		$this->venue->ViewCustomAttributes = "";

		// type
		if (strval($this->type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_type`";
		$sWhereWrk = "";
		$this->type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->type->ViewValue = $this->type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->type->ViewValue = $this->type->CurrentValue;
			}
		} else {
			$this->type->ViewValue = NULL;
		}
		$this->type->ViewCustomAttributes = "";

		// action_taken
		$this->action_taken->ViewValue = $this->action_taken->CurrentValue;
		if (strval($this->action_taken->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->action_taken->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `action_taken`";
		$sWhereWrk = "";
		$this->action_taken->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->action_taken, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->action_taken->ViewValue = $this->action_taken->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->action_taken->ViewValue = $this->action_taken->CurrentValue;
			}
		} else {
			$this->action_taken->ViewValue = NULL;
		}
		$this->action_taken->ViewCustomAttributes = "";

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->status->ViewValue = $this->status->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->status->ViewValue = $this->status->CurrentValue;
			}
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

		// initiated_by
		$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
		if (strval($this->initiated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->initiated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->initiated_by->ViewValue = $this->initiated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
			}
		} else {
			$this->initiated_by->ViewValue = NULL;
		}
		$this->initiated_by->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// referrence_id
			$this->referrence_id->LinkCustomAttributes = "";
			$this->referrence_id->HrefValue = "";
			$this->referrence_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// fullname
			$this->fullname->LinkCustomAttributes = "";
			$this->fullname->HrefValue = "";
			$this->fullname->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";
			$this->location->TooltipValue = "";

			// sub_location
			$this->sub_location->LinkCustomAttributes = "";
			$this->sub_location->HrefValue = "";
			$this->sub_location->TooltipValue = "";

			// venue
			$this->venue->LinkCustomAttributes = "";
			$this->venue->HrefValue = "";
			$this->venue->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// action_taken
			$this->action_taken->LinkCustomAttributes = "";
			$this->action_taken->HrefValue = "";
			$this->action_taken->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// initiated_by
			$this->initiated_by->LinkCustomAttributes = "";
			$this->initiated_by->HrefValue = "";
			$this->initiated_by->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];

				// Delete old files
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("dispenser_reportlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($dispenser_report_delete)) $dispenser_report_delete = new cdispenser_report_delete();

// Page init
$dispenser_report_delete->Page_Init();

// Page main
$dispenser_report_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dispenser_report_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdispenser_reportdelete = new ew_Form("fdispenser_reportdelete", "delete");

// Form_CustomValidate event
fdispenser_reportdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdispenser_reportdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdispenser_reportdelete.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportdelete.Lists["x_staff_id"].Data = "<?php echo $dispenser_report_delete->staff_id->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_delete->staff_id->LookupFilterQuery(TRUE, "delete"))) ?>;
fdispenser_reportdelete.Lists["x_fullname"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportdelete.Lists["x_fullname"].Data = "<?php echo $dispenser_report_delete->fullname->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fdispenser_reportdelete.Lists["x_department"].Data = "<?php echo $dispenser_report_delete->department->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_delete->department->LookupFilterQuery(TRUE, "delete"))) ?>;
fdispenser_reportdelete.Lists["x_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
fdispenser_reportdelete.Lists["x_location"].Data = "<?php echo $dispenser_report_delete->location->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.Lists["x_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_venue"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
fdispenser_reportdelete.Lists["x_sub_location"].Data = "<?php echo $dispenser_report_delete->sub_location->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.Lists["x_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_venue"};
fdispenser_reportdelete.Lists["x_venue"].Data = "<?php echo $dispenser_report_delete->venue->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.Lists["x_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","x_serial_no","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_type"};
fdispenser_reportdelete.Lists["x_type"].Data = "<?php echo $dispenser_report_delete->type->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.Lists["x_action_taken"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"action_taken"};
fdispenser_reportdelete.Lists["x_action_taken"].Data = "<?php echo $dispenser_report_delete->action_taken->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.AutoSuggests["x_action_taken"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_delete->action_taken->LookupFilterQuery(TRUE, "delete"))) ?>;
fdispenser_reportdelete.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdispenser_reportdelete.Lists["x_initiator_action"].Options = <?php echo json_encode($dispenser_report_delete->initiator_action->Options()) ?>;
fdispenser_reportdelete.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_status"};
fdispenser_reportdelete.Lists["x_status"].Data = "<?php echo $dispenser_report_delete->status->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_delete->status->LookupFilterQuery(TRUE, "delete"))) ?>;
fdispenser_reportdelete.Lists["x_initiated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportdelete.Lists["x_initiated_by"].Data = "<?php echo $dispenser_report_delete->initiated_by->LookupFilterQuery(FALSE, "delete") ?>";
fdispenser_reportdelete.AutoSuggests["x_initiated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_delete->initiated_by->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $dispenser_report_delete->ShowPageHeader(); ?>
<?php
$dispenser_report_delete->ShowMessage();
?>
<form name="fdispenser_reportdelete" id="fdispenser_reportdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dispenser_report_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dispenser_report_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dispenser_report">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($dispenser_report_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($dispenser_report->date_initiated->Visible) { // date_initiated ?>
		<th class="<?php echo $dispenser_report->date_initiated->HeaderCellClass() ?>"><span id="elh_dispenser_report_date_initiated" class="dispenser_report_date_initiated"><?php echo $dispenser_report->date_initiated->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->referrence_id->Visible) { // referrence_id ?>
		<th class="<?php echo $dispenser_report->referrence_id->HeaderCellClass() ?>"><span id="elh_dispenser_report_referrence_id" class="dispenser_report_referrence_id"><?php echo $dispenser_report->referrence_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->staff_id->Visible) { // staff_id ?>
		<th class="<?php echo $dispenser_report->staff_id->HeaderCellClass() ?>"><span id="elh_dispenser_report_staff_id" class="dispenser_report_staff_id"><?php echo $dispenser_report->staff_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->fullname->Visible) { // fullname ?>
		<th class="<?php echo $dispenser_report->fullname->HeaderCellClass() ?>"><span id="elh_dispenser_report_fullname" class="dispenser_report_fullname"><?php echo $dispenser_report->fullname->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->department->Visible) { // department ?>
		<th class="<?php echo $dispenser_report->department->HeaderCellClass() ?>"><span id="elh_dispenser_report_department" class="dispenser_report_department"><?php echo $dispenser_report->department->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->location->Visible) { // location ?>
		<th class="<?php echo $dispenser_report->location->HeaderCellClass() ?>"><span id="elh_dispenser_report_location" class="dispenser_report_location"><?php echo $dispenser_report->location->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->sub_location->Visible) { // sub_location ?>
		<th class="<?php echo $dispenser_report->sub_location->HeaderCellClass() ?>"><span id="elh_dispenser_report_sub_location" class="dispenser_report_sub_location"><?php echo $dispenser_report->sub_location->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->venue->Visible) { // venue ?>
		<th class="<?php echo $dispenser_report->venue->HeaderCellClass() ?>"><span id="elh_dispenser_report_venue" class="dispenser_report_venue"><?php echo $dispenser_report->venue->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->type->Visible) { // type ?>
		<th class="<?php echo $dispenser_report->type->HeaderCellClass() ?>"><span id="elh_dispenser_report_type" class="dispenser_report_type"><?php echo $dispenser_report->type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->action_taken->Visible) { // action_taken ?>
		<th class="<?php echo $dispenser_report->action_taken->HeaderCellClass() ?>"><span id="elh_dispenser_report_action_taken" class="dispenser_report_action_taken"><?php echo $dispenser_report->action_taken->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->initiator_action->Visible) { // initiator_action ?>
		<th class="<?php echo $dispenser_report->initiator_action->HeaderCellClass() ?>"><span id="elh_dispenser_report_initiator_action" class="dispenser_report_initiator_action"><?php echo $dispenser_report->initiator_action->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->status->Visible) { // status ?>
		<th class="<?php echo $dispenser_report->status->HeaderCellClass() ?>"><span id="elh_dispenser_report_status" class="dispenser_report_status"><?php echo $dispenser_report->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($dispenser_report->initiated_by->Visible) { // initiated_by ?>
		<th class="<?php echo $dispenser_report->initiated_by->HeaderCellClass() ?>"><span id="elh_dispenser_report_initiated_by" class="dispenser_report_initiated_by"><?php echo $dispenser_report->initiated_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$dispenser_report_delete->RecCnt = 0;
$i = 0;
while (!$dispenser_report_delete->Recordset->EOF) {
	$dispenser_report_delete->RecCnt++;
	$dispenser_report_delete->RowCnt++;

	// Set row properties
	$dispenser_report->ResetAttrs();
	$dispenser_report->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$dispenser_report_delete->LoadRowValues($dispenser_report_delete->Recordset);

	// Render row
	$dispenser_report_delete->RenderRow();
?>
	<tr<?php echo $dispenser_report->RowAttributes() ?>>
<?php if ($dispenser_report->date_initiated->Visible) { // date_initiated ?>
		<td<?php echo $dispenser_report->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_date_initiated" class="dispenser_report_date_initiated">
<span<?php echo $dispenser_report->date_initiated->ViewAttributes() ?>>
<?php echo $dispenser_report->date_initiated->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->referrence_id->Visible) { // referrence_id ?>
		<td<?php echo $dispenser_report->referrence_id->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_referrence_id" class="dispenser_report_referrence_id">
<span<?php echo $dispenser_report->referrence_id->ViewAttributes() ?>>
<?php echo $dispenser_report->referrence_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->staff_id->Visible) { // staff_id ?>
		<td<?php echo $dispenser_report->staff_id->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_staff_id" class="dispenser_report_staff_id">
<span<?php echo $dispenser_report->staff_id->ViewAttributes() ?>>
<?php echo $dispenser_report->staff_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->fullname->Visible) { // fullname ?>
		<td<?php echo $dispenser_report->fullname->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_fullname" class="dispenser_report_fullname">
<span<?php echo $dispenser_report->fullname->ViewAttributes() ?>>
<?php echo $dispenser_report->fullname->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->department->Visible) { // department ?>
		<td<?php echo $dispenser_report->department->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_department" class="dispenser_report_department">
<span<?php echo $dispenser_report->department->ViewAttributes() ?>>
<?php echo $dispenser_report->department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->location->Visible) { // location ?>
		<td<?php echo $dispenser_report->location->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_location" class="dispenser_report_location">
<span<?php echo $dispenser_report->location->ViewAttributes() ?>>
<?php echo $dispenser_report->location->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->sub_location->Visible) { // sub_location ?>
		<td<?php echo $dispenser_report->sub_location->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_sub_location" class="dispenser_report_sub_location">
<span<?php echo $dispenser_report->sub_location->ViewAttributes() ?>>
<?php echo $dispenser_report->sub_location->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->venue->Visible) { // venue ?>
		<td<?php echo $dispenser_report->venue->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_venue" class="dispenser_report_venue">
<span<?php echo $dispenser_report->venue->ViewAttributes() ?>>
<?php echo $dispenser_report->venue->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->type->Visible) { // type ?>
		<td<?php echo $dispenser_report->type->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_type" class="dispenser_report_type">
<span<?php echo $dispenser_report->type->ViewAttributes() ?>>
<?php echo $dispenser_report->type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->action_taken->Visible) { // action_taken ?>
		<td<?php echo $dispenser_report->action_taken->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_action_taken" class="dispenser_report_action_taken">
<span<?php echo $dispenser_report->action_taken->ViewAttributes() ?>>
<?php echo $dispenser_report->action_taken->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->initiator_action->Visible) { // initiator_action ?>
		<td<?php echo $dispenser_report->initiator_action->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_initiator_action" class="dispenser_report_initiator_action">
<span<?php echo $dispenser_report->initiator_action->ViewAttributes() ?>>
<?php echo $dispenser_report->initiator_action->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->status->Visible) { // status ?>
		<td<?php echo $dispenser_report->status->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_status" class="dispenser_report_status">
<span<?php echo $dispenser_report->status->ViewAttributes() ?>>
<?php echo $dispenser_report->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($dispenser_report->initiated_by->Visible) { // initiated_by ?>
		<td<?php echo $dispenser_report->initiated_by->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_delete->RowCnt ?>_dispenser_report_initiated_by" class="dispenser_report_initiated_by">
<span<?php echo $dispenser_report->initiated_by->ViewAttributes() ?>>
<?php echo $dispenser_report->initiated_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$dispenser_report_delete->Recordset->MoveNext();
}
$dispenser_report_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $dispenser_report_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdispenser_reportdelete.Init();
</script>
<?php
$dispenser_report_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dispenser_report_delete->Page_Terminate();
?>
