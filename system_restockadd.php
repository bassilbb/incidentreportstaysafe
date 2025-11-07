<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "system_restockinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$system_restock_add = NULL; // Initialize page object first

class csystem_restock_add extends csystem_restock {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'system_restock';

	// Page object name
	var $PageObjName = 'system_restock_add';

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

		// Table object (system_restock)
		if (!isset($GLOBALS["system_restock"]) || get_class($GLOBALS["system_restock"]) == "csystem_restock") {
			$GLOBALS["system_restock"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["system_restock"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'system_restock');

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
				$this->Page_Terminate(ew_GetUrl("system_restocklist.php"));
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
		$this->date_restocked->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity->SetVisibility();
		$this->restocked_by->SetVisibility();
		$this->statuss->SetVisibility();
		$this->restocked_action->SetVisibility();
		$this->restocked_comment->SetVisibility();
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
		global $EW_EXPORT, $system_restock;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($system_restock);
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
					if ($pageName == "system_restockview.php")
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
			if (@$_GET["code"] != "") {
				$this->code->setQueryStringValue($_GET["code"]);
				$this->setKey("code", $this->code->CurrentValue); // Set up key
			} else {
				$this->setKey("code", ""); // Clear key
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
					$this->Page_Terminate("system_restocklist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "system_restocklist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "system_restockview.php")
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
		$this->code->CurrentValue = NULL;
		$this->code->OldValue = $this->code->CurrentValue;
		$this->date_restocked->CurrentValue = NULL;
		$this->date_restocked->OldValue = $this->date_restocked->CurrentValue;
		$this->reference_id->CurrentValue = NULL;
		$this->reference_id->OldValue = $this->reference_id->CurrentValue;
		$this->material_name->CurrentValue = NULL;
		$this->material_name->OldValue = $this->material_name->CurrentValue;
		$this->quantity->CurrentValue = NULL;
		$this->quantity->OldValue = $this->quantity->CurrentValue;
		$this->restocked_by->CurrentValue = NULL;
		$this->restocked_by->OldValue = $this->restocked_by->CurrentValue;
		$this->statuss->CurrentValue = 0;
		$this->restocked_action->CurrentValue = NULL;
		$this->restocked_action->OldValue = $this->restocked_action->CurrentValue;
		$this->restocked_comment->CurrentValue = NULL;
		$this->restocked_comment->OldValue = $this->restocked_comment->CurrentValue;
		$this->approver_date->CurrentValue = NULL;
		$this->approver_date->OldValue = $this->approver_date->CurrentValue;
		$this->approver_action->CurrentValue = NULL;
		$this->approver_action->OldValue = $this->approver_action->CurrentValue;
		$this->approver_comment->CurrentValue = NULL;
		$this->approver_comment->OldValue = $this->approver_comment->CurrentValue;
		$this->approved_by->CurrentValue = NULL;
		$this->approved_by->OldValue = $this->approved_by->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->date_restocked->FldIsDetailKey) {
			$this->date_restocked->setFormValue($objForm->GetValue("x_date_restocked"));
			$this->date_restocked->CurrentValue = ew_UnFormatDateTime($this->date_restocked->CurrentValue, 0);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->material_name->FldIsDetailKey) {
			$this->material_name->setFormValue($objForm->GetValue("x_material_name"));
		}
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
		}
		if (!$this->restocked_by->FldIsDetailKey) {
			$this->restocked_by->setFormValue($objForm->GetValue("x_restocked_by"));
		}
		if (!$this->statuss->FldIsDetailKey) {
			$this->statuss->setFormValue($objForm->GetValue("x_statuss"));
		}
		if (!$this->restocked_action->FldIsDetailKey) {
			$this->restocked_action->setFormValue($objForm->GetValue("x_restocked_action"));
		}
		if (!$this->restocked_comment->FldIsDetailKey) {
			$this->restocked_comment->setFormValue($objForm->GetValue("x_restocked_comment"));
		}
		if (!$this->approver_date->FldIsDetailKey) {
			$this->approver_date->setFormValue($objForm->GetValue("x_approver_date"));
			$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		}
		if (!$this->approver_action->FldIsDetailKey) {
			$this->approver_action->setFormValue($objForm->GetValue("x_approver_action"));
		}
		if (!$this->approver_comment->FldIsDetailKey) {
			$this->approver_comment->setFormValue($objForm->GetValue("x_approver_comment"));
		}
		if (!$this->approved_by->FldIsDetailKey) {
			$this->approved_by->setFormValue($objForm->GetValue("x_approved_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->date_restocked->CurrentValue = $this->date_restocked->FormValue;
		$this->date_restocked->CurrentValue = ew_UnFormatDateTime($this->date_restocked->CurrentValue, 0);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->material_name->CurrentValue = $this->material_name->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->restocked_by->CurrentValue = $this->restocked_by->FormValue;
		$this->statuss->CurrentValue = $this->statuss->FormValue;
		$this->restocked_action->CurrentValue = $this->restocked_action->FormValue;
		$this->restocked_comment->CurrentValue = $this->restocked_comment->FormValue;
		$this->approver_date->CurrentValue = $this->approver_date->FormValue;
		$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		$this->approver_action->CurrentValue = $this->approver_action->FormValue;
		$this->approver_comment->CurrentValue = $this->approver_comment->FormValue;
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
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
		$this->code->setDbValue($row['code']);
		$this->date_restocked->setDbValue($row['date_restocked']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity->setDbValue($row['quantity']);
		$this->restocked_by->setDbValue($row['restocked_by']);
		$this->statuss->setDbValue($row['statuss']);
		$this->restocked_action->setDbValue($row['restocked_action']);
		$this->restocked_comment->setDbValue($row['restocked_comment']);
		$this->approver_date->setDbValue($row['approver_date']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['code'] = $this->code->CurrentValue;
		$row['date_restocked'] = $this->date_restocked->CurrentValue;
		$row['reference_id'] = $this->reference_id->CurrentValue;
		$row['material_name'] = $this->material_name->CurrentValue;
		$row['quantity'] = $this->quantity->CurrentValue;
		$row['restocked_by'] = $this->restocked_by->CurrentValue;
		$row['statuss'] = $this->statuss->CurrentValue;
		$row['restocked_action'] = $this->restocked_action->CurrentValue;
		$row['restocked_comment'] = $this->restocked_comment->CurrentValue;
		$row['approver_date'] = $this->approver_date->CurrentValue;
		$row['approver_action'] = $this->approver_action->CurrentValue;
		$row['approver_comment'] = $this->approver_comment->CurrentValue;
		$row['approved_by'] = $this->approved_by->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date_restocked->DbValue = $row['date_restocked'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity->DbValue = $row['quantity'];
		$this->restocked_by->DbValue = $row['restocked_by'];
		$this->statuss->DbValue = $row['statuss'];
		$this->restocked_action->DbValue = $row['restocked_action'];
		$this->restocked_comment->DbValue = $row['restocked_comment'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("code")) <> "")
			$this->code->CurrentValue = $this->getKey("code"); // code
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
		// code
		// date_restocked
		// reference_id
		// material_name
		// quantity
		// restocked_by
		// statuss
		// restocked_action
		// restocked_comment
		// approver_date
		// approver_action
		// approver_comment
		// approved_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_restocked
		$this->date_restocked->ViewValue = $this->date_restocked->CurrentValue;
		$this->date_restocked->ViewValue = ew_FormatDateTime($this->date_restocked->ViewValue, 0);
		$this->date_restocked->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_inventory`";
		$sWhereWrk = "";
		$this->material_name->LookupFilters = array("dx1" => '`material_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// restocked_by
		$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
		$this->restocked_by->ViewCustomAttributes = "";

		// statuss
		$this->statuss->ViewCustomAttributes = "";

		// restocked_action
		if (strval($this->restocked_action->CurrentValue) <> "") {
			$this->restocked_action->ViewValue = $this->restocked_action->OptionCaption($this->restocked_action->CurrentValue);
		} else {
			$this->restocked_action->ViewValue = NULL;
		}
		$this->restocked_action->ViewCustomAttributes = "";

		// restocked_comment
		$this->restocked_comment->ViewValue = $this->restocked_comment->CurrentValue;
		$this->restocked_comment->ViewCustomAttributes = "";

		// approver_date
		$this->approver_date->ViewValue = $this->approver_date->CurrentValue;
		$this->approver_date->ViewValue = ew_FormatDateTime($this->approver_date->ViewValue, 0);
		$this->approver_date->ViewCustomAttributes = "";

		// approver_action
		$this->approver_action->ViewValue = $this->approver_action->CurrentValue;
		$this->approver_action->ViewCustomAttributes = "";

		// approver_comment
		$this->approver_comment->ViewValue = $this->approver_comment->CurrentValue;
		$this->approver_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		$this->approved_by->ViewCustomAttributes = "";

			// date_restocked
			$this->date_restocked->LinkCustomAttributes = "";
			$this->date_restocked->HrefValue = "";
			$this->date_restocked->TooltipValue = "";

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

			// restocked_by
			$this->restocked_by->LinkCustomAttributes = "";
			$this->restocked_by->HrefValue = "";
			$this->restocked_by->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

			// restocked_action
			$this->restocked_action->LinkCustomAttributes = "";
			$this->restocked_action->HrefValue = "";
			$this->restocked_action->TooltipValue = "";

			// restocked_comment
			$this->restocked_comment->LinkCustomAttributes = "";
			$this->restocked_comment->HrefValue = "";
			$this->restocked_comment->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// date_restocked
			$this->date_restocked->EditAttrs["class"] = "form-control";
			$this->date_restocked->EditCustomAttributes = "";
			$this->date_restocked->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_restocked->CurrentValue, 8));
			$this->date_restocked->PlaceHolder = ew_RemoveHtml($this->date_restocked->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->CurrentValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// material_name
			$this->material_name->EditCustomAttributes = "";
			if (trim(strval($this->material_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `system_inventory`";
			$sWhereWrk = "";
			$this->material_name->LookupFilters = array("dx1" => '`material_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->material_name->ViewValue = $this->material_name->DisplayValue($arwrk);
			} else {
				$this->material_name->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->material_name->EditValue = $arwrk;

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// restocked_by
			$this->restocked_by->EditAttrs["class"] = "form-control";
			$this->restocked_by->EditCustomAttributes = "";
			$this->restocked_by->EditValue = ew_HtmlEncode($this->restocked_by->CurrentValue);
			$this->restocked_by->PlaceHolder = ew_RemoveHtml($this->restocked_by->FldCaption());

			// statuss
			$this->statuss->EditAttrs["class"] = "form-control";
			$this->statuss->EditCustomAttributes = "";

			// restocked_action
			$this->restocked_action->EditCustomAttributes = "";
			$this->restocked_action->EditValue = $this->restocked_action->Options(FALSE);

			// restocked_comment
			$this->restocked_comment->EditAttrs["class"] = "form-control";
			$this->restocked_comment->EditCustomAttributes = "";
			$this->restocked_comment->EditValue = ew_HtmlEncode($this->restocked_comment->CurrentValue);
			$this->restocked_comment->PlaceHolder = ew_RemoveHtml($this->restocked_comment->FldCaption());

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->approver_date->CurrentValue, 8));
			$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

			// approver_action
			$this->approver_action->EditAttrs["class"] = "form-control";
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = ew_HtmlEncode($this->approver_action->CurrentValue);
			$this->approver_action->PlaceHolder = ew_RemoveHtml($this->approver_action->FldCaption());

			// approver_comment
			$this->approver_comment->EditAttrs["class"] = "form-control";
			$this->approver_comment->EditCustomAttributes = "";
			$this->approver_comment->EditValue = ew_HtmlEncode($this->approver_comment->CurrentValue);
			$this->approver_comment->PlaceHolder = ew_RemoveHtml($this->approver_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// Add refer script
			// date_restocked

			$this->date_restocked->LinkCustomAttributes = "";
			$this->date_restocked->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";

			// restocked_by
			$this->restocked_by->LinkCustomAttributes = "";
			$this->restocked_by->HrefValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";

			// restocked_action
			$this->restocked_action->LinkCustomAttributes = "";
			$this->restocked_action->HrefValue = "";

			// restocked_comment
			$this->restocked_comment->LinkCustomAttributes = "";
			$this->restocked_comment->HrefValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";

			// approver_comment
			$this->approver_comment->LinkCustomAttributes = "";
			$this->approver_comment->HrefValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
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
		if (!ew_CheckDateDef($this->date_restocked->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_restocked->FldErrMsg());
		}
		if (!$this->material_name->FldIsDetailKey && !is_null($this->material_name->FormValue) && $this->material_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->material_name->FldCaption(), $this->material_name->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->restocked_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->restocked_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->approver_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->approver_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approver_action->FormValue)) {
			ew_AddMessage($gsFormError, $this->approver_action->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_by->FldErrMsg());
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

		// date_restocked
		$this->date_restocked->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_restocked->CurrentValue, 0), NULL, FALSE);

		// reference_id
		$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, NULL, FALSE);

		// material_name
		$this->material_name->SetDbValueDef($rsnew, $this->material_name->CurrentValue, NULL, FALSE);

		// quantity
		$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, NULL, FALSE);

		// restocked_by
		$this->restocked_by->SetDbValueDef($rsnew, $this->restocked_by->CurrentValue, NULL, FALSE);

		// statuss
		$this->statuss->SetDbValueDef($rsnew, $this->statuss->CurrentValue, NULL, FALSE);

		// restocked_action
		$this->restocked_action->SetDbValueDef($rsnew, $this->restocked_action->CurrentValue, NULL, FALSE);

		// restocked_comment
		$this->restocked_comment->SetDbValueDef($rsnew, $this->restocked_comment->CurrentValue, NULL, FALSE);

		// approver_date
		$this->approver_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->approver_date->CurrentValue, 0), NULL, FALSE);

		// approver_action
		$this->approver_action->SetDbValueDef($rsnew, $this->approver_action->CurrentValue, NULL, FALSE);

		// approver_comment
		$this->approver_comment->SetDbValueDef($rsnew, $this->approver_comment->CurrentValue, NULL, FALSE);

		// approved_by
		$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("system_restocklist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_material_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_inventory`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`material_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
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
			ew_SetClientVar("GetSystem_stockDetailsSearchModel", ew_Encrypt("SELECT `quantity`,`type`,`capacity` FROM `system_inventory` WHERE `id`= {query_value}"));
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
if (!isset($system_restock_add)) $system_restock_add = new csystem_restock_add();

// Page init
$system_restock_add->Page_Init();

// Page main
$system_restock_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$system_restock_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fsystem_restockadd = new ew_Form("fsystem_restockadd", "add");

// Validate form
fsystem_restockadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_date_restocked");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($system_restock->date_restocked->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_material_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $system_restock->material_name->FldCaption(), $system_restock->material_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_restocked_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($system_restock->restocked_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approver_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($system_restock->approver_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approver_action");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($system_restock->approver_action->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($system_restock->approved_by->FldErrMsg()) ?>");

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
fsystem_restockadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsystem_restockadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fsystem_restockadd.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"system_inventory"};
fsystem_restockadd.Lists["x_material_name"].Data = "<?php echo $system_restock_add->material_name->LookupFilterQuery(FALSE, "add") ?>";
fsystem_restockadd.Lists["x_restocked_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsystem_restockadd.Lists["x_restocked_action"].Options = <?php echo json_encode($system_restock_add->restocked_action->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
$(document).ready(function(){
	var system_inventory_quantity;
	$("#x_material_name").on("change", function() { 
	   var StoreId = this.value;

	//    alert('Hello')
		   if(StoreId !=''){

			 //alert(StoreId);
			 var resultSearchModel = ew_Ajax(ewVar.GetSystem_stockDetailsSearchModel, StoreId);   

		   //alert(resultSearchModel);
			 if(resultSearchModel !=''){

			// alert(resultSearchModel);
				//$('#x_stock_balance').val(resultSearchModel[0]);
				//$('#x_type').val(resultSearchModel[1]);
				//$('#x_capacity').val(resultSearchModel[2]);
				//$('#x_capacity').val(resultSearchModel[2]);

				}
				}else{

					//$('#x_stock_balance').val('');
					$('#x_quantity').val('');

					//$('#x_type').val('');
					//$('#x_capacity').val('');

				}
			})
		})	
</script>
<?php $system_restock_add->ShowPageHeader(); ?>
<?php
$system_restock_add->ShowMessage();
?>
<form name="fsystem_restockadd" id="fsystem_restockadd" class="<?php echo $system_restock_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($system_restock_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $system_restock_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="system_restock">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($system_restock_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($system_restock->date_restocked->Visible) { // date_restocked ?>
	<div id="r_date_restocked" class="form-group">
		<label id="elh_system_restock_date_restocked" for="x_date_restocked" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->date_restocked->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->date_restocked->CellAttributes() ?>>
<span id="el_system_restock_date_restocked">
<input type="text" data-table="system_restock" data-field="x_date_restocked" name="x_date_restocked" id="x_date_restocked" size="30" placeholder="<?php echo ew_HtmlEncode($system_restock->date_restocked->getPlaceHolder()) ?>" value="<?php echo $system_restock->date_restocked->EditValue ?>"<?php echo $system_restock->date_restocked->EditAttributes() ?>>
</span>
<?php echo $system_restock->date_restocked->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_system_restock_reference_id" for="x_reference_id" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->reference_id->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->reference_id->CellAttributes() ?>>
<span id="el_system_restock_reference_id">
<input type="text" data-table="system_restock" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($system_restock->reference_id->getPlaceHolder()) ?>" value="<?php echo $system_restock->reference_id->EditValue ?>"<?php echo $system_restock->reference_id->EditAttributes() ?>>
</span>
<?php echo $system_restock->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label id="elh_system_restock_material_name" for="x_material_name" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->material_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->material_name->CellAttributes() ?>>
<span id="el_system_restock_material_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_material_name"><?php echo (strval($system_restock->material_name->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $system_restock->material_name->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($system_restock->material_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_material_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($system_restock->material_name->ReadOnly || $system_restock->material_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="system_restock" data-field="x_material_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $system_restock->material_name->DisplayValueSeparatorAttribute() ?>" name="x_material_name" id="x_material_name" value="<?php echo $system_restock->material_name->CurrentValue ?>"<?php echo $system_restock->material_name->EditAttributes() ?>>
</span>
<?php echo $system_restock->material_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label id="elh_system_restock_quantity" for="x_quantity" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->quantity->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->quantity->CellAttributes() ?>>
<span id="el_system_restock_quantity">
<input type="text" data-table="system_restock" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($system_restock->quantity->getPlaceHolder()) ?>" value="<?php echo $system_restock->quantity->EditValue ?>"<?php echo $system_restock->quantity->EditAttributes() ?>>
</span>
<?php echo $system_restock->quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->restocked_by->Visible) { // restocked_by ?>
	<div id="r_restocked_by" class="form-group">
		<label id="elh_system_restock_restocked_by" for="x_restocked_by" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->restocked_by->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->restocked_by->CellAttributes() ?>>
<span id="el_system_restock_restocked_by">
<input type="text" data-table="system_restock" data-field="x_restocked_by" name="x_restocked_by" id="x_restocked_by" size="30" placeholder="<?php echo ew_HtmlEncode($system_restock->restocked_by->getPlaceHolder()) ?>" value="<?php echo $system_restock->restocked_by->EditValue ?>"<?php echo $system_restock->restocked_by->EditAttributes() ?>>
</span>
<?php echo $system_restock->restocked_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label id="elh_system_restock_statuss" for="x_statuss" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->statuss->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->statuss->CellAttributes() ?>>
<span id="el_system_restock_statuss">
<select data-table="system_restock" data-field="x_statuss" data-value-separator="<?php echo $system_restock->statuss->DisplayValueSeparatorAttribute() ?>" id="x_statuss" name="x_statuss"<?php echo $system_restock->statuss->EditAttributes() ?>>
<?php echo $system_restock->statuss->SelectOptionListHtml("x_statuss") ?>
</select>
</span>
<?php echo $system_restock->statuss->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->restocked_action->Visible) { // restocked_action ?>
	<div id="r_restocked_action" class="form-group">
		<label id="elh_system_restock_restocked_action" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->restocked_action->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->restocked_action->CellAttributes() ?>>
<span id="el_system_restock_restocked_action">
<div id="tp_x_restocked_action" class="ewTemplate"><input type="radio" data-table="system_restock" data-field="x_restocked_action" data-value-separator="<?php echo $system_restock->restocked_action->DisplayValueSeparatorAttribute() ?>" name="x_restocked_action" id="x_restocked_action" value="{value}"<?php echo $system_restock->restocked_action->EditAttributes() ?>></div>
<div id="dsl_x_restocked_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $system_restock->restocked_action->RadioButtonListHtml(FALSE, "x_restocked_action") ?>
</div></div>
</span>
<?php echo $system_restock->restocked_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->restocked_comment->Visible) { // restocked_comment ?>
	<div id="r_restocked_comment" class="form-group">
		<label id="elh_system_restock_restocked_comment" for="x_restocked_comment" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->restocked_comment->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->restocked_comment->CellAttributes() ?>>
<span id="el_system_restock_restocked_comment">
<textarea data-table="system_restock" data-field="x_restocked_comment" name="x_restocked_comment" id="x_restocked_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($system_restock->restocked_comment->getPlaceHolder()) ?>"<?php echo $system_restock->restocked_comment->EditAttributes() ?>><?php echo $system_restock->restocked_comment->EditValue ?></textarea>
</span>
<?php echo $system_restock->restocked_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label id="elh_system_restock_approver_date" for="x_approver_date" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->approver_date->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->approver_date->CellAttributes() ?>>
<span id="el_system_restock_approver_date">
<input type="text" data-table="system_restock" data-field="x_approver_date" name="x_approver_date" id="x_approver_date" placeholder="<?php echo ew_HtmlEncode($system_restock->approver_date->getPlaceHolder()) ?>" value="<?php echo $system_restock->approver_date->EditValue ?>"<?php echo $system_restock->approver_date->EditAttributes() ?>>
</span>
<?php echo $system_restock->approver_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_system_restock_approver_action" for="x_approver_action" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->approver_action->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->approver_action->CellAttributes() ?>>
<span id="el_system_restock_approver_action">
<input type="text" data-table="system_restock" data-field="x_approver_action" name="x_approver_action" id="x_approver_action" size="30" placeholder="<?php echo ew_HtmlEncode($system_restock->approver_action->getPlaceHolder()) ?>" value="<?php echo $system_restock->approver_action->EditValue ?>"<?php echo $system_restock->approver_action->EditAttributes() ?>>
</span>
<?php echo $system_restock->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label id="elh_system_restock_approver_comment" for="x_approver_comment" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->approver_comment->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->approver_comment->CellAttributes() ?>>
<span id="el_system_restock_approver_comment">
<input type="text" data-table="system_restock" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($system_restock->approver_comment->getPlaceHolder()) ?>" value="<?php echo $system_restock->approver_comment->EditValue ?>"<?php echo $system_restock->approver_comment->EditAttributes() ?>>
</span>
<?php echo $system_restock->approver_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($system_restock->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_system_restock_approved_by" for="x_approved_by" class="<?php echo $system_restock_add->LeftColumnClass ?>"><?php echo $system_restock->approved_by->FldCaption() ?></label>
		<div class="<?php echo $system_restock_add->RightColumnClass ?>"><div<?php echo $system_restock->approved_by->CellAttributes() ?>>
<span id="el_system_restock_approved_by">
<input type="text" data-table="system_restock" data-field="x_approved_by" name="x_approved_by" id="x_approved_by" size="30" placeholder="<?php echo ew_HtmlEncode($system_restock->approved_by->getPlaceHolder()) ?>" value="<?php echo $system_restock->approved_by->EditValue ?>"<?php echo $system_restock->approved_by->EditAttributes() ?>>
</span>
<?php echo $system_restock->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$system_restock_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $system_restock_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $system_restock_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fsystem_restockadd.Init();
</script>
<?php
$system_restock_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_restocked_by").hide();
$('#r_approved_by').hide();

//$('#r_verified_by').hide();
$('#r_statuss').hide();
</script>
<?php include_once "footer.php" ?>
<?php
$system_restock_add->Page_Terminate();
?>
