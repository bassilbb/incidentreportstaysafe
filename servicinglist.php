<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "servicinginfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$servicing_list = NULL; // Initialize page object first

class cservicing_list extends cservicing {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'servicing';

	// Page object name
	var $PageObjName = 'servicing_list';

	// Grid form hidden field names
	var $FormName = 'fservicinglist';
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

		// Table object (servicing)
		if (!isset($GLOBALS["servicing"]) || get_class($GLOBALS["servicing"]) == "cservicing") {
			$GLOBALS["servicing"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["servicing"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "servicingadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "servicingdelete.php";
		$this->MultiUpdateUrl = "servicingupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'servicing', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fservicinglistsrch";

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
		$this->priority->SetVisibility();
		$this->description->SetVisibility();
		$this->status->SetVisibility();
		$this->date_maintained->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->maintained_by->SetVisibility();
		$this->reviewed_date->SetVisibility();
		$this->reviewed_action->SetVisibility();
		$this->reviewed_by->SetVisibility();
		$this->staff_no->SetVisibility();

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
		global $EW_EXPORT, $servicing;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($servicing);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fservicinglistsrch");
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
		$sFilterList = ew_Concat($sFilterList, $this->description->AdvancedSearch->ToJson(), ","); // Field description
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->date_maintained->AdvancedSearch->ToJson(), ","); // Field date_maintained
		$sFilterList = ew_Concat($sFilterList, $this->initiator_action->AdvancedSearch->ToJson(), ","); // Field initiator_action
		$sFilterList = ew_Concat($sFilterList, $this->initiator_comment->AdvancedSearch->ToJson(), ","); // Field initiator_comment
		$sFilterList = ew_Concat($sFilterList, $this->maintained_by->AdvancedSearch->ToJson(), ","); // Field maintained_by
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_date->AdvancedSearch->ToJson(), ","); // Field reviewed_date
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_action->AdvancedSearch->ToJson(), ","); // Field reviewed_action
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_comment->AdvancedSearch->ToJson(), ","); // Field reviewed_comment
		$sFilterList = ew_Concat($sFilterList, $this->reviewed_by->AdvancedSearch->ToJson(), ","); // Field reviewed_by
		$sFilterList = ew_Concat($sFilterList, $this->staff_no->AdvancedSearch->ToJson(), ","); // Field staff_no
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fservicinglistsrch", $filters);

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

		// Field description
		$this->description->AdvancedSearch->SearchValue = @$filter["x_description"];
		$this->description->AdvancedSearch->SearchOperator = @$filter["z_description"];
		$this->description->AdvancedSearch->SearchCondition = @$filter["v_description"];
		$this->description->AdvancedSearch->SearchValue2 = @$filter["y_description"];
		$this->description->AdvancedSearch->SearchOperator2 = @$filter["w_description"];
		$this->description->AdvancedSearch->Save();

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

		// Field staff_no
		$this->staff_no->AdvancedSearch->SearchValue = @$filter["x_staff_no"];
		$this->staff_no->AdvancedSearch->SearchOperator = @$filter["z_staff_no"];
		$this->staff_no->AdvancedSearch->SearchCondition = @$filter["v_staff_no"];
		$this->staff_no->AdvancedSearch->SearchValue2 = @$filter["y_staff_no"];
		$this->staff_no->AdvancedSearch->SearchOperator2 = @$filter["w_staff_no"];
		$this->staff_no->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->reference_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staff_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->initiator_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->reviewed_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staff_no, $arKeywords, $type);
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
			$this->UpdateSort($this->date_initiated); // date_initiated
			$this->UpdateSort($this->reference_id); // reference_id
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->staff_name); // staff_name
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->branch); // branch
			$this->UpdateSort($this->buildings); // buildings
			$this->UpdateSort($this->floors); // floors
			$this->UpdateSort($this->items); // items
			$this->UpdateSort($this->priority); // priority
			$this->UpdateSort($this->description); // description
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->date_maintained); // date_maintained
			$this->UpdateSort($this->initiator_action); // initiator_action
			$this->UpdateSort($this->maintained_by); // maintained_by
			$this->UpdateSort($this->reviewed_date); // reviewed_date
			$this->UpdateSort($this->reviewed_action); // reviewed_action
			$this->UpdateSort($this->reviewed_by); // reviewed_by
			$this->UpdateSort($this->staff_no); // staff_no
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
				$this->priority->setSort("");
				$this->description->setSort("");
				$this->status->setSort("");
				$this->date_maintained->setSort("");
				$this->initiator_action->setSort("");
				$this->maintained_by->setSort("");
				$this->reviewed_date->setSort("");
				$this->reviewed_action->setSort("");
				$this->reviewed_by->setSort("");
				$this->staff_no->setSort("");
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
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fservicinglistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fservicinglistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fservicinglist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fservicinglistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		$this->description->setDbValue($row['description']);
		$this->status->setDbValue($row['status']);
		$this->date_maintained->setDbValue($row['date_maintained']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->maintained_by->setDbValue($row['maintained_by']);
		$this->reviewed_date->setDbValue($row['reviewed_date']);
		$this->reviewed_action->setDbValue($row['reviewed_action']);
		$this->reviewed_comment->setDbValue($row['reviewed_comment']);
		$this->reviewed_by->setDbValue($row['reviewed_by']);
		$this->staff_no->setDbValue($row['staff_no']);
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
		$row['description'] = NULL;
		$row['status'] = NULL;
		$row['date_maintained'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['maintained_by'] = NULL;
		$row['reviewed_date'] = NULL;
		$row['reviewed_action'] = NULL;
		$row['reviewed_comment'] = NULL;
		$row['reviewed_by'] = NULL;
		$row['staff_no'] = NULL;
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
		$this->description->DbValue = $row['description'];
		$this->status->DbValue = $row['status'];
		$this->date_maintained->DbValue = $row['date_maintained'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->maintained_by->DbValue = $row['maintained_by'];
		$this->reviewed_date->DbValue = $row['reviewed_date'];
		$this->reviewed_action->DbValue = $row['reviewed_action'];
		$this->reviewed_comment->DbValue = $row['reviewed_comment'];
		$this->reviewed_by->DbValue = $row['reviewed_by'];
		$this->staff_no->DbValue = $row['staff_no'];
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
		// description
		// status
		// date_maintained
		// initiator_action
		// initiator_comment
		// maintained_by
		// reviewed_date
		// reviewed_action
		// reviewed_comment
		// reviewed_by
		// staff_no

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
		$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
		if (strval($this->staff_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_name->LookupFilters = array();
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
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->items->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
		$sWhereWrk = "";
		$this->items->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->items->ViewValue = $this->items->DisplayValue($arwrk);
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

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `service_status`";
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
		$this->date_maintained->ViewValue = ew_FormatDateTime($this->date_maintained->ViewValue, 17);
		$this->date_maintained->ViewCustomAttributes = "";

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

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
		$this->reviewed_date->ViewValue = ew_FormatDateTime($this->reviewed_date->ViewValue, 17);
		$this->reviewed_date->ViewCustomAttributes = "";

		// reviewed_action
		if (strval($this->reviewed_action->CurrentValue) <> "") {
			$this->reviewed_action->ViewValue = $this->reviewed_action->OptionCaption($this->reviewed_action->CurrentValue);
		} else {
			$this->reviewed_action->ViewValue = NULL;
		}
		$this->reviewed_action->ViewCustomAttributes = "";

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

		// staff_no
		$this->staff_no->ViewValue = $this->staff_no->CurrentValue;
		$this->staff_no->ViewCustomAttributes = "";

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

			// priority
			$this->priority->LinkCustomAttributes = "";
			$this->priority->HrefValue = "";
			$this->priority->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// date_maintained
			$this->date_maintained->LinkCustomAttributes = "";
			$this->date_maintained->HrefValue = "";
			$this->date_maintained->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// maintained_by
			$this->maintained_by->LinkCustomAttributes = "";
			$this->maintained_by->HrefValue = "";
			$this->maintained_by->TooltipValue = "";

			// reviewed_date
			$this->reviewed_date->LinkCustomAttributes = "";
			$this->reviewed_date->HrefValue = "";
			$this->reviewed_date->TooltipValue = "";

			// reviewed_action
			$this->reviewed_action->LinkCustomAttributes = "";
			$this->reviewed_action->HrefValue = "";
			$this->reviewed_action->TooltipValue = "";

			// reviewed_by
			$this->reviewed_by->LinkCustomAttributes = "";
			$this->reviewed_by->HrefValue = "";
			$this->reviewed_by->TooltipValue = "";

			// staff_no
			$this->staff_no->LinkCustomAttributes = "";
			$this->staff_no->HrefValue = "";
			$this->staff_no->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_servicing\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_servicing',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fservicinglist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
			 $_SESSION['REF_ID'] = generateREFKey();
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
if (!isset($servicing_list)) $servicing_list = new cservicing_list();

// Page init
$servicing_list->Page_Init();

// Page main
$servicing_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicing_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($servicing->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fservicinglist = new ew_Form("fservicinglist", "list");
fservicinglist.FormKeyCountName = '<?php echo $servicing_list->FormKeyCountName ?>';

// Form_CustomValidate event
fservicinglist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fservicinglist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fservicinglist.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicinglist.Lists["x_staff_id"].Data = "<?php echo $servicing_list->staff_id->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_list->staff_id->LookupFilterQuery(TRUE, "list"))) ?>;
fservicinglist.Lists["x_staff_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicinglist.Lists["x_staff_name"].Data = "<?php echo $servicing_list->staff_name->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.AutoSuggests["x_staff_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_list->staff_name->LookupFilterQuery(TRUE, "list"))) ?>;
fservicinglist.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fservicinglist.Lists["x_department"].Data = "<?php echo $servicing_list->department->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_list->department->LookupFilterQuery(TRUE, "list"))) ?>;
fservicinglist.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
fservicinglist.Lists["x_branch"].Data = "<?php echo $servicing_list->branch->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.AutoSuggests["x_branch"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_list->branch->LookupFilterQuery(TRUE, "list"))) ?>;
fservicinglist.Lists["x_buildings"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"buildings"};
fservicinglist.Lists["x_buildings"].Data = "<?php echo $servicing_list->buildings->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.Lists["x_floors"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"floors"};
fservicinglist.Lists["x_floors"].Data = "<?php echo $servicing_list->floors->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.Lists["x_items"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"items"};
fservicinglist.Lists["x_items"].Data = "<?php echo $servicing_list->items->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.Lists["x_priority"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
fservicinglist.Lists["x_priority"].Data = "<?php echo $servicing_list->priority->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"service_status"};
fservicinglist.Lists["x_status"].Data = "<?php echo $servicing_list->status->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicinglist.Lists["x_initiator_action"].Options = <?php echo json_encode($servicing_list->initiator_action->Options()) ?>;
fservicinglist.Lists["x_maintained_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicinglist.Lists["x_maintained_by"].Data = "<?php echo $servicing_list->maintained_by->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.AutoSuggests["x_maintained_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_list->maintained_by->LookupFilterQuery(TRUE, "list"))) ?>;
fservicinglist.Lists["x_reviewed_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicinglist.Lists["x_reviewed_action"].Options = <?php echo json_encode($servicing_list->reviewed_action->Options()) ?>;
fservicinglist.Lists["x_reviewed_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicinglist.Lists["x_reviewed_by"].Data = "<?php echo $servicing_list->reviewed_by->LookupFilterQuery(FALSE, "list") ?>";
fservicinglist.AutoSuggests["x_reviewed_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_list->reviewed_by->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = fservicinglistsrch = new ew_Form("fservicinglistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($servicing->Export == "") { ?>
<div class="ewToolbar">
<?php if ($servicing_list->TotalRecs > 0 && $servicing_list->ExportOptions->Visible()) { ?>
<?php $servicing_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($servicing_list->SearchOptions->Visible()) { ?>
<?php $servicing_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($servicing_list->FilterOptions->Visible()) { ?>
<?php $servicing_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $servicing_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($servicing_list->TotalRecs <= 0)
			$servicing_list->TotalRecs = $servicing->ListRecordCount();
	} else {
		if (!$servicing_list->Recordset && ($servicing_list->Recordset = $servicing_list->LoadRecordset()))
			$servicing_list->TotalRecs = $servicing_list->Recordset->RecordCount();
	}
	$servicing_list->StartRec = 1;
	if ($servicing_list->DisplayRecs <= 0 || ($servicing->Export <> "" && $servicing->ExportAll)) // Display all records
		$servicing_list->DisplayRecs = $servicing_list->TotalRecs;
	if (!($servicing->Export <> "" && $servicing->ExportAll))
		$servicing_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$servicing_list->Recordset = $servicing_list->LoadRecordset($servicing_list->StartRec-1, $servicing_list->DisplayRecs);

	// Set no record found message
	if ($servicing->CurrentAction == "" && $servicing_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$servicing_list->setWarningMessage(ew_DeniedMsg());
		if ($servicing_list->SearchWhere == "0=101")
			$servicing_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$servicing_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$servicing_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($servicing->Export == "" && $servicing->CurrentAction == "") { ?>
<form name="fservicinglistsrch" id="fservicinglistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($servicing_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fservicinglistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="servicing">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($servicing_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($servicing_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $servicing_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($servicing_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($servicing_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($servicing_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($servicing_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $servicing_list->ShowPageHeader(); ?>
<?php
$servicing_list->ShowMessage();
?>
<?php if ($servicing_list->TotalRecs > 0 || $servicing->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($servicing_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> servicing">
<?php if ($servicing->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($servicing->CurrentAction <> "gridadd" && $servicing->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($servicing_list->Pager)) $servicing_list->Pager = new cPrevNextPager($servicing_list->StartRec, $servicing_list->DisplayRecs, $servicing_list->TotalRecs, $servicing_list->AutoHidePager) ?>
<?php if ($servicing_list->Pager->RecordCount > 0 && $servicing_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($servicing_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $servicing_list->PageUrl() ?>start=<?php echo $servicing_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($servicing_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $servicing_list->PageUrl() ?>start=<?php echo $servicing_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $servicing_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($servicing_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $servicing_list->PageUrl() ?>start=<?php echo $servicing_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($servicing_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $servicing_list->PageUrl() ?>start=<?php echo $servicing_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $servicing_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($servicing_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $servicing_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $servicing_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $servicing_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($servicing_list->TotalRecs > 0 && (!$servicing_list->AutoHidePageSizeSelector || $servicing_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="servicing">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($servicing_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($servicing_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($servicing_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($servicing_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($servicing_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($servicing->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($servicing_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fservicinglist" id="fservicinglist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($servicing_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $servicing_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="servicing">
<div id="gmp_servicing" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($servicing_list->TotalRecs > 0 || $servicing->CurrentAction == "gridedit") { ?>
<table id="tbl_servicinglist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$servicing_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$servicing_list->RenderListOptions();

// Render list options (header, left)
$servicing_list->ListOptions->Render("header", "left");
?>
<?php if ($servicing->date_initiated->Visible) { // date_initiated ?>
	<?php if ($servicing->SortUrl($servicing->date_initiated) == "") { ?>
		<th data-name="date_initiated" class="<?php echo $servicing->date_initiated->HeaderCellClass() ?>"><div id="elh_servicing_date_initiated" class="servicing_date_initiated"><div class="ewTableHeaderCaption"><?php echo $servicing->date_initiated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_initiated" class="<?php echo $servicing->date_initiated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->date_initiated) ?>',1);"><div id="elh_servicing_date_initiated" class="servicing_date_initiated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->date_initiated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->date_initiated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->date_initiated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->reference_id->Visible) { // reference_id ?>
	<?php if ($servicing->SortUrl($servicing->reference_id) == "") { ?>
		<th data-name="reference_id" class="<?php echo $servicing->reference_id->HeaderCellClass() ?>"><div id="elh_servicing_reference_id" class="servicing_reference_id"><div class="ewTableHeaderCaption"><?php echo $servicing->reference_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reference_id" class="<?php echo $servicing->reference_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->reference_id) ?>',1);"><div id="elh_servicing_reference_id" class="servicing_reference_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->reference_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($servicing->reference_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->reference_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->staff_id->Visible) { // staff_id ?>
	<?php if ($servicing->SortUrl($servicing->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $servicing->staff_id->HeaderCellClass() ?>"><div id="elh_servicing_staff_id" class="servicing_staff_id"><div class="ewTableHeaderCaption"><?php echo $servicing->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $servicing->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->staff_id) ?>',1);"><div id="elh_servicing_staff_id" class="servicing_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->staff_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($servicing->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->staff_name->Visible) { // staff_name ?>
	<?php if ($servicing->SortUrl($servicing->staff_name) == "") { ?>
		<th data-name="staff_name" class="<?php echo $servicing->staff_name->HeaderCellClass() ?>"><div id="elh_servicing_staff_name" class="servicing_staff_name"><div class="ewTableHeaderCaption"><?php echo $servicing->staff_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_name" class="<?php echo $servicing->staff_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->staff_name) ?>',1);"><div id="elh_servicing_staff_name" class="servicing_staff_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->staff_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->staff_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->staff_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->department->Visible) { // department ?>
	<?php if ($servicing->SortUrl($servicing->department) == "") { ?>
		<th data-name="department" class="<?php echo $servicing->department->HeaderCellClass() ?>"><div id="elh_servicing_department" class="servicing_department"><div class="ewTableHeaderCaption"><?php echo $servicing->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $servicing->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->department) ?>',1);"><div id="elh_servicing_department" class="servicing_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->branch->Visible) { // branch ?>
	<?php if ($servicing->SortUrl($servicing->branch) == "") { ?>
		<th data-name="branch" class="<?php echo $servicing->branch->HeaderCellClass() ?>"><div id="elh_servicing_branch" class="servicing_branch"><div class="ewTableHeaderCaption"><?php echo $servicing->branch->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="branch" class="<?php echo $servicing->branch->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->branch) ?>',1);"><div id="elh_servicing_branch" class="servicing_branch">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->branch->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->branch->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->branch->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->buildings->Visible) { // buildings ?>
	<?php if ($servicing->SortUrl($servicing->buildings) == "") { ?>
		<th data-name="buildings" class="<?php echo $servicing->buildings->HeaderCellClass() ?>"><div id="elh_servicing_buildings" class="servicing_buildings"><div class="ewTableHeaderCaption"><?php echo $servicing->buildings->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="buildings" class="<?php echo $servicing->buildings->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->buildings) ?>',1);"><div id="elh_servicing_buildings" class="servicing_buildings">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->buildings->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->buildings->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->buildings->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->floors->Visible) { // floors ?>
	<?php if ($servicing->SortUrl($servicing->floors) == "") { ?>
		<th data-name="floors" class="<?php echo $servicing->floors->HeaderCellClass() ?>"><div id="elh_servicing_floors" class="servicing_floors"><div class="ewTableHeaderCaption"><?php echo $servicing->floors->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="floors" class="<?php echo $servicing->floors->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->floors) ?>',1);"><div id="elh_servicing_floors" class="servicing_floors">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->floors->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->floors->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->floors->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->items->Visible) { // items ?>
	<?php if ($servicing->SortUrl($servicing->items) == "") { ?>
		<th data-name="items" class="<?php echo $servicing->items->HeaderCellClass() ?>"><div id="elh_servicing_items" class="servicing_items"><div class="ewTableHeaderCaption"><?php echo $servicing->items->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="items" class="<?php echo $servicing->items->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->items) ?>',1);"><div id="elh_servicing_items" class="servicing_items">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->items->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->items->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->items->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->priority->Visible) { // priority ?>
	<?php if ($servicing->SortUrl($servicing->priority) == "") { ?>
		<th data-name="priority" class="<?php echo $servicing->priority->HeaderCellClass() ?>"><div id="elh_servicing_priority" class="servicing_priority"><div class="ewTableHeaderCaption"><?php echo $servicing->priority->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="priority" class="<?php echo $servicing->priority->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->priority) ?>',1);"><div id="elh_servicing_priority" class="servicing_priority">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->priority->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->priority->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->priority->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->description->Visible) { // description ?>
	<?php if ($servicing->SortUrl($servicing->description) == "") { ?>
		<th data-name="description" class="<?php echo $servicing->description->HeaderCellClass() ?>"><div id="elh_servicing_description" class="servicing_description"><div class="ewTableHeaderCaption"><?php echo $servicing->description->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="description" class="<?php echo $servicing->description->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->description) ?>',1);"><div id="elh_servicing_description" class="servicing_description">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($servicing->description->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->description->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->status->Visible) { // status ?>
	<?php if ($servicing->SortUrl($servicing->status) == "") { ?>
		<th data-name="status" class="<?php echo $servicing->status->HeaderCellClass() ?>"><div id="elh_servicing_status" class="servicing_status"><div class="ewTableHeaderCaption"><?php echo $servicing->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $servicing->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->status) ?>',1);"><div id="elh_servicing_status" class="servicing_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->date_maintained->Visible) { // date_maintained ?>
	<?php if ($servicing->SortUrl($servicing->date_maintained) == "") { ?>
		<th data-name="date_maintained" class="<?php echo $servicing->date_maintained->HeaderCellClass() ?>"><div id="elh_servicing_date_maintained" class="servicing_date_maintained"><div class="ewTableHeaderCaption"><?php echo $servicing->date_maintained->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_maintained" class="<?php echo $servicing->date_maintained->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->date_maintained) ?>',1);"><div id="elh_servicing_date_maintained" class="servicing_date_maintained">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->date_maintained->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->date_maintained->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->date_maintained->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->initiator_action->Visible) { // initiator_action ?>
	<?php if ($servicing->SortUrl($servicing->initiator_action) == "") { ?>
		<th data-name="initiator_action" class="<?php echo $servicing->initiator_action->HeaderCellClass() ?>"><div id="elh_servicing_initiator_action" class="servicing_initiator_action"><div class="ewTableHeaderCaption"><?php echo $servicing->initiator_action->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="initiator_action" class="<?php echo $servicing->initiator_action->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->initiator_action) ?>',1);"><div id="elh_servicing_initiator_action" class="servicing_initiator_action">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->initiator_action->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->initiator_action->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->initiator_action->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->maintained_by->Visible) { // maintained_by ?>
	<?php if ($servicing->SortUrl($servicing->maintained_by) == "") { ?>
		<th data-name="maintained_by" class="<?php echo $servicing->maintained_by->HeaderCellClass() ?>"><div id="elh_servicing_maintained_by" class="servicing_maintained_by"><div class="ewTableHeaderCaption"><?php echo $servicing->maintained_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="maintained_by" class="<?php echo $servicing->maintained_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->maintained_by) ?>',1);"><div id="elh_servicing_maintained_by" class="servicing_maintained_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->maintained_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->maintained_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->maintained_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->reviewed_date->Visible) { // reviewed_date ?>
	<?php if ($servicing->SortUrl($servicing->reviewed_date) == "") { ?>
		<th data-name="reviewed_date" class="<?php echo $servicing->reviewed_date->HeaderCellClass() ?>"><div id="elh_servicing_reviewed_date" class="servicing_reviewed_date"><div class="ewTableHeaderCaption"><?php echo $servicing->reviewed_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reviewed_date" class="<?php echo $servicing->reviewed_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->reviewed_date) ?>',1);"><div id="elh_servicing_reviewed_date" class="servicing_reviewed_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->reviewed_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->reviewed_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->reviewed_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->reviewed_action->Visible) { // reviewed_action ?>
	<?php if ($servicing->SortUrl($servicing->reviewed_action) == "") { ?>
		<th data-name="reviewed_action" class="<?php echo $servicing->reviewed_action->HeaderCellClass() ?>"><div id="elh_servicing_reviewed_action" class="servicing_reviewed_action"><div class="ewTableHeaderCaption"><?php echo $servicing->reviewed_action->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reviewed_action" class="<?php echo $servicing->reviewed_action->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->reviewed_action) ?>',1);"><div id="elh_servicing_reviewed_action" class="servicing_reviewed_action">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->reviewed_action->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->reviewed_action->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->reviewed_action->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->reviewed_by->Visible) { // reviewed_by ?>
	<?php if ($servicing->SortUrl($servicing->reviewed_by) == "") { ?>
		<th data-name="reviewed_by" class="<?php echo $servicing->reviewed_by->HeaderCellClass() ?>"><div id="elh_servicing_reviewed_by" class="servicing_reviewed_by"><div class="ewTableHeaderCaption"><?php echo $servicing->reviewed_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reviewed_by" class="<?php echo $servicing->reviewed_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->reviewed_by) ?>',1);"><div id="elh_servicing_reviewed_by" class="servicing_reviewed_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->reviewed_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicing->reviewed_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->reviewed_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($servicing->staff_no->Visible) { // staff_no ?>
	<?php if ($servicing->SortUrl($servicing->staff_no) == "") { ?>
		<th data-name="staff_no" class="<?php echo $servicing->staff_no->HeaderCellClass() ?>"><div id="elh_servicing_staff_no" class="servicing_staff_no"><div class="ewTableHeaderCaption"><?php echo $servicing->staff_no->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_no" class="<?php echo $servicing->staff_no->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicing->SortUrl($servicing->staff_no) ?>',1);"><div id="elh_servicing_staff_no" class="servicing_staff_no">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicing->staff_no->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($servicing->staff_no->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicing->staff_no->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$servicing_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($servicing->ExportAll && $servicing->Export <> "") {
	$servicing_list->StopRec = $servicing_list->TotalRecs;
} else {

	// Set the last record to display
	if ($servicing_list->TotalRecs > $servicing_list->StartRec + $servicing_list->DisplayRecs - 1)
		$servicing_list->StopRec = $servicing_list->StartRec + $servicing_list->DisplayRecs - 1;
	else
		$servicing_list->StopRec = $servicing_list->TotalRecs;
}
$servicing_list->RecCnt = $servicing_list->StartRec - 1;
if ($servicing_list->Recordset && !$servicing_list->Recordset->EOF) {
	$servicing_list->Recordset->MoveFirst();
	$bSelectLimit = $servicing_list->UseSelectLimit;
	if (!$bSelectLimit && $servicing_list->StartRec > 1)
		$servicing_list->Recordset->Move($servicing_list->StartRec - 1);
} elseif (!$servicing->AllowAddDeleteRow && $servicing_list->StopRec == 0) {
	$servicing_list->StopRec = $servicing->GridAddRowCount;
}

// Initialize aggregate
$servicing->RowType = EW_ROWTYPE_AGGREGATEINIT;
$servicing->ResetAttrs();
$servicing_list->RenderRow();
while ($servicing_list->RecCnt < $servicing_list->StopRec) {
	$servicing_list->RecCnt++;
	if (intval($servicing_list->RecCnt) >= intval($servicing_list->StartRec)) {
		$servicing_list->RowCnt++;

		// Set up key count
		$servicing_list->KeyCount = $servicing_list->RowIndex;

		// Init row class and style
		$servicing->ResetAttrs();
		$servicing->CssClass = "";
		if ($servicing->CurrentAction == "gridadd") {
		} else {
			$servicing_list->LoadRowValues($servicing_list->Recordset); // Load row values
		}
		$servicing->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$servicing->RowAttrs = array_merge($servicing->RowAttrs, array('data-rowindex'=>$servicing_list->RowCnt, 'id'=>'r' . $servicing_list->RowCnt . '_servicing', 'data-rowtype'=>$servicing->RowType));

		// Render row
		$servicing_list->RenderRow();

		// Render list options
		$servicing_list->RenderListOptions();
?>
	<tr<?php echo $servicing->RowAttributes() ?>>
<?php

// Render list options (body, left)
$servicing_list->ListOptions->Render("body", "left", $servicing_list->RowCnt);
?>
	<?php if ($servicing->date_initiated->Visible) { // date_initiated ?>
		<td data-name="date_initiated"<?php echo $servicing->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_date_initiated" class="servicing_date_initiated">
<span<?php echo $servicing->date_initiated->ViewAttributes() ?>>
<?php echo $servicing->date_initiated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->reference_id->Visible) { // reference_id ?>
		<td data-name="reference_id"<?php echo $servicing->reference_id->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_reference_id" class="servicing_reference_id">
<span<?php echo $servicing->reference_id->ViewAttributes() ?>>
<?php echo $servicing->reference_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $servicing->staff_id->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_staff_id" class="servicing_staff_id">
<span<?php echo $servicing->staff_id->ViewAttributes() ?>>
<?php echo $servicing->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->staff_name->Visible) { // staff_name ?>
		<td data-name="staff_name"<?php echo $servicing->staff_name->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_staff_name" class="servicing_staff_name">
<span<?php echo $servicing->staff_name->ViewAttributes() ?>>
<?php echo $servicing->staff_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->department->Visible) { // department ?>
		<td data-name="department"<?php echo $servicing->department->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_department" class="servicing_department">
<span<?php echo $servicing->department->ViewAttributes() ?>>
<?php echo $servicing->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->branch->Visible) { // branch ?>
		<td data-name="branch"<?php echo $servicing->branch->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_branch" class="servicing_branch">
<span<?php echo $servicing->branch->ViewAttributes() ?>>
<?php echo $servicing->branch->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->buildings->Visible) { // buildings ?>
		<td data-name="buildings"<?php echo $servicing->buildings->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_buildings" class="servicing_buildings">
<span<?php echo $servicing->buildings->ViewAttributes() ?>>
<?php echo $servicing->buildings->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->floors->Visible) { // floors ?>
		<td data-name="floors"<?php echo $servicing->floors->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_floors" class="servicing_floors">
<span<?php echo $servicing->floors->ViewAttributes() ?>>
<?php echo $servicing->floors->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->items->Visible) { // items ?>
		<td data-name="items"<?php echo $servicing->items->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_items" class="servicing_items">
<span<?php echo $servicing->items->ViewAttributes() ?>>
<?php echo $servicing->items->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->priority->Visible) { // priority ?>
		<td data-name="priority"<?php echo $servicing->priority->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_priority" class="servicing_priority">
<span<?php echo $servicing->priority->ViewAttributes() ?>>
<?php echo $servicing->priority->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->description->Visible) { // description ?>
		<td data-name="description"<?php echo $servicing->description->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_description" class="servicing_description">
<span<?php echo $servicing->description->ViewAttributes() ?>>
<?php echo $servicing->description->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->status->Visible) { // status ?>
		<td data-name="status"<?php echo $servicing->status->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_status" class="servicing_status">
<span<?php echo $servicing->status->ViewAttributes() ?>>
<?php echo $servicing->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->date_maintained->Visible) { // date_maintained ?>
		<td data-name="date_maintained"<?php echo $servicing->date_maintained->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_date_maintained" class="servicing_date_maintained">
<span<?php echo $servicing->date_maintained->ViewAttributes() ?>>
<?php echo $servicing->date_maintained->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->initiator_action->Visible) { // initiator_action ?>
		<td data-name="initiator_action"<?php echo $servicing->initiator_action->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_initiator_action" class="servicing_initiator_action">
<span<?php echo $servicing->initiator_action->ViewAttributes() ?>>
<?php echo $servicing->initiator_action->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->maintained_by->Visible) { // maintained_by ?>
		<td data-name="maintained_by"<?php echo $servicing->maintained_by->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_maintained_by" class="servicing_maintained_by">
<span<?php echo $servicing->maintained_by->ViewAttributes() ?>>
<?php echo $servicing->maintained_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->reviewed_date->Visible) { // reviewed_date ?>
		<td data-name="reviewed_date"<?php echo $servicing->reviewed_date->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_reviewed_date" class="servicing_reviewed_date">
<span<?php echo $servicing->reviewed_date->ViewAttributes() ?>>
<?php echo $servicing->reviewed_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->reviewed_action->Visible) { // reviewed_action ?>
		<td data-name="reviewed_action"<?php echo $servicing->reviewed_action->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_reviewed_action" class="servicing_reviewed_action">
<span<?php echo $servicing->reviewed_action->ViewAttributes() ?>>
<?php echo $servicing->reviewed_action->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->reviewed_by->Visible) { // reviewed_by ?>
		<td data-name="reviewed_by"<?php echo $servicing->reviewed_by->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_reviewed_by" class="servicing_reviewed_by">
<span<?php echo $servicing->reviewed_by->ViewAttributes() ?>>
<?php echo $servicing->reviewed_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($servicing->staff_no->Visible) { // staff_no ?>
		<td data-name="staff_no"<?php echo $servicing->staff_no->CellAttributes() ?>>
<span id="el<?php echo $servicing_list->RowCnt ?>_servicing_staff_no" class="servicing_staff_no">
<span<?php echo $servicing->staff_no->ViewAttributes() ?>>
<?php echo $servicing->staff_no->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$servicing_list->ListOptions->Render("body", "right", $servicing_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($servicing->CurrentAction <> "gridadd")
		$servicing_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($servicing->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($servicing_list->Recordset)
	$servicing_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($servicing_list->TotalRecs == 0 && $servicing->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($servicing_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($servicing->Export == "") { ?>
<script type="text/javascript">
fservicinglistsrch.FilterList = <?php echo $servicing_list->GetFilterList() ?>;
fservicinglistsrch.Init();
fservicinglist.Init();
</script>
<?php } ?>
<?php
$servicing_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($servicing->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$servicing_list->Page_Terminate();
?>
