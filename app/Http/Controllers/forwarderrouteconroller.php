<?php

namespace App\Http\Controllers;

use App\Companyforwarderroute;
use App\Forwarderroute;
use App\Shippinginquiry;
use App\Country;
use App\Port_code;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;
use App\Jobs\ProcessShipmentInquiry;

class forwarderrouteconroller extends Controller
{
    public function view(Request $request, $id)
    {   
        $routes = Forwarderroute::where('company_id', $id)->get();
        return view('forwarderroutes.view')
        ->with('company_id', $id)
        ->with('routes',$routes);
    }
    public function manage(Request $request, $id = 0)
    {
        $userCompany = Auth::user()->companies->first();
        $countries = Country::where('active', 1)
        // ->whereHas('port_codes', function($query) use ($port_codes) {
        //     $query->where('isocode', $port_codes);
        // })
        ->orderBy('countryname')->get();
        return view('forwarderroutes.manage')->with('title', 'View Route')
            ->with('company_id', $userCompany->id)
            ->with('countries', $countries);
    }
    public function edit(Request $request, $id)
    {
        //$companyRoute = Companyforwarderroute::where('id', $id)->first();
        $route = Forwarderroute::where('id', $id)->first();
        $countries = Country::where('active', 1)
        ->orderBy('countryname')->get();
        $startports = Port_code::where('CountryCode', $route->startcode->country->isocode)->get();
        $endports = Port_code::where('CountryCode', $route->endcode->country->isocode)->get();
        return view('forwarderroutes.manage')
        ->with('countries', $countries)
        ->with('startports', $startports)
        ->with('endports', $endports)
        ->with('company_id', $route->company_id)
        ->with('route', $route);
    }
    public function save(Request $request, $id = 0)
    {
        $userCompany = Auth::user()->companies->first();
        if(isset($id))
            $route = Forwarderroute::where('id', $id)->first();
        if(!isset($route))
        {
            $route = new Forwarderroute;
        }
        $route->start = Input::get('startport_id');
        $route->end = Input::get('endport_id');
        $route->company_id = $userCompany->id;
        if(isset(Input::get('chkActive')[0]))
            $route->active = true;
        else
            $route->active = false;
        $route->created_by = Auth::user()->id;
        $route->updated_by = Auth::user()->id;
        $route->save();
        return redirect('/forwarder/route/view/'.$userCompany->id);
    }
    public function getport(Request $request)
    {
        $search = $request->countrycode;
        $ports = Port_code::where('CountryCode', $search)->get();
		 return response()->json($ports);
    }

    public function find(Request $request,$id)
    {
        $countries = Country::where('active', 1)
        // ->whereHas('port_codes', function($query) use ($port_codes) {
        //     $query->where('isocode', $port_codes);
        // })
        ->orderBy('countryname')->get();
        //->pluck('countryname' ,'isocode');
        return view('forwarderroutes.find')
        ->with('countries', $countries)
        ->with('poid', $id);
    }
    public function searchresult(Request $request, $id){
        
        $routes = Forwarderroute::where('start', Input::get('startport_id'))
        ->where('end', Input::get('endport_id'))
        ->get();
        
        $countries = Country::where('active', 1)
        // ->whereHas('port_codes', function($query) use ($port_codes) {
            //     $query->where('isocode', $port_codes);
            // })
        ->orderBy('countryname')->get();
        
        $startports = Port_code::where('CountryCode', Input::get('startcountry_id'))->get();
        $endports = Port_code::where('CountryCode', Input::get('endcountry_id'))->get();
        //$id = Input::get('poid');

        //->pluck('countryname' ,'isocode');
        return view('forwarderroutes.find')
        ->with('countries', $countries)
        ->with('startports', $startports)
        ->with('endports', $endports)
        ->with('routes', $routes)
        ->with('poid', $id);
    }
    public function shipInq(Request $request){

        if (Input::has('companyId')) {
            foreach (Input::get('companyId') as $item) {
                $shippingInquiry = new Shippinginquiry;
                $shippingInquiry->company_id = $item;
                $shippingInquiry->purchaseorder_id = Input::get('poid');
                $shippingInquiry->size = Input::get('size');
                $shippingInquiry->volume = Input::get('volume');
                $shippingInquiry->boxes = Input::get('boxes');
                $shippingInquiry->status = 31;
                $shippingInquiry->created_by = Auth::user()->id;
                $shippingInquiry->updated_by = Auth::user()->id;
                $shippingInquiry->save();
                //$purchaseorder = Purchaseorder::find(Input::get('poid'));
                ProcessShipmentInquiry::dispatch($shippingInquiry);
            }
        }
        //return view('forwarderroutes.find');
        return redirect('/purchaseorders/');
    }
    public function show(Request $request,$id)
    {
        $shippinginquiries = Shippinginquiry::where('purchaseorder_id', $id)->get();
        // $countries = Country::where('active', 1)
        // ->whereHas('port_codes', function($query) use ($port_codes) {
        //     $query->where('isocode', $port_codes);
        // })
        // ->orderBy('countryname')->get();
        //->pluck('countryname' ,'isocode');
        return view('forwarderroutes.show')
        ->with('shippinginquiries', $shippinginquiries);
        //->with('countries', $countries)
        // ->with('poid', $id);
    }
    public function display(Request $request,$id)
    {
        $shippinginquiries = Shippinginquiry::where('id', $id)->get();
        // $countries = Country::where('active', 1)
        // ->whereHas('port_codes', function($query) use ($port_codes) {
        //     $query->where('isocode', $port_codes);
        // })
        // ->orderBy('countryname')->get();
        //->pluck('countryname' ,'isocode');
        return view('forwarderroutes.show')
        ->with('shippinginquiries', $shippinginquiries);
        //->with('countries', $countries)
        // ->with('poid', $id);
    }
}
