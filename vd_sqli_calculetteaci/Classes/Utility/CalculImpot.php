<?php

namespace Vd\VdSqliCalculetteAci\Utility;

class CalculImpot
{
    /**
     * met en forme des valeurs; les copie dans le tableau, par la suite utilisé pour le calcul des impôts
     *
     * @param number $annee_fiscale :
     * @param number $etat_civil
     * @param number $n_enfants_menage
     * @param number $n_enfants
     * @param number $n_enfants_demi_quotient
     * @param string $commune
     * @param number $rev_ICC
     * @param number $taux_rev_ICC
     * @param number $fort_ICC
     * @param number $taux_fort_ICC
     * @param number $rev_IFD
     * @param number $taux_rev_IFD
     * @param mixed $is_ICC
     * @param mixed $is_IFD
     * @param mixed $is_repartition
     * @param mixed $is_distinct
     * @return multitype:boolean unknown number
     */
    public static function formatEntree(
        /* number */
        $annee_fiscale,
        /* number */
        $etat_civil,
        /* number */
        $n_enfants_menage,
        /* number */
        $n_enfants,
        /* number */
        $n_enfants_demi_quotient,
        /* string */
        $commune,
        /* number */
        $rev_ICC,
        /* number */
        $taux_rev_ICC,
        /* number */
        $fort_ICC,
        /* number */
        $taux_fort_ICC,
        /* number */
        $rev_IFD,
        /* number */
        $taux_rev_IFD,
        /* mixed */
        $is_ICC,
        /* mixed */
        $is_IFD,
        /* mixed */
        $is_repartition,
        /* mixed */
        $is_distinct
    ) {
        $donnees = [];
        $donnees ['periode'] = $annee_fiscale;
        $donnees ['etat_civil'] = $etat_civil;
        $donnees ['n_enfants_menage'] = $n_enfants_menage;
        $donnees ['n_enfants'] = $n_enfants;
        $donnees ['n_enfants_demi_quotient'] = $n_enfants_demi_quotient;
        $donnees ['commune'] = $commune;
        // Gestion des cas en cas de taux de répartition null

        $donnees ['taux_revenu_ICC'] = self::arrondiGen($taux_rev_ICC, 2);
        $donnees ['taux_fortune_ICC'] = self::arrondiGen($taux_fort_ICC, 3);
        $donnees ['taux_revenu_IFD'] = self::arrondiGen($taux_rev_IFD, 2);

        $donnees ['is_calc_ICC'] = !empty($is_ICC);
        $donnees ['is_calc_IFD'] = !empty($is_IFD);
        $donnees ['is_repartition'] = !empty($is_repartition);
        $donnees ['is_distinct'] = !empty($is_distinct);
        $donnees ['revenu_imposable_ICC'] = Utilities::getRevenuImposableICC(
            $rev_ICC,
            $is_repartition,
            $donnees ['taux_revenu_ICC']
        );
        $donnees ['fortune_imposable_ICC'] = Utilities::getFortuneImposableICC(
            $fort_ICC,
            $is_repartition,
            $donnees ['taux_fortune_ICC']
        );
        $donnees ['revenu_imposable_IFD'] = Utilities::getRevenuImposableIFD(
            $rev_IFD,
            $is_repartition,
            $donnees ['taux_revenu_IFD']
        );

        return $donnees;
    }

    /**
     * reprise de function TRONQUE avec une valeur d'arrondi négative, qui arrondi à un facteur 10^$facteur
     * @param number $value la valeur
     * @param number $facteur la puissance appliquée à 10 pour déterminer l'arrondi
     * @return number
     */
    public static function arrondiGen($value, $facteur)
    {
        if ($value < pow(10, $facteur)) {
            return 0;
        }
        return $value - $value % (pow(10, $facteur));
    }

    /**
     * code champ : ID59
     *
     * @return quotient familial pour l'ICC en fonction des options d'interface selectionnées
     */
    public static function quotientFamilialInterface(
        $is_ICC,
        $is_distinct,
        $anne_fiscale,
        $etat_civil,
        $n_enfants,
        $n_enfants_demi_quotient,
        $revenu_imposable
    ) {
        if (($is_ICC || $is_distinct) && $etat_civil > 0 && $revenu_imposable > 0) {
            $resultat = self::getQuotientFamiliale(
                $anne_fiscale,
                $etat_civil,
                $n_enfants,
                $n_enfants_demi_quotient,
                $is_ICC,
                $is_distinct
            );

            return $resultat;
        }

        return null;
    }

    /**
     * * code champ : ID59
     *
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $n_enfants
     * @param $n_enfants_demi_quotient
     * @param $is_ICC
     * @param $is_distinct
     * @return int|string|quotient|null familial pour l'ICC
     */
    public static function getQuotientFamiliale(
        $annee_fiscale,
        $etat_civil,
        $n_enfants,
        $n_enfants_demi_quotient,
        $is_ICC,
        $is_distinct
    ) {
        $quotientEtatCivil = DataParser::getQuotientEtatCivil($annee_fiscale, $etat_civil);
        $quotientEnfant = self::getQuotientEnfant($n_enfants, $n_enfants_demi_quotient, $is_ICC, $is_distinct);
        if (!isset($quotientEnfant)) {
            return $quotientEtatCivil;
        }
        if (!isset($quotientEtatCivil)) {
            return $quotientEnfant;
        }
        if (is_numeric($quotientEtatCivil) && is_numeric($quotientEnfant)) {
            return $quotientEtatCivil + $quotientEnfant;
        }
        return null;
    }

    /**
     * code champ: ID58
     * quotient par enfant pour le calcul de l'ICC
     * sans calcul d'impôt distinct
     *
     * @param $noEnfants :
     *            nombre d'enfants
     * @param $noEnfantsDemiQuotient :
     *            nombre d'enfants demi quotient
     * @param $isCalculICC :
     *            calcul de l'ICC activé
     * @param $isDistinct :
     *            calcul d'impôt distinct
     * @return quotient enfant si on effectue un calcul de l'ICC sans calcul d'impôt distinct
     */
    public static function getQuotientEnfant($noEnfants, $noEnfantDemiQuotient, $isCalculICC, $isDistinct)
    {
        if ($isCalculICC && !($isDistinct)) {
            return ($noEnfants + ($noEnfantDemiQuotient / 2)) * 0.5;
        }
        return 0;
    }

    /**
     * @param array $donnees_calcul
     * @return multitype:NULL Ambigous <quotient, number, NULL> number taux Ambigous <number, NULL>
     */
    public static function getDonneesBase($donnees_calcul)
    {
        $donnees_base = [];
        $donnees_base ['taux_cant_54'] = DataParser::getTauxCantonal($donnees_calcul ['periode']);
        $donnees_base ['taux_comm_55'] = DataParser::getTauxCommunal(
            $donnees_calcul ['periode'],
            $donnees_calcul ['commune']
        );
        $donnees_base ['quot_etc_56'] = DataParser::getQuotientEtatCivil(
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil']
        );
        $donnees_base ['txt_etc_57'] = Utilities::getTextEtatCivil(
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil']
        );
        $donnees_base ['quot_enf_58'] = self::getQuotientEnfant(
            $donnees_calcul ['n_enfants'],
            $donnees_calcul ['n_enfants_demi_quotient'],
            $donnees_calcul ['is_calc_ICC'],
            $donnees_calcul ['is_distinct']
        );
        $donnees_base ['quot_fam_59'] = self::getQuotientFamiliale(
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['n_enfants'],
            $donnees_calcul ['n_enfants_demi_quotient'],
            $donnees_calcul ['is_calc_ICC'],
            $donnees_calcul ['is_distinct']
        );
        $donnees_base ['rab_enf_60'] = DataParser::getRabaisEnfant(
            $donnees_calcul ['periode'],
            $donnees_calcul ['n_enfants_menage']
        );
        $donnees_base ['rab_IFD_61'] = DataParser::getRabaisIFD(
            $donnees_calcul ['is_calc_IFD'],
            $donnees_calcul ['is_distinct'],
            $donnees_calcul ['n_enfants_menage'],
            $donnees_base ['rab_enf_60']
        );
        $donnees_base ['bar_rev_ICC_62'] = DataParser::getBaremeRevenuICC(
            $donnees_calcul ['periode'],
            $donnees_calcul ['revenu_imposable_ICC']
        );
        $donnees_base ['bar_fort_63'] = DataParser::getBaremeFortuneICC(
            $donnees_calcul ['periode'],
            $donnees_calcul ['fortune_imposable_ICC']
        );
        $donnees_base ['bar_rev_IFD_64'] = DataParser::getBaremeRevenuIFD(
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_calcul ['n_enfants_menage'],
            $donnees_calcul ['is_distinct']
        );
        $donnees_base ['plaf_quot_65'] = DataParser::getPlafondQuotientICC(
            $donnees_calcul ['is_distinct'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['n_enfants'],
            $donnees_calcul ['n_enfants_demi_quotient']
        );
        return $donnees_base;
    }

    /**
     * @param $donnees_base
     * @param $donnees_calcul
     * @return array
     */
    public static function getDonneesFortune($donnees_base, $donnees_calcul): array
    {
        $donnees_fort_ICC = [];
        $donnees_fort_ICC ['fort_taux_91'] = Utilities::getFortunePourTaux(
            $donnees_calcul ['is_repartition'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_calcul ['taux_fortune_ICC']
        );
        $donnees_fort_ICC ['fort_imp_bar_92'] = DataParser::getFortuneImposableBareme(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_fort_63'],
            $donnees_fort_ICC ['fort_taux_91']
        );
        $donnees_fort_ICC ['diff_taux_imp_93'] = Utilities::getDiffTauxFortune(
            $donnees_fort_ICC ['fort_taux_91'],
            $donnees_fort_ICC ['fort_imp_bar_92']
        );
        $donnees_fort_ICC ['supp_1000_94'] = DataParser::getSupplementPar1000(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_fort_63'],
            $donnees_fort_ICC ['fort_taux_91']
        );
        $donnees_fort_ICC ['imp_ann_fort_bar_95'] = DataParser::getImpotAnnuelFortune(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_fort_63'],
            $donnees_fort_ICC ['fort_taux_91']
        );
        $donnees_fort_ICC ['supp_imp_fort_96'] = Utilities::getSupplementImpotFortune(
            $donnees_fort_ICC ['diff_taux_imp_93'],
            $donnees_fort_ICC ['supp_1000_94']
        );
        $donnees_fort_ICC ['imp_base_fort_97'] = Utilities::getImpotBaseFortune(
            $donnees_fort_ICC ['imp_ann_fort_bar_95'],
            $donnees_fort_ICC ['supp_imp_fort_96']
        );
        $donnees_fort_ICC ['pourc_taux_fort_98'] = Utilities::getPourcentTauxFortune(
            $donnees_fort_ICC ['fort_taux_91'],
            $donnees_fort_ICC ['imp_base_fort_97']
        );
        $donnees_fort_ICC ['fort_imp_99'] = $donnees_calcul ['fortune_imposable_ICC'];
        $donnees_fort_ICC ['imp_cant_base_fort_100'] = Utilities::getImpotCantonaleBaseFortune(
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_fort_ICC ['fort_taux_91'],
            $donnees_fort_ICC ['imp_base_fort_97']
        );
        $donnees_fort_ICC ['min_imp_fort_101'] = DataParser::getMinImposableFortune(
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil']
        );
        $donnees_fort_ICC ['is_fort_imp_102'] = Utilities::isFortuneImposable(
            $donnees_fort_ICC ['fort_taux_91'],
            $donnees_fort_ICC ['min_imp_fort_101']
        );
        $donnees_fort_ICC ['coeff_imp_cant_fort_103'] = DataParser::getTauxCantonal($donnees_calcul ['periode']);
        $donnees_fort_ICC ['imp_cant_fort_104'] = Utilities::getImpotCantonaleFortune(
            $donnees_calcul ['periode'],
            $donnees_fort_ICC ['is_fort_imp_102'],
            $donnees_fort_ICC ['imp_cant_base_fort_100']
        );
        $donnees_fort_ICC ['coeff_imp_comm_fort_105'] = DataParser::getTauxCommunal(
            $donnees_calcul ['periode'],
            $donnees_calcul ['commune']
        );
        $donnees_fort_ICC ['imp_comm_fort_calc_106'] = Utilities::getImpotCommunalFortuneCalc(
            $donnees_calcul ['periode'],
            $donnees_calcul ['commune'],
            $donnees_fort_ICC ['is_fort_imp_102'],
            $donnees_fort_ICC ['imp_cant_base_fort_100'],
            $donnees_fort_ICC ['coeff_imp_comm_fort_105']
        );
        $donnees_fort_ICC ['tot_imp_fort_107'] = Utilities::getTotalImpotFortune(
            $donnees_fort_ICC ['imp_cant_fort_104'],
            $donnees_fort_ICC ['imp_comm_fort_calc_106']
        );
        $donnees_fort_ICC ['max1_fort_imp_108'] = Utilities::getMax1Fortune($donnees_fort_ICC ['fort_imp_99']);
        $donnees_fort_ICC ['coeff_imp_base_fort_109'] = Utilities::getCoeffImpotBaseFortune(
            $donnees_fort_ICC ['tot_imp_fort_107'],
            $donnees_fort_ICC ['max1_fort_imp_108']
        );
        $donnees_fort_ICC ['imp_base_fort_calc_110'] = Utilities::getImpotBaseFortuneCalc(
            $donnees_fort_ICC ['imp_cant_base_fort_100'],
            $donnees_fort_ICC ['coeff_imp_base_fort_109']
        );
        $donnees_fort_ICC ['imp_cant_fort_calc_111'] = Utilities::getImpotCantonalFortuneFinal(
            $donnees_fort_ICC ['imp_cant_fort_104'],
            $donnees_fort_ICC ['coeff_imp_base_fort_109']
        );
        $donnees_fort_ICC ['imp_comm_fort_calc_112'] = Utilities::getImpotCommunalFortuneFinal(
            $donnees_fort_ICC ['imp_comm_fort_calc_106'],
            $donnees_fort_ICC ['coeff_imp_base_fort_109']
        );
        return $donnees_fort_ICC;
    }

    /**
     * @param $donnees_base
     * @param $donnees_calcul
     * @return array
     */
    public static function getDonneesRevenuIFD($donnees_base, $donnees_calcul): array
    {
        $donnees_rev_IFD = [];
        $donnees_rev_IFD ['rev_taux_IFD_113'] = Utilities::getRevenuTauxIFD(
            $donnees_calcul ['is_repartition'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_calcul ['taux_revenu_IFD']
        );
        $donnees_rev_IFD ['rev_imp_bar_114'] = DataParser::getRevenuImposableBaremeIFD(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_rev_IFD_64'],
            $donnees_rev_IFD ['rev_taux_IFD_113']
        );
        $donnees_rev_IFD ['diff_bar_taux_IFD_115'] = Utilities::getDiffTauxIFDBareme(
            $donnees_rev_IFD ['rev_taux_IFD_113'],
            $donnees_rev_IFD ['rev_imp_bar_114']
        );
        $donnees_rev_IFD ['supp_100_IFD_116'] = DataParser::getSupplementIFDPar100(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_rev_IFD_64'],
            $donnees_rev_IFD ['rev_taux_IFD_113']
        );
        $donnees_rev_IFD ['imp_ann_IFD_117'] = DataParser::getImpotAnnuelIFD(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_rev_IFD_64'],
            $donnees_rev_IFD ['rev_taux_IFD_113']
        );
        $donnees_rev_IFD ['supp_IFD_118'] = Utilities::getSupplementImpot(
            $donnees_rev_IFD ['diff_bar_taux_IFD_115'],
            $donnees_rev_IFD ['supp_100_IFD_116']
        );
        $donnees_rev_IFD ['imp_base_IFD_119'] = Utilities::getImpotBaseIFD(
            $donnees_rev_IFD ['imp_ann_IFD_117'],
            $donnees_rev_IFD ['supp_IFD_118']
        );
        $donnees_rev_IFD ['pourc_taux_IFD_120'] = Utilities::getPourcentTauxIFD(
            $donnees_rev_IFD ['rev_taux_IFD_113'],
            $donnees_rev_IFD ['imp_base_IFD_119']
        );
        $donnees_rev_IFD ['rev_imp_IFD_121'] = $donnees_calcul ['revenu_imposable_IFD'];
        $donnees_rev_IFD ['imp_IFD_base_122'] = Utilities::getImpotIFDBase(
            $donnees_calcul ['periode'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_calcul ['taux_revenu_IFD'],
            $donnees_rev_IFD ['pourc_taux_IFD_120']
        );
        $donnees_rev_IFD ['imp_rab_IFD_bar_123'] = DataParser::getRabaisIFDBareme(
            $donnees_calcul ['taux_revenu_IFD'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_base ['rab_IFD_61']
        );
        $donnees_rev_IFD ['rab_IFD_124'] = Utilities::getRabaisIFD(
            $donnees_rev_IFD ['imp_rab_IFD_bar_123'],
            $donnees_rev_IFD ['imp_IFD_base_122']
        );
        $donnees_rev_IFD ['imp_IFD_calc_125'] = Utilities::getImpotIFDCalc(
            $donnees_rev_IFD ['imp_IFD_base_122'],
            $donnees_rev_IFD ['rab_IFD_124']
        );
        $donnees_rev_IFD ['fac_imp_IFD_155'] = DataParser::getFacteurImpotIFD(
            $donnees_calcul ['periode'],
            $donnees_rev_IFD ['imp_IFD_base_122']
        );
        $donnees_rev_IFD ['imp_IFD_156'] = Utilities::getImpotIFDFinal(
            $donnees_rev_IFD ['imp_IFD_base_122'],
            $donnees_rev_IFD ['fac_imp_IFD_155']
        );
        return $donnees_rev_IFD;
    }

    /**
     * @param $donnees_calcul
     * @param $donnees_rev_ICC
     * @return array
     */
    public static function getDonneesCapital($donnees_calcul, $donnees_rev_ICC): array
    {
        $donnees_capital = [];
        $donnees_capital ['fac_imp_base_149'] = DataParser::getFacteurImpotBase(
            $donnees_calcul ['periode'],
            $donnees_rev_ICC ['imp_cant_base_80']
        );
        $donnees_capital ['imp_base_cap_150'] = Utilities::getImpotBaseCapital(
            $donnees_rev_ICC ['imp_cant_base_80'],
            $donnees_capital ['fac_imp_base_149']
        );
        $donnees_capital ['coeff_imp_cant_cap_151'] = DataParser::getTauxCantonal($donnees_calcul ['periode']);
        $donnees_capital ['imp_cant_cap_152'] = Utilities::getCoeffImpotCantonalCapital(
            $donnees_capital ['imp_base_cap_150'],
            $donnees_capital ['coeff_imp_cant_cap_151']
        );
        $donnees_capital ['coeff_imp_comm_cap_153'] = DataParser::getTauxCommunal(
            $donnees_calcul ['periode'],
            $donnees_calcul ['commune']
        );
        $donnees_capital ['imp_comm_cap_154'] = Utilities::getImpotCommunalCapital(
            $donnees_capital ['imp_base_cap_150'],
            $donnees_capital ['coeff_imp_comm_cap_153']
        );
        return $donnees_capital;
    }

    /**
     * @param $donnees_base
     * @param $donnees_calcul
     * @param $donnees_rev_ICC
     * @param $donnees_fort_ICC
     * @param $donnees_rev_IFD
     * @param $donnees_capital
     * @return array
     */
    public static function getDonneesImpression(
        $donnees_base,
        $donnees_calcul,
        $donnees_rev_ICC,
        $donnees_fort_ICC,
        $donnees_rev_IFD,
        $donnees_capital
    ): array {
        $donnees_imp = [];
        $donnees_imp['affiche_ICC_126'] = Utilities::isCalculICC(
            $donnees_calcul ['is_calc_ICC'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['fortune_imposable_ICC']
        );
        $donnees_imp['affiche_rep_127'] = Utilities::isRepartition(
            $donnees_calcul ['is_repartition'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['taux_fortune_ICC'],
            $donnees_calcul ['taux_revenu_IFD']
        );
        $donnees_imp['affiche_IFD_128'] = Utilities::isCalculIFD(
            $donnees_calcul ['is_calc_IFD'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_IFD']
        );
        $donnees_imp['affiche_dist_131'] = Utilities::isImpotDistinct(
            $donnees_calcul ['is_distinct'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['revenu_imposable_IFD']
        );
        $donnees_imp['quot_fam_129'] = self::impressionQuotientFamilial(
            $donnees_calcul ['is_calc_ICC'],
            $donnees_calcul ['is_distinct'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_base ['quot_fam_59']
        );
        $donnees_imp['rev_taux_130'] = self::getRevenuTauxInterface(
            $donnees_calcul ['is_calc_ICC'],
            $donnees_calcul ['is_distinct'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_rev_ICC ['rev_vs_pq_71']
        );
        $donnees_imp['rev_imp_132'] = Utilities::afficheRevenuIFD(
            $donnees_calcul ['is_calc_IFD'],
            $donnees_calcul ['is_distinct'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['revenu_imposable_IFD']
        );
        $donnees_imp['imp_base_133'] = Utilities::impotBaseAffichage(
            $donnees_imp['affiche_IFD_128'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_rev_IFD ['imp_IFD_base_122'],
            $donnees_rev_IFD ['imp_IFD_156']
        );
        $donnees_imp['rab_IFD_134'] = Utilities::rabaisIFDAffichage(
            $donnees_imp['affiche_IFD_128'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_rev_IFD ['rab_IFD_124']
        );
        $donnees_imp['charge_fisc_rev_135'] = Utilities::getChargeFiscaleRevenu(
            $donnees_imp['affiche_IFD_128'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_IFD'],
            $donnees_rev_IFD ['imp_IFD_calc_125'],
            $donnees_rev_IFD ['imp_IFD_156']
        );
        $donnees_imp['rev_imp_136'] = Utilities::afficheRevenuImposable(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC']
        );
        $donnees_imp['fort_imp_137'] = Utilities::afficheFortuneImposable(
            $donnees_imp['affiche_ICC_126'],
            $donnees_calcul ['fortune_imposable_ICC']
        );
        $donnees_imp['coeff_cant_138'] = Utilities::afficheCoeffCanton(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_base ['taux_cant_54']
        );
        $donnees_imp['coeff_comm_139'] = Utilities::afficheCoeffCommune(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_base ['taux_comm_55']
        );
        $donnees_imp['charge_fisc_rev_cant_140'] = Utilities::getChargeFiscaleRevenuCanton(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_rev_ICC ['imp_cant_rev_89'],
            $donnees_capital ['imp_cant_cap_152']
        );
        $donnees_imp['charge_fisc_rev_comm_141'] = Utilities::getChargeFiscaleRevenuCommune(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_base ['taux_comm_55'],
            $donnees_rev_ICC ['imp_comm_rev_calc_90'],
            $donnees_capital ['imp_comm_cap_154']
        );
        $donnees_imp['comm_charge_rev_142'] = Utilities::getNomCommuneRevenu(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_base ['taux_comm_55'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['commune']
        );
        $donnees_imp['charge_fisc_fort_cant_143'] = Utilities::getChargeFiscaleFortuneCanton(
            $donnees_imp['affiche_ICC_126'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_fort_ICC ['imp_cant_fort_calc_111']
        );
        $donnees_imp['charge_fisc_fort_cant_144'] = Utilities::getChargeFiscaleFortuneCommune(
            $donnees_imp['affiche_ICC_126'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_fort_ICC ['imp_comm_fort_calc_112']
        );
        $donnees_imp['comm_charge_fort_146'] = Utilities::getNomCommuneFortune(
            $donnees_imp['affiche_ICC_126'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_base ['taux_comm_55'],
            $donnees_calcul ['periode'],
            $donnees_calcul ['commune']
        );

        $donnees_imp['tot_final_ICC_147'] = Utilities::afficheTotalFinalICC(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_imp['charge_fisc_rev_cant_140'],
            $donnees_imp['charge_fisc_rev_comm_141'],
            $donnees_imp['charge_fisc_fort_cant_143'],
            $donnees_imp['charge_fisc_fort_cant_144']
        );
        $donnees_imp['tot_ICC_IFD_148'] = Utilities::getTotalICCplusIFD(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_imp['affiche_IFD_128'],
            $donnees_imp['tot_final_ICC_147'],
            $donnees_imp['charge_fisc_rev_135']
        );
        $donnees_imp['imp_base_rev_158'] = Utilities::getImpotBaseRevenu(
            $donnees_imp['affiche_ICC_126'],
            $donnees_imp['affiche_dist_131'],
            $donnees_calcul ['etat_civil'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_rev_ICC ['imp_cant_base_coeff_88'],
            $donnees_capital ['imp_base_cap_150']
        );
        $donnees_imp['imp_base_fort_159'] = Utilities::afficheImpotBaseFortune(
            $donnees_imp['affiche_ICC_126'],
            $donnees_calcul ['fortune_imposable_ICC'],
            $donnees_fort_ICC ['imp_cant_base_fort_100']
        );

        $donnees_imp['plaf_deduction_ICC_161'] = Utilities::getPlafondDeductionICC(
            $donnees_base ['plaf_quot_65'],
            $donnees_calcul ['periode']
        );

        $donnees_imp['abattement_162'] = Utilities::getAbattement($donnees_calcul ['periode']);

        $donnees_imp ['montant_abattement_163'] = Utilities::getMontantAbattement(
            $donnees_rev_ICC ['imp_cant_rev_82'],
            Utilities::getAbattement($donnees_calcul ['periode'])
        );

        $donnees_imp ['impot_cantonal_apres_abattement_164'] = Utilities::getImpotCantonalAbattement(
            $donnees_rev_ICC ['imp_cant_rev_82'],
            $donnees_rev_ICC ['montant_abattement_163']
        );
        return $donnees_imp;
    }

    /**
     * code champ : ID129
     * @param $is_icc
     * @param $is_distinct
     * @param $code_civil
     * @param $revenu_imposable_icc
     * @param $quotient_familial
     */
    public static function impressionQuotientFamilial(
        $is_icc,
        $is_distinct,
        $code_civil,
        $revenu_imposable_icc,
        $quotient_familial
    ) {
        if (($is_icc && $is_distinct) || $code_civil > 0 || $revenu_imposable_icc) {
            return sprintf('%.2f', $quotient_familial);
        }
        return null;
    }

    /**
     * code champ : ID130
     * @param $is_ICC
     * @param $annee_fiscale
     * @param $etat_civil
     * @param $revenu_imposableICC
     * @param $fortune_imposable_icc
     * @param $revenu_taux_icc_id66
     */
    public static function getRevenuTauxInterface(
        $is_ICC,
        $is_distinct,
        $etat_civil,
        $revenu_imposableICC,
        $revenu_taux_icc_id66
    ) {
        if (($is_ICC && $is_distinct) || ($etat_civil > 0 && $revenu_imposableICC > 0)) {
            return $revenu_taux_icc_id66;
        }
        return null;
    }

    /**
     * @param $donnees_base
     * @param $donnees_calcul
     * @return array
     */
    public static function getDonneesRevenuICC($donnees_base, $donnees_calcul): array
    {
        $donnees_rev_ICC = [];
        $donnees_rev_ICC ['rev_taux_66'] = Utilities::getRevenuPourTaux(
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['is_repartition']
        );
        $donnees_rev_ICC ['plaf_quot_67'] = DataParser::getPlafondQuotientFamiliale(
            $donnees_calcul['is_distinct'],
            $donnees_calcul['periode'],
            $donnees_calcul['etat_civil'],
            $donnees_calcul['n_enfants'],
            $donnees_calcul ['n_enfants_demi_quotient'],
            $donnees_base ['plaf_quot_65']
        );
        $donnees_rev_ICC ['rat_rev_quot_68'] = Utilities::getRatioRevenuQuotient(
            $donnees_rev_ICC ['rev_taux_66'],
            $donnees_base ['quot_fam_59']
        );
        $donnees_rev_ICC ['ded_69'] = DataParser::getDeduction(
            $donnees_base ['quot_etc_56'],
            $donnees_base ['quot_fam_59'],
            $donnees_base ['plaf_quot_65'],
            $donnees_rev_ICC ['plaf_quot_67']
        );
        $donnees_rev_ICC ['rev_plaf_taux_70'] = Utilities::getRevenuPlafondTaux(
            $donnees_rev_ICC ['rev_taux_66'],
            $donnees_base ['quot_etc_56'],/*$donnees_rev_ICC ['plaf_quot_67'],$donnees_base ['quot_etc_56'],*/
            $donnees_rev_ICC ['ded_69']
        );
        $donnees_rev_ICC ['rev_vs_pq_71'] = Utilities::getRatioRevenuSurPlafondQuotient(
            $donnees_rev_ICC ['rev_taux_66'],
            $donnees_rev_ICC ['plaf_quot_67'],
            $donnees_rev_ICC ['rat_rev_quot_68'],
            $donnees_rev_ICC ['rev_plaf_taux_70']
        );
        $donnees_rev_ICC ['rev_imp_bar_72'] = DataParser::getRevenuImposableBareme(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_rev_ICC_62'],
            $donnees_rev_ICC ['rev_vs_pq_71']
        );
        $donnees_rev_ICC ['diff_taux_imp_73'] = Utilities::getDiffTauxImposable(
            $donnees_rev_ICC ['rev_vs_pq_71'],
            $donnees_rev_ICC ['rev_imp_bar_72']
        );
        $donnees_rev_ICC ['supp_100_74'] = DataParser::getSupplementPar100(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_rev_ICC_62'],
            $donnees_rev_ICC ['rev_vs_pq_71']
        );
        $donnees_rev_ICC ['imp_ann_bar_75'] = DataParser::getImpotAnnuelBareme(
            $donnees_calcul ['periode'],
            $donnees_base ['bar_rev_ICC_62'],
            $donnees_rev_ICC ['rev_vs_pq_71']
        );
        $donnees_rev_ICC ['supp_imp_76'] = Utilities::getSupplementImpot(
            $donnees_rev_ICC ['diff_taux_imp_73'],
            $donnees_rev_ICC ['supp_100_74']
        );
        $donnees_rev_ICC ['imp_base_77'] = Utilities::getImpotBase(
            $donnees_rev_ICC ['imp_ann_bar_75'],
            $donnees_rev_ICC ['supp_imp_76']
        );
        $donnees_rev_ICC ['pourc_taux_78'] = Utilities::getPourcentPourTaux(
            $donnees_rev_ICC ['rev_vs_pq_71'],
            $donnees_rev_ICC ['imp_base_77']
        );
        $donnees_rev_ICC ['rev_imp_calc_79'] = $donnees_calcul ['revenu_imposable_ICC'];
        $donnees_rev_ICC ['imp_cant_base_80'] = Utilities::getImpotCantonalBase(
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_rev_ICC ['pourc_taux_78']
        );
        $donnees_rev_ICC ['coeff_imp_cant_rev_81'] = $donnees_base ['taux_cant_54'];
        $donnees_rev_ICC ['imp_cant_rev_82'] = Utilities::getImpotCantonalRevenu(
            $donnees_base ['taux_cant_54'],
            $donnees_rev_ICC ['imp_cant_base_80']
        );
        $donnees_rev_ICC ['montant_abattement_163'] = Utilities::getMontantAbattement(
            $donnees_rev_ICC ['imp_cant_rev_82'],
            Utilities::getAbattement($donnees_calcul ['periode'])
        );
        $donnees_rev_ICC ['impot_cantonal_apres_abattement_164'] = Utilities::getImpotCantonalAbattement(
            $donnees_rev_ICC ['imp_cant_rev_82'],
            $donnees_rev_ICC ['montant_abattement_163']
        );
        $donnees_rev_ICC ['coeff_imp_comm_rev_83'] = $donnees_base ['taux_comm_55'];
        $donnees_rev_ICC ['imp_comm_rev_84'] = Utilities::getImpotCommunalRevenu(
            $donnees_rev_ICC ['imp_cant_base_80'],
            $donnees_base ['taux_comm_55']
        );
        $donnees_rev_ICC ['tot_ICC_85'] = Utilities::getTotalICC(
            $donnees_rev_ICC ['impot_cantonal_apres_abattement_164'],
            $donnees_rev_ICC ['imp_comm_rev_84']
        );
        $donnees_rev_ICC ['max30_rev_imp_86'] = Utilities::getMax30RevenuImposable(
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['is_repartition']
        );
        $donnees_rev_ICC ['coeff_imp_cant_base_87'] = Utilities::getCoeffImpotCantonalBase(
            $donnees_rev_ICC ['tot_ICC_85'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['is_repartition']
        );
        $donnees_rev_ICC ['imp_cant_base_coeff_88'] = Utilities::getImpotCantonalBasePourCoeff(
            $donnees_rev_ICC ['imp_cant_base_80'],
            $donnees_rev_ICC ['tot_ICC_85'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['is_repartition']
        );
        $donnees_rev_ICC ['imp_cant_rev_89'] = Utilities::getImpotCantSurRevenu(
            Utilities::getImpotCantonalAbattement(
                $donnees_rev_ICC ['imp_cant_rev_82'],
                $donnees_rev_ICC ['montant_abattement_163']
            ),
            $donnees_rev_ICC ['tot_ICC_85'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['is_repartition']
        );
        $donnees_rev_ICC ['imp_comm_rev_calc_90'] = Utilities::getImpotCommunalRevenuCalc(
            $donnees_rev_ICC ['imp_comm_rev_84'],
            $donnees_rev_ICC ['tot_ICC_85'],
            $donnees_calcul ['revenu_imposable_ICC'],
            $donnees_calcul ['taux_revenu_ICC'],
            $donnees_calcul ['is_repartition']
        );
        return $donnees_rev_ICC;
    }
}
