<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "servicinginfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$servicing_add = NULL; // Initialize page object first

class cservicing_add extends cservicing {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'servicing';

	// Page object name
	var $PageObjName = 'servicing_add';

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

		// Table object (servicing)
		if (!isset($GLOBALS["servicing"]) || get_class($GLOBALS["servicing"]) == "cservicing") {
			$GLOBALS["servicing"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["servicing"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'servicing', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("servicinglist.php"));
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
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->maintained_by->SetVisibility();
		$this->reviewed_date->SetVisibility();
		$this->reviewed_action->SetVisibility();
		$this->reviewed_comment->SetVisibility();
		$this->reviewed_by->SetVisibility();
		$this->staff_no->SetVisibility();

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
		global $EW_EXPORT, $servicing;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($servicing);
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
					if ($pageName == "servicingview.php")
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
	var $MultiPages; // Multi pages object

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
					$this->Page_Terminate("servicinglist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "servicinglist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "servicingview.php")
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
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view type
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add type
		}

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
		$this->date_initiated->CurrentValue = NULL;
		$this->date_initiated->OldValue = $this->date_initiated->CurrentValue;
		$this->reference_id->CurrentValue = NULL;
		$this->reference_id->OldValue = $this->reference_id->CurrentValue;
		$this->staff_id->CurrentValue = NULL;
		$this->staff_id->OldValue = $this->staff_id->CurrentValue;
		$this->staff_name->CurrentValue = NULL;
		$this->staff_name->OldValue = $this->staff_name->CurrentValue;
		$this->department->CurrentValue = NULL;
		$this->department->OldValue = $this->department->CurrentValue;
		$this->branch->CurrentValue = NULL;
		$this->branch->OldValue = $this->branch->CurrentValue;
		$this->buildings->CurrentValue = NULL;
		$this->buildings->OldValue = $this->buildings->CurrentValue;
		$this->floors->CurrentValue = NULL;
		$this->floors->OldValue = $this->floors->CurrentValue;
		$this->items->CurrentValue = NULL;
		$this->items->OldValue = $this->items->CurrentValue;
		$this->priority->CurrentValue = NULL;
		$this->priority->OldValue = $this->priority->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->status->CurrentValue = 0;
		$this->date_maintained->CurrentValue = NULL;
		$this->date_maintained->OldValue = $this->date_maintained->CurrentValue;
		$this->initiator_action->CurrentValue = NULL;
		$this->initiator_action->OldValue = $this->initiator_action->CurrentValue;
		$this->initiator_comment->CurrentValue = NULL;
		$this->initiator_comment->OldValue = $this->initiator_comment->CurrentValue;
		$this->maintained_by->CurrentValue = NULL;
		$this->maintained_by->OldValue = $this->maintained_by->CurrentValue;
		$this->reviewed_date->CurrentValue = NULL;
		$this->reviewed_date->OldValue = $this->reviewed_date->CurrentValue;
		$this->reviewed_action->CurrentValue = NULL;
		$this->reviewed_action->OldValue = $this->reviewed_action->CurrentValue;
		$this->reviewed_comment->CurrentValue = NULL;
		$this->reviewed_comment->OldValue = $this->reviewed_comment->CurrentValue;
		$this->reviewed_by->CurrentValue = NULL;
		$this->reviewed_by->OldValue = $this->reviewed_by->CurrentValue;
		$this->staff_no->CurrentValue = NULL;
		$this->staff_no->OldValue = $this->staff_no->CurrentValue;
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
		if (!$this->initiator_action->FldIsDetailKey) {
			$this->initiator_action->setFormValue($objForm->GetValue("x_initiator_action"));
		}
		if (!$this->initiator_comment->FldIsDetailKey) {
			$this->initiator_comment->setFormValue($objForm->GetValue("x_initiator_comment"));
		}
		if (!$this->maintained_by->FldIsDetailKey) {
			$this->maintained_by->setFormValue($objForm->GetValue("x_maintained_by"));
		}
		if (!$this->reviewed_date->FldIsDetailKey) {
			$this->reviewed_date->setFormValue($objForm->GetValue("x_reviewed_date"));
			$this->reviewed_date->CurrentValue = ew_UnFormatDateTime($this->reviewed_date->CurrentValue, 17);
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
		if (!$this->staff_no->FldIsDetailKey) {
			$this->staff_no->setFormValue($objForm->GetValue("x_staff_no"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
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
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
		$this->maintained_by->CurrentValue = $this->maintained_by->FormValue;
		$this->reviewed_date->CurrentValue = $this->reviewed_date->FormValue;
		$this->reviewed_date->CurrentValue = ew_UnFormatDateTime($this->reviewed_date->CurrentValue, 17);
		$this->reviewed_action->CurrentValue = $this->reviewed_action->FormValue;
		$this->reviewed_comment->CurrentValue = $this->reviewed_comment->FormValue;
		$this->reviewed_by->CurrentValue = $this->reviewed_by->FormValue;
		$this->staff_no->CurrentValue = $this->staff_no->FormValue;
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
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->maintained_by->setDbValue($row['maintained_by']);
		$this->reviewed_date->setDbValue($row['reviewed_date']);
		$this->reviewed_action->setDbValue($row['reviewed_action']);
		$this->reviewed_comment->setDbValue($row['reviewed_comment']);
		$this->reviewed_by->setDbValue($row['reviewed_by']);
		$this->staff_no->setDbValue($row['staff_no']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['date_initiated'] = $this->date_initiated->CurrentValue;
		$row['reference_id'] = $this->reference_id->CurrentValue;
		$row['staff_id'] = $this->staff_id->CurrentValue;
		$row['staff_name'] = $this->staff_name->CurrentValue;
		$row['department'] = $this->department->CurrentValue;
		$row['branch'] = $this->branch->CurrentValue;
		$row['buildings'] = $this->buildings->CurrentValue;
		$row['floors'] = $this->floors->CurrentValue;
		$row['items'] = $this->items->CurrentValue;
		$row['priority'] = $this->priority->CurrentValue;
		$row['description'] = $this->description->CurrentValue;
		$row['status'] = $this->status->CurrentValue;
		$row['date_maintained'] = $this->date_maintained->CurrentValue;
		$row['initiator_action'] = $this->initiator_action->CurrentValue;
		$row['initiator_comment'] = $this->initiator_comment->CurrentValue;
		$row['maintained_by'] = $this->maintained_by->CurrentValue;
		$row['reviewed_date'] = $this->reviewed_date->CurrentValue;
		$row['reviewed_action'] = $this->reviewed_action->CurrentValue;
		$row['reviewed_comment'] = $this->reviewed_comment->CurrentValue;
		$row['reviewed_by'] = $this->reviewed_by->CurrentValue;
		$row['staff_no'] = $this->staff_no->CurrentValue;
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
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->maintained_by->DbValue = $row['maintained_by'];
		$this->reviewed_date->DbValue = $row['reviewed_date'];
		$this->reviewed_action->DbValue = $row['reviewed_action'];
		$this->reviewed_comment->DbValue = $row['reviewed_comment'];
		$this->reviewed_by->DbValue = $row['reviewed_by'];
		$this->staff_no->DbValue = $row['staff_no'];
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
		// initiator_action
		// initiator_comment
		// maintained_by
		// reviewed_date
		// reviewed_action
		// reviewed_comment
		// reviewed_by
		// staff_no

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
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->items->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
		$sWhereWrk = "";
		$this->items->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->items->ViewValue = $this->items->DisplayValue($arwrk);
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
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `service_status`";
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
		$this->reviewed_date->ViewValue = ew_FormatDateTime($this->reviewed_date->ViewValue, 17);
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

		// staff_no
		$this->staff_no->ViewValue = $this->staff_no->CurrentValue;
		$this->staff_no->ViewCustomAttributes = "";

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

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

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

			// staff_no
			$this->staff_no->LinkCustomAttributes = "";
			$this->staff_no->HrefValue = "";
			$this->staff_no->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `floors`";
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
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->items->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `items`";
			$sWhereWrk = "";
			$this->items->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->items->ViewValue = $this->items->DisplayValue($arwrk);
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
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `service_status`";
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

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

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
			$this->reviewed_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->reviewed_date->CurrentValue, 17));
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

			// staff_no
			$this->staff_no->EditAttrs["class"] = "form-control";
			$this->staff_no->EditCustomAttributes = "";
			$this->staff_no->EditValue = ew_HtmlEncode($this->staff_no->CurrentValue);
			$this->staff_no->PlaceHolder = ew_RemoveHtml($this->staff_no->FldCaption());

			// Add refer script
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

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

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

			// staff_no
			$this->staff_no->LinkCustomAttributes = "";
			$this->staff_no->HrefValue = "";
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
		if (!ew_CheckDateDef($this->date_initiated->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_initiated->FldErrMsg());
		}
		if (!ew_CheckInteger($this->staff_name->FormValue)) {
			ew_AddMessage($gsFormError, $this->staff_name->FldErrMsg());
		}
		if (!ew_CheckInteger($this->department->FormValue)) {
			ew_AddMessage($gsFormError, $this->department->FldErrMsg());
		}
		if (!ew_CheckInteger($this->branch->FormValue)) {
			ew_AddMessage($gsFormError, $this->branch->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->date_maintained->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_maintained->FldErrMsg());
		}
		if (!ew_CheckInteger($this->maintained_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->maintained_by->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->reviewed_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->reviewed_date->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// date_initiated
		$this->date_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_initiated->CurrentValue, 0), NULL, FALSE);

		// reference_id
		$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, NULL, FALSE);

		// staff_id
		$this->staff_id->SetDbValueDef($rsnew, $this->staff_id->CurrentValue, NULL, FALSE);

		// staff_name
		$this->staff_name->SetDbValueDef($rsnew, $this->staff_name->CurrentValue, NULL, FALSE);

		// department
		$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, FALSE);

		// branch
		$this->branch->SetDbValueDef($rsnew, $this->branch->CurrentValue, NULL, FALSE);

		// buildings
		$this->buildings->SetDbValueDef($rsnew, $this->buildings->CurrentValue, NULL, FALSE);

		// floors
		$this->floors->SetDbValueDef($rsnew, $this->floors->CurrentValue, NULL, FALSE);

		// items
		$this->items->SetDbValueDef($rsnew, $this->items->CurrentValue, NULL, FALSE);

		// priority
		$this->priority->SetDbValueDef($rsnew, $this->priority->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// date_maintained
		$this->date_maintained->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_maintained->CurrentValue, 17), NULL, FALSE);

		// initiator_action
		$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, FALSE);

		// initiator_comment
		$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, FALSE);

		// maintained_by
		$this->maintained_by->SetDbValueDef($rsnew, $this->maintained_by->CurrentValue, NULL, FALSE);

		// reviewed_date
		$this->reviewed_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->reviewed_date->CurrentValue, 17), NULL, FALSE);

		// reviewed_action
		$this->reviewed_action->SetDbValueDef($rsnew, $this->reviewed_action->CurrentValue, NULL, FALSE);

		// reviewed_comment
		$this->reviewed_comment->SetDbValueDef($rsnew, $this->reviewed_comment->CurrentValue, NULL, FALSE);

		// reviewed_by
		$this->reviewed_by->SetDbValueDef($rsnew, $this->reviewed_by->CurrentValue, NULL, FALSE);

		// staff_no
		$this->staff_no->SetDbValueDef($rsnew, $this->staff_no->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("servicinglist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
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
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
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
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `service_status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
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
if (!isset($servicing_add)) $servicing_add = new cservicing_add();

// Page init
$servicing_add->Page_Init();

// Page main
$servicing_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicing_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fservicingadd = new ew_Form("fservicingadd", "add");

// Validate form
fservicingadd.Validate = function() {
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
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->date_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staff_name");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->staff_name->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->department->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_branch");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->branch->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_date_maintained");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->date_maintained->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maintained_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->maintained_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_date");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->reviewed_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reviewed_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicing->reviewed_by->FldErrMsg()) ?>");

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
fservicingadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fservicingadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fservicingadd.MultiPage = new ew_MultiPage("fservicingadd");

// Dynamic selection lists
fservicingadd.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingadd.Lists["x_staff_id"].Data = "<?php echo $servicing_add->staff_id->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_add->staff_id->LookupFilterQuery(TRUE, "add"))) ?>;
fservicingadd.Lists["x_staff_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingadd.Lists["x_staff_name"].Data = "<?php echo $servicing_add->staff_name->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.AutoSuggests["x_staff_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_add->staff_name->LookupFilterQuery(TRUE, "add"))) ?>;
fservicingadd.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fservicingadd.Lists["x_department"].Data = "<?php echo $servicing_add->department->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.AutoSuggests["x_department"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_add->department->LookupFilterQuery(TRUE, "add"))) ?>;
fservicingadd.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
fservicingadd.Lists["x_branch"].Data = "<?php echo $servicing_add->branch->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.AutoSuggests["x_branch"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_add->branch->LookupFilterQuery(TRUE, "add"))) ?>;
fservicingadd.Lists["x_buildings"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"buildings"};
fservicingadd.Lists["x_buildings"].Data = "<?php echo $servicing_add->buildings->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.Lists["x_floors"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"floors"};
fservicingadd.Lists["x_floors"].Data = "<?php echo $servicing_add->floors->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.Lists["x_items"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"items"};
fservicingadd.Lists["x_items"].Data = "<?php echo $servicing_add->items->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.Lists["x_priority"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
fservicingadd.Lists["x_priority"].Data = "<?php echo $servicing_add->priority->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"service_status"};
fservicingadd.Lists["x_status"].Data = "<?php echo $servicing_add->status->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicingadd.Lists["x_initiator_action"].Options = <?php echo json_encode($servicing_add->initiator_action->Options()) ?>;
fservicingadd.Lists["x_maintained_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingadd.Lists["x_maintained_by"].Data = "<?php echo $servicing_add->maintained_by->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.AutoSuggests["x_maintained_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_add->maintained_by->LookupFilterQuery(TRUE, "add"))) ?>;
fservicingadd.Lists["x_reviewed_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicingadd.Lists["x_reviewed_action"].Options = <?php echo json_encode($servicing_add->reviewed_action->Options()) ?>;
fservicingadd.Lists["x_reviewed_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fservicingadd.Lists["x_reviewed_by"].Data = "<?php echo $servicing_add->reviewed_by->LookupFilterQuery(FALSE, "add") ?>";
fservicingadd.AutoSuggests["x_reviewed_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $servicing_add->reviewed_by->LookupFilterQuery(TRUE, "add"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $servicing_add->ShowPageHeader(); ?>
<?php
$servicing_add->ShowMessage();
?>
<form name="fservicingadd" id="fservicingadd" class="<?php echo $servicing_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($servicing_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $servicing_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="servicing">
<?php if ($servicing->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_add" id="a_add" value="F">
<?php } ?>
<input type="hidden" name="modal" value="<?php echo intval($servicing_add->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="servicing_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $servicing_add->MultiPages->NavStyle() ?>">
		<li<?php echo $servicing_add->MultiPages->TabStyle("1") ?>><a href="#tab_servicing1" data-toggle="tab"><?php echo $servicing->PageCaption(1) ?></a></li>
		<li<?php echo $servicing_add->MultiPages->TabStyle("2") ?>><a href="#tab_servicing2" data-toggle="tab"><?php echo $servicing->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $servicing_add->MultiPages->PageStyle("1") ?>" id="tab_servicing1"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($servicing->date_initiated->Visible) { // date_initiated ?>
	<div id="r_date_initiated" class="form-group">
		<label id="elh_servicing_date_initiated" for="x_date_initiated" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->date_initiated->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->date_initiated->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_date_initiated">
<input type="text" data-table="servicing" data-field="x_date_initiated" data-page="1" name="x_date_initiated" id="x_date_initiated" placeholder="<?php echo ew_HtmlEncode($servicing->date_initiated->getPlaceHolder()) ?>" value="<?php echo $servicing->date_initiated->EditValue ?>"<?php echo $servicing->date_initiated->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_date_initiated">
<span<?php echo $servicing->date_initiated->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->date_initiated->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_date_initiated" data-page="1" name="x_date_initiated" id="x_date_initiated" value="<?php echo ew_HtmlEncode($servicing->date_initiated->FormValue) ?>">
<?php } ?>
<?php echo $servicing->date_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_servicing_reference_id" for="x_reference_id" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->reference_id->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->reference_id->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_reference_id">
<input type="text" data-table="servicing" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($servicing->reference_id->getPlaceHolder()) ?>" value="<?php echo $servicing->reference_id->EditValue ?>"<?php echo $servicing->reference_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_reference_id">
<span<?php echo $servicing->reference_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->reference_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_reference_id" data-page="1" name="x_reference_id" id="x_reference_id" value="<?php echo ew_HtmlEncode($servicing->reference_id->FormValue) ?>">
<?php } ?>
<?php echo $servicing->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label id="elh_servicing_staff_id" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->staff_id->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->staff_id->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_staff_id">
<?php
$wrkonchange = trim(" " . @$servicing->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$servicing->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $servicing->staff_id->EditValue ?>" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($servicing->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($servicing->staff_id->getPlaceHolder()) ?>"<?php echo $servicing->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="servicing" data-field="x_staff_id" data-page="1" data-value-separator="<?php echo $servicing->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($servicing->staff_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fservicingadd.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_servicing_staff_id">
<span<?php echo $servicing->staff_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->staff_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_staff_id" data-page="1" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($servicing->staff_id->FormValue) ?>">
<?php } ?>
<?php echo $servicing->staff_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->staff_name->Visible) { // staff_name ?>
	<div id="r_staff_name" class="form-group">
		<label id="elh_servicing_staff_name" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->staff_name->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->staff_name->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_staff_name">
<?php
$wrkonchange = trim(" " . @$servicing->staff_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$servicing->staff_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_name" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_staff_name" id="sv_x_staff_name" value="<?php echo $servicing->staff_name->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($servicing->staff_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($servicing->staff_name->getPlaceHolder()) ?>"<?php echo $servicing->staff_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="servicing" data-field="x_staff_name" data-page="1" data-value-separator="<?php echo $servicing->staff_name->DisplayValueSeparatorAttribute() ?>" name="x_staff_name" id="x_staff_name" value="<?php echo ew_HtmlEncode($servicing->staff_name->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fservicingadd.CreateAutoSuggest({"id":"x_staff_name","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_servicing_staff_name">
<span<?php echo $servicing->staff_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->staff_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_staff_name" data-page="1" name="x_staff_name" id="x_staff_name" value="<?php echo ew_HtmlEncode($servicing->staff_name->FormValue) ?>">
<?php } ?>
<?php echo $servicing->staff_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_servicing_department" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->department->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->department->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_department">
<?php
$wrkonchange = trim(" " . @$servicing->department->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$servicing->department->EditAttrs["onchange"] = "";
?>
<span id="as_x_department" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_department" id="sv_x_department" value="<?php echo $servicing->department->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($servicing->department->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($servicing->department->getPlaceHolder()) ?>"<?php echo $servicing->department->EditAttributes() ?>>
</span>
<input type="hidden" data-table="servicing" data-field="x_department" data-page="1" data-value-separator="<?php echo $servicing->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($servicing->department->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fservicingadd.CreateAutoSuggest({"id":"x_department","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_servicing_department">
<span<?php echo $servicing->department->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->department->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_department" data-page="1" name="x_department" id="x_department" value="<?php echo ew_HtmlEncode($servicing->department->FormValue) ?>">
<?php } ?>
<?php echo $servicing->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->branch->Visible) { // branch ?>
	<div id="r_branch" class="form-group">
		<label id="elh_servicing_branch" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->branch->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->branch->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_branch">
<?php
$wrkonchange = trim(" " . @$servicing->branch->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$servicing->branch->EditAttrs["onchange"] = "";
?>
<span id="as_x_branch" style="white-space: nowrap; z-index: 8930">
	<input type="text" name="sv_x_branch" id="sv_x_branch" value="<?php echo $servicing->branch->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($servicing->branch->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($servicing->branch->getPlaceHolder()) ?>"<?php echo $servicing->branch->EditAttributes() ?>>
</span>
<input type="hidden" data-table="servicing" data-field="x_branch" data-page="1" data-value-separator="<?php echo $servicing->branch->DisplayValueSeparatorAttribute() ?>" name="x_branch" id="x_branch" value="<?php echo ew_HtmlEncode($servicing->branch->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fservicingadd.CreateAutoSuggest({"id":"x_branch","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_servicing_branch">
<span<?php echo $servicing->branch->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->branch->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_branch" data-page="1" name="x_branch" id="x_branch" value="<?php echo ew_HtmlEncode($servicing->branch->FormValue) ?>">
<?php } ?>
<?php echo $servicing->branch->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->buildings->Visible) { // buildings ?>
	<div id="r_buildings" class="form-group">
		<label id="elh_servicing_buildings" for="x_buildings" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->buildings->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->buildings->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_buildings">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_buildings"><?php echo (strval($servicing->buildings->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $servicing->buildings->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($servicing->buildings->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_buildings',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($servicing->buildings->ReadOnly || $servicing->buildings->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="servicing" data-field="x_buildings" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $servicing->buildings->DisplayValueSeparatorAttribute() ?>" name="x_buildings" id="x_buildings" value="<?php echo $servicing->buildings->CurrentValue ?>"<?php echo $servicing->buildings->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_buildings">
<span<?php echo $servicing->buildings->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->buildings->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_buildings" data-page="1" name="x_buildings" id="x_buildings" value="<?php echo ew_HtmlEncode($servicing->buildings->FormValue) ?>">
<?php } ?>
<?php echo $servicing->buildings->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->floors->Visible) { // floors ?>
	<div id="r_floors" class="form-group">
		<label id="elh_servicing_floors" for="x_floors" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->floors->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->floors->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_floors">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_floors"><?php echo (strval($servicing->floors->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $servicing->floors->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($servicing->floors->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_floors',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($servicing->floors->ReadOnly || $servicing->floors->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="servicing" data-field="x_floors" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $servicing->floors->DisplayValueSeparatorAttribute() ?>" name="x_floors" id="x_floors" value="<?php echo $servicing->floors->CurrentValue ?>"<?php echo $servicing->floors->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_floors">
<span<?php echo $servicing->floors->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->floors->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_floors" data-page="1" name="x_floors" id="x_floors" value="<?php echo ew_HtmlEncode($servicing->floors->FormValue) ?>">
<?php } ?>
<?php echo $servicing->floors->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->items->Visible) { // items ?>
	<div id="r_items" class="form-group">
		<label id="elh_servicing_items" for="x_items" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->items->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->items->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_items">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_items"><?php echo (strval($servicing->items->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $servicing->items->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($servicing->items->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_items',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($servicing->items->ReadOnly || $servicing->items->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="servicing" data-field="x_items" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $servicing->items->DisplayValueSeparatorAttribute() ?>" name="x_items" id="x_items" value="<?php echo $servicing->items->CurrentValue ?>"<?php echo $servicing->items->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_items">
<span<?php echo $servicing->items->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->items->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_items" data-page="1" name="x_items" id="x_items" value="<?php echo ew_HtmlEncode($servicing->items->FormValue) ?>">
<?php } ?>
<?php echo $servicing->items->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->priority->Visible) { // priority ?>
	<div id="r_priority" class="form-group">
		<label id="elh_servicing_priority" for="x_priority" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->priority->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->priority->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_priority">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_priority"><?php echo (strval($servicing->priority->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $servicing->priority->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($servicing->priority->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_priority',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($servicing->priority->ReadOnly || $servicing->priority->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="servicing" data-field="x_priority" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $servicing->priority->DisplayValueSeparatorAttribute() ?>" name="x_priority" id="x_priority" value="<?php echo $servicing->priority->CurrentValue ?>"<?php echo $servicing->priority->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_priority">
<span<?php echo $servicing->priority->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->priority->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_priority" data-page="1" name="x_priority" id="x_priority" value="<?php echo ew_HtmlEncode($servicing->priority->FormValue) ?>">
<?php } ?>
<?php echo $servicing->priority->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_servicing_description" for="x_description" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->description->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->description->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_description">
<textarea data-table="servicing" data-field="x_description" data-page="1" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($servicing->description->getPlaceHolder()) ?>"<?php echo $servicing->description->EditAttributes() ?>><?php echo $servicing->description->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_servicing_description">
<span<?php echo $servicing->description->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->description->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_description" data-page="1" name="x_description" id="x_description" value="<?php echo ew_HtmlEncode($servicing->description->FormValue) ?>">
<?php } ?>
<?php echo $servicing->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->staff_no->Visible) { // staff_no ?>
	<div id="r_staff_no" class="form-group">
		<label id="elh_servicing_staff_no" for="x_staff_no" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->staff_no->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->staff_no->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_staff_no">
<input type="text" data-table="servicing" data-field="x_staff_no" data-page="1" name="x_staff_no" id="x_staff_no" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($servicing->staff_no->getPlaceHolder()) ?>" value="<?php echo $servicing->staff_no->EditValue ?>"<?php echo $servicing->staff_no->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_staff_no">
<span<?php echo $servicing->staff_no->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->staff_no->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_staff_no" data-page="1" name="x_staff_no" id="x_staff_no" value="<?php echo ew_HtmlEncode($servicing->staff_no->FormValue) ?>">
<?php } ?>
<?php echo $servicing->staff_no->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $servicing_add->MultiPages->PageStyle("2") ?>" id="tab_servicing2"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($servicing->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_servicing_status" for="x_status" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->status->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->status->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_status">
<select data-table="servicing" data-field="x_status" data-page="2" data-value-separator="<?php echo $servicing->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $servicing->status->EditAttributes() ?>>
<?php echo $servicing->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php } else { ?>
<span id="el_servicing_status">
<span<?php echo $servicing->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_status" data-page="2" name="x_status" id="x_status" value="<?php echo ew_HtmlEncode($servicing->status->FormValue) ?>">
<?php } ?>
<?php echo $servicing->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->date_maintained->Visible) { // date_maintained ?>
	<div id="r_date_maintained" class="form-group">
		<label id="elh_servicing_date_maintained" for="x_date_maintained" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->date_maintained->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->date_maintained->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_date_maintained">
<input type="text" data-table="servicing" data-field="x_date_maintained" data-page="2" data-format="17" name="x_date_maintained" id="x_date_maintained" placeholder="<?php echo ew_HtmlEncode($servicing->date_maintained->getPlaceHolder()) ?>" value="<?php echo $servicing->date_maintained->EditValue ?>"<?php echo $servicing->date_maintained->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_date_maintained">
<span<?php echo $servicing->date_maintained->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->date_maintained->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_date_maintained" data-page="2" name="x_date_maintained" id="x_date_maintained" value="<?php echo ew_HtmlEncode($servicing->date_maintained->FormValue) ?>">
<?php } ?>
<?php echo $servicing->date_maintained->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_servicing_initiator_action" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->initiator_action->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->initiator_action->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="servicing" data-field="x_initiator_action" data-page="2" data-value-separator="<?php echo $servicing->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $servicing->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $servicing->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_servicing_initiator_action">
<span<?php echo $servicing->initiator_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->initiator_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_initiator_action" data-page="2" name="x_initiator_action" id="x_initiator_action" value="<?php echo ew_HtmlEncode($servicing->initiator_action->FormValue) ?>">
<?php } ?>
<?php echo $servicing->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_servicing_initiator_comment" for="x_initiator_comment" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->initiator_comment->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->initiator_comment->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_initiator_comment">
<textarea data-table="servicing" data-field="x_initiator_comment" data-page="2" name="x_initiator_comment" id="x_initiator_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($servicing->initiator_comment->getPlaceHolder()) ?>"<?php echo $servicing->initiator_comment->EditAttributes() ?>><?php echo $servicing->initiator_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_servicing_initiator_comment">
<span<?php echo $servicing->initiator_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->initiator_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_initiator_comment" data-page="2" name="x_initiator_comment" id="x_initiator_comment" value="<?php echo ew_HtmlEncode($servicing->initiator_comment->FormValue) ?>">
<?php } ?>
<?php echo $servicing->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->maintained_by->Visible) { // maintained_by ?>
	<div id="r_maintained_by" class="form-group">
		<label id="elh_servicing_maintained_by" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->maintained_by->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->maintained_by->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_maintained_by">
<?php
$wrkonchange = trim(" " . @$servicing->maintained_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$servicing->maintained_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_maintained_by" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_maintained_by" id="sv_x_maintained_by" value="<?php echo $servicing->maintained_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($servicing->maintained_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($servicing->maintained_by->getPlaceHolder()) ?>"<?php echo $servicing->maintained_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="servicing" data-field="x_maintained_by" data-page="2" data-value-separator="<?php echo $servicing->maintained_by->DisplayValueSeparatorAttribute() ?>" name="x_maintained_by" id="x_maintained_by" value="<?php echo ew_HtmlEncode($servicing->maintained_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fservicingadd.CreateAutoSuggest({"id":"x_maintained_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_servicing_maintained_by">
<span<?php echo $servicing->maintained_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->maintained_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_maintained_by" data-page="2" name="x_maintained_by" id="x_maintained_by" value="<?php echo ew_HtmlEncode($servicing->maintained_by->FormValue) ?>">
<?php } ?>
<?php echo $servicing->maintained_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->reviewed_date->Visible) { // reviewed_date ?>
	<div id="r_reviewed_date" class="form-group">
		<label id="elh_servicing_reviewed_date" for="x_reviewed_date" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->reviewed_date->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->reviewed_date->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_reviewed_date">
<input type="text" data-table="servicing" data-field="x_reviewed_date" data-page="2" data-format="17" name="x_reviewed_date" id="x_reviewed_date" placeholder="<?php echo ew_HtmlEncode($servicing->reviewed_date->getPlaceHolder()) ?>" value="<?php echo $servicing->reviewed_date->EditValue ?>"<?php echo $servicing->reviewed_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_servicing_reviewed_date">
<span<?php echo $servicing->reviewed_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->reviewed_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_reviewed_date" data-page="2" name="x_reviewed_date" id="x_reviewed_date" value="<?php echo ew_HtmlEncode($servicing->reviewed_date->FormValue) ?>">
<?php } ?>
<?php echo $servicing->reviewed_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->reviewed_action->Visible) { // reviewed_action ?>
	<div id="r_reviewed_action" class="form-group">
		<label id="elh_servicing_reviewed_action" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->reviewed_action->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->reviewed_action->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_reviewed_action">
<div id="tp_x_reviewed_action" class="ewTemplate"><input type="radio" data-table="servicing" data-field="x_reviewed_action" data-page="2" data-value-separator="<?php echo $servicing->reviewed_action->DisplayValueSeparatorAttribute() ?>" name="x_reviewed_action" id="x_reviewed_action" value="{value}"<?php echo $servicing->reviewed_action->EditAttributes() ?>></div>
<div id="dsl_x_reviewed_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $servicing->reviewed_action->RadioButtonListHtml(FALSE, "x_reviewed_action", 2) ?>
</div></div>
</span>
<?php } else { ?>
<span id="el_servicing_reviewed_action">
<span<?php echo $servicing->reviewed_action->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->reviewed_action->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_reviewed_action" data-page="2" name="x_reviewed_action" id="x_reviewed_action" value="<?php echo ew_HtmlEncode($servicing->reviewed_action->FormValue) ?>">
<?php } ?>
<?php echo $servicing->reviewed_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->reviewed_comment->Visible) { // reviewed_comment ?>
	<div id="r_reviewed_comment" class="form-group">
		<label id="elh_servicing_reviewed_comment" for="x_reviewed_comment" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->reviewed_comment->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->reviewed_comment->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_reviewed_comment">
<textarea data-table="servicing" data-field="x_reviewed_comment" data-page="2" name="x_reviewed_comment" id="x_reviewed_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($servicing->reviewed_comment->getPlaceHolder()) ?>"<?php echo $servicing->reviewed_comment->EditAttributes() ?>><?php echo $servicing->reviewed_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_servicing_reviewed_comment">
<span<?php echo $servicing->reviewed_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->reviewed_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_reviewed_comment" data-page="2" name="x_reviewed_comment" id="x_reviewed_comment" value="<?php echo ew_HtmlEncode($servicing->reviewed_comment->FormValue) ?>">
<?php } ?>
<?php echo $servicing->reviewed_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicing->reviewed_by->Visible) { // reviewed_by ?>
	<div id="r_reviewed_by" class="form-group">
		<label id="elh_servicing_reviewed_by" class="<?php echo $servicing_add->LeftColumnClass ?>"><?php echo $servicing->reviewed_by->FldCaption() ?></label>
		<div class="<?php echo $servicing_add->RightColumnClass ?>"><div<?php echo $servicing->reviewed_by->CellAttributes() ?>>
<?php if ($servicing->CurrentAction <> "F") { ?>
<span id="el_servicing_reviewed_by">
<?php
$wrkonchange = trim(" " . @$servicing->reviewed_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$servicing->reviewed_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_reviewed_by" style="white-space: nowrap; z-index: 8790">
	<input type="text" name="sv_x_reviewed_by" id="sv_x_reviewed_by" value="<?php echo $servicing->reviewed_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($servicing->reviewed_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($servicing->reviewed_by->getPlaceHolder()) ?>"<?php echo $servicing->reviewed_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="servicing" data-field="x_reviewed_by" data-page="2" data-value-separator="<?php echo $servicing->reviewed_by->DisplayValueSeparatorAttribute() ?>" name="x_reviewed_by" id="x_reviewed_by" value="<?php echo ew_HtmlEncode($servicing->reviewed_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fservicingadd.CreateAutoSuggest({"id":"x_reviewed_by","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el_servicing_reviewed_by">
<span<?php echo $servicing->reviewed_by->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicing->reviewed_by->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="servicing" data-field="x_reviewed_by" data-page="2" name="x_reviewed_by" id="x_reviewed_by" value="<?php echo ew_HtmlEncode($servicing->reviewed_by->FormValue) ?>">
<?php } ?>
<?php echo $servicing->reviewed_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$servicing_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $servicing_add->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($servicing->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_add.value='F';"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $servicing_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_add.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fservicingadd.Init();
</script>
<?php
$servicing_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$servicing_add->Page_Terminate();
?>
