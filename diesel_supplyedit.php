<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "diesel_supplyinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$diesel_supply_edit = NULL; // Initialize page object first

class cdiesel_supply_edit extends cdiesel_supply {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'diesel_supply';

	// Page object name
	var $PageObjName = 'diesel_supply_edit';

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

		// Table object (diesel_supply)
		if (!isset($GLOBALS["diesel_supply"]) || get_class($GLOBALS["diesel_supply"]) == "cdiesel_supply") {
			$GLOBALS["diesel_supply"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["diesel_supply"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'diesel_supply');

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
				$this->Page_Terminate(ew_GetUrl("diesel_supplylist.php"));
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
		$this->date_initiated->SetVisibility();
		$this->gen_type->SetVisibility();
		$this->category->SetVisibility();
		$this->diesel_initia_qty->SetVisibility();
		$this->diesel_new_qty->SetVisibility();
		$this->total->SetVisibility();
		$this->status->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
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
		global $EW_EXPORT, $diesel_supply;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($diesel_supply);
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
					if ($pageName == "diesel_supplyview.php")
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
			$this->Page_Terminate("diesel_supplylist.php"); // Return to list page
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
					$this->Page_Terminate("diesel_supplylist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "diesel_supplylist.php")
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
		if (!$this->date_initiated->FldIsDetailKey) {
			$this->date_initiated->setFormValue($objForm->GetValue("x_date_initiated"));
			$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 14);
		}
		if (!$this->gen_type->FldIsDetailKey) {
			$this->gen_type->setFormValue($objForm->GetValue("x_gen_type"));
		}
		if (!$this->category->FldIsDetailKey) {
			$this->category->setFormValue($objForm->GetValue("x_category"));
		}
		if (!$this->diesel_initia_qty->FldIsDetailKey) {
			$this->diesel_initia_qty->setFormValue($objForm->GetValue("x_diesel_initia_qty"));
		}
		if (!$this->diesel_new_qty->FldIsDetailKey) {
			$this->diesel_new_qty->setFormValue($objForm->GetValue("x_diesel_new_qty"));
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
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
		if (!$this->initiated_by->FldIsDetailKey) {
			$this->initiated_by->setFormValue($objForm->GetValue("x_initiated_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->date_initiated->CurrentValue = $this->date_initiated->FormValue;
		$this->date_initiated->CurrentValue = ew_UnFormatDateTime($this->date_initiated->CurrentValue, 14);
		$this->gen_type->CurrentValue = $this->gen_type->FormValue;
		$this->category->CurrentValue = $this->category->FormValue;
		$this->diesel_initia_qty->CurrentValue = $this->diesel_initia_qty->FormValue;
		$this->diesel_new_qty->CurrentValue = $this->diesel_new_qty->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->initiator_action->CurrentValue = $this->initiator_action->FormValue;
		$this->initiator_comment->CurrentValue = $this->initiator_comment->FormValue;
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
		$this->gen_type->setDbValue($row['gen_type']);
		$this->category->setDbValue($row['category']);
		$this->diesel_initia_qty->setDbValue($row['diesel_initia_qty']);
		$this->diesel_new_qty->setDbValue($row['diesel_new_qty']);
		$this->total->setDbValue($row['total']);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->initiated_by->setDbValue($row['initiated_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['date_initiated'] = NULL;
		$row['gen_type'] = NULL;
		$row['category'] = NULL;
		$row['diesel_initia_qty'] = NULL;
		$row['diesel_new_qty'] = NULL;
		$row['total'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
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
		$this->gen_type->DbValue = $row['gen_type'];
		$this->category->DbValue = $row['category'];
		$this->diesel_initia_qty->DbValue = $row['diesel_initia_qty'];
		$this->diesel_new_qty->DbValue = $row['diesel_new_qty'];
		$this->total->DbValue = $row['total'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
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
		// Convert decimal values if posted back

		if ($this->diesel_initia_qty->FormValue == $this->diesel_initia_qty->CurrentValue && is_numeric(ew_StrToFloat($this->diesel_initia_qty->CurrentValue)))
			$this->diesel_initia_qty->CurrentValue = ew_StrToFloat($this->diesel_initia_qty->CurrentValue);

		// Convert decimal values if posted back
		if ($this->diesel_new_qty->FormValue == $this->diesel_new_qty->CurrentValue && is_numeric(ew_StrToFloat($this->diesel_new_qty->CurrentValue)))
			$this->diesel_new_qty->CurrentValue = ew_StrToFloat($this->diesel_new_qty->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// date_initiated
		// gen_type
		// category
		// diesel_initia_qty
		// diesel_new_qty
		// total
		// status
		// initiator_action
		// initiator_comment
		// initiated_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 14);
		$this->date_initiated->ViewCustomAttributes = "";

		// gen_type
		if (strval($this->gen_type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
		$sWhereWrk = "";
		$this->gen_type->LookupFilters = array("dx1" => '`gen_name`', "dx2" => '`location`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gen_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->gen_type->ViewValue = $this->gen_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gen_type->ViewValue = $this->gen_type->CurrentValue;
			}
		} else {
			$this->gen_type->ViewValue = NULL;
		}
		$this->gen_type->ViewCustomAttributes = "";

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_category`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// diesel_initia_qty
		$this->diesel_initia_qty->ViewValue = $this->diesel_initia_qty->CurrentValue;
		$this->diesel_initia_qty->ViewCustomAttributes = "";

		// diesel_new_qty
		$this->diesel_new_qty->ViewValue = $this->diesel_new_qty->CurrentValue;
		$this->diesel_new_qty->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `supply_status`";
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

		// initiated_by
		$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
		if (strval($this->initiated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->initiated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->initiated_by->ViewValue = $this->initiated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
			}
		} else {
			$this->initiated_by->ViewValue = NULL;
		}
		$this->initiated_by->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";
			$this->date_initiated->TooltipValue = "";

			// gen_type
			$this->gen_type->LinkCustomAttributes = "";
			$this->gen_type->HrefValue = "";
			$this->gen_type->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// diesel_initia_qty
			$this->diesel_initia_qty->LinkCustomAttributes = "";
			$this->diesel_initia_qty->HrefValue = "";
			$this->diesel_initia_qty->TooltipValue = "";

			// diesel_new_qty
			$this->diesel_new_qty->LinkCustomAttributes = "";
			$this->diesel_new_qty->HrefValue = "";
			$this->diesel_new_qty->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

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

			// initiated_by
			$this->initiated_by->LinkCustomAttributes = "";
			$this->initiated_by->HrefValue = "";
			$this->initiated_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// date_initiated
			$this->date_initiated->EditAttrs["class"] = "form-control";
			$this->date_initiated->EditCustomAttributes = "";
			$this->date_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_initiated->CurrentValue, 14));
			$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

			// gen_type
			$this->gen_type->EditCustomAttributes = "";
			if (trim(strval($this->gen_type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `generator_registration`";
			$sWhereWrk = "";
			$this->gen_type->LookupFilters = array("dx1" => '`gen_name`', "dx2" => '`location`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->gen_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->gen_type->ViewValue = $this->gen_type->DisplayValue($arwrk);
			} else {
				$this->gen_type->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->gen_type->EditValue = $arwrk;

			// category
			$this->category->EditCustomAttributes = "";
			if (trim(strval($this->category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `gen_category`";
			$sWhereWrk = "";
			$this->category->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
			} else {
				$this->category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->category->EditValue = $arwrk;

			// diesel_initia_qty
			$this->diesel_initia_qty->EditAttrs["class"] = "form-control";
			$this->diesel_initia_qty->EditCustomAttributes = "";
			$this->diesel_initia_qty->EditValue = ew_HtmlEncode($this->diesel_initia_qty->CurrentValue);
			$this->diesel_initia_qty->PlaceHolder = ew_RemoveHtml($this->diesel_initia_qty->FldCaption());
			if (strval($this->diesel_initia_qty->EditValue) <> "" && is_numeric($this->diesel_initia_qty->EditValue)) $this->diesel_initia_qty->EditValue = ew_FormatNumber($this->diesel_initia_qty->EditValue, -2, -1, -2, 0);

			// diesel_new_qty
			$this->diesel_new_qty->EditAttrs["class"] = "form-control";
			$this->diesel_new_qty->EditCustomAttributes = "";
			$this->diesel_new_qty->EditValue = ew_HtmlEncode($this->diesel_new_qty->CurrentValue);
			$this->diesel_new_qty->PlaceHolder = ew_RemoveHtml($this->diesel_new_qty->FldCaption());
			if (strval($this->diesel_new_qty->EditValue) <> "" && is_numeric($this->diesel_new_qty->EditValue)) $this->diesel_new_qty->EditValue = ew_FormatNumber($this->diesel_new_qty->EditValue, -2, -1, -2, 0);

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `supply_status`";
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
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->CurrentValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// initiated_by
			$this->initiated_by->EditAttrs["class"] = "form-control";
			$this->initiated_by->EditCustomAttributes = "";
			$this->initiated_by->EditValue = ew_HtmlEncode($this->initiated_by->CurrentValue);
			if (strval($this->initiated_by->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->initiated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
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
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// date_initiated
			$this->date_initiated->LinkCustomAttributes = "";
			$this->date_initiated->HrefValue = "";

			// gen_type
			$this->gen_type->LinkCustomAttributes = "";
			$this->gen_type->HrefValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";

			// diesel_initia_qty
			$this->diesel_initia_qty->LinkCustomAttributes = "";
			$this->diesel_initia_qty->HrefValue = "";

			// diesel_new_qty
			$this->diesel_new_qty->LinkCustomAttributes = "";
			$this->diesel_new_qty->HrefValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";

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
		if (!ew_CheckShortEuroDate($this->date_initiated->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_initiated->FldErrMsg());
		}
		if (!ew_CheckNumber($this->diesel_initia_qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->diesel_initia_qty->FldErrMsg());
		}
		if (!ew_CheckNumber($this->diesel_new_qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->diesel_new_qty->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
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
			$this->date_initiated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_initiated->CurrentValue, 14), NULL, $this->date_initiated->ReadOnly);

			// gen_type
			$this->gen_type->SetDbValueDef($rsnew, $this->gen_type->CurrentValue, NULL, $this->gen_type->ReadOnly);

			// category
			$this->category->SetDbValueDef($rsnew, $this->category->CurrentValue, NULL, $this->category->ReadOnly);

			// diesel_initia_qty
			$this->diesel_initia_qty->SetDbValueDef($rsnew, $this->diesel_initia_qty->CurrentValue, NULL, $this->diesel_initia_qty->ReadOnly);

			// diesel_new_qty
			$this->diesel_new_qty->SetDbValueDef($rsnew, $this->diesel_new_qty->CurrentValue, NULL, $this->diesel_new_qty->ReadOnly);

			// total
			$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, NULL, $this->total->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// initiator_action
			$this->initiator_action->SetDbValueDef($rsnew, $this->initiator_action->CurrentValue, NULL, $this->initiator_action->ReadOnly);

			// initiator_comment
			$this->initiator_comment->SetDbValueDef($rsnew, $this->initiator_comment->CurrentValue, NULL, $this->initiator_comment->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("diesel_supplylist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_gen_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`gen_name`', "dx2" => '`location`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->gen_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `supply_status`";
			$sWhereWrk = "";
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
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
		case "x_initiated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%'";
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
if (!isset($diesel_supply_edit)) $diesel_supply_edit = new cdiesel_supply_edit();

// Page init
$diesel_supply_edit->Page_Init();

// Page main
$diesel_supply_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$diesel_supply_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdiesel_supplyedit = new ew_Form("fdiesel_supplyedit", "edit");

// Validate form
fdiesel_supplyedit.Validate = function() {
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
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($diesel_supply->date_initiated->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_diesel_initia_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($diesel_supply->diesel_initia_qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_diesel_new_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($diesel_supply->diesel_new_qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($diesel_supply->total->FldErrMsg()) ?>");

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
fdiesel_supplyedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdiesel_supplyedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdiesel_supplyedit.Lists["x_gen_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_gen_name","x_location","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"generator_registration"};
fdiesel_supplyedit.Lists["x_gen_type"].Data = "<?php echo $diesel_supply_edit->gen_type->LookupFilterQuery(FALSE, "edit") ?>";
fdiesel_supplyedit.Lists["x_category"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gen_category"};
fdiesel_supplyedit.Lists["x_category"].Data = "<?php echo $diesel_supply_edit->category->LookupFilterQuery(FALSE, "edit") ?>";
fdiesel_supplyedit.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"supply_status"};
fdiesel_supplyedit.Lists["x_status"].Data = "<?php echo $diesel_supply_edit->status->LookupFilterQuery(FALSE, "edit") ?>";
fdiesel_supplyedit.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdiesel_supplyedit.Lists["x_initiator_action"].Options = <?php echo json_encode($diesel_supply_edit->initiator_action->Options()) ?>;
fdiesel_supplyedit.Lists["x_initiated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdiesel_supplyedit.Lists["x_initiated_by"].Data = "<?php echo $diesel_supply_edit->initiated_by->LookupFilterQuery(FALSE, "edit") ?>";
fdiesel_supplyedit.AutoSuggests["x_initiated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $diesel_supply_edit->initiated_by->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $diesel_supply_edit->ShowPageHeader(); ?>
<?php
$diesel_supply_edit->ShowMessage();
?>
<?php if (!$diesel_supply_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($diesel_supply_edit->Pager)) $diesel_supply_edit->Pager = new cPrevNextPager($diesel_supply_edit->StartRec, $diesel_supply_edit->DisplayRecs, $diesel_supply_edit->TotalRecs, $diesel_supply_edit->AutoHidePager) ?>
<?php if ($diesel_supply_edit->Pager->RecordCount > 0 && $diesel_supply_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($diesel_supply_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $diesel_supply_edit->PageUrl() ?>start=<?php echo $diesel_supply_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($diesel_supply_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $diesel_supply_edit->PageUrl() ?>start=<?php echo $diesel_supply_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $diesel_supply_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($diesel_supply_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $diesel_supply_edit->PageUrl() ?>start=<?php echo $diesel_supply_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($diesel_supply_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $diesel_supply_edit->PageUrl() ?>start=<?php echo $diesel_supply_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $diesel_supply_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fdiesel_supplyedit" id="fdiesel_supplyedit" class="<?php echo $diesel_supply_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($diesel_supply_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $diesel_supply_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="diesel_supply">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($diesel_supply_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($diesel_supply->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_diesel_supply_id" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->id->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->id->CellAttributes() ?>>
<span id="el_diesel_supply_id">
<span<?php echo $diesel_supply->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $diesel_supply->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="diesel_supply" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($diesel_supply->id->CurrentValue) ?>">
<?php echo $diesel_supply->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->date_initiated->Visible) { // date_initiated ?>
	<div id="r_date_initiated" class="form-group">
		<label id="elh_diesel_supply_date_initiated" for="x_date_initiated" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->date_initiated->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->date_initiated->CellAttributes() ?>>
<span id="el_diesel_supply_date_initiated">
<input type="text" data-table="diesel_supply" data-field="x_date_initiated" data-format="14" name="x_date_initiated" id="x_date_initiated" size="30" placeholder="<?php echo ew_HtmlEncode($diesel_supply->date_initiated->getPlaceHolder()) ?>" value="<?php echo $diesel_supply->date_initiated->EditValue ?>"<?php echo $diesel_supply->date_initiated->EditAttributes() ?>>
<?php if (!$diesel_supply->date_initiated->ReadOnly && !$diesel_supply->date_initiated->Disabled && !isset($diesel_supply->date_initiated->EditAttrs["readonly"]) && !isset($diesel_supply->date_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdiesel_supplyedit", "x_date_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
<?php echo $diesel_supply->date_initiated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->gen_type->Visible) { // gen_type ?>
	<div id="r_gen_type" class="form-group">
		<label id="elh_diesel_supply_gen_type" for="x_gen_type" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->gen_type->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->gen_type->CellAttributes() ?>>
<span id="el_diesel_supply_gen_type">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_gen_type"><?php echo (strval($diesel_supply->gen_type->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $diesel_supply->gen_type->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($diesel_supply->gen_type->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_gen_type',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($diesel_supply->gen_type->ReadOnly || $diesel_supply->gen_type->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="diesel_supply" data-field="x_gen_type" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $diesel_supply->gen_type->DisplayValueSeparatorAttribute() ?>" name="x_gen_type" id="x_gen_type" value="<?php echo $diesel_supply->gen_type->CurrentValue ?>"<?php echo $diesel_supply->gen_type->EditAttributes() ?>>
</span>
<?php echo $diesel_supply->gen_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->category->Visible) { // category ?>
	<div id="r_category" class="form-group">
		<label id="elh_diesel_supply_category" for="x_category" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->category->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->category->CellAttributes() ?>>
<span id="el_diesel_supply_category">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_category"><?php echo (strval($diesel_supply->category->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $diesel_supply->category->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($diesel_supply->category->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_category',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($diesel_supply->category->ReadOnly || $diesel_supply->category->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="diesel_supply" data-field="x_category" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $diesel_supply->category->DisplayValueSeparatorAttribute() ?>" name="x_category" id="x_category" value="<?php echo $diesel_supply->category->CurrentValue ?>"<?php echo $diesel_supply->category->EditAttributes() ?>>
</span>
<?php echo $diesel_supply->category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->diesel_initia_qty->Visible) { // diesel_initia_qty ?>
	<div id="r_diesel_initia_qty" class="form-group">
		<label id="elh_diesel_supply_diesel_initia_qty" for="x_diesel_initia_qty" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->diesel_initia_qty->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->diesel_initia_qty->CellAttributes() ?>>
<span id="el_diesel_supply_diesel_initia_qty">
<input type="text" data-table="diesel_supply" data-field="x_diesel_initia_qty" name="x_diesel_initia_qty" id="x_diesel_initia_qty" size="30" placeholder="<?php echo ew_HtmlEncode($diesel_supply->diesel_initia_qty->getPlaceHolder()) ?>" value="<?php echo $diesel_supply->diesel_initia_qty->EditValue ?>"<?php echo $diesel_supply->diesel_initia_qty->EditAttributes() ?>>
</span>
<?php echo $diesel_supply->diesel_initia_qty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->diesel_new_qty->Visible) { // diesel_new_qty ?>
	<div id="r_diesel_new_qty" class="form-group">
		<label id="elh_diesel_supply_diesel_new_qty" for="x_diesel_new_qty" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->diesel_new_qty->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->diesel_new_qty->CellAttributes() ?>>
<span id="el_diesel_supply_diesel_new_qty">
<input type="text" data-table="diesel_supply" data-field="x_diesel_new_qty" name="x_diesel_new_qty" id="x_diesel_new_qty" size="30" placeholder="<?php echo ew_HtmlEncode($diesel_supply->diesel_new_qty->getPlaceHolder()) ?>" value="<?php echo $diesel_supply->diesel_new_qty->EditValue ?>"<?php echo $diesel_supply->diesel_new_qty->EditAttributes() ?>>
</span>
<?php echo $diesel_supply->diesel_new_qty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_diesel_supply_total" for="x_total" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->total->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->total->CellAttributes() ?>>
<span id="el_diesel_supply_total">
<input type="text" data-table="diesel_supply" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($diesel_supply->total->getPlaceHolder()) ?>" value="<?php echo $diesel_supply->total->EditValue ?>"<?php echo $diesel_supply->total->EditAttributes() ?>>
</span>
<?php echo $diesel_supply->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_diesel_supply_status" for="x_status" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->status->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->status->CellAttributes() ?>>
<span id="el_diesel_supply_status">
<select data-table="diesel_supply" data-field="x_status" data-value-separator="<?php echo $diesel_supply->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $diesel_supply->status->EditAttributes() ?>>
<?php echo $diesel_supply->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
<?php echo $diesel_supply->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label id="elh_diesel_supply_initiator_action" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->initiator_action->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->initiator_action->CellAttributes() ?>>
<span id="el_diesel_supply_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="diesel_supply" data-field="x_initiator_action" data-value-separator="<?php echo $diesel_supply->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $diesel_supply->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $diesel_supply->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action") ?>
</div></div>
</span>
<?php echo $diesel_supply->initiator_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label id="elh_diesel_supply_initiator_comment" for="x_initiator_comment" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->initiator_comment->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->initiator_comment->CellAttributes() ?>>
<span id="el_diesel_supply_initiator_comment">
<textarea data-table="diesel_supply" data-field="x_initiator_comment" name="x_initiator_comment" id="x_initiator_comment" cols="30" rows="4" placeholder="<?php echo ew_HtmlEncode($diesel_supply->initiator_comment->getPlaceHolder()) ?>"<?php echo $diesel_supply->initiator_comment->EditAttributes() ?>><?php echo $diesel_supply->initiator_comment->EditValue ?></textarea>
</span>
<?php echo $diesel_supply->initiator_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diesel_supply->initiated_by->Visible) { // initiated_by ?>
	<div id="r_initiated_by" class="form-group">
		<label id="elh_diesel_supply_initiated_by" class="<?php echo $diesel_supply_edit->LeftColumnClass ?>"><?php echo $diesel_supply->initiated_by->FldCaption() ?></label>
		<div class="<?php echo $diesel_supply_edit->RightColumnClass ?>"><div<?php echo $diesel_supply->initiated_by->CellAttributes() ?>>
<span id="el_diesel_supply_initiated_by">
<?php
$wrkonchange = trim(" " . @$diesel_supply->initiated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$diesel_supply->initiated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_initiated_by" style="white-space: nowrap; z-index: 8890">
	<input type="text" name="sv_x_initiated_by" id="sv_x_initiated_by" value="<?php echo $diesel_supply->initiated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($diesel_supply->initiated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($diesel_supply->initiated_by->getPlaceHolder()) ?>"<?php echo $diesel_supply->initiated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="diesel_supply" data-field="x_initiated_by" data-value-separator="<?php echo $diesel_supply->initiated_by->DisplayValueSeparatorAttribute() ?>" name="x_initiated_by" id="x_initiated_by" value="<?php echo ew_HtmlEncode($diesel_supply->initiated_by->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fdiesel_supplyedit.CreateAutoSuggest({"id":"x_initiated_by","forceSelect":false});
</script>
</span>
<?php echo $diesel_supply->initiated_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$diesel_supply_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $diesel_supply_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $diesel_supply_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdiesel_supplyedit.Init();
</script>
<?php
$diesel_supply_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$diesel_supply_edit->Page_Terminate();
?>
