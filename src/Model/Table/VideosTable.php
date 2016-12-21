<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Videos Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Videos
 * @property \Cake\ORM\Association\BelongsTo $Youtubes
 * @property \Cake\ORM\Association\BelongsTo $Categories
 * @property \Cake\ORM\Association\BelongsTo $VideoChannels
 *
 * @method \App\Model\Entity\Video get($primaryKey, $options = [])
 * @method \App\Model\Entity\Video newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Video[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Video|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Video patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Video[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Video findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VideosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('videos');
        $this->displayField('video_id');
        $this->primaryKey('video_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Videos', [
            'foreignKey' => 'video_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('category_name');

        $validator
            ->allowEmpty('video_title');

        $validator
            ->allowEmpty('video_thumb');

        $validator
            ->integer('video_length')
            ->allowEmpty('video_length');

        $validator
            ->dateTime('video_published_at')
            ->allowEmpty('video_published_at');

        $validator
            ->integer('status')
            ->allowEmpty('status');

        $validator
            ->integer('search_type')
            ->allowEmpty('search_type');

        $validator
            ->boolean('activate')
            ->allowEmpty('activate');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['video_id'], 'Videos'));
		$rules->add($rules->isUnique(['youtube_id']));
        return $rules;
    }

    public function updateTotalSuggest($video_id = null){
        if($video_id > 0){
            $VideosSuggests = TableRegistry::get('VideosSuggests');
            $video_suggests = $VideosSuggests->find('all', array(
                'conditions' => array('video_id' => $video_id)
            ));
            $total_suggest = $video_suggests->count();
            $video = $this->get($video_id);
            $video->total_suggest = $total_suggest;
            $this->save($video);
        }
    }
}
