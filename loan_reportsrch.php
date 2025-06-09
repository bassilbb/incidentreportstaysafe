<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "loan_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$loan_report_search = NULL; // Initialize page object first

class cloan_report_search extends cloan_report {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'loan_report';

	// Page object name
	var $PageObjName = 'loan_report_search';

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

		// Table object (loan_report)
		if (!isset($GLOBALS["loan_report"]) || get_class($GLOBALS["loan_report"]) == "cloan_report") {
			$GLOBALS["loan_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["loan_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'loan_report');

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
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("loan_reportlist.php"));
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
		$this->code->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->code->Visible = FALSE;
		$this->date_initiated->SetVisibility();
		$this->refernce_id->SetVisibility();
		$this->employee_name->SetVisibility();
		$this->address->SetVisibility();
		$this->mobile->SetVisibility();
		$this->department->SetVisibility();
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
		global $EW_EXPORT, $loan_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($loan_report);
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
					if ($pageName == "loan_reportview.php")
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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "loan_reportlist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->code); // code
		$this->BuildSearchUrl($sSrchUrl, $this->date_initiated); // date_initiated
		$this->BuildSearchUrl($sSrchUrl, $this->refernce_id); // refernce_id
		$this->BuildSearchUrl($sSrchUrl, $this->employee_name); // employee_name
		$this->BuildSearchUrl($sSrchUrl, $this->address); // address
		$this->BuildSearchUrl($sSrchUrl, $this->mobile); // mobile
		$this->BuildSearchUrl($sSrchUrl, $this->department); // department
		$this->BuildSearchUrl($sSrchUrl, $this->loan_amount); // loan_amount
		$this->BuildSearchUrl($sSrchUrl, $this->amount_inwords); // amount_inwords
		$this->BuildSearchUrl($sSrchUrl, $this->purpose); // purpose
		$this->BuildSearchUrl($sSrchUrl, $this->repayment_period); // repayment_period
		$this->BuildSearchUrl($sSrchUrl, $this->salary_permonth); // salary_permonth
		$this->BuildSearchUrl($sSrchUrl, $this->previous_loan); // previous_loan
		$this->BuildSearchUrl($sSrchUrl, $this->date_collected); // date_collected
		$this->BuildSearchUrl($sSrchUrl, $this->date_liquidated); // date_liquidated
		$this->BuildSearchUrl($sSrchUrl, $this->balance_remaining); // balance_remaining
		$this->BuildSearchUrl($sSrchUrl, $this->applicant_date); // applicant_date
		$this->BuildSearchUrl($sSrchUrl, $this->applicant_passport); // applicant_passport
		$this->BuildSearchUrl($sSrchUrl, $this->guarantor_name); // guarantor_name
		$this->BuildSearchUrl($sSrchUrl, $this->guarantor_address); // guarantor_address
		$this->BuildSearchUrl($sSrchUrl, $this->guarantor_mobile); // guarantor_mobile
		$this->BuildSearchUrl($sSrchUrl, $this->guarantor_department); // guarantor_department
		$this->BuildSearchUrl($sSrchUrl, $this->account_no); // account_no
		$this->BuildSearchUrl($sSrchUrl, $this->bank_name); // bank_name
		$this->BuildSearchUrl($sSrchUrl, $this->employers_name); // employers_name
		$this->BuildSearchUrl($sSrchUrl, $this->employers_address); // employers_address
		$this->BuildSearchUrl($sSrchUrl, $this->employers_mobile); // employers_mobile
		$this->BuildSearchUrl($sSrchUrl, $this->guarantor_date); // guarantor_date
		$this->BuildSearchUrl($sSrchUrl, $this->guarantor_passport); // guarantor_passport
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
		$this->BuildSearchUrl($sSrchUrl, $this->initiator_action); // initiator_action
		$this->BuildSearchUrl($sSrchUrl, $this->initiator_comment); // initiator_comment
		$this->BuildSearchUrl($sSrchUrl, $this->recommended_date); // recommended_date
		$this->BuildSearchUrl($sSrchUrl, $this->document_checklist); // document_checklist
		$this->BuildSearchUrl($sSrchUrl, $this->recommender_action); // recommender_action
		$this->BuildSearchUrl($sSrchUrl, $this->recommender_comment); // recommender_comment
		$this->BuildSearchUrl($sSrchUrl, $this->recommended_by); // recommended_by
		$this->BuildSearchUrl($sSrchUrl, $this->application_status); // application_status
		$this->BuildSearchUrl($sSrchUrl, $this->approved_amount); // approved_amount
		$this->BuildSearchUrl($sSrchUrl, $this->duration_approved); // duration_approved
		$this->BuildSearchUrl($sSrchUrl, $this->approval_date); // approval_date
		$this->BuildSearchUrl($sSrchUrl, $this->approval_action); // approval_action
		$this->BuildSearchUrl($sSrchUrl, $this->approval_comment); // approval_comment
		$this->BuildSearchUrl($sSrchUrl, $this->approved_by); // approved_by
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = $Fld->FldParm();
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = $FldVal;
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $FldVal2;
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// code

		$this->code->AdvancedSearch->SearchValue = $objForm->GetValue("x_code");
		$this->code->AdvancedSearch->SearchOperator = $objForm->GetValue("z_code");

		// date_initiated
		$this->date_initiated->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_initiated");
		$this->date_initiated->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_initiated");
		$this->date_initiated->AdvancedSearch->SearchCondition = $objForm->GetValue("v_date_initiated");
		$this->date_initiated->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_date_initiated");
		$this->date_initiated->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_date_initiated");

		// refernce_id
		$this->refernce_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_refernce_id");
		$this->refernce_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_refernce_id");

		// employee_name
		$this->employee_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_employee_name");
		$this->employee_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_employee_name");

		// address
		$this->address->AdvancedSearch->SearchValue = $objForm->GetValue("x_address");
		$this->address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_address");

		// mobile
		$this->mobile->AdvancedSearch->SearchValue = $objForm->GetValue("x_mobile");
		$this->mobile->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobile");

		// department
		$this->department->AdvancedSearch->SearchValue = $objForm->GetValue("x_department");
		$this->department->AdvancedSearch->SearchOperator = $objForm->GetValue("z_department");

		// loan_amount
		$this->loan_amount->AdvancedSearch->SearchValue = $objForm->GetValue("x_loan_amount");
		$this->loan_amount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_loan_amount");

		// amount_inwords
		$this->amount_inwords->AdvancedSearch->SearchValue = $objForm->GetValue("x_amount_inwords");
		$this->amount_inwords->AdvancedSearch->SearchOperator = $objForm->GetValue("z_amount_inwords");

		// purpose
		$this->purpose->AdvancedSearch->SearchValue = $objForm->GetValue("x_purpose");
		$this->purpose->AdvancedSearch->SearchOperator = $objForm->GetValue("z_purpose");

		// repayment_period
		$this->repayment_period->AdvancedSearch->SearchValue = $objForm->GetValue("x_repayment_period");
		$this->repayment_period->AdvancedSearch->SearchOperator = $objForm->GetValue("z_repayment_period");

		// salary_permonth
		$this->salary_permonth->AdvancedSearch->SearchValue = $objForm->GetValue("x_salary_permonth");
		$this->salary_permonth->AdvancedSearch->SearchOperator = $objForm->GetValue("z_salary_permonth");

		// previous_loan
		$this->previous_loan->AdvancedSearch->SearchValue = $objForm->GetValue("x_previous_loan");
		$this->previous_loan->AdvancedSearch->SearchOperator = $objForm->GetValue("z_previous_loan");

		// date_collected
		$this->date_collected->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_collected");
		$this->date_collected->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_collected");

		// date_liquidated
		$this->date_liquidated->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_liquidated");
		$this->date_liquidated->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_liquidated");

		// balance_remaining
		$this->balance_remaining->AdvancedSearch->SearchValue = $objForm->GetValue("x_balance_remaining");
		$this->balance_remaining->AdvancedSearch->SearchOperator = $objForm->GetValue("z_balance_remaining");

		// applicant_date
		$this->applicant_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_applicant_date");
		$this->applicant_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_applicant_date");

		// applicant_passport
		$this->applicant_passport->AdvancedSearch->SearchValue = $objForm->GetValue("x_applicant_passport");
		$this->applicant_passport->AdvancedSearch->SearchOperator = $objForm->GetValue("z_applicant_passport");

		// guarantor_name
		$this->guarantor_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_guarantor_name");
		$this->guarantor_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guarantor_name");

		// guarantor_address
		$this->guarantor_address->AdvancedSearch->SearchValue = $objForm->GetValue("x_guarantor_address");
		$this->guarantor_address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guarantor_address");

		// guarantor_mobile
		$this->guarantor_mobile->AdvancedSearch->SearchValue = $objForm->GetValue("x_guarantor_mobile");
		$this->guarantor_mobile->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guarantor_mobile");

		// guarantor_department
		$this->guarantor_department->AdvancedSearch->SearchValue = $objForm->GetValue("x_guarantor_department");
		$this->guarantor_department->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guarantor_department");

		// account_no
		$this->account_no->AdvancedSearch->SearchValue = $objForm->GetValue("x_account_no");
		$this->account_no->AdvancedSearch->SearchOperator = $objForm->GetValue("z_account_no");

		// bank_name
		$this->bank_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_bank_name");
		$this->bank_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_bank_name");

		// employers_name
		$this->employers_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_employers_name");
		$this->employers_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_employers_name");

		// employers_address
		$this->employers_address->AdvancedSearch->SearchValue = $objForm->GetValue("x_employers_address");
		$this->employers_address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_employers_address");

		// employers_mobile
		$this->employers_mobile->AdvancedSearch->SearchValue = $objForm->GetValue("x_employers_mobile");
		$this->employers_mobile->AdvancedSearch->SearchOperator = $objForm->GetValue("z_employers_mobile");

		// guarantor_date
		$this->guarantor_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_guarantor_date");
		$this->guarantor_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guarantor_date");

		// guarantor_passport
		$this->guarantor_passport->AdvancedSearch->SearchValue = $objForm->GetValue("x_guarantor_passport");
		$this->guarantor_passport->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guarantor_passport");

		// status
		$this->status->AdvancedSearch->SearchValue = $objForm->GetValue("x_status");
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");

		// initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_initiator_action");
		$this->initiator_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_initiator_action");

		// initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_initiator_comment");
		$this->initiator_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_initiator_comment");

		// recommended_date
		$this->recommended_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_recommended_date");
		$this->recommended_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recommended_date");

		// document_checklist
		$this->document_checklist->AdvancedSearch->SearchValue = $objForm->GetValue("x_document_checklist");
		$this->document_checklist->AdvancedSearch->SearchOperator = $objForm->GetValue("z_document_checklist");
		if (is_array($this->document_checklist->AdvancedSearch->SearchValue)) $this->document_checklist->AdvancedSearch->SearchValue = implode(",", $this->document_checklist->AdvancedSearch->SearchValue);
		if (is_array($this->document_checklist->AdvancedSearch->SearchValue2)) $this->document_checklist->AdvancedSearch->SearchValue2 = implode(",", $this->document_checklist->AdvancedSearch->SearchValue2);

		// recommender_action
		$this->recommender_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_recommender_action");
		$this->recommender_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recommender_action");

		// recommender_comment
		$this->recommender_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_recommender_comment");
		$this->recommender_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recommender_comment");

		// recommended_by
		$this->recommended_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_recommended_by");
		$this->recommended_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recommended_by");

		// application_status
		$this->application_status->AdvancedSearch->SearchValue = $objForm->GetValue("x_application_status");
		$this->application_status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_application_status");

		// approved_amount
		$this->approved_amount->AdvancedSearch->SearchValue = $objForm->GetValue("x_approved_amount");
		$this->approved_amount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approved_amount");

		// duration_approved
		$this->duration_approved->AdvancedSearch->SearchValue = $objForm->GetValue("x_duration_approved");
		$this->duration_approved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_duration_approved");

		// approval_date
		$this->approval_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_approval_date");
		$this->approval_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approval_date");

		// approval_action
		$this->approval_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_approval_action");
		$this->approval_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approval_action");

		// approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_approval_comment");
		$this->approval_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approval_comment");

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_approved_by");
		$this->approved_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approved_by");
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
		$this->employee_name->ViewValue = $this->employee_name->CurrentValue;
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
		$this->applicant_date->ViewValue = ew_FormatDateTime($this->applicant_date->ViewValue, 14);
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
		$this->guarantor_date->ViewValue = ew_FormatDateTime($this->guarantor_date->ViewValue, 14);
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

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
			$this->code->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = ew_HtmlEncode($this->code->AdvancedSearch->SearchValue);
			$this->code->PlaceHolder = ew_RemoveHtml($this->code->FldCaption());

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue, 0), 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue2, 0), 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// refernce_id
			$this->refernce_id->EditAttrs["class"] = "form-control";
			$this->refernce_id->EditCustomAttributes = "";
			$this->refernce_id->EditValue = ew_HtmlEncode($this->refernce_id->AdvancedSearch->SearchValue);
			$this->refernce_id->PlaceHolder = ew_RemoveHtml($this->refernce_id->FldCaption());

			// employee_name
			$this->employee_name->EditAttrs["class"] = "form-control";
			$this->employee_name->EditCustomAttributes = "";
			$this->employee_name->EditValue = ew_HtmlEncode($this->employee_name->AdvancedSearch->SearchValue);
			$this->employee_name->PlaceHolder = ew_RemoveHtml($this->employee_name->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->AdvancedSearch->SearchValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// mobile
			$this->mobile->EditAttrs["class"] = "form-control";
			$this->mobile->EditCustomAttributes = "";
			$this->mobile->EditValue = ew_HtmlEncode($this->mobile->AdvancedSearch->SearchValue);
			$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";
			if (trim(strval($this->department->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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

			// loan_amount
			$this->loan_amount->EditAttrs["class"] = "form-control";
			$this->loan_amount->EditCustomAttributes = "";
			$this->loan_amount->EditValue = ew_HtmlEncode($this->loan_amount->AdvancedSearch->SearchValue);
			$this->loan_amount->PlaceHolder = ew_RemoveHtml($this->loan_amount->FldCaption());

			// amount_inwords
			$this->amount_inwords->EditAttrs["class"] = "form-control";
			$this->amount_inwords->EditCustomAttributes = "";
			$this->amount_inwords->EditValue = ew_HtmlEncode($this->amount_inwords->AdvancedSearch->SearchValue);
			$this->amount_inwords->PlaceHolder = ew_RemoveHtml($this->amount_inwords->FldCaption());

			// purpose
			$this->purpose->EditAttrs["class"] = "form-control";
			$this->purpose->EditCustomAttributes = "";
			$this->purpose->EditValue = ew_HtmlEncode($this->purpose->AdvancedSearch->SearchValue);
			$this->purpose->PlaceHolder = ew_RemoveHtml($this->purpose->FldCaption());

			// repayment_period
			$this->repayment_period->EditAttrs["class"] = "form-control";
			$this->repayment_period->EditCustomAttributes = "";
			if (trim(strval($this->repayment_period->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->repayment_period->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->salary_permonth->EditValue = ew_HtmlEncode($this->salary_permonth->AdvancedSearch->SearchValue);
			$this->salary_permonth->PlaceHolder = ew_RemoveHtml($this->salary_permonth->FldCaption());

			// previous_loan
			$this->previous_loan->EditAttrs["class"] = "form-control";
			$this->previous_loan->EditCustomAttributes = "";
			$this->previous_loan->EditValue = ew_HtmlEncode($this->previous_loan->AdvancedSearch->SearchValue);
			$this->previous_loan->PlaceHolder = ew_RemoveHtml($this->previous_loan->FldCaption());

			// date_collected
			$this->date_collected->EditAttrs["class"] = "form-control";
			$this->date_collected->EditCustomAttributes = "";
			$this->date_collected->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_collected->AdvancedSearch->SearchValue, 0), 8));
			$this->date_collected->PlaceHolder = ew_RemoveHtml($this->date_collected->FldCaption());

			// date_liquidated
			$this->date_liquidated->EditAttrs["class"] = "form-control";
			$this->date_liquidated->EditCustomAttributes = "";
			$this->date_liquidated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_liquidated->AdvancedSearch->SearchValue, 0), 8));
			$this->date_liquidated->PlaceHolder = ew_RemoveHtml($this->date_liquidated->FldCaption());

			// balance_remaining
			$this->balance_remaining->EditAttrs["class"] = "form-control";
			$this->balance_remaining->EditCustomAttributes = "";
			$this->balance_remaining->EditValue = ew_HtmlEncode($this->balance_remaining->AdvancedSearch->SearchValue);
			$this->balance_remaining->PlaceHolder = ew_RemoveHtml($this->balance_remaining->FldCaption());

			// applicant_date
			$this->applicant_date->EditAttrs["class"] = "form-control";
			$this->applicant_date->EditCustomAttributes = "";
			$this->applicant_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->applicant_date->AdvancedSearch->SearchValue, 14), 14));
			$this->applicant_date->PlaceHolder = ew_RemoveHtml($this->applicant_date->FldCaption());

			// applicant_passport
			$this->applicant_passport->EditAttrs["class"] = "form-control";
			$this->applicant_passport->EditCustomAttributes = "";
			$this->applicant_passport->EditValue = ew_HtmlEncode($this->applicant_passport->AdvancedSearch->SearchValue);
			$this->applicant_passport->PlaceHolder = ew_RemoveHtml($this->applicant_passport->FldCaption());

			// guarantor_name
			$this->guarantor_name->EditAttrs["class"] = "form-control";
			$this->guarantor_name->EditCustomAttributes = "";
			$this->guarantor_name->EditValue = ew_HtmlEncode($this->guarantor_name->AdvancedSearch->SearchValue);
			$this->guarantor_name->PlaceHolder = ew_RemoveHtml($this->guarantor_name->FldCaption());

			// guarantor_address
			$this->guarantor_address->EditAttrs["class"] = "form-control";
			$this->guarantor_address->EditCustomAttributes = "";
			$this->guarantor_address->EditValue = ew_HtmlEncode($this->guarantor_address->AdvancedSearch->SearchValue);
			$this->guarantor_address->PlaceHolder = ew_RemoveHtml($this->guarantor_address->FldCaption());

			// guarantor_mobile
			$this->guarantor_mobile->EditAttrs["class"] = "form-control";
			$this->guarantor_mobile->EditCustomAttributes = "";
			$this->guarantor_mobile->EditValue = ew_HtmlEncode($this->guarantor_mobile->AdvancedSearch->SearchValue);
			$this->guarantor_mobile->PlaceHolder = ew_RemoveHtml($this->guarantor_mobile->FldCaption());

			// guarantor_department
			$this->guarantor_department->EditAttrs["class"] = "form-control";
			$this->guarantor_department->EditCustomAttributes = "";
			if (trim(strval($this->guarantor_department->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->guarantor_department->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->account_no->EditValue = ew_HtmlEncode($this->account_no->AdvancedSearch->SearchValue);
			$this->account_no->PlaceHolder = ew_RemoveHtml($this->account_no->FldCaption());

			// bank_name
			$this->bank_name->EditCustomAttributes = "";
			if (trim(strval($this->bank_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->bank_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
				$this->bank_name->AdvancedSearch->ViewValue = $this->bank_name->DisplayValue($arwrk);
			} else {
				$this->bank_name->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->bank_name->EditValue = $arwrk;

			// employers_name
			$this->employers_name->EditAttrs["class"] = "form-control";
			$this->employers_name->EditCustomAttributes = "";
			$this->employers_name->EditValue = ew_HtmlEncode($this->employers_name->AdvancedSearch->SearchValue);
			$this->employers_name->PlaceHolder = ew_RemoveHtml($this->employers_name->FldCaption());

			// employers_address
			$this->employers_address->EditAttrs["class"] = "form-control";
			$this->employers_address->EditCustomAttributes = "";
			$this->employers_address->EditValue = ew_HtmlEncode($this->employers_address->AdvancedSearch->SearchValue);
			$this->employers_address->PlaceHolder = ew_RemoveHtml($this->employers_address->FldCaption());

			// employers_mobile
			$this->employers_mobile->EditAttrs["class"] = "form-control";
			$this->employers_mobile->EditCustomAttributes = "";
			$this->employers_mobile->EditValue = ew_HtmlEncode($this->employers_mobile->AdvancedSearch->SearchValue);
			$this->employers_mobile->PlaceHolder = ew_RemoveHtml($this->employers_mobile->FldCaption());

			// guarantor_date
			$this->guarantor_date->EditAttrs["class"] = "form-control";
			$this->guarantor_date->EditCustomAttributes = "";
			$this->guarantor_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->guarantor_date->AdvancedSearch->SearchValue, 14), 14));
			$this->guarantor_date->PlaceHolder = ew_RemoveHtml($this->guarantor_date->FldCaption());

			// guarantor_passport
			$this->guarantor_passport->EditAttrs["class"] = "form-control";
			$this->guarantor_passport->EditCustomAttributes = "";
			$this->guarantor_passport->EditValue = ew_HtmlEncode($this->guarantor_passport->AdvancedSearch->SearchValue);
			$this->guarantor_passport->PlaceHolder = ew_RemoveHtml($this->guarantor_passport->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			if (strval($this->status->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
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
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->AdvancedSearch->SearchValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// recommended_date
			$this->recommended_date->EditAttrs["class"] = "form-control";
			$this->recommended_date->EditCustomAttributes = "";
			$this->recommended_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->recommended_date->AdvancedSearch->SearchValue, 14), 14));
			$this->recommended_date->PlaceHolder = ew_RemoveHtml($this->recommended_date->FldCaption());

			// document_checklist
			$this->document_checklist->EditCustomAttributes = "";
			if (trim(strval($this->document_checklist->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->document_checklist->AdvancedSearch->SearchValue);
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
			$this->recommender_comment->EditValue = ew_HtmlEncode($this->recommender_comment->AdvancedSearch->SearchValue);
			$this->recommender_comment->PlaceHolder = ew_RemoveHtml($this->recommender_comment->FldCaption());

			// recommended_by
			$this->recommended_by->EditAttrs["class"] = "form-control";
			$this->recommended_by->EditCustomAttributes = "";
			$this->recommended_by->EditValue = ew_HtmlEncode($this->recommended_by->AdvancedSearch->SearchValue);
			if (strval($this->recommended_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->recommended_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->recommended_by->EditValue = ew_HtmlEncode($this->recommended_by->AdvancedSearch->SearchValue);
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
			$this->approved_amount->EditValue = ew_HtmlEncode($this->approved_amount->AdvancedSearch->SearchValue);
			$this->approved_amount->PlaceHolder = ew_RemoveHtml($this->approved_amount->FldCaption());

			// duration_approved
			$this->duration_approved->EditAttrs["class"] = "form-control";
			$this->duration_approved->EditCustomAttributes = "";
			if (trim(strval(ew_UnFormatDateTime($this->duration_approved->AdvancedSearch->SearchValue, 0))) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", ew_UnFormatDateTime($this->duration_approved->AdvancedSearch->SearchValue, 0), EW_DATATYPE_NUMBER, "");
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
			$this->approval_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->approval_date->AdvancedSearch->SearchValue, 17), 17));
			$this->approval_date->PlaceHolder = ew_RemoveHtml($this->approval_date->FldCaption());

			// approval_action
			$this->approval_action->EditCustomAttributes = "";
			$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

			// approval_comment
			$this->approval_comment->EditAttrs["class"] = "form-control";
			$this->approval_comment->EditCustomAttributes = "";
			$this->approval_comment->EditValue = ew_HtmlEncode($this->approval_comment->AdvancedSearch->SearchValue);
			$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			if (trim(strval($this->approved_by->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->code->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->code->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_initiated->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_initiated->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_initiated->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->date_initiated->FldErrMsg());
		}
		if (!ew_CheckNumber($this->loan_amount->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->loan_amount->FldErrMsg());
		}
		if (!ew_CheckNumber($this->salary_permonth->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->salary_permonth->FldErrMsg());
		}
		if (!ew_CheckNumber($this->previous_loan->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->previous_loan->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_collected->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_collected->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_liquidated->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_liquidated->FldErrMsg());
		}
		if (!ew_CheckNumber($this->balance_remaining->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->balance_remaining->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->applicant_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->applicant_date->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->guarantor_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->guarantor_date->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->recommended_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->recommended_date->FldErrMsg());
		}
		if (!ew_CheckNumber($this->approved_amount->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approved_amount->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->approval_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approval_date->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->code->AdvancedSearch->Load();
		$this->date_initiated->AdvancedSearch->Load();
		$this->refernce_id->AdvancedSearch->Load();
		$this->employee_name->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->mobile->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->loan_amount->AdvancedSearch->Load();
		$this->amount_inwords->AdvancedSearch->Load();
		$this->purpose->AdvancedSearch->Load();
		$this->repayment_period->AdvancedSearch->Load();
		$this->salary_permonth->AdvancedSearch->Load();
		$this->previous_loan->AdvancedSearch->Load();
		$this->date_collected->AdvancedSearch->Load();
		$this->date_liquidated->AdvancedSearch->Load();
		$this->balance_remaining->AdvancedSearch->Load();
		$this->applicant_date->AdvancedSearch->Load();
		$this->applicant_passport->AdvancedSearch->Load();
		$this->guarantor_name->AdvancedSearch->Load();
		$this->guarantor_address->AdvancedSearch->Load();
		$this->guarantor_mobile->AdvancedSearch->Load();
		$this->guarantor_department->AdvancedSearch->Load();
		$this->account_no->AdvancedSearch->Load();
		$this->bank_name->AdvancedSearch->Load();
		$this->employers_name->AdvancedSearch->Load();
		$this->employers_address->AdvancedSearch->Load();
		$this->employers_mobile->AdvancedSearch->Load();
		$this->guarantor_date->AdvancedSearch->Load();
		$this->guarantor_passport->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->recommended_date->AdvancedSearch->Load();
		$this->document_checklist->AdvancedSearch->Load();
		$this->recommender_action->AdvancedSearch->Load();
		$this->recommender_comment->AdvancedSearch->Load();
		$this->recommended_by->AdvancedSearch->Load();
		$this->application_status->AdvancedSearch->Load();
		$this->approved_amount->AdvancedSearch->Load();
		$this->duration_approved->AdvancedSearch->Load();
		$this->approval_date->AdvancedSearch->Load();
		$this->approval_action->AdvancedSearch->Load();
		$this->approval_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("loan_reportlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
if (!isset($loan_report_search)) $loan_report_search = new cloan_report_search();

// Page init
$loan_report_search->Page_Init();

// Page main
$loan_report_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$loan_report_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($loan_report_search->IsModal) { ?>
var CurrentAdvancedSearchForm = floan_reportsearch = new ew_Form("floan_reportsearch", "search");
<?php } else { ?>
var CurrentForm = floan_reportsearch = new ew_Form("floan_reportsearch", "search");
<?php } ?>

// Form_CustomValidate event
floan_reportsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
floan_reportsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
floan_reportsearch.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
floan_reportsearch.Lists["x_department"].Data = "<?php echo $loan_report_search->department->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.Lists["x_repayment_period"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"duration_months"};
floan_reportsearch.Lists["x_repayment_period"].Data = "<?php echo $loan_report_search->repayment_period->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.Lists["x_guarantor_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
floan_reportsearch.Lists["x_guarantor_department"].Data = "<?php echo $loan_report_search->guarantor_department->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.Lists["x_bank_name"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"banks_list"};
floan_reportsearch.Lists["x_bank_name"].Data = "<?php echo $loan_report_search->bank_name->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"loan_status"};
floan_reportsearch.Lists["x_status"].Data = "<?php echo $loan_report_search->status->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_report_search->status->LookupFilterQuery(TRUE, "search"))) ?>;
floan_reportsearch.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_reportsearch.Lists["x_initiator_action"].Options = <?php echo json_encode($loan_report_search->initiator_action->Options()) ?>;
floan_reportsearch.Lists["x_document_checklist[]"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_discription","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"document_checklist"};
floan_reportsearch.Lists["x_document_checklist[]"].Data = "<?php echo $loan_report_search->document_checklist->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.Lists["x_recommender_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_reportsearch.Lists["x_recommender_action"].Options = <?php echo json_encode($loan_report_search->recommender_action->Options()) ?>;
floan_reportsearch.Lists["x_recommended_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_reportsearch.Lists["x_recommended_by"].Data = "<?php echo $loan_report_search->recommended_by->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.AutoSuggests["x_recommended_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_report_search->recommended_by->LookupFilterQuery(TRUE, "search"))) ?>;
floan_reportsearch.Lists["x_application_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_reportsearch.Lists["x_application_status"].Options = <?php echo json_encode($loan_report_search->application_status->Options()) ?>;
floan_reportsearch.Lists["x_duration_approved"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"duration_months"};
floan_reportsearch.Lists["x_duration_approved"].Data = "<?php echo $loan_report_search->duration_approved->LookupFilterQuery(FALSE, "search") ?>";
floan_reportsearch.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_reportsearch.Lists["x_approval_action"].Options = <?php echo json_encode($loan_report_search->approval_action->Options()) ?>;
floan_reportsearch.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_reportsearch.Lists["x_approved_by"].Data = "<?php echo $loan_report_search->approved_by->LookupFilterQuery(FALSE, "search") ?>";

// Form object for search
// Validate function for search

floan_reportsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_code");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->code->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_initiated");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->date_initiated->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_loan_amount");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->loan_amount->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_salary_permonth");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->salary_permonth->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_previous_loan");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->previous_loan->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_collected");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->date_collected->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_liquidated");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->date_liquidated->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_balance_remaining");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->balance_remaining->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_applicant_date");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->applicant_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_guarantor_date");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->guarantor_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_recommended_date");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->recommended_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_approved_amount");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->approved_amount->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_approval_date");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($loan_report->approval_date->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $loan_report_search->ShowPageHeader(); ?>
<?php
$loan_report_search->ShowMessage();
?>
<form name="floan_reportsearch" id="floan_reportsearch" class="<?php echo $loan_report_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($loan_report_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $loan_report_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="loan_report">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($loan_report_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($loan_report->code->Visible) { // code ?>
	<div id="r_code" class="form-group">
		<label for="x_code" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_code"><?php echo $loan_report->code->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_code" id="z_code" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->code->CellAttributes() ?>>
			<span id="el_loan_report_code">
<input type="text" data-table="loan_report" data-field="x_code" name="x_code" id="x_code" placeholder="<?php echo ew_HtmlEncode($loan_report->code->getPlaceHolder()) ?>" value="<?php echo $loan_report->code->EditValue ?>"<?php echo $loan_report->code->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->date_initiated->Visible) { // date_initiated ?>
	<div id="r_date_initiated" class="form-group">
		<label for="x_date_initiated" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_date_initiated"><?php echo $loan_report->date_initiated->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date_initiated" id="z_date_initiated" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->date_initiated->CellAttributes() ?>>
			<span id="el_loan_report_date_initiated">
<input type="text" data-table="loan_report" data-field="x_date_initiated" name="x_date_initiated" id="x_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->date_initiated->getPlaceHolder()) ?>" value="<?php echo $loan_report->date_initiated->EditValue ?>"<?php echo $loan_report->date_initiated->EditAttributes() ?>>
<?php if (!$loan_report->date_initiated->ReadOnly && !$loan_report->date_initiated->Disabled && !isset($loan_report->date_initiated->EditAttrs["readonly"]) && !isset($loan_report->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_reportsearch", "x_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_date_initiated">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_loan_report_date_initiated" class="btw1_date_initiated">
<input type="text" data-table="loan_report" data-field="x_date_initiated" name="y_date_initiated" id="y_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->date_initiated->getPlaceHolder()) ?>" value="<?php echo $loan_report->date_initiated->EditValue2 ?>"<?php echo $loan_report->date_initiated->EditAttributes() ?>>
<?php if (!$loan_report->date_initiated->ReadOnly && !$loan_report->date_initiated->Disabled && !isset($loan_report->date_initiated->EditAttrs["readonly"]) && !isset($loan_report->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_reportsearch", "y_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->refernce_id->Visible) { // refernce_id ?>
	<div id="r_refernce_id" class="form-group">
		<label for="x_refernce_id" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_refernce_id"><?php echo $loan_report->refernce_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_refernce_id" id="z_refernce_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->refernce_id->CellAttributes() ?>>
			<span id="el_loan_report_refernce_id">
<input type="text" data-table="loan_report" data-field="x_refernce_id" name="x_refernce_id" id="x_refernce_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->refernce_id->getPlaceHolder()) ?>" value="<?php echo $loan_report->refernce_id->EditValue ?>"<?php echo $loan_report->refernce_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->employee_name->Visible) { // employee_name ?>
	<div id="r_employee_name" class="form-group">
		<label for="x_employee_name" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_employee_name"><?php echo $loan_report->employee_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_employee_name" id="z_employee_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->employee_name->CellAttributes() ?>>
			<span id="el_loan_report_employee_name">
<input type="text" data-table="loan_report" data-field="x_employee_name" name="x_employee_name" id="x_employee_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->employee_name->getPlaceHolder()) ?>" value="<?php echo $loan_report->employee_name->EditValue ?>"<?php echo $loan_report->employee_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label for="x_address" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_address"><?php echo $loan_report->address->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_address" id="z_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->address->CellAttributes() ?>>
			<span id="el_loan_report_address">
<input type="text" data-table="loan_report" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="128" placeholder="<?php echo ew_HtmlEncode($loan_report->address->getPlaceHolder()) ?>" value="<?php echo $loan_report->address->EditValue ?>"<?php echo $loan_report->address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->mobile->Visible) { // mobile ?>
	<div id="r_mobile" class="form-group">
		<label for="x_mobile" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_mobile"><?php echo $loan_report->mobile->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile" id="z_mobile" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->mobile->CellAttributes() ?>>
			<span id="el_loan_report_mobile">
<input type="text" data-table="loan_report" data-field="x_mobile" name="x_mobile" id="x_mobile" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($loan_report->mobile->getPlaceHolder()) ?>" value="<?php echo $loan_report->mobile->EditValue ?>"<?php echo $loan_report->mobile->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label for="x_department" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_department"><?php echo $loan_report->department->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_department" id="z_department" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->department->CellAttributes() ?>>
			<span id="el_loan_report_department">
<select data-table="loan_report" data-field="x_department" data-value-separator="<?php echo $loan_report->department->DisplayValueSeparatorAttribute() ?>" id="x_department" name="x_department"<?php echo $loan_report->department->EditAttributes() ?>>
<?php echo $loan_report->department->SelectOptionListHtml("x_department") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->loan_amount->Visible) { // loan_amount ?>
	<div id="r_loan_amount" class="form-group">
		<label for="x_loan_amount" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_loan_amount"><?php echo $loan_report->loan_amount->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_loan_amount" id="z_loan_amount" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->loan_amount->CellAttributes() ?>>
			<span id="el_loan_report_loan_amount">
<input type="text" data-table="loan_report" data-field="x_loan_amount" name="x_loan_amount" id="x_loan_amount" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->loan_amount->getPlaceHolder()) ?>" value="<?php echo $loan_report->loan_amount->EditValue ?>"<?php echo $loan_report->loan_amount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->amount_inwords->Visible) { // amount_inwords ?>
	<div id="r_amount_inwords" class="form-group">
		<label for="x_amount_inwords" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_amount_inwords"><?php echo $loan_report->amount_inwords->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_amount_inwords" id="z_amount_inwords" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->amount_inwords->CellAttributes() ?>>
			<span id="el_loan_report_amount_inwords">
<input type="text" data-table="loan_report" data-field="x_amount_inwords" name="x_amount_inwords" id="x_amount_inwords" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->amount_inwords->getPlaceHolder()) ?>" value="<?php echo $loan_report->amount_inwords->EditValue ?>"<?php echo $loan_report->amount_inwords->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->purpose->Visible) { // purpose ?>
	<div id="r_purpose" class="form-group">
		<label for="x_purpose" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_purpose"><?php echo $loan_report->purpose->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_purpose" id="z_purpose" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->purpose->CellAttributes() ?>>
			<span id="el_loan_report_purpose">
<input type="text" data-table="loan_report" data-field="x_purpose" name="x_purpose" id="x_purpose" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->purpose->getPlaceHolder()) ?>" value="<?php echo $loan_report->purpose->EditValue ?>"<?php echo $loan_report->purpose->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->repayment_period->Visible) { // repayment_period ?>
	<div id="r_repayment_period" class="form-group">
		<label for="x_repayment_period" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_repayment_period"><?php echo $loan_report->repayment_period->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_repayment_period" id="z_repayment_period" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->repayment_period->CellAttributes() ?>>
			<span id="el_loan_report_repayment_period">
<select data-table="loan_report" data-field="x_repayment_period" data-value-separator="<?php echo $loan_report->repayment_period->DisplayValueSeparatorAttribute() ?>" id="x_repayment_period" name="x_repayment_period"<?php echo $loan_report->repayment_period->EditAttributes() ?>>
<?php echo $loan_report->repayment_period->SelectOptionListHtml("x_repayment_period") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->salary_permonth->Visible) { // salary_permonth ?>
	<div id="r_salary_permonth" class="form-group">
		<label for="x_salary_permonth" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_salary_permonth"><?php echo $loan_report->salary_permonth->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_salary_permonth" id="z_salary_permonth" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->salary_permonth->CellAttributes() ?>>
			<span id="el_loan_report_salary_permonth">
<input type="text" data-table="loan_report" data-field="x_salary_permonth" name="x_salary_permonth" id="x_salary_permonth" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->salary_permonth->getPlaceHolder()) ?>" value="<?php echo $loan_report->salary_permonth->EditValue ?>"<?php echo $loan_report->salary_permonth->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->previous_loan->Visible) { // previous_loan ?>
	<div id="r_previous_loan" class="form-group">
		<label for="x_previous_loan" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_previous_loan"><?php echo $loan_report->previous_loan->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_previous_loan" id="z_previous_loan" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->previous_loan->CellAttributes() ?>>
			<span id="el_loan_report_previous_loan">
<input type="text" data-table="loan_report" data-field="x_previous_loan" name="x_previous_loan" id="x_previous_loan" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->previous_loan->getPlaceHolder()) ?>" value="<?php echo $loan_report->previous_loan->EditValue ?>"<?php echo $loan_report->previous_loan->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->date_collected->Visible) { // date_collected ?>
	<div id="r_date_collected" class="form-group">
		<label for="x_date_collected" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_date_collected"><?php echo $loan_report->date_collected->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_collected" id="z_date_collected" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->date_collected->CellAttributes() ?>>
			<span id="el_loan_report_date_collected">
<input type="text" data-table="loan_report" data-field="x_date_collected" name="x_date_collected" id="x_date_collected" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->date_collected->getPlaceHolder()) ?>" value="<?php echo $loan_report->date_collected->EditValue ?>"<?php echo $loan_report->date_collected->EditAttributes() ?>>
<?php if (!$loan_report->date_collected->ReadOnly && !$loan_report->date_collected->Disabled && !isset($loan_report->date_collected->EditAttrs["readonly"]) && !isset($loan_report->date_collected->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_reportsearch", "x_date_collected", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->date_liquidated->Visible) { // date_liquidated ?>
	<div id="r_date_liquidated" class="form-group">
		<label for="x_date_liquidated" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_date_liquidated"><?php echo $loan_report->date_liquidated->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_liquidated" id="z_date_liquidated" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->date_liquidated->CellAttributes() ?>>
			<span id="el_loan_report_date_liquidated">
<input type="text" data-table="loan_report" data-field="x_date_liquidated" name="x_date_liquidated" id="x_date_liquidated" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->date_liquidated->getPlaceHolder()) ?>" value="<?php echo $loan_report->date_liquidated->EditValue ?>"<?php echo $loan_report->date_liquidated->EditAttributes() ?>>
<?php if (!$loan_report->date_liquidated->ReadOnly && !$loan_report->date_liquidated->Disabled && !isset($loan_report->date_liquidated->EditAttrs["readonly"]) && !isset($loan_report->date_liquidated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("floan_reportsearch", "x_date_liquidated", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->balance_remaining->Visible) { // balance_remaining ?>
	<div id="r_balance_remaining" class="form-group">
		<label for="x_balance_remaining" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_balance_remaining"><?php echo $loan_report->balance_remaining->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_balance_remaining" id="z_balance_remaining" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->balance_remaining->CellAttributes() ?>>
			<span id="el_loan_report_balance_remaining">
<input type="text" data-table="loan_report" data-field="x_balance_remaining" name="x_balance_remaining" id="x_balance_remaining" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->balance_remaining->getPlaceHolder()) ?>" value="<?php echo $loan_report->balance_remaining->EditValue ?>"<?php echo $loan_report->balance_remaining->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->applicant_date->Visible) { // applicant_date ?>
	<div id="r_applicant_date" class="form-group">
		<label for="x_applicant_date" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_applicant_date"><?php echo $loan_report->applicant_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_applicant_date" id="z_applicant_date" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->applicant_date->CellAttributes() ?>>
			<span id="el_loan_report_applicant_date">
<input type="text" data-table="loan_report" data-field="x_applicant_date" data-format="14" name="x_applicant_date" id="x_applicant_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->applicant_date->getPlaceHolder()) ?>" value="<?php echo $loan_report->applicant_date->EditValue ?>"<?php echo $loan_report->applicant_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->applicant_passport->Visible) { // applicant_passport ?>
	<div id="r_applicant_passport" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_applicant_passport"><?php echo $loan_report->applicant_passport->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_applicant_passport" id="z_applicant_passport" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->applicant_passport->CellAttributes() ?>>
			<span id="el_loan_report_applicant_passport">
<input type="text" data-table="loan_report" data-field="x_applicant_passport" name="x_applicant_passport" id="x_applicant_passport" placeholder="<?php echo ew_HtmlEncode($loan_report->applicant_passport->getPlaceHolder()) ?>" value="<?php echo $loan_report->applicant_passport->EditValue ?>"<?php echo $loan_report->applicant_passport->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->guarantor_name->Visible) { // guarantor_name ?>
	<div id="r_guarantor_name" class="form-group">
		<label for="x_guarantor_name" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_guarantor_name"><?php echo $loan_report->guarantor_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_guarantor_name" id="z_guarantor_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->guarantor_name->CellAttributes() ?>>
			<span id="el_loan_report_guarantor_name">
<input type="text" data-table="loan_report" data-field="x_guarantor_name" name="x_guarantor_name" id="x_guarantor_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->guarantor_name->getPlaceHolder()) ?>" value="<?php echo $loan_report->guarantor_name->EditValue ?>"<?php echo $loan_report->guarantor_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->guarantor_address->Visible) { // guarantor_address ?>
	<div id="r_guarantor_address" class="form-group">
		<label for="x_guarantor_address" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_guarantor_address"><?php echo $loan_report->guarantor_address->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_guarantor_address" id="z_guarantor_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->guarantor_address->CellAttributes() ?>>
			<span id="el_loan_report_guarantor_address">
<input type="text" data-table="loan_report" data-field="x_guarantor_address" name="x_guarantor_address" id="x_guarantor_address" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($loan_report->guarantor_address->getPlaceHolder()) ?>" value="<?php echo $loan_report->guarantor_address->EditValue ?>"<?php echo $loan_report->guarantor_address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->guarantor_mobile->Visible) { // guarantor_mobile ?>
	<div id="r_guarantor_mobile" class="form-group">
		<label for="x_guarantor_mobile" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_guarantor_mobile"><?php echo $loan_report->guarantor_mobile->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_guarantor_mobile" id="z_guarantor_mobile" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->guarantor_mobile->CellAttributes() ?>>
			<span id="el_loan_report_guarantor_mobile">
<input type="text" data-table="loan_report" data-field="x_guarantor_mobile" name="x_guarantor_mobile" id="x_guarantor_mobile" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($loan_report->guarantor_mobile->getPlaceHolder()) ?>" value="<?php echo $loan_report->guarantor_mobile->EditValue ?>"<?php echo $loan_report->guarantor_mobile->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->guarantor_department->Visible) { // guarantor_department ?>
	<div id="r_guarantor_department" class="form-group">
		<label for="x_guarantor_department" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_guarantor_department"><?php echo $loan_report->guarantor_department->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_guarantor_department" id="z_guarantor_department" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->guarantor_department->CellAttributes() ?>>
			<span id="el_loan_report_guarantor_department">
<select data-table="loan_report" data-field="x_guarantor_department" data-value-separator="<?php echo $loan_report->guarantor_department->DisplayValueSeparatorAttribute() ?>" id="x_guarantor_department" name="x_guarantor_department"<?php echo $loan_report->guarantor_department->EditAttributes() ?>>
<?php echo $loan_report->guarantor_department->SelectOptionListHtml("x_guarantor_department") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->account_no->Visible) { // account_no ?>
	<div id="r_account_no" class="form-group">
		<label for="x_account_no" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_account_no"><?php echo $loan_report->account_no->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_account_no" id="z_account_no" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->account_no->CellAttributes() ?>>
			<span id="el_loan_report_account_no">
<input type="text" data-table="loan_report" data-field="x_account_no" name="x_account_no" id="x_account_no" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($loan_report->account_no->getPlaceHolder()) ?>" value="<?php echo $loan_report->account_no->EditValue ?>"<?php echo $loan_report->account_no->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->bank_name->Visible) { // bank_name ?>
	<div id="r_bank_name" class="form-group">
		<label for="x_bank_name" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_bank_name"><?php echo $loan_report->bank_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_bank_name" id="z_bank_name" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->bank_name->CellAttributes() ?>>
			<span id="el_loan_report_bank_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_bank_name"><?php echo (strval($loan_report->bank_name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $loan_report->bank_name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($loan_report->bank_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_bank_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($loan_report->bank_name->ReadOnly || $loan_report->bank_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="loan_report" data-field="x_bank_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $loan_report->bank_name->DisplayValueSeparatorAttribute() ?>" name="x_bank_name" id="x_bank_name" value="<?php echo $loan_report->bank_name->AdvancedSearch->SearchValue ?>"<?php echo $loan_report->bank_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->employers_name->Visible) { // employers_name ?>
	<div id="r_employers_name" class="form-group">
		<label for="x_employers_name" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_employers_name"><?php echo $loan_report->employers_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_employers_name" id="z_employers_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->employers_name->CellAttributes() ?>>
			<span id="el_loan_report_employers_name">
<input type="text" data-table="loan_report" data-field="x_employers_name" name="x_employers_name" id="x_employers_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->employers_name->getPlaceHolder()) ?>" value="<?php echo $loan_report->employers_name->EditValue ?>"<?php echo $loan_report->employers_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->employers_address->Visible) { // employers_address ?>
	<div id="r_employers_address" class="form-group">
		<label for="x_employers_address" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_employers_address"><?php echo $loan_report->employers_address->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_employers_address" id="z_employers_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->employers_address->CellAttributes() ?>>
			<span id="el_loan_report_employers_address">
<input type="text" data-table="loan_report" data-field="x_employers_address" name="x_employers_address" id="x_employers_address" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->employers_address->getPlaceHolder()) ?>" value="<?php echo $loan_report->employers_address->EditValue ?>"<?php echo $loan_report->employers_address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->employers_mobile->Visible) { // employers_mobile ?>
	<div id="r_employers_mobile" class="form-group">
		<label for="x_employers_mobile" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_employers_mobile"><?php echo $loan_report->employers_mobile->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_employers_mobile" id="z_employers_mobile" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->employers_mobile->CellAttributes() ?>>
			<span id="el_loan_report_employers_mobile">
<input type="text" data-table="loan_report" data-field="x_employers_mobile" name="x_employers_mobile" id="x_employers_mobile" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($loan_report->employers_mobile->getPlaceHolder()) ?>" value="<?php echo $loan_report->employers_mobile->EditValue ?>"<?php echo $loan_report->employers_mobile->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->guarantor_date->Visible) { // guarantor_date ?>
	<div id="r_guarantor_date" class="form-group">
		<label for="x_guarantor_date" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_guarantor_date"><?php echo $loan_report->guarantor_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_guarantor_date" id="z_guarantor_date" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->guarantor_date->CellAttributes() ?>>
			<span id="el_loan_report_guarantor_date">
<input type="text" data-table="loan_report" data-field="x_guarantor_date" data-format="14" name="x_guarantor_date" id="x_guarantor_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->guarantor_date->getPlaceHolder()) ?>" value="<?php echo $loan_report->guarantor_date->EditValue ?>"<?php echo $loan_report->guarantor_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->guarantor_passport->Visible) { // guarantor_passport ?>
	<div id="r_guarantor_passport" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_guarantor_passport"><?php echo $loan_report->guarantor_passport->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_guarantor_passport" id="z_guarantor_passport" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->guarantor_passport->CellAttributes() ?>>
			<span id="el_loan_report_guarantor_passport">
<input type="text" data-table="loan_report" data-field="x_guarantor_passport" name="x_guarantor_passport" id="x_guarantor_passport" placeholder="<?php echo ew_HtmlEncode($loan_report->guarantor_passport->getPlaceHolder()) ?>" value="<?php echo $loan_report->guarantor_passport->EditValue ?>"<?php echo $loan_report->guarantor_passport->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_status"><?php echo $loan_report->status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->status->CellAttributes() ?>>
			<span id="el_loan_report_status">
<?php
$wrkonchange = trim(" " . @$loan_report->status->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$loan_report->status->EditAttrs["onchange"] = "";
?>
<span id="as_x_status" style="white-space: nowrap; z-index: 8700">
	<input type="text" name="sv_x_status" id="sv_x_status" value="<?php echo $loan_report->status->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->status->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($loan_report->status->getPlaceHolder()) ?>"<?php echo $loan_report->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="loan_report" data-field="x_status" data-value-separator="<?php echo $loan_report->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($loan_report->status->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
floan_reportsearch.CreateAutoSuggest({"id":"x_status","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_initiator_action"><?php echo $loan_report->initiator_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_initiator_action" id="z_initiator_action" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->initiator_action->CellAttributes() ?>>
			<span id="el_loan_report_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="loan_report" data-field="x_initiator_action" data-value-separator="<?php echo $loan_report->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $loan_report->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_report->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label for="x_initiator_comment" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_initiator_comment"><?php echo $loan_report->initiator_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_initiator_comment" id="z_initiator_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->initiator_comment->CellAttributes() ?>>
			<span id="el_loan_report_initiator_comment">
<input type="text" data-table="loan_report" data-field="x_initiator_comment" name="x_initiator_comment" id="x_initiator_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->initiator_comment->getPlaceHolder()) ?>" value="<?php echo $loan_report->initiator_comment->EditValue ?>"<?php echo $loan_report->initiator_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->recommended_date->Visible) { // recommended_date ?>
	<div id="r_recommended_date" class="form-group">
		<label for="x_recommended_date" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_recommended_date"><?php echo $loan_report->recommended_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_recommended_date" id="z_recommended_date" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->recommended_date->CellAttributes() ?>>
			<span id="el_loan_report_recommended_date">
<input type="text" data-table="loan_report" data-field="x_recommended_date" data-format="14" name="x_recommended_date" id="x_recommended_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->recommended_date->getPlaceHolder()) ?>" value="<?php echo $loan_report->recommended_date->EditValue ?>"<?php echo $loan_report->recommended_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->document_checklist->Visible) { // document_checklist ?>
	<div id="r_document_checklist" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_document_checklist"><?php echo $loan_report->document_checklist->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_document_checklist" id="z_document_checklist" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->document_checklist->CellAttributes() ?>>
			<span id="el_loan_report_document_checklist">
<div id="tp_x_document_checklist" class="ewTemplate"><input type="checkbox" data-table="loan_report" data-field="x_document_checklist" data-value-separator="<?php echo $loan_report->document_checklist->DisplayValueSeparatorAttribute() ?>" name="x_document_checklist[]" id="x_document_checklist[]" value="{value}"<?php echo $loan_report->document_checklist->EditAttributes() ?>></div>
<div id="dsl_x_document_checklist" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_report->document_checklist->CheckBoxListHtml(FALSE, "x_document_checklist[]") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->recommender_action->Visible) { // recommender_action ?>
	<div id="r_recommender_action" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_recommender_action"><?php echo $loan_report->recommender_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_recommender_action" id="z_recommender_action" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->recommender_action->CellAttributes() ?>>
			<span id="el_loan_report_recommender_action">
<div id="tp_x_recommender_action" class="ewTemplate"><input type="radio" data-table="loan_report" data-field="x_recommender_action" data-value-separator="<?php echo $loan_report->recommender_action->DisplayValueSeparatorAttribute() ?>" name="x_recommender_action" id="x_recommender_action" value="{value}"<?php echo $loan_report->recommender_action->EditAttributes() ?>></div>
<div id="dsl_x_recommender_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_report->recommender_action->RadioButtonListHtml(FALSE, "x_recommender_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->recommender_comment->Visible) { // recommender_comment ?>
	<div id="r_recommender_comment" class="form-group">
		<label for="x_recommender_comment" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_recommender_comment"><?php echo $loan_report->recommender_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_recommender_comment" id="z_recommender_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->recommender_comment->CellAttributes() ?>>
			<span id="el_loan_report_recommender_comment">
<input type="text" data-table="loan_report" data-field="x_recommender_comment" name="x_recommender_comment" id="x_recommender_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->recommender_comment->getPlaceHolder()) ?>" value="<?php echo $loan_report->recommender_comment->EditValue ?>"<?php echo $loan_report->recommender_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->recommended_by->Visible) { // recommended_by ?>
	<div id="r_recommended_by" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_recommended_by"><?php echo $loan_report->recommended_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_recommended_by" id="z_recommended_by" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->recommended_by->CellAttributes() ?>>
			<span id="el_loan_report_recommended_by">
<?php
$wrkonchange = trim(" " . @$loan_report->recommended_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$loan_report->recommended_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_recommended_by" style="white-space: nowrap; z-index: 8630">
	<input type="text" name="sv_x_recommended_by" id="sv_x_recommended_by" value="<?php echo $loan_report->recommended_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->recommended_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($loan_report->recommended_by->getPlaceHolder()) ?>"<?php echo $loan_report->recommended_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="loan_report" data-field="x_recommended_by" data-value-separator="<?php echo $loan_report->recommended_by->DisplayValueSeparatorAttribute() ?>" name="x_recommended_by" id="x_recommended_by" value="<?php echo ew_HtmlEncode($loan_report->recommended_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
floan_reportsearch.CreateAutoSuggest({"id":"x_recommended_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->application_status->Visible) { // application_status ?>
	<div id="r_application_status" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_application_status"><?php echo $loan_report->application_status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_application_status" id="z_application_status" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->application_status->CellAttributes() ?>>
			<span id="el_loan_report_application_status">
<div id="tp_x_application_status" class="ewTemplate"><input type="radio" data-table="loan_report" data-field="x_application_status" data-value-separator="<?php echo $loan_report->application_status->DisplayValueSeparatorAttribute() ?>" name="x_application_status" id="x_application_status" value="{value}"<?php echo $loan_report->application_status->EditAttributes() ?>></div>
<div id="dsl_x_application_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_report->application_status->RadioButtonListHtml(FALSE, "x_application_status") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->approved_amount->Visible) { // approved_amount ?>
	<div id="r_approved_amount" class="form-group">
		<label for="x_approved_amount" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_approved_amount"><?php echo $loan_report->approved_amount->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_amount" id="z_approved_amount" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->approved_amount->CellAttributes() ?>>
			<span id="el_loan_report_approved_amount">
<input type="text" data-table="loan_report" data-field="x_approved_amount" name="x_approved_amount" id="x_approved_amount" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->approved_amount->getPlaceHolder()) ?>" value="<?php echo $loan_report->approved_amount->EditValue ?>"<?php echo $loan_report->approved_amount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->duration_approved->Visible) { // duration_approved ?>
	<div id="r_duration_approved" class="form-group">
		<label for="x_duration_approved" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_duration_approved"><?php echo $loan_report->duration_approved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_duration_approved" id="z_duration_approved" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->duration_approved->CellAttributes() ?>>
			<span id="el_loan_report_duration_approved">
<select data-table="loan_report" data-field="x_duration_approved" data-value-separator="<?php echo $loan_report->duration_approved->DisplayValueSeparatorAttribute() ?>" id="x_duration_approved" name="x_duration_approved"<?php echo $loan_report->duration_approved->EditAttributes() ?>>
<?php echo $loan_report->duration_approved->SelectOptionListHtml("x_duration_approved") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->approval_date->Visible) { // approval_date ?>
	<div id="r_approval_date" class="form-group">
		<label for="x_approval_date" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_approval_date"><?php echo $loan_report->approval_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approval_date" id="z_approval_date" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->approval_date->CellAttributes() ?>>
			<span id="el_loan_report_approval_date">
<input type="text" data-table="loan_report" data-field="x_approval_date" data-format="17" name="x_approval_date" id="x_approval_date" size="30" placeholder="<?php echo ew_HtmlEncode($loan_report->approval_date->getPlaceHolder()) ?>" value="<?php echo $loan_report->approval_date->EditValue ?>"<?php echo $loan_report->approval_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->approval_action->Visible) { // approval_action ?>
	<div id="r_approval_action" class="form-group">
		<label class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_approval_action"><?php echo $loan_report->approval_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approval_action" id="z_approval_action" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->approval_action->CellAttributes() ?>>
			<span id="el_loan_report_approval_action">
<div id="tp_x_approval_action" class="ewTemplate"><input type="radio" data-table="loan_report" data-field="x_approval_action" data-value-separator="<?php echo $loan_report->approval_action->DisplayValueSeparatorAttribute() ?>" name="x_approval_action" id="x_approval_action" value="{value}"<?php echo $loan_report->approval_action->EditAttributes() ?>></div>
<div id="dsl_x_approval_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $loan_report->approval_action->RadioButtonListHtml(FALSE, "x_approval_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->approval_comment->Visible) { // approval_comment ?>
	<div id="r_approval_comment" class="form-group">
		<label for="x_approval_comment" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_approval_comment"><?php echo $loan_report->approval_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approval_comment" id="z_approval_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->approval_comment->CellAttributes() ?>>
			<span id="el_loan_report_approval_comment">
<input type="text" data-table="loan_report" data-field="x_approval_comment" name="x_approval_comment" id="x_approval_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_report->approval_comment->getPlaceHolder()) ?>" value="<?php echo $loan_report->approval_comment->EditValue ?>"<?php echo $loan_report->approval_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($loan_report->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label for="x_approved_by" class="<?php echo $loan_report_search->LeftColumnClass ?>"><span id="elh_loan_report_approved_by"><?php echo $loan_report->approved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_by" id="z_approved_by" value="="></p>
		</label>
		<div class="<?php echo $loan_report_search->RightColumnClass ?>"><div<?php echo $loan_report->approved_by->CellAttributes() ?>>
			<span id="el_loan_report_approved_by">
<select data-table="loan_report" data-field="x_approved_by" data-value-separator="<?php echo $loan_report->approved_by->DisplayValueSeparatorAttribute() ?>" id="x_approved_by" name="x_approved_by"<?php echo $loan_report->approved_by->EditAttributes() ?>>
<?php echo $loan_report->approved_by->SelectOptionListHtml("x_approved_by") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$loan_report_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $loan_report_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
floan_reportsearch.Init();
</script>
<?php
$loan_report_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$loan_report_search->Page_Terminate();
?>
