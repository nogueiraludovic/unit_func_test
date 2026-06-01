<?php

namespace Vd\VdSqliCalculetteAci\Controller;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
use Vd\VdSqliCalculetteAci\Exception\FileNotFoundException;
use Vd\VdSqliCalculetteAci\Utility\CalculImpot;
use Vd\VdSqliCalculetteAci\Utility\DataParser;

use function array_merge;
use function array_values;
use function count;
use function date;
use function file_exists;
use function file_get_contents;
use function is_array;
use function is_numeric;
use function number_format;
use function preg_replace;
use function str_replace;
use function strtolower;
use function substr;

class Affichage extends AbstractPlugin
{
    public const _FAMILLE_MONO = 3;
    public const _COMMUNE_NORM = 'commune_norm';

    /**
     * @var MarkerBasedTemplateService
     */
    protected $templateService;

    protected $emConf;

    /**
     * @var array liste des libellés referencés avec le même ID que le champ calculé
     */
    private $labels = [
        'taux_cant_54' => 'Taux cantonal [ID 54]',
        'taux_comm_55' => 'Taux communal [ID 55]',
        'quot_etc_56' => 'Quotient état civil [ID 56]',
        'txt_etc_57' => 'État civil [ID 57]',
        'quot_enf_58' => 'Quotient enfant [ID 58]',
        'quot_fam_59' => 'Quotient familiale [ID 59]',
        'rab_enf_60' => 'Rabais par enfant [ID 60]',
        'rab_IFD_61' => 'Rabais IFD [ID 61]',
        'bar_rev_ICC_62' => 'Barème revenu ICC [ID 62]',
        'bar_fort_63' => 'Bareme fortune ICC [ID 63]',
        'bar_rev_IFD_64' => 'Bareme revenu IFD [ID 64]',
        'plaf_quot_65' => 'Plafond quotient [ID 65]',
        'rev_taux_66' => 'Revenu pour le taux [ID 66]',
        'plaf_quot_67' => 'Plafond quotient [ID 67]',
        'rat_rev_quot_68' => 'Revenu / quotient [ID 68]',
        'ded_69' => 'Déduction [ID 69]',
        'rev_plaf_taux_70' => 'Revenu plafond p taux [ID 70]',
        'rev_vs_pq_71' => 'Revenu / plafond quotient [ID 71]',
        'rev_imp_bar_72' => 'Revenu imposable (Barème) [ID 72]',
        'diff_taux_imp_73' => 'Différence taux-imposable [ID 73]',
        'supp_100_74' => 'Supplément par 100 fr [ID 74]',
        'imp_ann_bar_75:' => 'Impôt annuel (Barême) [ID 75]',
        'supp_imp_76' => 'Supplément d\'impôt[ID 76]',
        'imp_base_77' => 'Impôt de base (100%) [ID 77]',
        'pourc_taux_78' => '% pour le taux [ID 78]',
        'rev_imp_calc_79' => 'Revenu imposable [ID 79]',
        'imp_cant_base_80' => 'Impôt cantonal de base [ID 80]',
        'coeff_imp_cant_rev_81' => 'Coeff Impôt cantonal sur le revenu [ID 81]',
        'imp_cant_rev_82' => 'Impôt cantonal sur le revenu [ID 82]',
        'coeff_imp_comm_rev_83' => 'Coeff Impôt communal sur le revenu [ID 83]',
        'imp_comm_rev_84' => 'Impôt communal sur le revenu [ID 84]',
        'tot_ICC_85' => 'Total impôt ICC [ID 85]',
        'max30_rev_imp_86' => 'Max 30% Revenu imposable [ID 86]',
        'coeff_imp_cant_base_87' => 'Coeff Impôt cantonal de base [ID 87]',
        'imp_cant_base_coeff_88' => 'Impôt cantonal de base [ID 88]',
        'imp_cant_rev_89' => 'Impôt cantonal sur le revenu [ID 89]',
        'imp_comm_rev_calc_90' => 'Impôt communal sur le revenu[ID 90]',
        'fort_taux_91' => 'Fortune pour le taux [ID 91]',
        'fort_imp_bar_92' => 'Fortune imposable (Barème) [ID92]',
        'diff_taux_imp_93' => 'Différence [ID 93]',
        'supp_1000_94' => 'Supplément par 1000 fr [ID 94]',
        'imp_ann_fort_bar_95' => 'Impôt annuel (Barème) [ID 95]',
        'supp_imp_fort_96' => 'Supplément d\'impôt [ID 96]',
        'imp_base_fort_97' => 'Impôt de base sur fortune [ID 97]',
        'pourc_taux_fort_98' => '% pour le taux [ID 98]',
        'fort_imp_99' => 'Fortune imposable [ID 99]',
        'imp_cant_base_fort_100' => 'Impôt cantonal de base [ID 100]',
        'min_imp_fort_101' => 'Minimum imposable [ID 101]',
        'is_fort_imp_102' => 'Fortune imposable? [ID 102]',
        'coeff_imp_cant_fort_103' => 'Coeff Impôt cantonal sur fortune [ID 103]',
        'imp_cant_fort_104' => 'Impôt cantonal sur fortune [ID 104]',
        'coeff_imp_comm_fort_105' => 'Coeff Impôt communal sur fortune [ID 105]',
        'imp_comm_fort_calc_106' => 'Impôt communal sur fortune [ID 106]',
        'tot_imp_fort_107' => 'Total impôt fortune [ID 107]',
        'max1_fort_imp_108' => 'Max 1% sur fortune impôsable [ID108]',
        'coeff_imp_base_fort_109' => 'Coeff Impôt de base sur fortune [ID 109]',
        'imp_base_fort_calc_110' => 'Impôt de base sur fortune [ID 110]',
        'imp_cant_fort_calc_111' => 'Impôt cantonal sur fortune [ID 111]',
        'imp_comm_fort_calc_112' => 'Impôt communal sur fortune [ID 112]',
        'rev_taux_IFD_113' => 'Revenu pour le taux [ID 113]',
        'rev_imp_bar_114' => 'Revenu imposable (Barème) [ID 114]',
        'diff_bar_taux_IFD_115' => 'Différence [ID 115]',
        'supp_100_IFD_116' => 'Supplément par 100 fr [116]',
        'imp_ann_IFD_117' => 'Impôt annuel (Barème) [ID 117]',
        'supp_IFD_118' => 'Supplément d\'impôt [ID 118]',
        'imp_base_IFD_119' => 'Impôt de base IFD[ID 119]',
        'pourc_taux_IFD_120' => '% pour le taux [ID120]',
        'rev_imp_IFD_121' => 'Revenu imposable [ID 121]',
        'imp_IFD_base_122' => 'Impôt IFD de base [ID 122]',
        'imp_rab_IFD_bar_123' => 'Rabais IFD (Barème)[ID 123]',
        'rab_IFD_124' => 'Rabais [ID 124]',
        'imp_IFD_calc_125' => 'Impôt IFD [ID 125]',
        'fac_imp_IFD_155' => 'Facteur Impôt IFD [ID 155]',
        'imp_IFD_156' => 'Impôt IFD [ID 156]',
        'fac_imp_base_149' => 'Facteur Impôt de base [ID 149]',
        'imp_base_cap_150' => 'Impôt de base [ID 150]',
        'coeff_imp_cant_cap_151' => 'Coeff Impôt cantonal [ID 151]',
        'imp_cant_cap_152' => 'Impôt cantonal [ID 152]',
        'coeff_imp_comm_cap_153' => 'Coeff Impôt communal [ID 153]',
        'imp_comm_cap_154' => 'Impôt communal [ID 154]',
        'affiche_ICC_126' => 'ICC [ID 126]',
        'affiche_rep_127' => 'Répartition [ID 127]',
        'affiche_IFD_128' => 'IFD [ID 128]',
        'affiche_dist_131' => 'Impôt distinct [ID 131]',
        'quot_fam_129' => 'Quotient familiale [ID 129]',
        'rev_taux_130' => 'Revenu pour le taux [ID 130]',
        'rev_imp_132' => 'Revenu imposable [ID 132]',
        'imp_base_133' => 'Impôt base [ID 133]',
        'rab_IFD_134' => 'Rabais IFD [ID 134]',
        'charge_fisc_rev_135' => 'Charge fiscale revenu [135]',
        'rev_imp_136' => 'Revenu Imposable [ID 136]',
        'fort_imp_137' => 'Fortune Imposable [ID 137]',
        'coeff_cant_138' => 'Coeff Canton [ID 138]',
        'coeff_comm_139' => 'Coeff Commune [ID 139]',
        'charge_fisc_rev_cant_140' => 'Charge fiscale revenu Canton [ID 140]',
        'charge_fisc_rev_comm_141' => 'Charge fiscale revenu Commune [ID 141]',
        'comm_charge_rev_142' => 'Commune pour charge revenu',
        'charge_fisc_fort_cant_143' => 'Charge fiscale fortune Canton [ID 143]',
        'charge_fisc_fort_cant_144' => 'Charge fiscale fortune Commune [ID 144]',
        'comm_charge_fort_146' => 'Commune pour charge fortune',
        'tot_final_ICC_147' => 'Total ICC [ID 147]',
        'tot_ICC_IFD_148' => 'Total ICC+IFD [ID 148]',
        'imp_base_rev_158' => 'Impôt base Revenu [ID 158]',
        'imp_base_fort_159' => 'Impôt base Fortune [ID 159]',
        'plaf_deduction_ICC_161' => 'Plafond déduction ICC [ID 161]',
        'abattement_162' => 'Pourcentage abattement [ID 162]',
        'montant_abattement_163' => 'Montant de l\'abattement [ID 163]',
        'impot_cantonal_apres_abattement_164' => 'Impôt cantonal après abattement [ID 164]'
    ];

    private $currPeriodeCalc;
    private $currEtatCivil;
    private $currNEnfantsMen;
    private $currNEnfants;
    private $currNEnfantsDemiQuotient;
    private $currCommune;
    private $currRevenuImposableICC;
    private $currTauxRevImpICC;
    private $currFortuneImposableICC;
    private $currTauxFortImpICC;
    private $currRevenuImpIFD;
    private $currTauxRevenuImpIFD;
    private $currSelectedCalcICC;
    private $currSelectedCalcIFD;
    private $currSelectedRepartition;
    private $currSelectedImpotDistinct;
    private $modeAffichage;

    public $prefixId = 'tx_vdsqlicalculetteaci_affichage';
    public $scriptRelPath = 'Classes/Controller/Affichage.php';
    public $extKey = 'vd_sqli_calculetteaci';

    /**
     * @param $caller
     */
    public function init($caller)
    {
        $this->templateService = GeneralUtility::makeInstance(MarkerBasedTemplateService::class);
        $this->currPeriodeCalc = $this->getAnneeDefault();
        $this->currEtatCivil = '-';
        $this->currNEnfantsMen = 0;
        $this->currNEnfants = 0;
        $this->currNEnfantsDemiQuotient = 0;
        $this->currCommune = '-';
        $this->currRevenuImposableICC = '';
        $this->currTauxRevImpICC = '';
        $this->currFortuneImposableICC = '';
        $this->currTauxFortImpICC = '';
        $this->currRevenuImpIFD = '';
        $this->currTauxRevenuImpIFD = '';
        $this->currSelectedCalcICC = '';
        $this->currSelectedCalcIFD = '';
        $this->currSelectedRepartition = '';
        $this->currSelectedImpotDistinct = '';
        $this->modeAffichage = 0;
        // @extensionScannerIgnoreLine
        $this->conf = $caller->conf;
        $this->setDonneesGUI($caller->piVars);
        $this->pi_setPiVarDefaults();
        // @extensionScannerIgnoreLine
        $this->pi_USER_INT_obj = true;
        $this->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        $assetCollector->addJavaScript(
            'vd-sqli-calculetteaci',
            'EXT:vd_sqli_calculetteaci/Resources/Public/JavaScript/vd_sqlicalculetteaci.js'
        );
        $assetCollector->addStyleSheet(
            'vd-sqli-calculetteaci',
            'EXT:vd_sqli_calculetteaci/Resources/Public/Css/vd_sqli_calculetteaci.css'
        );
    }

    /**
     * @param string $value
     * @return mixed|string
     */
    private function encodeCommuneName($value)
    {
        $result = strtolower($value);
        $alias_e = ['é', 'è', 'ê', 'ë'];
        $result = str_replace($alias_e, 'e', $result);
        $alias_a = ["'á", 'à', 'â', 'ä', 'ã'];
        $result = str_replace($alias_a, 'a', $result);
        $alias_o = ["'ó", 'ò', 'ô', 'ö', 'õ'];
        $result = str_replace($alias_o, 'o', $result);
        $alias_i = ['í', 'ì', 'î', 'ï'];
        $result = str_replace($alias_i, 'i', $result);
        $alias_u = ['ú', 'ù', 'û', 'ü'];
        $result = str_replace($alias_u, 'u', $result);
        $result = preg_replace('/[^a-zA-Z0-9]+/', '', $result);

        return substr($result, 0, 500);
    }

    private function setDonneesGUI(array $source)
    {
        if (isset($source['afficher'])) {
            if (is_numeric($source['periode']) && (int)$source['periode']) {
                $this->currPeriodeCalc = (int)$source['periode'];
            } else {
                $this->currPeriodeCalc = null;
            }

            if (is_numeric($source['etatCivil'])
                && (int)$source['etatCivil'] > 0
                && (int)$source['etatCivil'] < 4
            ) {
                $this->currEtatCivil = (int)$source['etatCivil'];
            } else {
                $this->currEtatCivil = null;
            }

            if (is_numeric($source['noEnfantsMenage'])
                && (int)$source['noEnfantsMenage'] >= 0
                && (int)$source['noEnfantsMenage'] <= 10
            ) {
                $this->currNEnfantsMen = (int)$source['noEnfantsMenage'];
            } else {
                $this->currNEnfantsMen = 0;
            }

            if (is_numeric($source['noEnfants'])
                && (int)$source['noEnfants'] >= 0
                && (int)$source['noEnfants'] <= 10
            ) {
                $this->currNEnfants = (int)$source['noEnfants'];
            } else {
                $this->currNEnfants = 0;
            }
            if (is_numeric($source['noEnfantsDemiQuotient'])
                && (int)$source['noEnfantsDemiQuotient'] >= 0
                && (int)$source['noEnfantsDemiQuotient'] <= 10
            ) {
                $this->currNEnfantsDemiQuotient = (int)$source['noEnfantsDemiQuotient'];
            } else {
                $this->currNEnfantsDemiQuotient = 0;
            }
            $this->currCommune = $this->encodeCommuneName($source['commune']);

            if (is_numeric($source['revenuImposableICC']) && (int)$source['revenuImposableICC'] >= 0
            ) {
                $this->currRevenuImposableICC = $source['revenuImposableICC'];
            } else {
                $this->currRevenuImposableICC = 0;
            }

            if (is_numeric($source['tauxRevenuImposableICC']) && (int)$source['tauxRevenuImposableICC'] >= 0
            ) {
                $this->currTauxRevImpICC = $source['tauxRevenuImposableICC'];
            } else {
                $this->currTauxRevImpICC = 0;
            }

            if (is_numeric($source['fortuneImposableICC']) && (int)$source['fortuneImposableICC'] >= 0
            ) {
                $this->currFortuneImposableICC = $source['fortuneImposableICC'];
            } else {
                $this->currFortuneImposableICC = 0;
            }

            if (is_numeric($source['tauxFortuneImposableICC']) && (int)$source['tauxFortuneImposableICC'] >= 0
            ) {
                $this->currTauxFortImpICC = $source['tauxFortuneImposableICC'];
            } else {
                $this->currTauxFortImpICC = 0;
            }

            if (is_numeric($source['revenuImposableIFD']) && (int)$source['revenuImposableIFD'] >= 0
            ) {
                $this->currRevenuImpIFD = $source['revenuImposableIFD'];
            } else {
                $this->currRevenuImpIFD = 0;
            }

            if (is_numeric($source['tauxRevenuImposableIFD']) && (int)$source['tauxRevenuImposableIFD'] >= 0
            ) {
                $this->currTauxRevenuImpIFD = $source['tauxRevenuImposableIFD'];
            } else {
                $this->currTauxRevenuImpIFD = 0;
            }

            $this->currSelectedCalcICC = $this->radioChecked($source['calculICC']);
            $this->currSelectedCalcIFD = $this->radioChecked($source['calculIFD']);
            $this->currSelectedRepartition = $this->radioChecked($source['repartition']);
            $this->currSelectedImpotDistinct = $this->radioChecked($source['impotDistinct']);
            $this->modeAffichage = 1;
        } else {
            $this->currPeriodeCalc = '';
            $this->currEtatCivil = '-';
            $this->currNEnfantsMen = 0;
            $this->currNEnfants = 0;
            $this->currNEnfantsDemiQuotient = 0;
            $this->currCommune = '-';
            $this->currRevenuImposableICC = '';
            $this->currTauxRevImpICC = '';
            $this->currFortuneImposableICC = '';
            $this->currTauxFortImpICC = '';
            $this->currRevenuImpIFD = '';
            $this->currTauxRevenuImpIFD = '';
            $this->currSelectedCalcICC = '';
            $this->currSelectedCalcIFD = '';
            $this->currSelectedRepartition = '';
            $this->currSelectedImpotDistinct = '';
            $this->modeAffichage = 0;
        }
    }

    /**
     * data - following indexes
     *         'periode'
     *         'etat_civil'
     *         'n_enfants_menage'
     *         'n_enfants'
     *          'n_enfants_demi_quotient'
     *         'commune'
     *         'revenu_imposable_ICC'
     *         'taux_revenu_ICC'
     *         'fortune_imposable_ICC'
     *         'taux_fortune_ICC'
     *         'revenu_imposable_IFD'
     *         'taux_revenu_IFD'
     *         'is_calc_ICC'
     *         'is_calc_IFD'
     *         'is_repartition'
     *         'is_distinct'
     *
     * @return array
     */
    private function getDonneesGUI(): array
    {
        return [
            'periode' => $this->currPeriodeCalc,
            'etat_civil' => $this->currEtatCivil,
            'n_enfants_menage' => $this->currNEnfantsMen,
            'n_enfants' => $this->currNEnfants,
            '$n_enfants_demi_quotient' => $this->currNEnfantsDemiQuotient,
            'commune' => $this->currCommune,
            'revenu_imposable_ICC' => $this->currRevenuImposableICC,
            'taux_revenu_ICC' => $this->currTauxRevImpICC,
            'fortune_imposable_ICC' => $this->currFortuneImposableICC,
            'taux_fortune_ICC' => $this->currTauxFortImpICC,
            'revenu_imposable_IFD' => $this->currRevenuImpIFD,
            'taux_revenu_IFD' => $this->currTauxRevenuImpIFD,
            'is_calc_ICC' => $this->currSelectedCalcICC,
            'is_calc_IFD' => $this->currSelectedCalcIFD,
            'is_repartition' => $this->currSelectedRepartition,
            'is_distinct' => $this->currSelectedImpotDistinct,
            'mode_affichage' => $this->modeAffichage
        ];
    }

    /**
     * @return number
     */
    private function getAnneeDefault()
    {
        return (int)(date('Y') - 1);
    }

    /**
     * @param $radioValue
     * @return string
     */
    private function radioChecked($radioValue): string
    {
        if (isset($radioValue)) {
            return ' checked ';
        }
        return '';
    }

    /**
     * @param $value
     * @param int $precision
     * @return string|null
     */
    private function prepareValue($value, $precision = 0)
    {
        $value = str_replace(',', '.', $value);

        if (is_numeric($value)) {
            return number_format(($value), $precision, '.', '\'');
        }

        return null;
    }

    /**
     * @param $donneesBase
     * @param $donneesRevenu
     * @param $donneesFortuneICC
     * @param $donneesRevenuIFD
     * @param $donneesCapital
     * @param $donneesImp
     * @return array
     */
    private function getMarkerArrayDetails(
        $donneesBase,
        $donneesRevenu,
        $donneesFortuneICC,
        $donneesRevenuIFD,
        $donneesCapital,
        $donneesImp
    ): array {
        function detailContainer($label, $detail)
        {
            return '<tr><td>' . $label . '</td><td>' . $detail . '</td></tr>';
        }

        function getDetailTable(
            array $labels,
            array $details, /*string*/
            $headerTxt
        ) {
            $table_detail = '<table class="table">';
            $table_detail .= '<tr><thead class="thead-default"><th colspan="2">' . $headerTxt . '</th></thead></tr>';
            $table_detail .= '<tbody>';
            foreach ($details as $key => $value) {
                $table_detail .= detailContainer(($labels[$key] ?: $key), $value);
            }
            $table_detail .= '</tbody>';
            $table_detail .= '</table>';
            return $table_detail;
        }

        $marker_detail = [];
        $marker_detail['###DETAIL_DONNEES_BASE###'] = getDetailTable($this->labels, $donneesBase, 'Données de base');
        $marker_detail['###DETAIL_IMPOT_REVENU_ICC###'] = getDetailTable(
            $this->labels,
            $donneesRevenu,
            'Impot revenu ICC'
        );
        $marker_detail['###DETAIL_IMPOT_FORTUNE_ICC###'] = getDetailTable(
            $this->labels,
            $donneesFortuneICC,
            'Impot fortune ICC'
        );
        $marker_detail['###DETAIL_IMPOT_REVENU_IFD###'] = getDetailTable(
            $this->labels,
            $donneesRevenuIFD,
            'Impot revenu IFD'
        );
        $marker_detail['###DETAIL_CAPITAL_DISTINCT###'] = getDetailTable(
            $this->labels,
            $donneesCapital,
            'Impot sur capital distinct'
        );
        $marker_detail['###DETAIL_IMPRESSION###'] = getDetailTable($this->labels, $donneesImp, 'Impression');

        return $marker_detail;
    }

    /**
     * @param $page_link
     * @param $periodes
     * @param $etatsCivil
     * @param $enfants
     * @param $communes
     * @return array
     */
    private function getMarkerArraySaisie($page_link, $periodes, $etatsCivil, $enfants, $communes): array
    {
        function getOption(
            $id,
            $value,
            $label,
            $selected = false,
            $dataPeriode = false
        ) {
            $selectedTxt = '';
            if ($selected) {
                $selectedTxt = ' selected="selected" ';
            }
            if ($dataPeriode) {
                $dataPeriode = ' data-periode="' . $dataPeriode . '"';
            }
            return '<option id="' . $id . /*'" name="' . $id . */
                '" value="' . $value . '" ' . $selectedTxt . $dataPeriode . ' >' . $label . '</option>';
        }

        $markerArraySaisie = [];
        $markerArraySaisie['###ERROR###'] = '';

        $markerArraySaisie['###FORM_ACTION###'] = $page_link . '#h2_vd_calculette_resultats';//'javascript:manager_calc.loadForm('..')';// //($this->modeAffichage?'#resCalc':'');
        // mise en place de l'état des checkbox
        $markerArraySaisie['###CALCUL_ICC_SELECTED###'] = $this->currSelectedCalcICC;
        $markerArraySaisie['###CALCUL_IFD_SELECTED###'] = $this->currSelectedCalcIFD;
        $markerArraySaisie['###REPARTITION_SELECTED###'] = $this->currSelectedRepartition;
        // chargement des valeurs saisies
        $markerArraySaisie['###IMPOT_DISTINCT_SELECTED###'] = $this->currSelectedImpotDistinct;
        $markerArraySaisie['###REVENU_IMPOSABLE_ICC###'] = $this->currRevenuImposableICC;
        $markerArraySaisie['###TAUX_REVENU_IMPOSABLE_ICC###'] = $this->currTauxRevImpICC;
        $markerArraySaisie['###FORTUNE_IMPOSABLE_ICC###'] = $this->currFortuneImposableICC;
        $markerArraySaisie['###TAUX_FORTUNE_IMPOSABLE_ICC###'] = $this->currTauxFortImpICC;
        $markerArraySaisie['###REVENU_IMPOSABLE_IFD###'] = $this->currRevenuImpIFD;
        $markerArraySaisie['###TAUX_REVENU_IMPOSABLE_IFD###'] = $this->currTauxRevenuImpIFD;
        // Mise en place des classes de visibilité des éléments dynamiques
        $markerArraySaisie['###REPARTITION_HIDDEN_CLASS###'] = (!empty($this->currSelectedCalcICC)
        || !empty($this->currSelectedCalcIFD) ? '' : 'hidden');
        $markerArraySaisie['###REPARTITION_DESELECTED###'] = (!empty($this->currSelectedRepartition) ? '' : 'hidden');
        $markerArraySaisie['###DISTINCT_DESELECTED###'] = (!empty($this->currSelectedImpotDistinct) ? '' : 'hidden');
        $markerArraySaisie['###NOT_DISTINCT###'] = (!empty($this->currSelectedImpotDistinct) ? 'hidden' : '');
        $markerArraySaisie['###ERR_MONO###'] = ((int)$this->currEtatCivil == self::_FAMILLE_MONO && ($this->currNEnfants + $this->currNEnfantsDemiQuotient >= $this->currNEnfantsMen) && ($this->currNEnfants + $this->currNEnfantsDemiQuotient < 1 || $this->currNEnfantsMen < 1) ? '' : 'hidden');
        $markerArraySaisie['###ERR_ENFANTS###'] = (($this->currNEnfants + $this->currNEnfantsDemiQuotient < $this->currNEnfantsMen) ? '' : 'hidden');

        // Mise en place des options des menus
        $optionsPeriode = '';
        for ($i = 0; $i < count($periodes); $i++) {
            $optionsPeriode .= getOption(
                'periode_' . $periodes[$i],
                $periodes[$i],
                $periodes[$i],
                ($this->currPeriodeCalc == $periodes[$i])
            );
        }
        $markerArraySaisie['###OPTIONS_PERIODE###'] = $optionsPeriode;

        $optionsEtatCivil = getOption('etatCivil_null', '', '', (!isset($this->currEtatCivil)));
        for ($i = 0; $i < count($etatsCivil); $i++) {
            $optionsEtatCivil .= getOption(
                'etatCivil' . (int)$etatsCivil[$i]['code'],
                (int)$etatsCivil[$i]['code'],
                $etatsCivil[$i]['etat_civil'],
                ($this->currEtatCivil == $etatsCivil[$i]['code'])
            );
        }
        $markerArraySaisie['###OPTIONS_ETAT_CIVIL###'] = $optionsEtatCivil;

        $optionsEnfants = '';
        for ($i = 0; $i < count($enfants); $i++) {
            $optionsEnfants .= getOption(
                'enfant' . $i,
                (int)$enfants[$i]['n_enfants'],
                (int)$enfants[$i]['n_enfants'],
                ($this->currNEnfants == $enfants[$i]['n_enfants'])
            );
        }
        $markerArraySaisie['###OPTIONS_NO_ENFANTS###'] = $optionsEnfants;

        $optionsEnfants = '';
        for ($i = 0; $i < count($enfants); $i++) {
            $optionsEnfants .= getOption(
                'enfantMenage' . $i,
                (int)$enfants[$i]['n_enfants'],
                (int)$enfants[$i]['n_enfants'],
                ($this->currNEnfantsMen == $enfants[$i]['n_enfants'])
            );
        }
        $markerArraySaisie['###OPTIONS_NO_ENFANTS_MENAGE###'] = $optionsEnfants;

        //gestion du demi quotien enfant
        $optionsEnfants = '';
        for ($i = 0; $i < count($enfants); $i++) {
            $optionsEnfants .= getOption(
                'enfantDemiQuotient' . $i,
                (int)$enfants[$i]['n_enfants'],
                (int)$enfants[$i]['n_enfants'],
                ($this->currNEnfantsDemiQuotient == $enfants[$i]['n_enfants'])
            );
        }
        $markerArraySaisie['###OPTIONS_NO_ENFANTS_DEMI_QUOTIENT###'] = $optionsEnfants;

        $optionsCommune = getOption('commune_null', '', '', (!isset($this->currCommune)));
        for ($i = 0; $i < count($communes); $i++) {
            $optionsCommune .= getOption(
                'commune' . $i,
                $communes[$i][self::_COMMUNE_NORM],
                $communes[$i]['commune'],
                $this->currCommune == $communes[$i][self::_COMMUNE_NORM],
                $communes[$i]['annee_fiscale']
            );
        }
        $markerArraySaisie['###OPTIONS_COMMUNE###'] = $optionsCommune;

        return $markerArraySaisie;
    }

    /**
     * @param $donnes
     * @return array
     */
    private function getMarkerArrayResultat($donnes): array
    {
        $markerArrayResultat = [];
        $markerArrayResultat['###PART_FAMILLE###'] = $this->prepareValue($donnes['quot_fam_129'], 2);
        $markerArrayResultat['###REVENU_TAUX###'] = $this->prepareValue($donnes['rev_taux_130']);
        $markerArrayResultat['###REVENU_MONTANT_IMPOSABLE###'] = $this->prepareValue($donnes['rev_imp_136']);
        $markerArrayResultat['###REVENU_IMPOT_BASE###'] = $this->prepareValue($donnes['imp_base_rev_158'], 2);
        $markerArrayResultat['###FORTUNE_MONTANT_IMPOSABLE###'] = $this->prepareValue($donnes['fort_imp_137']);
        $markerArrayResultat['###FORTUNE_IMPOT_BASE###'] = $this->prepareValue($donnes['imp_base_fort_159'], 2);
        $markerArrayResultat['###REVENU_COEFICIENT_CANTONAL###'] = $this->prepareValue($donnes['coeff_cant_138'], 1);
        $markerArrayResultat['###REVENU_CHARGE_FISCALE_CANTONAL###'] = $this->prepareValue(
            $donnes['charge_fisc_rev_cant_140'],
            2
        );
        $markerArrayResultat['###REVENU_COEFICIENT_COMMUNAL###'] = $this->prepareValue($donnes['coeff_comm_139'], 1);
        $markerArrayResultat['###REVENU_CHARGE_FISCALE_COMMUNAL###'] = $this->prepareValue(
            $donnes['charge_fisc_rev_comm_141'],
            2
        );
        $markerArrayResultat['###FORTUNE_CHARGE_FISCALE_CANTONAL###'] = $this->prepareValue(
            $donnes['charge_fisc_fort_cant_143'],
            2
        );
        $markerArrayResultat['###FORTUNE_CHARGE_FISCALE_COMMUNAL###'] = $this->prepareValue(
            $donnes['charge_fisc_fort_cant_144'],
            2
        );
        $markerArrayResultat['###TOTAL_ICC###'] = $this->prepareValue($donnes['tot_final_ICC_147'], 2);
        $markerArrayResultat['###REVENU_IFD###'] = $this->prepareValue($donnes['rev_imp_132']);
        $markerArrayResultat['###IMPOT_BASE_IFD###'] = $this->prepareValue($donnes['imp_base_133'], 2);
        $markerArrayResultat['###RABAIS_IFD###'] = $this->prepareValue($donnes['rab_IFD_134'], 2);
        $markerArrayResultat['###TOTAL_IFD###'] = $this->prepareValue((float)$donnes['charge_fisc_rev_135'], 2);
        $markerArrayResultat['###TOTAL_ICC_IFD###'] = $this->prepareValue((float)$donnes['tot_ICC_IFD_148'], 2);
        $markerArrayResultat['###COMMUNE_REVENU###'] = $donnes['comm_charge_rev_142'];
        $markerArrayResultat['###COMMUNE_FORTUNE###'] = $donnes['comm_charge_fort_146'];

        return $markerArrayResultat;
    }

    /**
     * @param bool $cacherAvantCalcul
     * @param bool $detailCalcul
     * @param string $templateFolder
     * @return string
     */
    public function getContent($cacherAvantCalcul = false, $detailCalcul = false): string
    {
        $donneesCourantes = $this->getDonneesGUI();

        // Données de calcul: mise en forme avec les arrondis souhaités pour les calculs
        $donneesCalcul = CalculImpot::formatEntree(
            $donneesCourantes['periode'],
            $donneesCourantes['etat_civil'],
            $donneesCourantes['n_enfants_menage'],
            $donneesCourantes['n_enfants'],
            $donneesCourantes['$n_enfants_demi_quotient'],
            $donneesCourantes['commune'],
            $donneesCourantes['revenu_imposable_ICC'],
            $donneesCourantes['taux_revenu_ICC'],
            $donneesCourantes['fortune_imposable_ICC'],
            $donneesCourantes['taux_fortune_ICC'],
            $donneesCourantes['revenu_imposable_IFD'],
            $donneesCourantes['taux_revenu_IFD'],
            $donneesCourantes['is_calc_ICC'],
            $donneesCourantes['is_calc_IFD'],
            $donneesCourantes['is_repartition'],
            $donneesCourantes['is_distinct']
        );

        // Données de base
        $donneesBase = CalculImpot::getDonneesBase($donneesCalcul);

        // Calcul impôt sur revenu ICC
        $donneesRevenuICC = CalculImpot::getDonneesRevenuICC($donneesBase, $donneesCalcul);

        // Calcul impôt sur fortune ICC
        $donneesFortuneICC = CalculImpot::getDonneesFortune($donneesBase, $donneesCalcul);

        // Calcul impôt sur revenu IFD
        $donneesRevenuIFD = CalculImpot::getDonneesRevenuIFD($donneesBase, $donneesCalcul);

        // Calcul capital distinct
        $donneesCapital = CalculImpot::getDonneesCapital($donneesCalcul, $donneesRevenuICC);

        // Impression
        $donneesImp = CalculImpot::getDonneesImpression(
            $donneesBase,
            $donneesCalcul,
            $donneesRevenuICC,
            $donneesFortuneICC,
            $donneesRevenuIFD,
            $donneesCapital
        );

        // recherche des données pour les menu de l'interface
        $periodesCalc = DataParser::get_periodes();

        if (empty($donneesCalcul['periode'])) {
            $tabEtatCivil = DataParser::get_tab_etat_civil((int)$this->getAnneeDefault());
            $tabEnfant = DataParser::get_tab_enfant((int)$this->getAnneeDefault());
        } else {
            $tabEtatCivil = DataParser::get_tab_etat_civil($donneesCalcul['periode']);
            $tabEnfant = DataParser::get_tab_enfant($donneesCalcul['periode']);
        }

        // Récupération de toutes les communes pour toutes les périodes disponibles
        $tabCommune = [];
        foreach ($periodesCalc as $periode) {
            $tabCommune = array_merge(DataParser::get_tab_taux_commune($periode), $tabCommune);
        }

        $template = [];
        $filepath = 'EXT:vd_sqli_calculetteaci/Resources/Private/Templates/Tmpl17/tx_vdsqlicalculetteaci_index.html';
        if (file_exists($file = GeneralUtility::getFileAbsFileName($filepath))) {
            $template['etvd'] = file_get_contents($file);
        } else {
            throw new FileNotFoundException('File not found:' . $filepath);
        }

        $template['saisie'] = $this->templateService->getSubpart($template['etvd'], '###CHAMPS_SAISIE###');
        $template['resultat'] = $this->templateService->getSubpart($template['etvd'], '###RESULTAT###');
        $template['finFormVide'] = $this->templateService->getSubpart($template['etvd'], '###END_EMPTY_FORM###');
        $template['detail'] = $this->templateService->getSubpart($template['etvd'], '###DETAIL_CALCUL###');
        $template['version'] = $this->templateService->getSubpart($template['etvd'], '###VERSION###');

        //Initialisation des données à affichier dans le formulaire de saisie à afficher
        $markerArraySaisie = $this->getMarkerArraySaisie(
            $this->pi_getPageLink($GLOBALS['TSFE']->id),
            $periodesCalc,
            $tabEtatCivil,
            $tabEnfant,
            $tabCommune
        );

        // Initiliasation des données calculées à afficher
        $markerArrayResultat = $this->getMarkerArrayResultat($donneesImp);

        //Initialisation des valeurs intérmédiares de calcul à afficher
        $markerArrayDetail = $this->getMarkerArrayDetails(
            $donneesBase,
            $donneesRevenuICC,
            $donneesFortuneICC,
            $donneesRevenuIFD,
            $donneesCapital,
            $donneesImp
        );

        // Ajout des données à afficher au contenu de la page
        $content = $this->templateService->substituteMarkerArray($template['saisie'], $markerArraySaisie);

        if ($cacherAvantCalcul) {
            if ($donneesCourantes['mode_affichage'] == 1) {
                $content .= $this->templateService->substituteMarkerArray($template['resultat'], $markerArrayResultat);
            } else {
                $content .= $this->templateService->substituteMarkerArray($template['finFormVide'], []);
            }
        } else {
            $content .= $this->templateService->substituteMarkerArray($template['resultat'], $markerArrayResultat);
        }

        if ($detailCalcul) {
            $content .= $this->templateService->substituteMarkerArray($template['detail'], $markerArrayDetail);
        }

        $content .= $this->templateService->substituteMarkerArray($template['version'], $this->getMarkerArrayVersion());
        return $content;
    }

    /**
     * @return array
     */
    public function getMarkerArrayVersion()
    {
        $versionNumber = '';
        $EM_CONF = [];
        $extPath = ExtensionManagementUtility::extPath('vd_sqli_calculetteaci');
        include($extPath . 'ext_emconf.php');

        if (is_array($EM_CONF)) {
            $conf = array_values($EM_CONF);
            $versionNumber = $conf[0]['version'];
        }

        return [
            '###DETAIL_VERSION###' => '<p class="mt-4 text-center">version ' . $versionNumber . '</p>'
        ];
    }
}
