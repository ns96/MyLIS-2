function myModalMessage(title, text, destination) {
    var div = $('<div>').html(text).dialog({
        title: title,
        modal: true,
        close: function() {
            $(this).dialog('destroy').remove();
	    window.location = destination;
        },
        buttons: [{
            text: "Ok",
            click: function() {
                $(this).dialog("close");
            }}]
    })
};

