
/*document.getElementById('shareBtnfb').onclick = function() {
  FB.ui({
    method: 'share',
    display: 'popup',
    href: 'https://developers.facebook.com/docs/',
  }, function(response){});
}*/

window.fbAsyncInit = function() {
    FB.init({
        appId      : '248450565574996',//'1058756954135050',
        status     : true,
        cookie     : true,
        xfbml      : true  
    });

    $(document).trigger('fbload');  //  <---- THIS RIGHT HERE TRIGGERS A CUSTOM EVENT CALLED 'fbload'
};
$(document).on('click', '#shareBtnfb', function(event){
	event.preventDefault();

	url = $(this).attr('data-link');
	console.log(url);
	FB.ui({
    method: 'share',
    display: 'popup',
    href: url,
  }, function(response){
    console.log(response);
    if (response.post_id=="undefined")
    {
      //console.log(response.error);
      alert('Posting error occured');
    }else{
      $.ajax({
          type: 'POST',
          dataType: 'json',
          url : base_url+datalink,
          data: 'params='+value,
          success: function(response) {
            console.log(response);
            $("#"+datatarget).select2('destroy').empty().select2({data : response});
            $(".select2-container").hide();
          }
        
        });
      alert('Success - Post ID: ' + response.post_id);
    }
  	//console.log(response);
  });
	return false;
});

$(document).on('click', '#shareBtntweet', function(event){
  event.preventDefault();
  url = $(this).attr('data-link');
  $.support.cors = true;
  $.ajax({
    /*type: 'POST',*/
    dataType: 'json',
    url : "https://twitter.com/intent/tweet?url="+url,
    
    success: function(response) {
      console.log(response);
      
    },
    error: function() { alert('Failed!'); },
    beforeSend: setHeader
  
  });
});

function setHeader(xhr) {

  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
}

$('.popup').click(function(event) {
  var url = $(this).attr('data-link');
    var width  = 575,
        height = 300,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = url,
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left;
    
    var response = window.open(url, 'twitter', opts);
    console.log(response);
    return false;
  });
