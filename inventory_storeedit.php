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
		$this->issued_action->SetVisibility();
		$this->issued_comment->SetVisibility();
		$this->issued_by->SetVisibility();
		$this->approver_date->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approved_comment->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->verified_date->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
		$this->verified_by->SetVisibility();
		$this->status->SetVisibility();

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
		if (!$this->issued_action->FldIsDetailKey) {
			$this->issued_action->setFormValue($objForm->GetValue("x_issued_action"));
		}
		if (!$this->issued_comment->FldIsDetailKey) {
			$this->issued_comment->setFormValue($objForm->GetValue("x_issued_comment"));
		}
		if (!$this->issued_by->FldIsDetailKey) {
			$this->issued_by->setFormValue($objForm->GetValue("x_issued_by"));
		}
		if (!$this->approver_date->FldIsDetailKey) {
			$this->approver_date->setFormValue($objForm->GetValue("x_approver_date"));
			$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		}
		if (!$this->approver_action->FldIsDetailKey) {
			$this->approver_action->setFormValue($objForm->GetValue("x_approver_action"));
		}
		if (!$this->approved_comment->FldIsDetailKey) {
			$this->approved_comment->setFormValue($objForm->GetValue("x_approved_comment"));
		}
		if (!$this->approved_by->FldIsDetailKey) {
			$this->approved_by->setFormValue($objForm->GetValue("x_approved_by"));
		}
		if (!$this->verified_date->FldIsDetailKey) {
			$this->verified_date->setFormValue($objForm->GetValue("x_verified_date"));
			$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 0);
		}
		if (!$this->verified_action->FldIsDetailKey) {
			$this->verified_action->setFormValue($objForm->GetValue("x_verified_action"));
		}
		if (!$this->verified_comment->FldIsDetailKey) {
			$this->verified_comment->setFormValue($objForm->GetValue("x_verified_comment"));
		}
		if (!$this->verified_by->FldIsDetailKey) {
			$this->verified_by->setFormValue($objForm->GetValue("x_verified_by"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
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
		$this->issued_action->CurrentValue = $this->issued_action->FormValue;
		$this->issued_comment->CurrentValue = $this->issued_comment->FormValue;
		$this->issued_by->CurrentValue = $this->issued_by->FormValue;
		$this->approver_date->CurrentValue = $this->approver_date->FormValue;
		$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		$this->approver_action->CurrentValue = $this->approver_action->FormValue;
		$this->approved_comment->CurrentValue = $this->approved_comment->FormValue;
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->verified_date->CurrentValue = $this->verified_date->FormValue;
		$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 0);
		$this->verified_action->CurrentValue = $this->verified_action->FormValue;
		$this->verified_comment->CurrentValue = $this->verified_comment->FormValue;
		$this->verified_by->CurrentValue = $this->verified_by->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$this->issued_action->setDbValue($row['issued_action']);
		$this->issued_comment->setDbValue($row['issued_comment']);
		$this->issued_by->setDbValue($row['issued_by']);
		$this->approver_date->setDbValue($row['approver_date']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approved_comment->setDbValue($row['approved_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_date->setDbValue($row['verified_date']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->verified_by->setDbValue($row['verified_by']);
		$this->status->setDbValue($row['status']);
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
		$row['issued_action'] = NULL;
		$row['issued_comment'] = NULL;
		$row['issued_by'] = NULL;
		$row['approver_date'] = NULL;
		$row['approver_action'] = NULL;
		$row['approved_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_date'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['verified_by'] = NULL;
		$row['status'] = NULL;
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
		$this->issued_action->DbValue = $row['issued_action'];
		$this->issued_comment->DbValue = $row['issued_comment'];
		$this->issued_by->DbValue = $row['issued_by'];
		$this->approver_date->DbValue = $row['approver_date'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approved_comment->DbValue = $row['approved_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_date->DbValue = $row['verified_date'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
		$this->status->DbValue = $row['status'];
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
		// issued_action
		// issued_comment
		// issued_by
		// approver_date
		// approver_action
		// approved_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by
		// status

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
		$this->material_name->LookupFilters = array("dx1" => '`material_name`');
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
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->treated_by->ViewValue = $this->treated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
			}
		} else {
			$this->treated_by->ViewValue = NULL;
		}
		$this->treated_by->ViewCustomAttributes = "";

		// issued_action
		if (strval($this->issued_action->CurrentValue) <> "") {
			$this->issued_action->ViewValue = $this->issued_action->OptionCaption($this->issued_action->CurrentValue);
		} else {
			$this->issued_action->ViewValue = NULL;
		}
		$this->issued_action->ViewCustomAttributes = "";

		// issued_comment
		$this->issued_comment->ViewValue = $this->issued_comment->CurrentValue;
		$this->issued_comment->ViewCustomAttributes = "";

		// issued_by
		$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
		if (strval($this->issued_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->issued_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->issued_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->issued_by->ViewValue = $this->issued_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
			}
		} else {
			$this->issued_by->ViewValue = NULL;
		}
		$this->issued_by->ViewCustomAttributes = "";

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

		// approved_comment
		$this->approved_comment->ViewValue = $this->approved_comment->CurrentValue;
		$this->approved_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		if (strval($this->approved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

		// verified_date
		$this->verified_date->ViewValue = $this->verified_date->CurrentValue;
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 0);
		$this->verified_date->ViewCustomAttributes = "";

		// verified_action
		if (strval($this->verified_action->CurrentValue) <> "") {
			$this->verified_action->ViewValue = $this->verified_action->OptionCaption($this->verified_action->CurrentValue);
		} else {
			$this->verified_action->ViewValue = NULL;
		}
		$this->verified_action->ViewCustomAttributes = "";

		// verified_comment
		$this->verified_comment->ViewValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->ViewCustomAttributes = "";

		// verified_by
		$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
		if (strval($this->verified_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->verified_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->verified_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->verified_by->ViewValue = $this->verified_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
			}
		} else {
			$this->verified_by->ViewValue = NULL;
		}
		$this->verified_by->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
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

			// issued_action
			$this->issued_action->LinkCustomAttributes = "";
			$this->issued_action->HrefValue = "";
			$this->issued_action->TooltipValue = "";

			// issued_comment
			$this->issued_comment->LinkCustomAttributes = "";
			$this->issued_comment->HrefValue = "";
			$this->issued_comment->TooltipValue = "";

			// issued_by
			$this->issued_by->LinkCustomAttributes = "";
			$this->issued_by->HrefValue = "";
			$this->issued_by->TooltipValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";
			$this->approver_date->TooltipValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";
			$this->approver_action->TooltipValue = "";

			// approved_comment
			$this->approved_comment->LinkCustomAttributes = "";
			$this->approved_comment->HrefValue = "";
			$this->approved_comment->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// verified_date
			$this->verified_date->LinkCustomAttributes = "";
			$this->verified_date->HrefValue = "";
			$this->verified_date->TooltipValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";
			$this->verified_action->TooltipValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";
			$this->verified_comment->TooltipValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
			$this->verified_by->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
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
			$this->material_name->EditCustomAttributes = "";
			if (trim(strval($this->material_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `inventory`";
			$sWhereWrk = "";
			$this->material_name->LookupFilters = array("dx1" => '`material_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->material_name->ViewValue = $this->material_name->DisplayValue($arwrk);
			} else {
				$this->material_name->ViewValue = $Language->Phrase("PleaseSelect");
			}
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
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->treated_by->EditValue = $this->treated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->treated_by->EditValue = ew_HtmlEncode($this->treated_by->CurrentValue);
				}
			} else {
				$this->treated_by->EditValue = NULL;
			}
			$this->treated_by->PlaceHolder = ew_RemoveHtml($this->treated_by->FldCaption());

			// issued_action
			$this->issued_action->EditCustomAttributes = "";
			$this->issued_action->EditValue = $this->issued_action->Options(FALSE);

			// issued_comment
			$this->issued_comment->EditAttrs["class"] = "form-control";
			$this->issued_comment->EditCustomAttributes = "";
			$this->issued_comment->EditValue = ew_HtmlEncode($this->issued_comment->CurrentValue);
			$this->issued_comment->PlaceHolder = ew_RemoveHtml($this->issued_comment->FldCaption());

			// issued_by
			$this->issued_by->EditAttrs["class"] = "form-control";
			$this->issued_by->EditCustomAttributes = "";
			$this->issued_by->EditValue = ew_HtmlEncode($this->issued_by->CurrentValue);
			if (strval($this->issued_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->issued_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->issued_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->issued_by->EditValue = $this->issued_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->issued_by->EditValue = ew_HtmlEncode($this->issued_by->CurrentValue);
				}
			} else {
				$this->issued_by->EditValue = NULL;
			}
			$this->issued_by->PlaceHolder = ew_RemoveHtml($this->issued_by->FldCaption());

			// approver_date
			$this->approver_date->EditAttrs["class"] = "form-control";
			$this->approver_date->EditCustomAttributes = "";
			$this->approver_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->approver_date->CurrentValue, 8));
			$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

			// approver_action
			$this->approver_action->EditCustomAttributes = "";
			$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

			// approved_comment
			$this->approved_comment->EditAttrs["class"] = "form-control";
			$this->approved_comment->EditCustomAttributes = "";
			$this->approved_comment->EditValue = ew_HtmlEncode($this->approved_comment->CurrentValue);
			$this->approved_comment->PlaceHolder = ew_RemoveHtml($this->approved_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
			if (strval($this->approved_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->approved_by->EditValue = $this->approved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->CurrentValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// verified_date
			$this->verified_date->EditAttrs["class"] = "form-control";
			$this->verified_date->EditCustomAttributes = "";
			$this->verified_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->verified_date->CurrentValue, 8));
			$this->verified_date->PlaceHolder = ew_RemoveHtml($this->verified_date->FldCaption());

			// verified_action
			$this->verified_action->EditCustomAttributes = "";
			$this->verified_action->EditValue = $this->verified_action->Options(FALSE);

			// verified_comment
			$this->verified_comment->EditAttrs["class"] = "form-control";
			$this->verified_comment->EditCustomAttributes = "";
			$this->verified_comment->EditValue = ew_HtmlEncode($this->verified_comment->CurrentValue);
			$this->verified_comment->PlaceHolder = ew_RemoveHtml($this->verified_comment->FldCaption());

			// verified_by
			$this->verified_by->EditAttrs["class"] = "form-control";
			$this->verified_by->EditCustomAttributes = "";
			$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->CurrentValue);
			if (strval($this->verified_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->verified_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->verified_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->verified_by->EditValue = $this->verified_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->CurrentValue);
				}
			} else {
				$this->verified_by->EditValue = NULL;
			}
			$this->verified_by->PlaceHolder = ew_RemoveHtml($this->verified_by->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			if (strval($this->status->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
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

			// issued_action
			$this->issued_action->LinkCustomAttributes = "";
			$this->issued_action->HrefValue = "";

			// issued_comment
			$this->issued_comment->LinkCustomAttributes = "";
			$this->issued_comment->HrefValue = "";

			// issued_by
			$this->issued_by->LinkCustomAttributes = "";
			$this->issued_by->HrefValue = "";

			// approver_date
			$this->approver_date->LinkCustomAttributes = "";
			$this->approver_date->HrefValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";

			// approved_comment
			$this->approved_comment->LinkCustomAttributes = "";
			$this->approved_comment->HrefValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";

			// verified_date
			$this->verified_date->LinkCustomAttributes = "";
			$this->verified_date->HrefValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
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
		if (!ew_CheckInteger($this->issued_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->issued_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->approver_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->approver_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->verified_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->verified_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_by->FldErrMsg());
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

			// issued_action
			$this->issued_action->SetDbValueDef($rsnew, $this->issued_action->CurrentValue, NULL, $this->issued_action->ReadOnly);

			// issued_comment
			$this->issued_comment->SetDbValueDef($rsnew, $this->issued_comment->CurrentValue, NULL, $this->issued_comment->ReadOnly);

			// issued_by
			$this->issued_by->SetDbValueDef($rsnew, $this->issued_by->CurrentValue, NULL, $this->issued_by->ReadOnly);

			// approver_date
			$this->approver_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->approver_date->CurrentValue, 0), NULL, $this->approver_date->ReadOnly);

			// approver_action
			$this->approver_action->SetDbValueDef($rsnew, $this->approver_action->CurrentValue, NULL, $this->approver_action->ReadOnly);

			// approved_comment
			$this->approved_comment->SetDbValueDef($rsnew, $this->approved_comment->CurrentValue, NULL, $this->approved_comment->ReadOnly);

			// approved_by
			$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, $this->approved_by->ReadOnly);

			// verified_date
			$this->verified_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->verified_date->CurrentValue, 0), NULL, $this->verified_date->ReadOnly);

			// verified_action
			$this->verified_action->SetDbValueDef($rsnew, $this->verified_action->CurrentValue, NULL, $this->verified_action->ReadOnly);

			// verified_comment
			$this->verified_comment->SetDbValueDef($rsnew, $this->verified_comment->CurrentValue, NULL, $this->verified_comment->ReadOnly);

			// verified_by
			$this->verified_by->SetDbValueDef($rsnew, $this->verified_by->CurrentValue, NULL, $this->verified_by->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

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
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`material_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_treated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_issued_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_verified_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
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
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->treated_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->treated_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_issued_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->issued_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->issued_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->approved_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->approved_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_verified_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->verified_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->verified_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld` FROM `statuss`";
			$sWhereWrk = "`description` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
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
			elm = this.GetElements("x" + infix + "_issued_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->issued_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approver_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->approver_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->approved_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->verified_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory_store->verified_by->FldErrMsg()) ?>");

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
finventory_storeedit.Lists["x_treated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_storeedit.Lists["x_treated_by"].Data = "<?php echo $inventory_store_edit->treated_by->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_treated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->treated_by->LookupFilterQuery(TRUE, "edit"))) ?>;
finventory_storeedit.Lists["x_issued_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_storeedit.Lists["x_issued_action"].Options = <?php echo json_encode($inventory_store_edit->issued_action->Options()) ?>;
finventory_storeedit.Lists["x_issued_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_storeedit.Lists["x_issued_by"].Data = "<?php echo $inventory_store_edit->issued_by->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_issued_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->issued_by->LookupFilterQuery(TRUE, "edit"))) ?>;
finventory_storeedit.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_storeedit.Lists["x_approver_action"].Options = <?php echo json_encode($inventory_store_edit->approver_action->Options()) ?>;
finventory_storeedit.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_storeedit.Lists["x_approved_by"].Data = "<?php echo $inventory_store_edit->approved_by->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->approved_by->LookupFilterQuery(TRUE, "edit"))) ?>;
finventory_storeedit.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventory_storeedit.Lists["x_verified_action"].Options = <?php echo json_encode($inventory_store_edit->verified_action->Options()) ?>;
finventory_storeedit.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventory_storeedit.Lists["x_verified_by"].Data = "<?php echo $inventory_store_edit->verified_by->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->verified_by->LookupFilterQuery(TRUE, "edit"))) ?>;
finventory_storeedit.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
finventory_storeedit.Lists["x_status"].Data = "<?php echo $inventory_store_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
finventory_storeedit.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_store_edit->status->LookupFilterQuery(TRUE, "edit"))) ?>;

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
<input type="text" data-table="inventory_store" data-field="x_date" data-page="1" name="x_date" id="x_date" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->date->getPlaceHolder()) ?>" value="<?php echo $inventory_store->date->EditValue ?>"<?php echo $inventory_store->date->EditAttributes() ?>>
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_material_name"><?php echo (strval($inventory_store->material_name->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $inventory_store->material_name->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($inventory_store->material_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_material_name',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($inventory_store->material_name->ReadOnly || $inventory_store->material_name->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="inventory_store" data-field="x_material_name" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $inventory_store->material_name->DisplayValueSeparatorAttribute() ?>" name="x_material_name" id="x_material_name" value="<?php echo $inventory_store->material_name->CurrentValue ?>"<?php echo $inventory_store->material_name->EditAttributes() ?>>
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
<?php if ($inventory_store->issued_action->Visible) { // issued_action ?>
	<div id="r_issued_action" class="form-group">
		<label id="elh_inventory_store_issued_action" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->issued_action->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->issued_action->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_issued_action">
<div id="tp_x_issued_action" class="ewTemplate"><input type="radio" data-table="inventory_store" data-field="x_issued_action" data-page="1" data-value-separator="<?php echo $inventory_store->issued_action->DisplayValueSeparatorAttribute() ?>" name="x_issued_action" id="x_issued_action" value="{value}"<?php echo $inventory_store->issued_action->EditAttributes() ?>></div>
<div id="dsl_x_issued_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory_store->issued_action->RadioButtonListHtml(FALSE, "x_issued_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_inventory_store_issued_action">
<span<?php echo $inventory_store->issued_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->issued_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_issued_action" data-page="1" name="x_issued_action" id="x_issued_action" value="<?php echo ew_HtmlEncode($inventory_store->issued_action->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->issued_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->issued_comment->Visible) { // issued_comment ?>
	<div id="r_issued_comment" class="form-group">
		<label id="elh_inventory_store_issued_comment" for="x_issued_comment" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->issued_comment->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->issued_comment->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_issued_comment">
<textarea data-table="inventory_store" data-field="x_issued_comment" data-page="1" name="x_issued_comment" id="x_issued_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($inventory_store->issued_comment->getPlaceHolder()) ?>"<?php echo $inventory_store->issued_comment->EditAttributes() ?>><?php echo $inventory_store->issued_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_inventory_store_issued_comment">
<span<?php echo $inventory_store->issued_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->issued_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_issued_comment" data-page="1" name="x_issued_comment" id="x_issued_comment" value="<?php echo ew_HtmlEncode($inventory_store->issued_comment->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->issued_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->issued_by->Visible) { // issued_by ?>
	<div id="r_issued_by" class="form-group">
		<label id="elh_inventory_store_issued_by" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->issued_by->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->issued_by->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_issued_by">
<?php
$wrkonchange = trim(" " . @$inventory_store->issued_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_store->issued_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_issued_by" style="white-space: nowrap; z-index: 8870">
	<input type="text" name="sv_x_issued_by" id="sv_x_issued_by" value="<?php echo $inventory_store->issued_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_store->issued_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_store->issued_by->getPlaceHolder()) ?>"<?php echo $inventory_store->issued_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_issued_by" data-page="1" data-value-separator="<?php echo $inventory_store->issued_by->DisplayValueSeparatorAttribute() ?>" name="x_issued_by" id="x_issued_by" value="<?php echo ew_HtmlEncode($inventory_store->issued_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_storeedit.CreateAutoSuggest({"id":"x_issued_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_store_issued_by">
<span<?php echo $inventory_store->issued_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->issued_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_issued_by" data-page="1" name="x_issued_by" id="x_issued_by" value="<?php echo ew_HtmlEncode($inventory_store->issued_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->issued_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label id="elh_inventory_store_approver_date" for="x_approver_date" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->approver_date->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->approver_date->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_approver_date">
<input type="text" data-table="inventory_store" data-field="x_approver_date" data-page="1" name="x_approver_date" id="x_approver_date" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_store->approver_date->getPlaceHolder()) ?>" value="<?php echo $inventory_store->approver_date->EditValue ?>"<?php echo $inventory_store->approver_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_approver_date">
<span<?php echo $inventory_store->approver_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->approver_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_approver_date" data-page="1" name="x_approver_date" id="x_approver_date" value="<?php echo ew_HtmlEncode($inventory_store->approver_date->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->approver_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_inventory_store_approver_action" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->approver_action->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->approver_action->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="inventory_store" data-field="x_approver_action" data-page="1" data-value-separator="<?php echo $inventory_store->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $inventory_store->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory_store->approver_action->RadioButtonListHtml(FALSE, "x_approver_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_inventory_store_approver_action">
<span<?php echo $inventory_store->approver_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->approver_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_approver_action" data-page="1" name="x_approver_action" id="x_approver_action" value="<?php echo ew_HtmlEncode($inventory_store->approver_action->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->approved_comment->Visible) { // approved_comment ?>
	<div id="r_approved_comment" class="form-group">
		<label id="elh_inventory_store_approved_comment" for="x_approved_comment" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->approved_comment->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->approved_comment->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_approved_comment">
<textarea data-table="inventory_store" data-field="x_approved_comment" data-page="1" name="x_approved_comment" id="x_approved_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($inventory_store->approved_comment->getPlaceHolder()) ?>"<?php echo $inventory_store->approved_comment->EditAttributes() ?>><?php echo $inventory_store->approved_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_inventory_store_approved_comment">
<span<?php echo $inventory_store->approved_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->approved_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_approved_comment" data-page="1" name="x_approved_comment" id="x_approved_comment" value="<?php echo ew_HtmlEncode($inventory_store->approved_comment->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->approved_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_inventory_store_approved_by" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->approved_by->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->approved_by->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_approved_by">
<?php
$wrkonchange = trim(" " . @$inventory_store->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_store->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $inventory_store->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_store->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_store->approved_by->getPlaceHolder()) ?>"<?php echo $inventory_store->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_approved_by" data-page="1" data-value-separator="<?php echo $inventory_store->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($inventory_store->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_storeedit.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_store_approved_by">
<span<?php echo $inventory_store->approved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->approved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_approved_by" data-page="1" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($inventory_store->approved_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label id="elh_inventory_store_verified_date" for="x_verified_date" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->verified_date->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->verified_date->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_verified_date">
<input type="text" data-table="inventory_store" data-field="x_verified_date" data-page="1" name="x_verified_date" id="x_verified_date" placeholder="<?php echo ew_HtmlEncode($inventory_store->verified_date->getPlaceHolder()) ?>" value="<?php echo $inventory_store->verified_date->EditValue ?>"<?php echo $inventory_store->verified_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_store_verified_date">
<span<?php echo $inventory_store->verified_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->verified_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_verified_date" data-page="1" name="x_verified_date" id="x_verified_date" value="<?php echo ew_HtmlEncode($inventory_store->verified_date->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->verified_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label id="elh_inventory_store_verified_action" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->verified_action->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->verified_action->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="inventory_store" data-field="x_verified_action" data-page="1" data-value-separator="<?php echo $inventory_store->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $inventory_store->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory_store->verified_action->RadioButtonListHtml(FALSE, "x_verified_action", 1) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_inventory_store_verified_action">
<span<?php echo $inventory_store->verified_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->verified_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_verified_action" data-page="1" name="x_verified_action" id="x_verified_action" value="<?php echo ew_HtmlEncode($inventory_store->verified_action->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->verified_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label id="elh_inventory_store_verified_comment" for="x_verified_comment" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->verified_comment->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->verified_comment->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_verified_comment">
<textarea data-table="inventory_store" data-field="x_verified_comment" data-page="1" name="x_verified_comment" id="x_verified_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($inventory_store->verified_comment->getPlaceHolder()) ?>"<?php echo $inventory_store->verified_comment->EditAttributes() ?>><?php echo $inventory_store->verified_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_inventory_store_verified_comment">
<span<?php echo $inventory_store->verified_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->verified_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_verified_comment" data-page="1" name="x_verified_comment" id="x_verified_comment" value="<?php echo ew_HtmlEncode($inventory_store->verified_comment->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->verified_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label id="elh_inventory_store_verified_by" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->verified_by->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->verified_by->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_verified_by">
<?php
$wrkonchange = trim(" " . @$inventory_store->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_store->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8790">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $inventory_store->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory_store->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_store->verified_by->getPlaceHolder()) ?>"<?php echo $inventory_store->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_verified_by" data-page="1" data-value-separator="<?php echo $inventory_store->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($inventory_store->verified_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_storeedit.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_store_verified_by">
<span<?php echo $inventory_store->verified_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->verified_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_verified_by" data-page="1" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($inventory_store->verified_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->verified_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory_store->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_inventory_store_status" class="<?php echo $inventory_store_edit->LeftColumnClass ?>"><?php echo $inventory_store->status->FldCaption() ?></label>
		<div class="<?php echo $inventory_store_edit->RightColumnClass ?>"><div<?php echo $inventory_store->status->CellAttributes() ?>>
<?php if ($inventory_store->CurrentAction <> "F") { ?>
<span id="el_inventory_store_status">
<?php
$wrkonchange = trim(" " . @$inventory_store->status->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory_store->status->EditAttrs["onchange"] = "";
?>
<span id="as_x_status" style="white-space: nowrap; z-index: 8780">
	<input type="text" name="sv_x_status" id="sv_x_status" value="<?php echo $inventory_store->status->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory_store->status->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory_store->status->getPlaceHolder()) ?>"<?php echo $inventory_store->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_status" data-page="1" data-value-separator="<?php echo $inventory_store->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($inventory_store->status->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventory_storeedit.CreateAutoSuggest({"id":"x_status","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_store_status">
<span<?php echo $inventory_store->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory_store->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory_store" data-field="x_status" data-page="1" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($inventory_store->status->FormValue) ?>">
<?php } ?>
<?php echo $inventory_store->status->CustomMsg ?></div></div>
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
$('#x_status').attr('readonly',true);
</script>
<?php include_once "footer.php" ?>
<?php
$inventory_store_edit->Page_Terminate();
?>
