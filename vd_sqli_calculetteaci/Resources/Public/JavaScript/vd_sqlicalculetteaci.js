var vdsqlicalculetteaci_Manager = function() {
  var manager_calc = {};
  manager_calc.test_isrepartition = function() {
    if (document.getElementById('tx_vdsqlicalculetteaci_pi1_repartition').checked === true) {
      if(document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableICC').value =='') {
        document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableICC').value =  document.getElementById('tx_vdsqlicalculetteaci_pi1_revenuImposableICC').value
      }
      if(document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxFortuneImposableICC').value =='') {
        document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxFortuneImposableICC').value =  document.getElementById('tx_vdsqlicalculetteaci_pi1_fortuneImposableICC').value
      }
      if(document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableIFD').value =='') {
        document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableIFD').value =  document.getElementById('tx_vdsqlicalculetteaci_pi1_revenuImposableIFD').value
      }
    } else {
      document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableICC').value = 0;
      document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableIFD').value = 0;
      document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxFortuneImposableICC').value = 0;
    }
    manager_calc.coherenceSelectionFamille(document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfants'));
  }
  var sendClass = function (condition, depentents_class){
      console.log(depentents_class)
      var dependentsEl = document.querySelectorAll('.' + depentents_class);
      if (condition)
      {
        for (i=0; i<dependentsEl.length; i++)
        {
          console.log(dependentsEl[1])
          re = /hidden/gi;
          if (dependentsEl[i].hasAttribute('class')){
            str = dependentsEl[i].classList.toString();
          }
          console.log(str)
          if (str) {
            found = str.match(re);
          }
          if (found){
            console.log(dependentsEl[i])
            dependentsEl[i].classList.remove('hidden');
            document.getElementById('tx_vdsqlicalculetteaci_pi1_afficher').disabled = true;
          }
        }
      }
      else
      {
        for (i=0; i<dependentsEl.length; i++)
        {
          re = /hidden/gi;
          if (dependentsEl[i].hasAttribute('class')){
            str = dependentsEl[i].classList.toString();
          }
          console.log(str)
          if (str) {
            found = str.match(re);
          }
          if (!found){
            dependentsEl[i].classList.add('hidden');
          }
        }

        if(!hasError()){
          document.getElementById('tx_vdsqlicalculetteaci_pi1_afficher').disabled = false;
        }
      }

      var id = depentents_class + '-danger';
      if (document.querySelector('#' + id).length === 1) {
        if(condition){
          if(document.querySelector('#' + id).classList.contains('has-danger')){
          } else {
            document.querySelector('#' + id).classList.add('has-danger');
          }
        } else {
          if(document.querySelector('#' + id).classList.contains('has-danger')){
            document.querySelector('#' + id).classList.remove('has-danger');
          }
        }
      }

      return true;
    }
  ;

  var isNumber = function (n)
    {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
  ;

  /**
   * Check if form has error
   *
   * @returns {boolean}
   */
  var hasError = function(){
    var elements = document.getElementsByClassName('error');
    var total = 0;

    for (var i = 0; i < elements.length; i++){
      if(!hasClass(elements[i], 'hidden')){
        total++;
      }
    }
    return total > 0;
  };

  /**
   * Check if given element has given className
   *
   * @param element
   * @param className
   * @returns {boolean}
   */
  var hasClass = function(element, className){
    if (element.hasAttribute('class')){
      var classNames = element.getAttribute('class');
      return classNames.indexOf(className) !== -1;
    }
    return false;
  };

  var removeClass = function(element, className){
    var classNames = element.getAttribute('class');
    element.className = classNames.replace(className, '');
  };

  manager_calc.onPeriodeChange = function() {
    var periode = document.querySelector('#tx_vdsqlicalculetteaci_pi1_periode').value;
    var communeHelp =  document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune_HelpChanged');
    var communeContainer =  document.querySelector('#commune-container');
    if(communeHelp){
      communeHelp.classList.add('hidden');
      communeHelp.setAttribute('aria-hidden','true');
      communeContainer.classList.remove('form-group', 'has-danger', 'vd-form-group-danger');
    }

    if (periode) {
      document.querySelector('#commune-container').style.display = 'flex';
      const currentDataPeriodeCommune = document.querySelectorAll('#tx_vdsqlicalculetteaci_pi1_commune option');
      if (currentDataPeriodeCommune) {
        currentDataPeriodeCommune.forEach((communeOption) => {
          document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune_reference ').add(communeOption)
        })
      }
      document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune').innerHTML = '';
      document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune').value = '';
      var dataPeriodeCommune = document.querySelectorAll('#tx_vdsqlicalculetteaci_pi1_commune_reference option[data-periode="' + periode + '"]');
      const emptyOption = document.createElement('option');
      emptyOption.value = '';
      document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune').add(emptyOption)
      dataPeriodeCommune.forEach((communeOption) => {
        document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune').add(communeOption)
      })

      if (document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune option[selected="selected"]').value) {
        document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune').value = document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune option[selected="selected"]').value
      }
    } else {
      document.querySelector('#commune-container').style.display = 'none';

    }
  };

  var versionNavigator = function() {
    var rv = -1;

    if (navigator.appName == 'Microsoft Internet Explorer')
    {
      var ua = navigator.userAgent;
      var re  = new RegExp('MSIE ([0-9]{1,}[\.0-9]{0,})');
      if (re.exec(ua) != null)
        rv = parseFloat( RegExp.$1 );
    }
    else if (navigator.appName == 'Netscape')
    {
      var ua = navigator.userAgent;
      var re  = new RegExp('Trident/.*rv:([0-9]{1,}[\.0-9]{0,})');
      if (re.exec(ua) != null)
        rv = parseFloat( RegExp.$1 );
    }
    return rv;
  };

  manager_calc.checkNumeric = function (object, errorDest,defaultvalue)
  {
    if(object)
    {
      if (object.value)
      {
        sendClass((!isNumber(object.value)||(String(object.value)).length > 11), errorDest);
      }
    }
    return true;
  }
  ;

  manager_calc.clearId = function (id)
  {
    document.getElementById(id).select();

  };

  manager_calc.clear = function ()
  {
    document.getElementById('tx_vdsqlicalculetteaci_pi1_calculICC').checked = null;
    document.getElementById('tx_vdsqlicalculetteaci_pi1_calculIFD').checked = null;
    document.getElementById('tx_vdsqlicalculetteaci_pi1_repartition').checked = null;
    document.getElementById('tx_vdsqlicalculetteaci_pi1_impotDistinct').checked = null;

    document.getElementById('tx_vdsqlicalculetteaci_pi1_periode').value = null;

    document.querySelector('#tx_vdsqlicalculetteaci_pi1_periode  option').removeAttribute('selected');
    const communes = document.querySelectorAll('#tx_vdsqlicalculetteaci_pi1_commune  option');
    communes.forEach(commune => {
      commune.removeAttribute('selected');
    })
    document.querySelector('#tx_vdsqlicalculetteaci_pi1_commune').value = null;
    const references = document.querySelectorAll('#tx_vdsqlicalculetteaci_pi1_commune_reference option')
    references.forEach(reference => {
      reference.removeAttribute('selected');
    })

    document.getElementById('tx_vdsqlicalculetteaci_pi1_etatCivil').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfants').value = '0';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfantsDemiQuotient').value = '0';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfantsMenage').value = '0';

    document.getElementById('tx_vdsqlicalculetteaci_pi1_revenuImposableICC').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableICC').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_fortuneImposableICC').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxFortuneImposableICC').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_revenuImposableIFD').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_tauxRevenuImposableIFD').value = '';

    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_part_familiale').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_taux').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_montant_imp').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_impot_base').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_fortune_montant_imp').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_fortune_impot_base').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_coef_cant').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_cf_cant').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_coef_comm').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_cf_comm').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_fortune_cf_cant').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_fortune_cf_comm').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_total_icc').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_ifd').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_revenu_base_ifd').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_rabais_ifd').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_total_ifd').value = '';
    document.getElementById('tx_vdsqlicalculetteaci_pi1_res_total_icc_ifd').value = '';
    manager_calc.hideAndSetButtons(document.getElementById('tx_vdsqlicalculetteaci_pi1_impotDistinct'));
    manager_calc.onPeriodeChange();

    document.querySelectorAll('.error').forEach((element) => {
      if(!element.classList.contains('hidden')){
        str = element.getAttribute('class');
        element.setAttribute('class', str + ' hidden');
        console.log('replace');
      }
    });

    return true;
  }
  ;

  manager_calc.hideAndSetButtons = function (object)
  {
    var calculICC = document.getElementById('tx_vdsqlicalculetteaci_pi1_calculICC');
    var calculIFD = document.getElementById('tx_vdsqlicalculetteaci_pi1_calculIFD');
    var repartition= document.getElementById('tx_vdsqlicalculetteaci_pi1_repartition');
    var impotDistinct = document.getElementById('tx_vdsqlicalculetteaci_pi1_impotDistinct');
    if (calculICC && calculIFD && repartition && impotDistinct)
    {
      if (object.id == impotDistinct.id)
      {
        calculICC.checked = '';
        calculIFD.checked = '';
        repartition.checked = '';

        if(impotDistinct.checked){
          var icc = document.getElementById('revenuImposableICCHelpText');
          if(icc){
            removeClass(icc, 'hidden');
          }
          var ifd = document.getElementById('revenuImposableIFDHelpText');
          if(ifd){
            removeClass(ifd, 'hidden');
          }
        }

        document.querySelectorAll('.err_mono, .err_enfants').forEach((element) => {
          if (!element.classList.contains('hidden')) {
            str = element.getAttribute('class');
            element.setAttribute('class', str + ' hidden');
          }
        });
      }
      else
      {
        impotDistinct.checked = '';
      }
    }

    const iccRelatedDisplay = (hidden) => {
      if (hidden) {
        const repartitionElements = document.querySelectorAll('.repartition_element')
        repartitionElements.forEach(element => {
          if (!element.classList.contains('hidden')) {
            element.classList.add('hidden')
          }
        })
        document.querySelector('#tx_vdsqlicalculetteaci_pi1_repartition').checked = false
      } else {
        const repartitionElements = document.querySelectorAll('.repartition_element')
        repartitionElements.forEach(element => {
          if (element.classList.contains('hidden')) {
            element.classList.remove('hidden')
          }
        })
      }
    }

    const repartitionElementDisplay = (hidden) => {
      if (hidden) {
        const repartitionDependents = document.querySelectorAll('.repartition_dependent')
        repartitionDependents.forEach(element => {
          if (!element.classList.contains('hidden')) {
            element.classList.add('hidden')
          }
        })
      } else {
        const repartitionDependents = document.querySelectorAll('.repartition_dependent')
        repartitionDependents.forEach(element => {
          if (element.classList.contains('hidden')) {
            element.classList.remove('hidden')
          }
        })
      }
    }

    const dinstinctRelatedDisplay = (hidden) => {
      if (hidden) {
        const repartitionDependents = document.querySelectorAll('.distinct_dependent')
        repartitionDependents.forEach(element => {
          if (!element.classList.contains('hidden')) {
            element.classList.add('hidden')
          }
        })
      } else {
        const repartitionDependents = document.querySelectorAll('.distinct_dependent')
        repartitionDependents.forEach(element => {
          if (element.classList.contains('hidden')) {
            element.classList.remove('hidden')
          }
        })
      }
    }

    if (calculICC.checked) {
      iccRelatedDisplay(false)
    } else {
      iccRelatedDisplay(true)
    }

    if (repartition.checked) {
      repartitionElementDisplay(false)
    } else {
      repartitionElementDisplay(true)
    }

    if (impotDistinct.checked) {
      repartitionElementDisplay(true)
      iccRelatedDisplay(true)
      calculICC.checked = false
      dinstinctRelatedDisplay(false)
      const notDinstinctElement = document.querySelectorAll('.not_distinct')
      notDinstinctElement.forEach(element => {
        if (!element.classList.contains('hidden')) {
          element.classList.add('hidden')
        }
      })
    } else {
      dinstinctRelatedDisplay(true)
      const notDinstinctElement = document.querySelectorAll('.not_distinct')
      notDinstinctElement.forEach(element => {
        if (element.classList.contains('hidden')) {
          element.classList.remove('hidden')
        }
      })
    }
    return true;
  }
  ;

  manager_calc.coherenceSelectionFamille = function (object)
  {
    var impotDistinct = document.getElementById('tx_vdsqlicalculetteaci_pi1_impotDistinct');
    // if impot distinct, does not check children
    if(impotDistinct.checked){
      return true;
    }

    var currentMenuId = object.id;
    var menuEnfants = document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfants');
    var menuEnfantsDemiQuotient = document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfantsDemiQuotient');
    var menuEnfantsMenage = document.getElementById('tx_vdsqlicalculetteaci_pi1_noEnfantsMenage');
    var menuEtatCivil =  document.getElementById('tx_vdsqlicalculetteaci_pi1_etatCivil');


    if(menuEnfants && menuEnfantsDemiQuotient && menuEnfantsMenage && menuEtatCivil)
    {
      if (currentMenuId == menuEnfants.id || currentMenuId == menuEnfantsDemiQuotient.id ||  currentMenuId == menuEnfantsMenage.id || currentMenuId == menuEtatCivil.id)
      {
        sendClass((parseInt(menuEnfants.value) + parseInt(menuEnfantsDemiQuotient.value)   < parseInt(menuEnfantsMenage.value)), 'err_enfants');
        sendClass((parseInt(menuEtatCivil.value) == 3 &&
          (parseInt(menuEnfants.value) +parseInt(menuEnfantsDemiQuotient.value)  < 1 || parseInt(menuEnfantsMenage.value) < 1 ) &&
          (parseInt(menuEnfants.value) +parseInt(menuEnfantsDemiQuotient.value)   >= parseInt(menuEnfantsMenage.value))), 'err_mono');
      }
    }
    return true;
  }
  ;
  return manager_calc;
};


document.addEventListener('DOMContentLoaded', function() {
  // usage
  vdsqlicalculetteaci_manager = vdsqlicalculetteaci_Manager();

  vdsqlicalculetteaci_manager.onPeriodeChange();
});
