CKEDITOR.dialog.add('quoteDialog', function (editor) {
  return {
    contents: [
      {
        elements: [
          {
            commit: function (element) {
              element.setHtml('')

              let blockquote = editor.document.createElement('blockquote')
              let values = this.getValue().split(/\r\n|\r|\n/g)

              for (let i = 0; i < values.length; ++i) {
                let paragraphs = editor.document.createElement('p')

                paragraphs.setText(values[i])

                blockquote.append(paragraphs)
              }

              element.append(blockquote)
            },
            id: 'text',
            label: editor.lang.quote.dialogQuoteText,
            setup: function (element) {
              let text = element.find('blockquote p')
              let count = text.count()

              if (count === 0) {
                return false
              }

              let quote = text.getItem(0).getText()

              for (let i = 1; i < text.count(); ++i) {
                quote += '\n' + text.getItem(i).getText()
              }

              this.setValue(quote)
            },
            type: 'textarea',
            validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.quote.dialogQuoteTextNotEmpty),
          },
          {
            commit: function (element) {
              let value = this.getValue()

              if (value === '') {
                return false
              }

              let paragraph = editor.document.createElement('p')
              paragraph.setAttribute('class', 'blockquote-footer')
              paragraph.setText(value)

              let author = editor.document.createElement('figcaption')
              author.append(paragraph)

              element.append(author)
            },
            id: 'author',
            label: editor.lang.quote.dialogQuoteAuthor,
            setup: function (element) {
              let author = element.findOne('figcaption p')

              if (author === null) {
                return false
              }

              this.setValue(author.getText())
            },
            type: 'text'
          }
        ],
        id: 'tab-basic'
      }
    ],
    minHeight: 200,
    minWidth: 400,
    onOk: function () {
      let figure = this.element
      figure.setAttribute('class', 'vd-blockquote')

      this.commitContent(figure)

      if (this.insertMode) {
        editor.insertElement(figure)
      }
    },
    onShow: function () {
      let element = editor.getSelection().getStartElement()

      if (element) {
        element = element.getAscendant('figure', true)
      }

      if (!element || element.getName() !== 'figure') {
        element = editor.document.createElement('figure')

        this.insertMode = true
      } else {
        this.insertMode = false
      }

      this.element = element

      if (!this.insertMode) {
        this.setupContent(element)
      }
    },
    resizable: CKEDITOR.DIALOG_RESIZE_NONE,
    title: editor.lang.quote.dialogTitle
  }
});
