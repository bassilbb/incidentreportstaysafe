<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "system_inventoryinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$system_inventory_delete = NULL; // Initialize page object first

class csystem_inventory_delete extends csystem_inventory {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'system_inventory';

	// Page object name
	var $PageObjName = 'system_inventory_delete';

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

		// Table object (system_inventory)
		if (!isset($GLOBALS["system_inventory"]) || get_class($GLOBALS["system_inventory"]) == "csystem_inventory") {
			$GLOBALS["system_inventory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["system_inventory"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'system_inventory');

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
				$this->Page_Terminate(ew_GetUrl("system_inventorylist.php"));
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
		$this->date_recieved->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->make->SetVisibility();
		$this->pc_ram->SetVisibility();
		$this->pc_harddisk->SetVisibility();
		$this->color->SetVisibility();
		$this->capacity->SetVisibility();
		$this->quantity->SetVisibility();
		$this->description->SetVisibility();
		$this->recieved_by->SetVisibility();

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
		global $EW_EXPORT, $system_inventory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($system_inventory);
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
			$this->Page_Terminate("system_inventorylist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in system_inventory class, system_inventoryinfo.php

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
				$this->Page_Terminate("system_inventorylist.php"); // Return to list
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
		$this->date_recieved->setDbValue($row['date_recieved']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->make->setDbValue($row['make']);
		$this->pc_ram->setDbValue($row['pc_ram']);
		$this->pc_harddisk->setDbValue($row['pc_harddisk']);
		$this->color->setDbValue($row['color']);
		$this->capacity->setDbValue($row['capacity']);
		$this->quantity->setDbValue($row['quantity']);
		$this->description->setDbValue($row['description']);
		$this->recieved_by->setDbValue($row['recieved_by']);
		$this->status->setDbValue($row['status']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_recieved'] = NULL;
		$row['reference_id'] = NULL;
		$row['material_name'] = NULL;
		$row['make'] = NULL;
		$row['pc_ram'] = NULL;
		$row['pc_harddisk'] = NULL;
		$row['color'] = NULL;
		$row['capacity'] = NULL;
		$row['quantity'] = NULL;
		$row['description'] = NULL;
		$row['recieved_by'] = NULL;
		$row['status'] = NULL;
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
		$this->material_name->DbValue = $row['material_name'];
		$this->make->DbValue = $row['make'];
		$this->pc_ram->DbValue = $row['pc_ram'];
		$this->pc_harddisk->DbValue = $row['pc_harddisk'];
		$this->color->DbValue = $row['color'];
		$this->capacity->DbValue = $row['capacity'];
		$this->quantity->DbValue = $row['quantity'];
		$this->description->DbValue = $row['description'];
		$this->recieved_by->DbValue = $row['recieved_by'];
		$this->status->DbValue = $row['status'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date_recieved
		// reference_id
		// material_name
		// make
		// pc_ram
		// pc_harddisk
		// color
		// capacity
		// quantity
		// description
		// recieved_by
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->ViewValue = $this->date_recieved->CurrentValue;
		$this->date_recieved->ViewValue = ew_FormatDateTime($this->date_recieved->ViewValue, 17);
		$this->date_recieved->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		$this->material_name->ViewValue = $this->material_name->CurrentValue;
		$this->material_name->ViewCustomAttributes = "";

		// make
		$this->make->ViewValue = $this->make->CurrentValue;
		$this->make->ViewCustomAttributes = "";

		// pc_ram
		$this->pc_ram->ViewValue = $this->pc_ram->CurrentValue;
		$this->pc_ram->ViewCustomAttributes = "";

		// pc_harddisk
		$this->pc_harddisk->ViewValue = $this->pc_harddisk->CurrentValue;
		$this->pc_harddisk->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// recieved_by
		$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
		if (strval($this->recieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recieved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// date_recieved
			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";
			$this->date_recieved->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";

			// make
			$this->make->LinkCustomAttributes = "";
			$this->make->HrefValue = "";
			$this->make->TooltipValue = "";

			// pc_ram
			$this->pc_ram->LinkCustomAttributes = "";
			$this->pc_ram->HrefValue = "";
			$this->pc_ram->TooltipValue = "";

			// pc_harddisk
			$this->pc_harddisk->LinkCustomAttributes = "";
			$this->pc_harddisk->HrefValue = "";
			$this->pc_harddisk->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";
			$this->capacity->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";
			$this->recieved_by->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("system_inventorylist.php"), "", $this->TableVar, TRUE);
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
if (!isset($system_inventory_delete)) $system_inventory_delete = new csystem_inventory_delete();

// Page init
$system_inventory_delete->Page_Init();

// Page main
$system_inventory_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$system_inventory_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsystem_inventorydelete = new ew_Form("fsystem_inventorydelete", "delete");

// Form_CustomValidate event
fsystem_inventorydelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsystem_inventorydelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fsystem_inventorydelete.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fsystem_inventorydelete.Lists["x_recieved_by"].Data = "<?php echo $system_inventory_delete->recieved_by->LookupFilterQuery(FALSE, "delete") ?>";
fsystem_inventorydelete.AutoSuggests["x_recieved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $system_inventory_delete->recieved_by->LookupFilterQuery(TRUE, "delete"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $system_inventory_delete->ShowPageHeader(); ?>
<?php
$system_inventory_delete->ShowMessage();
?>
<form name="fsystem_inventorydelete" id="fsystem_inventorydelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($system_inventory_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $system_inventory_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="system_inventory">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($system_inventory_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($system_inventory->id->Visible) { // id ?>
		<th class="<?php echo $system_inventory->id->HeaderCellClass() ?>"><span id="elh_system_inventory_id" class="system_inventory_id"><?php echo $system_inventory->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->date_recieved->Visible) { // date_recieved ?>
		<th class="<?php echo $system_inventory->date_recieved->HeaderCellClass() ?>"><span id="elh_system_inventory_date_recieved" class="system_inventory_date_recieved"><?php echo $system_inventory->date_recieved->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->reference_id->Visible) { // reference_id ?>
		<th class="<?php echo $system_inventory->reference_id->HeaderCellClass() ?>"><span id="elh_system_inventory_reference_id" class="system_inventory_reference_id"><?php echo $system_inventory->reference_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->material_name->Visible) { // material_name ?>
		<th class="<?php echo $system_inventory->material_name->HeaderCellClass() ?>"><span id="elh_system_inventory_material_name" class="system_inventory_material_name"><?php echo $system_inventory->material_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->make->Visible) { // make ?>
		<th class="<?php echo $system_inventory->make->HeaderCellClass() ?>"><span id="elh_system_inventory_make" class="system_inventory_make"><?php echo $system_inventory->make->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->pc_ram->Visible) { // pc_ram ?>
		<th class="<?php echo $system_inventory->pc_ram->HeaderCellClass() ?>"><span id="elh_system_inventory_pc_ram" class="system_inventory_pc_ram"><?php echo $system_inventory->pc_ram->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->pc_harddisk->Visible) { // pc_harddisk ?>
		<th class="<?php echo $system_inventory->pc_harddisk->HeaderCellClass() ?>"><span id="elh_system_inventory_pc_harddisk" class="system_inventory_pc_harddisk"><?php echo $system_inventory->pc_harddisk->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->color->Visible) { // color ?>
		<th class="<?php echo $system_inventory->color->HeaderCellClass() ?>"><span id="elh_system_inventory_color" class="system_inventory_color"><?php echo $system_inventory->color->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->capacity->Visible) { // capacity ?>
		<th class="<?php echo $system_inventory->capacity->HeaderCellClass() ?>"><span id="elh_system_inventory_capacity" class="system_inventory_capacity"><?php echo $system_inventory->capacity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->quantity->Visible) { // quantity ?>
		<th class="<?php echo $system_inventory->quantity->HeaderCellClass() ?>"><span id="elh_system_inventory_quantity" class="system_inventory_quantity"><?php echo $system_inventory->quantity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->description->Visible) { // description ?>
		<th class="<?php echo $system_inventory->description->HeaderCellClass() ?>"><span id="elh_system_inventory_description" class="system_inventory_description"><?php echo $system_inventory->description->FldCaption() ?></span></th>
<?php } ?>
<?php if ($system_inventory->recieved_by->Visible) { // recieved_by ?>
		<th class="<?php echo $system_inventory->recieved_by->HeaderCellClass() ?>"><span id="elh_system_inventory_recieved_by" class="system_inventory_recieved_by"><?php echo $system_inventory->recieved_by->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$system_inventory_delete->RecCnt = 0;
$i = 0;
while (!$system_inventory_delete->Recordset->EOF) {
	$system_inventory_delete->RecCnt++;
	$system_inventory_delete->RowCnt++;

	// Set row properties
	$system_inventory->ResetAttrs();
	$system_inventory->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$system_inventory_delete->LoadRowValues($system_inventory_delete->Recordset);

	// Render row
	$system_inventory_delete->RenderRow();
?>
	<tr<?php echo $system_inventory->RowAttributes() ?>>
<?php if ($system_inventory->id->Visible) { // id ?>
		<td<?php echo $system_inventory->id->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_id" class="system_inventory_id">
<span<?php echo $system_inventory->id->ViewAttributes() ?>>
<?php echo $system_inventory->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->date_recieved->Visible) { // date_recieved ?>
		<td<?php echo $system_inventory->date_recieved->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_date_recieved" class="system_inventory_date_recieved">
<span<?php echo $system_inventory->date_recieved->ViewAttributes() ?>>
<?php echo $system_inventory->date_recieved->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->reference_id->Visible) { // reference_id ?>
		<td<?php echo $system_inventory->reference_id->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_reference_id" class="system_inventory_reference_id">
<span<?php echo $system_inventory->reference_id->ViewAttributes() ?>>
<?php echo $system_inventory->reference_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->material_name->Visible) { // material_name ?>
		<td<?php echo $system_inventory->material_name->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_material_name" class="system_inventory_material_name">
<span<?php echo $system_inventory->material_name->ViewAttributes() ?>>
<?php echo $system_inventory->material_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->make->Visible) { // make ?>
		<td<?php echo $system_inventory->make->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_make" class="system_inventory_make">
<span<?php echo $system_inventory->make->ViewAttributes() ?>>
<?php echo $system_inventory->make->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->pc_ram->Visible) { // pc_ram ?>
		<td<?php echo $system_inventory->pc_ram->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_pc_ram" class="system_inventory_pc_ram">
<span<?php echo $system_inventory->pc_ram->ViewAttributes() ?>>
<?php echo $system_inventory->pc_ram->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->pc_harddisk->Visible) { // pc_harddisk ?>
		<td<?php echo $system_inventory->pc_harddisk->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_pc_harddisk" class="system_inventory_pc_harddisk">
<span<?php echo $system_inventory->pc_harddisk->ViewAttributes() ?>>
<?php echo $system_inventory->pc_harddisk->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->color->Visible) { // color ?>
		<td<?php echo $system_inventory->color->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_color" class="system_inventory_color">
<span<?php echo $system_inventory->color->ViewAttributes() ?>>
<?php echo $system_inventory->color->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->capacity->Visible) { // capacity ?>
		<td<?php echo $system_inventory->capacity->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_capacity" class="system_inventory_capacity">
<span<?php echo $system_inventory->capacity->ViewAttributes() ?>>
<?php echo $system_inventory->capacity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->quantity->Visible) { // quantity ?>
		<td<?php echo $system_inventory->quantity->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_quantity" class="system_inventory_quantity">
<span<?php echo $system_inventory->quantity->ViewAttributes() ?>>
<?php echo $system_inventory->quantity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->description->Visible) { // description ?>
		<td<?php echo $system_inventory->description->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_description" class="system_inventory_description">
<span<?php echo $system_inventory->description->ViewAttributes() ?>>
<?php echo $system_inventory->description->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($system_inventory->recieved_by->Visible) { // recieved_by ?>
		<td<?php echo $system_inventory->recieved_by->CellAttributes() ?>>
<span id="el<?php echo $system_inventory_delete->RowCnt ?>_system_inventory_recieved_by" class="system_inventory_recieved_by">
<span<?php echo $system_inventory->recieved_by->ViewAttributes() ?>>
<?php echo $system_inventory->recieved_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$system_inventory_delete->Recordset->MoveNext();
}
$system_inventory_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $system_inventory_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsystem_inventorydelete.Init();
</script>
<?php
$system_inventory_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$system_inventory_delete->Page_Terminate();
?>
