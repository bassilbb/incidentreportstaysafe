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

$spare_part_usage_add = NULL; // Initialize page object first

class cspare_part_usage_add extends cspare_part_usage {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'spare_part_usage';

	// Page object name
	var $PageObjName = 'spare_part_usage_add';

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
			define("EW_PAGE_ID", 'add');

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

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
		if (!$Security->CanAdd()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->date->SetVisibility();
		$this->part_name->SetVisibility();
		$this->maintenance_id->SetVisibility();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "spare_part_usageview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("spare_part_usagelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "spare_part_usagelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "spare_part_usageview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->date->CurrentValue = NULL;
		$this->date->OldValue = $this->date->CurrentValue;
		$this->part_name->CurrentValue = NULL;
		$this->part_name->OldValue = $this->part_name->CurrentValue;
		$this->maintenance_id->CurrentValue = NULL;
		$this->maintenance_id->OldValue = $this->maintenance_id->CurrentValue;
		$this->quantity_in->CurrentValue = NULL;
		$this->quantity_in->OldValue = $this->quantity_in->CurrentValue;
		$this->quantity_used->CurrentValue = NULL;
		$this->quantity_used->OldValue = $this->quantity_used->CurrentValue;
		$this->cost->CurrentValue = NULL;
		$this->cost->OldValue = $this->cost->CurrentValue;
		$this->total_quantity->CurrentValue = NULL;
		$this->total_quantity->OldValue = $this->total_quantity->CurrentValue;
		$this->total_cost->CurrentValue = NULL;
		$this->total_cost->OldValue = $this->total_cost->CurrentValue;
		$this->maintenance_total_cost->CurrentValue = NULL;
		$this->maintenance_total_cost->OldValue = $this->maintenance_total_cost->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		}
		if (!$this->part_name->FldIsDetailKey) {
			$this->part_name->setFormValue($objForm->GetValue("x_part_name"));
		}
		if (!$this->maintenance_id->FldIsDetailKey) {
			$this->maintenance_id->setFormValue($objForm->GetValue("x_maintenance_id"));
		}
		if (!$this->quantity_in->FldIsDetailKey) {
			$this->quantity_in->setFormValue($objForm->GetValue("x_quantity_in"));
		}
		if (!$this->quantity_used->FldIsDetailKey) {
			$this->quantity_used->setFormValue($objForm->GetValue("x_quantity_used"));
		}
		if (!$this->cost->FldIsDetailKey) {
			$this->cost->setFormValue($objForm->GetValue("x_cost"));
		}
		if (!$this->total_quantity->FldIsDetailKey) {
			$this->total_quantity->setFormValue($objForm->GetValue("x_total_quantity"));
		}
		if (!$this->total_cost->FldIsDetailKey) {
			$this->total_cost->setFormValue($objForm->GetValue("x_total_cost"));
		}
		if (!$this->maintenance_total_cost->FldIsDetailKey) {
			$this->maintenance_total_cost->setFormValue($objForm->GetValue("x_maintenance_total_cost"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		$this->part_name->CurrentValue = $this->part_name->FormValue;
		$this->maintenance_id->CurrentValue = $this->maintenance_id->FormValue;
		$this->quantity_in->CurrentValue = $this->quantity_in->FormValue;
		$this->quantity_used->CurrentValue = $this->quantity_used->FormValue;
		$this->cost->CurrentValue = $this->cost->FormValue;
		$this->total_quantity->CurrentValue = $this->total_quantity->FormValue;
		$this->total_cost->CurrentValue = $this->total_cost->FormValue;
		$this->maintenance_total_cost->CurrentValue = $this->maintenance_total_cost->FormValue;
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
		$this->part_name->setDbValue($row['part_name']);
		$this->maintenance_id->setDbValue($row['maintenance_id']);
		$this->quantity_in->setDbValue($row['quantity_in']);
		$this->quantity_used->setDbValue($row['quantity_used']);
		$this->cost->setDbValue($row['cost']);
		$this->total_quantity->setDbValue($row['total_quantity']);
		$this->total_cost->setDbValue($row['total_cost']);
		$this->maintenance_total_cost->setDbValue($row['maintenance_total_cost']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['date'] = $this->date->CurrentValue;
		$row['part_name'] = $this->part_name->CurrentValue;
		$row['maintenance_id'] = $this->maintenance_id->CurrentValue;
		$row['quantity_in'] = $this->quantity_in->CurrentValue;
		$row['quantity_used'] = $this->quantity_used->CurrentValue;
		$row['cost'] = $this->cost->CurrentValue;
		$row['total_quantity'] = $this->total_quantity->CurrentValue;
		$row['total_cost'] = $this->total_cost->CurrentValue;
		$row['maintenance_total_cost'] = $this->maintenance_total_cost->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date->DbValue = $row['date'];
		$this->part_name->DbValue = $row['part_name'];
		$this->maintenance_id->DbValue = $row['maintenance_id'];
		$this->quantity_in->DbValue = $row['quantity_in'];
		$this->quantity_used->DbValue = $row['quantity_used'];
		$this->cost->DbValue = $row['cost'];
		$this->total_quantity->DbValue = $row['total_quantity'];
		$this->total_cost->DbValue = $row['total_cost'];
		$this->maintenance_total_cost->DbValue = $row['maintenance_total_cost'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
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
		// part_name
		// maintenance_id
		// quantity_in
		// quantity_used
		// cost
		// total_quantity
		// total_cost
		// maintenance_total_cost

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// part_name
		if (strval($this->part_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
		$sWhereWrk = "";
		$this->part_name->LookupFilters = array();
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

		// maintenance_id
		if (strval($this->maintenance_id->CurrentValue) <> "") {
			$sFilterWrk = "`maintenance_id`" . ew_SearchString("=", $this->maintenance_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `maintenance_id`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_view`";
		$sWhereWrk = "";
		$this->maintenance_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->maintenance_id->ViewValue = $this->maintenance_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintenance_id->ViewValue = $this->maintenance_id->CurrentValue;
			}
		} else {
			$this->maintenance_id->ViewValue = NULL;
		}
		$this->maintenance_id->ViewCustomAttributes = "";

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

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// part_name
			$this->part_name->LinkCustomAttributes = "";
			$this->part_name->HrefValue = "";
			$this->part_name->TooltipValue = "";

			// maintenance_id
			$this->maintenance_id->LinkCustomAttributes = "";
			$this->maintenance_id->HrefValue = "";
			$this->maintenance_id->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// part_name
			$this->part_name->EditAttrs["class"] = "form-control";
			$this->part_name->EditCustomAttributes = "";
			if (trim(strval($this->part_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sparepart_module`";
			$sWhereWrk = "";
			$this->part_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->part_name->EditValue = $arwrk;

			// maintenance_id
			$this->maintenance_id->EditAttrs["class"] = "form-control";
			$this->maintenance_id->EditCustomAttributes = "";
			if (trim(strval($this->maintenance_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`maintenance_id`" . ew_SearchString("=", $this->maintenance_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `maintenance_id`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sparepart_view`";
			$sWhereWrk = "";
			$this->maintenance_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->maintenance_id->EditValue = $arwrk;

			// quantity_in
			$this->quantity_in->EditAttrs["class"] = "form-control";
			$this->quantity_in->EditCustomAttributes = "";
			$this->quantity_in->EditValue = ew_HtmlEncode($this->quantity_in->CurrentValue);
			$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

			// quantity_used
			$this->quantity_used->EditAttrs["class"] = "form-control";
			$this->quantity_used->EditCustomAttributes = "";
			$this->quantity_used->EditValue = ew_HtmlEncode($this->quantity_used->CurrentValue);
			$this->quantity_used->PlaceHolder = ew_RemoveHtml($this->quantity_used->FldCaption());

			// cost
			$this->cost->EditAttrs["class"] = "form-control";
			$this->cost->EditCustomAttributes = "";
			$this->cost->EditValue = ew_HtmlEncode($this->cost->CurrentValue);
			$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());
			if (strval($this->cost->EditValue) <> "" && is_numeric($this->cost->EditValue)) $this->cost->EditValue = ew_FormatNumber($this->cost->EditValue, -2, -1, -2, 0);

			// total_quantity
			$this->total_quantity->EditAttrs["class"] = "form-control";
			$this->total_quantity->EditCustomAttributes = "";
			$this->total_quantity->EditValue = ew_HtmlEncode($this->total_quantity->CurrentValue);
			$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

			// total_cost
			$this->total_cost->EditAttrs["class"] = "form-control";
			$this->total_cost->EditCustomAttributes = "";
			$this->total_cost->EditValue = ew_HtmlEncode($this->total_cost->CurrentValue);
			$this->total_cost->PlaceHolder = ew_RemoveHtml($this->total_cost->FldCaption());
			if (strval($this->total_cost->EditValue) <> "" && is_numeric($this->total_cost->EditValue)) $this->total_cost->EditValue = ew_FormatNumber($this->total_cost->EditValue, -2, -2, -2, -2);

			// maintenance_total_cost
			$this->maintenance_total_cost->EditAttrs["class"] = "form-control";
			$this->maintenance_total_cost->EditCustomAttributes = "";
			$this->maintenance_total_cost->EditValue = ew_HtmlEncode($this->maintenance_total_cost->CurrentValue);
			$this->maintenance_total_cost->PlaceHolder = ew_RemoveHtml($this->maintenance_total_cost->FldCaption());
			if (strval($this->maintenance_total_cost->EditValue) <> "" && is_numeric($this->maintenance_total_cost->EditValue)) $this->maintenance_total_cost->EditValue = ew_FormatNumber($this->maintenance_total_cost->EditValue, -2, -1, -2, 0);

			// Add refer script
			// date

			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// part_name
			$this->part_name->LinkCustomAttributes = "";
			$this->part_name->HrefValue = "";

			// maintenance_id
			$this->maintenance_id->LinkCustomAttributes = "";
			$this->maintenance_id->HrefValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";

			// quantity_used
			$this->quantity_used->LinkCustomAttributes = "";
			$this->quantity_used->HrefValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";

			// total_cost
			$this->total_cost->LinkCustomAttributes = "";
			$this->total_cost->HrefValue = "";

			// maintenance_total_cost
			$this->maintenance_total_cost->LinkCustomAttributes = "";
			$this->maintenance_total_cost->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckDateDef($this->date->FormValue)) {
			ew_AddMessage($gsFormError, $this->date->FldErrMsg());
		}
		if (!ew_CheckNumber($this->cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total_cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->total_cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->maintenance_total_cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->maintenance_total_cost->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// date
		$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 0), NULL, FALSE);

		// part_name
		$this->part_name->SetDbValueDef($rsnew, $this->part_name->CurrentValue, NULL, FALSE);

		// maintenance_id
		$this->maintenance_id->SetDbValueDef($rsnew, $this->maintenance_id->CurrentValue, NULL, FALSE);

		// quantity_in
		$this->quantity_in->SetDbValueDef($rsnew, $this->quantity_in->CurrentValue, NULL, FALSE);

		// quantity_used
		$this->quantity_used->SetDbValueDef($rsnew, $this->quantity_used->CurrentValue, NULL, FALSE);

		// cost
		$this->cost->SetDbValueDef($rsnew, $this->cost->CurrentValue, NULL, FALSE);

		// total_quantity
		$this->total_quantity->SetDbValueDef($rsnew, $this->total_quantity->CurrentValue, NULL, FALSE);

		// total_cost
		$this->total_cost->SetDbValueDef($rsnew, $this->total_cost->CurrentValue, NULL, FALSE);

		// maintenance_total_cost
		$this->maintenance_total_cost->SetDbValueDef($rsnew, $this->maintenance_total_cost->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("spare_part_usagelist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_part_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_maintenance_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `maintenance_id` AS `LinkFld`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_view`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`maintenance_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
				ew_SetClientVar("GetSparepart_ModuleDetailsSearchModel", ew_Encrypt("SELECT `quantity`,`cost` FROM `sparepart_module` WHERE `id`= {query_value}"));
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($spare_part_usage_add)) $spare_part_usage_add = new cspare_part_usage_add();

// Page init
$spare_part_usage_add->Page_Init();

// Page main
$spare_part_usage_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$spare_part_usage_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fspare_part_usageadd = new ew_Form("fspare_part_usageadd", "add");

// Validate form
fspare_part_usageadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->cost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->total_cost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maintenance_total_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->maintenance_total_cost->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fspare_part_usageadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fspare_part_usageadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fspare_part_usageadd.Lists["x_part_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_part_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sparepart_module"};
fspare_part_usageadd.Lists["x_part_name"].Data = "<?php echo $spare_part_usage_add->part_name->LookupFilterQuery(FALSE, "add") ?>";
fspare_part_usageadd.Lists["x_maintenance_id"] = {"LinkField":"x_maintenance_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_generator_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sparepart_view"};
fspare_part_usageadd.Lists["x_maintenance_id"].Data = "<?php echo $spare_part_usage_add->maintenance_id->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client$(document).ready(function(){
$(document).ready(function(){
	var sparepart_module;

	// When part changes
	$("#x_part_name").on("change", function() { 
		var StoreId = this.value;
		if(StoreId != ''){
			var resultSearchModel = ew_Ajax(ewVar.GetSparepart_ModuleDetailsSearchModel, StoreId);
			if(resultSearchModel != ''){
				$('#x_quantity_in').val(resultSearchModel[0]);
				$('#x_cost').val(resultSearchModel[1]);
			}
		}
	});

	// When quantity used changes
	$('#x_quantity_used').on("change", function() { 
		var quantity = this.value;
		sparepart_module = parseInt($('#x_quantity_in').val());
		var unit_cost = parseFloat($('#x_cost').val());
		if(quantity != ''){
			quantity = parseInt(quantity);
			if((quantity <= sparepart_module) && (quantity > 0)){
				var getBal = sparepart_module - quantity;
				if(getBal < 0){
					$('#x_quantity_used').val('');
					$('#x_total_quantity').val('');
					$('#x_total_cost').val('');
				}else{
					$('#x_total_quantity').val(getBal);

					// ⭐ Calculate cost for this row
					var total_cost = quantity * unit_cost;
					$('#x_total_cost').val(total_cost.toFixed(2));

					// ⭐ NEW: Sum total_cost for all rows where maintenance_id = 1
					calculateMaintenanceTotal();
				}
			} else {
				$('#x_quantity_used').val('');
				$('#x_total_quantity').val('');
				$('#x_total_cost').val('');
			}
		}else{
			$('#x_total_quantity').val('');
			$('#x_total_cost').val('');
		}
	});
});
</script>
<?php $spare_part_usage_add->ShowPageHeader(); ?>
<?php
$spare_part_usage_add->ShowMessage();
?>
<form name="fspare_part_usageadd" id="fspare_part_usageadd" class="<?php echo $spare_part_usage_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($spare_part_usage_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $spare_part_usage_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="spare_part_usage">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($spare_part_usage_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($spare_part_usage->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_spare_part_usage_date" for="x_date" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->date->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->date->CellAttributes() ?>>
<span id="el_spare_part_usage_date">
<input type="text" data-table="spare_part_usage" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->date->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->date->EditValue ?>"<?php echo $spare_part_usage->date->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->part_name->Visible) { // part_name ?>
	<div id="r_part_name" class="form-group">
		<label id="elh_spare_part_usage_part_name" for="x_part_name" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->part_name->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->part_name->CellAttributes() ?>>
<span id="el_spare_part_usage_part_name">
<select data-table="spare_part_usage" data-field="x_part_name" data-value-separator="<?php echo $spare_part_usage->part_name->DisplayValueSeparatorAttribute() ?>" id="x_part_name" name="x_part_name"<?php echo $spare_part_usage->part_name->EditAttributes() ?>>
<?php echo $spare_part_usage->part_name->SelectOptionListHtml("x_part_name") ?>
</select>
</span>
<?php echo $spare_part_usage->part_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->maintenance_id->Visible) { // maintenance_id ?>
	<div id="r_maintenance_id" class="form-group">
		<label id="elh_spare_part_usage_maintenance_id" for="x_maintenance_id" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->maintenance_id->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->maintenance_id->CellAttributes() ?>>
<span id="el_spare_part_usage_maintenance_id">
<select data-table="spare_part_usage" data-field="x_maintenance_id" data-value-separator="<?php echo $spare_part_usage->maintenance_id->DisplayValueSeparatorAttribute() ?>" id="x_maintenance_id" name="x_maintenance_id"<?php echo $spare_part_usage->maintenance_id->EditAttributes() ?>>
<?php echo $spare_part_usage->maintenance_id->SelectOptionListHtml("x_maintenance_id") ?>
</select>
</span>
<?php echo $spare_part_usage->maintenance_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->quantity_in->Visible) { // quantity_in ?>
	<div id="r_quantity_in" class="form-group">
		<label id="elh_spare_part_usage_quantity_in" for="x_quantity_in" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->quantity_in->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->quantity_in->CellAttributes() ?>>
<span id="el_spare_part_usage_quantity_in">
<input type="text" data-table="spare_part_usage" data-field="x_quantity_in" name="x_quantity_in" id="x_quantity_in" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->quantity_in->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->quantity_in->EditValue ?>"<?php echo $spare_part_usage->quantity_in->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->quantity_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->quantity_used->Visible) { // quantity_used ?>
	<div id="r_quantity_used" class="form-group">
		<label id="elh_spare_part_usage_quantity_used" for="x_quantity_used" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->quantity_used->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->quantity_used->CellAttributes() ?>>
<span id="el_spare_part_usage_quantity_used">
<input type="text" data-table="spare_part_usage" data-field="x_quantity_used" name="x_quantity_used" id="x_quantity_used" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->quantity_used->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->quantity_used->EditValue ?>"<?php echo $spare_part_usage->quantity_used->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->quantity_used->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->cost->Visible) { // cost ?>
	<div id="r_cost" class="form-group">
		<label id="elh_spare_part_usage_cost" for="x_cost" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->cost->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->cost->CellAttributes() ?>>
<span id="el_spare_part_usage_cost">
<input type="text" data-table="spare_part_usage" data-field="x_cost" name="x_cost" id="x_cost" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->cost->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->cost->EditValue ?>"<?php echo $spare_part_usage->cost->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->total_quantity->Visible) { // total_quantity ?>
	<div id="r_total_quantity" class="form-group">
		<label id="elh_spare_part_usage_total_quantity" for="x_total_quantity" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->total_quantity->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->total_quantity->CellAttributes() ?>>
<span id="el_spare_part_usage_total_quantity">
<input type="text" data-table="spare_part_usage" data-field="x_total_quantity" name="x_total_quantity" id="x_total_quantity" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->total_quantity->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->total_quantity->EditValue ?>"<?php echo $spare_part_usage->total_quantity->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->total_quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->total_cost->Visible) { // total_cost ?>
	<div id="r_total_cost" class="form-group">
		<label id="elh_spare_part_usage_total_cost" for="x_total_cost" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->total_cost->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->total_cost->CellAttributes() ?>>
<span id="el_spare_part_usage_total_cost">
<input type="text" data-table="spare_part_usage" data-field="x_total_cost" name="x_total_cost" id="x_total_cost" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->total_cost->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->total_cost->EditValue ?>"<?php echo $spare_part_usage->total_cost->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->total_cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->maintenance_total_cost->Visible) { // maintenance_total_cost ?>
	<div id="r_maintenance_total_cost" class="form-group">
		<label id="elh_spare_part_usage_maintenance_total_cost" for="x_maintenance_total_cost" class="<?php echo $spare_part_usage_add->LeftColumnClass ?>"><?php echo $spare_part_usage->maintenance_total_cost->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_add->RightColumnClass ?>"><div<?php echo $spare_part_usage->maintenance_total_cost->CellAttributes() ?>>
<span id="el_spare_part_usage_maintenance_total_cost">
<input type="text" data-table="spare_part_usage" data-field="x_maintenance_total_cost" name="x_maintenance_total_cost" id="x_maintenance_total_cost" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->maintenance_total_cost->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->maintenance_total_cost->EditValue ?>"<?php echo $spare_part_usage->maintenance_total_cost->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->maintenance_total_cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$spare_part_usage_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $spare_part_usage_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $spare_part_usage_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fspare_part_usageadd.Init();
</script>
<?php
$spare_part_usage_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_maintenance_total_cost").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$spare_part_usage_add->Page_Terminate();
?>
