(function($){

$.fn.autocomplete = function(opts) {
    
var defaults = {
    result_box: '',
    success_func: function(data) { },
    error_func: function() { },
    keydown_func: function() { },
    click_func: function(obj) { },
    params: { },
    page: '',
    url: '',
    action: 'search'
};

var options = $.extend(defaults, opts);
    
return this.each(function() {

//GLOBAL
var search_box = this;
var search_timer = null;
var search_focus = false;

function display_results(msg) {
    var li = $("<li>").html(msg);
    $(options.result_box).append(li).show();
}

function set_search_timer(q) {
    search_timer = null;
    $.ajax({
        url: options.url,
        type: 'post',
        data: {
            action: options.action,
            page: options.page,
            query: q,
            params: options.params
        },
        success: function(data) {
            $(options.result_box).html("");
            if (data.error) {
                display_results("Error: " + data.message);
                return;
            }
            
            if (data.results.length == 0) {
                display_results("No results");
                return;
            }
            
            for (i = 0; i < data.results.length; i++) {
                var r = data.results[i];
                var a = $("<a>", {text: r.name, 'href': '#' });
                a.data(r);
                display_results(a);
            }
            options.success_func();
        },
        error: function() {
            $(options.result_box).html("");
            display_results("Ajax Error");
            options.error_func();
        }
    });
}

function check_length(val) {
    return val.replace(/ /g, "").length < 2;
}

$(search_box).keyup( function(e) {
    if (check_length($(this).val())) {
        $(options.result_box).hide();
        return;
    }
    
    if (search_timer) clearTimeout(search_timer);
    
    var q = $(this).val();
    searchTimer = setTimeout(set_search_timer(q), 250);
    
}).keydown( function(e) {
        switch (e.keyCode) {
            default:
                options.keydown_func();
                break;
        }
    }).focusin( function() {
    if (check_length($(this).val())) {
        $(options.result_box).hide();
        return;
    }
    $(options.result_box).show();
    
}).focusout( function() {
    if (!search_focus) $(options.result_box).hide();
}); //END: search_box

$(options.result_box).on({
    click: function() {
        options.click_func(this);
        $(this).hide();
        return false;
    },
    mousedown: function() {
        search_focus = true;
    },
    mouseup: function() {
        search_focus = false;
    }
}, "li a"); //End: result_box

}); //END: return

}; //END: plugin declaration

})(jQuery);