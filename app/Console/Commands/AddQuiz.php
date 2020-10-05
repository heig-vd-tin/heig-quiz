<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use App\Models\Quiz;
use App\Models\User;

class AddQuiz extends Command
{
    protected $signature = 'quiz:add {dirname}';
    protected $description = 'Add a new quiz in the DB';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $path = base_path($this->argument('dirname'));
        $name = trim(basename($path));
        $questions = [];
        foreach (scandir($path) as $filename) {
            if (in_array($filename, ['.', '..'])) continue;
            if (!preg_match('/\.md$/', $filename)) continue;

            $qc = QuestionCreator::fromFilename("$path/$filename");
            $q = $qc->create();

            if ($q) {
                $this->info("Adding question $q->id");
                $questions[] = $q->id;
            }
        }

        $user = User::where('firstname', 'Yves')->where('lastname', 'Chevallier')->first();

        $quiz = Quiz::create([
            'name' => $name,
            'user_id' => $user->id
        ]);

        $quiz->questions()->attach($questions);

        return 0;
    }
}
