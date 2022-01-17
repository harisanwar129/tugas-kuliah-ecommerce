!(function (o) {
    "use strict";
    var e = function () {};
    (e.prototype.init = function () {
            o("#default-colorpicker").colorpicker({ format: "hex" }),
            o("#rgba-colorpicker").colorpicker(),
            o("#component-colorpicker").colorpicker({ format: null }),
            o(".clockpicker").clockpicker({ donetext: "Done" })
    }),
        (o.FormPickers = new e()),
        (o.FormPickers.Constructor = e);
})(window.jQuery),
    (function (e) {
        "use strict";
        window.jQuery.FormPickers.init();
    })();
