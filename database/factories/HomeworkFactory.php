<?php

namespace Database\Factories;

use App\Models\AcademicDiscipline;
use App\Models\LearningClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Homework>
 */
class HomeworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $learningClass = LearningClass::inRandomOrder()->first();
        return [
            'task' => fake()->text(),
            'date' => fake()->date(),
            'learning_class_id' =>  $learningClass->id,
            'academic_discipline_id' => $learningClass->academicDisciplines()->inRandomOrder()->first()->id,
        
        ];
    }
}

