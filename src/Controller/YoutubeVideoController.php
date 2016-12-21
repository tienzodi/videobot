<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use Cake\Network\Http\Client;
use Cake\Core\Configure;

/**
 * YoutubeVideo Controller
 *
 * @property \App\Model\Table\YoutubeVideoTable $YoutubeVideo
 */
class YoutubeVideoController extends AppController
{
    public function initialize()
    {
        parent::initialize();
		$this->APIKEY = 'AIzaSyCqQ0YswuSkLe1gTzIIMXU2qP0FEQjob9o';
		$this->CategoriesList = Configure::read('YT_Category');
		//$this->CategoriesList =  array('1'=>'Film & Animation');
    }
	
	public function cronUpdateVideos(){
		exit();
		$this->loadModel('Videos');
		$http = new Client();
		$params = ['key'=>$this->APIKEY] ;
		$params['part'] = 'snippet';
		$params['order'] = 'date';
		$params['maxResults'] = '10';
		//$params['videoDuration'] = 'medium';
		$params['relevanceLanguage'] = 'en';
		$params['type'] = 'video';
		foreach($this->CategoriesList as $category_id=>$categoryName)
		{
			$params['videoCategoryId'] = $category_id;
			//$params['channelId'] = 'UCHtSaifphPxPJ5-TrOI0P7g';
			
			$video = $this->Videos->find('all', array('order' => array('video_published_at' => 'DESC')))->where(['category_id'=>$category_id]);
			$after_video_published_at = '';
			if(!empty($video->toArray())){
				$first_video = $video->first();
				$after_video_published_at = $first_video->video_published_at->format('Y-m-d H:i:s');
				$after_video_published_at = date(\DateTime::RFC3339, strtotime($after_video_published_at)); // 'Y-m-d\TH:i:sP'				
				$params['publishedAfter'] = $after_video_published_at;
				//$params['publishedBefore'] = $video_published_at;
			}
			else //from default
			{
				$after_video_published_at = date(\DateTime::RFC3339, strtotime('2016-08-02')); // 'Y-m-d\TH:i:sP'			
				$params['publishedAfter'] = $after_video_published_at;
			}
			echo '<br>******************* Start fetch : '.$categoryName.' ******************* <br>'.'video_published_at : '.$after_video_published_at.'<br>';
			
			//echo 'https://www.googleapis.com/youtube/v3/search',$params;
			$response = $http->get('https://www.googleapis.com/youtube/v3/search',$params);
			if($response->isOk())
			{
				$json  = $response->body();
				$arr_response = json_decode($json,true);
				$items = isset($arr_response['items']) ? $arr_response['items'] : array();
				$video_data = array();
				
				//pr($items);continue;
				foreach($items as $item){
					$video_published_at = isset($item['snippet']['publishedAt']) ? $item['snippet']['publishedAt'] : null;					
					if($video_published_at != null){
						$video_published_at = date('Y-m-d H:i:s', strtotime($video_published_at));
					}
					$video_data[$item['id']['videoId']] = array(
						'youtube_id' => $item['id']['videoId'],
						'category_id' => $category_id,
						'category_name' => $categoryName,
						'video_title' => $item['snippet']['title'],
						'video_thumb' => isset($item['snippet']['thumbnails']['high']['url']) ? $item['snippet']['thumbnails']['high']['url'] : '',
						'video_published_at' => $video_published_at,
						'video_channel_id' => isset($item['snippet']['channelId']) ? $item['snippet']['channelId'] : '',
						'status' => 0,
						'search_type' => 2,
						'activate' => 1,
					);
				}
				$video_data = array_reverse($video_data,false);
				if(!empty($video_data)){
					$arr_video_id = array_keys($video_data);
					$string_video_id = implode(',', $arr_video_id);
					$video_detail_params = array(
						'part' => 'snippet,contentDetails',
						'key' => $this->APIKEY,
						'fields' => 'items(id,contentDetails,snippet/categoryId)',
						'id' => $string_video_id
					);
					$response = $http->get('https://www.googleapis.com/youtube/v3/videos',$video_detail_params);
					if($response->isOk()){
						$json  = $response->body();
						$arr_response = json_decode($json,true);
						//pr($arr_response);exit;
						$items = isset($arr_response['items']) ? $arr_response['items'] : array();
						//pr($items);exit;
						foreach($items as $item){							
							$id = $item['id'];
							$category_yt_id = isset($item['snippet']['categoryId']) ? $item['snippet']['categoryId'] : '';
							$duration = $item['contentDetails']['duration'];
							$video_length = $this->convertDuration($duration);
							echo '<BR>'.$id.' -> '.($video_data[$id]['video_published_at']).' : '. ($video_data[$id]['video_title']);
							echo ' - '.mb_detect_encoding($video_data[$id]['video_title']) . ' - video_length : '.$video_length;							
							if( $video_length > 0)
							{
								$video_data[$id]['video_length'] = $video_length;
								$video_data[$id]['category_yt_id'] = $category_yt_id;
	
								$video = $this->Videos->newEntity();
								//$temp_video_title = $video_data[$id]['video_title'] ;
								//$video_data[$id]['video_title'] = utf8_encode($video_data[$id]['video_title']);
								$video = $this->Videos->patchEntity($video, $video_data[$id]);
								if($this->Videos->save($video)){
									echo ' &nbsp; (['.$video->video_id.'])';
									//$video->video_title = $temp_video_title;
									//echo $video->video_title;
									//$this->Videos->save($video);
								}
							}
						}
					}
				}
			}
			else
			{
				pr($response);
			}
			echo '<br>******************* End ************************** <br><br>';
		}
		exit();
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

	public function cronUpdateVideos_BK(){
		$http = new Client();
		$params = ['key'=>$this->APIKEY] ;
		$params['part'] = 'snippet,id';
		$params['order'] = 'date';
		$params['maxResults'] = '20';
		foreach($this->ChanelsList as $chanel)
		{
			//$params['channelId'] = $chanel;
			//$response = $http->get('https://www.googleapis.com/youtube/v3/search',$params);
			$params['id'] = $chanel;
			$response = $http->get('https://www.googleapis.com/youtube/v3/channels',$params);
			if($response->isOk())
			{
				$json  = $response->body();
				$arr_response = json_decode($json,true);
				pr($arr_response);
			}
			else
			{
				pr($response);
			}

		}
		exit();
	}
}
