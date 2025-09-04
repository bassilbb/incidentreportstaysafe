<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "inventory_staysafe_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$inventory_staysafe_report_search = NULL; // Initialize page object first

class cinventory_staysafe_report_search extends cinventory_staysafe_report {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'inventory_staysafe_report';

	// Page object name
	var $PageObjName = 'inventory_staysafe_report_search';

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

		// Table object (inventory_staysafe_report)
		if (!isset($GLOBALS["inventory_staysafe_report"]) || get_class($GLOBALS["inventory_staysafe_report"]) == "cinventory_staysafe_report") {
			$GLOBALS["inventory_staysafe_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventory_staysafe_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory_staysafe_report');

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
				$this->Page_Terminate(ew_GetUrl("inventory_staysafe_reportlist.php"));
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
		$this->date_recieved->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->quantity->SetVisibility();
		$this->recieved_by->SetVisibility();
		$this->recieved_action->SetVisibility();
		$this->recieved_comment->SetVisibility();
		$this->date_approved->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approver_comment->SetVisibility();
		$this->statuss->SetVisibility();
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
		global $EW_EXPORT, $inventory_staysafe_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventory_staysafe_report);
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
					if ($pageName == "inventory_staysafe_reportview.php")
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
						$sSrchStr = "inventory_staysafe_reportlist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->date_recieved); // date_recieved
		$this->BuildSearchUrl($sSrchUrl, $this->reference_id); // reference_id
		$this->BuildSearchUrl($sSrchUrl, $this->staff_id); // staff_id
		$this->BuildSearchUrl($sSrchUrl, $this->material_name); // material_name
		$this->BuildSearchUrl($sSrchUrl, $this->type); // type
		$this->BuildSearchUrl($sSrchUrl, $this->capacity); // capacity
		$this->BuildSearchUrl($sSrchUrl, $this->quantity); // quantity
		$this->BuildSearchUrl($sSrchUrl, $this->recieved_by); // recieved_by
		$this->BuildSearchUrl($sSrchUrl, $this->recieved_action); // recieved_action
		$this->BuildSearchUrl($sSrchUrl, $this->recieved_comment); // recieved_comment
		$this->BuildSearchUrl($sSrchUrl, $this->date_approved); // date_approved
		$this->BuildSearchUrl($sSrchUrl, $this->approver_action); // approver_action
		$this->BuildSearchUrl($sSrchUrl, $this->approver_comment); // approver_comment
		$this->BuildSearchUrl($sSrchUrl, $this->statuss); // statuss
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

		// date_recieved
		$this->date_recieved->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_recieved");
		$this->date_recieved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_recieved");
		$this->date_recieved->AdvancedSearch->SearchCondition = $objForm->GetValue("v_date_recieved");
		$this->date_recieved->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_date_recieved");
		$this->date_recieved->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_date_recieved");

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_reference_id");
		$this->reference_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_reference_id");

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_staff_id");
		$this->staff_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staff_id");

		// material_name
		$this->material_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_material_name");
		$this->material_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_material_name");

		// type
		$this->type->AdvancedSearch->SearchValue = $objForm->GetValue("x_type");
		$this->type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_type");

		// capacity
		$this->capacity->AdvancedSearch->SearchValue = $objForm->GetValue("x_capacity");
		$this->capacity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_capacity");

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity");
		$this->quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity");

		// recieved_by
		$this->recieved_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_recieved_by");
		$this->recieved_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recieved_by");

		// recieved_action
		$this->recieved_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_recieved_action");
		$this->recieved_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recieved_action");

		// recieved_comment
		$this->recieved_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_recieved_comment");
		$this->recieved_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recieved_comment");

		// date_approved
		$this->date_approved->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_approved");
		$this->date_approved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_approved");

		// approver_action
		$this->approver_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_action");
		$this->approver_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_action");

		// approver_comment
		$this->approver_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_approver_comment");
		$this->approver_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approver_comment");

		// statuss
		$this->statuss->AdvancedSearch->SearchValue = $objForm->GetValue("x_statuss");
		$this->statuss->AdvancedSearch->SearchOperator = $objForm->GetValue("z_statuss");

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
		// date_recieved
		// reference_id
		// staff_id
		// material_name
		// type
		// capacity
		// quantity
		// recieved_by
		// recieved_action
		// recieved_comment
		// date_approved
		// approver_action
		// approver_comment
		// statuss
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->ViewValue = $this->date_recieved->CurrentValue;
		$this->date_recieved->ViewValue = ew_FormatDateTime($this->date_recieved->ViewValue, 0);
		$this->date_recieved->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

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

		// material_name
		$this->material_name->ViewValue = $this->material_name->CurrentValue;
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
		$sWhereWrk = "";
		$this->material_name->LookupFilters = array();
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

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// recieved_by
		$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
		if (strval($this->recieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recieved_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

		// recieved_action
		if (strval($this->recieved_action->CurrentValue) <> "") {
			$this->recieved_action->ViewValue = $this->recieved_action->OptionCaption($this->recieved_action->CurrentValue);
		} else {
			$this->recieved_action->ViewValue = NULL;
		}
		$this->recieved_action->ViewCustomAttributes = "";

		// recieved_comment
		$this->recieved_comment->ViewValue = $this->recieved_comment->CurrentValue;
		$this->recieved_comment->ViewCustomAttributes = "";

		// date_approved
		$this->date_approved->ViewValue = $this->date_approved->CurrentValue;
		$this->date_approved->ViewValue = ew_FormatDateTime($this->date_approved->ViewValue, 0);
		$this->date_approved->ViewCustomAttributes = "";

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

			// date_recieved
			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";
			$this->date_recieved->TooltipValue = "";

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

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";
			$this->capacity->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";
			$this->recieved_by->TooltipValue = "";

			// recieved_action
			$this->recieved_action->LinkCustomAttributes = "";
			$this->recieved_action->HrefValue = "";
			$this->recieved_action->TooltipValue = "";

			// recieved_comment
			$this->recieved_comment->LinkCustomAttributes = "";
			$this->recieved_comment->HrefValue = "";
			$this->recieved_comment->TooltipValue = "";

			// date_approved
			$this->date_approved->LinkCustomAttributes = "";
			$this->date_approved->HrefValue = "";
			$this->date_approved->TooltipValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";
			$this->approver_action->TooltipValue = "";

			// approver_comment
			$this->approver_comment->LinkCustomAttributes = "";
			$this->approver_comment->HrefValue = "";
			$this->approver_comment->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

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

			// date_recieved
			$this->date_recieved->EditAttrs["class"] = "form-control";
			$this->date_recieved->EditCustomAttributes = "";
			$this->date_recieved->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_recieved->AdvancedSearch->SearchValue, 0), 8));
			$this->date_recieved->PlaceHolder = ew_RemoveHtml($this->date_recieved->FldCaption());
			$this->date_recieved->EditAttrs["class"] = "form-control";
			$this->date_recieved->EditCustomAttributes = "";
			$this->date_recieved->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_recieved->AdvancedSearch->SearchValue2, 0), 8));
			$this->date_recieved->PlaceHolder = ew_RemoveHtml($this->date_recieved->FldCaption());

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
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// material_name
			$this->material_name->EditAttrs["class"] = "form-control";
			$this->material_name->EditCustomAttributes = "";
			$this->material_name->EditValue = ew_HtmlEncode($this->material_name->AdvancedSearch->SearchValue);
			if (strval($this->material_name->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
			$sWhereWrk = "";
			$this->material_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->material_name->EditValue = $this->material_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->material_name->EditValue = ew_HtmlEncode($this->material_name->AdvancedSearch->SearchValue);
				}
			} else {
				$this->material_name->EditValue = NULL;
			}
			$this->material_name->PlaceHolder = ew_RemoveHtml($this->material_name->FldCaption());

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

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->AdvancedSearch->SearchValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// recieved_by
			$this->recieved_by->EditAttrs["class"] = "form-control";
			$this->recieved_by->EditCustomAttributes = "";
			$this->recieved_by->EditValue = ew_HtmlEncode($this->recieved_by->AdvancedSearch->SearchValue);
			if (strval($this->recieved_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->recieved_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->recieved_by->EditValue = $this->recieved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->recieved_by->EditValue = ew_HtmlEncode($this->recieved_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->recieved_by->EditValue = NULL;
			}
			$this->recieved_by->PlaceHolder = ew_RemoveHtml($this->recieved_by->FldCaption());

			// recieved_action
			$this->recieved_action->EditCustomAttributes = "";
			$this->recieved_action->EditValue = $this->recieved_action->Options(FALSE);

			// recieved_comment
			$this->recieved_comment->EditAttrs["class"] = "form-control";
			$this->recieved_comment->EditCustomAttributes = "";
			$this->recieved_comment->EditValue = ew_HtmlEncode($this->recieved_comment->AdvancedSearch->SearchValue);
			$this->recieved_comment->PlaceHolder = ew_RemoveHtml($this->recieved_comment->FldCaption());

			// date_approved
			$this->date_approved->EditAttrs["class"] = "form-control";
			$this->date_approved->EditCustomAttributes = "";
			$this->date_approved->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_approved->AdvancedSearch->SearchValue, 0), 8));
			$this->date_approved->PlaceHolder = ew_RemoveHtml($this->date_approved->FldCaption());

			// approver_action
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

			// approver_comment
			$this->approver_comment->EditAttrs["class"] = "form-control";
			$this->approver_comment->EditCustomAttributes = "";
			$this->approver_comment->EditValue = ew_HtmlEncode($this->approver_comment->AdvancedSearch->SearchValue);
			$this->approver_comment->PlaceHolder = ew_RemoveHtml($this->approver_comment->FldCaption());

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
		if (!ew_CheckDateDef($this->date_approved->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_approved->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->verified_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->verified_date->FldErrMsg());
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
		$this->date_recieved->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->material_name->AdvancedSearch->Load();
		$this->type->AdvancedSearch->Load();
		$this->capacity->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->recieved_by->AdvancedSearch->Load();
		$this->recieved_action->AdvancedSearch->Load();
		$this->recieved_comment->AdvancedSearch->Load();
		$this->date_approved->AdvancedSearch->Load();
		$this->approver_action->AdvancedSearch->Load();
		$this->approver_comment->AdvancedSearch->Load();
		$this->statuss->AdvancedSearch->Load();
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inventory_staysafe_reportlist.php"), "", $this->TableVar, TRUE);
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
		case "x_material_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_recieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_material_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld` FROM `inventory_staysafe`";
			$sWhereWrk = "`material_name` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_recieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->recieved_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->recieved_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($inventory_staysafe_report_search)) $inventory_staysafe_report_search = new cinventory_staysafe_report_search();

// Page init
$inventory_staysafe_report_search->Page_Init();

// Page main
$inventory_staysafe_report_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_staysafe_report_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($inventory_staysafe_report_search->IsModal) { ?>
var CurrentAdvancedSearchForm = finventory_staysafe_reportsearch = new ew_Form("finventory_staysafe_reportsearch", "search");
<?php } else { ?>
var CurrentForm = finventory_staysafe_reportsearch = new ew_Form("finventory_staysafe_reportsearch", "search");
<?php } ?>

// Form_CustomValidate event
finventory_staysafe_reportsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventory_staysafe_reportsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventory_staysafe_reportsearch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_staysafe_reportsearch.Lists["x_staff_id"].Data = "<?php echo $inventory_staysafe_report_search->staff_id->LookupFilterQuery(FALSE, "search") ?>";
finventory_staysafe_reportsearch.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_staysafe_report_search->staff_id->LookupFilterQuery(TRUE, "search"))) ?>;
finventory_staysafe_reportsearch.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory_staysafe"};
finventory_staysafe_reportsearch.Lists["x_material_name"].Data = "<?php echo $inventory_staysafe_report_search->material_name->LookupFilterQuery(FALSE, "search") ?>";
finventory_staysafe_reportsearch.AutoSuggests["x_material_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_staysafe_report_search->material_name->LookupFilterQuery(TRUE, "search"))) ?>;
finventory_staysafe_reportsearch.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_staysafe_reportsearch.Lists["x_recieved_by"].Data = "<?php echo $inventory_staysafe_report_search->recieved_by->LookupFilterQuery(FALSE, "search") ?>";
finventory_staysafe_reportsearch.AutoSuggests["x_recieved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_staysafe_report_search->recieved_by->LookupFilterQuery(TRUE, "search"))) ?>;
finventory_staysafe_reportsearch.Lists["x_recieved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_staysafe_reportsearch.Lists["x_recieved_action"].Options = <?php echo json_encode($inventory_staysafe_report_search->recieved_action->Options()) ?>;
finventory_staysafe_reportsearch.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_staysafe_reportsearch.Lists["x_approver_action"].Options = <?php echo json_encode($inventory_staysafe_report_search->approver_action->Options()) ?>;
finventory_staysafe_reportsearch.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
finventory_staysafe_reportsearch.Lists["x_statuss"].Data = "<?php echo $inventory_staysafe_report_search->statuss->LookupFilterQuery(FALSE, "search") ?>";
finventory_staysafe_reportsearch.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_staysafe_reportsearch.Lists["x_approved_by"].Data = "<?php echo $inventory_staysafe_report_search->approved_by->LookupFilterQuery(FALSE, "search") ?>";
finventory_staysafe_reportsearch.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_staysafe_report_search->approved_by->LookupFilterQuery(TRUE, "search"))) ?>;
finventory_staysafe_reportsearch.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_staysafe_reportsearch.Lists["x_verified_action"].Options = <?php echo json_encode($inventory_staysafe_report_search->verified_action->Options()) ?>;
finventory_staysafe_reportsearch.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_staysafe_reportsearch.Lists["x_verified_by"].Data = "<?php echo $inventory_staysafe_report_search->verified_by->LookupFilterQuery(FALSE, "search") ?>";
finventory_staysafe_reportsearch.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_staysafe_report_search->verified_by->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

finventory_staysafe_reportsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_staysafe_report->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_approved");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_staysafe_report->date_approved->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_verified_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_staysafe_report->verified_date->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $inventory_staysafe_report_search->ShowPageHeader(); ?>
<?php
$inventory_staysafe_report_search->ShowMessage();
?>
<form name="finventory_staysafe_reportsearch" id="finventory_staysafe_reportsearch" class="<?php echo $inventory_staysafe_report_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_staysafe_report_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_staysafe_report_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory_staysafe_report">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($inventory_staysafe_report_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($inventory_staysafe_report->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_id"><?php echo $inventory_staysafe_report->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->id->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_id">
<input type="text" data-table="inventory_staysafe_report" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->id->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->id->EditValue ?>"<?php echo $inventory_staysafe_report->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->date_recieved->Visible) { // date_recieved ?>
	<div id="r_date_recieved" class="form-group">
		<label for="x_date_recieved" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_date_recieved"><?php echo $inventory_staysafe_report->date_recieved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date_recieved" id="z_date_recieved" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->date_recieved->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_date_recieved">
<input type="text" data-table="inventory_staysafe_report" data-field="x_date_recieved" name="x_date_recieved" id="x_date_recieved" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->date_recieved->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->date_recieved->EditValue ?>"<?php echo $inventory_staysafe_report->date_recieved->EditAttributes() ?>>
<?php if (!$inventory_staysafe_report->date_recieved->ReadOnly && !$inventory_staysafe_report->date_recieved->Disabled && !isset($inventory_staysafe_report->date_recieved->EditAttrs["readonly"]) && !isset($inventory_staysafe_report->date_recieved->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finventory_staysafe_reportsearch", "x_date_recieved", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_date_recieved">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_inventory_staysafe_report_date_recieved" class="btw1_date_recieved">
<input type="text" data-table="inventory_staysafe_report" data-field="x_date_recieved" name="y_date_recieved" id="y_date_recieved" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->date_recieved->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->date_recieved->EditValue2 ?>"<?php echo $inventory_staysafe_report->date_recieved->EditAttributes() ?>>
<?php if (!$inventory_staysafe_report->date_recieved->ReadOnly && !$inventory_staysafe_report->date_recieved->Disabled && !isset($inventory_staysafe_report->date_recieved->EditAttrs["readonly"]) && !isset($inventory_staysafe_report->date_recieved->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finventory_staysafe_reportsearch", "y_date_recieved", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label for="x_reference_id" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_reference_id"><?php echo $inventory_staysafe_report->reference_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->reference_id->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_reference_id">
<input type="text" data-table="inventory_staysafe_report" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->reference_id->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->reference_id->EditValue ?>"<?php echo $inventory_staysafe_report->reference_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_staff_id"><?php echo $inventory_staysafe_report->staff_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->staff_id->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_staff_id">
<?php
$wrkonchange = trim(" " . @$inventory_staysafe_report->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_staysafe_report->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $inventory_staysafe_report->staff_id->EditValue ?>" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->staff_id->getPlaceHolder()) ?>"<?php echo $inventory_staysafe_report->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_staysafe_report" data-field="x_staff_id" data-value-separator="<?php echo $inventory_staysafe_report->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($inventory_staysafe_report->staff_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_staysafe_reportsearch.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_material_name"><?php echo $inventory_staysafe_report->material_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_material_name" id="z_material_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->material_name->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_material_name">
<?php
$wrkonchange = trim(" " . @$inventory_staysafe_report->material_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_staysafe_report->material_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_material_name" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_material_name" id="sv_x_material_name" value="<?php echo $inventory_staysafe_report->material_name->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->material_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->material_name->getPlaceHolder()) ?>"<?php echo $inventory_staysafe_report->material_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_staysafe_report" data-field="x_material_name" data-value-separator="<?php echo $inventory_staysafe_report->material_name->DisplayValueSeparatorAttribute() ?>" name="x_material_name" id="x_material_name" value="<?php echo ew_HtmlEncode($inventory_staysafe_report->material_name->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_staysafe_reportsearch.CreateAutoSuggest({"id":"x_material_name","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label for="x_type" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_type"><?php echo $inventory_staysafe_report->type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_type" id="z_type" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->type->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_type">
<input type="text" data-table="inventory_staysafe_report" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->type->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->type->EditValue ?>"<?php echo $inventory_staysafe_report->type->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->capacity->Visible) { // capacity ?>
	<div id="r_capacity" class="form-group">
		<label for="x_capacity" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_capacity"><?php echo $inventory_staysafe_report->capacity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_capacity" id="z_capacity" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->capacity->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_capacity">
<input type="text" data-table="inventory_staysafe_report" data-field="x_capacity" name="x_capacity" id="x_capacity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->capacity->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->capacity->EditValue ?>"<?php echo $inventory_staysafe_report->capacity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label for="x_quantity" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_quantity"><?php echo $inventory_staysafe_report->quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity" id="z_quantity" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->quantity->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_quantity">
<input type="text" data-table="inventory_staysafe_report" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->quantity->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->quantity->EditValue ?>"<?php echo $inventory_staysafe_report->quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->recieved_by->Visible) { // recieved_by ?>
	<div id="r_recieved_by" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_recieved_by"><?php echo $inventory_staysafe_report->recieved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_recieved_by" id="z_recieved_by" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->recieved_by->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_recieved_by">
<?php
$wrkonchange = trim(" " . @$inventory_staysafe_report->recieved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_staysafe_report->recieved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_recieved_by" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_recieved_by" id="sv_x_recieved_by" value="<?php echo $inventory_staysafe_report->recieved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->recieved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->recieved_by->getPlaceHolder()) ?>"<?php echo $inventory_staysafe_report->recieved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_staysafe_report" data-field="x_recieved_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $inventory_staysafe_report->recieved_by->DisplayValueSeparatorAttribute() ?>" name="x_recieved_by" id="x_recieved_by" value="<?php echo ew_HtmlEncode($inventory_staysafe_report->recieved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_staysafe_reportsearch.CreateAutoSuggest({"id":"x_recieved_by","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($inventory_staysafe_report->recieved_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_recieved_by',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($inventory_staysafe_report->recieved_by->ReadOnly || $inventory_staysafe_report->recieved_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->recieved_action->Visible) { // recieved_action ?>
	<div id="r_recieved_action" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_recieved_action"><?php echo $inventory_staysafe_report->recieved_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_recieved_action" id="z_recieved_action" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->recieved_action->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_recieved_action">
<div id="tp_x_recieved_action" class="ewTemplate"><input type="radio" data-table="inventory_staysafe_report" data-field="x_recieved_action" data-value-separator="<?php echo $inventory_staysafe_report->recieved_action->DisplayValueSeparatorAttribute() ?>" name="x_recieved_action" id="x_recieved_action" value="{value}"<?php echo $inventory_staysafe_report->recieved_action->EditAttributes() ?>></div>
<div id="dsl_x_recieved_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory_staysafe_report->recieved_action->RadioButtonListHtml(FALSE, "x_recieved_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->recieved_comment->Visible) { // recieved_comment ?>
	<div id="r_recieved_comment" class="form-group">
		<label for="x_recieved_comment" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_recieved_comment"><?php echo $inventory_staysafe_report->recieved_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_recieved_comment" id="z_recieved_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->recieved_comment->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_recieved_comment">
<input type="text" data-table="inventory_staysafe_report" data-field="x_recieved_comment" name="x_recieved_comment" id="x_recieved_comment" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->recieved_comment->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->recieved_comment->EditValue ?>"<?php echo $inventory_staysafe_report->recieved_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->date_approved->Visible) { // date_approved ?>
	<div id="r_date_approved" class="form-group">
		<label for="x_date_approved" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_date_approved"><?php echo $inventory_staysafe_report->date_approved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_approved" id="z_date_approved" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->date_approved->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_date_approved">
<input type="text" data-table="inventory_staysafe_report" data-field="x_date_approved" name="x_date_approved" id="x_date_approved" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->date_approved->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->date_approved->EditValue ?>"<?php echo $inventory_staysafe_report->date_approved->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_approver_action"><?php echo $inventory_staysafe_report->approver_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approver_action" id="z_approver_action" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->approver_action->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="inventory_staysafe_report" data-field="x_approver_action" data-value-separator="<?php echo $inventory_staysafe_report->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $inventory_staysafe_report->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory_staysafe_report->approver_action->RadioButtonListHtml(FALSE, "x_approver_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label for="x_approver_comment" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_approver_comment"><?php echo $inventory_staysafe_report->approver_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approver_comment" id="z_approver_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->approver_comment->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_approver_comment">
<input type="text" data-table="inventory_staysafe_report" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->approver_comment->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->approver_comment->EditValue ?>"<?php echo $inventory_staysafe_report->approver_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label for="x_statuss" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_statuss"><?php echo $inventory_staysafe_report->statuss->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_statuss" id="z_statuss" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->statuss->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_statuss">
<select data-table="inventory_staysafe_report" data-field="x_statuss" data-value-separator="<?php echo $inventory_staysafe_report->statuss->DisplayValueSeparatorAttribute() ?>" id="x_statuss" name="x_statuss"<?php echo $inventory_staysafe_report->statuss->EditAttributes() ?>>
<?php echo $inventory_staysafe_report->statuss->SelectOptionListHtml("x_statuss") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_approved_by"><?php echo $inventory_staysafe_report->approved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_by" id="z_approved_by" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->approved_by->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_approved_by">
<?php
$wrkonchange = trim(" " . @$inventory_staysafe_report->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_staysafe_report->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8840">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $inventory_staysafe_report->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->approved_by->getPlaceHolder()) ?>"<?php echo $inventory_staysafe_report->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_staysafe_report" data-field="x_approved_by" data-value-separator="<?php echo $inventory_staysafe_report->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($inventory_staysafe_report->approved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_staysafe_reportsearch.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label for="x_verified_date" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_verified_date"><?php echo $inventory_staysafe_report->verified_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_date" id="z_verified_date" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->verified_date->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_verified_date">
<input type="text" data-table="inventory_staysafe_report" data-field="x_verified_date" name="x_verified_date" id="x_verified_date" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->verified_date->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->verified_date->EditValue ?>"<?php echo $inventory_staysafe_report->verified_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_verified_action"><?php echo $inventory_staysafe_report->verified_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_action" id="z_verified_action" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->verified_action->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="inventory_staysafe_report" data-field="x_verified_action" data-value-separator="<?php echo $inventory_staysafe_report->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $inventory_staysafe_report->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory_staysafe_report->verified_action->RadioButtonListHtml(FALSE, "x_verified_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label for="x_verified_comment" class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_verified_comment"><?php echo $inventory_staysafe_report->verified_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_verified_comment" id="z_verified_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->verified_comment->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_verified_comment">
<input type="text" data-table="inventory_staysafe_report" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->verified_comment->getPlaceHolder()) ?>" value="<?php echo $inventory_staysafe_report->verified_comment->EditValue ?>"<?php echo $inventory_staysafe_report->verified_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($inventory_staysafe_report->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label class="<?php echo $inventory_staysafe_report_search->LeftColumnClass ?>"><span id="elh_inventory_staysafe_report_verified_by"><?php echo $inventory_staysafe_report->verified_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_verified_by" id="z_verified_by" value="="></p>
		</label>
		<div class="<?php echo $inventory_staysafe_report_search->RightColumnClass ?>"><div<?php echo $inventory_staysafe_report->verified_by->CellAttributes() ?>>
			<span id="el_inventory_staysafe_report_verified_by">
<?php
$wrkonchange = trim(" " . @$inventory_staysafe_report->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_staysafe_report->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $inventory_staysafe_report->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_staysafe_report->verified_by->getPlaceHolder()) ?>"<?php echo $inventory_staysafe_report->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_staysafe_report" data-field="x_verified_by" data-value-separator="<?php echo $inventory_staysafe_report->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($inventory_staysafe_report->verified_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_staysafe_reportsearch.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$inventory_staysafe_report_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $inventory_staysafe_report_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finventory_staysafe_reportsearch.Init();
</script>
<?php
$inventory_staysafe_report_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$inventory_staysafe_report_search->Page_Terminate();
?>
