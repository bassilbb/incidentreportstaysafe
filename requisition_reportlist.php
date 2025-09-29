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

$requisition_report_list = NULL; // Initialize page object first

class crequisition_report_list extends crequisition_report {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'requisition_report';

	// Page object name
	var $PageObjName = 'requisition_report_list';

	// Grid form hidden field names
	var $FormName = 'frequisition_reportlist';
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

		// Table object (requisition_report)
		if (!isset($GLOBALS["requisition_report"]) || get_class($GLOBALS["requisition_report"]) == "crequisition_report") {
			$GLOBALS["requisition_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["requisition_report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "requisition_reportadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "requisition_reportdelete.php";
		$this->MultiUpdateUrl = "requisition_reportupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list');

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
		$this->FilterOptions->TagClassName = "ewFilterOption frequisition_reportlistsrch";

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
		$this->date->SetVisibility();
		$this->reference->SetVisibility();
		$this->outward_location->SetVisibility();
		$this->delivery_point->SetVisibility();
		$this->name->SetVisibility();
		$this->organization->SetVisibility();
		$this->designation->SetVisibility();
		$this->department->SetVisibility();
		$this->item_description->SetVisibility();
		$this->driver_name->SetVisibility();
		$this->vehicle_no->SetVisibility();
		$this->authorizer_name->SetVisibility();
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "frequisition_reportlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->code->AdvancedSearch->ToJson(), ","); // Field code
		$sFilterList = ew_Concat($sFilterList, $this->date->AdvancedSearch->ToJson(), ","); // Field date
		$sFilterList = ew_Concat($sFilterList, $this->reference->AdvancedSearch->ToJson(), ","); // Field reference
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->outward_location->AdvancedSearch->ToJson(), ","); // Field outward_location
		$sFilterList = ew_Concat($sFilterList, $this->delivery_point->AdvancedSearch->ToJson(), ","); // Field delivery_point
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJson(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->organization->AdvancedSearch->ToJson(), ","); // Field organization
		$sFilterList = ew_Concat($sFilterList, $this->designation->AdvancedSearch->ToJson(), ","); // Field designation
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->item_description->AdvancedSearch->ToJson(), ","); // Field item_description
		$sFilterList = ew_Concat($sFilterList, $this->driver_name->AdvancedSearch->ToJson(), ","); // Field driver_name
		$sFilterList = ew_Concat($sFilterList, $this->vehicle_no->AdvancedSearch->ToJson(), ","); // Field vehicle_no
		$sFilterList = ew_Concat($sFilterList, $this->requester_action->AdvancedSearch->ToJson(), ","); // Field requester_action
		$sFilterList = ew_Concat($sFilterList, $this->requester_comment->AdvancedSearch->ToJson(), ","); // Field requester_comment
		$sFilterList = ew_Concat($sFilterList, $this->date_authorized->AdvancedSearch->ToJson(), ","); // Field date_authorized
		$sFilterList = ew_Concat($sFilterList, $this->authorizer_name->AdvancedSearch->ToJson(), ","); // Field authorizer_name
		$sFilterList = ew_Concat($sFilterList, $this->authorizer_action->AdvancedSearch->ToJson(), ","); // Field authorizer_action
		$sFilterList = ew_Concat($sFilterList, $this->authorizer_comment->AdvancedSearch->ToJson(), ","); // Field authorizer_comment
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->rep_date->AdvancedSearch->ToJson(), ","); // Field rep_date
		$sFilterList = ew_Concat($sFilterList, $this->rep_name->AdvancedSearch->ToJson(), ","); // Field rep_name
		$sFilterList = ew_Concat($sFilterList, $this->outward_datetime->AdvancedSearch->ToJson(), ","); // Field outward_datetime
		$sFilterList = ew_Concat($sFilterList, $this->rep_action->AdvancedSearch->ToJson(), ","); // Field rep_action
		$sFilterList = ew_Concat($sFilterList, $this->rep_comment->AdvancedSearch->ToJson(), ","); // Field rep_comment
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "frequisition_reportlistsrch", $filters);

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

		// Field date
		$this->date->AdvancedSearch->SearchValue = @$filter["x_date"];
		$this->date->AdvancedSearch->SearchOperator = @$filter["z_date"];
		$this->date->AdvancedSearch->SearchCondition = @$filter["v_date"];
		$this->date->AdvancedSearch->SearchValue2 = @$filter["y_date"];
		$this->date->AdvancedSearch->SearchOperator2 = @$filter["w_date"];
		$this->date->AdvancedSearch->Save();

		// Field reference
		$this->reference->AdvancedSearch->SearchValue = @$filter["x_reference"];
		$this->reference->AdvancedSearch->SearchOperator = @$filter["z_reference"];
		$this->reference->AdvancedSearch->SearchCondition = @$filter["v_reference"];
		$this->reference->AdvancedSearch->SearchValue2 = @$filter["y_reference"];
		$this->reference->AdvancedSearch->SearchOperator2 = @$filter["w_reference"];
		$this->reference->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

		// Field outward_location
		$this->outward_location->AdvancedSearch->SearchValue = @$filter["x_outward_location"];
		$this->outward_location->AdvancedSearch->SearchOperator = @$filter["z_outward_location"];
		$this->outward_location->AdvancedSearch->SearchCondition = @$filter["v_outward_location"];
		$this->outward_location->AdvancedSearch->SearchValue2 = @$filter["y_outward_location"];
		$this->outward_location->AdvancedSearch->SearchOperator2 = @$filter["w_outward_location"];
		$this->outward_location->AdvancedSearch->Save();

		// Field delivery_point
		$this->delivery_point->AdvancedSearch->SearchValue = @$filter["x_delivery_point"];
		$this->delivery_point->AdvancedSearch->SearchOperator = @$filter["z_delivery_point"];
		$this->delivery_point->AdvancedSearch->SearchCondition = @$filter["v_delivery_point"];
		$this->delivery_point->AdvancedSearch->SearchValue2 = @$filter["y_delivery_point"];
		$this->delivery_point->AdvancedSearch->SearchOperator2 = @$filter["w_delivery_point"];
		$this->delivery_point->AdvancedSearch->Save();

		// Field name
		$this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
		$this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
		$this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
		$this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
		$this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
		$this->name->AdvancedSearch->Save();

		// Field organization
		$this->organization->AdvancedSearch->SearchValue = @$filter["x_organization"];
		$this->organization->AdvancedSearch->SearchOperator = @$filter["z_organization"];
		$this->organization->AdvancedSearch->SearchCondition = @$filter["v_organization"];
		$this->organization->AdvancedSearch->SearchValue2 = @$filter["y_organization"];
		$this->organization->AdvancedSearch->SearchOperator2 = @$filter["w_organization"];
		$this->organization->AdvancedSearch->Save();

		// Field designation
		$this->designation->AdvancedSearch->SearchValue = @$filter["x_designation"];
		$this->designation->AdvancedSearch->SearchOperator = @$filter["z_designation"];
		$this->designation->AdvancedSearch->SearchCondition = @$filter["v_designation"];
		$this->designation->AdvancedSearch->SearchValue2 = @$filter["y_designation"];
		$this->designation->AdvancedSearch->SearchOperator2 = @$filter["w_designation"];
		$this->designation->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field item_description
		$this->item_description->AdvancedSearch->SearchValue = @$filter["x_item_description"];
		$this->item_description->AdvancedSearch->SearchOperator = @$filter["z_item_description"];
		$this->item_description->AdvancedSearch->SearchCondition = @$filter["v_item_description"];
		$this->item_description->AdvancedSearch->SearchValue2 = @$filter["y_item_description"];
		$this->item_description->AdvancedSearch->SearchOperator2 = @$filter["w_item_description"];
		$this->item_description->AdvancedSearch->Save();

		// Field driver_name
		$this->driver_name->AdvancedSearch->SearchValue = @$filter["x_driver_name"];
		$this->driver_name->AdvancedSearch->SearchOperator = @$filter["z_driver_name"];
		$this->driver_name->AdvancedSearch->SearchCondition = @$filter["v_driver_name"];
		$this->driver_name->AdvancedSearch->SearchValue2 = @$filter["y_driver_name"];
		$this->driver_name->AdvancedSearch->SearchOperator2 = @$filter["w_driver_name"];
		$this->driver_name->AdvancedSearch->Save();

		// Field vehicle_no
		$this->vehicle_no->AdvancedSearch->SearchValue = @$filter["x_vehicle_no"];
		$this->vehicle_no->AdvancedSearch->SearchOperator = @$filter["z_vehicle_no"];
		$this->vehicle_no->AdvancedSearch->SearchCondition = @$filter["v_vehicle_no"];
		$this->vehicle_no->AdvancedSearch->SearchValue2 = @$filter["y_vehicle_no"];
		$this->vehicle_no->AdvancedSearch->SearchOperator2 = @$filter["w_vehicle_no"];
		$this->vehicle_no->AdvancedSearch->Save();

		// Field requester_action
		$this->requester_action->AdvancedSearch->SearchValue = @$filter["x_requester_action"];
		$this->requester_action->AdvancedSearch->SearchOperator = @$filter["z_requester_action"];
		$this->requester_action->AdvancedSearch->SearchCondition = @$filter["v_requester_action"];
		$this->requester_action->AdvancedSearch->SearchValue2 = @$filter["y_requester_action"];
		$this->requester_action->AdvancedSearch->SearchOperator2 = @$filter["w_requester_action"];
		$this->requester_action->AdvancedSearch->Save();

		// Field requester_comment
		$this->requester_comment->AdvancedSearch->SearchValue = @$filter["x_requester_comment"];
		$this->requester_comment->AdvancedSearch->SearchOperator = @$filter["z_requester_comment"];
		$this->requester_comment->AdvancedSearch->SearchCondition = @$filter["v_requester_comment"];
		$this->requester_comment->AdvancedSearch->SearchValue2 = @$filter["y_requester_comment"];
		$this->requester_comment->AdvancedSearch->SearchOperator2 = @$filter["w_requester_comment"];
		$this->requester_comment->AdvancedSearch->Save();

		// Field date_authorized
		$this->date_authorized->AdvancedSearch->SearchValue = @$filter["x_date_authorized"];
		$this->date_authorized->AdvancedSearch->SearchOperator = @$filter["z_date_authorized"];
		$this->date_authorized->AdvancedSearch->SearchCondition = @$filter["v_date_authorized"];
		$this->date_authorized->AdvancedSearch->SearchValue2 = @$filter["y_date_authorized"];
		$this->date_authorized->AdvancedSearch->SearchOperator2 = @$filter["w_date_authorized"];
		$this->date_authorized->AdvancedSearch->Save();

		// Field authorizer_name
		$this->authorizer_name->AdvancedSearch->SearchValue = @$filter["x_authorizer_name"];
		$this->authorizer_name->AdvancedSearch->SearchOperator = @$filter["z_authorizer_name"];
		$this->authorizer_name->AdvancedSearch->SearchCondition = @$filter["v_authorizer_name"];
		$this->authorizer_name->AdvancedSearch->SearchValue2 = @$filter["y_authorizer_name"];
		$this->authorizer_name->AdvancedSearch->SearchOperator2 = @$filter["w_authorizer_name"];
		$this->authorizer_name->AdvancedSearch->Save();

		// Field authorizer_action
		$this->authorizer_action->AdvancedSearch->SearchValue = @$filter["x_authorizer_action"];
		$this->authorizer_action->AdvancedSearch->SearchOperator = @$filter["z_authorizer_action"];
		$this->authorizer_action->AdvancedSearch->SearchCondition = @$filter["v_authorizer_action"];
		$this->authorizer_action->AdvancedSearch->SearchValue2 = @$filter["y_authorizer_action"];
		$this->authorizer_action->AdvancedSearch->SearchOperator2 = @$filter["w_authorizer_action"];
		$this->authorizer_action->AdvancedSearch->Save();

		// Field authorizer_comment
		$this->authorizer_comment->AdvancedSearch->SearchValue = @$filter["x_authorizer_comment"];
		$this->authorizer_comment->AdvancedSearch->SearchOperator = @$filter["z_authorizer_comment"];
		$this->authorizer_comment->AdvancedSearch->SearchCondition = @$filter["v_authorizer_comment"];
		$this->authorizer_comment->AdvancedSearch->SearchValue2 = @$filter["y_authorizer_comment"];
		$this->authorizer_comment->AdvancedSearch->SearchOperator2 = @$filter["w_authorizer_comment"];
		$this->authorizer_comment->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field rep_date
		$this->rep_date->AdvancedSearch->SearchValue = @$filter["x_rep_date"];
		$this->rep_date->AdvancedSearch->SearchOperator = @$filter["z_rep_date"];
		$this->rep_date->AdvancedSearch->SearchCondition = @$filter["v_rep_date"];
		$this->rep_date->AdvancedSearch->SearchValue2 = @$filter["y_rep_date"];
		$this->rep_date->AdvancedSearch->SearchOperator2 = @$filter["w_rep_date"];
		$this->rep_date->AdvancedSearch->Save();

		// Field rep_name
		$this->rep_name->AdvancedSearch->SearchValue = @$filter["x_rep_name"];
		$this->rep_name->AdvancedSearch->SearchOperator = @$filter["z_rep_name"];
		$this->rep_name->AdvancedSearch->SearchCondition = @$filter["v_rep_name"];
		$this->rep_name->AdvancedSearch->SearchValue2 = @$filter["y_rep_name"];
		$this->rep_name->AdvancedSearch->SearchOperator2 = @$filter["w_rep_name"];
		$this->rep_name->AdvancedSearch->Save();

		// Field outward_datetime
		$this->outward_datetime->AdvancedSearch->SearchValue = @$filter["x_outward_datetime"];
		$this->outward_datetime->AdvancedSearch->SearchOperator = @$filter["z_outward_datetime"];
		$this->outward_datetime->AdvancedSearch->SearchCondition = @$filter["v_outward_datetime"];
		$this->outward_datetime->AdvancedSearch->SearchValue2 = @$filter["y_outward_datetime"];
		$this->outward_datetime->AdvancedSearch->SearchOperator2 = @$filter["w_outward_datetime"];
		$this->outward_datetime->AdvancedSearch->Save();

		// Field rep_action
		$this->rep_action->AdvancedSearch->SearchValue = @$filter["x_rep_action"];
		$this->rep_action->AdvancedSearch->SearchOperator = @$filter["z_rep_action"];
		$this->rep_action->AdvancedSearch->SearchCondition = @$filter["v_rep_action"];
		$this->rep_action->AdvancedSearch->SearchValue2 = @$filter["y_rep_action"];
		$this->rep_action->AdvancedSearch->SearchOperator2 = @$filter["w_rep_action"];
		$this->rep_action->AdvancedSearch->Save();

		// Field rep_comment
		$this->rep_comment->AdvancedSearch->SearchValue = @$filter["x_rep_comment"];
		$this->rep_comment->AdvancedSearch->SearchOperator = @$filter["z_rep_comment"];
		$this->rep_comment->AdvancedSearch->SearchCondition = @$filter["v_rep_comment"];
		$this->rep_comment->AdvancedSearch->SearchValue2 = @$filter["y_rep_comment"];
		$this->rep_comment->AdvancedSearch->SearchOperator2 = @$filter["w_rep_comment"];
		$this->rep_comment->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->code, $Default, FALSE); // code
		$this->BuildSearchSql($sWhere, $this->date, $Default, FALSE); // date
		$this->BuildSearchSql($sWhere, $this->reference, $Default, FALSE); // reference
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->outward_location, $Default, FALSE); // outward_location
		$this->BuildSearchSql($sWhere, $this->delivery_point, $Default, FALSE); // delivery_point
		$this->BuildSearchSql($sWhere, $this->name, $Default, FALSE); // name
		$this->BuildSearchSql($sWhere, $this->organization, $Default, FALSE); // organization
		$this->BuildSearchSql($sWhere, $this->designation, $Default, FALSE); // designation
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->item_description, $Default, FALSE); // item_description
		$this->BuildSearchSql($sWhere, $this->driver_name, $Default, FALSE); // driver_name
		$this->BuildSearchSql($sWhere, $this->vehicle_no, $Default, FALSE); // vehicle_no
		$this->BuildSearchSql($sWhere, $this->requester_action, $Default, FALSE); // requester_action
		$this->BuildSearchSql($sWhere, $this->requester_comment, $Default, FALSE); // requester_comment
		$this->BuildSearchSql($sWhere, $this->date_authorized, $Default, FALSE); // date_authorized
		$this->BuildSearchSql($sWhere, $this->authorizer_name, $Default, FALSE); // authorizer_name
		$this->BuildSearchSql($sWhere, $this->authorizer_action, $Default, FALSE); // authorizer_action
		$this->BuildSearchSql($sWhere, $this->authorizer_comment, $Default, FALSE); // authorizer_comment
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->rep_date, $Default, FALSE); // rep_date
		$this->BuildSearchSql($sWhere, $this->rep_name, $Default, FALSE); // rep_name
		$this->BuildSearchSql($sWhere, $this->outward_datetime, $Default, FALSE); // outward_datetime
		$this->BuildSearchSql($sWhere, $this->rep_action, $Default, FALSE); // rep_action
		$this->BuildSearchSql($sWhere, $this->rep_comment, $Default, FALSE); // rep_comment

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->code->AdvancedSearch->Save(); // code
			$this->date->AdvancedSearch->Save(); // date
			$this->reference->AdvancedSearch->Save(); // reference
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->outward_location->AdvancedSearch->Save(); // outward_location
			$this->delivery_point->AdvancedSearch->Save(); // delivery_point
			$this->name->AdvancedSearch->Save(); // name
			$this->organization->AdvancedSearch->Save(); // organization
			$this->designation->AdvancedSearch->Save(); // designation
			$this->department->AdvancedSearch->Save(); // department
			$this->item_description->AdvancedSearch->Save(); // item_description
			$this->driver_name->AdvancedSearch->Save(); // driver_name
			$this->vehicle_no->AdvancedSearch->Save(); // vehicle_no
			$this->requester_action->AdvancedSearch->Save(); // requester_action
			$this->requester_comment->AdvancedSearch->Save(); // requester_comment
			$this->date_authorized->AdvancedSearch->Save(); // date_authorized
			$this->authorizer_name->AdvancedSearch->Save(); // authorizer_name
			$this->authorizer_action->AdvancedSearch->Save(); // authorizer_action
			$this->authorizer_comment->AdvancedSearch->Save(); // authorizer_comment
			$this->status->AdvancedSearch->Save(); // status
			$this->rep_date->AdvancedSearch->Save(); // rep_date
			$this->rep_name->AdvancedSearch->Save(); // rep_name
			$this->outward_datetime->AdvancedSearch->Save(); // outward_datetime
			$this->rep_action->AdvancedSearch->Save(); // rep_action
			$this->rep_comment->AdvancedSearch->Save(); // rep_comment
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
		$this->BuildBasicSearchSQL($sWhere, $this->reference, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->outward_location, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->delivery_point, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->organization, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->designation, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->department, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->item_description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->driver_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->vehicle_no, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->requester_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->authorizer_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->authorizer_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rep_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rep_comment, $arKeywords, $type);
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
		if ($this->code->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reference->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->outward_location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->delivery_point->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->organization->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->designation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->item_description->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->driver_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vehicle_no->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->requester_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->requester_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_authorized->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->authorizer_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->authorizer_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->authorizer_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rep_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rep_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->outward_datetime->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rep_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rep_comment->AdvancedSearch->IssetSession())
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
		$this->code->AdvancedSearch->UnsetSession();
		$this->date->AdvancedSearch->UnsetSession();
		$this->reference->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->outward_location->AdvancedSearch->UnsetSession();
		$this->delivery_point->AdvancedSearch->UnsetSession();
		$this->name->AdvancedSearch->UnsetSession();
		$this->organization->AdvancedSearch->UnsetSession();
		$this->designation->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->item_description->AdvancedSearch->UnsetSession();
		$this->driver_name->AdvancedSearch->UnsetSession();
		$this->vehicle_no->AdvancedSearch->UnsetSession();
		$this->requester_action->AdvancedSearch->UnsetSession();
		$this->requester_comment->AdvancedSearch->UnsetSession();
		$this->date_authorized->AdvancedSearch->UnsetSession();
		$this->authorizer_name->AdvancedSearch->UnsetSession();
		$this->authorizer_action->AdvancedSearch->UnsetSession();
		$this->authorizer_comment->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->rep_date->AdvancedSearch->UnsetSession();
		$this->rep_name->AdvancedSearch->UnsetSession();
		$this->outward_datetime->AdvancedSearch->UnsetSession();
		$this->rep_action->AdvancedSearch->UnsetSession();
		$this->rep_comment->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
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

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->date); // date
			$this->UpdateSort($this->reference); // reference
			$this->UpdateSort($this->outward_location); // outward_location
			$this->UpdateSort($this->delivery_point); // delivery_point
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->organization); // organization
			$this->UpdateSort($this->designation); // designation
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->item_description); // item_description
			$this->UpdateSort($this->driver_name); // driver_name
			$this->UpdateSort($this->vehicle_no); // vehicle_no
			$this->UpdateSort($this->authorizer_name); // authorizer_name
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
				$this->date->setSort("");
				$this->reference->setSort("");
				$this->outward_location->setSort("");
				$this->delivery_point->setSort("");
				$this->name->setSort("");
				$this->organization->setSort("");
				$this->designation->setSort("");
				$this->department->setSort("");
				$this->item_description->setSort("");
				$this->driver_name->setSort("");
				$this->vehicle_no->setSort("");
				$this->authorizer_name->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"requisition_report\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"frequisition_reportlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"frequisition_reportlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.frequisition_reportlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"frequisition_reportlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"requisition_reportsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"frequisition_reportlistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
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
		// code

		$this->code->AdvancedSearch->SearchValue = @$_GET["x_code"];
		if ($this->code->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->code->AdvancedSearch->SearchOperator = @$_GET["z_code"];

		// date
		$this->date->AdvancedSearch->SearchValue = @$_GET["x_date"];
		if ($this->date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date->AdvancedSearch->SearchOperator = @$_GET["z_date"];
		$this->date->AdvancedSearch->SearchCondition = @$_GET["v_date"];
		$this->date->AdvancedSearch->SearchValue2 = @$_GET["y_date"];
		if ($this->date->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->date->AdvancedSearch->SearchOperator2 = @$_GET["w_date"];

		// reference
		$this->reference->AdvancedSearch->SearchValue = @$_GET["x_reference"];
		if ($this->reference->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reference->AdvancedSearch->SearchOperator = @$_GET["z_reference"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// outward_location
		$this->outward_location->AdvancedSearch->SearchValue = @$_GET["x_outward_location"];
		if ($this->outward_location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->outward_location->AdvancedSearch->SearchOperator = @$_GET["z_outward_location"];

		// delivery_point
		$this->delivery_point->AdvancedSearch->SearchValue = @$_GET["x_delivery_point"];
		if ($this->delivery_point->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->delivery_point->AdvancedSearch->SearchOperator = @$_GET["z_delivery_point"];

		// name
		$this->name->AdvancedSearch->SearchValue = @$_GET["x_name"];
		if ($this->name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->name->AdvancedSearch->SearchOperator = @$_GET["z_name"];

		// organization
		$this->organization->AdvancedSearch->SearchValue = @$_GET["x_organization"];
		if ($this->organization->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->organization->AdvancedSearch->SearchOperator = @$_GET["z_organization"];

		// designation
		$this->designation->AdvancedSearch->SearchValue = @$_GET["x_designation"];
		if ($this->designation->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->designation->AdvancedSearch->SearchOperator = @$_GET["z_designation"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// item_description
		$this->item_description->AdvancedSearch->SearchValue = @$_GET["x_item_description"];
		if ($this->item_description->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->item_description->AdvancedSearch->SearchOperator = @$_GET["z_item_description"];

		// driver_name
		$this->driver_name->AdvancedSearch->SearchValue = @$_GET["x_driver_name"];
		if ($this->driver_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->driver_name->AdvancedSearch->SearchOperator = @$_GET["z_driver_name"];

		// vehicle_no
		$this->vehicle_no->AdvancedSearch->SearchValue = @$_GET["x_vehicle_no"];
		if ($this->vehicle_no->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->vehicle_no->AdvancedSearch->SearchOperator = @$_GET["z_vehicle_no"];

		// requester_action
		$this->requester_action->AdvancedSearch->SearchValue = @$_GET["x_requester_action"];
		if ($this->requester_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->requester_action->AdvancedSearch->SearchOperator = @$_GET["z_requester_action"];

		// requester_comment
		$this->requester_comment->AdvancedSearch->SearchValue = @$_GET["x_requester_comment"];
		if ($this->requester_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->requester_comment->AdvancedSearch->SearchOperator = @$_GET["z_requester_comment"];

		// date_authorized
		$this->date_authorized->AdvancedSearch->SearchValue = @$_GET["x_date_authorized"];
		if ($this->date_authorized->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_authorized->AdvancedSearch->SearchOperator = @$_GET["z_date_authorized"];

		// authorizer_name
		$this->authorizer_name->AdvancedSearch->SearchValue = @$_GET["x_authorizer_name"];
		if ($this->authorizer_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->authorizer_name->AdvancedSearch->SearchOperator = @$_GET["z_authorizer_name"];

		// authorizer_action
		$this->authorizer_action->AdvancedSearch->SearchValue = @$_GET["x_authorizer_action"];
		if ($this->authorizer_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->authorizer_action->AdvancedSearch->SearchOperator = @$_GET["z_authorizer_action"];

		// authorizer_comment
		$this->authorizer_comment->AdvancedSearch->SearchValue = @$_GET["x_authorizer_comment"];
		if ($this->authorizer_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->authorizer_comment->AdvancedSearch->SearchOperator = @$_GET["z_authorizer_comment"];

		// status
		$this->status->AdvancedSearch->SearchValue = @$_GET["x_status"];
		if ($this->status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// rep_date
		$this->rep_date->AdvancedSearch->SearchValue = @$_GET["x_rep_date"];
		if ($this->rep_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->rep_date->AdvancedSearch->SearchOperator = @$_GET["z_rep_date"];

		// rep_name
		$this->rep_name->AdvancedSearch->SearchValue = @$_GET["x_rep_name"];
		if ($this->rep_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->rep_name->AdvancedSearch->SearchOperator = @$_GET["z_rep_name"];

		// outward_datetime
		$this->outward_datetime->AdvancedSearch->SearchValue = @$_GET["x_outward_datetime"];
		if ($this->outward_datetime->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->outward_datetime->AdvancedSearch->SearchOperator = @$_GET["z_outward_datetime"];

		// rep_action
		$this->rep_action->AdvancedSearch->SearchValue = @$_GET["x_rep_action"];
		if ($this->rep_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->rep_action->AdvancedSearch->SearchOperator = @$_GET["z_rep_action"];

		// rep_comment
		$this->rep_comment->AdvancedSearch->SearchValue = @$_GET["x_rep_comment"];
		if ($this->rep_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->rep_comment->AdvancedSearch->SearchOperator = @$_GET["z_rep_comment"];
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
		$this->date->setDbValue($row['date']);
		$this->reference->setDbValue($row['reference']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->outward_location->setDbValue($row['outward_location']);
		$this->delivery_point->setDbValue($row['delivery_point']);
		$this->name->setDbValue($row['name']);
		$this->organization->setDbValue($row['organization']);
		$this->designation->setDbValue($row['designation']);
		$this->department->setDbValue($row['department']);
		$this->item_description->setDbValue($row['item_description']);
		$this->driver_name->setDbValue($row['driver_name']);
		$this->vehicle_no->setDbValue($row['vehicle_no']);
		$this->requester_action->setDbValue($row['requester_action']);
		$this->requester_comment->setDbValue($row['requester_comment']);
		$this->date_authorized->setDbValue($row['date_authorized']);
		$this->authorizer_name->setDbValue($row['authorizer_name']);
		$this->authorizer_action->setDbValue($row['authorizer_action']);
		$this->authorizer_comment->setDbValue($row['authorizer_comment']);
		$this->status->setDbValue($row['status']);
		$this->rep_date->setDbValue($row['rep_date']);
		$this->rep_name->setDbValue($row['rep_name']);
		$this->outward_datetime->setDbValue($row['outward_datetime']);
		$this->rep_action->setDbValue($row['rep_action']);
		$this->rep_comment->setDbValue($row['rep_comment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['date'] = NULL;
		$row['reference'] = NULL;
		$row['staff_id'] = NULL;
		$row['outward_location'] = NULL;
		$row['delivery_point'] = NULL;
		$row['name'] = NULL;
		$row['organization'] = NULL;
		$row['designation'] = NULL;
		$row['department'] = NULL;
		$row['item_description'] = NULL;
		$row['driver_name'] = NULL;
		$row['vehicle_no'] = NULL;
		$row['requester_action'] = NULL;
		$row['requester_comment'] = NULL;
		$row['date_authorized'] = NULL;
		$row['authorizer_name'] = NULL;
		$row['authorizer_action'] = NULL;
		$row['authorizer_comment'] = NULL;
		$row['status'] = NULL;
		$row['rep_date'] = NULL;
		$row['rep_name'] = NULL;
		$row['outward_datetime'] = NULL;
		$row['rep_action'] = NULL;
		$row['rep_comment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date->DbValue = $row['date'];
		$this->reference->DbValue = $row['reference'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->outward_location->DbValue = $row['outward_location'];
		$this->delivery_point->DbValue = $row['delivery_point'];
		$this->name->DbValue = $row['name'];
		$this->organization->DbValue = $row['organization'];
		$this->designation->DbValue = $row['designation'];
		$this->department->DbValue = $row['department'];
		$this->item_description->DbValue = $row['item_description'];
		$this->driver_name->DbValue = $row['driver_name'];
		$this->vehicle_no->DbValue = $row['vehicle_no'];
		$this->requester_action->DbValue = $row['requester_action'];
		$this->requester_comment->DbValue = $row['requester_comment'];
		$this->date_authorized->DbValue = $row['date_authorized'];
		$this->authorizer_name->DbValue = $row['authorizer_name'];
		$this->authorizer_action->DbValue = $row['authorizer_action'];
		$this->authorizer_comment->DbValue = $row['authorizer_comment'];
		$this->status->DbValue = $row['status'];
		$this->rep_date->DbValue = $row['rep_date'];
		$this->rep_name->DbValue = $row['rep_name'];
		$this->outward_datetime->DbValue = $row['outward_datetime'];
		$this->rep_action->DbValue = $row['rep_action'];
		$this->rep_comment->DbValue = $row['rep_comment'];
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
		if (strval($this->organization->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->organization->LookupFilters = array("dx1" => '`branch_name`');
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

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";
			$this->reference->TooltipValue = "";
			if ($this->Export == "")
				$this->reference->ViewValue = $this->HighlightValue($this->reference);

			// outward_location
			$this->outward_location->LinkCustomAttributes = "";
			$this->outward_location->HrefValue = "";
			$this->outward_location->TooltipValue = "";
			if ($this->Export == "")
				$this->outward_location->ViewValue = $this->HighlightValue($this->outward_location);

			// delivery_point
			$this->delivery_point->LinkCustomAttributes = "";
			$this->delivery_point->HrefValue = "";
			$this->delivery_point->TooltipValue = "";
			if ($this->Export == "")
				$this->delivery_point->ViewValue = $this->HighlightValue($this->delivery_point);

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
			if ($this->Export == "")
				$this->designation->ViewValue = $this->HighlightValue($this->designation);

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// item_description
			$this->item_description->LinkCustomAttributes = "";
			$this->item_description->HrefValue = "";
			$this->item_description->TooltipValue = "";
			if ($this->Export == "")
				$this->item_description->ViewValue = $this->HighlightValue($this->item_description);

			// driver_name
			$this->driver_name->LinkCustomAttributes = "";
			$this->driver_name->HrefValue = "";
			$this->driver_name->TooltipValue = "";
			if ($this->Export == "")
				$this->driver_name->ViewValue = $this->HighlightValue($this->driver_name);

			// vehicle_no
			$this->vehicle_no->LinkCustomAttributes = "";
			$this->vehicle_no->HrefValue = "";
			$this->vehicle_no->TooltipValue = "";
			if ($this->Export == "")
				$this->vehicle_no->ViewValue = $this->HighlightValue($this->vehicle_no);

			// authorizer_name
			$this->authorizer_name->LinkCustomAttributes = "";
			$this->authorizer_name->HrefValue = "";
			$this->authorizer_name->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
			if ($this->Export == "")
				$this->status->ViewValue = $this->HighlightValue($this->status);
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

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
			$this->organization->EditCustomAttributes = "";
			if (trim(strval($this->organization->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `branch`";
			$sWhereWrk = "";
			$this->organization->LookupFilters = array("dx1" => '`branch_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->organization->AdvancedSearch->ViewValue = $this->organization->DisplayValue($arwrk);
			} else {
				$this->organization->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->organization->EditValue = $arwrk;

			// designation
			$this->designation->EditAttrs["class"] = "form-control";
			$this->designation->EditCustomAttributes = "";
			$this->designation->EditValue = ew_HtmlEncode($this->designation->AdvancedSearch->SearchValue);
			$this->designation->PlaceHolder = ew_RemoveHtml($this->designation->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";

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

			// authorizer_name
			$this->authorizer_name->EditAttrs["class"] = "form-control";
			$this->authorizer_name->EditCustomAttributes = "";

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());
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
		$item->Body = "<button id=\"emf_requisition_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_requisition_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.frequisition_reportlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
				$fld->LookupFilters = array("dx1" => '`branch_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
				$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($requisition_report_list)) $requisition_report_list = new crequisition_report_list();

// Page init
$requisition_report_list->Page_Init();

// Page main
$requisition_report_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$requisition_report_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($requisition_report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = frequisition_reportlist = new ew_Form("frequisition_reportlist", "list");
frequisition_reportlist.FormKeyCountName = '<?php echo $requisition_report_list->FormKeyCountName ?>';

// Form_CustomValidate event
frequisition_reportlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frequisition_reportlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frequisition_reportlist.Lists["x_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportlist.Lists["x_name"].Data = "<?php echo $requisition_report_list->name->LookupFilterQuery(FALSE, "list") ?>";
frequisition_reportlist.Lists["x_organization"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
frequisition_reportlist.Lists["x_organization"].Data = "<?php echo $requisition_report_list->organization->LookupFilterQuery(FALSE, "list") ?>";
frequisition_reportlist.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
frequisition_reportlist.Lists["x_designation"].Data = "<?php echo $requisition_report_list->designation->LookupFilterQuery(FALSE, "list") ?>";
frequisition_reportlist.AutoSuggests["x_designation"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_list->designation->LookupFilterQuery(TRUE, "list"))) ?>;
frequisition_reportlist.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
frequisition_reportlist.Lists["x_department"].Data = "<?php echo $requisition_report_list->department->LookupFilterQuery(FALSE, "list") ?>";
frequisition_reportlist.Lists["x_authorizer_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportlist.Lists["x_authorizer_name"].Data = "<?php echo $requisition_report_list->authorizer_name->LookupFilterQuery(FALSE, "list") ?>";
frequisition_reportlist.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status_ssf"};
frequisition_reportlist.Lists["x_status"].Data = "<?php echo $requisition_report_list->status->LookupFilterQuery(FALSE, "list") ?>";
frequisition_reportlist.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_list->status->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = frequisition_reportlistsrch = new ew_Form("frequisition_reportlistsrch");

// Validate function for search
frequisition_reportlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
frequisition_reportlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frequisition_reportlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frequisition_reportlistsrch.Lists["x_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportlistsrch.Lists["x_name"].Data = "<?php echo $requisition_report_list->name->LookupFilterQuery(FALSE, "extbs") ?>";
frequisition_reportlistsrch.Lists["x_organization"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
frequisition_reportlistsrch.Lists["x_organization"].Data = "<?php echo $requisition_report_list->organization->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
<div class="ewToolbar">
<?php if ($requisition_report_list->TotalRecs > 0 && $requisition_report_list->ExportOptions->Visible()) { ?>
<?php $requisition_report_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($requisition_report_list->SearchOptions->Visible()) { ?>
<?php $requisition_report_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($requisition_report_list->FilterOptions->Visible()) { ?>
<?php $requisition_report_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $requisition_report_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($requisition_report_list->TotalRecs <= 0)
			$requisition_report_list->TotalRecs = $requisition_report->ListRecordCount();
	} else {
		if (!$requisition_report_list->Recordset && ($requisition_report_list->Recordset = $requisition_report_list->LoadRecordset()))
			$requisition_report_list->TotalRecs = $requisition_report_list->Recordset->RecordCount();
	}
	$requisition_report_list->StartRec = 1;
	if ($requisition_report_list->DisplayRecs <= 0 || ($requisition_report->Export <> "" && $requisition_report->ExportAll)) // Display all records
		$requisition_report_list->DisplayRecs = $requisition_report_list->TotalRecs;
	if (!($requisition_report->Export <> "" && $requisition_report->ExportAll))
		$requisition_report_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$requisition_report_list->Recordset = $requisition_report_list->LoadRecordset($requisition_report_list->StartRec-1, $requisition_report_list->DisplayRecs);

	// Set no record found message
	if ($requisition_report->CurrentAction == "" && $requisition_report_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$requisition_report_list->setWarningMessage(ew_DeniedMsg());
		if ($requisition_report_list->SearchWhere == "0=101")
			$requisition_report_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$requisition_report_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($requisition_report_list->AuditTrailOnSearch && $requisition_report_list->Command == "search" && !$requisition_report_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $requisition_report_list->getSessionWhere();
		$requisition_report_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$requisition_report_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($requisition_report->Export == "" && $requisition_report->CurrentAction == "") { ?>
<form name="frequisition_reportlistsrch" id="frequisition_reportlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($requisition_report_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="frequisition_reportlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="requisition_report">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$requisition_report_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$requisition_report->RowType = EW_ROWTYPE_SEARCH;

// Render row
$requisition_report->ResetAttrs();
$requisition_report_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($requisition_report->date->Visible) { // date ?>
	<div id="xsc_date" class="ewCell form-group">
		<label for="x_date" class="ewSearchCaption ewLabel"><?php echo $requisition_report->date->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date" id="z_date" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="requisition_report" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($requisition_report->date->getPlaceHolder()) ?>" value="<?php echo $requisition_report->date->EditValue ?>"<?php echo $requisition_report->date->EditAttributes() ?>>
<?php if (!$requisition_report->date->ReadOnly && !$requisition_report->date->Disabled && !isset($requisition_report->date->EditAttrs["readonly"]) && !isset($requisition_report->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("frequisition_reportlistsrch", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_date">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_date">
<input type="text" data-table="requisition_report" data-field="x_date" name="y_date" id="y_date" placeholder="<?php echo ew_HtmlEncode($requisition_report->date->getPlaceHolder()) ?>" value="<?php echo $requisition_report->date->EditValue2 ?>"<?php echo $requisition_report->date->EditAttributes() ?>>
<?php if (!$requisition_report->date->ReadOnly && !$requisition_report->date->Disabled && !isset($requisition_report->date->EditAttrs["readonly"]) && !isset($requisition_report->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("frequisition_reportlistsrch", "y_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($requisition_report->reference->Visible) { // reference ?>
	<div id="xsc_reference" class="ewCell form-group">
		<label for="x_reference" class="ewSearchCaption ewLabel"><?php echo $requisition_report->reference->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference" id="z_reference" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="requisition_report" data-field="x_reference" name="x_reference" id="x_reference" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_report->reference->getPlaceHolder()) ?>" value="<?php echo $requisition_report->reference->EditValue ?>"<?php echo $requisition_report->reference->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($requisition_report->name->Visible) { // name ?>
	<div id="xsc_name" class="ewCell form-group">
		<label for="x_name" class="ewSearchCaption ewLabel"><?php echo $requisition_report->name->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_name" id="z_name" value="LIKE"></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_name"><?php echo (strval($requisition_report->name->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $requisition_report->name->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($requisition_report->name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($requisition_report->name->ReadOnly || $requisition_report->name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="requisition_report" data-field="x_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $requisition_report->name->DisplayValueSeparatorAttribute() ?>" name="x_name" id="x_name" value="<?php echo $requisition_report->name->AdvancedSearch->SearchValue ?>"<?php echo $requisition_report->name->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($requisition_report->organization->Visible) { // organization ?>
	<div id="xsc_organization" class="ewCell form-group">
		<label for="x_organization" class="ewSearchCaption ewLabel"><?php echo $requisition_report->organization->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_organization" id="z_organization" value="LIKE"></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_organization"><?php echo (strval($requisition_report->organization->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $requisition_report->organization->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($requisition_report->organization->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_organization',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($requisition_report->organization->ReadOnly || $requisition_report->organization->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="requisition_report" data-field="x_organization" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $requisition_report->organization->DisplayValueSeparatorAttribute() ?>" name="x_organization" id="x_organization" value="<?php echo $requisition_report->organization->AdvancedSearch->SearchValue ?>"<?php echo $requisition_report->organization->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($requisition_report_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($requisition_report_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $requisition_report_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($requisition_report_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($requisition_report_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($requisition_report_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($requisition_report_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $requisition_report_list->ShowPageHeader(); ?>
<?php
$requisition_report_list->ShowMessage();
?>
<?php if ($requisition_report_list->TotalRecs > 0 || $requisition_report->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($requisition_report_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> requisition_report">
<?php if ($requisition_report->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($requisition_report->CurrentAction <> "gridadd" && $requisition_report->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($requisition_report_list->Pager)) $requisition_report_list->Pager = new cPrevNextPager($requisition_report_list->StartRec, $requisition_report_list->DisplayRecs, $requisition_report_list->TotalRecs, $requisition_report_list->AutoHidePager) ?>
<?php if ($requisition_report_list->Pager->RecordCount > 0 && $requisition_report_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($requisition_report_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $requisition_report_list->PageUrl() ?>start=<?php echo $requisition_report_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($requisition_report_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $requisition_report_list->PageUrl() ?>start=<?php echo $requisition_report_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $requisition_report_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($requisition_report_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $requisition_report_list->PageUrl() ?>start=<?php echo $requisition_report_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($requisition_report_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $requisition_report_list->PageUrl() ?>start=<?php echo $requisition_report_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $requisition_report_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($requisition_report_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $requisition_report_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $requisition_report_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $requisition_report_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($requisition_report_list->TotalRecs > 0 && (!$requisition_report_list->AutoHidePageSizeSelector || $requisition_report_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="requisition_report">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($requisition_report_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($requisition_report_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($requisition_report_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($requisition_report_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($requisition_report_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($requisition_report->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($requisition_report_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="frequisition_reportlist" id="frequisition_reportlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($requisition_report_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $requisition_report_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="requisition_report">
<div id="gmp_requisition_report" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($requisition_report_list->TotalRecs > 0 || $requisition_report->CurrentAction == "gridedit") { ?>
<table id="tbl_requisition_reportlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$requisition_report_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$requisition_report_list->RenderListOptions();

// Render list options (header, left)
$requisition_report_list->ListOptions->Render("header", "left");
?>
<?php if ($requisition_report->date->Visible) { // date ?>
	<?php if ($requisition_report->SortUrl($requisition_report->date) == "") { ?>
		<th data-name="date" class="<?php echo $requisition_report->date->HeaderCellClass() ?>"><div id="elh_requisition_report_date" class="requisition_report_date"><div class="ewTableHeaderCaption"><?php echo $requisition_report->date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date" class="<?php echo $requisition_report->date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->date) ?>',1);"><div id="elh_requisition_report_date" class="requisition_report_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->reference->Visible) { // reference ?>
	<?php if ($requisition_report->SortUrl($requisition_report->reference) == "") { ?>
		<th data-name="reference" class="<?php echo $requisition_report->reference->HeaderCellClass() ?>"><div id="elh_requisition_report_reference" class="requisition_report_reference"><div class="ewTableHeaderCaption"><?php echo $requisition_report->reference->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reference" class="<?php echo $requisition_report->reference->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->reference) ?>',1);"><div id="elh_requisition_report_reference" class="requisition_report_reference">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->reference->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->reference->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->reference->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->outward_location->Visible) { // outward_location ?>
	<?php if ($requisition_report->SortUrl($requisition_report->outward_location) == "") { ?>
		<th data-name="outward_location" class="<?php echo $requisition_report->outward_location->HeaderCellClass() ?>"><div id="elh_requisition_report_outward_location" class="requisition_report_outward_location"><div class="ewTableHeaderCaption"><?php echo $requisition_report->outward_location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="outward_location" class="<?php echo $requisition_report->outward_location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->outward_location) ?>',1);"><div id="elh_requisition_report_outward_location" class="requisition_report_outward_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->outward_location->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->outward_location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->outward_location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->delivery_point->Visible) { // delivery_point ?>
	<?php if ($requisition_report->SortUrl($requisition_report->delivery_point) == "") { ?>
		<th data-name="delivery_point" class="<?php echo $requisition_report->delivery_point->HeaderCellClass() ?>"><div id="elh_requisition_report_delivery_point" class="requisition_report_delivery_point"><div class="ewTableHeaderCaption"><?php echo $requisition_report->delivery_point->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="delivery_point" class="<?php echo $requisition_report->delivery_point->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->delivery_point) ?>',1);"><div id="elh_requisition_report_delivery_point" class="requisition_report_delivery_point">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->delivery_point->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->delivery_point->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->delivery_point->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->name->Visible) { // name ?>
	<?php if ($requisition_report->SortUrl($requisition_report->name) == "") { ?>
		<th data-name="name" class="<?php echo $requisition_report->name->HeaderCellClass() ?>"><div id="elh_requisition_report_name" class="requisition_report_name"><div class="ewTableHeaderCaption"><?php echo $requisition_report->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name" class="<?php echo $requisition_report->name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->name) ?>',1);"><div id="elh_requisition_report_name" class="requisition_report_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->organization->Visible) { // organization ?>
	<?php if ($requisition_report->SortUrl($requisition_report->organization) == "") { ?>
		<th data-name="organization" class="<?php echo $requisition_report->organization->HeaderCellClass() ?>"><div id="elh_requisition_report_organization" class="requisition_report_organization"><div class="ewTableHeaderCaption"><?php echo $requisition_report->organization->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="organization" class="<?php echo $requisition_report->organization->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->organization) ?>',1);"><div id="elh_requisition_report_organization" class="requisition_report_organization">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->organization->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->organization->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->organization->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->designation->Visible) { // designation ?>
	<?php if ($requisition_report->SortUrl($requisition_report->designation) == "") { ?>
		<th data-name="designation" class="<?php echo $requisition_report->designation->HeaderCellClass() ?>"><div id="elh_requisition_report_designation" class="requisition_report_designation"><div class="ewTableHeaderCaption"><?php echo $requisition_report->designation->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="designation" class="<?php echo $requisition_report->designation->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->designation) ?>',1);"><div id="elh_requisition_report_designation" class="requisition_report_designation">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->designation->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->designation->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->designation->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->department->Visible) { // department ?>
	<?php if ($requisition_report->SortUrl($requisition_report->department) == "") { ?>
		<th data-name="department" class="<?php echo $requisition_report->department->HeaderCellClass() ?>"><div id="elh_requisition_report_department" class="requisition_report_department"><div class="ewTableHeaderCaption"><?php echo $requisition_report->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $requisition_report->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->department) ?>',1);"><div id="elh_requisition_report_department" class="requisition_report_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->item_description->Visible) { // item_description ?>
	<?php if ($requisition_report->SortUrl($requisition_report->item_description) == "") { ?>
		<th data-name="item_description" class="<?php echo $requisition_report->item_description->HeaderCellClass() ?>"><div id="elh_requisition_report_item_description" class="requisition_report_item_description"><div class="ewTableHeaderCaption"><?php echo $requisition_report->item_description->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="item_description" class="<?php echo $requisition_report->item_description->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->item_description) ?>',1);"><div id="elh_requisition_report_item_description" class="requisition_report_item_description">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->item_description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->item_description->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->item_description->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->driver_name->Visible) { // driver_name ?>
	<?php if ($requisition_report->SortUrl($requisition_report->driver_name) == "") { ?>
		<th data-name="driver_name" class="<?php echo $requisition_report->driver_name->HeaderCellClass() ?>"><div id="elh_requisition_report_driver_name" class="requisition_report_driver_name"><div class="ewTableHeaderCaption"><?php echo $requisition_report->driver_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="driver_name" class="<?php echo $requisition_report->driver_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->driver_name) ?>',1);"><div id="elh_requisition_report_driver_name" class="requisition_report_driver_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->driver_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->driver_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->driver_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->vehicle_no->Visible) { // vehicle_no ?>
	<?php if ($requisition_report->SortUrl($requisition_report->vehicle_no) == "") { ?>
		<th data-name="vehicle_no" class="<?php echo $requisition_report->vehicle_no->HeaderCellClass() ?>"><div id="elh_requisition_report_vehicle_no" class="requisition_report_vehicle_no"><div class="ewTableHeaderCaption"><?php echo $requisition_report->vehicle_no->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vehicle_no" class="<?php echo $requisition_report->vehicle_no->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->vehicle_no) ?>',1);"><div id="elh_requisition_report_vehicle_no" class="requisition_report_vehicle_no">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->vehicle_no->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->vehicle_no->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->vehicle_no->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->authorizer_name->Visible) { // authorizer_name ?>
	<?php if ($requisition_report->SortUrl($requisition_report->authorizer_name) == "") { ?>
		<th data-name="authorizer_name" class="<?php echo $requisition_report->authorizer_name->HeaderCellClass() ?>"><div id="elh_requisition_report_authorizer_name" class="requisition_report_authorizer_name"><div class="ewTableHeaderCaption"><?php echo $requisition_report->authorizer_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="authorizer_name" class="<?php echo $requisition_report->authorizer_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->authorizer_name) ?>',1);"><div id="elh_requisition_report_authorizer_name" class="requisition_report_authorizer_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->authorizer_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->authorizer_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->authorizer_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($requisition_report->status->Visible) { // status ?>
	<?php if ($requisition_report->SortUrl($requisition_report->status) == "") { ?>
		<th data-name="status" class="<?php echo $requisition_report->status->HeaderCellClass() ?>"><div id="elh_requisition_report_status" class="requisition_report_status"><div class="ewTableHeaderCaption"><?php echo $requisition_report->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $requisition_report->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $requisition_report->SortUrl($requisition_report->status) ?>',1);"><div id="elh_requisition_report_status" class="requisition_report_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $requisition_report->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($requisition_report->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($requisition_report->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$requisition_report_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($requisition_report->ExportAll && $requisition_report->Export <> "") {
	$requisition_report_list->StopRec = $requisition_report_list->TotalRecs;
} else {

	// Set the last record to display
	if ($requisition_report_list->TotalRecs > $requisition_report_list->StartRec + $requisition_report_list->DisplayRecs - 1)
		$requisition_report_list->StopRec = $requisition_report_list->StartRec + $requisition_report_list->DisplayRecs - 1;
	else
		$requisition_report_list->StopRec = $requisition_report_list->TotalRecs;
}
$requisition_report_list->RecCnt = $requisition_report_list->StartRec - 1;
if ($requisition_report_list->Recordset && !$requisition_report_list->Recordset->EOF) {
	$requisition_report_list->Recordset->MoveFirst();
	$bSelectLimit = $requisition_report_list->UseSelectLimit;
	if (!$bSelectLimit && $requisition_report_list->StartRec > 1)
		$requisition_report_list->Recordset->Move($requisition_report_list->StartRec - 1);
} elseif (!$requisition_report->AllowAddDeleteRow && $requisition_report_list->StopRec == 0) {
	$requisition_report_list->StopRec = $requisition_report->GridAddRowCount;
}

// Initialize aggregate
$requisition_report->RowType = EW_ROWTYPE_AGGREGATEINIT;
$requisition_report->ResetAttrs();
$requisition_report_list->RenderRow();
while ($requisition_report_list->RecCnt < $requisition_report_list->StopRec) {
	$requisition_report_list->RecCnt++;
	if (intval($requisition_report_list->RecCnt) >= intval($requisition_report_list->StartRec)) {
		$requisition_report_list->RowCnt++;

		// Set up key count
		$requisition_report_list->KeyCount = $requisition_report_list->RowIndex;

		// Init row class and style
		$requisition_report->ResetAttrs();
		$requisition_report->CssClass = "";
		if ($requisition_report->CurrentAction == "gridadd") {
		} else {
			$requisition_report_list->LoadRowValues($requisition_report_list->Recordset); // Load row values
		}
		$requisition_report->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$requisition_report->RowAttrs = array_merge($requisition_report->RowAttrs, array('data-rowindex'=>$requisition_report_list->RowCnt, 'id'=>'r' . $requisition_report_list->RowCnt . '_requisition_report', 'data-rowtype'=>$requisition_report->RowType));

		// Render row
		$requisition_report_list->RenderRow();

		// Render list options
		$requisition_report_list->RenderListOptions();
?>
	<tr<?php echo $requisition_report->RowAttributes() ?>>
<?php

// Render list options (body, left)
$requisition_report_list->ListOptions->Render("body", "left", $requisition_report_list->RowCnt);
?>
	<?php if ($requisition_report->date->Visible) { // date ?>
		<td data-name="date"<?php echo $requisition_report->date->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_date" class="requisition_report_date">
<span<?php echo $requisition_report->date->ViewAttributes() ?>>
<?php echo $requisition_report->date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->reference->Visible) { // reference ?>
		<td data-name="reference"<?php echo $requisition_report->reference->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_reference" class="requisition_report_reference">
<span<?php echo $requisition_report->reference->ViewAttributes() ?>>
<?php echo $requisition_report->reference->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->outward_location->Visible) { // outward_location ?>
		<td data-name="outward_location"<?php echo $requisition_report->outward_location->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_outward_location" class="requisition_report_outward_location">
<span<?php echo $requisition_report->outward_location->ViewAttributes() ?>>
<?php echo $requisition_report->outward_location->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->delivery_point->Visible) { // delivery_point ?>
		<td data-name="delivery_point"<?php echo $requisition_report->delivery_point->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_delivery_point" class="requisition_report_delivery_point">
<span<?php echo $requisition_report->delivery_point->ViewAttributes() ?>>
<?php echo $requisition_report->delivery_point->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->name->Visible) { // name ?>
		<td data-name="name"<?php echo $requisition_report->name->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_name" class="requisition_report_name">
<span<?php echo $requisition_report->name->ViewAttributes() ?>>
<?php echo $requisition_report->name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->organization->Visible) { // organization ?>
		<td data-name="organization"<?php echo $requisition_report->organization->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_organization" class="requisition_report_organization">
<span<?php echo $requisition_report->organization->ViewAttributes() ?>>
<?php echo $requisition_report->organization->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->designation->Visible) { // designation ?>
		<td data-name="designation"<?php echo $requisition_report->designation->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_designation" class="requisition_report_designation">
<span<?php echo $requisition_report->designation->ViewAttributes() ?>>
<?php echo $requisition_report->designation->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->department->Visible) { // department ?>
		<td data-name="department"<?php echo $requisition_report->department->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_department" class="requisition_report_department">
<span<?php echo $requisition_report->department->ViewAttributes() ?>>
<?php echo $requisition_report->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->item_description->Visible) { // item_description ?>
		<td data-name="item_description"<?php echo $requisition_report->item_description->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_item_description" class="requisition_report_item_description">
<span<?php echo $requisition_report->item_description->ViewAttributes() ?>>
<?php echo $requisition_report->item_description->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->driver_name->Visible) { // driver_name ?>
		<td data-name="driver_name"<?php echo $requisition_report->driver_name->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_driver_name" class="requisition_report_driver_name">
<span<?php echo $requisition_report->driver_name->ViewAttributes() ?>>
<?php echo $requisition_report->driver_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->vehicle_no->Visible) { // vehicle_no ?>
		<td data-name="vehicle_no"<?php echo $requisition_report->vehicle_no->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_vehicle_no" class="requisition_report_vehicle_no">
<span<?php echo $requisition_report->vehicle_no->ViewAttributes() ?>>
<?php echo $requisition_report->vehicle_no->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->authorizer_name->Visible) { // authorizer_name ?>
		<td data-name="authorizer_name"<?php echo $requisition_report->authorizer_name->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_authorizer_name" class="requisition_report_authorizer_name">
<span<?php echo $requisition_report->authorizer_name->ViewAttributes() ?>>
<?php echo $requisition_report->authorizer_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($requisition_report->status->Visible) { // status ?>
		<td data-name="status"<?php echo $requisition_report->status->CellAttributes() ?>>
<span id="el<?php echo $requisition_report_list->RowCnt ?>_requisition_report_status" class="requisition_report_status">
<span<?php echo $requisition_report->status->ViewAttributes() ?>>
<?php echo $requisition_report->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$requisition_report_list->ListOptions->Render("body", "right", $requisition_report_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($requisition_report->CurrentAction <> "gridadd")
		$requisition_report_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($requisition_report->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($requisition_report_list->Recordset)
	$requisition_report_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($requisition_report_list->TotalRecs == 0 && $requisition_report->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($requisition_report_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
<script type="text/javascript">
frequisition_reportlistsrch.FilterList = <?php echo $requisition_report_list->GetFilterList() ?>;
frequisition_reportlistsrch.Init();
frequisition_reportlist.Init();
</script>
<?php } ?>
<?php
$requisition_report_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($requisition_report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$requisition_report_list->Page_Terminate();
?>
