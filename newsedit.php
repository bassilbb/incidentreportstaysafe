<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "newsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$news_edit = NULL; // Initialize page object first

class cnews_edit extends cnews {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'news';

	// Page object name
	var $PageObjName = 'news_edit';

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

		// Table object (news)
		if (!isset($GLOBALS["news"]) || get_class($GLOBALS["news"]) == "cnews") {
			$GLOBALS["news"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["news"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'news', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("newslist.php"));
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
		$this->code->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->code->Visible = FALSE;
		$this->news->SetVisibility();
		$this->news_image->SetVisibility();
		$this->news_content->SetVisibility();

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
		global $EW_EXPORT, $news;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($news);
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
					if ($pageName == "newsview.php")
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
			if ($objForm->HasValue("x_code")) {
				$this->code->setFormValue($objForm->GetValue("x_code"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["code"])) {
				$this->code->setQueryStringValue($_GET["code"]);
				$loadByQuery = TRUE;
			} else {
				$this->code->CurrentValue = NULL;
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
			$this->Page_Terminate("newslist.php"); // Return to list page
		} elseif ($loadByPosition) { // Load record by position
			$this->SetupStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$this->Recordset->Move($this->StartRec-1);
				$loaded = TRUE;
			}
		} else { // Match key values
			if (!is_null($this->code->CurrentValue)) {
				while (!$this->Recordset->EOF) {
					if (strval($this->code->CurrentValue) == strval($this->Recordset->fields('code'))) {
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
					$this->Page_Terminate("newslist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "newslist.php")
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
		$this->news_image->Upload->Index = $objForm->Index;
		$this->news_image->Upload->UploadFile();
		$this->news_image->CurrentValue = $this->news_image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->code->FldIsDetailKey)
			$this->code->setFormValue($objForm->GetValue("x_code"));
		if (!$this->news->FldIsDetailKey) {
			$this->news->setFormValue($objForm->GetValue("x_news"));
		}
		if (!$this->news_content->FldIsDetailKey) {
			$this->news_content->setFormValue($objForm->GetValue("x_news_content"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->code->CurrentValue = $this->code->FormValue;
		$this->news->CurrentValue = $this->news->FormValue;
		$this->news_content->CurrentValue = $this->news_content->FormValue;
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
		$this->code->setDbValue($row['code']);
		$this->news->setDbValue($row['news']);
		$this->news_image->Upload->DbValue = $row['news_image'];
		$this->news_image->setDbValue($this->news_image->Upload->DbValue);
		$this->news_content->setDbValue($row['news_content']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['code'] = NULL;
		$row['news'] = NULL;
		$row['news_image'] = NULL;
		$row['news_content'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->code->DbValue = $row['code'];
		$this->news->DbValue = $row['news'];
		$this->news_image->Upload->DbValue = $row['news_image'];
		$this->news_content->DbValue = $row['news_content'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("code")) <> "")
			$this->code->CurrentValue = $this->getKey("code"); // code
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
		// code
		// news
		// news_image
		// news_content

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// news
		$this->news->ViewValue = $this->news->CurrentValue;
		$this->news->ViewCustomAttributes = "";

		// news_image
		$this->news_image->UploadPath = "image/";
		if (!ew_Empty($this->news_image->Upload->DbValue)) {
			$this->news_image->ImageWidth = 50;
			$this->news_image->ImageHeight = 30;
			$this->news_image->ImageAlt = $this->news_image->FldAlt();
			$this->news_image->ViewValue = $this->news_image->Upload->DbValue;
		} else {
			$this->news_image->ViewValue = "";
		}
		$this->news_image->ViewCustomAttributes = "";

		// news_content
		$this->news_content->ViewValue = $this->news_content->CurrentValue;
		$this->news_content->ViewCustomAttributes = "";

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
			$this->code->TooltipValue = "";

			// news
			$this->news->LinkCustomAttributes = "";
			$this->news->HrefValue = "";
			$this->news->TooltipValue = "";

			// news_image
			$this->news_image->LinkCustomAttributes = "";
			$this->news_image->UploadPath = "image/";
			if (!ew_Empty($this->news_image->Upload->DbValue)) {
				$this->news_image->HrefValue = "%u"; // Add prefix/suffix
				$this->news_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->news_image->HrefValue = ew_FullUrl($this->news_image->HrefValue, "href");
			} else {
				$this->news_image->HrefValue = "";
			}
			$this->news_image->HrefValue2 = $this->news_image->UploadPath . $this->news_image->Upload->DbValue;
			$this->news_image->TooltipValue = "";
			if ($this->news_image->UseColorbox) {
				if (ew_Empty($this->news_image->TooltipValue))
					$this->news_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->news_image->LinkAttrs["data-rel"] = "news_x_news_image";
				ew_AppendClass($this->news_image->LinkAttrs["class"], "ewLightbox");
			}

			// news_content
			$this->news_content->LinkCustomAttributes = "";
			$this->news_content->HrefValue = "";
			$this->news_content->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = $this->code->CurrentValue;
			$this->code->ViewCustomAttributes = "";

			// news
			$this->news->EditAttrs["class"] = "form-control";
			$this->news->EditCustomAttributes = "";
			$this->news->EditValue = ew_HtmlEncode($this->news->CurrentValue);
			$this->news->PlaceHolder = ew_RemoveHtml($this->news->FldCaption());

			// news_image
			$this->news_image->EditAttrs["class"] = "form-control";
			$this->news_image->EditCustomAttributes = "";
			$this->news_image->UploadPath = "image/";
			if (!ew_Empty($this->news_image->Upload->DbValue)) {
				$this->news_image->ImageWidth = 50;
				$this->news_image->ImageHeight = 30;
				$this->news_image->ImageAlt = $this->news_image->FldAlt();
				$this->news_image->EditValue = $this->news_image->Upload->DbValue;
			} else {
				$this->news_image->EditValue = "";
			}
			if (!ew_Empty($this->news_image->CurrentValue))
					$this->news_image->Upload->FileName = $this->news_image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->news_image);

			// news_content
			$this->news_content->EditAttrs["class"] = "form-control";
			$this->news_content->EditCustomAttributes = "";
			$this->news_content->EditValue = ew_HtmlEncode($this->news_content->CurrentValue);
			$this->news_content->PlaceHolder = ew_RemoveHtml($this->news_content->FldCaption());

			// Edit refer script
			// code

			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";

			// news
			$this->news->LinkCustomAttributes = "";
			$this->news->HrefValue = "";

			// news_image
			$this->news_image->LinkCustomAttributes = "";
			$this->news_image->UploadPath = "image/";
			if (!ew_Empty($this->news_image->Upload->DbValue)) {
				$this->news_image->HrefValue = "%u"; // Add prefix/suffix
				$this->news_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->news_image->HrefValue = ew_FullUrl($this->news_image->HrefValue, "href");
			} else {
				$this->news_image->HrefValue = "";
			}
			$this->news_image->HrefValue2 = $this->news_image->UploadPath . $this->news_image->Upload->DbValue;

			// news_content
			$this->news_content->LinkCustomAttributes = "";
			$this->news_content->HrefValue = "";
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
			$this->news_image->OldUploadPath = "image/";
			$this->news_image->UploadPath = $this->news_image->OldUploadPath;
			$rsnew = array();

			// news
			$this->news->SetDbValueDef($rsnew, $this->news->CurrentValue, NULL, $this->news->ReadOnly);

			// news_image
			if ($this->news_image->Visible && !$this->news_image->ReadOnly && !$this->news_image->Upload->KeepFile) {
				$this->news_image->Upload->DbValue = $rsold['news_image']; // Get original value
				if ($this->news_image->Upload->FileName == "") {
					$rsnew['news_image'] = NULL;
				} else {
					$rsnew['news_image'] = $this->news_image->Upload->FileName;
				}
				$this->news_image->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH; // Resize width
				$this->news_image->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT; // Resize height
			}

			// news_content
			$this->news_content->SetDbValueDef($rsnew, $this->news_content->CurrentValue, NULL, $this->news_content->ReadOnly);
			if ($this->news_image->Visible && !$this->news_image->Upload->KeepFile) {
				$this->news_image->UploadPath = "image/";
				$OldFiles = ew_Empty($this->news_image->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->news_image->Upload->DbValue));
				if (!ew_Empty($this->news_image->Upload->FileName)) {
					$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->news_image->Upload->FileName));
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->news_image->Upload->Index < 0) ? $this->news_image->FldVar : substr($this->news_image->FldVar, 0, 1) . $this->news_image->Upload->Index . substr($this->news_image->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->news_image->TblVar) . $file)) {
								$OldFileFound = FALSE;
								$OldFileCount = count($OldFiles);
								for ($j = 0; $j < $OldFileCount; $j++) {
									$file1 = $OldFiles[$j];
									if ($file1 == $file) { // Old file found, no need to delete anymore
										unset($OldFiles[$j]);
										$OldFileFound = TRUE;
										break;
									}
								}
								if ($OldFileFound) // No need to check if file exists further
									continue;
								$file1 = ew_UploadFileNameEx($this->news_image->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->news_image->TblVar) . $file1) || file_exists($this->news_image->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->news_image->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->news_image->TblVar) . $file, ew_UploadTempPath($fldvar, $this->news_image->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->news_image->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->news_image->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->news_image->SetDbValueDef($rsnew, $this->news_image->Upload->FileName, NULL, $this->news_image->ReadOnly);
				}
			}

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
					if ($this->news_image->Visible && !$this->news_image->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->news_image->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->news_image->Upload->DbValue));
						if (!ew_Empty($this->news_image->Upload->FileName)) {
							$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->news_image->Upload->FileName);
							$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['news_image']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->news_image->Upload->Index < 0) ? $this->news_image->FldVar : substr($this->news_image->FldVar, 0, 1) . $this->news_image->Upload->Index . substr($this->news_image->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->news_image->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->news_image->Upload->ResizeAndSaveToFile($this->news_image->ImageWidth, $this->news_image->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
											$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
											return FALSE;
										}
									}
								}
							}
						} else {
							$NewFiles = array();
						}
						$OldFileCount = count($OldFiles);
						for ($i = 0; $i < $OldFileCount; $i++) {
							if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
								@unlink($this->news_image->OldPhysicalUploadPath() . $OldFiles[$i]);
						}
					}
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

		// news_image
		ew_CleanUploadTempPath($this->news_image, $this->news_image->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("newslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($news_edit)) $news_edit = new cnews_edit();

// Page init
$news_edit->Page_Init();

// Page main
$news_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$news_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fnewsedit = new ew_Form("fnewsedit", "edit");

// Validate form
fnewsedit.Validate = function() {
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
fnewsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fnewsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $news_edit->ShowPageHeader(); ?>
<?php
$news_edit->ShowMessage();
?>
<?php if (!$news_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($news_edit->Pager)) $news_edit->Pager = new cPrevNextPager($news_edit->StartRec, $news_edit->DisplayRecs, $news_edit->TotalRecs, $news_edit->AutoHidePager) ?>
<?php if ($news_edit->Pager->RecordCount > 0 && $news_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($news_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $news_edit->PageUrl() ?>start=<?php echo $news_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($news_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $news_edit->PageUrl() ?>start=<?php echo $news_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $news_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($news_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $news_edit->PageUrl() ?>start=<?php echo $news_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($news_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $news_edit->PageUrl() ?>start=<?php echo $news_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $news_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fnewsedit" id="fnewsedit" class="<?php echo $news_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($news_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $news_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="news">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($news_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($news->code->Visible) { // code ?>
	<div id="r_code" class="form-group">
		<label id="elh_news_code" class="<?php echo $news_edit->LeftColumnClass ?>"><?php echo $news->code->FldCaption() ?></label>
		<div class="<?php echo $news_edit->RightColumnClass ?>"><div<?php echo $news->code->CellAttributes() ?>>
<span id="el_news_code">
<span<?php echo $news->code->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $news->code->EditValue ?></p></span>
</span>
<input type="hidden" data-table="news" data-field="x_code" name="x_code" id="x_code" value="<?php echo ew_HtmlEncode($news->code->CurrentValue) ?>">
<?php echo $news->code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->news->Visible) { // news ?>
	<div id="r_news" class="form-group">
		<label id="elh_news_news" for="x_news" class="<?php echo $news_edit->LeftColumnClass ?>"><?php echo $news->news->FldCaption() ?></label>
		<div class="<?php echo $news_edit->RightColumnClass ?>"><div<?php echo $news->news->CellAttributes() ?>>
<span id="el_news_news">
<input type="text" data-table="news" data-field="x_news" name="x_news" id="x_news" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($news->news->getPlaceHolder()) ?>" value="<?php echo $news->news->EditValue ?>"<?php echo $news->news->EditAttributes() ?>>
</span>
<?php echo $news->news->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->news_image->Visible) { // news_image ?>
	<div id="r_news_image" class="form-group">
		<label id="elh_news_news_image" class="<?php echo $news_edit->LeftColumnClass ?>"><?php echo $news->news_image->FldCaption() ?></label>
		<div class="<?php echo $news_edit->RightColumnClass ?>"><div<?php echo $news->news_image->CellAttributes() ?>>
<span id="el_news_news_image">
<div id="fd_x_news_image">
<span title="<?php echo $news->news_image->FldTitle() ? $news->news_image->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($news->news_image->ReadOnly || $news->news_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="news" data-field="x_news_image" name="x_news_image" id="x_news_image" multiple="multiple"<?php echo $news->news_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_news_image" id= "fn_x_news_image" value="<?php echo $news->news_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_news_image"] == "0") { ?>
<input type="hidden" name="fa_x_news_image" id= "fa_x_news_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_news_image" id= "fa_x_news_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_news_image" id= "fs_x_news_image" value="128">
<input type="hidden" name="fx_x_news_image" id= "fx_x_news_image" value="<?php echo $news->news_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_news_image" id= "fm_x_news_image" value="<?php echo $news->news_image->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_news_image" id= "fc_x_news_image" value="<?php echo $news->news_image->UploadMaxFileCount ?>">
</div>
<table id="ft_x_news_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $news->news_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->news_content->Visible) { // news_content ?>
	<div id="r_news_content" class="form-group">
		<label id="elh_news_news_content" class="<?php echo $news_edit->LeftColumnClass ?>"><?php echo $news->news_content->FldCaption() ?></label>
		<div class="<?php echo $news_edit->RightColumnClass ?>"><div<?php echo $news->news_content->CellAttributes() ?>>
<span id="el_news_news_content">
<?php ew_AppendClass($news->news_content->EditAttrs["class"], "editor"); ?>
<textarea data-table="news" data-field="x_news_content" name="x_news_content" id="x_news_content" cols="100" rows="20" placeholder="<?php echo ew_HtmlEncode($news->news_content->getPlaceHolder()) ?>"<?php echo $news->news_content->EditAttributes() ?>><?php echo $news->news_content->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnewsedit", "x_news_content", 100, 20, <?php echo ($news->news_content->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $news->news_content->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$news_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $news_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $news_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fnewsedit.Init();
</script>
<?php
$news_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$news_edit->Page_Terminate();
?>
