<?php
namespace App\Transformer;

use League\Fractal;
use App\Models\Question;

class QuestionTransformer extends Fractal\TransformerAbstract
{
	public function transform(Question $question)
	{
	    return [

	    ];
	}
}
