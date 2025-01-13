<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "store_reportsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$store_reports_search = NULL; // Initialize page object first

class cstore_reports_search extends cstore_reports {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'store_reports';

	// Page object name
	var $PageObjName = 'store_reports_search';

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

		// Table object (store_reports)
		if (!isset($GLOBALS["store_reports"]) || get_class($GLOBALS["store_reports"]) == "cstore_reports") {
			$GLOBALS["store_reports"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["store_reports"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'store_reports', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("store_reportslist.php"));
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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_type->SetVisibility();
		$this->quantity_out->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->treated_by->SetVisibility();
		$this->statuss->SetVisibility();
		$this->issued_action->SetVisibility();
		$this->issued_comment->SetVisibility();
		$this->issued_by->SetVisibility();
		$this->approver_date->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approved_comment->SetVisibility();
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
		global $EW_EXPORT, $store_reports;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($store_reports);
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
					if ($pageName == "store_reportsview.php")
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
						$sSrchStr = "store_reportslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->id); // id
		$this->BuildSearchUrl($sSrchUrl, $this->date); // date
		$this->BuildSearchUrl($sSrchUrl, $this->reference_id); // reference_id
		$this->BuildSearchUrl($sSrchUrl, $this->staff_id); // staff_id
		$this->BuildSearchUrl($sSrchUrl, $this->material_name); // material_name
		$this->BuildSearchUrl($sSrchUrl, $this->quantity_in); // quantity_in
		$this->BuildSearchUrl($sSrchUrl, $this->quantity_type); // quantity_type
		$this->BuildSearchUrl($sSrchUrl, $this->quantity_out); // quantity_out
		$this->BuildSearchUrl($sSrchUrl, $this->total_quantity); // total_quantity
		$this->BuildSearchUrl($sSrchUrl, $this->treated_by); // treated_by
		$this->BuildSearchUrl($sSrchUrl, $this->statuss); // statuss
		$this->BuildSearchUrl($sSrchUrl, $this->issued_action); // issued_action
		$this->BuildSearchUrl($sSrchUrl, $this->issued_comment); // issued_comment
		$this->BuildSearchUrl($sSrchUrl, $this->issued_by); // issued_by
		$this->BuildSearchUrl($sSrchUrl, $this->approver_date); // approver_date
		$this->BuildSearchUrl($sSrchUrl, $this->approver_action); // approver_action
		$this->BuildSearchUrl($sSrchUrl, $this->approved_comment); // approved_comment
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
		// id

		$this->id->AdvancedSearch->SearchValue = $objForm->GetValue("x_id");
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// date
		$this->date->AdvancedSearch->SearchValue = $objForm->GetValue("x_date");
		$this->date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date");
		$this->date->AdvancedSearch->SearchCondition = $objForm->GetValue("v_date");
		$this->date->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_date");
		$this->date->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_date");

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_reference_id");
		$this->reference_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_reference_id");

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_staff_id");
		$this->staff_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staff_id");

		// material_name
		$this->material_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_material_name");
		$this->material_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_material_name");

		// quantity_in
		$this->quantity_in->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity_in");
		$this->quantity_in->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity_in");

		// quantity_type
		$this->quantity_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity_type");
		$this->quantity_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity_type");

		// quantity_out
		$this->quantity_out->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity_out");
		$this->quantity_out->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity_out");

		// total_quantity
		$this->total_quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_total_quantity");
		$this->total_quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_total_quantity");

		// treated_by
		$this->treated_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_treated_by");
		$this->treated_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_treated_by");

		// statuss
		$this->statuss->AdvancedSearch->SearchValue = $objForm->GetValue("x_statuss");
		$this->statuss->AdvancedSearch->SearchOperator = $objForm->GetValue("z_statuss");

		// issued_action
		$this->issued_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_issued_action");
		$this->issued_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_issued_action");

		// issued_comment
		$this->issued_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_issued_comment");
		$this->issued_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_issued_comment");

		// issued_by
		$this->issued_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_issued_by");
		$this->issued_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_issued_by");

		// approver_date
		$this->approver_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_date");
		$this->approver_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_date");

		// approver_action
		$this->approver_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_action");
		$this->approver_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_action");

		// approved_comment
		$this->approved_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_approved_comment");
		$this->approved_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approved_comment");

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
		// id
		// date
		// reference_id
		// staff_id
		// material_name
		// quantity_in
		// quantity_type
		// quantity_out
		// total_quantity
		// treated_by
		// statuss
		// issued_action
		// issued_comment
		// issued_by
		// approver_date
		// approver_action
		// approved_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

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
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
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

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_type
		$this->quantity_type->ViewValue = $this->quantity_type->CurrentValue;
		$this->quantity_type->ViewCustomAttributes = "";

		// quantity_out
		$this->quantity_out->ViewValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// treated_by
		$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
		if (strval($this->treated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->treated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->treated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->treated_by->ViewValue = $this->treated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
			}
		} else {
			$this->treated_by->ViewValue = NULL;
		}
		$this->treated_by->ViewCustomAttributes = "";

		// statuss
		$this->statuss->ViewValue = $this->statuss->CurrentValue;
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

		// issued_action
		if (strval($this->issued_action->CurrentValue) <> "") {
			$this->issued_action->ViewValue = $this->issued_action->OptionCaption($this->issued_action->CurrentValue);
		} else {
			$this->issued_action->ViewValue = NULL;
		}
		$this->issued_action->ViewCustomAttributes = "";

		// issued_comment
		$this->issued_comment->ViewValue = $this->issued_comment->CurrentValue;
		$this->issued_comment->ViewCustomAttributes = "";

		// issued_by
		if (strval($this->issued_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->issued_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->issued_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->issued_by->ViewValue = $this->issued_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
			}
		} else {
			$this->issued_by->ViewValue = NULL;
		}
		$this->issued_by->ViewCustomAttributes = "";

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

		// approved_comment
		$this->approved_comment->ViewValue = $this->approved_comment->CurrentValue;
		$this->approved_comment->ViewCustomAttributes = "";

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
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 0);
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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

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

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_type
			$this->quantity_type->LinkCustomAttributes = "";
			$this->quantity_type->HrefValue = "";
			$this->quantity_type->TooltipValue = "";

			// quantity_out
			$this->quantity_out->LinkCustomAttributes = "";
			$this->quantity_out->HrefValue = "";
			$this->quantity_out->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// treated_by
			$this->treated_by->LinkCustomAttributes = "";
			$this->treated_by->HrefValue = "";
			$this->treated_by->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

			// issued_action
			$this->issued_action->LinkCustomAttributes = "";
			$this->issued_action->HrefValue = "";
			$this->issued_action->TooltipValue = "";

			// issued_comment
			$this->issued_comment->LinkCustomAttributes = "";
			$this->issued_comment->HrefValue = "";
			$this->issued_comment->TooltipValue = "";

			// issued_by
			$this->issued_by->LinkCustomAttributes = "";
			$this->issued_by->HrefValue = "";
			$this->issued_by->TooltipValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";
			$this->approver_date->TooltipValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";
			$this->approver_action->TooltipValue = "";

			// approved_comment
			$this->approved_comment->LinkCustomAttributes = "";
			$this->approved_comment->HrefValue = "";
			$this->approved_comment->TooltipValue = "";

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

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date->AdvancedSearch->SearchValue, 0), 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date->AdvancedSearch->SearchValue2, 0), 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->AdvancedSearch->SearchValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
			if (strval($this->staff_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// material_name
			$this->material_name->EditCustomAttributes = "";
			if (trim(strval($this->material_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `inventory`";
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

			// quantity_in
			$this->quantity_in->EditAttrs["class"] = "form-control";
			$this->quantity_in->EditCustomAttributes = "";
			$this->quantity_in->EditValue = ew_HtmlEncode($this->quantity_in->AdvancedSearch->SearchValue);
			$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

			// quantity_type
			$this->quantity_type->EditAttrs["class"] = "form-control";
			$this->quantity_type->EditCustomAttributes = "";
			$this->quantity_type->EditValue = ew_HtmlEncode($this->quantity_type->AdvancedSearch->SearchValue);
			$this->quantity_type->PlaceHolder = ew_RemoveHtml($this->quantity_type->FldCaption());

			// quantity_out
			$this->quantity_out->EditAttrs["class"] = "form-control";
			$this->quantity_out->EditCustomAttributes = "";
			$this->quantity_out->EditValue = ew_HtmlEncode($this->quantity_out->AdvancedSearch->SearchValue);
			$this->quantity_out->PlaceHolder = ew_RemoveHtml($this->quantity_out->FldCaption());

			// total_quantity
			$this->total_quantity->EditAttrs["class"] = "form-control";
			$this->total_quantity->EditCustomAttributes = "";
			$this->total_quantity->EditValue = ew_HtmlEncode($this->total_quantity->AdvancedSearch->SearchValue);
			$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

			// treated_by
			$this->treated_by->EditAttrs["class"] = "form-control";
			$this->treated_by->EditCustomAttributes = "";
			$this->treated_by->EditValue = ew_HtmlEncode($this->treated_by->AdvancedSearch->SearchValue);
			if (strval($this->treated_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->treated_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->treated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->treated_by->EditValue = $this->treated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->treated_by->EditValue = ew_HtmlEncode($this->treated_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->treated_by->EditValue = NULL;
			}
			$this->treated_by->PlaceHolder = ew_RemoveHtml($this->treated_by->FldCaption());

			// statuss
			$this->statuss->EditAttrs["class"] = "form-control";
			$this->statuss->EditCustomAttributes = "";
			$this->statuss->EditValue = ew_HtmlEncode($this->statuss->AdvancedSearch->SearchValue);
			if (strval($this->statuss->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
			$sWhereWrk = "";
			$this->statuss->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->statuss->EditValue = $this->statuss->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->statuss->EditValue = ew_HtmlEncode($this->statuss->AdvancedSearch->SearchValue);
				}
			} else {
				$this->statuss->EditValue = NULL;
			}
			$this->statuss->PlaceHolder = ew_RemoveHtml($this->statuss->FldCaption());

			// issued_action
			$this->issued_action->EditCustomAttributes = "";
			$this->issued_action->EditValue = $this->issued_action->Options(FALSE);

			// issued_comment
			$this->issued_comment->EditAttrs["class"] = "form-control";
			$this->issued_comment->EditCustomAttributes = "";
			$this->issued_comment->EditValue = ew_HtmlEncode($this->issued_comment->AdvancedSearch->SearchValue);
			$this->issued_comment->PlaceHolder = ew_RemoveHtml($this->issued_comment->FldCaption());

			// issued_by
			$this->issued_by->EditCustomAttributes = "";
			if (trim(strval($this->issued_by->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->issued_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->issued_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->issued_by->AdvancedSearch->ViewValue = $this->issued_by->DisplayValue($arwrk);
			} else {
				$this->issued_by->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->issued_by->EditValue = $arwrk;

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->approver_date->AdvancedSearch->SearchValue, 0), 8));
			$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

			// approver_action
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

			// approved_comment
			$this->approved_comment->EditAttrs["class"] = "form-control";
			$this->approved_comment->EditCustomAttributes = "";
			$this->approved_comment->EditValue = ew_HtmlEncode($this->approved_comment->AdvancedSearch->SearchValue);
			$this->approved_comment->PlaceHolder = ew_RemoveHtml($this->approved_comment->FldCaption());

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
			$this->verified_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->verified_date->AdvancedSearch->SearchValue, 0), 8));
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
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->staff_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->staff_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->treated_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->treated_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->approver_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approver_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approved_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->verified_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->verified_date->FldErrMsg());
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
		$this->id->AdvancedSearch->Load();
		$this->date->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->material_name->AdvancedSearch->Load();
		$this->quantity_in->AdvancedSearch->Load();
		$this->quantity_type->AdvancedSearch->Load();
		$this->quantity_out->AdvancedSearch->Load();
		$this->total_quantity->AdvancedSearch->Load();
		$this->treated_by->AdvancedSearch->Load();
		$this->statuss->AdvancedSearch->Load();
		$this->issued_action->AdvancedSearch->Load();
		$this->issued_comment->AdvancedSearch->Load();
		$this->issued_by->AdvancedSearch->Load();
		$this->approver_date->AdvancedSearch->Load();
		$this->approver_action->AdvancedSearch->Load();
		$this->approved_comment->AdvancedSearch->Load();
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("store_reportslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
		case "x_material_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`material_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_treated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_statuss":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_issued_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_treated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->treated_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->treated_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_statuss":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld` FROM `statuss`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($store_reports_search)) $store_reports_search = new cstore_reports_search();

// Page init
$store_reports_search->Page_Init();

// Page main
$store_reports_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$store_reports_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($store_reports_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fstore_reportssearch = new ew_Form("fstore_reportssearch", "search");
<?php } else { ?>
var CurrentForm = fstore_reportssearch = new ew_Form("fstore_reportssearch", "search");
<?php } ?>

// Form_CustomValidate event
fstore_reportssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstore_reportssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstore_reportssearch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fstore_reportssearch.Lists["x_staff_id"].Data = "<?php echo $store_reports_search->staff_id->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_reports_search->staff_id->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_reportssearch.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory"};
fstore_reportssearch.Lists["x_material_name"].Data = "<?php echo $store_reports_search->material_name->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.Lists["x_treated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fstore_reportssearch.Lists["x_treated_by"].Data = "<?php echo $store_reports_search->treated_by->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.AutoSuggests["x_treated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_reports_search->treated_by->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_reportssearch.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
fstore_reportssearch.Lists["x_statuss"].Data = "<?php echo $store_reports_search->statuss->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.AutoSuggests["x_statuss"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_reports_search->statuss->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_reportssearch.Lists["x_issued_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstore_reportssearch.Lists["x_issued_action"].Options = <?php echo json_encode($store_reports_search->issued_action->Options()) ?>;
fstore_reportssearch.Lists["x_issued_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fstore_reportssearch.Lists["x_issued_by"].Data = "<?php echo $store_reports_search->issued_by->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstore_reportssearch.Lists["x_approver_action"].Options = <?php echo json_encode($store_reports_search->approver_action->Options()) ?>;
fstore_reportssearch.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fstore_reportssearch.Lists["x_approved_by"].Data = "<?php echo $store_reports_search->approved_by->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_reports_search->approved_by->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_reportssearch.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstore_reportssearch.Lists["x_verified_action"].Options = <?php echo json_encode($store_reports_search->verified_action->Options()) ?>;
fstore_reportssearch.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fstore_reportssearch.Lists["x_verified_by"].Data = "<?php echo $store_reports_search->verified_by->LookupFilterQuery(FALSE, "search") ?>";
fstore_reportssearch.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_reports_search->verified_by->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

fstore_reportssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_staff_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->staff_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_treated_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->treated_by->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_approver_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->approver_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_approved_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->approved_by->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_verified_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->verified_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_verified_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_reports->verified_by->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $store_reports_search->ShowPageHeader(); ?>
<?php
$store_reports_search->ShowMessage();
?>
<form name="fstore_reportssearch" id="fstore_reportssearch" class="<?php echo $store_reports_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($store_reports_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $store_reports_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="store_reports">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($store_reports_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($store_reports->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_id"><?php echo $store_reports->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->id->CellAttributes() ?>>
			<span id="el_store_reports_id">
<input type="text" data-table="store_reports" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($store_reports->id->getPlaceHolder()) ?>" value="<?php echo $store_reports->id->EditValue ?>"<?php echo $store_reports->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label for="x_date" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_date"><?php echo $store_reports->date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date" id="z_date" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->date->CellAttributes() ?>>
			<span id="el_store_reports_date">
<input type="text" data-table="store_reports" data-field="x_date" name="x_date" id="x_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->date->getPlaceHolder()) ?>" value="<?php echo $store_reports->date->EditValue ?>"<?php echo $store_reports->date->EditAttributes() ?>>
<?php if (!$store_reports->date->ReadOnly && !$store_reports->date->Disabled && !isset($store_reports->date->EditAttrs["readonly"]) && !isset($store_reports->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fstore_reportssearch", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_date">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_store_reports_date" class="btw1_date">
<input type="text" data-table="store_reports" data-field="x_date" name="y_date" id="y_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->date->getPlaceHolder()) ?>" value="<?php echo $store_reports->date->EditValue2 ?>"<?php echo $store_reports->date->EditAttributes() ?>>
<?php if (!$store_reports->date->ReadOnly && !$store_reports->date->Disabled && !isset($store_reports->date->EditAttrs["readonly"]) && !isset($store_reports->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fstore_reportssearch", "y_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label for="x_reference_id" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_reference_id"><?php echo $store_reports->reference_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->reference_id->CellAttributes() ?>>
			<span id="el_store_reports_reference_id">
<input type="text" data-table="store_reports" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->reference_id->getPlaceHolder()) ?>" value="<?php echo $store_reports->reference_id->EditValue ?>"<?php echo $store_reports->reference_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_staff_id"><?php echo $store_reports->staff_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->staff_id->CellAttributes() ?>>
			<span id="el_store_reports_staff_id">
<?php
$wrkonchange = trim(" " . @$store_reports->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_reports->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $store_reports->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($store_reports->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_reports->staff_id->getPlaceHolder()) ?>"<?php echo $store_reports->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_reports" data-field="x_staff_id" data-value-separator="<?php echo $store_reports->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($store_reports->staff_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_reportssearch.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label for="x_material_name" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_material_name"><?php echo $store_reports->material_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_material_name" id="z_material_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->material_name->CellAttributes() ?>>
			<span id="el_store_reports_material_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_material_name"><?php echo (strval($store_reports->material_name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $store_reports->material_name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($store_reports->material_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_material_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($store_reports->material_name->ReadOnly || $store_reports->material_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="store_reports" data-field="x_material_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $store_reports->material_name->DisplayValueSeparatorAttribute() ?>" name="x_material_name" id="x_material_name" value="<?php echo $store_reports->material_name->AdvancedSearch->SearchValue ?>"<?php echo $store_reports->material_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->quantity_in->Visible) { // quantity_in ?>
	<div id="r_quantity_in" class="form-group">
		<label for="x_quantity_in" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_quantity_in"><?php echo $store_reports->quantity_in->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity_in" id="z_quantity_in" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->quantity_in->CellAttributes() ?>>
			<span id="el_store_reports_quantity_in">
<input type="text" data-table="store_reports" data-field="x_quantity_in" name="x_quantity_in" id="x_quantity_in" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->quantity_in->getPlaceHolder()) ?>" value="<?php echo $store_reports->quantity_in->EditValue ?>"<?php echo $store_reports->quantity_in->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->quantity_type->Visible) { // quantity_type ?>
	<div id="r_quantity_type" class="form-group">
		<label for="x_quantity_type" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_quantity_type"><?php echo $store_reports->quantity_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity_type" id="z_quantity_type" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->quantity_type->CellAttributes() ?>>
			<span id="el_store_reports_quantity_type">
<input type="text" data-table="store_reports" data-field="x_quantity_type" name="x_quantity_type" id="x_quantity_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->quantity_type->getPlaceHolder()) ?>" value="<?php echo $store_reports->quantity_type->EditValue ?>"<?php echo $store_reports->quantity_type->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->quantity_out->Visible) { // quantity_out ?>
	<div id="r_quantity_out" class="form-group">
		<label for="x_quantity_out" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_quantity_out"><?php echo $store_reports->quantity_out->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity_out" id="z_quantity_out" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->quantity_out->CellAttributes() ?>>
			<span id="el_store_reports_quantity_out">
<input type="text" data-table="store_reports" data-field="x_quantity_out" name="x_quantity_out" id="x_quantity_out" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->quantity_out->getPlaceHolder()) ?>" value="<?php echo $store_reports->quantity_out->EditValue ?>"<?php echo $store_reports->quantity_out->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->total_quantity->Visible) { // total_quantity ?>
	<div id="r_total_quantity" class="form-group">
		<label for="x_total_quantity" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_total_quantity"><?php echo $store_reports->total_quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_total_quantity" id="z_total_quantity" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->total_quantity->CellAttributes() ?>>
			<span id="el_store_reports_total_quantity">
<input type="text" data-table="store_reports" data-field="x_total_quantity" name="x_total_quantity" id="x_total_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->total_quantity->getPlaceHolder()) ?>" value="<?php echo $store_reports->total_quantity->EditValue ?>"<?php echo $store_reports->total_quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->treated_by->Visible) { // treated_by ?>
	<div id="r_treated_by" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_treated_by"><?php echo $store_reports->treated_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_treated_by" id="z_treated_by" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->treated_by->CellAttributes() ?>>
			<span id="el_store_reports_treated_by">
<?php
$wrkonchange = trim(" " . @$store_reports->treated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_reports->treated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_treated_by" style="white-space: nowrap; z-index: 8900">
	<input type="text" name="sv_x_treated_by" id="sv_x_treated_by" value="<?php echo $store_reports->treated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($store_reports->treated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_reports->treated_by->getPlaceHolder()) ?>"<?php echo $store_reports->treated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_reports" data-field="x_treated_by" data-value-separator="<?php echo $store_reports->treated_by->DisplayValueSeparatorAttribute() ?>" name="x_treated_by" id="x_treated_by" value="<?php echo ew_HtmlEncode($store_reports->treated_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_reportssearch.CreateAutoSuggest({"id":"x_treated_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_statuss"><?php echo $store_reports->statuss->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_statuss" id="z_statuss" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->statuss->CellAttributes() ?>>
			<span id="el_store_reports_statuss">
<?php
$wrkonchange = trim(" " . @$store_reports->statuss->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_reports->statuss->EditAttrs["onchange"] = "";
?>
<span id="as_x_statuss" style="white-space: nowrap; z-index: 8890">
	<input type="text" name="sv_x_statuss" id="sv_x_statuss" value="<?php echo $store_reports->statuss->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->statuss->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_reports->statuss->getPlaceHolder()) ?>"<?php echo $store_reports->statuss->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_reports" data-field="x_statuss" data-value-separator="<?php echo $store_reports->statuss->DisplayValueSeparatorAttribute() ?>" name="x_statuss" id="x_statuss" value="<?php echo ew_HtmlEncode($store_reports->statuss->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_reportssearch.CreateAutoSuggest({"id":"x_statuss","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->issued_action->Visible) { // issued_action ?>
	<div id="r_issued_action" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_issued_action"><?php echo $store_reports->issued_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_issued_action" id="z_issued_action" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->issued_action->CellAttributes() ?>>
			<span id="el_store_reports_issued_action">
<div id="tp_x_issued_action" class="ewTemplate"><input type="radio" data-table="store_reports" data-field="x_issued_action" data-value-separator="<?php echo $store_reports->issued_action->DisplayValueSeparatorAttribute() ?>" name="x_issued_action" id="x_issued_action" value="{value}"<?php echo $store_reports->issued_action->EditAttributes() ?>></div>
<div id="dsl_x_issued_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $store_reports->issued_action->RadioButtonListHtml(FALSE, "x_issued_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->issued_comment->Visible) { // issued_comment ?>
	<div id="r_issued_comment" class="form-group">
		<label for="x_issued_comment" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_issued_comment"><?php echo $store_reports->issued_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_issued_comment" id="z_issued_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->issued_comment->CellAttributes() ?>>
			<span id="el_store_reports_issued_comment">
<input type="text" data-table="store_reports" data-field="x_issued_comment" name="x_issued_comment" id="x_issued_comment" size="35" maxlength="25" placeholder="<?php echo ew_HtmlEncode($store_reports->issued_comment->getPlaceHolder()) ?>" value="<?php echo $store_reports->issued_comment->EditValue ?>"<?php echo $store_reports->issued_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->issued_by->Visible) { // issued_by ?>
	<div id="r_issued_by" class="form-group">
		<label for="x_issued_by" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_issued_by"><?php echo $store_reports->issued_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_issued_by" id="z_issued_by" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->issued_by->CellAttributes() ?>>
			<span id="el_store_reports_issued_by">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_issued_by"><?php echo (strval($store_reports->issued_by->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $store_reports->issued_by->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($store_reports->issued_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_issued_by',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($store_reports->issued_by->ReadOnly || $store_reports->issued_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="store_reports" data-field="x_issued_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $store_reports->issued_by->DisplayValueSeparatorAttribute() ?>" name="x_issued_by" id="x_issued_by" value="<?php echo $store_reports->issued_by->AdvancedSearch->SearchValue ?>"<?php echo $store_reports->issued_by->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label for="x_approver_date" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_approver_date"><?php echo $store_reports->approver_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_date" id="z_approver_date" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->approver_date->CellAttributes() ?>>
			<span id="el_store_reports_approver_date">
<input type="text" data-table="store_reports" data-field="x_approver_date" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($store_reports->approver_date->getPlaceHolder()) ?>" value="<?php echo $store_reports->approver_date->EditValue ?>"<?php echo $store_reports->approver_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_approver_action"><?php echo $store_reports->approver_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_action" id="z_approver_action" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->approver_action->CellAttributes() ?>>
			<span id="el_store_reports_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="store_reports" data-field="x_approver_action" data-value-separator="<?php echo $store_reports->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $store_reports->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $store_reports->approver_action->RadioButtonListHtml(FALSE, "x_approver_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->approved_comment->Visible) { // approved_comment ?>
	<div id="r_approved_comment" class="form-group">
		<label for="x_approved_comment" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_approved_comment"><?php echo $store_reports->approved_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approved_comment" id="z_approved_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->approved_comment->CellAttributes() ?>>
			<span id="el_store_reports_approved_comment">
<input type="text" data-table="store_reports" data-field="x_approved_comment" name="x_approved_comment" id="x_approved_comment" size="35" maxlength="50" placeholder="<?php echo ew_HtmlEncode($store_reports->approved_comment->getPlaceHolder()) ?>" value="<?php echo $store_reports->approved_comment->EditValue ?>"<?php echo $store_reports->approved_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_approved_by"><?php echo $store_reports->approved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_by" id="z_approved_by" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->approved_by->CellAttributes() ?>>
			<span id="el_store_reports_approved_by">
<?php
$wrkonchange = trim(" " . @$store_reports->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_reports->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8820">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $store_reports->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($store_reports->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_reports->approved_by->getPlaceHolder()) ?>"<?php echo $store_reports->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_reports" data-field="x_approved_by" data-value-separator="<?php echo $store_reports->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($store_reports->approved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_reportssearch.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label for="x_verified_date" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_verified_date"><?php echo $store_reports->verified_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_date" id="z_verified_date" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->verified_date->CellAttributes() ?>>
			<span id="el_store_reports_verified_date">
<input type="text" data-table="store_reports" data-field="x_verified_date" name="x_verified_date" id="x_verified_date" placeholder="<?php echo ew_HtmlEncode($store_reports->verified_date->getPlaceHolder()) ?>" value="<?php echo $store_reports->verified_date->EditValue ?>"<?php echo $store_reports->verified_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_verified_action"><?php echo $store_reports->verified_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_action" id="z_verified_action" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->verified_action->CellAttributes() ?>>
			<span id="el_store_reports_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="store_reports" data-field="x_verified_action" data-value-separator="<?php echo $store_reports->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $store_reports->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $store_reports->verified_action->RadioButtonListHtml(FALSE, "x_verified_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label for="x_verified_comment" class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_verified_comment"><?php echo $store_reports->verified_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_verified_comment" id="z_verified_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->verified_comment->CellAttributes() ?>>
			<span id="el_store_reports_verified_comment">
<input type="text" data-table="store_reports" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" size="35" maxlength="25" placeholder="<?php echo ew_HtmlEncode($store_reports->verified_comment->getPlaceHolder()) ?>" value="<?php echo $store_reports->verified_comment->EditValue ?>"<?php echo $store_reports->verified_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_reports->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label class="<?php echo $store_reports_search->LeftColumnClass ?>"><span id="elh_store_reports_verified_by"><?php echo $store_reports->verified_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_by" id="z_verified_by" value="="></p>
		</label>
		<div class="<?php echo $store_reports_search->RightColumnClass ?>"><div<?php echo $store_reports->verified_by->CellAttributes() ?>>
			<span id="el_store_reports_verified_by">
<?php
$wrkonchange = trim(" " . @$store_reports->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_reports->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8780">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $store_reports->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($store_reports->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_reports->verified_by->getPlaceHolder()) ?>"<?php echo $store_reports->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_reports" data-field="x_verified_by" data-value-separator="<?php echo $store_reports->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($store_reports->verified_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_reportssearch.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$store_reports_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $store_reports_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fstore_reportssearch.Init();
</script>
<?php
$store_reports_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$store_reports_search->Page_Terminate();
?>
