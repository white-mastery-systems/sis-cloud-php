jQuery("body").queryLoader2({
    barColor: "#3899b5",
    backgroundColor: "#fff",
    percentage: true,
    barHeight: 4,
    completeAnimation: "grow",
    minimumTime: 500,
});
	
jQuery(document).ready(function() {	
"use strict";
jQuery(function() {
            jQuery('.meter_wrapper').appear(function() { 
			jQuery(".meter > span").each(function() {
				jQuery(this)
					.data("origWidth", jQuery(this).width())
					.width(0)
					.animate({
						width: jQuery(this).data("origWidth")
					}, 1200);
			});
			});
		});
		
 /* Count to */
	jQuery(function() {	
		jQuery('.countto').appear(function() {
			jQuery(".number_counter .number_count").each(function() {
				var count_element = jQuery(this).html();
				jQuery(this).countTo({
					from: 0,
					to: count_element,
					speed: 2500,
					refreshInterval: 50,
				});
			});
		});
		});
		
		
/* Pretty photo  */

 jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
		theme: 'pp_default',
		overlay_gallery: false,
		show_title: false,
        social_tools: false,
		hideflash: true
	});
 
/* Testminal */
  
   jQuery(function(){ 
			// Testimonials Setting    
        	jQuery('.testimonials ul,.testimonials_2 ul').cycle({
				timeout: 4000, 
				fx:      'fade', 
				pause:   true,	
				cleartypeNoBg: true, 
				pauseOnPagerHover: 0
        });
		});
		
/* Scale vid */
	
jQuery(".scale_vid").fitVids();	

/*  BG video */

jQuery(".player").mb_YTPlayer();

/* nav div active */
	 
jQuery('.sf-menu').onePageNav({
        begin: function() {
		console.log('start')
		},
		end: function() {
		console.log('stop')
		}
	});
	
/* Supperfish */

jQuery('ul.sf-menu').superfish(
		 {
            delay: 200,
            animation: {
                opacity: "show",
                height: "show"
            },
            speed: "fast",
            autoArrows: false,
            dropShadows: false
        });

/* sticky menu  */
	jQuery(document).scroll(function () {
		var position = jQuery(document).scrollTop();
		var headerHeight = jQuery('#header').outerHeight();
		if (position >= headerHeight - 62){
				jQuery('.main_wrapper_inner .header_top_second').addClass('sticked');
		} else {
				jQuery('.main_wrapper_inner .header_top_second').removeClass('sticked');
		}

	});	
	

/* Testimonials Rotator */
jQuery(function() { 
		/*
		- how to call the plugin:
		$( selector ).cbpQTRotator( [options] );
		- options:
		{
			// default transition speed (ms)
			speed : 700,
			// default transition easing
			easing : 'ease',
			// rotator interval (ms)
			interval : 8000
		}
		- destroy:
		$( selector ).cbpQTRotator( 'destroy' );
		*/

		jQuery( '#cbp-qtrotator' ).cbpQTRotator();

} );

/* smooth scrolling to div */

(function($) {
  $('a[href*=#]:not([href=#])').click(function() 
  {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
        || location.hostname == this.hostname) 
    {
      
      var target = $(this.hash),
      headerHeight = $(".header_top_inner1").height() + 40; // Get fixed header height
            
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
              
      if (target.length) 
      {
        $('html,body').animate({
          scrollTop: target.offset().top - headerHeight
        }, 500);
        return false;
      }
    }
  });
})(jQuery);

	/* Tabs */
	tabsInit();
	
	/* accordion */
	accordion('.accordion-items','.accordion-item','.accordion-item-body','.accordion-item-header a');
	
	/*  Totop plugin   */
	jQuery().UItoTop({
		scrollSpeed: 500,
		easingType: 'easeOutQuart' 
	});
	
	/* Color effect */
	
// Background color animation 
                "use strict"; 
				jQuery("a.button,button").hover(function() {
                jQuery(this).stop().animate({ backgroundColor: "#3899b5" }, 600);
                },function() {
                 jQuery(this).stop().animate({ backgroundColor: "#f1f1f1" }, 400);
                });
				
				jQuery("ul.sf-menu > li").hover(function() {
                jQuery(this).stop().animate({ backgroundColor: "#3899b5" }, 200);
                },function() {
                 jQuery(this).stop().animate({ backgroundColor: "#fff" }, 200);
                });
				
				jQuery("service_dis2").hover(function() {
                jQuery(this).stop().animate({ backgroundColor: "#444" }, 600);
                },function() {
                 jQuery(this).stop().animate({ backgroundColor: "#f0f0f0" }, 400);
                });
				
 // font color animation 
                
				jQuery(".service_wrapper_inner h3 a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#333" }, 500);
                });
				
				jQuery(".service_wrapper_inner3 h4 a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#555" }, 500);
                });
				
				jQuery(".view_title_port h4,.b_wrapper .home_time_wrap_holder h4").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#4d4d4d" }, 500);
                });
				
				jQuery(".team_h_title a h2").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#fff" }, 500);
                });
				
				jQuery(".quote_button_control a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#333" }, 300);
                },function() {
                jQuery(this).animate({ color: "#fff" }, 300);
                });
				
				jQuery(".subfooter .footer_nav a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 200);
                },function() {
                jQuery(this).animate({ color: "#f1f1f1" }, 300);
                });
				
				jQuery(".hire_holder_inner .hire_t1 h1 a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#333" }, 200);
                },function() {
                jQuery(this).animate({ color: "#fff" }, 300);
                });
				
                jQuery(".view_title_port_p_2 h4").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#4d4d4d" }, 400);
                },function() {
                jQuery(this).animate({ color: "#3899b5" }, 500);
                });
				
				jQuery(".widget .categories li a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#555" }, 500);
                });
				
				jQuery(".sub_pb_title a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#555" }, 400);
                },function() {
                jQuery(this).animate({ color: "#3899b5" }, 500);
                });
				
				jQuery(".view_title_port h4, .view_title_port_p h4").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 300);
                },function() {
                jQuery(this).animate({ color: "#4d4d4d" }, 300);
                });
				
				jQuery(".port_inner .text_soft").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#c6c6c6" }, 500);
                });
				
				jQuery(".footer_support_inner a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#333" }, 400);
                },function() {
                jQuery(this).animate({ color: "#eee" }, 500);
                });
				
				jQuery(".port_sing_ti a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#333" }, 500);
                });
				
				jQuery(".blog_title a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#333" }, 500);
                });
				
				jQuery(".blog_post a.readmore_b h6").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#3899b5" }, 400);
                },function() {
                jQuery(this).animate({ color: "#333" }, 500);
                });
				
				jQuery(".date_b_wrapper a").stop().hover(function() {
                jQuery(this).stop().animate({ color: "#fdc53e" }, 400);
                },function() {
                jQuery(this).animate({ color: "#fff" }, 500);
                });
	
// border color animate	
				
				jQuery('.view_title').hover(function() {
	            jQuery(this).animate({ borderBottomColor: "#3899b5" }, '400');
                },function() {
	            jQuery(this).animate({ borderBottomColor: "#e6e6e6" }, '500');
                });
				
				jQuery('.flickr_badge_image img').hover(function() {
	            jQuery(this).animate({ borderColor: "#3899b5" }, '400');
                },function() {
	            jQuery(this).animate({ borderColor: "#e3e3e3" }, '500');
                });
							
  }); /* End */

/**
 * Parallax Scrolling Tutorial
 * For NetTuts+
 *  
 * Author: Mohiuddin Parekh
 *	http://www.mohi.me
 * 	@mohiuddinparekh   
 */
jQuery(document).ready(function() {	 
	// Cache the Window object
	jQuerywindow = jQuery(window);
                
   jQuery('div[data-type="background"]').each(function(){
     var jQuerybgobj = jQuery(this); // assigning the object
                    
      jQuery(window).scroll(function() {
                    
		// Scroll the background at var speed
		// the yPos is a negative value because we're scrolling it UP!								
		var yPos = -(jQuerywindow.scrollTop() / jQuerybgobj.data('speed')); 
		
		// Put together our final background position
		var coords = '50% '+ yPos + 'px';

		// Move the background
		jQuerybgobj.css({ backgroundPosition: coords });
		
}); // window scroll Ends
 });
  
 }); /* End */
 /* 
 * Create HTML5 elements for IE's sake
 */

document.createElement("article");
document.createElement("div"); 

/* isotope */
jQuery(window).load(function () {
	    var $container = jQuery('.portfolio');
	    var $filter = jQuery('#filter');
	    // Initialize isotope 
	    $container.isotope({
	        filter: '*',
	        layoutMode: 'fitRows',
	        animationOptions: {
	            duration: 750,
	            easing: 'linear'
	        }
	    });
	    // Filter items when filter link is clicked
	    $filter.find('a').click(function () {
	        var selector = jQuery(this).attr('data-filter');
	        $filter.find('a').removeClass('current');
	        jQuery(this).addClass('current');
	        $container.isotope({
	            filter: selector,
	            animationOptions: {
	                animationDuration: 750,
	                easing: 'linear',
	                queue: false,
	            }
	        });
	        return false;
	    });	
		
	/* Flexslider */
    jQuery('.flexslider').flexslider({
		touchSwipe: true,     
		controlNav: true,
		slideshow: true,                
		slideshowSpeed: 7000,
		animationDuration: 600, 
		randomize: false, 
		pauseOnAction: true,    
		pauseOnHover: false, 
	});
	
	/* Tooltips Tipsy */
    jQuery('.tool_tipsy').tipsy({gravity: $.fn.tipsy.autoNS, fade:true});
	
	});	
		

/* accordion */
function accordion(b,bb,bbb,a){
	jQuery(a).click(function(e){
		if(jQuery(this).hasClass('active')){
			jQuery(this).removeClass('active').parents(bb).find(bbb).stop(true,true).slideUp(500,'easeOutQuad');
		}
		else{
			jQuery(this).parents(b).find(a+'.active').removeClass('active').parents(bb).find(bbb).stop(true,true).slideUp(500,'easeOutQuad');			
			jQuery(this).addClass('active').parents(bb).find(bbb).stop(true,true).slideDown(500,'easeOutQuad');
		}
		return false;
	});
	jQuery(b).each(function(index, element) {
		ax=jQuery(element).find(a+'.active');
		//if(!ax.size()){ax=jQuery(element).find(a).eq(0).addClass('active');}
		ax.parents(bb).find(bbb).slideDown(0);
	});
}

/* #Site Tabs */

function tabsInit() {
	
	/*
	* Skeleton V1.1
	* Copyright 2011, Dave Gamache
	* www.getskeleton.com
	* Free to use under the MIT license.
	* http://www.opensource.org/licenses/mit-license.php
	* 8/17/2011
	*/
	
	/* Tabs Activiation
	================================================== */

	var tabs = jQuery('ul.tabs');

	tabs.each(function(i) {

		//Get all tabs
		var tab = jQuery(this).find('> li > a');
		tab.click(function(e) {

			//Get Location of tab's content
			var contentLocation = jQuery(this).attr('href');

			//Let go if not a hashed one
			if(contentLocation.charAt(0)==".") {

				e.preventDefault();

				//Make Tab Active
				tab.removeClass('active');
				jQuery(this).addClass('active');

				//Show Tab Content & add active class
				jQuery(contentLocation).show().addClass('active').siblings().hide().removeClass('active');

			}
		});
	});	
}