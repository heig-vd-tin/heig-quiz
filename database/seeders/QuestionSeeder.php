<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Keyword;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $q = new Question;
        $q->name = 'Transformation binaire';
        $q->content = 'Que vaut `0x7` en binaire ?';
        $q->answer = '{"pattern" : "/\b(0b?)0*111\b"}';
        $q->difficulty = 'medium';
        $q->keywords()->attach(Keyword::where('name', 'binary')->get());
        $q->save();

        Question::create([
            'name' => 'Transformation binaire',
            'content' => 'Que vaut `0x8` en binaire ?',
            'answer' => '{"pattern" : "\b(0b?)0*1000\b"}',
            'difficulty' => 'medium',
            'explanation' => "`8` exprimé en hexadécimal correspond à $2^4$ soit le quatrième bit du nombre à un."
        ]);

        Question::create([
            'name' => 'Transformation binaire',
            'content' => 'Que vaut `0` en binaire ?',
            'answer' => '{"pattern" : "\b(0b?)0*0\b"}',
            'difficulty' => 'easy',
            'explanation' => ''
        ]);

        // Exemple de question avec des choix multiples
        Question::create([
            'name' => 'Complément à deux',
            'content' => "
Le complément à deux c'est...

## A

Inverser tous les bits

## B

Inverser tous les bits et ajouter 1

## C

Inverser tous les bits et soustraire 1

## D

Complémenter le nombre binaire pour avoir une somme de deux

## E

Ajouter 1 puis inverer tous les bits
",
            'answer' => '["B"]',
            'difficulty' => 'easy',
            'explanation' => ''
        ]);

        // Exemple de question avec des choix possibles
        // @a,b,c@ pour les listes possibles et
        // @...@ pour les champs libres
        Question::create([
            'name' => 'Shunting Yard',
            'content' => "

Dans l'algorithme de Shunting-yard dont l'image suitante résume le principe, plusieurs structures de données sont utilisées.

@Une pile,Une file d'attente@ est utilisé en entrée et @une pile,une file d'attente@ est utilisé en sortie. Les données intermédiaires sont stoquées sur @une pile,une file d'attente@.

![algorithm](https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Shunting_yard.svg/440px-Shunting_yard.svg.png)
",
            'answer' => '["Une file d\'attente", "une file d\'attente", "une pile"]',
            'difficulty' => 'insane',
            'explanation' => ""
        ]);
    }
}
