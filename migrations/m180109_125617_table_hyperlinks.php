<?php

/**
 * Migration adding the hyperlinks tables as needed by the module
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license AS infotrack AG license / MIT, see provided license file
 */
class m180109_125617_table_hyperlinks extends \asinfotrack\yii2\toolbox\console\Migration
{

	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createAuditedTable('{{%hyperlink}}', [
			'id'=>$this->primaryKey(),
			'model_type'=>$this->string()->notNull(),
			'foreign_pk'=>$this->string()->notNull(),
			'is_new_tab'=>$this->boolean()->notNull()->defaultValue(1),
			'url'=>$this->string()->notNull(),
			'title'=>$this->string()->notNull(),
			'description'=>$this->text(),
		]);
		$this->createIndex('IN_hyperlink_model_type', '{{%hyperlink}}', ['model_type']);
		$this->createIndex('IN_hyperlink_model_type_foreign_pk', '{{%hyperlink}}', ['model_type','foreign_pk']);
		$this->createIndex('IN_hyperlink_url', '{{%hyperlink}}', ['url']);

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('{{%hyperlink}}');

		return true;
	}

}
