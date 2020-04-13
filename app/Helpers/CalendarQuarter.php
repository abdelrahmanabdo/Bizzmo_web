<?php 

namespace App\Helpers;
use DateTime;


class CalendarQuarter {

    public function lasteightquarters() {
		$quarters = [];
		$month = date('n');
		$q = 4;
		if ($month <= 3) $q = 1;
		if ($month <= 6) $q = 2;
		if ($month <= 9) $q = 3;
		$year = date('Y') - 2;
		for ($i = 1; $i <= 8; $i++) {
			array_push($quarters, 'Q' . $q . '-' . $year);
			if ($q == 4) {
				$year = $year + 1;
				$q = 0;
			}
			$q = $q + 1;
		}
        return $quarters;
    }
    
}