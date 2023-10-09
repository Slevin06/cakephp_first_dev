<?php

use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;

class CreateUsers extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');
        $table
            ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => null, 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => null, 'null' => false])
            ->addPrimaryKey(['id'])
            ->create();

        // Insert initial data
        $data = [
            [
                'email' => 'example1@example.com',
                'password' => 'password1',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],

            // Add more data if needed
        ];
        $tableRegistry = TableRegistry::getTableLocator()->get('Users');
        $entities = $tableRegistry->newEntities($data);
        $tableRegistry->saveMany($entities);
    }
}
