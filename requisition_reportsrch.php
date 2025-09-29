<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "requisition_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$requisition_report_search = NULL; // Initialize page object first

class crequisition_report_search extends crequisition_report {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'requisition_report';

	// Page object name
	var $PageObjName = 'requisition_report_search';

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

		// Table object (requisition_report)
		if (!isset($GLOBALS["requisition_report"]) || get_class($GLOBALS["requisition_report"]) == "crequisition_report") {
			$GLOBALS["requisition_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["requisition_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'requisition_report');

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
				$this->Page_Terminate(ew_GetUrl("requisition_reportlist.php"));
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
		$this->date->SetVisibility();
		$this->reference->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->outward_location->SetVisibility();
		$this->delivery_point->SetVisibility();
		$this->name->SetVisibility();
		$this->organization->SetVisibility();
		$this->designation->SetVisibility();
		$this->department->SetVisibility();
		$this->item_description->SetVisibility();
		$this->driver_name->SetVisibility();
		$this->vehicle_no->SetVisibility();
		$this->requester_action->SetVisibility();
		$this->requester_comment->SetVisibility();
		$this->date_authorized->SetVisibility();
		$this->authorizer_name->SetVisibility();
		$this->authorizer_action->SetVisibility();
		$this->authorizer_comment->SetVisibility();
		$this->status->SetVisibility();
		$this->rep_date->SetVisibility();
		$this->rep_name->SetVisibility();
		$this->outward_datetime->SetVisibility();
		$this->rep_action->SetVisibility();
		$this->rep_comment->SetVisibility();

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
		global $EW_EXPORT, $requisition_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($requisition_report);
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
					if ($pageName == "requisition_reportview.php")
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
						$sSrchStr = "requisition_reportlist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->date); // date
		$this->BuildSearchUrl($sSrchUrl, $this->reference); // reference
		$this->BuildSearchUrl($sSrchUrl, $this->staff_id); // staff_id
		$this->BuildSearchUrl($sSrchUrl, $this->outward_location); // outward_location
		$this->BuildSearchUrl($sSrchUrl, $this->delivery_point); // delivery_point
		$this->BuildSearchUrl($sSrchUrl, $this->name); // name
		$this->BuildSearchUrl($sSrchUrl, $this->organization); // organization
		$this->BuildSearchUrl($sSrchUrl, $this->designation); // designation
		$this->BuildSearchUrl($sSrchUrl, $this->department); // department
		$this->BuildSearchUrl($sSrchUrl, $this->item_description); // item_description
		$this->BuildSearchUrl($sSrchUrl, $this->driver_name); // driver_name
		$this->BuildSearchUrl($sSrchUrl, $this->vehicle_no); // vehicle_no
		$this->BuildSearchUrl($sSrchUrl, $this->requester_action); // requester_action
		$this->BuildSearchUrl($sSrchUrl, $this->requester_comment); // requester_comment
		$this->BuildSearchUrl($sSrchUrl, $this->date_authorized); // date_authorized
		$this->BuildSearchUrl($sSrchUrl, $this->authorizer_name); // authorizer_name
		$this->BuildSearchUrl($sSrchUrl, $this->authorizer_action); // authorizer_action
		$this->BuildSearchUrl($sSrchUrl, $this->authorizer_comment); // authorizer_comment
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
		$this->BuildSearchUrl($sSrchUrl, $this->rep_date); // rep_date
		$this->BuildSearchUrl($sSrchUrl, $this->rep_name); // rep_name
		$this->BuildSearchUrl($sSrchUrl, $this->outward_datetime); // outward_datetime
		$this->BuildSearchUrl($sSrchUrl, $this->rep_action); // rep_action
		$this->BuildSearchUrl($sSrchUrl, $this->rep_comment); // rep_comment
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

		// date
		$this->date->AdvancedSearch->SearchValue = $objForm->GetValue("x_date");
		$this->date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date");
		$this->date->AdvancedSearch->SearchCondition = $objForm->GetValue("v_date");
		$this->date->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_date");
		$this->date->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_date");

		// reference
		$this->reference->AdvancedSearch->SearchValue = $objForm->GetValue("x_reference");
		$this->reference->AdvancedSearch->SearchOperator = $objForm->GetValue("z_reference");

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_staff_id");
		$this->staff_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staff_id");

		// outward_location
		$this->outward_location->AdvancedSearch->SearchValue = $objForm->GetValue("x_outward_location");
		$this->outward_location->AdvancedSearch->SearchOperator = $objForm->GetValue("z_outward_location");

		// delivery_point
		$this->delivery_point->AdvancedSearch->SearchValue = $objForm->GetValue("x_delivery_point");
		$this->delivery_point->AdvancedSearch->SearchOperator = $objForm->GetValue("z_delivery_point");

		// name
		$this->name->AdvancedSearch->SearchValue = $objForm->GetValue("x_name");
		$this->name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_name");

		// organization
		$this->organization->AdvancedSearch->SearchValue = $objForm->GetValue("x_organization");
		$this->organization->AdvancedSearch->SearchOperator = $objForm->GetValue("z_organization");

		// designation
		$this->designation->AdvancedSearch->SearchValue = $objForm->GetValue("x_designation");
		$this->designation->AdvancedSearch->SearchOperator = $objForm->GetValue("z_designation");

		// department
		$this->department->AdvancedSearch->SearchValue = $objForm->GetValue("x_department");
		$this->department->AdvancedSearch->SearchOperator = $objForm->GetValue("z_department");

		// item_description
		$this->item_description->AdvancedSearch->SearchValue = $objForm->GetValue("x_item_description");
		$this->item_description->AdvancedSearch->SearchOperator = $objForm->GetValue("z_item_description");

		// driver_name
		$this->driver_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_driver_name");
		$this->driver_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_driver_name");

		// vehicle_no
		$this->vehicle_no->AdvancedSearch->SearchValue = $objForm->GetValue("x_vehicle_no");
		$this->vehicle_no->AdvancedSearch->SearchOperator = $objForm->GetValue("z_vehicle_no");

		// requester_action
		$this->requester_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_requester_action");
		$this->requester_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_requester_action");

		// requester_comment
		$this->requester_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_requester_comment");
		$this->requester_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_requester_comment");

		// date_authorized
		$this->date_authorized->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_authorized");
		$this->date_authorized->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_authorized");

		// authorizer_name
		$this->authorizer_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_authorizer_name");
		$this->authorizer_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_authorizer_name");

		// authorizer_action
		$this->authorizer_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_authorizer_action");
		$this->authorizer_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_authorizer_action");

		// authorizer_comment
		$this->authorizer_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_authorizer_comment");
		$this->authorizer_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_authorizer_comment");

		// status
		$this->status->AdvancedSearch->SearchValue = $objForm->GetValue("x_status");
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");

		// rep_date
		$this->rep_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_rep_date");
		$this->rep_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_rep_date");

		// rep_name
		$this->rep_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_rep_name");
		$this->rep_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_rep_name");

		// outward_datetime
		$this->outward_datetime->AdvancedSearch->SearchValue = $objForm->GetValue("x_outward_datetime");
		$this->outward_datetime->AdvancedSearch->SearchOperator = $objForm->GetValue("z_outward_datetime");

		// rep_action
		$this->rep_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_rep_action");
		$this->rep_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_rep_action");

		// rep_comment
		$this->rep_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_rep_comment");
		$this->rep_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_rep_comment");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// code
		// date
		// reference
		// staff_id
		// outward_location
		// delivery_point
		// name
		// organization
		// designation
		// department
		// item_description
		// driver_name
		// vehicle_no
		// requester_action
		// requester_comment
		// date_authorized
		// authorizer_name
		// authorizer_action
		// authorizer_comment
		// status
		// rep_date
		// rep_name
		// outward_datetime
		// rep_action
		// rep_comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// reference
		$this->reference->ViewValue = $this->reference->CurrentValue;
		$this->reference->ViewCustomAttributes = "";

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

		// outward_location
		$this->outward_location->ViewValue = $this->outward_location->CurrentValue;
		$this->outward_location->ViewCustomAttributes = "";

		// delivery_point
		$this->delivery_point->ViewValue = $this->delivery_point->CurrentValue;
		$this->delivery_point->ViewCustomAttributes = "";

		// name
		if (strval($this->name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->name->ViewValue = $this->name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->name->ViewValue = $this->name->CurrentValue;
			}
		} else {
			$this->name->ViewValue = NULL;
		}
		$this->name->ViewCustomAttributes = "";

		// organization
		$this->organization->ViewValue = $this->organization->CurrentValue;
		if (strval($this->organization->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->organization->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organization->ViewValue = $this->organization->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organization->ViewValue = $this->organization->CurrentValue;
			}
		} else {
			$this->organization->ViewValue = NULL;
		}
		$this->organization->ViewCustomAttributes = "";

		// designation
		$this->designation->ViewValue = $this->designation->CurrentValue;
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array("dx1" => '`department_name`');
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

		// item_description
		$this->item_description->ViewValue = $this->item_description->CurrentValue;
		$this->item_description->ViewCustomAttributes = "";

		// driver_name
		$this->driver_name->ViewValue = $this->driver_name->CurrentValue;
		$this->driver_name->ViewCustomAttributes = "";

		// vehicle_no
		$this->vehicle_no->ViewValue = $this->vehicle_no->CurrentValue;
		$this->vehicle_no->ViewCustomAttributes = "";

		// requester_action
		if (strval($this->requester_action->CurrentValue) <> "") {
			$this->requester_action->ViewValue = $this->requester_action->OptionCaption($this->requester_action->CurrentValue);
		} else {
			$this->requester_action->ViewValue = NULL;
		}
		$this->requester_action->ViewCustomAttributes = "";

		// requester_comment
		$this->requester_comment->ViewValue = $this->requester_comment->CurrentValue;
		$this->requester_comment->ViewCustomAttributes = "";

		// date_authorized
		$this->date_authorized->ViewValue = $this->date_authorized->CurrentValue;
		$this->date_authorized->ViewValue = ew_FormatDateTime($this->date_authorized->ViewValue, 17);
		$this->date_authorized->ViewCustomAttributes = "";

		// authorizer_name
		if (strval($this->authorizer_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->authorizer_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->authorizer_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->authorizer_name->ViewValue = $this->authorizer_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->authorizer_name->ViewValue = $this->authorizer_name->CurrentValue;
			}
		} else {
			$this->authorizer_name->ViewValue = NULL;
		}
		$this->authorizer_name->ViewCustomAttributes = "";

		// authorizer_action
		if (strval($this->authorizer_action->CurrentValue) <> "") {
			$this->authorizer_action->ViewValue = $this->authorizer_action->OptionCaption($this->authorizer_action->CurrentValue);
		} else {
			$this->authorizer_action->ViewValue = NULL;
		}
		$this->authorizer_action->ViewCustomAttributes = "";

		// authorizer_comment
		$this->authorizer_comment->ViewValue = $this->authorizer_comment->CurrentValue;
		$this->authorizer_comment->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
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

		// rep_date
		$this->rep_date->ViewValue = $this->rep_date->CurrentValue;
		$this->rep_date->ViewValue = ew_FormatDateTime($this->rep_date->ViewValue, 17);
		$this->rep_date->ViewCustomAttributes = "";

		// rep_name
		$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
		if (strval($this->rep_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->rep_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->rep_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->rep_name->ViewValue = $this->rep_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
			}
		} else {
			$this->rep_name->ViewValue = NULL;
		}
		$this->rep_name->ViewCustomAttributes = "";

		// outward_datetime
		$this->outward_datetime->ViewValue = $this->outward_datetime->CurrentValue;
		$this->outward_datetime->ViewValue = ew_FormatDateTime($this->outward_datetime->ViewValue, 17);
		$this->outward_datetime->ViewCustomAttributes = "";

		// rep_action
		if (strval($this->rep_action->CurrentValue) <> "") {
			$this->rep_action->ViewValue = $this->rep_action->OptionCaption($this->rep_action->CurrentValue);
		} else {
			$this->rep_action->ViewValue = NULL;
		}
		$this->rep_action->ViewCustomAttributes = "";

		// rep_comment
		$this->rep_comment->ViewValue = $this->rep_comment->CurrentValue;
		$this->rep_comment->ViewCustomAttributes = "";

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
			$this->code->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";
			$this->reference->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// outward_location
			$this->outward_location->LinkCustomAttributes = "";
			$this->outward_location->HrefValue = "";
			$this->outward_location->TooltipValue = "";

			// delivery_point
			$this->delivery_point->LinkCustomAttributes = "";
			$this->delivery_point->HrefValue = "";
			$this->delivery_point->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// organization
			$this->organization->LinkCustomAttributes = "";
			$this->organization->HrefValue = "";
			$this->organization->TooltipValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";
			$this->designation->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// item_description
			$this->item_description->LinkCustomAttributes = "";
			$this->item_description->HrefValue = "";
			$this->item_description->TooltipValue = "";

			// driver_name
			$this->driver_name->LinkCustomAttributes = "";
			$this->driver_name->HrefValue = "";
			$this->driver_name->TooltipValue = "";

			// vehicle_no
			$this->vehicle_no->LinkCustomAttributes = "";
			$this->vehicle_no->HrefValue = "";
			$this->vehicle_no->TooltipValue = "";

			// requester_action
			$this->requester_action->LinkCustomAttributes = "";
			$this->requester_action->HrefValue = "";
			$this->requester_action->TooltipValue = "";

			// requester_comment
			$this->requester_comment->LinkCustomAttributes = "";
			$this->requester_comment->HrefValue = "";
			$this->requester_comment->TooltipValue = "";

			// date_authorized
			$this->date_authorized->LinkCustomAttributes = "";
			$this->date_authorized->HrefValue = "";
			$this->date_authorized->TooltipValue = "";

			// authorizer_name
			$this->authorizer_name->LinkCustomAttributes = "";
			$this->authorizer_name->HrefValue = "";
			$this->authorizer_name->TooltipValue = "";

			// authorizer_action
			$this->authorizer_action->LinkCustomAttributes = "";
			$this->authorizer_action->HrefValue = "";
			$this->authorizer_action->TooltipValue = "";

			// authorizer_comment
			$this->authorizer_comment->LinkCustomAttributes = "";
			$this->authorizer_comment->HrefValue = "";
			$this->authorizer_comment->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// rep_date
			$this->rep_date->LinkCustomAttributes = "";
			$this->rep_date->HrefValue = "";
			$this->rep_date->TooltipValue = "";

			// rep_name
			$this->rep_name->LinkCustomAttributes = "";
			$this->rep_name->HrefValue = "";
			$this->rep_name->TooltipValue = "";

			// outward_datetime
			$this->outward_datetime->LinkCustomAttributes = "";
			$this->outward_datetime->HrefValue = "";
			$this->outward_datetime->TooltipValue = "";

			// rep_action
			$this->rep_action->LinkCustomAttributes = "";
			$this->rep_action->HrefValue = "";
			$this->rep_action->TooltipValue = "";

			// rep_comment
			$this->rep_comment->LinkCustomAttributes = "";
			$this->rep_comment->HrefValue = "";
			$this->rep_comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = ew_HtmlEncode($this->code->AdvancedSearch->SearchValue);
			$this->code->PlaceHolder = ew_RemoveHtml($this->code->FldCaption());

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date->AdvancedSearch->SearchValue, 0), 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date->AdvancedSearch->SearchValue2, 0), 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// reference
			$this->reference->EditAttrs["class"] = "form-control";
			$this->reference->EditCustomAttributes = "";
			$this->reference->EditValue = ew_HtmlEncode($this->reference->AdvancedSearch->SearchValue);
			$this->reference->PlaceHolder = ew_RemoveHtml($this->reference->FldCaption());

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

			// outward_location
			$this->outward_location->EditAttrs["class"] = "form-control";
			$this->outward_location->EditCustomAttributes = "";
			$this->outward_location->EditValue = ew_HtmlEncode($this->outward_location->AdvancedSearch->SearchValue);
			$this->outward_location->PlaceHolder = ew_RemoveHtml($this->outward_location->FldCaption());

			// delivery_point
			$this->delivery_point->EditAttrs["class"] = "form-control";
			$this->delivery_point->EditCustomAttributes = "";
			$this->delivery_point->EditValue = ew_HtmlEncode($this->delivery_point->AdvancedSearch->SearchValue);
			$this->delivery_point->PlaceHolder = ew_RemoveHtml($this->delivery_point->FldCaption());

			// name
			$this->name->EditCustomAttributes = "";
			if (trim(strval($this->name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->name->AdvancedSearch->ViewValue = $this->name->DisplayValue($arwrk);
			} else {
				$this->name->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->name->EditValue = $arwrk;

			// organization
			$this->organization->EditAttrs["class"] = "form-control";
			$this->organization->EditCustomAttributes = "";
			$this->organization->EditValue = ew_HtmlEncode($this->organization->AdvancedSearch->SearchValue);
			if (strval($this->organization->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "";
			$this->organization->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->organization->EditValue = $this->organization->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->organization->EditValue = ew_HtmlEncode($this->organization->AdvancedSearch->SearchValue);
				}
			} else {
				$this->organization->EditValue = NULL;
			}
			$this->organization->PlaceHolder = ew_RemoveHtml($this->organization->FldCaption());

			// designation
			$this->designation->EditAttrs["class"] = "form-control";
			$this->designation->EditCustomAttributes = "";
			$this->designation->EditValue = ew_HtmlEncode($this->designation->AdvancedSearch->SearchValue);
			if (strval($this->designation->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
			$sWhereWrk = "";
			$this->designation->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->designation->EditValue = $this->designation->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->designation->EditValue = ew_HtmlEncode($this->designation->AdvancedSearch->SearchValue);
				}
			} else {
				$this->designation->EditValue = NULL;
			}
			$this->designation->PlaceHolder = ew_RemoveHtml($this->designation->FldCaption());

			// department
			$this->department->EditCustomAttributes = "";
			if (trim(strval($this->department->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `depertment`";
			$sWhereWrk = "";
			$this->department->LookupFilters = array("dx1" => '`department_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->department->AdvancedSearch->ViewValue = $this->department->DisplayValue($arwrk);
			} else {
				$this->department->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->department->EditValue = $arwrk;

			// item_description
			$this->item_description->EditAttrs["class"] = "form-control";
			$this->item_description->EditCustomAttributes = "";
			$this->item_description->EditValue = ew_HtmlEncode($this->item_description->AdvancedSearch->SearchValue);
			$this->item_description->PlaceHolder = ew_RemoveHtml($this->item_description->FldCaption());

			// driver_name
			$this->driver_name->EditAttrs["class"] = "form-control";
			$this->driver_name->EditCustomAttributes = "";
			$this->driver_name->EditValue = ew_HtmlEncode($this->driver_name->AdvancedSearch->SearchValue);
			$this->driver_name->PlaceHolder = ew_RemoveHtml($this->driver_name->FldCaption());

			// vehicle_no
			$this->vehicle_no->EditAttrs["class"] = "form-control";
			$this->vehicle_no->EditCustomAttributes = "";
			$this->vehicle_no->EditValue = ew_HtmlEncode($this->vehicle_no->AdvancedSearch->SearchValue);
			$this->vehicle_no->PlaceHolder = ew_RemoveHtml($this->vehicle_no->FldCaption());

			// requester_action
			$this->requester_action->EditCustomAttributes = "";
			$this->requester_action->EditValue = $this->requester_action->Options(FALSE);

			// requester_comment
			$this->requester_comment->EditAttrs["class"] = "form-control";
			$this->requester_comment->EditCustomAttributes = "";
			$this->requester_comment->EditValue = ew_HtmlEncode($this->requester_comment->AdvancedSearch->SearchValue);
			$this->requester_comment->PlaceHolder = ew_RemoveHtml($this->requester_comment->FldCaption());

			// date_authorized
			$this->date_authorized->EditAttrs["class"] = "form-control";
			$this->date_authorized->EditCustomAttributes = "";
			$this->date_authorized->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_authorized->AdvancedSearch->SearchValue, 17), 17));
			$this->date_authorized->PlaceHolder = ew_RemoveHtml($this->date_authorized->FldCaption());

			// authorizer_name
			$this->authorizer_name->EditAttrs["class"] = "form-control";
			$this->authorizer_name->EditCustomAttributes = "";
			if (trim(strval($this->authorizer_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->authorizer_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->authorizer_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->authorizer_name->EditValue = $arwrk;

			// authorizer_action
			$this->authorizer_action->EditCustomAttributes = "";
			$this->authorizer_action->EditValue = $this->authorizer_action->Options(FALSE);

			// authorizer_comment
			$this->authorizer_comment->EditAttrs["class"] = "form-control";
			$this->authorizer_comment->EditCustomAttributes = "";
			$this->authorizer_comment->EditValue = ew_HtmlEncode($this->authorizer_comment->AdvancedSearch->SearchValue);
			$this->authorizer_comment->PlaceHolder = ew_RemoveHtml($this->authorizer_comment->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			if (strval($this->status->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
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

			// rep_date
			$this->rep_date->EditAttrs["class"] = "form-control";
			$this->rep_date->EditCustomAttributes = "";
			$this->rep_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->rep_date->AdvancedSearch->SearchValue, 17), 17));
			$this->rep_date->PlaceHolder = ew_RemoveHtml($this->rep_date->FldCaption());

			// rep_name
			$this->rep_name->EditAttrs["class"] = "form-control";
			$this->rep_name->EditCustomAttributes = "";
			$this->rep_name->EditValue = ew_HtmlEncode($this->rep_name->AdvancedSearch->SearchValue);
			if (strval($this->rep_name->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->rep_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->rep_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->rep_name->EditValue = $this->rep_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->rep_name->EditValue = ew_HtmlEncode($this->rep_name->AdvancedSearch->SearchValue);
				}
			} else {
				$this->rep_name->EditValue = NULL;
			}
			$this->rep_name->PlaceHolder = ew_RemoveHtml($this->rep_name->FldCaption());

			// outward_datetime
			$this->outward_datetime->EditAttrs["class"] = "form-control";
			$this->outward_datetime->EditCustomAttributes = "";
			$this->outward_datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->outward_datetime->AdvancedSearch->SearchValue, 17), 17));
			$this->outward_datetime->PlaceHolder = ew_RemoveHtml($this->outward_datetime->FldCaption());

			// rep_action
			$this->rep_action->EditCustomAttributes = "";
			$this->rep_action->EditValue = $this->rep_action->Options(FALSE);

			// rep_comment
			$this->rep_comment->EditAttrs["class"] = "form-control";
			$this->rep_comment->EditCustomAttributes = "";
			$this->rep_comment->EditValue = ew_HtmlEncode($this->rep_comment->AdvancedSearch->SearchValue);
			$this->rep_comment->PlaceHolder = ew_RemoveHtml($this->rep_comment->FldCaption());
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
		if (!ew_CheckShortEuroDate($this->date_authorized->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_authorized->FldErrMsg());
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
		$this->date->AdvancedSearch->Load();
		$this->reference->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->outward_location->AdvancedSearch->Load();
		$this->delivery_point->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->organization->AdvancedSearch->Load();
		$this->designation->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->item_description->AdvancedSearch->Load();
		$this->driver_name->AdvancedSearch->Load();
		$this->vehicle_no->AdvancedSearch->Load();
		$this->requester_action->AdvancedSearch->Load();
		$this->requester_comment->AdvancedSearch->Load();
		$this->date_authorized->AdvancedSearch->Load();
		$this->authorizer_name->AdvancedSearch->Load();
		$this->authorizer_action->AdvancedSearch->Load();
		$this->authorizer_comment->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->rep_date->AdvancedSearch->Load();
		$this->rep_name->AdvancedSearch->Load();
		$this->outward_datetime->AdvancedSearch->Load();
		$this->rep_action->AdvancedSearch->Load();
		$this->rep_comment->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("requisition_reportlist.php"), "", $this->TableVar, TRUE);
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
		case "x_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_organization":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id` AS `LinkFld`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_designation":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id` AS `LinkFld`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`department_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`department_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_authorizer_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_rep_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
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
		case "x_organization":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld` FROM `branch`";
			$sWhereWrk = "`branch_name` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_designation":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld` FROM `designation`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld` FROM `status_ssf`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_rep_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->rep_name) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->rep_name) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($requisition_report_search)) $requisition_report_search = new crequisition_report_search();

// Page init
$requisition_report_search->Page_Init();

// Page main
$requisition_report_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$requisition_report_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($requisition_report_search->IsModal) { ?>
var CurrentAdvancedSearchForm = frequisition_reportsearch = new ew_Form("frequisition_reportsearch", "search");
<?php } else { ?>
var CurrentForm = frequisition_reportsearch = new ew_Form("frequisition_reportsearch", "search");
<?php } ?>

// Form_CustomValidate event
frequisition_reportsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frequisition_reportsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frequisition_reportsearch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportsearch.Lists["x_staff_id"].Data = "<?php echo $requisition_report_search->staff_id->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_search->staff_id->LookupFilterQuery(TRUE, "search"))) ?>;
frequisition_reportsearch.Lists["x_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportsearch.Lists["x_name"].Data = "<?php echo $requisition_report_search->name->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.Lists["x_organization"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
frequisition_reportsearch.Lists["x_organization"].Data = "<?php echo $requisition_report_search->organization->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.AutoSuggests["x_organization"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_search->organization->LookupFilterQuery(TRUE, "search"))) ?>;
frequisition_reportsearch.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
frequisition_reportsearch.Lists["x_designation"].Data = "<?php echo $requisition_report_search->designation->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.AutoSuggests["x_designation"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_search->designation->LookupFilterQuery(TRUE, "search"))) ?>;
frequisition_reportsearch.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
frequisition_reportsearch.Lists["x_department"].Data = "<?php echo $requisition_report_search->department->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.Lists["x_requester_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_reportsearch.Lists["x_requester_action"].Options = <?php echo json_encode($requisition_report_search->requester_action->Options()) ?>;
frequisition_reportsearch.Lists["x_authorizer_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportsearch.Lists["x_authorizer_name"].Data = "<?php echo $requisition_report_search->authorizer_name->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.Lists["x_authorizer_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_reportsearch.Lists["x_authorizer_action"].Options = <?php echo json_encode($requisition_report_search->authorizer_action->Options()) ?>;
frequisition_reportsearch.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status_ssf"};
frequisition_reportsearch.Lists["x_status"].Data = "<?php echo $requisition_report_search->status->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_search->status->LookupFilterQuery(TRUE, "search"))) ?>;
frequisition_reportsearch.Lists["x_rep_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportsearch.Lists["x_rep_name"].Data = "<?php echo $requisition_report_search->rep_name->LookupFilterQuery(FALSE, "search") ?>";
frequisition_reportsearch.AutoSuggests["x_rep_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_search->rep_name->LookupFilterQuery(TRUE, "search"))) ?>;
frequisition_reportsearch.Lists["x_rep_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_reportsearch.Lists["x_rep_action"].Options = <?php echo json_encode($requisition_report_search->rep_action->Options()) ?>;

// Form object for search
// Validate function for search

frequisition_reportsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_code");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($requisition_report->code->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_authorized");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($requisition_report->date_authorized->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $requisition_report_search->ShowPageHeader(); ?>
<?php
$requisition_report_search->ShowMessage();
?>
<form name="frequisition_reportsearch" id="frequisition_reportsearch" class="<?php echo $requisition_report_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($requisition_report_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $requisition_report_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="requisition_report">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($requisition_report_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($requisition_report->code->Visible) { // code ?>
	<div id="r_code" class="form-group">
		<label for="x_code" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_code"><?php echo $requisition_report->code->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_code" id="z_code" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->code->CellAttributes() ?>>
			<span id="el_requisition_report_code">
<input type="text" data-table="requisition_report" data-field="x_code" name="x_code" id="x_code" placeholder="<?php echo ew_HtmlEncode($requisition_report->code->getPlaceHolder()) ?>" value="<?php echo $requisition_report->code->EditValue ?>"<?php echo $requisition_report->code->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label for="x_date" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_date"><?php echo $requisition_report->date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date" id="z_date" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->date->CellAttributes() ?>>
			<span id="el_requisition_report_date">
<input type="text" data-table="requisition_report" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($requisition_report->date->getPlaceHolder()) ?>" value="<?php echo $requisition_report->date->EditValue ?>"<?php echo $requisition_report->date->EditAttributes() ?>>
</span>
			<span class="ewSearchCond btw1_date">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_requisition_report_date" class="btw1_date">
<input type="text" data-table="requisition_report" data-field="x_date" name="y_date" id="y_date" placeholder="<?php echo ew_HtmlEncode($requisition_report->date->getPlaceHolder()) ?>" value="<?php echo $requisition_report->date->EditValue2 ?>"<?php echo $requisition_report->date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->reference->Visible) { // reference ?>
	<div id="r_reference" class="form-group">
		<label for="x_reference" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_reference"><?php echo $requisition_report->reference->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference" id="z_reference" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->reference->CellAttributes() ?>>
			<span id="el_requisition_report_reference">
<input type="text" data-table="requisition_report" data-field="x_reference" name="x_reference" id="x_reference" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->reference->getPlaceHolder()) ?>" value="<?php echo $requisition_report->reference->EditValue ?>"<?php echo $requisition_report->reference->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_staff_id"><?php echo $requisition_report->staff_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->staff_id->CellAttributes() ?>>
			<span id="el_requisition_report_staff_id">
<?php
$wrkonchange = trim(" " . @$requisition_report->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_report->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $requisition_report->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($requisition_report->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_report->staff_id->getPlaceHolder()) ?>"<?php echo $requisition_report->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_report" data-field="x_staff_id" data-value-separator="<?php echo $requisition_report->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($requisition_report->staff_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_reportsearch.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->outward_location->Visible) { // outward_location ?>
	<div id="r_outward_location" class="form-group">
		<label for="x_outward_location" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_outward_location"><?php echo $requisition_report->outward_location->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_outward_location" id="z_outward_location" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->outward_location->CellAttributes() ?>>
			<span id="el_requisition_report_outward_location">
<input type="text" data-table="requisition_report" data-field="x_outward_location" name="x_outward_location" id="x_outward_location" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->outward_location->getPlaceHolder()) ?>" value="<?php echo $requisition_report->outward_location->EditValue ?>"<?php echo $requisition_report->outward_location->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->delivery_point->Visible) { // delivery_point ?>
	<div id="r_delivery_point" class="form-group">
		<label for="x_delivery_point" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_delivery_point"><?php echo $requisition_report->delivery_point->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_delivery_point" id="z_delivery_point" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->delivery_point->CellAttributes() ?>>
			<span id="el_requisition_report_delivery_point">
<input type="text" data-table="requisition_report" data-field="x_delivery_point" name="x_delivery_point" id="x_delivery_point" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->delivery_point->getPlaceHolder()) ?>" value="<?php echo $requisition_report->delivery_point->EditValue ?>"<?php echo $requisition_report->delivery_point->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label for="x_name" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_name"><?php echo $requisition_report->name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_name" id="z_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->name->CellAttributes() ?>>
			<span id="el_requisition_report_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_name"><?php echo (strval($requisition_report->name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $requisition_report->name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($requisition_report->name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($requisition_report->name->ReadOnly || $requisition_report->name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="requisition_report" data-field="x_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $requisition_report->name->DisplayValueSeparatorAttribute() ?>" name="x_name" id="x_name" value="<?php echo $requisition_report->name->AdvancedSearch->SearchValue ?>"<?php echo $requisition_report->name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->organization->Visible) { // organization ?>
	<div id="r_organization" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_organization"><?php echo $requisition_report->organization->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_organization" id="z_organization" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->organization->CellAttributes() ?>>
			<span id="el_requisition_report_organization">
<?php
$wrkonchange = trim(" " . @$requisition_report->organization->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_report->organization->EditAttrs["onchange"] = "";
?>
<span id="as_x_organization" style="white-space: nowrap; z-index: 8920">
	<input type="text" name="sv_x_organization" id="sv_x_organization" value="<?php echo $requisition_report->organization->EditValue ?>" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($requisition_report->organization->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_report->organization->getPlaceHolder()) ?>"<?php echo $requisition_report->organization->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_report" data-field="x_organization" data-value-separator="<?php echo $requisition_report->organization->DisplayValueSeparatorAttribute() ?>" name="x_organization" id="x_organization" value="<?php echo ew_HtmlEncode($requisition_report->organization->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_reportsearch.CreateAutoSuggest({"id":"x_organization","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->designation->Visible) { // designation ?>
	<div id="r_designation" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_designation"><?php echo $requisition_report->designation->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_designation" id="z_designation" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->designation->CellAttributes() ?>>
			<span id="el_requisition_report_designation">
<?php
$wrkonchange = trim(" " . @$requisition_report->designation->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_report->designation->EditAttrs["onchange"] = "";
?>
<span id="as_x_designation" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_designation" id="sv_x_designation" value="<?php echo $requisition_report->designation->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->designation->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_report->designation->getPlaceHolder()) ?>"<?php echo $requisition_report->designation->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_report" data-field="x_designation" data-value-separator="<?php echo $requisition_report->designation->DisplayValueSeparatorAttribute() ?>" name="x_designation" id="x_designation" value="<?php echo ew_HtmlEncode($requisition_report->designation->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_reportsearch.CreateAutoSuggest({"id":"x_designation","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label for="x_department" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_department"><?php echo $requisition_report->department->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_department" id="z_department" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->department->CellAttributes() ?>>
			<span id="el_requisition_report_department">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_department"><?php echo (strval($requisition_report->department->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $requisition_report->department->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($requisition_report->department->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_department',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($requisition_report->department->ReadOnly || $requisition_report->department->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="requisition_report" data-field="x_department" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $requisition_report->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="<?php echo $requisition_report->department->AdvancedSearch->SearchValue ?>"<?php echo $requisition_report->department->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->item_description->Visible) { // item_description ?>
	<div id="r_item_description" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_item_description"><?php echo $requisition_report->item_description->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_item_description" id="z_item_description" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->item_description->CellAttributes() ?>>
			<span id="el_requisition_report_item_description">
<input type="text" data-table="requisition_report" data-field="x_item_description" name="x_item_description" id="x_item_description" maxlength="255" placeholder="<?php echo ew_HtmlEncode($requisition_report->item_description->getPlaceHolder()) ?>" value="<?php echo $requisition_report->item_description->EditValue ?>"<?php echo $requisition_report->item_description->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->driver_name->Visible) { // driver_name ?>
	<div id="r_driver_name" class="form-group">
		<label for="x_driver_name" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_driver_name"><?php echo $requisition_report->driver_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_driver_name" id="z_driver_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->driver_name->CellAttributes() ?>>
			<span id="el_requisition_report_driver_name">
<input type="text" data-table="requisition_report" data-field="x_driver_name" name="x_driver_name" id="x_driver_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->driver_name->getPlaceHolder()) ?>" value="<?php echo $requisition_report->driver_name->EditValue ?>"<?php echo $requisition_report->driver_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->vehicle_no->Visible) { // vehicle_no ?>
	<div id="r_vehicle_no" class="form-group">
		<label for="x_vehicle_no" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_vehicle_no"><?php echo $requisition_report->vehicle_no->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_vehicle_no" id="z_vehicle_no" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->vehicle_no->CellAttributes() ?>>
			<span id="el_requisition_report_vehicle_no">
<input type="text" data-table="requisition_report" data-field="x_vehicle_no" name="x_vehicle_no" id="x_vehicle_no" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->vehicle_no->getPlaceHolder()) ?>" value="<?php echo $requisition_report->vehicle_no->EditValue ?>"<?php echo $requisition_report->vehicle_no->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->requester_action->Visible) { // requester_action ?>
	<div id="r_requester_action" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_requester_action"><?php echo $requisition_report->requester_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_requester_action" id="z_requester_action" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->requester_action->CellAttributes() ?>>
			<span id="el_requisition_report_requester_action">
<div id="tp_x_requester_action" class="ewTemplate"><input type="radio" data-table="requisition_report" data-field="x_requester_action" data-value-separator="<?php echo $requisition_report->requester_action->DisplayValueSeparatorAttribute() ?>" name="x_requester_action" id="x_requester_action" value="{value}"<?php echo $requisition_report->requester_action->EditAttributes() ?>></div>
<div id="dsl_x_requester_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $requisition_report->requester_action->RadioButtonListHtml(FALSE, "x_requester_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->requester_comment->Visible) { // requester_comment ?>
	<div id="r_requester_comment" class="form-group">
		<label for="x_requester_comment" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_requester_comment"><?php echo $requisition_report->requester_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_requester_comment" id="z_requester_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->requester_comment->CellAttributes() ?>>
			<span id="el_requisition_report_requester_comment">
<input type="text" data-table="requisition_report" data-field="x_requester_comment" name="x_requester_comment" id="x_requester_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->requester_comment->getPlaceHolder()) ?>" value="<?php echo $requisition_report->requester_comment->EditValue ?>"<?php echo $requisition_report->requester_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->date_authorized->Visible) { // date_authorized ?>
	<div id="r_date_authorized" class="form-group">
		<label for="x_date_authorized" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_date_authorized"><?php echo $requisition_report->date_authorized->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_authorized" id="z_date_authorized" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->date_authorized->CellAttributes() ?>>
			<span id="el_requisition_report_date_authorized">
<input type="text" data-table="requisition_report" data-field="x_date_authorized" data-format="17" name="x_date_authorized" id="x_date_authorized" placeholder="<?php echo ew_HtmlEncode($requisition_report->date_authorized->getPlaceHolder()) ?>" value="<?php echo $requisition_report->date_authorized->EditValue ?>"<?php echo $requisition_report->date_authorized->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->authorizer_name->Visible) { // authorizer_name ?>
	<div id="r_authorizer_name" class="form-group">
		<label for="x_authorizer_name" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_authorizer_name"><?php echo $requisition_report->authorizer_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_authorizer_name" id="z_authorizer_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->authorizer_name->CellAttributes() ?>>
			<span id="el_requisition_report_authorizer_name">
<select data-table="requisition_report" data-field="x_authorizer_name" data-value-separator="<?php echo $requisition_report->authorizer_name->DisplayValueSeparatorAttribute() ?>" id="x_authorizer_name" name="x_authorizer_name"<?php echo $requisition_report->authorizer_name->EditAttributes() ?>>
<?php echo $requisition_report->authorizer_name->SelectOptionListHtml("x_authorizer_name") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->authorizer_action->Visible) { // authorizer_action ?>
	<div id="r_authorizer_action" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_authorizer_action"><?php echo $requisition_report->authorizer_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_authorizer_action" id="z_authorizer_action" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->authorizer_action->CellAttributes() ?>>
			<span id="el_requisition_report_authorizer_action">
<div id="tp_x_authorizer_action" class="ewTemplate"><input type="radio" data-table="requisition_report" data-field="x_authorizer_action" data-value-separator="<?php echo $requisition_report->authorizer_action->DisplayValueSeparatorAttribute() ?>" name="x_authorizer_action" id="x_authorizer_action" value="{value}"<?php echo $requisition_report->authorizer_action->EditAttributes() ?>></div>
<div id="dsl_x_authorizer_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $requisition_report->authorizer_action->RadioButtonListHtml(FALSE, "x_authorizer_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->authorizer_comment->Visible) { // authorizer_comment ?>
	<div id="r_authorizer_comment" class="form-group">
		<label for="x_authorizer_comment" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_authorizer_comment"><?php echo $requisition_report->authorizer_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_authorizer_comment" id="z_authorizer_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->authorizer_comment->CellAttributes() ?>>
			<span id="el_requisition_report_authorizer_comment">
<input type="text" data-table="requisition_report" data-field="x_authorizer_comment" name="x_authorizer_comment" id="x_authorizer_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->authorizer_comment->getPlaceHolder()) ?>" value="<?php echo $requisition_report->authorizer_comment->EditValue ?>"<?php echo $requisition_report->authorizer_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_status"><?php echo $requisition_report->status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->status->CellAttributes() ?>>
			<span id="el_requisition_report_status">
<?php
$wrkonchange = trim(" " . @$requisition_report->status->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_report->status->EditAttrs["onchange"] = "";
?>
<span id="as_x_status" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_status" id="sv_x_status" value="<?php echo $requisition_report->status->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($requisition_report->status->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_report->status->getPlaceHolder()) ?>"<?php echo $requisition_report->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_report" data-field="x_status" data-value-separator="<?php echo $requisition_report->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($requisition_report->status->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_reportsearch.CreateAutoSuggest({"id":"x_status","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->rep_date->Visible) { // rep_date ?>
	<div id="r_rep_date" class="form-group">
		<label for="x_rep_date" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_rep_date"><?php echo $requisition_report->rep_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_rep_date" id="z_rep_date" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->rep_date->CellAttributes() ?>>
			<span id="el_requisition_report_rep_date">
<input type="text" data-table="requisition_report" data-field="x_rep_date" data-format="17" name="x_rep_date" id="x_rep_date" placeholder="<?php echo ew_HtmlEncode($requisition_report->rep_date->getPlaceHolder()) ?>" value="<?php echo $requisition_report->rep_date->EditValue ?>"<?php echo $requisition_report->rep_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->rep_name->Visible) { // rep_name ?>
	<div id="r_rep_name" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_rep_name"><?php echo $requisition_report->rep_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_rep_name" id="z_rep_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->rep_name->CellAttributes() ?>>
			<span id="el_requisition_report_rep_name">
<?php
$wrkonchange = trim(" " . @$requisition_report->rep_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_report->rep_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_rep_name" style="white-space: nowrap; z-index: 8780">
	<input type="text" name="sv_x_rep_name" id="sv_x_rep_name" value="<?php echo $requisition_report->rep_name->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->rep_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_report->rep_name->getPlaceHolder()) ?>"<?php echo $requisition_report->rep_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_report" data-field="x_rep_name" data-value-separator="<?php echo $requisition_report->rep_name->DisplayValueSeparatorAttribute() ?>" name="x_rep_name" id="x_rep_name" value="<?php echo ew_HtmlEncode($requisition_report->rep_name->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_reportsearch.CreateAutoSuggest({"id":"x_rep_name","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->outward_datetime->Visible) { // outward_datetime ?>
	<div id="r_outward_datetime" class="form-group">
		<label for="x_outward_datetime" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_outward_datetime"><?php echo $requisition_report->outward_datetime->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_outward_datetime" id="z_outward_datetime" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->outward_datetime->CellAttributes() ?>>
			<span id="el_requisition_report_outward_datetime">
<input type="text" data-table="requisition_report" data-field="x_outward_datetime" data-format="17" name="x_outward_datetime" id="x_outward_datetime" placeholder="<?php echo ew_HtmlEncode($requisition_report->outward_datetime->getPlaceHolder()) ?>" value="<?php echo $requisition_report->outward_datetime->EditValue ?>"<?php echo $requisition_report->outward_datetime->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->rep_action->Visible) { // rep_action ?>
	<div id="r_rep_action" class="form-group">
		<label class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_rep_action"><?php echo $requisition_report->rep_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_rep_action" id="z_rep_action" value="="></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->rep_action->CellAttributes() ?>>
			<span id="el_requisition_report_rep_action">
<div id="tp_x_rep_action" class="ewTemplate"><input type="radio" data-table="requisition_report" data-field="x_rep_action" data-value-separator="<?php echo $requisition_report->rep_action->DisplayValueSeparatorAttribute() ?>" name="x_rep_action" id="x_rep_action" value="{value}"<?php echo $requisition_report->rep_action->EditAttributes() ?>></div>
<div id="dsl_x_rep_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $requisition_report->rep_action->RadioButtonListHtml(FALSE, "x_rep_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($requisition_report->rep_comment->Visible) { // rep_comment ?>
	<div id="r_rep_comment" class="form-group">
		<label for="x_rep_comment" class="<?php echo $requisition_report_search->LeftColumnClass ?>"><span id="elh_requisition_report_rep_comment"><?php echo $requisition_report->rep_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_rep_comment" id="z_rep_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $requisition_report_search->RightColumnClass ?>"><div<?php echo $requisition_report->rep_comment->CellAttributes() ?>>
			<span id="el_requisition_report_rep_comment">
<input type="text" data-table="requisition_report" data-field="x_rep_comment" name="x_rep_comment" id="x_rep_comment" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->rep_comment->getPlaceHolder()) ?>" value="<?php echo $requisition_report->rep_comment->EditValue ?>"<?php echo $requisition_report->rep_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$requisition_report_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $requisition_report_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
frequisition_reportsearch.Init();
</script>
<?php
$requisition_report_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$requisition_report_search->Page_Terminate();
?>
