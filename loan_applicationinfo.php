<?php

// Global variable for table object
$loan_application = NULL;

//
// Table class for loan_application
//
class cloan_application extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $code;
	var $date_initiated;
	var $refernce_id;
	var $employee_name;
	var $address;
	var $mobile;
	var $department;
	var $pension;
	var $loan_amount;
	var $amount_inwords;
	var $purpose;
	var $repayment_period;
	var $salary_permonth;
	var $previous_loan;
	var $date_collected;
	var $date_liquidated;
	var $balance_remaining;
	var $applicant_date;
	var $applicant_passport;
	var $guarantor_name;
	var $guarantor_address;
	var $guarantor_mobile;
	var $guarantor_department;
	var $account_no;
	var $bank_name;
	var $employers_name;
	var $employers_address;
	var $employers_mobile;
	var $guarantor_date;
	var $guarantor_passport;
	var $status;
	var $initiator_action;
	var $initiator_comment;
	var $recommended_date;
	var $document_checklist;
	var $recommender_action;
	var $recommender_comment;
	var $recommended_by;
	var $application_status;
	var $approved_amount;
	var $duration_approved;
	var $approval_date;
	var $approval_action;
	var $approval_comment;
	var $approved_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'loan_application';
		$this->TableName = 'loan_application';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`loan_application`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// code
		$this->code = new cField('loan_application', 'loan_application', 'x_code', 'code', '`code`', '`code`', 3, -1, FALSE, '`code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->code->Sortable = TRUE; // Allow sort
		$this->code->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['code'] = &$this->code;

		// date_initiated
		$this->date_initiated = new cField('loan_application', 'loan_application', 'x_date_initiated', 'date_initiated', '`date_initiated`', ew_CastDateFieldForLike('`date_initiated`', 0, "DB"), 135, 0, FALSE, '`date_initiated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_initiated->Sortable = TRUE; // Allow sort
		$this->date_initiated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_initiated'] = &$this->date_initiated;

		// refernce_id
		$this->refernce_id = new cField('loan_application', 'loan_application', 'x_refernce_id', 'refernce_id', '`refernce_id`', '`refernce_id`', 200, -1, FALSE, '`refernce_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->refernce_id->Sortable = TRUE; // Allow sort
		$this->fields['refernce_id'] = &$this->refernce_id;

		// employee_name
		$this->employee_name = new cField('loan_application', 'loan_application', 'x_employee_name', 'employee_name', '`employee_name`', '`employee_name`', 200, -1, FALSE, '`employee_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->employee_name->Sortable = TRUE; // Allow sort
		$this->employee_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->employee_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['employee_name'] = &$this->employee_name;

		// address
		$this->address = new cField('loan_application', 'loan_application', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// mobile
		$this->mobile = new cField('loan_application', 'loan_application', 'x_mobile', 'mobile', '`mobile`', '`mobile`', 200, -1, FALSE, '`mobile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mobile->Sortable = TRUE; // Allow sort
		$this->fields['mobile'] = &$this->mobile;

		// department
		$this->department = new cField('loan_application', 'loan_application', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->department->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->department->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['department'] = &$this->department;

		// pension
		$this->pension = new cField('loan_application', 'loan_application', 'x_pension', 'pension', '`pension`', '`pension`', 200, -1, FALSE, '`pension`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pension->Sortable = TRUE; // Allow sort
		$this->fields['pension'] = &$this->pension;

		// loan_amount
		$this->loan_amount = new cField('loan_application', 'loan_application', 'x_loan_amount', 'loan_amount', '`loan_amount`', '`loan_amount`', 131, -1, FALSE, '`loan_amount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->loan_amount->Sortable = TRUE; // Allow sort
		$this->loan_amount->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['loan_amount'] = &$this->loan_amount;

		// amount_inwords
		$this->amount_inwords = new cField('loan_application', 'loan_application', 'x_amount_inwords', 'amount_inwords', '`amount_inwords`', '`amount_inwords`', 200, -1, FALSE, '`amount_inwords`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->amount_inwords->Sortable = TRUE; // Allow sort
		$this->fields['amount_inwords'] = &$this->amount_inwords;

		// purpose
		$this->purpose = new cField('loan_application', 'loan_application', 'x_purpose', 'purpose', '`purpose`', '`purpose`', 201, -1, FALSE, '`purpose`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->purpose->Sortable = TRUE; // Allow sort
		$this->fields['purpose'] = &$this->purpose;

		// repayment_period
		$this->repayment_period = new cField('loan_application', 'loan_application', 'x_repayment_period', 'repayment_period', '`repayment_period`', '`repayment_period`', 200, -1, FALSE, '`repayment_period`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->repayment_period->Sortable = TRUE; // Allow sort
		$this->repayment_period->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->repayment_period->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['repayment_period'] = &$this->repayment_period;

		// salary_permonth
		$this->salary_permonth = new cField('loan_application', 'loan_application', 'x_salary_permonth', 'salary_permonth', '`salary_permonth`', '`salary_permonth`', 131, -1, FALSE, '`salary_permonth`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->salary_permonth->Sortable = TRUE; // Allow sort
		$this->salary_permonth->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['salary_permonth'] = &$this->salary_permonth;

		// previous_loan
		$this->previous_loan = new cField('loan_application', 'loan_application', 'x_previous_loan', 'previous_loan', '`previous_loan`', '`previous_loan`', 131, -1, FALSE, '`previous_loan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->previous_loan->Sortable = TRUE; // Allow sort
		$this->previous_loan->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['previous_loan'] = &$this->previous_loan;

		// date_collected
		$this->date_collected = new cField('loan_application', 'loan_application', 'x_date_collected', 'date_collected', '`date_collected`', ew_CastDateFieldForLike('`date_collected`', 0, "DB"), 135, 0, FALSE, '`date_collected`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_collected->Sortable = TRUE; // Allow sort
		$this->date_collected->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_collected'] = &$this->date_collected;

		// date_liquidated
		$this->date_liquidated = new cField('loan_application', 'loan_application', 'x_date_liquidated', 'date_liquidated', '`date_liquidated`', ew_CastDateFieldForLike('`date_liquidated`', 0, "DB"), 135, 0, FALSE, '`date_liquidated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_liquidated->Sortable = TRUE; // Allow sort
		$this->date_liquidated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_liquidated'] = &$this->date_liquidated;

		// balance_remaining
		$this->balance_remaining = new cField('loan_application', 'loan_application', 'x_balance_remaining', 'balance_remaining', '`balance_remaining`', '`balance_remaining`', 131, -1, FALSE, '`balance_remaining`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->balance_remaining->Sortable = TRUE; // Allow sort
		$this->balance_remaining->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['balance_remaining'] = &$this->balance_remaining;

		// applicant_date
		$this->applicant_date = new cField('loan_application', 'loan_application', 'x_applicant_date', 'applicant_date', '`applicant_date`', ew_CastDateFieldForLike('`applicant_date`', 17, "DB"), 135, 17, FALSE, '`applicant_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->applicant_date->Sortable = TRUE; // Allow sort
		$this->applicant_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['applicant_date'] = &$this->applicant_date;

		// applicant_passport
		$this->applicant_passport = new cField('loan_application', 'loan_application', 'x_applicant_passport', 'applicant_passport', '`applicant_passport`', '`applicant_passport`', 201, -1, TRUE, '`applicant_passport`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->applicant_passport->Sortable = TRUE; // Allow sort
		$this->fields['applicant_passport'] = &$this->applicant_passport;

		// guarantor_name
		$this->guarantor_name = new cField('loan_application', 'loan_application', 'x_guarantor_name', 'guarantor_name', '`guarantor_name`', '`guarantor_name`', 200, -1, FALSE, '`guarantor_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->guarantor_name->Sortable = TRUE; // Allow sort
		$this->fields['guarantor_name'] = &$this->guarantor_name;

		// guarantor_address
		$this->guarantor_address = new cField('loan_application', 'loan_application', 'x_guarantor_address', 'guarantor_address', '`guarantor_address`', '`guarantor_address`', 200, -1, FALSE, '`guarantor_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->guarantor_address->Sortable = TRUE; // Allow sort
		$this->fields['guarantor_address'] = &$this->guarantor_address;

		// guarantor_mobile
		$this->guarantor_mobile = new cField('loan_application', 'loan_application', 'x_guarantor_mobile', 'guarantor_mobile', '`guarantor_mobile`', '`guarantor_mobile`', 200, -1, FALSE, '`guarantor_mobile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->guarantor_mobile->Sortable = TRUE; // Allow sort
		$this->fields['guarantor_mobile'] = &$this->guarantor_mobile;

		// guarantor_department
		$this->guarantor_department = new cField('loan_application', 'loan_application', 'x_guarantor_department', 'guarantor_department', '`guarantor_department`', '`guarantor_department`', 3, -1, FALSE, '`guarantor_department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->guarantor_department->Sortable = TRUE; // Allow sort
		$this->guarantor_department->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->guarantor_department->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->guarantor_department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['guarantor_department'] = &$this->guarantor_department;

		// account_no
		$this->account_no = new cField('loan_application', 'loan_application', 'x_account_no', 'account_no', '`account_no`', '`account_no`', 200, -1, FALSE, '`account_no`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->account_no->Sortable = TRUE; // Allow sort
		$this->fields['account_no'] = &$this->account_no;

		// bank_name
		$this->bank_name = new cField('loan_application', 'loan_application', 'x_bank_name', 'bank_name', '`bank_name`', '`bank_name`', 3, -1, FALSE, '`bank_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->bank_name->Sortable = TRUE; // Allow sort
		$this->bank_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->bank_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->bank_name->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['bank_name'] = &$this->bank_name;

		// employers_name
		$this->employers_name = new cField('loan_application', 'loan_application', 'x_employers_name', 'employers_name', '`employers_name`', '`employers_name`', 200, -1, FALSE, '`employers_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->employers_name->Sortable = TRUE; // Allow sort
		$this->fields['employers_name'] = &$this->employers_name;

		// employers_address
		$this->employers_address = new cField('loan_application', 'loan_application', 'x_employers_address', 'employers_address', '`employers_address`', '`employers_address`', 200, -1, FALSE, '`employers_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->employers_address->Sortable = TRUE; // Allow sort
		$this->fields['employers_address'] = &$this->employers_address;

		// employers_mobile
		$this->employers_mobile = new cField('loan_application', 'loan_application', 'x_employers_mobile', 'employers_mobile', '`employers_mobile`', '`employers_mobile`', 200, -1, FALSE, '`employers_mobile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->employers_mobile->Sortable = TRUE; // Allow sort
		$this->fields['employers_mobile'] = &$this->employers_mobile;

		// guarantor_date
		$this->guarantor_date = new cField('loan_application', 'loan_application', 'x_guarantor_date', 'guarantor_date', '`guarantor_date`', ew_CastDateFieldForLike('`guarantor_date`', 17, "DB"), 135, 17, FALSE, '`guarantor_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->guarantor_date->Sortable = TRUE; // Allow sort
		$this->guarantor_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['guarantor_date'] = &$this->guarantor_date;

		// guarantor_passport
		$this->guarantor_passport = new cField('loan_application', 'loan_application', 'x_guarantor_passport', 'guarantor_passport', '`guarantor_passport`', '`guarantor_passport`', 201, -1, TRUE, '`guarantor_passport`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->guarantor_passport->Sortable = TRUE; // Allow sort
		$this->fields['guarantor_passport'] = &$this->guarantor_passport;

		// status
		$this->status = new cField('loan_application', 'loan_application', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// initiator_action
		$this->initiator_action = new cField('loan_application', 'loan_application', 'x_initiator_action', 'initiator_action', '`initiator_action`', '`initiator_action`', 3, -1, FALSE, '`initiator_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->initiator_action->Sortable = TRUE; // Allow sort
		$this->initiator_action->OptionCount = 2;
		$this->initiator_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['initiator_action'] = &$this->initiator_action;

		// initiator_comment
		$this->initiator_comment = new cField('loan_application', 'loan_application', 'x_initiator_comment', 'initiator_comment', '`initiator_comment`', '`initiator_comment`', 200, -1, FALSE, '`initiator_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->initiator_comment->Sortable = TRUE; // Allow sort
		$this->fields['initiator_comment'] = &$this->initiator_comment;

		// recommended_date
		$this->recommended_date = new cField('loan_application', 'loan_application', 'x_recommended_date', 'recommended_date', '`recommended_date`', ew_CastDateFieldForLike('`recommended_date`', 14, "DB"), 135, 14, FALSE, '`recommended_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->recommended_date->Sortable = TRUE; // Allow sort
		$this->recommended_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['recommended_date'] = &$this->recommended_date;

		// document_checklist
		$this->document_checklist = new cField('loan_application', 'loan_application', 'x_document_checklist', 'document_checklist', '`document_checklist`', '`document_checklist`', 200, -1, FALSE, '`document_checklist`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->document_checklist->Sortable = TRUE; // Allow sort
		$this->fields['document_checklist'] = &$this->document_checklist;

		// recommender_action
		$this->recommender_action = new cField('loan_application', 'loan_application', 'x_recommender_action', 'recommender_action', '`recommender_action`', '`recommender_action`', 3, -1, FALSE, '`recommender_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->recommender_action->Sortable = TRUE; // Allow sort
		$this->recommender_action->OptionCount = 2;
		$this->fields['recommender_action'] = &$this->recommender_action;

		// recommender_comment
		$this->recommender_comment = new cField('loan_application', 'loan_application', 'x_recommender_comment', 'recommender_comment', '`recommender_comment`', '`recommender_comment`', 200, -1, FALSE, '`recommender_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->recommender_comment->Sortable = TRUE; // Allow sort
		$this->fields['recommender_comment'] = &$this->recommender_comment;

		// recommended_by
		$this->recommended_by = new cField('loan_application', 'loan_application', 'x_recommended_by', 'recommended_by', '`recommended_by`', '`recommended_by`', 3, -1, FALSE, '`recommended_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->recommended_by->Sortable = TRUE; // Allow sort
		$this->fields['recommended_by'] = &$this->recommended_by;

		// application_status
		$this->application_status = new cField('loan_application', 'loan_application', 'x_application_status', 'application_status', '`application_status`', '`application_status`', 200, -1, FALSE, '`application_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->application_status->Sortable = TRUE; // Allow sort
		$this->application_status->OptionCount = 2;
		$this->fields['application_status'] = &$this->application_status;

		// approved_amount
		$this->approved_amount = new cField('loan_application', 'loan_application', 'x_approved_amount', 'approved_amount', '`approved_amount`', '`approved_amount`', 131, -1, FALSE, '`approved_amount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_amount->Sortable = TRUE; // Allow sort
		$this->approved_amount->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['approved_amount'] = &$this->approved_amount;

		// duration_approved
		$this->duration_approved = new cField('loan_application', 'loan_application', 'x_duration_approved', 'duration_approved', '`duration_approved`', ew_CastDateFieldForLike('`duration_approved`', 0, "DB"), 133, 0, FALSE, '`duration_approved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->duration_approved->Sortable = TRUE; // Allow sort
		$this->duration_approved->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->duration_approved->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->duration_approved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['duration_approved'] = &$this->duration_approved;

		// approval_date
		$this->approval_date = new cField('loan_application', 'loan_application', 'x_approval_date', 'approval_date', '`approval_date`', ew_CastDateFieldForLike('`approval_date`', 17, "DB"), 135, 17, FALSE, '`approval_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approval_date->Sortable = TRUE; // Allow sort
		$this->approval_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['approval_date'] = &$this->approval_date;

		// approval_action
		$this->approval_action = new cField('loan_application', 'loan_application', 'x_approval_action', 'approval_action', '`approval_action`', '`approval_action`', 3, -1, FALSE, '`approval_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approval_action->Sortable = TRUE; // Allow sort
		$this->approval_action->OptionCount = 2;
		$this->approval_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approval_action'] = &$this->approval_action;

		// approval_comment
		$this->approval_comment = new cField('loan_application', 'loan_application', 'x_approval_comment', 'approval_comment', '`approval_comment`', '`approval_comment`', 200, -1, FALSE, '`approval_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approval_comment->Sortable = TRUE; // Allow sort
		$this->fields['approval_comment'] = &$this->approval_comment;

		// approved_by
		$this->approved_by = new cField('loan_application', 'loan_application', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->approved_by->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->approved_by->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->approved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approved_by'] = &$this->approved_by;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`loan_application`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->code->setDbValue($conn->Insert_ID());
			$rs['code'] = $this->code->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'code';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsold, $rsaudit);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('code', $rs))
				ew_AddFilter($where, ew_QuotedName('code', $this->DBID) . '=' . ew_QuotedValue($rs['code'], $this->code->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`code` = @code@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->code->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->code->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@code@", ew_AdjustSql($this->code->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "loan_applicationlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "loan_applicationview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "loan_applicationedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "loan_applicationadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "loan_applicationlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("loan_applicationview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("loan_applicationview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "loan_applicationadd.php?" . $this->UrlParm($parm);
		else
			$url = "loan_applicationadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("loan_applicationedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("loan_applicationadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("loan_applicationdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "code:" . ew_VarToJson($this->code->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->code->CurrentValue)) {
			$sUrl .= "code=" . urlencode($this->code->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["code"]))
				$arKeys[] = $_POST["code"];
			elseif (isset($_GET["code"]))
				$arKeys[] = $_GET["code"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->code->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->code->setDbValue($rs->fields('code'));
		$this->date_initiated->setDbValue($rs->fields('date_initiated'));
		$this->refernce_id->setDbValue($rs->fields('refernce_id'));
		$this->employee_name->setDbValue($rs->fields('employee_name'));
		$this->address->setDbValue($rs->fields('address'));
		$this->mobile->setDbValue($rs->fields('mobile'));
		$this->department->setDbValue($rs->fields('department'));
		$this->pension->setDbValue($rs->fields('pension'));
		$this->loan_amount->setDbValue($rs->fields('loan_amount'));
		$this->amount_inwords->setDbValue($rs->fields('amount_inwords'));
		$this->purpose->setDbValue($rs->fields('purpose'));
		$this->repayment_period->setDbValue($rs->fields('repayment_period'));
		$this->salary_permonth->setDbValue($rs->fields('salary_permonth'));
		$this->previous_loan->setDbValue($rs->fields('previous_loan'));
		$this->date_collected->setDbValue($rs->fields('date_collected'));
		$this->date_liquidated->setDbValue($rs->fields('date_liquidated'));
		$this->balance_remaining->setDbValue($rs->fields('balance_remaining'));
		$this->applicant_date->setDbValue($rs->fields('applicant_date'));
		$this->applicant_passport->Upload->DbValue = $rs->fields('applicant_passport');
		$this->guarantor_name->setDbValue($rs->fields('guarantor_name'));
		$this->guarantor_address->setDbValue($rs->fields('guarantor_address'));
		$this->guarantor_mobile->setDbValue($rs->fields('guarantor_mobile'));
		$this->guarantor_department->setDbValue($rs->fields('guarantor_department'));
		$this->account_no->setDbValue($rs->fields('account_no'));
		$this->bank_name->setDbValue($rs->fields('bank_name'));
		$this->employers_name->setDbValue($rs->fields('employers_name'));
		$this->employers_address->setDbValue($rs->fields('employers_address'));
		$this->employers_mobile->setDbValue($rs->fields('employers_mobile'));
		$this->guarantor_date->setDbValue($rs->fields('guarantor_date'));
		$this->guarantor_passport->Upload->DbValue = $rs->fields('guarantor_passport');
		$this->status->setDbValue($rs->fields('status'));
		$this->initiator_action->setDbValue($rs->fields('initiator_action'));
		$this->initiator_comment->setDbValue($rs->fields('initiator_comment'));
		$this->recommended_date->setDbValue($rs->fields('recommended_date'));
		$this->document_checklist->setDbValue($rs->fields('document_checklist'));
		$this->recommender_action->setDbValue($rs->fields('recommender_action'));
		$this->recommender_comment->setDbValue($rs->fields('recommender_comment'));
		$this->recommended_by->setDbValue($rs->fields('recommended_by'));
		$this->application_status->setDbValue($rs->fields('application_status'));
		$this->approved_amount->setDbValue($rs->fields('approved_amount'));
		$this->duration_approved->setDbValue($rs->fields('duration_approved'));
		$this->approval_date->setDbValue($rs->fields('approval_date'));
		$this->approval_action->setDbValue($rs->fields('approval_action'));
		$this->approval_comment->setDbValue($rs->fields('approval_comment'));
		$this->approved_by->setDbValue($rs->fields('approved_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// code
		// date_initiated
		// refernce_id
		// employee_name
		// address
		// mobile
		// department
		// pension
		// loan_amount
		// amount_inwords
		// purpose
		// repayment_period
		// salary_permonth
		// previous_loan
		// date_collected
		// date_liquidated
		// balance_remaining
		// applicant_date
		// applicant_passport
		// guarantor_name
		// guarantor_address
		// guarantor_mobile
		// guarantor_department
		// account_no
		// bank_name
		// employers_name
		// employers_address
		// employers_mobile
		// guarantor_date
		// guarantor_passport
		// status
		// initiator_action
		// initiator_comment
		// recommended_date
		// document_checklist
		// recommender_action
		// recommender_comment
		// recommended_by
		// application_status
		// approved_amount
		// duration_approved
		// approval_date
		// approval_action
		// approval_comment
		// approved_by
		// code

		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// refernce_id
		$this->refernce_id->ViewValue = $this->refernce_id->CurrentValue;
		$this->refernce_id->ViewCustomAttributes = "";

		// employee_name
		if (strval($this->employee_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->employee_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->employee_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->employee_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->employee_name->ViewValue = $this->employee_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->employee_name->ViewValue = $this->employee_name->CurrentValue;
			}
		} else {
			$this->employee_name->ViewValue = NULL;
		}
		$this->employee_name->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// mobile
		$this->mobile->ViewValue = $this->mobile->CurrentValue;
		$this->mobile->ViewCustomAttributes = "";

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

		// pension
		$this->pension->ViewValue = $this->pension->CurrentValue;
		$this->pension->ViewCustomAttributes = "";

		// loan_amount
		$this->loan_amount->ViewValue = $this->loan_amount->CurrentValue;
		$this->loan_amount->ViewValue = ew_FormatNumber($this->loan_amount->ViewValue, 0, -2, -2, -2);
		$this->loan_amount->ViewCustomAttributes = "";

		// amount_inwords
		$this->amount_inwords->ViewValue = $this->amount_inwords->CurrentValue;
		$this->amount_inwords->ViewCustomAttributes = "";

		// purpose
		$this->purpose->ViewValue = $this->purpose->CurrentValue;
		$this->purpose->ViewCustomAttributes = "";

		// repayment_period
		if (strval($this->repayment_period->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->repayment_period->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
		$sWhereWrk = "";
		$this->repayment_period->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->repayment_period, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->repayment_period->ViewValue = $this->repayment_period->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->repayment_period->ViewValue = $this->repayment_period->CurrentValue;
			}
		} else {
			$this->repayment_period->ViewValue = NULL;
		}
		$this->repayment_period->ViewCustomAttributes = "";

		// salary_permonth
		$this->salary_permonth->ViewValue = $this->salary_permonth->CurrentValue;
		$this->salary_permonth->ViewValue = ew_FormatNumber($this->salary_permonth->ViewValue, 0, -2, -2, -2);
		$this->salary_permonth->ViewCustomAttributes = "";

		// previous_loan
		$this->previous_loan->ViewValue = $this->previous_loan->CurrentValue;
		$this->previous_loan->ViewValue = ew_FormatNumber($this->previous_loan->ViewValue, 0, -2, -2, -2);
		$this->previous_loan->ViewCustomAttributes = "";

		// date_collected
		$this->date_collected->ViewValue = $this->date_collected->CurrentValue;
		$this->date_collected->ViewValue = ew_FormatDateTime($this->date_collected->ViewValue, 0);
		$this->date_collected->ViewCustomAttributes = "";

		// date_liquidated
		$this->date_liquidated->ViewValue = $this->date_liquidated->CurrentValue;
		$this->date_liquidated->ViewValue = ew_FormatDateTime($this->date_liquidated->ViewValue, 0);
		$this->date_liquidated->ViewCustomAttributes = "";

		// balance_remaining
		$this->balance_remaining->ViewValue = $this->balance_remaining->CurrentValue;
		$this->balance_remaining->ViewValue = ew_FormatNumber($this->balance_remaining->ViewValue, 0, -2, -2, -2);
		$this->balance_remaining->ViewCustomAttributes = "";

		// applicant_date
		$this->applicant_date->ViewValue = $this->applicant_date->CurrentValue;
		$this->applicant_date->ViewValue = ew_FormatDateTime($this->applicant_date->ViewValue, 17);
		$this->applicant_date->ViewCustomAttributes = "";

		// applicant_passport
		if (!ew_Empty($this->applicant_passport->Upload->DbValue)) {
			$this->applicant_passport->ViewValue = $this->applicant_passport->Upload->DbValue;
		} else {
			$this->applicant_passport->ViewValue = "";
		}
		$this->applicant_passport->ViewCustomAttributes = "";

		// guarantor_name
		$this->guarantor_name->ViewValue = $this->guarantor_name->CurrentValue;
		$this->guarantor_name->ViewCustomAttributes = "";

		// guarantor_address
		$this->guarantor_address->ViewValue = $this->guarantor_address->CurrentValue;
		$this->guarantor_address->ViewCustomAttributes = "";

		// guarantor_mobile
		$this->guarantor_mobile->ViewValue = $this->guarantor_mobile->CurrentValue;
		$this->guarantor_mobile->ViewCustomAttributes = "";

		// guarantor_department
		if (strval($this->guarantor_department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->guarantor_department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->guarantor_department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->guarantor_department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->guarantor_department->ViewValue = $this->guarantor_department->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->guarantor_department->ViewValue = $this->guarantor_department->CurrentValue;
			}
		} else {
			$this->guarantor_department->ViewValue = NULL;
		}
		$this->guarantor_department->ViewCustomAttributes = "";

		// account_no
		$this->account_no->ViewValue = $this->account_no->CurrentValue;
		$this->account_no->ViewCustomAttributes = "";

		// bank_name
		if (strval($this->bank_name->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->bank_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `banks_list`";
		$sWhereWrk = "";
		$this->bank_name->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->bank_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->bank_name->ViewValue = $this->bank_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->bank_name->ViewValue = $this->bank_name->CurrentValue;
			}
		} else {
			$this->bank_name->ViewValue = NULL;
		}
		$this->bank_name->ViewCustomAttributes = "";

		// employers_name
		$this->employers_name->ViewValue = $this->employers_name->CurrentValue;
		$this->employers_name->ViewCustomAttributes = "";

		// employers_address
		$this->employers_address->ViewValue = $this->employers_address->CurrentValue;
		$this->employers_address->ViewCustomAttributes = "";

		// employers_mobile
		$this->employers_mobile->ViewValue = $this->employers_mobile->CurrentValue;
		$this->employers_mobile->ViewCustomAttributes = "";

		// guarantor_date
		$this->guarantor_date->ViewValue = $this->guarantor_date->CurrentValue;
		$this->guarantor_date->ViewValue = ew_FormatDateTime($this->guarantor_date->ViewValue, 17);
		$this->guarantor_date->ViewCustomAttributes = "";

		// guarantor_passport
		if (!ew_Empty($this->guarantor_passport->Upload->DbValue)) {
			$this->guarantor_passport->ViewValue = $this->guarantor_passport->Upload->DbValue;
		} else {
			$this->guarantor_passport->ViewValue = "";
		}
		$this->guarantor_passport->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `loan_status`";
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

		// recommended_date
		$this->recommended_date->ViewValue = $this->recommended_date->CurrentValue;
		$this->recommended_date->ViewValue = ew_FormatDateTime($this->recommended_date->ViewValue, 14);
		$this->recommended_date->ViewCustomAttributes = "";

		// document_checklist
		if (strval($this->document_checklist->CurrentValue) <> "") {
			$arwrk = explode(",", $this->document_checklist->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `discription` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `document_checklist`";
		$sWhereWrk = "";
		$this->document_checklist->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->document_checklist, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->document_checklist->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->document_checklist->ViewValue .= $this->document_checklist->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->document_checklist->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->document_checklist->ViewValue = $this->document_checklist->CurrentValue;
			}
		} else {
			$this->document_checklist->ViewValue = NULL;
		}
		$this->document_checklist->ViewCustomAttributes = "";

		// recommender_action
		if (strval($this->recommender_action->CurrentValue) <> "") {
			$this->recommender_action->ViewValue = $this->recommender_action->OptionCaption($this->recommender_action->CurrentValue);
		} else {
			$this->recommender_action->ViewValue = NULL;
		}
		$this->recommender_action->ViewCustomAttributes = "";

		// recommender_comment
		$this->recommender_comment->ViewValue = $this->recommender_comment->CurrentValue;
		$this->recommender_comment->ViewCustomAttributes = "";

		// recommended_by
		$this->recommended_by->ViewValue = $this->recommended_by->CurrentValue;
		if (strval($this->recommended_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recommended_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recommended_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recommended_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->recommended_by->ViewValue = $this->recommended_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recommended_by->ViewValue = $this->recommended_by->CurrentValue;
			}
		} else {
			$this->recommended_by->ViewValue = NULL;
		}
		$this->recommended_by->ViewCustomAttributes = "";

		// application_status
		if (strval($this->application_status->CurrentValue) <> "") {
			$this->application_status->ViewValue = $this->application_status->OptionCaption($this->application_status->CurrentValue);
		} else {
			$this->application_status->ViewValue = NULL;
		}
		$this->application_status->ViewCustomAttributes = "";

		// approved_amount
		$this->approved_amount->ViewValue = $this->approved_amount->CurrentValue;
		$this->approved_amount->ViewValue = ew_FormatNumber($this->approved_amount->ViewValue, 0, -2, -2, -2);
		$this->approved_amount->ViewCustomAttributes = "";

		// duration_approved
		if (strval($this->duration_approved->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->duration_approved->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `duration_months`";
		$sWhereWrk = "";
		$this->duration_approved->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->duration_approved, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->duration_approved->ViewValue = $this->duration_approved->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->duration_approved->ViewValue = $this->duration_approved->CurrentValue;
			}
		} else {
			$this->duration_approved->ViewValue = NULL;
		}
		$this->duration_approved->ViewValue = ew_FormatDateTime($this->duration_approved->ViewValue, 0);
		$this->duration_approved->ViewCustomAttributes = "";

		// approval_date
		$this->approval_date->ViewValue = $this->approval_date->CurrentValue;
		$this->approval_date->ViewValue = ew_FormatDateTime($this->approval_date->ViewValue, 17);
		$this->approval_date->ViewCustomAttributes = "";

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

		// approved_by
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

		// code
		$this->code->LinkCustomAttributes = "";
		$this->code->HrefValue = "";
		$this->code->TooltipValue = "";

		// date_initiated
		$this->date_initiated->LinkCustomAttributes = "";
		$this->date_initiated->HrefValue = "";
		$this->date_initiated->TooltipValue = "";

		// refernce_id
		$this->refernce_id->LinkCustomAttributes = "";
		$this->refernce_id->HrefValue = "";
		$this->refernce_id->TooltipValue = "";

		// employee_name
		$this->employee_name->LinkCustomAttributes = "";
		$this->employee_name->HrefValue = "";
		$this->employee_name->TooltipValue = "";

		// address
		$this->address->LinkCustomAttributes = "";
		$this->address->HrefValue = "";
		$this->address->TooltipValue = "";

		// mobile
		$this->mobile->LinkCustomAttributes = "";
		$this->mobile->HrefValue = "";
		$this->mobile->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// pension
		$this->pension->LinkCustomAttributes = "";
		$this->pension->HrefValue = "";
		$this->pension->TooltipValue = "";

		// loan_amount
		$this->loan_amount->LinkCustomAttributes = "";
		$this->loan_amount->HrefValue = "";
		$this->loan_amount->TooltipValue = "";

		// amount_inwords
		$this->amount_inwords->LinkCustomAttributes = "";
		$this->amount_inwords->HrefValue = "";
		$this->amount_inwords->TooltipValue = "";

		// purpose
		$this->purpose->LinkCustomAttributes = "";
		$this->purpose->HrefValue = "";
		$this->purpose->TooltipValue = "";

		// repayment_period
		$this->repayment_period->LinkCustomAttributes = "";
		$this->repayment_period->HrefValue = "";
		$this->repayment_period->TooltipValue = "";

		// salary_permonth
		$this->salary_permonth->LinkCustomAttributes = "";
		$this->salary_permonth->HrefValue = "";
		$this->salary_permonth->TooltipValue = "";

		// previous_loan
		$this->previous_loan->LinkCustomAttributes = "";
		$this->previous_loan->HrefValue = "";
		$this->previous_loan->TooltipValue = "";

		// date_collected
		$this->date_collected->LinkCustomAttributes = "";
		$this->date_collected->HrefValue = "";
		$this->date_collected->TooltipValue = "";

		// date_liquidated
		$this->date_liquidated->LinkCustomAttributes = "";
		$this->date_liquidated->HrefValue = "";
		$this->date_liquidated->TooltipValue = "";

		// balance_remaining
		$this->balance_remaining->LinkCustomAttributes = "";
		$this->balance_remaining->HrefValue = "";
		$this->balance_remaining->TooltipValue = "";

		// applicant_date
		$this->applicant_date->LinkCustomAttributes = "";
		$this->applicant_date->HrefValue = "";
		$this->applicant_date->TooltipValue = "";

		// applicant_passport
		$this->applicant_passport->LinkCustomAttributes = "";
		$this->applicant_passport->HrefValue = "";
		$this->applicant_passport->HrefValue2 = $this->applicant_passport->UploadPath . $this->applicant_passport->Upload->DbValue;
		$this->applicant_passport->TooltipValue = "";

		// guarantor_name
		$this->guarantor_name->LinkCustomAttributes = "";
		$this->guarantor_name->HrefValue = "";
		$this->guarantor_name->TooltipValue = "";

		// guarantor_address
		$this->guarantor_address->LinkCustomAttributes = "";
		$this->guarantor_address->HrefValue = "";
		$this->guarantor_address->TooltipValue = "";

		// guarantor_mobile
		$this->guarantor_mobile->LinkCustomAttributes = "";
		$this->guarantor_mobile->HrefValue = "";
		$this->guarantor_mobile->TooltipValue = "";

		// guarantor_department
		$this->guarantor_department->LinkCustomAttributes = "";
		$this->guarantor_department->HrefValue = "";
		$this->guarantor_department->TooltipValue = "";

		// account_no
		$this->account_no->LinkCustomAttributes = "";
		$this->account_no->HrefValue = "";
		$this->account_no->TooltipValue = "";

		// bank_name
		$this->bank_name->LinkCustomAttributes = "";
		$this->bank_name->HrefValue = "";
		$this->bank_name->TooltipValue = "";

		// employers_name
		$this->employers_name->LinkCustomAttributes = "";
		$this->employers_name->HrefValue = "";
		$this->employers_name->TooltipValue = "";

		// employers_address
		$this->employers_address->LinkCustomAttributes = "";
		$this->employers_address->HrefValue = "";
		$this->employers_address->TooltipValue = "";

		// employers_mobile
		$this->employers_mobile->LinkCustomAttributes = "";
		$this->employers_mobile->HrefValue = "";
		$this->employers_mobile->TooltipValue = "";

		// guarantor_date
		$this->guarantor_date->LinkCustomAttributes = "";
		$this->guarantor_date->HrefValue = "";
		$this->guarantor_date->TooltipValue = "";

		// guarantor_passport
		$this->guarantor_passport->LinkCustomAttributes = "";
		$this->guarantor_passport->HrefValue = "";
		$this->guarantor_passport->HrefValue2 = $this->guarantor_passport->UploadPath . $this->guarantor_passport->Upload->DbValue;
		$this->guarantor_passport->TooltipValue = "";

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

		// recommended_date
		$this->recommended_date->LinkCustomAttributes = "";
		$this->recommended_date->HrefValue = "";
		$this->recommended_date->TooltipValue = "";

		// document_checklist
		$this->document_checklist->LinkCustomAttributes = "";
		$this->document_checklist->HrefValue = "";
		$this->document_checklist->TooltipValue = "";

		// recommender_action
		$this->recommender_action->LinkCustomAttributes = "";
		$this->recommender_action->HrefValue = "";
		$this->recommender_action->TooltipValue = "";

		// recommender_comment
		$this->recommender_comment->LinkCustomAttributes = "";
		$this->recommender_comment->HrefValue = "";
		$this->recommender_comment->TooltipValue = "";

		// recommended_by
		$this->recommended_by->LinkCustomAttributes = "";
		$this->recommended_by->HrefValue = "";
		$this->recommended_by->TooltipValue = "";

		// application_status
		$this->application_status->LinkCustomAttributes = "";
		$this->application_status->HrefValue = "";
		$this->application_status->TooltipValue = "";

		// approved_amount
		$this->approved_amount->LinkCustomAttributes = "";
		$this->approved_amount->HrefValue = "";
		$this->approved_amount->TooltipValue = "";

		// duration_approved
		$this->duration_approved->LinkCustomAttributes = "";
		$this->duration_approved->HrefValue = "";
		$this->duration_approved->TooltipValue = "";

		// approval_date
		$this->approval_date->LinkCustomAttributes = "";
		$this->approval_date->HrefValue = "";
		$this->approval_date->TooltipValue = "";

		// approval_action
		$this->approval_action->LinkCustomAttributes = "";
		$this->approval_action->HrefValue = "";
		$this->approval_action->TooltipValue = "";

		// approval_comment
		$this->approval_comment->LinkCustomAttributes = "";
		$this->approval_comment->HrefValue = "";
		$this->approval_comment->TooltipValue = "";

		// approved_by
		$this->approved_by->LinkCustomAttributes = "";
		$this->approved_by->HrefValue = "";
		$this->approved_by->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// code
		$this->code->EditAttrs["class"] = "form-control";
		$this->code->EditCustomAttributes = "";
		$this->code->EditValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->EditAttrs["class"] = "form-control";
		$this->date_initiated->EditCustomAttributes = "";
		$this->date_initiated->EditValue = ew_FormatDateTime($this->date_initiated->CurrentValue, 8);
		$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

		// refernce_id
		$this->refernce_id->EditAttrs["class"] = "form-control";
		$this->refernce_id->EditCustomAttributes = "";
		$this->refernce_id->EditValue = $this->refernce_id->CurrentValue;
		$this->refernce_id->PlaceHolder = ew_RemoveHtml($this->refernce_id->FldCaption());

		// employee_name
		$this->employee_name->EditAttrs["class"] = "form-control";
		$this->employee_name->EditCustomAttributes = "";

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// mobile
		$this->mobile->EditAttrs["class"] = "form-control";
		$this->mobile->EditCustomAttributes = "";
		$this->mobile->EditValue = $this->mobile->CurrentValue;
		$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";

		// pension
		$this->pension->EditAttrs["class"] = "form-control";
		$this->pension->EditCustomAttributes = "";
		$this->pension->EditValue = $this->pension->CurrentValue;
		$this->pension->PlaceHolder = ew_RemoveHtml($this->pension->FldCaption());

		// loan_amount
		$this->loan_amount->EditAttrs["class"] = "form-control";
		$this->loan_amount->EditCustomAttributes = "";
		$this->loan_amount->EditValue = $this->loan_amount->CurrentValue;
		$this->loan_amount->PlaceHolder = ew_RemoveHtml($this->loan_amount->FldCaption());
		if (strval($this->loan_amount->EditValue) <> "" && is_numeric($this->loan_amount->EditValue)) $this->loan_amount->EditValue = ew_FormatNumber($this->loan_amount->EditValue, -2, -2, -2, -2);

		// amount_inwords
		$this->amount_inwords->EditAttrs["class"] = "form-control";
		$this->amount_inwords->EditCustomAttributes = "";
		$this->amount_inwords->EditValue = $this->amount_inwords->CurrentValue;
		$this->amount_inwords->PlaceHolder = ew_RemoveHtml($this->amount_inwords->FldCaption());

		// purpose
		$this->purpose->EditAttrs["class"] = "form-control";
		$this->purpose->EditCustomAttributes = "";
		$this->purpose->EditValue = $this->purpose->CurrentValue;
		$this->purpose->PlaceHolder = ew_RemoveHtml($this->purpose->FldCaption());

		// repayment_period
		$this->repayment_period->EditAttrs["class"] = "form-control";
		$this->repayment_period->EditCustomAttributes = "";

		// salary_permonth
		$this->salary_permonth->EditAttrs["class"] = "form-control";
		$this->salary_permonth->EditCustomAttributes = "";
		$this->salary_permonth->EditValue = $this->salary_permonth->CurrentValue;
		$this->salary_permonth->PlaceHolder = ew_RemoveHtml($this->salary_permonth->FldCaption());
		if (strval($this->salary_permonth->EditValue) <> "" && is_numeric($this->salary_permonth->EditValue)) $this->salary_permonth->EditValue = ew_FormatNumber($this->salary_permonth->EditValue, -2, -2, -2, -2);

		// previous_loan
		$this->previous_loan->EditAttrs["class"] = "form-control";
		$this->previous_loan->EditCustomAttributes = "";
		$this->previous_loan->EditValue = $this->previous_loan->CurrentValue;
		$this->previous_loan->PlaceHolder = ew_RemoveHtml($this->previous_loan->FldCaption());
		if (strval($this->previous_loan->EditValue) <> "" && is_numeric($this->previous_loan->EditValue)) $this->previous_loan->EditValue = ew_FormatNumber($this->previous_loan->EditValue, -2, -2, -2, -2);

		// date_collected
		$this->date_collected->EditAttrs["class"] = "form-control";
		$this->date_collected->EditCustomAttributes = "";
		$this->date_collected->EditValue = ew_FormatDateTime($this->date_collected->CurrentValue, 8);
		$this->date_collected->PlaceHolder = ew_RemoveHtml($this->date_collected->FldCaption());

		// date_liquidated
		$this->date_liquidated->EditAttrs["class"] = "form-control";
		$this->date_liquidated->EditCustomAttributes = "";
		$this->date_liquidated->EditValue = ew_FormatDateTime($this->date_liquidated->CurrentValue, 8);
		$this->date_liquidated->PlaceHolder = ew_RemoveHtml($this->date_liquidated->FldCaption());

		// balance_remaining
		$this->balance_remaining->EditAttrs["class"] = "form-control";
		$this->balance_remaining->EditCustomAttributes = "";
		$this->balance_remaining->EditValue = $this->balance_remaining->CurrentValue;
		$this->balance_remaining->PlaceHolder = ew_RemoveHtml($this->balance_remaining->FldCaption());
		if (strval($this->balance_remaining->EditValue) <> "" && is_numeric($this->balance_remaining->EditValue)) $this->balance_remaining->EditValue = ew_FormatNumber($this->balance_remaining->EditValue, -2, -2, -2, -2);

		// applicant_date
		$this->applicant_date->EditAttrs["class"] = "form-control";
		$this->applicant_date->EditCustomAttributes = "";
		$this->applicant_date->EditValue = ew_FormatDateTime($this->applicant_date->CurrentValue, 17);
		$this->applicant_date->PlaceHolder = ew_RemoveHtml($this->applicant_date->FldCaption());

		// applicant_passport
		$this->applicant_passport->EditAttrs["class"] = "form-control";
		$this->applicant_passport->EditCustomAttributes = "";
		if (!ew_Empty($this->applicant_passport->Upload->DbValue)) {
			$this->applicant_passport->EditValue = $this->applicant_passport->Upload->DbValue;
		} else {
			$this->applicant_passport->EditValue = "";
		}
		if (!ew_Empty($this->applicant_passport->CurrentValue))
				$this->applicant_passport->Upload->FileName = $this->applicant_passport->CurrentValue;

		// guarantor_name
		$this->guarantor_name->EditAttrs["class"] = "form-control";
		$this->guarantor_name->EditCustomAttributes = "";
		$this->guarantor_name->EditValue = $this->guarantor_name->CurrentValue;
		$this->guarantor_name->PlaceHolder = ew_RemoveHtml($this->guarantor_name->FldCaption());

		// guarantor_address
		$this->guarantor_address->EditAttrs["class"] = "form-control";
		$this->guarantor_address->EditCustomAttributes = "";
		$this->guarantor_address->EditValue = $this->guarantor_address->CurrentValue;
		$this->guarantor_address->PlaceHolder = ew_RemoveHtml($this->guarantor_address->FldCaption());

		// guarantor_mobile
		$this->guarantor_mobile->EditAttrs["class"] = "form-control";
		$this->guarantor_mobile->EditCustomAttributes = "";
		$this->guarantor_mobile->EditValue = $this->guarantor_mobile->CurrentValue;
		$this->guarantor_mobile->PlaceHolder = ew_RemoveHtml($this->guarantor_mobile->FldCaption());

		// guarantor_department
		$this->guarantor_department->EditAttrs["class"] = "form-control";
		$this->guarantor_department->EditCustomAttributes = "";

		// account_no
		$this->account_no->EditAttrs["class"] = "form-control";
		$this->account_no->EditCustomAttributes = "";
		$this->account_no->EditValue = $this->account_no->CurrentValue;
		$this->account_no->PlaceHolder = ew_RemoveHtml($this->account_no->FldCaption());

		// bank_name
		$this->bank_name->EditAttrs["class"] = "form-control";
		$this->bank_name->EditCustomAttributes = "";

		// employers_name
		$this->employers_name->EditAttrs["class"] = "form-control";
		$this->employers_name->EditCustomAttributes = "";
		$this->employers_name->EditValue = $this->employers_name->CurrentValue;
		$this->employers_name->PlaceHolder = ew_RemoveHtml($this->employers_name->FldCaption());

		// employers_address
		$this->employers_address->EditAttrs["class"] = "form-control";
		$this->employers_address->EditCustomAttributes = "";
		$this->employers_address->EditValue = $this->employers_address->CurrentValue;
		$this->employers_address->PlaceHolder = ew_RemoveHtml($this->employers_address->FldCaption());

		// employers_mobile
		$this->employers_mobile->EditAttrs["class"] = "form-control";
		$this->employers_mobile->EditCustomAttributes = "";
		$this->employers_mobile->EditValue = $this->employers_mobile->CurrentValue;
		$this->employers_mobile->PlaceHolder = ew_RemoveHtml($this->employers_mobile->FldCaption());

		// guarantor_date
		$this->guarantor_date->EditAttrs["class"] = "form-control";
		$this->guarantor_date->EditCustomAttributes = "";
		$this->guarantor_date->EditValue = ew_FormatDateTime($this->guarantor_date->CurrentValue, 17);
		$this->guarantor_date->PlaceHolder = ew_RemoveHtml($this->guarantor_date->FldCaption());

		// guarantor_passport
		$this->guarantor_passport->EditAttrs["class"] = "form-control";
		$this->guarantor_passport->EditCustomAttributes = "";
		if (!ew_Empty($this->guarantor_passport->Upload->DbValue)) {
			$this->guarantor_passport->EditValue = $this->guarantor_passport->Upload->DbValue;
		} else {
			$this->guarantor_passport->EditValue = "";
		}
		if (!ew_Empty($this->guarantor_passport->CurrentValue))
				$this->guarantor_passport->Upload->FileName = $this->guarantor_passport->CurrentValue;

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// initiator_action
		$this->initiator_action->EditCustomAttributes = "";
		$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

		// initiator_comment
		$this->initiator_comment->EditAttrs["class"] = "form-control";
		$this->initiator_comment->EditCustomAttributes = "";
		$this->initiator_comment->EditValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

		// recommended_date
		$this->recommended_date->EditAttrs["class"] = "form-control";
		$this->recommended_date->EditCustomAttributes = "";
		$this->recommended_date->EditValue = ew_FormatDateTime($this->recommended_date->CurrentValue, 14);
		$this->recommended_date->PlaceHolder = ew_RemoveHtml($this->recommended_date->FldCaption());

		// document_checklist
		$this->document_checklist->EditCustomAttributes = "";

		// recommender_action
		$this->recommender_action->EditCustomAttributes = "";
		$this->recommender_action->EditValue = $this->recommender_action->Options(FALSE);

		// recommender_comment
		$this->recommender_comment->EditAttrs["class"] = "form-control";
		$this->recommender_comment->EditCustomAttributes = "";
		$this->recommender_comment->EditValue = $this->recommender_comment->CurrentValue;
		$this->recommender_comment->PlaceHolder = ew_RemoveHtml($this->recommender_comment->FldCaption());

		// recommended_by
		$this->recommended_by->EditAttrs["class"] = "form-control";
		$this->recommended_by->EditCustomAttributes = "";
		$this->recommended_by->EditValue = $this->recommended_by->CurrentValue;
		$this->recommended_by->PlaceHolder = ew_RemoveHtml($this->recommended_by->FldCaption());

		// application_status
		$this->application_status->EditCustomAttributes = "";
		$this->application_status->EditValue = $this->application_status->Options(FALSE);

		// approved_amount
		$this->approved_amount->EditAttrs["class"] = "form-control";
		$this->approved_amount->EditCustomAttributes = "";
		$this->approved_amount->EditValue = $this->approved_amount->CurrentValue;
		$this->approved_amount->PlaceHolder = ew_RemoveHtml($this->approved_amount->FldCaption());
		if (strval($this->approved_amount->EditValue) <> "" && is_numeric($this->approved_amount->EditValue)) $this->approved_amount->EditValue = ew_FormatNumber($this->approved_amount->EditValue, -2, -2, -2, -2);

		// duration_approved
		$this->duration_approved->EditAttrs["class"] = "form-control";
		$this->duration_approved->EditCustomAttributes = "";

		// approval_date
		$this->approval_date->EditAttrs["class"] = "form-control";
		$this->approval_date->EditCustomAttributes = "";
		$this->approval_date->EditValue = ew_FormatDateTime($this->approval_date->CurrentValue, 17);
		$this->approval_date->PlaceHolder = ew_RemoveHtml($this->approval_date->FldCaption());

		// approval_action
		$this->approval_action->EditCustomAttributes = "";
		$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

		// approval_comment
		$this->approval_comment->EditAttrs["class"] = "form-control";
		$this->approval_comment->EditCustomAttributes = "";
		$this->approval_comment->EditValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

		// approved_by
		$this->approved_by->EditAttrs["class"] = "form-control";
		$this->approved_by->EditCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
					if ($this->date_initiated->Exportable) $Doc->ExportCaption($this->date_initiated);
					if ($this->refernce_id->Exportable) $Doc->ExportCaption($this->refernce_id);
					if ($this->employee_name->Exportable) $Doc->ExportCaption($this->employee_name);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->mobile->Exportable) $Doc->ExportCaption($this->mobile);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->pension->Exportable) $Doc->ExportCaption($this->pension);
					if ($this->loan_amount->Exportable) $Doc->ExportCaption($this->loan_amount);
					if ($this->amount_inwords->Exportable) $Doc->ExportCaption($this->amount_inwords);
					if ($this->purpose->Exportable) $Doc->ExportCaption($this->purpose);
					if ($this->repayment_period->Exportable) $Doc->ExportCaption($this->repayment_period);
					if ($this->salary_permonth->Exportable) $Doc->ExportCaption($this->salary_permonth);
					if ($this->previous_loan->Exportable) $Doc->ExportCaption($this->previous_loan);
					if ($this->date_collected->Exportable) $Doc->ExportCaption($this->date_collected);
					if ($this->date_liquidated->Exportable) $Doc->ExportCaption($this->date_liquidated);
					if ($this->balance_remaining->Exportable) $Doc->ExportCaption($this->balance_remaining);
					if ($this->applicant_date->Exportable) $Doc->ExportCaption($this->applicant_date);
					if ($this->applicant_passport->Exportable) $Doc->ExportCaption($this->applicant_passport);
					if ($this->guarantor_name->Exportable) $Doc->ExportCaption($this->guarantor_name);
					if ($this->guarantor_address->Exportable) $Doc->ExportCaption($this->guarantor_address);
					if ($this->guarantor_mobile->Exportable) $Doc->ExportCaption($this->guarantor_mobile);
					if ($this->guarantor_department->Exportable) $Doc->ExportCaption($this->guarantor_department);
					if ($this->account_no->Exportable) $Doc->ExportCaption($this->account_no);
					if ($this->bank_name->Exportable) $Doc->ExportCaption($this->bank_name);
					if ($this->employers_name->Exportable) $Doc->ExportCaption($this->employers_name);
					if ($this->employers_address->Exportable) $Doc->ExportCaption($this->employers_address);
					if ($this->employers_mobile->Exportable) $Doc->ExportCaption($this->employers_mobile);
					if ($this->guarantor_date->Exportable) $Doc->ExportCaption($this->guarantor_date);
					if ($this->guarantor_passport->Exportable) $Doc->ExportCaption($this->guarantor_passport);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->recommended_date->Exportable) $Doc->ExportCaption($this->recommended_date);
					if ($this->document_checklist->Exportable) $Doc->ExportCaption($this->document_checklist);
					if ($this->recommender_action->Exportable) $Doc->ExportCaption($this->recommender_action);
					if ($this->recommender_comment->Exportable) $Doc->ExportCaption($this->recommender_comment);
					if ($this->recommended_by->Exportable) $Doc->ExportCaption($this->recommended_by);
					if ($this->application_status->Exportable) $Doc->ExportCaption($this->application_status);
					if ($this->approved_amount->Exportable) $Doc->ExportCaption($this->approved_amount);
					if ($this->duration_approved->Exportable) $Doc->ExportCaption($this->duration_approved);
					if ($this->approval_date->Exportable) $Doc->ExportCaption($this->approval_date);
					if ($this->approval_action->Exportable) $Doc->ExportCaption($this->approval_action);
					if ($this->approval_comment->Exportable) $Doc->ExportCaption($this->approval_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
				} else {
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
					if ($this->date_initiated->Exportable) $Doc->ExportCaption($this->date_initiated);
					if ($this->refernce_id->Exportable) $Doc->ExportCaption($this->refernce_id);
					if ($this->employee_name->Exportable) $Doc->ExportCaption($this->employee_name);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->mobile->Exportable) $Doc->ExportCaption($this->mobile);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->pension->Exportable) $Doc->ExportCaption($this->pension);
					if ($this->loan_amount->Exportable) $Doc->ExportCaption($this->loan_amount);
					if ($this->amount_inwords->Exportable) $Doc->ExportCaption($this->amount_inwords);
					if ($this->repayment_period->Exportable) $Doc->ExportCaption($this->repayment_period);
					if ($this->salary_permonth->Exportable) $Doc->ExportCaption($this->salary_permonth);
					if ($this->previous_loan->Exportable) $Doc->ExportCaption($this->previous_loan);
					if ($this->date_collected->Exportable) $Doc->ExportCaption($this->date_collected);
					if ($this->date_liquidated->Exportable) $Doc->ExportCaption($this->date_liquidated);
					if ($this->balance_remaining->Exportable) $Doc->ExportCaption($this->balance_remaining);
					if ($this->applicant_date->Exportable) $Doc->ExportCaption($this->applicant_date);
					if ($this->guarantor_name->Exportable) $Doc->ExportCaption($this->guarantor_name);
					if ($this->guarantor_address->Exportable) $Doc->ExportCaption($this->guarantor_address);
					if ($this->guarantor_mobile->Exportable) $Doc->ExportCaption($this->guarantor_mobile);
					if ($this->guarantor_department->Exportable) $Doc->ExportCaption($this->guarantor_department);
					if ($this->account_no->Exportable) $Doc->ExportCaption($this->account_no);
					if ($this->bank_name->Exportable) $Doc->ExportCaption($this->bank_name);
					if ($this->employers_name->Exportable) $Doc->ExportCaption($this->employers_name);
					if ($this->employers_address->Exportable) $Doc->ExportCaption($this->employers_address);
					if ($this->employers_mobile->Exportable) $Doc->ExportCaption($this->employers_mobile);
					if ($this->guarantor_date->Exportable) $Doc->ExportCaption($this->guarantor_date);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->recommended_date->Exportable) $Doc->ExportCaption($this->recommended_date);
					if ($this->document_checklist->Exportable) $Doc->ExportCaption($this->document_checklist);
					if ($this->recommender_action->Exportable) $Doc->ExportCaption($this->recommender_action);
					if ($this->recommender_comment->Exportable) $Doc->ExportCaption($this->recommender_comment);
					if ($this->recommended_by->Exportable) $Doc->ExportCaption($this->recommended_by);
					if ($this->application_status->Exportable) $Doc->ExportCaption($this->application_status);
					if ($this->approved_amount->Exportable) $Doc->ExportCaption($this->approved_amount);
					if ($this->duration_approved->Exportable) $Doc->ExportCaption($this->duration_approved);
					if ($this->approval_date->Exportable) $Doc->ExportCaption($this->approval_date);
					if ($this->approval_action->Exportable) $Doc->ExportCaption($this->approval_action);
					if ($this->approval_comment->Exportable) $Doc->ExportCaption($this->approval_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->code->Exportable) $Doc->ExportField($this->code);
						if ($this->date_initiated->Exportable) $Doc->ExportField($this->date_initiated);
						if ($this->refernce_id->Exportable) $Doc->ExportField($this->refernce_id);
						if ($this->employee_name->Exportable) $Doc->ExportField($this->employee_name);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->mobile->Exportable) $Doc->ExportField($this->mobile);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->pension->Exportable) $Doc->ExportField($this->pension);
						if ($this->loan_amount->Exportable) $Doc->ExportField($this->loan_amount);
						if ($this->amount_inwords->Exportable) $Doc->ExportField($this->amount_inwords);
						if ($this->purpose->Exportable) $Doc->ExportField($this->purpose);
						if ($this->repayment_period->Exportable) $Doc->ExportField($this->repayment_period);
						if ($this->salary_permonth->Exportable) $Doc->ExportField($this->salary_permonth);
						if ($this->previous_loan->Exportable) $Doc->ExportField($this->previous_loan);
						if ($this->date_collected->Exportable) $Doc->ExportField($this->date_collected);
						if ($this->date_liquidated->Exportable) $Doc->ExportField($this->date_liquidated);
						if ($this->balance_remaining->Exportable) $Doc->ExportField($this->balance_remaining);
						if ($this->applicant_date->Exportable) $Doc->ExportField($this->applicant_date);
						if ($this->applicant_passport->Exportable) $Doc->ExportField($this->applicant_passport);
						if ($this->guarantor_name->Exportable) $Doc->ExportField($this->guarantor_name);
						if ($this->guarantor_address->Exportable) $Doc->ExportField($this->guarantor_address);
						if ($this->guarantor_mobile->Exportable) $Doc->ExportField($this->guarantor_mobile);
						if ($this->guarantor_department->Exportable) $Doc->ExportField($this->guarantor_department);
						if ($this->account_no->Exportable) $Doc->ExportField($this->account_no);
						if ($this->bank_name->Exportable) $Doc->ExportField($this->bank_name);
						if ($this->employers_name->Exportable) $Doc->ExportField($this->employers_name);
						if ($this->employers_address->Exportable) $Doc->ExportField($this->employers_address);
						if ($this->employers_mobile->Exportable) $Doc->ExportField($this->employers_mobile);
						if ($this->guarantor_date->Exportable) $Doc->ExportField($this->guarantor_date);
						if ($this->guarantor_passport->Exportable) $Doc->ExportField($this->guarantor_passport);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->recommended_date->Exportable) $Doc->ExportField($this->recommended_date);
						if ($this->document_checklist->Exportable) $Doc->ExportField($this->document_checklist);
						if ($this->recommender_action->Exportable) $Doc->ExportField($this->recommender_action);
						if ($this->recommender_comment->Exportable) $Doc->ExportField($this->recommender_comment);
						if ($this->recommended_by->Exportable) $Doc->ExportField($this->recommended_by);
						if ($this->application_status->Exportable) $Doc->ExportField($this->application_status);
						if ($this->approved_amount->Exportable) $Doc->ExportField($this->approved_amount);
						if ($this->duration_approved->Exportable) $Doc->ExportField($this->duration_approved);
						if ($this->approval_date->Exportable) $Doc->ExportField($this->approval_date);
						if ($this->approval_action->Exportable) $Doc->ExportField($this->approval_action);
						if ($this->approval_comment->Exportable) $Doc->ExportField($this->approval_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
					} else {
						if ($this->code->Exportable) $Doc->ExportField($this->code);
						if ($this->date_initiated->Exportable) $Doc->ExportField($this->date_initiated);
						if ($this->refernce_id->Exportable) $Doc->ExportField($this->refernce_id);
						if ($this->employee_name->Exportable) $Doc->ExportField($this->employee_name);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->mobile->Exportable) $Doc->ExportField($this->mobile);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->pension->Exportable) $Doc->ExportField($this->pension);
						if ($this->loan_amount->Exportable) $Doc->ExportField($this->loan_amount);
						if ($this->amount_inwords->Exportable) $Doc->ExportField($this->amount_inwords);
						if ($this->repayment_period->Exportable) $Doc->ExportField($this->repayment_period);
						if ($this->salary_permonth->Exportable) $Doc->ExportField($this->salary_permonth);
						if ($this->previous_loan->Exportable) $Doc->ExportField($this->previous_loan);
						if ($this->date_collected->Exportable) $Doc->ExportField($this->date_collected);
						if ($this->date_liquidated->Exportable) $Doc->ExportField($this->date_liquidated);
						if ($this->balance_remaining->Exportable) $Doc->ExportField($this->balance_remaining);
						if ($this->applicant_date->Exportable) $Doc->ExportField($this->applicant_date);
						if ($this->guarantor_name->Exportable) $Doc->ExportField($this->guarantor_name);
						if ($this->guarantor_address->Exportable) $Doc->ExportField($this->guarantor_address);
						if ($this->guarantor_mobile->Exportable) $Doc->ExportField($this->guarantor_mobile);
						if ($this->guarantor_department->Exportable) $Doc->ExportField($this->guarantor_department);
						if ($this->account_no->Exportable) $Doc->ExportField($this->account_no);
						if ($this->bank_name->Exportable) $Doc->ExportField($this->bank_name);
						if ($this->employers_name->Exportable) $Doc->ExportField($this->employers_name);
						if ($this->employers_address->Exportable) $Doc->ExportField($this->employers_address);
						if ($this->employers_mobile->Exportable) $Doc->ExportField($this->employers_mobile);
						if ($this->guarantor_date->Exportable) $Doc->ExportField($this->guarantor_date);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->recommended_date->Exportable) $Doc->ExportField($this->recommended_date);
						if ($this->document_checklist->Exportable) $Doc->ExportField($this->document_checklist);
						if ($this->recommender_action->Exportable) $Doc->ExportField($this->recommender_action);
						if ($this->recommender_comment->Exportable) $Doc->ExportField($this->recommender_comment);
						if ($this->recommended_by->Exportable) $Doc->ExportField($this->recommended_by);
						if ($this->application_status->Exportable) $Doc->ExportField($this->application_status);
						if ($this->approved_amount->Exportable) $Doc->ExportField($this->approved_amount);
						if ($this->duration_approved->Exportable) $Doc->ExportField($this->duration_approved);
						if ($this->approval_date->Exportable) $Doc->ExportField($this->approval_date);
						if ($this->approval_action->Exportable) $Doc->ExportField($this->approval_action);
						if ($this->approval_comment->Exportable) $Doc->ExportField($this->approval_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'loan_application';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'loan_application';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['code'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'loan_application';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['code'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'loan_application';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['code'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
		if (CurrentUserLevel() == -2) {
			ew_AddFilter($filter, "`status` in (6)");
		}
		if (CurrentUserLevel() == 7) {
			ew_AddFilter($filter, "`status` in (1)");
		}
		if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "`status` in (2)");
		}
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE
		// Officer Only
		//if (CurrentPageID() == "add" && CurrentUserLevel() == 1) {

		if (CurrentPageID() == "add")  {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;

				//$rsnew["report_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Loan Application Request Sent Successfully!  Kindly Save your Loan Reference ID  (". $_SESSION['LAP_ID'] .")   for Tracking Your Loan Status &#x2714;");
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

		// Supervisor Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 7) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 2;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Loan Application Request Sent Successfully &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

		// Supervisor Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 3) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 2;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Loan Application Request Sent Successfully &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();

		// Supervisor
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 7) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
			$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
		}

		// Supervisor - Don't change field values captured by Officer
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3  && $this->status->CurrentValue == 1) {
			$rsnew["code"] = $rsold["code"];
			$rsnew["date_initiated"] = $rsold["date_initiated"];
			$rsnew["refernce_id"] = $rsold["refernce_id"];
			$rsnew["employee_name"] = $rsold["employee_name"];
			$rsnew["address"] = $rsold["address"];
			$rsnew["mobile"] = $rsold["mobile"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["loan_amount"] = $rsold["loan_amount"];
			$rsnew["amount_inwords"] = $rsold["amount_inwords"];
			$rsnew["purpose"] = $rsold["purpose"];
			$rsnew["repayment_period"] = $rsold["repayment_period"];
			$rsnew["salary_permonth"] = $rsold["salary_permonth"];
			$rsnew["previous_loan"] = $rsold["previous_loan"];
			$rsnew["date_collected"] = $rsold["date_collected"];
			$rsnew["date_liquidated"] = $rsold["date_liquidated"];
			$rsnew["balance_remaining"] = $rsold["balance_remaining"];
			$rsnew["applicant_date"] = $rsold["applicant_date"];
			$rsnew["applicant_passport"] = $rsold["applicant_passport"];
			$rsnew["guarantor_name"] = $rsold["guarantor_name"];
			$rsnew["guarantor_address"] = $rsold["guarantor_address"];
			$rsnew["guarantor_mobile"] = $rsold["guarantor_mobile"];
			$rsnew["guarantor_department"] = $rsold["guarantor_department"];
			$rsnew["account_no"] = $rsold["account_no"];
			$rsnew["bank_name"] = $rsold["bank_name"];
			$rsnew["employers_name"] = $rsold["employers_name"];
			$rsnew["employers_address"] = $rsold["employers_address"];
			$rsnew["employers_mobile"] = $rsold["employers_mobile"];
			$rsnew["guarantor_date"] = $rsold["guarantor_date"];
			$rsnew["guarantor_passport"] = $rsold["guarantor_passport"];

			//$rsnew["closed_by"] = $rsold["closed_by"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];

			//$rsnew["resolved_action"] = $rsold["resolved_action"];
			//$rsnew["resolved_comment"] = $rsold["resolved_comment"];

			$rsnew["approval_action"] = $rsold["approval_action"];
			$rsnew["approval_comment"] = $rsold["approval_comment"];
		}

		// Supervisor - Don't change field values captured by Officer
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3  && $this->status->CurrentValue == 2) {
			$rsnew["code"] = $rsold["code"];
			$rsnew["date_initiated"] = $rsold["date_initiated"];
			$rsnew["refernce_id"] = $rsold["refernce_id"];
			$rsnew["employee_name"] = $rsold["employee_name"];
			$rsnew["address"] = $rsold["address"];
			$rsnew["mobile"] = $rsold["mobile"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["loan_amount"] = $rsold["loan_amount"];
			$rsnew["amount_inwords"] = $rsold["amount_inwords"];
			$rsnew["purpose"] = $rsold["purpose"];
			$rsnew["repayment_period"] = $rsold["repayment_period"];
			$rsnew["salary_permonth"] = $rsold["salary_permonth"];
			$rsnew["previous_loan"] = $rsold["previous_loan"];
			$rsnew["date_collected"] = $rsold["date_collected"];
			$rsnew["date_liquidated"] = $rsold["date_liquidated"];
			$rsnew["balance_remaining"] = $rsold["balance_remaining"];
			$rsnew["applicant_date"] = $rsold["applicant_date"];
			$rsnew["applicant_passport"] = $rsold["applicant_passport"];
			$rsnew["guarantor_name"] = $rsold["guarantor_name"];
			$rsnew["guarantor_address"] = $rsold["guarantor_address"];
			$rsnew["guarantor_mobile"] = $rsold["guarantor_mobile"];
			$rsnew["guarantor_department"] = $rsold["guarantor_department"];
			$rsnew["account_no"] = $rsold["account_no"];
			$rsnew["bank_name"] = $rsold["bank_name"];
			$rsnew["employers_name"] = $rsold["employers_name"];
			$rsnew["employers_address"] = $rsold["employers_address"];
			$rsnew["employers_mobile"] = $rsold["employers_mobile"];
			$rsnew["guarantor_date"] = $rsold["guarantor_date"];
			$rsnew["guarantor_passport"] = $rsold["guarantor_passport"];
			$rsnew["recommended_date"] = $rsold["recommended_date"];
			$rsnew["document_checklist"] = $rsold["document_checklist"];
			$rsnew["recommended_by"] = $rsold["recommended_by"];

			//$rsnew["closed_by"] = $rsold["closed_by"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			$rsnew["recommender_action"] = $rsold["recommender_action"];
			$rsnew["recommender_comment"] = $rsold["recommender_comment"];

			//$rsnew["approval_action"] = $rsold["approval_action"];
			//$rsnew["approval_comment"] = $rsold["approval_comment"];

		}

		// Confirmed by RECOMMENDER
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 7) ) {
				$rsnew["recommended_date"] = $now->format('Y-m-d H:i:s');
				$rsnew["recommended_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Confirmed by Administrators
				if ($this->recommender_action->CurrentValue == 0 && $this->status->CurrentValue == 1 ) {

					// New
					if ($this->status->CurrentValue == 1) {
						$rsnew["status"] = 1;					
						$rsnew["recommender_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Record Save Only &#x2714;");
				}

				// Confirmed by Administrators
				if ($this->recommender_action->CurrentValue == 1 ) {

					// New
					if ($this->status->CurrentValue == 1) {
						$rsnew["status"] = 2;					
						$rsnew["recommender_action"] = 1;
					}
					$this->setSuccessMessage("&#x25C9; Loan Request successfully Recommended and sent for Authorization &#x2714;");
				}

				// Confirmed by AUTHORIZER
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) ) {
				$rsnew["approval_date"] = $now->format('Y-m-d H:i:s');
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Confirmed by Administrators
				if ($this->approval_action->CurrentValue == 4 && $this->status->CurrentValue == 2 ) {

					// New
					if ($this->status->CurrentValue == 2) {
						$rsnew["status"] = 3;					
						$rsnew["approval_action"] = 4;
					}
					$this->setSuccessMessage("&#x25C9; Loan Request Successfully Rejected &#x2714;");
				}

				// Confirmed by Administrators
				if ($this->approval_action->CurrentValue == 2 ) {

					// New
					if ($this->status->CurrentValue == 2) {
						$rsnew["status"] = 4;					
						$rsnew["approval_action"] = 2;
					}
					$this->setSuccessMessage("&#x25C9; Loan Request Successfully Approved &#x2714;");
				}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
		if (CurrentPageID() == "add")  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_initiated->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_initiated->EditValue = $this->date_initiated->CurrentValue;
			$this->applicant_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->applicant_date->EditValue = $this->applicant_date->CurrentValue;
			$this->guarantor_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->guarantor_date->EditValue = $this->guarantor_date->CurrentValue;
			$this->refernce_id->CurrentValue = $_SESSION['LAP_ID'];
			$this->refernce_id->EditValue = $this->refernce_id->CurrentValue;
		}
		if (CurrentPageID() == "edit" && (CurrentUserLevel() == 7 )) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->recommended_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->recommended_date->EditValue = $this->recommended_date->CurrentValue;
			$this->recommended_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->recommended_by->EditValue = $this->recommended_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && (CurrentUserLevel() == 3)) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->approval_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->approval_date->EditValue = $this->approval_date->CurrentValue;
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

			if (CurrentPageID() == "add") {
					$this->date_initiated->ReadOnly = TRUE;
					$this->refernce_id->ReadOnly = TRUE;
					$this->document_checklist->Visible = FALSE;
					$this->recommended_date->Visible = FALSE;
					$this->recommender_action->Visible = FALSE;
					$this->recommender_comment->Visible = FALSE;
					$this->recommended_by->Visible = FALSE;
					$this->status->Visible = FALSE;
					$this->application_status->Visible = FALSE;
					$this->approved_amount->Visible = FALSE;
					$this->duration_approved->Visible = FALSE;
					$this->approval_date->Visible = FALSE;
					$this->approval_action->Visible = FALSE;
					$this->approval_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->applicant_date->ReadOnly = TRUE;
					$this->guarantor_date->ReadOnly = TRUE;
				}

			// Edit Page
			   if (CurrentPageID() == "edit") {
					if (CurrentUserLevel() == 7 && ($this->status->CurrentValue == 7 || $this->status->CurrentValue == 1)) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->refernce_id->ReadOnly = TRUE;
					$this->employee_name->ReadOnly = TRUE;
					$this->address->ReadOnly = TRUE;
					$this->mobile->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->loan_amount->ReadOnly = TRUE;
					$this->amount_inwords->ReadOnly = TRUE;
					$this->purpose->ReadOnly = TRUE;
					$this->repayment_period->ReadOnly = TRUE;
					$this->salary_permonth->ReadOnly = TRUE;
					$this->previous_loan->ReadOnly = TRUE;
					$this->date_collected->ReadOnly = TRUE;
					$this->date_liquidated->ReadOnly = TRUE;
					$this->balance_remaining->ReadOnly = TRUE;
					$this->applicant_date->ReadOnly = TRUE;
					$this->applicant_passport->ReadOnly = TRUE;
					$this->guarantor_name->ReadOnly = TRUE;
					$this->guarantor_address->ReadOnly = TRUE;
					$this->guarantor_mobile->ReadOnly = TRUE;
					$this->guarantor_department->ReadOnly = TRUE;
					$this->account_no->ReadOnly = TRUE;
					$this->bank_name->ReadOnly = TRUE;
					$this->employers_name->ReadOnly = TRUE;
					$this->employers_address->ReadOnly = TRUE;
					$this->employers_mobile->ReadOnly = TRUE;
					$this->guarantor_date->ReadOnly = TRUE;
					$this->guarantor_passport->ReadOnly = TRUE;
					$this->initiator_action->ReadOnly = TRUE;
					$this->initiator_comment->ReadOnly = TRUE;

					//$this->document_checklist->Visible = TRUE;
					$this->recommended_date->ReadOnly = TRUE;
					$this->recommender_action->Visible = TRUE;
					$this->recommender_comment->Visible = TRUE;

					//$this->recommended_by->Visible = FALSE;
					$this->application_status->Visible = FALSE;
					$this->approved_amount->Visible = FALSE;
					$this->duration_approved->Visible = FALSE;
					$this->approval_date->Visible = FALSE;
					$this->approval_action->Visible = FALSE;
					$this->approval_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 3 && ($this->status->CurrentValue == 6 || $this->status->CurrentValue == 2)) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->refernce_id->ReadOnly = TRUE;
					$this->employee_name->ReadOnly = TRUE;
					$this->address->ReadOnly = TRUE;
					$this->mobile->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->loan_amount->ReadOnly = TRUE;
					$this->amount_inwords->ReadOnly = TRUE;
					$this->purpose->ReadOnly = TRUE;
					$this->repayment_period->ReadOnly = TRUE;
					$this->salary_permonth->ReadOnly = TRUE;
					$this->previous_loan->ReadOnly = TRUE;
					$this->date_collected->ReadOnly = TRUE;
					$this->date_liquidated->ReadOnly = TRUE;
					$this->balance_remaining->ReadOnly = TRUE;
					$this->applicant_date->ReadOnly = TRUE;
					$this->applicant_passport->ReadOnly = TRUE;
					$this->guarantor_name->ReadOnly = TRUE;
					$this->guarantor_address->ReadOnly = TRUE;
					$this->guarantor_mobile->ReadOnly = TRUE;
					$this->guarantor_department->ReadOnly = TRUE;
					$this->account_no->ReadOnly = TRUE;
					$this->bank_name->ReadOnly = TRUE;
					$this->employers_name->ReadOnly = TRUE;
					$this->employers_address->ReadOnly = TRUE;
					$this->employers_mobile->ReadOnly = TRUE;
					$this->guarantor_date->ReadOnly = TRUE;
					$this->guarantor_passport->ReadOnly = TRUE;
					$this->initiator_action->ReadOnly = TRUE;
					$this->initiator_comment->ReadOnly = TRUE;
					$this->document_checklist->ReadOnly = TRUE;
					$this->recommended_date->ReadOnly = TRUE;
					$this->recommender_action->ReadOnly = TRUE;
					$this->recommender_comment->ReadOnly = TRUE;
					$this->recommended_by->ReadOnly = TRUE;
					$this->application_status->Visible = TRUE;
					$this->approved_amount->Visible = TRUE;
					$this->duration_approved->Visible = TRUE;
					$this->approval_date->Visible = TRUE;
					$this->approval_action->Visible = TRUE;
					$this->approval_comment->Visible = TRUE;
					$this->approved_by->ReadOnly = TRUE;
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
