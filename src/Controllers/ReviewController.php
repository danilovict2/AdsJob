<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Models\Review;

class ReviewController extends Controller{

    public function store(array $params): void{
        if(!$this->session->validateToken($this->request->getBodyParameter('csrf_token'))){
            echo "CSRF TOKEN INVALID";
            die;
        }
        $jobId = (int)$params['job_id'];
        $validator = new \AdsJob\Validators\Validator([
            'review' => ['required', ['max' => 255]],
        ]);
        if(!$validator->validateForm($this->request->getBodyParameters())){
            $this->setValidationErrors($validator->getErrors());
            $this->response->redirect("/p/$jobId");
            return;
        }
        $rating = (int)$this->request->getBodyParameter('rating') ?? 0;
        $review = $this->request->getBodyParameter('review');
        $jobReview = new Review;
        $jobReview->create([
            'job_id' => $jobId,
            'user_id' => $this->auth->user()->id,
            'review_text' => $review,
            'review_value' => $rating
        ]);
        $jobReview->save();
        $this->response->redirect("/p/$jobId");
    }
}