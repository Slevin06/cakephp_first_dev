<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Article;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property UsersTable&BelongsTo $Users
 * @property TagsTable&BelongsToMany $Tags
 *
 * @method Article newEmptyEntity()
 * @method Article newEntity(array $data, array $options = [])
 * @method Article[] newEntities(array $data, array $options = [])
 * @method Article get($primaryKey, $options = [])
 * @method Article findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Article patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Article[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Article|false save(EntityInterface $entity, $options = [])
 * @method Article saveOrFail(EntityInterface $entity, $options = [])
 * @method Article[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Article[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Article[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Article[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class ArticlesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'articles_tags',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->minLength('title', 10, 'タイトルは最低10文字です。')
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 191)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     * タグで記事検索する
     *
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findTagged(Query $query, array $options): Query
    {
        $this->setAlias('A');
        $columns = [
            'A.id', 'A.user_id', 'A.title',
            'A.body', 'A.published', 'A.created',
            'A.slug',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if (empty($options['tags'])) {
            // タグが指定されていない場合はタグのない記事を検索する
            $query->leftJoinWith('Tags')
                ->where(['Tags.title IS' => null]);
        } else {
            // 提供されたタグが1つ以上ある記事を検索する
            $query->innerJoinWith('Tags')
                ->where(['Tags.title IN' => $options['tags']]);
        }

        return $query->group(['A.id']);
    }
}
