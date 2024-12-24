<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "inventory_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$inventory_report_list = NULL; // Initialize page object first

class cinventory_report_list extends cinventory_report {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'inventory_report';

	// Page object name
	var $PageObjName = 'inventory_report_list';

	// Grid form hidden field names
	var $FormName = 'finventory_reportlist';
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

		// Table object (inventory_report)
		if (!isset($GLOBALS["inventory_report"]) || get_class($GLOBALS["inventory_report"]) == "cinventory_report") {
			$GLOBALS["inventory_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventory_report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "inventory_reportadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "inventory_reportdelete.php";
		$this->MultiUpdateUrl = "inventory_reportupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory_report', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption finventory_reportlistsrch";

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
		$this->date_recieved->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity->SetVisibility();
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->recieved_by->SetVisibility();
		$this->statuss->SetVisibility();
		$this->recieved_action->SetVisibility();
		$this->approved_by->SetVisibility();
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
		global $EW_EXPORT, $inventory_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventory_report);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "finventory_reportlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->date_recieved->AdvancedSearch->ToJson(), ","); // Field date_recieved
		$sFilterList = ew_Concat($sFilterList, $this->reference_id->AdvancedSearch->ToJson(), ","); // Field reference_id
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->material_name->AdvancedSearch->ToJson(), ","); // Field material_name
		$sFilterList = ew_Concat($sFilterList, $this->quantity->AdvancedSearch->ToJson(), ","); // Field quantity
		$sFilterList = ew_Concat($sFilterList, $this->type->AdvancedSearch->ToJson(), ","); // Field type
		$sFilterList = ew_Concat($sFilterList, $this->capacity->AdvancedSearch->ToJson(), ","); // Field capacity
		$sFilterList = ew_Concat($sFilterList, $this->recieved_by->AdvancedSearch->ToJson(), ","); // Field recieved_by
		$sFilterList = ew_Concat($sFilterList, $this->statuss->AdvancedSearch->ToJson(), ","); // Field statuss
		$sFilterList = ew_Concat($sFilterList, $this->recieved_action->AdvancedSearch->ToJson(), ","); // Field recieved_action
		$sFilterList = ew_Concat($sFilterList, $this->recieved_comment->AdvancedSearch->ToJson(), ","); // Field recieved_comment
		$sFilterList = ew_Concat($sFilterList, $this->date_approved->AdvancedSearch->ToJson(), ","); // Field date_approved
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "finventory_reportlistsrch", $filters);

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

		// Field date_recieved
		$this->date_recieved->AdvancedSearch->SearchValue = @$filter["x_date_recieved"];
		$this->date_recieved->AdvancedSearch->SearchOperator = @$filter["z_date_recieved"];
		$this->date_recieved->AdvancedSearch->SearchCondition = @$filter["v_date_recieved"];
		$this->date_recieved->AdvancedSearch->SearchValue2 = @$filter["y_date_recieved"];
		$this->date_recieved->AdvancedSearch->SearchOperator2 = @$filter["w_date_recieved"];
		$this->date_recieved->AdvancedSearch->Save();

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

		// Field material_name
		$this->material_name->AdvancedSearch->SearchValue = @$filter["x_material_name"];
		$this->material_name->AdvancedSearch->SearchOperator = @$filter["z_material_name"];
		$this->material_name->AdvancedSearch->SearchCondition = @$filter["v_material_name"];
		$this->material_name->AdvancedSearch->SearchValue2 = @$filter["y_material_name"];
		$this->material_name->AdvancedSearch->SearchOperator2 = @$filter["w_material_name"];
		$this->material_name->AdvancedSearch->Save();

		// Field quantity
		$this->quantity->AdvancedSearch->SearchValue = @$filter["x_quantity"];
		$this->quantity->AdvancedSearch->SearchOperator = @$filter["z_quantity"];
		$this->quantity->AdvancedSearch->SearchCondition = @$filter["v_quantity"];
		$this->quantity->AdvancedSearch->SearchValue2 = @$filter["y_quantity"];
		$this->quantity->AdvancedSearch->SearchOperator2 = @$filter["w_quantity"];
		$this->quantity->AdvancedSearch->Save();

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

		// Field recieved_by
		$this->recieved_by->AdvancedSearch->SearchValue = @$filter["x_recieved_by"];
		$this->recieved_by->AdvancedSearch->SearchOperator = @$filter["z_recieved_by"];
		$this->recieved_by->AdvancedSearch->SearchCondition = @$filter["v_recieved_by"];
		$this->recieved_by->AdvancedSearch->SearchValue2 = @$filter["y_recieved_by"];
		$this->recieved_by->AdvancedSearch->SearchOperator2 = @$filter["w_recieved_by"];
		$this->recieved_by->AdvancedSearch->Save();

		// Field statuss
		$this->statuss->AdvancedSearch->SearchValue = @$filter["x_statuss"];
		$this->statuss->AdvancedSearch->SearchOperator = @$filter["z_statuss"];
		$this->statuss->AdvancedSearch->SearchCondition = @$filter["v_statuss"];
		$this->statuss->AdvancedSearch->SearchValue2 = @$filter["y_statuss"];
		$this->statuss->AdvancedSearch->SearchOperator2 = @$filter["w_statuss"];
		$this->statuss->AdvancedSearch->Save();

		// Field recieved_action
		$this->recieved_action->AdvancedSearch->SearchValue = @$filter["x_recieved_action"];
		$this->recieved_action->AdvancedSearch->SearchOperator = @$filter["z_recieved_action"];
		$this->recieved_action->AdvancedSearch->SearchCondition = @$filter["v_recieved_action"];
		$this->recieved_action->AdvancedSearch->SearchValue2 = @$filter["y_recieved_action"];
		$this->recieved_action->AdvancedSearch->SearchOperator2 = @$filter["w_recieved_action"];
		$this->recieved_action->AdvancedSearch->Save();

		// Field recieved_comment
		$this->recieved_comment->AdvancedSearch->SearchValue = @$filter["x_recieved_comment"];
		$this->recieved_comment->AdvancedSearch->SearchOperator = @$filter["z_recieved_comment"];
		$this->recieved_comment->AdvancedSearch->SearchCondition = @$filter["v_recieved_comment"];
		$this->recieved_comment->AdvancedSearch->SearchValue2 = @$filter["y_recieved_comment"];
		$this->recieved_comment->AdvancedSearch->SearchOperator2 = @$filter["w_recieved_comment"];
		$this->recieved_comment->AdvancedSearch->Save();

		// Field date_approved
		$this->date_approved->AdvancedSearch->SearchValue = @$filter["x_date_approved"];
		$this->date_approved->AdvancedSearch->SearchOperator = @$filter["z_date_approved"];
		$this->date_approved->AdvancedSearch->SearchCondition = @$filter["v_date_approved"];
		$this->date_approved->AdvancedSearch->SearchValue2 = @$filter["y_date_approved"];
		$this->date_approved->AdvancedSearch->SearchOperator2 = @$filter["w_date_approved"];
		$this->date_approved->AdvancedSearch->Save();

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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->date_recieved, $Default, FALSE); // date_recieved
		$this->BuildSearchSql($sWhere, $this->reference_id, $Default, FALSE); // reference_id
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->material_name, $Default, FALSE); // material_name
		$this->BuildSearchSql($sWhere, $this->quantity, $Default, FALSE); // quantity
		$this->BuildSearchSql($sWhere, $this->type, $Default, FALSE); // type
		$this->BuildSearchSql($sWhere, $this->capacity, $Default, FALSE); // capacity
		$this->BuildSearchSql($sWhere, $this->recieved_by, $Default, FALSE); // recieved_by
		$this->BuildSearchSql($sWhere, $this->statuss, $Default, FALSE); // statuss
		$this->BuildSearchSql($sWhere, $this->recieved_action, $Default, FALSE); // recieved_action
		$this->BuildSearchSql($sWhere, $this->recieved_comment, $Default, FALSE); // recieved_comment
		$this->BuildSearchSql($sWhere, $this->date_approved, $Default, FALSE); // date_approved
		$this->BuildSearchSql($sWhere, $this->approver_action, $Default, FALSE); // approver_action
		$this->BuildSearchSql($sWhere, $this->approver_comment, $Default, FALSE); // approver_comment
		$this->BuildSearchSql($sWhere, $this->approved_by, $Default, FALSE); // approved_by
		$this->BuildSearchSql($sWhere, $this->verified_date, $Default, FALSE); // verified_date
		$this->BuildSearchSql($sWhere, $this->verified_action, $Default, FALSE); // verified_action
		$this->BuildSearchSql($sWhere, $this->verified_comment, $Default, FALSE); // verified_comment
		$this->BuildSearchSql($sWhere, $this->verified_by, $Default, FALSE); // verified_by

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->date_recieved->AdvancedSearch->Save(); // date_recieved
			$this->reference_id->AdvancedSearch->Save(); // reference_id
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->material_name->AdvancedSearch->Save(); // material_name
			$this->quantity->AdvancedSearch->Save(); // quantity
			$this->type->AdvancedSearch->Save(); // type
			$this->capacity->AdvancedSearch->Save(); // capacity
			$this->recieved_by->AdvancedSearch->Save(); // recieved_by
			$this->statuss->AdvancedSearch->Save(); // statuss
			$this->recieved_action->AdvancedSearch->Save(); // recieved_action
			$this->recieved_comment->AdvancedSearch->Save(); // recieved_comment
			$this->date_approved->AdvancedSearch->Save(); // date_approved
			$this->approver_action->AdvancedSearch->Save(); // approver_action
			$this->approver_comment->AdvancedSearch->Save(); // approver_comment
			$this->approved_by->AdvancedSearch->Save(); // approved_by
			$this->verified_date->AdvancedSearch->Save(); // verified_date
			$this->verified_action->AdvancedSearch->Save(); // verified_action
			$this->verified_comment->AdvancedSearch->Save(); // verified_comment
			$this->verified_by->AdvancedSearch->Save(); // verified_by
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
		$this->BuildBasicSearchSQL($sWhere, $this->material_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->quantity, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->type, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->capacity, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->recieved_comment, $arKeywords, $type);
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_recieved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reference_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->material_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->quantity->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->capacity->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recieved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->statuss->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recieved_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recieved_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_approved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approver_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approver_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->verified_by->AdvancedSearch->IssetSession())
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
		$this->date_recieved->AdvancedSearch->UnsetSession();
		$this->reference_id->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->material_name->AdvancedSearch->UnsetSession();
		$this->quantity->AdvancedSearch->UnsetSession();
		$this->type->AdvancedSearch->UnsetSession();
		$this->capacity->AdvancedSearch->UnsetSession();
		$this->recieved_by->AdvancedSearch->UnsetSession();
		$this->statuss->AdvancedSearch->UnsetSession();
		$this->recieved_action->AdvancedSearch->UnsetSession();
		$this->recieved_comment->AdvancedSearch->UnsetSession();
		$this->date_approved->AdvancedSearch->UnsetSession();
		$this->approver_action->AdvancedSearch->UnsetSession();
		$this->approver_comment->AdvancedSearch->UnsetSession();
		$this->approved_by->AdvancedSearch->UnsetSession();
		$this->verified_date->AdvancedSearch->UnsetSession();
		$this->verified_action->AdvancedSearch->UnsetSession();
		$this->verified_comment->AdvancedSearch->UnsetSession();
		$this->verified_by->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->date_recieved->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->material_name->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->type->AdvancedSearch->Load();
		$this->capacity->AdvancedSearch->Load();
		$this->recieved_by->AdvancedSearch->Load();
		$this->statuss->AdvancedSearch->Load();
		$this->recieved_action->AdvancedSearch->Load();
		$this->recieved_comment->AdvancedSearch->Load();
		$this->date_approved->AdvancedSearch->Load();
		$this->approver_action->AdvancedSearch->Load();
		$this->approver_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->verified_date->AdvancedSearch->Load();
		$this->verified_action->AdvancedSearch->Load();
		$this->verified_comment->AdvancedSearch->Load();
		$this->verified_by->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->date_recieved); // date_recieved
			$this->UpdateSort($this->reference_id); // reference_id
			$this->UpdateSort($this->material_name); // material_name
			$this->UpdateSort($this->quantity); // quantity
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->capacity); // capacity
			$this->UpdateSort($this->recieved_by); // recieved_by
			$this->UpdateSort($this->statuss); // statuss
			$this->UpdateSort($this->recieved_action); // recieved_action
			$this->UpdateSort($this->approved_by); // approved_by
			$this->UpdateSort($this->verified_by); // verified_by
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
				$this->date_recieved->setSort("");
				$this->reference_id->setSort("");
				$this->material_name->setSort("");
				$this->quantity->setSort("");
				$this->type->setSort("");
				$this->capacity->setSort("");
				$this->recieved_by->setSort("");
				$this->statuss->setSort("");
				$this->recieved_action->setSort("");
				$this->approved_by->setSort("");
				$this->verified_by->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"inventory_report\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"finventory_reportlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"finventory_reportlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.finventory_reportlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"finventory_reportlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"finventory_reportlistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
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

		// date_recieved
		$this->date_recieved->AdvancedSearch->SearchValue = @$_GET["x_date_recieved"];
		if ($this->date_recieved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_recieved->AdvancedSearch->SearchOperator = @$_GET["z_date_recieved"];
		$this->date_recieved->AdvancedSearch->SearchCondition = @$_GET["v_date_recieved"];
		$this->date_recieved->AdvancedSearch->SearchValue2 = @$_GET["y_date_recieved"];
		if ($this->date_recieved->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->date_recieved->AdvancedSearch->SearchOperator2 = @$_GET["w_date_recieved"];

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = @$_GET["x_reference_id"];
		if ($this->reference_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reference_id->AdvancedSearch->SearchOperator = @$_GET["z_reference_id"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// material_name
		$this->material_name->AdvancedSearch->SearchValue = @$_GET["x_material_name"];
		if ($this->material_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->material_name->AdvancedSearch->SearchOperator = @$_GET["z_material_name"];

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = @$_GET["x_quantity"];
		if ($this->quantity->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->quantity->AdvancedSearch->SearchOperator = @$_GET["z_quantity"];

		// type
		$this->type->AdvancedSearch->SearchValue = @$_GET["x_type"];
		if ($this->type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->type->AdvancedSearch->SearchOperator = @$_GET["z_type"];

		// capacity
		$this->capacity->AdvancedSearch->SearchValue = @$_GET["x_capacity"];
		if ($this->capacity->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->capacity->AdvancedSearch->SearchOperator = @$_GET["z_capacity"];

		// recieved_by
		$this->recieved_by->AdvancedSearch->SearchValue = @$_GET["x_recieved_by"];
		if ($this->recieved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recieved_by->AdvancedSearch->SearchOperator = @$_GET["z_recieved_by"];

		// statuss
		$this->statuss->AdvancedSearch->SearchValue = @$_GET["x_statuss"];
		if ($this->statuss->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->statuss->AdvancedSearch->SearchOperator = @$_GET["z_statuss"];

		// recieved_action
		$this->recieved_action->AdvancedSearch->SearchValue = @$_GET["x_recieved_action"];
		if ($this->recieved_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recieved_action->AdvancedSearch->SearchOperator = @$_GET["z_recieved_action"];

		// recieved_comment
		$this->recieved_comment->AdvancedSearch->SearchValue = @$_GET["x_recieved_comment"];
		if ($this->recieved_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recieved_comment->AdvancedSearch->SearchOperator = @$_GET["z_recieved_comment"];

		// date_approved
		$this->date_approved->AdvancedSearch->SearchValue = @$_GET["x_date_approved"];
		if ($this->date_approved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_approved->AdvancedSearch->SearchOperator = @$_GET["z_date_approved"];

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

		// verified_date
		$this->verified_date->AdvancedSearch->SearchValue = @$_GET["x_verified_date"];
		if ($this->verified_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_date->AdvancedSearch->SearchOperator = @$_GET["z_verified_date"];

		// verified_action
		$this->verified_action->AdvancedSearch->SearchValue = @$_GET["x_verified_action"];
		if ($this->verified_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_action->AdvancedSearch->SearchOperator = @$_GET["z_verified_action"];

		// verified_comment
		$this->verified_comment->AdvancedSearch->SearchValue = @$_GET["x_verified_comment"];
		if ($this->verified_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_comment->AdvancedSearch->SearchOperator = @$_GET["z_verified_comment"];

		// verified_by
		$this->verified_by->AdvancedSearch->SearchValue = @$_GET["x_verified_by"];
		if ($this->verified_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->verified_by->AdvancedSearch->SearchOperator = @$_GET["z_verified_by"];
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
		$this->date_recieved->setDbValue($row['date_recieved']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity->setDbValue($row['quantity']);
		$this->type->setDbValue($row['type']);
		$this->capacity->setDbValue($row['capacity']);
		$this->recieved_by->setDbValue($row['recieved_by']);
		$this->statuss->setDbValue($row['statuss']);
		$this->recieved_action->setDbValue($row['recieved_action']);
		$this->recieved_comment->setDbValue($row['recieved_comment']);
		$this->date_approved->setDbValue($row['date_approved']);
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
		$row['id'] = NULL;
		$row['date_recieved'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['material_name'] = NULL;
		$row['quantity'] = NULL;
		$row['type'] = NULL;
		$row['capacity'] = NULL;
		$row['recieved_by'] = NULL;
		$row['statuss'] = NULL;
		$row['recieved_action'] = NULL;
		$row['recieved_comment'] = NULL;
		$row['date_approved'] = NULL;
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
		$this->id->DbValue = $row['id'];
		$this->date_recieved->DbValue = $row['date_recieved'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity->DbValue = $row['quantity'];
		$this->type->DbValue = $row['type'];
		$this->capacity->DbValue = $row['capacity'];
		$this->recieved_by->DbValue = $row['recieved_by'];
		$this->statuss->DbValue = $row['statuss'];
		$this->recieved_action->DbValue = $row['recieved_action'];
		$this->recieved_comment->DbValue = $row['recieved_comment'];
		$this->date_approved->DbValue = $row['date_approved'];
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
		// date_recieved
		// reference_id
		// staff_id
		// material_name
		// quantity
		// type
		// capacity
		// recieved_by
		// statuss
		// recieved_action
		// recieved_comment
		// date_approved
		// approver_action
		// approver_comment
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

		// material_name
		$this->material_name->ViewValue = $this->material_name->CurrentValue;
		$this->material_name->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// recieved_by
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
		$this->date_approved->ViewValue = ew_FormatDateTime($this->date_approved->ViewValue, 17);
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
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 17);
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
		$sSqlWrk .= " ORDER BY `id`";
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

			// date_recieved
			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";
			$this->date_recieved->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";
			if ($this->Export == "")
				$this->reference_id->ViewValue = $this->HighlightValue($this->reference_id);

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";
			if ($this->Export == "")
				$this->material_name->ViewValue = $this->HighlightValue($this->material_name);

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";
			if ($this->Export == "")
				$this->quantity->ViewValue = $this->HighlightValue($this->quantity);

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";
			if ($this->Export == "")
				$this->type->ViewValue = $this->HighlightValue($this->type);

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";
			$this->capacity->TooltipValue = "";
			if ($this->Export == "")
				$this->capacity->ViewValue = $this->HighlightValue($this->capacity);

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";
			$this->recieved_by->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

			// recieved_action
			$this->recieved_action->LinkCustomAttributes = "";
			$this->recieved_action->HrefValue = "";
			$this->recieved_action->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
			$this->verified_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

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

			// material_name
			$this->material_name->EditAttrs["class"] = "form-control";
			$this->material_name->EditCustomAttributes = "";
			$this->material_name->EditValue = ew_HtmlEncode($this->material_name->AdvancedSearch->SearchValue);
			$this->material_name->PlaceHolder = ew_RemoveHtml($this->material_name->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->AdvancedSearch->SearchValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

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

			// recieved_by
			$this->recieved_by->EditCustomAttributes = "";
			if (trim(strval($this->recieved_by->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
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
				$this->recieved_by->AdvancedSearch->ViewValue = $this->recieved_by->DisplayValue($arwrk);
			} else {
				$this->recieved_by->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->recieved_by->EditValue = $arwrk;

			// statuss
			$this->statuss->EditAttrs["class"] = "form-control";
			$this->statuss->EditCustomAttributes = "";

			// recieved_action
			$this->recieved_action->EditCustomAttributes = "";
			$this->recieved_action->EditValue = $this->recieved_action->Options(FALSE);

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->AdvancedSearch->SearchValue);
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// verified_by
			$this->verified_by->EditAttrs["class"] = "form-control";
			$this->verified_by->EditCustomAttributes = "";
			$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->AdvancedSearch->SearchValue);
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
		$this->quantity->AdvancedSearch->Load();
		$this->type->AdvancedSearch->Load();
		$this->capacity->AdvancedSearch->Load();
		$this->recieved_by->AdvancedSearch->Load();
		$this->statuss->AdvancedSearch->Load();
		$this->recieved_action->AdvancedSearch->Load();
		$this->recieved_comment->AdvancedSearch->Load();
		$this->date_approved->AdvancedSearch->Load();
		$this->approver_action->AdvancedSearch->Load();
		$this->approver_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->verified_date->AdvancedSearch->Load();
		$this->verified_action->AdvancedSearch->Load();
		$this->verified_comment->AdvancedSearch->Load();
		$this->verified_by->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_inventory_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_inventory_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finventory_reportlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($inventory_report_list)) $inventory_report_list = new cinventory_report_list();

// Page init
$inventory_report_list->Page_Init();

// Page main
$inventory_report_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_report_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($inventory_report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = finventory_reportlist = new ew_Form("finventory_reportlist", "list");
finventory_reportlist.FormKeyCountName = '<?php echo $inventory_report_list->FormKeyCountName ?>';

// Form_CustomValidate event
finventory_reportlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventory_reportlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventory_reportlist.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_reportlist.Lists["x_recieved_by"].Data = "<?php echo $inventory_report_list->recieved_by->LookupFilterQuery(FALSE, "list") ?>";
finventory_reportlist.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
finventory_reportlist.Lists["x_statuss"].Data = "<?php echo $inventory_report_list->statuss->LookupFilterQuery(FALSE, "list") ?>";
finventory_reportlist.Lists["x_recieved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_reportlist.Lists["x_recieved_action"].Options = <?php echo json_encode($inventory_report_list->recieved_action->Options()) ?>;
finventory_reportlist.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_reportlist.Lists["x_approved_by"].Data = "<?php echo $inventory_report_list->approved_by->LookupFilterQuery(FALSE, "list") ?>";
finventory_reportlist.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_report_list->approved_by->LookupFilterQuery(TRUE, "list"))) ?>;
finventory_reportlist.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_reportlist.Lists["x_verified_by"].Data = "<?php echo $inventory_report_list->verified_by->LookupFilterQuery(FALSE, "list") ?>";
finventory_reportlist.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_report_list->verified_by->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = finventory_reportlistsrch = new ew_Form("finventory_reportlistsrch");

// Validate function for search
finventory_reportlistsrch.Validate = function(fobj) {
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
finventory_reportlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventory_reportlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventory_reportlistsrch.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_reportlistsrch.Lists["x_recieved_by"].Data = "<?php echo $inventory_report_list->recieved_by->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($inventory_report->Export == "") { ?>
<div class="ewToolbar">
<?php if ($inventory_report_list->TotalRecs > 0 && $inventory_report_list->ExportOptions->Visible()) { ?>
<?php $inventory_report_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($inventory_report_list->SearchOptions->Visible()) { ?>
<?php $inventory_report_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($inventory_report_list->FilterOptions->Visible()) { ?>
<?php $inventory_report_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $inventory_report_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($inventory_report_list->TotalRecs <= 0)
			$inventory_report_list->TotalRecs = $inventory_report->ListRecordCount();
	} else {
		if (!$inventory_report_list->Recordset && ($inventory_report_list->Recordset = $inventory_report_list->LoadRecordset()))
			$inventory_report_list->TotalRecs = $inventory_report_list->Recordset->RecordCount();
	}
	$inventory_report_list->StartRec = 1;
	if ($inventory_report_list->DisplayRecs <= 0 || ($inventory_report->Export <> "" && $inventory_report->ExportAll)) // Display all records
		$inventory_report_list->DisplayRecs = $inventory_report_list->TotalRecs;
	if (!($inventory_report->Export <> "" && $inventory_report->ExportAll))
		$inventory_report_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$inventory_report_list->Recordset = $inventory_report_list->LoadRecordset($inventory_report_list->StartRec-1, $inventory_report_list->DisplayRecs);

	// Set no record found message
	if ($inventory_report->CurrentAction == "" && $inventory_report_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$inventory_report_list->setWarningMessage(ew_DeniedMsg());
		if ($inventory_report_list->SearchWhere == "0=101")
			$inventory_report_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$inventory_report_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($inventory_report_list->AuditTrailOnSearch && $inventory_report_list->Command == "search" && !$inventory_report_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $inventory_report_list->getSessionWhere();
		$inventory_report_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$inventory_report_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($inventory_report->Export == "" && $inventory_report->CurrentAction == "") { ?>
<form name="finventory_reportlistsrch" id="finventory_reportlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($inventory_report_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="finventory_reportlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="inventory_report">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$inventory_report_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$inventory_report->RowType = EW_ROWTYPE_SEARCH;

// Render row
$inventory_report->ResetAttrs();
$inventory_report_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($inventory_report->date_recieved->Visible) { // date_recieved ?>
	<div id="xsc_date_recieved" class="ewCell form-group">
		<label for="x_date_recieved" class="ewSearchCaption ewLabel"><?php echo $inventory_report->date_recieved->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_date_recieved" id="z_date_recieved" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="inventory_report" data-field="x_date_recieved" name="x_date_recieved" id="x_date_recieved" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_report->date_recieved->getPlaceHolder()) ?>" value="<?php echo $inventory_report->date_recieved->EditValue ?>"<?php echo $inventory_report->date_recieved->EditAttributes() ?>>
<?php if (!$inventory_report->date_recieved->ReadOnly && !$inventory_report->date_recieved->Disabled && !isset($inventory_report->date_recieved->EditAttrs["readonly"]) && !isset($inventory_report->date_recieved->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finventory_reportlistsrch", "x_date_recieved", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_date_recieved">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_date_recieved">
<input type="text" data-table="inventory_report" data-field="x_date_recieved" name="y_date_recieved" id="y_date_recieved" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_report->date_recieved->getPlaceHolder()) ?>" value="<?php echo $inventory_report->date_recieved->EditValue2 ?>"<?php echo $inventory_report->date_recieved->EditAttributes() ?>>
<?php if (!$inventory_report->date_recieved->ReadOnly && !$inventory_report->date_recieved->Disabled && !isset($inventory_report->date_recieved->EditAttrs["readonly"]) && !isset($inventory_report->date_recieved->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finventory_reportlistsrch", "y_date_recieved", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($inventory_report->reference_id->Visible) { // reference_id ?>
	<div id="xsc_reference_id" class="ewCell form-group">
		<label for="x_reference_id" class="ewSearchCaption ewLabel"><?php echo $inventory_report->reference_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="inventory_report" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_report->reference_id->getPlaceHolder()) ?>" value="<?php echo $inventory_report->reference_id->EditValue ?>"<?php echo $inventory_report->reference_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($inventory_report->material_name->Visible) { // material_name ?>
	<div id="xsc_material_name" class="ewCell form-group">
		<label for="x_material_name" class="ewSearchCaption ewLabel"><?php echo $inventory_report->material_name->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_material_name" id="z_material_name" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="inventory_report" data-field="x_material_name" name="x_material_name" id="x_material_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_report->material_name->getPlaceHolder()) ?>" value="<?php echo $inventory_report->material_name->EditValue ?>"<?php echo $inventory_report->material_name->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($inventory_report->recieved_by->Visible) { // recieved_by ?>
	<div id="xsc_recieved_by" class="ewCell form-group">
		<label for="x_recieved_by" class="ewSearchCaption ewLabel"><?php echo $inventory_report->recieved_by->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_recieved_by" id="z_recieved_by" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_recieved_by"><?php echo (strval($inventory_report->recieved_by->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $inventory_report->recieved_by->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($inventory_report->recieved_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_recieved_by',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($inventory_report->recieved_by->ReadOnly || $inventory_report->recieved_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="inventory_report" data-field="x_recieved_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $inventory_report->recieved_by->DisplayValueSeparatorAttribute() ?>" name="x_recieved_by" id="x_recieved_by" value="<?php echo $inventory_report->recieved_by->AdvancedSearch->SearchValue ?>"<?php echo $inventory_report->recieved_by->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($inventory_report_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($inventory_report_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $inventory_report_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($inventory_report_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($inventory_report_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($inventory_report_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($inventory_report_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $inventory_report_list->ShowPageHeader(); ?>
<?php
$inventory_report_list->ShowMessage();
?>
<?php if ($inventory_report_list->TotalRecs > 0 || $inventory_report->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($inventory_report_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> inventory_report">
<?php if ($inventory_report->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($inventory_report->CurrentAction <> "gridadd" && $inventory_report->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($inventory_report_list->Pager)) $inventory_report_list->Pager = new cPrevNextPager($inventory_report_list->StartRec, $inventory_report_list->DisplayRecs, $inventory_report_list->TotalRecs, $inventory_report_list->AutoHidePager) ?>
<?php if ($inventory_report_list->Pager->RecordCount > 0 && $inventory_report_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($inventory_report_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $inventory_report_list->PageUrl() ?>start=<?php echo $inventory_report_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($inventory_report_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $inventory_report_list->PageUrl() ?>start=<?php echo $inventory_report_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $inventory_report_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($inventory_report_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $inventory_report_list->PageUrl() ?>start=<?php echo $inventory_report_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($inventory_report_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $inventory_report_list->PageUrl() ?>start=<?php echo $inventory_report_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $inventory_report_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($inventory_report_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $inventory_report_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $inventory_report_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $inventory_report_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($inventory_report_list->TotalRecs > 0 && (!$inventory_report_list->AutoHidePageSizeSelector || $inventory_report_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="inventory_report">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($inventory_report_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($inventory_report_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($inventory_report_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($inventory_report_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($inventory_report_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($inventory_report->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($inventory_report_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="finventory_reportlist" id="finventory_reportlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_report_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_report_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory_report">
<div id="gmp_inventory_report" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($inventory_report_list->TotalRecs > 0 || $inventory_report->CurrentAction == "gridedit") { ?>
<table id="tbl_inventory_reportlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$inventory_report_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$inventory_report_list->RenderListOptions();

// Render list options (header, left)
$inventory_report_list->ListOptions->Render("header", "left");
?>
<?php if ($inventory_report->date_recieved->Visible) { // date_recieved ?>
	<?php if ($inventory_report->SortUrl($inventory_report->date_recieved) == "") { ?>
		<th data-name="date_recieved" class="<?php echo $inventory_report->date_recieved->HeaderCellClass() ?>"><div id="elh_inventory_report_date_recieved" class="inventory_report_date_recieved"><div class="ewTableHeaderCaption"><?php echo $inventory_report->date_recieved->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_recieved" class="<?php echo $inventory_report->date_recieved->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->date_recieved) ?>',1);"><div id="elh_inventory_report_date_recieved" class="inventory_report_date_recieved">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->date_recieved->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->date_recieved->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->date_recieved->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->reference_id->Visible) { // reference_id ?>
	<?php if ($inventory_report->SortUrl($inventory_report->reference_id) == "") { ?>
		<th data-name="reference_id" class="<?php echo $inventory_report->reference_id->HeaderCellClass() ?>"><div id="elh_inventory_report_reference_id" class="inventory_report_reference_id"><div class="ewTableHeaderCaption"><?php echo $inventory_report->reference_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reference_id" class="<?php echo $inventory_report->reference_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->reference_id) ?>',1);"><div id="elh_inventory_report_reference_id" class="inventory_report_reference_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->reference_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->reference_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->reference_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->material_name->Visible) { // material_name ?>
	<?php if ($inventory_report->SortUrl($inventory_report->material_name) == "") { ?>
		<th data-name="material_name" class="<?php echo $inventory_report->material_name->HeaderCellClass() ?>"><div id="elh_inventory_report_material_name" class="inventory_report_material_name"><div class="ewTableHeaderCaption"><?php echo $inventory_report->material_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="material_name" class="<?php echo $inventory_report->material_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->material_name) ?>',1);"><div id="elh_inventory_report_material_name" class="inventory_report_material_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->material_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->material_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->material_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->quantity->Visible) { // quantity ?>
	<?php if ($inventory_report->SortUrl($inventory_report->quantity) == "") { ?>
		<th data-name="quantity" class="<?php echo $inventory_report->quantity->HeaderCellClass() ?>"><div id="elh_inventory_report_quantity" class="inventory_report_quantity"><div class="ewTableHeaderCaption"><?php echo $inventory_report->quantity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="quantity" class="<?php echo $inventory_report->quantity->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->quantity) ?>',1);"><div id="elh_inventory_report_quantity" class="inventory_report_quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->quantity->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->type->Visible) { // type ?>
	<?php if ($inventory_report->SortUrl($inventory_report->type) == "") { ?>
		<th data-name="type" class="<?php echo $inventory_report->type->HeaderCellClass() ?>"><div id="elh_inventory_report_type" class="inventory_report_type"><div class="ewTableHeaderCaption"><?php echo $inventory_report->type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="type" class="<?php echo $inventory_report->type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->type) ?>',1);"><div id="elh_inventory_report_type" class="inventory_report_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->capacity->Visible) { // capacity ?>
	<?php if ($inventory_report->SortUrl($inventory_report->capacity) == "") { ?>
		<th data-name="capacity" class="<?php echo $inventory_report->capacity->HeaderCellClass() ?>"><div id="elh_inventory_report_capacity" class="inventory_report_capacity"><div class="ewTableHeaderCaption"><?php echo $inventory_report->capacity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="capacity" class="<?php echo $inventory_report->capacity->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->capacity) ?>',1);"><div id="elh_inventory_report_capacity" class="inventory_report_capacity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->capacity->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->capacity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->capacity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->recieved_by->Visible) { // recieved_by ?>
	<?php if ($inventory_report->SortUrl($inventory_report->recieved_by) == "") { ?>
		<th data-name="recieved_by" class="<?php echo $inventory_report->recieved_by->HeaderCellClass() ?>"><div id="elh_inventory_report_recieved_by" class="inventory_report_recieved_by"><div class="ewTableHeaderCaption"><?php echo $inventory_report->recieved_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recieved_by" class="<?php echo $inventory_report->recieved_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->recieved_by) ?>',1);"><div id="elh_inventory_report_recieved_by" class="inventory_report_recieved_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->recieved_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->recieved_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->recieved_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->statuss->Visible) { // statuss ?>
	<?php if ($inventory_report->SortUrl($inventory_report->statuss) == "") { ?>
		<th data-name="statuss" class="<?php echo $inventory_report->statuss->HeaderCellClass() ?>"><div id="elh_inventory_report_statuss" class="inventory_report_statuss"><div class="ewTableHeaderCaption"><?php echo $inventory_report->statuss->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="statuss" class="<?php echo $inventory_report->statuss->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->statuss) ?>',1);"><div id="elh_inventory_report_statuss" class="inventory_report_statuss">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->statuss->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->statuss->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->statuss->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->recieved_action->Visible) { // recieved_action ?>
	<?php if ($inventory_report->SortUrl($inventory_report->recieved_action) == "") { ?>
		<th data-name="recieved_action" class="<?php echo $inventory_report->recieved_action->HeaderCellClass() ?>"><div id="elh_inventory_report_recieved_action" class="inventory_report_recieved_action"><div class="ewTableHeaderCaption"><?php echo $inventory_report->recieved_action->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recieved_action" class="<?php echo $inventory_report->recieved_action->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->recieved_action) ?>',1);"><div id="elh_inventory_report_recieved_action" class="inventory_report_recieved_action">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->recieved_action->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->recieved_action->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->recieved_action->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->approved_by->Visible) { // approved_by ?>
	<?php if ($inventory_report->SortUrl($inventory_report->approved_by) == "") { ?>
		<th data-name="approved_by" class="<?php echo $inventory_report->approved_by->HeaderCellClass() ?>"><div id="elh_inventory_report_approved_by" class="inventory_report_approved_by"><div class="ewTableHeaderCaption"><?php echo $inventory_report->approved_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="approved_by" class="<?php echo $inventory_report->approved_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->approved_by) ?>',1);"><div id="elh_inventory_report_approved_by" class="inventory_report_approved_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->approved_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->approved_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->approved_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($inventory_report->verified_by->Visible) { // verified_by ?>
	<?php if ($inventory_report->SortUrl($inventory_report->verified_by) == "") { ?>
		<th data-name="verified_by" class="<?php echo $inventory_report->verified_by->HeaderCellClass() ?>"><div id="elh_inventory_report_verified_by" class="inventory_report_verified_by"><div class="ewTableHeaderCaption"><?php echo $inventory_report->verified_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="verified_by" class="<?php echo $inventory_report->verified_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $inventory_report->SortUrl($inventory_report->verified_by) ?>',1);"><div id="elh_inventory_report_verified_by" class="inventory_report_verified_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $inventory_report->verified_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($inventory_report->verified_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($inventory_report->verified_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$inventory_report_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($inventory_report->ExportAll && $inventory_report->Export <> "") {
	$inventory_report_list->StopRec = $inventory_report_list->TotalRecs;
} else {

	// Set the last record to display
	if ($inventory_report_list->TotalRecs > $inventory_report_list->StartRec + $inventory_report_list->DisplayRecs - 1)
		$inventory_report_list->StopRec = $inventory_report_list->StartRec + $inventory_report_list->DisplayRecs - 1;
	else
		$inventory_report_list->StopRec = $inventory_report_list->TotalRecs;
}
$inventory_report_list->RecCnt = $inventory_report_list->StartRec - 1;
if ($inventory_report_list->Recordset && !$inventory_report_list->Recordset->EOF) {
	$inventory_report_list->Recordset->MoveFirst();
	$bSelectLimit = $inventory_report_list->UseSelectLimit;
	if (!$bSelectLimit && $inventory_report_list->StartRec > 1)
		$inventory_report_list->Recordset->Move($inventory_report_list->StartRec - 1);
} elseif (!$inventory_report->AllowAddDeleteRow && $inventory_report_list->StopRec == 0) {
	$inventory_report_list->StopRec = $inventory_report->GridAddRowCount;
}

// Initialize aggregate
$inventory_report->RowType = EW_ROWTYPE_AGGREGATEINIT;
$inventory_report->ResetAttrs();
$inventory_report_list->RenderRow();
while ($inventory_report_list->RecCnt < $inventory_report_list->StopRec) {
	$inventory_report_list->RecCnt++;
	if (intval($inventory_report_list->RecCnt) >= intval($inventory_report_list->StartRec)) {
		$inventory_report_list->RowCnt++;

		// Set up key count
		$inventory_report_list->KeyCount = $inventory_report_list->RowIndex;

		// Init row class and style
		$inventory_report->ResetAttrs();
		$inventory_report->CssClass = "";
		if ($inventory_report->CurrentAction == "gridadd") {
		} else {
			$inventory_report_list->LoadRowValues($inventory_report_list->Recordset); // Load row values
		}
		$inventory_report->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$inventory_report->RowAttrs = array_merge($inventory_report->RowAttrs, array('data-rowindex'=>$inventory_report_list->RowCnt, 'id'=>'r' . $inventory_report_list->RowCnt . '_inventory_report', 'data-rowtype'=>$inventory_report->RowType));

		// Render row
		$inventory_report_list->RenderRow();

		// Render list options
		$inventory_report_list->RenderListOptions();
?>
	<tr<?php echo $inventory_report->RowAttributes() ?>>
<?php

// Render list options (body, left)
$inventory_report_list->ListOptions->Render("body", "left", $inventory_report_list->RowCnt);
?>
	<?php if ($inventory_report->date_recieved->Visible) { // date_recieved ?>
		<td data-name="date_recieved"<?php echo $inventory_report->date_recieved->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_date_recieved" class="inventory_report_date_recieved">
<span<?php echo $inventory_report->date_recieved->ViewAttributes() ?>>
<?php echo $inventory_report->date_recieved->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->reference_id->Visible) { // reference_id ?>
		<td data-name="reference_id"<?php echo $inventory_report->reference_id->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_reference_id" class="inventory_report_reference_id">
<span<?php echo $inventory_report->reference_id->ViewAttributes() ?>>
<?php echo $inventory_report->reference_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->material_name->Visible) { // material_name ?>
		<td data-name="material_name"<?php echo $inventory_report->material_name->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_material_name" class="inventory_report_material_name">
<span<?php echo $inventory_report->material_name->ViewAttributes() ?>>
<?php echo $inventory_report->material_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->quantity->Visible) { // quantity ?>
		<td data-name="quantity"<?php echo $inventory_report->quantity->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_quantity" class="inventory_report_quantity">
<span<?php echo $inventory_report->quantity->ViewAttributes() ?>>
<?php echo $inventory_report->quantity->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->type->Visible) { // type ?>
		<td data-name="type"<?php echo $inventory_report->type->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_type" class="inventory_report_type">
<span<?php echo $inventory_report->type->ViewAttributes() ?>>
<?php echo $inventory_report->type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->capacity->Visible) { // capacity ?>
		<td data-name="capacity"<?php echo $inventory_report->capacity->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_capacity" class="inventory_report_capacity">
<span<?php echo $inventory_report->capacity->ViewAttributes() ?>>
<?php echo $inventory_report->capacity->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->recieved_by->Visible) { // recieved_by ?>
		<td data-name="recieved_by"<?php echo $inventory_report->recieved_by->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_recieved_by" class="inventory_report_recieved_by">
<span<?php echo $inventory_report->recieved_by->ViewAttributes() ?>>
<?php echo $inventory_report->recieved_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->statuss->Visible) { // statuss ?>
		<td data-name="statuss"<?php echo $inventory_report->statuss->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_statuss" class="inventory_report_statuss">
<span<?php echo $inventory_report->statuss->ViewAttributes() ?>>
<?php echo $inventory_report->statuss->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->recieved_action->Visible) { // recieved_action ?>
		<td data-name="recieved_action"<?php echo $inventory_report->recieved_action->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_recieved_action" class="inventory_report_recieved_action">
<span<?php echo $inventory_report->recieved_action->ViewAttributes() ?>>
<?php echo $inventory_report->recieved_action->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->approved_by->Visible) { // approved_by ?>
		<td data-name="approved_by"<?php echo $inventory_report->approved_by->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_approved_by" class="inventory_report_approved_by">
<span<?php echo $inventory_report->approved_by->ViewAttributes() ?>>
<?php echo $inventory_report->approved_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($inventory_report->verified_by->Visible) { // verified_by ?>
		<td data-name="verified_by"<?php echo $inventory_report->verified_by->CellAttributes() ?>>
<span id="el<?php echo $inventory_report_list->RowCnt ?>_inventory_report_verified_by" class="inventory_report_verified_by">
<span<?php echo $inventory_report->verified_by->ViewAttributes() ?>>
<?php echo $inventory_report->verified_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$inventory_report_list->ListOptions->Render("body", "right", $inventory_report_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($inventory_report->CurrentAction <> "gridadd")
		$inventory_report_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($inventory_report->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($inventory_report_list->Recordset)
	$inventory_report_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($inventory_report_list->TotalRecs == 0 && $inventory_report->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($inventory_report_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($inventory_report->Export == "") { ?>
<script type="text/javascript">
finventory_reportlistsrch.FilterList = <?php echo $inventory_report_list->GetFilterList() ?>;
finventory_reportlistsrch.Init();
finventory_reportlist.Init();
</script>
<?php } ?>
<?php
$inventory_report_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($inventory_report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$inventory_report_list->Page_Terminate();
?>
