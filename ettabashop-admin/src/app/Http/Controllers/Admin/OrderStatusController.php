<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\EarningDistribution;
use App\Services\PointService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    private $moduleName = "Order";
    private $singularVariableName = 'order';
    private $pluralVariableName = 'orders';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new OrderStatus();
    }

    public function ChangeStatus(Request $request)
    {


        try {
            if ($this->globalObject->ChangeOrderStatus($request->id, $request->status)) {

                $this->globalObject->create($this->globalObject->GetData($request->all()));

                return redirect()->back()->with(['success' => $this->moduleName . " updated successfully"]);
            }
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
        return redirect()->back()->with(['error' => "Unable to handle this request !"]);
    }

    public function Done(Request $request)
    {
        try {

            $earningService = new EarningDistribution();

            $pointService = new PointService();

            $earningService->DistributeEarning($request->id);

            $pointService->UpdatePoint($request->id);

            $this->globalObject->ChangeOrderStatus($request->id, 'completed');

            return redirect()->back()->with(['success' => $this->moduleName . " updated successfully"]);

        } catch (QueryException $ex) {

            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }

    }
}
