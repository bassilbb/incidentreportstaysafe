<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "genmaintenance_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$genmaintenance_report_list = NULL; // Initialize page object first

class cgenmaintenance_report_list extends cgenmaintenance_report {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'genmaintenance_report';

	// Page object name
	var $PageObjName = 'genmaintenance_report_list';

	// Grid form hidden field names
	var $FormName = 'fgenmaintenance_reportlist';
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

		// Table object (genmaintenance_report)
		if (!isset($GLOBALS["genmaintenance_report"]) || get_class($GLOBALS["genmaintenance_report"]) == "cgenmaintenance_report") {
			$GLOBALS["genmaintenance_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["genmaintenance_report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "genmaintenance_reportadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "genmaintenance_reportdelete.php";
		$this->MultiUpdateUrl = "genmaintenance_reportupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'genmaintenance_report');

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
		$this->FilterOptions->TagClassName = "ewFilterOption fgenmaintenance_reportlistsrch";

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
		$this->datetime->SetVisibility();
		$this->gen_name->SetVisibility();
		$this->maintenance_type->SetVisibility();
		$this->running_hours->SetVisibility();
		$this->cost->SetVisibility();
		$this->labour_fee->SetVisibility();
		$this->total->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->status->SetVisibility();

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
		global $EW_EXPORT, $genmaintenance_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($genmaintenance_report);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

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

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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
		if ($sFilter == "") {
			$sFilter = "0=101";
			$this->SearchWhere = $sFilter;
		}

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
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fgenmaintenance_reportlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->datetime->AdvancedSearch->ToJson(), ","); // Field datetime
		$sFilterList = ew_Concat($sFilterList, $this->gen_name->AdvancedSearch->ToJson(), ","); // Field gen_name
		$sFilterList = ew_Concat($sFilterList, $this->maintenance_type->AdvancedSearch->ToJson(), ","); // Field maintenance_type
		$sFilterList = ew_Concat($sFilterList, $this->running_hours->AdvancedSearch->ToJson(), ","); // Field running_hours
		$sFilterList = ew_Concat($sFilterList, $this->cost->AdvancedSearch->ToJson(), ","); // Field cost
		$sFilterList = ew_Concat($sFilterList, $this->labour_fee->AdvancedSearch->ToJson(), ","); // Field labour_fee
		$sFilterList = ew_Concat($sFilterList, $this->total->AdvancedSearch->ToJson(), ","); // Field total
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->initiator_action->AdvancedSearch->ToJson(), ","); // Field initiator_action
		$sFilterList = ew_Concat($sFilterList, $this->initiator_comment->AdvancedSearch->ToJson(), ","); // Field initiator_comment
		$sFilterList = ew_Concat($sFilterList, $this->approver_date->AdvancedSearch->ToJson(), ","); // Field approver_date
		$sFilterList = ew_Concat($sFilterList, $this->approver_action->AdvancedSearch->ToJson(), ","); // Field approver_action
		$sFilterList = ew_Concat($sFilterList, $this->approver_comment->AdvancedSearch->ToJson(), ","); // Field approver_comment
		$sFilterList = ew_Concat($sFilterList, $this->approved_by->AdvancedSearch->ToJson(), ","); // Field approved_by
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fgenmaintenance_reportlistsrch", $filters);

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

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field datetime
		$this->datetime->AdvancedSearch->SearchValue = @$filter["x_datetime"];
		$this->datetime->AdvancedSearch->SearchOperator = @$filter["z_datetime"];
		$this->datetime->AdvancedSearch->SearchCondition = @$filter["v_datetime"];
		$this->datetime->AdvancedSearch->SearchValue2 = @$filter["y_datetime"];
		$this->datetime->AdvancedSearch->SearchOperator2 = @$filter["w_datetime"];
		$this->datetime->AdvancedSearch->Save();

		// Field gen_name
		$this->gen_name->AdvancedSearch->SearchValue = @$filter["x_gen_name"];
		$this->gen_name->AdvancedSearch->SearchOperator = @$filter["z_gen_name"];
		$this->gen_name->AdvancedSearch->SearchCondition = @$filter["v_gen_name"];
		$this->gen_name->AdvancedSearch->SearchValue2 = @$filter["y_gen_name"];
		$this->gen_name->AdvancedSearch->SearchOperator2 = @$filter["w_gen_name"];
		$this->gen_name->AdvancedSearch->Save();

		// Field maintenance_type
		$this->maintenance_type->AdvancedSearch->SearchValue = @$filter["x_maintenance_type"];
		$this->maintenance_type->AdvancedSearch->SearchOperator = @$filter["z_maintenance_type"];
		$this->maintenance_type->AdvancedSearch->SearchCondition = @$filter["v_maintenance_type"];
		$this->maintenance_type->AdvancedSearch->SearchValue2 = @$filter["y_maintenance_type"];
		$this->maintenance_type->AdvancedSearch->SearchOperator2 = @$filter["w_maintenance_type"];
		$this->maintenance_type->AdvancedSearch->Save();

		// Field running_hours
		$this->running_hours->AdvancedSearch->SearchValue = @$filter["x_running_hours"];
		$this->running_hours->AdvancedSearch->SearchOperator = @$filter["z_running_hours"];
		$this->running_hours->AdvancedSearch->SearchCondition = @$filter["v_running_hours"];
		$this->running_hours->AdvancedSearch->SearchValue2 = @$filter["y_running_hours"];
		$this->running_hours->AdvancedSearch->SearchOperator2 = @$filter["w_running_hours"];
		$this->running_hours->AdvancedSearch->Save();

		// Field cost
		$this->cost->AdvancedSearch->SearchValue = @$filter["x_cost"];
		$this->cost->AdvancedSearch->SearchOperator = @$filter["z_cost"];
		$this->cost->AdvancedSearch->SearchCondition = @$filter["v_cost"];
		$this->cost->AdvancedSearch->SearchValue2 = @$filter["y_cost"];
		$this->cost->AdvancedSearch->SearchOperator2 = @$filter["w_cost"];
		$this->cost->AdvancedSearch->Save();

		// Field labour_fee
		$this->labour_fee->AdvancedSearch->SearchValue = @$filter["x_labour_fee"];
		$this->labour_fee->AdvancedSearch->SearchOperator = @$filter["z_labour_fee"];
		$this->labour_fee->AdvancedSearch->SearchCondition = @$filter["v_labour_fee"];
		$this->labour_fee->AdvancedSearch->SearchValue2 = @$filter["y_labour_fee"];
		$this->labour_fee->AdvancedSearch->SearchOperator2 = @$filter["w_labour_fee"];
		$this->labour_fee->AdvancedSearch->Save();

		// Field total
		$this->total->AdvancedSearch->SearchValue = @$filter["x_total"];
		$this->total->AdvancedSearch->SearchOperator = @$filter["z_total"];
		$this->total->AdvancedSearch->SearchCondition = @$filter["v_total"];
		$this->total->AdvancedSearch->SearchValue2 = @$filter["y_total"];
		$this->total->AdvancedSearch->SearchOperator2 = @$filter["w_total"];
		$this->total->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = @$filter["x_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchOperator = @$filter["z_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchCondition = @$filter["v_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchValue2 = @$filter["y_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchOperator2 = @$filter["w_initiator_action"];
		$this->initiator_action->AdvancedSearch->Save();

		// Field initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = @$filter["x_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchOperator = @$filter["z_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchCondition = @$filter["v_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchValue2 = @$filter["y_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchOperator2 = @$filter["w_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->Save();

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
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->datetime, $Default, FALSE); // datetime
		$this->BuildSearchSql($sWhere, $this->gen_name, $Default, FALSE); // gen_name
		$this->BuildSearchSql($sWhere, $this->maintenance_type, $Default, FALSE); // maintenance_type
		$this->BuildSearchSql($sWhere, $this->running_hours, $Default, FALSE); // running_hours
		$this->BuildSearchSql($sWhere, $this->cost, $Default, FALSE); // cost
		$this->BuildSearchSql($sWhere, $this->labour_fee, $Default, FALSE); // labour_fee
		$this->BuildSearchSql($sWhere, $this->total, $Default, FALSE); // total
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->initiator_action, $Default, FALSE); // initiator_action
		$this->BuildSearchSql($sWhere, $this->initiator_comment, $Default, FALSE); // initiator_comment
		$this->BuildSearchSql($sWhere, $this->approver_date, $Default, FALSE); // approver_date
		$this->BuildSearchSql($sWhere, $this->approver_action, $Default, FALSE); // approver_action
		$this->BuildSearchSql($sWhere, $this->approver_comment, $Default, FALSE); // approver_comment
		$this->BuildSearchSql($sWhere, $this->approved_by, $Default, FALSE); // approved_by

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->datetime->AdvancedSearch->Save(); // datetime
			$this->gen_name->AdvancedSearch->Save(); // gen_name
			$this->maintenance_type->AdvancedSearch->Save(); // maintenance_type
			$this->running_hours->AdvancedSearch->Save(); // running_hours
			$this->cost->AdvancedSearch->Save(); // cost
			$this->labour_fee->AdvancedSearch->Save(); // labour_fee
			$this->total->AdvancedSearch->Save(); // total
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->status->AdvancedSearch->Save(); // status
			$this->initiator_action->AdvancedSearch->Save(); // initiator_action
			$this->initiator_comment->AdvancedSearch->Save(); // initiator_comment
			$this->approver_date->AdvancedSearch->Save(); // approver_date
			$this->approver_action->AdvancedSearch->Save(); // approver_action
			$this->approver_comment->AdvancedSearch->Save(); // approver_comment
			$this->approved_by->AdvancedSearch->Save(); // approved_by
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->gen_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->running_hours, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->initiator_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approver_comment, $arKeywords, $type);
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->datetime->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->gen_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->maintenance_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->running_hours->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cost->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->labour_fee->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->total->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approver_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approver_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approver_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approved_by->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->datetime->AdvancedSearch->UnsetSession();
		$this->gen_name->AdvancedSearch->UnsetSession();
		$this->maintenance_type->AdvancedSearch->UnsetSession();
		$this->running_hours->AdvancedSearch->UnsetSession();
		$this->cost->AdvancedSearch->UnsetSession();
		$this->labour_fee->AdvancedSearch->UnsetSession();
		$this->total->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->initiator_action->AdvancedSearch->UnsetSession();
		$this->initiator_comment->AdvancedSearch->UnsetSession();
		$this->approver_date->AdvancedSearch->UnsetSession();
		$this->approver_action->AdvancedSearch->UnsetSession();
		$this->approver_comment->AdvancedSearch->UnsetSession();
		$this->approved_by->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->datetime->AdvancedSearch->Load();
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
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->datetime); // datetime
			$this->UpdateSort($this->gen_name); // gen_name
			$this->UpdateSort($this->maintenance_type); // maintenance_type
			$this->UpdateSort($this->running_hours); // running_hours
			$this->UpdateSort($this->cost); // cost
			$this->UpdateSort($this->labour_fee); // labour_fee
			$this->UpdateSort($this->total); // total
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->status); // status
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
				$this->datetime->setSort("");
				$this->gen_name->setSort("");
				$this->maintenance_type->setSort("");
				$this->running_hours->setSort("");
				$this->cost->setSort("");
				$this->labour_fee->setSort("");
				$this->total->setSort("");
				$this->staff_id->setSort("");
				$this->status->setSort("");
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

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"genmaintenance_report\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fgenmaintenance_reportlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fgenmaintenance_reportlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fgenmaintenance_reportlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgenmaintenance_reportlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"genmaintenance_reportsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fgenmaintenance_reportlistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = @$_GET["x_id"];
		if ($this->id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// datetime
		$this->datetime->AdvancedSearch->SearchValue = @$_GET["x_datetime"];
		if ($this->datetime->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime->AdvancedSearch->SearchOperator = @$_GET["z_datetime"];
		$this->datetime->AdvancedSearch->SearchCondition = @$_GET["v_datetime"];
		$this->datetime->AdvancedSearch->SearchValue2 = @$_GET["y_datetime"];
		if ($this->datetime->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime->AdvancedSearch->SearchOperator2 = @$_GET["w_datetime"];

		// gen_name
		$this->gen_name->AdvancedSearch->SearchValue = @$_GET["x_gen_name"];
		if ($this->gen_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->gen_name->AdvancedSearch->SearchOperator = @$_GET["z_gen_name"];

		// maintenance_type
		$this->maintenance_type->AdvancedSearch->SearchValue = @$_GET["x_maintenance_type"];
		if ($this->maintenance_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->maintenance_type->AdvancedSearch->SearchOperator = @$_GET["z_maintenance_type"];

		// running_hours
		$this->running_hours->AdvancedSearch->SearchValue = @$_GET["x_running_hours"];
		if ($this->running_hours->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->running_hours->AdvancedSearch->SearchOperator = @$_GET["z_running_hours"];

		// cost
		$this->cost->AdvancedSearch->SearchValue = @$_GET["x_cost"];
		if ($this->cost->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->cost->AdvancedSearch->SearchOperator = @$_GET["z_cost"];

		// labour_fee
		$this->labour_fee->AdvancedSearch->SearchValue = @$_GET["x_labour_fee"];
		if ($this->labour_fee->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->labour_fee->AdvancedSearch->SearchOperator = @$_GET["z_labour_fee"];

		// total
		$this->total->AdvancedSearch->SearchValue = @$_GET["x_total"];
		if ($this->total->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->total->AdvancedSearch->SearchOperator = @$_GET["z_total"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// status
		$this->status->AdvancedSearch->SearchValue = @$_GET["x_status"];
		if ($this->status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = @$_GET["x_initiator_action"];
		if ($this->initiator_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiator_action->AdvancedSearch->SearchOperator = @$_GET["z_initiator_action"];

		// initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = @$_GET["x_initiator_comment"];
		if ($this->initiator_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiator_comment->AdvancedSearch->SearchOperator = @$_GET["z_initiator_comment"];

		// approver_date
		$this->approver_date->AdvancedSearch->SearchValue = @$_GET["x_approver_date"];
		if ($this->approver_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approver_date->AdvancedSearch->SearchOperator = @$_GET["z_approver_date"];

		// approver_action
		$this->approver_action->AdvancedSearch->SearchValue = @$_GET["x_approver_action"];
		if ($this->approver_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approver_action->AdvancedSearch->SearchOperator = @$_GET["z_approver_action"];

		// approver_comment
		$this->approver_comment->AdvancedSearch->SearchValue = @$_GET["x_approver_comment"];
		if ($this->approver_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approver_comment->AdvancedSearch->SearchOperator = @$_GET["z_approver_comment"];

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$_GET["x_approved_by"];
		if ($this->approved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approved_by->AdvancedSearch->SearchOperator = @$_GET["z_approved_by"];
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
		$this->id->setDbValue($row['id']);
		$this->datetime->setDbValue($row['datetime']);
		$this->gen_name->setDbValue($row['gen_name']);
		$this->maintenance_type->setDbValue($row['maintenance_type']);
		$this->running_hours->setDbValue($row['running_hours']);
		$this->cost->setDbValue($row['cost']);
		$this->labour_fee->setDbValue($row['labour_fee']);
		$this->total->setDbValue($row['total']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->approver_date->setDbValue($row['approver_date']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['datetime'] = NULL;
		$row['gen_name'] = NULL;
		$row['maintenance_type'] = NULL;
		$row['running_hours'] = NULL;
		$row['cost'] = NULL;
		$row['labour_fee'] = NULL;
		$row['total'] = NULL;
		$row['staff_id'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['approver_date'] = NULL;
		$row['approver_action'] = NULL;
		$row['approver_comment'] = NULL;
		$row['approved_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime->DbValue = $row['datetime'];
		$this->gen_name->DbValue = $row['gen_name'];
		$this->maintenance_type->DbValue = $row['maintenance_type'];
		$this->running_hours->DbValue = $row['running_hours'];
		$this->cost->DbValue = $row['cost'];
		$this->labour_fee->DbValue = $row['labour_fee'];
		$this->total->DbValue = $row['total'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime
		$this->datetime->ViewValue = $this->datetime->CurrentValue;
		$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 0);
		$this->datetime->ViewCustomAttributes = "";

		// gen_name
		if (strval($this->gen_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
		$sWhereWrk = "";
		$this->gen_name->LookupFilters = array("dx1" => '`gen_name`', "dx2" => '`location`', "dx3" => '`kva`');
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

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

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
			if ($this->Export == "")
				$this->running_hours->ViewValue = $this->HighlightValue($this->running_hours);

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";
			if ($this->Export == "")
				$this->cost->ViewValue = $this->HighlightValue($this->cost);

			// labour_fee
			$this->labour_fee->LinkCustomAttributes = "";
			$this->labour_fee->HrefValue = "";
			$this->labour_fee->TooltipValue = "";
			if ($this->Export == "")
				$this->labour_fee->ViewValue = $this->HighlightValue($this->labour_fee);

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";
			if ($this->Export == "")
				$this->total->ViewValue = $this->HighlightValue($this->total);

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// datetime
			$this->datetime->EditAttrs["class"] = "form-control";
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime->AdvancedSearch->SearchValue, 0), 8));
			$this->datetime->PlaceHolder = ew_RemoveHtml($this->datetime->FldCaption());
			$this->datetime->EditAttrs["class"] = "form-control";
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime->AdvancedSearch->SearchValue2, 0), 8));
			$this->datetime->PlaceHolder = ew_RemoveHtml($this->datetime->FldCaption());

			// gen_name
			$this->gen_name->EditCustomAttributes = "";
			if (trim(strval($this->gen_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `generator_registration`";
			$sWhereWrk = "";
			$this->gen_name->LookupFilters = array("dx1" => '`gen_name`', "dx2" => '`location`', "dx3" => '`kva`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->gen_name->AdvancedSearch->ViewValue = $this->gen_name->DisplayValue($arwrk);
			} else {
				$this->gen_name->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
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
		if (!ew_CheckDateDef($this->datetime->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->datetime->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->datetime->FldErrMsg());
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
		$item->Body = "<button id=\"emf_genmaintenance_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_genmaintenance_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgenmaintenance_reportlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
		case "x_gen_name":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`gen_name`', "dx2" => '`location`', "dx3" => '`kva`');
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
			}
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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
if (!isset($genmaintenance_report_list)) $genmaintenance_report_list = new cgenmaintenance_report_list();

// Page init
$genmaintenance_report_list->Page_Init();

// Page main
$genmaintenance_report_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$genmaintenance_report_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($genmaintenance_report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fgenmaintenance_reportlist = new ew_Form("fgenmaintenance_reportlist", "list");
fgenmaintenance_reportlist.FormKeyCountName = '<?php echo $genmaintenance_report_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgenmaintenance_reportlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgenmaintenance_reportlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fgenmaintenance_reportlist.Lists["x_gen_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","x_kva",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fgenmaintenance_reportlist.Lists["x_gen_name"].Data = "<?php echo $genmaintenance_report_list->gen_name->LookupFilterQuery(FALSE, "list") ?>";
fgenmaintenance_reportlist.Lists["x_maintenance_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintenance_type"};
fgenmaintenance_reportlist.Lists["x_maintenance_type"].Data = "<?php echo $genmaintenance_report_list->maintenance_type->LookupFilterQuery(FALSE, "list") ?>";
fgenmaintenance_reportlist.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgenmaintenance_reportlist.Lists["x_staff_id"].Data = "<?php echo $genmaintenance_report_list->staff_id->LookupFilterQuery(FALSE, "list") ?>";
fgenmaintenance_reportlist.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_status"};
fgenmaintenance_reportlist.Lists["x_status"].Data = "<?php echo $genmaintenance_report_list->status->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fgenmaintenance_reportlistsrch = new ew_Form("fgenmaintenance_reportlistsrch");

// Validate function for search
fgenmaintenance_reportlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_datetime");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($genmaintenance_report->datetime->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fgenmaintenance_reportlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgenmaintenance_reportlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fgenmaintenance_reportlistsrch.Lists["x_gen_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","x_kva",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fgenmaintenance_reportlistsrch.Lists["x_gen_name"].Data = "<?php echo $genmaintenance_report_list->gen_name->LookupFilterQuery(FALSE, "extbs") ?>";
fgenmaintenance_reportlistsrch.Lists["x_maintenance_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintenance_type"};
fgenmaintenance_reportlistsrch.Lists["x_maintenance_type"].Data = "<?php echo $genmaintenance_report_list->maintenance_type->LookupFilterQuery(FALSE, "extbs") ?>";
fgenmaintenance_reportlistsrch.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_status"};
fgenmaintenance_reportlistsrch.Lists["x_status"].Data = "<?php echo $genmaintenance_report_list->status->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($genmaintenance_report->Export == "") { ?>
<div class="ewToolbar">
<?php if ($genmaintenance_report_list->TotalRecs > 0 && $genmaintenance_report_list->ExportOptions->Visible()) { ?>
<?php $genmaintenance_report_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($genmaintenance_report_list->SearchOptions->Visible()) { ?>
<?php $genmaintenance_report_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($genmaintenance_report_list->FilterOptions->Visible()) { ?>
<?php $genmaintenance_report_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $genmaintenance_report_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($genmaintenance_report_list->TotalRecs <= 0)
			$genmaintenance_report_list->TotalRecs = $genmaintenance_report->ListRecordCount();
	} else {
		if (!$genmaintenance_report_list->Recordset && ($genmaintenance_report_list->Recordset = $genmaintenance_report_list->LoadRecordset()))
			$genmaintenance_report_list->TotalRecs = $genmaintenance_report_list->Recordset->RecordCount();
	}
	$genmaintenance_report_list->StartRec = 1;
	if ($genmaintenance_report_list->DisplayRecs <= 0 || ($genmaintenance_report->Export <> "" && $genmaintenance_report->ExportAll)) // Display all records
		$genmaintenance_report_list->DisplayRecs = $genmaintenance_report_list->TotalRecs;
	if (!($genmaintenance_report->Export <> "" && $genmaintenance_report->ExportAll))
		$genmaintenance_report_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$genmaintenance_report_list->Recordset = $genmaintenance_report_list->LoadRecordset($genmaintenance_report_list->StartRec-1, $genmaintenance_report_list->DisplayRecs);

	// Set no record found message
	if ($genmaintenance_report->CurrentAction == "" && $genmaintenance_report_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$genmaintenance_report_list->setWarningMessage(ew_DeniedMsg());
		if ($genmaintenance_report_list->SearchWhere == "0=101")
			$genmaintenance_report_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$genmaintenance_report_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($genmaintenance_report_list->AuditTrailOnSearch && $genmaintenance_report_list->Command == "search" && !$genmaintenance_report_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $genmaintenance_report_list->getSessionWhere();
		$genmaintenance_report_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$genmaintenance_report_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($genmaintenance_report->Export == "" && $genmaintenance_report->CurrentAction == "") { ?>
<form name="fgenmaintenance_reportlistsrch" id="fgenmaintenance_reportlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($genmaintenance_report_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fgenmaintenance_reportlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="genmaintenance_report">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$genmaintenance_report_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$genmaintenance_report->RowType = EW_ROWTYPE_SEARCH;

// Render row
$genmaintenance_report->ResetAttrs();
$genmaintenance_report_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($genmaintenance_report->datetime->Visible) { // datetime ?>
	<div id="xsc_datetime" class="ewCell form-group">
		<label for="x_datetime" class="ewSearchCaption ewLabel"><?php echo $genmaintenance_report->datetime->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_datetime" id="z_datetime" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="genmaintenance_report" data-field="x_datetime" name="x_datetime" id="x_datetime" size="30" placeholder="<?php echo ew_HtmlEncode($genmaintenance_report->datetime->getPlaceHolder()) ?>" value="<?php echo $genmaintenance_report->datetime->EditValue ?>"<?php echo $genmaintenance_report->datetime->EditAttributes() ?>>
<?php if (!$genmaintenance_report->datetime->ReadOnly && !$genmaintenance_report->datetime->Disabled && !isset($genmaintenance_report->datetime->EditAttrs["readonly"]) && !isset($genmaintenance_report->datetime->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fgenmaintenance_reportlistsrch", "x_datetime", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_datetime">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_datetime">
<input type="text" data-table="genmaintenance_report" data-field="x_datetime" name="y_datetime" id="y_datetime" size="30" placeholder="<?php echo ew_HtmlEncode($genmaintenance_report->datetime->getPlaceHolder()) ?>" value="<?php echo $genmaintenance_report->datetime->EditValue2 ?>"<?php echo $genmaintenance_report->datetime->EditAttributes() ?>>
<?php if (!$genmaintenance_report->datetime->ReadOnly && !$genmaintenance_report->datetime->Disabled && !isset($genmaintenance_report->datetime->EditAttrs["readonly"]) && !isset($genmaintenance_report->datetime->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fgenmaintenance_reportlistsrch", "y_datetime", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($genmaintenance_report->gen_name->Visible) { // gen_name ?>
	<div id="xsc_gen_name" class="ewCell form-group">
		<label for="x_gen_name" class="ewSearchCaption ewLabel"><?php echo $genmaintenance_report->gen_name->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_gen_name" id="z_gen_name" value="LIKE"></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_gen_name"><?php echo (strval($genmaintenance_report->gen_name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $genmaintenance_report->gen_name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($genmaintenance_report->gen_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_gen_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($genmaintenance_report->gen_name->ReadOnly || $genmaintenance_report->gen_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="genmaintenance_report" data-field="x_gen_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $genmaintenance_report->gen_name->DisplayValueSeparatorAttribute() ?>" name="x_gen_name" id="x_gen_name" value="<?php echo $genmaintenance_report->gen_name->AdvancedSearch->SearchValue ?>"<?php echo $genmaintenance_report->gen_name->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($genmaintenance_report->maintenance_type->Visible) { // maintenance_type ?>
	<div id="xsc_maintenance_type" class="ewCell form-group">
		<label for="x_maintenance_type" class="ewSearchCaption ewLabel"><?php echo $genmaintenance_report->maintenance_type->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maintenance_type" id="z_maintenance_type" value="="></span>
		<span class="ewSearchField">
<select data-table="genmaintenance_report" data-field="x_maintenance_type" data-value-separator="<?php echo $genmaintenance_report->maintenance_type->DisplayValueSeparatorAttribute() ?>" id="x_maintenance_type" name="x_maintenance_type"<?php echo $genmaintenance_report->maintenance_type->EditAttributes() ?>>
<?php echo $genmaintenance_report->maintenance_type->SelectOptionListHtml("x_maintenance_type") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($genmaintenance_report->status->Visible) { // status ?>
	<div id="xsc_status" class="ewCell form-group">
		<label for="x_status" class="ewSearchCaption ewLabel"><?php echo $genmaintenance_report->status->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></span>
		<span class="ewSearchField">
<select data-table="genmaintenance_report" data-field="x_status" data-value-separator="<?php echo $genmaintenance_report->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $genmaintenance_report->status->EditAttributes() ?>>
<?php echo $genmaintenance_report->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($genmaintenance_report_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($genmaintenance_report_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $genmaintenance_report_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($genmaintenance_report_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($genmaintenance_report_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($genmaintenance_report_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($genmaintenance_report_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $genmaintenance_report_list->ShowPageHeader(); ?>
<?php
$genmaintenance_report_list->ShowMessage();
?>
<?php if ($genmaintenance_report_list->TotalRecs > 0 || $genmaintenance_report->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($genmaintenance_report_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> genmaintenance_report">
<?php if ($genmaintenance_report->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($genmaintenance_report->CurrentAction <> "gridadd" && $genmaintenance_report->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($genmaintenance_report_list->Pager)) $genmaintenance_report_list->Pager = new cPrevNextPager($genmaintenance_report_list->StartRec, $genmaintenance_report_list->DisplayRecs, $genmaintenance_report_list->TotalRecs, $genmaintenance_report_list->AutoHidePager) ?>
<?php if ($genmaintenance_report_list->Pager->RecordCount > 0 && $genmaintenance_report_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($genmaintenance_report_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $genmaintenance_report_list->PageUrl() ?>start=<?php echo $genmaintenance_report_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($genmaintenance_report_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $genmaintenance_report_list->PageUrl() ?>start=<?php echo $genmaintenance_report_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $genmaintenance_report_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($genmaintenance_report_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $genmaintenance_report_list->PageUrl() ?>start=<?php echo $genmaintenance_report_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($genmaintenance_report_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $genmaintenance_report_list->PageUrl() ?>start=<?php echo $genmaintenance_report_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $genmaintenance_report_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($genmaintenance_report_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $genmaintenance_report_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $genmaintenance_report_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $genmaintenance_report_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($genmaintenance_report_list->TotalRecs > 0 && (!$genmaintenance_report_list->AutoHidePageSizeSelector || $genmaintenance_report_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="genmaintenance_report">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($genmaintenance_report_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($genmaintenance_report_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($genmaintenance_report_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($genmaintenance_report_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($genmaintenance_report_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($genmaintenance_report->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($genmaintenance_report_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgenmaintenance_reportlist" id="fgenmaintenance_reportlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($genmaintenance_report_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $genmaintenance_report_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="genmaintenance_report">
<div id="gmp_genmaintenance_report" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($genmaintenance_report_list->TotalRecs > 0 || $genmaintenance_report->CurrentAction == "gridedit") { ?>
<table id="tbl_genmaintenance_reportlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$genmaintenance_report_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$genmaintenance_report_list->RenderListOptions();

// Render list options (header, left)
$genmaintenance_report_list->ListOptions->Render("header", "left");
?>
<?php if ($genmaintenance_report->datetime->Visible) { // datetime ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->datetime) == "") { ?>
		<th data-name="datetime" class="<?php echo $genmaintenance_report->datetime->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_datetime" class="genmaintenance_report_datetime"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->datetime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="datetime" class="<?php echo $genmaintenance_report->datetime->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->datetime) ?>',1);"><div id="elh_genmaintenance_report_datetime" class="genmaintenance_report_datetime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->datetime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->datetime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->datetime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->gen_name->Visible) { // gen_name ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->gen_name) == "") { ?>
		<th data-name="gen_name" class="<?php echo $genmaintenance_report->gen_name->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_gen_name" class="genmaintenance_report_gen_name"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->gen_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gen_name" class="<?php echo $genmaintenance_report->gen_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->gen_name) ?>',1);"><div id="elh_genmaintenance_report_gen_name" class="genmaintenance_report_gen_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->gen_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->gen_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->gen_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->maintenance_type->Visible) { // maintenance_type ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->maintenance_type) == "") { ?>
		<th data-name="maintenance_type" class="<?php echo $genmaintenance_report->maintenance_type->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_maintenance_type" class="genmaintenance_report_maintenance_type"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->maintenance_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="maintenance_type" class="<?php echo $genmaintenance_report->maintenance_type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->maintenance_type) ?>',1);"><div id="elh_genmaintenance_report_maintenance_type" class="genmaintenance_report_maintenance_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->maintenance_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->maintenance_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->maintenance_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->running_hours->Visible) { // running_hours ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->running_hours) == "") { ?>
		<th data-name="running_hours" class="<?php echo $genmaintenance_report->running_hours->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_running_hours" class="genmaintenance_report_running_hours"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->running_hours->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="running_hours" class="<?php echo $genmaintenance_report->running_hours->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->running_hours) ?>',1);"><div id="elh_genmaintenance_report_running_hours" class="genmaintenance_report_running_hours">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->running_hours->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->running_hours->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->running_hours->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->cost->Visible) { // cost ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->cost) == "") { ?>
		<th data-name="cost" class="<?php echo $genmaintenance_report->cost->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_cost" class="genmaintenance_report_cost"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->cost->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cost" class="<?php echo $genmaintenance_report->cost->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->cost) ?>',1);"><div id="elh_genmaintenance_report_cost" class="genmaintenance_report_cost">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->cost->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->cost->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->cost->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->labour_fee->Visible) { // labour_fee ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->labour_fee) == "") { ?>
		<th data-name="labour_fee" class="<?php echo $genmaintenance_report->labour_fee->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_labour_fee" class="genmaintenance_report_labour_fee"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->labour_fee->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="labour_fee" class="<?php echo $genmaintenance_report->labour_fee->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->labour_fee) ?>',1);"><div id="elh_genmaintenance_report_labour_fee" class="genmaintenance_report_labour_fee">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->labour_fee->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->labour_fee->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->labour_fee->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->total->Visible) { // total ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->total) == "") { ?>
		<th data-name="total" class="<?php echo $genmaintenance_report->total->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_total" class="genmaintenance_report_total"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="total" class="<?php echo $genmaintenance_report->total->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->total) ?>',1);"><div id="elh_genmaintenance_report_total" class="genmaintenance_report_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->staff_id->Visible) { // staff_id ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $genmaintenance_report->staff_id->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_staff_id" class="genmaintenance_report_staff_id"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $genmaintenance_report->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->staff_id) ?>',1);"><div id="elh_genmaintenance_report_staff_id" class="genmaintenance_report_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->staff_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($genmaintenance_report->status->Visible) { // status ?>
	<?php if ($genmaintenance_report->SortUrl($genmaintenance_report->status) == "") { ?>
		<th data-name="status" class="<?php echo $genmaintenance_report->status->HeaderCellClass() ?>"><div id="elh_genmaintenance_report_status" class="genmaintenance_report_status"><div class="ewTableHeaderCaption"><?php echo $genmaintenance_report->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $genmaintenance_report->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $genmaintenance_report->SortUrl($genmaintenance_report->status) ?>',1);"><div id="elh_genmaintenance_report_status" class="genmaintenance_report_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $genmaintenance_report->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($genmaintenance_report->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($genmaintenance_report->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$genmaintenance_report_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($genmaintenance_report->ExportAll && $genmaintenance_report->Export <> "") {
	$genmaintenance_report_list->StopRec = $genmaintenance_report_list->TotalRecs;
} else {

	// Set the last record to display
	if ($genmaintenance_report_list->TotalRecs > $genmaintenance_report_list->StartRec + $genmaintenance_report_list->DisplayRecs - 1)
		$genmaintenance_report_list->StopRec = $genmaintenance_report_list->StartRec + $genmaintenance_report_list->DisplayRecs - 1;
	else
		$genmaintenance_report_list->StopRec = $genmaintenance_report_list->TotalRecs;
}
$genmaintenance_report_list->RecCnt = $genmaintenance_report_list->StartRec - 1;
if ($genmaintenance_report_list->Recordset && !$genmaintenance_report_list->Recordset->EOF) {
	$genmaintenance_report_list->Recordset->MoveFirst();
	$bSelectLimit = $genmaintenance_report_list->UseSelectLimit;
	if (!$bSelectLimit && $genmaintenance_report_list->StartRec > 1)
		$genmaintenance_report_list->Recordset->Move($genmaintenance_report_list->StartRec - 1);
} elseif (!$genmaintenance_report->AllowAddDeleteRow && $genmaintenance_report_list->StopRec == 0) {
	$genmaintenance_report_list->StopRec = $genmaintenance_report->GridAddRowCount;
}

// Initialize aggregate
$genmaintenance_report->RowType = EW_ROWTYPE_AGGREGATEINIT;
$genmaintenance_report->ResetAttrs();
$genmaintenance_report_list->RenderRow();
while ($genmaintenance_report_list->RecCnt < $genmaintenance_report_list->StopRec) {
	$genmaintenance_report_list->RecCnt++;
	if (intval($genmaintenance_report_list->RecCnt) >= intval($genmaintenance_report_list->StartRec)) {
		$genmaintenance_report_list->RowCnt++;

		// Set up key count
		$genmaintenance_report_list->KeyCount = $genmaintenance_report_list->RowIndex;

		// Init row class and style
		$genmaintenance_report->ResetAttrs();
		$genmaintenance_report->CssClass = "";
		if ($genmaintenance_report->CurrentAction == "gridadd") {
		} else {
			$genmaintenance_report_list->LoadRowValues($genmaintenance_report_list->Recordset); // Load row values
		}
		$genmaintenance_report->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$genmaintenance_report->RowAttrs = array_merge($genmaintenance_report->RowAttrs, array('data-rowindex'=>$genmaintenance_report_list->RowCnt, 'id'=>'r' . $genmaintenance_report_list->RowCnt . '_genmaintenance_report', 'data-rowtype'=>$genmaintenance_report->RowType));

		// Render row
		$genmaintenance_report_list->RenderRow();

		// Render list options
		$genmaintenance_report_list->RenderListOptions();
?>
	<tr<?php echo $genmaintenance_report->RowAttributes() ?>>
<?php

// Render list options (body, left)
$genmaintenance_report_list->ListOptions->Render("body", "left", $genmaintenance_report_list->RowCnt);
?>
	<?php if ($genmaintenance_report->datetime->Visible) { // datetime ?>
		<td data-name="datetime"<?php echo $genmaintenance_report->datetime->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_datetime" class="genmaintenance_report_datetime">
<span<?php echo $genmaintenance_report->datetime->ViewAttributes() ?>>
<?php echo $genmaintenance_report->datetime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->gen_name->Visible) { // gen_name ?>
		<td data-name="gen_name"<?php echo $genmaintenance_report->gen_name->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_gen_name" class="genmaintenance_report_gen_name">
<span<?php echo $genmaintenance_report->gen_name->ViewAttributes() ?>>
<?php echo $genmaintenance_report->gen_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->maintenance_type->Visible) { // maintenance_type ?>
		<td data-name="maintenance_type"<?php echo $genmaintenance_report->maintenance_type->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_maintenance_type" class="genmaintenance_report_maintenance_type">
<span<?php echo $genmaintenance_report->maintenance_type->ViewAttributes() ?>>
<?php echo $genmaintenance_report->maintenance_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->running_hours->Visible) { // running_hours ?>
		<td data-name="running_hours"<?php echo $genmaintenance_report->running_hours->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_running_hours" class="genmaintenance_report_running_hours">
<span<?php echo $genmaintenance_report->running_hours->ViewAttributes() ?>>
<?php echo $genmaintenance_report->running_hours->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->cost->Visible) { // cost ?>
		<td data-name="cost"<?php echo $genmaintenance_report->cost->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_cost" class="genmaintenance_report_cost">
<span<?php echo $genmaintenance_report->cost->ViewAttributes() ?>>
<?php echo $genmaintenance_report->cost->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->labour_fee->Visible) { // labour_fee ?>
		<td data-name="labour_fee"<?php echo $genmaintenance_report->labour_fee->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_labour_fee" class="genmaintenance_report_labour_fee">
<span<?php echo $genmaintenance_report->labour_fee->ViewAttributes() ?>>
<?php echo $genmaintenance_report->labour_fee->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->total->Visible) { // total ?>
		<td data-name="total"<?php echo $genmaintenance_report->total->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_total" class="genmaintenance_report_total">
<span<?php echo $genmaintenance_report->total->ViewAttributes() ?>>
<?php echo $genmaintenance_report->total->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $genmaintenance_report->staff_id->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_staff_id" class="genmaintenance_report_staff_id">
<span<?php echo $genmaintenance_report->staff_id->ViewAttributes() ?>>
<?php echo $genmaintenance_report->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($genmaintenance_report->status->Visible) { // status ?>
		<td data-name="status"<?php echo $genmaintenance_report->status->CellAttributes() ?>>
<span id="el<?php echo $genmaintenance_report_list->RowCnt ?>_genmaintenance_report_status" class="genmaintenance_report_status">
<span<?php echo $genmaintenance_report->status->ViewAttributes() ?>>
<?php echo $genmaintenance_report->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$genmaintenance_report_list->ListOptions->Render("body", "right", $genmaintenance_report_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($genmaintenance_report->CurrentAction <> "gridadd")
		$genmaintenance_report_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($genmaintenance_report->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($genmaintenance_report_list->Recordset)
	$genmaintenance_report_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($genmaintenance_report_list->TotalRecs == 0 && $genmaintenance_report->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($genmaintenance_report_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($genmaintenance_report->Export == "") { ?>
<script type="text/javascript">
fgenmaintenance_reportlistsrch.FilterList = <?php echo $genmaintenance_report_list->GetFilterList() ?>;
fgenmaintenance_reportlistsrch.Init();
fgenmaintenance_reportlist.Init();
</script>
<?php } ?>
<?php
$genmaintenance_report_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($genmaintenance_report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$genmaintenance_report_list->Page_Terminate();
?>
