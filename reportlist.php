<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$report_list = NULL; // Initialize page object first

class creport_list extends creport {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'report';

	// Page object name
	var $PageObjName = 'report_list';

	// Grid form hidden field names
	var $FormName = 'freportlist';
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

		// Table object (report)
		if (!isset($GLOBALS["report"]) || get_class($GLOBALS["report"]) == "creport") {
			$GLOBALS["report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "reportadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "reportdelete.php";
		$this->MultiUpdateUrl = "reportupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'report', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption freportlistsrch";

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
		$this->datetime_initiated->SetVisibility();
		$this->incident_id->SetVisibility();
		$this->staffid->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->branch->SetVisibility();
		$this->departments->SetVisibility();
		$this->category->SetVisibility();
		$this->sub_category->SetVisibility();
		$this->incident_location->SetVisibility();
		$this->status->SetVisibility();
		$this->assign_task->SetVisibility();
		$this->reason->SetVisibility();
		$this->last_updated_date->SetVisibility();
		$this->last_updated_by->SetVisibility();
		$this->job_assessment->SetVisibility();

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
		global $EW_EXPORT, $report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($report);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "freportlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->datetime_initiated->AdvancedSearch->ToJson(), ","); // Field datetime_initiated
		$sFilterList = ew_Concat($sFilterList, $this->incident_id->AdvancedSearch->ToJson(), ","); // Field incident_id
		$sFilterList = ew_Concat($sFilterList, $this->staffid->AdvancedSearch->ToJson(), ","); // Field staffid
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->branch->AdvancedSearch->ToJson(), ","); // Field branch
		$sFilterList = ew_Concat($sFilterList, $this->departments->AdvancedSearch->ToJson(), ","); // Field departments
		$sFilterList = ew_Concat($sFilterList, $this->category->AdvancedSearch->ToJson(), ","); // Field category
		$sFilterList = ew_Concat($sFilterList, $this->sub_category->AdvancedSearch->ToJson(), ","); // Field sub_category
		$sFilterList = ew_Concat($sFilterList, $this->sub_sub_category->AdvancedSearch->ToJson(), ","); // Field sub_sub_category
		$sFilterList = ew_Concat($sFilterList, $this->start_date->AdvancedSearch->ToJson(), ","); // Field start_date
		$sFilterList = ew_Concat($sFilterList, $this->end_date->AdvancedSearch->ToJson(), ","); // Field end_date
		$sFilterList = ew_Concat($sFilterList, $this->duration->AdvancedSearch->ToJson(), ","); // Field duration
		$sFilterList = ew_Concat($sFilterList, $this->amount_paid->AdvancedSearch->ToJson(), ","); // Field amount_paid
		$sFilterList = ew_Concat($sFilterList, $this->no_of_people_involved->AdvancedSearch->ToJson(), ","); // Field no_of_people_involved
		$sFilterList = ew_Concat($sFilterList, $this->incident_type->AdvancedSearch->ToJson(), ","); // Field incident_type
		$sFilterList = ew_Concat($sFilterList, $this->incident_category->AdvancedSearch->ToJson(), ","); // Field incident-category
		$sFilterList = ew_Concat($sFilterList, $this->incident_location->AdvancedSearch->ToJson(), ","); // Field incident_location
		$sFilterList = ew_Concat($sFilterList, $this->incident_sub_location->AdvancedSearch->ToJson(), ","); // Field incident_sub_location
		$sFilterList = ew_Concat($sFilterList, $this->incident_venue->AdvancedSearch->ToJson(), ","); // Field incident_venue
		$sFilterList = ew_Concat($sFilterList, $this->incident_description->AdvancedSearch->ToJson(), ","); // Field incident_description
		$sFilterList = ew_Concat($sFilterList, $this->_upload->AdvancedSearch->ToJson(), ","); // Field upload
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->initiator_action->AdvancedSearch->ToJson(), ","); // Field initiator_action
		$sFilterList = ew_Concat($sFilterList, $this->initiator_comment->AdvancedSearch->ToJson(), ","); // Field initiator_comment
		$sFilterList = ew_Concat($sFilterList, $this->report_by->AdvancedSearch->ToJson(), ","); // Field report_by
		$sFilterList = ew_Concat($sFilterList, $this->datetime_resolved->AdvancedSearch->ToJson(), ","); // Field datetime_resolved
		$sFilterList = ew_Concat($sFilterList, $this->assign_task->AdvancedSearch->ToJson(), ","); // Field assign_task
		$sFilterList = ew_Concat($sFilterList, $this->approval_action->AdvancedSearch->ToJson(), ","); // Field approval_action
		$sFilterList = ew_Concat($sFilterList, $this->approval_comment->AdvancedSearch->ToJson(), ","); // Field approval_comment
		$sFilterList = ew_Concat($sFilterList, $this->reason->AdvancedSearch->ToJson(), ","); // Field reason
		$sFilterList = ew_Concat($sFilterList, $this->resolved_action->AdvancedSearch->ToJson(), ","); // Field resolved_action
		$sFilterList = ew_Concat($sFilterList, $this->resolved_comment->AdvancedSearch->ToJson(), ","); // Field resolved_comment
		$sFilterList = ew_Concat($sFilterList, $this->resolved_by->AdvancedSearch->ToJson(), ","); // Field resolved_by
		$sFilterList = ew_Concat($sFilterList, $this->datetime_approved->AdvancedSearch->ToJson(), ","); // Field datetime_approved
		$sFilterList = ew_Concat($sFilterList, $this->approved_by->AdvancedSearch->ToJson(), ","); // Field approved_by
		$sFilterList = ew_Concat($sFilterList, $this->verified_by->AdvancedSearch->ToJson(), ","); // Field verified_by
		$sFilterList = ew_Concat($sFilterList, $this->last_updated_date->AdvancedSearch->ToJson(), ","); // Field last_updated_date
		$sFilterList = ew_Concat($sFilterList, $this->last_updated_by->AdvancedSearch->ToJson(), ","); // Field last_updated_by
		$sFilterList = ew_Concat($sFilterList, $this->selection_sub_category->AdvancedSearch->ToJson(), ","); // Field selection_sub_category
		$sFilterList = ew_Concat($sFilterList, $this->verified_datetime->AdvancedSearch->ToJson(), ","); // Field verified_datetime
		$sFilterList = ew_Concat($sFilterList, $this->verified_action->AdvancedSearch->ToJson(), ","); // Field verified_action
		$sFilterList = ew_Concat($sFilterList, $this->verified_comment->AdvancedSearch->ToJson(), ","); // Field verified_comment
		$sFilterList = ew_Concat($sFilterList, $this->job_assessment->AdvancedSearch->ToJson(), ","); // Field job_assessment
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "freportlistsrch", $filters);

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

		// Field datetime_initiated
		$this->datetime_initiated->AdvancedSearch->SearchValue = @$filter["x_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchOperator = @$filter["z_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchCondition = @$filter["v_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchValue2 = @$filter["y_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchOperator2 = @$filter["w_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->Save();

		// Field incident_id
		$this->incident_id->AdvancedSearch->SearchValue = @$filter["x_incident_id"];
		$this->incident_id->AdvancedSearch->SearchOperator = @$filter["z_incident_id"];
		$this->incident_id->AdvancedSearch->SearchCondition = @$filter["v_incident_id"];
		$this->incident_id->AdvancedSearch->SearchValue2 = @$filter["y_incident_id"];
		$this->incident_id->AdvancedSearch->SearchOperator2 = @$filter["w_incident_id"];
		$this->incident_id->AdvancedSearch->Save();

		// Field staffid
		$this->staffid->AdvancedSearch->SearchValue = @$filter["x_staffid"];
		$this->staffid->AdvancedSearch->SearchOperator = @$filter["z_staffid"];
		$this->staffid->AdvancedSearch->SearchCondition = @$filter["v_staffid"];
		$this->staffid->AdvancedSearch->SearchValue2 = @$filter["y_staffid"];
		$this->staffid->AdvancedSearch->SearchOperator2 = @$filter["w_staffid"];
		$this->staffid->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

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

		// Field departments
		$this->departments->AdvancedSearch->SearchValue = @$filter["x_departments"];
		$this->departments->AdvancedSearch->SearchOperator = @$filter["z_departments"];
		$this->departments->AdvancedSearch->SearchCondition = @$filter["v_departments"];
		$this->departments->AdvancedSearch->SearchValue2 = @$filter["y_departments"];
		$this->departments->AdvancedSearch->SearchOperator2 = @$filter["w_departments"];
		$this->departments->AdvancedSearch->Save();

		// Field category
		$this->category->AdvancedSearch->SearchValue = @$filter["x_category"];
		$this->category->AdvancedSearch->SearchOperator = @$filter["z_category"];
		$this->category->AdvancedSearch->SearchCondition = @$filter["v_category"];
		$this->category->AdvancedSearch->SearchValue2 = @$filter["y_category"];
		$this->category->AdvancedSearch->SearchOperator2 = @$filter["w_category"];
		$this->category->AdvancedSearch->Save();

		// Field sub_category
		$this->sub_category->AdvancedSearch->SearchValue = @$filter["x_sub_category"];
		$this->sub_category->AdvancedSearch->SearchOperator = @$filter["z_sub_category"];
		$this->sub_category->AdvancedSearch->SearchCondition = @$filter["v_sub_category"];
		$this->sub_category->AdvancedSearch->SearchValue2 = @$filter["y_sub_category"];
		$this->sub_category->AdvancedSearch->SearchOperator2 = @$filter["w_sub_category"];
		$this->sub_category->AdvancedSearch->Save();

		// Field sub_sub_category
		$this->sub_sub_category->AdvancedSearch->SearchValue = @$filter["x_sub_sub_category"];
		$this->sub_sub_category->AdvancedSearch->SearchOperator = @$filter["z_sub_sub_category"];
		$this->sub_sub_category->AdvancedSearch->SearchCondition = @$filter["v_sub_sub_category"];
		$this->sub_sub_category->AdvancedSearch->SearchValue2 = @$filter["y_sub_sub_category"];
		$this->sub_sub_category->AdvancedSearch->SearchOperator2 = @$filter["w_sub_sub_category"];
		$this->sub_sub_category->AdvancedSearch->Save();

		// Field start_date
		$this->start_date->AdvancedSearch->SearchValue = @$filter["x_start_date"];
		$this->start_date->AdvancedSearch->SearchOperator = @$filter["z_start_date"];
		$this->start_date->AdvancedSearch->SearchCondition = @$filter["v_start_date"];
		$this->start_date->AdvancedSearch->SearchValue2 = @$filter["y_start_date"];
		$this->start_date->AdvancedSearch->SearchOperator2 = @$filter["w_start_date"];
		$this->start_date->AdvancedSearch->Save();

		// Field end_date
		$this->end_date->AdvancedSearch->SearchValue = @$filter["x_end_date"];
		$this->end_date->AdvancedSearch->SearchOperator = @$filter["z_end_date"];
		$this->end_date->AdvancedSearch->SearchCondition = @$filter["v_end_date"];
		$this->end_date->AdvancedSearch->SearchValue2 = @$filter["y_end_date"];
		$this->end_date->AdvancedSearch->SearchOperator2 = @$filter["w_end_date"];
		$this->end_date->AdvancedSearch->Save();

		// Field duration
		$this->duration->AdvancedSearch->SearchValue = @$filter["x_duration"];
		$this->duration->AdvancedSearch->SearchOperator = @$filter["z_duration"];
		$this->duration->AdvancedSearch->SearchCondition = @$filter["v_duration"];
		$this->duration->AdvancedSearch->SearchValue2 = @$filter["y_duration"];
		$this->duration->AdvancedSearch->SearchOperator2 = @$filter["w_duration"];
		$this->duration->AdvancedSearch->Save();

		// Field amount_paid
		$this->amount_paid->AdvancedSearch->SearchValue = @$filter["x_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchOperator = @$filter["z_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchCondition = @$filter["v_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchValue2 = @$filter["y_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchOperator2 = @$filter["w_amount_paid"];
		$this->amount_paid->AdvancedSearch->Save();

		// Field no_of_people_involved
		$this->no_of_people_involved->AdvancedSearch->SearchValue = @$filter["x_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchOperator = @$filter["z_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchCondition = @$filter["v_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchValue2 = @$filter["y_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchOperator2 = @$filter["w_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->Save();

		// Field incident_type
		$this->incident_type->AdvancedSearch->SearchValue = @$filter["x_incident_type"];
		$this->incident_type->AdvancedSearch->SearchOperator = @$filter["z_incident_type"];
		$this->incident_type->AdvancedSearch->SearchCondition = @$filter["v_incident_type"];
		$this->incident_type->AdvancedSearch->SearchValue2 = @$filter["y_incident_type"];
		$this->incident_type->AdvancedSearch->SearchOperator2 = @$filter["w_incident_type"];
		$this->incident_type->AdvancedSearch->Save();

		// Field incident-category
		$this->incident_category->AdvancedSearch->SearchValue = @$filter["x_incident_category"];
		$this->incident_category->AdvancedSearch->SearchOperator = @$filter["z_incident_category"];
		$this->incident_category->AdvancedSearch->SearchCondition = @$filter["v_incident_category"];
		$this->incident_category->AdvancedSearch->SearchValue2 = @$filter["y_incident_category"];
		$this->incident_category->AdvancedSearch->SearchOperator2 = @$filter["w_incident_category"];
		$this->incident_category->AdvancedSearch->Save();

		// Field incident_location
		$this->incident_location->AdvancedSearch->SearchValue = @$filter["x_incident_location"];
		$this->incident_location->AdvancedSearch->SearchOperator = @$filter["z_incident_location"];
		$this->incident_location->AdvancedSearch->SearchCondition = @$filter["v_incident_location"];
		$this->incident_location->AdvancedSearch->SearchValue2 = @$filter["y_incident_location"];
		$this->incident_location->AdvancedSearch->SearchOperator2 = @$filter["w_incident_location"];
		$this->incident_location->AdvancedSearch->Save();

		// Field incident_sub_location
		$this->incident_sub_location->AdvancedSearch->SearchValue = @$filter["x_incident_sub_location"];
		$this->incident_sub_location->AdvancedSearch->SearchOperator = @$filter["z_incident_sub_location"];
		$this->incident_sub_location->AdvancedSearch->SearchCondition = @$filter["v_incident_sub_location"];
		$this->incident_sub_location->AdvancedSearch->SearchValue2 = @$filter["y_incident_sub_location"];
		$this->incident_sub_location->AdvancedSearch->SearchOperator2 = @$filter["w_incident_sub_location"];
		$this->incident_sub_location->AdvancedSearch->Save();

		// Field incident_venue
		$this->incident_venue->AdvancedSearch->SearchValue = @$filter["x_incident_venue"];
		$this->incident_venue->AdvancedSearch->SearchOperator = @$filter["z_incident_venue"];
		$this->incident_venue->AdvancedSearch->SearchCondition = @$filter["v_incident_venue"];
		$this->incident_venue->AdvancedSearch->SearchValue2 = @$filter["y_incident_venue"];
		$this->incident_venue->AdvancedSearch->SearchOperator2 = @$filter["w_incident_venue"];
		$this->incident_venue->AdvancedSearch->Save();

		// Field incident_description
		$this->incident_description->AdvancedSearch->SearchValue = @$filter["x_incident_description"];
		$this->incident_description->AdvancedSearch->SearchOperator = @$filter["z_incident_description"];
		$this->incident_description->AdvancedSearch->SearchCondition = @$filter["v_incident_description"];
		$this->incident_description->AdvancedSearch->SearchValue2 = @$filter["y_incident_description"];
		$this->incident_description->AdvancedSearch->SearchOperator2 = @$filter["w_incident_description"];
		$this->incident_description->AdvancedSearch->Save();

		// Field upload
		$this->_upload->AdvancedSearch->SearchValue = @$filter["x__upload"];
		$this->_upload->AdvancedSearch->SearchOperator = @$filter["z__upload"];
		$this->_upload->AdvancedSearch->SearchCondition = @$filter["v__upload"];
		$this->_upload->AdvancedSearch->SearchValue2 = @$filter["y__upload"];
		$this->_upload->AdvancedSearch->SearchOperator2 = @$filter["w__upload"];
		$this->_upload->AdvancedSearch->Save();

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

		// Field report_by
		$this->report_by->AdvancedSearch->SearchValue = @$filter["x_report_by"];
		$this->report_by->AdvancedSearch->SearchOperator = @$filter["z_report_by"];
		$this->report_by->AdvancedSearch->SearchCondition = @$filter["v_report_by"];
		$this->report_by->AdvancedSearch->SearchValue2 = @$filter["y_report_by"];
		$this->report_by->AdvancedSearch->SearchOperator2 = @$filter["w_report_by"];
		$this->report_by->AdvancedSearch->Save();

		// Field datetime_resolved
		$this->datetime_resolved->AdvancedSearch->SearchValue = @$filter["x_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchOperator = @$filter["z_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchCondition = @$filter["v_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchValue2 = @$filter["y_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchOperator2 = @$filter["w_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->Save();

		// Field assign_task
		$this->assign_task->AdvancedSearch->SearchValue = @$filter["x_assign_task"];
		$this->assign_task->AdvancedSearch->SearchOperator = @$filter["z_assign_task"];
		$this->assign_task->AdvancedSearch->SearchCondition = @$filter["v_assign_task"];
		$this->assign_task->AdvancedSearch->SearchValue2 = @$filter["y_assign_task"];
		$this->assign_task->AdvancedSearch->SearchOperator2 = @$filter["w_assign_task"];
		$this->assign_task->AdvancedSearch->Save();

		// Field approval_action
		$this->approval_action->AdvancedSearch->SearchValue = @$filter["x_approval_action"];
		$this->approval_action->AdvancedSearch->SearchOperator = @$filter["z_approval_action"];
		$this->approval_action->AdvancedSearch->SearchCondition = @$filter["v_approval_action"];
		$this->approval_action->AdvancedSearch->SearchValue2 = @$filter["y_approval_action"];
		$this->approval_action->AdvancedSearch->SearchOperator2 = @$filter["w_approval_action"];
		$this->approval_action->AdvancedSearch->Save();

		// Field approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = @$filter["x_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchOperator = @$filter["z_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchCondition = @$filter["v_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchValue2 = @$filter["y_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchOperator2 = @$filter["w_approval_comment"];
		$this->approval_comment->AdvancedSearch->Save();

		// Field reason
		$this->reason->AdvancedSearch->SearchValue = @$filter["x_reason"];
		$this->reason->AdvancedSearch->SearchOperator = @$filter["z_reason"];
		$this->reason->AdvancedSearch->SearchCondition = @$filter["v_reason"];
		$this->reason->AdvancedSearch->SearchValue2 = @$filter["y_reason"];
		$this->reason->AdvancedSearch->SearchOperator2 = @$filter["w_reason"];
		$this->reason->AdvancedSearch->Save();

		// Field resolved_action
		$this->resolved_action->AdvancedSearch->SearchValue = @$filter["x_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchOperator = @$filter["z_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchCondition = @$filter["v_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchValue2 = @$filter["y_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchOperator2 = @$filter["w_resolved_action"];
		$this->resolved_action->AdvancedSearch->Save();

		// Field resolved_comment
		$this->resolved_comment->AdvancedSearch->SearchValue = @$filter["x_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchOperator = @$filter["z_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchCondition = @$filter["v_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchValue2 = @$filter["y_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchOperator2 = @$filter["w_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->Save();

		// Field resolved_by
		$this->resolved_by->AdvancedSearch->SearchValue = @$filter["x_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchOperator = @$filter["z_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchCondition = @$filter["v_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchValue2 = @$filter["y_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchOperator2 = @$filter["w_resolved_by"];
		$this->resolved_by->AdvancedSearch->Save();

		// Field datetime_approved
		$this->datetime_approved->AdvancedSearch->SearchValue = @$filter["x_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchOperator = @$filter["z_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchCondition = @$filter["v_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchValue2 = @$filter["y_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchOperator2 = @$filter["w_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->Save();

		// Field approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$filter["x_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator = @$filter["z_approved_by"];
		$this->approved_by->AdvancedSearch->SearchCondition = @$filter["v_approved_by"];
		$this->approved_by->AdvancedSearch->SearchValue2 = @$filter["y_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator2 = @$filter["w_approved_by"];
		$this->approved_by->AdvancedSearch->Save();

		// Field verified_by
		$this->verified_by->AdvancedSearch->SearchValue = @$filter["x_verified_by"];
		$this->verified_by->AdvancedSearch->SearchOperator = @$filter["z_verified_by"];
		$this->verified_by->AdvancedSearch->SearchCondition = @$filter["v_verified_by"];
		$this->verified_by->AdvancedSearch->SearchValue2 = @$filter["y_verified_by"];
		$this->verified_by->AdvancedSearch->SearchOperator2 = @$filter["w_verified_by"];
		$this->verified_by->AdvancedSearch->Save();

		// Field last_updated_date
		$this->last_updated_date->AdvancedSearch->SearchValue = @$filter["x_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchOperator = @$filter["z_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchCondition = @$filter["v_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchValue2 = @$filter["y_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchOperator2 = @$filter["w_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->Save();

		// Field last_updated_by
		$this->last_updated_by->AdvancedSearch->SearchValue = @$filter["x_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchOperator = @$filter["z_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchCondition = @$filter["v_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchValue2 = @$filter["y_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchOperator2 = @$filter["w_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->Save();

		// Field selection_sub_category
		$this->selection_sub_category->AdvancedSearch->SearchValue = @$filter["x_selection_sub_category"];
		$this->selection_sub_category->AdvancedSearch->SearchOperator = @$filter["z_selection_sub_category"];
		$this->selection_sub_category->AdvancedSearch->SearchCondition = @$filter["v_selection_sub_category"];
		$this->selection_sub_category->AdvancedSearch->SearchValue2 = @$filter["y_selection_sub_category"];
		$this->selection_sub_category->AdvancedSearch->SearchOperator2 = @$filter["w_selection_sub_category"];
		$this->selection_sub_category->AdvancedSearch->Save();

		// Field verified_datetime
		$this->verified_datetime->AdvancedSearch->SearchValue = @$filter["x_verified_datetime"];
		$this->verified_datetime->AdvancedSearch->SearchOperator = @$filter["z_verified_datetime"];
		$this->verified_datetime->AdvancedSearch->SearchCondition = @$filter["v_verified_datetime"];
		$this->verified_datetime->AdvancedSearch->SearchValue2 = @$filter["y_verified_datetime"];
		$this->verified_datetime->AdvancedSearch->SearchOperator2 = @$filter["w_verified_datetime"];
		$this->verified_datetime->AdvancedSearch->Save();

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

		// Field job_assessment
		$this->job_assessment->AdvancedSearch->SearchValue = @$filter["x_job_assessment"];
		$this->job_assessment->AdvancedSearch->SearchOperator = @$filter["z_job_assessment"];
		$this->job_assessment->AdvancedSearch->SearchCondition = @$filter["v_job_assessment"];
		$this->job_assessment->AdvancedSearch->SearchValue2 = @$filter["y_job_assessment"];
		$this->job_assessment->AdvancedSearch->SearchOperator2 = @$filter["w_job_assessment"];
		$this->job_assessment->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->datetime_initiated, $Default, FALSE); // datetime_initiated
		$this->BuildSearchSql($sWhere, $this->incident_id, $Default, FALSE); // incident_id
		$this->BuildSearchSql($sWhere, $this->staffid, $Default, FALSE); // staffid
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->branch, $Default, FALSE); // branch
		$this->BuildSearchSql($sWhere, $this->departments, $Default, FALSE); // departments
		$this->BuildSearchSql($sWhere, $this->category, $Default, FALSE); // category
		$this->BuildSearchSql($sWhere, $this->sub_category, $Default, FALSE); // sub_category
		$this->BuildSearchSql($sWhere, $this->sub_sub_category, $Default, TRUE); // sub_sub_category
		$this->BuildSearchSql($sWhere, $this->start_date, $Default, FALSE); // start_date
		$this->BuildSearchSql($sWhere, $this->end_date, $Default, FALSE); // end_date
		$this->BuildSearchSql($sWhere, $this->duration, $Default, FALSE); // duration
		$this->BuildSearchSql($sWhere, $this->amount_paid, $Default, FALSE); // amount_paid
		$this->BuildSearchSql($sWhere, $this->no_of_people_involved, $Default, FALSE); // no_of_people_involved
		$this->BuildSearchSql($sWhere, $this->incident_type, $Default, FALSE); // incident_type
		$this->BuildSearchSql($sWhere, $this->incident_category, $Default, FALSE); // incident-category
		$this->BuildSearchSql($sWhere, $this->incident_location, $Default, FALSE); // incident_location
		$this->BuildSearchSql($sWhere, $this->incident_sub_location, $Default, FALSE); // incident_sub_location
		$this->BuildSearchSql($sWhere, $this->incident_venue, $Default, FALSE); // incident_venue
		$this->BuildSearchSql($sWhere, $this->incident_description, $Default, FALSE); // incident_description
		$this->BuildSearchSql($sWhere, $this->_upload, $Default, FALSE); // upload
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->initiator_action, $Default, FALSE); // initiator_action
		$this->BuildSearchSql($sWhere, $this->initiator_comment, $Default, FALSE); // initiator_comment
		$this->BuildSearchSql($sWhere, $this->report_by, $Default, FALSE); // report_by
		$this->BuildSearchSql($sWhere, $this->datetime_resolved, $Default, FALSE); // datetime_resolved
		$this->BuildSearchSql($sWhere, $this->assign_task, $Default, FALSE); // assign_task
		$this->BuildSearchSql($sWhere, $this->approval_action, $Default, FALSE); // approval_action
		$this->BuildSearchSql($sWhere, $this->approval_comment, $Default, FALSE); // approval_comment
		$this->BuildSearchSql($sWhere, $this->reason, $Default, FALSE); // reason
		$this->BuildSearchSql($sWhere, $this->resolved_action, $Default, FALSE); // resolved_action
		$this->BuildSearchSql($sWhere, $this->resolved_comment, $Default, FALSE); // resolved_comment
		$this->BuildSearchSql($sWhere, $this->resolved_by, $Default, FALSE); // resolved_by
		$this->BuildSearchSql($sWhere, $this->datetime_approved, $Default, FALSE); // datetime_approved
		$this->BuildSearchSql($sWhere, $this->approved_by, $Default, FALSE); // approved_by
		$this->BuildSearchSql($sWhere, $this->verified_by, $Default, FALSE); // verified_by
		$this->BuildSearchSql($sWhere, $this->last_updated_date, $Default, FALSE); // last_updated_date
		$this->BuildSearchSql($sWhere, $this->last_updated_by, $Default, FALSE); // last_updated_by
		$this->BuildSearchSql($sWhere, $this->selection_sub_category, $Default, FALSE); // selection_sub_category
		$this->BuildSearchSql($sWhere, $this->verified_datetime, $Default, FALSE); // verified_datetime
		$this->BuildSearchSql($sWhere, $this->verified_action, $Default, FALSE); // verified_action
		$this->BuildSearchSql($sWhere, $this->verified_comment, $Default, FALSE); // verified_comment
		$this->BuildSearchSql($sWhere, $this->job_assessment, $Default, FALSE); // job_assessment

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->datetime_initiated->AdvancedSearch->Save(); // datetime_initiated
			$this->incident_id->AdvancedSearch->Save(); // incident_id
			$this->staffid->AdvancedSearch->Save(); // staffid
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->department->AdvancedSearch->Save(); // department
			$this->branch->AdvancedSearch->Save(); // branch
			$this->departments->AdvancedSearch->Save(); // departments
			$this->category->AdvancedSearch->Save(); // category
			$this->sub_category->AdvancedSearch->Save(); // sub_category
			$this->sub_sub_category->AdvancedSearch->Save(); // sub_sub_category
			$this->start_date->AdvancedSearch->Save(); // start_date
			$this->end_date->AdvancedSearch->Save(); // end_date
			$this->duration->AdvancedSearch->Save(); // duration
			$this->amount_paid->AdvancedSearch->Save(); // amount_paid
			$this->no_of_people_involved->AdvancedSearch->Save(); // no_of_people_involved
			$this->incident_type->AdvancedSearch->Save(); // incident_type
			$this->incident_category->AdvancedSearch->Save(); // incident-category
			$this->incident_location->AdvancedSearch->Save(); // incident_location
			$this->incident_sub_location->AdvancedSearch->Save(); // incident_sub_location
			$this->incident_venue->AdvancedSearch->Save(); // incident_venue
			$this->incident_description->AdvancedSearch->Save(); // incident_description
			$this->_upload->AdvancedSearch->Save(); // upload
			$this->status->AdvancedSearch->Save(); // status
			$this->initiator_action->AdvancedSearch->Save(); // initiator_action
			$this->initiator_comment->AdvancedSearch->Save(); // initiator_comment
			$this->report_by->AdvancedSearch->Save(); // report_by
			$this->datetime_resolved->AdvancedSearch->Save(); // datetime_resolved
			$this->assign_task->AdvancedSearch->Save(); // assign_task
			$this->approval_action->AdvancedSearch->Save(); // approval_action
			$this->approval_comment->AdvancedSearch->Save(); // approval_comment
			$this->reason->AdvancedSearch->Save(); // reason
			$this->resolved_action->AdvancedSearch->Save(); // resolved_action
			$this->resolved_comment->AdvancedSearch->Save(); // resolved_comment
			$this->resolved_by->AdvancedSearch->Save(); // resolved_by
			$this->datetime_approved->AdvancedSearch->Save(); // datetime_approved
			$this->approved_by->AdvancedSearch->Save(); // approved_by
			$this->verified_by->AdvancedSearch->Save(); // verified_by
			$this->last_updated_date->AdvancedSearch->Save(); // last_updated_date
			$this->last_updated_by->AdvancedSearch->Save(); // last_updated_by
			$this->selection_sub_category->AdvancedSearch->Save(); // selection_sub_category
			$this->verified_datetime->AdvancedSearch->Save(); // verified_datetime
			$this->verified_action->AdvancedSearch->Save(); // verified_action
			$this->verified_comment->AdvancedSearch->Save(); // verified_comment
			$this->job_assessment->AdvancedSearch->Save(); // job_assessment
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
		$this->BuildBasicSearchSQL($sWhere, $this->incident_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staffid, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->incident_description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_upload, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->initiator_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approval_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->resolved_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->selection_sub_category, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->verified_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->job_assessment, $arKeywords, $type);
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
		if ($this->datetime_initiated->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staffid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->branch->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->departments->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sub_category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sub_sub_category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->start_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->end_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->duration->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->amount_paid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_of_people_involved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_sub_location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_venue->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_description->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_upload->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->report_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->datetime_resolved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->assign_task->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reason->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resolved_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resolved_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resolved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->datetime_approved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->last_updated_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->last_updated_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->selection_sub_category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_datetime->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->job_assessment->AdvancedSearch->IssetSession())
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
		$this->datetime_initiated->AdvancedSearch->UnsetSession();
		$this->incident_id->AdvancedSearch->UnsetSession();
		$this->staffid->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->branch->AdvancedSearch->UnsetSession();
		$this->departments->AdvancedSearch->UnsetSession();
		$this->category->AdvancedSearch->UnsetSession();
		$this->sub_category->AdvancedSearch->UnsetSession();
		$this->sub_sub_category->AdvancedSearch->UnsetSession();
		$this->start_date->AdvancedSearch->UnsetSession();
		$this->end_date->AdvancedSearch->UnsetSession();
		$this->duration->AdvancedSearch->UnsetSession();
		$this->amount_paid->AdvancedSearch->UnsetSession();
		$this->no_of_people_involved->AdvancedSearch->UnsetSession();
		$this->incident_type->AdvancedSearch->UnsetSession();
		$this->incident_category->AdvancedSearch->UnsetSession();
		$this->incident_location->AdvancedSearch->UnsetSession();
		$this->incident_sub_location->AdvancedSearch->UnsetSession();
		$this->incident_venue->AdvancedSearch->UnsetSession();
		$this->incident_description->AdvancedSearch->UnsetSession();
		$this->_upload->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->initiator_action->AdvancedSearch->UnsetSession();
		$this->initiator_comment->AdvancedSearch->UnsetSession();
		$this->report_by->AdvancedSearch->UnsetSession();
		$this->datetime_resolved->AdvancedSearch->UnsetSession();
		$this->assign_task->AdvancedSearch->UnsetSession();
		$this->approval_action->AdvancedSearch->UnsetSession();
		$this->approval_comment->AdvancedSearch->UnsetSession();
		$this->reason->AdvancedSearch->UnsetSession();
		$this->resolved_action->AdvancedSearch->UnsetSession();
		$this->resolved_comment->AdvancedSearch->UnsetSession();
		$this->resolved_by->AdvancedSearch->UnsetSession();
		$this->datetime_approved->AdvancedSearch->UnsetSession();
		$this->approved_by->AdvancedSearch->UnsetSession();
		$this->verified_by->AdvancedSearch->UnsetSession();
		$this->last_updated_date->AdvancedSearch->UnsetSession();
		$this->last_updated_by->AdvancedSearch->UnsetSession();
		$this->selection_sub_category->AdvancedSearch->UnsetSession();
		$this->verified_datetime->AdvancedSearch->UnsetSession();
		$this->verified_action->AdvancedSearch->UnsetSession();
		$this->verified_comment->AdvancedSearch->UnsetSession();
		$this->job_assessment->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->datetime_initiated->AdvancedSearch->Load();
		$this->incident_id->AdvancedSearch->Load();
		$this->staffid->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->branch->AdvancedSearch->Load();
		$this->departments->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->sub_category->AdvancedSearch->Load();
		$this->sub_sub_category->AdvancedSearch->Load();
		$this->start_date->AdvancedSearch->Load();
		$this->end_date->AdvancedSearch->Load();
		$this->duration->AdvancedSearch->Load();
		$this->amount_paid->AdvancedSearch->Load();
		$this->no_of_people_involved->AdvancedSearch->Load();
		$this->incident_type->AdvancedSearch->Load();
		$this->incident_category->AdvancedSearch->Load();
		$this->incident_location->AdvancedSearch->Load();
		$this->incident_sub_location->AdvancedSearch->Load();
		$this->incident_venue->AdvancedSearch->Load();
		$this->incident_description->AdvancedSearch->Load();
		$this->_upload->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->report_by->AdvancedSearch->Load();
		$this->datetime_resolved->AdvancedSearch->Load();
		$this->assign_task->AdvancedSearch->Load();
		$this->approval_action->AdvancedSearch->Load();
		$this->approval_comment->AdvancedSearch->Load();
		$this->reason->AdvancedSearch->Load();
		$this->resolved_action->AdvancedSearch->Load();
		$this->resolved_comment->AdvancedSearch->Load();
		$this->resolved_by->AdvancedSearch->Load();
		$this->datetime_approved->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->verified_by->AdvancedSearch->Load();
		$this->last_updated_date->AdvancedSearch->Load();
		$this->last_updated_by->AdvancedSearch->Load();
		$this->selection_sub_category->AdvancedSearch->Load();
		$this->verified_datetime->AdvancedSearch->Load();
		$this->verified_action->AdvancedSearch->Load();
		$this->verified_comment->AdvancedSearch->Load();
		$this->job_assessment->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->datetime_initiated); // datetime_initiated
			$this->UpdateSort($this->incident_id); // incident_id
			$this->UpdateSort($this->staffid); // staffid
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->branch); // branch
			$this->UpdateSort($this->departments); // departments
			$this->UpdateSort($this->category); // category
			$this->UpdateSort($this->sub_category); // sub_category
			$this->UpdateSort($this->incident_location); // incident_location
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->assign_task); // assign_task
			$this->UpdateSort($this->reason); // reason
			$this->UpdateSort($this->last_updated_date); // last_updated_date
			$this->UpdateSort($this->last_updated_by); // last_updated_by
			$this->UpdateSort($this->job_assessment); // job_assessment
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
				$this->datetime_initiated->setSort("");
				$this->incident_id->setSort("");
				$this->staffid->setSort("");
				$this->staff_id->setSort("");
				$this->branch->setSort("");
				$this->departments->setSort("");
				$this->category->setSort("");
				$this->sub_category->setSort("");
				$this->incident_location->setSort("");
				$this->status->setSort("");
				$this->assign_task->setSort("");
				$this->reason->setSort("");
				$this->last_updated_date->setSort("");
				$this->last_updated_by->setSort("");
				$this->job_assessment->setSort("");
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

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"report\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"freportlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"freportlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.freportlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"freportlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// datetime_initiated
		$this->datetime_initiated->AdvancedSearch->SearchValue = @$_GET["x_datetime_initiated"];
		if ($this->datetime_initiated->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_initiated->AdvancedSearch->SearchOperator = @$_GET["z_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchCondition = @$_GET["v_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchValue2 = @$_GET["y_datetime_initiated"];
		if ($this->datetime_initiated->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_initiated->AdvancedSearch->SearchOperator2 = @$_GET["w_datetime_initiated"];

		// incident_id
		$this->incident_id->AdvancedSearch->SearchValue = @$_GET["x_incident_id"];
		if ($this->incident_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_id->AdvancedSearch->SearchOperator = @$_GET["z_incident_id"];

		// staffid
		$this->staffid->AdvancedSearch->SearchValue = @$_GET["x_staffid"];
		if ($this->staffid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staffid->AdvancedSearch->SearchOperator = @$_GET["z_staffid"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// branch
		$this->branch->AdvancedSearch->SearchValue = @$_GET["x_branch"];
		if ($this->branch->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->branch->AdvancedSearch->SearchOperator = @$_GET["z_branch"];

		// departments
		$this->departments->AdvancedSearch->SearchValue = @$_GET["x_departments"];
		if ($this->departments->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->departments->AdvancedSearch->SearchOperator = @$_GET["z_departments"];

		// category
		$this->category->AdvancedSearch->SearchValue = @$_GET["x_category"];
		if ($this->category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->category->AdvancedSearch->SearchOperator = @$_GET["z_category"];

		// sub_category
		$this->sub_category->AdvancedSearch->SearchValue = @$_GET["x_sub_category"];
		if ($this->sub_category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sub_category->AdvancedSearch->SearchOperator = @$_GET["z_sub_category"];

		// sub_sub_category
		$this->sub_sub_category->AdvancedSearch->SearchValue = @$_GET["x_sub_sub_category"];
		if ($this->sub_sub_category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sub_sub_category->AdvancedSearch->SearchOperator = @$_GET["z_sub_sub_category"];
		if (is_array($this->sub_sub_category->AdvancedSearch->SearchValue)) $this->sub_sub_category->AdvancedSearch->SearchValue = implode(",", $this->sub_sub_category->AdvancedSearch->SearchValue);
		if (is_array($this->sub_sub_category->AdvancedSearch->SearchValue2)) $this->sub_sub_category->AdvancedSearch->SearchValue2 = implode(",", $this->sub_sub_category->AdvancedSearch->SearchValue2);

		// start_date
		$this->start_date->AdvancedSearch->SearchValue = @$_GET["x_start_date"];
		if ($this->start_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->start_date->AdvancedSearch->SearchOperator = @$_GET["z_start_date"];

		// end_date
		$this->end_date->AdvancedSearch->SearchValue = @$_GET["x_end_date"];
		if ($this->end_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->end_date->AdvancedSearch->SearchOperator = @$_GET["z_end_date"];

		// duration
		$this->duration->AdvancedSearch->SearchValue = @$_GET["x_duration"];
		if ($this->duration->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->duration->AdvancedSearch->SearchOperator = @$_GET["z_duration"];

		// amount_paid
		$this->amount_paid->AdvancedSearch->SearchValue = @$_GET["x_amount_paid"];
		if ($this->amount_paid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->amount_paid->AdvancedSearch->SearchOperator = @$_GET["z_amount_paid"];

		// no_of_people_involved
		$this->no_of_people_involved->AdvancedSearch->SearchValue = @$_GET["x_no_of_people_involved"];
		if ($this->no_of_people_involved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->no_of_people_involved->AdvancedSearch->SearchOperator = @$_GET["z_no_of_people_involved"];

		// incident_type
		$this->incident_type->AdvancedSearch->SearchValue = @$_GET["x_incident_type"];
		if ($this->incident_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_type->AdvancedSearch->SearchOperator = @$_GET["z_incident_type"];

		// incident-category
		$this->incident_category->AdvancedSearch->SearchValue = @$_GET["x_incident_category"];
		if ($this->incident_category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_category->AdvancedSearch->SearchOperator = @$_GET["z_incident_category"];

		// incident_location
		$this->incident_location->AdvancedSearch->SearchValue = @$_GET["x_incident_location"];
		if ($this->incident_location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_location->AdvancedSearch->SearchOperator = @$_GET["z_incident_location"];

		// incident_sub_location
		$this->incident_sub_location->AdvancedSearch->SearchValue = @$_GET["x_incident_sub_location"];
		if ($this->incident_sub_location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_sub_location->AdvancedSearch->SearchOperator = @$_GET["z_incident_sub_location"];

		// incident_venue
		$this->incident_venue->AdvancedSearch->SearchValue = @$_GET["x_incident_venue"];
		if ($this->incident_venue->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_venue->AdvancedSearch->SearchOperator = @$_GET["z_incident_venue"];

		// incident_description
		$this->incident_description->AdvancedSearch->SearchValue = @$_GET["x_incident_description"];
		if ($this->incident_description->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_description->AdvancedSearch->SearchOperator = @$_GET["z_incident_description"];

		// upload
		$this->_upload->AdvancedSearch->SearchValue = @$_GET["x__upload"];
		if ($this->_upload->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->_upload->AdvancedSearch->SearchOperator = @$_GET["z__upload"];

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

		// report_by
		$this->report_by->AdvancedSearch->SearchValue = @$_GET["x_report_by"];
		if ($this->report_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->report_by->AdvancedSearch->SearchOperator = @$_GET["z_report_by"];

		// datetime_resolved
		$this->datetime_resolved->AdvancedSearch->SearchValue = @$_GET["x_datetime_resolved"];
		if ($this->datetime_resolved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_resolved->AdvancedSearch->SearchOperator = @$_GET["z_datetime_resolved"];

		// assign_task
		$this->assign_task->AdvancedSearch->SearchValue = @$_GET["x_assign_task"];
		if ($this->assign_task->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->assign_task->AdvancedSearch->SearchOperator = @$_GET["z_assign_task"];

		// approval_action
		$this->approval_action->AdvancedSearch->SearchValue = @$_GET["x_approval_action"];
		if ($this->approval_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_action->AdvancedSearch->SearchOperator = @$_GET["z_approval_action"];

		// approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = @$_GET["x_approval_comment"];
		if ($this->approval_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_comment->AdvancedSearch->SearchOperator = @$_GET["z_approval_comment"];

		// reason
		$this->reason->AdvancedSearch->SearchValue = @$_GET["x_reason"];
		if ($this->reason->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reason->AdvancedSearch->SearchOperator = @$_GET["z_reason"];

		// resolved_action
		$this->resolved_action->AdvancedSearch->SearchValue = @$_GET["x_resolved_action"];
		if ($this->resolved_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resolved_action->AdvancedSearch->SearchOperator = @$_GET["z_resolved_action"];

		// resolved_comment
		$this->resolved_comment->AdvancedSearch->SearchValue = @$_GET["x_resolved_comment"];
		if ($this->resolved_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resolved_comment->AdvancedSearch->SearchOperator = @$_GET["z_resolved_comment"];

		// resolved_by
		$this->resolved_by->AdvancedSearch->SearchValue = @$_GET["x_resolved_by"];
		if ($this->resolved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resolved_by->AdvancedSearch->SearchOperator = @$_GET["z_resolved_by"];

		// datetime_approved
		$this->datetime_approved->AdvancedSearch->SearchValue = @$_GET["x_datetime_approved"];
		if ($this->datetime_approved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_approved->AdvancedSearch->SearchOperator = @$_GET["z_datetime_approved"];

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$_GET["x_approved_by"];
		if ($this->approved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approved_by->AdvancedSearch->SearchOperator = @$_GET["z_approved_by"];

		// verified_by
		$this->verified_by->AdvancedSearch->SearchValue = @$_GET["x_verified_by"];
		if ($this->verified_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_by->AdvancedSearch->SearchOperator = @$_GET["z_verified_by"];

		// last_updated_date
		$this->last_updated_date->AdvancedSearch->SearchValue = @$_GET["x_last_updated_date"];
		if ($this->last_updated_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->last_updated_date->AdvancedSearch->SearchOperator = @$_GET["z_last_updated_date"];

		// last_updated_by
		$this->last_updated_by->AdvancedSearch->SearchValue = @$_GET["x_last_updated_by"];
		if ($this->last_updated_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->last_updated_by->AdvancedSearch->SearchOperator = @$_GET["z_last_updated_by"];

		// selection_sub_category
		$this->selection_sub_category->AdvancedSearch->SearchValue = @$_GET["x_selection_sub_category"];
		if ($this->selection_sub_category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->selection_sub_category->AdvancedSearch->SearchOperator = @$_GET["z_selection_sub_category"];

		// verified_datetime
		$this->verified_datetime->AdvancedSearch->SearchValue = @$_GET["x_verified_datetime"];
		if ($this->verified_datetime->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_datetime->AdvancedSearch->SearchOperator = @$_GET["z_verified_datetime"];

		// verified_action
		$this->verified_action->AdvancedSearch->SearchValue = @$_GET["x_verified_action"];
		if ($this->verified_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_action->AdvancedSearch->SearchOperator = @$_GET["z_verified_action"];

		// verified_comment
		$this->verified_comment->AdvancedSearch->SearchValue = @$_GET["x_verified_comment"];
		if ($this->verified_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_comment->AdvancedSearch->SearchOperator = @$_GET["z_verified_comment"];

		// job_assessment
		$this->job_assessment->AdvancedSearch->SearchValue = @$_GET["x_job_assessment"];
		if ($this->job_assessment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->job_assessment->AdvancedSearch->SearchOperator = @$_GET["z_job_assessment"];
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
		$this->datetime_initiated->setDbValue($row['datetime_initiated']);
		$this->incident_id->setDbValue($row['incident_id']);
		$this->staffid->setDbValue($row['staffid']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->departments->setDbValue($row['departments']);
		$this->category->setDbValue($row['category']);
		$this->sub_category->setDbValue($row['sub_category']);
		$this->sub_sub_category->setDbValue($row['sub_sub_category']);
		$this->start_date->setDbValue($row['start_date']);
		$this->end_date->setDbValue($row['end_date']);
		$this->duration->setDbValue($row['duration']);
		$this->amount_paid->setDbValue($row['amount_paid']);
		$this->no_of_people_involved->setDbValue($row['no_of_people_involved']);
		$this->incident_type->setDbValue($row['incident_type']);
		$this->incident_category->setDbValue($row['incident-category']);
		$this->incident_location->setDbValue($row['incident_location']);
		$this->incident_sub_location->setDbValue($row['incident_sub_location']);
		$this->incident_venue->setDbValue($row['incident_venue']);
		$this->incident_description->setDbValue($row['incident_description']);
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->_upload->setDbValue($this->_upload->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->report_by->setDbValue($row['report_by']);
		$this->datetime_resolved->setDbValue($row['datetime_resolved']);
		$this->assign_task->setDbValue($row['assign_task']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->reason->setDbValue($row['reason']);
		$this->resolved_action->setDbValue($row['resolved_action']);
		$this->resolved_comment->setDbValue($row['resolved_comment']);
		$this->resolved_by->setDbValue($row['resolved_by']);
		$this->datetime_approved->setDbValue($row['datetime_approved']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_by->setDbValue($row['verified_by']);
		$this->last_updated_date->setDbValue($row['last_updated_date']);
		$this->last_updated_by->setDbValue($row['last_updated_by']);
		$this->selection_sub_category->setDbValue($row['selection_sub_category']);
		$this->verified_datetime->setDbValue($row['verified_datetime']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->job_assessment->setDbValue($row['job_assessment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['datetime_initiated'] = NULL;
		$row['incident_id'] = NULL;
		$row['staffid'] = NULL;
		$row['staff_id'] = NULL;
		$row['department'] = NULL;
		$row['branch'] = NULL;
		$row['departments'] = NULL;
		$row['category'] = NULL;
		$row['sub_category'] = NULL;
		$row['sub_sub_category'] = NULL;
		$row['start_date'] = NULL;
		$row['end_date'] = NULL;
		$row['duration'] = NULL;
		$row['amount_paid'] = NULL;
		$row['no_of_people_involved'] = NULL;
		$row['incident_type'] = NULL;
		$row['incident-category'] = NULL;
		$row['incident_location'] = NULL;
		$row['incident_sub_location'] = NULL;
		$row['incident_venue'] = NULL;
		$row['incident_description'] = NULL;
		$row['upload'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['report_by'] = NULL;
		$row['datetime_resolved'] = NULL;
		$row['assign_task'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['reason'] = NULL;
		$row['resolved_action'] = NULL;
		$row['resolved_comment'] = NULL;
		$row['resolved_by'] = NULL;
		$row['datetime_approved'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_by'] = NULL;
		$row['last_updated_date'] = NULL;
		$row['last_updated_by'] = NULL;
		$row['selection_sub_category'] = NULL;
		$row['verified_datetime'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['job_assessment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime_initiated->DbValue = $row['datetime_initiated'];
		$this->incident_id->DbValue = $row['incident_id'];
		$this->staffid->DbValue = $row['staffid'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->departments->DbValue = $row['departments'];
		$this->category->DbValue = $row['category'];
		$this->sub_category->DbValue = $row['sub_category'];
		$this->sub_sub_category->DbValue = $row['sub_sub_category'];
		$this->start_date->DbValue = $row['start_date'];
		$this->end_date->DbValue = $row['end_date'];
		$this->duration->DbValue = $row['duration'];
		$this->amount_paid->DbValue = $row['amount_paid'];
		$this->no_of_people_involved->DbValue = $row['no_of_people_involved'];
		$this->incident_type->DbValue = $row['incident_type'];
		$this->incident_category->DbValue = $row['incident-category'];
		$this->incident_location->DbValue = $row['incident_location'];
		$this->incident_sub_location->DbValue = $row['incident_sub_location'];
		$this->incident_venue->DbValue = $row['incident_venue'];
		$this->incident_description->DbValue = $row['incident_description'];
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->report_by->DbValue = $row['report_by'];
		$this->datetime_resolved->DbValue = $row['datetime_resolved'];
		$this->assign_task->DbValue = $row['assign_task'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->reason->DbValue = $row['reason'];
		$this->resolved_action->DbValue = $row['resolved_action'];
		$this->resolved_comment->DbValue = $row['resolved_comment'];
		$this->resolved_by->DbValue = $row['resolved_by'];
		$this->datetime_approved->DbValue = $row['datetime_approved'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_by->DbValue = $row['verified_by'];
		$this->last_updated_date->DbValue = $row['last_updated_date'];
		$this->last_updated_by->DbValue = $row['last_updated_by'];
		$this->selection_sub_category->DbValue = $row['selection_sub_category'];
		$this->verified_datetime->DbValue = $row['verified_datetime'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->job_assessment->DbValue = $row['job_assessment'];
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
		// datetime_initiated
		// incident_id
		// staffid
		// staff_id
		// department
		// branch
		// departments
		// category
		// sub_category
		// sub_sub_category
		// start_date
		// end_date
		// duration
		// amount_paid
		// no_of_people_involved
		// incident_type
		// incident-category
		// incident_location
		// incident_sub_location
		// incident_venue
		// incident_description
		// upload
		// status
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// assign_task
		// approval_action
		// approval_comment
		// reason
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approved_by
		// verified_by
		// last_updated_date
		// last_updated_by
		// selection_sub_category
		// verified_datetime
		// verified_action
		// verified_comment
		// job_assessment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 7);
		$this->datetime_initiated->ViewCustomAttributes = "";

		// incident_id
		$this->incident_id->ViewValue = $this->incident_id->CurrentValue;
		$this->incident_id->ViewCustomAttributes = "";

		// staffid
		$this->staffid->ViewValue = $this->staffid->CurrentValue;
		if (strval($this->staffid->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staffid->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staffid->ViewValue = $this->staffid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staffid->ViewValue = $this->staffid->CurrentValue;
			}
		} else {
			$this->staffid->ViewValue = NULL;
		}
		$this->staffid->ViewCustomAttributes = "";

		// staff_id
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array("dx1" => '`department_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `department_id` ASC";
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
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array("dx1" => '`branch_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `branch_id` ASC";
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

		// departments
		if (strval($this->departments->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
		$sWhereWrk = "";
		$this->departments->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departments->ViewValue = $this->departments->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departments->ViewValue = $this->departments->CurrentValue;
			}
		} else {
			$this->departments->ViewValue = NULL;
		}
		$this->departments->ViewCustomAttributes = "";

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category->ViewValue = $this->category->CurrentValue;
			}
		} else {
			$this->category->ViewValue = NULL;
		}
		$this->category->ViewCustomAttributes = "";

		// sub_category
		if (strval($this->sub_category->CurrentValue) <> "") {
			$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
		$sWhereWrk = "";
		$this->sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_category->ViewValue = $this->sub_category->CurrentValue;
			}
		} else {
			$this->sub_category->ViewValue = NULL;
		}
		$this->sub_category->ViewCustomAttributes = "";

		// sub_sub_category
		if (strval($this->sub_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->sub_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_sub_category`";
		$sWhereWrk = "";
		$this->sub_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->sub_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->sub_sub_category->ViewValue .= $this->sub_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->sub_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->sub_sub_category->ViewValue = $this->sub_sub_category->CurrentValue;
			}
		} else {
			$this->sub_sub_category->ViewValue = NULL;
		}
		$this->sub_sub_category->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 2);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 2);
		$this->end_date->ViewCustomAttributes = "";

		// duration
		$this->duration->ViewValue = $this->duration->CurrentValue;
		$this->duration->ViewCustomAttributes = "";

		// amount_paid
		$this->amount_paid->ViewValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->ViewValue = ew_FormatCurrency($this->amount_paid->ViewValue, 2, -2, -2, -2);
		$this->amount_paid->ViewCustomAttributes = "";

		// no_of_people_involved
		$this->no_of_people_involved->ViewValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->ViewCustomAttributes = "";

		// incident_type
		if (strval($this->incident_type->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
		$sWhereWrk = "";
		$this->incident_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_type->ViewValue = $this->incident_type->CurrentValue;
			}
		} else {
			$this->incident_type->ViewValue = NULL;
		}
		$this->incident_type->ViewCustomAttributes = "";

		// incident-category
		if (strval($this->incident_category->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->incident_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_category->ViewValue = $this->incident_category->CurrentValue;
			}
		} else {
			$this->incident_category->ViewValue = NULL;
		}
		$this->incident_category->ViewCustomAttributes = "";

		// incident_location
		if (strval($this->incident_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->incident_location->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_location->ViewValue = $this->incident_location->CurrentValue;
			}
		} else {
			$this->incident_location->ViewValue = NULL;
		}
		$this->incident_location->ViewCustomAttributes = "";

		// incident_sub_location
		if (strval($this->incident_sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->incident_sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->incident_sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->CurrentValue;
			}
		} else {
			$this->incident_sub_location->ViewValue = NULL;
		}
		$this->incident_sub_location->ViewCustomAttributes = "";

		// incident_venue
		if (strval($this->incident_venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->incident_venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_venue->ViewValue = $this->incident_venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_venue->ViewValue = $this->incident_venue->CurrentValue;
			}
		} else {
			$this->incident_venue->ViewValue = NULL;
		}
		$this->incident_venue->ViewCustomAttributes = "";

		// incident_description
		$this->incident_description->ViewValue = $this->incident_description->CurrentValue;
		$this->incident_description->ViewCustomAttributes = "";

		// upload
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->ViewValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->ViewValue = "";
		}
		$this->_upload->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `description` ASC";
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

		// report_by
		$this->report_by->ViewValue = $this->report_by->CurrentValue;
		if (strval($this->report_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->report_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->report_by->ViewValue = $this->report_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->report_by->ViewValue = $this->report_by->CurrentValue;
			}
		} else {
			$this->report_by->ViewValue = NULL;
		}
		$this->report_by->ViewCustomAttributes = "";

		// datetime_resolved
		$this->datetime_resolved->ViewValue = $this->datetime_resolved->CurrentValue;
		$this->datetime_resolved->ViewValue = ew_FormatDateTime($this->datetime_resolved->ViewValue, 11);
		$this->datetime_resolved->ViewCustomAttributes = "";

		// assign_task
		if (strval($this->assign_task->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_task->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_task->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->assign_task->ViewValue = $this->assign_task->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_task->ViewValue = $this->assign_task->CurrentValue;
			}
		} else {
			$this->assign_task->ViewValue = NULL;
		}
		$this->assign_task->ViewCustomAttributes = "";

		// approval_action
		if (strval($this->approval_action->CurrentValue) <> "") {
			$this->approval_action->ViewValue = $this->approval_action->OptionCaption($this->approval_action->CurrentValue);
		} else {
			$this->approval_action->ViewValue = NULL;
		}
		$this->approval_action->ViewCustomAttributes = "";

		// reason
		if (strval($this->reason->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reason->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `reason`";
		$sWhereWrk = "";
		$this->reason->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->reason->ViewValue = $this->reason->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reason->ViewValue = $this->reason->CurrentValue;
			}
		} else {
			$this->reason->ViewValue = NULL;
		}
		$this->reason->ViewCustomAttributes = "";

		// resolved_action
		if (strval($this->resolved_action->CurrentValue) <> "") {
			$this->resolved_action->ViewValue = $this->resolved_action->OptionCaption($this->resolved_action->CurrentValue);
		} else {
			$this->resolved_action->ViewValue = NULL;
		}
		$this->resolved_action->ViewCustomAttributes = "";

		// resolved_by
		$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
		if (strval($this->resolved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->resolved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->resolved_by->ViewValue = $this->resolved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
			}
		} else {
			$this->resolved_by->ViewValue = NULL;
		}
		$this->resolved_by->ViewCustomAttributes = "";

		// datetime_approved
		$this->datetime_approved->ViewValue = $this->datetime_approved->CurrentValue;
		$this->datetime_approved->ViewValue = ew_FormatDateTime($this->datetime_approved->ViewValue, 11);
		$this->datetime_approved->ViewCustomAttributes = "";

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

		// verified_by
		$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
		$this->verified_by->ViewCustomAttributes = "";

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 0);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->last_updated_by->ViewValue = $this->last_updated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
			}
		} else {
			$this->last_updated_by->ViewValue = NULL;
		}
		$this->last_updated_by->ViewCustomAttributes = "";

		// selection_sub_category
		$this->selection_sub_category->ViewValue = $this->selection_sub_category->CurrentValue;
		$this->selection_sub_category->ViewCustomAttributes = "";

		// verified_datetime
		$this->verified_datetime->ViewValue = $this->verified_datetime->CurrentValue;
		$this->verified_datetime->ViewValue = ew_FormatDateTime($this->verified_datetime->ViewValue, 0);
		$this->verified_datetime->ViewCustomAttributes = "";

		// verified_action
		$this->verified_action->ViewValue = $this->verified_action->CurrentValue;
		$this->verified_action->ViewCustomAttributes = "";

		// verified_comment
		$this->verified_comment->ViewValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->ViewCustomAttributes = "";

		// job_assessment
		if (strval($this->job_assessment->CurrentValue) <> "") {
			$this->job_assessment->ViewValue = $this->job_assessment->OptionCaption($this->job_assessment->CurrentValue);
		} else {
			$this->job_assessment->ViewValue = NULL;
		}
		$this->job_assessment->ViewCustomAttributes = "";

			// datetime_initiated
			$this->datetime_initiated->LinkCustomAttributes = "";
			$this->datetime_initiated->HrefValue = "";
			$this->datetime_initiated->TooltipValue = "";

			// incident_id
			$this->incident_id->LinkCustomAttributes = "";
			$this->incident_id->HrefValue = "";
			$this->incident_id->TooltipValue = "";

			// staffid
			$this->staffid->LinkCustomAttributes = "";
			$this->staffid->HrefValue = "";
			$this->staffid->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// departments
			$this->departments->LinkCustomAttributes = "";
			$this->departments->HrefValue = "";
			$this->departments->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// sub_category
			$this->sub_category->LinkCustomAttributes = "";
			$this->sub_category->HrefValue = "";
			$this->sub_category->TooltipValue = "";

			// incident_location
			$this->incident_location->LinkCustomAttributes = "";
			$this->incident_location->HrefValue = "";
			$this->incident_location->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// assign_task
			$this->assign_task->LinkCustomAttributes = "";
			$this->assign_task->HrefValue = "";
			$this->assign_task->TooltipValue = "";

			// reason
			$this->reason->LinkCustomAttributes = "";
			$this->reason->HrefValue = "";
			$this->reason->TooltipValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";
			$this->job_assessment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// datetime_initiated
			$this->datetime_initiated->EditAttrs["class"] = "form-control";
			$this->datetime_initiated->EditCustomAttributes = "";
			$this->datetime_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime_initiated->AdvancedSearch->SearchValue, 7), 7));
			$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());
			$this->datetime_initiated->EditAttrs["class"] = "form-control";
			$this->datetime_initiated->EditCustomAttributes = "";
			$this->datetime_initiated->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime_initiated->AdvancedSearch->SearchValue2, 7), 7));
			$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());

			// incident_id
			$this->incident_id->EditAttrs["class"] = "form-control";
			$this->incident_id->EditCustomAttributes = "";
			$this->incident_id->EditValue = ew_HtmlEncode($this->incident_id->AdvancedSearch->SearchValue);
			$this->incident_id->PlaceHolder = ew_RemoveHtml($this->incident_id->FldCaption());

			// staffid
			$this->staffid->EditAttrs["class"] = "form-control";
			$this->staffid->EditCustomAttributes = "";
			$this->staffid->EditValue = ew_HtmlEncode($this->staffid->AdvancedSearch->SearchValue);
			$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

			// staff_id
			$this->staff_id->EditCustomAttributes = "";
			if (trim(strval($this->staff_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->staff_id->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->staff_id->AdvancedSearch->ViewValue = $this->staff_id->DisplayValue($arwrk);
			} else {
				$this->staff_id->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->staff_id->EditValue = $arwrk;

			// branch
			$this->branch->EditAttrs["class"] = "form-control";
			$this->branch->EditCustomAttributes = "";

			// departments
			$this->departments->EditCustomAttributes = "";
			if (trim(strval($this->departments->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
			$sWhereWrk = "";
			$this->departments->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->departments->AdvancedSearch->ViewValue = $this->departments->DisplayValue($arwrk);
			} else {
				$this->departments->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->departments->EditValue = $arwrk;

			// category
			$this->category->EditAttrs["class"] = "form-control";
			$this->category->EditCustomAttributes = "";

			// sub_category
			$this->sub_category->EditCustomAttributes = "";

			// incident_location
			$this->incident_location->EditCustomAttributes = "";
			if (trim(strval($this->incident_location->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_location`";
			$sWhereWrk = "";
			$this->incident_location->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_location->AdvancedSearch->ViewValue = $this->incident_location->DisplayValue($arwrk);
			} else {
				$this->incident_location->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_location->EditValue = $arwrk;

			// status
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->status->AdvancedSearch->ViewValue = $this->status->DisplayValue($arwrk);
			} else {
				$this->status->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// assign_task
			$this->assign_task->EditCustomAttributes = "";
			if (trim(strval($this->assign_task->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_task->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->assign_task->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->assign_task->AdvancedSearch->ViewValue = $this->assign_task->DisplayValue($arwrk);
			} else {
				$this->assign_task->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->assign_task->EditValue = $arwrk;

			// reason
			$this->reason->EditAttrs["class"] = "form-control";
			$this->reason->EditCustomAttributes = "";
			if (trim(strval($this->reason->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->reason->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `reason`";
			$sWhereWrk = "";
			$this->reason->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->reason->EditValue = $arwrk;

			// last_updated_date
			$this->last_updated_date->EditAttrs["class"] = "form-control";
			$this->last_updated_date->EditCustomAttributes = "";
			$this->last_updated_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->last_updated_date->AdvancedSearch->SearchValue, 0), 8));
			$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

			// last_updated_by
			$this->last_updated_by->EditAttrs["class"] = "form-control";
			$this->last_updated_by->EditCustomAttributes = "";
			$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->AdvancedSearch->SearchValue);
			$this->last_updated_by->PlaceHolder = ew_RemoveHtml($this->last_updated_by->FldCaption());

			// job_assessment
			$this->job_assessment->EditCustomAttributes = "";
			$this->job_assessment->EditValue = $this->job_assessment->Options(FALSE);
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
		if (!ew_CheckEuroDate($this->datetime_initiated->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime_initiated->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->datetime_initiated->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->datetime_initiated->FldErrMsg());
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
		$this->datetime_initiated->AdvancedSearch->Load();
		$this->incident_id->AdvancedSearch->Load();
		$this->staffid->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->branch->AdvancedSearch->Load();
		$this->departments->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->sub_category->AdvancedSearch->Load();
		$this->sub_sub_category->AdvancedSearch->Load();
		$this->start_date->AdvancedSearch->Load();
		$this->end_date->AdvancedSearch->Load();
		$this->duration->AdvancedSearch->Load();
		$this->amount_paid->AdvancedSearch->Load();
		$this->no_of_people_involved->AdvancedSearch->Load();
		$this->incident_type->AdvancedSearch->Load();
		$this->incident_category->AdvancedSearch->Load();
		$this->incident_location->AdvancedSearch->Load();
		$this->incident_sub_location->AdvancedSearch->Load();
		$this->incident_venue->AdvancedSearch->Load();
		$this->incident_description->AdvancedSearch->Load();
		$this->_upload->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->report_by->AdvancedSearch->Load();
		$this->datetime_resolved->AdvancedSearch->Load();
		$this->assign_task->AdvancedSearch->Load();
		$this->approval_action->AdvancedSearch->Load();
		$this->approval_comment->AdvancedSearch->Load();
		$this->reason->AdvancedSearch->Load();
		$this->resolved_action->AdvancedSearch->Load();
		$this->resolved_comment->AdvancedSearch->Load();
		$this->resolved_by->AdvancedSearch->Load();
		$this->datetime_approved->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->verified_by->AdvancedSearch->Load();
		$this->last_updated_date->AdvancedSearch->Load();
		$this->last_updated_by->AdvancedSearch->Load();
		$this->selection_sub_category->AdvancedSearch->Load();
		$this->verified_datetime->AdvancedSearch->Load();
		$this->verified_action->AdvancedSearch->Load();
		$this->verified_comment->AdvancedSearch->Load();
		$this->job_assessment->AdvancedSearch->Load();
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
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.freportlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		case "x_staff_id":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_departments":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "n" => 5);
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_location":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$sSqlWrk .= " ORDER BY `description` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_assign_task":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
				$sWhereWrk = "{filter}";
				$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
				if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_reason":
			$sSqlWrk = "";
				$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `reason`";
				$sWhereWrk = "";
				$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($report_list)) $report_list = new creport_list();

// Page init
$report_list->Page_Init();

// Page main
$report_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$report_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = freportlist = new ew_Form("freportlist", "list");
freportlist.FormKeyCountName = '<?php echo $report_list->FormKeyCountName ?>';

// Form_CustomValidate event
freportlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freportlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
freportlist.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportlist.Lists["x_staffid"].Data = "<?php echo $report_list->staffid->LookupFilterQuery(FALSE, "list") ?>";
freportlist.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_list->staffid->LookupFilterQuery(TRUE, "list"))) ?>;
freportlist.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportlist.Lists["x_staff_id"].Data = "<?php echo $report_list->staff_id->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
freportlist.Lists["x_branch"].Data = "<?php echo $report_list->branch->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freportlist.Lists["x_departments"].Data = "<?php echo $report_list->departments->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"category"};
freportlist.Lists["x_category"].Data = "<?php echo $report_list->category->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":[],"ChildFields":["x_sub_sub_category[]"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sub_category"};
freportlist.Lists["x_sub_category"].Data = "<?php echo $report_list->sub_category->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_incident_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freportlist.Lists["x_incident_location"].Data = "<?php echo $report_list->incident_location->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freportlist.Lists["x_status"].Data = "<?php echo $report_list->status->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_assign_task"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportlist.Lists["x_assign_task"].Data = "<?php echo $report_list->assign_task->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_reason"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"reason"};
freportlist.Lists["x_reason"].Data = "<?php echo $report_list->reason->LookupFilterQuery(FALSE, "list") ?>";
freportlist.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportlist.Lists["x_last_updated_by"].Data = "<?php echo $report_list->last_updated_by->LookupFilterQuery(FALSE, "list") ?>";
freportlist.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_list->last_updated_by->LookupFilterQuery(TRUE, "list"))) ?>;
freportlist.Lists["x_job_assessment"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportlist.Lists["x_job_assessment"].Options = <?php echo json_encode($report_list->job_assessment->Options()) ?>;

// Form object for search
var CurrentSearchForm = freportlistsrch = new ew_Form("freportlistsrch");

// Validate function for search
freportlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_datetime_initiated");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report->datetime_initiated->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
freportlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freportlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
freportlistsrch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportlistsrch.Lists["x_staff_id"].Data = "<?php echo $report_list->staff_id->LookupFilterQuery(FALSE, "extbs") ?>";
freportlistsrch.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freportlistsrch.Lists["x_departments"].Data = "<?php echo $report_list->departments->LookupFilterQuery(FALSE, "extbs") ?>";
freportlistsrch.Lists["x_incident_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freportlistsrch.Lists["x_incident_location"].Data = "<?php echo $report_list->incident_location->LookupFilterQuery(FALSE, "extbs") ?>";
freportlistsrch.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freportlistsrch.Lists["x_status"].Data = "<?php echo $report_list->status->LookupFilterQuery(FALSE, "extbs") ?>";
freportlistsrch.Lists["x_assign_task"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportlistsrch.Lists["x_assign_task"].Data = "<?php echo $report_list->assign_task->LookupFilterQuery(FALSE, "extbs") ?>";
freportlistsrch.Lists["x_reason"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"reason"};
freportlistsrch.Lists["x_reason"].Data = "<?php echo $report_list->reason->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($report->Export == "") { ?>
<div class="ewToolbar">
<?php if ($report_list->TotalRecs > 0 && $report_list->ExportOptions->Visible()) { ?>
<?php $report_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($report_list->SearchOptions->Visible()) { ?>
<?php $report_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($report_list->FilterOptions->Visible()) { ?>
<?php $report_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $report_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($report_list->TotalRecs <= 0)
			$report_list->TotalRecs = $report->ListRecordCount();
	} else {
		if (!$report_list->Recordset && ($report_list->Recordset = $report_list->LoadRecordset()))
			$report_list->TotalRecs = $report_list->Recordset->RecordCount();
	}
	$report_list->StartRec = 1;
	if ($report_list->DisplayRecs <= 0 || ($report->Export <> "" && $report->ExportAll)) // Display all records
		$report_list->DisplayRecs = $report_list->TotalRecs;
	if (!($report->Export <> "" && $report->ExportAll))
		$report_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$report_list->Recordset = $report_list->LoadRecordset($report_list->StartRec-1, $report_list->DisplayRecs);

	// Set no record found message
	if ($report->CurrentAction == "" && $report_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$report_list->setWarningMessage(ew_DeniedMsg());
		if ($report_list->SearchWhere == "0=101")
			$report_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$report_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$report_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($report->Export == "" && $report->CurrentAction == "") { ?>
<form name="freportlistsrch" id="freportlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($report_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="freportlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="report">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$report_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$report->RowType = EW_ROWTYPE_SEARCH;

// Render row
$report->ResetAttrs();
$report_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($report->datetime_initiated->Visible) { // datetime_initiated ?>
	<div id="xsc_datetime_initiated" class="ewCell form-group">
		<label for="x_datetime_initiated" class="ewSearchCaption ewLabel"><?php echo $report->datetime_initiated->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_datetime_initiated" id="z_datetime_initiated" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="report" data-field="x_datetime_initiated" data-format="7" name="x_datetime_initiated" id="x_datetime_initiated" size="18" placeholder="<?php echo ew_HtmlEncode($report->datetime_initiated->getPlaceHolder()) ?>" value="<?php echo $report->datetime_initiated->EditValue ?>"<?php echo $report->datetime_initiated->EditAttributes() ?>>
<?php if (!$report->datetime_initiated->ReadOnly && !$report->datetime_initiated->Disabled && !isset($report->datetime_initiated->EditAttrs["readonly"]) && !isset($report->datetime_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freportlistsrch", "x_datetime_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_datetime_initiated">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_datetime_initiated">
<input type="text" data-table="report" data-field="x_datetime_initiated" data-format="7" name="y_datetime_initiated" id="y_datetime_initiated" size="18" placeholder="<?php echo ew_HtmlEncode($report->datetime_initiated->getPlaceHolder()) ?>" value="<?php echo $report->datetime_initiated->EditValue2 ?>"<?php echo $report->datetime_initiated->EditAttributes() ?>>
<?php if (!$report->datetime_initiated->ReadOnly && !$report->datetime_initiated->Disabled && !isset($report->datetime_initiated->EditAttrs["readonly"]) && !isset($report->datetime_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freportlistsrch", "y_datetime_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($report->staff_id->Visible) { // staff_id ?>
	<div id="xsc_staff_id" class="ewCell form-group">
		<label for="x_staff_id" class="ewSearchCaption ewLabel"><?php echo $report->staff_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_staff_id"><?php echo (strval($report->staff_id->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->staff_id->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->staff_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_staff_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->staff_id->ReadOnly || $report->staff_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_staff_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo $report->staff_id->AdvancedSearch->SearchValue ?>"<?php echo $report->staff_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($report->departments->Visible) { // departments ?>
	<div id="xsc_departments" class="ewCell form-group">
		<label for="x_departments" class="ewSearchCaption ewLabel"><?php echo $report->departments->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_departments" id="z_departments" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_departments"><?php echo (strval($report->departments->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->departments->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->departments->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_departments',m:0,n:5});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->departments->ReadOnly || $report->departments->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_departments" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->departments->DisplayValueSeparatorAttribute() ?>" name="x_departments" id="x_departments" value="<?php echo $report->departments->AdvancedSearch->SearchValue ?>"<?php echo $report->departments->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($report->incident_location->Visible) { // incident_location ?>
	<div id="xsc_incident_location" class="ewCell form-group">
		<label for="x_incident_location" class="ewSearchCaption ewLabel"><?php echo $report->incident_location->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_incident_location" id="z_incident_location" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_incident_location"><?php echo (strval($report->incident_location->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->incident_location->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->incident_location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_incident_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->incident_location->ReadOnly || $report->incident_location->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_incident_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->incident_location->DisplayValueSeparatorAttribute() ?>" name="x_incident_location" id="x_incident_location" value="<?php echo $report->incident_location->AdvancedSearch->SearchValue ?>"<?php echo $report->incident_location->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($report->status->Visible) { // status ?>
	<div id="xsc_status" class="ewCell form-group">
		<label for="x_status" class="ewSearchCaption ewLabel"><?php echo $report->status->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_status"><?php echo (strval($report->status->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->status->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->status->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_status',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->status->ReadOnly || $report->status->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_status" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo $report->status->AdvancedSearch->SearchValue ?>"<?php echo $report->status->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($report->assign_task->Visible) { // assign_task ?>
	<div id="xsc_assign_task" class="ewCell form-group">
		<label for="x_assign_task" class="ewSearchCaption ewLabel"><?php echo $report->assign_task->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assign_task" id="z_assign_task" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign_task"><?php echo (strval($report->assign_task->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->assign_task->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->assign_task->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign_task',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->assign_task->ReadOnly || $report->assign_task->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_assign_task" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->assign_task->DisplayValueSeparatorAttribute() ?>" name="x_assign_task" id="x_assign_task" value="<?php echo $report->assign_task->AdvancedSearch->SearchValue ?>"<?php echo $report->assign_task->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($report->reason->Visible) { // reason ?>
	<div id="xsc_reason" class="ewCell form-group">
		<label for="x_reason" class="ewSearchCaption ewLabel"><?php echo $report->reason->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_reason" id="z_reason" value="="></span>
		<span class="ewSearchField">
<select data-table="report" data-field="x_reason" data-value-separator="<?php echo $report->reason->DisplayValueSeparatorAttribute() ?>" id="x_reason" name="x_reason"<?php echo $report->reason->EditAttributes() ?>>
<?php echo $report->reason->SelectOptionListHtml("x_reason") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($report_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($report_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $report_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($report_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($report_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($report_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($report_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $report_list->ShowPageHeader(); ?>
<?php
$report_list->ShowMessage();
?>
<?php if ($report_list->TotalRecs > 0 || $report->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($report_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> report">
<?php if ($report->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($report->CurrentAction <> "gridadd" && $report->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($report_list->Pager)) $report_list->Pager = new cPrevNextPager($report_list->StartRec, $report_list->DisplayRecs, $report_list->TotalRecs, $report_list->AutoHidePager) ?>
<?php if ($report_list->Pager->RecordCount > 0 && $report_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($report_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $report_list->PageUrl() ?>start=<?php echo $report_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($report_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $report_list->PageUrl() ?>start=<?php echo $report_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $report_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($report_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $report_list->PageUrl() ?>start=<?php echo $report_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($report_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $report_list->PageUrl() ?>start=<?php echo $report_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $report_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($report_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $report_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $report_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $report_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($report_list->TotalRecs > 0 && (!$report_list->AutoHidePageSizeSelector || $report_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="report">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($report_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($report_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($report_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($report_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($report_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($report->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($report_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="freportlist" id="freportlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($report_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $report_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="report">
<div id="gmp_report" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($report_list->TotalRecs > 0 || $report->CurrentAction == "gridedit") { ?>
<table id="tbl_reportlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$report_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$report_list->RenderListOptions();

// Render list options (header, left)
$report_list->ListOptions->Render("header", "left");
?>
<?php if ($report->datetime_initiated->Visible) { // datetime_initiated ?>
	<?php if ($report->SortUrl($report->datetime_initiated) == "") { ?>
		<th data-name="datetime_initiated" class="<?php echo $report->datetime_initiated->HeaderCellClass() ?>"><div id="elh_report_datetime_initiated" class="report_datetime_initiated"><div class="ewTableHeaderCaption"><?php echo $report->datetime_initiated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="datetime_initiated" class="<?php echo $report->datetime_initiated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->datetime_initiated) ?>',1);"><div id="elh_report_datetime_initiated" class="report_datetime_initiated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->datetime_initiated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->datetime_initiated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->datetime_initiated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->incident_id->Visible) { // incident_id ?>
	<?php if ($report->SortUrl($report->incident_id) == "") { ?>
		<th data-name="incident_id" class="<?php echo $report->incident_id->HeaderCellClass() ?>"><div id="elh_report_incident_id" class="report_incident_id"><div class="ewTableHeaderCaption"><?php echo $report->incident_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="incident_id" class="<?php echo $report->incident_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->incident_id) ?>',1);"><div id="elh_report_incident_id" class="report_incident_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->incident_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($report->incident_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->incident_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->staffid->Visible) { // staffid ?>
	<?php if ($report->SortUrl($report->staffid) == "") { ?>
		<th data-name="staffid" class="<?php echo $report->staffid->HeaderCellClass() ?>"><div id="elh_report_staffid" class="report_staffid"><div class="ewTableHeaderCaption"><?php echo $report->staffid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staffid" class="<?php echo $report->staffid->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->staffid) ?>',1);"><div id="elh_report_staffid" class="report_staffid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->staffid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($report->staffid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->staffid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->staff_id->Visible) { // staff_id ?>
	<?php if ($report->SortUrl($report->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $report->staff_id->HeaderCellClass() ?>"><div id="elh_report_staff_id" class="report_staff_id"><div class="ewTableHeaderCaption"><?php echo $report->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $report->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->staff_id) ?>',1);"><div id="elh_report_staff_id" class="report_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->staff_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->branch->Visible) { // branch ?>
	<?php if ($report->SortUrl($report->branch) == "") { ?>
		<th data-name="branch" class="<?php echo $report->branch->HeaderCellClass() ?>"><div id="elh_report_branch" class="report_branch"><div class="ewTableHeaderCaption"><?php echo $report->branch->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="branch" class="<?php echo $report->branch->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->branch) ?>',1);"><div id="elh_report_branch" class="report_branch">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->branch->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->branch->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->branch->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->departments->Visible) { // departments ?>
	<?php if ($report->SortUrl($report->departments) == "") { ?>
		<th data-name="departments" class="<?php echo $report->departments->HeaderCellClass() ?>"><div id="elh_report_departments" class="report_departments"><div class="ewTableHeaderCaption"><?php echo $report->departments->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departments" class="<?php echo $report->departments->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->departments) ?>',1);"><div id="elh_report_departments" class="report_departments">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->departments->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->departments->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->departments->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->category->Visible) { // category ?>
	<?php if ($report->SortUrl($report->category) == "") { ?>
		<th data-name="category" class="<?php echo $report->category->HeaderCellClass() ?>"><div id="elh_report_category" class="report_category"><div class="ewTableHeaderCaption"><?php echo $report->category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="category" class="<?php echo $report->category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->category) ?>',1);"><div id="elh_report_category" class="report_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->category->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->sub_category->Visible) { // sub_category ?>
	<?php if ($report->SortUrl($report->sub_category) == "") { ?>
		<th data-name="sub_category" class="<?php echo $report->sub_category->HeaderCellClass() ?>"><div id="elh_report_sub_category" class="report_sub_category"><div class="ewTableHeaderCaption"><?php echo $report->sub_category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_category" class="<?php echo $report->sub_category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->sub_category) ?>',1);"><div id="elh_report_sub_category" class="report_sub_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->sub_category->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->sub_category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->sub_category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->incident_location->Visible) { // incident_location ?>
	<?php if ($report->SortUrl($report->incident_location) == "") { ?>
		<th data-name="incident_location" class="<?php echo $report->incident_location->HeaderCellClass() ?>"><div id="elh_report_incident_location" class="report_incident_location"><div class="ewTableHeaderCaption"><?php echo $report->incident_location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="incident_location" class="<?php echo $report->incident_location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->incident_location) ?>',1);"><div id="elh_report_incident_location" class="report_incident_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->incident_location->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->incident_location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->incident_location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->status->Visible) { // status ?>
	<?php if ($report->SortUrl($report->status) == "") { ?>
		<th data-name="status" class="<?php echo $report->status->HeaderCellClass() ?>"><div id="elh_report_status" class="report_status"><div class="ewTableHeaderCaption"><?php echo $report->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $report->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->status) ?>',1);"><div id="elh_report_status" class="report_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->assign_task->Visible) { // assign_task ?>
	<?php if ($report->SortUrl($report->assign_task) == "") { ?>
		<th data-name="assign_task" class="<?php echo $report->assign_task->HeaderCellClass() ?>"><div id="elh_report_assign_task" class="report_assign_task"><div class="ewTableHeaderCaption"><?php echo $report->assign_task->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="assign_task" class="<?php echo $report->assign_task->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->assign_task) ?>',1);"><div id="elh_report_assign_task" class="report_assign_task">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->assign_task->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->assign_task->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->assign_task->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->reason->Visible) { // reason ?>
	<?php if ($report->SortUrl($report->reason) == "") { ?>
		<th data-name="reason" class="<?php echo $report->reason->HeaderCellClass() ?>"><div id="elh_report_reason" class="report_reason"><div class="ewTableHeaderCaption"><?php echo $report->reason->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reason" class="<?php echo $report->reason->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->reason) ?>',1);"><div id="elh_report_reason" class="report_reason">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->reason->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->reason->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->reason->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->last_updated_date->Visible) { // last_updated_date ?>
	<?php if ($report->SortUrl($report->last_updated_date) == "") { ?>
		<th data-name="last_updated_date" class="<?php echo $report->last_updated_date->HeaderCellClass() ?>"><div id="elh_report_last_updated_date" class="report_last_updated_date"><div class="ewTableHeaderCaption"><?php echo $report->last_updated_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_updated_date" class="<?php echo $report->last_updated_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->last_updated_date) ?>',1);"><div id="elh_report_last_updated_date" class="report_last_updated_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->last_updated_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->last_updated_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->last_updated_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->last_updated_by->Visible) { // last_updated_by ?>
	<?php if ($report->SortUrl($report->last_updated_by) == "") { ?>
		<th data-name="last_updated_by" class="<?php echo $report->last_updated_by->HeaderCellClass() ?>"><div id="elh_report_last_updated_by" class="report_last_updated_by"><div class="ewTableHeaderCaption"><?php echo $report->last_updated_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_updated_by" class="<?php echo $report->last_updated_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->last_updated_by) ?>',1);"><div id="elh_report_last_updated_by" class="report_last_updated_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->last_updated_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->last_updated_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->last_updated_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($report->job_assessment->Visible) { // job_assessment ?>
	<?php if ($report->SortUrl($report->job_assessment) == "") { ?>
		<th data-name="job_assessment" class="<?php echo $report->job_assessment->HeaderCellClass() ?>"><div id="elh_report_job_assessment" class="report_job_assessment"><div class="ewTableHeaderCaption"><?php echo $report->job_assessment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="job_assessment" class="<?php echo $report->job_assessment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $report->SortUrl($report->job_assessment) ?>',1);"><div id="elh_report_job_assessment" class="report_job_assessment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $report->job_assessment->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($report->job_assessment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($report->job_assessment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$report_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($report->ExportAll && $report->Export <> "") {
	$report_list->StopRec = $report_list->TotalRecs;
} else {

	// Set the last record to display
	if ($report_list->TotalRecs > $report_list->StartRec + $report_list->DisplayRecs - 1)
		$report_list->StopRec = $report_list->StartRec + $report_list->DisplayRecs - 1;
	else
		$report_list->StopRec = $report_list->TotalRecs;
}
$report_list->RecCnt = $report_list->StartRec - 1;
if ($report_list->Recordset && !$report_list->Recordset->EOF) {
	$report_list->Recordset->MoveFirst();
	$bSelectLimit = $report_list->UseSelectLimit;
	if (!$bSelectLimit && $report_list->StartRec > 1)
		$report_list->Recordset->Move($report_list->StartRec - 1);
} elseif (!$report->AllowAddDeleteRow && $report_list->StopRec == 0) {
	$report_list->StopRec = $report->GridAddRowCount;
}

// Initialize aggregate
$report->RowType = EW_ROWTYPE_AGGREGATEINIT;
$report->ResetAttrs();
$report_list->RenderRow();
while ($report_list->RecCnt < $report_list->StopRec) {
	$report_list->RecCnt++;
	if (intval($report_list->RecCnt) >= intval($report_list->StartRec)) {
		$report_list->RowCnt++;

		// Set up key count
		$report_list->KeyCount = $report_list->RowIndex;

		// Init row class and style
		$report->ResetAttrs();
		$report->CssClass = "";
		if ($report->CurrentAction == "gridadd") {
		} else {
			$report_list->LoadRowValues($report_list->Recordset); // Load row values
		}
		$report->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$report->RowAttrs = array_merge($report->RowAttrs, array('data-rowindex'=>$report_list->RowCnt, 'id'=>'r' . $report_list->RowCnt . '_report', 'data-rowtype'=>$report->RowType));

		// Render row
		$report_list->RenderRow();

		// Render list options
		$report_list->RenderListOptions();
?>
	<tr<?php echo $report->RowAttributes() ?>>
<?php

// Render list options (body, left)
$report_list->ListOptions->Render("body", "left", $report_list->RowCnt);
?>
	<?php if ($report->datetime_initiated->Visible) { // datetime_initiated ?>
		<td data-name="datetime_initiated"<?php echo $report->datetime_initiated->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_datetime_initiated" class="report_datetime_initiated">
<span<?php echo $report->datetime_initiated->ViewAttributes() ?>>
<?php echo $report->datetime_initiated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->incident_id->Visible) { // incident_id ?>
		<td data-name="incident_id"<?php echo $report->incident_id->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_incident_id" class="report_incident_id">
<span<?php echo $report->incident_id->ViewAttributes() ?>>
<?php echo $report->incident_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->staffid->Visible) { // staffid ?>
		<td data-name="staffid"<?php echo $report->staffid->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_staffid" class="report_staffid">
<span<?php echo $report->staffid->ViewAttributes() ?>>
<?php echo $report->staffid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $report->staff_id->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_staff_id" class="report_staff_id">
<span<?php echo $report->staff_id->ViewAttributes() ?>>
<?php echo $report->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->branch->Visible) { // branch ?>
		<td data-name="branch"<?php echo $report->branch->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_branch" class="report_branch">
<span<?php echo $report->branch->ViewAttributes() ?>>
<?php echo $report->branch->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->departments->Visible) { // departments ?>
		<td data-name="departments"<?php echo $report->departments->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_departments" class="report_departments">
<span<?php echo $report->departments->ViewAttributes() ?>>
<?php echo $report->departments->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->category->Visible) { // category ?>
		<td data-name="category"<?php echo $report->category->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_category" class="report_category">
<span<?php echo $report->category->ViewAttributes() ?>>
<?php echo $report->category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->sub_category->Visible) { // sub_category ?>
		<td data-name="sub_category"<?php echo $report->sub_category->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_sub_category" class="report_sub_category">
<span<?php echo $report->sub_category->ViewAttributes() ?>>
<?php echo $report->sub_category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->incident_location->Visible) { // incident_location ?>
		<td data-name="incident_location"<?php echo $report->incident_location->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_incident_location" class="report_incident_location">
<span<?php echo $report->incident_location->ViewAttributes() ?>>
<?php echo $report->incident_location->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->status->Visible) { // status ?>
		<td data-name="status"<?php echo $report->status->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_status" class="report_status">
<span<?php echo $report->status->ViewAttributes() ?>>
<?php echo $report->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->assign_task->Visible) { // assign_task ?>
		<td data-name="assign_task"<?php echo $report->assign_task->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_assign_task" class="report_assign_task">
<span<?php echo $report->assign_task->ViewAttributes() ?>>
<?php echo $report->assign_task->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->reason->Visible) { // reason ?>
		<td data-name="reason"<?php echo $report->reason->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_reason" class="report_reason">
<span<?php echo $report->reason->ViewAttributes() ?>>
<?php echo $report->reason->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->last_updated_date->Visible) { // last_updated_date ?>
		<td data-name="last_updated_date"<?php echo $report->last_updated_date->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_last_updated_date" class="report_last_updated_date">
<span<?php echo $report->last_updated_date->ViewAttributes() ?>>
<?php echo $report->last_updated_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->last_updated_by->Visible) { // last_updated_by ?>
		<td data-name="last_updated_by"<?php echo $report->last_updated_by->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_last_updated_by" class="report_last_updated_by">
<span<?php echo $report->last_updated_by->ViewAttributes() ?>>
<?php echo $report->last_updated_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($report->job_assessment->Visible) { // job_assessment ?>
		<td data-name="job_assessment"<?php echo $report->job_assessment->CellAttributes() ?>>
<span id="el<?php echo $report_list->RowCnt ?>_report_job_assessment" class="report_job_assessment">
<span<?php echo $report->job_assessment->ViewAttributes() ?>>
<?php echo $report->job_assessment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$report_list->ListOptions->Render("body", "right", $report_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($report->CurrentAction <> "gridadd")
		$report_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($report->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($report_list->Recordset)
	$report_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($report_list->TotalRecs == 0 && $report->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($report_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($report->Export == "") { ?>
<script type="text/javascript">
freportlistsrch.FilterList = <?php echo $report_list->GetFilterList() ?>;
freportlistsrch.Init();
freportlist.Init();
</script>
<?php } ?>
<?php
$report_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$report_list->Page_Terminate();
?>
