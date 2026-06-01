CKEDITOR.plugins.add('quote', {
  icons: 'quote',
  init: function (editor) {
    editor.addCommand('quote', new CKEDITOR.dialogCommand('quoteDialog'))
    editor.ui.addButton('quote', {
      command: 'quote',
      icon: this.path + 'icons/quote.png',
      label: editor.lang.quote.buttonTitle,
      toolbar: 'insert'
    })

    if (editor.contextMenu) {
      editor.addMenuGroup('quoteGroup')
      editor.addMenuItem('quoteItem', {
        command: 'quote',
        group: 'quoteGroup',
        icon: this.path + 'icons/quote.png',
        label: editor.lang.quote.menuItemTitle
      })
      editor.contextMenu.addListener(function (element) {
        if (element.getAscendant('blockquote', true)) {
          return { blockquote: CKEDITOR.TRISTATE_OFF }
        }
      })
    }

    CKEDITOR.dialog.add('quoteDialog', this.path + 'dialogs/quote.js')
  },
  lang: 'en,fr'
});
