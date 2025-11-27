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

$gen_maintenance_search = NULL; // Initialize page object first

class cgen_maintenance_search extends cgen_maintenance {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'gen_maintenance';

	// Page object name
	var $PageObjName = 'gen_maintenance_search';

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
			define("EW_PAGE_ID", 'search');

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
		if (!$Security->CanSearch()) {
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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->datetime->SetVisibility();
		$this->reference_id->SetVisibility();
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
		$this->flag->SetVisibility();

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
						$sSrchStr = "gen_maintenancelist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->datetime); // datetime
		$this->BuildSearchUrl($sSrchUrl, $this->reference_id); // reference_id
		$this->BuildSearchUrl($sSrchUrl, $this->gen_name); // gen_name
		$this->BuildSearchUrl($sSrchUrl, $this->maintenance_type); // maintenance_type
		$this->BuildSearchUrl($sSrchUrl, $this->running_hours); // running_hours
		$this->BuildSearchUrl($sSrchUrl, $this->cost); // cost
		$this->BuildSearchUrl($sSrchUrl, $this->labour_fee); // labour_fee
		$this->BuildSearchUrl($sSrchUrl, $this->total); // total
		$this->BuildSearchUrl($sSrchUrl, $this->staff_id); // staff_id
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
		$this->BuildSearchUrl($sSrchUrl, $this->initiator_action); // initiator_action
		$this->BuildSearchUrl($sSrchUrl, $this->initiator_comment); // initiator_comment
		$this->BuildSearchUrl($sSrchUrl, $this->approver_date); // approver_date
		$this->BuildSearchUrl($sSrchUrl, $this->approver_action); // approver_action
		$this->BuildSearchUrl($sSrchUrl, $this->approver_comment); // approver_comment
		$this->BuildSearchUrl($sSrchUrl, $this->approved_by); // approved_by
		$this->BuildSearchUrl($sSrchUrl, $this->flag); // flag
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

		// datetime
		$this->datetime->AdvancedSearch->SearchValue = $objForm->GetValue("x_datetime");
		$this->datetime->AdvancedSearch->SearchOperator = $objForm->GetValue("z_datetime");

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_reference_id");
		$this->reference_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_reference_id");

		// gen_name
		$this->gen_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_gen_name");
		$this->gen_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_gen_name");

		// maintenance_type
		$this->maintenance_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_maintenance_type");
		$this->maintenance_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_maintenance_type");

		// running_hours
		$this->running_hours->AdvancedSearch->SearchValue = $objForm->GetValue("x_running_hours");
		$this->running_hours->AdvancedSearch->SearchOperator = $objForm->GetValue("z_running_hours");

		// cost
		$this->cost->AdvancedSearch->SearchValue = $objForm->GetValue("x_cost");
		$this->cost->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cost");

		// labour_fee
		$this->labour_fee->AdvancedSearch->SearchValue = $objForm->GetValue("x_labour_fee");
		$this->labour_fee->AdvancedSearch->SearchOperator = $objForm->GetValue("z_labour_fee");

		// total
		$this->total->AdvancedSearch->SearchValue = $objForm->GetValue("x_total");
		$this->total->AdvancedSearch->SearchOperator = $objForm->GetValue("z_total");

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_staff_id");
		$this->staff_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staff_id");

		// status
		$this->status->AdvancedSearch->SearchValue = $objForm->GetValue("x_status");
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");

		// initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_initiator_action");
		$this->initiator_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_initiator_action");

		// initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_initiator_comment");
		$this->initiator_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_initiator_comment");

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

		// flag
		$this->flag->AdvancedSearch->SearchValue = $objForm->GetValue("x_flag");
		$this->flag->AdvancedSearch->SearchOperator = $objForm->GetValue("z_flag");
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
		// reference_id
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
		// flag

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime
		$this->datetime->ViewValue = $this->datetime->CurrentValue;
		$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 0);
		$this->datetime->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

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

		// flag
		$this->flag->ViewValue = $this->flag->CurrentValue;
		$this->flag->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

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

			// flag
			$this->flag->LinkCustomAttributes = "";
			$this->flag->HrefValue = "";
			$this->flag->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// datetime
			$this->datetime->EditAttrs["class"] = "form-control";
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime->AdvancedSearch->SearchValue, 0), 8));
			$this->datetime->PlaceHolder = ew_RemoveHtml($this->datetime->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->AdvancedSearch->SearchValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// gen_name
			$this->gen_name->EditAttrs["class"] = "form-control";
			$this->gen_name->EditCustomAttributes = "";
			if (trim(strval($this->gen_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->maintenance_type->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintenance_type->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->running_hours->EditValue = ew_HtmlEncode($this->running_hours->AdvancedSearch->SearchValue);
			$this->running_hours->PlaceHolder = ew_RemoveHtml($this->running_hours->FldCaption());

			// cost
			$this->cost->EditAttrs["class"] = "form-control";
			$this->cost->EditCustomAttributes = "";
			$this->cost->EditValue = ew_HtmlEncode($this->cost->AdvancedSearch->SearchValue);
			$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());

			// labour_fee
			$this->labour_fee->EditAttrs["class"] = "form-control";
			$this->labour_fee->EditCustomAttributes = "";
			$this->labour_fee->EditValue = ew_HtmlEncode($this->labour_fee->AdvancedSearch->SearchValue);
			$this->labour_fee->PlaceHolder = ew_RemoveHtml($this->labour_fee->FldCaption());

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->AdvancedSearch->SearchValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			if (trim(strval($this->staff_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->status->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->AdvancedSearch->SearchValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->approver_date->AdvancedSearch->SearchValue, 0), 8));
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
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// flag
			$this->flag->EditAttrs["class"] = "form-control";
			$this->flag->EditCustomAttributes = "";
			$this->flag->EditValue = ew_HtmlEncode($this->flag->AdvancedSearch->SearchValue);
			$this->flag->PlaceHolder = ew_RemoveHtml($this->flag->FldCaption());
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
		if (!ew_CheckDateDef($this->datetime->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime->FldErrMsg());
		}
		if (!ew_CheckNumber($this->cost->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->labour_fee->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->labour_fee->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->total->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->approver_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approver_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approved_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckInteger($this->flag->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->flag->FldErrMsg());
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
		$this->datetime->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->gen_name->AdvancedSearch->Load();
		$this->maintenance_type->AdvancedSearch->Load();
		$this->running_hours->AdvancedSearch->Load();
		$this->cost->AdvancedSearch->Load();
		$this->labour_fee->AdvancedSearch->Load();
		$this->total->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->approver_date->AdvancedSearch->Load();
		$this->approver_action->AdvancedSearch->Load();
		$this->approver_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->flag->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("gen_maintenancelist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($gen_maintenance_search)) $gen_maintenance_search = new cgen_maintenance_search();

// Page init
$gen_maintenance_search->Page_Init();

// Page main
$gen_maintenance_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gen_maintenance_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($gen_maintenance_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fgen_maintenancesearch = new ew_Form("fgen_maintenancesearch", "search");
<?php } else { ?>
var CurrentForm = fgen_maintenancesearch = new ew_Form("fgen_maintenancesearch", "search");
<?php } ?>

// Form_CustomValidate event
fgen_maintenancesearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgen_maintenancesearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fgen_maintenancesearch.Lists["x_gen_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","x_kva",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fgen_maintenancesearch.Lists["x_gen_name"].Data = "<?php echo $gen_maintenance_search->gen_name->LookupFilterQuery(FALSE, "search") ?>";
fgen_maintenancesearch.Lists["x_maintenance_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintenance_type"};
fgen_maintenancesearch.Lists["x_maintenance_type"].Data = "<?php echo $gen_maintenance_search->maintenance_type->LookupFilterQuery(FALSE, "search") ?>";
fgen_maintenancesearch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenancesearch.Lists["x_staff_id"].Data = "<?php echo $gen_maintenance_search->staff_id->LookupFilterQuery(FALSE, "search") ?>";
fgen_maintenancesearch.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_status"};
fgen_maintenancesearch.Lists["x_status"].Data = "<?php echo $gen_maintenance_search->status->LookupFilterQuery(FALSE, "search") ?>";
fgen_maintenancesearch.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenancesearch.Lists["x_initiator_action"].Options = <?php echo json_encode($gen_maintenance_search->initiator_action->Options()) ?>;
fgen_maintenancesearch.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenancesearch.Lists["x_approver_action"].Options = <?php echo json_encode($gen_maintenance_search->approver_action->Options()) ?>;
fgen_maintenancesearch.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenancesearch.Lists["x_approved_by"].Data = "<?php echo $gen_maintenance_search->approved_by->LookupFilterQuery(FALSE, "search") ?>";
fgen_maintenancesearch.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $gen_maintenance_search->approved_by->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

fgen_maintenancesearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->id->FldErrMsg()) ?>");
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
	elm = this.GetElements("x" + infix + "_flag");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenance->flag->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $gen_maintenance_search->ShowPageHeader(); ?>
<?php
$gen_maintenance_search->ShowMessage();
?>
<form name="fgen_maintenancesearch" id="fgen_maintenancesearch" class="<?php echo $gen_maintenance_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gen_maintenance_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gen_maintenance_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gen_maintenance">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($gen_maintenance_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($gen_maintenance->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_id"><?php echo $gen_maintenance->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->id->CellAttributes() ?>>
			<span id="el_gen_maintenance_id">
<input type="text" data-table="gen_maintenance" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->id->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->id->EditValue ?>"<?php echo $gen_maintenance->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->datetime->Visible) { // datetime ?>
	<div id="r_datetime" class="form-group">
		<label for="x_datetime" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_datetime"><?php echo $gen_maintenance->datetime->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_datetime" id="z_datetime" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->datetime->CellAttributes() ?>>
			<span id="el_gen_maintenance_datetime">
<input type="text" data-table="gen_maintenance" data-field="x_datetime" name="x_datetime" id="x_datetime" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->datetime->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->datetime->EditValue ?>"<?php echo $gen_maintenance->datetime->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label for="x_reference_id" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_reference_id"><?php echo $gen_maintenance->reference_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->reference_id->CellAttributes() ?>>
			<span id="el_gen_maintenance_reference_id">
<input type="text" data-table="gen_maintenance" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->reference_id->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->reference_id->EditValue ?>"<?php echo $gen_maintenance->reference_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->gen_name->Visible) { // gen_name ?>
	<div id="r_gen_name" class="form-group">
		<label for="x_gen_name" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_gen_name"><?php echo $gen_maintenance->gen_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_gen_name" id="z_gen_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->gen_name->CellAttributes() ?>>
			<span id="el_gen_maintenance_gen_name">
<select data-table="gen_maintenance" data-field="x_gen_name" data-value-separator="<?php echo $gen_maintenance->gen_name->DisplayValueSeparatorAttribute() ?>" id="x_gen_name" name="x_gen_name"<?php echo $gen_maintenance->gen_name->EditAttributes() ?>>
<?php echo $gen_maintenance->gen_name->SelectOptionListHtml("x_gen_name") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->maintenance_type->Visible) { // maintenance_type ?>
	<div id="r_maintenance_type" class="form-group">
		<label for="x_maintenance_type" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_maintenance_type"><?php echo $gen_maintenance->maintenance_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maintenance_type" id="z_maintenance_type" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->maintenance_type->CellAttributes() ?>>
			<span id="el_gen_maintenance_maintenance_type">
<select data-table="gen_maintenance" data-field="x_maintenance_type" data-value-separator="<?php echo $gen_maintenance->maintenance_type->DisplayValueSeparatorAttribute() ?>" id="x_maintenance_type" name="x_maintenance_type"<?php echo $gen_maintenance->maintenance_type->EditAttributes() ?>>
<?php echo $gen_maintenance->maintenance_type->SelectOptionListHtml("x_maintenance_type") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->running_hours->Visible) { // running_hours ?>
	<div id="r_running_hours" class="form-group">
		<label for="x_running_hours" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_running_hours"><?php echo $gen_maintenance->running_hours->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_running_hours" id="z_running_hours" value="LIKE"></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->running_hours->CellAttributes() ?>>
			<span id="el_gen_maintenance_running_hours">
<input type="text" data-table="gen_maintenance" data-field="x_running_hours" name="x_running_hours" id="x_running_hours" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->running_hours->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->running_hours->EditValue ?>"<?php echo $gen_maintenance->running_hours->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->cost->Visible) { // cost ?>
	<div id="r_cost" class="form-group">
		<label for="x_cost" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_cost"><?php echo $gen_maintenance->cost->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_cost" id="z_cost" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->cost->CellAttributes() ?>>
			<span id="el_gen_maintenance_cost">
<input type="text" data-table="gen_maintenance" data-field="x_cost" name="x_cost" id="x_cost" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->cost->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->cost->EditValue ?>"<?php echo $gen_maintenance->cost->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->labour_fee->Visible) { // labour_fee ?>
	<div id="r_labour_fee" class="form-group">
		<label for="x_labour_fee" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_labour_fee"><?php echo $gen_maintenance->labour_fee->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_labour_fee" id="z_labour_fee" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->labour_fee->CellAttributes() ?>>
			<span id="el_gen_maintenance_labour_fee">
<input type="text" data-table="gen_maintenance" data-field="x_labour_fee" name="x_labour_fee" id="x_labour_fee" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->labour_fee->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->labour_fee->EditValue ?>"<?php echo $gen_maintenance->labour_fee->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label for="x_total" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_total"><?php echo $gen_maintenance->total->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_total" id="z_total" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->total->CellAttributes() ?>>
			<span id="el_gen_maintenance_total">
<input type="text" data-table="gen_maintenance" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->total->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->total->EditValue ?>"<?php echo $gen_maintenance->total->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label for="x_staff_id" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_staff_id"><?php echo $gen_maintenance->staff_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->staff_id->CellAttributes() ?>>
			<span id="el_gen_maintenance_staff_id">
<select data-table="gen_maintenance" data-field="x_staff_id" data-value-separator="<?php echo $gen_maintenance->staff_id->DisplayValueSeparatorAttribute() ?>" id="x_staff_id" name="x_staff_id"<?php echo $gen_maintenance->staff_id->EditAttributes() ?>>
<?php echo $gen_maintenance->staff_id->SelectOptionListHtml("x_staff_id") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label for="x_status" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_status"><?php echo $gen_maintenance->status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->status->CellAttributes() ?>>
			<span id="el_gen_maintenance_status">
<select data-table="gen_maintenance" data-field="x_status" data-value-separator="<?php echo $gen_maintenance->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $gen_maintenance->status->EditAttributes() ?>>
<?php echo $gen_maintenance->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_initiator_action"><?php echo $gen_maintenance->initiator_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_initiator_action" id="z_initiator_action" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->initiator_action->CellAttributes() ?>>
			<span id="el_gen_maintenance_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="gen_maintenance" data-field="x_initiator_action" data-value-separator="<?php echo $gen_maintenance->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $gen_maintenance->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $gen_maintenance->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label for="x_initiator_comment" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_initiator_comment"><?php echo $gen_maintenance->initiator_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_initiator_comment" id="z_initiator_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->initiator_comment->CellAttributes() ?>>
			<span id="el_gen_maintenance_initiator_comment">
<input type="text" data-table="gen_maintenance" data-field="x_initiator_comment" name="x_initiator_comment" id="x_initiator_comment" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->initiator_comment->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->initiator_comment->EditValue ?>"<?php echo $gen_maintenance->initiator_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label for="x_approver_date" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_approver_date"><?php echo $gen_maintenance->approver_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_date" id="z_approver_date" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->approver_date->CellAttributes() ?>>
			<span id="el_gen_maintenance_approver_date">
<input type="text" data-table="gen_maintenance" data-field="x_approver_date" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approver_date->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->approver_date->EditValue ?>"<?php echo $gen_maintenance->approver_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_approver_action"><?php echo $gen_maintenance->approver_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_action" id="z_approver_action" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->approver_action->CellAttributes() ?>>
			<span id="el_gen_maintenance_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="gen_maintenance" data-field="x_approver_action" data-value-separator="<?php echo $gen_maintenance->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $gen_maintenance->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $gen_maintenance->approver_action->RadioButtonListHtml(FALSE, "x_approver_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label for="x_approver_comment" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_approver_comment"><?php echo $gen_maintenance->approver_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approver_comment" id="z_approver_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->approver_comment->CellAttributes() ?>>
			<span id="el_gen_maintenance_approver_comment">
<input type="text" data-table="gen_maintenance" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approver_comment->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->approver_comment->EditValue ?>"<?php echo $gen_maintenance->approver_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_approved_by"><?php echo $gen_maintenance->approved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_by" id="z_approved_by" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->approved_by->CellAttributes() ?>>
			<span id="el_gen_maintenance_approved_by">
<?php
$wrkonchange = trim(" " . @$gen_maintenance->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$gen_maintenance->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $gen_maintenance->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->getPlaceHolder()) ?>"<?php echo $gen_maintenance->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="gen_maintenance" data-field="x_approved_by" data-value-separator="<?php echo $gen_maintenance->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($gen_maintenance->approved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fgen_maintenancesearch.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenance->flag->Visible) { // flag ?>
	<div id="r_flag" class="form-group">
		<label for="x_flag" class="<?php echo $gen_maintenance_search->LeftColumnClass ?>"><span id="elh_gen_maintenance_flag"><?php echo $gen_maintenance->flag->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_flag" id="z_flag" value="="></p>
		</label>
		<div class="<?php echo $gen_maintenance_search->RightColumnClass ?>"><div<?php echo $gen_maintenance->flag->CellAttributes() ?>>
			<span id="el_gen_maintenance_flag">
<input type="text" data-table="gen_maintenance" data-field="x_flag" name="x_flag" id="x_flag" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenance->flag->getPlaceHolder()) ?>" value="<?php echo $gen_maintenance->flag->EditValue ?>"<?php echo $gen_maintenance->flag->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$gen_maintenance_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $gen_maintenance_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fgen_maintenancesearch.Init();
</script>
<?php
$gen_maintenance_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gen_maintenance_search->Page_Terminate();
?>
