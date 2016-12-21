<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VideosSuggest Entity
 *
 * @property int $video_suggest_id
 * @property string $video_suggest_uid
 * @property int $video_id
 * @property string $fb_user_id
 * @property int $viewed
 * @property \Cake\I18n\Time $viewed_date
 * @property string $viewed_logs
 * @property int $video_end
 * @property \Cake\I18n\Time $video_end_date
 * @property string $video_end_logs
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\VideoSuggest $video_suggest
 * @property \App\Model\Entity\Video $video
 * @property \App\Model\Entity\FbUser $fb_user
 */
class VideosSuggest extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'video_suggest_id' => false
    ];
}
