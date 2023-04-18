<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\Job;
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
        $html = $this->renderer->render('job.html',array_merge(['user' => $user, 'job' => $job],$this->requiredData));
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
        $job = new Job;
        $job->create([
            'user_id' => (int)$this->session->get('user'),
            'name' => $this->request->getBodyParameter('name'),
            'location' => $this->request->getBodyParameter('location'),
            'description' => $this->request->getBodyParameter('description'),
        ]);
        
        $job->save();
        $this->response->redirect('/');
    }
}