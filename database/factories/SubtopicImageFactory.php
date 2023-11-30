<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubtopicImage;
use App\Models\Subtopic;

class SubtopicImageFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $subtopicPaths = [
            ["subtopic" => "Definiția mulțimilor", "path" => "/images/multimiDef.jpg"],
            ["subtopic" => "Modurile de reprezentare a mulțimii", "path" => "/images/multimiReprezentare.jpg"],
            ["subtopic" => "Relația de apartenență la mulțime", "path" => "/images/multimiApartenenta.jpg"],
            ["subtopic" => "Mulțimi egale", "path" => "/images/multimiEgale.jpg"],
            ["subtopic" => "Submulțimi", "path" => "/images/multimiSub.jpg"],
            ["subtopic" => "Cardinalul mulțimii finite", "path" => "/images/multimiCard.jpg"],
            ["subtopic" => "Operații cu mulțimi", "path" => "/images/multimiUniune.jpg"],
            ["subtopic" => "Operații cu mulțimi", "path" => "/images/multimiIntersectia.jpg"],
            ["subtopic" => "Operații cu mulțimi", "path" => "/images/multimiDisjuncte.jpg"],
            ["subtopic" => "Operații cu mulțimi", "path" => "/images/multimiDiferenta.jpg"],
            ["subtopic" => "Operații cu mulțimi", "path" => "/images/multimiProdus.jpg"],
            ["subtopic" => "Mulțimi de numere", "path" => "/images/multimiNumere.jpg"],
            ["subtopic" => "Incluziunile N ⊂ Z ⊂ Q ⊂ R", "path" => "/images/multimiIncluziune.jpg"]
        ];

        $subtopicPath = $subtopicPaths[$this->index];

        $path = $subtopicPath['path'];
        $subtopic = $subtopicPath['subtopic'];

        $subtopictId = Subtopic::firstWhere('name', $subtopic)->id;

        $this->index++;

        return [
            'path' => $path,
            'subtopic_id' => $subtopictId,
        ];
    }
}
