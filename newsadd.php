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

$news_add = NULL; // Initialize page object first

class cnews_add extends cnews {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'news';

	// Page object name
	var $PageObjName = 'news_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
			if (@$_GET["code"] != "") {
				$this->code->setQueryStringValue($_GET["code"]);
				$this->setKey("code", $this->code->CurrentValue); // Set up key
			} else {
				$this->setKey("code", ""); // Clear key
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
					$this->Page_Terminate("newslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "newslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "newsview.php")
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
		$this->news_image->Upload->Index = $objForm->Index;
		$this->news_image->Upload->UploadFile();
		$this->news_image->CurrentValue = $this->news_image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->code->CurrentValue = NULL;
		$this->code->OldValue = $this->code->CurrentValue;
		$this->news->CurrentValue = NULL;
		$this->news->OldValue = $this->news->CurrentValue;
		$this->news_image->Upload->DbValue = NULL;
		$this->news_image->OldValue = $this->news_image->Upload->DbValue;
		$this->news_image->CurrentValue = NULL; // Clear file related field
		$this->news_content->CurrentValue = NULL;
		$this->news_content->OldValue = $this->news_content->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
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
		$this->news->CurrentValue = $this->news->FormValue;
		$this->news_content->CurrentValue = $this->news_content->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['code'] = $this->code->CurrentValue;
		$row['news'] = $this->news->CurrentValue;
		$row['news_image'] = $this->news_image->Upload->DbValue;
		$row['news_content'] = $this->news_content->CurrentValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->news_image);

			// news_content
			$this->news_content->EditAttrs["class"] = "form-control";
			$this->news_content->EditCustomAttributes = "";
			$this->news_content->EditValue = ew_HtmlEncode($this->news_content->CurrentValue);
			$this->news_content->PlaceHolder = ew_RemoveHtml($this->news_content->FldCaption());

			// Add refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
			$this->news_image->OldUploadPath = "image/";
			$this->news_image->UploadPath = $this->news_image->OldUploadPath;
		}
		$rsnew = array();

		// news
		$this->news->SetDbValueDef($rsnew, $this->news->CurrentValue, NULL, FALSE);

		// news_image
		if ($this->news_image->Visible && !$this->news_image->Upload->KeepFile) {
			$this->news_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->news_image->Upload->FileName == "") {
				$rsnew['news_image'] = NULL;
			} else {
				$rsnew['news_image'] = $this->news_image->Upload->FileName;
			}
			$this->news_image->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH; // Resize width
			$this->news_image->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT; // Resize height
		}

		// news_content
		$this->news_content->SetDbValueDef($rsnew, $this->news_content->CurrentValue, NULL, FALSE);
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
				$this->news_image->SetDbValueDef($rsnew, $this->news_image->Upload->FileName, NULL, FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// news_image
		ew_CleanUploadTempPath($this->news_image, $this->news_image->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("newslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($news_add)) $news_add = new cnews_add();

// Page init
$news_add->Page_Init();

// Page main
$news_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$news_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fnewsadd = new ew_Form("fnewsadd", "add");

// Validate form
fnewsadd.Validate = function() {
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
fnewsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fnewsadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $news_add->ShowPageHeader(); ?>
<?php
$news_add->ShowMessage();
?>
<form name="fnewsadd" id="fnewsadd" class="<?php echo $news_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($news_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $news_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="news">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($news_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($news->news->Visible) { // news ?>
	<div id="r_news" class="form-group">
		<label id="elh_news_news" for="x_news" class="<?php echo $news_add->LeftColumnClass ?>"><?php echo $news->news->FldCaption() ?></label>
		<div class="<?php echo $news_add->RightColumnClass ?>"><div<?php echo $news->news->CellAttributes() ?>>
<span id="el_news_news">
<input type="text" data-table="news" data-field="x_news" name="x_news" id="x_news" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($news->news->getPlaceHolder()) ?>" value="<?php echo $news->news->EditValue ?>"<?php echo $news->news->EditAttributes() ?>>
</span>
<?php echo $news->news->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($news->news_image->Visible) { // news_image ?>
	<div id="r_news_image" class="form-group">
		<label id="elh_news_news_image" class="<?php echo $news_add->LeftColumnClass ?>"><?php echo $news->news_image->FldCaption() ?></label>
		<div class="<?php echo $news_add->RightColumnClass ?>"><div<?php echo $news->news_image->CellAttributes() ?>>
<span id="el_news_news_image">
<div id="fd_x_news_image">
<span title="<?php echo $news->news_image->FldTitle() ? $news->news_image->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($news->news_image->ReadOnly || $news->news_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="news" data-field="x_news_image" name="x_news_image" id="x_news_image" multiple="multiple"<?php echo $news->news_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_news_image" id= "fn_x_news_image" value="<?php echo $news->news_image->Upload->FileName ?>">
<input type="hidden" name="fa_x_news_image" id= "fa_x_news_image" value="0">
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
		<label id="elh_news_news_content" class="<?php echo $news_add->LeftColumnClass ?>"><?php echo $news->news_content->FldCaption() ?></label>
		<div class="<?php echo $news_add->RightColumnClass ?>"><div<?php echo $news->news_content->CellAttributes() ?>>
<span id="el_news_news_content">
<?php ew_AppendClass($news->news_content->EditAttrs["class"], "editor"); ?>
<textarea data-table="news" data-field="x_news_content" name="x_news_content" id="x_news_content" cols="100" rows="20" placeholder="<?php echo ew_HtmlEncode($news->news_content->getPlaceHolder()) ?>"<?php echo $news->news_content->EditAttributes() ?>><?php echo $news->news_content->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnewsadd", "x_news_content", 100, 20, <?php echo ($news->news_content->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $news->news_content->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$news_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $news_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $news_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fnewsadd.Init();
</script>
<?php
$news_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$news_add->Page_Terminate();
?>
