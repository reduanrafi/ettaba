<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public $message = '';
    public $view = 'website.checkout';
    public function checkout()
    {

        $category = new Category();
        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        if($this->checkEligibility()==0)
        {
            return redirect()->route('user.profile')->with(['error'=>$this->message]);
        }

        return view($this->view,[
            'categories'=>$categories
        ]);
    }

    public function getUserProfile()
    {
        if (Auth::user())
        {
            return User::with('profile')->where('id', Auth::user()->id)->first();
        }
    }
    private function checkEligibility()
    {
        $userProfile = $this->getUserProfile();

        if (isset($userProfile))
        {
            if ($userProfile->profile == null)
            {
                $this->message = 'Please create Your Profile with all necessary information';
                return 0;

            }
            elseif ($this->getUserProfile()->profile->address == null)
            {
                $this->message = 'Please add your address';

                return 0;
            }

        }
        return 1;
    }

    public function checkCartPaymentRules(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        $advancePaymentRequired = false;

        $products = \App\Models\Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            if ($product->category_id) {
                $catId = $product->category_id;
                while ($catId) {
                    $categoryObj = \App\Models\Category::find($catId);
                    if ($categoryObj) {
                        if ($categoryObj->advance_payment_required) {
                            $advancePaymentRequired = true;
                            break 2;
                        }
                        $catId = $categoryObj->parent_id;
                    } else {
                        break;
                    }
                }
            }
        }

        return response()->json([
            'advance_payment_required' => $advancePaymentRequired,
        ]);
    }
}
