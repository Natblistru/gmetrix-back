<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvaluationSource;

class EvaluationSourceFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $titles = ['SURSA A. REPERE CRONOLOGICE',
                'SURSA B.',
                'SURSA C.',
                'SURSA D.'
            ];
        $contents = [ 
            ["1" => "1922 - Marșul fasciștilor italieni spre Roma",
            "2" => "1926 - Formarea guvernului Unității Naționale a Franței în frunte cu R. Poincare",
            "3" => "1928 - Reforma electorală în Marea Britanie prin care femeile au primit dreptul la vot",
            "4" => "1929 - 1933 - Marea criză economică",
            "5" => "1933 - Numirea lui Adolf Hitler în postul de cancelar al Germaniei",
            "6" => "1939 - Începutul celui de-al Doilea Război Mondial",
            ],

            ["1" => '"În statele democratice din perioada interbelică a funcţionat principiul separarării puterilor. Parlamentarismul a continuat să fie o trăsătură caracteristică importantă în aceste ţări. Sistemul lor politic era pluripartit şi erau organizate alegeri libere, pe baza votului universal. Libertatea presei şi respectarea drepturilor cetăţenilor au fost alte trăsături ale regimurilor democratice. Perioada interbelică a fost marcată şi de lupta de emancipare a femeilor, care s-au implicat în viaţa economică. Totuşi, doar în unele ţări acestea au obţinut drepturi politice egale cu bărbaţii. În anii 1922-1928, lumea a cunoscut o perioadă de dezvoltare economică. Marea criză economică (1929-1933) a putut fi depăşită prin aplicarea unor politici economice complexe."'
            ],

            ["1" => '"Regimurile politice totalitare au fost opuse celor democratice. Regimurile totalitare aveau trăsături comune. Ideologia comunistă susţinea că reprezentă interesele proletariatului, dar, în realitate, era dictatura partidului comunist, a nomenclaturii. Cea fascistă accentua rolul statului şi supunerea indivizilor faţă de acesta, iar naţional-socialismul era o ideologie naţionalistă, rasistă şi antisemită. În cadrul regimurilor de extremă-dreaptă, apărute ca reacţie la comunism, economia de piaţă a continuat să existe, dar statul intervenea în cadrul ei, pentru a controla tensiunile sociale."'
            ],

            ["1" => '"Dacă primul mandat a lui Roosevelt a fost absorbit de criză, al doilea mandat a fost dominat de probleme internaționale. La început, conștient de atașamentul americanilor față de izolaționism, Roosevelt lasă Congresul să voteze legea neutralității (1935). Apoi, neliniștit de agresiunea germană, italiană și japoneză, amenință aceste țări cu punerea în carantină (1937)[...] Roosevelt refuză totuși să ajute Franța sfâșiată și slăbită, în 1940[...]. Reales în 1940 cu o majoritate redusă, decide să ajute Marea Britanie, rămasă singură în luptă, făcând Congresul să voteze legea armamentului (Lend-Lease), extinsă în 1941 și la URSS, angajând industria americană în producția de război."'
            ],
        ];

        $authors = ['',
                'Pierre Milza, Serge Bernstein',
                'Ewan Murray',
                'Dominique Vallaud'
            ];

        $text_sourses = ['',
                'Istoria secolului XX ,Bucureşti, 1998, vol.I',
                'Shut Up: Tale of Totalitarianism, 2005',
                'Dicționar istoric, București, 2008'
            ];

        $title = $titles[$this->index];
        $content = json_encode($contents[$this->index]);
        $author = $authors[$this->index];
        $text_sourse = $text_sourses[$this->index];


        $this->index++;

        return [
            'order_number' => $this->index,
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'text_sourse' => $text_sourse,
        ];
    }
}
