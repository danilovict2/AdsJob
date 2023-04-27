<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Database\DB;
use AdsJob\Models\Job;

class SearchController extends Controller{

    public function show(array $params){
        $jobsToSelect = "";
        $jobName = $this->request->getParameter('oglas');
        $jobLocation = $this->request->getParameter('mesto');
        if(!$jobName && !$jobLocation){
            $jobsToSelect = "SELECT id FROM job";
        }elseif(!$jobName && $jobLocation){
            $jobsToSelect = "SELECT id FROM job WHERE location = $jobLocation";
        }elseif($jobName && !$jobLocation){
            $jobsToSelect = "SELECT id FROM job WHERE name LIKE '%$jobName%'";
        }else{
            $jobsToSelect = "SELECT id FROM job WHERE name LIKE '%$jobName%' AND location = $jobLocation";
        }
        $queryResults = DB::rawQuery($jobsToSelect)->fetchAll();
        $searchResults = [];
        foreach($queryResults as $queryResult){
            $searchResults[] = Job::findOne(['id' => $queryResult['id']]);
        }
        $html = $this->renderer->render('searchResults.html',array_merge(['searchResults' => $searchResults] ,$this->requiredData));
        $this->response->setContent($html);
    }
}