
jQuery(document).ready(function ($) {

    // If element exists
    jQuery.fn.isExists = function () {
        return this.length;
    };

    // If value exists
    jQuery.fn.isValueExists = function () {
        if (!this.isExists()) {
            return false;
        }
        return this.val().length;
    };

    // Add/Remove error css-class
    jQuery.fn.isEmpty = function () {

        this.removeClass('digitsol-error');
        this.siblings('.error-message').remove();

        if (!this.isValueExists()) {
            this.after('<span class="error-message"> * Required</span>');
            this.addClass('digitsol-error');
            return true;
        }
        return false;
    };

    class O2M_Importer {
        constructor() {
            this.form = $('#o2m-importer');
            this.submitBtn = $('#o2m-run-importer');
            this.statusHolder = $('.o2m-status');
            this.submitBtnHtml = this.submitBtn.val();
            this.fileTableName = this.form.find('input[name=table_name]');
            this.fileField = this.form.find('input[name=json_file_url]');
            this.events();
        }

        events() {
            this.submitBtn.on("click", this.validateSubmit.bind(this));
        }

        // Submit Validate
        validateSubmit(e) {
            e.preventDefault();
            this.form = $(e.currentTarget).closest('form');
            let $form = this.form;
            this.submitBtn = $(e.currentTarget);
            var validated = false;

            var required_fields = {
                table_name: this.fileTableName,
                file_url: this.fileField,
            };
            $.each(required_fields, function (key, value) {
                if (this.isEmpty()) {
                    validated = false;
                    return false;
                }
                validated = true;

            });

            if (validated) {
                this.submitData();
            }
        }

        // Send Data
        submitData() {

            var required_data = {};

            var self = this;

            required_data = {
                self: this,
                type: 'POST',
                dataType: 'json',
                url: o2m_ajax_object.ajax_url,
                action: 'o2m_importer',
                data: self.form.serialize(),
                file_url: this.fileField.val(),
                table_name: this.fileTableName.val(),
            };

            $.ajax({
                type: required_data.type,
                dataType: required_data.dataType,
                url: required_data.url,
                data: {
                    action: required_data.action,
                    form: required_data.data,
                    file_url: required_data.file_url,
                    table_name: required_data.table_name
                },
                beforeSend: function () {
                    self.fileField.prop('disabled', true);
                    self.fileTableName.prop('disabled', true);
                    self.submitBtn.prop("disabled", true);
                    self.statusHolder.addClass('notice notice-info');
                    self.statusHolder.html('Importing...');
                },
                success: function (response) {
                    if (response.success) {
                        if (response.redirect) {
                            console.log('Reloading page...');
                        }
                        self.submitBtn.prop("disabled", false);
                        self.fileField.prop('disabled', false);
                        self.fileTableName.prop('disabled', false);
                        self.submitBtn.val(self.submitBtnHtml);
                        self.statusHolder.removeClass('notice-info');
                        self.statusHolder.addClass('notice-success');
                        self.statusHolder.html('Imported Successfully!');
                        self.fileField.val('');
                        self.fileTableName.val('');
                    } else {
                        self.submitBtn.prop("disabled", false);
                        self.submitBtn.val(self.submitBtnHtml);
                    }
                },
                error: function () {
                    self.statusHolder.removeClass('notice-info notice-success');
                    self.statusHolder.addClass('notice-error');
                    self.statusHolder.html('Something went wrong. Try after page reload.');
                    self.submitBtn.prop("disabled", true);
                    self.submitBtn.val(self.submitBtnHtml);
                }
            })
        }
    }
    new O2M_Importer();
})
