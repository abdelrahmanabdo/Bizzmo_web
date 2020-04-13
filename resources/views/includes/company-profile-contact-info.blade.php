<div class="profile-contact-info-container col-md-12 col-xs-12">
    <div class="contact-info col-md-12 col-xs-12">
        <div class="contact-info-title">Contact Information : </div>
        <div class="contact-info-content">
            <div class="info col-md-12 col-xs-12">
                <div class="item col-md-6">
                    <div class="title">Full address : </div>
                    <div class="string">{{\Auth::user()->getProfile()->address ?? ''}}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">PO Box :</div>
                    <div class="string">{{\Auth::user()->getProfile()->pobox ?? ''}}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">City :</div>
                    <div class="string">{{\Auth::user()->getProfile()->getCity->cityname ?? ''}}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">Country :</div>
                    <div class="string">{{\Auth::user()->getProfile()->getCountry->countryname ?? ''}}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">Tel :</div>
                <div class="string">{{\Auth::user()->getProfile()->tel ?? ''}}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">Fax :</div>
                <div class="string">{{\Auth::user()->getProfile()->fax ?? ''}}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">Web Page :</div>
                    <div class="string">{{ $company->website ?? '' }}</div>
                </div>
                <div class="item col-md-6">
                    <div class="title">Email :</div>
                    <div class="string">{{\Auth::user()->getProfile()->email ?? ''}}</div>
                </div>
            </div>
        </div>
    </div>
</div>