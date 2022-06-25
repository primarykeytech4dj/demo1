
/*document.getElementById('shareBtnfb').onclick = function() {
  FB.ui({
    method: 'share',
    display: 'popup',
    href: 'https://developers.facebook.com/docs/',
  }, function(response){});
}*/

window.fbAsyncInit = function() {
    FB.init({
        appId      : '395069404169981',//'248450565574996',//'1058756954135050',
        status     : true,
        cookie     : true,
        xfbml      : true  
    });

    $(document).trigger('fbload');  //  <---- THIS RIGHT HERE TRIGGERS A CUSTOM EVENT CALLED 'fbload'
};
$(document).on('click', '.shareBtnfb', function(event){
	event.preventDefault();
  //alert("hii");
	url = $(this).attr('data-link');
  var mediaId = $(this).attr('data-id');
  var media = $(this).attr('data-media');
  /*$.ajax({
    type: 'POST',
    dataType: 'json',
    url : base_url+'social_media_share/getAppDetails/'+media,
    async: false,
    success: function(res) {*/
      FB.ui({
        method: 'share',
        display: 'popup',
        //version: 'v2.7',
        href: url,
      }, function(response){
        //console.log(response);
          if(response != 'undefined')
          {
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url : base_url+'social_media_share/store_media_response',
              data: {'response':response, 'id':mediaId, 'media':'facebook'},
              success: function(res) {
                console.log(res);
                if(res.share_count >= res.min_count)
                {
                  $('#share_status_'+mediaId).html('<span class="label label-success">Success</span>');
                  $('#share_amount_'+mediaId).removeClass('hidden');

                  if(res.type == 'video') // For video to play once shared
                  {
                    $('#videovisibility_'+mediaId).val('0');
                    $('.vjs-text-track-display').html('');
                    var player = videojs('my_video');
                    player.play();
                  }
                }
              }
            });
          }
        });
      return false;
    //},
  //});
});

$(document).on('click', '#shareBtntweet', function(event){
  event.preventDefault();
  url = $(this).attr('data-link');
  $.support.cors = true;
  var mediaId = $(this).attr('data-id');
  var media = $(this).attr('data-media');
  $.ajax({
    /*type: 'POST',*/
    dataType: 'json',
    url : "https://twitter.com/intent/tweet?url="+url,
    
    success: function(response) {
      //console.log(response);
      $.ajax({
            type: 'POST',
            dataType: 'json',
            url : base_url+'social_media_share/store_media_response',
            data: {'response':response, 'id':mediaId, 'media':'twitter'},
            success: function(res) {
              console.log(res);
              if(res.share_count >= res.min_count)
              {
                $('#share_status_'+mediaId).html('<span class="label label-success">Success</span>');
                $('#share_amount_'+mediaId).removeClass('hidden');

                if(res.type == 'video') // For video to play once shared
                {
                  $('#videovisibility_'+mediaId).val('0');
                  $('.vjs-text-track-display').html('');
                  var player = videojs('my_video');
                  player.play();
                }
              }
            }
          });
    },
    error: function() { alert('Failed!'); },
    beforeSend: setHeader
  
  });
});

function setHeader(xhr) {

  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
}

$(document).on('click', '.popup', function() {
//$('.popup').click(function(event) {
  var shareUrl = $(this).attr('data-link');

    var width  = 575,
        height = 300,
        left   = 5,//($(window).width()  - width)  / 2,
        top    = 5,//($(window).height() - height) / 2,
        shareUrl    = "https://twitter.com/intent/tweet?url="+shareUrl,
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left +
                 ', returnValue='+true;
    var mediaId = $(this).attr('data-id');
    console.log(mediaId);
    //return false;
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url : base_url+'social_media_share/store_media_response',
      data: {'response':'twitter before posting', 'id':mediaId, 'media':'twitter'},
      success: function(res) {
        console.log(res);
        var response = window.open(shareUrl, 'twitter', opts);
        var loop = setInterval(function() {   
            if(response.closed) {  
                clearInterval(loop);
                if(res.share_count >= res.min_count)
                {
                  $('#share_status_'+mediaId).html('<span class="label label-success">Success</span>');
                  $('#share_amount_'+mediaId).removeClass('hidden');

                  if(res.type == 'video') // For video to play once shared
                  {
                    $('#videovisibility_'+mediaId).val('0');
                    $('.vjs-text-track-display').html('');
                    var player = videojs('my_video');
                    player.play();
                  }
                }
            }  
        }, 1000);
        return false;
      }
    });
    
    return false;
  });

function appDetails(media){
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url : base_url+'social_media_share/getAppDetails/'+media,
    data: {'media':media},
    async: false,
    success: function(res) {
      console.log(res);
      //alert(res.status);
      //return res;
      /*if(res.status=='success'){
        return res;
      }else{
        return false;
      }*/
    },
    //error:()
  
  });
}
var linkedInMsg = '';
$(document).on('click', '.linkedin', function(event){
  event.preventDefault();
  //alert("hello");
  linkedInMsg = $(this).attr('data-link');
  //alert(linkedInMsg);
  IN.Event.on(IN, "auth", shareContent);
  //onLinkedInLoad();
})
function onLinkedInLoad() {
  console.log(IN);
  var res = IN.Event.on(IN, "auth", shareContent);
  console.log(res);
}

  // Handle the successful return from the API call
  function onSuccess(data) {
    console.log(data);
  }

  // Handle an error response from the API call
  function onError(error) {
    console.log(error);
  }

  // Use the API call wrapper to share content on LinkedIn
  function shareContent() {
    alert("hello");
    return false;
    // Build the JSON payload containing the content to be shared
    var payload = { 
      "comment": linkedInMsg, 
      "visibility": { 
        "code": "anyone"
      } 
    };

    IN.API.Raw("/people/~/shares?format=json")
      .method("POST")
      .body(JSON.stringify(payload))
      .result(onSuccess)
      .error(onError);
  }
