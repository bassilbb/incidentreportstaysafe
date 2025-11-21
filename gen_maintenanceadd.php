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

$gen_maintenance_add = NULL; // Initialize page object first

class cgen_maintenance_add extends cgen_maintenance {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'gen_maintenance';

	// Page object name
	var $PageObjName = 'gen_maintenance_add';

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
			define("EW_PAGE_ID", 'add');

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
				$this->Page_Terminate(ew_GetUrl("gen_maintenancelist.php"));
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "gen_maintenanceview.php")
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
					$this->Page_Terminate("gen_maintenancelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->GetAddUrl();
					if (ew_GetPageName($sReturnUrl) == "gen_maintenancelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "gen_maintenanceview.php")
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
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view type
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add type
		}

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
		$this->datetime->CurrentValue = NULL;
		$this->datetime->OldValue = $this->datetime->CurrentValue;
		$this->gen_name->CurrentValue = NULL;
		$this->gen_name->OldValue = $this->gen_name->CurrentValue;
		$this->maintenance_type->CurrentValue = NULL;
		$this->maintenance_type->OldValue = $this->maintenance_type->CurrentValue;
		$this->running_hours->CurrentValue = NULL;
		$this->running_hours->OldValue = $this->running_hours->CurrentValue;
		$this->cost->CurrentValue = NULL;
		$this->cost->OldValue = $this->cost->CurrentValue;
		$this->labour_fee->CurrentValue = NULL;
		$this->labour_fee->OldValue = $this->labour_fee->CurrentValue;
		$this->total->CurrentValue = NULL;
		$this->total->OldValue = $this->total->CurrentValue;
		$this->staff_id->CurrentValue = NULL;
		$this->staff_id->OldValue = $this->staff_id->CurrentValue;
		$this->status->CurrentValue = 0;
		$this->initiator_action->CurrentValue = NULL;
		$this->initiator_action->OldValue = $this->initiator_action->CurrentValue;
		$this->initiator_comment->CurrentValue = NULL;
		$this->initiator_comment->OldValue = $this->initiator_comment->CurrentValue;
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
		if (!$this->datetime->FldIsDetailKey) {
			$this->datetime->setFormValue($objForm->GetValue("x_datetime"));
			$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 0);
		}
		if (!$this->gen_name->FldIsDetailKey) {
			$this->gen_name->setFormValue($objForm->GetValue("x_gen_name"));
		}
		if (!$this->maintenance_type->FldIsDetailKey) {
			$this->maintenance_type->setFormValue($objForm->GetValue("x_maintenance_type"));
		}
		if (!$this->running_hours->FldIsDetailKey) {
			$this->running_hours->setFormValue($objForm->GetValue("x_running_hours"));
		}
		if (!$this->cost->FldIsDetailKey) {
			$this->cost->setFormValue($objForm->GetValue("x_cost"));
		}
		if (!$this->labour_fee->FldIsDetailKey) {
			$this->labour_fee->setFormValue($objForm->GetValue("x_labour_fee"));
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->initiator_action->FldIsDetailKey) {
			$this->initiator_action->setFormValue($objForm->GetValue("x_initiator_action"));
		}
		if (!$this->initiator_comment->FldIsDetailKey) {
			$this->initiator_comment->setFormValue($objForm->GetValue("x_initiator_comment"));
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
		$this->datetime->CurrentValue = $this->datetime->FormValue;
		$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 0);
		$this->gen_name->CurrentValue = $this->gen_name->FormValue;
		$this->maintenance_type->CurrentValue = $this->maintenance_type->FormValue;
		$this->running_hours->CurrentValue = $this->running_hours->FormValue;
		$this->cost->CurrentValue = $this->cost->FormValue;
		$this->labour_fee->CurrentValue = $this->labour_fee->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['datetime'] = $this->datetime->CurrentValue;
		$row['gen_name'] = $this->gen_name->CurrentValue;
		$row['maintenance_type'] = $this->maintenance_type->CurrentValue;
		$row['running_hours'] = $this->running_hours->CurrentValue;
		$row['cost'] = $this->cost->CurrentValue;
		$row['labour_fee'] = $this->labour_fee->CurrentValue;
		$row['total'] = $this->total->CurrentValue;
		$row['staff_id'] = $this->staff_id->CurrentValue;
		$row['status'] = $this->status->CurrentValue;
		$row['initiator_action'] = $this->initiator_action->CurrentValue;
		$row['initiator_comment'] = $this->initiator_comment->CurrentValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// datetime
			$this->datetime->EditAttrs["class"] = "form-control";
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime->CurrentValue, 8));
			$this->datetime->PlaceHolder = ew_RemoveHtml($this->datetime->FldCaption());

			// gen_name
			$this->gen_name->EditAttrs["class"] = "form-control";
			$this->gen_name->EditCustomAttributes = "";
			if (trim(strval($this->gen_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `generator_registration`";
			$sWhereWrk = "";
			$this->gen_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->gen_name->EditValue = $arwrk;

			// maintenance_type
			$this->maintenance_type->EditAttrs["class"] = "form-control";
			$this->maintenance_type->EditCustomAttributes = "";
			if (trim(strval($this->maintenance_type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintenance_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `maintenance_type`";
			$sWhereWrk = "";
			$this->maintenance_type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->maintenance_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->maintenance_type->EditValue = $arwrk;

			// running_hours
			$this->running_hours->EditAttrs["class"] = "form-control";
			$this->running_hours->EditCustomAttributes = "";
			$this->running_hours->EditValue = ew_HtmlEncode($this->running_hours->CurrentValue);
			$this->running_hours->PlaceHolder = ew_RemoveHtml($this->running_hours->FldCaption());

			// cost
			$this->cost->EditAttrs["class"] = "form-control";
			$this->cost->EditCustomAttributes = "";
			$this->cost->EditValue = ew_HtmlEncode($this->cost->CurrentValue);
			$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());
			if (strval($this->cost->EditValue) <> "" && is_numeric($this->cost->EditValue)) $this->cost->EditValue = ew_FormatNumber($this->cost->EditValue, -2, -1, -2, 0);

			// labour_fee
			$this->labour_fee->EditAttrs["class"] = "form-control";
			$this->labour_fee->EditCustomAttributes = "";
			$this->labour_fee->EditValue = ew_HtmlEncode($this->labour_fee->CurrentValue);
			$this->labour_fee->PlaceHolder = ew_RemoveHtml($this->labour_fee->FldCaption());
			if (strval($this->labour_fee->EditValue) <> "" && is_numeric($this->labour_fee->EditValue)) $this->labour_fee->EditValue = ew_FormatNumber($this->labour_fee->EditValue, -2, -1, -2, 0);

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			if (trim(strval($this->staff_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->staff_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->staff_id->EditValue = $arwrk;

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `gen_status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->approver_date->CurrentValue, 8));
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
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
					$this->approved_by->EditValue = $this->approved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// Add refer script
			// datetime

			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";

			// maintenance_type
			$this->maintenance_type->LinkCustomAttributes = "";
			$this->maintenance_type->HrefValue = "";

			// running_hours
			$this->running_hours->LinkCustomAttributes = "";
			$this->running_hours->HrefValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";

			// labour_fee
			$this->labour_fee->LinkCustomAttributes = "";
			$this->labour_fee->HrefValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

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
		if (!ew_CheckDateDef($this->datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime->FldErrMsg());
		}
		if (!ew_CheckNumber($this->cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->labour_fee->FormValue)) {
			ew_AddMessage($gsFormError, $this->labour_fee->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->approver_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->approver_date->FldErrMsg());
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

		// datetime
		$this->datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime->CurrentValue, 0), NULL, FALSE);

		// gen_name
		$this->gen_name->SetDbValueDef($rsnew, $this->gen_name->CurrentValue, NULL, FALSE);

		// maintenance_type
		$this->maintenance_type->SetDbValueDef($rsnew, $this->maintenance_type->CurrentValue, NULL, FALSE);

		// running_hours
		$this->running_hours->SetDbValueDef($rsnew, $this->running_hours->CurrentValue, NULL, FALSE);

		// cost
		$this->cost->SetDbValueDef($rsnew, $this->cost->CurrentValue, NULL, FALSE);

		// labour_fee
		$this->labour_fee->SetDbValueDef($rsnew, $this->labour_fee->CurrentValue, NULL, FALSE);

		// total
		$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, NULL, FALSE);

		// staff_id
		$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// initiator_action
		$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, FALSE);

		// initiator_comment
		$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("gen_maintenancelist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_gen_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_maintenance_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintenance_type`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->maintenance_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->approved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($gen_maintenance_add)) $gen_maintenance_add = new cgen_maintenance_add();

// Page init
$gen_maintenance_add->Page_Init();

// Page main
$gen_maintenance_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gen_maintenance_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fgen_maintenanceadd = new ew_Form("fgen_maintenanceadd", "add");

// Validate form
fgen_maintenanceadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_datetime");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->datetime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->cost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_labour_fee");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->labour_fee->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approver_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->approver_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->approved_by->FldErrMsg()) ?>");

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
fgen_maintenanceadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgen_maintenanceadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fgen_maintenanceadd.Lists["x_gen_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","x_kva",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fgen_maintenanceadd.Lists["x_gen_name"].Data = "<?php echo $gen_maintenance_add->gen_name->LookupFilterQuery(FALSE, "add") ?>";
fgen_maintenanceadd.Lists["x_maintenance_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintenance_type"};
fgen_maintenanceadd.Lists["x_maintenance_type"].Data = "<?php echo $gen_maintenance_add->maintenance_type->LookupFilterQuery(FALSE, "add") ?>";
fgen_maintenanceadd.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenanceadd.Lists["x_staff_id"].Data = "<?php echo $gen_maintenance_add->staff_id->LookupFilterQuery(FALSE, "add") ?>";
fgen_maintenanceadd.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_status"};
fgen_maintenanceadd.Lists["x_status"].Data = "<?php echo $gen_maintenance_add->status->LookupFilterQuery(FALSE, "add") ?>";
fgen_maintenanceadd.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenanceadd.Lists["x_initiator_action"].Options = <?php echo json_encode($gen_maintenance_add->initiator_action->Options()) ?>;
fgen_maintenanceadd.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenanceadd.Lists["x_approver_action"].Options = <?php echo json_encode($gen_maintenance_add->approver_action->Options()) ?>;
fgen_maintenanceadd.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenanceadd.Lists["x_approved_by"].Data = "<?php echo $gen_maintenance_add->approved_by->LookupFilterQuery(FALSE, "add") ?>";
fgen_maintenanceadd.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $gen_maintenance_add->approved_by->LookupFilterQuery(TRUE, "add"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $gen_maintenance_add->ShowPageHeader(); ?>
<?php
$gen_maintenance_add->ShowMessage();
?>
<form name="fgen_maintenanceadd" id="fgen_maintenanceadd" class="<?php echo $gen_maintenance_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gen_maintenance_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gen_maintenance_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gen_maintenance">
<?php if ($gen_maintenance->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_add" id="a_add" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($gen_maintenance_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($gen_maintenance->datetime->Visible) { // datetime ?>
	<div id="r_datetime" class="form-group">
		<label id="elh_gen_maintenance_datetime" for="x_datetime" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->datetime->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->datetime->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_datetime">
<input type="text" data-table="gen_maintenance" data-field="x_datetime" data-page="1" name="x_datetime" id="x_datetime" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->datetime->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->datetime->EditValue ?>"<?php echo $gen_maintenance->datetime->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_datetime">
<span<?php echo $gen_maintenance->datetime->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->datetime->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_datetime" data-page="1" name="x_datetime" id="x_datetime" value="<?php echo ew_HtmlEncode($gen_maintenance->datetime->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->datetime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->gen_name->Visible) { // gen_name ?>
	<div id="r_gen_name" class="form-group">
		<label id="elh_gen_maintenance_gen_name" for="x_gen_name" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->gen_name->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->gen_name->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_gen_name">
<select data-table="gen_maintenance" data-field="x_gen_name" data-page="1" data-value-separator="<?php echo $gen_maintenance->gen_name->DisplayValueSeparatorAttribute() ?>" id="x_gen_name" name="x_gen_name"<?php echo $gen_maintenance->gen_name->EditAttributes() ?>>
<?php echo $gen_maintenance->gen_name->SelectOptionListHtml("x_gen_name") ?>
</select>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_gen_name">
<span<?php echo $gen_maintenance->gen_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->gen_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_gen_name" data-page="1" name="x_gen_name" id="x_gen_name" value="<?php echo ew_HtmlEncode($gen_maintenance->gen_name->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->gen_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->maintenance_type->Visible) { // maintenance_type ?>
	<div id="r_maintenance_type" class="form-group">
		<label id="elh_gen_maintenance_maintenance_type" for="x_maintenance_type" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->maintenance_type->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->maintenance_type->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_maintenance_type">
<select data-table="gen_maintenance" data-field="x_maintenance_type" data-page="1" data-value-separator="<?php echo $gen_maintenance->maintenance_type->DisplayValueSeparatorAttribute() ?>" id="x_maintenance_type" name="x_maintenance_type"<?php echo $gen_maintenance->maintenance_type->EditAttributes() ?>>
<?php echo $gen_maintenance->maintenance_type->SelectOptionListHtml("x_maintenance_type") ?>
</select>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_maintenance_type">
<span<?php echo $gen_maintenance->maintenance_type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->maintenance_type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_maintenance_type" data-page="1" name="x_maintenance_type" id="x_maintenance_type" value="<?php echo ew_HtmlEncode($gen_maintenance->maintenance_type->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->maintenance_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->running_hours->Visible) { // running_hours ?>
	<div id="r_running_hours" class="form-group">
		<label id="elh_gen_maintenance_running_hours" for="x_running_hours" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->running_hours->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->running_hours->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_running_hours">
<input type="text" data-table="gen_maintenance" data-field="x_running_hours" data-page="1" name="x_running_hours" id="x_running_hours" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->running_hours->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->running_hours->EditValue ?>"<?php echo $gen_maintenance->running_hours->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_running_hours">
<span<?php echo $gen_maintenance->running_hours->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->running_hours->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_running_hours" data-page="1" name="x_running_hours" id="x_running_hours" value="<?php echo ew_HtmlEncode($gen_maintenance->running_hours->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->running_hours->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->cost->Visible) { // cost ?>
	<div id="r_cost" class="form-group">
		<label id="elh_gen_maintenance_cost" for="x_cost" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->cost->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->cost->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_cost">
<input type="text" data-table="gen_maintenance" data-field="x_cost" data-page="1" name="x_cost" id="x_cost" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->cost->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->cost->EditValue ?>"<?php echo $gen_maintenance->cost->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_cost">
<span<?php echo $gen_maintenance->cost->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->cost->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_cost" data-page="1" name="x_cost" id="x_cost" value="<?php echo ew_HtmlEncode($gen_maintenance->cost->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->labour_fee->Visible) { // labour_fee ?>
	<div id="r_labour_fee" class="form-group">
		<label id="elh_gen_maintenance_labour_fee" for="x_labour_fee" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->labour_fee->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->labour_fee->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_labour_fee">
<input type="text" data-table="gen_maintenance" data-field="x_labour_fee" data-page="1" name="x_labour_fee" id="x_labour_fee" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->labour_fee->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->labour_fee->EditValue ?>"<?php echo $gen_maintenance->labour_fee->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_labour_fee">
<span<?php echo $gen_maintenance->labour_fee->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->labour_fee->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_labour_fee" data-page="1" name="x_labour_fee" id="x_labour_fee" value="<?php echo ew_HtmlEncode($gen_maintenance->labour_fee->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->labour_fee->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_gen_maintenance_total" for="x_total" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->total->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->total->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_total">
<input type="text" data-table="gen_maintenance" data-field="x_total" data-page="1" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->total->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->total->EditValue ?>"<?php echo $gen_maintenance->total->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_total">
<span<?php echo $gen_maintenance->total->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->total->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_total" data-page="1" name="x_total" id="x_total" value="<?php echo ew_HtmlEncode($gen_maintenance->total->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_gen_maintenance_staff_id" for="x_staff_id" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->staff_id->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->staff_id->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_staff_id">
<select data-table="gen_maintenance" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $gen_maintenance->staff_id->DisplayValueSeparatorAttribute() ?>" id="x_staff_id" name="x_staff_id"<?php echo $gen_maintenance->staff_id->EditAttributes() ?>>
<?php echo $gen_maintenance->staff_id->SelectOptionListHtml("x_staff_id") ?>
</select>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_staff_id">
<span<?php echo $gen_maintenance->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($gen_maintenance->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_gen_maintenance_status" for="x_status" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->status->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->status->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_status">
<select data-table="gen_maintenance" data-field="x_status" data-page="1" data-value-separator="<?php echo $gen_maintenance->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $gen_maintenance->status->EditAttributes() ?>>
<?php echo $gen_maintenance->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_status">
<span<?php echo $gen_maintenance->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_status" data-page="1" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($gen_maintenance->status->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_gen_maintenance_initiator_action" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->initiator_action->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->initiator_action->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="gen_maintenance" data-field="x_initiator_action" data-page="1" data-value-separator="<?php echo $gen_maintenance->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $gen_maintenance->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $gen_maintenance->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_initiator_action">
<span<?php echo $gen_maintenance->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->initiator_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_initiator_action" data-page="1" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($gen_maintenance->initiator_action->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_gen_maintenance_initiator_comment" for="x_initiator_comment" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->initiator_comment->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->initiator_comment->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_initiator_comment">
<textarea data-table="gen_maintenance" data-field="x_initiator_comment" data-page="1" name="x_initiator_comment" id="x_initiator_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->initiator_comment->getPlaceHolder()) ?>"<?php echo $gen_maintenance->initiator_comment->EditAttributes() ?>><?php echo $gen_maintenance->initiator_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_initiator_comment">
<span<?php echo $gen_maintenance->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->initiator_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_initiator_comment" data-page="1" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($gen_maintenance->initiator_comment->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label id="elh_gen_maintenance_approver_date" for="x_approver_date" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->approver_date->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->approver_date->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_approver_date">
<input type="text" data-table="gen_maintenance" data-field="x_approver_date" data-page="1" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approver_date->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->approver_date->EditValue ?>"<?php echo $gen_maintenance->approver_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_approver_date">
<span<?php echo $gen_maintenance->approver_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->approver_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_approver_date" data-page="1" name="x_approver_date" id="x_approver_date" value="<?php echo ew_HtmlEncode($gen_maintenance->approver_date->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->approver_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_gen_maintenance_approver_action" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->approver_action->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->approver_action->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="gen_maintenance" data-field="x_approver_action" data-page="1" data-value-separator="<?php echo $gen_maintenance->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $gen_maintenance->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $gen_maintenance->approver_action->RadioButtonListHtml(FALSE, "x_approver_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_approver_action">
<span<?php echo $gen_maintenance->approver_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->approver_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_approver_action" data-page="1" name="x_approver_action" id="x_approver_action" value="<?php echo ew_HtmlEncode($gen_maintenance->approver_action->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label id="elh_gen_maintenance_approver_comment" for="x_approver_comment" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->approver_comment->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->approver_comment->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_approver_comment">
<textarea data-table="gen_maintenance" data-field="x_approver_comment" data-page="1" name="x_approver_comment" id="x_approver_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approver_comment->getPlaceHolder()) ?>"<?php echo $gen_maintenance->approver_comment->EditAttributes() ?>><?php echo $gen_maintenance->approver_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_approver_comment">
<span<?php echo $gen_maintenance->approver_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->approver_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_approver_comment" data-page="1" name="x_approver_comment" id="x_approver_comment" value="<?php echo ew_HtmlEncode($gen_maintenance->approver_comment->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->approver_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_gen_maintenance_approved_by" class="<?php echo $gen_maintenance_add->LeftColumnClass ?>"><?php echo $gen_maintenance->approved_by->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenance_add->RightColumnClass ?>"><div<?php echo $gen_maintenance->approved_by->CellAttributes() ?>>
<?php if ($gen_maintenance->CurrentAction <> "F") { ?>
<span id="el_gen_maintenance_approved_by">
<?php
$wrkonchange = trim(" " . @$gen_maintenance->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$gen_maintenance->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8840">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $gen_maintenance->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->getPlaceHolder()) ?>"<?php echo $gen_maintenance->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_approved_by" data-page="1" data-value-separator="<?php echo $gen_maintenance->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fgen_maintenanceadd.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_gen_maintenance_approved_by">
<span<?php echo $gen_maintenance->approved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenance->approved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_approved_by" data-page="1" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenance->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$gen_maintenance_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $gen_maintenance_add->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($gen_maintenance->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_add.value='F';"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $gen_maintenance_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_add.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fgen_maintenanceadd.Init();
</script>
<?php
$gen_maintenance_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('#x_status').attr('readonly',true);
$("#r_staff_id").hide();
$("#r_approved_by").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$gen_maintenance_add->Page_Terminate();
?>
