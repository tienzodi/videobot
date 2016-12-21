<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VideosSuggests Model
 *
 * @property \Cake\ORM\Association\BelongsTo $VideoSuggests
 * @property \Cake\ORM\Association\BelongsTo $Videos
 * @property \Cake\ORM\Association\BelongsTo $FbUsers
 *
 * @method \App\Model\Entity\VideosSuggest get($primaryKey, $options = [])
 * @method \App\Model\Entity\VideosSuggest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VideosSuggest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VideosSuggest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VideosSuggest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VideosSuggest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VideosSuggest findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VideosSuggestsTable extends Table
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

        $this->table('videos_suggests');
        $this->displayField('video_suggest_id');
        $this->primaryKey('video_suggest_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('VideoSuggests', [
            'foreignKey' => 'video_suggest_id',
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
            ->allowEmpty('video_suggest_uid');

        $validator
            ->integer('viewed')
            ->allowEmpty('viewed');

        $validator
            ->dateTime('viewed_date')
            ->allowEmpty('viewed_date');

        $validator
            ->allowEmpty('viewed_logs');

        $validator
            ->integer('video_end')
            ->allowEmpty('video_end');

        $validator
            ->dateTime('video_end_date')
            ->allowEmpty('video_end_date');

        $validator
            ->allowEmpty('video_end_logs');

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
        //$rules->add($rules->existsIn(['video_suggest_id'], 'VideoSuggests'));

        return $rules;
    }
}
