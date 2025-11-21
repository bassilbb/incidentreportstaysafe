<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "gen_maintenanceinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$gen_maintenance_delete = NULL; // Initialize page object first

class cgen_maintenance_delete extends cgen_maintenance {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'gen_maintenance';

	// Page object name
	var $PageObjName = 'gen_maintenance_delete';

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
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

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

		// Table object (gen_maintenance)
		if (!isset($GLOBALS["gen_maintenance"]) || get_class($GLOBALS["gen_maintenance"]) == "cgen_maintenance") {
			$GLOBALS["gen_maintenance"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gen_maintenance"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gen_maintenance');

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
				$this->Page_Terminate(ew_GetUrl("gen_maintenancelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->datetime->SetVisibility();
		$this->gen_name->SetVisibility();
		$this->maintenance_type->SetVisibility();
		$this->running_hours->SetVisibility();
		$this->cost->SetVisibility();
		$this->labour_fee->SetVisibility();
		$this->total->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->status->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->approver_date->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approver_comment->SetVisibility();
		$this->approved_by->SetVisibility();

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
		global $EW_EXPORT, $gen_maintenance;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($gen_maintenance);
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
			$this->Page_Terminate("gen_maintenancelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in gen_maintenance class, gen_maintenanceinfo.php

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
				$this->Page_Terminate("gen_maintenancelist.php"); // Return to list
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
		$this->datetime->setDbValue($row['datetime']);
		$this->gen_name->setDbValue($row['gen_name']);
		$this->maintenance_type->setDbValue($row['maintenance_type']);
		$this->running_hours->setDbValue($row['running_hours']);
		$this->cost->setDbValue($row['cost']);
		$this->labour_fee->setDbValue($row['labour_fee']);
		$this->total->setDbValue($row['total']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->approver_date->setDbValue($row['approver_date']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['datetime'] = NULL;
		$row['gen_name'] = NULL;
		$row['maintenance_type'] = NULL;
		$row['running_hours'] = NULL;
		$row['cost'] = NULL;
		$row['labour_fee'] = NULL;
		$row['total'] = NULL;
		$row['staff_id'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['approver_date'] = NULL;
		$row['approver_action'] = NULL;
		$row['approver_comment'] = NULL;
		$row['approved_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime->DbValue = $row['datetime'];
		$this->gen_name->DbValue = $row['gen_name'];
		$this->maintenance_type->DbValue = $row['maintenance_type'];
		$this->running_hours->DbValue = $row['running_hours'];
		$this->cost->DbValue = $row['cost'];
		$this->labour_fee->DbValue = $row['labour_fee'];
		$this->total->DbValue = $row['total'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->cost->FormValue == $this->cost->CurrentValue && is_numeric(ew_StrToFloat($this->cost->CurrentValue)))
			$this->cost->CurrentValue = ew_StrToFloat($this->cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->labour_fee->FormValue == $this->labour_fee->CurrentValue && is_numeric(ew_StrToFloat($this->labour_fee->CurrentValue)))
			$this->labour_fee->CurrentValue = ew_StrToFloat($this->labour_fee->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// datetime
		// gen_name
		// maintenance_type
		// running_hours
		// cost
		// labour_fee
		// total
		// staff_id
		// status
		// initiator_action
		// initiator_comment
		// approver_date
		// approver_action
		// approver_comment
		// approved_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime
		$this->datetime->ViewValue = $this->datetime->CurrentValue;
		$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 0);
		$this->datetime->ViewCustomAttributes = "";

		// gen_name
		if (strval($this->gen_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
		$sWhereWrk = "";
		$this->gen_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->gen_name->ViewValue = $this->gen_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gen_name->ViewValue = $this->gen_name->CurrentValue;
			}
		} else {
			$this->gen_name->ViewValue = NULL;
		}
		$this->gen_name->ViewCustomAttributes = "";

		// maintenance_type
		if (strval($this->maintenance_type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintenance_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintenance_type`";
		$sWhereWrk = "";
		$this->maintenance_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintenance_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->maintenance_type->ViewValue = $this->maintenance_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintenance_type->ViewValue = $this->maintenance_type->CurrentValue;
			}
		} else {
			$this->maintenance_type->ViewValue = NULL;
		}
		$this->maintenance_type->ViewCustomAttributes = "";

		// running_hours
		$this->running_hours->ViewValue = $this->running_hours->CurrentValue;
		$this->running_hours->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// labour_fee
		$this->labour_fee->ViewValue = $this->labour_fee->CurrentValue;
		$this->labour_fee->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// staff_id
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_id->ViewValue = $this->staff_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
			}
		} else {
			$this->staff_id->ViewValue = NULL;
		}
		$this->staff_id->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_status`";
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

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

		// initiator_comment
		$this->initiator_comment->ViewValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->ViewCustomAttributes = "";

		// approver_date
		$this->approver_date->ViewValue = $this->approver_date->CurrentValue;
		$this->approver_date->ViewValue = ew_FormatDateTime($this->approver_date->ViewValue, 0);
		$this->approver_date->ViewCustomAttributes = "";

		// approver_action
		if (strval($this->approver_action->CurrentValue) <> "") {
			$this->approver_action->ViewValue = $this->approver_action->OptionCaption($this->approver_action->CurrentValue);
		} else {
			$this->approver_action->ViewValue = NULL;
		}
		$this->approver_action->ViewCustomAttributes = "";

		// approver_comment
		$this->approver_comment->ViewValue = $this->approver_comment->CurrentValue;
		$this->approver_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		if (strval($this->approved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->approved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";
			$this->gen_name->TooltipValue = "";

			// maintenance_type
			$this->maintenance_type->LinkCustomAttributes = "";
			$this->maintenance_type->HrefValue = "";
			$this->maintenance_type->TooltipValue = "";

			// running_hours
			$this->running_hours->LinkCustomAttributes = "";
			$this->running_hours->HrefValue = "";
			$this->running_hours->TooltipValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";

			// labour_fee
			$this->labour_fee->LinkCustomAttributes = "";
			$this->labour_fee->HrefValue = "";
			$this->labour_fee->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";
			$this->approver_date->TooltipValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";
			$this->approver_action->TooltipValue = "";

			// approver_comment
			$this->approver_comment->LinkCustomAttributes = "";
			$this->approver_comment->HrefValue = "";
			$this->approver_comment->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";
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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("gen_maintenancelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($gen_maintenance_delete)) $gen_maintenance_delete = new cgen_maintenance_delete();

// Page init
$gen_maintenance_delete->Page_Init();

// Page main
$gen_maintenance_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gen_maintenance_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fgen_maintenancedelete = new ew_Form("fgen_maintenancedelete", "delete");

// Form_CustomValidate event
fgen_maintenancedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgen_maintenancedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fgen_maintenancedelete.Lists["x_gen_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","x_kva",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fgen_maintenancedelete.Lists["x_gen_name"].Data = "<?php echo $gen_maintenance_delete->gen_name->LookupFilterQuery(FALSE, "delete") ?>";
fgen_maintenancedelete.Lists["x_maintenance_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintenance_type"};
fgen_maintenancedelete.Lists["x_maintenance_type"].Data = "<?php echo $gen_maintenance_delete->maintenance_type->LookupFilterQuery(FALSE, "delete") ?>";
fgen_maintenancedelete.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenancedelete.Lists["x_staff_id"].Data = "<?php echo $gen_maintenance_delete->staff_id->LookupFilterQuery(FALSE, "delete") ?>";
fgen_maintenancedelete.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_status"};
fgen_maintenancedelete.Lists["x_status"].Data = "<?php echo $gen_maintenance_delete->status->LookupFilterQuery(FALSE, "delete") ?>";
fgen_maintenancedelete.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenancedelete.Lists["x_initiator_action"].Options = <?php echo json_encode($gen_maintenance_delete->initiator_action->Options()) ?>;
fgen_maintenancedelete.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenancedelete.Lists["x_approver_action"].Options = <?php echo json_encode($gen_maintenance_delete->approver_action->Options()) ?>;
fgen_maintenancedelete.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenancedelete.Lists["x_approved_by"].Data = "<?php echo $gen_maintenance_delete->approved_by->LookupFilterQuery(FALSE, "delete") ?>";
fgen_maintenancedelete.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $gen_maintenance_delete->approved_by->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $gen_maintenance_delete->ShowPageHeader(); ?>
<?php
$gen_maintenance_delete->ShowMessage();
?>
<form name="fgen_maintenancedelete" id="fgen_maintenancedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gen_maintenance_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gen_maintenance_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gen_maintenance">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($gen_maintenance_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($gen_maintenance->id->Visible) { // id ?>
		<th class="<?php echo $gen_maintenance->id->HeaderCellClass() ?>"><span id="elh_gen_maintenance_id" class="gen_maintenance_id"><?php echo $gen_maintenance->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->datetime->Visible) { // datetime ?>
		<th class="<?php echo $gen_maintenance->datetime->HeaderCellClass() ?>"><span id="elh_gen_maintenance_datetime" class="gen_maintenance_datetime"><?php echo $gen_maintenance->datetime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->gen_name->Visible) { // gen_name ?>
		<th class="<?php echo $gen_maintenance->gen_name->HeaderCellClass() ?>"><span id="elh_gen_maintenance_gen_name" class="gen_maintenance_gen_name"><?php echo $gen_maintenance->gen_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->maintenance_type->Visible) { // maintenance_type ?>
		<th class="<?php echo $gen_maintenance->maintenance_type->HeaderCellClass() ?>"><span id="elh_gen_maintenance_maintenance_type" class="gen_maintenance_maintenance_type"><?php echo $gen_maintenance->maintenance_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->running_hours->Visible) { // running_hours ?>
		<th class="<?php echo $gen_maintenance->running_hours->HeaderCellClass() ?>"><span id="elh_gen_maintenance_running_hours" class="gen_maintenance_running_hours"><?php echo $gen_maintenance->running_hours->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->cost->Visible) { // cost ?>
		<th class="<?php echo $gen_maintenance->cost->HeaderCellClass() ?>"><span id="elh_gen_maintenance_cost" class="gen_maintenance_cost"><?php echo $gen_maintenance->cost->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->labour_fee->Visible) { // labour_fee ?>
		<th class="<?php echo $gen_maintenance->labour_fee->HeaderCellClass() ?>"><span id="elh_gen_maintenance_labour_fee" class="gen_maintenance_labour_fee"><?php echo $gen_maintenance->labour_fee->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->total->Visible) { // total ?>
		<th class="<?php echo $gen_maintenance->total->HeaderCellClass() ?>"><span id="elh_gen_maintenance_total" class="gen_maintenance_total"><?php echo $gen_maintenance->total->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->staff_id->Visible) { // staff_id ?>
		<th class="<?php echo $gen_maintenance->staff_id->HeaderCellClass() ?>"><span id="elh_gen_maintenance_staff_id" class="gen_maintenance_staff_id"><?php echo $gen_maintenance->staff_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->status->Visible) { // status ?>
		<th class="<?php echo $gen_maintenance->status->HeaderCellClass() ?>"><span id="elh_gen_maintenance_status" class="gen_maintenance_status"><?php echo $gen_maintenance->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->initiator_action->Visible) { // initiator_action ?>
		<th class="<?php echo $gen_maintenance->initiator_action->HeaderCellClass() ?>"><span id="elh_gen_maintenance_initiator_action" class="gen_maintenance_initiator_action"><?php echo $gen_maintenance->initiator_action->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->initiator_comment->Visible) { // initiator_comment ?>
		<th class="<?php echo $gen_maintenance->initiator_comment->HeaderCellClass() ?>"><span id="elh_gen_maintenance_initiator_comment" class="gen_maintenance_initiator_comment"><?php echo $gen_maintenance->initiator_comment->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->approver_date->Visible) { // approver_date ?>
		<th class="<?php echo $gen_maintenance->approver_date->HeaderCellClass() ?>"><span id="elh_gen_maintenance_approver_date" class="gen_maintenance_approver_date"><?php echo $gen_maintenance->approver_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->approver_action->Visible) { // approver_action ?>
		<th class="<?php echo $gen_maintenance->approver_action->HeaderCellClass() ?>"><span id="elh_gen_maintenance_approver_action" class="gen_maintenance_approver_action"><?php echo $gen_maintenance->approver_action->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->approver_comment->Visible) { // approver_comment ?>
		<th class="<?php echo $gen_maintenance->approver_comment->HeaderCellClass() ?>"><span id="elh_gen_maintenance_approver_comment" class="gen_maintenance_approver_comment"><?php echo $gen_maintenance->approver_comment->FldCaption() ?></span></th>
<?php } ?>
<?php if ($gen_maintenance->approved_by->Visible) { // approved_by ?>
		<th class="<?php echo $gen_maintenance->approved_by->HeaderCellClass() ?>"><span id="elh_gen_maintenance_approved_by" class="gen_maintenance_approved_by"><?php echo $gen_maintenance->approved_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$gen_maintenance_delete->RecCnt = 0;
$i = 0;
while (!$gen_maintenance_delete->Recordset->EOF) {
	$gen_maintenance_delete->RecCnt++;
	$gen_maintenance_delete->RowCnt++;

	// Set row properties
	$gen_maintenance->ResetAttrs();
	$gen_maintenance->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$gen_maintenance_delete->LoadRowValues($gen_maintenance_delete->Recordset);

	// Render row
	$gen_maintenance_delete->RenderRow();
?>
	<tr<?php echo $gen_maintenance->RowAttributes() ?>>
<?php if ($gen_maintenance->id->Visible) { // id ?>
		<td<?php echo $gen_maintenance->id->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_id" class="gen_maintenance_id">
<span<?php echo $gen_maintenance->id->ViewAttributes() ?>>
<?php echo $gen_maintenance->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->datetime->Visible) { // datetime ?>
		<td<?php echo $gen_maintenance->datetime->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_datetime" class="gen_maintenance_datetime">
<span<?php echo $gen_maintenance->datetime->ViewAttributes() ?>>
<?php echo $gen_maintenance->datetime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->gen_name->Visible) { // gen_name ?>
		<td<?php echo $gen_maintenance->gen_name->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_gen_name" class="gen_maintenance_gen_name">
<span<?php echo $gen_maintenance->gen_name->ViewAttributes() ?>>
<?php echo $gen_maintenance->gen_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->maintenance_type->Visible) { // maintenance_type ?>
		<td<?php echo $gen_maintenance->maintenance_type->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_maintenance_type" class="gen_maintenance_maintenance_type">
<span<?php echo $gen_maintenance->maintenance_type->ViewAttributes() ?>>
<?php echo $gen_maintenance->maintenance_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->running_hours->Visible) { // running_hours ?>
		<td<?php echo $gen_maintenance->running_hours->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_running_hours" class="gen_maintenance_running_hours">
<span<?php echo $gen_maintenance->running_hours->ViewAttributes() ?>>
<?php echo $gen_maintenance->running_hours->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->cost->Visible) { // cost ?>
		<td<?php echo $gen_maintenance->cost->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_cost" class="gen_maintenance_cost">
<span<?php echo $gen_maintenance->cost->ViewAttributes() ?>>
<?php echo $gen_maintenance->cost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->labour_fee->Visible) { // labour_fee ?>
		<td<?php echo $gen_maintenance->labour_fee->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_labour_fee" class="gen_maintenance_labour_fee">
<span<?php echo $gen_maintenance->labour_fee->ViewAttributes() ?>>
<?php echo $gen_maintenance->labour_fee->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->total->Visible) { // total ?>
		<td<?php echo $gen_maintenance->total->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_total" class="gen_maintenance_total">
<span<?php echo $gen_maintenance->total->ViewAttributes() ?>>
<?php echo $gen_maintenance->total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->staff_id->Visible) { // staff_id ?>
		<td<?php echo $gen_maintenance->staff_id->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_staff_id" class="gen_maintenance_staff_id">
<span<?php echo $gen_maintenance->staff_id->ViewAttributes() ?>>
<?php echo $gen_maintenance->staff_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->status->Visible) { // status ?>
		<td<?php echo $gen_maintenance->status->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_status" class="gen_maintenance_status">
<span<?php echo $gen_maintenance->status->ViewAttributes() ?>>
<?php echo $gen_maintenance->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->initiator_action->Visible) { // initiator_action ?>
		<td<?php echo $gen_maintenance->initiator_action->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_initiator_action" class="gen_maintenance_initiator_action">
<span<?php echo $gen_maintenance->initiator_action->ViewAttributes() ?>>
<?php echo $gen_maintenance->initiator_action->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->initiator_comment->Visible) { // initiator_comment ?>
		<td<?php echo $gen_maintenance->initiator_comment->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_initiator_comment" class="gen_maintenance_initiator_comment">
<span<?php echo $gen_maintenance->initiator_comment->ViewAttributes() ?>>
<?php echo $gen_maintenance->initiator_comment->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->approver_date->Visible) { // approver_date ?>
		<td<?php echo $gen_maintenance->approver_date->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_approver_date" class="gen_maintenance_approver_date">
<span<?php echo $gen_maintenance->approver_date->ViewAttributes() ?>>
<?php echo $gen_maintenance->approver_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->approver_action->Visible) { // approver_action ?>
		<td<?php echo $gen_maintenance->approver_action->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_approver_action" class="gen_maintenance_approver_action">
<span<?php echo $gen_maintenance->approver_action->ViewAttributes() ?>>
<?php echo $gen_maintenance->approver_action->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->approver_comment->Visible) { // approver_comment ?>
		<td<?php echo $gen_maintenance->approver_comment->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_approver_comment" class="gen_maintenance_approver_comment">
<span<?php echo $gen_maintenance->approver_comment->ViewAttributes() ?>>
<?php echo $gen_maintenance->approver_comment->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gen_maintenance->approved_by->Visible) { // approved_by ?>
		<td<?php echo $gen_maintenance->approved_by->CellAttributes() ?>>
<span id="el<?php echo $gen_maintenance_delete->RowCnt ?>_gen_maintenance_approved_by" class="gen_maintenance_approved_by">
<span<?php echo $gen_maintenance->approved_by->ViewAttributes() ?>>
<?php echo $gen_maintenance->approved_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$gen_maintenance_delete->Recordset->MoveNext();
}
$gen_maintenance_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $gen_maintenance_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fgen_maintenancedelete.Init();
</script>
<?php
$gen_maintenance_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gen_maintenance_delete->Page_Terminate();
?>
