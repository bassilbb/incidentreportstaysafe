<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "maintenance_historyinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$maintenance_history_list = NULL; // Initialize page object first

class cmaintenance_history_list extends cmaintenance_history {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'maintenance_history';

	// Page object name
	var $PageObjName = 'maintenance_history_list';

	// Grid form hidden field names
	var $FormName = 'fmaintenance_historylist';
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

		// Table object (maintenance_history)
		if (!isset($GLOBALS["maintenance_history"]) || get_class($GLOBALS["maintenance_history"]) == "cmaintenance_history") {
			$GLOBALS["maintenance_history"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["maintenance_history"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "maintenance_historyadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "maintenance_historydelete.php";
		$this->MultiUpdateUrl = "maintenance_historyupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'maintenance_history', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fmaintenance_historylistsrch";

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
		$this->date_initiated->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->staff_name->SetVisibility();
		$this->department->SetVisibility();
		$this->branch->SetVisibility();
		$this->buildings->SetVisibility();
		$this->floors->SetVisibility();
		$this->items->SetVisibility();
		$this->descrption->SetVisibility();
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
		global $EW_EXPORT, $maintenance_history;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($maintenance_history);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fmaintenance_historylistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->date_initiated->AdvancedSearch->ToJson(), ","); // Field date_initiated
		$sFilterList = ew_Concat($sFilterList, $this->reference_id->AdvancedSearch->ToJson(), ","); // Field reference_id
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->staff_name->AdvancedSearch->ToJson(), ","); // Field staff_name
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->branch->AdvancedSearch->ToJson(), ","); // Field branch
		$sFilterList = ew_Concat($sFilterList, $this->buildings->AdvancedSearch->ToJson(), ","); // Field buildings
		$sFilterList = ew_Concat($sFilterList, $this->floors->AdvancedSearch->ToJson(), ","); // Field floors
		$sFilterList = ew_Concat($sFilterList, $this->items->AdvancedSearch->ToJson(), ","); // Field items
		$sFilterList = ew_Concat($sFilterList, $this->priority->AdvancedSearch->ToJson(), ","); // Field priority
		$sFilterList = ew_Concat($sFilterList, $this->descrption->AdvancedSearch->ToJson(), ","); // Field descrption
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->date_maintained->AdvancedSearch->ToJson(), ","); // Field date_maintained
		$sFilterList = ew_Concat($sFilterList, $this->maintenance_action->AdvancedSearch->ToJson(), ","); // Field maintenance_action
		$sFilterList = ew_Concat($sFilterList, $this->maintenance_comment->AdvancedSearch->ToJson(), ","); // Field maintenance_comment
		$sFilterList = ew_Concat($sFilterList, $this->maintained_by->AdvancedSearch->ToJson(), ","); // Field maintained_by
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_date->AdvancedSearch->ToJson(), ","); // Field reviewed_date
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_action->AdvancedSearch->ToJson(), ","); // Field reviewed_action
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_comment->AdvancedSearch->ToJson(), ","); // Field reviewed_comment
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_by->AdvancedSearch->ToJson(), ","); // Field reviewed_by
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fmaintenance_historylistsrch", $filters);

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

		// Field date_initiated
		$this->date_initiated->AdvancedSearch->SearchValue = @$filter["x_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchOperator = @$filter["z_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchCondition = @$filter["v_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchValue2 = @$filter["y_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchOperator2 = @$filter["w_date_initiated"];
		$this->date_initiated->AdvancedSearch->Save();

		// Field reference_id
		$this->reference_id->AdvancedSearch->SearchValue = @$filter["x_reference_id"];
		$this->reference_id->AdvancedSearch->SearchOperator = @$filter["z_reference_id"];
		$this->reference_id->AdvancedSearch->SearchCondition = @$filter["v_reference_id"];
		$this->reference_id->AdvancedSearch->SearchValue2 = @$filter["y_reference_id"];
		$this->reference_id->AdvancedSearch->SearchOperator2 = @$filter["w_reference_id"];
		$this->reference_id->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

		// Field staff_name
		$this->staff_name->AdvancedSearch->SearchValue = @$filter["x_staff_name"];
		$this->staff_name->AdvancedSearch->SearchOperator = @$filter["z_staff_name"];
		$this->staff_name->AdvancedSearch->SearchCondition = @$filter["v_staff_name"];
		$this->staff_name->AdvancedSearch->SearchValue2 = @$filter["y_staff_name"];
		$this->staff_name->AdvancedSearch->SearchOperator2 = @$filter["w_staff_name"];
		$this->staff_name->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field branch
		$this->branch->AdvancedSearch->SearchValue = @$filter["x_branch"];
		$this->branch->AdvancedSearch->SearchOperator = @$filter["z_branch"];
		$this->branch->AdvancedSearch->SearchCondition = @$filter["v_branch"];
		$this->branch->AdvancedSearch->SearchValue2 = @$filter["y_branch"];
		$this->branch->AdvancedSearch->SearchOperator2 = @$filter["w_branch"];
		$this->branch->AdvancedSearch->Save();

		// Field buildings
		$this->buildings->AdvancedSearch->SearchValue = @$filter["x_buildings"];
		$this->buildings->AdvancedSearch->SearchOperator = @$filter["z_buildings"];
		$this->buildings->AdvancedSearch->SearchCondition = @$filter["v_buildings"];
		$this->buildings->AdvancedSearch->SearchValue2 = @$filter["y_buildings"];
		$this->buildings->AdvancedSearch->SearchOperator2 = @$filter["w_buildings"];
		$this->buildings->AdvancedSearch->Save();

		// Field floors
		$this->floors->AdvancedSearch->SearchValue = @$filter["x_floors"];
		$this->floors->AdvancedSearch->SearchOperator = @$filter["z_floors"];
		$this->floors->AdvancedSearch->SearchCondition = @$filter["v_floors"];
		$this->floors->AdvancedSearch->SearchValue2 = @$filter["y_floors"];
		$this->floors->AdvancedSearch->SearchOperator2 = @$filter["w_floors"];
		$this->floors->AdvancedSearch->Save();

		// Field items
		$this->items->AdvancedSearch->SearchValue = @$filter["x_items"];
		$this->items->AdvancedSearch->SearchOperator = @$filter["z_items"];
		$this->items->AdvancedSearch->SearchCondition = @$filter["v_items"];
		$this->items->AdvancedSearch->SearchValue2 = @$filter["y_items"];
		$this->items->AdvancedSearch->SearchOperator2 = @$filter["w_items"];
		$this->items->AdvancedSearch->Save();

		// Field priority
		$this->priority->AdvancedSearch->SearchValue = @$filter["x_priority"];
		$this->priority->AdvancedSearch->SearchOperator = @$filter["z_priority"];
		$this->priority->AdvancedSearch->SearchCondition = @$filter["v_priority"];
		$this->priority->AdvancedSearch->SearchValue2 = @$filter["y_priority"];
		$this->priority->AdvancedSearch->SearchOperator2 = @$filter["w_priority"];
		$this->priority->AdvancedSearch->Save();

		// Field descrption
		$this->descrption->AdvancedSearch->SearchValue = @$filter["x_descrption"];
		$this->descrption->AdvancedSearch->SearchOperator = @$filter["z_descrption"];
		$this->descrption->AdvancedSearch->SearchCondition = @$filter["v_descrption"];
		$this->descrption->AdvancedSearch->SearchValue2 = @$filter["y_descrption"];
		$this->descrption->AdvancedSearch->SearchOperator2 = @$filter["w_descrption"];
		$this->descrption->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field date_maintained
		$this->date_maintained->AdvancedSearch->SearchValue = @$filter["x_date_maintained"];
		$this->date_maintained->AdvancedSearch->SearchOperator = @$filter["z_date_maintained"];
		$this->date_maintained->AdvancedSearch->SearchCondition = @$filter["v_date_maintained"];
		$this->date_maintained->AdvancedSearch->SearchValue2 = @$filter["y_date_maintained"];
		$this->date_maintained->AdvancedSearch->SearchOperator2 = @$filter["w_date_maintained"];
		$this->date_maintained->AdvancedSearch->Save();

		// Field maintenance_action
		$this->maintenance_action->AdvancedSearch->SearchValue = @$filter["x_maintenance_action"];
		$this->maintenance_action->AdvancedSearch->SearchOperator = @$filter["z_maintenance_action"];
		$this->maintenance_action->AdvancedSearch->SearchCondition = @$filter["v_maintenance_action"];
		$this->maintenance_action->AdvancedSearch->SearchValue2 = @$filter["y_maintenance_action"];
		$this->maintenance_action->AdvancedSearch->SearchOperator2 = @$filter["w_maintenance_action"];
		$this->maintenance_action->AdvancedSearch->Save();

		// Field maintenance_comment
		$this->maintenance_comment->AdvancedSearch->SearchValue = @$filter["x_maintenance_comment"];
		$this->maintenance_comment->AdvancedSearch->SearchOperator = @$filter["z_maintenance_comment"];
		$this->maintenance_comment->AdvancedSearch->SearchCondition = @$filter["v_maintenance_comment"];
		$this->maintenance_comment->AdvancedSearch->SearchValue2 = @$filter["y_maintenance_comment"];
		$this->maintenance_comment->AdvancedSearch->SearchOperator2 = @$filter["w_maintenance_comment"];
		$this->maintenance_comment->AdvancedSearch->Save();

		// Field maintained_by
		$this->maintained_by->AdvancedSearch->SearchValue = @$filter["x_maintained_by"];
		$this->maintained_by->AdvancedSearch->SearchOperator = @$filter["z_maintained_by"];
		$this->maintained_by->AdvancedSearch->SearchCondition = @$filter["v_maintained_by"];
		$this->maintained_by->AdvancedSearch->SearchValue2 = @$filter["y_maintained_by"];
		$this->maintained_by->AdvancedSearch->SearchOperator2 = @$filter["w_maintained_by"];
		$this->maintained_by->AdvancedSearch->Save();

		// Field reviewed_date
		$this->reviewed_date->AdvancedSearch->SearchValue = @$filter["x_reviewed_date"];
		$this->reviewed_date->AdvancedSearch->SearchOperator = @$filter["z_reviewed_date"];
		$this->reviewed_date->AdvancedSearch->SearchCondition = @$filter["v_reviewed_date"];
		$this->reviewed_date->AdvancedSearch->SearchValue2 = @$filter["y_reviewed_date"];
		$this->reviewed_date->AdvancedSearch->SearchOperator2 = @$filter["w_reviewed_date"];
		$this->reviewed_date->AdvancedSearch->Save();

		// Field reviewed_action
		$this->reviewed_action->AdvancedSearch->SearchValue = @$filter["x_reviewed_action"];
		$this->reviewed_action->AdvancedSearch->SearchOperator = @$filter["z_reviewed_action"];
		$this->reviewed_action->AdvancedSearch->SearchCondition = @$filter["v_reviewed_action"];
		$this->reviewed_action->AdvancedSearch->SearchValue2 = @$filter["y_reviewed_action"];
		$this->reviewed_action->AdvancedSearch->SearchOperator2 = @$filter["w_reviewed_action"];
		$this->reviewed_action->AdvancedSearch->Save();

		// Field reviewed_comment
		$this->reviewed_comment->AdvancedSearch->SearchValue = @$filter["x_reviewed_comment"];
		$this->reviewed_comment->AdvancedSearch->SearchOperator = @$filter["z_reviewed_comment"];
		$this->reviewed_comment->AdvancedSearch->SearchCondition = @$filter["v_reviewed_comment"];
		$this->reviewed_comment->AdvancedSearch->SearchValue2 = @$filter["y_reviewed_comment"];
		$this->reviewed_comment->AdvancedSearch->SearchOperator2 = @$filter["w_reviewed_comment"];
		$this->reviewed_comment->AdvancedSearch->Save();

		// Field reviewed_by
		$this->reviewed_by->AdvancedSearch->SearchValue = @$filter["x_reviewed_by"];
		$this->reviewed_by->AdvancedSearch->SearchOperator = @$filter["z_reviewed_by"];
		$this->reviewed_by->AdvancedSearch->SearchCondition = @$filter["v_reviewed_by"];
		$this->reviewed_by->AdvancedSearch->SearchValue2 = @$filter["y_reviewed_by"];
		$this->reviewed_by->AdvancedSearch->SearchOperator2 = @$filter["w_reviewed_by"];
		$this->reviewed_by->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->date_initiated, $Default, FALSE); // date_initiated
		$this->BuildSearchSql($sWhere, $this->reference_id, $Default, FALSE); // reference_id
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->staff_name, $Default, FALSE); // staff_name
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->branch, $Default, FALSE); // branch
		$this->BuildSearchSql($sWhere, $this->buildings, $Default, FALSE); // buildings
		$this->BuildSearchSql($sWhere, $this->floors, $Default, FALSE); // floors
		$this->BuildSearchSql($sWhere, $this->items, $Default, FALSE); // items
		$this->BuildSearchSql($sWhere, $this->priority, $Default, FALSE); // priority
		$this->BuildSearchSql($sWhere, $this->descrption, $Default, FALSE); // descrption
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->date_maintained, $Default, FALSE); // date_maintained
		$this->BuildSearchSql($sWhere, $this->maintenance_action, $Default, FALSE); // maintenance_action
		$this->BuildSearchSql($sWhere, $this->maintenance_comment, $Default, FALSE); // maintenance_comment
		$this->BuildSearchSql($sWhere, $this->maintained_by, $Default, FALSE); // maintained_by
		$this->BuildSearchSql($sWhere, $this->reviewed_date, $Default, FALSE); // reviewed_date
		$this->BuildSearchSql($sWhere, $this->reviewed_action, $Default, FALSE); // reviewed_action
		$this->BuildSearchSql($sWhere, $this->reviewed_comment, $Default, FALSE); // reviewed_comment
		$this->BuildSearchSql($sWhere, $this->reviewed_by, $Default, FALSE); // reviewed_by

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->date_initiated->AdvancedSearch->Save(); // date_initiated
			$this->reference_id->AdvancedSearch->Save(); // reference_id
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->staff_name->AdvancedSearch->Save(); // staff_name
			$this->department->AdvancedSearch->Save(); // department
			$this->branch->AdvancedSearch->Save(); // branch
			$this->buildings->AdvancedSearch->Save(); // buildings
			$this->floors->AdvancedSearch->Save(); // floors
			$this->items->AdvancedSearch->Save(); // items
			$this->priority->AdvancedSearch->Save(); // priority
			$this->descrption->AdvancedSearch->Save(); // descrption
			$this->status->AdvancedSearch->Save(); // status
			$this->date_maintained->AdvancedSearch->Save(); // date_maintained
			$this->maintenance_action->AdvancedSearch->Save(); // maintenance_action
			$this->maintenance_comment->AdvancedSearch->Save(); // maintenance_comment
			$this->maintained_by->AdvancedSearch->Save(); // maintained_by
			$this->reviewed_date->AdvancedSearch->Save(); // reviewed_date
			$this->reviewed_action->AdvancedSearch->Save(); // reviewed_action
			$this->reviewed_comment->AdvancedSearch->Save(); // reviewed_comment
			$this->reviewed_by->AdvancedSearch->Save(); // reviewed_by
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
		$this->BuildBasicSearchSQL($sWhere, $this->reference_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staff_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->maintenance_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->reviewed_comment, $arKeywords, $type);
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
		if ($this->date_initiated->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reference_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->branch->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->buildings->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->floors->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->items->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->priority->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->descrption->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_maintained->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->maintenance_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->maintenance_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->maintained_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reviewed_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reviewed_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reviewed_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reviewed_by->AdvancedSearch->IssetSession())
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
		$this->date_initiated->AdvancedSearch->UnsetSession();
		$this->reference_id->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->staff_name->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->branch->AdvancedSearch->UnsetSession();
		$this->buildings->AdvancedSearch->UnsetSession();
		$this->floors->AdvancedSearch->UnsetSession();
		$this->items->AdvancedSearch->UnsetSession();
		$this->priority->AdvancedSearch->UnsetSession();
		$this->descrption->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->date_maintained->AdvancedSearch->UnsetSession();
		$this->maintenance_action->AdvancedSearch->UnsetSession();
		$this->maintenance_comment->AdvancedSearch->UnsetSession();
		$this->maintained_by->AdvancedSearch->UnsetSession();
		$this->reviewed_date->AdvancedSearch->UnsetSession();
		$this->reviewed_action->AdvancedSearch->UnsetSession();
		$this->reviewed_comment->AdvancedSearch->UnsetSession();
		$this->reviewed_by->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->date_initiated->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->staff_name->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->branch->AdvancedSearch->Load();
		$this->buildings->AdvancedSearch->Load();
		$this->floors->AdvancedSearch->Load();
		$this->items->AdvancedSearch->Load();
		$this->priority->AdvancedSearch->Load();
		$this->descrption->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->date_maintained->AdvancedSearch->Load();
		$this->maintenance_action->AdvancedSearch->Load();
		$this->maintenance_comment->AdvancedSearch->Load();
		$this->maintained_by->AdvancedSearch->Load();
		$this->reviewed_date->AdvancedSearch->Load();
		$this->reviewed_action->AdvancedSearch->Load();
		$this->reviewed_comment->AdvancedSearch->Load();
		$this->reviewed_by->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->date_initiated); // date_initiated
			$this->UpdateSort($this->reference_id); // reference_id
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->staff_name); // staff_name
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->branch); // branch
			$this->UpdateSort($this->buildings); // buildings
			$this->UpdateSort($this->floors); // floors
			$this->UpdateSort($this->items); // items
			$this->UpdateSort($this->descrption); // descrption
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
				$this->date_initiated->setSort("");
				$this->reference_id->setSort("");
				$this->staff_id->setSort("");
				$this->staff_name->setSort("");
				$this->department->setSort("");
				$this->branch->setSort("");
				$this->buildings->setSort("");
				$this->floors->setSort("");
				$this->items->setSort("");
				$this->descrption->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"maintenance_history\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fmaintenance_historylistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fmaintenance_historylistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fmaintenance_historylist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fmaintenance_historylistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = @$_GET["x_id"];
		if ($this->id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// date_initiated
		$this->date_initiated->AdvancedSearch->SearchValue = @$_GET["x_date_initiated"];
		if ($this->date_initiated->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_initiated->AdvancedSearch->SearchOperator = @$_GET["z_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchCondition = @$_GET["v_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchValue2 = @$_GET["y_date_initiated"];
		if ($this->date_initiated->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->date_initiated->AdvancedSearch->SearchOperator2 = @$_GET["w_date_initiated"];

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = @$_GET["x_reference_id"];
		if ($this->reference_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reference_id->AdvancedSearch->SearchOperator = @$_GET["z_reference_id"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// staff_name
		$this->staff_name->AdvancedSearch->SearchValue = @$_GET["x_staff_name"];
		if ($this->staff_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_name->AdvancedSearch->SearchOperator = @$_GET["z_staff_name"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// branch
		$this->branch->AdvancedSearch->SearchValue = @$_GET["x_branch"];
		if ($this->branch->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->branch->AdvancedSearch->SearchOperator = @$_GET["z_branch"];

		// buildings
		$this->buildings->AdvancedSearch->SearchValue = @$_GET["x_buildings"];
		if ($this->buildings->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->buildings->AdvancedSearch->SearchOperator = @$_GET["z_buildings"];

		// floors
		$this->floors->AdvancedSearch->SearchValue = @$_GET["x_floors"];
		if ($this->floors->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->floors->AdvancedSearch->SearchOperator = @$_GET["z_floors"];

		// items
		$this->items->AdvancedSearch->SearchValue = @$_GET["x_items"];
		if ($this->items->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->items->AdvancedSearch->SearchOperator = @$_GET["z_items"];
		if (is_array($this->items->AdvancedSearch->SearchValue)) $this->items->AdvancedSearch->SearchValue = implode(",", $this->items->AdvancedSearch->SearchValue);
		if (is_array($this->items->AdvancedSearch->SearchValue2)) $this->items->AdvancedSearch->SearchValue2 = implode(",", $this->items->AdvancedSearch->SearchValue2);

		// priority
		$this->priority->AdvancedSearch->SearchValue = @$_GET["x_priority"];
		if ($this->priority->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->priority->AdvancedSearch->SearchOperator = @$_GET["z_priority"];

		// descrption
		$this->descrption->AdvancedSearch->SearchValue = @$_GET["x_descrption"];
		if ($this->descrption->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->descrption->AdvancedSearch->SearchOperator = @$_GET["z_descrption"];

		// status
		$this->status->AdvancedSearch->SearchValue = @$_GET["x_status"];
		if ($this->status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// date_maintained
		$this->date_maintained->AdvancedSearch->SearchValue = @$_GET["x_date_maintained"];
		if ($this->date_maintained->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_maintained->AdvancedSearch->SearchOperator = @$_GET["z_date_maintained"];

		// maintenance_action
		$this->maintenance_action->AdvancedSearch->SearchValue = @$_GET["x_maintenance_action"];
		if ($this->maintenance_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->maintenance_action->AdvancedSearch->SearchOperator = @$_GET["z_maintenance_action"];

		// maintenance_comment
		$this->maintenance_comment->AdvancedSearch->SearchValue = @$_GET["x_maintenance_comment"];
		if ($this->maintenance_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->maintenance_comment->AdvancedSearch->SearchOperator = @$_GET["z_maintenance_comment"];

		// maintained_by
		$this->maintained_by->AdvancedSearch->SearchValue = @$_GET["x_maintained_by"];
		if ($this->maintained_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->maintained_by->AdvancedSearch->SearchOperator = @$_GET["z_maintained_by"];

		// reviewed_date
		$this->reviewed_date->AdvancedSearch->SearchValue = @$_GET["x_reviewed_date"];
		if ($this->reviewed_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reviewed_date->AdvancedSearch->SearchOperator = @$_GET["z_reviewed_date"];

		// reviewed_action
		$this->reviewed_action->AdvancedSearch->SearchValue = @$_GET["x_reviewed_action"];
		if ($this->reviewed_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reviewed_action->AdvancedSearch->SearchOperator = @$_GET["z_reviewed_action"];

		// reviewed_comment
		$this->reviewed_comment->AdvancedSearch->SearchValue = @$_GET["x_reviewed_comment"];
		if ($this->reviewed_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reviewed_comment->AdvancedSearch->SearchOperator = @$_GET["z_reviewed_comment"];

		// reviewed_by
		$this->reviewed_by->AdvancedSearch->SearchValue = @$_GET["x_reviewed_by"];
		if ($this->reviewed_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reviewed_by->AdvancedSearch->SearchOperator = @$_GET["z_reviewed_by"];
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
		$this->date_initiated->setDbValue($row['date_initiated']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->staff_name->setDbValue($row['staff_name']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->buildings->setDbValue($row['buildings']);
		$this->floors->setDbValue($row['floors']);
		$this->items->setDbValue($row['items']);
		$this->priority->setDbValue($row['priority']);
		$this->descrption->setDbValue($row['descrption']);
		$this->status->setDbValue($row['status']);
		$this->date_maintained->setDbValue($row['date_maintained']);
		$this->maintenance_action->setDbValue($row['maintenance_action']);
		$this->maintenance_comment->setDbValue($row['maintenance_comment']);
		$this->maintained_by->setDbValue($row['maintained_by']);
		$this->reviewed_date->setDbValue($row['reviewed_date']);
		$this->reviewed_action->setDbValue($row['reviewed_action']);
		$this->reviewed_comment->setDbValue($row['reviewed_comment']);
		$this->reviewed_by->setDbValue($row['reviewed_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['staff_name'] = NULL;
		$row['department'] = NULL;
		$row['branch'] = NULL;
		$row['buildings'] = NULL;
		$row['floors'] = NULL;
		$row['items'] = NULL;
		$row['priority'] = NULL;
		$row['descrption'] = NULL;
		$row['status'] = NULL;
		$row['date_maintained'] = NULL;
		$row['maintenance_action'] = NULL;
		$row['maintenance_comment'] = NULL;
		$row['maintained_by'] = NULL;
		$row['reviewed_date'] = NULL;
		$row['reviewed_action'] = NULL;
		$row['reviewed_comment'] = NULL;
		$row['reviewed_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->staff_name->DbValue = $row['staff_name'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->buildings->DbValue = $row['buildings'];
		$this->floors->DbValue = $row['floors'];
		$this->items->DbValue = $row['items'];
		$this->priority->DbValue = $row['priority'];
		$this->descrption->DbValue = $row['descrption'];
		$this->status->DbValue = $row['status'];
		$this->date_maintained->DbValue = $row['date_maintained'];
		$this->maintenance_action->DbValue = $row['maintenance_action'];
		$this->maintenance_comment->DbValue = $row['maintenance_comment'];
		$this->maintained_by->DbValue = $row['maintained_by'];
		$this->reviewed_date->DbValue = $row['reviewed_date'];
		$this->reviewed_action->DbValue = $row['reviewed_action'];
		$this->reviewed_comment->DbValue = $row['reviewed_comment'];
		$this->reviewed_by->DbValue = $row['reviewed_by'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date_initiated
		// reference_id
		// staff_id
		// staff_name
		// department
		// branch
		// buildings
		// floors
		// items
		// priority
		// descrption
		// status
		// date_maintained
		// maintenance_action
		// maintenance_comment
		// maintained_by
		// reviewed_date
		// reviewed_action
		// reviewed_comment
		// reviewed_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

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

		// staff_name
		if (strval($this->staff_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_name->ViewValue = $this->staff_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
			}
		} else {
			$this->staff_name->ViewValue = NULL;
		}
		$this->staff_name->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
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

		// branch
		$this->branch->ViewValue = $this->branch->CurrentValue;
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->branch->ViewValue = $this->branch->CurrentValue;
			}
		} else {
			$this->branch->ViewValue = NULL;
		}
		$this->branch->ViewCustomAttributes = "";

		// buildings
		if (strval($this->buildings->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->buildings->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `buildings`";
		$sWhereWrk = "";
		$this->buildings->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->buildings->ViewValue = $this->buildings->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->buildings->ViewValue = $this->buildings->CurrentValue;
			}
		} else {
			$this->buildings->ViewValue = NULL;
		}
		$this->buildings->ViewCustomAttributes = "";

		// floors
		if (strval($this->floors->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->floors->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `floors`";
		$sWhereWrk = "";
		$this->floors->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->floors->ViewValue = $this->floors->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->floors->ViewValue = $this->floors->CurrentValue;
			}
		} else {
			$this->floors->ViewValue = NULL;
		}
		$this->floors->ViewCustomAttributes = "";

		// items
		if (strval($this->items->CurrentValue) <> "") {
			$arwrk = explode(",", $this->items->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
		$sWhereWrk = "";
		$this->items->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->items->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->items->ViewValue .= $this->items->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->items->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->items->ViewValue = $this->items->CurrentValue;
			}
		} else {
			$this->items->ViewValue = NULL;
		}
		$this->items->ViewCustomAttributes = "";

		// priority
		if (strval($this->priority->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->priority->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->priority->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->priority, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->priority->ViewValue = $this->priority->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->priority->ViewValue = $this->priority->CurrentValue;
			}
		} else {
			$this->priority->ViewValue = NULL;
		}
		$this->priority->ViewCustomAttributes = "";

		// descrption
		$this->descrption->ViewValue = $this->descrption->CurrentValue;
		$this->descrption->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintained_status`";
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

		// date_maintained
		$this->date_maintained->ViewValue = $this->date_maintained->CurrentValue;
		$this->date_maintained->ViewValue = ew_FormatDateTime($this->date_maintained->ViewValue, 14);
		$this->date_maintained->ViewCustomAttributes = "";

		// maintenance_action
		if (strval($this->maintenance_action->CurrentValue) <> "") {
			$this->maintenance_action->ViewValue = $this->maintenance_action->OptionCaption($this->maintenance_action->CurrentValue);
		} else {
			$this->maintenance_action->ViewValue = NULL;
		}
		$this->maintenance_action->ViewCustomAttributes = "";

		// maintained_by
		$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
		if (strval($this->maintained_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintained_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->maintained_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->maintained_by->ViewValue = $this->maintained_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
			}
		} else {
			$this->maintained_by->ViewValue = NULL;
		}
		$this->maintained_by->ViewCustomAttributes = "";

		// reviewed_date
		$this->reviewed_date->ViewValue = $this->reviewed_date->CurrentValue;
		$this->reviewed_date->ViewValue = ew_FormatDateTime($this->reviewed_date->ViewValue, 14);
		$this->reviewed_date->ViewCustomAttributes = "";

		// reviewed_action
		if (strval($this->reviewed_action->CurrentValue) <> "") {
			$this->reviewed_action->ViewValue = $this->reviewed_action->OptionCaption($this->reviewed_action->CurrentValue);
		} else {
			$this->reviewed_action->ViewValue = NULL;
		}
		$this->reviewed_action->ViewCustomAttributes = "";

		// reviewed_comment
		$this->reviewed_comment->ViewValue = $this->reviewed_comment->CurrentValue;
		$this->reviewed_comment->ViewCustomAttributes = "";

		// reviewed_by
		$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
		if (strval($this->reviewed_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reviewed_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->reviewed_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->reviewed_by->ViewValue = $this->reviewed_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
			}
		} else {
			$this->reviewed_by->ViewValue = NULL;
		}
		$this->reviewed_by->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// staff_name
			$this->staff_name->LinkCustomAttributes = "";
			$this->staff_name->HrefValue = "";
			$this->staff_name->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// buildings
			$this->buildings->LinkCustomAttributes = "";
			$this->buildings->HrefValue = "";
			$this->buildings->TooltipValue = "";

			// floors
			$this->floors->LinkCustomAttributes = "";
			$this->floors->HrefValue = "";
			$this->floors->TooltipValue = "";

			// items
			$this->items->LinkCustomAttributes = "";
			$this->items->HrefValue = "";
			$this->items->TooltipValue = "";

			// descrption
			$this->descrption->LinkCustomAttributes = "";
			$this->descrption->HrefValue = "";
			$this->descrption->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue, 0), 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue2, 0), 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->AdvancedSearch->SearchValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// staff_name
			$this->staff_name->EditCustomAttributes = "";
			if (trim(strval($this->staff_name->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->staff_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->staff_name->AdvancedSearch->ViewValue = $this->staff_name->DisplayValue($arwrk);
			} else {
				$this->staff_name->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->staff_name->EditValue = $arwrk;

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";
			$this->department->EditValue = ew_HtmlEncode($this->department->AdvancedSearch->SearchValue);
			$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

			// branch
			$this->branch->EditAttrs["class"] = "form-control";
			$this->branch->EditCustomAttributes = "";
			$this->branch->EditValue = ew_HtmlEncode($this->branch->AdvancedSearch->SearchValue);
			$this->branch->PlaceHolder = ew_RemoveHtml($this->branch->FldCaption());

			// buildings
			$this->buildings->EditCustomAttributes = "";
			if (trim(strval($this->buildings->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->buildings->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `buildings`";
			$sWhereWrk = "";
			$this->buildings->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->buildings->AdvancedSearch->ViewValue = $this->buildings->DisplayValue($arwrk);
			} else {
				$this->buildings->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->buildings->EditValue = $arwrk;

			// floors
			$this->floors->EditCustomAttributes = "";
			if (trim(strval($this->floors->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->floors->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `buildings_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `floors`";
			$sWhereWrk = "";
			$this->floors->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->floors->AdvancedSearch->ViewValue = $this->floors->DisplayValue($arwrk);
			} else {
				$this->floors->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->floors->EditValue = $arwrk;

			// items
			$this->items->EditCustomAttributes = "";
			if (trim(strval($this->items->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->items->AdvancedSearch->SearchValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `floor_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `items`";
			$sWhereWrk = "";
			$this->items->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->items->AdvancedSearch->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->items->AdvancedSearch->ViewValue .= $this->items->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->items->AdvancedSearch->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->items->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->items->EditValue = $arwrk;

			// descrption
			$this->descrption->EditAttrs["class"] = "form-control";
			$this->descrption->EditCustomAttributes = "";
			$this->descrption->EditValue = ew_HtmlEncode($this->descrption->AdvancedSearch->SearchValue);
			$this->descrption->PlaceHolder = ew_RemoveHtml($this->descrption->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
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
		if (!ew_CheckDateDef($this->date_initiated->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_initiated->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_initiated->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->date_initiated->FldErrMsg());
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
		$this->date_initiated->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->staff_name->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->branch->AdvancedSearch->Load();
		$this->buildings->AdvancedSearch->Load();
		$this->floors->AdvancedSearch->Load();
		$this->items->AdvancedSearch->Load();
		$this->priority->AdvancedSearch->Load();
		$this->descrption->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->date_maintained->AdvancedSearch->Load();
		$this->maintenance_action->AdvancedSearch->Load();
		$this->maintenance_comment->AdvancedSearch->Load();
		$this->maintained_by->AdvancedSearch->Load();
		$this->reviewed_date->AdvancedSearch->Load();
		$this->reviewed_action->AdvancedSearch->Load();
		$this->reviewed_comment->AdvancedSearch->Load();
		$this->reviewed_by->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_maintenance_history\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_maintenance_history',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmaintenance_historylist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		case "x_staff_name":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_buildings":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `buildings`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_floors":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `floors`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`buildings_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_items":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`floor_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($maintenance_history_list)) $maintenance_history_list = new cmaintenance_history_list();

// Page init
$maintenance_history_list->Page_Init();

// Page main
$maintenance_history_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$maintenance_history_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($maintenance_history->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fmaintenance_historylist = new ew_Form("fmaintenance_historylist", "list");
fmaintenance_historylist.FormKeyCountName = '<?php echo $maintenance_history_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmaintenance_historylist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmaintenance_historylist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fmaintenance_historylist.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenance_historylist.Lists["x_staff_id"].Data = "<?php echo $maintenance_history_list->staff_id->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_history_list->staff_id->LookupFilterQuery(TRUE, "list"))) ?>;
fmaintenance_historylist.Lists["x_staff_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenance_historylist.Lists["x_staff_name"].Data = "<?php echo $maintenance_history_list->staff_name->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fmaintenance_historylist.Lists["x_department"].Data = "<?php echo $maintenance_history_list->department->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_history_list->department->LookupFilterQuery(TRUE, "list"))) ?>;
fmaintenance_historylist.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
fmaintenance_historylist.Lists["x_branch"].Data = "<?php echo $maintenance_history_list->branch->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.AutoSuggests["x_branch"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_history_list->branch->LookupFilterQuery(TRUE, "list"))) ?>;
fmaintenance_historylist.Lists["x_buildings"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_floors"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"buildings"};
fmaintenance_historylist.Lists["x_buildings"].Data = "<?php echo $maintenance_history_list->buildings->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.Lists["x_floors"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_items[]"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"floors"};
fmaintenance_historylist.Lists["x_floors"].Data = "<?php echo $maintenance_history_list->floors->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.Lists["x_items[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"items"};
fmaintenance_historylist.Lists["x_items[]"].Data = "<?php echo $maintenance_history_list->items->LookupFilterQuery(FALSE, "list") ?>";
fmaintenance_historylist.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintained_status"};
fmaintenance_historylist.Lists["x_status"].Data = "<?php echo $maintenance_history_list->status->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fmaintenance_historylistsrch = new ew_Form("fmaintenance_historylistsrch");

// Validate function for search
fmaintenance_historylistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_date_initiated");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance_history->date_initiated->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fmaintenance_historylistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmaintenance_historylistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fmaintenance_historylistsrch.Lists["x_staff_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenance_historylistsrch.Lists["x_staff_name"].Data = "<?php echo $maintenance_history_list->staff_name->LookupFilterQuery(FALSE, "extbs") ?>";
fmaintenance_historylistsrch.Lists["x_buildings"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_floors"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"buildings"};
fmaintenance_historylistsrch.Lists["x_buildings"].Data = "<?php echo $maintenance_history_list->buildings->LookupFilterQuery(FALSE, "extbs") ?>";
fmaintenance_historylistsrch.Lists["x_floors"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_buildings"],"ChildFields":["x_items[]"],"FilterFields":["x_buildings_id"],"Options":[],"Template":"","LinkTable":"floors"};
fmaintenance_historylistsrch.Lists["x_floors"].Data = "<?php echo $maintenance_history_list->floors->LookupFilterQuery(FALSE, "extbs") ?>";
fmaintenance_historylistsrch.Lists["x_items[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_floors"],"ChildFields":[],"FilterFields":["x_floor_id"],"Options":[],"Template":"","LinkTable":"items"};
fmaintenance_historylistsrch.Lists["x_items[]"].Data = "<?php echo $maintenance_history_list->items->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($maintenance_history->Export == "") { ?>
<div class="ewToolbar">
<?php if ($maintenance_history_list->TotalRecs > 0 && $maintenance_history_list->ExportOptions->Visible()) { ?>
<?php $maintenance_history_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($maintenance_history_list->SearchOptions->Visible()) { ?>
<?php $maintenance_history_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($maintenance_history_list->FilterOptions->Visible()) { ?>
<?php $maintenance_history_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $maintenance_history_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($maintenance_history_list->TotalRecs <= 0)
			$maintenance_history_list->TotalRecs = $maintenance_history->ListRecordCount();
	} else {
		if (!$maintenance_history_list->Recordset && ($maintenance_history_list->Recordset = $maintenance_history_list->LoadRecordset()))
			$maintenance_history_list->TotalRecs = $maintenance_history_list->Recordset->RecordCount();
	}
	$maintenance_history_list->StartRec = 1;
	if ($maintenance_history_list->DisplayRecs <= 0 || ($maintenance_history->Export <> "" && $maintenance_history->ExportAll)) // Display all records
		$maintenance_history_list->DisplayRecs = $maintenance_history_list->TotalRecs;
	if (!($maintenance_history->Export <> "" && $maintenance_history->ExportAll))
		$maintenance_history_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$maintenance_history_list->Recordset = $maintenance_history_list->LoadRecordset($maintenance_history_list->StartRec-1, $maintenance_history_list->DisplayRecs);

	// Set no record found message
	if ($maintenance_history->CurrentAction == "" && $maintenance_history_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$maintenance_history_list->setWarningMessage(ew_DeniedMsg());
		if ($maintenance_history_list->SearchWhere == "0=101")
			$maintenance_history_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$maintenance_history_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$maintenance_history_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($maintenance_history->Export == "" && $maintenance_history->CurrentAction == "") { ?>
<form name="fmaintenance_historylistsrch" id="fmaintenance_historylistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($maintenance_history_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fmaintenance_historylistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="maintenance_history">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$maintenance_history_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$maintenance_history->RowType = EW_ROWTYPE_SEARCH;

// Render row
$maintenance_history->ResetAttrs();
$maintenance_history_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($maintenance_history->date_initiated->Visible) { // date_initiated ?>
	<div id="xsc_date_initiated" class="ewCell form-group">
		<label for="x_date_initiated" class="ewSearchCaption ewLabel"><?php echo $maintenance_history->date_initiated->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date_initiated" id="z_date_initiated" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="maintenance_history" data-field="x_date_initiated" name="x_date_initiated" id="x_date_initiated" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance_history->date_initiated->getPlaceHolder()) ?>" value="<?php echo $maintenance_history->date_initiated->EditValue ?>"<?php echo $maintenance_history->date_initiated->EditAttributes() ?>>
<?php if (!$maintenance_history->date_initiated->ReadOnly && !$maintenance_history->date_initiated->Disabled && !isset($maintenance_history->date_initiated->EditAttrs["readonly"]) && !isset($maintenance_history->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fmaintenance_historylistsrch", "x_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_date_initiated">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_date_initiated">
<input type="text" data-table="maintenance_history" data-field="x_date_initiated" name="y_date_initiated" id="y_date_initiated" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance_history->date_initiated->getPlaceHolder()) ?>" value="<?php echo $maintenance_history->date_initiated->EditValue2 ?>"<?php echo $maintenance_history->date_initiated->EditAttributes() ?>>
<?php if (!$maintenance_history->date_initiated->ReadOnly && !$maintenance_history->date_initiated->Disabled && !isset($maintenance_history->date_initiated->EditAttrs["readonly"]) && !isset($maintenance_history->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fmaintenance_historylistsrch", "y_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($maintenance_history->reference_id->Visible) { // reference_id ?>
	<div id="xsc_reference_id" class="ewCell form-group">
		<label for="x_reference_id" class="ewSearchCaption ewLabel"><?php echo $maintenance_history->reference_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="maintenance_history" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="25" maxlength="50" placeholder="<?php echo ew_HtmlEncode($maintenance_history->reference_id->getPlaceHolder()) ?>" value="<?php echo $maintenance_history->reference_id->EditValue ?>"<?php echo $maintenance_history->reference_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($maintenance_history->staff_name->Visible) { // staff_name ?>
	<div id="xsc_staff_name" class="ewCell form-group">
		<label for="x_staff_name" class="ewSearchCaption ewLabel"><?php echo $maintenance_history->staff_name->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_name" id="z_staff_name" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_staff_name"><?php echo (strval($maintenance_history->staff_name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance_history->staff_name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance_history->staff_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_staff_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance_history->staff_name->ReadOnly || $maintenance_history->staff_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance_history" data-field="x_staff_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $maintenance_history->staff_name->DisplayValueSeparatorAttribute() ?>" name="x_staff_name" id="x_staff_name" value="<?php echo $maintenance_history->staff_name->AdvancedSearch->SearchValue ?>"<?php echo $maintenance_history->staff_name->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($maintenance_history->buildings->Visible) { // buildings ?>
	<div id="xsc_buildings" class="ewCell form-group">
		<label for="x_buildings" class="ewSearchCaption ewLabel"><?php echo $maintenance_history->buildings->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_buildings" id="z_buildings" value="="></span>
		<span class="ewSearchField">
<?php $maintenance_history->buildings->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$maintenance_history->buildings->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_buildings"><?php echo (strval($maintenance_history->buildings->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance_history->buildings->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance_history->buildings->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_buildings',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance_history->buildings->ReadOnly || $maintenance_history->buildings->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance_history" data-field="x_buildings" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $maintenance_history->buildings->DisplayValueSeparatorAttribute() ?>" name="x_buildings" id="x_buildings" value="<?php echo $maintenance_history->buildings->AdvancedSearch->SearchValue ?>"<?php echo $maintenance_history->buildings->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($maintenance_history->floors->Visible) { // floors ?>
	<div id="xsc_floors" class="ewCell form-group">
		<label for="x_floors" class="ewSearchCaption ewLabel"><?php echo $maintenance_history->floors->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_floors" id="z_floors" value="="></span>
		<span class="ewSearchField">
<?php $maintenance_history->floors->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$maintenance_history->floors->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_floors"><?php echo (strval($maintenance_history->floors->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance_history->floors->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance_history->floors->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_floors',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance_history->floors->ReadOnly || $maintenance_history->floors->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance_history" data-field="x_floors" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $maintenance_history->floors->DisplayValueSeparatorAttribute() ?>" name="x_floors" id="x_floors" value="<?php echo $maintenance_history->floors->AdvancedSearch->SearchValue ?>"<?php echo $maintenance_history->floors->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($maintenance_history->items->Visible) { // items ?>
	<div id="xsc_items" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $maintenance_history->items->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_items" id="z_items" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_items"><?php echo (strval($maintenance_history->items->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance_history->items->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance_history->items->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_items[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance_history->items->ReadOnly || $maintenance_history->items->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance_history" data-field="x_items" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $maintenance_history->items->DisplayValueSeparatorAttribute() ?>" name="x_items[]" id="x_items[]" value="<?php echo $maintenance_history->items->AdvancedSearch->SearchValue ?>"<?php echo $maintenance_history->items->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($maintenance_history_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($maintenance_history_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $maintenance_history_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($maintenance_history_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($maintenance_history_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($maintenance_history_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($maintenance_history_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $maintenance_history_list->ShowPageHeader(); ?>
<?php
$maintenance_history_list->ShowMessage();
?>
<?php if ($maintenance_history_list->TotalRecs > 0 || $maintenance_history->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($maintenance_history_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> maintenance_history">
<?php if ($maintenance_history->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($maintenance_history->CurrentAction <> "gridadd" && $maintenance_history->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($maintenance_history_list->Pager)) $maintenance_history_list->Pager = new cPrevNextPager($maintenance_history_list->StartRec, $maintenance_history_list->DisplayRecs, $maintenance_history_list->TotalRecs, $maintenance_history_list->AutoHidePager) ?>
<?php if ($maintenance_history_list->Pager->RecordCount > 0 && $maintenance_history_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($maintenance_history_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $maintenance_history_list->PageUrl() ?>start=<?php echo $maintenance_history_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($maintenance_history_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $maintenance_history_list->PageUrl() ?>start=<?php echo $maintenance_history_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $maintenance_history_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($maintenance_history_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $maintenance_history_list->PageUrl() ?>start=<?php echo $maintenance_history_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($maintenance_history_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $maintenance_history_list->PageUrl() ?>start=<?php echo $maintenance_history_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $maintenance_history_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($maintenance_history_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $maintenance_history_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $maintenance_history_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $maintenance_history_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($maintenance_history_list->TotalRecs > 0 && (!$maintenance_history_list->AutoHidePageSizeSelector || $maintenance_history_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="maintenance_history">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($maintenance_history_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($maintenance_history_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($maintenance_history_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($maintenance_history_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($maintenance_history_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($maintenance_history->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($maintenance_history_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmaintenance_historylist" id="fmaintenance_historylist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($maintenance_history_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $maintenance_history_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="maintenance_history">
<div id="gmp_maintenance_history" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($maintenance_history_list->TotalRecs > 0 || $maintenance_history->CurrentAction == "gridedit") { ?>
<table id="tbl_maintenance_historylist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$maintenance_history_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$maintenance_history_list->RenderListOptions();

// Render list options (header, left)
$maintenance_history_list->ListOptions->Render("header", "left");
?>
<?php if ($maintenance_history->date_initiated->Visible) { // date_initiated ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->date_initiated) == "") { ?>
		<th data-name="date_initiated" class="<?php echo $maintenance_history->date_initiated->HeaderCellClass() ?>"><div id="elh_maintenance_history_date_initiated" class="maintenance_history_date_initiated"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->date_initiated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_initiated" class="<?php echo $maintenance_history->date_initiated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->date_initiated) ?>',1);"><div id="elh_maintenance_history_date_initiated" class="maintenance_history_date_initiated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->date_initiated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->date_initiated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->date_initiated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->reference_id->Visible) { // reference_id ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->reference_id) == "") { ?>
		<th data-name="reference_id" class="<?php echo $maintenance_history->reference_id->HeaderCellClass() ?>"><div id="elh_maintenance_history_reference_id" class="maintenance_history_reference_id"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->reference_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reference_id" class="<?php echo $maintenance_history->reference_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->reference_id) ?>',1);"><div id="elh_maintenance_history_reference_id" class="maintenance_history_reference_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->reference_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->reference_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->reference_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->staff_id->Visible) { // staff_id ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $maintenance_history->staff_id->HeaderCellClass() ?>"><div id="elh_maintenance_history_staff_id" class="maintenance_history_staff_id"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $maintenance_history->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->staff_id) ?>',1);"><div id="elh_maintenance_history_staff_id" class="maintenance_history_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->staff_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->staff_name->Visible) { // staff_name ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->staff_name) == "") { ?>
		<th data-name="staff_name" class="<?php echo $maintenance_history->staff_name->HeaderCellClass() ?>"><div id="elh_maintenance_history_staff_name" class="maintenance_history_staff_name"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->staff_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_name" class="<?php echo $maintenance_history->staff_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->staff_name) ?>',1);"><div id="elh_maintenance_history_staff_name" class="maintenance_history_staff_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->staff_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->staff_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->staff_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->department->Visible) { // department ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->department) == "") { ?>
		<th data-name="department" class="<?php echo $maintenance_history->department->HeaderCellClass() ?>"><div id="elh_maintenance_history_department" class="maintenance_history_department"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $maintenance_history->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->department) ?>',1);"><div id="elh_maintenance_history_department" class="maintenance_history_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->branch->Visible) { // branch ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->branch) == "") { ?>
		<th data-name="branch" class="<?php echo $maintenance_history->branch->HeaderCellClass() ?>"><div id="elh_maintenance_history_branch" class="maintenance_history_branch"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->branch->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="branch" class="<?php echo $maintenance_history->branch->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->branch) ?>',1);"><div id="elh_maintenance_history_branch" class="maintenance_history_branch">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->branch->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->branch->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->branch->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->buildings->Visible) { // buildings ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->buildings) == "") { ?>
		<th data-name="buildings" class="<?php echo $maintenance_history->buildings->HeaderCellClass() ?>"><div id="elh_maintenance_history_buildings" class="maintenance_history_buildings"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->buildings->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="buildings" class="<?php echo $maintenance_history->buildings->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->buildings) ?>',1);"><div id="elh_maintenance_history_buildings" class="maintenance_history_buildings">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->buildings->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->buildings->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->buildings->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->floors->Visible) { // floors ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->floors) == "") { ?>
		<th data-name="floors" class="<?php echo $maintenance_history->floors->HeaderCellClass() ?>"><div id="elh_maintenance_history_floors" class="maintenance_history_floors"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->floors->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="floors" class="<?php echo $maintenance_history->floors->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->floors) ?>',1);"><div id="elh_maintenance_history_floors" class="maintenance_history_floors">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->floors->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->floors->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->floors->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->items->Visible) { // items ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->items) == "") { ?>
		<th data-name="items" class="<?php echo $maintenance_history->items->HeaderCellClass() ?>"><div id="elh_maintenance_history_items" class="maintenance_history_items"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->items->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="items" class="<?php echo $maintenance_history->items->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->items) ?>',1);"><div id="elh_maintenance_history_items" class="maintenance_history_items">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->items->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->items->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->items->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->descrption->Visible) { // descrption ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->descrption) == "") { ?>
		<th data-name="descrption" class="<?php echo $maintenance_history->descrption->HeaderCellClass() ?>"><div id="elh_maintenance_history_descrption" class="maintenance_history_descrption"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->descrption->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descrption" class="<?php echo $maintenance_history->descrption->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->descrption) ?>',1);"><div id="elh_maintenance_history_descrption" class="maintenance_history_descrption">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->descrption->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->descrption->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->descrption->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($maintenance_history->status->Visible) { // status ?>
	<?php if ($maintenance_history->SortUrl($maintenance_history->status) == "") { ?>
		<th data-name="status" class="<?php echo $maintenance_history->status->HeaderCellClass() ?>"><div id="elh_maintenance_history_status" class="maintenance_history_status"><div class="ewTableHeaderCaption"><?php echo $maintenance_history->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $maintenance_history->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $maintenance_history->SortUrl($maintenance_history->status) ?>',1);"><div id="elh_maintenance_history_status" class="maintenance_history_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $maintenance_history->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($maintenance_history->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($maintenance_history->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$maintenance_history_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($maintenance_history->ExportAll && $maintenance_history->Export <> "") {
	$maintenance_history_list->StopRec = $maintenance_history_list->TotalRecs;
} else {

	// Set the last record to display
	if ($maintenance_history_list->TotalRecs > $maintenance_history_list->StartRec + $maintenance_history_list->DisplayRecs - 1)
		$maintenance_history_list->StopRec = $maintenance_history_list->StartRec + $maintenance_history_list->DisplayRecs - 1;
	else
		$maintenance_history_list->StopRec = $maintenance_history_list->TotalRecs;
}
$maintenance_history_list->RecCnt = $maintenance_history_list->StartRec - 1;
if ($maintenance_history_list->Recordset && !$maintenance_history_list->Recordset->EOF) {
	$maintenance_history_list->Recordset->MoveFirst();
	$bSelectLimit = $maintenance_history_list->UseSelectLimit;
	if (!$bSelectLimit && $maintenance_history_list->StartRec > 1)
		$maintenance_history_list->Recordset->Move($maintenance_history_list->StartRec - 1);
} elseif (!$maintenance_history->AllowAddDeleteRow && $maintenance_history_list->StopRec == 0) {
	$maintenance_history_list->StopRec = $maintenance_history->GridAddRowCount;
}

// Initialize aggregate
$maintenance_history->RowType = EW_ROWTYPE_AGGREGATEINIT;
$maintenance_history->ResetAttrs();
$maintenance_history_list->RenderRow();
while ($maintenance_history_list->RecCnt < $maintenance_history_list->StopRec) {
	$maintenance_history_list->RecCnt++;
	if (intval($maintenance_history_list->RecCnt) >= intval($maintenance_history_list->StartRec)) {
		$maintenance_history_list->RowCnt++;

		// Set up key count
		$maintenance_history_list->KeyCount = $maintenance_history_list->RowIndex;

		// Init row class and style
		$maintenance_history->ResetAttrs();
		$maintenance_history->CssClass = "";
		if ($maintenance_history->CurrentAction == "gridadd") {
		} else {
			$maintenance_history_list->LoadRowValues($maintenance_history_list->Recordset); // Load row values
		}
		$maintenance_history->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$maintenance_history->RowAttrs = array_merge($maintenance_history->RowAttrs, array('data-rowindex'=>$maintenance_history_list->RowCnt, 'id'=>'r' . $maintenance_history_list->RowCnt . '_maintenance_history', 'data-rowtype'=>$maintenance_history->RowType));

		// Render row
		$maintenance_history_list->RenderRow();

		// Render list options
		$maintenance_history_list->RenderListOptions();
?>
	<tr<?php echo $maintenance_history->RowAttributes() ?>>
<?php

// Render list options (body, left)
$maintenance_history_list->ListOptions->Render("body", "left", $maintenance_history_list->RowCnt);
?>
	<?php if ($maintenance_history->date_initiated->Visible) { // date_initiated ?>
		<td data-name="date_initiated"<?php echo $maintenance_history->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_date_initiated" class="maintenance_history_date_initiated">
<span<?php echo $maintenance_history->date_initiated->ViewAttributes() ?>>
<?php echo $maintenance_history->date_initiated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->reference_id->Visible) { // reference_id ?>
		<td data-name="reference_id"<?php echo $maintenance_history->reference_id->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_reference_id" class="maintenance_history_reference_id">
<span<?php echo $maintenance_history->reference_id->ViewAttributes() ?>>
<?php echo $maintenance_history->reference_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $maintenance_history->staff_id->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_staff_id" class="maintenance_history_staff_id">
<span<?php echo $maintenance_history->staff_id->ViewAttributes() ?>>
<?php echo $maintenance_history->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->staff_name->Visible) { // staff_name ?>
		<td data-name="staff_name"<?php echo $maintenance_history->staff_name->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_staff_name" class="maintenance_history_staff_name">
<span<?php echo $maintenance_history->staff_name->ViewAttributes() ?>>
<?php echo $maintenance_history->staff_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->department->Visible) { // department ?>
		<td data-name="department"<?php echo $maintenance_history->department->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_department" class="maintenance_history_department">
<span<?php echo $maintenance_history->department->ViewAttributes() ?>>
<?php echo $maintenance_history->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->branch->Visible) { // branch ?>
		<td data-name="branch"<?php echo $maintenance_history->branch->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_branch" class="maintenance_history_branch">
<span<?php echo $maintenance_history->branch->ViewAttributes() ?>>
<?php echo $maintenance_history->branch->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->buildings->Visible) { // buildings ?>
		<td data-name="buildings"<?php echo $maintenance_history->buildings->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_buildings" class="maintenance_history_buildings">
<span<?php echo $maintenance_history->buildings->ViewAttributes() ?>>
<?php echo $maintenance_history->buildings->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->floors->Visible) { // floors ?>
		<td data-name="floors"<?php echo $maintenance_history->floors->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_floors" class="maintenance_history_floors">
<span<?php echo $maintenance_history->floors->ViewAttributes() ?>>
<?php echo $maintenance_history->floors->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->items->Visible) { // items ?>
		<td data-name="items"<?php echo $maintenance_history->items->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_items" class="maintenance_history_items">
<span<?php echo $maintenance_history->items->ViewAttributes() ?>>
<?php echo $maintenance_history->items->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->descrption->Visible) { // descrption ?>
		<td data-name="descrption"<?php echo $maintenance_history->descrption->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_descrption" class="maintenance_history_descrption">
<span<?php echo $maintenance_history->descrption->ViewAttributes() ?>>
<?php echo $maintenance_history->descrption->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($maintenance_history->status->Visible) { // status ?>
		<td data-name="status"<?php echo $maintenance_history->status->CellAttributes() ?>>
<span id="el<?php echo $maintenance_history_list->RowCnt ?>_maintenance_history_status" class="maintenance_history_status">
<span<?php echo $maintenance_history->status->ViewAttributes() ?>>
<?php echo $maintenance_history->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$maintenance_history_list->ListOptions->Render("body", "right", $maintenance_history_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($maintenance_history->CurrentAction <> "gridadd")
		$maintenance_history_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($maintenance_history->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($maintenance_history_list->Recordset)
	$maintenance_history_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($maintenance_history_list->TotalRecs == 0 && $maintenance_history->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($maintenance_history_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($maintenance_history->Export == "") { ?>
<script type="text/javascript">
fmaintenance_historylistsrch.FilterList = <?php echo $maintenance_history_list->GetFilterList() ?>;
fmaintenance_historylistsrch.Init();
fmaintenance_historylist.Init();
</script>
<?php } ?>
<?php
$maintenance_history_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($maintenance_history->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$maintenance_history_list->Page_Terminate();
?>
