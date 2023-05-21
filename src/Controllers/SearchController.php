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
        $params = [];
        $jobsToSelect = $this->chooseJobsToSelect($params);
        $queryResults = DB::rawQuery($jobsToSelect, $params)->fetchAll();
        $searchResults = $this->generateSearchResults($queryResults);
        $paginatedResults = $this->paginateSearchResults($searchResults);

        $results = $paginatedResults['results'];
        $pageLinks = $paginatedResults['links'];

        $html = $this->renderer->render('searchResults.html', array_merge(['searchResults' => $results, 'pageLinks' => $pageLinks], $this->requiredData));
        $this->response->setContent($html);
    }

    private function chooseJobsToSelect(array &$params): string{
        $jobName = $this->request->getQueryParameter('oglas');
        $jobLocation = $this->request->getQueryParameter('mesto');

        if(!$jobName && !$jobLocation){
            return "SELECT id FROM job";
        }elseif (!$jobName && $jobLocation){
            $params['jobLocation'] = $jobLocation;
            return "SELECT id FROM job WHERE location = :jobLocation";
        }elseif($jobName && !$jobLocation){
            $params['jobName'] = $jobName;
            return "SELECT id FROM job WHERE name LIKE CONCAT('%', :jobName, '%')";
        }else{
            $params['jobLocation'] = $jobLocation;
            $params['jobName'] = $jobName;
            return "SELECT id FROM job WHERE name LIKE CONCAT('%', :jobName, '%') AND location = :jobLocation";
        }
    }

    private function generateSearchResults(array $queryResults): array{
        $searchResults = [];

        foreach($queryResults as $queryResult){
            $jobId = $queryResult['id'];
            $job = Job::findOne(['id' => $jobId]);
            $chatRoom = $this->getChatRoomForUser($jobId);

            $chatRoomLink = $chatRoom ? '/chat/' . $chatRoom->id . '/' . $job->id : '/chat/index/' . $job->id;

            $searchResults[] = [
                'job' => $job,
                'review' => Review::average('review_value', ['job_id' => $job->id]) ?? 5.0,
                'chatRoomLink' => $chatRoomLink
            ];
        }

        return $searchResults;
    }

    private function getChatRoomForUser(int $jobId){
        if($this->session->get("user")){
            $userId = $this->session->get('user');
            $chatRoom = ChatRoom::findOne(['job_id' => $jobId, 'user_1_id' => $userId]);
            $chatRoom = $chatRoom ? $chatRoom : ChatRoom::findOne(['job_id' => $jobId, 'user_2_id' => $userId]);
            return $chatRoom;
        }

        return false;
    }

    private function paginateSearchResults(array $searchResults): array{
        $adapter = new ArrayAdapter($searchResults);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_JOBS_PER_PAGE);
        $currentPage = $this->request->getQueryParameter('page') ? (int) $this->request->getQueryParameter('page') : 1;
        $pagerfanta->setCurrentPage($currentPage);
        $results = $pagerfanta->getCurrentPageResults();
        $pageLinks = '';

        if($pagerfanta->hasPreviousPage()){
            $url = '?' . http_build_query(['page' => $pagerfanta->getPreviousPage()]);
            $pageLinks .= '<a href="' . $url . '">Previous</a>';
        }

        if($pagerfanta->hasNextPage()){
            $url = '?' . http_build_query(['page' => $pagerfanta->getNextPage()]);
            $pageLinks .= '<a href="' . $url . '">Next</a>';
        }

        return [
            'results' => $results,
            'links' => $pageLinks
        ];
    }
}
