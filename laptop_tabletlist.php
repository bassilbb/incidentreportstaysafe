<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "laptop_tabletinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$laptop_tablet_list = NULL; // Initialize page object first

class claptop_tablet_list extends claptop_tablet {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'laptop_tablet';

	// Page object name
	var $PageObjName = 'laptop_tablet_list';

	// Grid form hidden field names
	var $FormName = 'flaptop_tabletlist';
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

		// Table object (laptop_tablet)
		if (!isset($GLOBALS["laptop_tablet"]) || get_class($GLOBALS["laptop_tablet"]) == "claptop_tablet") {
			$GLOBALS["laptop_tablet"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["laptop_tablet"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "laptop_tabletadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "laptop_tabletdelete.php";
		$this->MultiUpdateUrl = "laptop_tabletupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'laptop_tablet', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption flaptop_tabletlistsrch";

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
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->asset_tag->SetVisibility();
		$this->start_sate->SetVisibility();
		$this->end_date->SetVisibility();
		$this->cost_for_repair->SetVisibility();
		$this->service_provider->SetVisibility();
		$this->address->SetVisibility();
		$this->type_of_repair->SetVisibility();
		$this->note->SetVisibility();
		$this->status->SetVisibility();
		$this->asset_category->SetVisibility();
		$this->asset_sub_category->SetVisibility();
		$this->serial_number->SetVisibility();
		$this->programe_area->SetVisibility();
		$this->division->SetVisibility();
		$this->branch->SetVisibility();
		$this->department->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->created_by->SetVisibility();
		$this->created_date->SetVisibility();
		$this->device_number->SetVisibility();
		$this->tablet_imie_number->SetVisibility();
		$this->model->SetVisibility();
		$this->flag->SetVisibility();
		$this->area->SetVisibility();
		$this->updated_date->SetVisibility();
		$this->updated_by->SetVisibility();
		$this->received_date->SetVisibility();
		$this->received_by->SetVisibility();

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
		global $EW_EXPORT, $laptop_tablet;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($laptop_tablet);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "flaptop_tabletlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->asset_tag->AdvancedSearch->ToJson(), ","); // Field asset_tag
		$sFilterList = ew_Concat($sFilterList, $this->start_sate->AdvancedSearch->ToJson(), ","); // Field start_sate
		$sFilterList = ew_Concat($sFilterList, $this->end_date->AdvancedSearch->ToJson(), ","); // Field end_date
		$sFilterList = ew_Concat($sFilterList, $this->cost_for_repair->AdvancedSearch->ToJson(), ","); // Field cost_for_repair
		$sFilterList = ew_Concat($sFilterList, $this->service_provider->AdvancedSearch->ToJson(), ","); // Field service_provider
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJson(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->type_of_repair->AdvancedSearch->ToJson(), ","); // Field type_of_repair
		$sFilterList = ew_Concat($sFilterList, $this->note->AdvancedSearch->ToJson(), ","); // Field note
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->asset_category->AdvancedSearch->ToJson(), ","); // Field asset_category
		$sFilterList = ew_Concat($sFilterList, $this->asset_sub_category->AdvancedSearch->ToJson(), ","); // Field asset_sub_category
		$sFilterList = ew_Concat($sFilterList, $this->serial_number->AdvancedSearch->ToJson(), ","); // Field serial_number
		$sFilterList = ew_Concat($sFilterList, $this->programe_area->AdvancedSearch->ToJson(), ","); // Field programe_area
		$sFilterList = ew_Concat($sFilterList, $this->division->AdvancedSearch->ToJson(), ","); // Field division
		$sFilterList = ew_Concat($sFilterList, $this->branch->AdvancedSearch->ToJson(), ","); // Field branch
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->created_by->AdvancedSearch->ToJson(), ","); // Field created_by
		$sFilterList = ew_Concat($sFilterList, $this->created_date->AdvancedSearch->ToJson(), ","); // Field created_date
		$sFilterList = ew_Concat($sFilterList, $this->device_number->AdvancedSearch->ToJson(), ","); // Field device_number
		$sFilterList = ew_Concat($sFilterList, $this->tablet_imie_number->AdvancedSearch->ToJson(), ","); // Field tablet_imie_number
		$sFilterList = ew_Concat($sFilterList, $this->model->AdvancedSearch->ToJson(), ","); // Field model
		$sFilterList = ew_Concat($sFilterList, $this->flag->AdvancedSearch->ToJson(), ","); // Field flag
		$sFilterList = ew_Concat($sFilterList, $this->area->AdvancedSearch->ToJson(), ","); // Field area
		$sFilterList = ew_Concat($sFilterList, $this->updated_date->AdvancedSearch->ToJson(), ","); // Field updated_date
		$sFilterList = ew_Concat($sFilterList, $this->updated_by->AdvancedSearch->ToJson(), ","); // Field updated_by
		$sFilterList = ew_Concat($sFilterList, $this->received_date->AdvancedSearch->ToJson(), ","); // Field received_date
		$sFilterList = ew_Concat($sFilterList, $this->received_by->AdvancedSearch->ToJson(), ","); // Field received_by
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "flaptop_tabletlistsrch", $filters);

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

		// Field asset_tag
		$this->asset_tag->AdvancedSearch->SearchValue = @$filter["x_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchOperator = @$filter["z_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchCondition = @$filter["v_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchValue2 = @$filter["y_asset_tag"];
		$this->asset_tag->AdvancedSearch->SearchOperator2 = @$filter["w_asset_tag"];
		$this->asset_tag->AdvancedSearch->Save();

		// Field start_sate
		$this->start_sate->AdvancedSearch->SearchValue = @$filter["x_start_sate"];
		$this->start_sate->AdvancedSearch->SearchOperator = @$filter["z_start_sate"];
		$this->start_sate->AdvancedSearch->SearchCondition = @$filter["v_start_sate"];
		$this->start_sate->AdvancedSearch->SearchValue2 = @$filter["y_start_sate"];
		$this->start_sate->AdvancedSearch->SearchOperator2 = @$filter["w_start_sate"];
		$this->start_sate->AdvancedSearch->Save();

		// Field end_date
		$this->end_date->AdvancedSearch->SearchValue = @$filter["x_end_date"];
		$this->end_date->AdvancedSearch->SearchOperator = @$filter["z_end_date"];
		$this->end_date->AdvancedSearch->SearchCondition = @$filter["v_end_date"];
		$this->end_date->AdvancedSearch->SearchValue2 = @$filter["y_end_date"];
		$this->end_date->AdvancedSearch->SearchOperator2 = @$filter["w_end_date"];
		$this->end_date->AdvancedSearch->Save();

		// Field cost_for_repair
		$this->cost_for_repair->AdvancedSearch->SearchValue = @$filter["x_cost_for_repair"];
		$this->cost_for_repair->AdvancedSearch->SearchOperator = @$filter["z_cost_for_repair"];
		$this->cost_for_repair->AdvancedSearch->SearchCondition = @$filter["v_cost_for_repair"];
		$this->cost_for_repair->AdvancedSearch->SearchValue2 = @$filter["y_cost_for_repair"];
		$this->cost_for_repair->AdvancedSearch->SearchOperator2 = @$filter["w_cost_for_repair"];
		$this->cost_for_repair->AdvancedSearch->Save();

		// Field service_provider
		$this->service_provider->AdvancedSearch->SearchValue = @$filter["x_service_provider"];
		$this->service_provider->AdvancedSearch->SearchOperator = @$filter["z_service_provider"];
		$this->service_provider->AdvancedSearch->SearchCondition = @$filter["v_service_provider"];
		$this->service_provider->AdvancedSearch->SearchValue2 = @$filter["y_service_provider"];
		$this->service_provider->AdvancedSearch->SearchOperator2 = @$filter["w_service_provider"];
		$this->service_provider->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field type_of_repair
		$this->type_of_repair->AdvancedSearch->SearchValue = @$filter["x_type_of_repair"];
		$this->type_of_repair->AdvancedSearch->SearchOperator = @$filter["z_type_of_repair"];
		$this->type_of_repair->AdvancedSearch->SearchCondition = @$filter["v_type_of_repair"];
		$this->type_of_repair->AdvancedSearch->SearchValue2 = @$filter["y_type_of_repair"];
		$this->type_of_repair->AdvancedSearch->SearchOperator2 = @$filter["w_type_of_repair"];
		$this->type_of_repair->AdvancedSearch->Save();

		// Field note
		$this->note->AdvancedSearch->SearchValue = @$filter["x_note"];
		$this->note->AdvancedSearch->SearchOperator = @$filter["z_note"];
		$this->note->AdvancedSearch->SearchCondition = @$filter["v_note"];
		$this->note->AdvancedSearch->SearchValue2 = @$filter["y_note"];
		$this->note->AdvancedSearch->SearchOperator2 = @$filter["w_note"];
		$this->note->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field asset_category
		$this->asset_category->AdvancedSearch->SearchValue = @$filter["x_asset_category"];
		$this->asset_category->AdvancedSearch->SearchOperator = @$filter["z_asset_category"];
		$this->asset_category->AdvancedSearch->SearchCondition = @$filter["v_asset_category"];
		$this->asset_category->AdvancedSearch->SearchValue2 = @$filter["y_asset_category"];
		$this->asset_category->AdvancedSearch->SearchOperator2 = @$filter["w_asset_category"];
		$this->asset_category->AdvancedSearch->Save();

		// Field asset_sub_category
		$this->asset_sub_category->AdvancedSearch->SearchValue = @$filter["x_asset_sub_category"];
		$this->asset_sub_category->AdvancedSearch->SearchOperator = @$filter["z_asset_sub_category"];
		$this->asset_sub_category->AdvancedSearch->SearchCondition = @$filter["v_asset_sub_category"];
		$this->asset_sub_category->AdvancedSearch->SearchValue2 = @$filter["y_asset_sub_category"];
		$this->asset_sub_category->AdvancedSearch->SearchOperator2 = @$filter["w_asset_sub_category"];
		$this->asset_sub_category->AdvancedSearch->Save();

		// Field serial_number
		$this->serial_number->AdvancedSearch->SearchValue = @$filter["x_serial_number"];
		$this->serial_number->AdvancedSearch->SearchOperator = @$filter["z_serial_number"];
		$this->serial_number->AdvancedSearch->SearchCondition = @$filter["v_serial_number"];
		$this->serial_number->AdvancedSearch->SearchValue2 = @$filter["y_serial_number"];
		$this->serial_number->AdvancedSearch->SearchOperator2 = @$filter["w_serial_number"];
		$this->serial_number->AdvancedSearch->Save();

		// Field programe_area
		$this->programe_area->AdvancedSearch->SearchValue = @$filter["x_programe_area"];
		$this->programe_area->AdvancedSearch->SearchOperator = @$filter["z_programe_area"];
		$this->programe_area->AdvancedSearch->SearchCondition = @$filter["v_programe_area"];
		$this->programe_area->AdvancedSearch->SearchValue2 = @$filter["y_programe_area"];
		$this->programe_area->AdvancedSearch->SearchOperator2 = @$filter["w_programe_area"];
		$this->programe_area->AdvancedSearch->Save();

		// Field division
		$this->division->AdvancedSearch->SearchValue = @$filter["x_division"];
		$this->division->AdvancedSearch->SearchOperator = @$filter["z_division"];
		$this->division->AdvancedSearch->SearchCondition = @$filter["v_division"];
		$this->division->AdvancedSearch->SearchValue2 = @$filter["y_division"];
		$this->division->AdvancedSearch->SearchOperator2 = @$filter["w_division"];
		$this->division->AdvancedSearch->Save();

		// Field branch
		$this->branch->AdvancedSearch->SearchValue = @$filter["x_branch"];
		$this->branch->AdvancedSearch->SearchOperator = @$filter["z_branch"];
		$this->branch->AdvancedSearch->SearchCondition = @$filter["v_branch"];
		$this->branch->AdvancedSearch->SearchValue2 = @$filter["y_branch"];
		$this->branch->AdvancedSearch->SearchOperator2 = @$filter["w_branch"];
		$this->branch->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

		// Field created_by
		$this->created_by->AdvancedSearch->SearchValue = @$filter["x_created_by"];
		$this->created_by->AdvancedSearch->SearchOperator = @$filter["z_created_by"];
		$this->created_by->AdvancedSearch->SearchCondition = @$filter["v_created_by"];
		$this->created_by->AdvancedSearch->SearchValue2 = @$filter["y_created_by"];
		$this->created_by->AdvancedSearch->SearchOperator2 = @$filter["w_created_by"];
		$this->created_by->AdvancedSearch->Save();

		// Field created_date
		$this->created_date->AdvancedSearch->SearchValue = @$filter["x_created_date"];
		$this->created_date->AdvancedSearch->SearchOperator = @$filter["z_created_date"];
		$this->created_date->AdvancedSearch->SearchCondition = @$filter["v_created_date"];
		$this->created_date->AdvancedSearch->SearchValue2 = @$filter["y_created_date"];
		$this->created_date->AdvancedSearch->SearchOperator2 = @$filter["w_created_date"];
		$this->created_date->AdvancedSearch->Save();

		// Field device_number
		$this->device_number->AdvancedSearch->SearchValue = @$filter["x_device_number"];
		$this->device_number->AdvancedSearch->SearchOperator = @$filter["z_device_number"];
		$this->device_number->AdvancedSearch->SearchCondition = @$filter["v_device_number"];
		$this->device_number->AdvancedSearch->SearchValue2 = @$filter["y_device_number"];
		$this->device_number->AdvancedSearch->SearchOperator2 = @$filter["w_device_number"];
		$this->device_number->AdvancedSearch->Save();

		// Field tablet_imie_number
		$this->tablet_imie_number->AdvancedSearch->SearchValue = @$filter["x_tablet_imie_number"];
		$this->tablet_imie_number->AdvancedSearch->SearchOperator = @$filter["z_tablet_imie_number"];
		$this->tablet_imie_number->AdvancedSearch->SearchCondition = @$filter["v_tablet_imie_number"];
		$this->tablet_imie_number->AdvancedSearch->SearchValue2 = @$filter["y_tablet_imie_number"];
		$this->tablet_imie_number->AdvancedSearch->SearchOperator2 = @$filter["w_tablet_imie_number"];
		$this->tablet_imie_number->AdvancedSearch->Save();

		// Field model
		$this->model->AdvancedSearch->SearchValue = @$filter["x_model"];
		$this->model->AdvancedSearch->SearchOperator = @$filter["z_model"];
		$this->model->AdvancedSearch->SearchCondition = @$filter["v_model"];
		$this->model->AdvancedSearch->SearchValue2 = @$filter["y_model"];
		$this->model->AdvancedSearch->SearchOperator2 = @$filter["w_model"];
		$this->model->AdvancedSearch->Save();

		// Field flag
		$this->flag->AdvancedSearch->SearchValue = @$filter["x_flag"];
		$this->flag->AdvancedSearch->SearchOperator = @$filter["z_flag"];
		$this->flag->AdvancedSearch->SearchCondition = @$filter["v_flag"];
		$this->flag->AdvancedSearch->SearchValue2 = @$filter["y_flag"];
		$this->flag->AdvancedSearch->SearchOperator2 = @$filter["w_flag"];
		$this->flag->AdvancedSearch->Save();

		// Field area
		$this->area->AdvancedSearch->SearchValue = @$filter["x_area"];
		$this->area->AdvancedSearch->SearchOperator = @$filter["z_area"];
		$this->area->AdvancedSearch->SearchCondition = @$filter["v_area"];
		$this->area->AdvancedSearch->SearchValue2 = @$filter["y_area"];
		$this->area->AdvancedSearch->SearchOperator2 = @$filter["w_area"];
		$this->area->AdvancedSearch->Save();

		// Field updated_date
		$this->updated_date->AdvancedSearch->SearchValue = @$filter["x_updated_date"];
		$this->updated_date->AdvancedSearch->SearchOperator = @$filter["z_updated_date"];
		$this->updated_date->AdvancedSearch->SearchCondition = @$filter["v_updated_date"];
		$this->updated_date->AdvancedSearch->SearchValue2 = @$filter["y_updated_date"];
		$this->updated_date->AdvancedSearch->SearchOperator2 = @$filter["w_updated_date"];
		$this->updated_date->AdvancedSearch->Save();

		// Field updated_by
		$this->updated_by->AdvancedSearch->SearchValue = @$filter["x_updated_by"];
		$this->updated_by->AdvancedSearch->SearchOperator = @$filter["z_updated_by"];
		$this->updated_by->AdvancedSearch->SearchCondition = @$filter["v_updated_by"];
		$this->updated_by->AdvancedSearch->SearchValue2 = @$filter["y_updated_by"];
		$this->updated_by->AdvancedSearch->SearchOperator2 = @$filter["w_updated_by"];
		$this->updated_by->AdvancedSearch->Save();

		// Field received_date
		$this->received_date->AdvancedSearch->SearchValue = @$filter["x_received_date"];
		$this->received_date->AdvancedSearch->SearchOperator = @$filter["z_received_date"];
		$this->received_date->AdvancedSearch->SearchCondition = @$filter["v_received_date"];
		$this->received_date->AdvancedSearch->SearchValue2 = @$filter["y_received_date"];
		$this->received_date->AdvancedSearch->SearchOperator2 = @$filter["w_received_date"];
		$this->received_date->AdvancedSearch->Save();

		// Field received_by
		$this->received_by->AdvancedSearch->SearchValue = @$filter["x_received_by"];
		$this->received_by->AdvancedSearch->SearchOperator = @$filter["z_received_by"];
		$this->received_by->AdvancedSearch->SearchCondition = @$filter["v_received_by"];
		$this->received_by->AdvancedSearch->SearchValue2 = @$filter["y_received_by"];
		$this->received_by->AdvancedSearch->SearchOperator2 = @$filter["w_received_by"];
		$this->received_by->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->asset_tag, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->service_provider, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->type_of_repair, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->note, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->asset_category, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->asset_sub_category, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->serial_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->programe_area, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->division, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->branch, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->department, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staff_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->created_by, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->device_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tablet_imie_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->model, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->area, $arKeywords, $type);
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
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->asset_tag); // asset_tag
			$this->UpdateSort($this->start_sate); // start_sate
			$this->UpdateSort($this->end_date); // end_date
			$this->UpdateSort($this->cost_for_repair); // cost_for_repair
			$this->UpdateSort($this->service_provider); // service_provider
			$this->UpdateSort($this->address); // address
			$this->UpdateSort($this->type_of_repair); // type_of_repair
			$this->UpdateSort($this->note); // note
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->asset_category); // asset_category
			$this->UpdateSort($this->asset_sub_category); // asset_sub_category
			$this->UpdateSort($this->serial_number); // serial_number
			$this->UpdateSort($this->programe_area); // programe_area
			$this->UpdateSort($this->division); // division
			$this->UpdateSort($this->branch); // branch
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->created_by); // created_by
			$this->UpdateSort($this->created_date); // created_date
			$this->UpdateSort($this->device_number); // device_number
			$this->UpdateSort($this->tablet_imie_number); // tablet_imie_number
			$this->UpdateSort($this->model); // model
			$this->UpdateSort($this->flag); // flag
			$this->UpdateSort($this->area); // area
			$this->UpdateSort($this->updated_date); // updated_date
			$this->UpdateSort($this->updated_by); // updated_by
			$this->UpdateSort($this->received_date); // received_date
			$this->UpdateSort($this->received_by); // received_by
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
				$this->id->setSort("");
				$this->asset_tag->setSort("");
				$this->start_sate->setSort("");
				$this->end_date->setSort("");
				$this->cost_for_repair->setSort("");
				$this->service_provider->setSort("");
				$this->address->setSort("");
				$this->type_of_repair->setSort("");
				$this->note->setSort("");
				$this->status->setSort("");
				$this->asset_category->setSort("");
				$this->asset_sub_category->setSort("");
				$this->serial_number->setSort("");
				$this->programe_area->setSort("");
				$this->division->setSort("");
				$this->branch->setSort("");
				$this->department->setSort("");
				$this->staff_id->setSort("");
				$this->created_by->setSort("");
				$this->created_date->setSort("");
				$this->device_number->setSort("");
				$this->tablet_imie_number->setSort("");
				$this->model->setSort("");
				$this->flag->setSort("");
				$this->area->setSort("");
				$this->updated_date->setSort("");
				$this->updated_by->setSort("");
				$this->received_date->setSort("");
				$this->received_by->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"flaptop_tabletlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"flaptop_tabletlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.flaptop_tabletlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"flaptop_tabletlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		$this->asset_tag->setDbValue($row['asset_tag']);
		$this->start_sate->setDbValue($row['start_sate']);
		$this->end_date->setDbValue($row['end_date']);
		$this->cost_for_repair->setDbValue($row['cost_for_repair']);
		$this->service_provider->setDbValue($row['service_provider']);
		$this->address->setDbValue($row['address']);
		$this->type_of_repair->setDbValue($row['type_of_repair']);
		$this->note->setDbValue($row['note']);
		$this->status->setDbValue($row['status']);
		$this->asset_category->setDbValue($row['asset_category']);
		$this->asset_sub_category->setDbValue($row['asset_sub_category']);
		$this->serial_number->setDbValue($row['serial_number']);
		$this->programe_area->setDbValue($row['programe_area']);
		$this->division->setDbValue($row['division']);
		$this->branch->setDbValue($row['branch']);
		$this->department->setDbValue($row['department']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->created_by->setDbValue($row['created_by']);
		$this->created_date->setDbValue($row['created_date']);
		$this->device_number->setDbValue($row['device_number']);
		$this->tablet_imie_number->setDbValue($row['tablet_imie_number']);
		$this->model->setDbValue($row['model']);
		$this->flag->setDbValue($row['flag']);
		$this->area->setDbValue($row['area']);
		$this->updated_date->setDbValue($row['updated_date']);
		$this->updated_by->setDbValue($row['updated_by']);
		$this->received_date->setDbValue($row['received_date']);
		$this->received_by->setDbValue($row['received_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['asset_tag'] = NULL;
		$row['start_sate'] = NULL;
		$row['end_date'] = NULL;
		$row['cost_for_repair'] = NULL;
		$row['service_provider'] = NULL;
		$row['address'] = NULL;
		$row['type_of_repair'] = NULL;
		$row['note'] = NULL;
		$row['status'] = NULL;
		$row['asset_category'] = NULL;
		$row['asset_sub_category'] = NULL;
		$row['serial_number'] = NULL;
		$row['programe_area'] = NULL;
		$row['division'] = NULL;
		$row['branch'] = NULL;
		$row['department'] = NULL;
		$row['staff_id'] = NULL;
		$row['created_by'] = NULL;
		$row['created_date'] = NULL;
		$row['device_number'] = NULL;
		$row['tablet_imie_number'] = NULL;
		$row['model'] = NULL;
		$row['flag'] = NULL;
		$row['area'] = NULL;
		$row['updated_date'] = NULL;
		$row['updated_by'] = NULL;
		$row['received_date'] = NULL;
		$row['received_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->asset_tag->DbValue = $row['asset_tag'];
		$this->start_sate->DbValue = $row['start_sate'];
		$this->end_date->DbValue = $row['end_date'];
		$this->cost_for_repair->DbValue = $row['cost_for_repair'];
		$this->service_provider->DbValue = $row['service_provider'];
		$this->address->DbValue = $row['address'];
		$this->type_of_repair->DbValue = $row['type_of_repair'];
		$this->note->DbValue = $row['note'];
		$this->status->DbValue = $row['status'];
		$this->asset_category->DbValue = $row['asset_category'];
		$this->asset_sub_category->DbValue = $row['asset_sub_category'];
		$this->serial_number->DbValue = $row['serial_number'];
		$this->programe_area->DbValue = $row['programe_area'];
		$this->division->DbValue = $row['division'];
		$this->branch->DbValue = $row['branch'];
		$this->department->DbValue = $row['department'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->created_by->DbValue = $row['created_by'];
		$this->created_date->DbValue = $row['created_date'];
		$this->device_number->DbValue = $row['device_number'];
		$this->tablet_imie_number->DbValue = $row['tablet_imie_number'];
		$this->model->DbValue = $row['model'];
		$this->flag->DbValue = $row['flag'];
		$this->area->DbValue = $row['area'];
		$this->updated_date->DbValue = $row['updated_date'];
		$this->updated_by->DbValue = $row['updated_by'];
		$this->received_date->DbValue = $row['received_date'];
		$this->received_by->DbValue = $row['received_by'];
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
		// asset_tag
		// start_sate
		// end_date
		// cost_for_repair
		// service_provider
		// address
		// type_of_repair
		// note
		// status
		// asset_category
		// asset_sub_category
		// serial_number
		// programe_area
		// division
		// branch
		// department
		// staff_id
		// created_by
		// created_date
		// device_number
		// tablet_imie_number
		// model
		// flag
		// area
		// updated_date
		// updated_by
		// received_date
		// received_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// asset_tag
		$this->asset_tag->ViewValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->ViewCustomAttributes = "";

		// start_sate
		$this->start_sate->ViewValue = $this->start_sate->CurrentValue;
		$this->start_sate->ViewValue = ew_FormatDateTime($this->start_sate->ViewValue, 0);
		$this->start_sate->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 0);
		$this->end_date->ViewCustomAttributes = "";

		// cost_for_repair
		$this->cost_for_repair->ViewValue = $this->cost_for_repair->CurrentValue;
		$this->cost_for_repair->ViewCustomAttributes = "";

		// service_provider
		$this->service_provider->ViewValue = $this->service_provider->CurrentValue;
		$this->service_provider->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// type_of_repair
		$this->type_of_repair->ViewValue = $this->type_of_repair->CurrentValue;
		$this->type_of_repair->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// asset_category
		$this->asset_category->ViewValue = $this->asset_category->CurrentValue;
		$this->asset_category->ViewCustomAttributes = "";

		// asset_sub_category
		$this->asset_sub_category->ViewValue = $this->asset_sub_category->CurrentValue;
		$this->asset_sub_category->ViewCustomAttributes = "";

		// serial_number
		$this->serial_number->ViewValue = $this->serial_number->CurrentValue;
		$this->serial_number->ViewCustomAttributes = "";

		// programe_area
		$this->programe_area->ViewValue = $this->programe_area->CurrentValue;
		$this->programe_area->ViewCustomAttributes = "";

		// division
		$this->division->ViewValue = $this->division->CurrentValue;
		$this->division->ViewCustomAttributes = "";

		// branch
		$this->branch->ViewValue = $this->branch->CurrentValue;
		$this->branch->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
		$this->department->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		$this->staff_id->ViewCustomAttributes = "";

		// created_by
		$this->created_by->ViewValue = $this->created_by->CurrentValue;
		$this->created_by->ViewCustomAttributes = "";

		// created_date
		$this->created_date->ViewValue = $this->created_date->CurrentValue;
		$this->created_date->ViewValue = ew_FormatDateTime($this->created_date->ViewValue, 0);
		$this->created_date->ViewCustomAttributes = "";

		// device_number
		$this->device_number->ViewValue = $this->device_number->CurrentValue;
		$this->device_number->ViewCustomAttributes = "";

		// tablet_imie_number
		$this->tablet_imie_number->ViewValue = $this->tablet_imie_number->CurrentValue;
		$this->tablet_imie_number->ViewCustomAttributes = "";

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// flag
		$this->flag->ViewValue = $this->flag->CurrentValue;
		$this->flag->ViewCustomAttributes = "";

		// area
		$this->area->ViewValue = $this->area->CurrentValue;
		$this->area->ViewCustomAttributes = "";

		// updated_date
		$this->updated_date->ViewValue = $this->updated_date->CurrentValue;
		$this->updated_date->ViewValue = ew_FormatDateTime($this->updated_date->ViewValue, 0);
		$this->updated_date->ViewCustomAttributes = "";

		// updated_by
		$this->updated_by->ViewValue = $this->updated_by->CurrentValue;
		$this->updated_by->ViewCustomAttributes = "";

		// received_date
		$this->received_date->ViewValue = $this->received_date->CurrentValue;
		$this->received_date->ViewValue = ew_FormatDateTime($this->received_date->ViewValue, 0);
		$this->received_date->ViewCustomAttributes = "";

		// received_by
		$this->received_by->ViewValue = $this->received_by->CurrentValue;
		$this->received_by->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";
			$this->asset_tag->TooltipValue = "";

			// start_sate
			$this->start_sate->LinkCustomAttributes = "";
			$this->start_sate->HrefValue = "";
			$this->start_sate->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// cost_for_repair
			$this->cost_for_repair->LinkCustomAttributes = "";
			$this->cost_for_repair->HrefValue = "";
			$this->cost_for_repair->TooltipValue = "";

			// service_provider
			$this->service_provider->LinkCustomAttributes = "";
			$this->service_provider->HrefValue = "";
			$this->service_provider->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// type_of_repair
			$this->type_of_repair->LinkCustomAttributes = "";
			$this->type_of_repair->HrefValue = "";
			$this->type_of_repair->TooltipValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";
			$this->note->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// asset_category
			$this->asset_category->LinkCustomAttributes = "";
			$this->asset_category->HrefValue = "";
			$this->asset_category->TooltipValue = "";

			// asset_sub_category
			$this->asset_sub_category->LinkCustomAttributes = "";
			$this->asset_sub_category->HrefValue = "";
			$this->asset_sub_category->TooltipValue = "";

			// serial_number
			$this->serial_number->LinkCustomAttributes = "";
			$this->serial_number->HrefValue = "";
			$this->serial_number->TooltipValue = "";

			// programe_area
			$this->programe_area->LinkCustomAttributes = "";
			$this->programe_area->HrefValue = "";
			$this->programe_area->TooltipValue = "";

			// division
			$this->division->LinkCustomAttributes = "";
			$this->division->HrefValue = "";
			$this->division->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// created_by
			$this->created_by->LinkCustomAttributes = "";
			$this->created_by->HrefValue = "";
			$this->created_by->TooltipValue = "";

			// created_date
			$this->created_date->LinkCustomAttributes = "";
			$this->created_date->HrefValue = "";
			$this->created_date->TooltipValue = "";

			// device_number
			$this->device_number->LinkCustomAttributes = "";
			$this->device_number->HrefValue = "";
			$this->device_number->TooltipValue = "";

			// tablet_imie_number
			$this->tablet_imie_number->LinkCustomAttributes = "";
			$this->tablet_imie_number->HrefValue = "";
			$this->tablet_imie_number->TooltipValue = "";

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";
			$this->model->TooltipValue = "";

			// flag
			$this->flag->LinkCustomAttributes = "";
			$this->flag->HrefValue = "";
			$this->flag->TooltipValue = "";

			// area
			$this->area->LinkCustomAttributes = "";
			$this->area->HrefValue = "";
			$this->area->TooltipValue = "";

			// updated_date
			$this->updated_date->LinkCustomAttributes = "";
			$this->updated_date->HrefValue = "";
			$this->updated_date->TooltipValue = "";

			// updated_by
			$this->updated_by->LinkCustomAttributes = "";
			$this->updated_by->HrefValue = "";
			$this->updated_by->TooltipValue = "";

			// received_date
			$this->received_date->LinkCustomAttributes = "";
			$this->received_date->HrefValue = "";
			$this->received_date->TooltipValue = "";

			// received_by
			$this->received_by->LinkCustomAttributes = "";
			$this->received_by->HrefValue = "";
			$this->received_by->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_laptop_tablet\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_laptop_tablet',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.flaptop_tabletlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($laptop_tablet_list)) $laptop_tablet_list = new claptop_tablet_list();

// Page init
$laptop_tablet_list->Page_Init();

// Page main
$laptop_tablet_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laptop_tablet_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($laptop_tablet->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = flaptop_tabletlist = new ew_Form("flaptop_tabletlist", "list");
flaptop_tabletlist.FormKeyCountName = '<?php echo $laptop_tablet_list->FormKeyCountName ?>';

// Form_CustomValidate event
flaptop_tabletlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flaptop_tabletlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = flaptop_tabletlistsrch = new ew_Form("flaptop_tabletlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($laptop_tablet->Export == "") { ?>
<div class="ewToolbar">
<?php if ($laptop_tablet_list->TotalRecs > 0 && $laptop_tablet_list->ExportOptions->Visible()) { ?>
<?php $laptop_tablet_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($laptop_tablet_list->SearchOptions->Visible()) { ?>
<?php $laptop_tablet_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($laptop_tablet_list->FilterOptions->Visible()) { ?>
<?php $laptop_tablet_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $laptop_tablet_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($laptop_tablet_list->TotalRecs <= 0)
			$laptop_tablet_list->TotalRecs = $laptop_tablet->ListRecordCount();
	} else {
		if (!$laptop_tablet_list->Recordset && ($laptop_tablet_list->Recordset = $laptop_tablet_list->LoadRecordset()))
			$laptop_tablet_list->TotalRecs = $laptop_tablet_list->Recordset->RecordCount();
	}
	$laptop_tablet_list->StartRec = 1;
	if ($laptop_tablet_list->DisplayRecs <= 0 || ($laptop_tablet->Export <> "" && $laptop_tablet->ExportAll)) // Display all records
		$laptop_tablet_list->DisplayRecs = $laptop_tablet_list->TotalRecs;
	if (!($laptop_tablet->Export <> "" && $laptop_tablet->ExportAll))
		$laptop_tablet_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$laptop_tablet_list->Recordset = $laptop_tablet_list->LoadRecordset($laptop_tablet_list->StartRec-1, $laptop_tablet_list->DisplayRecs);

	// Set no record found message
	if ($laptop_tablet->CurrentAction == "" && $laptop_tablet_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$laptop_tablet_list->setWarningMessage(ew_DeniedMsg());
		if ($laptop_tablet_list->SearchWhere == "0=101")
			$laptop_tablet_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$laptop_tablet_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$laptop_tablet_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($laptop_tablet->Export == "" && $laptop_tablet->CurrentAction == "") { ?>
<form name="flaptop_tabletlistsrch" id="flaptop_tabletlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($laptop_tablet_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="flaptop_tabletlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="laptop_tablet">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($laptop_tablet_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($laptop_tablet_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $laptop_tablet_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($laptop_tablet_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($laptop_tablet_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($laptop_tablet_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($laptop_tablet_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $laptop_tablet_list->ShowPageHeader(); ?>
<?php
$laptop_tablet_list->ShowMessage();
?>
<?php if ($laptop_tablet_list->TotalRecs > 0 || $laptop_tablet->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($laptop_tablet_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> laptop_tablet">
<?php if ($laptop_tablet->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($laptop_tablet->CurrentAction <> "gridadd" && $laptop_tablet->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($laptop_tablet_list->Pager)) $laptop_tablet_list->Pager = new cPrevNextPager($laptop_tablet_list->StartRec, $laptop_tablet_list->DisplayRecs, $laptop_tablet_list->TotalRecs, $laptop_tablet_list->AutoHidePager) ?>
<?php if ($laptop_tablet_list->Pager->RecordCount > 0 && $laptop_tablet_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($laptop_tablet_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $laptop_tablet_list->PageUrl() ?>start=<?php echo $laptop_tablet_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($laptop_tablet_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $laptop_tablet_list->PageUrl() ?>start=<?php echo $laptop_tablet_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $laptop_tablet_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($laptop_tablet_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $laptop_tablet_list->PageUrl() ?>start=<?php echo $laptop_tablet_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($laptop_tablet_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $laptop_tablet_list->PageUrl() ?>start=<?php echo $laptop_tablet_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $laptop_tablet_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($laptop_tablet_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $laptop_tablet_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $laptop_tablet_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $laptop_tablet_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($laptop_tablet_list->TotalRecs > 0 && (!$laptop_tablet_list->AutoHidePageSizeSelector || $laptop_tablet_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="laptop_tablet">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($laptop_tablet_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($laptop_tablet_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($laptop_tablet_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($laptop_tablet_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($laptop_tablet_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($laptop_tablet->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($laptop_tablet_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="flaptop_tabletlist" id="flaptop_tabletlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($laptop_tablet_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $laptop_tablet_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="laptop_tablet">
<div id="gmp_laptop_tablet" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($laptop_tablet_list->TotalRecs > 0 || $laptop_tablet->CurrentAction == "gridedit") { ?>
<table id="tbl_laptop_tabletlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$laptop_tablet_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$laptop_tablet_list->RenderListOptions();

// Render list options (header, left)
$laptop_tablet_list->ListOptions->Render("header", "left");
?>
<?php if ($laptop_tablet->id->Visible) { // id ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->id) == "") { ?>
		<th data-name="id" class="<?php echo $laptop_tablet->id->HeaderCellClass() ?>"><div id="elh_laptop_tablet_id" class="laptop_tablet_id"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $laptop_tablet->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->id) ?>',1);"><div id="elh_laptop_tablet_id" class="laptop_tablet_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->asset_tag->Visible) { // asset_tag ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->asset_tag) == "") { ?>
		<th data-name="asset_tag" class="<?php echo $laptop_tablet->asset_tag->HeaderCellClass() ?>"><div id="elh_laptop_tablet_asset_tag" class="laptop_tablet_asset_tag"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->asset_tag->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="asset_tag" class="<?php echo $laptop_tablet->asset_tag->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->asset_tag) ?>',1);"><div id="elh_laptop_tablet_asset_tag" class="laptop_tablet_asset_tag">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->asset_tag->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->asset_tag->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->asset_tag->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->start_sate->Visible) { // start_sate ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->start_sate) == "") { ?>
		<th data-name="start_sate" class="<?php echo $laptop_tablet->start_sate->HeaderCellClass() ?>"><div id="elh_laptop_tablet_start_sate" class="laptop_tablet_start_sate"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->start_sate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="start_sate" class="<?php echo $laptop_tablet->start_sate->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->start_sate) ?>',1);"><div id="elh_laptop_tablet_start_sate" class="laptop_tablet_start_sate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->start_sate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->start_sate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->start_sate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->end_date->Visible) { // end_date ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->end_date) == "") { ?>
		<th data-name="end_date" class="<?php echo $laptop_tablet->end_date->HeaderCellClass() ?>"><div id="elh_laptop_tablet_end_date" class="laptop_tablet_end_date"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->end_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="end_date" class="<?php echo $laptop_tablet->end_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->end_date) ?>',1);"><div id="elh_laptop_tablet_end_date" class="laptop_tablet_end_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->end_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->end_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->end_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->cost_for_repair->Visible) { // cost_for_repair ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->cost_for_repair) == "") { ?>
		<th data-name="cost_for_repair" class="<?php echo $laptop_tablet->cost_for_repair->HeaderCellClass() ?>"><div id="elh_laptop_tablet_cost_for_repair" class="laptop_tablet_cost_for_repair"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->cost_for_repair->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cost_for_repair" class="<?php echo $laptop_tablet->cost_for_repair->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->cost_for_repair) ?>',1);"><div id="elh_laptop_tablet_cost_for_repair" class="laptop_tablet_cost_for_repair">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->cost_for_repair->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->cost_for_repair->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->cost_for_repair->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->service_provider->Visible) { // service_provider ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->service_provider) == "") { ?>
		<th data-name="service_provider" class="<?php echo $laptop_tablet->service_provider->HeaderCellClass() ?>"><div id="elh_laptop_tablet_service_provider" class="laptop_tablet_service_provider"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->service_provider->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="service_provider" class="<?php echo $laptop_tablet->service_provider->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->service_provider) ?>',1);"><div id="elh_laptop_tablet_service_provider" class="laptop_tablet_service_provider">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->service_provider->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->service_provider->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->service_provider->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->address->Visible) { // address ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->address) == "") { ?>
		<th data-name="address" class="<?php echo $laptop_tablet->address->HeaderCellClass() ?>"><div id="elh_laptop_tablet_address" class="laptop_tablet_address"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address" class="<?php echo $laptop_tablet->address->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->address) ?>',1);"><div id="elh_laptop_tablet_address" class="laptop_tablet_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->type_of_repair->Visible) { // type_of_repair ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->type_of_repair) == "") { ?>
		<th data-name="type_of_repair" class="<?php echo $laptop_tablet->type_of_repair->HeaderCellClass() ?>"><div id="elh_laptop_tablet_type_of_repair" class="laptop_tablet_type_of_repair"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->type_of_repair->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="type_of_repair" class="<?php echo $laptop_tablet->type_of_repair->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->type_of_repair) ?>',1);"><div id="elh_laptop_tablet_type_of_repair" class="laptop_tablet_type_of_repair">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->type_of_repair->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->type_of_repair->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->type_of_repair->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->note->Visible) { // note ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->note) == "") { ?>
		<th data-name="note" class="<?php echo $laptop_tablet->note->HeaderCellClass() ?>"><div id="elh_laptop_tablet_note" class="laptop_tablet_note"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->note->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="note" class="<?php echo $laptop_tablet->note->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->note) ?>',1);"><div id="elh_laptop_tablet_note" class="laptop_tablet_note">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->note->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->note->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->note->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->status->Visible) { // status ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->status) == "") { ?>
		<th data-name="status" class="<?php echo $laptop_tablet->status->HeaderCellClass() ?>"><div id="elh_laptop_tablet_status" class="laptop_tablet_status"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $laptop_tablet->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->status) ?>',1);"><div id="elh_laptop_tablet_status" class="laptop_tablet_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->asset_category->Visible) { // asset_category ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->asset_category) == "") { ?>
		<th data-name="asset_category" class="<?php echo $laptop_tablet->asset_category->HeaderCellClass() ?>"><div id="elh_laptop_tablet_asset_category" class="laptop_tablet_asset_category"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->asset_category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="asset_category" class="<?php echo $laptop_tablet->asset_category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->asset_category) ?>',1);"><div id="elh_laptop_tablet_asset_category" class="laptop_tablet_asset_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->asset_category->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->asset_category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->asset_category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->asset_sub_category->Visible) { // asset_sub_category ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->asset_sub_category) == "") { ?>
		<th data-name="asset_sub_category" class="<?php echo $laptop_tablet->asset_sub_category->HeaderCellClass() ?>"><div id="elh_laptop_tablet_asset_sub_category" class="laptop_tablet_asset_sub_category"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->asset_sub_category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="asset_sub_category" class="<?php echo $laptop_tablet->asset_sub_category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->asset_sub_category) ?>',1);"><div id="elh_laptop_tablet_asset_sub_category" class="laptop_tablet_asset_sub_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->asset_sub_category->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->asset_sub_category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->asset_sub_category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->serial_number->Visible) { // serial_number ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->serial_number) == "") { ?>
		<th data-name="serial_number" class="<?php echo $laptop_tablet->serial_number->HeaderCellClass() ?>"><div id="elh_laptop_tablet_serial_number" class="laptop_tablet_serial_number"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->serial_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serial_number" class="<?php echo $laptop_tablet->serial_number->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->serial_number) ?>',1);"><div id="elh_laptop_tablet_serial_number" class="laptop_tablet_serial_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->serial_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->serial_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->serial_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->programe_area->Visible) { // programe_area ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->programe_area) == "") { ?>
		<th data-name="programe_area" class="<?php echo $laptop_tablet->programe_area->HeaderCellClass() ?>"><div id="elh_laptop_tablet_programe_area" class="laptop_tablet_programe_area"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->programe_area->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="programe_area" class="<?php echo $laptop_tablet->programe_area->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->programe_area) ?>',1);"><div id="elh_laptop_tablet_programe_area" class="laptop_tablet_programe_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->programe_area->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->programe_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->programe_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->division->Visible) { // division ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->division) == "") { ?>
		<th data-name="division" class="<?php echo $laptop_tablet->division->HeaderCellClass() ?>"><div id="elh_laptop_tablet_division" class="laptop_tablet_division"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->division->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="division" class="<?php echo $laptop_tablet->division->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->division) ?>',1);"><div id="elh_laptop_tablet_division" class="laptop_tablet_division">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->division->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->division->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->division->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->branch->Visible) { // branch ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->branch) == "") { ?>
		<th data-name="branch" class="<?php echo $laptop_tablet->branch->HeaderCellClass() ?>"><div id="elh_laptop_tablet_branch" class="laptop_tablet_branch"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->branch->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="branch" class="<?php echo $laptop_tablet->branch->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->branch) ?>',1);"><div id="elh_laptop_tablet_branch" class="laptop_tablet_branch">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->branch->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->branch->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->branch->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->department->Visible) { // department ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->department) == "") { ?>
		<th data-name="department" class="<?php echo $laptop_tablet->department->HeaderCellClass() ?>"><div id="elh_laptop_tablet_department" class="laptop_tablet_department"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $laptop_tablet->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->department) ?>',1);"><div id="elh_laptop_tablet_department" class="laptop_tablet_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->department->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->staff_id->Visible) { // staff_id ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $laptop_tablet->staff_id->HeaderCellClass() ?>"><div id="elh_laptop_tablet_staff_id" class="laptop_tablet_staff_id"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $laptop_tablet->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->staff_id) ?>',1);"><div id="elh_laptop_tablet_staff_id" class="laptop_tablet_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->staff_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->created_by->Visible) { // created_by ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->created_by) == "") { ?>
		<th data-name="created_by" class="<?php echo $laptop_tablet->created_by->HeaderCellClass() ?>"><div id="elh_laptop_tablet_created_by" class="laptop_tablet_created_by"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->created_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created_by" class="<?php echo $laptop_tablet->created_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->created_by) ?>',1);"><div id="elh_laptop_tablet_created_by" class="laptop_tablet_created_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->created_by->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->created_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->created_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->created_date->Visible) { // created_date ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->created_date) == "") { ?>
		<th data-name="created_date" class="<?php echo $laptop_tablet->created_date->HeaderCellClass() ?>"><div id="elh_laptop_tablet_created_date" class="laptop_tablet_created_date"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->created_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created_date" class="<?php echo $laptop_tablet->created_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->created_date) ?>',1);"><div id="elh_laptop_tablet_created_date" class="laptop_tablet_created_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->created_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->created_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->created_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->device_number->Visible) { // device_number ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->device_number) == "") { ?>
		<th data-name="device_number" class="<?php echo $laptop_tablet->device_number->HeaderCellClass() ?>"><div id="elh_laptop_tablet_device_number" class="laptop_tablet_device_number"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->device_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="device_number" class="<?php echo $laptop_tablet->device_number->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->device_number) ?>',1);"><div id="elh_laptop_tablet_device_number" class="laptop_tablet_device_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->device_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->device_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->device_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->tablet_imie_number->Visible) { // tablet_imie_number ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->tablet_imie_number) == "") { ?>
		<th data-name="tablet_imie_number" class="<?php echo $laptop_tablet->tablet_imie_number->HeaderCellClass() ?>"><div id="elh_laptop_tablet_tablet_imie_number" class="laptop_tablet_tablet_imie_number"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->tablet_imie_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tablet_imie_number" class="<?php echo $laptop_tablet->tablet_imie_number->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->tablet_imie_number) ?>',1);"><div id="elh_laptop_tablet_tablet_imie_number" class="laptop_tablet_tablet_imie_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->tablet_imie_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->tablet_imie_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->tablet_imie_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->model->Visible) { // model ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->model) == "") { ?>
		<th data-name="model" class="<?php echo $laptop_tablet->model->HeaderCellClass() ?>"><div id="elh_laptop_tablet_model" class="laptop_tablet_model"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->model->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model" class="<?php echo $laptop_tablet->model->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->model) ?>',1);"><div id="elh_laptop_tablet_model" class="laptop_tablet_model">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->model->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->model->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->model->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->flag->Visible) { // flag ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->flag) == "") { ?>
		<th data-name="flag" class="<?php echo $laptop_tablet->flag->HeaderCellClass() ?>"><div id="elh_laptop_tablet_flag" class="laptop_tablet_flag"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->flag->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="flag" class="<?php echo $laptop_tablet->flag->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->flag) ?>',1);"><div id="elh_laptop_tablet_flag" class="laptop_tablet_flag">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->flag->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->flag->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->flag->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->area->Visible) { // area ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->area) == "") { ?>
		<th data-name="area" class="<?php echo $laptop_tablet->area->HeaderCellClass() ?>"><div id="elh_laptop_tablet_area" class="laptop_tablet_area"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->area->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="area" class="<?php echo $laptop_tablet->area->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->area) ?>',1);"><div id="elh_laptop_tablet_area" class="laptop_tablet_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->area->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->updated_date->Visible) { // updated_date ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->updated_date) == "") { ?>
		<th data-name="updated_date" class="<?php echo $laptop_tablet->updated_date->HeaderCellClass() ?>"><div id="elh_laptop_tablet_updated_date" class="laptop_tablet_updated_date"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->updated_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="updated_date" class="<?php echo $laptop_tablet->updated_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->updated_date) ?>',1);"><div id="elh_laptop_tablet_updated_date" class="laptop_tablet_updated_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->updated_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->updated_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->updated_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->updated_by->Visible) { // updated_by ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->updated_by) == "") { ?>
		<th data-name="updated_by" class="<?php echo $laptop_tablet->updated_by->HeaderCellClass() ?>"><div id="elh_laptop_tablet_updated_by" class="laptop_tablet_updated_by"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->updated_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="updated_by" class="<?php echo $laptop_tablet->updated_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->updated_by) ?>',1);"><div id="elh_laptop_tablet_updated_by" class="laptop_tablet_updated_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->updated_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->updated_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->updated_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->received_date->Visible) { // received_date ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->received_date) == "") { ?>
		<th data-name="received_date" class="<?php echo $laptop_tablet->received_date->HeaderCellClass() ?>"><div id="elh_laptop_tablet_received_date" class="laptop_tablet_received_date"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->received_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="received_date" class="<?php echo $laptop_tablet->received_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->received_date) ?>',1);"><div id="elh_laptop_tablet_received_date" class="laptop_tablet_received_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->received_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->received_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->received_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($laptop_tablet->received_by->Visible) { // received_by ?>
	<?php if ($laptop_tablet->SortUrl($laptop_tablet->received_by) == "") { ?>
		<th data-name="received_by" class="<?php echo $laptop_tablet->received_by->HeaderCellClass() ?>"><div id="elh_laptop_tablet_received_by" class="laptop_tablet_received_by"><div class="ewTableHeaderCaption"><?php echo $laptop_tablet->received_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="received_by" class="<?php echo $laptop_tablet->received_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laptop_tablet->SortUrl($laptop_tablet->received_by) ?>',1);"><div id="elh_laptop_tablet_received_by" class="laptop_tablet_received_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laptop_tablet->received_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laptop_tablet->received_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laptop_tablet->received_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$laptop_tablet_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($laptop_tablet->ExportAll && $laptop_tablet->Export <> "") {
	$laptop_tablet_list->StopRec = $laptop_tablet_list->TotalRecs;
} else {

	// Set the last record to display
	if ($laptop_tablet_list->TotalRecs > $laptop_tablet_list->StartRec + $laptop_tablet_list->DisplayRecs - 1)
		$laptop_tablet_list->StopRec = $laptop_tablet_list->StartRec + $laptop_tablet_list->DisplayRecs - 1;
	else
		$laptop_tablet_list->StopRec = $laptop_tablet_list->TotalRecs;
}
$laptop_tablet_list->RecCnt = $laptop_tablet_list->StartRec - 1;
if ($laptop_tablet_list->Recordset && !$laptop_tablet_list->Recordset->EOF) {
	$laptop_tablet_list->Recordset->MoveFirst();
	$bSelectLimit = $laptop_tablet_list->UseSelectLimit;
	if (!$bSelectLimit && $laptop_tablet_list->StartRec > 1)
		$laptop_tablet_list->Recordset->Move($laptop_tablet_list->StartRec - 1);
} elseif (!$laptop_tablet->AllowAddDeleteRow && $laptop_tablet_list->StopRec == 0) {
	$laptop_tablet_list->StopRec = $laptop_tablet->GridAddRowCount;
}

// Initialize aggregate
$laptop_tablet->RowType = EW_ROWTYPE_AGGREGATEINIT;
$laptop_tablet->ResetAttrs();
$laptop_tablet_list->RenderRow();
while ($laptop_tablet_list->RecCnt < $laptop_tablet_list->StopRec) {
	$laptop_tablet_list->RecCnt++;
	if (intval($laptop_tablet_list->RecCnt) >= intval($laptop_tablet_list->StartRec)) {
		$laptop_tablet_list->RowCnt++;

		// Set up key count
		$laptop_tablet_list->KeyCount = $laptop_tablet_list->RowIndex;

		// Init row class and style
		$laptop_tablet->ResetAttrs();
		$laptop_tablet->CssClass = "";
		if ($laptop_tablet->CurrentAction == "gridadd") {
		} else {
			$laptop_tablet_list->LoadRowValues($laptop_tablet_list->Recordset); // Load row values
		}
		$laptop_tablet->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$laptop_tablet->RowAttrs = array_merge($laptop_tablet->RowAttrs, array('data-rowindex'=>$laptop_tablet_list->RowCnt, 'id'=>'r' . $laptop_tablet_list->RowCnt . '_laptop_tablet', 'data-rowtype'=>$laptop_tablet->RowType));

		// Render row
		$laptop_tablet_list->RenderRow();

		// Render list options
		$laptop_tablet_list->RenderListOptions();
?>
	<tr<?php echo $laptop_tablet->RowAttributes() ?>>
<?php

// Render list options (body, left)
$laptop_tablet_list->ListOptions->Render("body", "left", $laptop_tablet_list->RowCnt);
?>
	<?php if ($laptop_tablet->id->Visible) { // id ?>
		<td data-name="id"<?php echo $laptop_tablet->id->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_id" class="laptop_tablet_id">
<span<?php echo $laptop_tablet->id->ViewAttributes() ?>>
<?php echo $laptop_tablet->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->asset_tag->Visible) { // asset_tag ?>
		<td data-name="asset_tag"<?php echo $laptop_tablet->asset_tag->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_asset_tag" class="laptop_tablet_asset_tag">
<span<?php echo $laptop_tablet->asset_tag->ViewAttributes() ?>>
<?php echo $laptop_tablet->asset_tag->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->start_sate->Visible) { // start_sate ?>
		<td data-name="start_sate"<?php echo $laptop_tablet->start_sate->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_start_sate" class="laptop_tablet_start_sate">
<span<?php echo $laptop_tablet->start_sate->ViewAttributes() ?>>
<?php echo $laptop_tablet->start_sate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->end_date->Visible) { // end_date ?>
		<td data-name="end_date"<?php echo $laptop_tablet->end_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_end_date" class="laptop_tablet_end_date">
<span<?php echo $laptop_tablet->end_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->end_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->cost_for_repair->Visible) { // cost_for_repair ?>
		<td data-name="cost_for_repair"<?php echo $laptop_tablet->cost_for_repair->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_cost_for_repair" class="laptop_tablet_cost_for_repair">
<span<?php echo $laptop_tablet->cost_for_repair->ViewAttributes() ?>>
<?php echo $laptop_tablet->cost_for_repair->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->service_provider->Visible) { // service_provider ?>
		<td data-name="service_provider"<?php echo $laptop_tablet->service_provider->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_service_provider" class="laptop_tablet_service_provider">
<span<?php echo $laptop_tablet->service_provider->ViewAttributes() ?>>
<?php echo $laptop_tablet->service_provider->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->address->Visible) { // address ?>
		<td data-name="address"<?php echo $laptop_tablet->address->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_address" class="laptop_tablet_address">
<span<?php echo $laptop_tablet->address->ViewAttributes() ?>>
<?php echo $laptop_tablet->address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->type_of_repair->Visible) { // type_of_repair ?>
		<td data-name="type_of_repair"<?php echo $laptop_tablet->type_of_repair->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_type_of_repair" class="laptop_tablet_type_of_repair">
<span<?php echo $laptop_tablet->type_of_repair->ViewAttributes() ?>>
<?php echo $laptop_tablet->type_of_repair->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->note->Visible) { // note ?>
		<td data-name="note"<?php echo $laptop_tablet->note->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_note" class="laptop_tablet_note">
<span<?php echo $laptop_tablet->note->ViewAttributes() ?>>
<?php echo $laptop_tablet->note->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->status->Visible) { // status ?>
		<td data-name="status"<?php echo $laptop_tablet->status->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_status" class="laptop_tablet_status">
<span<?php echo $laptop_tablet->status->ViewAttributes() ?>>
<?php echo $laptop_tablet->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->asset_category->Visible) { // asset_category ?>
		<td data-name="asset_category"<?php echo $laptop_tablet->asset_category->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_asset_category" class="laptop_tablet_asset_category">
<span<?php echo $laptop_tablet->asset_category->ViewAttributes() ?>>
<?php echo $laptop_tablet->asset_category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->asset_sub_category->Visible) { // asset_sub_category ?>
		<td data-name="asset_sub_category"<?php echo $laptop_tablet->asset_sub_category->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_asset_sub_category" class="laptop_tablet_asset_sub_category">
<span<?php echo $laptop_tablet->asset_sub_category->ViewAttributes() ?>>
<?php echo $laptop_tablet->asset_sub_category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->serial_number->Visible) { // serial_number ?>
		<td data-name="serial_number"<?php echo $laptop_tablet->serial_number->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_serial_number" class="laptop_tablet_serial_number">
<span<?php echo $laptop_tablet->serial_number->ViewAttributes() ?>>
<?php echo $laptop_tablet->serial_number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->programe_area->Visible) { // programe_area ?>
		<td data-name="programe_area"<?php echo $laptop_tablet->programe_area->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_programe_area" class="laptop_tablet_programe_area">
<span<?php echo $laptop_tablet->programe_area->ViewAttributes() ?>>
<?php echo $laptop_tablet->programe_area->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->division->Visible) { // division ?>
		<td data-name="division"<?php echo $laptop_tablet->division->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_division" class="laptop_tablet_division">
<span<?php echo $laptop_tablet->division->ViewAttributes() ?>>
<?php echo $laptop_tablet->division->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->branch->Visible) { // branch ?>
		<td data-name="branch"<?php echo $laptop_tablet->branch->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_branch" class="laptop_tablet_branch">
<span<?php echo $laptop_tablet->branch->ViewAttributes() ?>>
<?php echo $laptop_tablet->branch->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->department->Visible) { // department ?>
		<td data-name="department"<?php echo $laptop_tablet->department->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_department" class="laptop_tablet_department">
<span<?php echo $laptop_tablet->department->ViewAttributes() ?>>
<?php echo $laptop_tablet->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $laptop_tablet->staff_id->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_staff_id" class="laptop_tablet_staff_id">
<span<?php echo $laptop_tablet->staff_id->ViewAttributes() ?>>
<?php echo $laptop_tablet->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->created_by->Visible) { // created_by ?>
		<td data-name="created_by"<?php echo $laptop_tablet->created_by->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_created_by" class="laptop_tablet_created_by">
<span<?php echo $laptop_tablet->created_by->ViewAttributes() ?>>
<?php echo $laptop_tablet->created_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->created_date->Visible) { // created_date ?>
		<td data-name="created_date"<?php echo $laptop_tablet->created_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_created_date" class="laptop_tablet_created_date">
<span<?php echo $laptop_tablet->created_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->created_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->device_number->Visible) { // device_number ?>
		<td data-name="device_number"<?php echo $laptop_tablet->device_number->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_device_number" class="laptop_tablet_device_number">
<span<?php echo $laptop_tablet->device_number->ViewAttributes() ?>>
<?php echo $laptop_tablet->device_number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->tablet_imie_number->Visible) { // tablet_imie_number ?>
		<td data-name="tablet_imie_number"<?php echo $laptop_tablet->tablet_imie_number->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_tablet_imie_number" class="laptop_tablet_tablet_imie_number">
<span<?php echo $laptop_tablet->tablet_imie_number->ViewAttributes() ?>>
<?php echo $laptop_tablet->tablet_imie_number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->model->Visible) { // model ?>
		<td data-name="model"<?php echo $laptop_tablet->model->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_model" class="laptop_tablet_model">
<span<?php echo $laptop_tablet->model->ViewAttributes() ?>>
<?php echo $laptop_tablet->model->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->flag->Visible) { // flag ?>
		<td data-name="flag"<?php echo $laptop_tablet->flag->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_flag" class="laptop_tablet_flag">
<span<?php echo $laptop_tablet->flag->ViewAttributes() ?>>
<?php echo $laptop_tablet->flag->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->area->Visible) { // area ?>
		<td data-name="area"<?php echo $laptop_tablet->area->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_area" class="laptop_tablet_area">
<span<?php echo $laptop_tablet->area->ViewAttributes() ?>>
<?php echo $laptop_tablet->area->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->updated_date->Visible) { // updated_date ?>
		<td data-name="updated_date"<?php echo $laptop_tablet->updated_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_updated_date" class="laptop_tablet_updated_date">
<span<?php echo $laptop_tablet->updated_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->updated_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->updated_by->Visible) { // updated_by ?>
		<td data-name="updated_by"<?php echo $laptop_tablet->updated_by->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_updated_by" class="laptop_tablet_updated_by">
<span<?php echo $laptop_tablet->updated_by->ViewAttributes() ?>>
<?php echo $laptop_tablet->updated_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->received_date->Visible) { // received_date ?>
		<td data-name="received_date"<?php echo $laptop_tablet->received_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_received_date" class="laptop_tablet_received_date">
<span<?php echo $laptop_tablet->received_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->received_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($laptop_tablet->received_by->Visible) { // received_by ?>
		<td data-name="received_by"<?php echo $laptop_tablet->received_by->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_list->RowCnt ?>_laptop_tablet_received_by" class="laptop_tablet_received_by">
<span<?php echo $laptop_tablet->received_by->ViewAttributes() ?>>
<?php echo $laptop_tablet->received_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$laptop_tablet_list->ListOptions->Render("body", "right", $laptop_tablet_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($laptop_tablet->CurrentAction <> "gridadd")
		$laptop_tablet_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($laptop_tablet->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($laptop_tablet_list->Recordset)
	$laptop_tablet_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($laptop_tablet_list->TotalRecs == 0 && $laptop_tablet->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($laptop_tablet_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($laptop_tablet->Export == "") { ?>
<script type="text/javascript">
flaptop_tabletlistsrch.FilterList = <?php echo $laptop_tablet_list->GetFilterList() ?>;
flaptop_tabletlistsrch.Init();
flaptop_tabletlist.Init();
</script>
<?php } ?>
<?php
$laptop_tablet_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($laptop_tablet->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$laptop_tablet_list->Page_Terminate();
?>
