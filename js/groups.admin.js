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
        $("#group-add").hide();
        $("#group-name-search").attr('disabled', true);
        $("#edit-group > h4").text(title);
        $("#edit-group").show();
    }
    
    function defaultView() {
        $("#edit-group").hide();
        $("#group-add").show();
        $("#group-edit").attr('disabled', false);
        $("#group-name-search").attr('disabled', false);
        if (!$("#group-id-input").val()) {
            //$("#group-edit").show();
            //$("#group-del").show();
        }
    }
    
    $("#group-add").click( function() {
        editGroup("Add Group");
        $("#group-edit").hide();
        $("#group-del").hide();
        $("#group-name-input").val('');
        $("#group-description-input").html('');
        $("#group-id-input").val('');
        return false;
    });
    
    $("#group-cancel").click( function() {
        defaultView();
        return false;
    });
    
    $("#group-edit").click( function() {
        editGroup("Edit Group");
        return false;
    });
    
    function displaySearchResults(msg) {
        var li = $("<li>").html(msg);
        $("#group-search-results").append(li).show();
    }
    
    var searchTimer = null;
    
    function setSearchTimer(val) {
        searchTimer = null;
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'group',
                action: 'search',
                query: val
            },
            success: function(data) {
                $("#group-search-results").html("");
                //console.log(data);
                if (data.error) {
                    displaySearchResults(data.message);
                    return;
                }
                if (data.results.length == 0) {
                    displaySearchResults("No results.");
                } else {
                    for (i = 0; i < data.results.length; i++) {
                        var r = data.results[i];
                        var a = $("<a>", { text: r.name, 'href': '#' });
                        a.data('id', r.id);
                        a.data('name', r.name);
                        a.data('description', r.description);
                        displaySearchResults(a);
                    }
                }
            },
            error: function() {
                $("#group-search-results").html("");
                displaySearchResults("Ajax error :(");
            }
        })
        
    }
    
    var groupFocus = false;
    
    $("#group-name-search").keyup( function(e) {
        // Nothing to search for
        if ($(this).val().replace(/ /g, "").length < 2) {
            $("#group-search-results").hide();
            return;
        }
        
        if (searchTimer) clearTimeout(searchTimer);
        
        searchTimer = setTimeout(setSearchTimer($("#group-name-search").val()), 250);
    }).keydown( function(e) {
        switch (e.keyCode) {
            default:
                $("#group-edit").hide();
                $("#group-del").hide();
                break;
        }
    }).focusin( function() {
        if ($(this).val().replace(/ /g, "").length < 2) {
            return;
        }
        $("#group-search-results").show();
    }).focusout( function() {
        if (!groupFocus) $("#group-search-results").hide();
    });
    
    $("#group-search-results").on({
        click: function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            $("#group-name-search").val(name);
            $("#group-name-input").val(name);
            $("#group-id-search").val(id);
            $("#group-id-input").val(id);
            $("#group-description-input").html(description);
            $("#group-search-results").hide();
            $("#group-edit").show();
            $("#group-del").show();
            return false;
        },
        mousedown: function() {
            groupFocus = true;
        },
        mouseup: function() {
            groupFocus = false;
        }
    }, 'li a');
    
    $("#group-save").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'group',
                action: 'save',
                id: $("#group-id-input").val(),
                name: $("#group-name-input").val(),
                description: $("#group-description-input").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif("Failed to save!", "error");
                    return;
                }
                if (data.edit) {
                    displayNotif("Edited "+data.name, '');
                } else {
                    displayNotif("Added new group: "+data.name, '');
                }
                defaultView();
            },
            error: ajaxError()
        });
        return false;
    });
    
    $("#group-del").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'group',
                action: 'del',
                id: $("#group-id-search").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif("Failed to delete!", "error");
                    return;
                }
                var name = $("#group-name-search").val();
                displayNotif("Deleted "+name, '');
                defaultView();
                $("#group-edit").hide();
                $("#group-del").hide();
                $("#group-name-search").val('');
                $("#group-search-results").html("");
            },
            error: ajaxError()
        })
        return false;
    });
});