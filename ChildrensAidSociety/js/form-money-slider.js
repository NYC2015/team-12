$('#range').on("change", function() {
    $('.output').val("$" + this.value +"00" )
    }).trigger("change");