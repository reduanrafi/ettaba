<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGenerationGroup;
use App\Services\EarningService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\CustomerGenerationCommissionGroupService;
class CustomerController extends Controller
{
    private $globalObject ;
    private  $moduleName="Customer";
    private $singularVariableName = 'customer';
    private $pluralVariableName = 'customers';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new User();
    }

    public function Index(Request $request)
    {

        $this->retrievedDataList=$this->globalObject->GetCustomers($request->state);

        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }
    public function Show(Request $request)
    {

        $this->singleData=$this->globalObject->getCustomerDetail($request->customer);
        //dd($this->singleData);
        return view('admin.customers.detail',[
            $this->singularVariableName=>$this->singleData
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

    public function UpdateReferralLimit(Request $request)
    {
        $oldData = $this->globalObject->findOrFail($request->id);

        if ($oldData->customer_type == 'buy_only')
        {
            return redirect()->back()->with(['error' => "Cannot update referral limits for Customer accounts"]);
        }

        $updateData = [];
        if ($oldData->customer_type == 'buy_earn' && $oldData->account_number == 'subsequent') {
            $updateData = [
                'partner_limit' => $request->partner_limit,
                'customer_limit' => 0,
                'merchant_limit' => 0
            ];
        } else {
            $updateData = [
                'partner_limit' => $request->partner_limit,
                'customer_limit' => $request->customer_limit,
                'merchant_limit' => $request->merchant_limit
            ];
        }

        try
        {

            if ($oldData->update($updateData))
            {
                return redirect()->back()->with(['success'=> "Referral limits updated successfully"]);
            }
            return redirect()->back()->with(['error'=>"Unable to update"]);

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
        //dd($userId);


        try
        {
            if ($request->status=='approve') {


                $customerGenerationCommissionGroupService = new CustomerGenerationCommissionGroupService($this->globalObject);

                $status = $customerGenerationCommissionGroupService->SaveUserGenerationGroup($userId);
                if ($status==0)
                {
                    return redirect()->back()->with(['error'=>"Generation group already created or there is error in this section"]);
                }

            }
            if ($this->globalObject->ChangeUserStatus($userId,$request->status))
            {
                return redirect()->back()->with(['success'=> $this->moduleName." updated successfully"]);
            }
            else{
                return redirect()->back()->with(['error'=>"this status  already updated"]);
            }
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
    }

    public function ChangeSearchAccess(Request $request)
    {
        $userId = $request->id;
        $status = $request->status;

        try
        {
            $user = User::findOrFail($userId);
            $user->merchant_search_access = $status;
            
            if ($user->save())
            {
                $statusText = $status == 1 ? 'Enabled' : 'Disabled';
                return redirect()->back()->with(['success'=> "Merchant search access {$statusText} successfully"]);
            }
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }
        return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
    }


    public function AddReferralBonus(Request $request)
    {

        try
        {
            $userId = $request->id;

            $earningService = new EarningService();

            if($earningService->AddReferralBonus($userId)==true)
            {
                return redirect()->back()->with(['success'=> "Referral added successfully"]);

            }

            return redirect()->back()->with(['error'=>  "Referral already  given"]);


        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }


}
