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

$pc_issuance_report_list = NULL; // Initialize page object first

class cpc_issuance_report_list extends cpc_issuance_report {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'pc_issuance_report';

	// Page object name
	var $PageObjName = 'pc_issuance_report_list';

	// Grid form hidden field names
	var $FormName = 'fpc_issuance_reportlist';
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

		// Table object (pc_issuance_report)
		if (!isset($GLOBALS["pc_issuance_report"]) || get_class($GLOBALS["pc_issuance_report"]) == "cpc_issuance_report") {
			$GLOBALS["pc_issuance_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pc_issuance_report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pc_issuance_reportadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pc_issuance_reportdelete.php";
		$this->MultiUpdateUrl = "pc_issuance_reportupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fpc_issuance_reportlistsrch";

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
		$this->issued_date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->asset_tag->SetVisibility();
		$this->make->SetVisibility();
		$this->ram->SetVisibility();
		$this->hard_disk->SetVisibility();
		$this->color->SetVisibility();
		$this->department->SetVisibility();
		$this->designation->SetVisibility();
		$this->assign_to->SetVisibility();
		$this->date_assign->SetVisibility();
		$this->assign_by->SetVisibility();
		$this->statuse->SetVisibility();
		$this->date_retrieved->SetVisibility();
		$this->retrieved_by->SetVisibility();

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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fpc_issuance_reportlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->issued_date->AdvancedSearch->ToJson(), ","); // Field issued_date
		$sFilterList = ew_Concat($sFilterList, $this->reference_id->AdvancedSearch->ToJson(), ","); // Field reference_id
		$sFilterList = ew_Concat($sFilterList, $this->asset_tag->AdvancedSearch->ToJson(), ","); // Field asset_tag
		$sFilterList = ew_Concat($sFilterList, $this->make->AdvancedSearch->ToJson(), ","); // Field make
		$sFilterList = ew_Concat($sFilterList, $this->ram->AdvancedSearch->ToJson(), ","); // Field ram
		$sFilterList = ew_Concat($sFilterList, $this->hard_disk->AdvancedSearch->ToJson(), ","); // Field hard_disk
		$sFilterList = ew_Concat($sFilterList, $this->color->AdvancedSearch->ToJson(), ","); // Field color
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->designation->AdvancedSearch->ToJson(), ","); // Field designation
		$sFilterList = ew_Concat($sFilterList, $this->assign_to->AdvancedSearch->ToJson(), ","); // Field assign_to
		$sFilterList = ew_Concat($sFilterList, $this->date_assign->AdvancedSearch->ToJson(), ","); // Field date_assign
		$sFilterList = ew_Concat($sFilterList, $this->assign_action->AdvancedSearch->ToJson(), ","); // Field assign_action
		$sFilterList = ew_Concat($sFilterList, $this->assign_comment->AdvancedSearch->ToJson(), ","); // Field assign_comment
		$sFilterList = ew_Concat($sFilterList, $this->assign_by->AdvancedSearch->ToJson(), ","); // Field assign_by
		$sFilterList = ew_Concat($sFilterList, $this->statuse->AdvancedSearch->ToJson(), ","); // Field statuse
		$sFilterList = ew_Concat($sFilterList, $this->date_retrieved->AdvancedSearch->ToJson(), ","); // Field date_retrieved
		$sFilterList = ew_Concat($sFilterList, $this->retriever_action->AdvancedSearch->ToJson(), ","); // Field retriever_action
		$sFilterList = ew_Concat($sFilterList, $this->retriever_comment->AdvancedSearch->ToJson(), ","); // Field retriever_comment
		$sFilterList = ew_Concat($sFilterList, $this->retrieved_by->AdvancedSearch->ToJson(), ","); // Field retrieved_by
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fpc_issuance_reportlistsrch", $filters);

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

		// Field issued_date
		$this->issued_date->AdvancedSearch->SearchValue = @$filter["x_issued_date"];
		$this->issued_date->AdvancedSearch->SearchOperator = @$filter["z_issued_date"];
		$this->issued_date->AdvancedSearch->SearchCondition = @$filter["v_issued_date"];
		$this->issued_date->AdvancedSearch->SearchValue2 = @$filter["y_issued_date"];
		$this->issued_date->AdvancedSearch->SearchOperator2 = @$filter["w_issued_date"];
		$this->issued_date->AdvancedSearch->Save();

		// Field reference_id
		$this->reference_id->AdvancedSearch->SearchValue = @$filter["x_reference_id"];
		$this->reference_id->AdvancedSearch->SearchOperator = @$filter["z_reference_id"];
		$this->reference_id->AdvancedSearch->SearchCondition = @$filter["v_reference_id"];
		$this->reference_id->AdvancedSearch->SearchValue2 = @$filter["y_reference_id"];
		$this->reference_id->AdvancedSearch->SearchOperator2 = @$filter["w_reference_id"];
		$this->reference_id->AdvancedSearch->Save();

		// Field asset_tag
		$this->asset_tag->AdvancedSearch->SearchValue = @$filter["x_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchOperator = @$filter["z_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchCondition = @$filter["v_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchValue2 = @$filter["y_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchOperator2 = @$filter["w_asset_tag"];
		$this->asset_tag->AdvancedSearch->Save();

		// Field make
		$this->make->AdvancedSearch->SearchValue = @$filter["x_make"];
		$this->make->AdvancedSearch->SearchOperator = @$filter["z_make"];
		$this->make->AdvancedSearch->SearchCondition = @$filter["v_make"];
		$this->make->AdvancedSearch->SearchValue2 = @$filter["y_make"];
		$this->make->AdvancedSearch->SearchOperator2 = @$filter["w_make"];
		$this->make->AdvancedSearch->Save();

		// Field ram
		$this->ram->AdvancedSearch->SearchValue = @$filter["x_ram"];
		$this->ram->AdvancedSearch->SearchOperator = @$filter["z_ram"];
		$this->ram->AdvancedSearch->SearchCondition = @$filter["v_ram"];
		$this->ram->AdvancedSearch->SearchValue2 = @$filter["y_ram"];
		$this->ram->AdvancedSearch->SearchOperator2 = @$filter["w_ram"];
		$this->ram->AdvancedSearch->Save();

		// Field hard_disk
		$this->hard_disk->AdvancedSearch->SearchValue = @$filter["x_hard_disk"];
		$this->hard_disk->AdvancedSearch->SearchOperator = @$filter["z_hard_disk"];
		$this->hard_disk->AdvancedSearch->SearchCondition = @$filter["v_hard_disk"];
		$this->hard_disk->AdvancedSearch->SearchValue2 = @$filter["y_hard_disk"];
		$this->hard_disk->AdvancedSearch->SearchOperator2 = @$filter["w_hard_disk"];
		$this->hard_disk->AdvancedSearch->Save();

		// Field color
		$this->color->AdvancedSearch->SearchValue = @$filter["x_color"];
		$this->color->AdvancedSearch->SearchOperator = @$filter["z_color"];
		$this->color->AdvancedSearch->SearchCondition = @$filter["v_color"];
		$this->color->AdvancedSearch->SearchValue2 = @$filter["y_color"];
		$this->color->AdvancedSearch->SearchOperator2 = @$filter["w_color"];
		$this->color->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field designation
		$this->designation->AdvancedSearch->SearchValue = @$filter["x_designation"];
		$this->designation->AdvancedSearch->SearchOperator = @$filter["z_designation"];
		$this->designation->AdvancedSearch->SearchCondition = @$filter["v_designation"];
		$this->designation->AdvancedSearch->SearchValue2 = @$filter["y_designation"];
		$this->designation->AdvancedSearch->SearchOperator2 = @$filter["w_designation"];
		$this->designation->AdvancedSearch->Save();

		// Field assign_to
		$this->assign_to->AdvancedSearch->SearchValue = @$filter["x_assign_to"];
		$this->assign_to->AdvancedSearch->SearchOperator = @$filter["z_assign_to"];
		$this->assign_to->AdvancedSearch->SearchCondition = @$filter["v_assign_to"];
		$this->assign_to->AdvancedSearch->SearchValue2 = @$filter["y_assign_to"];
		$this->assign_to->AdvancedSearch->SearchOperator2 = @$filter["w_assign_to"];
		$this->assign_to->AdvancedSearch->Save();

		// Field date_assign
		$this->date_assign->AdvancedSearch->SearchValue = @$filter["x_date_assign"];
		$this->date_assign->AdvancedSearch->SearchOperator = @$filter["z_date_assign"];
		$this->date_assign->AdvancedSearch->SearchCondition = @$filter["v_date_assign"];
		$this->date_assign->AdvancedSearch->SearchValue2 = @$filter["y_date_assign"];
		$this->date_assign->AdvancedSearch->SearchOperator2 = @$filter["w_date_assign"];
		$this->date_assign->AdvancedSearch->Save();

		// Field assign_action
		$this->assign_action->AdvancedSearch->SearchValue = @$filter["x_assign_action"];
		$this->assign_action->AdvancedSearch->SearchOperator = @$filter["z_assign_action"];
		$this->assign_action->AdvancedSearch->SearchCondition = @$filter["v_assign_action"];
		$this->assign_action->AdvancedSearch->SearchValue2 = @$filter["y_assign_action"];
		$this->assign_action->AdvancedSearch->SearchOperator2 = @$filter["w_assign_action"];
		$this->assign_action->AdvancedSearch->Save();

		// Field assign_comment
		$this->assign_comment->AdvancedSearch->SearchValue = @$filter["x_assign_comment"];
		$this->assign_comment->AdvancedSearch->SearchOperator = @$filter["z_assign_comment"];
		$this->assign_comment->AdvancedSearch->SearchCondition = @$filter["v_assign_comment"];
		$this->assign_comment->AdvancedSearch->SearchValue2 = @$filter["y_assign_comment"];
		$this->assign_comment->AdvancedSearch->SearchOperator2 = @$filter["w_assign_comment"];
		$this->assign_comment->AdvancedSearch->Save();

		// Field assign_by
		$this->assign_by->AdvancedSearch->SearchValue = @$filter["x_assign_by"];
		$this->assign_by->AdvancedSearch->SearchOperator = @$filter["z_assign_by"];
		$this->assign_by->AdvancedSearch->SearchCondition = @$filter["v_assign_by"];
		$this->assign_by->AdvancedSearch->SearchValue2 = @$filter["y_assign_by"];
		$this->assign_by->AdvancedSearch->SearchOperator2 = @$filter["w_assign_by"];
		$this->assign_by->AdvancedSearch->Save();

		// Field statuse
		$this->statuse->AdvancedSearch->SearchValue = @$filter["x_statuse"];
		$this->statuse->AdvancedSearch->SearchOperator = @$filter["z_statuse"];
		$this->statuse->AdvancedSearch->SearchCondition = @$filter["v_statuse"];
		$this->statuse->AdvancedSearch->SearchValue2 = @$filter["y_statuse"];
		$this->statuse->AdvancedSearch->SearchOperator2 = @$filter["w_statuse"];
		$this->statuse->AdvancedSearch->Save();

		// Field date_retrieved
		$this->date_retrieved->AdvancedSearch->SearchValue = @$filter["x_date_retrieved"];
		$this->date_retrieved->AdvancedSearch->SearchOperator = @$filter["z_date_retrieved"];
		$this->date_retrieved->AdvancedSearch->SearchCondition = @$filter["v_date_retrieved"];
		$this->date_retrieved->AdvancedSearch->SearchValue2 = @$filter["y_date_retrieved"];
		$this->date_retrieved->AdvancedSearch->SearchOperator2 = @$filter["w_date_retrieved"];
		$this->date_retrieved->AdvancedSearch->Save();

		// Field retriever_action
		$this->retriever_action->AdvancedSearch->SearchValue = @$filter["x_retriever_action"];
		$this->retriever_action->AdvancedSearch->SearchOperator = @$filter["z_retriever_action"];
		$this->retriever_action->AdvancedSearch->SearchCondition = @$filter["v_retriever_action"];
		$this->retriever_action->AdvancedSearch->SearchValue2 = @$filter["y_retriever_action"];
		$this->retriever_action->AdvancedSearch->SearchOperator2 = @$filter["w_retriever_action"];
		$this->retriever_action->AdvancedSearch->Save();

		// Field retriever_comment
		$this->retriever_comment->AdvancedSearch->SearchValue = @$filter["x_retriever_comment"];
		$this->retriever_comment->AdvancedSearch->SearchOperator = @$filter["z_retriever_comment"];
		$this->retriever_comment->AdvancedSearch->SearchCondition = @$filter["v_retriever_comment"];
		$this->retriever_comment->AdvancedSearch->SearchValue2 = @$filter["y_retriever_comment"];
		$this->retriever_comment->AdvancedSearch->SearchOperator2 = @$filter["w_retriever_comment"];
		$this->retriever_comment->AdvancedSearch->Save();

		// Field retrieved_by
		$this->retrieved_by->AdvancedSearch->SearchValue = @$filter["x_retrieved_by"];
		$this->retrieved_by->AdvancedSearch->SearchOperator = @$filter["z_retrieved_by"];
		$this->retrieved_by->AdvancedSearch->SearchCondition = @$filter["v_retrieved_by"];
		$this->retrieved_by->AdvancedSearch->SearchValue2 = @$filter["y_retrieved_by"];
		$this->retrieved_by->AdvancedSearch->SearchOperator2 = @$filter["w_retrieved_by"];
		$this->retrieved_by->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->issued_date, $Default, FALSE); // issued_date
		$this->BuildSearchSql($sWhere, $this->reference_id, $Default, FALSE); // reference_id
		$this->BuildSearchSql($sWhere, $this->asset_tag, $Default, FALSE); // asset_tag
		$this->BuildSearchSql($sWhere, $this->make, $Default, FALSE); // make
		$this->BuildSearchSql($sWhere, $this->ram, $Default, FALSE); // ram
		$this->BuildSearchSql($sWhere, $this->hard_disk, $Default, FALSE); // hard_disk
		$this->BuildSearchSql($sWhere, $this->color, $Default, FALSE); // color
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->designation, $Default, FALSE); // designation
		$this->BuildSearchSql($sWhere, $this->assign_to, $Default, FALSE); // assign_to
		$this->BuildSearchSql($sWhere, $this->date_assign, $Default, FALSE); // date_assign
		$this->BuildSearchSql($sWhere, $this->assign_action, $Default, FALSE); // assign_action
		$this->BuildSearchSql($sWhere, $this->assign_comment, $Default, FALSE); // assign_comment
		$this->BuildSearchSql($sWhere, $this->assign_by, $Default, FALSE); // assign_by
		$this->BuildSearchSql($sWhere, $this->statuse, $Default, TRUE); // statuse
		$this->BuildSearchSql($sWhere, $this->date_retrieved, $Default, FALSE); // date_retrieved
		$this->BuildSearchSql($sWhere, $this->retriever_action, $Default, FALSE); // retriever_action
		$this->BuildSearchSql($sWhere, $this->retriever_comment, $Default, FALSE); // retriever_comment
		$this->BuildSearchSql($sWhere, $this->retrieved_by, $Default, FALSE); // retrieved_by
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->issued_date->AdvancedSearch->Save(); // issued_date
			$this->reference_id->AdvancedSearch->Save(); // reference_id
			$this->asset_tag->AdvancedSearch->Save(); // asset_tag
			$this->make->AdvancedSearch->Save(); // make
			$this->ram->AdvancedSearch->Save(); // ram
			$this->hard_disk->AdvancedSearch->Save(); // hard_disk
			$this->color->AdvancedSearch->Save(); // color
			$this->department->AdvancedSearch->Save(); // department
			$this->designation->AdvancedSearch->Save(); // designation
			$this->assign_to->AdvancedSearch->Save(); // assign_to
			$this->date_assign->AdvancedSearch->Save(); // date_assign
			$this->assign_action->AdvancedSearch->Save(); // assign_action
			$this->assign_comment->AdvancedSearch->Save(); // assign_comment
			$this->assign_by->AdvancedSearch->Save(); // assign_by
			$this->statuse->AdvancedSearch->Save(); // statuse
			$this->date_retrieved->AdvancedSearch->Save(); // date_retrieved
			$this->retriever_action->AdvancedSearch->Save(); // retriever_action
			$this->retriever_comment->AdvancedSearch->Save(); // retriever_comment
			$this->retrieved_by->AdvancedSearch->Save(); // retrieved_by
			$this->staff_id->AdvancedSearch->Save(); // staff_id
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
		$this->BuildBasicSearchSQL($sWhere, $this->asset_tag, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->make, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ram, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->hard_disk, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->color, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->assign_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->statuse, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->retriever_comment, $arKeywords, $type);
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
		if ($this->issued_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->reference_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->asset_tag->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->make->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ram->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hard_disk->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->color->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->designation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->assign_to->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_assign->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->assign_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->assign_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->assign_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->statuse->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_retrieved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->retriever_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->retriever_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->retrieved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
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
		$this->issued_date->AdvancedSearch->UnsetSession();
		$this->reference_id->AdvancedSearch->UnsetSession();
		$this->asset_tag->AdvancedSearch->UnsetSession();
		$this->make->AdvancedSearch->UnsetSession();
		$this->ram->AdvancedSearch->UnsetSession();
		$this->hard_disk->AdvancedSearch->UnsetSession();
		$this->color->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->designation->AdvancedSearch->UnsetSession();
		$this->assign_to->AdvancedSearch->UnsetSession();
		$this->date_assign->AdvancedSearch->UnsetSession();
		$this->assign_action->AdvancedSearch->UnsetSession();
		$this->assign_comment->AdvancedSearch->UnsetSession();
		$this->assign_by->AdvancedSearch->UnsetSession();
		$this->statuse->AdvancedSearch->UnsetSession();
		$this->date_retrieved->AdvancedSearch->UnsetSession();
		$this->retriever_action->AdvancedSearch->UnsetSession();
		$this->retriever_comment->AdvancedSearch->UnsetSession();
		$this->retrieved_by->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->issued_date->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->asset_tag->AdvancedSearch->Load();
		$this->make->AdvancedSearch->Load();
		$this->ram->AdvancedSearch->Load();
		$this->hard_disk->AdvancedSearch->Load();
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

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->issued_date); // issued_date
			$this->UpdateSort($this->reference_id); // reference_id
			$this->UpdateSort($this->asset_tag); // asset_tag
			$this->UpdateSort($this->make); // make
			$this->UpdateSort($this->ram); // ram
			$this->UpdateSort($this->hard_disk); // hard_disk
			$this->UpdateSort($this->color); // color
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->designation); // designation
			$this->UpdateSort($this->assign_to); // assign_to
			$this->UpdateSort($this->date_assign); // date_assign
			$this->UpdateSort($this->assign_by); // assign_by
			$this->UpdateSort($this->statuse); // statuse
			$this->UpdateSort($this->date_retrieved); // date_retrieved
			$this->UpdateSort($this->retrieved_by); // retrieved_by
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
				$this->issued_date->setSort("");
				$this->reference_id->setSort("");
				$this->asset_tag->setSort("");
				$this->make->setSort("");
				$this->ram->setSort("");
				$this->hard_disk->setSort("");
				$this->color->setSort("");
				$this->department->setSort("");
				$this->designation->setSort("");
				$this->assign_to->setSort("");
				$this->date_assign->setSort("");
				$this->assign_by->setSort("");
				$this->statuse->setSort("");
				$this->date_retrieved->setSort("");
				$this->retrieved_by->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"pc_issuance_report\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpc_issuance_reportlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpc_issuance_reportlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpc_issuance_reportlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpc_issuance_reportlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"pc_issuance_reportsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"pc_issuance_report\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'pc_issuance_reportsrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fpc_issuance_reportlistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
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

		// issued_date
		$this->issued_date->AdvancedSearch->SearchValue = @$_GET["x_issued_date"];
		if ($this->issued_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->issued_date->AdvancedSearch->SearchOperator = @$_GET["z_issued_date"];
		$this->issued_date->AdvancedSearch->SearchCondition = @$_GET["v_issued_date"];
		$this->issued_date->AdvancedSearch->SearchValue2 = @$_GET["y_issued_date"];
		if ($this->issued_date->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->issued_date->AdvancedSearch->SearchOperator2 = @$_GET["w_issued_date"];

		// reference_id
		$this->reference_id->AdvancedSearch->SearchValue = @$_GET["x_reference_id"];
		if ($this->reference_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->reference_id->AdvancedSearch->SearchOperator = @$_GET["z_reference_id"];

		// asset_tag
		$this->asset_tag->AdvancedSearch->SearchValue = @$_GET["x_asset_tag"];
		if ($this->asset_tag->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->asset_tag->AdvancedSearch->SearchOperator = @$_GET["z_asset_tag"];

		// make
		$this->make->AdvancedSearch->SearchValue = @$_GET["x_make"];
		if ($this->make->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->make->AdvancedSearch->SearchOperator = @$_GET["z_make"];

		// ram
		$this->ram->AdvancedSearch->SearchValue = @$_GET["x_ram"];
		if ($this->ram->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ram->AdvancedSearch->SearchOperator = @$_GET["z_ram"];

		// hard_disk
		$this->hard_disk->AdvancedSearch->SearchValue = @$_GET["x_hard_disk"];
		if ($this->hard_disk->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->hard_disk->AdvancedSearch->SearchOperator = @$_GET["z_hard_disk"];

		// color
		$this->color->AdvancedSearch->SearchValue = @$_GET["x_color"];
		if ($this->color->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->color->AdvancedSearch->SearchOperator = @$_GET["z_color"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// designation
		$this->designation->AdvancedSearch->SearchValue = @$_GET["x_designation"];
		if ($this->designation->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->designation->AdvancedSearch->SearchOperator = @$_GET["z_designation"];

		// assign_to
		$this->assign_to->AdvancedSearch->SearchValue = @$_GET["x_assign_to"];
		if ($this->assign_to->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->assign_to->AdvancedSearch->SearchOperator = @$_GET["z_assign_to"];

		// date_assign
		$this->date_assign->AdvancedSearch->SearchValue = @$_GET["x_date_assign"];
		if ($this->date_assign->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_assign->AdvancedSearch->SearchOperator = @$_GET["z_date_assign"];

		// assign_action
		$this->assign_action->AdvancedSearch->SearchValue = @$_GET["x_assign_action"];
		if ($this->assign_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->assign_action->AdvancedSearch->SearchOperator = @$_GET["z_assign_action"];

		// assign_comment
		$this->assign_comment->AdvancedSearch->SearchValue = @$_GET["x_assign_comment"];
		if ($this->assign_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->assign_comment->AdvancedSearch->SearchOperator = @$_GET["z_assign_comment"];

		// assign_by
		$this->assign_by->AdvancedSearch->SearchValue = @$_GET["x_assign_by"];
		if ($this->assign_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->assign_by->AdvancedSearch->SearchOperator = @$_GET["z_assign_by"];

		// statuse
		$this->statuse->AdvancedSearch->SearchValue = @$_GET["x_statuse"];
		if ($this->statuse->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->statuse->AdvancedSearch->SearchOperator = @$_GET["z_statuse"];
		if (is_array($this->statuse->AdvancedSearch->SearchValue)) $this->statuse->AdvancedSearch->SearchValue = implode(",", $this->statuse->AdvancedSearch->SearchValue);
		if (is_array($this->statuse->AdvancedSearch->SearchValue2)) $this->statuse->AdvancedSearch->SearchValue2 = implode(",", $this->statuse->AdvancedSearch->SearchValue2);

		// date_retrieved
		$this->date_retrieved->AdvancedSearch->SearchValue = @$_GET["x_date_retrieved"];
		if ($this->date_retrieved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_retrieved->AdvancedSearch->SearchOperator = @$_GET["z_date_retrieved"];

		// retriever_action
		$this->retriever_action->AdvancedSearch->SearchValue = @$_GET["x_retriever_action"];
		if ($this->retriever_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->retriever_action->AdvancedSearch->SearchOperator = @$_GET["z_retriever_action"];

		// retriever_comment
		$this->retriever_comment->AdvancedSearch->SearchValue = @$_GET["x_retriever_comment"];
		if ($this->retriever_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->retriever_comment->AdvancedSearch->SearchOperator = @$_GET["z_retriever_comment"];

		// retrieved_by
		$this->retrieved_by->AdvancedSearch->SearchValue = @$_GET["x_retrieved_by"];
		if ($this->retrieved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->retrieved_by->AdvancedSearch->SearchOperator = @$_GET["z_retrieved_by"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];
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
		$this->issued_date->setDbValue($row['issued_date']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->asset_tag->setDbValue($row['asset_tag']);
		$this->make->setDbValue($row['make']);
		$this->ram->setDbValue($row['ram']);
		$this->hard_disk->setDbValue($row['hard_disk']);
		$this->color->setDbValue($row['color']);
		$this->department->setDbValue($row['department']);
		$this->designation->setDbValue($row['designation']);
		$this->assign_to->setDbValue($row['assign_to']);
		$this->date_assign->setDbValue($row['date_assign']);
		$this->assign_action->setDbValue($row['assign_action']);
		$this->assign_comment->setDbValue($row['assign_comment']);
		$this->assign_by->setDbValue($row['assign_by']);
		$this->statuse->setDbValue($row['statuse']);
		$this->date_retrieved->setDbValue($row['date_retrieved']);
		$this->retriever_action->setDbValue($row['retriever_action']);
		$this->retriever_comment->setDbValue($row['retriever_comment']);
		$this->retrieved_by->setDbValue($row['retrieved_by']);
		$this->staff_id->setDbValue($row['staff_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['issued_date'] = NULL;
		$row['reference_id'] = NULL;
		$row['asset_tag'] = NULL;
		$row['make'] = NULL;
		$row['ram'] = NULL;
		$row['hard_disk'] = NULL;
		$row['color'] = NULL;
		$row['department'] = NULL;
		$row['designation'] = NULL;
		$row['assign_to'] = NULL;
		$row['date_assign'] = NULL;
		$row['assign_action'] = NULL;
		$row['assign_comment'] = NULL;
		$row['assign_by'] = NULL;
		$row['statuse'] = NULL;
		$row['date_retrieved'] = NULL;
		$row['retriever_action'] = NULL;
		$row['retriever_comment'] = NULL;
		$row['retrieved_by'] = NULL;
		$row['staff_id'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->issued_date->DbValue = $row['issued_date'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->asset_tag->DbValue = $row['asset_tag'];
		$this->make->DbValue = $row['make'];
		$this->ram->DbValue = $row['ram'];
		$this->hard_disk->DbValue = $row['hard_disk'];
		$this->color->DbValue = $row['color'];
		$this->department->DbValue = $row['department'];
		$this->designation->DbValue = $row['designation'];
		$this->assign_to->DbValue = $row['assign_to'];
		$this->date_assign->DbValue = $row['date_assign'];
		$this->assign_action->DbValue = $row['assign_action'];
		$this->assign_comment->DbValue = $row['assign_comment'];
		$this->assign_by->DbValue = $row['assign_by'];
		$this->statuse->DbValue = $row['statuse'];
		$this->date_retrieved->DbValue = $row['date_retrieved'];
		$this->retriever_action->DbValue = $row['retriever_action'];
		$this->retriever_comment->DbValue = $row['retriever_comment'];
		$this->retrieved_by->DbValue = $row['retrieved_by'];
		$this->staff_id->DbValue = $row['staff_id'];
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
		// issued_date
		// reference_id
		// asset_tag
		// make
		// ram
		// hard_disk
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

		// ram
		$this->ram->ViewValue = $this->ram->CurrentValue;
		$this->ram->ViewCustomAttributes = "";

		// hard_disk
		$this->hard_disk->ViewValue = $this->hard_disk->CurrentValue;
		$this->hard_disk->ViewCustomAttributes = "";

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

			// issued_date
			$this->issued_date->LinkCustomAttributes = "";
			$this->issued_date->HrefValue = "";
			$this->issued_date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";
			if ($this->Export == "")
				$this->reference_id->ViewValue = $this->HighlightValue($this->reference_id);

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";
			$this->asset_tag->TooltipValue = "";
			if ($this->Export == "")
				$this->asset_tag->ViewValue = $this->HighlightValue($this->asset_tag);

			// make
			$this->make->LinkCustomAttributes = "";
			$this->make->HrefValue = "";
			$this->make->TooltipValue = "";
			if ($this->Export == "")
				$this->make->ViewValue = $this->HighlightValue($this->make);

			// ram
			$this->ram->LinkCustomAttributes = "";
			$this->ram->HrefValue = "";
			$this->ram->TooltipValue = "";
			if ($this->Export == "")
				$this->ram->ViewValue = $this->HighlightValue($this->ram);

			// hard_disk
			$this->hard_disk->LinkCustomAttributes = "";
			$this->hard_disk->HrefValue = "";
			$this->hard_disk->TooltipValue = "";
			if ($this->Export == "")
				$this->hard_disk->ViewValue = $this->HighlightValue($this->hard_disk);

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";
			if ($this->Export == "")
				$this->color->ViewValue = $this->HighlightValue($this->color);

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

			// retrieved_by
			$this->retrieved_by->LinkCustomAttributes = "";
			$this->retrieved_by->HrefValue = "";
			$this->retrieved_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

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

			// ram
			$this->ram->EditAttrs["class"] = "form-control";
			$this->ram->EditCustomAttributes = "";
			$this->ram->EditValue = ew_HtmlEncode($this->ram->AdvancedSearch->SearchValue);
			$this->ram->PlaceHolder = ew_RemoveHtml($this->ram->FldCaption());

			// hard_disk
			$this->hard_disk->EditAttrs["class"] = "form-control";
			$this->hard_disk->EditCustomAttributes = "";
			$this->hard_disk->EditValue = ew_HtmlEncode($this->hard_disk->AdvancedSearch->SearchValue);
			$this->hard_disk->PlaceHolder = ew_RemoveHtml($this->hard_disk->FldCaption());

			// color
			$this->color->EditAttrs["class"] = "form-control";
			$this->color->EditCustomAttributes = "";
			$this->color->EditValue = ew_HtmlEncode($this->color->AdvancedSearch->SearchValue);
			$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";

			// designation
			$this->designation->EditAttrs["class"] = "form-control";
			$this->designation->EditCustomAttributes = "";

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

			// date_retrieved
			$this->date_retrieved->EditAttrs["class"] = "form-control";
			$this->date_retrieved->EditCustomAttributes = "";
			$this->date_retrieved->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_retrieved->AdvancedSearch->SearchValue, 17), 17));
			$this->date_retrieved->PlaceHolder = ew_RemoveHtml($this->date_retrieved->FldCaption());

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
		$this->issued_date->AdvancedSearch->Load();
		$this->reference_id->AdvancedSearch->Load();
		$this->asset_tag->AdvancedSearch->Load();
		$this->make->AdvancedSearch->Load();
		$this->ram->AdvancedSearch->Load();
		$this->hard_disk->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_pc_issuance_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pc_issuance_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpc_issuance_reportlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($pc_issuance_report_list)) $pc_issuance_report_list = new cpc_issuance_report_list();

// Page init
$pc_issuance_report_list->Page_Init();

// Page main
$pc_issuance_report_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pc_issuance_report_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pc_issuance_report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpc_issuance_reportlist = new ew_Form("fpc_issuance_reportlist", "list");
fpc_issuance_reportlist.FormKeyCountName = '<?php echo $pc_issuance_report_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpc_issuance_reportlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpc_issuance_reportlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpc_issuance_reportlist.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fpc_issuance_reportlist.Lists["x_department"].Data = "<?php echo $pc_issuance_report_list->department->LookupFilterQuery(FALSE, "list") ?>";
fpc_issuance_reportlist.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
fpc_issuance_reportlist.Lists["x_designation"].Data = "<?php echo $pc_issuance_report_list->designation->LookupFilterQuery(FALSE, "list") ?>";
fpc_issuance_reportlist.Lists["x_assign_to"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportlist.Lists["x_assign_to"].Data = "<?php echo $pc_issuance_report_list->assign_to->LookupFilterQuery(FALSE, "list") ?>";
fpc_issuance_reportlist.Lists["x_assign_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportlist.Lists["x_assign_by"].Data = "<?php echo $pc_issuance_report_list->assign_by->LookupFilterQuery(FALSE, "list") ?>";
fpc_issuance_reportlist.Lists["x_statuse[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"system_status"};
fpc_issuance_reportlist.Lists["x_statuse[]"].Data = "<?php echo $pc_issuance_report_list->statuse->LookupFilterQuery(FALSE, "list") ?>";
fpc_issuance_reportlist.Lists["x_retrieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportlist.Lists["x_retrieved_by"].Data = "<?php echo $pc_issuance_report_list->retrieved_by->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fpc_issuance_reportlistsrch = new ew_Form("fpc_issuance_reportlistsrch");

// Validate function for search
fpc_issuance_reportlistsrch.Validate = function(fobj) {
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
fpc_issuance_reportlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpc_issuance_reportlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpc_issuance_reportlistsrch.Lists["x_assign_to"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportlistsrch.Lists["x_assign_to"].Data = "<?php echo $pc_issuance_report_list->assign_to->LookupFilterQuery(FALSE, "extbs") ?>";
fpc_issuance_reportlistsrch.Lists["x_assign_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportlistsrch.Lists["x_assign_by"].Data = "<?php echo $pc_issuance_report_list->assign_by->LookupFilterQuery(FALSE, "extbs") ?>";
fpc_issuance_reportlistsrch.Lists["x_retrieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuance_reportlistsrch.Lists["x_retrieved_by"].Data = "<?php echo $pc_issuance_report_list->retrieved_by->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pc_issuance_report->Export == "") { ?>
<div class="ewToolbar">
<?php if ($pc_issuance_report_list->TotalRecs > 0 && $pc_issuance_report_list->ExportOptions->Visible()) { ?>
<?php $pc_issuance_report_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($pc_issuance_report_list->SearchOptions->Visible()) { ?>
<?php $pc_issuance_report_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($pc_issuance_report_list->FilterOptions->Visible()) { ?>
<?php $pc_issuance_report_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $pc_issuance_report_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pc_issuance_report_list->TotalRecs <= 0)
			$pc_issuance_report_list->TotalRecs = $pc_issuance_report->ListRecordCount();
	} else {
		if (!$pc_issuance_report_list->Recordset && ($pc_issuance_report_list->Recordset = $pc_issuance_report_list->LoadRecordset()))
			$pc_issuance_report_list->TotalRecs = $pc_issuance_report_list->Recordset->RecordCount();
	}
	$pc_issuance_report_list->StartRec = 1;
	if ($pc_issuance_report_list->DisplayRecs <= 0 || ($pc_issuance_report->Export <> "" && $pc_issuance_report->ExportAll)) // Display all records
		$pc_issuance_report_list->DisplayRecs = $pc_issuance_report_list->TotalRecs;
	if (!($pc_issuance_report->Export <> "" && $pc_issuance_report->ExportAll))
		$pc_issuance_report_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pc_issuance_report_list->Recordset = $pc_issuance_report_list->LoadRecordset($pc_issuance_report_list->StartRec-1, $pc_issuance_report_list->DisplayRecs);

	// Set no record found message
	if ($pc_issuance_report->CurrentAction == "" && $pc_issuance_report_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$pc_issuance_report_list->setWarningMessage(ew_DeniedMsg());
		if ($pc_issuance_report_list->SearchWhere == "0=101")
			$pc_issuance_report_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pc_issuance_report_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$pc_issuance_report_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($pc_issuance_report->Export == "" && $pc_issuance_report->CurrentAction == "") { ?>
<form name="fpc_issuance_reportlistsrch" id="fpc_issuance_reportlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($pc_issuance_report_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fpc_issuance_reportlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pc_issuance_report">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$pc_issuance_report_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$pc_issuance_report->RowType = EW_ROWTYPE_SEARCH;

// Render row
$pc_issuance_report->ResetAttrs();
$pc_issuance_report_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($pc_issuance_report->issued_date->Visible) { // issued_date ?>
	<div id="xsc_issued_date" class="ewCell form-group">
		<label for="x_issued_date" class="ewSearchCaption ewLabel"><?php echo $pc_issuance_report->issued_date->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_issued_date" id="z_issued_date" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="pc_issuance_report" data-field="x_issued_date" data-format="14" name="x_issued_date" id="x_issued_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->issued_date->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->issued_date->EditValue ?>"<?php echo $pc_issuance_report->issued_date->EditAttributes() ?>>
<?php if (!$pc_issuance_report->issued_date->ReadOnly && !$pc_issuance_report->issued_date->Disabled && !isset($pc_issuance_report->issued_date->EditAttrs["readonly"]) && !isset($pc_issuance_report->issued_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpc_issuance_reportlistsrch", "x_issued_date", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_issued_date">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_issued_date">
<input type="text" data-table="pc_issuance_report" data-field="x_issued_date" data-format="14" name="y_issued_date" id="y_issued_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->issued_date->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->issued_date->EditValue2 ?>"<?php echo $pc_issuance_report->issued_date->EditAttributes() ?>>
<?php if (!$pc_issuance_report->issued_date->ReadOnly && !$pc_issuance_report->issued_date->Disabled && !isset($pc_issuance_report->issued_date->EditAttrs["readonly"]) && !isset($pc_issuance_report->issued_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpc_issuance_reportlistsrch", "y_issued_date", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($pc_issuance_report->reference_id->Visible) { // reference_id ?>
	<div id="xsc_reference_id" class="ewCell form-group">
		<label for="x_reference_id" class="ewSearchCaption ewLabel"><?php echo $pc_issuance_report->reference_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_reference_id" id="z_reference_id" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="pc_issuance_report" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance_report->reference_id->getPlaceHolder()) ?>" value="<?php echo $pc_issuance_report->reference_id->EditValue ?>"<?php echo $pc_issuance_report->reference_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($pc_issuance_report->assign_to->Visible) { // assign_to ?>
	<div id="xsc_assign_to" class="ewCell form-group">
		<label for="x_assign_to" class="ewSearchCaption ewLabel"><?php echo $pc_issuance_report->assign_to->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assign_to" id="z_assign_to" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign_to"><?php echo (strval($pc_issuance_report->assign_to->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->assign_to->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->assign_to->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign_to',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->assign_to->ReadOnly || $pc_issuance_report->assign_to->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_assign_to" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->assign_to->DisplayValueSeparatorAttribute() ?>" name="x_assign_to" id="x_assign_to" value="<?php echo $pc_issuance_report->assign_to->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->assign_to->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($pc_issuance_report->assign_by->Visible) { // assign_by ?>
	<div id="xsc_assign_by" class="ewCell form-group">
		<label for="x_assign_by" class="ewSearchCaption ewLabel"><?php echo $pc_issuance_report->assign_by->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_assign_by" id="z_assign_by" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign_by"><?php echo (strval($pc_issuance_report->assign_by->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->assign_by->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->assign_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign_by',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->assign_by->ReadOnly || $pc_issuance_report->assign_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_assign_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->assign_by->DisplayValueSeparatorAttribute() ?>" name="x_assign_by" id="x_assign_by" value="<?php echo $pc_issuance_report->assign_by->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->assign_by->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($pc_issuance_report->retrieved_by->Visible) { // retrieved_by ?>
	<div id="xsc_retrieved_by" class="ewCell form-group">
		<label for="x_retrieved_by" class="ewSearchCaption ewLabel"><?php echo $pc_issuance_report->retrieved_by->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_retrieved_by" id="z_retrieved_by" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_retrieved_by"><?php echo (strval($pc_issuance_report->retrieved_by->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pc_issuance_report->retrieved_by->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pc_issuance_report->retrieved_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_retrieved_by',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($pc_issuance_report->retrieved_by->ReadOnly || $pc_issuance_report->retrieved_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pc_issuance_report" data-field="x_retrieved_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pc_issuance_report->retrieved_by->DisplayValueSeparatorAttribute() ?>" name="x_retrieved_by" id="x_retrieved_by" value="<?php echo $pc_issuance_report->retrieved_by->AdvancedSearch->SearchValue ?>"<?php echo $pc_issuance_report->retrieved_by->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($pc_issuance_report_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($pc_issuance_report_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $pc_issuance_report_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($pc_issuance_report_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($pc_issuance_report_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($pc_issuance_report_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($pc_issuance_report_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $pc_issuance_report_list->ShowPageHeader(); ?>
<?php
$pc_issuance_report_list->ShowMessage();
?>
<?php if ($pc_issuance_report_list->TotalRecs > 0 || $pc_issuance_report->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($pc_issuance_report_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> pc_issuance_report">
<?php if ($pc_issuance_report->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($pc_issuance_report->CurrentAction <> "gridadd" && $pc_issuance_report->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pc_issuance_report_list->Pager)) $pc_issuance_report_list->Pager = new cPrevNextPager($pc_issuance_report_list->StartRec, $pc_issuance_report_list->DisplayRecs, $pc_issuance_report_list->TotalRecs, $pc_issuance_report_list->AutoHidePager) ?>
<?php if ($pc_issuance_report_list->Pager->RecordCount > 0 && $pc_issuance_report_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pc_issuance_report_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pc_issuance_report_list->PageUrl() ?>start=<?php echo $pc_issuance_report_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pc_issuance_report_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pc_issuance_report_list->PageUrl() ?>start=<?php echo $pc_issuance_report_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pc_issuance_report_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pc_issuance_report_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pc_issuance_report_list->PageUrl() ?>start=<?php echo $pc_issuance_report_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pc_issuance_report_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pc_issuance_report_list->PageUrl() ?>start=<?php echo $pc_issuance_report_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pc_issuance_report_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($pc_issuance_report_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pc_issuance_report_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pc_issuance_report_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pc_issuance_report_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pc_issuance_report_list->TotalRecs > 0 && (!$pc_issuance_report_list->AutoHidePageSizeSelector || $pc_issuance_report_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pc_issuance_report">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($pc_issuance_report_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($pc_issuance_report_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($pc_issuance_report_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($pc_issuance_report_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($pc_issuance_report_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($pc_issuance_report->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pc_issuance_report_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fpc_issuance_reportlist" id="fpc_issuance_reportlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pc_issuance_report_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pc_issuance_report_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pc_issuance_report">
<div id="gmp_pc_issuance_report" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($pc_issuance_report_list->TotalRecs > 0 || $pc_issuance_report->CurrentAction == "gridedit") { ?>
<table id="tbl_pc_issuance_reportlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$pc_issuance_report_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pc_issuance_report_list->RenderListOptions();

// Render list options (header, left)
$pc_issuance_report_list->ListOptions->Render("header", "left");
?>
<?php if ($pc_issuance_report->issued_date->Visible) { // issued_date ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->issued_date) == "") { ?>
		<th data-name="issued_date" class="<?php echo $pc_issuance_report->issued_date->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_issued_date" class="pc_issuance_report_issued_date"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->issued_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="issued_date" class="<?php echo $pc_issuance_report->issued_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->issued_date) ?>',1);"><div id="elh_pc_issuance_report_issued_date" class="pc_issuance_report_issued_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->issued_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->issued_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->issued_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->reference_id->Visible) { // reference_id ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->reference_id) == "") { ?>
		<th data-name="reference_id" class="<?php echo $pc_issuance_report->reference_id->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_reference_id" class="pc_issuance_report_reference_id"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->reference_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reference_id" class="<?php echo $pc_issuance_report->reference_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->reference_id) ?>',1);"><div id="elh_pc_issuance_report_reference_id" class="pc_issuance_report_reference_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->reference_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->reference_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->reference_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->asset_tag->Visible) { // asset_tag ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->asset_tag) == "") { ?>
		<th data-name="asset_tag" class="<?php echo $pc_issuance_report->asset_tag->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_asset_tag" class="pc_issuance_report_asset_tag"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->asset_tag->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="asset_tag" class="<?php echo $pc_issuance_report->asset_tag->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->asset_tag) ?>',1);"><div id="elh_pc_issuance_report_asset_tag" class="pc_issuance_report_asset_tag">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->asset_tag->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->asset_tag->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->asset_tag->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->make->Visible) { // make ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->make) == "") { ?>
		<th data-name="make" class="<?php echo $pc_issuance_report->make->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_make" class="pc_issuance_report_make"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->make->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="make" class="<?php echo $pc_issuance_report->make->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->make) ?>',1);"><div id="elh_pc_issuance_report_make" class="pc_issuance_report_make">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->make->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->make->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->make->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->ram->Visible) { // ram ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->ram) == "") { ?>
		<th data-name="ram" class="<?php echo $pc_issuance_report->ram->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_ram" class="pc_issuance_report_ram"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->ram->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ram" class="<?php echo $pc_issuance_report->ram->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->ram) ?>',1);"><div id="elh_pc_issuance_report_ram" class="pc_issuance_report_ram">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->ram->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->ram->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->ram->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->hard_disk->Visible) { // hard_disk ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->hard_disk) == "") { ?>
		<th data-name="hard_disk" class="<?php echo $pc_issuance_report->hard_disk->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_hard_disk" class="pc_issuance_report_hard_disk"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->hard_disk->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hard_disk" class="<?php echo $pc_issuance_report->hard_disk->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->hard_disk) ?>',1);"><div id="elh_pc_issuance_report_hard_disk" class="pc_issuance_report_hard_disk">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->hard_disk->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->hard_disk->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->hard_disk->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->color->Visible) { // color ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->color) == "") { ?>
		<th data-name="color" class="<?php echo $pc_issuance_report->color->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_color" class="pc_issuance_report_color"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->color->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="color" class="<?php echo $pc_issuance_report->color->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->color) ?>',1);"><div id="elh_pc_issuance_report_color" class="pc_issuance_report_color">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->color->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->color->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->color->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->department->Visible) { // department ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->department) == "") { ?>
		<th data-name="department" class="<?php echo $pc_issuance_report->department->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_department" class="pc_issuance_report_department"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $pc_issuance_report->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->department) ?>',1);"><div id="elh_pc_issuance_report_department" class="pc_issuance_report_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->designation->Visible) { // designation ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->designation) == "") { ?>
		<th data-name="designation" class="<?php echo $pc_issuance_report->designation->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_designation" class="pc_issuance_report_designation"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->designation->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="designation" class="<?php echo $pc_issuance_report->designation->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->designation) ?>',1);"><div id="elh_pc_issuance_report_designation" class="pc_issuance_report_designation">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->designation->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->designation->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->designation->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->assign_to->Visible) { // assign_to ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->assign_to) == "") { ?>
		<th data-name="assign_to" class="<?php echo $pc_issuance_report->assign_to->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_assign_to" class="pc_issuance_report_assign_to"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->assign_to->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="assign_to" class="<?php echo $pc_issuance_report->assign_to->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->assign_to) ?>',1);"><div id="elh_pc_issuance_report_assign_to" class="pc_issuance_report_assign_to">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->assign_to->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->assign_to->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->assign_to->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->date_assign->Visible) { // date_assign ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->date_assign) == "") { ?>
		<th data-name="date_assign" class="<?php echo $pc_issuance_report->date_assign->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_date_assign" class="pc_issuance_report_date_assign"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->date_assign->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_assign" class="<?php echo $pc_issuance_report->date_assign->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->date_assign) ?>',1);"><div id="elh_pc_issuance_report_date_assign" class="pc_issuance_report_date_assign">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->date_assign->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->date_assign->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->date_assign->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->assign_by->Visible) { // assign_by ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->assign_by) == "") { ?>
		<th data-name="assign_by" class="<?php echo $pc_issuance_report->assign_by->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_assign_by" class="pc_issuance_report_assign_by"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->assign_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="assign_by" class="<?php echo $pc_issuance_report->assign_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->assign_by) ?>',1);"><div id="elh_pc_issuance_report_assign_by" class="pc_issuance_report_assign_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->assign_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->assign_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->assign_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->statuse->Visible) { // statuse ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->statuse) == "") { ?>
		<th data-name="statuse" class="<?php echo $pc_issuance_report->statuse->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_statuse" class="pc_issuance_report_statuse"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->statuse->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="statuse" class="<?php echo $pc_issuance_report->statuse->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->statuse) ?>',1);"><div id="elh_pc_issuance_report_statuse" class="pc_issuance_report_statuse">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->statuse->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->statuse->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->statuse->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->date_retrieved->Visible) { // date_retrieved ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->date_retrieved) == "") { ?>
		<th data-name="date_retrieved" class="<?php echo $pc_issuance_report->date_retrieved->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_date_retrieved" class="pc_issuance_report_date_retrieved"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->date_retrieved->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_retrieved" class="<?php echo $pc_issuance_report->date_retrieved->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->date_retrieved) ?>',1);"><div id="elh_pc_issuance_report_date_retrieved" class="pc_issuance_report_date_retrieved">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->date_retrieved->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->date_retrieved->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->date_retrieved->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($pc_issuance_report->retrieved_by->Visible) { // retrieved_by ?>
	<?php if ($pc_issuance_report->SortUrl($pc_issuance_report->retrieved_by) == "") { ?>
		<th data-name="retrieved_by" class="<?php echo $pc_issuance_report->retrieved_by->HeaderCellClass() ?>"><div id="elh_pc_issuance_report_retrieved_by" class="pc_issuance_report_retrieved_by"><div class="ewTableHeaderCaption"><?php echo $pc_issuance_report->retrieved_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="retrieved_by" class="<?php echo $pc_issuance_report->retrieved_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pc_issuance_report->SortUrl($pc_issuance_report->retrieved_by) ?>',1);"><div id="elh_pc_issuance_report_retrieved_by" class="pc_issuance_report_retrieved_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pc_issuance_report->retrieved_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pc_issuance_report->retrieved_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pc_issuance_report->retrieved_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$pc_issuance_report_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($pc_issuance_report->ExportAll && $pc_issuance_report->Export <> "") {
	$pc_issuance_report_list->StopRec = $pc_issuance_report_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pc_issuance_report_list->TotalRecs > $pc_issuance_report_list->StartRec + $pc_issuance_report_list->DisplayRecs - 1)
		$pc_issuance_report_list->StopRec = $pc_issuance_report_list->StartRec + $pc_issuance_report_list->DisplayRecs - 1;
	else
		$pc_issuance_report_list->StopRec = $pc_issuance_report_list->TotalRecs;
}
$pc_issuance_report_list->RecCnt = $pc_issuance_report_list->StartRec - 1;
if ($pc_issuance_report_list->Recordset && !$pc_issuance_report_list->Recordset->EOF) {
	$pc_issuance_report_list->Recordset->MoveFirst();
	$bSelectLimit = $pc_issuance_report_list->UseSelectLimit;
	if (!$bSelectLimit && $pc_issuance_report_list->StartRec > 1)
		$pc_issuance_report_list->Recordset->Move($pc_issuance_report_list->StartRec - 1);
} elseif (!$pc_issuance_report->AllowAddDeleteRow && $pc_issuance_report_list->StopRec == 0) {
	$pc_issuance_report_list->StopRec = $pc_issuance_report->GridAddRowCount;
}

// Initialize aggregate
$pc_issuance_report->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pc_issuance_report->ResetAttrs();
$pc_issuance_report_list->RenderRow();
while ($pc_issuance_report_list->RecCnt < $pc_issuance_report_list->StopRec) {
	$pc_issuance_report_list->RecCnt++;
	if (intval($pc_issuance_report_list->RecCnt) >= intval($pc_issuance_report_list->StartRec)) {
		$pc_issuance_report_list->RowCnt++;

		// Set up key count
		$pc_issuance_report_list->KeyCount = $pc_issuance_report_list->RowIndex;

		// Init row class and style
		$pc_issuance_report->ResetAttrs();
		$pc_issuance_report->CssClass = "";
		if ($pc_issuance_report->CurrentAction == "gridadd") {
		} else {
			$pc_issuance_report_list->LoadRowValues($pc_issuance_report_list->Recordset); // Load row values
		}
		$pc_issuance_report->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$pc_issuance_report->RowAttrs = array_merge($pc_issuance_report->RowAttrs, array('data-rowindex'=>$pc_issuance_report_list->RowCnt, 'id'=>'r' . $pc_issuance_report_list->RowCnt . '_pc_issuance_report', 'data-rowtype'=>$pc_issuance_report->RowType));

		// Render row
		$pc_issuance_report_list->RenderRow();

		// Render list options
		$pc_issuance_report_list->RenderListOptions();
?>
	<tr<?php echo $pc_issuance_report->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pc_issuance_report_list->ListOptions->Render("body", "left", $pc_issuance_report_list->RowCnt);
?>
	<?php if ($pc_issuance_report->issued_date->Visible) { // issued_date ?>
		<td data-name="issued_date"<?php echo $pc_issuance_report->issued_date->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_issued_date" class="pc_issuance_report_issued_date">
<span<?php echo $pc_issuance_report->issued_date->ViewAttributes() ?>>
<?php echo $pc_issuance_report->issued_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->reference_id->Visible) { // reference_id ?>
		<td data-name="reference_id"<?php echo $pc_issuance_report->reference_id->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_reference_id" class="pc_issuance_report_reference_id">
<span<?php echo $pc_issuance_report->reference_id->ViewAttributes() ?>>
<?php echo $pc_issuance_report->reference_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->asset_tag->Visible) { // asset_tag ?>
		<td data-name="asset_tag"<?php echo $pc_issuance_report->asset_tag->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_asset_tag" class="pc_issuance_report_asset_tag">
<span<?php echo $pc_issuance_report->asset_tag->ViewAttributes() ?>>
<?php echo $pc_issuance_report->asset_tag->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->make->Visible) { // make ?>
		<td data-name="make"<?php echo $pc_issuance_report->make->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_make" class="pc_issuance_report_make">
<span<?php echo $pc_issuance_report->make->ViewAttributes() ?>>
<?php echo $pc_issuance_report->make->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->ram->Visible) { // ram ?>
		<td data-name="ram"<?php echo $pc_issuance_report->ram->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_ram" class="pc_issuance_report_ram">
<span<?php echo $pc_issuance_report->ram->ViewAttributes() ?>>
<?php echo $pc_issuance_report->ram->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->hard_disk->Visible) { // hard_disk ?>
		<td data-name="hard_disk"<?php echo $pc_issuance_report->hard_disk->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_hard_disk" class="pc_issuance_report_hard_disk">
<span<?php echo $pc_issuance_report->hard_disk->ViewAttributes() ?>>
<?php echo $pc_issuance_report->hard_disk->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->color->Visible) { // color ?>
		<td data-name="color"<?php echo $pc_issuance_report->color->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_color" class="pc_issuance_report_color">
<span<?php echo $pc_issuance_report->color->ViewAttributes() ?>>
<?php echo $pc_issuance_report->color->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->department->Visible) { // department ?>
		<td data-name="department"<?php echo $pc_issuance_report->department->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_department" class="pc_issuance_report_department">
<span<?php echo $pc_issuance_report->department->ViewAttributes() ?>>
<?php echo $pc_issuance_report->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->designation->Visible) { // designation ?>
		<td data-name="designation"<?php echo $pc_issuance_report->designation->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_designation" class="pc_issuance_report_designation">
<span<?php echo $pc_issuance_report->designation->ViewAttributes() ?>>
<?php echo $pc_issuance_report->designation->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->assign_to->Visible) { // assign_to ?>
		<td data-name="assign_to"<?php echo $pc_issuance_report->assign_to->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_assign_to" class="pc_issuance_report_assign_to">
<span<?php echo $pc_issuance_report->assign_to->ViewAttributes() ?>>
<?php echo $pc_issuance_report->assign_to->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->date_assign->Visible) { // date_assign ?>
		<td data-name="date_assign"<?php echo $pc_issuance_report->date_assign->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_date_assign" class="pc_issuance_report_date_assign">
<span<?php echo $pc_issuance_report->date_assign->ViewAttributes() ?>>
<?php echo $pc_issuance_report->date_assign->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->assign_by->Visible) { // assign_by ?>
		<td data-name="assign_by"<?php echo $pc_issuance_report->assign_by->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_assign_by" class="pc_issuance_report_assign_by">
<span<?php echo $pc_issuance_report->assign_by->ViewAttributes() ?>>
<?php echo $pc_issuance_report->assign_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->statuse->Visible) { // statuse ?>
		<td data-name="statuse"<?php echo $pc_issuance_report->statuse->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_statuse" class="pc_issuance_report_statuse">
<span<?php echo $pc_issuance_report->statuse->ViewAttributes() ?>>
<?php echo $pc_issuance_report->statuse->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->date_retrieved->Visible) { // date_retrieved ?>
		<td data-name="date_retrieved"<?php echo $pc_issuance_report->date_retrieved->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_date_retrieved" class="pc_issuance_report_date_retrieved">
<span<?php echo $pc_issuance_report->date_retrieved->ViewAttributes() ?>>
<?php echo $pc_issuance_report->date_retrieved->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($pc_issuance_report->retrieved_by->Visible) { // retrieved_by ?>
		<td data-name="retrieved_by"<?php echo $pc_issuance_report->retrieved_by->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_report_list->RowCnt ?>_pc_issuance_report_retrieved_by" class="pc_issuance_report_retrieved_by">
<span<?php echo $pc_issuance_report->retrieved_by->ViewAttributes() ?>>
<?php echo $pc_issuance_report->retrieved_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pc_issuance_report_list->ListOptions->Render("body", "right", $pc_issuance_report_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($pc_issuance_report->CurrentAction <> "gridadd")
		$pc_issuance_report_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pc_issuance_report->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pc_issuance_report_list->Recordset)
	$pc_issuance_report_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($pc_issuance_report_list->TotalRecs == 0 && $pc_issuance_report->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pc_issuance_report_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pc_issuance_report->Export == "") { ?>
<script type="text/javascript">
fpc_issuance_reportlistsrch.FilterList = <?php echo $pc_issuance_report_list->GetFilterList() ?>;
fpc_issuance_reportlistsrch.Init();
fpc_issuance_reportlist.Init();
</script>
<?php } ?>
<?php
$pc_issuance_report_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pc_issuance_report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pc_issuance_report_list->Page_Terminate();
?>
