<?php
namespace Admin\Model\Table;

use Admin\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 */
class UsersTable extends Table
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

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->notEmpty('firstname' ,'Please fill this field');

        $validator
            ->notEmpty('lastname','Please fill this field');

        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->notEmpty('email','Please fill this field');

        $validator
            ->requirePresence('password', 'create')
            ->add('password', [
                    'length' => [
                        'rule' => ['minLength', 8],
                        'message' => 'Password need to be at least 8 characters long',
                    ],
                    'letter' => [
                        'rule' => ['custom','/[a-z]+/'],
                        'message' => 'Password need to be at least one lowercase letter',
                    ],
                    'capital' => [
                        'rule' => ['custom','/[A-Z]+/'],
                        'message' => 'Password need to be at least one uppercase  letter',
                    ],
                    'number' => [
                        'rule' => ['custom','/[0-9]+/'],
                        'message' => 'Password need to be at least one number',
                    ]             
                ])
            ->notEmpty('password','Please fill password');
        $validator
            ->add('is_active', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_active');

        $validator
            ->allowEmpty('role');

        $validator
            ->add('last_login', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('last_login');

        return $validator;
    }
    
    public function validationChangePassword(Validator $validator)
    {
        $validator
         ->requirePresence('new_password', 'create')
         ->add('new_password', [
                 'length' => [
                     'rule' => ['minLength', 8],
                     'message' => 'Password need to be at least 8 characters long',
                 ],
                 'letter' => [
                     'rule' => ['custom','/[a-z]+/'],
                     'message' => 'Password need to be at least one lowercase letter',
                 ],
                 'capital' => [
                     'rule' => ['custom','/[A-Z]+/'],
                     'message' => 'Password need to be at least one uppercase  letter',
                 ],
                 'number' => [
                     'rule' => ['custom','/[0-9]+/'],
                     'message' => 'Password need to be at least one number',
                 ]             
        ])
        ->notEmpty('new_password','Please fill new password');   
        $validator
            ->add('reenter_new_password',
                'compareWith', [
                    'rule' => ['compareWith', 'new_password'],
                    'message' => 'Reenter new Passwords not equal.'
                ]
        )
        ->notEmpty('reenter_new_password','Please fill reenter new password');  
        return $validator;
    }    
    public function validationEditPassword(Validator $validator)
    {
        $validator
         ->requirePresence('new_password', 'create')
         ->add('new_password', [
                 'length' => [
                     'rule' => ['minLength', 8],
                     'message' => 'Password need to be at least 8 characters long',
                 ],
                 'letter' => [
                     'rule' => ['custom','/[a-z]+/'],
                     'message' => 'Password need to be at least one lowercase letter',
                 ],
                 'capital' => [
                     'rule' => ['custom','/[A-Z]+/'],
                     'message' => 'Password need to be at least one uppercase  letter',
                 ],
                 'number' => [
                     'rule' => ['custom','/[0-9]+/'],
                     'message' => 'Password need to be at least one number',
                 ]             
        ])
        ->notEmpty('new_password','Please fill new password');   
        $validator
            ->add('reenter_new_password',
                'compareWith', [
                    'rule' => ['compareWith', 'new_password'],
                    'message' => 'Reenter new Passwords not equal.'
                ]
         )
        ->notEmpty('reenter_new_password','Please fill reenter new password'); 
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
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
