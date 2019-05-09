<?php

namespace App\Modules\CableManagement\DataTables;

use App\Modules\User\Models\User;
use Carbon\Carbon;
use Yajra\Datatables\Services\DataTable;
use App\Modules\CableManagement\Models\Customer;
use Entrust;
use DB;

class CustomerDataTable extends DataTable
{
    private $customer_with_card_option, $product_type;

    public function setCustomerWithCardOption($customer_with_card_option)
    {
        $this->customer_with_card_option = $customer_with_card_option;
    }

    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {

        return $this->datatables->of($this->query())
            ->addColumn('Subdistributor', function ($customers) {
                if ($customers->subdistributor_user != null) {
                    return $customers->subdistributor_user->name;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('Distributor', function ($customers) {
                return $customers->user->name;
            })
            ->addColumn('Link', function ($customers) {
                if (Entrust::can('subscribers.update') && Entrust::can('subscribers.delete')) {
                    $action_view = '<a href="' . url('/customers') . '/' . $customers->customers_id . '/edit' . '" class="btn btn-xs btn-primary">
            <i class="glyphicon glyphicon-edit"></i> Edit
            </a>';
                } else {
                    $action_view = 'N/A';
                }
                return $action_view;
            })
            ->addColumn('Activate', function ($customers) {
                if ($customers->active == 0) {
                    $activate_button = '<a class="btn btn-xs bg-olive" id="' . $customers->customers_id . '"
                            data-toggle="modal" data-target="#activate_customer">
                            <i class="glyphicon glyphicon-edit"></i> Activate
                            </a>';
                } else {
                    $activate_button = '<a class="btn btn-xs btn-warning" id="' . $customers->customers_id . '"
                            data-toggle="modal" data-target="#deactivate_customer">
                            <i class="glyphicon glyphicon-edit"></i> Deactivate
                            </a>';
                }
                return $activate_button;
            })
            ->addColumn('Entitle_Link', function ($customers) {
                if (Entrust::can('entitle.create') && !$customers->cards()->get()->isEmpty()) {
                    $entitle_view = '<a href="' . url('/customers') . '/' . $customers->customers_id . '/entitle' . '" class="btn btn-xs btn-primary">
            <i class="glyphicon glyphicon-edit"></i> Entitle
            </a>';
                } elseif ($customers->cards()->get()->isEmpty()) {
                    $entitle_view = 'Card not assigned';
                }
                return $entitle_view;
            })
            ->addColumn('Pair_link', function ($customers) {
                if (Entrust::can('subscribers.update') && Entrust::can('subscribers.delete')) {
                    $pair_button_view = '<a class="btn btn-xs btn-success" id="' . $customers->customers_id . '"
                        data-toggle="modal" data-target="#confirm_pair">
                        <i class="glyphicon glyphicon-edit"></i> Pair
                        </a>';
                } else {
                    $pair_button_view = '<a class="btn btn-xs btn-danger" id="' . $customers->customers_id . '"
                        data-toggle="modal" data-target="#confirm_unpair">
                        <i class="glyphicon glyphicon-edit"></i> Unpair
                        </a>';
                }
                return $pair_button_view;
            })
            ->addColumn('Fingerprint_link', function ($customers) {
                $fingerprint_button = '<a class="btn btn-xs btn-info" id="' . $customers->customers_id . '"
                            data-toggle="modal" data-target="#confirm_fingerprint">
                            <i class="glyphicon glyphicon-edit"></i> Fingerprint
                            </a>';
                return $fingerprint_button;
            })
            ->make(true);

    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $user_id = Entrust::user();
        if (Entrust::hasRole('admin')) {
            if ($this->customer_with_card_option === "1") {
                $customers = Customer::has('card')->with('card', 'subdistributor_user', 'user');
                if ($this->product_type != null && $this->product_type !== '0') {
//                    dd($this->product_type);
                    $product_type = $this->product_type;
                    $customers->whereHas('card_entitlement_history', function ($q) use ($product_type) {
                        $q->where("products_id", "=", $product_type)
                        ->whereNotNull("customers_id")
                        ->whereDate("end_time", ">=", Carbon::today()->toDateString());
                    });
                }

            } elseif ($this->customer_with_card_option === "2") {
                $customers = Customer::doesntHave('card')->with('card', 'subdistributor_user', 'user');
            }

        } else if (Entrust::hasRole('distributor')) {
            if ($this->customer_with_card_option === "1")
                $customers = Customer::has('card')->with('card', 'subdistributor_user', 'user')->where('users_id', Entrust::user()->id);
            elseif ($this->customer_with_card_option === "2")
                $customers = Customer::doesntHave('card')->with('card', 'subdistributor_user', 'user')->where('users_id', Entrust::user()->id);
        } else if (Entrust::hasRole('sub_distributor')) {
            if ($this->customer_with_card_option === "1")
                $customers = Customer::has('card')->with('card', 'subdistributor_user', 'user')->where('subdistributor', Entrust::user()->id);
            elseif ($this->customer_with_card_option === "2")
                $customers = Customer::doesntHave('card')->with('card', 'subdistributor_user', 'user')->where('subdistributor', Entrust::user()->id);
        }

        return $this->applyScopes($customers);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset', 'reload']
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'customers_id' => ['title' => '#', 'data' => 'customers_id'],
            'name' => ['title' => 'Name', 'data' => 'name'],
            'phone' => ['title' => 'Phone', 'data' => 'phone'],
            'address' => ['title' => 'Address', 'data' => 'address'],
            'card.card_id' => ['title' => 'Card ID', 'data' => 'card.card_id'],
            'user.name' => ['title' => 'Distributor', 'data' => 'user.name'],
            'Subdistributor' => ['title' => 'Subdistributor', 'data' => 'subdistributor.name'],
            'Link' => ['title' => 'Action', 'data' => 'Link', 'orderable' => false, 'searchable' => false],
            'Activate' => ['title' => 'Activate/Deactivate', 'data' => 'Activate', 'orderable' => false, 'searchable' => false],
            'Entitle_Link' => ['title' => 'Entitle', 'data' => 'Entitle_Link', 'orderable' => false, 'searchable' => false],
            'Pair_link' => ['title' => 'Pair', 'data' => 'Pair_link', 'orderable' => false, 'searchable' => false],
            'Fingerprint_link' => ['title' => 'Fingerprint', 'data' => 'Fingerprint_link', 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'customertables_' . time();
    }
}
