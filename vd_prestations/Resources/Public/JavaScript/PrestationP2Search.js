'use strict'

document.addEventListener('DOMContentLoaded', () => {
  const searchBar = document.querySelector('.search-bar')

  if (searchBar === null) {
    return
  }

  search(searchBar.value.toLowerCase())

  searchBar.addEventListener('keyup', event => {
    search(event.target.value.toLowerCase())
  })

  searchBar.closest('form').addEventListener('submit', event => {
    event.preventDefault()
  })
})

function search(value) {
  const listLinks = document.querySelector('.vd-list-links')
  const services = listLinks.querySelectorAll('li > *')

  for (let service of services) {
    const li = service.closest('li')

    if (li.classList.contains('no-results')) {
      continue
    }

    if (service.innerHTML.toLowerCase().indexOf(value) > -1) {
      li.classList.remove('d-none')
    } else {
      li.classList.add('d-none')
    }
  }

  const hiddenServices = listLinks.querySelectorAll('li:not(.d-none):not(.no-results)').length
  const noResult = listLinks.querySelector('& > li.no-results')

  if (hiddenServices === 0) {
    if (noResult === null) {
      const li = document.createElement('li');
      li.classList.add('no-results');

      const p = document.createElement('p');
      p.textContent = 'Aucun résultat.';

      li.appendChild(p);

      document.querySelector('.vd-list-links').appendChild(li);
    } else {
      noResult.classList.remove('d-none')
    }
  } else if (hiddenServices > 0 && noResult !== null) {
    noResult.classList.add('d-none')
  }
}
