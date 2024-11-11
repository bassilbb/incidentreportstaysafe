<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "dispenserinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$dispenser_edit = NULL; // Initialize page object first

class cdispenser_edit extends cdispenser {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'dispenser';

	// Page object name
	var $PageObjName = 'dispenser_edit';

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

		// Table object (dispenser)
		if (!isset($GLOBALS["dispenser"]) || get_class($GLOBALS["dispenser"]) == "cdispenser") {
			$GLOBALS["dispenser"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dispenser"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'dispenser', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("dispenserlist.php"));
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
		$this->date_initiated->SetVisibility();
		$this->referrence_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->fullname->SetVisibility();
		$this->department->SetVisibility();
		$this->location->SetVisibility();
		$this->sub_location->SetVisibility();
		$this->venue->SetVisibility();
		$this->type->SetVisibility();
		$this->action_taken->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->status->SetVisibility();
		$this->initiated_by->SetVisibility();

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
		global $EW_EXPORT, $dispenser;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dispenser);
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
					if ($pageName == "dispenserview.php")
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
			$this->Page_Terminate("dispenserlist.php"); // Return to list page
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
					$this->Page_Terminate("dispenserlist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "dispenserlist.php")
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
		if (!$this->date_initiated->FldIsDetailKey) {
			$this->date_initiated->setFormValue($objForm->GetValue("x_date_initiated"));
			$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0);
		}
		if (!$this->referrence_id->FldIsDetailKey) {
			$this->referrence_id->setFormValue($objForm->GetValue("x_referrence_id"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->fullname->FldIsDetailKey) {
			$this->fullname->setFormValue($objForm->GetValue("x_fullname"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->location->FldIsDetailKey) {
			$this->location->setFormValue($objForm->GetValue("x_location"));
		}
		if (!$this->sub_location->FldIsDetailKey) {
			$this->sub_location->setFormValue($objForm->GetValue("x_sub_location"));
		}
		if (!$this->venue->FldIsDetailKey) {
			$this->venue->setFormValue($objForm->GetValue("x_venue"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->action_taken->FldIsDetailKey) {
			$this->action_taken->setFormValue($objForm->GetValue("x_action_taken"));
		}
		if (!$this->initiator_action->FldIsDetailKey) {
			$this->initiator_action->setFormValue($objForm->GetValue("x_initiator_action"));
		}
		if (!$this->initiator_comment->FldIsDetailKey) {
			$this->initiator_comment->setFormValue($objForm->GetValue("x_initiator_comment"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->initiated_by->FldIsDetailKey) {
			$this->initiated_by->setFormValue($objForm->GetValue("x_initiated_by"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->date_initiated->CurrentValue = $this->date_initiated->FormValue;
		$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0);
		$this->referrence_id->CurrentValue = $this->referrence_id->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->fullname->CurrentValue = $this->fullname->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->location->CurrentValue = $this->location->FormValue;
		$this->sub_location->CurrentValue = $this->sub_location->FormValue;
		$this->venue->CurrentValue = $this->venue->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->action_taken->CurrentValue = $this->action_taken->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->initiated_by->CurrentValue = $this->initiated_by->FormValue;
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
		$this->referrence_id->setDbValue($row['referrence_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->fullname->setDbValue($row['fullname']);
		$this->department->setDbValue($row['department']);
		$this->location->setDbValue($row['location']);
		$this->sub_location->setDbValue($row['sub_location']);
		$this->venue->setDbValue($row['venue']);
		$this->type->setDbValue($row['type']);
		$this->action_taken->setDbValue($row['action_taken']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->status->setDbValue($row['status']);
		$this->initiated_by->setDbValue($row['initiated_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['referrence_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['fullname'] = NULL;
		$row['department'] = NULL;
		$row['location'] = NULL;
		$row['sub_location'] = NULL;
		$row['venue'] = NULL;
		$row['type'] = NULL;
		$row['action_taken'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['status'] = NULL;
		$row['initiated_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->referrence_id->DbValue = $row['referrence_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->fullname->DbValue = $row['fullname'];
		$this->department->DbValue = $row['department'];
		$this->location->DbValue = $row['location'];
		$this->sub_location->DbValue = $row['sub_location'];
		$this->venue->DbValue = $row['venue'];
		$this->type->DbValue = $row['type'];
		$this->action_taken->DbValue = $row['action_taken'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->status->DbValue = $row['status'];
		$this->initiated_by->DbValue = $row['initiated_by'];
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
		// date_initiated
		// referrence_id
		// staff_id
		// fullname
		// department
		// location
		// sub_location
		// venue
		// type
		// action_taken
		// initiator_action
		// initiator_comment
		// status
		// initiated_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// referrence_id
		$this->referrence_id->ViewValue = $this->referrence_id->CurrentValue;
		$this->referrence_id->ViewCustomAttributes = "";

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

		// fullname
		$this->fullname->ViewValue = $this->fullname->CurrentValue;
		if (strval($this->fullname->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->fullname->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->fullname->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->fullname->ViewValue = $this->fullname->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->fullname->ViewValue = $this->fullname->CurrentValue;
			}
		} else {
			$this->fullname->ViewValue = NULL;
		}
		$this->fullname->ViewCustomAttributes = "";

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

		// location
		if (strval($this->location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->location->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->location->ViewValue = $this->location->CurrentValue;
			}
		} else {
			$this->location->ViewValue = NULL;
		}
		$this->location->ViewCustomAttributes = "";

		// sub_location
		if (strval($this->sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_sub` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_location->ViewValue = $this->sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_location->ViewValue = $this->sub_location->CurrentValue;
			}
		} else {
			$this->sub_location->ViewValue = NULL;
		}
		$this->sub_location->ViewCustomAttributes = "";

		// venue
		if (strval($this->venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->venue->ViewValue = $this->venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->venue->ViewValue = $this->venue->CurrentValue;
			}
		} else {
			$this->venue->ViewValue = NULL;
		}
		$this->venue->ViewCustomAttributes = "";

		// type
		if (strval($this->type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_type`";
		$sWhereWrk = "";
		$this->type->LookupFilters = array("dx1" => '`description`', "dx2" => '`serial_no`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->type->ViewValue = $this->type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->type->ViewValue = $this->type->CurrentValue;
			}
		} else {
			$this->type->ViewValue = NULL;
		}
		$this->type->ViewCustomAttributes = "";

		// action_taken
		if (strval($this->action_taken->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->action_taken->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `action_taken`";
		$sWhereWrk = "";
		$this->action_taken->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->action_taken, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->action_taken->ViewValue = $this->action_taken->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->action_taken->ViewValue = $this->action_taken->CurrentValue;
			}
		} else {
			$this->action_taken->ViewValue = NULL;
		}
		$this->action_taken->ViewCustomAttributes = "";

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

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
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

		// initiated_by
		$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
		if (strval($this->initiated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->initiated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->initiated_by->ViewValue = $this->initiated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
			}
		} else {
			$this->initiated_by->ViewValue = NULL;
		}
		$this->initiated_by->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// referrence_id
			$this->referrence_id->LinkCustomAttributes = "";
			$this->referrence_id->HrefValue = "";
			$this->referrence_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// fullname
			$this->fullname->LinkCustomAttributes = "";
			$this->fullname->HrefValue = "";
			$this->fullname->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";
			$this->location->TooltipValue = "";

			// sub_location
			$this->sub_location->LinkCustomAttributes = "";
			$this->sub_location->HrefValue = "";
			$this->sub_location->TooltipValue = "";

			// venue
			$this->venue->LinkCustomAttributes = "";
			$this->venue->HrefValue = "";
			$this->venue->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// action_taken
			$this->action_taken->LinkCustomAttributes = "";
			$this->action_taken->HrefValue = "";
			$this->action_taken->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// initiated_by
			$this->initiated_by->LinkCustomAttributes = "";
			$this->initiated_by->HrefValue = "";
			$this->initiated_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_initiated->CurrentValue, 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// referrence_id
			$this->referrence_id->EditAttrs["class"] = "form-control";
			$this->referrence_id->EditCustomAttributes = "";
			$this->referrence_id->EditValue = ew_HtmlEncode($this->referrence_id->CurrentValue);
			$this->referrence_id->PlaceHolder = ew_RemoveHtml($this->referrence_id->FldCaption());

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

			// fullname
			$this->fullname->EditAttrs["class"] = "form-control";
			$this->fullname->EditCustomAttributes = "";
			$this->fullname->EditValue = ew_HtmlEncode($this->fullname->CurrentValue);
			if (strval($this->fullname->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->fullname->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->fullname->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->fullname->EditValue = $this->fullname->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->fullname->EditValue = ew_HtmlEncode($this->fullname->CurrentValue);
				}
			} else {
				$this->fullname->EditValue = NULL;
			}
			$this->fullname->PlaceHolder = ew_RemoveHtml($this->fullname->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";
			$this->department->EditValue = ew_HtmlEncode($this->department->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->department->EditValue = $this->department->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->department->EditValue = ew_HtmlEncode($this->department->CurrentValue);
				}
			} else {
				$this->department->EditValue = NULL;
			}
			$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

			// location
			$this->location->EditCustomAttributes = "";
			if (trim(strval($this->location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_location`";
			$sWhereWrk = "";
			$this->location->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
			} else {
				$this->location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->location->EditValue = $arwrk;

			// sub_location
			$this->sub_location->EditCustomAttributes = "";
			if (trim(strval($this->sub_location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_sub_location`";
			$sWhereWrk = "";
			$this->sub_location->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sub_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_sub` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->sub_location->ViewValue = $this->sub_location->DisplayValue($arwrk);
			} else {
				$this->sub_location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->sub_location->EditValue = $arwrk;

			// venue
			$this->venue->EditAttrs["class"] = "form-control";
			$this->venue->EditCustomAttributes = "";
			if (trim(strval($this->venue->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->venue->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_sub` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_venue`";
			$sWhereWrk = "";
			$this->venue->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->venue, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->venue->EditValue = $arwrk;

			// type
			$this->type->EditCustomAttributes = "";
			if (trim(strval($this->type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dispenser_type`";
			$sWhereWrk = "";
			$this->type->LookupFilters = array("dx1" => '`description`', "dx2" => '`serial_no`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->type->ViewValue = $this->type->DisplayValue($arwrk);
			} else {
				$this->type->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->type->EditValue = $arwrk;

			// action_taken
			$this->action_taken->EditAttrs["class"] = "form-control";
			$this->action_taken->EditCustomAttributes = "";
			if (trim(strval($this->action_taken->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->action_taken->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `action_taken`";
			$sWhereWrk = "";
			$this->action_taken->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->action_taken, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->action_taken->EditValue = $arwrk;

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			if (strval($this->status->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->status->EditValue = $this->status->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
				}
			} else {
				$this->status->EditValue = NULL;
			}
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// initiated_by
			$this->initiated_by->EditAttrs["class"] = "form-control";
			$this->initiated_by->EditCustomAttributes = "";
			$this->initiated_by->EditValue = ew_HtmlEncode($this->initiated_by->CurrentValue);
			if (strval($this->initiated_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->initiated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->initiated_by->EditValue = $this->initiated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->initiated_by->EditValue = ew_HtmlEncode($this->initiated_by->CurrentValue);
				}
			} else {
				$this->initiated_by->EditValue = NULL;
			}
			$this->initiated_by->PlaceHolder = ew_RemoveHtml($this->initiated_by->FldCaption());

			// Edit refer script
			// date_initiated

			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";

			// referrence_id
			$this->referrence_id->LinkCustomAttributes = "";
			$this->referrence_id->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// fullname
			$this->fullname->LinkCustomAttributes = "";
			$this->fullname->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";

			// sub_location
			$this->sub_location->LinkCustomAttributes = "";
			$this->sub_location->HrefValue = "";

			// venue
			$this->venue->LinkCustomAttributes = "";
			$this->venue->HrefValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";

			// action_taken
			$this->action_taken->LinkCustomAttributes = "";
			$this->action_taken->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// initiated_by
			$this->initiated_by->LinkCustomAttributes = "";
			$this->initiated_by->HrefValue = "";
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
		if (!$this->date_initiated->FldIsDetailKey && !is_null($this->date_initiated->FormValue) && $this->date_initiated->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date_initiated->FldCaption(), $this->date_initiated->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date_initiated->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_initiated->FldErrMsg());
		}
		if (!$this->referrence_id->FldIsDetailKey && !is_null($this->referrence_id->FormValue) && $this->referrence_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->referrence_id->FldCaption(), $this->referrence_id->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!$this->fullname->FldIsDetailKey && !is_null($this->fullname->FormValue) && $this->fullname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fullname->FldCaption(), $this->fullname->ReqErrMsg));
		}
		if (!$this->department->FldIsDetailKey && !is_null($this->department->FormValue) && $this->department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->department->FldCaption(), $this->department->ReqErrMsg));
		}
		if (!$this->location->FldIsDetailKey && !is_null($this->location->FormValue) && $this->location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->location->FldCaption(), $this->location->ReqErrMsg));
		}
		if (!$this->sub_location->FldIsDetailKey && !is_null($this->sub_location->FormValue) && $this->sub_location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sub_location->FldCaption(), $this->sub_location->ReqErrMsg));
		}
		if (!$this->venue->FldIsDetailKey && !is_null($this->venue->FormValue) && $this->venue->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->venue->FldCaption(), $this->venue->ReqErrMsg));
		}
		if (!$this->action_taken->FldIsDetailKey && !is_null($this->action_taken->FormValue) && $this->action_taken->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->action_taken->FldCaption(), $this->action_taken->ReqErrMsg));
		}
		if ($this->initiator_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiator_action->FldCaption(), $this->initiator_action->ReqErrMsg));
		}
		if (!$this->initiator_comment->FldIsDetailKey && !is_null($this->initiator_comment->FormValue) && $this->initiator_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiator_comment->FldCaption(), $this->initiator_comment->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!$this->initiated_by->FldIsDetailKey && !is_null($this->initiated_by->FormValue) && $this->initiated_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiated_by->FldCaption(), $this->initiated_by->ReqErrMsg));
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

			// date_initiated
			$this->date_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0), ew_CurrentDate(), $this->date_initiated->ReadOnly);

			// referrence_id
			$this->referrence_id->SetDbValueDef($rsnew, $this->referrence_id->CurrentValue, NULL, $this->referrence_id->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, $this->staff_id->ReadOnly);

			// fullname
			$this->fullname->SetDbValueDef($rsnew, $this->fullname->CurrentValue, NULL, $this->fullname->ReadOnly);

			// department
			$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, $this->department->ReadOnly);

			// location
			$this->location->SetDbValueDef($rsnew, $this->location->CurrentValue, NULL, $this->location->ReadOnly);

			// sub_location
			$this->sub_location->SetDbValueDef($rsnew, $this->sub_location->CurrentValue, NULL, $this->sub_location->ReadOnly);

			// venue
			$this->venue->SetDbValueDef($rsnew, $this->venue->CurrentValue, NULL, $this->venue->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, $this->type->ReadOnly);

			// action_taken
			$this->action_taken->SetDbValueDef($rsnew, $this->action_taken->CurrentValue, NULL, $this->action_taken->ReadOnly);

			// initiator_action
			$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, $this->initiator_action->ReadOnly);

			// initiator_comment
			$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, $this->initiator_comment->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// initiated_by
			$this->initiated_by->SetDbValueDef($rsnew, $this->initiated_by->CurrentValue, NULL, $this->initiated_by->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("dispenserlist.php"), "", $this->TableVar, TRUE);
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
		case "x_fullname":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id` AS `LinkFld`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`department_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sub_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_sub` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_sub` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sub_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_sub` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_venue":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_sub` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->venue, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_type`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`', "dx2" => '`serial_no`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_action_taken":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `action_taken`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->action_taken, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_initiated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_fullname":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->fullname) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld` FROM `depertment`";
			$sWhereWrk = "`department_name` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld` FROM `dispenser_status`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_initiated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->initiated_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($dispenser_edit)) $dispenser_edit = new cdispenser_edit();

// Page init
$dispenser_edit->Page_Init();

// Page main
$dispenser_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dispenser_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdispenseredit = new ew_Form("fdispenseredit", "edit");

// Validate form
fdispenseredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_date_initiated");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->date_initiated->FldCaption(), $dispenser->date_initiated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_initiated");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dispenser->date_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_referrence_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->referrence_id->FldCaption(), $dispenser->referrence_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->staff_id->FldCaption(), $dispenser->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fullname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->fullname->FldCaption(), $dispenser->fullname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->department->FldCaption(), $dispenser->department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->location->FldCaption(), $dispenser->location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sub_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->sub_location->FldCaption(), $dispenser->sub_location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_venue");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->venue->FldCaption(), $dispenser->venue->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_action_taken");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->action_taken->FldCaption(), $dispenser->action_taken->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->initiator_action->FldCaption(), $dispenser->initiator_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->initiator_comment->FldCaption(), $dispenser->initiator_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->status->FldCaption(), $dispenser->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiated_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dispenser->initiated_by->FldCaption(), $dispenser->initiated_by->ReqErrMsg)) ?>");

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
fdispenseredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdispenseredit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdispenseredit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenseredit.Lists["x_staff_id"].Data = "<?php echo $dispenser_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_edit->staff_id->LookupFilterQuery(TRUE, "edit"))) ?>;
fdispenseredit.Lists["x_fullname"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenseredit.Lists["x_fullname"].Data = "<?php echo $dispenser_edit->fullname->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.AutoSuggests["x_fullname"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_edit->fullname->LookupFilterQuery(TRUE, "edit"))) ?>;
fdispenseredit.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fdispenseredit.Lists["x_department"].Data = "<?php echo $dispenser_edit->department->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_edit->department->LookupFilterQuery(TRUE, "edit"))) ?>;
fdispenseredit.Lists["x_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
fdispenseredit.Lists["x_location"].Data = "<?php echo $dispenser_edit->location->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.Lists["x_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_location"],"ChildFields":["x_venue"],"FilterFields":["x_code_id"],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
fdispenseredit.Lists["x_sub_location"].Data = "<?php echo $dispenser_edit->sub_location->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.Lists["x_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_sub_location"],"ChildFields":[],"FilterFields":["x_code_sub"],"Options":[],"Template":"","LinkTable":"incident_venue"};
fdispenseredit.Lists["x_venue"].Data = "<?php echo $dispenser_edit->venue->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.Lists["x_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","x_serial_no","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_type"};
fdispenseredit.Lists["x_type"].Data = "<?php echo $dispenser_edit->type->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.Lists["x_action_taken"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"action_taken"};
fdispenseredit.Lists["x_action_taken"].Data = "<?php echo $dispenser_edit->action_taken->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdispenseredit.Lists["x_initiator_action"].Options = <?php echo json_encode($dispenser_edit->initiator_action->Options()) ?>;
fdispenseredit.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"dispenser_status"};
fdispenseredit.Lists["x_status"].Data = "<?php echo $dispenser_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_edit->status->LookupFilterQuery(TRUE, "edit"))) ?>;
fdispenseredit.Lists["x_initiated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdispenseredit.Lists["x_initiated_by"].Data = "<?php echo $dispenser_edit->initiated_by->LookupFilterQuery(FALSE, "edit") ?>";
fdispenseredit.AutoSuggests["x_initiated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $dispenser_edit->initiated_by->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $dispenser_edit->ShowPageHeader(); ?>
<?php
$dispenser_edit->ShowMessage();
?>
<?php if (!$dispenser_edit->IsModal) { ?>
<?php if ($dispenser->CurrentAction <> "F") { // Confirm page ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($dispenser_edit->Pager)) $dispenser_edit->Pager = new cPrevNextPager($dispenser_edit->StartRec, $dispenser_edit->DisplayRecs, $dispenser_edit->TotalRecs, $dispenser_edit->AutoHidePager) ?>
<?php if ($dispenser_edit->Pager->RecordCount > 0 && $dispenser_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($dispenser_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $dispenser_edit->PageUrl() ?>start=<?php echo $dispenser_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($dispenser_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $dispenser_edit->PageUrl() ?>start=<?php echo $dispenser_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $dispenser_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($dispenser_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $dispenser_edit->PageUrl() ?>start=<?php echo $dispenser_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($dispenser_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $dispenser_edit->PageUrl() ?>start=<?php echo $dispenser_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $dispenser_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fdispenseredit" id="fdispenseredit" class="<?php echo $dispenser_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dispenser_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dispenser_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dispenser">
<?php if ($dispenser->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_edit" id="a_edit" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($dispenser_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($dispenser->date_initiated->Visible) { // date_initiated ?>
	<div id="r_date_initiated" class="form-group">
		<label id="elh_dispenser_date_initiated" for="x_date_initiated" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->date_initiated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->date_initiated->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_date_initiated">
<input type="text" data-table="dispenser" data-field="x_date_initiated" data-page="1" name="x_date_initiated" id="x_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser->date_initiated->getPlaceHolder()) ?>" value="<?php echo $dispenser->date_initiated->EditValue ?>"<?php echo $dispenser->date_initiated->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_dispenser_date_initiated">
<span<?php echo $dispenser->date_initiated->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->date_initiated->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_date_initiated" data-page="1" name="x_date_initiated" id="x_date_initiated" value="<?php echo ew_HtmlEncode($dispenser->date_initiated->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->date_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->referrence_id->Visible) { // referrence_id ?>
	<div id="r_referrence_id" class="form-group">
		<label id="elh_dispenser_referrence_id" for="x_referrence_id" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->referrence_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->referrence_id->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_referrence_id">
<input type="text" data-table="dispenser" data-field="x_referrence_id" data-page="1" name="x_referrence_id" id="x_referrence_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($dispenser->referrence_id->getPlaceHolder()) ?>" value="<?php echo $dispenser->referrence_id->EditValue ?>"<?php echo $dispenser->referrence_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_dispenser_referrence_id">
<span<?php echo $dispenser->referrence_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->referrence_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_referrence_id" data-page="1" name="x_referrence_id" id="x_referrence_id" value="<?php echo ew_HtmlEncode($dispenser->referrence_id->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->referrence_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_dispenser_staff_id" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->staff_id->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_staff_id">
<?php
$wrkonchange = trim(" " . @$dispenser->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$dispenser->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $dispenser->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($dispenser->staff_id->getPlaceHolder()) ?>"<?php echo $dispenser->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="dispenser" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $dispenser->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($dispenser->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdispenseredit.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_dispenser_staff_id">
<span<?php echo $dispenser->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($dispenser->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->fullname->Visible) { // fullname ?>
	<div id="r_fullname" class="form-group">
		<label id="elh_dispenser_fullname" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->fullname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->fullname->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_fullname">
<?php
$wrkonchange = trim(" " . @$dispenser->fullname->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$dispenser->fullname->EditAttrs["onchange"] = "";
?>
<span id="as_x_fullname" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_fullname" id="sv_x_fullname" value="<?php echo $dispenser->fullname->EditValue ?>" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($dispenser->fullname->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($dispenser->fullname->getPlaceHolder()) ?>"<?php echo $dispenser->fullname->EditAttributes() ?>>
</span>
<input type="hidden" data-table="dispenser" data-field="x_fullname" data-page="1" data-value-separator="<?php echo $dispenser->fullname->DisplayValueSeparatorAttribute() ?>" name="x_fullname" id="x_fullname" value="<?php echo ew_HtmlEncode($dispenser->fullname->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdispenseredit.CreateAutoSuggest({"id":"x_fullname","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_dispenser_fullname">
<span<?php echo $dispenser->fullname->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->fullname->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_fullname" data-page="1" name="x_fullname" id="x_fullname" value="<?php echo ew_HtmlEncode($dispenser->fullname->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->fullname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_dispenser_department" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->department->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_department">
<?php
$wrkonchange = trim(" " . @$dispenser->department->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$dispenser->department->EditAttrs["onchange"] = "";
?>
<span id="as_x_department" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_department" id="sv_x_department" value="<?php echo $dispenser->department->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser->department->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($dispenser->department->getPlaceHolder()) ?>"<?php echo $dispenser->department->EditAttributes() ?>>
</span>
<input type="hidden" data-table="dispenser" data-field="x_department" data-page="1" data-value-separator="<?php echo $dispenser->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($dispenser->department->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdispenseredit.CreateAutoSuggest({"id":"x_department","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_dispenser_department">
<span<?php echo $dispenser->department->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->department->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_department" data-page="1" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($dispenser->department->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->location->Visible) { // location ?>
	<div id="r_location" class="form-group">
		<label id="elh_dispenser_location" for="x_location" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->location->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_location">
<?php $dispenser->location->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$dispenser->location->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_location"><?php echo (strval($dispenser->location->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $dispenser->location->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($dispenser->location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($dispenser->location->ReadOnly || $dispenser->location->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="dispenser" data-field="x_location" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $dispenser->location->DisplayValueSeparatorAttribute() ?>" name="x_location" id="x_location" value="<?php echo $dispenser->location->CurrentValue ?>"<?php echo $dispenser->location->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_dispenser_location">
<span<?php echo $dispenser->location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_location" data-page="1" name="x_location" id="x_location" value="<?php echo ew_HtmlEncode($dispenser->location->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->sub_location->Visible) { // sub_location ?>
	<div id="r_sub_location" class="form-group">
		<label id="elh_dispenser_sub_location" for="x_sub_location" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->sub_location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->sub_location->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_sub_location">
<?php $dispenser->sub_location->EditAttrs["onclick"] = "ew_UpdateOpt.call(this); " . @$dispenser->sub_location->EditAttrs["onclick"]; ?>
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($dispenser->sub_location->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $dispenser->sub_location->ViewValue ?>
	</span>
	<?php if (!$dispenser->sub_location->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_sub_location" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $dispenser->sub_location->RadioButtonListHtml(TRUE, "x_sub_location", 1) ?>
		</div>
	</div>
	<div id="tp_x_sub_location" class="ewTemplate"><input type="radio" data-table="dispenser" data-field="x_sub_location" data-page="1" data-value-separator="<?php echo $dispenser->sub_location->DisplayValueSeparatorAttribute() ?>" name="x_sub_location" id="x_sub_location" value="{value}"<?php echo $dispenser->sub_location->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_dispenser_sub_location">
<span<?php echo $dispenser->sub_location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->sub_location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_sub_location" data-page="1" name="x_sub_location" id="x_sub_location" value="<?php echo ew_HtmlEncode($dispenser->sub_location->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->sub_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->venue->Visible) { // venue ?>
	<div id="r_venue" class="form-group">
		<label id="elh_dispenser_venue" for="x_venue" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->venue->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->venue->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_venue">
<select data-table="dispenser" data-field="x_venue" data-page="1" data-value-separator="<?php echo $dispenser->venue->DisplayValueSeparatorAttribute() ?>" id="x_venue" name="x_venue"<?php echo $dispenser->venue->EditAttributes() ?>>
<?php echo $dispenser->venue->SelectOptionListHtml("x_venue") ?>
</select>
</span>
<?php } else { ?>
<span id="el_dispenser_venue">
<span<?php echo $dispenser->venue->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->venue->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_venue" data-page="1" name="x_venue" id="x_venue" value="<?php echo ew_HtmlEncode($dispenser->venue->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->venue->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_dispenser_type" for="x_type" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->type->FldCaption() ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->type->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_type">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_type"><?php echo (strval($dispenser->type->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $dispenser->type->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($dispenser->type->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_type',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($dispenser->type->ReadOnly || $dispenser->type->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="dispenser" data-field="x_type" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $dispenser->type->DisplayValueSeparatorAttribute() ?>" name="x_type" id="x_type" value="<?php echo $dispenser->type->CurrentValue ?>"<?php echo $dispenser->type->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_dispenser_type">
<span<?php echo $dispenser->type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_type" data-page="1" name="x_type" id="x_type" value="<?php echo ew_HtmlEncode($dispenser->type->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->action_taken->Visible) { // action_taken ?>
	<div id="r_action_taken" class="form-group">
		<label id="elh_dispenser_action_taken" for="x_action_taken" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->action_taken->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->action_taken->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_action_taken">
<select data-table="dispenser" data-field="x_action_taken" data-page="1" data-value-separator="<?php echo $dispenser->action_taken->DisplayValueSeparatorAttribute() ?>" id="x_action_taken" name="x_action_taken"<?php echo $dispenser->action_taken->EditAttributes() ?>>
<?php echo $dispenser->action_taken->SelectOptionListHtml("x_action_taken") ?>
</select>
</span>
<?php } else { ?>
<span id="el_dispenser_action_taken">
<span<?php echo $dispenser->action_taken->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->action_taken->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_action_taken" data-page="1" name="x_action_taken" id="x_action_taken" value="<?php echo ew_HtmlEncode($dispenser->action_taken->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->action_taken->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_dispenser_initiator_action" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->initiator_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->initiator_action->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="dispenser" data-field="x_initiator_action" data-page="1" data-value-separator="<?php echo $dispenser->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $dispenser->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $dispenser->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_dispenser_initiator_action">
<span<?php echo $dispenser->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->initiator_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_initiator_action" data-page="1" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($dispenser->initiator_action->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_dispenser_initiator_comment" for="x_initiator_comment" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->initiator_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->initiator_comment->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_initiator_comment">
<textarea data-table="dispenser" data-field="x_initiator_comment" data-page="1" name="x_initiator_comment" id="x_initiator_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($dispenser->initiator_comment->getPlaceHolder()) ?>"<?php echo $dispenser->initiator_comment->EditAttributes() ?>><?php echo $dispenser->initiator_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_dispenser_initiator_comment">
<span<?php echo $dispenser->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->initiator_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_initiator_comment" data-page="1" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($dispenser->initiator_comment->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_dispenser_status" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->status->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_status">
<?php
$wrkonchange = trim(" " . @$dispenser->status->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$dispenser->status->EditAttrs["onchange"] = "";
?>
<span id="as_x_status" style="white-space: nowrap; z-index: 8860">
	<input type="text" name="sv_x_status" id="sv_x_status" value="<?php echo $dispenser->status->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser->status->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($dispenser->status->getPlaceHolder()) ?>"<?php echo $dispenser->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="dispenser" data-field="x_status" data-page="1" data-value-separator="<?php echo $dispenser->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($dispenser->status->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdispenseredit.CreateAutoSuggest({"id":"x_status","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_dispenser_status">
<span<?php echo $dispenser->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_status" data-page="1" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($dispenser->status->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dispenser->initiated_by->Visible) { // initiated_by ?>
	<div id="r_initiated_by" class="form-group">
		<label id="elh_dispenser_initiated_by" class="<?php echo $dispenser_edit->LeftColumnClass ?>"><?php echo $dispenser->initiated_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $dispenser_edit->RightColumnClass ?>"><div<?php echo $dispenser->initiated_by->CellAttributes() ?>>
<?php if ($dispenser->CurrentAction <> "F") { ?>
<span id="el_dispenser_initiated_by">
<?php
$wrkonchange = trim(" " . @$dispenser->initiated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$dispenser->initiated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_initiated_by" style="white-space: nowrap; z-index: 8850">
	<input type="text" name="sv_x_initiated_by" id="sv_x_initiated_by" value="<?php echo $dispenser->initiated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($dispenser->initiated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($dispenser->initiated_by->getPlaceHolder()) ?>"<?php echo $dispenser->initiated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="dispenser" data-field="x_initiated_by" data-page="1" data-value-separator="<?php echo $dispenser->initiated_by->DisplayValueSeparatorAttribute() ?>" name="x_initiated_by" id="x_initiated_by" value="<?php echo ew_HtmlEncode($dispenser->initiated_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdispenseredit.CreateAutoSuggest({"id":"x_initiated_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_dispenser_initiated_by">
<span<?php echo $dispenser->initiated_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $dispenser->initiated_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="dispenser" data-field="x_initiated_by" data-page="1" name="x_initiated_by" id="x_initiated_by" value="<?php echo ew_HtmlEncode($dispenser->initiated_by->FormValue) ?>">
<?php } ?>
<?php echo $dispenser->initiated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<input type="hidden" data-table="dispenser" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($dispenser->id->CurrentValue) ?>">
<?php if (!$dispenser_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $dispenser_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($dispenser->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $dispenser_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdispenseredit.Init();
</script>
<?php
$dispenser_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_initiated_by").hide();
$("#r_status").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$dispenser_edit->Page_Terminate();
?>
