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

$laptop_tablet_edit = NULL; // Initialize page object first

class claptop_tablet_edit extends claptop_tablet {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'laptop_tablet';

	// Page object name
	var $PageObjName = 'laptop_tablet_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("laptop_tabletlist.php"));
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "laptop_tabletview.php")
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
			$this->Page_Terminate("laptop_tabletlist.php"); // Return to list page
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
					$this->Page_Terminate("laptop_tabletlist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "laptop_tabletlist.php")
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
		if (!$this->asset_tag->FldIsDetailKey) {
			$this->asset_tag->setFormValue($objForm->GetValue("x_asset_tag"));
		}
		if (!$this->start_sate->FldIsDetailKey) {
			$this->start_sate->setFormValue($objForm->GetValue("x_start_sate"));
			$this->start_sate->CurrentValue = ew_UnFormatDateTime($this->start_sate->CurrentValue, 0);
		}
		if (!$this->end_date->FldIsDetailKey) {
			$this->end_date->setFormValue($objForm->GetValue("x_end_date"));
			$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 0);
		}
		if (!$this->cost_for_repair->FldIsDetailKey) {
			$this->cost_for_repair->setFormValue($objForm->GetValue("x_cost_for_repair"));
		}
		if (!$this->service_provider->FldIsDetailKey) {
			$this->service_provider->setFormValue($objForm->GetValue("x_service_provider"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->type_of_repair->FldIsDetailKey) {
			$this->type_of_repair->setFormValue($objForm->GetValue("x_type_of_repair"));
		}
		if (!$this->note->FldIsDetailKey) {
			$this->note->setFormValue($objForm->GetValue("x_note"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->asset_category->FldIsDetailKey) {
			$this->asset_category->setFormValue($objForm->GetValue("x_asset_category"));
		}
		if (!$this->asset_sub_category->FldIsDetailKey) {
			$this->asset_sub_category->setFormValue($objForm->GetValue("x_asset_sub_category"));
		}
		if (!$this->serial_number->FldIsDetailKey) {
			$this->serial_number->setFormValue($objForm->GetValue("x_serial_number"));
		}
		if (!$this->programe_area->FldIsDetailKey) {
			$this->programe_area->setFormValue($objForm->GetValue("x_programe_area"));
		}
		if (!$this->division->FldIsDetailKey) {
			$this->division->setFormValue($objForm->GetValue("x_division"));
		}
		if (!$this->branch->FldIsDetailKey) {
			$this->branch->setFormValue($objForm->GetValue("x_branch"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->created_by->FldIsDetailKey) {
			$this->created_by->setFormValue($objForm->GetValue("x_created_by"));
		}
		if (!$this->created_date->FldIsDetailKey) {
			$this->created_date->setFormValue($objForm->GetValue("x_created_date"));
			$this->created_date->CurrentValue = ew_UnFormatDateTime($this->created_date->CurrentValue, 0);
		}
		if (!$this->device_number->FldIsDetailKey) {
			$this->device_number->setFormValue($objForm->GetValue("x_device_number"));
		}
		if (!$this->tablet_imie_number->FldIsDetailKey) {
			$this->tablet_imie_number->setFormValue($objForm->GetValue("x_tablet_imie_number"));
		}
		if (!$this->model->FldIsDetailKey) {
			$this->model->setFormValue($objForm->GetValue("x_model"));
		}
		if (!$this->flag->FldIsDetailKey) {
			$this->flag->setFormValue($objForm->GetValue("x_flag"));
		}
		if (!$this->area->FldIsDetailKey) {
			$this->area->setFormValue($objForm->GetValue("x_area"));
		}
		if (!$this->updated_date->FldIsDetailKey) {
			$this->updated_date->setFormValue($objForm->GetValue("x_updated_date"));
			$this->updated_date->CurrentValue = ew_UnFormatDateTime($this->updated_date->CurrentValue, 0);
		}
		if (!$this->updated_by->FldIsDetailKey) {
			$this->updated_by->setFormValue($objForm->GetValue("x_updated_by"));
		}
		if (!$this->received_date->FldIsDetailKey) {
			$this->received_date->setFormValue($objForm->GetValue("x_received_date"));
			$this->received_date->CurrentValue = ew_UnFormatDateTime($this->received_date->CurrentValue, 0);
		}
		if (!$this->received_by->FldIsDetailKey) {
			$this->received_by->setFormValue($objForm->GetValue("x_received_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->asset_tag->CurrentValue = $this->asset_tag->FormValue;
		$this->start_sate->CurrentValue = $this->start_sate->FormValue;
		$this->start_sate->CurrentValue = ew_UnFormatDateTime($this->start_sate->CurrentValue, 0);
		$this->end_date->CurrentValue = $this->end_date->FormValue;
		$this->end_date->CurrentValue = ew_UnFormatDateTime($this->end_date->CurrentValue, 0);
		$this->cost_for_repair->CurrentValue = $this->cost_for_repair->FormValue;
		$this->service_provider->CurrentValue = $this->service_provider->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->type_of_repair->CurrentValue = $this->type_of_repair->FormValue;
		$this->note->CurrentValue = $this->note->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->asset_category->CurrentValue = $this->asset_category->FormValue;
		$this->asset_sub_category->CurrentValue = $this->asset_sub_category->FormValue;
		$this->serial_number->CurrentValue = $this->serial_number->FormValue;
		$this->programe_area->CurrentValue = $this->programe_area->FormValue;
		$this->division->CurrentValue = $this->division->FormValue;
		$this->branch->CurrentValue = $this->branch->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->created_by->CurrentValue = $this->created_by->FormValue;
		$this->created_date->CurrentValue = $this->created_date->FormValue;
		$this->created_date->CurrentValue = ew_UnFormatDateTime($this->created_date->CurrentValue, 0);
		$this->device_number->CurrentValue = $this->device_number->FormValue;
		$this->tablet_imie_number->CurrentValue = $this->tablet_imie_number->FormValue;
		$this->model->CurrentValue = $this->model->FormValue;
		$this->flag->CurrentValue = $this->flag->FormValue;
		$this->area->CurrentValue = $this->area->FormValue;
		$this->updated_date->CurrentValue = $this->updated_date->FormValue;
		$this->updated_date->CurrentValue = ew_UnFormatDateTime($this->updated_date->CurrentValue, 0);
		$this->updated_by->CurrentValue = $this->updated_by->FormValue;
		$this->received_date->CurrentValue = $this->received_date->FormValue;
		$this->received_date->CurrentValue = ew_UnFormatDateTime($this->received_date->CurrentValue, 0);
		$this->received_by->CurrentValue = $this->received_by->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// asset_tag
			$this->asset_tag->EditAttrs["class"] = "form-control";
			$this->asset_tag->EditCustomAttributes = "";
			$this->asset_tag->EditValue = ew_HtmlEncode($this->asset_tag->CurrentValue);
			$this->asset_tag->PlaceHolder = ew_RemoveHtml($this->asset_tag->FldCaption());

			// start_sate
			$this->start_sate->EditAttrs["class"] = "form-control";
			$this->start_sate->EditCustomAttributes = "";
			$this->start_sate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_sate->CurrentValue, 8));
			$this->start_sate->PlaceHolder = ew_RemoveHtml($this->start_sate->FldCaption());

			// end_date
			$this->end_date->EditAttrs["class"] = "form-control";
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->end_date->CurrentValue, 8));
			$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

			// cost_for_repair
			$this->cost_for_repair->EditAttrs["class"] = "form-control";
			$this->cost_for_repair->EditCustomAttributes = "";
			$this->cost_for_repair->EditValue = ew_HtmlEncode($this->cost_for_repair->CurrentValue);
			$this->cost_for_repair->PlaceHolder = ew_RemoveHtml($this->cost_for_repair->FldCaption());

			// service_provider
			$this->service_provider->EditAttrs["class"] = "form-control";
			$this->service_provider->EditCustomAttributes = "";
			$this->service_provider->EditValue = ew_HtmlEncode($this->service_provider->CurrentValue);
			$this->service_provider->PlaceHolder = ew_RemoveHtml($this->service_provider->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// type_of_repair
			$this->type_of_repair->EditAttrs["class"] = "form-control";
			$this->type_of_repair->EditCustomAttributes = "";
			$this->type_of_repair->EditValue = ew_HtmlEncode($this->type_of_repair->CurrentValue);
			$this->type_of_repair->PlaceHolder = ew_RemoveHtml($this->type_of_repair->FldCaption());

			// note
			$this->note->EditAttrs["class"] = "form-control";
			$this->note->EditCustomAttributes = "";
			$this->note->EditValue = ew_HtmlEncode($this->note->CurrentValue);
			$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// asset_category
			$this->asset_category->EditAttrs["class"] = "form-control";
			$this->asset_category->EditCustomAttributes = "";
			$this->asset_category->EditValue = ew_HtmlEncode($this->asset_category->CurrentValue);
			$this->asset_category->PlaceHolder = ew_RemoveHtml($this->asset_category->FldCaption());

			// asset_sub_category
			$this->asset_sub_category->EditAttrs["class"] = "form-control";
			$this->asset_sub_category->EditCustomAttributes = "";
			$this->asset_sub_category->EditValue = ew_HtmlEncode($this->asset_sub_category->CurrentValue);
			$this->asset_sub_category->PlaceHolder = ew_RemoveHtml($this->asset_sub_category->FldCaption());

			// serial_number
			$this->serial_number->EditAttrs["class"] = "form-control";
			$this->serial_number->EditCustomAttributes = "";
			$this->serial_number->EditValue = ew_HtmlEncode($this->serial_number->CurrentValue);
			$this->serial_number->PlaceHolder = ew_RemoveHtml($this->serial_number->FldCaption());

			// programe_area
			$this->programe_area->EditAttrs["class"] = "form-control";
			$this->programe_area->EditCustomAttributes = "";
			$this->programe_area->EditValue = ew_HtmlEncode($this->programe_area->CurrentValue);
			$this->programe_area->PlaceHolder = ew_RemoveHtml($this->programe_area->FldCaption());

			// division
			$this->division->EditAttrs["class"] = "form-control";
			$this->division->EditCustomAttributes = "";
			$this->division->EditValue = ew_HtmlEncode($this->division->CurrentValue);
			$this->division->PlaceHolder = ew_RemoveHtml($this->division->FldCaption());

			// branch
			$this->branch->EditAttrs["class"] = "form-control";
			$this->branch->EditCustomAttributes = "";
			$this->branch->EditValue = ew_HtmlEncode($this->branch->CurrentValue);
			$this->branch->PlaceHolder = ew_RemoveHtml($this->branch->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";
			$this->department->EditValue = ew_HtmlEncode($this->department->CurrentValue);
			$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// created_by
			$this->created_by->EditAttrs["class"] = "form-control";
			$this->created_by->EditCustomAttributes = "";
			$this->created_by->EditValue = ew_HtmlEncode($this->created_by->CurrentValue);
			$this->created_by->PlaceHolder = ew_RemoveHtml($this->created_by->FldCaption());

			// created_date
			$this->created_date->EditAttrs["class"] = "form-control";
			$this->created_date->EditCustomAttributes = "";
			$this->created_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_date->CurrentValue, 8));
			$this->created_date->PlaceHolder = ew_RemoveHtml($this->created_date->FldCaption());

			// device_number
			$this->device_number->EditAttrs["class"] = "form-control";
			$this->device_number->EditCustomAttributes = "";
			$this->device_number->EditValue = ew_HtmlEncode($this->device_number->CurrentValue);
			$this->device_number->PlaceHolder = ew_RemoveHtml($this->device_number->FldCaption());

			// tablet_imie_number
			$this->tablet_imie_number->EditAttrs["class"] = "form-control";
			$this->tablet_imie_number->EditCustomAttributes = "";
			$this->tablet_imie_number->EditValue = ew_HtmlEncode($this->tablet_imie_number->CurrentValue);
			$this->tablet_imie_number->PlaceHolder = ew_RemoveHtml($this->tablet_imie_number->FldCaption());

			// model
			$this->model->EditAttrs["class"] = "form-control";
			$this->model->EditCustomAttributes = "";
			$this->model->EditValue = ew_HtmlEncode($this->model->CurrentValue);
			$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

			// flag
			$this->flag->EditAttrs["class"] = "form-control";
			$this->flag->EditCustomAttributes = "";
			$this->flag->EditValue = ew_HtmlEncode($this->flag->CurrentValue);
			$this->flag->PlaceHolder = ew_RemoveHtml($this->flag->FldCaption());

			// area
			$this->area->EditAttrs["class"] = "form-control";
			$this->area->EditCustomAttributes = "";
			$this->area->EditValue = ew_HtmlEncode($this->area->CurrentValue);
			$this->area->PlaceHolder = ew_RemoveHtml($this->area->FldCaption());

			// updated_date
			$this->updated_date->EditAttrs["class"] = "form-control";
			$this->updated_date->EditCustomAttributes = "";
			$this->updated_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_date->CurrentValue, 8));
			$this->updated_date->PlaceHolder = ew_RemoveHtml($this->updated_date->FldCaption());

			// updated_by
			$this->updated_by->EditAttrs["class"] = "form-control";
			$this->updated_by->EditCustomAttributes = "";
			$this->updated_by->EditValue = ew_HtmlEncode($this->updated_by->CurrentValue);
			$this->updated_by->PlaceHolder = ew_RemoveHtml($this->updated_by->FldCaption());

			// received_date
			$this->received_date->EditAttrs["class"] = "form-control";
			$this->received_date->EditCustomAttributes = "";
			$this->received_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->received_date->CurrentValue, 8));
			$this->received_date->PlaceHolder = ew_RemoveHtml($this->received_date->FldCaption());

			// received_by
			$this->received_by->EditAttrs["class"] = "form-control";
			$this->received_by->EditCustomAttributes = "";
			$this->received_by->EditValue = ew_HtmlEncode($this->received_by->CurrentValue);
			$this->received_by->PlaceHolder = ew_RemoveHtml($this->received_by->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";

			// start_sate
			$this->start_sate->LinkCustomAttributes = "";
			$this->start_sate->HrefValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";

			// cost_for_repair
			$this->cost_for_repair->LinkCustomAttributes = "";
			$this->cost_for_repair->HrefValue = "";

			// service_provider
			$this->service_provider->LinkCustomAttributes = "";
			$this->service_provider->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// type_of_repair
			$this->type_of_repair->LinkCustomAttributes = "";
			$this->type_of_repair->HrefValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// asset_category
			$this->asset_category->LinkCustomAttributes = "";
			$this->asset_category->HrefValue = "";

			// asset_sub_category
			$this->asset_sub_category->LinkCustomAttributes = "";
			$this->asset_sub_category->HrefValue = "";

			// serial_number
			$this->serial_number->LinkCustomAttributes = "";
			$this->serial_number->HrefValue = "";

			// programe_area
			$this->programe_area->LinkCustomAttributes = "";
			$this->programe_area->HrefValue = "";

			// division
			$this->division->LinkCustomAttributes = "";
			$this->division->HrefValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// created_by
			$this->created_by->LinkCustomAttributes = "";
			$this->created_by->HrefValue = "";

			// created_date
			$this->created_date->LinkCustomAttributes = "";
			$this->created_date->HrefValue = "";

			// device_number
			$this->device_number->LinkCustomAttributes = "";
			$this->device_number->HrefValue = "";

			// tablet_imie_number
			$this->tablet_imie_number->LinkCustomAttributes = "";
			$this->tablet_imie_number->HrefValue = "";

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";

			// flag
			$this->flag->LinkCustomAttributes = "";
			$this->flag->HrefValue = "";

			// area
			$this->area->LinkCustomAttributes = "";
			$this->area->HrefValue = "";

			// updated_date
			$this->updated_date->LinkCustomAttributes = "";
			$this->updated_date->HrefValue = "";

			// updated_by
			$this->updated_by->LinkCustomAttributes = "";
			$this->updated_by->HrefValue = "";

			// received_date
			$this->received_date->LinkCustomAttributes = "";
			$this->received_date->HrefValue = "";

			// received_by
			$this->received_by->LinkCustomAttributes = "";
			$this->received_by->HrefValue = "";
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
		if (!ew_CheckDateDef($this->start_sate->FormValue)) {
			ew_AddMessage($gsFormError, $this->start_sate->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->end_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->end_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->cost_for_repair->FormValue)) {
			ew_AddMessage($gsFormError, $this->cost_for_repair->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->created_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->flag->FormValue)) {
			ew_AddMessage($gsFormError, $this->flag->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->updated_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->updated_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->received_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->received_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->received_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->received_by->FldErrMsg());
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

			// asset_tag
			$this->asset_tag->SetDbValueDef($rsnew, $this->asset_tag->CurrentValue, NULL, $this->asset_tag->ReadOnly);

			// start_sate
			$this->start_sate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_sate->CurrentValue, 0), NULL, $this->start_sate->ReadOnly);

			// end_date
			$this->end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->end_date->CurrentValue, 0), NULL, $this->end_date->ReadOnly);

			// cost_for_repair
			$this->cost_for_repair->SetDbValueDef($rsnew, $this->cost_for_repair->CurrentValue, NULL, $this->cost_for_repair->ReadOnly);

			// service_provider
			$this->service_provider->SetDbValueDef($rsnew, $this->service_provider->CurrentValue, NULL, $this->service_provider->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, $this->address->ReadOnly);

			// type_of_repair
			$this->type_of_repair->SetDbValueDef($rsnew, $this->type_of_repair->CurrentValue, NULL, $this->type_of_repair->ReadOnly);

			// note
			$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, NULL, $this->note->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// asset_category
			$this->asset_category->SetDbValueDef($rsnew, $this->asset_category->CurrentValue, NULL, $this->asset_category->ReadOnly);

			// asset_sub_category
			$this->asset_sub_category->SetDbValueDef($rsnew, $this->asset_sub_category->CurrentValue, NULL, $this->asset_sub_category->ReadOnly);

			// serial_number
			$this->serial_number->SetDbValueDef($rsnew, $this->serial_number->CurrentValue, NULL, $this->serial_number->ReadOnly);

			// programe_area
			$this->programe_area->SetDbValueDef($rsnew, $this->programe_area->CurrentValue, NULL, $this->programe_area->ReadOnly);

			// division
			$this->division->SetDbValueDef($rsnew, $this->division->CurrentValue, NULL, $this->division->ReadOnly);

			// branch
			$this->branch->SetDbValueDef($rsnew, $this->branch->CurrentValue, NULL, $this->branch->ReadOnly);

			// department
			$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, $this->department->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, $this->staff_id->ReadOnly);

			// created_by
			$this->created_by->SetDbValueDef($rsnew, $this->created_by->CurrentValue, NULL, $this->created_by->ReadOnly);

			// created_date
			$this->created_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_date->CurrentValue, 0), NULL, $this->created_date->ReadOnly);

			// device_number
			$this->device_number->SetDbValueDef($rsnew, $this->device_number->CurrentValue, NULL, $this->device_number->ReadOnly);

			// tablet_imie_number
			$this->tablet_imie_number->SetDbValueDef($rsnew, $this->tablet_imie_number->CurrentValue, NULL, $this->tablet_imie_number->ReadOnly);

			// model
			$this->model->SetDbValueDef($rsnew, $this->model->CurrentValue, NULL, $this->model->ReadOnly);

			// flag
			$this->flag->SetDbValueDef($rsnew, $this->flag->CurrentValue, NULL, $this->flag->ReadOnly);

			// area
			$this->area->SetDbValueDef($rsnew, $this->area->CurrentValue, NULL, $this->area->ReadOnly);

			// updated_date
			$this->updated_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_date->CurrentValue, 0), NULL, $this->updated_date->ReadOnly);

			// updated_by
			$this->updated_by->SetDbValueDef($rsnew, $this->updated_by->CurrentValue, NULL, $this->updated_by->ReadOnly);

			// received_date
			$this->received_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->received_date->CurrentValue, 0), NULL, $this->received_date->ReadOnly);

			// received_by
			$this->received_by->SetDbValueDef($rsnew, $this->received_by->CurrentValue, NULL, $this->received_by->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("laptop_tabletlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($laptop_tablet_edit)) $laptop_tablet_edit = new claptop_tablet_edit();

// Page init
$laptop_tablet_edit->Page_Init();

// Page main
$laptop_tablet_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laptop_tablet_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = flaptop_tabletedit = new ew_Form("flaptop_tabletedit", "edit");

// Validate form
flaptop_tabletedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_start_sate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->start_sate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_end_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->end_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cost_for_repair");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->cost_for_repair->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->created_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_flag");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->flag->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->updated_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->updated_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_received_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->received_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_received_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laptop_tablet->received_by->FldErrMsg()) ?>");

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
flaptop_tabletedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flaptop_tabletedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $laptop_tablet_edit->ShowPageHeader(); ?>
<?php
$laptop_tablet_edit->ShowMessage();
?>
<?php if (!$laptop_tablet_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($laptop_tablet_edit->Pager)) $laptop_tablet_edit->Pager = new cPrevNextPager($laptop_tablet_edit->StartRec, $laptop_tablet_edit->DisplayRecs, $laptop_tablet_edit->TotalRecs, $laptop_tablet_edit->AutoHidePager) ?>
<?php if ($laptop_tablet_edit->Pager->RecordCount > 0 && $laptop_tablet_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($laptop_tablet_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $laptop_tablet_edit->PageUrl() ?>start=<?php echo $laptop_tablet_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($laptop_tablet_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $laptop_tablet_edit->PageUrl() ?>start=<?php echo $laptop_tablet_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $laptop_tablet_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($laptop_tablet_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $laptop_tablet_edit->PageUrl() ?>start=<?php echo $laptop_tablet_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($laptop_tablet_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $laptop_tablet_edit->PageUrl() ?>start=<?php echo $laptop_tablet_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $laptop_tablet_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="flaptop_tabletedit" id="flaptop_tabletedit" class="<?php echo $laptop_tablet_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($laptop_tablet_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $laptop_tablet_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="laptop_tablet">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($laptop_tablet_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($laptop_tablet->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_laptop_tablet_id" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->id->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->id->CellAttributes() ?>>
<span id="el_laptop_tablet_id">
<span<?php echo $laptop_tablet->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $laptop_tablet->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="laptop_tablet" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($laptop_tablet->id->CurrentValue) ?>">
<?php echo $laptop_tablet->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->asset_tag->Visible) { // asset_tag ?>
	<div id="r_asset_tag" class="form-group">
		<label id="elh_laptop_tablet_asset_tag" for="x_asset_tag" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->asset_tag->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->asset_tag->CellAttributes() ?>>
<span id="el_laptop_tablet_asset_tag">
<input type="text" data-table="laptop_tablet" data-field="x_asset_tag" name="x_asset_tag" id="x_asset_tag" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->asset_tag->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->asset_tag->EditValue ?>"<?php echo $laptop_tablet->asset_tag->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->asset_tag->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->start_sate->Visible) { // start_sate ?>
	<div id="r_start_sate" class="form-group">
		<label id="elh_laptop_tablet_start_sate" for="x_start_sate" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->start_sate->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->start_sate->CellAttributes() ?>>
<span id="el_laptop_tablet_start_sate">
<input type="text" data-table="laptop_tablet" data-field="x_start_sate" name="x_start_sate" id="x_start_sate" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->start_sate->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->start_sate->EditValue ?>"<?php echo $laptop_tablet->start_sate->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->start_sate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->end_date->Visible) { // end_date ?>
	<div id="r_end_date" class="form-group">
		<label id="elh_laptop_tablet_end_date" for="x_end_date" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->end_date->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->end_date->CellAttributes() ?>>
<span id="el_laptop_tablet_end_date">
<input type="text" data-table="laptop_tablet" data-field="x_end_date" name="x_end_date" id="x_end_date" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->end_date->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->end_date->EditValue ?>"<?php echo $laptop_tablet->end_date->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->end_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->cost_for_repair->Visible) { // cost_for_repair ?>
	<div id="r_cost_for_repair" class="form-group">
		<label id="elh_laptop_tablet_cost_for_repair" for="x_cost_for_repair" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->cost_for_repair->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->cost_for_repair->CellAttributes() ?>>
<span id="el_laptop_tablet_cost_for_repair">
<input type="text" data-table="laptop_tablet" data-field="x_cost_for_repair" name="x_cost_for_repair" id="x_cost_for_repair" size="30" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->cost_for_repair->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->cost_for_repair->EditValue ?>"<?php echo $laptop_tablet->cost_for_repair->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->cost_for_repair->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->service_provider->Visible) { // service_provider ?>
	<div id="r_service_provider" class="form-group">
		<label id="elh_laptop_tablet_service_provider" for="x_service_provider" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->service_provider->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->service_provider->CellAttributes() ?>>
<span id="el_laptop_tablet_service_provider">
<input type="text" data-table="laptop_tablet" data-field="x_service_provider" name="x_service_provider" id="x_service_provider" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->service_provider->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->service_provider->EditValue ?>"<?php echo $laptop_tablet->service_provider->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->service_provider->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_laptop_tablet_address" for="x_address" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->address->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->address->CellAttributes() ?>>
<span id="el_laptop_tablet_address">
<input type="text" data-table="laptop_tablet" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="31" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->address->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->address->EditValue ?>"<?php echo $laptop_tablet->address->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->type_of_repair->Visible) { // type_of_repair ?>
	<div id="r_type_of_repair" class="form-group">
		<label id="elh_laptop_tablet_type_of_repair" for="x_type_of_repair" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->type_of_repair->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->type_of_repair->CellAttributes() ?>>
<span id="el_laptop_tablet_type_of_repair">
<input type="text" data-table="laptop_tablet" data-field="x_type_of_repair" name="x_type_of_repair" id="x_type_of_repair" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->type_of_repair->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->type_of_repair->EditValue ?>"<?php echo $laptop_tablet->type_of_repair->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->type_of_repair->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_laptop_tablet_note" for="x_note" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->note->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->note->CellAttributes() ?>>
<span id="el_laptop_tablet_note">
<input type="text" data-table="laptop_tablet" data-field="x_note" name="x_note" id="x_note" size="30" maxlength="38" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->note->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->note->EditValue ?>"<?php echo $laptop_tablet->note->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_laptop_tablet_status" for="x_status" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->status->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->status->CellAttributes() ?>>
<span id="el_laptop_tablet_status">
<input type="text" data-table="laptop_tablet" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->status->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->status->EditValue ?>"<?php echo $laptop_tablet->status->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->asset_category->Visible) { // asset_category ?>
	<div id="r_asset_category" class="form-group">
		<label id="elh_laptop_tablet_asset_category" for="x_asset_category" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->asset_category->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->asset_category->CellAttributes() ?>>
<span id="el_laptop_tablet_asset_category">
<input type="text" data-table="laptop_tablet" data-field="x_asset_category" name="x_asset_category" id="x_asset_category" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->asset_category->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->asset_category->EditValue ?>"<?php echo $laptop_tablet->asset_category->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->asset_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->asset_sub_category->Visible) { // asset_sub_category ?>
	<div id="r_asset_sub_category" class="form-group">
		<label id="elh_laptop_tablet_asset_sub_category" for="x_asset_sub_category" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->asset_sub_category->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->asset_sub_category->CellAttributes() ?>>
<span id="el_laptop_tablet_asset_sub_category">
<input type="text" data-table="laptop_tablet" data-field="x_asset_sub_category" name="x_asset_sub_category" id="x_asset_sub_category" size="30" maxlength="41" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->asset_sub_category->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->asset_sub_category->EditValue ?>"<?php echo $laptop_tablet->asset_sub_category->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->asset_sub_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->serial_number->Visible) { // serial_number ?>
	<div id="r_serial_number" class="form-group">
		<label id="elh_laptop_tablet_serial_number" for="x_serial_number" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->serial_number->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->serial_number->CellAttributes() ?>>
<span id="el_laptop_tablet_serial_number">
<input type="text" data-table="laptop_tablet" data-field="x_serial_number" name="x_serial_number" id="x_serial_number" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->serial_number->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->serial_number->EditValue ?>"<?php echo $laptop_tablet->serial_number->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->serial_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->programe_area->Visible) { // programe_area ?>
	<div id="r_programe_area" class="form-group">
		<label id="elh_laptop_tablet_programe_area" for="x_programe_area" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->programe_area->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->programe_area->CellAttributes() ?>>
<span id="el_laptop_tablet_programe_area">
<input type="text" data-table="laptop_tablet" data-field="x_programe_area" name="x_programe_area" id="x_programe_area" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->programe_area->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->programe_area->EditValue ?>"<?php echo $laptop_tablet->programe_area->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->programe_area->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->division->Visible) { // division ?>
	<div id="r_division" class="form-group">
		<label id="elh_laptop_tablet_division" for="x_division" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->division->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->division->CellAttributes() ?>>
<span id="el_laptop_tablet_division">
<input type="text" data-table="laptop_tablet" data-field="x_division" name="x_division" id="x_division" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->division->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->division->EditValue ?>"<?php echo $laptop_tablet->division->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->division->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->branch->Visible) { // branch ?>
	<div id="r_branch" class="form-group">
		<label id="elh_laptop_tablet_branch" for="x_branch" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->branch->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->branch->CellAttributes() ?>>
<span id="el_laptop_tablet_branch">
<input type="text" data-table="laptop_tablet" data-field="x_branch" name="x_branch" id="x_branch" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->branch->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->branch->EditValue ?>"<?php echo $laptop_tablet->branch->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->branch->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_laptop_tablet_department" for="x_department" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->department->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->department->CellAttributes() ?>>
<span id="el_laptop_tablet_department">
<input type="text" data-table="laptop_tablet" data-field="x_department" name="x_department" id="x_department" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->department->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->department->EditValue ?>"<?php echo $laptop_tablet->department->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_laptop_tablet_staff_id" for="x_staff_id" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->staff_id->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->staff_id->CellAttributes() ?>>
<span id="el_laptop_tablet_staff_id">
<input type="text" data-table="laptop_tablet" data-field="x_staff_id" name="x_staff_id" id="x_staff_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->staff_id->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->staff_id->EditValue ?>"<?php echo $laptop_tablet->staff_id->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->created_by->Visible) { // created_by ?>
	<div id="r_created_by" class="form-group">
		<label id="elh_laptop_tablet_created_by" for="x_created_by" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->created_by->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->created_by->CellAttributes() ?>>
<span id="el_laptop_tablet_created_by">
<input type="text" data-table="laptop_tablet" data-field="x_created_by" name="x_created_by" id="x_created_by" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->created_by->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->created_by->EditValue ?>"<?php echo $laptop_tablet->created_by->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->created_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->created_date->Visible) { // created_date ?>
	<div id="r_created_date" class="form-group">
		<label id="elh_laptop_tablet_created_date" for="x_created_date" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->created_date->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->created_date->CellAttributes() ?>>
<span id="el_laptop_tablet_created_date">
<input type="text" data-table="laptop_tablet" data-field="x_created_date" name="x_created_date" id="x_created_date" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->created_date->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->created_date->EditValue ?>"<?php echo $laptop_tablet->created_date->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->created_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->device_number->Visible) { // device_number ?>
	<div id="r_device_number" class="form-group">
		<label id="elh_laptop_tablet_device_number" for="x_device_number" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->device_number->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->device_number->CellAttributes() ?>>
<span id="el_laptop_tablet_device_number">
<input type="text" data-table="laptop_tablet" data-field="x_device_number" name="x_device_number" id="x_device_number" size="30" maxlength="9" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->device_number->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->device_number->EditValue ?>"<?php echo $laptop_tablet->device_number->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->device_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->tablet_imie_number->Visible) { // tablet_imie_number ?>
	<div id="r_tablet_imie_number" class="form-group">
		<label id="elh_laptop_tablet_tablet_imie_number" for="x_tablet_imie_number" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->tablet_imie_number->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->tablet_imie_number->CellAttributes() ?>>
<span id="el_laptop_tablet_tablet_imie_number">
<input type="text" data-table="laptop_tablet" data-field="x_tablet_imie_number" name="x_tablet_imie_number" id="x_tablet_imie_number" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->tablet_imie_number->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->tablet_imie_number->EditValue ?>"<?php echo $laptop_tablet->tablet_imie_number->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->tablet_imie_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_laptop_tablet_model" for="x_model" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->model->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->model->CellAttributes() ?>>
<span id="el_laptop_tablet_model">
<input type="text" data-table="laptop_tablet" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="7" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->model->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->model->EditValue ?>"<?php echo $laptop_tablet->model->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->flag->Visible) { // flag ?>
	<div id="r_flag" class="form-group">
		<label id="elh_laptop_tablet_flag" for="x_flag" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->flag->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->flag->CellAttributes() ?>>
<span id="el_laptop_tablet_flag">
<input type="text" data-table="laptop_tablet" data-field="x_flag" name="x_flag" id="x_flag" size="30" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->flag->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->flag->EditValue ?>"<?php echo $laptop_tablet->flag->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->flag->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->area->Visible) { // area ?>
	<div id="r_area" class="form-group">
		<label id="elh_laptop_tablet_area" for="x_area" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->area->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->area->CellAttributes() ?>>
<span id="el_laptop_tablet_area">
<input type="text" data-table="laptop_tablet" data-field="x_area" name="x_area" id="x_area" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->area->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->area->EditValue ?>"<?php echo $laptop_tablet->area->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->area->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->updated_date->Visible) { // updated_date ?>
	<div id="r_updated_date" class="form-group">
		<label id="elh_laptop_tablet_updated_date" for="x_updated_date" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->updated_date->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->updated_date->CellAttributes() ?>>
<span id="el_laptop_tablet_updated_date">
<input type="text" data-table="laptop_tablet" data-field="x_updated_date" name="x_updated_date" id="x_updated_date" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->updated_date->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->updated_date->EditValue ?>"<?php echo $laptop_tablet->updated_date->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->updated_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->updated_by->Visible) { // updated_by ?>
	<div id="r_updated_by" class="form-group">
		<label id="elh_laptop_tablet_updated_by" for="x_updated_by" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->updated_by->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->updated_by->CellAttributes() ?>>
<span id="el_laptop_tablet_updated_by">
<input type="text" data-table="laptop_tablet" data-field="x_updated_by" name="x_updated_by" id="x_updated_by" size="30" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->updated_by->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->updated_by->EditValue ?>"<?php echo $laptop_tablet->updated_by->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->updated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->received_date->Visible) { // received_date ?>
	<div id="r_received_date" class="form-group">
		<label id="elh_laptop_tablet_received_date" for="x_received_date" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->received_date->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->received_date->CellAttributes() ?>>
<span id="el_laptop_tablet_received_date">
<input type="text" data-table="laptop_tablet" data-field="x_received_date" name="x_received_date" id="x_received_date" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->received_date->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->received_date->EditValue ?>"<?php echo $laptop_tablet->received_date->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->received_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($laptop_tablet->received_by->Visible) { // received_by ?>
	<div id="r_received_by" class="form-group">
		<label id="elh_laptop_tablet_received_by" for="x_received_by" class="<?php echo $laptop_tablet_edit->LeftColumnClass ?>"><?php echo $laptop_tablet->received_by->FldCaption() ?></label>
		<div class="<?php echo $laptop_tablet_edit->RightColumnClass ?>"><div<?php echo $laptop_tablet->received_by->CellAttributes() ?>>
<span id="el_laptop_tablet_received_by">
<input type="text" data-table="laptop_tablet" data-field="x_received_by" name="x_received_by" id="x_received_by" size="30" placeholder="<?php echo ew_HtmlEncode($laptop_tablet->received_by->getPlaceHolder()) ?>" value="<?php echo $laptop_tablet->received_by->EditValue ?>"<?php echo $laptop_tablet->received_by->EditAttributes() ?>>
</span>
<?php echo $laptop_tablet->received_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$laptop_tablet_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $laptop_tablet_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $laptop_tablet_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
flaptop_tabletedit.Init();
</script>
<?php
$laptop_tablet_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$laptop_tablet_edit->Page_Terminate();
?>
