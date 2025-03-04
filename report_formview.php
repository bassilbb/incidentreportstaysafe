<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "report_forminfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$report_form_view = NULL; // Initialize page object first

class creport_form_view extends creport_form {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'report_form';

	// Page object name
	var $PageObjName = 'report_form_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
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

		// Table object (report_form)
		if (!isset($GLOBALS["report_form"]) || get_class($GLOBALS["report_form"]) == "creport_form") {
			$GLOBALS["report_form"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["report_form"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'report_form', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("report_formlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= $_GET["id"];
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
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
		$this->selection_sub_category->SetVisibility();
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
		$this->rejection_reasons->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->report_by->SetVisibility();
		$this->datetime_resolved->SetVisibility();
		$this->assign->SetVisibility();
		$this->approval_action->SetVisibility();
		$this->approval_comment->SetVisibility();
		$this->item_name->SetVisibility();
		$this->quantity_issued->SetVisibility();
		$this->reason->SetVisibility();
		$this->resolved_action->SetVisibility();
		$this->resolved_comment->SetVisibility();
		$this->resolved_by->SetVisibility();
		$this->datetime_approved->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->last_updated_date->SetVisibility();
		$this->last_updated_by->SetVisibility();
		$this->verified_datetime->SetVisibility();
		$this->job_assessment->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
		$this->verified_by->SetVisibility();
		$this->remainder->SetVisibility();

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
		global $EW_EXPORT, $report_form;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($report_form);
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
					if ($pageName == "report_formview.php")
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;
	var $MultiPages; // Multi pages object

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $gbSkipHeaderFooter, $EW_EXPORT;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("report_formlist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetupStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "report_formlist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "report_formlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddQuery($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = TRUE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
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
		$this->selection_sub_category->setDbValue($row['selection_sub_category']);
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
		$this->rejection_reasons->setDbValue($row['rejection_reasons']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->report_by->setDbValue($row['report_by']);
		$this->datetime_resolved->setDbValue($row['datetime_resolved']);
		$this->assign->setDbValue($row['assign']);
		$this->assign_task->setDbValue($row['assign_task']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->item_name->setDbValue($row['item_name']);
		$this->quantity_issued->setDbValue($row['quantity_issued']);
		$this->reason->setDbValue($row['reason']);
		$this->resolved_action->setDbValue($row['resolved_action']);
		$this->resolved_comment->setDbValue($row['resolved_comment']);
		$this->resolved_by->setDbValue($row['resolved_by']);
		$this->datetime_approved->setDbValue($row['datetime_approved']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->last_updated_date->setDbValue($row['last_updated_date']);
		$this->last_updated_by->setDbValue($row['last_updated_by']);
		$this->verified_datetime->setDbValue($row['verified_datetime']);
		$this->job_assessment->setDbValue($row['job_assessment']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->verified_by->setDbValue($row['verified_by']);
		$this->remainder->setDbValue($row['remainder']);
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
		$row['selection_sub_category'] = NULL;
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
		$row['rejection_reasons'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['report_by'] = NULL;
		$row['datetime_resolved'] = NULL;
		$row['assign'] = NULL;
		$row['assign_task'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['item_name'] = NULL;
		$row['quantity_issued'] = NULL;
		$row['reason'] = NULL;
		$row['resolved_action'] = NULL;
		$row['resolved_comment'] = NULL;
		$row['resolved_by'] = NULL;
		$row['datetime_approved'] = NULL;
		$row['approved_by'] = NULL;
		$row['last_updated_date'] = NULL;
		$row['last_updated_by'] = NULL;
		$row['verified_datetime'] = NULL;
		$row['job_assessment'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['verified_by'] = NULL;
		$row['remainder'] = NULL;
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
		$this->selection_sub_category->DbValue = $row['selection_sub_category'];
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
		$this->rejection_reasons->DbValue = $row['rejection_reasons'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->report_by->DbValue = $row['report_by'];
		$this->datetime_resolved->DbValue = $row['datetime_resolved'];
		$this->assign->DbValue = $row['assign'];
		$this->assign_task->DbValue = $row['assign_task'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->item_name->DbValue = $row['item_name'];
		$this->quantity_issued->DbValue = $row['quantity_issued'];
		$this->reason->DbValue = $row['reason'];
		$this->resolved_action->DbValue = $row['resolved_action'];
		$this->resolved_comment->DbValue = $row['resolved_comment'];
		$this->resolved_by->DbValue = $row['resolved_by'];
		$this->datetime_approved->DbValue = $row['datetime_approved'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->last_updated_date->DbValue = $row['last_updated_date'];
		$this->last_updated_by->DbValue = $row['last_updated_by'];
		$this->verified_datetime->DbValue = $row['verified_datetime'];
		$this->job_assessment->DbValue = $row['job_assessment'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->verified_by->DbValue = $row['verified_by'];
		$this->remainder->DbValue = $row['remainder'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		// selection_sub_category
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
		// rejection_reasons
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// assign
		// assign_task
		// approval_action
		// approval_comment
		// item_name
		// quantity_issued
		// reason
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approved_by
		// last_updated_date
		// last_updated_by
		// verified_datetime
		// job_assessment
		// verified_action
		// verified_comment
		// verified_by
		// remainder

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 11);
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array();
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
		$this->branch->LookupFilters = array();
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
		$this->departments->LookupFilters = array();
		$lookuptblfilter = "`flag`='2'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
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

		// selection_sub_category
		if (strval($this->selection_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->selection_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `selection_sub_category`";
		$sWhereWrk = "";
		$this->selection_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->selection_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->selection_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->selection_sub_category->ViewValue .= $this->selection_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->selection_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->selection_sub_category->ViewValue = $this->selection_sub_category->CurrentValue;
			}
		} else {
			$this->selection_sub_category->ViewValue = NULL;
		}
		$this->selection_sub_category->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 7);
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
		$this->incident_location->LookupFilters = array();
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
		$this->status->LookupFilters = array();
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

		// rejection_reasons
		$this->rejection_reasons->ViewValue = $this->rejection_reasons->CurrentValue;
		$this->rejection_reasons->ViewCustomAttributes = "";

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

		// assign
		if (strval($this->assign->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->assign->ViewValue = $this->assign->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign->ViewValue = $this->assign->CurrentValue;
			}
		} else {
			$this->assign->ViewValue = NULL;
		}
		$this->assign->ViewCustomAttributes = "";

		// assign_task
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

		// item_name
		if (strval($this->item_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->item_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
		$sWhereWrk = "";
		$this->item_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->item_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->item_name->ViewValue = $this->item_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->item_name->ViewValue = $this->item_name->CurrentValue;
			}
		} else {
			$this->item_name->ViewValue = NULL;
		}
		$this->item_name->ViewCustomAttributes = "";

		// quantity_issued
		$this->quantity_issued->ViewValue = $this->quantity_issued->CurrentValue;
		$this->quantity_issued->ViewCustomAttributes = "";

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

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 17);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array();
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

		// verified_datetime
		$this->verified_datetime->ViewValue = $this->verified_datetime->CurrentValue;
		$this->verified_datetime->ViewValue = ew_FormatDateTime($this->verified_datetime->ViewValue, 17);
		$this->verified_datetime->ViewCustomAttributes = "";

		// job_assessment
		if (strval($this->job_assessment->CurrentValue) <> "") {
			$this->job_assessment->ViewValue = $this->job_assessment->OptionCaption($this->job_assessment->CurrentValue);
		} else {
			$this->job_assessment->ViewValue = NULL;
		}
		$this->job_assessment->ViewCustomAttributes = "";

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

		// remainder
		if (strval($this->remainder->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->remainder->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->remainder->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->remainder, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->remainder->ViewValue = $this->remainder->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->remainder->ViewValue = $this->remainder->CurrentValue;
			}
		} else {
			$this->remainder->ViewValue = NULL;
		}
		$this->remainder->ViewCustomAttributes = "";

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

			// selection_sub_category
			$this->selection_sub_category->LinkCustomAttributes = "";
			$this->selection_sub_category->HrefValue = "";
			$this->selection_sub_category->TooltipValue = "";

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
				$this->_upload->LinkAttrs["data-rel"] = "report_form_x__upload";
				ew_AppendClass($this->_upload->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// rejection_reasons
			$this->rejection_reasons->LinkCustomAttributes = "";
			$this->rejection_reasons->HrefValue = "";
			$this->rejection_reasons->TooltipValue = "";

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

			// assign
			$this->assign->LinkCustomAttributes = "";
			$this->assign->HrefValue = "";
			$this->assign->TooltipValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";
			$this->approval_action->TooltipValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";
			$this->approval_comment->TooltipValue = "";

			// item_name
			$this->item_name->LinkCustomAttributes = "";
			$this->item_name->HrefValue = "";
			$this->item_name->TooltipValue = "";

			// quantity_issued
			$this->quantity_issued->LinkCustomAttributes = "";
			$this->quantity_issued->HrefValue = "";
			$this->quantity_issued->TooltipValue = "";

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

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";

			// verified_datetime
			$this->verified_datetime->LinkCustomAttributes = "";
			$this->verified_datetime->HrefValue = "";
			$this->verified_datetime->TooltipValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";
			$this->job_assessment->TooltipValue = "";

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

			// remainder
			$this->remainder->LinkCustomAttributes = "";
			$this->remainder->HrefValue = "";
			$this->remainder->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_report_form\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_report_form',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.freport_formview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetupStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("report_formlist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($report_form_view)) $report_form_view = new creport_form_view();

// Page init
$report_form_view->Page_Init();

// Page main
$report_form_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$report_form_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($report_form->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = freport_formview = new ew_Form("freport_formview", "view");

// Form_CustomValidate event
freport_formview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freport_formview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
freport_formview.MultiPage = new ew_MultiPage("freport_formview");

// Dynamic selection lists
freport_formview.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_staffid"].Data = "<?php echo $report_form_view->staffid->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->staffid->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_staff_id"].Data = "<?php echo $report_form_view->staff_id->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->staff_id->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
freport_formview.Lists["x_department"].Data = "<?php echo $report_form_view->department->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
freport_formview.Lists["x_branch"].Data = "<?php echo $report_form_view->branch->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freport_formview.Lists["x_departments"].Data = "<?php echo $report_form_view->departments->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"category"};
freport_formview.Lists["x_category"].Data = "<?php echo $report_form_view->category->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":[],"ChildFields":["x_sub_sub_category[]"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sub_category"};
freport_formview.Lists["x_sub_category"].Data = "<?php echo $report_form_view->sub_category->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_sub_sub_category[]"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_selection_sub_category[]"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sub_sub_category"};
freport_formview.Lists["x_sub_sub_category[]"].Data = "<?php echo $report_form_view->sub_sub_category->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_selection_sub_category[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"selection_sub_category"};
freport_formview.Lists["x_selection_sub_category[]"].Data = "<?php echo $report_form_view->selection_sub_category->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_incident_type"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"type_of_incident"};
freport_formview.Lists["x_incident_type"].Data = "<?php echo $report_form_view->incident_type->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_incident_category"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
freport_formview.Lists["x_incident_category"].Data = "<?php echo $report_form_view->incident_category->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_incident_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freport_formview.Lists["x_incident_location"].Data = "<?php echo $report_form_view->incident_location->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_incident_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_venue"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
freport_formview.Lists["x_incident_sub_location"].Data = "<?php echo $report_form_view->incident_sub_location->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_incident_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_venue"};
freport_formview.Lists["x_incident_venue"].Data = "<?php echo $report_form_view->incident_venue->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freport_formview.Lists["x_status"].Data = "<?php echo $report_form_view->status->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formview.Lists["x_initiator_action"].Options = <?php echo json_encode($report_form_view->initiator_action->Options()) ?>;
freport_formview.Lists["x_report_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_report_by"].Data = "<?php echo $report_form_view->report_by->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_report_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->report_by->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_assign"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_assign"].Data = "<?php echo $report_form_view->assign->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formview.Lists["x_approval_action"].Options = <?php echo json_encode($report_form_view->approval_action->Options()) ?>;
freport_formview.Lists["x_item_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_material_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"inventory"};
freport_formview.Lists["x_item_name"].Data = "<?php echo $report_form_view->item_name->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_reason"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"reason"};
freport_formview.Lists["x_reason"].Data = "<?php echo $report_form_view->reason->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.Lists["x_resolved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formview.Lists["x_resolved_action"].Options = <?php echo json_encode($report_form_view->resolved_action->Options()) ?>;
freport_formview.Lists["x_resolved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_resolved_by"].Data = "<?php echo $report_form_view->resolved_by->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_resolved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->resolved_by->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_approved_by"].Data = "<?php echo $report_form_view->approved_by->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->approved_by->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_last_updated_by"].Data = "<?php echo $report_form_view->last_updated_by->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->last_updated_by->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_job_assessment"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formview.Lists["x_job_assessment"].Options = <?php echo json_encode($report_form_view->job_assessment->Options()) ?>;
freport_formview.Lists["x_verified_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formview.Lists["x_verified_action"].Options = <?php echo json_encode($report_form_view->verified_action->Options()) ?>;
freport_formview.Lists["x_verified_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_verified_by"].Data = "<?php echo $report_form_view->verified_by->LookupFilterQuery(FALSE, "view") ?>";
freport_formview.AutoSuggests["x_verified_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_view->verified_by->LookupFilterQuery(TRUE, "view"))) ?>;
freport_formview.Lists["x_remainder"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formview.Lists["x_remainder"].Data = "<?php echo $report_form_view->remainder->LookupFilterQuery(FALSE, "view") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($report_form->Export == "") { ?>
<div class="ewToolbar">
<?php $report_form_view->ExportOptions->Render("body") ?>
<?php
	foreach ($report_form_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $report_form_view->ShowPageHeader(); ?>
<?php
$report_form_view->ShowMessage();
?>
<?php if (!$report_form_view->IsModal) { ?>
<?php if ($report_form->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($report_form_view->Pager)) $report_form_view->Pager = new cPrevNextPager($report_form_view->StartRec, $report_form_view->DisplayRecs, $report_form_view->TotalRecs, $report_form_view->AutoHidePager) ?>
<?php if ($report_form_view->Pager->RecordCount > 0 && $report_form_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($report_form_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $report_form_view->PageUrl() ?>start=<?php echo $report_form_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($report_form_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $report_form_view->PageUrl() ?>start=<?php echo $report_form_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $report_form_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($report_form_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $report_form_view->PageUrl() ?>start=<?php echo $report_form_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($report_form_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $report_form_view->PageUrl() ?>start=<?php echo $report_form_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $report_form_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="freport_formview" id="freport_formview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($report_form_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $report_form_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="report_form">
<input type="hidden" name="modal" value="<?php echo intval($report_form_view->IsModal) ?>">
<?php if ($report_form->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="report_form_view">
	<ul class="nav<?php echo $report_form_view->MultiPages->NavStyle() ?>">
		<li<?php echo $report_form_view->MultiPages->TabStyle("1") ?>><a href="#tab_report_form1" data-toggle="tab"><?php echo $report_form->PageCaption(1) ?></a></li>
		<li<?php echo $report_form_view->MultiPages->TabStyle("2") ?>><a href="#tab_report_form2" data-toggle="tab"><?php echo $report_form->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($report_form->Export == "") { ?>
		<div class="tab-pane<?php echo $report_form_view->MultiPages->PageStyle("1") ?>" id="tab_report_form1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($report_form->datetime_initiated->Visible) { // datetime_initiated ?>
	<tr id="r_datetime_initiated">
		<td class="col-sm-2"><span id="elh_report_form_datetime_initiated"><?php echo $report_form->datetime_initiated->FldCaption() ?></span></td>
		<td data-name="datetime_initiated"<?php echo $report_form->datetime_initiated->CellAttributes() ?>>
<span id="el_report_form_datetime_initiated" data-page="1">
<span<?php echo $report_form->datetime_initiated->ViewAttributes() ?>>
<?php echo $report_form->datetime_initiated->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_id->Visible) { // incident_id ?>
	<tr id="r_incident_id">
		<td class="col-sm-2"><span id="elh_report_form_incident_id"><?php echo $report_form->incident_id->FldCaption() ?></span></td>
		<td data-name="incident_id"<?php echo $report_form->incident_id->CellAttributes() ?>>
<span id="el_report_form_incident_id" data-page="1">
<span<?php echo $report_form->incident_id->ViewAttributes() ?>>
<?php echo $report_form->incident_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->staffid->Visible) { // staffid ?>
	<tr id="r_staffid">
		<td class="col-sm-2"><span id="elh_report_form_staffid"><?php echo $report_form->staffid->FldCaption() ?></span></td>
		<td data-name="staffid"<?php echo $report_form->staffid->CellAttributes() ?>>
<span id="el_report_form_staffid" data-page="1">
<span<?php echo $report_form->staffid->ViewAttributes() ?>>
<?php echo $report_form->staffid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->staff_id->Visible) { // staff_id ?>
	<tr id="r_staff_id">
		<td class="col-sm-2"><span id="elh_report_form_staff_id"><?php echo $report_form->staff_id->FldCaption() ?></span></td>
		<td data-name="staff_id"<?php echo $report_form->staff_id->CellAttributes() ?>>
<span id="el_report_form_staff_id" data-page="1">
<span<?php echo $report_form->staff_id->ViewAttributes() ?>>
<?php echo $report_form->staff_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->department->Visible) { // department ?>
	<tr id="r_department">
		<td class="col-sm-2"><span id="elh_report_form_department"><?php echo $report_form->department->FldCaption() ?></span></td>
		<td data-name="department"<?php echo $report_form->department->CellAttributes() ?>>
<span id="el_report_form_department" data-page="1">
<span<?php echo $report_form->department->ViewAttributes() ?>>
<?php echo $report_form->department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->branch->Visible) { // branch ?>
	<tr id="r_branch">
		<td class="col-sm-2"><span id="elh_report_form_branch"><?php echo $report_form->branch->FldCaption() ?></span></td>
		<td data-name="branch"<?php echo $report_form->branch->CellAttributes() ?>>
<span id="el_report_form_branch" data-page="1">
<span<?php echo $report_form->branch->ViewAttributes() ?>>
<?php echo $report_form->branch->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->departments->Visible) { // departments ?>
	<tr id="r_departments">
		<td class="col-sm-2"><span id="elh_report_form_departments"><?php echo $report_form->departments->FldCaption() ?></span></td>
		<td data-name="departments"<?php echo $report_form->departments->CellAttributes() ?>>
<span id="el_report_form_departments" data-page="1">
<span<?php echo $report_form->departments->ViewAttributes() ?>>
<?php echo $report_form->departments->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->category->Visible) { // category ?>
	<tr id="r_category">
		<td class="col-sm-2"><span id="elh_report_form_category"><?php echo $report_form->category->FldCaption() ?></span></td>
		<td data-name="category"<?php echo $report_form->category->CellAttributes() ?>>
<span id="el_report_form_category" data-page="1">
<span<?php echo $report_form->category->ViewAttributes() ?>>
<?php echo $report_form->category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->sub_category->Visible) { // sub_category ?>
	<tr id="r_sub_category">
		<td class="col-sm-2"><span id="elh_report_form_sub_category"><?php echo $report_form->sub_category->FldCaption() ?></span></td>
		<td data-name="sub_category"<?php echo $report_form->sub_category->CellAttributes() ?>>
<span id="el_report_form_sub_category" data-page="1">
<span<?php echo $report_form->sub_category->ViewAttributes() ?>>
<?php echo $report_form->sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->sub_sub_category->Visible) { // sub_sub_category ?>
	<tr id="r_sub_sub_category">
		<td class="col-sm-2"><span id="elh_report_form_sub_sub_category"><?php echo $report_form->sub_sub_category->FldCaption() ?></span></td>
		<td data-name="sub_sub_category"<?php echo $report_form->sub_sub_category->CellAttributes() ?>>
<span id="el_report_form_sub_sub_category" data-page="1">
<span<?php echo $report_form->sub_sub_category->ViewAttributes() ?>>
<?php echo $report_form->sub_sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->selection_sub_category->Visible) { // selection_sub_category ?>
	<tr id="r_selection_sub_category">
		<td class="col-sm-2"><span id="elh_report_form_selection_sub_category"><?php echo $report_form->selection_sub_category->FldCaption() ?></span></td>
		<td data-name="selection_sub_category"<?php echo $report_form->selection_sub_category->CellAttributes() ?>>
<span id="el_report_form_selection_sub_category" data-page="1">
<span<?php echo $report_form->selection_sub_category->ViewAttributes() ?>>
<?php echo $report_form->selection_sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->start_date->Visible) { // start_date ?>
	<tr id="r_start_date">
		<td class="col-sm-2"><span id="elh_report_form_start_date"><?php echo $report_form->start_date->FldCaption() ?></span></td>
		<td data-name="start_date"<?php echo $report_form->start_date->CellAttributes() ?>>
<span id="el_report_form_start_date" data-page="1">
<span<?php echo $report_form->start_date->ViewAttributes() ?>>
<?php echo $report_form->start_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->end_date->Visible) { // end_date ?>
	<tr id="r_end_date">
		<td class="col-sm-2"><span id="elh_report_form_end_date"><?php echo $report_form->end_date->FldCaption() ?></span></td>
		<td data-name="end_date"<?php echo $report_form->end_date->CellAttributes() ?>>
<span id="el_report_form_end_date" data-page="1">
<span<?php echo $report_form->end_date->ViewAttributes() ?>>
<?php echo $report_form->end_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->duration->Visible) { // duration ?>
	<tr id="r_duration">
		<td class="col-sm-2"><span id="elh_report_form_duration"><?php echo $report_form->duration->FldCaption() ?></span></td>
		<td data-name="duration"<?php echo $report_form->duration->CellAttributes() ?>>
<span id="el_report_form_duration" data-page="1">
<span<?php echo $report_form->duration->ViewAttributes() ?>>
<?php echo $report_form->duration->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->amount_paid->Visible) { // amount_paid ?>
	<tr id="r_amount_paid">
		<td class="col-sm-2"><span id="elh_report_form_amount_paid"><?php echo $report_form->amount_paid->FldCaption() ?></span></td>
		<td data-name="amount_paid"<?php echo $report_form->amount_paid->CellAttributes() ?>>
<span id="el_report_form_amount_paid" data-page="1">
<span<?php echo $report_form->amount_paid->ViewAttributes() ?>>
<?php echo $report_form->amount_paid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->no_of_people_involved->Visible) { // no_of_people_involved ?>
	<tr id="r_no_of_people_involved">
		<td class="col-sm-2"><span id="elh_report_form_no_of_people_involved"><?php echo $report_form->no_of_people_involved->FldCaption() ?></span></td>
		<td data-name="no_of_people_involved"<?php echo $report_form->no_of_people_involved->CellAttributes() ?>>
<span id="el_report_form_no_of_people_involved" data-page="1">
<span<?php echo $report_form->no_of_people_involved->ViewAttributes() ?>>
<?php echo $report_form->no_of_people_involved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_type->Visible) { // incident_type ?>
	<tr id="r_incident_type">
		<td class="col-sm-2"><span id="elh_report_form_incident_type"><?php echo $report_form->incident_type->FldCaption() ?></span></td>
		<td data-name="incident_type"<?php echo $report_form->incident_type->CellAttributes() ?>>
<span id="el_report_form_incident_type" data-page="1">
<span<?php echo $report_form->incident_type->ViewAttributes() ?>>
<?php echo $report_form->incident_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_category->Visible) { // incident-category ?>
	<tr id="r_incident_category">
		<td class="col-sm-2"><span id="elh_report_form_incident_category"><?php echo $report_form->incident_category->FldCaption() ?></span></td>
		<td data-name="incident_category"<?php echo $report_form->incident_category->CellAttributes() ?>>
<span id="el_report_form_incident_category" data-page="1">
<span<?php echo $report_form->incident_category->ViewAttributes() ?>>
<?php echo $report_form->incident_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_location->Visible) { // incident_location ?>
	<tr id="r_incident_location">
		<td class="col-sm-2"><span id="elh_report_form_incident_location"><?php echo $report_form->incident_location->FldCaption() ?></span></td>
		<td data-name="incident_location"<?php echo $report_form->incident_location->CellAttributes() ?>>
<span id="el_report_form_incident_location" data-page="1">
<span<?php echo $report_form->incident_location->ViewAttributes() ?>>
<?php echo $report_form->incident_location->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_sub_location->Visible) { // incident_sub_location ?>
	<tr id="r_incident_sub_location">
		<td class="col-sm-2"><span id="elh_report_form_incident_sub_location"><?php echo $report_form->incident_sub_location->FldCaption() ?></span></td>
		<td data-name="incident_sub_location"<?php echo $report_form->incident_sub_location->CellAttributes() ?>>
<span id="el_report_form_incident_sub_location" data-page="1">
<span<?php echo $report_form->incident_sub_location->ViewAttributes() ?>>
<?php echo $report_form->incident_sub_location->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_venue->Visible) { // incident_venue ?>
	<tr id="r_incident_venue">
		<td class="col-sm-2"><span id="elh_report_form_incident_venue"><?php echo $report_form->incident_venue->FldCaption() ?></span></td>
		<td data-name="incident_venue"<?php echo $report_form->incident_venue->CellAttributes() ?>>
<span id="el_report_form_incident_venue" data-page="1">
<span<?php echo $report_form->incident_venue->ViewAttributes() ?>>
<?php echo $report_form->incident_venue->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->incident_description->Visible) { // incident_description ?>
	<tr id="r_incident_description">
		<td class="col-sm-2"><span id="elh_report_form_incident_description"><?php echo $report_form->incident_description->FldCaption() ?></span></td>
		<td data-name="incident_description"<?php echo $report_form->incident_description->CellAttributes() ?>>
<span id="el_report_form_incident_description" data-page="1">
<span<?php echo $report_form->incident_description->ViewAttributes() ?>>
<?php echo $report_form->incident_description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->_upload->Visible) { // upload ?>
	<tr id="r__upload">
		<td class="col-sm-2"><span id="elh_report_form__upload"><?php echo $report_form->_upload->FldCaption() ?></span></td>
		<td data-name="_upload"<?php echo $report_form->_upload->CellAttributes() ?>>
<span id="el_report_form__upload" data-page="1">
<span>
<?php echo ew_GetFileViewTag($report_form->_upload, $report_form->_upload->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($report_form->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($report_form->Export == "") { ?>
		<div class="tab-pane<?php echo $report_form_view->MultiPages->PageStyle("2") ?>" id="tab_report_form2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($report_form->status->Visible) { // status ?>
	<tr id="r_status">
		<td class="col-sm-2"><span id="elh_report_form_status"><?php echo $report_form->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $report_form->status->CellAttributes() ?>>
<span id="el_report_form_status" data-page="2">
<span<?php echo $report_form->status->ViewAttributes() ?>>
<?php echo $report_form->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->rejection_reasons->Visible) { // rejection_reasons ?>
	<tr id="r_rejection_reasons">
		<td class="col-sm-2"><span id="elh_report_form_rejection_reasons"><?php echo $report_form->rejection_reasons->FldCaption() ?></span></td>
		<td data-name="rejection_reasons"<?php echo $report_form->rejection_reasons->CellAttributes() ?>>
<span id="el_report_form_rejection_reasons" data-page="2">
<span<?php echo $report_form->rejection_reasons->ViewAttributes() ?>>
<?php echo $report_form->rejection_reasons->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->initiator_action->Visible) { // initiator_action ?>
	<tr id="r_initiator_action">
		<td class="col-sm-2"><span id="elh_report_form_initiator_action"><?php echo $report_form->initiator_action->FldCaption() ?></span></td>
		<td data-name="initiator_action"<?php echo $report_form->initiator_action->CellAttributes() ?>>
<span id="el_report_form_initiator_action" data-page="2">
<span<?php echo $report_form->initiator_action->ViewAttributes() ?>>
<?php echo $report_form->initiator_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->initiator_comment->Visible) { // initiator_comment ?>
	<tr id="r_initiator_comment">
		<td class="col-sm-2"><span id="elh_report_form_initiator_comment"><?php echo $report_form->initiator_comment->FldCaption() ?></span></td>
		<td data-name="initiator_comment"<?php echo $report_form->initiator_comment->CellAttributes() ?>>
<span id="el_report_form_initiator_comment" data-page="2">
<span<?php echo $report_form->initiator_comment->ViewAttributes() ?>>
<?php echo $report_form->initiator_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->report_by->Visible) { // report_by ?>
	<tr id="r_report_by">
		<td class="col-sm-2"><span id="elh_report_form_report_by"><?php echo $report_form->report_by->FldCaption() ?></span></td>
		<td data-name="report_by"<?php echo $report_form->report_by->CellAttributes() ?>>
<span id="el_report_form_report_by" data-page="2">
<span<?php echo $report_form->report_by->ViewAttributes() ?>>
<?php echo $report_form->report_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->datetime_resolved->Visible) { // datetime_resolved ?>
	<tr id="r_datetime_resolved">
		<td class="col-sm-2"><span id="elh_report_form_datetime_resolved"><?php echo $report_form->datetime_resolved->FldCaption() ?></span></td>
		<td data-name="datetime_resolved"<?php echo $report_form->datetime_resolved->CellAttributes() ?>>
<span id="el_report_form_datetime_resolved" data-page="2">
<span<?php echo $report_form->datetime_resolved->ViewAttributes() ?>>
<?php echo $report_form->datetime_resolved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->assign->Visible) { // assign ?>
	<tr id="r_assign">
		<td class="col-sm-2"><span id="elh_report_form_assign"><?php echo $report_form->assign->FldCaption() ?></span></td>
		<td data-name="assign"<?php echo $report_form->assign->CellAttributes() ?>>
<span id="el_report_form_assign" data-page="2">
<span<?php echo $report_form->assign->ViewAttributes() ?>>
<?php echo $report_form->assign->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->approval_action->Visible) { // approval_action ?>
	<tr id="r_approval_action">
		<td class="col-sm-2"><span id="elh_report_form_approval_action"><?php echo $report_form->approval_action->FldCaption() ?></span></td>
		<td data-name="approval_action"<?php echo $report_form->approval_action->CellAttributes() ?>>
<span id="el_report_form_approval_action" data-page="2">
<span<?php echo $report_form->approval_action->ViewAttributes() ?>>
<?php echo $report_form->approval_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->approval_comment->Visible) { // approval_comment ?>
	<tr id="r_approval_comment">
		<td class="col-sm-2"><span id="elh_report_form_approval_comment"><?php echo $report_form->approval_comment->FldCaption() ?></span></td>
		<td data-name="approval_comment"<?php echo $report_form->approval_comment->CellAttributes() ?>>
<span id="el_report_form_approval_comment" data-page="2">
<span<?php echo $report_form->approval_comment->ViewAttributes() ?>>
<?php echo $report_form->approval_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->item_name->Visible) { // item_name ?>
	<tr id="r_item_name">
		<td class="col-sm-2"><span id="elh_report_form_item_name"><?php echo $report_form->item_name->FldCaption() ?></span></td>
		<td data-name="item_name"<?php echo $report_form->item_name->CellAttributes() ?>>
<span id="el_report_form_item_name" data-page="2">
<span<?php echo $report_form->item_name->ViewAttributes() ?>>
<?php echo $report_form->item_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->quantity_issued->Visible) { // quantity_issued ?>
	<tr id="r_quantity_issued">
		<td class="col-sm-2"><span id="elh_report_form_quantity_issued"><?php echo $report_form->quantity_issued->FldCaption() ?></span></td>
		<td data-name="quantity_issued"<?php echo $report_form->quantity_issued->CellAttributes() ?>>
<span id="el_report_form_quantity_issued" data-page="2">
<span<?php echo $report_form->quantity_issued->ViewAttributes() ?>>
<?php echo $report_form->quantity_issued->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->reason->Visible) { // reason ?>
	<tr id="r_reason">
		<td class="col-sm-2"><span id="elh_report_form_reason"><?php echo $report_form->reason->FldCaption() ?></span></td>
		<td data-name="reason"<?php echo $report_form->reason->CellAttributes() ?>>
<span id="el_report_form_reason" data-page="2">
<span<?php echo $report_form->reason->ViewAttributes() ?>>
<?php echo $report_form->reason->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->resolved_action->Visible) { // resolved_action ?>
	<tr id="r_resolved_action">
		<td class="col-sm-2"><span id="elh_report_form_resolved_action"><?php echo $report_form->resolved_action->FldCaption() ?></span></td>
		<td data-name="resolved_action"<?php echo $report_form->resolved_action->CellAttributes() ?>>
<span id="el_report_form_resolved_action" data-page="2">
<span<?php echo $report_form->resolved_action->ViewAttributes() ?>>
<?php echo $report_form->resolved_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->resolved_comment->Visible) { // resolved_comment ?>
	<tr id="r_resolved_comment">
		<td class="col-sm-2"><span id="elh_report_form_resolved_comment"><?php echo $report_form->resolved_comment->FldCaption() ?></span></td>
		<td data-name="resolved_comment"<?php echo $report_form->resolved_comment->CellAttributes() ?>>
<span id="el_report_form_resolved_comment" data-page="2">
<span<?php echo $report_form->resolved_comment->ViewAttributes() ?>>
<?php echo $report_form->resolved_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->resolved_by->Visible) { // resolved_by ?>
	<tr id="r_resolved_by">
		<td class="col-sm-2"><span id="elh_report_form_resolved_by"><?php echo $report_form->resolved_by->FldCaption() ?></span></td>
		<td data-name="resolved_by"<?php echo $report_form->resolved_by->CellAttributes() ?>>
<span id="el_report_form_resolved_by" data-page="2">
<span<?php echo $report_form->resolved_by->ViewAttributes() ?>>
<?php echo $report_form->resolved_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->datetime_approved->Visible) { // datetime_approved ?>
	<tr id="r_datetime_approved">
		<td class="col-sm-2"><span id="elh_report_form_datetime_approved"><?php echo $report_form->datetime_approved->FldCaption() ?></span></td>
		<td data-name="datetime_approved"<?php echo $report_form->datetime_approved->CellAttributes() ?>>
<span id="el_report_form_datetime_approved" data-page="2">
<span<?php echo $report_form->datetime_approved->ViewAttributes() ?>>
<?php echo $report_form->datetime_approved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->approved_by->Visible) { // approved_by ?>
	<tr id="r_approved_by">
		<td class="col-sm-2"><span id="elh_report_form_approved_by"><?php echo $report_form->approved_by->FldCaption() ?></span></td>
		<td data-name="approved_by"<?php echo $report_form->approved_by->CellAttributes() ?>>
<span id="el_report_form_approved_by" data-page="2">
<span<?php echo $report_form->approved_by->ViewAttributes() ?>>
<?php echo $report_form->approved_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->last_updated_date->Visible) { // last_updated_date ?>
	<tr id="r_last_updated_date">
		<td class="col-sm-2"><span id="elh_report_form_last_updated_date"><?php echo $report_form->last_updated_date->FldCaption() ?></span></td>
		<td data-name="last_updated_date"<?php echo $report_form->last_updated_date->CellAttributes() ?>>
<span id="el_report_form_last_updated_date" data-page="2">
<span<?php echo $report_form->last_updated_date->ViewAttributes() ?>>
<?php echo $report_form->last_updated_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->last_updated_by->Visible) { // last_updated_by ?>
	<tr id="r_last_updated_by">
		<td class="col-sm-2"><span id="elh_report_form_last_updated_by"><?php echo $report_form->last_updated_by->FldCaption() ?></span></td>
		<td data-name="last_updated_by"<?php echo $report_form->last_updated_by->CellAttributes() ?>>
<span id="el_report_form_last_updated_by" data-page="2">
<span<?php echo $report_form->last_updated_by->ViewAttributes() ?>>
<?php echo $report_form->last_updated_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->verified_datetime->Visible) { // verified_datetime ?>
	<tr id="r_verified_datetime">
		<td class="col-sm-2"><span id="elh_report_form_verified_datetime"><?php echo $report_form->verified_datetime->FldCaption() ?></span></td>
		<td data-name="verified_datetime"<?php echo $report_form->verified_datetime->CellAttributes() ?>>
<span id="el_report_form_verified_datetime" data-page="2">
<span<?php echo $report_form->verified_datetime->ViewAttributes() ?>>
<?php echo $report_form->verified_datetime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->job_assessment->Visible) { // job_assessment ?>
	<tr id="r_job_assessment">
		<td class="col-sm-2"><span id="elh_report_form_job_assessment"><?php echo $report_form->job_assessment->FldCaption() ?></span></td>
		<td data-name="job_assessment"<?php echo $report_form->job_assessment->CellAttributes() ?>>
<span id="el_report_form_job_assessment" data-page="2">
<span<?php echo $report_form->job_assessment->ViewAttributes() ?>>
<?php echo $report_form->job_assessment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->verified_action->Visible) { // verified_action ?>
	<tr id="r_verified_action">
		<td class="col-sm-2"><span id="elh_report_form_verified_action"><?php echo $report_form->verified_action->FldCaption() ?></span></td>
		<td data-name="verified_action"<?php echo $report_form->verified_action->CellAttributes() ?>>
<span id="el_report_form_verified_action" data-page="2">
<span<?php echo $report_form->verified_action->ViewAttributes() ?>>
<?php echo $report_form->verified_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->verified_comment->Visible) { // verified_comment ?>
	<tr id="r_verified_comment">
		<td class="col-sm-2"><span id="elh_report_form_verified_comment"><?php echo $report_form->verified_comment->FldCaption() ?></span></td>
		<td data-name="verified_comment"<?php echo $report_form->verified_comment->CellAttributes() ?>>
<span id="el_report_form_verified_comment" data-page="2">
<span<?php echo $report_form->verified_comment->ViewAttributes() ?>>
<?php echo $report_form->verified_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->verified_by->Visible) { // verified_by ?>
	<tr id="r_verified_by">
		<td class="col-sm-2"><span id="elh_report_form_verified_by"><?php echo $report_form->verified_by->FldCaption() ?></span></td>
		<td data-name="verified_by"<?php echo $report_form->verified_by->CellAttributes() ?>>
<span id="el_report_form_verified_by" data-page="2">
<span<?php echo $report_form->verified_by->ViewAttributes() ?>>
<?php echo $report_form->verified_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report_form->remainder->Visible) { // remainder ?>
	<tr id="r_remainder">
		<td class="col-sm-2"><span id="elh_report_form_remainder"><?php echo $report_form->remainder->FldCaption() ?></span></td>
		<td data-name="remainder"<?php echo $report_form->remainder->CellAttributes() ?>>
<span id="el_report_form_remainder" data-page="2">
<span<?php echo $report_form->remainder->ViewAttributes() ?>>
<?php echo $report_form->remainder->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($report_form->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($report_form->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($report_form->Export == "") { ?>
<script type="text/javascript">
freport_formview.Init();
</script>
<?php } ?>
<?php
$report_form_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($report_form->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$report_form_view->Page_Terminate();
?>
