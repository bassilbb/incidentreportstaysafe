<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "spare_part_usageinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$spare_part_usage_delete = NULL; // Initialize page object first

class cspare_part_usage_delete extends cspare_part_usage {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'spare_part_usage';

	// Page object name
	var $PageObjName = 'spare_part_usage_delete';

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

		// Table object (spare_part_usage)
		if (!isset($GLOBALS["spare_part_usage"]) || get_class($GLOBALS["spare_part_usage"]) == "cspare_part_usage") {
			$GLOBALS["spare_part_usage"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["spare_part_usage"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'spare_part_usage');

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
				$this->Page_Terminate(ew_GetUrl("spare_part_usagelist.php"));
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
		$this->part_name->SetVisibility();
		$this->gen_name->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_used->SetVisibility();
		$this->cost->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->total_cost->SetVisibility();
		$this->maintenance_total_cost->SetVisibility();

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
		global $EW_EXPORT, $spare_part_usage;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($spare_part_usage);
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
			$this->Page_Terminate("spare_part_usagelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in spare_part_usage class, spare_part_usageinfo.php

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
				$this->Page_Terminate("spare_part_usagelist.php"); // Return to list
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
		$this->part_name->setDbValue($row['part_name']);
		$this->gen_name->setDbValue($row['gen_name']);
		$this->quantity_in->setDbValue($row['quantity_in']);
		$this->quantity_used->setDbValue($row['quantity_used']);
		$this->cost->setDbValue($row['cost']);
		$this->total_quantity->setDbValue($row['total_quantity']);
		$this->total_cost->setDbValue($row['total_cost']);
		$this->maintenance_total_cost->setDbValue($row['maintenance_total_cost']);
		$this->maintenance_id->setDbValue($row['maintenance_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date'] = NULL;
		$row['reference_id'] = NULL;
		$row['part_name'] = NULL;
		$row['gen_name'] = NULL;
		$row['quantity_in'] = NULL;
		$row['quantity_used'] = NULL;
		$row['cost'] = NULL;
		$row['total_quantity'] = NULL;
		$row['total_cost'] = NULL;
		$row['maintenance_total_cost'] = NULL;
		$row['maintenance_id'] = NULL;
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
		$this->part_name->DbValue = $row['part_name'];
		$this->gen_name->DbValue = $row['gen_name'];
		$this->quantity_in->DbValue = $row['quantity_in'];
		$this->quantity_used->DbValue = $row['quantity_used'];
		$this->cost->DbValue = $row['cost'];
		$this->total_quantity->DbValue = $row['total_quantity'];
		$this->total_cost->DbValue = $row['total_cost'];
		$this->maintenance_total_cost->DbValue = $row['maintenance_total_cost'];
		$this->maintenance_id->DbValue = $row['maintenance_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->cost->FormValue == $this->cost->CurrentValue && is_numeric(ew_StrToFloat($this->cost->CurrentValue)))
			$this->cost->CurrentValue = ew_StrToFloat($this->cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total_cost->FormValue == $this->total_cost->CurrentValue && is_numeric(ew_StrToFloat($this->total_cost->CurrentValue)))
			$this->total_cost->CurrentValue = ew_StrToFloat($this->total_cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->maintenance_total_cost->FormValue == $this->maintenance_total_cost->CurrentValue && is_numeric(ew_StrToFloat($this->maintenance_total_cost->CurrentValue)))
			$this->maintenance_total_cost->CurrentValue = ew_StrToFloat($this->maintenance_total_cost->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date
		// reference_id
		// part_name
		// gen_name
		// quantity_in
		// quantity_used
		// cost
		// total_quantity
		// total_cost
		// maintenance_total_cost
		// maintenance_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// reference_id
		if (strval($this->reference_id->CurrentValue) <> "") {
			$sFilterWrk = "`reference_id`" . ew_SearchString("=", $this->reference_id->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `reference_id`, `reference_id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_maintenance`";
		$sWhereWrk = "";
		$this->reference_id->LookupFilters = array();
		$lookuptblfilter = "`flag`='0'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reference_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->reference_id->ViewValue = $this->reference_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
			}
		} else {
			$this->reference_id->ViewValue = NULL;
		}
		$this->reference_id->ViewCustomAttributes = "";

		// part_name
		if (strval($this->part_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
		$sWhereWrk = "";
		$this->part_name->LookupFilters = array("dx1" => '`part_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->part_name->ViewValue = $this->part_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->part_name->ViewValue = $this->part_name->CurrentValue;
			}
		} else {
			$this->part_name->ViewValue = NULL;
		}
		$this->part_name->ViewCustomAttributes = "";

		// gen_name
		$this->gen_name->ViewValue = $this->gen_name->CurrentValue;
		$this->gen_name->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_used
		$this->quantity_used->ViewValue = $this->quantity_used->CurrentValue;
		$this->quantity_used->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// total_cost
		$this->total_cost->ViewValue = $this->total_cost->CurrentValue;
		$this->total_cost->ViewValue = ew_FormatNumber($this->total_cost->ViewValue, 0, -2, -2, -2);
		$this->total_cost->ViewCustomAttributes = "";

		// maintenance_total_cost
		$this->maintenance_total_cost->ViewValue = $this->maintenance_total_cost->CurrentValue;
		$this->maintenance_total_cost->ViewCustomAttributes = "";

		// maintenance_id
		$this->maintenance_id->ViewValue = $this->maintenance_id->CurrentValue;
		$this->maintenance_id->ViewCustomAttributes = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// part_name
			$this->part_name->LinkCustomAttributes = "";
			$this->part_name->HrefValue = "";
			$this->part_name->TooltipValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";
			$this->gen_name->TooltipValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_used
			$this->quantity_used->LinkCustomAttributes = "";
			$this->quantity_used->HrefValue = "";
			$this->quantity_used->TooltipValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// total_cost
			$this->total_cost->LinkCustomAttributes = "";
			$this->total_cost->HrefValue = "";
			$this->total_cost->TooltipValue = "";

			// maintenance_total_cost
			$this->maintenance_total_cost->LinkCustomAttributes = "";
			$this->maintenance_total_cost->HrefValue = "";
			$this->maintenance_total_cost->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("spare_part_usagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($spare_part_usage_delete)) $spare_part_usage_delete = new cspare_part_usage_delete();

// Page init
$spare_part_usage_delete->Page_Init();

// Page main
$spare_part_usage_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$spare_part_usage_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fspare_part_usagedelete = new ew_Form("fspare_part_usagedelete", "delete");

// Form_CustomValidate event
fspare_part_usagedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fspare_part_usagedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fspare_part_usagedelete.Lists["x_reference_id"] = {"LinkField":"x_reference_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_reference_id","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_maintenance"};
fspare_part_usagedelete.Lists["x_reference_id"].Data = "<?php echo $spare_part_usage_delete->reference_id->LookupFilterQuery(FALSE, "delete") ?>";
fspare_part_usagedelete.Lists["x_part_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_part_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sparepart_module"};
fspare_part_usagedelete.Lists["x_part_name"].Data = "<?php echo $spare_part_usage_delete->part_name->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $spare_part_usage_delete->ShowPageHeader(); ?>
<?php
$spare_part_usage_delete->ShowMessage();
?>
<form name="fspare_part_usagedelete" id="fspare_part_usagedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($spare_part_usage_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $spare_part_usage_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="spare_part_usage">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($spare_part_usage_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($spare_part_usage->date->Visible) { // date ?>
		<th class="<?php echo $spare_part_usage->date->HeaderCellClass() ?>"><span id="elh_spare_part_usage_date" class="spare_part_usage_date"><?php echo $spare_part_usage->date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->reference_id->Visible) { // reference_id ?>
		<th class="<?php echo $spare_part_usage->reference_id->HeaderCellClass() ?>"><span id="elh_spare_part_usage_reference_id" class="spare_part_usage_reference_id"><?php echo $spare_part_usage->reference_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->part_name->Visible) { // part_name ?>
		<th class="<?php echo $spare_part_usage->part_name->HeaderCellClass() ?>"><span id="elh_spare_part_usage_part_name" class="spare_part_usage_part_name"><?php echo $spare_part_usage->part_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->gen_name->Visible) { // gen_name ?>
		<th class="<?php echo $spare_part_usage->gen_name->HeaderCellClass() ?>"><span id="elh_spare_part_usage_gen_name" class="spare_part_usage_gen_name"><?php echo $spare_part_usage->gen_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->quantity_in->Visible) { // quantity_in ?>
		<th class="<?php echo $spare_part_usage->quantity_in->HeaderCellClass() ?>"><span id="elh_spare_part_usage_quantity_in" class="spare_part_usage_quantity_in"><?php echo $spare_part_usage->quantity_in->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->quantity_used->Visible) { // quantity_used ?>
		<th class="<?php echo $spare_part_usage->quantity_used->HeaderCellClass() ?>"><span id="elh_spare_part_usage_quantity_used" class="spare_part_usage_quantity_used"><?php echo $spare_part_usage->quantity_used->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->cost->Visible) { // cost ?>
		<th class="<?php echo $spare_part_usage->cost->HeaderCellClass() ?>"><span id="elh_spare_part_usage_cost" class="spare_part_usage_cost"><?php echo $spare_part_usage->cost->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->total_quantity->Visible) { // total_quantity ?>
		<th class="<?php echo $spare_part_usage->total_quantity->HeaderCellClass() ?>"><span id="elh_spare_part_usage_total_quantity" class="spare_part_usage_total_quantity"><?php echo $spare_part_usage->total_quantity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->total_cost->Visible) { // total_cost ?>
		<th class="<?php echo $spare_part_usage->total_cost->HeaderCellClass() ?>"><span id="elh_spare_part_usage_total_cost" class="spare_part_usage_total_cost"><?php echo $spare_part_usage->total_cost->FldCaption() ?></span></th>
<?php } ?>
<?php if ($spare_part_usage->maintenance_total_cost->Visible) { // maintenance_total_cost ?>
		<th class="<?php echo $spare_part_usage->maintenance_total_cost->HeaderCellClass() ?>"><span id="elh_spare_part_usage_maintenance_total_cost" class="spare_part_usage_maintenance_total_cost"><?php echo $spare_part_usage->maintenance_total_cost->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$spare_part_usage_delete->RecCnt = 0;
$i = 0;
while (!$spare_part_usage_delete->Recordset->EOF) {
	$spare_part_usage_delete->RecCnt++;
	$spare_part_usage_delete->RowCnt++;

	// Set row properties
	$spare_part_usage->ResetAttrs();
	$spare_part_usage->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$spare_part_usage_delete->LoadRowValues($spare_part_usage_delete->Recordset);

	// Render row
	$spare_part_usage_delete->RenderRow();
?>
	<tr<?php echo $spare_part_usage->RowAttributes() ?>>
<?php if ($spare_part_usage->date->Visible) { // date ?>
		<td<?php echo $spare_part_usage->date->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_date" class="spare_part_usage_date">
<span<?php echo $spare_part_usage->date->ViewAttributes() ?>>
<?php echo $spare_part_usage->date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->reference_id->Visible) { // reference_id ?>
		<td<?php echo $spare_part_usage->reference_id->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_reference_id" class="spare_part_usage_reference_id">
<span<?php echo $spare_part_usage->reference_id->ViewAttributes() ?>>
<?php echo $spare_part_usage->reference_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->part_name->Visible) { // part_name ?>
		<td<?php echo $spare_part_usage->part_name->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_part_name" class="spare_part_usage_part_name">
<span<?php echo $spare_part_usage->part_name->ViewAttributes() ?>>
<?php echo $spare_part_usage->part_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->gen_name->Visible) { // gen_name ?>
		<td<?php echo $spare_part_usage->gen_name->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_gen_name" class="spare_part_usage_gen_name">
<span<?php echo $spare_part_usage->gen_name->ViewAttributes() ?>>
<?php echo $spare_part_usage->gen_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->quantity_in->Visible) { // quantity_in ?>
		<td<?php echo $spare_part_usage->quantity_in->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_quantity_in" class="spare_part_usage_quantity_in">
<span<?php echo $spare_part_usage->quantity_in->ViewAttributes() ?>>
<?php echo $spare_part_usage->quantity_in->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->quantity_used->Visible) { // quantity_used ?>
		<td<?php echo $spare_part_usage->quantity_used->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_quantity_used" class="spare_part_usage_quantity_used">
<span<?php echo $spare_part_usage->quantity_used->ViewAttributes() ?>>
<?php echo $spare_part_usage->quantity_used->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->cost->Visible) { // cost ?>
		<td<?php echo $spare_part_usage->cost->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_cost" class="spare_part_usage_cost">
<span<?php echo $spare_part_usage->cost->ViewAttributes() ?>>
<?php echo $spare_part_usage->cost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->total_quantity->Visible) { // total_quantity ?>
		<td<?php echo $spare_part_usage->total_quantity->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_total_quantity" class="spare_part_usage_total_quantity">
<span<?php echo $spare_part_usage->total_quantity->ViewAttributes() ?>>
<?php echo $spare_part_usage->total_quantity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->total_cost->Visible) { // total_cost ?>
		<td<?php echo $spare_part_usage->total_cost->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_total_cost" class="spare_part_usage_total_cost">
<span<?php echo $spare_part_usage->total_cost->ViewAttributes() ?>>
<?php echo $spare_part_usage->total_cost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($spare_part_usage->maintenance_total_cost->Visible) { // maintenance_total_cost ?>
		<td<?php echo $spare_part_usage->maintenance_total_cost->CellAttributes() ?>>
<span id="el<?php echo $spare_part_usage_delete->RowCnt ?>_spare_part_usage_maintenance_total_cost" class="spare_part_usage_maintenance_total_cost">
<span<?php echo $spare_part_usage->maintenance_total_cost->ViewAttributes() ?>>
<?php echo $spare_part_usage->maintenance_total_cost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$spare_part_usage_delete->Recordset->MoveNext();
}
$spare_part_usage_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $spare_part_usage_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fspare_part_usagedelete.Init();
</script>
<?php
$spare_part_usage_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$spare_part_usage_delete->Page_Terminate();
?>
