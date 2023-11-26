<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VideoBreakpoint;
use App\Models\Video;

class VideoBreakpointFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $titles = [
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
            'România în Primul Război Mondial',
        ];
        $sources = [
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',
            'https://www.youtube.com/embed/qV2PSgIK-c4',

        ];
        $videoId = Video::where('title', $titles[$this->index])
                                         ->where('source', $sources[$this->index])
                                         ->first()->id;
        $breakpoints = [
            ["time" => "0:17:40", "seconds" => "1060", "name" => "Repere"],
            ["time" => "0:17:54", "seconds" => "1074", "name" => "Keywords"],
            ["time" => "0:19:11", "seconds" => "1151", "name" => "1914-1916"],
            ["time" => "0:20:31", "seconds" => "1231", "name" => "Dilema Ramâniei"],
            ["time" => "0:22:04", "seconds" => "1324", "name" => "Aderarea României la Tripla Alianță"],
            ["time" => "0:23:42", "seconds" => "1422", "name" => "Politica de neutralitate"],
            ["time" => "0:24:21", "seconds" => "1461", "name" => "Aderarea României la Antanta (1916)"],
            ["time" => "0:24:53", "seconds" => "1493", "name" => "Victoria de la Turtucaia (1916)"],
            ["time" => "0:25:29", "seconds" => "1529", "name" => "Lupte la Mărăști, Mărășești, Oituz"],
            ["time" => "0:26:52", "seconds" => "1612", "name" => "Pacea de la București 1918"],
            ["time" => "0:28:36", "seconds" => "1716", "name" => "Actul Unirii"],
        ];

        $breakpoint = $breakpoints[$this->index];

        $this->index++;
        return [
            'name' => $breakpoint['name'],
            'time' => $breakpoint['time'],
            'seconds' => $breakpoint['seconds'],
            'video_id' => $videoId,
        ];
    }
}
