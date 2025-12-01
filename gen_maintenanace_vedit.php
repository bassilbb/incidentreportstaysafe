<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "gen_maintenanace_vinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$gen_maintenanace_v_edit = NULL; // Initialize page object first

class cgen_maintenanace_v_edit extends cgen_maintenanace_v {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'gen_maintenanace_v';

	// Page object name
	var $PageObjName = 'gen_maintenanace_v_edit';

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

		// Table object (gen_maintenanace_v)
		if (!isset($GLOBALS["gen_maintenanace_v"]) || get_class($GLOBALS["gen_maintenanace_v"]) == "cgen_maintenanace_v") {
			$GLOBALS["gen_maintenanace_v"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gen_maintenanace_v"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gen_maintenanace_v');

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
				$this->Page_Terminate(ew_GetUrl("gen_maintenanace_vlist.php"));
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
		$this->datetime->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->gen_name->SetVisibility();
		$this->maintenance_type->SetVisibility();
		$this->running_hours->SetVisibility();
		$this->cost->SetVisibility();
		$this->labour_fee->SetVisibility();
		$this->total->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->status->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->approver_date->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approver_comment->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->flag->SetVisibility();

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
		global $EW_EXPORT, $gen_maintenanace_v;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($gen_maintenanace_v);
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
					if ($pageName == "gen_maintenanace_vview.php")
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
			$this->Page_Terminate("gen_maintenanace_vlist.php"); // Return to list page
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
					$this->Page_Terminate("gen_maintenanace_vlist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "gen_maintenanace_vlist.php")
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
		if (!$this->datetime->FldIsDetailKey) {
			$this->datetime->setFormValue($objForm->GetValue("x_datetime"));
			$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 0);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->gen_name->FldIsDetailKey) {
			$this->gen_name->setFormValue($objForm->GetValue("x_gen_name"));
		}
		if (!$this->maintenance_type->FldIsDetailKey) {
			$this->maintenance_type->setFormValue($objForm->GetValue("x_maintenance_type"));
		}
		if (!$this->running_hours->FldIsDetailKey) {
			$this->running_hours->setFormValue($objForm->GetValue("x_running_hours"));
		}
		if (!$this->cost->FldIsDetailKey) {
			$this->cost->setFormValue($objForm->GetValue("x_cost"));
		}
		if (!$this->labour_fee->FldIsDetailKey) {
			$this->labour_fee->setFormValue($objForm->GetValue("x_labour_fee"));
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->initiator_action->FldIsDetailKey) {
			$this->initiator_action->setFormValue($objForm->GetValue("x_initiator_action"));
		}
		if (!$this->initiator_comment->FldIsDetailKey) {
			$this->initiator_comment->setFormValue($objForm->GetValue("x_initiator_comment"));
		}
		if (!$this->approver_date->FldIsDetailKey) {
			$this->approver_date->setFormValue($objForm->GetValue("x_approver_date"));
			$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		}
		if (!$this->approver_action->FldIsDetailKey) {
			$this->approver_action->setFormValue($objForm->GetValue("x_approver_action"));
		}
		if (!$this->approver_comment->FldIsDetailKey) {
			$this->approver_comment->setFormValue($objForm->GetValue("x_approver_comment"));
		}
		if (!$this->approved_by->FldIsDetailKey) {
			$this->approved_by->setFormValue($objForm->GetValue("x_approved_by"));
		}
		if (!$this->flag->FldIsDetailKey) {
			$this->flag->setFormValue($objForm->GetValue("x_flag"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->datetime->CurrentValue = $this->datetime->FormValue;
		$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 0);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->gen_name->CurrentValue = $this->gen_name->FormValue;
		$this->maintenance_type->CurrentValue = $this->maintenance_type->FormValue;
		$this->running_hours->CurrentValue = $this->running_hours->FormValue;
		$this->cost->CurrentValue = $this->cost->FormValue;
		$this->labour_fee->CurrentValue = $this->labour_fee->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
		$this->approver_date->CurrentValue = $this->approver_date->FormValue;
		$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		$this->approver_action->CurrentValue = $this->approver_action->FormValue;
		$this->approver_comment->CurrentValue = $this->approver_comment->FormValue;
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->flag->CurrentValue = $this->flag->FormValue;
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
		$this->datetime->setDbValue($row['datetime']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->gen_name->setDbValue($row['gen_name']);
		$this->maintenance_type->setDbValue($row['maintenance_type']);
		$this->running_hours->setDbValue($row['running_hours']);
		$this->cost->setDbValue($row['cost']);
		$this->labour_fee->setDbValue($row['labour_fee']);
		$this->total->setDbValue($row['total']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->approver_date->setDbValue($row['approver_date']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->flag->setDbValue($row['flag']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['datetime'] = NULL;
		$row['reference_id'] = NULL;
		$row['gen_name'] = NULL;
		$row['maintenance_type'] = NULL;
		$row['running_hours'] = NULL;
		$row['cost'] = NULL;
		$row['labour_fee'] = NULL;
		$row['total'] = NULL;
		$row['staff_id'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['approver_date'] = NULL;
		$row['approver_action'] = NULL;
		$row['approver_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['flag'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime->DbValue = $row['datetime'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->gen_name->DbValue = $row['gen_name'];
		$this->maintenance_type->DbValue = $row['maintenance_type'];
		$this->running_hours->DbValue = $row['running_hours'];
		$this->cost->DbValue = $row['cost'];
		$this->labour_fee->DbValue = $row['labour_fee'];
		$this->total->DbValue = $row['total'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->flag->DbValue = $row['flag'];
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
		if ($this->labour_fee->FormValue == $this->labour_fee->CurrentValue && is_numeric(ew_StrToFloat($this->labour_fee->CurrentValue)))
			$this->labour_fee->CurrentValue = ew_StrToFloat($this->labour_fee->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// datetime
		// reference_id
		// gen_name
		// maintenance_type
		// running_hours
		// cost
		// labour_fee
		// total
		// staff_id
		// status
		// initiator_action
		// initiator_comment
		// approver_date
		// approver_action
		// approver_comment
		// approved_by
		// flag

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime
		$this->datetime->ViewValue = $this->datetime->CurrentValue;
		$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 0);
		$this->datetime->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// gen_name
		if (strval($this->gen_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
		$sWhereWrk = "";
		$this->gen_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->gen_name->ViewValue = $this->gen_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gen_name->ViewValue = $this->gen_name->CurrentValue;
			}
		} else {
			$this->gen_name->ViewValue = NULL;
		}
		$this->gen_name->ViewCustomAttributes = "";

		// maintenance_type
		if (strval($this->maintenance_type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintenance_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintenance_type`";
		$sWhereWrk = "";
		$this->maintenance_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintenance_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->maintenance_type->ViewValue = $this->maintenance_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintenance_type->ViewValue = $this->maintenance_type->CurrentValue;
			}
		} else {
			$this->maintenance_type->ViewValue = NULL;
		}
		$this->maintenance_type->ViewCustomAttributes = "";

		// running_hours
		$this->running_hours->ViewValue = $this->running_hours->CurrentValue;
		$this->running_hours->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// labour_fee
		$this->labour_fee->ViewValue = $this->labour_fee->CurrentValue;
		$this->labour_fee->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// staff_id
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

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_status`";
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

		// approver_date
		$this->approver_date->ViewValue = $this->approver_date->CurrentValue;
		$this->approver_date->ViewValue = ew_FormatDateTime($this->approver_date->ViewValue, 0);
		$this->approver_date->ViewCustomAttributes = "";

		// approver_action
		if (strval($this->approver_action->CurrentValue) <> "") {
			$this->approver_action->ViewValue = $this->approver_action->OptionCaption($this->approver_action->CurrentValue);
		} else {
			$this->approver_action->ViewValue = NULL;
		}
		$this->approver_action->ViewCustomAttributes = "";

		// approver_comment
		$this->approver_comment->ViewValue = $this->approver_comment->CurrentValue;
		$this->approver_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
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

		// flag
		$this->flag->ViewValue = $this->flag->CurrentValue;
		$this->flag->ViewCustomAttributes = "";

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";
			$this->gen_name->TooltipValue = "";

			// maintenance_type
			$this->maintenance_type->LinkCustomAttributes = "";
			$this->maintenance_type->HrefValue = "";
			$this->maintenance_type->TooltipValue = "";

			// running_hours
			$this->running_hours->LinkCustomAttributes = "";
			$this->running_hours->HrefValue = "";
			$this->running_hours->TooltipValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";

			// labour_fee
			$this->labour_fee->LinkCustomAttributes = "";
			$this->labour_fee->HrefValue = "";
			$this->labour_fee->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";
			$this->approver_date->TooltipValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";
			$this->approver_action->TooltipValue = "";

			// approver_comment
			$this->approver_comment->LinkCustomAttributes = "";
			$this->approver_comment->HrefValue = "";
			$this->approver_comment->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// flag
			$this->flag->LinkCustomAttributes = "";
			$this->flag->HrefValue = "";
			$this->flag->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// datetime
			$this->datetime->EditAttrs["class"] = "form-control";
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = $this->datetime->CurrentValue;
			$this->datetime->EditValue = ew_FormatDateTime($this->datetime->EditValue, 0);
			$this->datetime->ViewCustomAttributes = "";

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = $this->reference_id->CurrentValue;
			$this->reference_id->ViewCustomAttributes = "";

			// gen_name
			$this->gen_name->EditAttrs["class"] = "form-control";
			$this->gen_name->EditCustomAttributes = "";
			if (strval($this->gen_name->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
			$sWhereWrk = "";
			$this->gen_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$arwrk[2] = $rswrk->fields('Disp2Fld');
					$arwrk[3] = $rswrk->fields('Disp3Fld');
					$this->gen_name->EditValue = $this->gen_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->gen_name->EditValue = $this->gen_name->CurrentValue;
				}
			} else {
				$this->gen_name->EditValue = NULL;
			}
			$this->gen_name->ViewCustomAttributes = "";

			// maintenance_type
			$this->maintenance_type->EditAttrs["class"] = "form-control";
			$this->maintenance_type->EditCustomAttributes = "";
			if (strval($this->maintenance_type->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintenance_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintenance_type`";
			$sWhereWrk = "";
			$this->maintenance_type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->maintenance_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->maintenance_type->EditValue = $this->maintenance_type->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->maintenance_type->EditValue = $this->maintenance_type->CurrentValue;
				}
			} else {
				$this->maintenance_type->EditValue = NULL;
			}
			$this->maintenance_type->ViewCustomAttributes = "";

			// running_hours
			$this->running_hours->EditAttrs["class"] = "form-control";
			$this->running_hours->EditCustomAttributes = "";
			$this->running_hours->EditValue = $this->running_hours->CurrentValue;
			$this->running_hours->ViewCustomAttributes = "";

			// cost
			$this->cost->EditAttrs["class"] = "form-control";
			$this->cost->EditCustomAttributes = "";
			$this->cost->EditValue = $this->cost->CurrentValue;
			$this->cost->ViewCustomAttributes = "";

			// labour_fee
			$this->labour_fee->EditAttrs["class"] = "form-control";
			$this->labour_fee->EditCustomAttributes = "";
			$this->labour_fee->EditValue = $this->labour_fee->CurrentValue;
			$this->labour_fee->ViewCustomAttributes = "";

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = $this->total->CurrentValue;
			$this->total->ViewCustomAttributes = "";

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
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
					$this->staff_id->EditValue = $this->staff_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_id->EditValue = $this->staff_id->CurrentValue;
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->ViewCustomAttributes = "";

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `gen_status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// initiator_action
			$this->initiator_action->EditAttrs["class"] = "form-control";
			$this->initiator_action->EditCustomAttributes = "";
			if (strval($this->initiator_action->CurrentValue) <> "") {
				$this->initiator_action->EditValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
			} else {
				$this->initiator_action->EditValue = NULL;
			}
			$this->initiator_action->ViewCustomAttributes = "";

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = $this->initiator_comment->CurrentValue;
			$this->initiator_comment->ViewCustomAttributes = "";

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->approver_date->CurrentValue, 8));
			$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

			// approver_action
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

			// approver_comment
			$this->approver_comment->EditAttrs["class"] = "form-control";
			$this->approver_comment->EditCustomAttributes = "";
			$this->approver_comment->EditValue = ew_HtmlEncode($this->approver_comment->CurrentValue);
			$this->approver_comment->PlaceHolder = ew_RemoveHtml($this->approver_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->approved_by->EditValue = $this->approved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// flag
			$this->flag->EditAttrs["class"] = "form-control";
			$this->flag->EditCustomAttributes = "";
			$this->flag->EditValue = $this->flag->CurrentValue;
			$this->flag->ViewCustomAttributes = "";

			// Edit refer script
			// datetime

			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// gen_name
			$this->gen_name->LinkCustomAttributes = "";
			$this->gen_name->HrefValue = "";
			$this->gen_name->TooltipValue = "";

			// maintenance_type
			$this->maintenance_type->LinkCustomAttributes = "";
			$this->maintenance_type->HrefValue = "";
			$this->maintenance_type->TooltipValue = "";

			// running_hours
			$this->running_hours->LinkCustomAttributes = "";
			$this->running_hours->HrefValue = "";
			$this->running_hours->TooltipValue = "";

			// cost
			$this->cost->LinkCustomAttributes = "";
			$this->cost->HrefValue = "";
			$this->cost->TooltipValue = "";

			// labour_fee
			$this->labour_fee->LinkCustomAttributes = "";
			$this->labour_fee->HrefValue = "";
			$this->labour_fee->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";

			// approver_comment
			$this->approver_comment->LinkCustomAttributes = "";
			$this->approver_comment->HrefValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";

			// flag
			$this->flag->LinkCustomAttributes = "";
			$this->flag->HrefValue = "";
			$this->flag->TooltipValue = "";
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
		if (!ew_CheckDateDef($this->approver_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->approver_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_by->FldErrMsg());
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

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// approver_date
			$this->approver_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->approver_date->CurrentValue, 0), NULL, $this->approver_date->ReadOnly);

			// approver_action
			$this->approver_action->SetDbValueDef($rsnew, $this->approver_action->CurrentValue, NULL, $this->approver_action->ReadOnly);

			// approver_comment
			$this->approver_comment->SetDbValueDef($rsnew, $this->approver_comment->CurrentValue, NULL, $this->approver_comment->ReadOnly);

			// approved_by
			$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, $this->approved_by->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("gen_maintenanace_vlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->approved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($gen_maintenanace_v_edit)) $gen_maintenanace_v_edit = new cgen_maintenanace_v_edit();

// Page init
$gen_maintenanace_v_edit->Page_Init();

// Page main
$gen_maintenanace_v_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gen_maintenanace_v_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fgen_maintenanace_vedit = new ew_Form("fgen_maintenanace_vedit", "edit");

// Validate form
fgen_maintenanace_vedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_approver_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenanace_v->approver_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gen_maintenanace_v->approved_by->FldErrMsg()) ?>");

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
fgen_maintenanace_vedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fgen_maintenanace_vedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fgen_maintenanace_vedit.Lists["x_gen_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","x_kva",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fgen_maintenanace_vedit.Lists["x_gen_name"].Data = "<?php echo $gen_maintenanace_v_edit->gen_name->LookupFilterQuery(FALSE, "edit") ?>";
fgen_maintenanace_vedit.Lists["x_maintenance_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintenance_type"};
fgen_maintenanace_vedit.Lists["x_maintenance_type"].Data = "<?php echo $gen_maintenanace_v_edit->maintenance_type->LookupFilterQuery(FALSE, "edit") ?>";
fgen_maintenanace_vedit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenanace_vedit.Lists["x_staff_id"].Data = "<?php echo $gen_maintenanace_v_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
fgen_maintenanace_vedit.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_status"};
fgen_maintenanace_vedit.Lists["x_status"].Data = "<?php echo $gen_maintenanace_v_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
fgen_maintenanace_vedit.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenanace_vedit.Lists["x_initiator_action"].Options = <?php echo json_encode($gen_maintenanace_v_edit->initiator_action->Options()) ?>;
fgen_maintenanace_vedit.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fgen_maintenanace_vedit.Lists["x_approver_action"].Options = <?php echo json_encode($gen_maintenanace_v_edit->approver_action->Options()) ?>;
fgen_maintenanace_vedit.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fgen_maintenanace_vedit.Lists["x_approved_by"].Data = "<?php echo $gen_maintenanace_v_edit->approved_by->LookupFilterQuery(FALSE, "edit") ?>";
fgen_maintenanace_vedit.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $gen_maintenanace_v_edit->approved_by->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $gen_maintenanace_v_edit->ShowPageHeader(); ?>
<?php
$gen_maintenanace_v_edit->ShowMessage();
?>
<?php if (!$gen_maintenanace_v_edit->IsModal) { ?>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { // Confirm page ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($gen_maintenanace_v_edit->Pager)) $gen_maintenanace_v_edit->Pager = new cPrevNextPager($gen_maintenanace_v_edit->StartRec, $gen_maintenanace_v_edit->DisplayRecs, $gen_maintenanace_v_edit->TotalRecs, $gen_maintenanace_v_edit->AutoHidePager) ?>
<?php if ($gen_maintenanace_v_edit->Pager->RecordCount > 0 && $gen_maintenanace_v_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($gen_maintenanace_v_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $gen_maintenanace_v_edit->PageUrl() ?>start=<?php echo $gen_maintenanace_v_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($gen_maintenanace_v_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $gen_maintenanace_v_edit->PageUrl() ?>start=<?php echo $gen_maintenanace_v_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $gen_maintenanace_v_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($gen_maintenanace_v_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $gen_maintenanace_v_edit->PageUrl() ?>start=<?php echo $gen_maintenanace_v_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($gen_maintenanace_v_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $gen_maintenanace_v_edit->PageUrl() ?>start=<?php echo $gen_maintenanace_v_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $gen_maintenanace_v_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fgen_maintenanace_vedit" id="fgen_maintenanace_vedit" class="<?php echo $gen_maintenanace_v_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($gen_maintenanace_v_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $gen_maintenanace_v_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="gen_maintenanace_v">
<?php if ($gen_maintenanace_v->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_edit" id="a_edit" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($gen_maintenanace_v_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($gen_maintenanace_v->datetime->Visible) { // datetime ?>
	<div id="r_datetime" class="form-group">
		<label id="elh_gen_maintenanace_v_datetime" for="x_datetime" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->datetime->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->datetime->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_datetime">
<span<?php echo $gen_maintenanace_v->datetime->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->datetime->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_datetime" data-page="1" name="x_datetime" id="x_datetime" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->datetime->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_datetime">
<span<?php echo $gen_maintenanace_v->datetime->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->datetime->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_datetime" data-page="1" name="x_datetime" id="x_datetime" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->datetime->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->datetime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_gen_maintenanace_v_reference_id" for="x_reference_id" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->reference_id->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->reference_id->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_reference_id">
<span<?php echo $gen_maintenanace_v->reference_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->reference_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->reference_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_reference_id">
<span<?php echo $gen_maintenanace_v->reference_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->reference_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->reference_id->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->gen_name->Visible) { // gen_name ?>
	<div id="r_gen_name" class="form-group">
		<label id="elh_gen_maintenanace_v_gen_name" for="x_gen_name" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->gen_name->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->gen_name->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_gen_name">
<span<?php echo $gen_maintenanace_v->gen_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->gen_name->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_gen_name" data-page="1" name="x_gen_name" id="x_gen_name" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->gen_name->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_gen_name">
<span<?php echo $gen_maintenanace_v->gen_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->gen_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_gen_name" data-page="1" name="x_gen_name" id="x_gen_name" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->gen_name->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->gen_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->maintenance_type->Visible) { // maintenance_type ?>
	<div id="r_maintenance_type" class="form-group">
		<label id="elh_gen_maintenanace_v_maintenance_type" for="x_maintenance_type" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->maintenance_type->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->maintenance_type->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_maintenance_type">
<span<?php echo $gen_maintenanace_v->maintenance_type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->maintenance_type->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_maintenance_type" data-page="1" name="x_maintenance_type" id="x_maintenance_type" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->maintenance_type->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_maintenance_type">
<span<?php echo $gen_maintenanace_v->maintenance_type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->maintenance_type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_maintenance_type" data-page="1" name="x_maintenance_type" id="x_maintenance_type" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->maintenance_type->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->maintenance_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->running_hours->Visible) { // running_hours ?>
	<div id="r_running_hours" class="form-group">
		<label id="elh_gen_maintenanace_v_running_hours" for="x_running_hours" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->running_hours->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->running_hours->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_running_hours">
<span<?php echo $gen_maintenanace_v->running_hours->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->running_hours->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_running_hours" data-page="1" name="x_running_hours" id="x_running_hours" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->running_hours->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_running_hours">
<span<?php echo $gen_maintenanace_v->running_hours->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->running_hours->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_running_hours" data-page="1" name="x_running_hours" id="x_running_hours" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->running_hours->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->running_hours->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->cost->Visible) { // cost ?>
	<div id="r_cost" class="form-group">
		<label id="elh_gen_maintenanace_v_cost" for="x_cost" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->cost->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->cost->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_cost">
<span<?php echo $gen_maintenanace_v->cost->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->cost->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_cost" data-page="1" name="x_cost" id="x_cost" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->cost->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_cost">
<span<?php echo $gen_maintenanace_v->cost->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->cost->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_cost" data-page="1" name="x_cost" id="x_cost" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->cost->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->cost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->labour_fee->Visible) { // labour_fee ?>
	<div id="r_labour_fee" class="form-group">
		<label id="elh_gen_maintenanace_v_labour_fee" for="x_labour_fee" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->labour_fee->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->labour_fee->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_labour_fee">
<span<?php echo $gen_maintenanace_v->labour_fee->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->labour_fee->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_labour_fee" data-page="1" name="x_labour_fee" id="x_labour_fee" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->labour_fee->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_labour_fee">
<span<?php echo $gen_maintenanace_v->labour_fee->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->labour_fee->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_labour_fee" data-page="1" name="x_labour_fee" id="x_labour_fee" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->labour_fee->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->labour_fee->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_gen_maintenanace_v_total" for="x_total" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->total->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->total->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_total">
<span<?php echo $gen_maintenanace_v->total->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->total->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_total" data-page="1" name="x_total" id="x_total" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->total->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_total">
<span<?php echo $gen_maintenanace_v->total->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->total->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_total" data-page="1" name="x_total" id="x_total" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->total->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_gen_maintenanace_v_staff_id" for="x_staff_id" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->staff_id->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->staff_id->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_staff_id">
<span<?php echo $gen_maintenanace_v->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->staff_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->staff_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_staff_id">
<span<?php echo $gen_maintenanace_v->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_gen_maintenanace_v_status" for="x_status" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->status->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->status->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_status">
<select data-table="gen_maintenanace_v" data-field="x_status" data-page="1" data-value-separator="<?php echo $gen_maintenanace_v->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $gen_maintenanace_v->status->EditAttributes() ?>>
<?php echo $gen_maintenanace_v->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php } else { ?>
<span id="el_gen_maintenanace_v_status">
<span<?php echo $gen_maintenanace_v->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_status" data-page="1" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->status->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_gen_maintenanace_v_initiator_action" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->initiator_action->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->initiator_action->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_initiator_action">
<span<?php echo $gen_maintenanace_v->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->initiator_action->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_initiator_action" data-page="1" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->initiator_action->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_initiator_action">
<span<?php echo $gen_maintenanace_v->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->initiator_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_initiator_action" data-page="1" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->initiator_action->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_gen_maintenanace_v_initiator_comment" for="x_initiator_comment" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->initiator_comment->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->initiator_comment->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_initiator_comment">
<span<?php echo $gen_maintenanace_v->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->initiator_comment->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_initiator_comment" data-page="1" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->initiator_comment->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_initiator_comment">
<span<?php echo $gen_maintenanace_v->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->initiator_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_initiator_comment" data-page="1" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->initiator_comment->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label id="elh_gen_maintenanace_v_approver_date" for="x_approver_date" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->approver_date->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->approver_date->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_approver_date">
<input type="text" data-table="gen_maintenanace_v" data-field="x_approver_date" data-page="1" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenanace_v->approver_date->getPlaceHolder()) ?>" value="<?php echo $gen_maintenanace_v->approver_date->EditValue ?>"<?php echo $gen_maintenanace_v->approver_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_gen_maintenanace_v_approver_date">
<span<?php echo $gen_maintenanace_v->approver_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->approver_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_approver_date" data-page="1" name="x_approver_date" id="x_approver_date" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->approver_date->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->approver_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_gen_maintenanace_v_approver_action" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->approver_action->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->approver_action->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="gen_maintenanace_v" data-field="x_approver_action" data-page="1" data-value-separator="<?php echo $gen_maintenanace_v->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $gen_maintenanace_v->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $gen_maintenanace_v->approver_action->RadioButtonListHtml(FALSE, "x_approver_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_gen_maintenanace_v_approver_action">
<span<?php echo $gen_maintenanace_v->approver_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->approver_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_approver_action" data-page="1" name="x_approver_action" id="x_approver_action" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->approver_action->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label id="elh_gen_maintenanace_v_approver_comment" for="x_approver_comment" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->approver_comment->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->approver_comment->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_approver_comment">
<textarea data-table="gen_maintenanace_v" data-field="x_approver_comment" data-page="1" name="x_approver_comment" id="x_approver_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($gen_maintenanace_v->approver_comment->getPlaceHolder()) ?>"<?php echo $gen_maintenanace_v->approver_comment->EditAttributes() ?>><?php echo $gen_maintenanace_v->approver_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_gen_maintenanace_v_approver_comment">
<span<?php echo $gen_maintenanace_v->approver_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->approver_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_approver_comment" data-page="1" name="x_approver_comment" id="x_approver_comment" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->approver_comment->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->approver_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_gen_maintenanace_v_approved_by" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->approved_by->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->approved_by->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_approved_by">
<?php
$wrkonchange = trim(" " . @$gen_maintenanace_v->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$gen_maintenanace_v->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $gen_maintenanace_v->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gen_maintenanace_v->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($gen_maintenanace_v->approved_by->getPlaceHolder()) ?>"<?php echo $gen_maintenanace_v->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_approved_by" data-page="1" data-value-separator="<?php echo $gen_maintenanace_v->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fgen_maintenanace_vedit.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_gen_maintenanace_v_approved_by">
<span<?php echo $gen_maintenanace_v->approved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->approved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_approved_by" data-page="1" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->approved_by->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($gen_maintenanace_v->flag->Visible) { // flag ?>
	<div id="r_flag" class="form-group">
		<label id="elh_gen_maintenanace_v_flag" for="x_flag" class="<?php echo $gen_maintenanace_v_edit->LeftColumnClass ?>"><?php echo $gen_maintenanace_v->flag->FldCaption() ?></label>
		<div class="<?php echo $gen_maintenanace_v_edit->RightColumnClass ?>"><div<?php echo $gen_maintenanace_v->flag->CellAttributes() ?>>
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { ?>
<span id="el_gen_maintenanace_v_flag">
<span<?php echo $gen_maintenanace_v->flag->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->flag->EditValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_flag" data-page="1" name="x_flag" id="x_flag" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->flag->CurrentValue) ?>">
<?php } else { ?>
<span id="el_gen_maintenanace_v_flag">
<span<?php echo $gen_maintenanace_v->flag->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $gen_maintenanace_v->flag->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_flag" data-page="1" name="x_flag" id="x_flag" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->flag->FormValue) ?>">
<?php } ?>
<?php echo $gen_maintenanace_v->flag->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<input type="hidden" data-table="gen_maintenanace_v" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($gen_maintenanace_v->id->CurrentValue) ?>">
<?php if (!$gen_maintenanace_v_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $gen_maintenanace_v_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($gen_maintenanace_v->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $gen_maintenanace_v_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fgen_maintenanace_vedit.Init();
</script>
<?php
$gen_maintenanace_v_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('#x_approver_date').attr('readonly',true);
$('#x_approved_by').attr('readonly',true);
$("#r_flag").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$gen_maintenanace_v_edit->Page_Terminate();
?>
