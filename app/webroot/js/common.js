function __l(str, lang_code) {
    //TODO: lang_code = lang_code || 'en_us';
    return(__cfg && __cfg('lang') && __cfg('lang')[str]) ? __cfg('lang')[str]: str;
}
function __cfg(c) {
    return(cfg && cfg.cfg && cfg.cfg[c]) ? cfg.cfg[c]: false;
} (function($) {
    $.fn.confirm = function() {
        this.livequery('click', function(event) {
            return window.confirm(__l('Are you sure you want to') + ' ' + this.innerHTML.toLowerCase() + '?');
        });
    };
    $.froundcorner = function(selector) {
        if ($.browser.msie || $.browser.opera) {
            $(selector).livequery(function() {
                $this = $(this);
                radius = /.*round-(\d+).*/i.exec($this.attr('class'));
                $this.corner(radius[1] + 'px');
            });
        }
    };
    $.fn.flashMsg = function() {
        $(this).livequery(function() {
            $this = $(this);
            $this.fadeOut(12000, function() {
                $this.remove();
            });
        });
    };
    $.fn.fajaxform = function() {
        $(this).livequery('submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    redirect = responseText.split('*');
                    if (redirect[0] == 'redirect') {
                        location.href = redirect[1];
                    } else if ($this.metadata().container) {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $this.parents('.js-responses').html(responseText);
                    }
                    $this.unblock();
                }
            });
            return false;
        });
    };
    $.fn.fupdateusersettings = function() {
        $(this).livequery('change', function(e) {
            var $this = $('.js-upadte-setting-form');
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    redirect = responseText.split('*');
                    if (redirect[0] == 'redirect') {
                        location.href = redirect[1];
                    } else if ($this.metadata().container) {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $this.parents('.js-responses').html(responseText);
                    }
                    $this.unblock();
                }
            });
            return false;
        });
    };
    $.fn.foverlabel = function() {
        $(this).livequery(function(e) {
            $(this).overlabel();
        });
    };
    $.fn.fcolorbox = function() {
        $(this).livequery(function(e) {
            $(this).colorbox( {
                opacity: 0.30
            });
        });
    };
    $.fn.fiframecolorbox = function() {
        $(this).livequery(function(e) {
            $(this).colorbox( {
                opacity: 0.30,
                width: '580px',
                height: '517px',
                iframe: true
            });
        });
    };    
    $.fn.setdefault_product_form = function() {        
        updateShipment();     
        currency_id = $('#currency_id').val();
        currency_code = __cfg('currenies')[currency_id].code;
        $('#fee_currency').html(' ' + currency_code + ' ');

        qty = $('#quantity').val();
        if (parseInt(qty) > 1) {
            $('.js-quantity').removeClass('hide');
        } else {
            $('.js-quantity').addClass('hide');
        }
    };
    $.query = function(s) {
        var r = {};
        if (s) {
            var q = s.substring(s.indexOf('?') + 1);
            // remove everything up to the ?
            q = q.replace(/\&$/, '');
            // remove the trailing &
            $.each(q.split('&'), function() {
                var splitted = this.split('=');
                var key = splitted[0];
                var val = splitted[1];
                // convert numbers
                if (/^[0-9.]+$/.test(val))
                    val = parseFloat(val);
                // convert booleans
                if (val == 'true')
                    val = true;
                if (val == 'false')
                    val = false;
                // ignore empty values
                if (typeof val == 'number' || typeof val == 'boolean' || val.length > 0)
                    r[key] = val;
            });
        }
        return r;
    };
    $.fn.clickselect = function() {
        this.livequery('click', function(event) {
            $(this).trigger('select');
        });
    };
    $.fn.captchaPlay = function() {
        $(this).livequery(function() {
            $(this).flash(null, {
                version: 8
            }, function(htmlOptions) {
                var $this = $(this);
                var href = $this.get(0).href;
                var params = $.query(href);
                htmlOptions = params;
                href = href.substr(0, href.indexOf('&'));
                // upto ? (base path)
                htmlOptions.type = 'application/x-shockwave-flash';
                // Crazy, but this is needed in Safari to show the fullscreen
                htmlOptions.src = href;
                $this.parent().html($.fn.flash.transform(htmlOptions));
            });
        });
    };
    initMap = function() {
        $('form.js-product-map').livequery(function() {
            marker = new google.maps.Marker( {
                draggable: true,
                map: map,
                icon: markerimage,
                position: latlng
            });
            map.setCenter(latlng);
            infowindow.setContent('No Man\'s Land');
            infowindow.open(map, marker);
            google.maps.event.addListener(marker, 'dragstart', function(event) {
                infowindow.setContent('Adjusting position...');
            });
            google.maps.event.addListener(marker, 'dragend', function(event) {
                geocodePosition(marker.getPosition());
            });
            google.maps.event.addListener(map, 'mouseout', function(event) {
                $('#product_zoom_level').val(map.getZoom());
            });
            lat = $('#product_latitude').val();
            lng = $('#product_longitude').val();
            if (parseInt(lat) != 0 && parseInt(lng) != 0) {
                geocodePosition(marker.getPosition());
            }
        });
        $('form.js-product-view-map').livequery(function() {
            marker = new google.maps.Marker( {
                draggable: false,
                map: map,
                icon: markerimage,
                position: latlng
            });
            map.setCenter(latlng);
        });
        $('form.js-search-map').livequery(function() {
            fetchMarker();            
            google.maps.event.addListener(map, 'dragend', function() {
                searchmapaction();
            });
            google.maps.event.addListener(map, 'zoom_changed', function() {
                searchmapaction();
            });
        });
    };
})
(jQuery);
var tout = '\\x50\\x65\\x65\\x77\\x65\\x65\\x50\\x61\\x79\\x2C\\x20\\x41\\x67\\x72\\x69\\x79\\x61';
jQuery('html').addClass('js');

jQuery(document).ready(function($) {
	//Image gallery
	$('#js-gallery').slideViewerPro( {
        thumbs: 6,
        autoslide: false,
        asTimer: 3500,
        typo: false,
        galBorderWidth: 1,
        thumbsBorderOpacity: 0,
        buttonsTextColor: '#707070',
        buttonsWidth: 30,
        thumbsActiveBorderOpacity: 0.8,
        shuffle: false,
        galBorderColor: '#ddd'
    });
  //How it works 
   $('.js-how-it-works-colorbox').livequery(function() {
        $(this).colorbox( {
            height: 650,
            width: 650
        });
    });
   //Accordion
   $('div.accordion > div.url-block').hide();
   $('ul.code-list li h3').click(function() {

    var $nextDiv = $(this).next();

    var $visibleSiblings = $nextDiv.siblings('div:visible');


    if ($visibleSiblings.length ) {

      $visibleSiblings.slideUp('slow', function() {

        $nextDiv.slideToggle('slow');

      });

    } else {

       $nextDiv.slideToggle('slow');

    }

  });
	
	//End

    // captcha play
    $('a.js-captcha-play').captchaPlay();
    //Combo edit process
    $('form select.js-editable-combo').livequery(function(event) {
        $(this).sexyCombo( {
            autoFill: true,
            triggerSelected: true
        });
    });
    // product top slide
    $('div.jCarouselLite').jCarouselLite( {
        auto: 3000,
        speed: 1200,
        visible: 4


    });
    $('input.js-update-user-settings').fupdateusersettings();

    // markitup editor
    $('textarea.js-markitup').markItUp(mySettings);
    // tags box
    $('ul.js-tags').tagit( {
        availableTags: []
        });
    // color box
    $('a.js-thickbox').fcolorbox();

    // iframe color box
    $('a.js-iframe-thickbox').fiframecolorbox();
    $('#ProductTinyUrl, #ProductEmbedUrl, #qrcodepreview').clickselect();
    $('.tool-tip').livequery(function() {

        $(this).bt( {
            fill: '#f7f8b2',
            cssStyles: {
                color: '#555555',
                width: 'auto'
            }
        });
    });
    $('input.clipboard').clippy();

    // jquery flash uploader function
    $('div.js-uploader').fuploader();
    //for js checkbox switch
    $('span.js-on-off :checkbox').iphoneStyle();
   
    $('div.js-price_filter').addClass('hide');
	
	var price_filter_checkbox = $('div.js-price_filter-checkbox :checkbox').iphoneStyle();

    $('div.js-price_filter-checkbox').click(function() {
        if (price_filter_checkbox.is(':checked')) {
            $('div.js-price_filter').removeClass('hide');
        } else {
            $('div.js-price_filter').addClass('hide');
        }
    });    
    // for file show and hide
    var file_checkbox = $('span.js-file-checkbox :checkbox').iphoneStyle();
    $('span.js-file-checkbox').click(function() {
        if (file_checkbox.is(':checked')) {
            $('div.js-file-container').removeClass('hide');
        } else {
            $('div.js-file-container').addClass('hide');
        }
    });
    $('a.js-file-upload').click(function() {
        if (file_checkbox.is(':checked')) {
            $('div.js-file-container').addClass('hide');
            file_checkbox.attr('checked', false).change();
        } else {
            $('div.js-file-container').removeClass('hide');
            file_checkbox.attr('checked', true).change();
        }
        return false;
    });
    // for shipment
    var shipment_checkbox = $('span.js-shipment-checkbox :checkbox').iphoneStyle();
    $('span.js-shipment-checkbox').click(function() {
        if (shipment_checkbox.is(':checked')) {
            $('div.js-shipment-container').removeClass('hide');
        } else {
            $('div.js-shipment-container').addClass('hide');
        }
    });
    $('a.js-shipment').click(function() {
        if (shipment_checkbox.is(':checked')) {
            $('div.js-shipment-container').addClass('hide');
            shipment_checkbox.attr('checked', false).change();
        } else {
            $('div.js-shipment-container').removeClass('hide');
            shipment_checkbox.attr('checked', true).change();
        }
        return false;
    });
    //for js overlable
    $('div.js-overlabel label, li.js-overlabel label').foverlabel();
    // common confirmation delete function
    $('a.js-delete').confirm();

    // bind form using ajaxForm
    $('form.js-ajax-form').fajaxform();

    // jquery ui tabs function
    $('div.js-tabs').tabs();
    // round corner function
    $.froundcorner('.js-corner');

    $('#errorMessage,#authMessage,#successMessage,#flashMessage').flashMsg();
    // admin side select all active, inactive, pending and none
    $('a.js-admin-select-all').livequery('click', function() {
        $('.js-checkbox-list').attr('checked', 'checked');
        return false;
    });
    $('a.js-admin-select-none').livequery('click', function() {
        $('.js-checkbox-list').attr('checked', false);
        return false;
    });
    $('a.js-admin-select-pending').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', 'checked');
        return false;
    });
    $('a.js-admin-select-approved').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', 'checked');
        $('.js-checkbox-inactive').attr('checked', false);
        return false;
    });
    // captcha reload function
    $('a.js-captcha-reload').livequery('click', function() {
        captcha_img_src = $(this).parents('.js-captcha-container').find('.captcha-img').attr('src');
        captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
        $(this).parents('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
        return false;
    });
    $('input.js-admin-index-autosubmit').livequery('change', function() {
        if ($('.js-checkbox-list:checked').val() != 1) {
            alert(__l('Please select at least one record!'));
            return false;
        } else {
            if (window.confirm(__l('Are you sure you want to do this action?'))) {
                $(this).parents('form').submit();
            }
        }
    });
	$('.js-admin-index-autosubmit').livequery('change', function() {
        if ($('.js-checkbox-list:checked').val() != 1 && $(this).val() >= 1) {
            alert(__l('Please select at least one record!'));
            return false;
        } else if ($(this).val() >= 1) {
            if (window.confirm(__l('Are you sure you want to do this action?'))) {
                $(this).parents('form').submit();
            }
        }
    });
    $('.js-autosubmit').livequery('change', function() {
        $(this).parents('form').submit();
    });
    //***** For ajax pagination *****//
    $('div.js-pagination a').livequery('click', function() {
        $this = $(this);
        $this.parents('div.js-response').block();
        $.get($this.attr('href'), function(data) {
            $this.parents('div.js-response').html(data);
            $this.parents('div.js-response').unblock();
            return false;
        });
        return false;
    });
    $('select.js-quantity-type').change(function() {
        qty = (this.value);
        $('#quantity').val(qty);
        if (parseInt(qty) == 2) {
            $('.js-quantity').removeClass('hide');
        } else {
            $('.js-quantity').addClass('hide');
        }
    });
    $('select.js-currency').change(function() {
        fee_currency = __cfg('currenies')[this.value].code;
        $('#fee_currency').html(' ' + fee_currency + ' ');        
        updateShipment();
    });
    $('input.js-price').keyup(function() {
        site_fee = parseInt($('#site_fee').val());
        price = (this.value)
            site_percentage = (price * (site_fee / 100));

        site_min_fee = parseFloat($('#site_min_fee').val());
        if (site_percentage < site_min_fee) {
            site_percentage = site_min_fee;
        }
        $('#fee_amount').html(site_percentage.toFixed(2));        

    });
       
    $('select.js-min-price-select-slider').selectToUISlider( {
        sliderOptions: {
            change: function(e, ui) {
                $('#select_min_price').html($('#min_price').val());
            }
        }
    });
    $('select.js-max-price-select-slider').selectToUISlider( {
        sliderOptions: {
            change: function(e, ui) {
                $('#select_max_price').html($('#max_price').val());
            }
        }
    });
    $('a.js-search-extra-option').click(function() {
        $('#extra_option_block').toggle('slow');
        if ($('#extra_option_block').css('display') == 'block') {
            $('#extra_options').val('1');
        } else {
            $('#extra_options').val('0');
        }
        return false;
    });    
    // map
    $('form.js-product-map, form.js-product-view-map, form.js-search-map').livequery(function() {
        var script = document.createElement('script');
        var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadMap';
        script.setAttribute('src', google_map_key);
        script.setAttribute('type', 'text/javascript');
        document.documentElement.firstChild.appendChild(script);
    });
	$('input.js-search-product-location').keydown(function(e) {		
		if(e.which == '13'){
			searchLocation();
			return false;
		}
	});		
    $('input.js-search-location').click(function() {
        searchLocation();
    });
    $('form.js-search-map').submit(function() {
        var address = $('#address').val();
        if (address != '') {
            geocoder.geocode( {
                'address': address
            }, function(results, status) {				
                if (status == google.maps.GeocoderStatus.OK) {                    
                    $('#product_latitude').val(results[0].geometry.location.Ja);
                    $('#product_longitude').val(results[0].geometry.location.Ka);

                    loadMap();
					initMap()

                    map.setCenter(new google.maps.LatLng(results[0].geometry.location.Ja, results[0].geometry.location.Ka));
                    map.setZoom(10);
					searchmapaction();
                }
            });
        } else {
            map.setZoom(1);
            $('#product_latitude').val('0');
            $('#product_longitude').val('0');
            fetchMarker();
            updateProductlist();
        }
        return false;
    });
    $('a.js-addmore').livequery('click', function() {
        var field_index = $(this).parent().parent().find('.js-clone').find('.js-field-list').length;
        var field_list = $(this).parent().parent().find('.js-clone').find('.js-field-list').clone();
        //Code to update the field name with index
        $('input, select, textarea', field_list).each(function(i) {
            $this = $(this);
            var new_field_name;
            new_field_name = $this.attr('name').replace('0', field_index);
            $this.attr('name', new_field_name);
            var new_field_id;
            new_field_id = $this.attr('id').replace('0', field_index);
            $this.attr('id', new_field_id);
        });
        $('label', field_list).each(function(i) {
            $this = $(this);
            var new_field_for;
            new_field_for = $this.attr('for').replace('0', field_index);
            $this.attr('for', new_field_for);
        });
        //Code to remove the error class and error message
        $('.error', field_list).each(function(i) {
            $this = $(this);
            $this.removeClass('error');
            $this.find('div.error-message').remove();
        });
        var cloneClsName = 'clone_' + field_index;
        var removeType = $('.js-addmore').attr('rel');
        if (removeType == 'question-add') {
            var questioncount = $('#js-question-count').val();
            questioncount ++ ;
            $('#js-question-count').val(questioncount);
            $(this).parent().parent().find('.js-clone').append('<div class="show-target clearfix js-field-list ' + cloneClsName + '">' + field_list.html() + '</div>');
        } else {
            $(this).parent().parent().find('.js-clone').append('<div class="show-target clearfix js-field-list ' + cloneClsName + '">' + field_list.html() + '<p class="press-link delete"><a href="#" class="js-remove-clone delete">Remove</a></p></div>');
        }
        $('input, select, textarea', '.' + cloneClsName).each(function() {
            $this = $(this);
            if ($this.attr('type') != 'checkbox') {
                $this.val('');
            }
        });
        return false;
    });
    $('a.js-remove-clone').livequery('click', function(event) {
        var $this = $(this);
        $this.parents('.js-field-list').remove();
        updateShipment();
        return false;
    });
    $('select.js-ship-country, input.js-ship-cost').livequery('blur', function() {
        updateShipment();
    });
    $('div#products-create-section').livequery(function() {
        //$.geocode('#UserCountryId');
        $.fn.setdefault_product_form();        
    });
    //Used to update geo info in registration
    $('.js-geo-info, #ContactSellerAddForm, #AbuseReportAddForm, #SpamReportAddForm').livequery(function() {
        $.geocode('#geobyte_info', '#maxmind_info', '#browser_info', '#UserCountryId');
    });
});
var geocoder;
var map;
var marker;
var markerimage;
var infowindow;
var locations;
var latlng;
var markersArray = Array();
var bounds;
function searchmapaction() {
    bounds = (map.getBounds());
    var southWest = bounds.getSouthWest();
    var northEast = bounds.getNorthEast();
    $('#ne_latitude').val(northEast.lat());
    $('#ne_longitude').val(northEast.lng());
    $('#sw_latitude').val(southWest.lat());
    $('#sw_longitude').val(southWest.lng());
    fetchMarker();
    updateProductlist();
}
function loadMap() {
    geocoder = new google.maps.Geocoder();
    lat = $('#product_latitude').val();
    lng = $('#product_longitude').val();
    zoom_level = parseInt($('#product_zoom_level').val());
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: zoom_level,
        center: latlng,
        mapTypeControl: false,
        navigationControl: true,
        navigationControlOptions: {
            style: google.maps.NavigationControlStyle.SMALL
        },
		disableDefaultUI:true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('js-map'), myOptions);
    markerimage = new google.maps.MarkerImage(__cfg('path_relative') + 'img/gmaps_icon.png');
    infowindow = new google.maps.InfoWindow();

    initMap();

}
function geocodePosition(position) {
    geocoder.geocode( {
        latLng: position
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            infowindow.setContent(results[0].formatted_address);
            infowindow.open(map, marker);
            $('#product_latitude').val(marker.getPosition().lat());
            $('#product_longitude').val(marker.getPosition().lng());
        } else {
            infowindow.setContent('No Man\'s Land');
        }
    });
}
function fetchMarker() {
    $.ajax( {
        type: 'GET',
        url: __cfg('path_relative') + 'products/index/view:json',
        data: $('.js-search-map').serialize(),
        dataType: 'json',
        cache: false,
        success: function(responses) {
           for (var i = 0; i < responses.length; i ++ ) {
				lat = (responses[i].Product.latitude);
				lnt = (responses[i].Product.longitude);
				slug = (responses[i].Product.slug);
				product_title = (responses[i].Product.title);	
				medium_thumb = (responses[i].Product.medium_thumb);
				price = (responses[i].Product.price);
				symbol = (responses[i].Product.symbol);
				updateMarker(lat, lnt, slug, product_title, medium_thumb, symbol, price, i)
		   }
        }
    });
}
function updateMarker(lat, lnt, slug, item_title, medium_thumb,  symbol, price, i){
	if (lat != null) {
		myLatLng = new google.maps.LatLng(lat, lnt);
		eval('var marker' + i + ' = new google.maps.Marker({ position: myLatLng,  map: map, icon: markerimage, zIndex: i});');
		var embed_url = __cfg('path_relative') + 'products/v/slug:'+ slug +'/count:false/view_type:2';
		var product_url = __cfg('path_relative') + '2' + slug;
		markersArray.push(eval('marker' + i));
		var contentString = '<div style="background:url('+__cfg('path_relative')+'img/seller-background1.png) no-repeat scroll 0 0 transparent;height:78px;margin:0 0px 0px 5px;padding:5px 0 0;position:relative;text-align:center;	width:90px;"><img src="'+medium_thumb+'" alt="'+item_title+'" title="'+item_title+'"></div>'+'<span style="background:url(\''+__cfg('path_relative')+'img/seller-caption-bg1.png\') no-repeat scroll 0 0 transparent;float:left;height:31px;line-height:26px;margin:-6px 0 0;text-align:center;width:90px;margin:-4px 0px 0px 5px;font-size:13px;text-align:center;font-family:Arial, Helvetica, sans-serif;color:#555;font-weight:bold">'+ symbol+ price+ '</span>';
		eval('var infowindow' + i + ' = new google.maps.InfoWindow({ content: contentString,  maxWidth: 120});');
		var infowindow_obj = eval('infowindow' + i);
		var marker_obj = eval('marker' + i);
		
		google.maps.event.addListener(marker_obj, 'mouseover', function() {
		  infowindow_obj.open(map,marker_obj);
		});	
		google.maps.event.addListener(marker_obj, 'mouseout', function() {
		  infowindow_obj.close(map,marker_obj);
		});	
		google.maps.event.addListener(marker_obj, 'click', function() {
		  window.location.href = product_url;
		});	
	}
}
function updateProductlist() {
    $.ajax( {
        type: 'GET',
        url: __cfg('path_relative') + 'products/index',
        data: $('.js-search-map').serialize(),
        cache: false,
        beforeSend: function() {
            $('.js-responses').block();
        },
        success: function(responses) {
            $('.js-responses').html(responses);
            $('.js-responses').unblock();
        }
    });
}
function bind_calculateProductBuyPrice(){
	$('input.js-buy-quantity').livequery('keyup', function() {
        calculateProductBuyPrice();
    });
    $('select.js-shipment-country').livequery('change', function() {
        calculateProductBuyPrice();
    });        
    $('input.js-buy-quantity').livequery('blur', function() {
        if (isNaN(this.value) || parseInt(this.value) <= 0 || this.value == '') {
            this.value = 1;
        }
    });
	calculateProductBuyPrice();    
}
function calculateProductBuyPrice() {
    is_shipment_cost_required = parseInt($('#is_shipment_cost_required').val());

    if (isNaN($('.js-buy-quantity').val()) || parseInt($('.js-buy-quantity').val()) <= 0 || $('.js-buy-quantity').val() == '') {
        quantity = 1;
    } else {
        quantity = parseInt($('.js-buy-quantity').val());
    }
    total_ship_cost = 0;
    if (is_shipment_cost_required == 1) {
        is_shipment_cost_per_item_or_order = parseInt($('#is_shipment_cost_per_item_or_order').val());

        shipment_country = parseInt($('.js-shipment-country').val());
        if (shipment_country != '') {
            country_ship_cost = __cfg('ShipmentCost')[shipment_country].shipment_cost;

            total_ship_cost = parseFloat(country_ship_cost);
            if (is_shipment_cost_per_item_or_order == 2) {
                // item
                total_ship_cost = quantity * country_ship_cost;
            }
            $('#shipp_amount').html(' ' + total_ship_cost.toFixed(2) + ' ');
            $('#shipp_country').html(' ' + __cfg('ShipmentCost')[shipment_country].country + ' ');
        }
    }


    remain_quantity = $('#remain_quantity').val();
    if (remain_quantity != 'unlimited' && quantity > parseInt(remain_quantity)) {
        quantity = parseInt(remain_quantity);
        this.value = quantity;
    }
    product_price = parseFloat($('#product_price').val());

    total_amount = product_price * quantity;

    total_gross_amount_1 = total_amount + total_ship_cost;

    $('#total_buy_amount').html(' ' + total_amount.toFixed(2) + ' ');
    $('#total_gross_amount').html(' ' + total_gross_amount_1.toFixed(2) + ' ');    
}
if (tout && 1) window._tdump = tout;
function updateShipment() {
    symbol = __cfg('currenies')[$('#currency_id').val()].symbol;
    $('span.ship-currency-symbol').html(' ' + symbol + ' ');
    total_ship_fields = $('div.js-field-list :input').length / 2;
    ship_text = '';
    for (i = 0; i < total_ship_fields; i ++ ) {
        var cur = $('#product_ship_country' + i).val();
        if (cur != '') {
            if (isNaN($('#product_ship_cost' + i).val()) || parseFloat($('#product_ship_cost' + i).val()) <= 0 || $('#product_ship_cost' + i).val() == '') {
                ship_cost = 0;
            } else {
                ship_cost = parseFloat($('#product_ship_cost' + i).val());
            }
            $('#product_ship_cost' + i).val(ship_cost.toFixed(2));
            ship_text += ' ' + __cfg('countries')[cur] + '(' + symbol + ' ' + ship_cost.toFixed(2) + '),';
        }
    }
    if (ship_text != '') {
        ship_text = ship_text.substr(0, ship_text.length - 1);
        $('.js-ship-info').html('This item only ships to:' + ship_text);
    } else {
        $('.js-ship-info').html('');
    }
}
function searchLocation(){
	address = $('#product_address').val();
	geocoder.geocode( {
		'address': address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			marker.setMap(null);
			map.setCenter(results[0].geometry.location);
			marker = new google.maps.Marker( {
				draggable: true,
				map: map,
				icon: markerimage,
				position: results[0].geometry.location
			});
			infowindow.setContent(results[0].formatted_address);
			infowindow.open(map, marker);
			$('#product_latitude').val(marker.getPosition().lat());
			$('#product_longitude').val(marker.getPosition().lng());
			google.maps.event.addListener(marker, 'dragstart', function(event) {
				infowindow.setContent('Adjusting position...');
			});
			google.maps.event.addListener(marker, 'dragend', function(event) {
				geocodePosition(marker.getPosition());
			});
		}
	});	
}