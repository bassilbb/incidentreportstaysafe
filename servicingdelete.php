<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "servicinginfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$servicing_delete = NULL; // Initialize page object first

class cservicing_delete extends cservicing {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'servicing';

	// Page object name
	var $PageObjName = 'servicing_delete';

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

		// Table object (servicing)
		if (!isset($GLOBALS["servicing"]) || get_class($GLOBALS["servicing"]) == "cservicing") {
			$GLOBALS["servicing"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["servicing"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'servicing', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("servicinglist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->date_initiated->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->staff_name->SetVisibility();
		$this->department->SetVisibility();
		$this->branch->SetVisibility();
		$this->buildings->SetVisibility();
		$this->floors->SetVisibility();
		$this->items->SetVisibility();
		$this->priority->SetVisibility();
		$this->description->SetVisibility();
		$this->status->SetVisibility();
		$this->date_maintained->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->maintained_by->SetVisibility();
		$this->reviewed_date->SetVisibility();
		$this->reviewed_action->SetVisibility();
		$this->reviewed_by->SetVisibility();
		$this->staff_no->SetVisibility();

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
		global $EW_EXPORT, $servicing;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($servicing);
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
			$this->Page_Terminate("servicinglist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in servicing class, servicinginfo.php

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
				$this->Page_Terminate("servicinglist.php"); // Return to list
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
		$this->reference_id->setDbValue($row['reference_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->staff_name->setDbValue($row['staff_name']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->buildings->setDbValue($row['buildings']);
		$this->floors->setDbValue($row['floors']);
		$this->items->setDbValue($row['items']);
		$this->priority->setDbValue($row['priority']);
		$this->description->setDbValue($row['description']);
		$this->status->setDbValue($row['status']);
		$this->date_maintained->setDbValue($row['date_maintained']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->maintained_by->setDbValue($row['maintained_by']);
		$this->reviewed_date->setDbValue($row['reviewed_date']);
		$this->reviewed_action->setDbValue($row['reviewed_action']);
		$this->reviewed_comment->setDbValue($row['reviewed_comment']);
		$this->reviewed_by->setDbValue($row['reviewed_by']);
		$this->staff_no->setDbValue($row['staff_no']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['staff_name'] = NULL;
		$row['department'] = NULL;
		$row['branch'] = NULL;
		$row['buildings'] = NULL;
		$row['floors'] = NULL;
		$row['items'] = NULL;
		$row['priority'] = NULL;
		$row['description'] = NULL;
		$row['status'] = NULL;
		$row['date_maintained'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['maintained_by'] = NULL;
		$row['reviewed_date'] = NULL;
		$row['reviewed_action'] = NULL;
		$row['reviewed_comment'] = NULL;
		$row['reviewed_by'] = NULL;
		$row['staff_no'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->staff_name->DbValue = $row['staff_name'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->buildings->DbValue = $row['buildings'];
		$this->floors->DbValue = $row['floors'];
		$this->items->DbValue = $row['items'];
		$this->priority->DbValue = $row['priority'];
		$this->description->DbValue = $row['description'];
		$this->status->DbValue = $row['status'];
		$this->date_maintained->DbValue = $row['date_maintained'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->maintained_by->DbValue = $row['maintained_by'];
		$this->reviewed_date->DbValue = $row['reviewed_date'];
		$this->reviewed_action->DbValue = $row['reviewed_action'];
		$this->reviewed_comment->DbValue = $row['reviewed_comment'];
		$this->reviewed_by->DbValue = $row['reviewed_by'];
		$this->staff_no->DbValue = $row['staff_no'];
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
		// reference_id
		// staff_id
		// staff_name
		// department
		// branch
		// buildings
		// floors
		// items
		// priority
		// description
		// status
		// date_maintained
		// initiator_action
		// initiator_comment
		// maintained_by
		// reviewed_date
		// reviewed_action
		// reviewed_comment
		// reviewed_by
		// staff_no

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

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

		// staff_name
		$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
		if (strval($this->staff_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_name->ViewValue = $this->staff_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
			}
		} else {
			$this->staff_name->ViewValue = NULL;
		}
		$this->staff_name->ViewCustomAttributes = "";

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

		// branch
		$this->branch->ViewValue = $this->branch->CurrentValue;
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->branch->ViewValue = $this->branch->CurrentValue;
			}
		} else {
			$this->branch->ViewValue = NULL;
		}
		$this->branch->ViewCustomAttributes = "";

		// buildings
		if (strval($this->buildings->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->buildings->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `buildings`";
		$sWhereWrk = "";
		$this->buildings->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->buildings->ViewValue = $this->buildings->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->buildings->ViewValue = $this->buildings->CurrentValue;
			}
		} else {
			$this->buildings->ViewValue = NULL;
		}
		$this->buildings->ViewCustomAttributes = "";

		// floors
		if (strval($this->floors->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->floors->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `floors`";
		$sWhereWrk = "";
		$this->floors->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->floors->ViewValue = $this->floors->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->floors->ViewValue = $this->floors->CurrentValue;
			}
		} else {
			$this->floors->ViewValue = NULL;
		}
		$this->floors->ViewCustomAttributes = "";

		// items
		if (strval($this->items->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->items->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
		$sWhereWrk = "";
		$this->items->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->items->ViewValue = $this->items->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->items->ViewValue = $this->items->CurrentValue;
			}
		} else {
			$this->items->ViewValue = NULL;
		}
		$this->items->ViewCustomAttributes = "";

		// priority
		if (strval($this->priority->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->priority->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->priority->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->priority, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->priority->ViewValue = $this->priority->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->priority->ViewValue = $this->priority->CurrentValue;
			}
		} else {
			$this->priority->ViewValue = NULL;
		}
		$this->priority->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `service_status`";
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

		// date_maintained
		$this->date_maintained->ViewValue = $this->date_maintained->CurrentValue;
		$this->date_maintained->ViewValue = ew_FormatDateTime($this->date_maintained->ViewValue, 17);
		$this->date_maintained->ViewCustomAttributes = "";

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

		// maintained_by
		$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
		if (strval($this->maintained_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintained_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->maintained_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->maintained_by->ViewValue = $this->maintained_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
			}
		} else {
			$this->maintained_by->ViewValue = NULL;
		}
		$this->maintained_by->ViewCustomAttributes = "";

		// reviewed_date
		$this->reviewed_date->ViewValue = $this->reviewed_date->CurrentValue;
		$this->reviewed_date->ViewValue = ew_FormatDateTime($this->reviewed_date->ViewValue, 17);
		$this->reviewed_date->ViewCustomAttributes = "";

		// reviewed_action
		if (strval($this->reviewed_action->CurrentValue) <> "") {
			$this->reviewed_action->ViewValue = $this->reviewed_action->OptionCaption($this->reviewed_action->CurrentValue);
		} else {
			$this->reviewed_action->ViewValue = NULL;
		}
		$this->reviewed_action->ViewCustomAttributes = "";

		// reviewed_by
		$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
		if (strval($this->reviewed_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reviewed_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->reviewed_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->reviewed_by->ViewValue = $this->reviewed_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
			}
		} else {
			$this->reviewed_by->ViewValue = NULL;
		}
		$this->reviewed_by->ViewCustomAttributes = "";

		// staff_no
		$this->staff_no->ViewValue = $this->staff_no->CurrentValue;
		$this->staff_no->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// staff_name
			$this->staff_name->LinkCustomAttributes = "";
			$this->staff_name->HrefValue = "";
			$this->staff_name->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// buildings
			$this->buildings->LinkCustomAttributes = "";
			$this->buildings->HrefValue = "";
			$this->buildings->TooltipValue = "";

			// floors
			$this->floors->LinkCustomAttributes = "";
			$this->floors->HrefValue = "";
			$this->floors->TooltipValue = "";

			// items
			$this->items->LinkCustomAttributes = "";
			$this->items->HrefValue = "";
			$this->items->TooltipValue = "";

			// priority
			$this->priority->LinkCustomAttributes = "";
			$this->priority->HrefValue = "";
			$this->priority->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// date_maintained
			$this->date_maintained->LinkCustomAttributes = "";
			$this->date_maintained->HrefValue = "";
			$this->date_maintained->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// maintained_by
			$this->maintained_by->LinkCustomAttributes = "";
			$this->maintained_by->HrefValue = "";
			$this->maintained_by->TooltipValue = "";

			// reviewed_date
			$this->reviewed_date->LinkCustomAttributes = "";
			$this->reviewed_date->HrefValue = "";
			$this->reviewed_date->TooltipValue = "";

			// reviewed_action
			$this->reviewed_action->LinkCustomAttributes = "";
			$this->reviewed_action->HrefValue = "";
			$this->reviewed_action->TooltipValue = "";

			// reviewed_by
			$this->reviewed_by->LinkCustomAttributes = "";
			$this->reviewed_by->HrefValue = "";
			$this->reviewed_by->TooltipValue = "";

			// staff_no
			$this->staff_no->LinkCustomAttributes = "";
			$this->staff_no->HrefValue = "";
			$this->staff_no->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("servicinglist.php"), "", $this->TableVar, TRUE);
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
if (!isset($servicing_delete)) $servicing_delete = new cservicing_delete();

// Page init
$servicing_delete->Page_Init();

// Page main
$servicing_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicing_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fservicingdelete = new ew_Form("fservicingdelete", "delete");

// Form_CustomValidate event
fservicingdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fservicingdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fservicingdelete.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingdelete.Lists["x_staff_id"].Data = "<?php echo $servicing_delete->staff_id->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_delete->staff_id->LookupFilterQuery(TRUE, "delete"))) ?>;
fservicingdelete.Lists["x_staff_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingdelete.Lists["x_staff_name"].Data = "<?php echo $servicing_delete->staff_name->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.AutoSuggests["x_staff_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_delete->staff_name->LookupFilterQuery(TRUE, "delete"))) ?>;
fservicingdelete.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fservicingdelete.Lists["x_department"].Data = "<?php echo $servicing_delete->department->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_delete->department->LookupFilterQuery(TRUE, "delete"))) ?>;
fservicingdelete.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
fservicingdelete.Lists["x_branch"].Data = "<?php echo $servicing_delete->branch->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.AutoSuggests["x_branch"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_delete->branch->LookupFilterQuery(TRUE, "delete"))) ?>;
fservicingdelete.Lists["x_buildings"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"buildings"};
fservicingdelete.Lists["x_buildings"].Data = "<?php echo $servicing_delete->buildings->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.Lists["x_floors"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"floors"};
fservicingdelete.Lists["x_floors"].Data = "<?php echo $servicing_delete->floors->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.Lists["x_items"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"items"};
fservicingdelete.Lists["x_items"].Data = "<?php echo $servicing_delete->items->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.Lists["x_priority"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
fservicingdelete.Lists["x_priority"].Data = "<?php echo $servicing_delete->priority->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"service_status"};
fservicingdelete.Lists["x_status"].Data = "<?php echo $servicing_delete->status->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicingdelete.Lists["x_initiator_action"].Options = <?php echo json_encode($servicing_delete->initiator_action->Options()) ?>;
fservicingdelete.Lists["x_maintained_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingdelete.Lists["x_maintained_by"].Data = "<?php echo $servicing_delete->maintained_by->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.AutoSuggests["x_maintained_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_delete->maintained_by->LookupFilterQuery(TRUE, "delete"))) ?>;
fservicingdelete.Lists["x_reviewed_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicingdelete.Lists["x_reviewed_action"].Options = <?php echo json_encode($servicing_delete->reviewed_action->Options()) ?>;
fservicingdelete.Lists["x_reviewed_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingdelete.Lists["x_reviewed_by"].Data = "<?php echo $servicing_delete->reviewed_by->LookupFilterQuery(FALSE, "delete") ?>";
fservicingdelete.AutoSuggests["x_reviewed_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_delete->reviewed_by->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $servicing_delete->ShowPageHeader(); ?>
<?php
$servicing_delete->ShowMessage();
?>
<form name="fservicingdelete" id="fservicingdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($servicing_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $servicing_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="servicing">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($servicing_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($servicing->date_initiated->Visible) { // date_initiated ?>
		<th class="<?php echo $servicing->date_initiated->HeaderCellClass() ?>"><span id="elh_servicing_date_initiated" class="servicing_date_initiated"><?php echo $servicing->date_initiated->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->reference_id->Visible) { // reference_id ?>
		<th class="<?php echo $servicing->reference_id->HeaderCellClass() ?>"><span id="elh_servicing_reference_id" class="servicing_reference_id"><?php echo $servicing->reference_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->staff_id->Visible) { // staff_id ?>
		<th class="<?php echo $servicing->staff_id->HeaderCellClass() ?>"><span id="elh_servicing_staff_id" class="servicing_staff_id"><?php echo $servicing->staff_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->staff_name->Visible) { // staff_name ?>
		<th class="<?php echo $servicing->staff_name->HeaderCellClass() ?>"><span id="elh_servicing_staff_name" class="servicing_staff_name"><?php echo $servicing->staff_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->department->Visible) { // department ?>
		<th class="<?php echo $servicing->department->HeaderCellClass() ?>"><span id="elh_servicing_department" class="servicing_department"><?php echo $servicing->department->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->branch->Visible) { // branch ?>
		<th class="<?php echo $servicing->branch->HeaderCellClass() ?>"><span id="elh_servicing_branch" class="servicing_branch"><?php echo $servicing->branch->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->buildings->Visible) { // buildings ?>
		<th class="<?php echo $servicing->buildings->HeaderCellClass() ?>"><span id="elh_servicing_buildings" class="servicing_buildings"><?php echo $servicing->buildings->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->floors->Visible) { // floors ?>
		<th class="<?php echo $servicing->floors->HeaderCellClass() ?>"><span id="elh_servicing_floors" class="servicing_floors"><?php echo $servicing->floors->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->items->Visible) { // items ?>
		<th class="<?php echo $servicing->items->HeaderCellClass() ?>"><span id="elh_servicing_items" class="servicing_items"><?php echo $servicing->items->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->priority->Visible) { // priority ?>
		<th class="<?php echo $servicing->priority->HeaderCellClass() ?>"><span id="elh_servicing_priority" class="servicing_priority"><?php echo $servicing->priority->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->description->Visible) { // description ?>
		<th class="<?php echo $servicing->description->HeaderCellClass() ?>"><span id="elh_servicing_description" class="servicing_description"><?php echo $servicing->description->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->status->Visible) { // status ?>
		<th class="<?php echo $servicing->status->HeaderCellClass() ?>"><span id="elh_servicing_status" class="servicing_status"><?php echo $servicing->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->date_maintained->Visible) { // date_maintained ?>
		<th class="<?php echo $servicing->date_maintained->HeaderCellClass() ?>"><span id="elh_servicing_date_maintained" class="servicing_date_maintained"><?php echo $servicing->date_maintained->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->initiator_action->Visible) { // initiator_action ?>
		<th class="<?php echo $servicing->initiator_action->HeaderCellClass() ?>"><span id="elh_servicing_initiator_action" class="servicing_initiator_action"><?php echo $servicing->initiator_action->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->maintained_by->Visible) { // maintained_by ?>
		<th class="<?php echo $servicing->maintained_by->HeaderCellClass() ?>"><span id="elh_servicing_maintained_by" class="servicing_maintained_by"><?php echo $servicing->maintained_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->reviewed_date->Visible) { // reviewed_date ?>
		<th class="<?php echo $servicing->reviewed_date->HeaderCellClass() ?>"><span id="elh_servicing_reviewed_date" class="servicing_reviewed_date"><?php echo $servicing->reviewed_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->reviewed_action->Visible) { // reviewed_action ?>
		<th class="<?php echo $servicing->reviewed_action->HeaderCellClass() ?>"><span id="elh_servicing_reviewed_action" class="servicing_reviewed_action"><?php echo $servicing->reviewed_action->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->reviewed_by->Visible) { // reviewed_by ?>
		<th class="<?php echo $servicing->reviewed_by->HeaderCellClass() ?>"><span id="elh_servicing_reviewed_by" class="servicing_reviewed_by"><?php echo $servicing->reviewed_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($servicing->staff_no->Visible) { // staff_no ?>
		<th class="<?php echo $servicing->staff_no->HeaderCellClass() ?>"><span id="elh_servicing_staff_no" class="servicing_staff_no"><?php echo $servicing->staff_no->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$servicing_delete->RecCnt = 0;
$i = 0;
while (!$servicing_delete->Recordset->EOF) {
	$servicing_delete->RecCnt++;
	$servicing_delete->RowCnt++;

	// Set row properties
	$servicing->ResetAttrs();
	$servicing->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$servicing_delete->LoadRowValues($servicing_delete->Recordset);

	// Render row
	$servicing_delete->RenderRow();
?>
	<tr<?php echo $servicing->RowAttributes() ?>>
<?php if ($servicing->date_initiated->Visible) { // date_initiated ?>
		<td<?php echo $servicing->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_date_initiated" class="servicing_date_initiated">
<span<?php echo $servicing->date_initiated->ViewAttributes() ?>>
<?php echo $servicing->date_initiated->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->reference_id->Visible) { // reference_id ?>
		<td<?php echo $servicing->reference_id->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_reference_id" class="servicing_reference_id">
<span<?php echo $servicing->reference_id->ViewAttributes() ?>>
<?php echo $servicing->reference_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->staff_id->Visible) { // staff_id ?>
		<td<?php echo $servicing->staff_id->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_staff_id" class="servicing_staff_id">
<span<?php echo $servicing->staff_id->ViewAttributes() ?>>
<?php echo $servicing->staff_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->staff_name->Visible) { // staff_name ?>
		<td<?php echo $servicing->staff_name->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_staff_name" class="servicing_staff_name">
<span<?php echo $servicing->staff_name->ViewAttributes() ?>>
<?php echo $servicing->staff_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->department->Visible) { // department ?>
		<td<?php echo $servicing->department->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_department" class="servicing_department">
<span<?php echo $servicing->department->ViewAttributes() ?>>
<?php echo $servicing->department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->branch->Visible) { // branch ?>
		<td<?php echo $servicing->branch->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_branch" class="servicing_branch">
<span<?php echo $servicing->branch->ViewAttributes() ?>>
<?php echo $servicing->branch->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->buildings->Visible) { // buildings ?>
		<td<?php echo $servicing->buildings->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_buildings" class="servicing_buildings">
<span<?php echo $servicing->buildings->ViewAttributes() ?>>
<?php echo $servicing->buildings->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->floors->Visible) { // floors ?>
		<td<?php echo $servicing->floors->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_floors" class="servicing_floors">
<span<?php echo $servicing->floors->ViewAttributes() ?>>
<?php echo $servicing->floors->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->items->Visible) { // items ?>
		<td<?php echo $servicing->items->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_items" class="servicing_items">
<span<?php echo $servicing->items->ViewAttributes() ?>>
<?php echo $servicing->items->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->priority->Visible) { // priority ?>
		<td<?php echo $servicing->priority->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_priority" class="servicing_priority">
<span<?php echo $servicing->priority->ViewAttributes() ?>>
<?php echo $servicing->priority->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->description->Visible) { // description ?>
		<td<?php echo $servicing->description->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_description" class="servicing_description">
<span<?php echo $servicing->description->ViewAttributes() ?>>
<?php echo $servicing->description->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->status->Visible) { // status ?>
		<td<?php echo $servicing->status->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_status" class="servicing_status">
<span<?php echo $servicing->status->ViewAttributes() ?>>
<?php echo $servicing->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->date_maintained->Visible) { // date_maintained ?>
		<td<?php echo $servicing->date_maintained->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_date_maintained" class="servicing_date_maintained">
<span<?php echo $servicing->date_maintained->ViewAttributes() ?>>
<?php echo $servicing->date_maintained->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->initiator_action->Visible) { // initiator_action ?>
		<td<?php echo $servicing->initiator_action->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_initiator_action" class="servicing_initiator_action">
<span<?php echo $servicing->initiator_action->ViewAttributes() ?>>
<?php echo $servicing->initiator_action->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->maintained_by->Visible) { // maintained_by ?>
		<td<?php echo $servicing->maintained_by->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_maintained_by" class="servicing_maintained_by">
<span<?php echo $servicing->maintained_by->ViewAttributes() ?>>
<?php echo $servicing->maintained_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->reviewed_date->Visible) { // reviewed_date ?>
		<td<?php echo $servicing->reviewed_date->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_reviewed_date" class="servicing_reviewed_date">
<span<?php echo $servicing->reviewed_date->ViewAttributes() ?>>
<?php echo $servicing->reviewed_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->reviewed_action->Visible) { // reviewed_action ?>
		<td<?php echo $servicing->reviewed_action->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_reviewed_action" class="servicing_reviewed_action">
<span<?php echo $servicing->reviewed_action->ViewAttributes() ?>>
<?php echo $servicing->reviewed_action->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->reviewed_by->Visible) { // reviewed_by ?>
		<td<?php echo $servicing->reviewed_by->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_reviewed_by" class="servicing_reviewed_by">
<span<?php echo $servicing->reviewed_by->ViewAttributes() ?>>
<?php echo $servicing->reviewed_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($servicing->staff_no->Visible) { // staff_no ?>
		<td<?php echo $servicing->staff_no->CellAttributes() ?>>
<span id="el<?php echo $servicing_delete->RowCnt ?>_servicing_staff_no" class="servicing_staff_no">
<span<?php echo $servicing->staff_no->ViewAttributes() ?>>
<?php echo $servicing->staff_no->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$servicing_delete->Recordset->MoveNext();
}
$servicing_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $servicing_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fservicingdelete.Init();
</script>
<?php
$servicing_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$servicing_delete->Page_Terminate();
?>
