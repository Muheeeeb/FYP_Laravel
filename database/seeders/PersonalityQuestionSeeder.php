<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonalityQuestion;

class PersonalityQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'I prefer working in a team rather than independently.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 1,
            ],
            [
                'question' => 'I enjoy solving complex problems.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 2,
            ],
            [
                'question' => 'I am comfortable taking risks.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 3,
            ],
            [
                'question' => 'I adapt well to changes in the workplace.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 4,
            ],
            [
                'question' => 'I prefer structured tasks with clear instructions.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 5,
            ],
            [
                'question' => 'I enjoy taking on leadership roles.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 6,
            ],
            [
                'question' => 'I handle stress well in high-pressure situations.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 7,
            ],
            [
                'question' => 'I prefer innovation over following established methods.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 8,
            ],
            [
                'question' => 'I pay close attention to details.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 9,
            ],
            [
                'question' => 'I am able to effectively communicate my ideas to others.',
                'type' => 'likert_scale',
                'options' => null,
                'order' => 10,
            ],
            [
                'question' => 'How do you prefer to make decisions?',
                'type' => 'multiple_choice',
                'options' => json_encode([
                    'Based on logic and facts',
                    'Based on feelings and values',
                    'A combination of both',
                    'It depends on the situation'
                ]),
                'order' => 11,
            ],
            [
                'question' => 'What type of work environment do you prefer?',
                'type' => 'multiple_choice',
                'options' => json_encode([
                    'Fast-paced and dynamic',
                    'Calm and structured',
                    'Collaborative and team-oriented',
                    'Independent with minimal supervision'
                ]),
                'order' => 12,
            ],
        ];

        foreach ($questions as $question) {
            PersonalityQuestion::create($question);
        }
    }
}





