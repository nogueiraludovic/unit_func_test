const OcospApplication = {
  activateCheckboxes: () => {
    const handleInterests = async () => {
      const form = document.querySelector('#tx_vdocosp_dmde_public_search_interests')
      const selectedInterests = form.querySelectorAll('#tx_vdocosp_dmde_public_search_interests input:checked')

      if (selectedInterests.length === 0) {
        OcospApplication.resetResultCount()
      } else {
        let url = '/ocosp/ajax?'
        let urlParts = []

        selectedInterests.forEach(selectedInterest => {
          urlParts.push(`tx_vdocosp[interests][]=${selectedInterest.value}`)
        })

        const response = await fetch(url + urlParts.join('&'), {
          method: 'GET',
          headers: {
            'Content-type': 'application/json;charset=UTF-8'
          },
        })
        const data = await response.json()

        if (response.ok) {
          OcospApplication.updateResultCount(data.count);
        } else {
          console.log(response.status)
        }
      }
    }

    document.querySelector('#tx_vdocosp_dmde_public_search_interests').addEventListener('change', () => {
      handleInterests()
    })
  },
  /**
   * Displays a result message depending on a number of matches
   *
   * @param count
   */
  updateResultCount: count => {
    // If the count is zero, also disable the search button
    if (count === 0) {
      document.querySelector('#tx_vdocosp_interests_search_button').setAttribute('disabled', 'true')
    } else {
      document.querySelector('#tx_vdocosp_interests_search_button').removeAttribute('disabled')
    }
    let message = 'Aucune profession ne correspond à vos critères de recherche.';
    if (count === 1) {
      message = 'Une seule profession correspond à vos critères de recherche.';
    } else if (count > 1) {
      message = count + ' professions correspondent à vos critères de recherche.';
    }
    document.querySelector('#tx_vdocosp_search_results').innerHTML = `<p>${message}</p>`
  },
  resetResultCount: () => {
    const searchResultsElement = document.querySelector('#tx_vdocosp_search_results')
    while (searchResultsElement.firstChild) {
      searchResultsElement.removeChild(searchResultsElement.firstChild)
    }
    document.querySelector('#tx_vdocosp_interests_search_button').removeAttribute('disabled')
  },
  /**
   * Activates the autocomplete on the profession search field
   */
  activateAutocomplete: () => {
    new window.autocomplete(document.querySelector('#tx_vdocosp_metier_search'), {
      items: Tx_VdOcosp_Professions,
      valueField: 'data',
      labelField: 'value',
      highlightTyped: true,
      fullWidth: true,
      highlightClass: 'bg-primary fw-bold text-white',
      suggestionsThreshold: 3,
      onSelectItem: (item) => {
        document.querySelector('#tx_vdocosp_metier_id').value = item.value
        document.querySelector('#tx_vdocosp_dmde_public_search_keyword').submit()
      }
    })
  }
}

document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('#tx_vdocosp_dmde_public_search_interests')) {
    OcospApplication.activateCheckboxes();
  }
  if (document.querySelector('#tx_vdocosp_metier_search')) {
    OcospApplication.activateAutocomplete();
  }
})
