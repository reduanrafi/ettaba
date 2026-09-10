<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\PointService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{
    public function test(){

//         $service = new PointService();
//         $service->UpdatePoint(38);
        //$this->TestRecursion(0);
        return redirect()->back();
    }
    public function getPrice(Request $request)
    {
//        return response()->json([
//            'price_in_usd' => $request->url,
//
//        ]);
        $request->validate([
            'url' => 'required|url'
        ]);

        $amazonUrl = $request->url;

        // Validate the URL
        if (!str_contains($amazonUrl, 'amazon')) {
            return response()->json(['error' => 'Invalid Amazon URL'], 400);
        }

        try {
            $client = new Client();
            $response = $client->request('GET', $amazonUrl, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ]
            ]);

            // Parse HTML content
            $htmlContent = $response->getBody()->getContents();
            $crawler = new Crawler($htmlContent);
            return response()->json([
                'price_in_usd' => $htmlContent,

            ]);
            // Extract the price using the appropriate selector
            $price = $crawler->filter('#priceblock_ourprice, #priceblock_dealprice')->first()->text();

            // Clean and convert the price
            $price = str_replace(['$', ','], '', $price);
            $priceInUsd = floatval($price);

            // Convert to BDT
            $exchangeRate = 110; // Example exchange rate
            $priceInBdt = $priceInUsd * $exchangeRate;

            // Add service charge (e.g., 10%)
            $serviceCharge = 0.10;
            $finalPrice = $priceInBdt + ($priceInBdt * $serviceCharge);

            return response()->json([
                'price_in_usd' => $priceInUsd,
                'price_in_bdt' => $finalPrice,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to fetch price'], 500);
        }
    }
    public function TestRecursion($result)
    {


        if ($result>0)
        {
            dd("This is test before recursion");
        }
        else{
            echo $result;
            $this->TestRecursion(1);

        }
        return;
    }


    public function updateOrderUniqueID(){
        $Products = Product::all();
        foreach ($Products as $product)
        {
            $product->update(['unique_id'=>'14'.$product->id]);
        }
        return redirect()->back();
    }
}
