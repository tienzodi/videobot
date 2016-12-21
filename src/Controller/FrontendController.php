<?php

namespace App\Controller;

use Cake\Core\Configure;

class FrontendController extends AppController
{
    public function playVideo()
    {
        $video_suggest_uid = isset($this->request->params['video_suggest_uid']) ? $this->request->params['video_suggest_uid'] : '';
        if($video_suggest_uid != ''){
            $this->loadModel('VideosSuggests');
            $video_suggests = $this->VideosSuggests->find('all', ['conditions' => ['video_suggest_uid' => $video_suggest_uid]]);
            pr($video_suggests->toArray());
        }
        exit('');
    }
}
