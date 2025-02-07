<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "systemsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$systems_view = NULL; // Initialize page object first

class csystems_view extends csystems {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'systems';

	// Page object name
	var $PageObjName = 'systems_view';

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

		// Table object (systems)
		if (!isset($GLOBALS["systems"]) || get_class($GLOBALS["systems"]) == "csystems") {
			$GLOBALS["systems"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["systems"];
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
			define("EW_TABLE_NAME", 'systems', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("systemslist.php"));
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
		global $EW_EXPORT, $systems;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($systems);
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
					if ($pageName == "systemsview.php")
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
						$this->Page_Terminate("systemslist.php"); // Return to list page
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
						$sReturnUrl = "systemslist.php"; // No matching record, return to list
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
			$sReturnUrl = "systemslist.php"; // Not page request, return to list
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
		$item->Body = "<button id=\"emf_systems\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_systems',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fsystemsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("systemslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($systems_view)) $systems_view = new csystems_view();

// Page init
$systems_view->Page_Init();

// Page main
$systems_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$systems_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($systems->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fsystemsview = new ew_Form("fsystemsview", "view");

// Form_CustomValidate event
fsystemsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsystemsview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($systems->Export == "") { ?>
<div class="ewToolbar">
<?php $systems_view->ExportOptions->Render("body") ?>
<?php
	foreach ($systems_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $systems_view->ShowPageHeader(); ?>
<?php
$systems_view->ShowMessage();
?>
<?php if (!$systems_view->IsModal) { ?>
<?php if ($systems->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($systems_view->Pager)) $systems_view->Pager = new cPrevNextPager($systems_view->StartRec, $systems_view->DisplayRecs, $systems_view->TotalRecs, $systems_view->AutoHidePager) ?>
<?php if ($systems_view->Pager->RecordCount > 0 && $systems_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($systems_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $systems_view->PageUrl() ?>start=<?php echo $systems_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($systems_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $systems_view->PageUrl() ?>start=<?php echo $systems_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $systems_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($systems_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $systems_view->PageUrl() ?>start=<?php echo $systems_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($systems_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $systems_view->PageUrl() ?>start=<?php echo $systems_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $systems_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fsystemsview" id="fsystemsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($systems_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $systems_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="systems">
<input type="hidden" name="modal" value="<?php echo intval($systems_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($systems->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="col-sm-2"><span id="elh_systems_id"><?php echo $systems->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $systems->id->CellAttributes() ?>>
<span id="el_systems_id">
<span<?php echo $systems->id->ViewAttributes() ?>>
<?php echo $systems->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->asset_tag->Visible) { // asset_tag ?>
	<tr id="r_asset_tag">
		<td class="col-sm-2"><span id="elh_systems_asset_tag"><?php echo $systems->asset_tag->FldCaption() ?></span></td>
		<td data-name="asset_tag"<?php echo $systems->asset_tag->CellAttributes() ?>>
<span id="el_systems_asset_tag">
<span<?php echo $systems->asset_tag->ViewAttributes() ?>>
<?php echo $systems->asset_tag->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->start_sate->Visible) { // start_sate ?>
	<tr id="r_start_sate">
		<td class="col-sm-2"><span id="elh_systems_start_sate"><?php echo $systems->start_sate->FldCaption() ?></span></td>
		<td data-name="start_sate"<?php echo $systems->start_sate->CellAttributes() ?>>
<span id="el_systems_start_sate">
<span<?php echo $systems->start_sate->ViewAttributes() ?>>
<?php echo $systems->start_sate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->end_date->Visible) { // end_date ?>
	<tr id="r_end_date">
		<td class="col-sm-2"><span id="elh_systems_end_date"><?php echo $systems->end_date->FldCaption() ?></span></td>
		<td data-name="end_date"<?php echo $systems->end_date->CellAttributes() ?>>
<span id="el_systems_end_date">
<span<?php echo $systems->end_date->ViewAttributes() ?>>
<?php echo $systems->end_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->cost_for_repair->Visible) { // cost_for_repair ?>
	<tr id="r_cost_for_repair">
		<td class="col-sm-2"><span id="elh_systems_cost_for_repair"><?php echo $systems->cost_for_repair->FldCaption() ?></span></td>
		<td data-name="cost_for_repair"<?php echo $systems->cost_for_repair->CellAttributes() ?>>
<span id="el_systems_cost_for_repair">
<span<?php echo $systems->cost_for_repair->ViewAttributes() ?>>
<?php echo $systems->cost_for_repair->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->service_provider->Visible) { // service_provider ?>
	<tr id="r_service_provider">
		<td class="col-sm-2"><span id="elh_systems_service_provider"><?php echo $systems->service_provider->FldCaption() ?></span></td>
		<td data-name="service_provider"<?php echo $systems->service_provider->CellAttributes() ?>>
<span id="el_systems_service_provider">
<span<?php echo $systems->service_provider->ViewAttributes() ?>>
<?php echo $systems->service_provider->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->address->Visible) { // address ?>
	<tr id="r_address">
		<td class="col-sm-2"><span id="elh_systems_address"><?php echo $systems->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $systems->address->CellAttributes() ?>>
<span id="el_systems_address">
<span<?php echo $systems->address->ViewAttributes() ?>>
<?php echo $systems->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->type_of_repair->Visible) { // type_of_repair ?>
	<tr id="r_type_of_repair">
		<td class="col-sm-2"><span id="elh_systems_type_of_repair"><?php echo $systems->type_of_repair->FldCaption() ?></span></td>
		<td data-name="type_of_repair"<?php echo $systems->type_of_repair->CellAttributes() ?>>
<span id="el_systems_type_of_repair">
<span<?php echo $systems->type_of_repair->ViewAttributes() ?>>
<?php echo $systems->type_of_repair->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->note->Visible) { // note ?>
	<tr id="r_note">
		<td class="col-sm-2"><span id="elh_systems_note"><?php echo $systems->note->FldCaption() ?></span></td>
		<td data-name="note"<?php echo $systems->note->CellAttributes() ?>>
<span id="el_systems_note">
<span<?php echo $systems->note->ViewAttributes() ?>>
<?php echo $systems->note->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->status->Visible) { // status ?>
	<tr id="r_status">
		<td class="col-sm-2"><span id="elh_systems_status"><?php echo $systems->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $systems->status->CellAttributes() ?>>
<span id="el_systems_status">
<span<?php echo $systems->status->ViewAttributes() ?>>
<?php echo $systems->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->asset_category->Visible) { // asset_category ?>
	<tr id="r_asset_category">
		<td class="col-sm-2"><span id="elh_systems_asset_category"><?php echo $systems->asset_category->FldCaption() ?></span></td>
		<td data-name="asset_category"<?php echo $systems->asset_category->CellAttributes() ?>>
<span id="el_systems_asset_category">
<span<?php echo $systems->asset_category->ViewAttributes() ?>>
<?php echo $systems->asset_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->asset_sub_category->Visible) { // asset_sub_category ?>
	<tr id="r_asset_sub_category">
		<td class="col-sm-2"><span id="elh_systems_asset_sub_category"><?php echo $systems->asset_sub_category->FldCaption() ?></span></td>
		<td data-name="asset_sub_category"<?php echo $systems->asset_sub_category->CellAttributes() ?>>
<span id="el_systems_asset_sub_category">
<span<?php echo $systems->asset_sub_category->ViewAttributes() ?>>
<?php echo $systems->asset_sub_category->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->serial_number->Visible) { // serial_number ?>
	<tr id="r_serial_number">
		<td class="col-sm-2"><span id="elh_systems_serial_number"><?php echo $systems->serial_number->FldCaption() ?></span></td>
		<td data-name="serial_number"<?php echo $systems->serial_number->CellAttributes() ?>>
<span id="el_systems_serial_number">
<span<?php echo $systems->serial_number->ViewAttributes() ?>>
<?php echo $systems->serial_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->programe_area->Visible) { // programe_area ?>
	<tr id="r_programe_area">
		<td class="col-sm-2"><span id="elh_systems_programe_area"><?php echo $systems->programe_area->FldCaption() ?></span></td>
		<td data-name="programe_area"<?php echo $systems->programe_area->CellAttributes() ?>>
<span id="el_systems_programe_area">
<span<?php echo $systems->programe_area->ViewAttributes() ?>>
<?php echo $systems->programe_area->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->division->Visible) { // division ?>
	<tr id="r_division">
		<td class="col-sm-2"><span id="elh_systems_division"><?php echo $systems->division->FldCaption() ?></span></td>
		<td data-name="division"<?php echo $systems->division->CellAttributes() ?>>
<span id="el_systems_division">
<span<?php echo $systems->division->ViewAttributes() ?>>
<?php echo $systems->division->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->branch->Visible) { // branch ?>
	<tr id="r_branch">
		<td class="col-sm-2"><span id="elh_systems_branch"><?php echo $systems->branch->FldCaption() ?></span></td>
		<td data-name="branch"<?php echo $systems->branch->CellAttributes() ?>>
<span id="el_systems_branch">
<span<?php echo $systems->branch->ViewAttributes() ?>>
<?php echo $systems->branch->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->department->Visible) { // department ?>
	<tr id="r_department">
		<td class="col-sm-2"><span id="elh_systems_department"><?php echo $systems->department->FldCaption() ?></span></td>
		<td data-name="department"<?php echo $systems->department->CellAttributes() ?>>
<span id="el_systems_department">
<span<?php echo $systems->department->ViewAttributes() ?>>
<?php echo $systems->department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->staff_id->Visible) { // staff_id ?>
	<tr id="r_staff_id">
		<td class="col-sm-2"><span id="elh_systems_staff_id"><?php echo $systems->staff_id->FldCaption() ?></span></td>
		<td data-name="staff_id"<?php echo $systems->staff_id->CellAttributes() ?>>
<span id="el_systems_staff_id">
<span<?php echo $systems->staff_id->ViewAttributes() ?>>
<?php echo $systems->staff_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->created_by->Visible) { // created_by ?>
	<tr id="r_created_by">
		<td class="col-sm-2"><span id="elh_systems_created_by"><?php echo $systems->created_by->FldCaption() ?></span></td>
		<td data-name="created_by"<?php echo $systems->created_by->CellAttributes() ?>>
<span id="el_systems_created_by">
<span<?php echo $systems->created_by->ViewAttributes() ?>>
<?php echo $systems->created_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->created_date->Visible) { // created_date ?>
	<tr id="r_created_date">
		<td class="col-sm-2"><span id="elh_systems_created_date"><?php echo $systems->created_date->FldCaption() ?></span></td>
		<td data-name="created_date"<?php echo $systems->created_date->CellAttributes() ?>>
<span id="el_systems_created_date">
<span<?php echo $systems->created_date->ViewAttributes() ?>>
<?php echo $systems->created_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->device_number->Visible) { // device_number ?>
	<tr id="r_device_number">
		<td class="col-sm-2"><span id="elh_systems_device_number"><?php echo $systems->device_number->FldCaption() ?></span></td>
		<td data-name="device_number"<?php echo $systems->device_number->CellAttributes() ?>>
<span id="el_systems_device_number">
<span<?php echo $systems->device_number->ViewAttributes() ?>>
<?php echo $systems->device_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->tablet_imie_number->Visible) { // tablet_imie_number ?>
	<tr id="r_tablet_imie_number">
		<td class="col-sm-2"><span id="elh_systems_tablet_imie_number"><?php echo $systems->tablet_imie_number->FldCaption() ?></span></td>
		<td data-name="tablet_imie_number"<?php echo $systems->tablet_imie_number->CellAttributes() ?>>
<span id="el_systems_tablet_imie_number">
<span<?php echo $systems->tablet_imie_number->ViewAttributes() ?>>
<?php echo $systems->tablet_imie_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->model->Visible) { // model ?>
	<tr id="r_model">
		<td class="col-sm-2"><span id="elh_systems_model"><?php echo $systems->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $systems->model->CellAttributes() ?>>
<span id="el_systems_model">
<span<?php echo $systems->model->ViewAttributes() ?>>
<?php echo $systems->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->flag->Visible) { // flag ?>
	<tr id="r_flag">
		<td class="col-sm-2"><span id="elh_systems_flag"><?php echo $systems->flag->FldCaption() ?></span></td>
		<td data-name="flag"<?php echo $systems->flag->CellAttributes() ?>>
<span id="el_systems_flag">
<span<?php echo $systems->flag->ViewAttributes() ?>>
<?php echo $systems->flag->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->area->Visible) { // area ?>
	<tr id="r_area">
		<td class="col-sm-2"><span id="elh_systems_area"><?php echo $systems->area->FldCaption() ?></span></td>
		<td data-name="area"<?php echo $systems->area->CellAttributes() ?>>
<span id="el_systems_area">
<span<?php echo $systems->area->ViewAttributes() ?>>
<?php echo $systems->area->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->updated_date->Visible) { // updated_date ?>
	<tr id="r_updated_date">
		<td class="col-sm-2"><span id="elh_systems_updated_date"><?php echo $systems->updated_date->FldCaption() ?></span></td>
		<td data-name="updated_date"<?php echo $systems->updated_date->CellAttributes() ?>>
<span id="el_systems_updated_date">
<span<?php echo $systems->updated_date->ViewAttributes() ?>>
<?php echo $systems->updated_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->updated_by->Visible) { // updated_by ?>
	<tr id="r_updated_by">
		<td class="col-sm-2"><span id="elh_systems_updated_by"><?php echo $systems->updated_by->FldCaption() ?></span></td>
		<td data-name="updated_by"<?php echo $systems->updated_by->CellAttributes() ?>>
<span id="el_systems_updated_by">
<span<?php echo $systems->updated_by->ViewAttributes() ?>>
<?php echo $systems->updated_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->received_date->Visible) { // received_date ?>
	<tr id="r_received_date">
		<td class="col-sm-2"><span id="elh_systems_received_date"><?php echo $systems->received_date->FldCaption() ?></span></td>
		<td data-name="received_date"<?php echo $systems->received_date->CellAttributes() ?>>
<span id="el_systems_received_date">
<span<?php echo $systems->received_date->ViewAttributes() ?>>
<?php echo $systems->received_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($systems->received_by->Visible) { // received_by ?>
	<tr id="r_received_by">
		<td class="col-sm-2"><span id="elh_systems_received_by"><?php echo $systems->received_by->FldCaption() ?></span></td>
		<td data-name="received_by"<?php echo $systems->received_by->CellAttributes() ?>>
<span id="el_systems_received_by">
<span<?php echo $systems->received_by->ViewAttributes() ?>>
<?php echo $systems->received_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($systems->Export == "") { ?>
<script type="text/javascript">
fsystemsview.Init();
</script>
<?php } ?>
<?php
$systems_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($systems->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$systems_view->Page_Terminate();
?>
