$(".slide-out-arrow").click(function() {
  $( this ).parent("aside").toggleClass('col-md-2').toggleClass("col-md-12");
  $( this ).toggleClass('fa-arrow-circle-o-right').toggleClass("fa-arrow-circle-o-left");

  //$( this ).removeClass('slide-out-arrow').addClass('slide-in-arrow');
});