<?php

namespace App\Mail;

use App\Models\History;
use App\Models\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientResult extends Mailable
{
    use Queueable, SerializesModels;

    public Test $test;
    public Collection $blocks;
    public string $profile_code;
    public string $profile_name;
    private object $history_card;
    public array $card;
    public History $history;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Test $test, Collection $blocks, string $profile_code, string $profile_name, object $history_card, History $history)
    {
        $this->test = $test;
        $this->blocks = $blocks;
        $this->profile_code = $profile_code;
        $this->profile_name = $profile_name;
        $this->history_card = $history_card;
        $this->history = $history;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->card = [];
        foreach(\App\Models\Test::$ident as $group) {
            foreach ($group as $element) {
                $name = $element['name'];
                $title = $element['label'];
                if(isset($this->history_card->$name))
                    $this->card[$title] = $this->history_card->$name;
            }
        }

        return $this->view('emails.tests.client')
            ->subject(env('APP_NAME') . ' - индивидуальный результат тестирования')
            ;
    }
}
