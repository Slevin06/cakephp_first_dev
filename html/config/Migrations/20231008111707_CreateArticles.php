<?php

use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;

class CreateArticles extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('articles');
        $table
            ->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('title', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('slug', 'string', ['limit' => 191, 'null' => false])
            ->addColumn('body', 'text', ['default' => null, 'null' => true])
            ->addColumn('published', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => null, 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => null, 'null' => false])
            ->addPrimaryKey(['id'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        // Insert initial data
        $data = [
            [
                'user_id' => 1,
                'title' => 'First Post',
                'slug' => 'first-post',
                'body' => 'This is the first post.',
                'published' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],

            // Add more data if needed
        ];
        $tableRegistry = TableRegistry::getTableLocator()->get('Articles');
        $entities = $tableRegistry->newEntities($data);
        $tableRegistry->saveMany($entities);
    }
}
