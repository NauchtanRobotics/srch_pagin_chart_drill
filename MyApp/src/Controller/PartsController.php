<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Parts Controller
 *
 * @property \App\Model\Table\PartsTable $Parts
 *
 * @method \App\Model\Entity\Part[] paginate($object = null, array $settings = [])
 */
class PartsController extends AppController
{
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];
    
    public $paginate = [
       'limit' => 10,
       'order' => [
          'Parts.part_id' => 'asc'
       ]
    ];
    
    public $json;
    
    public function initialize()
    {
       parent::initialize();
       $this->loadComponent('Paginator');
       $this->loadComponent('Search.Prg', [
        // This is default config. You can modify "actions" as needed to make
        // the PRG component works only for specified methods.
        'actions' => ['index', 'lookup']
    ]);
    }
       
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {      
       /*  if ($this->request->query){
            debug($this->request->query['date_begin']); 
            die;
            }*/
        
       // Use the plugin 'search' custom finder and pass in the
       // processed query params    
        $query = $this->Parts->find('search', ['search' => $this->request->query]);
        // You can add extra things to the query if you need to
        // ->contain(['Comments'])
        // ->where(['created LIKE' => "2015"]);         
        if ( isset ($this->request->query['date_begin'])){
            $query->where(['created >='  => $this->request->query['date_begin']]);
        }
        if ( isset ($this->request->query['date_end'])){
            $query->where(['created <'  => $this->request->query['date_end']]);
        }    
        
        $resultsArray = $query->toArray();
        $json = json_encode($resultsArray);
         
        $this->set('parts', $this->paginate($query));
        
        // To help create a reset button dynamically
        $this->set('isSearch', $this->Parts->isSearch()); 
        
        $this->set('data', $json);
        $this->set('number', $query->count() );
    }

    
    /**
     * View method
     *
     * @param string|null $id Part id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
       /* $part = $this->Parts->get($id, [
            'contain' => ['Parts']
        ]);*/
        $part = $this->Parts->get($id);
        $this->set(compact('part', 'parts'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $part = $this->Parts->newEntity();
        if ($this->request->is('post')) {
            $part = $this->Parts->patchEntity($part, $this->request->getData());
            if ($this->Parts->save($part)) {
                $this->Flash->success(__('The part has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The part could not be saved. Please, try again.'));
        }
        $parts = $this->Parts->find('list', ['limit' => 200]);
        $this->set(compact('part', 'parts'));
        
    }

    /**
     * Edit method
     *
     * @param string|null $id Part id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $part = $this->Parts->get($id);
        if ($this->request->is(['post', 'put'])) {
            $this->Parts->patchEntity($part, $this->request->getData());
            if ($this->Parts->save($part)) {
                $this->Flash->success(__('Your part has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your part.'));
        }

        $this->set('part', $part);
    }

    /**
     * Delete method
     *
     * @param string|null $id Part id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $part = $this->Parts->get($id);
        if ($this->Parts->delete($part)) {
            $this->Flash->success(__('The part has been deleted.'));
        } else {
            $this->Flash->error(__('The part could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    
  
    
}
