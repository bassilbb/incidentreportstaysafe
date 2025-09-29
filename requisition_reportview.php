<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "requisition_reportinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$requisition_report_view = NULL; // Initialize page object first

class crequisition_report_view extends crequisition_report {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'requisition_report';

	// Page object name
	var $PageObjName = 'requisition_report_view';

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

		// Table object (requisition_report)
		if (!isset($GLOBALS["requisition_report"]) || get_class($GLOBALS["requisition_report"]) == "crequisition_report") {
			$GLOBALS["requisition_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["requisition_report"];
		}
		$KeyUrl = "";
		if (@$_GET["code"] <> "") {
			$this->RecKey["code"] = $_GET["code"];
			$KeyUrl .= "&amp;code=" . urlencode($this->RecKey["code"]);
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
			define("EW_PAGE_ID", 'view');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'requisition_report');

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
				$this->Page_Terminate(ew_GetUrl("requisition_reportlist.php"));
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
		if (@$_GET["code"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= $_GET["code"];
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
		$this->code->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->code->Visible = FALSE;
		$this->date->SetVisibility();
		$this->reference->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->outward_location->SetVisibility();
		$this->delivery_point->SetVisibility();
		$this->name->SetVisibility();
		$this->organization->SetVisibility();
		$this->designation->SetVisibility();
		$this->department->SetVisibility();
		$this->item_description->SetVisibility();
		$this->driver_name->SetVisibility();
		$this->vehicle_no->SetVisibility();
		$this->requester_action->SetVisibility();
		$this->requester_comment->SetVisibility();
		$this->date_authorized->SetVisibility();
		$this->authorizer_name->SetVisibility();
		$this->authorizer_action->SetVisibility();
		$this->authorizer_comment->SetVisibility();
		$this->status->SetVisibility();
		$this->rep_date->SetVisibility();
		$this->rep_name->SetVisibility();
		$this->outward_datetime->SetVisibility();
		$this->rep_action->SetVisibility();
		$this->rep_comment->SetVisibility();

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
		global $EW_EXPORT, $requisition_report;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($requisition_report);
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
					if ($pageName == "requisition_reportview.php")
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
			if (@$_GET["code"] <> "") {
				$this->code->setQueryStringValue($_GET["code"]);
				$this->RecKey["code"] = $this->code->QueryStringValue;
			} elseif (@$_POST["code"] <> "") {
				$this->code->setFormValue($_POST["code"]);
				$this->RecKey["code"] = $this->code->FormValue;
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
						$this->Page_Terminate("requisition_reportlist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetupStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->code->CurrentValue) == strval($this->Recordset->fields('code'))) {
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
						$sReturnUrl = "requisition_reportlist.php"; // No matching record, return to list
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
			$sReturnUrl = "requisition_reportlist.php"; // Not page request, return to list
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
		$this->code->setDbValue($row['code']);
		$this->date->setDbValue($row['date']);
		$this->reference->setDbValue($row['reference']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->outward_location->setDbValue($row['outward_location']);
		$this->delivery_point->setDbValue($row['delivery_point']);
		$this->name->setDbValue($row['name']);
		$this->organization->setDbValue($row['organization']);
		$this->designation->setDbValue($row['designation']);
		$this->department->setDbValue($row['department']);
		$this->item_description->setDbValue($row['item_description']);
		$this->driver_name->setDbValue($row['driver_name']);
		$this->vehicle_no->setDbValue($row['vehicle_no']);
		$this->requester_action->setDbValue($row['requester_action']);
		$this->requester_comment->setDbValue($row['requester_comment']);
		$this->date_authorized->setDbValue($row['date_authorized']);
		$this->authorizer_name->setDbValue($row['authorizer_name']);
		$this->authorizer_action->setDbValue($row['authorizer_action']);
		$this->authorizer_comment->setDbValue($row['authorizer_comment']);
		$this->status->setDbValue($row['status']);
		$this->rep_date->setDbValue($row['rep_date']);
		$this->rep_name->setDbValue($row['rep_name']);
		$this->outward_datetime->setDbValue($row['outward_datetime']);
		$this->rep_action->setDbValue($row['rep_action']);
		$this->rep_comment->setDbValue($row['rep_comment']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['date'] = NULL;
		$row['reference'] = NULL;
		$row['staff_id'] = NULL;
		$row['outward_location'] = NULL;
		$row['delivery_point'] = NULL;
		$row['name'] = NULL;
		$row['organization'] = NULL;
		$row['designation'] = NULL;
		$row['department'] = NULL;
		$row['item_description'] = NULL;
		$row['driver_name'] = NULL;
		$row['vehicle_no'] = NULL;
		$row['requester_action'] = NULL;
		$row['requester_comment'] = NULL;
		$row['date_authorized'] = NULL;
		$row['authorizer_name'] = NULL;
		$row['authorizer_action'] = NULL;
		$row['authorizer_comment'] = NULL;
		$row['status'] = NULL;
		$row['rep_date'] = NULL;
		$row['rep_name'] = NULL;
		$row['outward_datetime'] = NULL;
		$row['rep_action'] = NULL;
		$row['rep_comment'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->date->DbValue = $row['date'];
		$this->reference->DbValue = $row['reference'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->outward_location->DbValue = $row['outward_location'];
		$this->delivery_point->DbValue = $row['delivery_point'];
		$this->name->DbValue = $row['name'];
		$this->organization->DbValue = $row['organization'];
		$this->designation->DbValue = $row['designation'];
		$this->department->DbValue = $row['department'];
		$this->item_description->DbValue = $row['item_description'];
		$this->driver_name->DbValue = $row['driver_name'];
		$this->vehicle_no->DbValue = $row['vehicle_no'];
		$this->requester_action->DbValue = $row['requester_action'];
		$this->requester_comment->DbValue = $row['requester_comment'];
		$this->date_authorized->DbValue = $row['date_authorized'];
		$this->authorizer_name->DbValue = $row['authorizer_name'];
		$this->authorizer_action->DbValue = $row['authorizer_action'];
		$this->authorizer_comment->DbValue = $row['authorizer_comment'];
		$this->status->DbValue = $row['status'];
		$this->rep_date->DbValue = $row['rep_date'];
		$this->rep_name->DbValue = $row['rep_name'];
		$this->outward_datetime->DbValue = $row['outward_datetime'];
		$this->rep_action->DbValue = $row['rep_action'];
		$this->rep_comment->DbValue = $row['rep_comment'];
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
		// code
		// date
		// reference
		// staff_id
		// outward_location
		// delivery_point
		// name
		// organization
		// designation
		// department
		// item_description
		// driver_name
		// vehicle_no
		// requester_action
		// requester_comment
		// date_authorized
		// authorizer_name
		// authorizer_action
		// authorizer_comment
		// status
		// rep_date
		// rep_name
		// outward_datetime
		// rep_action
		// rep_comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// reference
		$this->reference->ViewValue = $this->reference->CurrentValue;
		$this->reference->ViewCustomAttributes = "";

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

		// outward_location
		$this->outward_location->ViewValue = $this->outward_location->CurrentValue;
		$this->outward_location->ViewCustomAttributes = "";

		// delivery_point
		$this->delivery_point->ViewValue = $this->delivery_point->CurrentValue;
		$this->delivery_point->ViewCustomAttributes = "";

		// name
		if (strval($this->name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->name->ViewValue = $this->name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->name->ViewValue = $this->name->CurrentValue;
			}
		} else {
			$this->name->ViewValue = NULL;
		}
		$this->name->ViewCustomAttributes = "";

		// organization
		$this->organization->ViewValue = $this->organization->CurrentValue;
		if (strval($this->organization->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->organization->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organization->ViewValue = $this->organization->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organization->ViewValue = $this->organization->CurrentValue;
			}
		} else {
			$this->organization->ViewValue = NULL;
		}
		$this->organization->ViewCustomAttributes = "";

		// designation
		$this->designation->ViewValue = $this->designation->CurrentValue;
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array("dx1" => '`department_name`');
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

		// item_description
		$this->item_description->ViewValue = $this->item_description->CurrentValue;
		$this->item_description->ViewCustomAttributes = "";

		// driver_name
		$this->driver_name->ViewValue = $this->driver_name->CurrentValue;
		$this->driver_name->ViewCustomAttributes = "";

		// vehicle_no
		$this->vehicle_no->ViewValue = $this->vehicle_no->CurrentValue;
		$this->vehicle_no->ViewCustomAttributes = "";

		// requester_action
		if (strval($this->requester_action->CurrentValue) <> "") {
			$this->requester_action->ViewValue = $this->requester_action->OptionCaption($this->requester_action->CurrentValue);
		} else {
			$this->requester_action->ViewValue = NULL;
		}
		$this->requester_action->ViewCustomAttributes = "";

		// requester_comment
		$this->requester_comment->ViewValue = $this->requester_comment->CurrentValue;
		$this->requester_comment->ViewCustomAttributes = "";

		// date_authorized
		$this->date_authorized->ViewValue = $this->date_authorized->CurrentValue;
		$this->date_authorized->ViewValue = ew_FormatDateTime($this->date_authorized->ViewValue, 17);
		$this->date_authorized->ViewCustomAttributes = "";

		// authorizer_name
		if (strval($this->authorizer_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->authorizer_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->authorizer_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->authorizer_name->ViewValue = $this->authorizer_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->authorizer_name->ViewValue = $this->authorizer_name->CurrentValue;
			}
		} else {
			$this->authorizer_name->ViewValue = NULL;
		}
		$this->authorizer_name->ViewCustomAttributes = "";

		// authorizer_action
		if (strval($this->authorizer_action->CurrentValue) <> "") {
			$this->authorizer_action->ViewValue = $this->authorizer_action->OptionCaption($this->authorizer_action->CurrentValue);
		} else {
			$this->authorizer_action->ViewValue = NULL;
		}
		$this->authorizer_action->ViewCustomAttributes = "";

		// authorizer_comment
		$this->authorizer_comment->ViewValue = $this->authorizer_comment->CurrentValue;
		$this->authorizer_comment->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
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

		// rep_date
		$this->rep_date->ViewValue = $this->rep_date->CurrentValue;
		$this->rep_date->ViewValue = ew_FormatDateTime($this->rep_date->ViewValue, 17);
		$this->rep_date->ViewCustomAttributes = "";

		// rep_name
		$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
		if (strval($this->rep_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->rep_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->rep_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->rep_name->ViewValue = $this->rep_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
			}
		} else {
			$this->rep_name->ViewValue = NULL;
		}
		$this->rep_name->ViewCustomAttributes = "";

		// outward_datetime
		$this->outward_datetime->ViewValue = $this->outward_datetime->CurrentValue;
		$this->outward_datetime->ViewValue = ew_FormatDateTime($this->outward_datetime->ViewValue, 17);
		$this->outward_datetime->ViewCustomAttributes = "";

		// rep_action
		if (strval($this->rep_action->CurrentValue) <> "") {
			$this->rep_action->ViewValue = $this->rep_action->OptionCaption($this->rep_action->CurrentValue);
		} else {
			$this->rep_action->ViewValue = NULL;
		}
		$this->rep_action->ViewCustomAttributes = "";

		// rep_comment
		$this->rep_comment->ViewValue = $this->rep_comment->CurrentValue;
		$this->rep_comment->ViewCustomAttributes = "";

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
			$this->code->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";
			$this->reference->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// outward_location
			$this->outward_location->LinkCustomAttributes = "";
			$this->outward_location->HrefValue = "";
			$this->outward_location->TooltipValue = "";

			// delivery_point
			$this->delivery_point->LinkCustomAttributes = "";
			$this->delivery_point->HrefValue = "";
			$this->delivery_point->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// organization
			$this->organization->LinkCustomAttributes = "";
			$this->organization->HrefValue = "";
			$this->organization->TooltipValue = "";

			// designation
			$this->designation->LinkCustomAttributes = "";
			$this->designation->HrefValue = "";
			$this->designation->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// item_description
			$this->item_description->LinkCustomAttributes = "";
			$this->item_description->HrefValue = "";
			$this->item_description->TooltipValue = "";

			// driver_name
			$this->driver_name->LinkCustomAttributes = "";
			$this->driver_name->HrefValue = "";
			$this->driver_name->TooltipValue = "";

			// vehicle_no
			$this->vehicle_no->LinkCustomAttributes = "";
			$this->vehicle_no->HrefValue = "";
			$this->vehicle_no->TooltipValue = "";

			// requester_action
			$this->requester_action->LinkCustomAttributes = "";
			$this->requester_action->HrefValue = "";
			$this->requester_action->TooltipValue = "";

			// requester_comment
			$this->requester_comment->LinkCustomAttributes = "";
			$this->requester_comment->HrefValue = "";
			$this->requester_comment->TooltipValue = "";

			// date_authorized
			$this->date_authorized->LinkCustomAttributes = "";
			$this->date_authorized->HrefValue = "";
			$this->date_authorized->TooltipValue = "";

			// authorizer_name
			$this->authorizer_name->LinkCustomAttributes = "";
			$this->authorizer_name->HrefValue = "";
			$this->authorizer_name->TooltipValue = "";

			// authorizer_action
			$this->authorizer_action->LinkCustomAttributes = "";
			$this->authorizer_action->HrefValue = "";
			$this->authorizer_action->TooltipValue = "";

			// authorizer_comment
			$this->authorizer_comment->LinkCustomAttributes = "";
			$this->authorizer_comment->HrefValue = "";
			$this->authorizer_comment->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// rep_date
			$this->rep_date->LinkCustomAttributes = "";
			$this->rep_date->HrefValue = "";
			$this->rep_date->TooltipValue = "";

			// rep_name
			$this->rep_name->LinkCustomAttributes = "";
			$this->rep_name->HrefValue = "";
			$this->rep_name->TooltipValue = "";

			// outward_datetime
			$this->outward_datetime->LinkCustomAttributes = "";
			$this->outward_datetime->HrefValue = "";
			$this->outward_datetime->TooltipValue = "";

			// rep_action
			$this->rep_action->LinkCustomAttributes = "";
			$this->rep_action->HrefValue = "";
			$this->rep_action->TooltipValue = "";

			// rep_comment
			$this->rep_comment->LinkCustomAttributes = "";
			$this->rep_comment->HrefValue = "";
			$this->rep_comment->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_requisition_report\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_requisition_report',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.frequisition_reportview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("requisition_reportlist.php"), "", $this->TableVar, TRUE);
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
		$pages->Add(3);
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
if (!isset($requisition_report_view)) $requisition_report_view = new crequisition_report_view();

// Page init
$requisition_report_view->Page_Init();

// Page main
$requisition_report_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$requisition_report_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($requisition_report->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = frequisition_reportview = new ew_Form("frequisition_reportview", "view");

// Form_CustomValidate event
frequisition_reportview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frequisition_reportview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
frequisition_reportview.MultiPage = new ew_MultiPage("frequisition_reportview");

// Dynamic selection lists
frequisition_reportview.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportview.Lists["x_staff_id"].Data = "<?php echo $requisition_report_view->staff_id->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_view->staff_id->LookupFilterQuery(TRUE, "view"))) ?>;
frequisition_reportview.Lists["x_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportview.Lists["x_name"].Data = "<?php echo $requisition_report_view->name->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.Lists["x_organization"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
frequisition_reportview.Lists["x_organization"].Data = "<?php echo $requisition_report_view->organization->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.AutoSuggests["x_organization"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_view->organization->LookupFilterQuery(TRUE, "view"))) ?>;
frequisition_reportview.Lists["x_designation"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"designation"};
frequisition_reportview.Lists["x_designation"].Data = "<?php echo $requisition_report_view->designation->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.AutoSuggests["x_designation"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_view->designation->LookupFilterQuery(TRUE, "view"))) ?>;
frequisition_reportview.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
frequisition_reportview.Lists["x_department"].Data = "<?php echo $requisition_report_view->department->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.Lists["x_requester_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_reportview.Lists["x_requester_action"].Options = <?php echo json_encode($requisition_report_view->requester_action->Options()) ?>;
frequisition_reportview.Lists["x_authorizer_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportview.Lists["x_authorizer_name"].Data = "<?php echo $requisition_report_view->authorizer_name->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.Lists["x_authorizer_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_reportview.Lists["x_authorizer_action"].Options = <?php echo json_encode($requisition_report_view->authorizer_action->Options()) ?>;
frequisition_reportview.Lists["x_status"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status_ssf"};
frequisition_reportview.Lists["x_status"].Data = "<?php echo $requisition_report_view->status->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.AutoSuggests["x_status"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_view->status->LookupFilterQuery(TRUE, "view"))) ?>;
frequisition_reportview.Lists["x_rep_name"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","x_staffno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frequisition_reportview.Lists["x_rep_name"].Data = "<?php echo $requisition_report_view->rep_name->LookupFilterQuery(FALSE, "view") ?>";
frequisition_reportview.AutoSuggests["x_rep_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $requisition_report_view->rep_name->LookupFilterQuery(TRUE, "view"))) ?>;
frequisition_reportview.Lists["x_rep_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frequisition_reportview.Lists["x_rep_action"].Options = <?php echo json_encode($requisition_report_view->rep_action->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.

function ShowCertificate() {


	var curCode = "<?php echo $requisition_report->code->ViewValue ?>";
	window.location.href='material_note.php?code='+curCode;
}


</script>
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
<div class="ewToolbar">
<?php $requisition_report_view->ExportOptions->Render("body") ?>
<?php
	foreach ($requisition_report_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $requisition_report_view->ShowPageHeader(); ?>
<?php
$requisition_report_view->ShowMessage();
?>
<?php if (!$requisition_report_view->IsModal) { ?>
<?php if ($requisition_report->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($requisition_report_view->Pager)) $requisition_report_view->Pager = new cPrevNextPager($requisition_report_view->StartRec, $requisition_report_view->DisplayRecs, $requisition_report_view->TotalRecs, $requisition_report_view->AutoHidePager) ?>
<?php if ($requisition_report_view->Pager->RecordCount > 0 && $requisition_report_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($requisition_report_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $requisition_report_view->PageUrl() ?>start=<?php echo $requisition_report_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($requisition_report_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $requisition_report_view->PageUrl() ?>start=<?php echo $requisition_report_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $requisition_report_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($requisition_report_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $requisition_report_view->PageUrl() ?>start=<?php echo $requisition_report_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($requisition_report_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $requisition_report_view->PageUrl() ?>start=<?php echo $requisition_report_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $requisition_report_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="frequisition_reportview" id="frequisition_reportview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($requisition_report_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $requisition_report_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="requisition_report">
<input type="hidden" name="modal" value="<?php echo intval($requisition_report_view->IsModal) ?>">

<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" onclick="ShowCertificate();" type="button"><?php echo "View Material Outward Note"; ?></button>

<?php if ($requisition_report->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="requisition_report_view">
	<ul class="nav<?php echo $requisition_report_view->MultiPages->NavStyle() ?>">
		<li<?php echo $requisition_report_view->MultiPages->TabStyle("1") ?>><a href="#tab_requisition_report1" data-toggle="tab"><?php echo $requisition_report->PageCaption(1) ?></a></li>
		<li<?php echo $requisition_report_view->MultiPages->TabStyle("2") ?>><a href="#tab_requisition_report2" data-toggle="tab"><?php echo $requisition_report->PageCaption(2) ?></a></li>
		<li<?php echo $requisition_report_view->MultiPages->TabStyle("3") ?>><a href="#tab_requisition_report3" data-toggle="tab"><?php echo $requisition_report->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
		<div class="tab-pane<?php echo $requisition_report_view->MultiPages->PageStyle("1") ?>" id="tab_requisition_report1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($requisition_report->code->Visible) { // code ?>
	<tr id="r_code">
		<td class="col-sm-2"><span id="elh_requisition_report_code"><?php echo $requisition_report->code->FldCaption() ?></span></td>
		<td data-name="code"<?php echo $requisition_report->code->CellAttributes() ?>>
<span id="el_requisition_report_code" data-page="1">
<span<?php echo $requisition_report->code->ViewAttributes() ?>>
<?php echo $requisition_report->code->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->date->Visible) { // date ?>
	<tr id="r_date">
		<td class="col-sm-2"><span id="elh_requisition_report_date"><?php echo $requisition_report->date->FldCaption() ?></span></td>
		<td data-name="date"<?php echo $requisition_report->date->CellAttributes() ?>>
<span id="el_requisition_report_date" data-page="1">
<span<?php echo $requisition_report->date->ViewAttributes() ?>>
<?php echo $requisition_report->date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->reference->Visible) { // reference ?>
	<tr id="r_reference">
		<td class="col-sm-2"><span id="elh_requisition_report_reference"><?php echo $requisition_report->reference->FldCaption() ?></span></td>
		<td data-name="reference"<?php echo $requisition_report->reference->CellAttributes() ?>>
<span id="el_requisition_report_reference" data-page="1">
<span<?php echo $requisition_report->reference->ViewAttributes() ?>>
<?php echo $requisition_report->reference->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->staff_id->Visible) { // staff_id ?>
	<tr id="r_staff_id">
		<td class="col-sm-2"><span id="elh_requisition_report_staff_id"><?php echo $requisition_report->staff_id->FldCaption() ?></span></td>
		<td data-name="staff_id"<?php echo $requisition_report->staff_id->CellAttributes() ?>>
<span id="el_requisition_report_staff_id" data-page="1">
<span<?php echo $requisition_report->staff_id->ViewAttributes() ?>>
<?php echo $requisition_report->staff_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->outward_location->Visible) { // outward_location ?>
	<tr id="r_outward_location">
		<td class="col-sm-2"><span id="elh_requisition_report_outward_location"><?php echo $requisition_report->outward_location->FldCaption() ?></span></td>
		<td data-name="outward_location"<?php echo $requisition_report->outward_location->CellAttributes() ?>>
<span id="el_requisition_report_outward_location" data-page="1">
<span<?php echo $requisition_report->outward_location->ViewAttributes() ?>>
<?php echo $requisition_report->outward_location->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->delivery_point->Visible) { // delivery_point ?>
	<tr id="r_delivery_point">
		<td class="col-sm-2"><span id="elh_requisition_report_delivery_point"><?php echo $requisition_report->delivery_point->FldCaption() ?></span></td>
		<td data-name="delivery_point"<?php echo $requisition_report->delivery_point->CellAttributes() ?>>
<span id="el_requisition_report_delivery_point" data-page="1">
<span<?php echo $requisition_report->delivery_point->ViewAttributes() ?>>
<?php echo $requisition_report->delivery_point->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->name->Visible) { // name ?>
	<tr id="r_name">
		<td class="col-sm-2"><span id="elh_requisition_report_name"><?php echo $requisition_report->name->FldCaption() ?></span></td>
		<td data-name="name"<?php echo $requisition_report->name->CellAttributes() ?>>
<span id="el_requisition_report_name" data-page="1">
<span<?php echo $requisition_report->name->ViewAttributes() ?>>
<?php echo $requisition_report->name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->organization->Visible) { // organization ?>
	<tr id="r_organization">
		<td class="col-sm-2"><span id="elh_requisition_report_organization"><?php echo $requisition_report->organization->FldCaption() ?></span></td>
		<td data-name="organization"<?php echo $requisition_report->organization->CellAttributes() ?>>
<span id="el_requisition_report_organization" data-page="1">
<span<?php echo $requisition_report->organization->ViewAttributes() ?>>
<?php echo $requisition_report->organization->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->designation->Visible) { // designation ?>
	<tr id="r_designation">
		<td class="col-sm-2"><span id="elh_requisition_report_designation"><?php echo $requisition_report->designation->FldCaption() ?></span></td>
		<td data-name="designation"<?php echo $requisition_report->designation->CellAttributes() ?>>
<span id="el_requisition_report_designation" data-page="1">
<span<?php echo $requisition_report->designation->ViewAttributes() ?>>
<?php echo $requisition_report->designation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->department->Visible) { // department ?>
	<tr id="r_department">
		<td class="col-sm-2"><span id="elh_requisition_report_department"><?php echo $requisition_report->department->FldCaption() ?></span></td>
		<td data-name="department"<?php echo $requisition_report->department->CellAttributes() ?>>
<span id="el_requisition_report_department" data-page="1">
<span<?php echo $requisition_report->department->ViewAttributes() ?>>
<?php echo $requisition_report->department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->item_description->Visible) { // item_description ?>
	<tr id="r_item_description">
		<td class="col-sm-2"><span id="elh_requisition_report_item_description"><?php echo $requisition_report->item_description->FldCaption() ?></span></td>
		<td data-name="item_description"<?php echo $requisition_report->item_description->CellAttributes() ?>>
<span id="el_requisition_report_item_description" data-page="1">
<span<?php echo $requisition_report->item_description->ViewAttributes() ?>>
<?php echo $requisition_report->item_description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->driver_name->Visible) { // driver_name ?>
	<tr id="r_driver_name">
		<td class="col-sm-2"><span id="elh_requisition_report_driver_name"><?php echo $requisition_report->driver_name->FldCaption() ?></span></td>
		<td data-name="driver_name"<?php echo $requisition_report->driver_name->CellAttributes() ?>>
<span id="el_requisition_report_driver_name" data-page="1">
<span<?php echo $requisition_report->driver_name->ViewAttributes() ?>>
<?php echo $requisition_report->driver_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->vehicle_no->Visible) { // vehicle_no ?>
	<tr id="r_vehicle_no">
		<td class="col-sm-2"><span id="elh_requisition_report_vehicle_no"><?php echo $requisition_report->vehicle_no->FldCaption() ?></span></td>
		<td data-name="vehicle_no"<?php echo $requisition_report->vehicle_no->CellAttributes() ?>>
<span id="el_requisition_report_vehicle_no" data-page="1">
<span<?php echo $requisition_report->vehicle_no->ViewAttributes() ?>>
<?php echo $requisition_report->vehicle_no->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->requester_action->Visible) { // requester_action ?>
	<tr id="r_requester_action">
		<td class="col-sm-2"><span id="elh_requisition_report_requester_action"><?php echo $requisition_report->requester_action->FldCaption() ?></span></td>
		<td data-name="requester_action"<?php echo $requisition_report->requester_action->CellAttributes() ?>>
<span id="el_requisition_report_requester_action" data-page="1">
<span<?php echo $requisition_report->requester_action->ViewAttributes() ?>>
<?php echo $requisition_report->requester_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->requester_comment->Visible) { // requester_comment ?>
	<tr id="r_requester_comment">
		<td class="col-sm-2"><span id="elh_requisition_report_requester_comment"><?php echo $requisition_report->requester_comment->FldCaption() ?></span></td>
		<td data-name="requester_comment"<?php echo $requisition_report->requester_comment->CellAttributes() ?>>
<span id="el_requisition_report_requester_comment" data-page="1">
<span<?php echo $requisition_report->requester_comment->ViewAttributes() ?>>
<?php echo $requisition_report->requester_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($requisition_report->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
		<div class="tab-pane<?php echo $requisition_report_view->MultiPages->PageStyle("2") ?>" id="tab_requisition_report2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($requisition_report->date_authorized->Visible) { // date_authorized ?>
	<tr id="r_date_authorized">
		<td class="col-sm-2"><span id="elh_requisition_report_date_authorized"><?php echo $requisition_report->date_authorized->FldCaption() ?></span></td>
		<td data-name="date_authorized"<?php echo $requisition_report->date_authorized->CellAttributes() ?>>
<span id="el_requisition_report_date_authorized" data-page="2">
<span<?php echo $requisition_report->date_authorized->ViewAttributes() ?>>
<?php echo $requisition_report->date_authorized->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->authorizer_name->Visible) { // authorizer_name ?>
	<tr id="r_authorizer_name">
		<td class="col-sm-2"><span id="elh_requisition_report_authorizer_name"><?php echo $requisition_report->authorizer_name->FldCaption() ?></span></td>
		<td data-name="authorizer_name"<?php echo $requisition_report->authorizer_name->CellAttributes() ?>>
<span id="el_requisition_report_authorizer_name" data-page="2">
<span<?php echo $requisition_report->authorizer_name->ViewAttributes() ?>>
<?php echo $requisition_report->authorizer_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->authorizer_action->Visible) { // authorizer_action ?>
	<tr id="r_authorizer_action">
		<td class="col-sm-2"><span id="elh_requisition_report_authorizer_action"><?php echo $requisition_report->authorizer_action->FldCaption() ?></span></td>
		<td data-name="authorizer_action"<?php echo $requisition_report->authorizer_action->CellAttributes() ?>>
<span id="el_requisition_report_authorizer_action" data-page="2">
<span<?php echo $requisition_report->authorizer_action->ViewAttributes() ?>>
<?php echo $requisition_report->authorizer_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->authorizer_comment->Visible) { // authorizer_comment ?>
	<tr id="r_authorizer_comment">
		<td class="col-sm-2"><span id="elh_requisition_report_authorizer_comment"><?php echo $requisition_report->authorizer_comment->FldCaption() ?></span></td>
		<td data-name="authorizer_comment"<?php echo $requisition_report->authorizer_comment->CellAttributes() ?>>
<span id="el_requisition_report_authorizer_comment" data-page="2">
<span<?php echo $requisition_report->authorizer_comment->ViewAttributes() ?>>
<?php echo $requisition_report->authorizer_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->status->Visible) { // status ?>
	<tr id="r_status">
		<td class="col-sm-2"><span id="elh_requisition_report_status"><?php echo $requisition_report->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $requisition_report->status->CellAttributes() ?>>
<span id="el_requisition_report_status" data-page="2">
<span<?php echo $requisition_report->status->ViewAttributes() ?>>
<?php echo $requisition_report->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($requisition_report->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
		<div class="tab-pane<?php echo $requisition_report_view->MultiPages->PageStyle("3") ?>" id="tab_requisition_report3">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($requisition_report->rep_date->Visible) { // rep_date ?>
	<tr id="r_rep_date">
		<td class="col-sm-2"><span id="elh_requisition_report_rep_date"><?php echo $requisition_report->rep_date->FldCaption() ?></span></td>
		<td data-name="rep_date"<?php echo $requisition_report->rep_date->CellAttributes() ?>>
<span id="el_requisition_report_rep_date" data-page="3">
<span<?php echo $requisition_report->rep_date->ViewAttributes() ?>>
<?php echo $requisition_report->rep_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->rep_name->Visible) { // rep_name ?>
	<tr id="r_rep_name">
		<td class="col-sm-2"><span id="elh_requisition_report_rep_name"><?php echo $requisition_report->rep_name->FldCaption() ?></span></td>
		<td data-name="rep_name"<?php echo $requisition_report->rep_name->CellAttributes() ?>>
<span id="el_requisition_report_rep_name" data-page="3">
<span<?php echo $requisition_report->rep_name->ViewAttributes() ?>>
<?php echo $requisition_report->rep_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->outward_datetime->Visible) { // outward_datetime ?>
	<tr id="r_outward_datetime">
		<td class="col-sm-2"><span id="elh_requisition_report_outward_datetime"><?php echo $requisition_report->outward_datetime->FldCaption() ?></span></td>
		<td data-name="outward_datetime"<?php echo $requisition_report->outward_datetime->CellAttributes() ?>>
<span id="el_requisition_report_outward_datetime" data-page="3">
<span<?php echo $requisition_report->outward_datetime->ViewAttributes() ?>>
<?php echo $requisition_report->outward_datetime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->rep_action->Visible) { // rep_action ?>
	<tr id="r_rep_action">
		<td class="col-sm-2"><span id="elh_requisition_report_rep_action"><?php echo $requisition_report->rep_action->FldCaption() ?></span></td>
		<td data-name="rep_action"<?php echo $requisition_report->rep_action->CellAttributes() ?>>
<span id="el_requisition_report_rep_action" data-page="3">
<span<?php echo $requisition_report->rep_action->ViewAttributes() ?>>
<?php echo $requisition_report->rep_action->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($requisition_report->rep_comment->Visible) { // rep_comment ?>
	<tr id="r_rep_comment">
		<td class="col-sm-2"><span id="elh_requisition_report_rep_comment"><?php echo $requisition_report->rep_comment->FldCaption() ?></span></td>
		<td data-name="rep_comment"<?php echo $requisition_report->rep_comment->CellAttributes() ?>>
<span id="el_requisition_report_rep_comment" data-page="3">
<span<?php echo $requisition_report->rep_comment->ViewAttributes() ?>>
<?php echo $requisition_report->rep_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($requisition_report->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($requisition_report->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($requisition_report->Export == "") { ?>
<script type="text/javascript">
frequisition_reportview.Init();
</script>
<?php } ?>
<?php
$requisition_report_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($requisition_report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$requisition_report_view->Page_Terminate();
?>
