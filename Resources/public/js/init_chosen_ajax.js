if (app === undefined) {
    var app = {};
}
app.initChosenAjax = function (elem, url) {
    elem = elem ? elem : document;

    $(elem).find('.chosen-select-ajax').each(function () {
        var select = $(this);
        select.on('chosen:ready', function () {
            if (select.attr('multiple')) {
                app.loadEntityAjax(select, url, null, select.data('depend_field'), select.data('depend_value'));
            } else {
                select.parent().find('.chosen-search > input, .chosen-field > input').on('keyup', function () {
                    var value = $(this).val();
                    if (value.length === 1) {
                        app.loadEntityAjax(select, url, value, select.data('depend_field'), select.data('depend_value'));
                    }
                });
            }
        });
        select.chosen({
            no_results_text: 'No se encontraron resultados para:',
            placeholder_text_multiple: '-- Seleccione una opción --',
            placeholder_text_single: '-- Seleccione alguna opción --'
        });
    });
};

app.loadEntityAjax = function (elem, url, query, depend_field, depend_value) {
    $.ajax({
        url: elem.data('api') !== undefined ? elem.data('api') : url,
        data: {
            entity: elem.data('entity'),
            fieldText: elem.data('field_text'),
            query: query,
            depend_field: depend_field,
            depend_value: depend_value,
            order: elem.data('order')
        },
        elem: elem,
        success: function (data) {
            var length = data.length;
            var elem = $(this.elem);
            var selected = [];
            elem.find(':selected').each(function () {
                selected[selected.length] = $(this).val();
            });
            console.log()
            var options = '';
            for (var i = 0; i < length; i++) {
                options = options + '<option value="' + data[i].id + '" ' + (selected.indexOf((String)(data[i].id)) !== -1 ? ' selected="selected"' : '') + '>' + data[i].text + '</option>';
            }
            elem.empty();
            elem.append('<option></option>' + options);
            elem.chosen({
                no_results_text: 'No se encontraron resultados para:',
                placeholder_text_multiple: '-- Seleccione una opción --',
                placeholder_text_single: '-- Seleccione alguna opción --',
                allow_single_deselect: true,
                disable_search_threshold: 10
            });
            var input = elem.parent().find('.chosen-search > input');
            var currentValue = input.val();
            elem.trigger("chosen:updated");
            input.val(currentValue);
        },
        complete: function () {
            $(this.elem).trigger('select-ajax-loaded', elem);
        }
    });
}
