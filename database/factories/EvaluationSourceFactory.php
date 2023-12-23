<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationSource;

class EvaluationSourceFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $sources = [
            ["title" => 'SURSA A. REPERE CRONOLOGICE',
                "name" => 'Evoluția regimurilor totalitare în perioada interbelică (repere cronologice)',
                "content" => ["1" => "1922 - Marșul fasciștilor italieni spre Roma",
                            "2" => "1926 - Formarea guvernului Unității Naționale a Franței în frunte cu R. Poincare",
                            "3" => "1928 - Reforma electorală în Marea Britanie prin care femeile au primit dreptul la vot",
                            "4" => "1929 - 1933 - Marea criză economică",
                            "5" => "1933 - Numirea lui Adolf Hitler în postul de cancelar al Germaniei",
                            "6" => "1939 - Începutul celui de-al Doilea Război Mondial",
                            ],
                "author" => '',
                "text_sourse" => ''
            ],
            ["title" => 'SURSA B.',
                "name" => 'Caracteristica regimurilor democratice în perioada interbelică (Pierre Milza, Serge Bernstein, Istoria secolului XX ,Bucureşti, 1998)',
                "content" => ["1" => '"În statele democratice din perioada interbelică a funcţionat principiul separarării puterilor. Parlamentarismul a continuat să fie o trăsătură caracteristică importantă în aceste ţări. Sistemul lor politic era pluripartit şi erau organizate alegeri libere, pe baza votului universal. Libertatea presei şi respectarea drepturilor cetăţenilor au fost alte trăsături ale regimurilor democratice. Perioada interbelică a fost marcată şi de lupta de emancipare a femeilor, care s-au implicat în viaţa economică. Totuşi, doar în unele ţări acestea au obţinut drepturi politice egale cu bărbaţii. În anii 1922-1928, lumea a cunoscut o perioadă de dezvoltare economică. Marea criză economică (1929-1933) a putut fi depăşită prin aplicarea unor politici economice complexe."'
                           ],
                "author" => 'Pierre Milza, Serge Bernstein',
                "text_sourse" => 'Istoria secolului XX ,Bucureşti, 1998, vol.I'
            ],            
            ["title" => 'SURSA C.',
                "name" => 'Caracteristica regimurilor totalitare în perioada interbelică (Ewan Murray, Shut Up: Tale of Totalitarianism, 2005)',
                "content" => ["1" => '"Regimurile politice totalitare au fost opuse celor democratice. Regimurile totalitare aveau trăsături comune. Ideologia comunistă susţinea că reprezentă interesele proletariatului, dar, în realitate, era dictatura partidului comunist, a nomenclaturii. Cea fascistă accentua rolul statului şi supunerea indivizilor faţă de acesta, iar naţional-socialismul era o ideologie naţionalistă, rasistă şi antisemită. În cadrul regimurilor de extremă-dreaptă, apărute ca reacţie la comunism, economia de piaţă a continuat să existe, dar statul intervenea în cadrul ei, pentru a controla tensiunile sociale."'
                           ],
                "author" => 'Ewan Murray',
                "text_sourse" => 'Shut Up: Tale of Totalitarianism, 2005'
            ], 
            ["title" => 'SURSA D.',
                "name" => 'Politica externă a lui Roosevelt în al doilea Mandat: de la neutralitate la implicarea în conflictul mondial (Dominique Vallaud, Dicționar istoric, București, 2008)',
                "content" => ["1" => '"Dacă primul mandat a lui Roosevelt a fost absorbit de criză, al doilea mandat a fost dominat de probleme internaționale. La început, conștient de atașamentul americanilor față de izolaționism, Roosevelt lasă Congresul să voteze legea neutralității (1935). Apoi, neliniștit de agresiunea germană, italiană și japoneză, amenință aceste țări cu punerea în carantină (1937)[...] Roosevelt refuză totuși să ajute Franța sfâșiată și slăbită, în 1940[...]. Reales în 1940 cu o majoritate redusă, decide să ajute Marea Britanie, rămasă singură în luptă, făcând Congresul să voteze legea armamentului (Lend-Lease), extinsă în 1941 și la URSS, angajând industria americană în producția de război."'
                         ],
                "author" => 'Dominique Vallaud',
                "text_sourse" => 'Dicționar istoric, București, 2008'
            ],  

            ["title" => 'SURSA A.',
                "name" => 'Legea de Reformă Agrară din 1920 pentru Basarabia: exproprieri și distribuiri de Proprietăți (votată de Parlamentul României la 11 martie 1920)',
                "content" => ["1" => '"Articolul II. […] proprietățile ce sunt expuse exproprierii trec asupra statului, libere de orice obligațiuni sau orice sarcini de orice natură.',
                                "2" => 'Articolul III. Se vor expropria în întregime: a) Proprietățile imobiliare (rurale, urbane) ce aparțin haznalei (statului), udelurilor (coroanei), băncilor țărănești și mănăstirilor din străinătate; b) Proprietățile imobiliare rurale ale supușilor străini […].',
                                "3" => 'Articolul V. Se vor expropria pământurile mănăstirilor locale, lăsându-se fiecărei mănăstiri câte 50 ha pământ cultivabil, viile și grădinile de pomi roditori."',
                                ],
                "author" => '',
                "text_sourse" => '(Din Legea de reformă agrară pentru Basarabia, votată de Parlamentul României la 11 martie 1920)'
            ],
            ["title" => 'SURSA B.',
                "name" => 'Implementarea Reformei Agrare în Basarabia: obstacole și realități (Svetlana Suveică, Basarabia în primul deceniu interbelic (1918-1929). Modernizare prin reforme.)',
                "content" => ["1" => '"Specificul reformei agrare basarabene a constat nu atât în prevederile legislației agrare, cât în aplicarea acetora. Mecanismul de aplicare a fost unul greoi, din cauza dificitului de pământ și a numărului mare de țărani cu drept de împroprietărire, dar și a lipsei unui cadru legislativ, prin aplicarea căruia statul să sprijine proaspătul proprietar […]. În Basarabia au fost expropriate de fapt pământurile ocupate cu forța de țărani în urma mișcărilor țărănești din anii 1917-1918, care, în final, urmau să devină proprietatea de drept și de fapt. Lotul de împroprietărire în Basarabia a fost mai mic decât prevedea legea, din cauza insuficienței acute de pământ și a cererii ridicate pentru acesta comparativ cu alte regiuni ale României."'
                           ],
                "author" => 'Svetlana Suveică',
                "text_sourse" => 'Basarabia în primul deceniu interbelic (1918-1929). Modernizare prin reforme.'
            ], 
            ["title" => 'SURSA C.',
                "name" => 'Impactul Reformei Agrare din 1921: transformări socioeconomice și culturale în mediul rural (Alexandra Georgescu, Cum s-a aplicat reforma agrară din 1921, Adevărul.ro)',
                "content" => ["1" => '"Cu toată rentabilitatea redusă a loturilor, cu toate dificultăţile şi lipsurile prin care avea să treacă ţărănimea mai târziu, situaţia materială a țăranilor a început să se schimbe. În câţiva ani de zile după război au dispărut acoperişurile de paie care mai existau ici-colo înainte de împroprietărire. Mulţi ţărani îşi trimiteau copiii la şcoli în oraşe şi o generaţie nouă de intelectuali se ridica din sate. Erau urmările pozitive ale acestei reforme."'
                           ],
                "author" => 'Alexandra Georgescu',
                "text_sourse" => 'Cum s-a aplicat reforma agrară din 1921// Adevărul.ro'
            ],             
        ];

        $source = $sources[$this->index];

        $name = $source['name'];
        $title = $source['title'];
        $content = json_encode($source['content']);
        $author = $source['author'];
        $text_sourse = $source['text_sourse'];


        $this->index++;

        return [

            // 'order_number' => $this->index,
            'name' => $name,
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'text_sourse' => $text_sourse,
        ];
    }
}
