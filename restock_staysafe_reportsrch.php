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

$restock_staysafe_report_search = NULL; // Initialize page object first

class crestock_staysafe_report_search extends crestock_staysafe_report {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'restock_staysafe_report';

	// Page object name
	var $PageObjName = 'restock_staysafe_report_search';

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
			define("EW_PAGE_ID", 'search');

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
		if (!$Security->CanSearch()) {
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
		$this->code->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->code->Visible = FALSE;
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
						$sSrchStr = "restock_staysafe_reportlist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->date_restocked); // date_restocked
		$this->BuildSearchUrl($sSrchUrl, $this->reference_id); // reference_id
		$this->BuildSearchUrl($sSrchUrl, $this->material_name); // material_name
		$this->BuildSearchUrl($sSrchUrl, $this->type); // type
		$this->BuildSearchUrl($sSrchUrl, $this->capacity); // capacity
		$this->BuildSearchUrl($sSrchUrl, $this->stock_balance); // stock_balance
		$this->BuildSearchUrl($sSrchUrl, $this->quantity); // quantity
		$this->BuildSearchUrl($sSrchUrl, $this->statuss); // statuss
		$this->BuildSearchUrl($sSrchUrl, $this->restocked_action); // restocked_action
		$this->BuildSearchUrl($sSrchUrl, $this->restocked_comment); // restocked_comment
		$this->BuildSearchUrl($sSrchUrl, $this->restocked_by); // restocked_by
		$this->BuildSearchUrl($sSrchUrl, $this->approver_date); // approver_date
		$this->BuildSearchUrl($sSrchUrl, $this->approver_action); // approver_action
		$this->BuildSearchUrl($sSrchUrl, $this->approver_comment); // approver_comment
		$this->BuildSearchUrl($sSrchUrl, $this->approved_by); // approved_by
		$this->BuildSearchUrl($sSrchUrl, $this->verified_date); // verified_date
		$this->BuildSearchUrl($sSrchUrl, $this->verified_action); // verified_action
		$this->BuildSearchUrl($sSrchUrl, $this->verified_comment); // verified_comment
		$this->BuildSearchUrl($sSrchUrl, $this->verified_by); // verified_by
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

		// date_restocked
		$this->date_restocked->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_restocked");
		$this->date_restocked->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_restocked");
		$this->date_restocked->AdvancedSearch->SearchCondition = $objForm->GetValue("v_date_restocked");
		$this->date_restocked->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_date_restocked");
		$this->date_restocked->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_date_restocked");

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_reference_id");
		$this->reference_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_reference_id");

		// material_name
		$this->material_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_material_name");
		$this->material_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_material_name");

		// type
		$this->type->AdvancedSearch->SearchValue = $objForm->GetValue("x_type");
		$this->type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_type");

		// capacity
		$this->capacity->AdvancedSearch->SearchValue = $objForm->GetValue("x_capacity");
		$this->capacity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_capacity");

		// stock_balance
		$this->stock_balance->AdvancedSearch->SearchValue = $objForm->GetValue("x_stock_balance");
		$this->stock_balance->AdvancedSearch->SearchOperator = $objForm->GetValue("z_stock_balance");

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity");
		$this->quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity");

		// statuss
		$this->statuss->AdvancedSearch->SearchValue = $objForm->GetValue("x_statuss");
		$this->statuss->AdvancedSearch->SearchOperator = $objForm->GetValue("z_statuss");

		// restocked_action
		$this->restocked_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_restocked_action");
		$this->restocked_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_restocked_action");

		// restocked_comment
		$this->restocked_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_restocked_comment");
		$this->restocked_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_restocked_comment");

		// restocked_by
		$this->restocked_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_restocked_by");
		$this->restocked_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_restocked_by");

		// approver_date
		$this->approver_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_date");
		$this->approver_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_date");

		// approver_action
		$this->approver_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_action");
		$this->approver_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_action");

		// approver_comment
		$this->approver_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_comment");
		$this->approver_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_comment");

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_approved_by");
		$this->approved_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approved_by");

		// verified_date
		$this->verified_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_verified_date");
		$this->verified_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_verified_date");

		// verified_action
		$this->verified_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_verified_action");
		$this->verified_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_verified_action");

		// verified_comment
		$this->verified_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_verified_comment");
		$this->verified_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_verified_comment");

		// verified_by
		$this->verified_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_verified_by");
		$this->verified_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_verified_by");
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

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
			$this->code->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = ew_HtmlEncode($this->code->AdvancedSearch->SearchValue);
			$this->code->PlaceHolder = ew_RemoveHtml($this->code->FldCaption());

			// date_restocked
			$this->date_restocked->EditAttrs["class"] = "form-control";
			$this->date_restocked->EditCustomAttributes = "";
			$this->date_restocked->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_restocked->AdvancedSearch->SearchValue, 14), 14));
			$this->date_restocked->PlaceHolder = ew_RemoveHtml($this->date_restocked->FldCaption());
			$this->date_restocked->EditAttrs["class"] = "form-control";
			$this->date_restocked->EditCustomAttributes = "";
			$this->date_restocked->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_restocked->AdvancedSearch->SearchValue2, 14), 14));
			$this->date_restocked->PlaceHolder = ew_RemoveHtml($this->date_restocked->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->AdvancedSearch->SearchValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// material_name
			$this->material_name->EditCustomAttributes = "";
			if (trim(strval($this->material_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
				$this->material_name->AdvancedSearch->ViewValue = $this->material_name->DisplayValue($arwrk);
			} else {
				$this->material_name->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->material_name->EditValue = $arwrk;

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->AdvancedSearch->SearchValue);
			$this->type->PlaceHolder = ew_RemoveHtml($this->type->FldCaption());

			// capacity
			$this->capacity->EditAttrs["class"] = "form-control";
			$this->capacity->EditCustomAttributes = "";
			$this->capacity->EditValue = ew_HtmlEncode($this->capacity->AdvancedSearch->SearchValue);
			$this->capacity->PlaceHolder = ew_RemoveHtml($this->capacity->FldCaption());

			// stock_balance
			$this->stock_balance->EditAttrs["class"] = "form-control";
			$this->stock_balance->EditCustomAttributes = "";
			$this->stock_balance->EditValue = ew_HtmlEncode($this->stock_balance->AdvancedSearch->SearchValue);
			$this->stock_balance->PlaceHolder = ew_RemoveHtml($this->stock_balance->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->AdvancedSearch->SearchValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// statuss
			$this->statuss->EditAttrs["class"] = "form-control";
			$this->statuss->EditCustomAttributes = "";
			if (trim(strval($this->statuss->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->restocked_comment->EditValue = ew_HtmlEncode($this->restocked_comment->AdvancedSearch->SearchValue);
			$this->restocked_comment->PlaceHolder = ew_RemoveHtml($this->restocked_comment->FldCaption());

			// restocked_by
			$this->restocked_by->EditAttrs["class"] = "form-control";
			$this->restocked_by->EditCustomAttributes = "";
			$this->restocked_by->EditValue = ew_HtmlEncode($this->restocked_by->AdvancedSearch->SearchValue);
			if (strval($this->restocked_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->restocked_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->restocked_by->EditValue = ew_HtmlEncode($this->restocked_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->restocked_by->EditValue = NULL;
			}
			$this->restocked_by->PlaceHolder = ew_RemoveHtml($this->restocked_by->FldCaption());

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->approver_date->AdvancedSearch->SearchValue, 14), 14));
			$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

			// approver_action
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

			// approver_comment
			$this->approver_comment->EditAttrs["class"] = "form-control";
			$this->approver_comment->EditCustomAttributes = "";
			$this->approver_comment->EditValue = ew_HtmlEncode($this->approver_comment->AdvancedSearch->SearchValue);
			$this->approver_comment->PlaceHolder = ew_RemoveHtml($this->approver_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->AdvancedSearch->SearchValue);
			if (strval($this->approved_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// verified_date
			$this->verified_date->EditAttrs["class"] = "form-control";
			$this->verified_date->EditCustomAttributes = "";
			$this->verified_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->verified_date->AdvancedSearch->SearchValue, 14), 14));
			$this->verified_date->PlaceHolder = ew_RemoveHtml($this->verified_date->FldCaption());

			// verified_action
			$this->verified_action->EditCustomAttributes = "";
			$this->verified_action->EditValue = $this->verified_action->Options(FALSE);

			// verified_comment
			$this->verified_comment->EditAttrs["class"] = "form-control";
			$this->verified_comment->EditCustomAttributes = "";
			$this->verified_comment->EditValue = ew_HtmlEncode($this->verified_comment->AdvancedSearch->SearchValue);
			$this->verified_comment->PlaceHolder = ew_RemoveHtml($this->verified_comment->FldCaption());

			// verified_by
			$this->verified_by->EditAttrs["class"] = "form-control";
			$this->verified_by->EditCustomAttributes = "";
			$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->AdvancedSearch->SearchValue);
			if (strval($this->verified_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->verified_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->verified_by->EditValue = NULL;
			}
			$this->verified_by->PlaceHolder = ew_RemoveHtml($this->verified_by->FldCaption());
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
		if (!ew_CheckInteger($this->approved_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckInteger($this->verified_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->verified_by->FldErrMsg());
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
		$this->date_restocked->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->material_name->AdvancedSearch->Load();
		$this->type->AdvancedSearch->Load();
		$this->capacity->AdvancedSearch->Load();
		$this->stock_balance->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->statuss->AdvancedSearch->Load();
		$this->restocked_action->AdvancedSearch->Load();
		$this->restocked_comment->AdvancedSearch->Load();
		$this->restocked_by->AdvancedSearch->Load();
		$this->approver_date->AdvancedSearch->Load();
		$this->approver_action->AdvancedSearch->Load();
		$this->approver_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->verified_date->AdvancedSearch->Load();
		$this->verified_action->AdvancedSearch->Load();
		$this->verified_comment->AdvancedSearch->Load();
		$this->verified_by->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("restock_staysafe_reportlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($restock_staysafe_report_search)) $restock_staysafe_report_search = new crestock_staysafe_report_search();

// Page init
$restock_staysafe_report_search->Page_Init();

// Page main
$restock_staysafe_report_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$restock_staysafe_report_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($restock_staysafe_report_search->IsModal) { ?>
var CurrentAdvancedSearchForm = frestock_staysafe_reportsearch = new ew_Form("frestock_staysafe_reportsearch", "search");
<?php } else { ?>
var CurrentForm = frestock_staysafe_reportsearch = new ew_Form("frestock_staysafe_reportsearch", "search");
<?php } ?>

// Form_CustomValidate event
frestock_staysafe_reportsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frestock_staysafe_reportsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frestock_staysafe_reportsearch.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory_staysafe"};
frestock_staysafe_reportsearch.Lists["x_material_name"].Data = "<?php echo $restock_staysafe_report_search->material_name->LookupFilterQuery(FALSE, "search") ?>";
frestock_staysafe_reportsearch.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
frestock_staysafe_reportsearch.Lists["x_statuss"].Data = "<?php echo $restock_staysafe_report_search->statuss->LookupFilterQuery(FALSE, "search") ?>";
frestock_staysafe_reportsearch.Lists["x_restocked_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frestock_staysafe_reportsearch.Lists["x_restocked_action"].Options = <?php echo json_encode($restock_staysafe_report_search->restocked_action->Options()) ?>;
frestock_staysafe_reportsearch.Lists["x_restocked_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_staysafe_reportsearch.Lists["x_restocked_by"].Data = "<?php echo $restock_staysafe_report_search->restocked_by->LookupFilterQuery(FALSE, "search") ?>";
frestock_staysafe_reportsearch.AutoSuggests["x_restocked_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_staysafe_report_search->restocked_by->LookupFilterQuery(TRUE, "search"))) ?>;
frestock_staysafe_reportsearch.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frestock_staysafe_reportsearch.Lists["x_approver_action"].Options = <?php echo json_encode($restock_staysafe_report_search->approver_action->Options()) ?>;
frestock_staysafe_reportsearch.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_staysafe_reportsearch.Lists["x_approved_by"].Data = "<?php echo $restock_staysafe_report_search->approved_by->LookupFilterQuery(FALSE, "search") ?>";
frestock_staysafe_reportsearch.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_staysafe_report_search->approved_by->LookupFilterQuery(TRUE, "search"))) ?>;
frestock_staysafe_reportsearch.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frestock_staysafe_reportsearch.Lists["x_verified_action"].Options = <?php echo json_encode($restock_staysafe_report_search->verified_action->Options()) ?>;
frestock_staysafe_reportsearch.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_staysafe_reportsearch.Lists["x_verified_by"].Data = "<?php echo $restock_staysafe_report_search->verified_by->LookupFilterQuery(FALSE, "search") ?>";
frestock_staysafe_reportsearch.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_staysafe_report_search->verified_by->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

frestock_staysafe_reportsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_code");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($restock_staysafe_report->code->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_approved_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($restock_staysafe_report->approved_by->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_verified_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($restock_staysafe_report->verified_by->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $restock_staysafe_report_search->ShowPageHeader(); ?>
<?php
$restock_staysafe_report_search->ShowMessage();
?>
<form name="frestock_staysafe_reportsearch" id="frestock_staysafe_reportsearch" class="<?php echo $restock_staysafe_report_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($restock_staysafe_report_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $restock_staysafe_report_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="restock_staysafe_report">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($restock_staysafe_report_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($restock_staysafe_report->code->Visible) { // code ?>
	<div id="r_code" class="form-group">
		<label for="x_code" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_code"><?php echo $restock_staysafe_report->code->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_code" id="z_code" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->code->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_code">
<input type="text" data-table="restock_staysafe_report" data-field="x_code" name="x_code" id="x_code" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->code->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->code->EditValue ?>"<?php echo $restock_staysafe_report->code->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->date_restocked->Visible) { // date_restocked ?>
	<div id="r_date_restocked" class="form-group">
		<label for="x_date_restocked" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_date_restocked"><?php echo $restock_staysafe_report->date_restocked->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date_restocked" id="z_date_restocked" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->date_restocked->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_date_restocked">
<input type="text" data-table="restock_staysafe_report" data-field="x_date_restocked" data-format="14" name="x_date_restocked" id="x_date_restocked" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->date_restocked->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->date_restocked->EditValue ?>"<?php echo $restock_staysafe_report->date_restocked->EditAttributes() ?>>
<?php if (!$restock_staysafe_report->date_restocked->ReadOnly && !$restock_staysafe_report->date_restocked->Disabled && !isset($restock_staysafe_report->date_restocked->EditAttrs["readonly"]) && !isset($restock_staysafe_report->date_restocked->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("frestock_staysafe_reportsearch", "x_date_restocked", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_date_restocked">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_restock_staysafe_report_date_restocked" class="btw1_date_restocked">
<input type="text" data-table="restock_staysafe_report" data-field="x_date_restocked" data-format="14" name="y_date_restocked" id="y_date_restocked" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->date_restocked->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->date_restocked->EditValue2 ?>"<?php echo $restock_staysafe_report->date_restocked->EditAttributes() ?>>
<?php if (!$restock_staysafe_report->date_restocked->ReadOnly && !$restock_staysafe_report->date_restocked->Disabled && !isset($restock_staysafe_report->date_restocked->EditAttrs["readonly"]) && !isset($restock_staysafe_report->date_restocked->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("frestock_staysafe_reportsearch", "y_date_restocked", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label for="x_reference_id" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_reference_id"><?php echo $restock_staysafe_report->reference_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->reference_id->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_reference_id">
<input type="text" data-table="restock_staysafe_report" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->reference_id->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->reference_id->EditValue ?>"<?php echo $restock_staysafe_report->reference_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label for="x_material_name" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_material_name"><?php echo $restock_staysafe_report->material_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_material_name" id="z_material_name" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->material_name->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_material_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_material_name"><?php echo (strval($restock_staysafe_report->material_name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $restock_staysafe_report->material_name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($restock_staysafe_report->material_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_material_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($restock_staysafe_report->material_name->ReadOnly || $restock_staysafe_report->material_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_material_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $restock_staysafe_report->material_name->DisplayValueSeparatorAttribute() ?>" name="x_material_name" id="x_material_name" value="<?php echo $restock_staysafe_report->material_name->AdvancedSearch->SearchValue ?>"<?php echo $restock_staysafe_report->material_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label for="x_type" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_type"><?php echo $restock_staysafe_report->type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_type" id="z_type" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->type->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_type">
<input type="text" data-table="restock_staysafe_report" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->type->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->type->EditValue ?>"<?php echo $restock_staysafe_report->type->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->capacity->Visible) { // capacity ?>
	<div id="r_capacity" class="form-group">
		<label for="x_capacity" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_capacity"><?php echo $restock_staysafe_report->capacity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_capacity" id="z_capacity" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->capacity->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_capacity">
<input type="text" data-table="restock_staysafe_report" data-field="x_capacity" name="x_capacity" id="x_capacity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->capacity->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->capacity->EditValue ?>"<?php echo $restock_staysafe_report->capacity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->stock_balance->Visible) { // stock_balance ?>
	<div id="r_stock_balance" class="form-group">
		<label for="x_stock_balance" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_stock_balance"><?php echo $restock_staysafe_report->stock_balance->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_stock_balance" id="z_stock_balance" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->stock_balance->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_stock_balance">
<input type="text" data-table="restock_staysafe_report" data-field="x_stock_balance" name="x_stock_balance" id="x_stock_balance" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->stock_balance->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->stock_balance->EditValue ?>"<?php echo $restock_staysafe_report->stock_balance->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label for="x_quantity" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_quantity"><?php echo $restock_staysafe_report->quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity" id="z_quantity" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->quantity->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_quantity">
<input type="text" data-table="restock_staysafe_report" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->quantity->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->quantity->EditValue ?>"<?php echo $restock_staysafe_report->quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label for="x_statuss" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_statuss"><?php echo $restock_staysafe_report->statuss->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_statuss" id="z_statuss" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->statuss->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_statuss">
<select data-table="restock_staysafe_report" data-field="x_statuss" data-value-separator="<?php echo $restock_staysafe_report->statuss->DisplayValueSeparatorAttribute() ?>" id="x_statuss" name="x_statuss"<?php echo $restock_staysafe_report->statuss->EditAttributes() ?>>
<?php echo $restock_staysafe_report->statuss->SelectOptionListHtml("x_statuss") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->restocked_action->Visible) { // restocked_action ?>
	<div id="r_restocked_action" class="form-group">
		<label class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_restocked_action"><?php echo $restock_staysafe_report->restocked_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_restocked_action" id="z_restocked_action" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->restocked_action->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_restocked_action">
<div id="tp_x_restocked_action" class="ewTemplate"><input type="radio" data-table="restock_staysafe_report" data-field="x_restocked_action" data-value-separator="<?php echo $restock_staysafe_report->restocked_action->DisplayValueSeparatorAttribute() ?>" name="x_restocked_action" id="x_restocked_action" value="{value}"<?php echo $restock_staysafe_report->restocked_action->EditAttributes() ?>></div>
<div id="dsl_x_restocked_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $restock_staysafe_report->restocked_action->RadioButtonListHtml(FALSE, "x_restocked_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->restocked_comment->Visible) { // restocked_comment ?>
	<div id="r_restocked_comment" class="form-group">
		<label for="x_restocked_comment" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_restocked_comment"><?php echo $restock_staysafe_report->restocked_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_restocked_comment" id="z_restocked_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->restocked_comment->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_restocked_comment">
<input type="text" data-table="restock_staysafe_report" data-field="x_restocked_comment" name="x_restocked_comment" id="x_restocked_comment" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_comment->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->restocked_comment->EditValue ?>"<?php echo $restock_staysafe_report->restocked_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->restocked_by->Visible) { // restocked_by ?>
	<div id="r_restocked_by" class="form-group">
		<label class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_restocked_by"><?php echo $restock_staysafe_report->restocked_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_restocked_by" id="z_restocked_by" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->restocked_by->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_restocked_by">
<?php
$wrkonchange = trim(" " . @$restock_staysafe_report->restocked_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$restock_staysafe_report->restocked_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_restocked_by" style="white-space: nowrap; z-index: 8880">
	<input type="text" name="sv_x_restocked_by" id="sv_x_restocked_by" value="<?php echo $restock_staysafe_report->restocked_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_by->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->restocked_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_restocked_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $restock_staysafe_report->restocked_by->DisplayValueSeparatorAttribute() ?>" name="x_restocked_by" id="x_restocked_by" value="<?php echo ew_HtmlEncode($restock_staysafe_report->restocked_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frestock_staysafe_reportsearch.CreateAutoSuggest({"id":"x_restocked_by","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($restock_staysafe_report->restocked_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_restocked_by',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($restock_staysafe_report->restocked_by->ReadOnly || $restock_staysafe_report->restocked_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label for="x_approver_date" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_approver_date"><?php echo $restock_staysafe_report->approver_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_date" id="z_approver_date" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approver_date->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_approver_date">
<input type="text" data-table="restock_staysafe_report" data-field="x_approver_date" data-format="14" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approver_date->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->approver_date->EditValue ?>"<?php echo $restock_staysafe_report->approver_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_approver_action"><?php echo $restock_staysafe_report->approver_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_action" id="z_approver_action" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approver_action->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="restock_staysafe_report" data-field="x_approver_action" data-value-separator="<?php echo $restock_staysafe_report->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $restock_staysafe_report->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $restock_staysafe_report->approver_action->RadioButtonListHtml(FALSE, "x_approver_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label for="x_approver_comment" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_approver_comment"><?php echo $restock_staysafe_report->approver_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approver_comment" id="z_approver_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approver_comment->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_approver_comment">
<input type="text" data-table="restock_staysafe_report" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approver_comment->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->approver_comment->EditValue ?>"<?php echo $restock_staysafe_report->approver_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_approved_by"><?php echo $restock_staysafe_report->approved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_by" id="z_approved_by" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->approved_by->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_approved_by">
<?php
$wrkonchange = trim(" " . @$restock_staysafe_report->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$restock_staysafe_report->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8840">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $restock_staysafe_report->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->approved_by->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_approved_by" data-value-separator="<?php echo $restock_staysafe_report->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($restock_staysafe_report->approved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frestock_staysafe_reportsearch.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label for="x_verified_date" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_verified_date"><?php echo $restock_staysafe_report->verified_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_date" id="z_verified_date" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_date->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_verified_date">
<input type="text" data-table="restock_staysafe_report" data-field="x_verified_date" data-format="14" name="x_verified_date" id="x_verified_date" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_date->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->verified_date->EditValue ?>"<?php echo $restock_staysafe_report->verified_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_verified_action"><?php echo $restock_staysafe_report->verified_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_action" id="z_verified_action" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_action->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="restock_staysafe_report" data-field="x_verified_action" data-value-separator="<?php echo $restock_staysafe_report->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $restock_staysafe_report->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $restock_staysafe_report->verified_action->RadioButtonListHtml(FALSE, "x_verified_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label for="x_verified_comment" class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_verified_comment"><?php echo $restock_staysafe_report->verified_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_verified_comment" id="z_verified_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_comment->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_verified_comment">
<input type="text" data-table="restock_staysafe_report" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_comment->getPlaceHolder()) ?>" value="<?php echo $restock_staysafe_report->verified_comment->EditValue ?>"<?php echo $restock_staysafe_report->verified_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($restock_staysafe_report->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label class="<?php echo $restock_staysafe_report_search->LeftColumnClass ?>"><span id="elh_restock_staysafe_report_verified_by"><?php echo $restock_staysafe_report->verified_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_by" id="z_verified_by" value="="></p>
		</label>
		<div class="<?php echo $restock_staysafe_report_search->RightColumnClass ?>"><div<?php echo $restock_staysafe_report->verified_by->CellAttributes() ?>>
			<span id="el_restock_staysafe_report_verified_by">
<?php
$wrkonchange = trim(" " . @$restock_staysafe_report->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$restock_staysafe_report->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $restock_staysafe_report->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_by->getPlaceHolder()) ?>"<?php echo $restock_staysafe_report->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="restock_staysafe_report" data-field="x_verified_by" data-value-separator="<?php echo $restock_staysafe_report->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($restock_staysafe_report->verified_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frestock_staysafe_reportsearch.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$restock_staysafe_report_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $restock_staysafe_report_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
frestock_staysafe_reportsearch.Init();
</script>
<?php
$restock_staysafe_report_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$restock_staysafe_report_search->Page_Terminate();
?>
