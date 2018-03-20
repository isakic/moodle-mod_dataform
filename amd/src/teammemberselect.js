define(['jquery'], function($) {
 
    return {
		// We get parameters from initialisation in render.php
        init: function(fieldid, userurl, username, canunsubscribe) {
			
			// After initialisation we loop through all links for subscribe / unsubscribe.
			$( "a.datalynxfield_subscribe" ).each(function () {
				    var href = $(this).attr('href');
				    var params = extract_params(href.split('?')[1]);
				    if (params.fieldid !== fieldid) {
						return;
					}
					params.ajax = true;
            
					$( "a.datalynxfield_subscribe" ).off( "click" );
					$( "a.datalynxfield_subscribe" ).click(function(e) {
						e.preventDefault();
						// var ul = e.target.ancestor().one('ul');
						//var ul = e.target.closest().$('ul');
						var ul = $(this).prev('ul'); 
						console.log(ul);
						if (!ul) {
							$(this).prepend('<ul></ul>');    
						}
						// AJAX call
						var actionurl = 'field/teammemberselect/ajax.php';
						$.ajax(
							{
								method: 'POST',
								url: actionurl,
								data: params,
								context: this,
								dataType: 'json',
								success: function(data) {
									//if (o.responseText === 'true' && e.target.hasClass('subscribed')) {
									if (data && $(this).hasClass('subscribed')) { 
										if (canunsubscribe) {
											$(this).toggleClass('subscribed');
											$(this).prop('title', 'Eintragen'); // TODO: Multilang.
											$(this).prop('innerHTML', 'Eintragen'); // TODO: Multilang.
											params.action = 'subscribe';
											$(this).prop('href', $(this).prop('href').replace('unsubscribe', 'subscribe'));
										}
									remove_user(ul);
									} else if (data && !$(this).hasClass('subscribed')) {
										$(this).toggleClass('subscribed');
										$(this).prop('title', 'Austragen'); // TODO: Multilang.
										$(this).prop('innerHTML', 'Austragen'); // TODO: Multilang.
										params.action = 'unsubscribe';
										$(this).prop('href', $(this).prop('href').replace('subscribe', 'unsubscribe'));
										ul.append('<li><a href=' + userurl + '>' + username + '</a></li>');
									}
								},	
							}
						);
				});
			});

			// This needs optimising.
			function remove_user(listelement) {
				
				var userurlparams = extract_params(userurl.split('?')[1]);
				
				// Loop through jquery object and find all lis.
				var listItems = $( listelement ).find( "li" );
				listItems.find( "a" ).each(function(idx, li) {
					var anchorparams = extract_params($(li).prop('href').split('?')[1]);
					console.log(userurlparams.id + " adfdasf " + anchorparams.id);
					if (userurlparams.id == anchorparams.id) {
						$(li).parent().remove();
					}
				});
				if (listelement.children().length == 0 ) {
					console.log("wir löschen das ganze ul");
					listelement.remove();
				}
			}	

			function extract_params(paramstring) {
				var params = paramstring.split('&');
				var output = {}; // Create an object.
				for (var i = 0; i < params.length; i++) {
					var param = params[i];
					output[param.split('=')[0]] = param.split('=')[1];
				}
				return output;
			}	
        }
    };
});


/*


    require(['jquery'], function($) {
		return {
			
        init: function() {
		
	};
	};
    });
    
    
    
/*


define(['jquery'], function($) {
 
    return {
        init: function() {
		    


				
			};
        
        
    };
};


});
/*

$(document).ready(function(){
    $("p").click(function(){
        $(this).hide();
    });
});
* */
