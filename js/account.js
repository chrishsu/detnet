$( function() {
    
    function getAccountBox(obj) {
        var box; 
    }
    
    $(".edit-account").click( function() {
        var parent = $(this).parent();
        var box = parent.parents(".account-box");
        var form = box.children("form");
        $("form.active").closest(".account-box").children(".account-box-header").children("span").children(".cancel-account").click();
        form.show().addClass("active");
        form.children("input").attr('disabled', false);
        $(this).hide();
        parent.children(".save-account").show();
        parent.children(".cancel-account").show();
        return false;
    });
    
    $(".save-account").click( function() {
        var parent = $(this).parent();
        var box = parent.parents(".account-box");
        var form = box.children("form");
        form.submit();
        return false;
    });
    
    $(".cancel-account").click( function() {
        var parent = $(this).parent();
        parent.children(".save-account").hide();
        $(this).hide();
        parent.children(".edit-account").show();
        $("form.active").hide();
        return false;
    })
    
});