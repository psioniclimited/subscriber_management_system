<?php

namespace App\Modules\CableManagement\DataTables;

use App\Modules\User\Models\User;
use Yajra\Datatables\Services\DataTable;
use App\Modules\CableManagement\Models\Customer;
use App\Modules\SubscriberManagement\Repository\CardRepository;
use App\Modules\SubscriberManagement\Models\Card;
use Entrust;
use DB;
use \Carbon\Carbon;

class CardDataTable extends DataTable
{
    private $distributor, $sub_distributor;

    public function setDistributor($distributor){
        $this->distributor = $distributor;
    }

    public function setSubDistributor($sub_distributor){
        $this->sub_distributor = $sub_distributor;
    }
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax() {

        return $this->datatables->of($this->query())
        ->addColumn('Subdistributor', function ($cards) {
            if($cards->subdistributor_user != null) {
                return $cards->subdistributor_user->name;
            }
            else{
                return 'N/A';
            }
        })
        ->addColumn('Link', function ($cards) {
            if(Entrust::can('cards.update')) {
                $action_view = '<a href="' . url('/cards') . '/' . $cards->id . '/edit' . '" class="btn btn-xs btn-primary">
                <i class="glyphicon glyphicon-edit"></i> Edit
                </a>';
            }
            else{
                $action_view = 'N/A';
            }
            
            return $action_view;
        })
        ->addColumn('Blacklist_link', function ($cards){
            
            if($cards->last_blacklist_history != null && Entrust::can('cards.update')){
                
                $expired_time = Carbon::parse($cards->last_blacklist_history->expired_time);
                
                if($cards->blacklisted == 1 && $expired_time->gt(Carbon::now()) )
                {
                    $blacklist_button ='<a class="btn btn-xs btn-warning" id="'.$cards->id.'"
                        data-toggle="modal" data-target="#confirm_unblacklist">
                        <i class="glyphicon glyphicon-edit"></i> Unblacklist
                        </a>';

                }
                else{
                    $blacklist_button ='<a class="btn btn-xs btn-danger" id="'.$cards->id.'"
                            data-toggle="modal" data-target="#confirm_blacklist">
                            <i class="glyphicon glyphicon-edit"></i> Blacklist
                            </a>';
                }
            }
            else if(Entrust::can('cards.update')){
                $blacklist_button ='<a class="btn btn-xs btn-danger" id="'.$cards->id.'"
                            data-toggle="modal" data-target="#confirm_blacklist">
                            <i class="glyphicon glyphicon-edit"></i> Blacklist
                            </a>';
            }
            else {
                $blacklist_button = "N/A";
            }
            return $blacklist_button;
        })
        ->addColumn('Fingerprint_link', function($cards){
            $fingerprint_button = '<a class="btn btn-xs btn-info" id="'.$cards->id.'"
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
        if(Entrust::hasRole('admin'))
        	$cards = Card::with('subdistributor_user', 'user','last_blacklist_history');
        else if(Entrust::hasRole('distributor'))
            $cards = Card::with('subdistributor_user', 'user','last_blacklist_history')->where('users_id', Entrust::user()->id);
        else if(Entrust::hasRole('sub_distributor'))
            $cards = Card::with('subdistributor_user', 'user','last_blacklist_history')->where('subdistributor', Entrust::user()->id);
        
        return $this->applyScopes($cards);
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
			'card_id' => ['title' => 'Card ID', 'data' => 'card_id'],
            'user.name' => ['title' => 'Distributor', 'data' => 'user.name'],
            'Subdistributor' => ['title' => 'Subdistributor', 'data' => 'subdistributor.name'],
            'Link' => ['title' => 'Action', 'data' => 'Link', 'orderable'=> false, 'searchable' => false],
            'Blacklist_link' => ['title' => 'Blacklist', 'data' => 'Blacklist_link', 'orderable'=> false, 'searchable' => false],
            'Fingerprint_link' => ['title' => 'Fingerprint', 'data' => 'Fingerprint_link', 'orderable'=> false, 'searchable' => false],
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
