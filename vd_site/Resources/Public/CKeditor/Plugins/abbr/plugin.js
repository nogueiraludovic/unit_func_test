CKEDITOR.plugins.add('abbr', {
  icons: 'abbr',
  init: function (editor) {
    editor.addCommand('abbr', new CKEDITOR.dialogCommand('abbrDialog', {
      allowedContent: 'abbr[title]',
      contentForms: [
        'abbr',
        'acronym'
      ],
      requiredContent: 'abbr'
    }))
    editor.ui.addButton('abbr', {
      command: 'abbr',
      icon: this.path + 'icons/abbr.png',
      label: editor.lang.abbr.buttonTitle,
      toolbar: 'insert'
    })

    if (editor.contextMenu) {
      editor.addMenuGroup('abbrGroup')
      editor.addMenuItem('abbrItem', {
        command: 'abbr',
        group: 'abbrGroup',
        icon: this.path + 'icons/abbr.png',
        label: editor.lang.abbr.menuItemTitle
      })
      editor.contextMenu.addListener(function (element) {
        if (element.getAscendant('abbr', true)) {
          return { abbrItem: CKEDITOR.TRISTATE_OFF }
        }
      })
    }

    CKEDITOR.dialog.add('abbrDialog', this.path + 'dialogs/abbr.js')
  },
  lang: 'en,fr'
});
