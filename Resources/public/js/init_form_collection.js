if (app === undefined) {
    var app = {};
}

app.initFormCollection = function (elem) {
    $(elem ? elem : document).find('.form-collections-add_button').on('click', function (e) {
        e.preventDefault();
        var ul = $('#' + $(this).attr('data-target'));
        var newWidget = ul.attr('data-prototype');
        newWidget = $(newWidget.replace(/__name__/g, ul.attr('data-count')));

        var removeButton = $(newWidget).find('.form-collection-remove-button');

        if (removeButton.length === 0) {
            newWidget.append($('<div class="form-group form-md-line-input"> <div class="col-xs-2 text-right"><a class="btn btn-sm btn-danger ' +
                'form-collection-remove-button" ' +
                'data-target="' + ul.attr('data-base_id') + '_' + ul.attr('data-count') + '"><i class="fa fa-trash"></i> Eliminar</a></div>')
            );
            removeButton = $(newWidget).find('.form-collection-remove-button');
        }

        removeButton.on('click', function (e) {
            e.preventDefault();
            newWidget.remove();
        });

        newWidget.appendTo(ul);
        ul.attr('data-count', ul.attr('data-count') + 1);
        $(this).trigger('elem_added');
    });

    $(elem ? elem : document).find('.form-collection-remove-button').click(function (e) {
        e.preventDefault();

        var elem = $('#' + $(this).attr('data-target'));
        elem.remove();
        $(this).trigger('elem_removed');
    });
};