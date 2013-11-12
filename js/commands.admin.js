$( function() {
    
    
    
    function displayNotif(msg, c) {
        var entry = $('<span>', {text: msg, 'class': c});
        $("div.notify").html(entry); //append?
        entry.fadeIn(500);
    }
    
    function ajaxError() {
        displayNotif("Ajax failed :(", "error");
    }
    
    function editGroup(title) {
        $("#command-add").hide();
        $("#command-edit").attr('disabled', true);
        $("#command-name-search").attr('disabled', true);
        $("#edit-command > h4").text(title);
        $("#edit-command").show();
    }
    
    function defaultView() {
        $("#edit-command").hide();
        $("#command-add").show();
        $("#command-edit").attr('disabled', false);
        $("#command-name-search").attr('disabled', false);
        if (!$("#command-id-input").val()) {
            //$("#command-edit").show();
            //$("#command-del").show();
        }
    }
    
    $("#command-add").click( function() {
        editGroup("Add Command");
        $("#command-edit").hide();
        $("#command-del").hide();
        $("#command-name-input").val('');
        $("#command-description-input").html("");
        $("#command-id-input").val('');
        $("#command-parent-input").val('');
        $("#command-parent-id-input").val('');
        return false;
    });
    
    $("#command-cancel").click( function() {
        defaultView();
        return false;
    });
    
    $("#command-edit").click( function() {
        editGroup("Edit Command");
        return false;
    });
    
    function displaySearchResults(msg, results_box) {
        var li = $("<li>").html(msg);
        $(results_box).append(li).show();
    }
    
    var searchTimer = null;
    
    function setSearchTimer(val, results_box) {
        searchTimer = null;
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'command',
                action: 'search',
                query: val,
                id: $("#command-id-input").val()
            },
            success: function(data) {
                $(results_box).html("");
                //console.log(data);
                if (data.error) {
                    displaySearchResults(data.message, results_box);
                    return;
                }
                if (data.results.length == 0) {
                    displaySearchResults("No results.", results_box);
                } else {
                    for (i = 0; i < data.results.length; i++) {
                        var r = data.results[i];
                        var a = $("<a>", { text: r.name, 'href': '#' });
                        a.data(r);
                        displaySearchResults(a, results_box);
                    }
                }
            },
            error: function() {
                $(results_box).html("");
                displaySearchResults("Ajax error :(");
            }
        })
        
    }
    
    var commandFocus = false;
    
    $("#command-name-search").keyup( function(e) {
        // Nothing to search for
        if ($(this).val().replace(/ /g, "").length < 2) {
            $("#command-search-results").hide();
            return;
        }
        
        if (searchTimer) clearTimeout(searchTimer);
        
        searchTimer = setTimeout(setSearchTimer($("#command-name-search").val(), "#command-search-results"), 250);
    }).keydown( function(e) {
        switch (e.keyCode) {
            default:
                $("#command-edit").hide();
                $("#command-del").hide();
                break;
        }
    }).focusin( function() {
        if ($(this).val().replace(/ /g, "").length < 2) {
            return;
        }
        $("#command-search-results").show();
    }).focusout( function() {
        if (!commandFocus) $("#command-search-results").hide();
    });
    
    $("#command-search-results").on({
        click: function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var parent = $(this).data('parent');
            var parentID = $(this).data('parentID');
            
            $("#command-name-search").val(name);
            $("#command-name-input").val(name);
            $("#command-id-search").val(id);
            $("#command-id-input").val(id);
            $("#command-parent-input").val(parent);
            $("#command-parent-id-input").val(parentID);
            $("#command-description-input").html(description);
            $("#command-search-results").hide();
            $("#command-edit").show();
            $("#command-del").show();
            return false;
        },
        mousedown: function() {
            commandFocus = true;
        },
        mouseup: function() {
            commandFocus = false;
        }
    }, 'li a');
    
    var parentFocus = false;
    
    $("#command-parent-input").keyup( function(e) {
        // Nothing to search for
        if ($(this).val().replace(/ /g, "").length < 2) {
            $("#parent-search-results").hide();
            return;
        }
        
        if (searchTimer) clearTimeout(searchTimer);
        
        searchTimer = setTimeout(setSearchTimer($("#command-parent-input").val(), "#parent-search-results"), 250);
    }).keydown( function(e) {
        switch (e.keyCode) {
            default:
                break;
        }
    }).focusin( function() {
        if ($(this).val().replace(/ /g, "").length < 2) {
            return;
        }
        $("#parent-search-results").show();
    }).focusout( function() {
        if (!parentFocus) $("#parent-search-results").hide();
    });
    
    $("#parent-search-results").on({
        click: function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $("#command-parent-input").val(name);
            $("#command-parent-id-input").val(id);
            $("#parent-search-results").hide();
            return false;
        },
        mousedown: function() {
            parentFocus = true;
        },
        mouseup: function() {
            parentFocus = false;
        }
    }, 'li a');
    
    $("#command-save").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'command',
                action: 'save',
                id: $("#command-id-input").val(),
                name: $("#command-name-input").val(),
                description: $("#command-description-input").val(),
                parent: $("#command-parent-id-input").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif("Failed to save!", "error");
                    return;
                }
                if (data.edit) {
                    displayNotif("Edited "+data.name, '');
                } else {
                    displayNotif("Added new command: "+data.name, '');
                }
                defaultView();
            },
            error: ajaxError()
        });
        return false;
    });
    
    $("#command-del").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'command',
                action: 'del',
                id: $("#command-id-search").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif("Failed to delete!", "error");
                    return;
                }
                var name = $("#command-name-search").val();
                displayNotif("Deleted "+name, '');
                defaultView();
                $("#command-edit").hide();
                $("#command-del").hide();
                $("#command-name-search").val('');
                $("#command-search-results").html("");
            },
            error: ajaxError()
        })
        return false;
    });
});