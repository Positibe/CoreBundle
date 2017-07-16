if (app === undefined) {
    var app = {};
}

app.addNewItem = function (data, urlCreateEnum) {
    var select = $('#' + data);
    var enumText = $('#' + data + '_chosen .chosen-search input').val();
    var enumType = select.data('enum_type');

    $.post(urlCreateEnum, {'enum_type': enumType, 'enum_text': enumText}, function (data) {
        select.chosen('destroy');
        select.append('<option selected value="' + data.key + '">' + data.value + '</option>');

        app.createChosenEnum(urlCreateEnum, select.parents('.form-group'));
    }, 'json')
};

app.createChosenEnum = function (urlCreateEnum, target) {
    target = target ? target : document;
    var select = $(target).find('.chosen-select-enum');
    select.each(function () {
        $(this).chosen({
            no_results_text: '<a onclick="app.addNewItem(\'' + $(this).attr('id') + '\', \'' + urlCreateEnum + '\')">Agregar nuevo: </a>',
            search_contains: true,
            placeholder_text_multiple: '-- Seleccione una opción --',
            placeholder_text_single: '-- Seleccione alguna opción --'

        })
    })
    ;

};