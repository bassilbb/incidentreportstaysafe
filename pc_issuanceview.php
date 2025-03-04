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

$pc_issuance_view = NULL; // Initialize page object first

class cpc_issuance_view extends cpc_issuance {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'pc_issuance';

	// Page object name
	var $PageObjName = 'pc_issuance_view';

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

		// Table object (pc_issuance)
		if (!isset($GLOBALS["pc_issuance"]) || get_class($GLOBALS["pc_issuance"]) == "cpc_issuance") {
			$GLOBALS["pc_issuance"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pc_issuance"];
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
				$this->Page_Terminate(ew_GetUrl("pc_issuancelist.php"));
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
		$this->issued_date->SetVisibility();
		$this->reference_id->SetVisibility();
		$this->asset_tag->SetVisibility();
		$this->make->SetVisibility();
		$this->ram->SetVisibility();
		$this->hard_disk->SetVisibility();
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
		$this->staff_id->SetVisibility();

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
						$this->Page_Terminate("pc_issuancelist.php"); // Return to list page
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
						$sReturnUrl = "pc_issuancelist.php"; // No matching record, return to list
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
			$sReturnUrl = "pc_issuancelist.php"; // Not page request, return to list
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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
		$this->id->setDbValue($row['id']);
		$this->issued_date->setDbValue($row['issued_date']);
		$this->reference_id->setDbValue($row['reference_id']);
		$this->asset_tag->setDbValue($row['asset_tag']);
		$this->make->setDbValue($row['make']);
		$this->ram->setDbValue($row['ram']);
		$this->hard_disk->setDbValue($row['hard_disk']);
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
		$this->staff_id->setDbValue($row['staff_id']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['issued_date'] = NULL;
		$row['reference_id'] = NULL;
		$row['asset_tag'] = NULL;
		$row['make'] = NULL;
		$row['ram'] = NULL;
		$row['hard_disk'] = NULL;
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
		$row['staff_id'] = NULL;
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
		$this->ram->DbValue = $row['ram'];
		$this->hard_disk->DbValue = $row['hard_disk'];
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
		$this->staff_id->DbValue = $row['staff_id'];
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
		// issued_date
		// reference_id
		// asset_tag
		// make
		// ram
		// hard_disk
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
		// staff_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// issued_date
		$this->issued_date->ViewValue = $this->issued_date->CurrentValue;
		$this->issued_date->ViewValue = ew_FormatDateTime($this->issued_date->ViewValue, 17);
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

		// ram
		$this->ram->ViewValue = $this->ram->CurrentValue;
		$this->ram->ViewCustomAttributes = "";

		// hard_disk
		$this->hard_disk->ViewValue = $this->hard_disk->CurrentValue;
		$this->hard_disk->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// department
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

		// designation
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->designation->ViewValue = $this->designation->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->designation->ViewValue = $this->designation->CurrentValue;
			}
		} else {
			$this->designation->ViewValue = NULL;
		}
		$this->designation->ViewCustomAttributes = "";

		// assign_to
		if (strval($this->assign_to->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_to->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_to->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_to, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->assign_to->ViewValue = $this->assign_to->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_to->ViewValue = $this->assign_to->CurrentValue;
			}
		} else {
			$this->assign_to->ViewValue = NULL;
		}
		$this->assign_to->ViewCustomAttributes = "";

		// date_assign
		$this->date_assign->ViewValue = $this->date_assign->CurrentValue;
		$this->date_assign->ViewValue = ew_FormatDateTime($this->date_assign->ViewValue, 17);
		$this->date_assign->ViewCustomAttributes = "";

		// assign_action
		if (strval($this->assign_action->CurrentValue) <> "") {
			$this->assign_action->ViewValue = $this->assign_action->OptionCaption($this->assign_action->CurrentValue);
		} else {
			$this->assign_action->ViewValue = NULL;
		}
		$this->assign_action->ViewCustomAttributes = "";

		// assign_comment
		$this->assign_comment->ViewValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->ViewCustomAttributes = "";

		// assign_by
		if (strval($this->assign_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->assign_by->ViewValue = $this->assign_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_by->ViewValue = $this->assign_by->CurrentValue;
			}
		} else {
			$this->assign_by->ViewValue = NULL;
		}
		$this->assign_by->ViewCustomAttributes = "";

		// statuse
		if (strval($this->statuse->CurrentValue) <> "") {
			$arwrk = explode(",", $this->statuse->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_status`";
		$sWhereWrk = "";
		$this->statuse->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->statuse, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->statuse->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->statuse->ViewValue .= $this->statuse->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->statuse->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->statuse->ViewValue = $this->statuse->CurrentValue;
			}
		} else {
			$this->statuse->ViewValue = NULL;
		}
		$this->statuse->ViewCustomAttributes = "";

		// date_retrieved
		$this->date_retrieved->ViewValue = $this->date_retrieved->CurrentValue;
		$this->date_retrieved->ViewValue = ew_FormatDateTime($this->date_retrieved->ViewValue, 17);
		$this->date_retrieved->ViewCustomAttributes = "";

		// retriever_action
		if (strval($this->retriever_action->CurrentValue) <> "") {
			$this->retriever_action->ViewValue = $this->retriever_action->OptionCaption($this->retriever_action->CurrentValue);
		} else {
			$this->retriever_action->ViewValue = NULL;
		}
		$this->retriever_action->ViewCustomAttributes = "";

		// retriever_comment
		$this->retriever_comment->ViewValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->ViewCustomAttributes = "";

		// retrieved_by
		if (strval($this->retrieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->retrieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->retrieved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->retrieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->retrieved_by->ViewValue = $this->retrieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->retrieved_by->ViewValue = $this->retrieved_by->CurrentValue;
			}
		} else {
			$this->retrieved_by->ViewValue = NULL;
		}
		$this->retrieved_by->ViewCustomAttributes = "";

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

			// ram
			$this->ram->LinkCustomAttributes = "";
			$this->ram->HrefValue = "";
			$this->ram->TooltipValue = "";

			// hard_disk
			$this->hard_disk->LinkCustomAttributes = "";
			$this->hard_disk->HrefValue = "";
			$this->hard_disk->TooltipValue = "";

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

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_pc_issuance\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pc_issuance',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpc_issuanceview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pc_issuancelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($pc_issuance_view)) $pc_issuance_view = new cpc_issuance_view();

// Page init
$pc_issuance_view->Page_Init();

// Page main
$pc_issuance_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pc_issuance_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pc_issuance->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fpc_issuanceview = new ew_Form("fpc_issuanceview", "view");

// Form_CustomValidate event
fpc_issuanceview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpc_issuanceview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fpc_issuanceview.MultiPage = new ew_MultiPage("fpc_issuanceview");

// Dynamic selection lists
fpc_issuanceview.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
fpc_issuanceview.Lists["x_department"].Data = "<?php echo $pc_issuance_view->department->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
fpc_issuanceview.Lists["x_designation"].Data = "<?php echo $pc_issuance_view->designation->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.Lists["x_assign_to"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuanceview.Lists["x_assign_to"].Data = "<?php echo $pc_issuance_view->assign_to->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.Lists["x_assign_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpc_issuanceview.Lists["x_assign_action"].Options = <?php echo json_encode($pc_issuance_view->assign_action->Options()) ?>;
fpc_issuanceview.Lists["x_assign_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuanceview.Lists["x_assign_by"].Data = "<?php echo $pc_issuance_view->assign_by->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.Lists["x_statuse[]"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"system_status"};
fpc_issuanceview.Lists["x_statuse[]"].Data = "<?php echo $pc_issuance_view->statuse->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.Lists["x_retriever_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpc_issuanceview.Lists["x_retriever_action"].Options = <?php echo json_encode($pc_issuance_view->retriever_action->Options()) ?>;
fpc_issuanceview.Lists["x_retrieved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuanceview.Lists["x_retrieved_by"].Data = "<?php echo $pc_issuance_view->retrieved_by->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fpc_issuanceview.Lists["x_staff_id"].Data = "<?php echo $pc_issuance_view->staff_id->LookupFilterQuery(FALSE, "view") ?>";
fpc_issuanceview.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $pc_issuance_view->staff_id->LookupFilterQuery(TRUE, "view"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pc_issuance->Export == "") { ?>
<div class="ewToolbar">
<?php $pc_issuance_view->ExportOptions->Render("body") ?>
<?php
	foreach ($pc_issuance_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pc_issuance_view->ShowPageHeader(); ?>
<?php
$pc_issuance_view->ShowMessage();
?>
<?php if (!$pc_issuance_view->IsModal) { ?>
<?php if ($pc_issuance->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pc_issuance_view->Pager)) $pc_issuance_view->Pager = new cPrevNextPager($pc_issuance_view->StartRec, $pc_issuance_view->DisplayRecs, $pc_issuance_view->TotalRecs, $pc_issuance_view->AutoHidePager) ?>
<?php if ($pc_issuance_view->Pager->RecordCount > 0 && $pc_issuance_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pc_issuance_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pc_issuance_view->PageUrl() ?>start=<?php echo $pc_issuance_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pc_issuance_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pc_issuance_view->PageUrl() ?>start=<?php echo $pc_issuance_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pc_issuance_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pc_issuance_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pc_issuance_view->PageUrl() ?>start=<?php echo $pc_issuance_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pc_issuance_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pc_issuance_view->PageUrl() ?>start=<?php echo $pc_issuance_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pc_issuance_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fpc_issuanceview" id="fpc_issuanceview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pc_issuance_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pc_issuance_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pc_issuance">
<input type="hidden" name="modal" value="<?php echo intval($pc_issuance_view->IsModal) ?>">
<?php if ($pc_issuance->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="pc_issuance_view">
	<ul class="nav<?php echo $pc_issuance_view->MultiPages->NavStyle() ?>">
		<li<?php echo $pc_issuance_view->MultiPages->TabStyle("1") ?>><a href="#tab_pc_issuance1" data-toggle="tab"><?php echo $pc_issuance->PageCaption(1) ?></a></li>
		<li<?php echo $pc_issuance_view->MultiPages->TabStyle("2") ?>><a href="#tab_pc_issuance2" data-toggle="tab"><?php echo $pc_issuance->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($pc_issuance->Export == "") { ?>
		<div class="tab-pane<?php echo $pc_issuance_view->MultiPages->PageStyle("1") ?>" id="tab_pc_issuance1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($pc_issuance->id->Visible) { // id ?>
	<tr id="r_id">
		<td class="col-sm-2"><span id="elh_pc_issuance_id"><?php echo $pc_issuance->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $pc_issuance->id->CellAttributes() ?>>
<span id="el_pc_issuance_id" data-page="1">
<span<?php echo $pc_issuance->id->ViewAttributes() ?>>
<?php echo $pc_issuance->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->issued_date->Visible) { // issued_date ?>
	<tr id="r_issued_date">
		<td class="col-sm-2"><span id="elh_pc_issuance_issued_date"><?php echo $pc_issuance->issued_date->FldCaption() ?></span></td>
		<td data-name="issued_date"<?php echo $pc_issuance->issued_date->CellAttributes() ?>>
<span id="el_pc_issuance_issued_date" data-page="1">
<span<?php echo $pc_issuance->issued_date->ViewAttributes() ?>>
<?php echo $pc_issuance->issued_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->reference_id->Visible) { // reference_id ?>
	<tr id="r_reference_id">
		<td class="col-sm-2"><span id="elh_pc_issuance_reference_id"><?php echo $pc_issuance->reference_id->FldCaption() ?></span></td>
		<td data-name="reference_id"<?php echo $pc_issuance->reference_id->CellAttributes() ?>>
<span id="el_pc_issuance_reference_id" data-page="1">
<span<?php echo $pc_issuance->reference_id->ViewAttributes() ?>>
<?php echo $pc_issuance->reference_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->asset_tag->Visible) { // asset_tag ?>
	<tr id="r_asset_tag">
		<td class="col-sm-2"><span id="elh_pc_issuance_asset_tag"><?php echo $pc_issuance->asset_tag->FldCaption() ?></span></td>
		<td data-name="asset_tag"<?php echo $pc_issuance->asset_tag->CellAttributes() ?>>
<span id="el_pc_issuance_asset_tag" data-page="1">
<span<?php echo $pc_issuance->asset_tag->ViewAttributes() ?>>
<?php echo $pc_issuance->asset_tag->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->make->Visible) { // make ?>
	<tr id="r_make">
		<td class="col-sm-2"><span id="elh_pc_issuance_make"><?php echo $pc_issuance->make->FldCaption() ?></span></td>
		<td data-name="make"<?php echo $pc_issuance->make->CellAttributes() ?>>
<span id="el_pc_issuance_make" data-page="1">
<span<?php echo $pc_issuance->make->ViewAttributes() ?>>
<?php echo $pc_issuance->make->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->ram->Visible) { // ram ?>
	<tr id="r_ram">
		<td class="col-sm-2"><span id="elh_pc_issuance_ram"><?php echo $pc_issuance->ram->FldCaption() ?></span></td>
		<td data-name="ram"<?php echo $pc_issuance->ram->CellAttributes() ?>>
<span id="el_pc_issuance_ram" data-page="1">
<span<?php echo $pc_issuance->ram->ViewAttributes() ?>>
<?php echo $pc_issuance->ram->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->hard_disk->Visible) { // hard_disk ?>
	<tr id="r_hard_disk">
		<td class="col-sm-2"><span id="elh_pc_issuance_hard_disk"><?php echo $pc_issuance->hard_disk->FldCaption() ?></span></td>
		<td data-name="hard_disk"<?php echo $pc_issuance->hard_disk->CellAttributes() ?>>
<span id="el_pc_issuance_hard_disk" data-page="1">
<span<?php echo $pc_issuance->hard_disk->ViewAttributes() ?>>
<?php echo $pc_issuance->hard_disk->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->color->Visible) { // color ?>
	<tr id="r_color">
		<td class="col-sm-2"><span id="elh_pc_issuance_color"><?php echo $pc_issuance->color->FldCaption() ?></span></td>
		<td data-name="color"<?php echo $pc_issuance->color->CellAttributes() ?>>
<span id="el_pc_issuance_color" data-page="1">
<span<?php echo $pc_issuance->color->ViewAttributes() ?>>
<?php echo $pc_issuance->color->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->department->Visible) { // department ?>
	<tr id="r_department">
		<td class="col-sm-2"><span id="elh_pc_issuance_department"><?php echo $pc_issuance->department->FldCaption() ?></span></td>
		<td data-name="department"<?php echo $pc_issuance->department->CellAttributes() ?>>
<span id="el_pc_issuance_department" data-page="1">
<span<?php echo $pc_issuance->department->ViewAttributes() ?>>
<?php echo $pc_issuance->department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->designation->Visible) { // designation ?>
	<tr id="r_designation">
		<td class="col-sm-2"><span id="elh_pc_issuance_designation"><?php echo $pc_issuance->designation->FldCaption() ?></span></td>
		<td data-name="designation"<?php echo $pc_issuance->designation->CellAttributes() ?>>
<span id="el_pc_issuance_designation" data-page="1">
<span<?php echo $pc_issuance->designation->ViewAttributes() ?>>
<?php echo $pc_issuance->designation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->assign_to->Visible) { // assign_to ?>
	<tr id="r_assign_to">
		<td class="col-sm-2"><span id="elh_pc_issuance_assign_to"><?php echo $pc_issuance->assign_to->FldCaption() ?></span></td>
		<td data-name="assign_to"<?php echo $pc_issuance->assign_to->CellAttributes() ?>>
<span id="el_pc_issuance_assign_to" data-page="1">
<span<?php echo $pc_issuance->assign_to->ViewAttributes() ?>>
<?php echo $pc_issuance->assign_to->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->date_assign->Visible) { // date_assign ?>
	<tr id="r_date_assign">
		<td class="col-sm-2"><span id="elh_pc_issuance_date_assign"><?php echo $pc_issuance->date_assign->FldCaption() ?></span></td>
		<td data-name="date_assign"<?php echo $pc_issuance->date_assign->CellAttributes() ?>>
<span id="el_pc_issuance_date_assign" data-page="1">
<span<?php echo $pc_issuance->date_assign->ViewAttributes() ?>>
<?php echo $pc_issuance->date_assign->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->assign_action->Visible) { // assign_action ?>
	<tr id="r_assign_action">
		<td class="col-sm-2"><span id="elh_pc_issuance_assign_action"><?php echo $pc_issuance->assign_action->FldCaption() ?></span></td>
		<td data-name="assign_action"<?php echo $pc_issuance->assign_action->CellAttributes() ?>>
<span id="el_pc_issuance_assign_action" data-page="1">
<span<?php echo $pc_issuance->assign_action->ViewAttributes() ?>>
<?php echo $pc_issuance->assign_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->assign_comment->Visible) { // assign_comment ?>
	<tr id="r_assign_comment">
		<td class="col-sm-2"><span id="elh_pc_issuance_assign_comment"><?php echo $pc_issuance->assign_comment->FldCaption() ?></span></td>
		<td data-name="assign_comment"<?php echo $pc_issuance->assign_comment->CellAttributes() ?>>
<span id="el_pc_issuance_assign_comment" data-page="1">
<span<?php echo $pc_issuance->assign_comment->ViewAttributes() ?>>
<?php echo $pc_issuance->assign_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->assign_by->Visible) { // assign_by ?>
	<tr id="r_assign_by">
		<td class="col-sm-2"><span id="elh_pc_issuance_assign_by"><?php echo $pc_issuance->assign_by->FldCaption() ?></span></td>
		<td data-name="assign_by"<?php echo $pc_issuance->assign_by->CellAttributes() ?>>
<span id="el_pc_issuance_assign_by" data-page="1">
<span<?php echo $pc_issuance->assign_by->ViewAttributes() ?>>
<?php echo $pc_issuance->assign_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->staff_id->Visible) { // staff_id ?>
	<tr id="r_staff_id">
		<td class="col-sm-2"><span id="elh_pc_issuance_staff_id"><?php echo $pc_issuance->staff_id->FldCaption() ?></span></td>
		<td data-name="staff_id"<?php echo $pc_issuance->staff_id->CellAttributes() ?>>
<span id="el_pc_issuance_staff_id" data-page="1">
<span<?php echo $pc_issuance->staff_id->ViewAttributes() ?>>
<?php echo $pc_issuance->staff_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($pc_issuance->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($pc_issuance->Export == "") { ?>
		<div class="tab-pane<?php echo $pc_issuance_view->MultiPages->PageStyle("2") ?>" id="tab_pc_issuance2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($pc_issuance->statuse->Visible) { // statuse ?>
	<tr id="r_statuse">
		<td class="col-sm-2"><span id="elh_pc_issuance_statuse"><?php echo $pc_issuance->statuse->FldCaption() ?></span></td>
		<td data-name="statuse"<?php echo $pc_issuance->statuse->CellAttributes() ?>>
<span id="el_pc_issuance_statuse" data-page="2">
<span<?php echo $pc_issuance->statuse->ViewAttributes() ?>>
<?php echo $pc_issuance->statuse->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->date_retrieved->Visible) { // date_retrieved ?>
	<tr id="r_date_retrieved">
		<td class="col-sm-2"><span id="elh_pc_issuance_date_retrieved"><?php echo $pc_issuance->date_retrieved->FldCaption() ?></span></td>
		<td data-name="date_retrieved"<?php echo $pc_issuance->date_retrieved->CellAttributes() ?>>
<span id="el_pc_issuance_date_retrieved" data-page="2">
<span<?php echo $pc_issuance->date_retrieved->ViewAttributes() ?>>
<?php echo $pc_issuance->date_retrieved->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->retriever_action->Visible) { // retriever_action ?>
	<tr id="r_retriever_action">
		<td class="col-sm-2"><span id="elh_pc_issuance_retriever_action"><?php echo $pc_issuance->retriever_action->FldCaption() ?></span></td>
		<td data-name="retriever_action"<?php echo $pc_issuance->retriever_action->CellAttributes() ?>>
<span id="el_pc_issuance_retriever_action" data-page="2">
<span<?php echo $pc_issuance->retriever_action->ViewAttributes() ?>>
<?php echo $pc_issuance->retriever_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->retriever_comment->Visible) { // retriever_comment ?>
	<tr id="r_retriever_comment">
		<td class="col-sm-2"><span id="elh_pc_issuance_retriever_comment"><?php echo $pc_issuance->retriever_comment->FldCaption() ?></span></td>
		<td data-name="retriever_comment"<?php echo $pc_issuance->retriever_comment->CellAttributes() ?>>
<span id="el_pc_issuance_retriever_comment" data-page="2">
<span<?php echo $pc_issuance->retriever_comment->ViewAttributes() ?>>
<?php echo $pc_issuance->retriever_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pc_issuance->retrieved_by->Visible) { // retrieved_by ?>
	<tr id="r_retrieved_by">
		<td class="col-sm-2"><span id="elh_pc_issuance_retrieved_by"><?php echo $pc_issuance->retrieved_by->FldCaption() ?></span></td>
		<td data-name="retrieved_by"<?php echo $pc_issuance->retrieved_by->CellAttributes() ?>>
<span id="el_pc_issuance_retrieved_by" data-page="2">
<span<?php echo $pc_issuance->retrieved_by->ViewAttributes() ?>>
<?php echo $pc_issuance->retrieved_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($pc_issuance->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($pc_issuance->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($pc_issuance->Export == "") { ?>
<script type="text/javascript">
fpc_issuanceview.Init();
</script>
<?php } ?>
<?php
$pc_issuance_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pc_issuance->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pc_issuance_view->Page_Terminate();
?>
