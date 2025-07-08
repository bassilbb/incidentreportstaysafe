<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "loan_applicationinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$loan_application_list = NULL; // Initialize page object first

class cloan_application_list extends cloan_application {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'loan_application';

	// Page object name
	var $PageObjName = 'loan_application_list';

	// Grid form hidden field names
	var $FormName = 'floan_applicationlist';
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

		// Table object (loan_application)
		if (!isset($GLOBALS["loan_application"]) || get_class($GLOBALS["loan_application"]) == "cloan_application") {
			$GLOBALS["loan_application"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["loan_application"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "loan_applicationadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "loan_applicationdelete.php";
		$this->MultiUpdateUrl = "loan_applicationupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'loan_application');

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
		$this->FilterOptions->TagClassName = "ewFilterOption floan_applicationlistsrch";

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
		$this->refernce_id->SetVisibility();
		$this->employee_name->SetVisibility();
		$this->address->SetVisibility();
		$this->mobile->SetVisibility();
		$this->department->SetVisibility();
		$this->pension->SetVisibility();
		$this->loan_amount->SetVisibility();
		$this->amount_inwords->SetVisibility();
		$this->repayment_period->SetVisibility();
		$this->salary_permonth->SetVisibility();
		$this->previous_loan->SetVisibility();
		$this->date_collected->SetVisibility();
		$this->date_liquidated->SetVisibility();
		$this->balance_remaining->SetVisibility();
		$this->applicant_date->SetVisibility();
		$this->guarantor_name->SetVisibility();
		$this->guarantor_address->SetVisibility();
		$this->guarantor_mobile->SetVisibility();
		$this->status->SetVisibility();
		$this->application_status->SetVisibility();
		$this->correction_date->SetVisibility();
		$this->correction_action->SetVisibility();
		$this->correction_comment->SetVisibility();
		$this->corrected_by->SetVisibility();

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
		global $EW_EXPORT, $loan_application;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($loan_application);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "floan_applicationlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->code->AdvancedSearch->ToJson(), ","); // Field code
		$sFilterList = ew_Concat($sFilterList, $this->date_initiated->AdvancedSearch->ToJson(), ","); // Field date_initiated
		$sFilterList = ew_Concat($sFilterList, $this->refernce_id->AdvancedSearch->ToJson(), ","); // Field refernce_id
		$sFilterList = ew_Concat($sFilterList, $this->employee_name->AdvancedSearch->ToJson(), ","); // Field employee_name
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJson(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->mobile->AdvancedSearch->ToJson(), ","); // Field mobile
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->pension->AdvancedSearch->ToJson(), ","); // Field pension
		$sFilterList = ew_Concat($sFilterList, $this->loan_amount->AdvancedSearch->ToJson(), ","); // Field loan_amount
		$sFilterList = ew_Concat($sFilterList, $this->amount_inwords->AdvancedSearch->ToJson(), ","); // Field amount_inwords
		$sFilterList = ew_Concat($sFilterList, $this->purpose->AdvancedSearch->ToJson(), ","); // Field purpose
		$sFilterList = ew_Concat($sFilterList, $this->repayment_period->AdvancedSearch->ToJson(), ","); // Field repayment_period
		$sFilterList = ew_Concat($sFilterList, $this->salary_permonth->AdvancedSearch->ToJson(), ","); // Field salary_permonth
		$sFilterList = ew_Concat($sFilterList, $this->previous_loan->AdvancedSearch->ToJson(), ","); // Field previous_loan
		$sFilterList = ew_Concat($sFilterList, $this->date_collected->AdvancedSearch->ToJson(), ","); // Field date_collected
		$sFilterList = ew_Concat($sFilterList, $this->date_liquidated->AdvancedSearch->ToJson(), ","); // Field date_liquidated
		$sFilterList = ew_Concat($sFilterList, $this->balance_remaining->AdvancedSearch->ToJson(), ","); // Field balance_remaining
		$sFilterList = ew_Concat($sFilterList, $this->applicant_date->AdvancedSearch->ToJson(), ","); // Field applicant_date
		$sFilterList = ew_Concat($sFilterList, $this->applicant_passport->AdvancedSearch->ToJson(), ","); // Field applicant_passport
		$sFilterList = ew_Concat($sFilterList, $this->guarantor_name->AdvancedSearch->ToJson(), ","); // Field guarantor_name
		$sFilterList = ew_Concat($sFilterList, $this->guarantor_address->AdvancedSearch->ToJson(), ","); // Field guarantor_address
		$sFilterList = ew_Concat($sFilterList, $this->guarantor_mobile->AdvancedSearch->ToJson(), ","); // Field guarantor_mobile
		$sFilterList = ew_Concat($sFilterList, $this->guarantor_department->AdvancedSearch->ToJson(), ","); // Field guarantor_department
		$sFilterList = ew_Concat($sFilterList, $this->account_no->AdvancedSearch->ToJson(), ","); // Field account_no
		$sFilterList = ew_Concat($sFilterList, $this->bank_name->AdvancedSearch->ToJson(), ","); // Field bank_name
		$sFilterList = ew_Concat($sFilterList, $this->employers_name->AdvancedSearch->ToJson(), ","); // Field employers_name
		$sFilterList = ew_Concat($sFilterList, $this->employers_address->AdvancedSearch->ToJson(), ","); // Field employers_address
		$sFilterList = ew_Concat($sFilterList, $this->employers_mobile->AdvancedSearch->ToJson(), ","); // Field employers_mobile
		$sFilterList = ew_Concat($sFilterList, $this->guarantor_date->AdvancedSearch->ToJson(), ","); // Field guarantor_date
		$sFilterList = ew_Concat($sFilterList, $this->guarantor_passport->AdvancedSearch->ToJson(), ","); // Field guarantor_passport
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->initiator_action->AdvancedSearch->ToJson(), ","); // Field initiator_action
		$sFilterList = ew_Concat($sFilterList, $this->initiator_comment->AdvancedSearch->ToJson(), ","); // Field initiator_comment
		$sFilterList = ew_Concat($sFilterList, $this->recommended_date->AdvancedSearch->ToJson(), ","); // Field recommended_date
		$sFilterList = ew_Concat($sFilterList, $this->document_checklist->AdvancedSearch->ToJson(), ","); // Field document_checklist
		$sFilterList = ew_Concat($sFilterList, $this->recommender_action->AdvancedSearch->ToJson(), ","); // Field recommender_action
		$sFilterList = ew_Concat($sFilterList, $this->recommender_comment->AdvancedSearch->ToJson(), ","); // Field recommender_comment
		$sFilterList = ew_Concat($sFilterList, $this->recommended_by->AdvancedSearch->ToJson(), ","); // Field recommended_by
		$sFilterList = ew_Concat($sFilterList, $this->application_status->AdvancedSearch->ToJson(), ","); // Field application_status
		$sFilterList = ew_Concat($sFilterList, $this->approved_amount->AdvancedSearch->ToJson(), ","); // Field approved_amount
		$sFilterList = ew_Concat($sFilterList, $this->duration_approved->AdvancedSearch->ToJson(), ","); // Field duration_approved
		$sFilterList = ew_Concat($sFilterList, $this->approval_date->AdvancedSearch->ToJson(), ","); // Field approval_date
		$sFilterList = ew_Concat($sFilterList, $this->approval_action->AdvancedSearch->ToJson(), ","); // Field approval_action
		$sFilterList = ew_Concat($sFilterList, $this->approval_comment->AdvancedSearch->ToJson(), ","); // Field approval_comment
		$sFilterList = ew_Concat($sFilterList, $this->approved_by->AdvancedSearch->ToJson(), ","); // Field approved_by
		$sFilterList = ew_Concat($sFilterList, $this->correction_date->AdvancedSearch->ToJson(), ","); // Field correction_date
		$sFilterList = ew_Concat($sFilterList, $this->correction_action->AdvancedSearch->ToJson(), ","); // Field correction_action
		$sFilterList = ew_Concat($sFilterList, $this->correction_comment->AdvancedSearch->ToJson(), ","); // Field correction_comment
		$sFilterList = ew_Concat($sFilterList, $this->corrected_by->AdvancedSearch->ToJson(), ","); // Field corrected_by
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "floan_applicationlistsrch", $filters);

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

		// Field date_initiated
		$this->date_initiated->AdvancedSearch->SearchValue = @$filter["x_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchOperator = @$filter["z_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchCondition = @$filter["v_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchValue2 = @$filter["y_date_initiated"];
		$this->date_initiated->AdvancedSearch->SearchOperator2 = @$filter["w_date_initiated"];
		$this->date_initiated->AdvancedSearch->Save();

		// Field refernce_id
		$this->refernce_id->AdvancedSearch->SearchValue = @$filter["x_refernce_id"];
		$this->refernce_id->AdvancedSearch->SearchOperator = @$filter["z_refernce_id"];
		$this->refernce_id->AdvancedSearch->SearchCondition = @$filter["v_refernce_id"];
		$this->refernce_id->AdvancedSearch->SearchValue2 = @$filter["y_refernce_id"];
		$this->refernce_id->AdvancedSearch->SearchOperator2 = @$filter["w_refernce_id"];
		$this->refernce_id->AdvancedSearch->Save();

		// Field employee_name
		$this->employee_name->AdvancedSearch->SearchValue = @$filter["x_employee_name"];
		$this->employee_name->AdvancedSearch->SearchOperator = @$filter["z_employee_name"];
		$this->employee_name->AdvancedSearch->SearchCondition = @$filter["v_employee_name"];
		$this->employee_name->AdvancedSearch->SearchValue2 = @$filter["y_employee_name"];
		$this->employee_name->AdvancedSearch->SearchOperator2 = @$filter["w_employee_name"];
		$this->employee_name->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field mobile
		$this->mobile->AdvancedSearch->SearchValue = @$filter["x_mobile"];
		$this->mobile->AdvancedSearch->SearchOperator = @$filter["z_mobile"];
		$this->mobile->AdvancedSearch->SearchCondition = @$filter["v_mobile"];
		$this->mobile->AdvancedSearch->SearchValue2 = @$filter["y_mobile"];
		$this->mobile->AdvancedSearch->SearchOperator2 = @$filter["w_mobile"];
		$this->mobile->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field pension
		$this->pension->AdvancedSearch->SearchValue = @$filter["x_pension"];
		$this->pension->AdvancedSearch->SearchOperator = @$filter["z_pension"];
		$this->pension->AdvancedSearch->SearchCondition = @$filter["v_pension"];
		$this->pension->AdvancedSearch->SearchValue2 = @$filter["y_pension"];
		$this->pension->AdvancedSearch->SearchOperator2 = @$filter["w_pension"];
		$this->pension->AdvancedSearch->Save();

		// Field loan_amount
		$this->loan_amount->AdvancedSearch->SearchValue = @$filter["x_loan_amount"];
		$this->loan_amount->AdvancedSearch->SearchOperator = @$filter["z_loan_amount"];
		$this->loan_amount->AdvancedSearch->SearchCondition = @$filter["v_loan_amount"];
		$this->loan_amount->AdvancedSearch->SearchValue2 = @$filter["y_loan_amount"];
		$this->loan_amount->AdvancedSearch->SearchOperator2 = @$filter["w_loan_amount"];
		$this->loan_amount->AdvancedSearch->Save();

		// Field amount_inwords
		$this->amount_inwords->AdvancedSearch->SearchValue = @$filter["x_amount_inwords"];
		$this->amount_inwords->AdvancedSearch->SearchOperator = @$filter["z_amount_inwords"];
		$this->amount_inwords->AdvancedSearch->SearchCondition = @$filter["v_amount_inwords"];
		$this->amount_inwords->AdvancedSearch->SearchValue2 = @$filter["y_amount_inwords"];
		$this->amount_inwords->AdvancedSearch->SearchOperator2 = @$filter["w_amount_inwords"];
		$this->amount_inwords->AdvancedSearch->Save();

		// Field purpose
		$this->purpose->AdvancedSearch->SearchValue = @$filter["x_purpose"];
		$this->purpose->AdvancedSearch->SearchOperator = @$filter["z_purpose"];
		$this->purpose->AdvancedSearch->SearchCondition = @$filter["v_purpose"];
		$this->purpose->AdvancedSearch->SearchValue2 = @$filter["y_purpose"];
		$this->purpose->AdvancedSearch->SearchOperator2 = @$filter["w_purpose"];
		$this->purpose->AdvancedSearch->Save();

		// Field repayment_period
		$this->repayment_period->AdvancedSearch->SearchValue = @$filter["x_repayment_period"];
		$this->repayment_period->AdvancedSearch->SearchOperator = @$filter["z_repayment_period"];
		$this->repayment_period->AdvancedSearch->SearchCondition = @$filter["v_repayment_period"];
		$this->repayment_period->AdvancedSearch->SearchValue2 = @$filter["y_repayment_period"];
		$this->repayment_period->AdvancedSearch->SearchOperator2 = @$filter["w_repayment_period"];
		$this->repayment_period->AdvancedSearch->Save();

		// Field salary_permonth
		$this->salary_permonth->AdvancedSearch->SearchValue = @$filter["x_salary_permonth"];
		$this->salary_permonth->AdvancedSearch->SearchOperator = @$filter["z_salary_permonth"];
		$this->salary_permonth->AdvancedSearch->SearchCondition = @$filter["v_salary_permonth"];
		$this->salary_permonth->AdvancedSearch->SearchValue2 = @$filter["y_salary_permonth"];
		$this->salary_permonth->AdvancedSearch->SearchOperator2 = @$filter["w_salary_permonth"];
		$this->salary_permonth->AdvancedSearch->Save();

		// Field previous_loan
		$this->previous_loan->AdvancedSearch->SearchValue = @$filter["x_previous_loan"];
		$this->previous_loan->AdvancedSearch->SearchOperator = @$filter["z_previous_loan"];
		$this->previous_loan->AdvancedSearch->SearchCondition = @$filter["v_previous_loan"];
		$this->previous_loan->AdvancedSearch->SearchValue2 = @$filter["y_previous_loan"];
		$this->previous_loan->AdvancedSearch->SearchOperator2 = @$filter["w_previous_loan"];
		$this->previous_loan->AdvancedSearch->Save();

		// Field date_collected
		$this->date_collected->AdvancedSearch->SearchValue = @$filter["x_date_collected"];
		$this->date_collected->AdvancedSearch->SearchOperator = @$filter["z_date_collected"];
		$this->date_collected->AdvancedSearch->SearchCondition = @$filter["v_date_collected"];
		$this->date_collected->AdvancedSearch->SearchValue2 = @$filter["y_date_collected"];
		$this->date_collected->AdvancedSearch->SearchOperator2 = @$filter["w_date_collected"];
		$this->date_collected->AdvancedSearch->Save();

		// Field date_liquidated
		$this->date_liquidated->AdvancedSearch->SearchValue = @$filter["x_date_liquidated"];
		$this->date_liquidated->AdvancedSearch->SearchOperator = @$filter["z_date_liquidated"];
		$this->date_liquidated->AdvancedSearch->SearchCondition = @$filter["v_date_liquidated"];
		$this->date_liquidated->AdvancedSearch->SearchValue2 = @$filter["y_date_liquidated"];
		$this->date_liquidated->AdvancedSearch->SearchOperator2 = @$filter["w_date_liquidated"];
		$this->date_liquidated->AdvancedSearch->Save();

		// Field balance_remaining
		$this->balance_remaining->AdvancedSearch->SearchValue = @$filter["x_balance_remaining"];
		$this->balance_remaining->AdvancedSearch->SearchOperator = @$filter["z_balance_remaining"];
		$this->balance_remaining->AdvancedSearch->SearchCondition = @$filter["v_balance_remaining"];
		$this->balance_remaining->AdvancedSearch->SearchValue2 = @$filter["y_balance_remaining"];
		$this->balance_remaining->AdvancedSearch->SearchOperator2 = @$filter["w_balance_remaining"];
		$this->balance_remaining->AdvancedSearch->Save();

		// Field applicant_date
		$this->applicant_date->AdvancedSearch->SearchValue = @$filter["x_applicant_date"];
		$this->applicant_date->AdvancedSearch->SearchOperator = @$filter["z_applicant_date"];
		$this->applicant_date->AdvancedSearch->SearchCondition = @$filter["v_applicant_date"];
		$this->applicant_date->AdvancedSearch->SearchValue2 = @$filter["y_applicant_date"];
		$this->applicant_date->AdvancedSearch->SearchOperator2 = @$filter["w_applicant_date"];
		$this->applicant_date->AdvancedSearch->Save();

		// Field applicant_passport
		$this->applicant_passport->AdvancedSearch->SearchValue = @$filter["x_applicant_passport"];
		$this->applicant_passport->AdvancedSearch->SearchOperator = @$filter["z_applicant_passport"];
		$this->applicant_passport->AdvancedSearch->SearchCondition = @$filter["v_applicant_passport"];
		$this->applicant_passport->AdvancedSearch->SearchValue2 = @$filter["y_applicant_passport"];
		$this->applicant_passport->AdvancedSearch->SearchOperator2 = @$filter["w_applicant_passport"];
		$this->applicant_passport->AdvancedSearch->Save();

		// Field guarantor_name
		$this->guarantor_name->AdvancedSearch->SearchValue = @$filter["x_guarantor_name"];
		$this->guarantor_name->AdvancedSearch->SearchOperator = @$filter["z_guarantor_name"];
		$this->guarantor_name->AdvancedSearch->SearchCondition = @$filter["v_guarantor_name"];
		$this->guarantor_name->AdvancedSearch->SearchValue2 = @$filter["y_guarantor_name"];
		$this->guarantor_name->AdvancedSearch->SearchOperator2 = @$filter["w_guarantor_name"];
		$this->guarantor_name->AdvancedSearch->Save();

		// Field guarantor_address
		$this->guarantor_address->AdvancedSearch->SearchValue = @$filter["x_guarantor_address"];
		$this->guarantor_address->AdvancedSearch->SearchOperator = @$filter["z_guarantor_address"];
		$this->guarantor_address->AdvancedSearch->SearchCondition = @$filter["v_guarantor_address"];
		$this->guarantor_address->AdvancedSearch->SearchValue2 = @$filter["y_guarantor_address"];
		$this->guarantor_address->AdvancedSearch->SearchOperator2 = @$filter["w_guarantor_address"];
		$this->guarantor_address->AdvancedSearch->Save();

		// Field guarantor_mobile
		$this->guarantor_mobile->AdvancedSearch->SearchValue = @$filter["x_guarantor_mobile"];
		$this->guarantor_mobile->AdvancedSearch->SearchOperator = @$filter["z_guarantor_mobile"];
		$this->guarantor_mobile->AdvancedSearch->SearchCondition = @$filter["v_guarantor_mobile"];
		$this->guarantor_mobile->AdvancedSearch->SearchValue2 = @$filter["y_guarantor_mobile"];
		$this->guarantor_mobile->AdvancedSearch->SearchOperator2 = @$filter["w_guarantor_mobile"];
		$this->guarantor_mobile->AdvancedSearch->Save();

		// Field guarantor_department
		$this->guarantor_department->AdvancedSearch->SearchValue = @$filter["x_guarantor_department"];
		$this->guarantor_department->AdvancedSearch->SearchOperator = @$filter["z_guarantor_department"];
		$this->guarantor_department->AdvancedSearch->SearchCondition = @$filter["v_guarantor_department"];
		$this->guarantor_department->AdvancedSearch->SearchValue2 = @$filter["y_guarantor_department"];
		$this->guarantor_department->AdvancedSearch->SearchOperator2 = @$filter["w_guarantor_department"];
		$this->guarantor_department->AdvancedSearch->Save();

		// Field account_no
		$this->account_no->AdvancedSearch->SearchValue = @$filter["x_account_no"];
		$this->account_no->AdvancedSearch->SearchOperator = @$filter["z_account_no"];
		$this->account_no->AdvancedSearch->SearchCondition = @$filter["v_account_no"];
		$this->account_no->AdvancedSearch->SearchValue2 = @$filter["y_account_no"];
		$this->account_no->AdvancedSearch->SearchOperator2 = @$filter["w_account_no"];
		$this->account_no->AdvancedSearch->Save();

		// Field bank_name
		$this->bank_name->AdvancedSearch->SearchValue = @$filter["x_bank_name"];
		$this->bank_name->AdvancedSearch->SearchOperator = @$filter["z_bank_name"];
		$this->bank_name->AdvancedSearch->SearchCondition = @$filter["v_bank_name"];
		$this->bank_name->AdvancedSearch->SearchValue2 = @$filter["y_bank_name"];
		$this->bank_name->AdvancedSearch->SearchOperator2 = @$filter["w_bank_name"];
		$this->bank_name->AdvancedSearch->Save();

		// Field employers_name
		$this->employers_name->AdvancedSearch->SearchValue = @$filter["x_employers_name"];
		$this->employers_name->AdvancedSearch->SearchOperator = @$filter["z_employers_name"];
		$this->employers_name->AdvancedSearch->SearchCondition = @$filter["v_employers_name"];
		$this->employers_name->AdvancedSearch->SearchValue2 = @$filter["y_employers_name"];
		$this->employers_name->AdvancedSearch->SearchOperator2 = @$filter["w_employers_name"];
		$this->employers_name->AdvancedSearch->Save();

		// Field employers_address
		$this->employers_address->AdvancedSearch->SearchValue = @$filter["x_employers_address"];
		$this->employers_address->AdvancedSearch->SearchOperator = @$filter["z_employers_address"];
		$this->employers_address->AdvancedSearch->SearchCondition = @$filter["v_employers_address"];
		$this->employers_address->AdvancedSearch->SearchValue2 = @$filter["y_employers_address"];
		$this->employers_address->AdvancedSearch->SearchOperator2 = @$filter["w_employers_address"];
		$this->employers_address->AdvancedSearch->Save();

		// Field employers_mobile
		$this->employers_mobile->AdvancedSearch->SearchValue = @$filter["x_employers_mobile"];
		$this->employers_mobile->AdvancedSearch->SearchOperator = @$filter["z_employers_mobile"];
		$this->employers_mobile->AdvancedSearch->SearchCondition = @$filter["v_employers_mobile"];
		$this->employers_mobile->AdvancedSearch->SearchValue2 = @$filter["y_employers_mobile"];
		$this->employers_mobile->AdvancedSearch->SearchOperator2 = @$filter["w_employers_mobile"];
		$this->employers_mobile->AdvancedSearch->Save();

		// Field guarantor_date
		$this->guarantor_date->AdvancedSearch->SearchValue = @$filter["x_guarantor_date"];
		$this->guarantor_date->AdvancedSearch->SearchOperator = @$filter["z_guarantor_date"];
		$this->guarantor_date->AdvancedSearch->SearchCondition = @$filter["v_guarantor_date"];
		$this->guarantor_date->AdvancedSearch->SearchValue2 = @$filter["y_guarantor_date"];
		$this->guarantor_date->AdvancedSearch->SearchOperator2 = @$filter["w_guarantor_date"];
		$this->guarantor_date->AdvancedSearch->Save();

		// Field guarantor_passport
		$this->guarantor_passport->AdvancedSearch->SearchValue = @$filter["x_guarantor_passport"];
		$this->guarantor_passport->AdvancedSearch->SearchOperator = @$filter["z_guarantor_passport"];
		$this->guarantor_passport->AdvancedSearch->SearchCondition = @$filter["v_guarantor_passport"];
		$this->guarantor_passport->AdvancedSearch->SearchValue2 = @$filter["y_guarantor_passport"];
		$this->guarantor_passport->AdvancedSearch->SearchOperator2 = @$filter["w_guarantor_passport"];
		$this->guarantor_passport->AdvancedSearch->Save();

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

		// Field recommended_date
		$this->recommended_date->AdvancedSearch->SearchValue = @$filter["x_recommended_date"];
		$this->recommended_date->AdvancedSearch->SearchOperator = @$filter["z_recommended_date"];
		$this->recommended_date->AdvancedSearch->SearchCondition = @$filter["v_recommended_date"];
		$this->recommended_date->AdvancedSearch->SearchValue2 = @$filter["y_recommended_date"];
		$this->recommended_date->AdvancedSearch->SearchOperator2 = @$filter["w_recommended_date"];
		$this->recommended_date->AdvancedSearch->Save();

		// Field document_checklist
		$this->document_checklist->AdvancedSearch->SearchValue = @$filter["x_document_checklist"];
		$this->document_checklist->AdvancedSearch->SearchOperator = @$filter["z_document_checklist"];
		$this->document_checklist->AdvancedSearch->SearchCondition = @$filter["v_document_checklist"];
		$this->document_checklist->AdvancedSearch->SearchValue2 = @$filter["y_document_checklist"];
		$this->document_checklist->AdvancedSearch->SearchOperator2 = @$filter["w_document_checklist"];
		$this->document_checklist->AdvancedSearch->Save();

		// Field recommender_action
		$this->recommender_action->AdvancedSearch->SearchValue = @$filter["x_recommender_action"];
		$this->recommender_action->AdvancedSearch->SearchOperator = @$filter["z_recommender_action"];
		$this->recommender_action->AdvancedSearch->SearchCondition = @$filter["v_recommender_action"];
		$this->recommender_action->AdvancedSearch->SearchValue2 = @$filter["y_recommender_action"];
		$this->recommender_action->AdvancedSearch->SearchOperator2 = @$filter["w_recommender_action"];
		$this->recommender_action->AdvancedSearch->Save();

		// Field recommender_comment
		$this->recommender_comment->AdvancedSearch->SearchValue = @$filter["x_recommender_comment"];
		$this->recommender_comment->AdvancedSearch->SearchOperator = @$filter["z_recommender_comment"];
		$this->recommender_comment->AdvancedSearch->SearchCondition = @$filter["v_recommender_comment"];
		$this->recommender_comment->AdvancedSearch->SearchValue2 = @$filter["y_recommender_comment"];
		$this->recommender_comment->AdvancedSearch->SearchOperator2 = @$filter["w_recommender_comment"];
		$this->recommender_comment->AdvancedSearch->Save();

		// Field recommended_by
		$this->recommended_by->AdvancedSearch->SearchValue = @$filter["x_recommended_by"];
		$this->recommended_by->AdvancedSearch->SearchOperator = @$filter["z_recommended_by"];
		$this->recommended_by->AdvancedSearch->SearchCondition = @$filter["v_recommended_by"];
		$this->recommended_by->AdvancedSearch->SearchValue2 = @$filter["y_recommended_by"];
		$this->recommended_by->AdvancedSearch->SearchOperator2 = @$filter["w_recommended_by"];
		$this->recommended_by->AdvancedSearch->Save();

		// Field application_status
		$this->application_status->AdvancedSearch->SearchValue = @$filter["x_application_status"];
		$this->application_status->AdvancedSearch->SearchOperator = @$filter["z_application_status"];
		$this->application_status->AdvancedSearch->SearchCondition = @$filter["v_application_status"];
		$this->application_status->AdvancedSearch->SearchValue2 = @$filter["y_application_status"];
		$this->application_status->AdvancedSearch->SearchOperator2 = @$filter["w_application_status"];
		$this->application_status->AdvancedSearch->Save();

		// Field approved_amount
		$this->approved_amount->AdvancedSearch->SearchValue = @$filter["x_approved_amount"];
		$this->approved_amount->AdvancedSearch->SearchOperator = @$filter["z_approved_amount"];
		$this->approved_amount->AdvancedSearch->SearchCondition = @$filter["v_approved_amount"];
		$this->approved_amount->AdvancedSearch->SearchValue2 = @$filter["y_approved_amount"];
		$this->approved_amount->AdvancedSearch->SearchOperator2 = @$filter["w_approved_amount"];
		$this->approved_amount->AdvancedSearch->Save();

		// Field duration_approved
		$this->duration_approved->AdvancedSearch->SearchValue = @$filter["x_duration_approved"];
		$this->duration_approved->AdvancedSearch->SearchOperator = @$filter["z_duration_approved"];
		$this->duration_approved->AdvancedSearch->SearchCondition = @$filter["v_duration_approved"];
		$this->duration_approved->AdvancedSearch->SearchValue2 = @$filter["y_duration_approved"];
		$this->duration_approved->AdvancedSearch->SearchOperator2 = @$filter["w_duration_approved"];
		$this->duration_approved->AdvancedSearch->Save();

		// Field approval_date
		$this->approval_date->AdvancedSearch->SearchValue = @$filter["x_approval_date"];
		$this->approval_date->AdvancedSearch->SearchOperator = @$filter["z_approval_date"];
		$this->approval_date->AdvancedSearch->SearchCondition = @$filter["v_approval_date"];
		$this->approval_date->AdvancedSearch->SearchValue2 = @$filter["y_approval_date"];
		$this->approval_date->AdvancedSearch->SearchOperator2 = @$filter["w_approval_date"];
		$this->approval_date->AdvancedSearch->Save();

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

		// Field approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$filter["x_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator = @$filter["z_approved_by"];
		$this->approved_by->AdvancedSearch->SearchCondition = @$filter["v_approved_by"];
		$this->approved_by->AdvancedSearch->SearchValue2 = @$filter["y_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator2 = @$filter["w_approved_by"];
		$this->approved_by->AdvancedSearch->Save();

		// Field correction_date
		$this->correction_date->AdvancedSearch->SearchValue = @$filter["x_correction_date"];
		$this->correction_date->AdvancedSearch->SearchOperator = @$filter["z_correction_date"];
		$this->correction_date->AdvancedSearch->SearchCondition = @$filter["v_correction_date"];
		$this->correction_date->AdvancedSearch->SearchValue2 = @$filter["y_correction_date"];
		$this->correction_date->AdvancedSearch->SearchOperator2 = @$filter["w_correction_date"];
		$this->correction_date->AdvancedSearch->Save();

		// Field correction_action
		$this->correction_action->AdvancedSearch->SearchValue = @$filter["x_correction_action"];
		$this->correction_action->AdvancedSearch->SearchOperator = @$filter["z_correction_action"];
		$this->correction_action->AdvancedSearch->SearchCondition = @$filter["v_correction_action"];
		$this->correction_action->AdvancedSearch->SearchValue2 = @$filter["y_correction_action"];
		$this->correction_action->AdvancedSearch->SearchOperator2 = @$filter["w_correction_action"];
		$this->correction_action->AdvancedSearch->Save();

		// Field correction_comment
		$this->correction_comment->AdvancedSearch->SearchValue = @$filter["x_correction_comment"];
		$this->correction_comment->AdvancedSearch->SearchOperator = @$filter["z_correction_comment"];
		$this->correction_comment->AdvancedSearch->SearchCondition = @$filter["v_correction_comment"];
		$this->correction_comment->AdvancedSearch->SearchValue2 = @$filter["y_correction_comment"];
		$this->correction_comment->AdvancedSearch->SearchOperator2 = @$filter["w_correction_comment"];
		$this->correction_comment->AdvancedSearch->Save();

		// Field corrected_by
		$this->corrected_by->AdvancedSearch->SearchValue = @$filter["x_corrected_by"];
		$this->corrected_by->AdvancedSearch->SearchOperator = @$filter["z_corrected_by"];
		$this->corrected_by->AdvancedSearch->SearchCondition = @$filter["v_corrected_by"];
		$this->corrected_by->AdvancedSearch->SearchValue2 = @$filter["y_corrected_by"];
		$this->corrected_by->AdvancedSearch->SearchOperator2 = @$filter["w_corrected_by"];
		$this->corrected_by->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->code, $Default, FALSE); // code
		$this->BuildSearchSql($sWhere, $this->date_initiated, $Default, FALSE); // date_initiated
		$this->BuildSearchSql($sWhere, $this->refernce_id, $Default, FALSE); // refernce_id
		$this->BuildSearchSql($sWhere, $this->employee_name, $Default, FALSE); // employee_name
		$this->BuildSearchSql($sWhere, $this->address, $Default, FALSE); // address
		$this->BuildSearchSql($sWhere, $this->mobile, $Default, FALSE); // mobile
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->pension, $Default, FALSE); // pension
		$this->BuildSearchSql($sWhere, $this->loan_amount, $Default, FALSE); // loan_amount
		$this->BuildSearchSql($sWhere, $this->amount_inwords, $Default, FALSE); // amount_inwords
		$this->BuildSearchSql($sWhere, $this->purpose, $Default, FALSE); // purpose
		$this->BuildSearchSql($sWhere, $this->repayment_period, $Default, FALSE); // repayment_period
		$this->BuildSearchSql($sWhere, $this->salary_permonth, $Default, FALSE); // salary_permonth
		$this->BuildSearchSql($sWhere, $this->previous_loan, $Default, FALSE); // previous_loan
		$this->BuildSearchSql($sWhere, $this->date_collected, $Default, FALSE); // date_collected
		$this->BuildSearchSql($sWhere, $this->date_liquidated, $Default, FALSE); // date_liquidated
		$this->BuildSearchSql($sWhere, $this->balance_remaining, $Default, FALSE); // balance_remaining
		$this->BuildSearchSql($sWhere, $this->applicant_date, $Default, FALSE); // applicant_date
		$this->BuildSearchSql($sWhere, $this->applicant_passport, $Default, FALSE); // applicant_passport
		$this->BuildSearchSql($sWhere, $this->guarantor_name, $Default, FALSE); // guarantor_name
		$this->BuildSearchSql($sWhere, $this->guarantor_address, $Default, FALSE); // guarantor_address
		$this->BuildSearchSql($sWhere, $this->guarantor_mobile, $Default, FALSE); // guarantor_mobile
		$this->BuildSearchSql($sWhere, $this->guarantor_department, $Default, FALSE); // guarantor_department
		$this->BuildSearchSql($sWhere, $this->account_no, $Default, FALSE); // account_no
		$this->BuildSearchSql($sWhere, $this->bank_name, $Default, FALSE); // bank_name
		$this->BuildSearchSql($sWhere, $this->employers_name, $Default, FALSE); // employers_name
		$this->BuildSearchSql($sWhere, $this->employers_address, $Default, FALSE); // employers_address
		$this->BuildSearchSql($sWhere, $this->employers_mobile, $Default, FALSE); // employers_mobile
		$this->BuildSearchSql($sWhere, $this->guarantor_date, $Default, FALSE); // guarantor_date
		$this->BuildSearchSql($sWhere, $this->guarantor_passport, $Default, FALSE); // guarantor_passport
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->initiator_action, $Default, FALSE); // initiator_action
		$this->BuildSearchSql($sWhere, $this->initiator_comment, $Default, FALSE); // initiator_comment
		$this->BuildSearchSql($sWhere, $this->recommended_date, $Default, FALSE); // recommended_date
		$this->BuildSearchSql($sWhere, $this->document_checklist, $Default, TRUE); // document_checklist
		$this->BuildSearchSql($sWhere, $this->recommender_action, $Default, FALSE); // recommender_action
		$this->BuildSearchSql($sWhere, $this->recommender_comment, $Default, FALSE); // recommender_comment
		$this->BuildSearchSql($sWhere, $this->recommended_by, $Default, FALSE); // recommended_by
		$this->BuildSearchSql($sWhere, $this->application_status, $Default, FALSE); // application_status
		$this->BuildSearchSql($sWhere, $this->approved_amount, $Default, FALSE); // approved_amount
		$this->BuildSearchSql($sWhere, $this->duration_approved, $Default, FALSE); // duration_approved
		$this->BuildSearchSql($sWhere, $this->approval_date, $Default, FALSE); // approval_date
		$this->BuildSearchSql($sWhere, $this->approval_action, $Default, FALSE); // approval_action
		$this->BuildSearchSql($sWhere, $this->approval_comment, $Default, FALSE); // approval_comment
		$this->BuildSearchSql($sWhere, $this->approved_by, $Default, FALSE); // approved_by
		$this->BuildSearchSql($sWhere, $this->correction_date, $Default, FALSE); // correction_date
		$this->BuildSearchSql($sWhere, $this->correction_action, $Default, FALSE); // correction_action
		$this->BuildSearchSql($sWhere, $this->correction_comment, $Default, FALSE); // correction_comment
		$this->BuildSearchSql($sWhere, $this->corrected_by, $Default, FALSE); // corrected_by

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->code->AdvancedSearch->Save(); // code
			$this->date_initiated->AdvancedSearch->Save(); // date_initiated
			$this->refernce_id->AdvancedSearch->Save(); // refernce_id
			$this->employee_name->AdvancedSearch->Save(); // employee_name
			$this->address->AdvancedSearch->Save(); // address
			$this->mobile->AdvancedSearch->Save(); // mobile
			$this->department->AdvancedSearch->Save(); // department
			$this->pension->AdvancedSearch->Save(); // pension
			$this->loan_amount->AdvancedSearch->Save(); // loan_amount
			$this->amount_inwords->AdvancedSearch->Save(); // amount_inwords
			$this->purpose->AdvancedSearch->Save(); // purpose
			$this->repayment_period->AdvancedSearch->Save(); // repayment_period
			$this->salary_permonth->AdvancedSearch->Save(); // salary_permonth
			$this->previous_loan->AdvancedSearch->Save(); // previous_loan
			$this->date_collected->AdvancedSearch->Save(); // date_collected
			$this->date_liquidated->AdvancedSearch->Save(); // date_liquidated
			$this->balance_remaining->AdvancedSearch->Save(); // balance_remaining
			$this->applicant_date->AdvancedSearch->Save(); // applicant_date
			$this->applicant_passport->AdvancedSearch->Save(); // applicant_passport
			$this->guarantor_name->AdvancedSearch->Save(); // guarantor_name
			$this->guarantor_address->AdvancedSearch->Save(); // guarantor_address
			$this->guarantor_mobile->AdvancedSearch->Save(); // guarantor_mobile
			$this->guarantor_department->AdvancedSearch->Save(); // guarantor_department
			$this->account_no->AdvancedSearch->Save(); // account_no
			$this->bank_name->AdvancedSearch->Save(); // bank_name
			$this->employers_name->AdvancedSearch->Save(); // employers_name
			$this->employers_address->AdvancedSearch->Save(); // employers_address
			$this->employers_mobile->AdvancedSearch->Save(); // employers_mobile
			$this->guarantor_date->AdvancedSearch->Save(); // guarantor_date
			$this->guarantor_passport->AdvancedSearch->Save(); // guarantor_passport
			$this->status->AdvancedSearch->Save(); // status
			$this->initiator_action->AdvancedSearch->Save(); // initiator_action
			$this->initiator_comment->AdvancedSearch->Save(); // initiator_comment
			$this->recommended_date->AdvancedSearch->Save(); // recommended_date
			$this->document_checklist->AdvancedSearch->Save(); // document_checklist
			$this->recommender_action->AdvancedSearch->Save(); // recommender_action
			$this->recommender_comment->AdvancedSearch->Save(); // recommender_comment
			$this->recommended_by->AdvancedSearch->Save(); // recommended_by
			$this->application_status->AdvancedSearch->Save(); // application_status
			$this->approved_amount->AdvancedSearch->Save(); // approved_amount
			$this->duration_approved->AdvancedSearch->Save(); // duration_approved
			$this->approval_date->AdvancedSearch->Save(); // approval_date
			$this->approval_action->AdvancedSearch->Save(); // approval_action
			$this->approval_comment->AdvancedSearch->Save(); // approval_comment
			$this->approved_by->AdvancedSearch->Save(); // approved_by
			$this->correction_date->AdvancedSearch->Save(); // correction_date
			$this->correction_action->AdvancedSearch->Save(); // correction_action
			$this->correction_comment->AdvancedSearch->Save(); // correction_comment
			$this->corrected_by->AdvancedSearch->Save(); // corrected_by
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
		$this->BuildBasicSearchSQL($sWhere, $this->refernce_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->employee_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pension, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->amount_inwords, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->purpose, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->repayment_period, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->applicant_passport, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->guarantor_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->guarantor_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->guarantor_mobile, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->account_no, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->employers_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->employers_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->employers_mobile, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->guarantor_passport, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->initiator_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->document_checklist, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->recommender_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->application_status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approval_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->correction_comment, $arKeywords, $type);
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
		if ($this->date_initiated->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->refernce_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->employee_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mobile->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pension->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->loan_amount->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->amount_inwords->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->purpose->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->repayment_period->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->salary_permonth->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->previous_loan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_collected->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_liquidated->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->balance_remaining->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->applicant_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->applicant_passport->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->guarantor_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->guarantor_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->guarantor_mobile->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->guarantor_department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->account_no->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bank_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->employers_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->employers_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->employers_mobile->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->guarantor_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->guarantor_passport->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recommended_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->document_checklist->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recommender_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recommender_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->recommended_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->application_status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approved_amount->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->duration_approved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->correction_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->correction_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->correction_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->corrected_by->AdvancedSearch->IssetSession())
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
		$this->code->AdvancedSearch->UnsetSession();
		$this->date_initiated->AdvancedSearch->UnsetSession();
		$this->refernce_id->AdvancedSearch->UnsetSession();
		$this->employee_name->AdvancedSearch->UnsetSession();
		$this->address->AdvancedSearch->UnsetSession();
		$this->mobile->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->pension->AdvancedSearch->UnsetSession();
		$this->loan_amount->AdvancedSearch->UnsetSession();
		$this->amount_inwords->AdvancedSearch->UnsetSession();
		$this->purpose->AdvancedSearch->UnsetSession();
		$this->repayment_period->AdvancedSearch->UnsetSession();
		$this->salary_permonth->AdvancedSearch->UnsetSession();
		$this->previous_loan->AdvancedSearch->UnsetSession();
		$this->date_collected->AdvancedSearch->UnsetSession();
		$this->date_liquidated->AdvancedSearch->UnsetSession();
		$this->balance_remaining->AdvancedSearch->UnsetSession();
		$this->applicant_date->AdvancedSearch->UnsetSession();
		$this->applicant_passport->AdvancedSearch->UnsetSession();
		$this->guarantor_name->AdvancedSearch->UnsetSession();
		$this->guarantor_address->AdvancedSearch->UnsetSession();
		$this->guarantor_mobile->AdvancedSearch->UnsetSession();
		$this->guarantor_department->AdvancedSearch->UnsetSession();
		$this->account_no->AdvancedSearch->UnsetSession();
		$this->bank_name->AdvancedSearch->UnsetSession();
		$this->employers_name->AdvancedSearch->UnsetSession();
		$this->employers_address->AdvancedSearch->UnsetSession();
		$this->employers_mobile->AdvancedSearch->UnsetSession();
		$this->guarantor_date->AdvancedSearch->UnsetSession();
		$this->guarantor_passport->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->initiator_action->AdvancedSearch->UnsetSession();
		$this->initiator_comment->AdvancedSearch->UnsetSession();
		$this->recommended_date->AdvancedSearch->UnsetSession();
		$this->document_checklist->AdvancedSearch->UnsetSession();
		$this->recommender_action->AdvancedSearch->UnsetSession();
		$this->recommender_comment->AdvancedSearch->UnsetSession();
		$this->recommended_by->AdvancedSearch->UnsetSession();
		$this->application_status->AdvancedSearch->UnsetSession();
		$this->approved_amount->AdvancedSearch->UnsetSession();
		$this->duration_approved->AdvancedSearch->UnsetSession();
		$this->approval_date->AdvancedSearch->UnsetSession();
		$this->approval_action->AdvancedSearch->UnsetSession();
		$this->approval_comment->AdvancedSearch->UnsetSession();
		$this->approved_by->AdvancedSearch->UnsetSession();
		$this->correction_date->AdvancedSearch->UnsetSession();
		$this->correction_action->AdvancedSearch->UnsetSession();
		$this->correction_comment->AdvancedSearch->UnsetSession();
		$this->corrected_by->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->code->AdvancedSearch->Load();
		$this->date_initiated->AdvancedSearch->Load();
		$this->refernce_id->AdvancedSearch->Load();
		$this->employee_name->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->mobile->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->pension->AdvancedSearch->Load();
		$this->loan_amount->AdvancedSearch->Load();
		$this->amount_inwords->AdvancedSearch->Load();
		$this->purpose->AdvancedSearch->Load();
		$this->repayment_period->AdvancedSearch->Load();
		$this->salary_permonth->AdvancedSearch->Load();
		$this->previous_loan->AdvancedSearch->Load();
		$this->date_collected->AdvancedSearch->Load();
		$this->date_liquidated->AdvancedSearch->Load();
		$this->balance_remaining->AdvancedSearch->Load();
		$this->applicant_date->AdvancedSearch->Load();
		$this->applicant_passport->AdvancedSearch->Load();
		$this->guarantor_name->AdvancedSearch->Load();
		$this->guarantor_address->AdvancedSearch->Load();
		$this->guarantor_mobile->AdvancedSearch->Load();
		$this->guarantor_department->AdvancedSearch->Load();
		$this->account_no->AdvancedSearch->Load();
		$this->bank_name->AdvancedSearch->Load();
		$this->employers_name->AdvancedSearch->Load();
		$this->employers_address->AdvancedSearch->Load();
		$this->employers_mobile->AdvancedSearch->Load();
		$this->guarantor_date->AdvancedSearch->Load();
		$this->guarantor_passport->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->recommended_date->AdvancedSearch->Load();
		$this->document_checklist->AdvancedSearch->Load();
		$this->recommender_action->AdvancedSearch->Load();
		$this->recommender_comment->AdvancedSearch->Load();
		$this->recommended_by->AdvancedSearch->Load();
		$this->application_status->AdvancedSearch->Load();
		$this->approved_amount->AdvancedSearch->Load();
		$this->duration_approved->AdvancedSearch->Load();
		$this->approval_date->AdvancedSearch->Load();
		$this->approval_action->AdvancedSearch->Load();
		$this->approval_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->correction_date->AdvancedSearch->Load();
		$this->correction_action->AdvancedSearch->Load();
		$this->correction_comment->AdvancedSearch->Load();
		$this->corrected_by->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->date_initiated); // date_initiated
			$this->UpdateSort($this->refernce_id); // refernce_id
			$this->UpdateSort($this->employee_name); // employee_name
			$this->UpdateSort($this->address); // address
			$this->UpdateSort($this->mobile); // mobile
			$this->UpdateSort($this->department); // department
			$this->UpdateSort($this->pension); // pension
			$this->UpdateSort($this->loan_amount); // loan_amount
			$this->UpdateSort($this->amount_inwords); // amount_inwords
			$this->UpdateSort($this->repayment_period); // repayment_period
			$this->UpdateSort($this->salary_permonth); // salary_permonth
			$this->UpdateSort($this->previous_loan); // previous_loan
			$this->UpdateSort($this->date_collected); // date_collected
			$this->UpdateSort($this->date_liquidated); // date_liquidated
			$this->UpdateSort($this->balance_remaining); // balance_remaining
			$this->UpdateSort($this->applicant_date); // applicant_date
			$this->UpdateSort($this->guarantor_name); // guarantor_name
			$this->UpdateSort($this->guarantor_address); // guarantor_address
			$this->UpdateSort($this->guarantor_mobile); // guarantor_mobile
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->application_status); // application_status
			$this->UpdateSort($this->correction_date); // correction_date
			$this->UpdateSort($this->correction_action); // correction_action
			$this->UpdateSort($this->correction_comment); // correction_comment
			$this->UpdateSort($this->corrected_by); // corrected_by
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
				$this->refernce_id->setSort("");
				$this->employee_name->setSort("");
				$this->address->setSort("");
				$this->mobile->setSort("");
				$this->department->setSort("");
				$this->pension->setSort("");
				$this->loan_amount->setSort("");
				$this->amount_inwords->setSort("");
				$this->repayment_period->setSort("");
				$this->salary_permonth->setSort("");
				$this->previous_loan->setSort("");
				$this->date_collected->setSort("");
				$this->date_liquidated->setSort("");
				$this->balance_remaining->setSort("");
				$this->applicant_date->setSort("");
				$this->guarantor_name->setSort("");
				$this->guarantor_address->setSort("");
				$this->guarantor_mobile->setSort("");
				$this->status->setSort("");
				$this->application_status->setSort("");
				$this->correction_date->setSort("");
				$this->correction_action->setSort("");
				$this->correction_comment->setSort("");
				$this->corrected_by->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"loan_application\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"floan_applicationlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"floan_applicationlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.floan_applicationlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"floan_applicationlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// code

		$this->code->AdvancedSearch->SearchValue = @$_GET["x_code"];
		if ($this->code->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->code->AdvancedSearch->SearchOperator = @$_GET["z_code"];

		// date_initiated
		$this->date_initiated->AdvancedSearch->SearchValue = @$_GET["x_date_initiated"];
		if ($this->date_initiated->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_initiated->AdvancedSearch->SearchOperator = @$_GET["z_date_initiated"];

		// refernce_id
		$this->refernce_id->AdvancedSearch->SearchValue = @$_GET["x_refernce_id"];
		if ($this->refernce_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->refernce_id->AdvancedSearch->SearchOperator = @$_GET["z_refernce_id"];

		// employee_name
		$this->employee_name->AdvancedSearch->SearchValue = @$_GET["x_employee_name"];
		if ($this->employee_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->employee_name->AdvancedSearch->SearchOperator = @$_GET["z_employee_name"];

		// address
		$this->address->AdvancedSearch->SearchValue = @$_GET["x_address"];
		if ($this->address->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->address->AdvancedSearch->SearchOperator = @$_GET["z_address"];

		// mobile
		$this->mobile->AdvancedSearch->SearchValue = @$_GET["x_mobile"];
		if ($this->mobile->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mobile->AdvancedSearch->SearchOperator = @$_GET["z_mobile"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// pension
		$this->pension->AdvancedSearch->SearchValue = @$_GET["x_pension"];
		if ($this->pension->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->pension->AdvancedSearch->SearchOperator = @$_GET["z_pension"];

		// loan_amount
		$this->loan_amount->AdvancedSearch->SearchValue = @$_GET["x_loan_amount"];
		if ($this->loan_amount->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->loan_amount->AdvancedSearch->SearchOperator = @$_GET["z_loan_amount"];

		// amount_inwords
		$this->amount_inwords->AdvancedSearch->SearchValue = @$_GET["x_amount_inwords"];
		if ($this->amount_inwords->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->amount_inwords->AdvancedSearch->SearchOperator = @$_GET["z_amount_inwords"];

		// purpose
		$this->purpose->AdvancedSearch->SearchValue = @$_GET["x_purpose"];
		if ($this->purpose->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->purpose->AdvancedSearch->SearchOperator = @$_GET["z_purpose"];

		// repayment_period
		$this->repayment_period->AdvancedSearch->SearchValue = @$_GET["x_repayment_period"];
		if ($this->repayment_period->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->repayment_period->AdvancedSearch->SearchOperator = @$_GET["z_repayment_period"];

		// salary_permonth
		$this->salary_permonth->AdvancedSearch->SearchValue = @$_GET["x_salary_permonth"];
		if ($this->salary_permonth->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->salary_permonth->AdvancedSearch->SearchOperator = @$_GET["z_salary_permonth"];

		// previous_loan
		$this->previous_loan->AdvancedSearch->SearchValue = @$_GET["x_previous_loan"];
		if ($this->previous_loan->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->previous_loan->AdvancedSearch->SearchOperator = @$_GET["z_previous_loan"];

		// date_collected
		$this->date_collected->AdvancedSearch->SearchValue = @$_GET["x_date_collected"];
		if ($this->date_collected->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_collected->AdvancedSearch->SearchOperator = @$_GET["z_date_collected"];

		// date_liquidated
		$this->date_liquidated->AdvancedSearch->SearchValue = @$_GET["x_date_liquidated"];
		if ($this->date_liquidated->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_liquidated->AdvancedSearch->SearchOperator = @$_GET["z_date_liquidated"];

		// balance_remaining
		$this->balance_remaining->AdvancedSearch->SearchValue = @$_GET["x_balance_remaining"];
		if ($this->balance_remaining->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->balance_remaining->AdvancedSearch->SearchOperator = @$_GET["z_balance_remaining"];

		// applicant_date
		$this->applicant_date->AdvancedSearch->SearchValue = @$_GET["x_applicant_date"];
		if ($this->applicant_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->applicant_date->AdvancedSearch->SearchOperator = @$_GET["z_applicant_date"];

		// applicant_passport
		$this->applicant_passport->AdvancedSearch->SearchValue = @$_GET["x_applicant_passport"];
		if ($this->applicant_passport->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->applicant_passport->AdvancedSearch->SearchOperator = @$_GET["z_applicant_passport"];

		// guarantor_name
		$this->guarantor_name->AdvancedSearch->SearchValue = @$_GET["x_guarantor_name"];
		if ($this->guarantor_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->guarantor_name->AdvancedSearch->SearchOperator = @$_GET["z_guarantor_name"];

		// guarantor_address
		$this->guarantor_address->AdvancedSearch->SearchValue = @$_GET["x_guarantor_address"];
		if ($this->guarantor_address->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->guarantor_address->AdvancedSearch->SearchOperator = @$_GET["z_guarantor_address"];

		// guarantor_mobile
		$this->guarantor_mobile->AdvancedSearch->SearchValue = @$_GET["x_guarantor_mobile"];
		if ($this->guarantor_mobile->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->guarantor_mobile->AdvancedSearch->SearchOperator = @$_GET["z_guarantor_mobile"];

		// guarantor_department
		$this->guarantor_department->AdvancedSearch->SearchValue = @$_GET["x_guarantor_department"];
		if ($this->guarantor_department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->guarantor_department->AdvancedSearch->SearchOperator = @$_GET["z_guarantor_department"];

		// account_no
		$this->account_no->AdvancedSearch->SearchValue = @$_GET["x_account_no"];
		if ($this->account_no->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->account_no->AdvancedSearch->SearchOperator = @$_GET["z_account_no"];

		// bank_name
		$this->bank_name->AdvancedSearch->SearchValue = @$_GET["x_bank_name"];
		if ($this->bank_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->bank_name->AdvancedSearch->SearchOperator = @$_GET["z_bank_name"];

		// employers_name
		$this->employers_name->AdvancedSearch->SearchValue = @$_GET["x_employers_name"];
		if ($this->employers_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->employers_name->AdvancedSearch->SearchOperator = @$_GET["z_employers_name"];

		// employers_address
		$this->employers_address->AdvancedSearch->SearchValue = @$_GET["x_employers_address"];
		if ($this->employers_address->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->employers_address->AdvancedSearch->SearchOperator = @$_GET["z_employers_address"];

		// employers_mobile
		$this->employers_mobile->AdvancedSearch->SearchValue = @$_GET["x_employers_mobile"];
		if ($this->employers_mobile->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->employers_mobile->AdvancedSearch->SearchOperator = @$_GET["z_employers_mobile"];

		// guarantor_date
		$this->guarantor_date->AdvancedSearch->SearchValue = @$_GET["x_guarantor_date"];
		if ($this->guarantor_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->guarantor_date->AdvancedSearch->SearchOperator = @$_GET["z_guarantor_date"];

		// guarantor_passport
		$this->guarantor_passport->AdvancedSearch->SearchValue = @$_GET["x_guarantor_passport"];
		if ($this->guarantor_passport->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->guarantor_passport->AdvancedSearch->SearchOperator = @$_GET["z_guarantor_passport"];

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

		// recommended_date
		$this->recommended_date->AdvancedSearch->SearchValue = @$_GET["x_recommended_date"];
		if ($this->recommended_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recommended_date->AdvancedSearch->SearchOperator = @$_GET["z_recommended_date"];

		// document_checklist
		$this->document_checklist->AdvancedSearch->SearchValue = @$_GET["x_document_checklist"];
		if ($this->document_checklist->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->document_checklist->AdvancedSearch->SearchOperator = @$_GET["z_document_checklist"];
		if (is_array($this->document_checklist->AdvancedSearch->SearchValue)) $this->document_checklist->AdvancedSearch->SearchValue = implode(",", $this->document_checklist->AdvancedSearch->SearchValue);
		if (is_array($this->document_checklist->AdvancedSearch->SearchValue2)) $this->document_checklist->AdvancedSearch->SearchValue2 = implode(",", $this->document_checklist->AdvancedSearch->SearchValue2);

		// recommender_action
		$this->recommender_action->AdvancedSearch->SearchValue = @$_GET["x_recommender_action"];
		if ($this->recommender_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recommender_action->AdvancedSearch->SearchOperator = @$_GET["z_recommender_action"];

		// recommender_comment
		$this->recommender_comment->AdvancedSearch->SearchValue = @$_GET["x_recommender_comment"];
		if ($this->recommender_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recommender_comment->AdvancedSearch->SearchOperator = @$_GET["z_recommender_comment"];

		// recommended_by
		$this->recommended_by->AdvancedSearch->SearchValue = @$_GET["x_recommended_by"];
		if ($this->recommended_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->recommended_by->AdvancedSearch->SearchOperator = @$_GET["z_recommended_by"];

		// application_status
		$this->application_status->AdvancedSearch->SearchValue = @$_GET["x_application_status"];
		if ($this->application_status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->application_status->AdvancedSearch->SearchOperator = @$_GET["z_application_status"];

		// approved_amount
		$this->approved_amount->AdvancedSearch->SearchValue = @$_GET["x_approved_amount"];
		if ($this->approved_amount->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approved_amount->AdvancedSearch->SearchOperator = @$_GET["z_approved_amount"];

		// duration_approved
		$this->duration_approved->AdvancedSearch->SearchValue = @$_GET["x_duration_approved"];
		if ($this->duration_approved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->duration_approved->AdvancedSearch->SearchOperator = @$_GET["z_duration_approved"];

		// approval_date
		$this->approval_date->AdvancedSearch->SearchValue = @$_GET["x_approval_date"];
		if ($this->approval_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_date->AdvancedSearch->SearchOperator = @$_GET["z_approval_date"];

		// approval_action
		$this->approval_action->AdvancedSearch->SearchValue = @$_GET["x_approval_action"];
		if ($this->approval_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_action->AdvancedSearch->SearchOperator = @$_GET["z_approval_action"];

		// approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = @$_GET["x_approval_comment"];
		if ($this->approval_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_comment->AdvancedSearch->SearchOperator = @$_GET["z_approval_comment"];

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$_GET["x_approved_by"];
		if ($this->approved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approved_by->AdvancedSearch->SearchOperator = @$_GET["z_approved_by"];

		// correction_date
		$this->correction_date->AdvancedSearch->SearchValue = @$_GET["x_correction_date"];
		if ($this->correction_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->correction_date->AdvancedSearch->SearchOperator = @$_GET["z_correction_date"];

		// correction_action
		$this->correction_action->AdvancedSearch->SearchValue = @$_GET["x_correction_action"];
		if ($this->correction_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->correction_action->AdvancedSearch->SearchOperator = @$_GET["z_correction_action"];

		// correction_comment
		$this->correction_comment->AdvancedSearch->SearchValue = @$_GET["x_correction_comment"];
		if ($this->correction_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->correction_comment->AdvancedSearch->SearchOperator = @$_GET["z_correction_comment"];

		// corrected_by
		$this->corrected_by->AdvancedSearch->SearchValue = @$_GET["x_corrected_by"];
		if ($this->corrected_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->corrected_by->AdvancedSearch->SearchOperator = @$_GET["z_corrected_by"];
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
		$this->date_initiated->setDbValue($row['date_initiated']);
		$this->refernce_id->setDbValue($row['refernce_id']);
		$this->employee_name->setDbValue($row['employee_name']);
		$this->address->setDbValue($row['address']);
		$this->mobile->setDbValue($row['mobile']);
		$this->department->setDbValue($row['department']);
		$this->pension->setDbValue($row['pension']);
		$this->loan_amount->setDbValue($row['loan_amount']);
		$this->amount_inwords->setDbValue($row['amount_inwords']);
		$this->purpose->setDbValue($row['purpose']);
		$this->repayment_period->setDbValue($row['repayment_period']);
		$this->salary_permonth->setDbValue($row['salary_permonth']);
		$this->previous_loan->setDbValue($row['previous_loan']);
		$this->date_collected->setDbValue($row['date_collected']);
		$this->date_liquidated->setDbValue($row['date_liquidated']);
		$this->balance_remaining->setDbValue($row['balance_remaining']);
		$this->applicant_date->setDbValue($row['applicant_date']);
		$this->applicant_passport->Upload->DbValue = $row['applicant_passport'];
		$this->applicant_passport->setDbValue($this->applicant_passport->Upload->DbValue);
		$this->guarantor_name->setDbValue($row['guarantor_name']);
		$this->guarantor_address->setDbValue($row['guarantor_address']);
		$this->guarantor_mobile->setDbValue($row['guarantor_mobile']);
		$this->guarantor_department->setDbValue($row['guarantor_department']);
		$this->account_no->setDbValue($row['account_no']);
		$this->bank_name->setDbValue($row['bank_name']);
		$this->employers_name->setDbValue($row['employers_name']);
		$this->employers_address->setDbValue($row['employers_address']);
		$this->employers_mobile->setDbValue($row['employers_mobile']);
		$this->guarantor_date->setDbValue($row['guarantor_date']);
		$this->guarantor_passport->Upload->DbValue = $row['guarantor_passport'];
		$this->guarantor_passport->setDbValue($this->guarantor_passport->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->recommended_date->setDbValue($row['recommended_date']);
		$this->document_checklist->setDbValue($row['document_checklist']);
		$this->recommender_action->setDbValue($row['recommender_action']);
		$this->recommender_comment->setDbValue($row['recommender_comment']);
		$this->recommended_by->setDbValue($row['recommended_by']);
		$this->application_status->setDbValue($row['application_status']);
		$this->approved_amount->setDbValue($row['approved_amount']);
		$this->duration_approved->setDbValue($row['duration_approved']);
		$this->approval_date->setDbValue($row['approval_date']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->correction_date->setDbValue($row['correction_date']);
		$this->correction_action->setDbValue($row['correction_action']);
		$this->correction_comment->setDbValue($row['correction_comment']);
		$this->corrected_by->setDbValue($row['corrected_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['date_initiated'] = NULL;
		$row['refernce_id'] = NULL;
		$row['employee_name'] = NULL;
		$row['address'] = NULL;
		$row['mobile'] = NULL;
		$row['department'] = NULL;
		$row['pension'] = NULL;
		$row['loan_amount'] = NULL;
		$row['amount_inwords'] = NULL;
		$row['purpose'] = NULL;
		$row['repayment_period'] = NULL;
		$row['salary_permonth'] = NULL;
		$row['previous_loan'] = NULL;
		$row['date_collected'] = NULL;
		$row['date_liquidated'] = NULL;
		$row['balance_remaining'] = NULL;
		$row['applicant_date'] = NULL;
		$row['applicant_passport'] = NULL;
		$row['guarantor_name'] = NULL;
		$row['guarantor_address'] = NULL;
		$row['guarantor_mobile'] = NULL;
		$row['guarantor_department'] = NULL;
		$row['account_no'] = NULL;
		$row['bank_name'] = NULL;
		$row['employers_name'] = NULL;
		$row['employers_address'] = NULL;
		$row['employers_mobile'] = NULL;
		$row['guarantor_date'] = NULL;
		$row['guarantor_passport'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['recommended_date'] = NULL;
		$row['document_checklist'] = NULL;
		$row['recommender_action'] = NULL;
		$row['recommender_comment'] = NULL;
		$row['recommended_by'] = NULL;
		$row['application_status'] = NULL;
		$row['approved_amount'] = NULL;
		$row['duration_approved'] = NULL;
		$row['approval_date'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['correction_date'] = NULL;
		$row['correction_action'] = NULL;
		$row['correction_comment'] = NULL;
		$row['corrected_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->refernce_id->DbValue = $row['refernce_id'];
		$this->employee_name->DbValue = $row['employee_name'];
		$this->address->DbValue = $row['address'];
		$this->mobile->DbValue = $row['mobile'];
		$this->department->DbValue = $row['department'];
		$this->pension->DbValue = $row['pension'];
		$this->loan_amount->DbValue = $row['loan_amount'];
		$this->amount_inwords->DbValue = $row['amount_inwords'];
		$this->purpose->DbValue = $row['purpose'];
		$this->repayment_period->DbValue = $row['repayment_period'];
		$this->salary_permonth->DbValue = $row['salary_permonth'];
		$this->previous_loan->DbValue = $row['previous_loan'];
		$this->date_collected->DbValue = $row['date_collected'];
		$this->date_liquidated->DbValue = $row['date_liquidated'];
		$this->balance_remaining->DbValue = $row['balance_remaining'];
		$this->applicant_date->DbValue = $row['applicant_date'];
		$this->applicant_passport->Upload->DbValue = $row['applicant_passport'];
		$this->guarantor_name->DbValue = $row['guarantor_name'];
		$this->guarantor_address->DbValue = $row['guarantor_address'];
		$this->guarantor_mobile->DbValue = $row['guarantor_mobile'];
		$this->guarantor_department->DbValue = $row['guarantor_department'];
		$this->account_no->DbValue = $row['account_no'];
		$this->bank_name->DbValue = $row['bank_name'];
		$this->employers_name->DbValue = $row['employers_name'];
		$this->employers_address->DbValue = $row['employers_address'];
		$this->employers_mobile->DbValue = $row['employers_mobile'];
		$this->guarantor_date->DbValue = $row['guarantor_date'];
		$this->guarantor_passport->Upload->DbValue = $row['guarantor_passport'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->recommended_date->DbValue = $row['recommended_date'];
		$this->document_checklist->DbValue = $row['document_checklist'];
		$this->recommender_action->DbValue = $row['recommender_action'];
		$this->recommender_comment->DbValue = $row['recommender_comment'];
		$this->recommended_by->DbValue = $row['recommended_by'];
		$this->application_status->DbValue = $row['application_status'];
		$this->approved_amount->DbValue = $row['approved_amount'];
		$this->duration_approved->DbValue = $row['duration_approved'];
		$this->approval_date->DbValue = $row['approval_date'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->correction_date->DbValue = $row['correction_date'];
		$this->correction_action->DbValue = $row['correction_action'];
		$this->correction_comment->DbValue = $row['correction_comment'];
		$this->corrected_by->DbValue = $row['corrected_by'];
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

		// Convert decimal values if posted back
		if ($this->loan_amount->FormValue == $this->loan_amount->CurrentValue && is_numeric(ew_StrToFloat($this->loan_amount->CurrentValue)))
			$this->loan_amount->CurrentValue = ew_StrToFloat($this->loan_amount->CurrentValue);

		// Convert decimal values if posted back
		if ($this->salary_permonth->FormValue == $this->salary_permonth->CurrentValue && is_numeric(ew_StrToFloat($this->salary_permonth->CurrentValue)))
			$this->salary_permonth->CurrentValue = ew_StrToFloat($this->salary_permonth->CurrentValue);

		// Convert decimal values if posted back
		if ($this->previous_loan->FormValue == $this->previous_loan->CurrentValue && is_numeric(ew_StrToFloat($this->previous_loan->CurrentValue)))
			$this->previous_loan->CurrentValue = ew_StrToFloat($this->previous_loan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->balance_remaining->FormValue == $this->balance_remaining->CurrentValue && is_numeric(ew_StrToFloat($this->balance_remaining->CurrentValue)))
			$this->balance_remaining->CurrentValue = ew_StrToFloat($this->balance_remaining->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// code
		// date_initiated
		// refernce_id
		// employee_name
		// address
		// mobile
		// department
		// pension
		// loan_amount
		// amount_inwords
		// purpose
		// repayment_period
		// salary_permonth
		// previous_loan
		// date_collected
		// date_liquidated
		// balance_remaining
		// applicant_date
		// applicant_passport
		// guarantor_name
		// guarantor_address
		// guarantor_mobile
		// guarantor_department
		// account_no
		// bank_name
		// employers_name
		// employers_address
		// employers_mobile
		// guarantor_date
		// guarantor_passport
		// status
		// initiator_action
		// initiator_comment
		// recommended_date
		// document_checklist
		// recommender_action
		// recommender_comment
		// recommended_by
		// application_status
		// approved_amount
		// duration_approved
		// approval_date
		// approval_action
		// approval_comment
		// approved_by
		// correction_date
		// correction_action
		// correction_comment
		// corrected_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// refernce_id
		$this->refernce_id->ViewValue = $this->refernce_id->CurrentValue;
		$this->refernce_id->ViewCustomAttributes = "";

		// employee_name
		if (strval($this->employee_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->employee_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->employee_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->employee_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->employee_name->ViewValue = $this->employee_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->employee_name->ViewValue = $this->employee_name->CurrentValue;
			}
		} else {
			$this->employee_name->ViewValue = NULL;
		}
		$this->employee_name->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// mobile
		$this->mobile->ViewValue = $this->mobile->CurrentValue;
		$this->mobile->ViewCustomAttributes = "";

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

		// pension
		$this->pension->ViewValue = $this->pension->CurrentValue;
		$this->pension->ViewCustomAttributes = "";

		// loan_amount
		$this->loan_amount->ViewValue = $this->loan_amount->CurrentValue;
		$this->loan_amount->ViewValue = ew_FormatNumber($this->loan_amount->ViewValue, 0, -2, -2, -2);
		$this->loan_amount->ViewCustomAttributes = "";

		// amount_inwords
		$this->amount_inwords->ViewValue = $this->amount_inwords->CurrentValue;
		$this->amount_inwords->ViewCustomAttributes = "";

		// repayment_period
		if (strval($this->repayment_period->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->repayment_period->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
		$sWhereWrk = "";
		$this->repayment_period->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->repayment_period, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->repayment_period->ViewValue = $this->repayment_period->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->repayment_period->ViewValue = $this->repayment_period->CurrentValue;
			}
		} else {
			$this->repayment_period->ViewValue = NULL;
		}
		$this->repayment_period->ViewCustomAttributes = "";

		// salary_permonth
		$this->salary_permonth->ViewValue = $this->salary_permonth->CurrentValue;
		$this->salary_permonth->ViewValue = ew_FormatNumber($this->salary_permonth->ViewValue, 0, -2, -2, -2);
		$this->salary_permonth->ViewCustomAttributes = "";

		// previous_loan
		$this->previous_loan->ViewValue = $this->previous_loan->CurrentValue;
		$this->previous_loan->ViewValue = ew_FormatNumber($this->previous_loan->ViewValue, 0, -2, -2, -2);
		$this->previous_loan->ViewCustomAttributes = "";

		// date_collected
		$this->date_collected->ViewValue = $this->date_collected->CurrentValue;
		$this->date_collected->ViewValue = ew_FormatDateTime($this->date_collected->ViewValue, 0);
		$this->date_collected->ViewCustomAttributes = "";

		// date_liquidated
		$this->date_liquidated->ViewValue = $this->date_liquidated->CurrentValue;
		$this->date_liquidated->ViewValue = ew_FormatDateTime($this->date_liquidated->ViewValue, 0);
		$this->date_liquidated->ViewCustomAttributes = "";

		// balance_remaining
		$this->balance_remaining->ViewValue = $this->balance_remaining->CurrentValue;
		$this->balance_remaining->ViewValue = ew_FormatNumber($this->balance_remaining->ViewValue, 0, -2, -2, -2);
		$this->balance_remaining->ViewCustomAttributes = "";

		// applicant_date
		$this->applicant_date->ViewValue = $this->applicant_date->CurrentValue;
		$this->applicant_date->ViewValue = ew_FormatDateTime($this->applicant_date->ViewValue, 17);
		$this->applicant_date->ViewCustomAttributes = "";

		// guarantor_name
		$this->guarantor_name->ViewValue = $this->guarantor_name->CurrentValue;
		$this->guarantor_name->ViewCustomAttributes = "";

		// guarantor_address
		$this->guarantor_address->ViewValue = $this->guarantor_address->CurrentValue;
		$this->guarantor_address->ViewCustomAttributes = "";

		// guarantor_mobile
		$this->guarantor_mobile->ViewValue = $this->guarantor_mobile->CurrentValue;
		$this->guarantor_mobile->ViewCustomAttributes = "";

		// guarantor_department
		if (strval($this->guarantor_department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->guarantor_department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->guarantor_department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->guarantor_department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->guarantor_department->ViewValue = $this->guarantor_department->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->guarantor_department->ViewValue = $this->guarantor_department->CurrentValue;
			}
		} else {
			$this->guarantor_department->ViewValue = NULL;
		}
		$this->guarantor_department->ViewCustomAttributes = "";

		// account_no
		$this->account_no->ViewValue = $this->account_no->CurrentValue;
		$this->account_no->ViewCustomAttributes = "";

		// bank_name
		if (strval($this->bank_name->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->bank_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `banks_list`";
		$sWhereWrk = "";
		$this->bank_name->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->bank_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->bank_name->ViewValue = $this->bank_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->bank_name->ViewValue = $this->bank_name->CurrentValue;
			}
		} else {
			$this->bank_name->ViewValue = NULL;
		}
		$this->bank_name->ViewCustomAttributes = "";

		// employers_name
		$this->employers_name->ViewValue = $this->employers_name->CurrentValue;
		$this->employers_name->ViewCustomAttributes = "";

		// employers_address
		$this->employers_address->ViewValue = $this->employers_address->CurrentValue;
		$this->employers_address->ViewCustomAttributes = "";

		// employers_mobile
		$this->employers_mobile->ViewValue = $this->employers_mobile->CurrentValue;
		$this->employers_mobile->ViewCustomAttributes = "";

		// guarantor_date
		$this->guarantor_date->ViewValue = $this->guarantor_date->CurrentValue;
		$this->guarantor_date->ViewValue = ew_FormatDateTime($this->guarantor_date->ViewValue, 17);
		$this->guarantor_date->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `loan_status`";
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

		// recommended_date
		$this->recommended_date->ViewValue = $this->recommended_date->CurrentValue;
		$this->recommended_date->ViewValue = ew_FormatDateTime($this->recommended_date->ViewValue, 14);
		$this->recommended_date->ViewCustomAttributes = "";

		// document_checklist
		if (strval($this->document_checklist->CurrentValue) <> "") {
			$arwrk = explode(",", $this->document_checklist->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `discription` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `document_checklist`";
		$sWhereWrk = "";
		$this->document_checklist->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->document_checklist, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->document_checklist->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->document_checklist->ViewValue .= $this->document_checklist->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->document_checklist->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->document_checklist->ViewValue = $this->document_checklist->CurrentValue;
			}
		} else {
			$this->document_checklist->ViewValue = NULL;
		}
		$this->document_checklist->ViewCustomAttributes = "";

		// recommender_action
		if (strval($this->recommender_action->CurrentValue) <> "") {
			$this->recommender_action->ViewValue = $this->recommender_action->OptionCaption($this->recommender_action->CurrentValue);
		} else {
			$this->recommender_action->ViewValue = NULL;
		}
		$this->recommender_action->ViewCustomAttributes = "";

		// recommender_comment
		$this->recommender_comment->ViewValue = $this->recommender_comment->CurrentValue;
		$this->recommender_comment->ViewCustomAttributes = "";

		// recommended_by
		$this->recommended_by->ViewValue = $this->recommended_by->CurrentValue;
		if (strval($this->recommended_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recommended_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recommended_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recommended_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->recommended_by->ViewValue = $this->recommended_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recommended_by->ViewValue = $this->recommended_by->CurrentValue;
			}
		} else {
			$this->recommended_by->ViewValue = NULL;
		}
		$this->recommended_by->ViewCustomAttributes = "";

		// application_status
		if (strval($this->application_status->CurrentValue) <> "") {
			$this->application_status->ViewValue = $this->application_status->OptionCaption($this->application_status->CurrentValue);
		} else {
			$this->application_status->ViewValue = NULL;
		}
		$this->application_status->ViewCustomAttributes = "";

		// approved_amount
		$this->approved_amount->ViewValue = $this->approved_amount->CurrentValue;
		$this->approved_amount->ViewValue = ew_FormatNumber($this->approved_amount->ViewValue, 0, -2, -2, -2);
		$this->approved_amount->ViewCustomAttributes = "";

		// duration_approved
		if (strval($this->duration_approved->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->duration_approved->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
		$sWhereWrk = "";
		$this->duration_approved->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->duration_approved, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->duration_approved->ViewValue = $this->duration_approved->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->duration_approved->ViewValue = $this->duration_approved->CurrentValue;
			}
		} else {
			$this->duration_approved->ViewValue = NULL;
		}
		$this->duration_approved->ViewValue = ew_FormatDateTime($this->duration_approved->ViewValue, 0);
		$this->duration_approved->ViewCustomAttributes = "";

		// approval_date
		$this->approval_date->ViewValue = $this->approval_date->CurrentValue;
		$this->approval_date->ViewValue = ew_FormatDateTime($this->approval_date->ViewValue, 17);
		$this->approval_date->ViewCustomAttributes = "";

		// approval_action
		if (strval($this->approval_action->CurrentValue) <> "") {
			$this->approval_action->ViewValue = $this->approval_action->OptionCaption($this->approval_action->CurrentValue);
		} else {
			$this->approval_action->ViewValue = NULL;
		}
		$this->approval_action->ViewCustomAttributes = "";

		// approval_comment
		$this->approval_comment->ViewValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->ViewCustomAttributes = "";

		// approved_by
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

		// correction_date
		$this->correction_date->ViewValue = $this->correction_date->CurrentValue;
		$this->correction_date->ViewValue = ew_FormatDateTime($this->correction_date->ViewValue, 0);
		$this->correction_date->ViewCustomAttributes = "";

		// correction_action
		if (strval($this->correction_action->CurrentValue) <> "") {
			$this->correction_action->ViewValue = $this->correction_action->OptionCaption($this->correction_action->CurrentValue);
		} else {
			$this->correction_action->ViewValue = NULL;
		}
		$this->correction_action->ViewCustomAttributes = "";

		// correction_comment
		$this->correction_comment->ViewValue = $this->correction_comment->CurrentValue;
		$this->correction_comment->ViewCustomAttributes = "";

		// corrected_by
		$this->corrected_by->ViewValue = $this->corrected_by->CurrentValue;
		if (strval($this->corrected_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->corrected_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->corrected_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->corrected_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->corrected_by->ViewValue = $this->corrected_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->corrected_by->ViewValue = $this->corrected_by->CurrentValue;
			}
		} else {
			$this->corrected_by->ViewValue = NULL;
		}
		$this->corrected_by->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// refernce_id
			$this->refernce_id->LinkCustomAttributes = "";
			$this->refernce_id->HrefValue = "";
			$this->refernce_id->TooltipValue = "";

			// employee_name
			$this->employee_name->LinkCustomAttributes = "";
			$this->employee_name->HrefValue = "";
			$this->employee_name->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// mobile
			$this->mobile->LinkCustomAttributes = "";
			$this->mobile->HrefValue = "";
			$this->mobile->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// pension
			$this->pension->LinkCustomAttributes = "";
			$this->pension->HrefValue = "";
			$this->pension->TooltipValue = "";

			// loan_amount
			$this->loan_amount->LinkCustomAttributes = "";
			$this->loan_amount->HrefValue = "";
			$this->loan_amount->TooltipValue = "";

			// amount_inwords
			$this->amount_inwords->LinkCustomAttributes = "";
			$this->amount_inwords->HrefValue = "";
			$this->amount_inwords->TooltipValue = "";

			// repayment_period
			$this->repayment_period->LinkCustomAttributes = "";
			$this->repayment_period->HrefValue = "";
			$this->repayment_period->TooltipValue = "";

			// salary_permonth
			$this->salary_permonth->LinkCustomAttributes = "";
			$this->salary_permonth->HrefValue = "";
			$this->salary_permonth->TooltipValue = "";

			// previous_loan
			$this->previous_loan->LinkCustomAttributes = "";
			$this->previous_loan->HrefValue = "";
			$this->previous_loan->TooltipValue = "";

			// date_collected
			$this->date_collected->LinkCustomAttributes = "";
			$this->date_collected->HrefValue = "";
			$this->date_collected->TooltipValue = "";

			// date_liquidated
			$this->date_liquidated->LinkCustomAttributes = "";
			$this->date_liquidated->HrefValue = "";
			$this->date_liquidated->TooltipValue = "";

			// balance_remaining
			$this->balance_remaining->LinkCustomAttributes = "";
			$this->balance_remaining->HrefValue = "";
			$this->balance_remaining->TooltipValue = "";

			// applicant_date
			$this->applicant_date->LinkCustomAttributes = "";
			$this->applicant_date->HrefValue = "";
			$this->applicant_date->TooltipValue = "";

			// guarantor_name
			$this->guarantor_name->LinkCustomAttributes = "";
			$this->guarantor_name->HrefValue = "";
			$this->guarantor_name->TooltipValue = "";

			// guarantor_address
			$this->guarantor_address->LinkCustomAttributes = "";
			$this->guarantor_address->HrefValue = "";
			$this->guarantor_address->TooltipValue = "";

			// guarantor_mobile
			$this->guarantor_mobile->LinkCustomAttributes = "";
			$this->guarantor_mobile->HrefValue = "";
			$this->guarantor_mobile->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// application_status
			$this->application_status->LinkCustomAttributes = "";
			$this->application_status->HrefValue = "";
			$this->application_status->TooltipValue = "";

			// correction_date
			$this->correction_date->LinkCustomAttributes = "";
			$this->correction_date->HrefValue = "";
			$this->correction_date->TooltipValue = "";

			// correction_action
			$this->correction_action->LinkCustomAttributes = "";
			$this->correction_action->HrefValue = "";
			$this->correction_action->TooltipValue = "";

			// correction_comment
			$this->correction_comment->LinkCustomAttributes = "";
			$this->correction_comment->HrefValue = "";
			$this->correction_comment->TooltipValue = "";

			// corrected_by
			$this->corrected_by->LinkCustomAttributes = "";
			$this->corrected_by->HrefValue = "";
			$this->corrected_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_initiated->AdvancedSearch->SearchValue, 0), 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// refernce_id
			$this->refernce_id->EditAttrs["class"] = "form-control";
			$this->refernce_id->EditCustomAttributes = "";
			$this->refernce_id->EditValue = ew_HtmlEncode($this->refernce_id->AdvancedSearch->SearchValue);
			$this->refernce_id->PlaceHolder = ew_RemoveHtml($this->refernce_id->FldCaption());

			// employee_name
			$this->employee_name->EditAttrs["class"] = "form-control";
			$this->employee_name->EditCustomAttributes = "";

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->AdvancedSearch->SearchValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// mobile
			$this->mobile->EditAttrs["class"] = "form-control";
			$this->mobile->EditCustomAttributes = "";
			$this->mobile->EditValue = ew_HtmlEncode($this->mobile->AdvancedSearch->SearchValue);
			$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";

			// pension
			$this->pension->EditAttrs["class"] = "form-control";
			$this->pension->EditCustomAttributes = "";
			$this->pension->EditValue = ew_HtmlEncode($this->pension->AdvancedSearch->SearchValue);
			$this->pension->PlaceHolder = ew_RemoveHtml($this->pension->FldCaption());

			// loan_amount
			$this->loan_amount->EditAttrs["class"] = "form-control";
			$this->loan_amount->EditCustomAttributes = "";
			$this->loan_amount->EditValue = ew_HtmlEncode($this->loan_amount->AdvancedSearch->SearchValue);
			$this->loan_amount->PlaceHolder = ew_RemoveHtml($this->loan_amount->FldCaption());

			// amount_inwords
			$this->amount_inwords->EditAttrs["class"] = "form-control";
			$this->amount_inwords->EditCustomAttributes = "";
			$this->amount_inwords->EditValue = ew_HtmlEncode($this->amount_inwords->AdvancedSearch->SearchValue);
			$this->amount_inwords->PlaceHolder = ew_RemoveHtml($this->amount_inwords->FldCaption());

			// repayment_period
			$this->repayment_period->EditAttrs["class"] = "form-control";
			$this->repayment_period->EditCustomAttributes = "";

			// salary_permonth
			$this->salary_permonth->EditAttrs["class"] = "form-control";
			$this->salary_permonth->EditCustomAttributes = "";
			$this->salary_permonth->EditValue = ew_HtmlEncode($this->salary_permonth->AdvancedSearch->SearchValue);
			$this->salary_permonth->PlaceHolder = ew_RemoveHtml($this->salary_permonth->FldCaption());

			// previous_loan
			$this->previous_loan->EditAttrs["class"] = "form-control";
			$this->previous_loan->EditCustomAttributes = "";
			$this->previous_loan->EditValue = ew_HtmlEncode($this->previous_loan->AdvancedSearch->SearchValue);
			$this->previous_loan->PlaceHolder = ew_RemoveHtml($this->previous_loan->FldCaption());

			// date_collected
			$this->date_collected->EditAttrs["class"] = "form-control";
			$this->date_collected->EditCustomAttributes = "";
			$this->date_collected->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_collected->AdvancedSearch->SearchValue, 0), 8));
			$this->date_collected->PlaceHolder = ew_RemoveHtml($this->date_collected->FldCaption());

			// date_liquidated
			$this->date_liquidated->EditAttrs["class"] = "form-control";
			$this->date_liquidated->EditCustomAttributes = "";
			$this->date_liquidated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_liquidated->AdvancedSearch->SearchValue, 0), 8));
			$this->date_liquidated->PlaceHolder = ew_RemoveHtml($this->date_liquidated->FldCaption());

			// balance_remaining
			$this->balance_remaining->EditAttrs["class"] = "form-control";
			$this->balance_remaining->EditCustomAttributes = "";
			$this->balance_remaining->EditValue = ew_HtmlEncode($this->balance_remaining->AdvancedSearch->SearchValue);
			$this->balance_remaining->PlaceHolder = ew_RemoveHtml($this->balance_remaining->FldCaption());

			// applicant_date
			$this->applicant_date->EditAttrs["class"] = "form-control";
			$this->applicant_date->EditCustomAttributes = "";
			$this->applicant_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->applicant_date->AdvancedSearch->SearchValue, 17), 17));
			$this->applicant_date->PlaceHolder = ew_RemoveHtml($this->applicant_date->FldCaption());

			// guarantor_name
			$this->guarantor_name->EditAttrs["class"] = "form-control";
			$this->guarantor_name->EditCustomAttributes = "";
			$this->guarantor_name->EditValue = ew_HtmlEncode($this->guarantor_name->AdvancedSearch->SearchValue);
			$this->guarantor_name->PlaceHolder = ew_RemoveHtml($this->guarantor_name->FldCaption());

			// guarantor_address
			$this->guarantor_address->EditAttrs["class"] = "form-control";
			$this->guarantor_address->EditCustomAttributes = "";
			$this->guarantor_address->EditValue = ew_HtmlEncode($this->guarantor_address->AdvancedSearch->SearchValue);
			$this->guarantor_address->PlaceHolder = ew_RemoveHtml($this->guarantor_address->FldCaption());

			// guarantor_mobile
			$this->guarantor_mobile->EditAttrs["class"] = "form-control";
			$this->guarantor_mobile->EditCustomAttributes = "";
			$this->guarantor_mobile->EditValue = ew_HtmlEncode($this->guarantor_mobile->AdvancedSearch->SearchValue);
			$this->guarantor_mobile->PlaceHolder = ew_RemoveHtml($this->guarantor_mobile->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// application_status
			$this->application_status->EditCustomAttributes = "";
			$this->application_status->EditValue = $this->application_status->Options(FALSE);

			// correction_date
			$this->correction_date->EditAttrs["class"] = "form-control";
			$this->correction_date->EditCustomAttributes = "";
			$this->correction_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->correction_date->AdvancedSearch->SearchValue, 0), 8));
			$this->correction_date->PlaceHolder = ew_RemoveHtml($this->correction_date->FldCaption());

			// correction_action
			$this->correction_action->EditCustomAttributes = "";
			$this->correction_action->EditValue = $this->correction_action->Options(FALSE);

			// correction_comment
			$this->correction_comment->EditAttrs["class"] = "form-control";
			$this->correction_comment->EditCustomAttributes = "";
			$this->correction_comment->EditValue = ew_HtmlEncode($this->correction_comment->AdvancedSearch->SearchValue);
			$this->correction_comment->PlaceHolder = ew_RemoveHtml($this->correction_comment->FldCaption());

			// corrected_by
			$this->corrected_by->EditAttrs["class"] = "form-control";
			$this->corrected_by->EditCustomAttributes = "";
			$this->corrected_by->EditValue = ew_HtmlEncode($this->corrected_by->AdvancedSearch->SearchValue);
			$this->corrected_by->PlaceHolder = ew_RemoveHtml($this->corrected_by->FldCaption());
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
		$this->date_initiated->AdvancedSearch->Load();
		$this->refernce_id->AdvancedSearch->Load();
		$this->employee_name->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->mobile->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->pension->AdvancedSearch->Load();
		$this->loan_amount->AdvancedSearch->Load();
		$this->amount_inwords->AdvancedSearch->Load();
		$this->purpose->AdvancedSearch->Load();
		$this->repayment_period->AdvancedSearch->Load();
		$this->salary_permonth->AdvancedSearch->Load();
		$this->previous_loan->AdvancedSearch->Load();
		$this->date_collected->AdvancedSearch->Load();
		$this->date_liquidated->AdvancedSearch->Load();
		$this->balance_remaining->AdvancedSearch->Load();
		$this->applicant_date->AdvancedSearch->Load();
		$this->applicant_passport->AdvancedSearch->Load();
		$this->guarantor_name->AdvancedSearch->Load();
		$this->guarantor_address->AdvancedSearch->Load();
		$this->guarantor_mobile->AdvancedSearch->Load();
		$this->guarantor_department->AdvancedSearch->Load();
		$this->account_no->AdvancedSearch->Load();
		$this->bank_name->AdvancedSearch->Load();
		$this->employers_name->AdvancedSearch->Load();
		$this->employers_address->AdvancedSearch->Load();
		$this->employers_mobile->AdvancedSearch->Load();
		$this->guarantor_date->AdvancedSearch->Load();
		$this->guarantor_passport->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->recommended_date->AdvancedSearch->Load();
		$this->document_checklist->AdvancedSearch->Load();
		$this->recommender_action->AdvancedSearch->Load();
		$this->recommender_comment->AdvancedSearch->Load();
		$this->recommended_by->AdvancedSearch->Load();
		$this->application_status->AdvancedSearch->Load();
		$this->approved_amount->AdvancedSearch->Load();
		$this->duration_approved->AdvancedSearch->Load();
		$this->approval_date->AdvancedSearch->Load();
		$this->approval_action->AdvancedSearch->Load();
		$this->approval_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->correction_date->AdvancedSearch->Load();
		$this->correction_action->AdvancedSearch->Load();
		$this->correction_comment->AdvancedSearch->Load();
		$this->corrected_by->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_loan_application\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_loan_application',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.floan_applicationlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
			if (CurrentPageID() == "list"){
			 $_SESSION['LAP_ID'] = generateLAPKey();
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
if (!isset($loan_application_list)) $loan_application_list = new cloan_application_list();

// Page init
$loan_application_list->Page_Init();

// Page main
$loan_application_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$loan_application_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($loan_application->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = floan_applicationlist = new ew_Form("floan_applicationlist", "list");
floan_applicationlist.FormKeyCountName = '<?php echo $loan_application_list->FormKeyCountName ?>';

// Form_CustomValidate event
floan_applicationlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
floan_applicationlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
floan_applicationlist.Lists["x_employee_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_applicationlist.Lists["x_employee_name"].Data = "<?php echo $loan_application_list->employee_name->LookupFilterQuery(FALSE, "list") ?>";
floan_applicationlist.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
floan_applicationlist.Lists["x_department"].Data = "<?php echo $loan_application_list->department->LookupFilterQuery(FALSE, "list") ?>";
floan_applicationlist.Lists["x_repayment_period"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"duration_months"};
floan_applicationlist.Lists["x_repayment_period"].Data = "<?php echo $loan_application_list->repayment_period->LookupFilterQuery(FALSE, "list") ?>";
floan_applicationlist.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"loan_status"};
floan_applicationlist.Lists["x_status"].Data = "<?php echo $loan_application_list->status->LookupFilterQuery(FALSE, "list") ?>";
floan_applicationlist.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_application_list->status->LookupFilterQuery(TRUE, "list"))) ?>;
floan_applicationlist.Lists["x_application_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationlist.Lists["x_application_status"].Options = <?php echo json_encode($loan_application_list->application_status->Options()) ?>;
floan_applicationlist.Lists["x_correction_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
floan_applicationlist.Lists["x_correction_action"].Options = <?php echo json_encode($loan_application_list->correction_action->Options()) ?>;
floan_applicationlist.Lists["x_corrected_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
floan_applicationlist.Lists["x_corrected_by"].Data = "<?php echo $loan_application_list->corrected_by->LookupFilterQuery(FALSE, "list") ?>";
floan_applicationlist.AutoSuggests["x_corrected_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $loan_application_list->corrected_by->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = floan_applicationlistsrch = new ew_Form("floan_applicationlistsrch");

// Validate function for search
floan_applicationlistsrch.Validate = function(fobj) {
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
floan_applicationlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
floan_applicationlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($loan_application->Export == "") { ?>
<div class="ewToolbar">
<?php if ($loan_application_list->TotalRecs > 0 && $loan_application_list->ExportOptions->Visible()) { ?>
<?php $loan_application_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($loan_application_list->SearchOptions->Visible()) { ?>
<?php $loan_application_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($loan_application_list->FilterOptions->Visible()) { ?>
<?php $loan_application_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $loan_application_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($loan_application_list->TotalRecs <= 0)
			$loan_application_list->TotalRecs = $loan_application->ListRecordCount();
	} else {
		if (!$loan_application_list->Recordset && ($loan_application_list->Recordset = $loan_application_list->LoadRecordset()))
			$loan_application_list->TotalRecs = $loan_application_list->Recordset->RecordCount();
	}
	$loan_application_list->StartRec = 1;
	if ($loan_application_list->DisplayRecs <= 0 || ($loan_application->Export <> "" && $loan_application->ExportAll)) // Display all records
		$loan_application_list->DisplayRecs = $loan_application_list->TotalRecs;
	if (!($loan_application->Export <> "" && $loan_application->ExportAll))
		$loan_application_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$loan_application_list->Recordset = $loan_application_list->LoadRecordset($loan_application_list->StartRec-1, $loan_application_list->DisplayRecs);

	// Set no record found message
	if ($loan_application->CurrentAction == "" && $loan_application_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$loan_application_list->setWarningMessage(ew_DeniedMsg());
		if ($loan_application_list->SearchWhere == "0=101")
			$loan_application_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$loan_application_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($loan_application_list->AuditTrailOnSearch && $loan_application_list->Command == "search" && !$loan_application_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $loan_application_list->getSessionWhere();
		$loan_application_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$loan_application_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($loan_application->Export == "" && $loan_application->CurrentAction == "") { ?>
<form name="floan_applicationlistsrch" id="floan_applicationlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($loan_application_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="floan_applicationlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="loan_application">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$loan_application_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$loan_application->RowType = EW_ROWTYPE_SEARCH;

// Render row
$loan_application->ResetAttrs();
$loan_application_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($loan_application->refernce_id->Visible) { // refernce_id ?>
	<div id="xsc_refernce_id" class="ewCell form-group">
		<label for="x_refernce_id" class="ewSearchCaption ewLabel"><?php echo $loan_application->refernce_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_refernce_id" id="z_refernce_id" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="loan_application" data-field="x_refernce_id" name="x_refernce_id" id="x_refernce_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($loan_application->refernce_id->getPlaceHolder()) ?>" value="<?php echo $loan_application->refernce_id->EditValue ?>"<?php echo $loan_application->refernce_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($loan_application_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($loan_application_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $loan_application_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($loan_application_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($loan_application_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($loan_application_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($loan_application_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $loan_application_list->ShowPageHeader(); ?>
<?php
$loan_application_list->ShowMessage();
?>
<?php if ($loan_application_list->TotalRecs > 0 || $loan_application->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($loan_application_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> loan_application">
<?php if ($loan_application->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($loan_application->CurrentAction <> "gridadd" && $loan_application->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($loan_application_list->Pager)) $loan_application_list->Pager = new cPrevNextPager($loan_application_list->StartRec, $loan_application_list->DisplayRecs, $loan_application_list->TotalRecs, $loan_application_list->AutoHidePager) ?>
<?php if ($loan_application_list->Pager->RecordCount > 0 && $loan_application_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($loan_application_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $loan_application_list->PageUrl() ?>start=<?php echo $loan_application_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($loan_application_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $loan_application_list->PageUrl() ?>start=<?php echo $loan_application_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $loan_application_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($loan_application_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $loan_application_list->PageUrl() ?>start=<?php echo $loan_application_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($loan_application_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $loan_application_list->PageUrl() ?>start=<?php echo $loan_application_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $loan_application_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($loan_application_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $loan_application_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $loan_application_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $loan_application_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($loan_application_list->TotalRecs > 0 && (!$loan_application_list->AutoHidePageSizeSelector || $loan_application_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="loan_application">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($loan_application_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($loan_application_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($loan_application_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($loan_application_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($loan_application_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($loan_application->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($loan_application_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="floan_applicationlist" id="floan_applicationlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($loan_application_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $loan_application_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="loan_application">
<div id="gmp_loan_application" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($loan_application_list->TotalRecs > 0 || $loan_application->CurrentAction == "gridedit") { ?>
<table id="tbl_loan_applicationlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$loan_application_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$loan_application_list->RenderListOptions();

// Render list options (header, left)
$loan_application_list->ListOptions->Render("header", "left");
?>
<?php if ($loan_application->date_initiated->Visible) { // date_initiated ?>
	<?php if ($loan_application->SortUrl($loan_application->date_initiated) == "") { ?>
		<th data-name="date_initiated" class="<?php echo $loan_application->date_initiated->HeaderCellClass() ?>"><div id="elh_loan_application_date_initiated" class="loan_application_date_initiated"><div class="ewTableHeaderCaption"><?php echo $loan_application->date_initiated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_initiated" class="<?php echo $loan_application->date_initiated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->date_initiated) ?>',1);"><div id="elh_loan_application_date_initiated" class="loan_application_date_initiated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->date_initiated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->date_initiated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->date_initiated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->refernce_id->Visible) { // refernce_id ?>
	<?php if ($loan_application->SortUrl($loan_application->refernce_id) == "") { ?>
		<th data-name="refernce_id" class="<?php echo $loan_application->refernce_id->HeaderCellClass() ?>"><div id="elh_loan_application_refernce_id" class="loan_application_refernce_id"><div class="ewTableHeaderCaption"><?php echo $loan_application->refernce_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="refernce_id" class="<?php echo $loan_application->refernce_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->refernce_id) ?>',1);"><div id="elh_loan_application_refernce_id" class="loan_application_refernce_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->refernce_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->refernce_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->refernce_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->employee_name->Visible) { // employee_name ?>
	<?php if ($loan_application->SortUrl($loan_application->employee_name) == "") { ?>
		<th data-name="employee_name" class="<?php echo $loan_application->employee_name->HeaderCellClass() ?>"><div id="elh_loan_application_employee_name" class="loan_application_employee_name"><div class="ewTableHeaderCaption"><?php echo $loan_application->employee_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="employee_name" class="<?php echo $loan_application->employee_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->employee_name) ?>',1);"><div id="elh_loan_application_employee_name" class="loan_application_employee_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->employee_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->employee_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->employee_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->address->Visible) { // address ?>
	<?php if ($loan_application->SortUrl($loan_application->address) == "") { ?>
		<th data-name="address" class="<?php echo $loan_application->address->HeaderCellClass() ?>"><div id="elh_loan_application_address" class="loan_application_address"><div class="ewTableHeaderCaption"><?php echo $loan_application->address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address" class="<?php echo $loan_application->address->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->address) ?>',1);"><div id="elh_loan_application_address" class="loan_application_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->mobile->Visible) { // mobile ?>
	<?php if ($loan_application->SortUrl($loan_application->mobile) == "") { ?>
		<th data-name="mobile" class="<?php echo $loan_application->mobile->HeaderCellClass() ?>"><div id="elh_loan_application_mobile" class="loan_application_mobile"><div class="ewTableHeaderCaption"><?php echo $loan_application->mobile->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mobile" class="<?php echo $loan_application->mobile->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->mobile) ?>',1);"><div id="elh_loan_application_mobile" class="loan_application_mobile">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->mobile->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->mobile->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->mobile->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->department->Visible) { // department ?>
	<?php if ($loan_application->SortUrl($loan_application->department) == "") { ?>
		<th data-name="department" class="<?php echo $loan_application->department->HeaderCellClass() ?>"><div id="elh_loan_application_department" class="loan_application_department"><div class="ewTableHeaderCaption"><?php echo $loan_application->department->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="department" class="<?php echo $loan_application->department->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->department) ?>',1);"><div id="elh_loan_application_department" class="loan_application_department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->pension->Visible) { // pension ?>
	<?php if ($loan_application->SortUrl($loan_application->pension) == "") { ?>
		<th data-name="pension" class="<?php echo $loan_application->pension->HeaderCellClass() ?>"><div id="elh_loan_application_pension" class="loan_application_pension"><div class="ewTableHeaderCaption"><?php echo $loan_application->pension->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pension" class="<?php echo $loan_application->pension->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->pension) ?>',1);"><div id="elh_loan_application_pension" class="loan_application_pension">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->pension->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->pension->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->pension->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->loan_amount->Visible) { // loan_amount ?>
	<?php if ($loan_application->SortUrl($loan_application->loan_amount) == "") { ?>
		<th data-name="loan_amount" class="<?php echo $loan_application->loan_amount->HeaderCellClass() ?>"><div id="elh_loan_application_loan_amount" class="loan_application_loan_amount"><div class="ewTableHeaderCaption"><?php echo $loan_application->loan_amount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="loan_amount" class="<?php echo $loan_application->loan_amount->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->loan_amount) ?>',1);"><div id="elh_loan_application_loan_amount" class="loan_application_loan_amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->loan_amount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->loan_amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->loan_amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->amount_inwords->Visible) { // amount_inwords ?>
	<?php if ($loan_application->SortUrl($loan_application->amount_inwords) == "") { ?>
		<th data-name="amount_inwords" class="<?php echo $loan_application->amount_inwords->HeaderCellClass() ?>"><div id="elh_loan_application_amount_inwords" class="loan_application_amount_inwords"><div class="ewTableHeaderCaption"><?php echo $loan_application->amount_inwords->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="amount_inwords" class="<?php echo $loan_application->amount_inwords->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->amount_inwords) ?>',1);"><div id="elh_loan_application_amount_inwords" class="loan_application_amount_inwords">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->amount_inwords->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->amount_inwords->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->amount_inwords->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->repayment_period->Visible) { // repayment_period ?>
	<?php if ($loan_application->SortUrl($loan_application->repayment_period) == "") { ?>
		<th data-name="repayment_period" class="<?php echo $loan_application->repayment_period->HeaderCellClass() ?>"><div id="elh_loan_application_repayment_period" class="loan_application_repayment_period"><div class="ewTableHeaderCaption"><?php echo $loan_application->repayment_period->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="repayment_period" class="<?php echo $loan_application->repayment_period->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->repayment_period) ?>',1);"><div id="elh_loan_application_repayment_period" class="loan_application_repayment_period">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->repayment_period->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->repayment_period->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->repayment_period->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->salary_permonth->Visible) { // salary_permonth ?>
	<?php if ($loan_application->SortUrl($loan_application->salary_permonth) == "") { ?>
		<th data-name="salary_permonth" class="<?php echo $loan_application->salary_permonth->HeaderCellClass() ?>"><div id="elh_loan_application_salary_permonth" class="loan_application_salary_permonth"><div class="ewTableHeaderCaption"><?php echo $loan_application->salary_permonth->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="salary_permonth" class="<?php echo $loan_application->salary_permonth->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->salary_permonth) ?>',1);"><div id="elh_loan_application_salary_permonth" class="loan_application_salary_permonth">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->salary_permonth->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->salary_permonth->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->salary_permonth->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->previous_loan->Visible) { // previous_loan ?>
	<?php if ($loan_application->SortUrl($loan_application->previous_loan) == "") { ?>
		<th data-name="previous_loan" class="<?php echo $loan_application->previous_loan->HeaderCellClass() ?>"><div id="elh_loan_application_previous_loan" class="loan_application_previous_loan"><div class="ewTableHeaderCaption"><?php echo $loan_application->previous_loan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="previous_loan" class="<?php echo $loan_application->previous_loan->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->previous_loan) ?>',1);"><div id="elh_loan_application_previous_loan" class="loan_application_previous_loan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->previous_loan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->previous_loan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->previous_loan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->date_collected->Visible) { // date_collected ?>
	<?php if ($loan_application->SortUrl($loan_application->date_collected) == "") { ?>
		<th data-name="date_collected" class="<?php echo $loan_application->date_collected->HeaderCellClass() ?>"><div id="elh_loan_application_date_collected" class="loan_application_date_collected"><div class="ewTableHeaderCaption"><?php echo $loan_application->date_collected->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_collected" class="<?php echo $loan_application->date_collected->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->date_collected) ?>',1);"><div id="elh_loan_application_date_collected" class="loan_application_date_collected">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->date_collected->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->date_collected->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->date_collected->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->date_liquidated->Visible) { // date_liquidated ?>
	<?php if ($loan_application->SortUrl($loan_application->date_liquidated) == "") { ?>
		<th data-name="date_liquidated" class="<?php echo $loan_application->date_liquidated->HeaderCellClass() ?>"><div id="elh_loan_application_date_liquidated" class="loan_application_date_liquidated"><div class="ewTableHeaderCaption"><?php echo $loan_application->date_liquidated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_liquidated" class="<?php echo $loan_application->date_liquidated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->date_liquidated) ?>',1);"><div id="elh_loan_application_date_liquidated" class="loan_application_date_liquidated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->date_liquidated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->date_liquidated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->date_liquidated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->balance_remaining->Visible) { // balance_remaining ?>
	<?php if ($loan_application->SortUrl($loan_application->balance_remaining) == "") { ?>
		<th data-name="balance_remaining" class="<?php echo $loan_application->balance_remaining->HeaderCellClass() ?>"><div id="elh_loan_application_balance_remaining" class="loan_application_balance_remaining"><div class="ewTableHeaderCaption"><?php echo $loan_application->balance_remaining->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="balance_remaining" class="<?php echo $loan_application->balance_remaining->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->balance_remaining) ?>',1);"><div id="elh_loan_application_balance_remaining" class="loan_application_balance_remaining">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->balance_remaining->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->balance_remaining->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->balance_remaining->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->applicant_date->Visible) { // applicant_date ?>
	<?php if ($loan_application->SortUrl($loan_application->applicant_date) == "") { ?>
		<th data-name="applicant_date" class="<?php echo $loan_application->applicant_date->HeaderCellClass() ?>"><div id="elh_loan_application_applicant_date" class="loan_application_applicant_date"><div class="ewTableHeaderCaption"><?php echo $loan_application->applicant_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="applicant_date" class="<?php echo $loan_application->applicant_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->applicant_date) ?>',1);"><div id="elh_loan_application_applicant_date" class="loan_application_applicant_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->applicant_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->applicant_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->applicant_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->guarantor_name->Visible) { // guarantor_name ?>
	<?php if ($loan_application->SortUrl($loan_application->guarantor_name) == "") { ?>
		<th data-name="guarantor_name" class="<?php echo $loan_application->guarantor_name->HeaderCellClass() ?>"><div id="elh_loan_application_guarantor_name" class="loan_application_guarantor_name"><div class="ewTableHeaderCaption"><?php echo $loan_application->guarantor_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="guarantor_name" class="<?php echo $loan_application->guarantor_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->guarantor_name) ?>',1);"><div id="elh_loan_application_guarantor_name" class="loan_application_guarantor_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->guarantor_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->guarantor_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->guarantor_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->guarantor_address->Visible) { // guarantor_address ?>
	<?php if ($loan_application->SortUrl($loan_application->guarantor_address) == "") { ?>
		<th data-name="guarantor_address" class="<?php echo $loan_application->guarantor_address->HeaderCellClass() ?>"><div id="elh_loan_application_guarantor_address" class="loan_application_guarantor_address"><div class="ewTableHeaderCaption"><?php echo $loan_application->guarantor_address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="guarantor_address" class="<?php echo $loan_application->guarantor_address->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->guarantor_address) ?>',1);"><div id="elh_loan_application_guarantor_address" class="loan_application_guarantor_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->guarantor_address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->guarantor_address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->guarantor_address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->guarantor_mobile->Visible) { // guarantor_mobile ?>
	<?php if ($loan_application->SortUrl($loan_application->guarantor_mobile) == "") { ?>
		<th data-name="guarantor_mobile" class="<?php echo $loan_application->guarantor_mobile->HeaderCellClass() ?>"><div id="elh_loan_application_guarantor_mobile" class="loan_application_guarantor_mobile"><div class="ewTableHeaderCaption"><?php echo $loan_application->guarantor_mobile->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="guarantor_mobile" class="<?php echo $loan_application->guarantor_mobile->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->guarantor_mobile) ?>',1);"><div id="elh_loan_application_guarantor_mobile" class="loan_application_guarantor_mobile">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->guarantor_mobile->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->guarantor_mobile->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->guarantor_mobile->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->status->Visible) { // status ?>
	<?php if ($loan_application->SortUrl($loan_application->status) == "") { ?>
		<th data-name="status" class="<?php echo $loan_application->status->HeaderCellClass() ?>"><div id="elh_loan_application_status" class="loan_application_status"><div class="ewTableHeaderCaption"><?php echo $loan_application->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $loan_application->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->status) ?>',1);"><div id="elh_loan_application_status" class="loan_application_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->application_status->Visible) { // application_status ?>
	<?php if ($loan_application->SortUrl($loan_application->application_status) == "") { ?>
		<th data-name="application_status" class="<?php echo $loan_application->application_status->HeaderCellClass() ?>"><div id="elh_loan_application_application_status" class="loan_application_application_status"><div class="ewTableHeaderCaption"><?php echo $loan_application->application_status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="application_status" class="<?php echo $loan_application->application_status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->application_status) ?>',1);"><div id="elh_loan_application_application_status" class="loan_application_application_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->application_status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->application_status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->application_status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->correction_date->Visible) { // correction_date ?>
	<?php if ($loan_application->SortUrl($loan_application->correction_date) == "") { ?>
		<th data-name="correction_date" class="<?php echo $loan_application->correction_date->HeaderCellClass() ?>"><div id="elh_loan_application_correction_date" class="loan_application_correction_date"><div class="ewTableHeaderCaption"><?php echo $loan_application->correction_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="correction_date" class="<?php echo $loan_application->correction_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->correction_date) ?>',1);"><div id="elh_loan_application_correction_date" class="loan_application_correction_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->correction_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->correction_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->correction_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->correction_action->Visible) { // correction_action ?>
	<?php if ($loan_application->SortUrl($loan_application->correction_action) == "") { ?>
		<th data-name="correction_action" class="<?php echo $loan_application->correction_action->HeaderCellClass() ?>"><div id="elh_loan_application_correction_action" class="loan_application_correction_action"><div class="ewTableHeaderCaption"><?php echo $loan_application->correction_action->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="correction_action" class="<?php echo $loan_application->correction_action->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->correction_action) ?>',1);"><div id="elh_loan_application_correction_action" class="loan_application_correction_action">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->correction_action->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->correction_action->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->correction_action->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->correction_comment->Visible) { // correction_comment ?>
	<?php if ($loan_application->SortUrl($loan_application->correction_comment) == "") { ?>
		<th data-name="correction_comment" class="<?php echo $loan_application->correction_comment->HeaderCellClass() ?>"><div id="elh_loan_application_correction_comment" class="loan_application_correction_comment"><div class="ewTableHeaderCaption"><?php echo $loan_application->correction_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="correction_comment" class="<?php echo $loan_application->correction_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->correction_comment) ?>',1);"><div id="elh_loan_application_correction_comment" class="loan_application_correction_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->correction_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->correction_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->correction_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($loan_application->corrected_by->Visible) { // corrected_by ?>
	<?php if ($loan_application->SortUrl($loan_application->corrected_by) == "") { ?>
		<th data-name="corrected_by" class="<?php echo $loan_application->corrected_by->HeaderCellClass() ?>"><div id="elh_loan_application_corrected_by" class="loan_application_corrected_by"><div class="ewTableHeaderCaption"><?php echo $loan_application->corrected_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="corrected_by" class="<?php echo $loan_application->corrected_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $loan_application->SortUrl($loan_application->corrected_by) ?>',1);"><div id="elh_loan_application_corrected_by" class="loan_application_corrected_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $loan_application->corrected_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($loan_application->corrected_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($loan_application->corrected_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$loan_application_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($loan_application->ExportAll && $loan_application->Export <> "") {
	$loan_application_list->StopRec = $loan_application_list->TotalRecs;
} else {

	// Set the last record to display
	if ($loan_application_list->TotalRecs > $loan_application_list->StartRec + $loan_application_list->DisplayRecs - 1)
		$loan_application_list->StopRec = $loan_application_list->StartRec + $loan_application_list->DisplayRecs - 1;
	else
		$loan_application_list->StopRec = $loan_application_list->TotalRecs;
}
$loan_application_list->RecCnt = $loan_application_list->StartRec - 1;
if ($loan_application_list->Recordset && !$loan_application_list->Recordset->EOF) {
	$loan_application_list->Recordset->MoveFirst();
	$bSelectLimit = $loan_application_list->UseSelectLimit;
	if (!$bSelectLimit && $loan_application_list->StartRec > 1)
		$loan_application_list->Recordset->Move($loan_application_list->StartRec - 1);
} elseif (!$loan_application->AllowAddDeleteRow && $loan_application_list->StopRec == 0) {
	$loan_application_list->StopRec = $loan_application->GridAddRowCount;
}

// Initialize aggregate
$loan_application->RowType = EW_ROWTYPE_AGGREGATEINIT;
$loan_application->ResetAttrs();
$loan_application_list->RenderRow();
while ($loan_application_list->RecCnt < $loan_application_list->StopRec) {
	$loan_application_list->RecCnt++;
	if (intval($loan_application_list->RecCnt) >= intval($loan_application_list->StartRec)) {
		$loan_application_list->RowCnt++;

		// Set up key count
		$loan_application_list->KeyCount = $loan_application_list->RowIndex;

		// Init row class and style
		$loan_application->ResetAttrs();
		$loan_application->CssClass = "";
		if ($loan_application->CurrentAction == "gridadd") {
		} else {
			$loan_application_list->LoadRowValues($loan_application_list->Recordset); // Load row values
		}
		$loan_application->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$loan_application->RowAttrs = array_merge($loan_application->RowAttrs, array('data-rowindex'=>$loan_application_list->RowCnt, 'id'=>'r' . $loan_application_list->RowCnt . '_loan_application', 'data-rowtype'=>$loan_application->RowType));

		// Render row
		$loan_application_list->RenderRow();

		// Render list options
		$loan_application_list->RenderListOptions();
?>
	<tr<?php echo $loan_application->RowAttributes() ?>>
<?php

// Render list options (body, left)
$loan_application_list->ListOptions->Render("body", "left", $loan_application_list->RowCnt);
?>
	<?php if ($loan_application->date_initiated->Visible) { // date_initiated ?>
		<td data-name="date_initiated"<?php echo $loan_application->date_initiated->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_date_initiated" class="loan_application_date_initiated">
<span<?php echo $loan_application->date_initiated->ViewAttributes() ?>>
<?php echo $loan_application->date_initiated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->refernce_id->Visible) { // refernce_id ?>
		<td data-name="refernce_id"<?php echo $loan_application->refernce_id->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_refernce_id" class="loan_application_refernce_id">
<span<?php echo $loan_application->refernce_id->ViewAttributes() ?>>
<?php echo $loan_application->refernce_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->employee_name->Visible) { // employee_name ?>
		<td data-name="employee_name"<?php echo $loan_application->employee_name->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_employee_name" class="loan_application_employee_name">
<span<?php echo $loan_application->employee_name->ViewAttributes() ?>>
<?php echo $loan_application->employee_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->address->Visible) { // address ?>
		<td data-name="address"<?php echo $loan_application->address->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_address" class="loan_application_address">
<span<?php echo $loan_application->address->ViewAttributes() ?>>
<?php echo $loan_application->address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->mobile->Visible) { // mobile ?>
		<td data-name="mobile"<?php echo $loan_application->mobile->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_mobile" class="loan_application_mobile">
<span<?php echo $loan_application->mobile->ViewAttributes() ?>>
<?php echo $loan_application->mobile->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->department->Visible) { // department ?>
		<td data-name="department"<?php echo $loan_application->department->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_department" class="loan_application_department">
<span<?php echo $loan_application->department->ViewAttributes() ?>>
<?php echo $loan_application->department->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->pension->Visible) { // pension ?>
		<td data-name="pension"<?php echo $loan_application->pension->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_pension" class="loan_application_pension">
<span<?php echo $loan_application->pension->ViewAttributes() ?>>
<?php echo $loan_application->pension->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->loan_amount->Visible) { // loan_amount ?>
		<td data-name="loan_amount"<?php echo $loan_application->loan_amount->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_loan_amount" class="loan_application_loan_amount">
<span<?php echo $loan_application->loan_amount->ViewAttributes() ?>>
<?php echo $loan_application->loan_amount->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->amount_inwords->Visible) { // amount_inwords ?>
		<td data-name="amount_inwords"<?php echo $loan_application->amount_inwords->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_amount_inwords" class="loan_application_amount_inwords">
<span<?php echo $loan_application->amount_inwords->ViewAttributes() ?>>
<?php echo $loan_application->amount_inwords->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->repayment_period->Visible) { // repayment_period ?>
		<td data-name="repayment_period"<?php echo $loan_application->repayment_period->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_repayment_period" class="loan_application_repayment_period">
<span<?php echo $loan_application->repayment_period->ViewAttributes() ?>>
<?php echo $loan_application->repayment_period->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->salary_permonth->Visible) { // salary_permonth ?>
		<td data-name="salary_permonth"<?php echo $loan_application->salary_permonth->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_salary_permonth" class="loan_application_salary_permonth">
<span<?php echo $loan_application->salary_permonth->ViewAttributes() ?>>
<?php echo $loan_application->salary_permonth->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->previous_loan->Visible) { // previous_loan ?>
		<td data-name="previous_loan"<?php echo $loan_application->previous_loan->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_previous_loan" class="loan_application_previous_loan">
<span<?php echo $loan_application->previous_loan->ViewAttributes() ?>>
<?php echo $loan_application->previous_loan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->date_collected->Visible) { // date_collected ?>
		<td data-name="date_collected"<?php echo $loan_application->date_collected->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_date_collected" class="loan_application_date_collected">
<span<?php echo $loan_application->date_collected->ViewAttributes() ?>>
<?php echo $loan_application->date_collected->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->date_liquidated->Visible) { // date_liquidated ?>
		<td data-name="date_liquidated"<?php echo $loan_application->date_liquidated->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_date_liquidated" class="loan_application_date_liquidated">
<span<?php echo $loan_application->date_liquidated->ViewAttributes() ?>>
<?php echo $loan_application->date_liquidated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->balance_remaining->Visible) { // balance_remaining ?>
		<td data-name="balance_remaining"<?php echo $loan_application->balance_remaining->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_balance_remaining" class="loan_application_balance_remaining">
<span<?php echo $loan_application->balance_remaining->ViewAttributes() ?>>
<?php echo $loan_application->balance_remaining->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->applicant_date->Visible) { // applicant_date ?>
		<td data-name="applicant_date"<?php echo $loan_application->applicant_date->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_applicant_date" class="loan_application_applicant_date">
<span<?php echo $loan_application->applicant_date->ViewAttributes() ?>>
<?php echo $loan_application->applicant_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->guarantor_name->Visible) { // guarantor_name ?>
		<td data-name="guarantor_name"<?php echo $loan_application->guarantor_name->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_guarantor_name" class="loan_application_guarantor_name">
<span<?php echo $loan_application->guarantor_name->ViewAttributes() ?>>
<?php echo $loan_application->guarantor_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->guarantor_address->Visible) { // guarantor_address ?>
		<td data-name="guarantor_address"<?php echo $loan_application->guarantor_address->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_guarantor_address" class="loan_application_guarantor_address">
<span<?php echo $loan_application->guarantor_address->ViewAttributes() ?>>
<?php echo $loan_application->guarantor_address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->guarantor_mobile->Visible) { // guarantor_mobile ?>
		<td data-name="guarantor_mobile"<?php echo $loan_application->guarantor_mobile->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_guarantor_mobile" class="loan_application_guarantor_mobile">
<span<?php echo $loan_application->guarantor_mobile->ViewAttributes() ?>>
<?php echo $loan_application->guarantor_mobile->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->status->Visible) { // status ?>
		<td data-name="status"<?php echo $loan_application->status->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_status" class="loan_application_status">
<span<?php echo $loan_application->status->ViewAttributes() ?>>
<?php echo $loan_application->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->application_status->Visible) { // application_status ?>
		<td data-name="application_status"<?php echo $loan_application->application_status->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_application_status" class="loan_application_application_status">
<span<?php echo $loan_application->application_status->ViewAttributes() ?>>
<?php echo $loan_application->application_status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->correction_date->Visible) { // correction_date ?>
		<td data-name="correction_date"<?php echo $loan_application->correction_date->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_correction_date" class="loan_application_correction_date">
<span<?php echo $loan_application->correction_date->ViewAttributes() ?>>
<?php echo $loan_application->correction_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->correction_action->Visible) { // correction_action ?>
		<td data-name="correction_action"<?php echo $loan_application->correction_action->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_correction_action" class="loan_application_correction_action">
<span<?php echo $loan_application->correction_action->ViewAttributes() ?>>
<?php echo $loan_application->correction_action->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->correction_comment->Visible) { // correction_comment ?>
		<td data-name="correction_comment"<?php echo $loan_application->correction_comment->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_correction_comment" class="loan_application_correction_comment">
<span<?php echo $loan_application->correction_comment->ViewAttributes() ?>>
<?php echo $loan_application->correction_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($loan_application->corrected_by->Visible) { // corrected_by ?>
		<td data-name="corrected_by"<?php echo $loan_application->corrected_by->CellAttributes() ?>>
<span id="el<?php echo $loan_application_list->RowCnt ?>_loan_application_corrected_by" class="loan_application_corrected_by">
<span<?php echo $loan_application->corrected_by->ViewAttributes() ?>>
<?php echo $loan_application->corrected_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$loan_application_list->ListOptions->Render("body", "right", $loan_application_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($loan_application->CurrentAction <> "gridadd")
		$loan_application_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($loan_application->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($loan_application_list->Recordset)
	$loan_application_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($loan_application_list->TotalRecs == 0 && $loan_application->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($loan_application_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($loan_application->Export == "") { ?>
<script type="text/javascript">
floan_applicationlistsrch.FilterList = <?php echo $loan_application_list->GetFilterList() ?>;
floan_applicationlistsrch.Init();
floan_applicationlist.Init();
</script>
<?php } ?>
<?php
$loan_application_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($loan_application->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$loan_application_list->Page_Terminate();
?>
