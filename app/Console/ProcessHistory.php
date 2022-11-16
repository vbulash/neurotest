<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessHistory
{
	public function __invoke(): void
	{
		Log::debug('Контроль зависших записей истории и лицензий...');
		DB::table('log')->delete();
		$deleted = DB::table('history')->whereNull('code')->delete();
		if ($deleted > 0)
			Log::debug('Удалено зависших записей истории прохождения тестов: ' . $deleted);
		// TODO Вернуть лицензии
	}
}
