$(document).ready(function(){
    $("#calculate").on("click", function(event){

        let source   = $('#calc-form-source').val();
        let to       = $('#calc-form-to').val();
        let weight   = $('#calc-form-weight').val();
        let delivery = $('#calc-form-delivery-type').val();

        $.ajax({
            url:        '/calc-information/ajax',
            contentType: 'application/json; charset=utf-8',
            data: { source: source, to: to, weight: weight, delivery: delivery, },
            type:       'GET',
            dataType:   'json',
            async:      true,

            success: function(data, status) {
                var e = $('<tr><th>Стоимость</th><th>Дата доставки</th><th>Ошибка</th></tr>');
                $('#result').html('');
                $('#result').append(e);

                for(i = 0; i < data.length; i++) {
                    result = data[i];
                    var e = $('<tr></td><td id="result-price"></td><td id="result-date"></td><td id="result-error"></tr>');

                    $('#result-price', e).html(result['price']);
                    $('#result-date', e).html(result['date']);
                    $('#result-error', e).html(result['error']);
                    $('#result').append(e);
                }
            },
            error : function(xhr, textStatus, errorThrown) {
                //alert('Ajax request failed.');
            }
        });
    });
});