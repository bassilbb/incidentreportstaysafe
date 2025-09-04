<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "restock_staysafe_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$restock_staysafe_report_add = NULL; // Initialize page object first

class crestock_staysafe_report_add extends crestock_staysafe_report {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'restock_staysafe_report';

	// Page object name
	var $PageObjName = 'restock_staysafe_report_add';

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

		// Table object (restock_staysafe_report)
		if (!isset($GLOBALS["restock_staysafe_report"]) || get_class($GLOBALS["restock_staysafe_report"]) == "crestock_staysafe_report") {
			$GLOBALS["restock_staysafe_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["restock_staysafe_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'restock_staysafe_report');

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
				$this->Page_Terminate(ew_GetUrl("restock_staysafe_reportlist.php"));
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
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->stock_balance->SetVisibility();
		$this->quantity->SetVisibility();
		$this->statuss->SetVisibility();
		$this->restocked_action->SetVisibility();
		$this->restocked_comment->SetVisibility();
		$this->restocked_by->SetVisibility();
		$this->approver_date->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approver_comment->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->verified_date->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
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
		global $EW_EXPORT, $restock_staysafe_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($restock_staysafe_report);
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
					if ($pageName == "restock_staysafe_reportview.php")
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
					$this->Page_Terminate("restock_staysafe_reportlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "restock_staysafe_reportlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "restock_staysafe_reportview.php")
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
		$this->type->CurrentValue = NULL;
		$this->type->OldValue = $this->type->CurrentValue;
		$this->capacity->CurrentValue = NULL;
		$this->capacity->OldValue = $this->capacity->CurrentValue;
		$this->stock_balance->CurrentValue = NULL;
		$this->stock_balance->OldValue = $this->stock_balance->CurrentValue;
		$this->quantity->CurrentValue = NULL;
		$this->quantity->OldValue = $this->quantity->CurrentValue;
		$this->statuss->CurrentValue = NULL;
		$this->statuss->OldValue = $this->statuss->CurrentValue;
		$this->restocked_action->CurrentValue = NULL;
		$this->restocked_action->OldValue = $this->restocked_action->CurrentValue;
		$this->restocked_comment->CurrentValue = NULL;
		$this->restocked_comment->OldValue = $this->restocked_comment->CurrentValue;
		$this->restocked_by->CurrentValue = NULL;
		$this->restocked_by->OldValue = $this->restocked_by->CurrentValue;
		$this->approver_date->CurrentValue = NULL;
		$this->approver_date->OldValue = $this->approver_date->CurrentValue;
		$this->approver_action->CurrentValue = NULL;
		$this->approver_action->OldValue = $this->approver_action->CurrentValue;
		$this->approver_comment->CurrentValue = NULL;
		$this->approver_comment->OldValue = $this->approver_comment->CurrentValue;
		$this->approved_by->CurrentValue = NULL;
		$this->approved_by->OldValue = $this->approved_by->CurrentValue;
		$this->verified_date->CurrentValue = NULL;
		$this->verified_date->OldValue = $this->verified_date->CurrentValue;
		$this->verified_action->CurrentValue = NULL;
		$this->verified_action->OldValue = $this->verified_action->CurrentValue;
		$this->verified_comment->CurrentValue = NULL;
		$this->verified_comment->OldValue = $this->verified_comment->CurrentValue;
		$this->verified_by->CurrentValue = NULL;
		$this->verified_by->OldValue = $this->verified_by->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->date_restocked->FldIsDetailKey) {
			$this->date_restocked->setFormValue($objForm->GetValue("x_date_restocked"));
			$this->date_restocked->CurrentValue = ew_UnFormatDateTime($this->date_restocked->CurrentValue, 14);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->material_name->FldIsDetailKey) {
			$this->material_name->setFormValue($objForm->GetValue("x_material_name"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->capacity->FldIsDetailKey) {
			$this->capacity->setFormValue($objForm->GetValue("x_capacity"));
		}
		if (!$this->stock_balance->FldIsDetailKey) {
			$this->stock_balance->setFormValue($objForm->GetValue("x_stock_balance"));
		}
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
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
		if (!$this->restocked_by->FldIsDetailKey) {
			$this->restocked_by->setFormValue($objForm->GetValue("x_restocked_by"));
		}
		if (!$this->approver_date->FldIsDetailKey) {
			$this->approver_date->setFormValue($objForm->GetValue("x_approver_date"));
			$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 14);
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
		if (!$this->verified_date->FldIsDetailKey) {
			$this->verified_date->setFormValue($objForm->GetValue("x_verified_date"));
			$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 14);
		}
		if (!$this->verified_action->FldIsDetailKey) {
			$this->verified_action->setFormValue($objForm->GetValue("x_verified_action"));
		}
		if (!$this->verified_comment->FldIsDetailKey) {
			$this->verified_comment->setFormValue($objForm->GetValue("x_verified_comment"));
		}
		if (!$this->verified_by->FldIsDetailKey) {
			$this->verified_by->setFormValue($objForm->GetValue("x_verified_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->date_restocked->CurrentValue = $this->date_restocked->FormValue;
		$this->date_restocked->CurrentValue = ew_UnFormatDateTime($this->date_restocked->CurrentValue, 14);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->material_name->CurrentValue = $this->material_name->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->capacity->CurrentValue = $this->capacity->FormValue;
		$this->stock_balance->CurrentValue = $this->stock_balance->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->statuss->CurrentValue = $this->statuss->FormValue;
		$this->restocked_action->CurrentValue = $this->restocked_action->FormValue;
		$this->restocked_comment->CurrentValue = $this->restocked_comment->FormValue;
		$this->restocked_by->CurrentValue = $this->restocked_by->FormValue;
		$this->approver_date->CurrentValue = $this->approver_date->FormValue;
		$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 14);
		$this->approver_action->CurrentValue = $this->approver_action->FormValue;
		$this->approver_comment->CurrentValue = $this->approver_comment->FormValue;
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->verified_date->CurrentValue = $this->verified_date->FormValue;
		$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 14);
		$this->verified_action->CurrentValue = $this->verified_action->FormValue;
		$this->verified_comment->CurrentValue = $this->verified_comment->FormValue;
		$this->verified_by->CurrentValue = $this->verified_by->FormValue;
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
		$this->type->setDbValue($row['type']);
		$this->capacity->setDbValue($row['capacity']);
		$this->stock_balance->setDbValue($row['stock_balance']);
		$this->quantity->setDbValue($row['quantity']);
		$this->statuss->setDbValue($row['statuss']);
		$this->restocked_action->setDbValue($row['restocked_action']);
		$this->restocked_comment->setDbValue($row['restocked_comment']);
		$this->restocked_by->setDbValue($row['restocked_by']);
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
		$this->LoadDefaultValues();
		$row = array();
		$row['code'] = $this->code->CurrentValue;
		$row['date_restocked'] = $this->date_restocked->CurrentValue;
		$row['reference_id'] = $this->reference_id->CurrentValue;
		$row['material_name'] = $this->material_name->CurrentValue;
		$row['type'] = $this->type->CurrentValue;
		$row['capacity'] = $this->capacity->CurrentValue;
		$row['stock_balance'] = $this->stock_balance->CurrentValue;
		$row['quantity'] = $this->quantity->CurrentValue;
		$row['statuss'] = $this->statuss->CurrentValue;
		$row['restocked_action'] = $this->restocked_action->CurrentValue;
		$row['restocked_comment'] = $this->restocked_comment->CurrentValue;
		$row['restocked_by'] = $this->restocked_by->CurrentValue;
		$row['approver_date'] = $this->approver_date->CurrentValue;
		$row['approver_action'] = $this->approver_action->CurrentValue;
		$row['approver_comment'] = $this->approver_comment->CurrentValue;
		$row['approved_by'] = $this->approved_by->CurrentValue;
		$row['verified_date'] = $this->verified_date->CurrentValue;
		$row['verified_action'] = $this->verified_action->CurrentValue;
		$row['verified_comment'] = $this->verified_comment->CurrentValue;
		$row['verified_by'] = $this->verified_by->CurrentValue;
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
		$this->type->DbValue = $row['type'];
		$this->capacity->DbValue = $row['capacity'];
		$this->stock_balance->DbValue = $row['stock_balance'];
		$this->quantity->DbValue = $row['quantity'];
		$this->statuss->DbValue = $row['statuss'];
		$this->restocked_action->DbValue = $row['restocked_action'];
		$this->restocked_comment->DbValue = $row['restocked_comment'];
		$this->restocked_by->DbValue = $row['restocked_by'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_date->DbValue = $row['verified_date'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
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
		// type
		// capacity
		// stock_balance
		// quantity
		// statuss
		// restocked_action
		// restocked_comment
		// restocked_by
		// approver_date
		// approver_action
		// approver_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_restocked
		$this->date_restocked->ViewValue = $this->date_restocked->CurrentValue;
		$this->date_restocked->ViewValue = ew_FormatDateTime($this->date_restocked->ViewValue, 14);
		$this->date_restocked->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
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

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// stock_balance
		$this->stock_balance->ViewValue = $this->stock_balance->CurrentValue;
		$this->stock_balance->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

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

		// restocked_by
		$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
		if (strval($this->restocked_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->restocked_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->restocked_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->restocked_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->restocked_by->ViewValue = $this->restocked_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
			}
		} else {
			$this->restocked_by->ViewValue = NULL;
		}
		$this->restocked_by->ViewCustomAttributes = "";

		// approver_date
		$this->approver_date->ViewValue = $this->approver_date->CurrentValue;
		$this->approver_date->ViewValue = ew_FormatDateTime($this->approver_date->ViewValue, 14);
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
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 14);
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

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";
			$this->capacity->TooltipValue = "";

			// stock_balance
			$this->stock_balance->LinkCustomAttributes = "";
			$this->stock_balance->HrefValue = "";
			$this->stock_balance->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

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

			// restocked_by
			$this->restocked_by->LinkCustomAttributes = "";
			$this->restocked_by->HrefValue = "";
			$this->restocked_by->TooltipValue = "";

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

			// verified_date
			$this->verified_date->LinkCustomAttributes = "";
			$this->verified_date->HrefValue = "";
			$this->verified_date->TooltipValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";
			$this->verified_action->TooltipValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";
			$this->verified_comment->TooltipValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
			$this->verified_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// date_restocked
			$this->date_restocked->EditAttrs["class"] = "form-control";
			$this->date_restocked->EditCustomAttributes = "";
			$this->date_restocked->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_restocked->CurrentValue, 14));
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
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `inventory_staysafe`";
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

			// stock_balance
			$this->stock_balance->EditAttrs["class"] = "form-control";
			$this->stock_balance->EditCustomAttributes = "";
			$this->stock_balance->EditValue = ew_HtmlEncode($this->stock_balance->CurrentValue);
			$this->stock_balance->PlaceHolder = ew_RemoveHtml($this->stock_balance->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// statuss
			$this->statuss->EditAttrs["class"] = "form-control";
			$this->statuss->EditCustomAttributes = "";
			if (trim(strval($this->statuss->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `statuss`";
			$sWhereWrk = "";
			$this->statuss->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->statuss->EditValue = $arwrk;

			// restocked_action
			$this->restocked_action->EditCustomAttributes = "";
			$this->restocked_action->EditValue = $this->restocked_action->Options(FALSE);

			// restocked_comment
			$this->restocked_comment->EditAttrs["class"] = "form-control";
			$this->restocked_comment->EditCustomAttributes = "";
			$this->restocked_comment->EditValue = ew_HtmlEncode($this->restocked_comment->CurrentValue);
			$this->restocked_comment->PlaceHolder = ew_RemoveHtml($this->restocked_comment->FldCaption());

			// restocked_by
			$this->restocked_by->EditAttrs["class"] = "form-control";
			$this->restocked_by->EditCustomAttributes = "";
			$this->restocked_by->EditValue = ew_HtmlEncode($this->restocked_by->CurrentValue);
			if (strval($this->restocked_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->restocked_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->restocked_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->restocked_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->restocked_by->EditValue = $this->restocked_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->restocked_by->EditValue = ew_HtmlEncode($this->restocked_by->CurrentValue);
				}
			} else {
				$this->restocked_by->EditValue = NULL;
			}
			$this->restocked_by->PlaceHolder = ew_RemoveHtml($this->restocked_by->FldCaption());

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->approver_date->CurrentValue, 14));
			$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

			// approver_action
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

			// approver_comment
			$this->approver_comment->EditAttrs["class"] = "form-control";
			$this->approver_comment->EditCustomAttributes = "";
			$this->approver_comment->EditValue = ew_HtmlEncode($this->approver_comment->CurrentValue);
			$this->approver_comment->PlaceHolder = ew_RemoveHtml($this->approver_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->approved_by->EditValue = $this->approved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// verified_date
			$this->verified_date->EditAttrs["class"] = "form-control";
			$this->verified_date->EditCustomAttributes = "";
			$this->verified_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->verified_date->CurrentValue, 14));
			$this->verified_date->PlaceHolder = ew_RemoveHtml($this->verified_date->FldCaption());

			// verified_action
			$this->verified_action->EditCustomAttributes = "";
			$this->verified_action->EditValue = $this->verified_action->Options(FALSE);

			// verified_comment
			$this->verified_comment->EditAttrs["class"] = "form-control";
			$this->verified_comment->EditCustomAttributes = "";
			$this->verified_comment->EditValue = ew_HtmlEncode($this->verified_comment->CurrentValue);
			$this->verified_comment->PlaceHolder = ew_RemoveHtml($this->verified_comment->FldCaption());

			// verified_by
			$this->verified_by->EditAttrs["class"] = "form-control";
			$this->verified_by->EditCustomAttributes = "";
			$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->CurrentValue);
			if (strval($this->verified_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->verified_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->verified_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->verified_by->EditValue = $this->verified_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->CurrentValue);
				}
			} else {
				$this->verified_by->EditValue = NULL;
			}
			$this->verified_by->PlaceHolder = ew_RemoveHtml($this->verified_by->FldCaption());

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

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";

			// stock_balance
			$this->stock_balance->LinkCustomAttributes = "";
			$this->stock_balance->HrefValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";

			// restocked_action
			$this->restocked_action->LinkCustomAttributes = "";
			$this->restocked_action->HrefValue = "";

			// restocked_comment
			$this->restocked_comment->LinkCustomAttributes = "";
			$this->restocked_comment->HrefValue = "";

			// restocked_by
			$this->restocked_by->LinkCustomAttributes = "";
			$this->restocked_by->HrefValue = "";

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

			// verified_date
			$this->verified_date->LinkCustomAttributes = "";
			$this->verified_date->HrefValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
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
		if (!ew_CheckInteger($this->approved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckInteger($this->verified_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_by->FldErrMsg());
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
		$this->date_restocked->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_restocked->CurrentValue, 14), NULL, FALSE);

		// reference_id
		$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, NULL, FALSE);

		// material_name
		$this->material_name->SetDbValueDef($rsnew, $this->material_name->CurrentValue, NULL, FALSE);

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, FALSE);

		// capacity
		$this->capacity->SetDbValueDef($rsnew, $this->capacity->CurrentValue, NULL, FALSE);

		// stock_balance
		$this->stock_balance->SetDbValueDef($rsnew, $this->stock_balance->CurrentValue, NULL, FALSE);

		// quantity
		$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, NULL, FALSE);

		// statuss
		$this->statuss->SetDbValueDef($rsnew, $this->statuss->CurrentValue, NULL, FALSE);

		// restocked_action
		$this->restocked_action->SetDbValueDef($rsnew, $this->restocked_action->CurrentValue, NULL, FALSE);

		// restocked_comment
		$this->restocked_comment->SetDbValueDef($rsnew, $this->restocked_comment->CurrentValue, NULL, FALSE);

		// restocked_by
		$this->restocked_by->SetDbValueDef($rsnew, $this->restocked_by->CurrentValue, NULL, FALSE);

		// approver_date
		$this->approver_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->approver_date->CurrentValue, 14), NULL, FALSE);

		// approver_action
		$this->approver_action->SetDbValueDef($rsnew, $this->approver_action->CurrentValue, NULL, FALSE);

		// approver_comment
		$this->approver_comment->SetDbValueDef($rsnew, $this->approver_comment->CurrentValue, NULL, FALSE);

		// approved_by
		$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, FALSE);

		// verified_date
		$this->verified_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->verified_date->CurrentValue, 14), NULL, FALSE);

		// verified_action
		$this->verified_action->SetDbValueDef($rsnew, $this->verified_action->CurrentValue, NULL, FALSE);

		// verified_comment
		$this->verified_comment->SetDbValueDef($rsnew, $this->verified_comment->CurrentValue, NULL, FALSE);

		// verified_by
		$this->verified_by->SetDbValueDef($rsnew, $this->verified_by->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("restock_staysafe_reportlist.php"), "", $this->TableVar, TRUE);
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
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`material_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_statuss":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_restocked_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->restocked_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_verified_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_restocked_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->restocked_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->restocked_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->approved_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->approved_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_verified_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->verified_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->verified_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($restock_staysafe_report_add)) $restock_staysafe_report_add = new crestock_staysafe_report_add();

// Page init
$restock_staysafe_report_add->Page_Init();

// Page main
$restock_staysafe_report_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$restock_staysafe_report_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = frestock_staysafe_reportadd = new ew_Form("frestock_staysafe_reportadd", "add");

// Validate form
frestock_staysafe_reportadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($restock_staysafe_report->approved_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($restock_staysafe_report->verified_by->FldErrMsg()) ?>");

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
frestock_staysafe_reportadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frestock_staysafe_reportadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frestock_staysafe_reportadd.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory_staysafe"};
frestock_staysafe_reportadd.Lists["x_material_name"].Data = "<?php echo $restock_staysafe_report_add->material_name->LookupFilterQuery(FALSE, "add") ?>";
frestock_staysafe_reportadd.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
frestock_staysafe_reportadd.Lists["x_statuss"].Data = "<?php echo $restock_staysafe_report_add->statuss->LookupFilterQuery(FALSE, "add") ?>";
frestock_staysafe_reportadd.Lists["x_restocked_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frestock_staysafe_reportadd.Lists["x_restocked_action"].Options = <?php echo json_encode($restock_staysafe_report_add->restocked_action->Options()) ?>;
frestock_staysafe_reportadd.Lists["x_restocked_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_staysafe_reportadd.Lists["x_restocked_by"].Data = "<?php echo $restock_staysafe_report_add->restocked_by->LookupFilterQuery(FALSE, "add") ?>";
frestock_staysafe_reportadd.AutoSuggests["x_restocked_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_staysafe_report_add->restocked_by->LookupFilterQuery(TRUE, "add"))) ?>;
frestock_staysafe_reportadd.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frestock_staysafe_reportadd.Lists["x_approver_action"].Options = <?php echo json_encode($restock_staysafe_report_add->approver_action->Options()) ?>;
frestock_staysafe_reportadd.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_staysafe_reportadd.Lists["x_approved_by"].Data = "<?php echo $restock_staysafe_report_add->approved_by->LookupFilterQuery(FALSE, "add") ?>";
frestock_staysafe_reportadd.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_staysafe_report_add->approved_by->LookupFilterQuery(TRUE, "add"))) ?>;
frestock_staysafe_reportadd.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frestock_staysafe_reportadd.Lists["x_verified_action"].Options = <?php echo json_encode($restock_staysafe_report_add->verified_action->Options()) ?>;
frestock_staysafe_reportadd.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_staysafe_reportadd.Lists["x_verified_by"].Data = "<?php echo $restock_staysafe_report_add->verified_by->LookupFilterQuery(FALSE, "add") ?>";
frestock_staysafe_reportadd.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_staysafe_report_add->verified_by->LookupFilterQuery(TRUE, "add"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $restock_staysafe_report_add->ShowPageHeader(); ?>
<?php
$restock_staysafe_report_add->ShowMessage();
?>
<form name="frestock_staysafe_reportadd" id="frestock_staysafe_reportadd" class="<?php echo $restock_staysafe_report_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($restock_staysafe_report_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $restock_staysafe_report_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="restock_staysafe_report">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($restock_staysafe_report_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($restock_staysafe_report->date_restocked->Visible) { // date_restocked ?>
	<div id="r_date_restocked" class="form-group">
		<label id="elh_restock_staysafe_report_date_restocked" for="x_date_restocked" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->date_restocked->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->date_restocked->CellAttributes() ?>>
<span id="el_restock_staysafe_report_date_restocked">
<input type="text" data-table="restock_staysafe_report" data-field="x_date_restocked" data-page="1" data-format="14" name="x_date_restocked" id="x_date_restocked" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->date_restocked->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->date_restocked->EditValue ?>"<?php echo $restock_staysafe_report->date_restocked->EditAttributes() ?>>
<?php if (!$restock_staysafe_report->date_restocked->ReadOnly && !$restock_staysafe_report->date_restocked->Disabled && !isset($restock_staysafe_report->date_restocked->EditAttrs["readonly"]) && !isset($restock_staysafe_report->date_restocked->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("frestock_staysafe_reportadd", "x_date_restocked", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
<?php echo $restock_staysafe_report->date_restocked->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_restock_staysafe_report_reference_id" for="x_reference_id" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->reference_id->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->reference_id->CellAttributes() ?>>
<span id="el_restock_staysafe_report_reference_id">
<input type="text" data-table="restock_staysafe_report" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->reference_id->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->reference_id->EditValue ?>"<?php echo $restock_staysafe_report->reference_id->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label id="elh_restock_staysafe_report_material_name" for="x_material_name" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->material_name->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->material_name->CellAttributes() ?>>
<span id="el_restock_staysafe_report_material_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_material_name"><?php echo (strval($restock_staysafe_report->material_name->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $restock_staysafe_report->material_name->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($restock_staysafe_report->material_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_material_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($restock_staysafe_report->material_name->ReadOnly || $restock_staysafe_report->material_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_material_name" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $restock_staysafe_report->material_name->DisplayValueSeparatorAttribute() ?>" name="x_material_name" id="x_material_name" value="<?php echo $restock_staysafe_report->material_name->CurrentValue ?>"<?php echo $restock_staysafe_report->material_name->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->material_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_restock_staysafe_report_type" for="x_type" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->type->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->type->CellAttributes() ?>>
<span id="el_restock_staysafe_report_type">
<input type="text" data-table="restock_staysafe_report" data-field="x_type" data-page="1" name="x_type" id="x_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->type->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->type->EditValue ?>"<?php echo $restock_staysafe_report->type->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->capacity->Visible) { // capacity ?>
	<div id="r_capacity" class="form-group">
		<label id="elh_restock_staysafe_report_capacity" for="x_capacity" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->capacity->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->capacity->CellAttributes() ?>>
<span id="el_restock_staysafe_report_capacity">
<input type="text" data-table="restock_staysafe_report" data-field="x_capacity" data-page="1" name="x_capacity" id="x_capacity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->capacity->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->capacity->EditValue ?>"<?php echo $restock_staysafe_report->capacity->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->capacity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->stock_balance->Visible) { // stock_balance ?>
	<div id="r_stock_balance" class="form-group">
		<label id="elh_restock_staysafe_report_stock_balance" for="x_stock_balance" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->stock_balance->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->stock_balance->CellAttributes() ?>>
<span id="el_restock_staysafe_report_stock_balance">
<input type="text" data-table="restock_staysafe_report" data-field="x_stock_balance" data-page="1" name="x_stock_balance" id="x_stock_balance" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->stock_balance->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->stock_balance->EditValue ?>"<?php echo $restock_staysafe_report->stock_balance->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->stock_balance->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label id="elh_restock_staysafe_report_quantity" for="x_quantity" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->quantity->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->quantity->CellAttributes() ?>>
<span id="el_restock_staysafe_report_quantity">
<input type="text" data-table="restock_staysafe_report" data-field="x_quantity" data-page="1" name="x_quantity" id="x_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->quantity->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->quantity->EditValue ?>"<?php echo $restock_staysafe_report->quantity->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label id="elh_restock_staysafe_report_statuss" for="x_statuss" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->statuss->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->statuss->CellAttributes() ?>>
<span id="el_restock_staysafe_report_statuss">
<select data-table="restock_staysafe_report" data-field="x_statuss" data-page="1" data-value-separator="<?php echo $restock_staysafe_report->statuss->DisplayValueSeparatorAttribute() ?>" id="x_statuss" name="x_statuss"<?php echo $restock_staysafe_report->statuss->EditAttributes() ?>>
<?php echo $restock_staysafe_report->statuss->SelectOptionListHtml("x_statuss") ?>
</select>
</span>
<?php echo $restock_staysafe_report->statuss->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->restocked_action->Visible) { // restocked_action ?>
	<div id="r_restocked_action" class="form-group">
		<label id="elh_restock_staysafe_report_restocked_action" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->restocked_action->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->restocked_action->CellAttributes() ?>>
<span id="el_restock_staysafe_report_restocked_action">
<div id="tp_x_restocked_action" class="ewTemplate"><input type="radio" data-table="restock_staysafe_report" data-field="x_restocked_action" data-page="1" data-value-separator="<?php echo $restock_staysafe_report->restocked_action->DisplayValueSeparatorAttribute() ?>" name="x_restocked_action" id="x_restocked_action" value="{value}"<?php echo $restock_staysafe_report->restocked_action->EditAttributes() ?>></div>
<div id="dsl_x_restocked_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $restock_staysafe_report->restocked_action->RadioButtonListHtml(FALSE, "x_restocked_action", 1) ?>
</div></div>
</span>
<?php echo $restock_staysafe_report->restocked_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->restocked_comment->Visible) { // restocked_comment ?>
	<div id="r_restocked_comment" class="form-group">
		<label id="elh_restock_staysafe_report_restocked_comment" for="x_restocked_comment" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->restocked_comment->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->restocked_comment->CellAttributes() ?>>
<span id="el_restock_staysafe_report_restocked_comment">
<textarea data-table="restock_staysafe_report" data-field="x_restocked_comment" data-page="1" name="x_restocked_comment" id="x_restocked_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_comment->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->restocked_comment->EditAttributes() ?>><?php echo $restock_staysafe_report->restocked_comment->EditValue ?></textarea>
</span>
<?php echo $restock_staysafe_report->restocked_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->restocked_by->Visible) { // restocked_by ?>
	<div id="r_restocked_by" class="form-group">
		<label id="elh_restock_staysafe_report_restocked_by" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->restocked_by->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->restocked_by->CellAttributes() ?>>
<span id="el_restock_staysafe_report_restocked_by">
<?php
$wrkonchange = trim(" " . @$restock_staysafe_report->restocked_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$restock_staysafe_report->restocked_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_restocked_by" style="white-space: nowrap; z-index: 8880">
	<input type="text" name="sv_x_restocked_by" id="sv_x_restocked_by" value="<?php echo $restock_staysafe_report->restocked_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_by->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->restocked_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_restocked_by" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $restock_staysafe_report->restocked_by->DisplayValueSeparatorAttribute() ?>" name="x_restocked_by" id="x_restocked_by" value="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frestock_staysafe_reportadd.CreateAutoSuggest({"id":"x_restocked_by","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($restock_staysafe_report->restocked_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_restocked_by',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($restock_staysafe_report->restocked_by->ReadOnly || $restock_staysafe_report->restocked_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
<?php echo $restock_staysafe_report->restocked_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label id="elh_restock_staysafe_report_approver_date" for="x_approver_date" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->approver_date->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approver_date->CellAttributes() ?>>
<span id="el_restock_staysafe_report_approver_date">
<input type="text" data-table="restock_staysafe_report" data-field="x_approver_date" data-page="1" data-format="14" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approver_date->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->approver_date->EditValue ?>"<?php echo $restock_staysafe_report->approver_date->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->approver_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_restock_staysafe_report_approver_action" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->approver_action->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approver_action->CellAttributes() ?>>
<span id="el_restock_staysafe_report_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="restock_staysafe_report" data-field="x_approver_action" data-page="1" data-value-separator="<?php echo $restock_staysafe_report->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $restock_staysafe_report->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $restock_staysafe_report->approver_action->RadioButtonListHtml(FALSE, "x_approver_action", 1) ?>
</div></div>
</span>
<?php echo $restock_staysafe_report->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label id="elh_restock_staysafe_report_approver_comment" for="x_approver_comment" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->approver_comment->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approver_comment->CellAttributes() ?>>
<span id="el_restock_staysafe_report_approver_comment">
<textarea data-table="restock_staysafe_report" data-field="x_approver_comment" data-page="1" name="x_approver_comment" id="x_approver_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approver_comment->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->approver_comment->EditAttributes() ?>><?php echo $restock_staysafe_report->approver_comment->EditValue ?></textarea>
</span>
<?php echo $restock_staysafe_report->approver_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_restock_staysafe_report_approved_by" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->approved_by->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approved_by->CellAttributes() ?>>
<span id="el_restock_staysafe_report_approved_by">
<?php
$wrkonchange = trim(" " . @$restock_staysafe_report->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$restock_staysafe_report->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8840">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $restock_staysafe_report->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approved_by->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_approved_by" data-page="1" data-value-separator="<?php echo $restock_staysafe_report->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($restock_staysafe_report->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frestock_staysafe_reportadd.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php echo $restock_staysafe_report->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label id="elh_restock_staysafe_report_verified_date" for="x_verified_date" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->verified_date->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_date->CellAttributes() ?>>
<span id="el_restock_staysafe_report_verified_date">
<input type="text" data-table="restock_staysafe_report" data-field="x_verified_date" data-page="1" data-format="14" name="x_verified_date" id="x_verified_date" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_date->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->verified_date->EditValue ?>"<?php echo $restock_staysafe_report->verified_date->EditAttributes() ?>>
</span>
<?php echo $restock_staysafe_report->verified_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label id="elh_restock_staysafe_report_verified_action" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->verified_action->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_action->CellAttributes() ?>>
<span id="el_restock_staysafe_report_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="restock_staysafe_report" data-field="x_verified_action" data-page="1" data-value-separator="<?php echo $restock_staysafe_report->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $restock_staysafe_report->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $restock_staysafe_report->verified_action->RadioButtonListHtml(FALSE, "x_verified_action", 1) ?>
</div></div>
</span>
<?php echo $restock_staysafe_report->verified_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label id="elh_restock_staysafe_report_verified_comment" for="x_verified_comment" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->verified_comment->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_comment->CellAttributes() ?>>
<span id="el_restock_staysafe_report_verified_comment">
<textarea data-table="restock_staysafe_report" data-field="x_verified_comment" data-page="1" name="x_verified_comment" id="x_verified_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_comment->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->verified_comment->EditAttributes() ?>><?php echo $restock_staysafe_report->verified_comment->EditValue ?></textarea>
</span>
<?php echo $restock_staysafe_report->verified_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label id="elh_restock_staysafe_report_verified_by" class="<?php echo $restock_staysafe_report_add->LeftColumnClass ?>"><?php echo $restock_staysafe_report->verified_by->FldCaption() ?></label>
		<div class="<?php echo $restock_staysafe_report_add->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_by->CellAttributes() ?>>
<span id="el_restock_staysafe_report_verified_by">
<?php
$wrkonchange = trim(" " . @$restock_staysafe_report->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$restock_staysafe_report->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $restock_staysafe_report->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_by->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_verified_by" data-page="1" data-value-separator="<?php echo $restock_staysafe_report->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frestock_staysafe_reportadd.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
<?php echo $restock_staysafe_report->verified_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$restock_staysafe_report_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $restock_staysafe_report_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $restock_staysafe_report_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
frestock_staysafe_reportadd.Init();
</script>
<?php
$restock_staysafe_report_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$restock_staysafe_report_add->Page_Terminate();
?>
