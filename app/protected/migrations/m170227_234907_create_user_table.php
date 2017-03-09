<?php

class m170227_234907_create_user_table
    extends EDbMigration {
    const TABLE='user';

    public function up() {
        $this->createTable(self::TABLE, array(
            'id' => 'pk',
            'api_key' => 'string NOT NULL',
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