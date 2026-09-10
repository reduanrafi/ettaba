<?php


namespace App\Services;


use App\Models\SearchKeyword;
use Carbon\Carbon;

class SearchService
{
    function Keyword($keyword)
    {
       // $this->SaveNewKeyWord($keyword);
        $existingKeyword = $this->CheckKeyword($keyword);
        if ($existingKeyword!=null)
        {
            $this->UpdateKeyWordFrequency($existingKeyword);
        }
        else{

            $this->SaveNewKeyWord($keyword);

        }
    }
    function CheckKeyword($keyword)
    {
        return SearchKeyword::where('title',$keyword)->first();

    }
    function SaveNewKeyWord($keyword)
    {
        $keywordObj = new SearchKeyword();
        return SearchKeyword::create(['title'=>$keyword]);
    }

    function UpdateKeyWordFrequency($existingKeyword)
    {
        return $existingKeyword->update(['frequency'=>$existingKeyword->frequency+1]);
    }
}