<?php
namespace Admin\Controller;

use Admin\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Http\Client;

/**
 * Videos Controller
 *
 * @property \Admin\Model\Table\VideosTable $Videos
 */
class VideosController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->APIKEY = 'AIzaSyCqQ0YswuSkLe1gTzIIMXU2qP0FEQjob9o';
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $category = isset($this->request->query['category']) ? $this->request->query['category'] : '';
        $search = isset($this->request->query['search']) ? $this->request->query['search'] :"";
        $status_id = isset($this->request->query['status']) ? $this->request->query['status'] : "";
        $options = array();
        if($status_id >= 0 && is_numeric($status_id)){
            $options['conditions'][] = array('status' => $status_id);
        }
        if($category >= 0 && is_numeric($category)){
            $options['conditions'][] = array('category_id' => $category);
        }
        if($search != ''){
            $options['conditions'][] = array('video_title LIKE' => '%'.$search.'%');
        }
		$options['order'] = ['video_published_at'=>'desc','video_id'=>'asc'];
        $this->paginate = $options;
        $videos = $this->paginate($this->Videos);
        $this->set(compact('videos'));
        $this->set('_serialize', ['videos']);
        $status = array(
            '0' => 'Waiting for processing',
            '1' => 'Approved',
            '2' => 'Decilined'
        );

        $YT_Category = Configure::read('YT_Category');
        $this->set('YT_Category', $YT_Category);
        $this->set('status', $status);
        $this->set('category', $category);
        $this->set('search', $search);
        $this->set('status_id', $status_id);

    }

    /**
     * View method
     *
     * @param string|null $id Video id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $video = $this->Videos->get($id, [
            'contain' => []
        ]);

        $this->set('video', $video);
        $this->set('_serialize', ['video']);
        $status = array(
            '0' => 'Waiting for processing',
            '1' => 'Approved',
            '2' => 'Decilined'
        );
        $this->set('status', $status);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Videos');
        $YT_Category = Configure::read('YT_Category');
        $video = $this->Videos->newEntity();
        if ($this->request->is('post')) {
            $youtube_id = isset($this->request->data['youtube_id']) ? $this->request->data['youtube_id'] : '';
            if($youtube_id != ''){
                $results = $this->getVideoYoutube($youtube_id);
                $item = isset($results['items'][0]) ? $results['items'][0] : array();
                if(!empty($item)){
                    $video_published_at = isset($item['snippet']['publishedAt']) ? $item['snippet']['publishedAt'] : null;
                    if($video_published_at != null){
                        $video_published_at = date('Y-m-d H:i:s', strtotime($video_published_at));
                    }
                    $duration = isset($item['contentDetails']['duration']) ? $item['contentDetails']['duration'] : '';
                    $video_data = array(
                        'youtube_id' => $youtube_id,
                        'category_id' => $this->request->data['category_id'],
                        'category_yt_id' => isset($item['snippet']['categoryId']) ? $item['snippet']['categoryId'] : '',
                        'category_name' => isset($YT_Category[$this->request->data['category_id']]) ? $YT_Category[$this->request->data['category_id']] : '',
                        'video_title' => isset($item['snippet']['title']) ? $item['snippet']['title'] : '',
                        'video_thumb' => isset($item['snippet']['thumbnails']['high']['url']) ? $item['snippet']['thumbnails']['high']['url'] : '',
                        'video_length' => $this->convertDuration($duration),
                        'video_published_at' => $video_published_at,
                        'video_channel_id' => isset($item['snippet']['channelId']) ? $item['snippet']['channelId'] : '',
                        'status' => 0,
                        'search_type' => 1,
                        'activate' => 1
                    );
                    $video = $this->Videos->patchEntity($video, $video_data);
                    if ($this->Videos->save($video)) {
                        $this->Flash->success(__('The video has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The video could not be saved. Please, try again.'));
                    }
                }
                else{
                    $this->Flash->error(__('The video could not be found. Please, try again.'));
                }
            }
        }
        $status = array(
            '0' => 'Waiting for processing',
            '1' => 'Approved',
            '2' => 'Decilined'
        );
        $this->set('YT_Category', $YT_Category);
        $this->set('status', $status);
        $this->set(compact('video'));
        $this->set('_serialize', ['video']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Video id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Videos');
        $video = $this->Videos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $video = $this->Videos->patchEntity($video, $this->request->data);
            if ($this->Videos->save($video)) {
                $this->Flash->success(__('The video has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The video could not be saved. Please, try again.'));
            }
        }
        $status = array(
            '0' => 'Waiting for processing',
            '1' => 'Approved',
            '2' => 'Decilined'
        );
        $this->set('status', $status);
        $this->set(compact('video'));
        $this->set('_serialize', ['video']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Video id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $video = $this->Videos->get($id);
        if ($this->Videos->delete($video)) {
            $this->Flash->success(__('The video has been deleted.'));
        } else {
            $this->Flash->error(__('The video could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function change_status(){
        $this->autoRender = false;
        if($this->request->is('ajax') && isset($this->request->data['video_id']) && $this->request->data['video_id'] > 0){
            $video_id = $this->request->data['video_id'];
            $status = $this->request->data['status'];
            $video = $this->Videos->get($video_id);
            $video->status = $status;
            if($this->Videos->save($video)){
                echo '1';exit;
            }
        }
    }

    public function getVideoYoutube($id = ''){
        $result = array();
        if($id != ''){
            $http = new Client();
            $params = ['key'=>$this->APIKEY] ;
            $params['part'] = 'snippet,contentDetails';
            $params['type'] = 'video';
            $params['id'] = $id;

            $response = $http->get('https://www.googleapis.com/youtube/v3/videos',$params);
            if($response->isOk()){
                $json  = $response->body();
                $arr_response = json_decode($json,true);
                $result = $arr_response;
            }

        }
        return $result;
    }

    function convertDuration($duration = null){
        $hour = 0;
        $minute = 0;
        $second = 0;
        if($duration != ''){
            if(strpos($duration, 'H') > 0){
                $s = 2;
                $e = strpos($duration, 'H') - $s;
                $hour = substr($duration, $s, $e);
            }
            if(strpos($duration, 'M') > 0 && strpos($duration, 'H') > 0){
                $s = strpos($duration, 'H') + 1;
                $e = strpos($duration, 'M') - $s;
                $minute = substr($duration, $s, $e);
            }elseif(strpos($duration, 'M') > 0){
                $s = 2;
                $e = strpos($duration, 'M') - $s;
                $minute = substr($duration, $s, $e);
            }
            if(strpos($duration, 'S') > 0 && strpos($duration, 'M') > 0){
                $s = strpos($duration, 'M') + 1;
                $e = strpos($duration, 'S') - $s;
                $second = substr($duration, $s, $e);
            }elseif(strpos($duration, 'S') > 0){
                $s = 2;
                $e = strpos($duration, 'S') - $s;
                $second = substr($duration, $s, $e);
            }
            return $hour * 3600 + $minute * 60 + $second;
        }
        else{
            return 0;
        }
    }
}
