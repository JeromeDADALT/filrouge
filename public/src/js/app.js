// menu responsive

//au clic sur le menu burger ou sur le bandeau,
// je sélectionne dans le body la classe display_menuResp pour faire apparaitre ou disparaitre le menu
$("#burger").click(function(){
    $("body").toggleClass("display_menuResp");
});

$("#overlay").click(function(){
    $("body").toggleClass("display_menuResp");
});
