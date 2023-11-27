<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ThemeLearningProgram;
use App\Models\LearningProgram;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\SubjectStudyLevel;
use App\Models\Theme;


class ThemeLearningProgramFactory extends Factory
{
    private $thems = [
        //2022 MATEM CAPITOL 1 - Mulţimi numerice
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor întregi', 
        'Mulțimea numerelor raționale',
        'Mulțimea numerelor reale',
        //2022 MATEM CAPITOL 2 - Rapoarte şi proporţii
        'Rapoarte. Proporţii. Proprietatea fundamentală a proporţiilor',
        'Mărimi direct proporţionale şi mărimi invers proporţionale. Regula de trei simplă',
        'Procente. Aflarea % dintr-un număr dat. Aflarea unui număr din %',
        'Media aritmetică',

        // MATEM CAPITOL 3 - Calcul algebric
        'Operaţii cu numere reale reprezentate prin litere',
        'Formule de calcul prescurtat',
        'Metode de descompunere în factori',
        'Transformări identice ale expresiilor algebrice',
        'Rapoarte (fracții) algebrice, DVA',
        // MATEM CAPITOL 4 - Funcţii 
        'Coordonatele punctului pe plan',
        'Noţiune de funcţie. Graficul funcţiei',
        'Funcţia de gradul I. Reprezentarea grafică. Proprietăţi',
        'Funcţia de gradul II. Reprezentarea grafică. Proprietăţi',    
        // MATEM CAPITOL 5 - Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii 
        'Ecuaţii de gradul I cu o necunoscută',
        'Sisteme de ecuaţii de gradul I cu două necunoscute', 
        'Inecuaţii de gradul I cu o necunoscută',
        'Sisteme de inecuaţii de gradul I cu o necunoscută',
        'Ecuaţii de gradul II cu o necunoscută. Relaţiile Viète',
        'Inecuaţii de gradul II cu o necunoscută',
        'Ecuaţii raţionale cu o necunoscută',
        // MATEM CAPITOL 6 - Geometrie     
	    'Unități de măsură (lungime, timp, arie, volum)', 
        'Noțiuni geometrice fundamentale', 
        'Triunghiuri',
        'Patrulatere. Poligoane.',
        'Cercul. Discul', 
        'Arii', 
        'Poliedre',
        'Corpuri de rotație',
        //ISTORIA CAPITOL 1 - Primul Război Mondial și formarea statului național român
        'România în Primul Război Mondial',
        'Mișcarea națională a românilor din Basarabia și teritoriile din stânga Nistrului',
        'Formarea Statului Național Unitar Român. Recunoașterea Marii Uniri de la 1918',
        'Conferinţa de Pace de la Paris. Sistemul de la Versailles',
        //ISTORIA CAPITOL 2 - Lumea în perioada interbelică
        'SUA în perioada interbelică',
        'Europa de Vest în perioada interbelică',
        'România în perioada interbelică',
        'Basarabia în cadrul României Mari (1918-1940)',
        'RASSM (1924-1940). Politica expansionistă a URSS',
        'Cultura și știința în perioada interbelică',
        'Cultura românească în 1918-40',
        //ISTORIA CAPITOL 3 - Relațiile internaționale în perioada interbelică
        'Alianțe și tratate politico-militare în perioada interbelică',
        'Relațiie sovieto-române(1918-1940). Pactul Molotov-Ribbentrop',
        'Pierderile teritoriale ale României în vara anului 1940',
        'Formarea RSSM și instaurarea regimului comunist',
        //ISTORIA CAPITOL 4 - Al Doilea Război Mondial  
        'Al Doilea Război Mondial 1939-1945',
        'Spațiul românesc între 1941 şi 1944', 
        'Crime de război. Holocaust', 
        'Consecințele celui de-al Doilea Război Mondial',       
        //ISTORIA CAPITOL 5 - Lumea postbelică 
        'Relațiile internaționale în perioada 1945-1991. Constituirea ONU', 
        'Uniunea Sovietică în perioada postbelică',
        'RSSM. Economie şi societate (1944-1985)',
        'Foametea, represiile și deportările staliniste din RSSM',
        //ISTORIA CAPITOL 6 - Lumea la sfârșitul secolului XX - începutul secolului XXI 
        'RSSM între 1985-1991. Proclamarea independenței Republicii Moldova',
        'Războiul de pe Nistru',
        //ISTORIA CAPITOL 7 - Cultura și știința în perioada postbelică      
        'Cultura și știința în RSSM (1944-1991)', 
        'Evoluția culturii în Republica Moldova',
        'Cultura și știința universală în Epoca Contemporană',
       
        //ROMANA CAPITOL 1 - Perceperea identității lingvistice și culturale proprii în context național
        'Limba română', 
        'Personalitățile neamului',
        'Comunitatea vorbitorilor de limbă română',
        'Cultura națională și europeană',
        //ROMANA CAPITOL 2 - Utilizarea limbii ca sistem și a normelor lingvistice în realizarea actelor communicative
        'Elemente de fonetică şi ortografie',
        'Lexicul',
        'Morfologia',
        'Sintaxa',
        //ROMANA CAPITOL 3 - Lectura și receptarea textelor literare și nonliterare
        'Cartea',
        'Structura operii literare',
        'Personaje principale și personaje secundare',
        'Text narativ',
        'Text liric',
        'Text dramatic',
        'Elementele de versificație',
        'Figurile de stil',
        'Genuri și specii literare',
        'Literatura populară și literatura cultă',
        'Textul literar și nonliterar',
        //ROMANA CAPITOL 4 - Producerea textelor scrise
        'Scrierea reflexivă',
        'Scrierea imaginativă',
        'Scrierea funcțională',
        //ROMANA CAPITOL 5 - Integrarea experiențelor lingvistice și de lectură în contexte şcolare şi de viață 
        'Interese și preferințe de lectură', 
        'Raftul de literature artistică în biblioteca personală', 
        'Interconexiunea literaturii cu alte disciplini',
        'Literatura în viața cotidiană', 
    ];
    private $index = 0;
    public function definition(): array
    {
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjects = ['Matematica', 
        'Matematica', 
        'Matematica', 
                'Matematica',
                 'Matematica',
                  'Matematica',
                'Matematica',
                 'Matematica',
                  'Matematica',
                'Matematica',
                 'Matematica',
                  'Matematica', 
                'Matematica',
                 'Matematica',
                  'Matematica',
                'Matematica',
                 'Matematica',
                  'Matematica',
                'Matematica',
                 'Matematica',
                  'Matematica', 
                'Matematica',
                 'Matematica',
                  'Matematica',
                'Matematica',
                 'Matematica',
                  'Matematica',
                'Matematica',
                 'Matematica',
                  'Matematica', 
                'Matematica',
                 'Matematica',

                'Istoria',
                 'Istoria',
                  'Istoria', 
                'Istoria',
                 'Istoria',
                  'Istoria',
                'Istoria', 
                'Istoria',
                 'Istoria',
                'Istoria',
                 'Istoria',
                  'Istoria', 
                'Istoria',
                 'Istoria',
                  'Istoria',
                'Istoria',
                 'Istoria',
                  'Istoria',
                'Istoria',
                 'Istoria',
                  'Istoria', 
                'Istoria',
                 'Istoria',
                  'Istoria',
                'Istoria',
                 'Istoria',
                  'Istoria',
                   'Istoria',
                'Limba română',
                 'Limba română',
                  'Limba română', 
                'Limba română', 
                'Limba română',
                 'Limba română',
                'Limba română',
                 'Limba română',
                  'Limba română',
                'Limba română',
                 'Limba română', 
                 'Limba română', 
                'Limba română', 
                'Limba română', 
                'Limba română',
                'Limba română', 
                'Limba română', 
                'Limba română',
                'Limba română',
                'Limba română', 
                'Limba română', 

                'Limba română',
                 'Limba română', 
                 'Limba română',
                'Limba română', 
                'Limba română'
            ];
        // $years = [2022,2022,2022,2022,2022,2022,2022,2022,
        //         2014,2014,2014,2014,2014,2014,2014,2014,
        //         2014,2014,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022,2022,
        //         2022,2022,2022,2022,2022,2022,2022,2022,2022    
        // ];
        // $year = $years[$this->index];
        $subjectId = Subject::firstWhere('name', $subjects[$this->index])->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectId) ->first()->id;
        $learningProgramId = LearningProgram::where('subject_study_level_id', $subjectStudyLevelId)
                                              -> where('year', 2022)->first()->id;

        $themeId = Theme::firstWhere('name', $this->thems[$this->index])->id;

        $this->index++;

        return [
            'learning_program_id' => $learningProgramId,
            'theme_id' => $themeId,
        ];
    }
}
