<?php 

namespace App\Helpers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioHelper {
	protected $accountSid;    
    protected $authToken;
    protected $twilioNumber;

    public function __construct() {
		$this->accountSid = env('TWILIO_ACCOUNT_SID');
        $this->authToken = env('TWILIO_AUTH_TOKEN');
        $this->twilioNumber = env('TWILIO_NUMBER');
    }

    public function sendSMS($phone_number, $message){
		$client = new Client($this->accountSid, $this->authToken);
		$client->messages->create(
			// Where to send a text message (your cell phone?)
			$phone_number,
			array(
				'from' => $this->twilioNumber,
				'body' => $message
			)
		);  
			  
    }
	
	function generatePIN($digits = 4)
	{
		$i = 0; //counter
		$pin = ""; //our default pin is blank.
		while ($i < $digits) {
        //generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return '0000'; //$pin;
	}
	
	function hiddenphone($phone)
	{
		$showchars = 3;
		$numberOfHiddenChars = strlen($phone) - $showchars;
		$stars = "";
		for ($i = 0; $i < $numberOfHiddenChars; $i++) {
			$stars .= "*";
		}
		return $stars . substr($phone, (-1 * $showchars));
	}
}