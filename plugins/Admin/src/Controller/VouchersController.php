<?php
namespace Admin\Controller;

use Admin\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
/**
 * Vouchers Controller
 *
 * @property \Admin\Model\Table\VouchersTable $Vouchers
 */
class VouchersController extends AppController
{
    public $upload_dir = "files/upload/vouchers/";
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $vouchers = $this->paginate($this->Vouchers);

        $this->set(compact('vouchers'));
        $this->set('_serialize', ['vouchers']);
    }

    /**
     * View method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $voucher = $this->Vouchers->get($id);

        $this->set('voucher', $voucher);
        $this->set('_serialize', ['voucher']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Vouchers');
        $voucher = $this->Vouchers->newEntity();
        if ($this->request->is('post')) {
            $voucher = $this->Vouchers->patchEntity($voucher, $this->request->data);
            $image_url = isset($this->request->data['image_url']) ? $this->request->data['image_url'] : '';
            unset($this->request->data['image_url']);
            if ($this->Vouchers->save($voucher)) {
                $voucher_id = $voucher->voucher_id;

                if($image_url != ''){
                    $upload_link = $image_url;
                    $path_image = '';
                    if(file_exists($image_url))
                    {
                        $name_file = basename($upload_link);
                        $path_parts = pathinfo($name_file);
                        $rand = rand(1000,99999999);
                        $extension = '_'.$rand.'.'.$path_parts['extension'];

                        if(!is_dir($this->upload_dir))
                            $upload_dir = new Folder(WWW_ROOT . $this->upload_dir, true, 0777);
                        $dir = new Folder(WWW_ROOT . $this->upload_dir.$voucher_id, true, 0777);

                        copy($upload_link, $this->upload_dir.$voucher_id.'/'.$path_parts['filename'].$extension);
                        $path_image = $this->upload_dir.$voucher_id.'/'.$path_parts['filename'].$extension;
                        unlink($upload_link);

                        $thumb_image = str_replace($name_file, 'thumbnail/'.$name_file, $upload_link);
                        unlink($thumb_image);
                    }
                    $voucher->image_url = $path_image;
                    $this->Vouchers->save($voucher);
                }

                $this->Flash->success(__('The voucher has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
            }
        }
        $vouchers = $this->Vouchers->Vouchers->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'vouchers'));
        $this->set('_serialize', ['voucher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Vouchers');
        $voucher = $this->Vouchers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $image_url = isset($this->request->data['image_url']) ? $this->request->data['image_url'] : '';
            unset($this->request->data['image_url']);
            $voucher = $this->Vouchers->patchEntity($voucher, $this->request->data);
            if ($this->Vouchers->save($voucher)) {
                $voucher_id = $id;
                if($image_url != ''){
                    $upload_link = $image_url;
                    $path_image = '';
                    if(file_exists($image_url))
                    {
                        $name_file = basename($upload_link);
                        $path_parts = pathinfo($name_file);
                        $rand = rand(1000,99999999);
                        $extension = '_'.$rand.'.'.$path_parts['extension'];

                        if(!is_dir($this->upload_dir))
                            $upload_dir = new Folder(WWW_ROOT . $this->upload_dir, true, 0777);
                        $dir = new Folder(WWW_ROOT . $this->upload_dir.$voucher_id, true, 0777);

                        copy($upload_link, $this->upload_dir.$voucher_id.'/'.$path_parts['filename'].$extension);
                        $path_image = $this->upload_dir.$voucher_id.'/'.$path_parts['filename'].$extension;

                        unlink($upload_link);
                        $thumb_image = str_replace($name_file, 'thumbnail/'.$name_file, $upload_link);
                        unlink($thumb_image);
                    }
                    $voucher->image_url = $path_image;
                    $this->Vouchers->save($voucher);
                }


                $this->Flash->success(__('The voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The voucher could not be saved. Please, try again.'));
            }
        }
        $vouchers = $this->Vouchers->Vouchers->find('list', ['limit' => 200]);
        $this->set(compact('voucher', 'vouchers'));
        $this->set('_serialize', ['voucher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Voucher id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $voucher = $this->Vouchers->get($id);
        if ($this->Vouchers->delete($voucher)) {
            $folder_image = $this->upload_dir.$id;
            if(file_exists($folder_image))
            {
                $this->rrmdir($folder_image);
                @rmdir($folder_image);
            }
            $this->Flash->success(__('The voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    function rrmdir($dir) {
        $this->autoRender = false;
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
