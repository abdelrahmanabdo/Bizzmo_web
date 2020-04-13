<?php

namespace App;

use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

use App\Jobs\Processcreditrequest;

class Creditrequest extends Model
{
	
	use Notifiable;
	
	protected $appends = array('isapprovable');
		
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
	
	public function financialscurrency()
    {
        return $this->belongsTo('App\Currency', 'financialscurrency_id');
    }
	
	public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }
	
	public function creditrequesttype()
    {
        return $this->belongsTo('App\Creditrequesttype', 'requesttype_id');
    }
	
	public function tenor()
    {
        return $this->belongsTo('App\Tenor');
    }
	public function margindeposittype()
    {
        return $this->belongsTo('App\Margindeposittype');
    }
	
	public function busrefs()
    {
        return $this->hasMany('App\Creditrequestbusref');
    }
	
	public function securities()
    {
        return $this->hasMany('App\Creditrequestsecurity');
    }
	
	public function bankstatements()
    {
        return $this->hasMany('App\Creditrequestbankstatement');
    }
	
	public function incomestatements()
    {
        return $this->hasMany('App\Creditrequestincomestatement');
    }
	
	public function balancesheets()
    {
        return $this->hasMany('App\Creditrequestbalancesheet');
    }
	
	public function creditassessments()
    {
        return $this->hasMany('App\CreditAssessment');
    }
	
	public function tradehistory()
    {
        return $this->hasMany('App\TradingHistory');
    }
	
	public function scorecard()
    {
        return $this->hasMany('App\ScoreCard');
    }
	
	public function creditassessmentcompanies()
    {
        return $this->hasMany('App\CreditAssessmentCompany');
    }
	
	public function securitytype()
    {
        return $this->belongsTo('App\Securitytype');
    }
	
	public function creditstatus()
    {
        return $this->belongsTo('App\Creditstatus');
    }
	
	public function appointment()
    {
        return $this->belongsTo('App\Appointment');
    }
	
	public static function cancelappointment($appointmentid) {
		$creditrequests = Creditrequest::where('appointment_id', $appointmentid)->get();
		foreach ($creditrequests as $creditrequest) {
			$creditrequest->appointment_id = null;
			$creditrequest->creditstatus_id = 6; // Set credit status `Pending site visit request`
			$creditrequest->save();
		}
	}
	
	public function updatestatus() {
		if ($this->isapprovable && ($this->creditstatus_id == 4 || $this->creditstatus_id == 1)) {
			if ($this->creditstatus_id == 4) {
				$this->creditstatus_id = 1;			
				$this->save();
			}
			if ($this->creditstatus_id == 1) {
				Processcreditrequest::dispatch($this);
				$company = Company::find($this->company_id);
				$company->creditlimit = $this->limit;
				$company->save();
				foreach ($company->paymentterms as $paymentterm) {
					$company->paymentterms()->updateExistingPivot($paymentterm->id, ['active' => 1]);
				}
			}
		}
	}
	
	public function getIsapprovableAttribute() {
		$attachmentok = true;		
		if ($this->securities->count() > 0) {
			foreach ($this->securities as $security) {
				if ($security->status <> 'signing_complete') {
					$attachmentok = false;
				}
			}
		}
		if ($this->requesttype_id == 1) {
			if ($this->appointment_id == null) {
				$attachmentok = false;
			} else {
				$appointment = Appointment::find($this->appointment_id);
				if ($appointment->status_id != 3) {
					$attachmentok = false;
				}
			}
		}
		return $attachmentok;
	}

	public function getTypeName() {
		return 'credit request';
	}

	public function isSecuritesCompleted() {
		$securities = $this->securities;

		foreach ($securities as $security) {
			if($security->status != "signing_complete")
				return false;
		}
		
		return true;
	}

	public function approveCredit() {
		$company = $this->company;
		$company->creditlimit = $this->limit;
		$company->save();

		$this->creditstatus_id = 1;//Approved
		$this->save();
	}
}
