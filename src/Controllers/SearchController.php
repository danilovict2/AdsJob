<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Database\DB;
use AdsJob\Models\Job;

class SearchController extends Controller{

    public function show(){
        $jobsToSelect = "";
        $jobName = $this->request->getParameter('oglas');
        $jobLocation = $this->request->getParameter('mesto');
        $params = [];
        if(!$jobName && !$jobLocation){
            $jobsToSelect = "SELECT id FROM job";
        }elseif(!$jobName && $jobLocation){
            $jobsToSelect = "SELECT id FROM job WHERE location = :jobLocation";
            $params ['jobLocation'] = $jobLocation;
        }elseif($jobName && !$jobLocation){
            $jobsToSelect = "SELECT id FROM job WHERE name LIKE '%:jobName%'";
            $params['jobName'] = $jobName;
        }else{
            $jobsToSelect = "SELECT id FROM job WHERE name LIKE '%:jobName%' AND location = :jobLocation";
            $params['jobLocation'] = $jobLocation;
            $params['jobName'] = $jobName;
        }
        $queryResults = DB::rawQuery($jobsToSelect, $params)->fetchAll();
        $searchResults = [];
        foreach($queryResults as $queryResult){
            $searchResults[] = Job::findOne(['id' => $queryResult['id']]);
        }
        $html = $this->renderer->render('searchResults.html',array_merge(['searchResults' => $searchResults] ,$this->requiredData));
        $this->response->setContent($html);
    }
}