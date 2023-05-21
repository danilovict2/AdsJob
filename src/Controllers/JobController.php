<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Middleware\AuthMiddleware;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\JobImage;
use AdsJob\Models\Review;
use Intervention\Image\ImageManagerStatic as Image;
use AdsJob\Models\User;

class JobController extends Controller{
    
    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth, ['create', 'edit']));
    }

    public function create(): void{
        $html = $this->renderer->render('postJob.html', $this->requiredData);
        $this->response->setContent($html);
    }

    public function show(array $params): void{
        $jobId = (int) $params['job_id'];
        $job = Job::findOne(['id' => $jobId]);

        if(!$job){
            $this->response->redirect('/404');
            return;
        }

        $averageReview = Review::average('review_value', ['job_id' => $job->id]) ?? 5.0;
        $chatRoom = $this->getChatRoomForUser($jobId, $this->session->get("user"));
        $chatRoomLink = $chatRoom ? '/chat/' . $chatRoom->id . '/' . $job->id : '/chat/index/' . $job->id;
        $html = $this->renderer->render('job.html', array_merge(compact('job', 'averageReview', 'chatRoomLink'), $this->requiredData));
        $this->response->setContent($html);
    }

    public function edit(array $params){
        $jobId = $params['job_id'];
        $job = Job::findOne(['id' => $jobId]);

        if (!$job || $job->user_id !== $this->auth->user()->id){
            $this->response->redirect('/');
            return;
        }

        $html = $this->renderer->render('editJob.html', $this->requiredData);
        $this->response->setContent($html);
    }

    public function store(): void{
        if (!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
            die;
        }

        $validator = new \AdsJob\Validators\Validator([
            'name' => ['required', ['max' => 30]],
            'location' => ['required', ['max' => 30]],
            'description' => [['max' => 255]],
        ]);

        $hasImage = $this->checkImageFiles();

        if(!$validator->validateForm($this->request->getBodyParameters()) || !$hasImage){
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/p/create');
            return;
        }

        $this->storeJob();
        $this->response->redirect('/');
    }

    private function checkImageFiles(): bool{
        foreach($this->request->getFiles() as $file){
            if ($file['error'] === UPLOAD_ERR_OK){
                return true;
            }
        }

        return false;
    }

    private function storeJob(){
        $job = new Job;
        $job->create([
            'user_id' => (int) $this->session->get('user'),
            'name' => $this->request->getBodyParameter('name'),
            'location' => $this->request->getBodyParameter('location'),
            'description' => $this->request->getBodyParameter('description'),
        ]);
        $job->save();

        foreach($this->request->getFiles() as $file){
            $this->storeJobImage($file, $job);
        }
    }

    private function storeJobImage($image, $job): void{
        if ($image['tmp_name'] === ''){
            return;
        }

        $imageName = uniqid('JOB-', true) . '.' . strtolower(pathinfo($image['name'])['extension']);
        $imagePath = 'storage/jobImages/' . $imageName;
        move_uploaded_file($image['tmp_name'], $imagePath);
        $img = Image::make($imagePath);
        $img->resize(1000, 500);
        $img->save();
        $jobImage = new JobImage;
        $jobImage->create([
            'job_id' => (int) $job->id,
            'imagePath' => $imagePath
        ]);
        $jobImage->save();
    }

    private function getChatRoomForUser(int $jobId, $userId){
        if($userId){
            $chatRoom = ChatRoom::findOne(['job_id' => $jobId, 'user_1_id' => $userId]);
            if(!$chatRoom){
                $chatRoom = ChatRoom::findOne(['job_id' => $jobId, 'user_2_id' => $userId]);
            }
            return $chatRoom;
        }

        return false;
    }
}
