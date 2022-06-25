
</section>
<footer id="footer">
            <!-- <div id="newsletter-container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 clearfix">
                            <h3>Sing up to receive the latest fashion news</h3>
                            <form id="register-newsletter">
                                <input type="text" name="newsletter" required="" placeholder="Enter your email address">
                                <input type="submit" class="btn btn-custom-3" value="SIGN UP">
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <div id="inner-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12 widget">
                            <h3>MY ACCOUNT</h3>
                            <!-- <ul class="links">
                                <li><a href="#">My account</a></li>
                                <li><a href="#">Personal information</a></li>
                                <li><a href="#">Addresses</a></li>
                                <li><a href="#">Discount</a></li>
                                <li><a href="#">Order History</a></li>
                                <li><a href="#">Your Vouchers</a></li>
                            </ul> -->
                            <?php  $menu = $this->pktlib->create_nested_menu(0,3);
                           $sql =  $this->pktlib->createulli($menu,'links');
                           echo $sql; ?>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 widget">
                            <h3>INFORMATION</h3>
                            <?php  $menu = $this->pktlib->create_nested_menu(0,4);
                           $sql =  $this->pktlib->createulli($menu,'links');
                           echo $sql; ?>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 widget">
                            <h3>MY ACCOUNT</h3>
                            <div class="twitter_feed_widget"></div>
                        </div>
                        <div class="clearfix visible-sm"></div>
                        <div class="col-md-3 col-sm-12 col-xs-12 widget">
                            <h3>FACEBOOK LIKE BOX</h3>
                            <div class="facebook-likebox">
                                <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fbloomingcollection&amp;colorscheme=dark&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12 footer-social-links-container">
                            <ul class="social-links clearfix">
                                <li>
                                    <a href="#" class="social-icon icon-facebook"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-twitter"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-rss"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-delicious"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-linkedin"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-flickr"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-skype"></a>
                                </li>
                                <li>
                                    <a href="#" class="social-icon icon-email"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12 footer-text-container">
                            <p>&copy; <?=date('Y')?> Powered by <?=$websiteInfo['company_name']?>&trade;. All Rights Reserved. Developed By <?=anchor('http://www.primarykey.in', 'Primary Key Technologies', ['target'=>'_new'])?></p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </footer>
    </div><a href="#" id="scroll-top" title="Scroll to Top" class="pull-left"><i class="fa fa-angle-up"></i></a>
    <script type="text/javascript">
         $(".check_email").on('blur', function(){
            var value = $(this).val();
            var datalink = $(this).attr('data-link');
            //alert(val);
            //console.log(value);
            //console.log(datalink);
            $.ajax({
              type: 'GET',
              dataType: 'json',
              url : base_url+datalink+'/'+value,
              //data: {'email':+value},
              success: function(response) {
                if(response==false){
                    console.log("hello");
                    $("#show_password").removeClass('hideIt');
                }else{
                    $("#show_password").addClass('hideIt');
                }
                //console.log(response);
                //$("#"+datatarget).select2('destroy').empty().select2({data : response});
                //$(".select2-container").hide();
              }
            });
        });
    </script>
    <script src="<?=assets_url()?>pkt/js/core.js"></script>
    <script src="<?=assets_url()?>pkt/js/cart.js"></script>
    <script src="<?=assets_url()?>venedor/js/main.js"></script>
    
    <script>
        $(function() {
            jQuery("#slider-rev").revolution({
                delay: 5e3,
                startwidth: 1170,
                startheight: 550,
                onHoverStop: "true",
                hideThumbs: 250,
                navigationHAlign: "center",
                navigationVAlign: "bottom",
                navigationHOffset: 0,
                navigationVOffset: 20,
                soloArrowLeftHalign: "left",
                soloArrowLeftValign: "center",
                soloArrowLeftHOffset: 0,
                soloArrowLeftVOffset: 0,
                soloArrowRightHalign: "right",
                soloArrowRightValign: "center",
                soloArrowRightHOffset: 0,
                soloArrowRightVOffset: 0,
                touchenabled: "on",
                stopAtSlide: -1,
                stopAfterLoops: -1,
                dottedOverlay: "none",
                fullWidth: "on",
                spinned: "spinner2",
                shadow: 0,
                hideTimerBar: "on"
            })
        });
    </script>
    <script type="text/javascript">
       
        /*$('#check_email').click(function() {
    var emailVal = $('#check_email').val(); // assuming this is a input text field
    console.log(emailVal);
    $.post('customers/', {'email' : emailVal}, function(data) {
       // alert(data);
    });
});*/
    </script>
    <!-- Modal -->
<div id="socialShare" class="modal fade pull-left" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header share-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Share With Friends</h4>
            </div>
            <div class="modal-body text-center">
                <div class="row">
                    <div class="col-md-4 hidden-lg hidden-md">
                        <div class="icon-wrapper">
                            <a href="#" id="whatsapp"> <i class="fa fa-whatsapp custom-icon mesg_options_icon" id="watsaapLink"><span class="fix-editor">&nbsp;</span></i></a>
                            <p class="share-name">Message</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="icon-wrapper">
                            <a href="#" id="emailLink"> <i class="fa fa-envelope-o custom-icon mail_options_icon" ><span class="fix-editor">&nbsp;</span></i></a>
                            <p class="share-name">Mail</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="icon-wrapper">
                            <a href="javascript:void(0);" id="linkUrl"><i class="fa fa-link custom-icon link_options_icon" ><span class="fix-editor">&nbsp;</span></i></a>
                            <p class="share-name">Link</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="icon-wrapper">
                            <a href="#" id="facebookLink"> <i class="fa fa-facebook-f custom-icon fb_options_icon" ><span class="fix-editor">&nbsp;</span></i></a>
                            <p class="share-name">Facebook</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-wrapper">
                            <a href="#" id="twitterLink"><i class="fa fa-twitter custom-icon twit_options_icon" ><span class="fix-editor">&nbsp;</span></i></a>
                            <p class="share-name">Twitter</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="icon-wrapper">
                            <a href="#" id="linkedinLink"><i class="fa fa-linkedin custom-icon linkedin_options_icon" ><span class="fix-editor">&nbsp;</span></i></a>
                            <p class="share-name">Linkedin</p>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-12 col-xs-12">
                <div class="share_options">
                    <input type="text" class="width" id="showInput" value="" >
                </div>
            </div>
            <div class="share-footer text-center" >
                <a href="javascript:void(0);" data-dismiss="modal">Maybe Later</a>
            </div>
        </div>
    </div>
</div>

<script>
function init() {
    var imgDefer = document.getElementsByTagName('img');
    for (var i=0; i<imgDefer.length; i++) {
    if(imgDefer[i].getAttribute('data-src')) {
    imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
    } } }
    window.onload = init;
</script>
<script>
    jQuery('.share_options').hide();

    jQuery('.link_options_icon').click(function() {
        jQuery('.share_options').show();
    })

    jQuery('.btn-share').click(function() {
        jQuery('.share_options').hide();
    })
</script>
<script type="text/javascript">
    function social_share(link_href, gmail_href, email_href, facebook_href, twitter_href, linkedin_href, whatsapp_href) {

        jQuery('.share_options').hide();

        jQuery('#socialShare').modal('show');

        jQuery('#emailLink').attr('href', email_href);

        jQuery('#facebookLink').attr('href', facebook_href).attr('target','_blank');

        jQuery('#twitterLink').attr('href', twitter_href).attr('target','_blank');

        jQuery('#linkedinLink').attr('href', linkedin_href).attr('target','_blank');

        jQuery('#showInput').attr('value', link_href).attr('target','_blank');

        jQuery('#whatsapp').attr('href', whatsapp_href).attr('target','_blank');



    }

    (function () {
        var options = {
            facebook: "https://www.facebook.com/skillenigma",//"https://www.facebook.com/Abhitakk-News-Community-803709696490211", // Facebook page ID
            whatsapp: "+918767921234", // WhatsApp number
            email: "<?=$websiteInfo['company_name']?>", // Email
            sms: "08767921234", // Sms phone number
            call: "08767921234", // Call phone number
            company_logo_url: "//static.whatshelp.io/img/flag.png", // URL of company logo (png, jpg, gif)
            greeting_message: "Hello, how may we help you? Just send us a message now to get assistance.", // Text of greeting message
            call_to_action: "Message us", // Call to action
            button_color: "#FF6550", // Color of button
            position: "right", // Position may be 'right' or 'left'
            order: "facebook,whatsapp,sms,call,email" // Order of buttons
        };
        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '371746996644849',
      xfbml      : true,
      version    : 'v2.12'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
</body>
<!-- Mirrored from www.portotheme.com/html/venedor/blue/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 Aug 2018 07:23:27 GMT -->

</html>