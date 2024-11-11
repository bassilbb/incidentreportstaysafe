<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$report_edit = NULL; // Initialize page object first

class creport_edit extends creport {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'report';

	// Page object name
	var $PageObjName = 'report_edit';

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

		// Table object (report)
		if (!isset($GLOBALS["report"]) || get_class($GLOBALS["report"]) == "creport") {
			$GLOBALS["report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["report"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'report');

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
				$this->Page_Terminate(ew_GetUrl("reportlist.php"));
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
		$this->datetime_initiated->SetVisibility();
		$this->incident_id->SetVisibility();
		$this->staffid->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->department->SetVisibility();
		$this->branch->SetVisibility();
		$this->departments->SetVisibility();
		$this->category->SetVisibility();
		$this->sub_category->SetVisibility();
		$this->sub_sub_category->SetVisibility();
		$this->start_date->SetVisibility();
		$this->end_date->SetVisibility();
		$this->duration->SetVisibility();
		$this->amount_paid->SetVisibility();
		$this->no_of_people_involved->SetVisibility();
		$this->incident_type->SetVisibility();
		$this->incident_category->SetVisibility();
		$this->incident_location->SetVisibility();
		$this->incident_sub_location->SetVisibility();
		$this->incident_venue->SetVisibility();
		$this->incident_description->SetVisibility();
		$this->_upload->SetVisibility();
		$this->status->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->report_by->SetVisibility();
		$this->datetime_resolved->SetVisibility();
		$this->assign_task->SetVisibility();
		$this->approval_action->SetVisibility();
		$this->approval_comment->SetVisibility();
		$this->reason->SetVisibility();
		$this->resolved_action->SetVisibility();
		$this->resolved_comment->SetVisibility();
		$this->resolved_by->SetVisibility();
		$this->datetime_approved->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->verified_by->SetVisibility();
		$this->last_updated_date->SetVisibility();
		$this->last_updated_by->SetVisibility();
		$this->selection_sub_category->SetVisibility();
		$this->verified_datetime->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
		$this->job_assessment->SetVisibility();

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
		global $EW_EXPORT, $report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($report);
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
					if ($pageName == "reportview.php")
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
			$this->Page_Terminate("reportlist.php"); // Return to list page
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
					$this->Page_Terminate("reportlist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "reportlist.php")
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
		$this->_upload->Upload->Index = $objForm->Index;
		$this->_upload->Upload->UploadFile();
		$this->_upload->CurrentValue = $this->_upload->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->datetime_initiated->FldIsDetailKey) {
			$this->datetime_initiated->setFormValue($objForm->GetValue("x_datetime_initiated"));
			$this->datetime_initiated->CurrentValue = ew_UnFormatDateTime($this->datetime_initiated->CurrentValue, 7);
		}
		if (!$this->incident_id->FldIsDetailKey) {
			$this->incident_id->setFormValue($objForm->GetValue("x_incident_id"));
		}
		if (!$this->staffid->FldIsDetailKey) {
			$this->staffid->setFormValue($objForm->GetValue("x_staffid"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->branch->FldIsDetailKey) {
			$this->branch->setFormValue($objForm->GetValue("x_branch"));
		}
		if (!$this->departments->FldIsDetailKey) {
			$this->departments->setFormValue($objForm->GetValue("x_departments"));
		}
		if (!$this->category->FldIsDetailKey) {
			$this->category->setFormValue($objForm->GetValue("x_category"));
		}
		if (!$this->sub_category->FldIsDetailKey) {
			$this->sub_category->setFormValue($objForm->GetValue("x_sub_category"));
		}
		if (!$this->sub_sub_category->FldIsDetailKey) {
			$this->sub_sub_category->setFormValue($objForm->GetValue("x_sub_sub_category"));
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
			$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 2);
		}
		if (!$this->end_date->FldIsDetailKey) {
			$this->end_date->setFormValue($objForm->GetValue("x_end_date"));
			$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 2);
		}
		if (!$this->duration->FldIsDetailKey) {
			$this->duration->setFormValue($objForm->GetValue("x_duration"));
		}
		if (!$this->amount_paid->FldIsDetailKey) {
			$this->amount_paid->setFormValue($objForm->GetValue("x_amount_paid"));
		}
		if (!$this->no_of_people_involved->FldIsDetailKey) {
			$this->no_of_people_involved->setFormValue($objForm->GetValue("x_no_of_people_involved"));
		}
		if (!$this->incident_type->FldIsDetailKey) {
			$this->incident_type->setFormValue($objForm->GetValue("x_incident_type"));
		}
		if (!$this->incident_category->FldIsDetailKey) {
			$this->incident_category->setFormValue($objForm->GetValue("x_incident_category"));
		}
		if (!$this->incident_location->FldIsDetailKey) {
			$this->incident_location->setFormValue($objForm->GetValue("x_incident_location"));
		}
		if (!$this->incident_sub_location->FldIsDetailKey) {
			$this->incident_sub_location->setFormValue($objForm->GetValue("x_incident_sub_location"));
		}
		if (!$this->incident_venue->FldIsDetailKey) {
			$this->incident_venue->setFormValue($objForm->GetValue("x_incident_venue"));
		}
		if (!$this->incident_description->FldIsDetailKey) {
			$this->incident_description->setFormValue($objForm->GetValue("x_incident_description"));
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
		if (!$this->report_by->FldIsDetailKey) {
			$this->report_by->setFormValue($objForm->GetValue("x_report_by"));
		}
		if (!$this->datetime_resolved->FldIsDetailKey) {
			$this->datetime_resolved->setFormValue($objForm->GetValue("x_datetime_resolved"));
			$this->datetime_resolved->CurrentValue = ew_UnFormatDateTime($this->datetime_resolved->CurrentValue, 11);
		}
		if (!$this->assign_task->FldIsDetailKey) {
			$this->assign_task->setFormValue($objForm->GetValue("x_assign_task"));
		}
		if (!$this->approval_action->FldIsDetailKey) {
			$this->approval_action->setFormValue($objForm->GetValue("x_approval_action"));
		}
		if (!$this->approval_comment->FldIsDetailKey) {
			$this->approval_comment->setFormValue($objForm->GetValue("x_approval_comment"));
		}
		if (!$this->reason->FldIsDetailKey) {
			$this->reason->setFormValue($objForm->GetValue("x_reason"));
		}
		if (!$this->resolved_action->FldIsDetailKey) {
			$this->resolved_action->setFormValue($objForm->GetValue("x_resolved_action"));
		}
		if (!$this->resolved_comment->FldIsDetailKey) {
			$this->resolved_comment->setFormValue($objForm->GetValue("x_resolved_comment"));
		}
		if (!$this->resolved_by->FldIsDetailKey) {
			$this->resolved_by->setFormValue($objForm->GetValue("x_resolved_by"));
		}
		if (!$this->datetime_approved->FldIsDetailKey) {
			$this->datetime_approved->setFormValue($objForm->GetValue("x_datetime_approved"));
			$this->datetime_approved->CurrentValue = ew_UnFormatDateTime($this->datetime_approved->CurrentValue, 11);
		}
		if (!$this->approved_by->FldIsDetailKey) {
			$this->approved_by->setFormValue($objForm->GetValue("x_approved_by"));
		}
		if (!$this->verified_by->FldIsDetailKey) {
			$this->verified_by->setFormValue($objForm->GetValue("x_verified_by"));
		}
		if (!$this->last_updated_date->FldIsDetailKey) {
			$this->last_updated_date->setFormValue($objForm->GetValue("x_last_updated_date"));
			$this->last_updated_date->CurrentValue = ew_UnFormatDateTime($this->last_updated_date->CurrentValue, 0);
		}
		if (!$this->last_updated_by->FldIsDetailKey) {
			$this->last_updated_by->setFormValue($objForm->GetValue("x_last_updated_by"));
		}
		if (!$this->selection_sub_category->FldIsDetailKey) {
			$this->selection_sub_category->setFormValue($objForm->GetValue("x_selection_sub_category"));
		}
		if (!$this->verified_datetime->FldIsDetailKey) {
			$this->verified_datetime->setFormValue($objForm->GetValue("x_verified_datetime"));
			$this->verified_datetime->CurrentValue = ew_UnFormatDateTime($this->verified_datetime->CurrentValue, 0);
		}
		if (!$this->verified_action->FldIsDetailKey) {
			$this->verified_action->setFormValue($objForm->GetValue("x_verified_action"));
		}
		if (!$this->verified_comment->FldIsDetailKey) {
			$this->verified_comment->setFormValue($objForm->GetValue("x_verified_comment"));
		}
		if (!$this->job_assessment->FldIsDetailKey) {
			$this->job_assessment->setFormValue($objForm->GetValue("x_job_assessment"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->datetime_initiated->CurrentValue = $this->datetime_initiated->FormValue;
		$this->datetime_initiated->CurrentValue = ew_UnFormatDateTime($this->datetime_initiated->CurrentValue, 7);
		$this->incident_id->CurrentValue = $this->incident_id->FormValue;
		$this->staffid->CurrentValue = $this->staffid->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->branch->CurrentValue = $this->branch->FormValue;
		$this->departments->CurrentValue = $this->departments->FormValue;
		$this->category->CurrentValue = $this->category->FormValue;
		$this->sub_category->CurrentValue = $this->sub_category->FormValue;
		$this->sub_sub_category->CurrentValue = $this->sub_sub_category->FormValue;
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 2);
		$this->end_date->CurrentValue = $this->end_date->FormValue;
		$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 2);
		$this->duration->CurrentValue = $this->duration->FormValue;
		$this->amount_paid->CurrentValue = $this->amount_paid->FormValue;
		$this->no_of_people_involved->CurrentValue = $this->no_of_people_involved->FormValue;
		$this->incident_type->CurrentValue = $this->incident_type->FormValue;
		$this->incident_category->CurrentValue = $this->incident_category->FormValue;
		$this->incident_location->CurrentValue = $this->incident_location->FormValue;
		$this->incident_sub_location->CurrentValue = $this->incident_sub_location->FormValue;
		$this->incident_venue->CurrentValue = $this->incident_venue->FormValue;
		$this->incident_description->CurrentValue = $this->incident_description->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
		$this->report_by->CurrentValue = $this->report_by->FormValue;
		$this->datetime_resolved->CurrentValue = $this->datetime_resolved->FormValue;
		$this->datetime_resolved->CurrentValue = ew_UnFormatDateTime($this->datetime_resolved->CurrentValue, 11);
		$this->assign_task->CurrentValue = $this->assign_task->FormValue;
		$this->approval_action->CurrentValue = $this->approval_action->FormValue;
		$this->approval_comment->CurrentValue = $this->approval_comment->FormValue;
		$this->reason->CurrentValue = $this->reason->FormValue;
		$this->resolved_action->CurrentValue = $this->resolved_action->FormValue;
		$this->resolved_comment->CurrentValue = $this->resolved_comment->FormValue;
		$this->resolved_by->CurrentValue = $this->resolved_by->FormValue;
		$this->datetime_approved->CurrentValue = $this->datetime_approved->FormValue;
		$this->datetime_approved->CurrentValue = ew_UnFormatDateTime($this->datetime_approved->CurrentValue, 11);
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->verified_by->CurrentValue = $this->verified_by->FormValue;
		$this->last_updated_date->CurrentValue = $this->last_updated_date->FormValue;
		$this->last_updated_date->CurrentValue = ew_UnFormatDateTime($this->last_updated_date->CurrentValue, 0);
		$this->last_updated_by->CurrentValue = $this->last_updated_by->FormValue;
		$this->selection_sub_category->CurrentValue = $this->selection_sub_category->FormValue;
		$this->verified_datetime->CurrentValue = $this->verified_datetime->FormValue;
		$this->verified_datetime->CurrentValue = ew_UnFormatDateTime($this->verified_datetime->CurrentValue, 0);
		$this->verified_action->CurrentValue = $this->verified_action->FormValue;
		$this->verified_comment->CurrentValue = $this->verified_comment->FormValue;
		$this->job_assessment->CurrentValue = $this->job_assessment->FormValue;
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
		$this->datetime_initiated->setDbValue($row['datetime_initiated']);
		$this->incident_id->setDbValue($row['incident_id']);
		$this->staffid->setDbValue($row['staffid']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->departments->setDbValue($row['departments']);
		$this->category->setDbValue($row['category']);
		$this->sub_category->setDbValue($row['sub_category']);
		$this->sub_sub_category->setDbValue($row['sub_sub_category']);
		$this->start_date->setDbValue($row['start_date']);
		$this->end_date->setDbValue($row['end_date']);
		$this->duration->setDbValue($row['duration']);
		$this->amount_paid->setDbValue($row['amount_paid']);
		$this->no_of_people_involved->setDbValue($row['no_of_people_involved']);
		$this->incident_type->setDbValue($row['incident_type']);
		$this->incident_category->setDbValue($row['incident-category']);
		$this->incident_location->setDbValue($row['incident_location']);
		$this->incident_sub_location->setDbValue($row['incident_sub_location']);
		$this->incident_venue->setDbValue($row['incident_venue']);
		$this->incident_description->setDbValue($row['incident_description']);
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->_upload->setDbValue($this->_upload->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->report_by->setDbValue($row['report_by']);
		$this->datetime_resolved->setDbValue($row['datetime_resolved']);
		$this->assign_task->setDbValue($row['assign_task']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->reason->setDbValue($row['reason']);
		$this->resolved_action->setDbValue($row['resolved_action']);
		$this->resolved_comment->setDbValue($row['resolved_comment']);
		$this->resolved_by->setDbValue($row['resolved_by']);
		$this->datetime_approved->setDbValue($row['datetime_approved']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_by->setDbValue($row['verified_by']);
		$this->last_updated_date->setDbValue($row['last_updated_date']);
		$this->last_updated_by->setDbValue($row['last_updated_by']);
		$this->selection_sub_category->setDbValue($row['selection_sub_category']);
		$this->verified_datetime->setDbValue($row['verified_datetime']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->job_assessment->setDbValue($row['job_assessment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['datetime_initiated'] = NULL;
		$row['incident_id'] = NULL;
		$row['staffid'] = NULL;
		$row['staff_id'] = NULL;
		$row['department'] = NULL;
		$row['branch'] = NULL;
		$row['departments'] = NULL;
		$row['category'] = NULL;
		$row['sub_category'] = NULL;
		$row['sub_sub_category'] = NULL;
		$row['start_date'] = NULL;
		$row['end_date'] = NULL;
		$row['duration'] = NULL;
		$row['amount_paid'] = NULL;
		$row['no_of_people_involved'] = NULL;
		$row['incident_type'] = NULL;
		$row['incident-category'] = NULL;
		$row['incident_location'] = NULL;
		$row['incident_sub_location'] = NULL;
		$row['incident_venue'] = NULL;
		$row['incident_description'] = NULL;
		$row['upload'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['report_by'] = NULL;
		$row['datetime_resolved'] = NULL;
		$row['assign_task'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['reason'] = NULL;
		$row['resolved_action'] = NULL;
		$row['resolved_comment'] = NULL;
		$row['resolved_by'] = NULL;
		$row['datetime_approved'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_by'] = NULL;
		$row['last_updated_date'] = NULL;
		$row['last_updated_by'] = NULL;
		$row['selection_sub_category'] = NULL;
		$row['verified_datetime'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['job_assessment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime_initiated->DbValue = $row['datetime_initiated'];
		$this->incident_id->DbValue = $row['incident_id'];
		$this->staffid->DbValue = $row['staffid'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->departments->DbValue = $row['departments'];
		$this->category->DbValue = $row['category'];
		$this->sub_category->DbValue = $row['sub_category'];
		$this->sub_sub_category->DbValue = $row['sub_sub_category'];
		$this->start_date->DbValue = $row['start_date'];
		$this->end_date->DbValue = $row['end_date'];
		$this->duration->DbValue = $row['duration'];
		$this->amount_paid->DbValue = $row['amount_paid'];
		$this->no_of_people_involved->DbValue = $row['no_of_people_involved'];
		$this->incident_type->DbValue = $row['incident_type'];
		$this->incident_category->DbValue = $row['incident-category'];
		$this->incident_location->DbValue = $row['incident_location'];
		$this->incident_sub_location->DbValue = $row['incident_sub_location'];
		$this->incident_venue->DbValue = $row['incident_venue'];
		$this->incident_description->DbValue = $row['incident_description'];
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->report_by->DbValue = $row['report_by'];
		$this->datetime_resolved->DbValue = $row['datetime_resolved'];
		$this->assign_task->DbValue = $row['assign_task'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->reason->DbValue = $row['reason'];
		$this->resolved_action->DbValue = $row['resolved_action'];
		$this->resolved_comment->DbValue = $row['resolved_comment'];
		$this->resolved_by->DbValue = $row['resolved_by'];
		$this->datetime_approved->DbValue = $row['datetime_approved'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_by->DbValue = $row['verified_by'];
		$this->last_updated_date->DbValue = $row['last_updated_date'];
		$this->last_updated_by->DbValue = $row['last_updated_by'];
		$this->selection_sub_category->DbValue = $row['selection_sub_category'];
		$this->verified_datetime->DbValue = $row['verified_datetime'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->job_assessment->DbValue = $row['job_assessment'];
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

		if ($this->amount_paid->FormValue == $this->amount_paid->CurrentValue && is_numeric(ew_StrToFloat($this->amount_paid->CurrentValue)))
			$this->amount_paid->CurrentValue = ew_StrToFloat($this->amount_paid->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// datetime_initiated
		// incident_id
		// staffid
		// staff_id
		// department
		// branch
		// departments
		// category
		// sub_category
		// sub_sub_category
		// start_date
		// end_date
		// duration
		// amount_paid
		// no_of_people_involved
		// incident_type
		// incident-category
		// incident_location
		// incident_sub_location
		// incident_venue
		// incident_description
		// upload
		// status
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// assign_task
		// approval_action
		// approval_comment
		// reason
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approved_by
		// verified_by
		// last_updated_date
		// last_updated_by
		// selection_sub_category
		// verified_datetime
		// verified_action
		// verified_comment
		// job_assessment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 7);
		$this->datetime_initiated->ViewCustomAttributes = "";

		// incident_id
		$this->incident_id->ViewValue = $this->incident_id->CurrentValue;
		$this->incident_id->ViewCustomAttributes = "";

		// staffid
		$this->staffid->ViewValue = $this->staffid->CurrentValue;
		if (strval($this->staffid->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staffid->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staffid->ViewValue = $this->staffid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staffid->ViewValue = $this->staffid->CurrentValue;
			}
		} else {
			$this->staffid->ViewValue = NULL;
		}
		$this->staffid->ViewCustomAttributes = "";

		// staff_id
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array("dx1" => '`department_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `department_id` ASC";
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

		// branch
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array("dx1" => '`branch_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `branch_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->branch->ViewValue = $this->branch->CurrentValue;
			}
		} else {
			$this->branch->ViewValue = NULL;
		}
		$this->branch->ViewCustomAttributes = "";

		// departments
		if (strval($this->departments->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
		$sWhereWrk = "";
		$this->departments->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departments->ViewValue = $this->departments->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departments->ViewValue = $this->departments->CurrentValue;
			}
		} else {
			$this->departments->ViewValue = NULL;
		}
		$this->departments->ViewCustomAttributes = "";

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category->ViewValue = $this->category->CurrentValue;
			}
		} else {
			$this->category->ViewValue = NULL;
		}
		$this->category->ViewCustomAttributes = "";

		// sub_category
		if (strval($this->sub_category->CurrentValue) <> "") {
			$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
		$sWhereWrk = "";
		$this->sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_category->ViewValue = $this->sub_category->CurrentValue;
			}
		} else {
			$this->sub_category->ViewValue = NULL;
		}
		$this->sub_category->ViewCustomAttributes = "";

		// sub_sub_category
		if (strval($this->sub_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->sub_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_sub_category`";
		$sWhereWrk = "";
		$this->sub_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->sub_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->sub_sub_category->ViewValue .= $this->sub_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->sub_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->sub_sub_category->ViewValue = $this->sub_sub_category->CurrentValue;
			}
		} else {
			$this->sub_sub_category->ViewValue = NULL;
		}
		$this->sub_sub_category->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 2);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 2);
		$this->end_date->ViewCustomAttributes = "";

		// duration
		$this->duration->ViewValue = $this->duration->CurrentValue;
		$this->duration->ViewCustomAttributes = "";

		// amount_paid
		$this->amount_paid->ViewValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->ViewValue = ew_FormatCurrency($this->amount_paid->ViewValue, 2, -2, -2, -2);
		$this->amount_paid->ViewCustomAttributes = "";

		// no_of_people_involved
		$this->no_of_people_involved->ViewValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->ViewCustomAttributes = "";

		// incident_type
		if (strval($this->incident_type->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
		$sWhereWrk = "";
		$this->incident_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_type->ViewValue = $this->incident_type->CurrentValue;
			}
		} else {
			$this->incident_type->ViewValue = NULL;
		}
		$this->incident_type->ViewCustomAttributes = "";

		// incident-category
		if (strval($this->incident_category->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->incident_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_category->ViewValue = $this->incident_category->CurrentValue;
			}
		} else {
			$this->incident_category->ViewValue = NULL;
		}
		$this->incident_category->ViewCustomAttributes = "";

		// incident_location
		if (strval($this->incident_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->incident_location->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_location->ViewValue = $this->incident_location->CurrentValue;
			}
		} else {
			$this->incident_location->ViewValue = NULL;
		}
		$this->incident_location->ViewCustomAttributes = "";

		// incident_sub_location
		if (strval($this->incident_sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->incident_sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->incident_sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->CurrentValue;
			}
		} else {
			$this->incident_sub_location->ViewValue = NULL;
		}
		$this->incident_sub_location->ViewCustomAttributes = "";

		// incident_venue
		if (strval($this->incident_venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->incident_venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_venue->ViewValue = $this->incident_venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_venue->ViewValue = $this->incident_venue->CurrentValue;
			}
		} else {
			$this->incident_venue->ViewValue = NULL;
		}
		$this->incident_venue->ViewCustomAttributes = "";

		// incident_description
		$this->incident_description->ViewValue = $this->incident_description->CurrentValue;
		$this->incident_description->ViewCustomAttributes = "";

		// upload
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->ViewValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->ViewValue = "";
		}
		$this->_upload->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `description` ASC";
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

		// report_by
		$this->report_by->ViewValue = $this->report_by->CurrentValue;
		if (strval($this->report_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->report_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->report_by->ViewValue = $this->report_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->report_by->ViewValue = $this->report_by->CurrentValue;
			}
		} else {
			$this->report_by->ViewValue = NULL;
		}
		$this->report_by->ViewCustomAttributes = "";

		// datetime_resolved
		$this->datetime_resolved->ViewValue = $this->datetime_resolved->CurrentValue;
		$this->datetime_resolved->ViewValue = ew_FormatDateTime($this->datetime_resolved->ViewValue, 11);
		$this->datetime_resolved->ViewCustomAttributes = "";

		// assign_task
		if (strval($this->assign_task->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_task->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_task->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->assign_task->ViewValue = $this->assign_task->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_task->ViewValue = $this->assign_task->CurrentValue;
			}
		} else {
			$this->assign_task->ViewValue = NULL;
		}
		$this->assign_task->ViewCustomAttributes = "";

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

		// reason
		if (strval($this->reason->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reason->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `reason`";
		$sWhereWrk = "";
		$this->reason->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->reason->ViewValue = $this->reason->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reason->ViewValue = $this->reason->CurrentValue;
			}
		} else {
			$this->reason->ViewValue = NULL;
		}
		$this->reason->ViewCustomAttributes = "";

		// resolved_action
		if (strval($this->resolved_action->CurrentValue) <> "") {
			$this->resolved_action->ViewValue = $this->resolved_action->OptionCaption($this->resolved_action->CurrentValue);
		} else {
			$this->resolved_action->ViewValue = NULL;
		}
		$this->resolved_action->ViewCustomAttributes = "";

		// resolved_comment
		$this->resolved_comment->ViewValue = $this->resolved_comment->CurrentValue;
		$this->resolved_comment->ViewCustomAttributes = "";

		// resolved_by
		$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
		if (strval($this->resolved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->resolved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->resolved_by->ViewValue = $this->resolved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
			}
		} else {
			$this->resolved_by->ViewValue = NULL;
		}
		$this->resolved_by->ViewCustomAttributes = "";

		// datetime_approved
		$this->datetime_approved->ViewValue = $this->datetime_approved->CurrentValue;
		$this->datetime_approved->ViewValue = ew_FormatDateTime($this->datetime_approved->ViewValue, 11);
		$this->datetime_approved->ViewCustomAttributes = "";

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

		// verified_by
		$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
		$this->verified_by->ViewCustomAttributes = "";

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 0);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->last_updated_by->ViewValue = $this->last_updated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
			}
		} else {
			$this->last_updated_by->ViewValue = NULL;
		}
		$this->last_updated_by->ViewCustomAttributes = "";

		// selection_sub_category
		$this->selection_sub_category->ViewValue = $this->selection_sub_category->CurrentValue;
		$this->selection_sub_category->ViewCustomAttributes = "";

		// verified_datetime
		$this->verified_datetime->ViewValue = $this->verified_datetime->CurrentValue;
		$this->verified_datetime->ViewValue = ew_FormatDateTime($this->verified_datetime->ViewValue, 0);
		$this->verified_datetime->ViewCustomAttributes = "";

		// verified_action
		$this->verified_action->ViewValue = $this->verified_action->CurrentValue;
		$this->verified_action->ViewCustomAttributes = "";

		// verified_comment
		$this->verified_comment->ViewValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->ViewCustomAttributes = "";

		// job_assessment
		if (strval($this->job_assessment->CurrentValue) <> "") {
			$this->job_assessment->ViewValue = $this->job_assessment->OptionCaption($this->job_assessment->CurrentValue);
		} else {
			$this->job_assessment->ViewValue = NULL;
		}
		$this->job_assessment->ViewCustomAttributes = "";

			// datetime_initiated
			$this->datetime_initiated->LinkCustomAttributes = "";
			$this->datetime_initiated->HrefValue = "";
			$this->datetime_initiated->TooltipValue = "";

			// incident_id
			$this->incident_id->LinkCustomAttributes = "";
			$this->incident_id->HrefValue = "";
			$this->incident_id->TooltipValue = "";

			// staffid
			$this->staffid->LinkCustomAttributes = "";
			$this->staffid->HrefValue = "";
			$this->staffid->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// departments
			$this->departments->LinkCustomAttributes = "";
			$this->departments->HrefValue = "";
			$this->departments->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// sub_category
			$this->sub_category->LinkCustomAttributes = "";
			$this->sub_category->HrefValue = "";
			$this->sub_category->TooltipValue = "";

			// sub_sub_category
			$this->sub_sub_category->LinkCustomAttributes = "";
			$this->sub_sub_category->HrefValue = "";
			$this->sub_sub_category->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// duration
			$this->duration->LinkCustomAttributes = "";
			$this->duration->HrefValue = "";
			$this->duration->TooltipValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";
			$this->amount_paid->TooltipValue = "";

			// no_of_people_involved
			$this->no_of_people_involved->LinkCustomAttributes = "";
			$this->no_of_people_involved->HrefValue = "";
			$this->no_of_people_involved->TooltipValue = "";

			// incident_type
			$this->incident_type->LinkCustomAttributes = "";
			$this->incident_type->HrefValue = "";
			$this->incident_type->TooltipValue = "";

			// incident-category
			$this->incident_category->LinkCustomAttributes = "";
			$this->incident_category->HrefValue = "";
			$this->incident_category->TooltipValue = "";

			// incident_location
			$this->incident_location->LinkCustomAttributes = "";
			$this->incident_location->HrefValue = "";
			$this->incident_location->TooltipValue = "";

			// incident_sub_location
			$this->incident_sub_location->LinkCustomAttributes = "";
			$this->incident_sub_location->HrefValue = "";
			$this->incident_sub_location->TooltipValue = "";

			// incident_venue
			$this->incident_venue->LinkCustomAttributes = "";
			$this->incident_venue->HrefValue = "";
			$this->incident_venue->TooltipValue = "";

			// incident_description
			$this->incident_description->LinkCustomAttributes = "";
			$this->incident_description->HrefValue = "";
			$this->incident_description->TooltipValue = "";

			// upload
			$this->_upload->LinkCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->HrefValue = "%u"; // Add prefix/suffix
				$this->_upload->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->_upload->HrefValue = ew_FullUrl($this->_upload->HrefValue, "href");
			} else {
				$this->_upload->HrefValue = "";
			}
			$this->_upload->HrefValue2 = $this->_upload->UploadPath . $this->_upload->Upload->DbValue;
			$this->_upload->TooltipValue = "";
			if ($this->_upload->UseColorbox) {
				if (ew_Empty($this->_upload->TooltipValue))
					$this->_upload->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->_upload->LinkAttrs["data-rel"] = "report_x__upload";
				ew_AppendClass($this->_upload->LinkAttrs["class"], "ewLightbox");
			}

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

			// report_by
			$this->report_by->LinkCustomAttributes = "";
			$this->report_by->HrefValue = "";
			$this->report_by->TooltipValue = "";

			// datetime_resolved
			$this->datetime_resolved->LinkCustomAttributes = "";
			$this->datetime_resolved->HrefValue = "";
			$this->datetime_resolved->TooltipValue = "";

			// assign_task
			$this->assign_task->LinkCustomAttributes = "";
			$this->assign_task->HrefValue = "";
			$this->assign_task->TooltipValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";
			$this->approval_action->TooltipValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";
			$this->approval_comment->TooltipValue = "";

			// reason
			$this->reason->LinkCustomAttributes = "";
			$this->reason->HrefValue = "";
			$this->reason->TooltipValue = "";

			// resolved_action
			$this->resolved_action->LinkCustomAttributes = "";
			$this->resolved_action->HrefValue = "";
			$this->resolved_action->TooltipValue = "";

			// resolved_comment
			$this->resolved_comment->LinkCustomAttributes = "";
			$this->resolved_comment->HrefValue = "";
			$this->resolved_comment->TooltipValue = "";

			// resolved_by
			$this->resolved_by->LinkCustomAttributes = "";
			$this->resolved_by->HrefValue = "";
			$this->resolved_by->TooltipValue = "";

			// datetime_approved
			$this->datetime_approved->LinkCustomAttributes = "";
			$this->datetime_approved->HrefValue = "";
			$this->datetime_approved->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
			$this->verified_by->TooltipValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";

			// selection_sub_category
			$this->selection_sub_category->LinkCustomAttributes = "";
			$this->selection_sub_category->HrefValue = "";
			$this->selection_sub_category->TooltipValue = "";

			// verified_datetime
			$this->verified_datetime->LinkCustomAttributes = "";
			$this->verified_datetime->HrefValue = "";
			$this->verified_datetime->TooltipValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";
			$this->verified_action->TooltipValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";
			$this->verified_comment->TooltipValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";
			$this->job_assessment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// datetime_initiated
			$this->datetime_initiated->EditAttrs["class"] = "form-control";
			$this->datetime_initiated->EditCustomAttributes = "";
			$this->datetime_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime_initiated->CurrentValue, 7));
			$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());

			// incident_id
			$this->incident_id->EditAttrs["class"] = "form-control";
			$this->incident_id->EditCustomAttributes = "";
			$this->incident_id->EditValue = ew_HtmlEncode($this->incident_id->CurrentValue);
			$this->incident_id->PlaceHolder = ew_RemoveHtml($this->incident_id->FldCaption());

			// staffid
			$this->staffid->EditAttrs["class"] = "form-control";
			$this->staffid->EditCustomAttributes = "";
			$this->staffid->EditValue = ew_HtmlEncode($this->staffid->CurrentValue);
			if (strval($this->staffid->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->staffid->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->staffid->EditValue = $this->staffid->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staffid->EditValue = ew_HtmlEncode($this->staffid->CurrentValue);
				}
			} else {
				$this->staffid->EditValue = NULL;
			}
			$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

			// staff_id
			$this->staff_id->EditCustomAttributes = "";
			if (trim(strval($this->staff_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->staff_id->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->staff_id->ViewValue = $this->staff_id->DisplayValue($arwrk);
			} else {
				$this->staff_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->staff_id->EditValue = $arwrk;

			// department
			$this->department->EditCustomAttributes = "";
			if (trim(strval($this->department->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `depertment`";
			$sWhereWrk = "";
			$this->department->LookupFilters = array("dx1" => '`department_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `department_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->department->ViewValue = $this->department->DisplayValue($arwrk);
			} else {
				$this->department->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->department->EditValue = $arwrk;

			// branch
			$this->branch->EditCustomAttributes = "";
			if (trim(strval($this->branch->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `branch`";
			$sWhereWrk = "";
			$this->branch->LookupFilters = array("dx1" => '`branch_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `branch_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
			} else {
				$this->branch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->branch->EditValue = $arwrk;

			// departments
			$this->departments->EditCustomAttributes = "";
			if (trim(strval($this->departments->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
			$sWhereWrk = "";
			$this->departments->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->departments->ViewValue = $this->departments->DisplayValue($arwrk);
			} else {
				$this->departments->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->departments->EditValue = $arwrk;

			// category
			$this->category->EditAttrs["class"] = "form-control";
			$this->category->EditCustomAttributes = "";
			if (trim(strval($this->category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `category`";
			$sWhereWrk = "";
			$this->category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->category->EditValue = $arwrk;

			// sub_category
			$this->sub_category->EditCustomAttributes = "";
			if (trim(strval($this->sub_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `category_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sub-category`";
			$sWhereWrk = "";
			$this->sub_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
			} else {
				$this->sub_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->sub_category->EditValue = $arwrk;

			// sub_sub_category
			$this->sub_sub_category->EditCustomAttributes = "";
			if (trim(strval($this->sub_sub_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->sub_sub_category->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `sub_category_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sub_sub_category`";
			$sWhereWrk = "";
			$this->sub_sub_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->sub_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->sub_sub_category->ViewValue .= $this->sub_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->sub_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->sub_sub_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->sub_sub_category->EditValue = $arwrk;

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 2));
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// end_date
			$this->end_date->EditAttrs["class"] = "form-control";
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->end_date->CurrentValue, 2));
			$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

			// duration
			$this->duration->EditAttrs["class"] = "form-control";
			$this->duration->EditCustomAttributes = "";
			$this->duration->EditValue = ew_HtmlEncode($this->duration->CurrentValue);
			$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

			// amount_paid
			$this->amount_paid->EditAttrs["class"] = "form-control";
			$this->amount_paid->EditCustomAttributes = "";
			$this->amount_paid->EditValue = ew_HtmlEncode($this->amount_paid->CurrentValue);
			$this->amount_paid->PlaceHolder = ew_RemoveHtml($this->amount_paid->FldCaption());
			if (strval($this->amount_paid->EditValue) <> "" && is_numeric($this->amount_paid->EditValue)) $this->amount_paid->EditValue = ew_FormatNumber($this->amount_paid->EditValue, -2, -2, -2, -2);

			// no_of_people_involved
			$this->no_of_people_involved->EditAttrs["class"] = "form-control";
			$this->no_of_people_involved->EditCustomAttributes = "";
			$this->no_of_people_involved->EditValue = ew_HtmlEncode($this->no_of_people_involved->CurrentValue);
			$this->no_of_people_involved->PlaceHolder = ew_RemoveHtml($this->no_of_people_involved->FldCaption());

			// incident_type
			$this->incident_type->EditCustomAttributes = "";
			if (trim(strval($this->incident_type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `type_of_incident`";
			$sWhereWrk = "";
			$this->incident_type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
			} else {
				$this->incident_type->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_type->EditValue = $arwrk;

			// incident-category
			$this->incident_category->EditCustomAttributes = "";
			if (trim(strval($this->incident_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident-category`";
			$sWhereWrk = "";
			$this->incident_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
			} else {
				$this->incident_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_category->EditValue = $arwrk;

			// incident_location
			$this->incident_location->EditCustomAttributes = "";
			if (trim(strval($this->incident_location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_location`";
			$sWhereWrk = "";
			$this->incident_location->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
			} else {
				$this->incident_location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_location->EditValue = $arwrk;

			// incident_sub_location
			$this->incident_sub_location->EditAttrs["class"] = "form-control";
			$this->incident_sub_location->EditCustomAttributes = "";
			if (trim(strval($this->incident_sub_location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->incident_sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_sub_location`";
			$sWhereWrk = "";
			$this->incident_sub_location->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_sub_location->EditValue = $arwrk;

			// incident_venue
			$this->incident_venue->EditAttrs["class"] = "form-control";
			$this->incident_venue->EditCustomAttributes = "";
			if (trim(strval($this->incident_venue->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_venue->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_sub` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_venue`";
			$sWhereWrk = "";
			$this->incident_venue->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_venue->EditValue = $arwrk;

			// incident_description
			$this->incident_description->EditAttrs["class"] = "form-control";
			$this->incident_description->EditCustomAttributes = "";
			$this->incident_description->EditValue = ew_HtmlEncode($this->incident_description->CurrentValue);
			$this->incident_description->PlaceHolder = ew_RemoveHtml($this->incident_description->FldCaption());

			// upload
			$this->_upload->EditAttrs["class"] = "form-control";
			$this->_upload->EditCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->ImageAlt = $this->_upload->FldAlt();
				$this->_upload->EditValue = $this->_upload->Upload->DbValue;
			} else {
				$this->_upload->EditValue = "";
			}
			if (!ew_Empty($this->_upload->CurrentValue))
					$this->_upload->Upload->FileName = $this->_upload->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->_upload);

			// status
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->status->ViewValue = $this->status->DisplayValue($arwrk);
			} else {
				$this->status->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// report_by
			$this->report_by->EditAttrs["class"] = "form-control";
			$this->report_by->EditCustomAttributes = "";
			$this->report_by->EditValue = ew_HtmlEncode($this->report_by->CurrentValue);
			if (strval($this->report_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->report_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->report_by->EditValue = $this->report_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->report_by->EditValue = ew_HtmlEncode($this->report_by->CurrentValue);
				}
			} else {
				$this->report_by->EditValue = NULL;
			}
			$this->report_by->PlaceHolder = ew_RemoveHtml($this->report_by->FldCaption());

			// datetime_resolved
			$this->datetime_resolved->EditAttrs["class"] = "form-control";
			$this->datetime_resolved->EditCustomAttributes = "";
			$this->datetime_resolved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime_resolved->CurrentValue, 11));
			$this->datetime_resolved->PlaceHolder = ew_RemoveHtml($this->datetime_resolved->FldCaption());

			// assign_task
			$this->assign_task->EditCustomAttributes = "";
			if (trim(strval($this->assign_task->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_task->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->assign_task->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->assign_task->ViewValue = $this->assign_task->DisplayValue($arwrk);
			} else {
				$this->assign_task->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->assign_task->EditValue = $arwrk;

			// approval_action
			$this->approval_action->EditCustomAttributes = "";
			$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

			// approval_comment
			$this->approval_comment->EditAttrs["class"] = "form-control";
			$this->approval_comment->EditCustomAttributes = "";
			$this->approval_comment->EditValue = ew_HtmlEncode($this->approval_comment->CurrentValue);
			$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

			// reason
			$this->reason->EditAttrs["class"] = "form-control";
			$this->reason->EditCustomAttributes = "";
			if (trim(strval($this->reason->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->reason->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `reason`";
			$sWhereWrk = "";
			$this->reason->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->reason->EditValue = $arwrk;

			// resolved_action
			$this->resolved_action->EditCustomAttributes = "";
			$this->resolved_action->EditValue = $this->resolved_action->Options(FALSE);

			// resolved_comment
			$this->resolved_comment->EditAttrs["class"] = "form-control";
			$this->resolved_comment->EditCustomAttributes = "";
			$this->resolved_comment->EditValue = ew_HtmlEncode($this->resolved_comment->CurrentValue);
			$this->resolved_comment->PlaceHolder = ew_RemoveHtml($this->resolved_comment->FldCaption());

			// resolved_by
			$this->resolved_by->EditAttrs["class"] = "form-control";
			$this->resolved_by->EditCustomAttributes = "";
			$this->resolved_by->EditValue = ew_HtmlEncode($this->resolved_by->CurrentValue);
			if (strval($this->resolved_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->resolved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->resolved_by->EditValue = $this->resolved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->resolved_by->EditValue = ew_HtmlEncode($this->resolved_by->CurrentValue);
				}
			} else {
				$this->resolved_by->EditValue = NULL;
			}
			$this->resolved_by->PlaceHolder = ew_RemoveHtml($this->resolved_by->FldCaption());

			// datetime_approved
			$this->datetime_approved->EditAttrs["class"] = "form-control";
			$this->datetime_approved->EditCustomAttributes = "";
			$this->datetime_approved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime_approved->CurrentValue, 11));
			$this->datetime_approved->PlaceHolder = ew_RemoveHtml($this->datetime_approved->FldCaption());

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

			// verified_by
			$this->verified_by->EditAttrs["class"] = "form-control";
			$this->verified_by->EditCustomAttributes = "";
			$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->CurrentValue);
			$this->verified_by->PlaceHolder = ew_RemoveHtml($this->verified_by->FldCaption());

			// last_updated_date
			$this->last_updated_date->EditAttrs["class"] = "form-control";
			$this->last_updated_date->EditCustomAttributes = "";
			$this->last_updated_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_updated_date->CurrentValue, 8));
			$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

			// last_updated_by
			$this->last_updated_by->EditAttrs["class"] = "form-control";
			$this->last_updated_by->EditCustomAttributes = "";
			$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->CurrentValue);
			if (strval($this->last_updated_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->last_updated_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->last_updated_by->EditValue = $this->last_updated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->CurrentValue);
				}
			} else {
				$this->last_updated_by->EditValue = NULL;
			}
			$this->last_updated_by->PlaceHolder = ew_RemoveHtml($this->last_updated_by->FldCaption());

			// selection_sub_category
			$this->selection_sub_category->EditAttrs["class"] = "form-control";
			$this->selection_sub_category->EditCustomAttributes = "";
			$this->selection_sub_category->EditValue = ew_HtmlEncode($this->selection_sub_category->CurrentValue);
			$this->selection_sub_category->PlaceHolder = ew_RemoveHtml($this->selection_sub_category->FldCaption());

			// verified_datetime
			$this->verified_datetime->EditAttrs["class"] = "form-control";
			$this->verified_datetime->EditCustomAttributes = "";
			$this->verified_datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->verified_datetime->CurrentValue, 8));
			$this->verified_datetime->PlaceHolder = ew_RemoveHtml($this->verified_datetime->FldCaption());

			// verified_action
			$this->verified_action->EditAttrs["class"] = "form-control";
			$this->verified_action->EditCustomAttributes = "";
			$this->verified_action->EditValue = ew_HtmlEncode($this->verified_action->CurrentValue);
			$this->verified_action->PlaceHolder = ew_RemoveHtml($this->verified_action->FldCaption());

			// verified_comment
			$this->verified_comment->EditAttrs["class"] = "form-control";
			$this->verified_comment->EditCustomAttributes = "";
			$this->verified_comment->EditValue = ew_HtmlEncode($this->verified_comment->CurrentValue);
			$this->verified_comment->PlaceHolder = ew_RemoveHtml($this->verified_comment->FldCaption());

			// job_assessment
			$this->job_assessment->EditCustomAttributes = "";
			$this->job_assessment->EditValue = $this->job_assessment->Options(FALSE);

			// Edit refer script
			// datetime_initiated

			$this->datetime_initiated->LinkCustomAttributes = "";
			$this->datetime_initiated->HrefValue = "";

			// incident_id
			$this->incident_id->LinkCustomAttributes = "";
			$this->incident_id->HrefValue = "";

			// staffid
			$this->staffid->LinkCustomAttributes = "";
			$this->staffid->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";

			// departments
			$this->departments->LinkCustomAttributes = "";
			$this->departments->HrefValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";

			// sub_category
			$this->sub_category->LinkCustomAttributes = "";
			$this->sub_category->HrefValue = "";

			// sub_sub_category
			$this->sub_sub_category->LinkCustomAttributes = "";
			$this->sub_sub_category->HrefValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";

			// duration
			$this->duration->LinkCustomAttributes = "";
			$this->duration->HrefValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";

			// no_of_people_involved
			$this->no_of_people_involved->LinkCustomAttributes = "";
			$this->no_of_people_involved->HrefValue = "";

			// incident_type
			$this->incident_type->LinkCustomAttributes = "";
			$this->incident_type->HrefValue = "";

			// incident-category
			$this->incident_category->LinkCustomAttributes = "";
			$this->incident_category->HrefValue = "";

			// incident_location
			$this->incident_location->LinkCustomAttributes = "";
			$this->incident_location->HrefValue = "";

			// incident_sub_location
			$this->incident_sub_location->LinkCustomAttributes = "";
			$this->incident_sub_location->HrefValue = "";

			// incident_venue
			$this->incident_venue->LinkCustomAttributes = "";
			$this->incident_venue->HrefValue = "";

			// incident_description
			$this->incident_description->LinkCustomAttributes = "";
			$this->incident_description->HrefValue = "";

			// upload
			$this->_upload->LinkCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->HrefValue = "%u"; // Add prefix/suffix
				$this->_upload->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->_upload->HrefValue = ew_FullUrl($this->_upload->HrefValue, "href");
			} else {
				$this->_upload->HrefValue = "";
			}
			$this->_upload->HrefValue2 = $this->_upload->UploadPath . $this->_upload->Upload->DbValue;

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

			// report_by
			$this->report_by->LinkCustomAttributes = "";
			$this->report_by->HrefValue = "";

			// datetime_resolved
			$this->datetime_resolved->LinkCustomAttributes = "";
			$this->datetime_resolved->HrefValue = "";

			// assign_task
			$this->assign_task->LinkCustomAttributes = "";
			$this->assign_task->HrefValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";

			// reason
			$this->reason->LinkCustomAttributes = "";
			$this->reason->HrefValue = "";

			// resolved_action
			$this->resolved_action->LinkCustomAttributes = "";
			$this->resolved_action->HrefValue = "";

			// resolved_comment
			$this->resolved_comment->LinkCustomAttributes = "";
			$this->resolved_comment->HrefValue = "";

			// resolved_by
			$this->resolved_by->LinkCustomAttributes = "";
			$this->resolved_by->HrefValue = "";

			// datetime_approved
			$this->datetime_approved->LinkCustomAttributes = "";
			$this->datetime_approved->HrefValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";

			// selection_sub_category
			$this->selection_sub_category->LinkCustomAttributes = "";
			$this->selection_sub_category->HrefValue = "";

			// verified_datetime
			$this->verified_datetime->LinkCustomAttributes = "";
			$this->verified_datetime->HrefValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";
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
		if (!$this->datetime_initiated->FldIsDetailKey && !is_null($this->datetime_initiated->FormValue) && $this->datetime_initiated->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datetime_initiated->FldCaption(), $this->datetime_initiated->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datetime_initiated->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime_initiated->FldErrMsg());
		}
		if (!$this->incident_id->FldIsDetailKey && !is_null($this->incident_id->FormValue) && $this->incident_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_id->FldCaption(), $this->incident_id->ReqErrMsg));
		}
		if (!$this->staffid->FldIsDetailKey && !is_null($this->staffid->FormValue) && $this->staffid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffid->FldCaption(), $this->staffid->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!$this->department->FldIsDetailKey && !is_null($this->department->FormValue) && $this->department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->department->FldCaption(), $this->department->ReqErrMsg));
		}
		if (!$this->branch->FldIsDetailKey && !is_null($this->branch->FormValue) && $this->branch->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->branch->FldCaption(), $this->branch->ReqErrMsg));
		}
		if (!$this->category->FldIsDetailKey && !is_null($this->category->FormValue) && $this->category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->category->FldCaption(), $this->category->ReqErrMsg));
		}
		if (!$this->sub_category->FldIsDetailKey && !is_null($this->sub_category->FormValue) && $this->sub_category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sub_category->FldCaption(), $this->sub_category->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->start_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->end_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->end_date->FldErrMsg());
		}
		if (!$this->duration->FldIsDetailKey && !is_null($this->duration->FormValue) && $this->duration->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->duration->FldCaption(), $this->duration->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->duration->FormValue)) {
			ew_AddMessage($gsFormError, $this->duration->FldErrMsg());
		}
		if (!ew_CheckNumber($this->amount_paid->FormValue)) {
			ew_AddMessage($gsFormError, $this->amount_paid->FldErrMsg());
		}
		if (!$this->incident_category->FldIsDetailKey && !is_null($this->incident_category->FormValue) && $this->incident_category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_category->FldCaption(), $this->incident_category->ReqErrMsg));
		}
		if (!$this->incident_description->FldIsDetailKey && !is_null($this->incident_description->FormValue) && $this->incident_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incident_description->FldCaption(), $this->incident_description->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if ($this->initiator_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiator_action->FldCaption(), $this->initiator_action->ReqErrMsg));
		}
		if (!$this->initiator_comment->FldIsDetailKey && !is_null($this->initiator_comment->FormValue) && $this->initiator_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->initiator_comment->FldCaption(), $this->initiator_comment->ReqErrMsg));
		}
		if (!$this->report_by->FldIsDetailKey && !is_null($this->report_by->FormValue) && $this->report_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->report_by->FldCaption(), $this->report_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->report_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->report_by->FldErrMsg());
		}
		if (!$this->datetime_resolved->FldIsDetailKey && !is_null($this->datetime_resolved->FormValue) && $this->datetime_resolved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datetime_resolved->FldCaption(), $this->datetime_resolved->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datetime_resolved->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime_resolved->FldErrMsg());
		}
		if ($this->approval_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approval_action->FldCaption(), $this->approval_action->ReqErrMsg));
		}
		if (!$this->approval_comment->FldIsDetailKey && !is_null($this->approval_comment->FormValue) && $this->approval_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approval_comment->FldCaption(), $this->approval_comment->ReqErrMsg));
		}
		if (!$this->resolved_comment->FldIsDetailKey && !is_null($this->resolved_comment->FormValue) && $this->resolved_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->resolved_comment->FldCaption(), $this->resolved_comment->ReqErrMsg));
		}
		if (!$this->resolved_by->FldIsDetailKey && !is_null($this->resolved_by->FormValue) && $this->resolved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->resolved_by->FldCaption(), $this->resolved_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->resolved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->resolved_by->FldErrMsg());
		}
		if (!$this->datetime_approved->FldIsDetailKey && !is_null($this->datetime_approved->FormValue) && $this->datetime_approved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->datetime_approved->FldCaption(), $this->datetime_approved->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->datetime_approved->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime_approved->FldErrMsg());
		}
		if (!$this->approved_by->FldIsDetailKey && !is_null($this->approved_by->FormValue) && $this->approved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approved_by->FldCaption(), $this->approved_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->approved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckInteger($this->verified_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_by->FldErrMsg());
		}
		if (!$this->last_updated_date->FldIsDetailKey && !is_null($this->last_updated_date->FormValue) && $this->last_updated_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->last_updated_date->FldCaption(), $this->last_updated_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->last_updated_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_updated_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->last_updated_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_updated_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->verified_datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_datetime->FldErrMsg());
		}
		if (!ew_CheckInteger($this->verified_action->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_action->FldErrMsg());
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
			$this->_upload->OldUploadPath = "picture/";
			$this->_upload->UploadPath = $this->_upload->OldUploadPath;
			$rsnew = array();

			// datetime_initiated
			$this->datetime_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime_initiated->CurrentValue, 7), NULL, $this->datetime_initiated->ReadOnly);

			// incident_id
			$this->incident_id->SetDbValueDef($rsnew, $this->incident_id->CurrentValue, NULL, $this->incident_id->ReadOnly);

			// staffid
			$this->staffid->SetDbValueDef($rsnew, $this->staffid->CurrentValue, NULL, $this->staffid->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, $this->staff_id->ReadOnly);

			// department
			$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, $this->department->ReadOnly);

			// branch
			$this->branch->SetDbValueDef($rsnew, $this->branch->CurrentValue, NULL, $this->branch->ReadOnly);

			// departments
			$this->departments->SetDbValueDef($rsnew, $this->departments->CurrentValue, NULL, $this->departments->ReadOnly);

			// category
			$this->category->SetDbValueDef($rsnew, $this->category->CurrentValue, NULL, $this->category->ReadOnly);

			// sub_category
			$this->sub_category->SetDbValueDef($rsnew, $this->sub_category->CurrentValue, NULL, $this->sub_category->ReadOnly);

			// sub_sub_category
			$this->sub_sub_category->SetDbValueDef($rsnew, $this->sub_sub_category->CurrentValue, NULL, $this->sub_sub_category->ReadOnly);

			// start_date
			$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 2), NULL, $this->start_date->ReadOnly);

			// end_date
			$this->end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->end_date->CurrentValue, 2), NULL, $this->end_date->ReadOnly);

			// duration
			$this->duration->SetDbValueDef($rsnew, $this->duration->CurrentValue, 0, $this->duration->ReadOnly);

			// amount_paid
			$this->amount_paid->SetDbValueDef($rsnew, $this->amount_paid->CurrentValue, NULL, $this->amount_paid->ReadOnly);

			// no_of_people_involved
			$this->no_of_people_involved->SetDbValueDef($rsnew, $this->no_of_people_involved->CurrentValue, NULL, $this->no_of_people_involved->ReadOnly);

			// incident_type
			$this->incident_type->SetDbValueDef($rsnew, $this->incident_type->CurrentValue, NULL, $this->incident_type->ReadOnly);

			// incident-category
			$this->incident_category->SetDbValueDef($rsnew, $this->incident_category->CurrentValue, NULL, $this->incident_category->ReadOnly);

			// incident_location
			$this->incident_location->SetDbValueDef($rsnew, $this->incident_location->CurrentValue, NULL, $this->incident_location->ReadOnly);

			// incident_sub_location
			$this->incident_sub_location->SetDbValueDef($rsnew, $this->incident_sub_location->CurrentValue, NULL, $this->incident_sub_location->ReadOnly);

			// incident_venue
			$this->incident_venue->SetDbValueDef($rsnew, $this->incident_venue->CurrentValue, NULL, $this->incident_venue->ReadOnly);

			// incident_description
			$this->incident_description->SetDbValueDef($rsnew, $this->incident_description->CurrentValue, NULL, $this->incident_description->ReadOnly);

			// upload
			if ($this->_upload->Visible && !$this->_upload->ReadOnly && !$this->_upload->Upload->KeepFile) {
				$this->_upload->Upload->DbValue = $rsold['upload']; // Get original value
				if ($this->_upload->Upload->FileName == "") {
					$rsnew['upload'] = NULL;
				} else {
					$rsnew['upload'] = $this->_upload->Upload->FileName;
				}
				$this->_upload->ImageWidth = 1000; // Resize width
				$this->_upload->ImageHeight = 0; // Resize height
			}

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// initiator_action
			$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, $this->initiator_action->ReadOnly);

			// initiator_comment
			$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, $this->initiator_comment->ReadOnly);

			// report_by
			$this->report_by->SetDbValueDef($rsnew, $this->report_by->CurrentValue, NULL, $this->report_by->ReadOnly);

			// datetime_resolved
			$this->datetime_resolved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime_resolved->CurrentValue, 11), NULL, $this->datetime_resolved->ReadOnly);

			// assign_task
			$this->assign_task->SetDbValueDef($rsnew, $this->assign_task->CurrentValue, NULL, $this->assign_task->ReadOnly);

			// approval_action
			$this->approval_action->SetDbValueDef($rsnew, $this->approval_action->CurrentValue, NULL, $this->approval_action->ReadOnly);

			// approval_comment
			$this->approval_comment->SetDbValueDef($rsnew, $this->approval_comment->CurrentValue, NULL, $this->approval_comment->ReadOnly);

			// reason
			$this->reason->SetDbValueDef($rsnew, $this->reason->CurrentValue, NULL, $this->reason->ReadOnly);

			// resolved_action
			$this->resolved_action->SetDbValueDef($rsnew, $this->resolved_action->CurrentValue, NULL, $this->resolved_action->ReadOnly);

			// resolved_comment
			$this->resolved_comment->SetDbValueDef($rsnew, $this->resolved_comment->CurrentValue, NULL, $this->resolved_comment->ReadOnly);

			// resolved_by
			$this->resolved_by->SetDbValueDef($rsnew, $this->resolved_by->CurrentValue, NULL, $this->resolved_by->ReadOnly);

			// datetime_approved
			$this->datetime_approved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime_approved->CurrentValue, 11), ew_CurrentDate(), $this->datetime_approved->ReadOnly);

			// approved_by
			$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, $this->approved_by->ReadOnly);

			// verified_by
			$this->verified_by->SetDbValueDef($rsnew, $this->verified_by->CurrentValue, NULL, $this->verified_by->ReadOnly);

			// last_updated_date
			$this->last_updated_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_updated_date->CurrentValue, 0), ew_CurrentDate(), $this->last_updated_date->ReadOnly);

			// last_updated_by
			$this->last_updated_by->SetDbValueDef($rsnew, $this->last_updated_by->CurrentValue, NULL, $this->last_updated_by->ReadOnly);

			// selection_sub_category
			$this->selection_sub_category->SetDbValueDef($rsnew, $this->selection_sub_category->CurrentValue, NULL, $this->selection_sub_category->ReadOnly);

			// verified_datetime
			$this->verified_datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->verified_datetime->CurrentValue, 0), NULL, $this->verified_datetime->ReadOnly);

			// verified_action
			$this->verified_action->SetDbValueDef($rsnew, $this->verified_action->CurrentValue, NULL, $this->verified_action->ReadOnly);

			// verified_comment
			$this->verified_comment->SetDbValueDef($rsnew, $this->verified_comment->CurrentValue, NULL, $this->verified_comment->ReadOnly);

			// job_assessment
			$this->job_assessment->SetDbValueDef($rsnew, $this->job_assessment->CurrentValue, NULL, $this->job_assessment->ReadOnly);
			if ($this->_upload->Visible && !$this->_upload->Upload->KeepFile) {
				$this->_upload->UploadPath = "picture/";
				$OldFiles = ew_Empty($this->_upload->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->_upload->Upload->DbValue));
				if (!ew_Empty($this->_upload->Upload->FileName)) {
					$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->_upload->Upload->FileName));
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->_upload->Upload->Index < 0) ? $this->_upload->FldVar : substr($this->_upload->FldVar, 0, 1) . $this->_upload->Upload->Index . substr($this->_upload->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file)) {
								$OldFileFound = FALSE;
								$OldFileCount = count($OldFiles);
								for ($j = 0; $j < $OldFileCount; $j++) {
									$file1 = $OldFiles[$j];
									if ($file1 == $file) { // Old file found, no need to delete anymore
										unset($OldFiles[$j]);
										$OldFileFound = TRUE;
										break;
									}
								}
								if ($OldFileFound) // No need to check if file exists further
									continue;
								$file1 = ew_UploadFileNameEx($this->_upload->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file1) || file_exists($this->_upload->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->_upload->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file, ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->_upload->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->_upload->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->_upload->SetDbValueDef($rsnew, $this->_upload->Upload->FileName, NULL, $this->_upload->ReadOnly);
				}
			}

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
					if ($this->_upload->Visible && !$this->_upload->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->_upload->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->_upload->Upload->DbValue));
						if (!ew_Empty($this->_upload->Upload->FileName)) {
							$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->_upload->Upload->FileName);
							$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['upload']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->_upload->Upload->Index < 0) ? $this->_upload->FldVar : substr($this->_upload->FldVar, 0, 1) . $this->_upload->Upload->Index . substr($this->_upload->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->_upload->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->_upload->Upload->ResizeAndSaveToFile($this->_upload->ImageWidth, $this->_upload->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
											$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
											return FALSE;
										}
									}
								}
							}
						} else {
							$NewFiles = array();
						}
						$OldFileCount = count($OldFiles);
						for ($i = 0; $i < $OldFileCount; $i++) {
							if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
								@unlink($this->_upload->OldPhysicalUploadPath() . $OldFiles[$i]);
						}
					}
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

		// upload
		ew_CleanUploadTempPath($this->_upload, $this->_upload->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("reportlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staffid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id` AS `LinkFld`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`department_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`department_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `department_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_branch":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id` AS `LinkFld`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`branch_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `branch_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_departments":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "n" => 5);
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `category_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`category_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sub_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sub-category_id` AS `LinkFld`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sub-category_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`category_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sub_sub_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_sub_category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`sub_category_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_sub_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_sub` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_sub` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_venue":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`code_sub` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_report_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_assign_task":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_reason":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `reason`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->reason, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_resolved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_last_updated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_staffid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld` FROM `users`";
			$sWhereWrk = "`staffno` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_report_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->report_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_resolved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->resolved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
		case "x_last_updated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->last_updated_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->last_updated_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($report_edit)) $report_edit = new creport_edit();

// Page init
$report_edit->Page_Init();

// Page main
$report_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$report_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = freportedit = new ew_Form("freportedit", "edit");

// Validate form
freportedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_datetime_initiated");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->datetime_initiated->FldCaption(), $report->datetime_initiated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datetime_initiated");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->datetime_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_incident_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->incident_id->FldCaption(), $report->incident_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->staffid->FldCaption(), $report->staffid->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->staff_id->FldCaption(), $report->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->department->FldCaption(), $report->department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_branch");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->branch->FldCaption(), $report->branch->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->category->FldCaption(), $report->category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sub_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->sub_category->FldCaption(), $report->sub_category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_start_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->start_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_end_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->end_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_duration");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->duration->FldCaption(), $report->duration->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duration");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->duration->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_amount_paid");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->amount_paid->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_incident_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->incident_category->FldCaption(), $report->incident_category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_incident_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->incident_description->FldCaption(), $report->incident_description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->status->FldCaption(), $report->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->initiator_action->FldCaption(), $report->initiator_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_initiator_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->initiator_comment->FldCaption(), $report->initiator_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_report_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->report_by->FldCaption(), $report->report_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_report_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->report_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_datetime_resolved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->datetime_resolved->FldCaption(), $report->datetime_resolved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datetime_resolved");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->datetime_resolved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approval_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->approval_action->FldCaption(), $report->approval_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approval_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->approval_comment->FldCaption(), $report->approval_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resolved_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->resolved_comment->FldCaption(), $report->resolved_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resolved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->resolved_by->FldCaption(), $report->resolved_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_resolved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->resolved_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_datetime_approved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->datetime_approved->FldCaption(), $report->datetime_approved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_datetime_approved");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->datetime_approved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->approved_by->FldCaption(), $report->approved_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->approved_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->verified_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_updated_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $report->last_updated_date->FldCaption(), $report->last_updated_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_last_updated_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->last_updated_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_updated_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->last_updated_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_datetime");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->verified_datetime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_action");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($report->verified_action->FldErrMsg()) ?>");

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
freportedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freportedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
freportedit.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_staffid"].Data = "<?php echo $report_edit->staffid->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_edit->staffid->LookupFilterQuery(TRUE, "edit"))) ?>;
freportedit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_staff_id"].Data = "<?php echo $report_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
freportedit.Lists["x_department"].Data = "<?php echo $report_edit->department->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
freportedit.Lists["x_branch"].Data = "<?php echo $report_edit->branch->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freportedit.Lists["x_departments"].Data = "<?php echo $report_edit->departments->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_departments"],"ChildFields":["x_sub_category"],"FilterFields":["x_code_id"],"Options":[],"Template":"","LinkTable":"category"};
freportedit.Lists["x_category"].Data = "<?php echo $report_edit->category->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":["x_category"],"ChildFields":["x_sub_sub_category[]"],"FilterFields":["x_category_id"],"Options":[],"Template":"","LinkTable":"sub_category"};
freportedit.Lists["x_sub_category"].Data = "<?php echo $report_edit->sub_category->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_sub_sub_category[]"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_sub_category"],"ChildFields":[],"FilterFields":["x_sub_category_id"],"Options":[],"Template":"","LinkTable":"sub_sub_category"};
freportedit.Lists["x_sub_sub_category[]"].Data = "<?php echo $report_edit->sub_sub_category->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_incident_type"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"type_of_incident"};
freportedit.Lists["x_incident_type"].Data = "<?php echo $report_edit->incident_type->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_incident_category"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
freportedit.Lists["x_incident_category"].Data = "<?php echo $report_edit->incident_category->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_incident_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freportedit.Lists["x_incident_location"].Data = "<?php echo $report_edit->incident_location->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_incident_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_incident_location"],"ChildFields":["x_incident_venue"],"FilterFields":["x_code_id"],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
freportedit.Lists["x_incident_sub_location"].Data = "<?php echo $report_edit->incident_sub_location->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_incident_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_incident_sub_location"],"ChildFields":[],"FilterFields":["x_code_sub"],"Options":[],"Template":"","LinkTable":"incident_venue"};
freportedit.Lists["x_incident_venue"].Data = "<?php echo $report_edit->incident_venue->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freportedit.Lists["x_status"].Data = "<?php echo $report_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportedit.Lists["x_initiator_action"].Options = <?php echo json_encode($report_edit->initiator_action->Options()) ?>;
freportedit.Lists["x_report_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_report_by"].Data = "<?php echo $report_edit->report_by->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.AutoSuggests["x_report_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_edit->report_by->LookupFilterQuery(TRUE, "edit"))) ?>;
freportedit.Lists["x_assign_task"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_assign_task"].Data = "<?php echo $report_edit->assign_task->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportedit.Lists["x_approval_action"].Options = <?php echo json_encode($report_edit->approval_action->Options()) ?>;
freportedit.Lists["x_reason"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"reason"};
freportedit.Lists["x_reason"].Data = "<?php echo $report_edit->reason->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.Lists["x_resolved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportedit.Lists["x_resolved_action"].Options = <?php echo json_encode($report_edit->resolved_action->Options()) ?>;
freportedit.Lists["x_resolved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_resolved_by"].Data = "<?php echo $report_edit->resolved_by->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.AutoSuggests["x_resolved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_edit->resolved_by->LookupFilterQuery(TRUE, "edit"))) ?>;
freportedit.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_approved_by"].Data = "<?php echo $report_edit->approved_by->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_edit->approved_by->LookupFilterQuery(TRUE, "edit"))) ?>;
freportedit.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportedit.Lists["x_last_updated_by"].Data = "<?php echo $report_edit->last_updated_by->LookupFilterQuery(FALSE, "edit") ?>";
freportedit.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_edit->last_updated_by->LookupFilterQuery(TRUE, "edit"))) ?>;
freportedit.Lists["x_job_assessment"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportedit.Lists["x_job_assessment"].Options = <?php echo json_encode($report_edit->job_assessment->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $report_edit->ShowPageHeader(); ?>
<?php
$report_edit->ShowMessage();
?>
<?php if (!$report_edit->IsModal) { ?>
<?php if ($report->CurrentAction <> "F") { // Confirm page ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($report_edit->Pager)) $report_edit->Pager = new cPrevNextPager($report_edit->StartRec, $report_edit->DisplayRecs, $report_edit->TotalRecs, $report_edit->AutoHidePager) ?>
<?php if ($report_edit->Pager->RecordCount > 0 && $report_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($report_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $report_edit->PageUrl() ?>start=<?php echo $report_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($report_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $report_edit->PageUrl() ?>start=<?php echo $report_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $report_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($report_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $report_edit->PageUrl() ?>start=<?php echo $report_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($report_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $report_edit->PageUrl() ?>start=<?php echo $report_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $report_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="freportedit" id="freportedit" class="<?php echo $report_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($report_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $report_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="report">
<?php if ($report->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_edit" id="a_edit" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($report_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($report->datetime_initiated->Visible) { // datetime_initiated ?>
	<div id="r_datetime_initiated" class="form-group">
		<label id="elh_report_datetime_initiated" for="x_datetime_initiated" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->datetime_initiated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->datetime_initiated->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_datetime_initiated">
<input type="text" data-table="report" data-field="x_datetime_initiated" data-format="7" name="x_datetime_initiated" id="x_datetime_initiated" size="18" placeholder="<?php echo ew_HtmlEncode($report->datetime_initiated->getPlaceHolder()) ?>" value="<?php echo $report->datetime_initiated->EditValue ?>"<?php echo $report->datetime_initiated->EditAttributes() ?>>
<?php if (!$report->datetime_initiated->ReadOnly && !$report->datetime_initiated->Disabled && !isset($report->datetime_initiated->EditAttrs["readonly"]) && !isset($report->datetime_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freportedit", "x_datetime_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_datetime_initiated">
<span<?php echo $report->datetime_initiated->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->datetime_initiated->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_datetime_initiated" name="x_datetime_initiated" id="x_datetime_initiated" value="<?php echo ew_HtmlEncode($report->datetime_initiated->FormValue) ?>">
<?php } ?>
<?php echo $report->datetime_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_id->Visible) { // incident_id ?>
	<div id="r_incident_id" class="form-group">
		<label id="elh_report_incident_id" for="x_incident_id" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_id->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_id">
<input type="text" data-table="report" data-field="x_incident_id" name="x_incident_id" id="x_incident_id" size="18" placeholder="<?php echo ew_HtmlEncode($report->incident_id->getPlaceHolder()) ?>" value="<?php echo $report->incident_id->EditValue ?>"<?php echo $report->incident_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_incident_id">
<span<?php echo $report->incident_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_id" name="x_incident_id" id="x_incident_id" value="<?php echo ew_HtmlEncode($report->incident_id->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->staffid->Visible) { // staffid ?>
	<div id="r_staffid" class="form-group">
		<label id="elh_report_staffid" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->staffid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->staffid->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_staffid">
<?php
$wrkonchange = trim(" " . @$report->staffid->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report->staffid->EditAttrs["onchange"] = "";
?>
<span id="as_x_staffid" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staffid" id="sv_x_staffid" value="<?php echo $report->staffid->EditValue ?>" size="18" placeholder="<?php echo ew_HtmlEncode($report->staffid->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report->staffid->getPlaceHolder()) ?>"<?php echo $report->staffid->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report" data-field="x_staffid" data-value-separator="<?php echo $report->staffid->DisplayValueSeparatorAttribute() ?>" name="x_staffid" id="x_staffid" value="<?php echo ew_HtmlEncode($report->staffid->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freportedit.CreateAutoSuggest({"id":"x_staffid","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_staffid">
<span<?php echo $report->staffid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->staffid->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_staffid" name="x_staffid" id="x_staffid" value="<?php echo ew_HtmlEncode($report->staffid->FormValue) ?>">
<?php } ?>
<?php echo $report->staffid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_report_staff_id" for="x_staff_id" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->staff_id->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_staff_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_staff_id"><?php echo (strval($report->staff_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->staff_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->staff_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_staff_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->staff_id->ReadOnly || $report->staff_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_staff_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo $report->staff_id->CurrentValue ?>"<?php echo $report->staff_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_staff_id">
<span<?php echo $report->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_staff_id" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($report->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $report->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_report_department" for="x_department" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->department->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_department">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_department"><?php echo (strval($report->department->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->department->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->department->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_department',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->department->ReadOnly || $report->department->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_department" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="<?php echo $report->department->CurrentValue ?>"<?php echo $report->department->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_department">
<span<?php echo $report->department->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->department->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_department" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($report->department->FormValue) ?>">
<?php } ?>
<?php echo $report->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->branch->Visible) { // branch ?>
	<div id="r_branch" class="form-group">
		<label id="elh_report_branch" for="x_branch" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->branch->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->branch->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_branch">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_branch"><?php echo (strval($report->branch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->branch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->branch->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_branch',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->branch->ReadOnly || $report->branch->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_branch" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->branch->DisplayValueSeparatorAttribute() ?>" name="x_branch" id="x_branch" value="<?php echo $report->branch->CurrentValue ?>"<?php echo $report->branch->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_branch">
<span<?php echo $report->branch->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->branch->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_branch" name="x_branch" id="x_branch" value="<?php echo ew_HtmlEncode($report->branch->FormValue) ?>">
<?php } ?>
<?php echo $report->branch->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->departments->Visible) { // departments ?>
	<div id="r_departments" class="form-group">
		<label id="elh_report_departments" for="x_departments" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->departments->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->departments->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_departments">
<?php $report->departments->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report->departments->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_departments"><?php echo (strval($report->departments->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->departments->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->departments->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_departments',m:0,n:5});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->departments->ReadOnly || $report->departments->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_departments" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->departments->DisplayValueSeparatorAttribute() ?>" name="x_departments" id="x_departments" value="<?php echo $report->departments->CurrentValue ?>"<?php echo $report->departments->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_departments">
<span<?php echo $report->departments->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->departments->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_departments" name="x_departments" id="x_departments" value="<?php echo ew_HtmlEncode($report->departments->FormValue) ?>">
<?php } ?>
<?php echo $report->departments->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->category->Visible) { // category ?>
	<div id="r_category" class="form-group">
		<label id="elh_report_category" for="x_category" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->category->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_category">
<?php $report->category->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report->category->EditAttrs["onchange"]; ?>
<select data-table="report" data-field="x_category" data-value-separator="<?php echo $report->category->DisplayValueSeparatorAttribute() ?>" id="x_category" name="x_category"<?php echo $report->category->EditAttributes() ?>>
<?php echo $report->category->SelectOptionListHtml("x_category") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_category">
<span<?php echo $report->category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_category" name="x_category" id="x_category" value="<?php echo ew_HtmlEncode($report->category->FormValue) ?>">
<?php } ?>
<?php echo $report->category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->sub_category->Visible) { // sub_category ?>
	<div id="r_sub_category" class="form-group">
		<label id="elh_report_sub_category" for="x_sub_category" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->sub_category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->sub_category->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_sub_category">
<?php $report->sub_category->EditAttrs["onclick"] = "ew_UpdateOpt.call(this); " . @$report->sub_category->EditAttrs["onclick"]; ?>
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report->sub_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report->sub_category->ViewValue ?>
	</span>
	<?php if (!$report->sub_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_sub_category" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report->sub_category->RadioButtonListHtml(TRUE, "x_sub_category") ?>
		</div>
	</div>
	<div id="tp_x_sub_category" class="ewTemplate"><input type="radio" data-table="report" data-field="x_sub_category" data-value-separator="<?php echo $report->sub_category->DisplayValueSeparatorAttribute() ?>" name="x_sub_category" id="x_sub_category" value="{value}"<?php echo $report->sub_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_sub_category">
<span<?php echo $report->sub_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->sub_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_sub_category" name="x_sub_category" id="x_sub_category" value="<?php echo ew_HtmlEncode($report->sub_category->FormValue) ?>">
<?php } ?>
<?php echo $report->sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->sub_sub_category->Visible) { // sub_sub_category ?>
	<div id="r_sub_sub_category" class="form-group">
		<label id="elh_report_sub_sub_category" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->sub_sub_category->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->sub_sub_category->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_sub_sub_category">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report->sub_sub_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report->sub_sub_category->ViewValue ?>
	</span>
	<?php if (!$report->sub_sub_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_sub_sub_category" data-repeatcolumn="5" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report->sub_sub_category->CheckBoxListHtml(TRUE, "x_sub_sub_category[]") ?>
		</div>
	</div>
	<div id="tp_x_sub_sub_category" class="ewTemplate"><input type="checkbox" data-table="report" data-field="x_sub_sub_category" data-value-separator="<?php echo $report->sub_sub_category->DisplayValueSeparatorAttribute() ?>" name="x_sub_sub_category[]" id="x_sub_sub_category[]" value="{value}"<?php echo $report->sub_sub_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_sub_sub_category">
<span<?php echo $report->sub_sub_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->sub_sub_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_sub_sub_category" name="x_sub_sub_category" id="x_sub_sub_category" value="<?php echo ew_HtmlEncode($report->sub_sub_category->FormValue) ?>">
<?php } ?>
<?php echo $report->sub_sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->start_date->Visible) { // start_date ?>
	<div id="r_start_date" class="form-group">
		<label id="elh_report_start_date" for="x_start_date" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->start_date->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->start_date->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_start_date">
<input type="text" data-table="report" data-field="x_start_date" data-format="2" name="x_start_date" id="x_start_date" size="18" placeholder="<?php echo ew_HtmlEncode($report->start_date->getPlaceHolder()) ?>" value="<?php echo $report->start_date->EditValue ?>"<?php echo $report->start_date->EditAttributes() ?>>
<?php if (!$report->start_date->ReadOnly && !$report->start_date->Disabled && !isset($report->start_date->EditAttrs["readonly"]) && !isset($report->start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freportedit", "x_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":2});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_start_date">
<span<?php echo $report->start_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->start_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_start_date" name="x_start_date" id="x_start_date" value="<?php echo ew_HtmlEncode($report->start_date->FormValue) ?>">
<?php } ?>
<?php echo $report->start_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->end_date->Visible) { // end_date ?>
	<div id="r_end_date" class="form-group">
		<label id="elh_report_end_date" for="x_end_date" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->end_date->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->end_date->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_end_date">
<input type="text" data-table="report" data-field="x_end_date" data-format="2" name="x_end_date" id="x_end_date" size="18" placeholder="<?php echo ew_HtmlEncode($report->end_date->getPlaceHolder()) ?>" value="<?php echo $report->end_date->EditValue ?>"<?php echo $report->end_date->EditAttributes() ?>>
<?php if (!$report->end_date->ReadOnly && !$report->end_date->Disabled && !isset($report->end_date->EditAttrs["readonly"]) && !isset($report->end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freportedit", "x_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":2});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_end_date">
<span<?php echo $report->end_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->end_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_end_date" name="x_end_date" id="x_end_date" value="<?php echo ew_HtmlEncode($report->end_date->FormValue) ?>">
<?php } ?>
<?php echo $report->end_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->duration->Visible) { // duration ?>
	<div id="r_duration" class="form-group">
		<label id="elh_report_duration" for="x_duration" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->duration->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->duration->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_duration">
<input type="text" data-table="report" data-field="x_duration" name="x_duration" id="x_duration" size="18" placeholder="<?php echo ew_HtmlEncode($report->duration->getPlaceHolder()) ?>" value="<?php echo $report->duration->EditValue ?>"<?php echo $report->duration->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_duration">
<span<?php echo $report->duration->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->duration->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_duration" name="x_duration" id="x_duration" value="<?php echo ew_HtmlEncode($report->duration->FormValue) ?>">
<?php } ?>
<?php echo $report->duration->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->amount_paid->Visible) { // amount_paid ?>
	<div id="r_amount_paid" class="form-group">
		<label id="elh_report_amount_paid" for="x_amount_paid" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->amount_paid->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->amount_paid->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_amount_paid">
<input type="text" data-table="report" data-field="x_amount_paid" name="x_amount_paid" id="x_amount_paid" size="18" placeholder="<?php echo ew_HtmlEncode($report->amount_paid->getPlaceHolder()) ?>" value="<?php echo $report->amount_paid->EditValue ?>"<?php echo $report->amount_paid->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_amount_paid">
<span<?php echo $report->amount_paid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->amount_paid->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_amount_paid" name="x_amount_paid" id="x_amount_paid" value="<?php echo ew_HtmlEncode($report->amount_paid->FormValue) ?>">
<?php } ?>
<?php echo $report->amount_paid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->no_of_people_involved->Visible) { // no_of_people_involved ?>
	<div id="r_no_of_people_involved" class="form-group">
		<label id="elh_report_no_of_people_involved" for="x_no_of_people_involved" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->no_of_people_involved->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->no_of_people_involved->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_no_of_people_involved">
<input type="text" data-table="report" data-field="x_no_of_people_involved" name="x_no_of_people_involved" id="x_no_of_people_involved" size="18" placeholder="<?php echo ew_HtmlEncode($report->no_of_people_involved->getPlaceHolder()) ?>" value="<?php echo $report->no_of_people_involved->EditValue ?>"<?php echo $report->no_of_people_involved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_no_of_people_involved">
<span<?php echo $report->no_of_people_involved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->no_of_people_involved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_no_of_people_involved" name="x_no_of_people_involved" id="x_no_of_people_involved" value="<?php echo ew_HtmlEncode($report->no_of_people_involved->FormValue) ?>">
<?php } ?>
<?php echo $report->no_of_people_involved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_type->Visible) { // incident_type ?>
	<div id="r_incident_type" class="form-group">
		<label id="elh_report_incident_type" for="x_incident_type" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_type->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_type->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_type">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report->incident_type->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report->incident_type->ViewValue ?>
	</span>
	<?php if (!$report->incident_type->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_type" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report->incident_type->RadioButtonListHtml(TRUE, "x_incident_type") ?>
		</div>
	</div>
	<div id="tp_x_incident_type" class="ewTemplate"><input type="radio" data-table="report" data-field="x_incident_type" data-value-separator="<?php echo $report->incident_type->DisplayValueSeparatorAttribute() ?>" name="x_incident_type" id="x_incident_type" value="{value}"<?php echo $report->incident_type->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_incident_type">
<span<?php echo $report->incident_type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_type" name="x_incident_type" id="x_incident_type" value="<?php echo ew_HtmlEncode($report->incident_type->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_category->Visible) { // incident-category ?>
	<div id="r_incident_category" class="form-group">
		<label id="elh_report_incident_category" for="x_incident_category" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_category->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_category">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report->incident_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report->incident_category->ViewValue ?>
	</span>
	<?php if (!$report->incident_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_category" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report->incident_category->RadioButtonListHtml(TRUE, "x_incident_category") ?>
		</div>
	</div>
	<div id="tp_x_incident_category" class="ewTemplate"><input type="radio" data-table="report" data-field="x_incident_category" data-value-separator="<?php echo $report->incident_category->DisplayValueSeparatorAttribute() ?>" name="x_incident_category" id="x_incident_category" value="{value}"<?php echo $report->incident_category->EditAttributes() ?>></div>
</div>
</span>
<?php } else { ?>
<span id="el_report_incident_category">
<span<?php echo $report->incident_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_category" name="x_incident_category" id="x_incident_category" value="<?php echo ew_HtmlEncode($report->incident_category->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_location->Visible) { // incident_location ?>
	<div id="r_incident_location" class="form-group">
		<label id="elh_report_incident_location" for="x_incident_location" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_location->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_location->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_location">
<?php $report->incident_location->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report->incident_location->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_incident_location"><?php echo (strval($report->incident_location->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->incident_location->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->incident_location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_incident_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->incident_location->ReadOnly || $report->incident_location->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_incident_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->incident_location->DisplayValueSeparatorAttribute() ?>" name="x_incident_location" id="x_incident_location" value="<?php echo $report->incident_location->CurrentValue ?>"<?php echo $report->incident_location->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_incident_location">
<span<?php echo $report->incident_location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_location" name="x_incident_location" id="x_incident_location" value="<?php echo ew_HtmlEncode($report->incident_location->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_sub_location->Visible) { // incident_sub_location ?>
	<div id="r_incident_sub_location" class="form-group">
		<label id="elh_report_incident_sub_location" for="x_incident_sub_location" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_sub_location->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_sub_location->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_sub_location">
<?php $report->incident_sub_location->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report->incident_sub_location->EditAttrs["onchange"]; ?>
<select data-table="report" data-field="x_incident_sub_location" data-value-separator="<?php echo $report->incident_sub_location->DisplayValueSeparatorAttribute() ?>" id="x_incident_sub_location" name="x_incident_sub_location"<?php echo $report->incident_sub_location->EditAttributes() ?>>
<?php echo $report->incident_sub_location->SelectOptionListHtml("x_incident_sub_location") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_incident_sub_location">
<span<?php echo $report->incident_sub_location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_sub_location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_sub_location" name="x_incident_sub_location" id="x_incident_sub_location" value="<?php echo ew_HtmlEncode($report->incident_sub_location->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_sub_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_venue->Visible) { // incident_venue ?>
	<div id="r_incident_venue" class="form-group">
		<label id="elh_report_incident_venue" for="x_incident_venue" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_venue->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_venue->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_venue">
<select data-table="report" data-field="x_incident_venue" data-value-separator="<?php echo $report->incident_venue->DisplayValueSeparatorAttribute() ?>" id="x_incident_venue" name="x_incident_venue"<?php echo $report->incident_venue->EditAttributes() ?>>
<?php echo $report->incident_venue->SelectOptionListHtml("x_incident_venue") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_incident_venue">
<span<?php echo $report->incident_venue->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_venue->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_venue" name="x_incident_venue" id="x_incident_venue" value="<?php echo ew_HtmlEncode($report->incident_venue->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_venue->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->incident_description->Visible) { // incident_description ?>
	<div id="r_incident_description" class="form-group">
		<label id="elh_report_incident_description" for="x_incident_description" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->incident_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->incident_description->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_incident_description">
<textarea data-table="report" data-field="x_incident_description" name="x_incident_description" id="x_incident_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report->incident_description->getPlaceHolder()) ?>"<?php echo $report->incident_description->EditAttributes() ?>><?php echo $report->incident_description->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_incident_description">
<span<?php echo $report->incident_description->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->incident_description->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_incident_description" name="x_incident_description" id="x_incident_description" value="<?php echo ew_HtmlEncode($report->incident_description->FormValue) ?>">
<?php } ?>
<?php echo $report->incident_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->_upload->Visible) { // upload ?>
	<div id="r__upload" class="form-group">
		<label id="elh_report__upload" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->_upload->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->_upload->CellAttributes() ?>>
<span id="el_report__upload">
<div id="fd_x__upload">
<span title="<?php echo $report->_upload->FldTitle() ? $report->_upload->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($report->_upload->ReadOnly || $report->_upload->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="report" data-field="x__upload" name="x__upload" id="x__upload" multiple="multiple"<?php echo $report->_upload->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x__upload" id= "fn_x__upload" value="<?php echo $report->_upload->Upload->FileName ?>">
<?php if (@$_POST["fa_x__upload"] == "0") { ?>
<input type="hidden" name="fa_x__upload" id= "fa_x__upload" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x__upload" id= "fa_x__upload" value="1">
<?php } ?>
<input type="hidden" name="fs_x__upload" id= "fs_x__upload" value="128">
<input type="hidden" name="fx_x__upload" id= "fx_x__upload" value="<?php echo $report->_upload->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x__upload" id= "fm_x__upload" value="<?php echo $report->_upload->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x__upload" id= "fc_x__upload" value="<?php echo $report->_upload->UploadMaxFileCount ?>">
</div>
<table id="ft_x__upload" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $report->_upload->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_report_status" for="x_status" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->status->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_status">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_status"><?php echo (strval($report->status->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->status->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->status->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_status',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->status->ReadOnly || $report->status->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_status" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo $report->status->CurrentValue ?>"<?php echo $report->status->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_status">
<span<?php echo $report->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_status" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($report->status->FormValue) ?>">
<?php } ?>
<?php echo $report->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_report_initiator_action" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->initiator_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->initiator_action->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="report" data-field="x_initiator_action" data-value-separator="<?php echo $report->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $report->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_initiator_action">
<span<?php echo $report->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->initiator_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_initiator_action" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($report->initiator_action->FormValue) ?>">
<?php } ?>
<?php echo $report->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_report_initiator_comment" for="x_initiator_comment" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->initiator_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->initiator_comment->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_initiator_comment">
<textarea data-table="report" data-field="x_initiator_comment" name="x_initiator_comment" id="x_initiator_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report->initiator_comment->getPlaceHolder()) ?>"<?php echo $report->initiator_comment->EditAttributes() ?>><?php echo $report->initiator_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_initiator_comment">
<span<?php echo $report->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->initiator_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_initiator_comment" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($report->initiator_comment->FormValue) ?>">
<?php } ?>
<?php echo $report->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->report_by->Visible) { // report_by ?>
	<div id="r_report_by" class="form-group">
		<label id="elh_report_report_by" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->report_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->report_by->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_report_by">
<?php
$wrkonchange = trim(" " . @$report->report_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report->report_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_report_by" style="white-space: nowrap; z-index: 8730">
	<input type="text" name="sv_x_report_by" id="sv_x_report_by" value="<?php echo $report->report_by->EditValue ?>" size="15" placeholder="<?php echo ew_HtmlEncode($report->report_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report->report_by->getPlaceHolder()) ?>"<?php echo $report->report_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report" data-field="x_report_by" data-value-separator="<?php echo $report->report_by->DisplayValueSeparatorAttribute() ?>" name="x_report_by" id="x_report_by" value="<?php echo ew_HtmlEncode($report->report_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freportedit.CreateAutoSuggest({"id":"x_report_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_report_by">
<span<?php echo $report->report_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->report_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_report_by" name="x_report_by" id="x_report_by" value="<?php echo ew_HtmlEncode($report->report_by->FormValue) ?>">
<?php } ?>
<?php echo $report->report_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->datetime_resolved->Visible) { // datetime_resolved ?>
	<div id="r_datetime_resolved" class="form-group">
		<label id="elh_report_datetime_resolved" for="x_datetime_resolved" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->datetime_resolved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->datetime_resolved->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_datetime_resolved">
<input type="text" data-table="report" data-field="x_datetime_resolved" data-format="11" name="x_datetime_resolved" id="x_datetime_resolved" size="20" placeholder="<?php echo ew_HtmlEncode($report->datetime_resolved->getPlaceHolder()) ?>" value="<?php echo $report->datetime_resolved->EditValue ?>"<?php echo $report->datetime_resolved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_datetime_resolved">
<span<?php echo $report->datetime_resolved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->datetime_resolved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_datetime_resolved" name="x_datetime_resolved" id="x_datetime_resolved" value="<?php echo ew_HtmlEncode($report->datetime_resolved->FormValue) ?>">
<?php } ?>
<?php echo $report->datetime_resolved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->assign_task->Visible) { // assign_task ?>
	<div id="r_assign_task" class="form-group">
		<label id="elh_report_assign_task" for="x_assign_task" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->assign_task->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->assign_task->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_assign_task">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_assign_task"><?php echo (strval($report->assign_task->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report->assign_task->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->assign_task->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_assign_task',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->assign_task->ReadOnly || $report->assign_task->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report" data-field="x_assign_task" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->assign_task->DisplayValueSeparatorAttribute() ?>" name="x_assign_task" id="x_assign_task" value="<?php echo $report->assign_task->CurrentValue ?>"<?php echo $report->assign_task->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_assign_task">
<span<?php echo $report->assign_task->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->assign_task->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_assign_task" name="x_assign_task" id="x_assign_task" value="<?php echo ew_HtmlEncode($report->assign_task->FormValue) ?>">
<?php } ?>
<?php echo $report->assign_task->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->approval_action->Visible) { // approval_action ?>
	<div id="r_approval_action" class="form-group">
		<label id="elh_report_approval_action" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->approval_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->approval_action->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_approval_action">
<div id="tp_x_approval_action" class="ewTemplate"><input type="radio" data-table="report" data-field="x_approval_action" data-value-separator="<?php echo $report->approval_action->DisplayValueSeparatorAttribute() ?>" name="x_approval_action" id="x_approval_action" value="{value}"<?php echo $report->approval_action->EditAttributes() ?>></div>
<div id="dsl_x_approval_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report->approval_action->RadioButtonListHtml(FALSE, "x_approval_action") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_approval_action">
<span<?php echo $report->approval_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->approval_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_approval_action" name="x_approval_action" id="x_approval_action" value="<?php echo ew_HtmlEncode($report->approval_action->FormValue) ?>">
<?php } ?>
<?php echo $report->approval_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->approval_comment->Visible) { // approval_comment ?>
	<div id="r_approval_comment" class="form-group">
		<label id="elh_report_approval_comment" for="x_approval_comment" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->approval_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->approval_comment->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_approval_comment">
<textarea data-table="report" data-field="x_approval_comment" name="x_approval_comment" id="x_approval_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report->approval_comment->getPlaceHolder()) ?>"<?php echo $report->approval_comment->EditAttributes() ?>><?php echo $report->approval_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_approval_comment">
<span<?php echo $report->approval_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->approval_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_approval_comment" name="x_approval_comment" id="x_approval_comment" value="<?php echo ew_HtmlEncode($report->approval_comment->FormValue) ?>">
<?php } ?>
<?php echo $report->approval_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->reason->Visible) { // reason ?>
	<div id="r_reason" class="form-group">
		<label id="elh_report_reason" for="x_reason" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->reason->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->reason->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_reason">
<select data-table="report" data-field="x_reason" data-value-separator="<?php echo $report->reason->DisplayValueSeparatorAttribute() ?>" id="x_reason" name="x_reason"<?php echo $report->reason->EditAttributes() ?>>
<?php echo $report->reason->SelectOptionListHtml("x_reason") ?>
</select>
</span>
<?php } else { ?>
<span id="el_report_reason">
<span<?php echo $report->reason->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->reason->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_reason" name="x_reason" id="x_reason" value="<?php echo ew_HtmlEncode($report->reason->FormValue) ?>">
<?php } ?>
<?php echo $report->reason->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->resolved_action->Visible) { // resolved_action ?>
	<div id="r_resolved_action" class="form-group">
		<label id="elh_report_resolved_action" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->resolved_action->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->resolved_action->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_resolved_action">
<div id="tp_x_resolved_action" class="ewTemplate"><input type="radio" data-table="report" data-field="x_resolved_action" data-value-separator="<?php echo $report->resolved_action->DisplayValueSeparatorAttribute() ?>" name="x_resolved_action" id="x_resolved_action" value="{value}"<?php echo $report->resolved_action->EditAttributes() ?>></div>
<div id="dsl_x_resolved_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report->resolved_action->RadioButtonListHtml(FALSE, "x_resolved_action") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_resolved_action">
<span<?php echo $report->resolved_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->resolved_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_resolved_action" name="x_resolved_action" id="x_resolved_action" value="<?php echo ew_HtmlEncode($report->resolved_action->FormValue) ?>">
<?php } ?>
<?php echo $report->resolved_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->resolved_comment->Visible) { // resolved_comment ?>
	<div id="r_resolved_comment" class="form-group">
		<label id="elh_report_resolved_comment" for="x_resolved_comment" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->resolved_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->resolved_comment->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_resolved_comment">
<textarea data-table="report" data-field="x_resolved_comment" name="x_resolved_comment" id="x_resolved_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($report->resolved_comment->getPlaceHolder()) ?>"<?php echo $report->resolved_comment->EditAttributes() ?>><?php echo $report->resolved_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_report_resolved_comment">
<span<?php echo $report->resolved_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->resolved_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_resolved_comment" name="x_resolved_comment" id="x_resolved_comment" value="<?php echo ew_HtmlEncode($report->resolved_comment->FormValue) ?>">
<?php } ?>
<?php echo $report->resolved_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->resolved_by->Visible) { // resolved_by ?>
	<div id="r_resolved_by" class="form-group">
		<label id="elh_report_resolved_by" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->resolved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->resolved_by->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_resolved_by">
<?php
$wrkonchange = trim(" " . @$report->resolved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report->resolved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_resolved_by" style="white-space: nowrap; z-index: 8650">
	<input type="text" name="sv_x_resolved_by" id="sv_x_resolved_by" value="<?php echo $report->resolved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report->resolved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report->resolved_by->getPlaceHolder()) ?>"<?php echo $report->resolved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report" data-field="x_resolved_by" data-value-separator="<?php echo $report->resolved_by->DisplayValueSeparatorAttribute() ?>" name="x_resolved_by" id="x_resolved_by" value="<?php echo ew_HtmlEncode($report->resolved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freportedit.CreateAutoSuggest({"id":"x_resolved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_resolved_by">
<span<?php echo $report->resolved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->resolved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_resolved_by" name="x_resolved_by" id="x_resolved_by" value="<?php echo ew_HtmlEncode($report->resolved_by->FormValue) ?>">
<?php } ?>
<?php echo $report->resolved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->datetime_approved->Visible) { // datetime_approved ?>
	<div id="r_datetime_approved" class="form-group">
		<label id="elh_report_datetime_approved" for="x_datetime_approved" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->datetime_approved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->datetime_approved->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_datetime_approved">
<input type="text" data-table="report" data-field="x_datetime_approved" data-format="11" name="x_datetime_approved" id="x_datetime_approved" size="17" placeholder="<?php echo ew_HtmlEncode($report->datetime_approved->getPlaceHolder()) ?>" value="<?php echo $report->datetime_approved->EditValue ?>"<?php echo $report->datetime_approved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_datetime_approved">
<span<?php echo $report->datetime_approved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->datetime_approved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_datetime_approved" name="x_datetime_approved" id="x_datetime_approved" value="<?php echo ew_HtmlEncode($report->datetime_approved->FormValue) ?>">
<?php } ?>
<?php echo $report->datetime_approved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_report_approved_by" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->approved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->approved_by->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_approved_by">
<?php
$wrkonchange = trim(" " . @$report->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8630">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $report->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report->approved_by->getPlaceHolder()) ?>"<?php echo $report->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report" data-field="x_approved_by" data-value-separator="<?php echo $report->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($report->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freportedit.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_report_approved_by">
<span<?php echo $report->approved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->approved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_approved_by" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($report->approved_by->FormValue) ?>">
<?php } ?>
<?php echo $report->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label id="elh_report_verified_by" for="x_verified_by" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->verified_by->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->verified_by->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_verified_by">
<input type="text" data-table="report" data-field="x_verified_by" name="x_verified_by" id="x_verified_by" size="30" placeholder="<?php echo ew_HtmlEncode($report->verified_by->getPlaceHolder()) ?>" value="<?php echo $report->verified_by->EditValue ?>"<?php echo $report->verified_by->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_verified_by">
<span<?php echo $report->verified_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->verified_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_verified_by" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($report->verified_by->FormValue) ?>">
<?php } ?>
<?php echo $report->verified_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->last_updated_date->Visible) { // last_updated_date ?>
	<div id="r_last_updated_date" class="form-group">
		<label id="elh_report_last_updated_date" for="x_last_updated_date" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->last_updated_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->last_updated_date->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_last_updated_date">
<input type="text" data-table="report" data-field="x_last_updated_date" name="x_last_updated_date" id="x_last_updated_date" placeholder="<?php echo ew_HtmlEncode($report->last_updated_date->getPlaceHolder()) ?>" value="<?php echo $report->last_updated_date->EditValue ?>"<?php echo $report->last_updated_date->EditAttributes() ?>>
<?php if (!$report->last_updated_date->ReadOnly && !$report->last_updated_date->Disabled && !isset($report->last_updated_date->EditAttrs["readonly"]) && !isset($report->last_updated_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freportedit", "x_last_updated_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_report_last_updated_date">
<span<?php echo $report->last_updated_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->last_updated_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_last_updated_date" name="x_last_updated_date" id="x_last_updated_date" value="<?php echo ew_HtmlEncode($report->last_updated_date->FormValue) ?>">
<?php } ?>
<?php echo $report->last_updated_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->last_updated_by->Visible) { // last_updated_by ?>
	<div id="r_last_updated_by" class="form-group">
		<label id="elh_report_last_updated_by" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->last_updated_by->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->last_updated_by->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_last_updated_by">
<?php
$wrkonchange = trim(" " . @$report->last_updated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report->last_updated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_last_updated_by" style="white-space: nowrap; z-index: 8600">
	<input type="text" name="sv_x_last_updated_by" id="sv_x_last_updated_by" value="<?php echo $report->last_updated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report->last_updated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report->last_updated_by->getPlaceHolder()) ?>"<?php echo $report->last_updated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report" data-field="x_last_updated_by" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report->last_updated_by->DisplayValueSeparatorAttribute() ?>" name="x_last_updated_by" id="x_last_updated_by" value="<?php echo ew_HtmlEncode($report->last_updated_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freportedit.CreateAutoSuggest({"id":"x_last_updated_by","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report->last_updated_by->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_last_updated_by',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report->last_updated_by->ReadOnly || $report->last_updated_by->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
<?php } else { ?>
<span id="el_report_last_updated_by">
<span<?php echo $report->last_updated_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->last_updated_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_last_updated_by" name="x_last_updated_by" id="x_last_updated_by" value="<?php echo ew_HtmlEncode($report->last_updated_by->FormValue) ?>">
<?php } ?>
<?php echo $report->last_updated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->selection_sub_category->Visible) { // selection_sub_category ?>
	<div id="r_selection_sub_category" class="form-group">
		<label id="elh_report_selection_sub_category" for="x_selection_sub_category" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->selection_sub_category->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->selection_sub_category->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_selection_sub_category">
<input type="text" data-table="report" data-field="x_selection_sub_category" name="x_selection_sub_category" id="x_selection_sub_category" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($report->selection_sub_category->getPlaceHolder()) ?>" value="<?php echo $report->selection_sub_category->EditValue ?>"<?php echo $report->selection_sub_category->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_selection_sub_category">
<span<?php echo $report->selection_sub_category->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->selection_sub_category->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_selection_sub_category" name="x_selection_sub_category" id="x_selection_sub_category" value="<?php echo ew_HtmlEncode($report->selection_sub_category->FormValue) ?>">
<?php } ?>
<?php echo $report->selection_sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->verified_datetime->Visible) { // verified_datetime ?>
	<div id="r_verified_datetime" class="form-group">
		<label id="elh_report_verified_datetime" for="x_verified_datetime" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->verified_datetime->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->verified_datetime->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_verified_datetime">
<input type="text" data-table="report" data-field="x_verified_datetime" name="x_verified_datetime" id="x_verified_datetime" placeholder="<?php echo ew_HtmlEncode($report->verified_datetime->getPlaceHolder()) ?>" value="<?php echo $report->verified_datetime->EditValue ?>"<?php echo $report->verified_datetime->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_verified_datetime">
<span<?php echo $report->verified_datetime->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->verified_datetime->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_verified_datetime" name="x_verified_datetime" id="x_verified_datetime" value="<?php echo ew_HtmlEncode($report->verified_datetime->FormValue) ?>">
<?php } ?>
<?php echo $report->verified_datetime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label id="elh_report_verified_action" for="x_verified_action" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->verified_action->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->verified_action->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_verified_action">
<input type="text" data-table="report" data-field="x_verified_action" name="x_verified_action" id="x_verified_action" size="30" placeholder="<?php echo ew_HtmlEncode($report->verified_action->getPlaceHolder()) ?>" value="<?php echo $report->verified_action->EditValue ?>"<?php echo $report->verified_action->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_verified_action">
<span<?php echo $report->verified_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->verified_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_verified_action" name="x_verified_action" id="x_verified_action" value="<?php echo ew_HtmlEncode($report->verified_action->FormValue) ?>">
<?php } ?>
<?php echo $report->verified_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label id="elh_report_verified_comment" for="x_verified_comment" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->verified_comment->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->verified_comment->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_verified_comment">
<input type="text" data-table="report" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($report->verified_comment->getPlaceHolder()) ?>" value="<?php echo $report->verified_comment->EditValue ?>"<?php echo $report->verified_comment->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_report_verified_comment">
<span<?php echo $report->verified_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->verified_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" value="<?php echo ew_HtmlEncode($report->verified_comment->FormValue) ?>">
<?php } ?>
<?php echo $report->verified_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($report->job_assessment->Visible) { // job_assessment ?>
	<div id="r_job_assessment" class="form-group">
		<label id="elh_report_job_assessment" class="<?php echo $report_edit->LeftColumnClass ?>"><?php echo $report->job_assessment->FldCaption() ?></label>
		<div class="<?php echo $report_edit->RightColumnClass ?>"><div<?php echo $report->job_assessment->CellAttributes() ?>>
<?php if ($report->CurrentAction <> "F") { ?>
<span id="el_report_job_assessment">
<div id="tp_x_job_assessment" class="ewTemplate"><input type="radio" data-table="report" data-field="x_job_assessment" data-value-separator="<?php echo $report->job_assessment->DisplayValueSeparatorAttribute() ?>" name="x_job_assessment" id="x_job_assessment" value="{value}"<?php echo $report->job_assessment->EditAttributes() ?>></div>
<div id="dsl_x_job_assessment" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report->job_assessment->RadioButtonListHtml(FALSE, "x_job_assessment") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_report_job_assessment">
<span<?php echo $report->job_assessment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $report->job_assessment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="report" data-field="x_job_assessment" name="x_job_assessment" id="x_job_assessment" value="<?php echo ew_HtmlEncode($report->job_assessment->FormValue) ?>">
<?php } ?>
<?php echo $report->job_assessment->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<input type="hidden" data-table="report" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($report->id->CurrentValue) ?>">
<?php if (!$report_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $report_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($report->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $report_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
freportedit.Init();
</script>
<?php
$report_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$report_edit->Page_Terminate();
?>
