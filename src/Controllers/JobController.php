<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\Job;
use AdsJob\Models\JobImage;
use AdsJob\Models\User;

class JobController extends Controller{

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
        $user = User::findOne(['id' => $job->user_id]);
        $html = $this->renderer->render('job.html',array_merge(['user' => $user, 'job' => $job, 'jobImages' => $job->images()],$this->requiredData));
        $this->response->setContent($html);
    }

    public function store() : void{
        $validator = new \AdsJob\Validators\Validator([
            'name' => ['required', ['max' => 30]],
            'location' => ['required', ['max' => 30]],
        ]);
        if(!$validator->validateForm($this->request->getBodyParameters())){
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/p/create');
            return;
        }
        $hasImage = false;
        $this->storeJob();
        foreach($this->request->getFiles() as $file){
            $hasImage = $this->storeJobImage($file) || $hasImage;
        }
        if(!$hasImage){
            $validator->addError("image", "Jedna slika je obavezna");
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect('/p/create');
            return;
        }
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
    }

    private function storeJobImage($image) : bool{
        if($image['tmp_name'] === '')return false;
        $imageName = uniqid('JOB-', true) . '.' . strtolower(pathinfo($image['name'])['extension']);
        $imagePath = 'storage/jobImages/' . $imageName;
        move_uploaded_file($image['tmp_name'], $imagePath);
        $jobImage = new JobImage;
        $job = Job::findOne($this->request->getBodyParameters());
        $jobImage->create([
            'job_id' => (int)$job->id,
            'imagePath' => $imagePath
        ]);
        $jobImage->save();
        return true;
    }
}