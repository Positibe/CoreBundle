if (app === undefined) {
    var app = {};
}

app.initChosen = function (elem) {
    elem = elem ? elem : document;
    var config = {
        '.chosen-select': {
            no_results_text: 'No se encontraron resultados para:',
            placeholder_text_multiple: '-- Seleccione una opción --',
            placeholder_text_single: '-- Seleccione alguna opción --'
        },
        '.chosen-select-deselect': {allow_single_deselect: true},
        '.chosen-select-no-single': {disable_search_threshold: 10},
        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
        '.chosen-select-width': {width: "95%"}
    };
    
    for (var selector in config) {
        $(elem).find(selector).chosen(config[selector]);
    }
};