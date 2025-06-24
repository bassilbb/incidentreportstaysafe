<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "restock_moduleinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$restock_module_list = NULL; // Initialize page object first

class crestock_module_list extends crestock_module {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'restock_module';

	// Page object name
	var $PageObjName = 'restock_module_list';

	// Grid form hidden field names
	var $FormName = 'frestock_modulelist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
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

		// Table object (restock_module)
		if (!isset($GLOBALS["restock_module"]) || get_class($GLOBALS["restock_module"]) == "crestock_module") {
			$GLOBALS["restock_module"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["restock_module"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "restock_moduleadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "restock_moduledelete.php";
		$this->MultiUpdateUrl = "restock_moduleupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'restock_module');

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption frestock_modulelistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->date_restocked->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->stock_balance->SetVisibility();
		$this->quantity->SetVisibility();
		$this->restocked_by->SetVisibility();
		$this->statuss->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $restock_module;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($restock_module);
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetupDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetupDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->code->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->code->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server" && isset($UserProfile))
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "frestock_modulelistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->code->AdvancedSearch->ToJson(), ","); // Field code
		$sFilterList = ew_Concat($sFilterList, $this->date_restocked->AdvancedSearch->ToJson(), ","); // Field date_restocked
		$sFilterList = ew_Concat($sFilterList, $this->reference_id->AdvancedSearch->ToJson(), ","); // Field reference_id
		$sFilterList = ew_Concat($sFilterList, $this->material_name->AdvancedSearch->ToJson(), ","); // Field material_name
		$sFilterList = ew_Concat($sFilterList, $this->type->AdvancedSearch->ToJson(), ","); // Field type
		$sFilterList = ew_Concat($sFilterList, $this->capacity->AdvancedSearch->ToJson(), ","); // Field capacity
		$sFilterList = ew_Concat($sFilterList, $this->stock_balance->AdvancedSearch->ToJson(), ","); // Field stock_balance
		$sFilterList = ew_Concat($sFilterList, $this->quantity->AdvancedSearch->ToJson(), ","); // Field quantity
		$sFilterList = ew_Concat($sFilterList, $this->restocked_by->AdvancedSearch->ToJson(), ","); // Field restocked_by
		$sFilterList = ew_Concat($sFilterList, $this->statuss->AdvancedSearch->ToJson(), ","); // Field statuss
		$sFilterList = ew_Concat($sFilterList, $this->restocked_action->AdvancedSearch->ToJson(), ","); // Field restocked_action
		$sFilterList = ew_Concat($sFilterList, $this->restocked_comment->AdvancedSearch->ToJson(), ","); // Field restocked_comment
		$sFilterList = ew_Concat($sFilterList, $this->approver_date->AdvancedSearch->ToJson(), ","); // Field approver_date
		$sFilterList = ew_Concat($sFilterList, $this->approver_action->AdvancedSearch->ToJson(), ","); // Field approver_action
		$sFilterList = ew_Concat($sFilterList, $this->approver_comment->AdvancedSearch->ToJson(), ","); // Field approver_comment
		$sFilterList = ew_Concat($sFilterList, $this->approved_by->AdvancedSearch->ToJson(), ","); // Field approved_by
		$sFilterList = ew_Concat($sFilterList, $this->verified_date->AdvancedSearch->ToJson(), ","); // Field verified_date
		$sFilterList = ew_Concat($sFilterList, $this->verified_action->AdvancedSearch->ToJson(), ","); // Field verified_action
		$sFilterList = ew_Concat($sFilterList, $this->verified_comment->AdvancedSearch->ToJson(), ","); // Field verified_comment
		$sFilterList = ew_Concat($sFilterList, $this->verified_by->AdvancedSearch->ToJson(), ","); // Field verified_by
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "frestock_modulelistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field code
		$this->code->AdvancedSearch->SearchValue = @$filter["x_code"];
		$this->code->AdvancedSearch->SearchOperator = @$filter["z_code"];
		$this->code->AdvancedSearch->SearchCondition = @$filter["v_code"];
		$this->code->AdvancedSearch->SearchValue2 = @$filter["y_code"];
		$this->code->AdvancedSearch->SearchOperator2 = @$filter["w_code"];
		$this->code->AdvancedSearch->Save();

		// Field date_restocked
		$this->date_restocked->AdvancedSearch->SearchValue = @$filter["x_date_restocked"];
		$this->date_restocked->AdvancedSearch->SearchOperator = @$filter["z_date_restocked"];
		$this->date_restocked->AdvancedSearch->SearchCondition = @$filter["v_date_restocked"];
		$this->date_restocked->AdvancedSearch->SearchValue2 = @$filter["y_date_restocked"];
		$this->date_restocked->AdvancedSearch->SearchOperator2 = @$filter["w_date_restocked"];
		$this->date_restocked->AdvancedSearch->Save();

		// Field reference_id
		$this->reference_id->AdvancedSearch->SearchValue = @$filter["x_reference_id"];
		$this->reference_id->AdvancedSearch->SearchOperator = @$filter["z_reference_id"];
		$this->reference_id->AdvancedSearch->SearchCondition = @$filter["v_reference_id"];
		$this->reference_id->AdvancedSearch->SearchValue2 = @$filter["y_reference_id"];
		$this->reference_id->AdvancedSearch->SearchOperator2 = @$filter["w_reference_id"];
		$this->reference_id->AdvancedSearch->Save();

		// Field material_name
		$this->material_name->AdvancedSearch->SearchValue = @$filter["x_material_name"];
		$this->material_name->AdvancedSearch->SearchOperator = @$filter["z_material_name"];
		$this->material_name->AdvancedSearch->SearchCondition = @$filter["v_material_name"];
		$this->material_name->AdvancedSearch->SearchValue2 = @$filter["y_material_name"];
		$this->material_name->AdvancedSearch->SearchOperator2 = @$filter["w_material_name"];
		$this->material_name->AdvancedSearch->Save();

		// Field type
		$this->type->AdvancedSearch->SearchValue = @$filter["x_type"];
		$this->type->AdvancedSearch->SearchOperator = @$filter["z_type"];
		$this->type->AdvancedSearch->SearchCondition = @$filter["v_type"];
		$this->type->AdvancedSearch->SearchValue2 = @$filter["y_type"];
		$this->type->AdvancedSearch->SearchOperator2 = @$filter["w_type"];
		$this->type->AdvancedSearch->Save();

		// Field capacity
		$this->capacity->AdvancedSearch->SearchValue = @$filter["x_capacity"];
		$this->capacity->AdvancedSearch->SearchOperator = @$filter["z_capacity"];
		$this->capacity->AdvancedSearch->SearchCondition = @$filter["v_capacity"];
		$this->capacity->AdvancedSearch->SearchValue2 = @$filter["y_capacity"];
		$this->capacity->AdvancedSearch->SearchOperator2 = @$filter["w_capacity"];
		$this->capacity->AdvancedSearch->Save();

		// Field stock_balance
		$this->stock_balance->AdvancedSearch->SearchValue = @$filter["x_stock_balance"];
		$this->stock_balance->AdvancedSearch->SearchOperator = @$filter["z_stock_balance"];
		$this->stock_balance->AdvancedSearch->SearchCondition = @$filter["v_stock_balance"];
		$this->stock_balance->AdvancedSearch->SearchValue2 = @$filter["y_stock_balance"];
		$this->stock_balance->AdvancedSearch->SearchOperator2 = @$filter["w_stock_balance"];
		$this->stock_balance->AdvancedSearch->Save();

		// Field quantity
		$this->quantity->AdvancedSearch->SearchValue = @$filter["x_quantity"];
		$this->quantity->AdvancedSearch->SearchOperator = @$filter["z_quantity"];
		$this->quantity->AdvancedSearch->SearchCondition = @$filter["v_quantity"];
		$this->quantity->AdvancedSearch->SearchValue2 = @$filter["y_quantity"];
		$this->quantity->AdvancedSearch->SearchOperator2 = @$filter["w_quantity"];
		$this->quantity->AdvancedSearch->Save();

		// Field restocked_by
		$this->restocked_by->AdvancedSearch->SearchValue = @$filter["x_restocked_by"];
		$this->restocked_by->AdvancedSearch->SearchOperator = @$filter["z_restocked_by"];
		$this->restocked_by->AdvancedSearch->SearchCondition = @$filter["v_restocked_by"];
		$this->restocked_by->AdvancedSearch->SearchValue2 = @$filter["y_restocked_by"];
		$this->restocked_by->AdvancedSearch->SearchOperator2 = @$filter["w_restocked_by"];
		$this->restocked_by->AdvancedSearch->Save();

		// Field statuss
		$this->statuss->AdvancedSearch->SearchValue = @$filter["x_statuss"];
		$this->statuss->AdvancedSearch->SearchOperator = @$filter["z_statuss"];
		$this->statuss->AdvancedSearch->SearchCondition = @$filter["v_statuss"];
		$this->statuss->AdvancedSearch->SearchValue2 = @$filter["y_statuss"];
		$this->statuss->AdvancedSearch->SearchOperator2 = @$filter["w_statuss"];
		$this->statuss->AdvancedSearch->Save();

		// Field restocked_action
		$this->restocked_action->AdvancedSearch->SearchValue = @$filter["x_restocked_action"];
		$this->restocked_action->AdvancedSearch->SearchOperator = @$filter["z_restocked_action"];
		$this->restocked_action->AdvancedSearch->SearchCondition = @$filter["v_restocked_action"];
		$this->restocked_action->AdvancedSearch->SearchValue2 = @$filter["y_restocked_action"];
		$this->restocked_action->AdvancedSearch->SearchOperator2 = @$filter["w_restocked_action"];
		$this->restocked_action->AdvancedSearch->Save();

		// Field restocked_comment
		$this->restocked_comment->AdvancedSearch->SearchValue = @$filter["x_restocked_comment"];
		$this->restocked_comment->AdvancedSearch->SearchOperator = @$filter["z_restocked_comment"];
		$this->restocked_comment->AdvancedSearch->SearchCondition = @$filter["v_restocked_comment"];
		$this->restocked_comment->AdvancedSearch->SearchValue2 = @$filter["y_restocked_comment"];
		$this->restocked_comment->AdvancedSearch->SearchOperator2 = @$filter["w_restocked_comment"];
		$this->restocked_comment->AdvancedSearch->Save();

		// Field approver_date
		$this->approver_date->AdvancedSearch->SearchValue = @$filter["x_approver_date"];
		$this->approver_date->AdvancedSearch->SearchOperator = @$filter["z_approver_date"];
		$this->approver_date->AdvancedSearch->SearchCondition = @$filter["v_approver_date"];
		$this->approver_date->AdvancedSearch->SearchValue2 = @$filter["y_approver_date"];
		$this->approver_date->AdvancedSearch->SearchOperator2 = @$filter["w_approver_date"];
		$this->approver_date->AdvancedSearch->Save();

		// Field approver_action
		$this->approver_action->AdvancedSearch->SearchValue = @$filter["x_approver_action"];
		$this->approver_action->AdvancedSearch->SearchOperator = @$filter["z_approver_action"];
		$this->approver_action->AdvancedSearch->SearchCondition = @$filter["v_approver_action"];
		$this->approver_action->AdvancedSearch->SearchValue2 = @$filter["y_approver_action"];
		$this->approver_action->AdvancedSearch->SearchOperator2 = @$filter["w_approver_action"];
		$this->approver_action->AdvancedSearch->Save();

		// Field approver_comment
		$this->approver_comment->AdvancedSearch->SearchValue = @$filter["x_approver_comment"];
		$this->approver_comment->AdvancedSearch->SearchOperator = @$filter["z_approver_comment"];
		$this->approver_comment->AdvancedSearch->SearchCondition = @$filter["v_approver_comment"];
		$this->approver_comment->AdvancedSearch->SearchValue2 = @$filter["y_approver_comment"];
		$this->approver_comment->AdvancedSearch->SearchOperator2 = @$filter["w_approver_comment"];
		$this->approver_comment->AdvancedSearch->Save();

		// Field approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$filter["x_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator = @$filter["z_approved_by"];
		$this->approved_by->AdvancedSearch->SearchCondition = @$filter["v_approved_by"];
		$this->approved_by->AdvancedSearch->SearchValue2 = @$filter["y_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator2 = @$filter["w_approved_by"];
		$this->approved_by->AdvancedSearch->Save();

		// Field verified_date
		$this->verified_date->AdvancedSearch->SearchValue = @$filter["x_verified_date"];
		$this->verified_date->AdvancedSearch->SearchOperator = @$filter["z_verified_date"];
		$this->verified_date->AdvancedSearch->SearchCondition = @$filter["v_verified_date"];
		$this->verified_date->AdvancedSearch->SearchValue2 = @$filter["y_verified_date"];
		$this->verified_date->AdvancedSearch->SearchOperator2 = @$filter["w_verified_date"];
		$this->verified_date->AdvancedSearch->Save();

		// Field verified_action
		$this->verified_action->AdvancedSearch->SearchValue = @$filter["x_verified_action"];
		$this->verified_action->AdvancedSearch->SearchOperator = @$filter["z_verified_action"];
		$this->verified_action->AdvancedSearch->SearchCondition = @$filter["v_verified_action"];
		$this->verified_action->AdvancedSearch->SearchValue2 = @$filter["y_verified_action"];
		$this->verified_action->AdvancedSearch->SearchOperator2 = @$filter["w_verified_action"];
		$this->verified_action->AdvancedSearch->Save();

		// Field verified_comment
		$this->verified_comment->AdvancedSearch->SearchValue = @$filter["x_verified_comment"];
		$this->verified_comment->AdvancedSearch->SearchOperator = @$filter["z_verified_comment"];
		$this->verified_comment->AdvancedSearch->SearchCondition = @$filter["v_verified_comment"];
		$this->verified_comment->AdvancedSearch->SearchValue2 = @$filter["y_verified_comment"];
		$this->verified_comment->AdvancedSearch->SearchOperator2 = @$filter["w_verified_comment"];
		$this->verified_comment->AdvancedSearch->Save();

		// Field verified_by
		$this->verified_by->AdvancedSearch->SearchValue = @$filter["x_verified_by"];
		$this->verified_by->AdvancedSearch->SearchOperator = @$filter["z_verified_by"];
		$this->verified_by->AdvancedSearch->SearchCondition = @$filter["v_verified_by"];
		$this->verified_by->AdvancedSearch->SearchValue2 = @$filter["y_verified_by"];
		$this->verified_by->AdvancedSearch->SearchOperator2 = @$filter["w_verified_by"];
		$this->verified_by->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->date_restocked, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->reference_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->type, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->capacity, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->stock_balance, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->quantity, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->restocked_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approver_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->verified_comment, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->date_restocked); // date_restocked
			$this->UpdateSort($this->reference_id); // reference_id
			$this->UpdateSort($this->material_name); // material_name
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->capacity); // capacity
			$this->UpdateSort($this->stock_balance); // stock_balance
			$this->UpdateSort($this->quantity); // quantity
			$this->UpdateSort($this->restocked_by); // restocked_by
			$this->UpdateSort($this->statuss); // statuss
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->date_restocked->setSort("");
				$this->reference_id->setSort("");
				$this->material_name->setSort("");
				$this->type->setSort("");
				$this->capacity->setSort("");
				$this->stock_balance->setSort("");
				$this->quantity->setSort("");
				$this->restocked_by->setSort("");
				$this->statuss->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"restock_module\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->code->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"frestock_modulelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"frestock_modulelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.frestock_modulelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"frestock_modulelistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->date_restocked->setDbValue($row['date_restocked']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->type->setDbValue($row['type']);
		$this->capacity->setDbValue($row['capacity']);
		$this->stock_balance->setDbValue($row['stock_balance']);
		$this->quantity->setDbValue($row['quantity']);
		$this->restocked_by->setDbValue($row['restocked_by']);
		$this->statuss->setDbValue($row['statuss']);
		$this->restocked_action->setDbValue($row['restocked_action']);
		$this->restocked_comment->setDbValue($row['restocked_comment']);
		$this->approver_date->setDbValue($row['approver_date']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_date->setDbValue($row['verified_date']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->verified_by->setDbValue($row['verified_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['date_restocked'] = NULL;
		$row['reference_id'] = NULL;
		$row['material_name'] = NULL;
		$row['type'] = NULL;
		$row['capacity'] = NULL;
		$row['stock_balance'] = NULL;
		$row['quantity'] = NULL;
		$row['restocked_by'] = NULL;
		$row['statuss'] = NULL;
		$row['restocked_action'] = NULL;
		$row['restocked_comment'] = NULL;
		$row['approver_date'] = NULL;
		$row['approver_action'] = NULL;
		$row['approver_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_date'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['verified_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date_restocked->DbValue = $row['date_restocked'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->type->DbValue = $row['type'];
		$this->capacity->DbValue = $row['capacity'];
		$this->stock_balance->DbValue = $row['stock_balance'];
		$this->quantity->DbValue = $row['quantity'];
		$this->restocked_by->DbValue = $row['restocked_by'];
		$this->statuss->DbValue = $row['statuss'];
		$this->restocked_action->DbValue = $row['restocked_action'];
		$this->restocked_comment->DbValue = $row['restocked_comment'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_date->DbValue = $row['verified_date'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		// restocked_by
		// statuss
		// restocked_action
		// restocked_comment
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
		$this->date_restocked->ViewValue = ew_FormatDateTime($this->date_restocked->ViewValue, 17);
		$this->date_restocked->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

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

		// restocked_by
		$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
		if (strval($this->restocked_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->restocked_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->restocked_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->restocked_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->restocked_by->ViewValue = $this->restocked_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
			}
		} else {
			$this->restocked_by->ViewValue = NULL;
		}
		$this->restocked_by->ViewCustomAttributes = "";

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

			// restocked_by
			$this->restocked_by->LinkCustomAttributes = "";
			$this->restocked_by->HrefValue = "";
			$this->restocked_by->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_restock_module\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_restock_module',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.frestock_modulelist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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
		if (CurrentPageID() == "list"){
			 $_SESSION['RST_ID'] = generateRSTKey();
		 }
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($restock_module_list)) $restock_module_list = new crestock_module_list();

// Page init
$restock_module_list->Page_Init();

// Page main
$restock_module_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$restock_module_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($restock_module->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = frestock_modulelist = new ew_Form("frestock_modulelist", "list");
frestock_modulelist.FormKeyCountName = '<?php echo $restock_module_list->FormKeyCountName ?>';

// Form_CustomValidate event
frestock_modulelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frestock_modulelist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frestock_modulelist.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory"};
frestock_modulelist.Lists["x_material_name"].Data = "<?php echo $restock_module_list->material_name->LookupFilterQuery(FALSE, "list") ?>";
frestock_modulelist.Lists["x_restocked_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frestock_modulelist.Lists["x_restocked_by"].Data = "<?php echo $restock_module_list->restocked_by->LookupFilterQuery(FALSE, "list") ?>";
frestock_modulelist.AutoSuggests["x_restocked_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $restock_module_list->restocked_by->LookupFilterQuery(TRUE, "list"))) ?>;
frestock_modulelist.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
frestock_modulelist.Lists["x_statuss"].Data = "<?php echo $restock_module_list->statuss->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = frestock_modulelistsrch = new ew_Form("frestock_modulelistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($restock_module->Export == "") { ?>
<div class="ewToolbar">
<?php if ($restock_module_list->TotalRecs > 0 && $restock_module_list->ExportOptions->Visible()) { ?>
<?php $restock_module_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($restock_module_list->SearchOptions->Visible()) { ?>
<?php $restock_module_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($restock_module_list->FilterOptions->Visible()) { ?>
<?php $restock_module_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $restock_module_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($restock_module_list->TotalRecs <= 0)
			$restock_module_list->TotalRecs = $restock_module->ListRecordCount();
	} else {
		if (!$restock_module_list->Recordset && ($restock_module_list->Recordset = $restock_module_list->LoadRecordset()))
			$restock_module_list->TotalRecs = $restock_module_list->Recordset->RecordCount();
	}
	$restock_module_list->StartRec = 1;
	if ($restock_module_list->DisplayRecs <= 0 || ($restock_module->Export <> "" && $restock_module->ExportAll)) // Display all records
		$restock_module_list->DisplayRecs = $restock_module_list->TotalRecs;
	if (!($restock_module->Export <> "" && $restock_module->ExportAll))
		$restock_module_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$restock_module_list->Recordset = $restock_module_list->LoadRecordset($restock_module_list->StartRec-1, $restock_module_list->DisplayRecs);

	// Set no record found message
	if ($restock_module->CurrentAction == "" && $restock_module_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$restock_module_list->setWarningMessage(ew_DeniedMsg());
		if ($restock_module_list->SearchWhere == "0=101")
			$restock_module_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$restock_module_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($restock_module_list->AuditTrailOnSearch && $restock_module_list->Command == "search" && !$restock_module_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $restock_module_list->getSessionWhere();
		$restock_module_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$restock_module_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($restock_module->Export == "" && $restock_module->CurrentAction == "") { ?>
<form name="frestock_modulelistsrch" id="frestock_modulelistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($restock_module_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="frestock_modulelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="restock_module">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($restock_module_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($restock_module_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $restock_module_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($restock_module_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($restock_module_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($restock_module_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($restock_module_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $restock_module_list->ShowPageHeader(); ?>
<?php
$restock_module_list->ShowMessage();
?>
<?php if ($restock_module_list->TotalRecs > 0 || $restock_module->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($restock_module_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> restock_module">
<?php if ($restock_module->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($restock_module->CurrentAction <> "gridadd" && $restock_module->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($restock_module_list->Pager)) $restock_module_list->Pager = new cPrevNextPager($restock_module_list->StartRec, $restock_module_list->DisplayRecs, $restock_module_list->TotalRecs, $restock_module_list->AutoHidePager) ?>
<?php if ($restock_module_list->Pager->RecordCount > 0 && $restock_module_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($restock_module_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $restock_module_list->PageUrl() ?>start=<?php echo $restock_module_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($restock_module_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $restock_module_list->PageUrl() ?>start=<?php echo $restock_module_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $restock_module_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($restock_module_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $restock_module_list->PageUrl() ?>start=<?php echo $restock_module_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($restock_module_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $restock_module_list->PageUrl() ?>start=<?php echo $restock_module_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $restock_module_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($restock_module_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $restock_module_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $restock_module_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $restock_module_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($restock_module_list->TotalRecs > 0 && (!$restock_module_list->AutoHidePageSizeSelector || $restock_module_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="restock_module">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($restock_module_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($restock_module_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($restock_module_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($restock_module_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($restock_module_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($restock_module->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($restock_module_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="frestock_modulelist" id="frestock_modulelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($restock_module_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $restock_module_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="restock_module">
<div id="gmp_restock_module" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($restock_module_list->TotalRecs > 0 || $restock_module->CurrentAction == "gridedit") { ?>
<table id="tbl_restock_modulelist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$restock_module_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$restock_module_list->RenderListOptions();

// Render list options (header, left)
$restock_module_list->ListOptions->Render("header", "left");
?>
<?php if ($restock_module->date_restocked->Visible) { // date_restocked ?>
	<?php if ($restock_module->SortUrl($restock_module->date_restocked) == "") { ?>
		<th data-name="date_restocked" class="<?php echo $restock_module->date_restocked->HeaderCellClass() ?>"><div id="elh_restock_module_date_restocked" class="restock_module_date_restocked"><div class="ewTableHeaderCaption"><?php echo $restock_module->date_restocked->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_restocked" class="<?php echo $restock_module->date_restocked->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->date_restocked) ?>',1);"><div id="elh_restock_module_date_restocked" class="restock_module_date_restocked">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->date_restocked->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->date_restocked->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->date_restocked->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->reference_id->Visible) { // reference_id ?>
	<?php if ($restock_module->SortUrl($restock_module->reference_id) == "") { ?>
		<th data-name="reference_id" class="<?php echo $restock_module->reference_id->HeaderCellClass() ?>"><div id="elh_restock_module_reference_id" class="restock_module_reference_id"><div class="ewTableHeaderCaption"><?php echo $restock_module->reference_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reference_id" class="<?php echo $restock_module->reference_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->reference_id) ?>',1);"><div id="elh_restock_module_reference_id" class="restock_module_reference_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->reference_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->reference_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->reference_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->material_name->Visible) { // material_name ?>
	<?php if ($restock_module->SortUrl($restock_module->material_name) == "") { ?>
		<th data-name="material_name" class="<?php echo $restock_module->material_name->HeaderCellClass() ?>"><div id="elh_restock_module_material_name" class="restock_module_material_name"><div class="ewTableHeaderCaption"><?php echo $restock_module->material_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="material_name" class="<?php echo $restock_module->material_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->material_name) ?>',1);"><div id="elh_restock_module_material_name" class="restock_module_material_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->material_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->material_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->material_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->type->Visible) { // type ?>
	<?php if ($restock_module->SortUrl($restock_module->type) == "") { ?>
		<th data-name="type" class="<?php echo $restock_module->type->HeaderCellClass() ?>"><div id="elh_restock_module_type" class="restock_module_type"><div class="ewTableHeaderCaption"><?php echo $restock_module->type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="type" class="<?php echo $restock_module->type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->type) ?>',1);"><div id="elh_restock_module_type" class="restock_module_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->capacity->Visible) { // capacity ?>
	<?php if ($restock_module->SortUrl($restock_module->capacity) == "") { ?>
		<th data-name="capacity" class="<?php echo $restock_module->capacity->HeaderCellClass() ?>"><div id="elh_restock_module_capacity" class="restock_module_capacity"><div class="ewTableHeaderCaption"><?php echo $restock_module->capacity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="capacity" class="<?php echo $restock_module->capacity->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->capacity) ?>',1);"><div id="elh_restock_module_capacity" class="restock_module_capacity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->capacity->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->capacity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->capacity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->stock_balance->Visible) { // stock_balance ?>
	<?php if ($restock_module->SortUrl($restock_module->stock_balance) == "") { ?>
		<th data-name="stock_balance" class="<?php echo $restock_module->stock_balance->HeaderCellClass() ?>"><div id="elh_restock_module_stock_balance" class="restock_module_stock_balance"><div class="ewTableHeaderCaption"><?php echo $restock_module->stock_balance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stock_balance" class="<?php echo $restock_module->stock_balance->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->stock_balance) ?>',1);"><div id="elh_restock_module_stock_balance" class="restock_module_stock_balance">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->stock_balance->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->stock_balance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->stock_balance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->quantity->Visible) { // quantity ?>
	<?php if ($restock_module->SortUrl($restock_module->quantity) == "") { ?>
		<th data-name="quantity" class="<?php echo $restock_module->quantity->HeaderCellClass() ?>"><div id="elh_restock_module_quantity" class="restock_module_quantity"><div class="ewTableHeaderCaption"><?php echo $restock_module->quantity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="quantity" class="<?php echo $restock_module->quantity->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->quantity) ?>',1);"><div id="elh_restock_module_quantity" class="restock_module_quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->quantity->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->restocked_by->Visible) { // restocked_by ?>
	<?php if ($restock_module->SortUrl($restock_module->restocked_by) == "") { ?>
		<th data-name="restocked_by" class="<?php echo $restock_module->restocked_by->HeaderCellClass() ?>"><div id="elh_restock_module_restocked_by" class="restock_module_restocked_by"><div class="ewTableHeaderCaption"><?php echo $restock_module->restocked_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="restocked_by" class="<?php echo $restock_module->restocked_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->restocked_by) ?>',1);"><div id="elh_restock_module_restocked_by" class="restock_module_restocked_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->restocked_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->restocked_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->restocked_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($restock_module->statuss->Visible) { // statuss ?>
	<?php if ($restock_module->SortUrl($restock_module->statuss) == "") { ?>
		<th data-name="statuss" class="<?php echo $restock_module->statuss->HeaderCellClass() ?>"><div id="elh_restock_module_statuss" class="restock_module_statuss"><div class="ewTableHeaderCaption"><?php echo $restock_module->statuss->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="statuss" class="<?php echo $restock_module->statuss->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $restock_module->SortUrl($restock_module->statuss) ?>',1);"><div id="elh_restock_module_statuss" class="restock_module_statuss">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $restock_module->statuss->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($restock_module->statuss->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($restock_module->statuss->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$restock_module_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($restock_module->ExportAll && $restock_module->Export <> "") {
	$restock_module_list->StopRec = $restock_module_list->TotalRecs;
} else {

	// Set the last record to display
	if ($restock_module_list->TotalRecs > $restock_module_list->StartRec + $restock_module_list->DisplayRecs - 1)
		$restock_module_list->StopRec = $restock_module_list->StartRec + $restock_module_list->DisplayRecs - 1;
	else
		$restock_module_list->StopRec = $restock_module_list->TotalRecs;
}
$restock_module_list->RecCnt = $restock_module_list->StartRec - 1;
if ($restock_module_list->Recordset && !$restock_module_list->Recordset->EOF) {
	$restock_module_list->Recordset->MoveFirst();
	$bSelectLimit = $restock_module_list->UseSelectLimit;
	if (!$bSelectLimit && $restock_module_list->StartRec > 1)
		$restock_module_list->Recordset->Move($restock_module_list->StartRec - 1);
} elseif (!$restock_module->AllowAddDeleteRow && $restock_module_list->StopRec == 0) {
	$restock_module_list->StopRec = $restock_module->GridAddRowCount;
}

// Initialize aggregate
$restock_module->RowType = EW_ROWTYPE_AGGREGATEINIT;
$restock_module->ResetAttrs();
$restock_module_list->RenderRow();
while ($restock_module_list->RecCnt < $restock_module_list->StopRec) {
	$restock_module_list->RecCnt++;
	if (intval($restock_module_list->RecCnt) >= intval($restock_module_list->StartRec)) {
		$restock_module_list->RowCnt++;

		// Set up key count
		$restock_module_list->KeyCount = $restock_module_list->RowIndex;

		// Init row class and style
		$restock_module->ResetAttrs();
		$restock_module->CssClass = "";
		if ($restock_module->CurrentAction == "gridadd") {
		} else {
			$restock_module_list->LoadRowValues($restock_module_list->Recordset); // Load row values
		}
		$restock_module->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$restock_module->RowAttrs = array_merge($restock_module->RowAttrs, array('data-rowindex'=>$restock_module_list->RowCnt, 'id'=>'r' . $restock_module_list->RowCnt . '_restock_module', 'data-rowtype'=>$restock_module->RowType));

		// Render row
		$restock_module_list->RenderRow();

		// Render list options
		$restock_module_list->RenderListOptions();
?>
	<tr<?php echo $restock_module->RowAttributes() ?>>
<?php

// Render list options (body, left)
$restock_module_list->ListOptions->Render("body", "left", $restock_module_list->RowCnt);
?>
	<?php if ($restock_module->date_restocked->Visible) { // date_restocked ?>
		<td data-name="date_restocked"<?php echo $restock_module->date_restocked->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_date_restocked" class="restock_module_date_restocked">
<span<?php echo $restock_module->date_restocked->ViewAttributes() ?>>
<?php echo $restock_module->date_restocked->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->reference_id->Visible) { // reference_id ?>
		<td data-name="reference_id"<?php echo $restock_module->reference_id->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_reference_id" class="restock_module_reference_id">
<span<?php echo $restock_module->reference_id->ViewAttributes() ?>>
<?php echo $restock_module->reference_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->material_name->Visible) { // material_name ?>
		<td data-name="material_name"<?php echo $restock_module->material_name->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_material_name" class="restock_module_material_name">
<span<?php echo $restock_module->material_name->ViewAttributes() ?>>
<?php echo $restock_module->material_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->type->Visible) { // type ?>
		<td data-name="type"<?php echo $restock_module->type->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_type" class="restock_module_type">
<span<?php echo $restock_module->type->ViewAttributes() ?>>
<?php echo $restock_module->type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->capacity->Visible) { // capacity ?>
		<td data-name="capacity"<?php echo $restock_module->capacity->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_capacity" class="restock_module_capacity">
<span<?php echo $restock_module->capacity->ViewAttributes() ?>>
<?php echo $restock_module->capacity->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->stock_balance->Visible) { // stock_balance ?>
		<td data-name="stock_balance"<?php echo $restock_module->stock_balance->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_stock_balance" class="restock_module_stock_balance">
<span<?php echo $restock_module->stock_balance->ViewAttributes() ?>>
<?php echo $restock_module->stock_balance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->quantity->Visible) { // quantity ?>
		<td data-name="quantity"<?php echo $restock_module->quantity->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_quantity" class="restock_module_quantity">
<span<?php echo $restock_module->quantity->ViewAttributes() ?>>
<?php echo $restock_module->quantity->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->restocked_by->Visible) { // restocked_by ?>
		<td data-name="restocked_by"<?php echo $restock_module->restocked_by->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_restocked_by" class="restock_module_restocked_by">
<span<?php echo $restock_module->restocked_by->ViewAttributes() ?>>
<?php echo $restock_module->restocked_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($restock_module->statuss->Visible) { // statuss ?>
		<td data-name="statuss"<?php echo $restock_module->statuss->CellAttributes() ?>>
<span id="el<?php echo $restock_module_list->RowCnt ?>_restock_module_statuss" class="restock_module_statuss">
<span<?php echo $restock_module->statuss->ViewAttributes() ?>>
<?php echo $restock_module->statuss->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$restock_module_list->ListOptions->Render("body", "right", $restock_module_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($restock_module->CurrentAction <> "gridadd")
		$restock_module_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($restock_module->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($restock_module_list->Recordset)
	$restock_module_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($restock_module_list->TotalRecs == 0 && $restock_module->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($restock_module_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($restock_module->Export == "") { ?>
<script type="text/javascript">
frestock_modulelistsrch.FilterList = <?php echo $restock_module_list->GetFilterList() ?>;
frestock_modulelistsrch.Init();
frestock_modulelist.Init();
</script>
<?php } ?>
<?php
$restock_module_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($restock_module->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$restock_module_list->Page_Terminate();
?>
