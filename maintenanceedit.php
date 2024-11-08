<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "maintenanceinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$maintenance_edit = NULL; // Initialize page object first

class cmaintenance_edit extends cmaintenance {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'maintenance';

	// Page object name
	var $PageObjName = 'maintenance_edit';

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

		// Table object (maintenance)
		if (!isset($GLOBALS["maintenance"]) || get_class($GLOBALS["maintenance"]) == "cmaintenance") {
			$GLOBALS["maintenance"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["maintenance"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'maintenance', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("maintenancelist.php"));
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
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->staff_name->SetVisibility();
		$this->department->SetVisibility();
		$this->branch->SetVisibility();
		$this->buildings->SetVisibility();
		$this->floors->SetVisibility();
		$this->items->SetVisibility();
		$this->priority->SetVisibility();
		$this->description->SetVisibility();
		$this->status->SetVisibility();
		$this->date_maintained->SetVisibility();
		$this->maintenance_action->SetVisibility();
		$this->maintenance_comment->SetVisibility();
		$this->maintained_by->SetVisibility();
		$this->reviewed_date->SetVisibility();
		$this->reviewed_action->SetVisibility();
		$this->reviewed_comment->SetVisibility();
		$this->reviewed_by->SetVisibility();

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
		global $EW_EXPORT, $maintenance;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($maintenance);
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
					if ($pageName == "maintenanceview.php")
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
			$this->Page_Terminate("maintenancelist.php"); // Return to list page
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
					$this->Page_Terminate("maintenancelist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "maintenancelist.php")
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
		if (!$this->date_initiated->FldIsDetailKey) {
			$this->date_initiated->setFormValue($objForm->GetValue("x_date_initiated"));
			$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->staff_name->FldIsDetailKey) {
			$this->staff_name->setFormValue($objForm->GetValue("x_staff_name"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->branch->FldIsDetailKey) {
			$this->branch->setFormValue($objForm->GetValue("x_branch"));
		}
		if (!$this->buildings->FldIsDetailKey) {
			$this->buildings->setFormValue($objForm->GetValue("x_buildings"));
		}
		if (!$this->floors->FldIsDetailKey) {
			$this->floors->setFormValue($objForm->GetValue("x_floors"));
		}
		if (!$this->items->FldIsDetailKey) {
			$this->items->setFormValue($objForm->GetValue("x_items"));
		}
		if (!$this->priority->FldIsDetailKey) {
			$this->priority->setFormValue($objForm->GetValue("x_priority"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->date_maintained->FldIsDetailKey) {
			$this->date_maintained->setFormValue($objForm->GetValue("x_date_maintained"));
			$this->date_maintained->CurrentValue = ew_UnFormatDateTime($this->date_maintained->CurrentValue, 17);
		}
		if (!$this->maintenance_action->FldIsDetailKey) {
			$this->maintenance_action->setFormValue($objForm->GetValue("x_maintenance_action"));
		}
		if (!$this->maintenance_comment->FldIsDetailKey) {
			$this->maintenance_comment->setFormValue($objForm->GetValue("x_maintenance_comment"));
		}
		if (!$this->maintained_by->FldIsDetailKey) {
			$this->maintained_by->setFormValue($objForm->GetValue("x_maintained_by"));
		}
		if (!$this->reviewed_date->FldIsDetailKey) {
			$this->reviewed_date->setFormValue($objForm->GetValue("x_reviewed_date"));
			$this->reviewed_date->CurrentValue = ew_UnFormatDateTime($this->reviewed_date->CurrentValue, 11);
		}
		if (!$this->reviewed_action->FldIsDetailKey) {
			$this->reviewed_action->setFormValue($objForm->GetValue("x_reviewed_action"));
		}
		if (!$this->reviewed_comment->FldIsDetailKey) {
			$this->reviewed_comment->setFormValue($objForm->GetValue("x_reviewed_comment"));
		}
		if (!$this->reviewed_by->FldIsDetailKey) {
			$this->reviewed_by->setFormValue($objForm->GetValue("x_reviewed_by"));
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
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->staff_name->CurrentValue = $this->staff_name->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->branch->CurrentValue = $this->branch->FormValue;
		$this->buildings->CurrentValue = $this->buildings->FormValue;
		$this->floors->CurrentValue = $this->floors->FormValue;
		$this->items->CurrentValue = $this->items->FormValue;
		$this->priority->CurrentValue = $this->priority->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->date_maintained->CurrentValue = $this->date_maintained->FormValue;
		$this->date_maintained->CurrentValue = ew_UnFormatDateTime($this->date_maintained->CurrentValue, 17);
		$this->maintenance_action->CurrentValue = $this->maintenance_action->FormValue;
		$this->maintenance_comment->CurrentValue = $this->maintenance_comment->FormValue;
		$this->maintained_by->CurrentValue = $this->maintained_by->FormValue;
		$this->reviewed_date->CurrentValue = $this->reviewed_date->FormValue;
		$this->reviewed_date->CurrentValue = ew_UnFormatDateTime($this->reviewed_date->CurrentValue, 11);
		$this->reviewed_action->CurrentValue = $this->reviewed_action->FormValue;
		$this->reviewed_comment->CurrentValue = $this->reviewed_comment->FormValue;
		$this->reviewed_by->CurrentValue = $this->reviewed_by->FormValue;
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
		$this->reference_id->setDbValue($row['reference_id']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->staff_name->setDbValue($row['staff_name']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->buildings->setDbValue($row['buildings']);
		$this->floors->setDbValue($row['floors']);
		$this->items->setDbValue($row['items']);
		$this->priority->setDbValue($row['priority']);
		$this->description->setDbValue($row['description']);
		$this->status->setDbValue($row['status']);
		$this->date_maintained->setDbValue($row['date_maintained']);
		$this->maintenance_action->setDbValue($row['maintenance_action']);
		$this->maintenance_comment->setDbValue($row['maintenance_comment']);
		$this->maintained_by->setDbValue($row['maintained_by']);
		$this->reviewed_date->setDbValue($row['reviewed_date']);
		$this->reviewed_action->setDbValue($row['reviewed_action']);
		$this->reviewed_comment->setDbValue($row['reviewed_comment']);
		$this->reviewed_by->setDbValue($row['reviewed_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['staff_name'] = NULL;
		$row['department'] = NULL;
		$row['branch'] = NULL;
		$row['buildings'] = NULL;
		$row['floors'] = NULL;
		$row['items'] = NULL;
		$row['priority'] = NULL;
		$row['description'] = NULL;
		$row['status'] = NULL;
		$row['date_maintained'] = NULL;
		$row['maintenance_action'] = NULL;
		$row['maintenance_comment'] = NULL;
		$row['maintained_by'] = NULL;
		$row['reviewed_date'] = NULL;
		$row['reviewed_action'] = NULL;
		$row['reviewed_comment'] = NULL;
		$row['reviewed_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->date_initiated->DbValue = $row['date_initiated'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->staff_name->DbValue = $row['staff_name'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->buildings->DbValue = $row['buildings'];
		$this->floors->DbValue = $row['floors'];
		$this->items->DbValue = $row['items'];
		$this->priority->DbValue = $row['priority'];
		$this->description->DbValue = $row['description'];
		$this->status->DbValue = $row['status'];
		$this->date_maintained->DbValue = $row['date_maintained'];
		$this->maintenance_action->DbValue = $row['maintenance_action'];
		$this->maintenance_comment->DbValue = $row['maintenance_comment'];
		$this->maintained_by->DbValue = $row['maintained_by'];
		$this->reviewed_date->DbValue = $row['reviewed_date'];
		$this->reviewed_action->DbValue = $row['reviewed_action'];
		$this->reviewed_comment->DbValue = $row['reviewed_comment'];
		$this->reviewed_by->DbValue = $row['reviewed_by'];
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
		// reference_id
		// staff_id
		// staff_name
		// department
		// branch
		// buildings
		// floors
		// items
		// priority
		// description
		// status
		// date_maintained
		// maintenance_action
		// maintenance_comment
		// maintained_by
		// reviewed_date
		// reviewed_action
		// reviewed_comment
		// reviewed_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

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

		// staff_name
		$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
		if (strval($this->staff_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_name->ViewValue = $this->staff_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
			}
		} else {
			$this->staff_name->ViewValue = NULL;
		}
		$this->staff_name->ViewCustomAttributes = "";

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

		// branch
		$this->branch->ViewValue = $this->branch->CurrentValue;
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// buildings
		if (strval($this->buildings->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->buildings->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `buildings`";
		$sWhereWrk = "";
		$this->buildings->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->buildings->ViewValue = $this->buildings->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->buildings->ViewValue = $this->buildings->CurrentValue;
			}
		} else {
			$this->buildings->ViewValue = NULL;
		}
		$this->buildings->ViewCustomAttributes = "";

		// floors
		if (strval($this->floors->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->floors->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `floors`";
		$sWhereWrk = "";
		$this->floors->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->floors->ViewValue = $this->floors->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->floors->ViewValue = $this->floors->CurrentValue;
			}
		} else {
			$this->floors->ViewValue = NULL;
		}
		$this->floors->ViewCustomAttributes = "";

		// items
		if (strval($this->items->CurrentValue) <> "") {
			$arwrk = explode(",", $this->items->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
		$sWhereWrk = "";
		$this->items->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->items->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->items->ViewValue .= $this->items->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->items->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->items->ViewValue = $this->items->CurrentValue;
			}
		} else {
			$this->items->ViewValue = NULL;
		}
		$this->items->ViewCustomAttributes = "";

		// priority
		if (strval($this->priority->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->priority->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->priority->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->priority, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->priority->ViewValue = $this->priority->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->priority->ViewValue = $this->priority->CurrentValue;
			}
		} else {
			$this->priority->ViewValue = NULL;
		}
		$this->priority->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintained_status`";
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

		// date_maintained
		$this->date_maintained->ViewValue = $this->date_maintained->CurrentValue;
		$this->date_maintained->ViewValue = ew_FormatDateTime($this->date_maintained->ViewValue, 17);
		$this->date_maintained->ViewCustomAttributes = "";

		// maintenance_action
		if (strval($this->maintenance_action->CurrentValue) <> "") {
			$this->maintenance_action->ViewValue = $this->maintenance_action->OptionCaption($this->maintenance_action->CurrentValue);
		} else {
			$this->maintenance_action->ViewValue = NULL;
		}
		$this->maintenance_action->ViewCustomAttributes = "";

		// maintenance_comment
		$this->maintenance_comment->ViewValue = $this->maintenance_comment->CurrentValue;
		$this->maintenance_comment->ViewCustomAttributes = "";

		// maintained_by
		$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
		if (strval($this->maintained_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintained_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->maintained_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->maintained_by->ViewValue = $this->maintained_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
			}
		} else {
			$this->maintained_by->ViewValue = NULL;
		}
		$this->maintained_by->ViewCustomAttributes = "";

		// reviewed_date
		$this->reviewed_date->ViewValue = $this->reviewed_date->CurrentValue;
		$this->reviewed_date->ViewValue = ew_FormatDateTime($this->reviewed_date->ViewValue, 11);
		$this->reviewed_date->ViewCustomAttributes = "";

		// reviewed_action
		if (strval($this->reviewed_action->CurrentValue) <> "") {
			$this->reviewed_action->ViewValue = $this->reviewed_action->OptionCaption($this->reviewed_action->CurrentValue);
		} else {
			$this->reviewed_action->ViewValue = NULL;
		}
		$this->reviewed_action->ViewCustomAttributes = "";

		// reviewed_comment
		$this->reviewed_comment->ViewValue = $this->reviewed_comment->CurrentValue;
		$this->reviewed_comment->ViewCustomAttributes = "";

		// reviewed_by
		$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
		if (strval($this->reviewed_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reviewed_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->reviewed_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->reviewed_by->ViewValue = $this->reviewed_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
			}
		} else {
			$this->reviewed_by->ViewValue = NULL;
		}
		$this->reviewed_by->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// staff_name
			$this->staff_name->LinkCustomAttributes = "";
			$this->staff_name->HrefValue = "";
			$this->staff_name->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// buildings
			$this->buildings->LinkCustomAttributes = "";
			$this->buildings->HrefValue = "";
			$this->buildings->TooltipValue = "";

			// floors
			$this->floors->LinkCustomAttributes = "";
			$this->floors->HrefValue = "";
			$this->floors->TooltipValue = "";

			// items
			$this->items->LinkCustomAttributes = "";
			$this->items->HrefValue = "";
			$this->items->TooltipValue = "";

			// priority
			$this->priority->LinkCustomAttributes = "";
			$this->priority->HrefValue = "";
			$this->priority->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// date_maintained
			$this->date_maintained->LinkCustomAttributes = "";
			$this->date_maintained->HrefValue = "";
			$this->date_maintained->TooltipValue = "";

			// maintenance_action
			$this->maintenance_action->LinkCustomAttributes = "";
			$this->maintenance_action->HrefValue = "";
			$this->maintenance_action->TooltipValue = "";

			// maintenance_comment
			$this->maintenance_comment->LinkCustomAttributes = "";
			$this->maintenance_comment->HrefValue = "";
			$this->maintenance_comment->TooltipValue = "";

			// maintained_by
			$this->maintained_by->LinkCustomAttributes = "";
			$this->maintained_by->HrefValue = "";
			$this->maintained_by->TooltipValue = "";

			// reviewed_date
			$this->reviewed_date->LinkCustomAttributes = "";
			$this->reviewed_date->HrefValue = "";
			$this->reviewed_date->TooltipValue = "";

			// reviewed_action
			$this->reviewed_action->LinkCustomAttributes = "";
			$this->reviewed_action->HrefValue = "";
			$this->reviewed_action->TooltipValue = "";

			// reviewed_comment
			$this->reviewed_comment->LinkCustomAttributes = "";
			$this->reviewed_comment->HrefValue = "";
			$this->reviewed_comment->TooltipValue = "";

			// reviewed_by
			$this->reviewed_by->LinkCustomAttributes = "";
			$this->reviewed_by->HrefValue = "";
			$this->reviewed_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_initiated->CurrentValue, 8));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

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

			// staff_name
			$this->staff_name->EditAttrs["class"] = "form-control";
			$this->staff_name->EditCustomAttributes = "";
			$this->staff_name->EditValue = ew_HtmlEncode($this->staff_name->CurrentValue);
			if (strval($this->staff_name->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->staff_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->staff_name->EditValue = $this->staff_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_name->EditValue = ew_HtmlEncode($this->staff_name->CurrentValue);
				}
			} else {
				$this->staff_name->EditValue = NULL;
			}
			$this->staff_name->PlaceHolder = ew_RemoveHtml($this->staff_name->FldCaption());

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

			// branch
			$this->branch->EditAttrs["class"] = "form-control";
			$this->branch->EditCustomAttributes = "";
			$this->branch->EditValue = ew_HtmlEncode($this->branch->CurrentValue);
			if (strval($this->branch->CurrentValue) <> "") {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "";
			$this->branch->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->branch->EditValue = $this->branch->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->branch->EditValue = ew_HtmlEncode($this->branch->CurrentValue);
				}
			} else {
				$this->branch->EditValue = NULL;
			}
			$this->branch->PlaceHolder = ew_RemoveHtml($this->branch->FldCaption());

			// buildings
			$this->buildings->EditCustomAttributes = "";
			if (trim(strval($this->buildings->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->buildings->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `buildings`";
			$sWhereWrk = "";
			$this->buildings->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->buildings->ViewValue = $this->buildings->DisplayValue($arwrk);
			} else {
				$this->buildings->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->buildings->EditValue = $arwrk;

			// floors
			$this->floors->EditCustomAttributes = "";
			if (trim(strval($this->floors->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->floors->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `buildings_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `floors`";
			$sWhereWrk = "";
			$this->floors->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->floors->ViewValue = $this->floors->DisplayValue($arwrk);
			} else {
				$this->floors->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->floors->EditValue = $arwrk;

			// items
			$this->items->EditCustomAttributes = "";
			if (trim(strval($this->items->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->items->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `floor_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `items`";
			$sWhereWrk = "";
			$this->items->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->items->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->items->ViewValue .= $this->items->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->items->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->items->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->items->EditValue = $arwrk;

			// priority
			$this->priority->EditCustomAttributes = "";
			if (trim(strval($this->priority->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->priority->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident-category`";
			$sWhereWrk = "";
			$this->priority->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->priority, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->priority->ViewValue = $this->priority->DisplayValue($arwrk);
			} else {
				$this->priority->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->priority->EditValue = $arwrk;

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `maintained_status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// date_maintained
			$this->date_maintained->EditAttrs["class"] = "form-control";
			$this->date_maintained->EditCustomAttributes = "";
			$this->date_maintained->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_maintained->CurrentValue, 17));
			$this->date_maintained->PlaceHolder = ew_RemoveHtml($this->date_maintained->FldCaption());

			// maintenance_action
			$this->maintenance_action->EditCustomAttributes = "";
			$this->maintenance_action->EditValue = $this->maintenance_action->Options(FALSE);

			// maintenance_comment
			$this->maintenance_comment->EditAttrs["class"] = "form-control";
			$this->maintenance_comment->EditCustomAttributes = "";
			$this->maintenance_comment->EditValue = ew_HtmlEncode($this->maintenance_comment->CurrentValue);
			$this->maintenance_comment->PlaceHolder = ew_RemoveHtml($this->maintenance_comment->FldCaption());

			// maintained_by
			$this->maintained_by->EditAttrs["class"] = "form-control";
			$this->maintained_by->EditCustomAttributes = "";
			$this->maintained_by->EditValue = ew_HtmlEncode($this->maintained_by->CurrentValue);
			if (strval($this->maintained_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintained_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->maintained_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->maintained_by->EditValue = $this->maintained_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->maintained_by->EditValue = ew_HtmlEncode($this->maintained_by->CurrentValue);
				}
			} else {
				$this->maintained_by->EditValue = NULL;
			}
			$this->maintained_by->PlaceHolder = ew_RemoveHtml($this->maintained_by->FldCaption());

			// reviewed_date
			$this->reviewed_date->EditAttrs["class"] = "form-control";
			$this->reviewed_date->EditCustomAttributes = "";
			$this->reviewed_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->reviewed_date->CurrentValue, 11));
			$this->reviewed_date->PlaceHolder = ew_RemoveHtml($this->reviewed_date->FldCaption());

			// reviewed_action
			$this->reviewed_action->EditCustomAttributes = "";
			$this->reviewed_action->EditValue = $this->reviewed_action->Options(FALSE);

			// reviewed_comment
			$this->reviewed_comment->EditAttrs["class"] = "form-control";
			$this->reviewed_comment->EditCustomAttributes = "";
			$this->reviewed_comment->EditValue = ew_HtmlEncode($this->reviewed_comment->CurrentValue);
			$this->reviewed_comment->PlaceHolder = ew_RemoveHtml($this->reviewed_comment->FldCaption());

			// reviewed_by
			$this->reviewed_by->EditAttrs["class"] = "form-control";
			$this->reviewed_by->EditCustomAttributes = "";
			$this->reviewed_by->EditValue = ew_HtmlEncode($this->reviewed_by->CurrentValue);
			if (strval($this->reviewed_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->reviewed_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->reviewed_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->reviewed_by->EditValue = $this->reviewed_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->reviewed_by->EditValue = ew_HtmlEncode($this->reviewed_by->CurrentValue);
				}
			} else {
				$this->reviewed_by->EditValue = NULL;
			}
			$this->reviewed_by->PlaceHolder = ew_RemoveHtml($this->reviewed_by->FldCaption());

			// Edit refer script
			// date_initiated

			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// staff_name
			$this->staff_name->LinkCustomAttributes = "";
			$this->staff_name->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";

			// buildings
			$this->buildings->LinkCustomAttributes = "";
			$this->buildings->HrefValue = "";

			// floors
			$this->floors->LinkCustomAttributes = "";
			$this->floors->HrefValue = "";

			// items
			$this->items->LinkCustomAttributes = "";
			$this->items->HrefValue = "";

			// priority
			$this->priority->LinkCustomAttributes = "";
			$this->priority->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// date_maintained
			$this->date_maintained->LinkCustomAttributes = "";
			$this->date_maintained->HrefValue = "";

			// maintenance_action
			$this->maintenance_action->LinkCustomAttributes = "";
			$this->maintenance_action->HrefValue = "";

			// maintenance_comment
			$this->maintenance_comment->LinkCustomAttributes = "";
			$this->maintenance_comment->HrefValue = "";

			// maintained_by
			$this->maintained_by->LinkCustomAttributes = "";
			$this->maintained_by->HrefValue = "";

			// reviewed_date
			$this->reviewed_date->LinkCustomAttributes = "";
			$this->reviewed_date->HrefValue = "";

			// reviewed_action
			$this->reviewed_action->LinkCustomAttributes = "";
			$this->reviewed_action->HrefValue = "";

			// reviewed_comment
			$this->reviewed_comment->LinkCustomAttributes = "";
			$this->reviewed_comment->HrefValue = "";

			// reviewed_by
			$this->reviewed_by->LinkCustomAttributes = "";
			$this->reviewed_by->HrefValue = "";
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
		if (!$this->reference_id->FldIsDetailKey && !is_null($this->reference_id->FormValue) && $this->reference_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reference_id->FldCaption(), $this->reference_id->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!$this->staff_name->FldIsDetailKey && !is_null($this->staff_name->FormValue) && $this->staff_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_name->FldCaption(), $this->staff_name->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->staff_name->FormValue)) {
			ew_AddMessage($gsFormError, $this->staff_name->FldErrMsg());
		}
		if (!$this->department->FldIsDetailKey && !is_null($this->department->FormValue) && $this->department->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->department->FldCaption(), $this->department->ReqErrMsg));
		}
		if (!$this->branch->FldIsDetailKey && !is_null($this->branch->FormValue) && $this->branch->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->branch->FldCaption(), $this->branch->ReqErrMsg));
		}
		if (!$this->buildings->FldIsDetailKey && !is_null($this->buildings->FormValue) && $this->buildings->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->buildings->FldCaption(), $this->buildings->ReqErrMsg));
		}
		if (!$this->floors->FldIsDetailKey && !is_null($this->floors->FormValue) && $this->floors->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->floors->FldCaption(), $this->floors->ReqErrMsg));
		}
		if ($this->items->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->items->FldCaption(), $this->items->ReqErrMsg));
		}
		if (!$this->priority->FldIsDetailKey && !is_null($this->priority->FormValue) && $this->priority->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->priority->FldCaption(), $this->priority->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->date_maintained->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_maintained->FldErrMsg());
		}
		if ($this->maintenance_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->maintenance_action->FldCaption(), $this->maintenance_action->ReqErrMsg));
		}
		if (!$this->maintenance_comment->FldIsDetailKey && !is_null($this->maintenance_comment->FormValue) && $this->maintenance_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->maintenance_comment->FldCaption(), $this->maintenance_comment->ReqErrMsg));
		}
		if (!$this->maintained_by->FldIsDetailKey && !is_null($this->maintained_by->FormValue) && $this->maintained_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->maintained_by->FldCaption(), $this->maintained_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->maintained_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->maintained_by->FldErrMsg());
		}
		if (!$this->reviewed_date->FldIsDetailKey && !is_null($this->reviewed_date->FormValue) && $this->reviewed_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reviewed_date->FldCaption(), $this->reviewed_date->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->reviewed_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->reviewed_date->FldErrMsg());
		}
		if ($this->reviewed_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reviewed_action->FldCaption(), $this->reviewed_action->ReqErrMsg));
		}
		if (!$this->reviewed_comment->FldIsDetailKey && !is_null($this->reviewed_comment->FormValue) && $this->reviewed_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reviewed_comment->FldCaption(), $this->reviewed_comment->ReqErrMsg));
		}
		if (!$this->reviewed_by->FldIsDetailKey && !is_null($this->reviewed_by->FormValue) && $this->reviewed_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reviewed_by->FldCaption(), $this->reviewed_by->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->reviewed_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->reviewed_by->FldErrMsg());
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
			$this->date_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0), NULL, $this->date_initiated->ReadOnly);

			// reference_id
			$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, NULL, $this->reference_id->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, $this->staff_id->ReadOnly);

			// staff_name
			$this->staff_name->SetDbValueDef($rsnew, $this->staff_name->CurrentValue, NULL, $this->staff_name->ReadOnly);

			// department
			$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, $this->department->ReadOnly);

			// branch
			$this->branch->SetDbValueDef($rsnew, $this->branch->CurrentValue, NULL, $this->branch->ReadOnly);

			// buildings
			$this->buildings->SetDbValueDef($rsnew, $this->buildings->CurrentValue, NULL, $this->buildings->ReadOnly);

			// floors
			$this->floors->SetDbValueDef($rsnew, $this->floors->CurrentValue, NULL, $this->floors->ReadOnly);

			// items
			$this->items->SetDbValueDef($rsnew, $this->items->CurrentValue, NULL, $this->items->ReadOnly);

			// priority
			$this->priority->SetDbValueDef($rsnew, $this->priority->CurrentValue, NULL, $this->priority->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// date_maintained
			$this->date_maintained->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_maintained->CurrentValue, 17), NULL, $this->date_maintained->ReadOnly);

			// maintenance_action
			$this->maintenance_action->SetDbValueDef($rsnew, $this->maintenance_action->CurrentValue, NULL, $this->maintenance_action->ReadOnly);

			// maintenance_comment
			$this->maintenance_comment->SetDbValueDef($rsnew, $this->maintenance_comment->CurrentValue, NULL, $this->maintenance_comment->ReadOnly);

			// maintained_by
			$this->maintained_by->SetDbValueDef($rsnew, $this->maintained_by->CurrentValue, NULL, $this->maintained_by->ReadOnly);

			// reviewed_date
			$this->reviewed_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->reviewed_date->CurrentValue, 11), NULL, $this->reviewed_date->ReadOnly);

			// reviewed_action
			$this->reviewed_action->SetDbValueDef($rsnew, $this->reviewed_action->CurrentValue, 0, $this->reviewed_action->ReadOnly);

			// reviewed_comment
			$this->reviewed_comment->SetDbValueDef($rsnew, $this->reviewed_comment->CurrentValue, NULL, $this->reviewed_comment->ReadOnly);

			// reviewed_by
			$this->reviewed_by->SetDbValueDef($rsnew, $this->reviewed_by->CurrentValue, NULL, $this->reviewed_by->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("maintenancelist.php"), "", $this->TableVar, TRUE);
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
		case "x_staff_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
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
		case "x_branch":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id` AS `LinkFld`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_buildings":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `buildings`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_floors":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `floors`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`buildings_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_items":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`floor_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_priority":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->priority, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintained_status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_maintained_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_reviewed_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_staff_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->staff_name) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
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
		case "x_branch":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld` FROM `branch`";
			$sWhereWrk = "`branch_name` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_maintained_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->maintained_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_reviewed_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->reviewed_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($maintenance_edit)) $maintenance_edit = new cmaintenance_edit();

// Page init
$maintenance_edit->Page_Init();

// Page main
$maintenance_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$maintenance_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmaintenanceedit = new ew_Form("fmaintenanceedit", "edit");

// Validate form
fmaintenanceedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->date_initiated->FldCaption(), $maintenance->date_initiated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_initiated");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance->date_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reference_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->reference_id->FldCaption(), $maintenance->reference_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->staff_id->FldCaption(), $maintenance->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->staff_name->FldCaption(), $maintenance->staff_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_name");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance->staff_name->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->department->FldCaption(), $maintenance->department->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_branch");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->branch->FldCaption(), $maintenance->branch->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_buildings");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->buildings->FldCaption(), $maintenance->buildings->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_floors");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->floors->FldCaption(), $maintenance->floors->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_items[]");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->items->FldCaption(), $maintenance->items->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_priority");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->priority->FldCaption(), $maintenance->priority->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->description->FldCaption(), $maintenance->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->status->FldCaption(), $maintenance->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_maintained");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance->date_maintained->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maintenance_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->maintenance_action->FldCaption(), $maintenance->maintenance_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_maintenance_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->maintenance_comment->FldCaption(), $maintenance->maintenance_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_maintained_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->maintained_by->FldCaption(), $maintenance->maintained_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_maintained_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance->maintained_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->reviewed_date->FldCaption(), $maintenance->reviewed_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance->reviewed_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->reviewed_action->FldCaption(), $maintenance->reviewed_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->reviewed_comment->FldCaption(), $maintenance->reviewed_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $maintenance->reviewed_by->FldCaption(), $maintenance->reviewed_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($maintenance->reviewed_by->FldErrMsg()) ?>");

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
fmaintenanceedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmaintenanceedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fmaintenanceedit.MultiPage = new ew_MultiPage("fmaintenanceedit");

// Dynamic selection lists
fmaintenanceedit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenanceedit.Lists["x_staff_id"].Data = "<?php echo $maintenance_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_edit->staff_id->LookupFilterQuery(TRUE, "edit"))) ?>;
fmaintenanceedit.Lists["x_staff_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenanceedit.Lists["x_staff_name"].Data = "<?php echo $maintenance_edit->staff_name->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.AutoSuggests["x_staff_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_edit->staff_name->LookupFilterQuery(TRUE, "edit"))) ?>;
fmaintenanceedit.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fmaintenanceedit.Lists["x_department"].Data = "<?php echo $maintenance_edit->department->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_edit->department->LookupFilterQuery(TRUE, "edit"))) ?>;
fmaintenanceedit.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
fmaintenanceedit.Lists["x_branch"].Data = "<?php echo $maintenance_edit->branch->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.AutoSuggests["x_branch"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_edit->branch->LookupFilterQuery(TRUE, "edit"))) ?>;
fmaintenanceedit.Lists["x_buildings"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_floors"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"buildings"};
fmaintenanceedit.Lists["x_buildings"].Data = "<?php echo $maintenance_edit->buildings->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.Lists["x_floors"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_buildings"],"ChildFields":["x_items[]"],"FilterFields":["x_buildings_id"],"Options":[],"Template":"","LinkTable":"floors"};
fmaintenanceedit.Lists["x_floors"].Data = "<?php echo $maintenance_edit->floors->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.Lists["x_items[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_floors"],"ChildFields":[],"FilterFields":["x_floor_id"],"Options":[],"Template":"","LinkTable":"items"};
fmaintenanceedit.Lists["x_items[]"].Data = "<?php echo $maintenance_edit->items->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.Lists["x_priority"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
fmaintenanceedit.Lists["x_priority"].Data = "<?php echo $maintenance_edit->priority->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"maintained_status"};
fmaintenanceedit.Lists["x_status"].Data = "<?php echo $maintenance_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.Lists["x_maintenance_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmaintenanceedit.Lists["x_maintenance_action"].Options = <?php echo json_encode($maintenance_edit->maintenance_action->Options()) ?>;
fmaintenanceedit.Lists["x_maintained_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenanceedit.Lists["x_maintained_by"].Data = "<?php echo $maintenance_edit->maintained_by->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.AutoSuggests["x_maintained_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_edit->maintained_by->LookupFilterQuery(TRUE, "edit"))) ?>;
fmaintenanceedit.Lists["x_reviewed_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmaintenanceedit.Lists["x_reviewed_action"].Options = <?php echo json_encode($maintenance_edit->reviewed_action->Options()) ?>;
fmaintenanceedit.Lists["x_reviewed_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fmaintenanceedit.Lists["x_reviewed_by"].Data = "<?php echo $maintenance_edit->reviewed_by->LookupFilterQuery(FALSE, "edit") ?>";
fmaintenanceedit.AutoSuggests["x_reviewed_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $maintenance_edit->reviewed_by->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $maintenance_edit->ShowPageHeader(); ?>
<?php
$maintenance_edit->ShowMessage();
?>
<?php if (!$maintenance_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($maintenance_edit->Pager)) $maintenance_edit->Pager = new cPrevNextPager($maintenance_edit->StartRec, $maintenance_edit->DisplayRecs, $maintenance_edit->TotalRecs, $maintenance_edit->AutoHidePager) ?>
<?php if ($maintenance_edit->Pager->RecordCount > 0 && $maintenance_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($maintenance_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $maintenance_edit->PageUrl() ?>start=<?php echo $maintenance_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($maintenance_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $maintenance_edit->PageUrl() ?>start=<?php echo $maintenance_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $maintenance_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($maintenance_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $maintenance_edit->PageUrl() ?>start=<?php echo $maintenance_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($maintenance_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $maintenance_edit->PageUrl() ?>start=<?php echo $maintenance_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $maintenance_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fmaintenanceedit" id="fmaintenanceedit" class="<?php echo $maintenance_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($maintenance_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $maintenance_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="maintenance">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($maintenance_edit->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="maintenance_edit"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $maintenance_edit->MultiPages->NavStyle() ?>">
		<li<?php echo $maintenance_edit->MultiPages->TabStyle("1") ?>><a href="#tab_maintenance1" data-toggle="tab"><?php echo $maintenance->PageCaption(1) ?></a></li>
		<li<?php echo $maintenance_edit->MultiPages->TabStyle("2") ?>><a href="#tab_maintenance2" data-toggle="tab"><?php echo $maintenance->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $maintenance_edit->MultiPages->PageStyle("1") ?>" id="tab_maintenance1"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($maintenance->date_initiated->Visible) { // date_initiated ?>
	<div id="r_date_initiated" class="form-group">
		<label id="elh_maintenance_date_initiated" for="x_date_initiated" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->date_initiated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->date_initiated->CellAttributes() ?>>
<span id="el_maintenance_date_initiated">
<input type="text" data-table="maintenance" data-field="x_date_initiated" data-page="1" name="x_date_initiated" id="x_date_initiated" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance->date_initiated->getPlaceHolder()) ?>" value="<?php echo $maintenance->date_initiated->EditValue ?>"<?php echo $maintenance->date_initiated->EditAttributes() ?>>
</span>
<?php echo $maintenance->date_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_maintenance_reference_id" for="x_reference_id" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->reference_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->reference_id->CellAttributes() ?>>
<span id="el_maintenance_reference_id">
<input type="text" data-table="maintenance" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" size="25" maxlength="50" placeholder="<?php echo ew_HtmlEncode($maintenance->reference_id->getPlaceHolder()) ?>" value="<?php echo $maintenance->reference_id->EditValue ?>"<?php echo $maintenance->reference_id->EditAttributes() ?>>
</span>
<?php echo $maintenance->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_maintenance_staff_id" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->staff_id->CellAttributes() ?>>
<span id="el_maintenance_staff_id">
<?php
$wrkonchange = trim(" " . @$maintenance->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$maintenance->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $maintenance->staff_id->EditValue ?>" size="25" maxlength="50" placeholder="<?php echo ew_HtmlEncode($maintenance->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($maintenance->staff_id->getPlaceHolder()) ?>"<?php echo $maintenance->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="maintenance" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $maintenance->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($maintenance->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fmaintenanceedit.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php echo $maintenance->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->staff_name->Visible) { // staff_name ?>
	<div id="r_staff_name" class="form-group">
		<label id="elh_maintenance_staff_name" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->staff_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->staff_name->CellAttributes() ?>>
<span id="el_maintenance_staff_name">
<?php
$wrkonchange = trim(" " . @$maintenance->staff_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$maintenance->staff_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_name" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_staff_name" id="sv_x_staff_name" value="<?php echo $maintenance->staff_name->EditValue ?>" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance->staff_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($maintenance->staff_name->getPlaceHolder()) ?>"<?php echo $maintenance->staff_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="maintenance" data-field="x_staff_name" data-page="1" data-value-separator="<?php echo $maintenance->staff_name->DisplayValueSeparatorAttribute() ?>" name="x_staff_name" id="x_staff_name" value="<?php echo ew_HtmlEncode($maintenance->staff_name->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fmaintenanceedit.CreateAutoSuggest({"id":"x_staff_name","forceSelect":false});
</script>
</span>
<?php echo $maintenance->staff_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_maintenance_department" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->department->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->department->CellAttributes() ?>>
<span id="el_maintenance_department">
<?php
$wrkonchange = trim(" " . @$maintenance->department->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$maintenance->department->EditAttrs["onchange"] = "";
?>
<span id="as_x_department" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_department" id="sv_x_department" value="<?php echo $maintenance->department->EditValue ?>" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance->department->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($maintenance->department->getPlaceHolder()) ?>"<?php echo $maintenance->department->EditAttributes() ?>>
</span>
<input type="hidden" data-table="maintenance" data-field="x_department" data-page="1" data-value-separator="<?php echo $maintenance->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($maintenance->department->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fmaintenanceedit.CreateAutoSuggest({"id":"x_department","forceSelect":false});
</script>
</span>
<?php echo $maintenance->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->branch->Visible) { // branch ?>
	<div id="r_branch" class="form-group">
		<label id="elh_maintenance_branch" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->branch->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->branch->CellAttributes() ?>>
<span id="el_maintenance_branch">
<?php
$wrkonchange = trim(" " . @$maintenance->branch->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$maintenance->branch->EditAttrs["onchange"] = "";
?>
<span id="as_x_branch" style="white-space: nowrap; z-index: 8930">
	<input type="text" name="sv_x_branch" id="sv_x_branch" value="<?php echo $maintenance->branch->EditValue ?>" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance->branch->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($maintenance->branch->getPlaceHolder()) ?>"<?php echo $maintenance->branch->EditAttributes() ?>>
</span>
<input type="hidden" data-table="maintenance" data-field="x_branch" data-page="1" data-value-separator="<?php echo $maintenance->branch->DisplayValueSeparatorAttribute() ?>" name="x_branch" id="x_branch" value="<?php echo ew_HtmlEncode($maintenance->branch->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fmaintenanceedit.CreateAutoSuggest({"id":"x_branch","forceSelect":false});
</script>
</span>
<?php echo $maintenance->branch->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->buildings->Visible) { // buildings ?>
	<div id="r_buildings" class="form-group">
		<label id="elh_maintenance_buildings" for="x_buildings" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->buildings->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->buildings->CellAttributes() ?>>
<span id="el_maintenance_buildings">
<?php $maintenance->buildings->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$maintenance->buildings->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_buildings"><?php echo (strval($maintenance->buildings->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance->buildings->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance->buildings->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_buildings',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance->buildings->ReadOnly || $maintenance->buildings->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance" data-field="x_buildings" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $maintenance->buildings->DisplayValueSeparatorAttribute() ?>" name="x_buildings" id="x_buildings" value="<?php echo $maintenance->buildings->CurrentValue ?>"<?php echo $maintenance->buildings->EditAttributes() ?>>
</span>
<?php echo $maintenance->buildings->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->floors->Visible) { // floors ?>
	<div id="r_floors" class="form-group">
		<label id="elh_maintenance_floors" for="x_floors" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->floors->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->floors->CellAttributes() ?>>
<span id="el_maintenance_floors">
<?php $maintenance->floors->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$maintenance->floors->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_floors"><?php echo (strval($maintenance->floors->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance->floors->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance->floors->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_floors',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance->floors->ReadOnly || $maintenance->floors->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance" data-field="x_floors" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $maintenance->floors->DisplayValueSeparatorAttribute() ?>" name="x_floors" id="x_floors" value="<?php echo $maintenance->floors->CurrentValue ?>"<?php echo $maintenance->floors->EditAttributes() ?>>
</span>
<?php echo $maintenance->floors->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->items->Visible) { // items ?>
	<div id="r_items" class="form-group">
		<label id="elh_maintenance_items" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->items->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->items->CellAttributes() ?>>
<span id="el_maintenance_items">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_items"><?php echo (strval($maintenance->items->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance->items->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance->items->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_items[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance->items->ReadOnly || $maintenance->items->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance" data-field="x_items" data-page="1" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $maintenance->items->DisplayValueSeparatorAttribute() ?>" name="x_items[]" id="x_items[]" value="<?php echo $maintenance->items->CurrentValue ?>"<?php echo $maintenance->items->EditAttributes() ?>>
</span>
<?php echo $maintenance->items->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->priority->Visible) { // priority ?>
	<div id="r_priority" class="form-group">
		<label id="elh_maintenance_priority" for="x_priority" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->priority->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->priority->CellAttributes() ?>>
<span id="el_maintenance_priority">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_priority"><?php echo (strval($maintenance->priority->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $maintenance->priority->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($maintenance->priority->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_priority',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($maintenance->priority->ReadOnly || $maintenance->priority->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="maintenance" data-field="x_priority" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $maintenance->priority->DisplayValueSeparatorAttribute() ?>" name="x_priority" id="x_priority" value="<?php echo $maintenance->priority->CurrentValue ?>"<?php echo $maintenance->priority->EditAttributes() ?>>
</span>
<?php echo $maintenance->priority->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_maintenance_description" for="x_description" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->description->CellAttributes() ?>>
<span id="el_maintenance_description">
<textarea data-table="maintenance" data-field="x_description" data-page="1" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($maintenance->description->getPlaceHolder()) ?>"<?php echo $maintenance->description->EditAttributes() ?>><?php echo $maintenance->description->EditValue ?></textarea>
</span>
<?php echo $maintenance->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $maintenance_edit->MultiPages->PageStyle("2") ?>" id="tab_maintenance2"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($maintenance->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_maintenance_status" for="x_status" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->status->CellAttributes() ?>>
<span id="el_maintenance_status">
<select data-table="maintenance" data-field="x_status" data-page="2" data-value-separator="<?php echo $maintenance->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $maintenance->status->EditAttributes() ?>>
<?php echo $maintenance->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php echo $maintenance->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->date_maintained->Visible) { // date_maintained ?>
	<div id="r_date_maintained" class="form-group">
		<label id="elh_maintenance_date_maintained" for="x_date_maintained" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->date_maintained->FldCaption() ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->date_maintained->CellAttributes() ?>>
<span id="el_maintenance_date_maintained">
<input type="text" data-table="maintenance" data-field="x_date_maintained" data-page="2" data-format="17" name="x_date_maintained" id="x_date_maintained" placeholder="<?php echo ew_HtmlEncode($maintenance->date_maintained->getPlaceHolder()) ?>" value="<?php echo $maintenance->date_maintained->EditValue ?>"<?php echo $maintenance->date_maintained->EditAttributes() ?>>
<?php if (!$maintenance->date_maintained->ReadOnly && !$maintenance->date_maintained->Disabled && !isset($maintenance->date_maintained->EditAttrs["readonly"]) && !isset($maintenance->date_maintained->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fmaintenanceedit", "x_date_maintained", {"ignoreReadonly":true,"useCurrent":false,"format":17});
</script>
<?php } ?>
</span>
<?php echo $maintenance->date_maintained->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->maintenance_action->Visible) { // maintenance_action ?>
	<div id="r_maintenance_action" class="form-group">
		<label id="elh_maintenance_maintenance_action" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->maintenance_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->maintenance_action->CellAttributes() ?>>
<span id="el_maintenance_maintenance_action">
<div id="tp_x_maintenance_action" class="ewTemplate"><input type="radio" data-table="maintenance" data-field="x_maintenance_action" data-page="2" data-value-separator="<?php echo $maintenance->maintenance_action->DisplayValueSeparatorAttribute() ?>" name="x_maintenance_action" id="x_maintenance_action" value="{value}"<?php echo $maintenance->maintenance_action->EditAttributes() ?>></div>
<div id="dsl_x_maintenance_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $maintenance->maintenance_action->RadioButtonListHtml(FALSE, "x_maintenance_action", 2) ?>
</div></div>
</span>
<?php echo $maintenance->maintenance_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->maintenance_comment->Visible) { // maintenance_comment ?>
	<div id="r_maintenance_comment" class="form-group">
		<label id="elh_maintenance_maintenance_comment" for="x_maintenance_comment" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->maintenance_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->maintenance_comment->CellAttributes() ?>>
<span id="el_maintenance_maintenance_comment">
<textarea data-table="maintenance" data-field="x_maintenance_comment" data-page="2" name="x_maintenance_comment" id="x_maintenance_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($maintenance->maintenance_comment->getPlaceHolder()) ?>"<?php echo $maintenance->maintenance_comment->EditAttributes() ?>><?php echo $maintenance->maintenance_comment->EditValue ?></textarea>
</span>
<?php echo $maintenance->maintenance_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->maintained_by->Visible) { // maintained_by ?>
	<div id="r_maintained_by" class="form-group">
		<label id="elh_maintenance_maintained_by" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->maintained_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->maintained_by->CellAttributes() ?>>
<span id="el_maintenance_maintained_by">
<?php
$wrkonchange = trim(" " . @$maintenance->maintained_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$maintenance->maintained_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_maintained_by" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_maintained_by" id="sv_x_maintained_by" value="<?php echo $maintenance->maintained_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($maintenance->maintained_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($maintenance->maintained_by->getPlaceHolder()) ?>"<?php echo $maintenance->maintained_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="maintenance" data-field="x_maintained_by" data-page="2" data-value-separator="<?php echo $maintenance->maintained_by->DisplayValueSeparatorAttribute() ?>" name="x_maintained_by" id="x_maintained_by" value="<?php echo ew_HtmlEncode($maintenance->maintained_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fmaintenanceedit.CreateAutoSuggest({"id":"x_maintained_by","forceSelect":false});
</script>
</span>
<?php echo $maintenance->maintained_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->reviewed_date->Visible) { // reviewed_date ?>
	<div id="r_reviewed_date" class="form-group">
		<label id="elh_maintenance_reviewed_date" for="x_reviewed_date" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->reviewed_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->reviewed_date->CellAttributes() ?>>
<span id="el_maintenance_reviewed_date">
<input type="text" data-table="maintenance" data-field="x_reviewed_date" data-page="2" data-format="11" name="x_reviewed_date" id="x_reviewed_date" size="25" placeholder="<?php echo ew_HtmlEncode($maintenance->reviewed_date->getPlaceHolder()) ?>" value="<?php echo $maintenance->reviewed_date->EditValue ?>"<?php echo $maintenance->reviewed_date->EditAttributes() ?>>
</span>
<?php echo $maintenance->reviewed_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->reviewed_action->Visible) { // reviewed_action ?>
	<div id="r_reviewed_action" class="form-group">
		<label id="elh_maintenance_reviewed_action" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->reviewed_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->reviewed_action->CellAttributes() ?>>
<span id="el_maintenance_reviewed_action">
<div id="tp_x_reviewed_action" class="ewTemplate"><input type="radio" data-table="maintenance" data-field="x_reviewed_action" data-page="2" data-value-separator="<?php echo $maintenance->reviewed_action->DisplayValueSeparatorAttribute() ?>" name="x_reviewed_action" id="x_reviewed_action" value="{value}"<?php echo $maintenance->reviewed_action->EditAttributes() ?>></div>
<div id="dsl_x_reviewed_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $maintenance->reviewed_action->RadioButtonListHtml(FALSE, "x_reviewed_action", 2) ?>
</div></div>
</span>
<?php echo $maintenance->reviewed_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->reviewed_comment->Visible) { // reviewed_comment ?>
	<div id="r_reviewed_comment" class="form-group">
		<label id="elh_maintenance_reviewed_comment" for="x_reviewed_comment" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->reviewed_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->reviewed_comment->CellAttributes() ?>>
<span id="el_maintenance_reviewed_comment">
<textarea data-table="maintenance" data-field="x_reviewed_comment" data-page="2" name="x_reviewed_comment" id="x_reviewed_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($maintenance->reviewed_comment->getPlaceHolder()) ?>"<?php echo $maintenance->reviewed_comment->EditAttributes() ?>><?php echo $maintenance->reviewed_comment->EditValue ?></textarea>
</span>
<?php echo $maintenance->reviewed_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($maintenance->reviewed_by->Visible) { // reviewed_by ?>
	<div id="r_reviewed_by" class="form-group">
		<label id="elh_maintenance_reviewed_by" class="<?php echo $maintenance_edit->LeftColumnClass ?>"><?php echo $maintenance->reviewed_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $maintenance_edit->RightColumnClass ?>"><div<?php echo $maintenance->reviewed_by->CellAttributes() ?>>
<span id="el_maintenance_reviewed_by">
<?php
$wrkonchange = trim(" " . @$maintenance->reviewed_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$maintenance->reviewed_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_reviewed_by" style="white-space: nowrap; z-index: 8790">
	<input type="text" name="sv_x_reviewed_by" id="sv_x_reviewed_by" value="<?php echo $maintenance->reviewed_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($maintenance->reviewed_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($maintenance->reviewed_by->getPlaceHolder()) ?>"<?php echo $maintenance->reviewed_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="maintenance" data-field="x_reviewed_by" data-page="2" data-value-separator="<?php echo $maintenance->reviewed_by->DisplayValueSeparatorAttribute() ?>" name="x_reviewed_by" id="x_reviewed_by" value="<?php echo ew_HtmlEncode($maintenance->reviewed_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fmaintenanceedit.CreateAutoSuggest({"id":"x_reviewed_by","forceSelect":false});
</script>
</span>
<?php echo $maintenance->reviewed_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<input type="hidden" data-table="maintenance" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($maintenance->id->CurrentValue) ?>">
<?php if (!$maintenance_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $maintenance_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $maintenance_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fmaintenanceedit.Init();
</script>
<?php
$maintenance_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('#x_status').attr('readonly',true);
</script>
<?php include_once "footer.php" ?>
<?php
$maintenance_edit->Page_Terminate();
?>
