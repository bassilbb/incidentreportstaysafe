<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$report_view = NULL; // Initialize page object first

class creport_view extends creport {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'report';

	// Page object name
	var $PageObjName = 'report_view';

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

		// Table object (report)
		if (!isset($GLOBALS["report"]) || get_class($GLOBALS["report"]) == "creport") {
			$GLOBALS["report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["report"];
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
			define("EW_TABLE_NAME", 'report', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("reportlist.php"));
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
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->report_by->SetVisibility();
		$this->datetime_resolved->SetVisibility();
		$this->assign_task->SetVisibility();
		$this->approval_action->SetVisibility();
		$this->approval_comment->SetVisibility();
		$this->reason->SetVisibility();
		$this->resolved_action->SetVisibility();
		$this->resolved_comment->SetVisibility();
		$this->resolved_by->SetVisibility();
		$this->datetime_approved->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->verified_by->SetVisibility();
		$this->last_updated_date->SetVisibility();
		$this->last_updated_by->SetVisibility();
		$this->selection_sub_category->SetVisibility();
		$this->verified_datetime->SetVisibility();
		$this->verified_action->SetVisibility();
		$this->verified_comment->SetVisibility();
		$this->job_assessment->SetVisibility();

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
		global $EW_EXPORT, $report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($report);
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
					if ($pageName == "reportview.php")
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
						$this->Page_Terminate("reportlist.php"); // Return to list page
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
						$sReturnUrl = "reportlist.php"; // No matching record, return to list
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
			$sReturnUrl = "reportlist.php"; // Not page request, return to list
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

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

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
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->report_by->setDbValue($row['report_by']);
		$this->datetime_resolved->setDbValue($row['datetime_resolved']);
		$this->assign_task->setDbValue($row['assign_task']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->reason->setDbValue($row['reason']);
		$this->resolved_action->setDbValue($row['resolved_action']);
		$this->resolved_comment->setDbValue($row['resolved_comment']);
		$this->resolved_by->setDbValue($row['resolved_by']);
		$this->datetime_approved->setDbValue($row['datetime_approved']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->verified_by->setDbValue($row['verified_by']);
		$this->last_updated_date->setDbValue($row['last_updated_date']);
		$this->last_updated_by->setDbValue($row['last_updated_by']);
		$this->selection_sub_category->setDbValue($row['selection_sub_category']);
		$this->verified_datetime->setDbValue($row['verified_datetime']);
		$this->verified_action->setDbValue($row['verified_action']);
		$this->verified_comment->setDbValue($row['verified_comment']);
		$this->job_assessment->setDbValue($row['job_assessment']);
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
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['report_by'] = NULL;
		$row['datetime_resolved'] = NULL;
		$row['assign_task'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['reason'] = NULL;
		$row['resolved_action'] = NULL;
		$row['resolved_comment'] = NULL;
		$row['resolved_by'] = NULL;
		$row['datetime_approved'] = NULL;
		$row['approved_by'] = NULL;
		$row['verified_by'] = NULL;
		$row['last_updated_date'] = NULL;
		$row['last_updated_by'] = NULL;
		$row['selection_sub_category'] = NULL;
		$row['verified_datetime'] = NULL;
		$row['verified_action'] = NULL;
		$row['verified_comment'] = NULL;
		$row['job_assessment'] = NULL;
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
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->report_by->DbValue = $row['report_by'];
		$this->datetime_resolved->DbValue = $row['datetime_resolved'];
		$this->assign_task->DbValue = $row['assign_task'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->reason->DbValue = $row['reason'];
		$this->resolved_action->DbValue = $row['resolved_action'];
		$this->resolved_comment->DbValue = $row['resolved_comment'];
		$this->resolved_by->DbValue = $row['resolved_by'];
		$this->datetime_approved->DbValue = $row['datetime_approved'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->verified_by->DbValue = $row['verified_by'];
		$this->last_updated_date->DbValue = $row['last_updated_date'];
		$this->last_updated_by->DbValue = $row['last_updated_by'];
		$this->selection_sub_category->DbValue = $row['selection_sub_category'];
		$this->verified_datetime->DbValue = $row['verified_datetime'];
		$this->verified_action->DbValue = $row['verified_action'];
		$this->verified_comment->DbValue = $row['verified_comment'];
		$this->job_assessment->DbValue = $row['job_assessment'];
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
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// assign_task
		// approval_action
		// approval_comment
		// reason
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approved_by
		// verified_by
		// last_updated_date
		// last_updated_by
		// selection_sub_category
		// verified_datetime
		// verified_action
		// verified_comment
		// job_assessment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 7);
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
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
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
		$this->department->LookupFilters = array("dx1" => '`department_name`');
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
		$this->branch->LookupFilters = array("dx1" => '`branch_name`');
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
		$this->departments->LookupFilters = array("dx1" => '`description`');
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

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 2);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 2);
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
		$this->incident_location->LookupFilters = array("dx1" => '`description`');
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
		$this->status->LookupFilters = array("dx1" => '`description`');
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

		// assign_task
		if (strval($this->assign_task->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_task->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_task->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->assign_task->ViewValue = $this->assign_task->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_task->ViewValue = $this->assign_task->CurrentValue;
			}
		} else {
			$this->assign_task->ViewValue = NULL;
		}
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

		// verified_by
		$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
		$this->verified_by->ViewCustomAttributes = "";

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 0);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
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

		// selection_sub_category
		$this->selection_sub_category->ViewValue = $this->selection_sub_category->CurrentValue;
		$this->selection_sub_category->ViewCustomAttributes = "";

		// verified_datetime
		$this->verified_datetime->ViewValue = $this->verified_datetime->CurrentValue;
		$this->verified_datetime->ViewValue = ew_FormatDateTime($this->verified_datetime->ViewValue, 0);
		$this->verified_datetime->ViewCustomAttributes = "";

		// verified_action
		$this->verified_action->ViewValue = $this->verified_action->CurrentValue;
		$this->verified_action->ViewCustomAttributes = "";

		// verified_comment
		$this->verified_comment->ViewValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->ViewCustomAttributes = "";

		// job_assessment
		if (strval($this->job_assessment->CurrentValue) <> "") {
			$this->job_assessment->ViewValue = $this->job_assessment->OptionCaption($this->job_assessment->CurrentValue);
		} else {
			$this->job_assessment->ViewValue = NULL;
		}
		$this->job_assessment->ViewCustomAttributes = "";

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
				$this->_upload->LinkAttrs["data-rel"] = "report_x__upload";
				ew_AppendClass($this->_upload->LinkAttrs["class"], "ewLightbox");
			}

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

			// report_by
			$this->report_by->LinkCustomAttributes = "";
			$this->report_by->HrefValue = "";
			$this->report_by->TooltipValue = "";

			// datetime_resolved
			$this->datetime_resolved->LinkCustomAttributes = "";
			$this->datetime_resolved->HrefValue = "";
			$this->datetime_resolved->TooltipValue = "";

			// assign_task
			$this->assign_task->LinkCustomAttributes = "";
			$this->assign_task->HrefValue = "";
			$this->assign_task->TooltipValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";
			$this->approval_action->TooltipValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";
			$this->approval_comment->TooltipValue = "";

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

			// verified_by
			$this->verified_by->LinkCustomAttributes = "";
			$this->verified_by->HrefValue = "";
			$this->verified_by->TooltipValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";

			// selection_sub_category
			$this->selection_sub_category->LinkCustomAttributes = "";
			$this->selection_sub_category->HrefValue = "";
			$this->selection_sub_category->TooltipValue = "";

			// verified_datetime
			$this->verified_datetime->LinkCustomAttributes = "";
			$this->verified_datetime->HrefValue = "";
			$this->verified_datetime->TooltipValue = "";

			// verified_action
			$this->verified_action->LinkCustomAttributes = "";
			$this->verified_action->HrefValue = "";
			$this->verified_action->TooltipValue = "";

			// verified_comment
			$this->verified_comment->LinkCustomAttributes = "";
			$this->verified_comment->HrefValue = "";
			$this->verified_comment->TooltipValue = "";

			// job_assessment
			$this->job_assessment->LinkCustomAttributes = "";
			$this->job_assessment->HrefValue = "";
			$this->job_assessment->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.freportview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("reportlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($report_view)) $report_view = new creport_view();

// Page init
$report_view->Page_Init();

// Page main
$report_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$report_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = freportview = new ew_Form("freportview", "view");

// Form_CustomValidate event
freportview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freportview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
freportview.MultiPage = new ew_MultiPage("freportview");

// Dynamic selection lists
freportview.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_staffid"].Data = "<?php echo $report_view->staffid->LookupFilterQuery(FALSE, "view") ?>";
freportview.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_view->staffid->LookupFilterQuery(TRUE, "view"))) ?>;
freportview.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_staff_id"].Data = "<?php echo $report_view->staff_id->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
freportview.Lists["x_department"].Data = "<?php echo $report_view->department->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
freportview.Lists["x_branch"].Data = "<?php echo $report_view->branch->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freportview.Lists["x_departments"].Data = "<?php echo $report_view->departments->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"category"};
freportview.Lists["x_category"].Data = "<?php echo $report_view->category->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":[],"ChildFields":["x_sub_sub_category[]"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sub_category"};
freportview.Lists["x_sub_category"].Data = "<?php echo $report_view->sub_category->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_sub_sub_category[]"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sub_sub_category"};
freportview.Lists["x_sub_sub_category[]"].Data = "<?php echo $report_view->sub_sub_category->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_incident_type"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"type_of_incident"};
freportview.Lists["x_incident_type"].Data = "<?php echo $report_view->incident_type->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_incident_category"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
freportview.Lists["x_incident_category"].Data = "<?php echo $report_view->incident_category->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_incident_location"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_sub_location"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freportview.Lists["x_incident_location"].Data = "<?php echo $report_view->incident_location->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_incident_sub_location"] = {"LinkField":"x_code_sub","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_incident_venue"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_sub_location"};
freportview.Lists["x_incident_sub_location"].Data = "<?php echo $report_view->incident_sub_location->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_incident_venue"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_venue"};
freportview.Lists["x_incident_venue"].Data = "<?php echo $report_view->incident_venue->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freportview.Lists["x_status"].Data = "<?php echo $report_view->status->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportview.Lists["x_initiator_action"].Options = <?php echo json_encode($report_view->initiator_action->Options()) ?>;
freportview.Lists["x_report_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_report_by"].Data = "<?php echo $report_view->report_by->LookupFilterQuery(FALSE, "view") ?>";
freportview.AutoSuggests["x_report_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_view->report_by->LookupFilterQuery(TRUE, "view"))) ?>;
freportview.Lists["x_assign_task"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_assign_task"].Data = "<?php echo $report_view->assign_task->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportview.Lists["x_approval_action"].Options = <?php echo json_encode($report_view->approval_action->Options()) ?>;
freportview.Lists["x_reason"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"reason"};
freportview.Lists["x_reason"].Data = "<?php echo $report_view->reason->LookupFilterQuery(FALSE, "view") ?>";
freportview.Lists["x_resolved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportview.Lists["x_resolved_action"].Options = <?php echo json_encode($report_view->resolved_action->Options()) ?>;
freportview.Lists["x_resolved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_resolved_by"].Data = "<?php echo $report_view->resolved_by->LookupFilterQuery(FALSE, "view") ?>";
freportview.AutoSuggests["x_resolved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_view->resolved_by->LookupFilterQuery(TRUE, "view"))) ?>;
freportview.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_approved_by"].Data = "<?php echo $report_view->approved_by->LookupFilterQuery(FALSE, "view") ?>";
freportview.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_view->approved_by->LookupFilterQuery(TRUE, "view"))) ?>;
freportview.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freportview.Lists["x_last_updated_by"].Data = "<?php echo $report_view->last_updated_by->LookupFilterQuery(FALSE, "view") ?>";
freportview.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_view->last_updated_by->LookupFilterQuery(TRUE, "view"))) ?>;
freportview.Lists["x_job_assessment"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freportview.Lists["x_job_assessment"].Options = <?php echo json_encode($report_view->job_assessment->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($report->Export == "") { ?>
<div class="ewToolbar">
<?php $report_view->ExportOptions->Render("body") ?>
<?php
	foreach ($report_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $report_view->ShowPageHeader(); ?>
<?php
$report_view->ShowMessage();
?>
<?php if (!$report_view->IsModal) { ?>
<?php if ($report->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($report_view->Pager)) $report_view->Pager = new cPrevNextPager($report_view->StartRec, $report_view->DisplayRecs, $report_view->TotalRecs, $report_view->AutoHidePager) ?>
<?php if ($report_view->Pager->RecordCount > 0 && $report_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($report_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $report_view->PageUrl() ?>start=<?php echo $report_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($report_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $report_view->PageUrl() ?>start=<?php echo $report_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $report_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($report_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $report_view->PageUrl() ?>start=<?php echo $report_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($report_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $report_view->PageUrl() ?>start=<?php echo $report_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $report_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="freportview" id="freportview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($report_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $report_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="report">
<input type="hidden" name="modal" value="<?php echo intval($report_view->IsModal) ?>">
<?php if ($report->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="report_view">
	<ul class="nav<?php echo $report_view->MultiPages->NavStyle() ?>">
		<li<?php echo $report_view->MultiPages->TabStyle("1") ?>><a href="#tab_report1" data-toggle="tab"><?php echo $report->PageCaption(1) ?></a></li>
		<li<?php echo $report_view->MultiPages->TabStyle("2") ?>><a href="#tab_report2" data-toggle="tab"><?php echo $report->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($report->Export == "") { ?>
		<div class="tab-pane<?php echo $report_view->MultiPages->PageStyle("1") ?>" id="tab_report1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($report->datetime_initiated->Visible) { // datetime_initiated ?>
	<tr id="r_datetime_initiated">
		<td class="col-sm-2"><span id="elh_report_datetime_initiated"><?php echo $report->datetime_initiated->FldCaption() ?></span></td>
		<td data-name="datetime_initiated"<?php echo $report->datetime_initiated->CellAttributes() ?>>
<span id="el_report_datetime_initiated" data-page="1">
<span<?php echo $report->datetime_initiated->ViewAttributes() ?>>
<?php echo $report->datetime_initiated->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_id->Visible) { // incident_id ?>
	<tr id="r_incident_id">
		<td class="col-sm-2"><span id="elh_report_incident_id"><?php echo $report->incident_id->FldCaption() ?></span></td>
		<td data-name="incident_id"<?php echo $report->incident_id->CellAttributes() ?>>
<span id="el_report_incident_id" data-page="1">
<span<?php echo $report->incident_id->ViewAttributes() ?>>
<?php echo $report->incident_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->staffid->Visible) { // staffid ?>
	<tr id="r_staffid">
		<td class="col-sm-2"><span id="elh_report_staffid"><?php echo $report->staffid->FldCaption() ?></span></td>
		<td data-name="staffid"<?php echo $report->staffid->CellAttributes() ?>>
<span id="el_report_staffid" data-page="1">
<span<?php echo $report->staffid->ViewAttributes() ?>>
<?php echo $report->staffid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->staff_id->Visible) { // staff_id ?>
	<tr id="r_staff_id">
		<td class="col-sm-2"><span id="elh_report_staff_id"><?php echo $report->staff_id->FldCaption() ?></span></td>
		<td data-name="staff_id"<?php echo $report->staff_id->CellAttributes() ?>>
<span id="el_report_staff_id" data-page="1">
<span<?php echo $report->staff_id->ViewAttributes() ?>>
<?php echo $report->staff_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->department->Visible) { // department ?>
	<tr id="r_department">
		<td class="col-sm-2"><span id="elh_report_department"><?php echo $report->department->FldCaption() ?></span></td>
		<td data-name="department"<?php echo $report->department->CellAttributes() ?>>
<span id="el_report_department" data-page="1">
<span<?php echo $report->department->ViewAttributes() ?>>
<?php echo $report->department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->branch->Visible) { // branch ?>
	<tr id="r_branch">
		<td class="col-sm-2"><span id="elh_report_branch"><?php echo $report->branch->FldCaption() ?></span></td>
		<td data-name="branch"<?php echo $report->branch->CellAttributes() ?>>
<span id="el_report_branch" data-page="1">
<span<?php echo $report->branch->ViewAttributes() ?>>
<?php echo $report->branch->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->departments->Visible) { // departments ?>
	<tr id="r_departments">
		<td class="col-sm-2"><span id="elh_report_departments"><?php echo $report->departments->FldCaption() ?></span></td>
		<td data-name="departments"<?php echo $report->departments->CellAttributes() ?>>
<span id="el_report_departments" data-page="1">
<span<?php echo $report->departments->ViewAttributes() ?>>
<?php echo $report->departments->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->category->Visible) { // category ?>
	<tr id="r_category">
		<td class="col-sm-2"><span id="elh_report_category"><?php echo $report->category->FldCaption() ?></span></td>
		<td data-name="category"<?php echo $report->category->CellAttributes() ?>>
<span id="el_report_category" data-page="1">
<span<?php echo $report->category->ViewAttributes() ?>>
<?php echo $report->category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->sub_category->Visible) { // sub_category ?>
	<tr id="r_sub_category">
		<td class="col-sm-2"><span id="elh_report_sub_category"><?php echo $report->sub_category->FldCaption() ?></span></td>
		<td data-name="sub_category"<?php echo $report->sub_category->CellAttributes() ?>>
<span id="el_report_sub_category" data-page="1">
<span<?php echo $report->sub_category->ViewAttributes() ?>>
<?php echo $report->sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->sub_sub_category->Visible) { // sub_sub_category ?>
	<tr id="r_sub_sub_category">
		<td class="col-sm-2"><span id="elh_report_sub_sub_category"><?php echo $report->sub_sub_category->FldCaption() ?></span></td>
		<td data-name="sub_sub_category"<?php echo $report->sub_sub_category->CellAttributes() ?>>
<span id="el_report_sub_sub_category" data-page="1">
<span<?php echo $report->sub_sub_category->ViewAttributes() ?>>
<?php echo $report->sub_sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->start_date->Visible) { // start_date ?>
	<tr id="r_start_date">
		<td class="col-sm-2"><span id="elh_report_start_date"><?php echo $report->start_date->FldCaption() ?></span></td>
		<td data-name="start_date"<?php echo $report->start_date->CellAttributes() ?>>
<span id="el_report_start_date" data-page="1">
<span<?php echo $report->start_date->ViewAttributes() ?>>
<?php echo $report->start_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->end_date->Visible) { // end_date ?>
	<tr id="r_end_date">
		<td class="col-sm-2"><span id="elh_report_end_date"><?php echo $report->end_date->FldCaption() ?></span></td>
		<td data-name="end_date"<?php echo $report->end_date->CellAttributes() ?>>
<span id="el_report_end_date" data-page="1">
<span<?php echo $report->end_date->ViewAttributes() ?>>
<?php echo $report->end_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->duration->Visible) { // duration ?>
	<tr id="r_duration">
		<td class="col-sm-2"><span id="elh_report_duration"><?php echo $report->duration->FldCaption() ?></span></td>
		<td data-name="duration"<?php echo $report->duration->CellAttributes() ?>>
<span id="el_report_duration" data-page="1">
<span<?php echo $report->duration->ViewAttributes() ?>>
<?php echo $report->duration->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->amount_paid->Visible) { // amount_paid ?>
	<tr id="r_amount_paid">
		<td class="col-sm-2"><span id="elh_report_amount_paid"><?php echo $report->amount_paid->FldCaption() ?></span></td>
		<td data-name="amount_paid"<?php echo $report->amount_paid->CellAttributes() ?>>
<span id="el_report_amount_paid" data-page="1">
<span<?php echo $report->amount_paid->ViewAttributes() ?>>
<?php echo $report->amount_paid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->no_of_people_involved->Visible) { // no_of_people_involved ?>
	<tr id="r_no_of_people_involved">
		<td class="col-sm-2"><span id="elh_report_no_of_people_involved"><?php echo $report->no_of_people_involved->FldCaption() ?></span></td>
		<td data-name="no_of_people_involved"<?php echo $report->no_of_people_involved->CellAttributes() ?>>
<span id="el_report_no_of_people_involved" data-page="1">
<span<?php echo $report->no_of_people_involved->ViewAttributes() ?>>
<?php echo $report->no_of_people_involved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_type->Visible) { // incident_type ?>
	<tr id="r_incident_type">
		<td class="col-sm-2"><span id="elh_report_incident_type"><?php echo $report->incident_type->FldCaption() ?></span></td>
		<td data-name="incident_type"<?php echo $report->incident_type->CellAttributes() ?>>
<span id="el_report_incident_type" data-page="1">
<span<?php echo $report->incident_type->ViewAttributes() ?>>
<?php echo $report->incident_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_category->Visible) { // incident-category ?>
	<tr id="r_incident_category">
		<td class="col-sm-2"><span id="elh_report_incident_category"><?php echo $report->incident_category->FldCaption() ?></span></td>
		<td data-name="incident_category"<?php echo $report->incident_category->CellAttributes() ?>>
<span id="el_report_incident_category" data-page="1">
<span<?php echo $report->incident_category->ViewAttributes() ?>>
<?php echo $report->incident_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_location->Visible) { // incident_location ?>
	<tr id="r_incident_location">
		<td class="col-sm-2"><span id="elh_report_incident_location"><?php echo $report->incident_location->FldCaption() ?></span></td>
		<td data-name="incident_location"<?php echo $report->incident_location->CellAttributes() ?>>
<span id="el_report_incident_location" data-page="1">
<span<?php echo $report->incident_location->ViewAttributes() ?>>
<?php echo $report->incident_location->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_sub_location->Visible) { // incident_sub_location ?>
	<tr id="r_incident_sub_location">
		<td class="col-sm-2"><span id="elh_report_incident_sub_location"><?php echo $report->incident_sub_location->FldCaption() ?></span></td>
		<td data-name="incident_sub_location"<?php echo $report->incident_sub_location->CellAttributes() ?>>
<span id="el_report_incident_sub_location" data-page="1">
<span<?php echo $report->incident_sub_location->ViewAttributes() ?>>
<?php echo $report->incident_sub_location->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_venue->Visible) { // incident_venue ?>
	<tr id="r_incident_venue">
		<td class="col-sm-2"><span id="elh_report_incident_venue"><?php echo $report->incident_venue->FldCaption() ?></span></td>
		<td data-name="incident_venue"<?php echo $report->incident_venue->CellAttributes() ?>>
<span id="el_report_incident_venue" data-page="1">
<span<?php echo $report->incident_venue->ViewAttributes() ?>>
<?php echo $report->incident_venue->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->incident_description->Visible) { // incident_description ?>
	<tr id="r_incident_description">
		<td class="col-sm-2"><span id="elh_report_incident_description"><?php echo $report->incident_description->FldCaption() ?></span></td>
		<td data-name="incident_description"<?php echo $report->incident_description->CellAttributes() ?>>
<span id="el_report_incident_description" data-page="1">
<span<?php echo $report->incident_description->ViewAttributes() ?>>
<?php echo $report->incident_description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->_upload->Visible) { // upload ?>
	<tr id="r__upload">
		<td class="col-sm-2"><span id="elh_report__upload"><?php echo $report->_upload->FldCaption() ?></span></td>
		<td data-name="_upload"<?php echo $report->_upload->CellAttributes() ?>>
<span id="el_report__upload" data-page="1">
<span>
<?php echo ew_GetFileViewTag($report->_upload, $report->_upload->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($report->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($report->Export == "") { ?>
		<div class="tab-pane<?php echo $report_view->MultiPages->PageStyle("2") ?>" id="tab_report2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($report->status->Visible) { // status ?>
	<tr id="r_status">
		<td class="col-sm-2"><span id="elh_report_status"><?php echo $report->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $report->status->CellAttributes() ?>>
<span id="el_report_status" data-page="2">
<span<?php echo $report->status->ViewAttributes() ?>>
<?php echo $report->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->initiator_action->Visible) { // initiator_action ?>
	<tr id="r_initiator_action">
		<td class="col-sm-2"><span id="elh_report_initiator_action"><?php echo $report->initiator_action->FldCaption() ?></span></td>
		<td data-name="initiator_action"<?php echo $report->initiator_action->CellAttributes() ?>>
<span id="el_report_initiator_action" data-page="2">
<span<?php echo $report->initiator_action->ViewAttributes() ?>>
<?php echo $report->initiator_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->initiator_comment->Visible) { // initiator_comment ?>
	<tr id="r_initiator_comment">
		<td class="col-sm-2"><span id="elh_report_initiator_comment"><?php echo $report->initiator_comment->FldCaption() ?></span></td>
		<td data-name="initiator_comment"<?php echo $report->initiator_comment->CellAttributes() ?>>
<span id="el_report_initiator_comment" data-page="2">
<span<?php echo $report->initiator_comment->ViewAttributes() ?>>
<?php echo $report->initiator_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->report_by->Visible) { // report_by ?>
	<tr id="r_report_by">
		<td class="col-sm-2"><span id="elh_report_report_by"><?php echo $report->report_by->FldCaption() ?></span></td>
		<td data-name="report_by"<?php echo $report->report_by->CellAttributes() ?>>
<span id="el_report_report_by" data-page="2">
<span<?php echo $report->report_by->ViewAttributes() ?>>
<?php echo $report->report_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->datetime_resolved->Visible) { // datetime_resolved ?>
	<tr id="r_datetime_resolved">
		<td class="col-sm-2"><span id="elh_report_datetime_resolved"><?php echo $report->datetime_resolved->FldCaption() ?></span></td>
		<td data-name="datetime_resolved"<?php echo $report->datetime_resolved->CellAttributes() ?>>
<span id="el_report_datetime_resolved" data-page="2">
<span<?php echo $report->datetime_resolved->ViewAttributes() ?>>
<?php echo $report->datetime_resolved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->assign_task->Visible) { // assign_task ?>
	<tr id="r_assign_task">
		<td class="col-sm-2"><span id="elh_report_assign_task"><?php echo $report->assign_task->FldCaption() ?></span></td>
		<td data-name="assign_task"<?php echo $report->assign_task->CellAttributes() ?>>
<span id="el_report_assign_task" data-page="2">
<span<?php echo $report->assign_task->ViewAttributes() ?>>
<?php echo $report->assign_task->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->approval_action->Visible) { // approval_action ?>
	<tr id="r_approval_action">
		<td class="col-sm-2"><span id="elh_report_approval_action"><?php echo $report->approval_action->FldCaption() ?></span></td>
		<td data-name="approval_action"<?php echo $report->approval_action->CellAttributes() ?>>
<span id="el_report_approval_action" data-page="2">
<span<?php echo $report->approval_action->ViewAttributes() ?>>
<?php echo $report->approval_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->approval_comment->Visible) { // approval_comment ?>
	<tr id="r_approval_comment">
		<td class="col-sm-2"><span id="elh_report_approval_comment"><?php echo $report->approval_comment->FldCaption() ?></span></td>
		<td data-name="approval_comment"<?php echo $report->approval_comment->CellAttributes() ?>>
<span id="el_report_approval_comment" data-page="2">
<span<?php echo $report->approval_comment->ViewAttributes() ?>>
<?php echo $report->approval_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->reason->Visible) { // reason ?>
	<tr id="r_reason">
		<td class="col-sm-2"><span id="elh_report_reason"><?php echo $report->reason->FldCaption() ?></span></td>
		<td data-name="reason"<?php echo $report->reason->CellAttributes() ?>>
<span id="el_report_reason" data-page="2">
<span<?php echo $report->reason->ViewAttributes() ?>>
<?php echo $report->reason->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->resolved_action->Visible) { // resolved_action ?>
	<tr id="r_resolved_action">
		<td class="col-sm-2"><span id="elh_report_resolved_action"><?php echo $report->resolved_action->FldCaption() ?></span></td>
		<td data-name="resolved_action"<?php echo $report->resolved_action->CellAttributes() ?>>
<span id="el_report_resolved_action" data-page="2">
<span<?php echo $report->resolved_action->ViewAttributes() ?>>
<?php echo $report->resolved_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->resolved_comment->Visible) { // resolved_comment ?>
	<tr id="r_resolved_comment">
		<td class="col-sm-2"><span id="elh_report_resolved_comment"><?php echo $report->resolved_comment->FldCaption() ?></span></td>
		<td data-name="resolved_comment"<?php echo $report->resolved_comment->CellAttributes() ?>>
<span id="el_report_resolved_comment" data-page="2">
<span<?php echo $report->resolved_comment->ViewAttributes() ?>>
<?php echo $report->resolved_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->resolved_by->Visible) { // resolved_by ?>
	<tr id="r_resolved_by">
		<td class="col-sm-2"><span id="elh_report_resolved_by"><?php echo $report->resolved_by->FldCaption() ?></span></td>
		<td data-name="resolved_by"<?php echo $report->resolved_by->CellAttributes() ?>>
<span id="el_report_resolved_by" data-page="2">
<span<?php echo $report->resolved_by->ViewAttributes() ?>>
<?php echo $report->resolved_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->datetime_approved->Visible) { // datetime_approved ?>
	<tr id="r_datetime_approved">
		<td class="col-sm-2"><span id="elh_report_datetime_approved"><?php echo $report->datetime_approved->FldCaption() ?></span></td>
		<td data-name="datetime_approved"<?php echo $report->datetime_approved->CellAttributes() ?>>
<span id="el_report_datetime_approved" data-page="2">
<span<?php echo $report->datetime_approved->ViewAttributes() ?>>
<?php echo $report->datetime_approved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->approved_by->Visible) { // approved_by ?>
	<tr id="r_approved_by">
		<td class="col-sm-2"><span id="elh_report_approved_by"><?php echo $report->approved_by->FldCaption() ?></span></td>
		<td data-name="approved_by"<?php echo $report->approved_by->CellAttributes() ?>>
<span id="el_report_approved_by" data-page="2">
<span<?php echo $report->approved_by->ViewAttributes() ?>>
<?php echo $report->approved_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->verified_by->Visible) { // verified_by ?>
	<tr id="r_verified_by">
		<td class="col-sm-2"><span id="elh_report_verified_by"><?php echo $report->verified_by->FldCaption() ?></span></td>
		<td data-name="verified_by"<?php echo $report->verified_by->CellAttributes() ?>>
<span id="el_report_verified_by" data-page="2">
<span<?php echo $report->verified_by->ViewAttributes() ?>>
<?php echo $report->verified_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->last_updated_date->Visible) { // last_updated_date ?>
	<tr id="r_last_updated_date">
		<td class="col-sm-2"><span id="elh_report_last_updated_date"><?php echo $report->last_updated_date->FldCaption() ?></span></td>
		<td data-name="last_updated_date"<?php echo $report->last_updated_date->CellAttributes() ?>>
<span id="el_report_last_updated_date" data-page="2">
<span<?php echo $report->last_updated_date->ViewAttributes() ?>>
<?php echo $report->last_updated_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->last_updated_by->Visible) { // last_updated_by ?>
	<tr id="r_last_updated_by">
		<td class="col-sm-2"><span id="elh_report_last_updated_by"><?php echo $report->last_updated_by->FldCaption() ?></span></td>
		<td data-name="last_updated_by"<?php echo $report->last_updated_by->CellAttributes() ?>>
<span id="el_report_last_updated_by" data-page="2">
<span<?php echo $report->last_updated_by->ViewAttributes() ?>>
<?php echo $report->last_updated_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->selection_sub_category->Visible) { // selection_sub_category ?>
	<tr id="r_selection_sub_category">
		<td class="col-sm-2"><span id="elh_report_selection_sub_category"><?php echo $report->selection_sub_category->FldCaption() ?></span></td>
		<td data-name="selection_sub_category"<?php echo $report->selection_sub_category->CellAttributes() ?>>
<span id="el_report_selection_sub_category" data-page="2">
<span<?php echo $report->selection_sub_category->ViewAttributes() ?>>
<?php echo $report->selection_sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->verified_datetime->Visible) { // verified_datetime ?>
	<tr id="r_verified_datetime">
		<td class="col-sm-2"><span id="elh_report_verified_datetime"><?php echo $report->verified_datetime->FldCaption() ?></span></td>
		<td data-name="verified_datetime"<?php echo $report->verified_datetime->CellAttributes() ?>>
<span id="el_report_verified_datetime" data-page="2">
<span<?php echo $report->verified_datetime->ViewAttributes() ?>>
<?php echo $report->verified_datetime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->verified_action->Visible) { // verified_action ?>
	<tr id="r_verified_action">
		<td class="col-sm-2"><span id="elh_report_verified_action"><?php echo $report->verified_action->FldCaption() ?></span></td>
		<td data-name="verified_action"<?php echo $report->verified_action->CellAttributes() ?>>
<span id="el_report_verified_action" data-page="2">
<span<?php echo $report->verified_action->ViewAttributes() ?>>
<?php echo $report->verified_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->verified_comment->Visible) { // verified_comment ?>
	<tr id="r_verified_comment">
		<td class="col-sm-2"><span id="elh_report_verified_comment"><?php echo $report->verified_comment->FldCaption() ?></span></td>
		<td data-name="verified_comment"<?php echo $report->verified_comment->CellAttributes() ?>>
<span id="el_report_verified_comment" data-page="2">
<span<?php echo $report->verified_comment->ViewAttributes() ?>>
<?php echo $report->verified_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($report->job_assessment->Visible) { // job_assessment ?>
	<tr id="r_job_assessment">
		<td class="col-sm-2"><span id="elh_report_job_assessment"><?php echo $report->job_assessment->FldCaption() ?></span></td>
		<td data-name="job_assessment"<?php echo $report->job_assessment->CellAttributes() ?>>
<span id="el_report_job_assessment" data-page="2">
<span<?php echo $report->job_assessment->ViewAttributes() ?>>
<?php echo $report->job_assessment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($report->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($report->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($report->Export == "") { ?>
<script type="text/javascript">
freportview.Init();
</script>
<?php } ?>
<?php
$report_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$report_view->Page_Terminate();
?>
