<?php

use Migrations\AbstractMigration;

class CreateArticlesTags extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('articles_tags', ['id' => false, 'primary_key' => ['article_id', 'tag_id']]);
        $table
            ->addColumn('article_id', 'integer', ['null' => false])
            ->addColumn('tag_id', 'integer', ['null' => false])
            ->addForeignKey('article_id', 'articles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('tag_id', 'tags', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
