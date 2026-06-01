/* eslint no-undef: "off" */
define(['jquery'], function ($) {
  'use strict'

  return new class {
    constructor () {
      this.initialize()
    }

    initialize () {
      const self = this
      const $inputSearch = $('.formengine-field-font-awesome input[data-formengine-action="filter"]')

      $('.formengine-field-font-awesome').each(function () {
        const $self = $(this)
        const $inputHidden = $self.find('input[type=hidden]')
        const $iconCurrent = $self.find('.icon-current')
        const $iconSelected = $iconCurrent.find('.icon-selected')

        if ($inputHidden.val() === '') {
          $iconCurrent.hide()
        }

        $self.on('click', '.btn-delete', function () {
          $self.find('.active').removeClass('active')

          $inputHidden.val('')
          $iconCurrent.hide()
        })
        $self.on('click', '.item', function () {
          const newValue = $(this).data('value')

          $(this).addClass('active')
          $(this).siblings('.active').removeClass('active')

          $inputHidden.val(newValue)
          $iconSelected.find('i').attr('class', newValue + ' fa-5x')
          $iconSelected.find('span').text(newValue)
          $iconCurrent.show()
        })
      })

      $inputSearch.on('change keydown keyup', function () {
        self._updateList($(this).val())
      })
    }

    _updateList (query) {
      const $items = $('.formengine-field-font-awesome .icon-list .item')

      $items.each(function () {
        if (query === '' || $(this).data('keywords').toString().toLowerCase().indexOf(query.toLowerCase()) >= 0) {
          $(this).removeClass('hidden')
        } else {
          $(this).addClass('hidden')
        }
      })

      if ($items.length === $('.formengine-field-font-awesome .icon-list .item.hidden').length) {
        $('.formengine-field-font-awesome .icon-list .empty').removeClass('hidden')
      } else {
        $('.formengine-field-font-awesome .icon-list .empty').addClass('hidden')
      }
    }
  }()
})
