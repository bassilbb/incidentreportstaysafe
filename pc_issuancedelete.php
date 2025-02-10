<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pc_issuanceinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pc_issuance_delete = NULL; // Initialize page object first

class cpc_issuance_delete extends cpc_issuance {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'pc_issuance';

	// Page object name
	var $PageObjName = 'pc_issuance_delete';

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

		// Table object (pc_issuance)
		if (!isset($GLOBALS["pc_issuance"]) || get_class($GLOBALS["pc_issuance"]) == "cpc_issuance") {
			$GLOBALS["pc_issuance"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pc_issuance"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pc_issuance', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("pc_issuancelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->issued_date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->asset_tag->SetVisibility();
		$this->make->SetVisibility();
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
		global $EW_EXPORT, $pc_issuance;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pc_issuance);
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
			$this->Page_Terminate("pc_issuancelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pc_issuance class, pc_issuanceinfo.php

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
				$this->Page_Terminate("pc_issuancelist.php"); // Return to list
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
		$this->issued_date->setDbValue($row['issued_date']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->asset_tag->setDbValue($row['asset_tag']);
		$this->make->setDbValue($row['make']);
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// issued_date
		// reference_id
		// asset_tag
		// make
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
		$this->issued_date->ViewValue = ew_FormatDateTime($this->issued_date->ViewValue, 17);
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
		$this->assign_by->LookupFilters = array();
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
		$this->retrieved_by->LookupFilters = array();
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

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";
			$this->asset_tag->TooltipValue = "";

			// make
			$this->make->LinkCustomAttributes = "";
			$this->make->HrefValue = "";
			$this->make->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pc_issuancelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($pc_issuance_delete)) $pc_issuance_delete = new cpc_issuance_delete();

// Page init
$pc_issuance_delete->Page_Init();

// Page main
$pc_issuance_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pc_issuance_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpc_issuancedelete = new ew_Form("fpc_issuancedelete", "delete");

// Form_CustomValidate event
fpc_issuancedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpc_issuancedelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpc_issuancedelete.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fpc_issuancedelete.Lists["x_department"].Data = "<?php echo $pc_issuance_delete->department->LookupFilterQuery(FALSE, "delete") ?>";
fpc_issuancedelete.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
fpc_issuancedelete.Lists["x_designation"].Data = "<?php echo $pc_issuance_delete->designation->LookupFilterQuery(FALSE, "delete") ?>";
fpc_issuancedelete.Lists["x_assign_to"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuancedelete.Lists["x_assign_to"].Data = "<?php echo $pc_issuance_delete->assign_to->LookupFilterQuery(FALSE, "delete") ?>";
fpc_issuancedelete.Lists["x_assign_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuancedelete.Lists["x_assign_by"].Data = "<?php echo $pc_issuance_delete->assign_by->LookupFilterQuery(FALSE, "delete") ?>";
fpc_issuancedelete.Lists["x_statuse[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"system_status"};
fpc_issuancedelete.Lists["x_statuse[]"].Data = "<?php echo $pc_issuance_delete->statuse->LookupFilterQuery(FALSE, "delete") ?>";
fpc_issuancedelete.Lists["x_retrieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuancedelete.Lists["x_retrieved_by"].Data = "<?php echo $pc_issuance_delete->retrieved_by->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pc_issuance_delete->ShowPageHeader(); ?>
<?php
$pc_issuance_delete->ShowMessage();
?>
<form name="fpc_issuancedelete" id="fpc_issuancedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pc_issuance_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pc_issuance_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pc_issuance">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pc_issuance_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($pc_issuance->issued_date->Visible) { // issued_date ?>
		<th class="<?php echo $pc_issuance->issued_date->HeaderCellClass() ?>"><span id="elh_pc_issuance_issued_date" class="pc_issuance_issued_date"><?php echo $pc_issuance->issued_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->reference_id->Visible) { // reference_id ?>
		<th class="<?php echo $pc_issuance->reference_id->HeaderCellClass() ?>"><span id="elh_pc_issuance_reference_id" class="pc_issuance_reference_id"><?php echo $pc_issuance->reference_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->asset_tag->Visible) { // asset_tag ?>
		<th class="<?php echo $pc_issuance->asset_tag->HeaderCellClass() ?>"><span id="elh_pc_issuance_asset_tag" class="pc_issuance_asset_tag"><?php echo $pc_issuance->asset_tag->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->make->Visible) { // make ?>
		<th class="<?php echo $pc_issuance->make->HeaderCellClass() ?>"><span id="elh_pc_issuance_make" class="pc_issuance_make"><?php echo $pc_issuance->make->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->color->Visible) { // color ?>
		<th class="<?php echo $pc_issuance->color->HeaderCellClass() ?>"><span id="elh_pc_issuance_color" class="pc_issuance_color"><?php echo $pc_issuance->color->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->department->Visible) { // department ?>
		<th class="<?php echo $pc_issuance->department->HeaderCellClass() ?>"><span id="elh_pc_issuance_department" class="pc_issuance_department"><?php echo $pc_issuance->department->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->designation->Visible) { // designation ?>
		<th class="<?php echo $pc_issuance->designation->HeaderCellClass() ?>"><span id="elh_pc_issuance_designation" class="pc_issuance_designation"><?php echo $pc_issuance->designation->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->assign_to->Visible) { // assign_to ?>
		<th class="<?php echo $pc_issuance->assign_to->HeaderCellClass() ?>"><span id="elh_pc_issuance_assign_to" class="pc_issuance_assign_to"><?php echo $pc_issuance->assign_to->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->date_assign->Visible) { // date_assign ?>
		<th class="<?php echo $pc_issuance->date_assign->HeaderCellClass() ?>"><span id="elh_pc_issuance_date_assign" class="pc_issuance_date_assign"><?php echo $pc_issuance->date_assign->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->assign_by->Visible) { // assign_by ?>
		<th class="<?php echo $pc_issuance->assign_by->HeaderCellClass() ?>"><span id="elh_pc_issuance_assign_by" class="pc_issuance_assign_by"><?php echo $pc_issuance->assign_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->statuse->Visible) { // statuse ?>
		<th class="<?php echo $pc_issuance->statuse->HeaderCellClass() ?>"><span id="elh_pc_issuance_statuse" class="pc_issuance_statuse"><?php echo $pc_issuance->statuse->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->date_retrieved->Visible) { // date_retrieved ?>
		<th class="<?php echo $pc_issuance->date_retrieved->HeaderCellClass() ?>"><span id="elh_pc_issuance_date_retrieved" class="pc_issuance_date_retrieved"><?php echo $pc_issuance->date_retrieved->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pc_issuance->retrieved_by->Visible) { // retrieved_by ?>
		<th class="<?php echo $pc_issuance->retrieved_by->HeaderCellClass() ?>"><span id="elh_pc_issuance_retrieved_by" class="pc_issuance_retrieved_by"><?php echo $pc_issuance->retrieved_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pc_issuance_delete->RecCnt = 0;
$i = 0;
while (!$pc_issuance_delete->Recordset->EOF) {
	$pc_issuance_delete->RecCnt++;
	$pc_issuance_delete->RowCnt++;

	// Set row properties
	$pc_issuance->ResetAttrs();
	$pc_issuance->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pc_issuance_delete->LoadRowValues($pc_issuance_delete->Recordset);

	// Render row
	$pc_issuance_delete->RenderRow();
?>
	<tr<?php echo $pc_issuance->RowAttributes() ?>>
<?php if ($pc_issuance->issued_date->Visible) { // issued_date ?>
		<td<?php echo $pc_issuance->issued_date->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_issued_date" class="pc_issuance_issued_date">
<span<?php echo $pc_issuance->issued_date->ViewAttributes() ?>>
<?php echo $pc_issuance->issued_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->reference_id->Visible) { // reference_id ?>
		<td<?php echo $pc_issuance->reference_id->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_reference_id" class="pc_issuance_reference_id">
<span<?php echo $pc_issuance->reference_id->ViewAttributes() ?>>
<?php echo $pc_issuance->reference_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->asset_tag->Visible) { // asset_tag ?>
		<td<?php echo $pc_issuance->asset_tag->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_asset_tag" class="pc_issuance_asset_tag">
<span<?php echo $pc_issuance->asset_tag->ViewAttributes() ?>>
<?php echo $pc_issuance->asset_tag->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->make->Visible) { // make ?>
		<td<?php echo $pc_issuance->make->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_make" class="pc_issuance_make">
<span<?php echo $pc_issuance->make->ViewAttributes() ?>>
<?php echo $pc_issuance->make->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->color->Visible) { // color ?>
		<td<?php echo $pc_issuance->color->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_color" class="pc_issuance_color">
<span<?php echo $pc_issuance->color->ViewAttributes() ?>>
<?php echo $pc_issuance->color->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->department->Visible) { // department ?>
		<td<?php echo $pc_issuance->department->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_department" class="pc_issuance_department">
<span<?php echo $pc_issuance->department->ViewAttributes() ?>>
<?php echo $pc_issuance->department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->designation->Visible) { // designation ?>
		<td<?php echo $pc_issuance->designation->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_designation" class="pc_issuance_designation">
<span<?php echo $pc_issuance->designation->ViewAttributes() ?>>
<?php echo $pc_issuance->designation->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->assign_to->Visible) { // assign_to ?>
		<td<?php echo $pc_issuance->assign_to->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_assign_to" class="pc_issuance_assign_to">
<span<?php echo $pc_issuance->assign_to->ViewAttributes() ?>>
<?php echo $pc_issuance->assign_to->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->date_assign->Visible) { // date_assign ?>
		<td<?php echo $pc_issuance->date_assign->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_date_assign" class="pc_issuance_date_assign">
<span<?php echo $pc_issuance->date_assign->ViewAttributes() ?>>
<?php echo $pc_issuance->date_assign->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->assign_by->Visible) { // assign_by ?>
		<td<?php echo $pc_issuance->assign_by->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_assign_by" class="pc_issuance_assign_by">
<span<?php echo $pc_issuance->assign_by->ViewAttributes() ?>>
<?php echo $pc_issuance->assign_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->statuse->Visible) { // statuse ?>
		<td<?php echo $pc_issuance->statuse->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_statuse" class="pc_issuance_statuse">
<span<?php echo $pc_issuance->statuse->ViewAttributes() ?>>
<?php echo $pc_issuance->statuse->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->date_retrieved->Visible) { // date_retrieved ?>
		<td<?php echo $pc_issuance->date_retrieved->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_date_retrieved" class="pc_issuance_date_retrieved">
<span<?php echo $pc_issuance->date_retrieved->ViewAttributes() ?>>
<?php echo $pc_issuance->date_retrieved->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pc_issuance->retrieved_by->Visible) { // retrieved_by ?>
		<td<?php echo $pc_issuance->retrieved_by->CellAttributes() ?>>
<span id="el<?php echo $pc_issuance_delete->RowCnt ?>_pc_issuance_retrieved_by" class="pc_issuance_retrieved_by">
<span<?php echo $pc_issuance->retrieved_by->ViewAttributes() ?>>
<?php echo $pc_issuance->retrieved_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pc_issuance_delete->Recordset->MoveNext();
}
$pc_issuance_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pc_issuance_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpc_issuancedelete.Init();
</script>
<?php
$pc_issuance_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pc_issuance_delete->Page_Terminate();
?>
