<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chapter;
use App\Models\Theme;

class ThemeFactory extends Factory
{

    private $values = [
        //MATEM CAPITOL 1 - Mulţimi numerice
        'Mulțimea numerelor naturale', 
        'Mulțimea numerelor întregi', 
        'Mulțimea numerelor raționale',
        'Mulțimea numerelor reale',
        // MATEM CAPITOL 2 - Rapoarte şi proporţii
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
        'Inecuaţii de gradul I cu o necunoscută',
        'Sisteme de ecuaţii de gradul I cu două necunoscute', 
        'Sisteme de inecuaţii de gradul I cu o necunoscută',
        'Ecuaţii de gradul II cu o necunoscută. Relaţiile Viète',
        'Ecuaţii raţionale cu o necunoscută',
        'Inecuaţii de gradul II cu o necunoscută',
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
    private $previousChapterId;
    private $orderNumber = 1;

    public function definition(): array
    {
        $chapters = [
            'Mulţimi numerice', 
                     'Mulţimi numerice', 
                     'Mulţimi numerice', 
                     'Mulţimi numerice',
		     'Rapoarte şi proporţii', 
             'Rapoarte şi proporţii', 
             'Rapoarte şi proporţii', 
             'Rapoarte şi proporţii', 

		     'Calcul algebric', 
             'Calcul algebric', 
             'Calcul algebric', 
             'Calcul algebric', 
             'Calcul algebric', 
             'Funcţii',
             'Funcţii',
             'Funcţii',
             'Funcţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii',
             'Geometrie', 
             'Geometrie', 
             'Geometrie', 
             'Geometrie', 
             'Geometrie', 
             'Geometrie', 
             'Geometrie', 
             'Geometrie', 

		     'Primul Război Mondial și formarea statului național român', 
             'Primul Război Mondial și formarea statului național român', 
             'Primul Război Mondial și formarea statului național român', 
             'Primul Război Mondial și formarea statului național român',
		     'Lumea în perioada interbelică', 
             'Lumea în perioada interbelică', 
             'Lumea în perioada interbelică',
             'Lumea în perioada interbelică',
             'Lumea în perioada interbelică',
             'Lumea în perioada interbelică',
             'Lumea în perioada interbelică',
		     'Relațiile internaționale în perioada interbelică', 
             'Relațiile internaționale în perioada interbelică', 
             'Relațiile internaționale în perioada interbelică', 
             'Relațiile internaționale în perioada interbelică',
             'Al Doilea Război Mondial',
             'Al Doilea Război Mondial',
             'Al Doilea Război Mondial',
             'Al Doilea Război Mondial', 
             'Lumea postbelică', 
             'Lumea postbelică', 
             'Lumea postbelică', 
             'Lumea postbelică',
             'Lumea la sfârșitul secolului XX - începutul secolului XXI', 
             'Lumea la sfârșitul secolului XX - începutul secolului XXI',
             'Cultura și știința în perioada postbelică',
             'Cultura și știința în perioada postbelică',
             'Cultura și știința în perioada postbelică',

		     'Perceperea identității lingvistice și culturale proprii în context național', 
             'Perceperea identității lingvistice și culturale proprii în context național', 
             'Perceperea identității lingvistice și culturale proprii în context național', 
             'Perceperea identității lingvistice și culturale proprii în context național',
		     'Utilizarea limbii ca sistem și a normelor lingvistice în realizarea actelor communicative', 
             'Utilizarea limbii ca sistem și a normelor lingvistice în realizarea actelor communicative', 
             'Utilizarea limbii ca sistem și a normelor lingvistice în realizarea actelor communicative', 
             'Utilizarea limbii ca sistem și a normelor lingvistice în realizarea actelor communicative',
		     'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare', 
             'Lectura și receptarea textelor literare și nonliterare',
             'Producerea textelor scrise', 
             'Producerea textelor scrise', 
             'Producerea textelor scrise',
             'Integrarea experiențelor lingvistice și de lectură în contexte şcolare şi de viață', 
             'Integrarea experiențelor lingvistice și de lectură în contexte şcolare şi de viață', 
             'Integrarea experiențelor lingvistice și de lectură în contexte şcolare şi de viață', 
             'Integrarea experiențelor lingvistice și de lectură în contexte şcolare şi de viață'
        ];

        $paths = [
            "/matem/multimea-numerelor-naturale",
            "/matem/multimea-numerelor-intregi",
            "/matem/multimea-numerelor-rationale",
            "/matem/multimea-numerelor-reale",

            "/matem/rapoarte-proportii",
            "/matem/marimi-direct-invers-proportionale",
            "/matem/procente",
            "/matem/media-aritmetică",

            "/matem/operatii-cu-numere-reale",
            "/matem/formule-de-calcul-prescurtat",
            "/matem/descompunere-in-factori",
            "/matem/transformari-identice",
            "/matem/rapoarte-algebrice",

            "/matem/coordonatele-punctului-pe-plan",
            "/matem/notiune-de-functie",
            "/matem/functia-de-gradul-1",
            "/matem/functia-de-gradul-2",

            "/matem/ecuatii-de-gradul-1-cu-1-necunoscuta",
            "/matem/sisteme-de-ecuatii-de-gradul-1-cu-2-necunoscute",
            "/matem/inecuatii-de-gradul-1-cu-1-necunoscuta",
            "/matem/sisteme-de-inecuatii-de-gradul-1-cu-o-necunoscuta",
            "/matem/ecuatii-de-gradul-2-cu-o-necunoscuta",
            "/matem/inecuatii-de-gradul-2-cu-1-necunoscuta",
            "/matem/ecuatii-rationale-cu-1-necunoscuta",

            "/matem/initati-de-masura",
            "/matem/notiuni-geometrice-fundamentale",
            "/matem/triunghiuri",
            "/matem/patrulatere-poligoane",
            "/matem/cercul-discul",
            "/matem/arii",
            "/matem/poliedre",
            "/matem/corpuri-de-rotatie",

        "/istoria/romania-in-primul-razboi",
        "/istoria/mișcarea-națională-a-românilor",
        "/istoria/formarea-statului-national-unitar-roman",
        "/istoria/conferinta-de-pace-de-la-paris",
        
        "/istoria/sua-in-perioada-interbelica",
        "/istoria/europa-de-vest-in-perioada-interbelica",
        "/istoria/romania-in-perioada-interbelica",
        "/istoria/basarabia-in-cadrul-romaniei-mari",
        "/istoria/rassm",
        "/istoria/cultura-in-perioada-interbelica",
        "/istoria/cultura-romaneasca-interbelica",
        
        "/istoria/aliante-in-perioada-interbelica",
        "/istoria/relatiie-sovieto-romane",
        "/istoria/pierderile-teritoriale-ale-romaniei",
        "/istoria/formarea-rssm",
        
        "/istoria/al-doilea-razboi-mondial",
        "/istoria/romania-in-al-doilea-razboi-mondial",
        "/istoria/holocaust",
        "/istoria/consecintele-celui-de-al-doilea-razboi-mondial",
        
        "/istoria/constituirea-onu",
        "/istoria/uniunea-sovietica-in-perioada-postbelica",
        "/istoria/rssm",
        "/istoria/deportarile-staliniste-din-rssm",
        
        "/istoria/proclamarea-independentei-republicii-moldova",
        "/istoria/razboiul-de-pe-nistru",
        
        "/istoria/cultura-si-stiinta-in-rssm",
        "/istoria/evolutia-culturii-in-republica-moldova",
        "/istoria/cultura-si-stiinta-in-epoca-contemporana",

        "/romana/limba-romana",
        "/romana/personalitatile-neamului",
        "/romana/comunitatea-vorbitorilor-de-limba-romana",
        "/romana/cultura-nationala-si-europeana",

        "/romana/elemente-de-fonetica-si-ortografie",
        "/romana/lexicul",
        "/romana/morfologia",
        "/romana/sintaxa",

        "/romana/cartea",
        "/romana/structura-operii-literare",
        "/romana/personaje",
        "/romana/text-narativ",
        "/romana/text-liric",
        "/romana/text-dramatic",
        "/romana/elementele-de-versificatie",
        "/romana/figurile-de-stil",
        "/romana/genuri-si-specii-literare",
        "/romana/literatura-populara-si-literatura-cultă",
        "/romana/textul-literar-si-nonliterar",

        "/romana/scrierea-reflexiva",
        "/romana/scrierea-imaginativa",
        "/romana/scrierea-funcționala",

        "/romana/interese-și-preferinte-de-lectura",
        "/romana/raftul-de-literature-artistica",
        "/romana/interconexiunea-literaturii-cu-alte-disciplini",
        "/romana/literatura-in-viata-cotidiana",
    
    ];

        $chapterId = Chapter::firstWhere('name', $chapters[$this->index])->id;

        $name = $this->values[$this->index % count($this->values)];
        $path = $paths[$this->index];

        $this->orderNumber = isset($this->previousChapterId) && $chapterId == $this->previousChapterId
            ? $this->orderNumber + 1
            : 1;

        $this->previousChapterId = $chapterId;

        $this->index++;

        return [
            'name' => $name,
            'path' => $path,
            'order_number' => $this->orderNumber,
            'chapter_id' => $chapterId,
        ];
    }
}

