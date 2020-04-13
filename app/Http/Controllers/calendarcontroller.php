<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
use Gate;
use Input;
use View;

use App\Appointment;
use App\Company;
use App\Creditrequest;
use App\Timeslot;
use App\Status;
use App\Jobs\ProcessAppointment;
use App\Jobs\ProcessCancelAppointment;
use App\User;
use App\ScoreFactor;
use App\Score;
use App\CompanyRelationType;
use App\Http\Requests\CompleteAppointmentRequest;
use App\CreditAssessment;
use App\CreditAssessmentCompany;
use App\TradingHistory;
use App\ScoreCard;

use App\Helpers\CalendarQuarter;

class calendarcontroller extends Controller
{
	public function view($id) {
		$appointment = Appointment::findOrFail($id);		
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		
		$creditRequest = Creditrequest::where('appointment_id', $id)->first();
		if ($creditRequest) {
			$creditRequestId = $creditRequest->id;
			$creditAssessment = CreditAssessment::where('creditrequest_id', $creditRequestId)->get();
			$creditAssessmentComapnies = CreditAssessmentCompany::where('creditrequest_id', $creditRequestId)->get();
			return view('calendar.manage')->with('title', 'View appointment')->with('mode', 'v')->with('appointment', $appointment)
			->with('creditAssessment', $creditAssessment->first())
			->with('creditAssessmentComapnies', $creditAssessmentComapnies);
		} else {
			return view('calendar.manage')->with('title', 'View appointment')->with('mode', 'v')->with('appointment', $appointment);
		}
		
	}
	
	public function manage($id) {
		$appointment = Appointment::findOrFail($id);		
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		return view('calendar.manage')->with('title', 'Manage appointment')->with('mode', 'm')->with('appointment', $appointment);
	}
	
	public function confirm($id) {
		$appointment = Appointment::findOrFail($id);		
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		return view('calendar.manage')->with('title', 'Manage appointment')->with('mode', 'n')->with('appointment', $appointment);
	}
	
	public function reject($id) {
		$appointment = Appointment::findOrFail($id);
		if (Gate::allows('cr_of')) {
			
		} else {
			$companies = Auth::user()->companypermissions(['cr_cr', 'cr_ch'])->pluck('id')->all();
			if (!in_array($appointment->company_id, $companies)) {
				return view('message')->with('title', 'Reject appointment')->with('message', __('messages.noauthapp'));		
			}
			$appointment->date = date("j/n/Y",strtotime($appointment->date));
			if ($appointment->status_id == 2 || $appointment->status_id == 3) {
				return view('message')->with('title', 'Reject appointment')->with('message', 'Cannot reject. The appointment status is ' . $appointment->status->name);		
			}
		}
		return view('calendar.manage')->with('title', 'Reject appointment')->with('mode', 'r')->with('appointment', $appointment);
	}
	
	public function rejectc($id) {
		$appointment = Appointment::findOrFail($id);		
		if (Gate::allows('cr_of')) {
			
		} else {
			$companies = Auth::user()->companypermissions(['cr_cr', 'cr_ch'])->pluck('id')->all();
			if (!in_array($appointment->company_id, $companies)) {
				return view('message')->with('title', 'Reject appointment')->with('message', __('messages.noauthapp'));		
			}
			if ($appointment->status_id == 2 || $appointment->status_id == 3) {
				return view('message')->with('title', __('messages.rejectappointment'))->with('message', __('messages.cannotreject') . $appointment->status->name);		
			}
		}
		$appointment->status_id = 9;
		Creditrequest::cancelappointment($id);
		$appointment->save();
		return view('calendar.manage')->with('title', 'View appointment')->with('mode', 'v')->with('appointment', $appointment);
	}
	
	public function accept($id) {
		$appointment = Appointment::findOrFail($id);
		if (Gate::allows('cr_of')) {
			
		} else {
			$companies = Auth::user()->companypermissions(['cr_cr', 'cr_ch'])->pluck('id')->all();
			if (!in_array($appointment->company_id, $companies)) {
				return view('message')->with('title', __('messages.acceptappointment'))->with('message', __('messages.noauthapp'));		
			}
		}
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		if ($appointment->status_id != 1) {
			return view('message')->with('title', __('messages.acceptappointment'))->with('message', __('messages.cannotapp') . $appointment->status->name);		
		}
		return view('calendar.manage')->with('title', __('messages.acceptappointment'))->with('mode', 'a')->with('appointment', $appointment);
	}
	
	public function acceptc($id) {
		$appointment = Appointment::findOrFail($id);
		if (Gate::allows('cr_of')) {
			
		} else {
			$companies = Auth::user()->companypermissions(['cr_cr', 'cr_ch'])->pluck('id')->all();
			if (!in_array($appointment->company_id, $companies)) {
				return view('message')->with('title', __('messages.acceptappointment'))->with('message', __('messages.noauthapp'));		
			}
			if ($appointment->status_id != 1) {
				return view('message')->with('title', __('messages.acceptappointment'))->with('message', __('messages.cannotapp') . $appointment->status->name);		
			}
		}		
		$appointment->status_id = 8;
		$appointment->save();

		// Send appointment email for two parties
		$user = User::findOrFail($appointment->created_by);
		$company = Company::findOrFail($appointment->company_id);
		ProcessAppointment::dispatch($user, $company, $appointment);

		// Change date format
		$appointment->date = date("j/n/Y",strtotime($appointment->date));

		return view('calendar.manage')->with('title', 'View appointment')->with('mode', 'v')->with('appointment', $appointment);
	}
	public function cancel($id) {
		$appointment = Appointment::find($id);		
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		if ($appointment->status_id == 3 || $appointment->status_id == 9) {
			return view('message')->with('title', 'Cancel appointment')->with('message', 'Cannot cancel. The appointment status is ' . $appointment->status->name);		
		}
		return view('calendar.manage')->with('title', 'Cancel appointment')->with('mode', 'c')->with('appointment', $appointment);
	}
	
	public function cancelc($id) {
		$appointment = Appointment::find($id);		
		if ($appointment->status_id == 3 || $appointment->status_id == 9) 
			return view('message')->with('title', 'Cancel appointment')->with('message', 'Cannot cancel. The appointment status is ' . $appointment->status->name);		
		
		$company = Company::findOrFail($appointment->company_id);

		Creditrequest::cancelappointment($id);
		$appointment->status_id = 2;
		$appointment->save();

		ProcessCancelAppointment::dispatch($company, $appointment);
		
		// Change date format
		$appointment->date = date("j/n/Y",strtotime($appointment->date));

		return view('calendar.manage')->with('title', 'View appointment')->with('mode', 'v')->with('appointment', $appointment);
	}

	public function unblock($id) {
		$appointment = Appointment::find($id);		
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		if ($appointment->status_id != 17) {
			return view('message')->with('title', 'Block appointment')->with('message', 'Cannot block. The appointment status is ' . $appointment->status->name);		
		}
		return view('calendar.manage')->with('title', 'Block appointment')->with('mode', 'b')->with('appointment', $appointment);
	}
	
	public function unblockc($id) {
		$appointment = Appointment::find($id);		
		if ($appointment->status_id == 3 || $appointment->status_id == 9) {
			return view('message')->with('title', 'Block appointment')->with('message', 'Cannot block. The appointment status is ' . $appointment->status->name);		
		}
		$appointment->delete();		
		return redirect('calendar');
	}

	public function complete($id) {
		$appointment = Appointment::find($id);
		$scoreFactors = ScoreFactor::all();
		$scores = Score::all();
		$calendarquarter = new CalendarQuarter();
		$companyRelationTypes = CompanyRelationType::all();

		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		if ($appointment->status_id != 8) {
			return view('message')->with('title', 'Complete appointment')->with('message', __('messages.cannotcomplete') . $appointment->status->name);		
		}
		return view('calendar.manage')
			->with('title', __('messages.completeappointment'))
			->with('mode', 'l')
			->with('appointment', $appointment)
			->with ('quarters', $calendarquarter->lasteightquarters())
			->with('scoreFactors', $scoreFactors)
			->with('scores', $scores->pluck('value', 'id'))
			->with('companyRelationTypes', $companyRelationTypes->pluck('name', 'id'));
	}

	public function completeappointment(CompleteAppointmentRequest $request, $id) {
		$appointment = Appointment::find($id);		

		if ($appointment->status_id != 8) {
			return view('message')->with('title', __('messages.completeappointment'))->with('message', __('messages.cannotcomplete') . $appointment->status->name);		
		}
		
		$creditRequest = Creditrequest::where('appointment_id', $id)->first();
		$creditRequestId = $creditRequest->id;

		// Save credit assessment data
		$creditAssessment = new CreditAssessment();
		$creditAssessment->creditrequest_id = $creditRequestId;
		$creditAssessment->prepared_by = $request->prepared_by;
		$creditAssessment->approved_by = $request->approved_by;
		
		
		$date = date_create_from_format("d/m/Y",$request->date_of_assessment);
		//$purchaseorder->date = $date->format('Y-m-d');			
		
		
		$creditAssessment->date_of_assessment = $date->format('Y-m-d');
		$creditAssessment->company_background = $request->company_background;
		$creditAssessment->key_financials_developments = $request->key_financials_developments;
		$creditAssessment->key_risks = $request->key_risks;
		$creditAssessment->mitigating_factors = $request->mitigating_factors;
		$creditAssessment->heighest_balance = $request->highest_outstanding_blance ? $request->highest_outstanding_blance : 0;
		$creditAssessment->created_by = Auth::user()->id;
		$creditAssessment->updated_by = Auth::user()->id;
		$creditAssessment->save();

		// Save companies
		$i = 0;
		$types = $request->companyType;
		$creditAssessmentCompanies = [];
		$tradingHistoryRecords = [];
		$scores = [];
		
		if (Input::has('companyName')) {
			foreach ($request->companyName as $name) {
				$company = new CreditAssessmentCompany();
				$company->creditrequest_id = $creditRequestId;			
				$company->company_name = $name;
				$company->companyrelationtype_id = $types[$i++];
				$company->created_by = Auth::user()->id;
				$company->updated_by = Auth::user()->id;
				$company->save();
				$creditAssessmentCompanies[] = $company;
			}
		}
		// Save sales and payments `Trading History`
		for ($i=0; $i < 8; $i++) { 
			$sales = 'sales_' . $i;
			$payments = 'payments_' . $i;
			$tradingHistory = new TradingHistory();
			$tradingHistory->creditrequest_id = $creditRequestId;			
			$tradingHistory->quarter =$request->quarter[$i];
			$tradingHistory->sales = $request->$sales ? $request->$sales : 0;
			$tradingHistory->payments = $request->$payments ? $request->$payments : 0;
			$tradingHistory->created_by = Auth::user()->id;
			$tradingHistory->updated_by = Auth::user()->id;
			$tradingHistory->save();
			$tradingHistoryRecords[] = $tradingHistory;
		}

		// Save scores
		for ($i=0; $i < 7; $i++) { 
			$scoreId = 'scores_' . $i;
			$scoreFactor = 'score_factor_' . $i;
			$factorWeight = 'factor_weight_' . $i;
			$score = new ScoreCard();
			$score->creditrequest_id = $creditRequestId;			
			$score->factor_id = $request->$scoreFactor;
			$score->weight = $request->$factorWeight;
			$score->score_id = $request->$scoreId;
			$score->created_by = Auth::user()->id;
			$score->updated_by = Auth::user()->id;
			$score->save();
			$scores[] = $score;
		}

		// Update appointment status
		$appointment->status_id = 3;
		$appointment->save();

		// Change date format
		$appointment->date = date("j/n/Y",strtotime($appointment->date));
		\Log::Debug($tradingHistoryRecords);
		return view('calendar.manage',[
			'title' => 'View appointment',
			'mode' => 'v',
			'appointment' => $appointment,
			'tradingHistory' => $tradingHistory,
			'creditAssessment' => $creditAssessment,
			'creditAssessmentCompanies' => $creditAssessmentCompanies,
			'scores' => $scores,
			'tradingHistoryRecords' => $tradingHistoryRecords
		]);
	}
	
    public function create($creditrequestid = '', $start = 0) {
		//echo 'start: ' . $start . '<br>';
		//create the from
		$date=date_create(date('Ymd'));
		date_add($date,date_interval_create_from_date_string($start . " week"));
		$from = date_format($date,"Y-m-d");		
		date_add($date,date_interval_create_from_date_string("6 days"));
		$to = date_format($date,"Y-m-d");
		if ($creditrequestid != '') {
			$creditrequest = Creditrequest::find($creditrequestid);
			if ($creditrequest->appointment != null) {
				if ($creditrequest->appointment->status_id != 2) {
					return view('message')->with('title', 'Create appointment')->with('message', 'An appointment is already pending for this request');		
				}
			}	
		}
		$appointments = Appointment::whereBetween('date', array($from, $to))->whereIn('status_id', [1, 8, 17])->get();
		$timeslots = Timeslot::where('active', 1)->get();
		return view('calendar.calendar')->with('title', 'Create appointment')->with('start', $start * 7)->with('appointments', $appointments)
		->with('timeslots', $timeslots->pluck('name', 'id'));
	}
	
	public function save(Request $request, $creditrequestid = '') {
		//echo Input::get('nextprev');
		if (Input::get('nextprev') != '0') {
			return $this->create($creditrequestid, Input::get('period'));
		}
		
		$rules = [
			'slot' => 'required',
        ];
		$this->validate($request, $rules);		
		
		$date=date_create(date('Ymd'));
		$i = explode('-', Input::get('slot'));
		$date = date_add($date,date_interval_create_from_date_string($i[0] . " day"));
		$appointment = new Appointment;
		if ($creditrequestid != '') {
			$creditrequest = Creditrequest::find($creditrequestid);
			if ($creditrequest->appointment_id != null) {
				return view('message')->with('title', 'Request site visit')->with('message', 'Cannot request visit. A site visit is already requested.');
			}			
			$appointment->company_id = $creditrequest->company_id;
		}		
		$appointment->timeslot_id = $i[1] + 1;
		$appointment->date = $date;
		$appointment->description = 'Site visit for ' . Company::find($appointment->company_id)->companyname;
		$appointment->appointmenttype_id = 1;		
		$appointment->status_id = 1;
		$appointment->created_by = Auth::user()->id;
		$appointment->updated_by = Auth::user()->id;
		$appointment->save();
		if ($creditrequestid != '') {
			$creditrequest->appointment_id = $appointment->id;
			$creditrequest->creditstatus_id = 2;
			$creditrequest->save();
		}
		return $this->view($appointment->id);
	}
	
	public function searchpending() {
		return $this->search(true, false, false, true);
	}
	
	public function searchpendingcredit() {
		return $this->search(true, false, true, false);
	}
	
	public function searchupcoming() {
		return $this->search(true, true);
	}
	
	public function searchstart() {
		return $this->search(false, false);
	}
	
	public function search($startsearch = true, $upcoming = false, $pendingcredit = false, $pending = false)
    {
		$query = Appointment::orderBy('date', 'asc');
		$roles = Auth::User()->roles;
		$companyzero = $roles->where('company_id', '0');
		if ($companyzero->count() == 0 ) {
			$companies = Auth::user()->companypermissions(['cr_cr', 'cr_ch', 'cr_vw']);
		} else {
			$companies = Company::where('companytype_id', 1)->get();
		}
		$statuses = Status::where('statustype', 'appointment');
		if (Gate::denies('cr_ap')) {
			$query = $query->whereIn('company_id', $companies->pluck('id'));
		}
		if (Input::get('status_id') != '0' && Input::get('status_id') != null) {
			$query = $query->where('status_id', Input::get('status_id'));
		}
		if (Input::get('fromdate') != '') {
			$date = date_create_from_format("j/n/Y",Input::get('fromdate'));
			$query = $query->where('date', '>=', $date->format('Y-m-d'));
		}		
		if (Input::get('todate') != '') {
			$date = date_create_from_format("j/n/Y",Input::get('todate'));
			$query = $query->where('date', '<=', $date->format('Y-m-d'));
		}
		if ($upcoming) {
			$date=date_create(date('Ymd'));
			$from = date_format($date,"Y-m-d");
			date_add($date,date_interval_create_from_date_string("1 days"));
			$to = date_format($date,"Y-m-d"); 	
			$query = $query->whereBetween('date', array($from, $to));
			$query = $query->whereIn('status_id', [1, 8]);
		}
		if ($pendingcredit) {
			$query = $query->whereIn('status_id', [1, 8]);
		}
		if ($pending) {
			$query = $query->whereIn('status_id', [1, 8])->whereIn('company_id', $companies->pluck('id'));
		}
		$showconditions  = true;
		if ($upcoming || $pendingcredit || $pending ) {
			$showconditions  = false;
		}
			
		if ($startsearch) {
			//DB::enableQueryLog();
			$appointments = $query->get();
			//var_dump( DB::getQueryLog());
			return View('calendar.search')->with('title', 'Search appointments')
			->with('companies', array('0' => 'All') + $companies->pluck('companyname', 'id')->all())
			->with('statuses', array('0' => 'All') + $statuses->pluck('name', 'id')->all())
			->with('upcoming', $upcoming)->with('pendingcredit', $pendingcredit)
			->with('showconditions', $showconditions)
			->with('appointments', $appointments);
		} else {
			return View('calendar.search')->with('title', 'Search appointments')
			->with('companies', array('0' => 'All') + $companies->pluck('companyname', 'id')->all())
			->with('statuses', array('0' => 'All') + $statuses->pluck('name', 'id')->all())			
			->with('showconditions', $showconditions)
			->with('upcoming', $upcoming)->with('pendingcredit', $pendingcredit);
		}
	}

	public function blockAppointments($start = 0) {
		//echo 'start: ' . $start . '<br>';
		//create the from
		$date=date_create(date('Ymd'));
		date_add($date,date_interval_create_from_date_string($start . " week"));
		$from = date_format($date,"Y-m-d");		
		date_add($date,date_interval_create_from_date_string("6 days"));
		$to = date_format($date,"Y-m-d");

		$appointments = Appointment::whereBetween('date', array($from, $to))
			->whereIn('status_id', [1, 8, 17])
			->get();
		$timeslots = Timeslot::where('active', 1)->get();
		return view('calendar.blockAppointment')->with('title', 'Block appointment')->with('start', $start * 7)->with('appointments', $appointments)
		->with('timeslots', $timeslots->pluck('name', 'id'));
	}
		
	public function block(Request $request, $companyId = '') {
			if (Input::get('nextprev') != '0') {
				return $this->blockAppointments(Input::get('period'));
			}
			
			$rules = [
				'slots' => 'required | array | min:1'
			];
			$this->validate($request, $rules);		

			$slots = Input::get('slots');
			foreach ($slots as $slot) {
				$date=date_create(date('Ymd'));
				$i = explode('-', $slot);
				$date = date_add($date,date_interval_create_from_date_string($i[0] . " day"));
				$appointment = new Appointment;
				$appointment->company_id = 0;
				$appointment->timeslot_id = $i[1] + 1;
				$appointment->date = $date;
				$appointment->description = 'Blocked by admin';
				$appointment->appointmenttype_id = 2;		
				$appointment->status_id = 17;
				$appointment->created_by = Auth::user()->id;
				$appointment->updated_by = Auth::user()->id;
				$appointment->save();
			}
			

			return $this->blockAppointments(Input::get('period'));
		}
}
