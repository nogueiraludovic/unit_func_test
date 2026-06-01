define(['jquery'], function($) {
    var ServiceContact = {
        initialize: function() {
            if (Vaud.settings.department - 0 > 0) {
                this.onDepartmentChange(Vaud.settings.department, false)
            }

            this.getControl('department').change(function(e) {
              ServiceContact.onDepartmentChange(e.target.value, true)
            })

            if (!this.getValue('serviceContact')) {
              this.hideServiceContact()
            }
        },
        onDepartmentChange: function(departmentIdentifier, reset) {
            if (reset) {
                this.resetAndHideServiceContact()
            }

            if (departmentIdentifier) {
                this.buildServiceContactOptions(this.findServiceContactByDepartment(departmentIdentifier))
                this.getContainer('serviceContact').show()
            }
        },
        findServiceContactByDepartment: function(departmentIdentifier) {
            return Vaud.serviceContactGroupedByDepartment[departmentIdentifier]
                ? Vaud.serviceContactGroupedByDepartment[departmentIdentifier]
                : []
        },
        buildServiceContactOptions: function(serviceContacts) {
            var currentServiceContact = this.getValue('serviceContact') || Vaud.settings.serviceContact
            var dropDownMenu = this.getControl('serviceContact')
                .empty()
                .append('<option value="">Veuillez choisir un contact</option>')

            for (var index = 0; index < serviceContacts.length; ++index) {
                var selected = currentServiceContact - 0 === serviceContacts[index].uid - 0 ? 'selected' : ''

                dropDownMenu.append(
                    '<option value="' + serviceContacts[index].uid + '" ' + selected + '>' + serviceContacts[index].label + '</option>'
                )
            }
        },
        resetAndHideServiceContact: function() {
            this.getControl('serviceContact').val('')
            this.hideServiceContact()

            Vaud.settings.serviceContact = ''
        },
        getContainer: function(containerName) {
            return $('#container-' + containerName)
        },
        getControl: function(controlName) {
            return $('#control-' + controlName)
        },
        getValue: function(controlName) {
          return this.getControl(controlName).val()
        },
        hideServiceContact: function() {
          this.getContainer('serviceContact').hide()
        }
    };

    return ServiceContact
})
