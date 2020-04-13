<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

use App\Chat;
use App\Company;
use App\Creditrequest;
use App\Permission;
use App\Purchaseorder;
use App\Quotation;
use App\Role;
use App\Vendor;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
		
		Passport::routes();

        Gate::define('co_cr', function ($user, $company = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'co_cr');
			return $filtered->count() or $user->isAdmin;
		});
				
		Gate::define('co_ch', function ($user, $company = null) {
			if ($company == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($company instanceof Company) {
					$permissions = $user->permissions($company->id);
				} else {
					$permissions = $user->permissions($company);
				}				
			}			
			$filtered = $permissions->where('name', 'co_ch');
			return $filtered->count();
		});
		
		Gate::define('co_vw', function ($user, $company = null) {
			if ($company == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($company instanceof Company) {
					$permissions = $user->permissions($company->id);
				} else {
					$permissions = $user->permissions($company);
				}
			}			
			$filtered = $permissions->where('name', 'co_vw');
			return $filtered->count() or Gate::allows('cr_ap') or Gate::allows('fi_vw');
		});
		
		Gate::define('co_co', function ($user, $company = null) {
			if ($company == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($company instanceof Company) {
					$permissions = $user->permissions($company->id);
				} else {
					$permissions = $user->permissions($company);
				}
			}			
			$filtered = $permissions->where('name', 'co_co');
			return $filtered->count() or Gate::allows('cr_ap');
		});
		
		//co_at is company attach. Returns true if the user can create or change a company
		//It is a deduced permission.
		Gate::define('co_at', function ($user, $company = null) {
			return (Gate::allows('co_cr', $company) || Gate::allows('co_ch', $company)) ;
		});
		
		//co_va is company view attachment. Returns true if the user can create or change or view a company
		//It is a deduced permission.
		Gate::define('co_va', function ($user, $company = null) {
			return (Gate::allows('co_cr', $company) || Gate::allows('co_ch', $company)|| Gate::allows('co_vw', $company)) ;
		});
		//co_sc is company search. Returns true if the user can create or change or view a company
		//It is a deduced permission.
		Gate::define('co_sc', function ($user, $company = null) {
			return (Gate::allows('co_cr') || Gate::allows('co_ch') || Gate::allows('co_vw') || Gate::allows('co_co') || Gate::allows('pt_as'));
		});
		
		//vendor
		Gate::define('vn_cr', function ($user, $vendor = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'vn_cr');
			return $filtered->count() or $user->isAdmin;
		});
				
		Gate::define('vn_ch', function ($user, $vendor = null) {
			if ($vendor == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($vendor instanceof Vendor) {
					$permissions = $user->permissions($vendor->id);
				} else {
					$permissions = $user->permissions($vendor);
				}				
			}			
			$filtered = $permissions->where('name', 'vn_ch');
			return $filtered->count();
		});
		
		Gate::define('vn_vw', function ($user, $vendor = null) {
			if ($vendor == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($vendor instanceof Vendor) {
					$permissions = $user->permissions($vendor->id);
				} else {
					$permissions = $user->permissions($vendor);
				}
			}			
			$filtered = $permissions->where('name', 'vn_vw');
			return $filtered->count() or Gate::allows('cr_ap');
		});
		
		Gate::define('vn_co', function ($user, $vendor = null) {
			if ($vendor == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($vendor instanceof Vendor) {
					$permissions = $user->permissions($vendor->id);
				} else {
					$permissions = $user->permissions($vendor);
				}
			}			
			$filtered = $permissions->where('name', 'vn_co');
			return $filtered->count() or Gate::allows('cr_ap');
		});
		
		//vn_sc is vendor attach. Returns true if the user can create or change a vendor
		//It is a deduced permission.
		Gate::define('vn_at', function ($user, $vendor = null) {
			return (Gate::any(['vn_cr', 'vn_ch'], $vendor)) ;
		});
		
		//vn_va is vendor view attachment. Returns true if the user can create or change or view a vendor
		//It is a deduced permission.
		Gate::define('vn_va', function ($user, $vendor = null) {
			return (Gate::any(['vn_cr', 'vn_ch', 'vn_vw'], $vendor)) ;
		});
		//vn_sc is vendor search. Returns true if the user can create or change or view a vendor
		//It is a deduced permission.
		Gate::define('vn_sc', function ($user, $vendor = null) {
			return (Gate::any(['vn_cr', 'vn_ch', 'vn_vw']));
		});
		
		//credit request
		Gate::define('cr_cr', function ($user, $creditrequest = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'cr_cr');
			return $filtered->count() or $user->isAdmin;
		});
				
		Gate::define('cr_ch', function ($user, $creditrequest = null) {
			if ($creditrequest == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($creditrequest instanceof Creditrequest) {
					$permissions = $user->permissions($creditrequest->company_id);
				} else {
					$permissions = $user->permissions(Creditrequest::findorFail($creditrequest)->company_id);
				}				
			}			
			$filtered = $permissions->where('name', 'cr_ch');
			return $filtered->count();
		});
		
		Gate::define('cr_vw', function ($user, $creditrequest = null) {
			if ($creditrequest == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($creditrequest instanceof Creditrequest) {
					$permissions = $user->permissions($creditrequest->company_id);
				} else {
					$permissions = $user->permissions(Creditrequest::findorFail($creditrequest)->company_id);
				}
			}			
			$filtered = $permissions->where('name', 'cr_vw');
			return $filtered->count() or Gate::allows('cr_ap') or Gate::allows('cr_of');
		});
		
		Gate::define('cr_ap', function ($user, $creditrequest = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'cr_ap');
			return $filtered->count();
		});
		
		Gate::define('cr_of', function ($user, $creditrequest = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'cr_of');
			return $filtered->count() or Gate::allows('cr_ap');
		});
		
		//support
		Gate::define('su_vw', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'su_vw');
			return $filtered->count();
		});
		Gate::define('su_ch', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'su_ch');
			return $filtered->count();
		});
		Gate::define('su_sc', function ($user) {
			return (Gate::any(['su_ch', 'su_vw']));
		});
		// can report issue
		Gate::define('report_issue', function ($user) {
			return (!(Gate::any(['su_sc', 'su_vw'])));
		});
		//payment term
		Gate::define('pt_mg', function ($user, $creditrequest = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'pt_mg');
			return $filtered->count();
		});
		
		Gate::define('pt_as', function ($user, $creditrequest = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'pt_as');
			return $filtered->count();
		});
		
		Gate::define('pt_vw', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->whereIn('name', ['pt_vw', 'pt_as']);
			return $filtered->count();
		});
		
		//cr_sc is creditrequest search. Returns true if the user can create or change or view a creditrequest
		//It is a deduced permission.
		Gate::define('cr_sc', function ($user, $creditrequest = null) {
			return (Gate::any(['cr_cr', 'cr_ch', 'cr_vw', 'cr_ap', 'cr_of']));
		});
		
		//purchase orders
		Gate::define('po_cr', function ($user, $purchaseorder = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'po_cr');
			return $filtered->count();
		});
				
		Gate::define('po_ch', function ($user, $purchaseorder = null) {
			if ($purchaseorder == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($purchaseorder instanceof Purchaseorder) {
					$permissions = $user->permissions($purchaseorder->company_id);
				} else {
					$permissions = $user->permissions(Purchaseorder::findorFail($purchaseorder)->company->id);
				}				
			}			
			$filtered = $permissions->where('name', 'po_ch');
			return $filtered->count();
		});
		
		Gate::define('po_vw', function ($user, $purchaseorder = null) {
			if ($purchaseorder == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($purchaseorder instanceof Purchaseorder) {
					$permissions = $user->permissions($purchaseorder->company_id);
				} else {
					$permissions = $user->permissions(Purchaseorder::findorFail($purchaseorder)->company->id);
				}
			}			
			$filtered = $permissions->where('name', 'po_vw');
			return $filtered->count();
		});
		
		//po_sc is purchaseorder attach. Returns true if the user can create or change a purchaseorder
		//It is a deduced permission.
		Gate::define('po_at', function ($user, $purchaseorder = null) {
			return (Gate::any(['po_cr', 'po_ch'], $purchaseorder)) ;
		});
		
		Gate::define('po_rl', function ($user, $purchaseorder) {
			if ($purchaseorder instanceof Purchaseorder) {
				$permissions = $user->permissions($purchaseorder->company_id);
			} else {
				$permissions = $user->permissions(Purchaseorder::findorFail($purchaseorder)->company->id);
			}
			$filtered = $permissions->where('name', 'po_rl');
			return $filtered->count();
		});
		
		//po_va is purchaseorder view attachment. Returns true if the user can create or change or view a purchaseorder
		//It is a deduced permission.
		Gate::define('po_va', function ($user, $purchaseorder = null) {
			return (Gate::any(['po_cr', 'po_ch', 'po_vw'], $purchaseorder)) ;
		});
		//po_sc is purchaseorder search. Returns true if the user can create or change or view a purchaseorder
		//It is a deduced permission.
		Gate::define('po_sc', function ($user, $purchaseorder = null) {
			return (Gate::any(['po_cr', 'po_ch', 'po_vw', 'vp_vw', 'po_vm', 'po_cr']));
		});
		
		//global po viewer
		Gate::define('po_vm', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'po_vm');
			return $filtered->count();
		});
		
		Gate::define('po_rc', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'po_rc');
			return $filtered->count();
		});
		
		//vendor po
		Gate::define('vp_vw', function ($user, $purchaseorder = null) {
			if ($purchaseorder == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($purchaseorder instanceof Purchaseorder) {
					$permissions = $user->permissions($purchaseorder->vendor_id);
				} else {
					$permissions = $user->permissions(Purchaseorder::findorFail($purchaseorder)->vendor->id);
				}
			}			
			$filtered = $permissions->where('name', 'vp_vw');
			return $filtered->count();
		});
		Gate::define('vp_ch', function ($user, $purchaseorder = null) {
			if ($purchaseorder == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($purchaseorder instanceof Purchaseorder) {
					$permissions = $user->permissions($purchaseorder->vendor_id);
				} else {
					$permissions = $user->permissions(Purchaseorder::findorFail($purchaseorder)->vendor_id);
					
				}				
			}			
			$filtered = $permissions->where('name', 'vp_ch');
			return $filtered->count();
		});
		Gate::define('vp_ap', function ($user, $purchaseorder = null) {
			if ($purchaseorder == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($purchaseorder instanceof Purchaseorder) {
					$permissions = $user->permissions($purchaseorder->vendor_id);
				} else {
					$permissions = $user->permissions(Purchaseorder::findorFail($purchaseorder)->vendor_id);
					
				}				
			}			
			$filtered = $permissions->where('name', 'vp_ap');
			return $filtered->count();
		});
		//vp_sc is vendor purchaseorder search. Returns true if the user can create or change or view a vendor purchaseorder
		//It is a deduced permission.
		Gate::define('vp_sc', function ($user, $purchaseorder = null) {
			return (Gate::allows('vp_cr') || Gate::allows('vp_ch') || Gate::allows('vp_vw'));
		});
		
		Gate::define('mg_mg', function ($user) {
			$permissions = $user->permissions(0);			
			$filtered = $permissions->where('name', 'mg_mg');
			return $filtered->count();
		});
		
		//quotations
		Gate::define('qu_cr', function ($user, $quotation = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'qu_cr');
			return $filtered->count();
		});
				
		Gate::define('qu_ch', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->vendor_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->vendor->id);
				}				
			}			
			$filtered = $permissions->where('name', 'qu_ch');
			return $filtered->count();
		});
		
		Gate::define('qu_vw', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->vendor_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->vendor->id);
				}
			}			
			$filtered = $permissions->where('name', 'qu_vw');
			return $filtered->count();
		});
		
		Gate::define('cq_vw', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->company_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->company->id);
				}
			}			
			$filtered = $permissions->where('name', 'cq_vw');
			return $filtered->count();
		});

		Gate::define('cq_ch', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->company_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->company->id);
				}
			}			
			$filtered = $permissions->where('name', 'cq_ch');
			return $filtered->count();
		});
		
		//qu_sc is quotation attach. Returns true if the user can create or change a quotation
		//It is a deduced permission.
		Gate::define('qu_at', function ($user, $quotation = null) {
			return (Gate::allows('qu_cr', $quotation) || Gate::allows('qu_ch', $quotation)) ;
		});
		
		Gate::define('qu_rl', function ($user, $quotation) {
			if ($quotation instanceof Quotation) {
				$permissions = $user->permissions($quotation->vendor_id);
			} else {
				$permissions = $user->permissions(Quotation::findorFail($quotation)->vendor->id);
			}
			$filtered = $permissions->where('name', 'qu_rl');
			return $filtered->count();
		});
		
		//qu_va is quotation view attachment. Returns true if the user can create or change or view a quotation
		//It is a deduced permission.
		Gate::define('qu_va', function ($user, $quotation = null) {
			return (Gate::allows('qu_cr', $quotation) || Gate::allows('qu_ch', $quotation)|| Gate::allows('qu_vw', $quotation)) ;
		});
		//qu_sc is quotation search. Returns true if the user can create or change or view a quotation
		//It is a deduced permission.
		Gate::define('qu_sc', function ($user, $quotation = null) {
			return (Gate::any(['qu_cr', 'qu_ch', 'qu_vw', 'vp_vw', 'qu_vm', 'qu_cr', 'cq_vw']));
		});
		
		//global po viewer
		Gate::define('qu_vm', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'qu_vm');
			return $filtered->count();
		});
		
		Gate::define('qu_rc', function ($user) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'qu_rc');
			return $filtered->count();
		});
		
		//buyer quotation
		Gate::define('cq_vw', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->company_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->company->id);
				}
			}			
			$filtered = $permissions->where('name', 'cq_vw');
			return $filtered->count();
		});
		Gate::define('cq_ch', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->company_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->company_id);
					
				}				
			}			
			$filtered = $permissions->where('name', 'cq_ch');
			return $filtered->count();
		});
		Gate::define('cq_ap', function ($user, $quotation = null) {
			if ($quotation == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($quotation instanceof Quotation) {
					$permissions = $user->permissions($quotation->company_id);
				} else {
					$permissions = $user->permissions(Quotation::findorFail($quotation)->company_id);
					
				}				
			}			
			$filtered = $permissions->where('name', 'cq_ap');
			return $filtered->count();
		});
		//cq_sc is vendor quotation search. Returns true if the user can create or change or view a vendor quotation
		//It is a deduced permission.
		Gate::define('cq_sc', function ($user, $quotation = null) {
			return (Gate::allows('vp_cr') || Gate::allows('vp_ch') || Gate::allows('cq_vw'));
		});
		
		//users
		Gate::define('us_cr', function ($user, $company = 0) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'us_cr');
			return $filtered->count() or $user->isSysadmin;
		});
		Gate::define('us_ch', function ($user, $theuser = null) {
			if ($theuser == null) {
				$permissions = $user->permissions(0);
				return $permissions->where('name', 'us_ch')->count() or $user->isSysadmin;
			} else {
				if ($theuser instanceof User) {
					$companies = $theuser->companies;
				} else {
					$companies = User::findorFail($theuser)->companies;
				}
				$mycompanies = $user->companypermissions(['us_ch']);
				$intersect = $mycompanies->intersect($companies);
				return $intersect->count() or ($intersect->count() == 0 and $user->isSysadmin);
			}
		});		
		Gate::define('us_vw', function ($user, $theuser = null) {
			if ($theuser == null) {
				$permissions = $user->permissions(0);
				return $permissions->where('name', 'us_vw')->count() or $user->isSysadmin;
			} else {
				if ($theuser instanceof User) {
					$companies = $theuser->companies;
				} else {
					$companies = User::findorFail($theuser)->companies;
				}
				$mycompanies = $user->companypermissions(['us_vw']);
				$intersect = $mycompanies->intersect($companies);
				return $intersect->count() or $user->isSysadmin;
			}
		});
		Gate::define('us_as', function ($user, $theuser = null) {
			if ($theuser == null) {
				$permissions = $user->permissions(0);
				return $permissions->where('name', 'us_as')->count() or $user->isSysadmin;
			} else {
				if ($theuser instanceof User) {
					$companies = $theuser->companies;
				} else {
					$companies = User::findorFail($theuser)->companies;
				}
				$mycompanies = $user->companypermissions(['us_as']);
				$intersect = $mycompanies->intersect($companies);
				return $intersect->count() or $user->isSysadmin;
			}
		});
		//us_sc is role search. Returns true if the user can create or change or view a role
		//It is a deduced permission.
		Gate::define('us_sc', function ($user, $purchaseorder = null) {
			return (Gate::allows('us_cr') || Gate::allows('us_ch') || Gate::allows('us_vw') || Gate::allows('us_as'));
		});
		//roles
		Gate::define('ro_cr', function ($user, $role = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'ro_cr');
			return $filtered->count() or $user->isSysadmin;
		});
				
		Gate::define('ro_ch', function ($user, $role = null) {
			if ($role == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($role instanceof Role) {
					$permissions = $user->permissions($role->company_id);
				} else {
					$permissions = $user->permissions(Role::findorFail($role)->company_id);
				}				
			}			
			$filtered = $permissions->where('name', 'ro_ch');
			return $filtered->count() or ($filtered->count() == 0 and $user->isSysadmin);
		});
		
		Gate::define('ro_vw', function ($user, $role = null) {
			if ($role == null) {
				$permissions = $user->permissions(0);
			} else {
				if ($role instanceof Role) {
					$permissions = $user->permissions($role->company_id);
				} else {
					if ($user->isSysadmin) {
						$permissions = Permission::where('module_id', 3)->where('active', true)->get();
					} else {
						$permissions = $user->permissions(Role::findorFail($role)->company->id);
					}
				}
			}			
			$filtered = $permissions->where('name', 'ro_vw');
			return $filtered->count() or $user->isSysadmin;
		});				
		//ro_sc is role search. Returns true if the user can create or change or view a role
		//It is a deduced permission.
		Gate::define('ro_sc', function ($user, $purchaseorder = null) {
			return (Gate::allows('ro_cr') || Gate::allows('ro_ch') || Gate::allows('ro_vw'));
		});
			
		//log viewer
		Gate::define('lg_mg', function ($user, $company = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'lg_mg');
			return $filtered->count() or $user->isSysadmin;
		});		
		
		//FI clerk
		Gate::define('fi_cl', function ($user, $company = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'fi_cl');
			return $filtered->count();
		});
		
		//FI viewer
		Gate::define('fi_vw', function ($user, $company = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'fi_vw');
			return $filtered->count();
		});	
		
		//AP
		Gate::define('fi_ap', function ($user, $company = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'fi_ap');
			return $filtered->count();
		});	
		
		//AR
		Gate::define('fi_ar', function ($user, $company = null) {
			$permissions = $user->permissions(0);
			$filtered = $permissions->where('name', 'fi_ar');
			return $filtered->count();
		});	
				
		//phpinfo
		Gate::define('sy_ad', function ($user, $company = null) {
			return $user->isSysadmin;
		});

		//chat view permission ch_vw is not in the DB
		Gate::define('ch_vw', function ($user, $chat = null) {
			if ($chat == null) {
				return true;
			} else {
				$chats = Chat::with('users')->where('id', $chat)->whereHas('users', function ($query) use($user) {
					$query->where('user_id', $user->id);
				})->get();
				return $chats->count();
			}			
		});
    }
}
