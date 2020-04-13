<?php

namespace App\Http\Controllers;

use App\Companyservices;
use App\Forwarderservices;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;

class forwarderserviceconroller extends Controller
{
    public function view(Request $request, $id)
    {
        $services = Forwarderservices::all();
        $companyservices = Companyservices::where('company_id', $id)->get();
        return view('forwarderservices.manage')->with('title', 'View Route')
            ->with('company_id', $id)
            ->with('services', $services)
            ->with('companyservices', $companyservices);
    }
    public function manage(Request $request, $id)
    {
        $services = Forwarderservices::all();
        $companyservices = Companyservices::where('company_id', $id)->get();
        return view('forwarderservices.manage')->with('title', 'View Route')->with('mode', 'v')
            ->with('company_id', $id)
            ->with('services', $services)
            ->with('companyservices', $companyservices);
    }
    public function save(Request $request, $id)
    {
        $hasCompany = Auth::user()->companies->count();
        if ($hasCompany && !$id) {
            abort(404);
        }

        DB::table('companyservices')->where('company_id', $id)->delete();

        if (Input::has('service_id')) {
            foreach (Input::get('service_id') as $item) {
                $companyservice = new Companyservices;
                $companyservice->service_id = $item;
                $companyservice->company_id = $id;
                $companyservice->created_by = Auth::user()->id;
                $companyservice->updated_by = Auth::user()->id;
                $companyservice->save();
            }
        }
        $services = Forwarderservices::all();
        $companyservices = Companyservices::where('company_id', $id)->get();
        return view('forwarderservices.manage')->with('title', 'View Route')
            ->with('company_id', $id)
            ->with('services', $services)
            ->with('companyservices', $companyservices);
    }
}
