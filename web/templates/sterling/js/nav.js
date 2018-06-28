function showMobileMenu(){
    $(".nav.navbar-nav.custom-nav.hide-mobile").toggle()
}
jQuery(document).ready(function(){
    jQuery("#menu-icon-mobile").click(function(){
        jQuery(".nav.navbar-nav.custom-nav.hide-mobile").toggle()
    });
});