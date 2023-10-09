<?php

use Migrations\AbstractMigration;

class CreateTags extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('tags');
        $table
            ->addColumn('title', 'string', ['limit' => 191])
            ->addColumn('created', 'datetime', ['default' => null, 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => null, 'null' => false])
            ->addPrimaryKey(['id'])
            ->addIndex(['title'], ['unique' => true])
            ->create();
    }
}
