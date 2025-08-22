$( document ).ready(function() {
    var w = window.innerWidth;

    if(w > 767){
        $('#menu-jk').scrollToFixed();
    }else{
        $('#menu-jk').scrollToFixed();
    }

     $("#owl-demo").owlCarousel();

})

function fire() {
    Swal.fire({
        title: "Thankyou!",
        text: "Query Sent Successfully.",
        icon: "success"
      });
}