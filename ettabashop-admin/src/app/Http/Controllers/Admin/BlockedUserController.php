<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedUser;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BlockedUserController extends Controller
{
    private $globalObject ;
    private  $moduleName="Blocked";
    private $singularVariableName = 'blockedUser';
    private $pluralVariableName = 'blockedUsers';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new BlockedUser();
    }

    public function Index()
    {


        $this->retrievedDataList=$this->globalObject->all();

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
            if ($this->globalObject->checkNid($request->nid)==null){
                if ($this->globalObject->create($this->globalObject->GetData($request->all())))
                {
                    return redirect()->back()->with(['success'=> " This nid and phone number added into the black list"]);
                }
            }
            else{
                return redirect()->back()->with(['error'=>"This Nid Already black listed"]);

            }
            return redirect()->back()->with(['error'=>"Unable to handle this request !"]);
        }
        catch (QueryException $ex)
        {
            return redirect()->back()->with(['error'=>$ex->getMessage()]);
        }

    }
    public function CheckNID(Request $request)
    {
        try
        {

            if ($this->globalObject->checkNid($request->nid)==null){
                return redirect()->back()->with(['error'=>"NID Not found in the black list"]);
            }
            else{
                return redirect()->back()->with(['error'=>"This Nid Already black listed"]);

            }

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
}
