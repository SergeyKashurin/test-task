$(document).ready(function(){
    $("#calculate").on("click", function(event){

        let source = $('#calc-form-source').val();
        let to     = $('#calc-form-to').val();
        let size   = $('#calc-form-size').val();

        $.ajax({
            url:        '/calc-information/ajax',
            contentType: 'application/json; charset=utf-8',
            data: { source: source, to: to, size: size },
            type:       'GET',
            dataType:   'json',
            async:      true,

            success: function(data, status) {
                var e = $('<tr><th>Откуда</th><th>Куда</th><th>Размер</th></tr>');
                $('#result').html('');
                $('#result').append(e);

                for(i = 0; i < data.length; i++) {
                    result = data[i];
                    var e = $('<tr><td id="result-source"></td><td id="result-to"></td><td id="result-size"></td></tr>');

                    $('#result-source', e).html(result['source']);
                    $('#result-to', e).html(result['to']);
                    $('#result-size', e).html(result['size']);
                    $('#result').append(e);
                }
            },
            error : function(xhr, textStatus, errorThrown) {
                alert('Ajax request failed.');
            }
        });
    });
});