<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Log\Log;
use Cake\Network\Http\Client;
use Cake\Utility\Text;
use Cake\I18n\Time;
use Cake\Routing\Router;

class BotController extends AppController
{
    private $verify_token = 'RCJWIgCDM8lyWGy';
    private $page_access_token = 'EAAPEdz0FSKEBAOPAaul5LRmNpzkbg9r8p1HVPL7ukIZAPikit0ub7Gyv7GuSnZCVeIfXFQG1sIaOVqqmtwIGtqFfZCGHZCY5xBw25ydYMMXzEZBSlK8NNMtIKRXWBhnHA6w84tt5GluUOZAElYelZBw63lJfgmLkEeZAUSRUxZBEB9QZDZD';

    private $timo_address = "\n https://www.google.com/maps/place/Timo+Hangout/@10.7797821,106.6963925,19.08z/data=!4m5!3m4!1s0x0:0xee86b2b787893e50!8m2!3d10.7818799!4d106.694577";

    public function settingGreeting()
    {
        //echo $this->_processText('phut 20  phút');
        //echo $this->_processText('ko co');
        //echo $this->_findKeyWords('hoi Số tài 1khoản cua toi Cà');
        exit();
        $message = [
            'setting_type' => 'greeting',
            'greeting' => ["text" => "Welcome to Timo!"]
        ];

        $message_start_button = array(
            "setting_type" => "call_to_actions",
            "thread_state" => "new_thread",
            "call_to_actions" => array(["payload" => "USER_STARS_CHATTING"])
        );

        $http = new Client();
        $response = $http->post('https://graph.facebook.com/v2.6/me/thread_settings?access_token=' . $this->page_access_token, json_encode($message), ['type' => 'json']);
        //$response = $http->post('https://graph.facebook.com/v2.6/me/thread_settings?access_token='.$this->page_access_token,json_encode($message_start_button),['type'=>'json']);
        if ($response->isOk()) {
            $json = $response->body();
            $arr_response = json_decode($json, true);
            pr($arr_response);
        } else {
            pr($response->body());
        }
        exit();
    }

    public function webhook()
    {
        //Log::write('debug','Log Received webhook data: '.print_r($this->request,true));
        // initial verification
        if (!$this->request->is('post')) {
            $token = $this->request->query('hub_verify_token');
            if ($token && $token == $this->verify_token) {
                $content = $this->request->query('hub_challenge');
                Log::write('debug', 'Received challenge: ' . $content);
                echo $content;
            } else {
                Log::write('debug', 'Missing/invalid verify token on webhook callback.');
            }
            // webhook itself
        } else {
            Log::write('debug', 'Received webhook data: ' . print_r($this->request->data, true));
            $data = $this->request->data;

            if ($data['object'] == 'page') {
                // loop all entries
                foreach ($data['entry'] as $entry) {
                    $page_id = $entry['id'];
                    $time = $entry['time'];

                    // check all entries
                    foreach ($entry['messaging'] as $event) {
                        // check event type
                        if (isset($event['option'])) {
                            // optin event (authentication)
                        } elseif (isset($event['message'])) {
                            // message event
                            $this->_receivedMessage($event);
                        } elseif (isset($event['delivery'])) {
                            // delivery event
                        } elseif (isset($event['postback'])) {
                            // postback event
                            $this->_receivedPostback($event);
                        }
                    }
                }
            }

            echo 'OK';
        }
        exit();
    }

    //fb Send APIS [Start]
    private function _sendmessage($recipient, $msg)
    {
        $message = [
            'recipient' => [
                'id' => $recipient
            ],
            'message' => $msg
        ];
        $http = new Client();
        $response = $http->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->page_access_token, json_encode($message), ['type' => 'json']);
        if (!$response->isOk()) {
            Log::write('debug', 'Message posting failed! ' . print_r($response, true));
        }
    }

    private function _sendButton($recipient, $msg)
    {
        $message = [
            'recipient' => [
                'id' => $recipient
            ],
            'message' => $msg
        ];
        $http = new Client();
        $response = $http->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->page_access_token, json_encode($message), ['type' => 'json']);
        if (!$response->isOk()) {
            Log::write('debug', 'Message posting failed! ' . print_r($response, true));
        }
    }

    private function _sendGeneric($recipient, $msg)
    {
        $message = [
            'recipient' => [
                'id' => $recipient
            ],
            'message' => $msg
        ];
        $http = new Client();
        $response = $http->post('https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->page_access_token, json_encode($message), ['type' => 'json']);
        if (!$response->isOk()) {
            Log::write('debug', 'Message posting failed! ' . print_r($response, true));
        }
    }
    //fb Send APIS [End]

    //message processing [Start]
    private function _receivedMessage($event)
    {
        if (isset($event['message']) && isset($event['message']['text'])) {
            $sender = $event['sender']['id'];
            $text = $event['message']['text'];
            $this->_processUserMessage($sender, $text);
        }
    }

    private function _processUserMessage($sender, $message)
    {
        if ($message == 'Bliss_Demo_Button_Template') {
            $message = array(
                'attachment' => array(
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => 'button Demo',
                        'buttons' => [
                            [
                                "type" => "web_url",
                                "url" => "http://bliss-interactive.com/",
                                "title" => "Bliss Website"
                            ],
                            [
                                "type" => "web_url",
                                "url" => "https://happiness-saigon.com/",
                                "title" => "HS Website"
                            ],
                            [
                                "type" => "postback",
                                "title" => "Start Chatting",
                                "payload" => "phone_number"
                            ]
                        ]
                    ]
                )
            );
            $this->_sendButton($sender, $message);
        } else if ($message == 'Bliss_Demo_SendGeneric') {
            $this->_sendmessage($sender, ['text' => 'Your Message . Test messgae']);
            $message = array(
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => "Welcome to Peter's Hats",
                                "image_url" => "http://petersapparel.parseapp.com/img/item100-thumb.png",
                                "subtitle" => "We've got the right hat for everyone.",
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => "https://petersapparel.parseapp.com/view_item?item_id=100",
                                        "title" => "View Website"
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "Start Chatting",
                                        "payload" => "USER_DEFINED_PAYLOAD"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
            $this->_sendGeneric($sender, $message);
        } else if ($message == 'Bliss_Demo_SendImage') {
            $message = array(
                "attachment" => [
                    "type" => "image",
                    "payload" => ["url" => "http://petersapparel.parseapp.com/img/item100-thumb.png"]
                ]
            );
            $this->_sendmessage($sender, $message);
        } else if ($message == 'Hi my Bot,') {
            $http = new Client();
            $response = $http->get('https://graph.facebook.com/v2.6/' . $sender . '?access_token=' . $this->page_access_token);
            if ($response->isOk()) {
                $json = $response->body();
                $arr_response = json_decode($json, true);
                $first_name = $arr_response['first_name'];
                $last_name = $arr_response['last_name'];
                $text = "Hi " . $first_name . '!';
            } else {
                $text = "Error !";
            }
            $this->_sendmessage($sender, ['text' => $text]);
        } else if ($message == 'Bliss_Demo_Send_Message') {
            $this->_sendmessage($sender, ['text' => 'Your Message :' . $message]);
        } else {
            $free_minute = $this->_processText($message);
            if ($free_minute == '' || (is_numeric($free_minute) && $free_minute == 0)) //zero message
            {
                $message_str = "Thật bất ngờ! Bạn bận đến mức không còn thời gian giải trí. Đừng lo, trợ thủ Ngân hàng số Timo sẽ giúp bạn giao dịch nhanh chóng, tiện lợi, mọi lúc mọi nơi và tiết kiệm được nhiều thời gian hơn. Timo đi! https://timo.vn ";
                $message = array(
                    'attachment' => array(
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'button',
                            'text' => $message_str,
                            'buttons' => [
                                [
                                    "type" => "web_url",
                                    "url" => "https://timo.vn/",
                                    "title" => "Tìm hiểu về Timo",
                                ],
                                [
                                    "type" => "web_url",
                                    "url" => "https://www.facebook.com/messages/yourtimo",
                                    "title" => "Chat với Timo Care"
                                ],
                                [
                                    "type" => "postback",
                                    "title" => "Cho Timo 1' nhé",
                                    "payload" => "USER_GIVE_TIMO_1_MINUTE"
                                ]
                            ]
                        ]
                    )
                );
                $this->_sendButton($sender, $message);
            } else {
                if (is_numeric($free_minute) && $free_minute > 0)  //valid message
                {
                    $result_findVideo = $this->_saveVideoSuggest($sender, $free_minute);
                    if (!empty($result_findVideo)) {
                        $youtube_url = $result_findVideo['youtube_url'];
                        $video_data = $result_findVideo['video_data'];
                        //$this->_sendmessage($sender,['text'=>$video_url]);
                        $message_str = $youtube_url;
                        /*$message = array(
                                            'attachment'=>array(
                                                                'type' => 'template',
                                                                'payload' => [
                                                                                'template_type'=>'button',
                                                                                'text'=>$message_str,
                                                                                'buttons' =>[
                                                                                                [
                                                                                                    "type" => "web_url",
                                                                                                    "url"  =>  "https://www.facebook.com/messages/yourtimo",
                                                                                                    "title" => "Chat với Timo Care"
                                                                                                ],
                                                                                                [
                                                                                                    "type" => "phone_number",
                                                                                                    "title"  =>  "Hotline 1800 6788",
                                                                                                    "payload" => "+8418006788"
                                                                                                ],
                                                                                                [
                                                                                                    "type" => "postback",
                                                                                                    "title"  =>  'Xem thêm Video',
                                                                                                    "payload" => "USER_SHOW_MORE_VIDEOS"
                                                                                                ]
                                                                                            ]
                                                                             ]
                                                                )
                                        );
                        $this->_sendButton($sender,$message);*/
                        $message = array(
                            "attachment" => [
                                "type" => "template",
                                "payload" => [
                                    "template_type" => "generic",
                                    "elements" => [
                                        [
                                            "title" => $video_data->video_title,
                                            "image_url" => $video_data->video_thumb,
                                            "item_url" => $message_str,
                                            "subtitle" => '',
                                            "buttons" => [
                                                [
                                                    "type" => "web_url",
                                                    "url" => "https://timo.vn/",
                                                    "title" => "Tìm hiểu về Timo",
                                                ],
                                                [
                                                    "type" => "web_url",
                                                    "url" => "https://www.facebook.com/messages/yourtimo",
                                                    "title" => "Chat với Timo Care"
                                                ],
                                                [
                                                    "type" => "postback",
                                                    "title" => 'Xem thêm Video',
                                                    "payload" => "USER_SHOW_MORE_VIDEOS"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        );
                        $this->_sendGeneric($sender, $message);

                    } else {
                        $this->_sendmessage($sender, ['text' => "Rất tiếc, Timo không tìm thấy video cho bạn!"]);
                    }
                } else // messages cases
                {
                    $result_find_message = $this->_findKeyWords($message);
                    if ($result_find_message == 1) {
                        $this->_sendmessage($sender, ['text' => "Hôm nay có gì vui! Bạn đang rảnh bao nhiêu phút lúc này?  (VD: 2 phút, 2 phut, 2')"]);
                    } else if ($result_find_message == 2) {
                        $this->_sendmessage($sender, ['text' => "Thật là vui quá! Cám ơn bạn đã dành thời gian cho Timo. Nhớ mời bạn bè cùng tham gia Timo nhé. "]); //.$this->timo_address
                        $image_url = Router::url('/', true) . "images/Timo_Social_Invites.jpg";
                        $message = array(
                            "attachment" => [
                                "type" => "image",
                                "payload" => ["url" => $image_url]
                            ]
                        );
                        $this->_sendmessage($sender, $message);

                    } else {
                        $this->_sendmessage($sender, ['text' => "Hix, Bạn nói gì Timo chưa hiểu lắm. Nhắn lại số phút theo mẫu nhé: 1 phút, 1 phut, 1'"]);
                    }

                }
            }
        }
    }

    private function _processText($message)
    {
        $result = '';
        $message = preg_replace('/(\s)|(min)|(minute)|(minutes)|(phút)|(phut)|(\')/', '', $message);
        $result = $message;
        return $result;
    }
    //message processing [End]

    //Postback [Start]
    private function _receivedPostback($event)
    {
        $sender = $event['sender']['id'];
        $payload = $event['postback']['payload'];
        $this->_processPostback($sender, $payload);

    }

    private function _processPostback($sender, $payload)
    {
        if ($payload == 'USER_STARS_CHATTING') {
            $this->_sendmessage($sender, ['text' => "Bạn ơi, đang rảnh đúng không, bao nhiêu phút vậy? Điều bất ngờ thú vị sẽ đến ngay đấy. (VD: 5 phút, 5 phut, 5')"]);
        } else if ($payload == 'USER_SHOW_MORE_VIDEOS') {
            $this->_sendmessage($sender, ['text' => "Woohoo! Bạn vẫn còn thời gian rảnh đúng không, bao nhiêu phút vậy?"]);
        } else if ($payload == 'USER_GIVE_TIMO_1_MINUTE') {
            $video_title = '45s tìm hiểu Timo - Ngân hàng số thế hệ mới';
            $video_url = 'https://www.youtube.com/watch?v=fYfNi5s0OVg';
            $video_image = 'https://i.ytimg.com/vi/fYfNi5s0OVg/hqdefault.jpg';
            $message = array(
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => $video_title,
                                "image_url" => $video_image,
                                "item_url" => $video_url,
                                "subtitle" => $video_url,
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => $video_url,
                                        "title" => "Xem video"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
            $this->_sendGeneric($sender, $message);
        } else {
            $this->_sendmessage($sender, ['text' => "Invalid payload : " . $payload]);
        }
    }

    //Postback [End]

    private function _findKeyWords($string)
    {
        $result = 0;
        $string = mb_convert_case($string, MB_CASE_LOWER, 'utf-8');
        //check Re-greeting message
        $re_greeting_words = array("hello", "hi", "xin chao", "xin chào", "chào", "chao", "hello timo", "hi timo", "xin chao timo", "xin chào timo", "chào timo", "chao timo");
        if (in_array($string, $re_greeting_words)) //Re-greeting message
        {
            return 1;
        } else //service	message
        {
            $services_words = array('Chuyển tiền', 'Đơn giản', 'Rút tiền', 'Danh sách giao dịch', 'Đề xuất thông minh', 'Miễn phí giao dịch', 'Tiện lợi', 'Miễn phí rút tiền', 'Goal Save', 'Tài khoản thanh toán', 'Giao dịch', 'Timo Hangout', 'ATM', 'Kế hoạch tiết kiệm', 'Vui vẻ', 'Tiết kiệm', 'Timo', 'ATM toàn quốc', 'Lãi suất không kỳ hạn', 'Quan tâm', 'Tiết kiệm thông minh', 'Tư vấn', 'Miễn phí nạp tiền', 'Tiết kiệm có kỳ hạn', 'Tự do', 'Tiết kiệm thời gian', 'Banking', 'Chuyển tiền', 'Thủ túc không rườm rà', 'Chủ động', 'Ngân hàng số', 'Tài khoản', 'Nhanh chóng', 'Lãi suất đa năng', 'An toàn', 'Quản lý tài chính', 'Thanh toán', 'Thanh toán hóa đơn', 'Điều chỉnh kế hoạch tiết kiệm', 'Digital banking', 'Bảo mật', 'Chuyển tiền nội mạng', 'Mọi lúc mọi nơi', 'Bảo mật tối đa', 'Internet banking', 'Miễn phí', 'Liên ngân hàng', 'Nạp tiền điện thoại', 'Rườm rà', 'Dễ dàng', 'Số tài khoản', 'Thanh toán quốc tế', 'Email', 'Số thẻ', 'VP Bank', 'Nhận thẻ', 'Mất phí', 'Nhận thẻ', 'Vé xem phim', 'Phí hàng tháng', 'Miễn phí', 'Mất thẻ', 'Nạp tiền', 'Phí thường niên', 'Mobile banking', 'Lỗi ứng dụng', 'Ngân hàng', 'Phí duy trì', 'Thẻ tín dụng', 'CMND', 'Cà thẻ', 'Số dư', 'Thẻ ghi nợ', 'Mùa hàng online', 'Mật khẩu', 'Quà tặng', 'Thẻ Credit', 'Mua hàng nước ngoài', 'Ứng dụng', 'Thẻ Master', 'Thẻ Debit', 'Tổng đài', 'Đăng nhập', 'Thẻ Visa', 'Ví Timo');
            $services_words_string = '(' . implode(")|(", $services_words) . ')';
            $services_words_string = mb_convert_case($services_words_string, MB_CASE_LOWER, 'utf-8');
            //echo $services_words_string;
            if (preg_match('/' . $services_words_string . '/', $string)) {
                return 2;
            }
        }
        return 0; //no service
    }

    private function _findVideo($duration = null, $fb_user_id = null)
    {
        $range = [30, 60, 60];
        $video = array();
        if ($duration > 0) {
            $this->loadModel('Videos');

            $arr_video_id_except = array();
            if ($fb_user_id != '') {
                $this->loadModel('VideosSuggests');
                $options = array('conditions' => array('fb_user_id' => $fb_user_id));
                $video_suggests = $this->VideosSuggests->find('list', [
                    'keyField' => 'video_suggest_id',
                    'valueField' => 'video_id'
                ], $options);
                if (!empty($video_suggests->toArray())) {
                    $video_id_except = $video_suggests->toArray();
                    $arr_video_id_except = array_values($video_id_except);
                }
            }
            $video = $this->_doFindVideo($duration, $arr_video_id_except);
        }
        return $video;
    }

    private function _doFindVideo($duration = null, $arr_video_id_except = array())
    {
        $range = [0, 30, 60, 60];
        $video = array();
        if ($duration > 0) {
            $this->loadModel('Videos');

            $vRange = 0;
            $i = 1;
            foreach ($range as $range_value) {
                $max = $duration - $vRange;
                if ($i++ == count($range)) {
                    $min = 0;
                } else {
                    $min = max(0, ($max - $range_value));
                }
                //echo '('.$min.'-'.$max.')<br/>';
                $options = array(
                    'conditions' => array(
                        'video_length >=' => $min,
                        'video_length <=' => $max,
                        'status ' => 1
                    ),
                    'limit' => 1
                );

                if ($range_value == 0 || $range_value == 30) {
                    $options['order'] = 'rand()';
                } else {
                    $options['order'] = array('video_length' => 'desc');
                }

                if (!empty($arr_video_id_except)) {
                    $options['conditions'][] = array('video_id NOT IN' => $arr_video_id_except);
                }
                $videos = $this->Videos->find('all', $options);
                if (!empty($videos->toArray())) {
                    $video = $videos->first();
                    return $video;
                }
                $vRange += $range_value;
            }
            if (empty($video)) {
                $video = $this->_doFindVideoRepeat();
            }
        }
        return $video;
    }

    private function _doFindVideoRepeat()
    {
        $video = array();
        $this->loadModel('VideosSuggests');
        $this->loadModel('Videos');
        $options = array(
            'order' => array('video_suggest_count' => 'asc'),
            'limit' => 1
        );
        $videos_suggests = $this->VideosSuggests->find('all', $options);
        if (!empty($videos_suggests->toArray())) {
            $videos_suggest = $videos_suggests->first();
            $video_id = $videos_suggest->video_id;
            $video = $this->Videos->get($video_id);
        }
        return $video;
    }

    public function test()
    {
        $this->autoRender = false;
        $free_minute = 2;
        $fb_user_id = 'test2';
        $video_data = $this->_saveVideoSuggest($fb_user_id, $free_minute);
        pr($video_data);
        exit;
    }

    function _saveVideoSuggest($fb_user_id = null, $free_minute = 0)
    {
        $free_second = $free_minute * 60;
        $video = $this->_findVideo($free_second, $fb_user_id);
        $arr_result = array();
        if (!empty($video) && $fb_user_id != '') {
            $youtube_id = $video->youtube_id;
            $video_id = $video->video_id;

            $this->loadModel('VideosSuggests');
            $options = array(
                'conditions' => array(
                    'fb_user_id' => $fb_user_id,
                    'video_id' => $video_id
                )
            );
            $videos_suggests = $this->VideosSuggests->find('all', $options);
            if (!empty($videos_suggests->toArray())) {
                $video_suggest = $videos_suggests->first();
                $video_suggest->video_suggest_count += 1;
            } else {
                $video_suggest_data = array(
                    'video_suggest_uid' => Text::uuid(),
                    'request_time' => $free_minute,
                    'video_id' => $video_id,
                    'fb_user_id' => $fb_user_id,
                    'viewed' => 0,
                    'viewed_date' => null,
                    'video_suggest_count' => 1
                );
                $video_suggest = $this->VideosSuggests->newEntity();
                $video_suggest = $this->VideosSuggests->patchEntity($video_suggest, $video_suggest_data);
            }

            if ($this->VideosSuggests->save($video_suggest)) {
                //$play_video = Router::url('/', true) . 'playVideo/' . $video_suggest->video_suggest_uid;
                $arr_result['youtube_url'] = 'https://www.youtube.com/watch?v=' . $youtube_id;
                $arr_result['video_data'] = $video;
                //echo $play_video;exit;
                $this->loadModel('Videos');
                $this->Videos->updateTotalSuggest($video_id);
            }
        }
        return $arr_result;
    }

}

?>