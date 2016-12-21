<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vouchers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Vouchers
 *
 * @method \App\Model\Entity\Voucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\Voucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Voucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Voucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VouchersTable extends Table
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

        $this->table('vouchers');
        $this->displayField('voucher_id');
        $this->primaryKey('voucher_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Vouchers', [
            'foreignKey' => 'voucher_id',
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
            ->allowEmpty('image_url');

        $validator
            ->integer('activate')
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
        $rules->add($rules->existsIn(['voucher_id'], 'Vouchers'));

        return $rules;
    }
}
