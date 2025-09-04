<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "issuance_store_staysafeinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$issuance_store_staysafe_delete = NULL; // Initialize page object first

class cissuance_store_staysafe_delete extends cissuance_store_staysafe {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'issuance_store_staysafe';

	// Page object name
	var $PageObjName = 'issuance_store_staysafe_delete';

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

		// Table object (issuance_store_staysafe)
		if (!isset($GLOBALS["issuance_store_staysafe"]) || get_class($GLOBALS["issuance_store_staysafe"]) == "cissuance_store_staysafe") {
			$GLOBALS["issuance_store_staysafe"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["issuance_store_staysafe"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'issuance_store_staysafe');

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
				$this->Page_Terminate(ew_GetUrl("issuance_store_staysafelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_out->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->quantity_type->SetVisibility();
		$this->statuss->SetVisibility();
		$this->issued_by->SetVisibility();
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
		global $EW_EXPORT, $issuance_store_staysafe;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($issuance_store_staysafe);
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
			$this->Page_Terminate("issuance_store_staysafelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in issuance_store_staysafe class, issuance_store_staysafeinfo.php

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
				$this->Page_Terminate("issuance_store_staysafelist.php"); // Return to list
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
		$this->date->setDbValue($row['date']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity_in->setDbValue($row['quantity_in']);
		$this->quantity_out->setDbValue($row['quantity_out']);
		$this->total_quantity->setDbValue($row['total_quantity']);
		$this->quantity_type->setDbValue($row['quantity_type']);
		$this->treated_by->setDbValue($row['treated_by']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->statuss->setDbValue($row['statuss']);
		$this->issued_action->setDbValue($row['issued_action']);
		$this->issued_comment->setDbValue($row['issued_comment']);
		$this->issued_by->setDbValue($row['issued_by']);
		$this->approver_date->setDbValue($row['approver_date']);
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
		$row['date'] = NULL;
		$row['reference_id'] = NULL;
		$row['material_name'] = NULL;
		$row['quantity_in'] = NULL;
		$row['quantity_out'] = NULL;
		$row['total_quantity'] = NULL;
		$row['quantity_type'] = NULL;
		$row['treated_by'] = NULL;
		$row['staff_id'] = NULL;
		$row['statuss'] = NULL;
		$row['issued_action'] = NULL;
		$row['issued_comment'] = NULL;
		$row['issued_by'] = NULL;
		$row['approver_date'] = NULL;
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
		$this->date->DbValue = $row['date'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity_in->DbValue = $row['quantity_in'];
		$this->quantity_out->DbValue = $row['quantity_out'];
		$this->total_quantity->DbValue = $row['total_quantity'];
		$this->quantity_type->DbValue = $row['quantity_type'];
		$this->treated_by->DbValue = $row['treated_by'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->statuss->DbValue = $row['statuss'];
		$this->issued_action->DbValue = $row['issued_action'];
		$this->issued_comment->DbValue = $row['issued_comment'];
		$this->issued_by->DbValue = $row['issued_by'];
		$this->approver_date->DbValue = $row['approver_date'];
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
		// date
		// reference_id
		// material_name
		// quantity_in
		// quantity_out
		// total_quantity
		// quantity_type
		// treated_by
		// staff_id
		// statuss
		// issued_action
		// issued_comment
		// issued_by
		// approver_date
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

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 17);
		$this->date->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
		$sWhereWrk = "";
		$this->material_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->material_name->ViewValue = $this->material_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->material_name->ViewValue = $this->material_name->CurrentValue;
			}
		} else {
			$this->material_name->ViewValue = NULL;
		}
		$this->material_name->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_out
		$this->quantity_out->ViewValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// quantity_type
		$this->quantity_type->ViewValue = $this->quantity_type->CurrentValue;
		$this->quantity_type->ViewCustomAttributes = "";

		// treated_by
		$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
		if (strval($this->treated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->treated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->treated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->treated_by->ViewValue = $this->treated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
			}
		} else {
			$this->treated_by->ViewValue = NULL;
		}
		$this->treated_by->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
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

		// issued_action
		if (strval($this->issued_action->CurrentValue) <> "") {
			$this->issued_action->ViewValue = $this->issued_action->OptionCaption($this->issued_action->CurrentValue);
		} else {
			$this->issued_action->ViewValue = NULL;
		}
		$this->issued_action->ViewCustomAttributes = "";

		// issued_comment
		$this->issued_comment->ViewValue = $this->issued_comment->CurrentValue;
		$this->issued_comment->ViewCustomAttributes = "";

		// issued_by
		$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
		if (strval($this->issued_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->issued_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->issued_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->issued_by->ViewValue = $this->issued_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
			}
		} else {
			$this->issued_by->ViewValue = NULL;
		}
		$this->issued_by->ViewCustomAttributes = "";

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

		// verified_date
		$this->verified_date->ViewValue = $this->verified_date->CurrentValue;
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 0);
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
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->verified_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->verified_by->ViewValue = $this->verified_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
			}
		} else {
			$this->verified_by->ViewValue = NULL;
		}
		$this->verified_by->ViewCustomAttributes = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_out
			$this->quantity_out->LinkCustomAttributes = "";
			$this->quantity_out->HrefValue = "";
			$this->quantity_out->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// quantity_type
			$this->quantity_type->LinkCustomAttributes = "";
			$this->quantity_type->HrefValue = "";
			$this->quantity_type->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

			// issued_by
			$this->issued_by->LinkCustomAttributes = "";
			$this->issued_by->HrefValue = "";
			$this->issued_by->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("issuance_store_staysafelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($issuance_store_staysafe_delete)) $issuance_store_staysafe_delete = new cissuance_store_staysafe_delete();

// Page init
$issuance_store_staysafe_delete->Page_Init();

// Page main
$issuance_store_staysafe_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$issuance_store_staysafe_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fissuance_store_staysafedelete = new ew_Form("fissuance_store_staysafedelete", "delete");

// Form_CustomValidate event
fissuance_store_staysafedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fissuance_store_staysafedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fissuance_store_staysafedelete.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory_staysafe"};
fissuance_store_staysafedelete.Lists["x_material_name"].Data = "<?php echo $issuance_store_staysafe_delete->material_name->LookupFilterQuery(FALSE, "delete") ?>";
fissuance_store_staysafedelete.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
fissuance_store_staysafedelete.Lists["x_statuss"].Data = "<?php echo $issuance_store_staysafe_delete->statuss->LookupFilterQuery(FALSE, "delete") ?>";
fissuance_store_staysafedelete.Lists["x_issued_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafedelete.Lists["x_issued_by"].Data = "<?php echo $issuance_store_staysafe_delete->issued_by->LookupFilterQuery(FALSE, "delete") ?>";
fissuance_store_staysafedelete.AutoSuggests["x_issued_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_delete->issued_by->LookupFilterQuery(TRUE, "delete"))) ?>;
fissuance_store_staysafedelete.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafedelete.Lists["x_approved_by"].Data = "<?php echo $issuance_store_staysafe_delete->approved_by->LookupFilterQuery(FALSE, "delete") ?>";
fissuance_store_staysafedelete.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_delete->approved_by->LookupFilterQuery(TRUE, "delete"))) ?>;
fissuance_store_staysafedelete.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafedelete.Lists["x_verified_by"].Data = "<?php echo $issuance_store_staysafe_delete->verified_by->LookupFilterQuery(FALSE, "delete") ?>";
fissuance_store_staysafedelete.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_delete->verified_by->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $issuance_store_staysafe_delete->ShowPageHeader(); ?>
<?php
$issuance_store_staysafe_delete->ShowMessage();
?>
<form name="fissuance_store_staysafedelete" id="fissuance_store_staysafedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($issuance_store_staysafe_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $issuance_store_staysafe_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="issuance_store_staysafe">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($issuance_store_staysafe_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($issuance_store_staysafe->date->Visible) { // date ?>
		<th class="<?php echo $issuance_store_staysafe->date->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_date" class="issuance_store_staysafe_date"><?php echo $issuance_store_staysafe->date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->reference_id->Visible) { // reference_id ?>
		<th class="<?php echo $issuance_store_staysafe->reference_id->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_reference_id" class="issuance_store_staysafe_reference_id"><?php echo $issuance_store_staysafe->reference_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->material_name->Visible) { // material_name ?>
		<th class="<?php echo $issuance_store_staysafe->material_name->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_material_name" class="issuance_store_staysafe_material_name"><?php echo $issuance_store_staysafe->material_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_in->Visible) { // quantity_in ?>
		<th class="<?php echo $issuance_store_staysafe->quantity_in->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_quantity_in" class="issuance_store_staysafe_quantity_in"><?php echo $issuance_store_staysafe->quantity_in->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_out->Visible) { // quantity_out ?>
		<th class="<?php echo $issuance_store_staysafe->quantity_out->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_quantity_out" class="issuance_store_staysafe_quantity_out"><?php echo $issuance_store_staysafe->quantity_out->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->total_quantity->Visible) { // total_quantity ?>
		<th class="<?php echo $issuance_store_staysafe->total_quantity->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_total_quantity" class="issuance_store_staysafe_total_quantity"><?php echo $issuance_store_staysafe->total_quantity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_type->Visible) { // quantity_type ?>
		<th class="<?php echo $issuance_store_staysafe->quantity_type->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_quantity_type" class="issuance_store_staysafe_quantity_type"><?php echo $issuance_store_staysafe->quantity_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->statuss->Visible) { // statuss ?>
		<th class="<?php echo $issuance_store_staysafe->statuss->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_statuss" class="issuance_store_staysafe_statuss"><?php echo $issuance_store_staysafe->statuss->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->issued_by->Visible) { // issued_by ?>
		<th class="<?php echo $issuance_store_staysafe->issued_by->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_issued_by" class="issuance_store_staysafe_issued_by"><?php echo $issuance_store_staysafe->issued_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->approved_by->Visible) { // approved_by ?>
		<th class="<?php echo $issuance_store_staysafe->approved_by->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_approved_by" class="issuance_store_staysafe_approved_by"><?php echo $issuance_store_staysafe->approved_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($issuance_store_staysafe->verified_by->Visible) { // verified_by ?>
		<th class="<?php echo $issuance_store_staysafe->verified_by->HeaderCellClass() ?>"><span id="elh_issuance_store_staysafe_verified_by" class="issuance_store_staysafe_verified_by"><?php echo $issuance_store_staysafe->verified_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$issuance_store_staysafe_delete->RecCnt = 0;
$i = 0;
while (!$issuance_store_staysafe_delete->Recordset->EOF) {
	$issuance_store_staysafe_delete->RecCnt++;
	$issuance_store_staysafe_delete->RowCnt++;

	// Set row properties
	$issuance_store_staysafe->ResetAttrs();
	$issuance_store_staysafe->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$issuance_store_staysafe_delete->LoadRowValues($issuance_store_staysafe_delete->Recordset);

	// Render row
	$issuance_store_staysafe_delete->RenderRow();
?>
	<tr<?php echo $issuance_store_staysafe->RowAttributes() ?>>
<?php if ($issuance_store_staysafe->date->Visible) { // date ?>
		<td<?php echo $issuance_store_staysafe->date->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_date" class="issuance_store_staysafe_date">
<span<?php echo $issuance_store_staysafe->date->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->reference_id->Visible) { // reference_id ?>
		<td<?php echo $issuance_store_staysafe->reference_id->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_reference_id" class="issuance_store_staysafe_reference_id">
<span<?php echo $issuance_store_staysafe->reference_id->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->reference_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->material_name->Visible) { // material_name ?>
		<td<?php echo $issuance_store_staysafe->material_name->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_material_name" class="issuance_store_staysafe_material_name">
<span<?php echo $issuance_store_staysafe->material_name->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->material_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_in->Visible) { // quantity_in ?>
		<td<?php echo $issuance_store_staysafe->quantity_in->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_quantity_in" class="issuance_store_staysafe_quantity_in">
<span<?php echo $issuance_store_staysafe->quantity_in->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->quantity_in->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_out->Visible) { // quantity_out ?>
		<td<?php echo $issuance_store_staysafe->quantity_out->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_quantity_out" class="issuance_store_staysafe_quantity_out">
<span<?php echo $issuance_store_staysafe->quantity_out->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->quantity_out->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->total_quantity->Visible) { // total_quantity ?>
		<td<?php echo $issuance_store_staysafe->total_quantity->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_total_quantity" class="issuance_store_staysafe_total_quantity">
<span<?php echo $issuance_store_staysafe->total_quantity->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->total_quantity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_type->Visible) { // quantity_type ?>
		<td<?php echo $issuance_store_staysafe->quantity_type->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_quantity_type" class="issuance_store_staysafe_quantity_type">
<span<?php echo $issuance_store_staysafe->quantity_type->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->quantity_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->statuss->Visible) { // statuss ?>
		<td<?php echo $issuance_store_staysafe->statuss->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_statuss" class="issuance_store_staysafe_statuss">
<span<?php echo $issuance_store_staysafe->statuss->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->statuss->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->issued_by->Visible) { // issued_by ?>
		<td<?php echo $issuance_store_staysafe->issued_by->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_issued_by" class="issuance_store_staysafe_issued_by">
<span<?php echo $issuance_store_staysafe->issued_by->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->issued_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->approved_by->Visible) { // approved_by ?>
		<td<?php echo $issuance_store_staysafe->approved_by->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_approved_by" class="issuance_store_staysafe_approved_by">
<span<?php echo $issuance_store_staysafe->approved_by->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->approved_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($issuance_store_staysafe->verified_by->Visible) { // verified_by ?>
		<td<?php echo $issuance_store_staysafe->verified_by->CellAttributes() ?>>
<span id="el<?php echo $issuance_store_staysafe_delete->RowCnt ?>_issuance_store_staysafe_verified_by" class="issuance_store_staysafe_verified_by">
<span<?php echo $issuance_store_staysafe->verified_by->ViewAttributes() ?>>
<?php echo $issuance_store_staysafe->verified_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$issuance_store_staysafe_delete->Recordset->MoveNext();
}
$issuance_store_staysafe_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $issuance_store_staysafe_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fissuance_store_staysafedelete.Init();
</script>
<?php
$issuance_store_staysafe_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$issuance_store_staysafe_delete->Page_Terminate();
?>
