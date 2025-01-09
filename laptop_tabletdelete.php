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

$laptop_tablet_delete = NULL; // Initialize page object first

class claptop_tablet_delete extends claptop_tablet {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'laptop_tablet';

	// Page object name
	var $PageObjName = 'laptop_tablet_delete';

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

		// Table object (laptop_tablet)
		if (!isset($GLOBALS["laptop_tablet"]) || get_class($GLOBALS["laptop_tablet"]) == "claptop_tablet") {
			$GLOBALS["laptop_tablet"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["laptop_tablet"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("laptop_tabletlist.php"));
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
			$this->Page_Terminate("laptop_tabletlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in laptop_tablet class, laptop_tabletinfo.php

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
				$this->Page_Terminate("laptop_tabletlist.php"); // Return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("laptop_tabletlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($laptop_tablet_delete)) $laptop_tablet_delete = new claptop_tablet_delete();

// Page init
$laptop_tablet_delete->Page_Init();

// Page main
$laptop_tablet_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laptop_tablet_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = flaptop_tabletdelete = new ew_Form("flaptop_tabletdelete", "delete");

// Form_CustomValidate event
flaptop_tabletdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flaptop_tabletdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $laptop_tablet_delete->ShowPageHeader(); ?>
<?php
$laptop_tablet_delete->ShowMessage();
?>
<form name="flaptop_tabletdelete" id="flaptop_tabletdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($laptop_tablet_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $laptop_tablet_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="laptop_tablet">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($laptop_tablet_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($laptop_tablet->id->Visible) { // id ?>
		<th class="<?php echo $laptop_tablet->id->HeaderCellClass() ?>"><span id="elh_laptop_tablet_id" class="laptop_tablet_id"><?php echo $laptop_tablet->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->asset_tag->Visible) { // asset_tag ?>
		<th class="<?php echo $laptop_tablet->asset_tag->HeaderCellClass() ?>"><span id="elh_laptop_tablet_asset_tag" class="laptop_tablet_asset_tag"><?php echo $laptop_tablet->asset_tag->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->start_sate->Visible) { // start_sate ?>
		<th class="<?php echo $laptop_tablet->start_sate->HeaderCellClass() ?>"><span id="elh_laptop_tablet_start_sate" class="laptop_tablet_start_sate"><?php echo $laptop_tablet->start_sate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->end_date->Visible) { // end_date ?>
		<th class="<?php echo $laptop_tablet->end_date->HeaderCellClass() ?>"><span id="elh_laptop_tablet_end_date" class="laptop_tablet_end_date"><?php echo $laptop_tablet->end_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->cost_for_repair->Visible) { // cost_for_repair ?>
		<th class="<?php echo $laptop_tablet->cost_for_repair->HeaderCellClass() ?>"><span id="elh_laptop_tablet_cost_for_repair" class="laptop_tablet_cost_for_repair"><?php echo $laptop_tablet->cost_for_repair->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->service_provider->Visible) { // service_provider ?>
		<th class="<?php echo $laptop_tablet->service_provider->HeaderCellClass() ?>"><span id="elh_laptop_tablet_service_provider" class="laptop_tablet_service_provider"><?php echo $laptop_tablet->service_provider->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->address->Visible) { // address ?>
		<th class="<?php echo $laptop_tablet->address->HeaderCellClass() ?>"><span id="elh_laptop_tablet_address" class="laptop_tablet_address"><?php echo $laptop_tablet->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->type_of_repair->Visible) { // type_of_repair ?>
		<th class="<?php echo $laptop_tablet->type_of_repair->HeaderCellClass() ?>"><span id="elh_laptop_tablet_type_of_repair" class="laptop_tablet_type_of_repair"><?php echo $laptop_tablet->type_of_repair->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->note->Visible) { // note ?>
		<th class="<?php echo $laptop_tablet->note->HeaderCellClass() ?>"><span id="elh_laptop_tablet_note" class="laptop_tablet_note"><?php echo $laptop_tablet->note->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->status->Visible) { // status ?>
		<th class="<?php echo $laptop_tablet->status->HeaderCellClass() ?>"><span id="elh_laptop_tablet_status" class="laptop_tablet_status"><?php echo $laptop_tablet->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->asset_category->Visible) { // asset_category ?>
		<th class="<?php echo $laptop_tablet->asset_category->HeaderCellClass() ?>"><span id="elh_laptop_tablet_asset_category" class="laptop_tablet_asset_category"><?php echo $laptop_tablet->asset_category->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->asset_sub_category->Visible) { // asset_sub_category ?>
		<th class="<?php echo $laptop_tablet->asset_sub_category->HeaderCellClass() ?>"><span id="elh_laptop_tablet_asset_sub_category" class="laptop_tablet_asset_sub_category"><?php echo $laptop_tablet->asset_sub_category->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->serial_number->Visible) { // serial_number ?>
		<th class="<?php echo $laptop_tablet->serial_number->HeaderCellClass() ?>"><span id="elh_laptop_tablet_serial_number" class="laptop_tablet_serial_number"><?php echo $laptop_tablet->serial_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->programe_area->Visible) { // programe_area ?>
		<th class="<?php echo $laptop_tablet->programe_area->HeaderCellClass() ?>"><span id="elh_laptop_tablet_programe_area" class="laptop_tablet_programe_area"><?php echo $laptop_tablet->programe_area->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->division->Visible) { // division ?>
		<th class="<?php echo $laptop_tablet->division->HeaderCellClass() ?>"><span id="elh_laptop_tablet_division" class="laptop_tablet_division"><?php echo $laptop_tablet->division->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->branch->Visible) { // branch ?>
		<th class="<?php echo $laptop_tablet->branch->HeaderCellClass() ?>"><span id="elh_laptop_tablet_branch" class="laptop_tablet_branch"><?php echo $laptop_tablet->branch->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->department->Visible) { // department ?>
		<th class="<?php echo $laptop_tablet->department->HeaderCellClass() ?>"><span id="elh_laptop_tablet_department" class="laptop_tablet_department"><?php echo $laptop_tablet->department->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->staff_id->Visible) { // staff_id ?>
		<th class="<?php echo $laptop_tablet->staff_id->HeaderCellClass() ?>"><span id="elh_laptop_tablet_staff_id" class="laptop_tablet_staff_id"><?php echo $laptop_tablet->staff_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->created_by->Visible) { // created_by ?>
		<th class="<?php echo $laptop_tablet->created_by->HeaderCellClass() ?>"><span id="elh_laptop_tablet_created_by" class="laptop_tablet_created_by"><?php echo $laptop_tablet->created_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->created_date->Visible) { // created_date ?>
		<th class="<?php echo $laptop_tablet->created_date->HeaderCellClass() ?>"><span id="elh_laptop_tablet_created_date" class="laptop_tablet_created_date"><?php echo $laptop_tablet->created_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->device_number->Visible) { // device_number ?>
		<th class="<?php echo $laptop_tablet->device_number->HeaderCellClass() ?>"><span id="elh_laptop_tablet_device_number" class="laptop_tablet_device_number"><?php echo $laptop_tablet->device_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->tablet_imie_number->Visible) { // tablet_imie_number ?>
		<th class="<?php echo $laptop_tablet->tablet_imie_number->HeaderCellClass() ?>"><span id="elh_laptop_tablet_tablet_imie_number" class="laptop_tablet_tablet_imie_number"><?php echo $laptop_tablet->tablet_imie_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->model->Visible) { // model ?>
		<th class="<?php echo $laptop_tablet->model->HeaderCellClass() ?>"><span id="elh_laptop_tablet_model" class="laptop_tablet_model"><?php echo $laptop_tablet->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->flag->Visible) { // flag ?>
		<th class="<?php echo $laptop_tablet->flag->HeaderCellClass() ?>"><span id="elh_laptop_tablet_flag" class="laptop_tablet_flag"><?php echo $laptop_tablet->flag->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->area->Visible) { // area ?>
		<th class="<?php echo $laptop_tablet->area->HeaderCellClass() ?>"><span id="elh_laptop_tablet_area" class="laptop_tablet_area"><?php echo $laptop_tablet->area->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->updated_date->Visible) { // updated_date ?>
		<th class="<?php echo $laptop_tablet->updated_date->HeaderCellClass() ?>"><span id="elh_laptop_tablet_updated_date" class="laptop_tablet_updated_date"><?php echo $laptop_tablet->updated_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->updated_by->Visible) { // updated_by ?>
		<th class="<?php echo $laptop_tablet->updated_by->HeaderCellClass() ?>"><span id="elh_laptop_tablet_updated_by" class="laptop_tablet_updated_by"><?php echo $laptop_tablet->updated_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->received_date->Visible) { // received_date ?>
		<th class="<?php echo $laptop_tablet->received_date->HeaderCellClass() ?>"><span id="elh_laptop_tablet_received_date" class="laptop_tablet_received_date"><?php echo $laptop_tablet->received_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($laptop_tablet->received_by->Visible) { // received_by ?>
		<th class="<?php echo $laptop_tablet->received_by->HeaderCellClass() ?>"><span id="elh_laptop_tablet_received_by" class="laptop_tablet_received_by"><?php echo $laptop_tablet->received_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$laptop_tablet_delete->RecCnt = 0;
$i = 0;
while (!$laptop_tablet_delete->Recordset->EOF) {
	$laptop_tablet_delete->RecCnt++;
	$laptop_tablet_delete->RowCnt++;

	// Set row properties
	$laptop_tablet->ResetAttrs();
	$laptop_tablet->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$laptop_tablet_delete->LoadRowValues($laptop_tablet_delete->Recordset);

	// Render row
	$laptop_tablet_delete->RenderRow();
?>
	<tr<?php echo $laptop_tablet->RowAttributes() ?>>
<?php if ($laptop_tablet->id->Visible) { // id ?>
		<td<?php echo $laptop_tablet->id->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_id" class="laptop_tablet_id">
<span<?php echo $laptop_tablet->id->ViewAttributes() ?>>
<?php echo $laptop_tablet->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->asset_tag->Visible) { // asset_tag ?>
		<td<?php echo $laptop_tablet->asset_tag->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_asset_tag" class="laptop_tablet_asset_tag">
<span<?php echo $laptop_tablet->asset_tag->ViewAttributes() ?>>
<?php echo $laptop_tablet->asset_tag->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->start_sate->Visible) { // start_sate ?>
		<td<?php echo $laptop_tablet->start_sate->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_start_sate" class="laptop_tablet_start_sate">
<span<?php echo $laptop_tablet->start_sate->ViewAttributes() ?>>
<?php echo $laptop_tablet->start_sate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->end_date->Visible) { // end_date ?>
		<td<?php echo $laptop_tablet->end_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_end_date" class="laptop_tablet_end_date">
<span<?php echo $laptop_tablet->end_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->end_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->cost_for_repair->Visible) { // cost_for_repair ?>
		<td<?php echo $laptop_tablet->cost_for_repair->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_cost_for_repair" class="laptop_tablet_cost_for_repair">
<span<?php echo $laptop_tablet->cost_for_repair->ViewAttributes() ?>>
<?php echo $laptop_tablet->cost_for_repair->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->service_provider->Visible) { // service_provider ?>
		<td<?php echo $laptop_tablet->service_provider->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_service_provider" class="laptop_tablet_service_provider">
<span<?php echo $laptop_tablet->service_provider->ViewAttributes() ?>>
<?php echo $laptop_tablet->service_provider->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->address->Visible) { // address ?>
		<td<?php echo $laptop_tablet->address->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_address" class="laptop_tablet_address">
<span<?php echo $laptop_tablet->address->ViewAttributes() ?>>
<?php echo $laptop_tablet->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->type_of_repair->Visible) { // type_of_repair ?>
		<td<?php echo $laptop_tablet->type_of_repair->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_type_of_repair" class="laptop_tablet_type_of_repair">
<span<?php echo $laptop_tablet->type_of_repair->ViewAttributes() ?>>
<?php echo $laptop_tablet->type_of_repair->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->note->Visible) { // note ?>
		<td<?php echo $laptop_tablet->note->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_note" class="laptop_tablet_note">
<span<?php echo $laptop_tablet->note->ViewAttributes() ?>>
<?php echo $laptop_tablet->note->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->status->Visible) { // status ?>
		<td<?php echo $laptop_tablet->status->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_status" class="laptop_tablet_status">
<span<?php echo $laptop_tablet->status->ViewAttributes() ?>>
<?php echo $laptop_tablet->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->asset_category->Visible) { // asset_category ?>
		<td<?php echo $laptop_tablet->asset_category->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_asset_category" class="laptop_tablet_asset_category">
<span<?php echo $laptop_tablet->asset_category->ViewAttributes() ?>>
<?php echo $laptop_tablet->asset_category->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->asset_sub_category->Visible) { // asset_sub_category ?>
		<td<?php echo $laptop_tablet->asset_sub_category->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_asset_sub_category" class="laptop_tablet_asset_sub_category">
<span<?php echo $laptop_tablet->asset_sub_category->ViewAttributes() ?>>
<?php echo $laptop_tablet->asset_sub_category->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->serial_number->Visible) { // serial_number ?>
		<td<?php echo $laptop_tablet->serial_number->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_serial_number" class="laptop_tablet_serial_number">
<span<?php echo $laptop_tablet->serial_number->ViewAttributes() ?>>
<?php echo $laptop_tablet->serial_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->programe_area->Visible) { // programe_area ?>
		<td<?php echo $laptop_tablet->programe_area->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_programe_area" class="laptop_tablet_programe_area">
<span<?php echo $laptop_tablet->programe_area->ViewAttributes() ?>>
<?php echo $laptop_tablet->programe_area->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->division->Visible) { // division ?>
		<td<?php echo $laptop_tablet->division->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_division" class="laptop_tablet_division">
<span<?php echo $laptop_tablet->division->ViewAttributes() ?>>
<?php echo $laptop_tablet->division->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->branch->Visible) { // branch ?>
		<td<?php echo $laptop_tablet->branch->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_branch" class="laptop_tablet_branch">
<span<?php echo $laptop_tablet->branch->ViewAttributes() ?>>
<?php echo $laptop_tablet->branch->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->department->Visible) { // department ?>
		<td<?php echo $laptop_tablet->department->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_department" class="laptop_tablet_department">
<span<?php echo $laptop_tablet->department->ViewAttributes() ?>>
<?php echo $laptop_tablet->department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->staff_id->Visible) { // staff_id ?>
		<td<?php echo $laptop_tablet->staff_id->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_staff_id" class="laptop_tablet_staff_id">
<span<?php echo $laptop_tablet->staff_id->ViewAttributes() ?>>
<?php echo $laptop_tablet->staff_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->created_by->Visible) { // created_by ?>
		<td<?php echo $laptop_tablet->created_by->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_created_by" class="laptop_tablet_created_by">
<span<?php echo $laptop_tablet->created_by->ViewAttributes() ?>>
<?php echo $laptop_tablet->created_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->created_date->Visible) { // created_date ?>
		<td<?php echo $laptop_tablet->created_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_created_date" class="laptop_tablet_created_date">
<span<?php echo $laptop_tablet->created_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->created_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->device_number->Visible) { // device_number ?>
		<td<?php echo $laptop_tablet->device_number->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_device_number" class="laptop_tablet_device_number">
<span<?php echo $laptop_tablet->device_number->ViewAttributes() ?>>
<?php echo $laptop_tablet->device_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->tablet_imie_number->Visible) { // tablet_imie_number ?>
		<td<?php echo $laptop_tablet->tablet_imie_number->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_tablet_imie_number" class="laptop_tablet_tablet_imie_number">
<span<?php echo $laptop_tablet->tablet_imie_number->ViewAttributes() ?>>
<?php echo $laptop_tablet->tablet_imie_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->model->Visible) { // model ?>
		<td<?php echo $laptop_tablet->model->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_model" class="laptop_tablet_model">
<span<?php echo $laptop_tablet->model->ViewAttributes() ?>>
<?php echo $laptop_tablet->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->flag->Visible) { // flag ?>
		<td<?php echo $laptop_tablet->flag->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_flag" class="laptop_tablet_flag">
<span<?php echo $laptop_tablet->flag->ViewAttributes() ?>>
<?php echo $laptop_tablet->flag->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->area->Visible) { // area ?>
		<td<?php echo $laptop_tablet->area->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_area" class="laptop_tablet_area">
<span<?php echo $laptop_tablet->area->ViewAttributes() ?>>
<?php echo $laptop_tablet->area->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->updated_date->Visible) { // updated_date ?>
		<td<?php echo $laptop_tablet->updated_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_updated_date" class="laptop_tablet_updated_date">
<span<?php echo $laptop_tablet->updated_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->updated_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->updated_by->Visible) { // updated_by ?>
		<td<?php echo $laptop_tablet->updated_by->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_updated_by" class="laptop_tablet_updated_by">
<span<?php echo $laptop_tablet->updated_by->ViewAttributes() ?>>
<?php echo $laptop_tablet->updated_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->received_date->Visible) { // received_date ?>
		<td<?php echo $laptop_tablet->received_date->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_received_date" class="laptop_tablet_received_date">
<span<?php echo $laptop_tablet->received_date->ViewAttributes() ?>>
<?php echo $laptop_tablet->received_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($laptop_tablet->received_by->Visible) { // received_by ?>
		<td<?php echo $laptop_tablet->received_by->CellAttributes() ?>>
<span id="el<?php echo $laptop_tablet_delete->RowCnt ?>_laptop_tablet_received_by" class="laptop_tablet_received_by">
<span<?php echo $laptop_tablet->received_by->ViewAttributes() ?>>
<?php echo $laptop_tablet->received_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$laptop_tablet_delete->Recordset->MoveNext();
}
$laptop_tablet_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $laptop_tablet_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
flaptop_tabletdelete.Init();
</script>
<?php
$laptop_tablet_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$laptop_tablet_delete->Page_Terminate();
?>
