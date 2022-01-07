
$(document).ready(function () {

    /*$(document).ready(function(){
        var target_id = $('.customInput').data("target");
        var product_id = $('.customInput').data("product");
        $.ajax({
            type: 'GET',
            url: ajax_url, // not sure about this part
            data: {
                ajax: true,
                datatype: 'json',
                action: 'getData',
                target: target_id,
                product: product_id
            },
            success: function (data) {
                var result = (JSON.parse(data)).result;//(JSON.parse(data)).result[0].id_chained;
                if(result != '')
                    document.getElementById(result[0].id_chained).checked = true;
            }
        });
    })*/

    $(document).on('click', '.customInput', function () {
        var target_id = $(this).data("target");
        var product_id = $(this).data("product");
        var isChecked = 0;
        if ($(this).is(":checked")) {
            isChecked = 1;
        } else {
            isChecked = 0;
        }
        $.ajax({
            type: 'POST',
            url: ajax_url, // not sure about this part
            data: {
                ajax: true,
                action: 'postData',
                target: target_id,
                product: product_id,
                isChecked: isChecked
            },
            success: function (data) {
                // something magical
            }
        });
    })
});

