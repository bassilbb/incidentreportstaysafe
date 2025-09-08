<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "issuance_store_staysafeinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$issuance_store_staysafe_add = NULL; // Initialize page object first

class cissuance_store_staysafe_add extends cissuance_store_staysafe {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'issuance_store_staysafe';

	// Page object name
	var $PageObjName = 'issuance_store_staysafe_add';

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

		// Table object (issuance_store_staysafe)
		if (!isset($GLOBALS["issuance_store_staysafe"]) || get_class($GLOBALS["issuance_store_staysafe"]) == "cissuance_store_staysafe") {
			$GLOBALS["issuance_store_staysafe"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["issuance_store_staysafe"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'issuance_store_staysafe');

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("issuance_store_staysafelist.php"));
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
		$this->reference_id->SetVisibility();
		$this->material_name->SetVisibility();
		$this->quantity_in->SetVisibility();
		$this->quantity_out->SetVisibility();
		$this->total_quantity->SetVisibility();
		$this->quantity_type->SetVisibility();
		$this->treated_by->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->statuss->SetVisibility();
		$this->issued_action->SetVisibility();
		$this->issued_comment->SetVisibility();
		$this->issued_by->SetVisibility();
		$this->approver_date->SetVisibility();
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
		global $EW_EXPORT, $issuance_store_staysafe;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($issuance_store_staysafe);
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
					if ($pageName == "issuance_store_staysafeview.php")
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("issuance_store_staysafelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "issuance_store_staysafelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "issuance_store_staysafeview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->date->CurrentValue = NULL;
		$this->date->OldValue = $this->date->CurrentValue;
		$this->reference_id->CurrentValue = NULL;
		$this->reference_id->OldValue = $this->reference_id->CurrentValue;
		$this->material_name->CurrentValue = NULL;
		$this->material_name->OldValue = $this->material_name->CurrentValue;
		$this->quantity_in->CurrentValue = NULL;
		$this->quantity_in->OldValue = $this->quantity_in->CurrentValue;
		$this->quantity_out->CurrentValue = NULL;
		$this->quantity_out->OldValue = $this->quantity_out->CurrentValue;
		$this->total_quantity->CurrentValue = NULL;
		$this->total_quantity->OldValue = $this->total_quantity->CurrentValue;
		$this->quantity_type->CurrentValue = NULL;
		$this->quantity_type->OldValue = $this->quantity_type->CurrentValue;
		$this->treated_by->CurrentValue = NULL;
		$this->treated_by->OldValue = $this->treated_by->CurrentValue;
		$this->staff_id->CurrentValue = NULL;
		$this->staff_id->OldValue = $this->staff_id->CurrentValue;
		$this->statuss->CurrentValue = 0;
		$this->issued_action->CurrentValue = NULL;
		$this->issued_action->OldValue = $this->issued_action->CurrentValue;
		$this->issued_comment->CurrentValue = NULL;
		$this->issued_comment->OldValue = $this->issued_comment->CurrentValue;
		$this->issued_by->CurrentValue = NULL;
		$this->issued_by->OldValue = $this->issued_by->CurrentValue;
		$this->approver_date->CurrentValue = NULL;
		$this->approver_date->OldValue = $this->approver_date->CurrentValue;
		$this->approver_action->CurrentValue = NULL;
		$this->approver_action->OldValue = $this->approver_action->CurrentValue;
		$this->approver_comment->CurrentValue = NULL;
		$this->approver_comment->OldValue = $this->approver_comment->CurrentValue;
		$this->approved_by->CurrentValue = NULL;
		$this->approved_by->OldValue = $this->approved_by->CurrentValue;
		$this->verified_date->CurrentValue = NULL;
		$this->verified_date->OldValue = $this->verified_date->CurrentValue;
		$this->verified_action->CurrentValue = NULL;
		$this->verified_action->OldValue = $this->verified_action->CurrentValue;
		$this->verified_comment->CurrentValue = NULL;
		$this->verified_comment->OldValue = $this->verified_comment->CurrentValue;
		$this->verified_by->CurrentValue = NULL;
		$this->verified_by->OldValue = $this->verified_by->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 17);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->material_name->FldIsDetailKey) {
			$this->material_name->setFormValue($objForm->GetValue("x_material_name"));
		}
		if (!$this->quantity_in->FldIsDetailKey) {
			$this->quantity_in->setFormValue($objForm->GetValue("x_quantity_in"));
		}
		if (!$this->quantity_out->FldIsDetailKey) {
			$this->quantity_out->setFormValue($objForm->GetValue("x_quantity_out"));
		}
		if (!$this->total_quantity->FldIsDetailKey) {
			$this->total_quantity->setFormValue($objForm->GetValue("x_total_quantity"));
		}
		if (!$this->quantity_type->FldIsDetailKey) {
			$this->quantity_type->setFormValue($objForm->GetValue("x_quantity_type"));
		}
		if (!$this->treated_by->FldIsDetailKey) {
			$this->treated_by->setFormValue($objForm->GetValue("x_treated_by"));
		}
		if (!$this->staff_id->FldIsDetailKey) {
			$this->staff_id->setFormValue($objForm->GetValue("x_staff_id"));
		}
		if (!$this->statuss->FldIsDetailKey) {
			$this->statuss->setFormValue($objForm->GetValue("x_statuss"));
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
		if (!$this->approver_comment->FldIsDetailKey) {
			$this->approver_comment->setFormValue($objForm->GetValue("x_approver_comment"));
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 17);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->material_name->CurrentValue = $this->material_name->FormValue;
		$this->quantity_in->CurrentValue = $this->quantity_in->FormValue;
		$this->quantity_out->CurrentValue = $this->quantity_out->FormValue;
		$this->total_quantity->CurrentValue = $this->total_quantity->FormValue;
		$this->quantity_type->CurrentValue = $this->quantity_type->FormValue;
		$this->treated_by->CurrentValue = $this->treated_by->FormValue;
		$this->staff_id->CurrentValue = $this->staff_id->FormValue;
		$this->statuss->CurrentValue = $this->statuss->FormValue;
		$this->issued_action->CurrentValue = $this->issued_action->FormValue;
		$this->issued_comment->CurrentValue = $this->issued_comment->FormValue;
		$this->issued_by->CurrentValue = $this->issued_by->FormValue;
		$this->approver_date->CurrentValue = $this->approver_date->FormValue;
		$this->approver_date->CurrentValue = ew_UnFormatDateTime($this->approver_date->CurrentValue, 0);
		$this->approver_action->CurrentValue = $this->approver_action->FormValue;
		$this->approver_comment->CurrentValue = $this->approver_comment->FormValue;
		$this->approved_by->CurrentValue = $this->approved_by->FormValue;
		$this->verified_date->CurrentValue = $this->verified_date->FormValue;
		$this->verified_date->CurrentValue = ew_UnFormatDateTime($this->verified_date->CurrentValue, 0);
		$this->verified_action->CurrentValue = $this->verified_action->FormValue;
		$this->verified_comment->CurrentValue = $this->verified_comment->FormValue;
		$this->verified_by->CurrentValue = $this->verified_by->FormValue;
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
		$this->material_name->setDbValue($row['material_name']);
		$this->quantity_in->setDbValue($row['quantity_in']);
		$this->quantity_out->setDbValue($row['quantity_out']);
		$this->total_quantity->setDbValue($row['total_quantity']);
		$this->quantity_type->setDbValue($row['quantity_type']);
		$this->treated_by->setDbValue($row['treated_by']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->statuss->setDbValue($row['statuss']);
		$this->issued_action->setDbValue($row['issued_action']);
		$this->issued_comment->setDbValue($row['issued_comment']);
		$this->issued_by->setDbValue($row['issued_by']);
		$this->approver_date->setDbValue($row['approver_date']);
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['date'] = $this->date->CurrentValue;
		$row['reference_id'] = $this->reference_id->CurrentValue;
		$row['material_name'] = $this->material_name->CurrentValue;
		$row['quantity_in'] = $this->quantity_in->CurrentValue;
		$row['quantity_out'] = $this->quantity_out->CurrentValue;
		$row['total_quantity'] = $this->total_quantity->CurrentValue;
		$row['quantity_type'] = $this->quantity_type->CurrentValue;
		$row['treated_by'] = $this->treated_by->CurrentValue;
		$row['staff_id'] = $this->staff_id->CurrentValue;
		$row['statuss'] = $this->statuss->CurrentValue;
		$row['issued_action'] = $this->issued_action->CurrentValue;
		$row['issued_comment'] = $this->issued_comment->CurrentValue;
		$row['issued_by'] = $this->issued_by->CurrentValue;
		$row['approver_date'] = $this->approver_date->CurrentValue;
		$row['approver_action'] = $this->approver_action->CurrentValue;
		$row['approver_comment'] = $this->approver_comment->CurrentValue;
		$row['approved_by'] = $this->approved_by->CurrentValue;
		$row['verified_date'] = $this->verified_date->CurrentValue;
		$row['verified_action'] = $this->verified_action->CurrentValue;
		$row['verified_comment'] = $this->verified_comment->CurrentValue;
		$row['verified_by'] = $this->verified_by->CurrentValue;
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
		$this->material_name->DbValue = $row['material_name'];
		$this->quantity_in->DbValue = $row['quantity_in'];
		$this->quantity_out->DbValue = $row['quantity_out'];
		$this->total_quantity->DbValue = $row['total_quantity'];
		$this->quantity_type->DbValue = $row['quantity_type'];
		$this->treated_by->DbValue = $row['treated_by'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->statuss->DbValue = $row['statuss'];
		$this->issued_action->DbValue = $row['issued_action'];
		$this->issued_comment->DbValue = $row['issued_comment'];
		$this->issued_by->DbValue = $row['issued_by'];
		$this->approver_date->DbValue = $row['approver_date'];
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
		// date
		// reference_id
		// material_name
		// quantity_in
		// quantity_out
		// total_quantity
		// quantity_type
		// treated_by
		// staff_id
		// statuss
		// issued_action
		// issued_comment
		// issued_by
		// approver_date
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

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 17);
		$this->date->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
		$sWhereWrk = "";
		$this->material_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
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

		// quantity_out
		$this->quantity_out->ViewValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// quantity_type
		$this->quantity_type->ViewValue = $this->quantity_type->CurrentValue;
		$this->quantity_type->ViewCustomAttributes = "";

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

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
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
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$this->verified_by->ViewValue = $this->verified_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
			}
		} else {
			$this->verified_by->ViewValue = NULL;
		}
		$this->verified_by->ViewCustomAttributes = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";
			$this->material_name->TooltipValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";
			$this->quantity_in->TooltipValue = "";

			// quantity_out
			$this->quantity_out->LinkCustomAttributes = "";
			$this->quantity_out->HrefValue = "";
			$this->quantity_out->TooltipValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";
			$this->total_quantity->TooltipValue = "";

			// quantity_type
			$this->quantity_type->LinkCustomAttributes = "";
			$this->quantity_type->HrefValue = "";
			$this->quantity_type->TooltipValue = "";

			// treated_by
			$this->treated_by->LinkCustomAttributes = "";
			$this->treated_by->HrefValue = "";
			$this->treated_by->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";
			$this->statuss->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 17));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->CurrentValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// material_name
			$this->material_name->EditAttrs["class"] = "form-control";
			$this->material_name->EditCustomAttributes = "";
			if (trim(strval($this->material_name->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `inventory_staysafe`";
			$sWhereWrk = "";
			$this->material_name->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->material_name->EditValue = $arwrk;

			// quantity_in
			$this->quantity_in->EditAttrs["class"] = "form-control";
			$this->quantity_in->EditCustomAttributes = "";
			$this->quantity_in->EditValue = ew_HtmlEncode($this->quantity_in->CurrentValue);
			$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

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

			// quantity_type
			$this->quantity_type->EditAttrs["class"] = "form-control";
			$this->quantity_type->EditCustomAttributes = "";
			$this->quantity_type->EditValue = ew_HtmlEncode($this->quantity_type->CurrentValue);
			$this->quantity_type->PlaceHolder = ew_RemoveHtml($this->quantity_type->FldCaption());

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

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->staff_id->EditValue = $this->staff_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->CurrentValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

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
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
					$this->verified_by->EditValue = $this->verified_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->verified_by->EditValue = ew_HtmlEncode($this->verified_by->CurrentValue);
				}
			} else {
				$this->verified_by->EditValue = NULL;
			}
			$this->verified_by->PlaceHolder = ew_RemoveHtml($this->verified_by->FldCaption());

			// Add refer script
			// date

			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// material_name
			$this->material_name->LinkCustomAttributes = "";
			$this->material_name->HrefValue = "";

			// quantity_in
			$this->quantity_in->LinkCustomAttributes = "";
			$this->quantity_in->HrefValue = "";

			// quantity_out
			$this->quantity_out->LinkCustomAttributes = "";
			$this->quantity_out->HrefValue = "";

			// total_quantity
			$this->total_quantity->LinkCustomAttributes = "";
			$this->total_quantity->HrefValue = "";

			// quantity_type
			$this->quantity_type->LinkCustomAttributes = "";
			$this->quantity_type->HrefValue = "";

			// treated_by
			$this->treated_by->LinkCustomAttributes = "";
			$this->treated_by->HrefValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";

			// statuss
			$this->statuss->LinkCustomAttributes = "";
			$this->statuss->HrefValue = "";

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
		if (!$this->date->FldIsDetailKey && !is_null($this->date->FormValue) && $this->date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date->FldCaption(), $this->date->ReqErrMsg));
		}
		if (!$this->reference_id->FldIsDetailKey && !is_null($this->reference_id->FormValue) && $this->reference_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reference_id->FldCaption(), $this->reference_id->ReqErrMsg));
		}
		if (!$this->material_name->FldIsDetailKey && !is_null($this->material_name->FormValue) && $this->material_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->material_name->FldCaption(), $this->material_name->ReqErrMsg));
		}
		if (!$this->quantity_in->FldIsDetailKey && !is_null($this->quantity_in->FormValue) && $this->quantity_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity_in->FldCaption(), $this->quantity_in->ReqErrMsg));
		}
		if (!$this->quantity_out->FldIsDetailKey && !is_null($this->quantity_out->FormValue) && $this->quantity_out->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity_out->FldCaption(), $this->quantity_out->ReqErrMsg));
		}
		if (!$this->total_quantity->FldIsDetailKey && !is_null($this->total_quantity->FormValue) && $this->total_quantity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->total_quantity->FldCaption(), $this->total_quantity->ReqErrMsg));
		}
		if (!$this->quantity_type->FldIsDetailKey && !is_null($this->quantity_type->FormValue) && $this->quantity_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity_type->FldCaption(), $this->quantity_type->ReqErrMsg));
		}
		if (!$this->treated_by->FldIsDetailKey && !is_null($this->treated_by->FormValue) && $this->treated_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->treated_by->FldCaption(), $this->treated_by->ReqErrMsg));
		}
		if (!$this->staff_id->FldIsDetailKey && !is_null($this->staff_id->FormValue) && $this->staff_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staff_id->FldCaption(), $this->staff_id->ReqErrMsg));
		}
		if ($this->issued_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->issued_action->FldCaption(), $this->issued_action->ReqErrMsg));
		}
		if (!$this->issued_comment->FldIsDetailKey && !is_null($this->issued_comment->FormValue) && $this->issued_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->issued_comment->FldCaption(), $this->issued_comment->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->approver_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->approver_date->FldErrMsg());
		}
		if ($this->approver_action->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approver_action->FldCaption(), $this->approver_action->ReqErrMsg));
		}
		if (!$this->approver_comment->FldIsDetailKey && !is_null($this->approver_comment->FormValue) && $this->approver_comment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approver_comment->FldCaption(), $this->approver_comment->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->verified_date->FormValue)) {
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// date
		$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 17), ew_CurrentDate(), FALSE);

		// reference_id
		$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, "", FALSE);

		// material_name
		$this->material_name->SetDbValueDef($rsnew, $this->material_name->CurrentValue, "", FALSE);

		// quantity_in
		$this->quantity_in->SetDbValueDef($rsnew, $this->quantity_in->CurrentValue, "", FALSE);

		// quantity_out
		$this->quantity_out->SetDbValueDef($rsnew, $this->quantity_out->CurrentValue, NULL, FALSE);

		// total_quantity
		$this->total_quantity->SetDbValueDef($rsnew, $this->total_quantity->CurrentValue, NULL, FALSE);

		// quantity_type
		$this->quantity_type->SetDbValueDef($rsnew, $this->quantity_type->CurrentValue, NULL, FALSE);

		// treated_by
		$this->treated_by->SetDbValueDef($rsnew, $this->treated_by->CurrentValue, 0, FALSE);

		// staff_id
		$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, 0, FALSE);

		// statuss
		$this->statuss->SetDbValueDef($rsnew, $this->statuss->CurrentValue, NULL, FALSE);

		// issued_action
		$this->issued_action->SetDbValueDef($rsnew, $this->issued_action->CurrentValue, NULL, FALSE);

		// issued_comment
		$this->issued_comment->SetDbValueDef($rsnew, $this->issued_comment->CurrentValue, NULL, FALSE);

		// issued_by
		$this->issued_by->SetDbValueDef($rsnew, $this->issued_by->CurrentValue, NULL, FALSE);

		// approver_date
		$this->approver_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->approver_date->CurrentValue, 0), NULL, FALSE);

		// approver_action
		$this->approver_action->SetDbValueDef($rsnew, $this->approver_action->CurrentValue, NULL, FALSE);

		// approver_comment
		$this->approver_comment->SetDbValueDef($rsnew, $this->approver_comment->CurrentValue, NULL, FALSE);

		// approved_by
		$this->approved_by->SetDbValueDef($rsnew, $this->approved_by->CurrentValue, NULL, FALSE);

		// verified_date
		$this->verified_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->verified_date->CurrentValue, 0), NULL, FALSE);

		// verified_action
		$this->verified_action->SetDbValueDef($rsnew, $this->verified_action->CurrentValue, NULL, FALSE);

		// verified_comment
		$this->verified_comment->SetDbValueDef($rsnew, $this->verified_comment->CurrentValue, NULL, FALSE);

		// verified_by
		$this->verified_by->SetDbValueDef($rsnew, $this->verified_by->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("issuance_store_staysafelist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_material_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
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
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
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
		case "x_verified_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
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
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->staff_id) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
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
		case "x_verified_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->verified_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
		ew_SetClientVar("GetInventory_StaysafeDetailsSearchModel", ew_Encrypt("SELECT `quantity`,`type` FROM `inventory_staysafe` WHERE `id`= {query_value}"));
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
if (!isset($issuance_store_staysafe_add)) $issuance_store_staysafe_add = new cissuance_store_staysafe_add();

// Page init
$issuance_store_staysafe_add->Page_Init();

// Page main
$issuance_store_staysafe_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$issuance_store_staysafe_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fissuance_store_staysafeadd = new ew_Form("fissuance_store_staysafeadd", "add");

// Validate form
fissuance_store_staysafeadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->date->FldCaption(), $issuance_store_staysafe->date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reference_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->reference_id->FldCaption(), $issuance_store_staysafe->reference_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_material_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->material_name->FldCaption(), $issuance_store_staysafe->material_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->quantity_in->FldCaption(), $issuance_store_staysafe->quantity_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity_out");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->quantity_out->FldCaption(), $issuance_store_staysafe->quantity_out->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total_quantity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->total_quantity->FldCaption(), $issuance_store_staysafe->total_quantity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->quantity_type->FldCaption(), $issuance_store_staysafe->quantity_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_treated_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->treated_by->FldCaption(), $issuance_store_staysafe->treated_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staff_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->staff_id->FldCaption(), $issuance_store_staysafe->staff_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_issued_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->issued_action->FldCaption(), $issuance_store_staysafe->issued_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_issued_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->issued_comment->FldCaption(), $issuance_store_staysafe->issued_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approver_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($issuance_store_staysafe->approver_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approver_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->approver_action->FldCaption(), $issuance_store_staysafe->approver_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approver_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->approver_comment->FldCaption(), $issuance_store_staysafe->approver_comment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($issuance_store_staysafe->verified_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_verified_action");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->verified_action->FldCaption(), $issuance_store_staysafe->verified_action->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verified_comment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $issuance_store_staysafe->verified_comment->FldCaption(), $issuance_store_staysafe->verified_comment->ReqErrMsg)) ?>");

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
fissuance_store_staysafeadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fissuance_store_staysafeadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fissuance_store_staysafeadd.Lists["x_material_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory_staysafe"};
fissuance_store_staysafeadd.Lists["x_material_name"].Data = "<?php echo $issuance_store_staysafe_add->material_name->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.Lists["x_treated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafeadd.Lists["x_treated_by"].Data = "<?php echo $issuance_store_staysafe_add->treated_by->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.AutoSuggests["x_treated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_add->treated_by->LookupFilterQuery(TRUE, "add"))) ?>;
fissuance_store_staysafeadd.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafeadd.Lists["x_staff_id"].Data = "<?php echo $issuance_store_staysafe_add->staff_id->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_add->staff_id->LookupFilterQuery(TRUE, "add"))) ?>;
fissuance_store_staysafeadd.Lists["x_statuss"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"statuss"};
fissuance_store_staysafeadd.Lists["x_statuss"].Data = "<?php echo $issuance_store_staysafe_add->statuss->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.Lists["x_issued_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fissuance_store_staysafeadd.Lists["x_issued_action"].Options = <?php echo json_encode($issuance_store_staysafe_add->issued_action->Options()) ?>;
fissuance_store_staysafeadd.Lists["x_issued_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafeadd.Lists["x_issued_by"].Data = "<?php echo $issuance_store_staysafe_add->issued_by->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.AutoSuggests["x_issued_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_add->issued_by->LookupFilterQuery(TRUE, "add"))) ?>;
fissuance_store_staysafeadd.Lists["x_approver_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fissuance_store_staysafeadd.Lists["x_approver_action"].Options = <?php echo json_encode($issuance_store_staysafe_add->approver_action->Options()) ?>;
fissuance_store_staysafeadd.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafeadd.Lists["x_approved_by"].Data = "<?php echo $issuance_store_staysafe_add->approved_by->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_add->approved_by->LookupFilterQuery(TRUE, "add"))) ?>;
fissuance_store_staysafeadd.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fissuance_store_staysafeadd.Lists["x_verified_action"].Options = <?php echo json_encode($issuance_store_staysafe_add->verified_action->Options()) ?>;
fissuance_store_staysafeadd.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fissuance_store_staysafeadd.Lists["x_verified_by"].Data = "<?php echo $issuance_store_staysafe_add->verified_by->LookupFilterQuery(FALSE, "add") ?>";
fissuance_store_staysafeadd.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $issuance_store_staysafe_add->verified_by->LookupFilterQuery(TRUE, "add"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
$(document).ready(function(){
	var inventory_quantity;
	$("#x_material_name").on("change", function() { 
	   var StoreId = this.value;

	//    alert('Hello')
		   if(StoreId!=''){

			 //alert(StoreId);
			 var resultSearchModel = ew_Ajax(ewVar.GetInventory_StaysafeDetailsSearchModel, StoreId);   

		   //alert(resultSearchModel);
			 if(resultSearchModel!=''){
				$('#x_quantity_in').val(resultSearchModel[0]);
				$('#x_quantity_type').val(resultSearchModel[1]);

				//$('#x_capacity').val(resultSearchModel[2]);
				}
				}
			})
			$('#x_quantity_out').on("change", function() { 
				var quantity = this.value;
				inventory_quantity = parseInt($('#x_quantity_in').val());
				if(quantity!=''){
					quantity = parseInt(quantity)

					 //alert(inventory_quantity + 'Inputted');
					// alert(quantity + 'In stock');

					if((quantity <= inventory_quantity) && (quantity > 0)){
						var getBal =  inventory_quantity - quantity ;

						//var getBal =  quantity - inventory_quantity  ;
						// alert(getBal + 'Balance');

						getBal = parseInt(getBal);

						//alert(getBal);
						if(getBal < 0 ){

						    //alert('Here');
						   //alertify('Quantity inputted can not be less than quantity in Stock');
						   //alert('Quantity inputted can not be zero and can not be higher than quantity in stock!');

						   $('#x_quantity_out').val('');
						   $('#x_total_quantity').val('');
						}else{
							 $('#x_total_quantity').val(getBal);
						}

						// alert(inventory_quantity + '2')
						//$('#x_total_quantity').val(getBal);

					}
					 else {

					 	  //alert('Quantity inputted can not be zero and can not be higher than quantity in stock!');
						   $('#x_quantity_out').val('');
						   $('#x_total_quantity').val('');
					 }
				}else{
						$('#x_total_quantity').val('');
				}
			})
})
</script>
<?php $issuance_store_staysafe_add->ShowPageHeader(); ?>
<?php
$issuance_store_staysafe_add->ShowMessage();
?>
<form name="fissuance_store_staysafeadd" id="fissuance_store_staysafeadd" class="<?php echo $issuance_store_staysafe_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($issuance_store_staysafe_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $issuance_store_staysafe_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="issuance_store_staysafe">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($issuance_store_staysafe_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($issuance_store_staysafe->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_issuance_store_staysafe_date" for="x_date" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->date->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_date">
<input type="text" data-table="issuance_store_staysafe" data-field="x_date" data-format="17" name="x_date" id="x_date" size="30" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->date->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->date->EditValue ?>"<?php echo $issuance_store_staysafe->date->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_issuance_store_staysafe_reference_id" for="x_reference_id" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->reference_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->reference_id->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_reference_id">
<input type="text" data-table="issuance_store_staysafe" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->reference_id->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->reference_id->EditValue ?>"<?php echo $issuance_store_staysafe->reference_id->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->material_name->Visible) { // material_name ?>
	<div id="r_material_name" class="form-group">
		<label id="elh_issuance_store_staysafe_material_name" for="x_material_name" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->material_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->material_name->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_material_name">
<select data-table="issuance_store_staysafe" data-field="x_material_name" data-value-separator="<?php echo $issuance_store_staysafe->material_name->DisplayValueSeparatorAttribute() ?>" id="x_material_name" name="x_material_name"<?php echo $issuance_store_staysafe->material_name->EditAttributes() ?>>
<?php echo $issuance_store_staysafe->material_name->SelectOptionListHtml("x_material_name") ?>
</select>
</span>
<?php echo $issuance_store_staysafe->material_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_in->Visible) { // quantity_in ?>
	<div id="r_quantity_in" class="form-group">
		<label id="elh_issuance_store_staysafe_quantity_in" for="x_quantity_in" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->quantity_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->quantity_in->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_quantity_in">
<input type="text" data-table="issuance_store_staysafe" data-field="x_quantity_in" name="x_quantity_in" id="x_quantity_in" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->quantity_in->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->quantity_in->EditValue ?>"<?php echo $issuance_store_staysafe->quantity_in->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->quantity_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_out->Visible) { // quantity_out ?>
	<div id="r_quantity_out" class="form-group">
		<label id="elh_issuance_store_staysafe_quantity_out" for="x_quantity_out" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->quantity_out->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->quantity_out->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_quantity_out">
<input type="text" data-table="issuance_store_staysafe" data-field="x_quantity_out" name="x_quantity_out" id="x_quantity_out" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->quantity_out->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->quantity_out->EditValue ?>"<?php echo $issuance_store_staysafe->quantity_out->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->quantity_out->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->total_quantity->Visible) { // total_quantity ?>
	<div id="r_total_quantity" class="form-group">
		<label id="elh_issuance_store_staysafe_total_quantity" for="x_total_quantity" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->total_quantity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->total_quantity->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_total_quantity">
<input type="text" data-table="issuance_store_staysafe" data-field="x_total_quantity" name="x_total_quantity" id="x_total_quantity" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->total_quantity->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->total_quantity->EditValue ?>"<?php echo $issuance_store_staysafe->total_quantity->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->total_quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->quantity_type->Visible) { // quantity_type ?>
	<div id="r_quantity_type" class="form-group">
		<label id="elh_issuance_store_staysafe_quantity_type" for="x_quantity_type" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->quantity_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->quantity_type->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_quantity_type">
<input type="text" data-table="issuance_store_staysafe" data-field="x_quantity_type" name="x_quantity_type" id="x_quantity_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->quantity_type->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->quantity_type->EditValue ?>"<?php echo $issuance_store_staysafe->quantity_type->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->quantity_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->treated_by->Visible) { // treated_by ?>
	<div id="r_treated_by" class="form-group">
		<label id="elh_issuance_store_staysafe_treated_by" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->treated_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->treated_by->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_treated_by">
<?php
$wrkonchange = trim(" " . @$issuance_store_staysafe->treated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$issuance_store_staysafe->treated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_treated_by" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_treated_by" id="sv_x_treated_by" value="<?php echo $issuance_store_staysafe->treated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->treated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->treated_by->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->treated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="issuance_store_staysafe" data-field="x_treated_by" data-value-separator="<?php echo $issuance_store_staysafe->treated_by->DisplayValueSeparatorAttribute() ?>" name="x_treated_by" id="x_treated_by" value="<?php echo ew_HtmlEncode($issuance_store_staysafe->treated_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fissuance_store_staysafeadd.CreateAutoSuggest({"id":"x_treated_by","forceSelect":false});
</script>
</span>
<?php echo $issuance_store_staysafe->treated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_issuance_store_staysafe_staff_id" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->staff_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->staff_id->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_staff_id">
<?php
$wrkonchange = trim(" " . @$issuance_store_staysafe->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$issuance_store_staysafe->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8900">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $issuance_store_staysafe->staff_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->staff_id->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="issuance_store_staysafe" data-field="x_staff_id" data-value-separator="<?php echo $issuance_store_staysafe->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($issuance_store_staysafe->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fissuance_store_staysafeadd.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php echo $issuance_store_staysafe->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->statuss->Visible) { // statuss ?>
	<div id="r_statuss" class="form-group">
		<label id="elh_issuance_store_staysafe_statuss" for="x_statuss" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->statuss->FldCaption() ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->statuss->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_statuss">
<select data-table="issuance_store_staysafe" data-field="x_statuss" data-value-separator="<?php echo $issuance_store_staysafe->statuss->DisplayValueSeparatorAttribute() ?>" id="x_statuss" name="x_statuss"<?php echo $issuance_store_staysafe->statuss->EditAttributes() ?>>
<?php echo $issuance_store_staysafe->statuss->SelectOptionListHtml("x_statuss") ?>
</select>
</span>
<?php echo $issuance_store_staysafe->statuss->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->issued_action->Visible) { // issued_action ?>
	<div id="r_issued_action" class="form-group">
		<label id="elh_issuance_store_staysafe_issued_action" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->issued_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->issued_action->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_issued_action">
<div id="tp_x_issued_action" class="ewTemplate"><input type="radio" data-table="issuance_store_staysafe" data-field="x_issued_action" data-value-separator="<?php echo $issuance_store_staysafe->issued_action->DisplayValueSeparatorAttribute() ?>" name="x_issued_action" id="x_issued_action" value="{value}"<?php echo $issuance_store_staysafe->issued_action->EditAttributes() ?>></div>
<div id="dsl_x_issued_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $issuance_store_staysafe->issued_action->RadioButtonListHtml(FALSE, "x_issued_action") ?>
</div></div>
</span>
<?php echo $issuance_store_staysafe->issued_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->issued_comment->Visible) { // issued_comment ?>
	<div id="r_issued_comment" class="form-group">
		<label id="elh_issuance_store_staysafe_issued_comment" for="x_issued_comment" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->issued_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->issued_comment->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_issued_comment">
<textarea data-table="issuance_store_staysafe" data-field="x_issued_comment" name="x_issued_comment" id="x_issued_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->issued_comment->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->issued_comment->EditAttributes() ?>><?php echo $issuance_store_staysafe->issued_comment->EditValue ?></textarea>
</span>
<?php echo $issuance_store_staysafe->issued_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->issued_by->Visible) { // issued_by ?>
	<div id="r_issued_by" class="form-group">
		<label id="elh_issuance_store_staysafe_issued_by" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->issued_by->FldCaption() ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->issued_by->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_issued_by">
<?php
$wrkonchange = trim(" " . @$issuance_store_staysafe->issued_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$issuance_store_staysafe->issued_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_issued_by" style="white-space: nowrap; z-index: 8860">
	<input type="text" name="sv_x_issued_by" id="sv_x_issued_by" value="<?php echo $issuance_store_staysafe->issued_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->issued_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->issued_by->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->issued_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="issuance_store_staysafe" data-field="x_issued_by" data-value-separator="<?php echo $issuance_store_staysafe->issued_by->DisplayValueSeparatorAttribute() ?>" name="x_issued_by" id="x_issued_by" value="<?php echo ew_HtmlEncode($issuance_store_staysafe->issued_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fissuance_store_staysafeadd.CreateAutoSuggest({"id":"x_issued_by","forceSelect":false});
</script>
</span>
<?php echo $issuance_store_staysafe->issued_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->approver_date->Visible) { // approver_date ?>
	<div id="r_approver_date" class="form-group">
		<label id="elh_issuance_store_staysafe_approver_date" for="x_approver_date" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->approver_date->FldCaption() ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->approver_date->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_approver_date">
<input type="text" data-table="issuance_store_staysafe" data-field="x_approver_date" name="x_approver_date" id="x_approver_date" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->approver_date->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->approver_date->EditValue ?>"<?php echo $issuance_store_staysafe->approver_date->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->approver_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->approver_action->Visible) { // approver_action ?>
	<div id="r_approver_action" class="form-group">
		<label id="elh_issuance_store_staysafe_approver_action" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->approver_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->approver_action->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_approver_action">
<div id="tp_x_approver_action" class="ewTemplate"><input type="radio" data-table="issuance_store_staysafe" data-field="x_approver_action" data-value-separator="<?php echo $issuance_store_staysafe->approver_action->DisplayValueSeparatorAttribute() ?>" name="x_approver_action" id="x_approver_action" value="{value}"<?php echo $issuance_store_staysafe->approver_action->EditAttributes() ?>></div>
<div id="dsl_x_approver_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $issuance_store_staysafe->approver_action->RadioButtonListHtml(FALSE, "x_approver_action") ?>
</div></div>
</span>
<?php echo $issuance_store_staysafe->approver_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->approver_comment->Visible) { // approver_comment ?>
	<div id="r_approver_comment" class="form-group">
		<label id="elh_issuance_store_staysafe_approver_comment" for="x_approver_comment" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->approver_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->approver_comment->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_approver_comment">
<textarea data-table="issuance_store_staysafe" data-field="x_approver_comment" name="x_approver_comment" id="x_approver_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->approver_comment->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->approver_comment->EditAttributes() ?>><?php echo $issuance_store_staysafe->approver_comment->EditValue ?></textarea>
</span>
<?php echo $issuance_store_staysafe->approver_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label id="elh_issuance_store_staysafe_approved_by" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->approved_by->FldCaption() ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->approved_by->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_approved_by">
<?php
$wrkonchange = trim(" " . @$issuance_store_staysafe->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$issuance_store_staysafe->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8820">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $issuance_store_staysafe->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->approved_by->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="issuance_store_staysafe" data-field="x_approved_by" data-value-separator="<?php echo $issuance_store_staysafe->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($issuance_store_staysafe->approved_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fissuance_store_staysafeadd.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
<?php echo $issuance_store_staysafe->approved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->verified_date->Visible) { // verified_date ?>
	<div id="r_verified_date" class="form-group">
		<label id="elh_issuance_store_staysafe_verified_date" for="x_verified_date" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->verified_date->FldCaption() ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->verified_date->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_verified_date">
<input type="text" data-table="issuance_store_staysafe" data-field="x_verified_date" name="x_verified_date" id="x_verified_date" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->verified_date->getPlaceHolder()) ?>" value="<?php echo $issuance_store_staysafe->verified_date->EditValue ?>"<?php echo $issuance_store_staysafe->verified_date->EditAttributes() ?>>
</span>
<?php echo $issuance_store_staysafe->verified_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->verified_action->Visible) { // verified_action ?>
	<div id="r_verified_action" class="form-group">
		<label id="elh_issuance_store_staysafe_verified_action" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->verified_action->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->verified_action->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_verified_action">
<div id="tp_x_verified_action" class="ewTemplate"><input type="radio" data-table="issuance_store_staysafe" data-field="x_verified_action" data-value-separator="<?php echo $issuance_store_staysafe->verified_action->DisplayValueSeparatorAttribute() ?>" name="x_verified_action" id="x_verified_action" value="{value}"<?php echo $issuance_store_staysafe->verified_action->EditAttributes() ?>></div>
<div id="dsl_x_verified_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $issuance_store_staysafe->verified_action->RadioButtonListHtml(FALSE, "x_verified_action") ?>
</div></div>
</span>
<?php echo $issuance_store_staysafe->verified_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->verified_comment->Visible) { // verified_comment ?>
	<div id="r_verified_comment" class="form-group">
		<label id="elh_issuance_store_staysafe_verified_comment" for="x_verified_comment" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->verified_comment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->verified_comment->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_verified_comment">
<textarea data-table="issuance_store_staysafe" data-field="x_verified_comment" name="x_verified_comment" id="x_verified_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->verified_comment->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->verified_comment->EditAttributes() ?>><?php echo $issuance_store_staysafe->verified_comment->EditValue ?></textarea>
</span>
<?php echo $issuance_store_staysafe->verified_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($issuance_store_staysafe->verified_by->Visible) { // verified_by ?>
	<div id="r_verified_by" class="form-group">
		<label id="elh_issuance_store_staysafe_verified_by" class="<?php echo $issuance_store_staysafe_add->LeftColumnClass ?>"><?php echo $issuance_store_staysafe->verified_by->FldCaption() ?></label>
		<div class="<?php echo $issuance_store_staysafe_add->RightColumnClass ?>"><div<?php echo $issuance_store_staysafe->verified_by->CellAttributes() ?>>
<span id="el_issuance_store_staysafe_verified_by">
<?php
$wrkonchange = trim(" " . @$issuance_store_staysafe->verified_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$issuance_store_staysafe->verified_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_verified_by" style="white-space: nowrap; z-index: 8780">
	<input type="text" name="sv_x_verified_by" id="sv_x_verified_by" value="<?php echo $issuance_store_staysafe->verified_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->verified_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($issuance_store_staysafe->verified_by->getPlaceHolder()) ?>"<?php echo $issuance_store_staysafe->verified_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="issuance_store_staysafe" data-field="x_verified_by" data-value-separator="<?php echo $issuance_store_staysafe->verified_by->DisplayValueSeparatorAttribute() ?>" name="x_verified_by" id="x_verified_by" value="<?php echo ew_HtmlEncode($issuance_store_staysafe->verified_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fissuance_store_staysafeadd.CreateAutoSuggest({"id":"x_verified_by","forceSelect":false});
</script>
</span>
<?php echo $issuance_store_staysafe->verified_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$issuance_store_staysafe_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $issuance_store_staysafe_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $issuance_store_staysafe_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fissuance_store_staysafeadd.Init();
</script>
<?php
$issuance_store_staysafe_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#r_staff_id").hide();
$("#r_treated_by").hide();
$("#r_statuss").hide();
</script>
<?php include_once "footer.php" ?>
<?php
$issuance_store_staysafe_add->Page_Terminate();
?>
