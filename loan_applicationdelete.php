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

$loan_application_delete = NULL; // Initialize page object first

class cloan_application_delete extends cloan_application {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'loan_application';

	// Page object name
	var $PageObjName = 'loan_application_delete';

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
			define("EW_PAGE_ID", 'delete');

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
				$this->Page_Terminate(ew_GetUrl("loan_applicationlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->date_initiated->SetVisibility();
		$this->refernce_id->SetVisibility();
		$this->employee_name->SetVisibility();
		$this->address->SetVisibility();
		$this->mobile->SetVisibility();
		$this->department->SetVisibility();
		$this->loan_amount->SetVisibility();
		$this->amount_inwords->SetVisibility();
		$this->repayment_period->SetVisibility();
		$this->salary_permonth->SetVisibility();
		$this->previous_loan->SetVisibility();
		$this->date_collected->SetVisibility();
		$this->date_liquidated->SetVisibility();
		$this->balance_remaining->SetVisibility();
		$this->applicant_date->SetVisibility();
		$this->guarantor_name->SetVisibility();
		$this->guarantor_address->SetVisibility();
		$this->guarantor_mobile->SetVisibility();
		$this->status->SetVisibility();
		$this->application_status->SetVisibility();
		$this->pension->SetVisibility();

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
			$this->Page_Terminate("loan_applicationlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in loan_application class, loan_applicationinfo.php

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
				$this->Page_Terminate("loan_applicationlist.php"); // Return to list
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
		$this->code->setDbValue($row['code']);
		$this->date_initiated->setDbValue($row['date_initiated']);
		$this->refernce_id->setDbValue($row['refernce_id']);
		$this->employee_name->setDbValue($row['employee_name']);
		$this->address->setDbValue($row['address']);
		$this->mobile->setDbValue($row['mobile']);
		$this->department->setDbValue($row['department']);
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
		$this->pension->setDbValue($row['pension']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['date_initiated'] = NULL;
		$row['refernce_id'] = NULL;
		$row['employee_name'] = NULL;
		$row['address'] = NULL;
		$row['mobile'] = NULL;
		$row['department'] = NULL;
		$row['loan_amount'] = NULL;
		$row['amount_inwords'] = NULL;
		$row['purpose'] = NULL;
		$row['repayment_period'] = NULL;
		$row['salary_permonth'] = NULL;
		$row['previous_loan'] = NULL;
		$row['date_collected'] = NULL;
		$row['date_liquidated'] = NULL;
		$row['balance_remaining'] = NULL;
		$row['applicant_date'] = NULL;
		$row['applicant_passport'] = NULL;
		$row['guarantor_name'] = NULL;
		$row['guarantor_address'] = NULL;
		$row['guarantor_mobile'] = NULL;
		$row['guarantor_department'] = NULL;
		$row['account_no'] = NULL;
		$row['bank_name'] = NULL;
		$row['employers_name'] = NULL;
		$row['employers_address'] = NULL;
		$row['employers_mobile'] = NULL;
		$row['guarantor_date'] = NULL;
		$row['guarantor_passport'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['recommended_date'] = NULL;
		$row['document_checklist'] = NULL;
		$row['recommender_action'] = NULL;
		$row['recommender_comment'] = NULL;
		$row['recommended_by'] = NULL;
		$row['application_status'] = NULL;
		$row['approved_amount'] = NULL;
		$row['duration_approved'] = NULL;
		$row['approval_date'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['pension'] = NULL;
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
		$this->pension->DbValue = $row['pension'];
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
		// pension

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

		// loan_amount
		$this->loan_amount->ViewValue = $this->loan_amount->CurrentValue;
		$this->loan_amount->ViewValue = ew_FormatNumber($this->loan_amount->ViewValue, 0, -2, -2, -2);
		$this->loan_amount->ViewCustomAttributes = "";

		// amount_inwords
		$this->amount_inwords->ViewValue = $this->amount_inwords->CurrentValue;
		$this->amount_inwords->ViewCustomAttributes = "";

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

		// pension
		$this->pension->ViewValue = $this->pension->CurrentValue;
		$this->pension->ViewCustomAttributes = "";

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

			// loan_amount
			$this->loan_amount->LinkCustomAttributes = "";
			$this->loan_amount->HrefValue = "";
			$this->loan_amount->TooltipValue = "";

			// amount_inwords
			$this->amount_inwords->LinkCustomAttributes = "";
			$this->amount_inwords->HrefValue = "";
			$this->amount_inwords->TooltipValue = "";

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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// application_status
			$this->application_status->LinkCustomAttributes = "";
			$this->application_status->HrefValue = "";
			$this->application_status->TooltipValue = "";

			// pension
			$this->pension->LinkCustomAttributes = "";
			$this->pension->HrefValue = "";
			$this->pension->TooltipValue = "";
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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
				$sThisKey .= $row['code'];

				// Delete old files
				$this->LoadDbValues($row);
				$OldFiles = ew_Empty($row['applicant_passport']) ? array() : array($row['applicant_passport']);
				$OldFileCount = count($OldFiles);
				for ($i = 0; $i < $OldFileCount; $i++) {
					if (file_exists($this->applicant_passport->OldPhysicalUploadPath() . $OldFiles[$i]))
						@unlink($this->applicant_passport->OldPhysicalUploadPath() . $OldFiles[$i]);
				}
				$OldFiles = ew_Empty($row['guarantor_passport']) ? array() : array($row['guarantor_passport']);
				$OldFileCount = count($OldFiles);
				for ($i = 0; $i < $OldFileCount; $i++) {
					if (file_exists($this->guarantor_passport->OldPhysicalUploadPath() . $OldFiles[$i]))
						@unlink($this->guarantor_passport->OldPhysicalUploadPath() . $OldFiles[$i]);
				}
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
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("loan_applicationlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($loan_application_delete)) $loan_application_delete = new cloan_application_delete();

// Page init
$loan_application_delete->Page_Init();

// Page main
$loan_application_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$loan_application_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = floan_applicationdelete = new ew_Form("floan_applicationdelete", "delete");

// Form_CustomValidate event
floan_applicationdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
floan_applicationdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
floan_applicationdelete.Lists["x_employee_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_applicationdelete.Lists["x_employee_name"].Data = "<?php echo $loan_application_delete->employee_name->LookupFilterQuery(FALSE, "delete") ?>";
floan_applicationdelete.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
floan_applicationdelete.Lists["x_department"].Data = "<?php echo $loan_application_delete->department->LookupFilterQuery(FALSE, "delete") ?>";
floan_applicationdelete.Lists["x_repayment_period"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"duration_months"};
floan_applicationdelete.Lists["x_repayment_period"].Data = "<?php echo $loan_application_delete->repayment_period->LookupFilterQuery(FALSE, "delete") ?>";
floan_applicationdelete.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"loan_status"};
floan_applicationdelete.Lists["x_status"].Data = "<?php echo $loan_application_delete->status->LookupFilterQuery(FALSE, "delete") ?>";
floan_applicationdelete.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_application_delete->status->LookupFilterQuery(TRUE, "delete"))) ?>;
floan_applicationdelete.Lists["x_application_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationdelete.Lists["x_application_status"].Options = <?php echo json_encode($loan_application_delete->application_status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $loan_application_delete->ShowPageHeader(); ?>
<?php
$loan_application_delete->ShowMessage();
?>
<form name="floan_applicationdelete" id="floan_applicationdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($loan_application_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $loan_application_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="loan_application">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($loan_application_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($loan_application->date_initiated->Visible) { // date_initiated ?>
		<th class="<?php echo $loan_application->date_initiated->HeaderCellClass() ?>"><span id="elh_loan_application_date_initiated" class="loan_application_date_initiated"><?php echo $loan_application->date_initiated->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->refernce_id->Visible) { // refernce_id ?>
		<th class="<?php echo $loan_application->refernce_id->HeaderCellClass() ?>"><span id="elh_loan_application_refernce_id" class="loan_application_refernce_id"><?php echo $loan_application->refernce_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->employee_name->Visible) { // employee_name ?>
		<th class="<?php echo $loan_application->employee_name->HeaderCellClass() ?>"><span id="elh_loan_application_employee_name" class="loan_application_employee_name"><?php echo $loan_application->employee_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->address->Visible) { // address ?>
		<th class="<?php echo $loan_application->address->HeaderCellClass() ?>"><span id="elh_loan_application_address" class="loan_application_address"><?php echo $loan_application->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->mobile->Visible) { // mobile ?>
		<th class="<?php echo $loan_application->mobile->HeaderCellClass() ?>"><span id="elh_loan_application_mobile" class="loan_application_mobile"><?php echo $loan_application->mobile->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->department->Visible) { // department ?>
		<th class="<?php echo $loan_application->department->HeaderCellClass() ?>"><span id="elh_loan_application_department" class="loan_application_department"><?php echo $loan_application->department->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->loan_amount->Visible) { // loan_amount ?>
		<th class="<?php echo $loan_application->loan_amount->HeaderCellClass() ?>"><span id="elh_loan_application_loan_amount" class="loan_application_loan_amount"><?php echo $loan_application->loan_amount->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->amount_inwords->Visible) { // amount_inwords ?>
		<th class="<?php echo $loan_application->amount_inwords->HeaderCellClass() ?>"><span id="elh_loan_application_amount_inwords" class="loan_application_amount_inwords"><?php echo $loan_application->amount_inwords->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->repayment_period->Visible) { // repayment_period ?>
		<th class="<?php echo $loan_application->repayment_period->HeaderCellClass() ?>"><span id="elh_loan_application_repayment_period" class="loan_application_repayment_period"><?php echo $loan_application->repayment_period->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->salary_permonth->Visible) { // salary_permonth ?>
		<th class="<?php echo $loan_application->salary_permonth->HeaderCellClass() ?>"><span id="elh_loan_application_salary_permonth" class="loan_application_salary_permonth"><?php echo $loan_application->salary_permonth->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->previous_loan->Visible) { // previous_loan ?>
		<th class="<?php echo $loan_application->previous_loan->HeaderCellClass() ?>"><span id="elh_loan_application_previous_loan" class="loan_application_previous_loan"><?php echo $loan_application->previous_loan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->date_collected->Visible) { // date_collected ?>
		<th class="<?php echo $loan_application->date_collected->HeaderCellClass() ?>"><span id="elh_loan_application_date_collected" class="loan_application_date_collected"><?php echo $loan_application->date_collected->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->date_liquidated->Visible) { // date_liquidated ?>
		<th class="<?php echo $loan_application->date_liquidated->HeaderCellClass() ?>"><span id="elh_loan_application_date_liquidated" class="loan_application_date_liquidated"><?php echo $loan_application->date_liquidated->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->balance_remaining->Visible) { // balance_remaining ?>
		<th class="<?php echo $loan_application->balance_remaining->HeaderCellClass() ?>"><span id="elh_loan_application_balance_remaining" class="loan_application_balance_remaining"><?php echo $loan_application->balance_remaining->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->applicant_date->Visible) { // applicant_date ?>
		<th class="<?php echo $loan_application->applicant_date->HeaderCellClass() ?>"><span id="elh_loan_application_applicant_date" class="loan_application_applicant_date"><?php echo $loan_application->applicant_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->guarantor_name->Visible) { // guarantor_name ?>
		<th class="<?php echo $loan_application->guarantor_name->HeaderCellClass() ?>"><span id="elh_loan_application_guarantor_name" class="loan_application_guarantor_name"><?php echo $loan_application->guarantor_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->guarantor_address->Visible) { // guarantor_address ?>
		<th class="<?php echo $loan_application->guarantor_address->HeaderCellClass() ?>"><span id="elh_loan_application_guarantor_address" class="loan_application_guarantor_address"><?php echo $loan_application->guarantor_address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->guarantor_mobile->Visible) { // guarantor_mobile ?>
		<th class="<?php echo $loan_application->guarantor_mobile->HeaderCellClass() ?>"><span id="elh_loan_application_guarantor_mobile" class="loan_application_guarantor_mobile"><?php echo $loan_application->guarantor_mobile->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->status->Visible) { // status ?>
		<th class="<?php echo $loan_application->status->HeaderCellClass() ?>"><span id="elh_loan_application_status" class="loan_application_status"><?php echo $loan_application->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->application_status->Visible) { // application_status ?>
		<th class="<?php echo $loan_application->application_status->HeaderCellClass() ?>"><span id="elh_loan_application_application_status" class="loan_application_application_status"><?php echo $loan_application->application_status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($loan_application->pension->Visible) { // pension ?>
		<th class="<?php echo $loan_application->pension->HeaderCellClass() ?>"><span id="elh_loan_application_pension" class="loan_application_pension"><?php echo $loan_application->pension->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$loan_application_delete->RecCnt = 0;
$i = 0;
while (!$loan_application_delete->Recordset->EOF) {
	$loan_application_delete->RecCnt++;
	$loan_application_delete->RowCnt++;

	// Set row properties
	$loan_application->ResetAttrs();
	$loan_application->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$loan_application_delete->LoadRowValues($loan_application_delete->Recordset);

	// Render row
	$loan_application_delete->RenderRow();
?>
	<tr<?php echo $loan_application->RowAttributes() ?>>
<?php if ($loan_application->date_initiated->Visible) { // date_initiated ?>
		<td<?php echo $loan_application->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_date_initiated" class="loan_application_date_initiated">
<span<?php echo $loan_application->date_initiated->ViewAttributes() ?>>
<?php echo $loan_application->date_initiated->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->refernce_id->Visible) { // refernce_id ?>
		<td<?php echo $loan_application->refernce_id->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_refernce_id" class="loan_application_refernce_id">
<span<?php echo $loan_application->refernce_id->ViewAttributes() ?>>
<?php echo $loan_application->refernce_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->employee_name->Visible) { // employee_name ?>
		<td<?php echo $loan_application->employee_name->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_employee_name" class="loan_application_employee_name">
<span<?php echo $loan_application->employee_name->ViewAttributes() ?>>
<?php echo $loan_application->employee_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->address->Visible) { // address ?>
		<td<?php echo $loan_application->address->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_address" class="loan_application_address">
<span<?php echo $loan_application->address->ViewAttributes() ?>>
<?php echo $loan_application->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->mobile->Visible) { // mobile ?>
		<td<?php echo $loan_application->mobile->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_mobile" class="loan_application_mobile">
<span<?php echo $loan_application->mobile->ViewAttributes() ?>>
<?php echo $loan_application->mobile->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->department->Visible) { // department ?>
		<td<?php echo $loan_application->department->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_department" class="loan_application_department">
<span<?php echo $loan_application->department->ViewAttributes() ?>>
<?php echo $loan_application->department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->loan_amount->Visible) { // loan_amount ?>
		<td<?php echo $loan_application->loan_amount->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_loan_amount" class="loan_application_loan_amount">
<span<?php echo $loan_application->loan_amount->ViewAttributes() ?>>
<?php echo $loan_application->loan_amount->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->amount_inwords->Visible) { // amount_inwords ?>
		<td<?php echo $loan_application->amount_inwords->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_amount_inwords" class="loan_application_amount_inwords">
<span<?php echo $loan_application->amount_inwords->ViewAttributes() ?>>
<?php echo $loan_application->amount_inwords->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->repayment_period->Visible) { // repayment_period ?>
		<td<?php echo $loan_application->repayment_period->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_repayment_period" class="loan_application_repayment_period">
<span<?php echo $loan_application->repayment_period->ViewAttributes() ?>>
<?php echo $loan_application->repayment_period->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->salary_permonth->Visible) { // salary_permonth ?>
		<td<?php echo $loan_application->salary_permonth->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_salary_permonth" class="loan_application_salary_permonth">
<span<?php echo $loan_application->salary_permonth->ViewAttributes() ?>>
<?php echo $loan_application->salary_permonth->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->previous_loan->Visible) { // previous_loan ?>
		<td<?php echo $loan_application->previous_loan->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_previous_loan" class="loan_application_previous_loan">
<span<?php echo $loan_application->previous_loan->ViewAttributes() ?>>
<?php echo $loan_application->previous_loan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->date_collected->Visible) { // date_collected ?>
		<td<?php echo $loan_application->date_collected->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_date_collected" class="loan_application_date_collected">
<span<?php echo $loan_application->date_collected->ViewAttributes() ?>>
<?php echo $loan_application->date_collected->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->date_liquidated->Visible) { // date_liquidated ?>
		<td<?php echo $loan_application->date_liquidated->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_date_liquidated" class="loan_application_date_liquidated">
<span<?php echo $loan_application->date_liquidated->ViewAttributes() ?>>
<?php echo $loan_application->date_liquidated->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->balance_remaining->Visible) { // balance_remaining ?>
		<td<?php echo $loan_application->balance_remaining->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_balance_remaining" class="loan_application_balance_remaining">
<span<?php echo $loan_application->balance_remaining->ViewAttributes() ?>>
<?php echo $loan_application->balance_remaining->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->applicant_date->Visible) { // applicant_date ?>
		<td<?php echo $loan_application->applicant_date->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_applicant_date" class="loan_application_applicant_date">
<span<?php echo $loan_application->applicant_date->ViewAttributes() ?>>
<?php echo $loan_application->applicant_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->guarantor_name->Visible) { // guarantor_name ?>
		<td<?php echo $loan_application->guarantor_name->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_guarantor_name" class="loan_application_guarantor_name">
<span<?php echo $loan_application->guarantor_name->ViewAttributes() ?>>
<?php echo $loan_application->guarantor_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->guarantor_address->Visible) { // guarantor_address ?>
		<td<?php echo $loan_application->guarantor_address->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_guarantor_address" class="loan_application_guarantor_address">
<span<?php echo $loan_application->guarantor_address->ViewAttributes() ?>>
<?php echo $loan_application->guarantor_address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->guarantor_mobile->Visible) { // guarantor_mobile ?>
		<td<?php echo $loan_application->guarantor_mobile->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_guarantor_mobile" class="loan_application_guarantor_mobile">
<span<?php echo $loan_application->guarantor_mobile->ViewAttributes() ?>>
<?php echo $loan_application->guarantor_mobile->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->status->Visible) { // status ?>
		<td<?php echo $loan_application->status->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_status" class="loan_application_status">
<span<?php echo $loan_application->status->ViewAttributes() ?>>
<?php echo $loan_application->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->application_status->Visible) { // application_status ?>
		<td<?php echo $loan_application->application_status->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_application_status" class="loan_application_application_status">
<span<?php echo $loan_application->application_status->ViewAttributes() ?>>
<?php echo $loan_application->application_status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($loan_application->pension->Visible) { // pension ?>
		<td<?php echo $loan_application->pension->CellAttributes() ?>>
<span id="el<?php echo $loan_application_delete->RowCnt ?>_loan_application_pension" class="loan_application_pension">
<span<?php echo $loan_application->pension->ViewAttributes() ?>>
<?php echo $loan_application->pension->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$loan_application_delete->Recordset->MoveNext();
}
$loan_application_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $loan_application_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
floan_applicationdelete.Init();
</script>
<?php
$loan_application_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$loan_application_delete->Page_Terminate();
?>
