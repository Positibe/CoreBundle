function js_initializer() {
    return {
        functions: [],
        addFunction: function (order, callable) {
            functions[functions.length] = {order: order, callable: callable};
        },
        init: function () {
            functions.sort(function (a, b) {
                return a.order > b.order;
            });

            for (var i = 0; i < functions.length; i++) {
                functions[i].callable();
            }
        }
    }
}
