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

$inventory_add = NULL; // Initialize page object first

class cinventory_add extends cinventory {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'inventory';

	// Page object name
	var $PageObjName = 'inventory_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("inventorylist.php"));
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
		$this->date_recieved->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity->SetVisibility();
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->recieved_by->SetVisibility();

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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "inventoryview.php")
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
					$this->Page_Terminate("inventorylist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "inventorylist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "inventoryview.php")
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
		$this->date_recieved->CurrentValue = NULL;
		$this->date_recieved->OldValue = $this->date_recieved->CurrentValue;
		$this->reference_id->CurrentValue = NULL;
		$this->reference_id->OldValue = $this->reference_id->CurrentValue;
		$this->staff_id->CurrentValue = NULL;
		$this->staff_id->OldValue = $this->staff_id->CurrentValue;
		$this->material_name->CurrentValue = NULL;
		$this->material_name->OldValue = $this->material_name->CurrentValue;
		$this->quantity->CurrentValue = NULL;
		$this->quantity->OldValue = $this->quantity->CurrentValue;
		$this->type->CurrentValue = NULL;
		$this->type->OldValue = $this->type->CurrentValue;
		$this->capacity->CurrentValue = NULL;
		$this->capacity->OldValue = $this->capacity->CurrentValue;
		$this->recieved_by->CurrentValue = NULL;
		$this->recieved_by->OldValue = $this->recieved_by->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->date_recieved->FldIsDetailKey) {
			$this->date_recieved->setFormValue($objForm->GetValue("x_date_recieved"));
			$this->date_recieved->CurrentValue = ew_UnFormatDateTime($this->date_recieved->CurrentValue, 0);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->material_name->FldIsDetailKey) {
			$this->material_name->setFormValue($objForm->GetValue("x_material_name"));
		}
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->capacity->FldIsDetailKey) {
			$this->capacity->setFormValue($objForm->GetValue("x_capacity"));
		}
		if (!$this->recieved_by->FldIsDetailKey) {
			$this->recieved_by->setFormValue($objForm->GetValue("x_recieved_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->date_recieved->CurrentValue = $this->date_recieved->FormValue;
		$this->date_recieved->CurrentValue = ew_UnFormatDateTime($this->date_recieved->CurrentValue, 0);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->material_name->CurrentValue = $this->material_name->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->capacity->CurrentValue = $this->capacity->FormValue;
		$this->recieved_by->CurrentValue = $this->recieved_by->FormValue;
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
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['date_recieved'] = $this->date_recieved->CurrentValue;
		$row['reference_id'] = $this->reference_id->CurrentValue;
		$row['staff_id'] = $this->staff_id->CurrentValue;
		$row['material_name'] = $this->material_name->CurrentValue;
		$row['quantity'] = $this->quantity->CurrentValue;
		$row['type'] = $this->type->CurrentValue;
		$row['capacity'] = $this->capacity->CurrentValue;
		$row['recieved_by'] = $this->recieved_by->CurrentValue;
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->ViewValue = $this->date_recieved->CurrentValue;
		$this->date_recieved->ViewValue = ew_FormatDateTime($this->date_recieved->ViewValue, 0);
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
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

			// date_recieved
			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";
			$this->date_recieved->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// date_recieved
			$this->date_recieved->EditAttrs["class"] = "form-control";
			$this->date_recieved->EditCustomAttributes = "";
			$this->date_recieved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_recieved->CurrentValue, 8));
			$this->date_recieved->PlaceHolder = ew_RemoveHtml($this->date_recieved->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->CurrentValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->staff_id->EditValue = $this->staff_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// material_name
			$this->material_name->EditAttrs["class"] = "form-control";
			$this->material_name->EditCustomAttributes = "";
			$this->material_name->EditValue = ew_HtmlEncode($this->material_name->CurrentValue);
			$this->material_name->PlaceHolder = ew_RemoveHtml($this->material_name->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);
			$this->type->PlaceHolder = ew_RemoveHtml($this->type->FldCaption());

			// capacity
			$this->capacity->EditAttrs["class"] = "form-control";
			$this->capacity->EditCustomAttributes = "";
			$this->capacity->EditValue = ew_HtmlEncode($this->capacity->CurrentValue);
			$this->capacity->PlaceHolder = ew_RemoveHtml($this->capacity->FldCaption());

			// recieved_by
			$this->recieved_by->EditAttrs["class"] = "form-control";
			$this->recieved_by->EditCustomAttributes = "";
			$this->recieved_by->EditValue = ew_HtmlEncode($this->recieved_by->CurrentValue);
			if (strval($this->recieved_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->recieved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->recieved_by->EditValue = $this->recieved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->recieved_by->EditValue = ew_HtmlEncode($this->recieved_by->CurrentValue);
				}
			} else {
				$this->recieved_by->EditValue = NULL;
			}
			$this->recieved_by->PlaceHolder = ew_RemoveHtml($this->recieved_by->FldCaption());

			// Add refer script
			// date_recieved

			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";
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
		if (!$this->date_recieved->FldIsDetailKey && !is_null($this->date_recieved->FormValue) && $this->date_recieved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date_recieved->FldCaption(), $this->date_recieved->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date_recieved->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_recieved->FldErrMsg());
		}
		if (!$this->reference_id->FldIsDetailKey && !is_null($this->reference_id->FormValue) && $this->reference_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reference_id->FldCaption(), $this->reference_id->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->staff_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->staff_id->FldErrMsg());
		}
		if (!$this->material_name->FldIsDetailKey && !is_null($this->material_name->FormValue) && $this->material_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->material_name->FldCaption(), $this->material_name->ReqErrMsg));
		}
		if (!$this->quantity->FldIsDetailKey && !is_null($this->quantity->FormValue) && $this->quantity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity->FldCaption(), $this->quantity->ReqErrMsg));
		}
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type->FldCaption(), $this->type->ReqErrMsg));
		}
		if (!$this->capacity->FldIsDetailKey && !is_null($this->capacity->FormValue) && $this->capacity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->capacity->FldCaption(), $this->capacity->ReqErrMsg));
		}
		if (!$this->recieved_by->FldIsDetailKey && !is_null($this->recieved_by->FormValue) && $this->recieved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recieved_by->FldCaption(), $this->recieved_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->recieved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->recieved_by->FldErrMsg());
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

		// date_recieved
		$this->date_recieved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_recieved->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// reference_id
		$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, "", FALSE);

		// staff_id
		$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, 0, FALSE);

		// material_name
		$this->material_name->SetDbValueDef($rsnew, $this->material_name->CurrentValue, "", FALSE);

		// quantity
		$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, "", FALSE);

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, "", FALSE);

		// capacity
		$this->capacity->SetDbValueDef($rsnew, $this->capacity->CurrentValue, "", FALSE);

		// recieved_by
		$this->recieved_by->SetDbValueDef($rsnew, $this->recieved_by->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inventorylist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_recieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld` FROM `users`";
			$sWhereWrk = "`staffno` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_recieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->recieved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($inventory_add)) $inventory_add = new cinventory_add();

// Page init
$inventory_add->Page_Init();

// Page main
$inventory_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = finventoryadd = new ew_Form("finventoryadd", "add");

// Validate form
finventoryadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_date_recieved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->date_recieved->FldCaption(), $inventory->date_recieved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_recieved");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->date_recieved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reference_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->reference_id->FldCaption(), $inventory->reference_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->staff_id->FldCaption(), $inventory->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->staff_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_material_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->material_name->FldCaption(), $inventory->material_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->quantity->FldCaption(), $inventory->quantity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->type->FldCaption(), $inventory->type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_capacity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->capacity->FldCaption(), $inventory->capacity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recieved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->recieved_by->FldCaption(), $inventory->recieved_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recieved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->recieved_by->FldErrMsg()) ?>");

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
finventoryadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventoryadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventoryadd.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventoryadd.Lists["x_staff_id"].Data = "<?php echo $inventory_add->staff_id->LookupFilterQuery(FALSE, "add") ?>";
finventoryadd.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_add->staff_id->LookupFilterQuery(TRUE, "add"))) ?>;
finventoryadd.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventoryadd.Lists["x_recieved_by"].Data = "<?php echo $inventory_add->recieved_by->LookupFilterQuery(FALSE, "add") ?>";
finventoryadd.AutoSuggests["x_recieved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_add->recieved_by->LookupFilterQuery(TRUE, "add"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
$(document).ready(function(){
	$('#x_start_date').blur(function() {
		$("#x_end_date").val('');
		$("#x_duration").val('');
	});
	$('#x_end_date').blur(function() {
		var from1 = $('#x_start_date').val().split("/");
		var started = new Date(from1[2], from1[1] - 1, from1[0]);

		//
		var end1 = $('#x_end_date').val().split("/");
		var ended = new Date(end1[2], end1[1] - 1, end1[0]);
		var days1 = ((ended - started) / (1000 * 60 * 60 * 24)) + 1;
		$("#x_duration").val(days1.toFixed());

		//alert(days1.toFixed());
	});	
/****Hide and Show */

//Hide All fields
$("#r_duration").hide();
$("#r_no_of_people_involved").hide();
$("#r_incident_type").hide();
$("#r_start_date").hide();
$("#r_end_date").hide();
$("#r_category").hide("");
$("#r_sub_category").hide("");
$("#r_incident_location").hide("");
$("#r_incident_sub_location").hide("");
$("#r_amount_paid").hide("");
$("#r_sub_sub_category").hide("");
$("#r_incident_venue").hide("");
$("#r_selection_sub_category").hide("");
$("#x_departments").on("change", function() {
$('#x_departments').val()
var str = $("option:selected", this);
if (this.value == "") {

 //Hide All fields
 $("#r_duration").hide();
 $("#x_duration").val("");
 $("#r_no_of_people_involved").hide();
 $("#x_no_of_people_involved").val("");
 $("#r_incident_type").hide();
 $("#x_incident_type").val("");
 $("#r_start_date").hide();
 $("#x_start_date").val("");
 $("#r_end_date").hide("");
 $("#x_end_date").val("");
 $("#r_category").hide("");
 $("#x_category").val("");
 $("#r_sub_category").hide("");
 $("#x_sub_category").val("");
 $("#r_amount_paid").hide("");
 $("#x_amount_paid").val("");
 $("#r_incident_location").hide("");
 $("#x_incident_location").val("");
 $("#r_incident_sub_location").hide("");
 $("#x_incident_sub_location").val("");
 $("#r_incident_venue").hide("");
 $("#x_incident_venue").val("");
 $("#r_sub_sub_category").hide("");
 $("#x_sub_sub_category").val("");
 $("#r_selection_sub_category").hide("");
 $("#x_selection_sub_category").val("");
} else {

	//Show Only fields for Staff
	if (this.value == 6) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	}

	   //Show Only fields for Clients
	 if (this.value == 1) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
		$('#x_sub_category').val('');

		//$("#r_start_date").show();
		//$("#r_end_date").show();
		//$("#r_duration").show();  
		//$("#r_Amount").show(); 

	}

	//   else{
	// 	} 
	//Show and Hide fields for Grooming Center

	 if (this.value == 2) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	 }

	//Show and Hide fields for Grooming Center
	if (this.value == 3) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	 }

	//Show and Hide fields for Grooming Center
	if (this.value == 4) {
		$("#r_category").show();
		$("#r_sub_category").show();
		$("#r_sub_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
		$("#r_incident_location").show();
		$("#r_incident_venue").show();
		$("#r_incident_sub_location").show();
		$("#r_selection_sub_category").show();
		$("#r_incident_type").show();
		$("#r_no_of_people_involved").show();
	 }
	 if (this.value == 5) {
		$("#r_category").show();
		$("#r_sub_category").show();
		$("#r_sub_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
		$("#r_incident_location").show();
		$("#r_incident_venue").show();
		$("#r_incident_sub_location").show();
		$("#r_selection_sub_category").show();
	 }
	 if (this.value == 14) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	 }
	 if (this.value == 8) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	 }
	 if (this.value == 9) {
		$("#r_category").show();
		$("#r_sub_category").show();
		$("#r_sub_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
		$("#r_incident_location").show();
		$("#r_incident_venue").show();
		$("#r_incident_sub_location").show();
		$("#r_selection_sub_category").show();
	 }
	 if (this.value == 11) {
		$("#r_category").hide();			   
		$("#r_sub_category").hide();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	 }
	 if (this.value == 5) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	 }
	  if (this.value == 12) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	  }
	  if (this.value == 12) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
	  }
	}
  });

//   ===============================
/*$("#x_sub_category").on("change", function() { 
	var subCategory= $('#x_sub_category').val();

	//alert(subCategory);
	if(subCategory ==62){
		$("#r_start_date").show();
		$("#r_end_date").show();
		$("#r_duration").show();  
		$("#r_amount_paid").show();
	}else{
		$("#r_start_date").hide();
		$("#r_end_date").hide();
		$("#r_duration").hide();
		$("#r_amount_paid").hide();
	}
});*/

//   ===============================
$("#x_category").on("change", function() { 
	var Category= $('#x_category').val();

	//alert(subCategory);
	if(Category ==9){
		$("#r_start_date").show();
		$("#r_end_date").show();
		$("#r_duration").show();  
		$("#r_amount_paid").show();
	}else{
		$("#r_start_date").hide();
		$("#r_end_date").hide();
		$("#r_duration").hide();
		$("#r_amount_paid").hide();
	}
});
$("#x_sub_category").on("change", function() { 
	var subCategory= $('#x_sub_category').val();

	//alert(subCategory);
	if(subCategory ==10){
		$("#r_no_of_people_involved").show();
		$("#r_incident_type").show();

		//$("#r_duration").show();  
		//$("#r_amount_paid").show();

	}else{
		$("#r_incident_type").hide();
		$("#r_no_of_people_involved").hide();

		//$("#r_duration").hide();
		//$("#r_amount_paid").hide();

	}
});
})
</script>
<?php $inventory_add->ShowPageHeader(); ?>
<?php
$inventory_add->ShowMessage();
?>
<form name="finventoryadd" id="finventoryadd" class="<?php echo $inventory_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($inventory_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($inventory->date_recieved->Visible) { // date_recieved ?>
	<div id="r_date_recieved" class="form-group">
		<label id="elh_inventory_date_recieved" for="x_date_recieved" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->date_recieved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->date_recieved->CellAttributes() ?>>
<span id="el_inventory_date_recieved">
<input type="text" data-table="inventory" data-field="x_date_recieved" name="x_date_recieved" id="x_date_recieved" placeholder="<?php echo ew_HtmlEncode($inventory->date_recieved->getPlaceHolder()) ?>" value="<?php echo $inventory->date_recieved->EditValue ?>"<?php echo $inventory->date_recieved->EditAttributes() ?>>
</span>
<?php echo $inventory->date_recieved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_inventory_reference_id" for="x_reference_id" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->reference_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->reference_id->CellAttributes() ?>>
<span id="el_inventory_reference_id">
<input type="text" data-table="inventory" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->reference_id->getPlaceHolder()) ?>" value="<?php echo $inventory->reference_id->EditValue ?>"<?php echo $inventory->reference_id->EditAttributes() ?>>
</span>
<?php echo $inventory->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_inventory_staff_id" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->staff_id->CellAttributes() ?>>
<span id="el_inventory_staff_id">
<?php
$wrkonchange = trim(" " . @$inventory->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $inventory->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory->staff_id->getPlaceHolder()) ?>"<?php echo $inventory->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory" data-field="x_staff_id" data-value-separator="<?php echo $inventory->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($inventory->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventoryadd.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php echo $inventory->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label id="elh_inventory_material_name" for="x_material_name" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->material_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->material_name->CellAttributes() ?>>
<span id="el_inventory_material_name">
<input type="text" data-table="inventory" data-field="x_material_name" name="x_material_name" id="x_material_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->material_name->getPlaceHolder()) ?>" value="<?php echo $inventory->material_name->EditValue ?>"<?php echo $inventory->material_name->EditAttributes() ?>>
</span>
<?php echo $inventory->material_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label id="elh_inventory_quantity" for="x_quantity" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->quantity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->quantity->CellAttributes() ?>>
<span id="el_inventory_quantity">
<input type="text" data-table="inventory" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->quantity->getPlaceHolder()) ?>" value="<?php echo $inventory->quantity->EditValue ?>"<?php echo $inventory->quantity->EditAttributes() ?>>
</span>
<?php echo $inventory->quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_inventory_type" for="x_type" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->type->CellAttributes() ?>>
<span id="el_inventory_type">
<input type="text" data-table="inventory" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->type->getPlaceHolder()) ?>" value="<?php echo $inventory->type->EditValue ?>"<?php echo $inventory->type->EditAttributes() ?>>
</span>
<?php echo $inventory->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->capacity->Visible) { // capacity ?>
	<div id="r_capacity" class="form-group">
		<label id="elh_inventory_capacity" for="x_capacity" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->capacity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->capacity->CellAttributes() ?>>
<span id="el_inventory_capacity">
<input type="text" data-table="inventory" data-field="x_capacity" name="x_capacity" id="x_capacity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->capacity->getPlaceHolder()) ?>" value="<?php echo $inventory->capacity->EditValue ?>"<?php echo $inventory->capacity->EditAttributes() ?>>
</span>
<?php echo $inventory->capacity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->recieved_by->Visible) { // recieved_by ?>
	<div id="r_recieved_by" class="form-group">
		<label id="elh_inventory_recieved_by" class="<?php echo $inventory_add->LeftColumnClass ?>"><?php echo $inventory->recieved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_add->RightColumnClass ?>"><div<?php echo $inventory->recieved_by->CellAttributes() ?>>
<span id="el_inventory_recieved_by">
<?php
$wrkonchange = trim(" " . @$inventory->recieved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory->recieved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_recieved_by" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_recieved_by" id="sv_x_recieved_by" value="<?php echo $inventory->recieved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->recieved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory->recieved_by->getPlaceHolder()) ?>"<?php echo $inventory->recieved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory" data-field="x_recieved_by" data-value-separator="<?php echo $inventory->recieved_by->DisplayValueSeparatorAttribute() ?>" name="x_recieved_by" id="x_recieved_by" value="<?php echo ew_HtmlEncode($inventory->recieved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventoryadd.CreateAutoSuggest({"id":"x_recieved_by","forceSelect":false});
</script>
</span>
<?php echo $inventory->recieved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$inventory_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $inventory_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inventory_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finventoryadd.Init();
</script>
<?php
$inventory_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_staff_id").hide();
$("#r_recieved_by").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$inventory_add->Page_Terminate();
?>
