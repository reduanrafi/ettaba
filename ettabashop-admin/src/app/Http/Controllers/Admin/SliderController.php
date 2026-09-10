<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    private $globalObject ;
    private  $moduleName="Slider";
    private $singularVariableName = 'slider';
    private $pluralVariableName = 'sliders';

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new Slider();
    }

    public function index()
    {


        $this->retrievedDataList=$this->globalObject->all();

        return view('admin.'.$this->pluralVariableName.'.index',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function create()
    {

        $this->retrievedDataList=$this->globalObject->all();

        return view('admin.'.$this->pluralVariableName.'.create',[
            $this->pluralVariableName=>$this->retrievedDataList
        ]);
    }

    public function show($id)
    {

    }
    public function edit($id)
    {
        $data = $this->globalObject->findOrFail($id);

        return view('admin.'.$this->pluralVariableName.'.edit',[
            $this->singularVariableName=>$data,
            $this->pluralVariableName=>$this->globalObject->all()
        ]);
    }

    public function store(Request $request)
    {
        //dd($request->all($this->globalObject->GetData($request->all())));

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

    public function update(Request $request,$id)
    {

        $oldData = $this->globalObject->findOrFail($id);
       // dd($oldData);

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

    public function destroy($id)
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
