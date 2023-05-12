<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Database\DB;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\Review;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class SearchController extends Controller{

    private const MAX_JOBS_PER_PAGE = 30;

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
            if($this->session->get("user")){
                $chatRoom = ChatRoom::findOne(['job_id' => $job->id, 'user_1_id' => $this->session->get('user')]);
                $chatRoom = $chatRoom ? $chatRoom : ChatRoom::findOne(['job_id' => $job->id, 'user_2_id' => $this->session->get('user')]);
            }else $chatRoom = false;
            $chatRoomLink = $chatRoom ? '/chat/' . $chatRoom->id . '/' . $job->id : '/chat/index/' . $job->id;

            $searchResults[$i]['job'] = $job;
            $searchResults[$i]['review'] = Review::average('review_value', ['job_id' => $job->id]) ?? 5.0;
            $searchResults[$i]['chatRoomLink'] = $chatRoomLink;
            $i++;
        }

        $adapter = new ArrayAdapter($searchResults);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_JOBS_PER_PAGE);
        $currentPage = (int)$this->request->getQueryParameter('page') ?? 1;
        $pagerfanta->setCurrentPage($currentPage);
        $results = $pagerfanta->getCurrentPageResults();
        $pageLinks = '';
        if($pagerfanta->hasPreviousPage()){
            $url = '?'.http_build_query(['page' => $pagerfanta->getPreviousPage()]);
            $pageLinks .= '<a href="'.$url.'">Previous</a>';
        }
        if($pagerfanta->hasNextPage()){
            $url = '?'.http_build_query(['page' => $pagerfanta->getNextPage()]);
            $pageLinks .= '<a href="'.$url.'">Next</a>';
        }
        $html = $this->renderer->render('searchResults.html',array_merge(['searchResults' => $results, 'pageLinks' => $pageLinks] ,$this->requiredData));
        $this->response->setContent($html);
    }
}