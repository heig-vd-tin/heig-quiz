<?php
namespace App\Transformer;

use League\Fractal;
use App\Models\Student;

class StudentTransformer extends Fractal\TransformerAbstract
{
	public function transform(Student $student)
	{
	    return [
            'id' => $student->id,
            'orientation' => $student->orientation,
            'name' => $student->user->name,
            'user_id' => $student->user->id,
        ];
	}
}
