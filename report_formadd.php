<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "report_forminfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$report_form_add = NULL; // Initialize page object first

class creport_form_add extends creport_form {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'report_form';

	// Page object name
	var $PageObjName = 'report_form_add';

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

		// Table object (report_form)
		if (!isset($GLOBALS["report_form"]) || get_class($GLOBALS["report_form"]) == "creport_form") {
			$GLOBALS["report_form"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["report_form"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'report_form');

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
				$this->Page_Terminate(ew_GetUrl("report_formlist.php"));
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
		$this->datetime_initiated->SetVisibility();
		$this->incident_id->SetVisibility();
		$this->staffid->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->department->SetVisibility();
		$this->branch->SetVisibility();
		$this->departments->SetVisibility();
		$this->category->SetVisibility();
		$this->sub_category->SetVisibility();
		$this->sub_sub_category->SetVisibility();
		$this->selection_sub_category->SetVisibility();
		$this->start_date->SetVisibility();
		$this->end_date->SetVisibility();
		$this->duration->SetVisibility();
		$this->amount_paid->SetVisibility();
		$this->no_of_people_involved->SetVisibility();
		$this->incident_type->SetVisibility();
		$this->incident_category->SetVisibility();
		$this->incident_location->SetVisibility();
		$this->incident_sub_location->SetVisibility();
		$this->incident_venue->SetVisibility();
		$this->incident_description->SetVisibility();
		$this->_upload->SetVisibility();
		$this->status->SetVisibility();
		$this->rejection_reasons->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->report_by->SetVisibility();
		$this->datetime_resolved->SetVisibility();
		$this->assign->SetVisibility();
		$this->approval_action->SetVisibility();
		$this->approval_comment->SetVisibility();
		$this->item_name->SetVisibility();
		$this->quantity_issued->SetVisibility();
		$this->reason->SetVisibility();
		$this->resolved_action->SetVisibility();
		$this->resolved_comment->SetVisibility();
		$this->resolved_by->SetVisibility();
		$this->datetime_approved->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->last_updated_date->SetVisibility();
		$this->last_updated_by->SetVisibility();
		$this->verified_datetime->SetVisibility();
		$this->job_assessment->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
		$this->verified_by->SetVisibility();
		$this->remainder->SetVisibility();
		$this->organization->SetVisibility();

		// Set up multi page object
		$this->SetupMultiPages();

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
		global $EW_EXPORT, $report_form;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($report_form);
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
					if ($pageName == "report_formview.php")
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
	var $MultiPages; // Multi pages object

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
					$this->Page_Terminate("report_formlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "report_formlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "report_formview.php")
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
		$this->_upload->Upload->Index = $objForm->Index;
		$this->_upload->Upload->UploadFile();
		$this->_upload->CurrentValue = $this->_upload->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->datetime_initiated->CurrentValue = NULL;
		$this->datetime_initiated->OldValue = $this->datetime_initiated->CurrentValue;
		$this->incident_id->CurrentValue = NULL;
		$this->incident_id->OldValue = $this->incident_id->CurrentValue;
		$this->staffid->CurrentValue = NULL;
		$this->staffid->OldValue = $this->staffid->CurrentValue;
		$this->staff_id->CurrentValue = NULL;
		$this->staff_id->OldValue = $this->staff_id->CurrentValue;
		$this->department->CurrentValue = NULL;
		$this->department->OldValue = $this->department->CurrentValue;
		$this->branch->CurrentValue = NULL;
		$this->branch->OldValue = $this->branch->CurrentValue;
		$this->departments->CurrentValue = NULL;
		$this->departments->OldValue = $this->departments->CurrentValue;
		$this->category->CurrentValue = NULL;
		$this->category->OldValue = $this->category->CurrentValue;
		$this->sub_category->CurrentValue = NULL;
		$this->sub_category->OldValue = $this->sub_category->CurrentValue;
		$this->sub_sub_category->CurrentValue = NULL;
		$this->sub_sub_category->OldValue = $this->sub_sub_category->CurrentValue;
		$this->selection_sub_category->CurrentValue = NULL;
		$this->selection_sub_category->OldValue = $this->selection_sub_category->CurrentValue;
		$this->start_date->CurrentValue = NULL;
		$this->start_date->OldValue = $this->start_date->CurrentValue;
		$this->end_date->CurrentValue = NULL;
		$this->end_date->OldValue = $this->end_date->CurrentValue;
		$this->duration->CurrentValue = NULL;
		$this->duration->OldValue = $this->duration->CurrentValue;
		$this->amount_paid->CurrentValue = NULL;
		$this->amount_paid->OldValue = $this->amount_paid->CurrentValue;
		$this->no_of_people_involved->CurrentValue = NULL;
		$this->no_of_people_involved->OldValue = $this->no_of_people_involved->CurrentValue;
		$this->incident_type->CurrentValue = NULL;
		$this->incident_type->OldValue = $this->incident_type->CurrentValue;
		$this->incident_category->CurrentValue = NULL;
		$this->incident_category->OldValue = $this->incident_category->CurrentValue;
		$this->incident_location->CurrentValue = NULL;
		$this->incident_location->OldValue = $this->incident_location->CurrentValue;
		$this->incident_sub_location->CurrentValue = NULL;
		$this->incident_sub_location->OldValue = $this->incident_sub_location->CurrentValue;
		$this->incident_venue->CurrentValue = NULL;
		$this->incident_venue->OldValue = $this->incident_venue->CurrentValue;
		$this->incident_description->CurrentValue = NULL;
		$this->incident_description->OldValue = $this->incident_description->CurrentValue;
		$this->_upload->Upload->DbValue = NULL;
		$this->_upload->OldValue = $this->_upload->Upload->DbValue;
		$this->_upload->CurrentValue = NULL; // Clear file related field
		$this->status->CurrentValue = 0;
		$this->rejection_reasons->CurrentValue = NULL;
		$this->rejection_reasons->OldValue = $this->rejection_reasons->CurrentValue;
		$this->initiator_action->CurrentValue = NULL;
		$this->initiator_action->OldValue = $this->initiator_action->CurrentValue;
		$this->initiator_comment->CurrentValue = NULL;
		$this->initiator_comment->OldValue = $this->initiator_comment->CurrentValue;
		$this->report_by->CurrentValue = NULL;
		$this->report_by->OldValue = $this->report_by->CurrentValue;
		$this->datetime_resolved->CurrentValue = NULL;
		$this->datetime_resolved->OldValue = $this->datetime_resolved->CurrentValue;
		$this->assign->CurrentValue = NULL;
		$this->assign->OldValue = $this->assign->CurrentValue;
		$this->assign_task->CurrentValue = NULL;
		$this->assign_task->OldValue = $this->assign_task->CurrentValue;
		$this->approval_action->CurrentValue = NULL;
		$this->approval_action->OldValue = $this->approval_action->CurrentValue;
		$this->approval_comment->CurrentValue = NULL;
		$this->approval_comment->OldValue = $this->approval_comment->CurrentValue;
		$this->item_name->CurrentValue = NULL;
		$this->item_name->OldValue = $this->item_name->CurrentValue;
		$this->quantity_issued->CurrentValue = NULL;
		$this->quantity_issued->OldValue = $this->quantity_issued->CurrentValue;
		$this->reason->CurrentValue = NULL;
		$this->reason->OldValue = $this->reason->CurrentValue;
		$this->resolved_action->CurrentValue = NULL;
		$this->resolved_action->OldValue = $this->resolved_action->CurrentValue;
		$this->resolved_comment->CurrentValue = NULL;
		$this->resolved_comment->OldValue = $this->resolved_comment->CurrentValue;
		$this->resolved_by->CurrentValue = NULL;
		$this->resolved_by->OldValue = $this->resolved_by->CurrentValue;
		$this->datetime_approved->CurrentValue = NULL;
		$this->datetime_approved->OldValue = $this->datetime_approved->CurrentValue;
		$this->approved_by->CurrentValue = NULL;
		$this->approved_by->OldValue = $this->approved_by->CurrentValue;
		$this->last_updated_date->CurrentValue = NULL;
		$this->last_updated_date->OldValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_by->CurrentValue = NULL;
		$this->last_updated_by->OldValue = $this->last_updated_by->CurrentValue;
		$this->verified_datetime->CurrentValue = NULL;
		$this->verified_datetime->OldValue = $this->verified_datetime->CurrentValue;
		$this->job_assessment->CurrentValue = NULL;
		$this->job_assessment->OldValue = $this->job_assessment->CurrentValue;
		$this->verified_action->CurrentValue = NULL;
		$this->verified_action->OldValue = $this->verified_action->CurrentValue;
		$this->verified_comment->CurrentValue = NULL;
		$this->verified_comment->OldValue = $this->verified_comment->CurrentValue;
		$this->verified_by->CurrentValue = NULL;
		$this->verified_by->OldValue = $this->verified_by->CurrentValue;
		$this->remainder->CurrentValue = NULL;
		$this->remainder->OldValue = $this->remainder->CurrentValue;
		$this->organization->CurrentValue = NULL;
		$this->organization->OldValue = $this->organization->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->datetime_initiated->FldIsDetailKey) {
			$this->datetime_initiated->setFormValue($objForm->GetValue("x_datetime_initiated"));
			$this->datetime_initiated->CurrentValue = ew_UnFormatDateTime($this->datetime_initiated->CurrentValue, 11);
		}
		if (!$this->incident_id->FldIsDetailKey) {
			$this->incident_id->setFormValue($objForm->GetValue("x_incident_id"));
		}
		if (!$this->staffid->FldIsDetailKey) {
			$this->staffid->setFormValue($objForm->GetValue("x_staffid"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->branch->FldIsDetailKey) {
			$this->branch->setFormValue($objForm->GetValue("x_branch"));
		}
		if (!$this->departments->FldIsDetailKey) {
			$this->departments->setFormValue($objForm->GetValue("x_departments"));
		}
		if (!$this->category->FldIsDetailKey) {
			$this->category->setFormValue($objForm->GetValue("x_category"));
		}
		if (!$this->sub_category->FldIsDetailKey) {
			$this->sub_category->setFormValue($objForm->GetValue("x_sub_category"));
		}
		if (!$this->sub_sub_category->FldIsDetailKey) {
			$this->sub_sub_category->setFormValue($objForm->GetValue("x_sub_sub_category"));
		}
		if (!$this->selection_sub_category->FldIsDetailKey) {
			$this->selection_sub_category->setFormValue($objForm->GetValue("x_selection_sub_category"));
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
			$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 7);
		}
		if (!$this->end_date->FldIsDetailKey) {
			$this->end_date->setFormValue($objForm->GetValue("x_end_date"));
			$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 7);
		}
		if (!$this->duration->FldIsDetailKey) {
			$this->duration->setFormValue($objForm->GetValue("x_duration"));
		}
		if (!$this->amount_paid->FldIsDetailKey) {
			$this->amount_paid->setFormValue($objForm->GetValue("x_amount_paid"));
		}
		if (!$this->no_of_people_involved->FldIsDetailKey) {
			$this->no_of_people_involved->setFormValue($objForm->GetValue("x_no_of_people_involved"));
		}
		if (!$this->incident_type->FldIsDetailKey) {
			$this->incident_type->setFormValue($objForm->GetValue("x_incident_type"));
		}
		if (!$this->incident_category->FldIsDetailKey) {
			$this->incident_category->setFormValue($objForm->GetValue("x_incident_category"));
		}
		if (!$this->incident_location->FldIsDetailKey) {
			$this->incident_location->setFormValue($objForm->GetValue("x_incident_location"));
		}
		if (!$this->incident_sub_location->FldIsDetailKey) {
			$this->incident_sub_location->setFormValue($objForm->GetValue("x_incident_sub_location"));
		}
		if (!$this->incident_venue->FldIsDetailKey) {
			$this->incident_venue->setFormValue($objForm->GetValue("x_incident_venue"));
		}
		if (!$this->incident_description->FldIsDetailKey) {
			$this->incident_description->setFormValue($objForm->GetValue("x_incident_description"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->rejection_reasons->FldIsDetailKey) {
			$this->rejection_reasons->setFormValue($objForm->GetValue("x_rejection_reasons"));
		}
		if (!$this->initiator_action->FldIsDetailKey) {
			$this->initiator_action->setFormValue($objForm->GetValue("x_initiator_action"));
		}
		if (!$this->initiator_comment->FldIsDetailKey) {
			$this->initiator_comment->setFormValue($objForm->GetValue("x_initiator_comment"));
		}
		if (!$this->report_by->FldIsDetailKey) {
			$this->report_by->setFormValue($objForm->GetValue("x_report_by"));
		}
		if (!$this->datetime_resolved->FldIsDetailKey) {
			$this->datetime_resolved->setFormValue($objForm->GetValue("x_datetime_resolved"));
			$this->datetime_resolved->CurrentValue = ew_UnFormatDateTime($this->datetime_resolved->CurrentValue, 11);
		}
		if (!$this->assign->FldIsDetailKey) {
			$this->assign->setFormValue($objForm->GetValue("x_assign"));
		}
		if (!$this->approval_action->FldIsDetailKey) {
			$this->approval_action->setFormValue($objForm->GetValue("x_approval_action"));
		}
		if (!$this->approval_comment->FldIsDetailKey) {
			$this->approval_comment->setFormValue($objForm->GetValue("x_approval_comment"));
		}
		if (!$this->item_name->FldIsDetailKey) {
			$this->item_name->setFormValue($objForm->GetValue("x_item_name"));
		}
		if (!$this->quantity_issued->FldIsDetailKey) {
			$this->quantity_issued->setFormValue($objForm->GetValue("x_quantity_issued"));
		}
		if (!$this->reason->FldIsDetailKey) {
			$this->reason->setFormValue($objForm->GetValue("x_reason"));
		}
		if (!$this->resolved_action->FldIsDetailKey) {
			$this->resolved_action->setFormValue($objForm->GetValue("x_resolved_action"));
		}
		if (!$this->resolved_comment->FldIsDetailKey) {
			$this->resolved_comment->setFormValue($objForm->GetValue("x_resolved_comment"));
		}
		if (!$this->resolved_by->FldIsDetailKey) {
			$this->resolved_by->setFormValue($objForm->GetValue("x_resolved_by"));
		}
		if (!$this->datetime_approved->FldIsDetailKey) {
			$this->datetime_approved->setFormValue($objForm->GetValue("x_datetime_approved"));
			$this->datetime_approved->CurrentValue = ew_UnFormatDateTime($this->datetime_approved->CurrentValue, 11);
		}
		if (!$this->approved_by->FldIsDetailKey) {
			$this->approved_by->setFormValue($objForm->GetValue("x_approved_by"));
		}
		if (!$this->last_updated_date->FldIsDetailKey) {
			$this->last_updated_date->setFormValue($objForm->GetValue("x_last_updated_date"));
			$this->last_updated_date->CurrentValue = ew_UnFormatDateTime($this->last_updated_date->CurrentValue, 17);
		}
		if (!$this->last_updated_by->FldIsDetailKey) {
			$this->last_updated_by->setFormValue($objForm->GetValue("x_last_updated_by"));
		}
		if (!$this->verified_datetime->FldIsDetailKey) {
			$this->verified_datetime->setFormValue($objForm->GetValue("x_verified_datetime"));
			$this->verified_datetime->CurrentValue = ew_UnFormatDateTime($this->verified_datetime->CurrentValue, 17);
		}
		if (!$this->job_assessment->FldIsDetailKey) {
			$this->job_assessment->setFormValue($objForm->GetValue("x_job_assessment"));
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
		if (!$this->remainder->FldIsDetailKey) {
			$this->remainder->setFormValue($objForm->GetValue("x_remainder"));
		}
		if (!$this->organization->FldIsDetailKey) {
			$this->organization->setFormValue($objForm->GetValue("x_organization"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->datetime_initiated->CurrentValue = $this->datetime_initiated->FormValue;
		$this->datetime_initiated->CurrentValue = ew_UnFormatDateTime($this->datetime_initiated->CurrentValue, 11);
		$this->incident_id->CurrentValue = $this->incident_id->FormValue;
		$this->staffid->CurrentValue = $this->staffid->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->branch->CurrentValue = $this->branch->FormValue;
		$this->departments->CurrentValue = $this->departments->FormValue;
		$this->category->CurrentValue = $this->category->FormValue;
		$this->sub_category->CurrentValue = $this->sub_category->FormValue;
		$this->sub_sub_category->CurrentValue = $this->sub_sub_category->FormValue;
		$this->selection_sub_category->CurrentValue = $this->selection_sub_category->FormValue;
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 7);
		$this->end_date->CurrentValue = $this->end_date->FormValue;
		$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 7);
		$this->duration->CurrentValue = $this->duration->FormValue;
		$this->amount_paid->CurrentValue = $this->amount_paid->FormValue;
		$this->no_of_people_involved->CurrentValue = $this->no_of_people_involved->FormValue;
		$this->incident_type->CurrentValue = $this->incident_type->FormValue;
		$this->incident_category->CurrentValue = $this->incident_category->FormValue;
		$this->incident_location->CurrentValue = $this->incident_location->FormValue;
		$this->incident_sub_location->CurrentValue = $this->incident_sub_location->FormValue;
		$this->incident_venue->CurrentValue = $this->incident_venue->FormValue;
		$this->incident_description->CurrentValue = $this->incident_description->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->rejection_reasons->CurrentValue = $this->rejection_reasons->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
		$this->report_by->CurrentValue = $this->report_by->FormValue;
		$this->datetime_resolved->CurrentValue = $this->datetime_resolved->FormValue;
		$this->datetime_resolved->CurrentValue = ew_UnFormatDateTime($this->datetime_resolved->CurrentValue, 11);
		$this->assign->CurrentValue = $this->assign->FormValue;
		$this->approval_action->CurrentValue = $this->approval_action->FormValue;
		$this->approval_comment->CurrentValue = $this->approval_comment->FormValue;
		$this->item_name->CurrentValue = $this->item_name->FormValue;
		$this->quantity_issued->CurrentValue = $this->quantity_issued->FormValue;
		$this->reason->CurrentValue = $this->reason->FormValue;
		$this->resolved_action->CurrentValue = $this->resolved_action->FormValue;
		$this->resolved_comment->CurrentValue = $this->resolved_comment->FormValue;
		$this->resolved_by->CurrentValue = $this->resolved_by->FormValue;
		$this->datetime_approved->CurrentValue = $this->datetime_approved->FormValue;
		$this->datetime_approved->CurrentValue = ew_UnFormatDateTime($this->datetime_approved->CurrentValue, 11);
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->last_updated_date->CurrentValue = $this->last_updated_date->FormValue;
		$this->last_updated_date->CurrentValue = ew_UnFormatDateTime($this->last_updated_date->CurrentValue, 17);
		$this->last_updated_by->CurrentValue = $this->last_updated_by->FormValue;
		$this->verified_datetime->CurrentValue = $this->verified_datetime->FormValue;
		$this->verified_datetime->CurrentValue = ew_UnFormatDateTime($this->verified_datetime->CurrentValue, 17);
		$this->job_assessment->CurrentValue = $this->job_assessment->FormValue;
		$this->verified_action->CurrentValue = $this->verified_action->FormValue;
		$this->verified_comment->CurrentValue = $this->verified_comment->FormValue;
		$this->verified_by->CurrentValue = $this->verified_by->FormValue;
		$this->remainder->CurrentValue = $this->remainder->FormValue;
		$this->organization->CurrentValue = $this->organization->FormValue;
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
		$this->datetime_initiated->setDbValue($row['datetime_initiated']);
		$this->incident_id->setDbValue($row['incident_id']);
		$this->staffid->setDbValue($row['staffid']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->departments->setDbValue($row['departments']);
		$this->category->setDbValue($row['category']);
		$this->sub_category->setDbValue($row['sub_category']);
		$this->sub_sub_category->setDbValue($row['sub_sub_category']);
		$this->selection_sub_category->setDbValue($row['selection_sub_category']);
		$this->start_date->setDbValue($row['start_date']);
		$this->end_date->setDbValue($row['end_date']);
		$this->duration->setDbValue($row['duration']);
		$this->amount_paid->setDbValue($row['amount_paid']);
		$this->no_of_people_involved->setDbValue($row['no_of_people_involved']);
		$this->incident_type->setDbValue($row['incident_type']);
		$this->incident_category->setDbValue($row['incident-category']);
		$this->incident_location->setDbValue($row['incident_location']);
		$this->incident_sub_location->setDbValue($row['incident_sub_location']);
		$this->incident_venue->setDbValue($row['incident_venue']);
		$this->incident_description->setDbValue($row['incident_description']);
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->_upload->setDbValue($this->_upload->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->rejection_reasons->setDbValue($row['rejection_reasons']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->report_by->setDbValue($row['report_by']);
		$this->datetime_resolved->setDbValue($row['datetime_resolved']);
		$this->assign->setDbValue($row['assign']);
		$this->assign_task->setDbValue($row['assign_task']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->item_name->setDbValue($row['item_name']);
		$this->quantity_issued->setDbValue($row['quantity_issued']);
		$this->reason->setDbValue($row['reason']);
		$this->resolved_action->setDbValue($row['resolved_action']);
		$this->resolved_comment->setDbValue($row['resolved_comment']);
		$this->resolved_by->setDbValue($row['resolved_by']);
		$this->datetime_approved->setDbValue($row['datetime_approved']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->last_updated_date->setDbValue($row['last_updated_date']);
		$this->last_updated_by->setDbValue($row['last_updated_by']);
		$this->verified_datetime->setDbValue($row['verified_datetime']);
		$this->job_assessment->setDbValue($row['job_assessment']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->verified_by->setDbValue($row['verified_by']);
		$this->remainder->setDbValue($row['remainder']);
		$this->organization->setDbValue($row['organization']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['datetime_initiated'] = $this->datetime_initiated->CurrentValue;
		$row['incident_id'] = $this->incident_id->CurrentValue;
		$row['staffid'] = $this->staffid->CurrentValue;
		$row['staff_id'] = $this->staff_id->CurrentValue;
		$row['department'] = $this->department->CurrentValue;
		$row['branch'] = $this->branch->CurrentValue;
		$row['departments'] = $this->departments->CurrentValue;
		$row['category'] = $this->category->CurrentValue;
		$row['sub_category'] = $this->sub_category->CurrentValue;
		$row['sub_sub_category'] = $this->sub_sub_category->CurrentValue;
		$row['selection_sub_category'] = $this->selection_sub_category->CurrentValue;
		$row['start_date'] = $this->start_date->CurrentValue;
		$row['end_date'] = $this->end_date->CurrentValue;
		$row['duration'] = $this->duration->CurrentValue;
		$row['amount_paid'] = $this->amount_paid->CurrentValue;
		$row['no_of_people_involved'] = $this->no_of_people_involved->CurrentValue;
		$row['incident_type'] = $this->incident_type->CurrentValue;
		$row['incident-category'] = $this->incident_category->CurrentValue;
		$row['incident_location'] = $this->incident_location->CurrentValue;
		$row['incident_sub_location'] = $this->incident_sub_location->CurrentValue;
		$row['incident_venue'] = $this->incident_venue->CurrentValue;
		$row['incident_description'] = $this->incident_description->CurrentValue;
		$row['upload'] = $this->_upload->Upload->DbValue;
		$row['status'] = $this->status->CurrentValue;
		$row['rejection_reasons'] = $this->rejection_reasons->CurrentValue;
		$row['initiator_action'] = $this->initiator_action->CurrentValue;
		$row['initiator_comment'] = $this->initiator_comment->CurrentValue;
		$row['report_by'] = $this->report_by->CurrentValue;
		$row['datetime_resolved'] = $this->datetime_resolved->CurrentValue;
		$row['assign'] = $this->assign->CurrentValue;
		$row['assign_task'] = $this->assign_task->CurrentValue;
		$row['approval_action'] = $this->approval_action->CurrentValue;
		$row['approval_comment'] = $this->approval_comment->CurrentValue;
		$row['item_name'] = $this->item_name->CurrentValue;
		$row['quantity_issued'] = $this->quantity_issued->CurrentValue;
		$row['reason'] = $this->reason->CurrentValue;
		$row['resolved_action'] = $this->resolved_action->CurrentValue;
		$row['resolved_comment'] = $this->resolved_comment->CurrentValue;
		$row['resolved_by'] = $this->resolved_by->CurrentValue;
		$row['datetime_approved'] = $this->datetime_approved->CurrentValue;
		$row['approved_by'] = $this->approved_by->CurrentValue;
		$row['last_updated_date'] = $this->last_updated_date->CurrentValue;
		$row['last_updated_by'] = $this->last_updated_by->CurrentValue;
		$row['verified_datetime'] = $this->verified_datetime->CurrentValue;
		$row['job_assessment'] = $this->job_assessment->CurrentValue;
		$row['verified_action'] = $this->verified_action->CurrentValue;
		$row['verified_comment'] = $this->verified_comment->CurrentValue;
		$row['verified_by'] = $this->verified_by->CurrentValue;
		$row['remainder'] = $this->remainder->CurrentValue;
		$row['organization'] = $this->organization->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime_initiated->DbValue = $row['datetime_initiated'];
		$this->incident_id->DbValue = $row['incident_id'];
		$this->staffid->DbValue = $row['staffid'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->departments->DbValue = $row['departments'];
		$this->category->DbValue = $row['category'];
		$this->sub_category->DbValue = $row['sub_category'];
		$this->sub_sub_category->DbValue = $row['sub_sub_category'];
		$this->selection_sub_category->DbValue = $row['selection_sub_category'];
		$this->start_date->DbValue = $row['start_date'];
		$this->end_date->DbValue = $row['end_date'];
		$this->duration->DbValue = $row['duration'];
		$this->amount_paid->DbValue = $row['amount_paid'];
		$this->no_of_people_involved->DbValue = $row['no_of_people_involved'];
		$this->incident_type->DbValue = $row['incident_type'];
		$this->incident_category->DbValue = $row['incident-category'];
		$this->incident_location->DbValue = $row['incident_location'];
		$this->incident_sub_location->DbValue = $row['incident_sub_location'];
		$this->incident_venue->DbValue = $row['incident_venue'];
		$this->incident_description->DbValue = $row['incident_description'];
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->status->DbValue = $row['status'];
		$this->rejection_reasons->DbValue = $row['rejection_reasons'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->report_by->DbValue = $row['report_by'];
		$this->datetime_resolved->DbValue = $row['datetime_resolved'];
		$this->assign->DbValue = $row['assign'];
		$this->assign_task->DbValue = $row['assign_task'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->item_name->DbValue = $row['item_name'];
		$this->quantity_issued->DbValue = $row['quantity_issued'];
		$this->reason->DbValue = $row['reason'];
		$this->resolved_action->DbValue = $row['resolved_action'];
		$this->resolved_comment->DbValue = $row['resolved_comment'];
		$this->resolved_by->DbValue = $row['resolved_by'];
		$this->datetime_approved->DbValue = $row['datetime_approved'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->last_updated_date->DbValue = $row['last_updated_date'];
		$this->last_updated_by->DbValue = $row['last_updated_by'];
		$this->verified_datetime->DbValue = $row['verified_datetime'];
		$this->job_assessment->DbValue = $row['job_assessment'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
		$this->remainder->DbValue = $row['remainder'];
		$this->organization->DbValue = $row['organization'];
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

		if ($this->amount_paid->FormValue == $this->amount_paid->CurrentValue && is_numeric(ew_StrToFloat($this->amount_paid->CurrentValue)))
			$this->amount_paid->CurrentValue = ew_StrToFloat($this->amount_paid->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// datetime_initiated
		// incident_id
		// staffid
		// staff_id
		// department
		// branch
		// departments
		// category
		// sub_category
		// sub_sub_category
		// selection_sub_category
		// start_date
		// end_date
		// duration
		// amount_paid
		// no_of_people_involved
		// incident_type
		// incident-category
		// incident_location
		// incident_sub_location
		// incident_venue
		// incident_description
		// upload
		// status
		// rejection_reasons
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// assign
		// assign_task
		// approval_action
		// approval_comment
		// item_name
		// quantity_issued
		// reason
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approved_by
		// last_updated_date
		// last_updated_by
		// verified_datetime
		// job_assessment
		// verified_action
		// verified_comment
		// verified_by
		// remainder
		// organization

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 11);
		$this->datetime_initiated->ViewCustomAttributes = "";

		// incident_id
		$this->incident_id->ViewValue = $this->incident_id->CurrentValue;
		$this->incident_id->ViewCustomAttributes = "";

		// staffid
		$this->staffid->ViewValue = $this->staffid->CurrentValue;
		if (strval($this->staffid->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staffid->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staffid->ViewValue = $this->staffid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staffid->ViewValue = $this->staffid->CurrentValue;
			}
		} else {
			$this->staffid->ViewValue = NULL;
		}
		$this->staffid->ViewCustomAttributes = "";

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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `department_id` ASC";
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
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `branch_id` ASC";
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

		// departments
		if (strval($this->departments->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
		$sWhereWrk = "";
		$this->departments->LookupFilters = array();
		$lookuptblfilter = "`flag`='2'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departments->ViewValue = $this->departments->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departments->ViewValue = $this->departments->CurrentValue;
			}
		} else {
			$this->departments->ViewValue = NULL;
		}
		$this->departments->ViewCustomAttributes = "";

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category->ViewValue = $this->category->CurrentValue;
			}
		} else {
			$this->category->ViewValue = NULL;
		}
		$this->category->ViewCustomAttributes = "";

		// sub_category
		if (strval($this->sub_category->CurrentValue) <> "") {
			$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
		$sWhereWrk = "";
		$this->sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_category->ViewValue = $this->sub_category->CurrentValue;
			}
		} else {
			$this->sub_category->ViewValue = NULL;
		}
		$this->sub_category->ViewCustomAttributes = "";

		// sub_sub_category
		if (strval($this->sub_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->sub_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_sub_category`";
		$sWhereWrk = "";
		$this->sub_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->sub_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->sub_sub_category->ViewValue .= $this->sub_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->sub_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->sub_sub_category->ViewValue = $this->sub_sub_category->CurrentValue;
			}
		} else {
			$this->sub_sub_category->ViewValue = NULL;
		}
		$this->sub_sub_category->ViewCustomAttributes = "";

		// selection_sub_category
		if (strval($this->selection_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->selection_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `selection_sub_category`";
		$sWhereWrk = "";
		$this->selection_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->selection_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->selection_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->selection_sub_category->ViewValue .= $this->selection_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->selection_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->selection_sub_category->ViewValue = $this->selection_sub_category->CurrentValue;
			}
		} else {
			$this->selection_sub_category->ViewValue = NULL;
		}
		$this->selection_sub_category->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 7);
		$this->end_date->ViewCustomAttributes = "";

		// duration
		$this->duration->ViewValue = $this->duration->CurrentValue;
		$this->duration->ViewCustomAttributes = "";

		// amount_paid
		$this->amount_paid->ViewValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->ViewValue = ew_FormatCurrency($this->amount_paid->ViewValue, 2, -2, -2, -2);
		$this->amount_paid->ViewCustomAttributes = "";

		// no_of_people_involved
		$this->no_of_people_involved->ViewValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->ViewCustomAttributes = "";

		// incident_type
		if (strval($this->incident_type->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
		$sWhereWrk = "";
		$this->incident_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_type->ViewValue = $this->incident_type->CurrentValue;
			}
		} else {
			$this->incident_type->ViewValue = NULL;
		}
		$this->incident_type->ViewCustomAttributes = "";

		// incident-category
		if (strval($this->incident_category->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->incident_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_category->ViewValue = $this->incident_category->CurrentValue;
			}
		} else {
			$this->incident_category->ViewValue = NULL;
		}
		$this->incident_category->ViewCustomAttributes = "";

		// incident_location
		if (strval($this->incident_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->incident_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_location->ViewValue = $this->incident_location->CurrentValue;
			}
		} else {
			$this->incident_location->ViewValue = NULL;
		}
		$this->incident_location->ViewCustomAttributes = "";

		// incident_sub_location
		if (strval($this->incident_sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->incident_sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->incident_sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->CurrentValue;
			}
		} else {
			$this->incident_sub_location->ViewValue = NULL;
		}
		$this->incident_sub_location->ViewCustomAttributes = "";

		// incident_venue
		if (strval($this->incident_venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->incident_venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_venue->ViewValue = $this->incident_venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_venue->ViewValue = $this->incident_venue->CurrentValue;
			}
		} else {
			$this->incident_venue->ViewValue = NULL;
		}
		$this->incident_venue->ViewCustomAttributes = "";

		// incident_description
		$this->incident_description->ViewValue = $this->incident_description->CurrentValue;
		$this->incident_description->ViewCustomAttributes = "";

		// upload
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->ViewValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->ViewValue = "";
		}
		$this->_upload->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `description` ASC";
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

		// rejection_reasons
		$this->rejection_reasons->ViewValue = $this->rejection_reasons->CurrentValue;
		$this->rejection_reasons->ViewCustomAttributes = "";

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

		// report_by
		$this->report_by->ViewValue = $this->report_by->CurrentValue;
		if (strval($this->report_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->report_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->report_by->ViewValue = $this->report_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->report_by->ViewValue = $this->report_by->CurrentValue;
			}
		} else {
			$this->report_by->ViewValue = NULL;
		}
		$this->report_by->ViewCustomAttributes = "";

		// datetime_resolved
		$this->datetime_resolved->ViewValue = $this->datetime_resolved->CurrentValue;
		$this->datetime_resolved->ViewValue = ew_FormatDateTime($this->datetime_resolved->ViewValue, 11);
		$this->datetime_resolved->ViewCustomAttributes = "";

		// assign
		if (strval($this->assign->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->assign->ViewValue = $this->assign->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign->ViewValue = $this->assign->CurrentValue;
			}
		} else {
			$this->assign->ViewValue = NULL;
		}
		$this->assign->ViewCustomAttributes = "";

		// assign_task
		$this->assign_task->ViewCustomAttributes = "";

		// approval_action
		if (strval($this->approval_action->CurrentValue) <> "") {
			$this->approval_action->ViewValue = $this->approval_action->OptionCaption($this->approval_action->CurrentValue);
		} else {
			$this->approval_action->ViewValue = NULL;
		}
		$this->approval_action->ViewCustomAttributes = "";

		// approval_comment
		$this->approval_comment->ViewValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->ViewCustomAttributes = "";

		// item_name
		if (strval($this->item_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->item_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
		$sWhereWrk = "";
		$this->item_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->item_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->item_name->ViewValue = $this->item_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->item_name->ViewValue = $this->item_name->CurrentValue;
			}
		} else {
			$this->item_name->ViewValue = NULL;
		}
		$this->item_name->ViewCustomAttributes = "";

		// quantity_issued
		$this->quantity_issued->ViewValue = $this->quantity_issued->CurrentValue;
		$this->quantity_issued->ViewCustomAttributes = "";

		// reason
		if (strval($this->reason->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reason->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `reason`";
		$sWhereWrk = "";
		$this->reason->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->reason->ViewValue = $this->reason->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reason->ViewValue = $this->reason->CurrentValue;
			}
		} else {
			$this->reason->ViewValue = NULL;
		}
		$this->reason->ViewCustomAttributes = "";

		// resolved_action
		if (strval($this->resolved_action->CurrentValue) <> "") {
			$this->resolved_action->ViewValue = $this->resolved_action->OptionCaption($this->resolved_action->CurrentValue);
		} else {
			$this->resolved_action->ViewValue = NULL;
		}
		$this->resolved_action->ViewCustomAttributes = "";

		// resolved_comment
		$this->resolved_comment->ViewValue = $this->resolved_comment->CurrentValue;
		$this->resolved_comment->ViewCustomAttributes = "";

		// resolved_by
		$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
		if (strval($this->resolved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->resolved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->resolved_by->ViewValue = $this->resolved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
			}
		} else {
			$this->resolved_by->ViewValue = NULL;
		}
		$this->resolved_by->ViewCustomAttributes = "";

		// datetime_approved
		$this->datetime_approved->ViewValue = $this->datetime_approved->CurrentValue;
		$this->datetime_approved->ViewValue = ew_FormatDateTime($this->datetime_approved->ViewValue, 11);
		$this->datetime_approved->ViewCustomAttributes = "";

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

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 17);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->last_updated_by->ViewValue = $this->last_updated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
			}
		} else {
			$this->last_updated_by->ViewValue = NULL;
		}
		$this->last_updated_by->ViewCustomAttributes = "";

		// verified_datetime
		$this->verified_datetime->ViewValue = $this->verified_datetime->CurrentValue;
		$this->verified_datetime->ViewValue = ew_FormatDateTime($this->verified_datetime->ViewValue, 17);
		$this->verified_datetime->ViewCustomAttributes = "";

		// job_assessment
		if (strval($this->job_assessment->CurrentValue) <> "") {
			$this->job_assessment->ViewValue = $this->job_assessment->OptionCaption($this->job_assessment->CurrentValue);
		} else {
			$this->job_assessment->ViewValue = NULL;
		}
		$this->job_assessment->ViewCustomAttributes = "";

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

		// remainder
		if (strval($this->remainder->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->remainder->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->remainder->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->remainder, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->remainder->ViewValue = $this->remainder->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->remainder->ViewValue = $this->remainder->CurrentValue;
			}
		} else {
			$this->remainder->ViewValue = NULL;
		}
		$this->remainder->ViewCustomAttributes = "";

		// organization
		$this->organization->ViewValue = $this->organization->CurrentValue;
		$this->organization->ViewCustomAttributes = "";

			// datetime_initiated
			$this->datetime_initiated->LinkCustomAttributes = "";
			$this->datetime_initiated->HrefValue = "";
			$this->datetime_initiated->TooltipValue = "";

			// incident_id
			$this->incident_id->LinkCustomAttributes = "";
			$this->incident_id->HrefValue = "";
			$this->incident_id->TooltipValue = "";

			// staffid
			$this->staffid->LinkCustomAttributes = "";
			$this->staffid->HrefValue = "";
			$this->staffid->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// departments
			$this->departments->LinkCustomAttributes = "";
			$this->departments->HrefValue = "";
			$this->departments->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// sub_category
			$this->sub_category->LinkCustomAttributes = "";
			$this->sub_category->HrefValue = "";
			$this->sub_category->TooltipValue = "";

			// sub_sub_category
			$this->sub_sub_category->LinkCustomAttributes = "";
			$this->sub_sub_category->HrefValue = "";
			$this->sub_sub_category->TooltipValue = "";

			// selection_sub_category
			$this->selection_sub_category->LinkCustomAttributes = "";
			$this->selection_sub_category->HrefValue = "";
			$this->selection_sub_category->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// duration
			$this->duration->LinkCustomAttributes = "";
			$this->duration->HrefValue = "";
			$this->duration->TooltipValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";
			$this->amount_paid->TooltipValue = "";

			// no_of_people_involved
			$this->no_of_people_involved->LinkCustomAttributes = "";
			$this->no_of_people_involved->HrefValue = "";
			$this->no_of_people_involved->TooltipValue = "";

			// incident_type
			$this->incident_type->LinkCustomAttributes = "";
			$this->incident_type->HrefValue = "";
			$this->incident_type->TooltipValue = "";

			// incident-category
			$this->incident_category->LinkCustomAttributes = "";
			$this->incident_category->HrefValue = "";
			$this->incident_category->TooltipValue = "";

			// incident_location
			$this->incident_location->LinkCustomAttributes = "";
			$this->incident_location->HrefValue = "";
			$this->incident_location->TooltipValue = "";

			// incident_sub_location
			$this->incident_sub_location->LinkCustomAttributes = "";
			$this->incident_sub_location->HrefValue = "";
			$this->incident_sub_location->TooltipValue = "";

			// incident_venue
			$this->incident_venue->LinkCustomAttributes = "";
			$this->incident_venue->HrefValue = "";
			$this->incident_venue->TooltipValue = "";

			// incident_description
			$this->incident_description->LinkCustomAttributes = "";
			$this->incident_description->HrefValue = "";
			$this->incident_description->TooltipValue = "";

			// upload
			$this->_upload->LinkCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->HrefValue = "%u"; // Add prefix/suffix
				$this->_upload->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->_upload->HrefValue = ew_FullUrl($this->_upload->HrefValue, "href");
			} else {
				$this->_upload->HrefValue = "";
			}
			$this->_upload->HrefValue2 = $this->_upload->UploadPath . $this->_upload->Upload->DbValue;
			$this->_upload->TooltipValue = "";
			if ($this->_upload->UseColorbox) {
				if (ew_Empty($this->_upload->TooltipValue))
					$this->_upload->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->_upload->LinkAttrs["data-rel"] = "report_form_x__upload";
				ew_AppendClass($this->_upload->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// rejection_reasons
			$this->rejection_reasons->LinkCustomAttributes = "";
			$this->rejection_reasons->HrefValue = "";
			$this->rejection_reasons->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

			// report_by
			$this->report_by->LinkCustomAttributes = "";
			$this->report_by->HrefValue = "";
			$this->report_by->TooltipValue = "";

			// datetime_resolved
			$this->datetime_resolved->LinkCustomAttributes = "";
			$this->datetime_resolved->HrefValue = "";
			$this->datetime_resolved->TooltipValue = "";

			// assign
			$this->assign->LinkCustomAttributes = "";
			$this->assign->HrefValue = "";
			$this->assign->TooltipValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";
			$this->approval_action->TooltipValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";
			$this->approval_comment->TooltipValue = "";

			// item_name
			$this->item_name->LinkCustomAttributes = "";
			$this->item_name->HrefValue = "";
			$this->item_name->TooltipValue = "";

			// quantity_issued
			$this->quantity_issued->LinkCustomAttributes = "";
			$this->quantity_issued->HrefValue = "";
			$this->quantity_issued->TooltipValue = "";

			// reason
			$this->reason->LinkCustomAttributes = "";
			$this->reason->HrefValue = "";
			$this->reason->TooltipValue = "";

			// resolved_action
			$this->resolved_action->LinkCustomAttributes = "";
			$this->resolved_action->HrefValue = "";
			$this->resolved_action->TooltipValue = "";

			// resolved_comment
			$this->resolved_comment->LinkCustomAttributes = "";
			$this->resolved_comment->HrefValue = "";
			$this->resolved_comment->TooltipValue = "";

			// resolved_by
			$this->resolved_by->LinkCustomAttributes = "";
			$this->resolved_by->HrefValue = "";
			$this->resolved_by->TooltipValue = "";

			// datetime_approved
			$this->datetime_approved->LinkCustomAttributes = "";
			$this->datetime_approved->HrefValue = "";
			$this->datetime_approved->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";

			// verified_datetime
			$this->verified_datetime->LinkCustomAttributes = "";
			$this->verified_datetime->HrefValue = "";
			$this->verified_datetime->TooltipValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";
			$this->job_assessment->TooltipValue = "";

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

			// remainder
			$this->remainder->LinkCustomAttributes = "";
			$this->remainder->HrefValue = "";
			$this->remainder->TooltipValue = "";

			// organization
			$this->organization->LinkCustomAttributes = "";
			$this->organization->HrefValue = "";
			$this->organization->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// datetime_initiated
			$this->datetime_initiated->EditAttrs["class"] = "form-control";
			$this->datetime_initiated->EditCustomAttributes = "";
			$this->datetime_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime_initiated->CurrentValue, 11));
			$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());

			// incident_id
			$this->incident_id->EditAttrs["class"] = "form-control";
			$this->incident_id->EditCustomAttributes = "";
			$this->incident_id->EditValue = ew_HtmlEncode($this->incident_id->CurrentValue);
			$this->incident_id->PlaceHolder = ew_RemoveHtml($this->incident_id->FldCaption());

			// staffid
			$this->staffid->EditAttrs["class"] = "form-control";
			$this->staffid->EditCustomAttributes = "";
			$this->staffid->EditValue = ew_HtmlEncode($this->staffid->CurrentValue);
			if (strval($this->staffid->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->staffid->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->staffid->EditValue = $this->staffid->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staffid->EditValue = ew_HtmlEncode($this->staffid->CurrentValue);
				}
			} else {
				$this->staffid->EditValue = NULL;
			}
			$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->staff_id->EditValue = $this->staff_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// department
			$this->department->EditCustomAttributes = "";
			if (trim(strval($this->department->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `depertment`";
			$sWhereWrk = "";
			$this->department->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `department_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->department->ViewValue = $this->department->DisplayValue($arwrk);
			} else {
				$this->department->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->department->EditValue = $arwrk;

			// branch
			$this->branch->EditCustomAttributes = "";
			if (trim(strval($this->branch->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `branch`";
			$sWhereWrk = "";
			$this->branch->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `branch_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
			} else {
				$this->branch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->branch->EditValue = $arwrk;

			// departments
			$this->departments->EditAttrs["class"] = "form-control";
			$this->departments->EditCustomAttributes = "";
			if (trim(strval($this->departments->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
			$sWhereWrk = "";
			$this->departments->LookupFilters = array();
			$lookuptblfilter = "`flag`='2'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->departments->EditValue = $arwrk;

			// category
			$this->category->EditAttrs["class"] = "form-control";
			$this->category->EditCustomAttributes = "";
			if (trim(strval($this->category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `category`";
			$sWhereWrk = "";
			$this->category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->category->EditValue = $arwrk;

			// sub_category
			$this->sub_category->EditCustomAttributes = "";
			if (trim(strval($this->sub_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `category_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sub-category`";
			$sWhereWrk = "";
			$this->sub_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
			} else {
				$this->sub_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->sub_category->EditValue = $arwrk;

			// sub_sub_category
			$this->sub_sub_category->EditCustomAttributes = "";
			if (trim(strval($this->sub_sub_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->sub_sub_category->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `sub_category_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sub_sub_category`";
			$sWhereWrk = "";
			$this->sub_sub_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->sub_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->sub_sub_category->ViewValue .= $this->sub_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->sub_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->sub_sub_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->sub_sub_category->EditValue = $arwrk;

			// selection_sub_category
			$this->selection_sub_category->EditCustomAttributes = "";
			if (trim(strval($this->selection_sub_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->selection_sub_category->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `selection_sub_category`";
			$sWhereWrk = "";
			$this->selection_sub_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->selection_sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->selection_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->selection_sub_category->ViewValue .= $this->selection_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->selection_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->selection_sub_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->selection_sub_category->EditValue = $arwrk;

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 7));
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// end_date
			$this->end_date->EditAttrs["class"] = "form-control";
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->end_date->CurrentValue, 7));
			$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

			// duration
			$this->duration->EditAttrs["class"] = "form-control";
			$this->duration->EditCustomAttributes = "";
			$this->duration->EditValue = ew_HtmlEncode($this->duration->CurrentValue);
			$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

			// amount_paid
			$this->amount_paid->EditAttrs["class"] = "form-control";
			$this->amount_paid->EditCustomAttributes = "";
			$this->amount_paid->EditValue = ew_HtmlEncode($this->amount_paid->CurrentValue);
			$this->amount_paid->PlaceHolder = ew_RemoveHtml($this->amount_paid->FldCaption());
			if (strval($this->amount_paid->EditValue) <> "" && is_numeric($this->amount_paid->EditValue)) $this->amount_paid->EditValue = ew_FormatNumber($this->amount_paid->EditValue, -2, -2, -2, -2);

			// no_of_people_involved
			$this->no_of_people_involved->EditAttrs["class"] = "form-control";
			$this->no_of_people_involved->EditCustomAttributes = "";
			$this->no_of_people_involved->EditValue = ew_HtmlEncode($this->no_of_people_involved->CurrentValue);
			$this->no_of_people_involved->PlaceHolder = ew_RemoveHtml($this->no_of_people_involved->FldCaption());

			// incident_type
			$this->incident_type->EditCustomAttributes = "";
			if (trim(strval($this->incident_type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `type_of_incident`";
			$sWhereWrk = "";
			$this->incident_type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
			} else {
				$this->incident_type->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_type->EditValue = $arwrk;

			// incident-category
			$this->incident_category->EditCustomAttributes = "";
			if (trim(strval($this->incident_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident-category`";
			$sWhereWrk = "";
			$this->incident_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
			} else {
				$this->incident_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_category->EditValue = $arwrk;

			// incident_location
			$this->incident_location->EditCustomAttributes = "";
			if (trim(strval($this->incident_location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_location`";
			$sWhereWrk = "";
			$this->incident_location->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
			} else {
				$this->incident_location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_location->EditValue = $arwrk;

			// incident_sub_location
			$this->incident_sub_location->EditAttrs["class"] = "form-control";
			$this->incident_sub_location->EditCustomAttributes = "";
			if (trim(strval($this->incident_sub_location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->incident_sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_sub_location`";
			$sWhereWrk = "";
			$this->incident_sub_location->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_sub_location->EditValue = $arwrk;

			// incident_venue
			$this->incident_venue->EditAttrs["class"] = "form-control";
			$this->incident_venue->EditCustomAttributes = "";
			if (trim(strval($this->incident_venue->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_venue->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_sub` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_venue`";
			$sWhereWrk = "";
			$this->incident_venue->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_venue->EditValue = $arwrk;

			// incident_description
			$this->incident_description->EditAttrs["class"] = "form-control";
			$this->incident_description->EditCustomAttributes = "";
			$this->incident_description->EditValue = ew_HtmlEncode($this->incident_description->CurrentValue);
			$this->incident_description->PlaceHolder = ew_RemoveHtml($this->incident_description->FldCaption());

			// upload
			$this->_upload->EditAttrs["class"] = "form-control";
			$this->_upload->EditCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->ImageAlt = $this->_upload->FldAlt();
				$this->_upload->EditValue = $this->_upload->Upload->DbValue;
			} else {
				$this->_upload->EditValue = "";
			}
			if (!ew_Empty($this->_upload->CurrentValue))
					$this->_upload->Upload->FileName = $this->_upload->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->_upload);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// rejection_reasons
			$this->rejection_reasons->EditAttrs["class"] = "form-control";
			$this->rejection_reasons->EditCustomAttributes = "";
			$this->rejection_reasons->EditValue = ew_HtmlEncode($this->rejection_reasons->CurrentValue);
			$this->rejection_reasons->PlaceHolder = ew_RemoveHtml($this->rejection_reasons->FldCaption());

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// report_by
			$this->report_by->EditAttrs["class"] = "form-control";
			$this->report_by->EditCustomAttributes = "";
			$this->report_by->EditValue = ew_HtmlEncode($this->report_by->CurrentValue);
			if (strval($this->report_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->report_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->report_by->EditValue = $this->report_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->report_by->EditValue = ew_HtmlEncode($this->report_by->CurrentValue);
				}
			} else {
				$this->report_by->EditValue = NULL;
			}
			$this->report_by->PlaceHolder = ew_RemoveHtml($this->report_by->FldCaption());

			// datetime_resolved
			$this->datetime_resolved->EditAttrs["class"] = "form-control";
			$this->datetime_resolved->EditCustomAttributes = "";
			$this->datetime_resolved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime_resolved->CurrentValue, 11));
			$this->datetime_resolved->PlaceHolder = ew_RemoveHtml($this->datetime_resolved->FldCaption());

			// assign
			$this->assign->EditCustomAttributes = "";
			if (trim(strval($this->assign->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->assign->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->assign, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->assign->ViewValue = $this->assign->DisplayValue($arwrk);
			} else {
				$this->assign->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->assign->EditValue = $arwrk;

			// approval_action
			$this->approval_action->EditCustomAttributes = "";
			$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

			// approval_comment
			$this->approval_comment->EditAttrs["class"] = "form-control";
			$this->approval_comment->EditCustomAttributes = "";
			$this->approval_comment->EditValue = ew_HtmlEncode($this->approval_comment->CurrentValue);
			$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

			// item_name
			$this->item_name->EditAttrs["class"] = "form-control";
			$this->item_name->EditCustomAttributes = "";
			if (trim(strval($this->item_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->item_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `inventory`";
			$sWhereWrk = "";
			$this->item_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->item_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->item_name->EditValue = $arwrk;

			// quantity_issued
			$this->quantity_issued->EditAttrs["class"] = "form-control";
			$this->quantity_issued->EditCustomAttributes = "";
			$this->quantity_issued->EditValue = ew_HtmlEncode($this->quantity_issued->CurrentValue);
			$this->quantity_issued->PlaceHolder = ew_RemoveHtml($this->quantity_issued->FldCaption());

			// reason
			$this->reason->EditAttrs["class"] = "form-control";
			$this->reason->EditCustomAttributes = "";
			if (trim(strval($this->reason->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->reason->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `reason`";
			$sWhereWrk = "";
			$this->reason->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->reason->EditValue = $arwrk;

			// resolved_action
			$this->resolved_action->EditCustomAttributes = "";
			$this->resolved_action->EditValue = $this->resolved_action->Options(FALSE);

			// resolved_comment
			$this->resolved_comment->EditAttrs["class"] = "form-control";
			$this->resolved_comment->EditCustomAttributes = "";
			$this->resolved_comment->EditValue = ew_HtmlEncode($this->resolved_comment->CurrentValue);
			$this->resolved_comment->PlaceHolder = ew_RemoveHtml($this->resolved_comment->FldCaption());

			// resolved_by
			$this->resolved_by->EditAttrs["class"] = "form-control";
			$this->resolved_by->EditCustomAttributes = "";
			$this->resolved_by->EditValue = ew_HtmlEncode($this->resolved_by->CurrentValue);
			if (strval($this->resolved_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->resolved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->resolved_by->EditValue = $this->resolved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->resolved_by->EditValue = ew_HtmlEncode($this->resolved_by->CurrentValue);
				}
			} else {
				$this->resolved_by->EditValue = NULL;
			}
			$this->resolved_by->PlaceHolder = ew_RemoveHtml($this->resolved_by->FldCaption());

			// datetime_approved
			$this->datetime_approved->EditAttrs["class"] = "form-control";
			$this->datetime_approved->EditCustomAttributes = "";
			$this->datetime_approved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime_approved->CurrentValue, 11));
			$this->datetime_approved->PlaceHolder = ew_RemoveHtml($this->datetime_approved->FldCaption());

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

			// last_updated_date
			$this->last_updated_date->EditAttrs["class"] = "form-control";
			$this->last_updated_date->EditCustomAttributes = "";
			$this->last_updated_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_updated_date->CurrentValue, 17));
			$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

			// last_updated_by
			$this->last_updated_by->EditAttrs["class"] = "form-control";
			$this->last_updated_by->EditCustomAttributes = "";
			$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->CurrentValue);
			if (strval($this->last_updated_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->last_updated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->last_updated_by->EditValue = $this->last_updated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->CurrentValue);
				}
			} else {
				$this->last_updated_by->EditValue = NULL;
			}
			$this->last_updated_by->PlaceHolder = ew_RemoveHtml($this->last_updated_by->FldCaption());

			// verified_datetime
			$this->verified_datetime->EditAttrs["class"] = "form-control";
			$this->verified_datetime->EditCustomAttributes = "";
			$this->verified_datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->verified_datetime->CurrentValue, 17));
			$this->verified_datetime->PlaceHolder = ew_RemoveHtml($this->verified_datetime->FldCaption());

			// job_assessment
			$this->job_assessment->EditCustomAttributes = "";
			$this->job_assessment->EditValue = $this->job_assessment->Options(FALSE);

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

			// remainder
			$this->remainder->EditAttrs["class"] = "form-control";
			$this->remainder->EditCustomAttributes = "";
			if (trim(strval($this->remainder->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->remainder->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->remainder->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->remainder, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->remainder->EditValue = $arwrk;

			// organization
			$this->organization->EditAttrs["class"] = "form-control";
			$this->organization->EditCustomAttributes = "";
			$this->organization->EditValue = ew_HtmlEncode($this->organization->CurrentValue);
			$this->organization->PlaceHolder = ew_RemoveHtml($this->organization->FldCaption());

			// Add refer script
			// datetime_initiated

			$this->datetime_initiated->LinkCustomAttributes = "";
			$this->datetime_initiated->HrefValue = "";

			// incident_id
			$this->incident_id->LinkCustomAttributes = "";
			$this->incident_id->HrefValue = "";

			// staffid
			$this->staffid->LinkCustomAttributes = "";
			$this->staffid->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";

			// departments
			$this->departments->LinkCustomAttributes = "";
			$this->departments->HrefValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";

			// sub_category
			$this->sub_category->LinkCustomAttributes = "";
			$this->sub_category->HrefValue = "";

			// sub_sub_category
			$this->sub_sub_category->LinkCustomAttributes = "";
			$this->sub_sub_category->HrefValue = "";

			// selection_sub_category
			$this->selection_sub_category->LinkCustomAttributes = "";
			$this->selection_sub_category->HrefValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";

			// duration
			$this->duration->LinkCustomAttributes = "";
			$this->duration->HrefValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";

			// no_of_people_involved
			$this->no_of_people_involved->LinkCustomAttributes = "";
			$this->no_of_people_involved->HrefValue = "";

			// incident_type
			$this->incident_type->LinkCustomAttributes = "";
			$this->incident_type->HrefValue = "";

			// incident-category
			$this->incident_category->LinkCustomAttributes = "";
			$this->incident_category->HrefValue = "";

			// incident_location
			$this->incident_location->LinkCustomAttributes = "";
			$this->incident_location->HrefValue = "";

			// incident_sub_location
			$this->incident_sub_location->LinkCustomAttributes = "";
			$this->incident_sub_location->HrefValue = "";

			// incident_venue
			$this->incident_venue->LinkCustomAttributes = "";
			$this->incident_venue->HrefValue = "";

			// incident_description
			$this->incident_description->LinkCustomAttributes = "";
			$this->incident_description->HrefValue = "";

			// upload
			$this->_upload->LinkCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->HrefValue = "%u"; // Add prefix/suffix
				$this->_upload->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->_upload->HrefValue = ew_FullUrl($this->_upload->HrefValue, "href");
			} else {
				$this->_upload->HrefValue = "";
			}
			$this->_upload->HrefValue2 = $this->_upload->UploadPath . $this->_upload->Upload->DbValue;

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// rejection_reasons
			$this->rejection_reasons->LinkCustomAttributes = "";
			$this->rejection_reasons->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

			// report_by
			$this->report_by->LinkCustomAttributes = "";
			$this->report_by->HrefValue = "";

			// datetime_resolved
			$this->datetime_resolved->LinkCustomAttributes = "";
			$this->datetime_resolved->HrefValue = "";

			// assign
			$this->assign->LinkCustomAttributes = "";
			$this->assign->HrefValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";

			// item_name
			$this->item_name->LinkCustomAttributes = "";
			$this->item_name->HrefValue = "";

			// quantity_issued
			$this->quantity_issued->LinkCustomAttributes = "";
			$this->quantity_issued->HrefValue = "";

			// reason
			$this->reason->LinkCustomAttributes = "";
			$this->reason->HrefValue = "";

			// resolved_action
			$this->resolved_action->LinkCustomAttributes = "";
			$this->resolved_action->HrefValue = "";

			// resolved_comment
			$this->resolved_comment->LinkCustomAttributes = "";
			$this->resolved_comment->HrefValue = "";

			// resolved_by
			$this->resolved_by->LinkCustomAttributes = "";
			$this->resolved_by->HrefValue = "";

			// datetime_approved
			$this->datetime_approved->LinkCustomAttributes = "";
			$this->datetime_approved->HrefValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";

			// verified_datetime
			$this->verified_datetime->LinkCustomAttributes = "";
			$this->verified_datetime->HrefValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";

			// remainder
			$this->remainder->LinkCustomAttributes = "";
			$this->remainder->HrefValue = "";

			// organization
			$this->organization->LinkCustomAttributes = "";
			$this->organization->HrefValue = "";
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
		if (!$this->datetime_initiated->FldIsDetailKey && !is_null($this->datetime_initiated->FormValue) && $this->datetime_initiated->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datetime_initiated->FldCaption(), $this->datetime_initiated->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datetime_initiated->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime_initiated->FldErrMsg());
		}
		if (!$this->incident_id->FldIsDetailKey && !is_null($this->incident_id->FormValue) && $this->incident_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_id->FldCaption(), $this->incident_id->ReqErrMsg));
		}
		if (!$this->staffid->FldIsDetailKey && !is_null($this->staffid->FormValue) && $this->staffid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffid->FldCaption(), $this->staffid->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->staff_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->staff_id->FldErrMsg());
		}
		if (!$this->department->FldIsDetailKey && !is_null($this->department->FormValue) && $this->department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->department->FldCaption(), $this->department->ReqErrMsg));
		}
		if (!$this->branch->FldIsDetailKey && !is_null($this->branch->FormValue) && $this->branch->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->branch->FldCaption(), $this->branch->ReqErrMsg));
		}
		if (!$this->departments->FldIsDetailKey && !is_null($this->departments->FormValue) && $this->departments->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->departments->FldCaption(), $this->departments->ReqErrMsg));
		}
		if (!$this->category->FldIsDetailKey && !is_null($this->category->FormValue) && $this->category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->category->FldCaption(), $this->category->ReqErrMsg));
		}
		if (!$this->sub_category->FldIsDetailKey && !is_null($this->sub_category->FormValue) && $this->sub_category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sub_category->FldCaption(), $this->sub_category->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->start_date->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->end_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->end_date->FldErrMsg());
		}
		if (!$this->duration->FldIsDetailKey && !is_null($this->duration->FormValue) && $this->duration->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->duration->FldCaption(), $this->duration->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->duration->FormValue)) {
			ew_AddMessage($gsFormError, $this->duration->FldErrMsg());
		}
		if (!ew_CheckNumber($this->amount_paid->FormValue)) {
			ew_AddMessage($gsFormError, $this->amount_paid->FldErrMsg());
		}
		if (!$this->incident_category->FldIsDetailKey && !is_null($this->incident_category->FormValue) && $this->incident_category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_category->FldCaption(), $this->incident_category->ReqErrMsg));
		}
		if (!$this->incident_location->FldIsDetailKey && !is_null($this->incident_location->FormValue) && $this->incident_location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_location->FldCaption(), $this->incident_location->ReqErrMsg));
		}
		if (!$this->incident_sub_location->FldIsDetailKey && !is_null($this->incident_sub_location->FormValue) && $this->incident_sub_location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_sub_location->FldCaption(), $this->incident_sub_location->ReqErrMsg));
		}
		if (!$this->incident_description->FldIsDetailKey && !is_null($this->incident_description->FormValue) && $this->incident_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_description->FldCaption(), $this->incident_description->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if ($this->initiator_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiator_action->FldCaption(), $this->initiator_action->ReqErrMsg));
		}
		if (!$this->initiator_comment->FldIsDetailKey && !is_null($this->initiator_comment->FormValue) && $this->initiator_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiator_comment->FldCaption(), $this->initiator_comment->ReqErrMsg));
		}
		if (!$this->report_by->FldIsDetailKey && !is_null($this->report_by->FormValue) && $this->report_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->report_by->FldCaption(), $this->report_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->report_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->report_by->FldErrMsg());
		}
		if (!$this->datetime_resolved->FldIsDetailKey && !is_null($this->datetime_resolved->FormValue) && $this->datetime_resolved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datetime_resolved->FldCaption(), $this->datetime_resolved->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datetime_resolved->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime_resolved->FldErrMsg());
		}
		if (!ew_CheckInteger($this->quantity_issued->FormValue)) {
			ew_AddMessage($gsFormError, $this->quantity_issued->FldErrMsg());
		}
		if (!$this->reason->FldIsDetailKey && !is_null($this->reason->FormValue) && $this->reason->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reason->FldCaption(), $this->reason->ReqErrMsg));
		}
		if ($this->resolved_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->resolved_action->FldCaption(), $this->resolved_action->ReqErrMsg));
		}
		if (!$this->resolved_by->FldIsDetailKey && !is_null($this->resolved_by->FormValue) && $this->resolved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->resolved_by->FldCaption(), $this->resolved_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->resolved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->resolved_by->FldErrMsg());
		}
		if (!$this->datetime_approved->FldIsDetailKey && !is_null($this->datetime_approved->FormValue) && $this->datetime_approved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datetime_approved->FldCaption(), $this->datetime_approved->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datetime_approved->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime_approved->FldErrMsg());
		}
		if (!$this->approved_by->FldIsDetailKey && !is_null($this->approved_by->FormValue) && $this->approved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approved_by->FldCaption(), $this->approved_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->approved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_by->FldErrMsg());
		}
		if (!$this->last_updated_date->FldIsDetailKey && !is_null($this->last_updated_date->FormValue) && $this->last_updated_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->last_updated_date->FldCaption(), $this->last_updated_date->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->last_updated_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_updated_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->last_updated_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_updated_by->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->verified_datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_datetime->FldErrMsg());
		}
		if ($this->job_assessment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->job_assessment->FldCaption(), $this->job_assessment->ReqErrMsg));
		}
		if ($this->verified_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->verified_action->FldCaption(), $this->verified_action->ReqErrMsg));
		}
		if (!$this->verified_comment->FldIsDetailKey && !is_null($this->verified_comment->FormValue) && $this->verified_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->verified_comment->FldCaption(), $this->verified_comment->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->verified_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_by->FldErrMsg());
		}
		if (!ew_CheckInteger($this->organization->FormValue)) {
			ew_AddMessage($gsFormError, $this->organization->FldErrMsg());
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
			$this->_upload->OldUploadPath = "picture/";
			$this->_upload->UploadPath = $this->_upload->OldUploadPath;
		}
		$rsnew = array();

		// datetime_initiated
		$this->datetime_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime_initiated->CurrentValue, 11), ew_CurrentDate(), FALSE);

		// incident_id
		$this->incident_id->SetDbValueDef($rsnew, $this->incident_id->CurrentValue, NULL, FALSE);

		// staffid
		$this->staffid->SetDbValueDef($rsnew, $this->staffid->CurrentValue, NULL, FALSE);

		// staff_id
		$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, FALSE);

		// department
		$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, FALSE);

		// branch
		$this->branch->SetDbValueDef($rsnew, $this->branch->CurrentValue, NULL, FALSE);

		// departments
		$this->departments->SetDbValueDef($rsnew, $this->departments->CurrentValue, NULL, FALSE);

		// category
		$this->category->SetDbValueDef($rsnew, $this->category->CurrentValue, NULL, FALSE);

		// sub_category
		$this->sub_category->SetDbValueDef($rsnew, $this->sub_category->CurrentValue, NULL, FALSE);

		// sub_sub_category
		$this->sub_sub_category->SetDbValueDef($rsnew, $this->sub_sub_category->CurrentValue, NULL, FALSE);

		// selection_sub_category
		$this->selection_sub_category->SetDbValueDef($rsnew, $this->selection_sub_category->CurrentValue, NULL, FALSE);

		// start_date
		$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 7), NULL, FALSE);

		// end_date
		$this->end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->end_date->CurrentValue, 7), NULL, FALSE);

		// duration
		$this->duration->SetDbValueDef($rsnew, $this->duration->CurrentValue, 0, FALSE);

		// amount_paid
		$this->amount_paid->SetDbValueDef($rsnew, $this->amount_paid->CurrentValue, NULL, FALSE);

		// no_of_people_involved
		$this->no_of_people_involved->SetDbValueDef($rsnew, $this->no_of_people_involved->CurrentValue, NULL, FALSE);

		// incident_type
		$this->incident_type->SetDbValueDef($rsnew, $this->incident_type->CurrentValue, NULL, FALSE);

		// incident-category
		$this->incident_category->SetDbValueDef($rsnew, $this->incident_category->CurrentValue, NULL, FALSE);

		// incident_location
		$this->incident_location->SetDbValueDef($rsnew, $this->incident_location->CurrentValue, NULL, FALSE);

		// incident_sub_location
		$this->incident_sub_location->SetDbValueDef($rsnew, $this->incident_sub_location->CurrentValue, NULL, FALSE);

		// incident_venue
		$this->incident_venue->SetDbValueDef($rsnew, $this->incident_venue->CurrentValue, NULL, FALSE);

		// incident_description
		$this->incident_description->SetDbValueDef($rsnew, $this->incident_description->CurrentValue, NULL, FALSE);

		// upload
		if ($this->_upload->Visible && !$this->_upload->Upload->KeepFile) {
			$this->_upload->Upload->DbValue = ""; // No need to delete old file
			if ($this->_upload->Upload->FileName == "") {
				$rsnew['upload'] = NULL;
			} else {
				$rsnew['upload'] = $this->_upload->Upload->FileName;
			}
			$this->_upload->ImageWidth = 1000; // Resize width
			$this->_upload->ImageHeight = 0; // Resize height
		}

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// rejection_reasons
		$this->rejection_reasons->SetDbValueDef($rsnew, $this->rejection_reasons->CurrentValue, NULL, FALSE);

		// initiator_action
		$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, FALSE);

		// initiator_comment
		$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, FALSE);

		// report_by
		$this->report_by->SetDbValueDef($rsnew, $this->report_by->CurrentValue, NULL, FALSE);

		// datetime_resolved
		$this->datetime_resolved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime_resolved->CurrentValue, 11), NULL, FALSE);

		// assign
		$this->assign->SetDbValueDef($rsnew, $this->assign->CurrentValue, NULL, FALSE);

		// approval_action
		$this->approval_action->SetDbValueDef($rsnew, $this->approval_action->CurrentValue, NULL, FALSE);

		// approval_comment
		$this->approval_comment->SetDbValueDef($rsnew, $this->approval_comment->CurrentValue, NULL, FALSE);

		// item_name
		$this->item_name->SetDbValueDef($rsnew, $this->item_name->CurrentValue, NULL, FALSE);

		// quantity_issued
		$this->quantity_issued->SetDbValueDef($rsnew, $this->quantity_issued->CurrentValue, NULL, FALSE);

		// reason
		$this->reason->SetDbValueDef($rsnew, $this->reason->CurrentValue, NULL, FALSE);

		// resolved_action
		$this->resolved_action->SetDbValueDef($rsnew, $this->resolved_action->CurrentValue, NULL, FALSE);

		// resolved_comment
		$this->resolved_comment->SetDbValueDef($rsnew, $this->resolved_comment->CurrentValue, NULL, FALSE);

		// resolved_by
		$this->resolved_by->SetDbValueDef($rsnew, $this->resolved_by->CurrentValue, NULL, FALSE);

		// datetime_approved
		$this->datetime_approved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime_approved->CurrentValue, 11), ew_CurrentDate(), FALSE);

		// approved_by
		$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, FALSE);

		// last_updated_date
		$this->last_updated_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_updated_date->CurrentValue, 17), ew_CurrentDate(), FALSE);

		// last_updated_by
		$this->last_updated_by->SetDbValueDef($rsnew, $this->last_updated_by->CurrentValue, NULL, FALSE);

		// verified_datetime
		$this->verified_datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->verified_datetime->CurrentValue, 17), NULL, FALSE);

		// job_assessment
		$this->job_assessment->SetDbValueDef($rsnew, $this->job_assessment->CurrentValue, NULL, FALSE);

		// verified_action
		$this->verified_action->SetDbValueDef($rsnew, $this->verified_action->CurrentValue, NULL, FALSE);

		// verified_comment
		$this->verified_comment->SetDbValueDef($rsnew, $this->verified_comment->CurrentValue, NULL, FALSE);

		// verified_by
		$this->verified_by->SetDbValueDef($rsnew, $this->verified_by->CurrentValue, NULL, FALSE);

		// remainder
		$this->remainder->SetDbValueDef($rsnew, $this->remainder->CurrentValue, NULL, FALSE);

		// organization
		$this->organization->SetDbValueDef($rsnew, $this->organization->CurrentValue, NULL, FALSE);
		if ($this->_upload->Visible && !$this->_upload->Upload->KeepFile) {
			$this->_upload->UploadPath = "picture/";
			$OldFiles = ew_Empty($this->_upload->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->_upload->Upload->DbValue));
			if (!ew_Empty($this->_upload->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->_upload->Upload->FileName));
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->_upload->Upload->Index < 0) ? $this->_upload->FldVar : substr($this->_upload->FldVar, 0, 1) . $this->_upload->Upload->Index . substr($this->_upload->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file)) {
							$OldFileFound = FALSE;
							$OldFileCount = count($OldFiles);
							for ($j = 0; $j < $OldFileCount; $j++) {
								$file1 = $OldFiles[$j];
								if ($file1 == $file) { // Old file found, no need to delete anymore
									unset($OldFiles[$j]);
									$OldFileFound = TRUE;
									break;
								}
							}
							if ($OldFileFound) // No need to check if file exists further
								continue;
							$file1 = ew_UploadFileNameEx($this->_upload->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file1) || file_exists($this->_upload->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->_upload->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file, ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->_upload->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->_upload->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->_upload->SetDbValueDef($rsnew, $this->_upload->Upload->FileName, NULL, FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->_upload->Visible && !$this->_upload->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->_upload->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->_upload->Upload->DbValue));
					if (!ew_Empty($this->_upload->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->_upload->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['upload']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->_upload->Upload->Index < 0) ? $this->_upload->FldVar : substr($this->_upload->FldVar, 0, 1) . $this->_upload->Upload->Index . substr($this->_upload->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->_upload->Upload->ResizeAndSaveToFile($this->_upload->ImageWidth, $this->_upload->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$OldFileCount = count($OldFiles);
					for ($i = 0; $i < $OldFileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink($this->_upload->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
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

		// upload
		ew_CleanUploadTempPath($this->_upload, $this->_upload->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("report_formlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staffid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id` AS `LinkFld`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`department_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `department_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_branch":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id` AS `LinkFld`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `branch_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_departments":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$lookuptblfilter = "`flag`='2'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `category_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`category_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sub_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sub-category_id` AS `LinkFld`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sub-category_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`category_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sub_sub_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_sub_category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`sub_category_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_selection_sub_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `selection_sub_category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->selection_sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_sub_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_sub` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_sub` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_venue":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_sub` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_report_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_assign":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->assign, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_item_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->item_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_reason":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `reason`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_resolved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_last_updated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_remainder":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->remainder, $sWhereWrk); // Call Lookup Selecting
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
		case "x_staffid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld` FROM `users`";
			$sWhereWrk = "`staffno` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->staff_id) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_report_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->report_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_resolved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->resolved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
		case "x_last_updated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->last_updated_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->last_updated_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
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
			//	ew_SetClientVar("Report_formDetailsSearchModel", ew_Encrypt("SELECT `material_name`,`quantity` FROM `inventory` WHERE `id`= {query_value}"));

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
		//$this->quantity_issued->CustomMsg .= "<div class='small' id='lnmessage' style='padding-top:3px; color:blue;'></div>";
		//$this->item_name->CustomMsg .= "<div class='small' id='itemName' style='padding-top:3px; color:blue;'></div>";

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
if (!isset($report_form_add)) $report_form_add = new creport_form_add();

// Page init
$report_form_add->Page_Init();

// Page main
$report_form_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$report_form_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = freport_formadd = new ew_Form("freport_formadd", "add");

// Validate form
freport_formadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_datetime_initiated");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->datetime_initiated->FldCaption(), $report_form->datetime_initiated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datetime_initiated");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->datetime_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_incident_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->incident_id->FldCaption(), $report_form->incident_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->staffid->FldCaption(), $report_form->staffid->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->staff_id->FldCaption(), $report_form->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->staff_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->department->FldCaption(), $report_form->department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_branch");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->branch->FldCaption(), $report_form->branch->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_departments");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->departments->FldCaption(), $report_form->departments->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->category->FldCaption(), $report_form->category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sub_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->sub_category->FldCaption(), $report_form->sub_category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_start_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->start_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_end_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->end_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_duration");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->duration->FldCaption(), $report_form->duration->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duration");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->duration->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_amount_paid");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->amount_paid->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_incident_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->incident_category->FldCaption(), $report_form->incident_category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_incident_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->incident_location->FldCaption(), $report_form->incident_location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_incident_sub_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->incident_sub_location->FldCaption(), $report_form->incident_sub_location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_incident_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->incident_description->FldCaption(), $report_form->incident_description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->status->FldCaption(), $report_form->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->initiator_action->FldCaption(), $report_form->initiator_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->initiator_comment->FldCaption(), $report_form->initiator_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_report_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->report_by->FldCaption(), $report_form->report_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_report_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->report_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_datetime_resolved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->datetime_resolved->FldCaption(), $report_form->datetime_resolved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datetime_resolved");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->datetime_resolved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_quantity_issued");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->quantity_issued->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reason");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->reason->FldCaption(), $report_form->reason->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resolved_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->resolved_action->FldCaption(), $report_form->resolved_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resolved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->resolved_by->FldCaption(), $report_form->resolved_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resolved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->resolved_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_datetime_approved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->datetime_approved->FldCaption(), $report_form->datetime_approved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datetime_approved");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->datetime_approved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->approved_by->FldCaption(), $report_form->approved_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->approved_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_updated_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->last_updated_date->FldCaption(), $report_form->last_updated_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_last_updated_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->last_updated_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_updated_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->last_updated_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_datetime");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->verified_datetime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_job_assessment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->job_assessment->FldCaption(), $report_form->job_assessment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->verified_action->FldCaption(), $report_form->verified_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report_form->verified_comment->FldCaption(), $report_form->verified_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->verified_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_organization");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->organization->FldErrMsg()) ?>");

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
freport_formadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freport_formadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
freport_formadd.MultiPage = new ew_MultiPage("freport_formadd");

// Dynamic selection lists
freport_formadd.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_staffid"].Data = "<?php echo $report_form_add->staffid->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->staffid->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_staff_id"].Data = "<?php echo $report_form_add->staff_id->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->staff_id->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
freport_formadd.Lists["x_department"].Data = "<?php echo $report_form_add->department->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
freport_formadd.Lists["x_branch"].Data = "<?php echo $report_form_add->branch->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freport_formadd.Lists["x_departments"].Data = "<?php echo $report_form_add->departments->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_departments"],"ChildFields":["x_sub_category"],"FilterFields":["x_code_id"],"Options":[],"Template":"","LinkTable":"category"};
freport_formadd.Lists["x_category"].Data = "<?php echo $report_form_add->category->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":["x_category"],"ChildFields":["x_sub_sub_category[]"],"FilterFields":["x_category_id"],"Options":[],"Template":"","LinkTable":"sub_category"};
freport_formadd.Lists["x_sub_category"].Data = "<?php echo $report_form_add->sub_category->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_sub_sub_category[]"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_sub_category"],"ChildFields":["x_selection_sub_category[]"],"FilterFields":["x_sub_category_id"],"Options":[],"Template":"","LinkTable":"sub_sub_category"};
freport_formadd.Lists["x_sub_sub_category[]"].Data = "<?php echo $report_form_add->sub_sub_category->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_selection_sub_category[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_sub_sub_category[]"],"ChildFields":[],"FilterFields":["x_code"],"Options":[],"Template":"","LinkTable":"selection_sub_category"};
freport_formadd.Lists["x_selection_sub_category[]"].Data = "<?php echo $report_form_add->selection_sub_category->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_incident_type"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"type_of_incident"};
freport_formadd.Lists["x_incident_type"].Data = "<?php echo $report_form_add->incident_type->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_incident_category"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
freport_formadd.Lists["x_incident_category"].Data = "<?php echo $report_form_add->incident_category->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_incident_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freport_formadd.Lists["x_incident_location"].Data = "<?php echo $report_form_add->incident_location->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_incident_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_incident_location"],"ChildFields":["x_incident_venue"],"FilterFields":["x_code_id"],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
freport_formadd.Lists["x_incident_sub_location"].Data = "<?php echo $report_form_add->incident_sub_location->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_incident_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_incident_sub_location"],"ChildFields":[],"FilterFields":["x_code_sub"],"Options":[],"Template":"","LinkTable":"incident_venue"};
freport_formadd.Lists["x_incident_venue"].Data = "<?php echo $report_form_add->incident_venue->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freport_formadd.Lists["x_status"].Data = "<?php echo $report_form_add->status->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formadd.Lists["x_initiator_action"].Options = <?php echo json_encode($report_form_add->initiator_action->Options()) ?>;
freport_formadd.Lists["x_report_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_report_by"].Data = "<?php echo $report_form_add->report_by->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_report_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->report_by->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_assign"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_assign"].Data = "<?php echo $report_form_add->assign->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formadd.Lists["x_approval_action"].Options = <?php echo json_encode($report_form_add->approval_action->Options()) ?>;
freport_formadd.Lists["x_item_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory"};
freport_formadd.Lists["x_item_name"].Data = "<?php echo $report_form_add->item_name->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_reason"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"reason"};
freport_formadd.Lists["x_reason"].Data = "<?php echo $report_form_add->reason->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.Lists["x_resolved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formadd.Lists["x_resolved_action"].Options = <?php echo json_encode($report_form_add->resolved_action->Options()) ?>;
freport_formadd.Lists["x_resolved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_resolved_by"].Data = "<?php echo $report_form_add->resolved_by->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_resolved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->resolved_by->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_approved_by"].Data = "<?php echo $report_form_add->approved_by->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->approved_by->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_last_updated_by"].Data = "<?php echo $report_form_add->last_updated_by->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->last_updated_by->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_job_assessment"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formadd.Lists["x_job_assessment"].Options = <?php echo json_encode($report_form_add->job_assessment->Options()) ?>;
freport_formadd.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formadd.Lists["x_verified_action"].Options = <?php echo json_encode($report_form_add->verified_action->Options()) ?>;
freport_formadd.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_verified_by"].Data = "<?php echo $report_form_add->verified_by->LookupFilterQuery(FALSE, "add") ?>";
freport_formadd.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_add->verified_by->LookupFilterQuery(TRUE, "add"))) ?>;
freport_formadd.Lists["x_remainder"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formadd.Lists["x_remainder"].Data = "<?php echo $report_form_add->remainder->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
//$('#lnmessage').html('Your message goes here');

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
	  if (this.value == 13) {
		$("#r_category").show();			   
		$("#r_sub_category").show();
		$("#r_incident_category").show();
		$("#r_incident_description").show();
		$("#r_incident_location").show();
		$("#r_incident_venue").show();
		$("#r_incident_sub_location").show();
		$("#r_selection_sub_category").show();
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
<?php $report_form_add->ShowPageHeader(); ?>
<?php
$report_form_add->ShowMessage();
?>
<form name="freport_formadd" id="freport_formadd" class="<?php echo $report_form_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($report_form_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $report_form_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="report_form">
<?php if ($report_form->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_add" id="a_add" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($report_form_add->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="report_form_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $report_form_add->MultiPages->NavStyle() ?>">
		<li<?php echo $report_form_add->MultiPages->TabStyle("1") ?>><a href="#tab_report_form1" data-toggle="tab"><?php echo $report_form->PageCaption(1) ?></a></li>
		<li<?php echo $report_form_add->MultiPages->TabStyle("2") ?>><a href="#tab_report_form2" data-toggle="tab"><?php echo $report_form->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $report_form_add->MultiPages->PageStyle("1") ?>" id="tab_report_form1"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($report_form->datetime_initiated->Visible) { // datetime_initiated ?>
	<div id="r_datetime_initiated" class="form-group">
		<label id="elh_report_form_datetime_initiated" for="x_datetime_initiated" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->datetime_initiated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->datetime_initiated->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_datetime_initiated">
<input type="text" data-table="report_form" data-field="x_datetime_initiated" data-page="1" data-format="11" name="x_datetime_initiated" id="x_datetime_initiated" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->datetime_initiated->getPlaceHolder()) ?>" value="<?php echo $report_form->datetime_initiated->EditValue ?>"<?php echo $report_form->datetime_initiated->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_datetime_initiated">
<span<?php echo $report_form->datetime_initiated->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->datetime_initiated->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_datetime_initiated" data-page="1" name="x_datetime_initiated" id="x_datetime_initiated" value="<?php echo ew_HtmlEncode($report_form->datetime_initiated->FormValue) ?>">
<?php } ?>
<?php echo $report_form->datetime_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_id->Visible) { // incident_id ?>
	<div id="r_incident_id" class="form-group">
		<label id="elh_report_form_incident_id" for="x_incident_id" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_id->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_id">
<input type="text" data-table="report_form" data-field="x_incident_id" data-page="1" name="x_incident_id" id="x_incident_id" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->incident_id->getPlaceHolder()) ?>" value="<?php echo $report_form->incident_id->EditValue ?>"<?php echo $report_form->incident_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_incident_id">
<span<?php echo $report_form->incident_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_id" data-page="1" name="x_incident_id" id="x_incident_id" value="<?php echo ew_HtmlEncode($report_form->incident_id->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->staffid->Visible) { // staffid ?>
	<div id="r_staffid" class="form-group">
		<label id="elh_report_form_staffid" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->staffid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->staffid->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_staffid">
<?php
$wrkonchange = trim(" " . @$report_form->staffid->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->staffid->EditAttrs["onchange"] = "";
?>
<span id="as_x_staffid" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staffid" id="sv_x_staffid" value="<?php echo $report_form->staffid->EditValue ?>" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->staffid->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->staffid->getPlaceHolder()) ?>"<?php echo $report_form->staffid->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_staffid" data-page="1" data-value-separator="<?php echo $report_form->staffid->DisplayValueSeparatorAttribute() ?>" name="x_staffid" id="x_staffid" value="<?php echo ew_HtmlEncode($report_form->staffid->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_staffid","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_staffid">
<span<?php echo $report_form->staffid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->staffid->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_staffid" data-page="1" name="x_staffid" id="x_staffid" value="<?php echo ew_HtmlEncode($report_form->staffid->FormValue) ?>">
<?php } ?>
<?php echo $report_form->staffid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_report_form_staff_id" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->staff_id->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_staff_id">
<?php
$wrkonchange = trim(" " . @$report_form->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $report_form->staff_id->EditValue ?>" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->staff_id->getPlaceHolder()) ?>"<?php echo $report_form->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $report_form->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($report_form->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_staff_id">
<span<?php echo $report_form->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($report_form->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $report_form->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_report_form_department" for="x_department" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->department->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_department">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->department->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->department->ViewValue ?>
	</span>
	<?php if (!$report_form->department->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_department" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->department->RadioButtonListHtml(TRUE, "x_department", 1) ?>
		</div>
	</div>
	<div id="tp_x_department" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_department" data-page="1" data-value-separator="<?php echo $report_form->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="{value}"<?php echo $report_form->department->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_department">
<span<?php echo $report_form->department->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->department->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_department" data-page="1" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($report_form->department->FormValue) ?>">
<?php } ?>
<?php echo $report_form->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->branch->Visible) { // branch ?>
	<div id="r_branch" class="form-group">
		<label id="elh_report_form_branch" for="x_branch" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->branch->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->branch->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_branch">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->branch->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->branch->ViewValue ?>
	</span>
	<?php if (!$report_form->branch->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_branch" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->branch->RadioButtonListHtml(TRUE, "x_branch", 1) ?>
		</div>
	</div>
	<div id="tp_x_branch" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_branch" data-page="1" data-value-separator="<?php echo $report_form->branch->DisplayValueSeparatorAttribute() ?>" name="x_branch" id="x_branch" value="{value}"<?php echo $report_form->branch->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_branch">
<span<?php echo $report_form->branch->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->branch->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_branch" data-page="1" name="x_branch" id="x_branch" value="<?php echo ew_HtmlEncode($report_form->branch->FormValue) ?>">
<?php } ?>
<?php echo $report_form->branch->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->departments->Visible) { // departments ?>
	<div id="r_departments" class="form-group">
		<label id="elh_report_form_departments" for="x_departments" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->departments->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->departments->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_departments">
<?php $report_form->departments->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report_form->departments->EditAttrs["onchange"]; ?>
<select data-table="report_form" data-field="x_departments" data-page="1" data-value-separator="<?php echo $report_form->departments->DisplayValueSeparatorAttribute() ?>" id="x_departments" name="x_departments"<?php echo $report_form->departments->EditAttributes() ?>>
<?php echo $report_form->departments->SelectOptionListHtml("x_departments") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_departments">
<span<?php echo $report_form->departments->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->departments->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_departments" data-page="1" name="x_departments" id="x_departments" value="<?php echo ew_HtmlEncode($report_form->departments->FormValue) ?>">
<?php } ?>
<?php echo $report_form->departments->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->category->Visible) { // category ?>
	<div id="r_category" class="form-group">
		<label id="elh_report_form_category" for="x_category" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->category->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_category">
<?php $report_form->category->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report_form->category->EditAttrs["onchange"]; ?>
<select data-table="report_form" data-field="x_category" data-page="1" data-value-separator="<?php echo $report_form->category->DisplayValueSeparatorAttribute() ?>" id="x_category" name="x_category"<?php echo $report_form->category->EditAttributes() ?>>
<?php echo $report_form->category->SelectOptionListHtml("x_category") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_category">
<span<?php echo $report_form->category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_category" data-page="1" name="x_category" id="x_category" value="<?php echo ew_HtmlEncode($report_form->category->FormValue) ?>">
<?php } ?>
<?php echo $report_form->category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->sub_category->Visible) { // sub_category ?>
	<div id="r_sub_category" class="form-group">
		<label id="elh_report_form_sub_category" for="x_sub_category" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->sub_category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->sub_category->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_sub_category">
<?php $report_form->sub_category->EditAttrs["onclick"] = "ew_UpdateOpt.call(this); " . @$report_form->sub_category->EditAttrs["onclick"]; ?>
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->sub_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->sub_category->ViewValue ?>
	</span>
	<?php if (!$report_form->sub_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_sub_category" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->sub_category->RadioButtonListHtml(TRUE, "x_sub_category", 1) ?>
		</div>
	</div>
	<div id="tp_x_sub_category" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_sub_category" data-page="1" data-value-separator="<?php echo $report_form->sub_category->DisplayValueSeparatorAttribute() ?>" name="x_sub_category" id="x_sub_category" value="{value}"<?php echo $report_form->sub_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_sub_category">
<span<?php echo $report_form->sub_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->sub_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_sub_category" data-page="1" name="x_sub_category" id="x_sub_category" value="<?php echo ew_HtmlEncode($report_form->sub_category->FormValue) ?>">
<?php } ?>
<?php echo $report_form->sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->sub_sub_category->Visible) { // sub_sub_category ?>
	<div id="r_sub_sub_category" class="form-group">
		<label id="elh_report_form_sub_sub_category" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->sub_sub_category->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->sub_sub_category->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_sub_sub_category">
<?php $report_form->sub_sub_category->EditAttrs["onclick"] = "ew_UpdateOpt.call(this); " . @$report_form->sub_sub_category->EditAttrs["onclick"]; ?>
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->sub_sub_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->sub_sub_category->ViewValue ?>
	</span>
	<?php if (!$report_form->sub_sub_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_sub_sub_category" data-repeatcolumn="5" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->sub_sub_category->CheckBoxListHtml(TRUE, "x_sub_sub_category[]", 1) ?>
		</div>
	</div>
	<div id="tp_x_sub_sub_category" class="ewTemplate"><input type="checkbox" data-table="report_form" data-field="x_sub_sub_category" data-page="1" data-value-separator="<?php echo $report_form->sub_sub_category->DisplayValueSeparatorAttribute() ?>" name="x_sub_sub_category[]" id="x_sub_sub_category[]" value="{value}"<?php echo $report_form->sub_sub_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_sub_sub_category">
<span<?php echo $report_form->sub_sub_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->sub_sub_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_sub_sub_category" data-page="1" name="x_sub_sub_category" id="x_sub_sub_category" value="<?php echo ew_HtmlEncode($report_form->sub_sub_category->FormValue) ?>">
<?php } ?>
<?php echo $report_form->sub_sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->selection_sub_category->Visible) { // selection_sub_category ?>
	<div id="r_selection_sub_category" class="form-group">
		<label id="elh_report_form_selection_sub_category" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->selection_sub_category->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->selection_sub_category->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_selection_sub_category">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->selection_sub_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->selection_sub_category->ViewValue ?>
	</span>
	<?php if (!$report_form->selection_sub_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_selection_sub_category" data-repeatcolumn="5" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->selection_sub_category->CheckBoxListHtml(TRUE, "x_selection_sub_category[]", 1) ?>
		</div>
	</div>
	<div id="tp_x_selection_sub_category" class="ewTemplate"><input type="checkbox" data-table="report_form" data-field="x_selection_sub_category" data-page="1" data-value-separator="<?php echo $report_form->selection_sub_category->DisplayValueSeparatorAttribute() ?>" name="x_selection_sub_category[]" id="x_selection_sub_category[]" value="{value}"<?php echo $report_form->selection_sub_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_selection_sub_category">
<span<?php echo $report_form->selection_sub_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->selection_sub_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_selection_sub_category" data-page="1" name="x_selection_sub_category" id="x_selection_sub_category" value="<?php echo ew_HtmlEncode($report_form->selection_sub_category->FormValue) ?>">
<?php } ?>
<?php echo $report_form->selection_sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->start_date->Visible) { // start_date ?>
	<div id="r_start_date" class="form-group">
		<label id="elh_report_form_start_date" for="x_start_date" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->start_date->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->start_date->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_start_date">
<input type="text" data-table="report_form" data-field="x_start_date" data-page="1" data-format="7" name="x_start_date" id="x_start_date" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->start_date->getPlaceHolder()) ?>" value="<?php echo $report_form->start_date->EditValue ?>"<?php echo $report_form->start_date->EditAttributes() ?>>
<?php if (!$report_form->start_date->ReadOnly && !$report_form->start_date->Disabled && !isset($report_form->start_date->EditAttrs["readonly"]) && !isset($report_form->start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freport_formadd", "x_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_form_start_date">
<span<?php echo $report_form->start_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->start_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_start_date" data-page="1" name="x_start_date" id="x_start_date" value="<?php echo ew_HtmlEncode($report_form->start_date->FormValue) ?>">
<?php } ?>
<?php echo $report_form->start_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->end_date->Visible) { // end_date ?>
	<div id="r_end_date" class="form-group">
		<label id="elh_report_form_end_date" for="x_end_date" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->end_date->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->end_date->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_end_date">
<input type="text" data-table="report_form" data-field="x_end_date" data-page="1" data-format="7" name="x_end_date" id="x_end_date" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->end_date->getPlaceHolder()) ?>" value="<?php echo $report_form->end_date->EditValue ?>"<?php echo $report_form->end_date->EditAttributes() ?>>
<?php if (!$report_form->end_date->ReadOnly && !$report_form->end_date->Disabled && !isset($report_form->end_date->EditAttrs["readonly"]) && !isset($report_form->end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freport_formadd", "x_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_form_end_date">
<span<?php echo $report_form->end_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->end_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_end_date" data-page="1" name="x_end_date" id="x_end_date" value="<?php echo ew_HtmlEncode($report_form->end_date->FormValue) ?>">
<?php } ?>
<?php echo $report_form->end_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->duration->Visible) { // duration ?>
	<div id="r_duration" class="form-group">
		<label id="elh_report_form_duration" for="x_duration" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->duration->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->duration->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_duration">
<input type="text" data-table="report_form" data-field="x_duration" data-page="1" name="x_duration" id="x_duration" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->duration->getPlaceHolder()) ?>" value="<?php echo $report_form->duration->EditValue ?>"<?php echo $report_form->duration->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_duration">
<span<?php echo $report_form->duration->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->duration->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_duration" data-page="1" name="x_duration" id="x_duration" value="<?php echo ew_HtmlEncode($report_form->duration->FormValue) ?>">
<?php } ?>
<?php echo $report_form->duration->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->amount_paid->Visible) { // amount_paid ?>
	<div id="r_amount_paid" class="form-group">
		<label id="elh_report_form_amount_paid" for="x_amount_paid" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->amount_paid->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->amount_paid->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_amount_paid">
<input type="text" data-table="report_form" data-field="x_amount_paid" data-page="1" name="x_amount_paid" id="x_amount_paid" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->amount_paid->getPlaceHolder()) ?>" value="<?php echo $report_form->amount_paid->EditValue ?>"<?php echo $report_form->amount_paid->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_amount_paid">
<span<?php echo $report_form->amount_paid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->amount_paid->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_amount_paid" data-page="1" name="x_amount_paid" id="x_amount_paid" value="<?php echo ew_HtmlEncode($report_form->amount_paid->FormValue) ?>">
<?php } ?>
<?php echo $report_form->amount_paid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->no_of_people_involved->Visible) { // no_of_people_involved ?>
	<div id="r_no_of_people_involved" class="form-group">
		<label id="elh_report_form_no_of_people_involved" for="x_no_of_people_involved" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->no_of_people_involved->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->no_of_people_involved->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_no_of_people_involved">
<input type="text" data-table="report_form" data-field="x_no_of_people_involved" data-page="1" name="x_no_of_people_involved" id="x_no_of_people_involved" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->no_of_people_involved->getPlaceHolder()) ?>" value="<?php echo $report_form->no_of_people_involved->EditValue ?>"<?php echo $report_form->no_of_people_involved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_no_of_people_involved">
<span<?php echo $report_form->no_of_people_involved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->no_of_people_involved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_no_of_people_involved" data-page="1" name="x_no_of_people_involved" id="x_no_of_people_involved" value="<?php echo ew_HtmlEncode($report_form->no_of_people_involved->FormValue) ?>">
<?php } ?>
<?php echo $report_form->no_of_people_involved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_type->Visible) { // incident_type ?>
	<div id="r_incident_type" class="form-group">
		<label id="elh_report_form_incident_type" for="x_incident_type" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_type->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_type->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_type">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->incident_type->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->incident_type->ViewValue ?>
	</span>
	<?php if (!$report_form->incident_type->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_type" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->incident_type->RadioButtonListHtml(TRUE, "x_incident_type", 1) ?>
		</div>
	</div>
	<div id="tp_x_incident_type" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_incident_type" data-page="1" data-value-separator="<?php echo $report_form->incident_type->DisplayValueSeparatorAttribute() ?>" name="x_incident_type" id="x_incident_type" value="{value}"<?php echo $report_form->incident_type->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_incident_type">
<span<?php echo $report_form->incident_type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_type" data-page="1" name="x_incident_type" id="x_incident_type" value="<?php echo ew_HtmlEncode($report_form->incident_type->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_category->Visible) { // incident-category ?>
	<div id="r_incident_category" class="form-group">
		<label id="elh_report_form_incident_category" for="x_incident_category" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_category->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_category">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->incident_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->incident_category->ViewValue ?>
	</span>
	<?php if (!$report_form->incident_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_category" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->incident_category->RadioButtonListHtml(TRUE, "x_incident_category", 1) ?>
		</div>
	</div>
	<div id="tp_x_incident_category" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_incident_category" data-page="1" data-value-separator="<?php echo $report_form->incident_category->DisplayValueSeparatorAttribute() ?>" name="x_incident_category" id="x_incident_category" value="{value}"<?php echo $report_form->incident_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_incident_category">
<span<?php echo $report_form->incident_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_category" data-page="1" name="x_incident_category" id="x_incident_category" value="<?php echo ew_HtmlEncode($report_form->incident_category->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_location->Visible) { // incident_location ?>
	<div id="r_incident_location" class="form-group">
		<label id="elh_report_form_incident_location" for="x_incident_location" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_location->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_location">
<?php $report_form->incident_location->EditAttrs["onclick"] = "ew_UpdateOpt.call(this); " . @$report_form->incident_location->EditAttrs["onclick"]; ?>
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->incident_location->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->incident_location->ViewValue ?>
	</span>
	<?php if (!$report_form->incident_location->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_location" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->incident_location->RadioButtonListHtml(TRUE, "x_incident_location", 1) ?>
		</div>
	</div>
	<div id="tp_x_incident_location" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_incident_location" data-page="1" data-value-separator="<?php echo $report_form->incident_location->DisplayValueSeparatorAttribute() ?>" name="x_incident_location" id="x_incident_location" value="{value}"<?php echo $report_form->incident_location->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_form_incident_location">
<span<?php echo $report_form->incident_location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_location" data-page="1" name="x_incident_location" id="x_incident_location" value="<?php echo ew_HtmlEncode($report_form->incident_location->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_sub_location->Visible) { // incident_sub_location ?>
	<div id="r_incident_sub_location" class="form-group">
		<label id="elh_report_form_incident_sub_location" for="x_incident_sub_location" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_sub_location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_sub_location->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_sub_location">
<?php $report_form->incident_sub_location->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report_form->incident_sub_location->EditAttrs["onchange"]; ?>
<select data-table="report_form" data-field="x_incident_sub_location" data-page="1" data-value-separator="<?php echo $report_form->incident_sub_location->DisplayValueSeparatorAttribute() ?>" id="x_incident_sub_location" name="x_incident_sub_location"<?php echo $report_form->incident_sub_location->EditAttributes() ?>>
<?php echo $report_form->incident_sub_location->SelectOptionListHtml("x_incident_sub_location") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_incident_sub_location">
<span<?php echo $report_form->incident_sub_location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_sub_location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_sub_location" data-page="1" name="x_incident_sub_location" id="x_incident_sub_location" value="<?php echo ew_HtmlEncode($report_form->incident_sub_location->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_sub_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_venue->Visible) { // incident_venue ?>
	<div id="r_incident_venue" class="form-group">
		<label id="elh_report_form_incident_venue" for="x_incident_venue" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_venue->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_venue->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_venue">
<select data-table="report_form" data-field="x_incident_venue" data-page="1" data-value-separator="<?php echo $report_form->incident_venue->DisplayValueSeparatorAttribute() ?>" id="x_incident_venue" name="x_incident_venue"<?php echo $report_form->incident_venue->EditAttributes() ?>>
<?php echo $report_form->incident_venue->SelectOptionListHtml("x_incident_venue") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_incident_venue">
<span<?php echo $report_form->incident_venue->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_venue->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_venue" data-page="1" name="x_incident_venue" id="x_incident_venue" value="<?php echo ew_HtmlEncode($report_form->incident_venue->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_venue->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_description->Visible) { // incident_description ?>
	<div id="r_incident_description" class="form-group">
		<label id="elh_report_form_incident_description" for="x_incident_description" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->incident_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->incident_description->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_incident_description">
<textarea data-table="report_form" data-field="x_incident_description" data-page="1" name="x_incident_description" id="x_incident_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report_form->incident_description->getPlaceHolder()) ?>"<?php echo $report_form->incident_description->EditAttributes() ?>><?php echo $report_form->incident_description->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_form_incident_description">
<span<?php echo $report_form->incident_description->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->incident_description->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_incident_description" data-page="1" name="x_incident_description" id="x_incident_description" value="<?php echo ew_HtmlEncode($report_form->incident_description->FormValue) ?>">
<?php } ?>
<?php echo $report_form->incident_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->_upload->Visible) { // upload ?>
	<div id="r__upload" class="form-group">
		<label id="elh_report_form__upload" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->_upload->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->_upload->CellAttributes() ?>>
<span id="el_report_form__upload">
<div id="fd_x__upload">
<span title="<?php echo $report_form->_upload->FldTitle() ? $report_form->_upload->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($report_form->_upload->ReadOnly || $report_form->_upload->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="report_form" data-field="x__upload" data-page="1" name="x__upload" id="x__upload" multiple="multiple"<?php echo $report_form->_upload->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x__upload" id= "fn_x__upload" value="<?php echo $report_form->_upload->Upload->FileName ?>">
<input type="hidden" name="fa_x__upload" id= "fa_x__upload" value="0">
<input type="hidden" name="fs_x__upload" id= "fs_x__upload" value="128">
<input type="hidden" name="fx_x__upload" id= "fx_x__upload" value="<?php echo $report_form->_upload->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x__upload" id= "fm_x__upload" value="<?php echo $report_form->_upload->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x__upload" id= "fc_x__upload" value="<?php echo $report_form->_upload->UploadMaxFileCount ?>">
</div>
<table id="ft_x__upload" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $report_form->_upload->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->organization->Visible) { // organization ?>
	<div id="r_organization" class="form-group">
		<label id="elh_report_form_organization" for="x_organization" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->organization->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->organization->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_organization">
<input type="text" data-table="report_form" data-field="x_organization" data-page="1" name="x_organization" id="x_organization" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->organization->getPlaceHolder()) ?>" value="<?php echo $report_form->organization->EditValue ?>"<?php echo $report_form->organization->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_organization">
<span<?php echo $report_form->organization->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->organization->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_organization" data-page="1" name="x_organization" id="x_organization" value="<?php echo ew_HtmlEncode($report_form->organization->FormValue) ?>">
<?php } ?>
<?php echo $report_form->organization->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $report_form_add->MultiPages->PageStyle("2") ?>" id="tab_report_form2"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($report_form->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_report_form_status" for="x_status" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->status->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_status">
<select data-table="report_form" data-field="x_status" data-page="2" data-value-separator="<?php echo $report_form->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $report_form->status->EditAttributes() ?>>
<?php echo $report_form->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_status">
<span<?php echo $report_form->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_status" data-page="2" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($report_form->status->FormValue) ?>">
<?php } ?>
<?php echo $report_form->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->rejection_reasons->Visible) { // rejection_reasons ?>
	<div id="r_rejection_reasons" class="form-group">
		<label id="elh_report_form_rejection_reasons" for="x_rejection_reasons" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->rejection_reasons->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->rejection_reasons->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_rejection_reasons">
<textarea data-table="report_form" data-field="x_rejection_reasons" data-page="2" name="x_rejection_reasons" id="x_rejection_reasons" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report_form->rejection_reasons->getPlaceHolder()) ?>"<?php echo $report_form->rejection_reasons->EditAttributes() ?>><?php echo $report_form->rejection_reasons->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_form_rejection_reasons">
<span<?php echo $report_form->rejection_reasons->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->rejection_reasons->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_rejection_reasons" data-page="2" name="x_rejection_reasons" id="x_rejection_reasons" value="<?php echo ew_HtmlEncode($report_form->rejection_reasons->FormValue) ?>">
<?php } ?>
<?php echo $report_form->rejection_reasons->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_report_form_initiator_action" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->initiator_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->initiator_action->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_initiator_action" data-page="2" data-value-separator="<?php echo $report_form->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $report_form->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_form_initiator_action">
<span<?php echo $report_form->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->initiator_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_initiator_action" data-page="2" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($report_form->initiator_action->FormValue) ?>">
<?php } ?>
<?php echo $report_form->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_report_form_initiator_comment" for="x_initiator_comment" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->initiator_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->initiator_comment->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_initiator_comment">
<textarea data-table="report_form" data-field="x_initiator_comment" data-page="2" name="x_initiator_comment" id="x_initiator_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report_form->initiator_comment->getPlaceHolder()) ?>"<?php echo $report_form->initiator_comment->EditAttributes() ?>><?php echo $report_form->initiator_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_form_initiator_comment">
<span<?php echo $report_form->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->initiator_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_initiator_comment" data-page="2" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($report_form->initiator_comment->FormValue) ?>">
<?php } ?>
<?php echo $report_form->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->report_by->Visible) { // report_by ?>
	<div id="r_report_by" class="form-group">
		<label id="elh_report_form_report_by" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->report_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->report_by->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_report_by">
<?php
$wrkonchange = trim(" " . @$report_form->report_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->report_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_report_by" style="white-space: nowrap; z-index: 8710">
	<input type="text" name="sv_x_report_by" id="sv_x_report_by" value="<?php echo $report_form->report_by->EditValue ?>" size="15" placeholder="<?php echo ew_HtmlEncode($report_form->report_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->report_by->getPlaceHolder()) ?>"<?php echo $report_form->report_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_report_by" data-page="2" data-value-separator="<?php echo $report_form->report_by->DisplayValueSeparatorAttribute() ?>" name="x_report_by" id="x_report_by" value="<?php echo ew_HtmlEncode($report_form->report_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_report_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_report_by">
<span<?php echo $report_form->report_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->report_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_report_by" data-page="2" name="x_report_by" id="x_report_by" value="<?php echo ew_HtmlEncode($report_form->report_by->FormValue) ?>">
<?php } ?>
<?php echo $report_form->report_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->datetime_resolved->Visible) { // datetime_resolved ?>
	<div id="r_datetime_resolved" class="form-group">
		<label id="elh_report_form_datetime_resolved" for="x_datetime_resolved" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->datetime_resolved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->datetime_resolved->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_datetime_resolved">
<input type="text" data-table="report_form" data-field="x_datetime_resolved" data-page="2" data-format="11" name="x_datetime_resolved" id="x_datetime_resolved" size="20" placeholder="<?php echo ew_HtmlEncode($report_form->datetime_resolved->getPlaceHolder()) ?>" value="<?php echo $report_form->datetime_resolved->EditValue ?>"<?php echo $report_form->datetime_resolved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_datetime_resolved">
<span<?php echo $report_form->datetime_resolved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->datetime_resolved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_datetime_resolved" data-page="2" name="x_datetime_resolved" id="x_datetime_resolved" value="<?php echo ew_HtmlEncode($report_form->datetime_resolved->FormValue) ?>">
<?php } ?>
<?php echo $report_form->datetime_resolved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->assign->Visible) { // assign ?>
	<div id="r_assign" class="form-group">
		<label id="elh_report_form_assign" for="x_assign" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->assign->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->assign->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_assign">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign"><?php echo (strval($report_form->assign->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report_form->assign->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report_form->assign->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report_form->assign->ReadOnly || $report_form->assign->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report_form" data-field="x_assign" data-page="2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report_form->assign->DisplayValueSeparatorAttribute() ?>" name="x_assign" id="x_assign" value="<?php echo $report_form->assign->CurrentValue ?>"<?php echo $report_form->assign->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_assign">
<span<?php echo $report_form->assign->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->assign->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_assign" data-page="2" name="x_assign" id="x_assign" value="<?php echo ew_HtmlEncode($report_form->assign->FormValue) ?>">
<?php } ?>
<?php echo $report_form->assign->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->approval_action->Visible) { // approval_action ?>
	<div id="r_approval_action" class="form-group">
		<label id="elh_report_form_approval_action" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->approval_action->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->approval_action->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_approval_action">
<div id="tp_x_approval_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_approval_action" data-page="2" data-value-separator="<?php echo $report_form->approval_action->DisplayValueSeparatorAttribute() ?>" name="x_approval_action" id="x_approval_action" value="{value}"<?php echo $report_form->approval_action->EditAttributes() ?>></div>
<div id="dsl_x_approval_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->approval_action->RadioButtonListHtml(FALSE, "x_approval_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_form_approval_action">
<span<?php echo $report_form->approval_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->approval_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_approval_action" data-page="2" name="x_approval_action" id="x_approval_action" value="<?php echo ew_HtmlEncode($report_form->approval_action->FormValue) ?>">
<?php } ?>
<?php echo $report_form->approval_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->approval_comment->Visible) { // approval_comment ?>
	<div id="r_approval_comment" class="form-group">
		<label id="elh_report_form_approval_comment" for="x_approval_comment" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->approval_comment->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->approval_comment->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_approval_comment">
<textarea data-table="report_form" data-field="x_approval_comment" data-page="2" name="x_approval_comment" id="x_approval_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report_form->approval_comment->getPlaceHolder()) ?>"<?php echo $report_form->approval_comment->EditAttributes() ?>><?php echo $report_form->approval_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_form_approval_comment">
<span<?php echo $report_form->approval_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->approval_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_approval_comment" data-page="2" name="x_approval_comment" id="x_approval_comment" value="<?php echo ew_HtmlEncode($report_form->approval_comment->FormValue) ?>">
<?php } ?>
<?php echo $report_form->approval_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->item_name->Visible) { // item_name ?>
	<div id="r_item_name" class="form-group">
		<label id="elh_report_form_item_name" for="x_item_name" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->item_name->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->item_name->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_item_name">
<select data-table="report_form" data-field="x_item_name" data-page="2" data-value-separator="<?php echo $report_form->item_name->DisplayValueSeparatorAttribute() ?>" id="x_item_name" name="x_item_name"<?php echo $report_form->item_name->EditAttributes() ?>>
<?php echo $report_form->item_name->SelectOptionListHtml("x_item_name") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_item_name">
<span<?php echo $report_form->item_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->item_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_item_name" data-page="2" name="x_item_name" id="x_item_name" value="<?php echo ew_HtmlEncode($report_form->item_name->FormValue) ?>">
<?php } ?>
<?php echo $report_form->item_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->quantity_issued->Visible) { // quantity_issued ?>
	<div id="r_quantity_issued" class="form-group">
		<label id="elh_report_form_quantity_issued" for="x_quantity_issued" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->quantity_issued->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->quantity_issued->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_quantity_issued">
<input type="text" data-table="report_form" data-field="x_quantity_issued" data-page="2" name="x_quantity_issued" id="x_quantity_issued" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->quantity_issued->getPlaceHolder()) ?>" value="<?php echo $report_form->quantity_issued->EditValue ?>"<?php echo $report_form->quantity_issued->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_quantity_issued">
<span<?php echo $report_form->quantity_issued->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->quantity_issued->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_quantity_issued" data-page="2" name="x_quantity_issued" id="x_quantity_issued" value="<?php echo ew_HtmlEncode($report_form->quantity_issued->FormValue) ?>">
<?php } ?>
<?php echo $report_form->quantity_issued->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->reason->Visible) { // reason ?>
	<div id="r_reason" class="form-group">
		<label id="elh_report_form_reason" for="x_reason" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->reason->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->reason->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_reason">
<select data-table="report_form" data-field="x_reason" data-page="2" data-value-separator="<?php echo $report_form->reason->DisplayValueSeparatorAttribute() ?>" id="x_reason" name="x_reason"<?php echo $report_form->reason->EditAttributes() ?>>
<?php echo $report_form->reason->SelectOptionListHtml("x_reason") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "reason") && !$report_form->reason->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $report_form->reason->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_reason',url:'reasonaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_reason"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $report_form->reason->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_form_reason">
<span<?php echo $report_form->reason->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->reason->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_reason" data-page="2" name="x_reason" id="x_reason" value="<?php echo ew_HtmlEncode($report_form->reason->FormValue) ?>">
<?php } ?>
<?php echo $report_form->reason->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->resolved_action->Visible) { // resolved_action ?>
	<div id="r_resolved_action" class="form-group">
		<label id="elh_report_form_resolved_action" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->resolved_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->resolved_action->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_resolved_action">
<div id="tp_x_resolved_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_resolved_action" data-page="2" data-value-separator="<?php echo $report_form->resolved_action->DisplayValueSeparatorAttribute() ?>" name="x_resolved_action" id="x_resolved_action" value="{value}"<?php echo $report_form->resolved_action->EditAttributes() ?>></div>
<div id="dsl_x_resolved_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->resolved_action->RadioButtonListHtml(FALSE, "x_resolved_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_form_resolved_action">
<span<?php echo $report_form->resolved_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->resolved_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_resolved_action" data-page="2" name="x_resolved_action" id="x_resolved_action" value="<?php echo ew_HtmlEncode($report_form->resolved_action->FormValue) ?>">
<?php } ?>
<?php echo $report_form->resolved_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->resolved_comment->Visible) { // resolved_comment ?>
	<div id="r_resolved_comment" class="form-group">
		<label id="elh_report_form_resolved_comment" for="x_resolved_comment" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->resolved_comment->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->resolved_comment->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_resolved_comment">
<textarea data-table="report_form" data-field="x_resolved_comment" data-page="2" name="x_resolved_comment" id="x_resolved_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report_form->resolved_comment->getPlaceHolder()) ?>"<?php echo $report_form->resolved_comment->EditAttributes() ?>><?php echo $report_form->resolved_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_form_resolved_comment">
<span<?php echo $report_form->resolved_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->resolved_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_resolved_comment" data-page="2" name="x_resolved_comment" id="x_resolved_comment" value="<?php echo ew_HtmlEncode($report_form->resolved_comment->FormValue) ?>">
<?php } ?>
<?php echo $report_form->resolved_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->resolved_by->Visible) { // resolved_by ?>
	<div id="r_resolved_by" class="form-group">
		<label id="elh_report_form_resolved_by" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->resolved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->resolved_by->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_resolved_by">
<?php
$wrkonchange = trim(" " . @$report_form->resolved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->resolved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_resolved_by" style="white-space: nowrap; z-index: 8600">
	<input type="text" name="sv_x_resolved_by" id="sv_x_resolved_by" value="<?php echo $report_form->resolved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->resolved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->resolved_by->getPlaceHolder()) ?>"<?php echo $report_form->resolved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_resolved_by" data-page="2" data-value-separator="<?php echo $report_form->resolved_by->DisplayValueSeparatorAttribute() ?>" name="x_resolved_by" id="x_resolved_by" value="<?php echo ew_HtmlEncode($report_form->resolved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_resolved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_resolved_by">
<span<?php echo $report_form->resolved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->resolved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_resolved_by" data-page="2" name="x_resolved_by" id="x_resolved_by" value="<?php echo ew_HtmlEncode($report_form->resolved_by->FormValue) ?>">
<?php } ?>
<?php echo $report_form->resolved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->datetime_approved->Visible) { // datetime_approved ?>
	<div id="r_datetime_approved" class="form-group">
		<label id="elh_report_form_datetime_approved" for="x_datetime_approved" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->datetime_approved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->datetime_approved->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_datetime_approved">
<input type="text" data-table="report_form" data-field="x_datetime_approved" data-page="2" data-format="11" name="x_datetime_approved" id="x_datetime_approved" size="17" placeholder="<?php echo ew_HtmlEncode($report_form->datetime_approved->getPlaceHolder()) ?>" value="<?php echo $report_form->datetime_approved->EditValue ?>"<?php echo $report_form->datetime_approved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_datetime_approved">
<span<?php echo $report_form->datetime_approved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->datetime_approved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_datetime_approved" data-page="2" name="x_datetime_approved" id="x_datetime_approved" value="<?php echo ew_HtmlEncode($report_form->datetime_approved->FormValue) ?>">
<?php } ?>
<?php echo $report_form->datetime_approved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_report_form_approved_by" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->approved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->approved_by->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_approved_by">
<?php
$wrkonchange = trim(" " . @$report_form->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8580">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $report_form->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->approved_by->getPlaceHolder()) ?>"<?php echo $report_form->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_approved_by" data-page="2" data-value-separator="<?php echo $report_form->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($report_form->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_approved_by">
<span<?php echo $report_form->approved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->approved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_approved_by" data-page="2" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($report_form->approved_by->FormValue) ?>">
<?php } ?>
<?php echo $report_form->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->last_updated_date->Visible) { // last_updated_date ?>
	<div id="r_last_updated_date" class="form-group">
		<label id="elh_report_form_last_updated_date" for="x_last_updated_date" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->last_updated_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->last_updated_date->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_last_updated_date">
<input type="text" data-table="report_form" data-field="x_last_updated_date" data-page="2" data-format="17" name="x_last_updated_date" id="x_last_updated_date" placeholder="<?php echo ew_HtmlEncode($report_form->last_updated_date->getPlaceHolder()) ?>" value="<?php echo $report_form->last_updated_date->EditValue ?>"<?php echo $report_form->last_updated_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_last_updated_date">
<span<?php echo $report_form->last_updated_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->last_updated_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_last_updated_date" data-page="2" name="x_last_updated_date" id="x_last_updated_date" value="<?php echo ew_HtmlEncode($report_form->last_updated_date->FormValue) ?>">
<?php } ?>
<?php echo $report_form->last_updated_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->last_updated_by->Visible) { // last_updated_by ?>
	<div id="r_last_updated_by" class="form-group">
		<label id="elh_report_form_last_updated_by" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->last_updated_by->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->last_updated_by->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_last_updated_by">
<?php
$wrkonchange = trim(" " . @$report_form->last_updated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->last_updated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_last_updated_by" style="white-space: nowrap; z-index: 8560">
	<input type="text" name="sv_x_last_updated_by" id="sv_x_last_updated_by" value="<?php echo $report_form->last_updated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->last_updated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->last_updated_by->getPlaceHolder()) ?>"<?php echo $report_form->last_updated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_last_updated_by" data-page="2" data-value-separator="<?php echo $report_form->last_updated_by->DisplayValueSeparatorAttribute() ?>" name="x_last_updated_by" id="x_last_updated_by" value="<?php echo ew_HtmlEncode($report_form->last_updated_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_last_updated_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_last_updated_by">
<span<?php echo $report_form->last_updated_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->last_updated_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_last_updated_by" data-page="2" name="x_last_updated_by" id="x_last_updated_by" value="<?php echo ew_HtmlEncode($report_form->last_updated_by->FormValue) ?>">
<?php } ?>
<?php echo $report_form->last_updated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->verified_datetime->Visible) { // verified_datetime ?>
	<div id="r_verified_datetime" class="form-group">
		<label id="elh_report_form_verified_datetime" for="x_verified_datetime" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->verified_datetime->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->verified_datetime->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_verified_datetime">
<input type="text" data-table="report_form" data-field="x_verified_datetime" data-page="2" data-format="17" name="x_verified_datetime" id="x_verified_datetime" placeholder="<?php echo ew_HtmlEncode($report_form->verified_datetime->getPlaceHolder()) ?>" value="<?php echo $report_form->verified_datetime->EditValue ?>"<?php echo $report_form->verified_datetime->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_form_verified_datetime">
<span<?php echo $report_form->verified_datetime->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->verified_datetime->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_verified_datetime" data-page="2" name="x_verified_datetime" id="x_verified_datetime" value="<?php echo ew_HtmlEncode($report_form->verified_datetime->FormValue) ?>">
<?php } ?>
<?php echo $report_form->verified_datetime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->job_assessment->Visible) { // job_assessment ?>
	<div id="r_job_assessment" class="form-group">
		<label id="elh_report_form_job_assessment" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->job_assessment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->job_assessment->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_job_assessment">
<div id="tp_x_job_assessment" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_job_assessment" data-page="2" data-value-separator="<?php echo $report_form->job_assessment->DisplayValueSeparatorAttribute() ?>" name="x_job_assessment" id="x_job_assessment" value="{value}"<?php echo $report_form->job_assessment->EditAttributes() ?>></div>
<div id="dsl_x_job_assessment" data-repeatcolumn="6" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->job_assessment->RadioButtonListHtml(FALSE, "x_job_assessment", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_form_job_assessment">
<span<?php echo $report_form->job_assessment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->job_assessment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_job_assessment" data-page="2" name="x_job_assessment" id="x_job_assessment" value="<?php echo ew_HtmlEncode($report_form->job_assessment->FormValue) ?>">
<?php } ?>
<?php echo $report_form->job_assessment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label id="elh_report_form_verified_action" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->verified_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->verified_action->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_verified_action" data-page="2" data-value-separator="<?php echo $report_form->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $report_form->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->verified_action->RadioButtonListHtml(FALSE, "x_verified_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_form_verified_action">
<span<?php echo $report_form->verified_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->verified_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_verified_action" data-page="2" name="x_verified_action" id="x_verified_action" value="<?php echo ew_HtmlEncode($report_form->verified_action->FormValue) ?>">
<?php } ?>
<?php echo $report_form->verified_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label id="elh_report_form_verified_comment" for="x_verified_comment" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->verified_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->verified_comment->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_verified_comment">
<textarea data-table="report_form" data-field="x_verified_comment" data-page="2" name="x_verified_comment" id="x_verified_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report_form->verified_comment->getPlaceHolder()) ?>"<?php echo $report_form->verified_comment->EditAttributes() ?>><?php echo $report_form->verified_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_form_verified_comment">
<span<?php echo $report_form->verified_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->verified_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_verified_comment" data-page="2" name="x_verified_comment" id="x_verified_comment" value="<?php echo ew_HtmlEncode($report_form->verified_comment->FormValue) ?>">
<?php } ?>
<?php echo $report_form->verified_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label id="elh_report_form_verified_by" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->verified_by->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->verified_by->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_verified_by">
<?php
$wrkonchange = trim(" " . @$report_form->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8510">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $report_form->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->verified_by->getPlaceHolder()) ?>"<?php echo $report_form->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_verified_by" data-page="2" data-value-separator="<?php echo $report_form->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($report_form->verified_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formadd.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_form_verified_by">
<span<?php echo $report_form->verified_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->verified_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_verified_by" data-page="2" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($report_form->verified_by->FormValue) ?>">
<?php } ?>
<?php echo $report_form->verified_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report_form->remainder->Visible) { // remainder ?>
	<div id="r_remainder" class="form-group">
		<label id="elh_report_form_remainder" for="x_remainder" class="<?php echo $report_form_add->LeftColumnClass ?>"><?php echo $report_form->remainder->FldCaption() ?></label>
		<div class="<?php echo $report_form_add->RightColumnClass ?>"><div<?php echo $report_form->remainder->CellAttributes() ?>>
<?php if ($report_form->CurrentAction <> "F") { ?>
<span id="el_report_form_remainder">
<select data-table="report_form" data-field="x_remainder" data-page="2" data-value-separator="<?php echo $report_form->remainder->DisplayValueSeparatorAttribute() ?>" id="x_remainder" name="x_remainder"<?php echo $report_form->remainder->EditAttributes() ?>>
<?php echo $report_form->remainder->SelectOptionListHtml("x_remainder") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_form_remainder">
<span<?php echo $report_form->remainder->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report_form->remainder->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report_form" data-field="x_remainder" data-page="2" name="x_remainder" id="x_remainder" value="<?php echo ew_HtmlEncode($report_form->remainder->FormValue) ?>">
<?php } ?>
<?php echo $report_form->remainder->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$report_form_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $report_form_add->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($report_form->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_add.value='F';"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $report_form_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_add.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
freport_formadd.Init();
</script>
<?php
$report_form_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('#x_status').attr('readonly',true);
$('#r_rejection_reasons').hide();
$("#r_remainder").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$report_form_add->Page_Terminate();
?>
