<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Parts Model
 *
 * @property \App\Model\Table\PartsTable|\Cake\ORM\Association\BelongsTo $Parts
 *
 * @method \App\Model\Entity\Part get($primaryKey, $options = [])
 * @method \App\Model\Entity\Part newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Part[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Part|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Part patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Part[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Part findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PartsTable extends Table
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

        $this->setTable('parts');
        $this->setDisplayField('part_id');
        $this->setPrimaryKey('part_id');

        $this->addBehavior('Timestamp');
        
        // Add the behaviour to your table
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            //->value('partname') // This is for exact matches only
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->add('partname', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => ['partname']
            ])
            ->add('foo', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    // Modify $query as required
                    debug("Reached PartsTable search callback");
                    debug($query); 
                    die;
                }
            ]);

         $this->belongsTo('Parts', [
            'foreignKey' => 'part_id',
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
            ->scalar('partname')
            ->requirePresence('partname', 'create')
            ->notEmpty('partname');

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
        $rules->add($rules->existsIn(['part_id'], 'Parts'));

        return $rules;
    }
}
