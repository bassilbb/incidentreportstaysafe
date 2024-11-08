<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "inventory_storeinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$inventory_store_edit = NULL; // Initialize page object first

class cinventory_store_edit extends cinventory_store {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'inventory_store';

	// Page object name
	var $PageObjName = 'inventory_store_edit';

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

		// Table object (inventory_store)
		if (!isset($GLOBALS["inventory_store"]) || get_class($GLOBALS["inventory_store"]) == "cinventory_store") {
			$GLOBALS["inventory_store"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventory_store"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory_store', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("inventory_storelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_type->SetVisibility();
		$this->quantity_out->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->treated_by->SetVisibility();

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
		global $EW_EXPORT, $inventory_store;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventory_store);
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "inventory_storeview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $RecCnt;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";

		// Load record by position
		$loadByPosition = FALSE;
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_id")) {
				$this->id->setFormValue($objForm->GetValue("x_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id"])) {
				$this->id->setQueryStringValue($_GET["id"]);
				$loadByQuery = TRUE;
			} else {
				$this->id->CurrentValue = NULL;
			}
			if (!$loadByQuery)
				$loadByPosition = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("inventory_storelist.php"); // Return to list page
		} elseif ($loadByPosition) { // Load record by position
			$this->SetupStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$this->Recordset->Move($this->StartRec-1);
				$loaded = TRUE;
			}
		} else { // Match key values
			if (!is_null($this->id->CurrentValue)) {
				while (!$this->Recordset->EOF) {
					if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
						$this->setStartRecordNumber($this->StartRec); // Save record position
						$loaded = TRUE;
						break;
					} else {
						$this->StartRec++;
						$this->Recordset->MoveNext();
					}
				}
			}
		}

		// Load current row values
		if ($loaded)
			$this->LoadRowValues($this->Recordset);

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("inventory_storelist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "inventory_storelist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render as View
		} else {
			$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		}
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->material_name->FldIsDetailKey) {
			$this->material_name->setFormValue($objForm->GetValue("x_material_name"));
		}
		if (!$this->quantity_in->FldIsDetailKey) {
			$this->quantity_in->setFormValue($objForm->GetValue("x_quantity_in"));
		}
		if (!$this->quantity_type->FldIsDetailKey) {
			$this->quantity_type->setFormValue($objForm->GetValue("x_quantity_type"));
		}
		if (!$this->quantity_out->FldIsDetailKey) {
			$this->quantity_out->setFormValue($objForm->GetValue("x_quantity_out"));
		}
		if (!$this->total_quantity->FldIsDetailKey) {
			$this->total_quantity->setFormValue($objForm->GetValue("x_total_quantity"));
		}
		if (!$this->treated_by->FldIsDetailKey) {
			$this->treated_by->setFormValue($objForm->GetValue("x_treated_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->material_name->CurrentValue = $this->material_name->FormValue;
		$this->quantity_in->CurrentValue = $this->quantity_in->FormValue;
		$this->quantity_type->CurrentValue = $this->quantity_type->FormValue;
		$this->quantity_out->CurrentValue = $this->quantity_out->FormValue;
		$this->total_quantity->CurrentValue = $this->total_quantity->FormValue;
		$this->treated_by->CurrentValue = $this->treated_by->FormValue;
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
		$this->date->setDbValue($row['date']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity_in->setDbValue($row['quantity_in']);
		$this->quantity_type->setDbValue($row['quantity_type']);
		$this->quantity_out->setDbValue($row['quantity_out']);
		$this->total_quantity->setDbValue($row['total_quantity']);
		$this->treated_by->setDbValue($row['treated_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['material_name'] = NULL;
		$row['quantity_in'] = NULL;
		$row['quantity_type'] = NULL;
		$row['quantity_out'] = NULL;
		$row['total_quantity'] = NULL;
		$row['treated_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date->DbValue = $row['date'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity_in->DbValue = $row['quantity_in'];
		$this->quantity_type->DbValue = $row['quantity_type'];
		$this->quantity_out->DbValue = $row['quantity_out'];
		$this->total_quantity->DbValue = $row['total_quantity'];
		$this->treated_by->DbValue = $row['treated_by'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date
		// reference_id
		// staff_id
		// material_name
		// quantity_in
		// quantity_type
		// quantity_out
		// total_quantity
		// treated_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

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
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
		$sWhereWrk = "";
		$this->material_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->material_name->ViewValue = $this->material_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->material_name->ViewValue = $this->material_name->CurrentValue;
			}
		} else {
			$this->material_name->ViewValue = NULL;
		}
		$this->material_name->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_type
		$this->quantity_type->ViewValue = $this->quantity_type->CurrentValue;
		$this->quantity_type->ViewCustomAttributes = "";

		// quantity_out
		$this->quantity_out->ViewValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// treated_by
		$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
		if (strval($this->treated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->treated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->treated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->treated_by->ViewValue = $this->treated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
			}
		} else {
			$this->treated_by->ViewValue = NULL;
		}
		$this->treated_by->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_type
			$this->quantity_type->LinkCustomAttributes = "";
			$this->quantity_type->HrefValue = "";
			$this->quantity_type->TooltipValue = "";

			// quantity_out
			$this->quantity_out->LinkCustomAttributes = "";
			$this->quantity_out->HrefValue = "";
			$this->quantity_out->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// treated_by
			$this->treated_by->LinkCustomAttributes = "";
			$this->treated_by->HrefValue = "";
			$this->treated_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->CurrentValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->staff_id->EditValue = $this->staff_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// material_name
			$this->material_name->EditAttrs["class"] = "form-control";
			$this->material_name->EditCustomAttributes = "";
			if (trim(strval($this->material_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `inventory`";
			$sWhereWrk = "";
			$this->material_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->material_name->EditValue = $arwrk;

			// quantity_in
			$this->quantity_in->EditAttrs["class"] = "form-control";
			$this->quantity_in->EditCustomAttributes = "";
			$this->quantity_in->EditValue = ew_HtmlEncode($this->quantity_in->CurrentValue);
			$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

			// quantity_type
			$this->quantity_type->EditAttrs["class"] = "form-control";
			$this->quantity_type->EditCustomAttributes = "";
			$this->quantity_type->EditValue = ew_HtmlEncode($this->quantity_type->CurrentValue);
			$this->quantity_type->PlaceHolder = ew_RemoveHtml($this->quantity_type->FldCaption());

			// quantity_out
			$this->quantity_out->EditAttrs["class"] = "form-control";
			$this->quantity_out->EditCustomAttributes = "";
			$this->quantity_out->EditValue = ew_HtmlEncode($this->quantity_out->CurrentValue);
			$this->quantity_out->PlaceHolder = ew_RemoveHtml($this->quantity_out->FldCaption());

			// total_quantity
			$this->total_quantity->EditAttrs["class"] = "form-control";
			$this->total_quantity->EditCustomAttributes = "";
			$this->total_quantity->EditValue = ew_HtmlEncode($this->total_quantity->CurrentValue);
			$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

			// treated_by
			$this->treated_by->EditAttrs["class"] = "form-control";
			$this->treated_by->EditCustomAttributes = "";
			$this->treated_by->EditValue = ew_HtmlEncode($this->treated_by->CurrentValue);
			if (strval($this->treated_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->treated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->treated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->treated_by->EditValue = $this->treated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->treated_by->EditValue = ew_HtmlEncode($this->treated_by->CurrentValue);
				}
			} else {
				$this->treated_by->EditValue = NULL;
			}
			$this->treated_by->PlaceHolder = ew_RemoveHtml($this->treated_by->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";

			// quantity_type
			$this->quantity_type->LinkCustomAttributes = "";
			$this->quantity_type->HrefValue = "";

			// quantity_out
			$this->quantity_out->LinkCustomAttributes = "";
			$this->quantity_out->HrefValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";

			// treated_by
			$this->treated_by->LinkCustomAttributes = "";
			$this->treated_by->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->date->FldIsDetailKey && !is_null($this->date->FormValue) && $this->date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date->FldCaption(), $this->date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date->FormValue)) {
			ew_AddMessage($gsFormError, $this->date->FldErrMsg());
		}
		if (!$this->reference_id->FldIsDetailKey && !is_null($this->reference_id->FormValue) && $this->reference_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reference_id->FldCaption(), $this->reference_id->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->staff_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->staff_id->FldErrMsg());
		}
		if (!$this->material_name->FldIsDetailKey && !is_null($this->material_name->FormValue) && $this->material_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->material_name->FldCaption(), $this->material_name->ReqErrMsg));
		}
		if (!$this->quantity_in->FldIsDetailKey && !is_null($this->quantity_in->FormValue) && $this->quantity_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity_in->FldCaption(), $this->quantity_in->ReqErrMsg));
		}
		if (!$this->treated_by->FldIsDetailKey && !is_null($this->treated_by->FormValue) && $this->treated_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->treated_by->FldCaption(), $this->treated_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->treated_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->treated_by->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// date
			$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 0), ew_CurrentDate(), $this->date->ReadOnly);

			// reference_id
			$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, "", $this->reference_id->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, 0, $this->staff_id->ReadOnly);

			// material_name
			$this->material_name->SetDbValueDef($rsnew, $this->material_name->CurrentValue, "", $this->material_name->ReadOnly);

			// quantity_in
			$this->quantity_in->SetDbValueDef($rsnew, $this->quantity_in->CurrentValue, "", $this->quantity_in->ReadOnly);

			// quantity_type
			$this->quantity_type->SetDbValueDef($rsnew, $this->quantity_type->CurrentValue, NULL, $this->quantity_type->ReadOnly);

			// quantity_out
			$this->quantity_out->SetDbValueDef($rsnew, $this->quantity_out->CurrentValue, NULL, $this->quantity_out->ReadOnly);

			// total_quantity
			$this->total_quantity->SetDbValueDef($rsnew, $this->total_quantity->CurrentValue, NULL, $this->total_quantity->ReadOnly);

			// treated_by
			$this->treated_by->SetDbValueDef($rsnew, $this->treated_by->CurrentValue, 0, $this->treated_by->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inventory_storelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_material_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_treated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld` FROM `users`";
			$sWhereWrk = "`staffno` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_treated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->treated_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($inventory_store_edit)) $inventory_store_edit = new cinventory_store_edit();

// Page init
$inventory_store_edit->Page_Init();

// Page main
$inventory_store_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_store_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finventory_storeedit = new ew_Form("finventory_storeedit", "edit");

// Validate form
finventory_storeedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory_store->date->FldCaption(), $inventory_store->date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reference_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory_store->reference_id->FldCaption(), $inventory_store->reference_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory_store->staff_id->FldCaption(), $inventory_store->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->staff_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_material_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory_store->material_name->FldCaption(), $inventory_store->material_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory_store->quantity_in->FldCaption(), $inventory_store->quantity_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_treated_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory_store->treated_by->FldCaption(), $inventory_store->treated_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_treated_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->treated_by->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
finventory_storeedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventory_storeedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventory_storeedit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_storeedit.Lists["x_staff_id"].Data = "<?php echo $inventory_store_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->staff_id->LookupFilterQuery(TRUE, "edit"))) ?>;
finventory_storeedit.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory"};
finventory_storeedit.Lists["x_material_name"].Data = "<?php echo $inventory_store_edit->material_name->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.Lists["x_treated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_storeedit.Lists["x_treated_by"].Data = "<?php echo $inventory_store_edit->treated_by->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_treated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->treated_by->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $inventory_store_edit->ShowPageHeader(); ?>
<?php
$inventory_store_edit->ShowMessage();
?>
<?php if (!$inventory_store_edit->IsModal) { ?>
<?php if ($inventory_store->CurrentAction <> "F") { // Confirm page ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($inventory_store_edit->Pager)) $inventory_store_edit->Pager = new cPrevNextPager($inventory_store_edit->StartRec, $inventory_store_edit->DisplayRecs, $inventory_store_edit->TotalRecs, $inventory_store_edit->AutoHidePager) ?>
<?php if ($inventory_store_edit->Pager->RecordCount > 0 && $inventory_store_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($inventory_store_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $inventory_store_edit->PageUrl() ?>start=<?php echo $inventory_store_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($inventory_store_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $inventory_store_edit->PageUrl() ?>start=<?php echo $inventory_store_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $inventory_store_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($inventory_store_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $inventory_store_edit->PageUrl() ?>start=<?php echo $inventory_store_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($inventory_store_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $inventory_store_edit->PageUrl() ?>start=<?php echo $inventory_store_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $inventory_store_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="finventory_storeedit" id="finventory_storeedit" class="<?php echo $inventory_store_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_store_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_store_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory_store">
<?php if ($inventory_store->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_edit" id="a_edit" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($inventory_store_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($inventory_store->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_inventory_store_id" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->id->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->id->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_id">
<span<?php echo $inventory_store->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_id" data-page="1" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($inventory_store->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_inventory_store_id">
<span<?php echo $inventory_store->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_id" data-page="1" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($inventory_store->id->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_inventory_store_date" for="x_date" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->date->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_date">
<input type="text" data-table="inventory_store" data-field="x_date" data-page="1" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($inventory_store->date->getPlaceHolder()) ?>" value="<?php echo $inventory_store->date->EditValue ?>"<?php echo $inventory_store->date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_date">
<span<?php echo $inventory_store->date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_date" data-page="1" name="x_date" id="x_date" value="<?php echo ew_HtmlEncode($inventory_store->date->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_inventory_store_reference_id" for="x_reference_id" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->reference_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->reference_id->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_reference_id">
<input type="text" data-table="inventory_store" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->reference_id->getPlaceHolder()) ?>" value="<?php echo $inventory_store->reference_id->EditValue ?>"<?php echo $inventory_store->reference_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_reference_id">
<span<?php echo $inventory_store->reference_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->reference_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" value="<?php echo ew_HtmlEncode($inventory_store->reference_id->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_inventory_store_staff_id" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->staff_id->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_staff_id">
<?php
$wrkonchange = trim(" " . @$inventory_store->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_store->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $inventory_store->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_store->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_store->staff_id->getPlaceHolder()) ?>"<?php echo $inventory_store->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $inventory_store->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($inventory_store->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_storeedit.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_store_staff_id">
<span<?php echo $inventory_store->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($inventory_store->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label id="elh_inventory_store_material_name" for="x_material_name" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->material_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->material_name->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_material_name">
<select data-table="inventory_store" data-field="x_material_name" data-page="1" data-value-separator="<?php echo $inventory_store->material_name->DisplayValueSeparatorAttribute() ?>" id="x_material_name" name="x_material_name"<?php echo $inventory_store->material_name->EditAttributes() ?>>
<?php echo $inventory_store->material_name->SelectOptionListHtml("x_material_name") ?>
</select>
</span>
<?php } else { ?>
<span id="el_inventory_store_material_name">
<span<?php echo $inventory_store->material_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->material_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_material_name" data-page="1" name="x_material_name" id="x_material_name" value="<?php echo ew_HtmlEncode($inventory_store->material_name->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->material_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->quantity_in->Visible) { // quantity_in ?>
	<div id="r_quantity_in" class="form-group">
		<label id="elh_inventory_store_quantity_in" for="x_quantity_in" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->quantity_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->quantity_in->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_quantity_in">
<input type="text" data-table="inventory_store" data-field="x_quantity_in" data-page="1" name="x_quantity_in" id="x_quantity_in" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->quantity_in->getPlaceHolder()) ?>" value="<?php echo $inventory_store->quantity_in->EditValue ?>"<?php echo $inventory_store->quantity_in->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_quantity_in">
<span<?php echo $inventory_store->quantity_in->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->quantity_in->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_quantity_in" data-page="1" name="x_quantity_in" id="x_quantity_in" value="<?php echo ew_HtmlEncode($inventory_store->quantity_in->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->quantity_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->quantity_type->Visible) { // quantity_type ?>
	<div id="r_quantity_type" class="form-group">
		<label id="elh_inventory_store_quantity_type" for="x_quantity_type" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->quantity_type->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->quantity_type->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_quantity_type">
<input type="text" data-table="inventory_store" data-field="x_quantity_type" data-page="1" name="x_quantity_type" id="x_quantity_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->quantity_type->getPlaceHolder()) ?>" value="<?php echo $inventory_store->quantity_type->EditValue ?>"<?php echo $inventory_store->quantity_type->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_quantity_type">
<span<?php echo $inventory_store->quantity_type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->quantity_type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_quantity_type" data-page="1" name="x_quantity_type" id="x_quantity_type" value="<?php echo ew_HtmlEncode($inventory_store->quantity_type->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->quantity_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->quantity_out->Visible) { // quantity_out ?>
	<div id="r_quantity_out" class="form-group">
		<label id="elh_inventory_store_quantity_out" for="x_quantity_out" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->quantity_out->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->quantity_out->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_quantity_out">
<input type="text" data-table="inventory_store" data-field="x_quantity_out" data-page="1" name="x_quantity_out" id="x_quantity_out" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->quantity_out->getPlaceHolder()) ?>" value="<?php echo $inventory_store->quantity_out->EditValue ?>"<?php echo $inventory_store->quantity_out->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_quantity_out">
<span<?php echo $inventory_store->quantity_out->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->quantity_out->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_quantity_out" data-page="1" name="x_quantity_out" id="x_quantity_out" value="<?php echo ew_HtmlEncode($inventory_store->quantity_out->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->quantity_out->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->total_quantity->Visible) { // total_quantity ?>
	<div id="r_total_quantity" class="form-group">
		<label id="elh_inventory_store_total_quantity" for="x_total_quantity" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->total_quantity->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->total_quantity->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_total_quantity">
<input type="text" data-table="inventory_store" data-field="x_total_quantity" data-page="1" name="x_total_quantity" id="x_total_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->total_quantity->getPlaceHolder()) ?>" value="<?php echo $inventory_store->total_quantity->EditValue ?>"<?php echo $inventory_store->total_quantity->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_total_quantity">
<span<?php echo $inventory_store->total_quantity->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->total_quantity->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_total_quantity" data-page="1" name="x_total_quantity" id="x_total_quantity" value="<?php echo ew_HtmlEncode($inventory_store->total_quantity->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->total_quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->treated_by->Visible) { // treated_by ?>
	<div id="r_treated_by" class="form-group">
		<label id="elh_inventory_store_treated_by" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->treated_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->treated_by->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_treated_by">
<?php
$wrkonchange = trim(" " . @$inventory_store->treated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_store->treated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_treated_by" style="white-space: nowrap; z-index: 8900">
	<input type="text" name="sv_x_treated_by" id="sv_x_treated_by" value="<?php echo $inventory_store->treated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_store->treated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_store->treated_by->getPlaceHolder()) ?>"<?php echo $inventory_store->treated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_treated_by" data-page="1" data-value-separator="<?php echo $inventory_store->treated_by->DisplayValueSeparatorAttribute() ?>" name="x_treated_by" id="x_treated_by" value="<?php echo ew_HtmlEncode($inventory_store->treated_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_storeedit.CreateAutoSuggest({"id":"x_treated_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_store_treated_by">
<span<?php echo $inventory_store->treated_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->treated_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_treated_by" data-page="1" name="x_treated_by" id="x_treated_by" value="<?php echo ew_HtmlEncode($inventory_store->treated_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->treated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$inventory_store_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $inventory_store_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($inventory_store->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inventory_store_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finventory_storeedit.Init();
</script>
<?php
$inventory_store_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_staff_id").hide();
$("#r_treated_by").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$inventory_store_edit->Page_Terminate();
?>
