if (app === undefined) {
    var app = {};
}

app.initFormToggle = function (elem) {
    elem = elem ? elem : document;
    $(elem).find('.form-toggle').parents('.form-group:first, .md-form:first').css('display', 'none');

    /**
     * Formularios toggle.
     *
     * Formulario de mostrar o ocultar un formulario mediante el valor de otro formulario
     * EL formulario conoce el campo que lo debe ocultar o mostrar
     */

    $(elem).find('.form-toggle-if').each(function () {
        var toggleIf = $(this);
        //Se observa el cambio sobre el campo definido en el atributo 'data-toggle_if_for' del campo.
        $('#' + toggleIf.data('toggle_if_for')).on('change', function (event) {

            //Si es tipo tipo selección simple es el valor directo, sino si es de tipo radio
            if (this.type === 'select-one') {
                var value = $(this).val();
            }
            else if (this.type === 'checkbox') {
                var value = this.checked;
            }
            else {
                var value = $(this).find('input:checked').val();
            }

            //Si el valor es el definido en el atributo 'data-toggle_if_value' del campo se muestra, sino se oculta
            toggleIf.each(function () {
                var eachToggle = $(this);
                //No importa si es número o letra
                if (value == eachToggle.data('toggle_if_value') || eachToggle.data('toggle_if_multiple_values') !== undefined && eachToggle.data('toggle_if_multiple_values').split(',').indexOf(value) !== -1 || eachToggle.data('toggle_if_any_value')) {
                    eachToggle.parents('.form-group:first, .md-form:first').css('display', 'block');
                }
                else {
                    eachToggle.parents('.form-group:first, .md-form:first').css('display', 'none');
                }
            });

        }).trigger('change');
    });

};