<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Withdraw();
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
}
