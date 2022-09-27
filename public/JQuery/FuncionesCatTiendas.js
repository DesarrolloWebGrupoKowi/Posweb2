$('#filtroEstado').change(function(event) {

    $.get('Ciudades/' + event.target.value + "", function(response, state) {
        $('#filtroCiudad').empty();
        if (response <= 0) {
            $('#filtroCiudad').append("<option value='0'>No Hay Ciudad Registrada</option>");
        } else {
            for (i = 0; i < response.length; i++) {
                $('#filtroCiudad').append("<option value='" + response[i].IdCiudad + "'>" + response[i].NomCiudad + "</option>");
            }
            $('#filtroCiudad').append("<option value='0'>TODAS</option>");
        }
    });
});


$(document).ready(function() {

    $('#filtroCiudad').change(function(event) {
        var e = $("#filtroCiudad option:selected").val();
        console.log(e); //Imprimir el id de la ciudad
    });
    $.get('Ciudades/' + $("#filtroEstado option:selected").val() + "", function(response, state) {
        var e = $("#filtroCiudad option:selected").val();
        $('#filtroCiudad').empty();
        if (response <= 0) {
            $('#filtroCiudad').append("<option value='0'>No Hay Ciudad Registrada</option>");
        } else {
            for (i = 0; i < response.length; i++) {
                $('#filtroCiudad').append($('<option>', { 'value': response[i].IdCiudad, 'text': response[i].NomCiudad, 'selected': response[i].IdCiudad == e }));
                //$('#filtroCiudad').append("<option {!! '"+response[i].IdCiudad+"' == '2' ? 'selected' : '' !!} value='"+response[i].IdCiudad+"'>"+response[i].NomCiudad+"</option>");
            }
            $('#filtroCiudad').append("<option value='0'>TODAS</option>");
        }
    });
});