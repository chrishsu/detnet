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
        $("#member-add").hide();
        $("#member-edit").attr('disabled', true);
        $("#member-name-search").attr('disabled', true);
        $("#edit-member > h4").text(title);
        $("#edit-member").show();
    }
    
    function defaultView() {
        $("#edit-member").hide();
        $("#member-add").show();
        $("#member-edit").attr('disabled', false);
        $("#member-name-search").attr('disabled', false);
        if (!$("#member-id-input").val()) {
            //$("#member-edit").show();
            //$("#member-del").show();
        }
    }
    
    $("#member-cancel").click( function() {
        defaultView();
        return false;
    });
    
    $("#member-edit").click( function() {
        editGroup("Edit Assignment");
        return false;
    });
    
    $("#member-name-search").autocomplete({
        result_box: "#member-search-results",
        keydown_func: function() {
            $("#member-edit").hide();
            $("#member-del").hide();
        },
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            var assignment = $(obj).data('assignment');
            var assignmentID = $(obj).data('assignmentID');
            var hash = $(obj).data('assignmentHash');
            var def = $(obj).data('default');
            var defaultID = $(obj).data('defaultID');
            
            $("#member-name-search").val(name);
            $("#member-name-input").val(name);
            $("#member-id-search").val(id);
            $("#member-id-input").val(id);
            $("#member-assignment-input").val(assignment);
            $("#member-assignment-id-input").val(assignmentID);
            $("#member-assignment-hash-input").val(hash);
            $("#member-default-input").val(def);
            $("#member-default-id-input").val(defaultID);
            
            $("#member-edit").show();
            $("#member-del").show();
        },
        url: 'ajax.admin.php',
        page: 'member',
        params: { type: '' }
    });
    
    $("#member-assignment-input").autocomplete({
        result_box: "#assignment-search-results",
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            $("#member-assignment-input").val(name);
            $("#member-assignment-id-input").val(id);
        },
        url: 'ajax.admin.php',
        page: 'member',
        params: { type: 'assignment' }
    });
    
    $("#member-default-input").autocomplete({
        result_box: "#default-search-results",
        click_func: function(obj) {
            var id = $(obj).data('id');
            var name = $(obj).data('name');
            $("#member-default-input").val(name);
            $("#member-default-id-input").val(id);
        },
        url: 'ajax.admin.php',
        page: 'member',
        params: { type: 'default' }
    });
    
    $("#member-save").click( function() {
        $.ajax({
            url: 'ajax.admin.php',
            type: 'post',
            data: {
                page: 'member',
                action: 'save',
                id: $("#member-id-input").val(),
                name: $("#member-name-input").val(),
                assignment: $("#member-assignment-id-input").val(),
                'assignmenthash': $("#member-assignment-hash-input").val(),
                'default': $("#member-default-id-input").val()
            },
            success: function(data) {
                if (data.error) {
                    displayNotif(data.message, "error");
                    return;
                }
                if (data.edit) {
                    displayNotif("Edited "+data.name, '');
                    if (data.hash) {
                        $("#member-assignment-hash-input").val(data.hash);
                    }
                } else {
                    displayNotif("Added new member: "+data.name, '');
                }
                defaultView();
            },
            error: ajaxError()
        });
        return false;
    });
    
});