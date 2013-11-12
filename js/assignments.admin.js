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
        $("#assignment-add").hide();
        $("#assignment-edit").attr('disabled', true);
        $("#assignment-name-search").attr('disabled', true);
        $("#edit-assignment > h4").text(title);
        $("#edit-assignment").show();
    }
    
    function defaultView() {
        $("#edit-assignment").hide();
        $("#assignment-add").show();
        $("#assignment-edit").attr('disabled', false);
        $("#assignment-name-search").attr('disabled', false);
        if (!$("#assignment-id-input").val()) {
            //$("#assignment-edit").show();
            //$("#assignment-del").show();
        }
    }
    
    $("#assignment-add").click( function() {
        editGroup("Add Assignment");
        $("#assignment-edit").hide();
        $("#assignment-del").hide();
        $("#assignment-description-input").val("");
        $("#edit-assignment input").val('');
        return false;
    });
    
    $("#assignment-cancel").click( function() {
        defaultView();
        return false;
    });
    
    $("#assignment-edit").click( function() {
        editGroup("Edit Assignment");
        return false;
    });
    
    $("#assignment-name-search").autocomplete({
        result_box: "#assignment-search-results",
        keydown_func: function() {
            $("#assignment-edit").hide();
            $("#assignment-del").hide();
        },
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            var description = $(obj).data('description');
            var parent = $(obj).data('parent');
            var parentID = $(obj).data('parentID');
            var def = $(obj).data('default');
            var defaultID = $(obj).data('defaultID');
            var command = $(obj).data('command');
            var commandID = $(obj).data('commandID');
            
            $("#assignment-name-search").val(name);
            $("#assignment-name-input").val(name);
            $("#assignment-id-search").val(id);
            $("#assignment-id-input").val(id);
            $("#assignment-parent-input").val(parent);
            $("#assignment-parent-id-input").val(parentID);
            $("#assignment-default-input").val(def);
            $("#assignment-default-id-input").val(defaultID);
            $("#assignment-command-input").val(command);
            $("#assignment-command-id-input").val(commandID);
            $("#assignment-description-input").html(description);
            
            $("#assignment-edit").show();
            $("#assignment-del").show();
        },
        url: 'ajax.admin.php',
        page: 'assignment',
        params: { type: '' }
    });
    
    $("#assignment-parent-input").autocomplete({
        result_box: "#parent-search-results",
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            $("#assignment-parent-input").val(name);
            $("#assignment-parent-id-input").val(id);
        },
        url: 'ajax.admin.php',
        page: 'assignment',
        params: {
            id: function() {
                return $("#assignment-id-input").val();
            },
            type: ''
        }
    });
    
    $("#assignment-default-input").autocomplete({
        result_box: "#default-search-results",
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            $("#assignment-default-input").val(name);
            $("#assignment-default-id-input").val(id);
        },
        url: 'ajax.admin.php',
        page: 'assignment',
        params: { type: 'default' }
    });
    
    $("#assignment-command-input").autocomplete({
        result_box: "#command-search-results",
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            $("#assignment-command-input").val(name);
            $("#assignment-command-id-input").val(id);
        },
        url: 'ajax.admin.php',
        page: 'assignment',
        params: { type: 'hiearchy' }
    });
    
    $("#assignment-save").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'assignment',
                action: 'save',
                id: $("#assignment-id-input").val(),
                name: $("#assignment-name-input").val(),
                description: $("#assignment-description-input").val(),
                parent: $("#assignment-parent-id-input").val(),
                'default': $("#assignment-default-id-input").val(),
                'command': $("#assignment-command-id-input").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif("Failed to save!", "error");
                    return;
                }
                if (data.edit) {
                    displayNotif("Edited "+data.name, '');
                } else {
                    displayNotif("Added new assignment: "+data.name, '');
                }
                defaultView();
            },
            error: ajaxError()
        });
        return false;
    });
    
    $("#assignment-del").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'assignment',
                action: 'del',
                id: $("#assignment-id-search").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif("Failed to delete!", "error");
                    return;
                }
                var name = $("#assignment-name-search").val();
                displayNotif("Deleted "+name, '');
                defaultView();
                $("#assignment-edit").hide();
                $("#assignment-del").hide();
                $("#assignment-name-search").val('');
                $("#assignment-search-results").html("");
            },
            error: ajaxError()
        })
        return false;
    });
});