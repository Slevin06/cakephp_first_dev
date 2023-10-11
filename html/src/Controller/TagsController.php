<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Tag;
use App\Model\Table\TagsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Tags Controller
 *
 * @property TagsTable $Tags
 * @method Tag[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TagsController extends AppController
{
    /**
     * Index method
     *
     * @return void Renders view
     */
    public function index(): void
    {
        $tags = $this->paginate($this->Tags);

        $this->set(compact('tags'));
    }

    /**
     * View method
     *
     * @param string|null $id Tag id.
     * @return void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $id = null): void
    {
        $tag = $this->Tags->get($id, [
            'contain' => ['Articles'],
        ]);

        $this->set(compact('tag'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tag = $this->Tags->newEmptyEntity();
        if ($this->request->is('post')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The tag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }
        $articles = $this->Tags->Articles->find('list', ['limit' => 200])->all();
        $this->set(compact('tag', 'articles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tag id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $tag = $this->Tags->get($id, [
            'contain' => ['Articles'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The tag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }
        $articles = $this->Tags->Articles->find('list', ['limit' => 200])->all();
        $this->set(compact('tag', 'articles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tag id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tag = $this->Tags->get($id);
        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('The tag has been deleted.'));
        } else {
            $this->Flash->error(__('The tag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
