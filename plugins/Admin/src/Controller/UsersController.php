<?php
namespace Admin\Controller;

use Admin\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Validation\Validator;
use Cake\Routing\Router;
/**
 * Users Controller
 *
 * @property \Admin\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	public $current_user = null;
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    
        $this->loadModel('LogUsers');
        $this->user_db = $this->Users->find('all');
        $this->session = $this->request->session();
        if($this->user_db->toArray())
        {
            $this->session->delete('User.CheckData');
            $this->Auth->allow(['login']);
        }
        else
        {
            $this->session->write('User.CheckData', 1);
            $this->Auth->allow(['add']);
        }
        if($this->session->check('User.CheckPassword')){
            $this->Auth->allow(['edit_password']);
        }
		$this->set('active_menu', 'users');
		$this->current_user = $this->Auth->user();

    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
//		if ($this->current_user['role'] != 'admin') {
//			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
//		}
          
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
//		if ($this->current_user['role'] != 'admin') {
//			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
//		}
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
//        if($this->session->check('User.CheckData'))
//        {
//            if ($this->current_user['role'] != 'admin') {
//                return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
//            }
//        }
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            
            $this->request->data['last_edit'] = Time::now();
            $user = $this->Users->patchEntity($user, $this->request->data);
            //$user->errors();
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                if($this->session->check('User.CheckData')){
                    $this->session->delete('User.CheckData');
                    return $this->redirect(['action' => 'login']);
                }
                else
                    return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
//		if ($this->current_user['role'] != 'admin') {
//			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
//		}        
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
//		if ($this->current_user['role'] != 'admin') {
//			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
//		}
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	public function login() {
        if($this->session->check('User.CheckData'))
        {
            return $this->redirect(['action' => 'add']);
        }
        else{
            if (isset($this->current_user)) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            if ($this->request->is('post')) {
                $user = $this->Auth->identify();
                if ($user) {
                    //$now = Time::now();
					$this->Auth->setUser($user);
                        return $this->redirect($this->Auth->redirectUrl());
						
					/*	
                    $last_edit = $user['last_edit'];
                    if (time() <= strtotime('+360 day', strtotime($last_edit)))
                    {
                        $this->Auth->setUser($user);
                        return $this->redirect($this->Auth->redirectUrl());
                    }
                    else
                    {
                        $this->Auth->allow(['edit_password']);
                       // $this->session->write('User.CheckPassword', 1);
                         $this->session->write('User.CheckPassword', $user);
                        return $this->redirect(['action' => 'edit_password']);
                    }
                    */
                    
                    //return $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->Flash->error( __('Username or password is incorrect') );
                }
             } 
        }
	}

	public function logout() {
	    return $this->redirect($this->Auth->logout());
	}
    public function check_passwordLog($id,$password,$old_password){
        $check = '';

        $correct = (new DefaultPasswordHasher)->check($password,$old_password);
        if($correct)
        {
            $check = true;
        }
        else
        {
            $LogUsers = $this->LogUsers->find('all', ['conditions' => ['user_id' =>  $id],'order' => ['LogUsers.created' => 'DESC'],'limit' =>10]);
            if(!empty($LogUsers)){
                foreach($LogUsers as $value)
                {
                   $correct = (new DefaultPasswordHasher)->check($password, $value->password);
                   if($correct)
                    {
                        $check = true;
                        return $check;
                    }
                    else
                    {
                        $check = false;
                    }
                }
            }
            else {
                $check = false;
            }
        }
        return $check;
    }
    public function change_password()
    {
        if(!$this->request->session()->check('Auth.User')){
          $this->redirect(['controller' => 'users', 'action' => 'login']);    
        }
        $id = $this->Auth->user('id');

        $user = $this->Users->get($id, [
            'fields' => ['password']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            //echo Security::hash('123456', 'blowfish', '$10$1nMmKt/iCxao7L06zl1Xa.Ykq5n1S7CvjovwSNkRU5q2fNH53BLIK');
            //echo $password = AuthComponent::password($this->request->data['User']['password']);
            $password = $this->request->data['password'];
            $new_password = $this->request->data['new_password'];
            $correct = (new DefaultPasswordHasher)->check($password, $user['password']);
            
            if($correct)
            {
                //check
                if($this->check_passwordLog($id,$new_password,$user['password']) == false)
                {
                    $user = $this->Users->get($id);  
                    $password_db = $user['password'];
                    $created = $user['created'];
                    $modified = $user['modified'];
                    $user->last_edit = Time::now();
                    $user->password = $new_password;


                    $validator = new Validator();
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
                    $errors = $validator->errors($this->request->data());
                    if (!empty($errors)) {

                        foreach ($errors as $value)
                        {
                            foreach($value as $rs){
                                $this->Flash->error( __($rs) );
                            }
                        }
                        
                    foreach ($errors as $value)
                    {
                        foreach($value as $rs){
                           $er = $rs;
                            //$this->Flash->error( __($rs) );
                        }
                         $this->Flash->error($er);
                    }                        

                    }
                    else
                    {
                        if($this->Users->save($user)){
     //                       $LogUsers = $this->LogUsers->find('all', ['conditions' => ['user_id' =>  $id],'limit' =>1]);
     //                       $data_logUser  = $LogUsers->toArray();
    //                        if(!empty($data_logUser))
    //                        {
    //                            $id_logUser = $data_logUser[0]['id'];
    //                            $LogUsers = $this->LogUsers->get($id_logUser);
    //                            $LogUsers->password  = $password_db;
    //                            $LogUsers->last_edit = Time::now();
    //                            $LogUsers->created = $created;
    //                            $LogUsers->modified = $modified;
    //                            if ($this->LogUsers->save($LogUsers)) {
    //                                $this->Flash->success(__('Change password successfully'));
    //                                $this->redirect(['action' => 'index']);                        
    //                            }                   
    //                        }
    //                        else
    //                        {
                                $LogUsers = $this->LogUsers->newEntity();
                                $LogUsers->user_id  = $id;
                                $LogUsers->password  = $password_db;
                                $LogUsers->last_edit = Time::now();
                                $LogUsers->created = $created;
                                $LogUsers->modified = $modified;
                                if ($this->LogUsers->save($LogUsers)) {
                                    $this->Flash->success(__('Change password successfully'));
                                    $this->redirect(['action' => 'index']);
                                }
                           // }
                       }
                       else
                       {
                            $this->Flash->error( __('The user could not be saved. Please, try again.'));
                       }
                    }
                }else
                {
                    $this->Flash->error( __('You had registered this password. Please, try again.'));
                }              
                  
            }
            else
            {
                $this->Flash->error( __('The old password is not correct.'));
            }
        }      
    }   
    public function edit_password()
    {
        if($this->request->session()->check('Auth.User')){
          $this->redirect(['controller' => 'users', 'action' => 'login']);    
        }
        else if(!$this->session->check('User.CheckPassword'))
        {
            $this->redirect(['controller' => 'users', 'action' => 'login']);    
        }        
        if ($this->request->is(['patch', 'post', 'put'])) {  
            $Session_user = $this->session->read('User.CheckPassword');
            $this->request->data['email'] = $Session_user['email'];
            $user = $this->Auth->identify();
            if ($user) {
                $validator = new Validator();
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
                $errors = $validator->errors($this->request->data());
               // pr($errors);
                $er = array();
                if (!empty($errors)) {
                    
                    foreach ($errors as $value)
                    {
                        foreach($value as $rs){
                            $this->Flash->error( __($rs) );
                        }
                    }
                   
                }
                else
                {
                    $user = $this->Users->get($user['id']);
                    $password_db = $user['password'];
                    $created = $user['created'];
                    $modified = $user['modified'];
                    $user->last_edit = Time::now();
                    $new_password = $this->request->data['new_password'];
                    $user->password = $new_password;
                    
                    if($this->check_passwordLog($user['id'],$new_password,$password_db) == false)
                    {
                          if($this->Users->save($user)){
                              $this->session->delete('User.CheckPassword');
      //                        $LogUsers = $this->LogUsers->find('all', ['conditions' => ['user_id' =>  $user['id']],'limit' =>1]);
      //                        $data_logUser  = $LogUsers->toArray();
      //                        if(!empty($data_logUser))
      //                        {
      //                            $id_logUser = $data_logUser[0]['id'];
      //                            $LogUsers = $this->LogUsers->get($id_logUser);
      //                            $LogUsers->password  = $password_db;
      //                            $LogUsers->last_edit = Time::now();
      //                            $LogUsers->created = $created;
      //                            $LogUsers->modified = $modified;
      //                            if ($this->LogUsers->save($LogUsers)) {
      //                                $this->Flash->success(__('Change password successfully'));
      //                                $this->redirect(['action' => 'index']);                        
      //                            }                   
      //                        }
      //                        else
      //                        {
                                  $LogUsers = $this->LogUsers->newEntity();
                                  $LogUsers->user_id  = $user['id'];
                                  $LogUsers->password  = $password_db;
                                  $LogUsers->last_edit = Time::now();
                                  $LogUsers->created = $created;
                                  $LogUsers->modified = $modified;
                                  if ($this->LogUsers->save($LogUsers)) {
                                      $this->Flash->success(__('Change password successfully'));
                                      $this->redirect(['action' => 'index']);
                                  }
                             // }   
                          }
                          else
                          {
                              $this->Flash->error( __('The user could not be saved. Please, try again.'));
                          }                          

                    }
                    else{
                        $this->Flash->error( __('You had registered this password. Please, try again.'));
                    }
                }  
            }
            else
            {
                $this->Flash->error( __('Email or Password is not correct.'));
            }
           
        }      
    }       
	public function extendSession(){
        $this->autoRender = false;	
        $response = array();
	    $response = array('SessionExprieIn' => $this->createSessionExprie());		
        $this->response->body(json_encode($response));
        $this->response->type('json');
        return $this->response;
	}	
	public function sessionLogout(){
        $this->autoRender = false;	
        $response = array();
		$this->Auth->logout();
		$response = array('status' => 'ok');
        $this->response->body(json_encode($response));
        $this->response->type('json');
        return $this->response;		
	}
	public function validateSessionExprie() 
	{
		$SessionExprieTime = $this->request->session()->read('Admin.SessionExprieTime');
		$SessionExprieIn = $SessionExprieTime - time();
		$response = array('SessionExprieIn'=>$SessionExprieIn,'SessionExprieTime'=>$SessionExprieTime,'time'=>time());
		$this->response->body(json_encode($response));
        $this->response->type('json');
        return $this->response;
	}     

}
