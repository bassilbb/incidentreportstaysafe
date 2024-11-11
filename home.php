<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$home_php = NULL; // Initialize page object first

class chome_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'home.php';

	// Page object name
	var $PageObjName = 'home_php';

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
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'home.php');

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect();

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
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		if (@$_GET["export"] <> "")
			$gsExport = $_GET["export"]; // Get export parameter, used in header

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

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

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		// Close connection

		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "home_php", $url, "", "home_php", TRUE);
		$this->Heading = $Language->TablePhrase("home_php", "TblCaption"); 
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($home_php)) $home_php = new chome_php();

// Page init
$home_php->Page_Init();

// Page main
$home_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<div class="panel panel-danger">
	 <div class="panel-heading">
	 <marquee width="70%" direction="left" height="20px">
		<strong><span style="color:#8d1c2d !important">WELCOME TO STAYSAFE LIMITED INCIDENT-REPORTING-PLATFORM:    All Issue Reported Here Are Being Treated Realtime:  Ensure To Login At Interval To Check Task Assigned To You:    Signed Management!  <?php //echo $_SESSION['Staff_Name']?> </span></strong> 
		<!--<i style="color:#fff01"> | Staff ID: <?php echo $_SESSION['Staff_ID']?> | Role: <?php echo $_SESSION['Department']?> | Company: <?php echo $_SESSION['Company']?> </i>-->
		</marquee>
	 </div>
 </div>

 <div class="alert alert-success alert-dismissible">
	<button type = "button" class="close" data-dismiss = "alert">x</button>
	Welcome!
	<?php 
		if(isset($_SESSION['Firstname'])){
		echo $_SESSION['Firstname'];}else{echo "Administrator";} 
		?>
	</div>




	 <!-- <div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h3><i class="icon fa fa-check"></i> Welcome 
		<?php 
		if(isset($_SESSION['Firstname'])){
		echo $_SESSION['Firstname'];}else{echo "Administrator"; } 
		?></h3>
	 </div>-->
	 
<a href="report_formlist.php" class="small-box-footer"> 
<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-aqua-gradient">
				<span class="info-box-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">New Issues</span>
					<span class="info-box-number"><?php echo $_SESSION['MyNewCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>

		<!-- <div class="col-lg-2 col-xs-6"> -->
		  <!-- small box -->
		  <!-- <div class="small-box bg-aqua">
			<div class="inner">
			  <h3><</h3>

			  <p>New Records</p>
			</div>
			<div class="icon">
			  <i class="ion ion-bag"></i>
			</div>
			<a href="report_formedit.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		  </div>
		</div> -->

		<a href="report_formlist.php" class="small-box-footer">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-red-gradient">
				<span class="info-box-icon"><i class="fa fa-ban" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Pending Isssue</span>
					<span class="info-box-number"><?php echo $_SESSION['MyPendingCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>



		<!-- ./col -->
		<!-- <div class="col-lg-2 col-xs-6"> -->
		  <!-- small box -->
		  <!-- <div class="small-box bg-green">
			<div class="inner">
			  <h3><?php echo $_SESSION['MyApprovedCount'] ?></h3>

			  <p>Closed Records</p>
			</div>
			<div class="icon">
			  <i class="ion ion-stats-bars"></i>
			</div>
			<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		  </div>
		</div> -->

		<a href="report_formlist.php" class="small-box-footer">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-yellow-gradient">
				<span class="info-box-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Reworked Issue</span>
					<span class="info-box-number"><?php echo $_SESSION['MyReworkCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		<i class="fa-solid fa-pen-to-square"></i>
		</a>

		<!-- ./col -->
		<!-- <div class="col-lg-2 col-xs-6"> -->
		  <!-- small box -->
		  <!-- <div class="small-box bg-yellow">
			<div class="inner">
			  <h3><?php echo $_SESSION['MyReworkCount'] ?></h3>

			  <p>Rework Record</p>
			</div>
			<div class="icon">
			  <i class="ion ion-person-add"></i>
			</div>
			<a href="report_formlist.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		  </div>
		</div> -->



		 <a href="reportlist.php" class="small-box-footer">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-green-gradient">
			
				<span class="info-box-icon"><i class="fa fa-thumbs-up" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Closed Issued</span>
					<span class="info-box-number"><?php echo $_SESSION['MyApprovedCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>

	<a href="assign_tasklist.php" class="small-box-footer">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-blue-gradient">
				<span class="info-box-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Task Assign</span>
					<span class="info-box-number"><?php echo $_SESSION['MyAssigntaskCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>
		
		<a href="report_formlist.php" class="small-box-footer">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-teal-gradient">
				<span class="info-box-icon"><i class="fa fa-thumbs-up" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Awaits verification</span>
					<span class="info-box-number"><?php echo $_SESSION['MyIssueresolvedCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>

		<a href="maintenancelist.php" class="small-box-footer">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-purple-gradient">
				<span class="info-box-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Maintenance Ticket Initiated</span>
					<span class="info-box-number"><?php echo $_SESSION['MyMaintenancetickketCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>

			<a href="maintenance_reportlist.php" class="small-box-footer">
			<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-green-gradient">
				<span class="info-box-icon"><i class="fa fa-thumbs-up" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Ticket Reviewed & closed</span>
					<span class="info-box-number"><?php echo $_SESSION['MyTicketreviewedCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>


		<a href="dispenser_reportlist.php" class="small-box-footer">
			<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-teal-gradient">
				<span class="info-box-icon"><i class="fa fa-glass" aria-hidden="true"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Dispenser Activity</span>
					<span class="info-box-number"><?php echo $_SESSION['MyDispenserCount'] ?></span>

				</div><!-- /.info-box-content -->
			</div><!-- /.info-box -->
		</div>
		</a>

	




 <!--<div class="card bg-dark text-white">
  <img src="picture/download.jpg"  class="card-img" alt="..." style="width: 125rem !important">
  <div class="card-img-overlay">
  </div>
</div>--->





 
<!--<div class="card bg-dark text-white">
  <img src="picture/download.jpg"  class="card-img" alt="..." style="width: 122rem !important">
  <div class="card-img-overlay">
  </div>
</div>-->


<!---<div class="row">
			<div class="col-md-12">
			  <div class="card">
				<div class="card-body">
				  <h5 class="card-title">Bar Chart</h5>
				  <div class="flot-chart">
					<div class="flot-chart-content" id="flot-line-chart"></div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>--->


		 
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$home_php->Page_Terminate();
?>
