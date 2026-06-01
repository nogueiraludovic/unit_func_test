<?php

namespace Vd\VdSqliCalculetteAci\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class DataParser
{
    public const _COMMUNE_NORM = 'commune_norm';
    public const defaultDataPath = 'Resources/Private/Data/';
    public const dataDirName = 'Calculette_Tables';
    public const bar_fort = 'Table_Bareme_Fortune.csv';
    public const bar_ICC = 'Table_Bareme_Revenu.csv';
    public const bar_IFD_C = 'Table_Bareme_IFD_Celibataire.csv';
    public const bar_IFD_M = 'Table_Bareme_IFD_Marie.csv';
    //public const plaf_quotient = 'Table_Plafond quotient familial.csv';
    public const tab_enfant = 'Table_Enfants.csv';
    public const tab_etat_civil = 'Table_Etat_civil.csv';
    public const tab_parametres = 'Table_Parametres_generaux.csv';
    public const tab_taux_commune = 'Table_Taux_communaux.csv';
    public const head_bar_fort = 'annee_fiscale;fortune_imposable;impot_annuel;supplement_par_1000';
    public const head_bar_ICC = 'annee_fiscale;bar_canton_revenu_imposable;bar_canton_impot;supplement_par_100';
    public const head_bar_IFD_C = 'annee_fiscale;bar_IFD_celibataire_revenu_imposable;impot_annuel;supplement_par_100';
    public const head_bar_IFD_M = 'annee_fiscale;bar_IFD_marie_revenu_imposable;impot_annuel;supplement_par_100';
    //public const head_plaf_quotient = 'annee_fiscale;etat_civil;n_enfants;code;plafond;deduction';
    public const head_tab_enfant = 'annee_fiscale;n_enfants';
    public const head_tab_etat_civil = 'annee_fiscale;code;etat_civil;quotient';
    public const head_tab_parametres = 'annee_fiscale;facteur_canton;facteur_fédéral;coefficient_canton;fortune_min_imposable_seule;fortune_min_imposable_marie;rabais_impot_IFD;bareme_ICC_Revenu;bareme_ICC_fortune;bareme_IFD_celibataire;bareme_IFD_marie;plafond_quotient_plafond;plafond_quotient_deduction;abattement';
    public const head_tab_taux_commune = 'annee_fiscale;commune;taux';
    public const separateur = ';';
    public const delimiteur = '';
    public const code_celibataire = 1;
    public const code_marie = 2;
    public const bareme_revenu_marie = 'Bar_IFD_M';

    private static $bar_fort;
    private static $bar_ICC;
    private static $bar_IFD_C;
    private static $bar_IFD_M;
    // private static $plaf_quotient;
    private static $tab_enfant;
    private static $tab_etat_civil;
    private static $tab_parametres;
    private static $tab_taux_commune;

    /**
     * prepare un code pour representer le nom des communes afin de normaliser l'input de l'utilisateur
     * et fournir un identifiant.
     *
     * Modifications appliquées:
     * reduit la chaîne à 500 characteres, alphanumériques, minuscules, supprime les accents des voyelles
     *
     * @param le nom d'une commune
     * @return
     */
    private static function encodeCommuneName($value)
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
        $result = str_replace($alias_i, 'u', $result);
        $result = preg_replace('/[^a-zA-Z0-9]+/', '', $result);
        $result = substr($result, 0, 500);
        return $result;
    }

    /**
     * @param $value
     * @param $key
     * @param array $array
     * @return int|string|null
     */
    public static function searcharray(
        $value,
        $key,
        array $array
    ) {
        foreach ($array as $k => $val) {
            if ($val [$key] == $value) {
                return $k;
            }
        }
        return null;
    }

    /**
     * @param $value
     * @param $key
     * @param array $array
     * @return int|string|null
     */
    public static function searchArrayNum(
        $value,
        $key,
        array $array
    ) {
        $last = count($array) - 1;
        if ($array [$last] [$key] <= $value) {
            return $last;
        }

        foreach ($array as $k => $val) {
            if ($val [$key] == $value) {
                return $k;
            }
            if ($val [$key] > $value) {
                return $k - 1;
            }

            /*if ($val [$key] >= $value) {
                if ($k > 1) {
                    return $k-1;
                }
                                else if ($k > 0)  {
                                    return $k;
                }
                                else {
                                    return NULL;
                                }
        }*/
        }
        return null;
    }

    /**
     * retourne la racine
     */
    public static function rootPath()
    {
        return PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath('vd_sqli_calculetteaci'));
    }

    /**
     * @return string
     */
    private static function getDataPath()
    {
        $context = (string)Environment::getContext();

        if ($context === 'Development/DDEV' || $context === 'Production/DDEV') {
            return self::rootPath() . DataParser::defaultDataPath;
        }

        $confArray = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_sqli_calculetteaci');

        if (!isset($confArray['dataPath']) || $confArray['dataPath'] === '') {
            return self::rootPath() . DataParser::defaultDataPath;
        }

        return Environment::getPublicPath() . '/' . $confArray['dataPath'];
    }

    /**
     * @param int $annee_fiscale
     * @return string
     */
    public static function getPath(
        $annee_fiscale
    ) {
        return sprintf(
            DataParser::getDataPath() . DataParser::dataDirName . '_%s/',
            $annee_fiscale
        );
    }

    /**
     * @param $file
     * @param $dir
     * @param $head
     * @return array
     */
    public static function getDataCSV(/*string*/
        $file,
        /*string*/
        $dir,
        /*string*/
        $head
    ) {
        $array = [];
        if (is_file($dir . $file)) {
            $csv_data = file_get_contents($dir . $file);
            $lines = explode(
                "\n", /*iconv ( "ISO-8859-1", "UTF-8", */
                $csv_data /*)*/
            );
            // $head = str_getcsv(array_shift($lines), DataParser::separateur, DataParser::delimiteur);
            array_shift($lines);

            foreach ($lines as $line) {
                // $body = str_getcsv($line, DataParser::separateur, DataParser::delimiteur);
                // $body = array_filter(array_map('trim', explode(DataParser::separateur,$line)));
                $body = explode(DataParser::separateur, $line);

                if (count($body) == count(explode(DataParser::separateur, $head))
                    && !is_null($body [0])
                ) {
                    $array [] = array_combine(explode(DataParser::separateur, $head), $body);
                }
            }
        }
        return $array;
    }

    /**
     * @return multitype:string
     */
    public static function get_periodes()
    {
        $tabPeriodeCalc = [];
        $dirs = scandir(DataParser::getDataPath());
        for ($i = count($dirs) - 1; $i >= 0; $i--) {
            if (is_dir(DataParser::getDataPath() . $dirs [$i])) {
                $trimmed = trim(str_ireplace(DataParser::dataDirName . '_', '', $dirs [$i], $n_inst));
                if ($n_inst > 0) {
                    $tabPeriodeCalc [] = $trimmed;
                }
            }
        }
        return $tabPeriodeCalc;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_bar_fort($annee_fiscale)
    {
        if (!isset(self::$bar_fort['annee_fiscale']) || self::$bar_fort['annee_fiscale'] != $annee_fiscale) {
            self::$bar_fort = self::getDataCSV(
                DataParser::bar_fort,
                self::getPath($annee_fiscale),
                DataParser::head_bar_fort
            );
            for ($i = 0; $i < count(self::$bar_fort); $i++) {
                self::$bar_fort[$i]['fortune_imposable'] = str_replace(
                    "'",
                    '',
                    self::$bar_fort[$i]['fortune_imposable']
                );
                self::$bar_fort[$i]['impot_annuel'] = str_replace("'", '', self::$bar_fort[$i]['impot_annuel']);
            }
        }
        return self::$bar_fort;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_bar_ICC($annee_fiscale)
    {
        if (!isset(self::$bar_ICC['annee_fiscale']) || self::$bar_ICC['annee_fiscale'] != $annee_fiscale) {
            self::$bar_ICC = self::getDataCSV(
                DataParser::bar_ICC,
                self::getPath($annee_fiscale),
                DataParser::head_bar_ICC
            );
            for ($i = 0; $i < count(self::$bar_ICC); $i++) {
                self::$bar_ICC [$i]['bar_canton_revenu_imposable'] = str_replace(
                    "'",
                    '',
                    self::$bar_ICC[$i]['bar_canton_revenu_imposable']
                );
                self::$bar_ICC[$i]['bar_canton_impot'] = str_replace("'", '', self::$bar_ICC[$i]['bar_canton_impot']);
            }
        }
        return self::$bar_ICC;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_bar_IFD_C($annee_fiscale)
    {
        if (!isset(self::$bar_IFD_C['annee_fiscale']) || self::$bar_IFD_C['annee_fiscale'] != $annee_fiscale) {
            self::$bar_IFD_C = self::getDataCSV(
                DataParser::bar_IFD_C,
                self::getPath($annee_fiscale),
                DataParser::head_bar_IFD_C
            );
            for ($i = 0; $i < count(self::$bar_IFD_C); $i++) {
                self::$bar_IFD_C[$i]['bar_IFD_celibataire_revenu_imposable'] = str_replace(
                    "'",
                    '',
                    self::$bar_IFD_C[$i]['bar_IFD_celibataire_revenu_imposable']
                );
                self::$bar_IFD_C[$i]['impot_annuel'] = str_replace("'", '', self::$bar_IFD_C[$i]['impot_annuel']);
            }
        }
        return self::$bar_IFD_C;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_bar_IFD_M($annee_fiscale)
    {
        if (!isset(self::$bar_IFD_M['annee_fiscale']) || self::$bar_IFD_M['annee_fiscale'] != $annee_fiscale) {
            self::$bar_IFD_M = self::getDataCSV(
                DataParser::bar_IFD_M,
                self::getPath($annee_fiscale),
                DataParser::head_bar_IFD_M
            );
            for ($i = 0; $i < count(self::$bar_IFD_M); $i++) {
                self::$bar_IFD_M[$i]['bar_IFD_marie_revenu_imposable'] = str_replace(
                    "'",
                    '',
                    self::$bar_IFD_M[$i]['bar_IFD_marie_revenu_imposable']
                );
                self::$bar_IFD_M[$i]['impot_annuel'] = str_replace("'", '', self::$bar_IFD_M[$i]['impot_annuel']);
            }
        }
        return self::$bar_IFD_M;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_plaf_quotient($annee_fiscale)
    {
        if (!isset(self::$tab_parametres['annee_fiscale']) || self::$tab_parametres['annee_fiscale'] != $annee_fiscale) {
            self::$tab_parametres = self::getDataCSV(
                DataParser::tab_parametres,
                self::getPath($annee_fiscale),
                DataParser::head_tab_parametres
            );
        }

        return self::$tab_parametres;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_tab_enfant($annee_fiscale)
    {
        if (!isset(self::$tab_enfant['annee_fiscale']) || self::$tab_enfant['annee_fiscale'] != $annee_fiscale) {
            self::$tab_enfant = self::getDataCSV(
                DataParser::tab_enfant,
                self::getPath($annee_fiscale),
                DataParser::head_tab_enfant
            );
        }
        return self::$tab_enfant;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_tab_enfant_demi_qotient($annee_fiscale)
    {
        if (!isset(self::$tab_enfant['annee_fiscale']) || self::$tab_enfant['annee_fiscale'] != $annee_fiscale) {
            self::$tab_enfant = self::getDataCSV(
                DataParser::tab_enfant,
                self::getPath($annee_fiscale),
                DataParser::head_tab_enfant
            );
        }
        return self::$tab_enfant;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_tab_etat_civil($annee_fiscale)
    {
        if (!isset(self::$tab_etat_civil['annee_fiscale']) || self::$tab_etat_civil['annee_fiscale'] != $annee_fiscale) {
            self::$tab_etat_civil = self::getDataCSV(
                DataParser::tab_etat_civil,
                self::getPath($annee_fiscale),
                DataParser::head_tab_etat_civil
            );
        }
        return self::$tab_etat_civil;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_tab_parametres($annee_fiscale)
    {
        if (!isset(self::$tab_parametres['annee_fiscale']) || self::$tab_parametres['annee_fiscale'] != $annee_fiscale) {
            self::$tab_parametres = self::getDataCSV(
                DataParser::tab_parametres,
                self::getPath($annee_fiscale),
                DataParser::head_tab_parametres
            );
            for ($i = 0; $i < count(self::$tab_parametres); $i++) {
                self::$tab_parametres[$i]['fortune_min_imposable_marie'] = str_replace(
                    "'",
                    '',
                    self::$tab_parametres[$i]['fortune_min_imposable_marie']
                );
                self::$tab_parametres[$i]['fortune_min_imposable_seule'] = str_replace(
                    "'",
                    '',
                    self::$tab_parametres[$i]['fortune_min_imposable_seule']
                );
            }
        }

        return self::$tab_parametres;
    }

    /**
     * @param int $annee_fiscale
     * @return multitype
     */
    public static function get_tab_taux_commune($annee_fiscale)
    {
        if (!isset(self::$tab_taux_commune['annee_fiscale']) || self::$tab_taux_commune['annee_fiscale'] != $annee_fiscale) {
            self::$tab_taux_commune = self::getDataCSV(
                DataParser::tab_taux_commune,
                self::getPath($annee_fiscale),
                DataParser::head_tab_taux_commune
            );
            for ($i = 0; $i < count(self::$tab_taux_commune); $i++) {
                self::$tab_taux_commune[$i][self::_COMMUNE_NORM] = self::encodeCommuneName(
                    self::$tab_taux_commune[$i]['commune']
                );
            }
        }
        return self::$tab_taux_commune;
    }

    /**
     * @param $annee_fiscale
     * @param $param
     * @return mixed
     */
    public static function getParam($annee_fiscale, $param)
    {
        $result = self::get_tab_parametres($annee_fiscale);
        $pos = self::searcharray(
            $annee_fiscale,
            'annee_fiscale',
            $result
        );

        return $result [$pos] [$param];
    }

    /**
     * Recherche dans la table des quotient familiaux de la colonne $data pour une periode,
     * un état civil et le nombre d'enfants indiqués
     *
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $no_enfants
     * @param $no_enfants_demi_quotient
     * @param $plafond_quotient
     * @param $data
     * @return int
     */
    private static function getQuotientFamilialeData(/*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $no_enfants,
        /*number*/
        $no_enfants_demi_quotient,
        /*number*/
        $plafond_quotient,
        /*mixed*/
        $data
    ): int {
        if (!empty($annee_fiscale)
            && is_numeric($etat_civil)
            && is_numeric($no_enfants)
            && is_numeric($no_enfants_demi_quotient)
            && !empty($plafond_quotient)
        ) {
            $result = self::get_plaf_quotient($annee_fiscale);
            $code = $etat_civil * 100 + $no_enfants + $no_enfants_demi_quotient;
            $pos = self::searcharray($code, 'code', $result);

            return $result [$pos] [$data];
        }
        return 0;
    }

    /**
     * @param int $annee_fiscale
     * @param string $commune_norm
     * @return le|null nom exacte d'une commune à partir du nom normalisé au sens encodeCommuneName pour l'affichage
     */
    public static function getNomCommuneExacte($annee_fiscale, $commune_norm)
    {
        if (!empty($annee_fiscale) && isset($commune_norm)) {
            $result = self::get_tab_taux_commune($annee_fiscale);
            $pos = self::searcharray($commune_norm, self::_COMMUNE_NORM, $result);
            return $result[$pos]['commune'];
        }
        return null;
    }

    /**
     * si la condition $condition est vraie
     * on effectue une recherche dans la table $table
     * de la ligne pour laquelle la valeur $searchVal se trouve dans la colonne $searchCol
     * (où l'égalité est evalué comme la valeur de la table <= de la valeur cherchée)
     * pour cette ligne on retourne la valeur quie se trouve à la colonne $returnCol
     * si la recherche echoue ou la condition n'est pas remplie retourne $default
     *
     * @param $condition
     * @param array $table
     * @param $searchCol
     * @param $searchVal
     * @param $returnCol
     * @param $default
     * @return mixed
     */
    private static function rechercheValeur($condition, array $table, $searchCol, $searchVal, $returnCol, $default)
    {
        if ($condition) {
            $pos = self::searchArrayNum($searchVal, $searchCol, $table);

            if (!empty($pos)) {
                return $table[$pos][$returnCol];
            }
        }
        return $default;
    }

    /**
     * @param int $annee_fiscale
     * @return number
     */
    public static function getRabaisImpotIFD_param($annee_fiscale)
    {
        return (float)self::getParam($annee_fiscale, 'rabais_impot_IFD');
    }

    /* Taux */

    /**
     * code du champ ID54
     *
     * @param unknown $annee_fiscale
     * @return taux d'imposition pour le canton de Vaud
     */
    public static function getTauxCantonal($annee_fiscale)
    {
        return self::getParam($annee_fiscale, 'coefficient_canton');
    }

    /**
     * code du champ ID55
     *
     * @param $annee_fiscale
     * @param string $search nom de la commune
     * @return number taux d'imposition pour la commune
     */
    public static function getTauxCommunal($annee_fiscale, $search)
    {
        if (!empty($search) && !empty($annee_fiscale)) {
            $result = self::get_tab_taux_commune($annee_fiscale);
            //            $pos = self::searcharray ( $search, 'commune', $result );
            $pos = self::searcharray($search, self::_COMMUNE_NORM, $result);
            return (float)$result [$pos] ['taux'];
        }
        return 0;
    }

    /**
     * code du champ ID56
     *
     * @param annee_fiscale
     * @param $search
     * @return number
     */
    public static function getQuotientEtatCivil($annee_fiscale, $search)
    {
        if (!empty($search) && !empty($annee_fiscale)) {
            $result = self::get_tab_etat_civil($annee_fiscale);
            $pos = self::searcharray($search, 'code', $result);
            return (float)$result[$pos]['quotient'];
        }
        return 0;
    }

    /**
     * code du champ ID60
     *
     * @param $annee_fiscale
     * @param $noEnfantMenage
     * @return number|null
     */
    public static function getRabaisEnfant($annee_fiscale, $noEnfantMenage)
    {
        if (!empty($noEnfantMenage) && $noEnfantMenage > 0 && !empty($annee_fiscale)) {
            return self::getRabaisImpotIFD_param($annee_fiscale);
        }
        return null;
    }

    /**
     * code du champ ID61
     * rabais ifd par enfant selon
     *
     * @param $isCalculIFD
     * @param $isImpotDistinct
     * @param $noEnfantMenage
     * @param $rabaisEnfant
     * @return float|int
     */
    public static function getRabaisIFD($isCalculIFD, $isImpotDistinct, $noEnfantMenage, $rabaisEnfant)
    {
        if ($isCalculIFD && (!$isImpotDistinct)) {
            if (is_numeric($noEnfantMenage)
                && is_numeric($rabaisEnfant)
            ) {
                return $noEnfantMenage * $rabaisEnfant;
            }
        }
        return 0;
    }

    /**
     * code du champ ID123
     *
     * @param $tx_revenu_IFD
     * @param $revenu_imposable_IFD
     * @param $rabais_ifd
     * @return float|int
     */
    public static function getRabaisIFDBareme($tx_revenu_IFD, $revenu_imposable_IFD, $rabais_ifd)
    {
        if ($tx_revenu_IFD > 0 && $tx_revenu_IFD > $revenu_imposable_IFD) {
            return floor((($rabais_ifd / $tx_revenu_IFD * $revenu_imposable_IFD) * 20) + 0.5) / 20;
        }
        return $rabais_ifd;
    }

    /**
     * code champ ID62
     *
     * @param $annee_fiscale
     * @param $revenuImposable
     * @return mixed|null
     */
    public static function getBaremeRevenuICC(/*number*/
        $annee_fiscale, /*number*/
        $revenuImposable
    ) {
        if (!empty($revenuImposable) && $revenuImposable > 0 && !empty($annee_fiscale)) {
            return self::getParam($annee_fiscale, 'bareme_ICC_Revenu');
        }
        return null;
    }

    /**
     * code champ ID63
     *
     * @param $annee_fiscale
     * @param $fortuneImposable
     * @return mixed|null
     */
    public static function getBaremeFortuneICC(/*number*/
        $annee_fiscale, /*number*/
        $fortuneImposable
    ) {
        if (!empty($fortuneImposable) && $fortuneImposable > 0 && !empty($annee_fiscale)) {
            return self::getParam($annee_fiscale, 'bareme_ICC_fortune');
        }
        return null;
    }

    /**
     * code champ ID64
     *
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $revenu_imposable
     * @param $enfants_menage
     * @param $isDistinct
     * @return mixed|null
     */
    public static function getBaremeRevenuIFD(/*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $revenu_imposable,
        /*number*/
        $enfants_menage,
        /*boolean*/
        $isDistinct
    ) {
        if (!empty($annee_fiscale)
            && !empty($etat_civil)
            && !empty($revenu_imposable)
        ) {
            if ($etat_civil == DataParser::code_celibataire
            ) {
                if ($isDistinct || $enfants_menage == 0) {
                    return self::getParam($annee_fiscale, 'bareme_IFD_celibataire');
                }
            }
            return self::getParam($annee_fiscale, 'bareme_IFD_marie');
        }
        return null;
    }

    /**
     * code champ ID65
     *
     * @param $isDistinct
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $no_enfants
     * @param $no_enfants_demi_quotient
     * @return mixed|null
     */
    public static function getPlafondQuotientICC(/*boolean*/
        $isDistinct,
        /*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $no_enfants,
        /*number*/
        $no_enfants_demi_quotient
    ) {
        $no_enfants .= $no_enfants_demi_quotient;
        if ((!$isDistinct)
            && !empty($annee_fiscale)
            && !empty($etat_civil)
            && (int)$no_enfants > 0
        ) {
            return self::getParam($annee_fiscale, 'plafond_quotient_plafond');
        }
        return null;
    }

    /**
     * code champ ID67
     *
     * @param $isDistinct
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $no_enfants
     * @param $no_enfants_demi_quotient
     * @param $taux_plafond_icc
     * @return float|int|mixed|null
     */
    public static function getPlafondQuotientFamiliale(/*boolean*/
        $isDistinct,
        /*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $no_enfants,
        /*number*/
        $no_enfants_demi_quotient,
        /*number*/
        $taux_plafond_icc
    ) {
        $plafond_quotient = self::getPlafondQuotientICC(
            $isDistinct,
            $annee_fiscale,
            $etat_civil,
            $no_enfants,
            $no_enfants_demi_quotient
        );

        $plafond_deduction = Utilities::getPlafondDeductionICC($taux_plafond_icc, $annee_fiscale);
        if ($plafond_quotient > 0) {
            if ($no_enfants + $no_enfants_demi_quotient == 1) {
                return $plafond_quotient;
            }
            return $plafond_quotient + ($plafond_deduction * ($no_enfants + ($no_enfants_demi_quotient / 2) - 1));
        }
        return 0;
    }

    /**
     * code champ ID69
     *
     * @param $quotien_etat_civil
     * @param $quotient_familiale
     * @param $plafond_quotient_icc
     * @param $plafond_quotient
     * @return float|int
     */
    public static function getDeduction(/*number*/
        $quotien_etat_civil,
        /*number*/
        $quotient_familiale,
        /*number*/
        $plafond_quotient_icc,
        /* number */
        $plafond_quotient
    ) {
        if ($quotien_etat_civil > 0 && $quotient_familiale > 0 && $plafond_quotient_icc > 0) {
            return round(($plafond_quotient / $quotien_etat_civil) - ($plafond_quotient / $quotient_familiale));
        }
        return 0;
    }

    /**
     * code champ ID72
     *
     * @param $annee_fiscale
     * @param $bareme_revenu_ICC
     * @param $ratio_revenu_plafond_q
     * @return mixed
     */
    public static function getRevenuImposableBareme(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu_ICC,
        /*number*/
        $ratio_revenu_plafond_q
    ) {
        return self::rechercheValeur(
            !empty($bareme_revenu_ICC),
            self::get_bar_ICC($annee_fiscale),
            'bar_canton_revenu_imposable',
            $ratio_revenu_plafond_q,
            'bar_canton_revenu_imposable',
            0
        );
    }

    /**
     * code champ ID74
     *
     * @param $annee_fiscale
     * @param $bareme_revenu_ICC
     * @param $ratio_revenu_plafond_q
     * @return mixed
     */
    public static function getSupplementPar100(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu_ICC,
        /*number*/
        $ratio_revenu_plafond_q
    ) {
        return self::rechercheValeur(
            !empty($bareme_revenu_ICC),
            self::get_bar_ICC($annee_fiscale),
            'bar_canton_revenu_imposable',
            $ratio_revenu_plafond_q,
            'supplement_par_100',
            0
        );
    }

    /**
     * code champ ID75
     *
     * @param $annee_fiscale
     * @param $bareme_revenu_ICC
     * @param $ratio_revenu_plafond_q
     * @return mixed
     */
    public static function getImpotAnnuelBareme(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu_ICC,
        /*number*/
        $ratio_revenu_plafond_q
    ) {
        return self::rechercheValeur(
            !empty($bareme_revenu_ICC),
            self::get_bar_ICC($annee_fiscale),
            'bar_canton_revenu_imposable',
            $ratio_revenu_plafond_q,
            'bar_canton_impot',
            0
        );
    }

    /**
     * code champ id 92
     *
     * @param $annee_fiscale
     * @param $bareme_fortune
     * @param $fortune_taux
     * @return mixed
     */
    public static function getFortuneImposableBareme(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_fortune,
        /*number*/
        $fortune_taux
    ) {
        return self::rechercheValeur(
            !empty($bareme_fortune),
            self::get_bar_fort($annee_fiscale),
            'fortune_imposable',
            $fortune_taux,
            'fortune_imposable',
            0
        );
    }

    /**
     * code champ id 94
     *
     * @param $annee_fiscale
     * @param $bareme_fortune
     * @param $fortune_taux
     * @return mixed
     */
    public static function getSupplementPar1000(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_fortune,
        /*number*/
        $fortune_taux
    ) {
        return self::rechercheValeur(
            !empty($bareme_fortune),
            self::get_bar_fort($annee_fiscale),
            'fortune_imposable',
            $fortune_taux,
            'supplement_par_1000',
            0
        );
    }

    /**
     * code champ id 95
     *
     * @param $annee_fiscale
     * @param $bareme_fortune
     * @param $fortune_taux
     * @return mixed
     */
    public static function getImpotAnnuelFortune(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_fortune,
        /*number*/
        $fortune_taux
    ) {
        return self::rechercheValeur(
            !empty($bareme_fortune),
            self::get_bar_fort($annee_fiscale),
            'fortune_imposable',
            $fortune_taux,
            'impot_annuel',
            0
        );
    }

    /**
     * code champ id 101
     *
     * @param $annee_fiscale
     * @param $etat_civil
     * @return int|mixed
     */
    public static function getMinImposableFortune(/*number*/
        $annee_fiscale,/*number*/
        $etat_civil
    ) {
        if (!empty($annee_fiscale)) {
            if ($etat_civil == DataParser::code_marie) {
                return self::getParam($annee_fiscale, 'fortune_min_imposable_marie');
            }
            return self::getParam($annee_fiscale, 'fortune_min_imposable_seule');
        }
        return 0;
    }

    /**
     * code champ id 114
     *
     * @param $annee_fiscale
     * @param $bareme_revenu
     * @param $revenu_taux
     * @return mixed
     */
    public static function getRevenuImposableBaremeIFD(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu,
        /*number*/
        $revenu_taux
    ) {
        if ($bareme_revenu == DataParser::bareme_revenu_marie) {
            return self::rechercheValeur(
                !empty($bareme_revenu),
                self::get_bar_IFD_M($annee_fiscale),
                'bar_IFD_marie_revenu_imposable',
                $revenu_taux,
                'bar_IFD_marie_revenu_imposable',
                1
            );
        }
        return self::rechercheValeur(
            !empty($bareme_revenu),
            self::get_bar_IFD_C($annee_fiscale),
            'bar_IFD_celibataire_revenu_imposable',
            $revenu_taux,
            'bar_IFD_celibataire_revenu_imposable',
            1
        );
    }

    /**
     * code champ id 116
     *
     * @param $annee_fiscale
     * @param $bareme_revenu
     * @param $revenu_taux
     * @return mixed
     */
    public static function getSupplementIFDPar100(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu,
        /*number*/
        $revenu_taux
    ) {
        if ($bareme_revenu == DataParser::bareme_revenu_marie) {
            return self::rechercheValeur(
                $bareme_revenu,
                self::get_bar_IFD_M($annee_fiscale),
                'bar_IFD_marie_revenu_imposable',
                $revenu_taux,
                'supplement_par_100',
                0
            );
        }
        return self::rechercheValeur(
            !empty($bareme_revenu),
            self::get_bar_IFD_C($annee_fiscale),
            'bar_IFD_celibataire_revenu_imposable',
            $revenu_taux,
            'supplement_par_100',
            0
        );
    }

    /**
     * code champ id 117
     *
     * @param $annee_fiscale
     * @param $bareme_revenu
     * @param $revenu_taux
     * @return mixed
     */
    public static function getImpotAnnuelIFD(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu,
        /*number*/
        $revenu_taux
    ) {
        if ($bareme_revenu == DataParser::bareme_revenu_marie) {
            return self::rechercheValeur(
                !empty($bareme_revenu),
                self::get_bar_IFD_M($annee_fiscale),
                'bar_IFD_marie_revenu_imposable',
                $revenu_taux,
                'impot_annuel',
                0
            );
        }
        return self::rechercheValeur(
            !empty($bareme_revenu),
            self::get_bar_IFD_C($annee_fiscale),
            'bar_IFD_celibataire_revenu_imposable',
            $revenu_taux,
            'impot_annuel',
            0
        );
    }

    /**
     * code champ id 118
     *
     * @param $annee_fiscale
     * @param $bareme_revenu
     * @param $revenu_taux
     * @return mixed
     */
    public static function getSupplementIFD(/*number*/
        $annee_fiscale,
        /*number*/
        $bareme_revenu,
        /*number*/
        $revenu_taux
    ) {
        if ($bareme_revenu == DataParser::bareme_revenu_marie) {
            return self::rechercheValeur(
                !empty($bareme_revenu),
                self::get_bar_IFD_M($annee_fiscale),
                'bar_IFD_marie_revenu_imposable',
                $revenu_taux,
                'supplement_par_100',
                0
            );
        }
        return self::rechercheValeur(
            !empty($bareme_revenu),
            self::get_bar_IFD_C($annee_fiscale),
            'bar_IFD_celibataire_revenu_imposable',
            $revenu_taux,
            'supplement_par_100',
            0
        );
    }

    /**
     * code champ id 119
     *
     * @param $revenu_imposable_bareme_IFD
     * @param $supplement_IFD
     * @return float|int
     */
    public static function getImpotBase(/*number*/
        $revenu_imposable_bareme_IFD,/*number*/
        $supplement_IFD
    ) {
        return floor((($revenu_imposable_bareme_IFD + $supplement_IFD) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 155
     *
     * @param $annee_fiscale
     * @param $impot_base
     * @return int|mixed
     */
    public static function getFacteurImpotIFD(/*number*/
        $annee_fiscale,/*number*/
        $impot_base
    ) {
        if ($impot_base > 0) {
            return self::getParam($annee_fiscale, 'facteur_fédéral');
        }
        return 0;
    }

    /**
     * code champ id 149
     *
     * @param $annee_fiscale
     * @param $impot_cantonal_base
     * @return int|mixed
     */
    public static function getFacteurImpotBase(/*number*/
        $annee_fiscale,/*number*/
        $impot_cantonal_base
    ) {
        if ($impot_cantonal_base) {
            return self::getParam($annee_fiscale, 'facteur_canton');
        }
        return 0;
    }
}
