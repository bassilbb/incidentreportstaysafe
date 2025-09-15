<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "requisition_moduleinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$requisition_module_edit = NULL; // Initialize page object first

class crequisition_module_edit extends crequisition_module {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'requisition_module';

	// Page object name
	var $PageObjName = 'requisition_module_edit';

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

		// Table object (requisition_module)
		if (!isset($GLOBALS["requisition_module"]) || get_class($GLOBALS["requisition_module"]) == "crequisition_module") {
			$GLOBALS["requisition_module"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["requisition_module"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'requisition_module');

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
				$this->Page_Terminate(ew_GetUrl("requisition_modulelist.php"));
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
		$this->date->SetVisibility();
		$this->reference->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->outward_location->SetVisibility();
		$this->delivery_point->SetVisibility();
		$this->name->SetVisibility();
		$this->organization->SetVisibility();
		$this->designation->SetVisibility();
		$this->department->SetVisibility();
		$this->item_description->SetVisibility();
		$this->driver_name->SetVisibility();
		$this->vehicle_no->SetVisibility();
		$this->requester_action->SetVisibility();
		$this->requester_comment->SetVisibility();
		$this->date_authorized->SetVisibility();
		$this->authorizer_name->SetVisibility();
		$this->authorizer_action->SetVisibility();
		$this->authorizer_comment->SetVisibility();
		$this->status->SetVisibility();
		$this->rep_date->SetVisibility();
		$this->rep_name->SetVisibility();
		$this->outward_datetime->SetVisibility();
		$this->rep_action->SetVisibility();
		$this->rep_comment->SetVisibility();

		// Set up multi page object
		$this->SetupMultiPages();

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
		global $EW_EXPORT, $requisition_module;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($requisition_module);
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
					if ($pageName == "requisition_moduleview.php")
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
	var $MultiPages; // Multi pages object

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
			if ($objForm->HasValue("x_code")) {
				$this->code->setFormValue($objForm->GetValue("x_code"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["code"])) {
				$this->code->setQueryStringValue($_GET["code"]);
				$loadByQuery = TRUE;
			} else {
				$this->code->CurrentValue = NULL;
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
			$this->Page_Terminate("requisition_modulelist.php"); // Return to list page
		} elseif ($loadByPosition) { // Load record by position
			$this->SetupStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$this->Recordset->Move($this->StartRec-1);
				$loaded = TRUE;
			}
		} else { // Match key values
			if (!is_null($this->code->CurrentValue)) {
				while (!$this->Recordset->EOF) {
					if (strval($this->code->CurrentValue) == strval($this->Recordset->fields('code'))) {
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
					$this->Page_Terminate("requisition_modulelist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "requisition_modulelist.php")
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
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 17);
		}
		if (!$this->reference->FldIsDetailKey) {
			$this->reference->setFormValue($objForm->GetValue("x_reference"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->outward_location->FldIsDetailKey) {
			$this->outward_location->setFormValue($objForm->GetValue("x_outward_location"));
		}
		if (!$this->delivery_point->FldIsDetailKey) {
			$this->delivery_point->setFormValue($objForm->GetValue("x_delivery_point"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->organization->FldIsDetailKey) {
			$this->organization->setFormValue($objForm->GetValue("x_organization"));
		}
		if (!$this->designation->FldIsDetailKey) {
			$this->designation->setFormValue($objForm->GetValue("x_designation"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->item_description->FldIsDetailKey) {
			$this->item_description->setFormValue($objForm->GetValue("x_item_description"));
		}
		if (!$this->driver_name->FldIsDetailKey) {
			$this->driver_name->setFormValue($objForm->GetValue("x_driver_name"));
		}
		if (!$this->vehicle_no->FldIsDetailKey) {
			$this->vehicle_no->setFormValue($objForm->GetValue("x_vehicle_no"));
		}
		if (!$this->requester_action->FldIsDetailKey) {
			$this->requester_action->setFormValue($objForm->GetValue("x_requester_action"));
		}
		if (!$this->requester_comment->FldIsDetailKey) {
			$this->requester_comment->setFormValue($objForm->GetValue("x_requester_comment"));
		}
		if (!$this->date_authorized->FldIsDetailKey) {
			$this->date_authorized->setFormValue($objForm->GetValue("x_date_authorized"));
			$this->date_authorized->CurrentValue = ew_UnFormatDateTime($this->date_authorized->CurrentValue, 17);
		}
		if (!$this->authorizer_name->FldIsDetailKey) {
			$this->authorizer_name->setFormValue($objForm->GetValue("x_authorizer_name"));
		}
		if (!$this->authorizer_action->FldIsDetailKey) {
			$this->authorizer_action->setFormValue($objForm->GetValue("x_authorizer_action"));
		}
		if (!$this->authorizer_comment->FldIsDetailKey) {
			$this->authorizer_comment->setFormValue($objForm->GetValue("x_authorizer_comment"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->rep_date->FldIsDetailKey) {
			$this->rep_date->setFormValue($objForm->GetValue("x_rep_date"));
			$this->rep_date->CurrentValue = ew_UnFormatDateTime($this->rep_date->CurrentValue, 17);
		}
		if (!$this->rep_name->FldIsDetailKey) {
			$this->rep_name->setFormValue($objForm->GetValue("x_rep_name"));
		}
		if (!$this->outward_datetime->FldIsDetailKey) {
			$this->outward_datetime->setFormValue($objForm->GetValue("x_outward_datetime"));
			$this->outward_datetime->CurrentValue = ew_UnFormatDateTime($this->outward_datetime->CurrentValue, 17);
		}
		if (!$this->rep_action->FldIsDetailKey) {
			$this->rep_action->setFormValue($objForm->GetValue("x_rep_action"));
		}
		if (!$this->rep_comment->FldIsDetailKey) {
			$this->rep_comment->setFormValue($objForm->GetValue("x_rep_comment"));
		}
		if (!$this->code->FldIsDetailKey)
			$this->code->setFormValue($objForm->GetValue("x_code"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->code->CurrentValue = $this->code->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 17);
		$this->reference->CurrentValue = $this->reference->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->outward_location->CurrentValue = $this->outward_location->FormValue;
		$this->delivery_point->CurrentValue = $this->delivery_point->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->organization->CurrentValue = $this->organization->FormValue;
		$this->designation->CurrentValue = $this->designation->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->item_description->CurrentValue = $this->item_description->FormValue;
		$this->driver_name->CurrentValue = $this->driver_name->FormValue;
		$this->vehicle_no->CurrentValue = $this->vehicle_no->FormValue;
		$this->requester_action->CurrentValue = $this->requester_action->FormValue;
		$this->requester_comment->CurrentValue = $this->requester_comment->FormValue;
		$this->date_authorized->CurrentValue = $this->date_authorized->FormValue;
		$this->date_authorized->CurrentValue = ew_UnFormatDateTime($this->date_authorized->CurrentValue, 17);
		$this->authorizer_name->CurrentValue = $this->authorizer_name->FormValue;
		$this->authorizer_action->CurrentValue = $this->authorizer_action->FormValue;
		$this->authorizer_comment->CurrentValue = $this->authorizer_comment->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->rep_date->CurrentValue = $this->rep_date->FormValue;
		$this->rep_date->CurrentValue = ew_UnFormatDateTime($this->rep_date->CurrentValue, 17);
		$this->rep_name->CurrentValue = $this->rep_name->FormValue;
		$this->outward_datetime->CurrentValue = $this->outward_datetime->FormValue;
		$this->outward_datetime->CurrentValue = ew_UnFormatDateTime($this->outward_datetime->CurrentValue, 17);
		$this->rep_action->CurrentValue = $this->rep_action->FormValue;
		$this->rep_comment->CurrentValue = $this->rep_comment->FormValue;
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
		$this->date->setDbValue($row['date']);
		$this->reference->setDbValue($row['reference']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->outward_location->setDbValue($row['outward_location']);
		$this->delivery_point->setDbValue($row['delivery_point']);
		$this->name->setDbValue($row['name']);
		$this->organization->setDbValue($row['organization']);
		$this->designation->setDbValue($row['designation']);
		$this->department->setDbValue($row['department']);
		$this->item_description->setDbValue($row['item_description']);
		$this->driver_name->setDbValue($row['driver_name']);
		$this->vehicle_no->setDbValue($row['vehicle_no']);
		$this->requester_action->setDbValue($row['requester_action']);
		$this->requester_comment->setDbValue($row['requester_comment']);
		$this->date_authorized->setDbValue($row['date_authorized']);
		$this->authorizer_name->setDbValue($row['authorizer_name']);
		$this->authorizer_action->setDbValue($row['authorizer_action']);
		$this->authorizer_comment->setDbValue($row['authorizer_comment']);
		$this->status->setDbValue($row['status']);
		$this->rep_date->setDbValue($row['rep_date']);
		$this->rep_name->setDbValue($row['rep_name']);
		$this->outward_datetime->setDbValue($row['outward_datetime']);
		$this->rep_action->setDbValue($row['rep_action']);
		$this->rep_comment->setDbValue($row['rep_comment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['date'] = NULL;
		$row['reference'] = NULL;
		$row['staff_id'] = NULL;
		$row['outward_location'] = NULL;
		$row['delivery_point'] = NULL;
		$row['name'] = NULL;
		$row['organization'] = NULL;
		$row['designation'] = NULL;
		$row['department'] = NULL;
		$row['item_description'] = NULL;
		$row['driver_name'] = NULL;
		$row['vehicle_no'] = NULL;
		$row['requester_action'] = NULL;
		$row['requester_comment'] = NULL;
		$row['date_authorized'] = NULL;
		$row['authorizer_name'] = NULL;
		$row['authorizer_action'] = NULL;
		$row['authorizer_comment'] = NULL;
		$row['status'] = NULL;
		$row['rep_date'] = NULL;
		$row['rep_name'] = NULL;
		$row['outward_datetime'] = NULL;
		$row['rep_action'] = NULL;
		$row['rep_comment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date->DbValue = $row['date'];
		$this->reference->DbValue = $row['reference'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->outward_location->DbValue = $row['outward_location'];
		$this->delivery_point->DbValue = $row['delivery_point'];
		$this->name->DbValue = $row['name'];
		$this->organization->DbValue = $row['organization'];
		$this->designation->DbValue = $row['designation'];
		$this->department->DbValue = $row['department'];
		$this->item_description->DbValue = $row['item_description'];
		$this->driver_name->DbValue = $row['driver_name'];
		$this->vehicle_no->DbValue = $row['vehicle_no'];
		$this->requester_action->DbValue = $row['requester_action'];
		$this->requester_comment->DbValue = $row['requester_comment'];
		$this->date_authorized->DbValue = $row['date_authorized'];
		$this->authorizer_name->DbValue = $row['authorizer_name'];
		$this->authorizer_action->DbValue = $row['authorizer_action'];
		$this->authorizer_comment->DbValue = $row['authorizer_comment'];
		$this->status->DbValue = $row['status'];
		$this->rep_date->DbValue = $row['rep_date'];
		$this->rep_name->DbValue = $row['rep_name'];
		$this->outward_datetime->DbValue = $row['outward_datetime'];
		$this->rep_action->DbValue = $row['rep_action'];
		$this->rep_comment->DbValue = $row['rep_comment'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// code
		// date
		// reference
		// staff_id
		// outward_location
		// delivery_point
		// name
		// organization
		// designation
		// department
		// item_description
		// driver_name
		// vehicle_no
		// requester_action
		// requester_comment
		// date_authorized
		// authorizer_name
		// authorizer_action
		// authorizer_comment
		// status
		// rep_date
		// rep_name
		// outward_datetime
		// rep_action
		// rep_comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 17);
		$this->date->ViewCustomAttributes = "";

		// reference
		$this->reference->ViewValue = $this->reference->CurrentValue;
		$this->reference->ViewCustomAttributes = "";

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

		// outward_location
		$this->outward_location->ViewValue = $this->outward_location->CurrentValue;
		$this->outward_location->ViewCustomAttributes = "";

		// delivery_point
		$this->delivery_point->ViewValue = $this->delivery_point->CurrentValue;
		$this->delivery_point->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		if (strval($this->name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->name->ViewValue = $this->name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->name->ViewValue = $this->name->CurrentValue;
			}
		} else {
			$this->name->ViewValue = NULL;
		}
		$this->name->ViewCustomAttributes = "";

		// organization
		$this->organization->ViewValue = $this->organization->CurrentValue;
		if (strval($this->organization->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->organization->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organization->ViewValue = $this->organization->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organization->ViewValue = $this->organization->CurrentValue;
			}
		} else {
			$this->organization->ViewValue = NULL;
		}
		$this->organization->ViewCustomAttributes = "";

		// designation
		$this->designation->ViewValue = $this->designation->CurrentValue;
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// item_description
		$this->item_description->ViewValue = $this->item_description->CurrentValue;
		$this->item_description->ViewCustomAttributes = "";

		// driver_name
		$this->driver_name->ViewValue = $this->driver_name->CurrentValue;
		$this->driver_name->ViewCustomAttributes = "";

		// vehicle_no
		$this->vehicle_no->ViewValue = $this->vehicle_no->CurrentValue;
		$this->vehicle_no->ViewCustomAttributes = "";

		// requester_action
		if (strval($this->requester_action->CurrentValue) <> "") {
			$this->requester_action->ViewValue = $this->requester_action->OptionCaption($this->requester_action->CurrentValue);
		} else {
			$this->requester_action->ViewValue = NULL;
		}
		$this->requester_action->ViewCustomAttributes = "";

		// requester_comment
		$this->requester_comment->ViewValue = $this->requester_comment->CurrentValue;
		$this->requester_comment->ViewCustomAttributes = "";

		// date_authorized
		$this->date_authorized->ViewValue = $this->date_authorized->CurrentValue;
		$this->date_authorized->ViewValue = ew_FormatDateTime($this->date_authorized->ViewValue, 17);
		$this->date_authorized->ViewCustomAttributes = "";

		// authorizer_name
		$this->authorizer_name->ViewValue = $this->authorizer_name->CurrentValue;
		if (strval($this->authorizer_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->authorizer_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->authorizer_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->authorizer_name->ViewValue = $this->authorizer_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->authorizer_name->ViewValue = $this->authorizer_name->CurrentValue;
			}
		} else {
			$this->authorizer_name->ViewValue = NULL;
		}
		$this->authorizer_name->ViewCustomAttributes = "";

		// authorizer_action
		if (strval($this->authorizer_action->CurrentValue) <> "") {
			$this->authorizer_action->ViewValue = $this->authorizer_action->OptionCaption($this->authorizer_action->CurrentValue);
		} else {
			$this->authorizer_action->ViewValue = NULL;
		}
		$this->authorizer_action->ViewCustomAttributes = "";

		// authorizer_comment
		$this->authorizer_comment->ViewValue = $this->authorizer_comment->CurrentValue;
		$this->authorizer_comment->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
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

		// rep_date
		$this->rep_date->ViewValue = $this->rep_date->CurrentValue;
		$this->rep_date->ViewValue = ew_FormatDateTime($this->rep_date->ViewValue, 17);
		$this->rep_date->ViewCustomAttributes = "";

		// rep_name
		$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
		if (strval($this->rep_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->rep_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->rep_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->rep_name->ViewValue = $this->rep_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
			}
		} else {
			$this->rep_name->ViewValue = NULL;
		}
		$this->rep_name->ViewCustomAttributes = "";

		// outward_datetime
		$this->outward_datetime->ViewValue = $this->outward_datetime->CurrentValue;
		$this->outward_datetime->ViewValue = ew_FormatDateTime($this->outward_datetime->ViewValue, 17);
		$this->outward_datetime->ViewCustomAttributes = "";

		// rep_action
		if (strval($this->rep_action->CurrentValue) <> "") {
			$this->rep_action->ViewValue = $this->rep_action->OptionCaption($this->rep_action->CurrentValue);
		} else {
			$this->rep_action->ViewValue = NULL;
		}
		$this->rep_action->ViewCustomAttributes = "";

		// rep_comment
		$this->rep_comment->ViewValue = $this->rep_comment->CurrentValue;
		$this->rep_comment->ViewCustomAttributes = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";
			$this->reference->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// outward_location
			$this->outward_location->LinkCustomAttributes = "";
			$this->outward_location->HrefValue = "";
			$this->outward_location->TooltipValue = "";

			// delivery_point
			$this->delivery_point->LinkCustomAttributes = "";
			$this->delivery_point->HrefValue = "";
			$this->delivery_point->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// organization
			$this->organization->LinkCustomAttributes = "";
			$this->organization->HrefValue = "";
			$this->organization->TooltipValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";
			$this->designation->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// item_description
			$this->item_description->LinkCustomAttributes = "";
			$this->item_description->HrefValue = "";
			$this->item_description->TooltipValue = "";

			// driver_name
			$this->driver_name->LinkCustomAttributes = "";
			$this->driver_name->HrefValue = "";
			$this->driver_name->TooltipValue = "";

			// vehicle_no
			$this->vehicle_no->LinkCustomAttributes = "";
			$this->vehicle_no->HrefValue = "";
			$this->vehicle_no->TooltipValue = "";

			// requester_action
			$this->requester_action->LinkCustomAttributes = "";
			$this->requester_action->HrefValue = "";
			$this->requester_action->TooltipValue = "";

			// requester_comment
			$this->requester_comment->LinkCustomAttributes = "";
			$this->requester_comment->HrefValue = "";
			$this->requester_comment->TooltipValue = "";

			// date_authorized
			$this->date_authorized->LinkCustomAttributes = "";
			$this->date_authorized->HrefValue = "";
			$this->date_authorized->TooltipValue = "";

			// authorizer_name
			$this->authorizer_name->LinkCustomAttributes = "";
			$this->authorizer_name->HrefValue = "";
			$this->authorizer_name->TooltipValue = "";

			// authorizer_action
			$this->authorizer_action->LinkCustomAttributes = "";
			$this->authorizer_action->HrefValue = "";
			$this->authorizer_action->TooltipValue = "";

			// authorizer_comment
			$this->authorizer_comment->LinkCustomAttributes = "";
			$this->authorizer_comment->HrefValue = "";
			$this->authorizer_comment->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// rep_date
			$this->rep_date->LinkCustomAttributes = "";
			$this->rep_date->HrefValue = "";
			$this->rep_date->TooltipValue = "";

			// rep_name
			$this->rep_name->LinkCustomAttributes = "";
			$this->rep_name->HrefValue = "";
			$this->rep_name->TooltipValue = "";

			// outward_datetime
			$this->outward_datetime->LinkCustomAttributes = "";
			$this->outward_datetime->HrefValue = "";
			$this->outward_datetime->TooltipValue = "";

			// rep_action
			$this->rep_action->LinkCustomAttributes = "";
			$this->rep_action->HrefValue = "";
			$this->rep_action->TooltipValue = "";

			// rep_comment
			$this->rep_comment->LinkCustomAttributes = "";
			$this->rep_comment->HrefValue = "";
			$this->rep_comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 17));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// reference
			$this->reference->EditAttrs["class"] = "form-control";
			$this->reference->EditCustomAttributes = "";
			$this->reference->EditValue = ew_HtmlEncode($this->reference->CurrentValue);
			$this->reference->PlaceHolder = ew_RemoveHtml($this->reference->FldCaption());

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

			// outward_location
			$this->outward_location->EditAttrs["class"] = "form-control";
			$this->outward_location->EditCustomAttributes = "";
			$this->outward_location->EditValue = ew_HtmlEncode($this->outward_location->CurrentValue);
			$this->outward_location->PlaceHolder = ew_RemoveHtml($this->outward_location->FldCaption());

			// delivery_point
			$this->delivery_point->EditAttrs["class"] = "form-control";
			$this->delivery_point->EditCustomAttributes = "";
			$this->delivery_point->EditValue = ew_HtmlEncode($this->delivery_point->CurrentValue);
			$this->delivery_point->PlaceHolder = ew_RemoveHtml($this->delivery_point->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			if (strval($this->name->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->name->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->name->EditValue = $this->name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
				}
			} else {
				$this->name->EditValue = NULL;
			}
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// organization
			$this->organization->EditAttrs["class"] = "form-control";
			$this->organization->EditCustomAttributes = "";
			$this->organization->EditValue = ew_HtmlEncode($this->organization->CurrentValue);
			if (strval($this->organization->CurrentValue) <> "") {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "";
			$this->organization->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->organization->EditValue = $this->organization->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->organization->EditValue = ew_HtmlEncode($this->organization->CurrentValue);
				}
			} else {
				$this->organization->EditValue = NULL;
			}
			$this->organization->PlaceHolder = ew_RemoveHtml($this->organization->FldCaption());

			// designation
			$this->designation->EditAttrs["class"] = "form-control";
			$this->designation->EditCustomAttributes = "";
			$this->designation->EditValue = ew_HtmlEncode($this->designation->CurrentValue);
			if (strval($this->designation->CurrentValue) <> "") {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
			$sWhereWrk = "";
			$this->designation->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->designation->EditValue = $this->designation->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->designation->EditValue = ew_HtmlEncode($this->designation->CurrentValue);
				}
			} else {
				$this->designation->EditValue = NULL;
			}
			$this->designation->PlaceHolder = ew_RemoveHtml($this->designation->FldCaption());

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

			// item_description
			$this->item_description->EditAttrs["class"] = "form-control";
			$this->item_description->EditCustomAttributes = "";
			$this->item_description->EditValue = ew_HtmlEncode($this->item_description->CurrentValue);
			$this->item_description->PlaceHolder = ew_RemoveHtml($this->item_description->FldCaption());

			// driver_name
			$this->driver_name->EditAttrs["class"] = "form-control";
			$this->driver_name->EditCustomAttributes = "";
			$this->driver_name->EditValue = ew_HtmlEncode($this->driver_name->CurrentValue);
			$this->driver_name->PlaceHolder = ew_RemoveHtml($this->driver_name->FldCaption());

			// vehicle_no
			$this->vehicle_no->EditAttrs["class"] = "form-control";
			$this->vehicle_no->EditCustomAttributes = "";
			$this->vehicle_no->EditValue = ew_HtmlEncode($this->vehicle_no->CurrentValue);
			$this->vehicle_no->PlaceHolder = ew_RemoveHtml($this->vehicle_no->FldCaption());

			// requester_action
			$this->requester_action->EditCustomAttributes = "";
			$this->requester_action->EditValue = $this->requester_action->Options(FALSE);

			// requester_comment
			$this->requester_comment->EditAttrs["class"] = "form-control";
			$this->requester_comment->EditCustomAttributes = "";
			$this->requester_comment->EditValue = ew_HtmlEncode($this->requester_comment->CurrentValue);
			$this->requester_comment->PlaceHolder = ew_RemoveHtml($this->requester_comment->FldCaption());

			// date_authorized
			$this->date_authorized->EditAttrs["class"] = "form-control";
			$this->date_authorized->EditCustomAttributes = "";
			$this->date_authorized->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_authorized->CurrentValue, 17));
			$this->date_authorized->PlaceHolder = ew_RemoveHtml($this->date_authorized->FldCaption());

			// authorizer_name
			$this->authorizer_name->EditAttrs["class"] = "form-control";
			$this->authorizer_name->EditCustomAttributes = "";
			$this->authorizer_name->EditValue = ew_HtmlEncode($this->authorizer_name->CurrentValue);
			if (strval($this->authorizer_name->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->authorizer_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->authorizer_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->authorizer_name->EditValue = $this->authorizer_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->authorizer_name->EditValue = ew_HtmlEncode($this->authorizer_name->CurrentValue);
				}
			} else {
				$this->authorizer_name->EditValue = NULL;
			}
			$this->authorizer_name->PlaceHolder = ew_RemoveHtml($this->authorizer_name->FldCaption());

			// authorizer_action
			$this->authorizer_action->EditCustomAttributes = "";
			$this->authorizer_action->EditValue = $this->authorizer_action->Options(FALSE);

			// authorizer_comment
			$this->authorizer_comment->EditAttrs["class"] = "form-control";
			$this->authorizer_comment->EditCustomAttributes = "";
			$this->authorizer_comment->EditValue = ew_HtmlEncode($this->authorizer_comment->CurrentValue);
			$this->authorizer_comment->PlaceHolder = ew_RemoveHtml($this->authorizer_comment->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `status_ssf`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// rep_date
			$this->rep_date->EditAttrs["class"] = "form-control";
			$this->rep_date->EditCustomAttributes = "";
			$this->rep_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->rep_date->CurrentValue, 17));
			$this->rep_date->PlaceHolder = ew_RemoveHtml($this->rep_date->FldCaption());

			// rep_name
			$this->rep_name->EditAttrs["class"] = "form-control";
			$this->rep_name->EditCustomAttributes = "";
			$this->rep_name->EditValue = ew_HtmlEncode($this->rep_name->CurrentValue);
			if (strval($this->rep_name->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->rep_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->rep_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->rep_name->EditValue = $this->rep_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->rep_name->EditValue = ew_HtmlEncode($this->rep_name->CurrentValue);
				}
			} else {
				$this->rep_name->EditValue = NULL;
			}
			$this->rep_name->PlaceHolder = ew_RemoveHtml($this->rep_name->FldCaption());

			// outward_datetime
			$this->outward_datetime->EditAttrs["class"] = "form-control";
			$this->outward_datetime->EditCustomAttributes = "";
			$this->outward_datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->outward_datetime->CurrentValue, 17));
			$this->outward_datetime->PlaceHolder = ew_RemoveHtml($this->outward_datetime->FldCaption());

			// rep_action
			$this->rep_action->EditCustomAttributes = "";
			$this->rep_action->EditValue = $this->rep_action->Options(FALSE);

			// rep_comment
			$this->rep_comment->EditAttrs["class"] = "form-control";
			$this->rep_comment->EditCustomAttributes = "";
			$this->rep_comment->EditValue = ew_HtmlEncode($this->rep_comment->CurrentValue);
			$this->rep_comment->PlaceHolder = ew_RemoveHtml($this->rep_comment->FldCaption());

			// Edit refer script
			// date

			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// outward_location
			$this->outward_location->LinkCustomAttributes = "";
			$this->outward_location->HrefValue = "";

			// delivery_point
			$this->delivery_point->LinkCustomAttributes = "";
			$this->delivery_point->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// organization
			$this->organization->LinkCustomAttributes = "";
			$this->organization->HrefValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// item_description
			$this->item_description->LinkCustomAttributes = "";
			$this->item_description->HrefValue = "";

			// driver_name
			$this->driver_name->LinkCustomAttributes = "";
			$this->driver_name->HrefValue = "";

			// vehicle_no
			$this->vehicle_no->LinkCustomAttributes = "";
			$this->vehicle_no->HrefValue = "";

			// requester_action
			$this->requester_action->LinkCustomAttributes = "";
			$this->requester_action->HrefValue = "";

			// requester_comment
			$this->requester_comment->LinkCustomAttributes = "";
			$this->requester_comment->HrefValue = "";

			// date_authorized
			$this->date_authorized->LinkCustomAttributes = "";
			$this->date_authorized->HrefValue = "";

			// authorizer_name
			$this->authorizer_name->LinkCustomAttributes = "";
			$this->authorizer_name->HrefValue = "";

			// authorizer_action
			$this->authorizer_action->LinkCustomAttributes = "";
			$this->authorizer_action->HrefValue = "";

			// authorizer_comment
			$this->authorizer_comment->LinkCustomAttributes = "";
			$this->authorizer_comment->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// rep_date
			$this->rep_date->LinkCustomAttributes = "";
			$this->rep_date->HrefValue = "";

			// rep_name
			$this->rep_name->LinkCustomAttributes = "";
			$this->rep_name->HrefValue = "";

			// outward_datetime
			$this->outward_datetime->LinkCustomAttributes = "";
			$this->outward_datetime->HrefValue = "";

			// rep_action
			$this->rep_action->LinkCustomAttributes = "";
			$this->rep_action->HrefValue = "";

			// rep_comment
			$this->rep_comment->LinkCustomAttributes = "";
			$this->rep_comment->HrefValue = "";
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
		if (!$this->reference->FldIsDetailKey && !is_null($this->reference->FormValue) && $this->reference->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reference->FldCaption(), $this->reference->ReqErrMsg));
		}
		if (!$this->outward_location->FldIsDetailKey && !is_null($this->outward_location->FormValue) && $this->outward_location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->outward_location->FldCaption(), $this->outward_location->ReqErrMsg));
		}
		if (!$this->delivery_point->FldIsDetailKey && !is_null($this->delivery_point->FormValue) && $this->delivery_point->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->delivery_point->FldCaption(), $this->delivery_point->ReqErrMsg));
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->organization->FldIsDetailKey && !is_null($this->organization->FormValue) && $this->organization->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->organization->FldCaption(), $this->organization->ReqErrMsg));
		}
		if (!$this->designation->FldIsDetailKey && !is_null($this->designation->FormValue) && $this->designation->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->designation->FldCaption(), $this->designation->ReqErrMsg));
		}
		if (!$this->department->FldIsDetailKey && !is_null($this->department->FormValue) && $this->department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->department->FldCaption(), $this->department->ReqErrMsg));
		}
		if (!$this->item_description->FldIsDetailKey && !is_null($this->item_description->FormValue) && $this->item_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->item_description->FldCaption(), $this->item_description->ReqErrMsg));
		}
		if (!$this->driver_name->FldIsDetailKey && !is_null($this->driver_name->FormValue) && $this->driver_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->driver_name->FldCaption(), $this->driver_name->ReqErrMsg));
		}
		if (!$this->vehicle_no->FldIsDetailKey && !is_null($this->vehicle_no->FormValue) && $this->vehicle_no->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vehicle_no->FldCaption(), $this->vehicle_no->ReqErrMsg));
		}
		if ($this->requester_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->requester_action->FldCaption(), $this->requester_action->ReqErrMsg));
		}
		if (!$this->requester_comment->FldIsDetailKey && !is_null($this->requester_comment->FormValue) && $this->requester_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->requester_comment->FldCaption(), $this->requester_comment->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->rep_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->rep_date->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->outward_datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->outward_datetime->FldErrMsg());
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
			$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 17), NULL, $this->date->ReadOnly);

			// reference
			$this->reference->SetDbValueDef($rsnew, $this->reference->CurrentValue, NULL, $this->reference->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, $this->staff_id->ReadOnly);

			// outward_location
			$this->outward_location->SetDbValueDef($rsnew, $this->outward_location->CurrentValue, NULL, $this->outward_location->ReadOnly);

			// delivery_point
			$this->delivery_point->SetDbValueDef($rsnew, $this->delivery_point->CurrentValue, NULL, $this->delivery_point->ReadOnly);

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, $this->name->ReadOnly);

			// organization
			$this->organization->SetDbValueDef($rsnew, $this->organization->CurrentValue, NULL, $this->organization->ReadOnly);

			// designation
			$this->designation->SetDbValueDef($rsnew, $this->designation->CurrentValue, NULL, $this->designation->ReadOnly);

			// department
			$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, $this->department->ReadOnly);

			// item_description
			$this->item_description->SetDbValueDef($rsnew, $this->item_description->CurrentValue, NULL, $this->item_description->ReadOnly);

			// driver_name
			$this->driver_name->SetDbValueDef($rsnew, $this->driver_name->CurrentValue, NULL, $this->driver_name->ReadOnly);

			// vehicle_no
			$this->vehicle_no->SetDbValueDef($rsnew, $this->vehicle_no->CurrentValue, NULL, $this->vehicle_no->ReadOnly);

			// requester_action
			$this->requester_action->SetDbValueDef($rsnew, $this->requester_action->CurrentValue, NULL, $this->requester_action->ReadOnly);

			// requester_comment
			$this->requester_comment->SetDbValueDef($rsnew, $this->requester_comment->CurrentValue, NULL, $this->requester_comment->ReadOnly);

			// date_authorized
			$this->date_authorized->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_authorized->CurrentValue, 17), NULL, $this->date_authorized->ReadOnly);

			// authorizer_name
			$this->authorizer_name->SetDbValueDef($rsnew, $this->authorizer_name->CurrentValue, NULL, $this->authorizer_name->ReadOnly);

			// authorizer_action
			$this->authorizer_action->SetDbValueDef($rsnew, $this->authorizer_action->CurrentValue, NULL, $this->authorizer_action->ReadOnly);

			// authorizer_comment
			$this->authorizer_comment->SetDbValueDef($rsnew, $this->authorizer_comment->CurrentValue, NULL, $this->authorizer_comment->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// rep_date
			$this->rep_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->rep_date->CurrentValue, 17), NULL, $this->rep_date->ReadOnly);

			// rep_name
			$this->rep_name->SetDbValueDef($rsnew, $this->rep_name->CurrentValue, NULL, $this->rep_name->ReadOnly);

			// outward_datetime
			$this->outward_datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->outward_datetime->CurrentValue, 17), NULL, $this->outward_datetime->ReadOnly);

			// rep_action
			$this->rep_action->SetDbValueDef($rsnew, $this->rep_action->CurrentValue, NULL, $this->rep_action->ReadOnly);

			// rep_comment
			$this->rep_comment->SetDbValueDef($rsnew, $this->rep_comment->CurrentValue, NULL, $this->rep_comment->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("requisition_modulelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$pages->Add(3);
		$this->MultiPages = $pages;
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
		case "x_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_organization":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id` AS `LinkFld`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_designation":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
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
		case "x_authorizer_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_rep_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
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
		case "x_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->name) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->name) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_organization":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld` FROM `branch`";
			$sWhereWrk = "`branch_name` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_designation":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld` FROM `designation`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
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
		case "x_authorizer_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->authorizer_name) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->authorizer_name) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_rep_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->rep_name) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->rep_name) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($requisition_module_edit)) $requisition_module_edit = new crequisition_module_edit();

// Page init
$requisition_module_edit->Page_Init();

// Page main
$requisition_module_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$requisition_module_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = frequisition_moduleedit = new ew_Form("frequisition_moduleedit", "edit");

// Validate form
frequisition_moduleedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->date->FldCaption(), $requisition_module->date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reference");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->reference->FldCaption(), $requisition_module->reference->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_outward_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->outward_location->FldCaption(), $requisition_module->outward_location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_delivery_point");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->delivery_point->FldCaption(), $requisition_module->delivery_point->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->name->FldCaption(), $requisition_module->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_organization");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->organization->FldCaption(), $requisition_module->organization->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_designation");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->designation->FldCaption(), $requisition_module->designation->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->department->FldCaption(), $requisition_module->department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_item_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->item_description->FldCaption(), $requisition_module->item_description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_driver_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->driver_name->FldCaption(), $requisition_module->driver_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_vehicle_no");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->vehicle_no->FldCaption(), $requisition_module->vehicle_no->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_requester_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->requester_action->FldCaption(), $requisition_module->requester_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_requester_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->requester_comment->FldCaption(), $requisition_module->requester_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rep_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($requisition_module->rep_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_outward_datetime");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($requisition_module->outward_datetime->FldErrMsg()) ?>");

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
frequisition_moduleedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	var currlevel = "<?php echo CurrentUserLevel() ?>";	
 	var $row = $(this).fields();
  	if ((currlevel == 13) && ($row["date_authorized"].value() == ''))
 	return this.OnError($row["date_authorized"], "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->date_authorized->FldCaption(), "The Date Authorized Field cannot be empty!")) ?>"); 	
 	if ((currlevel == 13) && ($row["authorizer_action"].value() == ''))
 	return this.OnError($row["authorizer_action"], "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->authorizer_action->FldCaption(), "The Authorizer Action Field cannot be empty!")) ?>"); 	
 	if ((currlevel == 13) && ($row["authorizer_comment"].value() == ''))
 	return this.OnError($row["authorizer_comment"], "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->authorizer_comment->FldCaption(), "The Authorizer Comment Field cannot be empty!")) ?>"); 	
 	if ((currlevel == 9) && ($row["rep_action"].value() == ''))
 	return this.OnError($row["rep_action"], "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->rep_action->FldCaption(), "The Rep Action Field cannot be empty!")) ?>"); 	
 	if ((currlevel == 9) && ($row["rep_comment"].value() == ''))
 	return this.OnError($row["rep_comment"], "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->rep_comment->FldCaption(), "The Rep  Comment Field cannot be empty!")) ?>");
 	if ((currlevel == 9) && ($row["outward_datetime"].value() == ''))
 	return this.OnError($row["outward_datetime"], "<?php echo ew_JsEncode2(str_replace("%s", $requisition_module->outward_datetime->FldCaption(), "The Outward Datetime Field cannot be empty!")) ?>"); 	
 	return true;
 }

// Use JavaScript validation or not
frequisition_moduleedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
frequisition_moduleedit.MultiPage = new ew_MultiPage("frequisition_moduleedit");

// Dynamic selection lists
frequisition_moduleedit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_moduleedit.Lists["x_staff_id"].Data = "<?php echo $requisition_module_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->staff_id->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_moduleedit.Lists["x_name"].Data = "<?php echo $requisition_module_edit->name->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->name->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_organization"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
frequisition_moduleedit.Lists["x_organization"].Data = "<?php echo $requisition_module_edit->organization->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_organization"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->organization->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
frequisition_moduleedit.Lists["x_designation"].Data = "<?php echo $requisition_module_edit->designation->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_designation"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->designation->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
frequisition_moduleedit.Lists["x_department"].Data = "<?php echo $requisition_module_edit->department->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->department->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_requester_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_moduleedit.Lists["x_requester_action"].Options = <?php echo json_encode($requisition_module_edit->requester_action->Options()) ?>;
frequisition_moduleedit.Lists["x_authorizer_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_moduleedit.Lists["x_authorizer_name"].Data = "<?php echo $requisition_module_edit->authorizer_name->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_authorizer_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->authorizer_name->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_authorizer_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_moduleedit.Lists["x_authorizer_action"].Options = <?php echo json_encode($requisition_module_edit->authorizer_action->Options()) ?>;
frequisition_moduleedit.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status_ssf"};
frequisition_moduleedit.Lists["x_status"].Data = "<?php echo $requisition_module_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.Lists["x_rep_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_moduleedit.Lists["x_rep_name"].Data = "<?php echo $requisition_module_edit->rep_name->LookupFilterQuery(FALSE, "edit") ?>";
frequisition_moduleedit.AutoSuggests["x_rep_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_module_edit->rep_name->LookupFilterQuery(TRUE, "edit"))) ?>;
frequisition_moduleedit.Lists["x_rep_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_moduleedit.Lists["x_rep_action"].Options = <?php echo json_encode($requisition_module_edit->rep_action->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $requisition_module_edit->ShowPageHeader(); ?>
<?php
$requisition_module_edit->ShowMessage();
?>
<?php if (!$requisition_module_edit->IsModal) { ?>
<?php if ($requisition_module->CurrentAction <> "F") { // Confirm page ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($requisition_module_edit->Pager)) $requisition_module_edit->Pager = new cPrevNextPager($requisition_module_edit->StartRec, $requisition_module_edit->DisplayRecs, $requisition_module_edit->TotalRecs, $requisition_module_edit->AutoHidePager) ?>
<?php if ($requisition_module_edit->Pager->RecordCount > 0 && $requisition_module_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($requisition_module_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $requisition_module_edit->PageUrl() ?>start=<?php echo $requisition_module_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($requisition_module_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $requisition_module_edit->PageUrl() ?>start=<?php echo $requisition_module_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $requisition_module_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($requisition_module_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $requisition_module_edit->PageUrl() ?>start=<?php echo $requisition_module_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($requisition_module_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $requisition_module_edit->PageUrl() ?>start=<?php echo $requisition_module_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $requisition_module_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="frequisition_moduleedit" id="frequisition_moduleedit" class="<?php echo $requisition_module_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($requisition_module_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $requisition_module_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="requisition_module">
<?php if ($requisition_module->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_edit" id="a_edit" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($requisition_module_edit->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="requisition_module_edit"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $requisition_module_edit->MultiPages->NavStyle() ?>">
		<li<?php echo $requisition_module_edit->MultiPages->TabStyle("1") ?>><a href="#tab_requisition_module1" data-toggle="tab"><?php echo $requisition_module->PageCaption(1) ?></a></li>
		<li<?php echo $requisition_module_edit->MultiPages->TabStyle("2") ?>><a href="#tab_requisition_module2" data-toggle="tab"><?php echo $requisition_module->PageCaption(2) ?></a></li>
		<li<?php echo $requisition_module_edit->MultiPages->TabStyle("3") ?>><a href="#tab_requisition_module3" data-toggle="tab"><?php echo $requisition_module->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $requisition_module_edit->MultiPages->PageStyle("1") ?>" id="tab_requisition_module1"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($requisition_module->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_requisition_module_date" for="x_date" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->date->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_date">
<input type="text" data-table="requisition_module" data-field="x_date" data-page="1" data-format="17" name="x_date" id="x_date" size="30" placeholder="<?php echo ew_HtmlEncode($requisition_module->date->getPlaceHolder()) ?>" value="<?php echo $requisition_module->date->EditValue ?>"<?php echo $requisition_module->date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_requisition_module_date">
<span<?php echo $requisition_module->date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_date" data-page="1" name="x_date" id="x_date" value="<?php echo ew_HtmlEncode($requisition_module->date->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->reference->Visible) { // reference ?>
	<div id="r_reference" class="form-group">
		<label id="elh_requisition_module_reference" for="x_reference" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->reference->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->reference->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_reference">
<input type="text" data-table="requisition_module" data-field="x_reference" data-page="1" name="x_reference" id="x_reference" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->reference->getPlaceHolder()) ?>" value="<?php echo $requisition_module->reference->EditValue ?>"<?php echo $requisition_module->reference->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_requisition_module_reference">
<span<?php echo $requisition_module->reference->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->reference->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_reference" data-page="1" name="x_reference" id="x_reference" value="<?php echo ew_HtmlEncode($requisition_module->reference->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->reference->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_requisition_module_staff_id" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->staff_id->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->staff_id->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_staff_id">
<?php
$wrkonchange = trim(" " . @$requisition_module->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $requisition_module->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($requisition_module->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->staff_id->getPlaceHolder()) ?>"<?php echo $requisition_module->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $requisition_module->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($requisition_module->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_staff_id">
<span<?php echo $requisition_module->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($requisition_module->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->outward_location->Visible) { // outward_location ?>
	<div id="r_outward_location" class="form-group">
		<label id="elh_requisition_module_outward_location" for="x_outward_location" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->outward_location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->outward_location->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_outward_location">
<textarea data-table="requisition_module" data-field="x_outward_location" data-page="1" name="x_outward_location" id="x_outward_location" cols="30" rows="1" placeholder="<?php echo ew_HtmlEncode($requisition_module->outward_location->getPlaceHolder()) ?>"<?php echo $requisition_module->outward_location->EditAttributes() ?>><?php echo $requisition_module->outward_location->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_requisition_module_outward_location">
<span<?php echo $requisition_module->outward_location->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->outward_location->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_outward_location" data-page="1" name="x_outward_location" id="x_outward_location" value="<?php echo ew_HtmlEncode($requisition_module->outward_location->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->outward_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->delivery_point->Visible) { // delivery_point ?>
	<div id="r_delivery_point" class="form-group">
		<label id="elh_requisition_module_delivery_point" for="x_delivery_point" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->delivery_point->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->delivery_point->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_delivery_point">
<textarea data-table="requisition_module" data-field="x_delivery_point" data-page="1" name="x_delivery_point" id="x_delivery_point" cols="30" rows="1" placeholder="<?php echo ew_HtmlEncode($requisition_module->delivery_point->getPlaceHolder()) ?>"<?php echo $requisition_module->delivery_point->EditAttributes() ?>><?php echo $requisition_module->delivery_point->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_requisition_module_delivery_point">
<span<?php echo $requisition_module->delivery_point->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->delivery_point->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_delivery_point" data-page="1" name="x_delivery_point" id="x_delivery_point" value="<?php echo ew_HtmlEncode($requisition_module->delivery_point->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->delivery_point->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_requisition_module_name" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->name->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_name">
<?php
$wrkonchange = trim(" " . @$requisition_module->name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->name->EditAttrs["onchange"] = "";
?>
<span id="as_x_name" style="white-space: nowrap; z-index: 8930">
	<input type="text" name="sv_x_name" id="sv_x_name" value="<?php echo $requisition_module->name->EditValue ?>" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($requisition_module->name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->name->getPlaceHolder()) ?>"<?php echo $requisition_module->name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_name" data-page="1" data-value-separator="<?php echo $requisition_module->name->DisplayValueSeparatorAttribute() ?>" name="x_name" id="x_name" value="<?php echo ew_HtmlEncode($requisition_module->name->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_name","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_name">
<span<?php echo $requisition_module->name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_name" data-page="1" name="x_name" id="x_name" value="<?php echo ew_HtmlEncode($requisition_module->name->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->organization->Visible) { // organization ?>
	<div id="r_organization" class="form-group">
		<label id="elh_requisition_module_organization" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->organization->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->organization->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_organization">
<?php
$wrkonchange = trim(" " . @$requisition_module->organization->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->organization->EditAttrs["onchange"] = "";
?>
<span id="as_x_organization" style="white-space: nowrap; z-index: 8920">
	<input type="text" name="sv_x_organization" id="sv_x_organization" value="<?php echo $requisition_module->organization->EditValue ?>" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($requisition_module->organization->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->organization->getPlaceHolder()) ?>"<?php echo $requisition_module->organization->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_organization" data-page="1" data-value-separator="<?php echo $requisition_module->organization->DisplayValueSeparatorAttribute() ?>" name="x_organization" id="x_organization" value="<?php echo ew_HtmlEncode($requisition_module->organization->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_organization","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_organization">
<span<?php echo $requisition_module->organization->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->organization->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_organization" data-page="1" name="x_organization" id="x_organization" value="<?php echo ew_HtmlEncode($requisition_module->organization->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->organization->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->designation->Visible) { // designation ?>
	<div id="r_designation" class="form-group">
		<label id="elh_requisition_module_designation" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->designation->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->designation->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_designation">
<?php
$wrkonchange = trim(" " . @$requisition_module->designation->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->designation->EditAttrs["onchange"] = "";
?>
<span id="as_x_designation" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_designation" id="sv_x_designation" value="<?php echo $requisition_module->designation->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->designation->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->designation->getPlaceHolder()) ?>"<?php echo $requisition_module->designation->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_designation" data-page="1" data-value-separator="<?php echo $requisition_module->designation->DisplayValueSeparatorAttribute() ?>" name="x_designation" id="x_designation" value="<?php echo ew_HtmlEncode($requisition_module->designation->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_designation","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_designation">
<span<?php echo $requisition_module->designation->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->designation->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_designation" data-page="1" name="x_designation" id="x_designation" value="<?php echo ew_HtmlEncode($requisition_module->designation->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->designation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_requisition_module_department" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->department->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_department">
<?php
$wrkonchange = trim(" " . @$requisition_module->department->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->department->EditAttrs["onchange"] = "";
?>
<span id="as_x_department" style="white-space: nowrap; z-index: 8900">
	<input type="text" name="sv_x_department" id="sv_x_department" value="<?php echo $requisition_module->department->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->department->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->department->getPlaceHolder()) ?>"<?php echo $requisition_module->department->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_department" data-page="1" data-value-separator="<?php echo $requisition_module->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($requisition_module->department->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_department","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_department">
<span<?php echo $requisition_module->department->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->department->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_department" data-page="1" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($requisition_module->department->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->item_description->Visible) { // item_description ?>
	<div id="r_item_description" class="form-group">
		<label id="elh_requisition_module_item_description" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->item_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->item_description->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_item_description">
<?php ew_AppendClass($requisition_module->item_description->EditAttrs["class"], "editor"); ?>
<textarea data-table="requisition_module" data-field="x_item_description" data-page="1" name="x_item_description" id="x_item_description" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($requisition_module->item_description->getPlaceHolder()) ?>"<?php echo $requisition_module->item_description->EditAttributes() ?>><?php echo $requisition_module->item_description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("frequisition_moduleedit", "x_item_description", 30, 4, <?php echo ($requisition_module->item_description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_item_description">
<span<?php echo $requisition_module->item_description->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->item_description->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_item_description" data-page="1" name="x_item_description" id="x_item_description" value="<?php echo ew_HtmlEncode($requisition_module->item_description->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->item_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->driver_name->Visible) { // driver_name ?>
	<div id="r_driver_name" class="form-group">
		<label id="elh_requisition_module_driver_name" for="x_driver_name" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->driver_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->driver_name->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_driver_name">
<input type="text" data-table="requisition_module" data-field="x_driver_name" data-page="1" name="x_driver_name" id="x_driver_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->driver_name->getPlaceHolder()) ?>" value="<?php echo $requisition_module->driver_name->EditValue ?>"<?php echo $requisition_module->driver_name->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_requisition_module_driver_name">
<span<?php echo $requisition_module->driver_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->driver_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_driver_name" data-page="1" name="x_driver_name" id="x_driver_name" value="<?php echo ew_HtmlEncode($requisition_module->driver_name->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->driver_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->vehicle_no->Visible) { // vehicle_no ?>
	<div id="r_vehicle_no" class="form-group">
		<label id="elh_requisition_module_vehicle_no" for="x_vehicle_no" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->vehicle_no->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->vehicle_no->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_vehicle_no">
<input type="text" data-table="requisition_module" data-field="x_vehicle_no" data-page="1" name="x_vehicle_no" id="x_vehicle_no" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->vehicle_no->getPlaceHolder()) ?>" value="<?php echo $requisition_module->vehicle_no->EditValue ?>"<?php echo $requisition_module->vehicle_no->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_requisition_module_vehicle_no">
<span<?php echo $requisition_module->vehicle_no->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->vehicle_no->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_vehicle_no" data-page="1" name="x_vehicle_no" id="x_vehicle_no" value="<?php echo ew_HtmlEncode($requisition_module->vehicle_no->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->vehicle_no->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->requester_action->Visible) { // requester_action ?>
	<div id="r_requester_action" class="form-group">
		<label id="elh_requisition_module_requester_action" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->requester_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->requester_action->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_requester_action">
<div id="tp_x_requester_action" class="ewTemplate"><input type="radio" data-table="requisition_module" data-field="x_requester_action" data-page="1" data-value-separator="<?php echo $requisition_module->requester_action->DisplayValueSeparatorAttribute() ?>" name="x_requester_action" id="x_requester_action" value="{value}"<?php echo $requisition_module->requester_action->EditAttributes() ?>></div>
<div id="dsl_x_requester_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $requisition_module->requester_action->RadioButtonListHtml(FALSE, "x_requester_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_requisition_module_requester_action">
<span<?php echo $requisition_module->requester_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->requester_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_requester_action" data-page="1" name="x_requester_action" id="x_requester_action" value="<?php echo ew_HtmlEncode($requisition_module->requester_action->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->requester_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->requester_comment->Visible) { // requester_comment ?>
	<div id="r_requester_comment" class="form-group">
		<label id="elh_requisition_module_requester_comment" for="x_requester_comment" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->requester_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->requester_comment->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_requester_comment">
<textarea data-table="requisition_module" data-field="x_requester_comment" data-page="1" name="x_requester_comment" id="x_requester_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($requisition_module->requester_comment->getPlaceHolder()) ?>"<?php echo $requisition_module->requester_comment->EditAttributes() ?>><?php echo $requisition_module->requester_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_requisition_module_requester_comment">
<span<?php echo $requisition_module->requester_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->requester_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_requester_comment" data-page="1" name="x_requester_comment" id="x_requester_comment" value="<?php echo ew_HtmlEncode($requisition_module->requester_comment->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->requester_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $requisition_module_edit->MultiPages->PageStyle("2") ?>" id="tab_requisition_module2"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($requisition_module->date_authorized->Visible) { // date_authorized ?>
	<div id="r_date_authorized" class="form-group">
		<label id="elh_requisition_module_date_authorized" for="x_date_authorized" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->date_authorized->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->date_authorized->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_date_authorized">
<input type="text" data-table="requisition_module" data-field="x_date_authorized" data-page="2" data-format="17" name="x_date_authorized" id="x_date_authorized" size="30" placeholder="<?php echo ew_HtmlEncode($requisition_module->date_authorized->getPlaceHolder()) ?>" value="<?php echo $requisition_module->date_authorized->EditValue ?>"<?php echo $requisition_module->date_authorized->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_requisition_module_date_authorized">
<span<?php echo $requisition_module->date_authorized->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->date_authorized->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_date_authorized" data-page="2" name="x_date_authorized" id="x_date_authorized" value="<?php echo ew_HtmlEncode($requisition_module->date_authorized->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->date_authorized->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->authorizer_name->Visible) { // authorizer_name ?>
	<div id="r_authorizer_name" class="form-group">
		<label id="elh_requisition_module_authorizer_name" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->authorizer_name->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->authorizer_name->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_authorizer_name">
<?php
$wrkonchange = trim(" " . @$requisition_module->authorizer_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->authorizer_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_authorizer_name" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_authorizer_name" id="sv_x_authorizer_name" value="<?php echo $requisition_module->authorizer_name->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->authorizer_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->authorizer_name->getPlaceHolder()) ?>"<?php echo $requisition_module->authorizer_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_authorizer_name" data-page="2" data-value-separator="<?php echo $requisition_module->authorizer_name->DisplayValueSeparatorAttribute() ?>" name="x_authorizer_name" id="x_authorizer_name" value="<?php echo ew_HtmlEncode($requisition_module->authorizer_name->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_authorizer_name","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_authorizer_name">
<span<?php echo $requisition_module->authorizer_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->authorizer_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_authorizer_name" data-page="2" name="x_authorizer_name" id="x_authorizer_name" value="<?php echo ew_HtmlEncode($requisition_module->authorizer_name->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->authorizer_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->authorizer_action->Visible) { // authorizer_action ?>
	<div id="r_authorizer_action" class="form-group">
		<label id="elh_requisition_module_authorizer_action" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->authorizer_action->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->authorizer_action->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_authorizer_action">
<div id="tp_x_authorizer_action" class="ewTemplate"><input type="radio" data-table="requisition_module" data-field="x_authorizer_action" data-page="2" data-value-separator="<?php echo $requisition_module->authorizer_action->DisplayValueSeparatorAttribute() ?>" name="x_authorizer_action" id="x_authorizer_action" value="{value}"<?php echo $requisition_module->authorizer_action->EditAttributes() ?>></div>
<div id="dsl_x_authorizer_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $requisition_module->authorizer_action->RadioButtonListHtml(FALSE, "x_authorizer_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_requisition_module_authorizer_action">
<span<?php echo $requisition_module->authorizer_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->authorizer_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_authorizer_action" data-page="2" name="x_authorizer_action" id="x_authorizer_action" value="<?php echo ew_HtmlEncode($requisition_module->authorizer_action->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->authorizer_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->authorizer_comment->Visible) { // authorizer_comment ?>
	<div id="r_authorizer_comment" class="form-group">
		<label id="elh_requisition_module_authorizer_comment" for="x_authorizer_comment" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->authorizer_comment->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->authorizer_comment->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_authorizer_comment">
<textarea data-table="requisition_module" data-field="x_authorizer_comment" data-page="2" name="x_authorizer_comment" id="x_authorizer_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($requisition_module->authorizer_comment->getPlaceHolder()) ?>"<?php echo $requisition_module->authorizer_comment->EditAttributes() ?>><?php echo $requisition_module->authorizer_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_requisition_module_authorizer_comment">
<span<?php echo $requisition_module->authorizer_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->authorizer_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_authorizer_comment" data-page="2" name="x_authorizer_comment" id="x_authorizer_comment" value="<?php echo ew_HtmlEncode($requisition_module->authorizer_comment->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->authorizer_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_requisition_module_status" for="x_status" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->status->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->status->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_status">
<select data-table="requisition_module" data-field="x_status" data-page="2" data-value-separator="<?php echo $requisition_module->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $requisition_module->status->EditAttributes() ?>>
<?php echo $requisition_module->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php } else { ?>
<span id="el_requisition_module_status">
<span<?php echo $requisition_module->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_status" data-page="2" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($requisition_module->status->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $requisition_module_edit->MultiPages->PageStyle("3") ?>" id="tab_requisition_module3"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($requisition_module->rep_date->Visible) { // rep_date ?>
	<div id="r_rep_date" class="form-group">
		<label id="elh_requisition_module_rep_date" for="x_rep_date" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->rep_date->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->rep_date->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_rep_date">
<input type="text" data-table="requisition_module" data-field="x_rep_date" data-page="3" data-format="17" name="x_rep_date" id="x_rep_date" size="30" placeholder="<?php echo ew_HtmlEncode($requisition_module->rep_date->getPlaceHolder()) ?>" value="<?php echo $requisition_module->rep_date->EditValue ?>"<?php echo $requisition_module->rep_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_requisition_module_rep_date">
<span<?php echo $requisition_module->rep_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->rep_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_rep_date" data-page="3" name="x_rep_date" id="x_rep_date" value="<?php echo ew_HtmlEncode($requisition_module->rep_date->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->rep_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->rep_name->Visible) { // rep_name ?>
	<div id="r_rep_name" class="form-group">
		<label id="elh_requisition_module_rep_name" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->rep_name->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->rep_name->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_rep_name">
<?php
$wrkonchange = trim(" " . @$requisition_module->rep_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$requisition_module->rep_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_rep_name" style="white-space: nowrap; z-index: 8780">
	<input type="text" name="sv_x_rep_name" id="sv_x_rep_name" value="<?php echo $requisition_module->rep_name->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($requisition_module->rep_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($requisition_module->rep_name->getPlaceHolder()) ?>"<?php echo $requisition_module->rep_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_rep_name" data-page="3" data-value-separator="<?php echo $requisition_module->rep_name->DisplayValueSeparatorAttribute() ?>" name="x_rep_name" id="x_rep_name" value="<?php echo ew_HtmlEncode($requisition_module->rep_name->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
frequisition_moduleedit.CreateAutoSuggest({"id":"x_rep_name","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_requisition_module_rep_name">
<span<?php echo $requisition_module->rep_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->rep_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_rep_name" data-page="3" name="x_rep_name" id="x_rep_name" value="<?php echo ew_HtmlEncode($requisition_module->rep_name->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->rep_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->outward_datetime->Visible) { // outward_datetime ?>
	<div id="r_outward_datetime" class="form-group">
		<label id="elh_requisition_module_outward_datetime" for="x_outward_datetime" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->outward_datetime->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->outward_datetime->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_outward_datetime">
<input type="text" data-table="requisition_module" data-field="x_outward_datetime" data-page="3" data-format="17" name="x_outward_datetime" id="x_outward_datetime" placeholder="<?php echo ew_HtmlEncode($requisition_module->outward_datetime->getPlaceHolder()) ?>" value="<?php echo $requisition_module->outward_datetime->EditValue ?>"<?php echo $requisition_module->outward_datetime->EditAttributes() ?>>
<?php if (!$requisition_module->outward_datetime->ReadOnly && !$requisition_module->outward_datetime->Disabled && !isset($requisition_module->outward_datetime->EditAttrs["readonly"]) && !isset($requisition_module->outward_datetime->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("frequisition_moduleedit", "x_outward_datetime", {"ignoreReadonly":true,"useCurrent":false,"format":17});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_requisition_module_outward_datetime">
<span<?php echo $requisition_module->outward_datetime->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->outward_datetime->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_outward_datetime" data-page="3" name="x_outward_datetime" id="x_outward_datetime" value="<?php echo ew_HtmlEncode($requisition_module->outward_datetime->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->outward_datetime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->rep_action->Visible) { // rep_action ?>
	<div id="r_rep_action" class="form-group">
		<label id="elh_requisition_module_rep_action" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->rep_action->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->rep_action->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_rep_action">
<div id="tp_x_rep_action" class="ewTemplate"><input type="radio" data-table="requisition_module" data-field="x_rep_action" data-page="3" data-value-separator="<?php echo $requisition_module->rep_action->DisplayValueSeparatorAttribute() ?>" name="x_rep_action" id="x_rep_action" value="{value}"<?php echo $requisition_module->rep_action->EditAttributes() ?>></div>
<div id="dsl_x_rep_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $requisition_module->rep_action->RadioButtonListHtml(FALSE, "x_rep_action", 3) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_requisition_module_rep_action">
<span<?php echo $requisition_module->rep_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->rep_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_rep_action" data-page="3" name="x_rep_action" id="x_rep_action" value="<?php echo ew_HtmlEncode($requisition_module->rep_action->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->rep_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($requisition_module->rep_comment->Visible) { // rep_comment ?>
	<div id="r_rep_comment" class="form-group">
		<label id="elh_requisition_module_rep_comment" for="x_rep_comment" class="<?php echo $requisition_module_edit->LeftColumnClass ?>"><?php echo $requisition_module->rep_comment->FldCaption() ?></label>
		<div class="<?php echo $requisition_module_edit->RightColumnClass ?>"><div<?php echo $requisition_module->rep_comment->CellAttributes() ?>>
<?php if ($requisition_module->CurrentAction <> "F") { ?>
<span id="el_requisition_module_rep_comment">
<textarea data-table="requisition_module" data-field="x_rep_comment" data-page="3" name="x_rep_comment" id="x_rep_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($requisition_module->rep_comment->getPlaceHolder()) ?>"<?php echo $requisition_module->rep_comment->EditAttributes() ?>><?php echo $requisition_module->rep_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_requisition_module_rep_comment">
<span<?php echo $requisition_module->rep_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $requisition_module->rep_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="requisition_module" data-field="x_rep_comment" data-page="3" name="x_rep_comment" id="x_rep_comment" value="<?php echo ew_HtmlEncode($requisition_module->rep_comment->FormValue) ?>">
<?php } ?>
<?php echo $requisition_module->rep_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<input type="hidden" data-table="requisition_module" data-field="x_code" name="x_code" id="x_code" value="<?php echo ew_HtmlEncode($requisition_module->code->CurrentValue) ?>">
<?php if (!$requisition_module_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $requisition_module_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($requisition_module->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $requisition_module_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
frequisition_moduleedit.Init();
</script>
<?php
$requisition_module_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('#r_status').hide();
$("#r_staff_id").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$requisition_module_edit->Page_Terminate();
?>
