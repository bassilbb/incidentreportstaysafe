<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pc_issuance_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pc_issuance_report_search = NULL; // Initialize page object first

class cpc_issuance_report_search extends cpc_issuance_report {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'pc_issuance_report';

	// Page object name
	var $PageObjName = 'pc_issuance_report_search';

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

		// Table object (pc_issuance_report)
		if (!isset($GLOBALS["pc_issuance_report"]) || get_class($GLOBALS["pc_issuance_report"]) == "cpc_issuance_report") {
			$GLOBALS["pc_issuance_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pc_issuance_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pc_issuance_report', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("pc_issuance_reportlist.php"));
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
		$this->issued_date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->asset_tag->SetVisibility();
		$this->make->SetVisibility();
		$this->color->SetVisibility();
		$this->department->SetVisibility();
		$this->designation->SetVisibility();
		$this->assign_to->SetVisibility();
		$this->date_assign->SetVisibility();
		$this->assign_action->SetVisibility();
		$this->assign_comment->SetVisibility();
		$this->assign_by->SetVisibility();
		$this->statuse->SetVisibility();
		$this->date_retrieved->SetVisibility();
		$this->retriever_action->SetVisibility();
		$this->retriever_comment->SetVisibility();
		$this->retrieved_by->SetVisibility();
		$this->staff_id->SetVisibility();

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
		global $EW_EXPORT, $pc_issuance_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pc_issuance_report);
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
					if ($pageName == "pc_issuance_reportview.php")
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
	var $MultiPages; // Multi pages object

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
						$sSrchStr = "pc_issuance_reportlist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->issued_date); // issued_date
		$this->BuildSearchUrl($sSrchUrl, $this->reference_id); // reference_id
		$this->BuildSearchUrl($sSrchUrl, $this->asset_tag); // asset_tag
		$this->BuildSearchUrl($sSrchUrl, $this->make); // make
		$this->BuildSearchUrl($sSrchUrl, $this->color); // color
		$this->BuildSearchUrl($sSrchUrl, $this->department); // department
		$this->BuildSearchUrl($sSrchUrl, $this->designation); // designation
		$this->BuildSearchUrl($sSrchUrl, $this->assign_to); // assign_to
		$this->BuildSearchUrl($sSrchUrl, $this->date_assign); // date_assign
		$this->BuildSearchUrl($sSrchUrl, $this->assign_action); // assign_action
		$this->BuildSearchUrl($sSrchUrl, $this->assign_comment); // assign_comment
		$this->BuildSearchUrl($sSrchUrl, $this->assign_by); // assign_by
		$this->BuildSearchUrl($sSrchUrl, $this->statuse); // statuse
		$this->BuildSearchUrl($sSrchUrl, $this->date_retrieved); // date_retrieved
		$this->BuildSearchUrl($sSrchUrl, $this->retriever_action); // retriever_action
		$this->BuildSearchUrl($sSrchUrl, $this->retriever_comment); // retriever_comment
		$this->BuildSearchUrl($sSrchUrl, $this->retrieved_by); // retrieved_by
		$this->BuildSearchUrl($sSrchUrl, $this->staff_id); // staff_id
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

		// issued_date
		$this->issued_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_issued_date");
		$this->issued_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_issued_date");
		$this->issued_date->AdvancedSearch->SearchCondition = $objForm->GetValue("v_issued_date");
		$this->issued_date->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_issued_date");
		$this->issued_date->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_issued_date");

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_reference_id");
		$this->reference_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_reference_id");

		// asset_tag
		$this->asset_tag->AdvancedSearch->SearchValue = $objForm->GetValue("x_asset_tag");
		$this->asset_tag->AdvancedSearch->SearchOperator = $objForm->GetValue("z_asset_tag");

		// make
		$this->make->AdvancedSearch->SearchValue = $objForm->GetValue("x_make");
		$this->make->AdvancedSearch->SearchOperator = $objForm->GetValue("z_make");

		// color
		$this->color->AdvancedSearch->SearchValue = $objForm->GetValue("x_color");
		$this->color->AdvancedSearch->SearchOperator = $objForm->GetValue("z_color");

		// department
		$this->department->AdvancedSearch->SearchValue = $objForm->GetValue("x_department");
		$this->department->AdvancedSearch->SearchOperator = $objForm->GetValue("z_department");

		// designation
		$this->designation->AdvancedSearch->SearchValue = $objForm->GetValue("x_designation");
		$this->designation->AdvancedSearch->SearchOperator = $objForm->GetValue("z_designation");

		// assign_to
		$this->assign_to->AdvancedSearch->SearchValue = $objForm->GetValue("x_assign_to");
		$this->assign_to->AdvancedSearch->SearchOperator = $objForm->GetValue("z_assign_to");

		// date_assign
		$this->date_assign->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_assign");
		$this->date_assign->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_assign");

		// assign_action
		$this->assign_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_assign_action");
		$this->assign_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_assign_action");

		// assign_comment
		$this->assign_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_assign_comment");
		$this->assign_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_assign_comment");

		// assign_by
		$this->assign_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_assign_by");
		$this->assign_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_assign_by");

		// statuse
		$this->statuse->AdvancedSearch->SearchValue = $objForm->GetValue("x_statuse");
		$this->statuse->AdvancedSearch->SearchOperator = $objForm->GetValue("z_statuse");
		if (is_array($this->statuse->AdvancedSearch->SearchValue)) $this->statuse->AdvancedSearch->SearchValue = implode(",", $this->statuse->AdvancedSearch->SearchValue);
		if (is_array($this->statuse->AdvancedSearch->SearchValue2)) $this->statuse->AdvancedSearch->SearchValue2 = implode(",", $this->statuse->AdvancedSearch->SearchValue2);

		// date_retrieved
		$this->date_retrieved->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_retrieved");
		$this->date_retrieved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_retrieved");

		// retriever_action
		$this->retriever_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_retriever_action");
		$this->retriever_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_retriever_action");

		// retriever_comment
		$this->retriever_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_retriever_comment");
		$this->retriever_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_retriever_comment");

		// retrieved_by
		$this->retrieved_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_retrieved_by");
		$this->retrieved_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_retrieved_by");

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_staff_id");
		$this->staff_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staff_id");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// issued_date
		// reference_id
		// asset_tag
		// make
		// color
		// department
		// designation
		// assign_to
		// date_assign
		// assign_action
		// assign_comment
		// assign_by
		// statuse
		// date_retrieved
		// retriever_action
		// retriever_comment
		// retrieved_by
		// staff_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// issued_date
		$this->issued_date->ViewValue = $this->issued_date->CurrentValue;
		$this->issued_date->ViewValue = ew_FormatDateTime($this->issued_date->ViewValue, 14);
		$this->issued_date->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// asset_tag
		$this->asset_tag->ViewValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->ViewCustomAttributes = "";

		// make
		$this->make->ViewValue = $this->make->CurrentValue;
		$this->make->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

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

		// designation
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->designation->ViewValue = $this->designation->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->designation->ViewValue = $this->designation->CurrentValue;
			}
		} else {
			$this->designation->ViewValue = NULL;
		}
		$this->designation->ViewCustomAttributes = "";

		// assign_to
		if (strval($this->assign_to->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_to->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_to->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_to, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->assign_to->ViewValue = $this->assign_to->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_to->ViewValue = $this->assign_to->CurrentValue;
			}
		} else {
			$this->assign_to->ViewValue = NULL;
		}
		$this->assign_to->ViewCustomAttributes = "";

		// date_assign
		$this->date_assign->ViewValue = $this->date_assign->CurrentValue;
		$this->date_assign->ViewValue = ew_FormatDateTime($this->date_assign->ViewValue, 17);
		$this->date_assign->ViewCustomAttributes = "";

		// assign_action
		if (strval($this->assign_action->CurrentValue) <> "") {
			$this->assign_action->ViewValue = $this->assign_action->OptionCaption($this->assign_action->CurrentValue);
		} else {
			$this->assign_action->ViewValue = NULL;
		}
		$this->assign_action->ViewCustomAttributes = "";

		// assign_comment
		$this->assign_comment->ViewValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->ViewCustomAttributes = "";

		// assign_by
		if (strval($this->assign_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->assign_by->ViewValue = $this->assign_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_by->ViewValue = $this->assign_by->CurrentValue;
			}
		} else {
			$this->assign_by->ViewValue = NULL;
		}
		$this->assign_by->ViewCustomAttributes = "";

		// statuse
		if (strval($this->statuse->CurrentValue) <> "") {
			$arwrk = explode(",", $this->statuse->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_status`";
		$sWhereWrk = "";
		$this->statuse->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->statuse, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->statuse->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->statuse->ViewValue .= $this->statuse->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->statuse->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->statuse->ViewValue = $this->statuse->CurrentValue;
			}
		} else {
			$this->statuse->ViewValue = NULL;
		}
		$this->statuse->ViewCustomAttributes = "";

		// date_retrieved
		$this->date_retrieved->ViewValue = $this->date_retrieved->CurrentValue;
		$this->date_retrieved->ViewValue = ew_FormatDateTime($this->date_retrieved->ViewValue, 17);
		$this->date_retrieved->ViewCustomAttributes = "";

		// retriever_action
		if (strval($this->retriever_action->CurrentValue) <> "") {
			$this->retriever_action->ViewValue = $this->retriever_action->OptionCaption($this->retriever_action->CurrentValue);
		} else {
			$this->retriever_action->ViewValue = NULL;
		}
		$this->retriever_action->ViewCustomAttributes = "";

		// retriever_comment
		$this->retriever_comment->ViewValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->ViewCustomAttributes = "";

		// retrieved_by
		if (strval($this->retrieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->retrieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->retrieved_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->retrieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->retrieved_by->ViewValue = $this->retrieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->retrieved_by->ViewValue = $this->retrieved_by->CurrentValue;
			}
		} else {
			$this->retrieved_by->ViewValue = NULL;
		}
		$this->retrieved_by->ViewCustomAttributes = "";

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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// issued_date
			$this->issued_date->LinkCustomAttributes = "";
			$this->issued_date->HrefValue = "";
			$this->issued_date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";
			$this->asset_tag->TooltipValue = "";

			// make
			$this->make->LinkCustomAttributes = "";
			$this->make->HrefValue = "";
			$this->make->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";
			$this->designation->TooltipValue = "";

			// assign_to
			$this->assign_to->LinkCustomAttributes = "";
			$this->assign_to->HrefValue = "";
			$this->assign_to->TooltipValue = "";

			// date_assign
			$this->date_assign->LinkCustomAttributes = "";
			$this->date_assign->HrefValue = "";
			$this->date_assign->TooltipValue = "";

			// assign_action
			$this->assign_action->LinkCustomAttributes = "";
			$this->assign_action->HrefValue = "";
			$this->assign_action->TooltipValue = "";

			// assign_comment
			$this->assign_comment->LinkCustomAttributes = "";
			$this->assign_comment->HrefValue = "";
			$this->assign_comment->TooltipValue = "";

			// assign_by
			$this->assign_by->LinkCustomAttributes = "";
			$this->assign_by->HrefValue = "";
			$this->assign_by->TooltipValue = "";

			// statuse
			$this->statuse->LinkCustomAttributes = "";
			$this->statuse->HrefValue = "";
			$this->statuse->TooltipValue = "";

			// date_retrieved
			$this->date_retrieved->LinkCustomAttributes = "";
			$this->date_retrieved->HrefValue = "";
			$this->date_retrieved->TooltipValue = "";

			// retriever_action
			$this->retriever_action->LinkCustomAttributes = "";
			$this->retriever_action->HrefValue = "";
			$this->retriever_action->TooltipValue = "";

			// retriever_comment
			$this->retriever_comment->LinkCustomAttributes = "";
			$this->retriever_comment->HrefValue = "";
			$this->retriever_comment->TooltipValue = "";

			// retrieved_by
			$this->retrieved_by->LinkCustomAttributes = "";
			$this->retrieved_by->HrefValue = "";
			$this->retrieved_by->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// issued_date
			$this->issued_date->EditAttrs["class"] = "form-control";
			$this->issued_date->EditCustomAttributes = "";
			$this->issued_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->issued_date->AdvancedSearch->SearchValue, 14), 14));
			$this->issued_date->PlaceHolder = ew_RemoveHtml($this->issued_date->FldCaption());
			$this->issued_date->EditAttrs["class"] = "form-control";
			$this->issued_date->EditCustomAttributes = "";
			$this->issued_date->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->issued_date->AdvancedSearch->SearchValue2, 14), 14));
			$this->issued_date->PlaceHolder = ew_RemoveHtml($this->issued_date->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->AdvancedSearch->SearchValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// asset_tag
			$this->asset_tag->EditAttrs["class"] = "form-control";
			$this->asset_tag->EditCustomAttributes = "";
			$this->asset_tag->EditValue = ew_HtmlEncode($this->asset_tag->AdvancedSearch->SearchValue);
			$this->asset_tag->PlaceHolder = ew_RemoveHtml($this->asset_tag->FldCaption());

			// make
			$this->make->EditAttrs["class"] = "form-control";
			$this->make->EditCustomAttributes = "";
			$this->make->EditValue = ew_HtmlEncode($this->make->AdvancedSearch->SearchValue);
			$this->make->PlaceHolder = ew_RemoveHtml($this->make->FldCaption());

			// color
			$this->color->EditAttrs["class"] = "form-control";
			$this->color->EditCustomAttributes = "";
			$this->color->EditValue = ew_HtmlEncode($this->color->AdvancedSearch->SearchValue);
			$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

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

			// designation
			$this->designation->EditCustomAttributes = "";
			if (trim(strval($this->designation->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `designation`";
			$sWhereWrk = "";
			$this->designation->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->designation->AdvancedSearch->ViewValue = $this->designation->DisplayValue($arwrk);
			} else {
				$this->designation->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->designation->EditValue = $arwrk;

			// assign_to
			$this->assign_to->EditCustomAttributes = "";
			if (trim(strval($this->assign_to->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_to->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->assign_to->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->assign_to, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->assign_to->AdvancedSearch->ViewValue = $this->assign_to->DisplayValue($arwrk);
			} else {
				$this->assign_to->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->assign_to->EditValue = $arwrk;

			// date_assign
			$this->date_assign->EditAttrs["class"] = "form-control";
			$this->date_assign->EditCustomAttributes = "";
			$this->date_assign->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_assign->AdvancedSearch->SearchValue, 17), 17));
			$this->date_assign->PlaceHolder = ew_RemoveHtml($this->date_assign->FldCaption());

			// assign_action
			$this->assign_action->EditCustomAttributes = "";
			$this->assign_action->EditValue = $this->assign_action->Options(FALSE);

			// assign_comment
			$this->assign_comment->EditAttrs["class"] = "form-control";
			$this->assign_comment->EditCustomAttributes = "";
			$this->assign_comment->EditValue = ew_HtmlEncode($this->assign_comment->AdvancedSearch->SearchValue);
			$this->assign_comment->PlaceHolder = ew_RemoveHtml($this->assign_comment->FldCaption());

			// assign_by
			$this->assign_by->EditCustomAttributes = "";
			if (trim(strval($this->assign_by->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->assign_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->assign_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->assign_by->AdvancedSearch->ViewValue = $this->assign_by->DisplayValue($arwrk);
			} else {
				$this->assign_by->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->assign_by->EditValue = $arwrk;

			// statuse
			$this->statuse->EditAttrs["class"] = "form-control";
			$this->statuse->EditCustomAttributes = "";
			if (trim(strval($this->statuse->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->statuse->AdvancedSearch->SearchValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `system_status`";
			$sWhereWrk = "";
			$this->statuse->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->statuse, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->statuse->EditValue = $arwrk;

			// date_retrieved
			$this->date_retrieved->EditAttrs["class"] = "form-control";
			$this->date_retrieved->EditCustomAttributes = "";
			$this->date_retrieved->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_retrieved->AdvancedSearch->SearchValue, 17), 17));
			$this->date_retrieved->PlaceHolder = ew_RemoveHtml($this->date_retrieved->FldCaption());

			// retriever_action
			$this->retriever_action->EditCustomAttributes = "";
			$this->retriever_action->EditValue = $this->retriever_action->Options(FALSE);

			// retriever_comment
			$this->retriever_comment->EditAttrs["class"] = "form-control";
			$this->retriever_comment->EditCustomAttributes = "";
			$this->retriever_comment->EditValue = ew_HtmlEncode($this->retriever_comment->AdvancedSearch->SearchValue);
			$this->retriever_comment->PlaceHolder = ew_RemoveHtml($this->retriever_comment->FldCaption());

			// retrieved_by
			$this->retrieved_by->EditCustomAttributes = "";
			if (trim(strval($this->retrieved_by->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->retrieved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->retrieved_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->retrieved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->retrieved_by->AdvancedSearch->ViewValue = $this->retrieved_by->DisplayValue($arwrk);
			} else {
				$this->retrieved_by->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->retrieved_by->EditValue = $arwrk;

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
		if (!ew_CheckShortEuroDate($this->date_assign->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_assign->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->date_retrieved->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_retrieved->FldErrMsg());
		}
		if (!ew_CheckInteger($this->staff_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->staff_id->FldErrMsg());
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
		$this->issued_date->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->asset_tag->AdvancedSearch->Load();
		$this->make->AdvancedSearch->Load();
		$this->color->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->designation->AdvancedSearch->Load();
		$this->assign_to->AdvancedSearch->Load();
		$this->date_assign->AdvancedSearch->Load();
		$this->assign_action->AdvancedSearch->Load();
		$this->assign_comment->AdvancedSearch->Load();
		$this->assign_by->AdvancedSearch->Load();
		$this->statuse->AdvancedSearch->Load();
		$this->date_retrieved->AdvancedSearch->Load();
		$this->retriever_action->AdvancedSearch->Load();
		$this->retriever_comment->AdvancedSearch->Load();
		$this->retrieved_by->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pc_issuance_reportlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
		case "x_designation":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_assign_to":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->assign_to, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_assign_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->assign_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_statuse":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->statuse, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_retrieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->retrieved_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($pc_issuance_report_search)) $pc_issuance_report_search = new cpc_issuance_report_search();

// Page init
$pc_issuance_report_search->Page_Init();

// Page main
$pc_issuance_report_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pc_issuance_report_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($pc_issuance_report_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fpc_issuance_reportsearch = new ew_Form("fpc_issuance_reportsearch", "search");
<?php } else { ?>
var CurrentForm = fpc_issuance_reportsearch = new ew_Form("fpc_issuance_reportsearch", "search");
<?php } ?>

// Form_CustomValidate event
fpc_issuance_reportsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpc_issuance_reportsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fpc_issuance_reportsearch.MultiPage = new ew_MultiPage("fpc_issuance_reportsearch");

// Dynamic selection lists
fpc_issuance_reportsearch.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fpc_issuance_reportsearch.Lists["x_department"].Data = "<?php echo $pc_issuance_report_search->department->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
fpc_issuance_reportsearch.Lists["x_designation"].Data = "<?php echo $pc_issuance_report_search->designation->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.Lists["x_assign_to"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportsearch.Lists["x_assign_to"].Data = "<?php echo $pc_issuance_report_search->assign_to->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.Lists["x_assign_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpc_issuance_reportsearch.Lists["x_assign_action"].Options = <?php echo json_encode($pc_issuance_report_search->assign_action->Options()) ?>;
fpc_issuance_reportsearch.Lists["x_assign_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportsearch.Lists["x_assign_by"].Data = "<?php echo $pc_issuance_report_search->assign_by->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.Lists["x_statuse[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"system_status"};
fpc_issuance_reportsearch.Lists["x_statuse[]"].Data = "<?php echo $pc_issuance_report_search->statuse->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.Lists["x_retriever_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpc_issuance_reportsearch.Lists["x_retriever_action"].Options = <?php echo json_encode($pc_issuance_report_search->retriever_action->Options()) ?>;
fpc_issuance_reportsearch.Lists["x_retrieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportsearch.Lists["x_retrieved_by"].Data = "<?php echo $pc_issuance_report_search->retrieved_by->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportsearch.Lists["x_staff_id"].Data = "<?php echo $pc_issuance_report_search->staff_id->LookupFilterQuery(FALSE, "search") ?>";
fpc_issuance_reportsearch.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $pc_issuance_report_search->staff_id->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

fpc_issuance_reportsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance_report->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_assign");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance_report->date_assign->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_retrieved");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance_report->date_retrieved->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_staff_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance_report->staff_id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pc_issuance_report_search->ShowPageHeader(); ?>
<?php
$pc_issuance_report_search->ShowMessage();
?>
<form name="fpc_issuance_reportsearch" id="fpc_issuance_reportsearch" class="<?php echo $pc_issuance_report_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pc_issuance_report_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pc_issuance_report_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pc_issuance_report">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($pc_issuance_report_search->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="pc_issuance_report_search"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $pc_issuance_report_search->MultiPages->NavStyle() ?>">
		<li<?php echo $pc_issuance_report_search->MultiPages->TabStyle("1") ?>><a href="#tab_pc_issuance_report1" data-toggle="tab"><?php echo $pc_issuance_report->PageCaption(1) ?></a></li>
		<li<?php echo $pc_issuance_report_search->MultiPages->TabStyle("2") ?>><a href="#tab_pc_issuance_report2" data-toggle="tab"><?php echo $pc_issuance_report->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $pc_issuance_report_search->MultiPages->PageStyle("1") ?>" id="tab_pc_issuance_report1"><!-- multi-page .tab-pane -->
<div class="ewSearchDiv"><!-- page* -->
<?php if ($pc_issuance_report->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_id"><?php echo $pc_issuance_report->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->id->CellAttributes() ?>>
			<span id="el_pc_issuance_report_id">
<input type="text" data-table="pc_issuance_report" data-field="x_id" data-page="1" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->id->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->id->EditValue ?>"<?php echo $pc_issuance_report->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->issued_date->Visible) { // issued_date ?>
	<div id="r_issued_date" class="form-group">
		<label for="x_issued_date" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_issued_date"><?php echo $pc_issuance_report->issued_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_issued_date" id="z_issued_date" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->issued_date->CellAttributes() ?>>
			<span id="el_pc_issuance_report_issued_date">
<input type="text" data-table="pc_issuance_report" data-field="x_issued_date" data-page="1" data-format="14" name="x_issued_date" id="x_issued_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->issued_date->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->issued_date->EditValue ?>"<?php echo $pc_issuance_report->issued_date->EditAttributes() ?>>
<?php if (!$pc_issuance_report->issued_date->ReadOnly && !$pc_issuance_report->issued_date->Disabled && !isset($pc_issuance_report->issued_date->EditAttrs["readonly"]) && !isset($pc_issuance_report->issued_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpc_issuance_reportsearch", "x_issued_date", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_issued_date">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_pc_issuance_report_issued_date" class="btw1_issued_date">
<input type="text" data-table="pc_issuance_report" data-field="x_issued_date" data-page="1" data-format="14" name="y_issued_date" id="y_issued_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->issued_date->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->issued_date->EditValue2 ?>"<?php echo $pc_issuance_report->issued_date->EditAttributes() ?>>
<?php if (!$pc_issuance_report->issued_date->ReadOnly && !$pc_issuance_report->issued_date->Disabled && !isset($pc_issuance_report->issued_date->EditAttrs["readonly"]) && !isset($pc_issuance_report->issued_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpc_issuance_reportsearch", "y_issued_date", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label for="x_reference_id" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_reference_id"><?php echo $pc_issuance_report->reference_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->reference_id->CellAttributes() ?>>
			<span id="el_pc_issuance_report_reference_id">
<input type="text" data-table="pc_issuance_report" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->reference_id->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->reference_id->EditValue ?>"<?php echo $pc_issuance_report->reference_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->asset_tag->Visible) { // asset_tag ?>
	<div id="r_asset_tag" class="form-group">
		<label for="x_asset_tag" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_asset_tag"><?php echo $pc_issuance_report->asset_tag->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_asset_tag" id="z_asset_tag" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->asset_tag->CellAttributes() ?>>
			<span id="el_pc_issuance_report_asset_tag">
<input type="text" data-table="pc_issuance_report" data-field="x_asset_tag" data-page="1" name="x_asset_tag" id="x_asset_tag" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->asset_tag->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->asset_tag->EditValue ?>"<?php echo $pc_issuance_report->asset_tag->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->make->Visible) { // make ?>
	<div id="r_make" class="form-group">
		<label for="x_make" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_make"><?php echo $pc_issuance_report->make->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_make" id="z_make" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->make->CellAttributes() ?>>
			<span id="el_pc_issuance_report_make">
<input type="text" data-table="pc_issuance_report" data-field="x_make" data-page="1" name="x_make" id="x_make" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->make->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->make->EditValue ?>"<?php echo $pc_issuance_report->make->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->color->Visible) { // color ?>
	<div id="r_color" class="form-group">
		<label for="x_color" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_color"><?php echo $pc_issuance_report->color->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_color" id="z_color" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->color->CellAttributes() ?>>
			<span id="el_pc_issuance_report_color">
<input type="text" data-table="pc_issuance_report" data-field="x_color" data-page="1" name="x_color" id="x_color" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->color->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->color->EditValue ?>"<?php echo $pc_issuance_report->color->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label for="x_department" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_department"><?php echo $pc_issuance_report->department->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_department" id="z_department" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->department->CellAttributes() ?>>
			<span id="el_pc_issuance_report_department">
<select data-table="pc_issuance_report" data-field="x_department" data-page="1" data-value-separator="<?php echo $pc_issuance_report->department->DisplayValueSeparatorAttribute() ?>" id="x_department" name="x_department"<?php echo $pc_issuance_report->department->EditAttributes() ?>>
<?php echo $pc_issuance_report->department->SelectOptionListHtml("x_department") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->designation->Visible) { // designation ?>
	<div id="r_designation" class="form-group">
		<label for="x_designation" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_designation"><?php echo $pc_issuance_report->designation->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_designation" id="z_designation" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->designation->CellAttributes() ?>>
			<span id="el_pc_issuance_report_designation">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_designation"><?php echo (strval($pc_issuance_report->designation->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->designation->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->designation->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_designation',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->designation->ReadOnly || $pc_issuance_report->designation->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_designation" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->designation->DisplayValueSeparatorAttribute() ?>" name="x_designation" id="x_designation" value="<?php echo $pc_issuance_report->designation->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->designation->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->assign_to->Visible) { // assign_to ?>
	<div id="r_assign_to" class="form-group">
		<label for="x_assign_to" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_assign_to"><?php echo $pc_issuance_report->assign_to->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assign_to" id="z_assign_to" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->assign_to->CellAttributes() ?>>
			<span id="el_pc_issuance_report_assign_to">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign_to"><?php echo (strval($pc_issuance_report->assign_to->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->assign_to->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->assign_to->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign_to',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->assign_to->ReadOnly || $pc_issuance_report->assign_to->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_assign_to" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->assign_to->DisplayValueSeparatorAttribute() ?>" name="x_assign_to" id="x_assign_to" value="<?php echo $pc_issuance_report->assign_to->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->assign_to->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->date_assign->Visible) { // date_assign ?>
	<div id="r_date_assign" class="form-group">
		<label for="x_date_assign" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_date_assign"><?php echo $pc_issuance_report->date_assign->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_assign" id="z_date_assign" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->date_assign->CellAttributes() ?>>
			<span id="el_pc_issuance_report_date_assign">
<input type="text" data-table="pc_issuance_report" data-field="x_date_assign" data-page="1" data-format="17" name="x_date_assign" id="x_date_assign" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->date_assign->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->date_assign->EditValue ?>"<?php echo $pc_issuance_report->date_assign->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->assign_action->Visible) { // assign_action ?>
	<div id="r_assign_action" class="form-group">
		<label class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_assign_action"><?php echo $pc_issuance_report->assign_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assign_action" id="z_assign_action" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->assign_action->CellAttributes() ?>>
			<span id="el_pc_issuance_report_assign_action">
<div id="tp_x_assign_action" class="ewTemplate"><input type="radio" data-table="pc_issuance_report" data-field="x_assign_action" data-page="1" data-value-separator="<?php echo $pc_issuance_report->assign_action->DisplayValueSeparatorAttribute() ?>" name="x_assign_action" id="x_assign_action" value="{value}"<?php echo $pc_issuance_report->assign_action->EditAttributes() ?>></div>
<div id="dsl_x_assign_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pc_issuance_report->assign_action->RadioButtonListHtml(FALSE, "x_assign_action", 1) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->assign_comment->Visible) { // assign_comment ?>
	<div id="r_assign_comment" class="form-group">
		<label for="x_assign_comment" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_assign_comment"><?php echo $pc_issuance_report->assign_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_assign_comment" id="z_assign_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->assign_comment->CellAttributes() ?>>
			<span id="el_pc_issuance_report_assign_comment">
<input type="text" data-table="pc_issuance_report" data-field="x_assign_comment" data-page="1" name="x_assign_comment" id="x_assign_comment" size="35" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->assign_comment->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->assign_comment->EditValue ?>"<?php echo $pc_issuance_report->assign_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->assign_by->Visible) { // assign_by ?>
	<div id="r_assign_by" class="form-group">
		<label for="x_assign_by" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_assign_by"><?php echo $pc_issuance_report->assign_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assign_by" id="z_assign_by" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->assign_by->CellAttributes() ?>>
			<span id="el_pc_issuance_report_assign_by">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign_by"><?php echo (strval($pc_issuance_report->assign_by->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->assign_by->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->assign_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign_by',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->assign_by->ReadOnly || $pc_issuance_report->assign_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_assign_by" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->assign_by->DisplayValueSeparatorAttribute() ?>" name="x_assign_by" id="x_assign_by" value="<?php echo $pc_issuance_report->assign_by->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->assign_by->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_staff_id"><?php echo $pc_issuance_report->staff_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->staff_id->CellAttributes() ?>>
			<span id="el_pc_issuance_report_staff_id">
<?php
$wrkonchange = trim(" " . @$pc_issuance_report->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$pc_issuance_report->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8810">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $pc_issuance_report->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->staff_id->getPlaceHolder()) ?>"<?php echo $pc_issuance_report->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pc_issuance_report" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $pc_issuance_report->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($pc_issuance_report->staff_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fpc_issuance_reportsearch.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $pc_issuance_report_search->MultiPages->PageStyle("2") ?>" id="tab_pc_issuance_report2"><!-- multi-page .tab-pane -->
<div class="ewSearchDiv"><!-- page* -->
<?php if ($pc_issuance_report->statuse->Visible) { // statuse ?>
	<div id="r_statuse" class="form-group">
		<label for="x_statuse" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_statuse"><?php echo $pc_issuance_report->statuse->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_statuse" id="z_statuse" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->statuse->CellAttributes() ?>>
			<span id="el_pc_issuance_report_statuse">
<select data-table="pc_issuance_report" data-field="x_statuse" data-page="2" data-value-separator="<?php echo $pc_issuance_report->statuse->DisplayValueSeparatorAttribute() ?>" id="x_statuse[]" name="x_statuse[]" multiple="multiple"<?php echo $pc_issuance_report->statuse->EditAttributes() ?>>
<?php echo $pc_issuance_report->statuse->SelectOptionListHtml("x_statuse[]") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->date_retrieved->Visible) { // date_retrieved ?>
	<div id="r_date_retrieved" class="form-group">
		<label for="x_date_retrieved" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_date_retrieved"><?php echo $pc_issuance_report->date_retrieved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_retrieved" id="z_date_retrieved" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->date_retrieved->CellAttributes() ?>>
			<span id="el_pc_issuance_report_date_retrieved">
<input type="text" data-table="pc_issuance_report" data-field="x_date_retrieved" data-page="2" data-format="17" name="x_date_retrieved" id="x_date_retrieved" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->date_retrieved->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->date_retrieved->EditValue ?>"<?php echo $pc_issuance_report->date_retrieved->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->retriever_action->Visible) { // retriever_action ?>
	<div id="r_retriever_action" class="form-group">
		<label class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_retriever_action"><?php echo $pc_issuance_report->retriever_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_retriever_action" id="z_retriever_action" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->retriever_action->CellAttributes() ?>>
			<span id="el_pc_issuance_report_retriever_action">
<div id="tp_x_retriever_action" class="ewTemplate"><input type="radio" data-table="pc_issuance_report" data-field="x_retriever_action" data-page="2" data-value-separator="<?php echo $pc_issuance_report->retriever_action->DisplayValueSeparatorAttribute() ?>" name="x_retriever_action" id="x_retriever_action" value="{value}"<?php echo $pc_issuance_report->retriever_action->EditAttributes() ?>></div>
<div id="dsl_x_retriever_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pc_issuance_report->retriever_action->RadioButtonListHtml(FALSE, "x_retriever_action", 2) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->retriever_comment->Visible) { // retriever_comment ?>
	<div id="r_retriever_comment" class="form-group">
		<label for="x_retriever_comment" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_retriever_comment"><?php echo $pc_issuance_report->retriever_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_retriever_comment" id="z_retriever_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->retriever_comment->CellAttributes() ?>>
			<span id="el_pc_issuance_report_retriever_comment">
<input type="text" data-table="pc_issuance_report" data-field="x_retriever_comment" data-page="2" name="x_retriever_comment" id="x_retriever_comment" size="35" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->retriever_comment->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->retriever_comment->EditValue ?>"<?php echo $pc_issuance_report->retriever_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance_report->retrieved_by->Visible) { // retrieved_by ?>
	<div id="r_retrieved_by" class="form-group">
		<label for="x_retrieved_by" class="<?php echo $pc_issuance_report_search->LeftColumnClass ?>"><span id="elh_pc_issuance_report_retrieved_by"><?php echo $pc_issuance_report->retrieved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_retrieved_by" id="z_retrieved_by" value="="></p>
		</label>
		<div class="<?php echo $pc_issuance_report_search->RightColumnClass ?>"><div<?php echo $pc_issuance_report->retrieved_by->CellAttributes() ?>>
			<span id="el_pc_issuance_report_retrieved_by">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_retrieved_by"><?php echo (strval($pc_issuance_report->retrieved_by->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->retrieved_by->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->retrieved_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_retrieved_by',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->retrieved_by->ReadOnly || $pc_issuance_report->retrieved_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_retrieved_by" data-page="2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->retrieved_by->DisplayValueSeparatorAttribute() ?>" name="x_retrieved_by" id="x_retrieved_by" value="<?php echo $pc_issuance_report->retrieved_by->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->retrieved_by->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$pc_issuance_report_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $pc_issuance_report_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fpc_issuance_reportsearch.Init();
</script>
<?php
$pc_issuance_report_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pc_issuance_report_search->Page_Terminate();
?>
