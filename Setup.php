<?php

namespace Laneros\MailStats;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
	{
		$this->schemaManager()->createTable('xf_ms_stats', function (Create $table) {
			$table->addColumn('stat_id', 'int')->autoIncrement();
			$table->addColumn('user_id', 'int')->nullable();
			$table->addColumn('date', 'int');
			$table->addColumn('email', 'varchar', 120);
			$table->addColumn('subject', 'varchar', 500);
			$table->addColumn('report', 'enum')->values([
				'pass', 'fail'
			])->setDefault('pass');
			$table->addColumn('message', 'text')->nullable();
			$table->addColumn('error_message', 'text')->nullable();
		});
	}
}