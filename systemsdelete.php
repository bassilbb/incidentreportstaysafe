<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "systemsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$systems_delete = NULL; // Initialize page object first

class csystems_delete extends csystems {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'systems';

	// Page object name
	var $PageObjName = 'systems_delete';

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

		// Table object (systems)
		if (!isset($GLOBALS["systems"]) || get_class($GLOBALS["systems"]) == "csystems") {
			$GLOBALS["systems"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["systems"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'systems', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("systemslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Create Token
		$this->CreateToken();
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
		global $EW_EXPORT, $systems;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($systems);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("systemslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in systems class, systemsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("systemslist.php"); // Return to list
			}
		}
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];

				// Delete old files
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("systemslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($systems_delete)) $systems_delete = new csystems_delete();

// Page init
$systems_delete->Page_Init();

// Page main
$systems_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$systems_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsystemsdelete = new ew_Form("fsystemsdelete", "delete");

// Form_CustomValidate event
fsystemsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsystemsdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $systems_delete->ShowPageHeader(); ?>
<?php
$systems_delete->ShowMessage();
?>
<form name="fsystemsdelete" id="fsystemsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($systems_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $systems_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="systems">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($systems_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($systems->id->Visible) { // id ?>
		<th class="<?php echo $systems->id->HeaderCellClass() ?>"><span id="elh_systems_id" class="systems_id"><?php echo $systems->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->asset_tag->Visible) { // asset_tag ?>
		<th class="<?php echo $systems->asset_tag->HeaderCellClass() ?>"><span id="elh_systems_asset_tag" class="systems_asset_tag"><?php echo $systems->asset_tag->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->start_sate->Visible) { // start_sate ?>
		<th class="<?php echo $systems->start_sate->HeaderCellClass() ?>"><span id="elh_systems_start_sate" class="systems_start_sate"><?php echo $systems->start_sate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->end_date->Visible) { // end_date ?>
		<th class="<?php echo $systems->end_date->HeaderCellClass() ?>"><span id="elh_systems_end_date" class="systems_end_date"><?php echo $systems->end_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->cost_for_repair->Visible) { // cost_for_repair ?>
		<th class="<?php echo $systems->cost_for_repair->HeaderCellClass() ?>"><span id="elh_systems_cost_for_repair" class="systems_cost_for_repair"><?php echo $systems->cost_for_repair->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->service_provider->Visible) { // service_provider ?>
		<th class="<?php echo $systems->service_provider->HeaderCellClass() ?>"><span id="elh_systems_service_provider" class="systems_service_provider"><?php echo $systems->service_provider->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->address->Visible) { // address ?>
		<th class="<?php echo $systems->address->HeaderCellClass() ?>"><span id="elh_systems_address" class="systems_address"><?php echo $systems->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->type_of_repair->Visible) { // type_of_repair ?>
		<th class="<?php echo $systems->type_of_repair->HeaderCellClass() ?>"><span id="elh_systems_type_of_repair" class="systems_type_of_repair"><?php echo $systems->type_of_repair->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->note->Visible) { // note ?>
		<th class="<?php echo $systems->note->HeaderCellClass() ?>"><span id="elh_systems_note" class="systems_note"><?php echo $systems->note->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->status->Visible) { // status ?>
		<th class="<?php echo $systems->status->HeaderCellClass() ?>"><span id="elh_systems_status" class="systems_status"><?php echo $systems->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->asset_category->Visible) { // asset_category ?>
		<th class="<?php echo $systems->asset_category->HeaderCellClass() ?>"><span id="elh_systems_asset_category" class="systems_asset_category"><?php echo $systems->asset_category->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->asset_sub_category->Visible) { // asset_sub_category ?>
		<th class="<?php echo $systems->asset_sub_category->HeaderCellClass() ?>"><span id="elh_systems_asset_sub_category" class="systems_asset_sub_category"><?php echo $systems->asset_sub_category->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->serial_number->Visible) { // serial_number ?>
		<th class="<?php echo $systems->serial_number->HeaderCellClass() ?>"><span id="elh_systems_serial_number" class="systems_serial_number"><?php echo $systems->serial_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->programe_area->Visible) { // programe_area ?>
		<th class="<?php echo $systems->programe_area->HeaderCellClass() ?>"><span id="elh_systems_programe_area" class="systems_programe_area"><?php echo $systems->programe_area->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->division->Visible) { // division ?>
		<th class="<?php echo $systems->division->HeaderCellClass() ?>"><span id="elh_systems_division" class="systems_division"><?php echo $systems->division->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->branch->Visible) { // branch ?>
		<th class="<?php echo $systems->branch->HeaderCellClass() ?>"><span id="elh_systems_branch" class="systems_branch"><?php echo $systems->branch->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->department->Visible) { // department ?>
		<th class="<?php echo $systems->department->HeaderCellClass() ?>"><span id="elh_systems_department" class="systems_department"><?php echo $systems->department->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->staff_id->Visible) { // staff_id ?>
		<th class="<?php echo $systems->staff_id->HeaderCellClass() ?>"><span id="elh_systems_staff_id" class="systems_staff_id"><?php echo $systems->staff_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->created_by->Visible) { // created_by ?>
		<th class="<?php echo $systems->created_by->HeaderCellClass() ?>"><span id="elh_systems_created_by" class="systems_created_by"><?php echo $systems->created_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->created_date->Visible) { // created_date ?>
		<th class="<?php echo $systems->created_date->HeaderCellClass() ?>"><span id="elh_systems_created_date" class="systems_created_date"><?php echo $systems->created_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->device_number->Visible) { // device_number ?>
		<th class="<?php echo $systems->device_number->HeaderCellClass() ?>"><span id="elh_systems_device_number" class="systems_device_number"><?php echo $systems->device_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->tablet_imie_number->Visible) { // tablet_imie_number ?>
		<th class="<?php echo $systems->tablet_imie_number->HeaderCellClass() ?>"><span id="elh_systems_tablet_imie_number" class="systems_tablet_imie_number"><?php echo $systems->tablet_imie_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->model->Visible) { // model ?>
		<th class="<?php echo $systems->model->HeaderCellClass() ?>"><span id="elh_systems_model" class="systems_model"><?php echo $systems->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->flag->Visible) { // flag ?>
		<th class="<?php echo $systems->flag->HeaderCellClass() ?>"><span id="elh_systems_flag" class="systems_flag"><?php echo $systems->flag->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->area->Visible) { // area ?>
		<th class="<?php echo $systems->area->HeaderCellClass() ?>"><span id="elh_systems_area" class="systems_area"><?php echo $systems->area->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->updated_date->Visible) { // updated_date ?>
		<th class="<?php echo $systems->updated_date->HeaderCellClass() ?>"><span id="elh_systems_updated_date" class="systems_updated_date"><?php echo $systems->updated_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->updated_by->Visible) { // updated_by ?>
		<th class="<?php echo $systems->updated_by->HeaderCellClass() ?>"><span id="elh_systems_updated_by" class="systems_updated_by"><?php echo $systems->updated_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->received_date->Visible) { // received_date ?>
		<th class="<?php echo $systems->received_date->HeaderCellClass() ?>"><span id="elh_systems_received_date" class="systems_received_date"><?php echo $systems->received_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($systems->received_by->Visible) { // received_by ?>
		<th class="<?php echo $systems->received_by->HeaderCellClass() ?>"><span id="elh_systems_received_by" class="systems_received_by"><?php echo $systems->received_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$systems_delete->RecCnt = 0;
$i = 0;
while (!$systems_delete->Recordset->EOF) {
	$systems_delete->RecCnt++;
	$systems_delete->RowCnt++;

	// Set row properties
	$systems->ResetAttrs();
	$systems->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$systems_delete->LoadRowValues($systems_delete->Recordset);

	// Render row
	$systems_delete->RenderRow();
?>
	<tr<?php echo $systems->RowAttributes() ?>>
<?php if ($systems->id->Visible) { // id ?>
		<td<?php echo $systems->id->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_id" class="systems_id">
<span<?php echo $systems->id->ViewAttributes() ?>>
<?php echo $systems->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->asset_tag->Visible) { // asset_tag ?>
		<td<?php echo $systems->asset_tag->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_asset_tag" class="systems_asset_tag">
<span<?php echo $systems->asset_tag->ViewAttributes() ?>>
<?php echo $systems->asset_tag->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->start_sate->Visible) { // start_sate ?>
		<td<?php echo $systems->start_sate->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_start_sate" class="systems_start_sate">
<span<?php echo $systems->start_sate->ViewAttributes() ?>>
<?php echo $systems->start_sate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->end_date->Visible) { // end_date ?>
		<td<?php echo $systems->end_date->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_end_date" class="systems_end_date">
<span<?php echo $systems->end_date->ViewAttributes() ?>>
<?php echo $systems->end_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->cost_for_repair->Visible) { // cost_for_repair ?>
		<td<?php echo $systems->cost_for_repair->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_cost_for_repair" class="systems_cost_for_repair">
<span<?php echo $systems->cost_for_repair->ViewAttributes() ?>>
<?php echo $systems->cost_for_repair->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->service_provider->Visible) { // service_provider ?>
		<td<?php echo $systems->service_provider->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_service_provider" class="systems_service_provider">
<span<?php echo $systems->service_provider->ViewAttributes() ?>>
<?php echo $systems->service_provider->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->address->Visible) { // address ?>
		<td<?php echo $systems->address->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_address" class="systems_address">
<span<?php echo $systems->address->ViewAttributes() ?>>
<?php echo $systems->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->type_of_repair->Visible) { // type_of_repair ?>
		<td<?php echo $systems->type_of_repair->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_type_of_repair" class="systems_type_of_repair">
<span<?php echo $systems->type_of_repair->ViewAttributes() ?>>
<?php echo $systems->type_of_repair->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->note->Visible) { // note ?>
		<td<?php echo $systems->note->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_note" class="systems_note">
<span<?php echo $systems->note->ViewAttributes() ?>>
<?php echo $systems->note->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->status->Visible) { // status ?>
		<td<?php echo $systems->status->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_status" class="systems_status">
<span<?php echo $systems->status->ViewAttributes() ?>>
<?php echo $systems->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->asset_category->Visible) { // asset_category ?>
		<td<?php echo $systems->asset_category->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_asset_category" class="systems_asset_category">
<span<?php echo $systems->asset_category->ViewAttributes() ?>>
<?php echo $systems->asset_category->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->asset_sub_category->Visible) { // asset_sub_category ?>
		<td<?php echo $systems->asset_sub_category->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_asset_sub_category" class="systems_asset_sub_category">
<span<?php echo $systems->asset_sub_category->ViewAttributes() ?>>
<?php echo $systems->asset_sub_category->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->serial_number->Visible) { // serial_number ?>
		<td<?php echo $systems->serial_number->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_serial_number" class="systems_serial_number">
<span<?php echo $systems->serial_number->ViewAttributes() ?>>
<?php echo $systems->serial_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->programe_area->Visible) { // programe_area ?>
		<td<?php echo $systems->programe_area->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_programe_area" class="systems_programe_area">
<span<?php echo $systems->programe_area->ViewAttributes() ?>>
<?php echo $systems->programe_area->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->division->Visible) { // division ?>
		<td<?php echo $systems->division->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_division" class="systems_division">
<span<?php echo $systems->division->ViewAttributes() ?>>
<?php echo $systems->division->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->branch->Visible) { // branch ?>
		<td<?php echo $systems->branch->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_branch" class="systems_branch">
<span<?php echo $systems->branch->ViewAttributes() ?>>
<?php echo $systems->branch->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->department->Visible) { // department ?>
		<td<?php echo $systems->department->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_department" class="systems_department">
<span<?php echo $systems->department->ViewAttributes() ?>>
<?php echo $systems->department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->staff_id->Visible) { // staff_id ?>
		<td<?php echo $systems->staff_id->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_staff_id" class="systems_staff_id">
<span<?php echo $systems->staff_id->ViewAttributes() ?>>
<?php echo $systems->staff_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->created_by->Visible) { // created_by ?>
		<td<?php echo $systems->created_by->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_created_by" class="systems_created_by">
<span<?php echo $systems->created_by->ViewAttributes() ?>>
<?php echo $systems->created_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->created_date->Visible) { // created_date ?>
		<td<?php echo $systems->created_date->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_created_date" class="systems_created_date">
<span<?php echo $systems->created_date->ViewAttributes() ?>>
<?php echo $systems->created_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->device_number->Visible) { // device_number ?>
		<td<?php echo $systems->device_number->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_device_number" class="systems_device_number">
<span<?php echo $systems->device_number->ViewAttributes() ?>>
<?php echo $systems->device_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->tablet_imie_number->Visible) { // tablet_imie_number ?>
		<td<?php echo $systems->tablet_imie_number->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_tablet_imie_number" class="systems_tablet_imie_number">
<span<?php echo $systems->tablet_imie_number->ViewAttributes() ?>>
<?php echo $systems->tablet_imie_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->model->Visible) { // model ?>
		<td<?php echo $systems->model->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_model" class="systems_model">
<span<?php echo $systems->model->ViewAttributes() ?>>
<?php echo $systems->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->flag->Visible) { // flag ?>
		<td<?php echo $systems->flag->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_flag" class="systems_flag">
<span<?php echo $systems->flag->ViewAttributes() ?>>
<?php echo $systems->flag->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->area->Visible) { // area ?>
		<td<?php echo $systems->area->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_area" class="systems_area">
<span<?php echo $systems->area->ViewAttributes() ?>>
<?php echo $systems->area->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->updated_date->Visible) { // updated_date ?>
		<td<?php echo $systems->updated_date->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_updated_date" class="systems_updated_date">
<span<?php echo $systems->updated_date->ViewAttributes() ?>>
<?php echo $systems->updated_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->updated_by->Visible) { // updated_by ?>
		<td<?php echo $systems->updated_by->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_updated_by" class="systems_updated_by">
<span<?php echo $systems->updated_by->ViewAttributes() ?>>
<?php echo $systems->updated_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->received_date->Visible) { // received_date ?>
		<td<?php echo $systems->received_date->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_received_date" class="systems_received_date">
<span<?php echo $systems->received_date->ViewAttributes() ?>>
<?php echo $systems->received_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($systems->received_by->Visible) { // received_by ?>
		<td<?php echo $systems->received_by->CellAttributes() ?>>
<span id="el<?php echo $systems_delete->RowCnt ?>_systems_received_by" class="systems_received_by">
<span<?php echo $systems->received_by->ViewAttributes() ?>>
<?php echo $systems->received_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$systems_delete->Recordset->MoveNext();
}
$systems_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $systems_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsystemsdelete.Init();
</script>
<?php
$systems_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$systems_delete->Page_Terminate();
?>
