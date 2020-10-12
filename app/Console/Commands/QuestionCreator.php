<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use App\Models\Question;
use App\Models\Keyword;
use InvalidArgumentException;

class QuestionCreator
{
    public $content;
    public $frontmatter;
    public $explanation;

    public function __construct($content)
    {
        $this->content = $content;
        $this->frontmatter = $this->parseFrontmatter($this->content);
        $this->explanation = $this->parseExplanation($this->content);
    }

    public static function fromFilename(string $filename)
    {
        $content = file_get_contents($filename);
        return new QuestionCreator($content);
    }

    protected function parseFrontmatter(&$md_content, $extract=true)
    {
        // https://regex101.com/r/ayUHsw/1
        $re = "/\A---\s*(.*)?^---\s*/ms";
        if (!preg_match($re, $md_content, $matches))
            return null;

        $frontmatter = yaml_parse($matches[1]);

        if ($extract) {
            $md_content = preg_replace($re, '', $md_content);
        }

        return $frontmatter;
    }

    protected function parseExplanation(&$md_content, $extract=true)
    {
        // https://regex101.com/r/1g102j/3
        $re = "/##\s(?:[eE]xplications?|[eE]xplanations?|[rR]ationale)\s*(.*)?(?=^#{1,2}\s+|\Z)/ms";
        if (!preg_match($re, $md_content, $matches))
            return null;

        $explanation = $matches[1];

        if ($extract) {
            $md_content = preg_replace($re, '', $md_content);
        }

        return $explanation;
    }

    public function create()
    {
        $fm = $this->frontmatter;
        $question = (object)[
            'content' => $this->content,
            'explanation' => $this->explanation,
            'name' => Arr::get($fm, 'name'),
            'difficulty' => Arr::get($fm, 'difficulty', 1),
            'duration' => Arr::get($fm, 'duration', 30),
            'validation' => Arr::get($fm, 'validation'),
            'type' => Arr::get($fm, 'type', $this->guessType($this->content)),
            'options' => Arr::get($fm, 'options'),
        ];

        // Fix one answer on multiple-choice
        if ($question->validation == 'multiple-choice' && is_integer($question->validation))
            $question->validation = [$question->validation];

        // Check if the question exists
        $q = Question::where('content', 'like', $question->content)
            ->where('name', $question->name)
            ->first();

        $q = $q ? $q : Question::create((array)$question);

        $this->attachTags($q, Arr::get($this->frontmatter, 'tags', []));

        return $q;
    }

    protected function guessType($content)
    {
        if (preg_match_all('/^## \d\b/m', $content) > 1) {
            return 'multiple-choice';
        }

        if (preg_match_all('/\s\*-\*\s/', $content)) {
            return 'fill-in-the-gaps';
        }

        return 'short-answer';
    }

    protected function attachTags($question, $tags)
    {
        if (!$question) return;

        foreach($tags as $name) {
            $tag = Keyword::where('name', $name)->first();
            if (!$tag) {
                $tag = Keyword::create([
                    'name' => $name
                ]);
            }
            $question->keywords()->attach($tag);
        }
    }
}
