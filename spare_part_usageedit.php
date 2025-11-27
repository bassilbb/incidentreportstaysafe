<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "spare_part_usageinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$spare_part_usage_edit = NULL; // Initialize page object first

class cspare_part_usage_edit extends cspare_part_usage {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'spare_part_usage';

	// Page object name
	var $PageObjName = 'spare_part_usage_edit';

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

		// Table object (spare_part_usage)
		if (!isset($GLOBALS["spare_part_usage"]) || get_class($GLOBALS["spare_part_usage"]) == "cspare_part_usage") {
			$GLOBALS["spare_part_usage"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["spare_part_usage"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'spare_part_usage');

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
				$this->Page_Terminate(ew_GetUrl("spare_part_usagelist.php"));
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
		$this->part_name->SetVisibility();
		$this->gen_name->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_used->SetVisibility();
		$this->cost->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->total_cost->SetVisibility();
		$this->maintenance_total_cost->SetVisibility();
		$this->maintenance_id->SetVisibility();

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
		global $EW_EXPORT, $spare_part_usage;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($spare_part_usage);
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
					if ($pageName == "spare_part_usageview.php")
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
			$this->Page_Terminate("spare_part_usagelist.php"); // Return to list page
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
					$this->Page_Terminate("spare_part_usagelist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "spare_part_usagelist.php")
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
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
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
		if (!$this->part_name->FldIsDetailKey) {
			$this->part_name->setFormValue($objForm->GetValue("x_part_name"));
		}
		if (!$this->gen_name->FldIsDetailKey) {
			$this->gen_name->setFormValue($objForm->GetValue("x_gen_name"));
		}
		if (!$this->quantity_in->FldIsDetailKey) {
			$this->quantity_in->setFormValue($objForm->GetValue("x_quantity_in"));
		}
		if (!$this->quantity_used->FldIsDetailKey) {
			$this->quantity_used->setFormValue($objForm->GetValue("x_quantity_used"));
		}
		if (!$this->cost->FldIsDetailKey) {
			$this->cost->setFormValue($objForm->GetValue("x_cost"));
		}
		if (!$this->total_quantity->FldIsDetailKey) {
			$this->total_quantity->setFormValue($objForm->GetValue("x_total_quantity"));
		}
		if (!$this->total_cost->FldIsDetailKey) {
			$this->total_cost->setFormValue($objForm->GetValue("x_total_cost"));
		}
		if (!$this->maintenance_total_cost->FldIsDetailKey) {
			$this->maintenance_total_cost->setFormValue($objForm->GetValue("x_maintenance_total_cost"));
		}
		if (!$this->maintenance_id->FldIsDetailKey) {
			$this->maintenance_id->setFormValue($objForm->GetValue("x_maintenance_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->part_name->CurrentValue = $this->part_name->FormValue;
		$this->gen_name->CurrentValue = $this->gen_name->FormValue;
		$this->quantity_in->CurrentValue = $this->quantity_in->FormValue;
		$this->quantity_used->CurrentValue = $this->quantity_used->FormValue;
		$this->cost->CurrentValue = $this->cost->FormValue;
		$this->total_quantity->CurrentValue = $this->total_quantity->FormValue;
		$this->total_cost->CurrentValue = $this->total_cost->FormValue;
		$this->maintenance_total_cost->CurrentValue = $this->maintenance_total_cost->FormValue;
		$this->maintenance_id->CurrentValue = $this->maintenance_id->FormValue;
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
		$this->part_name->setDbValue($row['part_name']);
		$this->gen_name->setDbValue($row['gen_name']);
		$this->quantity_in->setDbValue($row['quantity_in']);
		$this->quantity_used->setDbValue($row['quantity_used']);
		$this->cost->setDbValue($row['cost']);
		$this->total_quantity->setDbValue($row['total_quantity']);
		$this->total_cost->setDbValue($row['total_cost']);
		$this->maintenance_total_cost->setDbValue($row['maintenance_total_cost']);
		$this->maintenance_id->setDbValue($row['maintenance_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date'] = NULL;
		$row['reference_id'] = NULL;
		$row['part_name'] = NULL;
		$row['gen_name'] = NULL;
		$row['quantity_in'] = NULL;
		$row['quantity_used'] = NULL;
		$row['cost'] = NULL;
		$row['total_quantity'] = NULL;
		$row['total_cost'] = NULL;
		$row['maintenance_total_cost'] = NULL;
		$row['maintenance_id'] = NULL;
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
		$this->part_name->DbValue = $row['part_name'];
		$this->gen_name->DbValue = $row['gen_name'];
		$this->quantity_in->DbValue = $row['quantity_in'];
		$this->quantity_used->DbValue = $row['quantity_used'];
		$this->cost->DbValue = $row['cost'];
		$this->total_quantity->DbValue = $row['total_quantity'];
		$this->total_cost->DbValue = $row['total_cost'];
		$this->maintenance_total_cost->DbValue = $row['maintenance_total_cost'];
		$this->maintenance_id->DbValue = $row['maintenance_id'];
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
		// Convert decimal values if posted back

		if ($this->cost->FormValue == $this->cost->CurrentValue && is_numeric(ew_StrToFloat($this->cost->CurrentValue)))
			$this->cost->CurrentValue = ew_StrToFloat($this->cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total_cost->FormValue == $this->total_cost->CurrentValue && is_numeric(ew_StrToFloat($this->total_cost->CurrentValue)))
			$this->total_cost->CurrentValue = ew_StrToFloat($this->total_cost->CurrentValue);

		// Convert decimal values if posted back
		if ($this->maintenance_total_cost->FormValue == $this->maintenance_total_cost->CurrentValue && is_numeric(ew_StrToFloat($this->maintenance_total_cost->CurrentValue)))
			$this->maintenance_total_cost->CurrentValue = ew_StrToFloat($this->maintenance_total_cost->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date
		// reference_id
		// part_name
		// gen_name
		// quantity_in
		// quantity_used
		// cost
		// total_quantity
		// total_cost
		// maintenance_total_cost
		// maintenance_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// reference_id
		if (strval($this->reference_id->CurrentValue) <> "") {
			$sFilterWrk = "`reference_id`" . ew_SearchString("=", $this->reference_id->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `reference_id`, `reference_id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_maintenance`";
		$sWhereWrk = "";
		$this->reference_id->LookupFilters = array();
		$lookuptblfilter = "`flag`='0'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reference_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->reference_id->ViewValue = $this->reference_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
			}
		} else {
			$this->reference_id->ViewValue = NULL;
		}
		$this->reference_id->ViewCustomAttributes = "";

		// part_name
		if (strval($this->part_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
		$sWhereWrk = "";
		$this->part_name->LookupFilters = array("dx1" => '`part_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->part_name->ViewValue = $this->part_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->part_name->ViewValue = $this->part_name->CurrentValue;
			}
		} else {
			$this->part_name->ViewValue = NULL;
		}
		$this->part_name->ViewCustomAttributes = "";

		// gen_name
		$this->gen_name->ViewValue = $this->gen_name->CurrentValue;
		$this->gen_name->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_used
		$this->quantity_used->ViewValue = $this->quantity_used->CurrentValue;
		$this->quantity_used->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// total_cost
		$this->total_cost->ViewValue = $this->total_cost->CurrentValue;
		$this->total_cost->ViewValue = ew_FormatNumber($this->total_cost->ViewValue, 0, -2, -2, -2);
		$this->total_cost->ViewCustomAttributes = "";

		// maintenance_total_cost
		$this->maintenance_total_cost->ViewValue = $this->maintenance_total_cost->CurrentValue;
		$this->maintenance_total_cost->ViewCustomAttributes = "";

		// maintenance_id
		$this->maintenance_id->ViewValue = $this->maintenance_id->CurrentValue;
		$this->maintenance_id->ViewCustomAttributes = "";

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

			// part_name
			$this->part_name->LinkCustomAttributes = "";
			$this->part_name->HrefValue = "";
			$this->part_name->TooltipValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";
			$this->gen_name->TooltipValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_used
			$this->quantity_used->LinkCustomAttributes = "";
			$this->quantity_used->HrefValue = "";
			$this->quantity_used->TooltipValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// total_cost
			$this->total_cost->LinkCustomAttributes = "";
			$this->total_cost->HrefValue = "";
			$this->total_cost->TooltipValue = "";

			// maintenance_total_cost
			$this->maintenance_total_cost->LinkCustomAttributes = "";
			$this->maintenance_total_cost->HrefValue = "";
			$this->maintenance_total_cost->TooltipValue = "";

			// maintenance_id
			$this->maintenance_id->LinkCustomAttributes = "";
			$this->maintenance_id->HrefValue = "";
			$this->maintenance_id->TooltipValue = "";
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
			if (trim(strval($this->reference_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`reference_id`" . ew_SearchString("=", $this->reference_id->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `reference_id`, `reference_id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `gen_maintenance`";
			$sWhereWrk = "";
			$this->reference_id->LookupFilters = array();
			$lookuptblfilter = "`flag`='0'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->reference_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->reference_id->EditValue = $arwrk;

			// part_name
			$this->part_name->EditCustomAttributes = "";
			if (trim(strval($this->part_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sparepart_module`";
			$sWhereWrk = "";
			$this->part_name->LookupFilters = array("dx1" => '`part_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->part_name->ViewValue = $this->part_name->DisplayValue($arwrk);
			} else {
				$this->part_name->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->part_name->EditValue = $arwrk;

			// gen_name
			$this->gen_name->EditAttrs["class"] = "form-control";
			$this->gen_name->EditCustomAttributes = "";
			$this->gen_name->EditValue = ew_HtmlEncode($this->gen_name->CurrentValue);
			$this->gen_name->PlaceHolder = ew_RemoveHtml($this->gen_name->FldCaption());

			// quantity_in
			$this->quantity_in->EditAttrs["class"] = "form-control";
			$this->quantity_in->EditCustomAttributes = "";
			$this->quantity_in->EditValue = ew_HtmlEncode($this->quantity_in->CurrentValue);
			$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

			// quantity_used
			$this->quantity_used->EditAttrs["class"] = "form-control";
			$this->quantity_used->EditCustomAttributes = "";
			$this->quantity_used->EditValue = ew_HtmlEncode($this->quantity_used->CurrentValue);
			$this->quantity_used->PlaceHolder = ew_RemoveHtml($this->quantity_used->FldCaption());

			// cost
			$this->cost->EditAttrs["class"] = "form-control";
			$this->cost->EditCustomAttributes = "";
			$this->cost->EditValue = ew_HtmlEncode($this->cost->CurrentValue);
			$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());
			if (strval($this->cost->EditValue) <> "" && is_numeric($this->cost->EditValue)) $this->cost->EditValue = ew_FormatNumber($this->cost->EditValue, -2, -1, -2, 0);

			// total_quantity
			$this->total_quantity->EditAttrs["class"] = "form-control";
			$this->total_quantity->EditCustomAttributes = "";
			$this->total_quantity->EditValue = ew_HtmlEncode($this->total_quantity->CurrentValue);
			$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

			// total_cost
			$this->total_cost->EditAttrs["class"] = "form-control";
			$this->total_cost->EditCustomAttributes = "";
			$this->total_cost->EditValue = ew_HtmlEncode($this->total_cost->CurrentValue);
			$this->total_cost->PlaceHolder = ew_RemoveHtml($this->total_cost->FldCaption());
			if (strval($this->total_cost->EditValue) <> "" && is_numeric($this->total_cost->EditValue)) $this->total_cost->EditValue = ew_FormatNumber($this->total_cost->EditValue, -2, -2, -2, -2);

			// maintenance_total_cost
			$this->maintenance_total_cost->EditAttrs["class"] = "form-control";
			$this->maintenance_total_cost->EditCustomAttributes = "";
			$this->maintenance_total_cost->EditValue = ew_HtmlEncode($this->maintenance_total_cost->CurrentValue);
			$this->maintenance_total_cost->PlaceHolder = ew_RemoveHtml($this->maintenance_total_cost->FldCaption());
			if (strval($this->maintenance_total_cost->EditValue) <> "" && is_numeric($this->maintenance_total_cost->EditValue)) $this->maintenance_total_cost->EditValue = ew_FormatNumber($this->maintenance_total_cost->EditValue, -2, -1, -2, 0);

			// maintenance_id
			$this->maintenance_id->EditAttrs["class"] = "form-control";
			$this->maintenance_id->EditCustomAttributes = "";
			$this->maintenance_id->EditValue = ew_HtmlEncode($this->maintenance_id->CurrentValue);
			$this->maintenance_id->PlaceHolder = ew_RemoveHtml($this->maintenance_id->FldCaption());

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

			// part_name
			$this->part_name->LinkCustomAttributes = "";
			$this->part_name->HrefValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";

			// quantity_used
			$this->quantity_used->LinkCustomAttributes = "";
			$this->quantity_used->HrefValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";

			// total_cost
			$this->total_cost->LinkCustomAttributes = "";
			$this->total_cost->HrefValue = "";

			// maintenance_total_cost
			$this->maintenance_total_cost->LinkCustomAttributes = "";
			$this->maintenance_total_cost->HrefValue = "";

			// maintenance_id
			$this->maintenance_id->LinkCustomAttributes = "";
			$this->maintenance_id->HrefValue = "";
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
		if (!$this->part_name->FldIsDetailKey && !is_null($this->part_name->FormValue) && $this->part_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->part_name->FldCaption(), $this->part_name->ReqErrMsg));
		}
		if (!$this->gen_name->FldIsDetailKey && !is_null($this->gen_name->FormValue) && $this->gen_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gen_name->FldCaption(), $this->gen_name->ReqErrMsg));
		}
		if (!$this->quantity_in->FldIsDetailKey && !is_null($this->quantity_in->FormValue) && $this->quantity_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity_in->FldCaption(), $this->quantity_in->ReqErrMsg));
		}
		if (!$this->quantity_used->FldIsDetailKey && !is_null($this->quantity_used->FormValue) && $this->quantity_used->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity_used->FldCaption(), $this->quantity_used->ReqErrMsg));
		}
		if (!$this->cost->FldIsDetailKey && !is_null($this->cost->FormValue) && $this->cost->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cost->FldCaption(), $this->cost->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->cost->FldErrMsg());
		}
		if (!$this->total_quantity->FldIsDetailKey && !is_null($this->total_quantity->FormValue) && $this->total_quantity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->total_quantity->FldCaption(), $this->total_quantity->ReqErrMsg));
		}
		if (!$this->total_cost->FldIsDetailKey && !is_null($this->total_cost->FormValue) && $this->total_cost->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->total_cost->FldCaption(), $this->total_cost->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->total_cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->total_cost->FldErrMsg());
		}
		if (!ew_CheckNumber($this->maintenance_total_cost->FormValue)) {
			ew_AddMessage($gsFormError, $this->maintenance_total_cost->FldErrMsg());
		}
		if (!ew_CheckInteger($this->maintenance_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->maintenance_id->FldErrMsg());
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
			$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 0), NULL, $this->date->ReadOnly);

			// reference_id
			$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, NULL, $this->reference_id->ReadOnly);

			// part_name
			$this->part_name->SetDbValueDef($rsnew, $this->part_name->CurrentValue, NULL, $this->part_name->ReadOnly);

			// gen_name
			$this->gen_name->SetDbValueDef($rsnew, $this->gen_name->CurrentValue, NULL, $this->gen_name->ReadOnly);

			// quantity_in
			$this->quantity_in->SetDbValueDef($rsnew, $this->quantity_in->CurrentValue, NULL, $this->quantity_in->ReadOnly);

			// quantity_used
			$this->quantity_used->SetDbValueDef($rsnew, $this->quantity_used->CurrentValue, NULL, $this->quantity_used->ReadOnly);

			// cost
			$this->cost->SetDbValueDef($rsnew, $this->cost->CurrentValue, NULL, $this->cost->ReadOnly);

			// total_quantity
			$this->total_quantity->SetDbValueDef($rsnew, $this->total_quantity->CurrentValue, NULL, $this->total_quantity->ReadOnly);

			// total_cost
			$this->total_cost->SetDbValueDef($rsnew, $this->total_cost->CurrentValue, NULL, $this->total_cost->ReadOnly);

			// maintenance_total_cost
			$this->maintenance_total_cost->SetDbValueDef($rsnew, $this->maintenance_total_cost->CurrentValue, NULL, $this->maintenance_total_cost->ReadOnly);

			// maintenance_id
			$this->maintenance_id->SetDbValueDef($rsnew, $this->maintenance_id->CurrentValue, NULL, $this->maintenance_id->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("spare_part_usagelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_reference_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `reference_id` AS `LinkFld`, `reference_id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_maintenance`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$lookuptblfilter = "`flag`='0'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`reference_id` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->reference_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_part_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`part_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($spare_part_usage_edit)) $spare_part_usage_edit = new cspare_part_usage_edit();

// Page init
$spare_part_usage_edit->Page_Init();

// Page main
$spare_part_usage_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$spare_part_usage_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fspare_part_usageedit = new ew_Form("fspare_part_usageedit", "edit");

// Validate form
fspare_part_usageedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->date->FldCaption(), $spare_part_usage->date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_part_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->part_name->FldCaption(), $spare_part_usage->part_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gen_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->gen_name->FldCaption(), $spare_part_usage->gen_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->quantity_in->FldCaption(), $spare_part_usage->quantity_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity_used");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->quantity_used->FldCaption(), $spare_part_usage->quantity_used->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cost");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->cost->FldCaption(), $spare_part_usage->cost->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->cost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total_quantity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->total_quantity->FldCaption(), $spare_part_usage->total_quantity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total_cost");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $spare_part_usage->total_cost->FldCaption(), $spare_part_usage->total_cost->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->total_cost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maintenance_total_cost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->maintenance_total_cost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maintenance_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($spare_part_usage->maintenance_id->FldErrMsg()) ?>");

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
fspare_part_usageedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fspare_part_usageedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fspare_part_usageedit.Lists["x_reference_id"] = {"LinkField":"x_reference_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_reference_id","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_maintenance"};
fspare_part_usageedit.Lists["x_reference_id"].Data = "<?php echo $spare_part_usage_edit->reference_id->LookupFilterQuery(FALSE, "edit") ?>";
fspare_part_usageedit.Lists["x_part_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_part_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sparepart_module"};
fspare_part_usageedit.Lists["x_part_name"].Data = "<?php echo $spare_part_usage_edit->part_name->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
// Function to calculate total for a row
function calculateTotal(el) {
	var $row = $(el).closest("tr"); // get the row container

	// Get cost and labour fee values
	var cost = parseFloat($row.find("input[name*='cost']").val()) || 0;
	var labourFee = parseFloat($row.find("input[name*='labour_fee']").val()) || 0;

	// Calculate total
	var total = cost + labourFee;

	// Update total field in this row
	$row.find("input[name*='total']").val(total.toFixed(2));
}

// Bind function to labour_fee inputs dynamically
$(document).on("input", "input[name*='labour_fee']", function() {
	calculateTotal(this);
});

// Optional: calculate totals on page load (if cost/labour_fee are prefilled)
$(document).ready(function() {
	$("input[name*='labour_fee']").each(function() {
		calculateTotal(this);
	});
});
</script>
<?php $spare_part_usage_edit->ShowPageHeader(); ?>
<?php
$spare_part_usage_edit->ShowMessage();
?>
<?php if (!$spare_part_usage_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($spare_part_usage_edit->Pager)) $spare_part_usage_edit->Pager = new cPrevNextPager($spare_part_usage_edit->StartRec, $spare_part_usage_edit->DisplayRecs, $spare_part_usage_edit->TotalRecs, $spare_part_usage_edit->AutoHidePager) ?>
<?php if ($spare_part_usage_edit->Pager->RecordCount > 0 && $spare_part_usage_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($spare_part_usage_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $spare_part_usage_edit->PageUrl() ?>start=<?php echo $spare_part_usage_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($spare_part_usage_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $spare_part_usage_edit->PageUrl() ?>start=<?php echo $spare_part_usage_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $spare_part_usage_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($spare_part_usage_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $spare_part_usage_edit->PageUrl() ?>start=<?php echo $spare_part_usage_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($spare_part_usage_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $spare_part_usage_edit->PageUrl() ?>start=<?php echo $spare_part_usage_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $spare_part_usage_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fspare_part_usageedit" id="fspare_part_usageedit" class="<?php echo $spare_part_usage_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($spare_part_usage_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $spare_part_usage_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="spare_part_usage">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($spare_part_usage_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($spare_part_usage->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_spare_part_usage_id" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->id->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->id->CellAttributes() ?>>
<span id="el_spare_part_usage_id">
<span<?php echo $spare_part_usage->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $spare_part_usage->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="spare_part_usage" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($spare_part_usage->id->CurrentValue) ?>">
<?php echo $spare_part_usage->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_spare_part_usage_date" for="x_date" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->date->CellAttributes() ?>>
<span id="el_spare_part_usage_date">
<input type="text" data-table="spare_part_usage" data-field="x_date" name="x_date" id="x_date" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->date->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->date->EditValue ?>"<?php echo $spare_part_usage->date->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_spare_part_usage_reference_id" for="x_reference_id" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->reference_id->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->reference_id->CellAttributes() ?>>
<span id="el_spare_part_usage_reference_id">
<select data-table="spare_part_usage" data-field="x_reference_id" data-value-separator="<?php echo $spare_part_usage->reference_id->DisplayValueSeparatorAttribute() ?>" id="x_reference_id" name="x_reference_id"<?php echo $spare_part_usage->reference_id->EditAttributes() ?>>
<?php echo $spare_part_usage->reference_id->SelectOptionListHtml("x_reference_id") ?>
</select>
</span>
<?php echo $spare_part_usage->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->part_name->Visible) { // part_name ?>
	<div id="r_part_name" class="form-group">
		<label id="elh_spare_part_usage_part_name" for="x_part_name" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->part_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->part_name->CellAttributes() ?>>
<span id="el_spare_part_usage_part_name">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_part_name"><?php echo (strval($spare_part_usage->part_name->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $spare_part_usage->part_name->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($spare_part_usage->part_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_part_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($spare_part_usage->part_name->ReadOnly || $spare_part_usage->part_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="spare_part_usage" data-field="x_part_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $spare_part_usage->part_name->DisplayValueSeparatorAttribute() ?>" name="x_part_name" id="x_part_name" value="<?php echo $spare_part_usage->part_name->CurrentValue ?>"<?php echo $spare_part_usage->part_name->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->part_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->gen_name->Visible) { // gen_name ?>
	<div id="r_gen_name" class="form-group">
		<label id="elh_spare_part_usage_gen_name" for="x_gen_name" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->gen_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->gen_name->CellAttributes() ?>>
<span id="el_spare_part_usage_gen_name">
<input type="text" data-table="spare_part_usage" data-field="x_gen_name" name="x_gen_name" id="x_gen_name" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->gen_name->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->gen_name->EditValue ?>"<?php echo $spare_part_usage->gen_name->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->gen_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->quantity_in->Visible) { // quantity_in ?>
	<div id="r_quantity_in" class="form-group">
		<label id="elh_spare_part_usage_quantity_in" for="x_quantity_in" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->quantity_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->quantity_in->CellAttributes() ?>>
<span id="el_spare_part_usage_quantity_in">
<input type="text" data-table="spare_part_usage" data-field="x_quantity_in" name="x_quantity_in" id="x_quantity_in" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->quantity_in->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->quantity_in->EditValue ?>"<?php echo $spare_part_usage->quantity_in->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->quantity_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->quantity_used->Visible) { // quantity_used ?>
	<div id="r_quantity_used" class="form-group">
		<label id="elh_spare_part_usage_quantity_used" for="x_quantity_used" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->quantity_used->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->quantity_used->CellAttributes() ?>>
<span id="el_spare_part_usage_quantity_used">
<input type="text" data-table="spare_part_usage" data-field="x_quantity_used" name="x_quantity_used" id="x_quantity_used" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->quantity_used->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->quantity_used->EditValue ?>"<?php echo $spare_part_usage->quantity_used->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->quantity_used->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->cost->Visible) { // cost ?>
	<div id="r_cost" class="form-group">
		<label id="elh_spare_part_usage_cost" for="x_cost" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->cost->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->cost->CellAttributes() ?>>
<span id="el_spare_part_usage_cost">
<input type="text" data-table="spare_part_usage" data-field="x_cost" name="x_cost" id="x_cost" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->cost->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->cost->EditValue ?>"<?php echo $spare_part_usage->cost->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->total_quantity->Visible) { // total_quantity ?>
	<div id="r_total_quantity" class="form-group">
		<label id="elh_spare_part_usage_total_quantity" for="x_total_quantity" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->total_quantity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->total_quantity->CellAttributes() ?>>
<span id="el_spare_part_usage_total_quantity">
<input type="text" data-table="spare_part_usage" data-field="x_total_quantity" name="x_total_quantity" id="x_total_quantity" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->total_quantity->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->total_quantity->EditValue ?>"<?php echo $spare_part_usage->total_quantity->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->total_quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->total_cost->Visible) { // total_cost ?>
	<div id="r_total_cost" class="form-group">
		<label id="elh_spare_part_usage_total_cost" for="x_total_cost" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->total_cost->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->total_cost->CellAttributes() ?>>
<span id="el_spare_part_usage_total_cost">
<input type="text" data-table="spare_part_usage" data-field="x_total_cost" name="x_total_cost" id="x_total_cost" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->total_cost->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->total_cost->EditValue ?>"<?php echo $spare_part_usage->total_cost->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->total_cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->maintenance_total_cost->Visible) { // maintenance_total_cost ?>
	<div id="r_maintenance_total_cost" class="form-group">
		<label id="elh_spare_part_usage_maintenance_total_cost" for="x_maintenance_total_cost" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->maintenance_total_cost->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->maintenance_total_cost->CellAttributes() ?>>
<span id="el_spare_part_usage_maintenance_total_cost">
<input type="text" data-table="spare_part_usage" data-field="x_maintenance_total_cost" name="x_maintenance_total_cost" id="x_maintenance_total_cost" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->maintenance_total_cost->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->maintenance_total_cost->EditValue ?>"<?php echo $spare_part_usage->maintenance_total_cost->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->maintenance_total_cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($spare_part_usage->maintenance_id->Visible) { // maintenance_id ?>
	<div id="r_maintenance_id" class="form-group">
		<label id="elh_spare_part_usage_maintenance_id" for="x_maintenance_id" class="<?php echo $spare_part_usage_edit->LeftColumnClass ?>"><?php echo $spare_part_usage->maintenance_id->FldCaption() ?></label>
		<div class="<?php echo $spare_part_usage_edit->RightColumnClass ?>"><div<?php echo $spare_part_usage->maintenance_id->CellAttributes() ?>>
<span id="el_spare_part_usage_maintenance_id">
<input type="text" data-table="spare_part_usage" data-field="x_maintenance_id" name="x_maintenance_id" id="x_maintenance_id" size="30" placeholder="<?php echo ew_HtmlEncode($spare_part_usage->maintenance_id->getPlaceHolder()) ?>" value="<?php echo $spare_part_usage->maintenance_id->EditValue ?>"<?php echo $spare_part_usage->maintenance_id->EditAttributes() ?>>
</span>
<?php echo $spare_part_usage->maintenance_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$spare_part_usage_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $spare_part_usage_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $spare_part_usage_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fspare_part_usageedit.Init();
</script>
<?php
$spare_part_usage_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_maintenance_total_cost").hide();
$('#x_date').attr('readonly',true);
$('#x_part_name').attr('readonly',true);
$('#x_gen_name').attr('readonly',true);

//$('#x_maintenance_id').attr('readonly',true);
$('#x_quantity_in').attr('readonly',true);
$('#x_cost').attr('readonly',true);
$('#x_total_quantity').attr('readonly',true);
$('#x_total_cost').attr('readonly',true);
$('#x_maintenance_total_cost').attr('readonly',true);
$("#r_maintenance_id").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$spare_part_usage_edit->Page_Terminate();
?>
