<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "loan_applicationinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$loan_application_add = NULL; // Initialize page object first

class cloan_application_add extends cloan_application {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'loan_application';

	// Page object name
	var $PageObjName = 'loan_application_add';

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

		// Table object (loan_application)
		if (!isset($GLOBALS["loan_application"]) || get_class($GLOBALS["loan_application"]) == "cloan_application") {
			$GLOBALS["loan_application"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["loan_application"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'loan_application');

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
				$this->Page_Terminate(ew_GetUrl("loan_applicationlist.php"));
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
		$this->date_initiated->SetVisibility();
		$this->refernce_id->SetVisibility();
		$this->employee_name->SetVisibility();
		$this->address->SetVisibility();
		$this->mobile->SetVisibility();
		$this->department->SetVisibility();
		$this->pension->SetVisibility();
		$this->loan_amount->SetVisibility();
		$this->amount_inwords->SetVisibility();
		$this->purpose->SetVisibility();
		$this->repayment_period->SetVisibility();
		$this->salary_permonth->SetVisibility();
		$this->previous_loan->SetVisibility();
		$this->date_collected->SetVisibility();
		$this->date_liquidated->SetVisibility();
		$this->balance_remaining->SetVisibility();
		$this->applicant_date->SetVisibility();
		$this->applicant_passport->SetVisibility();
		$this->guarantor_name->SetVisibility();
		$this->guarantor_address->SetVisibility();
		$this->guarantor_mobile->SetVisibility();
		$this->guarantor_department->SetVisibility();
		$this->account_no->SetVisibility();
		$this->bank_name->SetVisibility();
		$this->employers_name->SetVisibility();
		$this->employers_address->SetVisibility();
		$this->employers_mobile->SetVisibility();
		$this->guarantor_date->SetVisibility();
		$this->guarantor_passport->SetVisibility();
		$this->status->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->recommended_date->SetVisibility();
		$this->document_checklist->SetVisibility();
		$this->recommender_action->SetVisibility();
		$this->recommender_comment->SetVisibility();
		$this->recommended_by->SetVisibility();
		$this->application_status->SetVisibility();
		$this->approved_amount->SetVisibility();
		$this->duration_approved->SetVisibility();
		$this->approval_date->SetVisibility();
		$this->approval_action->SetVisibility();
		$this->approval_comment->SetVisibility();
		$this->approved_by->SetVisibility();

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
		global $EW_EXPORT, $loan_application;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($loan_application);
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
					if ($pageName == "loan_applicationview.php")
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
					$this->Page_Terminate("loan_applicationlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "loan_applicationlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "loan_applicationview.php")
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
		$this->applicant_passport->Upload->Index = $objForm->Index;
		$this->applicant_passport->Upload->UploadFile();
		$this->applicant_passport->CurrentValue = $this->applicant_passport->Upload->FileName;
		$this->guarantor_passport->Upload->Index = $objForm->Index;
		$this->guarantor_passport->Upload->UploadFile();
		$this->guarantor_passport->CurrentValue = $this->guarantor_passport->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->code->CurrentValue = NULL;
		$this->code->OldValue = $this->code->CurrentValue;
		$this->date_initiated->CurrentValue = NULL;
		$this->date_initiated->OldValue = $this->date_initiated->CurrentValue;
		$this->refernce_id->CurrentValue = NULL;
		$this->refernce_id->OldValue = $this->refernce_id->CurrentValue;
		$this->employee_name->CurrentValue = NULL;
		$this->employee_name->OldValue = $this->employee_name->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->mobile->CurrentValue = NULL;
		$this->mobile->OldValue = $this->mobile->CurrentValue;
		$this->department->CurrentValue = NULL;
		$this->department->OldValue = $this->department->CurrentValue;
		$this->pension->CurrentValue = NULL;
		$this->pension->OldValue = $this->pension->CurrentValue;
		$this->loan_amount->CurrentValue = NULL;
		$this->loan_amount->OldValue = $this->loan_amount->CurrentValue;
		$this->amount_inwords->CurrentValue = NULL;
		$this->amount_inwords->OldValue = $this->amount_inwords->CurrentValue;
		$this->purpose->CurrentValue = NULL;
		$this->purpose->OldValue = $this->purpose->CurrentValue;
		$this->repayment_period->CurrentValue = NULL;
		$this->repayment_period->OldValue = $this->repayment_period->CurrentValue;
		$this->salary_permonth->CurrentValue = NULL;
		$this->salary_permonth->OldValue = $this->salary_permonth->CurrentValue;
		$this->previous_loan->CurrentValue = NULL;
		$this->previous_loan->OldValue = $this->previous_loan->CurrentValue;
		$this->date_collected->CurrentValue = NULL;
		$this->date_collected->OldValue = $this->date_collected->CurrentValue;
		$this->date_liquidated->CurrentValue = NULL;
		$this->date_liquidated->OldValue = $this->date_liquidated->CurrentValue;
		$this->balance_remaining->CurrentValue = NULL;
		$this->balance_remaining->OldValue = $this->balance_remaining->CurrentValue;
		$this->applicant_date->CurrentValue = NULL;
		$this->applicant_date->OldValue = $this->applicant_date->CurrentValue;
		$this->applicant_passport->Upload->DbValue = NULL;
		$this->applicant_passport->OldValue = $this->applicant_passport->Upload->DbValue;
		$this->applicant_passport->CurrentValue = NULL; // Clear file related field
		$this->guarantor_name->CurrentValue = NULL;
		$this->guarantor_name->OldValue = $this->guarantor_name->CurrentValue;
		$this->guarantor_address->CurrentValue = NULL;
		$this->guarantor_address->OldValue = $this->guarantor_address->CurrentValue;
		$this->guarantor_mobile->CurrentValue = NULL;
		$this->guarantor_mobile->OldValue = $this->guarantor_mobile->CurrentValue;
		$this->guarantor_department->CurrentValue = NULL;
		$this->guarantor_department->OldValue = $this->guarantor_department->CurrentValue;
		$this->account_no->CurrentValue = NULL;
		$this->account_no->OldValue = $this->account_no->CurrentValue;
		$this->bank_name->CurrentValue = NULL;
		$this->bank_name->OldValue = $this->bank_name->CurrentValue;
		$this->employers_name->CurrentValue = NULL;
		$this->employers_name->OldValue = $this->employers_name->CurrentValue;
		$this->employers_address->CurrentValue = NULL;
		$this->employers_address->OldValue = $this->employers_address->CurrentValue;
		$this->employers_mobile->CurrentValue = NULL;
		$this->employers_mobile->OldValue = $this->employers_mobile->CurrentValue;
		$this->guarantor_date->CurrentValue = NULL;
		$this->guarantor_date->OldValue = $this->guarantor_date->CurrentValue;
		$this->guarantor_passport->Upload->DbValue = NULL;
		$this->guarantor_passport->OldValue = $this->guarantor_passport->Upload->DbValue;
		$this->guarantor_passport->CurrentValue = NULL; // Clear file related field
		$this->status->CurrentValue = 0;
		$this->initiator_action->CurrentValue = NULL;
		$this->initiator_action->OldValue = $this->initiator_action->CurrentValue;
		$this->initiator_comment->CurrentValue = NULL;
		$this->initiator_comment->OldValue = $this->initiator_comment->CurrentValue;
		$this->recommended_date->CurrentValue = NULL;
		$this->recommended_date->OldValue = $this->recommended_date->CurrentValue;
		$this->document_checklist->CurrentValue = NULL;
		$this->document_checklist->OldValue = $this->document_checklist->CurrentValue;
		$this->recommender_action->CurrentValue = NULL;
		$this->recommender_action->OldValue = $this->recommender_action->CurrentValue;
		$this->recommender_comment->CurrentValue = NULL;
		$this->recommender_comment->OldValue = $this->recommender_comment->CurrentValue;
		$this->recommended_by->CurrentValue = NULL;
		$this->recommended_by->OldValue = $this->recommended_by->CurrentValue;
		$this->application_status->CurrentValue = NULL;
		$this->application_status->OldValue = $this->application_status->CurrentValue;
		$this->approved_amount->CurrentValue = NULL;
		$this->approved_amount->OldValue = $this->approved_amount->CurrentValue;
		$this->duration_approved->CurrentValue = NULL;
		$this->duration_approved->OldValue = $this->duration_approved->CurrentValue;
		$this->approval_date->CurrentValue = NULL;
		$this->approval_date->OldValue = $this->approval_date->CurrentValue;
		$this->approval_action->CurrentValue = NULL;
		$this->approval_action->OldValue = $this->approval_action->CurrentValue;
		$this->approval_comment->CurrentValue = NULL;
		$this->approval_comment->OldValue = $this->approval_comment->CurrentValue;
		$this->approved_by->CurrentValue = NULL;
		$this->approved_by->OldValue = $this->approved_by->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->date_initiated->FldIsDetailKey) {
			$this->date_initiated->setFormValue($objForm->GetValue("x_date_initiated"));
			$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0);
		}
		if (!$this->refernce_id->FldIsDetailKey) {
			$this->refernce_id->setFormValue($objForm->GetValue("x_refernce_id"));
		}
		if (!$this->employee_name->FldIsDetailKey) {
			$this->employee_name->setFormValue($objForm->GetValue("x_employee_name"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->mobile->FldIsDetailKey) {
			$this->mobile->setFormValue($objForm->GetValue("x_mobile"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->pension->FldIsDetailKey) {
			$this->pension->setFormValue($objForm->GetValue("x_pension"));
		}
		if (!$this->loan_amount->FldIsDetailKey) {
			$this->loan_amount->setFormValue($objForm->GetValue("x_loan_amount"));
		}
		if (!$this->amount_inwords->FldIsDetailKey) {
			$this->amount_inwords->setFormValue($objForm->GetValue("x_amount_inwords"));
		}
		if (!$this->purpose->FldIsDetailKey) {
			$this->purpose->setFormValue($objForm->GetValue("x_purpose"));
		}
		if (!$this->repayment_period->FldIsDetailKey) {
			$this->repayment_period->setFormValue($objForm->GetValue("x_repayment_period"));
		}
		if (!$this->salary_permonth->FldIsDetailKey) {
			$this->salary_permonth->setFormValue($objForm->GetValue("x_salary_permonth"));
		}
		if (!$this->previous_loan->FldIsDetailKey) {
			$this->previous_loan->setFormValue($objForm->GetValue("x_previous_loan"));
		}
		if (!$this->date_collected->FldIsDetailKey) {
			$this->date_collected->setFormValue($objForm->GetValue("x_date_collected"));
			$this->date_collected->CurrentValue = ew_UnFormatDateTime($this->date_collected->CurrentValue, 0);
		}
		if (!$this->date_liquidated->FldIsDetailKey) {
			$this->date_liquidated->setFormValue($objForm->GetValue("x_date_liquidated"));
			$this->date_liquidated->CurrentValue = ew_UnFormatDateTime($this->date_liquidated->CurrentValue, 0);
		}
		if (!$this->balance_remaining->FldIsDetailKey) {
			$this->balance_remaining->setFormValue($objForm->GetValue("x_balance_remaining"));
		}
		if (!$this->applicant_date->FldIsDetailKey) {
			$this->applicant_date->setFormValue($objForm->GetValue("x_applicant_date"));
			$this->applicant_date->CurrentValue = ew_UnFormatDateTime($this->applicant_date->CurrentValue, 17);
		}
		if (!$this->guarantor_name->FldIsDetailKey) {
			$this->guarantor_name->setFormValue($objForm->GetValue("x_guarantor_name"));
		}
		if (!$this->guarantor_address->FldIsDetailKey) {
			$this->guarantor_address->setFormValue($objForm->GetValue("x_guarantor_address"));
		}
		if (!$this->guarantor_mobile->FldIsDetailKey) {
			$this->guarantor_mobile->setFormValue($objForm->GetValue("x_guarantor_mobile"));
		}
		if (!$this->guarantor_department->FldIsDetailKey) {
			$this->guarantor_department->setFormValue($objForm->GetValue("x_guarantor_department"));
		}
		if (!$this->account_no->FldIsDetailKey) {
			$this->account_no->setFormValue($objForm->GetValue("x_account_no"));
		}
		if (!$this->bank_name->FldIsDetailKey) {
			$this->bank_name->setFormValue($objForm->GetValue("x_bank_name"));
		}
		if (!$this->employers_name->FldIsDetailKey) {
			$this->employers_name->setFormValue($objForm->GetValue("x_employers_name"));
		}
		if (!$this->employers_address->FldIsDetailKey) {
			$this->employers_address->setFormValue($objForm->GetValue("x_employers_address"));
		}
		if (!$this->employers_mobile->FldIsDetailKey) {
			$this->employers_mobile->setFormValue($objForm->GetValue("x_employers_mobile"));
		}
		if (!$this->guarantor_date->FldIsDetailKey) {
			$this->guarantor_date->setFormValue($objForm->GetValue("x_guarantor_date"));
			$this->guarantor_date->CurrentValue = ew_UnFormatDateTime($this->guarantor_date->CurrentValue, 17);
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
		if (!$this->recommended_date->FldIsDetailKey) {
			$this->recommended_date->setFormValue($objForm->GetValue("x_recommended_date"));
			$this->recommended_date->CurrentValue = ew_UnFormatDateTime($this->recommended_date->CurrentValue, 14);
		}
		if (!$this->document_checklist->FldIsDetailKey) {
			$this->document_checklist->setFormValue($objForm->GetValue("x_document_checklist"));
		}
		if (!$this->recommender_action->FldIsDetailKey) {
			$this->recommender_action->setFormValue($objForm->GetValue("x_recommender_action"));
		}
		if (!$this->recommender_comment->FldIsDetailKey) {
			$this->recommender_comment->setFormValue($objForm->GetValue("x_recommender_comment"));
		}
		if (!$this->recommended_by->FldIsDetailKey) {
			$this->recommended_by->setFormValue($objForm->GetValue("x_recommended_by"));
		}
		if (!$this->application_status->FldIsDetailKey) {
			$this->application_status->setFormValue($objForm->GetValue("x_application_status"));
		}
		if (!$this->approved_amount->FldIsDetailKey) {
			$this->approved_amount->setFormValue($objForm->GetValue("x_approved_amount"));
		}
		if (!$this->duration_approved->FldIsDetailKey) {
			$this->duration_approved->setFormValue($objForm->GetValue("x_duration_approved"));
			$this->duration_approved->CurrentValue = ew_UnFormatDateTime($this->duration_approved->CurrentValue, 0);
		}
		if (!$this->approval_date->FldIsDetailKey) {
			$this->approval_date->setFormValue($objForm->GetValue("x_approval_date"));
			$this->approval_date->CurrentValue = ew_UnFormatDateTime($this->approval_date->CurrentValue, 17);
		}
		if (!$this->approval_action->FldIsDetailKey) {
			$this->approval_action->setFormValue($objForm->GetValue("x_approval_action"));
		}
		if (!$this->approval_comment->FldIsDetailKey) {
			$this->approval_comment->setFormValue($objForm->GetValue("x_approval_comment"));
		}
		if (!$this->approved_by->FldIsDetailKey) {
			$this->approved_by->setFormValue($objForm->GetValue("x_approved_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->date_initiated->CurrentValue = $this->date_initiated->FormValue;
		$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0);
		$this->refernce_id->CurrentValue = $this->refernce_id->FormValue;
		$this->employee_name->CurrentValue = $this->employee_name->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->mobile->CurrentValue = $this->mobile->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->pension->CurrentValue = $this->pension->FormValue;
		$this->loan_amount->CurrentValue = $this->loan_amount->FormValue;
		$this->amount_inwords->CurrentValue = $this->amount_inwords->FormValue;
		$this->purpose->CurrentValue = $this->purpose->FormValue;
		$this->repayment_period->CurrentValue = $this->repayment_period->FormValue;
		$this->salary_permonth->CurrentValue = $this->salary_permonth->FormValue;
		$this->previous_loan->CurrentValue = $this->previous_loan->FormValue;
		$this->date_collected->CurrentValue = $this->date_collected->FormValue;
		$this->date_collected->CurrentValue = ew_UnFormatDateTime($this->date_collected->CurrentValue, 0);
		$this->date_liquidated->CurrentValue = $this->date_liquidated->FormValue;
		$this->date_liquidated->CurrentValue = ew_UnFormatDateTime($this->date_liquidated->CurrentValue, 0);
		$this->balance_remaining->CurrentValue = $this->balance_remaining->FormValue;
		$this->applicant_date->CurrentValue = $this->applicant_date->FormValue;
		$this->applicant_date->CurrentValue = ew_UnFormatDateTime($this->applicant_date->CurrentValue, 17);
		$this->guarantor_name->CurrentValue = $this->guarantor_name->FormValue;
		$this->guarantor_address->CurrentValue = $this->guarantor_address->FormValue;
		$this->guarantor_mobile->CurrentValue = $this->guarantor_mobile->FormValue;
		$this->guarantor_department->CurrentValue = $this->guarantor_department->FormValue;
		$this->account_no->CurrentValue = $this->account_no->FormValue;
		$this->bank_name->CurrentValue = $this->bank_name->FormValue;
		$this->employers_name->CurrentValue = $this->employers_name->FormValue;
		$this->employers_address->CurrentValue = $this->employers_address->FormValue;
		$this->employers_mobile->CurrentValue = $this->employers_mobile->FormValue;
		$this->guarantor_date->CurrentValue = $this->guarantor_date->FormValue;
		$this->guarantor_date->CurrentValue = ew_UnFormatDateTime($this->guarantor_date->CurrentValue, 17);
		$this->status->CurrentValue = $this->status->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
		$this->recommended_date->CurrentValue = $this->recommended_date->FormValue;
		$this->recommended_date->CurrentValue = ew_UnFormatDateTime($this->recommended_date->CurrentValue, 14);
		$this->document_checklist->CurrentValue = $this->document_checklist->FormValue;
		$this->recommender_action->CurrentValue = $this->recommender_action->FormValue;
		$this->recommender_comment->CurrentValue = $this->recommender_comment->FormValue;
		$this->recommended_by->CurrentValue = $this->recommended_by->FormValue;
		$this->application_status->CurrentValue = $this->application_status->FormValue;
		$this->approved_amount->CurrentValue = $this->approved_amount->FormValue;
		$this->duration_approved->CurrentValue = $this->duration_approved->FormValue;
		$this->duration_approved->CurrentValue = ew_UnFormatDateTime($this->duration_approved->CurrentValue, 0);
		$this->approval_date->CurrentValue = $this->approval_date->FormValue;
		$this->approval_date->CurrentValue = ew_UnFormatDateTime($this->approval_date->CurrentValue, 17);
		$this->approval_action->CurrentValue = $this->approval_action->FormValue;
		$this->approval_comment->CurrentValue = $this->approval_comment->FormValue;
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
		$this->date_initiated->setDbValue($row['date_initiated']);
		$this->refernce_id->setDbValue($row['refernce_id']);
		$this->employee_name->setDbValue($row['employee_name']);
		$this->address->setDbValue($row['address']);
		$this->mobile->setDbValue($row['mobile']);
		$this->department->setDbValue($row['department']);
		$this->pension->setDbValue($row['pension']);
		$this->loan_amount->setDbValue($row['loan_amount']);
		$this->amount_inwords->setDbValue($row['amount_inwords']);
		$this->purpose->setDbValue($row['purpose']);
		$this->repayment_period->setDbValue($row['repayment_period']);
		$this->salary_permonth->setDbValue($row['salary_permonth']);
		$this->previous_loan->setDbValue($row['previous_loan']);
		$this->date_collected->setDbValue($row['date_collected']);
		$this->date_liquidated->setDbValue($row['date_liquidated']);
		$this->balance_remaining->setDbValue($row['balance_remaining']);
		$this->applicant_date->setDbValue($row['applicant_date']);
		$this->applicant_passport->Upload->DbValue = $row['applicant_passport'];
		$this->applicant_passport->setDbValue($this->applicant_passport->Upload->DbValue);
		$this->guarantor_name->setDbValue($row['guarantor_name']);
		$this->guarantor_address->setDbValue($row['guarantor_address']);
		$this->guarantor_mobile->setDbValue($row['guarantor_mobile']);
		$this->guarantor_department->setDbValue($row['guarantor_department']);
		$this->account_no->setDbValue($row['account_no']);
		$this->bank_name->setDbValue($row['bank_name']);
		$this->employers_name->setDbValue($row['employers_name']);
		$this->employers_address->setDbValue($row['employers_address']);
		$this->employers_mobile->setDbValue($row['employers_mobile']);
		$this->guarantor_date->setDbValue($row['guarantor_date']);
		$this->guarantor_passport->Upload->DbValue = $row['guarantor_passport'];
		$this->guarantor_passport->setDbValue($this->guarantor_passport->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->recommended_date->setDbValue($row['recommended_date']);
		$this->document_checklist->setDbValue($row['document_checklist']);
		$this->recommender_action->setDbValue($row['recommender_action']);
		$this->recommender_comment->setDbValue($row['recommender_comment']);
		$this->recommended_by->setDbValue($row['recommended_by']);
		$this->application_status->setDbValue($row['application_status']);
		$this->approved_amount->setDbValue($row['approved_amount']);
		$this->duration_approved->setDbValue($row['duration_approved']);
		$this->approval_date->setDbValue($row['approval_date']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['code'] = $this->code->CurrentValue;
		$row['date_initiated'] = $this->date_initiated->CurrentValue;
		$row['refernce_id'] = $this->refernce_id->CurrentValue;
		$row['employee_name'] = $this->employee_name->CurrentValue;
		$row['address'] = $this->address->CurrentValue;
		$row['mobile'] = $this->mobile->CurrentValue;
		$row['department'] = $this->department->CurrentValue;
		$row['pension'] = $this->pension->CurrentValue;
		$row['loan_amount'] = $this->loan_amount->CurrentValue;
		$row['amount_inwords'] = $this->amount_inwords->CurrentValue;
		$row['purpose'] = $this->purpose->CurrentValue;
		$row['repayment_period'] = $this->repayment_period->CurrentValue;
		$row['salary_permonth'] = $this->salary_permonth->CurrentValue;
		$row['previous_loan'] = $this->previous_loan->CurrentValue;
		$row['date_collected'] = $this->date_collected->CurrentValue;
		$row['date_liquidated'] = $this->date_liquidated->CurrentValue;
		$row['balance_remaining'] = $this->balance_remaining->CurrentValue;
		$row['applicant_date'] = $this->applicant_date->CurrentValue;
		$row['applicant_passport'] = $this->applicant_passport->Upload->DbValue;
		$row['guarantor_name'] = $this->guarantor_name->CurrentValue;
		$row['guarantor_address'] = $this->guarantor_address->CurrentValue;
		$row['guarantor_mobile'] = $this->guarantor_mobile->CurrentValue;
		$row['guarantor_department'] = $this->guarantor_department->CurrentValue;
		$row['account_no'] = $this->account_no->CurrentValue;
		$row['bank_name'] = $this->bank_name->CurrentValue;
		$row['employers_name'] = $this->employers_name->CurrentValue;
		$row['employers_address'] = $this->employers_address->CurrentValue;
		$row['employers_mobile'] = $this->employers_mobile->CurrentValue;
		$row['guarantor_date'] = $this->guarantor_date->CurrentValue;
		$row['guarantor_passport'] = $this->guarantor_passport->Upload->DbValue;
		$row['status'] = $this->status->CurrentValue;
		$row['initiator_action'] = $this->initiator_action->CurrentValue;
		$row['initiator_comment'] = $this->initiator_comment->CurrentValue;
		$row['recommended_date'] = $this->recommended_date->CurrentValue;
		$row['document_checklist'] = $this->document_checklist->CurrentValue;
		$row['recommender_action'] = $this->recommender_action->CurrentValue;
		$row['recommender_comment'] = $this->recommender_comment->CurrentValue;
		$row['recommended_by'] = $this->recommended_by->CurrentValue;
		$row['application_status'] = $this->application_status->CurrentValue;
		$row['approved_amount'] = $this->approved_amount->CurrentValue;
		$row['duration_approved'] = $this->duration_approved->CurrentValue;
		$row['approval_date'] = $this->approval_date->CurrentValue;
		$row['approval_action'] = $this->approval_action->CurrentValue;
		$row['approval_comment'] = $this->approval_comment->CurrentValue;
		$row['approved_by'] = $this->approved_by->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->refernce_id->DbValue = $row['refernce_id'];
		$this->employee_name->DbValue = $row['employee_name'];
		$this->address->DbValue = $row['address'];
		$this->mobile->DbValue = $row['mobile'];
		$this->department->DbValue = $row['department'];
		$this->pension->DbValue = $row['pension'];
		$this->loan_amount->DbValue = $row['loan_amount'];
		$this->amount_inwords->DbValue = $row['amount_inwords'];
		$this->purpose->DbValue = $row['purpose'];
		$this->repayment_period->DbValue = $row['repayment_period'];
		$this->salary_permonth->DbValue = $row['salary_permonth'];
		$this->previous_loan->DbValue = $row['previous_loan'];
		$this->date_collected->DbValue = $row['date_collected'];
		$this->date_liquidated->DbValue = $row['date_liquidated'];
		$this->balance_remaining->DbValue = $row['balance_remaining'];
		$this->applicant_date->DbValue = $row['applicant_date'];
		$this->applicant_passport->Upload->DbValue = $row['applicant_passport'];
		$this->guarantor_name->DbValue = $row['guarantor_name'];
		$this->guarantor_address->DbValue = $row['guarantor_address'];
		$this->guarantor_mobile->DbValue = $row['guarantor_mobile'];
		$this->guarantor_department->DbValue = $row['guarantor_department'];
		$this->account_no->DbValue = $row['account_no'];
		$this->bank_name->DbValue = $row['bank_name'];
		$this->employers_name->DbValue = $row['employers_name'];
		$this->employers_address->DbValue = $row['employers_address'];
		$this->employers_mobile->DbValue = $row['employers_mobile'];
		$this->guarantor_date->DbValue = $row['guarantor_date'];
		$this->guarantor_passport->Upload->DbValue = $row['guarantor_passport'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->recommended_date->DbValue = $row['recommended_date'];
		$this->document_checklist->DbValue = $row['document_checklist'];
		$this->recommender_action->DbValue = $row['recommender_action'];
		$this->recommender_comment->DbValue = $row['recommender_comment'];
		$this->recommended_by->DbValue = $row['recommended_by'];
		$this->application_status->DbValue = $row['application_status'];
		$this->approved_amount->DbValue = $row['approved_amount'];
		$this->duration_approved->DbValue = $row['duration_approved'];
		$this->approval_date->DbValue = $row['approval_date'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
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
		// Convert decimal values if posted back

		if ($this->loan_amount->FormValue == $this->loan_amount->CurrentValue && is_numeric(ew_StrToFloat($this->loan_amount->CurrentValue)))
			$this->loan_amount->CurrentValue = ew_StrToFloat($this->loan_amount->CurrentValue);

		// Convert decimal values if posted back
		if ($this->salary_permonth->FormValue == $this->salary_permonth->CurrentValue && is_numeric(ew_StrToFloat($this->salary_permonth->CurrentValue)))
			$this->salary_permonth->CurrentValue = ew_StrToFloat($this->salary_permonth->CurrentValue);

		// Convert decimal values if posted back
		if ($this->previous_loan->FormValue == $this->previous_loan->CurrentValue && is_numeric(ew_StrToFloat($this->previous_loan->CurrentValue)))
			$this->previous_loan->CurrentValue = ew_StrToFloat($this->previous_loan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->balance_remaining->FormValue == $this->balance_remaining->CurrentValue && is_numeric(ew_StrToFloat($this->balance_remaining->CurrentValue)))
			$this->balance_remaining->CurrentValue = ew_StrToFloat($this->balance_remaining->CurrentValue);

		// Convert decimal values if posted back
		if ($this->approved_amount->FormValue == $this->approved_amount->CurrentValue && is_numeric(ew_StrToFloat($this->approved_amount->CurrentValue)))
			$this->approved_amount->CurrentValue = ew_StrToFloat($this->approved_amount->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// code
		// date_initiated
		// refernce_id
		// employee_name
		// address
		// mobile
		// department
		// pension
		// loan_amount
		// amount_inwords
		// purpose
		// repayment_period
		// salary_permonth
		// previous_loan
		// date_collected
		// date_liquidated
		// balance_remaining
		// applicant_date
		// applicant_passport
		// guarantor_name
		// guarantor_address
		// guarantor_mobile
		// guarantor_department
		// account_no
		// bank_name
		// employers_name
		// employers_address
		// employers_mobile
		// guarantor_date
		// guarantor_passport
		// status
		// initiator_action
		// initiator_comment
		// recommended_date
		// document_checklist
		// recommender_action
		// recommender_comment
		// recommended_by
		// application_status
		// approved_amount
		// duration_approved
		// approval_date
		// approval_action
		// approval_comment
		// approved_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// refernce_id
		$this->refernce_id->ViewValue = $this->refernce_id->CurrentValue;
		$this->refernce_id->ViewCustomAttributes = "";

		// employee_name
		if (strval($this->employee_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->employee_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->employee_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->employee_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->employee_name->ViewValue = $this->employee_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->employee_name->ViewValue = $this->employee_name->CurrentValue;
			}
		} else {
			$this->employee_name->ViewValue = NULL;
		}
		$this->employee_name->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// mobile
		$this->mobile->ViewValue = $this->mobile->CurrentValue;
		$this->mobile->ViewCustomAttributes = "";

		// department
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

		// pension
		$this->pension->ViewValue = $this->pension->CurrentValue;
		$this->pension->ViewCustomAttributes = "";

		// loan_amount
		$this->loan_amount->ViewValue = $this->loan_amount->CurrentValue;
		$this->loan_amount->ViewValue = ew_FormatNumber($this->loan_amount->ViewValue, 0, -2, -2, -2);
		$this->loan_amount->ViewCustomAttributes = "";

		// amount_inwords
		$this->amount_inwords->ViewValue = $this->amount_inwords->CurrentValue;
		$this->amount_inwords->ViewCustomAttributes = "";

		// purpose
		$this->purpose->ViewValue = $this->purpose->CurrentValue;
		$this->purpose->ViewCustomAttributes = "";

		// repayment_period
		if (strval($this->repayment_period->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->repayment_period->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
		$sWhereWrk = "";
		$this->repayment_period->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->repayment_period, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->repayment_period->ViewValue = $this->repayment_period->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->repayment_period->ViewValue = $this->repayment_period->CurrentValue;
			}
		} else {
			$this->repayment_period->ViewValue = NULL;
		}
		$this->repayment_period->ViewCustomAttributes = "";

		// salary_permonth
		$this->salary_permonth->ViewValue = $this->salary_permonth->CurrentValue;
		$this->salary_permonth->ViewValue = ew_FormatNumber($this->salary_permonth->ViewValue, 0, -2, -2, -2);
		$this->salary_permonth->ViewCustomAttributes = "";

		// previous_loan
		$this->previous_loan->ViewValue = $this->previous_loan->CurrentValue;
		$this->previous_loan->ViewValue = ew_FormatNumber($this->previous_loan->ViewValue, 0, -2, -2, -2);
		$this->previous_loan->ViewCustomAttributes = "";

		// date_collected
		$this->date_collected->ViewValue = $this->date_collected->CurrentValue;
		$this->date_collected->ViewValue = ew_FormatDateTime($this->date_collected->ViewValue, 0);
		$this->date_collected->ViewCustomAttributes = "";

		// date_liquidated
		$this->date_liquidated->ViewValue = $this->date_liquidated->CurrentValue;
		$this->date_liquidated->ViewValue = ew_FormatDateTime($this->date_liquidated->ViewValue, 0);
		$this->date_liquidated->ViewCustomAttributes = "";

		// balance_remaining
		$this->balance_remaining->ViewValue = $this->balance_remaining->CurrentValue;
		$this->balance_remaining->ViewValue = ew_FormatNumber($this->balance_remaining->ViewValue, 0, -2, -2, -2);
		$this->balance_remaining->ViewCustomAttributes = "";

		// applicant_date
		$this->applicant_date->ViewValue = $this->applicant_date->CurrentValue;
		$this->applicant_date->ViewValue = ew_FormatDateTime($this->applicant_date->ViewValue, 17);
		$this->applicant_date->ViewCustomAttributes = "";

		// applicant_passport
		if (!ew_Empty($this->applicant_passport->Upload->DbValue)) {
			$this->applicant_passport->ViewValue = $this->applicant_passport->Upload->DbValue;
		} else {
			$this->applicant_passport->ViewValue = "";
		}
		$this->applicant_passport->ViewCustomAttributes = "";

		// guarantor_name
		$this->guarantor_name->ViewValue = $this->guarantor_name->CurrentValue;
		$this->guarantor_name->ViewCustomAttributes = "";

		// guarantor_address
		$this->guarantor_address->ViewValue = $this->guarantor_address->CurrentValue;
		$this->guarantor_address->ViewCustomAttributes = "";

		// guarantor_mobile
		$this->guarantor_mobile->ViewValue = $this->guarantor_mobile->CurrentValue;
		$this->guarantor_mobile->ViewCustomAttributes = "";

		// guarantor_department
		if (strval($this->guarantor_department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->guarantor_department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->guarantor_department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->guarantor_department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->guarantor_department->ViewValue = $this->guarantor_department->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->guarantor_department->ViewValue = $this->guarantor_department->CurrentValue;
			}
		} else {
			$this->guarantor_department->ViewValue = NULL;
		}
		$this->guarantor_department->ViewCustomAttributes = "";

		// account_no
		$this->account_no->ViewValue = $this->account_no->CurrentValue;
		$this->account_no->ViewCustomAttributes = "";

		// bank_name
		if (strval($this->bank_name->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->bank_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `banks_list`";
		$sWhereWrk = "";
		$this->bank_name->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->bank_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->bank_name->ViewValue = $this->bank_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->bank_name->ViewValue = $this->bank_name->CurrentValue;
			}
		} else {
			$this->bank_name->ViewValue = NULL;
		}
		$this->bank_name->ViewCustomAttributes = "";

		// employers_name
		$this->employers_name->ViewValue = $this->employers_name->CurrentValue;
		$this->employers_name->ViewCustomAttributes = "";

		// employers_address
		$this->employers_address->ViewValue = $this->employers_address->CurrentValue;
		$this->employers_address->ViewCustomAttributes = "";

		// employers_mobile
		$this->employers_mobile->ViewValue = $this->employers_mobile->CurrentValue;
		$this->employers_mobile->ViewCustomAttributes = "";

		// guarantor_date
		$this->guarantor_date->ViewValue = $this->guarantor_date->CurrentValue;
		$this->guarantor_date->ViewValue = ew_FormatDateTime($this->guarantor_date->ViewValue, 17);
		$this->guarantor_date->ViewCustomAttributes = "";

		// guarantor_passport
		if (!ew_Empty($this->guarantor_passport->Upload->DbValue)) {
			$this->guarantor_passport->ViewValue = $this->guarantor_passport->Upload->DbValue;
		} else {
			$this->guarantor_passport->ViewValue = "";
		}
		$this->guarantor_passport->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `loan_status`";
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

		// recommended_date
		$this->recommended_date->ViewValue = $this->recommended_date->CurrentValue;
		$this->recommended_date->ViewValue = ew_FormatDateTime($this->recommended_date->ViewValue, 14);
		$this->recommended_date->ViewCustomAttributes = "";

		// document_checklist
		if (strval($this->document_checklist->CurrentValue) <> "") {
			$arwrk = explode(",", $this->document_checklist->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `discription` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `document_checklist`";
		$sWhereWrk = "";
		$this->document_checklist->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->document_checklist, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->document_checklist->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->document_checklist->ViewValue .= $this->document_checklist->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->document_checklist->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->document_checklist->ViewValue = $this->document_checklist->CurrentValue;
			}
		} else {
			$this->document_checklist->ViewValue = NULL;
		}
		$this->document_checklist->ViewCustomAttributes = "";

		// recommender_action
		if (strval($this->recommender_action->CurrentValue) <> "") {
			$this->recommender_action->ViewValue = $this->recommender_action->OptionCaption($this->recommender_action->CurrentValue);
		} else {
			$this->recommender_action->ViewValue = NULL;
		}
		$this->recommender_action->ViewCustomAttributes = "";

		// recommender_comment
		$this->recommender_comment->ViewValue = $this->recommender_comment->CurrentValue;
		$this->recommender_comment->ViewCustomAttributes = "";

		// recommended_by
		$this->recommended_by->ViewValue = $this->recommended_by->CurrentValue;
		if (strval($this->recommended_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recommended_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recommended_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recommended_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->recommended_by->ViewValue = $this->recommended_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recommended_by->ViewValue = $this->recommended_by->CurrentValue;
			}
		} else {
			$this->recommended_by->ViewValue = NULL;
		}
		$this->recommended_by->ViewCustomAttributes = "";

		// application_status
		if (strval($this->application_status->CurrentValue) <> "") {
			$this->application_status->ViewValue = $this->application_status->OptionCaption($this->application_status->CurrentValue);
		} else {
			$this->application_status->ViewValue = NULL;
		}
		$this->application_status->ViewCustomAttributes = "";

		// approved_amount
		$this->approved_amount->ViewValue = $this->approved_amount->CurrentValue;
		$this->approved_amount->ViewValue = ew_FormatNumber($this->approved_amount->ViewValue, 0, -2, -2, -2);
		$this->approved_amount->ViewCustomAttributes = "";

		// duration_approved
		if (strval($this->duration_approved->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->duration_approved->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
		$sWhereWrk = "";
		$this->duration_approved->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->duration_approved, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->duration_approved->ViewValue = $this->duration_approved->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->duration_approved->ViewValue = $this->duration_approved->CurrentValue;
			}
		} else {
			$this->duration_approved->ViewValue = NULL;
		}
		$this->duration_approved->ViewValue = ew_FormatDateTime($this->duration_approved->ViewValue, 0);
		$this->duration_approved->ViewCustomAttributes = "";

		// approval_date
		$this->approval_date->ViewValue = $this->approval_date->CurrentValue;
		$this->approval_date->ViewValue = ew_FormatDateTime($this->approval_date->ViewValue, 17);
		$this->approval_date->ViewCustomAttributes = "";

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

		// approved_by
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

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// refernce_id
			$this->refernce_id->LinkCustomAttributes = "";
			$this->refernce_id->HrefValue = "";
			$this->refernce_id->TooltipValue = "";

			// employee_name
			$this->employee_name->LinkCustomAttributes = "";
			$this->employee_name->HrefValue = "";
			$this->employee_name->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// mobile
			$this->mobile->LinkCustomAttributes = "";
			$this->mobile->HrefValue = "";
			$this->mobile->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// pension
			$this->pension->LinkCustomAttributes = "";
			$this->pension->HrefValue = "";
			$this->pension->TooltipValue = "";

			// loan_amount
			$this->loan_amount->LinkCustomAttributes = "";
			$this->loan_amount->HrefValue = "";
			$this->loan_amount->TooltipValue = "";

			// amount_inwords
			$this->amount_inwords->LinkCustomAttributes = "";
			$this->amount_inwords->HrefValue = "";
			$this->amount_inwords->TooltipValue = "";

			// purpose
			$this->purpose->LinkCustomAttributes = "";
			$this->purpose->HrefValue = "";
			$this->purpose->TooltipValue = "";

			// repayment_period
			$this->repayment_period->LinkCustomAttributes = "";
			$this->repayment_period->HrefValue = "";
			$this->repayment_period->TooltipValue = "";

			// salary_permonth
			$this->salary_permonth->LinkCustomAttributes = "";
			$this->salary_permonth->HrefValue = "";
			$this->salary_permonth->TooltipValue = "";

			// previous_loan
			$this->previous_loan->LinkCustomAttributes = "";
			$this->previous_loan->HrefValue = "";
			$this->previous_loan->TooltipValue = "";

			// date_collected
			$this->date_collected->LinkCustomAttributes = "";
			$this->date_collected->HrefValue = "";
			$this->date_collected->TooltipValue = "";

			// date_liquidated
			$this->date_liquidated->LinkCustomAttributes = "";
			$this->date_liquidated->HrefValue = "";
			$this->date_liquidated->TooltipValue = "";

			// balance_remaining
			$this->balance_remaining->LinkCustomAttributes = "";
			$this->balance_remaining->HrefValue = "";
			$this->balance_remaining->TooltipValue = "";

			// applicant_date
			$this->applicant_date->LinkCustomAttributes = "";
			$this->applicant_date->HrefValue = "";
			$this->applicant_date->TooltipValue = "";

			// applicant_passport
			$this->applicant_passport->LinkCustomAttributes = "";
			$this->applicant_passport->HrefValue = "";
			$this->applicant_passport->HrefValue2 = $this->applicant_passport->UploadPath . $this->applicant_passport->Upload->DbValue;
			$this->applicant_passport->TooltipValue = "";

			// guarantor_name
			$this->guarantor_name->LinkCustomAttributes = "";
			$this->guarantor_name->HrefValue = "";
			$this->guarantor_name->TooltipValue = "";

			// guarantor_address
			$this->guarantor_address->LinkCustomAttributes = "";
			$this->guarantor_address->HrefValue = "";
			$this->guarantor_address->TooltipValue = "";

			// guarantor_mobile
			$this->guarantor_mobile->LinkCustomAttributes = "";
			$this->guarantor_mobile->HrefValue = "";
			$this->guarantor_mobile->TooltipValue = "";

			// guarantor_department
			$this->guarantor_department->LinkCustomAttributes = "";
			$this->guarantor_department->HrefValue = "";
			$this->guarantor_department->TooltipValue = "";

			// account_no
			$this->account_no->LinkCustomAttributes = "";
			$this->account_no->HrefValue = "";
			$this->account_no->TooltipValue = "";

			// bank_name
			$this->bank_name->LinkCustomAttributes = "";
			$this->bank_name->HrefValue = "";
			$this->bank_name->TooltipValue = "";

			// employers_name
			$this->employers_name->LinkCustomAttributes = "";
			$this->employers_name->HrefValue = "";
			$this->employers_name->TooltipValue = "";

			// employers_address
			$this->employers_address->LinkCustomAttributes = "";
			$this->employers_address->HrefValue = "";
			$this->employers_address->TooltipValue = "";

			// employers_mobile
			$this->employers_mobile->LinkCustomAttributes = "";
			$this->employers_mobile->HrefValue = "";
			$this->employers_mobile->TooltipValue = "";

			// guarantor_date
			$this->guarantor_date->LinkCustomAttributes = "";
			$this->guarantor_date->HrefValue = "";
			$this->guarantor_date->TooltipValue = "";

			// guarantor_passport
			$this->guarantor_passport->LinkCustomAttributes = "";
			$this->guarantor_passport->HrefValue = "";
			$this->guarantor_passport->HrefValue2 = $this->guarantor_passport->UploadPath . $this->guarantor_passport->Upload->DbValue;
			$this->guarantor_passport->TooltipValue = "";

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

			// recommended_date
			$this->recommended_date->LinkCustomAttributes = "";
			$this->recommended_date->HrefValue = "";
			$this->recommended_date->TooltipValue = "";

			// document_checklist
			$this->document_checklist->LinkCustomAttributes = "";
			$this->document_checklist->HrefValue = "";
			$this->document_checklist->TooltipValue = "";

			// recommender_action
			$this->recommender_action->LinkCustomAttributes = "";
			$this->recommender_action->HrefValue = "";
			$this->recommender_action->TooltipValue = "";

			// recommender_comment
			$this->recommender_comment->LinkCustomAttributes = "";
			$this->recommender_comment->HrefValue = "";
			$this->recommender_comment->TooltipValue = "";

			// recommended_by
			$this->recommended_by->LinkCustomAttributes = "";
			$this->recommended_by->HrefValue = "";
			$this->recommended_by->TooltipValue = "";

			// application_status
			$this->application_status->LinkCustomAttributes = "";
			$this->application_status->HrefValue = "";
			$this->application_status->TooltipValue = "";

			// approved_amount
			$this->approved_amount->LinkCustomAttributes = "";
			$this->approved_amount->HrefValue = "";
			$this->approved_amount->TooltipValue = "";

			// duration_approved
			$this->duration_approved->LinkCustomAttributes = "";
			$this->duration_approved->HrefValue = "";
			$this->duration_approved->TooltipValue = "";

			// approval_date
			$this->approval_date->LinkCustomAttributes = "";
			$this->approval_date->HrefValue = "";
			$this->approval_date->TooltipValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";
			$this->approval_action->TooltipValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";
			$this->approval_comment->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_initiated->CurrentValue, 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// refernce_id
			$this->refernce_id->EditAttrs["class"] = "form-control";
			$this->refernce_id->EditCustomAttributes = "";
			$this->refernce_id->EditValue = ew_HtmlEncode($this->refernce_id->CurrentValue);
			$this->refernce_id->PlaceHolder = ew_RemoveHtml($this->refernce_id->FldCaption());

			// employee_name
			$this->employee_name->EditCustomAttributes = "";
			if (trim(strval($this->employee_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->employee_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->employee_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->employee_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->employee_name->ViewValue = $this->employee_name->DisplayValue($arwrk);
			} else {
				$this->employee_name->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->employee_name->EditValue = $arwrk;

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// mobile
			$this->mobile->EditAttrs["class"] = "form-control";
			$this->mobile->EditCustomAttributes = "";
			$this->mobile->EditValue = ew_HtmlEncode($this->mobile->CurrentValue);
			$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
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
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->department->EditValue = $arwrk;

			// pension
			$this->pension->EditAttrs["class"] = "form-control";
			$this->pension->EditCustomAttributes = "";
			$this->pension->EditValue = ew_HtmlEncode($this->pension->CurrentValue);
			$this->pension->PlaceHolder = ew_RemoveHtml($this->pension->FldCaption());

			// loan_amount
			$this->loan_amount->EditAttrs["class"] = "form-control";
			$this->loan_amount->EditCustomAttributes = "";
			$this->loan_amount->EditValue = ew_HtmlEncode($this->loan_amount->CurrentValue);
			$this->loan_amount->PlaceHolder = ew_RemoveHtml($this->loan_amount->FldCaption());
			if (strval($this->loan_amount->EditValue) <> "" && is_numeric($this->loan_amount->EditValue)) $this->loan_amount->EditValue = ew_FormatNumber($this->loan_amount->EditValue, -2, -2, -2, -2);

			// amount_inwords
			$this->amount_inwords->EditAttrs["class"] = "form-control";
			$this->amount_inwords->EditCustomAttributes = "";
			$this->amount_inwords->EditValue = ew_HtmlEncode($this->amount_inwords->CurrentValue);
			$this->amount_inwords->PlaceHolder = ew_RemoveHtml($this->amount_inwords->FldCaption());

			// purpose
			$this->purpose->EditAttrs["class"] = "form-control";
			$this->purpose->EditCustomAttributes = "";
			$this->purpose->EditValue = ew_HtmlEncode($this->purpose->CurrentValue);
			$this->purpose->PlaceHolder = ew_RemoveHtml($this->purpose->FldCaption());

			// repayment_period
			$this->repayment_period->EditAttrs["class"] = "form-control";
			$this->repayment_period->EditCustomAttributes = "";
			if (trim(strval($this->repayment_period->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->repayment_period->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `duration_months`";
			$sWhereWrk = "";
			$this->repayment_period->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->repayment_period, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->repayment_period->EditValue = $arwrk;

			// salary_permonth
			$this->salary_permonth->EditAttrs["class"] = "form-control";
			$this->salary_permonth->EditCustomAttributes = "";
			$this->salary_permonth->EditValue = ew_HtmlEncode($this->salary_permonth->CurrentValue);
			$this->salary_permonth->PlaceHolder = ew_RemoveHtml($this->salary_permonth->FldCaption());
			if (strval($this->salary_permonth->EditValue) <> "" && is_numeric($this->salary_permonth->EditValue)) $this->salary_permonth->EditValue = ew_FormatNumber($this->salary_permonth->EditValue, -2, -2, -2, -2);

			// previous_loan
			$this->previous_loan->EditAttrs["class"] = "form-control";
			$this->previous_loan->EditCustomAttributes = "";
			$this->previous_loan->EditValue = ew_HtmlEncode($this->previous_loan->CurrentValue);
			$this->previous_loan->PlaceHolder = ew_RemoveHtml($this->previous_loan->FldCaption());
			if (strval($this->previous_loan->EditValue) <> "" && is_numeric($this->previous_loan->EditValue)) $this->previous_loan->EditValue = ew_FormatNumber($this->previous_loan->EditValue, -2, -2, -2, -2);

			// date_collected
			$this->date_collected->EditAttrs["class"] = "form-control";
			$this->date_collected->EditCustomAttributes = "";
			$this->date_collected->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_collected->CurrentValue, 8));
			$this->date_collected->PlaceHolder = ew_RemoveHtml($this->date_collected->FldCaption());

			// date_liquidated
			$this->date_liquidated->EditAttrs["class"] = "form-control";
			$this->date_liquidated->EditCustomAttributes = "";
			$this->date_liquidated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_liquidated->CurrentValue, 8));
			$this->date_liquidated->PlaceHolder = ew_RemoveHtml($this->date_liquidated->FldCaption());

			// balance_remaining
			$this->balance_remaining->EditAttrs["class"] = "form-control";
			$this->balance_remaining->EditCustomAttributes = "";
			$this->balance_remaining->EditValue = ew_HtmlEncode($this->balance_remaining->CurrentValue);
			$this->balance_remaining->PlaceHolder = ew_RemoveHtml($this->balance_remaining->FldCaption());
			if (strval($this->balance_remaining->EditValue) <> "" && is_numeric($this->balance_remaining->EditValue)) $this->balance_remaining->EditValue = ew_FormatNumber($this->balance_remaining->EditValue, -2, -2, -2, -2);

			// applicant_date
			$this->applicant_date->EditAttrs["class"] = "form-control";
			$this->applicant_date->EditCustomAttributes = "";
			$this->applicant_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->applicant_date->CurrentValue, 17));
			$this->applicant_date->PlaceHolder = ew_RemoveHtml($this->applicant_date->FldCaption());

			// applicant_passport
			$this->applicant_passport->EditAttrs["class"] = "form-control";
			$this->applicant_passport->EditCustomAttributes = "";
			if (!ew_Empty($this->applicant_passport->Upload->DbValue)) {
				$this->applicant_passport->EditValue = $this->applicant_passport->Upload->DbValue;
			} else {
				$this->applicant_passport->EditValue = "";
			}
			if (!ew_Empty($this->applicant_passport->CurrentValue))
					$this->applicant_passport->Upload->FileName = $this->applicant_passport->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->applicant_passport);

			// guarantor_name
			$this->guarantor_name->EditAttrs["class"] = "form-control";
			$this->guarantor_name->EditCustomAttributes = "";
			$this->guarantor_name->EditValue = ew_HtmlEncode($this->guarantor_name->CurrentValue);
			$this->guarantor_name->PlaceHolder = ew_RemoveHtml($this->guarantor_name->FldCaption());

			// guarantor_address
			$this->guarantor_address->EditAttrs["class"] = "form-control";
			$this->guarantor_address->EditCustomAttributes = "";
			$this->guarantor_address->EditValue = ew_HtmlEncode($this->guarantor_address->CurrentValue);
			$this->guarantor_address->PlaceHolder = ew_RemoveHtml($this->guarantor_address->FldCaption());

			// guarantor_mobile
			$this->guarantor_mobile->EditAttrs["class"] = "form-control";
			$this->guarantor_mobile->EditCustomAttributes = "";
			$this->guarantor_mobile->EditValue = ew_HtmlEncode($this->guarantor_mobile->CurrentValue);
			$this->guarantor_mobile->PlaceHolder = ew_RemoveHtml($this->guarantor_mobile->FldCaption());

			// guarantor_department
			$this->guarantor_department->EditAttrs["class"] = "form-control";
			$this->guarantor_department->EditCustomAttributes = "";
			if (trim(strval($this->guarantor_department->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->guarantor_department->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `depertment`";
			$sWhereWrk = "";
			$this->guarantor_department->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->guarantor_department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->guarantor_department->EditValue = $arwrk;

			// account_no
			$this->account_no->EditAttrs["class"] = "form-control";
			$this->account_no->EditCustomAttributes = "";
			$this->account_no->EditValue = ew_HtmlEncode($this->account_no->CurrentValue);
			$this->account_no->PlaceHolder = ew_RemoveHtml($this->account_no->FldCaption());

			// bank_name
			$this->bank_name->EditCustomAttributes = "";
			if (trim(strval($this->bank_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->bank_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `banks_list`";
			$sWhereWrk = "";
			$this->bank_name->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->bank_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->bank_name->ViewValue = $this->bank_name->DisplayValue($arwrk);
			} else {
				$this->bank_name->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->bank_name->EditValue = $arwrk;

			// employers_name
			$this->employers_name->EditAttrs["class"] = "form-control";
			$this->employers_name->EditCustomAttributes = "";
			$this->employers_name->EditValue = ew_HtmlEncode($this->employers_name->CurrentValue);
			$this->employers_name->PlaceHolder = ew_RemoveHtml($this->employers_name->FldCaption());

			// employers_address
			$this->employers_address->EditAttrs["class"] = "form-control";
			$this->employers_address->EditCustomAttributes = "";
			$this->employers_address->EditValue = ew_HtmlEncode($this->employers_address->CurrentValue);
			$this->employers_address->PlaceHolder = ew_RemoveHtml($this->employers_address->FldCaption());

			// employers_mobile
			$this->employers_mobile->EditAttrs["class"] = "form-control";
			$this->employers_mobile->EditCustomAttributes = "";
			$this->employers_mobile->EditValue = ew_HtmlEncode($this->employers_mobile->CurrentValue);
			$this->employers_mobile->PlaceHolder = ew_RemoveHtml($this->employers_mobile->FldCaption());

			// guarantor_date
			$this->guarantor_date->EditAttrs["class"] = "form-control";
			$this->guarantor_date->EditCustomAttributes = "";
			$this->guarantor_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->guarantor_date->CurrentValue, 17));
			$this->guarantor_date->PlaceHolder = ew_RemoveHtml($this->guarantor_date->FldCaption());

			// guarantor_passport
			$this->guarantor_passport->EditAttrs["class"] = "form-control";
			$this->guarantor_passport->EditCustomAttributes = "";
			if (!ew_Empty($this->guarantor_passport->Upload->DbValue)) {
				$this->guarantor_passport->EditValue = $this->guarantor_passport->Upload->DbValue;
			} else {
				$this->guarantor_passport->EditValue = "";
			}
			if (!ew_Empty($this->guarantor_passport->CurrentValue))
					$this->guarantor_passport->Upload->FileName = $this->guarantor_passport->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->guarantor_passport);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			if (strval($this->status->CurrentValue) <> "") {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `loan_status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->status->EditValue = $this->status->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
				}
			} else {
				$this->status->EditValue = NULL;
			}
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// recommended_date
			$this->recommended_date->EditAttrs["class"] = "form-control";
			$this->recommended_date->EditCustomAttributes = "";
			$this->recommended_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->recommended_date->CurrentValue, 14));
			$this->recommended_date->PlaceHolder = ew_RemoveHtml($this->recommended_date->FldCaption());

			// document_checklist
			$this->document_checklist->EditCustomAttributes = "";
			if (trim(strval($this->document_checklist->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->document_checklist->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `code`, `discription` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `document_checklist`";
			$sWhereWrk = "";
			$this->document_checklist->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->document_checklist, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->document_checklist->EditValue = $arwrk;

			// recommender_action
			$this->recommender_action->EditCustomAttributes = "";
			$this->recommender_action->EditValue = $this->recommender_action->Options(FALSE);

			// recommender_comment
			$this->recommender_comment->EditAttrs["class"] = "form-control";
			$this->recommender_comment->EditCustomAttributes = "";
			$this->recommender_comment->EditValue = ew_HtmlEncode($this->recommender_comment->CurrentValue);
			$this->recommender_comment->PlaceHolder = ew_RemoveHtml($this->recommender_comment->FldCaption());

			// recommended_by
			$this->recommended_by->EditAttrs["class"] = "form-control";
			$this->recommended_by->EditCustomAttributes = "";
			$this->recommended_by->EditValue = ew_HtmlEncode($this->recommended_by->CurrentValue);
			if (strval($this->recommended_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->recommended_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->recommended_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->recommended_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->recommended_by->EditValue = $this->recommended_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->recommended_by->EditValue = ew_HtmlEncode($this->recommended_by->CurrentValue);
				}
			} else {
				$this->recommended_by->EditValue = NULL;
			}
			$this->recommended_by->PlaceHolder = ew_RemoveHtml($this->recommended_by->FldCaption());

			// application_status
			$this->application_status->EditCustomAttributes = "";
			$this->application_status->EditValue = $this->application_status->Options(FALSE);

			// approved_amount
			$this->approved_amount->EditAttrs["class"] = "form-control";
			$this->approved_amount->EditCustomAttributes = "";
			$this->approved_amount->EditValue = ew_HtmlEncode($this->approved_amount->CurrentValue);
			$this->approved_amount->PlaceHolder = ew_RemoveHtml($this->approved_amount->FldCaption());
			if (strval($this->approved_amount->EditValue) <> "" && is_numeric($this->approved_amount->EditValue)) $this->approved_amount->EditValue = ew_FormatNumber($this->approved_amount->EditValue, -2, -2, -2, -2);

			// duration_approved
			$this->duration_approved->EditAttrs["class"] = "form-control";
			$this->duration_approved->EditCustomAttributes = "";
			if (trim(strval($this->duration_approved->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->duration_approved->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `duration_months`";
			$sWhereWrk = "";
			$this->duration_approved->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->duration_approved, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->duration_approved->EditValue = $arwrk;

			// approval_date
			$this->approval_date->EditAttrs["class"] = "form-control";
			$this->approval_date->EditCustomAttributes = "";
			$this->approval_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->approval_date->CurrentValue, 17));
			$this->approval_date->PlaceHolder = ew_RemoveHtml($this->approval_date->FldCaption());

			// approval_action
			$this->approval_action->EditCustomAttributes = "";
			$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

			// approval_comment
			$this->approval_comment->EditAttrs["class"] = "form-control";
			$this->approval_comment->EditCustomAttributes = "";
			$this->approval_comment->EditValue = ew_HtmlEncode($this->approval_comment->CurrentValue);
			$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			if (trim(strval($this->approved_by->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->approved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->approved_by->EditValue = $arwrk;

			// Add refer script
			// date_initiated

			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";

			// refernce_id
			$this->refernce_id->LinkCustomAttributes = "";
			$this->refernce_id->HrefValue = "";

			// employee_name
			$this->employee_name->LinkCustomAttributes = "";
			$this->employee_name->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// mobile
			$this->mobile->LinkCustomAttributes = "";
			$this->mobile->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// pension
			$this->pension->LinkCustomAttributes = "";
			$this->pension->HrefValue = "";

			// loan_amount
			$this->loan_amount->LinkCustomAttributes = "";
			$this->loan_amount->HrefValue = "";

			// amount_inwords
			$this->amount_inwords->LinkCustomAttributes = "";
			$this->amount_inwords->HrefValue = "";

			// purpose
			$this->purpose->LinkCustomAttributes = "";
			$this->purpose->HrefValue = "";

			// repayment_period
			$this->repayment_period->LinkCustomAttributes = "";
			$this->repayment_period->HrefValue = "";

			// salary_permonth
			$this->salary_permonth->LinkCustomAttributes = "";
			$this->salary_permonth->HrefValue = "";

			// previous_loan
			$this->previous_loan->LinkCustomAttributes = "";
			$this->previous_loan->HrefValue = "";

			// date_collected
			$this->date_collected->LinkCustomAttributes = "";
			$this->date_collected->HrefValue = "";

			// date_liquidated
			$this->date_liquidated->LinkCustomAttributes = "";
			$this->date_liquidated->HrefValue = "";

			// balance_remaining
			$this->balance_remaining->LinkCustomAttributes = "";
			$this->balance_remaining->HrefValue = "";

			// applicant_date
			$this->applicant_date->LinkCustomAttributes = "";
			$this->applicant_date->HrefValue = "";

			// applicant_passport
			$this->applicant_passport->LinkCustomAttributes = "";
			$this->applicant_passport->HrefValue = "";
			$this->applicant_passport->HrefValue2 = $this->applicant_passport->UploadPath . $this->applicant_passport->Upload->DbValue;

			// guarantor_name
			$this->guarantor_name->LinkCustomAttributes = "";
			$this->guarantor_name->HrefValue = "";

			// guarantor_address
			$this->guarantor_address->LinkCustomAttributes = "";
			$this->guarantor_address->HrefValue = "";

			// guarantor_mobile
			$this->guarantor_mobile->LinkCustomAttributes = "";
			$this->guarantor_mobile->HrefValue = "";

			// guarantor_department
			$this->guarantor_department->LinkCustomAttributes = "";
			$this->guarantor_department->HrefValue = "";

			// account_no
			$this->account_no->LinkCustomAttributes = "";
			$this->account_no->HrefValue = "";

			// bank_name
			$this->bank_name->LinkCustomAttributes = "";
			$this->bank_name->HrefValue = "";

			// employers_name
			$this->employers_name->LinkCustomAttributes = "";
			$this->employers_name->HrefValue = "";

			// employers_address
			$this->employers_address->LinkCustomAttributes = "";
			$this->employers_address->HrefValue = "";

			// employers_mobile
			$this->employers_mobile->LinkCustomAttributes = "";
			$this->employers_mobile->HrefValue = "";

			// guarantor_date
			$this->guarantor_date->LinkCustomAttributes = "";
			$this->guarantor_date->HrefValue = "";

			// guarantor_passport
			$this->guarantor_passport->LinkCustomAttributes = "";
			$this->guarantor_passport->HrefValue = "";
			$this->guarantor_passport->HrefValue2 = $this->guarantor_passport->UploadPath . $this->guarantor_passport->Upload->DbValue;

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

			// recommended_date
			$this->recommended_date->LinkCustomAttributes = "";
			$this->recommended_date->HrefValue = "";

			// document_checklist
			$this->document_checklist->LinkCustomAttributes = "";
			$this->document_checklist->HrefValue = "";

			// recommender_action
			$this->recommender_action->LinkCustomAttributes = "";
			$this->recommender_action->HrefValue = "";

			// recommender_comment
			$this->recommender_comment->LinkCustomAttributes = "";
			$this->recommender_comment->HrefValue = "";

			// recommended_by
			$this->recommended_by->LinkCustomAttributes = "";
			$this->recommended_by->HrefValue = "";

			// application_status
			$this->application_status->LinkCustomAttributes = "";
			$this->application_status->HrefValue = "";

			// approved_amount
			$this->approved_amount->LinkCustomAttributes = "";
			$this->approved_amount->HrefValue = "";

			// duration_approved
			$this->duration_approved->LinkCustomAttributes = "";
			$this->duration_approved->HrefValue = "";

			// approval_date
			$this->approval_date->LinkCustomAttributes = "";
			$this->approval_date->HrefValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";

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
		if (!$this->date_initiated->FldIsDetailKey && !is_null($this->date_initiated->FormValue) && $this->date_initiated->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date_initiated->FldCaption(), $this->date_initiated->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date_initiated->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_initiated->FldErrMsg());
		}
		if (!$this->refernce_id->FldIsDetailKey && !is_null($this->refernce_id->FormValue) && $this->refernce_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->refernce_id->FldCaption(), $this->refernce_id->ReqErrMsg));
		}
		if (!$this->employee_name->FldIsDetailKey && !is_null($this->employee_name->FormValue) && $this->employee_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->employee_name->FldCaption(), $this->employee_name->ReqErrMsg));
		}
		if (!$this->address->FldIsDetailKey && !is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address->FldCaption(), $this->address->ReqErrMsg));
		}
		if (!$this->mobile->FldIsDetailKey && !is_null($this->mobile->FormValue) && $this->mobile->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mobile->FldCaption(), $this->mobile->ReqErrMsg));
		}
		if (!$this->department->FldIsDetailKey && !is_null($this->department->FormValue) && $this->department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->department->FldCaption(), $this->department->ReqErrMsg));
		}
		if (!$this->pension->FldIsDetailKey && !is_null($this->pension->FormValue) && $this->pension->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pension->FldCaption(), $this->pension->ReqErrMsg));
		}
		if (!$this->loan_amount->FldIsDetailKey && !is_null($this->loan_amount->FormValue) && $this->loan_amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->loan_amount->FldCaption(), $this->loan_amount->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->loan_amount->FormValue)) {
			ew_AddMessage($gsFormError, $this->loan_amount->FldErrMsg());
		}
		if (!$this->amount_inwords->FldIsDetailKey && !is_null($this->amount_inwords->FormValue) && $this->amount_inwords->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->amount_inwords->FldCaption(), $this->amount_inwords->ReqErrMsg));
		}
		if (!$this->purpose->FldIsDetailKey && !is_null($this->purpose->FormValue) && $this->purpose->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->purpose->FldCaption(), $this->purpose->ReqErrMsg));
		}
		if (!$this->repayment_period->FldIsDetailKey && !is_null($this->repayment_period->FormValue) && $this->repayment_period->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->repayment_period->FldCaption(), $this->repayment_period->ReqErrMsg));
		}
		if (!$this->salary_permonth->FldIsDetailKey && !is_null($this->salary_permonth->FormValue) && $this->salary_permonth->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->salary_permonth->FldCaption(), $this->salary_permonth->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->salary_permonth->FormValue)) {
			ew_AddMessage($gsFormError, $this->salary_permonth->FldErrMsg());
		}
		if (!ew_CheckNumber($this->previous_loan->FormValue)) {
			ew_AddMessage($gsFormError, $this->previous_loan->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_collected->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_collected->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_liquidated->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_liquidated->FldErrMsg());
		}
		if (!ew_CheckNumber($this->balance_remaining->FormValue)) {
			ew_AddMessage($gsFormError, $this->balance_remaining->FldErrMsg());
		}
		if (!$this->applicant_date->FldIsDetailKey && !is_null($this->applicant_date->FormValue) && $this->applicant_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->applicant_date->FldCaption(), $this->applicant_date->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->applicant_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->applicant_date->FldErrMsg());
		}
		if ($this->applicant_passport->Upload->FileName == "" && !$this->applicant_passport->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->applicant_passport->FldCaption(), $this->applicant_passport->ReqErrMsg));
		}
		if (!$this->guarantor_name->FldIsDetailKey && !is_null($this->guarantor_name->FormValue) && $this->guarantor_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->guarantor_name->FldCaption(), $this->guarantor_name->ReqErrMsg));
		}
		if (!$this->guarantor_address->FldIsDetailKey && !is_null($this->guarantor_address->FormValue) && $this->guarantor_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->guarantor_address->FldCaption(), $this->guarantor_address->ReqErrMsg));
		}
		if (!$this->guarantor_mobile->FldIsDetailKey && !is_null($this->guarantor_mobile->FormValue) && $this->guarantor_mobile->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->guarantor_mobile->FldCaption(), $this->guarantor_mobile->ReqErrMsg));
		}
		if (!$this->guarantor_department->FldIsDetailKey && !is_null($this->guarantor_department->FormValue) && $this->guarantor_department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->guarantor_department->FldCaption(), $this->guarantor_department->ReqErrMsg));
		}
		if (!$this->account_no->FldIsDetailKey && !is_null($this->account_no->FormValue) && $this->account_no->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->account_no->FldCaption(), $this->account_no->ReqErrMsg));
		}
		if (!$this->bank_name->FldIsDetailKey && !is_null($this->bank_name->FormValue) && $this->bank_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bank_name->FldCaption(), $this->bank_name->ReqErrMsg));
		}
		if (!$this->employers_name->FldIsDetailKey && !is_null($this->employers_name->FormValue) && $this->employers_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->employers_name->FldCaption(), $this->employers_name->ReqErrMsg));
		}
		if (!$this->employers_address->FldIsDetailKey && !is_null($this->employers_address->FormValue) && $this->employers_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->employers_address->FldCaption(), $this->employers_address->ReqErrMsg));
		}
		if (!$this->employers_mobile->FldIsDetailKey && !is_null($this->employers_mobile->FormValue) && $this->employers_mobile->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->employers_mobile->FldCaption(), $this->employers_mobile->ReqErrMsg));
		}
		if (!$this->guarantor_date->FldIsDetailKey && !is_null($this->guarantor_date->FormValue) && $this->guarantor_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->guarantor_date->FldCaption(), $this->guarantor_date->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->guarantor_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->guarantor_date->FldErrMsg());
		}
		if ($this->guarantor_passport->Upload->FileName == "" && !$this->guarantor_passport->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->guarantor_passport->FldCaption(), $this->guarantor_passport->ReqErrMsg));
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
		if (!$this->recommended_date->FldIsDetailKey && !is_null($this->recommended_date->FormValue) && $this->recommended_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recommended_date->FldCaption(), $this->recommended_date->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->recommended_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->recommended_date->FldErrMsg());
		}
		if ($this->document_checklist->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->document_checklist->FldCaption(), $this->document_checklist->ReqErrMsg));
		}
		if ($this->recommender_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recommender_action->FldCaption(), $this->recommender_action->ReqErrMsg));
		}
		if (!$this->recommender_comment->FldIsDetailKey && !is_null($this->recommender_comment->FormValue) && $this->recommender_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recommender_comment->FldCaption(), $this->recommender_comment->ReqErrMsg));
		}
		if (!$this->recommended_by->FldIsDetailKey && !is_null($this->recommended_by->FormValue) && $this->recommended_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recommended_by->FldCaption(), $this->recommended_by->ReqErrMsg));
		}
		if ($this->application_status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->application_status->FldCaption(), $this->application_status->ReqErrMsg));
		}
		if (!$this->approved_amount->FldIsDetailKey && !is_null($this->approved_amount->FormValue) && $this->approved_amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approved_amount->FldCaption(), $this->approved_amount->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->approved_amount->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_amount->FldErrMsg());
		}
		if (!$this->duration_approved->FldIsDetailKey && !is_null($this->duration_approved->FormValue) && $this->duration_approved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->duration_approved->FldCaption(), $this->duration_approved->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->approval_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->approval_date->FldErrMsg());
		}
		if ($this->approval_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approval_action->FldCaption(), $this->approval_action->ReqErrMsg));
		}
		if (!$this->approval_comment->FldIsDetailKey && !is_null($this->approval_comment->FormValue) && $this->approval_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approval_comment->FldCaption(), $this->approval_comment->ReqErrMsg));
		}
		if (!$this->approved_by->FldIsDetailKey && !is_null($this->approved_by->FormValue) && $this->approved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approved_by->FldCaption(), $this->approved_by->ReqErrMsg));
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

		// date_initiated
		$this->date_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0), NULL, FALSE);

		// refernce_id
		$this->refernce_id->SetDbValueDef($rsnew, $this->refernce_id->CurrentValue, NULL, FALSE);

		// employee_name
		$this->employee_name->SetDbValueDef($rsnew, $this->employee_name->CurrentValue, NULL, FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, FALSE);

		// mobile
		$this->mobile->SetDbValueDef($rsnew, $this->mobile->CurrentValue, NULL, FALSE);

		// department
		$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, FALSE);

		// pension
		$this->pension->SetDbValueDef($rsnew, $this->pension->CurrentValue, NULL, FALSE);

		// loan_amount
		$this->loan_amount->SetDbValueDef($rsnew, $this->loan_amount->CurrentValue, NULL, FALSE);

		// amount_inwords
		$this->amount_inwords->SetDbValueDef($rsnew, $this->amount_inwords->CurrentValue, NULL, FALSE);

		// purpose
		$this->purpose->SetDbValueDef($rsnew, $this->purpose->CurrentValue, NULL, FALSE);

		// repayment_period
		$this->repayment_period->SetDbValueDef($rsnew, $this->repayment_period->CurrentValue, NULL, FALSE);

		// salary_permonth
		$this->salary_permonth->SetDbValueDef($rsnew, $this->salary_permonth->CurrentValue, NULL, FALSE);

		// previous_loan
		$this->previous_loan->SetDbValueDef($rsnew, $this->previous_loan->CurrentValue, NULL, FALSE);

		// date_collected
		$this->date_collected->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_collected->CurrentValue, 0), NULL, FALSE);

		// date_liquidated
		$this->date_liquidated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_liquidated->CurrentValue, 0), NULL, FALSE);

		// balance_remaining
		$this->balance_remaining->SetDbValueDef($rsnew, $this->balance_remaining->CurrentValue, NULL, FALSE);

		// applicant_date
		$this->applicant_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->applicant_date->CurrentValue, 17), NULL, FALSE);

		// applicant_passport
		if ($this->applicant_passport->Visible && !$this->applicant_passport->Upload->KeepFile) {
			$this->applicant_passport->Upload->DbValue = ""; // No need to delete old file
			if ($this->applicant_passport->Upload->FileName == "") {
				$rsnew['applicant_passport'] = NULL;
			} else {
				$rsnew['applicant_passport'] = $this->applicant_passport->Upload->FileName;
			}
		}

		// guarantor_name
		$this->guarantor_name->SetDbValueDef($rsnew, $this->guarantor_name->CurrentValue, NULL, FALSE);

		// guarantor_address
		$this->guarantor_address->SetDbValueDef($rsnew, $this->guarantor_address->CurrentValue, NULL, FALSE);

		// guarantor_mobile
		$this->guarantor_mobile->SetDbValueDef($rsnew, $this->guarantor_mobile->CurrentValue, NULL, FALSE);

		// guarantor_department
		$this->guarantor_department->SetDbValueDef($rsnew, $this->guarantor_department->CurrentValue, NULL, FALSE);

		// account_no
		$this->account_no->SetDbValueDef($rsnew, $this->account_no->CurrentValue, NULL, FALSE);

		// bank_name
		$this->bank_name->SetDbValueDef($rsnew, $this->bank_name->CurrentValue, NULL, FALSE);

		// employers_name
		$this->employers_name->SetDbValueDef($rsnew, $this->employers_name->CurrentValue, NULL, FALSE);

		// employers_address
		$this->employers_address->SetDbValueDef($rsnew, $this->employers_address->CurrentValue, NULL, FALSE);

		// employers_mobile
		$this->employers_mobile->SetDbValueDef($rsnew, $this->employers_mobile->CurrentValue, NULL, FALSE);

		// guarantor_date
		$this->guarantor_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->guarantor_date->CurrentValue, 17), NULL, FALSE);

		// guarantor_passport
		if ($this->guarantor_passport->Visible && !$this->guarantor_passport->Upload->KeepFile) {
			$this->guarantor_passport->Upload->DbValue = ""; // No need to delete old file
			if ($this->guarantor_passport->Upload->FileName == "") {
				$rsnew['guarantor_passport'] = NULL;
			} else {
				$rsnew['guarantor_passport'] = $this->guarantor_passport->Upload->FileName;
			}
		}

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// initiator_action
		$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, FALSE);

		// initiator_comment
		$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, FALSE);

		// recommended_date
		$this->recommended_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->recommended_date->CurrentValue, 14), NULL, FALSE);

		// document_checklist
		$this->document_checklist->SetDbValueDef($rsnew, $this->document_checklist->CurrentValue, NULL, FALSE);

		// recommender_action
		$this->recommender_action->SetDbValueDef($rsnew, $this->recommender_action->CurrentValue, NULL, FALSE);

		// recommender_comment
		$this->recommender_comment->SetDbValueDef($rsnew, $this->recommender_comment->CurrentValue, NULL, FALSE);

		// recommended_by
		$this->recommended_by->SetDbValueDef($rsnew, $this->recommended_by->CurrentValue, NULL, FALSE);

		// application_status
		$this->application_status->SetDbValueDef($rsnew, $this->application_status->CurrentValue, NULL, FALSE);

		// approved_amount
		$this->approved_amount->SetDbValueDef($rsnew, $this->approved_amount->CurrentValue, NULL, FALSE);

		// duration_approved
		$this->duration_approved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->duration_approved->CurrentValue, 0), NULL, FALSE);

		// approval_date
		$this->approval_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->approval_date->CurrentValue, 17), NULL, FALSE);

		// approval_action
		$this->approval_action->SetDbValueDef($rsnew, $this->approval_action->CurrentValue, NULL, FALSE);

		// approval_comment
		$this->approval_comment->SetDbValueDef($rsnew, $this->approval_comment->CurrentValue, NULL, FALSE);

		// approved_by
		$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, FALSE);
		if ($this->applicant_passport->Visible && !$this->applicant_passport->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->applicant_passport->Upload->DbValue) ? array() : array($this->applicant_passport->Upload->DbValue);
			if (!ew_Empty($this->applicant_passport->Upload->FileName)) {
				$NewFiles = array($this->applicant_passport->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->applicant_passport->Upload->Index < 0) ? $this->applicant_passport->FldVar : substr($this->applicant_passport->FldVar, 0, 1) . $this->applicant_passport->Upload->Index . substr($this->applicant_passport->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->applicant_passport->TblVar) . $file)) {
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
							$file1 = ew_UploadFileNameEx($this->applicant_passport->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->applicant_passport->TblVar) . $file1) || file_exists($this->applicant_passport->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->applicant_passport->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->applicant_passport->TblVar) . $file, ew_UploadTempPath($fldvar, $this->applicant_passport->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->applicant_passport->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->applicant_passport->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->applicant_passport->SetDbValueDef($rsnew, $this->applicant_passport->Upload->FileName, NULL, FALSE);
			}
		}
		if ($this->guarantor_passport->Visible && !$this->guarantor_passport->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->guarantor_passport->Upload->DbValue) ? array() : array($this->guarantor_passport->Upload->DbValue);
			if (!ew_Empty($this->guarantor_passport->Upload->FileName)) {
				$NewFiles = array($this->guarantor_passport->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->guarantor_passport->Upload->Index < 0) ? $this->guarantor_passport->FldVar : substr($this->guarantor_passport->FldVar, 0, 1) . $this->guarantor_passport->Upload->Index . substr($this->guarantor_passport->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->guarantor_passport->TblVar) . $file)) {
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
							$file1 = ew_UploadFileNameEx($this->guarantor_passport->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->guarantor_passport->TblVar) . $file1) || file_exists($this->guarantor_passport->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->guarantor_passport->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->guarantor_passport->TblVar) . $file, ew_UploadTempPath($fldvar, $this->guarantor_passport->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->guarantor_passport->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->guarantor_passport->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->guarantor_passport->SetDbValueDef($rsnew, $this->guarantor_passport->Upload->FileName, NULL, FALSE);
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
				if ($this->applicant_passport->Visible && !$this->applicant_passport->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->applicant_passport->Upload->DbValue) ? array() : array($this->applicant_passport->Upload->DbValue);
					if (!ew_Empty($this->applicant_passport->Upload->FileName)) {
						$NewFiles = array($this->applicant_passport->Upload->FileName);
						$NewFiles2 = array($rsnew['applicant_passport']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->applicant_passport->Upload->Index < 0) ? $this->applicant_passport->FldVar : substr($this->applicant_passport->FldVar, 0, 1) . $this->applicant_passport->Upload->Index . substr($this->applicant_passport->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->applicant_passport->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->applicant_passport->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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
							@unlink($this->applicant_passport->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
				if ($this->guarantor_passport->Visible && !$this->guarantor_passport->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->guarantor_passport->Upload->DbValue) ? array() : array($this->guarantor_passport->Upload->DbValue);
					if (!ew_Empty($this->guarantor_passport->Upload->FileName)) {
						$NewFiles = array($this->guarantor_passport->Upload->FileName);
						$NewFiles2 = array($rsnew['guarantor_passport']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->guarantor_passport->Upload->Index < 0) ? $this->guarantor_passport->FldVar : substr($this->guarantor_passport->FldVar, 0, 1) . $this->guarantor_passport->Upload->Index . substr($this->guarantor_passport->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->guarantor_passport->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->guarantor_passport->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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
							@unlink($this->guarantor_passport->OldPhysicalUploadPath() . $OldFiles[$i]);
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

		// applicant_passport
		ew_CleanUploadTempPath($this->applicant_passport, $this->applicant_passport->Upload->Index);

		// guarantor_passport
		ew_CleanUploadTempPath($this->guarantor_passport, $this->guarantor_passport->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("loan_applicationlist.php"), "", $this->TableVar, TRUE);
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
		$pages->Add(3);
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_employee_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->employee_name, $sWhereWrk); // Call Lookup Selecting
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
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_repayment_period":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->repayment_period, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_guarantor_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id` AS `LinkFld`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`department_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->guarantor_department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_bank_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `banks_list`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->bank_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `loan_status`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_document_checklist":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `discription` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `document_checklist`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->document_checklist, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_recommended_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recommended_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_duration_approved":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->duration_approved, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
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
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld` FROM `loan_status`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_recommended_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->recommended_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recommended_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($loan_application_add)) $loan_application_add = new cloan_application_add();

// Page init
$loan_application_add->Page_Init();

// Page main
$loan_application_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$loan_application_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = floan_applicationadd = new ew_Form("floan_applicationadd", "add");

// Validate form
floan_applicationadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_date_initiated");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->date_initiated->FldCaption(), $loan_application->date_initiated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_initiated");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->date_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_refernce_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->refernce_id->FldCaption(), $loan_application->refernce_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_employee_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->employee_name->FldCaption(), $loan_application->employee_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->address->FldCaption(), $loan_application->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mobile");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->mobile->FldCaption(), $loan_application->mobile->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->department->FldCaption(), $loan_application->department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pension");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->pension->FldCaption(), $loan_application->pension->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_loan_amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->loan_amount->FldCaption(), $loan_application->loan_amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_loan_amount");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->loan_amount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_amount_inwords");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->amount_inwords->FldCaption(), $loan_application->amount_inwords->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_purpose");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->purpose->FldCaption(), $loan_application->purpose->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_repayment_period");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->repayment_period->FldCaption(), $loan_application->repayment_period->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_salary_permonth");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->salary_permonth->FldCaption(), $loan_application->salary_permonth->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_salary_permonth");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->salary_permonth->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_previous_loan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->previous_loan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_date_collected");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->date_collected->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_date_liquidated");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->date_liquidated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_balance_remaining");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->balance_remaining->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_applicant_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->applicant_date->FldCaption(), $loan_application->applicant_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_applicant_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->applicant_date->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_applicant_passport");
			elm = this.GetElements("fn_x" + infix + "_applicant_passport");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->applicant_passport->FldCaption(), $loan_application->applicant_passport->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_guarantor_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->guarantor_name->FldCaption(), $loan_application->guarantor_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_guarantor_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->guarantor_address->FldCaption(), $loan_application->guarantor_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_guarantor_mobile");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->guarantor_mobile->FldCaption(), $loan_application->guarantor_mobile->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_guarantor_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->guarantor_department->FldCaption(), $loan_application->guarantor_department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_account_no");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->account_no->FldCaption(), $loan_application->account_no->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->bank_name->FldCaption(), $loan_application->bank_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_employers_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->employers_name->FldCaption(), $loan_application->employers_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_employers_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->employers_address->FldCaption(), $loan_application->employers_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_employers_mobile");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->employers_mobile->FldCaption(), $loan_application->employers_mobile->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_guarantor_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->guarantor_date->FldCaption(), $loan_application->guarantor_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_guarantor_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->guarantor_date->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_guarantor_passport");
			elm = this.GetElements("fn_x" + infix + "_guarantor_passport");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->guarantor_passport->FldCaption(), $loan_application->guarantor_passport->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->status->FldCaption(), $loan_application->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->initiator_action->FldCaption(), $loan_application->initiator_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->initiator_comment->FldCaption(), $loan_application->initiator_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recommended_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->recommended_date->FldCaption(), $loan_application->recommended_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recommended_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->recommended_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_document_checklist[]");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->document_checklist->FldCaption(), $loan_application->document_checklist->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recommender_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->recommender_action->FldCaption(), $loan_application->recommender_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recommender_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->recommender_comment->FldCaption(), $loan_application->recommender_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recommended_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->recommended_by->FldCaption(), $loan_application->recommended_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_application_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->application_status->FldCaption(), $loan_application->application_status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approved_amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->approved_amount->FldCaption(), $loan_application->approved_amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approved_amount");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->approved_amount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_duration_approved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->duration_approved->FldCaption(), $loan_application->duration_approved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approval_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($loan_application->approval_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approval_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->approval_action->FldCaption(), $loan_application->approval_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approval_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->approval_comment->FldCaption(), $loan_application->approval_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $loan_application->approved_by->FldCaption(), $loan_application->approved_by->ReqErrMsg)) ?>");

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
floan_applicationadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
floan_applicationadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
floan_applicationadd.MultiPage = new ew_MultiPage("floan_applicationadd");

// Dynamic selection lists
floan_applicationadd.Lists["x_employee_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_applicationadd.Lists["x_employee_name"].Data = "<?php echo $loan_application_add->employee_name->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
floan_applicationadd.Lists["x_department"].Data = "<?php echo $loan_application_add->department->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_repayment_period"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"duration_months"};
floan_applicationadd.Lists["x_repayment_period"].Data = "<?php echo $loan_application_add->repayment_period->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_guarantor_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
floan_applicationadd.Lists["x_guarantor_department"].Data = "<?php echo $loan_application_add->guarantor_department->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_bank_name"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"banks_list"};
floan_applicationadd.Lists["x_bank_name"].Data = "<?php echo $loan_application_add->bank_name->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"loan_status"};
floan_applicationadd.Lists["x_status"].Data = "<?php echo $loan_application_add->status->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_application_add->status->LookupFilterQuery(TRUE, "add"))) ?>;
floan_applicationadd.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationadd.Lists["x_initiator_action"].Options = <?php echo json_encode($loan_application_add->initiator_action->Options()) ?>;
floan_applicationadd.Lists["x_document_checklist[]"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_discription","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"document_checklist"};
floan_applicationadd.Lists["x_document_checklist[]"].Data = "<?php echo $loan_application_add->document_checklist->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_recommender_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationadd.Lists["x_recommender_action"].Options = <?php echo json_encode($loan_application_add->recommender_action->Options()) ?>;
floan_applicationadd.Lists["x_recommended_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_applicationadd.Lists["x_recommended_by"].Data = "<?php echo $loan_application_add->recommended_by->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.AutoSuggests["x_recommended_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_application_add->recommended_by->LookupFilterQuery(TRUE, "add"))) ?>;
floan_applicationadd.Lists["x_application_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationadd.Lists["x_application_status"].Options = <?php echo json_encode($loan_application_add->application_status->Options()) ?>;
floan_applicationadd.Lists["x_duration_approved"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"duration_months"};
floan_applicationadd.Lists["x_duration_approved"].Data = "<?php echo $loan_application_add->duration_approved->LookupFilterQuery(FALSE, "add") ?>";
floan_applicationadd.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationadd.Lists["x_approval_action"].Options = <?php echo json_encode($loan_application_add->approval_action->Options()) ?>;
floan_applicationadd.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_applicationadd.Lists["x_approved_by"].Data = "<?php echo $loan_application_add->approved_by->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $loan_application_add->ShowPageHeader(); ?>
<?php
$loan_application_add->ShowMessage();
?>
<form name="floan_applicationadd" id="floan_applicationadd" class="<?php echo $loan_application_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($loan_application_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $loan_application_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="loan_application">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($loan_application_add->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="loan_application_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $loan_application_add->MultiPages->NavStyle() ?>">
		<li<?php echo $loan_application_add->MultiPages->TabStyle("1") ?>><a href="#tab_loan_application1" data-toggle="tab"><?php echo $loan_application->PageCaption(1) ?></a></li>
		<li<?php echo $loan_application_add->MultiPages->TabStyle("2") ?>><a href="#tab_loan_application2" data-toggle="tab"><?php echo $loan_application->PageCaption(2) ?></a></li>
		<li<?php echo $loan_application_add->MultiPages->TabStyle("3") ?>><a href="#tab_loan_application3" data-toggle="tab"><?php echo $loan_application->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $loan_application_add->MultiPages->PageStyle("1") ?>" id="tab_loan_application1"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($loan_application->date_initiated->Visible) { // date_initiated ?>
	<div id="r_date_initiated" class="form-group">
		<label id="elh_loan_application_date_initiated" for="x_date_initiated" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->date_initiated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->date_initiated->CellAttributes() ?>>
<span id="el_loan_application_date_initiated">
<input type="text" data-table="loan_application" data-field="x_date_initiated" data-page="1" name="x_date_initiated" id="x_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->date_initiated->getPlaceHolder()) ?>" value="<?php echo $loan_application->date_initiated->EditValue ?>"<?php echo $loan_application->date_initiated->EditAttributes() ?>>
</span>
<?php echo $loan_application->date_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->refernce_id->Visible) { // refernce_id ?>
	<div id="r_refernce_id" class="form-group">
		<label id="elh_loan_application_refernce_id" for="x_refernce_id" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->refernce_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->refernce_id->CellAttributes() ?>>
<span id="el_loan_application_refernce_id">
<input type="text" data-table="loan_application" data-field="x_refernce_id" data-page="1" name="x_refernce_id" id="x_refernce_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_application->refernce_id->getPlaceHolder()) ?>" value="<?php echo $loan_application->refernce_id->EditValue ?>"<?php echo $loan_application->refernce_id->EditAttributes() ?>>
</span>
<?php echo $loan_application->refernce_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->employee_name->Visible) { // employee_name ?>
	<div id="r_employee_name" class="form-group">
		<label id="elh_loan_application_employee_name" for="x_employee_name" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->employee_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->employee_name->CellAttributes() ?>>
<span id="el_loan_application_employee_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_employee_name"><?php echo (strval($loan_application->employee_name->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $loan_application->employee_name->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($loan_application->employee_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_employee_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($loan_application->employee_name->ReadOnly || $loan_application->employee_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="loan_application" data-field="x_employee_name" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $loan_application->employee_name->DisplayValueSeparatorAttribute() ?>" name="x_employee_name" id="x_employee_name" value="<?php echo $loan_application->employee_name->CurrentValue ?>"<?php echo $loan_application->employee_name->EditAttributes() ?>>
</span>
<?php echo $loan_application->employee_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_loan_application_address" for="x_address" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->address->CellAttributes() ?>>
<span id="el_loan_application_address">
<textarea data-table="loan_application" data-field="x_address" data-page="1" name="x_address" id="x_address" cols="30" rows="2" placeholder="<?php echo ew_HtmlEncode($loan_application->address->getPlaceHolder()) ?>"<?php echo $loan_application->address->EditAttributes() ?>><?php echo $loan_application->address->EditValue ?></textarea>
</span>
<?php echo $loan_application->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->mobile->Visible) { // mobile ?>
	<div id="r_mobile" class="form-group">
		<label id="elh_loan_application_mobile" for="x_mobile" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->mobile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->mobile->CellAttributes() ?>>
<span id="el_loan_application_mobile">
<input type="text" data-table="loan_application" data-field="x_mobile" data-page="1" name="x_mobile" id="x_mobile" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($loan_application->mobile->getPlaceHolder()) ?>" value="<?php echo $loan_application->mobile->EditValue ?>"<?php echo $loan_application->mobile->EditAttributes() ?>>
</span>
<?php echo $loan_application->mobile->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_loan_application_department" for="x_department" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->department->CellAttributes() ?>>
<span id="el_loan_application_department">
<select data-table="loan_application" data-field="x_department" data-page="1" data-value-separator="<?php echo $loan_application->department->DisplayValueSeparatorAttribute() ?>" id="x_department" name="x_department"<?php echo $loan_application->department->EditAttributes() ?>>
<?php echo $loan_application->department->SelectOptionListHtml("x_department") ?>
</select>
</span>
<?php echo $loan_application->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->pension->Visible) { // pension ?>
	<div id="r_pension" class="form-group">
		<label id="elh_loan_application_pension" for="x_pension" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->pension->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->pension->CellAttributes() ?>>
<span id="el_loan_application_pension">
<input type="text" data-table="loan_application" data-field="x_pension" data-page="1" name="x_pension" id="x_pension" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_application->pension->getPlaceHolder()) ?>" value="<?php echo $loan_application->pension->EditValue ?>"<?php echo $loan_application->pension->EditAttributes() ?>>
</span>
<?php echo $loan_application->pension->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->loan_amount->Visible) { // loan_amount ?>
	<div id="r_loan_amount" class="form-group">
		<label id="elh_loan_application_loan_amount" for="x_loan_amount" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->loan_amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->loan_amount->CellAttributes() ?>>
<span id="el_loan_application_loan_amount">
<input type="text" data-table="loan_application" data-field="x_loan_amount" data-page="1" name="x_loan_amount" id="x_loan_amount" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->loan_amount->getPlaceHolder()) ?>" value="<?php echo $loan_application->loan_amount->EditValue ?>"<?php echo $loan_application->loan_amount->EditAttributes() ?>>
</span>
<?php echo $loan_application->loan_amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->amount_inwords->Visible) { // amount_inwords ?>
	<div id="r_amount_inwords" class="form-group">
		<label id="elh_loan_application_amount_inwords" for="x_amount_inwords" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->amount_inwords->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->amount_inwords->CellAttributes() ?>>
<span id="el_loan_application_amount_inwords">
<textarea data-table="loan_application" data-field="x_amount_inwords" data-page="1" name="x_amount_inwords" id="x_amount_inwords" cols="30" rows="2" placeholder="<?php echo ew_HtmlEncode($loan_application->amount_inwords->getPlaceHolder()) ?>"<?php echo $loan_application->amount_inwords->EditAttributes() ?>><?php echo $loan_application->amount_inwords->EditValue ?></textarea>
</span>
<?php echo $loan_application->amount_inwords->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->purpose->Visible) { // purpose ?>
	<div id="r_purpose" class="form-group">
		<label id="elh_loan_application_purpose" for="x_purpose" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->purpose->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->purpose->CellAttributes() ?>>
<span id="el_loan_application_purpose">
<textarea data-table="loan_application" data-field="x_purpose" data-page="1" name="x_purpose" id="x_purpose" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($loan_application->purpose->getPlaceHolder()) ?>"<?php echo $loan_application->purpose->EditAttributes() ?>><?php echo $loan_application->purpose->EditValue ?></textarea>
</span>
<?php echo $loan_application->purpose->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->repayment_period->Visible) { // repayment_period ?>
	<div id="r_repayment_period" class="form-group">
		<label id="elh_loan_application_repayment_period" for="x_repayment_period" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->repayment_period->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->repayment_period->CellAttributes() ?>>
<span id="el_loan_application_repayment_period">
<select data-table="loan_application" data-field="x_repayment_period" data-page="1" data-value-separator="<?php echo $loan_application->repayment_period->DisplayValueSeparatorAttribute() ?>" id="x_repayment_period" name="x_repayment_period"<?php echo $loan_application->repayment_period->EditAttributes() ?>>
<?php echo $loan_application->repayment_period->SelectOptionListHtml("x_repayment_period") ?>
</select>
</span>
<?php echo $loan_application->repayment_period->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->salary_permonth->Visible) { // salary_permonth ?>
	<div id="r_salary_permonth" class="form-group">
		<label id="elh_loan_application_salary_permonth" for="x_salary_permonth" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->salary_permonth->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->salary_permonth->CellAttributes() ?>>
<span id="el_loan_application_salary_permonth">
<input type="text" data-table="loan_application" data-field="x_salary_permonth" data-page="1" name="x_salary_permonth" id="x_salary_permonth" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->salary_permonth->getPlaceHolder()) ?>" value="<?php echo $loan_application->salary_permonth->EditValue ?>"<?php echo $loan_application->salary_permonth->EditAttributes() ?>>
</span>
<?php echo $loan_application->salary_permonth->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->previous_loan->Visible) { // previous_loan ?>
	<div id="r_previous_loan" class="form-group">
		<label id="elh_loan_application_previous_loan" for="x_previous_loan" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->previous_loan->FldCaption() ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->previous_loan->CellAttributes() ?>>
<span id="el_loan_application_previous_loan">
<input type="text" data-table="loan_application" data-field="x_previous_loan" data-page="1" name="x_previous_loan" id="x_previous_loan" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->previous_loan->getPlaceHolder()) ?>" value="<?php echo $loan_application->previous_loan->EditValue ?>"<?php echo $loan_application->previous_loan->EditAttributes() ?>>
</span>
<?php echo $loan_application->previous_loan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->date_collected->Visible) { // date_collected ?>
	<div id="r_date_collected" class="form-group">
		<label id="elh_loan_application_date_collected" for="x_date_collected" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->date_collected->FldCaption() ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->date_collected->CellAttributes() ?>>
<span id="el_loan_application_date_collected">
<input type="text" data-table="loan_application" data-field="x_date_collected" data-page="1" name="x_date_collected" id="x_date_collected" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->date_collected->getPlaceHolder()) ?>" value="<?php echo $loan_application->date_collected->EditValue ?>"<?php echo $loan_application->date_collected->EditAttributes() ?>>
<?php if (!$loan_application->date_collected->ReadOnly && !$loan_application->date_collected->Disabled && !isset($loan_application->date_collected->EditAttrs["readonly"]) && !isset($loan_application->date_collected->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_applicationadd", "x_date_collected", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $loan_application->date_collected->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->date_liquidated->Visible) { // date_liquidated ?>
	<div id="r_date_liquidated" class="form-group">
		<label id="elh_loan_application_date_liquidated" for="x_date_liquidated" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->date_liquidated->FldCaption() ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->date_liquidated->CellAttributes() ?>>
<span id="el_loan_application_date_liquidated">
<input type="text" data-table="loan_application" data-field="x_date_liquidated" data-page="1" name="x_date_liquidated" id="x_date_liquidated" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->date_liquidated->getPlaceHolder()) ?>" value="<?php echo $loan_application->date_liquidated->EditValue ?>"<?php echo $loan_application->date_liquidated->EditAttributes() ?>>
<?php if (!$loan_application->date_liquidated->ReadOnly && !$loan_application->date_liquidated->Disabled && !isset($loan_application->date_liquidated->EditAttrs["readonly"]) && !isset($loan_application->date_liquidated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_applicationadd", "x_date_liquidated", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $loan_application->date_liquidated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->balance_remaining->Visible) { // balance_remaining ?>
	<div id="r_balance_remaining" class="form-group">
		<label id="elh_loan_application_balance_remaining" for="x_balance_remaining" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->balance_remaining->FldCaption() ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->balance_remaining->CellAttributes() ?>>
<span id="el_loan_application_balance_remaining">
<input type="text" data-table="loan_application" data-field="x_balance_remaining" data-page="1" name="x_balance_remaining" id="x_balance_remaining" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->balance_remaining->getPlaceHolder()) ?>" value="<?php echo $loan_application->balance_remaining->EditValue ?>"<?php echo $loan_application->balance_remaining->EditAttributes() ?>>
</span>
<?php echo $loan_application->balance_remaining->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->applicant_date->Visible) { // applicant_date ?>
	<div id="r_applicant_date" class="form-group">
		<label id="elh_loan_application_applicant_date" for="x_applicant_date" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->applicant_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->applicant_date->CellAttributes() ?>>
<span id="el_loan_application_applicant_date">
<input type="text" data-table="loan_application" data-field="x_applicant_date" data-page="1" data-format="17" name="x_applicant_date" id="x_applicant_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->applicant_date->getPlaceHolder()) ?>" value="<?php echo $loan_application->applicant_date->EditValue ?>"<?php echo $loan_application->applicant_date->EditAttributes() ?>>
</span>
<?php echo $loan_application->applicant_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->applicant_passport->Visible) { // applicant_passport ?>
	<div id="r_applicant_passport" class="form-group">
		<label id="elh_loan_application_applicant_passport" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->applicant_passport->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->applicant_passport->CellAttributes() ?>>
<span id="el_loan_application_applicant_passport">
<div id="fd_x_applicant_passport">
<span title="<?php echo $loan_application->applicant_passport->FldTitle() ? $loan_application->applicant_passport->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($loan_application->applicant_passport->ReadOnly || $loan_application->applicant_passport->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="loan_application" data-field="x_applicant_passport" data-page="1" name="x_applicant_passport" id="x_applicant_passport"<?php echo $loan_application->applicant_passport->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_applicant_passport" id= "fn_x_applicant_passport" value="<?php echo $loan_application->applicant_passport->Upload->FileName ?>">
<input type="hidden" name="fa_x_applicant_passport" id= "fa_x_applicant_passport" value="0">
<input type="hidden" name="fs_x_applicant_passport" id= "fs_x_applicant_passport" value="65535">
<input type="hidden" name="fx_x_applicant_passport" id= "fx_x_applicant_passport" value="<?php echo $loan_application->applicant_passport->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_applicant_passport" id= "fm_x_applicant_passport" value="<?php echo $loan_application->applicant_passport->UploadMaxFileSize ?>">
</div>
<table id="ft_x_applicant_passport" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $loan_application->applicant_passport->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $loan_application_add->MultiPages->PageStyle("2") ?>" id="tab_loan_application2"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($loan_application->guarantor_name->Visible) { // guarantor_name ?>
	<div id="r_guarantor_name" class="form-group">
		<label id="elh_loan_application_guarantor_name" for="x_guarantor_name" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->guarantor_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->guarantor_name->CellAttributes() ?>>
<span id="el_loan_application_guarantor_name">
<input type="text" data-table="loan_application" data-field="x_guarantor_name" data-page="2" name="x_guarantor_name" id="x_guarantor_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_application->guarantor_name->getPlaceHolder()) ?>" value="<?php echo $loan_application->guarantor_name->EditValue ?>"<?php echo $loan_application->guarantor_name->EditAttributes() ?>>
</span>
<?php echo $loan_application->guarantor_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->guarantor_address->Visible) { // guarantor_address ?>
	<div id="r_guarantor_address" class="form-group">
		<label id="elh_loan_application_guarantor_address" for="x_guarantor_address" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->guarantor_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->guarantor_address->CellAttributes() ?>>
<span id="el_loan_application_guarantor_address">
<input type="text" data-table="loan_application" data-field="x_guarantor_address" data-page="2" name="x_guarantor_address" id="x_guarantor_address" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($loan_application->guarantor_address->getPlaceHolder()) ?>" value="<?php echo $loan_application->guarantor_address->EditValue ?>"<?php echo $loan_application->guarantor_address->EditAttributes() ?>>
</span>
<?php echo $loan_application->guarantor_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->guarantor_mobile->Visible) { // guarantor_mobile ?>
	<div id="r_guarantor_mobile" class="form-group">
		<label id="elh_loan_application_guarantor_mobile" for="x_guarantor_mobile" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->guarantor_mobile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->guarantor_mobile->CellAttributes() ?>>
<span id="el_loan_application_guarantor_mobile">
<input type="text" data-table="loan_application" data-field="x_guarantor_mobile" data-page="2" name="x_guarantor_mobile" id="x_guarantor_mobile" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($loan_application->guarantor_mobile->getPlaceHolder()) ?>" value="<?php echo $loan_application->guarantor_mobile->EditValue ?>"<?php echo $loan_application->guarantor_mobile->EditAttributes() ?>>
</span>
<?php echo $loan_application->guarantor_mobile->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->guarantor_department->Visible) { // guarantor_department ?>
	<div id="r_guarantor_department" class="form-group">
		<label id="elh_loan_application_guarantor_department" for="x_guarantor_department" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->guarantor_department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->guarantor_department->CellAttributes() ?>>
<span id="el_loan_application_guarantor_department">
<select data-table="loan_application" data-field="x_guarantor_department" data-page="2" data-value-separator="<?php echo $loan_application->guarantor_department->DisplayValueSeparatorAttribute() ?>" id="x_guarantor_department" name="x_guarantor_department"<?php echo $loan_application->guarantor_department->EditAttributes() ?>>
<?php echo $loan_application->guarantor_department->SelectOptionListHtml("x_guarantor_department") ?>
</select>
</span>
<?php echo $loan_application->guarantor_department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->account_no->Visible) { // account_no ?>
	<div id="r_account_no" class="form-group">
		<label id="elh_loan_application_account_no" for="x_account_no" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->account_no->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->account_no->CellAttributes() ?>>
<span id="el_loan_application_account_no">
<input type="text" data-table="loan_application" data-field="x_account_no" data-page="2" name="x_account_no" id="x_account_no" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($loan_application->account_no->getPlaceHolder()) ?>" value="<?php echo $loan_application->account_no->EditValue ?>"<?php echo $loan_application->account_no->EditAttributes() ?>>
</span>
<?php echo $loan_application->account_no->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->bank_name->Visible) { // bank_name ?>
	<div id="r_bank_name" class="form-group">
		<label id="elh_loan_application_bank_name" for="x_bank_name" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->bank_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->bank_name->CellAttributes() ?>>
<span id="el_loan_application_bank_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_bank_name"><?php echo (strval($loan_application->bank_name->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $loan_application->bank_name->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($loan_application->bank_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_bank_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($loan_application->bank_name->ReadOnly || $loan_application->bank_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="loan_application" data-field="x_bank_name" data-page="2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $loan_application->bank_name->DisplayValueSeparatorAttribute() ?>" name="x_bank_name" id="x_bank_name" value="<?php echo $loan_application->bank_name->CurrentValue ?>"<?php echo $loan_application->bank_name->EditAttributes() ?>>
</span>
<?php echo $loan_application->bank_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->employers_name->Visible) { // employers_name ?>
	<div id="r_employers_name" class="form-group">
		<label id="elh_loan_application_employers_name" for="x_employers_name" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->employers_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->employers_name->CellAttributes() ?>>
<span id="el_loan_application_employers_name">
<input type="text" data-table="loan_application" data-field="x_employers_name" data-page="2" name="x_employers_name" id="x_employers_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_application->employers_name->getPlaceHolder()) ?>" value="<?php echo $loan_application->employers_name->EditValue ?>"<?php echo $loan_application->employers_name->EditAttributes() ?>>
</span>
<?php echo $loan_application->employers_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->employers_address->Visible) { // employers_address ?>
	<div id="r_employers_address" class="form-group">
		<label id="elh_loan_application_employers_address" for="x_employers_address" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->employers_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->employers_address->CellAttributes() ?>>
<span id="el_loan_application_employers_address">
<textarea data-table="loan_application" data-field="x_employers_address" data-page="2" name="x_employers_address" id="x_employers_address" cols="30" rows="2" placeholder="<?php echo ew_HtmlEncode($loan_application->employers_address->getPlaceHolder()) ?>"<?php echo $loan_application->employers_address->EditAttributes() ?>><?php echo $loan_application->employers_address->EditValue ?></textarea>
</span>
<?php echo $loan_application->employers_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->employers_mobile->Visible) { // employers_mobile ?>
	<div id="r_employers_mobile" class="form-group">
		<label id="elh_loan_application_employers_mobile" for="x_employers_mobile" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->employers_mobile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->employers_mobile->CellAttributes() ?>>
<span id="el_loan_application_employers_mobile">
<input type="text" data-table="loan_application" data-field="x_employers_mobile" data-page="2" name="x_employers_mobile" id="x_employers_mobile" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($loan_application->employers_mobile->getPlaceHolder()) ?>" value="<?php echo $loan_application->employers_mobile->EditValue ?>"<?php echo $loan_application->employers_mobile->EditAttributes() ?>>
</span>
<?php echo $loan_application->employers_mobile->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->guarantor_date->Visible) { // guarantor_date ?>
	<div id="r_guarantor_date" class="form-group">
		<label id="elh_loan_application_guarantor_date" for="x_guarantor_date" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->guarantor_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->guarantor_date->CellAttributes() ?>>
<span id="el_loan_application_guarantor_date">
<input type="text" data-table="loan_application" data-field="x_guarantor_date" data-page="2" data-format="17" name="x_guarantor_date" id="x_guarantor_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->guarantor_date->getPlaceHolder()) ?>" value="<?php echo $loan_application->guarantor_date->EditValue ?>"<?php echo $loan_application->guarantor_date->EditAttributes() ?>>
</span>
<?php echo $loan_application->guarantor_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->guarantor_passport->Visible) { // guarantor_passport ?>
	<div id="r_guarantor_passport" class="form-group">
		<label id="elh_loan_application_guarantor_passport" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->guarantor_passport->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->guarantor_passport->CellAttributes() ?>>
<span id="el_loan_application_guarantor_passport">
<div id="fd_x_guarantor_passport">
<span title="<?php echo $loan_application->guarantor_passport->FldTitle() ? $loan_application->guarantor_passport->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($loan_application->guarantor_passport->ReadOnly || $loan_application->guarantor_passport->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="loan_application" data-field="x_guarantor_passport" data-page="2" name="x_guarantor_passport" id="x_guarantor_passport"<?php echo $loan_application->guarantor_passport->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_guarantor_passport" id= "fn_x_guarantor_passport" value="<?php echo $loan_application->guarantor_passport->Upload->FileName ?>">
<input type="hidden" name="fa_x_guarantor_passport" id= "fa_x_guarantor_passport" value="0">
<input type="hidden" name="fs_x_guarantor_passport" id= "fs_x_guarantor_passport" value="65535">
<input type="hidden" name="fx_x_guarantor_passport" id= "fx_x_guarantor_passport" value="<?php echo $loan_application->guarantor_passport->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_guarantor_passport" id= "fm_x_guarantor_passport" value="<?php echo $loan_application->guarantor_passport->UploadMaxFileSize ?>">
</div>
<table id="ft_x_guarantor_passport" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $loan_application->guarantor_passport->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $loan_application_add->MultiPages->PageStyle("3") ?>" id="tab_loan_application3"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($loan_application->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_loan_application_status" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->status->CellAttributes() ?>>
<span id="el_loan_application_status">
<?php
$wrkonchange = trim(" " . @$loan_application->status->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$loan_application->status->EditAttrs["onchange"] = "";
?>
<span id="as_x_status" style="white-space: nowrap; z-index: 8690">
	<input type="text" name="sv_x_status" id="sv_x_status" value="<?php echo $loan_application->status->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->status->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($loan_application->status->getPlaceHolder()) ?>"<?php echo $loan_application->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="loan_application" data-field="x_status" data-page="3" data-value-separator="<?php echo $loan_application->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($loan_application->status->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
floan_applicationadd.CreateAutoSuggest({"id":"x_status","forceSelect":false});
</script>
</span>
<?php echo $loan_application->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_loan_application_initiator_action" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->initiator_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->initiator_action->CellAttributes() ?>>
<span id="el_loan_application_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="loan_application" data-field="x_initiator_action" data-page="3" data-value-separator="<?php echo $loan_application->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $loan_application->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_application->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action", 3) ?>
</div></div>
</span>
<?php echo $loan_application->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_loan_application_initiator_comment" for="x_initiator_comment" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->initiator_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->initiator_comment->CellAttributes() ?>>
<span id="el_loan_application_initiator_comment">
<textarea data-table="loan_application" data-field="x_initiator_comment" data-page="3" name="x_initiator_comment" id="x_initiator_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($loan_application->initiator_comment->getPlaceHolder()) ?>"<?php echo $loan_application->initiator_comment->EditAttributes() ?>><?php echo $loan_application->initiator_comment->EditValue ?></textarea>
</span>
<?php echo $loan_application->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->recommended_date->Visible) { // recommended_date ?>
	<div id="r_recommended_date" class="form-group">
		<label id="elh_loan_application_recommended_date" for="x_recommended_date" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->recommended_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->recommended_date->CellAttributes() ?>>
<span id="el_loan_application_recommended_date">
<input type="text" data-table="loan_application" data-field="x_recommended_date" data-page="3" data-format="14" name="x_recommended_date" id="x_recommended_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->recommended_date->getPlaceHolder()) ?>" value="<?php echo $loan_application->recommended_date->EditValue ?>"<?php echo $loan_application->recommended_date->EditAttributes() ?>>
</span>
<?php echo $loan_application->recommended_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->document_checklist->Visible) { // document_checklist ?>
	<div id="r_document_checklist" class="form-group">
		<label id="elh_loan_application_document_checklist" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->document_checklist->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->document_checklist->CellAttributes() ?>>
<span id="el_loan_application_document_checklist">
<div id="tp_x_document_checklist" class="ewTemplate"><input type="checkbox" data-table="loan_application" data-field="x_document_checklist" data-page="3" data-value-separator="<?php echo $loan_application->document_checklist->DisplayValueSeparatorAttribute() ?>" name="x_document_checklist[]" id="x_document_checklist[]" value="{value}"<?php echo $loan_application->document_checklist->EditAttributes() ?>></div>
<div id="dsl_x_document_checklist" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_application->document_checklist->CheckBoxListHtml(FALSE, "x_document_checklist[]", 3) ?>
</div></div>
</span>
<?php echo $loan_application->document_checklist->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->recommender_action->Visible) { // recommender_action ?>
	<div id="r_recommender_action" class="form-group">
		<label id="elh_loan_application_recommender_action" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->recommender_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->recommender_action->CellAttributes() ?>>
<span id="el_loan_application_recommender_action">
<div id="tp_x_recommender_action" class="ewTemplate"><input type="radio" data-table="loan_application" data-field="x_recommender_action" data-page="3" data-value-separator="<?php echo $loan_application->recommender_action->DisplayValueSeparatorAttribute() ?>" name="x_recommender_action" id="x_recommender_action" value="{value}"<?php echo $loan_application->recommender_action->EditAttributes() ?>></div>
<div id="dsl_x_recommender_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_application->recommender_action->RadioButtonListHtml(FALSE, "x_recommender_action", 3) ?>
</div></div>
</span>
<?php echo $loan_application->recommender_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->recommender_comment->Visible) { // recommender_comment ?>
	<div id="r_recommender_comment" class="form-group">
		<label id="elh_loan_application_recommender_comment" for="x_recommender_comment" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->recommender_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->recommender_comment->CellAttributes() ?>>
<span id="el_loan_application_recommender_comment">
<textarea data-table="loan_application" data-field="x_recommender_comment" data-page="3" name="x_recommender_comment" id="x_recommender_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($loan_application->recommender_comment->getPlaceHolder()) ?>"<?php echo $loan_application->recommender_comment->EditAttributes() ?>><?php echo $loan_application->recommender_comment->EditValue ?></textarea>
</span>
<?php echo $loan_application->recommender_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->recommended_by->Visible) { // recommended_by ?>
	<div id="r_recommended_by" class="form-group">
		<label id="elh_loan_application_recommended_by" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->recommended_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->recommended_by->CellAttributes() ?>>
<span id="el_loan_application_recommended_by">
<?php
$wrkonchange = trim(" " . @$loan_application->recommended_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$loan_application->recommended_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_recommended_by" style="white-space: nowrap; z-index: 8620">
	<input type="text" name="sv_x_recommended_by" id="sv_x_recommended_by" value="<?php echo $loan_application->recommended_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->recommended_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($loan_application->recommended_by->getPlaceHolder()) ?>"<?php echo $loan_application->recommended_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="loan_application" data-field="x_recommended_by" data-page="3" data-value-separator="<?php echo $loan_application->recommended_by->DisplayValueSeparatorAttribute() ?>" name="x_recommended_by" id="x_recommended_by" value="<?php echo ew_HtmlEncode($loan_application->recommended_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
floan_applicationadd.CreateAutoSuggest({"id":"x_recommended_by","forceSelect":false});
</script>
</span>
<?php echo $loan_application->recommended_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->application_status->Visible) { // application_status ?>
	<div id="r_application_status" class="form-group">
		<label id="elh_loan_application_application_status" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->application_status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->application_status->CellAttributes() ?>>
<span id="el_loan_application_application_status">
<div id="tp_x_application_status" class="ewTemplate"><input type="radio" data-table="loan_application" data-field="x_application_status" data-page="3" data-value-separator="<?php echo $loan_application->application_status->DisplayValueSeparatorAttribute() ?>" name="x_application_status" id="x_application_status" value="{value}"<?php echo $loan_application->application_status->EditAttributes() ?>></div>
<div id="dsl_x_application_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_application->application_status->RadioButtonListHtml(FALSE, "x_application_status", 3) ?>
</div></div>
</span>
<?php echo $loan_application->application_status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->approved_amount->Visible) { // approved_amount ?>
	<div id="r_approved_amount" class="form-group">
		<label id="elh_loan_application_approved_amount" for="x_approved_amount" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->approved_amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->approved_amount->CellAttributes() ?>>
<span id="el_loan_application_approved_amount">
<input type="text" data-table="loan_application" data-field="x_approved_amount" data-page="3" name="x_approved_amount" id="x_approved_amount" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->approved_amount->getPlaceHolder()) ?>" value="<?php echo $loan_application->approved_amount->EditValue ?>"<?php echo $loan_application->approved_amount->EditAttributes() ?>>
</span>
<?php echo $loan_application->approved_amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->duration_approved->Visible) { // duration_approved ?>
	<div id="r_duration_approved" class="form-group">
		<label id="elh_loan_application_duration_approved" for="x_duration_approved" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->duration_approved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->duration_approved->CellAttributes() ?>>
<span id="el_loan_application_duration_approved">
<select data-table="loan_application" data-field="x_duration_approved" data-page="3" data-value-separator="<?php echo $loan_application->duration_approved->DisplayValueSeparatorAttribute() ?>" id="x_duration_approved" name="x_duration_approved"<?php echo $loan_application->duration_approved->EditAttributes() ?>>
<?php echo $loan_application->duration_approved->SelectOptionListHtml("x_duration_approved") ?>
</select>
</span>
<?php echo $loan_application->duration_approved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->approval_date->Visible) { // approval_date ?>
	<div id="r_approval_date" class="form-group">
		<label id="elh_loan_application_approval_date" for="x_approval_date" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->approval_date->FldCaption() ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->approval_date->CellAttributes() ?>>
<span id="el_loan_application_approval_date">
<input type="text" data-table="loan_application" data-field="x_approval_date" data-page="3" data-format="17" name="x_approval_date" id="x_approval_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_application->approval_date->getPlaceHolder()) ?>" value="<?php echo $loan_application->approval_date->EditValue ?>"<?php echo $loan_application->approval_date->EditAttributes() ?>>
<?php if (!$loan_application->approval_date->ReadOnly && !$loan_application->approval_date->Disabled && !isset($loan_application->approval_date->EditAttrs["readonly"]) && !isset($loan_application->approval_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_applicationadd", "x_approval_date", {"ignoreReadonly":true,"useCurrent":false,"format":17});
</script>
<?php } ?>
</span>
<?php echo $loan_application->approval_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->approval_action->Visible) { // approval_action ?>
	<div id="r_approval_action" class="form-group">
		<label id="elh_loan_application_approval_action" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->approval_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->approval_action->CellAttributes() ?>>
<span id="el_loan_application_approval_action">
<div id="tp_x_approval_action" class="ewTemplate"><input type="radio" data-table="loan_application" data-field="x_approval_action" data-page="3" data-value-separator="<?php echo $loan_application->approval_action->DisplayValueSeparatorAttribute() ?>" name="x_approval_action" id="x_approval_action" value="{value}"<?php echo $loan_application->approval_action->EditAttributes() ?>></div>
<div id="dsl_x_approval_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_application->approval_action->RadioButtonListHtml(FALSE, "x_approval_action", 3) ?>
</div></div>
</span>
<?php echo $loan_application->approval_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->approval_comment->Visible) { // approval_comment ?>
	<div id="r_approval_comment" class="form-group">
		<label id="elh_loan_application_approval_comment" for="x_approval_comment" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->approval_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->approval_comment->CellAttributes() ?>>
<span id="el_loan_application_approval_comment">
<textarea data-table="loan_application" data-field="x_approval_comment" data-page="3" name="x_approval_comment" id="x_approval_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($loan_application->approval_comment->getPlaceHolder()) ?>"<?php echo $loan_application->approval_comment->EditAttributes() ?>><?php echo $loan_application->approval_comment->EditValue ?></textarea>
</span>
<?php echo $loan_application->approval_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($loan_application->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_loan_application_approved_by" for="x_approved_by" class="<?php echo $loan_application_add->LeftColumnClass ?>"><?php echo $loan_application->approved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $loan_application_add->RightColumnClass ?>"><div<?php echo $loan_application->approved_by->CellAttributes() ?>>
<span id="el_loan_application_approved_by">
<select data-table="loan_application" data-field="x_approved_by" data-page="3" data-value-separator="<?php echo $loan_application->approved_by->DisplayValueSeparatorAttribute() ?>" id="x_approved_by" name="x_approved_by"<?php echo $loan_application->approved_by->EditAttributes() ?>>
<?php echo $loan_application->approved_by->SelectOptionListHtml("x_approved_by") ?>
</select>
</span>
<?php echo $loan_application->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$loan_application_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $loan_application_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $loan_application_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
floan_applicationadd.Init();
</script>
<?php
$loan_application_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('#x_status').attr('readonly',true);
$('#x_recommended_date').attr('readonly',true);
$('#x_approval_date').attr('readonly',true);
$("#r_approved_by").hide();
$("#r_recommended_by").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$loan_application_add->Page_Terminate();
?>
