<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Video Entity
 *
 * @property int $video_id
 * @property string $youtube_id
 * @property int $category_id
 * @property string $category_name
 * @property string $video_title
 * @property string $video_thumb
 * @property int $video_length
 * @property \Cake\I18n\Time $video_published_at
 * @property string $video_channel_id
 * @property int $status
 * @property bool $search_type
 * @property bool $activate
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Video $video
 */
class Video extends Entity
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
        'video_id' => false
    ];
}
