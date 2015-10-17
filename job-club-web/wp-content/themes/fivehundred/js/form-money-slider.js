$('#range').on("change", function() {
    $('.output').val("$" + this.value +"00" )
    }).trigger("change");

$('#date_range').on("change", function() {
    $('.date_range').val(this.value +" month(s)" )
    }).trigger("change");