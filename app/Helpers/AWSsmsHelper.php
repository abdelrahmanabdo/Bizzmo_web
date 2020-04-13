<?php 

namespace App\Helpers;

use AWS;
use Illuminate\Http\Request;

class AWSsmsHelper {
	protected $awsregion;    
    protected $awskey;
    protected $awssecret;

    public function __construct() {
		$this->awsregion = env('AWS_REGION');
        $this->awskey = env('AWS_KEY');
        $this->awssecret = env('AWS_SECRET');
    }

    public function sendSMS($phone_number, $message){
        $sms = AWS::createClient('sns');
    
        $sms->publish([
                'Message' => $message,
                'PhoneNumber' => $phone_number,    
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType'  => [
                        'DataType'    => 'String',
                        'StringValue' => 'Transactional',
                     ]
                 ],
              ]);
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
		return $pin;
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