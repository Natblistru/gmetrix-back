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
            ["subtopic" => "Incluziunile N ⊂ Z ⊂ Q ⊂ R", "path" => "/images/multimiIncluziune.jpg"],
       
            ["subtopic" => "Adunarea și scăderea numerelor naturale. Proprietăți", "path" => "/images/NumereNaturaleAddPropr.jpg"],
            ["subtopic" => "Adunarea și scăderea numerelor naturale. Proprietăți", "path" => "/images/NumereNaturaleScadPropr.jpg"],
            ["subtopic" => "Înmulțirea numerelor naturale. Proprietăți. Factorul comun", "path" => "/images/NumereNaturaleImnultPropr.jpg"],
            ["subtopic" => "Înmulțirea numerelor naturale. Proprietăți. Factorul comun", "path" => "/images/NumereNaturaleImnultTehnica.jpg"],
            ["subtopic" => "Puterea cu exponent număr natural. Proprietăți", "path" => "/images/NumereNaturalePuterePropr.jpg"],
            ["subtopic" => "Puterea cu exponent număr natural. Proprietăți", "path" => "/images/NumereNaturalePutereCub.jpg"],
            ["subtopic" => "Împărțirea numerelor naturale. Împărțirea cu rest", "path" => "/images/NumereNaturaleImpartire.jpg"],
            ["subtopic" => "Împărțirea numerelor naturale. Împărțirea cu rest", "path" => "/images/NumereNaturaleImpRest.jpg"],
            ["subtopic" => "Împărțirea numerelor naturale. Împărțirea cu rest", "path" => "/images/NumereNaturaleImpTehnica.jpg"],
            ["subtopic" => "Divizor. Mulțimea divizorilor unui număr natural", "path" => "/images/DivizorDef.jpg"],
            ["subtopic" => "Multiplu. Mulțimea multiplilor unui număr natural", "path" => "/images/MultipluDef.jpg"],
            ["subtopic" => "Numere prime, numere compuse", "path" => "/images/NumerePrimeCompuse.jpg"],
            ["subtopic" => "Criteriile de divizibilitate cu 2, 3, 5, 9, 10. Numere pare, impare", "path" => "/images/CriteriiDivizibilitate.jpg"],
            ["subtopic" => "Criteriile de divizibilitate cu 2, 3, 5, 9, 10. Numere pare, impare", "path" => "/images/NumerePareImpare.jpg"],
            ["subtopic" => "Descompunerea numerelor naturale în produs de puteri de numere prime", "path" => "/images/DescompunereFactori.jpg"],
            ["subtopic" => "Divizor comun al două numere naturale. C.m.m.d.c. al două numere naturale", "path" => "/images/CMMDivizorComun.jpg"],
            ["subtopic" => "Numere prime între ele", "path" => "/images/PrimeInreEle.jpg"],
            ["subtopic" => "Multipli comuni ai două numere naturale", "path" => "/images/MultipliComuni.jpg"],
            ["subtopic" => "C.m.m.m.c. al două numere naturale", "path" => "/images/CMMMultipluComun.jpg"]
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
