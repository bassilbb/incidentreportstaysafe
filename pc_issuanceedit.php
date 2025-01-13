<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "pc_issuanceinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$pc_issuance_edit = NULL; // Initialize page object first

class cpc_issuance_edit extends cpc_issuance {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'pc_issuance';

	// Page object name
	var $PageObjName = 'pc_issuance_edit';

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

		// Table object (pc_issuance)
		if (!isset($GLOBALS["pc_issuance"]) || get_class($GLOBALS["pc_issuance"]) == "cpc_issuance") {
			$GLOBALS["pc_issuance"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pc_issuance"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pc_issuance', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("pc_issuancelist.php"));
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
		$this->issued_date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->asset_tag->SetVisibility();
		$this->make->SetVisibility();
		$this->color->SetVisibility();
		$this->department->SetVisibility();
		$this->designation->SetVisibility();
		$this->assign_to->SetVisibility();
		$this->date_assign->SetVisibility();
		$this->assign_action->SetVisibility();
		$this->assign_comment->SetVisibility();
		$this->assign_by->SetVisibility();
		$this->statuse->SetVisibility();
		$this->date_retrieved->SetVisibility();
		$this->retriever_action->SetVisibility();
		$this->retriever_comment->SetVisibility();
		$this->retrieved_by->SetVisibility();

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
		global $EW_EXPORT, $pc_issuance;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pc_issuance);
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
					if ($pageName == "pc_issuanceview.php")
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
			$this->Page_Terminate("pc_issuancelist.php"); // Return to list page
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
					$this->Page_Terminate("pc_issuancelist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pc_issuancelist.php")
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
		if (!$this->issued_date->FldIsDetailKey) {
			$this->issued_date->setFormValue($objForm->GetValue("x_issued_date"));
			$this->issued_date->CurrentValue = ew_UnFormatDateTime($this->issued_date->CurrentValue, 0);
		}
		if (!$this->reference_id->FldIsDetailKey) {
			$this->reference_id->setFormValue($objForm->GetValue("x_reference_id"));
		}
		if (!$this->asset_tag->FldIsDetailKey) {
			$this->asset_tag->setFormValue($objForm->GetValue("x_asset_tag"));
		}
		if (!$this->make->FldIsDetailKey) {
			$this->make->setFormValue($objForm->GetValue("x_make"));
		}
		if (!$this->color->FldIsDetailKey) {
			$this->color->setFormValue($objForm->GetValue("x_color"));
		}
		if (!$this->department->FldIsDetailKey) {
			$this->department->setFormValue($objForm->GetValue("x_department"));
		}
		if (!$this->designation->FldIsDetailKey) {
			$this->designation->setFormValue($objForm->GetValue("x_designation"));
		}
		if (!$this->assign_to->FldIsDetailKey) {
			$this->assign_to->setFormValue($objForm->GetValue("x_assign_to"));
		}
		if (!$this->date_assign->FldIsDetailKey) {
			$this->date_assign->setFormValue($objForm->GetValue("x_date_assign"));
			$this->date_assign->CurrentValue = ew_UnFormatDateTime($this->date_assign->CurrentValue, 0);
		}
		if (!$this->assign_action->FldIsDetailKey) {
			$this->assign_action->setFormValue($objForm->GetValue("x_assign_action"));
		}
		if (!$this->assign_comment->FldIsDetailKey) {
			$this->assign_comment->setFormValue($objForm->GetValue("x_assign_comment"));
		}
		if (!$this->assign_by->FldIsDetailKey) {
			$this->assign_by->setFormValue($objForm->GetValue("x_assign_by"));
		}
		if (!$this->statuse->FldIsDetailKey) {
			$this->statuse->setFormValue($objForm->GetValue("x_statuse"));
		}
		if (!$this->date_retrieved->FldIsDetailKey) {
			$this->date_retrieved->setFormValue($objForm->GetValue("x_date_retrieved"));
			$this->date_retrieved->CurrentValue = ew_UnFormatDateTime($this->date_retrieved->CurrentValue, 0);
		}
		if (!$this->retriever_action->FldIsDetailKey) {
			$this->retriever_action->setFormValue($objForm->GetValue("x_retriever_action"));
		}
		if (!$this->retriever_comment->FldIsDetailKey) {
			$this->retriever_comment->setFormValue($objForm->GetValue("x_retriever_comment"));
		}
		if (!$this->retrieved_by->FldIsDetailKey) {
			$this->retrieved_by->setFormValue($objForm->GetValue("x_retrieved_by"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->issued_date->CurrentValue = $this->issued_date->FormValue;
		$this->issued_date->CurrentValue = ew_UnFormatDateTime($this->issued_date->CurrentValue, 0);
		$this->reference_id->CurrentValue = $this->reference_id->FormValue;
		$this->asset_tag->CurrentValue = $this->asset_tag->FormValue;
		$this->make->CurrentValue = $this->make->FormValue;
		$this->color->CurrentValue = $this->color->FormValue;
		$this->department->CurrentValue = $this->department->FormValue;
		$this->designation->CurrentValue = $this->designation->FormValue;
		$this->assign_to->CurrentValue = $this->assign_to->FormValue;
		$this->date_assign->CurrentValue = $this->date_assign->FormValue;
		$this->date_assign->CurrentValue = ew_UnFormatDateTime($this->date_assign->CurrentValue, 0);
		$this->assign_action->CurrentValue = $this->assign_action->FormValue;
		$this->assign_comment->CurrentValue = $this->assign_comment->FormValue;
		$this->assign_by->CurrentValue = $this->assign_by->FormValue;
		$this->statuse->CurrentValue = $this->statuse->FormValue;
		$this->date_retrieved->CurrentValue = $this->date_retrieved->FormValue;
		$this->date_retrieved->CurrentValue = ew_UnFormatDateTime($this->date_retrieved->CurrentValue, 0);
		$this->retriever_action->CurrentValue = $this->retriever_action->FormValue;
		$this->retriever_comment->CurrentValue = $this->retriever_comment->FormValue;
		$this->retrieved_by->CurrentValue = $this->retrieved_by->FormValue;
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
		$this->issued_date->setDbValue($row['issued_date']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->asset_tag->setDbValue($row['asset_tag']);
		$this->make->setDbValue($row['make']);
		$this->color->setDbValue($row['color']);
		$this->department->setDbValue($row['department']);
		$this->designation->setDbValue($row['designation']);
		$this->assign_to->setDbValue($row['assign_to']);
		$this->date_assign->setDbValue($row['date_assign']);
		$this->assign_action->setDbValue($row['assign_action']);
		$this->assign_comment->setDbValue($row['assign_comment']);
		$this->assign_by->setDbValue($row['assign_by']);
		$this->statuse->setDbValue($row['statuse']);
		$this->date_retrieved->setDbValue($row['date_retrieved']);
		$this->retriever_action->setDbValue($row['retriever_action']);
		$this->retriever_comment->setDbValue($row['retriever_comment']);
		$this->retrieved_by->setDbValue($row['retrieved_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['issued_date'] = NULL;
		$row['reference_id'] = NULL;
		$row['asset_tag'] = NULL;
		$row['make'] = NULL;
		$row['color'] = NULL;
		$row['department'] = NULL;
		$row['designation'] = NULL;
		$row['assign_to'] = NULL;
		$row['date_assign'] = NULL;
		$row['assign_action'] = NULL;
		$row['assign_comment'] = NULL;
		$row['assign_by'] = NULL;
		$row['statuse'] = NULL;
		$row['date_retrieved'] = NULL;
		$row['retriever_action'] = NULL;
		$row['retriever_comment'] = NULL;
		$row['retrieved_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->issued_date->DbValue = $row['issued_date'];
		$this->reference_id->DbValue = $row['reference_id'];
		$this->asset_tag->DbValue = $row['asset_tag'];
		$this->make->DbValue = $row['make'];
		$this->color->DbValue = $row['color'];
		$this->department->DbValue = $row['department'];
		$this->designation->DbValue = $row['designation'];
		$this->assign_to->DbValue = $row['assign_to'];
		$this->date_assign->DbValue = $row['date_assign'];
		$this->assign_action->DbValue = $row['assign_action'];
		$this->assign_comment->DbValue = $row['assign_comment'];
		$this->assign_by->DbValue = $row['assign_by'];
		$this->statuse->DbValue = $row['statuse'];
		$this->date_retrieved->DbValue = $row['date_retrieved'];
		$this->retriever_action->DbValue = $row['retriever_action'];
		$this->retriever_comment->DbValue = $row['retriever_comment'];
		$this->retrieved_by->DbValue = $row['retrieved_by'];
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
		// issued_date
		// reference_id
		// asset_tag
		// make
		// color
		// department
		// designation
		// assign_to
		// date_assign
		// assign_action
		// assign_comment
		// assign_by
		// statuse
		// date_retrieved
		// retriever_action
		// retriever_comment
		// retrieved_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// issued_date
		$this->issued_date->ViewValue = $this->issued_date->CurrentValue;
		$this->issued_date->ViewValue = ew_FormatDateTime($this->issued_date->ViewValue, 0);
		$this->issued_date->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// asset_tag
		$this->asset_tag->ViewValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->ViewCustomAttributes = "";

		// make
		$this->make->ViewValue = $this->make->CurrentValue;
		$this->make->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
		$this->department->ViewCustomAttributes = "";

		// designation
		$this->designation->ViewValue = $this->designation->CurrentValue;
		$this->designation->ViewCustomAttributes = "";

		// assign_to
		$this->assign_to->ViewValue = $this->assign_to->CurrentValue;
		$this->assign_to->ViewCustomAttributes = "";

		// date_assign
		$this->date_assign->ViewValue = $this->date_assign->CurrentValue;
		$this->date_assign->ViewValue = ew_FormatDateTime($this->date_assign->ViewValue, 0);
		$this->date_assign->ViewCustomAttributes = "";

		// assign_action
		$this->assign_action->ViewValue = $this->assign_action->CurrentValue;
		$this->assign_action->ViewCustomAttributes = "";

		// assign_comment
		$this->assign_comment->ViewValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->ViewCustomAttributes = "";

		// assign_by
		$this->assign_by->ViewValue = $this->assign_by->CurrentValue;
		$this->assign_by->ViewCustomAttributes = "";

		// statuse
		$this->statuse->ViewValue = $this->statuse->CurrentValue;
		$this->statuse->ViewCustomAttributes = "";

		// date_retrieved
		$this->date_retrieved->ViewValue = $this->date_retrieved->CurrentValue;
		$this->date_retrieved->ViewValue = ew_FormatDateTime($this->date_retrieved->ViewValue, 0);
		$this->date_retrieved->ViewCustomAttributes = "";

		// retriever_action
		$this->retriever_action->ViewValue = $this->retriever_action->CurrentValue;
		$this->retriever_action->ViewCustomAttributes = "";

		// retriever_comment
		$this->retriever_comment->ViewValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->ViewCustomAttributes = "";

		// retrieved_by
		$this->retrieved_by->ViewValue = $this->retrieved_by->CurrentValue;
		$this->retrieved_by->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// issued_date
			$this->issued_date->LinkCustomAttributes = "";
			$this->issued_date->HrefValue = "";
			$this->issued_date->TooltipValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";
			$this->reference_id->TooltipValue = "";

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";
			$this->asset_tag->TooltipValue = "";

			// make
			$this->make->LinkCustomAttributes = "";
			$this->make->HrefValue = "";
			$this->make->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";
			$this->designation->TooltipValue = "";

			// assign_to
			$this->assign_to->LinkCustomAttributes = "";
			$this->assign_to->HrefValue = "";
			$this->assign_to->TooltipValue = "";

			// date_assign
			$this->date_assign->LinkCustomAttributes = "";
			$this->date_assign->HrefValue = "";
			$this->date_assign->TooltipValue = "";

			// assign_action
			$this->assign_action->LinkCustomAttributes = "";
			$this->assign_action->HrefValue = "";
			$this->assign_action->TooltipValue = "";

			// assign_comment
			$this->assign_comment->LinkCustomAttributes = "";
			$this->assign_comment->HrefValue = "";
			$this->assign_comment->TooltipValue = "";

			// assign_by
			$this->assign_by->LinkCustomAttributes = "";
			$this->assign_by->HrefValue = "";
			$this->assign_by->TooltipValue = "";

			// statuse
			$this->statuse->LinkCustomAttributes = "";
			$this->statuse->HrefValue = "";
			$this->statuse->TooltipValue = "";

			// date_retrieved
			$this->date_retrieved->LinkCustomAttributes = "";
			$this->date_retrieved->HrefValue = "";
			$this->date_retrieved->TooltipValue = "";

			// retriever_action
			$this->retriever_action->LinkCustomAttributes = "";
			$this->retriever_action->HrefValue = "";
			$this->retriever_action->TooltipValue = "";

			// retriever_comment
			$this->retriever_comment->LinkCustomAttributes = "";
			$this->retriever_comment->HrefValue = "";
			$this->retriever_comment->TooltipValue = "";

			// retrieved_by
			$this->retrieved_by->LinkCustomAttributes = "";
			$this->retrieved_by->HrefValue = "";
			$this->retrieved_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// issued_date
			$this->issued_date->EditAttrs["class"] = "form-control";
			$this->issued_date->EditCustomAttributes = "";
			$this->issued_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->issued_date->CurrentValue, 8));
			$this->issued_date->PlaceHolder = ew_RemoveHtml($this->issued_date->FldCaption());

			// reference_id
			$this->reference_id->EditAttrs["class"] = "form-control";
			$this->reference_id->EditCustomAttributes = "";
			$this->reference_id->EditValue = ew_HtmlEncode($this->reference_id->CurrentValue);
			$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

			// asset_tag
			$this->asset_tag->EditAttrs["class"] = "form-control";
			$this->asset_tag->EditCustomAttributes = "";
			$this->asset_tag->EditValue = ew_HtmlEncode($this->asset_tag->CurrentValue);
			$this->asset_tag->PlaceHolder = ew_RemoveHtml($this->asset_tag->FldCaption());

			// make
			$this->make->EditAttrs["class"] = "form-control";
			$this->make->EditCustomAttributes = "";
			$this->make->EditValue = ew_HtmlEncode($this->make->CurrentValue);
			$this->make->PlaceHolder = ew_RemoveHtml($this->make->FldCaption());

			// color
			$this->color->EditAttrs["class"] = "form-control";
			$this->color->EditCustomAttributes = "";
			$this->color->EditValue = ew_HtmlEncode($this->color->CurrentValue);
			$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

			// department
			$this->department->EditAttrs["class"] = "form-control";
			$this->department->EditCustomAttributes = "";
			$this->department->EditValue = ew_HtmlEncode($this->department->CurrentValue);
			$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

			// designation
			$this->designation->EditAttrs["class"] = "form-control";
			$this->designation->EditCustomAttributes = "";
			$this->designation->EditValue = ew_HtmlEncode($this->designation->CurrentValue);
			$this->designation->PlaceHolder = ew_RemoveHtml($this->designation->FldCaption());

			// assign_to
			$this->assign_to->EditAttrs["class"] = "form-control";
			$this->assign_to->EditCustomAttributes = "";
			$this->assign_to->EditValue = ew_HtmlEncode($this->assign_to->CurrentValue);
			$this->assign_to->PlaceHolder = ew_RemoveHtml($this->assign_to->FldCaption());

			// date_assign
			$this->date_assign->EditAttrs["class"] = "form-control";
			$this->date_assign->EditCustomAttributes = "";
			$this->date_assign->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_assign->CurrentValue, 8));
			$this->date_assign->PlaceHolder = ew_RemoveHtml($this->date_assign->FldCaption());

			// assign_action
			$this->assign_action->EditAttrs["class"] = "form-control";
			$this->assign_action->EditCustomAttributes = "";
			$this->assign_action->EditValue = ew_HtmlEncode($this->assign_action->CurrentValue);
			$this->assign_action->PlaceHolder = ew_RemoveHtml($this->assign_action->FldCaption());

			// assign_comment
			$this->assign_comment->EditAttrs["class"] = "form-control";
			$this->assign_comment->EditCustomAttributes = "";
			$this->assign_comment->EditValue = ew_HtmlEncode($this->assign_comment->CurrentValue);
			$this->assign_comment->PlaceHolder = ew_RemoveHtml($this->assign_comment->FldCaption());

			// assign_by
			$this->assign_by->EditAttrs["class"] = "form-control";
			$this->assign_by->EditCustomAttributes = "";
			$this->assign_by->EditValue = ew_HtmlEncode($this->assign_by->CurrentValue);
			$this->assign_by->PlaceHolder = ew_RemoveHtml($this->assign_by->FldCaption());

			// statuse
			$this->statuse->EditAttrs["class"] = "form-control";
			$this->statuse->EditCustomAttributes = "";
			$this->statuse->EditValue = ew_HtmlEncode($this->statuse->CurrentValue);
			$this->statuse->PlaceHolder = ew_RemoveHtml($this->statuse->FldCaption());

			// date_retrieved
			$this->date_retrieved->EditAttrs["class"] = "form-control";
			$this->date_retrieved->EditCustomAttributes = "";
			$this->date_retrieved->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_retrieved->CurrentValue, 8));
			$this->date_retrieved->PlaceHolder = ew_RemoveHtml($this->date_retrieved->FldCaption());

			// retriever_action
			$this->retriever_action->EditAttrs["class"] = "form-control";
			$this->retriever_action->EditCustomAttributes = "";
			$this->retriever_action->EditValue = ew_HtmlEncode($this->retriever_action->CurrentValue);
			$this->retriever_action->PlaceHolder = ew_RemoveHtml($this->retriever_action->FldCaption());

			// retriever_comment
			$this->retriever_comment->EditAttrs["class"] = "form-control";
			$this->retriever_comment->EditCustomAttributes = "";
			$this->retriever_comment->EditValue = ew_HtmlEncode($this->retriever_comment->CurrentValue);
			$this->retriever_comment->PlaceHolder = ew_RemoveHtml($this->retriever_comment->FldCaption());

			// retrieved_by
			$this->retrieved_by->EditAttrs["class"] = "form-control";
			$this->retrieved_by->EditCustomAttributes = "";
			$this->retrieved_by->EditValue = ew_HtmlEncode($this->retrieved_by->CurrentValue);
			$this->retrieved_by->PlaceHolder = ew_RemoveHtml($this->retrieved_by->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// issued_date
			$this->issued_date->LinkCustomAttributes = "";
			$this->issued_date->HrefValue = "";

			// reference_id
			$this->reference_id->LinkCustomAttributes = "";
			$this->reference_id->HrefValue = "";

			// asset_tag
			$this->asset_tag->LinkCustomAttributes = "";
			$this->asset_tag->HrefValue = "";

			// make
			$this->make->LinkCustomAttributes = "";
			$this->make->HrefValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";

			// assign_to
			$this->assign_to->LinkCustomAttributes = "";
			$this->assign_to->HrefValue = "";

			// date_assign
			$this->date_assign->LinkCustomAttributes = "";
			$this->date_assign->HrefValue = "";

			// assign_action
			$this->assign_action->LinkCustomAttributes = "";
			$this->assign_action->HrefValue = "";

			// assign_comment
			$this->assign_comment->LinkCustomAttributes = "";
			$this->assign_comment->HrefValue = "";

			// assign_by
			$this->assign_by->LinkCustomAttributes = "";
			$this->assign_by->HrefValue = "";

			// statuse
			$this->statuse->LinkCustomAttributes = "";
			$this->statuse->HrefValue = "";

			// date_retrieved
			$this->date_retrieved->LinkCustomAttributes = "";
			$this->date_retrieved->HrefValue = "";

			// retriever_action
			$this->retriever_action->LinkCustomAttributes = "";
			$this->retriever_action->HrefValue = "";

			// retriever_comment
			$this->retriever_comment->LinkCustomAttributes = "";
			$this->retriever_comment->HrefValue = "";

			// retrieved_by
			$this->retrieved_by->LinkCustomAttributes = "";
			$this->retrieved_by->HrefValue = "";
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
		if (!ew_CheckDateDef($this->issued_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->issued_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->department->FormValue)) {
			ew_AddMessage($gsFormError, $this->department->FldErrMsg());
		}
		if (!ew_CheckInteger($this->designation->FormValue)) {
			ew_AddMessage($gsFormError, $this->designation->FldErrMsg());
		}
		if (!ew_CheckInteger($this->assign_to->FormValue)) {
			ew_AddMessage($gsFormError, $this->assign_to->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_assign->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_assign->FldErrMsg());
		}
		if (!ew_CheckInteger($this->assign_action->FormValue)) {
			ew_AddMessage($gsFormError, $this->assign_action->FldErrMsg());
		}
		if (!ew_CheckInteger($this->assign_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->assign_by->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_retrieved->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_retrieved->FldErrMsg());
		}
		if (!ew_CheckInteger($this->retriever_action->FormValue)) {
			ew_AddMessage($gsFormError, $this->retriever_action->FldErrMsg());
		}
		if (!ew_CheckInteger($this->retrieved_by->FormValue)) {
			ew_AddMessage($gsFormError, $this->retrieved_by->FldErrMsg());
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

			// issued_date
			$this->issued_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->issued_date->CurrentValue, 0), NULL, $this->issued_date->ReadOnly);

			// reference_id
			$this->reference_id->SetDbValueDef($rsnew, $this->reference_id->CurrentValue, NULL, $this->reference_id->ReadOnly);

			// asset_tag
			$this->asset_tag->SetDbValueDef($rsnew, $this->asset_tag->CurrentValue, NULL, $this->asset_tag->ReadOnly);

			// make
			$this->make->SetDbValueDef($rsnew, $this->make->CurrentValue, NULL, $this->make->ReadOnly);

			// color
			$this->color->SetDbValueDef($rsnew, $this->color->CurrentValue, NULL, $this->color->ReadOnly);

			// department
			$this->department->SetDbValueDef($rsnew, $this->department->CurrentValue, NULL, $this->department->ReadOnly);

			// designation
			$this->designation->SetDbValueDef($rsnew, $this->designation->CurrentValue, NULL, $this->designation->ReadOnly);

			// assign_to
			$this->assign_to->SetDbValueDef($rsnew, $this->assign_to->CurrentValue, NULL, $this->assign_to->ReadOnly);

			// date_assign
			$this->date_assign->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_assign->CurrentValue, 0), NULL, $this->date_assign->ReadOnly);

			// assign_action
			$this->assign_action->SetDbValueDef($rsnew, $this->assign_action->CurrentValue, NULL, $this->assign_action->ReadOnly);

			// assign_comment
			$this->assign_comment->SetDbValueDef($rsnew, $this->assign_comment->CurrentValue, NULL, $this->assign_comment->ReadOnly);

			// assign_by
			$this->assign_by->SetDbValueDef($rsnew, $this->assign_by->CurrentValue, NULL, $this->assign_by->ReadOnly);

			// statuse
			$this->statuse->SetDbValueDef($rsnew, $this->statuse->CurrentValue, NULL, $this->statuse->ReadOnly);

			// date_retrieved
			$this->date_retrieved->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_retrieved->CurrentValue, 0), NULL, $this->date_retrieved->ReadOnly);

			// retriever_action
			$this->retriever_action->SetDbValueDef($rsnew, $this->retriever_action->CurrentValue, NULL, $this->retriever_action->ReadOnly);

			// retriever_comment
			$this->retriever_comment->SetDbValueDef($rsnew, $this->retriever_comment->CurrentValue, NULL, $this->retriever_comment->ReadOnly);

			// retrieved_by
			$this->retrieved_by->SetDbValueDef($rsnew, $this->retrieved_by->CurrentValue, NULL, $this->retrieved_by->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pc_issuancelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($pc_issuance_edit)) $pc_issuance_edit = new cpc_issuance_edit();

// Page init
$pc_issuance_edit->Page_Init();

// Page main
$pc_issuance_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pc_issuance_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpc_issuanceedit = new ew_Form("fpc_issuanceedit", "edit");

// Validate form
fpc_issuanceedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_issued_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->issued_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_department");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->department->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_designation");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->designation->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_assign_to");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->assign_to->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_date_assign");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->date_assign->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_assign_action");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->assign_action->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_assign_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->assign_by->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_date_retrieved");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->date_retrieved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_retriever_action");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->retriever_action->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_retrieved_by");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pc_issuance->retrieved_by->FldErrMsg()) ?>");

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
fpc_issuanceedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpc_issuanceedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $pc_issuance_edit->ShowPageHeader(); ?>
<?php
$pc_issuance_edit->ShowMessage();
?>
<?php if (!$pc_issuance_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pc_issuance_edit->Pager)) $pc_issuance_edit->Pager = new cPrevNextPager($pc_issuance_edit->StartRec, $pc_issuance_edit->DisplayRecs, $pc_issuance_edit->TotalRecs, $pc_issuance_edit->AutoHidePager) ?>
<?php if ($pc_issuance_edit->Pager->RecordCount > 0 && $pc_issuance_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pc_issuance_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pc_issuance_edit->PageUrl() ?>start=<?php echo $pc_issuance_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pc_issuance_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pc_issuance_edit->PageUrl() ?>start=<?php echo $pc_issuance_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pc_issuance_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pc_issuance_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pc_issuance_edit->PageUrl() ?>start=<?php echo $pc_issuance_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pc_issuance_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pc_issuance_edit->PageUrl() ?>start=<?php echo $pc_issuance_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pc_issuance_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fpc_issuanceedit" id="fpc_issuanceedit" class="<?php echo $pc_issuance_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pc_issuance_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pc_issuance_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pc_issuance">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($pc_issuance_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($pc_issuance->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_pc_issuance_id" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->id->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->id->CellAttributes() ?>>
<span id="el_pc_issuance_id">
<span<?php echo $pc_issuance->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pc_issuance->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pc_issuance" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($pc_issuance->id->CurrentValue) ?>">
<?php echo $pc_issuance->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->issued_date->Visible) { // issued_date ?>
	<div id="r_issued_date" class="form-group">
		<label id="elh_pc_issuance_issued_date" for="x_issued_date" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->issued_date->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->issued_date->CellAttributes() ?>>
<span id="el_pc_issuance_issued_date">
<input type="text" data-table="pc_issuance" data-field="x_issued_date" name="x_issued_date" id="x_issued_date" placeholder="<?php echo ew_HtmlEncode($pc_issuance->issued_date->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->issued_date->EditValue ?>"<?php echo $pc_issuance->issued_date->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->issued_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->reference_id->Visible) { // reference_id ?>
	<div id="r_reference_id" class="form-group">
		<label id="elh_pc_issuance_reference_id" for="x_reference_id" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->reference_id->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->reference_id->CellAttributes() ?>>
<span id="el_pc_issuance_reference_id">
<input type="text" data-table="pc_issuance" data-field="x_reference_id" name="x_reference_id" id="x_reference_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance->reference_id->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->reference_id->EditValue ?>"<?php echo $pc_issuance->reference_id->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->reference_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->asset_tag->Visible) { // asset_tag ?>
	<div id="r_asset_tag" class="form-group">
		<label id="elh_pc_issuance_asset_tag" for="x_asset_tag" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->asset_tag->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->asset_tag->CellAttributes() ?>>
<span id="el_pc_issuance_asset_tag">
<input type="text" data-table="pc_issuance" data-field="x_asset_tag" name="x_asset_tag" id="x_asset_tag" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pc_issuance->asset_tag->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->asset_tag->EditValue ?>"<?php echo $pc_issuance->asset_tag->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->asset_tag->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->make->Visible) { // make ?>
	<div id="r_make" class="form-group">
		<label id="elh_pc_issuance_make" for="x_make" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->make->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->make->CellAttributes() ?>>
<span id="el_pc_issuance_make">
<input type="text" data-table="pc_issuance" data-field="x_make" name="x_make" id="x_make" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance->make->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->make->EditValue ?>"<?php echo $pc_issuance->make->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->make->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->color->Visible) { // color ?>
	<div id="r_color" class="form-group">
		<label id="elh_pc_issuance_color" for="x_color" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->color->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->color->CellAttributes() ?>>
<span id="el_pc_issuance_color">
<input type="text" data-table="pc_issuance" data-field="x_color" name="x_color" id="x_color" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pc_issuance->color->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->color->EditValue ?>"<?php echo $pc_issuance->color->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->color->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label id="elh_pc_issuance_department" for="x_department" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->department->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->department->CellAttributes() ?>>
<span id="el_pc_issuance_department">
<input type="text" data-table="pc_issuance" data-field="x_department" name="x_department" id="x_department" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->department->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->department->EditValue ?>"<?php echo $pc_issuance->department->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->department->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->designation->Visible) { // designation ?>
	<div id="r_designation" class="form-group">
		<label id="elh_pc_issuance_designation" for="x_designation" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->designation->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->designation->CellAttributes() ?>>
<span id="el_pc_issuance_designation">
<input type="text" data-table="pc_issuance" data-field="x_designation" name="x_designation" id="x_designation" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->designation->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->designation->EditValue ?>"<?php echo $pc_issuance->designation->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->designation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->assign_to->Visible) { // assign_to ?>
	<div id="r_assign_to" class="form-group">
		<label id="elh_pc_issuance_assign_to" for="x_assign_to" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->assign_to->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->assign_to->CellAttributes() ?>>
<span id="el_pc_issuance_assign_to">
<input type="text" data-table="pc_issuance" data-field="x_assign_to" name="x_assign_to" id="x_assign_to" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->assign_to->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->assign_to->EditValue ?>"<?php echo $pc_issuance->assign_to->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->assign_to->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->date_assign->Visible) { // date_assign ?>
	<div id="r_date_assign" class="form-group">
		<label id="elh_pc_issuance_date_assign" for="x_date_assign" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->date_assign->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->date_assign->CellAttributes() ?>>
<span id="el_pc_issuance_date_assign">
<input type="text" data-table="pc_issuance" data-field="x_date_assign" name="x_date_assign" id="x_date_assign" placeholder="<?php echo ew_HtmlEncode($pc_issuance->date_assign->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->date_assign->EditValue ?>"<?php echo $pc_issuance->date_assign->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->date_assign->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->assign_action->Visible) { // assign_action ?>
	<div id="r_assign_action" class="form-group">
		<label id="elh_pc_issuance_assign_action" for="x_assign_action" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->assign_action->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->assign_action->CellAttributes() ?>>
<span id="el_pc_issuance_assign_action">
<input type="text" data-table="pc_issuance" data-field="x_assign_action" name="x_assign_action" id="x_assign_action" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->assign_action->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->assign_action->EditValue ?>"<?php echo $pc_issuance->assign_action->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->assign_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->assign_comment->Visible) { // assign_comment ?>
	<div id="r_assign_comment" class="form-group">
		<label id="elh_pc_issuance_assign_comment" for="x_assign_comment" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->assign_comment->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->assign_comment->CellAttributes() ?>>
<span id="el_pc_issuance_assign_comment">
<textarea data-table="pc_issuance" data-field="x_assign_comment" name="x_assign_comment" id="x_assign_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($pc_issuance->assign_comment->getPlaceHolder()) ?>"<?php echo $pc_issuance->assign_comment->EditAttributes() ?>><?php echo $pc_issuance->assign_comment->EditValue ?></textarea>
</span>
<?php echo $pc_issuance->assign_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->assign_by->Visible) { // assign_by ?>
	<div id="r_assign_by" class="form-group">
		<label id="elh_pc_issuance_assign_by" for="x_assign_by" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->assign_by->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->assign_by->CellAttributes() ?>>
<span id="el_pc_issuance_assign_by">
<input type="text" data-table="pc_issuance" data-field="x_assign_by" name="x_assign_by" id="x_assign_by" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->assign_by->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->assign_by->EditValue ?>"<?php echo $pc_issuance->assign_by->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->assign_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->statuse->Visible) { // statuse ?>
	<div id="r_statuse" class="form-group">
		<label id="elh_pc_issuance_statuse" for="x_statuse" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->statuse->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->statuse->CellAttributes() ?>>
<span id="el_pc_issuance_statuse">
<input type="text" data-table="pc_issuance" data-field="x_statuse" name="x_statuse" id="x_statuse" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pc_issuance->statuse->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->statuse->EditValue ?>"<?php echo $pc_issuance->statuse->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->statuse->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->date_retrieved->Visible) { // date_retrieved ?>
	<div id="r_date_retrieved" class="form-group">
		<label id="elh_pc_issuance_date_retrieved" for="x_date_retrieved" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->date_retrieved->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->date_retrieved->CellAttributes() ?>>
<span id="el_pc_issuance_date_retrieved">
<input type="text" data-table="pc_issuance" data-field="x_date_retrieved" name="x_date_retrieved" id="x_date_retrieved" placeholder="<?php echo ew_HtmlEncode($pc_issuance->date_retrieved->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->date_retrieved->EditValue ?>"<?php echo $pc_issuance->date_retrieved->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->date_retrieved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->retriever_action->Visible) { // retriever_action ?>
	<div id="r_retriever_action" class="form-group">
		<label id="elh_pc_issuance_retriever_action" for="x_retriever_action" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->retriever_action->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->retriever_action->CellAttributes() ?>>
<span id="el_pc_issuance_retriever_action">
<input type="text" data-table="pc_issuance" data-field="x_retriever_action" name="x_retriever_action" id="x_retriever_action" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->retriever_action->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->retriever_action->EditValue ?>"<?php echo $pc_issuance->retriever_action->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->retriever_action->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->retriever_comment->Visible) { // retriever_comment ?>
	<div id="r_retriever_comment" class="form-group">
		<label id="elh_pc_issuance_retriever_comment" for="x_retriever_comment" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->retriever_comment->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->retriever_comment->CellAttributes() ?>>
<span id="el_pc_issuance_retriever_comment">
<textarea data-table="pc_issuance" data-field="x_retriever_comment" name="x_retriever_comment" id="x_retriever_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($pc_issuance->retriever_comment->getPlaceHolder()) ?>"<?php echo $pc_issuance->retriever_comment->EditAttributes() ?>><?php echo $pc_issuance->retriever_comment->EditValue ?></textarea>
</span>
<?php echo $pc_issuance->retriever_comment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pc_issuance->retrieved_by->Visible) { // retrieved_by ?>
	<div id="r_retrieved_by" class="form-group">
		<label id="elh_pc_issuance_retrieved_by" for="x_retrieved_by" class="<?php echo $pc_issuance_edit->LeftColumnClass ?>"><?php echo $pc_issuance->retrieved_by->FldCaption() ?></label>
		<div class="<?php echo $pc_issuance_edit->RightColumnClass ?>"><div<?php echo $pc_issuance->retrieved_by->CellAttributes() ?>>
<span id="el_pc_issuance_retrieved_by">
<input type="text" data-table="pc_issuance" data-field="x_retrieved_by" name="x_retrieved_by" id="x_retrieved_by" size="30" placeholder="<?php echo ew_HtmlEncode($pc_issuance->retrieved_by->getPlaceHolder()) ?>" value="<?php echo $pc_issuance->retrieved_by->EditValue ?>"<?php echo $pc_issuance->retrieved_by->EditAttributes() ?>>
</span>
<?php echo $pc_issuance->retrieved_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$pc_issuance_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $pc_issuance_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pc_issuance_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fpc_issuanceedit.Init();
</script>
<?php
$pc_issuance_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pc_issuance_edit->Page_Terminate();
?>
