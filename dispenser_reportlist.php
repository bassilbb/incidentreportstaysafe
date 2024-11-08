<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "dispenser_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$dispenser_report_list = NULL; // Initialize page object first

class cdispenser_report_list extends cdispenser_report {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'dispenser_report';

	// Page object name
	var $PageObjName = 'dispenser_report_list';

	// Grid form hidden field names
	var $FormName = 'fdispenser_reportlist';
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

		// Table object (dispenser_report)
		if (!isset($GLOBALS["dispenser_report"]) || get_class($GLOBALS["dispenser_report"]) == "cdispenser_report") {
			$GLOBALS["dispenser_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dispenser_report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "dispenser_reportadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "dispenser_reportdelete.php";
		$this->MultiUpdateUrl = "dispenser_reportupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dispenser_report');

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdispenser_reportlistsrch";

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
		$this->referrence_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->fullname->SetVisibility();
		$this->department->SetVisibility();
		$this->location->SetVisibility();
		$this->sub_location->SetVisibility();
		$this->venue->SetVisibility();
		$this->type->SetVisibility();
		$this->action_taken->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->status->SetVisibility();
		$this->initiated_by->SetVisibility();

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
		global $EW_EXPORT, $dispenser_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dispenser_report);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fdispenser_reportlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->date_initiated->AdvancedSearch->ToJson(), ","); // Field date_initiated
		$sFilterList = ew_Concat($sFilterList, $this->referrence_id->AdvancedSearch->ToJson(), ","); // Field referrence_id
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->fullname->AdvancedSearch->ToJson(), ","); // Field fullname
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->location->AdvancedSearch->ToJson(), ","); // Field location
		$sFilterList = ew_Concat($sFilterList, $this->sub_location->AdvancedSearch->ToJson(), ","); // Field sub_location
		$sFilterList = ew_Concat($sFilterList, $this->venue->AdvancedSearch->ToJson(), ","); // Field venue
		$sFilterList = ew_Concat($sFilterList, $this->type->AdvancedSearch->ToJson(), ","); // Field type
		$sFilterList = ew_Concat($sFilterList, $this->action_taken->AdvancedSearch->ToJson(), ","); // Field action_taken
		$sFilterList = ew_Concat($sFilterList, $this->initiator_action->AdvancedSearch->ToJson(), ","); // Field initiator_action
		$sFilterList = ew_Concat($sFilterList, $this->initiator_comment->AdvancedSearch->ToJson(), ","); // Field initiator_comment
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->initiated_by->AdvancedSearch->ToJson(), ","); // Field initiated_by
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdispenser_reportlistsrch", $filters);

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

		// Field referrence_id
		$this->referrence_id->AdvancedSearch->SearchValue = @$filter["x_referrence_id"];
		$this->referrence_id->AdvancedSearch->SearchOperator = @$filter["z_referrence_id"];
		$this->referrence_id->AdvancedSearch->SearchCondition = @$filter["v_referrence_id"];
		$this->referrence_id->AdvancedSearch->SearchValue2 = @$filter["y_referrence_id"];
		$this->referrence_id->AdvancedSearch->SearchOperator2 = @$filter["w_referrence_id"];
		$this->referrence_id->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

		// Field fullname
		$this->fullname->AdvancedSearch->SearchValue = @$filter["x_fullname"];
		$this->fullname->AdvancedSearch->SearchOperator = @$filter["z_fullname"];
		$this->fullname->AdvancedSearch->SearchCondition = @$filter["v_fullname"];
		$this->fullname->AdvancedSearch->SearchValue2 = @$filter["y_fullname"];
		$this->fullname->AdvancedSearch->SearchOperator2 = @$filter["w_fullname"];
		$this->fullname->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field location
		$this->location->AdvancedSearch->SearchValue = @$filter["x_location"];
		$this->location->AdvancedSearch->SearchOperator = @$filter["z_location"];
		$this->location->AdvancedSearch->SearchCondition = @$filter["v_location"];
		$this->location->AdvancedSearch->SearchValue2 = @$filter["y_location"];
		$this->location->AdvancedSearch->SearchOperator2 = @$filter["w_location"];
		$this->location->AdvancedSearch->Save();

		// Field sub_location
		$this->sub_location->AdvancedSearch->SearchValue = @$filter["x_sub_location"];
		$this->sub_location->AdvancedSearch->SearchOperator = @$filter["z_sub_location"];
		$this->sub_location->AdvancedSearch->SearchCondition = @$filter["v_sub_location"];
		$this->sub_location->AdvancedSearch->SearchValue2 = @$filter["y_sub_location"];
		$this->sub_location->AdvancedSearch->SearchOperator2 = @$filter["w_sub_location"];
		$this->sub_location->AdvancedSearch->Save();

		// Field venue
		$this->venue->AdvancedSearch->SearchValue = @$filter["x_venue"];
		$this->venue->AdvancedSearch->SearchOperator = @$filter["z_venue"];
		$this->venue->AdvancedSearch->SearchCondition = @$filter["v_venue"];
		$this->venue->AdvancedSearch->SearchValue2 = @$filter["y_venue"];
		$this->venue->AdvancedSearch->SearchOperator2 = @$filter["w_venue"];
		$this->venue->AdvancedSearch->Save();

		// Field type
		$this->type->AdvancedSearch->SearchValue = @$filter["x_type"];
		$this->type->AdvancedSearch->SearchOperator = @$filter["z_type"];
		$this->type->AdvancedSearch->SearchCondition = @$filter["v_type"];
		$this->type->AdvancedSearch->SearchValue2 = @$filter["y_type"];
		$this->type->AdvancedSearch->SearchOperator2 = @$filter["w_type"];
		$this->type->AdvancedSearch->Save();

		// Field action_taken
		$this->action_taken->AdvancedSearch->SearchValue = @$filter["x_action_taken"];
		$this->action_taken->AdvancedSearch->SearchOperator = @$filter["z_action_taken"];
		$this->action_taken->AdvancedSearch->SearchCondition = @$filter["v_action_taken"];
		$this->action_taken->AdvancedSearch->SearchValue2 = @$filter["y_action_taken"];
		$this->action_taken->AdvancedSearch->SearchOperator2 = @$filter["w_action_taken"];
		$this->action_taken->AdvancedSearch->Save();

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

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field initiated_by
		$this->initiated_by->AdvancedSearch->SearchValue = @$filter["x_initiated_by"];
		$this->initiated_by->AdvancedSearch->SearchOperator = @$filter["z_initiated_by"];
		$this->initiated_by->AdvancedSearch->SearchCondition = @$filter["v_initiated_by"];
		$this->initiated_by->AdvancedSearch->SearchValue2 = @$filter["y_initiated_by"];
		$this->initiated_by->AdvancedSearch->SearchOperator2 = @$filter["w_initiated_by"];
		$this->initiated_by->AdvancedSearch->Save();
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
		$this->BuildSearchSql($sWhere, $this->referrence_id, $Default, FALSE); // referrence_id
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->fullname, $Default, FALSE); // fullname
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->location, $Default, FALSE); // location
		$this->BuildSearchSql($sWhere, $this->sub_location, $Default, FALSE); // sub_location
		$this->BuildSearchSql($sWhere, $this->venue, $Default, FALSE); // venue
		$this->BuildSearchSql($sWhere, $this->type, $Default, FALSE); // type
		$this->BuildSearchSql($sWhere, $this->action_taken, $Default, FALSE); // action_taken
		$this->BuildSearchSql($sWhere, $this->initiator_action, $Default, FALSE); // initiator_action
		$this->BuildSearchSql($sWhere, $this->initiator_comment, $Default, FALSE); // initiator_comment
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->initiated_by, $Default, FALSE); // initiated_by

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->date_initiated->AdvancedSearch->Save(); // date_initiated
			$this->referrence_id->AdvancedSearch->Save(); // referrence_id
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->fullname->AdvancedSearch->Save(); // fullname
			$this->department->AdvancedSearch->Save(); // department
			$this->location->AdvancedSearch->Save(); // location
			$this->sub_location->AdvancedSearch->Save(); // sub_location
			$this->venue->AdvancedSearch->Save(); // venue
			$this->type->AdvancedSearch->Save(); // type
			$this->action_taken->AdvancedSearch->Save(); // action_taken
			$this->initiator_action->AdvancedSearch->Save(); // initiator_action
			$this->initiator_comment->AdvancedSearch->Save(); // initiator_comment
			$this->status->AdvancedSearch->Save(); // status
			$this->initiated_by->AdvancedSearch->Save(); // initiated_by
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
		$this->BuildBasicSearchSQL($sWhere, $this->referrence_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fullname, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->initiator_comment, $arKeywords, $type);
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
		if ($this->referrence_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fullname->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sub_location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->venue->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->action_taken->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiated_by->AdvancedSearch->IssetSession())
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
		$this->status->AdvancedSearch->LoadDefault();
		return TRUE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->date_initiated->AdvancedSearch->UnsetSession();
		$this->referrence_id->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->fullname->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->location->AdvancedSearch->UnsetSession();
		$this->sub_location->AdvancedSearch->UnsetSession();
		$this->venue->AdvancedSearch->UnsetSession();
		$this->type->AdvancedSearch->UnsetSession();
		$this->action_taken->AdvancedSearch->UnsetSession();
		$this->initiator_action->AdvancedSearch->UnsetSession();
		$this->initiator_comment->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->initiated_by->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->date_initiated->AdvancedSearch->Load();
		$this->referrence_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->fullname->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->location->AdvancedSearch->Load();
		$this->sub_location->AdvancedSearch->Load();
		$this->venue->AdvancedSearch->Load();
		$this->type->AdvancedSearch->Load();
		$this->action_taken->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiated_by->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->date_initiated); // date_initiated
			$this->UpdateSort($this->referrence_id); // referrence_id
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->fullname); // fullname
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->location); // location
			$this->UpdateSort($this->sub_location); // sub_location
			$this->UpdateSort($this->venue); // venue
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->action_taken); // action_taken
			$this->UpdateSort($this->initiator_action); // initiator_action
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->initiated_by); // initiated_by
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
				$this->referrence_id->setSort("");
				$this->staff_id->setSort("");
				$this->fullname->setSort("");
				$this->department->setSort("");
				$this->location->setSort("");
				$this->sub_location->setSort("");
				$this->venue->setSort("");
				$this->type->setSort("");
				$this->action_taken->setSort("");
				$this->initiator_action->setSort("");
				$this->status->setSort("");
				$this->initiated_by->setSort("");
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

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanDelete();
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"dispenser_report\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdispenser_reportlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdispenser_reportlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdispenser_reportlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdispenser_reportlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// referrence_id
		$this->referrence_id->AdvancedSearch->SearchValue = @$_GET["x_referrence_id"];
		if ($this->referrence_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->referrence_id->AdvancedSearch->SearchOperator = @$_GET["z_referrence_id"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// fullname
		$this->fullname->AdvancedSearch->SearchValue = @$_GET["x_fullname"];
		if ($this->fullname->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fullname->AdvancedSearch->SearchOperator = @$_GET["z_fullname"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// location
		$this->location->AdvancedSearch->SearchValue = @$_GET["x_location"];
		if ($this->location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->location->AdvancedSearch->SearchOperator = @$_GET["z_location"];

		// sub_location
		$this->sub_location->AdvancedSearch->SearchValue = @$_GET["x_sub_location"];
		if ($this->sub_location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sub_location->AdvancedSearch->SearchOperator = @$_GET["z_sub_location"];

		// venue
		$this->venue->AdvancedSearch->SearchValue = @$_GET["x_venue"];
		if ($this->venue->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->venue->AdvancedSearch->SearchOperator = @$_GET["z_venue"];

		// type
		$this->type->AdvancedSearch->SearchValue = @$_GET["x_type"];
		if ($this->type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->type->AdvancedSearch->SearchOperator = @$_GET["z_type"];

		// action_taken
		$this->action_taken->AdvancedSearch->SearchValue = @$_GET["x_action_taken"];
		if ($this->action_taken->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->action_taken->AdvancedSearch->SearchOperator = @$_GET["z_action_taken"];

		// initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = @$_GET["x_initiator_action"];
		if ($this->initiator_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiator_action->AdvancedSearch->SearchOperator = @$_GET["z_initiator_action"];

		// initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = @$_GET["x_initiator_comment"];
		if ($this->initiator_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiator_comment->AdvancedSearch->SearchOperator = @$_GET["z_initiator_comment"];

		// status
		$this->status->AdvancedSearch->SearchValue = @$_GET["x_status"];
		if ($this->status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// initiated_by
		$this->initiated_by->AdvancedSearch->SearchValue = @$_GET["x_initiated_by"];
		if ($this->initiated_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiated_by->AdvancedSearch->SearchOperator = @$_GET["z_initiated_by"];
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
		$this->referrence_id->setDbValue($row['referrence_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->fullname->setDbValue($row['fullname']);
		$this->department->setDbValue($row['department']);
		$this->location->setDbValue($row['location']);
		$this->sub_location->setDbValue($row['sub_location']);
		$this->venue->setDbValue($row['venue']);
		$this->type->setDbValue($row['type']);
		$this->action_taken->setDbValue($row['action_taken']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->status->setDbValue($row['status']);
		$this->initiated_by->setDbValue($row['initiated_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['referrence_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['fullname'] = NULL;
		$row['department'] = NULL;
		$row['location'] = NULL;
		$row['sub_location'] = NULL;
		$row['venue'] = NULL;
		$row['type'] = NULL;
		$row['action_taken'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['status'] = NULL;
		$row['initiated_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->referrence_id->DbValue = $row['referrence_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->fullname->DbValue = $row['fullname'];
		$this->department->DbValue = $row['department'];
		$this->location->DbValue = $row['location'];
		$this->sub_location->DbValue = $row['sub_location'];
		$this->venue->DbValue = $row['venue'];
		$this->type->DbValue = $row['type'];
		$this->action_taken->DbValue = $row['action_taken'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->status->DbValue = $row['status'];
		$this->initiated_by->DbValue = $row['initiated_by'];
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
		// referrence_id
		// staff_id
		// fullname
		// department
		// location
		// sub_location
		// venue
		// type
		// action_taken
		// initiator_action
		// initiator_comment
		// status
		// initiated_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 7);
		$this->date_initiated->ViewCustomAttributes = "";

		// referrence_id
		$this->referrence_id->ViewValue = $this->referrence_id->CurrentValue;
		$this->referrence_id->ViewCustomAttributes = "";

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

		// fullname
		if (strval($this->fullname->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->fullname->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->fullname->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->fullname->ViewValue = $this->fullname->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->fullname->ViewValue = $this->fullname->CurrentValue;
			}
		} else {
			$this->fullname->ViewValue = NULL;
		}
		$this->fullname->ViewCustomAttributes = "";

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

		// location
		if (strval($this->location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->location->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->location->ViewValue = $this->location->CurrentValue;
			}
		} else {
			$this->location->ViewValue = NULL;
		}
		$this->location->ViewCustomAttributes = "";

		// sub_location
		if (strval($this->sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_sub` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_location->ViewValue = $this->sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_location->ViewValue = $this->sub_location->CurrentValue;
			}
		} else {
			$this->sub_location->ViewValue = NULL;
		}
		$this->sub_location->ViewCustomAttributes = "";

		// venue
		if (strval($this->venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->venue->ViewValue = $this->venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->venue->ViewValue = $this->venue->CurrentValue;
			}
		} else {
			$this->venue->ViewValue = NULL;
		}
		$this->venue->ViewCustomAttributes = "";

		// type
		if (strval($this->type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_type`";
		$sWhereWrk = "";
		$this->type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->type->ViewValue = $this->type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->type->ViewValue = $this->type->CurrentValue;
			}
		} else {
			$this->type->ViewValue = NULL;
		}
		$this->type->ViewCustomAttributes = "";

		// action_taken
		$this->action_taken->ViewValue = $this->action_taken->CurrentValue;
		if (strval($this->action_taken->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->action_taken->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `action_taken`";
		$sWhereWrk = "";
		$this->action_taken->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->action_taken, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->action_taken->ViewValue = $this->action_taken->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->action_taken->ViewValue = $this->action_taken->CurrentValue;
			}
		} else {
			$this->action_taken->ViewValue = NULL;
		}
		$this->action_taken->ViewCustomAttributes = "";

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
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

		// initiated_by
		$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
		if (strval($this->initiated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->initiated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->initiated_by->ViewValue = $this->initiated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
			}
		} else {
			$this->initiated_by->ViewValue = NULL;
		}
		$this->initiated_by->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// referrence_id
			$this->referrence_id->LinkCustomAttributes = "";
			$this->referrence_id->HrefValue = "";
			$this->referrence_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// fullname
			$this->fullname->LinkCustomAttributes = "";
			$this->fullname->HrefValue = "";
			$this->fullname->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";
			$this->location->TooltipValue = "";

			// sub_location
			$this->sub_location->LinkCustomAttributes = "";
			$this->sub_location->HrefValue = "";
			$this->sub_location->TooltipValue = "";

			// venue
			$this->venue->LinkCustomAttributes = "";
			$this->venue->HrefValue = "";
			$this->venue->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// action_taken
			$this->action_taken->LinkCustomAttributes = "";
			$this->action_taken->HrefValue = "";
			$this->action_taken->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// initiated_by
			$this->initiated_by->LinkCustomAttributes = "";
			$this->initiated_by->HrefValue = "";
			$this->initiated_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue, 7), 7));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue2, 7), 7));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// referrence_id
			$this->referrence_id->EditAttrs["class"] = "form-control";
			$this->referrence_id->EditCustomAttributes = "";
			$this->referrence_id->EditValue = ew_HtmlEncode($this->referrence_id->AdvancedSearch->SearchValue);
			$this->referrence_id->PlaceHolder = ew_RemoveHtml($this->referrence_id->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// fullname
			$this->fullname->EditCustomAttributes = "";
			if (trim(strval($this->fullname->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->fullname->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->fullname->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->fullname->AdvancedSearch->ViewValue = $this->fullname->DisplayValue($arwrk);
			} else {
				$this->fullname->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->fullname->EditValue = $arwrk;

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";
			$this->department->EditValue = ew_HtmlEncode($this->department->AdvancedSearch->SearchValue);
			$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

			// location
			$this->location->EditCustomAttributes = "";
			if (trim(strval($this->location->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->location->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_location`";
			$sWhereWrk = "";
			$this->location->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->location->AdvancedSearch->ViewValue = $this->location->DisplayValue($arwrk);
			} else {
				$this->location->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->location->EditValue = $arwrk;

			// sub_location
			$this->sub_location->EditCustomAttributes = "";

			// venue
			$this->venue->EditAttrs["class"] = "form-control";
			$this->venue->EditCustomAttributes = "";

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			if (trim(strval($this->type->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dispenser_type`";
			$sWhereWrk = "";
			$this->type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->type->EditValue = $arwrk;

			// action_taken
			$this->action_taken->EditAttrs["class"] = "form-control";
			$this->action_taken->EditCustomAttributes = "";
			$this->action_taken->EditValue = ew_HtmlEncode($this->action_taken->AdvancedSearch->SearchValue);
			$this->action_taken->PlaceHolder = ew_RemoveHtml($this->action_taken->FldCaption());

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			if (strval($this->status->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
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

			// initiated_by
			$this->initiated_by->EditAttrs["class"] = "form-control";
			$this->initiated_by->EditCustomAttributes = "";
			$this->initiated_by->EditValue = ew_HtmlEncode($this->initiated_by->AdvancedSearch->SearchValue);
			$this->initiated_by->PlaceHolder = ew_RemoveHtml($this->initiated_by->FldCaption());
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
		if (!ew_CheckInteger($this->status->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->status->FldErrMsg());
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
		$this->referrence_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->fullname->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->location->AdvancedSearch->Load();
		$this->sub_location->AdvancedSearch->Load();
		$this->venue->AdvancedSearch->Load();
		$this->type->AdvancedSearch->Load();
		$this->action_taken->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiated_by->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_dispenser_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_dispenser_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdispenser_reportlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		case "x_fullname":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_location":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_type":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_type`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
				$sWhereWrk = "{filter}";
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
		case "x_status":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id`, `description` AS `DispFld` FROM `dispenser_status`";
				$sWhereWrk = "`description` LIKE '{query_value}%'";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($dispenser_report_list)) $dispenser_report_list = new cdispenser_report_list();

// Page init
$dispenser_report_list->Page_Init();

// Page main
$dispenser_report_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dispenser_report_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($dispenser_report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdispenser_reportlist = new ew_Form("fdispenser_reportlist", "list");
fdispenser_reportlist.FormKeyCountName = '<?php echo $dispenser_report_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdispenser_reportlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdispenser_reportlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdispenser_reportlist.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportlist.Lists["x_staff_id"].Data = "<?php echo $dispenser_report_list->staff_id->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_list->staff_id->LookupFilterQuery(TRUE, "list"))) ?>;
fdispenser_reportlist.Lists["x_fullname"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportlist.Lists["x_fullname"].Data = "<?php echo $dispenser_report_list->fullname->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fdispenser_reportlist.Lists["x_department"].Data = "<?php echo $dispenser_report_list->department->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_list->department->LookupFilterQuery(TRUE, "list"))) ?>;
fdispenser_reportlist.Lists["x_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
fdispenser_reportlist.Lists["x_location"].Data = "<?php echo $dispenser_report_list->location->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.Lists["x_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_venue"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
fdispenser_reportlist.Lists["x_sub_location"].Data = "<?php echo $dispenser_report_list->sub_location->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.Lists["x_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_venue"};
fdispenser_reportlist.Lists["x_venue"].Data = "<?php echo $dispenser_report_list->venue->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.Lists["x_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","x_serial_no","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_type"};
fdispenser_reportlist.Lists["x_type"].Data = "<?php echo $dispenser_report_list->type->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.Lists["x_action_taken"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"action_taken"};
fdispenser_reportlist.Lists["x_action_taken"].Data = "<?php echo $dispenser_report_list->action_taken->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.AutoSuggests["x_action_taken"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_list->action_taken->LookupFilterQuery(TRUE, "list"))) ?>;
fdispenser_reportlist.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdispenser_reportlist.Lists["x_initiator_action"].Options = <?php echo json_encode($dispenser_report_list->initiator_action->Options()) ?>;
fdispenser_reportlist.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_status"};
fdispenser_reportlist.Lists["x_status"].Data = "<?php echo $dispenser_report_list->status->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_list->status->LookupFilterQuery(TRUE, "list"))) ?>;
fdispenser_reportlist.Lists["x_initiated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportlist.Lists["x_initiated_by"].Data = "<?php echo $dispenser_report_list->initiated_by->LookupFilterQuery(FALSE, "list") ?>";
fdispenser_reportlist.AutoSuggests["x_initiated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_list->initiated_by->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = fdispenser_reportlistsrch = new ew_Form("fdispenser_reportlistsrch");

// Validate function for search
fdispenser_reportlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_status");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($dispenser_report->status->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fdispenser_reportlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdispenser_reportlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdispenser_reportlistsrch.Lists["x_fullname"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenser_reportlistsrch.Lists["x_fullname"].Data = "<?php echo $dispenser_report_list->fullname->LookupFilterQuery(FALSE, "extbs") ?>";
fdispenser_reportlistsrch.Lists["x_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
fdispenser_reportlistsrch.Lists["x_location"].Data = "<?php echo $dispenser_report_list->location->LookupFilterQuery(FALSE, "extbs") ?>";
fdispenser_reportlistsrch.Lists["x_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","x_serial_no","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_type"};
fdispenser_reportlistsrch.Lists["x_type"].Data = "<?php echo $dispenser_report_list->type->LookupFilterQuery(FALSE, "extbs") ?>";
fdispenser_reportlistsrch.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_status"};
fdispenser_reportlistsrch.Lists["x_status"].Data = "<?php echo $dispenser_report_list->status->LookupFilterQuery(FALSE, "extbs") ?>";
fdispenser_reportlistsrch.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_report_list->status->LookupFilterQuery(TRUE, "extbs"))) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($dispenser_report->Export == "") { ?>
<div class="ewToolbar">
<?php if ($dispenser_report_list->TotalRecs > 0 && $dispenser_report_list->ExportOptions->Visible()) { ?>
<?php $dispenser_report_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($dispenser_report_list->SearchOptions->Visible()) { ?>
<?php $dispenser_report_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($dispenser_report_list->FilterOptions->Visible()) { ?>
<?php $dispenser_report_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $dispenser_report_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($dispenser_report_list->TotalRecs <= 0)
			$dispenser_report_list->TotalRecs = $dispenser_report->ListRecordCount();
	} else {
		if (!$dispenser_report_list->Recordset && ($dispenser_report_list->Recordset = $dispenser_report_list->LoadRecordset()))
			$dispenser_report_list->TotalRecs = $dispenser_report_list->Recordset->RecordCount();
	}
	$dispenser_report_list->StartRec = 1;
	if ($dispenser_report_list->DisplayRecs <= 0 || ($dispenser_report->Export <> "" && $dispenser_report->ExportAll)) // Display all records
		$dispenser_report_list->DisplayRecs = $dispenser_report_list->TotalRecs;
	if (!($dispenser_report->Export <> "" && $dispenser_report->ExportAll))
		$dispenser_report_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$dispenser_report_list->Recordset = $dispenser_report_list->LoadRecordset($dispenser_report_list->StartRec-1, $dispenser_report_list->DisplayRecs);

	// Set no record found message
	if ($dispenser_report->CurrentAction == "" && $dispenser_report_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$dispenser_report_list->setWarningMessage(ew_DeniedMsg());
		if ($dispenser_report_list->SearchWhere == "0=101")
			$dispenser_report_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$dispenser_report_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$dispenser_report_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($dispenser_report->Export == "" && $dispenser_report->CurrentAction == "") { ?>
<form name="fdispenser_reportlistsrch" id="fdispenser_reportlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($dispenser_report_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdispenser_reportlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="dispenser_report">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$dispenser_report_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$dispenser_report->RowType = EW_ROWTYPE_SEARCH;

// Render row
$dispenser_report->ResetAttrs();
$dispenser_report_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($dispenser_report->date_initiated->Visible) { // date_initiated ?>
	<div id="xsc_date_initiated" class="ewCell form-group">
		<label for="x_date_initiated" class="ewSearchCaption ewLabel"><?php echo $dispenser_report->date_initiated->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date_initiated" id="z_date_initiated" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="dispenser_report" data-field="x_date_initiated" data-format="7" name="x_date_initiated" id="x_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser_report->date_initiated->getPlaceHolder()) ?>" value="<?php echo $dispenser_report->date_initiated->EditValue ?>"<?php echo $dispenser_report->date_initiated->EditAttributes() ?>>
<?php if (!$dispenser_report->date_initiated->ReadOnly && !$dispenser_report->date_initiated->Disabled && !isset($dispenser_report->date_initiated->EditAttrs["readonly"]) && !isset($dispenser_report->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdispenser_reportlistsrch", "x_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_date_initiated">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_date_initiated">
<input type="text" data-table="dispenser_report" data-field="x_date_initiated" data-format="7" name="y_date_initiated" id="y_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser_report->date_initiated->getPlaceHolder()) ?>" value="<?php echo $dispenser_report->date_initiated->EditValue2 ?>"<?php echo $dispenser_report->date_initiated->EditAttributes() ?>>
<?php if (!$dispenser_report->date_initiated->ReadOnly && !$dispenser_report->date_initiated->Disabled && !isset($dispenser_report->date_initiated->EditAttrs["readonly"]) && !isset($dispenser_report->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdispenser_reportlistsrch", "y_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($dispenser_report->fullname->Visible) { // fullname ?>
	<div id="xsc_fullname" class="ewCell form-group">
		<label for="x_fullname" class="ewSearchCaption ewLabel"><?php echo $dispenser_report->fullname->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fullname" id="z_fullname" value="LIKE"></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_fullname"><?php echo (strval($dispenser_report->fullname->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $dispenser_report->fullname->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($dispenser_report->fullname->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_fullname',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($dispenser_report->fullname->ReadOnly || $dispenser_report->fullname->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="dispenser_report" data-field="x_fullname" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $dispenser_report->fullname->DisplayValueSeparatorAttribute() ?>" name="x_fullname" id="x_fullname" value="<?php echo $dispenser_report->fullname->AdvancedSearch->SearchValue ?>"<?php echo $dispenser_report->fullname->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($dispenser_report->location->Visible) { // location ?>
	<div id="xsc_location" class="ewCell form-group">
		<label for="x_location" class="ewSearchCaption ewLabel"><?php echo $dispenser_report->location->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_location" id="z_location" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_location"><?php echo (strval($dispenser_report->location->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $dispenser_report->location->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($dispenser_report->location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($dispenser_report->location->ReadOnly || $dispenser_report->location->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="dispenser_report" data-field="x_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $dispenser_report->location->DisplayValueSeparatorAttribute() ?>" name="x_location" id="x_location" value="<?php echo $dispenser_report->location->AdvancedSearch->SearchValue ?>"<?php echo $dispenser_report->location->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($dispenser_report->type->Visible) { // type ?>
	<div id="xsc_type" class="ewCell form-group">
		<label for="x_type" class="ewSearchCaption ewLabel"><?php echo $dispenser_report->type->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_type" id="z_type" value="="></span>
		<span class="ewSearchField">
<select data-table="dispenser_report" data-field="x_type" data-value-separator="<?php echo $dispenser_report->type->DisplayValueSeparatorAttribute() ?>" id="x_type" name="x_type"<?php echo $dispenser_report->type->EditAttributes() ?>>
<?php echo $dispenser_report->type->SelectOptionListHtml("x_type") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($dispenser_report->status->Visible) { // status ?>
	<div id="xsc_status" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $dispenser_report->status->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></span>
		<span class="ewSearchField">
<?php
$wrkonchange = trim(" " . @$dispenser_report->status->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$dispenser_report->status->EditAttrs["onchange"] = "";
?>
<span id="as_x_status" style="white-space: nowrap; z-index: 8860">
	<input type="text" name="sv_x_status" id="sv_x_status" value="<?php echo $dispenser_report->status->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser_report->status->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($dispenser_report->status->getPlaceHolder()) ?>"<?php echo $dispenser_report->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="dispenser_report" data-field="x_status" data-value-separator="<?php echo $dispenser_report->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($dispenser_report->status->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdispenser_reportlistsrch.CreateAutoSuggest({"id":"x_status","forceSelect":false});
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($dispenser_report_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($dispenser_report_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $dispenser_report_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($dispenser_report_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($dispenser_report_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($dispenser_report_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($dispenser_report_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $dispenser_report_list->ShowPageHeader(); ?>
<?php
$dispenser_report_list->ShowMessage();
?>
<?php if ($dispenser_report_list->TotalRecs > 0 || $dispenser_report->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($dispenser_report_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> dispenser_report">
<?php if ($dispenser_report->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($dispenser_report->CurrentAction <> "gridadd" && $dispenser_report->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($dispenser_report_list->Pager)) $dispenser_report_list->Pager = new cPrevNextPager($dispenser_report_list->StartRec, $dispenser_report_list->DisplayRecs, $dispenser_report_list->TotalRecs, $dispenser_report_list->AutoHidePager) ?>
<?php if ($dispenser_report_list->Pager->RecordCount > 0 && $dispenser_report_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($dispenser_report_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $dispenser_report_list->PageUrl() ?>start=<?php echo $dispenser_report_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($dispenser_report_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $dispenser_report_list->PageUrl() ?>start=<?php echo $dispenser_report_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $dispenser_report_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($dispenser_report_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $dispenser_report_list->PageUrl() ?>start=<?php echo $dispenser_report_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($dispenser_report_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $dispenser_report_list->PageUrl() ?>start=<?php echo $dispenser_report_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $dispenser_report_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($dispenser_report_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $dispenser_report_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $dispenser_report_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $dispenser_report_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($dispenser_report_list->TotalRecs > 0 && (!$dispenser_report_list->AutoHidePageSizeSelector || $dispenser_report_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="dispenser_report">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($dispenser_report_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($dispenser_report_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($dispenser_report_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($dispenser_report_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($dispenser_report_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($dispenser_report->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($dispenser_report_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fdispenser_reportlist" id="fdispenser_reportlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dispenser_report_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dispenser_report_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dispenser_report">
<div id="gmp_dispenser_report" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($dispenser_report_list->TotalRecs > 0 || $dispenser_report->CurrentAction == "gridedit") { ?>
<table id="tbl_dispenser_reportlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$dispenser_report_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$dispenser_report_list->RenderListOptions();

// Render list options (header, left)
$dispenser_report_list->ListOptions->Render("header", "left");
?>
<?php if ($dispenser_report->date_initiated->Visible) { // date_initiated ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->date_initiated) == "") { ?>
		<th data-name="date_initiated" class="<?php echo $dispenser_report->date_initiated->HeaderCellClass() ?>"><div id="elh_dispenser_report_date_initiated" class="dispenser_report_date_initiated"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->date_initiated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_initiated" class="<?php echo $dispenser_report->date_initiated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->date_initiated) ?>',1);"><div id="elh_dispenser_report_date_initiated" class="dispenser_report_date_initiated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->date_initiated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->date_initiated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->date_initiated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->referrence_id->Visible) { // referrence_id ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->referrence_id) == "") { ?>
		<th data-name="referrence_id" class="<?php echo $dispenser_report->referrence_id->HeaderCellClass() ?>"><div id="elh_dispenser_report_referrence_id" class="dispenser_report_referrence_id"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->referrence_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referrence_id" class="<?php echo $dispenser_report->referrence_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->referrence_id) ?>',1);"><div id="elh_dispenser_report_referrence_id" class="dispenser_report_referrence_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->referrence_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->referrence_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->referrence_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->staff_id->Visible) { // staff_id ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $dispenser_report->staff_id->HeaderCellClass() ?>"><div id="elh_dispenser_report_staff_id" class="dispenser_report_staff_id"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $dispenser_report->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->staff_id) ?>',1);"><div id="elh_dispenser_report_staff_id" class="dispenser_report_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->staff_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->fullname->Visible) { // fullname ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->fullname) == "") { ?>
		<th data-name="fullname" class="<?php echo $dispenser_report->fullname->HeaderCellClass() ?>"><div id="elh_dispenser_report_fullname" class="dispenser_report_fullname"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->fullname->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fullname" class="<?php echo $dispenser_report->fullname->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->fullname) ?>',1);"><div id="elh_dispenser_report_fullname" class="dispenser_report_fullname">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->fullname->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->fullname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->fullname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->department->Visible) { // department ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->department) == "") { ?>
		<th data-name="department" class="<?php echo $dispenser_report->department->HeaderCellClass() ?>"><div id="elh_dispenser_report_department" class="dispenser_report_department"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $dispenser_report->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->department) ?>',1);"><div id="elh_dispenser_report_department" class="dispenser_report_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->location->Visible) { // location ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->location) == "") { ?>
		<th data-name="location" class="<?php echo $dispenser_report->location->HeaderCellClass() ?>"><div id="elh_dispenser_report_location" class="dispenser_report_location"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="location" class="<?php echo $dispenser_report->location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->location) ?>',1);"><div id="elh_dispenser_report_location" class="dispenser_report_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->location->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->sub_location->Visible) { // sub_location ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->sub_location) == "") { ?>
		<th data-name="sub_location" class="<?php echo $dispenser_report->sub_location->HeaderCellClass() ?>"><div id="elh_dispenser_report_sub_location" class="dispenser_report_sub_location"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->sub_location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_location" class="<?php echo $dispenser_report->sub_location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->sub_location) ?>',1);"><div id="elh_dispenser_report_sub_location" class="dispenser_report_sub_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->sub_location->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->sub_location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->sub_location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->venue->Visible) { // venue ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->venue) == "") { ?>
		<th data-name="venue" class="<?php echo $dispenser_report->venue->HeaderCellClass() ?>"><div id="elh_dispenser_report_venue" class="dispenser_report_venue"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->venue->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="venue" class="<?php echo $dispenser_report->venue->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->venue) ?>',1);"><div id="elh_dispenser_report_venue" class="dispenser_report_venue">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->venue->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->venue->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->venue->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->type->Visible) { // type ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->type) == "") { ?>
		<th data-name="type" class="<?php echo $dispenser_report->type->HeaderCellClass() ?>"><div id="elh_dispenser_report_type" class="dispenser_report_type"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="type" class="<?php echo $dispenser_report->type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->type) ?>',1);"><div id="elh_dispenser_report_type" class="dispenser_report_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->action_taken->Visible) { // action_taken ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->action_taken) == "") { ?>
		<th data-name="action_taken" class="<?php echo $dispenser_report->action_taken->HeaderCellClass() ?>"><div id="elh_dispenser_report_action_taken" class="dispenser_report_action_taken"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->action_taken->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="action_taken" class="<?php echo $dispenser_report->action_taken->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->action_taken) ?>',1);"><div id="elh_dispenser_report_action_taken" class="dispenser_report_action_taken">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->action_taken->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->action_taken->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->action_taken->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->initiator_action->Visible) { // initiator_action ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->initiator_action) == "") { ?>
		<th data-name="initiator_action" class="<?php echo $dispenser_report->initiator_action->HeaderCellClass() ?>"><div id="elh_dispenser_report_initiator_action" class="dispenser_report_initiator_action"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->initiator_action->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="initiator_action" class="<?php echo $dispenser_report->initiator_action->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->initiator_action) ?>',1);"><div id="elh_dispenser_report_initiator_action" class="dispenser_report_initiator_action">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->initiator_action->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->initiator_action->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->initiator_action->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->status->Visible) { // status ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->status) == "") { ?>
		<th data-name="status" class="<?php echo $dispenser_report->status->HeaderCellClass() ?>"><div id="elh_dispenser_report_status" class="dispenser_report_status"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $dispenser_report->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->status) ?>',1);"><div id="elh_dispenser_report_status" class="dispenser_report_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($dispenser_report->initiated_by->Visible) { // initiated_by ?>
	<?php if ($dispenser_report->SortUrl($dispenser_report->initiated_by) == "") { ?>
		<th data-name="initiated_by" class="<?php echo $dispenser_report->initiated_by->HeaderCellClass() ?>"><div id="elh_dispenser_report_initiated_by" class="dispenser_report_initiated_by"><div class="ewTableHeaderCaption"><?php echo $dispenser_report->initiated_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="initiated_by" class="<?php echo $dispenser_report->initiated_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $dispenser_report->SortUrl($dispenser_report->initiated_by) ?>',1);"><div id="elh_dispenser_report_initiated_by" class="dispenser_report_initiated_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $dispenser_report->initiated_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($dispenser_report->initiated_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($dispenser_report->initiated_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$dispenser_report_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($dispenser_report->ExportAll && $dispenser_report->Export <> "") {
	$dispenser_report_list->StopRec = $dispenser_report_list->TotalRecs;
} else {

	// Set the last record to display
	if ($dispenser_report_list->TotalRecs > $dispenser_report_list->StartRec + $dispenser_report_list->DisplayRecs - 1)
		$dispenser_report_list->StopRec = $dispenser_report_list->StartRec + $dispenser_report_list->DisplayRecs - 1;
	else
		$dispenser_report_list->StopRec = $dispenser_report_list->TotalRecs;
}
$dispenser_report_list->RecCnt = $dispenser_report_list->StartRec - 1;
if ($dispenser_report_list->Recordset && !$dispenser_report_list->Recordset->EOF) {
	$dispenser_report_list->Recordset->MoveFirst();
	$bSelectLimit = $dispenser_report_list->UseSelectLimit;
	if (!$bSelectLimit && $dispenser_report_list->StartRec > 1)
		$dispenser_report_list->Recordset->Move($dispenser_report_list->StartRec - 1);
} elseif (!$dispenser_report->AllowAddDeleteRow && $dispenser_report_list->StopRec == 0) {
	$dispenser_report_list->StopRec = $dispenser_report->GridAddRowCount;
}

// Initialize aggregate
$dispenser_report->RowType = EW_ROWTYPE_AGGREGATEINIT;
$dispenser_report->ResetAttrs();
$dispenser_report_list->RenderRow();
while ($dispenser_report_list->RecCnt < $dispenser_report_list->StopRec) {
	$dispenser_report_list->RecCnt++;
	if (intval($dispenser_report_list->RecCnt) >= intval($dispenser_report_list->StartRec)) {
		$dispenser_report_list->RowCnt++;

		// Set up key count
		$dispenser_report_list->KeyCount = $dispenser_report_list->RowIndex;

		// Init row class and style
		$dispenser_report->ResetAttrs();
		$dispenser_report->CssClass = "";
		if ($dispenser_report->CurrentAction == "gridadd") {
		} else {
			$dispenser_report_list->LoadRowValues($dispenser_report_list->Recordset); // Load row values
		}
		$dispenser_report->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$dispenser_report->RowAttrs = array_merge($dispenser_report->RowAttrs, array('data-rowindex'=>$dispenser_report_list->RowCnt, 'id'=>'r' . $dispenser_report_list->RowCnt . '_dispenser_report', 'data-rowtype'=>$dispenser_report->RowType));

		// Render row
		$dispenser_report_list->RenderRow();

		// Render list options
		$dispenser_report_list->RenderListOptions();
?>
	<tr<?php echo $dispenser_report->RowAttributes() ?>>
<?php

// Render list options (body, left)
$dispenser_report_list->ListOptions->Render("body", "left", $dispenser_report_list->RowCnt);
?>
	<?php if ($dispenser_report->date_initiated->Visible) { // date_initiated ?>
		<td data-name="date_initiated"<?php echo $dispenser_report->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_date_initiated" class="dispenser_report_date_initiated">
<span<?php echo $dispenser_report->date_initiated->ViewAttributes() ?>>
<?php echo $dispenser_report->date_initiated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->referrence_id->Visible) { // referrence_id ?>
		<td data-name="referrence_id"<?php echo $dispenser_report->referrence_id->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_referrence_id" class="dispenser_report_referrence_id">
<span<?php echo $dispenser_report->referrence_id->ViewAttributes() ?>>
<?php echo $dispenser_report->referrence_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $dispenser_report->staff_id->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_staff_id" class="dispenser_report_staff_id">
<span<?php echo $dispenser_report->staff_id->ViewAttributes() ?>>
<?php echo $dispenser_report->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->fullname->Visible) { // fullname ?>
		<td data-name="fullname"<?php echo $dispenser_report->fullname->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_fullname" class="dispenser_report_fullname">
<span<?php echo $dispenser_report->fullname->ViewAttributes() ?>>
<?php echo $dispenser_report->fullname->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->department->Visible) { // department ?>
		<td data-name="department"<?php echo $dispenser_report->department->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_department" class="dispenser_report_department">
<span<?php echo $dispenser_report->department->ViewAttributes() ?>>
<?php echo $dispenser_report->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->location->Visible) { // location ?>
		<td data-name="location"<?php echo $dispenser_report->location->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_location" class="dispenser_report_location">
<span<?php echo $dispenser_report->location->ViewAttributes() ?>>
<?php echo $dispenser_report->location->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->sub_location->Visible) { // sub_location ?>
		<td data-name="sub_location"<?php echo $dispenser_report->sub_location->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_sub_location" class="dispenser_report_sub_location">
<span<?php echo $dispenser_report->sub_location->ViewAttributes() ?>>
<?php echo $dispenser_report->sub_location->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->venue->Visible) { // venue ?>
		<td data-name="venue"<?php echo $dispenser_report->venue->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_venue" class="dispenser_report_venue">
<span<?php echo $dispenser_report->venue->ViewAttributes() ?>>
<?php echo $dispenser_report->venue->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->type->Visible) { // type ?>
		<td data-name="type"<?php echo $dispenser_report->type->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_type" class="dispenser_report_type">
<span<?php echo $dispenser_report->type->ViewAttributes() ?>>
<?php echo $dispenser_report->type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->action_taken->Visible) { // action_taken ?>
		<td data-name="action_taken"<?php echo $dispenser_report->action_taken->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_action_taken" class="dispenser_report_action_taken">
<span<?php echo $dispenser_report->action_taken->ViewAttributes() ?>>
<?php echo $dispenser_report->action_taken->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->initiator_action->Visible) { // initiator_action ?>
		<td data-name="initiator_action"<?php echo $dispenser_report->initiator_action->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_initiator_action" class="dispenser_report_initiator_action">
<span<?php echo $dispenser_report->initiator_action->ViewAttributes() ?>>
<?php echo $dispenser_report->initiator_action->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->status->Visible) { // status ?>
		<td data-name="status"<?php echo $dispenser_report->status->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_status" class="dispenser_report_status">
<span<?php echo $dispenser_report->status->ViewAttributes() ?>>
<?php echo $dispenser_report->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($dispenser_report->initiated_by->Visible) { // initiated_by ?>
		<td data-name="initiated_by"<?php echo $dispenser_report->initiated_by->CellAttributes() ?>>
<span id="el<?php echo $dispenser_report_list->RowCnt ?>_dispenser_report_initiated_by" class="dispenser_report_initiated_by">
<span<?php echo $dispenser_report->initiated_by->ViewAttributes() ?>>
<?php echo $dispenser_report->initiated_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$dispenser_report_list->ListOptions->Render("body", "right", $dispenser_report_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($dispenser_report->CurrentAction <> "gridadd")
		$dispenser_report_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($dispenser_report->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($dispenser_report_list->Recordset)
	$dispenser_report_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($dispenser_report_list->TotalRecs == 0 && $dispenser_report->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($dispenser_report_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($dispenser_report->Export == "") { ?>
<script type="text/javascript">
fdispenser_reportlistsrch.FilterList = <?php echo $dispenser_report_list->GetFilterList() ?>;
fdispenser_reportlistsrch.Init();
fdispenser_reportlist.Init();
</script>
<?php } ?>
<?php
$dispenser_report_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($dispenser_report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$dispenser_report_list->Page_Terminate();
?>
