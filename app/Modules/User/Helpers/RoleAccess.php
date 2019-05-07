<?php
namespace App\Modules\User\Helpers;
use Entrust;

class RoleAccess{
	
	public function getDistributor($request) {
		if(Entrust::hasRole('admin') && $request->has('distributors_id'))
            return $request->input('distributors_id');
        elseif(Entrust::hasRole('distributor'))
            return Entrust::user()->id;
    }

    public function getSubdistributor($request)	{
		if ($request->has('sub_distributors_id') && (Entrust::hasRole('admin') || Entrust::hasRole('distributor')))
            return $request->input('sub_distributors_id');
        else
            return null;
	}



	public function getUserForAdmin($request)	{
		if ($request->has('sub_distributors_id'))
            return $request->input('sub_distributors_id');
        elseif($request->has('distributors_id'))
            return $request->input('distributors_id');
	}

	public function getUserForDistributor($request)	{
		if ($request->has('sub_distributors_id'))
            return $request->input('sub_distributors_id');
        else
            return Entrust::user()->id;
    }

	public function getUserForSubDistributor($request)	{
		return Entrust::user()->id;
	}
}