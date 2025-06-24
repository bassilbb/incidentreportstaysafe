<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "inventoryinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$inventory_delete = NULL; // Initialize page object first

class cinventory_delete extends cinventory {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'inventory';

	// Page object name
	var $PageObjName = 'inventory_delete';

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

		// Table object (inventory)
		if (!isset($GLOBALS["inventory"]) || get_class($GLOBALS["inventory"]) == "cinventory") {
			$GLOBALS["inventory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventory"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory');

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
				$this->Page_Terminate(ew_GetUrl("inventorylist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->date_recieved->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity->SetVisibility();
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->recieved_by->SetVisibility();
		$this->statuss->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->verified_by->SetVisibility();

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
		global $EW_EXPORT, $inventory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventory);
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
			$this->Page_Terminate("inventorylist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in inventory class, inventoryinfo.php

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
				$this->Page_Terminate("inventorylist.php"); // Return to list
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
		$this->date_recieved->setDbValue($row['date_recieved']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity->setDbValue($row['quantity']);
		$this->type->setDbValue($row['type']);
		$this->capacity->setDbValue($row['capacity']);
		$this->recieved_by->setDbValue($row['recieved_by']);
		$this->statuss->setDbValue($row['statuss']);
		$this->recieved_action->setDbValue($row['recieved_action']);
		$this->recieved_comment->setDbValue($row['recieved_comment']);
		$this->date_approved->setDbValue($row['date_approved']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_date->setDbValue($row['verified_date']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->verified_by->setDbValue($row['verified_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_recieved'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['material_name'] = NULL;
		$row['quantity'] = NULL;
		$row['type'] = NULL;
		$row['capacity'] = NULL;
		$row['recieved_by'] = NULL;
		$row['statuss'] = NULL;
		$row['recieved_action'] = NULL;
		$row['recieved_comment'] = NULL;
		$row['date_approved'] = NULL;
		$row['approver_action'] = NULL;
		$row['approver_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_date'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['verified_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_recieved->DbValue = $row['date_recieved'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity->DbValue = $row['quantity'];
		$this->type->DbValue = $row['type'];
		$this->capacity->DbValue = $row['capacity'];
		$this->recieved_by->DbValue = $row['recieved_by'];
		$this->statuss->DbValue = $row['statuss'];
		$this->recieved_action->DbValue = $row['recieved_action'];
		$this->recieved_comment->DbValue = $row['recieved_comment'];
		$this->date_approved->DbValue = $row['date_approved'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_date->DbValue = $row['verified_date'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date_recieved
		// reference_id
		// staff_id
		// material_name
		// quantity
		// type
		// capacity
		// recieved_by
		// statuss
		// recieved_action
		// recieved_comment
		// date_approved
		// approver_action
		// approver_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->ViewValue = $this->date_recieved->CurrentValue;
		$this->date_recieved->ViewValue = ew_FormatDateTime($this->date_recieved->ViewValue, 17);
		$this->date_recieved->ViewCustomAttributes = "";

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

		// material_name
		$this->material_name->ViewValue = $this->material_name->CurrentValue;
		$this->material_name->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// recieved_by
		$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
		if (strval($this->recieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recieved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

		// statuss
		if (strval($this->statuss->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
		$sWhereWrk = "";
		$this->statuss->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->statuss->ViewValue = $this->statuss->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->statuss->ViewValue = $this->statuss->CurrentValue;
			}
		} else {
			$this->statuss->ViewValue = NULL;
		}
		$this->statuss->ViewCustomAttributes = "";

		// recieved_action
		if (strval($this->recieved_action->CurrentValue) <> "") {
			$this->recieved_action->ViewValue = $this->recieved_action->OptionCaption($this->recieved_action->CurrentValue);
		} else {
			$this->recieved_action->ViewValue = NULL;
		}
		$this->recieved_action->ViewCustomAttributes = "";

		// recieved_comment
		$this->recieved_comment->ViewValue = $this->recieved_comment->CurrentValue;
		$this->recieved_comment->ViewCustomAttributes = "";

		// date_approved
		$this->date_approved->ViewValue = $this->date_approved->CurrentValue;
		$this->date_approved->ViewValue = ew_FormatDateTime($this->date_approved->ViewValue, 17);
		$this->date_approved->ViewCustomAttributes = "";

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
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

		// verified_date
		$this->verified_date->ViewValue = $this->verified_date->CurrentValue;
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 17);
		$this->verified_date->ViewCustomAttributes = "";

		// verified_action
		if (strval($this->verified_action->CurrentValue) <> "") {
			$this->verified_action->ViewValue = $this->verified_action->OptionCaption($this->verified_action->CurrentValue);
		} else {
			$this->verified_action->ViewValue = NULL;
		}
		$this->verified_action->ViewCustomAttributes = "";

		// verified_comment
		$this->verified_comment->ViewValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->ViewCustomAttributes = "";

		// verified_by
		$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
		if (strval($this->verified_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->verified_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->verified_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->verified_by->ViewValue = $this->verified_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
			}
		} else {
			$this->verified_by->ViewValue = NULL;
		}
		$this->verified_by->ViewCustomAttributes = "";

			// date_recieved
			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";
			$this->date_recieved->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";
			$this->capacity->TooltipValue = "";

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";
			$this->recieved_by->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
			$this->verified_by->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inventorylist.php"), "", $this->TableVar, TRUE);
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
if (!isset($inventory_delete)) $inventory_delete = new cinventory_delete();

// Page init
$inventory_delete->Page_Init();

// Page main
$inventory_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = finventorydelete = new ew_Form("finventorydelete", "delete");

// Form_CustomValidate event
finventorydelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventorydelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventorydelete.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventorydelete.Lists["x_recieved_by"].Data = "<?php echo $inventory_delete->recieved_by->LookupFilterQuery(FALSE, "delete") ?>";
finventorydelete.AutoSuggests["x_recieved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_delete->recieved_by->LookupFilterQuery(TRUE, "delete"))) ?>;
finventorydelete.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
finventorydelete.Lists["x_statuss"].Data = "<?php echo $inventory_delete->statuss->LookupFilterQuery(FALSE, "delete") ?>";
finventorydelete.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventorydelete.Lists["x_approved_by"].Data = "<?php echo $inventory_delete->approved_by->LookupFilterQuery(FALSE, "delete") ?>";
finventorydelete.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_delete->approved_by->LookupFilterQuery(TRUE, "delete"))) ?>;
finventorydelete.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventorydelete.Lists["x_verified_by"].Data = "<?php echo $inventory_delete->verified_by->LookupFilterQuery(FALSE, "delete") ?>";
finventorydelete.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_delete->verified_by->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $inventory_delete->ShowPageHeader(); ?>
<?php
$inventory_delete->ShowMessage();
?>
<form name="finventorydelete" id="finventorydelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($inventory_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($inventory->date_recieved->Visible) { // date_recieved ?>
		<th class="<?php echo $inventory->date_recieved->HeaderCellClass() ?>"><span id="elh_inventory_date_recieved" class="inventory_date_recieved"><?php echo $inventory->date_recieved->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->reference_id->Visible) { // reference_id ?>
		<th class="<?php echo $inventory->reference_id->HeaderCellClass() ?>"><span id="elh_inventory_reference_id" class="inventory_reference_id"><?php echo $inventory->reference_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->material_name->Visible) { // material_name ?>
		<th class="<?php echo $inventory->material_name->HeaderCellClass() ?>"><span id="elh_inventory_material_name" class="inventory_material_name"><?php echo $inventory->material_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->quantity->Visible) { // quantity ?>
		<th class="<?php echo $inventory->quantity->HeaderCellClass() ?>"><span id="elh_inventory_quantity" class="inventory_quantity"><?php echo $inventory->quantity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->type->Visible) { // type ?>
		<th class="<?php echo $inventory->type->HeaderCellClass() ?>"><span id="elh_inventory_type" class="inventory_type"><?php echo $inventory->type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->capacity->Visible) { // capacity ?>
		<th class="<?php echo $inventory->capacity->HeaderCellClass() ?>"><span id="elh_inventory_capacity" class="inventory_capacity"><?php echo $inventory->capacity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->recieved_by->Visible) { // recieved_by ?>
		<th class="<?php echo $inventory->recieved_by->HeaderCellClass() ?>"><span id="elh_inventory_recieved_by" class="inventory_recieved_by"><?php echo $inventory->recieved_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->statuss->Visible) { // statuss ?>
		<th class="<?php echo $inventory->statuss->HeaderCellClass() ?>"><span id="elh_inventory_statuss" class="inventory_statuss"><?php echo $inventory->statuss->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->approved_by->Visible) { // approved_by ?>
		<th class="<?php echo $inventory->approved_by->HeaderCellClass() ?>"><span id="elh_inventory_approved_by" class="inventory_approved_by"><?php echo $inventory->approved_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($inventory->verified_by->Visible) { // verified_by ?>
		<th class="<?php echo $inventory->verified_by->HeaderCellClass() ?>"><span id="elh_inventory_verified_by" class="inventory_verified_by"><?php echo $inventory->verified_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$inventory_delete->RecCnt = 0;
$i = 0;
while (!$inventory_delete->Recordset->EOF) {
	$inventory_delete->RecCnt++;
	$inventory_delete->RowCnt++;

	// Set row properties
	$inventory->ResetAttrs();
	$inventory->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$inventory_delete->LoadRowValues($inventory_delete->Recordset);

	// Render row
	$inventory_delete->RenderRow();
?>
	<tr<?php echo $inventory->RowAttributes() ?>>
<?php if ($inventory->date_recieved->Visible) { // date_recieved ?>
		<td<?php echo $inventory->date_recieved->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_date_recieved" class="inventory_date_recieved">
<span<?php echo $inventory->date_recieved->ViewAttributes() ?>>
<?php echo $inventory->date_recieved->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->reference_id->Visible) { // reference_id ?>
		<td<?php echo $inventory->reference_id->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_reference_id" class="inventory_reference_id">
<span<?php echo $inventory->reference_id->ViewAttributes() ?>>
<?php echo $inventory->reference_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->material_name->Visible) { // material_name ?>
		<td<?php echo $inventory->material_name->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_material_name" class="inventory_material_name">
<span<?php echo $inventory->material_name->ViewAttributes() ?>>
<?php echo $inventory->material_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->quantity->Visible) { // quantity ?>
		<td<?php echo $inventory->quantity->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_quantity" class="inventory_quantity">
<span<?php echo $inventory->quantity->ViewAttributes() ?>>
<?php echo $inventory->quantity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->type->Visible) { // type ?>
		<td<?php echo $inventory->type->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_type" class="inventory_type">
<span<?php echo $inventory->type->ViewAttributes() ?>>
<?php echo $inventory->type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->capacity->Visible) { // capacity ?>
		<td<?php echo $inventory->capacity->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_capacity" class="inventory_capacity">
<span<?php echo $inventory->capacity->ViewAttributes() ?>>
<?php echo $inventory->capacity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->recieved_by->Visible) { // recieved_by ?>
		<td<?php echo $inventory->recieved_by->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_recieved_by" class="inventory_recieved_by">
<span<?php echo $inventory->recieved_by->ViewAttributes() ?>>
<?php echo $inventory->recieved_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->statuss->Visible) { // statuss ?>
		<td<?php echo $inventory->statuss->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_statuss" class="inventory_statuss">
<span<?php echo $inventory->statuss->ViewAttributes() ?>>
<?php echo $inventory->statuss->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->approved_by->Visible) { // approved_by ?>
		<td<?php echo $inventory->approved_by->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_approved_by" class="inventory_approved_by">
<span<?php echo $inventory->approved_by->ViewAttributes() ?>>
<?php echo $inventory->approved_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($inventory->verified_by->Visible) { // verified_by ?>
		<td<?php echo $inventory->verified_by->CellAttributes() ?>>
<span id="el<?php echo $inventory_delete->RowCnt ?>_inventory_verified_by" class="inventory_verified_by">
<span<?php echo $inventory->verified_by->ViewAttributes() ?>>
<?php echo $inventory->verified_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$inventory_delete->Recordset->MoveNext();
}
$inventory_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inventory_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
finventorydelete.Init();
</script>
<?php
$inventory_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$inventory_delete->Page_Terminate();
?>
