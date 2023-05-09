<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Database\DB;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Review;

class SearchController extends Controller{

    public function show(){
        $jobsToSelect = "";
        $jobName = $this->request->getQueryParameter('oglas');
        $jobLocation = $this->request->getQueryParameter('mesto');
        $params = [];
        if(!$jobName && !$jobLocation){
            $jobsToSelect = "SELECT id FROM job";
        }elseif(!$jobName && $jobLocation){
            $jobsToSelect = "SELECT id FROM job WHERE location = :jobLocation";
            $params ['jobLocation'] = $jobLocation;
        }elseif($jobName && !$jobLocation){
            $jobsToSelect = "SELECT id FROM job WHERE name LIKE CONCAT('%', :jobName, '%')";
            $params['jobName'] = $jobName;
        }else{
            $jobsToSelect = "SELECT id FROM job WHERE name LIKE CONCAT('%', :jobName, '%') AND location = :jobLocation";
            $params['jobLocation'] = $jobLocation;
            $params['jobName'] = $jobName;
        }
        $queryResults = DB::rawQuery($jobsToSelect, $params)->fetchAll();
        $searchResults = [];
        $i = 0;
        foreach($queryResults as $queryResult){
            $job = Job::findOne(['id' => $queryResult['id']]);
            $chatRoom = ChatRoom::findOne(['user_1_id' => $this->session->get('user') ?? 1]);
            $chatRoom = $chatRoom ? $chatRoom : ChatRoom::findOne(['user_2_id' => $this->session->get('user') ?? 1]);
            $chatRoomLink = $chatRoom ? '/chat/' . $chatRoom->id . '/' . $job->id : '/chat/index/' . $job->id;

            $searchResults[$i]['job'] = $job;
            $searchResults[$i]['review'] = Review::average('review_value', ['job_id' => $job->id]) ?? 5.0;
            $searchResults[$i]['chatRoomLink'] = $chatRoomLink;
            $i++;
        }
        $html = $this->renderer->render('searchResults.html',array_merge(compact('searchResults') ,$this->requiredData));
        $this->response->setContent($html);
    }
}