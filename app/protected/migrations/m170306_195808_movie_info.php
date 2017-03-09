<?php

class m170306_195808_movie_info extends EDbMigration
{
    const TABLE='movie_info';

    public function up() {
        $this->createTable(self::TABLE, array(
            'id' => 'int',

            'title'         => 'string NOT NULL',
            'original_title' => 'string NOT NULL',
            'release_date'  => 'string NOT NULL',
            'runtime'       => 'string NOT NULL',
            'overview'      => 'text',
            'genres'        => 'string NOT NULL',
            'poster_path'   => 'string NOT NULL',
        ));
    }

    public function down() {
        //    echo "m170227_234907_create_user_table does not support migration down.\n";
        //    return false;

        $this->dropTable(self::TABLE);
    }
	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}