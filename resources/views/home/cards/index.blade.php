@foreach($items as $item)
    @php
        $created_at = new DateTime($item->created_at);
        $now = new DateTime();
        $now->format('Y-m-d H:i:s');
        $date_diff = $created_at->diff($now);
        if($date_diff->d)
            $show_date = "$date_diff->d days";
        elseif($date_diff->h)
            $show_date = "$date_diff->h hours";
        else
            $show_date = "$date_diff->i mins";
    @endphp
<div class="col-md-4 card-container">
    <div class="bm-panel card">
        @switch($type)
            @case('po')
                @include('home.cards.po-card', [
                    'po' => $item, 
                    'view_link' => $view_link, 
                    'show_date' => $show_date,
                    'info' => $info
                    ])
                @break

            @case('shpinq')
                @include('home.cards.shpinq-card', [
                    'shpinq' => $item, 
                    'info' => $info
                    ])
                @break

            @case('qu')
                @include('home.cards.qu-card', [
                    'qu' => $item,
                    'show_date' => $show_date,
                    'view_link' => $view_link,
                    'info' => $info                  
                    ])
                @break
            
            @case('cr')
                @include('home.cards.cr-card', [
                    'cr' => $item,
                    'info' => $info,
                    'show_date' => $show_date
                    ])
                @break
			@case('co')
                @include('home.cards.company-card', [
                    'company' => $item, 
                    'show_date' => $show_date,
					'message' => 'Inactive company'
                    ])
                @break
			 @case('vt')
                @include('home.cards.vt-card', [
                    'vt' => $item,
                    'info' => $info,
                    'show_date' => $show_date
                    ])
                @break
				
            @case('appt')
                @include('home.cards.appt-card', [
                    'appt' => $item, 
                    'show_date' => $show_date,
                    'is_comp_site_visit' => $is_comp_site_visit
                    ])
                @break

             @case('support')
                @include('home.cards.support-card', [
                    'support' => $item, 
                    'show_date' => $show_date
                    ])
                @break

            @case('company')				
                @include('home.cards.company-card', [
                    'company' => $item, 
                    'show_date' => $show_date,
					'message' => 'Unconfirmed company'
                    ])
                @break
			 @case('unsignedcompany')
				@switch ($item->companytype_id)
					@case (1)
						<?php
							if ($item->customer_signed == 0) {
								$contract = 'Buyer '; 
							}
						?>
					@break
					@case (2)
						<?php
							if ($item->vendor_signed == 0) {
								$contract = 'Supplier '; 
							}
						?>
					@break
					@case (3)
						<?php
							if ($item->customer_signed == 0 && $item->vendor_signed == 0) {
								$contract = 'Buyer & Supplier'; 
							} elseif ($item->customer_signed == 0) {
								$contract = 'Buyer'; 
							} elseif ($item->vendor_signed == 0) {
								$contract = 'Supplier'; 
							}
						?>
					@break
					@case (4)
						<?php
							if ($item->vendor_signed == 0) {
								$contract = 'Forwarder '; 
							}
						?>
					@break
				@endswitch
                @include('home.cards.company-card', [
                    'company' => $item, 
                    'show_date' => $show_date,
					'message' => $contract . ' contract not signed'
                    ])
                @break
        @endswitch
    </div>
</div>
@endforeach