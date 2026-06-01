<?php

namespace Vd\VdSqliCalculetteAci\Utility;

class Utilities
{
    public const MINIMUM_IFD = 25;

    /**
     * code champ ID57 : texte correspondant à l'état civil
     *
     * @param $annee_fiscale
     * @param $etat_civil
     * @return |null
     */
    public static function getTextEtatCivil($annee_fiscale, $etat_civil)
    {
        if (!empty($etat_civil)) {
            $etatsCivil = DataParser::get_tab_etat_civil($annee_fiscale);
            $pos = DataParser::searcharray($etat_civil, 'code', $etatsCivil);

            return $etatsCivil[$pos]['etat_civil'];
        }
        return null;
    }

    /**
     * id 48 : Calcul du revenu imposable ICC
     *
     * @param int $rev_ICC
     * @param bool $is_repartition
     * @param int $tx_revenu_icc
     * @return int
     */
    public static function getRevenuImposableICC($rev_ICC, $is_repartition, $tx_revenu_icc)
    {
        if (!$is_repartition || ($is_repartition && $tx_revenu_icc > 0)) {
            return self::arrondiGen($rev_ICC, 2);
        }

        return 0;
    }

    /**
     * id 50 : Calcul de la fortune imposable ICC
     *
     * @param int $fort_ICC
     * @param bool $is_repartition
     * @param int $tx_fortune_icc
     * @return int
     */
    public static function getFortuneImposableICC($fort_ICC, $is_repartition, $tx_fortune_icc)
    {
        if (!$is_repartition || ($is_repartition && $tx_fortune_icc > 0)) {
            return self::arrondiGen($fort_ICC, 3);
        }

        return 0;
    }

    /**
     * id 52 : Calcul de revenu imposable IFD
     *
     * @param int $rev_IFD
     * @param bool $is_repartition
     * @param int $tx_revenu_ifd
     * @return int
     */
    public static function getRevenuImposableIFD($rev_IFD, $is_repartition, $tx_revenu_ifd)
    {
        if (!$is_repartition || ($is_repartition && $tx_revenu_ifd > 0)) {
            return self::arrondiGen($rev_IFD, 2);
        }

        return 0;
    }

    /****************************** IMPOT REVENU ********************************************************/
    /**
     * code champ ID66 : revenu pour taux
     *
     * @param int $revenu_imposable_ICC
     * @param bool $taux_revenu_ICC
     * @param int $is_repartition
     * @return int
     */
    public static function getRevenuPourTaux($revenu_imposable_ICC, $taux_revenu_ICC, $is_repartition)
    {
        if (!$is_repartition) {
            return $revenu_imposable_ICC;
        }

        if (!empty($taux_revenu_ICC) && is_numeric($taux_revenu_ICC) && $taux_revenu_ICC > 0) {
            return $taux_revenu_ICC;
        }

        return 0;
    }

    /**
     *  code champ ID68
     *
     * @param int $revenu_taux
     * @param int $quotient
     * @return float|int
     */
    public static function getRatioRevenuQuotient($revenu_taux, $quotient)
    {
        if (is_numeric($revenu_taux)
            && !empty($revenu_taux)
            && $revenu_taux > 0
            && is_numeric($quotient)
            && !empty($quotient)
            && $quotient > 0
        ) {
            return floor(($revenu_taux / $quotient) / 100) * 100;
        }

        return 0;
    }

    /**
     * @param int $value
     * @param int $facteur
     * @return int
     */
    public static function arrondiGen($value, $facteur)
    {
        if ($value < (10 ** $facteur)) {
            return 0;
        }

        return $value - $value % (pow(10, $facteur));
    }

    /**
     * code champ 70
     *
     * @param $revenu_taux
     * @param $quotient_etat_civil
     * @param $deduction
     * @return int
     */
    public static function getRevenuPlafondTaux($revenu_taux, $quotient_etat_civil, $deduction)
    {
        if (!empty($revenu_taux)
            && is_numeric($revenu_taux)
            && $revenu_taux > 0
            && !empty($quotient_etat_civil)
            && is_numeric($quotient_etat_civil)
            && $quotient_etat_civil > 0
        ) {
            return self::arrondiGen(
                (round($revenu_taux / $quotient_etat_civil - $deduction) - (round(
                    $revenu_taux / $quotient_etat_civil - $deduction,
                    2
                ) % 100)),
                2
            );
        }

        return 0;
    }

    /**
     * code champ 71
     *
     * @param $revenu_taux
     * @param $plafond_quotient
     * @param $ratio_revenu_quotient
     * @param $revenu_plafond_taux
     * @return mixed
     */
    public static function getRatioRevenuSurPlafondQuotient(
        $revenu_taux,
        $plafond_quotient,
        $ratio_revenu_quotient,
        $revenu_plafond_taux
    ) {
        if ($revenu_taux < $plafond_quotient) {
            return $ratio_revenu_quotient;
        }

        return $revenu_plafond_taux;
    }

    /**
     * code champ 73
     *
     * @param $ratio_revenu_plafond_q
     * @param $revenu_imposable_bareme
     * @return mixed
     */
    public static function getDiffTauxImposable($ratio_revenu_plafond_q, $revenu_imposable_bareme)
    {
        return $ratio_revenu_plafond_q - $revenu_imposable_bareme;
    }

    /**
     * code champ 76
     *
     * @param $diff_taux_imposable
     * @param $supplement_par_100
     * @return float
     */
    public static function getSupplementImpot($diff_taux_imposable, $supplement_par_100)
    {
        return round($diff_taux_imposable * $supplement_par_100 / 100, 2);
    }

    /**
     * code champ 77
     *
     * @param $impot_annuel_bareme
     * @param $supplement_impot
     * @return mixed
     */
    public static function getImpotBase($impot_annuel_bareme, $supplement_impot)
    {
        return $impot_annuel_bareme + $supplement_impot;
    }

    /**
     * code champ 78
     *
     * @param $ratio_revenu_plafond_q
     * @param $impot_base
     * @return float|int
     */
    public static function getPourcentPourTaux($ratio_revenu_plafond_q, $impot_base)
    {
        if ($ratio_revenu_plafond_q != 0) {
            return 100 * $impot_base / $ratio_revenu_plafond_q;
        }

        return 0;
    }

    /**
     * code champ 80
     *
     * @param $revenu_imposable_calc
     * @param $pourcent_taux
     * @return float|int
     */
    public static function getImpotCantonalBase($revenu_imposable_calc, $pourcent_taux)
    {
        // id 80 getImpotCantonalBase(167600, 7.8752351633199)
        return floor((($revenu_imposable_calc * $pourcent_taux / 100) * 20) + 0.5) / 20;
    }

    /**
     * code champ 82
     *
     * @param $taux_cantonal
     * @param $impot_cantonal_base
     * @return float|int
     */
    public static function getImpotCantonalRevenu($taux_cantonal, $impot_cantonal_base)
    {
        return floor((($taux_cantonal * $impot_cantonal_base / 100) * 20) + 0.5) / 20;
    }

    /**
     * code champ 84
     *
     * @param $impot_cantonal_base
     * @param $taux_commune
     * @return float|int
     */
    public static function getImpotCommunalRevenu($impot_cantonal_base, $taux_commune)
    {
        return floor((($impot_cantonal_base * $taux_commune / 100) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 85
     *
     * @param $impot_cantonal_revenu
     * @param $impot_communal_revenu
     * @return mixed
     */
    public static function getTotalICC($impot_cantonal_revenu, $impot_communal_revenu)
    {
        return $impot_cantonal_revenu + $impot_communal_revenu;
    }

    /**
     * code champ id 86
     *
     * @param $revenu_imposable_ICC
     * @param $taux_revenu_impot_ICC
     * @param $is_repartition
     * @return float|int
     */
    public static function getMax30RevenuImposable($revenu_imposable_ICC)
    {
        return $revenu_imposable_ICC / 100 * 30;
    }

    /**
     * code champ id87
     *
     * @param $total_ICC
     * @param $revenu_imposable_ICC
     * @param $taux_revenu_imp_ICC
     * @param $is_repartition
     * @return float|int
     */
    public static function getCoeffImpotCantonalBase(
        $total_ICC,
        $revenu_imposable_ICC,
        $taux_revenu_imp_ICC,
        $is_repartition
    ) {
        $max30 = self::getMax30RevenuImposable($revenu_imposable_ICC);

        if ($total_ICC >= $max30 && $total_ICC > 0) {
            return $max30 / $total_ICC;
        }

        return 1;
    }

    /**
     * code champ id 88
     *
     * @param $impot_cantonal_base
     * @param $total_ICC
     * @param $revenu_imposable_ICC
     * @param $taux_revenu_imp_ICC
     * @param $is_repartition
     * @return float|int
     */
    public static function getImpotCantonalBasePourCoeff(
        $impot_cantonal_base,
        $total_ICC,
        $revenu_imposable_ICC,
        $taux_revenu_imp_ICC,
        $is_repartition
    ) {
        $coefImpCantBase = self::getCoeffImpotCantonalBase(
            $total_ICC,
            $revenu_imposable_ICC,
            $taux_revenu_imp_ICC,
            $is_repartition
        );

        return floor((($impot_cantonal_base * $coefImpCantBase) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 89
     *
     * @param $impot_cantonal_revenu
     * @param $total_ICC
     * @param $revenu_imposable_ICC
     * @param $taux_revenu_imp_ICC
     * @param $is_repartition
     * @return float|int
     */
    public static function getImpotCantSurRevenu(/*number*/
        $impot_cantonal_revenu,
        /*number*/
        $total_ICC,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $taux_revenu_imp_ICC,
        /*boolean*/
        $is_repartition
    ) {
        $coeffImpotCantonalBase = self::getCoeffImpotCantonalBase(
            $total_ICC,
            $revenu_imposable_ICC,
            $taux_revenu_imp_ICC,
            $is_repartition
        );

        return floor((($impot_cantonal_revenu * $coeffImpotCantonalBase) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 90
     *
     * @param $impot_communal_revenu
     * @param $total_ICC
     * @param $revenu_imposable_ICC
     * @param $taux_revenu_imp_ICC
     * @param $is_repartition
     * @return float|int
     */
    public static function getImpotCommunalRevenuCalc(/*number*/
        $impot_communal_revenu,
        /*number*/
        $total_ICC,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $taux_revenu_imp_ICC,
        /*boolean*/
        $is_repartition
    ) {
        $coeffImpotCantonalBase = self::getCoeffImpotCantonalBase(
            $total_ICC,
            $revenu_imposable_ICC,
            $taux_revenu_imp_ICC,
            $is_repartition
        );

        return floor((($impot_communal_revenu * $coeffImpotCantonalBase) * 20) + 0.5) / 20;
    }

    /****************************** FORTUNE *********************************/

    /**
     * ID 91 : Calcul de la fortune pour le taux
     * @param $is_repartition
     * @param $fortune_imposable
     * @param $taux_fortune
     * @return int
     */
    public static function getFortunePourTaux(/*boolean*/
        $is_repartition, /*boolean*/
        $fortune_imposable, /*boolean*/
        $taux_fortune
    ) {
        if (!$is_repartition) {
            return $fortune_imposable;
        }

        if (!empty($taux_fortune) && is_numeric($taux_fortune) && $taux_fortune > 0) {
            return $taux_fortune;
        }

        return 0;
    }

    /**
     * code champ id 93
     *
     * @param $fortune_taux
     * @param $fortune_bareme
     * @return mixed
     */
    public static function getDiffTauxFortune(/*number*/
        $fortune_taux, /*number*/
        $fortune_bareme
    ) {
        return $fortune_taux - $fortune_bareme;
    }

    /**
     * code champ id 96
     *
     * @param $diff_taux_imposable
     * @param $supplement
     * @return float|int
     */
    public static function getSupplementImpotFortune(/*number*/
        $diff_taux_imposable, /*number*/
        $supplement
    ) {
        return $diff_taux_imposable * $supplement / 1000;
    }

    /**
     * code champ id 97
     *
     * @param $impot_annuel_fortune_bareme
     * @param $supplement_fortune
     * @return float|int
     */
    public static function getImpotBaseFortune(/*number*/
        $impot_annuel_fortune_bareme, /*number*/
        $supplement_fortune
    ) {
        return floor((($impot_annuel_fortune_bareme + $supplement_fortune) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 98
     *
     * @param $fortune_taux
     * @param $impot_base_fortune
     * @return float|int
     */
    public static function getPourcentTauxFortune(/*number*/
        $fortune_taux, /*number*/
        $impot_base_fortune
    ) {
        if ($fortune_taux != 0) {
            return round($impot_base_fortune / $fortune_taux * 100, 9);
        }
        return 0;
    }

    /**
     * code champ id 100
     *
     * @param $fortune_imposable_ICC
     * @param $fortune_taux
     * @param $impot_base_fortune
     * @return float|int
     */
    public static function getImpotCantonaleBaseFortune(/*number*/
        $fortune_imposable_ICC,
        /*number*/
        $fortune_taux,
        /*number*/
        $impot_base_fortune
    ) {
        $pourcent = self::getPourcentTauxFortune($fortune_taux, $impot_base_fortune);

        return $fortune_imposable_ICC * $pourcent / 100;
    }

    /**
     * code champ id 102
     *
     * @param $fortune_taux
     * @param $min_imposable
     * @return bool
     */
    public static function isFortuneImposable(/*number*/
        $fortune_taux, /*number*/
        $min_imposable
    ) {
        return $fortune_taux >= $min_imposable;
    }

    /**
     * code champ id 104
     *
     * @param $annee_fiscale
     * @param $is_fortune_imposable
     * @param $impot_cantonal_base_fortune
     * @return float|int
     */
    public static function getImpotCantonaleFortune(/*number*/
        $annee_fiscale,
        /*boolean*/
        $is_fortune_imposable,
        /*number*/
        $impot_cantonal_base_fortune
    ) {
        if ($is_fortune_imposable) {
            $coeffImpotCantonaleFortune = DataParser::getTauxCantonal($annee_fiscale);
            return ((int)floor((($impot_cantonal_base_fortune * $coeffImpotCantonaleFortune / 100) * 20) + 0.5)) / 20;
        }

        return 0;
    }

    /**
     * code champ id 106
     *
     * @param $currPeriodeCalc
     * @param $currCommune
     * @param $is_imposable
     * @param $impot_cantonal_base_fortune
     * @return float|int
     */
    public static function getImpotCommunalFortuneCalc(/*number*/
        $currPeriodeCalc,
        /*string*/
        $currCommune,
        /*boolean*/
        $is_imposable,
        /*number*/
        $impot_cantonal_base_fortune
    ) {
        $coeffFortune = DataParser::getTauxCommunal($currPeriodeCalc, $currCommune);

        if ($is_imposable) {
            return floor((($impot_cantonal_base_fortune * $coeffFortune / 100) * 20) + 0.5) / 20;
        }

        return 0;
    }

    /**
     * code champ id 107
     *
     * @param $impot_cantonal_fortune
     * @param $impot_communal_fortune
     * @return mixed
     */
    public static function getTotalImpotFortune(/*number*/ $impot_cantonal_fortune, /*number*/ $impot_communal_fortune)
    {
        return $impot_cantonal_fortune + $impot_communal_fortune;
    }

    /**
     * code champ id 108
     *
     * @param $fortune_taux
     * @return float|int
     */
    public static function getMax1Fortune(/*number*/ $fortune_taux)
    {
        return floor((($fortune_taux / 1000 * 10) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 109
     *
     * @param $total_impot_fortune
     * @param $seuil_fortune_imposable
     * @return float|int
     */
    public static function getCoeffImpotBaseFortune($total_impot_fortune, $seuil_fortune_imposable)
    {
        if ($total_impot_fortune >= $seuil_fortune_imposable && $total_impot_fortune > 0) {
            return $seuil_fortune_imposable / $total_impot_fortune;
        }

        return 1;
    }

    /**
     * code champ id 110
     *
     * @param $impot_cantonal_fortune
     * @param $coeff_impot_base_fortune
     * @return float|int
     */
    public static function getImpotBaseFortuneCalc(/*number*/
        $impot_cantonal_fortune, /*number*/
        $coeff_impot_base_fortune
    ) {
        return floor((($impot_cantonal_fortune * $coeff_impot_base_fortune) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 111
     *
     * @param $impot_cantonal_fortune
     * @param $coeff_base_fortune
     * @return float|int
     */
    public static function getImpotCantonalFortuneFinal(/*number*/
        $impot_cantonal_fortune, /*number*/
        $coeff_base_fortune
    ) {
        return floor((($impot_cantonal_fortune * $coeff_base_fortune) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 112
     *
     * @param $impot_communal_fortune
     * @param $coeff_impot_base
     * @return float|int
     */
    public static function getImpotCommunalFortuneFinal(/*number*/
        $impot_communal_fortune, /*number*/
        $coeff_impot_base
    ) {
        return floor((($impot_communal_fortune * $coeff_impot_base) * 20) + 0.5) / 20;
    }

    /**
     * id 113 : Calcul revenu pour le Taux IFD
     * @param $is_repartition
     * @param $revenu_IFD
     * @param $revenu_taux_IFD
     * @return mixed
     */
    public static function getRevenuTauxIFD(/*boolean*/
        $is_repartition,
        /*number*/
        $revenu_IFD,
        /*number*/
        $revenu_taux_IFD
    ) {
        if (!$is_repartition) {
            return $revenu_IFD;
        }
        if (!empty($revenu_taux_IFD) && is_numeric($revenu_taux_IFD) && $revenu_taux_IFD > 0) {
            return $revenu_taux_IFD;
        }
        return $revenu_IFD;
    }

    /**
     * code champ id 115
     *
     * @param $revenu_taux_IFD
     * @param $revenu_imposable_bareme_IFD
     * @return mixed
     */
    public static function getDiffTauxIFDBareme(/*number*/
        $revenu_taux_IFD, /*number*/
        $revenu_imposable_bareme_IFD
    ) {
        return $revenu_taux_IFD - $revenu_imposable_bareme_IFD;
    }

    /**
     * code champ id 119
     *
     * @param $revenu_imposable_bareme_IFD
     * @param $supplement
     * @return float|int
     */
    public static function getImpotBaseIFD(/*number*/
        $revenu_imposable_bareme_IFD, /*number*/
        $supplement
    ) {
        return floor((($revenu_imposable_bareme_IFD + $supplement) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 120
     *
     * @param $revenu_taux
     * @param $impot_base
     * @return float|int
     */
    public static function getPourcentTauxIFD(/*number*/
        $revenu_taux, /*number*/
        $impot_base
    ) {
        if ($revenu_taux != 0) {
            return $impot_base / $revenu_taux * 100;
        }
        return 0;
    }

    /**
     * id 122
     * @param $annee_impot
     * @param $revenu_imposable_IFD
     * @param $pourcentage
     * @return float|int
     */
    public static function getImpotIFDBase(/*string*/
        $annee_impot,/*number*/
        $revenu_imposable_IFD, /*number*/
        $revenu_global_IFD, /*number*/
        $pourcentage
    ) {
        return floor((($revenu_imposable_IFD * $pourcentage / 100) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 124
     *
     * @param $impot_rabais_IFD_Bareme
     * @param $impot_IFD_base
     * @return int
     */
    public static function getRabaisIFD(/*number*/
        $impot_rabais_IFD_Bareme, /*number*/
        $impot_IFD_base
    ) {
        if ($impot_rabais_IFD_Bareme > 0) {
            if ($impot_IFD_base < $impot_rabais_IFD_Bareme) {
                return $impot_IFD_base;
            }
            return $impot_rabais_IFD_Bareme;
        }
        return 0;
    }

    /**
     * code champ id 125
     *
     * @param $impot_IFD_base
     * @param $rabais_IFD
     * @return mixed
     */
    public static function getImpotIFDCalc(/*number*/
        $impot_IFD_base, /*number*/
        $rabais_IFD
    ) {
        return $impot_IFD_base - $rabais_IFD;
    }

    /**
     * code champ id 150
     *
     * @param $impot_cantonal_base
     * @param $facteur_impot_base
     * @return float|int
     */
    public static function getImpotBaseCapital(/*number*/
        $impot_cantonal_base, /*number*/
        $facteur_impot_base
    ) {
        if ($impot_cantonal_base > 0 && $facteur_impot_base > 0) {
            return floor((($impot_cantonal_base / $facteur_impot_base) * 20) + 0.5) / 20;
        }

        return 0;
    }

    /**
     * code champ id 152
     *
     * @param $impot_base_capital
     * @param $coeff_impot_cantonal
     * @return float|int
     */
    public static function getCoeffImpotCantonalCapital(/*number*/
        $impot_base_capital, /*number*/
        $coeff_impot_cantonal
    ) {
        return floor((($impot_base_capital * $coeff_impot_cantonal / 100) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 154
     *
     * @param $pourcentPourTaux
     * @param $coeffImpotCommunal
     * @return float|int
     */
    public static function getImpotCommunalCapital(/*number*/
        $pourcentPourTaux, /*number*/
        $coeffImpotCommunal
    ) {
        return floor((($pourcentPourTaux * $coeffImpotCommunal / 100) * 20) + 0.5) / 20;
    }

    /**
     * code champ id 126 :
     * return vrai si le mode de calcul est calcul ICC, que l'annee fiscale, l'état civil ont été définis
     * ainsi qu'au moins une des deux valeurs revenu ICC ou fortune ICC
     * faux autrement
     *
     * @param $option_ICC
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $revenu_imposable_ICC
     * @param $fortune_ICC
     * @return bool
     */
    public static function isCalculICC(/*boolean*/
        $option_ICC,
        /*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $fortune_ICC
    ) {
        return $option_ICC
            && $annee_fiscale > 0
            && $etat_civil > 0
            && ($revenu_imposable_ICC > 0 || $fortune_ICC > 0);
    }

    /**
     * code champ id 127 :
     * return vrai si le mode de calcul est repartition, que l'annee fiscale, l'état civil ont été définis
     * ainsi qu'au moins une des deux valeurs revenu ICC , fortune ICC ou revenu IFD
     * faux autrement
     *
     * @param $is_repartition
     * @param $anne_fiscale
     * @param $etat_civil
     * @param $taux_revenu_ICC
     * @param $taux_fortune_ICC
     * @param $taux_revenu_IFD
     * @return bool
     */
    public static function isRepartition(/*boolean*/
        $is_repartition,
        /*number*/
        $anne_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $taux_revenu_ICC,
        /*number*/
        $taux_fortune_ICC,
        /*number*/
        $taux_revenu_IFD
    ) {
        return $is_repartition
            && $anne_fiscale > 0
            && $etat_civil > 0
            && ($taux_revenu_ICC > 0 || $taux_fortune_ICC > 0 || $taux_revenu_IFD > 0);
    }

    /**
     * code champ id 128 :
     * return vrai si le mode de calcul est calcul de l'IFD, que l'annee fiscale, l'état civil ont été définis
     * ainsi que le revenu IFD
     * faux autrement
     *
     * @param $is_calcul_IFD
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $revenu_impot_IFD
     * @return bool
     */
    public static function isCalculIFD(/*boolean*/
        $is_calcul_IFD, /*number*/
        $annee_fiscale, /*number*/
        $etat_civil,
        /*number*/
        $revenu_impot_IFD
    ) {
        if ($is_calcul_IFD && $annee_fiscale > 0 && $etat_civil > 0 && $revenu_impot_IFD > 0) {
            return true;
        }
        return false;
    }

    /**
     * code champ id 131 :
     * return vrai si le mode de calcul est calcul de capital distinct, que l'annee fiscale, l'état civil ont été définis
     * ainsi qu'au moins une des deux valeurs revenu ICC ou revenu IFD
     * faux autrement
     *
     * @param $isImpotDistinct
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $revenu_impot_ICC
     * @param $revenu_impot_IFD
     * @return bool
     */
    public static function isImpotDistinct(/*boolean*/
        $isImpotDistinct,
        /*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $revenu_impot_ICC,
        /*number*/
        $revenu_impot_IFD
    ) {
        return $isImpotDistinct
            && $annee_fiscale > 0
            && $etat_civil > 0
            && ($revenu_impot_ICC > 0 || $revenu_impot_IFD > 0);
    }

    /**
     * code champ id 132 : return vrai si on est en mode calcul d'IFD ou capital distinct et que le
     * revenu IFD imposable est > 0 faux autrement
     *
     * @param $is_IFD
     * @param $is_distinct
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $revenu_imposable_ICC
     * @param $revenu_imposable_IFD
     */
    public static function afficheRevenuIFD(/*boolean*/
        $is_IFD,
        /*boolean*/
        $is_distinct,
        /*number*/
        $annee_fiscale,
        /*number*/
        $etat_civil,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $revenu_imposable_IFD
    ) {
        if (self::isCalculIFD($is_IFD, $annee_fiscale, $etat_civil, $revenu_imposable_IFD)
            || self::isImpotDistinct(
                $is_distinct,
                $annee_fiscale,
                $etat_civil,
                $revenu_imposable_ICC,
                $revenu_imposable_IFD
            )
            && $revenu_imposable_IFD > 0
        ) {
            return $revenu_imposable_IFD;
        }
        return null;
    }

    /**
     * code champ id 156
     *
     * @param $impot_IFD_Base
     * @param $facteur_impot_IFD
     * @return float|int
     */
    public static function getImpotIFDFinal(/*number*/
        $impot_IFD_Base, /*number*/
        $facteur_impot_IFD
    ) {
        if ($impot_IFD_Base > 0 && $facteur_impot_IFD > 0) {
            return floor((($impot_IFD_Base / $facteur_impot_IFD) * 20) + 0.5) / 20;
        }
        return 0;
    }

    /**
     * code champ id 133
     *
     * @param $is_IFD
     * @param $is_impot_distinct
     * @param $revenu_impot_IFD
     * @param $impotIFDBase
     * @param $impot_IFD
     */
    public static function impotBaseAffichage(/*boolean*/
        $is_IFD,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_impot_IFD,
        /*number*/
        $impotIFDBase,
        /*number*/
        $impot_IFD
    ) {
        if ($is_IFD && $revenu_impot_IFD > 0) {
            return $impotIFDBase;
        }
        if ($is_impot_distinct && $revenu_impot_IFD > 0) {
            return $impot_IFD;
        }
        return null;
    }

    /**
     * code champ id 134
     *
     * @param $is_IFD
     * @param $is_impot_distinct
     * @param $revenu_impot_IFD
     * @param $rabais_IFD
     */
    public static function rabaisIFDAffichage(/*boolean*/
        $is_IFD,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_impot_IFD,
        /*number*/
        $rabais_IFD
    ) {
        if (($is_IFD || $is_impot_distinct) && $revenu_impot_IFD > 0) {
            return $rabais_IFD;
        }
        return null;
    }

    /**
     * code champ id 135
     *
     * @param $is_IFD
     * @param $is_impot_distinct
     * @param $revenu_imposable_IFD
     * @param $impot_IFD_calc
     * @param $impot_IFD
     * @return int|null
     */
    public static function getChargeFiscaleRevenu(/*boolean*/
        $is_IFD,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_imposable_IFD,
        /*number*/
        $impot_IFD_calc,
        /*number*/
        $impot_IFD
    ) {
        if ($is_IFD && $revenu_imposable_IFD > 0) {
            return $impot_IFD_calc >= Utilities::MINIMUM_IFD ? $impot_IFD_calc : 0;
        }
        if ($is_impot_distinct && $revenu_imposable_IFD >= Utilities::MINIMUM_IFD) {
            return $impot_IFD >= Utilities::MINIMUM_IFD ? $impot_IFD : 0;
        }
        return null;
    }

    /**
     * code champ id 136
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $etat_civil
     * @param $revenu_imposable_ICC
     */
    public static function afficheRevenuImposable(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $etat_civil,
        /*number*/
        $revenu_imposable_ICC
    ) {
        if (($is_ICC || $is_impot_distinct)
            && $etat_civil > 0
            && $revenu_imposable_ICC > 0
        ) {
            return $revenu_imposable_ICC;
        }
        return null;
    }

    /**
     * code champ id 137
     *
     * @param $is_ICC
     * @param $fortune_imposable_ICC
     */
    public static function afficheFortuneImposable(/*boolean*/
        $is_ICC, /*number*/
        $fortune_imposable_ICC
    ) {
        if ($is_ICC && $fortune_imposable_ICC > 0) {
            return $fortune_imposable_ICC;
        }
        return null;
    }

    /**
     * code champ id 138
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $revenu_imposable_ICC
     * @param $fortune_imposable
     * @param $taux_cantonal
     * @return string
     */
    public static function afficheCoeffCanton(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $fortune_imposable,
        /*number*/
        $taux_cantonal
    ) {
        if (($is_ICC || $is_impot_distinct) && ($revenu_imposable_ICC > 0 || $fortune_imposable > 0)) {
            return $taux_cantonal;
        }
        return '';
    }

    /**
     * code champ id 139
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $revenu_imposable_ICC
     * @param $fortune_imposable
     * @param $coeff_commune
     * @return string
     */
    public static function afficheCoeffCommune(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $fortune_imposable,
        /*number*/
        $coeff_commune
    ) {
        if (($is_ICC || $is_impot_distinct) ||
            ($revenu_imposable_ICC > 0 || $fortune_imposable > 0) &&
            $coeff_commune > 0
        ) {
            return $coeff_commune;
        }
        return '';
    }

    /**
     * code champ id 140
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $revenu_imposable_ICC
     * @param $impot_cantonal_sur_revenu
     * @param $impot_cantonal_capital
     * @return string
     */
    public static function getChargeFiscaleRevenuCanton(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $impot_cantonal_sur_revenu,
        /*number*/
        $impot_cantonal_capital
    ) {
        if ($is_ICC && $revenu_imposable_ICC > 0) {
            return $impot_cantonal_sur_revenu;
        }
        if ($is_impot_distinct && $revenu_imposable_ICC > 0) {
            return $impot_cantonal_capital;
        }
        return '';
    }

    /**
     * code champ id 141
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $revenu_imposable_ICC
     * @param $taux_commune
     * @param $impot_communal_revenu_calc
     * @param $taux_cantonal
     */
    public static function getChargeFiscaleRevenuCommune(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $revenu_imposable_ICC,
        /*number*/
        $taux_commune,
        /*number*/
        $impot_communal_revenu_calc,
        /*number*/
        $taux_cantonal
    ) {
        if ($is_ICC && $revenu_imposable_ICC > 0 && $taux_commune > 0) {
            return $impot_communal_revenu_calc;
        }
        if ($is_impot_distinct && $revenu_imposable_ICC > 0 && $taux_commune > 0) {
            return $taux_cantonal;
        }
        return null;
    }

    /**
     * code champ id 143
     *
     * @param $is_ICC
     * @param $fortune_imposable
     * @param $impot_cantonal_fortune_calc
     */
    public static function getChargeFiscaleFortuneCanton(/*boolean*/
        $is_ICC,
        /*number*/
        $fortune_imposable,
        /*number*/
        $impot_cantonal_fortune_calc
    ) {
        if ($is_ICC && $fortune_imposable > 0) {
            return $impot_cantonal_fortune_calc;
        }
        return null;
    }

    /**
     * code champ id 144
     *
     * @param $is_ICC
     * @param $fortune_imposable
     * @param $impot_communal_fortune_calc
     */
    public static function getChargeFiscaleFortuneCommune(/*boolean*/
        $is_ICC,
        /*number*/
        $fortune_imposable,
        /*number*/
        $impot_communal_fortune_calc
    ) {
        if ($is_ICC && $fortune_imposable > 0) {
            return $impot_communal_fortune_calc;
        }
        return null;
    }

    /**
     * code champ id 147
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $charge_fiscale_revenu_cant
     * @param $charge_fiscale_revenu_comm
     * @param $charge_fiscale_fortune_cant
     * @param $charge_fiscale_fortune_comm
     */
    public static function afficheTotalFinalICC(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $charge_fiscale_revenu_cant,
        /*number*/
        $charge_fiscale_revenu_comm,
        /*number*/
        $charge_fiscale_fortune_cant,
        /*number*/
        $charge_fiscale_fortune_comm
    ) {
        if ($is_ICC || $is_impot_distinct) {
            return $charge_fiscale_revenu_cant + $charge_fiscale_revenu_comm + $charge_fiscale_fortune_cant + $charge_fiscale_fortune_comm;
        }
        return null;
    }

    /**
     * code champ id 148
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $is_IFD
     * @param $total_final_ICC
     * @param $charge_fiscale_revenu
     * @return float|int
     */
    public static function getTotalICCplusIFD(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*boolean*/
        $is_IFD,
        /*number*/
        $total_final_ICC,
        /*number*/
        $charge_fiscale_revenu
    ) {
        if ($is_ICC || $is_impot_distinct || $is_IFD) {
            return round($total_final_ICC + $charge_fiscale_revenu, 2);
        }
        return 0;
    }

    /**
     * code champ id 158
     *
     * @param $is_ICC
     * @param $is_impot_distinct
     * @param $etat_civil
     * @param $revenu_imposable
     * @param $impot_cantonal_base
     * @param $impot_base_capital
     * @return int|null
     */
    public static function getImpotBaseRevenu(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_impot_distinct,
        /*number*/
        $etat_civil,
        /*number*/
        $revenu_imposable,
        /*number*/
        $impot_cantonal_base,
        /*number*/
        $impot_base_capital
    ) {
        if ($etat_civil > 0 && $revenu_imposable > 0) {
            if ($is_ICC) {
                return $impot_cantonal_base;
            }
            if ($is_impot_distinct) {
                return $impot_base_capital;
            }
            return 0;
        }
        return null;
    }

    /**
     * code champ id 159
     *
     * @param $is_ICC
     * @param $fortune_imposable
     * @param $impot_base_fortune_calc
     */
    public static function afficheImpotBaseFortune(/*boolean*/
        $is_ICC,
        /*number*/
        $fortune_imposable,
        /*number*/
        $impot_base_fortune_calc
    ) {
        if ($is_ICC && $fortune_imposable > 0) {
            return $impot_base_fortune_calc;
        }
        return null;
    }

    /**
     * ID 161
     * @param $taux_plafond_icc
     * @param $annee_fiscale
     * @return int
     */
    public static function getPlafondDeductionICC(/*number*/
        $taux_plafond_icc, /*number*/
        $annee_fiscale
    ) {
        if (!empty($taux_plafond_icc) && is_numeric($taux_plafond_icc) && $taux_plafond_icc > 0) {
            return DataParser::getParam($annee_fiscale, 'plafond_quotient_deduction');
        }
        return 0;
    }

    /**
     * ID 162
     * @param $annee_fiscale
     * @return mixed
     */
    public static function getAbattement($annee_fiscale)
    {
        return DataParser::getParam($annee_fiscale, 'abattement');
    }

    /**
     * code champ id 142
     *
     * @param $is_ICC
     * @param $is_distinct
     * @param $revenu_imposable
     * @param $taux_communal
     * @param $annee_fiscale
     * @param $commune_norm
     * @return le|null
     */
    public static function getNomCommuneRevenu(/*boolean*/
        $is_ICC,
        /*boolean*/
        $is_distinct,
        /*number*/
        $revenu_imposable,
        /*number*/
        $taux_communal,
        /*number*/
        $annee_fiscale,
        /*string*/
        $commune_norm
    ) {
        if (($is_ICC || $is_distinct) && $revenu_imposable > 0 && $taux_communal > 0) {
            return DataParser::getNomCommuneExacte($annee_fiscale, $commune_norm);
        }
        return null;
    }

    /**
     * code champ id 146
     *
     * @param $is_ICC
     * @param $fortune_imposable
     * @param $taux_communal
     * @param $annee_fiscale
     * @param $commune_norm
     * @return le|null
     */
    public static function getNomCommuneFortune(/*boolean*/
        $is_ICC,
        /*number*/
        $fortune_imposable,
        /*number*/
        $taux_communal,
        /*number*/
        $annee_fiscale,
        /*string*/
        $commune_norm
    ) {
        if ($is_ICC && $fortune_imposable > 0 && $taux_communal > 0) {
            return DataParser::getNomCommuneExacte($annee_fiscale, $commune_norm);
        }
        return null;
    }

    /**
     * code champ id 163
     *
     * @param $impot_cantonal_effectif
     * @param $id_abattement
     * @return float|int
     */
    public static function getMontantAbattement(
        $impot_cantonal_effectif,
        $id_abattement
    ) {
        return $impot_cantonal_effectif * ($id_abattement / 100);
    }

    /**
     * code champ id 164
     *
     * @param $impot_cantonal_effectif
     * @param $montant_abattement
     * @return mixed
     */
    public static function getImpotCantonalAbattement(
        $impot_cantonal_effectif,
        $montant_abattement
    ) {
        return $impot_cantonal_effectif - $montant_abattement;
    }
}
