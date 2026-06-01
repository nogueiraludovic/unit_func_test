'use strict'

export default class Event {
  constructor (name, callback) {
    this.callback = callback
    this.name = name
  }

  delegateTo (element, selector) {
    element.addEventListener(this.name, event => {
      for (let target = event.target; (typeof target) !== 'undefined' && target !== element; target = target.parentNode) {
        if (target.matches(selector) === false) {
          continue;
        }

        event.preventDefault()

        this.callback.call(target, event)

        break
      }
    })
  }
}
