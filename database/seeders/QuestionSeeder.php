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
            'type' => 'multiple-choice',
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
            'type' => 'multiple-choice',
            'content' => "

Dans l'algorithme de Shunting-yard dont l'image suitante résume le principe, plusieurs structures de données sont utilisées.

@Une pile,Une file d'attente@ est utilisé en entrée et @une pile,une file d'attente@ est utilisé en sortie. Les données intermédiaires sont stoquées sur @une pile,une file d'attente@.

![algorithm](https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Shunting_yard.svg/440px-Shunting_yard.svg.png)
",
            'answer' => '["Une file d\'attente", "une file d\'attente", "une pile"]',
            'difficulty' => 'insane',
            'explanation' => ""
        ]);

        Question::create([
            'name' => 'Boucle',
            'content' => "Comment déclarer une variable nommée `i`, un entier 32-bits non signé égal à la valeur `42` ?",
            'answer' => json_encode([
                'pattern' => '\b(unsigned\s+int|uint32_t)\s+i\s*=\s*42\s*;\b'
            ]),
            'options' => json_encode([
                "lines" => 1,
                'chars' => 40
            ]),
            'difficulty' => 'insane',
            'explanation' => ""
        ]);

        Question::create([
            'name' => 'Philostrate',
            'type' => 'multiple-choice',
            'content' => "
Témoin de la chute de [Jérusalem](https://en.wikipedia.org/wiki/Jerusalem), **Philostrate** n’hésite pas à écrire
que :

>Ce peuple s’était dès longtemps insurgé non contre les Romains, mais contre l’humanité en général. Des hommes qui ont imaginé une vie insociable, qui ne partagent avec leurs semblables ni la table, ni les libations, ni les prières, ni les sacrifices, sont plus éloignés de nous que Suse ou Bactres ou que l’Inde plus reculée encore...

Quelle eut été la portée de son argument sur **Dion Cassius** ?

## A
Dion Cassius chante avec René
## B
Dion Cassius n'affiche aucune sérénité
## C
Dion Cassius adhère pleinement
## D
La quatrième proposition est certainement la bonne
",
            'answer' => json_encode(["C"]),
            'options' => json_encode([]),
            'difficulty' => 'hard',
            'explanation' => 'Aucune idée de pourquoi... Cette question ne semble pas avoir de sens'
        ]);

        Question::create([
            'name' => 'Montage électronique',
            'type' => 'fill-in-the-gaps',
            'content' => "

Dans le circuit ci-dessous. On reconnaît qu'il s'agit d'un `circuit_type`. Les deux `semiconductors` d'entrée forme un `montage`. Les deux `semi2` de sortie forment un montage de type `sortie`

![circuit](https://en.wikipedia.org/wiki/Amplifier#/media/File:Amplifier_Circuit_Small.svg)

",
            'answer' => json_encode([
                "une paire différentielle",
                "transistors bipolaires",
                "amplificateur de signal",
                "push-pull"
            ]),
            'options' => json_encode([
                'gaps' => [
                    'circuit_type' => [
                        'une paire croisée',
                        'un gain semi-relatif',
                        'une paire différentielle',
                        'une source de courant',
                    ],
                    'semiconductors' => [
                        'transistors à effet de champ',
                        'mosfets',
                        'transistors bipolaires',
                        'résistances',
                        'IGBT',
                    ],
                    'montage' => [
                        'amplificateur de signal',
                        'atténuateur de bruit',
                        'intégrateur',
                        'un étage de transconductance'
                    ],
                    'semi2' => [
                        'transistors bipolaires',
                        'transistors à effet de champ',
                        'mosfets',
                        'IGBT',
                        'résistances'
                    ],
                    'sortie' => [
                        'push-pull',
                        'différentiel',
                        'à collecteur ouvert',
                        'à descendance neutre',
                        'de Schottky'
                    ]
                ]
            ]),
            'difficulty' => 'medium',
            'explanation' => 'Explication'
        ]);
    }
}
