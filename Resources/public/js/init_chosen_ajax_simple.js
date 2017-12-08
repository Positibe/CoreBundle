if (app === undefined) {
    var app = {};
}
app.initChosenAjax = function (elem, url) {
    elem = elem ? elem : document;

    $(elem).find('.chosen-select-ajax').each(function () {
        $.ajax({
            url: $(this).data('api') !== undefined ? $(this).data('api') : url,
            data: {
                entity: $(this).data('entity'),
                fieldText: $(this).data('field_text'),
                depend_value: $(this).val()
            },
            elem: this,
            success: function (data) {
                var length = data.length;
                var elem = $(this.elem);
                var selected = [];
                elem.find(':selected').each(function () {
                    selected[selected.length] = $(this).val();
                });
                var options = '';
                for (var i = 0; i < length; i++) {
                    //console.log(data[i]);
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
            }
        });


    });
};

