<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "sparepart_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$sparepart_report_search = NULL; // Initialize page object first

class csparepart_report_search extends csparepart_report {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'sparepart_report';

	// Page object name
	var $PageObjName = 'sparepart_report_search';

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

		// Table object (sparepart_report)
		if (!isset($GLOBALS["sparepart_report"]) || get_class($GLOBALS["sparepart_report"]) == "csparepart_report") {
			$GLOBALS["sparepart_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sparepart_report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sparepart_report');

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
				$this->Page_Terminate(ew_GetUrl("sparepart_reportlist.php"));
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
		$this->part_name->SetVisibility();
		$this->maintenance_id->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_used->SetVisibility();
		$this->cost->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->total_cost->SetVisibility();
		$this->maintenance_total_cost->SetVisibility();

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
		global $EW_EXPORT, $sparepart_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sparepart_report);
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
					if ($pageName == "sparepart_reportview.php")
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
						$sSrchStr = "sparepart_reportlist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->part_name); // part_name
		$this->BuildSearchUrl($sSrchUrl, $this->maintenance_id); // maintenance_id
		$this->BuildSearchUrl($sSrchUrl, $this->quantity_in); // quantity_in
		$this->BuildSearchUrl($sSrchUrl, $this->quantity_used); // quantity_used
		$this->BuildSearchUrl($sSrchUrl, $this->cost); // cost
		$this->BuildSearchUrl($sSrchUrl, $this->total_quantity); // total_quantity
		$this->BuildSearchUrl($sSrchUrl, $this->total_cost); // total_cost
		$this->BuildSearchUrl($sSrchUrl, $this->maintenance_total_cost); // maintenance_total_cost
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

		// part_name
		$this->part_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_part_name");
		$this->part_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_part_name");

		// maintenance_id
		$this->maintenance_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_maintenance_id");
		$this->maintenance_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_maintenance_id");

		// quantity_in
		$this->quantity_in->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity_in");
		$this->quantity_in->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity_in");

		// quantity_used
		$this->quantity_used->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity_used");
		$this->quantity_used->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity_used");

		// cost
		$this->cost->AdvancedSearch->SearchValue = $objForm->GetValue("x_cost");
		$this->cost->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cost");

		// total_quantity
		$this->total_quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_total_quantity");
		$this->total_quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_total_quantity");

		// total_cost
		$this->total_cost->AdvancedSearch->SearchValue = $objForm->GetValue("x_total_cost");
		$this->total_cost->AdvancedSearch->SearchOperator = $objForm->GetValue("z_total_cost");

		// maintenance_total_cost
		$this->maintenance_total_cost->AdvancedSearch->SearchValue = $objForm->GetValue("x_maintenance_total_cost");
		$this->maintenance_total_cost->AdvancedSearch->SearchOperator = $objForm->GetValue("z_maintenance_total_cost");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->cost->FormValue == $this->cost->CurrentValue && is_numeric(ew_StrToFloat($this->cost->CurrentValue)))
			$this->cost->CurrentValue = ew_StrToFloat($this->cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total_cost->FormValue == $this->total_cost->CurrentValue && is_numeric(ew_StrToFloat($this->total_cost->CurrentValue)))
			$this->total_cost->CurrentValue = ew_StrToFloat($this->total_cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->maintenance_total_cost->FormValue == $this->maintenance_total_cost->CurrentValue && is_numeric(ew_StrToFloat($this->maintenance_total_cost->CurrentValue)))
			$this->maintenance_total_cost->CurrentValue = ew_StrToFloat($this->maintenance_total_cost->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date
		// part_name
		// maintenance_id
		// quantity_in
		// quantity_used
		// cost
		// total_quantity
		// total_cost
		// maintenance_total_cost

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// part_name
		if (strval($this->part_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
		$sWhereWrk = "";
		$this->part_name->LookupFilters = array("dx1" => '`part_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->part_name->ViewValue = $this->part_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->part_name->ViewValue = $this->part_name->CurrentValue;
			}
		} else {
			$this->part_name->ViewValue = NULL;
		}
		$this->part_name->ViewCustomAttributes = "";

		// maintenance_id
		if (strval($this->maintenance_id->CurrentValue) <> "") {
			$sFilterWrk = "`maintenance_id`" . ew_SearchString("=", $this->maintenance_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `maintenance_id`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_view`";
		$sWhereWrk = "";
		$this->maintenance_id->LookupFilters = array("dx1" => '`generator_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->maintenance_id->ViewValue = $this->maintenance_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintenance_id->ViewValue = $this->maintenance_id->CurrentValue;
			}
		} else {
			$this->maintenance_id->ViewValue = NULL;
		}
		$this->maintenance_id->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_used
		$this->quantity_used->ViewValue = $this->quantity_used->CurrentValue;
		$this->quantity_used->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// total_cost
		$this->total_cost->ViewValue = $this->total_cost->CurrentValue;
		$this->total_cost->ViewValue = ew_FormatNumber($this->total_cost->ViewValue, 0, -2, -2, -2);
		$this->total_cost->ViewCustomAttributes = "";

		// maintenance_total_cost
		$this->maintenance_total_cost->ViewValue = $this->maintenance_total_cost->CurrentValue;
		$this->maintenance_total_cost->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// part_name
			$this->part_name->LinkCustomAttributes = "";
			$this->part_name->HrefValue = "";
			$this->part_name->TooltipValue = "";

			// maintenance_id
			$this->maintenance_id->LinkCustomAttributes = "";
			$this->maintenance_id->HrefValue = "";
			$this->maintenance_id->TooltipValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_used
			$this->quantity_used->LinkCustomAttributes = "";
			$this->quantity_used->HrefValue = "";
			$this->quantity_used->TooltipValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// total_cost
			$this->total_cost->LinkCustomAttributes = "";
			$this->total_cost->HrefValue = "";
			$this->total_cost->TooltipValue = "";

			// maintenance_total_cost
			$this->maintenance_total_cost->LinkCustomAttributes = "";
			$this->maintenance_total_cost->HrefValue = "";
			$this->maintenance_total_cost->TooltipValue = "";
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

			// part_name
			$this->part_name->EditCustomAttributes = "";
			if (trim(strval($this->part_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sparepart_module`";
			$sWhereWrk = "";
			$this->part_name->LookupFilters = array("dx1" => '`part_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->part_name->AdvancedSearch->ViewValue = $this->part_name->DisplayValue($arwrk);
			} else {
				$this->part_name->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->part_name->EditValue = $arwrk;

			// maintenance_id
			$this->maintenance_id->EditCustomAttributes = "";
			if (trim(strval($this->maintenance_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`maintenance_id`" . ew_SearchString("=", $this->maintenance_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `maintenance_id`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sparepart_view`";
			$sWhereWrk = "";
			$this->maintenance_id->LookupFilters = array("dx1" => '`generator_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->maintenance_id->AdvancedSearch->ViewValue = $this->maintenance_id->DisplayValue($arwrk);
			} else {
				$this->maintenance_id->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->maintenance_id->EditValue = $arwrk;

			// quantity_in
			$this->quantity_in->EditAttrs["class"] = "form-control";
			$this->quantity_in->EditCustomAttributes = "";
			$this->quantity_in->EditValue = ew_HtmlEncode($this->quantity_in->AdvancedSearch->SearchValue);
			$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

			// quantity_used
			$this->quantity_used->EditAttrs["class"] = "form-control";
			$this->quantity_used->EditCustomAttributes = "";
			$this->quantity_used->EditValue = ew_HtmlEncode($this->quantity_used->AdvancedSearch->SearchValue);
			$this->quantity_used->PlaceHolder = ew_RemoveHtml($this->quantity_used->FldCaption());

			// cost
			$this->cost->EditAttrs["class"] = "form-control";
			$this->cost->EditCustomAttributes = "";
			$this->cost->EditValue = ew_HtmlEncode($this->cost->AdvancedSearch->SearchValue);
			$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());

			// total_quantity
			$this->total_quantity->EditAttrs["class"] = "form-control";
			$this->total_quantity->EditCustomAttributes = "";
			$this->total_quantity->EditValue = ew_HtmlEncode($this->total_quantity->AdvancedSearch->SearchValue);
			$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

			// total_cost
			$this->total_cost->EditAttrs["class"] = "form-control";
			$this->total_cost->EditCustomAttributes = "";
			$this->total_cost->EditValue = ew_HtmlEncode($this->total_cost->AdvancedSearch->SearchValue);
			$this->total_cost->PlaceHolder = ew_RemoveHtml($this->total_cost->FldCaption());

			// maintenance_total_cost
			$this->maintenance_total_cost->EditAttrs["class"] = "form-control";
			$this->maintenance_total_cost->EditCustomAttributes = "";
			$this->maintenance_total_cost->EditValue = ew_HtmlEncode($this->maintenance_total_cost->AdvancedSearch->SearchValue);
			$this->maintenance_total_cost->PlaceHolder = ew_RemoveHtml($this->maintenance_total_cost->FldCaption());
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
		if (!ew_CheckDateDef($this->date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->date->FldErrMsg());
		}
		if (!ew_CheckNumber($this->cost->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total_cost->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->total_cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->maintenance_total_cost->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->maintenance_total_cost->FldErrMsg());
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
		$this->part_name->AdvancedSearch->Load();
		$this->maintenance_id->AdvancedSearch->Load();
		$this->quantity_in->AdvancedSearch->Load();
		$this->quantity_used->AdvancedSearch->Load();
		$this->cost->AdvancedSearch->Load();
		$this->total_quantity->AdvancedSearch->Load();
		$this->total_cost->AdvancedSearch->Load();
		$this->maintenance_total_cost->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sparepart_reportlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_part_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`part_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_maintenance_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `maintenance_id` AS `LinkFld`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_view`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`generator_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`maintenance_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($sparepart_report_search)) $sparepart_report_search = new csparepart_report_search();

// Page init
$sparepart_report_search->Page_Init();

// Page main
$sparepart_report_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sparepart_report_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($sparepart_report_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fsparepart_reportsearch = new ew_Form("fsparepart_reportsearch", "search");
<?php } else { ?>
var CurrentForm = fsparepart_reportsearch = new ew_Form("fsparepart_reportsearch", "search");
<?php } ?>

// Form_CustomValidate event
fsparepart_reportsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsparepart_reportsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fsparepart_reportsearch.Lists["x_part_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_part_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sparepart_module"};
fsparepart_reportsearch.Lists["x_part_name"].Data = "<?php echo $sparepart_report_search->part_name->LookupFilterQuery(FALSE, "search") ?>";
fsparepart_reportsearch.Lists["x_maintenance_id"] = {"LinkField":"x_maintenance_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_generator_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sparepart_view"};
fsparepart_reportsearch.Lists["x_maintenance_id"].Data = "<?php echo $sparepart_report_search->maintenance_id->LookupFilterQuery(FALSE, "search") ?>";

// Form object for search
// Validate function for search

fsparepart_reportsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sparepart_report->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sparepart_report->date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_cost");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sparepart_report->cost->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_total_cost");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sparepart_report->total_cost->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_maintenance_total_cost");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sparepart_report->maintenance_total_cost->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $sparepart_report_search->ShowPageHeader(); ?>
<?php
$sparepart_report_search->ShowMessage();
?>
<form name="fsparepart_reportsearch" id="fsparepart_reportsearch" class="<?php echo $sparepart_report_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sparepart_report_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sparepart_report_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sparepart_report">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($sparepart_report_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($sparepart_report->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_id"><?php echo $sparepart_report->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->id->CellAttributes() ?>>
			<span id="el_sparepart_report_id">
<input type="text" data-table="sparepart_report" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($sparepart_report->id->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->id->EditValue ?>"<?php echo $sparepart_report->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label for="x_date" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_date"><?php echo $sparepart_report->date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date" id="z_date" value="BETWEEN"></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->date->CellAttributes() ?>>
			<span id="el_sparepart_report_date">
<input type="text" data-table="sparepart_report" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($sparepart_report->date->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->date->EditValue ?>"<?php echo $sparepart_report->date->EditAttributes() ?>>
<?php if (!$sparepart_report->date->ReadOnly && !$sparepart_report->date->Disabled && !isset($sparepart_report->date->EditAttrs["readonly"]) && !isset($sparepart_report->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fsparepart_reportsearch", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw1_date">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_sparepart_report_date" class="btw1_date">
<input type="text" data-table="sparepart_report" data-field="x_date" name="y_date" id="y_date" placeholder="<?php echo ew_HtmlEncode($sparepart_report->date->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->date->EditValue2 ?>"<?php echo $sparepart_report->date->EditAttributes() ?>>
<?php if (!$sparepart_report->date->ReadOnly && !$sparepart_report->date->Disabled && !isset($sparepart_report->date->EditAttrs["readonly"]) && !isset($sparepart_report->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fsparepart_reportsearch", "y_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->part_name->Visible) { // part_name ?>
	<div id="r_part_name" class="form-group">
		<label for="x_part_name" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_part_name"><?php echo $sparepart_report->part_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_part_name" id="z_part_name" value="="></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->part_name->CellAttributes() ?>>
			<span id="el_sparepart_report_part_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_part_name"><?php echo (strval($sparepart_report->part_name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $sparepart_report->part_name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($sparepart_report->part_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_part_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($sparepart_report->part_name->ReadOnly || $sparepart_report->part_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="sparepart_report" data-field="x_part_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $sparepart_report->part_name->DisplayValueSeparatorAttribute() ?>" name="x_part_name" id="x_part_name" value="<?php echo $sparepart_report->part_name->AdvancedSearch->SearchValue ?>"<?php echo $sparepart_report->part_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->maintenance_id->Visible) { // maintenance_id ?>
	<div id="r_maintenance_id" class="form-group">
		<label for="x_maintenance_id" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_maintenance_id"><?php echo $sparepart_report->maintenance_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maintenance_id" id="z_maintenance_id" value="="></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->maintenance_id->CellAttributes() ?>>
			<span id="el_sparepart_report_maintenance_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_maintenance_id"><?php echo (strval($sparepart_report->maintenance_id->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $sparepart_report->maintenance_id->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($sparepart_report->maintenance_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_maintenance_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($sparepart_report->maintenance_id->ReadOnly || $sparepart_report->maintenance_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="sparepart_report" data-field="x_maintenance_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $sparepart_report->maintenance_id->DisplayValueSeparatorAttribute() ?>" name="x_maintenance_id" id="x_maintenance_id" value="<?php echo $sparepart_report->maintenance_id->AdvancedSearch->SearchValue ?>"<?php echo $sparepart_report->maintenance_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->quantity_in->Visible) { // quantity_in ?>
	<div id="r_quantity_in" class="form-group">
		<label for="x_quantity_in" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_quantity_in"><?php echo $sparepart_report->quantity_in->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity_in" id="z_quantity_in" value="LIKE"></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->quantity_in->CellAttributes() ?>>
			<span id="el_sparepart_report_quantity_in">
<input type="text" data-table="sparepart_report" data-field="x_quantity_in" name="x_quantity_in" id="x_quantity_in" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sparepart_report->quantity_in->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->quantity_in->EditValue ?>"<?php echo $sparepart_report->quantity_in->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->quantity_used->Visible) { // quantity_used ?>
	<div id="r_quantity_used" class="form-group">
		<label for="x_quantity_used" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_quantity_used"><?php echo $sparepart_report->quantity_used->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_quantity_used" id="z_quantity_used" value="LIKE"></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->quantity_used->CellAttributes() ?>>
			<span id="el_sparepart_report_quantity_used">
<input type="text" data-table="sparepart_report" data-field="x_quantity_used" name="x_quantity_used" id="x_quantity_used" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sparepart_report->quantity_used->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->quantity_used->EditValue ?>"<?php echo $sparepart_report->quantity_used->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->cost->Visible) { // cost ?>
	<div id="r_cost" class="form-group">
		<label for="x_cost" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_cost"><?php echo $sparepart_report->cost->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_cost" id="z_cost" value="="></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->cost->CellAttributes() ?>>
			<span id="el_sparepart_report_cost">
<input type="text" data-table="sparepart_report" data-field="x_cost" name="x_cost" id="x_cost" size="30" placeholder="<?php echo ew_HtmlEncode($sparepart_report->cost->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->cost->EditValue ?>"<?php echo $sparepart_report->cost->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->total_quantity->Visible) { // total_quantity ?>
	<div id="r_total_quantity" class="form-group">
		<label for="x_total_quantity" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_total_quantity"><?php echo $sparepart_report->total_quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_total_quantity" id="z_total_quantity" value="LIKE"></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->total_quantity->CellAttributes() ?>>
			<span id="el_sparepart_report_total_quantity">
<input type="text" data-table="sparepart_report" data-field="x_total_quantity" name="x_total_quantity" id="x_total_quantity" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sparepart_report->total_quantity->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->total_quantity->EditValue ?>"<?php echo $sparepart_report->total_quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->total_cost->Visible) { // total_cost ?>
	<div id="r_total_cost" class="form-group">
		<label for="x_total_cost" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_total_cost"><?php echo $sparepart_report->total_cost->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_total_cost" id="z_total_cost" value="="></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->total_cost->CellAttributes() ?>>
			<span id="el_sparepart_report_total_cost">
<input type="text" data-table="sparepart_report" data-field="x_total_cost" name="x_total_cost" id="x_total_cost" size="30" placeholder="<?php echo ew_HtmlEncode($sparepart_report->total_cost->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->total_cost->EditValue ?>"<?php echo $sparepart_report->total_cost->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sparepart_report->maintenance_total_cost->Visible) { // maintenance_total_cost ?>
	<div id="r_maintenance_total_cost" class="form-group">
		<label for="x_maintenance_total_cost" class="<?php echo $sparepart_report_search->LeftColumnClass ?>"><span id="elh_sparepart_report_maintenance_total_cost"><?php echo $sparepart_report->maintenance_total_cost->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maintenance_total_cost" id="z_maintenance_total_cost" value="="></p>
		</label>
		<div class="<?php echo $sparepart_report_search->RightColumnClass ?>"><div<?php echo $sparepart_report->maintenance_total_cost->CellAttributes() ?>>
			<span id="el_sparepart_report_maintenance_total_cost">
<input type="text" data-table="sparepart_report" data-field="x_maintenance_total_cost" name="x_maintenance_total_cost" id="x_maintenance_total_cost" size="30" placeholder="<?php echo ew_HtmlEncode($sparepart_report->maintenance_total_cost->getPlaceHolder()) ?>" value="<?php echo $sparepart_report->maintenance_total_cost->EditValue ?>"<?php echo $sparepart_report->maintenance_total_cost->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$sparepart_report_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $sparepart_report_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fsparepart_reportsearch.Init();
</script>
<?php
$sparepart_report_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sparepart_report_search->Page_Terminate();
?>
