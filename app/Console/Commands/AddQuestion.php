<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use App\Models\Question;
use App\Models\Keyword;

class AddQuestion extends Command
{
    protected $signature = 'question:parse {filename}';
    protected $description = 'Parse a Markdown question';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $qc = QuestionCreator::fromFilename(base_path($this->argument('filename')));
        $q = $qc->create();

        if ($q) {
            $this->info("Question {$q->id} created");
        } else {
            $this->warn("Question not created!");
        }

        return 0;
    }
}
