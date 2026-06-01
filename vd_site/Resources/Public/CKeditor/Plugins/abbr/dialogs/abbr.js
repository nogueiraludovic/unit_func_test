CKEDITOR.dialog.add('abbrDialog', function (editor) {
  return {
    contents: [
      {
        elements: [
          {
            commit: function (element) {
              element.setText(this.getValue())
            },
            id: 'abbr',
            label: editor.lang.abbr.dialogAbbrText,
            setup: function (element) {
              this.setValue(element.getText())
            },
            type: 'text',
            validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.abbr.dialogAbbrTextNotEmpty)
          },
          {
            commit: function (element) {
              element.setAttribute('title', this.getValue())
            },
            id: 'title',
            label: editor.lang.abbr.dialogAbbrTitle,
            requiredContent: 'abbr[title]',
            setup: function (element) {
              this.setValue(element.getAttribute('title'))
            },
            type: 'text',
            validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.abbr.dialogAbbrTitleNotEmpty)
          }
        ]
      }
    ],
    minHeight: 200,
    minWidth: 400,
    onOk: function () {
      let abbr = this.element

      this.commitContent(abbr)

      if (this.insertMode) {
        editor.insertElement(abbr)
      }
    },
    onShow: function () {
      let element = editor.getSelection().getStartElement()

      if (element) {
        element = element.getAscendant('abbr', true)
      }

      if (!element || element.getName() !== 'abbr') {
        element = editor.document.createElement('abbr')

        this.insertMode = true
      }
      else {
        this.insertMode = false
      }

      this.element = element

      if (!this.insertMode) {
        this.setupContent(element)
      }
    },
    resizable: CKEDITOR.DIALOG_RESIZE_NONE,
    title: editor.lang.abbr.dialogTitle
  }
});
