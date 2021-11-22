$(document).on('ready', function() {

$('.fbt-checkbox input').on('click', function (){

      var sum = 0;

      $('.fbt-checkbox input:checkbox:checked').each(function() {
            sum += Number($(this).attr('data-price'));
      });


      $('.fbt-total-price').text('Total price: ' + sum + '€')
      
      })


});
