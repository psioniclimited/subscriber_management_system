<?php

namespace App\Modules\Dashboard\Controllers;
use Illuminate\Http\Request;
use App\Modules\CableManagement\DataTables\CustomerDataTable;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Models\RoleUser;
use App\Modules\User\Models\User;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\CableManagement\Models\CustomerDetails;
use Carbon\Carbon;
use App\Modules\Dashboard\Repository\ReportsRepository;

class DashboardController extends Controller {

    public function index(Request $request, CustomerDataTable $dataTable, ReportsRepository $reports) {
        if (Auth::check()) {
            $dataTable->setCustomerWithCardOption($request->customer_with_card_option);
            return $dataTable
                    ->render('Dashboard::dashboard', [
                        'distributor_count' => $reports->attributeCounter('distributor'),
                        'user_count' => $reports->attributeCounter('user'),
                        'customer_count' => $reports->attributeCounter('customer'),
                        'card_count' => $reports->attributeCounter('card'),
                        'active_customer_count' => $reports->attributeCounter('active_customer'),
                        'inactive_customer_count' => $reports->attributeCounter('inactive_customer')
                    ]);
        }

       	return redirect('login'); 
    }
    

}
