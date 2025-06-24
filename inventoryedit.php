<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "inventoryinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$inventory_edit = NULL; // Initialize page object first

class cinventory_edit extends cinventory {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'inventory';

	// Page object name
	var $PageObjName = 'inventory_edit';

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

		// Table object (inventory)
		if (!isset($GLOBALS["inventory"]) || get_class($GLOBALS["inventory"]) == "cinventory") {
			$GLOBALS["inventory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventory"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventory');

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
				$this->Page_Terminate(ew_GetUrl("inventorylist.php"));
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
		$this->date_recieved->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity->SetVisibility();
		$this->type->SetVisibility();
		$this->capacity->SetVisibility();
		$this->recieved_by->SetVisibility();
		$this->statuss->SetVisibility();
		$this->recieved_action->SetVisibility();
		$this->recieved_comment->SetVisibility();
		$this->date_approved->SetVisibility();
		$this->approver_action->SetVisibility();
		$this->approver_comment->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->verified_date->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
		$this->verified_by->SetVisibility();

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
		global $EW_EXPORT, $inventory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventory);
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
					if ($pageName == "inventoryview.php")
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
			$this->Page_Terminate("inventorylist.php"); // Return to list page
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
					$this->Page_Terminate("inventorylist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "inventorylist.php")
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
		if (!$this->date_recieved->FldIsDetailKey) {
			$this->date_recieved->setFormValue($objForm->GetValue("x_date_recieved"));
			$this->date_recieved->CurrentValue = ew_UnFormatDateTime($this->date_recieved->CurrentValue, 17);
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
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->capacity->FldIsDetailKey) {
			$this->capacity->setFormValue($objForm->GetValue("x_capacity"));
		}
		if (!$this->recieved_by->FldIsDetailKey) {
			$this->recieved_by->setFormValue($objForm->GetValue("x_recieved_by"));
		}
		if (!$this->statuss->FldIsDetailKey) {
			$this->statuss->setFormValue($objForm->GetValue("x_statuss"));
		}
		if (!$this->recieved_action->FldIsDetailKey) {
			$this->recieved_action->setFormValue($objForm->GetValue("x_recieved_action"));
		}
		if (!$this->recieved_comment->FldIsDetailKey) {
			$this->recieved_comment->setFormValue($objForm->GetValue("x_recieved_comment"));
		}
		if (!$this->date_approved->FldIsDetailKey) {
			$this->date_approved->setFormValue($objForm->GetValue("x_date_approved"));
			$this->date_approved->CurrentValue = ew_UnFormatDateTime($this->date_approved->CurrentValue, 17);
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
		if (!$this->verified_date->FldIsDetailKey) {
			$this->verified_date->setFormValue($objForm->GetValue("x_verified_date"));
			$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 17);
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->date_recieved->CurrentValue = $this->date_recieved->FormValue;
		$this->date_recieved->CurrentValue = ew_UnFormatDateTime($this->date_recieved->CurrentValue, 17);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->material_name->CurrentValue = $this->material_name->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->capacity->CurrentValue = $this->capacity->FormValue;
		$this->recieved_by->CurrentValue = $this->recieved_by->FormValue;
		$this->statuss->CurrentValue = $this->statuss->FormValue;
		$this->recieved_action->CurrentValue = $this->recieved_action->FormValue;
		$this->recieved_comment->CurrentValue = $this->recieved_comment->FormValue;
		$this->date_approved->CurrentValue = $this->date_approved->FormValue;
		$this->date_approved->CurrentValue = ew_UnFormatDateTime($this->date_approved->CurrentValue, 17);
		$this->approver_action->CurrentValue = $this->approver_action->FormValue;
		$this->approver_comment->CurrentValue = $this->approver_comment->FormValue;
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->verified_date->CurrentValue = $this->verified_date->FormValue;
		$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 17);
		$this->verified_action->CurrentValue = $this->verified_action->FormValue;
		$this->verified_comment->CurrentValue = $this->verified_comment->FormValue;
		$this->verified_by->CurrentValue = $this->verified_by->FormValue;
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
		$this->staff_id->setDbValue($row['staff_id']);
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity->setDbValue($row['quantity']);
		$this->type->setDbValue($row['type']);
		$this->capacity->setDbValue($row['capacity']);
		$this->recieved_by->setDbValue($row['recieved_by']);
		$this->statuss->setDbValue($row['statuss']);
		$this->recieved_action->setDbValue($row['recieved_action']);
		$this->recieved_comment->setDbValue($row['recieved_comment']);
		$this->date_approved->setDbValue($row['date_approved']);
		$this->approver_action->setDbValue($row['approver_action']);
		$this->approver_comment->setDbValue($row['approver_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_date->setDbValue($row['verified_date']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->verified_by->setDbValue($row['verified_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_recieved'] = NULL;
		$row['reference_id'] = NULL;
		$row['staff_id'] = NULL;
		$row['material_name'] = NULL;
		$row['quantity'] = NULL;
		$row['type'] = NULL;
		$row['capacity'] = NULL;
		$row['recieved_by'] = NULL;
		$row['statuss'] = NULL;
		$row['recieved_action'] = NULL;
		$row['recieved_comment'] = NULL;
		$row['date_approved'] = NULL;
		$row['approver_action'] = NULL;
		$row['approver_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_date'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['verified_by'] = NULL;
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
		$this->staff_id->DbValue = $row['staff_id'];
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity->DbValue = $row['quantity'];
		$this->type->DbValue = $row['type'];
		$this->capacity->DbValue = $row['capacity'];
		$this->recieved_by->DbValue = $row['recieved_by'];
		$this->statuss->DbValue = $row['statuss'];
		$this->recieved_action->DbValue = $row['recieved_action'];
		$this->recieved_comment->DbValue = $row['recieved_comment'];
		$this->date_approved->DbValue = $row['date_approved'];
		$this->approver_action->DbValue = $row['approver_action'];
		$this->approver_comment->DbValue = $row['approver_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_date->DbValue = $row['verified_date'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
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
		// date_recieved
		// reference_id
		// staff_id
		// material_name
		// quantity
		// type
		// capacity
		// recieved_by
		// statuss
		// recieved_action
		// recieved_comment
		// date_approved
		// approver_action
		// approver_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by

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
		$this->material_name->ViewValue = $this->material_name->CurrentValue;
		$this->material_name->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// recieved_by
		$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
		if (strval($this->recieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

		// statuss
		if (strval($this->statuss->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
		$sWhereWrk = "";
		$this->statuss->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->statuss->ViewValue = $this->statuss->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->statuss->ViewValue = $this->statuss->CurrentValue;
			}
		} else {
			$this->statuss->ViewValue = NULL;
		}
		$this->statuss->ViewCustomAttributes = "";

		// recieved_action
		if (strval($this->recieved_action->CurrentValue) <> "") {
			$this->recieved_action->ViewValue = $this->recieved_action->OptionCaption($this->recieved_action->CurrentValue);
		} else {
			$this->recieved_action->ViewValue = NULL;
		}
		$this->recieved_action->ViewCustomAttributes = "";

		// recieved_comment
		$this->recieved_comment->ViewValue = $this->recieved_comment->CurrentValue;
		$this->recieved_comment->ViewCustomAttributes = "";

		// date_approved
		$this->date_approved->ViewValue = $this->date_approved->CurrentValue;
		$this->date_approved->ViewValue = ew_FormatDateTime($this->date_approved->ViewValue, 17);
		$this->date_approved->ViewCustomAttributes = "";

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
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 17);
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
		$sSqlWrk .= " ORDER BY `id`";
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

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";
			$this->capacity->TooltipValue = "";

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";
			$this->recieved_by->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

			// recieved_action
			$this->recieved_action->LinkCustomAttributes = "";
			$this->recieved_action->HrefValue = "";
			$this->recieved_action->TooltipValue = "";

			// recieved_comment
			$this->recieved_comment->LinkCustomAttributes = "";
			$this->recieved_comment->HrefValue = "";
			$this->recieved_comment->TooltipValue = "";

			// date_approved
			$this->date_approved->LinkCustomAttributes = "";
			$this->date_approved->HrefValue = "";
			$this->date_approved->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// date_recieved
			$this->date_recieved->EditAttrs["class"] = "form-control";
			$this->date_recieved->EditCustomAttributes = "";
			$this->date_recieved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_recieved->CurrentValue, 17));
			$this->date_recieved->PlaceHolder = ew_RemoveHtml($this->date_recieved->FldCaption());

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
			$this->material_name->EditValue = ew_HtmlEncode($this->material_name->CurrentValue);
			$this->material_name->PlaceHolder = ew_RemoveHtml($this->material_name->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);
			$this->type->PlaceHolder = ew_RemoveHtml($this->type->FldCaption());

			// capacity
			$this->capacity->EditAttrs["class"] = "form-control";
			$this->capacity->EditCustomAttributes = "";
			$this->capacity->EditValue = ew_HtmlEncode($this->capacity->CurrentValue);
			$this->capacity->PlaceHolder = ew_RemoveHtml($this->capacity->FldCaption());

			// recieved_by
			$this->recieved_by->EditAttrs["class"] = "form-control";
			$this->recieved_by->EditCustomAttributes = "";
			$this->recieved_by->EditValue = ew_HtmlEncode($this->recieved_by->CurrentValue);
			if (strval($this->recieved_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->recieved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->recieved_by->EditValue = $this->recieved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->recieved_by->EditValue = ew_HtmlEncode($this->recieved_by->CurrentValue);
				}
			} else {
				$this->recieved_by->EditValue = NULL;
			}
			$this->recieved_by->PlaceHolder = ew_RemoveHtml($this->recieved_by->FldCaption());

			// statuss
			$this->statuss->EditAttrs["class"] = "form-control";
			$this->statuss->EditCustomAttributes = "";
			if (trim(strval($this->statuss->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `statuss`";
			$sWhereWrk = "";
			$this->statuss->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->statuss->EditValue = $arwrk;

			// recieved_action
			$this->recieved_action->EditCustomAttributes = "";
			$this->recieved_action->EditValue = $this->recieved_action->Options(FALSE);

			// recieved_comment
			$this->recieved_comment->EditAttrs["class"] = "form-control";
			$this->recieved_comment->EditCustomAttributes = "";
			$this->recieved_comment->EditValue = ew_HtmlEncode($this->recieved_comment->CurrentValue);
			$this->recieved_comment->PlaceHolder = ew_RemoveHtml($this->recieved_comment->FldCaption());

			// date_approved
			$this->date_approved->EditAttrs["class"] = "form-control";
			$this->date_approved->EditCustomAttributes = "";
			$this->date_approved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_approved->CurrentValue, 17));
			$this->date_approved->PlaceHolder = ew_RemoveHtml($this->date_approved->FldCaption());

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
			$this->verified_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->verified_date->CurrentValue, 17));
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
			$sSqlWrk .= " ORDER BY `id`";
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

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// date_recieved
			$this->date_recieved->LinkCustomAttributes = "";
			$this->date_recieved->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";

			// capacity
			$this->capacity->LinkCustomAttributes = "";
			$this->capacity->HrefValue = "";

			// recieved_by
			$this->recieved_by->LinkCustomAttributes = "";
			$this->recieved_by->HrefValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";

			// recieved_action
			$this->recieved_action->LinkCustomAttributes = "";
			$this->recieved_action->HrefValue = "";

			// recieved_comment
			$this->recieved_comment->LinkCustomAttributes = "";
			$this->recieved_comment->HrefValue = "";

			// date_approved
			$this->date_approved->LinkCustomAttributes = "";
			$this->date_approved->HrefValue = "";

			// approver_action
			$this->approver_action->LinkCustomAttributes = "";
			$this->approver_action->HrefValue = "";

			// approver_comment
			$this->approver_comment->LinkCustomAttributes = "";
			$this->approver_comment->HrefValue = "";

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
		if (!$this->date_recieved->FldIsDetailKey && !is_null($this->date_recieved->FormValue) && $this->date_recieved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date_recieved->FldCaption(), $this->date_recieved->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->date_recieved->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_recieved->FldErrMsg());
		}
		if (!$this->reference_id->FldIsDetailKey && !is_null($this->reference_id->FormValue) && $this->reference_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reference_id->FldCaption(), $this->reference_id->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if (!$this->material_name->FldIsDetailKey && !is_null($this->material_name->FormValue) && $this->material_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->material_name->FldCaption(), $this->material_name->ReqErrMsg));
		}
		if (!$this->quantity->FldIsDetailKey && !is_null($this->quantity->FormValue) && $this->quantity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity->FldCaption(), $this->quantity->ReqErrMsg));
		}
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type->FldCaption(), $this->type->ReqErrMsg));
		}
		if (!$this->capacity->FldIsDetailKey && !is_null($this->capacity->FormValue) && $this->capacity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->capacity->FldCaption(), $this->capacity->ReqErrMsg));
		}
		if (!$this->recieved_by->FldIsDetailKey && !is_null($this->recieved_by->FormValue) && $this->recieved_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recieved_by->FldCaption(), $this->recieved_by->ReqErrMsg));
		}
		if ($this->recieved_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recieved_action->FldCaption(), $this->recieved_action->ReqErrMsg));
		}
		if (!$this->recieved_comment->FldIsDetailKey && !is_null($this->recieved_comment->FormValue) && $this->recieved_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->recieved_comment->FldCaption(), $this->recieved_comment->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->date_approved->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_approved->FldErrMsg());
		}
		if ($this->approver_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approver_action->FldCaption(), $this->approver_action->ReqErrMsg));
		}
		if (!$this->approver_comment->FldIsDetailKey && !is_null($this->approver_comment->FormValue) && $this->approver_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approver_comment->FldCaption(), $this->approver_comment->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->verified_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->verified_date->FldErrMsg());
		}
		if ($this->verified_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->verified_action->FldCaption(), $this->verified_action->ReqErrMsg));
		}
		if (!$this->verified_comment->FldIsDetailKey && !is_null($this->verified_comment->FormValue) && $this->verified_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->verified_comment->FldCaption(), $this->verified_comment->ReqErrMsg));
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

			// date_recieved
			$this->date_recieved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_recieved->CurrentValue, 17), ew_CurrentDate(), $this->date_recieved->ReadOnly);

			// reference_id
			$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, "", $this->reference_id->ReadOnly);

			// staff_id
			$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, "", $this->staff_id->ReadOnly);

			// material_name
			$this->material_name->SetDbValueDef($rsnew, $this->material_name->CurrentValue, "", $this->material_name->ReadOnly);

			// quantity
			$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, "", $this->quantity->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, "", $this->type->ReadOnly);

			// capacity
			$this->capacity->SetDbValueDef($rsnew, $this->capacity->CurrentValue, "", $this->capacity->ReadOnly);

			// recieved_by
			$this->recieved_by->SetDbValueDef($rsnew, $this->recieved_by->CurrentValue, 0, $this->recieved_by->ReadOnly);

			// statuss
			$this->statuss->SetDbValueDef($rsnew, $this->statuss->CurrentValue, NULL, $this->statuss->ReadOnly);

			// recieved_action
			$this->recieved_action->SetDbValueDef($rsnew, $this->recieved_action->CurrentValue, 0, $this->recieved_action->ReadOnly);

			// recieved_comment
			$this->recieved_comment->SetDbValueDef($rsnew, $this->recieved_comment->CurrentValue, "", $this->recieved_comment->ReadOnly);

			// date_approved
			$this->date_approved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_approved->CurrentValue, 17), NULL, $this->date_approved->ReadOnly);

			// approver_action
			$this->approver_action->SetDbValueDef($rsnew, $this->approver_action->CurrentValue, 0, $this->approver_action->ReadOnly);

			// approver_comment
			$this->approver_comment->SetDbValueDef($rsnew, $this->approver_comment->CurrentValue, "", $this->approver_comment->ReadOnly);

			// approved_by
			$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, $this->approved_by->ReadOnly);

			// verified_date
			$this->verified_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->verified_date->CurrentValue, 17), NULL, $this->verified_date->ReadOnly);

			// verified_action
			$this->verified_action->SetDbValueDef($rsnew, $this->verified_action->CurrentValue, 0, $this->verified_action->ReadOnly);

			// verified_comment
			$this->verified_comment->SetDbValueDef($rsnew, $this->verified_comment->CurrentValue, "", $this->verified_comment->ReadOnly);

			// verified_by
			$this->verified_by->SetDbValueDef($rsnew, $this->verified_by->CurrentValue, NULL, $this->verified_by->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inventorylist.php"), "", $this->TableVar, TRUE);
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
		case "x_recieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_statuss":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
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
			$sSqlWrk .= " ORDER BY `id`";
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
		case "x_recieved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->recieved_by) . "',COALESCE(`lastname`,''),'" . ew_ValueSeparator(2, $this->recieved_by) . "',COALESCE(`staffno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
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
			$sSqlWrk .= " ORDER BY `id`";
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
if (!isset($inventory_edit)) $inventory_edit = new cinventory_edit();

// Page init
$inventory_edit->Page_Init();

// Page main
$inventory_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventory_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finventoryedit = new ew_Form("finventoryedit", "edit");

// Validate form
finventoryedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_date_recieved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->date_recieved->FldCaption(), $inventory->date_recieved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_recieved");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->date_recieved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reference_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->reference_id->FldCaption(), $inventory->reference_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->staff_id->FldCaption(), $inventory->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_material_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->material_name->FldCaption(), $inventory->material_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->quantity->FldCaption(), $inventory->quantity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->type->FldCaption(), $inventory->type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_capacity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->capacity->FldCaption(), $inventory->capacity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recieved_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->recieved_by->FldCaption(), $inventory->recieved_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recieved_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->recieved_action->FldCaption(), $inventory->recieved_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_recieved_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->recieved_comment->FldCaption(), $inventory->recieved_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date_approved");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->date_approved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approver_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->approver_action->FldCaption(), $inventory->approver_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approver_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->approver_comment->FldCaption(), $inventory->approver_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($inventory->verified_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->verified_action->FldCaption(), $inventory->verified_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $inventory->verified_comment->FldCaption(), $inventory->verified_comment->ReqErrMsg)) ?>");

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
finventoryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finventoryedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finventoryedit.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventoryedit.Lists["x_staff_id"].Data = "<?php echo $inventory_edit->staff_id->LookupFilterQuery(FALSE, "edit") ?>";
finventoryedit.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_edit->staff_id->LookupFilterQuery(TRUE, "edit"))) ?>;
finventoryedit.Lists["x_recieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventoryedit.Lists["x_recieved_by"].Data = "<?php echo $inventory_edit->recieved_by->LookupFilterQuery(FALSE, "edit") ?>";
finventoryedit.AutoSuggests["x_recieved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_edit->recieved_by->LookupFilterQuery(TRUE, "edit"))) ?>;
finventoryedit.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
finventoryedit.Lists["x_statuss"].Data = "<?php echo $inventory_edit->statuss->LookupFilterQuery(FALSE, "edit") ?>";
finventoryedit.Lists["x_recieved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventoryedit.Lists["x_recieved_action"].Options = <?php echo json_encode($inventory_edit->recieved_action->Options()) ?>;
finventoryedit.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventoryedit.Lists["x_approver_action"].Options = <?php echo json_encode($inventory_edit->approver_action->Options()) ?>;
finventoryedit.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventoryedit.Lists["x_approved_by"].Data = "<?php echo $inventory_edit->approved_by->LookupFilterQuery(FALSE, "edit") ?>";
finventoryedit.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_edit->approved_by->LookupFilterQuery(TRUE, "edit"))) ?>;
finventoryedit.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finventoryedit.Lists["x_verified_action"].Options = <?php echo json_encode($inventory_edit->verified_action->Options()) ?>;
finventoryedit.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
finventoryedit.Lists["x_verified_by"].Data = "<?php echo $inventory_edit->verified_by->LookupFilterQuery(FALSE, "edit") ?>";
finventoryedit.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $inventory_edit->verified_by->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
//$('#r_statuss').hide();

</script>
<?php $inventory_edit->ShowPageHeader(); ?>
<?php
$inventory_edit->ShowMessage();
?>
<?php if (!$inventory_edit->IsModal) { ?>
<?php if ($inventory->CurrentAction <> "F") { // Confirm page ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($inventory_edit->Pager)) $inventory_edit->Pager = new cPrevNextPager($inventory_edit->StartRec, $inventory_edit->DisplayRecs, $inventory_edit->TotalRecs, $inventory_edit->AutoHidePager) ?>
<?php if ($inventory_edit->Pager->RecordCount > 0 && $inventory_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($inventory_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $inventory_edit->PageUrl() ?>start=<?php echo $inventory_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($inventory_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $inventory_edit->PageUrl() ?>start=<?php echo $inventory_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $inventory_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($inventory_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $inventory_edit->PageUrl() ?>start=<?php echo $inventory_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($inventory_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $inventory_edit->PageUrl() ?>start=<?php echo $inventory_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $inventory_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="finventoryedit" id="finventoryedit" class="<?php echo $inventory_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inventory_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inventory_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inventory">
<?php if ($inventory->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_edit" id="a_edit" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($inventory_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($inventory->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_inventory_id" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->id->FldCaption() ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->id->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_id">
<span<?php echo $inventory->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($inventory->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_inventory_id">
<span<?php echo $inventory->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($inventory->id->FormValue) ?>">
<?php } ?>
<?php echo $inventory->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->date_recieved->Visible) { // date_recieved ?>
	<div id="r_date_recieved" class="form-group">
		<label id="elh_inventory_date_recieved" for="x_date_recieved" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->date_recieved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->date_recieved->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_date_recieved">
<input type="text" data-table="inventory" data-field="x_date_recieved" data-format="17" name="x_date_recieved" id="x_date_recieved" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->date_recieved->getPlaceHolder()) ?>" value="<?php echo $inventory->date_recieved->EditValue ?>"<?php echo $inventory->date_recieved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_date_recieved">
<span<?php echo $inventory->date_recieved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->date_recieved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_date_recieved" name="x_date_recieved" id="x_date_recieved" value="<?php echo ew_HtmlEncode($inventory->date_recieved->FormValue) ?>">
<?php } ?>
<?php echo $inventory->date_recieved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_inventory_reference_id" for="x_reference_id" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->reference_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->reference_id->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_reference_id">
<input type="text" data-table="inventory" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->reference_id->getPlaceHolder()) ?>" value="<?php echo $inventory->reference_id->EditValue ?>"<?php echo $inventory->reference_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_reference_id">
<span<?php echo $inventory->reference_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->reference_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" value="<?php echo ew_HtmlEncode($inventory->reference_id->FormValue) ?>">
<?php } ?>
<?php echo $inventory->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_inventory_staff_id" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->staff_id->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_staff_id">
<?php
$wrkonchange = trim(" " . @$inventory->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $inventory->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory->staff_id->getPlaceHolder()) ?>"<?php echo $inventory->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory" data-field="x_staff_id" data-value-separator="<?php echo $inventory->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($inventory->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventoryedit.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_staff_id">
<span<?php echo $inventory->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_staff_id" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($inventory->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $inventory->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label id="elh_inventory_material_name" for="x_material_name" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->material_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->material_name->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_material_name">
<input type="text" data-table="inventory" data-field="x_material_name" name="x_material_name" id="x_material_name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->material_name->getPlaceHolder()) ?>" value="<?php echo $inventory->material_name->EditValue ?>"<?php echo $inventory->material_name->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_material_name">
<span<?php echo $inventory->material_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->material_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_material_name" name="x_material_name" id="x_material_name" value="<?php echo ew_HtmlEncode($inventory->material_name->FormValue) ?>">
<?php } ?>
<?php echo $inventory->material_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label id="elh_inventory_quantity" for="x_quantity" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->quantity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->quantity->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_quantity">
<input type="text" data-table="inventory" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->quantity->getPlaceHolder()) ?>" value="<?php echo $inventory->quantity->EditValue ?>"<?php echo $inventory->quantity->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_quantity">
<span<?php echo $inventory->quantity->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->quantity->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_quantity" name="x_quantity" id="x_quantity" value="<?php echo ew_HtmlEncode($inventory->quantity->FormValue) ?>">
<?php } ?>
<?php echo $inventory->quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_inventory_type" for="x_type" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->type->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_type">
<input type="text" data-table="inventory" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->type->getPlaceHolder()) ?>" value="<?php echo $inventory->type->EditValue ?>"<?php echo $inventory->type->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_type">
<span<?php echo $inventory->type->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->type->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_type" name="x_type" id="x_type" value="<?php echo ew_HtmlEncode($inventory->type->FormValue) ?>">
<?php } ?>
<?php echo $inventory->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->capacity->Visible) { // capacity ?>
	<div id="r_capacity" class="form-group">
		<label id="elh_inventory_capacity" for="x_capacity" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->capacity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->capacity->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_capacity">
<input type="text" data-table="inventory" data-field="x_capacity" name="x_capacity" id="x_capacity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($inventory->capacity->getPlaceHolder()) ?>" value="<?php echo $inventory->capacity->EditValue ?>"<?php echo $inventory->capacity->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_capacity">
<span<?php echo $inventory->capacity->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->capacity->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_capacity" name="x_capacity" id="x_capacity" value="<?php echo ew_HtmlEncode($inventory->capacity->FormValue) ?>">
<?php } ?>
<?php echo $inventory->capacity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->recieved_by->Visible) { // recieved_by ?>
	<div id="r_recieved_by" class="form-group">
		<label id="elh_inventory_recieved_by" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->recieved_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->recieved_by->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_recieved_by">
<?php
$wrkonchange = trim(" " . @$inventory->recieved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory->recieved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_recieved_by" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_recieved_by" id="sv_x_recieved_by" value="<?php echo $inventory->recieved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->recieved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory->recieved_by->getPlaceHolder()) ?>"<?php echo $inventory->recieved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory" data-field="x_recieved_by" data-value-separator="<?php echo $inventory->recieved_by->DisplayValueSeparatorAttribute() ?>" name="x_recieved_by" id="x_recieved_by" value="<?php echo ew_HtmlEncode($inventory->recieved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventoryedit.CreateAutoSuggest({"id":"x_recieved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_recieved_by">
<span<?php echo $inventory->recieved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->recieved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_recieved_by" name="x_recieved_by" id="x_recieved_by" value="<?php echo ew_HtmlEncode($inventory->recieved_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory->recieved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label id="elh_inventory_statuss" for="x_statuss" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->statuss->FldCaption() ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->statuss->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_statuss">
<select data-table="inventory" data-field="x_statuss" data-value-separator="<?php echo $inventory->statuss->DisplayValueSeparatorAttribute() ?>" id="x_statuss" name="x_statuss"<?php echo $inventory->statuss->EditAttributes() ?>>
<?php echo $inventory->statuss->SelectOptionListHtml("x_statuss") ?>
</select>
</span>
<?php } else { ?>
<span id="el_inventory_statuss">
<span<?php echo $inventory->statuss->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->statuss->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_statuss" name="x_statuss" id="x_statuss" value="<?php echo ew_HtmlEncode($inventory->statuss->FormValue) ?>">
<?php } ?>
<?php echo $inventory->statuss->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->recieved_action->Visible) { // recieved_action ?>
	<div id="r_recieved_action" class="form-group">
		<label id="elh_inventory_recieved_action" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->recieved_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->recieved_action->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_recieved_action">
<div id="tp_x_recieved_action" class="ewTemplate"><input type="radio" data-table="inventory" data-field="x_recieved_action" data-value-separator="<?php echo $inventory->recieved_action->DisplayValueSeparatorAttribute() ?>" name="x_recieved_action" id="x_recieved_action" value="{value}"<?php echo $inventory->recieved_action->EditAttributes() ?>></div>
<div id="dsl_x_recieved_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory->recieved_action->RadioButtonListHtml(FALSE, "x_recieved_action") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_inventory_recieved_action">
<span<?php echo $inventory->recieved_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->recieved_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_recieved_action" name="x_recieved_action" id="x_recieved_action" value="<?php echo ew_HtmlEncode($inventory->recieved_action->FormValue) ?>">
<?php } ?>
<?php echo $inventory->recieved_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->recieved_comment->Visible) { // recieved_comment ?>
	<div id="r_recieved_comment" class="form-group">
		<label id="elh_inventory_recieved_comment" for="x_recieved_comment" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->recieved_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->recieved_comment->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_recieved_comment">
<textarea data-table="inventory" data-field="x_recieved_comment" name="x_recieved_comment" id="x_recieved_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($inventory->recieved_comment->getPlaceHolder()) ?>"<?php echo $inventory->recieved_comment->EditAttributes() ?>><?php echo $inventory->recieved_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_inventory_recieved_comment">
<span<?php echo $inventory->recieved_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->recieved_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_recieved_comment" name="x_recieved_comment" id="x_recieved_comment" value="<?php echo ew_HtmlEncode($inventory->recieved_comment->FormValue) ?>">
<?php } ?>
<?php echo $inventory->recieved_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->date_approved->Visible) { // date_approved ?>
	<div id="r_date_approved" class="form-group">
		<label id="elh_inventory_date_approved" for="x_date_approved" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->date_approved->FldCaption() ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->date_approved->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_date_approved">
<input type="text" data-table="inventory" data-field="x_date_approved" data-format="17" name="x_date_approved" id="x_date_approved" placeholder="<?php echo ew_HtmlEncode($inventory->date_approved->getPlaceHolder()) ?>" value="<?php echo $inventory->date_approved->EditValue ?>"<?php echo $inventory->date_approved->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_date_approved">
<span<?php echo $inventory->date_approved->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->date_approved->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_date_approved" name="x_date_approved" id="x_date_approved" value="<?php echo ew_HtmlEncode($inventory->date_approved->FormValue) ?>">
<?php } ?>
<?php echo $inventory->date_approved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_inventory_approver_action" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->approver_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->approver_action->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="inventory" data-field="x_approver_action" data-value-separator="<?php echo $inventory->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $inventory->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory->approver_action->RadioButtonListHtml(FALSE, "x_approver_action") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_inventory_approver_action">
<span<?php echo $inventory->approver_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->approver_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_approver_action" name="x_approver_action" id="x_approver_action" value="<?php echo ew_HtmlEncode($inventory->approver_action->FormValue) ?>">
<?php } ?>
<?php echo $inventory->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label id="elh_inventory_approver_comment" for="x_approver_comment" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->approver_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->approver_comment->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_approver_comment">
<textarea data-table="inventory" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($inventory->approver_comment->getPlaceHolder()) ?>"<?php echo $inventory->approver_comment->EditAttributes() ?>><?php echo $inventory->approver_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_inventory_approver_comment">
<span<?php echo $inventory->approver_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->approver_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" value="<?php echo ew_HtmlEncode($inventory->approver_comment->FormValue) ?>">
<?php } ?>
<?php echo $inventory->approver_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_inventory_approved_by" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->approved_by->FldCaption() ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->approved_by->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_approved_by">
<?php
$wrkonchange = trim(" " . @$inventory->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8840">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $inventory->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory->approved_by->getPlaceHolder()) ?>"<?php echo $inventory->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory" data-field="x_approved_by" data-value-separator="<?php echo $inventory->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($inventory->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventoryedit.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_approved_by">
<span<?php echo $inventory->approved_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->approved_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_approved_by" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($inventory->approved_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label id="elh_inventory_verified_date" for="x_verified_date" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->verified_date->FldCaption() ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->verified_date->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_verified_date">
<input type="text" data-table="inventory" data-field="x_verified_date" data-format="17" name="x_verified_date" id="x_verified_date" placeholder="<?php echo ew_HtmlEncode($inventory->verified_date->getPlaceHolder()) ?>" value="<?php echo $inventory->verified_date->EditValue ?>"<?php echo $inventory->verified_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_inventory_verified_date">
<span<?php echo $inventory->verified_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->verified_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_verified_date" name="x_verified_date" id="x_verified_date" value="<?php echo ew_HtmlEncode($inventory->verified_date->FormValue) ?>">
<?php } ?>
<?php echo $inventory->verified_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label id="elh_inventory_verified_action" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->verified_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->verified_action->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="inventory" data-field="x_verified_action" data-value-separator="<?php echo $inventory->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $inventory->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $inventory->verified_action->RadioButtonListHtml(FALSE, "x_verified_action") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_inventory_verified_action">
<span<?php echo $inventory->verified_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->verified_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_verified_action" name="x_verified_action" id="x_verified_action" value="<?php echo ew_HtmlEncode($inventory->verified_action->FormValue) ?>">
<?php } ?>
<?php echo $inventory->verified_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label id="elh_inventory_verified_comment" for="x_verified_comment" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->verified_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->verified_comment->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_verified_comment">
<textarea data-table="inventory" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($inventory->verified_comment->getPlaceHolder()) ?>"<?php echo $inventory->verified_comment->EditAttributes() ?>><?php echo $inventory->verified_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_inventory_verified_comment">
<span<?php echo $inventory->verified_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->verified_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" value="<?php echo ew_HtmlEncode($inventory->verified_comment->FormValue) ?>">
<?php } ?>
<?php echo $inventory->verified_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inventory->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label id="elh_inventory_verified_by" class="<?php echo $inventory_edit->LeftColumnClass ?>"><?php echo $inventory->verified_by->FldCaption() ?></label>
		<div class="<?php echo $inventory_edit->RightColumnClass ?>"><div<?php echo $inventory->verified_by->CellAttributes() ?>>
<?php if ($inventory->CurrentAction <> "F") { ?>
<span id="el_inventory_verified_by">
<?php
$wrkonchange = trim(" " . @$inventory->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inventory->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $inventory->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inventory->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inventory->verified_by->getPlaceHolder()) ?>"<?php echo $inventory->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inventory" data-field="x_verified_by" data-value-separator="<?php echo $inventory->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($inventory->verified_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
finventoryedit.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_inventory_verified_by">
<span<?php echo $inventory->verified_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inventory->verified_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="inventory" data-field="x_verified_by" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($inventory->verified_by->FormValue) ?>">
<?php } ?>
<?php echo $inventory->verified_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$inventory_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $inventory_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($inventory->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inventory_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finventoryedit.Init();
</script>
<?php
$inventory_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_staff_id").hide();
$("#r_recieved_by").hide();
$("#r_approved_by").hide();
$("#r_verified_by").hide();

//$('#r_statuss').hide();
$('#r_date_approved').hide();
$('#r_verified_date').hide();
$('#x_statuss').attr('readonly',true);
</script>
<?php include_once "footer.php" ?>
<?php
$inventory_edit->Page_Terminate();
?>
