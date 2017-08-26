if (app === undefined) {
    var app = {};
}

app.clickOnObject = function (id) {
    $('#' + id).click();
};

app.loadModalChosenAdd = function (modalId, htmlFormUrl, apiCreateUrl) {
    $.get(htmlFormUrl, function (data) {
        var modal = $(modalId).find('.modal-content');
        if (modal.length) {
            modal.empty();
            modal.append(data);

            $(document).trigger('init_function', modal);

            modal.find('form').on('submit', function (event) {
                event.preventDefault();
                $.post(apiCreateUrl, $(this).serialize(), function (data) {

                    var select = $(modal.data('target')).parents('.form-group').find('.chosen-select-add');
                    select.chosen('destroy');
                    select.append('<option selected value="' + data.key + '">' + data.value + '</option>');

                    app.createChosenModal(modal.data('button'), select);

                    modal.parents('.modal').modal("hide");
                    app.loadModalChosenAdd(modalId, htmlFormUrl, apiCreateUrl);
                }, 'json')

            })
        }
    });
};


app.createChosenModal = function () {
    $('.chosen-select-add').each(function () {
        $(this).chosen({
            no_results_text: '<a onclick="app.clickOnObject(\'' + $(this).attr('data-chosen_button') + '\',\'' + $(this).attr('id') + '\')">Agregar nuevo: </a>',
            search_contains: true,
            placeholder_text_multiple: '-- Seleccione una opción --',
            placeholder_text_single: '-- Seleccione alguna opción --',
            allow_single_deselect: true,
            disable_search_threshold: 10

        })
        ;
    });
};