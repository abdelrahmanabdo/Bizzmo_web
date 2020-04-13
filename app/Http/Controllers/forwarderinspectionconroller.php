<?php

namespace App\Http\Controllers;

use App\Companyforwarderinspection;
use App\Forwarderinspection;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;

class forwarderinspectionconroller extends Controller
{
    public function view(Request $request, $id)
    {
        $inspection = Forwarderinspection::where('company_id', $id)->first();
        return view('forwarderinspection.manage')->with('title', 'View Inspection')
            ->with('Forwarderinspections', $inspection);
    }
    public function template(Request $request, $id)
    {
        $templates = Companyforwarderinspection::where('company_id', $id)->get();
        return view('forwarderinspection.template')
        ->with('company_id', $id)
        ->with('templates',$templates);
    }
    public function manage(Request $request, $id)
    {
        return view('forwarderinspection.manage')->with('company_id', $id);
    }
    public function edit(Request $request, $id)
    {
        $CompanyInspection = Companyforwarderinspection::where('id', $id)->first();
        $Inspections = Forwarderinspection::where('template_id', $id)->get();

        return view('forwarderinspection.manage')->with('mode', 'v')
        ->with('ispection_id', $id)
        ->with('CompanyInspection', $CompanyInspection)
        ->with('company_id', $CompanyInspection->company_id)
        ->with('Inspections', $Inspections);
    }
    public function save(Request $request, $id)
    {
        $hasCompany = Auth::user()->companies->count();
        if ($hasCompany && !$id) {
            abort(404);
        }
        if (Input::has('ispection_id')) {
            //$Inspection = Forwarderinspection::where('id', $id)->first();
            DB::table('forwarderinspections')->where('template_id', $id)->delete();
            $CompanyInspection = Companyforwarderinspection::where('id', $id)->first();
        }
        if (!isset($CompanyInspection)) {
            $CompanyInspection = new Companyforwarderinspection;
        }
        $CompanyInspection->company_id = Input::get('company_id');
        $CompanyInspection->name = Input::get('templatename');
        $CompanyInspection->created_by = Auth::user()->id;
        $CompanyInspection->updated_by = Auth::user()->id;
        $CompanyInspection->save();
        
        $Inspection = new Forwarderinspection;
        if (Input::has('fieldname')) {
            for($i = 0; $i < count(Input::get('fieldname')); $i++) {
                
                $Inspection = new Forwarderinspection;

                $Inspection->name = Input::get('fieldname')[$i];
                $Inspection->template_id = $CompanyInspection->id;
                $Inspection->type = Input::get('fieldtype')[$i];
                $Inspection->value = Input::get('fieldvalue')[$i];
                if(isset(Input::get('chkRequired')[$i]))
                    $Inspection->required = true;
                else
                    $Inspection->required = false;
                if(isset(Input::get('chkActive')[$i]))
                    $Inspection->active = true;
                else
                    $Inspection->active = false;
                $Inspection->created_by = Auth::user()->id;
                $Inspection->updated_by = Auth::user()->id;
                $Inspection->save();
            }
        }
        return redirect('/forwarder/inspection/template/'.Input::get('company_id'));
        // return view('forwarderinspection.manage')->with('title', 'View Inspection')
        //     ->with('company_id', $id)
        //     ->with('Forwarderinspections', $Inspection)
        //     ->with('CompanyInspection', $CompanyInspection);
    }
}
