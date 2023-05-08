<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Middleware\AuthMiddleware;
use AdsJob\Models\ChatRoom;
use AdsJob\Models\Job;
use AdsJob\Models\JobImage;
use AdsJob\Models\Review;
use AdsJob\Models\User;

class JobController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth,['create']));
    }

    public function create() : void{
        $html = $this->renderer->render('postJob.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function show(array $params) : void{
        $job = Job::findOne(['id' => (int)$params['job_id']]);
        if(!$job){
            $this->response->redirect('/404');
            return;
        }
        $averageReview = Review::average('review_value', ['job_id' => $job->id]) ?? 5.0;
        $chatRoom = ChatRoom::findOne(['user_1_id' => $this->session->get('user') ?? 1]);
        $chatRoom = $chatRoom ? $chatRoom : ChatRoom::findOne(['user_2_id' => $this->session->get('user') ?? 1]);
        $chatRoomLink = $chatRoom ? '/chat/' . $chatRoom->id . '/' . $job->id : '/chat/index/' . $job->id;
        $html = $this->renderer->render('job.html',array_merge(compact('job', 'averageReview', 'chatRoomLink'),$this->requiredData));
        $this->response->setContent($html);
    }

    public function edit(array $params){
        $html = $this->renderer->render('editJob.html', $this->requiredData);
        $this->response->setContent($html);
    }

    public function store() : void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
            die;
        }
        $validator = new \AdsJob\Validators\Validator([
            'name' => ['required', ['max' => 30]],
            'location' => ['required', ['max' => 30]],
            'description' => [['max' => 255]],
        ]);
        $hasImage = false;
        foreach($this->request->getFiles() as $file){
            $hasImage = $file['error'] === UPLOAD_ERR_OK || $hasImage;
        }
        if(!$hasImage){
            $validator->addError("image", "Potrebna je minimalno jedna slika");
        }
        if(!$validator->validateForm($this->request->getBodyParameters()) || !$hasImage){
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/p/create');
            return;
        }
        
        $this->storeJob();
        $this->response->redirect('/');
    }

    private function storeJob(){
        $job = new Job;
        $job->create([
            'user_id' => (int)$this->session->get('user'),
            'name' => $this->request->getBodyParameter('name'),
            'location' => $this->request->getBodyParameter('location'),
            'description' => $this->request->getBodyParameter('description'),
        ]);
        $job->save();
        foreach($this->request->getFiles() as $file){
            $this->storeJobImage($file, $job);
        }
    }

    private function storeJobImage($image, $job) : void{
        if($image['tmp_name'] === '')return;
        $imageName = uniqid('JOB-', true) . '.' . strtolower(pathinfo($image['name'])['extension']);
        $imagePath = 'storage/jobImages/' . $imageName;
        move_uploaded_file($image['tmp_name'], $imagePath);
        $jobImage = new JobImage;
        $jobImage->create([
            'job_id' => (int)$job->id,
            'imagePath' => $imagePath
        ]);
        $jobImage->save();
    }
}