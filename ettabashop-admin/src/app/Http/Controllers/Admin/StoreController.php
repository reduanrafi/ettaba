<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private $globalObject ;
    private  $moduleName="Module";
    private $singularVariableName = 'store';
    private $pluralVariableName = 'stores';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new User();
    }

    public function Index(Request $request)
    {

        $this->retrievedDataList=$this->globalObject->GetShops($request->state);

        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Create()
    {

        $this->retrievedDataList=$this->globalObject->all();

        return view('admin.'.$this->pluralVariableName.'.create',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function Edit($id)
    {
        $data = $this->globalObject->findOrFail($id);

        return view('admin.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$data,
            $this->pluralVariableName=>$this->globalObject->all()
        ]);
    }

    public function Store(Request $request)
    {

        try
        {
            if ($this->globalObject->create($this->globalObject->GetData($request->all())))
            {
                return redirect()->back()->with(['success'=> $this->moduleName." created successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }

    public function Update(Request $request)
    {
        $oldData = $this->globalObject->findOrFail($request->id);



        try
        {

            if ($oldData->update($this->globalObject->GetData($request->all())))
            {
                return redirect()->back()->with(['success'=>$this->moduleName."  updated successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to update"]);

        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }

    public function Delete($id)
    {

        try{
            if ($this->globalObject->destroy($id)){
                return redirect()->back()->with(['success'=>$this->moduleName."  deleted successfully"]);
            }
        }
        catch (QueryException $exception){
            return redirect()->back()->with(['error'=>$exception->getMessage()]);

        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);

    }

    public function ChangeStatus(Request $request)
    {
        $userId = $request->id;
        $status = $request->status;

        try
        {
            $user = User::findOrFail($userId);

            if ($status == 'block') {
                $user->is_active = 0;
                $user->is_blocked = 1;
                $user->is_new = 0;
                $user->save();

                $phone = $user->phone;
                $nid = optional($user->profile)->nid ?: optional($user->shop)->nid_tin;

                // Cross-blocking rule: Blocking a seller also blocks merchant account with same phone/NID
                $linkedMerchants = User::where('type', 'store_administrator')
                    ->where('id', '!=', $user->id)
                    ->where(function($q) use ($phone, $nid) {
                        $q->where('phone', $phone);
                        if (!empty($nid)) {
                            $q->orWhereHas('profile', function($pq) use ($nid) {
                                $pq->where('nid', $nid);
                            })->orWhereHas('shop', function($sq) use ($nid) {
                                $sq->where('nid_tin', $nid);
                            });
                        }
                    })->get();

                foreach ($linkedMerchants as $lm) {
                    $lm->is_active = 0;
                    $lm->is_blocked = 1;
                    $lm->is_new = 0;
                    $lm->save();
                }

                // Add to BlockedUser as seller_merchant so they cannot create seller or merchant account
                \App\Models\BlockedUser::updateOrCreate(
                    ['phone' => $phone, 'type' => 'seller_merchant'],
                    [
                        'user_id' => $user->id,
                        'nid' => $nid,
                        'reason' => 'Seller account blocked by admin (linked merchant also blocked; customer account unaffected)'
                    ]
                );

                return redirect()->back()->with(['success' => "Seller blocked successfully. Any associated Merchant account is also blocked. Customer account remains completely unaffected."]);
            }
            elseif ($status == 'activate') {
                $user->is_active = 1;
                $user->is_blocked = 0;
                $user->is_new = 0;
                $user->save();

                \App\Models\BlockedUser::where('user_id', $user->id)->where('type', 'seller_merchant')->delete();
                \App\Models\BlockedUser::where('phone', $user->phone)->where('type', 'seller_merchant')->delete();

                return redirect()->back()->with(['success' => "Seller activated successfully."]);
            }
            elseif ($status == 'inactivate') {
                $user->is_active = 0;
                $user->is_blocked = 0;
                $user->is_new = 0;
                $user->save();

                return redirect()->back()->with(['success' => "Seller marked inactive successfully."]);
            }
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
        return redirect()->back()->with(['error' => "Unable to handle this request !"]);
    }

}
