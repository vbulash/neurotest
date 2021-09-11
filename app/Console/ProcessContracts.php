<?php


namespace App\Console;


use App\Models\Contract;
use DateTime;
use Illuminate\Support\Facades\Log;

class ProcessContracts
{
    public function __invoke()
    {
        $contracts = Contract::all();
        foreach ($contracts as $contract) {
            $current = $contract->status;

            $today = new DateTime();
            $start = DateTime::createFromFormat('Y-m-d', $contract->start);
            $end = DateTime::createFromFormat('Y-m-d', $contract->end);

            $status = Contract::INACTIVE;
            if (($today >= $start) && ($today < $end)) $status = Contract::ACTIVE;
            if ($today > $end) $status = Contract::COMPLETE_BY_DATE;

            if ($status != $current) {
                $contract->status = $status;
                $contract->update();

                $statuses = [
                    Contract::INACTIVE => 'Неактивен (дата начала в будущем)',
                    Contract::ACTIVE => 'Исполняется',
                    Contract::COMPLETE_BY_DATE => 'Завершен по дате',
                    Contract::COMPLETE_BY_COUNT => 'Завершен, закончились свободные лицензии'
                ];

                Log::debug(sprintf("Статус контракта # %s измененен с '%s' на '%s'",
                    $contract->number, $statuses[$current], $statuses[$status]));
            }
        }
    }

}
